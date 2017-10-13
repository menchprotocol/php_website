<script>
//Bootcamp admin management features
function ba_add(){
	alert('Contact us at support@mench.co to modify team members.');
}
function ba_open_modify(){
	alert('Contact us at support@mench.co to modify team members.');
}


function save_settings(){
	//Show spinner:
	$('#save_setting_results').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
	
	//Save the rest of the content:
	$.post("/process/bootcamp_edit", {
		
		b_id:$('#b_id').val(),
		b_status:$('#b_status').val(),
		b_url_key:$('#b_url_key').val(),
		b_video_url:$('#b_video_url').val(),
		b_image_url:$('#b_image_url').val(),
		
	} , function(data) {
		
		//Update UI to confirm with user:
		$('#save_setting_results').html(data).hide().fadeIn();
		
		//Disapper in a while:
		setTimeout(function() {
			$('#save_setting_results').fadeOut();
	    }, 10000);
    });
}
</script>




<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />




<ul class="nav nav-pills nav-pills-primary">
  <li class="active"><a href="#pill1" data-toggle="tab"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
  <li><a href="#pill2" data-toggle="tab"><i class="fa fa-user-plus" aria-hidden="true"></i> Team</a></li>
</ul>


<div class="tab-content tab-space">

    <div class="tab-pane active" id="pill1">
    
    	<div class="title" style="margin-top:0;"><h4>Bootcamp Status</h4></div>
		<?php echo_status_dropdown('b','b_status',$bootcamp['b_status']); ?>
		<div style="clear:both; margin:0; padding:0;"></div>
		
        <div class="title" style="margin-top:15px;"><h4>Landing Page URL Key</h4></div>
        <div class="form-group label-floating is-empty">
        	<p>This is the URL of your bootcamp that would be shared with students to enroll:</p>
        	<div class="input-group border">
              <span class="input-group-addon addon-lean" style="color:#CCC;"> &nbsp;&nbsp;https://mench.co/bootcamps/</span>
              <input type="text" id="b_url_key" style="text-transform:lowercase; margin:0 0 -2px -12px !important; font-size:18px !important; " value="<?= $bootcamp['b_url_key'] ?>" maxlength="255" class="form-control" />
            </div>
            <p class="extra-info" style="margin-bottom:0; padding-bottom:0;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warning: URL changes break previously shared links.</p>
            <div style="margin-bottom:20px;"><a href="/bootcamps/<?= $bootcamp['b_url_key'] ?>" target="_blank" class="btn btn-default landing_page_url">View Landing Page <i class="fa fa-external-link" style="font-size:1em;" aria-hidden="true"></i></a></div>
        </div>
        
        
        <div class="title"><h4>Featured Image URL (w/h ratio of 1.78) <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="The image for the marketplace."></i></h4></div>
        <div class="form-group label-floating is-empty border" style="padding-bottom:0;">
            <input type="url" id="b_image_url" value="<?= $bootcamp['b_image_url'] ?>" style="margin-bottom:0;" class="form-control">
            <span class="material-input"></span>
        </div>
        
        
        <br />
        <div class="title"><h4>Featured Video URL (2-3 minutes max) <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="The video that would be displayed on the landing page of the bootcamp that explains why students should join your bootcamp."></i></h4></div>
        <div class="form-group label-floating is-empty border" style="padding-bottom:0;">
            <input type="url" id="b_video_url" value="<?= $bootcamp['b_video_url'] ?>" style="margin-bottom:0;" class="form-control">
            <span class="material-input"></span>
        </div>
        
        
        <br />
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span id="save_setting_results"></span></td></tr></table>
        
    </div>
    
    
    <div class="tab-pane" id="pill2">
    
		<p>Here's a list of your bootcamp team members that can assist you with operations. Contact us via live chat to add/remove team members.</p>
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
    
</div>





