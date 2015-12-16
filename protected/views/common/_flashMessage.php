<?php if(Yii::app()->user->hasFlash('success')){ 

	?>
	<div class="alert alert-success">
	     <button type="button" class="close" data-dismiss="alert">&times;</button>
	    <?php
		$flash = Yii::app()->user->getFlash('success');

		if(is_array($flash)){

			foreach($flash as $flashes){
				echo $flashes.'<br>';
			}
		} else {
		 echo  $flash; 
		} 
		?>
		</div>
		<?php
	} 
?>

<?php if(Yii::app()->user->hasFlash('error')) { ?>
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php
        $flash = Yii::app()->user->getFlash('error');
        if(is_array($flash)){
			foreach($flash as $flashes){
				echo $flashes.'<br>';
			}
		} else {
          echo $flash; 
     	}
     	?>
    </div>
<?php } ?>