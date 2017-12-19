<script>


$(document).ready(function() {

});

</script>

<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />

<div class="help_body maxout below_h" id="content_629"></div>


<div class="list-group maxout">
<?php
$admin_ids = array();
foreach($bootcamp['b__admins'] as $admin){
    echo echo_br($admin);
    array_push($admin_ids,$admin['u_id']);
}
$mench_advisers = $this->config->item('mench_advisers');
//Fetch the profile of the hard-coded mench advisery team
//Currently: Miguel & Shervin
$mench_advisers = $this->Db_model->u_fetch(array(
    'u_id IN ('.join(',',$mench_advisers).')' => null,
));

foreach($mench_advisers as $adviser){
    if(in_array($adviser['u_id'],$admin_ids)){
        continue;
    }
    echo echo_br(array_merge($adviser,array(
        'ba_id' => 0,
        'ba_u_id' => $adviser['u_id'],
        'ba_status' => 1, //Advisery status
        'ba_b_id' => $bootcamp['b_id'],
        'ba_team_display' => 'f', //Advisers are not shown on the landing page
    )));
}
?>
</div>
<p>Contact us to add new team members.</p>