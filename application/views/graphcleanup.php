<?php


/*
 *


$_GET['disable_algolia'] = true;

$missing_ideas = array();
foreach($this->Ideas->read(array(), 0) as $idea_fix){
    if(!count($this->Chains->read(array('chainid' => $idea_fix['ideaid'])))){
        array_push($missing_ideas, $idea_fix);
        //$this->Ideas->create($idea_fix);
    }
}
$missing_sources = array();
foreach($this->Sources->read(array(), 0) as $source_fix){
    if(!count($this->Chains->read(array('chainid' => $source_fix['sourceid'])))){
        array_push($missing_sources, $source_fix);
        //$this->Sources->create($source_fix);
    }
}

view_json(array(
    'ideas_missing' => count($missing_ideas),
    'sources_missing' => count($missing_sources),
    //'idea_list' => $missing_ideas,
    //'sources_list' => $missing_sources,
    //'idea_settings' => idea_settings($focus_i['ideahashtag'], false),
    //'history' => $this->Chains->history($focus_i, $focus_e['sourceid']),
));




if(0){

//Idea cache update

$edited = 0;
$edited_sources = 0;
foreach($this->Ideas->read(array(
), 0) as $idea_fix){

    $this->Ideas->update($idea_fix['ideaid'], array(
        'ideacache' => ideacache($idea_fix['ideaid'], $idea_fix['ideavalue']),
    ), $source_session['sourceid']);

}

echo '<hr />Edited ['.$edited.']['.$edited_sources.']<br />';

}

echo '<table>';
foreach($this->Chains->read(array(
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___31919')) . ')' => null, //IDEA AUTHOR
), array(), 0, 0, array(
    'chainsourcetype' => 'ASC',
    'chainsourceup' => 'ASC',
    'chainsourcedown' => 'ASC',
    'chainidearight' => 'ASC',
    'chainidealeft' => 'ASC',
    'chainvalue' => 'ASC',
    'chainid' => 'DESC',
)) as $discover){

    $count++;
    if($previous && $previous['chainsourcetype']==$discover['chainsourcetype'] && $previous['chainsourceup']==$discover['chainsourceup'] && $previous['chainsourcedown']==$discover['chainsourcedown'] && $previous['chainidearight']==$discover['chainidearight'] && $previous['chainidealeft']==$discover['chainidealeft'] && trim(strtolower($previous['chainvalue']))==trim(strtolower($discover['chainvalue']))){

        $duplicate++;
        echo '<tr><td>'.$previous['chainsourcecreator'].'</td><td>'.$sources___4593[$previous['chainsourcetype']]['m__title'].'</td><td>'.$previous['chaintime'].'</td><td>'.$previous['chainsourcecreator'].'</td><td>'.$previous['chainsourceup'].'</td><td>'.$previous['chainsourcedown'].'</td><td>'.$previous['chainidearight'].'</td><td>'.$previous['chainidealeft'].'</td><td>'.$previous['chainvalue'].'</td><td>'.$previous['chainsourcetype'].'</td><td>'.$previous['chainsourcetype'].'</td></tr>';
        echo '<tr style="background-color: #CCC;"><td>'.$discover['chainsourcecreator'].'</td><td>'.$sources___4593[$discover['chainsourcetype']]['m__title'].'</td><td>'.$discover['chaintime'].'</td><td>'.$discover['chainsourcecreator'].'</td><td>'.$discover['chainsourceup'].'</td><td>'.$discover['chainsourcedown'].'</td><td>'.$discover['chainidearight'].'</td><td>'.$discover['chainidealeft'].'</td><td>'.$discover['chainvalue'].'</td><td>'.$discover['chainsourcetype'].'</td><td>'.$discover['chainsourcetype'].'</td></tr>';

        $this->db->query("DELETE FROM ideachain WHERE chainid=".$discover['chainid'].";");

    }

    $previous = $discover;
}

echo '</table>';
echo $duplicate.'/'.$count.' are duplicate';



//Various cleanup functions
echo @$_GET['sourcehandle'];


if(isset($_GET['action']) && $_GET['action']=='idea_messages'){

    //Sync Ideas & Sources
    $stats = array(
        'cached_ideas' => 0,
        'active_ideas' => 0,
        'missing_creation' => 0,
    );

    $edited = 0;
    $edited_sources = 0;
    foreach($this->Ideas->read(array(
    ), 0) as $idea_fix){

        $this->Ideas->update($idea_fix['ideaid'], array(
            'ideacache' => ideacache($idea_fix['ideaid'], $idea_fix['ideavalue']),
        ), $source_session['sourceid']);

    }

    echo '<hr />Edited ['.$edited.']['.$edited_sources.']<br />';


} elseif(isset($_GET['action']) && $_GET['action']=='import_discovery') {


    //Import Discoveries?
    $flash_message = '';
    if(isset($_GET['sourcehandle'])){
        foreach($this->Sources->read(array(
            'LOWER(sourcehandle)' => strtolower($_GET['sourcehandle']),
        )) as $source_append){
            $completed = 0;
            foreach($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'chainidealeft' => $is[0]['ideaid'],
            ), array(), 0) as $x){
                if(!count($this->Chains->read(array(
                    'chainsourceup' => $source_append['sourceid'],
                    'chainsourcedown' => $x['chainsourcecreator'],
                    'chainvalue' => $x['chainvalue'],
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                    )))){
                    //Increment Source chain:
                    $completed++;
                    $this->Chains->create(array(
                        'chainsourcecreator' => ($source_session ? $source_session['sourceid'] : $x['chainsourcecreator']),
                        'chainsourceup' => $source_append['sourceid'],
                        'chainsourcedown' => $x['chainsourcecreator'],
                        'chainvalue' => $x['chainvalue'],
                        'chainsourcetype' => 4230,
                    ));
                }
            }

            $flash_message = '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span> '.$completed.' Sources who played this idea added to @'.$source_append['sourcehandle'].'</div>';
        }
    }

} else {

    //SHow list of actions:
    echo 'Enter GET action value to process...';

}

*/