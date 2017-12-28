<?php
$uses = $this->session->userdata('user');
$ufetch = $this->Db_model->u_fetch(array(
    'u_id' => $uses['u_id'],
));
if(!(count($ufetch)==1)){
    redirect_message('/console','Session expired.');
}
$udata = $ufetch[0];
$message_max = $this->config->item('message_max');
?>
<script>
$(document).ready(function() {
	//Detect any possible hashes that controll the menu?
	if(window.location.hash) {
		focu_hash(window.location.hash);
    }

	//Counter:
	changeBio();
});

//Count text area characters:
function changeBio() {
    var len = $('#u_bio').val().length;
    if (len > <?= $message_max ?>) {
    	$('#charNum').addClass('overload').text(len);
    } else {
        $('#charNum').removeClass('overload').text(len);
    }
}

function trigger_link_watch(link_id,prepend_url){
	
	if($( "#"+link_id ).val().length>0){
		$( "#ph_"+link_id ).html('<a href="'+prepend_url+$( "#"+link_id ).val()+'" class="link-view" target="_blank">Test <i class="fa fa-external-link-square" aria-hidden="true"></i></a>');
    } else {
    	$( "#ph_"+link_id ).html('');
    }
	
	$( "#"+link_id ).bind('change keyup', function () {
		if($( "#"+link_id ).val().length>0){
			$( "#ph_"+link_id ).html('<a href="'+prepend_url+$( "#"+link_id ).val()+'" class="link-view" target="_blank">Test <i class="fa fa-external-link-square" aria-hidden="true"></i></a>');
        } else {
        	$( "#ph_"+link_id ).html('');
        }
	});
}


function update_account(){
	
	//Show spinner:
	$('.update_u_results').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
	
	$.post("/api_v1/account_update", {
		
		u_id:$('#u_id').val(),
		u_fname:$('#u_fname').val(),
		u_lname:$('#u_lname').val(),
		u_email:$('#u_email').val(),
		u_phone:$('#u_phone').val(),
		u_image_url:$('#u_image_url').val(),
		u_gender:$('#u_gender').val(),
		u_country_code:$('#u_country_code').val(),
		u_current_city:$('#u_current_city').val(),
		u_timezone:$('#u_timezone').val(),
		u_language:$('#u_language').val(),
		u_newly_checked:(document.getElementById('u_terms_agreement_time').checked ? '1' : '0'),
		
		u_bio:$('#u_bio').val(),
		
		u_password_current:$('#u_password_current').val(),
		u_password_new:$('#u_password_new').val(),
		
		u_website_url:$('#u_website_url').val(),
		u_linkedin_username:$('#u_linkedin_username').val(),
		u_github_username:$('#u_github_username').val(),
		u_twitter_username:$('#u_twitter_username').val(),
		u_youtube_username:$('#u_youtube_username').val(),
		u_fb_username:$('#u_fb_username').val(),
		u_instagram_username:$('#u_instagram_username').val(),
        u_skype_username:$('#u_skype_username').val(),
        u_paypal_email:$('#u_paypal_email').val(),
		/*
		u_quora_username:$('#u_quora_username').val(),
		u_stackoverflow_username:$('#u_stackoverflow_username').val(),
		u_medium_username:$('#u_medium_username').val(),
		u_dribbble_username:$('#u_dribbble_username').val(),
		*/
		u_calendly_username:$('#u_calendly_username').val(),
		
	} , function(data) {
		//Update UI to confirm with user:
		$('.update_u_results').html(data).hide().fadeIn();
		
		//Disapper in a while:
		setTimeout(function() {
			$('.update_u_results').fadeOut();
	    }, 10000);
    });
}

function insert_gravatar(){
	var gravatar_url = 'https://www.gravatar.com/avatar/<?= md5(trim(strtolower($udata['u_email']))) ?>';
	$('.profile-pic').attr('src',gravatar_url);
    $('#u_image_url').val(gravatar_url);
    alert('Gravatar URL for your email <?= $udata['u_email'] ?> was successfully inserted. Make sure to SAVE changes.');
}
</script>



