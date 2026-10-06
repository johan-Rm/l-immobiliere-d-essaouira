'use strict';

export function initBtnFilterWrapAndCarousel() {
    // étudier comment nous pourrons mettre la config dan sun fichier séparé
    var owl = jQuery('.owl-carousel-filter').owlCarousel({
        loop:false,
        autoplay:true,
        margin:20,
        nav:true,
        dots: false,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        responsive:{
            0:{
                items:1
            },
            480:{
                items:1
            },
            580:{
                items:2
            },
            767:{
                items:2
            },
            991:{
                items:3
            },
            1152:{
                items:4
            },
            1360:{
                items:4
            },
            1366:{
                items:5
            }
        }
    })
    /* animate filter */
    var owlAnimateFilter = function(even) {
        jQuery(this)
            .addClass('__loading')
            .delay(70 * $(this).parent().index())
            .queue(function() {
                $(this).dequeue().removeClass('__loading')
        })
    }
    jQuery('.btn-filter-wrap').on('click', '.btn-filter', function(e) {
        var filter_data = jQuery(this).data('filter');

        /* return if current */
        if(jQuery(this).hasClass('btn-active')) return;

        /* active current */
        jQuery(this).addClass('btn-active').siblings().removeClass('btn-active');

        /* Filter */
        owl.owlFilter(filter_data, function(_owl) {
            jQuery(_owl).find('.item').each(owlAnimateFilter);
        });
    })
}

export function initHomeCarousel1() {
    jQuery('.home-carousel-1').owlCarousel({
        loop:true,
         margin:0,
         autoplay:true,
         autoplayTimeout:3000,
         //center: true,
         nav:false,
         // dotsContainer: '#carousel-custom-dots',
         dots: false,
         dotsData: true,
         navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
         responsive:{
             0:{
                 items:1
             },
             480:{
                 items:1
             },
             767:{
                 items:1
             },
             1000:{
                 items:1
             }
         }
     })
}

export function initHomeCarousel2() {
    jQuery('.home-carousel-2').owlCarousel({
        loop:true,
     autoplay:true,
     margin:0,
     nav:true,
     dots: false,
     navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
     responsive:{
         0:{
             items:1
         },
         480:{
             items:1
         },
         767:{
             items:1
         },
         1000:{
             items:1
         }
     }
 })
}

export function initHomeClientCarousel() {
    jQuery('.home-client-carousel').owlCarousel({
      loop:true,
      margin:10,
      nav:true,
      dots: false,
      navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
      responsive:{
          0:{
              items:2
          },
          480:{
              items:3
          },
          767:{
              items:4
          },
          1000:{
              items:5
          }
      }
    })
}

export function initTestimonialHome() {
    jQuery('.testimonial-home').owlCarousel({
        loop:true,
        autoplay:true,
        margin:80,
        nav:false,
        // dotsContainer: '#carousel-custom-dots',
        dots: false,
        dotsData: true,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        responsive:{
            0:{
                items:1
            },
            991:{
                items:2
            }
        }
    })
}

export function initAboutusCarousel() {
    jQuery('.about-us-carousel').owlCarousel({
        loop:false,
        autoplay:true,
        autoplayTimeout:3000,
        margin:30,
        nav:true,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        items:1,
        dots: false,
    });
}

/**/
export function initMasonryBox() {
    if ( jQuery().isotope ) {
        var $container = jQuery('.portfolio-wrap');
            $container.isotope({
                itemSelector: '.masonry-item',
                transitionDuration: '1s',
                originLeft: true
            });
        jQuery('.masonry-filter li').on('click',function() {
            var selector = jQuery(this).find("a").attr('data-filter');
            jQuery('.masonry-filter li').removeClass('active');
            jQuery(this).addClass('active');
          //  $container.isotope({ filter: selector });
            return false;
        });
   };
}



