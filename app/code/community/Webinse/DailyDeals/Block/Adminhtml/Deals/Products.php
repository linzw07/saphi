<?php

/**
 * Adminhtml new deals grid
 *
 * @category   Webinse
 * @package    Webinse_DailyDeals
 * @author     Webinse Team <info@webinse.com.com>
 */
class Webinse_DailyDeals_Block_Adminhtml_Deals_Products extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     * Set some default on the grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('dealsGrid');
        $this->setDefaultSort('identifier');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
    }

    /**
     * Set the desired collection on our grid
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('visibility', array('neq' => 1))
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'deal_status', 'null' => true),
                    array('attribute' => 'deal_status', 'eq' => null),
                    array('attribute' => 'deal_status', 'neq' => 1),
                ), '', 'left'
            );
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     *  Set columns on our grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $store = $this->getStore();
        $helper = Mage::helper('dailydeals');

        $this->addColumn('title', array(
            'header' => $helper->__('Product Name'),
            'index' => 'name',
        ));
        $this->addColumn('sku', array(
            'header' => $helper->__('Sku'),
            'index' => 'sku',
        ));
        $this->addColumn('type_product', array(
            'header' => $helper->__('Type'),
            'index' => 'type_id',
            'type' => 'options',
            'options' => Mage::getSingleton('dailydeals/statuses')->getOptionsTypeArray(),
        ));
        $this->addColumn('price', array(
            'header' => $helper->__('Price'),
            'type' => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index' => 'price',
        ));

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            $this->addColumn('action', array(
                'header' => $helper->__('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => $helper->__('Add Deal'),
                        'url' => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));
        }

        return parent::_prepareColumns();
    }

    /**
     * Get url for row
     *
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/products', array('_current' => true));
    }

}