<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(FALSE);
        $udata = $this->session->userdata('user');
    }


    function error()
    {
        $this->load->view('view_shared/public_header', array(
            'title' => 'Page Not Found',
        ));
        $this->load->view('other/404_page_not_found');
        $this->load->view('view_shared/public_footer');
    }




    function index()
    {

        $udata = $this->session->userdata('user');

        if (isset($udata['en__parents'][0]) && fn___filter_array($udata['en__parents'], 'en_id', 1308)) {

            //Lead miner and above, go to matrix:
            fn___redirect_message('/intents/' . $this->config->item('in_primary_id'));

        } elseif (0 && (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'mench.co')) {

            //Show the Hiring Ad:
            fn___redirect_message('/8327?do_expand=1');

        } else {

            //How many featured intents do we have?
            $featured_cs = $ins = $this->Database_model->in_fetch(array(
                'in_status' => 3, //Featured Intents
            ));

            if (count($featured_cs) == 0) {

                //Go to default landing page:
                return fn___redirect_message('/' . $this->config->item('in_primary_id'));

            } elseif (count($featured_cs) == 1) {

                //TO to single feature:
                return fn___redirect_message('/' . $featured_cs[0]['in_id']);

            } else {

                //We have more featured, list them so user can choose:
                //Show index page:
                $this->load->view('view_shared/public_header', array(
                    'title' => 'Advance Your Tech Career',
                ));
                $this->load->view('view_intents/in_home_featured_ui', array(
                    'featured_cs' => $featured_cs,
                ));
                $this->load->view('view_shared/public_footer');

            }
        }
    }


    function ses()
    {
        fn___echo_json($this->session->all_userdata());
    }

    function info()
    {
        echo phpinfo();
    }

    function mench_legend()
    {
        //Display statuses for intents, entities and ledger transactions:
        $this->load->view('view_shared/matrix_header', array(
            'title' => 'Mench Legend',
        ));
        $this->load->view('view_shared/mench_legend');
        $this->load->view('view_shared/matrix_footer');
    }

}
