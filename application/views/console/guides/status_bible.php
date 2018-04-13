<h1>Status Bible</h1>
<p>A comprehensive list of all statuses for primary objects throughout the Mench platform. First column is the integer value stored in the table, second column is the condensed view of status and the third column is the full status view.</p>
<br /><br />
<?php
foreach(status_bible() as $object_id=>$statuses){
    echo '<h2>'.$this->lang->line('obj_'.$object_id.'_name').' ('.$object_id.')</h2>';
    foreach($statuses as $intval=>$status){
        echo '<p style="padding-left:10px;"><span style="width:30px; display:inline-block;">['.$intval.']</span>'.status_bible($object_id,$intval,0,'right').( isset($status['s_desc']) ? ' '.nl2br($status['s_desc']) : '').'</p>';
    }
	echo '<br />';

}
?>