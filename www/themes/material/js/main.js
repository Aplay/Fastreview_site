$(document).ready(function() {

  /* ========================================================================

     Fullscreen burger menu

   ========================================================================== */

  $(".menu-trigger").click(function () {
    $(".mobilenav").fadeToggle(500);
    $("#mobilenav_search").hide();
    if(!$('body').hasClass('fixed')){
      $("#mobilenav_links").show();
    } else {
      $("#mobilenav_links").hide();
    }
      $('body').toggleClass('fixed');
      $(".top-menu").toggleClass("top-animate");
      $(".mid-menu").toggleClass("mid-animate");
      $(".bottom-menu").toggleClass("bottom-animate");
  });

  $(".search-trigger").click(function () {
      if(!$('body').hasClass('fixed')){

        $(".mobilenav").fadeToggle(500);
        $("#mobilenav_links").hide();
        $("#mobilenav_search").show();
        $('#searchFieldReviewObjectBlue').focus();
        $('body').toggleClass('fixed');
        $(".top-menu").toggleClass("top-animate");
        $(".mid-menu").toggleClass("mid-animate");
        $(".bottom-menu").toggleClass("bottom-animate");
      }
      
  });

  $('.trunk_1').trunk8({lines:1, tooltip: false});
    $('.trunk_2').trunk8({lines:2, tooltip: false});
    $('.trunk_3').trunk8({lines:3, tooltip: false});
    $('.trunk_4').trunk8({lines:4, tooltip: false});
    $('.trunk_8').trunk8({lines:8, tooltip: false});
   $(window).on('debouncedresize', function(){
    $('.trunk_1').trunk8({lines:1, tooltip: false});
    $('.trunk_2').trunk8({lines:2, tooltip: false});
    $('.trunk_3').trunk8({lines:3, tooltip: false});
    $('.trunk_4').trunk8({lines:4, tooltip: false});
    $('.trunk_8').trunk8({lines:8, tooltip: false});
   });

});
