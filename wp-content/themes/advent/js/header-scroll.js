
jQuery(function () {
// Check the initial Poistion of the Sticky Header
    var stickyHeaderTop = jQuery('.header-menu').offset().top;

    jQuery(window).scroll(function () {
        if (jQuery(document).width() > 980) {
            if (jQuery(window).scrollTop() > stickyHeaderTop) {
                if (jQuery('body').hasClass('logged-in'))
                    jQuery('.header-menu').css({position: 'fixed', top: '30px'});
                else
                    jQuery('.header-menu').css({position: 'fixed', top: '0px'});                     
                //jQuery('body > section').css({'margin-top':jQuery('.header_bottom').height()});
            } 
            else {
                jQuery('.header-menu').css({position: 'absolute',top: '0px'});                 
                jQuery('body > section').css({'margin-top':jQuery('.header_bottom').height()+75});
            }
        }
    });

});
