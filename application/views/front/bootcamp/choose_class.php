<p style="border-bottom:3px solid #000; font-weight:bold; padding-bottom:10px; display:block;"><?= $title ?></p>

<div class="section s1" style="display:block;">
	<p>Choose which class you'd like to join:</p>
	
	<div class="list-group" style="font-size: 0.8em;">
	<?php
	foreach($active_classes as $class){
	    echo '<a href="/'.$bootcamp['b_url_key'].'/apply/'.$class['r_id'].'" class="list-group-item" style="padding:10px 5px 12px 10px;">';
	    echo '<span class="pull-right"><span class="badge badge-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
	    echo '<i class="fa fa-calendar" aria-hidden="true"></i> '.time_format($class['r_start_date'],2).' &nbsp; ';
	    echo '<i class="fa fa-usd" aria-hidden="true"></i> '.(strlen($class['r_usd_price'])>0 ? number_format($class['r_usd_price']) : 'FREE');
	    echo '</a>';
	}
	?>
	</div>
	
	<p>Or go back to the <a href="/<?= $bootcamp['b_url_key'] ?>">Bootcamp Page</a>.</p>
    
</div>


