<script>
var current_section = 1; //The index for the wizard

function move_ui(adjustment){

	//Any pre-check with submitted data?
	//Let's check the value of the current posstible ID for input validation checking:
	if(adjustment>0 && typeof $('.wizard-box').eq((current_section-1)).attr( "id" ) !== 'undefined' && $('.wizard-box').eq((current_section-1)).attr( "id" ).length){
		var the_id = $('.wizard-box').eq((current_section-1)).attr( "id" );
		if(the_id=='wz_objective' && $('#'+the_id+' input').val().length<2){
			alert('Enter something more longer than 2 characters');
			$('#'+the_id+' input').focus();
			return false;
		} else if(the_id=='wz_sprint_unit' && typeof $('#'+the_id+' input[name=b_sprint_unit]:checked').val() == 'undefined'){

			alert('Select 1 of the options to continue');
			return false;
			
		}
	}

	
    
    
    
	//Variables:
	var total_steps = $('.wizard-box').length;
	if(adjustment<0 && current_section==1){
		return false;
	} else if(adjustment>0 && current_section==total_steps){
		return false;
	}
	
	//We're all good, lets continue:
	current_section = current_section+adjustment;
	var progress = Math.round((current_section/total_steps*100));

	//UI Adjustment
	$('.wizard-box').hide();
	$('.wizard-box').eq((current_section-1)).fadeIn(function(){
		  $( this ).find( "input" ).focus();
		  $( this ).find( ".ql-editor" ).focus();
	});

	//Previous Button adjustments:
	if(current_section==1){
		$('#btn_prev').hide();
	} else {
		$('#btn_prev').show();
	}
	
	//Update progress:
	$('.progress-bar').attr('aria-valuenow',progress).css('width',progress+'%');
	$('#step_progress').html(progress+'% Done');

	
	//Submit data only if last item:
	if(current_section==total_steps){

		//Hide both buttons:
		$('#btn_next, #btn_prev').hide();
		
		//Send for processing:
		$.post("/process/bootcamp_create", {
			c_primary_objective:$('#c_primary_objective').val()
		}, function(data) {
			//Append data to view:
			$( "#new_bootcam_result" ).html(data).hide().fadeIn();
		});
	}
}

function skip_intro(){
	$('.platform-intro').remove();
	move_ui(0);
}

$(window).on('load',function(){
    //$('#newBootcampModal').modal('show');
});

$(document).ready(function() {
	$('#newBootcampModal').on('shown.bs.modal', function () {
		//Update progress bar:
		move_ui(0);
	});

	$('body').keyup(function(e){
        if((event.keyCode == 10 || event.keyCode == 13) && event.ctrlKey)
        {
        	move_ui(1);
        }
    });

	$(document).keydown(function(e) {
	    switch(e.which) {
	        case 37: // left
	        	move_ui(-1);
	        break;

	        case 39: // right
	        	move_ui(1);
	        break;
	        
	        default: return; // exit this handler for other keys
	    }
	    e.preventDefault(); // prevent the default action (scroll / move caret)
	});
});
</script>




<!-- Modal Core -->
<?php /*
<div class="modal fade" id="newBootcampModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">New Bootcamp</h3>
      </div>
      <div class="modal-body">
        	<div class="title"><h4><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Primary Goal</h4></div>
        	<ul>
    			<li>Describe your bootcamp's core offering in 70 characters or less.</li>
                <li>Define a goal that is both "Specific" and "Measurable".</li>
                <li>Sets the bar for our <a href="https://support.mench.co/hc/en-us/articles/115002080031"><u>Tuition Guarantee</u></a>.</li>
    			<li>Success is % of players who accomplish this when bootcamp ends.</li>
    		</ul>
			<div class="form-group label-floating is-empty">
			    <input type="text" id="c_primary_objective" maxlength="70" placeholder="Get hired as entry-level web developer" class="form-control border" />
			    <span class="material-input"></span>
			</div>
			<div id="new_bootcam_result"></div>
      </div>
      <div class="modal-footer">
        <a href="javascript:bootcamp_create()" type="button" class="btn btn-primary">Create</a>
      </div>
    </div>
  </div>
</div>
*/ ?>

