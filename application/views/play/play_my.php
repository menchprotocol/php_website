
<script>
    //Include some cached players:
    var open_en_id = <?= (isset($_GET['open_en_id']) && intval($_GET['open_en_id'])>0 ? intval($_GET['open_en_id']) : 0 ) ?>;
</script>
<script src="/application/views/play/play_my.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
<div class="container">

<?php

$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH

if($session_en) {

    //See how this user is connected to Mench
    $messenger_activated = in_array(6196, $this->session->userdata('session_parent_ids'));
    $web_email_activated = in_array(12103, $this->session->userdata('session_parent_ids'));


    echo '<div class="pull-right inline-block">';

        echo '<a href="/play/' . $session_en['en_id'] . '" class="btn btn-play btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="' . $en_all_11035[12205]['m_name'] . '">' . $en_all_11035[12205]['m_icon'] . '</a>';

        if (!intval($this->session->userdata('session_6196_signin'))) {
            //Only give signout option if NOT logged-in from Messenger
            echo '<a href="/play/signout" class="btn btn-play btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="' . $en_all_11035[7291]['m_name'] . '">' . $en_all_11035[7291]['m_icon'] . '</a>';
    }

    echo '</div>';

    echo '<h1 class="play pull-left inline-block"><span class="icon-block-xlg icon_photo icon_en_'.$session_en['en_id'].'">' . echo_en_icon($session_en['en_icon']) . '</span><span class="en_name_full_'.$session_en['en_id'].'">' . $session_en['en_name'] . '</span></h1>';

    echo '<div class="doclear">&nbsp;</div>';

    echo '<div class="accordion" id="MyPlayerAccordion" style="margin-bottom:34px;">';

    //Display account fields ordered with their player links:
    foreach ($this->config->item('en_all_6225') as $acc_en_id => $acc_detail) {

        if(in_array(6196, $acc_detail['m_parents']) && !$messenger_activated){
            //Messenger Setting but player is not connected via Messenger
            continue;
        }

        //Keep all closed for now:
        $expand_by_default = false;

        //Print header:
        echo '<div class="card">
    <div class="card-header" id="heading' . $acc_en_id . '">
    <button class="btn" type="button" data-toggle="collapse" data-target="#openEn' . $acc_en_id . '" aria-expanded="' . ($expand_by_default ? 'true' : 'false') . '" aria-controls="openEn' . $acc_en_id . '">
      <span class="icon-block-lg">' . $acc_detail['m_icon'] . '</span><b class="montserrat doupper ' . extract_icon_color($acc_detail['m_icon']) . '" style="padding-left:5px;">' . $acc_detail['m_name'] . '</b>
    </button>
    </div>
    
    <div id="openEn' . $acc_en_id . '" class="collapse ' . ($expand_by_default ? ' show ' : '') . '" aria-labelledby="heading' . $acc_en_id . '" data-parent="#MyPlayerAccordion">
    <div class="card-body">';


        //Show description if any:
        echo(strlen($acc_detail['m_desc']) > 0 ? '<p>' . $acc_detail['m_desc'] . '</p>' : '');


        //Print account fields that are either Single Selectable or Multi Selectable:
        $is_multi_selectable = in_array(6122, $acc_detail['m_parents']);
        $is_single_selectable = in_array(6204, $acc_detail['m_parents']);

        if ($acc_en_id == 12289) {

            $player_icon_parts = explode(' ',one_two_explode('class="', '"', $session_en['en_icon']));

            echo '<div class="'.superpower_active(10939).'"><div class="doclear">&nbsp;</div><div class="btn-group avatar-type-group pull-right" role="group" style="margin:-36px -10px 0 0;">
                      <a href="javascript:void(0)" onclick="account_update_avatar_type(\'far\')" class="btn btn-far '.( $player_icon_parts[0]=='far' ? ' active ' : '' ).'"><i class="far fa-paw play"></i></a>
                      <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fad\')" class="btn btn-fad '.( $player_icon_parts[0]=='fad' ? ' active ' : '' ).'"><i class="fad fa-paw play"></i></a>
                      <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fas\')" class="btn btn-fas '.( $player_icon_parts[0]=='fas' ? ' active ' : '' ).'"><i class="fas fa-paw play"></i></a>
                    </div><div class="doclear">&nbsp;</div></div>';


            //List avatars:
            foreach ($this->config->item('en_all_12279') as $en_id => $m) {

                $avatar_icon_parts = explode(' ',one_two_explode('class="', '"', $m['m_icon']));
                $avatar_type_match = ($player_icon_parts[0] == $avatar_icon_parts[0]);
                $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m['m_parents']);

                echo '<span class="'.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a href="javascript:void(0);" onclick="account_update_avatar_icon(\'' . $avatar_icon_parts[0] . '\', \'' . $avatar_icon_parts[1] . '\')" icon-css="' . $avatar_icon_parts[1] . '" class="list-group-item itemplay avatar-item item-square avatar-type-'.$avatar_icon_parts[0].' avatar-name-'.$avatar_icon_parts[1].' ' .( $avatar_type_match ? '' : ' hidden ' ). ( $avatar_type_match && $player_icon_parts[1] == $avatar_icon_parts[1] ? ' active ' : '') . '"><div class="avatar-icon">' . $m['m_icon'] . '</div></a></span>';

            }

            echo '<div class="doclear">&nbsp;</div>';

        } elseif ($acc_en_id == 6197 /* Name */) {

            echo '<span class="white-wrapper"><input type="text" id="en_name" class="form-control border play doupper montserrat" value="' . $session_en['en_name'] . '" /></span>
                    <a href="javascript:void(0)" onclick="account_update_name()" class="btn btn-play">Save</a>
                    <span class="saving-account save_full_name"></span>';

        } elseif ($acc_en_id == 3288 /* Email */) {

            $user_emails = $this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_child_play_id' => $session_en['en_id'],
                'ln_type_play_id' => 4255, //Linked Players Text (Email is text)
                'ln_parent_play_id' => 3288, //Mench Email
            ));

            echo '<span class="white-wrapper"><input type="email" id="en_email" class="form-control border" value="' . (count($user_emails) > 0 ? $user_emails[0]['ln_content'] : '') . '" placeholder="you@gmail.com" /></span>
                    <a href="javascript:void(0)" onclick="account_update_email()" class="btn btn-play">Save</a>
                    <span class="saving-account save_email"></span>';

        } elseif ($acc_en_id == 3286 /* Password */) {

            echo '<span class="white-wrapper"><input type="password" id="input_password" class="form-control border" data-lpignore="true" autocomplete="new-password" placeholder="New Password..." /></span>
                    <a href="javascript:void(0)" onclick="account_update_password()" class="btn btn-play">Save</a>
                    <span class="saving-account save_password"></span>';

        } elseif ($is_multi_selectable || $is_single_selectable) {

            echo echo_radio_players($acc_en_id, $session_en['en_id'], ($is_multi_selectable ? 1 : 0));

        }

        //Print footer:
        echo '</div></div></div>';

    }

    echo '</div>'; //End of accordion

} else {

    echo '<div style="padding:10px 0 20px;"><a href="/signin?url=/play" class="btn btn-play montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to start playing.</div>';


}



