<?php
/*
 *
 * This plugin prepares a PHP-friendly text to be copied
 * to platform_cache.php (which is auto loaded) to offer
 * instant access of some sources used in platform logic
 *
 * */

//First first all sources that have Cache in PHP Config @4527 as their parent:
$config_sources = $this->READ_model->fetch(array(
    'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
    'read__up' => 4527,
), array('read__down'), 0);

echo htmlentities('<?php').'<br /><br />';
echo 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'.'<br /><br />';

echo '/*<br />
 * Keep a cache of certain parts of the idea for faster processing<br />
 * See source @4527 for more details<br />
 *<br />
 */<br /><br />';



//PLATFORM STATS
$cache_timestamp = time();
$reads = $this->READ_model->fetch(array(), array(), 0, 0, array(), 'COUNT(read__id) as totals');
$read_coins = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_6255')) . ')' => null, //READ COIN
), array(), 0, 0, array(), 'COUNT(read__id) as totals');
$idea_coins = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
    'read__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    ' EXISTS (SELECT 1 FROM mench_interactions WHERE source__id=read__down AND read__up=4430 AND read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ') AND read__status IN ('.join(',', $this->config->item('sources_id_7359')) /* PUBLIC */.')) ' => null,
), array(), 0, 0, array(), 'COUNT(read__id) as totals');
$source_coins = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12274')) . ')' => null, //SOURCE COIN
), array(), 0, 0, array(), 'COUNT(read__id) as totals');


echo '//Generated '.date("Y-m-d H:i:s", $cache_timestamp).' PST<br />';

//Append more data:
echo '<br />//PLATFORM STATS:<br />';
echo '$config[\'cache_timestamp\'] = '.$cache_timestamp.';<br />';
echo '$config[\'cache_count_interaction\'] = '.$reads[0]['totals'].';<br />';
echo '$config[\'cache_count_read\'] = '.$read_coins[0]['totals'].';<br />';
echo '$config[\'cache_count_idea\'] = '.$idea_coins[0]['totals'].';<br />';
echo '$config[\'cache_count_source\'] = '.$source_coins[0]['totals'].';<br />';
echo '<br /><br />';


//CONFIG VARS
foreach($config_sources as $en){

    //Now fetch all its children:
    $children = $this->READ_model->fetch(array(
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
        'read__up' => $en['read__down'],
        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
    ), array('read__down'), 0, 0, array('read__sort' => 'ASC', 'source__title' => 'ASC'));


    //Find common base, if allowed:
    $common_prefix = ( in_array($en['read__down'], $this->config->item('sources_id_12588')) ? null : idea_calc_common_prefix($children, 'source__title') );

    //Generate raw IDs:
    $child_ids = array();
    foreach($children as $child){
        array_push($child_ids , $child['source__id']);
    }

    echo '<br />//'.$en['source__title'].':<br />';
    echo '$config[\'sources_id_'.$en['read__down'].'\'] = array('.join(',',$child_ids).');<br />';
    echo '$config[\'sources__'.$en['read__down'].'\'] = array(<br />';
    foreach($children as $child){

        //Do we have an omit command?
        if(strlen($common_prefix) > 0){
            $child['source__title'] = trim(substr($child['source__title'], strlen($common_prefix)));
        }

        //Fetch all parents for this child:
        $child_parent_ids = array(); //To be populated soon
        $child_parents = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
            'read__down' => $child['source__id'],
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
        ), array('read__up'), 0);
        foreach($child_parents as $cp_en){
            array_push($child_parent_ids, intval($cp_en['source__id']));
        }

        echo '&nbsp;&nbsp;&nbsp;&nbsp; '.$child['source__id'].' => array(<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_icon\' => \''.htmlentities($child['source__icon']).'\',<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_name\' => \''.htmlentities(str_replace('\'','\\\'',$child['source__title'])).'\',<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_desc\' => \''.htmlentities(str_replace('\'','\\\'',$child['read__message'])).'\',<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_parents\' => array('.join(',',$child_parent_ids).'),<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp; ),<br />';

    }
    echo ');<br />';
}

