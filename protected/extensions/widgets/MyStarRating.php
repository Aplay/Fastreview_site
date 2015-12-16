<?php
Yii::import('CStarRating');


class MyStarRating extends CStarRating
{


	/**
	 * Registers the necessary javascript and css scripts.
	 * @param string $id the ID of the container
	 */
	public function registerClientScript($id)
	{
		$jsOptions=$this->getClientOptions();
		$jsOptions=empty($jsOptions) ? '' : CJavaScript::encode($jsOptions);
		$js="jQuery('#{$id} > input').rating({$jsOptions});";
		$js.="
		$('#{$id} .star-rating-control .star-rating').each(function( index ) {
	  	 	if($(this).hasClass('star-rating-on')){
	  	 		$(this).prevAll('.star-rating-on').css('color', $(this).css('color'));
	  	 	}
	  	 	/*if($(this).hasClass('star-rating-live')){
	  	 		$(this).on('mouseenter', function(){
	  	 			console.log(this)
	  	 			$(this).prevAll('.star-rating-on').css('color', $(this).css('color'));
	  	 		});
	  	 	}*/
	 	});

		";
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('rating');
		$cs->registerScript('Yii.CStarRating#'.$id,$js);
		if($this->cssFile!==false)
			self::registerCssFile($this->cssFile);
	}

}