function _openSideTab(act,orientation){
	console.log('_openSideTab');
	if(act == 1){
		if(orientation == "left"){
			jQuery("#containerLeftArrow").animate({
				width:"250px"
			},150, function(){
			//Fim
				jQuery("#containerContentLeftArrow").fadeIn();
			});
		} else if(orientation == "right") {
			jQuery("#containerRightArrow").animate({
				width:"250px"
			},150, function(){
				//Fim
				jQuery("#containerContentRightArrow").fadeIn();
			});
		}
	} else if (act == 0) {
		if(orientation == "left"){
			jQuery("#containerContentLeftArrow").fadeOut(300);
			jQuery("#containerLeftArrow").animate({
				width:"50px"
			},150, function(){
			//Fim
				jQuery("#containerContentLeftArrow").hide();
			});
		} else if(orientation == "right") {
			jQuery("#containerContentRightArrow").fadeOut(300);
			jQuery("#containerRightArrow").animate({
				width:"50px"
			},150, function(){
			//Fim
				jQuery("#containerContentRightArrow").hide();
			});
		}
	}
}

/*
** Funções comuns a todos os arquivos do site
*/
(function($){
	$("#containerLeftArrow").on({
		mouseenter: function(){
			console.log('containerLeftArrow mouseenter');
			_openSideTab(1,'left');
		},
		mouseleave: function(){
			_openSideTab(0,'left');
		}
	});

	$("#containerRightArrow").on({
		mouseenter: function(){
			console.log('containerRightArrow mouseenter');
			_openSideTab(1,'right');
		},
		mouseleave: function(){
			_openSideTab(0,'right');
		}
	});
})(jQuery);