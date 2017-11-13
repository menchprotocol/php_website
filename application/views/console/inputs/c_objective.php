<?php $core_objects = $this->config->item('core_objects'); ?>
<div class="title"><h4><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <?= $core_objects['level_'.($level-1)]['o_name'] ?> Objective</h4></div>
<ul>
    <li>Set a <b>specific</b> and <b>measurable</b> objective in 70 characters or less.</li>
	<?php if($level==1){ ?>
	<li>Do not include time constraints like "in 2 weeks" as total time investment is calculated using the sum of <b><i class="fa fa-clock-o" aria-hidden="true"></i> Estimated Time</b> values for all &nbsp;<b><i class="fa fa-check-square" aria-hidden="true"></i> Tasks</b>.</li>
	<?php } ?>
</ul>
<div class="form-group label-floating is-empty">
    <input type="text" id="c_objective" maxlength="70" placeholder="<?= ( $level==1 ? 'Get hired as an entry-level web developer' : '') ?>" value="<?= (isset($c_objective) ? $c_objective : '') ?>" class="form-control border">			
</div>