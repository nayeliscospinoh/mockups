/*header Extras*/
( function( $ ) {
	"use strict";
	
	var WidgetHeaderExtras = function($scope, $) {
		var $container = $scope.find('.header-extra-icons');
		
		if($(".header-extra-icons .search-icon",$scope).length){
			var search_container =$(".header-extra-icons .search-icon",$scope);
			var search_icon =search_container.find(".plus-post-search-icon");
			var search_icon_close =search_container.find(".plus-search-close");
			
			search_icon.on('click',function(event){
				var $this=$(this),anim='';
				var form_content=$this.closest(".search-icon").find(".plus-search-form");
				var form_content_style=form_content.data("style");
				var animDuration = 500;
				if((form_content_style=='style-1' || form_content_style=='style-3') && !form_content.hasClass("open")){
					form_content.addClass("open");
					form_content.css({
						opacity: 0,
						display: "block"
					}),
					form_content.css("transform","perspective(200px) translateZ(30px)"),
					form_content.animate({
						transform: "none",
						opacity: 1
					}, {
						ease: "easeOutQuart",
						duration:animDuration,
						complete: function() {
							form_content.css("transform", "none")
						}
					});
				}
				if(form_content_style=='style-2' || form_content_style=='style-4'){
					form_content.toggleClass("open");
				}
			});
			search_icon_close.on('click',function(){
				var $this=$(this),anim='';
				var form_content=$this.closest(".plus-search-form");
				var form_content_style=form_content.data("style");
				var animDuration = 300;
				if((form_content_style=='style-1' || form_content_style=='style-3') && form_content.hasClass("open")){
					form_content.removeClass("open");
					form_content.css("transform","perspective(200px) translateZ(30px)"),
					form_content.animate({
						transform: "perspective(200px) translateZ(30px)",
						opacity: 0
					}, {
						duration :animDuration,
						ease: "easeInQuad",
						complete: function() {
							form_content.css("display", "none")
						}
					});
				}
				if(form_content_style=='style-2' || form_content_style=='style-4'){
					form_content.toggleClass("open");
				}
			});
			/*outside search div close search*/
			$(document).mouseup(function(e) {
				  var container = $(".plus-search-form.plus-search-form-content.style-3");
				  if(!container.is(e.target) && container.has(e.target).length === 0) {					
					container.removeClass('open').css('opacity','0').css('display','none').css('transform','perspective(200px) translateZ(30px)');					
				  }
				  
				  var container_4 = $(".header-extra-icons .plus-search-form.plus-search-form-content.style-4");
				  if(!container_4.is(e.target) && container_4.has(e.target).length === 0) {					
					container_4.removeClass('open');					
				  }
				  
				  var container_2 = $(".header-extra-icons .plus-search-form.plus-search-form-content.style-2");
				  if(!container_2.is(e.target) && container_2.has(e.target).length === 0) {					
					container_2.removeClass('open');					
				  }
			});			
			/*outside search div close search*/
		}
		if($(".header-extra-icons .mini-cart-icon",$scope).length){
		var timeout;
			if($(".header-extra-icons .mini-cart-icon.style-1",$scope).length){
				$('.header-extra-icons .mini-cart-icon.style-1 .content-icon-list').on("mouseover",function(){
					$('.tpmc-header-extra-toggle-content-ext',this).addClass('open');
					$('.widget_shopping_cart',this).addClass('open');
					clearTimeout(timeout);				
				});
				$('body').on('mouseleave','.header-extra-icons .mini-cart-icon.style-1 .content-icon-list',function(){
					var $that = $(this);
					setTimeout(function(){
						if(!$that.is(':hover')){							
							$that.find('.widget_shopping_cart').removeClass('open');
							$that.find('.tpmc-header-extra-toggle-content-ext').removeClass('open');
						}
					},50);
				});
			}
		}
	};
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/tp-header-extras.default', WidgetHeaderExtras);
	});
})(jQuery);