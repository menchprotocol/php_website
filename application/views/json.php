<?php

//Translator
echo '<table class="table table-sm table-striped stats-table mini-stats-table">';
foreach($this->Chains->read(array(
    'chainvoid >=' => 0, //Any Chain
    'chainhandletype IN (' . join(',', array(12273,12274,4256)) . ')' => null, //HANDLE CHAINS
), array()) as $x){

    $new_value = '';
    if($x['chainhandletype']==12273){
        //Fetch from Cache table:

    } elseif($x['chainhandletype']==12274){
        //Fetch from Cache table:
    } elseif($x['chainhandletype']==4256){
        //URL
    } elseif($x['chainhandletype']==){
        //
    } elseif($x['chainhandletype']==){
        //
    } elseif($x['chainhandletype']==){
        //
    } elseif($x['chainhandletype']==){
        //
    } elseif($x['chainhandletype']==){
        //
    } elseif($x['chainhandletype']==){
        //
    } elseif($x['chainhandletype']==){
        //
    }

    echo '<tr>';
    echo '<td>'.$x['chainid'].'</td>';
    echo '<td>VOID '.$x['chainvoid'].'</td>';
    echo '<td>@'.$x['chainhandletype'].'</td>';
    echo '<td>'.$x['chainvalue'].'</td>';
    echo '<td>'.$new_value.'</td>';
    echo '</tr>';
}

echo '</table>';


//$_GET['skip_config'] = true;
//view_json($this->Chains->flat_tree($focus_i));
