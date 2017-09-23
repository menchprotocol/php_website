<h1><?= $this->lang->line('m_name') ?> <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="<?= $this->lang->line('m_desc') ?>"></i></h1>


<?php 
//Attempt to fetch session variables:
echo '<div class="list-group" style="margin-top:30px;">';
foreach($challenges as $c){
    echo '<a href="/marketplace/'.$c['c_id'].'" class="list-group-item"><span class="label label-primary pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>'.echo_title($c['c_objective']).'</a>'; //<span data-toggle="tooltip" title="'.$c['count_users'].' Challenge Administrators" style="width:45px; display:inline-block;"><i class="fa fa-user"></i> '.$c['count_users'].'</span> <span data-toggle="tooltip" title="'.$c['count_runs'].' Challenge Runs" style="width:45px; display:inline-block;"><i class="fa fa-code-fork"></i> '.$c['count_runs'].'</span>
}
echo '</div>';
?>


<?php /*
<a href="/marketplace/new" type="submit" class="btn btn-primary btn-raised btn-round"><i class="fa fa-plus"></i> <?= $this->lang->line('new').' '.$this->lang->line('c_name') ?></a>
*/?>