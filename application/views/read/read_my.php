
<script src="/application/views/read/read_my.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
<?php

$has_multiple_ideas = ( count($player_reads) >= 2 );
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION


if(!$session_en){

    echo '<div style="padding:10px 0 20px;"><a href="/signin?url=/read" class="btn btn-read montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to get started.</div>';

} else {

    //echo '<div class="pull-left">' . echo_menu(12201, 'btn-read') . '</div>';
    $en_all_2738 = $this->config->item('en_all_2738'); //MENCH


    echo '<div class="pull-right inline-block">';

    echo '<a href="/read/next" class="btn btn-read btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12211]['m_name'].'">'.$en_all_11035[12211]['m_icon'].'</a>';

    //READ HISTORY
    //echo '<a href="/oii?ln_type_play_id='.join(',', $this->config->item('en_ids_6255')).'&ln_status_play_id='.join(',', $this->config->item('en_ids_7359')).'&ln_owner_play_id='.$session_en['en_id'].'" class="btn btn-read btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[11999]['m_name'].'">'.$en_all_11035[11999]['m_icon'].'</a>';

    //Give option to delete all:
    echo '<a href="javascript:void(0)" onclick="$(\'.clear-reading-list\').toggleClass(\'hidden\')" class="btn btn-read btn-five icon-block-lg '.superpower_active(10984).'" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[6415]['m_name'].'">'.$en_all_11035[6415]['m_icon'].'</a>';

    //Browse New Reads on Home:
    echo '<a href="/" class="btn btn-read btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12201]['m_name'].'">'.$en_all_11035[12201]['m_icon'].'</a>';

    echo '</div>';


    //LEFT
    echo '<h1 class="pull-left inline-block read"><span class="icon-block-xlg">' . $en_all_2738[6205]['m_icon'] . '</span>'.$en_all_2738[6205]['m_name'].'</h1>';


    echo '<div class="doclear">&nbsp;</div>';

    $timestamp = time();

    echo '<div class="clear-reading-list hidden">';

    echo '<p><span class="icon-block"><i class="fas fa-exclamation-triangle read"></i></span><b class="read montserrat">WARNING:</b> You are about to clear you entire reading list. You will lose all your <span class="icon-block">🔴</span><b class="montserrat read">READ COINS</b> but can earn them back by reading again.</p>';
    echo '<p style="margin-top:20px;"><a href="/read/actionplan_reset_progress/'.$session_en['en_id'].'/'.$timestamp.'/'.md5($session_en['en_id'] . $this->config->item('cred_password_salt') . $timestamp).'" onclick="$(\'.clear-reading-list\').html(\'Removing all reads, please wait...\')" class="btn btn-read"><i class="far fa-trash-alt"></i> CLEAR READS & COINS</a> or <a href="javascript:void(0)" onclick="$(\'.clear-reading-list\').toggleClass(\'hidden\')" style="text-decoration: underline;">Cancel</a></p>';

    echo '</div>';


    //User has multiple READING LISTs, so list all READING LISTs to enable User to choose:
    echo '<div id="actionplan_steps" class="list-group actionplan-list">';
    foreach ($player_reads as $priority => $ln) {

        //Display row:
        echo '<a id="ap_in_'.$ln['in_id'].'" href="/' . $ln['in_id'] . '" sort-link-id="'.$ln['ln_id'].'" class="list-group-item itemread '.( $has_multiple_ideas ? 'actionplan_sort' : '').'">';

        echo echo_in_thumbnail($ln['in_id'], true, 'margin-right-18');

        echo '<b class="actionplan-title montserrat montserrat idea-url in-title-'.$ln['in_id'].'">' . $ln['in_title'] . '</b>';

        echo '<div class="montserrat idea-info doupper">';

        $completion_rate = $this->READ_model->read__completion_progress($session_en['en_id'], $ln);
        $metadata = unserialize($ln['in_metadata']);
        if( isset($metadata['in__metadata_common_steps']) && count(array_flatten($metadata['in__metadata_common_steps'])) > 0){

            //It does have some children, let's show more details about it:
            $has_time_estimate = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );

            echo ( $has_time_estimate ? echo_time_range($ln, true).' READ ' : '' );
            echo '<span title="'.$completion_rate['steps_completed'].' of '.$completion_rate['steps_total'].' ideas read">['.$completion_rate['completion_percentage'].'% DONE]</span> ';

        }

        echo '</div>';



        echo '<div class="note-edit edit-off"><span class="show-on-hover">';

        //Sort:
        if($has_multiple_ideas){
            echo '<span title="Drag up/down to sort" data-toggle="tooltip" data-placement="left"><i class="fas fa-sort" style="margin-bottom:7px;"></i></span>';
        }

        //Remove:
        echo '<span title="Remove from list" data-toggle="tooltip" data-placement="left"><span class="actionplan_remove" in-id="'.$ln['in_id'].'"><i class="far fa-trash-alt"></i></span></span>';

        echo '</span></div>';


        echo '</a>';
    }

    echo '</div>';

}
?>
</div>