(function($){
	
	$.fn.viewport = function(){	
	    var e = window, a = 'inner';
	    if (!('innerWidth' in window )) {
	        a = 'client';
	        e = document.documentElement || document.body;
	    }
	    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
	}	
	
	$.fn.getScrollbarWidth=	function(){
		document.body.style.overflow = 'hidden'; 
		var width = document.body.clientWidth;
		document.body.style.overflow = 'scroll'; 
		width -= document.body.clientWidth; 
		if(!width) width = document.body.offsetWidth - document.body.clientWidth;
		document.body.style.overflow = ''; 	
		return width;
	}
	
	$.fn.detectDevice = function(){
		//var width = $(window).width();
		var width = $.fn.viewport().width;
		var scrollWidth = $().getScrollbarWidth();
			
  		//console.log(width);
 		if( width > 1200 ){
   		return 'wide';
  		}else if( width >= 980 ){
   		return 'normal';
  		}else if(  width >= 768 ){
   		return 'tablet';
  		}else if( width >= 480 && width <= 767 ){
   		return 'stablet';
  		}else if(  width > 0 ){
   		return 'mobile';
  		}
		
		/*
		Mobile portrait (320x480)
		Mobile landscape (480x320)
		Small tablet portrait (600x800)
		Small tablet landscape (800x600)
		Tablet portrait (768x1024
		Tablet landscape (1024x768)
		*/
	
	}

	$.fn.updateDataElementClass = function(){
		var $this = $(this);
		
		currentdevice = (currentdevice)?currentdevice:$.fn.detectDevice();
		
		// Build data
		if ($this.data('default')) return; 		
		// With attr data-*
		else $this.data();
		// Make the source better view in inspector
    	$this.removeAttr ('data-default data-wide data-normal data-tablet data-stablet data-mobile');
		// For element no attr data-default
		if (!$this.data('default')) 
			$this.data('default', $this.attr('class'));
		// Default
		if ( !$this.data('default') ||  !currentdevice || !$this.data(currentdevice))
			return;	

		// Add new
		else{
				// Remove current
				$this.removeClass ($this.data('default'));			
				$this.addClass ($this.data(currentdevice));
		}
	}
	
})(jQuery || jsmart)
var currentdevice = '';
$jsmart(document).ready(function($){
	
	var bootstrap_elements = $('[class*="span"]');
	// Build data
	bootstrap_elements.each ( function(){
		var $this = $(this);
		// With attr data-*
		$this.data();
		// Make the source better view in inspector
    	 $this.removeAttr ('data-default data-wide data-normal data-tablet data-stablet data-mobile');
		// For element no attr data-default
		if (!$this.data('default')) 
			$this.data('default', $this.attr('class'));
	
	});
	function updateBootstrapElementClass(newdevice){
  		if (newdevice == currentdevice) return ;
		bootstrap_elements.each(function(){
			var $this = $(this);
			// Default
			if ( !$this.data('default') || (!$this.data(newdevice) && (!currentdevice || !$this.data(currentdevice))) )
				return;
			// Remove current
			if ($this.data(currentdevice)) $this.removeClass($this.data(currentdevice));
			else $this.removeClass ($this.data('default'));
			// Add new
			if ($this.data(newdevice)) $this.addClass ($this.data(newdevice));
			else $this.addClass ($this.data('default'));
		});
    	currentdevice = newdevice;
	};
	function getScrollbarWidth(){
		document.body.style.overflow = 'hidden'; 
		var width = document.body.clientWidth;
		document.body.style.overflow = 'scroll'; 
		width -= document.body.clientWidth; 
		if(!width) width = document.body.offsetWidth - document.body.clientWidth;
		document.body.style.overflow = ''; 	
		return width;
	}
	function viewport() {
	    var e = window, a = 'inner';
	    if (!('innerWidth' in window )) {
	        a = 'client';
	        e = document.documentElement || document.body;
	    }
	    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
	}

	function idforPriceOnMenu(){
		/*if($('.sm_megamenu_content .price-box .special-price span.special-price').length > 0){
			$('.sm_megamenu_content .price-box .special-price span.special-price').each(function(){
				//alert($(this).attr('id'));
				$(this).attr('id', $(this).attr('id')+'_onmenu');
			});
		}*/
		if($('.yt-product-detail .price-box .special-price span.special-price').length > 0){
			$('.sm_megamenu_content .price-box .special-price span.special-price').each(function(){
				//alert($(this).attr('id'));
				$(this).attr('id', $(this).attr('id')+'_onmenu');
			});
		}
		if($('.yt-product-detail .price-box span.old-price > .price').length > 0){
			$('.sm_megamenu_content .price-box span.old-price .price').each(function(){
				//alert($(this).attr('id'));
				$(this).attr('id', $(this).attr('id')+'_onmenu');
			});
		}
		if($('.sm_megamenu_content .price-box .regular-price').length > 0){
			$('.sm_megamenu_content .price-box .regular-price').each(function(){
				//alert($(this).attr('id'));
				$(this).attr('id', $(this).attr('id')+'_onmenu');
			});
		}
	}
	idforPriceOnMenu();
	function detectDevice () {
		var width = viewport().width; 
  		//console.log(width);
		//alert(width);
 		if( width > 1200 ){
   		return 'wide';
  		}else if( width >= 980 ){
   		return 'normal';
  		}else if(  width >= 768 ){
   		return 'tablet';
  		}else if( width >= 480 && width <= 767 ){
   		return 'stablet';
  		}else if(  width > 0 ){
   		return 'mobile';
  		}
		
		/*
		Mobile portrait (320x480)
		Mobile landscape (480x320)
		Small tablet portrait (600x800)
		Small tablet landscape (800x600)
		Tablet portrait (768x1024
		Tablet landscape (1024x768)
		*/
	}

  	updateBootstrapElementClass (detectDevice());
  
  	// With window resize
  	$(window).resize(function(){
    	if ($.data(window, 'detect-device-time'))
      		clearTimeout($.data(window, 'detect-device-time'));
    	$.data(window, 'detect-device-time',
      		setTimeout(function(){
        		updateBootstrapElementClass (detectDevice());
      		}, 200)
    	)
  	});

});


