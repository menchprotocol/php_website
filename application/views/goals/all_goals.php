<h1>#goals</h1>
<p>What we like to accomplish as extracted from our sources:</p>

<table class="table table-striped">
<thead>
	<tr>
		<th>Rank</th>
		<th>Goal</th>
		<th>Achieved From</th>
		<th>Contributes To</th>
	</tr>
</thead>
<tbody>
<?php
$rank = 0;
foreach ($top_goals as $row){
	$rank++;
	echo '<tr>
                <td>'.$rank.'</td>
                <td><a href="/goals/'.$row['hashtag'].'">#'.$row['hashtag'].'</a></td>
                <td>'.$row['count_child'].' Goals</td>
                <td>'.$row['count_parent'].' Goals</td>
		</tr>';
}
?>
</tbody>
</table>