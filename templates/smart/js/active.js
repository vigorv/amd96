$(document).ready(function() {
	
	/*
		Aleksey Skubaev

		askubaev@gmail.com
		icq - 322253350
		Разработка шаблонов для DLE и кроссбраузерная верстка
		------------------
		Необходимые jQuery скрипты.
	
	*/
	
	$('.top-news-block:odd').addClass('activ');
	
	
	$('.login-enter').click(function(){
		$('.login-panel').fadeIn();
		$('.shadow-fon').fadeIn();
	})
	$('.close, .shadow-fon').click(function() {
		$('.login-panel').fadeOut();
		$('.shadow-fon').fadeOut();
	});
	
	
	$('.slider-item:first').addClass('active-slider').find('.slider-item-big').fadeIn(800);
	
	var sliderTime = 8000;//скорость слайдера
	
	function showSliderItem() {
		clearTimeout(timerId);
		var currentBlock = $('.active-slider');
		var nextBlock = currentBlock.next('.slider-item').length ? currentBlock.next('.slider-item') : $('.slider-item:first');
	
		currentBlock.find('.slider-item-big').fadeOut(300);
		$('.slider-item').removeClass('active-slider');
		
		nextBlock.addClass('active-slider').find('.slider-item-big').fadeIn(800, function () {
			timerId = setTimeout(showSliderItem, sliderTime);
			});
			return false;
	}
	
	function showCurrent() {
		clearTimeout(timerId);
		$('.slider-item').removeClass('active-slider');
		$('.slider-item-big').fadeOut(300);
		$(this).parent().addClass('active-slider').find('.slider-item-big').fadeIn(300);
		
		timerId = setTimeout(showSliderItem, sliderTime);
	}
	
	timerId = setTimeout(showSliderItem, sliderTime);

	$('.slider-item-small').hover(
		function() {
		$(this).addClass('slider-item-hover');
	}, function() {
		$(this).removeClass('slider-item-hover');
	});
	
	$('.slider-item-small').click(showCurrent);
	
	
});
