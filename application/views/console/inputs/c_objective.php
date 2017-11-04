<?php $core_objects = $this->config->item('core_objects'); ?>
<div class="title"><h4><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <?= $core_objects['level_'.($level-1)]['o_name'] ?> Primary Goal</h4></div>
<ul>
    <li>Set a <b>specific</b> and <b>measurable</b> primary goal in 70 characters or less.</li>
	<?php if($level==1){ ?>
	<li>Do not include time constraints like "in 2 weeks" as we'll auto calculate by counting your Milestones.</li>
	<li>Students who execute all milestones by the final date are covered by our platform-wide <a href="https://support.mench.co/hc/en-us/articles/115002080031" target="_blank"><u>Tuition Guarantee <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
	<?php } ?>
</ul>
<div class="form-group label-floating is-empty">
    <input type="text" id="c_objective" maxlength="70" placeholder="<?= ( $level==1 ? 'Get hired as an entry-level web developer' : '') ?>" value="<?= (isset($c_objective) ? $c_objective : '') ?>" class="form-control border">			
</div>