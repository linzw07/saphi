<?php
/*------------------------------------------------------------------------
 * Copyright (C) 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.magentech.com - http://www.cmsportal.net
-------------------------------------------------------------------------*/
/*--- BEGIN: Theme param config ---*/
$_params = new ThemeParameter();
$_config = array(
	'showcpanel'				=>'1',
	'body_font_size'			=>'12px',
	'body_font_family'			=>'arial',
	'color'						=>'default',
	'menu_styles'				=>'1',
	'body_background_color'		=>'#fff',
	'body_background_image'		=>'1',
	'responsive_menu'           =>'1',
	'body_link_color'			=>'#909090',
	'body_text_color'			=>'#909090'
);
if(Mage::getConfig()->getNode('modules/Sm_saphi')){
	$_config	=	Mage::helper('saphi/data')->get();
}
// enable Cpanel
$_params->set('showCpanel',$_config['showcpanel']);//image: Image; text: Text
// show custom box notice
//$_params->set('shownotice',$_config['show_notice']);//image: Image; text: Text
// Logo type
$_params->set('logoType','Text');//image: Image; text: Text
// Logo text desx
$_params->set('logoText','Logo Text');
// Slogan text
$_params->set('sloganText','Slogan'); 
// Fontsize
$_params->set('fontsize',$_config['body_font_size']);//value from 1 to 6
// font family
$_params->set('font_name',$_config['body_font_family']);
// Google web font
$_params->set('googleWebFont',$_config['google_font']);
// Respnsive menu
$_params->set('responsive_menu',$_config['responsive_menu']);
// Google WebFont Targets
$_params->set('googleWebFontTargets',$_config['google_font_targets']);
// Theme color
$_params->set('sitestyle',$_config['color']);//'black','blue','brown','cyan';
//keep on top menu
$_params->set('keepontop_menu',$_config['keepontop_menu']);
// Theme menu
if($_config['menu_styles'] ==1) {	$menu_style='mega';}	
if($_config['menu_styles'] ==2) {	$menu_style='css';}	
if($_config['menu_styles'] ==3) {	$menu_style='split';}	

$_params->set('menustyle',$menu_style);//css:CSS Menu; split: Split Menu; mega: Mega Menu
// Body background-color
$_params->set('bgcolor', $_config['body_background_color']);
// Body background-image
//$_params->set('body-bgimage', 'pattern'.$_config['body_background_image']);
// Body link color
$_params->set('linkcolor', $_config['body_link_color']);
// Body text color
$_params->set('textcolor', $_config['body_text_color']);
// Header background-color
//$_params->set('header-bgcolor', $_config['header_background_color']);
// Header background-image
//$_params->set('header-bgimage', 'hpattern'.$_config['header_background_image']);
// Footer background-color
//$_params->set('footer-bgcolor', $_config['footer_background_color']);
// Footer background-image
//$_params->set('footer-bgimage', 'fpattern'.$_config['footer_background_image']);

// CategoryID array of displays 2nd image when hover - Catalog list
$_params->set('yt_arraydisplaylist', array('4','5','16','17','18'));
/*--- END: Theme param config ---*/

// Array param for cookie
$paramscookie =array(
				  'direction', 
				  'fontsize',
				  'font_name',
				  'sitestyle',
				  'bgcolor',
				  /*'body-bgimage',*/
				  'linkcolor',
				  'textcolor',
				  /*'header-bgimage',
				  'header-bgcolor',
				  'footer-bgcolor',
				  'footer-bgimage',*/
				  'menustyle',
				  'shownotice',				  
				  'googleWebFont'
);
global $var_yttheme;
$var_yttheme = new YtTheme('sm_saphi', $_params, $paramscookie);
?>


