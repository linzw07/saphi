<?php

/**
 * Data Helper
 *
 * @category   Webinse
 * @package    Webinse_DailyDeals
 * @author     Webinse Team <info@webinse.com.com>
 */
class Webinse_DailyDeals_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Retrieve current time
     *
     * @return string
     */
    public function getCurrentTime()
    {
        return date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
    }

    /**
     * Check if product's deal is enabled
     *
     * @param Mage_Catalog_Model_Product $product
     * @return boolean
     */
    public function isDealEnabled(Mage_Catalog_Model_Product $product)
    {
        if (!$product->getDealStatus()) {
            return false;
        } else {
            $dealStatus = $this->checkUpdateDealStatus($product);
            if ($dealStatus == 'running') {
                return true;
            }
            return false;
        }
    }

    /**
     * Selects the products status 'running' in collection
     * @param $collection
     * @return mixed
     */
    public function filterRunningWithProductCollection($collection)
    {
        $collection->addAttributeToFilter('deal_statuses', array("eq" => 'running'));
        return $collection;
    }

    /**
     * @param $iterator
     * @return bool|string
     */
    public function countDays($iterator)
    {
        return date('Y-m-d H:i:s', strtotime(Mage::helper('dailydeals')->getCurrentTime() . ' + ' . $iterator . ' day'));
    }

    /**
     * Returns the current status
     * @param $item
     * @return string
     */
    public function checkUpdateDealStatus($item)
    {
        $startTime = date('Y-m-d', strtotime($item->getDealStartTime()));
        $endTime = '';
        if ($item->getDealEndTime()) {
            $endTime = date('Y-m-d', strtotime($item->getDealEndTime()));
        }
        $res = '';
        if (($item->getDealStatus()) && $item->getDealStatuses() == 'Disabled') {
            $res = $item->getDealStatuses();
        } elseif (($endTime == null && $startTime > $this->getCurrentTime()) || $this->getCurrentTime() < $startTime) {
            $res = 'pending';
        } elseif (($this->getCurrentTime() > $endTime) || ($item->getDealQty() <= 0)) {
            $res = 'ended';
        } elseif ((!$endTime) || ($endTime > $this->getCurrentTime() && $startTime < $this->getCurrentTime())) {
            $res = 'running';
        }
        return $res;
    }

    /**
     * Changes the special data on deals_data
     * @param $product
     * @param $status
     */
    public function changeSpecialData($product, $status)
    {
        $oldSpecialPrice = $product->getOldSpecialPrice();
        if (!$oldSpecialPrice) {

            if ($status == 'running') {
                $specialData = $this->_setOldSpecialData($product);
                if ($specialData) {
                    $product->addData($specialData);
                    $product->addData($this->_getDealData($product));
                    $product->setDealStatuses($status);

                } else {
                    $product->addData($this->_getDealData($product));
                    $oldDataDash = array(
                        'old_special_price' => '-',
                    );
                    $product->addData($oldDataDash);
                    $product->addData($this->_getDealData($product));
                    $product->setDealStatuses($status);
                }
            }
        } else {
            if ($status == 'running') {
                $product->addData($this->_getDealData($product));
            } elseif ($status == 'ended') {
                $this->returnSpecialDataWhenStatusesDisabled($product);
            }
        }
    }

    /**
     * return special_data when deal_statuses : disabled, remove or ended
     * @param $product
     */
    public function returnSpecialDataWhenStatusesDisabled($product)
    {
        if ($product->getOldSpecialPrice() == '-') {
            $product->addData($this->_returnOldData($product, true));
        } else {
            $product->addData($this->_returnOldData($product));
        }


    }

    /**
     * set deal_data in special_data
     * @param $product
     * @return array
     */
    public function _getDealData($product)
    {
        $dealData = array(
            'special_from_date' => $product->getDealStartTime(),
            'special_to_date' => $product->getDealEndTime(),
            'special_price' => $product->getDealPrice(),
        );
        return $dealData;
    }


    /**
     * Gets old special data
     * @param $product
     * @return array|bool
     */
    protected function _setOldSpecialData($product)
    {
        $specialDateFrom = date('Y-m-d', strtotime($product->getSpecialFromDate()));
        $specialDateTo = '';
        if ($product->getSpecialToDate()) {
            $specialDateTo = date('Y-m-d', strtotime($product->getDealEndTime()));
        }

        if (($product->getSpecialFromDate()) || ($product->getSpecialToDate()) || ($product->getSpecialPrice())) {
            if (($specialDateFrom != $product->getDealStartTime()) &&
                ($specialDateTo != $product->getDealEndTime()) &&
                ($product->getSpecialPrice() != $product->getDealPrice())
            ) {
                $oldData = array(
                    'old_special_date_from' => $product->getSpecialFromDate(),
                    'old_special_date_to' => $product->getSpecialToDate(),
                    'old_special_price' => $product->getSpecialPrice(),
                );
                return $oldData;
            }
        }
        return false;
    }

    /**
     * @param $product
     * @return array
     */
    public function _returnOldData($product, $emptyData = false)
    {

        if ($emptyData == false) {
            $returnOldData = array(
                'special_from_date' => $product->getOldSpecialDateFrom(),
                'special_to_date' => $product->getOldSpecialDateTo(),
                'special_price' => $product->getOldSpecialPrice(),
                'old_special_date_from' => null,
                'old_special_date_to' => null,
                'old_special_price' => null,
            );
            return $returnOldData;
        } else {

            $emptyData = array(
                'special_from_date' => null,
                'special_to_date' => null,
                'special_price' => null,
                'old_special_date_from' => null,
                'old_special_date_to' => null,
                'old_special_price' => null,
            );
            return $emptyData;
        }
    }

    /**
     * return params for field
     * @param $product
     * @param null $id
     * @return string
     */
    public function returnProductQtyFieldParams($product, $id = null)
    {
        //use in custom tab
        if ($product) {
            $qty = (int)$product->getData('stock_item')->getQty();
            if (!$qty) {
                return '<input id="deal_qty" class="input-text disabled" type="text"
                                   value="" name="deal_qty" disabled>';
            } else {
                return '<input id="deal_qty" class="input-text input-text " type="text"
                                   value="'.$product->getDealQty().'" name="deal_qty">';
            }
        }
        //use in render form
        if ($id) {
            $productModel = Mage::getModel('catalog/product')->load($id);
            $qty = (int)$productModel->getData('stock_item')->getQty();
            if (!$qty) {
                return 'input-text validate-number disabled';
            } else {
                return 'require-entry validate-digits-range digits-range-1-' . $qty;
            }
        }
    }

	/**
	 * Convert dates in array from localized to internal format
	 *
	 * @param   array $array
	 * @param   array $dateFields
	 * @return  array
	 */
	public function _filterDates($array, $dateFields)
	{
		if (empty($dateFields)) {
			return $array;
		}
		$filterInput = new Zend_Filter_LocalizedToNormalized(array(
			'date_format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
		));
		$filterInternal = new Zend_Filter_NormalizedToLocalized(array(
			'date_format' => Varien_Date::DATE_INTERNAL_FORMAT
		));

		foreach ($dateFields as $dateField) {
			if (array_key_exists($dateField, $array) && !empty($dateField)) {
				$array[$dateField] = $filterInput->filter($array[$dateField]);
				$array[$dateField] = $filterInternal->filter($array[$dateField]);
			}
		}
		return $array;
	}


    /**
     * @return mixed
     */
    public function getWidgetTitle()
    {
        $collection = Mage::getModel('widget/widget_instance');
        $data = $collection->getCollection()->addFieldToFilter('instance_type', array('dailydeals/widget'));
        foreach($data as $value)
        {
            return $value->getData('title');
        }
    }
}