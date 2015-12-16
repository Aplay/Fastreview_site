<div class="error-page">
<div class="error-code">403</div>
<div class="error-text">

	<?php 

	if(isset($error['message'])){
		echo MHelper::String()->toUpper($error['message']);
	} else {
		echo 'YOU HAVEN\'T ACCESS TO THIS PAGE';
	}
	?>
</div> <!-- / .error-text -->
</div>

