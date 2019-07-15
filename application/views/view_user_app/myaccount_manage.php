
<script>
    //Set global variables:
    var en_miner_id = <?= $session_en['en_id'] ?>;
</script>
<script src="/js/custom/messenger-myaccount.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>

<h1>My Account</h1>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

    <?php
    //Display account fields ordered with their entity links:
    foreach($this->config->item('en_all_6225') as $acc_en_id => $acc_detail){

        //Print header:
        echo '<div class="panel panel-default">
                <div class="panel-heading" role="tab" id="openEn'.$acc_en_id.'">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$acc_en_id.'" aria-expanded="true" aria-controls="collapse'.$acc_en_id.'">
                            <span class="icon-block" style="width:38px;">'.$acc_detail['m_icon'].'</span>'.$acc_detail['m_name'].'
                        </a>
                    </h4>
                </div>
                <div id="collapse'.$acc_en_id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="openEn'.$acc_en_id.'">
                    <div class="panel-body entity-list">
                        <p>'.$acc_detail['m_desc'].'</p>';


        //Print account fields that are either Single Selectable or Multi Selectable:
        $is_multi_selectable  = in_array(6122 , $acc_detail['m_parents']);
        $is_single_selectable = in_array(6204 , $acc_detail['m_parents']);
        if($is_multi_selectable || $is_single_selectable){

            echo echo_radio_entities($acc_en_id, $session_en['en_id'], ( $is_multi_selectable ? 1 : 0 ));

        } elseif($acc_en_id==6197 /* Full Name */){

            echo '<input type="text" id="en_name" class="form-control border" value="'.$session_en['en_name'].'" />
                    <a href="javascript:void(0)" onclick="save_full_name()" class="btn btn-sm btn-primary">Save</a>
                    <span class="saving-account save_full_name"></span>';

        } elseif($acc_en_id==3288 /* Email */){

            $user_emails = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_child_entity_id' => $session_en['en_id'],
                'ln_type_entity_id' => 4255, //Linked Entities Text (Email is text)
                'ln_parent_entity_id' => 3288, //Email Address
            ));

            echo '<input type="email" id="en_email" class="form-control border" value="'.( count($user_emails) > 0 ? $user_emails[0]['ln_content'] : '' ).'" placeholder="you@gmail.com" />
                    <a href="javascript:void(0)" onclick="save_email()" class="btn btn-sm btn-primary">Save</a>
                    <span class="saving-account save_email"></span>';

        } elseif($acc_en_id==3286 /* Password */){

            $user_passwords = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_entity_id' => 4255, //Text
                'ln_parent_entity_id' => 3286, //Password
                'ln_child_entity_id' => $session_en['en_id'], //For this user
            ));

            echo '<input type="password" id="en_password" class="form-control border" placeholder="Set new password..." />
                    <a href="javascript:void(0)" onclick="save_password()" class="btn btn-sm btn-primary">Save</a>
                    <span class="saving-account save_password"></span>
                    <p>Note: '. ( count($user_passwords) > 0 ? 'Password updated '.echo_time_difference(strtotime($user_passwords[0]['ln_timestamp'])).' ago.' : 'You have not yet set a password.') .'</p>';

        } elseif($acc_en_id==4783 /* Phone */){

            $user_phones = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_child_entity_id' => $session_en['en_id'],
                'ln_type_entity_id' => 4319, //Phone are of type number
                'ln_parent_entity_id' => 4783, //Phone Number
            ));

            echo '<input type="number" id="en_phone" class="form-control border" value="'.( count($user_phones) > 0 ? $user_phones[0]['ln_content'] : '' ).'" placeholder="Set phone number..." />
                    <a href="javascript:void(0)" onclick="save_phone()" class="btn btn-sm btn-primary">Save</a>
                    <span class="saving-account save_phone"></span>';

        } elseif($acc_en_id==6123 /* Social Profiles */){

            $user_social_profiles = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_entity_id' => 4256, //Generic URL
                'ln_parent_entity_id IN ('.join(',', $this->config->item('en_ids_6123')).')' => null, //Any social profile
                'ln_child_entity_id' => $session_en['en_id'], //For this user
            ));

            echo '<script> var en_ids_6123 = ' . json_encode($this->config->item('en_ids_6123')) . '; </script>'; //Used for JS variables:
            foreach($this->config->item('en_all_6123') as $acc_en_id => $acc_detail){
                //Do we have this social profile?
                $profile_array = filter_array($user_social_profiles, 'ln_parent_entity_id', $acc_en_id);
                echo '<div class="form-group label-floating is-empty">
                        <div class="input-group border" style="width: 155px;">
                            <span class="input-group-addon addon-lean addon-grey">'.$acc_detail['m_icon'].'</span>
                            <input type="url" value="'.( $profile_array ? $profile_array['ln_content'] : '' ).'" parent-en-id="'.$acc_en_id.'" class="form-control border social_profile_url" placeholder="'.$acc_detail['m_name'].' Profile URL" style="display: inline-block;" />
                        </div>
                    </div>';
            }
            echo '<a href="javascript:void(0)" onclick="save_social_profiles()" class="btn btn-sm btn-primary">Save</a>
                                <span class="saving-account save_social_profiles"></span>';

        }

        //Print footer:
        echo '</div></div></div>';
    }
    ?>

</div>