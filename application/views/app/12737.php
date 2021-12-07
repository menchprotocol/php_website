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

echo $fixed.'/'.$scanned.' Transactions Fixed & '.$skipped.' Skipped.<hr />';


//Now find deleted sources with active links:
foreach($this->X_model->fetch(array(
    'e__type' => 6178,
), array(), 0) as $e){
    $active_links = count($this->X_model->fetch(array(
        '(x__source='.$e['e__id'].' OR x__up='.$e['e__id'].' OR x__down='.$e['e__id'].')' => null, //SOURCE LINKS
    ), array(), 0));
    if($active_links > 0){
        echo '<a href="/@'.$e['e__id'].'">@'.$e['e__id'].' '.$e['e__title'].'</a> Has '.$active_links.' Active Links<br />';
    }
}