$jsmart(window).load(function(){
	anyFix();
	forTabs();
  	function anyFix(){
  		if(viewport().width >= 980){
  			width = $jsmart('#yt_main_top .tabs-container.font1').outerWidth() - $jsmart('#yt_main_top .tabs-container.font1 ul.tabs').outerWidth(); 
  			$jsmart('.block-banner.block-support').css('width', width);

  			width2 = $jsmart('#yt_main_bottom .tabs-container.font1').outerWidth() - $jsmart('#yt_main_bottom .tabs-container.font1 ul.tabs').outerWidth(); 
  			$jsmart('.block-banner.block-freeship').css('width', width2);
  		}
  		width3 = $jsmart('#yt_main_middle').outerWidth() - $jsmart('#yt_main_middle .control-button').outerWidth() - 1;
  		$jsmart('#yt_main_middle .page-title-slider').css('width', width3);
  		if(viewport().width >= 768 && viewport().width <=1024){
  			if($jsmart('#products-list')!= undefined){
  				$jsmart('#products-list #pl_imagebox').css('height', $jsmart('#products-list #pl_infobox').outerHeight());
  				$jsmart('#products-list #pl_imagebox .product-image').css('margin-top', ($jsmart('#products-list #pl_infobox').outerHeight()-$jsmart('#products-list #pl_imagebox .product-image').outerHeight())/2);
  			}
  		}
  	}
  	function forTabs(){
		h = $jsmart('#yt_main_bottom .tabs-content .tab-content.sel').outerHeight();
		$jsmart('#yt_main_bottom .tabs-content .tabs-content-inner').css('height', h);

		h2 = $jsmart('#yt_main_top .tabs-content .tab-content.sel').outerHeight();
		$jsmart('#yt_main_top .tabs-content .tabs-content-inner').css('height', h2);
	}
  	function viewport() {
	    var e = window, a = 'inner';
	    if (!('innerWidth' in window )) {
	        a = 'client';
	        e = document.documentElement || document.body;
	    }
	    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
	}

	// With window resize
  	$jsmart(window).resize(function(){
    	if ($jsmart.data(window, 'fortab-time'))
      		clearTimeout($jsmart.data(window, 'fortab-time'));
    	$jsmart.data(window, 'fortab-time',
      		setTimeout(function(){
        		anyFix();forTabs();
      		}, 200)
    	);
  	});


});