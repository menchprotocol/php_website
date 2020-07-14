<?php
/*
 *
 * This plugin prepares a PHP-friendly text to be copied
 * to platform_cache.php (which is auto loaded) to offer
 * instant access of some sources used in platform logic
 *
 * */

//First first all sources that have Cache in PHP Config @4527 as their parent:
$config_es = $this->X_model->fetch(array(
    'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'x__up' => 4527,
), array('x__down'), 0);

echo htmlentities('<?php').'<br /><br />';
echo 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'.'<br /><br />';

echo '/*<br />
 * Keep a cache of certain parts of the idea for faster processing<br />
 * See source @4527 for more details<br />
 *<br />
 */<br /><br />';


//PLATFORM STATS
echo '//Generated '.date("Y-m-d H:i:s").' PST<br />';



//CACHE MENCH COINS COUNT:
foreach($this->config->item('e___12467') as $x__type => $m) {
    echo '$config[\'s___'.$x__type.'\'] = '.x_stats_count($x__type).'; //'.$m['m_name'].'<br />';
}


//CONFIG VARS
foreach($config_es as $en){

    //Now fetch all its children:
    $children = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        'x__up' => $en['x__down'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    ), array('x__down'), 0, 0, array('x__sort' => 'ASC', 'e__title' => 'ASC'));


    //Find common base, if allowed:
    $common_prefix = i_calc_common_prefix($children, 'e__title');

    //Generate raw IDs:
    $child_ids = array();
    foreach($children as $child){
        array_push($child_ids , $child['e__id']);
    }

    echo '<br />//'.$en['e__title'].':<br />';
    echo '$config[\'n___'.$en['x__down'].'\'] = array('.join(',',$child_ids).');<br />';
    echo '$config[\'e___'.$en['x__down'].'\'] = array(<br />';
    foreach($children as $child){

        //Do we have an omit command?
        if(strlen($common_prefix) > 0){
            $child['e__title'] = trim(substr($child['e__title'], strlen($common_prefix)));
        }

        //Fetch all parents for this child:
        $child_parent_ids = array(); //To be populated soon
        $child_parents = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
            'x__down' => $child['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        ), array('x__up'), 0);
        foreach($child_parents as $cp_en){
            array_push($child_parent_ids, bigintval($cp_en['e__id']));
        }

        echo '&nbsp;&nbsp;&nbsp;&nbsp; '.$child['e__id'].' => array(<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_icon\' => \''.htmlentities($child['e__icon']).'\',<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_name\' => \''.htmlentities(str_replace('\'','\\\'',$child['e__title'])).'\',<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_desc\' => \''.htmlentities(str_replace('\'','\\\'',$child['x__message'])).'\',<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_parents\' => array('.join(',',$child_parent_ids).'),<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp; ),<br />';

    }
    echo ');<br />';
}

