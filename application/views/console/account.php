<script>

function trigger_link_watch(link_id,prepend_url){
	
	if($( "#"+link_id ).val().length>0){
		$( "#ph_"+link_id ).html('<a href="'+prepend_url+$( "#"+link_id ).val()+'" class="link-view" target="_blank">Test <i class="fa fa-external-link" aria-hidden="true"></i></a>');
    } else {
    	$( "#ph_"+link_id ).html('');
    }
	
	$( "#"+link_id ).bind('change keyup', function () {
		if($( "#"+link_id ).val().length>0){
			$( "#ph_"+link_id ).html('<a href="'+prepend_url+$( "#"+link_id ).val()+'" class="link-view" target="_blank">Test <i class="fa fa-external-link" aria-hidden="true"></i></a>');
        } else {
        	$( "#ph_"+link_id ).html('');
        }
	});
}

function update_account(){
	
	if(!$('#u_fname').val().length){
		alert('Missing first name.');
		return false;
	} else if(!$('#u_lname').val().length){
		alert('Missing last name.');
		return false;
	} else if(!$('#u_email').val().length){
		alert('Missing email.');
		return false;
	} else if(!$('#u_image_url').val().length){
		alert('Missing profile picture url.');
		return false;
	} else if(!$('#u_gender').val().length){
		alert('Missing gender.');
		return false;
	} else if(!$('#u_country_code').val().length){
		alert('Missing country.');
		return false;
	} else if(!$('#u_timezone').val().length){
		alert('Missing time zone.');
		return false;
	} else if(!$('#u_language').val().length){
		alert('Missing language.');
		return false;
	}
	
	//Show spinner:
	$('.update_u_results').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
	
	$.post("/process/account_update", {
		
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

		u_bio:( u_bio_quill.getLength()>1 ? $('#u_bio .ql-editor').html() : "" ),
		
		u_password_current:$('#u_password_current').val(),
		u_password_new:$('#u_password_new').val(),
		
		u_website_url:$('#u_website_url').val(),
		u_linkedin_username:$('#u_linkedin_username').val(),
		u_github_username:$('#u_github_username').val(),
		u_twitter_username:$('#u_twitter_username').val(),
		u_youtube_username:$('#u_youtube_username').val(),
		u_fb_username:$('#u_fb_username').val(),
		u_instagram_username:$('#u_instagram_username').val(),
		u_quora_username:$('#u_quora_username').val(),
		u_stackoverflow_username:$('#u_stackoverflow_username').val(),
		u_skype_username:$('#u_skype_username').val(),
		u_medium_username:$('#u_medium_username').val(),
		u_dribbble_username:$('#u_dribbble_username').val(),
		
	} , function(data) {
		//Update UI to confirm with user:
		$('.update_u_results').html(data).hide().fadeIn();
		
		//Disapper in a while:
		setTimeout(function() {
			$('.update_u_results').fadeOut();
	    }, 10000);
    });
}
</script>



<?php
$uses = $this->session->userdata('user');
$ufetch = $this->Db_model->u_fetch(array(
    'u_id' => $uses['u_id'],
));
if(!(count($ufetch)==1)){
    redirect_message('/console','Session expired.');
}
$udata = $ufetch[0];
?>

<ul class="nav nav-pills nav-pills-primary">
  <li class="active"><a href="#pill1" data-toggle="tab"><i class="fa fa-id-card" aria-hidden="true"></i> Profile</a></li>
  <li><a href="#pill2" data-toggle="tab"><i class="fa fa-lock" aria-hidden="true"></i> Password</a></li>
  <li><a href="#pill3" data-toggle="tab"><i class="fa fa-comments" aria-hidden="true"></i> Social</a></li>
  <!-- <li><a href="#pill4" data-toggle="tab"><i class="fa fa-university" aria-hidden="true"></i> Banking</a></li> -->
</ul>



