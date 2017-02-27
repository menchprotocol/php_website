<h1>Sources</h1>
<p>The list of all credible sources of knowledge used to link goal causality:</p>

<table class="table table-striped">
<thead>
	<tr>
		<th>Rank</th>
		<th>Source</th>
		<th>Type</th>
		<th>Links</th>
		<th>Authors</th>
	</tr>
</thead>
<tbody>
<?php
$rank = 0;
foreach ($top_sources as $row){
	$rank++;
	echo '<tr>
                <td>'.$rank.'</td>
                <td><a href="/sources/'.$row['hashtag'].'">#'.$row['hashtag'].'</a></td>
                <td>'.source_types($row['type_id']).'</td>
                <td>'.$row['reference_count'].'</td>
				<td>';
				foreach ($row['authors'] as $author){
					echo '<a href="/us/'.$author.'">@'.$author.'</a> ';
				}
                echo '</td>
              </tr>';
}
?>

</tbody>
</table>