//MENCH COINS
$read_coins = $this->READ_model->ln_fetch(array(
    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
$idea_coins = $this->READ_model->ln_fetch(array(
    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_play_id' => 4250, //UNIQUE IDEAS
), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
$play_coins = $this->READ_model->ln_fetch(array(
    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //PLAY COIN
), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');

echo '<h1 class="montserrat play"><span class="icon-block-xlg icon_photo">'.$en_all_11035[12358]['m_icon'].'</span>'.$en_all_11035[12358]['m_name'].'</h1>';

echo '<table class="table table-sm table-striped" style="margin-bottom: 30px;">';
echo '<tr>';
echo '<td class="play MENCHcolumn1"><span class="play"><span class="parent-icon icon-block">' . $en_all_2738[4536]['m_icon'] . '</span><span class="montserrat" title="'.number_format($play_coins[0]['total_coins'], 0).'">'.echo_number($play_coins[0]['total_coins']).' PLAYERS</span></span></td>';
echo '<td class="idea MENCHcolumn2"><span class="idea"><span class="parent-icon icon-block">' . $en_all_2738[4535]['m_icon'] . '</span><span class="montserrat" title="'.number_format($idea_coins[0]['total_coins'], 0).'">'.echo_number($idea_coins[0]['total_coins']).' IDEAS</span></span></td>';
echo '<td class="read MENCHcolumn3"><span class="read"><span class="parent-icon icon-block">' . $en_all_2738[6205]['m_icon'] . '</span><span class="montserrat" title="'.number_format($read_coins[0]['total_coins'], 0).'">'.echo_number($read_coins[0]['total_coins']).' READS</span></span></td>';
echo '</tr>';
echo '</table>';




?>

<!-- Top Players -->
<h1 class="montserrat play"><span class="icon-block-xlg icon_photo"><?= $en_all_11035[4430]['m_icon'] ?></span><?= $en_all_11035[4430]['m_name'] ?></h1>
    <div class="one-pix">
        <div id="load_top_players"></div>
    </div>
</div>