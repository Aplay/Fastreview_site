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
        $('body').toggleClass('fixed');
        $(".top-menu").toggleClass("top-animate");
        $(".mid-menu").toggleClass("mid-animate");
        $(".bottom-menu").toggleClass("bottom-animate");
      }
      
  });


});
