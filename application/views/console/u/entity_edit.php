<?php
$message_max = $this->config->item('message_max');
$udata = $this->session->userdata('user');
?>
<script>

$(document).ready(function() {
	//Detect any possible hashes that controll the menu?
	if(window.location.hash) {
		focus_hash(window.location.hash);
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
		$( "#ph_"+link_id ).html('<a href="'+prepend_url+$( "#"+link_id ).val()+'" class="link-view" target="_blank">Test <i class="fas fa-external-link-square"></i></a>');
    } else {
    	$( "#ph_"+link_id ).html('');
    }
	
	$( "#"+link_id ).bind('change keyup', function () {
		if($( "#"+link_id ).val().length>0){
			$( "#ph_"+link_id ).html('<a href="'+prepend_url+$( "#"+link_id ).val()+'" class="link-view" target="_blank">Test <i class="fas fa-external-link-square"></i></a>');
        } else {
        	$( "#ph_"+link_id ).html('');
        }
	});
}


function update_account(){
	
	//Show spinner:
	$('.update_u_results').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
	
	$.post("/entities/entity_save_edit", {
		
		u_id:<?= $entity['u_id'] ?>,
		u_full_name:$('#u_full_name').val(),
		u_email:$('#u_email').val(),
		u_phone:$('#u_phone').val(),
		u_image_url:$('#u_image_url').val(),
		u_gender:$('#u_gender').val(),
		u_country_code:$('#u_country_code').val(),
		u_current_city:$('#u_current_city').val(),
		u_timezone:$('#u_timezone').val(),
		u_language:$('#u_language').val(),
        u_paypal_email:$('#u_paypal_email').val(),
        u_newly_checked:(document.getElementById('u_terms_agreement_time').checked ? '1' : '0'),
		
		u_bio:$('#u_bio').val(),
		
		u_password_current:$('#u_password_current').val(),
		u_password_new:$('#u_password_new').val(),

        //Social accounts:
        u_fb_username:$('#u_fb_username').val(),
		u_primary_url:$('#u_primary_url').val(),
		u_linkedin_username:$('#u_linkedin_username').val(),
		u_github_username:$('#u_github_username').val(),
		u_twitter_username:$('#u_twitter_username').val(),
		u_youtube_username:$('#u_youtube_username').val(),
		u_instagram_username:$('#u_instagram_username').val(),
        u_skype_username:$('#u_skype_username').val(),
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
	var gravatar_url = 'https://www.gravatar.com/avatar/<?= md5(trim(strtolower($entity['u_email']))) ?>';
	$('.profile-pic').attr('src',gravatar_url);
    $('#u_image_url').val(gravatar_url);
    alert('Gravatar URL for your email <?= $entity['u_email'] ?> was successfully inserted. Make sure to SAVE changes.');
}
</script>






<p style="float:right; margin-top:-75px;">
	<a href="/logout" class="btn btn-sm btn-primary"><i class="fas fa-power-off"></i><span> Logout</span></a>
</p>


<ul id="topnav" class="nav nav-pills nav-pills-primary">
  <li id="nav_profile" class="active"><a href="#profile"><i class="fas fa-user-circle"></i> Profile</a></li>
  <li id="nav_communication"><a href="#communication"><i class="fab fa-twitter"></i> Social Links</a></li>
  <li id="nav_details"><a href="#details"><i class="fas fa-cog"></i> Details</a></li>
  <li id="nav_password" style="<?= ( in_array($entity['u_inbound_u_id'], array(1280,1323,1279,1307,1281,1308,1304)) ? '' : 'display:none;' ) ?>"><a href="#password"><i class="fas fa-lock"></i> Password</a></li>
</ul>




<div class="tab-content tab-space">

    <div class="tab-pane active" id="tabprofile">
        
        <div class="row" style="margin:0 0 0 0;">
            <div class="col-xs-6" style="margin-top:0; padding-left:0; padding-right:5px;">
                <div class="title" style="margin-bottom:0; padding-bottom:0;"><h4><i class="fas fa-id-card"></i> Full Name</h4></div>
                <input type="text" required id="u_full_name" value="<?= $entity['u_full_name'] ?>" data-lpignore="true" placeholder="Full Name" class="form-control border">
            </div>
            <div class="col-xs-6" style="margin-top:0; padding:0; display:<?= ( in_array($entity['u_inbound_u_id'], array(1280,1323,1279,1307,1281,1308,1304,1282)) ? 'block' : 'none' ) ?>;">
                <div class="title" style="margin-top:5px;"><h4><i class="fas fa-envelope"></i> Primary Email <i class="fas fa-eye-slash" data-toggle="tooltip" title="Will NOT be published publicly"></i></h4></div>
                <input type="email" id="u_email" data-lpignore="true" value="<?= $entity['u_email'] ?>" class="form-control border">
            </div>
        </div>



        <div class="title" style="margin-top:20px;"><h4><?= (strlen($entity['u_primary_url'])>0 && strlen($entity['u_url_last_check'])>0 ? status_bible('u_url_type_id', $entity['u_url_type_id'],1, 'right') : '<i class="fas fa-link"></i>' ) ?> Primary URL
                <?php
                if(strlen($entity['u_primary_url'])>0 && strlen($entity['u_url_last_check'])>0){
                    //We have checked this before, lets show the results:
                    echo '&nbsp;<a href="'.$entity['u_clean_url'].'" target="_blank">';
                    if($entity['u_url_is_broken']==1){
                        //The previous URL was detected broken:
                        echo '<i class="fas fa-times-hexagon" data-toggle="tooltip" data-placement="right" style="color:#FF0000;" title="URL detected broken on '.time_format($entity['u_url_last_check'],0).'"></i>';
                    } else {
                        echo '<i class="fas fa-check-circle" data-toggle="tooltip" data-placement="right" title="HTTP code ['.$entity['u_url_http_code'].'] when we last checked on '.time_format($entity['u_url_last_check'],0).'"></i>';
                    }
                    echo '</a>';
                }
                ?>
                <span id="ph_u_primary_url"></span></h4></div>
        <input type="url" class="form-control border" id="u_primary_url" data-lpignore="true" maxlength="255" value="<?= $entity['u_primary_url'] ?>" />
        <script>trigger_link_watch('u_primary_url','');</script>



        <div style="display: block;">
            <div class="title" style="margin-top:20px;"><h4><i class="fas fa-image"></i>  HTTPS Picture URL
                    <?php if(strlen($entity['u_image_url'])>0){ ?>
                    <img src="<?= ( strlen($entity['u_image_url'])>0 ? $entity['u_image_url'] : '/img/bp_128.png' ) ?>" style="width: 24px; height:24px; margin-left:0;" class="profile-pic" />
                    <?php } ?>
                </h4></div>
            <?php if(strlen($entity['u_email'])>0 && strlen($entity['u_image_url'])<1){ ?>
                <p>You may also <a href="javascript:insert_gravatar();"><u>Insert Your Gravatar URL</u></a> & then update it on <a href="https://en.gravatar.com/" target="_blank"><u>gravatar.com</u></a> <i class="fas fa-external-link-square" style="font-size: 0.8em;"></i></p>
            <?php } ?>
            <div class="row" style="margin:0 0 0 0;">
                <input type="url" required id="u_image_url" value="<?= $entity['u_image_url'] ?>" class="form-control border">
            </div>
        </div>


        <div class="title" style="margin-top:20px;"><h4><i class="fas fa-comment-dots"></i> Summary</h4></div>
        <textarea class="form-control text-edit border msg" id="u_bio" style="height:100px;" onkeyup="changeBio()"><?= substr(trim(strip_tags($entity['u_bio'])),0,$message_max); ?></textarea>
        <div style="margin:0 0 10px 0; font-size:0.8em;"><span id="charNum">0</span>/<?= $message_max ?></div>


        
        
        
        <table width="100%" style="margin-top:30px;"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></span></td></tr></table>
    </div>


    <div class="tab-pane" id="tabdetails">


        <div class="title" style="margin-top:0px;"><h4><i class="fas fa-language"></i> Languages</h4></div>
        <p>Hold down Ctrl to select multiple:</p>
        <div class="form-group label-floating is-empty">
            <select multiple id="u_language" style="height:150px;" class="border">
                <?php
                $all_languages = $this->config->item('languages');
                $my_languages = explode(',',$entity['u_language']);
                foreach($all_languages as $ln_key=>$ln_name){
                    echo '<option value="'.$ln_key.'" '.(in_array($ln_key,$my_languages)?'selected="selected"':'').'>'.$ln_name.'</option>';
                }
                ?>
            </select>
            <span class="material-input"></span>
        </div>




        <div class="title" style="margin-top:20px;"><h4><i class="fas fa-map"></i> Timezone</h4></div>
        <div class="form-group label-floating is-empty">
            <select id="u_timezone" class="border">
                <option value="">Choose...</option>
                <?php
                $timezones = $this->config->item('timezones');
                foreach($timezones as $tz_val=>$tz_name){
                    echo '<option value="'.$tz_val.'" '.($entity['u_timezone']==$tz_val?'selected="selected"':'').'>'.$tz_name.'</option>';
                }
                ?>
            </select>
            <span class="material-input"></span>
        </div>





        <div class="title" style="margin-top:20px;"><h4><i class="fas fa-phone-square"></i> Phone <i class="fas fa-eye-slash" data-toggle="tooltip" title="Will NOT be published publicly"></i></h4></div>
        <div class="form-group label-floating is-empty">
            <input type="tel" maxlength="30" required id="u_phone" data-lpignore="true" style="max-width:260px;" value="<?= $entity['u_phone'] ?>" class="form-control border">
            <span class="material-input"></span>
        </div>




        <div class="title" style="margin-top:20px;"><h4><i class="fas fa-map-marker"></i> Location</h4></div>
        <div class="form-group label-floating is-empty">
            <select id="u_country_code" class="border" style="width:100%; margin-bottom:10px; max-width:260px;">
                <option value="">Choose...</option>
                <?php
                $countries_all = $this->config->item('countries_all');
                foreach($countries_all as $country_key=>$country_name){
                    echo '<option value="'.$country_key.'" '.($entity['u_country_code']==$country_key?'selected="selected"':'').'>'.$country_name.'</option>';
                }
                ?>
            </select>
            <span class="material-input"></span>
        </div>
        <input type="text" required id="u_current_city" placeholder="Vancouver" style="max-width:260px;" data-lpignore="true" value="<?= $entity['u_current_city'] ?>" class="form-control border">






        <div>
            <div class="title" style="margin-top:20px;"><h4><i class="fas fa-venus-mars"></i> Gender</h4></div>
            <div class="form-group label-floating is-empty">
                <select id="u_gender" class="border">
                    <option value="">Neither</option>
                    <?php
                    echo '<option value="m" '.($entity['u_gender']=='m'?'selected="selected"':'').'>Male</option>';
                    echo '<option value="f" '.($entity['u_gender']=='f'?'selected="selected"':'').'>Female</option>';
                    ?>
                </select>
                <span class="material-input"></span>
            </div>
        </div>




        <div style="display:<?= (in_array($entity['u_inbound_u_id'],array(1308,1280,1281)) ? 'block' : 'none') ?>;">

            <div class="title" style="margin-top:15px;"><h4><i class="fab fa-paypal"></i> Paypal Payout Email</h4></div>
            <div class="form-group label-floating is-empty">
                <input type="email" id="u_paypal_email" data-lpignore="true" style="max-width:260px;" value="<?= $entity['u_paypal_email'] ?>" class="form-control border">
                <span class="material-input"></span>
            </div>


            <div>
                <div class="title" style="margin-top:20px;"><h4><i class="fas fa-badge-check"></i> Instructor Agreement</h4></div>
                <ul>
                    <li>I have read and understood how <a href="https://support.mench.com/hc/en-us/articles/115002473111" target="_blank"><u>Instructor Earning & Payouts <i class="fas fa-external-link-square" style="font-size: 0.8em;"></i></u></a> work.</li>
                    <li>I have read and understood the <a href="https://support.mench.com/hc/en-us/articles/115002096752" target="_blank"><u>Mench Code of Conduct <i class="fas fa-external-link-square" style="font-size: 0.8em;"></i></u></a>.</li>
                    <li>I have read and understood the <a href="https://support.mench.com/hc/en-us/articles/115002096732" target="_blank"><u>Mench Honor Code <i class="fas fa-external-link-square" style="font-size: 0.8em;"></i></u></a>.</li>
                    <li>I have read and agreed to Mench's <a href="/terms" target="_blank"><u>Terms of Service & Privacy Policy <i class="fas fa-external-link-square" style="font-size: 0.8em;"></i></u></a>.</li>
                </ul>
                <div class="form-group label-floating is-empty">
                    <div class="checkbox">
                        <label>
                            <?php $has_agreed = (isset($entity['u_terms_agreement_time']) && strlen($entity['u_terms_agreement_time'])>0); ?>
                            <?php if($has_agreed){ ?>
                                <input type="checkbox" id="u_terms_agreement_time" disabled checked /> Agreed on <b><?= time_format($entity['u_terms_agreement_time'],0) ?> PST</b>
                            <?php } else { ?>
                                <input type="checkbox" id="u_terms_agreement_time" <?= ( $udata['u_id']==$entity['u_id'] ? '' : 'disabled') ?> /> I certify that all above statements are true <?= ( $udata['u_id']==$entity['u_id'] ? '' : '<i class="fas fa-lock" data-toggle="tooltip" data-placement="left" title="Only owner can mark this as done​"></i>') ?>
                            <?php } ?>
                        </label>
                    </div>
                </div>
            </div>

        </div>




        <table width="100%" style="margin-top:30px;"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></span></td></tr></table>

    </div>


    <div class="tab-pane" id="tabcommunication">

        <?php
        //Social links:
        $u_social_account = $this->config->item('u_social_account');
        foreach($u_social_account as $sa_key=>$sa){
            echo '<div class="title" style="margin-top:'.( $sa_key>0 ? 30 : 0 ).'px;"><h4>'.$sa['sa_icon'].' '.$sa['sa_name'].' <span id="ph_'.$sa_key.'"></span></h4></div>
    	<div class="input-group border">
          <span class="input-group-addon addon-lean">'.$sa['sa_prefix'].'</span><input type="text" data-lpignore="true" class="form-control social-input" id="'.$sa_key.'" maxlength="100" value="'.$entity[$sa_key].'" />
        </div>';
            echo '<script>trigger_link_watch("'.$sa_key.'","'.$sa['sa_prefix'].'");</script>';
        }
        ?>
        
        <div class="title" style="margin-top:10px;"><h4><i class="fab fa-skype"></i> Skype Username</h4></div>
    	<input type="text" class="form-control border" data-lpignore="true" id="u_skype_username" maxlength="100" value="<?= $entity['u_skype_username'] ?>" />
    	
    	<table width="100%" style="margin-top:30px;"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></span></td></tr></table>
    </div>



    <div class="tab-pane" id="tabpassword">

        <div style="display:<?= ( strlen($entity['u_password'])>0 && !($udata['u_inbound_u_id']==1281) ? 'block' : 'none' ) ?>;">
            <div class="title"><h4><i class="fas fa-asterisk"></i> Current Password</h4></div>
            <div class="form-group label-floating is-empty">
                <input type="password" id="u_password_current" style="max-width: 260px;" class="form-control border">
                <span class="material-input"></span>
            </div>
        </div>

        <div class="title" style="margin-top:30px;"><h4><i class="fas fa-asterisk"></i> Set New Password</h4></div>
        <div class="form-group label-floating is-empty">
            <input type="password" id="u_password_new" style="max-width: 260px;" autocomplete="off" class="form-control border">
            <span class="material-input"></span>
        </div>

        <table width="100%" style="margin-top:30px;"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></span></td></tr></table>
    </div>

</div>