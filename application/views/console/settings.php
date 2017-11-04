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

		<?php $this->load->view('console/inputs/b_video_url' , array('b_video_url'=>$bootcamp['b_video_url']) ); ?>
		<br />
		<?php $this->load->view('console/inputs/b_url_key' , array('b_url_key'=>$bootcamp['b_url_key']) ); ?>
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
    	
		<?php $this->load->view('console/inputs/b_status' , array('b_status'=>$bootcamp['b_status']) ); ?>
		<?php $this->load->view('console/inputs/b_sprint_unit' , array('b_sprint_unit'=>$bootcamp['b_sprint_unit']) ); ?>
		<br />
		<?php $this->load->view('console/inputs/b_terms_agreement' , array('b_terms_agreement_time'=>$bootcamp['b_terms_agreement_time']) ); ?>
		<br />
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_setting_results"></span></td></tr></table>
        
    </div>
</div>





