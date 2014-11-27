<?php

/**
 * Statuses model
 *
 * @category   Webinse
 * @package    Webinse_DailyDeals
 * @author     Webinse Team <info@webinse.com.com>
 */
class Webinse_DailyDeals_Model_Statuses extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('dailydeals/statuses');
    }

    public function getOptionsStatusArray()
    {
        $statuses = array();
        $collection = Mage::getModel('dailydeals/statuses')->getCollection()->getData();
        foreach ($collection as $item) {
            $statuses[$item['code']] = $item['title'];
        }

        return $statuses;

    }

    public function getOptionsTypeArray()
    {
        $options = array();
        foreach (self::getTypes() as $typeId => $type) {
            $options[$typeId] = Mage::helper('catalog')->__($type['label']);
        }

        return $options;
    }

    static protected function getTypes()
    {

        $productTypes = Mage::getConfig()->getNode('global/catalog/product/type')->asArray();
        foreach ($productTypes as $productKey => $productConfig) {
            $moduleName = 'catalog';
            if (isset($productConfig['@']['module'])) {
                $moduleName = $productConfig['@']['module'];
            }
            $translatedLabel = Mage::helper($moduleName)->__($productConfig['label']);
            $productTypes[$productKey]['label'] = $translatedLabel;
        }


        return $productTypes;
    }
}