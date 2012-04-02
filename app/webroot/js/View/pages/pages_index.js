(function($){

	$('.ContentLarge').cycle({
	    fx:     'scrollLeft',
	    timeout: 6000, 
    	delay:  -2000,
    	pause:   1,
	    pager:  '.ContentThumb',
	    pagerAnchorBuilder: function(idx, slide) {
	        // return selector string for existing anchor
	        return '.ContentThumb div:eq(' + idx + ') a';
	    }
	});

})(jQuery);