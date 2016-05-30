(function($){
  $(function() {
  "use strict";


    $(document).foundation();
    
    // your code goes here

    (function(){
      $('.js-show-menu').on('click',function(){
          $('.js-menu').toggle(500);
      });
    })();


  });
})(jQuery);