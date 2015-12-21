<div id="addshare">		
<noindex>
<span id="ya_share1"></span>
</noindex>
</div>
<?php 
$scriptAdd = "

$(document).ready(function(){
	
   // создаем блок
        $.getScript('http://yastatic.net/share/share.js', function () {
        new Ya.share({
        element: 'ya_share1',

        theme: 'counter',
            elementStyle: {
              'text': '',
                'type': 'button',
                'border': false,
                'quickServices': [ 'facebook', 'vkontakte', 'twitter']
            },

            link: '".$thisUrl."',
            title: '".addslashes($this->pageTitle)."',
            description: '".addslashes($this->pageDescription)."',
            ";
        if(isset($image) && !empty($image)){
            $scriptAdd .= "image: '".$image."'";
        }
$scriptAdd .= "
                    
        });
    })
})
";
Yii::app()->clientScript->registerScript("share", $scriptAdd, CClientScript::POS_END);