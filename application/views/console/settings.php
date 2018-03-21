<script>
<?php
//What are the total permissions we need?
$required_fb_permissions = $this->config->item('required_fb_permissions');
$fb_settings = $this->config->item('fb_settings');
$pm = $this->config->item('pricing_model');

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
        $('#why_permissions').addClass('hidden');
    } else {
        $('#login_message').html('<span style="color:#000">to connect this Bootcamp to your Facebook Page</span>');
        $('#why_permissions').removeClass('hidden');
    }
}

function refresh_integration(fp_id){

    $('#simulate_'+fp_id).html('<img src="/img/round_load.gif" class="loader" />');

    $.post("/api_v1/refresh_integration", {

        b_id:$('#b_id').val(),
        fp_id:fp_id,

    }, function(data) {

        //Update UI to confirm with user:
        if(data.status){
            $('#simulate_'+fp_id).html(data.message);
        } else {
            alert('ERROR: '+data.message);

            $('#simulate_'+fp_id).html('<i class="fa fa-exclamation-triangle" aria-hidden="true" data-toggle="tooltip" title="ERROR: '+data.message+'"></i>');

            //Load ToolTip:
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

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

                            });

                        } else {
                            //Just missing, nothing has been denied:
                            show_fb_auth('We are missing '+missing_permissions+' permission(s). Try again.');
                        }

                    } else {

                        //All good, we have all the permissions we need
                        //loadup the Facebook pages for this user:
                        $('#fb_login').addClass('hidden');
                        $('#page_list').html('<img src="/img/round_load.gif" class="loader" />').removeClass('hidden');

                        $.post("/api_v1/list_facebook_pages", {

                            b_id:$('#b_id').val(),
                            login_response:login_response,

                        }, function(data) {

                            //Update UI to confirm with user:
                            $('#page_list').html(data);

                            //Load ToolTip:
                            $('[data-toggle="tooltip"]').tooltip();

                        });

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

    //Go through Level 2 menus to see if any are selected, if so, select the Level 1 too:
    $( ".level2" ).each(function() {
        //Make sure this is NOT the dummy drag in box
        if ($(this).val()>0) {
            //Show the parent of this:
            $( ".level1" ).val($(this).attr('data-c-id'));
            $( "#c_s_"+$(this).attr('data-c-id') ).removeClass('hidden');
            return false;
        }
    });


    $('.c_select.level1').on('change', function() {

        //Hide all children:
        $('.c_select.level2').addClass('hidden');
        //This id?
        var this_id = $(this).attr('data-c-id');
        var level_1_c_id = this.value;

        //Show if selected:
        if(level_1_c_id>0){
            $('#c_s_'+level_1_c_id).removeClass('hidden');
        }

    });



    if(window.location.hash) {
        focus_hash(window.location.hash);
    }


    //Watch for Group Support Change:
    $('#b_p2_max_seats').on('change', function() {
        if(this.value==0){
            $('#support_settings').hide();
        } else {
            $('#support_settings').fadeIn();
        }
    });

    $('#b_p3_rate').on('change', function() {
        if(this.value==0){
            $('#mentorship_settings').hide();
        } else {
            $('#mentorship_settings').fadeIn();
        }
    });



    window.fbAsyncInit = function() {
        FB.init({
            autoLogAppEvents : true,
            xfbml            : true,
            appId            : '<?= $fb_settings['app_id'] ?>',
            version          : '<?= $fb_settings['default_graph_version'] ?>'
        });
        loadFacebookPages(1);
    };

});


function fb_connect(current_b_fp_id,new_b_fp_id){

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

    //Re-load the page:
    $('#fb_login').addClass('hidden');
    $('#page_list').html('<img src="/img/round_load.gif" class="loader" />').removeClass('hidden');

    $.post("/api_v1/fb_connect", {

        b_id:$('#b_id').val(),
        current_b_fp_id:current_b_fp_id,
        new_b_fp_id:new_b_fp_id,

    }, function(data) {

        if(!data.status){
            //Ooops, something went wrong, show error:
            alert('ERROR: '+data.message);
        }

        //Reload page listing:
        loadFacebookPages(0);

    });

}


