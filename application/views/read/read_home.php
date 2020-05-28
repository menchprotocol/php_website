<?php
$timestamp = time();
$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
//Fetch Reads:
$player_reads = $this->READ_model->fetch(array(
    'read__source' => $session_source['source__id'],
    'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
    'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
), array('read__left'), 0, 0, array('read__sort' => 'ASC'));
?>

<style>
    <?= ( count($player_reads) < 2 ? '.read-sorter {display:none !important;}' : '' ) //Need 2 or more to sort ?>
</style>
<script>

    //Include some cached sources:
    var clear_read_url = '<?= '/read/read_coins_remove_all/'.$session_source['source__id'].'/'.$timestamp.'/'.md5($session_source['source__id'] . $this->config->item('cred_password_salt') . $timestamp) ?>';

    <?= ( count($player_reads) >= 2 ? '$(document).ready(function () {read_sort_load()});' : '' ) ?>

</script>
<script src="/application/views/read/read_home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>


<div class="container">
<?php

//MY READS
echo '<div class="read-topic"><span class="icon-block">'.$sources__11035[12969]['m_icon'].'</span>'.$sources__11035[12969]['m_name'].'</div>';
if(!count($player_reads)){

    echo '<div class="alert alert-danger"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Nothing yet. <a href="/" class="underline">Get Started &raquo;</a></div>';

} else {

    echo '<div class="clear-reads-list">';

    $all_completed = true;

    echo '<div id="home_reads" class="cover-list" style="padding-top:21px; padding-left:34px;">';
    foreach($player_reads as $idea) {
        $completion_rate = $this->READ_model->completion_progress($session_source['source__id'], $idea);
        echo view_idea_cover($idea, true, null, $completion_rate);
        if($completion_rate['completion_percentage']!=100 && $all_completed){
            $all_completed = false;
        }
    }
    echo '</div>';

    echo '<div class="doclear">&nbsp;</div>';


    //NEXT
    if(!$all_completed){
        echo '<div class="inline-block margin-top-down pull-right"><a href="/read/next" class="btn btn-read btn-circle">'.$sources__11035[12211]['m_icon'].'</a></div>';
    }


    echo '</div>';


    //READ DELETE ALL (ACCESSIBLE VIA MAIN MENU)
    echo '<div class="clear-reads-list hidden margin-top-down">';
    echo '<div class="alert alert-danger" role="alert">';
    echo '<span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span><b class="read montserrat">DELETE ALL READS?</b>';
    echo '<br /><span class="icon-block">&nbsp;</span>Action cannot be undone.';
    echo '</div>';
    echo '<p style="margin-top:20px;"><a href="javascript:void(0);" onclick="read_clear_all()" class="btn btn-read"><i class="far fa-trash-alt"></i> DELETE ALL</a> or <a href="javascript:void(0)" onclick="$(\'.clear-reads-list\').toggleClass(\'hidden\')" style="text-decoration: underline;">Cancel</a></p>';
    echo '</div>';

}




?>
</div>