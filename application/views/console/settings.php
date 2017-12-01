<script>
//Bootcamp admin management features
function ba_add(){
	alert('Contact us at support@mench.co to modify instructor team.');
}

$(document).ready(function() {

	//Watchout for the copy URL to clipboard:
    $( ".aff_b_url" ).click(function() {
    	copyToClipboard(document.getElementById("aff_b_url"));
    	$( ".aff_b_url" ).hide().fadeIn().css('color','#fedd16');
    });
    $( ".aff_a_url" ).click(function() {
    	copyToClipboard(document.getElementById("aff_a_url"));
    	$( ".aff_a_url" ).hide().fadeIn().css('color','#fedd16');
    });
    $( ".marketplace_b_url" ).click(function() {
    	copyToClipboard(document.getElementById("marketplace_b_url"));
    	$( ".marketplace_b_url" ).hide().fadeIn().css('color','#fedd16');
    });
    
    //Enforce Alphanumeric for Hashtag:
    $('#b_url_key').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
    
        e.preventDefault();
        return false;
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
	$.post("/api_v1/bootcamp_edit", {
		
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
  <li id="nav_affiliate"><a href="#affiliate" data-toggle="tab" onclick="update_hash('affiliate')"><i class="fa fa-share-alt" aria-hidden="true"></i> Affiliate</a></li>
  <li id="nav_team"><a href="#team" data-toggle="tab" onclick="update_hash('team')"><i class="fa fa-user-plus" aria-hidden="true"></i> Instructors</a></li>
</ul>

<div class="tab-content tab-space">

    <div class="tab-pane active" id="general">
    	
    	<div class="title"><h4><i class="fa fa-tag" aria-hidden="true"></i> Bootcamp Category <span id="hb_626" class="help_button" intent-id="626"></span></h4></div>
    	<div class="help_body maxout" id="content_626"></div>
        <?= echo_status_dropdown('ct','b_category_id',$bootcamp['b_category_id']); ?>
        
        
        <br />
		<div class="title" style="margin-top:30px;"><h4><i class="fa fa-circle" aria-hidden="true"></i> Bootcamp Status <span id="hb_627" class="help_button" intent-id="627"></span></h4></div>
        <div class="help_body maxout" id="content_627"></div>
        <?= echo_status_dropdown('b','b_status',$bootcamp['b_status']); ?>
        <div style="clear:both; margin:0; padding:0;"></div>

		
		<div class="title" style="margin-top:35px;"><h4><i class="fa fa-hashtag" aria-hidden="true"></i> Bootcamp Hashtag <span id="hb_628" class="help_button" intent-id="628"></span></h4></div>
        <div class="help_body maxout" id="content_628"></div>
        <div class="form-group label-floating is-empty">
        	<div class="input-group border" style="max-width:340px;">
              <span class="input-group-addon addon-lean" style="color:#222;">#</span>
              <input type="text" id="b_url_key" style="margin:0 0 -2px -10px !important; font-size:18px !important;" value="<?= $bootcamp['b_url_key'] ?>" maxlength="30" class="form-control" />
            </div>
        </div>
		
        <table width="100%" style="margin-top:50px;"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_setting_results"></span></td></tr></table>
    </div>
    
    
    <div class="tab-pane" id="affiliate">
    	
    	<?php itip(724); ?>
    	<?php $website = $this->config->item('website'); ?>
    	
    	<div class="title"><h4><i class="fa fa-lock" aria-hidden="true"></i> Fixed Variables</h4></div>
    	<p style="margin-bottom:0;">Bootcamp ID: <b><?= $bootcamp['b_id'] ?></b></p>
    	<p style="margin-bottom:0;"><?= $udata['u_email'] ?> Account ID: <b><?= $udata['u_id'] ?></b></p>
    	<p>Track affiliate activity in <a href="/console/account#referrals"><b>My Account</b></p></a>
            
    	
    	<?php $aff_b_url = $website['url'].'a/'.$bootcamp['b_id'].'/'.$udata['u_id']; ?>
    	<div class="title" style="margin-top:30px;"><h4><i class="fa fa-link" aria-hidden="true"></i> Affiliate Bootcamp URL <span id="hb_726" class="help_button" intent-id="726"></span></h4></div>
    	<div class="help_body maxout" id="content_726"></div>
    	<input type="text" disabled value="<?= $aff_b_url ?>" class="form-control disabled" />
    	<div style="margin-bottom:20px;">
        	<a href="<?= $aff_b_url ?>" target="_blank" class="btn btn-sm btn-default">Open &nbsp;<i class="fa fa-external-link-square" style="font-size:1em;" aria-hidden="true"></i></a>
        	<a href="#" class="btn btn-sm btn-default aff_b_url">Copy &nbsp;<i class="fa fa-clone" style="font-size:1em;" aria-hidden="true"></i></a>
        	<div id="aff_b_url" style="display:none;"><?= $aff_b_url ?></div>
        </div>
        
        
        <?php $aff_a_url = $website['url'].'a/'.$bootcamp['b_id'].'/'.$udata['u_id'].'/apply'; ?>
    	<div class="title"><h4><i class="fa fa-link" aria-hidden="true"></i> Affiliate Application URL <span id="hb_727" class="help_button" intent-id="727"></span></h4></div>
    	<div class="help_body maxout" id="content_727"></div>
    	<input type="text" disabled value="<?= $aff_a_url ?>" class="form-control disabled" />
        <div style="margin-bottom:20px;">
        	<a href="<?= $aff_a_url ?>" target="_blank" class="btn btn-sm btn-default">Open &nbsp;<i class="fa fa-external-link-square" style="font-size:1em;" aria-hidden="true"></i></a>
        	<a href="#" class="btn btn-sm btn-default aff_a_url">Copy &nbsp;<i class="fa fa-clone" style="font-size:1em;" aria-hidden="true"></i></a>
        	<div id="aff_a_url" style="display:none;"><?= $aff_a_url ?></div>
        </div>
        
        
        <?php $marketplace_b_url = $website['url'].$bootcamp['b_url_key']; ?>
        <div class="title"><h4><i class="fa fa-link" aria-hidden="true"></i> Standard Bootcamp URL <span id="hb_725" class="help_button" intent-id="725"></span></h4></div>
    	<div class="help_body maxout" id="content_725"></div>
    	<input type="text" disabled id="marketplace_b_url_ui" value="https://mench.co/<?= $bootcamp['b_url_key'] ?>" class="form-control disabled" />
    	<div style="margin-bottom:20px;">
        	<a href="/<?= $bootcamp['b_url_key'] ?>" target="_blank" class="btn btn-sm btn-default landing_page_url">Open &nbsp;<i class="fa fa-external-link-square" style="font-size:1em;" aria-hidden="true"></i></a>
        	<a href="#" class="btn btn-sm btn-default marketplace_b_url">Copy &nbsp;<i class="fa fa-clone" style="font-size:1em;" aria-hidden="true"></i></a>
        	<div id="marketplace_b_url" style="display:none;"><?= $marketplace_b_url ?></div>
        </div>
       
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





