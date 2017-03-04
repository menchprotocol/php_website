<h1>US</h1>
<p>Here is a list of all contributors to US:</p>


<div class="list-group">
	<?php
	foreach ($top_users as $row){
		echo '<a href="/us/'.$row['username'].'" class="list-group-item"><span class="badge">'.$row['points'].' <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>@'.$row['username'].'</a>';
	}
	?>
	<div class="list-group-item" style="padding:2px 3px;">
		<form action="us/invite" method="GET">
		<div class="easy-autocomplete" style="width: 692px;"><input type="text" class="form-control autocomplete_pattern" select-action="create_mirror" parent-scope="0" parent-id="485" required="required" name="pattern_name" style="font-size: 16px; border:0; border-radius:0; box-shadow:none; -webkit-box-shadow:none; -webkit-transition:none; transition:none;" placeholder="Invite by email..." id="eac-4555" autocomplete="off"><div class="easy-autocomplete-container" id="eac-container-eac-4555"><ul></ul></div></div>
		</form>
	</div>
</div>