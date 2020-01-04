
<script src="/application/views/read/read_my.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
<?php

$has_multiple_blogs = ( count($user_blogs) >= 2 );
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION

echo '<h1 class="read inline-block pull-left"><span class="icon-block-xlg">'.$en_all_11035[6205]['m_icon'].'</span>'.$en_all_11035[6205]['m_name'].'</h1>';

if(!$session_en){

    echo '<div style="padding:10px 0 20px;"><a href="/signin" class="btn btn-play montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to start reading.</div>';

} else {

    echo '<div class="pull-right inline-block">';

    $next_in_id = $this->READ_model->read_next_go($session_en['en_id'], false, false);
    if ($next_in_id > 0) {
        echo '<a href="/'.$next_in_id.'" class="btn btn-read btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12211]['m_name'].'">'.$en_all_11035[12211]['m_icon'].'</a>';
    }

    echo '<a href="/read/history" class="btn btn-read btn-five icon-block-lg '.superpower_active(10964).'" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[11046]['m_name'].'">'.$en_all_11035[11046]['m_icon'].'</a>';

    //Browse New Reads on Home:
    echo '<a href="/" class="btn btn-read btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12201]['m_name'].'">'.$en_all_11035[12201]['m_icon'].'</a>';

    echo '</div>';


    echo '<div class="doclear">&nbsp;</div>';


    //User has multiple 🔴 READING LISTs, so list all 🔴 READING LISTs to enable User to choose:
    echo '<div id="actionplan_steps" class="list-group actionplan-list">';
    foreach ($user_blogs as $priority => $ln) {

        //Display row:
        echo '<a id="ap_in_'.$ln['in_id'].'" href="/' . $ln['in_id'] . '" sort-link-id="'.$ln['ln_id'].'" class="list-group-item itemread '.( $has_multiple_blogs ? 'actionplan_sort' : '').'">';

        echo echo_in_thumbnail($ln['in_id'], true, 'margin-right-18');

        echo '<b class="actionplan-title montserrat montserrat blog-url in-title-'.$ln['in_id'].'">' . $ln['in_title'] . '</b>';

        echo '<div class="montserrat blog-info doupper">';

        $completion_rate = $this->READ_model->read__completion_progress($session_en['en_id'], $ln);
        $metadata = unserialize($ln['in_metadata']);
        if( isset($metadata['in__metadata_common_steps']) && count(array_flatten($metadata['in__metadata_common_steps'])) > 0){

            //It does have some children, let's show more details about it:
            $has_time_estimate = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );

            //Fetch primary author:
            $authors = $this->READ_model->ln_fetch(array(
                'ln_type_play_id' => 4250,
                'ln_child_blog_id' => $ln['in_id'],
            ), array('en_creator'), 1);

            echo ( $has_time_estimate ? echo_time_range($ln, true).' READ ' : '' ).'BY '.one_two_explode('',' ',$authors[0]['en_name']);

        }

        echo ' <span title="'.$completion_rate['steps_completed'].' of '.$completion_rate['steps_total'].' blogs read">'.$completion_rate['completion_percentage'].'% DONE</span>';

        echo '</div>';



        echo '<div class="note-edit edit-off"><span class="show-on-hover">';

        //Sort:
        if($has_multiple_blogs){
            echo '<span title="Drag up/down to sort" data-toggle="tooltip" data-placement="left"><i class="fas fa-sort" style="margin-bottom:7px;"></i></span>';
        }

        //Remove:
        echo '<span title="Remove from list" data-toggle="tooltip" data-placement="left"><span class="actionplan_remove" in-id="'.$ln['in_id'].'"><i class="far fa-trash-alt"></i></span></span>';

        echo '</span></div>';


        echo '</a>';
    }

    echo '</div>';


    if($has_multiple_blogs){

        //Give option to delete all:
        echo '<div class="pull-right clear-reading-list" style="padding:25px 0 0;"><a href="javascript:void(0)" onclick="$(\'.clear-reading-list\').toggleClass(\'hidden\')" class="montserrat doupper dolight"><span class="icon-block"><i class="fas fa-trash-alt"></i></span></a></div>';

        $timestamp = time();

        echo '<div class="clear-reading-list hidden">';

        echo '<p><span class="icon-block"><i class="fas fa-exclamation-triangle read"></i></span><b class="read montserrat">WARNING:</b> You are about to clear you entire reading list. You will lose all your <span class="icon-block"><i class="fas fa-circle read"></i></span><b class="montserrat read">READ COINS</b> but can earn them back by reading again.</p>';
        echo '<p style="margin-top:20px;"><a href="/read/actionplan_reset_progress/'.$session_en['en_id'].'/'.$timestamp.'/'.md5($session_en['en_id'] . $this->config->item('cred_password_salt') . $timestamp).'" class="btn btn-read"><i class="far fa-trash-alt"></i> CLEAR READS & COINS</a> or <a href="javascript:void(0)" onclick="$(\'.clear-reading-list\').toggleClass(\'hidden\')" style="text-decoration: underline;">Cancel</a></p>';

        echo '</div>';

    }
}
?>
</div>