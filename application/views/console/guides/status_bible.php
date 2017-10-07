<h1>Status Bible</h1>
<p>A comprehensive list of all statuses for all database tables. First column is the integer value stored in the table, while second column is the title and description. Hover over titles to see descriptions.</p>
<br /><br />
<?php
$status_bible = status_bible();
foreach($status_bible as $table=>$statuses){
    echo '<h2>Object: '.$table.'</h2>';
	foreach($statuses as $intval=>$status){
	    echo '<p style="padding-left:60px;"><span style="width:60px; display:inline-block;">'.$intval.'</span><span style="width:40px; display:inline-block;">'.status_bible($table,$intval,1).'</span>'.status_bible($table,$intval).'</p>';
	}
	echo '<br />';
}
?>