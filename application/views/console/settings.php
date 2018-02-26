<script>
<?php
//What are the total permissions we need?
$required_fb_permissions = $this->config->item('required_fb_permissions');
$permission_string = join_keys($required_fb_permissions);
echo 'var required_fb_permissions = '.json_encode($required_fb_permissions).';';
echo 'var total_permission_count = '.count($required_fb_permissions).';';
?>

function show_fb_auth(error_message=null){
    //Show the login button to fetch access:
    $('#page_list').addClass('hidden');
    $('#fb_login').removeClass('hidden');
    if(error_message){
        $('#login_message').html('<span style="color:#FF0000"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ERROR: '+error_message+'</span>');
    } else {
        $('#login_message').html('<span style="color:#000">to connect your Facebook Page</span>');
    }
}

function loadFacebookPages(is_onstart){
    FB.getLoginStatus(function(login_response) {
        if(login_response.status=='connected'){
            //Check permissions to make sure we have all that we need:
            FB.api(
                '/me/permissions',
                'GET',
                {},
                function(response) {
                    var granted_permissions = 0;
                    var denied_permissions = 0;
                    jQuery.each( response.data, function( i, val ) {
                        if((val.permission in required_fb_permissions)) {
                            if(val.status=='granted'){
                                //yes, increase the counter
                                granted_permissions++;
                            } else if(val.status=='declined') {
                                //ooopsy
                                denied_permissions++;
                            }
                        }
                    });

                    var missing_permissions = total_permission_count - granted_permissions;
                    if(missing_permissions){

                        //Did they deny anything?
                        if(denied_permissions>0){

                            show_fb_auth('You have denied '+denied_permissions+' permission(s). Try again.');

                            //Let admin know that some permissions had been denied:
                            $.post("/api_v1/log_engagement", {
                                e_initiator_u_id:<?= $udata['u_id'] ?>,
                                e_b_id:$('#b_id').val(),
                                e_type_id:9, //User needs attention
                                e_message:"Instructor has denied "+denied_permissions+" permission(s) and cannot load their Facebook Pages in Settings.",
                                e_json:response,
                                e_hash_time:"<?= time() ?>",
                                e_hash_code:"<?= md5(time().'hashcod3') ?>",
                            }, function(data) {
                                console.log(data);
                            });

                        } else {
                            //Just missing, nothing has been denied:
                            show_fb_auth('We are missing '+missing_permissions+' permission(s). Try again.');
                        }

                    } else {

                        //All good, we have all the permissions we need
                        //loadup the Facebook pages for this user:
                        load_fp(login_response,0,-1);

                    }
                }
            );

        } else {
            //Loadup the initial Facebook button:
            show_fb_auth();
        }
    });
}

$(document).ready(function() {

    if(window.location.hash) {
        focus_hash(window.location.hash);
    }

    window.fbAsyncInit = function() {
        FB.init({
            appId            : '1782431902047009',
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v2.10'
        });
        loadFacebookPages(1);
    };

});

function load_fp(login_response,current_b_fp_id,new_b_fp_id){

    //DO we need to show warning?
    var confirm_action = null;
    if(current_b_fp_id>0 && new_b_fp_id>0){
        //Instructor wants to change:
        confirm_action = 'Changing';
    } else if(current_b_fp_id>0 && new_b_fp_id==0){
        //Instructor wants to disconnect:
        confirm_action = 'Removing';
    }

    if(confirm_action){
        var did_confirm = confirm('WARNING: '+confirm_action+' a connected Facebook Page will impact existing students. NOT RECOMMENDED if you have a running class! Continue?');
        if (!did_confirm) {
            return false;
        }
    }


    //Show loader:
    $('#fb_login').addClass('hidden');
    $('#page_list').html('<img src="/img/round_load.gif" class="loader" />').removeClass('hidden');

    $.post("/api_v1/load_facebook_pages", {
        b_id:$('#b_id').val(),
        login_response:login_response,
        b_fp_id:new_b_fp_id,
    }, function(data) {

        //Update UI to confirm with user:
        $('#page_list').html(data);

        //Load ToolTip:
        $('[data-toggle="tooltip"]').tooltip();

    });

}

