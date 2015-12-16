<?php
	$cs = Yii::app()->clientScript;
	$themeUrl = '/themes/bootstrap_311/';
	$cs->registerScriptFile($themeUrl.'/js/modernizr.custom.js', CClientScript::POS_END);
    $cs->registerScriptFile($themeUrl.'/js/main.js', CClientScript::POS_END);
    $cs->registerScriptFile($themeUrl.'/js/masonry.pkgd.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($themeUrl.'/js/imagesloaded.js', CClientScript::POS_END);
    $cs->registerScriptFile($themeUrl.'/js/classie.js', CClientScript::POS_END);
    $cs->registerScriptFile($themeUrl.'/js/AnimOnScroll.js', CClientScript::POS_END);
?>
<!-- MAIN IMAGE SECTION -->
	<div id="headerwrap">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<h1><?php echo Yii::app()->name; ?></h1>
					<h2>We Create Cool Stuff</h2>
					<div class="spacer"></div>
					<i class="fa fa-angle-down"></i>
				</div>
			</div><!-- row -->
		</div><!-- /container -->
	</div><!-- /headerwrap -->

	<!-- WELCOME SECTION -->
    <div class="container">
      <div class="row mt">
      	<div class="col-lg-8">
	        <h1>We build websites & apps that people love!</h1>
	        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
      	</div>
      	<div class="col-lg-4">
      		<p class="pull-right"><br><button type="button" class="btn btn-green">Start Your Project Now</button></p>
      	</div>
      </div><!-- /row -->
    </div><!-- /.container -->
    
    <!-- PORTFOLIO SECTION -->
    <div id="portfolio">
    	<div class="container"
	    	<div class="row mt">
				<ul class="grid effect-2" id="grid">
					<li><a href="/singleproject"><img src="/images/portfolio/1.jpg"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/3.jpg"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/4.jpg"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/12.png"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/13.png"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/10.png"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/9.jpg"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/2.jpg"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/14.png"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/5.jpg"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/6.jpg"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/7.jpg"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/6.jpg"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/9.jpg"></a></li>
					<li><a href="/singleproject"><img src="/images/portfolio/11.png"></a></li>
				</ul>
	    	</div><!-- row -->
	    </div><!-- container -->
    </div><!-- portfolio -->


	<!-- SERVICES SECTION -->
	<div id="services">
		<div class="container">
			<div class="row mt">
				<div class="col-lg-1 centered">
					<i class="fa fa-certificate"></i>
				</div>
				<div class="col-lg-3">
					<h3>Quality Design</h3>
					<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
				</div>

				<div class="col-lg-1 centered">
					<i class="fa fa-question-circle"></i>
				</div>
				<div class="col-lg-3">
					<h3>Awesome Support</h3>
					<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
				</div>
			
			
				<div class="col-lg-1 centered">
					<i class="fa fa-globe"></i>
				</div>
				<div class="col-lg-3">
					<h3>Global Services</h3>
					<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
				</div>
			
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- services section -->
	
	
	<!-- BLOG POSTS -->
	<div class="container">
		<div class="row mt">
			<div class="col-lg-12">
				<h1>Recent Posts</h1>
			</div><!-- col-lg-12 -->
			<div class="col-lg-8">
				<p>Our latests thoughts about things that only matters to us.</p>
			</div><!-- col-lg-8-->
			<div class="col-lg-4 goright">
				<p><a href="#"><i class="fa fa-angle-right"></i> See All Posts</a></p>
			</div>
		</div><!-- row -->
		
		<div class="row mt">
			<div class="col-lg-4">
				<img class="img-responsive" src="/images/post01.jpg" alt="">
				<h3><a href="#">Designing for the reader experience</a></h3>
				<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
				<p><a href="#"><i class="fa fa-link"></i> Read More</a></p>
			</div>
			<div class="col-lg-4">
				<img class="img-responsive" src="/images/post02.jpg" alt="">
				<h3><a href="#">25 Examples of flat web & application design</a></h3>
				<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
				<p><a href="#"><i class="fa fa-link"></i> Read More</a></p>
			</div>
			<div class="col-lg-4">
				<img class="img-responsive" src="/images/post03.jpg" alt="">
				<h3><a href="#">We are an award winning design agency</a></h3>
				<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
				<p><a href="#"><i class="fa fa-link"></i> Read More</a></p>
			</div>		
		</div><!-- row -->
	</div><!-- container -->
	
	
	<!-- CLIENTS LOGOS -->
	<div id="lg">
		<div class="container">
			<div class="row centered">
				<div class="col-lg-2 col-lg-offset-1">
					<img src="/images/clients/c01.gif" alt="">
				</div>
				<div class="col-lg-2">
					<img src="/images/clients/c02.gif" alt="">
				</div>
				<div class="col-lg-2">
					<img src="/images/clients/c03.gif" alt="">
				</div>
				<div class="col-lg-2">
					<img src="/images/clients/c04.gif" alt="">
				</div>
				<div class="col-lg-2">
					<img src="/images/clients/c05.gif" alt="">
				</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- dg -->
	
	
	<!-- CALL TO ACTION -->
	<div id="call">
		<div class="container">
			<div class="row">
				<h3>THIS IS A CALL TO ACTION AREA</h3>
				<div class="col-lg-8 col-lg-offset-2">
					<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.</p>
					<p><button type="button" class="btn btn-green btn-lg">Call To Action Button</button></p>
				</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- Call to action -->
	
	<div class="container">
		<div class="row mt">
			<div class="col-lg-12">
				<h1>Stay Connected</h1>
				<p>Join us on our social networks for all the latest updates, product/service announcements and more.</p>
				<br>
			</div><!-- col-lg-12 -->
		</div><!-- row -->
	</div><!-- container -->
	
	

	<section id="contact">
	<div id="sf">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 dg">
					<h4 class="ml">FACEBOOK</h4>
					<p class="centered"><a href="#"><i class="fa fa-facebook"></i></a></p>
					<p class="ml">> Become A Friend</p>
				</div>
				<div class="col-lg-4 lg">
					<h4 class="ml">TWITTER</h4>
					<p class="centered"><a href="#"><i class="fa fa-twitter"></i></a></p>
					<p class="ml">> Follow Us</p>
				</div>
				<div class="col-lg-4 dg">
					<h4 class="ml">GOOGLE +</h4>
					<p class="centered"><a href="#"><i class="fa fa-google-plus"></i></a></p>
					<p class="ml">> Add Us To Your Circle</p>
				</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- Social Footer -->
	

	<div id="cf">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
		        	<div id="mapwrap">
						<iframe height="400" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
						src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAD0K4IQrPq-VeWff1YeAY8O2aR9QwxZ8w&q=580+Jefferson+Avenue,Redwood+City,CA,USA"></iframe>
					</div>	
				</div><!--col-lg-8-->
				<div class="col-lg-4">
					<h4>ADDRESS<br/>Redwood City - Head Office</h4>
					<br>
					<p>
						580 Jefferson Avenue, <br/>Redwood City, CA, USA
					</p>
					<p>
						P: +322 233-322-233<br/>
						F: +322 233-322-233<br/>
						E: <a href="mailto:#">hi@favoraim.com</a>
					</p>
					<p>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</p>
				</div><!--col-lg-4-->
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- Contact Footer -->
</section>
 
   
<?php
$script = '
		new AnimOnScroll( document.getElementById( "grid" ), {
			minDuration : 0.4,
			maxDuration : 0.7,
			viewportFactor : 0.2
		} );
';
$cs->registerScript("newAnimOnScroll", $script, CClientScript::POS_END);
?>