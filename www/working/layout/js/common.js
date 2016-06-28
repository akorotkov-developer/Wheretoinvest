(function($){
  $(function() {
  "use strict";


    $(document).foundation();
    
    // your code goes here

    /**
     * Created by Евгений on 28.04.2016.
     */


    (function(){
      alert('g');


      $('.js-show-menu').on('click',function(){
          $('.js-menu').toggle(500);
      });
    })();


  });
})(jQuery);