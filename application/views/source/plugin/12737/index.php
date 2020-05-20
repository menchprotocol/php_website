<?php

$scanned = 0;
$skipped = 0;
$fixed = 0;

foreach($this->READ_model->fetch(array(
    'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
), array(), 0) as $source_link){

    if(filter_var($source_link['read__message'], FILTER_VALIDATE_URL)){
        //SKIP URLS:
        $skipped++;
        continue;
    }

    $scanned++;
    $detected_read_type = read_detect_type($source_link['read__message']);
    if ($detected_read_type['status']){
        if(!($detected_read_type['read__type'] == $source_link['read__type'])){
            $fixed++;
            $this->READ_model->update($source_link['read__id'], array(
                'read__type' => $detected_read_type['read__type'],
            ));
        }
    } else {
        echo 'ERROR for Link ID '.$source_link['read__id'].': '.$detected_read_type['message'].'<hr />';
    }

}

echo $fixed.'/'.$scanned.' Links Fixed & '.$skipped.' Skipped.';

