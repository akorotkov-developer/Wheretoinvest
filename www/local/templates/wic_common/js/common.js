(function ($) {
    $(function () {
        "use strict";



        $.getJSON('/regions.json', function(data){
            //$('.js-regions').html('');
            $("#myTags").tagit( {
                availableTags: data
            });
            window.olo =  new Array();
            $.each( data, function( key, value ) {

               window.olo = window.olo + value;
            });
            console.log(window.olo);
        });
        if($('.js-regions').length){


            var regions = $('.js-regions').html().split(' ');
             $.getJSON('/regions.json', function(data){
                 $('.js-regions').html('');
                 $.each( regions, function( key, value ) {

                     var div = '<span class="js-region region" style="border: 1px solid #cccccc;height: 40px;padding: 0px 5px;'
                     +'margin: 0px 5px;" contenteditable="false">'+data[value]+'</span>';
                     $('.js-regions').append(div);
                 });

            });



        }

        $(document).foundation({
            equalizer : {
                equalize_on_stack: true
            },

        });

        $(document).foundation('tab', 'reflow');

        $('.x-slick').slick({
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000
        });
		
		$('#x-top-search-btn').click(function(){
			
			if ( $('#x-top-search-form:visible').length ) {
				if ( $('#x-top-search-form input').val() ) {
					$('#x-top-search-form').submit();
				} else {
					$('#x-top-search-form').hide();
				}
			} else {
				$('#x-top-search-form').show();
			}
			return false;
			
		});

        // your code goes here

        /*(function(){

         })();*/

    });
	
	$(".menu-footer .toggle-topbar.menu-icon a").click(function(){
		var scrollHeight = $("body")[0]["scrollHeight"];
		$('html').animate({ scrollTop: scrollHeight }, 1000);	 
	}); 	
	
})(jQuery);