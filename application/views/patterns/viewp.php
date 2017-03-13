<?php //print_r($pattern) ?>

<h4 style="margin:15px 0 0 0;">#<?= $pattern['p_hashtag'] ?> ID <?= $pattern['p_id'] ?></h4>
<h1 style="margin:5px 0 15px 0;"><?= $pattern['p_name'] . ($pattern['p_id']>0 ? '<a style="padding: 5px 6px 0 12px; font-size:18px; line-height: 0;" href="/patterns/edit/'.$pattern['p_id'].'"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>' : '') ?></h1>




<div class="list-group">
<?php 
foreach ($pattern['parents'] as $row){
	//TODO: Reflect status of the other patterns
	echo '<a href="/patterns/'.$row['p_hashtag'].'" class="list-group-item"><span class="badge" style="float: left; margin-right: 10px;"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> #'.$row['p_hashtag'].'</span> '.$row['link_reference_notes'].'</a>';
}
?>
</div>


<div class="list-group">
<?php 
if(count($pattern['children'])>0){
	foreach ($pattern['children'] as $row){
		//TODO: Reflect status of the other patterns
		//TODO: Implement sorting later: <span class="glyphicon glyphicon-sort sort_handle spott" aria-hidden="true"></span>
		echo '<a href="/patterns/'.$row['p_hashtag'].'" class="list-group-item"><span class="badge">#'.$row['p_hashtag'].' <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>'.$row['p_name'].'</a>';
	}
} else {
	echo '<p class="list-group-item-text">Nothing yet...</p>';
	//echo '<div class="alert alert-warning" role="alert">Nothing found.</div>';
}
echo '<div class="list-group-item" style="padding:2px 3px;">
		<form action="us/invite" method="GET">
		<div class="easy-autocomplete" style="width: 692px;"><input type="text" class="form-control autocomplete_pattern" select-action="create_mirror" parent-scope="0" parent-id="485" required="required" name="pattern_name" style="font-size: 16px; border:0; border-radius:0; box-shadow:none; -webkit-box-shadow:none; -webkit-transition:none; transition:none;" placeholder="Invite by email..." id="eac-4555" autocomplete="off"><div class="easy-autocomplete-container" id="eac-container-eac-4555"><ul></ul></div></div>
		</form>
	</div>';
?>

</div>


