(function($){
	
	/* Chosen Select Box Plugin */
	if($.fn.chosen) {
		$('select.chzn-select').chosen();
	}

	/* Tooltips */
	if($.fn.tipsy) {
		$(".mws-tooltip-s").tipsy({gravity: 's'});
	}
	
})(jQuery);