$(document).ready(function(){
	$(".slides").slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 4000,
		dots: true,
		arrows: false, 
		fade: true,
	});
	/* $(".search-section .search-field").attr("placeholder", "");
	 $('.search-section .search-field').blur(function() {
		if( $(this).val().length >= 1) {
			$(this).addClass('none');
		}
		else {
			$(this).removeClass('none');
		}
	});  */
});
