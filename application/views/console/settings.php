<script>
//Bootcamp admin management features
function ba_add(){
	alert('Contact us at support@mench.co to modify instructor team.');
}


$(document).ready(function() {
	//Detect any possible hashes that controll the menu?
	if(window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        //Open specific menu with a 100ms delay to fix TOP NAV bug
        $('.tab-pane, #topnav > li').removeClass('active');
		$('#'+hash+'.tab-pane, #nav_'+hash).addClass('active');
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
		b_newly_checked:(document.getElementById('b_terms_agreement_time').checked ? '1' : '0'),
		
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

<ul id="topnav" class="nav nav-pills nav-pills-primary">
  <li id="nav_landingpage" class="active"><a href="#landingpage" data-toggle="tab" onclick="update_hash('landingpage')"><i class="fa fa-bullhorn" aria-hidden="true"></i> Landing Page</a></li>
  <li id="nav_team"><a href="#team" data-toggle="tab" onclick="update_hash('team')"><i class="fa fa-user-plus" aria-hidden="true"></i> Co-Instructors</a></li>
  <li id="nav_settings"><a href="#settings" data-toggle="tab" onclick="update_hash('settings')"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
</ul>

<div class="tab-content tab-space">

    <div class="tab-pane active" id="landingpage">

		<div class="title"><h4><i class="fa fa-youtube-play" aria-hidden="true"></i> Explainer Video</h4></div>
        <ul>
			<li>A 1-3 minute video explaining who should join this bootcamp and why.</li>
			<li>Published at the top of your landing page.</li>
  			<li>Enter a YouTube URL or link to a video file hosted online.</li>
  			<li>Chat with us if you'd like us to host the video for you.</li>
		</ul>
		<div class="form-group label-floating is-empty border" style="padding-bottom:0;">
            <input type="url" id="b_video_url" placeholder="https://www.youtube.com/watch?v=Ec4o68fSRsc" value="<?= (isset($bootcamp['b_video_url']) ? $bootcamp['b_video_url'] : null) ?>" style="margin-bottom:0;" class="form-control">
            <span class="material-input"></span>
        </div>
		<br />
		
		
		<div class="title" style="margin-top:15px;"><h4><i class="fa fa-chrome" aria-hidden="true"></i> Landing Page URL</h4></div>
        <ul>
        	<li>Your bootcamp's unique landing page URL.</li>
        	<li>Use letters and hyphens. No numbers or other characters.</li>
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
        <br />
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_setting_results"></span></td></tr></table>
        
    </div>
    
    
    <div class="tab-pane" id="team">
    	<p>Co-instructors help you scale your ability to provide 1-on-1 support to manage a larger and more consistent bootcamp operation:</p>
    	<ul>
			<li>Each bootcamp has a single <?= status_bible('ba',3) ?> who is the "Bootcamp CEO".</li>
			<li><?= status_bible('ba',3) ?> can recruit and manage multiple <?= status_bible('ba',2) ?>.</li>
			<li>Each <?= status_bible('ba',2) ?> can have specific read/write privileges.</li>
			<li>Each <?= status_bible('ba',2) ?> can be displayed of hidden from the landing page.</li>
			<li>You can also setup revenue sharing with each <?= status_bible('ba',2) ?>.</li>
            <li>Contact us via live chat (bottom-right) to modify your instructor team.</li>
		</ul>
    	<?php
    	echo '<div id="list-outbound" class="list-group">';
    	foreach($bootcamp['b__admins'] as $admin){
    	    echo echo_br($admin);
    	}
		echo '</div>';
		
		/*
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
		*/
		
		?>
    </div>
    
    <div class="tab-pane" id="settings">
    	
		<div class="title" style="margin-top:0;"><h4><i class="fa fa-circle" aria-hidden="true"></i> Bootcamp Status</h4></div>
        <ul>
        	<li>Bootcamps are created in <?= status_bible('b',0) ?> mode to give you time to build them.</li>
        	<li>When you're ready to publish you update the status to <?= status_bible('b',1) ?>.</li>
        	<li>We will start our review process & work with you to iteratively improve it.</li>
        	<li>Once ready & approved we update the status to <?= status_bible('b',3) ?>.</li>
        </ul>
        <?php echo_status_dropdown('b','b_status',$bootcamp['b_status']); ?>
        <div style="clear:both; margin:0; padding:0;"></div>



		<?php $this->load->view('console/inputs/b_sprint_unit' , array('b_sprint_unit'=>$bootcamp['b_sprint_unit']) ); ?>
		<br />
		
		
		
		
		<div class="title" style="margin-top:15px;"><h4><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Lead Instructor Agreement</h4></div>
        <ul>
        	<li>I have read and understood how <a href="https://support.mench.co/hc/en-us/articles/115002473111" target="_blank"><u>Instructor Earning & Payouts <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a> work.</li>
        	<li>I have read and understood my bootcamp's <a href="https://support.mench.co/hc/en-us/articles/115002080031" target="_blank"><u>Tuition Guarantee <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
        	<li>I have read and understood the <a href="https://support.mench.co/hc/en-us/articles/115002096752" target="_blank"><u>Mench Code of Conduct <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
        	<li>I have read and understood the <a href="https://support.mench.co/hc/en-us/articles/115002096732" target="_blank"><u>Mench Honor Code <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
        	<li>I have read and agreed to Mench's <a href="/terms" target="_blank"><u>Terms of Service & Privacy Policy <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
        </ul>
        <div class="form-group label-floating is-empty">
        	<div class="checkbox">
            	<label>
            		<?php if(isset($bootcamp['b_terms_agreement_time']) && strlen($bootcamp['b_terms_agreement_time'])>0){ ?>
            		<input type="checkbox" id="b_terms_agreement_time" disabled checked /> Agreed on <b><?= time_format($bootcamp['b_terms_agreement_time'],0) ?> PST</b>
            		<?php } else { ?>
            		<input type="checkbox" id="b_terms_agreement_time" /> As the lead bootcamp instructor I certify that all above statements are true
            		<?php } ?>
            	</label>
            </div>
        </div>
		<br />
		
		
		
		
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_setting_results"></span></td></tr></table>
        
    </div>
</div>





