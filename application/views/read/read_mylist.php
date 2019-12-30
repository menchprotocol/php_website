
<script src="/application/views/read/read_mylist.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
<?php

echo '<h1 class="ispink"><span class="icon-block-xlg"><i class="fas fa-circle ispink"></i></span>MY READS</h1>';

//See if we have 2 or more blogs:
$has_multiple_blogs = ( count($user_blogs) >= 2 );

//User has multiple 🔴 READING LISTs, so list all 🔴 READING LISTs to enable User to choose:
echo '<div id="actionplan_steps" class="list-group actionplan-list" style="margin-top:15px;">';
foreach ($user_blogs as $priority => $ln) {

    //Display row:
    echo '<a id="ap_in_'.$ln['in_id'].'" href="/' . $ln['in_id'] . '" sort-link-id="'.$ln['ln_id'].'" class="list-group-item itemread '.( $has_multiple_blogs ? 'actionplan_sort' : '').'">';

    echo echo_in_thumbnail($ln['in_id'], true);

    echo '<b class="actionplan-title montserrat montserrat blog-url in-title-'.$ln['in_id'].'">' . $ln['in_title'] . '</b>';

    echo '<div class="montserrat blog-info doupper">';

    $completion_rate = $this->READ_model->read__completion_progress($session_en['en_id'], $ln);
    $metadata = unserialize($ln['in_metadata']);
    if( isset($metadata['in__metadata_common_steps']) && count(array_flatten($metadata['in__metadata_common_steps'])) > 0){

        //It does have some children, let's show more details about it:
        $has_time_estimate = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );

        //Fetch primary author:
        $authors = $this->READ_model->ln_fetch(array(
            'ln_type_player_id' => 4250,
            'ln_child_blog_id' => $ln['in_id'],
        ), array('en_creator'), 1);

        echo ( $has_time_estimate ? echo_time_range($ln, true).' READ ' : '' ).'BY '.one_two_explode('',' ',$authors[0]['en_name']);

    }

    echo ' <span title="'.$completion_rate['steps_completed'].' of '.$completion_rate['steps_total'].' blogs read" class="montserrat blog-info doupper">'.$completion_rate['completion_percentage'].'% DONE</span>';

    echo '</div>';



    $ui .= '<div class="note-edit edit-off"><span class="show-on-hover">';

    //Sort:
    if($has_multiple_blogs){
        $ui .= '<span title="Drag up/down to sort" data-toggle="tooltip" data-placement="left"><i class="fas fa-sort"></i></span>';
    }

    //Remove:
    $ui .= '<span title="Remove from list" data-toggle="tooltip" data-placement="left"><span class="actionplan_remove" in-id="'.$ln['in_id'].'"><i class="far fa-trash-alt"></i></span></span>';

    $ui .= '</span></div>';


    echo '</a>';
}

echo '</div>';

//Give option to add
echo ' <a class="btn btn-read inline-block" href="/"  style="margin: 20px 0;"><i class="fas fa-plus"></i> READ</a>';
echo ' <a class="btn btn-read inline-block" href="/read/next"  style="margin: 20px 0;">NEXT <i class="fas fa-angle-right"></i></a>';


if($has_multiple_blogs){

    //Give option to delete all:
    echo '<div class="pull-right clear-reading-list" style="padding:25px 0 0;"><a href="javascript:void(0)" onclick="$(\'.clear-reading-list\').toggleClass(\'hidden\')" class="montserrat doupper">ALL<span class="icon-block"><i class="far fa-trash-alt"></i></span></a></div>';

    $timestamp = time();

    echo '<div class="clear-reading-list hidden">';

        echo '<p><span class="icon-block"><i class="fas fa-exclamation-triangle ispink"></i></span><b class="ispink montserrat">WARNING:</b> You are about to remove all blogs from your reading list.</p>';
        echo '<p style="margin-top:20px;"><a href="/read/actionplan_reset_progress/'.$session_en['en_id'].'/'.$timestamp.'/'.md5($session_en['en_id'] . $this->config->item('cred_password_salt') . $timestamp).'" class="btn btn-read"><i class="far fa-trash-alt"></i> REMOVE ALL</a> or <a href="javascript:void(0)" onclick="$(\'.clear-reading-list\').toggleClass(\'hidden\')" style="text-decoration: underline;">Cancel</a></p>';

    echo '</div>';

}


?>
</div>