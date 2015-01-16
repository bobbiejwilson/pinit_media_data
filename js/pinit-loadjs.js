jQuery(window).bind("load", function($){
	var len = jQuery('script[src*="pinit_main.js"]').length;
console.log(len);
	if (len === 0) {
    		jQuery('body').append('<script src="//assets.pinterest.com/js/pinit.js" async defer data-pin-hover="true"></script>');
	} else {
		console.log('Already Loaded');
	}
   
	
});