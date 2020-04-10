
<script>
    //Include some cached sources:
    var open_en_id = <?= (isset($_GET['open_en_id']) && intval($_GET['open_en_id'])>0 ? intval($_GET['open_en_id']) : 0 ) ?>;
</script>

<script src="/application/views/source/source_account.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php

    //My Account Header
    $en_all_11035 = $this->config->item('en_all_11035'); //MENCH  NAVIGATION
    echo '<div class="read-topic"><span class="icon-block">'.$en_all_11035[6225]['m_icon'].'</span>'.$en_all_11035[6225]['m_name'].'</div>';



    echo '<div class="accordion" id="MyAccountAccordion" style="margin-bottom:34px;">';

    //Display account fields ordered with their source links:
    foreach ($this->config->item('en_all_6225') as $acc_en_id => $acc_detail) {

        //Keep all closed for now:
        $expand_by_default = false;

        //Print header:
        echo '<div class="card">
<div class="card-header" id="heading' . $acc_en_id . '">
<button class="btn btn-block" type="button" data-toggle="collapse" data-target="#openEn' . $acc_en_id . '" aria-expanded="' . ($expand_by_default ? 'true' : 'false') . '" aria-controls="openEn' . $acc_en_id . '">
  <span class="icon-block">' . $acc_detail['m_icon'] . '</span><b class="montserrat source doupper ' . extract_icon_color($acc_detail['m_icon']) . '" style="padding-left:5px;">' . $acc_detail['m_name'] . '</b><span class="pull-right icon-block"><i class="fas fa-chevron-down"></i></span>
</button>
</div>

<div class="doclear">&nbsp;</div>

<div id="openEn' . $acc_en_id . '" class="collapse ' . ($expand_by_default ? ' show ' : '') . '" aria-labelledby="heading' . $acc_en_id . '" data-parent="#MyAccountAccordion">
<div class="card-body">';


        //Show description if any:
        echo (strlen($acc_detail['m_desc']) > 0 ? '<p>' . $acc_detail['m_desc'] . '</p>' : '');


        //Print account fields that are either Single Selectable or Multi Selectable:
        $is_multi_selectable = in_array(6122, $acc_detail['m_parents']);
        $is_single_selectable = in_array(6204, $acc_detail['m_parents']);

        if ($acc_en_id == 12289) {

            $source_icon_parts = explode(' ',one_two_explode('class="', '"', $session_en['en_icon']));

            echo '<div class="'.superpower_active(10939).'"><div class="doclear">&nbsp;</div><div class="btn-group avatar-type-group pull-right" role="group" style="margin:0 0 10px 0;">
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'far\')" class="btn btn-far '.( $source_icon_parts[0]=='far' ? ' active ' : '' ).'"><i class="far fa-paw source"></i></a>
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fad\')" class="btn btn-fad '.( $source_icon_parts[0]=='fad' ? ' active ' : '' ).'"><i class="fad fa-paw source"></i></a>
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fas\')" class="btn btn-fas '.( $source_icon_parts[0]=='fas' ? ' active ' : '' ).'"><i class="fas fa-paw source"></i></a>
                </div><div class="doclear">&nbsp;</div></div>';


            //List avatars:
            foreach ($this->config->item('en_all_12279') as $en_id => $m) {

                $avatar_icon_parts = explode(' ',one_two_explode('class="', '"', $m['m_icon']));
                $avatar_type_match = ($source_icon_parts[0] == $avatar_icon_parts[0]);
                $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m['m_parents']);

                echo '<span class="'.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a href="javascript:void(0);" onclick="account_update_avatar_icon(\'' . $avatar_icon_parts[0] . '\', \'' . $avatar_icon_parts[1] . '\')" icon-css="' . $avatar_icon_parts[1] . '" class="list-group-item itemsource avatar-item item-square avatar-type-'.$avatar_icon_parts[0].' avatar-name-'.$avatar_icon_parts[1].' ' .( $avatar_type_match ? '' : ' hidden ' ). ( $avatar_type_match && $source_icon_parts[1] == $avatar_icon_parts[1] ? ' active ' : '') . '"><div class="avatar-icon">' . $m['m_icon'] . '</div></a></span>';

            }

        } elseif ($acc_en_id == 10957 /* Superpowers */) {

            //Load Website URLs:
            $en_all_10876 = $this->config->item('en_all_10876'); //MENCH WEBSITE


            echo '<div class="list-group">';

            //List avatars:
            foreach($this->config->item('en_all_10957') as $superpower_en_id => $m){

                //What is the superpower requirement?
                $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m['m_parents']);
                $is_available = (!count($superpower_actives) || superpower_assigned(end($superpower_actives)));
                $is_unlocked = ($is_available && superpower_assigned($superpower_en_id));
                $has_training_url = ( strlen($en_all_10876[$superpower_en_id]['m_desc']) ? $en_all_10876[$superpower_en_id]['m_desc'] : false );
                $extract_icon_color = extract_icon_color($m['m_icon']);

                if($has_training_url && ($is_unlocked || $is_available)){

                    //Superpower Available
                    echo '<div class="list-group-item itemsetting"><a href="'.$has_training_url.'"><span class="icon-block">'.( $is_unlocked ? '<i class="fas fa-unlock-alt"></i>' : '<i class="fas fa-lock"></i>' ).'</span><span class="icon-block '.$extract_icon_color.'">'.$m['m_icon'].'</span><b class="montserrat '.$extract_icon_color.'">'.$m['m_name'].'</b> '.$m['m_desc'].'</a></div>';

                } else {

                    //Locked
                    //echo '<div class="list-group-item"><span class="icon-block '.$extract_icon_color.'">'.$m['m_icon'].'</span><b class="montserrat '.$extract_icon_color.'">'.$m['m_name'].'</b> '.$m['m_desc'].'<span class="icon-block pull-right"><i class="fas fa-lock"></i></span></div>';

                }
            }

            echo '</div>';

        } elseif ($acc_en_id == 6197 /* Name */) {

            echo '<span class="white-wrapper"><input type="text" id="en_name" class="form-control border source doupper montserrat dotransparent" value="' . $session_en['en_name'] . '" /></span>
                <a href="javascript:void(0)" onclick="account_update_name()" class="btn btn-source">Save</a>
                <span class="saving-account save_full_name"></span>';

        } elseif ($is_multi_selectable || $is_single_selectable) {

            echo echo_radio_sources($acc_en_id, $session_en['en_id'], ($is_multi_selectable ? 1 : 0));

        }

        //Print footer:
        echo '<div class="doclear">&nbsp;</div>';
        echo '</div></div></div>';

    }

    echo '</div>'; //End of accordion

    ?>
</div>