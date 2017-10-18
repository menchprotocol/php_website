<h1>Status Bible</h1>
<p>A comprehensive list of all statuses for primary objects throughout the Mench platform. First column is the integer value stored in the table, second column is the condensed view of status and the third column is the full status view.</p>
<br /><br />
<?php
$core_objects = $this->config->item('core_objects');
foreach($core_objects as $object_id=>$co){
    $statuses = status_bible($object_id);
    if($statuses){
        echo '<h2>'.$co['o_name'].'</h2>';
        foreach($statuses as $intval=>$status){
            echo '<p style="padding-left:10px;"><span style="width:30px; display:inline-block;">'.$intval.'</span><span style="width:30px; display:inline-block;">'.status_bible($object_id,$intval,1,'right').'</span>'.status_bible($object_id,$intval,0,'right').' '.$status['s_desc'].'</p>';
        }
    }
	echo '<br />';
}
?>