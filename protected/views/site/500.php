<div class="error-page">
<div class="error-code">500</div>

<div class="error-text">

	<?php 

	if(isset($error['message'])){
		echo MHelper::String()->toUpper($error['message']);
	} else {
		echo 'SOMETHING IS NOT QUITE RIGHT';
	}
	?>
	
	<br>
	<span class="solve">We hope to solve it shortly</span>
</div> <!-- / .error-text -->
</div>