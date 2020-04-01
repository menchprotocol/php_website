<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mench extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }



    function update_coin_counter(){

        $session_en = superpower_assigned();
        if (!$session_en) {
            //Return All Count:

            //COUNT COINS
            $read_coins = $this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
            $blog_coins = $this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id' => 4250, //UNIQUE BLOGS
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
            $play_coins = $this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //PLAY COIN
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');

            return echo_json(array(
                'play_count' => echo_number($play_coins[0]['total_coins']),
                'play_raw_count' => $play_coins[0]['total_coins'],
                'blog_count' => echo_number($blog_coins[0]['total_coins']),
                'blog_raw_count' => $blog_coins[0]['total_coins'],
                'read_count' => echo_number($read_coins[0]['total_coins']),
                'read_raw_count' => $read_coins[0]['total_coins']
            ));

        }

        $play_coin_count = 1;
        if(superpower_assigned(10986)){
            $play_coins = $this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //PLAY COIN
                'ln_player_play_id' => $session_en['en_id'],
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
            $play_coin_count = $play_coins[0]['total_coins'];
        }

        $blog_coins = $this->READ_model->ln_fetch(array(
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_12273')) . ')' => null, //BLOG COIN
            'ln_parent_play_id' => $session_en['en_id'],
        ), array('in_child'), 0, 0, array(), 'COUNT(ln_id) as total_coins');

        $read_coins = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_player_play_id' => $session_en['en_id'],
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');

        return echo_json(array(
            'play_count' => echo_number($play_coin_count),
            'play_raw_count' => $play_coin_count,
            'blog_count' => echo_number($blog_coins[0]['total_coins']),
            'blog_raw_count' => $blog_coins[0]['total_coins'],
            'read_count' => echo_number($read_coins[0]['total_coins']),
            'read_raw_count' => $read_coins[0]['total_coins']
        ));

    }




}

?>