<?php

/**
 * Observer Model
 *
 * @category   Webinse
 * @package    Webinse_DailyDeals
 * @author     Webinse Team <info@webinse.com.com>
 */
class Webinse_DailyDeals_Model_Observer
{

    /**
     * Check status product when data save
     *
     * @param Varien_Event_Observer $observer
     */
    public function catalogProductSaveBefore(Varien_Event_Observer $observer)
    {
        $customFieldValue = $this->_getRequest()->getPost();
        $product = $observer->getProduct();
        $helper = Mage::helper('dailydeals');
        if (!empty($customFieldValue['deal_status'])) {
            $setDealStatus = true;
            $setDealStatuses = null;
            if ($customFieldValue['deal_status'] == 'Disabled') {
                $setDealStatuses = 'Disabled';
            }

	        //convert date from locale to international format
	        $customFieldValue = $helper->_filterDates($customFieldValue, array('deal_start_time', 'deal_end_time'));
            $product->addData(array(
                'deal_status' => $setDealStatus,
                'deal_statuses' => $setDealStatuses,
                'deal_price' => $customFieldValue['deal_price'],
                'deal_qty' => $customFieldValue['deal_qty'],
                'deal_start_time' => $customFieldValue['deal_start_time'],
                'deal_end_time' => $customFieldValue['deal_end_time']
            ));
        }
        if ((($product->getDealStatuses() == 'Disabled')) && ($product->getDealPrice())) {
            /***
             * If deal disabled
             */
            $helper->returnSpecialDataWhenStatusesDisabled($product);
        }
    }

    /**
     * Check deal_status when using product collection
     *
     * @param Varien_Event_Observer $observer
     */
    public function catalogBlockProductListCollection(Varien_Event_Observer $observer) {
	    $observer->getCollection()->addAttributeToSelect('*');
    }

    /**
     * Output notice in catalog_product
     *
     * @param Varien_Event_Observer $observer
     * @return Mage_Core_Model_Session_Abstract
     */
    public function catalogProductEditFormRenderRecurring(Varien_Event_Observer $observer)
    {
        $product = $observer->getProduct();
        if ($product->getDealStatuses() == 'running') {
            if (($product->getOldSpecialDateFrom()) || ($product->getOldSpecialDateTo()) || ($product->getOldSpecialPrice() != '-')) {
                return Mage::getSingleton('adminhtml/session')
                    ->addNotice('Now activated the "Daily Deals" for this product. While it is activated you can not use standard special price. When "Daily Deals" will end will be returned your special data: <br/>special price: '
                        . $product->getOldSpecialPrice() . ' <br/>special date from: ' . $product->getOldSpecialDateFrom() . ' <br/>special date to: ' . $product->getOldSpecialDateTo());
            }
            return Mage::getSingleton('adminhtml/session')
                ->addNotice('Now activated the "Daily Deals" for this product. While it is activated you can not use standard special price.');
        }

    }

    /**
     * set deal_qty and deal_bought when create order
     * @param Varien_Event_Observer $observer
     */
    public function salesOrderPlaceAfter(Varien_Event_Observer $observer)
    {
        $helper = Mage::helper('dailydeals');
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        $model = Mage::getModel('catalog/product');
        $orderItems = $observer->getOrder()->getQuote()->getAllItems();
        $this->setDealStat($observer);
        foreach ($orderItems as $orderItem) {
            $product = $model->load($orderItem->getProductId());
            if ($helper->isDealEnabled($product)) {
                $product->setDealQty($product->getDealQty() - $orderItem->getQuote()->getItemsQty())
                    ->setDealBought($product->getDealBought() + $orderItem->getQuote()->getItemsQty())
                    ->save();
            }
        }
    }

    /**
     * set deal_stat in order_item
     * @param $observer
     */
    protected function setDealStat($observer)
    {
        $model = Mage::getModel('catalog/product');
        $helper = Mage::helper('dailydeals');
        $items = $observer->getOrder()->getAllItems();
        foreach ($items as $item) {
            $product = $model->load($item->getProductId());
            if ($helper->isDealEnabled($product)) {
                $item->setDealStat(true);
            }
        }
    }

    /**
     * Set available quantity to quote item
     *
     * @param Varien_Event_Observer $observer
     */
    public function checkoutCartSaveBefore(Varien_Event_Observer $observer)
    {
        $model = Mage::getModel('catalog/product');
        $helper = Mage::helper('dailydeals');
        $cartItems = $observer->getCart()->getItems();
        foreach ($cartItems as $cartItem) {
            $product = $model->load($cartItem->getProductId());
            if ($helper->isDealEnabled($product)) {
                if ($product->getDealQty() < $cartItem->getQty()) {
                    $cartItem->setQty($product->getDealQty());
                    Mage::getSingleton('core/session')->addError($helper->__('Only ' . $product->getDealQty() . ' deal "' . $product->getName() . '" left.'));
                }
            }
        }
    }

    /**
     * Change deal_qty and deal_bought when item cancel
     * @param Varien_Event_Observer $observer
     */
    public function salesOrderItemCancel(Varien_Event_Observer $observer)
    {
        $item = $observer->getItem();
        if ($item->getDealStat()) {
            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            $product->setDealQty($product->getDealQty() + $item->getQtyOrdered())
                ->setDealBought($product->getDealBought() - $item->getQtyOrdered())
                ->save();
        }

    }

    /**
     * Change deal_qty and deal_bought if refound Credit memo
     * @param Varien_Event_Observer $observer
     */
    public function salesOrderCreditmemoRefund(Varien_Event_Observer $observer)
    {
        $arguments = $this->_getRequest()->getParam('creditmemo');
        $modelOrderItem = Mage::getModel('sales/order_item');
        $modelProduct = Mage::getModel('catalog/product');
        $items = $observer->getCreditmemo()->getAllItems();
        foreach ($items as $item) {
            $orderItem = $modelOrderItem->load($item->getOrderItemId());
            if ($orderItem->getDealStat()) {
                $qty = $orderItem->getQtyOrdered() - $orderItem->getQtyRefunded();
                if (($item->getQty() != $qty) || (!$arguments['adjustment_negative'])) {
                    $product = $modelProduct->load($item->getProductId());
                    $product->setDealQty($product->getDealQty() + $item->getQty())
                        ->setDealBought($product->getDealBought() - $item->getQty())
                        ->save();
                }
            }
        }
    }

    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }

    /** for countdown-mini in category and deals page
     * @param Varien_Event_Observer $observer
     */
    public function coreBlockAbstractToHtmlBefore(Varien_Event_Observer $observer)
    {
        $controller = $this->_getRequest()->getControllerName();
        $module = $this->_getRequest()->getModuleName();
	    $showOnSearch = ($controller == 'result') && Mage::getStoreConfig('dailydeals/dailydeals_group/show_on_search');
        if (($controller == 'category') || ($module == 'dailydeals') || $showOnSearch) {
	        $block = $observer->getBlock();
	        $type = $block->getType();
	        if ($type == 'catalog/product_price') {
		        $block->unsetChild('countdown.mini');
		        $child = clone $block;
		        $child->setType('core/template');
		        $block->setChild('countdown.mini', $child);
		        $block->setTemplate('deals/countdown-mini.phtml');
	        }
        }
    }
}