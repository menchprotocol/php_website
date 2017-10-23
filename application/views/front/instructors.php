<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
?>

<h1>Let's Do It</h1>
<h3 class="maxout">Empower your students to accomplish tangible results by taking action.</h3>
<br /><br />



<div class="section text-center">
	<!-- How? -->
	<div class="features">
		<div class="row">
			<div class="col-md-12">
				<h2 class="title">How It Works?</h2>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary mtweak"><i class="material-icons">filter_1</i></div>
					<h3 class="info-title">Create Bootcamp</h3>
					<p>What's something your students are struggling to achieve? Launch a website, podcast or Youtube channel? Create an app or write a book? With Mench you can help them make it happen once and for all.</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary mtweak"><i class="material-icons">filter_2</i></div>
					<h3 class="info-title">Invite Students</h3>
					<p>Set a launch date and announce your bootcamp. Make it open to anyone or create an application page to make sure participants meet a specific criteria. You set the rules and the cost. We take care of the technology.</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary mtweak"><i class="material-icons">filter_3</i></div>
					<h3 class="info-title">Navigate To Success</h3>
					<p>Ready, set, go! Set weekly milestones, completion prizes, and automated reminders to motivate participants to cross the finish line. Use our analytics to monitor their progress and identify who needs help the most.</p>
				</div>
			</div>
		</div>
	</div>
		
		
	
	<div class="features">
		<div class="row">
			<div class="col-md-12">
				<h2 class="title" style="margin-top:100px;">Why Mench?</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary mtweak"><i class="material-icons">blur_on</i></div>
					<h3 class="info-title">Expand Your Reach</h3>
					<p>People love challenges, especially when their friends join. You know what that means right? more participants, more reach, more business for you. Bootcamps are one of the most effective lead generation solutions available.</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary mtweak"><i class="material-icons">flash_on</i></div>
					<h3 class="info-title">Make a Real Impact</h3>
					<p>A bootcamp is all about getting your audience tangible results via execution. At Mench we provide you with all the tools to help your students take action and accomplish their goals once and for all. Only through action we can change the world!</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<div class="icon icon-primary mtweak"><i class="material-icons">monetization_on</i></div>
					<h3 class="info-title">Grow Your Business</h3>
					<p>A bootcamp allows you to add an lucrative source of income. People are willing to pay a premiun for content that helps them get actual results. At Mench you'll be able to command higher prices than for information only products.</p>
				</div>
			</div>
		</div>
	</div>
	
	
	
	
	<div class="features">
		<div class="row">
			<div class="col-md-12">
				<h2 class="title" style="margin-top:100px;">What Do Students Say?</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<div class="info">
					<p>I can't believe how much I've gotten done in your bootcamp. I thought for sure I'd run out of steam after 4 or 6 weeks, but you've actually created an environment that's giving me more and more energy every week you put forward a new challenge. Amazing. So much gratitude to you.</p>
					<h4>Donna Barker</h4>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<p>There are SO many things I've wanted to do for SO long that had me scared and overwhelmed. had no idea where to begin. Your weekly assignments; your reminders, and your OH SO incredible emails chock-full of help I'm just so very grateful! This 12-week bootcamp got me started on something that's been in my "to do" plan for at least 5 years now. SO BIG THANK YOU! Looking forward to doing more of your bootcamps!</p>
					<h4>Carolyn Martyn</h4>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info">
					<p>It seems silly but I've bought $2000+ courses on the same topic and I haven't done anything useful with them yet. Those online courses that I'm in are FILLED with modules and videos and it seems like such a daunting task to start. I think the simple style of your bootcamp is what's bringing me into action.</p>
					<h4>Tina Huynh</h4>
				</div>
			</div>
			
		</div>
	</div>
	
	
	
	<!-- Get Started -->
	<div class="section section-contacts" style="padding-top:20px;">
		<div class="row">
			<div class="col-md-8 col-md-offset-2" style="text-align:center;">
				<?php
	    		if(isset($udata['u_id'])){
	    			echo '<a href="/console" class="btn btn-danger btn-raised btn-lg bg-glow">'.$this->lang->line('m_name').' <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>';
	    		} else {
	    		    echo '<a href="'.typeform_url('nh4s2u').'" class="btn btn-danger btn-raised btn-lg bg-glow glow">'.$this->lang->line('signup').' <i class="fa fa-sign-in"></i><div class="ripple-container"></div></a>';
	    		    echo '<p class="sub-button">'.$this->lang->line('or').' <a href="https://support.mench.co/hc/en-us/articles/115002079731">Read More Testimonials</a> or <a href="/login">Login</a></p>';
	    		}
	    		?>
			</div>
		</div>
	</div>

	<?php /*
	<div class="section section-contacts">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" style="text-align:center;">
                <a href="/features" class="btn btn-danger btn-raised btn-lg bg-glow">See Features</a>
            </div>
        </div>
    </div>
    */ ?>
     
  </div>