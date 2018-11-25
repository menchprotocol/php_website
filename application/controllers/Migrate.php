<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    //To carry the user object after validation

    function __construct() {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }

    function c(){
        boost_power();

        $intents = $this->Db_model->in_fetch(array(), 0);
        foreach($intents as $c){

        }

    }

    function u(){
        boost_power();


    }

    function e(){
        boost_power();


    }


}