<?php

$scanned = 0;
$skipped = 0;
$fixed = 0;

foreach($this->DISCOVER_model->ln_fetch(array(
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
), array(), 0) as $source_link){

    if(filter_var($source_link['ln_content'], FILTER_VALIDATE_URL)){
        //SKIP URLS:
        $skipped++;
        continue;
    }

    $scanned++;
    $detected_ln_type = ln_detect_type($source_link['ln_content']);
    if ($detected_ln_type['status']){
        if(!($detected_ln_type['ln_type_source_id'] == $source_link['ln_type_source_id'])){
            $fixed++;
            $this->DISCOVER_model->ln_update($source_link['ln_id'], array(
                'ln_type_source_id' => $detected_ln_type['ln_type_source_id'],
            ));
        }
    } else {
        echo 'ERROR for Link ID '.$source_link['ln_id'].': '.$detected_ln_type['message'].'<hr />';
    }

}

echo $fixed.'/'.$scanned.' Links Fixed & '.$skipped.' Skipped.';