export function initWork_carousel() {
	jQuery('.work-carousel').owlCarousel({
        loop:true,
		autoplay:false,
		center: true,
		items:3,
		margin:0,
		nav:true,
		dots: false,
		navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
		responsive:{
			0:{
				items:1
			},
			854:{
				items:2
			}

		}
	});
}


//  google map 4 default function custom ========================== //
export function initMapContact() {
	if(jQuery('#gmap_canvas4').length){
		function init_map4() {
			var myOptions = {
				zoom: 10,
				center: new google.maps.LatLng(48.8583698, 2.2944833000000244),
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				styles:[{"elementType":"geometry","stylers":[{"color":"#212121"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#212121"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#757575"}]},{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"administrative.land_parcel","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#181818"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"color":"#1b1b1b"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#2c2c2c"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#8a8a8a"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#373737"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#3c3c3c"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#3d3d3d"}]}]
			};
            // console.log('ici')
			// Let's also add a marker while we're at it
            // console.log(document.getElementById('gmap_canvas4'))
			map4 = new google.maps.Map(document.getElementById('gmap_canvas4'), myOptions);
			marker4 = new google.maps.Marker({
				map: map4,
				position: new google.maps.LatLng(48.8583698, 2.2944833000000244),
				optimized: false,
				icon: new google.maps.MarkerImage('images/marker2.png')
			});

			marker4.setDraggable(true);
			// marker on click show infowindow

			infowindow4 = new google.maps.InfoWindow({
				content: '<strong>Title</strong><br>Tour Eiffel, 5 Avenue Anatole France, 75007 Paris, France<br>'
			});

			google.maps.event.addListener(marker4, 'click', function() {
				infowindow4.open(map4, marker4);
			});
		}
		google.maps.event.addDomListener(window, 'load', init_map4);
    }
}

// > Hover Tab   function ========================== //
export function initHover_tab(){
	jQuery('.circle-block-outer[data-toggle="tab-hover"] div').on('mouseenter', function(){
		jQuery(this).tab('show');
	});

}
//  related with content function by = owl.carousel.js ========================== //
export function initBlog_related_slider(items){
  // console.log('ici');
	jQuery('.blog-related-slider').owlCarousel({
		loop:false,
		margin:30,
		nav:true,
		dots: false,
		navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
		responsive:{
			0:{
				items:1
			},
			480:{
				items:2
			},
			767:{
				items:1
			},
            1023:{
                items:2
            }
		}
	});
}

// > magnificPopup function	by = magnific-popup.js =========================== //
export function inintMagnific_popup(){

    jQuery('.mfp-gallery').magnificPopup({
      delegate: '.mfp-link',
      type: 'image',
      tLoading: 'Loading image #%curr%...',
      mainClass: 'mfp-img-mobile',
      gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0,1] // Will preload 0 - before current, and 1 after the current image
      },
      image: {
        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        titleSrc: function(item) {

  				return  '<a  style="position: relative;bottom:30px;" class="v-button letter-spacing-4 font-12 text-uppercase p-l20" class="image-source-link" href="'+item.el.attr('data-href')+'">' + item.el.attr('title') + '</a>'+ '<div data-v-5cbbdb20="" class="listing-badges" ><span data-v-5cbbdb20="" class="featured letter-spacing-4 font-12 text-uppercase p-lr20"> '+ item.el.attr('subtitle') +'</span></div>';
  			}
      }
   });
    jQuery('.image-link-popup').magnificPopup({
     type:'image',
     gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0,1] // Will preload 0 - before current, and 1 after the current image
      },
   });
}
export function initOwl_fade_slider(){
// Fade slider function by = owl.carousel.js ========================== //

  var owl = jQuery('.owl-fade-slider-one').owlCarousel({
      items:1,
      loop:true,
      margin:10,
      autoplay:false,
      autoplayTimeout:25000,
      autoplayHoverPause:false,
      nav:true,
      navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
      dots: true,
      dotsData:true,
      dotsContainer: '#carousel-custom-dots',
      animateOut:'fadeOut',
      // autoWidth: true
      // autoHeight: true
  })

  jQuery('.owl-dot').click(function(){
    owl.trigger('to.owl.carousel', [$(this).index(), 300]);
  })

  jQuery('.play').click(function(){
      owl.trigger('play.owl.autoplay',[1000])
  })

  jQuery('.stop').click(function(){
      owl.trigger('stop.owl.autoplay')
  })

}

