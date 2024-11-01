
function initPwnSocialSticky(pwnstickys_setting){
	jQuery('body').prepend('<div id="pwnstickysocial"><ul class="mainul"></ul></div>');

	for(var i=0;i<pwnStickySocials.length;i++){
	jQuery(".mainul").append("<li class='l1'>" + '<ul class="scli" style="background-color:' + pwnStickySocials[i][2] + '" link="'+pwnStickySocials[i][1]+'">' +
						'<li>' + pwnStickySocials[i][0] + '<img src="' + pwnStickySocials[i][3] + '"/></li></ul></li>');
	}
	if(pwnstickys_setting.top){
		jQuery("#pwnstickysocial").css('top', pwnstickys_setting.top +"px");
	}
	if(pwnstickys_setting.fontsize){
		jQuery("#pwnstickysocial").css('font-size', pwnstickys_setting.fontsize+"px");
	}
	if(pwnstickys_setting.location != undefined && pwnstickys_setting.location == 'right'){
		jQuery("#pwnstickysocial").addClass('right-float');
	}
	/// bar click event
	jQuery(".scli").click(function(e){
		e.preventDefault();
		var link = jQuery(this).attr('link');		
		window.open(link);
		//for(var i=0;i<pwnStickySocials.length;i++){
		//	if(pwnStickySocials[i][0] == link){
		//		//window.open(pwnStickySocials[i][1]);
		//	}
		//}		
	});
	
	/// mouse hover event
	jQuery(".scli").mouseenter(function() {	
		jQuery(this).stop(true);	
		jQuery(this).clearQueue();
		if(jQuery(this).parents('.right-float').length){
			jQuery(this).animate({right : "140px"}, 300);
		} else {
			jQuery(this).animate({left : "140px"}, 300);
		}
				
	});

	/// mouse out event
	jQuery(".scli").mouseleave(function(){
		if(jQuery(this).parents('.right-float').length){
			jQuery(this).animate({right:"0px"},700,'easeOutBounce');
		} else {
			jQuery(this).animate({left:"0px"},700,'easeOutBounce');
		}
	});

}

jQuery(document).ready(function(){
		jQuery( "#pwnsocialsticky-sortable" ).sortable({
			placeholder: "sortable-placeholder",
			start: function(event, ui){        
			   jQuery(ui.item).addClass('dragging');
			},
			stop: function(event, ui){ 
			   jQuery(ui.item).removeClass('dragging');
			}
		});
		jQuery("#pwnsocialsticky-admin-frm").submit(function(e){
				var ids = jQuery( "#pwnsocialsticky-sortable" ).sortable("toArray");
				for(var i in ids){
						var id = ids[i];
						jQuery("#"+id + " .order").val(i);
				}
				//e.preventDefault();
		});
});
