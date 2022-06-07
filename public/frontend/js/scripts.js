
(function ($) {
	'use strict';

	//Preloader
	var win = $(window);
	win.on('load',function() {
		$('.tw-loader').delay(100).fadeOut('slow');
	});
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	//Top Menu sticky
	win.on('scroll',function() {
		// if ($(this).scrollTop() > 100){
		if ($(this).scrollTop() > 500){
			$('#sticky-header').addClass("sticky");
		}else{
			$('#sticky-header').removeClass("sticky");
		}
		top_categories_nicescroll();
	});
	
	//ScrollToTop
	$(".scroll-to-top").scrollToTop(1000);
	
	//niceScroll for Top Categories
	var top_categories_nicescroll = function() {
		$(".nav_cat_content").getNiceScroll().resize();
		$(".nav_cat_content").niceScroll({
			cursorborder: "",
			cursorcolor: theme_color,
			boxzoom: false,
			scrollspeed: 60, 
			cursorwidth: "3px",
			smoothscroll: true,
		});
	}
	
	top_categories_nicescroll();
	
	// Off Canvas Open close start
	$(".off-canvas-btn").on('click', function () {
		$(".mobile-menu-wrapper").addClass('open');
	});

	$(".offcanvas-btn-close, .off-canvas-overlay").on('click', function () {
		$(".mobile-menu-wrapper").removeClass('open');
	});

	// slide effect dropdown
	function dropdownAnimation() {
		$('.dropdown').on('show.bs.dropdown', function (e) {
			$(this).find('.dropdown-menu').first().stop(true, true).slideDown(500);
		});

		$('.dropdown').on('hide.bs.dropdown', function (e) {
			$(this).find('.dropdown-menu').first().stop(true, true).slideUp(500);
		});
	}
	
	dropdownAnimation();

	//offcanvas mobile menu start 
    var $offCanvasNav = $('.mobile-menu'),
        $offCanvasNavSubMenu = $offCanvasNav.find('.dropdown');
    
    /*Add Toggle Button With Off Canvas Sub Menu*/
    $offCanvasNavSubMenu.parent().prepend('<span class="menu-expand"><i></i></span>');
    
    /*Close Off Canvas Sub Menu*/
    $offCanvasNavSubMenu.slideUp();
    
    /*Category Sub Menu Toggle*/
    $offCanvasNav.on('click', 'li a, li .menu-expand', function(e) {
        var $this = $(this);
        if ( ($this.parent().attr('class').match(/\b(has-children-menu|has-children|has-sub-menu)\b/)) && ($this.attr('href') === '#' || $this.hasClass('menu-expand')) ) {
            e.preventDefault();
            if ($this.siblings('ul:visible').length){
                $this.parent('li').removeClass('active');
                $this.siblings('ul').slideUp();
            } else {
                $this.parent('li').addClass('active');
                $this.closest('li').siblings('li').removeClass('active').find('li').removeClass('active');
                $this.closest('li').siblings('li').find('ul:visible').slideUp();
                $this.siblings('ul').slideDown();
            }
        }
    });

	// tooltip active js
	//$('[data-toggle="tooltip"]').tooltip();

	$('.home-slider').owlCarousel({
        navText: ['<i class="bi bi-arrow-left"></i>', '<i class="bi bi-arrow-right"></i>'],
		rtl: isRTL,
        loop: true,
        nav: true,
		dots: true,
        mouseDrag: true,
		responsiveClass:true,
		smartSpeed: 1000,
		responsive:{
			0:{
				items:1
			},
			400:{
				items:1
			},
			600:{
				items:1
			},
			900:{
				items:1
			},
			1000:{
				items:1
			}
		}
	});
	
	$('.brands-carousel').owlCarousel({
        navText: ['<i class="bi bi-arrow-left"></i>', '<i class="bi bi-arrow-right"></i>'],
		rtl: isRTL,
        loop: true,
        nav: true,
		dots: false,
		margin: 25,
        mouseDrag: true,
		responsiveClass:true,
		smartSpeed: 1000,
		responsive:{
			0:{
				items:1
			},
			400:{
				items:2
			},
			600:{
				items:3
			},
			900:{
				items:5
			},
			1000:{
				items:6
			}
		}
	});
	
	$('.category-carousel').owlCarousel({
        navText: ['<i class="bi bi-arrow-left"></i>', '<i class="bi bi-arrow-right"></i>'],
		rtl: isRTL,
        loop: true,
        nav: true,
		dots: false,
		margin: 25,
        mouseDrag: true,
		responsiveClass:true,
		smartSpeed: 1000,
		responsive:{
			0:{
				items:1
			},
			400:{
				items:2
			},
			600:{
				items:2
			},
			900:{
				items:3
			},
			1000:{
				items:4
			}
		}
	});
	
	//Sigle Product Slider
	var bigimage = $("#product_big");
	var thumbs = $("#product_thumbs");
	var syncedSecondary = true;

	bigimage.owlCarousel({
		navText: ['<i class="bi bi-arrow-left"></i>', '<i class="bi bi-arrow-right"></i>'],
		rtl: isRTL,
		items: 1,
		nav: true,
		autoplay: false,
		dots: false,
		loop: true,
		smartSpeed: 1000,
		slideSpeed: 2000,
		responsiveRefreshRate: 200
	}).on("changed.owl.carousel", syncPosition);

	thumbs.on("initialized.owl.carousel", function() {
		thumbs.find(".owl-item").eq(0).addClass("current");
	}).owlCarousel({
		navText: ['<i class="bi bi-arrow-left"></i>', '<i class="bi bi-arrow-right"></i>'],
		rtl: isRTL,
		dots: false,
		nav: false,
		margin: 10,
		smartSpeed: 1000,
		slideSpeed: 500,
		slideBy: 4,
		responsiveRefreshRate: 100,
        mouseDrag: true,
		responsiveClass:true,
		responsive:{
			0:{
				items:3
			},
			400:{
				items:5
			},
			600:{
				items:6
			},
			900:{
				items:6
			},
			1000:{
				items:6
			}
		}
	}).on("changed.owl.carousel", syncPosition2);

	function syncPosition(el) {
		var count = el.item.count - 1;
		var current = Math.round(el.item.index - el.item.count / 2 - 0.5);

		if (current < 0) {
			current = count;
		}
		
		if (current > count) {
			current = 0;
		}
		
		thumbs.find(".owl-item").removeClass("current").eq(current).addClass("current");
		var onscreen = thumbs.find(".owl-item.active").length - 1;
		var start = thumbs.find(".owl-item.active").first().index();
		var end = thumbs.find(".owl-item.active").last().index();

		if (current > end) {
			thumbs.data("owl.carousel").to(current, 100, true);
		}

		if (current < start) {
			thumbs.data("owl.carousel").to(current - onscreen, 100, true);
		}
	}

	function syncPosition2(el) {
		if (syncedSecondary) {
			var number = el.item.index;
			bigimage.data("owl.carousel").to(number, 100, true);
		}
	}

	thumbs.on("click", ".owl-item", function(e) {
		e.preventDefault();
		var number = $(this).index();
		bigimage.data("owl.carousel").to(number, 300, true);
	});
	
	$('.sidebar_show_hide').on('click', function () {
		$('.cart-sidebar').toggleClass('active');
	});

	//Subscribe for footer
	$(document).on("click", ".subscribe_btn", function(event) {
		event.preventDefault();
		
		var sub_email = $("#subscribe_email").val();
		var status = 'subscribed';
		
		var sub_btn = $('.sub_btn').html();
		var sub_recordid = '';
		
		var subscribe_email = sub_email.trim();
		
		if(subscribe_email == ''){
			$('.subscribe_msg').html('<p class="text-danger">The email address field is required.</p>');
			return;
		}
		
		$.ajax({
			type : 'POST',
			url: base_url + '/frontend/saveSubscriber',
			data: 'RecordId=' + sub_recordid+'&email_address='+subscribe_email+'&status='+status,
			beforeSend: function() {
				$('.subscribe_msg').html('');
				$('.sub_btn').html('<span class="spinner-border spinner-border-sm"></span> Please Wait...');
			},
			success: function (response) {			
				var msgType = response.msgType;
				var msg = response.msg;

				if (msgType == "success") {
					$("#subscribe_email").val('');
					$('.subscribe_msg').html('<p class="text-success">'+msg+'</p>');
				} else {
					$('.subscribe_msg').html('<p class="text-danger">'+msg+'</p>');
				}
				
				$('.sub_btn').html(sub_btn);
			}
		});
	});

}(jQuery));
