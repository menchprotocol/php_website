<?php $core_objects = $this->config->item('core_objects'); ?>
<div class="title"><h4><i class="fa fa-binoculars" aria-hidden="true"></i> <?= $core_objects['level_'.($level-1)]['o_name'] ?> Overview</h4></div>
<ul class="maxout">
	<?php if($level==1){ ?>
	<li>Provide an overview of how your bootcamp plans to accomplish its <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Primary Goal</b>.</li>
	<li><?= $core_objects['level_'.($level-1)]['o_name'] ?> overviews are published on the landing page below the title.</li>
	<?php } elseif($level==2){ ?>
	<li>Provide an overview of <b>how</b> this milestone builds towards the <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Primary Goal</b> and <b>what</b> will students be doing for this milestone.</li>
	<li><?= $core_objects['level_'.($level-1)]['o_name'] ?> overviews are published in the landing page's Milestone section.</li>
	<?php } elseif($level>=3){ ?>
	<li>Give more context on how to execute this <?= strtolower($core_objects['level_'.($level-1)]['o_name']) ?>.</li>
	<li><?= $core_objects['level_'.($level-1)]['o_name'] ?> overview provides instructions on how to execute this task.</li>
	<li><?= $core_objects['level_'.($level-1)]['o_name'] ?> overviews are private & "drip-fed" to students during the bootcamp.</li>
	<?php } ?>
</ul>
<div id="c_todo_overview"><?= ( isset($c_todo_overview) ? $c_todo_overview : '' ) ?></div>
<script> var c_todo_overview_quill = new Quill('#c_todo_overview', setting_full); </script>