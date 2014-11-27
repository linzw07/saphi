<?php
class Sm_Saphi_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function __construct(){
		$this->defaults = array(			
			'showcpanel'				=> '1',
			'color'						=> 'default',
			'body_background_color'		=> '#fff',
			'body_link_color'			=> '#909090',
			'body_text_color'			=> '#909090',
			'menu_styles'				=> '1',
			'google_font'				=> '',
			'google_font_targets'		=> '',
			'body_font_size'			=> '12px',
			'body_font_family'			=> 'arial',
		);
	}

	function get($attributes=array())
	{
		$data = $this->defaults;
		$general = Mage::getStoreConfig("saphi_cfg/general");
		if (!is_array($attributes)) {
			$attributes = array($attributes);
		}
		if (is_array($general))	
		$data = array_merge($data, $general);
		return array_merge($data, $attributes);;
	}
}
	 