function save_settings(){

    //Show spinner:
    $('.save_r_results').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();

    var modify_data = {
        b_id:$('#b_id').val(),
        b_url_key:$('#b_url_key').val(),
        b_status:$('#b_status').val(),
    };

    //Save the rest of the content:
    $.post("/api_v1/save_settings", modify_data, function(data) {

        console.log(data);

        if(data.status){

            //Update UI to confirm with user:
            $('.save_r_results').html(data.message).hide().fadeIn();

            //URL Update:
            $(".landing_page_url").attr("href", "/"+modify_data['b_url_key']);

            //Hide message
            setTimeout(function() {
                $('.save_r_results').hide();
            }, 2500);

        } else {
            //Update UI to confirm with user:
            $('.save_r_results').html('<span style="color:#FF0000;">ERROR: '+data.message+'</span>').hide().fadeIn();
        }

    });
}




</script>


<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />



<ul id="topnav" class="nav nav-pills nav-pills-primary">
    <li id="nav_general" class="active"><a href="#general"><i class="fa fa-cog" aria-hidden="true"></i> General</a></li>
    <?php if($udata['u_status']==3){ ?>
    <li id="nav_pages"><a href="#pages"><i class="fa fa-facebook-official" aria-hidden="true"></i> Pages</a></li>
    <?php } ?>
    <li id="nav_team"><a href="#team"><i class="fa fa-user-plus" aria-hidden="true"></i> Team</a></li>
    <li id="nav_coupons"><a href="#coupons"><i class="fa fa-tags" aria-hidden="true"></i> Coupons</a></li>
</ul>


<div class="tab-content tab-space">

    <div class="tab-pane active" id="tabgeneral">

        <div>
            <div class="title" style="margin-top:15px;"><h4><i class="fa fa-circle" aria-hidden="true"></i> Bootcamp Status <span id="hb_627" class="help_button" intent-id="627"></span></h4></div>
            <div class="help_body maxout" id="content_627"></div>
            <?= echo_status_dropdown('b','b_status',$bootcamp['b_status']); ?>
            <div style="clear:both; margin:0; padding:0;"></div>
        </div>


        <div style="margin-top:15px;">
            <div class="title"><h4><i class="fa fa-link" aria-hidden="true"></i> Landing Page URL <span id="hb_725" class="help_button" intent-id="725"></span></h4></div>
            <div class="help_body maxout" id="content_725"></div>
            <div class="form-group label-floating is-empty">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean" style="color:#222; font-weight: 300;">https://mench.co/</span>
                    <input type="text" id="b_url_key" style="margin:0 !important; font-size:18px !important; padding-left:0;" value="<?= $bootcamp['b_url_key'] ?>" maxlength="30" class="form-control" />
                </div>
            </div>
        </div>

        <table width="100%" style="margin-top:20px;"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_r_results"></span></td></tr></table>

    </div>

    <div class="tab-pane" id="tabpages">

        <?php itip(3531); ?>
        <div id="page_list"><img src="/img/round_load.gif" class="loader" /></div>
        <div id="fb_login" class="hidden"><fb:login-button scope="<?= $permission_string ?>" onlogin="loadFacebookPages(0);"></fb:login-button> <span id="login_message"></span></div>
        <br />

    </div>

    <div class="tab-pane" id="tabteam">

        <?php itip(629); ?>
        <div class="list-group maxout">
            <?php
            $admin_ids = array();
            foreach($bootcamp['b__admins'] as $admin){
                echo echo_br($admin);
                array_push($admin_ids,$admin['u_id']);
            }
            $mench_advisers = $this->config->item('mench_advisers');
            //Fetch the profile of the hard-coded mench advisery team
            //Currently: Miguel & Shervin
            $mench_advisers = $this->Db_model->u_fetch(array(
                'u_id IN ('.join(',',$mench_advisers).')' => null,
            ));

            foreach($mench_advisers as $adviser){
                if(in_array($adviser['u_id'],$admin_ids)){
                    continue;
                }
                echo echo_br(array_merge($adviser,array(
                    'ba_id' => 0,
                    'ba_u_id' => $adviser['u_id'],
                    'ba_status' => 1, //Advisery status
                    'ba_b_id' => $bootcamp['b_id'],
                    'ba_team_display' => 'f', //Advisers are not shown on the landing page
                )));
            }
            ?>
        </div>
        <p>Contact us to add new team members.</p>
    </div>

    <div class="tab-pane" id="tabcoupons">
        <div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Pending development; Scheduled for March 2018 🎉​</div>
    </div>

</div>