<style>
.wizard-box * { line-height:110%; }
.wizard-box { font-size:1.2em; }
.wizard-box label { font-size:0.8em; }
.wizard-box p, .wizard-box ul { margin-bottom:20px; }
.wizard-box ul li { margin-bottom:10px; }
.wizard-box a { text-decoration:underline; }
.wizard-box h4 { margin:0 0 15px 0; padding:0; font-size:1.2em; }
.wizard-box .col-xs-6 { text-align:center; }
.aligned-list>li>i { width:36px; display:inline-block; text-align:center; }
.large-fa {font-size: 60px; margin-top:15px;}
.xlarge-fa {font-size: 68px; margin-top:15px;}
</style>

<?php 
$udata = $this->session->userdata('user');
?>
<div class="modal fade" id="newBootcampModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">New Bootcamp Wizard</h3>
      </div>
      <div class="modal-body" style="min-height:300px;">
      
        	<div class="wizard-box platform-intro">
        		<p>Welcome <?= $udata['u_fname'] ?>!</p>
        		<p>This wizard covers:</p>
        		<ul style="list-style:decimal;">
        			<li>Mench platform overview & best practices</li>
        			<li>Bootcamp creation wizard - <a href="javascript:skip_intro();">Skip to here</a></li>
        		</ul>
        		<p>And it takes about 12 minutes to complete.</p>
        		<p>Let's get started:</p>
            </div>
            
            <div class="wizard-box platform-intro">
        		<p>Mench is a game designed to dramatically increase the engagement of online courses.</p>
        		<p>How?</p>
        		<ul style="list-style:decimal;">
        			<li>By deliverying learning content using 2 powerful technology products used by over 2 Billion people.</li>
        			<li>And by "stacking" 7 key learning principles that when combined, create a more intuitive learning experience.</li>
        		</ul>
        		<p>Let's review our 2 technology products...</p>
            </div>
            
            
            <div class="wizard-box platform-intro">
        		<p>To maximize player engagement, we integrated Mench into the most engaging platform of all time: Facebook!</p>
        		<p>Specifically, we leverage 2 Facebook products:</p>
        		<ul style="list-style:decimal;">
        			<li>Facebook Messenger: for live chat, automated updates, player leaderboard and Action Plan. Mench is primarily played on Messenger. Players need NO apps, websites or login details!</li>
        			<li>Facebook Groups: An extra layer to enhance Messenger. Used for community development and peer-to-peer interactions.</li>
        		</ul>
        		<p>Messenger & Groups are available on mobile & desktop.</p>
        		<p>So, how do I manage all of this as an instructor?!</p>
            </div>
            
            
            <div class="wizard-box platform-intro">
        		<p>You manage Mench games using the Console (what you're logged into) which has 3 key components:</p>
        		<ul style="list-style:none; margin-left:-20px;">
        			<li><b><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b> Transform online courses into into a step-by-step execution guide delivered using Messenger.</li>
        			<li><b><i class="fa fa-calendar" aria-hidden="true"></i> Cohorts</b> group & enroll players on specific start dates so each cohort plays 1 game of Mench together.</li>
        			<li><b><i class="fa fa-users" aria-hidden="true"></i> Players</b> earn points for completing Action Plan items & are encouraged to work as a team.</li>
        		</ul>
        		<p>Let's review our 7 key learning principles that inspire our products...</p>
            </div>
            
            
            
            
            
            
            
            <div class="wizard-box platform-intro">
        		<p>There are no silver bullets when it comes to inspiring another human to learn. So here are the 7 key learning principles we use to maximize player engagement:</p>
        		<ul style="list-style:none;" class="aligned-list">
        			<li><i class="fa fa-gamepad" aria-hidden="true"></i> Gamification</li>
        			<li><i class="fa fa-handshake-o" aria-hidden="true"></i> Accountability</li>
        			<li><i class="fa fa-list-ol" aria-hidden="true"></i> Step-by-Step Navigation</li>
        			<li><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> Deadlines</li>
        			<li><i class="fa fa-line-chart" aria-hidden="true"></i> Incremental Difficulty Rise</li>
        			<li><i class="fa fa-usd" aria-hidden="true"></i> High Stakes Commitments</li>
        			<li><i class="material-icons">verified_user</i> Tuition Guarantee</li>
        		</ul>
        		<p>Now let's review each principle...</p>
            </div>
            
            
            <div class="wizard-box platform-intro">
        		<table>
            		<tr>
            			<td style="width:90px;" valign="top"><h4><i class="fa large-fa fa-gamepad" aria-hidden="true"></i></h4></td>
            			<td>
            				<h4>Principle #1: Gamification</h4>
            				<p>Humans LOVE playing games, so we turned learning into a game:</p>
            				<ul style="list-style:decimal;">
                    			<li>Each game's objective is to accomplish the <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> primary goal</b> (set by you) ON-TIME.<br />i.e. "<i class="fa fa-dot-circle-o" aria-hidden="true"></i> Build HTML5/JS Website in 2 Weeks"</li>
                    			<li>Players earn points for completing tasks. Points correlate to each task's estimated completion time set by the instructor.</li>
                    			<li>Each game has a leaderboard that sheds light on who is falling behind. Then live chats help remove bottle-necks to progress as a team.</li>
                    		</ul>
                    		<p>You can read more about <a href="https://support.mench.co/hc/en-us/articles/115002372531" target="_blank">The Mench Game <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a></p>
            			</td>
            		</tr>
        		</table>
            </div>
            
            
            <div class="wizard-box platform-intro">
        		<table>
            		<tr>
            			<td style="width:90px;" valign="top"><h4><i class="fa large-fa fa-handshake-o" aria-hidden="true"></i></h4></td>
            			<td>
            				<h4>Principle #2: Accountability</h4>
            				<p>Humans are WAY LESS likely to quit if held accountable by another person, so:</p>
            				<ul style="list-style:decimal;">
                    			<li>The instructor and their team create a bond with each player over live video chats.</li>
                    			<li>Players are coupled together per <b><i class="fa fa-calendar" aria-hidden="true"></i> Cohort</b> to help each other in completing tasks.</li>
                    			<li>For Odd groups, the instructor becomes the partner of the last odd player.</li>
                    		</ul>
            			</td>
            		</tr>
        		</table>
            </div>
            
            
            
            <div class="wizard-box platform-intro">
        		<table>
            		<tr>
            			<td style="width:90px;" valign="top"><h4><i class="fa large-fa fa-list-ol" aria-hidden="true"></i></h4></td>
            			<td>
            				<h4>Principle #3: Step-by-Step Navigation</h4>
            				<p>Complexity causes procrastination. Guidelines streamlione execution. So Mench games:</p>
            				<ul style="list-style:decimal;">
                    			<li>Provide a roadmap to the <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> primary goal</b> using a clear, step-by-step &nbsp;<b style="display:inline-block;"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b>.</li>
                    			<li>Break down the <b style="display:inline-block;"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b> further into sub-tasks for more detailed instructions.</li>
                    			<li>Have <b><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Tips</b> referenced from the web (Blogs, YouTube, etc...) to help players in taking action.</li>
                    		</ul>
            			</td>
            		</tr>
        		</table>
            </div>
            
            
            
             <div class="wizard-box platform-intro">
        		<table>
            		<tr>
            			<td style="width:90px;" valign="top"><h4><i class="fa large-fa fa-calendar-plus-o" aria-hidden="true"></i></h4></td>
            			<td>
            				<h4>Principle #4: Deadlines</h4>
            				<p>To maximize engagements, Mench games enforce a DAILY or WEEKLY <b style="display:inline-block;"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b> deadline, known as the <b><i class="fa fa-hourglass-end" aria-hidden="true"></i> Deadline Frequency</b>:</p>
            				<ul style="list-style:decimal;">
                    			<li>Deadlines create urgency and help players stay focused on the task on hand.</li>
                    			<li>Daily deadlines due nightly at 11:59pm. Weekly deadlines due Sundays at 11:59pm.</li>
                    			<li>Players earn 1x points if "on-time" and 0.5x points if "A little late". <a href="https://support.mench.co/hc/en-us/articles/115002372531" target="_blank">Learn More <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a></li>
                    		</ul>
            			</td>
            		</tr>
        		</table>
            </div>
            
            
            <div class="wizard-box platform-intro">
        		<table>
            		<tr>
            			<td style="width:90px;" valign="top"><h4><i class="fa large-fa fa-line-chart" aria-hidden="true"></i></h4></td>
            			<td>
            				<h4>Principle #5: Incremental Difficulty Rise</h4>
            				<p>Humans form habits by starting somewhere managable and growing incrementally. So:</p>
            				<ul style="list-style:decimal;">
                    			<li>We encourage instructors to design games that start easy and get incrementally harder after each <b><i class="fa fa-hourglass-end" aria-hidden="true"></i> Deadline Frequency</b>.</li>
                    			<li>Players get to warm-up to the game before being put under pressure to perform.</li>
                    			<li>This principle reduces drop-out rates while increasing player engagements.</li>
                    		</ul>
            			</td>
            		</tr>
        		</table>
            </div>

            
            
            
            <div class="wizard-box platform-intro">
        		<table>
            		<tr>
            			<td style="width:90px; text-align:center;" valign="top"><h4><i class="fa xlarge-fa fa-usd" aria-hidden="true"></i></h4></td>
            			<td>
            				<h4>Principle #6: High Stakes Commitments</h4>
            				<p>Humans have a hard time respecting things they easily earned, which also includes online course that only cost $0-$50! So Mench games:</p>
            				<ul style="list-style:decimal;">
                    			<li>Cost $100s or $1000s of dollars per seat depending on the <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> primary goal</b>.</li>
                    			<li>High Tuitions are justified because of personalized support provided by instructors.</li>
                    			<li>This causes players to commit & follow their instructor as they are vested in the game.</li>
                    		</ul>
            			</td>
            		</tr>
        		</table>
            </div>
            
            
            
            <div class="wizard-box platform-intro">
        		<table>
            		<tr>
            			<td style="width:90px;" valign="top"><h4><i class="material-icons xlarge-fa">verified_user</i></h4></td>
            			<td>
            				<h4>Principle #7: Tuition Guarantee</h4>
            				<p>If players followed the Action Plan and did not accomplish the <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> primary goal</b> by the end of the game, they will receive a full account credit. Why?</p>
            				<ul style="list-style:decimal;">
                    			<li>Players gain more confidence and trust in their instructor and the <b style="display:inline-block;"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b>.</li>
                    			<li>Instructors gain more credibility by aligning their interest with their players interest.</li>
                    			<li>IF a player fails, it's 99% because they QUIT and NOT because they followed the <b style="display:inline-block;"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b> and didn't get results!</li>
                    		</ul>
                    		<p>You can read more about <a href="https://support.mench.co/hc/en-us/articles/115002080031" target="_blank" style="display:inline-block;">Tuition Guarantee <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a></p>
            			</td>
            		</tr>
        		</table>
            </div>
            
            
            <div class="wizard-box platform-intro">
        		<p>Now you know the foundation that powers Mench:</p>
        		<ul style="list-style:none;">
        			<li>2 Technology Stacks: Facebook Messenger & Groups</li>
        			<li>7 Learning Principles (which we won't repeat)</li>
        		</ul>
        		<p>But before we get into the Bootcamp creation wizard, let's review how earnings & payments work at Mench...</p>
        		
            </div>
            
            
           
            
            <div class="wizard-box platform-intro">
        		<p>Here are the instructor earning & payment highlights:</p>
        		<ul style="list-style:decimal;">
        			<li>Mench games can be paid or free. We'd never ever charge for free games! We charge 15% commission for paid games so we can build/grow the platform for you.</li>
        			<li>Mench is a tool for independant instructors to transform their online courses into games and build their own Mench business, much like Upwork or Airbnb.</li>
        			<li>You set the tuition per game, which usually correlates to the level of personalized support and game duration. We also have a price calculator to make suggestions.</li>
        		</ul>
				<p>Next we will review how we payout your Mench earnings...</p>
            </div>
            
           
            <div class="wizard-box platform-intro">
        		<p>Your total earnings for each game are grouped in 3 buckets:</p>
        		<ul style="list-style:decimal;">
        			<li><b>35% Instant Payout</b>: Sent to your Paypal on the very first day of the game. So if you sell 10 seats x $2,000 = $20,000 then you get $7,000 on day 1 of the game.</li>
        			<li><b>50% Performance Payout</b>: Sent to your Paypal 2 weeks after the game end date and accounts for cancellations caused by your <a href="https://support.mench.co/hc/en-us/articles/115002095952" target="_blank" style="display:inline-block;">Refund Policy <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a> & <a href="https://support.mench.co/hc/en-us/articles/115002080031" target="_blank" style="display:inline-block;">Tuition Guarantee <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a>.</li>
        			<li><b>15% Universal Commission</b>: Ensures we can continue building the tools you need to constantly grow your business. We only charge commission on successful transaction calculated during your Performance Payout.</li>
        		</ul>
            </div>

            <div class="wizard-box platform-intro">
        		<p>Ok, now you know more about the Mench platform and how it works!</p>
        		<br />
        		<p>Let's start creating a new bootcamp.</p>
            </div>
            
             
            <div class="wizard-box">
        		<p>A few notes for creating a new Mench bootcamp:</p>
        		<ul style="list-style:decimal;">
        			<li><b>Don't sweat it</b> as you can edit everything you input in this wizard after you created your bootcamp.</li>
        			<li><b>Build Iteratively</b> as the best bootcamps are created over time by learning from your real-world players.</li>
        		</ul>
            </div>
            
            
            
            <div class="wizard-box">
        		<p>First let's create your bootcamp by defining these 3 fields:</p>
        		<ul style="list-style:decimal;" class="aligned-list">
        			<li><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Bootcamp Primary Goal</li>
        			<li><i class="fa fa-hourglass-end" aria-hidden="true"></i> Bootcamp Deadline Frequency</li>
        			<li><i class="fa fa-binoculars" aria-hidden="true"></i> Bootcamp Overview</li>
        		</ul>
            </div>
            
            
            <script> /*$('#newBootcampModal').on('shown.bs.modal', function () {move_ui(18);}); */</script>
                        
            <div class="wizard-box" id="wz_objective">
            	<?php $this->load->view('console/inputs/c_objective' ); ?>
            </div>
            
            <div class="wizard-box" id="wz_sprint_unit">
            	<?php $this->load->view('console/inputs/b_sprint_unit' ); ?>
            </div>
            
            <div class="wizard-box">
            	<?php $this->load->view('console/inputs/c_todo_overview' ); ?>
            </div>
            
            
            <div class="wizard-box">
        		<p>That was pretty easy right?</p>
        		<p>On the next section, we will start building the very first draft of your <b style="display:inline-block;"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b>.</p>
            </div>
            
            
            <div class="wizard-box">
        		<p>Dynamic action plan builder here...</p>
            </div>
            
            
            <div class="wizard-box">
        		<p>That was pretty easy right?</p>
        		<p>On the next section, we will start building the very first draft of your <b style="display:inline-block;"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b>.</p>
            </div>
            
            
            <div class="wizard-box" id="wz_start_day_time">
            	<?php $this->load->view('console/inputs/r_start_day_time' , array(
                    'r_start_time_mins' => 540, //9 AM Default
                )); ?>
            </div>
            
            
            
            <div class="wizard-box">
            	<p style="text-align:center;"><b>Creating New Bootcamp...</b></p>
            	<br />
            	<div id="new_bootcam_result" style="text-align:center; height:200px;"><img src="/img/round_load.gif" class="loader" /></div>
			</div>
			
      </div>
      <div class="modal-footer" style="text-align:left;">
        	<a id="btn_prev" href="javascript:move_ui(-1)" class="btn btn-primary" style="padding-left:10px;padding-right:12px; display:none;"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
            <span id="btn_next"><a href="javascript:move_ui(1)" class="btn btn-primary">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a><span class="enter">or press <b>CTRL+ENTER</b></span></span>
            
            <div style="text-align:right; margin:-30px 2px 0;"><b id="step_progress"></b></div>
            <div class="progress" style="margin:auto 2px;">
            	<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"></div>
            </div>
      </div>
    </div>
  </div>
</div>
