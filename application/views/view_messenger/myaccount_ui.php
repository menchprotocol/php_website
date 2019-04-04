
<script src="/js/custom/messenger-myaccount.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

    <?php
    //Full Name
    $acc_en_id = 0;
    echo '<div class="panel panel-default">
            <div class="panel-heading" role="tab" id="openEn'.$acc_en_id.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$acc_en_id.'" aria-expanded="true" aria-controls="collapse'.$acc_en_id.'">
                        <i class="fal fa-id-badge"></i> Full Name
                    </a>
                </h4>
            </div>
            <div id="collapse'.$acc_en_id.'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="openEn'.$acc_en_id.'">
                <div class="panel-body entity-list">
                    <p>Your first and last name:</p>
                    <input type="text" id="en_name_url" class="form-control border" value="'.$session_en['en_name'].'" style="display: inline-block;" />
                    <a href="javascript:void(0)" onclick="save_full_name()" class="btn btn-sm btn-secondary">Save</a>
                </div>
            </div>
        </div>';


    //Email Address:
    $acc_en_id = 1;
    echo '<div class="panel panel-default">
            <div class="panel-heading" role="tab" id="openEn'.$acc_en_id.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$acc_en_id.'" aria-expanded="true" aria-controls="collapse'.$acc_en_id.'">
                        <i class="fal fa-envelope"></i> Login Email
                    </a>
                </h4>
            </div>
            <div id="collapse'.$acc_en_id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="openEn'.$acc_en_id.'">
                <div class="panel-body entity-list">
                    <p>The email address used to login to your Action Plan on mench.com:</p>
                    <input type="email" id="en_email" class="form-control border" placeholder="you@gmail.com" style="display: inline-block;" />
                    <a href="javascript:void(0)" onclick="save_email()" class="btn btn-sm btn-secondary">Save</a>
                </div>
            </div>
        </div>';


    //Portal Password
    $current_pass_trs = $this->Database_model->tr_fetch(array(
        'tr_status' => 2,
        'tr_type_entity_id' => 4255, //Text
        'tr_parent_entity_id' => 3286, //Password
        'tr_child_entity_id' => $session_en['en_id'], //For this student
    ));
    $acc_en_id = 2;
    echo '<div class="panel panel-default">
            <div class="panel-heading" role="tab" id="openEn'.$acc_en_id.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$acc_en_id.'" aria-expanded="true" aria-controls="collapse'.$acc_en_id.'">
                        <i class="fal fa-lock-open"></i> Login Password
                    </a>
                </h4>
            </div>
            <div id="collapse'.$acc_en_id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="openEn'.$acc_en_id.'">
                <div class="panel-body entity-list">
                    <p>The password used to login to your Action Plan on mench.com:</p>
                    <input type="password" id="en_password" class="form-control border" placeholder="Set new password..." style="display: inline-block;" />
                    <a href="javascript:void(0)" onclick="save_password()" class="btn btn-sm btn-secondary">Save</a>
                    <p>Note: '. ( count($current_pass_trs) > 0 ? 'Password updated '.echo_time_difference(strtotime($current_pass_trs[0]['tr_timestamp'])).' ago.' : 'You have not yet set a password.') .'</p>
                </div>
            </div>
        </div>';


    //Social Profiles:
    $acc_en_id = 3;
    echo '<div class="panel panel-default">
            <div class="panel-heading" role="tab" id="openEn'.$acc_en_id.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$acc_en_id.'" aria-expanded="true" aria-controls="collapse'.$acc_en_id.'">
                        <i class="fal fa-share-alt-square"></i> Social Profiles
                    </a>
                </h4>
            </div>
            <div id="collapse'.$acc_en_id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="openEn'.$acc_en_id.'">
                <div class="panel-body entity-list">
                <p>Share your social profiles with the Mench community:</p>';

                //Print social URLs:
                foreach($this->config->item('en_all_6123') as $acc_en_id => $acc_detail){
                    echo '<div class="form-group label-floating is-empty">
                        <div class="input-group border" style="width: 155px;">
                            <span class="input-group-addon addon-lean addon-grey">'.$acc_detail['m_icon'].'</span>
                            <input type="url" id="social_'.$acc_en_id.'" class="form-control border" placeholder="'.$acc_detail['m_name'].' Profile URL" style="display: inline-block;" />
                        </div>
                    </div>';
                }

                echo '<a href="javascript:void(0)" onclick="save_social_urls()" class="btn btn-sm btn-secondary">Save</a>
                            </div>
                        </div>
                    </div>';


    //Print other account radio buttons:
    foreach($this->config->item('en_all_4461') as $acc_en_id => $acc_detail){
        echo '<div class="panel panel-default">
            <div class="panel-heading" role="tab" id="openEn'.$acc_en_id.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$acc_en_id.'" aria-expanded="true" aria-controls="collapse'.$acc_en_id.'">
                        '.$acc_detail['m_icon'].' '.$acc_detail['m_name'].'
                    </a>
                </h4>
            </div>
            <div id="collapse'.$acc_en_id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="openEn'.$acc_en_id.'">
                <div class="panel-body entity-list">
                    <p>'.$acc_detail['m_desc'].'</p>
                    '.echo_radio_entities($acc_en_id, $session_en['en_id'], in_array(6122, $acc_detail['m_parents'])).'
                </div>
            </div>
        </div>';
    }
    ?>

</div>