<?php
$core_objects = $this->config->item('core_objects');
$tip_index = array(
    1 => 598,
    2 => 606,
    3 => 607,
);
?>
<div class="title"><h4><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <?= $core_objects['level_'.($level-1)]['o_name'] ?> Objective <span id="hb_<?= $tip_index[$level] ?>" class="help_button" intent-id="<?= $tip_index[$level] ?>"></span></h4></div>
<div class="help_body maxout" id="content_<?= $tip_index[$level] ?>"></div>
<div class="form-group label-floating is-empty">
    <input type="text" id="c_objective" maxlength="70" placeholder="<?= ( $level==1 ? 'Get hired as an entry-level web developer' : '') ?>" value="<?= (isset($c_objective) ? $c_objective : '') ?>" class="form-control border">			
</div>