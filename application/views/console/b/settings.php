<script>
<?php
//What are the total permissions we need?
$required_fb_permissions = $this->config->item('required_fb_permissions');
$fb_settings = $this->config->item('fb_settings');

$permission_string = join_keys($required_fb_permissions);
echo 'var required_fb_permissions = '.json_encode($required_fb_permissions).';';
echo 'var total_permission_count = '.count($required_fb_permissions).';';
?>

function show_fb_auth(error_message=null){
    //Show the login button to fetch access:
    $('#page_list').addClass('hidden');
    $('#fb_login').removeClass('hidden');
    if(error_message){
        $('#login_message').html('<span style="color:#FF0000"><i class="fas fa-exclamation-triangle"></i> ERROR: '+error_message+'</span>');
        $('#why_permissions').addClass('hidden');
    } else {
        $('#login_message').html('<span style="color:#3C4858">to connect this Bootcamp to your Facebook Page</span>');
        $('#why_permissions').removeClass('hidden');
    }
}

function fp_refresh(fp_id){

    $('#simulate_'+fp_id).html('select_dates');

    $.post("/api_v1/fp_refresh", {

        b_id:$('#b_id').val(),
        fp_id:fp_id,

    }, function(data) {

        //Update UI to confirm with user:
        if(data.status){
            $('#simulate_'+fp_id).html(data.message);
        } else {
            alert('ERROR: '+data.message);

            $('#simulate_'+fp_id).html('<i class="fas fa-exclamation-triangle" data-toggle="tooltip" title="ERROR: '+data.message+'"></i>');

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
                            $.post("/api_v1/e_js_create", {
                                e_inbound_u_id:<?= $udata['u_id'] ?>,
                                e_b_id:$('#b_id').val(),
                                e_inbound_c_id:9, //User needs attention
                                e_text_value:"Coach has denied "+denied_permissions+" permission(s) and cannot load their Facebook Pages in Settings.",
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

                        $.post("/api_v1/fp_list", {

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

    //The 3 checkboxes in the Enrollment tab:
    $('#b_requires_assessment').change(function() {
        if (this.checked) {
            $('.enrollment_assessment').removeClass('hidden');
        } else {
            $('.enrollment_assessment').addClass('hidden');
        }
    });
    $('#b_offers_diy').change(function() {
        if (this.checked) {
            $('.diy_package_class').removeClass('hidden');
        } else {
            $('.diy_package_class').addClass('hidden');
        }
    });
    $('#b_offers_coaching').change(function() {
        if (this.checked) {
            $('#coaching_package_div').removeClass('hidden');
        } else {
            $('#coaching_package_div').addClass('hidden');
        }
    });


    //Category selector
    //TODO ENABLE as its Currently disabled...
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
        //Coach wants to change:
        confirm_action = 'Changing';
    } else if(current_b_fp_id>0 && new_b_fp_id==0){
        //Coach wants to disconnect:
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


function b_save_settings(){

    //Show spinner:
    $('.save_r_results').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();

    var modify_data = {

        b_id:$('#b_id').val(),

        b_weeks_count:$('#b_weeks_count').val(),
        b_unlock_intents:$('#b_unlock_intents').val(),
        b_status:$('#b_status').val(),
        b_url_key:$('#b_url_key').val(),
        b_post_enrollment_url_diy:$('#b_post_enrollment_url_diy').val(),
        b_requires_assessment:(document.getElementById('b_requires_assessment').checked ? '1' : '0'),
        b_assessment_url:$('#b_assessment_url').val(),
        b_assessment_minutes:$('#b_assessment_minutes').val(),
        b_fb_pixel_id:$('#b_fb_pixel_id').val(),
        level1_c_id:$('.level1').val(),
        level2_c_id:( $('.level1').val()>0 ? $('.outbound_c_'+$('.level1').val()).val() : 0),

        b_offers_diy:(document.getElementById('b_offers_diy').checked ? '1' : '0'),
        b_offers_coaching:(document.getElementById('b_offers_coaching').checked ? '1' : '0'),
        b_offers_deferred:(document.getElementById('b_offers_deferred').checked ? '1' : '0'),
        b_offers_job:(document.getElementById('b_offers_job').checked ? '1' : '0'),

        b_weekly_coaching_hours:$('#b_weekly_coaching_hours').val(),
        b_weekly_coaching_rate:$('#b_weekly_coaching_rate').val(),
        b_guarantee_weeks:$('#b_guarantee_weeks').val(),

    };

    //Save the rest of the content:
    $.post("/api_v1/b_save_settings", modify_data, function(data) {

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
    <li id="nav_general" class="active"><a href="#general"><i class="fas fa-cube"></i> Bootcamp</a></li>
    <li id="nav_enrollment"><a href="#enrollment"><i class="fas fa-user-plus"></i> Enrollment</a></li>
    <li id="nav_pages"><a href="#pages"><i class="fab fa-facebook-messenger"></i> Chatbot</a></li>
    <!-- <li id="nav_coupons"><a href="#coupons"><i class="fas fa-tags"></i> Coupons</a></li> -->
</ul>




<div class="tab-content tab-space">


    <div class="tab-pane active" id="tabgeneral">

        <div class="title" style="margin-top:20px;"><h4><i class="fas fa-sliders-h"></i> Status <span id="hb_627" class="help_button" intent-id="627"></span></h4></div>
        <div class="help_body maxout" id="content_627"></div>
        <?= echo_dropdown_status('b','b_status',$b['b_status'],( $udata['u_inbound_u_id']==1281 ? array() : array(3) )); ?>
        <div style="clear:both; margin:0; padding:0;"></div>



        <div class="title" style="margin-top:0px;"><h4><i class="fas fa-calendar-alt"></i> Class Duration <span id="hb_7101" class="help_button" intent-id="7101"></span></h4></div>
        <div class="help_body maxout" id="content_7101"></div>
        <?php
        echo '<select class="input-mini border" id="b_weeks_count" style="padding:4px !important;">';
        $b_weeks_count_options = $this->config->item('b_weeks_count_options');
        foreach($b_weeks_count_options as $num){
            echo '<option value="'.$num.'" '.( $b['b_weeks_count']==$num ? 'selected="selected"' : '' ).'>'.$num.' Week'.echo__s($num).'</option>';
        }
        echo '</select>';
        ?>





        <div class="title" style="margin-top:20px;"><h4><i class="fas fa-lock-open"></i> Intent Unlock Rate <span id="hb_7102" class="help_button" intent-id="7102"></span></h4></div>
        <div class="help_body maxout" id="content_7102"></div>
        <?php
        echo '<select class="input-mini border" id="b_unlock_intents" style="padding:4px !important;">';
        $b_unlock_intents_options = $this->config->item('b_unlock_intents_options');
        foreach($b_unlock_intents_options as $num){
            echo '<option value="'.$num.'" '.( $b['b_unlock_intents']==$num ? 'selected="selected"' : '' ).'>Unlock '.($num==0?'All':$num).' Intent'.echo__s($num).' Ahead</option>';
        }
        echo '</select>';
        ?>





        <div class="title" style="margin-top:20px;"><h4><i class="fas fa-usd-circle"></i> Offers a Job <span id="hb_7097" class="help_button" intent-id="7097"></h4></div>
        <div class="help_body maxout" id="content_7097"></div>
        <div class="form-group label-floating is-empty">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="b_offers_job" <?= ( $b['b_offers_job'] ? 'checked' : '') ?> /> Yes, This Bootcamp Results in Getting Hired
                </label>
            </div>
        </div>





        <div style="display:none; margin-bottom:20px;">
            <div class="title" style="margin-top:0;"><h4><i class="fas fa-hashtag"></i> Category <span id="hb_4869" class="help_button" intent-id="4869"></span></h4></div>
            <div class="help_body maxout" id="content_4869"></div>
            <div class="form-group label-floating">
                <?php
                $current_c_ids = array();
                $current_inbounds = $this->Db_model->cr_inbound_fetch(array(
                    'cr.cr_outbound_c_id' => $b['b_outbound_c_id'],
                    'cr.cr_status' => 1,
                ));
                foreach($current_inbounds as $c){
                    array_push($current_c_ids,$c['cr_inbound_c_id']);
                }
                //Show Menu
                echo tree_menu(4793,$current_c_ids,'select');
                ?>
            </div>
        </div>


        <div class="title" style="margin-top:20px;"><h4><i class="fas fa-cart-plus"></i> Landing Page URL <span id="hb_725" class="help_button" intent-id="725"></span></h4></div>
        <div class="help_body maxout" id="content_725"></div>
        <div class="form-group label-floating is-empty">
            <div class="input-group border" style="width:100%; max-width:380px;">
                <span class="input-group-addon addon-lean" style="color:#3C4858; font-weight: 300;">https://mench.com/</span>
                <input type="text" id="b_url_key" style="margin:0 0 0 -3px !important; font-size:16px !important; padding-left:0;" value="<?= $b['b_url_key'] ?>" maxlength="30" class="form-control" />
            </div>
        </div>



        <div class="title" style="margin-top:30px;"><h4><i class="fab fa-facebook"></i> Facebook Pixel Tracker <span id="hb_718" class="help_button" intent-id="718"></span></h4></div>
        <div class="help_body maxout" id="content_718"></div>
        <div class="input-group">
            <input type="number" min="0" step="1" style="width:380px; margin-bottom:-5px;" id="b_fb_pixel_id" placeholder="123456789012345" value="<?= (strlen($b['b_fb_pixel_id'])>0?$b['b_fb_pixel_id']:null) ?>" class="form-control border" />
        </div>





        <br />
        <table width="100%" style="margin-top:10px;"><tr><td class="save-td"><a href="javascript:b_save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_r_results"></span></td></tr></table>

    </div>




    <div class="tab-pane" id="tabenrollment">

        <div class="title" style="margin-top:25px;"><h4><i class="fas fa-tachometer"></i> Require Instant Assessment <span id="hb_6964" class="help_button" intent-id="6964"></span></h4></div>
        <div class="help_body maxout" id="content_6964"></div>
        <div class="form-group label-floating is-empty">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="b_requires_assessment" <?= ( $b['b_requires_assessment'] ? 'checked' : '') ?> /> Enable
                </label>
            </div>
        </div>
        <div class="inline-box enrollment_assessment <?= ( $b['b_requires_assessment'] ? '' : 'hidden') ?>">
            <div class="form-group label-floating is-empty">
                <div class="input-group border" style="width:420px; margin-bottom: 7px;">
                    <span class="input-group-addon addon-lean" style="color:#3C4858; font-weight: 300;">Assessment URL:</span>
                    <input type="URL" id="b_assessment_url" style="padding-left:0; padding-right:0;" placeholder="URL to load in iframe" value="<?= $b['b_assessment_url'] ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group label-floating is-empty" >
                <div class="input-group border" style="width:420px;">
                    <span class="input-group-addon addon-lean" style="color:#3C4858; font-weight: 300;">Countdown Timer:</span>
                    <input type="number" step="1" maxlength="3" style="padding-left:0; padding-right:0; text-align: center" id="b_assessment_minutes" value="<?= $b['b_assessment_minutes'] ?>" class="form-control">
                    <span class="input-group-addon addon-lean" style="color:#3C4858; font-weight: 300;">Minutes (0 = Disable Timer)</span>
                </div>
            </div>
        </div>





        <div class="title" style="margin-top:20px;"><h4><i class="fas fa-user"></i> Offer Do It Yourself for Free <span id="hb_4789" class="help_button" intent-id="4789"></h4></h4></div>
        <div class="help_body maxout" id="content_4789"></div>
        <div class="form-group label-floating is-empty">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="b_offers_diy" <?= ( $b['b_offers_diy'] ? 'checked' : '') ?> /> Enable
                </label>
            </div>
        </div>
        <div class="inline-box diy_package_class <?= ( $b['b_offers_diy'] ? '' : 'hidden') ?>">


            <div class="alert alert-info" style="margin:0; font-size:1.3em;">
                <p style="margin: 0 0 10px 0;"><i class="fas fa-info-circle"></i> Here is what happens when you enable the Free Do It Yourself package:</p>
                <ul style="list-style: decimal;">
                    <li><a href="/console/<?= $b['b_id'] ?>/actionplan">Action Plan</a> becomes public using the <i class="fab fa-creative-commons"></i> <a href="https://creativecommons.org/" target="_blank" style="display: inline-block">Creative Commons <i class="fas fa-external-link-square"></i></a> license.</li>
                    <li>Anyone will be allowed to enroll for free without being offered coaching.</li>
                    <li>Coaches can access all enrolled student profiles and message students.</li>
                    <!--<li>Will soon be published in an open-source intent directory that can help students learn faster.</li>-->
                </ul>
            </div>

            <div class="title" style="margin-top:25px;"><h4><i class="fas fa-link"></i> Post-Enrollment URL <span id="hb_4867" class="help_button" intent-id="4867"></span></h4></div>
            <div class="help_body maxout" id="content_4867"></div>
            <div class="input-group">
                <input type="URL" id="b_post_enrollment_url_diy" style="width:380px;" value="<?= $b['b_post_enrollment_url_diy'] ?>" class="form-control border" />
            </div>

        </div>




        <div class="title" style="margin-top:25px;"><h4><i class="fas fa-user-friends"></i> Offer 1-on-1 Coaching <span id="hb_4791" class="help_button" intent-id="4791"></h4></div>
        <div class="help_body maxout" id="content_4791"></div>
        <div class="form-group label-floating is-empty">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="b_offers_coaching" <?= ( $b['b_offers_coaching'] ? 'checked' : '') ?> /> Enable
                </label>
            </div>
        </div>

        <div id="coaching_package_div" class="inline-box <?= ( $b['b_offers_coaching'] ? '' : 'hidden') ?>">


            <div class="title" style="margin-top:0px;"><h4><i class="fas fa-box-usd"></i> Hours & Rate</div>
            <div class="form-group label-floating is-empty" style="margin-bottom:7px;">
                <div class="input-group border" style="width:366px;">
                    <span class="input-group-addon addon-lean" style="color:#3C4858; font-weight: 300;">Coach each student for ~</span>
                    <input type="number" step="0.1" style="padding-left:0; padding-right:0;" id="b_weekly_coaching_hours" value="<?= $b['b_weekly_coaching_hours'] ?>" class="form-control">
                    <span class="input-group-addon addon-lean" style="color:#3C4858; font-weight: 300;">hours per week</span>
                </div>
            </div>
            <div class="form-group label-floating is-empty">
                <div class="input-group border" style="width:366px;">
                    <span class="input-group-addon addon-lean" style="color:#3C4858; font-weight: 300;">With a tuition rate of $</span>
                    <input style="padding-left:0; padding-right:0;" type="number" step="0.1" id="b_weekly_coaching_rate" value="<?= $b['b_weekly_coaching_rate'] ?>" class="form-control">
                    <span class="input-group-addon addon-lean" style="color:#3C4858; font-weight: 300;">USD per week</span>
                </div>
            </div>




            <div class="title" style="margin-top:25px;"><h4><i class="fas fa-user-plus"></i> Coaching Team <span id="hb_629" class="help_button" intent-id="629"></h4></div>
            <div class="help_body maxout" id="content_629"></div>
            <div class="list-group maxout" style="margin: 0; padding: 0;">
                <?php
                $admin_ids = array();
                foreach($b['b__coaches'] as $admin){
                    echo echo_br($admin);
                    array_push($admin_ids,$admin['u_id']);
                }
                ?>
                <li class="list-group-item"><i class="fab fa-facebook-messenger"></i> &nbsp;&nbsp;Contact us to add new coaches to your team.</li>
            </div>



            <div class="title" style="margin-top:25px;"><h4><i class="fas fa-smile"></i> Tuition Guarantee Timeframe <span id="hb_2585" class="help_button" intent-id="2585"></h4></div>
            <div class="help_body maxout" id="content_2585"></div>
            <p>By when are students guaranteed to [<?= $b['c_outcome'] ?>]?</p>
            <?= echo_dropdown_status('b_guarantee_weeks','b_guarantee_weeks',$b['b_guarantee_weeks']); ?>






            <div style="margin-top:25px;">
                <div class="title" style="margin-top:20px;"><h4><i class="fas fa-credit-card"></i> Offer Deferred Payments <span id="hb_7019" class="help_button" intent-id="7019"></h4></div>
                <div class="help_body maxout" id="content_7019"></div>
                <div class="form-group label-floating is-empty">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="b_offers_deferred" <?= ( $b['b_offers_deferred'] ? 'checked' : '') ?> /> Enable [Must Offer a Job]
                            <?php
                            if($b['b_offers_deferred']){
                                echo '<div class="alert alert-info" style="margin:10px 0 0 0; font-size:1.3em;">
            <p style="margin: 0 0 10px 0;"><i class="fas fa-info-circle"></i> Students can choose the deferred payment plan and pay '.($b['b_deferred_rate']*100).'% of the normal tuition rate from '.($b['b_deferred_payback']*100).'% of each net-paycheck (after they get a job) with a '.($b['b_deferred_deposit']*100).'% up-front & non-refundable payment.</div>';
                            }
                            ?>
                        </label>
                    </div>
                </div>
            </div>


        </div>







        <table width="100%" style="margin-top:25px;"><tr><td class="save-td"><a href="javascript:b_save_settings();" class="btn btn-primary">Save</a></td><td><span class="save_r_results"></span></td></tr></table>

    </div>

    <div class="tab-pane" id="tabpages">

        <?php echo_tip(3531); ?>
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



    <div class="tab-pane" id="tabcoupons">
        <div class="alert alert-info maxout" role="alert"><i class="fas fa-exclamation-triangle"></i> Pending development; Scheduled for March 2018 🎉​</div>
    </div>

</div>

