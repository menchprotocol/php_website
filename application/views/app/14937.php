<?php

//Smart Cover Sync for Sources and Ideas


//IDEAS
$i_scanned = 0;
$i_inherit = 0;
$i_inherit_image = 0;
echo '<br /><br />';
echo $i_scanned.' Ideas scanned.<br />';
echo $i_inherit.' Ideas inherited, of which '.$i_inherit_image.' had images.<br />';
echo '<br /><br />';




//SOURCES
$sources_scanned = 0;
$sources_inherit = 0;
$sources_inherit_image = 0;
foreach($this->E_model->fetch(array('e__cover IS NULL' => null)) as $o) {

    $sources_scanned++;
    $found_image = null;
    $found_icon = null;

    //Source Following Search:
    foreach($this->X_model->fetch(array( //SOURCE PROFILE
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___14756')) . ')' => null, //Inherit Cover Types
        'x__down' => $o['e__id'], //This follower source
    ), array('x__up'), 0, 0, array()) as $fetched_e){

        if($fetched_e['x__type']==4260){
            $found_image = $fetched_e['x__message'];
            break;
        } elseif($fetched_e['x__type']==4257){
            //Embed: [DISABLED FOR NOW - Duplicated code: search "TIGER"]
            $video_id = extract_youtube_id($fetched_e['x__message']);
            if($video_id){
                //Use the YouTube video image:
                $found_image = 'https://img.youtube.com/vi/'.$video_id.'/hqdefault.jpg';
                break;
            }
        }
    }

    if(!$found_image && !$found_icon){
        //Following Sources:
        foreach($this->X_model->fetch(array(
            'x__down' => $o['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__up'), 0, 0, array('e__title' => 'DESC')) as $linked_e){
            if(strlen($linked_e['e__cover'])){
                if(filter_var($linked_e['e__cover'], FILTER_VALIDATE_URL)){
                    $found_image = $linked_e['e__cover'];
                } elseif(!$found_icon) {
                    $found_icon = $linked_e['e__cover'];
                }
            }
            if($found_image){
                break;
            }
        }
    }


    if($found_image){
        $sources_inherit_image++;
    }
    $new_icon = ( $found_image ? $found_image : $found_icon );
    if(strlen($new_icon)){
        echo '<a href="/@'.$o['e__id'].'">NEW</a> ['.$new_icon.']<br />';
        $sources_inherit += $this->E_model->update($o['e__id'], array(
            'e__cover' => $new_icon,
        ), false, ( $member_e ? $member_e['e__id'] : 7274 ), 18148);
    }

}
echo '<br /><br />';
echo $sources_scanned.' Sources scanned.<br />';
echo $sources_inherit.' Sources inherited, of which '.$sources_inherit_image.' had images.<br />';
echo '<br /><br />';
