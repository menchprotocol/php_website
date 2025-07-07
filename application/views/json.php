<?php

$stats = array(
    'hashtags_all' => 0,
    'hashtags_void' => 0,
    'handles_all' => 0,
    'handles_void' => 0,
);
$mentions = $this->config->item('handles___13550');
$ideas = $this->config->item('handles___4486');

//Translator

foreach($this->Chains->read(array(
    'chainhandletype' => 33600,
), array('chainhashtagoutput')) as $x){

    foreach ($this->Handles->read(array(
        'handleid' => $x['chainhandleinput'],
    )) as $handle) {

        $x['hashtagvalue'] = $x['hashtagvalue']."\n@".$handle['handlehandle'];

        echo $x['hashtagvalue'].'<ht />';

        //Fetch from Cache table:
        /*
        $this->Chains->update($x['chainid'], array(
            'chainhandletype' => 31835,
        ));
        */
    }
}

exit;

echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


foreach($this->Chains->read(array(
    'chainvoid >=' => 0, //Any Chain
    'chainhandletype' => 12273,
), array('chainhashtagoutput')) as $x){

    //Fetch from Cache table:
    $current_value = $x['hashtagvalue'];
    foreach($this->Chains->read(array(
        'chainhashtagoutput' => $x['hashtagid'],
        'chainhandletype IN (' . join(',', array(31835)) . ')' => null,
    ), array('chainhandleinput')) as $x2){
        $current_value .= "\n";
    }

    $new_value = '#'.$x['hashtaghashtag']."\n".$current_value;



    //HASHTAG
    $stats['hashtags_all']++;
    if($x['chainvoid']>0){
        $stats['hashtags_void']++;
    }

    //Fetch from Cache table:
    foreach($this->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainhashtagoutput' => $x['hashtagid'],
        'chainhandletype IN (' . join(',', array(7545, 26599, 10573, 41949, 1695880, 32235, 31835, 27984, 43513, 43514, 26600)) . ')' => null, //HANDLE CHAINS
    ), array('chainhandleinput')) as $x2){
        $new_value .= "\n";
    }


    echo '<tr>';
    echo '<td>'.$x['chainid'].'</td>';
    echo '<td>VOID '.$x['chainvoid'].'</td>';
    echo '<td>@'.$x['chainhandletype'].'</td>';
    echo '<td>'.$x['chainvalue'].'</td>';
    echo '<td>'.$new_value.'</td>';
    echo '</tr>';

}


foreach($this->Chains->read(array(
    'chainvoid >=' => 0, //Any Chain
    'chainhandletype' => 12274,
), array('chainhandleoutput')) as $x){

    $new_value = '';

    //HANDLE
    $stats['hashtags_all']++;
    if($x['chainvoid']>0){
        $stats['hashtags_void']++;
    }

    $new_value = '#';
    //Fetch from Cache table:
    $references = $this->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainhandletype IN (' . join(',', array(12273,12274)) . ')' => null, //HANDLE CHAINS
    ), array());

    echo '<tr>';
    echo '<td>'.$x['chainid'].'</td>';
    echo '<td>VOID '.$x['chainvoid'].'</td>';
    echo '<td>@'.$x['chainhandletype'].'</td>';
    echo '<td>'.$x['chainvalue'].'</td>';
    echo '<td>'.$new_value.'</td>';
    echo '</tr>';

}

//Show STats:
echo '<tr>';
echo '<td>#'.$stats['hashtags_all'].'</td>';
echo '<td>VOID '.$stats['hashtags_void'].'</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '</tr>';


echo '<tr>';
echo '<td>@'.$stats['handles_all'].'</td>';
echo '<td>VOID '.$stats['handles_void'].'</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '</tr>';

echo '</table>';


//$_GET['skip_config'] = true;
//view_json($this->Chains->flat_tree($focus_i));
