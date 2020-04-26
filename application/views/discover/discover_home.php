<?php

$timestamp = time();
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION

?>

<style>
    <?= ( count($player_discoveries) < 2 ? '.discover-sorter {display:none;}' : '' ) //Need 2 or more to sort ?>
</style>
<script>
    //Include some cached sources:
    var clear_discover_url = '<?= '/discover/actionplan_reset_progress/'.$session_en['en_id'].'/'.$timestamp.'/'.md5($session_en['en_id'] . $this->config->item('cred_password_salt') . $timestamp) ?>';

    <?= ( count($player_discoveries) >= 2 ? '$(document).ready(function () {load_discover_sort()});' : '' ) ?>

</script>
<script src="/application/views/discover/discover_home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>



<div class="container">
<?php

//We'll see if we can prove the opposite:
$all_completed = true;


//List Discoveries:
echo '<div id="actionplan_steps" class="list-group no-side-padding">';
foreach ($player_discoveries as $priority => $in) {
    $completion_rate = $this->DISCOVER_model->discover_completion_progress($session_en['en_id'], $in);
    echo echo_in_discover($in, false, null, null, true, $completion_rate);
    if($completion_rate['completion_percentage']!=100 && $all_completed){
        $all_completed = false;
    }
}
echo '</div>';





//HOME
echo '<div class="inline-block margin-top-down pull-left"><a href="/" class="btn btn-discover btn-circle">'.$en_all_11035[12581]['m_icon'].'</a></div>';


//Clear All
return '<div class="pull-middle"><a href="javascript:void(0)" onclick="$(\'.clear-discovery-list\').toggleClass(\'hidden\')" class="pull-right grey"><span class="icon-block-sm">'.$en_all_11035[6415]['m_icon'].'</span></a></div>';


//NEXT
if(!$all_completed){
    echo '<div class="inline-block margin-top-down pull-right"><a href="/discover/next" class="btn btn-discover btn-circle">'.$en_all_11035[12211]['m_icon'].'</a></div>';
}

echo '<div class="doclear">&nbsp;</div>';

//DISCOVER DELETE ALL
echo '<div class="clear-discovery-list hidden margin-top-down">';
echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span><b class="discover montserrat">DELETE ALL DISCOVER COINS?</b><br /><span class="icon-block">&nbsp;</span>Action cannot be undone.</div>';
echo '<p style="margin-top:20px;"><a href="javascript:void(0);" onclick="clear_all_discoveries()" class="btn btn-discover"><i class="far fa-trash-alt"></i> DELETE ALL</a> or <a href="javascript:void(0)" onclick="$(\'.clear-discovery-list\').toggleClass(\'hidden\')" style="text-decoration: underline;">Cancel</a></p>';
echo '</div>';

?>
</div>