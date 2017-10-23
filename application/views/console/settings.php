<script>
//Bootcamp admin management features
function ba_add(){
	alert('Contact us at support@mench.co to modify team members.');
}


$(document).ready(function() {
	//Detect any possible hashes that controll the menu?
	if(window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        //Open specific menu with a 100ms delay to fix TOP NAV bug
    	setTimeout(function() {
    		$('.tab-pane, #topnav > li').removeClass('active');
    		$('#'+hash+', #nav_'+hash).addClass('active');
	    }, 100);
    }
});

function save_settings(){
	//Show spinner:
	$('.save_setting_results').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
	
	//Save the rest of the content:
	$.post("/process/bootcamp_edit", {
		
		b_id:$('#b_id').val(),
		b_status:$('#b_status').val(),
		b_url_key:$('#b_url_key').val(),
		b_video_url:$('#b_video_url').val(),
		b_sprint_unit:$('input[name=b_sprint_unit]:checked').val(),
		
		c_id:$('#c_id').val(),
		c_objective:$('#c_objective').val(),
 		c_todo_overview:( c_todo_overview_quill.getLength()>1 ? $('#c_todo_overview .ql-editor').html() : "" ),
		
	} , function(data) {
		
		//Update UI to confirm with user:
		$('.save_setting_results').html(data).hide().fadeIn();
		
		//Disapper in a while:
		setTimeout(function() {
			$('.save_setting_results').fadeOut();
	    }, 10000);
    });
}
</script>




<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />
<input type="hidden" id="c_id" value="<?= $bootcamp['b_c_id'] ?>" />


<ul id="topnav" class="nav nav-pills nav-pills-primary">
  <li id="nav_details" class="active"><a href="#details" data-toggle="tab" onclick="update_hash('details')"><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a></li>
  <li id="nav_team"><a href="#team" data-toggle="tab" onclick="update_hash('team')"><i class="fa fa-user-circle" aria-hidden="true"></i> Team</a></li>
  <li id="nav_settings"><a href="#settings" data-toggle="tab" onclick="update_hash('settings')"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
</ul>


