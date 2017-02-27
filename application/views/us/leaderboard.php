<h1>US</h1>
<p>Here is a list of all contributors to US:</p>

<table class="table table-striped">
<thead>
	<tr>
		<th>Rank</th>
		<th>User</th>
		<th>Goals</th>
		<th>Links</th>
		<th>Sources</th>
		<th>Authors</th>
	</tr>
</thead>
<tbody>
<?php
$rank = 0;
foreach ($top_users as $row){
	$rank++;
	echo '<tr>
                <td>'.$rank.'</td>
                <td><a href="/us/'.$row['username'].'">@'.$row['username'].'</a></td>
                <td>'.$row['count_goals'].'</td>
                <td>'.$row['count_links'].'</td>
				<td>'.$row['count_sources'].'</td>
				<td>'.$row['count_authors'].'</td>
				
               </tr>';
}
//<th>Points</th>
//<td><b>'.$row['points'].'</b></td>
//<p><b>Points:</b> Goals/Sources = 5 &nbsp;&nbsp;&nbsp; Links/Authors = 2</p>
?>

</tbody>
</table>

