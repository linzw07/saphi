<?php
/*------------------------------------------------------------------------
 # SM saphi - Version 1.0
 # Copyright (c) 2013 The YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Saphi_Model_System_Config_Source_ListColor
{
	public function toOptionArray()
	{	
		return array(
		array('value'=>'default', 'label'=>Mage::helper('saphi')->__('Default')),		
		array('value'=>'blue', 'label'=>Mage::helper('saphi')->__('Blue')),
		array('value'=>'brown', 'label'=>Mage::helper('saphi')->__('Brown')),
		array('value'=>'cyan', 'label'=>Mage::helper('saphi')->__('Cyan')),
		array('value'=>'red', 'label'=>Mage::helper('saphi')->__('Red'))
		);
	}
}
