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
			c_objective:$('#c_objective').val(),
     		c_todo_overview:( c_todo_overview_quill.getLength()>1 ? $('#c_todo_overview .ql-editor').html() : "" ),
     		b_sprint_unit:$('input[name=b_sprint_unit]:checked').val(),
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
    			<li>Success is % of students who accomplish this when bootcamp ends.</li>
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
            </div>
            
            <div class="wizard-box platform-intro">
        		<p>Mench is a marketplace for online bootcamps.</p>
        		<p>We've designed a learning chatbot called <b><i class="fa fa-commenting" aria-hidden="true"></i> MenchBot</b> that dramatically increases student engagement.</p>
        		<p>How?</p>
        		<ul style="list-style:decimal;">
        			<li>By deliverying learning content over Facebook Messenger which is a highly engaging platform used by over 1.3 Billion users.</li>
        			<li>By stacking 7 key learning principles that when combined, create a more action-driven learning experience.</li>
        		</ul>
        		<p>Let's talk about <b><i class="fa fa-commenting" aria-hidden="true"></i>  MenchBot</b>...</p>
            </div>
            
            
            <div class="wizard-box platform-intro">
        		<p><b><i class="fa fa-commenting" aria-hidden="true"></i> MenchBot</b> empowers instructors to deliver an engaging learning experience by leveraging features designed to help students to <b>easily take action</b>.</p>
        		<p>Students do not need to install any apps or login to any websites to learn. They simply chat and interact with <b style="display:inline-block;"><i class="fa fa-commenting" aria-hidden="true"></i> MenchBot</b>.</p>
        		<br />
        		<p>So, how do I manage my bootcamps using <b style="display:inline-block;"><i class="fa fa-commenting" aria-hidden="true"></i> MenchBot</b>?</p>
            </div>
            
            
            <div class="wizard-box platform-intro">
        		<p>Mench bootcamps are managed using the Console which is the web-based software that you're currently logged into. Each bootcamp has 3 key components:</p>
        		<ul style="list-style:none; margin-left:-20px;">
        			<li><b><i class="fa fa-flag" aria-hidden="true"></i> Milestones</b> that collectively lead students to accomplish the bootcamp's primary goal. Each milestone can have a number of <b><i class="fa fa-check-square" aria-hidden="true"></i> Tasks</b> for more instructions.</li>
        			<li><b><i class="fa fa-calendar" aria-hidden="true"></i> Cohorts</b> that group students based on start date and enable you to run your bootcamp multiple times.</li>
        			<li><b><i class="fa fa-users" aria-hidden="true"></i> Students</b> that join your bootcamp and earn points for completing <b><i class="fa fa-flag" aria-hidden="true"></i> Milestones</b> on-time.</li>
        		</ul>
        		<p>Now let's review our 7 key learning principles...</p>
            </div>
            
            
            
            
            
            
            
            <div class="wizard-box platform-intro">
        		<p>There are no silver bullets when it comes to inspiring students to take action in a world with so many distractions. But here are the 7 principles we use to encouraging students to take action:</p>
        		<ul style="list-style:none;" class="aligned-list">
        			<li><i class="fa fa-gamepad" aria-hidden="true"></i> Gamification</li>
        			<li><i class="fa fa-handshake-o" aria-hidden="true"></i> Accountability</li>
        			<li><i class="fa fa-flag" aria-hidden="true"></i> Step-by-Step Milestones</li>
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
            				<p>Most students LOVE playing games, so we designed each bootcamp similar to a game:</p>
            				<ul style="list-style:decimal;">
                    			<li>The game objective is to accomplish the bootcamp's <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> primary goal</b> ON-TIME!<br />i.e. "<i class="fa fa-dot-circle-o" aria-hidden="true"></i> Build HTML5/JS Website in 2 Weeks"</li>
                    			<li>Students earn points for completing tasks on-time. Task points correlate to their estimated completion time set by the instructor.</li>
                    			<li>Each bootcamp has a leaderboard that publicly lists the top 20% of students while privately helping instructors identify students who are falling behind.</li>
                    		</ul>
                    		<p>You can read more about <a href="https://support.mench.co/hc/en-us/articles/115002372531" target="_blank">Mench Bootcamps <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a></p>
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
            				<p>Students are WAY LESS likely to quit if held accountable by another person, so:</p>
            				<ul style="list-style:decimal;">
                    			<li>The instructor and their team create a bond with each student through personalized interactions.</li>
                    			<li>Students are coupled together per <b><i class="fa fa-calendar" aria-hidden="true"></i> Cohort</b> to help each other in completing milestones.</li>
                    			<li>For Odd groups, the instructor becomes the partner of the last odd student.</li>
                    		</ul>
            			</td>
            		</tr>
        		</table>
            </div>
            
            
            
            <div class="wizard-box platform-intro">
        		<table>
            		<tr>
            			<td style="width:90px;" valign="top"><h4><i class="fa large-fa fa-flag" aria-hidden="true"></i></h4></td>
            			<td>
            				<h4>Principle #3: Step-by-Step Milestones</h4>
            				<p>Complexity causes procrastination. Guidelines streamlione execution. So:</p>
            				<ul style="list-style:decimal;">
                    			<li>Each bootcamp provides a roadmap to the <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> primary goal</b> using well-defined and step-by-step <b style="display:inline-block;"><i class="fa fa-flag" aria-hidden="true"></i> Milestones</b>.</li>
                    			<li><b><i class="fa fa-flag" aria-hidden="true"></i> Milestones</b> are further broken down into <b><i class="fa fa-check-square" aria-hidden="true"></i> Tasks</b> for more detailed instructions.</li>
                    			<li><b><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Tips</b> can be added to milestones and tasks to share action-driven insights with students referenced from around the internet (Blogs, YouTube, etc...).</li>
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
            				<p>To maximize engagements, bootcamps enforce a DAILY or WEEKLY milestone submission timeline called the <b><i class="fa fa-hourglass-end" aria-hidden="true"></i> Milestone Submission Frequency</b>:</p>
            				<ul style="list-style:decimal;">
                    			<li>Deadlines create urgency and help students stay focused on the task on hand.</li>
                    			<li>Daily milestones due nightly at 11:59pm. Weekly milestones due Sundays at 11:59pm.</li>
                    			<li>Students earn 1x points if "on-time" and 0.5x points if "A little late". <a href="https://support.mench.co/hc/en-us/articles/115002372531" target="_blank">Learn More <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a></li>
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
            				<p>Students form habits by starting somewhere managable and growing incrementally. So:</p>
            				<ul style="list-style:decimal;">
                    			<li>Bootcamps should be designed to start-off easy and get incrementally harder one <b style="display:inline-block;"><i class="fa fa-flag" aria-hidden="true"></i> Milestone</b> at a time.</li>
                    			<li>Students get to warm-up during the first few milestones before going under pressure to perform.</li>
                    			<li>This reduces drop-out rates while increasing student engagement.</li>
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
            				<p>Most students find it difficult to value things they easily earned including online course that only costed them $0-$50! So:</p>
            				<ul style="list-style:decimal;">
                    			<li>A typical Mench bootcamp cost $100s or $1000s of dollars per seat depending on its duration and level of personalized support.</li>
                    			<li>Higher tuitions are justified because of personalized support provided by instructors.</li>
                    			<li>Paying more usually causes students to become more focused as they become more vested in their own success.</li>
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
            				<p>If students complete all <b><i class="fa fa-flag" aria-hidden="true"></i> Milestones</b> by the end-date and do not accomplish the <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> primary goal</b> of the bootcamp, they will receive a full account credit. Why?</p>
            				<ul style="list-style:decimal;">
                    			<li>Students gain more confidence/trust in the bootcamp's ability to deliver results.</li>
                    			<li>Instructors gain more credibility by aligning their interest with their students interest.</li>
                    			<li>IF a student fails, it's 99% because they QUIT and NOT because they complete all <b style="display:inline-block;"><i class="fa fa-flag" aria-hidden="true"></i> Milestones</b> and didn't get results!</li>
                    		</ul>
                    		<p>You can read more about <a href="https://support.mench.co/hc/en-us/articles/115002080031" target="_blank" style="display:inline-block;">Tuition Guarantee <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a></p>
            			</td>
            		</tr>
        		</table>
            </div>
            
            
            <div class="wizard-box platform-intro">
        		<p>Now you know how Mench works.</p>
        		<br />
        		<p>Let's review our instructor earning and commission model...</p>
            </div>
            
            
           
            
            <div class="wizard-box platform-intro">
        		<p>Here are the instructor earning & commission highlights:</p>
        		<ul style="list-style:decimal;">
        			<li>Mench bootcamps can be paid or free. We'd never charge for free bootcamps as we only charge 15% commission for paid bootcamps.</li>
        			<li>Mench is a tool for online instructors to transform their existing courses into bootcamps and build their own business, much like Upwork or Airbnb.</li>
        			<li>You set the tuition per bootcamp which usually correlates to the level of personalized support and total duration.</li>
        		</ul>
				<p>Next we will review how we payout instructor earnings...</p>
            </div>
            
           
            <div class="wizard-box platform-intro">
        		<p>We subtract Mench's 15% commission from your cohort's gross earnings and payout the remaining 85% in 2 installments:</p>
        		<ul style="list-style:decimal;">
        			<li><b>35% Instant Payout</b>: Sent to your Paypal on the very first day of the cohort. So if you sell 10 seats x $2,000 = $20,000 then you get $7,000 on day 1 of the cohort.</li>
        			<li><b>50% Performance Payout</b>: Sent to your Paypal 2 weeks after the cohort's end date and accounts for your <a href="https://support.mench.co/hc/en-us/articles/115002095952" target="_blank" style="display:inline-block;">Refund Policy <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a> & <a href="https://support.mench.co/hc/en-us/articles/115002080031" target="_blank" style="display:inline-block;">Tuition Guarantee <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a>.</li>
        		</ul>
        		<p>See a payout example on <a href="https://support.mench.co/hc/en-us/articles/115002473111" target="_blank" style="display:inline-block;">Instructor Earning & Payouts <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a>.</p>
            </div>

            <div class="wizard-box platform-intro">
        		<p>Ok, now you know how Mench earning and payouts work.</p>
        		<br />
        		<p>Let's start creating a new bootcamp.</p>
            </div>
            
             
            <div class="wizard-box">
        		<p>A few notes for creating a new bootcamp:</p>
        		<ul style="list-style:decimal;">
        			<li><b>Don't sweat it</b> as you can edit everything you input in this wizard after you created your bootcamp.</li>
        			<li><b>Build Iteratively</b> as the best bootcamps are created over time by learning from your real-world students.</li>
        		</ul>
            </div>
            
            
            
            <div class="wizard-box">
        		<p>Let's create your bootcamp by defining these 3 fields:</p>
        		<ul style="list-style:decimal;" class="aligned-list">
        			<li><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Bootcamp Primary Goal</li>
        			<li><i class="fa fa-hourglass-end" aria-hidden="true"></i> Milestone Submission Frequency</li>
        			<li><i class="fa fa-binoculars" aria-hidden="true"></i> Bootcamp Overview</li>
        		</ul>
            </div>
            
            
            <script> /* $('#newBootcampModal').on('shown.bs.modal', function () {move_ui(18);}); */ </script>
                        
            <div class="wizard-box" id="wz_objective">
            	<?php $this->load->view('console/inputs/c_objective' , array(
                    'level' => 1,
                )); ?>
            </div>
            
            <div class="wizard-box" id="wz_sprint_unit">
            	<?php $this->load->view('console/inputs/b_sprint_unit' ); ?>
            </div>
            
            <div class="wizard-box">
            	<?php $this->load->view('console/inputs/c_todo_overview' , array(
                    'level' => 1,
                )); ?>
            </div>
            
            
            <?php /*
            <div class="wizard-box">
        		<p>That was pretty easy right?</p>
        		<p>On the next section, we will start building the very first draft of your <b><i class="fa fa-flag" aria-hidden="true"></i> Milestones</b>.</p>
            </div>
            
            
            <div class="wizard-box">
        		<p>Dynamic builder here...</p>
            </div>
            
            
            <div class="wizard-box">
        		<p>That was pretty easy right?</p>
            </div>
            
            
            <div class="wizard-box" id="wz_start_day_time">
            	<?php $this->load->view('console/inputs/r_start_day_time' , array(
                    'r_start_time_mins' => 540, //9 AM Default
                )); ?>
            </div>
            */ ?>
            
            
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
