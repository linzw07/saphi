<?php
/*------------------------------------------------------------------------
 # Yt Slider - Version 1.0
 # Copyright (C) 2009-2011 The YouTech JSC. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: The YouTech JSC
 # Websites: http://magento.ytcvn.com - http://joomla.ytcvn.com
 -------------------------------------------------------------------------*/


class Sm_Slider_Model_System_Config_Source_ListShowbuttons
{
	public function toOptionArray()
	{
		return array(
		array('value'=>'1', 'label'=>Mage::helper('slider')->__('Always Display')),
        array('value'=>'2', 'label'=>Mage::helper('slider')->__('Hover')),
		array('value'=>'0', 'label'=>Mage::helper('slider')->__('Hide'))
		);
	}
}
