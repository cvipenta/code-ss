/**
 * @author dan.rades
 */
Csid = {
	emptyFunction : function() {},
	pageName : '',
	scrollers : [],
	zodii : '',
	initialise : function() {
		if (typeof this.initors[this.pageName] == 'function') {
			Csid.initors[this.pageName]();
		}
		
		$('.glade_banners').each(function() {
			var _this = $(this);
			var _file = $('.glade_banner_file', _this).eq(0).val();
			var _height = $('.glade_banner_height', _this).eq(0).val();
			var _width = $('.glade_banner_width', _this).eq(0).val();
					
			_this.flash({
				src : _file,
				height : _height,
				width : _width, 
				wmode : 'transparent'
			});
		});
		
		$('#video_list_carousel').jCarouselLite({
	        btnNext: ".btnNext",
	        btnPrev: ".btnPrev",
			circular: true,
			visible : 3
	    });		
		
		var slideshowContests = $('#slideshowc')[0];
		if (slideshowContests) {
			$('#slideshowc').nextSlideshow({
				staticTime: 6000
			});
			slideshowContests.start();
		}
		
		this.main_article_details = $('.main_article_details');
		$('.main_article').each(function(i) {
			var article = $(this);
			article.mouseover(function() {
				$('.main_article_details').hide();
				$(Csid.main_article_details[i]).show();
				$('.main_article').removeClass('div_hover_1');
				$('.main_article').removeClass('div_hover_2');
				article.addClass('div_hover_1');
				article.addClass('div_hover_2'); 
				
			});
		});		

		var tabs = $('.tabsContainer');
		if (tabs.size() != 0) {			 			
			tabs.nextTabs();
		}
		
		$('.product_list').jCarouselLite({
	        btnNext: ".btnNext",
	        btnPrev: ".btnPrev",
			circular: true,
			visible : 5
	    });		
		
		this.zodii = $('div.zodie');
		
		$('#horoscop_selector').change(function() {
			$('.zodie').hide();
			$(Csid.zodii[this.value]).show();
		});
		
		this.regions = $('div.region');
		
		$('#region_selector').change(function() {
			$('.region').hide();
			$(Csid.regions[this.value]).show();
		});		
	}
	

}


Csid.initors = {
	'photoGallery' : function() {
		$('#product_list_slideshow').jCarouselLite({
	        btnNext: ".btnNext",
	        btnPrev: ".btnPrev",
			circular: false,
			visible : 4
	    });
		
		$('li',$('#product_list_slideshow')).each(function(i) {
			var slideshow = $('#slideshow')[0];
			$(this).click(function() {
				slideshow.jumpTo(i);
			});
		});
		
		$('#slideshow').nextSlideshow();		
	},
	
	'productsSlideshow' : function() {
		$('#product_list_slideshow').jCarouselLite({
	        btnNext: ".btnNext",
	        btnPrev: ".btnPrev",
			circular: false,
			visible : 5
	    });
		
		$('li',$('#product_list_slideshow')).each(function(i) {
			var slideshow = $('#slideshow')[0];
			$(this).click(function() {
				slideshow.jumpTo(i);
			});
		});
		
		$('#slideshow').nextSlideshow();		
	},
	
	'quizDetails' : function() {
		$('#quizz').nextQuiz({
			showScore : false
		});	
	},
	
	'babyNames' : function() {
		var letterClick = function(container) {
			$('.babyname_link', container).each(function(i) {
				var offset = $(this).offset();
				var bnDetails = $('.babynames_detalii', container);
				var currentDetails = $(bnDetails[i]);
				$('a', currentDetails).click(function() {
					currentDetails.hide();
				});
				$(this).click(function() {
					babyNamesDetalii.hide();
					currentDetails.css('top', offset.top + 15);
					var left = offset.left + 5;
					if (left > 600) {
						left = left - 300;
					}
					currentDetails.css('left', left);
					currentDetails.show();
				});
			});				
		}
		$('#select_localitati_infoutile').change(function() {
			window.location.href = this.value;
		});
		var letters = $('a', $('#letters'));
		var babyNamesDetalii = $('.babynames_detalii');
		var babyNamesList = $('.babynamelist');		
		var no_names = $('#no_names');

		letters.click(function() {
			var _self = $(this);
			var letter = _self.text();
			var detailsId = '#letter_'  + letter;
			var details = $(detailsId);
			babyNamesList.hide();
			if ($('li',details).length != 0) {
				no_names.hide();
				details.show();	
				letterClick($(detailsId));			
			}
			else {
				no_names.show();
			}
			letters.removeClass('selected');
			_self.addClass('selected');	
			
			
		});
		
		letterClick($(babyNamesList[0]));
		
	},
		
	'symptom_checker' : function() 
	{
		
		var currentCity = $('#symptom_city').val();
		
		$('#city').bind('change', function() 
		{
			var cityv = $(this).val();
			
			if (cityv != 0)
			{
				var url = '/symptom-checker/cauta/' + $('#symptom_gender').val() + '-' + $('#symptom_zone').val();					
				url += '/oras-' + cityv + '/#centre';			
				
			}
			
			window.location.href = url;
		});
		
		if (currentCity != 'undefined')
		{
			setTimeout(function() 
			{
					$('#city option[value="' + currentCity + '"]').attr('selected', true);		
			}, 100);
		}
		else
		{
			$('#city option').eq(0).attr('selected', true);
		}

	}
};

$(document).ready(function() {
	Csid.initialise();
	var selectedLink = $('.menu_link_selected');
	 $('li.menu_link').hover( function() {
		var menu_link = $(this);
		menu_link.addClass('menu_link_selected');
		$('.submenu', menu_link).css('display','block');
		
	},function() {
		var menu_link = $(this);
		menu_link.removeClass('menu_link_selected');
		$('.submenu', menu_link).css('display','none');
		selectedLink.removeClass('menu_link_selected').addClass('menu_link_selected');
	});	
});	
	
	
	

