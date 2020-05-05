<?php

if(!isset($_GET['en_id']) || !intval($_GET['en_id'])){

    echo 'Missing source ID (Append ?en_id=SOURCE_ID in URL)';
    $just_do = null;

    //Add here for now:
    echo '<table>';
    foreach($this->LEDGER_model->ln_fetch(array(
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
        'ln_type_source_id' => 4257, //EMBED
        'ln_content LIKE \'%youtube.com/embed/%\'' => null,
    ), array('en_portfolio')) as $counter => $en_embed){

        //Find Parent Video:
        $expert_video_parent = null; //try to find this
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
            'ln_portfolio_source_id' => $en_embed['en_id'],
        ), array('en_profile'), 0, 0, array('en_weight' => 'DESC')) as $parent_en){

            //Does this have a expert video parent?
            if(count($this->LEDGER_model->ln_fetch(array(
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                'ln_portfolio_source_id' => $parent_en['en_id'],
                'ln_profile_source_id' => 2998, //EXPERT VIDEO
            )))){
                $expert_video_parent = $parent_en;
                break;
            }

            if($expert_video_parent){
                break;
            }
        }



        if(is_null($just_do)){
            $just_do = $expert_video_parent['en_id'];
        } elseif($just_do!=$expert_video_parent['en_id']){
            break;
        }

        $start_time = intval(one_two_explode('start=', '&', $en_embed['ln_content']));
        $end_time = intval(one_two_explode('end=', '&', $en_embed['ln_content']));

        echo '<tr><td>'.($counter+1).'</td><td><a href="/source/'.$en_embed['en_id'].'">'.$en_embed['en_name'].'</a></td><td>'.$en_embed['ln_content'].'</td><td>'.$start_time.' - '.$end_time.'</td><td>'.extract_youtube_id($en_embed['ln_content']).'</td><td><a href="/source/'.$expert_video_parent['en_id'].'">'.$expert_video_parent['en_name'].'</a></td><td>';

        //List ALl Ideas With Text:
        $ideas = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12273')) . ')' => null,
            'ln_profile_source_id' => $en_embed['en_id'],
        ), array('in_next'), 0);

        foreach($ideas as $in){

            $new_content = str_replace('@'.$en_embed['en_id'],'@'.$expert_video_parent['en_id'].':'.$start_time.':'.$end_time, $in['ln_content']);

            /*
            $this->LEDGER_model->ln_update(intval($_POST['ln_id']), array(
                'ln_content' => $new_content,
                'ln_profile_source_id' => $expert_video_parent['en_id'],
            ), 1, 10679, update_description($in['ln_content'], $new_content));
            */

            echo '!'.$in['ln_id'].' <a href="/idea/'.$in['in_id'].'">'.$in['in_title'].'</a> ['.$in['ln_content'].'] => ['.$new_content.']<hr />';


        }


        //Move Idea Message Reference

        //Update URL to YouTUbe Only

        //Delete Child source


        echo '</td></tr>';

    }

    echo '</table>';

} else {

    //Fetch Source:
    $ens = $this->SOURCE_model->en_fetch(array(
        'en_id' => intval($_GET['en_id']),
    ));
    if(count($ens) > 0){

        //unserialize metadata if needed:
        echo_json($this->SOURCE_model->en_metadat_experts($ens[0]));

    } else {
        echo 'Source @'.intval($_GET['en_id']).' not found!';
    }
}

