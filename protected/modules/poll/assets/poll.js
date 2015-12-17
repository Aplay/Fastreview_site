sendPoll = function(org, type){  
                
                $('.PollChoice_label_em_').hide();   
                var datav = {'ajax':'poll-form', 'PollChoice[org_id]':org, 'PollChoice[type]':type, 'PollChoice[label]':$('#PollChoice_label_'+ org +'_'+ type).val()},
              	hpv = $('#csfr').attr('name'),
              	hpt = $('#csfr').attr('value');
              	datav[hpv] = hpt;
                $.ajax({  
                    type: 'POST',  
                    url: '/poll/poll/addpoll/',  
                    data: datav, 
                    
                    success: function(html){  
                        if (html.indexOf('{')==0) {
                                var obj = jQuery.parseJSON(html); 
                                $('#PollChoice_label_em_'+ org +'_'+ type).show().html(obj.PollChoice_label);
                         } else {
 
                              $('.comment_add').remove();
                              
                              $('.poll_box_type_'+ type).append(html);
                              
                              $('#PollChoice_label_'+ org +'_'+ type).val('');
                              // plusScripts();

                         }
                    }  
                });  
                return false; 
            };
toVotePoll = function(id, vote){
    if(!$('#vote'+id).hasClass('active'))
        return;
    var hn = $('#csfr').attr('name');
    var hv = $('#csfr').attr('value');
    var datav = {'id':id,'vote':vote};
    datav[hn] = hv;
    $.ajax({
        type: 'POST',
        dataType: 'json',
        data:datav,
        url: '/poll/poll/tovote',

        success: function(data) {
            if(data.flag==true){
                $('#vote'+id).removeClass('active');
                
                if(vote == 1){
                    $('#vote'+id+' .user_pro').addClass('user_mine');
                } else {
                    $('#vote'+id+' .user_contra').addClass('user_mine');
                }
                var diff = data.yes - data.no;
                if(diff>0){
                	$('#vote'+id+' .user_n').removeClass('user_num_r').addClass('user_num_g').html(diff);
                } else if(diff<-9){

                    $('#vote'+id+' .user_n').removeClass('user_num_g').addClass('user_num_r').html(Math.abs(diff));
                	$('#poll_block_'+id).fadeOut();
                } else if(diff==0) {
                	$('#vote'+id+' .user_n').removeClass('user_num_g, user_num_r').html('');
                } else {
                	$('#vote'+id+' .user_n').removeClass('user_num_g').addClass('user_num_r').html(Math.abs(diff));
                }
                $('#mean_'+id).text(data.weight_text);
                console.log(data)
                var items = $('.poll_item_'+data.type).not('#poll_block_'+data.id);
                var item = $('#poll_block_'+data.id);
                $(item).attr('data-weight',data.weight);
               /* $.each(items, function(i,v){

	            	if($(v).data('weight') > data.weight){
	            		$(item).insertAfter($(v));
	            	}
                	
                })*/
            
            }
        }
    });
}