<div class="tab-content tab-space">

    <div class="tab-pane active" id="pill1">
    	
    	<input type="hidden" id="u_id" value="<?= $udata['u_id'] ?>" />
    	
        <div class="title"><h4>Full Name</h4></div>
        <div class="col-xs-6" style="padding-left:0; padding-right:5px;">
        	<input type="text" required id="u_fname" value="<?= $udata['u_fname'] ?>" placeholder="First Name" class="form-control border">
        </div>
        <div class="col-xs-6" style="padding-left:5px; padding-right:0;">
        	<input type="text" required id="u_lname" value="<?= $udata['u_lname'] ?>" placeholder="Last Name" class="form-control border">
        </div>
        
        
        
        <div class="col-xs-6" style="padding-left:0; padding-right:5px;">
        	<div class="title"><h4>Email <i class="fa fa-eye-slash" aria-hidden="true" data-toggle="tooltip" title="Hidden from students"></i></h4></div>
            <div class="form-group label-floating is-empty">
                <input type="email" required id="u_email" value="<?= $udata['u_email'] ?>" class="form-control border">
                <span class="material-input"></span>
            </div>
        </div>
        <div class="col-xs-6" style="padding-left:5px; padding-right:0;">
        	<div class="title"><h4>Phone <i class="fa fa-eye-slash" aria-hidden="true" data-toggle="tooltip" title="Hidden from students"></i></h4></div>
            <div class="form-group label-floating is-empty">
                <input type="tel" maxlength="30" required id="u_phone" value="<?= $udata['u_phone'] ?>" class="form-control border">
                <span class="material-input"></span>
            </div>
        </div>            
        
        
        
        
        <div class="title"><h4>Profile Picture URL</h4></div>
        <div class="col-xs-2" style="padding-left:0; padding-right:5px;">
        	<img src="<?= $udata['u_image_url'] ?>" class="profile-pic" />
        </div>
        <div class="col-xs-10" style="padding-left:5px; padding-right:0;">
        	<input type="url" required id="u_image_url" value="<?= $udata['u_image_url'] ?>" class="form-control border">
        </div>
        
        
        <div class="title"><h4>Gender</h4></div>
        <div class="form-group label-floating is-empty">
            <select id="u_gender" class="border">
            	<?php
            	echo '<option value="m" '.($udata['u_gender']=='m'?'selected="selected"':'').'>Male</option>';
            	echo '<option value="f" '.($udata['u_gender']=='f'?'selected="selected"':'').'>Female</option>';
            	?>
            </select>
            <span class="material-input"></span>
        </div>
        
        
        <div class="title"><h4>Country, City, State</h4></div>
        <div class="col-md-6" style="padding-left:0; padding-right:5px;">
        	<div class="form-group label-floating is-empty">
            	<select id="u_country_code" class="border">
                	<?php
                	$countries_all = $this->config->item('countries_all');
                	foreach($countries_all as $c_key=>$c_name){
                	    echo '<option value="'.$c_key.'" '.($udata['u_country_code']==$c_key?'selected="selected"':'').'>'.$c_name.'</option>';
                	}
                	?>
                </select>
            	<span class="material-input"></span>
            </div>
        </div>
        <div class="col-md-6" style="padding-left:5px; padding-right:0;">
        	<input type="text" required id="u_current_city" placeholder="Los Angeles, CA" value="<?= $udata['u_current_city'] ?>" class="form-control border">
        </div>
        
        
        
        <div class="title"><h4>Timezone</h4></div>
        <div class="form-group label-floating is-empty">
            <select id="u_timezone" class="border">
            	<?php
            	$timezones = $this->config->item('timezones');
            	foreach($timezones as $tz_val=>$tz_name){
            	    echo '<option value="'.$tz_val.'" '.($udata['u_timezone']==$tz_val?'selected="selected"':'').'>'.$tz_name.'</option>';
            	}
            	?>
            </select>
            <span class="material-input"></span>
        </div>
        
        
        <div class="title"><h4>Fluent Languages</h4></div>
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
		
		
		<div class="title"><h4>Biography</h4></div>
		<ul class="maxout">
			<li>Give students a background of your self and your tangible accomplishments.</li>
			<li>Will be displayed on yor instrutor profile.</li>
		</ul>
		<div id="u_bio"><?= $udata['u_bio'] ?></div>
        <script> var u_bio_quill = new Quill('#u_bio', setting_full); </script>
    </div>
    
    
    <div class="tab-pane" id="pill2">
    	<div class="title"><h4>Current Password</h4></div>
        <div class="form-group label-floating is-empty">
            <input type="password" id="u_password_current" class="form-control border">
            <span class="material-input"></span>
        </div>
        
        <div class="title"><h4>New Password</h4></div>
        <div class="form-group label-floating is-empty">
            <input type="password" id="u_password_new" class="form-control border">
            <span class="material-input"></span>
        </div>
        
    </div>
    
    
    <div class="tab-pane" id="pill3">
    	
    	<p>Link social accounts you wish to share on your bootcamp page to allow student to learn more about you.</p>
    	
    	
        <div class="title"><h4><i class="fa fa-chrome" aria-hidden="true"></i> Your Website <span id="ph_u_website_url"></span></h4></div>
        <p>Start with http:// or https://</p>
    	<input type="url" class="form-control border" id="u_website_url" maxlength="255" value="<?= $udata['u_website_url'] ?>" />
        <script>trigger_link_watch('u_website_url','');</script>
        
        <?php
        $u_social_account = $this->config->item('u_social_account');
        foreach($u_social_account as $sa_key=>$sa){
            echo '<div class="title"><h4>'.$sa['sa_icon'].' '.$sa['sa_name'].' URL <span id="ph_'.$sa_key.'"></span></h4></div>
    	<div class="input-group border">
          <span class="input-group-addon addon-lean">'.$sa['sa_prefix'].'</span><input type="text" class="form-control social-input" id="'.$sa_key.'" maxlength="100" value="'.$udata[$sa_key].'" />
        </div>';
            echo '<script>trigger_link_watch("'.$sa_key.'","'.$sa['sa_prefix'].'");</script>';
        }
        ?>
        
        <div class="title"><h4><i class="fa fa-skype" aria-hidden="true"></i> Skype Username</h4></div>
    	<input type="text" class="form-control border" id="u_skype_username" maxlength="100" value="<?= $udata['u_skype_username'] ?>" />
    </div>
    
    <div class="tab-pane" id="pill4">
    	<p><b>Coming soon.</b></p>
    	<p>Setup your bank account to receive direct deposits for each cohort payments via Paypal.</p>
    </div>
    
</div>

<br />
<table width="100%"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></span></td></tr></table>