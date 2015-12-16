<!doctype html> 
<html>
<head>
	<meta charset="utf-8">
	<title>Mammarket</title>

	<?php /* ?><link rel="stylesheet" type="text/css" href="<?php echo $this->module->assetsUrl ?>/css/yui-grids/reset-fonts-grids.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $this->module->assetsUrl ?>/css/base.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $this->module->assetsUrl ?>/css/forms.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $this->module->assetsUrl ?>/css/theme.css"><?php */ ?>
	<style type="text/css">
            #loginform {
                width:300px; margin:auto;margin-top:140px;
                border:1px solid gray; padding:10px;
            }
            #loginform label{
               display:block;
                width:120px;
                float:left;
            }
            #loginform .row {
                margin-bottom: 10px;
            }
	</style>
</head>
<body>
	
				
					
					<div id="loginform">
						
						<?php
							echo $content;
						?>
					</div>
		
</body>
</html>
