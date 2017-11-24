<script>
//Bootcamp admin management features
function ba_add(){
	alert('Contact us at support@mench.co to modify instructor team.');
}

$(document).ready(function() {

	//Watchout for the copy URL to clipboard:
    $( ".copy_button" ).click(function() {
    	copyToClipboard(document.getElementById("copy_button"));
    	$( ".copy_button" ).hide().fadeIn().css('color','#fedd16');
    });
    
	//Detect any possible hashes that controll the menu?
	if(window.location.hash) {
		focu_hash(window.location.hash);
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
		b_category_id:$('#b_category_id').val(),
		
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
  <li id="nav_general" class="active"><a href="#general" data-toggle="tab" onclick="update_hash('general')"><i class="fa fa-cog" aria-hidden="true"></i> General</a></li>
  <li id="nav_team"><a href="#team" data-toggle="tab" onclick="update_hash('team')"><i class="fa fa-user-plus" aria-hidden="true"></i> Instructors</a></li>
</ul>

<div class="tab-content tab-space">

    <div class="tab-pane active" id="general">
    	
    	
    	<div class="title"><h4><i class="fa fa-tag" aria-hidden="true"></i> Bootcamp Category <span id="hb_626" class="help_button" intent-id="626"></span></h4></div>
    	<div class="help_body maxout" id="content_626"></div>
            
        <ul>
        	<li>Select the primary category that represents the main bootcamp idea.</li>
        </ul>
        <?php echo_status_dropdown('ct','b_category_id',$bootcamp['b_category_id']); ?>
        
        
        <br />
		<div class="title" style="margin-top:0;"><h4><i class="fa fa-circle" aria-hidden="true"></i> Bootcamp Status <span id="hb_627" class="help_button" intent-id="627"></span></h4></div>
        <div class="help_body maxout" id="content_627"></div>
        <?php echo_status_dropdown('b','b_status',$bootcamp['b_status']); ?>
        <div style="clear:both; margin:0; padding:0;"></div>

		
		<div class="title" style="margin-top:15px;"><h4><i class="fa fa-chrome" aria-hidden="true"></i> Bootcamp Hashtag <span id="hb_628" class="help_button" intent-id="628"></span></h4></div>
        <div class="help_body maxout" id="content_628"></div>
        <div class="form-group label-floating is-empty">
        	<div class="input-group border">
              <span class="input-group-addon addon-lean" style="color:#CCC;"> &nbsp;&nbsp;https://mench.co/bootcamps/</span>
              <input type="text" id="b_url_key" style="text-transform:lowercase; margin:0 0 -2px -12px !important; font-size:18px !important; " value="<?= $bootcamp['b_url_key'] ?>" maxlength="255" class="form-control" />
            </div>
            <div style="margin-bottom:20px;">
            	<a href="/bootcamps/<?= $bootcamp['b_url_key'] ?>" target="_blank" class="btn btn-sm btn-default landing_page_url">Open Landing Page &nbsp;<i class="fa fa-external-link" style="font-size:1em;" aria-hidden="true"></i></a>
            	
            	<?php $website = $this->config->item('website'); ?>
				<?php $url = $website['url'].'bootcamps/'.$bootcamp['b_url_key']; ?>
            	<a href="#" class="btn btn-sm btn-default copy_button">Copy URL to Clipboard &nbsp;<i class="fa fa-clone" style="font-size:1em;" aria-hidden="true"></i></a>
            	<div id="copy_button"><?= $url ?></div>
            </div>
        </div>
		
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_setting_results"></span></td></tr></table>
    </div>
    
    
    
    <div class="tab-pane" id="team">
    	<?php
    	itip(629);
    	echo '<div id="list-outbound" class="list-group">';
    	foreach($bootcamp['b__admins'] as $admin){
    	    echo echo_br($admin);
    	}
		echo '</div>';		
		?>
    </div>
    
</div>





