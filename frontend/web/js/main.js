/*
*   MA5 jquery mobile menu
*   v 1.0
*   Copyright (c) 2015 Tomasz Kalinowski
*   http://mobile-menu.ma5.pl
*   GitHub: https://github.com/ma-5/ma5-mobile-menu
*/
jQuery(document).ready(function(){
    jQuery('body').append('<div class="ma5-mobile-menu-container"/>');
    jQuery('.ma5-menu-mobile').find('ul').clone().addClass('ma5-menu-panel').appendTo('.ma5-mobile-menu-container').find('ul').remove();
    jQuery('.ma5-toggle-menu').on('click touch', function () {        
        if(jQuery(".plan_venues").hasClass("toggled")){            
            jQuery(".plan_venues").removeClass("toggled");           
            jQuery(".overlay").css("display","none");
            isClosed = false;
            // jQuery(".overlay").css("display","block");
        }
        jQuery('html').toggleClass('ma5-menu-active')});
    jQuery('.ma5-btn-enter').on('click touch', function () {
        jQuery('.ma5-menu-panel').removeClass('ma5-active-ul');
        jQuery('.ma5-menu-panel li').removeClass('ma5-active-li');
        var itemPath = jQuery(this).parent().attr('class').replace("li", "ul");
        var itemParent = jQuery(this).parent().attr('class').replace("li", "ul").split('-');
        var spliced = itemParent.splice(-1, 1);
        var itemParent = itemParent.join("-");
        jQuery('.ma5-menu-panel').removeClass('ma5-active-leave ma5-parent-leave ma5-active-enter ma5-parent-enter');
        jQuery('.ma5-menu-panel.' + itemParent).addClass('ma5-parent-enter');
        jQuery('.ma5-menu-panel.' + itemPath).addClass('ma5-active-enter');
    });
    jQuery('.ma5-leave-bar').on('click touch', function () {
        var itemParent = jQuery(this).parent().attr('class').replace("li", "ul").split('-');
        var splicedParent = itemParent.splice(-1, 1);
        var splicedParent = itemParent.splice(-1, 1);
        var itemParent = itemParent.join("-");
        var itemPath = jQuery(this).parent().attr('class').replace("li", "ul").split('-');
        var spliced = itemPath.splice(-1, 1);
        var itemPath = itemPath.join("-");
        jQuery('.ma5-menu-panel').removeClass('ma5-active-leave ma5-parent-leave ma5-active-enter ma5-parent-enter');
        jQuery('.ma5-menu-panel.' + itemParent).addClass('ma5-parent-leave');
        jQuery('.ma5-menu-panel.' + itemPath).addClass('ma5-active-leave'); 
    });
});

function filter_butt() {
	if(jQuery("html").hasClass("ma5-menu-active"))
	{      
		jQuery("html").removeClass("ma5-menu-active");		
	}
   


if (isClosed == true) {     
overlay.hide();
trigger.removeClass('ses_dct');
trigger.addClass('ses_act');
isClosed = false;
} else {   
overlay.show();
trigger.removeClass('ses_act');
trigger.addClass('ses_dct');
isClosed = true;
}
}
