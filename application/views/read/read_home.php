<?php

$timestamp = time();
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH  NAVIGATION

?>


<script>
    //Include some cached sources:
    var clear_read_url = '<?= '/read/actionplan_reset_progress/'.$session_en['en_id'].'/'.$timestamp.'/'.md5($session_en['en_id'] . $this->config->item('cred_password_salt') . $timestamp) ?>';

    <?= ( count($player_reads) >= 2 ? '$(document).ready(function () {load_read_sort()});' : '' ) ?>

</script>
<script src="/application/views/read/read_home.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
<?php

if(!$session_en){

    echo '<div style="padding:10px 0 20px;"><a href="/source/sign?url=/read" class="btn btn-read montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to get started.</div>';

} else {


    //List Reads:
    echo '<div id="actionplan_steps" class="list-group no-side-padding">';
    foreach ($player_reads as $priority => $ln) {
        echo echo_in_read($ln, false, null, null, null, true);
    }
    echo '</div>';


    //Call to Actions:
    echo '<div style="margin-top: 10px;">';

        //Add New Read:
        echo '<a href="/" class="btn btn-read" title="'.$en_all_11035[12581]['m_name'].'">'.$en_all_11035[12581]['m_icon'].'</a>&nbsp;&nbsp;';


        //Next Read:
        echo '<a href="/read/next" class="btn btn-read">'.$en_all_11035[12211]['m_name'].' '.$en_all_11035[12211]['m_icon'].'</a>&nbsp;&nbsp;';


        //Give option to delete all:
        echo '<a href="javascript:void(0)" onclick="$(\'.clear-reading-list\').toggleClass(\'hidden\')" class="pull-right grey"><span class="icon-block-sm" style="margin-top: 9px;">'.$en_all_11035[6415]['m_icon'].'</span></a>';

        echo '<div class="clear-reading-list hidden" style="padding:34px 0;">';
        echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fad fa-exclamation-triangle read"></i></span><b class="read montserrat">DELETE ALL READ COINS?</b><br /><span class="icon-block">&nbsp;</span>Action cannot be undone.</div>';

        echo '<p style="margin-top:20px;"><a href="javascript:void(0);" onclick="clear_all_reads()" class="btn btn-read"><i class="far fa-trash-alt"></i> DELETE ALL</a> or <a href="javascript:void(0)" onclick="$(\'.clear-reading-list\').toggleClass(\'hidden\')" style="text-decoration: underline;">Cancel</a></p>';
        echo '</div>';


    echo '</div>';


}
?>
</div>