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
        $this->load->view('shared/public_header', array(
            'title' => 'Page Not Found',
        ));
        $this->load->view('other/404_page_not_found');
        $this->load->view('shared/public_footer');
    }


    function jobs()
    {
        $this->load->view('shared/public_header', array(
            'title' => 'Work at Mench',
        ));
        $this->load->view('entities/mench-co-jobs');
        $this->load->view('shared/public_footer');
    }


    function index()
    {

        $udata = $this->session->userdata('user');

        if (isset($udata['en__parents'][0]) && filter_array($udata['en__parents'], 'en_id', 1308)) {

            //Lead coach and above, go to console:
            redirect_message('/intents/' . $this->config->item('in_primary_id'));

        } elseif (1 || (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'mench.co')) {

            $this->load->view('shared/public_header', array(
                'title' => ucwords($this->config->item('primary_in_name')),
            ));
            $this->load->view('entities/mench-co-intro');
            $this->load->view('shared/public_footer');

        } else {

            //How many featured intents do we have?
            $featured_cs = $fetch_cs = $this->Db_model->in_fetch(array(
                'in_status' => 3, //Featured Intents
            ));

            if (count($featured_cs) == 0) {

                //Go to default landing page:
                return redirect_message('/' . $this->config->item('in_primary_id'));

            } elseif (count($featured_cs) == 1) {

                //TO to single feature:
                return redirect_message('/' . $featured_cs[0]['in_id']);

            } else {

                //We have more featured, list them so user can choose:
                //Show index page:
                $this->load->view('shared/public_header', array(
                    'title' => 'Advance Your Tech Career',
                ));
                $this->load->view('intents/home_featured_intents', array(
                    'featured_cs' => $featured_cs,
                ));
                $this->load->view('shared/public_footer');

            }
        }
    }


    function login()
    {
        //Check to see if they are already logged in?
        $udata = $this->session->userdata('user');
        if (isset($udata['en__parents'][0]) && filter_array($udata['en__parents'], 'en_id', 1308)) {
            //Lead coach and above, go to console:
            redirect_message('/intents/' . $this->config->item('in_primary_id'));
        }

        $this->load->view('shared/public_header', array(
            'title' => 'Login',
        ));
        $this->load->view('entities/login');
        $this->load->view('shared/public_footer');
    }


    function terms()
    {
        $this->load->view('shared/public_header', array(
            'title' => 'Terms & Privacy Policy',
        ));
        $this->load->view('other/terms');
        $this->load->view('shared/public_footer');
    }

    function ses()
    {
        echo_json($this->session->all_userdata());
    }

    function info()
    {
        echo phpinfo();
    }


    /* ******************************
     * Pitch Pages
     ****************************** */


    function train()
    {
        $data = array(
            'title' => 'Train Mench to become the best Personal Assistant',
            'landing_page' => 'entities/splash_trainers_why',
        );
        $this->load->view('shared/public_header', $data);
        $this->load->view('entities/become_a_trainer', $data);
        $this->load->view('shared/public_footer');
    }


}
