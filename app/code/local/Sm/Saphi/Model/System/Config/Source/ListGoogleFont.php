<?php
/*------------------------------------------------------------------------
 # SM saphi - Version 1.1
 # Copyright (c) 2013 YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Saphi_Model_System_Config_Source_ListGoogleFont
{
	public function toOptionArray()
	{	
		return array(
			array('value'=>'', 'label'=>Mage::helper('saphi')->__('No select')),
			array('value'=>'Questrial', 'label'=>Mage::helper('saphi')->__('Questrial')),
			array('value'=>'Kameron', 'label'=>Mage::helper('saphi')->__('Kameron')),
			array('value'=>'Oswald', 'label'=>Mage::helper('saphi')->__('Oswald')),
			array('value'=>'Open Sans', 'label'=>Mage::helper('saphi')->__('Open Sans')),
			array('value'=>'BenchNine', 'label'=>Mage::helper('saphi')->__('BenchNine')),
			array('value'=>'Droid Sans', 'label'=>Mage::helper('saphi')->__('Droid Sans')),
			array('value'=>'Droid Serif', 'label'=>Mage::helper('saphi')->__('Droid Serif')),
			array('value'=>'PT Sans', 'label'=>Mage::helper('saphi')->__('PT Sans')),
			array('value'=>'Vollkorn', 'label'=>Mage::helper('saphi')->__('Vollkorn')),
			array('value'=>'Ubuntu', 'label'=>Mage::helper('saphi')->__('Ubuntu')),
			array('value'=>'Neucha', 'label'=>Mage::helper('saphi')->__('Neucha')),
			array('value'=>'Cuprum', 'label'=>Mage::helper('saphi')->__('Cuprum'))	
		);
	}
}
