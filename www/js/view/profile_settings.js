$(document).ready(function(){

$('.submitField').on('focusin', function(){
	   // $('.form-group').removeClass('has-success');
		//$('.form-group').find('i.fa').remove();

		var send = $(this).next('.dataSend'), pad;
		send.css({'opacity':0, 'display': 'block'});
		send.animate({'opacity':1});
		pad = send.width() + 24;
		$(this).css('padding-right', pad);
	}).on('focusout', function(){
		//$('.form-group').removeClass('has-success');
		//$('.form-group').find('i.fa').remove();
		var send = $(this).next('.dataSend');
		send.animate({'opacity': 0}, function(){
			send.css({'display': 'none'});
		});
	});


$.validator.addMethod("alfabeta", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9_]+$/.test(value);
}, 'Symbols (A-z0-9_) only.');

$.validator.addMethod("validateUserEmail", function(value, element)
{
    var form = $('#form-account-email'),
    	formData = form.serialize();
 
    $.ajax(
    {
        type: "POST",
        url: form.attr('action'),
        data: formData,
        success: function(data)
        {
            	if (data.indexOf('{')==0) {
		     		var obj = jQuery.parseJSON(data);
			        $.growl.error({ message: obj.User_email});
			        
			        return false;
			     }
			     else {
			     	 $.growl.notice({ message: 'Saved successfully' });
			     	 return true;
			     }
        },
        error: function(xhr, textStatus, errorThrown)
        {
            $.growl.error({ message: textStatus +'  '+errorThrown});
            return false;
        }
    });
 
}, '');
$.validator.setDefaults({
      /*  highlight: function (element, errorClass, validClass) {
            if (element.type === "radio") {
                this.findByName(element.name).addClass(errorClass).removeClass(validClass);
            } else {
            	$(element).next('.input_btn').show();
                $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
                $(element).closest('.form-group').find('i.fa').remove();
               // $(element).closest('.form-group').append('<i class="fa fa-exclamation fa-lg form-control-feedback"></i>')
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            if (element.type === "radio") {
                this.findByName(element.name).removeClass(errorClass).addClass(validClass);
            } else {

            	
                $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-feedback has-done');
                $(element).closest('.form-group').addClass('has-success');
			    $(element).closest('.form-group').find('i.fa').remove();
                $(element).closest('.form-group').append('<i class="fa fa-check fa-lg form-control-feedback"></i>');
                
            }
        }, */
        /*onkeyup : function(element){
        	
				$(element).closest('.form-group').removeClass('has-success');
			    $(element).closest('.form-group').find('i.fa').remove();
			    $(element).next('.input_btn').show();
			
			return false;


		},*/

        	

        
        

})
$('#form-fullname').validate({
	/*onfocusout: function(e) {
      this.element(e);
    },*/
    onfocusout: false,
    onkeyup: false,
	focusInvalid: true
});

$('#form-about').validate({
	/*onfocusout: function(e) {
      this.element(e);
    },*/
    onfocusout: false,
    onkeyup: false,
    focusInvalid: true
 });

$('#form-social').validate({
	/*onfocusout: function(e) {
      this.element(e);
    },*/
    onfocusout: false,
    onkeyup: false,
    focusInvalid: true
});

$('#form-account-username').validate({
	/*onfocusout: function(e) {
      this.element(e);
    },*/
    onfocusout: false,
    onkeyup: false,
    focusInvalid: true,
    
 
});

$('#form-account-email').validate({
	/*onfocusout: function(e) {
      this.element(e);
    },*/
    onfocusout: false,
    onkeyup: false, 
    focusInvalid: true,
    
});

$('#form-changepassword').validate({
	/*onfocusout: function(e) {
      this.element(e);
    },*/
    onfocusout: false,
    onkeyup: false, 
    focusInvalid: true
});

// Validate name

$('#User_fullname').rules('add', {
    required: true,
    minlength: 3,
    maxlength:255
});

