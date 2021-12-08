$(document).ready(function(){

	(function(){

		footer();

		$(window).resize(function() {
			footer();
		});

		function footer() {
			if ($('.main-footer').length) {
				var docHeight = $(window).height(),
					footerHeight = $('.main-footer').outerHeight(),
					footerTop = $('.main-footer').position().top + footerHeight;

				if (footerTop < docHeight) {
					$('.main-footer').css('margin-top', (docHeight - footerTop) + 'px');
				}
			}
		}

	})();

	$('.burger-menu').click(function(){
		$(this).toggleClass('active');
		openActiveElement('.main-menu');
	});

	$('#fixed-overlay').click(function(){
		closeActiveElement();
		$('.burger-menu').removeClass('active');
		$('.main-menu').removeClass('active');
	});

	$('.main-menu-close').click(function(){
		closeActiveElement();
		$('.burger-menu').removeClass('active');
		$('.main-menu').removeClass('active');
	});
});

var timeout;

$(window).on('resize orientationchange', function(){
	clearTimeout(timeout);
	timeout = setTimeout(function(){
		if($(window).width() > 1023) {
			$('html, body').removeAttr('style').removeAttr('class');
			$('.burger-menu').removeClass('active');
			$('#fixed-overlay').removeClass('active');
		}
	}, 40);
});