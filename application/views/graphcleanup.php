<?php


/*
 *


$_GET['disable_algolia'] = true;

$missing_hashtags = array();
foreach($this->Hashtags->read(array(), 0) as $hashtag_fix){
    if(!count($this->Chains->read(array('chainid' => $hashtag_fix['hashtagid'])))){
        array_push($missing_hashtags, $hashtag_fix);
        //$this->Hashtags->create($hashtag_fix);
    }
}
$missing_handles = array();
foreach($this->Handles->read(array(), 0) as $handle_fix){
    if(!count($this->Chains->read(array('chainid' => $handle_fix['handleid'])))){
        array_push($missing_handles, $handle_fix);
        //$this->Handles->create($handle_fix);
    }
}

view_json(array(
    'hashtags_missing' => count($missing_hashtags),
    'handles_missing' => count($missing_handles),
    //'hashtag_list' => $missing_hashtags,
    //'handles_list' => $missing_handles,
    //'hashtag_settings' => hashtag_settings($focus_i['hashtagstring'], false),
    //'history' => $this->Chains->history($focus_i, $focus_e['handleid']),
));




if(0){

//Hashtag cache update

$edited = 0;
$edited_handles = 0;
foreach($this->Hashtags->read(array(
), 0) as $hashtag_fix){

    $this->Hashtags->update($hashtag_fix['hashtagid'], array(
        'hashtagread' => hashtagread($hashtag_fix['hashtagid'], $hashtag_fix['hashtagvalue']),
    ), $handle_session['handleid']);

}

echo '<hr />Edited ['.$edited.']['.$edited_handles.']<br />';

}

echo '<table>';
foreach($this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___31919')) . ')' => null, //HASHTAG AUTHOR
), array(), 0, 0, array(
    'chainhandletype' => 'ASC',
    'chainhandleinput' => 'ASC',
    'chainhandleoutput' => 'ASC',
    'chainhashtagoutput' => 'ASC',
    'chainhashtaginput' => 'ASC',
    'chainvalue' => 'ASC',
    'chainid' => 'DESC',
)) as $discover){

    $count++;
    if($previous && $previous['chainhandletype']==$discover['chainhandletype'] && $previous['chainhandleinput']==$discover['chainhandleinput'] && $previous['chainhandleoutput']==$discover['chainhandleoutput'] && $previous['chainhashtagoutput']==$discover['chainhashtagoutput'] && $previous['chainhashtaginput']==$discover['chainhashtaginput'] && trim(strtolower($previous['chainvalue']))==trim(strtolower($discover['chainvalue']))){

        $duplicate++;
        echo '<tr><td>'.$previous['chainhandlecreator'].'</td><td>'.$handles___4593[$previous['chainhandletype']]['m__title'].'</td><td>'.$previous['chaintime'].'</td><td>'.$previous['chainhandlecreator'].'</td><td>'.$previous['chainhandleinput'].'</td><td>'.$previous['chainhandleoutput'].'</td><td>'.$previous['chainhashtagoutput'].'</td><td>'.$previous['chainhashtaginput'].'</td><td>'.$previous['chainvalue'].'</td><td>'.$previous['chainhandletype'].'</td><td>'.$previous['chainhandletype'].'</td></tr>';
        echo '<tr style="background-color: #CCC;"><td>'.$discover['chainhandlecreator'].'</td><td>'.$handles___4593[$discover['chainhandletype']]['m__title'].'</td><td>'.$discover['chaintime'].'</td><td>'.$discover['chainhandlecreator'].'</td><td>'.$discover['chainhandleinput'].'</td><td>'.$discover['chainhandleoutput'].'</td><td>'.$discover['chainhashtagoutput'].'</td><td>'.$discover['chainhashtaginput'].'</td><td>'.$discover['chainvalue'].'</td><td>'.$discover['chainhandletype'].'</td><td>'.$discover['chainhandletype'].'</td></tr>';

        $this->db->query("DELETE FROM ideachain WHERE chainid=".$discover['chainid'].";");

    }

    $previous = $discover;
}

echo '</table>';
echo $duplicate.'/'.$count.' are duplicate';



//Various cleanup functions
echo @$_GET['handlehandle'];


if(isset($_GET['action']) && $_GET['action']=='hashtag_messages'){

    //Sync Hashtags & Handles
    $stats = array(
        'cached_hashtags' => 0,
        'active_hashtags' => 0,
        'missing_creation' => 0,
    );

    $edited = 0;
    $edited_handles = 0;
    foreach($this->Hashtags->read(array(
    ), 0) as $hashtag_fix){

        $this->Hashtags->update($hashtag_fix['hashtagid'], array(
            'hashtagread' => hashtagread($hashtag_fix['hashtagid'], $hashtag_fix['hashtagvalue']),
        ), $handle_session['handleid']);

    }

    echo '<hr />Edited ['.$edited.']['.$edited_handles.']<br />';


} elseif(isset($_GET['action']) && $_GET['action']=='import_discovery') {


    //Import Discoveries?
    $flash_message = '';
    if(isset($_GET['handlehandle'])){
        foreach($this->Handles->read(array(
            'LOWER(handlehandle)' => strtolower($_GET['handlehandle']),
        )) as $handle_append){
            $completed = 0;
            foreach($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                'chainhashtaginput' => $is[0]['hashtagid'],
            ), array(), 0) as $x){
                if(!count($this->Chains->read(array(
                    'chainhandleinput' => $handle_append['handleid'],
                    'chainhandleoutput' => $x['chainhandlecreator'],
                    'chainvalue' => $x['chainvalue'],
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                    )))){
                    //Increment Handle chain:
                    $completed++;
                    $this->Chains->create(array(
                        'chainhandlecreator' => ($handle_session ? $handle_session['handleid'] : $x['chainhandlecreator']),
                        'chainhandleinput' => $handle_append['handleid'],
                        'chainhandleoutput' => $x['chainhandlecreator'],
                        'chainvalue' => $x['chainvalue'],
                        'chainhandletype' => 4230,
                    ));
                }
            }

            $flash_message = '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span> '.$completed.' Handles who played this hashtag added to @'.$handle_append['handlehandle'].'</div>';
        }
    }

} else {

    //SHow list of actions:
    echo 'Enter GET action value to process...';

}

*/