$('#User_soc_envelope').rules('add', {
    maxlength:255,
    email: true
});
$('#User_username').rules('add', {
	required: true,
    minlength: 3,
    maxlength:20,
    'alfabeta':true,
    messages: {
	    	remote: "Already exists"
	  },
	
    remote :
	  {
	      url: $('#form-account-username').attr('action'),
	      type: "post",
	      data:
	      {
	      	 /* HiddenPropertyValue : function()
	          {
	              return $('#form-account-username :input[name="HiddenPropertyValue"]').val();
	          }, */
	          $('#csfr').attr('name') :function()
	          {
	              var hn = $('#csfr').attr('name');
	              return $('#form-account-username :input[name="'+ hn +'"]').val();
	          },
	          ajax : function()
	          {
	              return $('#form-account-username :input[name="ajax"]').val();
	          },
	          username: function()
	          {
	              return $('#form-account-username :input[name="username"]').val();
	          }
	      },
	      beforeSend: function () {
                  $(".jquery-validate-error[for='User_username']").hide();
          },
	      complete: function (data) {

	        if (data.responseText != "false") {
	            $(".jquery-validate-error[for='User_username']").hide();

	            /*$('#User_username').next('.input_btn').hide();
	            $('#User_username').closest('.form-group').addClass('has-success has-feedback');
			    $('#User_username').closest('.form-group').find('i.fa').remove();
                $('#User_username').closest('.form-group').append('<i class="fa fa-check-circle form-control-feedback"></i>');*/
	            $.growl.notice({ message: 'Saved successfully' });
	            
	        } else {
	          //  $.growl.error({ message: 'Already exists'});
	            return false;
	        }
	    }
	}
});
$('#User_email').rules('add', {
	required: true,
    email: true,
   // 'validateUserEmail':true,
    messages: {
	    	remote: "Already exists"
	  },
    remote :
	  {
	      url: $('#form-account-email').attr('action'),
	      type: "post",
	      data:
	      {
	      	  HiddenPropertyValue : function()
	          {
	              return $('#form-account-email :input[name="HiddenPropertyValue"]').val();
	          },
	          ajax : function()
	          {
	              return $('#form-account-email :input[name="ajax"]').val();
	          },
	          email: function()
	          {
	              return $('#form-account-email :input[name="email"]').val();
	          }
	      },
	      beforeSend: function () {
                  $(".jquery-validate-error[for='User_email']").hide();
          },
	      complete: function (data) {

	        if (data.responseText != "false") {
	            $(".jquery-validate-error[for='User_email']").hide();

	          /*  $('#User_email').next('.input_btn').hide();
	            $('#User_email').closest('.form-group').addClass('has-success has-feedback');
			    $('#User_email').closest('.form-group').find('i.fa').remove();
                $('#User_email').closest('.form-group').append('<i class="fa fa-check-circle form-control-feedback"></i>');
                */

	            $.growl.notice({ message: 'Saved successfully' });
	            
	        } else {
	          //  $.growl.error({ message: 'Already exists'});
	            return false;
	        }
	    }
	    //  async: false,
        /*  success: function (data) { 
          		if (data.indexOf('{')==0) {

		     		var obj = jQuery.parseJSON(data);
			        $.growl.error({ message: obj.User_email});
			        
			        return false;
			     }
			     else {
			     	 $.growl.notice({ message: 'Saved successfully' });
			     	 return true;
			     }
            }*/
	  	}
		
});

// Validate password
$('#UserChangePassword_oldPassword').rules('add', {
    required: true,
    minlength: 5
});
$('#UserChangePassword_password').rules('add', {
    required: true,
    minlength: 5
});
$('#UserChangePassword_verifyPassword').rules('add', {
    required: true,
    minlength: 5,
    equalTo: "#UserChangePassword_password"
});

$("input.submitField").next('.input_btn').click(function() {
		
	    $(this).prev('input').submit();

});

});