//  widget-client-carousel function by = owl.carousel.js ========================== //
export function initWidget_client(){
jQuery('.widget-client').owlCarousel({
    loop:true,
    autoplay:true,
    items:1,
    nav:false,
    dots:true,
    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
})	}

// > Main menu sticky on top  when scroll down function by = custom.js ========== //
export function initSticky_header(){
    if(jQuery('.sticky-header').length){
        var sticky = new Waypoint.Sticky({
          element: jQuery('.sticky-header')
        })
    }
}

// > page scroll top on button click function by = custom.js ===================== //
export function initScrollTop(){
  jQuery("button.scroltop").on('click', function() {
    jQuery("html, body").animate({
      scrollTop: 0
    }, 1000);
    return false;
  });

  jQuery(window).on("scroll", function() {
    var scroll = jQuery(window).scrollTop();
    if (scroll > 900) {
      jQuery("button.scroltop").fadeIn(1000);
    } else {
      jQuery("button.scroltop").fadeOut(1000);
    }
  });
}

export function initScrollPage(){
  jQuery("li.scroll-page").on('click', function() {
    jQuery("html, body").animate({
      scrollTop: $(".accommodation-list-search").offset().top
    }, 500);
    return false;
  });
}

export function initPopup_vertical_center(){
    jQuery(function() {
        function reposition() {
            var modal = jQuery(this),
            dialog = modal.find('.modal-dialog');
            modal.css('display', 'block');
            // Dividing by two centers the modal exactly, but dividing by three
            // or four works better for larger screens.
            dialog.css("margin-top", Math.max(0, (jQuery(window).height() - dialog.height()) / 2));
        }
        // Reposition when a modal is shown
        jQuery('.modal').on('show.bs.modal', reposition);
        // Reposition when the window is resized
        jQuery(window).on('resize', function() {
            jQuery('.modal:visible').each(reposition);
        });
    });
}

// > magnificPopup for video function by = magnific-popup.js ===================== //
export  function init_magnific_video(){
    jQuery('.mfp-video').magnificPopup({
      type: 'iframe',
    });
}

export function trigger_button_mobile_nav(mobile){
    if(!mobile) {
      return false;
    }

    // console.log('trigger_button_mobile_nav')
    // console.log(jQuery('.main-bar .navigation-default').hasClass('in'))
    // console.log(jQuery('.main-bar .navigation-default').attr('aria-expanded'))

    if(!jQuery('.main-bar .navigation-default').hasClass('in')) {
        if(!jQuery('.main-bar .navigation-default').attr('aria-expanded')) {
            jQuery('body').addClass('menu-open');
            jQuery('button.navbar-toggle').addClass('rotate');
        } else {
            jQuery('body').remove('menu-open');
            jQuery('button.navbar-toggle').removeClass('rotate');
        }
    } else {
        jQuery('body').remove('menu-open');
        jQuery('button.navbar-toggle').removeClass('rotate');
    }

 }

export function trigger_mobile_nav(mobile){
    if(!mobile) {
      return false;
    }

    // console.log('trigger_mobile_nav')
    // console.log(jQuery('.main-bar .navigation-default').hasClass('in'))
    // console.log(jQuery('.main-bar .navigation-default').attr('aria-expanded'))

    if(!jQuery('.main-bar .navigation-default').hasClass('in')) {
        if(!jQuery('.main-bar .navigation-default').attr('aria-expanded')) {
            jQuery('body').addClass('menu-open');
            jQuery('button.navbar-toggle').addClass('rotate');
        } else {
            jQuery('body').remove('menu-open');
            jQuery('button.navbar-toggle').removeClass('rotate');
        }
    } else {
        jQuery('body').remove('menu-open');
        jQuery('button.navbar-toggle').removeClass('rotate');
        jQuery('.header-simple.mode-mobile button.navbar-toggle').trigger('click');
    }

 }

