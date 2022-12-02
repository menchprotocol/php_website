<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class I extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        cookie_check();

    }




    function i_go($i__id = 0){
        /*
         *
         * The next section is very important as it
         * manages the entire search traffic that
         * comes through /iID
         *
         * */
        if(!$i__id){
            die('missing valid ID');
        }
        $member_e = superpower_unlocked(10939);
        return redirect_message(( $member_e ? '/~' : '/' ) . $i__id . ( $member_e && isset($_GET['load__e']) ? '?load__e='.$_GET['load__e'] : '' ) );
    }


    function i_copy(){

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'messagCloe' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Parent Source',
            ));
        } elseif (!isset($_POST['do_template'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing template parameter',
            ));
        }

        return view_json($this->I_model->recursive_clone(intval($_POST['i__id']), intval($_POST['do_template']), $member_e['e__id']));

    }


    function i_layout($i__id, $append_e__id = 0){

        //Validate/fetch Idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
        ));
        if ( count($is) < 1) {
            return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>IDEA #' . $i__id . ' Not Found</div>', true);
        }

        $member_e = superpower_unlocked(10939); //Idea Pen?
        if(!$member_e){
            if(in_array($is[0]['i__type'], $this->config->item('n___7355'))){
                return redirect_message('/'.$i__id);
            } else {
                return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>IDEA #' . $i__id . ' is not published yet.</div>');
            }
        }

        //Import Discoveries?
        if($append_e__id>0){

            $completed = 0;
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__left' => $i__id,
            ), array(), 0) as $x){
                if(!count($this->X_model->fetch(array(
                    'x__up' => $append_e__id,
                    'x__down' => $x['x__source'],
                    'x__message' => $x['x__message'],
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){
                    //Add source link:
                    $completed++;
                    $this->X_model->create(array(
                        'x__source' => ($member_e ? $member_e['e__id'] : $x['x__source']),
                        'x__up' => $append_e__id,
                        'x__down' => $x['x__source'],
                        'x__message' => $x['x__message'],
                        'x__type' => e_x__type($x['x__message']),
                    ));
                }
            }

            echo '<div>'.$completed.' sources who played this idea added to @'.$append_e__id.'</div>';

        }

        //Load views:
        $this->load->view('header', array(
            'title' => first_line($is[0]['i__title']),
            'i' => $is[0],
        ));
        $this->load->view('i_layout', array(
            'i' => $is[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }



    function i_navigate($previous_i__id, $current_i__id, $action){

        $trigger_next = false;
        $track_previous = 0;

        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $previous_i__id,
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $i){
            if($action=='next'){
                if($trigger_next){
                    return redirect_message('/~' . $i['i__id'] );
                }
                if($i['i__id']==$current_i__id){
                    $trigger_next = true;
                }
            } elseif($action=='previous'){
                if($i['i__id']==$current_i__id){
                    if($track_previous > 0){
                        return redirect_message('/~' . $track_previous );
                    } else {
                        //First item:
                        break;
                    }
                } else {
                    $track_previous = $i['i__id'];
                }
            }
        }

        if($previous_i__id > 0){
            return redirect_message('/~' .$previous_i__id );
        } else {
            die('Could not find matching idea');
        }

    }



    function i__add()
    {

        /*
         *
         * Either creates a IDEA transaction between focus_id & link_i__id
         * OR will create a new idea with outcome i__title and then transaction it
         * to focus_id (In this case link_i__id=0)
         *
         * */

        //Authenticate Member:
        $member_e = superpower_unlocked(10939);
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['x__type']) || !isset($_POST['focus_id']) || !isset($_POST['focus_coin'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variables',
            ));
        } elseif (!isset($_POST['i__title']) || !isset($_POST['link_i__id']) || ( strlen($_POST['i__title']) < 1 && intval($_POST['link_i__id']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing either Idea Outcome OR Child Idea ID',
            ));
        } elseif (strlen($_POST['i__title']) > view_memory(6404,4736)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Idea outcome cannot be longer than '.view_memory(6404,4736).' characters',
            ));
        }


        $x_i = array();

        if($_POST['link_i__id'] > 0){
            //Fetch transaction idea to determine idea type:
            $x_i = $this->I_model->fetch(array(
                'i__id' => intval($_POST['link_i__id']),
                'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ));
            if(count($x_i)==0){
                //validate Idea:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Idea #'.$_POST['link_i__id'].' is not active',
                ));
            }
        }

        //All seems good, go ahead and try to create/link the Idea:
        return view_json($this->I_model->create_or_link($_POST['focus_coin'], $_POST['x__type'], trim($_POST['i__title']), $member_e['e__id'], $_POST['focus_id'], $_POST['link_i__id']));

    }

    function view_body_i(){
        //Authenticate Member:
        if (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1 || !isset($_POST['counter']) || !isset($_POST['x__type']) || intval($_POST['x__type']) < 1) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {
            echo view_body_i($_POST['x__type'], $_POST['counter'], $_POST['i__id']);
        }
    }

    function i_load_coin(){

        if (!isset($_POST['i__id']) || !isset($_POST['x__type']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {

            $ui = '';
            $listed_items = 0;
            if($_POST['x__type']==12274 || $_POST['x__type']==6255){

                //SOURCES
                $e___6177 = $this->config->item('e___6177'); //Source Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types
                $current_e = ( substr($_POST['first_segment'], 0, 1)=='@' ? intval(substr($_POST['first_segment'], 1)) : 0 );

                foreach(view_coins_i($_POST['x__type'], $_POST['i__id'], 1, false, view_memory(6404,13206)) as $source_e) {
                    if(isset($source_e['e__id'])){
                        $ui .= view_coin_line('/@'.$source_e['e__id'], $source_e['e__id']==$current_e, $e___4593[$source_e['x__type']]['m__cover'], $e___6177[$source_e['e__type']]['m__cover'], view_cover(12274,$source_e['e__cover']), $source_e['e__title'], view_x__message($source_e['x__message'],$source_e['x__type']));
                        $listed_items++;
                    }
                }

            } elseif($_POST['x__type']==11019 || $_POST['x__type']==12273){

                //IDEAS
                $e___4737 = $this->config->item('e___4737'); //Idea Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types
                $superpower_10939 = superpower_active(10939, true);
                $current_i = ( substr($_POST['first_segment'], 0, 1)=='~' ? intval(substr($_POST['first_segment'], 1)) : 0 );
                foreach(view_coins_i($_POST['x__type'], $_POST['i__id'], 1, false, view_memory(6404,13206)) as $next_i) {
                    if(isset($next_i['i__id'])){
                        $ui .= view_coin_line('/~'.$next_i['i__id'], $next_i['i__id']==$current_i, $e___4593[$next_i['x__type']]['m__cover'], $e___4737[$next_i['i__type']]['m__cover'], null, view_i_title($next_i), view_x__message($next_i['x__message'],$next_i['x__type']));
                        $listed_items++;
                    }
                }

            }

            if($listed_items < $_POST['counter']){
                //We have more to show:
                $ui .= view_coin_line('/~'.$_POST['i__id'], false, '&nbsp;', '&nbsp;', '&nbsp;', 'View all '.number_format($_POST['counter'], 0));
            }

            echo $ui;
        }
    }


    function load_message_27963(){

        //Authenticate Member:
        $member_e = superpower_unlocked(10939); //Superpower not required as it may be just a comment

        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));

        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {

            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));

        }

        $message = '';
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $_POST['i__id'],
        ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $x) {
            $message .= $x['x__message']."\n";
        }


        return view_json(array(
            'status' => 1,
            'message' => $message,
        ));


    }







    function view_load_page_i()
    {

        $superpower_10939 = superpower_active(10939, true); //SUPERPOWER OF IDEAGING
        $items_per_page = view_memory(6404,11064);
        $x__type = intval($_POST['x__type']);
        $focus_id = intval($_POST['focus_id']);
        $page = intval($_POST['page'])+1;

        $list_results = view_coins_e($x__type, $focus_id, $page);
        $child_count = view_coins_e($x__type, $focus_id,0, false);
        $es = $this->E_model->fetch(array(
            'e__id' => $focus_id,
        ));

        foreach($list_results as $i) {
            echo view_i($x__type, 0, null, $i, $es[0]);
        }


        //Do we need another load more button?
        if ($child_count > (($page * $items_per_page) + count($list_results))) {
            echo view_load_page_i($x__type, ($page + 1), $items_per_page, $child_count);
        }

    }


    function save_message_27963(){

        //Authenticate Member:
        $member_e = superpower_unlocked(10939);
        $e___12112 = $this->config->item('e___12112');

        if(!isset($_POST['field_value'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing message input',
            ));

        } elseif (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));

        } elseif(!isset($_POST['i__id'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));

        }

        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        ));
        if(!count($is)){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }


        $errors = false;
        $line_number = 0;
        $msg_validations = array();
        $input_clean = ''; //Generate new clean message
        $textarea_content = '';
        foreach(explode(PHP_EOL, $_POST['field_value']) as $message_input) {

            if(!strlen(trim($message_input))){
                //Ignore empty line:
                continue;
            }

            $line_number++;

            //Validate message:
            $msg_validation = $this->X_model->message_compile($message_input, false, $member_e, 0, $is[0]['i__id']);


            //Did we have ane error in message validation?
            if (!$msg_validation['status']) {
                $errors .= '<br /><span class="inline-block">&nbsp;</span>Line #'.$line_number.': '.$msg_validation['message'];
                $input_clean .= $message_input."\n\n";
            } else {
                array_push($msg_validations, $msg_validation);
                //Add to clean messages:
                $input_clean .= $msg_validation['clean_message']."\n\n";
            }

        }

        //Did we catch any errors?
        if($errors){
            return view_json(array(
                'status' => 0,
                'message' => $errors,
            ));
        }


        //Validation complete! Let's update messages...
        //DELETE all current notes, if any:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => 4231,
            'x__right' => $is[0]['i__id'],
        ), array(), 0) as $x) {

            //Remove Note:
            $this->X_model->update($x['x__id'], array(
                'x__status' => 6173,
            ), $member_e['e__id'], 13579);

        }


        foreach($msg_validations as $count => $msg_validation) {

            //SAVE this message:
            $x = $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__spectrum' => ($count + 1),
                'x__type' => 4231,
                'x__right' => $is[0]['i__id'],
                'x__message' => $msg_validation['clean_message'],
                'x__up' => ( count($msg_validation['note_references']) ? $msg_validation['note_references'][0] : 0 ),
            ));

            //GENERATE New Preview:
            $textarea_content .= $this->X_model->message_view($msg_validation['clean_message'], false, $member_e, $is[0]['i__id']);

        }

        if(!strlen($textarea_content)){
            $textarea_content = '<i class="no-message">Write Message...</i>';
        }

        //Update Search Index:
        update_algolia(12273, $is[0]['i__id']);


        return view_json(array(
            'status' => 1,
            'message' => $textarea_content,
        ));

    }

}