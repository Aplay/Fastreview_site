;(function(){
      $('.newNoteGet').on('click', function(e){
          e.preventDefault();
          var datav = {'param' : 'new'},
          hpv = $(this).data('hpv'),
          hpt = $(this).data('hpt');
          datav[hpt] = hpv;
          $.post(
            '/notes/new', datav, function() { window.location.href = '/notes' }
          );
      })
			// Menu settings
			$('#menuToggle, .menu-close').on('click', function(){
				$('#menuToggle').toggleClass('active');
				$('body').toggleClass('body-push-toleft');
				$('#theMenu').toggleClass('menu-open');
			});

	$('#menu-content-demo .close').click(function () {
		console.log('fff');
    var $p = $(this).parents('.menu-content');
    $p.addClass('fadeOut');
    setTimeout(function () {
      $p.css({ height: $p.outerHeight(), overflow: 'hidden' }).animate({'padding-top': 0, height: $('#main-navbar').outerHeight()}, 500, function () {
        $p.remove();
      });
    }, 300);
    return false;
  });

ReadNotifications = function(){
 $('#dropdownNotifier').on('show.bs.dropdown', function () {
      if($('#dropdownNotifier').hasClass('bang')){
          $('#main-navbar-notifications').slimScroll({ height: 250 });
          $("#main-navbar-notifications").html('').addClass('list-view-loading');
          var datav = {'notif_count' : $('#notif_calc').text()},
          hpv = $('#csfr').attr('value'),
          hpt = $('#csfr').attr('name');
          datav[hpt] = hpv;
          $.ajax({
                type:'POST',
                data: datav,
                url:'/profile/notification',
                success:function(data) {
                    $("#main-navbar-notifications").removeClass('list-view-loading').html(data);
                    $('#main-navbar-notifications').slimScroll({ height: 250 });
                },
                error: function (xhr, status) {  
                    alert('Unknown error ' + status); 
                } 
            });
          $(this).removeClass('bang');
    }
  }).on('hide.bs.dropdown', function(){
      $("#main-navbar-notifications .notseen").remove();
  })
}
$('.ui-bootbox-confirm').on('click', function (e) {
        var link = $(this).attr("href"); // "get" the intended link in a var
        var confirmtext = $(this).data("confirmtext");
        var message
        if(confirmtext.length>0){
          message = confirmtext;
        } else {
          message = "Are you sure?";
        }
        e.preventDefault(); 
        bootbox.confirm({
            message: message,
            callback: function(result) {
                if (result) {
                    document.location.href = link;  // if result, "set" the document location       
                }  
            },
            className: "bootbox-sm"
        });
    });

  ReadNotifications();

})(jQuery)