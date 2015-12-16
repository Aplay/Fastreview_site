$(document).ready(function() {

    var mousedownHappened = false;

    $('#header .form-control.search').on('focusin', function(){
        $(this).addClass('onfocusin');  
    }).on('focusout', function(){
        if(mousedownHappened) // cancel the blur event
        {
            $('#header .form-control.search').focus();
            mousedownHappened = false;
        }
        else   // blur event is okay
        {
            $(this).removeClass('onfocusin');
        }
    });
    $('a.redir').click(function(){
        var url = $(this).attr('href');
        var loc = $(this).attr('loc');
        $(this).attr('href',loc+"?url="+url);
    });
    $('#form_search input.hichinput').on('focus',function(e){
      e.preventDefault();
      $( "#form_search input.form-control" ).focus();
    });

    var dr_megamenu = false;
    var firstPosTop;  
    var position_ukfirst;
    var counterg;

    gorLoaded = function() {
       // function to invoke for loaded images
       // decrement the counter
       counterg--; 
       if( counterg === 0 ) {

        $(".ukfirst").each(function(i,v) {
          var thisPos = Math.round($(v).parent(".key").position().top);
          var nextPos = Math.round($(v).parent(".key").next().position().top); 
          if(thisPos > nextPos){
            $(v).removeClass("marazoom");
            $(v).parent().before("<div class=\'key springs\'></div><div class=\'key springs\'></div><div class=\'key springs\'></div>");
          }  
          if(thisPos == firstPosTop){
            $(v).removeClass("marazoom");
          }
        });
        
       }
    }
    runUkfirst = function(){

    $(".ukfirst").not(":first").addClass("marazoom"); 
    $(".key.springs").remove();

    firstPosTop = Math.round($(".ukfirst").first().parent().position().top);  
    position_ukfirst = $(".ukfirst");
    counterg = position_ukfirst.length;  // initialize the counter


    position_ukfirst.each(function(i,v) {
        if( this.complete ) {
            gorLoaded.call( this );
        } else {
            $(this).ready(gorLoaded)
        }
    });
    }
    
    $(window).on("debouncedresize", function(){
      if(dr_megamenu == true){
         runUkfirst();
       }
    });

    $('#city_menu').on('show.bs.modal', function () {
        if(dr_megamenu == false){
            var citypass = $('#dr_megamenu').data('city');
            var datav = {},
              hpv = $('#csfr').attr('name'),
              hpt = $('#csfr').attr('value');
              datav[hpv] = hpt;
            $.ajax({
                type: 'post',
                 url: '/site/getcities/' + citypass,
                data: datav,
                success: function(data)
                {
                    var obj = $.parseJSON(data);
                    if(obj.status == 'OK'){
                       // $('#dr_li').css('padding','10px 20px 10px 20px');
                        var ara = []; var ukfirst = '';
                        for (i=0; i < obj.arWrapper['k'].length; i++) {
                           ukfirst = obj.arWrapper['v'][i].data_ukfirst;
                           if($.inArray(ukfirst,ara) == -1){
                                ara.push(ukfirst);
                                $('#dr_field').append('<div class="key"><span class="ukfirst marazoom">'+ ukfirst +'</span></div>');
                           }
                           $('#dr_field').append('<div class="key"><a href="'+ obj.arWrapper['v'][i].url +'">'+ obj.arWrapper['v'][i].title +'</a></div>');
                        }

                        dr_megamenu = true;
                        runUkfirst();
                    }
                    
                }
            })
            
        }
    });

    $('#searchInput').on('focusin',function(){
      $(this).parent('md-input-container').addClass('md-input-focused md-input-has-value');
    }).on('focusout',function(e){
        $(this).parent('md-input-container').removeClass('md-input-focused md-input-has-value');
    });
    $('md-input-container:not(.md-input-focused)').on('click',function(e){
      $(this).addClass('md-input-focused md-input-has-value');
      $('#searchInput').focus();
    });


    $('#topFormSearch .zoomSearch').click(function(e){

     $( "#searchInput" ).trigger( "click" );
     /* if( $('#topFormSearch').isValid() ){
            $('#topFormSearch').submit();
        }else{
            e.preventDefault();
        }
      */
    });
    
});
(function( $ ){
    $.fn.isValid = function() {
        return document.getElementById(this[0].id).checkValidity();
    };
})( jQuery );