function save_settings(){

    //Show spinner:
    $('.save_r_results').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();

    var modify_data = {
        b_id:$('#b_id').val(),
        b_url_key:$('#b_url_key').val(),
        b_status:$('#b_status').val(),
        b_fb_pixel_id:$('#b_fb_pixel_id').val(),
        b_p1_rate:$('#b_p1_rate').val(),
        b_p2_max_seats:$('#b_p2_max_seats').val(),
        b_p2_rate:$('#b_p2_rate').val(),
        b_p3_rate:$('#b_p3_rate').val(),
        b_support_email:$('#b_support_email').val(),
        b_calendly_url:$('#b_calendly_url').val(),
        b_difficulty_level:$('#b_difficulty_level').val(),
        level1_c_id:$('.level1').val(),
        level2_c_id:( $('.level1').val()>0 ? $('.outbound_c_'+$('.level1').val()).val() : 0),
        b_thankyou_url:$('#b_thankyou_url').val(),
    };

    //Save the rest of the content:
    $.post("/api_v1/save_settings", modify_data, function(data) {

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


<input type="hidden" id="b_id" value="<?= $b['b_id'] ?>" />


<ul id="topnav" class="nav nav-pills nav-pills-primary">
    <li id="nav_support" class="active"><a href="#support"><i class="fa fa-life-ring" aria-hidden="true"></i> Support</a></li>
    <li id="nav_landingpage"><a href="#landingpage"><i class="fa fa-bullhorn" aria-hidden="true"></i> Landing Page</a></li>
    <li id="nav_pages"><a href="#pages"><i class="fa fa-facebook-official" aria-hidden="true"></i> Pages</a></li>
    <li id="nav_team"><a href="#team"><i class="fa fa-user-plus" aria-hidden="true"></i> Team</a></li>
    <!-- <li id="nav_coupons"><a href="#coupons"><i class="fa fa-tags" aria-hidden="true"></i> Coupons</a></li> -->
</ul>


<div class="tab-content tab-space">

    <div class="tab-pane active" id="tabsupport">


        <div class="title" style="margin-top:20px;"><h4><i class="fa fa-wrench" aria-hidden="true"></i> Do It Yourself Package <span id="hb_4789" class="help_button" intent-id="4789"></span></h4></div>
        <div class="help_body maxout" id="content_4789"></div>
        <div class="form-group label-floating <?= (count($pm['p1_rates'])<=1 ? 'hidden' : '') ?>">
            <select id="b_p1_rate" class="border" style="width:100%; margin-bottom:10px; max-width:380px;">
                <?php
                foreach($pm['p1_rates'] as $option){
                    echo '<option value="'.$option.'" '.($b['b_p1_rate']==$option?'selected="selected"':'').'>'.( $option==0 ? 'Free' : '$'.$option.' per Student per Week' ).'</option>';
                }
                ?>
            </select>
        </div>
        <?php if(count($pm['p1_rates'])==1){ ?>
            <div>Universal Price: $<?= $pm['p1_rates'][0] ?>/Week/Student</div>
        <?php } ?>




        <div class="title" style="margin-top:25px;"><h4><i class="fa fa-life-ring" aria-hidden="true"></i> Classroom Package <span id="hb_4791" class="help_button" intent-id="4791"></span></h4></div>
        <div class="help_body maxout" id="content_4791"></div>
        <?php if(count($pm['p2_rates'])==1){ ?>
            <div style="margin-bottom: 5px;">Universal Price: $<?= $pm['p2_rates'][0] ?>/Week/Student</div>
        <?php } ?>
        <div class="form-group label-floating">
            <select id="b_p2_max_seats" class="border" style="width:100%; margin-bottom:10px; max-width:380px;">
                <?php
                foreach($pm['p2_max_seats'] as $option){
                    echo '<option value="'.$option.'" '.($b['b_p2_max_seats']==$option?'selected="selected"':'').'>'.( $option==0 ? 'Do Not Offer Guidance' : $option.' Students per Week Max' ).'</option>';
                }
                ?>
            </select>
        </div>


        <div id="support_settings" style="display:<?= ( $b['b_p2_max_seats']==0 ? 'none' : 'block' ) ?>;">

            <!-- Disabled for now as we only have a single pricing option for Guidance Package -->
            <div class="form-group label-floating <?= (count($pm['p2_rates'])<=1 ? 'hidden' : '') ?>">
                <select id="b_p2_rate" class="border" style="width:100%; margin-bottom:10px; max-width:380px;">
                    <?php
                    foreach($pm['p2_rates'] as $option){
                        echo '<option value="'.$option.'" '.($b['b_p2_rate']==$option?'selected="selected"':'').'>$'.$option.'/Week</option>';
                    }
                    ?>
                </select>
            </div>


            <div class="title" style="margin-top:20px;"><h4><i class="fa fa-handshake-o" aria-hidden="true"></i> Mentorship Package <span id="hb_615" class="help_button" intent-id="615"></span></h4></div>
            <div class="help_body maxout" id="content_615"></div>
            <div class="form-group label-floating">
                <select id="b_p3_rate" class="border" style="width:100%; margin-bottom:10px; max-width:380px;">
                    <?php
                    foreach($pm['p3_rates'] as $option){
                        echo '<option value="'.$option.'" '.($b['b_p3_rate']==$option?'selected="selected"':'').'>'.($option==0?'Do Not Offer Mentorship':'$'.number_format($option,2).'/Min ($'.number_format(($option*25),0).' for each 25-Min Session)').'</option>';
                    }
                    ?>
                </select>
            </div>


            <div style="padding-left:30px;">
                <div class="title" style="margin-top:20px;"><h4><i class="fa fa-envelope" aria-hidden="true"></i> Forwarding Email Address <span id="hb_4790" class="help_button" intent-id="4790"></span></h4></div>
                <div class="help_body maxout" id="content_4790"></div>
                <div class="form-group label-floating is-empty">
                    <input type="email" id="b_support_email" data-lpignore="true" style="width:320px;" placeholder="yoursupportemail@gmail.com" value="<?= $b['b_support_email'] ?>" class="form-control border">
                    <span class="material-input"></span>
                </div>



                <div id="mentorship_settings" style="display:<?= ( $b['b_p3_rate']==0 ? 'none' : 'block' ) ?>;">
                    <div class="title" style="margin-top:20px;"><h4><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Calendly Booking URL <span id="hb_4792" class="help_button" intent-id="4792"></span></h4></div>
                    <div class="help_body maxout" id="content_4792"></div>
                    <div class="form-group label-floating is-empty">
                        <input type="url" id="b_calendly_url" style="width:320px;" placeholder="https://calendly.com/shervine/demo" value="<?= $b['b_calendly_url'] ?>" class="form-control border">
                        <span class="material-input"></span>
                    </div>
                </div>
            </div>

        </div>


        <br />
        <table width="100%" style="margin-top:10px;"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_r_results"></span></td></tr></table>

    </div>

    <div class="tab-pane" id="tablandingpage">


        <div class="title" style="margin-top:20px;"><h4><i class="fa fa-eye" aria-hidden="true"></i> Bootcamp Status <span id="hb_627" class="help_button" intent-id="627"></span></h4></div>
        <div class="help_body maxout" id="content_627"></div>
        <?= echo_status_dropdown('b','b_status',$b['b_status'],( $udata['u_status']>=3 ? array() : array(3) )); ?>
        <div style="clear:both; margin:0; padding:0;"></div>


        <div class="title" style="margin-top:0;"><h4><i class="fa fa-hashtag" aria-hidden="true"></i> Category <span id="hb_4869" class="help_button" intent-id="4869"></span></h4></div>
        <div class="help_body maxout" id="content_4869"></div>
        <div class="form-group label-floating">
            <?php
            $current_c_ids = array();
            $current_inbounds = $this->Db_model->cr_inbound_fetch(array(
                'cr.cr_outbound_id' => $b['b_c_id'],
                'cr.cr_status' => 1,
            ));
            foreach($current_inbounds as $c){
                array_push($current_c_ids,$c['cr_inbound_id']);
            }
            //Show Menu
            echo tree_menu(4793,$current_c_ids,'select');
            ?>
        </div>

        <div class="title" style="margin-top:20px;"><h4><i class="fa fa-thermometer-half" aria-hidden="true"></i> Student Experience Level <span id="hb_4868" class="help_button" intent-id="4868"></span></h4></div>
        <div class="help_body maxout" id="content_4868"></div>
        <div class="form-group label-floating is-empty">
            <select class="border c_select" id="b_difficulty_level" style="width:100%; margin-bottom:10px; max-width:380px;">
                <?php
                echo '<option value="">Choose...</option>';
                $df_statuses = status_bible('df');
                foreach($df_statuses as $status_id=>$status){
                    echo '<option value="'.$status_id.'" '.( $b['b_difficulty_level']==$status_id ? 'selected="selected"' : '' ).'>'.$status['s_name'].'</option>';
                }
                ?>
            </select>
        </div>


        <div class="title" style="margin-top:15px;"><h4><i class="fa fa-link" aria-hidden="true"></i> Landing Page URL <span id="hb_725" class="help_button" intent-id="725"></span></h4></div>
        <div class="help_body maxout" id="content_725"></div>
        <div class="form-group label-floating is-empty">
            <div class="input-group border" style="width:100%; max-width:380px;">
                <span class="input-group-addon addon-lean" style="color:#222; font-weight: 300;">https://mench.com/</span>
                <input type="text" id="b_url_key" style="margin:0 0 0 -3px !important; font-size:16px !important; padding-left:0;" value="<?= $b['b_url_key'] ?>" maxlength="30" class="form-control" />
            </div>
        </div>



        <div class="title" style="margin-top:20px;"><h4><i class="fa fa-link" aria-hidden="true"></i> Thank You Redirect URL <span id="hb_4867" class="help_button" intent-id="4867"></span></h4></div>
        <div class="help_body maxout" id="content_4867"></div>
        <div class="input-group">
            <input type="URL" id="b_thankyou_url" style="width:380px;" value="<?= $b['b_thankyou_url'] ?>" class="form-control border" />
        </div>


        <div class="title" style="margin-top:20px;"><h4><i class="fa fa-facebook-official" aria-hidden="true"></i> Facebook Pixel Tracker <span id="hb_718" class="help_button" intent-id="718"></span></h4></div>
        <div class="help_body maxout" id="content_718"></div>
        <div class="input-group">
            <input type="number" min="0" step="1" style="width:380px; margin-bottom:-5px;" id="b_fb_pixel_id" placeholder="123456789012345" value="<?= (strlen($b['b_fb_pixel_id'])>0?$b['b_fb_pixel_id']:null) ?>" class="form-control border" />
        </div>



        <br />
        <table width="100%" style="margin-top:10px;"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_r_results"></span></td></tr></table>

    </div>

    <div class="tab-pane" id="tabpages">

        <?php itip(3531); ?>
        <div id="page_list"><img src="/img/round_load.gif" class="loader" /></div>
        <div id="fb_login" class="hidden">
            <fb:login-button scope="<?= $permission_string ?>" onlogin="loadFacebookPages(0);"></fb:login-button>
            <span id="login_message"></span>
            <div id="why_permissions" class="hidden">
                <p style="margin-top:15px;">We need to be granted these <?= count($required_fb_permissions) ?> permissions to automate your messages:</p>
                <ul style="list-style: decimal;">
                <?php
                foreach($required_fb_permissions as $key=>$desc){
                    echo '<li><b>'.ucwords(str_replace('_',' ',$key)).':</b> '.$desc.'</li>';
                }
                ?>
                </ul>
            </div>
        </div>
        <br />

    </div>

    <div class="tab-pane" id="tabteam">

        <?php itip(629); ?>
        <div class="list-group maxout">
            <?php
            $admin_ids = array();
            foreach($b['b__admins'] as $admin){
                echo echo_br($admin);
                array_push($admin_ids,$admin['u_id']);
            }
            $mench_support_team = $this->config->item('mench_support_team');
            //Fetch the profile of the hard-coded mench advisery team
            //Currently: Miguel & Shervin
            $mench_support_team = $this->Db_model->u_fetch(array(
                'u_id IN ('.join(',',$mench_support_team).')' => null,
            ));

            foreach($mench_support_team as $adviser){
                if(in_array($adviser['u_id'],$admin_ids)){
                    continue;
                }
                echo echo_br(array_merge($adviser,array(
                    'ba_id' => 0,
                    'ba_u_id' => $adviser['u_id'],
                    'ba_status' => 1, //Advisery status
                    'ba_b_id' => $b['b_id'],
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

