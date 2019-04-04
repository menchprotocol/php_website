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
                <div class="panel-body">
                    <input type="text" id="en_name_url" class="form-control border" value="'.$session_en['en_name'].'" style="display: inline-block;" />
                    <a href="javascript:void(0)" onclick="save_full_name()" class="btn btn-sm btn-secondary">Save</a>
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
    $acc_en_id = 1;
    echo '<div class="panel panel-default">
            <div class="panel-heading" role="tab" id="openEn'.$acc_en_id.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$acc_en_id.'" aria-expanded="true" aria-controls="collapse'.$acc_en_id.'">
                        <i class="fal fa-lock-open"></i> Mench Password
                    </a>
                </h4>
            </div>
            <div id="collapse'.$acc_en_id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="openEn'.$acc_en_id.'">
                <div class="panel-body">
                    <p>This password enables you to login to your Action Plan on mench.com via any device.</p>
                    <p>'. ( count($current_pass_trs) > 0 ? 'Password updated '.echo_time_difference(strtotime($current_pass_trs[0]['tr_timestamp'])).' ago.' : 'You have not yet set a password.') .'</p>
                    <input type="password" id="en_password" class="form-control border" placeholder="Set new password..." style="display: inline-block;" />
                    <a href="javascript:void(0)" onclick="save_password()" class="btn btn-sm btn-secondary">Update</a>
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
                    '.echo_radio_entities($acc_en_id, $session_en['en_id'], in_array(6122, $acc_detail['m_parents'])).'
                </div>
            </div>
        </div>';
    }

    //Print social URLs:
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
                    '.echo_radio_entities($acc_en_id, $session_en['en_id'], in_array(6122, $acc_detail['m_parents'])).'
                </div>
            </div>
        </div>';
    }
    ?>

</div>