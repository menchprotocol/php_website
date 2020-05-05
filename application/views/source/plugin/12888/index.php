<?php

if(!isset($_GET['en_id']) || !intval($_GET['en_id'])){

    echo 'Missing source ID (Append ?en_id=SOURCE_ID in URL)';

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
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
                'ln_portfolio_source_id' => $en_embed['en_id'],
                'ln_profile_source_id' => 2998, //EXPERT VIDEO
            )))){
                $expert_video_parent = $parent_en;
                break;
            }

            if($expert_video_parent){
                break;
            }
        }

        echo '<tr><td>'.($counter+1).'</td><td><a href="/source/'.$en_embed['en_id'].'">'.$en_embed['en_name'].'</a></td><td>'.$en_embed['ln_content'].'</td><td>'.( $expert_video_parent ? '<a href="/source/'.$expert_video_parent['en_id'].'">'.$expert_video_parent['en_name'].'</a>' : 'NOT FOUND!!!' ).'</td></tr>';

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

