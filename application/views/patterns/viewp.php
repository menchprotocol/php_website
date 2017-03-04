<?php //print_r($pattern) ?>

<ol class="breadcrumb" style="margin-top: 10px;">
  	<?php 
  	if($pattern['p_id']>0){
  		echo '<li><a href="/patterns">#patterns</a></li>';
  	}
	foreach ($pattern['parents'] as $row){
		//TODO: Reflect status of the other patterns
		echo '<li><a href="/patterns/'.$row['p_hashtag'].'">#'.$row['p_hashtag'].'</a></li>';
	}
	?>
  <li class="active">#<?= $pattern['p_hashtag'] ?></li>
</ol>

<h1 style="margin-top:0;"><?= $pattern['p_name'] . ($pattern['p_id']>0 ? '<a style="padding: 5px 6px 0 12px; font-size:18px; line-height: 0;" href="/patterns/edit/'.$pattern['p_id'].'"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>' : '') ?></h1>
<p><?= $pattern['p_description'] ?></p>



<div class="list-group" id="child-patterns" style="margin-bottom:0;">
<?php 
if(count($pattern['children'])>0){
	foreach ($pattern['children'] as $row){
		//TODO: Reflect status of the other patterns
		//TODO: Implement sorting later: <span class="glyphicon glyphicon-sort sort_handle spott" aria-hidden="true"></span>
		echo '<a href="/patterns/'.$row['p_hashtag'].'" class="list-group-item">';
			echo '<span class="badge">41 <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>';
			echo '<p>#'.$row['p_hashtag'].'</p>';
			//echo '<p class="list-group-item-text">'.$row['link_reference_notes'].' #'.$row['link_source_hashtag'].' '.$row['link_reference_location'].'</p>';
		echo '</a>';
	}
} else {
	echo '<p class="list-group-item-text">Nothing yet...</p>';
	//echo '<div class="alert alert-warning" role="alert">Nothing found.</div>';
}
echo '<div class="list-group-item" style="padding:2px 3px;">
		<form action="/patterns/add" method="GET">
		<input type="hidden" name="parent_id" value="'.$pattern['p_id'].'" />
		<input type="text" class="form-control autocomplete_pattern" select-action="create_mirror" parent-scope="0" parent-id="'.$pattern['p_id'].'" required="required" name="pattern_name" style="font-size: 16px; border:0; border-radius:0; box-shadow:none; -webkit-box-shadow:none; -webkit-transition:none; transition:none;" placeholder="New pattern...">
		</form>
		</div>';
?>
</div>


