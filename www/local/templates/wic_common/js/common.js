(function ($) {
    $(function () {
        "use strict";
        $('[name="orginfo"]').on('submit',function(){
            $('body .js-cols').each(function(i,elem) {
              //  alert(i)
            });
            //return false;
        });
        $('body ').on('click','.js-addcols',function(){
            $('.js-cols').append(
                '<div class="input-group"><input type="text" name="sum[]" class="js-col1 input-group-field"><span class="input-group-label">—</span>'
            +'<input type="text" name="sum2[]" class="js-col2 input-group-field"><div class="input-group-button"><button type="button" class="button hollow success js-addcols">Добавить</button>'
                +'</div>                </div>');

        });
        $('body ').on('click','.js-addrows',function(){
            $('.js-rows').append(
                '<div class="row collapse" ><div class="column small-6"><div class="input-group"><input type="text"  name="day[]" class="js-row1 input-group-field"><span class="input-group-label">—</span>'
            + '<input type="text" name="day2[]" class="js-row2 input-group-field">                </div>                </div>'
            +'<div class="column small-4 large-3"><select name="day3[]" class="js-row3"><option selected="" value="1">Дней</option><option value="2">Месяцев</option><option value="3">Лет</option></select>'
              +'  </div><div class="column small-12 large-3"><button type="button" class="button hollow success js-addrows">Добавить</button></div> </div>');
        });

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