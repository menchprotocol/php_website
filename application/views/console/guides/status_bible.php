<h1>Status Bible</h1>
<p>A comprehensive list of all statuses for primary objects throughout the Mench platform. First column is the integer value stored in the table, second column is the condensed view of status and the third column is the full status view.</p>
<br /><br />
<?php
$object_names = $this->config->item('object_names');
foreach($object_names as $table=>$object_name){
    echo '<h2>'.$object_name.'</h2>';
    $statuses = status_bible($table);
	foreach($statuses as $intval=>$status){
	    echo '<p style="padding-left:10px;"><span style="width:30px; display:inline-block;">'.$intval.'</span><span style="width:30px; display:inline-block;">'.status_bible($table,$intval,1,'right').'</span>'.status_bible($table,$intval,0,'right').' '.$status['s_desc'].'</p>';
	}
	echo '<br />';
}
?>