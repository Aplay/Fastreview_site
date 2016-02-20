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
function toVoteArticle(id, vote){
    if(!$('#vote'+id).hasClass('active'))
        return;
    hpv = $('#csfr').attr('name'),
    hpt = $('#csfr').attr('value');
          
    var datav = {'id':id,'vote':vote};
    datav[hpv] = hpt;
    $.ajax({
        type: 'POST',
        dataType: 'json',
        data:datav,
        url: '/site/tovotearticle',

        success: function(data) {
            if(data.flag==true){
                $('#vote'+id).removeClass('active');
                if(vote == 1){
                    $('#vote'+id+' .user_pro').addClass('user_mine');
                    $('#vote'+id+' .user_num').html(data.count);
                } else {
                    $('#vote'+id+' .user_contra').addClass('user_mine');
                    $('#vote'+id+' .user_contra-num').html(data.count);
                }
            }
        }
    });
}