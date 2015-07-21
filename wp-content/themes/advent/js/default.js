jQuery(document).ready(function() {

    jQuery("#our-brand").owlCarousel({
        autoPlay: false, //Set AutoPlay to 3 seconds

        items: 3,
        itemsDesktop: [1024, 3],
        itemsDesktopSmall: [980, 3],
        itemsTabletSmall: [768, 2],
        itemsMobile : [480, 1]

    });
    jQuery(".next").click(function() {
        jQuery("#our-brand").trigger('owl.next');
    })
    jQuery(".prev").click(function() {
        jQuery("#our-brand").trigger('owl.prev');
    })
});

