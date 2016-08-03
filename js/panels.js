jQuery(document).ready(function() {
    jQuery('.panel-slider').slick({
    	dots: false,
    	infinite: true,
    	speed: 1500,
    	slidesToShow: 1,
    	adaptiveHeight: true,
    	autoplay: true,
    	autoplaySpeed: 8000,
    	arrows: true
    });
});