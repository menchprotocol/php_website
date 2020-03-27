<?php

$timestamp = time();
$has_multiple_blogs = ( count($player_reads) >= 2 );
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION

?>


<script>
    //Include some cached players:
    var clear_read_url = '<?= '/read/actionplan_reset_progress/'.$session_en['en_id'].'/'.$timestamp.'/'.md5($session_en['en_id'] . $this->config->item('cred_password_salt') . $timestamp) ?>';
</script>
<script src="/application/views/read/read_home.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
<?php
if(!$session_en){

    echo '<div style="padding:10px 0 20px;"><a href="/sign?url=/read" class="btn btn-read montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to get started.</div>';

} else {

    echo '<div class="read-topic"><span class="icon-block">'.$en_all_11035[7347]['m_icon'].'</span>'.$en_all_11035[7347]['m_name'].'</div>';

    echo '<div id="actionplan_steps" class="list-group no-side-padding">';
    foreach ($player_reads as $priority => $ln) {

        //Display row:
        echo '<a id="ap_in_'.$ln['in_id'].'" href="/' . $ln['in_id'] . '" sort-link-id="'.$ln['ln_id'].'" class="list-group-item no-side-padding itemread '.( $has_multiple_blogs ? 'actionplan_sort' : '').'" style="padding-right: 25px !important;">';

        echo echo_in_thumbnail($ln['in_id']);

        echo '<span class="icon-block"><i class="fas fa-circle read" aria-hidden="true"></i></span>';
        echo '<b class="actionplan-title montserrat montserrat blog-url in-title-'.$ln['in_id'].'">' . $ln['in_title'] . '</b>';


        if(superpower_active(10989, true)){
            $completion_rate = $this->READ_model->read__completion_progress($session_en['en_id'], $ln);
            $metadata = unserialize($ln['in_metadata']);
            if( isset($metadata['in__metadata_common_steps']) && count(array_flatten($metadata['in__metadata_common_steps'])) > 0){

                echo '<div class="montserrat blog-info doupper '.superpower_active(10989).'"><span class="icon-block">&nbsp;</span>';
                //It does have some children, let's show more details about it:
                $has_time_estimate = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );

                echo ( $has_time_estimate ? echo_time_range($ln, true).' READ ' : '' );
                echo '<span title="'.$completion_rate['steps_completed'].' of '.$completion_rate['steps_total'].' blogs read">['.$completion_rate['completion_percentage'].'% DONE]</span> ';
                echo '</div>';

            }
        }




        echo '<div class="note-edit edit-off"><span class="show-on-hover">';

        //Sort:
        if($has_multiple_blogs){
            echo '<span title="Drag up/down to sort" data-toggle="tooltip" data-placement="left"><i class="fas fa-sort"></i></span>';
        }

        //Remove:
        echo '<span title="Remove from list" data-toggle="tooltip" data-placement="left"><span class="actionplan_remove" in-id="'.$ln['in_id'].'"><i class="far fa-trash-alt"></i></span></span>';

        echo '</span></div>';


        echo '</a>';
    }

    echo '</div>';



    echo '<div style="margin-top: 10px;">';


        //Add New Read:
        echo '<a href="/" class="btn btn-read">'.$en_all_11035[12581]['m_icon'].' '.$en_all_11035[12581]['m_name'].'</a>&nbsp;&nbsp;';


        //Next Read:
        echo '<a href="/read/next" class="btn btn-read">'.$en_all_11035[12211]['m_name'].' '.$en_all_11035[12211]['m_icon'].'</a>&nbsp;&nbsp;';


        //Give option to delete all:
        echo '<a href="javascript:void(0)" onclick="$(\'.clear-reading-list\').toggleClass(\'hidden\')" class="btn btn-read '.superpower_active(10984).'">'.$en_all_11035[6415]['m_icon'].'</a>';
        echo '<div class="clear-reading-list hidden" style="padding:34px 0;">';
        echo '<p><span class="icon-block"><i class="fad fa-exclamation-triangle read"></i></span><b class="read montserrat">WARNING:</b> You are about to clear you entire reading list. You will lose all your <span class="icon-block">🔴</span><b class="montserrat read">READ COINS</b> but can earn them back by reading again.</p>';
        echo '<p style="margin-top:20px;"><a href="javascript:void(0);" onclick="clear_all_reads()" class="btn btn-read"><i class="far fa-trash-alt"></i> CLEAR ALL READS</a> or <a href="javascript:void(0)" onclick="$(\'.clear-reading-list\').toggleClass(\'hidden\')" style="text-decoration: underline;">Cancel</a></p>';
        echo '</div>';


    echo '</div>';


}
?>
</div>