// > Nav submenu show hide on mobile by = custom.js=============================
export function init_mobile_nav(mobile){

    if(!mobile) {
      return false;
    }

    jQuery(".sub-menu").parent('li').addClass('has-child');
    jQuery(".mega-menu").parent('li').addClass('has-child');
    jQuery("<div class='glyphicon glyphicon-chevron-right submenu-toogle'></div>").insertAfter(".has-child > a");
    jQuery('.header-simple.mode-mobile .has-child a+.submenu-toogle').on('click',function(ev) {

        jQuery(this).next(jQuery('.sub-menu')).slideToggle('fast', function(){
            jQuery(this).parent().toggleClass('nav-active');
        });
        ev.stopPropagation();
    });

  jQuery('.header-simple.mode-mobile .has-child').on('click',function(ev) {

   jQuery(this).find(jQuery('.sub-menu')).slideToggle('fast', function(){
    jQuery(this).parent().toggleClass('nav-active');
   });
  });

  // var load = true
  // jQuery('.header-simple.mode-mobile .main-bar .navigation-default li').on('click',function(ev) {
  //   jQuery('.header-simple.mode-mobile button.navbar-toggle').removeClass('rotate');
  //
  //   if(true === load) {
  //       load = false
  //
  //       return false;
  //   }
  //
  //   jQuery('.header-simple.mode-mobile button.navbar-toggle').trigger('click');
  //   ev.stopPropagation();
  // });
  //
  // jQuery('.header-simple.mode-mobile button.navbar-toggle').on('click',function(ev) {
  //
  //   if(!jQuery('.main-bar .navigation-default').hasClass('in')) {
  //       jQuery('body').addClass('menu-open');
  //       jQuery('button.navbar-toggle').addClass('rotate');
  //   } else {
  //       jQuery('body').remove('menu-open');
  //       jQuery('button.navbar-toggle').removeClass('rotate');
  //   }
  // });

    // jQuery('.header-simple.mode-mobile button.navbar-toggle').prop('disabled', false);
 }

// >Vertical Nav submenu show hide on mobile by = custom.js
export function init_vertical_nav(){

   jQuery(".v-sub-menu").parent('li').addClass('has-child');
   jQuery("<div class='glyphicon glyphicon-chevron-right submenu-toogle'></div>").insertAfter(".has-child > a");
   jQuery('.has-child a+.submenu-toogle').click(function(ev) {
     jQuery(this).next(jQuery('.v-sub-menu')).slideToggle('fast', function(){
       jQuery(this).parent().toggleClass('nav-active');
     });
     ev.stopPropagation();
   });
 }

//  > Top Search bar Show Hide function by = custom.js =================== //
 export function init_site_search() {
    jQuery('a[href="#search"]').on('click', function(event) {
        jQuery('#search').addClass('open');
        jQuery('#search > form > input[type="search"]').focus();
    });

    jQuery('#search, #search button.close').on('click keyup', function(event) {
        if (event.target === this || event.target.className === 'close') {
            jQuery(this).removeClass('open');
        }
    });
 }

 //  related with content function by = owl.carousel.js ========================== //
 export function init_blog_related_slider(){
	jQuery('.blog-related-slider').owlCarousel({
		margin:30,
		nav:true,
		dots: false,
		navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
		responsive:{
			0:{
				items:1
			},
			480:{
				items:2
			},
			767:{
				items:3
			},
			1000:{
				items:3
			}
		}
	});
	}
