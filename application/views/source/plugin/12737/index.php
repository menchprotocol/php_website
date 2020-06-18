<?php

$scanned = 0;
$skipped = 0;
$fixed = 0;

foreach($this->DISCOVER_model->fetch(array(
    'x__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
), array(), 0) as $source_link){

    if(filter_var($source_link['x__message'], FILTER_VALIDATE_URL)){
        //SKIP URLS:
        $skipped++;
        continue;
    }

    $scanned++;
    $detected_read_type = read_detect_type($source_link['x__message']);
    if ($detected_read_type['status']){
        if(!($detected_read_type['x__type'] == $source_link['x__type'])){
            $fixed++;
            $this->DISCOVER_model->update($source_link['x__id'], array(
                'x__type' => $detected_read_type['x__type'],
            ));
        }
    } else {
        echo 'ERROR for Link ID '.$source_link['x__id'].': '.$detected_read_type['message'].'<hr />';
    }

}

echo $fixed.'/'.$scanned.' Links Fixed & '.$skipped.' Skipped.';

