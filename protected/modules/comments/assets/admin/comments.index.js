
/**
 * Update selected comments status
 * @param status_id
 */
function setCommentsStatus(status_id, el)
{
    var hn = $('#csfr').attr('name');
	var hv = $('#csfr').attr('value');

	var datav = {ids: $.fn.yiiGridView.getSelection('commentsListGrid'),
            status:status_id};
	datav[hn] = hv;
	// datav[hn] = $(el).attr('data-token');
    $.ajax('/admin/comments/updateStatus', {
        type:"post",
        data:datav,
        success: function(data){
            $.fn.yiiGridView.update('commentsListGrid');
            $.jGrowl(data);
        },
        error:function(XHR, textStatus, errorThrown){
            var err='';
            switch(textStatus) {
                case 'timeout':
                    err='The request timed out!';
                    break;
                case 'parsererror':
                    err='Parser error!';
                    break;
                case 'error':
                    if(XHR.status && !/^\s*$/.test(XHR.status))
                        err='Error ' + XHR.status;
                    else
                        err='Error';
                    if(XHR.responseText && !/^\s*$/.test(XHR.responseText))
                        err=err + ': ' + XHR.responseText;
                    break;
            }
            alert(err);
        }
    });
    return false;
}