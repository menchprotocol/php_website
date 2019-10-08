<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mench extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }


    function overview(){
        //Load default:
        return redirect_message('/play');
    }

    function update_counters(){

        //Return stats for the platform home page:
        $in_count = $this->Intents_model->in_fetch(array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array(), 0, 0, array(), 'COUNT(in_id) as total_public_intents');
        $en_count = $this->Entities_model->en_fetch(array(
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
        ), array(), 0, 0, array(), 'COUNT(en_id) as total_public_entities');
        $ln_count = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_public_links');

        return echo_json(array(
            'intents' => array(
                'current_count' => number_format($in_count[0]['total_public_intents']),
            ),
            'entities' => array(
                'current_count' => number_format($en_count[0]['total_public_entities']),
            ),
            'links' => array(
                'current_count' => number_format($ln_count[0]['total_public_links']),
            )
        ));

    }


}