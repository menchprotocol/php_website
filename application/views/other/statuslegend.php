<div class="maxout">
<?php
echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-shapes"></i> Status Legend</h5>';

foreach(echo_status() as $object_id=>$statuses){
    echo '<h3 style="margin-bottom: 15px; border-bottom: 2px solid #2f2739;">'.$this->lang->line('obj_'.$object_id.'_name').' ['.$object_id.']</h3>';
    foreach($statuses as $intval=>$status){
        echo '<p style="padding-left:10px;"><span style="width:30px; display:inline-block;">['.$intval.']</span>'.echo_status($object_id,$intval,0,'right').( isset($status['s_desc']) ? ' '.nl2br($status['s_desc']) : '').'</p>';
    }
	echo '<br />';

}
?>
</div>