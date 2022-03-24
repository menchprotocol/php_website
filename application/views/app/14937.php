<?php

//Smart Cover Sync for Sources and Ideas


//IDEAS
$i_scanned = 0;
$i_inherit = 0;
$i_inherit_image = 0;
foreach($this->I_model->fetch(array('i__cover IS NULL' => null)) as $o){

    $i_scanned++;

    $found_image = null;
    $found_icon = null;

    //IDEA SOURCE
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
        'x__right' => $o['i__id'],
        'x__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    ), array('x__up'), 0, 0, array(
        'x__type' => 'ASC', //Messages First, Sources Second
        'x__spectrum' => 'ASC', //Sort by message order
    )) as $fetched_e){

        //See if this source has a photo:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___14756')) . ')' => null, //Inherit Cover Types
            'x__down' => $fetched_e['e__id'],
        )) as $e_image) {
            if($e_image['x__type']==4260){
                $found_image = $e_image['x__message'];
                break;
            } elseif($e_image['x__type']==4257){
                //Embed: [DISABLED FOR NOW - Duplicated code: search "TIGER"]
                $video_id = extract_youtube_id($e_image['x__message']);
                if($video_id){
                    //Use the YouTube video image:
                    $found_image = 'https://img.youtube.com/vi/'.$video_id.'/hqdefault.jpg';
                    break;
                }
            }
        }

        if($found_image){
            break;
        }

        //Try to find Icon:
        if(!$found_icon && in_array($fetched_e['x__type'], $this->config->item('n___14818')) && strlen($fetched_e['e__cover'])){
            $found_icon = $fetched_e['e__cover'];
            $o_id = $fetched_e['e__id'];
        }

        if($found_icon){
            break;
        }

    }


    if($found_image){
        $i_inherit_image++;
    }
    $new_icon = ( $found_image ? $found_image : $found_icon );
    if(strlen($new_icon)){
        echo '<a href="/~'.$o['i__id'].'">NEW</a> ['.$new_icon.']<br />';
        $i_inherit += $this->I_model->update($o['i__id'], array(
            'i__cover' => $new_icon,
        ), false, ( $member_e ? $member_e['e__id'] : 7274 ), 18148);
    }

}
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

    //Source Profile Search:
    foreach($this->X_model->fetch(array( //SOURCE PROFILE
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $this->config->item('n___14756')) . ')' => null, //Inherit Cover Types
        'x__down' => $o['e__id'], //This child source
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
        //Idea References:
        foreach(view_coins_e(12273, $o['e__id'], 1) as $linked_i){
            if(strlen($linked_i['i__cover'])){
                if(filter_var($linked_i['i__cover'], FILTER_VALIDATE_URL)){
                    $found_image = $linked_i['i__cover'];
                } elseif(!$found_icon) {
                    $found_icon = $linked_i['i__cover'];
                }
            }
            if($found_image){
                break;
            }
        }
    }

    if(!$found_image && !$found_icon){
        //Parent Sources:
        foreach($this->X_model->fetch(array(
            'x__down' => $o['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
        ), array('x__up'), 0, 0, array('LENGTH(x__message)' => 'DESC', 'e__spectrum' => 'DESC')) as $linked_e){
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
