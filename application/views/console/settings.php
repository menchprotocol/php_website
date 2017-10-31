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
    
		<?php $this->load->view('console/inputs/c_objective' , array('c_objective'=>$bootcamp['c_objective']) ); ?>
		<br />
		<?php $this->load->view('console/inputs/b_video_url' , array('b_video_url'=>$bootcamp['b_video_url']) ); ?>
		<br />
		<?php $this->load->view('console/inputs/c_todo_overview' , array('c_todo_overview'=>$bootcamp['c_todo_overview']) ); ?>
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
		<?php $this->load->view('console/inputs/b_url_key' , array('b_url_key'=>$bootcamp['b_url_key']) ); ?>
		
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_setting_results"></span></td></tr></table>
        
    </div>
</div>





