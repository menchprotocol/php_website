<?php //print_r($goal) ?>

<ol class="breadcrumb" style="margin-top: 10px;">
  	<?php 
  	if($goal['goal_id']>0){
  		echo '<li><a href="/goals">#goals</a></li>';
  	}
	foreach ($goal['parents'] as $row){
		//TODO: Reflect status of the other goal
		echo '<li><a href="/goals/'.$row['goal_hashtag'].'">#'.$row['goal_hashtag'].'</a></li>';
	}
	?>
  <li class="active">#<?= $goal['goal_hashtag'] ?></li>
</ol>

<h1 style="margin-top:0;"><?= $goal['goal_name'] . ($goal['goal_id']>0 ? '<a style="padding: 5px 6px 0 12px; font-size:18px; line-height: 0;" href="/goals/edit/'.$goal['goal_id'].'"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>' : '') ?></h1>
<p><?= $goal['goal_description'] . ( strlen($goal['goal_also_known_as'])>0 ? ' Also known as '.$goal['goal_also_known_as'].'.' : '' ) ?></p>



<div class="list-group" id="child-patterns" style="margin-bottom:0;">
<?php 
if(count($goal['children'])>0){
	foreach ($goal['children'] as $row){
		//TODO: Reflect status of the other goal
		//TODO: Implement sorting later: <span class="glyphicon glyphicon-sort sort_handle spott" aria-hidden="true"></span>
		echo '<a href="/goals/'.$row['goal_hashtag'].'" class="list-group-item">';
			echo '<span class="badge">41 <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>';
			echo '<h4 class="list-group-item-heading">#'.$row['goal_hashtag'].'</h4>';
			echo '<p class="list-group-item-text">'.$row['link_reference_notes'].' #'.$row['link_source_hashtag'].' '.$row['link_reference_location'].'</p>';
		echo '</a>';
	}
} else {
	echo '<p class="list-group-item-text">Nothing yet...</p>';
	//echo '<div class="alert alert-warning" role="alert">Nothing found.</div>';
}
echo '<div class="list-group-item" style="padding:2px 3px;">
		<form action="/patterns/add" method="GET">
		<input type="hidden" name="parent_id" value="'.$goal['goal_id'].'" />
		<input type="text" class="form-control autocomplete_pattern" select-action="create_mirror" parent-scope="0" parent-id="'.$goal['goal_id'].'" required="required" name="pattern_name" style="font-size: 16px; border:0; border-radius:0; box-shadow:none; -webkit-box-shadow:none; -webkit-transition:none; transition:none;" placeholder="New pattern...">
		</form>
		</div>';
?>
</div>


