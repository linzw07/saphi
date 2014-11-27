<?php
/**
 * @category   Webinse
 * @package    Webinse_DailyDeals
 * @author     Webinse Team <info@webinse.com.com>
 */

$installer = new Mage_Catalog_Model_Resource_Setup('core_setup');
$installer->startSetup();

$entityTypeId = Mage::getModel('eav/entity')
    ->setType('catalog_product')
    ->getTypeId();

$installer->addAttribute($entityTypeId, 'deal_statuses', array(
    'type' => 'varchar',
    'backend' => '',
    'frontend' => '',
    'label' => 'Deal Statuses',
    'note' => ' ',
    'input' => 'text',
    'class' => '',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible' => false,
    'required' => false,
    'user_defined' => false,
    'default' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => false,
    'unique' => false,
    'is_configurable' => false
));

$attributeSetCollection = Mage::getResourceModel('eav/entity_attribute_set_collection')
    ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId());

foreach ($attributeSetCollection as $attribute) {
    $installer->addAttributeToSet($entityTypeId, $attribute->getAttributeSetName(), 'Daily Deals', 'deal_statuses');
}

$installer->endSetup();
