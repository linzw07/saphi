<?php
/*------------------------------------------------------------------------
 # SM saphi - Version 1.1
 # Copyright (c) 2013 YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Saphi_Model_System_Config_Source_ListBodyFont
{
	public function toOptionArray()
	{	
		return array(
			array('value'=>'segoe ui', 'label'=>Mage::helper('saphi')->__('Segoe UI')),
			array('value'=>'Arial', 'label'=>Mage::helper('saphi')->__('Arial')),
			array('value'=>'Arial Black', 'label'=>Mage::helper('saphi')->__('Arial-black')),
			array('value'=>'Courier New', 'label'=>Mage::helper('saphi')->__('Courier')),
			array('value'=>'Georgia', 'label'=>Mage::helper('saphi')->__('Georgia')),
			array('value'=>'Impact', 'label'=>Mage::helper('saphi')->__('Impact')),
			array('value'=>'Lucida Console', 'label'=>Mage::helper('saphi')->__('Lucida-console')),
			array('value'=>'Lucida Grande', 'label'=>Mage::helper('saphi')->__('Lucida-grande')),
			array('value'=>'Palatino', 'label'=>Mage::helper('saphi')->__('Palatino')),
			array('value'=>'Tahoma', 'label'=>Mage::helper('saphi')->__('Tahoma')),
			array('value'=>'Times New Roman', 'label'=>Mage::helper('saphi')->__('Times')),	
			array('value'=>'Trebuchet', 'label'=>Mage::helper('saphi')->__('Trebuchet')),	
			array('value'=>'Verdana', 'label'=>Mage::helper('saphi')->__('Verdana'))		
		);
	}
}
