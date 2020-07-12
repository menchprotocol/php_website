<?php

$scanned = 0;
$skipped = 0;
$fixed = 0;

foreach($this->X_model->fetch(array(
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
), array(), 0) as $e_x){

    if(filter_var($e_x['x__message'], FILTER_VALIDATE_URL)){
        //SKIP URLS:
        $skipped++;
        continue;
    }

    $scanned++;
    $detected_x_type = x_detect_type($e_x['x__message']);
    if ($detected_x_type['status']){
        if(!($detected_x_type['x__type'] == $e_x['x__type'])){
            $fixed++;
            $this->X_model->update($e_x['x__id'], array(
                'x__type' => $detected_x_type['x__type'],
            ));
        }
    } else {
        echo 'ERROR for Transaction ID '.$e_x['x__id'].': '.$detected_x_type['message'].'<hr />';
    }

}

echo $fixed.'/'.$scanned.' Transactions Fixed & '.$skipped.' Skipped.';

