<?php
$uses = $this->session->userdata('user');
$ufetch = $this->Db_model->users_fetch(array(
    'u_id' => $uses['u_id'],
));
if(!(count($ufetch)==1)){
    redirect_message('/marketplace','Session expired.');
}
$udata = $ufetch[0];
?>


<h1>My Account</h1>

<ul class="nav nav-pills nav-pills-primary" style="margin-top:10px;">
  <li class="active"><a href="#pill1" data-toggle="tab"><i class="fa fa-id-card" aria-hidden="true"></i> Overview</a></li>
  <li><a href="#pill2" data-toggle="tab"><i class="fa fa-lock" aria-hidden="true"></i> Password</a></li>
  <li><a href="#pill3" data-toggle="tab"><i class="fa fa-link" aria-hidden="true"></i> Social Links</a></li>
  <li><a href="#pill4" data-toggle="tab"><i class="fa fa-university" aria-hidden="true"></i> Wire Payments</a></li>
</ul>


<div class="tab-content tab-space">

    <div class="tab-pane active" id="pill1" style="max-width:500px;">
    	
    	<input type="hidden" id="u_id" value="<?= $udata['u_id'] ?>" />
    	
        <div class="title"><h4>Full Name</h4></div>
        <div class="col-xs-6" style="padding-left:0; padding-right:5px;">
        	<input type="text" required id="u_fname" value="<?= $udata['u_fname'] ?>" placeholder="First Name" class="form-control">
        </div>
        <div class="col-xs-6" style="padding-left:5px; padding-right:0;">
        	<input type="text" required id="u_lname" value="<?= $udata['u_lname'] ?>" placeholder="Last Name" class="form-control">
        </div>
        
        
        <div class="title"><h4>Email</h4></div>
        <div class="form-group label-floating is-empty">
            <input type="email" required id="u_email" value="<?= $udata['u_email'] ?>" class="form-control">
            <span class="material-input"></span>
        </div>
        
        
        <div class="title"><h4>Profile Picture URL</h4></div>
        <div class="col-xs-2" style="padding-left:0; padding-right:5px;">
        	<img src="<?= $udata['u_image_url'] ?>" class="profile-pic" />
        </div>
        <div class="col-xs-10" style="padding-left:5px; padding-right:0;">
        	<input type="url" required id="u_image_url" value="<?= $udata['u_image_url'] ?>" class="form-control">
        </div>
        
        
        <div class="title"><h4>Gender</h4></div>
        <div class="form-group label-floating is-empty">
            <select id="u_gender">
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
            	<select id="u_country_code" style="margin-top:9px;">
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
        	<input type="text" required id="u_current_city" placeholder="Los Angeles, CA" value="<?= $udata['u_current_city'] ?>" class="form-control">
        </div>
        
        
        
        <div class="title"><h4>Timezone</h4></div>
        <div class="form-group label-floating is-empty">
            <select id="u_timezone">
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
        <div class="form-group label-floating is-empty">
        	<select multiple id="u_language" style="height:150px;">
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
        
        
        <div class="title"><h4>Tangible Accomplishments <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Provide a list of your top 3-7 professional accomplishments as the first thing that would be displayed on your profile. You are encouraged to use actual numbers and metrics."></i> <span style="font-size:0.6em; color:#AAA;">(<a href="/guides/showdown_markup" target="_blank">Markup Support <i class="fa fa-info-circle"></i></a>)</span></h4></div>
		<div class="form-group label-floating is-empty">
		    <textarea class="form-control text-edit" rows="2" id="u_tangible_experience"><?= $udata['u_tangible_experience'] ?></textarea>
		    <span class="material-input"></span>
		</div>
		
		
		<div class="title"><h4>Biography <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Would be displayer below your tangible accomplishments on your profile."></i> <span style="font-size:0.6em; color:#AAA;">(<a href="/guides/showdown_markup" target="_blank">Markup Support <i class="fa fa-info-circle"></i></a>)</span></h4></div>
		<div class="form-group label-floating is-empty">
		    <textarea class="form-control text-edit" rows="2" id="u_bio"><?= $udata['u_bio'] ?></textarea>
		    <span class="material-input"></span>
		</div>
        
        
        
        <table width="100%"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></td></tr></table>
    </div>
    
    
    <div class="tab-pane" id="pill2" style="max-width:500px;">
    	<div class="title"><h4>Current Password</h4></div>
        <div class="form-group label-floating is-empty">
            <input type="password" id="u_password_current" class="form-control">
            <span class="material-input"></span>
        </div>
        
        <div class="title"><h4>New Password</h4></div>
        <div class="form-group label-floating is-empty">
            <input type="password" id="u_password_new" class="form-control">
            <span class="material-input"></span>
        </div>
        
        <table width="100%"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></td></tr></table>
    </div>
    
    
    <div class="tab-pane" id="pill3" style="max-width:500px;">
    	
    	<p>Link social accounts you wish to share on your bootcamp page to allow student to learn more about you.</p>
        <div class="title"><h4><i class="fa fa-chrome" aria-hidden="true"></i> Your Website <span id="ph_u_website_url"></span></h4></div>
        <p>Start with http:// or https://</p>
    	<input type="url" class="form-control" id="u_website_url" maxlength="255" value="<?= $udata['u_website_url'] ?>" />
        <script>trigger_link_watch('u_website_url','');</script>
        <?php
        $u_social_account = $this->config->item('u_social_account');
        foreach($u_social_account as $sa_key=>$sa){
            echo '<div class="title"><h4>'.$sa['sa_icon'].' '.$sa['sa_name'].' Username <span id="ph_'.$sa_key.'"></span></h4></div>
    	<div class="input-group">
          <span class="input-group-addon addon-lean">https://'.$sa['sa_prefix'].'</span>
          <input type="text" class="form-control" id="'.$sa_key.'" maxlength="100" value="'.$udata[$sa_key].'" />
        </div>';
            echo '<script>trigger_link_watch("'.$sa_key.'","https://'.$sa['sa_prefix'].'");</script>';
        }
        ?>
        
        
        
        <table width="100%"><tr><td class="save-td"><a href="javascript:update_account();" class="btn btn-primary">Save</a></td><td><span class="update_u_results"></td></tr></table>
    </div>
    
    <div class="tab-pane" id="pill4" style="max-width:500px;">
    	<p><b>Coming soon.</b></p>
    	<?php
    	$countries_stripe_support = $this->config->item('countries_stripe_support');
    	echo '<p>Setup your bank account to receive direct deposits for each cohort payments. We will support the following '.count($countries_stripe_support).' countries:</p>';
    	echo '<p>';
    	foreach($countries_stripe_support as $c_key=>$c_name){
    	    echo '<img src="/img/flags/'.strtolower($c_key).'.png" class="flag" /> '.$c_name.'<br />';
    	}
    	echo '</p>';
    	?>
    </div>
    
</div>
