<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
?>

<div class="section text-center" style="padding-top:10px;">	
	
	<!-- 
	<div class="features" style="padding-bottom:45px;">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<h2 class="title pull-left">Empower Your Audience to <span id="js-rotating" class="jsrotate">Execute, Get S**t Done, Succeed</span></h2>
				<h5 class="description">While creating an entirely new revenue stream with your existing content. We give you tools to launch and orchestrate an online challenge for your followers to infuse clarity, accountability and motivation for those ready to transfrom.</h5>
			</div>
		</div>
	</div>
	-->
	
	<div class="features" style="padding-bottom:45px;">
		<div class="row">
			<div class="col-md-12">
				<h2 class="title" style="text-align:center;">Online Challenge Features</h2>
			</div>
		</div>
	</div>

	<!-- Features -->     
	<div class="features">
				
		<div class="row">
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary"><i class="fa fa-comments" aria-hidden="true"></i></div>
					<h4 class="info-title">Instant Messenging</h4>
					<p>Have a direct line of contant via <a href="https://messenger.fb.com/">Facebook Messenger</a> with each participant.</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary"><i class="fa fa-trophy" aria-hidden="true"></i></div>
					<h4 class="info-title">Leaderboard</h4>
					<p>It's how we gamify your challenge to increase engagement using our natural sense of competition.</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary"><i class="fa fa-tint" aria-hidden="true"></i></div>
					<h4 class="info-title">Drip Content</h4>
					<p>Have your content automatically delivered in in small but targetted portions for each sprint.</p>
				</div>
			</div>
		</div>
	</div>
	
	<div class="features" style="margin-top:60px;">
		<div class="row">
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary"><i class="fa fa-bell-o" aria-hidden="true"></i></div>
					<h4 class="info-title">Auto Reminders</h4>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary"><i class="fa fa-check-square-o" aria-hidden="true"></i></div>
					<h4 class="info-title">Onboarding Surveys</h4>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary"><i class="fa fa-usd" aria-hidden="true"></i></div>
					<h4 class="info-title">Payment Processing</h4>
				</div>
			</div>
		</div>
	</div>
	
	
	
	
	
	<div class="features" style="margin-top:60px;">
		<div class="row">
			<div class="col-md-12">
				<h2 class="title">Testimonials</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="info">
					<p>Thanks so much for lighting a fire under my butt with this challenge. I've been talking about doing this course for about five years. I'm so excited to finally be doing it!!!<br /><b style="color:#000;">Linda Salazar</b></p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<p>Just wanted to thank you, the truth is that I've been "cooking" this course for about 2 years now, and I'm taking up your challenge as the accountability push to make it happen.<br /><b style="color:#000;">Pazit Perez</b></p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<p>Week #1's Assignment forced me to focus on this OUTLINE... an obstacle that I've failed to overcome for over two years... and YOU helped me complete it in a week!<br /><b style="color:#000;">Wayne Pollard</b></p>
				</div>
			</div>
		</div>
	</div>
	
	
	
	<!--  
	<div class="features-4">
			<div class="row">
				<div class="col-md-3 col-md-offset-1">
		           	<div class="info info-horizontal">
						<div class="description">
							<h4 class="info-title">For Developers</h4>
							<p>The moment you use Material Kit, you know you’ve never felt anything like it. With a single use, this powerfull UI Kit lets you do more than ever before. </p>
						</div>
		        	</div>

					<div class="info info-horizontal">
						<div class="description">
							<h4 class="info-title">For Designers</h4>
							<p>Divide details about your product or agency work into parts. Write a few lines about each one. A paragraph describing a feature will be enough.</p>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="phone-container">
						<img src="/img/iphone2.png">
					</div>
				</div>

				<div class="col-md-3">
					<div class="info info-horizontal">
						<div class="description">
							<h4 class="info-title">Bootstrap Grid</h4>
							<p>Divide details about your product or agency work into parts. Write a few lines about each one. A paragraph describing a feature will be enough.</p>
						</div>
					</div>

					<div class="info info-horizontal">
						<div class="description">
							<h4 class="info-title">Pages Included</h4>
							<p>Divide details about your product or agency work into parts. Write a few lines about each one. A paragraph describing a feature will be enough.</p>
						</div>
					</div>
				</div>
			</div>
	    </div>
	-->
	
	
	
	
				
	
	
				
</div>
						
<!-- Get Started -->
<div class="section section-contacts" style="padding-top:20px;">
	<div class="row">
		<div class="col-md-8 col-md-offset-2" style="text-align:center;">
					<?php
    				if(isset($udata['id'])){
    					echo '<a href="/dashboard" class="btn btn-danger btn-raised btn-lg bg-glow">MY DASHBOARD <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>';
    				} else {
    					echo '<a href="https://mench.typeform.com/to/nh4s2u" class="btn btn-danger btn-raised btn-lg bg-glow glow">Get Early Access <i class="fa fa-sign-in"></i><div class="ripple-container"></div></a>';
    					echo '<p class="sub-button">Or <a href="#" data-toggle="modal" data-target="#loginModal">Login as Partner</a></p>';
    				}
    				?>
    				
            
		</div>
	</div>
</div>
	
	

            
<script>
$(document).ready(function() {
	$('.jsrotate').css('display','inline-block');
	$("#js-rotating").Morphext({
	    // The [in] animation type. Refer to Animate.css for a list of available animations.
	    animation: "bounceIn",
	    // An array of phrases to rotate are created based on this separator. Change it if you wish to separate the phrases differently (e.g. So Simple | Very Doge | Much Wow | Such Cool).
	    separator: ",",
	    // The delay between the changing of each phrase in milliseconds.
	    speed: 2000,
	    complete: function () {
	        // Called after the entrance animation is executed.
	    }
	});
});
</script>