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
echo '<table class="table table-sm table-striped stats-table mini-stats-table">';

foreach($this->Chains->read(array(
    'chainvoid >=' => 0, //Any Chain
    'chainhandletype' => 12273,
), array('chainhashtagoutput'), 100) as $x){

    //HASHTAG
    $stats['hashtags_all']++;
    if($x['chainvoid']>0){
        $stats['hashtags_void']++;
    }

    //Fetch from Cache table:
    $current_value = '#'.$x['hashtaghashtag']."\n".$x['hashtagvalue'].' ';
    foreach($this->Chains->read(array(
        'chainhashtagoutput' => $x['hashtagid'],
        'chainhandletype' => 31835, //Mentions
    ), array('chainhandleinput')) as $x2){
        //$current_value = str_replace('@'.$x2['handlehandle'].' ', '@'.$x2['handleid'].' ', $current_value);
    }


    //Append authors:
    foreach($this->Chains->read(array(
        'chainhashtagoutput' => $x['hashtagid'],
        'chainhandleinput !=' => $x['chainhandlecreator'],
        'chainhandletype' => 4983, //Authors
    ), array('chainhandleinput')) as $x2){
        $current_value .= "\n@".$x2['handlehandle'].( strlen($x2['chainvalue']) > 0 ? " ".$x2['chainvalue'] : "" );
    }


    //Transform URLs:
    foreach($this->Chains->read(array(
        'chainhashtagoutput' => $x['hashtagid'],
        'chainhandleinput !=' => $x['chainhandlecreator'],
        'chainhandletype' => 4256,
    ), array('chainhandleinput')) as $x2){

        $url_key = random_string(8);

        //Create new handle:
        /*
        $added_e = $this->Handles->create(array(
            'handlehandle' => 'URL'.$url_key,
            'handlevalue' => 'URL '.$url_key,
            'handlecover' => 'fas fa-browser',
        ), $x['chainhandlecreator']);
        $current_value .= "\n@".$added_e['handle_create']['handlehandle'];
        */

        $current_value = str_replace($x2['chainvalue'], '@URL'.$url_key, $current_value);
    }


    //Append Media:
    foreach($this->Chains->read(array(
        'chainhashtagoutput' => $x['hashtagid'],
        'chainhandleinput !=' => $x['chainhandlecreator'],
        'chainhandletype IN (' . join(',', array(4258,4260,4259)) . ')' => null,
    ), array('chainhandleinput')) as $x2){
        $current_value .= "\n@".$x2['handlehandle'];
    }


    //Fetch from Cache table:
    foreach($this->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainhashtagoutput' => $x['hashtagid'],
        'chainhandletype IN (' . join(',', array(7545, 26599, 10573, 41949, 1695880, 27984, 43513, 43514, 26600)) . ')' => null,
    ), array('chainhandleinput')) as $x2){
        $current_value .= "\n".$mentions[$x2['chainhandletype']]['m__handle'].$x2['handlehandle'];
    }


    echo '<tr>';
    echo '<td>'.$x['chainid'].'</td>';
    echo '<td>VOID '.$x['chainvoid'].'</td>';
    echo '<td>T@'.$x['chainhandletype'].'</td>';
    echo '<td>C@'.$x['chainhandlecreator'].'</td>';
    echo '<td>'.$x['chainvalue'].'</td>';
    echo '<td>'.nl2br($current_value).'</td>';
    echo '</tr>';

}

//Show stats:
echo '<tr>';
echo '<td>#'.$stats['hashtags_all'].'</td>';
echo '<td>VOID '.$stats['hashtags_void'].'</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '</tr>';



/*
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


echo '<tr>';
echo '<td>@'.$stats['handles_all'].'</td>';
echo '<td>VOID '.$stats['handles_void'].'</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '</tr>';

*/

echo '</table>';


//$_GET['skip_config'] = true;
//view_json($this->Chains->flat_tree($focus_i));