<div class="tab-content tab-space">

    <div class="tab-pane active" id="details">
    
    	<p>Mench bootcamps offer a S.M.A.R.T. Framework for achieving goals that are:</p>
    	<ul>
			<li><b>S</b>pecific like "Get hired as front-end developer" as the "Primary Goal".</li>
            <li><b>M</b>easurable by also adding "Make $40k+/year" to the "Overview".</li>
            <li><b>A</b>ttainable by providing a step-by-step <a href="/console/<?= $bootcamp['b_id'] ?>/actionplan"><u>Action Plan</u></a> for students to follow.</li>
            <li><b>R</b>ewarding by having a <a href="https://support.mench.co/hc/en-us/articles/115002315231"><u>Gamified Leaderboard <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a> for every <a href="/console/<?= $bootcamp['b_id'] ?>/cohorts"><u>Cohort</u></a>.</li>
            <li><b>T</b>ime-bound by defining the total duration of each cohort upfront.</li>
		</ul>		
		
		
		
		<br />
    	<div class="title"><h4><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Primary Goal</h4></div>
    	<ul>
			<li>Describe your bootcamp's key offering in 70 characters or less.</li>
            <li>Define a goal that is both "Specific" and "Measurable".</li>
            <li>This sets the bar for our <a href="https://support.mench.co/hc/en-us/articles/115002080031"><u>Tuition Reimbursement Guarantee <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
			<li>Success is % of students who accomplish this goal by the end date.</li>
		</ul>
        <div class="form-group label-floating is-empty">
            <input type="text" id="c_objective" maxlength="70" value="<?= $bootcamp['c_objective'] ?>" class="form-control border">			
        </div>
        
        
        
        
        <br />
        <div class="title"><h4><i class="fa fa-youtube-play" aria-hidden="true"></i> Explainer Video URL</h4></div>
        <ul>
			<li>Explains who should join this bootcamp and why.</li>
			<li>Displayed at the top of your landing page.</li>
			<li>We highly recommended a short 1-3 minute video.</li>
  			<li>Enter a YouTube URL or link to a video file hosted online.</li>
		</ul>
		<div class="form-group label-floating is-empty border" style="padding-bottom:0;">
            <input type="url" id="b_video_url" placeholder="https://www.youtube.com/watch?v=Ec4o68fSRsc" value="<?= $bootcamp['b_video_url'] ?>" style="margin-bottom:0;" class="form-control">
            <span class="material-input"></span>
        </div>
        
        
        
        
        <br />
        <div class="title"><h4><i class="fa fa-binoculars" aria-hidden="true"></i> Overview</h4></div>
        <ul>
			<li>Give an overview of how you plan to help students accomplish the Primary Goal.</li>
            <li>Displayed at the top of the landing page.</li>
		</ul>
        <div id="c_todo_overview"><?= $bootcamp['c_todo_overview'] ?></div>
        <script> var c_todo_overview_quill = new Quill('#c_todo_overview', setting_full); </script>


		
        
        
        <br />
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_setting_results"></span></td></tr></table>
        
    </div>
    
    
    <div class="tab-pane" id="team">
    	<p>Your team scales your 1-on-1 support and ability to manage your operations:</p>
    	<ul>
			<li>You hire/pay/manage your team. We give you tools to make them efficient.</li>
			<li>Each bootcamp must have a single <?= status_bible('ba',3) ?>.</li>
			<li>Team members can be added as <?= status_bible('ba',1) ?> or <?= status_bible('ba',2) ?>.</li>
			<li>Team members can be displayed on the landing page or be hidden.</li>
            <li>Contact us via live chat (bottom-right) to modify team members.</li>
		</ul>
    	<?php
    	echo '<div id="list-outbound" class="list-group">';
    	foreach($bootcamp['b__admins'] as $admin){
    	    echo echo_br($admin);
    	}
		echo '</div>';
		?>
		
		<!-- 
		<div id="list-outbound" class="list-group">
    		<div class="list-group-item list_input">
				<div class="input-group">
					<div class="form-group is-empty" style="margin: 0; padding: 0;">
						<input type="email" class="form-control autosearch" id="addAdmin" placeholder="johnsmith@gmail.com">
					</div>
					<span class="input-group-addon" style="padding-right:0;">
						<span class="label label-primary pull-right" style="cursor:pointer;" onclick="ba_add();">
							<div><i class="fa fa-plus"></i></div>
						</span>
					</span>
				</div>
			</div>
		</div>
		-->
    </div>
    
    <div class="tab-pane" id="settings">
    	
    	
    	<div class="title" style="margin-top:0;"><h4><i class="fa fa-circle" aria-hidden="true"></i> Bootcamp Status</h4></div>
    	<ul>
			<li>Bootcamps are created in <?= status_bible('b',0) ?> mode to give you time to build them.</li>
			<li>When you're ready to publish you update the status to <?= status_bible('b',1) ?>.</li>
			<li>We will start our review process & work with you to iteratively improve it.</li>
			<li>Once ready & approved we update the status to <?= status_bible('b',3) ?>.</li>
			<li>You may also get your bootcamp published with the status <?= status_bible('b',2) ?>.</li>
		</ul>
		<?php echo_status_dropdown('b','b_status',$bootcamp['b_status']); ?>
		<div style="clear:both; margin:0; padding:0;"></div>
		
		
		
		
		
		<div class="title"><h4><i class="fa fa-hourglass-end" aria-hidden="true"></i> Action Plan Frequency</h4></div>
        <ul>
			<li>Sets the frequency between each <a href="/console/<?= $bootcamp['b_id'] ?>/actionplan"><u>Action Plan</u></a> item.</li>
			<li>Used to calculate cohort durations based on the number of <a href="/console/<?= $bootcamp['b_id'] ?>/actionplan"><u>Action Plans</u></a>.</li>
		</ul>
		<?php 
		$sprint_units = $this->config->item('sprint_units');
		foreach($sprint_units as $key=>$sprint_unit){
		    echo '<div class="radio">
        	<label>
        		<input type="radio" name="b_sprint_unit" value="'.$key.'" '.( $bootcamp['b_sprint_unit']==$key ? 'checked="true"' : '' ).' />
        		'.$sprint_unit['name'].'<i style="font-weight:normal; font-style:normal;">: '.$sprint_unit['desc'].'</i>
        	</label>
        	</div>';
		}
		?>
		
		
		
		
		<br />
		<div class="title" style="margin-top:15px;"><h4><i class="fa fa-chrome" aria-hidden="true"></i> Landing Page URL</h4></div>
        <ul>
			<li>Your bootcamp's unique landing page URL.</li>
			<li>Accepts letter and hyphens as inputs. No numbers or other characters.</li>
			<li>Share with students to enroll in your online bootcamp.</li>
			<li>Be aware that URL changes break previously shared links.</li>
		</ul>
        <div class="form-group label-floating is-empty">
        	<div class="input-group border">
              <span class="input-group-addon addon-lean" style="color:#CCC;"> &nbsp;&nbsp;https://mench.co/bootcamps/</span>
              <input type="text" id="b_url_key" style="text-transform:lowercase; margin:0 0 -2px -12px !important; font-size:18px !important; " value="<?= $bootcamp['b_url_key'] ?>" maxlength="255" class="form-control" />
            </div>
            <div style="margin-bottom:20px;"><a href="/bootcamps/<?= $bootcamp['b_url_key'] ?>" target="_blank" class="btn btn-default landing_page_url">View Landing Page <i class="fa fa-external-link" style="font-size:1em;" aria-hidden="true"></i></a></div>
        </div>
    	
		
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_setting_results"></span></td></tr></table>
        
    </div>
</div>





