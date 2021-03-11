<?php

//Smart Cover Sync for Sources and Ideas


//IDEAS
$ideas_scanned = 0;
foreach($this->I_model->fetch(array()) as $o){

    $ideas_scanned++;
    if(cover_can_update($o['i__cover']) && strlen($o['i__cover'])){
        echo $o['i__id'].' ['.$o['i__cover'].']<br />';
    }
    continue;



    if(!cover_can_update($o['i__cover'])){
        continue; //Can't update this
    }


    $found_image = null;
    $found_icon = null;

    //IDEA SOURCE
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
        'x__right' => $o['i__id'],
        'x__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    ), array('x__up'), 0, 0, array(
        'x__type' => 'ASC', //Messages First, Sources Second
        'x__spectrum' => 'ASC', //Sort by message order
    )) as $fetched_e){

        //See if this source has a photo:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___14756')) . ')' => null, //SOURCE LINK IMAGE HOLDERS
            'x__down' => $fetched_e['x__up'],
        )) as $e_image) {
            if($e_image['x__type']==4260){
                $found_image = $e_image['x__message'];
                break;
            } elseif($e_image['x__type']==4257 /* Currently excluded from @14756 */){
                //Embed:
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
        if(!$found_icon && in_array($fetched_e['x__type'], $this->config->item('n___14818'))){
            if(strlen($fetched_e['e__cover']) > 0){
                $found_icon = $fetched_e['e__cover'];
                $o_id = $fetched_e['e__id'];
            }
        }
        if($found_icon){
            break;
        }
    }


    $new_icon = ( strlen($found_image) ? $found_image : $found_icon );
    if(strlen($new_icon)){
        $this->I_model->update($o['i__id'], array(
            'i__cover' => $new_icon,
        ));
    }
}
echo '<br /><br />'.$ideas_scanned.' Ideas scanned.<br />';



//SOURCES
$sources_scanned
foreach($this->E_model->fetch(array()) as $o) {

    $sources_scanned++;
    if (cover_can_update($o['e__cover']) && strlen($o['e__cover'])) {
        echo $o['e__id'] . ' [' . $o['e__cover'] . ']<br />';
    }
    continue;
}
echo '<br /><br />'.$sources_scanned.' Ideas scanned.<br />';


/*
$o_id = ( $is_idea ? $o['i__id'] : $o['e__id'] );



if(!$found_image && (!$found_icon || !$is_idea)){

    //Have no image in the main cover, look elsewhere:
    if($is_idea){



    } else {

        //Source Profile Search:
        foreach($this->X_model->fetch(array( //SOURCE PROFILE
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___14756')) . ')' => null, //SOURCE LINK IMAGE HOLDERS
            'x__down' => $o['e__id'], //This child source
        ), array('x__up'), 0, 0, array()) as $fetched_e){

            if($fetched_e['x__type']==4260){
                $found_image = $fetched_e['x__message'];
                break;
            } elseif($fetched_e['x__type']==4257){
                //Embed:
                $video_id = extract_youtube_id($fetched_e['x__message']);
                if($video_id){
                    //Use the YouTube video image:
                    $found_image = 'https://img.youtube.com/vi/'.$video_id.'/hqdefault.jpg';
                    break;
                }
            }
        }

    }

}

*/