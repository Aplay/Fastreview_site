$(function(){
$('#InviteByEmailForm').validate({ 

    focusInvalid: true, 
   // errorLabelContainer: "#errorInvite",
  //  errorPlacement: function () {} ,
    rules: {
            'inviteEmail': {
              required: true,
              email: true
            },
    },
    messages : {
            inviteEmail : $('#InviteByEmail').data('error-text'),
    },
    submitHandler: function(form) {
   //     console.log('done!');
    form.submit();
  // return false;
  }
});
  $('.add-tooltip').tooltip();
})

function a(a, b) {
        var c, d = [];
        d.push(a.type.toUpperCase() + ' url = "' + a.url + '"');
        for (var e in a.data) {
            if (a.data[e] && "object" == typeof a.data[e]) {
                c = [];
                for (var f in a.data[e]) c.push(f + ': "' + a.data[e][f] + '"');
                c = "{ " + c.join(", ") + " }"
            } else c = '"' + a.data[e] + '"';
            d.push(e + " = " + c)
        }
        d.push("RESPONSE: status = " + b.status), b.responseText && ($.isArray(b.responseText) ? (d.push("["), $.each(b.responseText, function(a, b) {
            d.push("{value: " + b.value + ', text: "' + b.text + '"}')
        }), d.push("]")) : d.push($.trim(b.responseText))), d.push("--------------------------------------\n")
}