<?php
/*
 *
 * This plugin prepares a PHP-friendly text to be copied
 * to platform_cache.php (which is auto loaded) to offer
 * instant access of some sources used in platform logic
 *
 * */

//First first all sources that have Cache in PHP Config @4527 as their parent:
$config_ens = $this->READ_model->fetch(array(
    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
    'ln_profile_source_id' => 4527,
), array('en_portfolio'), 0);

echo htmlentities('<?php').'<br /><br />';
echo 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'.'<br /><br />';

echo '/*<br />
 * Keep a cache of certain parts of the idea for faster processing<br />
 * See source @4527 for more details<br />
 *<br />
 */<br /><br />';



//PLATFORM STATS
$cache_timestamp = time();
$transactions = $this->READ_model->fetch(array(), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
$read_coins = $this->READ_model->fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
$in_coins = $this->READ_model->fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12273')) . ')' => null, //IDEA COIN
    'ln_profile_source_id >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
$en_coins = $this->READ_model->fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //SOURCE COIN
), array(), 0, 0, array(), 'COUNT(ln_id) as totals');


echo '//Generated '.date("Y-m-d H:i:s", $cache_timestamp).' PST<br />';

//Append more data:
echo '<br />//PLATFORM STATS:<br />';
echo '$config[\'cache_timestamp\'] = '.$cache_timestamp.';<br />';
echo '$config[\'cache_count_transaction\'] = '.$transactions[0]['totals'].';<br />';
echo '$config[\'cache_count_read\'] = '.$read_coins[0]['totals'].';<br />';
echo '$config[\'cache_count_idea\'] = '.$in_coins[0]['totals'].';<br />';
echo '$config[\'cache_count_source\'] = '.$en_coins[0]['totals'].';<br />';
echo '<br /><br />';


//CONFIG VARS
foreach($config_ens as $en){

    //Now fetch all its children:
    $children = $this->READ_model->fetch(array(
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
        'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
        'ln_profile_source_id' => $en['ln_portfolio_source_id'],
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
    ), array('en_portfolio'), 0, 0, array('ln_order' => 'ASC', 'en_name' => 'ASC'));


    //Find common base, if allowed:
    $common_prefix = ( in_array($en['ln_portfolio_source_id'], $this->config->item('en_ids_12588')) ? null : in_calc_common_prefix($children, 'en_name') );

    //Generate raw IDs:
    $child_ids = array();
    foreach($children as $child){
        array_push($child_ids , $child['en_id']);
    }

    echo '<br />//'.$en['en_name'].':<br />';
    echo '$config[\'en_ids_'.$en['ln_portfolio_source_id'].'\'] = array('.join(',',$child_ids).');<br />';
    echo '$config[\'en_all_'.$en['ln_portfolio_source_id'].'\'] = array(<br />';
    foreach($children as $child){

        //Do we have an omit command?
        if(strlen($common_prefix) > 0){
            $child['en_name'] = trim(substr($child['en_name'], strlen($common_prefix)));
        }

        //Fetch all parents for this child:
        $child_parent_ids = array(); //To be populated soon
        $child_parents = $this->READ_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
            'ln_portfolio_source_id' => $child['en_id'],
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
        ), array('en_profile'), 0);
        foreach($child_parents as $cp_en){
            array_push($child_parent_ids, intval($cp_en['en_id']));
        }

        echo '&nbsp;&nbsp;&nbsp;&nbsp; '.$child['en_id'].' => array(<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_icon\' => \''.htmlentities($child['en_icon']).'\',<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_name\' => \''.htmlentities(str_replace('\'','\\\'',$child['en_name'])).'\',<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_desc\' => \''.htmlentities(str_replace('\'','\\\'',$child['ln_content'])).'\',<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_parents\' => array('.join(',',$child_parent_ids).'),<br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp; ),<br />';

    }
    echo ');<br />';
}

