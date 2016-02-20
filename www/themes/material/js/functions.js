
/*
* Layout
*/

(function(){
    //Get saved layout type from LocalStorage
    var layoutStatus = localStorage.getItem('ma-layout-status');
    if (layoutStatus == 1 && $('#sidebar').length) {
        $('body').addClass('sw-toggled');
        $('#tw-switch').prop('checked', true);
    }
    $('body').on('change', '#toggle-width input:checkbox', function(){
        if ($(this).is(':checked')) {
            setTimeout(function(){
                $('body').addClass('toggled sw-toggled');
                localStorage.setItem('ma-layout-status', 1);
            }, 250);
        }
        else {
            setTimeout(function(){
                $('body').removeClass('toggled sw-toggled');
                localStorage.setItem('ma-layout-status', 0);
                $('.main-menu > li').removeClass('animated');
            }, 250);
        }
    });
})();

    
/*
 * Detact Mobile Browser
 */
if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
   $('html').addClass('ismobile');
}
    
$(document).ready(function(){
    /*
     * Top Search
     */
    (function(){
        $('body').on('click', '#top-search > a', function(e){
            e.preventDefault();
            
            $('#header').addClass('search-toggled');
            $('#top-search-wrap input').focus();
        });
        
        $('body').on('click', '#top-search-close', function(e){
            e.preventDefault();
            
            $('#header').removeClass('search-toggled');
        });
    })();
        

    
    /*
     * Sidebar
     */
    (function(){
        //Toggle
        $('body').on('click', '#menu-trigger, #chat-trigger', function(e){            
            e.preventDefault();
            var x = $(this).data('trigger');
        
            $(x).toggleClass('toggled');
            $(this).toggleClass('open');


            $('body').toggleClass('modal-open');
	    
    	    //Close opened sub-menus
    	    $('.sub-menu.toggled').not('.active').each(function(){
        		$(this).removeClass('toggled');
        		$(this).find('ul').hide();
    	    });
            
            

	    $('.profile-menu .main-menu').hide();
            
            if (x == '#sidebar') {
                
                $elem = '#sidebar';
                $elem2 = '#menu-trigger';
                $elem3 = '#main_container .popover';
                
                $('#chat-trigger').removeClass('open');
                
                if (!$('#chat').hasClass('toggled')) {
                    $('#header').toggleClass('sidebar-toggled');
                }
                else {
                    $('#chat').removeClass('toggled');
                }

            }
            
            if (x == '#chat') {
                $elem = '#chat';
                $elem2 = '#chat-trigger';
                $elem3 = '#main_container .popover';
                
                $('#menu-trigger').removeClass('open');
                
                if (!$('#sidebar').hasClass('toggled')) {
                    $('#header').toggleClass('sidebar-toggled');
                }
                else {
                    $('#sidebar').removeClass('toggled');
                }
            }
            
            //When clicking outside
          /*  if ($('#header').hasClass('sidebar-toggled')) {
                $(document).on('click', function (e) {
                    if (($(e.target).closest($elem).length === 0) && ($(e.target).closest($elem2).length === 0) && ($(e.target).closest($elem3).length === 0)) {
                        setTimeout(function(){
                            $('body').removeClass('modal-open');
                            $($elem).removeClass('toggled');
                            $('#header').removeClass('sidebar-toggled');
                            $($elem2).removeClass('open');
                        });
                    }
                });
            }
            clearScroll();*/
        })
        
        //Submenu
        $('body').on('click', '.sub-menu > a', function(e){
            e.preventDefault();
            $(this).next().slideToggle(200);
            $(this).parent().toggleClass('toggled');
        });
    })();
    
    /*
     * Clear Notification
     */
    $('body').on('click', '[data-clear="notification"]', function(e){
      e.preventDefault();
    
      var x = $(this).closest('.listview');
      var y = x.find('.lv-item');
      var z = y.size();
      
      $(this).parent().fadeOut();
      
      x.find('.list-group').prepend('<i class="grid-loading hide-it"></i>');
      x.find('.grid-loading').fadeIn(1500);
      
          
      var w = 0;
      y.each(function(){
          var z = $(this);
          setTimeout(function(){
          z.addClass('animated fadeOutRightBig').delay(1000).queue(function(){
              z.remove();
          });
          }, w+=150);
      })
	
	//Popup empty message
	setTimeout(function(){
	    $('#notifications').addClass('empty');
	}, (z*150)+200);
    });
    
    /*
     * Dropdown Menu
     */
    if($('.dropdown')[0]) {
	//Propagate
	$('body').on('click', '.dropdown.open .dropdown-menu', function(e){
	    e.stopPropagation();
	});
	
	$('.dropdown').on('shown.bs.dropdown', function (e) {
	    if($(this).attr('data-animation')) {
		$animArray = [];
		$animation = $(this).data('animation');
		$animArray = $animation.split(',');
		$animationIn = 'animated '+$animArray[0];
		$animationOut = 'animated '+ $animArray[1];
		$animationDuration = ''
		if(!$animArray[2]) {
		    $animationDuration = 500; //if duration is not defined, default is set to 500ms
		}
		else {
		    $animationDuration = $animArray[2];
		}
		
		$(this).find('.dropdown-menu').removeClass($animationOut)
		$(this).find('.dropdown-menu').addClass($animationIn);
	    }
	});
	
	$('.dropdown').on('hide.bs.dropdown', function (e) {
	    if($(this).attr('data-animation')) {
    		e.preventDefault();
    		$this = $(this);
    		$dropdownMenu = $this.find('.dropdown-menu');
    	
    		$dropdownMenu.addClass($animationOut);
    		setTimeout(function(){
    		    $this.removeClass('open')
    		    
    		}, $animationDuration);
    	    }
    	});
    }
    
    /*
     * Calendar Widget
     */
    if($('#calendar-widget')[0]) {
        (function(){
            $('#calendar-widget').fullCalendar({
                contentHeight: 'auto',
                theme: true,
                header: {
                    right: '',
                    center: 'prev, title, next',
                    left: ''
                },
                defaultDate: '2014-06-12',
                editable: true,
                events: [
                    {
                        title: 'All Day',
                        start: '2014-06-01',
                        className: 'bgm-cyan'
                    },
                    {
                        title: 'Long Event',
                        start: '2014-06-07',
                        end: '2014-06-10',
                        className: 'bgm-orange'
                    },
                    {
                        id: 999,
                        title: 'Repeat',
                        start: '2014-06-09',
                        className: 'bgm-lightgreen'
                    },
                    {
                        id: 999,
                        title: 'Repeat',
                        start: '2014-06-16',
                        className: 'bgm-lightblue'
                    },
                    {
                        title: 'Meet',
                        start: '2014-06-12',
                        end: '2014-06-12',
                        className: 'bgm-green'
                    },
                    {
                        title: 'Lunch',
                        start: '2014-06-12',
                        className: 'bgm-cyan'
                    },
                    {
                        title: 'Birthday',
                        start: '2014-06-13',
                        className: 'bgm-amber'
                    },
                    {
                        title: 'Google',
                        url: 'http://google.com/',
                        start: '2014-06-28',
                        className: 'bgm-amber'
                    }
                ]
            });
        })();
    }
    
    /*
     * Weather Widget
     */
    if ($('#weather-widget')[0]) {
        $.simpleWeather({
            location: 'Austin, TX',
            woeid: '',
            unit: 'f',
            success: function(weather) {
                html = '<div class="weather-status">'+weather.temp+'&deg;'+weather.units.temp+'</div>';
                html += '<ul class="weather-info"><li>'+weather.city+', '+weather.region+'</li>';
                html += '<li class="currently">'+weather.currently+'</li></ul>';
                html += '<div class="weather-icon wi-'+weather.code+'"></div>';
                html += '<div class="dash-widget-footer"><div class="weather-list tomorrow">';
                html += '<span class="weather-list-icon wi-'+weather.forecast[2].code+'"></span><span>'+weather.forecast[1].high+'/'+weather.forecast[1].low+'</span><span>'+weather.forecast[1].text+'</span>';
                html += '</div>';
                html += '<div class="weather-list after-tomorrow">';
                html += '<span class="weather-list-icon wi-'+weather.forecast[2].code+'"></span><span>'+weather.forecast[2].high+'/'+weather.forecast[2].low+'</span><span>'+weather.forecast[2].text+'</span>';
                html += '</div></div>';
                $("#weather-widget").html(html);
            },
            error: function(error) {
                $("#weather-widget").html('<p>'+error+'</p>');
            }
        });
    }
    /*
     * Todo Add new item
     */
    if ($('#todo-lists')[0]) {
        //Add Todo Item
        $('body').on('click', '#add-tl-item .add-new-item', function(){
            $(this).parent().addClass('toggled'); 
        });
            
            //Dismiss
            $('body').on('click', '.add-tl-actions > a', function(e){
                e.preventDefault();
                var x = $(this).closest('#add-tl-item');
                var y = $(this).data('tl-action');
                            
                if (y == "dismiss") {
                    x.find('textarea').val('');
                    x.removeClass('toggled'); 
                }
                
                if (y == "save") {
                    x.find('textarea').val('');
                    x.removeClass('toggled'); 
                }
        });
    }
    
 
    /*
     * Auto Hight Textarea
     */
    if ($('.auto-size')[0]) {
	   $('.auto-size').autosize();
    }
    
    /*
     * Custom Scrollbars
     */
 /*   function scrollbar(className, color, cursorWidth) {
        $(className).niceScroll({
            cursorcolor: color,
            cursorborder: 0,
            cursorborderradius: 0,
            cursorwidth: cursorWidth,
            bouncescroll: true,
            mousescrollstep: 100,
            //autohidemode: false
        });
    } 

    //Scrollbar for HTML(not mobile) but not for login page
    if (!$('html').hasClass('ismobile')) {
        if (!$('.login-content')[0]) {
            scrollbar('html', 'rgba(0,0,0,0.3)', '5px');
        }
        
        //Scrollbar Tables
        if ($('.table-responsive')[0]) {
            scrollbar('.table-responsive', 'rgba(0,0,0,0.5)', '5px');
        }
        
        //Scrill bar for Chosen
        if ($('.chosen-results')[0]) {
            scrollbar('.chosen-results', 'rgba(0,0,0,0.5)', '5px');
        }
        
        //Scroll bar for tabs
        if ($('.tab-nav')[0]) {
            scrollbar('.tab-nav', 'rgba(0,0,0,0)', '1px');
        }
    
        //Scroll bar for dropdowm-menu
        if ($('.dropdown-menu .c-overflow')[0]) {
            scrollbar('.dropdown-menu .c-overflow', 'rgba(0,0,0,0.5)', '0px');
        }
    
        //Scrollbar for rest
        if ($('.c-overflow')[0]) {
            scrollbar('.c-overflow', 'rgba(0,0,0,0.5)', '5px');
        }
    }
    */
    
    /*
    * Profile Menu
    */
  /*  $('body').on('click', '.profile-menu > a', function(e){
        e.preventDefault();
        $(this).parent().toggleClass('toggled');
        $(this).next().slideToggle(200);
    });
  */
    /*
     * Text Feild
     */
    
    //Add blue animated border and remove with condition when focus and blur
    if($('.fg-line')[0]) {
        $('body').on('focus', '.form-control', function(){
            $(this).closest('.fg-line').addClass('fg-toggled');
        })

        $('body').on('blur', '.form-control', function(){
            var p = $(this).closest('.form-group');
            var i = p.find('.form-control').val();
            
            if (p.hasClass('fg-float')) {
                if (i.length == 0) {
                    $(this).closest('.fg-line').removeClass('fg-toggled');
                }
            }
            else {
                $(this).closest('.fg-line').removeClass('fg-toggled');
            }
        });
    }
    
    //Add blue border for pre-valued fg-flot text feilds
    if($('.fg-float')[0]) {
        $('.fg-float .form-control').each(function(){
            var i = $(this).val();
            
            if (!i.length == 0) {
                $(this).closest('.fg-line').addClass('fg-toggled');
            }
            
        });
    }
    
    /*
     * Audio and Video
     */
    if($('audio, video')[0]) {
       /* $('video,audio').mediaelementplayer(); */
    }
    
    /*
     * Custom Select
     */
    if ($('.selectpickers')[0]) {
        $('.selecstpicker').selectpicker();
    }
    
    /*
     * Tag Select
     */
    if($('.tag-select')[0]) {
        $('.tag-select').chosen({
            width: '100%',
            allow_single_deselect: true
        });
    }
    
    /*
     * Input Slider
     */ 
    //Basic
    if($('.input-slider')[0]) {
        $('.input-slider').each(function(){
            var isStart = $(this).data('is-start');
            
            $(this).noUiSlider({
                start: isStart,
                range: {
                    'min': 0,
                    'max': 100,
                }
            });
        });
    }
	
    //Range slider
    if($('.input-slider-range')[0]) {
    $('.input-slider-range').noUiSlider({
        start: [30, 60],
        range: {
            'min': 0,
            'max': 100
        },
        connect: true
    });
    }
    
    //Range slider with value
    if($('.input-slider-values')[0]) {
    $('.input-slider-values').noUiSlider({
        start: [ 45, 80 ],
        connect: true,
        direction: 'rtl',
        behaviour: 'tap-drag',
        range: {
            'min': 0,
            'max': 100
        }
    });

	$('.input-slider-values').Link('lower').to($('#value-lower'));
        $('.input-slider-values').Link('upper').to($('#value-upper'), 'html');
    }
    
    /*
     * Input Mask
     */
    if ($('input-mask')[0]) {
        $('.input-mask').mask();
    }
    
    /*
     * Color Picker
     */
    if ($('.color-picker')[0]) {
    $('.color-picker').each(function(){
        $('.color-picker').each(function(){
            var colorOutput = $(this).closest('.cp-container').find('.cp-value');
                $(this).farbtastic(colorOutput);
            });
        });
    }
    
    /*
     * HTML Editor
     */
    if ($('.html-editor')[0]) {
	$('.html-editor').summernote({
            height: 150
        });
    }
    
    if($('.html-editor-click')[0]) {
	//Edit
	$('body').on('click', '.hec-button', function(){
	    $('.html-editor-click').summernote({
            focus: true
	    });
	    $('.hec-save').show();
	})
	
	//Save
	$('body').on('click', '.hec-save', function(){
        $('.html-editor-click').code();
            $('.html-editor-click').destroy();
            $('.hec-save').hide();
            notify('Content Saved Successfully!', 'success');
        });
    }
    
    //Air Mode
    if($('.html-editor-airmod')[0]) {
        $('.html-editor-airmod').summernote({
            airMode: true
        });
    }
    
    /*
     * Date Time Picker
     */
    
    //Date Time Picker
    if ($('.date-time-picker')[0]) {
	   $('.date-time-picker').datetimepicker();
       // add this if many datetimepickers
	   $(this).each(function(){
		    $(this).datetimepicker()
		});
    }
    
    //Time
    if ($('.time-picker')[0]) {
    	$('.time-picker').each(function(){
		    $(this).datetimepicker({
		    	locale: 'ru',
		        format: 'HH:mm',
		      //  keepInvalid: true,
		      //  enabledHours:[18,19,20,21,22,23,24]
		    });
		});
    }
    
    //Date
    if ($('.date-picker')[0]) {
        // add each for many datetimepickers
    	$('.date-picker').each(function(){
		    	$(this).datetimepicker({
    	    	format: 'DD/MM/YYYY'
    		});
		});
    }

    /*
     * Form Wizard
     */
    
    if ($('.form-wizard-basic')[0]) {
    	$('.form-wizard-basic').bootstrapWizard({
    	    tabClass: 'fw-nav',
            'nextSelector': '.next', 
            'previousSelector': '.previous'
    	});
    }
    
    /*
     * Bootstrap Growl - Notifications popups
     */ 
    function notify(message, type){
        $.growl({
            message: message
        },{
            type: type,
            allow_dismiss: false,
            label: 'Cancel',
            className: 'btn-xs btn-inverse',
            placement: {
                from: 'top',
                align: 'right'
            },
            delay: 2500,
            animate: {
                    enter: 'animated bounceIn',
                    exit: 'animated bounceOut'
            },
            offset: {
                x: 20,
                y: 85
            }
        });
    };

    /*
     * Waves Animation
     */
   /* (function(){
         Waves.attach('.btn:not(.btn-icon):not(.btn-float)');
         Waves.attach('.btn-icon, .btn-float', ['waves-circle', 'waves-float']);
        Waves.init();
    })();*/
    
    /*
     * Lightbox
     */
    if ($('.lightbox')[0]) {
        $('.lightbox').lightGallery({
            enableTouch: true
        }); 
    }
    
    /*
     * Link prevent
     */
    $('body').on('click', '.a-prevent', function(e){
        e.preventDefault();
    });
    
    /*
     * Collaspe Fix
     */
    if ($('.collapse')[0]) {
        
        //Add active class for opened items
        $('.collapse').on('show.bs.collapse', function (e) {
            $(this).closest('.panel').find('.panel-heading').addClass('active');
        });
   
        $('.collapse').on('hide.bs.collapse', function (e) {
            $(this).closest('.panel').find('.panel-heading').removeClass('active');
        });
        
        //Add active class for pre opened items
        $('.collapse.in').each(function(){
            $(this).closest('.panel').find('.panel-heading').addClass('active');
        });
    }
    
    /*
     * Tooltips
     */
    if ($('[data-toggle="tooltip"]')[0]) {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    /*
     * Popover
     */
    if ($('[data-toggle="popover"]')[0]) {
        $('[data-toggle="popover"]').popover();
    }
    
    /*
     * Message
     */

    //Actions
    if ($('.on-select')[0]) {
        var checkboxes = '.lv-avatar-content input:checkbox';
        var actions = $('.on-select').closest('.lv-actions');
    
        $('body').on('click', checkboxes, function() {
            if ($(checkboxes+':checked')[0]) {
                actions.addClass('toggled');
            }
            else {
                actions.removeClass('toggled');
            }
        });
    }

    if($('#ms-menu-trigger')[0]) {
        $('body').on('click', '#ms-menu-trigger', function(e){            
            e.preventDefault();
            $(this).toggleClass('open');
            $('.ms-menu').toggleClass('toggled');
        });
    }
    
    /*
     * Login
     */
     /*
     if ($('.login-content')[0]) {
        //Add class to HTML. This is used to center align the logn box
        $('html').addClass('login-content');
        
        $('body').on('click', '.login-navigation > li', function(){
            var z = $(this).data('block');
            var t = $(this).closest('.lc-block');
            
            t.removeClass('toggled');
            
            setTimeout(function(){
                $(z).addClass('toggled');
            });
            
        })
    } */
    
    /*
     * Fullscreen Browsing
     */
    if ($('[data-action="fullscreen"]')[0]) {
    var fs = $("[data-action='fullscreen']");
    fs.on('click', function(e) {
        e.preventDefault();
                
        //Launch
        function launchIntoFullscreen(element) {
        
        if(element.requestFullscreen) {
            element.requestFullscreen();
        } else if(element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if(element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if(element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
        }
        
        //Exit
        function exitFullscreen() {
        
        if(document.exitFullscreen) {
            document.exitFullscreen();
        } else if(document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if(document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
        }
        
        launchIntoFullscreen(document.documentElement);
        fs.closest('.dropdown').removeClass('open');
    });
    }
    
    
    /*
     * Clear Local Storage
     */
    if ($('[data-action="clear-localstorage"]')[0]) {
        var cls = $('[data-action="clear-localstorage"]');
        
        cls.on('click', function(e) {
            e.preventDefault();
            
            swal({   
                title: "Are you sure?",   
                text: "All your saved localStorage values will be removed",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Yes, delete it!",   
                closeOnConfirm: false 
            }, function(){
                localStorage.clear();
                swal("Done!", "localStorage is cleared", "success"); 
            });
        });
    }
    
    /*
     * Profile Edit Toggle
     */
    if ($('[data-pmb-action]')[0]) {
        $('body').on('click', '[data-pmb-action]', function(e){
            e.preventDefault();
            var d = $(this).data('pmb-action');
            
            if (d === "edit") {
                $(this).closest('.pmb-block').toggleClass('toggled');
            }
            
            if (d === "reset") {
                $(this).closest('.pmb-block').removeClass('toggled');
            }
            
            
        });
    }

    /*
     * IE 9 Placeholder
     */
    if($('html').hasClass('ie9')) {
        $('input, textarea').placeholder({
            customClass: 'ie9-placeholder'
        });
    }

    /*
     * Listview Search
     */ 
    if ($('.lvh-search-trigger')[0]) {
         
        
        $('body').on('click', '.lvh-search-trigger', function(e){
            e.preventDefault();
            x = $(this).closest('.lv-header-alt').find('.lvh-search');
            
            x.fadeIn(300);
            x.find('.lvhs-input').focus();
        });
        
        //Close Search
        $('body').on('click', '.lvh-search-close', function(){
            x.fadeOut(300);
            setTimeout(function(){
                x.find('.lvhs-input').val('');
            }, 350);
        })
    }

     /*
     * Print
     */
    if ($('[data-action="print"]')[0]) {
        $('body').on('click', '[data-action="print"]', function(e){
            e.preventDefault();
            
            window.print();
        })
    }


    /*
     * Wall
     */
    if ($('.wcc-toggle')[0]) {
        var z = '<div class="wcc-inner">' +
                    '<textarea class="wcci-text auto-size" placeholder="Write Something..."></textarea>' +
                '</div>' +
                '<div class="m-t-15">' +
                    '<button class="btn btn-sm btn-primary">Post</button>' +
                    '<button class="btn btn-sm btn-link wcc-cencel">Cancel</button>' +
                '</div>'
                
        
        $('body').on('click', '.wcc-toggle', function() {
            $(this).parent().html(z);
            autosize($('.auto-size')); //Reload Auto size textarea
        });
        
        //Cancel
        $('body').on('click', '.wcc-cencel', function(e) {
            e.preventDefault();
            
            $(this).closest('.wc-comment').find('.wcc-inner').addClass('wcc-toggle').html('Write Something...')
        });
        
    }
    
    /* mscript */
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
    var dr_megamenu_2 = false;
    
    
    

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
	    });

	/* ------------------ for new style ------------------------- */


		$('.select_city_link').popover({
		    html: true,
		    trigger:'click',
		    placement: 'bottom',
		    template: '<div class=\"popover select_city_popover\" role=\"tooltip\"><div class=\"arrow\"></div><div class=\"popover-content popover-city\"></div></div>',
		    content: function () {
		        return $('#select_city').find('.content').html();
		    }
		}).on('show.bs.popover', function () {
			
	      //  if(dr_megamenu_2 == false){
	              doGetc(this,null);   
	      //  } 
	      clearScroll();
	        
		}).on('shown.bs.popover', function () {

			clearScroll();

	    }).on('hiden.bs.popover', function () {
	    	clearScroll();
	    }).on('click', function(){
	        $(this).toggleClass('active');
	        clearScroll();
	    });

	   
	    $('#header_user_box2').on('click',function() {
		  $('#nav-li-last').toggleClass('activep');
		});


	    	$('#header_user_box').popover({
			    html: true,
			    trigger:'click',
			    placement: 'bottom',
			    template: '<div class=\"popover select_city_popover user_header_popover\" role=\"tooltip\"><div class=\"arrow\"></div><div class=\"popover-content p-0\"></div></div>',
			    content: function () {
			        return $('#user_header_menu').html();
			    }
			}).on('click', function(){
		        $('#nav-li-last').toggleClass('activep');
		       // clearScroll();
		    });
			$('#header_user_box').on('shown.bs.popover', function () {
                $('#user_header_menu_li a').on('mouseenter',function(){
                  $('.popover.select_city_popover.bottom > .arrow').addClass('active');
                }).on('mouseleave',function(){
                  $('.popover.select_city_popover.bottom > .arrow').removeClass('active');
                });
            })

		

	$('html').on('click', function(e) {
	  if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover.in')) {
	    $('[data-original-title]').popover('hide');
	    $('.select_city_link').removeClass('active');
	    $('#header_user_box').popover('hide');
	    $('#header_user_box, #header_user_box2').removeClass('active');
        $('#nav-li-last').removeClass('activep');
	  }
	});
	/*function clearScroll(){
	  setTimeout(function(){
	    $('html').getNiceScroll().hide();
		$('html').getNiceScroll().show();
	  }, 100);
	}*/
	function doGetc(el,term){
		var citypass = $(el).data('city');
		if(!term)
			term = $("#search_in_popup").val();
        var datav = {'term':term,'limit':19},
          hpv = $('#csfr').attr('name'),
          hpt = $('#csfr').attr('value');
          datav[hpv] = hpt;
		$.ajax({
                type: 'post',
                 url: '/site/getcitiesbig/' + citypass,
                data: datav,
                success: function(data)
                {
                    var obj = $.parseJSON(data);
                    $('#select_city #dr_field_2').html('');
                    if(obj.status == 'OK' && obj.arWrapper['k']){
                       // $('#dr_li').css('padding','10px 20px 10px 20px');
                        var ara = []; var ukfirst = '';
                        for (i=0; i < obj.arWrapper['k'].length; i++) {
                           ukfirst = obj.arWrapper['v'][i].data_ukfirst;
                           if(obj.arWrapper['v'][i].data_star){
                           		$('#select_city #dr_field_2').append('<div class="key star"><a class="white9" href="'+ obj.arWrapper['v'][i].url +'">'+ obj.arWrapper['v'][i].title +'</a></div>');
                           } else {
	                           if($.inArray(ukfirst,ara) == -1){
	                                ara.push(ukfirst);
	                                $('#select_city #dr_field_2').append('<div class="key ukfirst">'+ ukfirst +'</div>');
	                           }
                           		$('#select_city #dr_field_2').append('<div class="key"><a class="white9" href="'+ obj.arWrapper['v'][i].url +'">'+ obj.arWrapper['v'][i].title +'</a></div>');
                           }
                        }
                        
						
                      //  runUkfirst();
                    } else {

                    }
                    var sh = $('#sitehost').val();
                    
                    $('#select_city #dr_field_2').append('<div class="showall"><a class="grayd" href="'+ sh +'">Показать все</a></div>');
                    $(".popover-content.popover-city").html($('#select_city').find('.content').html());
                    $(".popover-content.popover-city .search_in_popup").attr('value',$('#search_in_popup').attr('value'))
                    if($(".popover-content.popover-city .search_in_popup").attr('value')){
                    	
                    	var search_txt = $(".popover-content.popover-city .search_in_popup").val();
						$(".popover-content.popover-city .search_in_popup").focus().val("").val(search_txt);
                    } else {
                    	$(".popover-content.popover-city .search_in_popup").focus();
                    }
                    dr_megamenu_2 = true;
                    clearScroll();
					doAutoc(el);

                }
            })  
		
	}
	function doAutoc(el){
		 
		$('.popover .search_in_popup').autocomplete({
		    'minLength':'3',
		    source: function(request, response) {
		    	$("#search_in_popup").val(request.term);
		    	doGetc(el,request.term);
		    },
		    'showAnim':'fold',
		    'focus':function(event, ui) {
		           
		    }, 
		    'select':function(event, ui) {    
		      
		    },
		    'change':function(event, ui) {  

		    	    
			    	// $("#search_in_popup").val(request.term);
			    	// doGetc(request.term);
		    }
		}).keyup(function (e) {
				
		        if(e.which === 13) {
			    	$("#search_in_popup").val($(this).val());
			    	doGetc(el,$(this).val());
		        }  else if($(this).val() == '') {
		        	$("#search_in_popup").val($(this).val());
		        	doGetc(el,$(this).val());
		        }           
		});
	}

	if($('#main_select_city').length){
		doAutocMain();
	}
    function doAutocMain(){

	$('#main_select_city').autocomplete({
		    'minLength':'3',
		    source: function(request, response) {
		    	$('#search_in_popup').val(request.term);
		    	doGetcMain(request.term);
		    },
		    'showAnim':'fold',
		}).keyup(function (e) {
				
		        if(e.which === 13) {
			    	$('#search_in_popup').val($(this).val());
			    	doGetcMain($(this).val());
		        }  else if($(this).val() == '') {
		        	$('#search_in_popup').val($(this).val());
		        	doGetcMain($(this).val());
		        }           
		});

	}

	function doGetcMain(term){
		if(!term)
			term = $("#search_in_popup").val();
        var datav = {'term':term},
          hpv = $('#csfr').attr('name'),
          hpt = $('#csfr').attr('value');
          datav[hpv] = hpt;
		$.ajax({
                type: 'post',
                 url: '/site/getcitiesbig/',
                data: datav,
                success: function(data)
                {
                    var obj = $.parseJSON(data);
                    $('#dr_field_1').html('');
                    if(obj.status == 'OK' && obj.arWrapper['k']){
                        var ara = []; var ukfirst = '';
                        for (i=0; i < obj.arWrapper['k'].length; i++) {
                           ukfirst = obj.arWrapper['v'][i].data_ukfirst;
                           if(obj.arWrapper['v'][i].data_star){
                           		$('#dr_field_1').append('<div class="key star"><i class="md md-star c-green"></i><a class="white9" href="'+ obj.arWrapper['v'][i].url +'">'+ obj.arWrapper['v'][i].title +'</a></div>');
                           } else {
	                           if($.inArray(ukfirst,ara) == -1){
	                                ara.push(ukfirst);
	                                $('#dr_field_1').append('<div class="key "><span class="ukfirst">'+ ukfirst +'</span></div>');
	                           }
                           		$('#dr_field_1').append('<div class="key"><a class="white9" href="'+ obj.arWrapper['v'][i].url +'">'+ obj.arWrapper['v'][i].title +'</a></div>');
                           }
                        }
                        
						
                        
                    } 
                    runUkfirst();
                    var sh = $('#sitehost').val();
                   

                    clearScroll();
					doAutocMain();

                }
            })  
		
	}
	
	$('.header-inner.sub').find('li.mymenu:visible').addClass('sep');
	$('.header-inner.sub').find('li.mymenu.sep:visible:last').removeClass('sep');
	$('.header-inner.sub > li.mymenu').hover(function(){
		if(!$('#menu-trigger').is(':visible')) {
		$(this).prev('li.mymenu:visible').toggleClass('sep');
		}

	});
	$('.tohoveriks').hover(function(){
		$('#top-search-close').parent().toggleClass('sep');
	});


    $(window).on("debouncedresize", function(){
    	
    	
      
    	if($('.shmoster').length){
        	scaleVideoContainer();
	        scaleBannerVideoSize('.video-container .poster img',true);
	        scaleBannerVideoSize('.video-container .filter',true);
	        scaleBannerVideoSize('.video-container video',true);
    	}
	     if(dr_megamenu == true){
	         runUkfirst();
	     }
    });
    // for main page
    if($('.shmoster').length){
			scaleVideoContainer();
		    initBannerVideoSize('.video-container .poster img');
		    initBannerVideoSize('.video-container .filter');
		    initBannerVideoSize('.video-container video');
	}
	if($('#main_page_cat .key').length){
		runUkfirst();
	}
});
(function( $ ){
    $.fn.isValid = function() {
        return document.getElementById(this[0].id).checkValidity();
    };
})( jQuery );
/*
function scaleVideoContainer() {
	var windowWidth = $(window).width();
	var height = windowWidth / 1.7777;
    var unitHeight = parseInt(height) + 'px';
    var unitHeight = '550px';
    $('.homepage-hero-module').css('height',unitHeight);
}

function initBannerVideoSize(element){
	var windowWidth = $(window).width();
	var height = windowWidth / 1.7777;
    var unitHeight = parseInt(height) + 'px';
    var unitHeight = '550px';
    $(element).each(function(){
        $(this).data('height', unitHeight);
        $(this).data('width', '100%');
    });

}
*/
var firstPosTop;  
    var position_ukfirst;
    var counterg;

function gorLoaded() {
       // function to invoke for loaded images
       // decrement the counter
       counterg--; 
       if( counterg === 0 ) {

        $(".ukfirst").each(function(i,v) {
          
          var thisPos,nextPos,cnt=0;
           thisPos = Math.round($(v).parent(".key").position().top);
           nextPos = Math.round($(v).parent(".key").next().position().top);

          
           while(thisPos > nextPos){
          	if(cnt>8)
          		break;
          	$(v).removeClass("marazoom");
            $(v).parent().before("<div class=\'key springs\'><i></i></div>");
        	
        	thisPos = Math.round($(v).parent(".key").position().top);
          	nextPos = Math.round($(v).parent(".key").next().position().top);
          	cnt++;
          	console.log(cnt)
          }

          if(thisPos == firstPosTop){
            $(v).removeClass("marazoom");
          }
          if($(v).data('num')==0){
          	$(v).parent().prev('.springs').remove();
          }

        });

        
       }
    }
function runUkfirst(){

    	if(!$("#main_page_cat .key").length)
    		return;
 //   $(".ukfirst").not(":first").addClass("marazoom"); 
    $(".ukfirst").each(function(i,v){
    	$(this).addClass("marazoom");
    	$(this).attr('data-num',i);
    });
   // $(".ukfirst").addClass("marazoom"); 
    $(".key.springs").remove();

    
    firstPosTop = Math.round($("#main_page_cat .key").first().position().top);  
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
function scaleVideoContainer() {

    var height = $(window).height() + 5;
    var unitHeight = parseInt(height) + 'px';
    var unitHeight = '550px';
    $('.homepage-hero-module').css('height',unitHeight);

}

function initBannerVideoSize(element){

    $(element).each(function(){
        $(this).data('height', $(this).height());
        $(this).data('width', $(this).width());
    });

    scaleBannerVideoSize(element,false);

}

function scaleBannerVideoSize(element,resize){

    var windowWidth = $(window).width(),
    windowHeight = $(window).height() + 5,
    videoWidth,
    videoHeight;
    windowHeight = 550;
    // console.log(windowHeight);

    $(element).each(function(){
        var videoAspectRatio = $(this).data('height')/$(this).data('width');

        $(this).width(windowWidth);

        if(windowWidth < 1000){
            videoHeight = windowHeight;
            videoWidth = videoHeight / videoAspectRatio;
            $(this).css({'margin-top' : 0, 'margin-left' : -(videoWidth - windowWidth) / 2 + 'px'});

            $(this).width(videoWidth).height(videoHeight);
        } else {

        	 if(resize){
        		 $(this).css({'margin-top' : 0, 'margin-left' :  'auto'});
        		 $(this).width($(window).width());
        		 if($(this).hasClass('fillWidth')){
        		 	$(this).height($(window).width()/1.7777);
        		 }
        	 }
        }
        

        $('.homepage-hero-module .video-container video').addClass('fadeIn animated');

    });
}
function toVoteComment(id, vote){
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
        url: '/site/tovotecomment',

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
