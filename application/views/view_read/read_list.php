
<script>
    //Set global variables:
    var en_creator_id = <?= $session_en['en_id'] ?>;
</script>
<script src="/js/custom/actionplan-intentions.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<?php

echo '<h1><span class="icon-block-xlg"><i class="far fa-bookmark"></i></span>MY READS</h1>';

if(count($user_intents) > 0){

    //See if we have 2 or more intentions:
    $has_pending_intentions = false;
    $has_multiple_intentions = ( count($user_intents) >= 2 );

    //User has multiple Action Plans, so list all Action Plans to enable User to choose:
    echo '<div id="actionplan_steps" class="list-group actionplan-list '.( $has_multiple_intentions ? 'actionplan-sort' : '').'" style="margin-top:15px;">';
    foreach ($user_intents as $priority => $ln) {

         //Display row:
        echo '<a id="ap_in_'.$ln['in_id'].'" href="/' . $ln['in_id'] . '" sort-link-id="'.$ln['ln_id'].'" class="list-group-item itemread actionplan_sort">';

        echo '<span class="pull-right" style="padding-right:8px; padding-left:10px;">';
        echo '<span class="actionplan_remove" in-id="'.$ln['in_id'].'"><i class="fas fa-trash"></i></span>';
        echo '</span>';

        echo echo_blog_thumbnail($ln['in_id']);

        $completion_rate = $this->READ_model->read__completion_progress($session_en['en_id'], $ln);

        echo '<b class="actionplan-title montserrat montserrat blog-url in-title-'.$ln['in_id'].'">' . $ln['in_outcome'] . '</b>';
        echo '<div class="actionplan-overview">';
        echo '<span title="'.$completion_rate['steps_completed'].' of '.$completion_rate['steps_total'].' blogs read" class="montserrat blog-info doupper">READ '.$completion_rate['completion_percentage'].'%</span>';
        echo '</div>';
        echo '</a>';

        if(!$has_pending_intentions && $completion_rate['completion_percentage'] < 100){
            $has_pending_intentions = true;
        }
    }

    echo '</div>';

    if($has_pending_intentions){
        echo '<a class="btn btn-read inline-block" style="margin: 20px 0;" href="/read/next">CONTINUE <i class="fas fa-angle-right"></i></a>';
    }

    //Give option to add
    echo ' <a class="btn btn-read inline-block" href="/"  style="margin: 20px 0;"><i class="fas fa-plus"></i> NEW READ</a>';


    //Give option to delete all:
    echo '<div class="pull-right"  style="margin: 20px 0;"><a href="/actionplan/delete/'.$psid.'" style="font-size:0.8em; color:#AAA;" data-toggle="tooltip" title="Remove ALL reads from your reading list" data-placement="left"><i class="fas fa-trash"></i>ALL</a></div>';

    if($has_multiple_intentions){
        //Give sorting tip:
        echo '<div class="actionplan-tip"><i class="fas fa-lightbulb"></i> TIP: Prioritize your reads by holding them & dragging them up/down.</div>';
    }

} else {

    //Show warning:
    echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> No reads added to your reading list yet.</div>';

    //Give option to add
    echo ' <a class="btn btn-read inline-block" href="/"><i class="fas fa-plus"></i> READ</a>';

}


?>