//JS script for Joomla template



$jsmart(document).ready(function($){
	$window = $jsmart(window);

	//menu_offset_top = $jsmart('.header-top-menu').offset().top;	
	menu_offset_top = 172;
	function processScroll() {

		var scrollTop = $window.scrollTop();

		if ( scrollTop >= menu_offset_top) {

			$jsmart('.header-top-menu').addClass('subnav-fixed');

		} else if (scrollTop <= menu_offset_top) {

			$jsmart('.header-top-menu').removeClass('subnav-fixed');

		}

	}

	if($jsmart('.header-top-menu')){

		// fix sub nav on scroll

		processScroll();

		$window.scroll(function(){

			processScroll();

		});

	}

});