<p style="float:right; margin-top:-75px;">
	<a href="/console" class="btn btn-sm btn-primary"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> &nbsp;My Bootcamps</span></a>
	<a href="/api_v1/logout" class="btn btn-sm btn-primary"><i class="fa fa-power-off" aria-hidden="true"></i><span> Logout</span></a>
</p>


<ul id="topnav" class="nav nav-pills nav-pills-primary">
  <li id="nav_profile" class="active"><a href="#profile" data-toggle="tab" onclick="update_hash('profile')"><i class="fa fa-id-card" aria-hidden="true"></i> Profile</a></li>
  <li id="nav_communication"><a href="#communication" data-toggle="tab" onclick="update_hash('communication')"><i class="fa fa-share-alt" aria-hidden="true"></i> Communication</a></li>
  <li id="nav_finance"><a href="#finance" data-toggle="tab" onclick="update_hash('finance')"><i class="fa fa-bar-chart" aria-hidden="true"></i> Finance</a></li>
  <li id="nav_password"><a href="#password" data-toggle="tab" onclick="update_hash('password')"><i class="fa fa-lock" aria-hidden="true"></i> Password</a></li>
</ul>



<div class="tab-content tab-space">

    <div class="tab-pane active" id="profile">
    	
    	<input type="hidden" id="u_id" value="<?= $udata['u_id'] ?>" />
    	
        <div class="title" style="margin-bottom:0; padding-bottom:0;"><h4><i class="fa fa-id-card" aria-hidden="true"></i> Name</h4></div>
        
        <div class="row" style="margin:0 0 0 0;">
        	<div class="col-xs-6" style="padding-left:0; padding-right:5px;">
            	<input type="text" required id="u_fname" value="<?= $udata['u_fname'] ?>" data-lpignore="true" placeholder="First Name" class="form-control border">
            </div>
            <div class="col-xs-6" style="padding-left:5px; padding-right:0;">
            	<input type="text" required id="u_lname" value="<?= $udata['u_lname'] ?>" data-lpignore="true" placeholder="Last Name" class="form-control border">
            </div>
        </div>
        
        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-picture-o" aria-hidden="true"></i> Picture</h4></div>
        <ul>
        	<li>Used as your instructor profile photo in your bootcamp landing pages.</li>
        	<li>Link to any URL that hosts your photo, starting with "https://"</li>
        	<?php if(strlen($udata['u_email'])>0){ ?>
        	<li>You may also <a href="javascript:insert_gravatar();"><u>Insert Your Gravatar URL</u></a> & then update it on <a href="https://en.gravatar.com/" target="_blank"><u>gravatar.com</u> <i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></a>.</li>
        	<?php } ?>
        </ul>
        <div class="row" style="margin:0 0 0 0;">
        	<div class="col-xs-2" style="padding-left:0; padding-right:5px;">
            	<img src="<?= ( strlen($udata['u_image_url'])>0 ? $udata['u_image_url'] : '/img/bp_128.png' ) ?>" class="profile-pic" />
            </div>
            <div class="col-xs-10" style="padding-left:5px; padding-right:0;">
            	<input type="url" required id="u_image_url" value="<?= $udata['u_image_url'] ?>" class="form-control border">
            </div>
        </div>
        
        
        
        <div style="display:none;">
        		<div class="title" style="margin-top:30px;"><h4><i class="fa fa-venus-mars" aria-hidden="true"></i> Gender</h4></div>
            <div class="form-group label-floating is-empty">
                <select id="u_gender" class="border">
                	<option value="">Choose...</option>
                	<?php
                	echo '<option value="m" '.($udata['u_gender']=='m'?'selected="selected"':'').'>Male</option>';
                	echo '<option value="f" '.($udata['u_gender']=='f'?'selected="selected"':'').'>Female</option>';
                	?>
                </select>
                <span class="material-input"></span>
            </div>
        </div>
    		
    		
		
		<div class="title" style="margin-top:30px;"><h4><i class="fa fa-commenting" aria-hidden="true"></i> Introductory Message</h4></div>
		<ul class="maxout">
			<li>Give the Mench community an overview of your professional background.</li>
			<li>Make sure to include your strong suits and tangible accomplishments.</li>
			<li>Your Introductory Message will be displayed on your bootcamp landing page.</li>
		</ul>
		<textarea class="form-control text-edit border msg" id="u_bio" style="height:100px;" onkeyup="changeBio()"><?= substr(trim(strip_tags($udata['u_bio'])),0,$message_max); ?></textarea>
        <div style="margin:0 0 10px 0; font-size:0.8em;"><span id="charNum">0</span>/<?= $message_max ?></div>
        
        
        
        
          <div class="title" style="margin-top:30px;"><h4><i class="fa fa-map-marker" aria-hidden="true"></i> Location</h4></div>
        <div class="form-group label-floating is-empty">
        	<select id="u_country_code" class="border" style="width:100%; margin-bottom:10px; max-width:260px;">
        		<option value="">Choose...</option>
            	<?php
            	$countries_all = $this->config->item('countries_all');
            	foreach($countries_all as $country_key=>$country_name){
            	    echo '<option value="'.$country_key.'" '.($udata['u_country_code']==$country_key?'selected="selected"':'').'>'.$country_name.'</option>';
            	}
            	?>
            </select>
        	<span class="material-input"></span>
        </div>
        <input type="text" required id="u_current_city" placeholder="Vancouver" style="max-width:260px;" data-lpignore="true" value="<?= $udata['u_current_city'] ?>" class="form-control border">


        
        
        
        <table width="100%" style="margin-top:30px;"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></span></td></tr></table>
    </div>
    
    
    <div class="tab-pane" id="password">
    	<div class="title"><h4><i class="fa fa-asterisk" aria-hidden="true"></i> Current Password</h4></div>
        <div class="form-group label-floating is-empty">
            <input type="password" id="u_password_current" style="max-width: 260px;" class="form-control border">
            <span class="material-input"></span>
        </div>
        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-asterisk" aria-hidden="true"></i> New Password</h4></div>
        <div class="form-group label-floating is-empty">
            <input type="password" id="u_password_new" style="max-width: 260px;" class="form-control border">
            <span class="material-input"></span>
        </div>
        
        <table width="100%" style="margin-top:30px;"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></span></td></tr></table>
    </div>
    
    
    <div class="tab-pane" id="communication">
    
    
     	<div class="title"><h4><i class="fa fa-language" aria-hidden="true"></i> Fluent Languages</h4></div>
        <p>Hold down Ctrl to select multiple:</p>
        <div class="form-group label-floating is-empty">
        	<select multiple id="u_language" style="height:150px;" class="border">
            	<?php
            	$all_languages = $this->config->item('languages');
            	$my_languages = explode(',',$udata['u_language']);
            	foreach($all_languages as $ln_key=>$ln_name){
            	    echo '<option value="'.$ln_key.'" '.(in_array($ln_key,$my_languages)?'selected="selected"':'').'>'.$ln_name.'</option>';
            	}
            	?>
            </select>
        	<span class="material-input"></span>
        </div>
        
        
		
        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-clock-o" aria-hidden="true"></i> Timezone</h4></div>
        <div class="form-group label-floating is-empty">
            <select id="u_timezone" class="border">
            	<option value="">Choose...</option>
            	<?php
            	$timezones = $this->config->item('timezones');
            	foreach($timezones as $tz_val=>$tz_name){
            	    echo '<option value="'.$tz_val.'" '.($udata['u_timezone']==$tz_val?'selected="selected"':'').'>'.$tz_name.'</option>';
            	}
            	?>
            </select>
            <span class="material-input"></span>
        </div>





        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-envelope" aria-hidden="true"></i> Email <i class="fa fa-eye-slash" aria-hidden="true" data-toggle="tooltip" title="Hidden from students"></i></h4></div>
        <div class="form-group label-floating is-empty">
            <input type="email" id="u_email" data-lpignore="true" style="max-width:260px;" value="<?= $udata['u_email'] ?>" class="form-control border">
            <span class="material-input"></span>
        </div>



        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-phone-square" aria-hidden="true"></i> Phone <i class="fa fa-eye-slash" aria-hidden="true" data-toggle="tooltip" title="Hidden from students"></i></h4></div>
        <div class="form-group label-floating is-empty">
            <input type="tel" maxlength="30" required id="u_phone" data-lpignore="true" style="max-width:260px;" value="<?= $udata['u_phone'] ?>" class="form-control border">
            <span class="material-input"></span>
        </div>
        
       
       
       
    	
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-chrome" aria-hidden="true"></i> Website <span id="ph_u_website_url"></span></h4></div>
        <p>Start with http:// or https://</p>
    	<input type="url" class="form-control border" id="u_website_url" data-lpignore="true" maxlength="255" value="<?= $udata['u_website_url'] ?>" />
        <script>trigger_link_watch('u_website_url','');</script>
        
        <?php
        $u_social_account = $this->config->item('u_social_account');
        foreach($u_social_account as $sa_key=>$sa){
            echo '<div class="title" style="margin-top:30px;"><h4>'.$sa['sa_icon'].' '.$sa['sa_name'].' <span id="ph_'.$sa_key.'"></span></h4></div>
    	<div class="input-group border">
          <span class="input-group-addon addon-lean">'.$sa['sa_prefix'].'</span><input type="text" data-lpignore="true" class="form-control social-input" id="'.$sa_key.'" maxlength="100" value="'.$udata[$sa_key].'" />
        </div>';
            echo '<script>trigger_link_watch("'.$sa_key.'","'.$sa['sa_prefix'].'");</script>';
        }
        ?>
        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-skype" aria-hidden="true"></i> Skype Username</h4></div>
    	<input type="text" class="form-control border" data-lpignore="true" id="u_skype_username" maxlength="100" value="<?= $udata['u_skype_username'] ?>" />
    	
    	<table width="100%" style="margin-top:30px;"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></span></td></tr></table>
    </div>
    
    
    
    
    
    
    
    
    
    
    <div class="tab-pane" id="finance" style="max-width:none !important;">




        <div class="title"><h4><i class="fa fa-history" aria-hidden="true"></i> Payout History</h4></div>
        	<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No payouts yet.</div>




        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-paypal" aria-hidden="true"></i> Paypal Email for Payouts <i class="fa fa-eye-slash" aria-hidden="true" data-toggle="tooltip" title="Hidden from students"></i></h4></div>
        <div class="form-group label-floating is-empty">
            <input type="email" id="u_paypal_email" data-lpignore="true" style="max-width:260px;" value="<?= $udata['u_paypal_email'] ?>" class="form-control border">
            <span class="material-input"></span>
        </div>






        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Instructor Agreement</h4></div>
        <ul>
        	<li>I have read and understood how <a href="https://support.mench.co/hc/en-us/articles/115002473111" target="_blank"><u>Instructor Earning & Payouts <i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></u></a> work.</li>
        	<li>I have read and understood my bootcamp's <a href="https://support.mench.co/hc/en-us/articles/115002080031" target="_blank"><u>Tuition Guarantee <i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
        	<li>I have read and understood the <a href="https://support.mench.co/hc/en-us/articles/115002096752" target="_blank"><u>Mench Code of Conduct <i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
        	<li>I have read and understood the <a href="https://support.mench.co/hc/en-us/articles/115002096732" target="_blank"><u>Mench Honor Code <i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
        	<li>I have read and agreed to Mench's <a href="/terms" target="_blank"><u>Terms of Service & Privacy Policy <i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
        </ul>
        <div class="form-group label-floating is-empty">
        	<div class="checkbox">
            	<label>
            		<?php $has_agreed = (isset($udata['u_terms_agreement_time']) && strlen($udata['u_terms_agreement_time'])>0); ?>
            		<?php if($has_agreed){ ?>
            		<input type="checkbox" id="u_terms_agreement_time" disabled checked /> Agreed on <b><?= time_format($udata['u_terms_agreement_time'],0) ?> PST</b>
            		<?php } else { ?>
            		<input type="checkbox" id="u_terms_agreement_time" /> I certify that all above statements are true
            		<?php } ?>
            	</label>
            </div>
        </div>


        <table width="100%" style="margin-top:30px;"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></span></td></tr></table>


    </div>
    
</div>