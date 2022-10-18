<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class E extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        cookie_check();

    }


    function index()
    {
        //source:
        $member_e = superpower_unlocked(null);

        $e___11035 = $this->config->item('e___11035');
        $this->load->view('header', array(
            'title' => $e___11035[13207]['m__title'],
        ));
        $this->load->view('e/home', array(
            'member_e' => $member_e,
        ));
        $this->load->view('footer');
    }


    function e_view_body_e(){
        //Authenticate Member:
        if (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1 || !isset($_POST['counter']) || !isset($_POST['x__type']) || intval($_POST['x__type']) < 1) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {
            echo view_body_e($_POST['x__type'], $_POST['counter'], $_POST['e__id']);
        }
    }

    function toggle_left_menu(){

        $member_e = superpower_unlocked();
        if(!$member_e){
            return 'Session expired. You must <a href="/-4269"><b><u>login</u></b></a> to view this menu.';
        }

        //Start building the sidebar
        $sidebar_ui = '';
        $sidebar_ui .= '<hr /><a href="/@'.$member_e['e__id'].'" class="css__title"><span class="icon-block-xs">'.view_cover(12274,$member_e['e__cover']).'</span>'.$member_e['e__title'].'</a><hr />';

        foreach($this->config->item('e___28658') as $x__type => $m) {

            //Have Needed Superpowers?
            $require = 0;
            $missing = 0;
            $meeting = 0;
            foreach(array_intersect($this->config->item('n___10957'), $m['m__profile']) as $superpower_required){
                $require++;
                if(superpower_active($superpower_required, true)){
                    $meeting++;
                } else {
                    $missing++;
                }
            }
            if($require && !$meeting){
                //RELAX: Meet any requirement and it would be shown
                continue;
            }


            if($x__type==12969){

                //STARTED
                $counter = view_coins_e(12969, $member_e['e__id'], 0, false);
                if($counter){
                    $sidebar_ui .= '<div class="low-title grey"><span class="icon-block-xs">'.$m['m__cover'].'</span>'.$counter.' '.$m['m__title'].'</div>';
                    foreach(view_coins_e(12969, $member_e['e__id'], 1) as $item){
                        $completion_rate = $this->X_model->completion_progress($member_e['e__id'], $item);
                        $sidebar_ui .= '<a href="/x/x_next/'.$item['i__id'].'/'.$item['i__id'].'" class="css__title" title="'.$item['i__title'].'">['.$completion_rate['completion_percentage'].'%] '.$item['i__title'].'</a>';
                    }
                    $sidebar_ui .= '<hr />';
                }

            } elseif($x__type==6255){

                //RECENT DISCOVERIES
                $counter = view_coins_e($x__type, $member_e['e__id'], 0, false);
                if($counter){
                    $sidebar_ui .= '<div class="low-title grey"><span class="icon-block-xs">'.$m['m__cover'].'</span>'.$counter.' '.$m['m__title'].'</div>';
                    foreach(view_coins_e(6255, $member_e['e__id'], 1, false, 10) as $item){
                        $sidebar_ui .= '<a href="/'.$item['i__id'].'" class="css__title" title="'.$item['i__title'].'">'.$item['i__title'].'</a>';
                    }
                    $sidebar_ui .= '<hr />';
                }

            } elseif($x__type==12896){

                //SAVED DISCOVERIES
                $i_notes_query = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                    'x__type' => 12896,
                    'x__up' => $member_e['e__id'],
                ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC', 'x__id' => 'DESC'));
                $counter = count($i_notes_query);
                if($counter > 0){
                    $sidebar_ui .= '<div class="low-title grey"><span class="icon-block-xs">'.$m['m__cover'].'</span>'.$counter.' '.$m['m__title'].'</div>';
                    foreach($i_notes_query as $item) {
                        $sidebar_ui .= '<a href="/'.$item['i__id'].'" class="css__title" title="'.$item['i__title'].'">'.$item['i__title'].'</a>';
                    }
                    $sidebar_ui .= '<hr />';
                }

            } elseif($x__type==28646){

                //ADMIN MENU
                $domain_list = intval(substr(get_domain_setting(28646), 1));
                if($domain_list){

                    if(is_array($this->config->item('x___'.$domain_list)) && count($this->config->item('x___'.$domain_list))){
                        $sidebar_ui .= '<div class="low-title grey"><span class="icon-block-xs">'.$m['m__cover'].'</span>'.$m['m__title'].' Ideas</div>';
                        foreach($this->config->item('x___'.$domain_list) as $i__id => $m2){

                            //Count discoveries:
                            $x_coins = $this->X_model->fetch(array(
                                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
                                'x__left' => $i__id,
                            ), array(), 1, 0, array(), 'COUNT(x__id) as totals');
                            $sidebar_ui .= '<a href="/~'.$i__id.'" class="css__title" title="'.$m2['m__title'].'"><span class="icon-block-xs">'.$m2['m__cover'].'</span>'.( $x_coins[0]['totals']>0 ? number_format($x_coins[0]['totals'], 0).' ' : '' ).$m2['m__title'].'</a>';

                        }
                    }

                    if(is_array($this->config->item('e___'.$domain_list))){
                        $sidebar_ui .= '<div class="low-title grey"><span class="icon-block-xs">'.$m['m__cover'].'</span>'.$m['m__title'].' Sources</div>';
                        foreach($this->config->item('e___'.$domain_list) as $e__id => $m2){

                            //Count sources:
                            $list_e_count = $this->X_model->fetch(array(
                                'x__up' => $e__id,
                                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            ), array(), 0, 0, array(), 'COUNT(x__id) as totals');
                            $sidebar_ui .= '<a href="/@'.$e__id.'" class="css__title" title="'.$m2['m__title'].'"><span class="icon-block-xs">'.$m2['m__cover'].'</span>'.( $list_e_count[0]['totals']>0 ? number_format($list_e_count[0]['totals'], 0).' ' : '' ).$m2['m__title'].'</a>';

                        }
                    }
                }
            }
        }

        if(!strlen($sidebar_ui)){
            $sidebar_ui .= '<div style="padding: 5px;">Nothing started yet... Visit the <a href="/"><b><u>Home Page</u></b></a> to get started.</div>';
        }

        echo $sidebar_ui;

    }



    //Lists sources
    function e_layout($e__id)
    {

        $member_e = superpower_unlocked();

        //Make sure not a private source:
        if(in_array($e__id, $this->config->item('n___4755')) && (!$member_e || $member_e['e__id']==$e__id)){
            $member_e = superpower_unlocked(12701, true);
        }

        //Validate source ID and fetch data:
        $es = $this->E_model->fetch(array(
            'e__id' => $e__id,
            'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if (count($es) < 1) {
            return redirect_message(home_url());
        }

        //Load views:
        $this->load->view('header', array(
            'title' => $es[0]['e__title'],
        ));
        $this->load->view('e_layout', array(
            'e' => $es[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }


    function e_sort_reset()
    {

        //Authenticate Member:
        $member_e = superpower_unlocked(13422);

        //Validate Source:
        $es = $this->E_model->fetch(array(
            'e__id' => $_POST['e__id'],
            'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));

        if (!$member_e) {
            view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(13422),
            ));
        } elseif (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1 || count($es) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid e__id',
            ));
        }



        //All good, reset sort value for all children:
        foreach($this->X_model->fetch(array(
            'x__up' => $_POST['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__down'), 0, 0, array(), 'x__id') as $x) {
            $this->X_model->update($x['x__id'], array(
                'x__spectrum' => 0,
            ), $member_e['e__id'], 13007 /* SOURCE SORT RESET */);
        }

        //Display message:
        view_json(array(
            'status' => 1,
        ));
    }


    function e_load_coin(){

        if (!isset($_POST['e__id']) || !isset($_POST['x__type']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {

            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';

        } else {

            $ui = '';
            $listed_items = 0;

            if($_POST['x__type']==11029 || $_POST['x__type']==11030 || $_POST['x__type']==12274){
                //SOURCES
                $current_e = ( substr($_POST['first_segment'], 0, 1)=='@' ? intval(substr($_POST['first_segment'], 1)) : 0 );
                $e___6177 = $this->config->item('e___6177'); //Source Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types
                foreach(view_coins_e($_POST['x__type'], $_POST['e__id'], 1, false, view_memory(6404,13206)) as $source_e) {
                    if(isset($source_e['is_break'])){
                        $ui .= '<hr />';
                    } elseif(isset($source_e['e__id'])){
                        $ui .= view_coin_line('/@'.$source_e['e__id'], $source_e['e__id']==$current_e, $e___4593[$source_e['x__type']]['m__cover'], $e___6177[$source_e['e__type']]['m__cover'], view_cover(12274,$source_e['e__cover']), $source_e['e__title'], view_x__message($source_e['x__message'],$source_e['x__type']));
                        $listed_items++;
                    }
                }
            } elseif($_POST['x__type']==12273 || $_POST['x__type']==6255){
                //IDEAS
                $current_i = ( substr($_POST['first_segment'], 0, 1)=='~' ? intval(substr($_POST['first_segment'], 1)) : 0 );
                $e___4737 = $this->config->item('e___4737'); //Idea Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types
                foreach(view_coins_e($_POST['x__type'], $_POST['e__id'], 1, false, view_memory(6404,13206)) as $next_i) {
                    if(isset($next_i['is_break'])){
                        $ui .= '<hr />';
                    } elseif(isset($next_i['i__id'])){
                        $ui .= view_coin_line('/i/i_go/'.$next_i['i__id'], $next_i['i__id']==$current_i, $e___4593[$next_i['x__type']]['m__cover'], $e___4737[$next_i['i__type']]['m__cover'], null, view_i_title($next_i), view_x__message($next_i['x__message'],$next_i['x__type']));
                        $listed_items++;
                    }
                }
            }

            if($listed_items < $_POST['counter']){
                //We have more to show:
                $ui .= view_coin_line('/@'.$_POST['e__id'], false, '&nbsp;', '&nbsp;', '&nbsp;', 'View all '.number_format($_POST['counter'], 0));
            }

            echo $ui;
        }
    }

    function e_sort_save()
    {

        //Authenticate Member:
        $member_e = superpower_unlocked(10939);
        if (!$member_e) {
            view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid e__id',
            ));
        } elseif (!isset($_POST['new_x__spectrums']) || !is_array($_POST['new_x__spectrums']) || count($_POST['new_x__spectrums']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Source:
            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['e__id'],
                'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));

            //Count Portfolio:
            $list_e_count = $this->X_model->fetch(array(
                'x__up' => $_POST['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ), array('x__down'), 0, 0, array(), 'COUNT(e__id) as totals');

            if (count($es) < 1) {

                view_json(array(
                    'status' => 0,
                    'message' => 'Invalid e__id',
                ));

            } elseif($list_e_count[0]['totals'] > view_memory(6404,13005)){

                view_json(array(
                    'status' => 0,
                    'message' => 'Cannot sort sources if greater than '.view_memory(6404,13005),
                ));

            } else {

                //Update them all:
                foreach($_POST['new_x__spectrums'] as $rank => $x__id) {
                    $this->X_model->update($x__id, array(
                        'x__spectrum' => intval($rank),
                    ), $member_e['e__id'], 13006 /* SOURCE SORT MANUAL */);
                }

                //Display message:
                view_json(array(
                    'status' => 1,
                ));

            }
        }
    }




    function view_load_page_e()
    {

        $items_per_page = view_memory(6404,11064);
        $focus__id = intval($_POST['focus__id']);
        $source_of_e = source_of_e($focus__id);
        $page = intval($_POST['page']);
        $query_filters = array(
            'x__up' => $focus__id,
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null,
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        );

        //Fetch & display next batch of children:
        $extra_items = $this->X_model->fetch($query_filters, array('x__down'), $items_per_page, ($page * $items_per_page), array(
            'x__spectrum' => 'ASC',
            'e__title' => 'ASC'
        ));

        foreach($extra_items as $e) {
            echo view_e($_POST['x__type'], $e, null, $source_of_e);
        }

        //Count total children:
        $child_count = $this->X_model->fetch($query_filters, array('x__down'), 0, 0, array(), 'COUNT(x__id) as totals');

        //Do we need another load more button?
        if ($child_count[0]['totals'] > (($page * $items_per_page) + count($extra_items))) {
            echo view_load_page_e($_POST['x__type'], ($page + 1), $items_per_page, $child_count[0]['totals']);
        }

    }

    function e_remove(){

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Transaction ID',
            ));
        }

        //Archive Transaction:
        $this->X_model->update($_POST['x__id'], array(
            'x__status' => 6173,
        ), $member_e['e__id'], 10673 /* IDEA NOTES Unpublished */);

        return view_json(array(
            'status' => 1,
        ));

    }

    function e_add_only_7551()
    {

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        } elseif (!isset($_POST['x__type']) || !in_array($_POST['x__type'], $this->config->item('n___7551'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea Note Type ID',
            ));
        } elseif (!isset($_POST['e_existing_id']) || !isset($_POST['e_new_string']) || (intval($_POST['e_existing_id']) < 1 && strlen($_POST['e_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
            ));
        }


        //Validate Idea
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        ));
        if (count($is) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea',
            ));
        }


        //Set some variables:
        $_POST['e_existing_id'] = intval($_POST['e_existing_id']);

        //Are we adding an existing source?
        if ($_POST['e_existing_id'] > 0) {

            //Validate this existing source:
            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['e_existing_id'],
                'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));
            if (count($es) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid active source',
                ));
            }

            //Make sure not already there:
            if(count($this->X_model->fetch(array(
                'x__right' => $is[0]['i__id'],
                'x__up' => $_POST['e_existing_id'],
                'x__type' => $_POST['x__type'],
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
                $e___7551 = $this->config->item('e___7551');
                return view_json(array(
                    'status' => 0,
                    'message' => $es[0]['e__title'].' is already added as idea '.$e___7551[$_POST['x__type']]['m__title'],
                ));
            }

            //All good, assign:
            $focus_e = $es[0];

        } else {

            //Create:
            $added_e = $this->E_model->verify_create($_POST['e_new_string'], $member_e['e__id']);
            if(!$added_e['status']){
                //We had an error, return it:
                return view_json($added_e);
            }

            //Assign new source:
            $focus_e = $added_e['new_e'];

            //Assign to Member:
            $this->E_model->add_source($focus_e['e__id']);

            //Update Algolia:
            update_algolia($_POST['x__type'], $focus_e['e__id']);

        }

        //Create Note:
        $new_note = $this->X_model->create(array(
            'x__source' => $member_e['e__id'],
            'x__type' => $_POST['x__type'],
            'x__right' => $is[0]['i__id'],
            'x__up' => $focus_e['e__id'],
        ));

        //Return source:
        return view_json(array(
            'status' => 1,
            'e_new_echo' => view_e($_POST['x__type'], array_merge($focus_e, $new_note),  null,  true),
        ));

    }


    function e_copy(){

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['e__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Parent Source',
            ));
        }


        //Validate Source:
        $fetch_o = $this->E_model->fetch(array(
            'e__id' => $_POST['e__id'],
            'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if (count($fetch_o) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid parent source ID',
            ));
        }



        //Create:
        $added_e = $this->E_model->verify_create($fetch_o[0]['e__title']." Copy", $member_e['e__id'], $fetch_o[0]['e__cover']);
        if(!$added_e['status']){
            //We had an error, return it:
            return view_json($added_e);
        } else {
            //Assign new source:
            $focus_e = $added_e['new_e'];
        }


        //Children:
        foreach($this->X_model->fetch(array(
            'x__up' => $_POST['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0) as $x) {
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => $x['x__type'],
                'x__up' => $focus_e['e__id'],
                'x__down' => $x['x__down'],
                'x__message' => $x['x__message'],
                'x__spectrum' => $x['x__spectrum'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__status' => $x['x__status'],
            ));
        }


        //Parents:
        foreach($this->X_model->fetch(array(
            'x__down' => $_POST['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0) as $x) {
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => $x['x__type'],
                'x__up' => $x['x__up'],
                'x__down' => $focus_e['e__id'],
                'x__message' => $x['x__message'],
                'x__spectrum' => $x['x__spectrum'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__status' => $x['x__status'],
            ));
        }

        //Ideas:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__right > 0' => null,
            'x__up' => $_POST['e__id'],
        ), array(), 0) as $x){
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => $x['x__type'],
                'x__up' => $focus_e['e__id'],
                'x__down' => $x['x__down'],
                'x__left' => $x['x__left'],
                'x__right' => $x['x__right'],
                'x__message' => $x['x__message'],
                'x__spectrum' => $x['x__spectrum'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__status' => $x['x__status'],
            ));
        }


        return view_json(array(
            'status' => 1,
            'new_e__id' => $focus_e['e__id'],
        ));


    }

    function e__add()
    {

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['focus__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Parent Source',
            ));
        } elseif (!isset($_POST['x__type']) || !in_array($_POST['x__type'], $this->config->item('n___14055'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Creation Type',
            ));
        } elseif (!isset($_POST['e_existing_id']) || !isset($_POST['e_new_string']) || (intval($_POST['e_existing_id']) < 1 && strlen($_POST['e_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
            ));
        }

        $adding_to_idea = ($_POST['x__type']==12274);

        if($adding_to_idea){

            //Validate Idea:
            $fetch_o = $this->I_model->fetch(array(
                'i__id' => $_POST['focus__id'],
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid parent source ID',
                ));
            }

        } else {

            //Validate Source:
            $fetch_o = $this->E_model->fetch(array(
                'e__id' => $_POST['focus__id'],
                'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid parent source ID',
                ));
            }

        }



        //Set some variables:
        $_POST['x__type'] = intval($_POST['x__type']);
        $_POST['e_existing_id'] = intval($_POST['e_existing_id']);
        $url_previously_existed = false;
        $url_e = false;

        //Are we adding an existing source?
        if (intval($_POST['e_existing_id']) > 0) {

            //Validate this existing source:
            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['e_existing_id'],
                'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));

            if (count($es) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid active source',
                ));
            }

            //All good, assign:
            $focus_e = $es[0];

        } else {

            //We are creating a new source OR adding a URL...

            //Is this a URL?
            if (filter_var($_POST['e_new_string'], FILTER_VALIDATE_URL)) {

                //Digest URL to see what type it is and if we have any errors:
                $url_e = $this->E_model->url($_POST['e_new_string'], ( $adding_to_idea ? $member_e['e__id'] /* Will Create if Not Found */ : 0 ));
                if (!$url_e['status']) {
                    return view_json($url_e);
                }

                $url_previously_existed = $url_e['url_previously_existed'];

                //Is this a root domain? Add to domains if so:
                if($url_e['url_root']){

                    //Domain
                    $focus_e = ( $adding_to_idea ? $url_e['e_domain'] : array('e__id' => 1326) );

                    //Update domain to stay synced:
                    $_POST['e_new_string'] = $url_e['url_clean_domain'];

                } else {

                    //Let's first find/add the domain:
                    $url_domain = $this->E_model->domain($_POST['e_new_string'], $member_e['e__id']);

                    //Add this source:
                    $focus_e = ( $adding_to_idea ? $url_e['e_url'] : $url_domain['e_domain'] );

                }

            } else {

                //Create:
                $added_e = $this->E_model->verify_create($_POST['e_new_string'], $member_e['e__id']);
                if(!$added_e['status']){
                    //We had an error, return it:
                    return view_json($added_e);
                } else {
                    //Assign new source:
                    $focus_e = $added_e['new_e'];
                }

            }

        }


        //We need to check to ensure this is not a duplicate transaction if adding an existing source:
        $ur2 = array();
        $e_already_linked = 0;

        if($adding_to_idea) {

            $e_already_linked = count($this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__up' => $focus_e['e__id'],
                'x__right' => $fetch_o[0]['i__id'],
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            )));

            //Add Reference:
            $ur2 = $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => 4983, //IDEA SOURCES
                'x__up' => $focus_e['e__id'],
                'x__right' => $fetch_o[0]['i__id'],
            ));

        } elseif (!$url_previously_existed) {

            //Add Up/Down Source:

            //Add transactions only if not previously added by the URL function:
            if (in_array($_POST['x__type'], $this->config->item('n___14686'))) {

                //Profile
                $x__down = $fetch_o[0]['e__id'];
                $x__up = $focus_e['e__id'];
                $x__spectrum = 0; //Never sort profiles, only sort portfolios

            } else {

                //Portfolio
                $x__up = $fetch_o[0]['e__id'];
                $x__down = $focus_e['e__id'];

                if(sources_currently_sorted($x__up)){

                    $x__spectrum = 1 + $this->X_model->max_spectrum(array(
                            'x__up' => $x__up,
                            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        ));

                } else {

                    //Don't sort since currently not sorted:
                    $x__spectrum = 0;

                }

            }


            if (isset($url_e['url_root']) && $url_e['url_root']) {

                $x__message = $url_e['clean_url'];
                $x__type = e_x__type($x__message);

            } elseif (isset($url_e['e_domain']) && $url_e['e_domain']) {

                $x__message = $url_e['clean_url'];
                $x__type = $url_e['x__type'];

            } else {

                $x__message = null;
                $x__type = e_x__type($x__message);

            }

            $e_already_linked = count($this->X_model->fetch(array(
                'x__down' => $x__down,
                'x__up' => $x__up,
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            )));

            //Create transaction:
            $ur2 = $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => $x__type,
                'x__message' => $x__message,
                'x__down' => $x__down,
                'x__up' => $x__up,
                'x__spectrum' => $x__spectrum,
            ));

        }

        //Return source:
        return view_json(array(
            'status' => 1,
            'e_new_echo' => view_e($_POST['x__type'], array_merge($focus_e, $ur2), null,  true),
            'e_already_linked' => $e_already_linked,
        ));

    }



    function e_toggle_superpower($superpower_e__id){

        //Toggles the advance session variable for the member on/off for logged-in members:
        $member_e = superpower_unlocked();
        $superpower_e__id = intval($superpower_e__id);
        $e___10957 = $this->config->item('e___10957');

        if(!$member_e){

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif(!in_array($superpower_e__id, $this->session->userdata('session_superpowers_unlocked'))){

            //Access not authorized:
            return view_json(array(
                'status' => 0,
                'message' => 'You have not yet unlocked the superpower of '.$e___10957[$superpower_e__id]['m__title'],
            ));

        }

        //Figure out new toggle state:
        $session_data = $this->session->all_userdata();

        if(in_array($superpower_e__id, $session_data['session_superpowers_activated'])){
            //Previously there, turn it off:
            $session_data['session_superpowers_activated'] = array_diff($session_data['session_superpowers_activated'], array($superpower_e__id));
            $toggled_setting = 'DEACTIVATED';
        } else {
            //Not there, turn it on:
            array_push($session_data['session_superpowers_activated'], $superpower_e__id);
            $toggled_setting = 'ACTIVATED';
        }


        //Update Session:
        $this->session->set_userdata($session_data);


        //Log Transaction:
        $this->X_model->create(array(
            'x__source' => $member_e['e__id'],
            'x__type' => 5007, //TOGGLE SUPERPOWER
            'x__up' => $superpower_e__id,
            'x__message' => 'SUPERPOWER '.$toggled_setting, //To be used when member logs in again
        ));

        //Return to JS function:
        return view_json(array(
            'status' => 1,
            'message' => 'Success',
        ));
    }


    function coin__load()
    {
        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['coin__type']) || !in_array($_POST['coin__type'] , $this->config->item('n___12761'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin Type',
            ));
        } elseif (!isset($_POST['coin__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin ID',
            ));
        }

        //Any suggestions?
        $icon_suggestions = array();
        if($_POST['coin__type']==12274 && $member_e['e__id']==$_POST['coin__id']){
            //Show animal icons:
            foreach($this->config->item('e___12279') as $e__id => $m) {
                $cover = one_two_explode('class="','"',$m['m__cover']);
                array_push($icon_suggestions, array(
                    'cover_preview' => $cover,
                    'cover_apply' => $cover,
                    'new_title' => $m['m__title'],
                ));
            }
        }

        if($_POST['coin__type']==12273){
            //IDEA
            $is = $this->I_model->fetch(array(
                'i__id' => $_POST['coin__id'],
            ));
            if(count($is)){
                return view_json(array(
                    'status' => 1,
                    'coin__title' => $is[0]['i__title'],
                    'coin__cover' => null,
                    'icon_suggestions' => $icon_suggestions,
                ));
            }
        } elseif($_POST['coin__type']==12274){
            //SOURCE
            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['coin__id'],
                'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));
            if(count($es)){
                return view_json(array(
                    'status' => 1,
                    'coin__title' => $es[0]['e__title'],
                    'coin__cover' => $es[0]['e__cover'],
                    'icon_suggestions' => $icon_suggestions,
                ));
            }
        }

        //Could not find:
        return view_json(array(
            'status' => 0,
            'message' => 'Could not find coin',
        ));
    }





    function e_radio()
    {
        /*
         *
         * Saves the radio selection of some account fields
         *
         * */

        if(isset($_POST['member__id_override']) && intval($_POST['member__id_override']) > 0){
            $member_e['e__id'] = intval($_POST['member__id_override']);
        } else {
            $member_e = superpower_unlocked();
            if (!$member_e) {
                return view_json(array(
                    'status' => 0,
                    'message' => view_unauthorized_message(),
                ));
            }
        }

        if (!isset($_POST['focus__id']) || intval($_POST['focus__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing parent source',
            ));
        } elseif (!isset($_POST['selected_e__id']) || intval($_POST['selected_e__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing selected source',
            ));
        } elseif (!isset($_POST['enable_mulitiselect']) || !isset($_POST['was_previously_selected'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing multi-select setting',
            ));
        }



        if(!$_POST['enable_mulitiselect'] || $_POST['was_previously_selected']){
            //Since this is not a multi-select we want to delete all existing options...

            //Fetch all possible answers based on parent source:
            $query_filters = array(
                'x__up' => $_POST['focus__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
            );

            if($_POST['enable_mulitiselect'] && $_POST['was_previously_selected']){
                //Just delete this single item, not the other ones:
                $query_filters['x__down'] = $_POST['selected_e__id'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach($this->X_model->fetch($query_filters, array('x__down'), 0, 0) as $answer_e){
                array_push($possible_answers, $answer_e['e__id']);
            }

            //Delete selected options for this member:
            foreach($this->X_model->fetch(array(
                'x__up IN (' . join(',', $possible_answers) . ')' => null,
                'x__down' => $member_e['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )) as $delete){
                //Should usually delete a single option:
                $this->X_model->update($delete['x__id'], array(
                    'x__status' => 6173, //Transaction Removed
                ), $member_e['e__id'], 6224 /* Member Account Updated */);
            }

        }

        //Add new option if not previously there:
        if(!$_POST['enable_mulitiselect'] || !$_POST['was_previously_selected']){
            $this->X_model->create(array(
                'x__up' => $_POST['selected_e__id'],
                'x__down' => $member_e['e__id'],
                'x__source' => $member_e['e__id'],
                'x__type' => e_x__type(),
            ));
        }


        //Log Account Update transaction type:
        $_POST['account_update_function'] = 'e_radio'; //Add this variable to indicate which My Account function created this transaction
        $this->X_model->create(array(
            'x__source' => $member_e['e__id'],
            'x__type' => 6224, //My Account updated
            'x__message' => 'My Account '.( $_POST['enable_mulitiselect'] ? 'Multi-Select Radio Field ' : 'Single-Select Radio Field ' ).( $_POST['was_previously_selected'] ? 'Deleted' : 'Added' ),
            'x__metadata' => $_POST,
            'x__up' => $_POST['focus__id'],
            'x__down' => $_POST['selected_e__id'],
        ));

        //Add special transaction to monitor unsubscribes:
        if($_POST['focus__id']==28904 && in_array($_POST['selected_e__id'], $this->config->item('n___29648'))){
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => 29648, //Communication Downgraded
                'x__up' => $_POST['focus__id'],
                'x__down' => $_POST['selected_e__id'],
            ));
        }


        //Update Session:
        if(count($member_e) >= 2){
            $this->E_model->activate_session($member_e, true);
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Updated', //NOT shown in UI
        ));
    }






    function e_avatar()
    {

        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['type_css'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing type_css',
            ));
        } elseif (!isset($_POST['icon_css'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing icon_css',
            ));
        }

        //Validate:
        $icon_new_css = $_POST['type_css'].' '.$_POST['icon_css'];
        $validated = false;
        foreach($this->config->item('e___12279') as $e__id => $m) {
            if(substr_count($m['m__cover'], $icon_new_css) == 1){
                $validated = true;
                break;
            }
        }
        if(!$validated){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid icon',
            ));
        }


        //Update icon:
        $new_avatar = '<i class="'.$icon_new_css.'"></i>';
        $this->E_model->update($member_e['e__id'], array(
            'e__cover' => $new_avatar,
        ), true, $member_e['e__id']);


        //Update Session:
        $member_e['e__cover'] = $new_avatar;
        $this->E_model->activate_session($member_e, true);


        return view_json(array(
            'status' => 1,
            'message' => 'Name updated',
            'new_avatar' => $new_avatar,
        ));
    }



    function e_email()
    {

        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['e_email']) || !filter_var($_POST['e_email'], FILTER_VALIDATE_EMAIL)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        }


        if (strlen($_POST['e_email']) > 0) {

            //Cleanup:
            $_POST['e_email'] = trim(strtolower($_POST['e_email']));

            //Check to make sure not duplicate:
            $duplicates = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => 3288, //Email
                'x__down !=' => $member_e['e__id'],
                'LOWER(x__message)' => $_POST['e_email'],
            ));
            if (count($duplicates) > 0) {
                //This is a duplicate, disallow:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Email previously in-use. Use another email or contact support for assistance.',
                ));
            }
        }


        //Fetch existing email:
        $u_emails = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $member_e['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Email
        ));
        if (count($u_emails) > 0) {

            if (strlen($_POST['e_email']) == 0) {

                //Delete email:
                $this->X_model->update($u_emails[0]['x__id'], array(
                    'x__status' => 6173, //Transaction Removed
                ), $member_e['e__id'], 6224 /* Member Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Email deleted',
                );

            } elseif ($u_emails[0]['x__message'] != $_POST['e_email']) {

                //Update if not duplicate:
                $this->X_model->update($u_emails[0]['x__id'], array(
                    'x__message' => $_POST['e_email'],
                ), $member_e['e__id'], 6224 /* Member Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Email updated',
                );

            } else {

                $return = array(
                    'status' => 0,
                    'message' => 'Email unchanged',
                );

            }

        } elseif (strlen($_POST['e_email']) > 0) {

            //Create new transaction:
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__down' => $member_e['e__id'],
                'x__type' => e_x__type($_POST['e_email']),
                'x__up' => 3288, //Email
                'x__message' => $_POST['e_email'],
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Email added',
            );

        } else {

            $return = array(
                'status' => 0,
                'message' => 'Email unchanged',
            );

        }


        if($return['status']){
            //Log Account Update transaction type:
            $_POST['account_update_function'] = 'e_email'; //Add this variable to indicate which My Account function created this transaction
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => 6224, //My Account updated
                'x__message' => 'My Account '.$return['message']. ( strlen($_POST['e_email']) > 0 ? ': '.$_POST['e_email'] : ''),
                'x__metadata' => $_POST,
            ));
        }


        //Return results:
        return view_json($return);


    }






    function e_phone()
    {

        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['e_phone']) || (strlen($_POST['e_phone'])>0 && intval(preg_replace("/[^0-9]/", "", $_POST['e_phone'] ))<10000000)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Phone number',
            ));
        }


        //Cleanup:
        $_POST['e_phone'] = preg_replace("/[^0-9]/", "", $_POST['e_phone'] );


        //Fetch existing phone:
        $u_phones = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $member_e['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 4783, //Phone
        ));
        if (count($u_phones) > 0) {

            if (strlen($_POST['e_phone']) == 0) {

                //Delete phone:
                $this->X_model->update($u_phones[0]['x__id'], array(
                    'x__status' => 6173, //Transaction Removed
                ), $member_e['e__id'], 6224 /* Member Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Phone deleted',
                );

            } elseif ($u_phones[0]['x__message'] != $_POST['e_phone']) {

                //Update if not the same:
                $this->X_model->update($u_phones[0]['x__id'], array(
                    'x__message' => $_POST['e_phone'],
                ), $member_e['e__id'], 6224 /* Member Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Phone updated',
                );

            } else {

                $return = array(
                    'status' => 0,
                    'message' => 'Phone unchanged',
                );

            }

        } elseif (strlen($_POST['e_phone']) > 0) {

            //Create new transaction:
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__down' => $member_e['e__id'],
                'x__type' => e_x__type($_POST['e_phone']),
                'x__up' => 4783, //Phone
                'x__message' => $_POST['e_phone'],
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Phone added',
            );

        } else {

            $return = array(
                'status' => 0,
                'message' => 'Phone unchanged',
            );

        }


        if($return['status']){
            //Log Account Update transaction type:
            $_POST['account_update_function'] = 'e_phone'; //Add this variable to indicate which My Account function created this transaction
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => 6224, //My Account updated
                'x__message' => 'My Account '.$return['message']. ( strlen($_POST['e_phone']) > 0 ? ': '.$_POST['e_phone'] : ''),
                'x__metadata' => $_POST,
            ));
        }


        //Return results:
        return view_json($return);


    }





    function e_password()
    {

        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['input_password']) || strlen($_POST['input_password']) < view_memory(6404,11066)) {
            return view_json(array(
                'status' => 0,
                'message' => 'New password must be '.view_memory(6404,11066).' characters or more',
            ));
        }

        //Fetch existing password:
        $u_passwords = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3286, //Password
            'x__down' => $member_e['e__id'],
        ));

        $hashed_password = strtolower(hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password'] . $member_e['e__id']));


        if (count($u_passwords) > 0) {

            if ($hashed_password == $u_passwords[0]['x__message']) {

                $return = array(
                    'status' => 0,
                    'message' => 'Password Unchanged',
                );

            } else {

                //Update password:
                $this->X_model->update($u_passwords[0]['x__id'], array(
                    'x__message' => $hashed_password,
                ), $member_e['e__id'], 7578 /* Member Updated Password  */);

                $return = array(
                    'status' => 1,
                    'message' => 'Password Updated',
                );

            }

        } else {

            //Create new transaction:
            $this->X_model->create(array(
                'x__type' => e_x__type($hashed_password),
                'x__up' => 3286, //Password
                'x__source' => $member_e['e__id'],
                'x__down' => $member_e['e__id'],
                'x__message' => $hashed_password,
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Password Added',
            );

        }


        //Log Account Update transaction type:
        if($return['status']){
            $_POST['account_update_function'] = 'e_password'; //Add this variable to indicate which My Account function created this transaction
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => 6224, //My Account Updated
                'x__message' => 'My Account '.$return['message'],
                'x__metadata' => $_POST,
            ));
        }


        //Return results:
        return view_json($return);

    }









    function e_signin_create(){

        if (!isset($_POST['sign_i__id']) || !isset($_POST['referrer_url'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        } elseif (!isset($_POST['input_email'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['input_name'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing name',
                'focus_input_field' => 'input_name',
            ));
        } elseif ($_POST['new_account_passcode'] != substr(preg_replace('/[^0-9.]+/', '', md5($_POST['input_email'])), 0, 4)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invaid passcode. Check your email (and spam folder) and try again.',
                'focus_input_field' => 'new_account_passcode',
            ));
        } elseif (strlen($_POST['password_reset'])<1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing password',
                'focus_input_field' => 'password_reset',
            ));
        } elseif (strlen($_POST['password_reset']) < view_memory(6404,11066)) {
            return view_json(array(
                'status' => 0,
                'message' => 'New password must be '.view_memory(6404,11066).' characters or longer',
                'focus_input_field' => 'password_reset',
            ));
        }



        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));
        $u_emails = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__message' => $_POST['input_email'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Email
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
        ), array('x__down'));



        if(count($u_emails)){

            //Member already exists:
            $member_result['e'] = $u_emails[0];

            //Assign session & log login transaction:
            $this->E_model->activate_session($member_result['e']);

        } else {

            //Prep inputs & validate further:
            $_POST['input_name'] = trim($_POST['input_name']);
            if (strlen($_POST['input_name']) < view_memory(6404,12232)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Name must longer than '.view_memory(6404,12232).' characters',
                    'focus_input_field' => 'input_name',
                ));
            } elseif (strlen($_POST['input_name']) > view_memory(6404,6197)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Name must be less than '.view_memory(6404,6197).' characters',
                    'focus_input_field' => 'input_name',
                ));
            }

            $member_result = $this->E_model->add_member(trim($_POST['input_name']), $_POST['input_email']);
            if (!$member_result['status']) {
                return view_json($member_result);
            }

        }



        //Add Password:
        $hash = strtolower(hash('sha256', $this->config->item('cred_password_salt') . $_POST['password_reset'] . $member_result['e']['e__id']));
        $this->X_model->create(array(
            'x__type' => e_x__type($hash),
            'x__message' => $hash,
            'x__up' => 3286, //Password
            'x__source' => $member_result['e']['e__id'],
            'x__down' => $member_result['e']['e__id'],
        ));


        if (strlen(urldecode($_POST['referrer_url'])) > 1) {

            $sign_url = urldecode($_POST['referrer_url']);

        } else {

            //Go to home page and let them continue from there:
            $sign_url = new_member_redirect($member_result['e']['e__id'], intval($_POST['sign_i__id']));

        }

        //Created account:
        return view_json(array(
            'status' => 1,
            'sign_url' => $sign_url,
        ));

    }



    function e_signin_password(){

        if (!isset($_POST['sign_e__id']) || intval($_POST['sign_e__id'])<1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing user ID',
            ));
        } elseif (!isset($_POST['input_password']) || strlen($_POST['input_password']) < view_memory(6404,11066)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Password',
            ));
        } elseif (!isset($_POST['referrer_url'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing referrer URL',
            ));
        } elseif (!isset($_POST['sign_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea referrer',
            ));
        }



        //Validaye member ID
        $es = $this->E_model->fetch(array(
            'e__id' => $_POST['sign_e__id'],
        ));
        if (!in_array($es[0]['e__type'], $this->config->item('n___7357') /* PRIVATE */)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Your account source is not public. Contact us to adjust your account.',
            ));
        }


        //Authenticate password:
        $es[0]['is_masterpass_login'] = 0;

        //Is this the master password?
        if(hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password']) == view_memory(6404,13014)){

            $es[0]['is_masterpass_login'] = 1;

        } elseif(!count($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3286, //Password
            'x__message' => hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password'] . $es[0]['e__id']),
            'x__down' => $es[0]['e__id'],
        )))) {

            return view_json(array(
                'status' => 0,
                'message' => 'Incorrect password',
            ));

        }

        //Assign session & log transaction:
        $this->E_model->activate_session($es[0]);


        if (intval($_POST['sign_i__id']) > 0) {

            $sign_url = '/x/x_start/'.$_POST['sign_i__id'];

        } elseif (isset($_POST['referrer_url']) && strlen(urldecode($_POST['referrer_url'])) > 1) {

            $sign_url = urldecode($_POST['referrer_url']);

        } else {
            $sign_url = '/@'.$es[0]['e__id'];
        }

        return view_json(array(
            'status' => 1,
            'sign_url' => $sign_url,
        ));

    }




    function e_magic_email(){


        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['sign_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        $has_i = ( intval($_POST['sign_i__id']) > 0 );
        if($has_i){
            $is = $this->I_model->fetch(array('i__id' => $_POST['sign_i__id']));
            $has_i = count($is);
        }


        //Cleanup/validate email:
        $_POST['input_email'] = trim(strtolower($_POST['input_email']));
        $u_emails = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__message' => $_POST['input_email'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Email
        ), array('x__down'));
        if(count($u_emails) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Email not associated with a registered account',
            ));
        }

        //Log email search attempt:
        $reset_x = $this->X_model->create(array(
            'x__type' => 7563, //Member Signin Magic Email
            'x__source' => $u_emails[0]['e__id'], //Member making request
            'x__message' => $_POST['input_email'],
            'x__left' => intval($_POST['sign_i__id']),
        ));

        //This is a new email, send invitation to join:

        ##Email Subject
        $e___11035 = $this->config->item('e___11035'); //NAVIGATION
        $subject = $e___11035[11068]['m__title'].' | '.get_domain('m__title', $u_emails[0]['e__id']);

        ##Email Body
        $magic_x_expiry_hours = (view_memory(6404,11065)/3600);

        //Send email:
        $this->X_model->send_dm($u_emails[0]['e__id'], $subject, 'Login within the next '.$magic_x_expiry_hours.' hour'.view__s($magic_x_expiry_hours).( $has_i ? ' to discover '.$is[0]['i__title'] : '' ).':'."\n" . $this->config->item('base_url').'/e/e_magic_sign/' . $reset_x['x__id'] . '?email='.$_POST['input_email']);

        //Return success
        return view_json(array(
            'status' => 1,
        ));
    }

    function e_toggle_e(){

        $member_e = superpower_unlocked();

        if(!$member_e){

            return view_json(array(
                'status' => 0,
                'message' => 'You must login to continue...',
            ));

        } elseif(!isset($_POST['x__source']) || !isset($_POST['e__id']) || !isset($_POST['i__id']) || !isset($_POST['x__id'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variable',
            ));

        } elseif( !in_array($_POST['e__id'], $this->config->item('n___28714'))){

            return view_json(array(
                'status' => 0,
                'message' => 'This source is not editable via @28714',
            ));

        } else {

            $already_added = $this->X_model->fetch(array(
                'x__up' => $_POST['e__id'],
                'x__down' => $_POST['x__source'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ));

            if(count($already_added)){

                //Already exists, let's remove:
                $this->X_model->update($already_added[0]['x__id'], array(
                    'x__status' => 6173, //Transaction Deleted
                ), $member_e['e__id'], 10673 /* Member Transaction Unpublished */);

                return view_json(array(
                    'status' => 1,
                    'message' => ' ',
                ));

            } else {

                //Does not exist, Add:
                $this->X_model->create(array(
                    'x__up' => $_POST['e__id'],
                    'x__down' => $_POST['x__source'],
                    'x__source' => $member_e['e__id'],
                    'x__type' => e_x__type(),
                ));

                $e___28714 = $this->config->item('e___28714');

                return view_json(array(
                    'status' => 1,
                    'message' => $e___28714[$_POST['e__id']]['m__cover'],
                ));

            }

        }

    }

    function e_magic_sign($x__id){

        //Remove Session:
        session_delete();

        //Validate email:
        if(!isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
            //Missing email input:
            return redirect_message('/-4269', '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing Email</div>', true);
        }

        //Validate DISCOVERY ID and matching email:
        $validate_x = $this->X_model->fetch(array(
            'x__id' => $x__id,
            'x__message' => $_GET['email'],
            'x__type' => 7563, //Member Signin Magic Email
        )); //The member making the request
        if(count($validate_x) < 1){
            //Probably previously completed the reset password:
            return redirect_message('/-4269?input_email='.$_GET['email'], '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Invalid data source</div>', true);
        } elseif(strtotime($validate_x[0]['x__time']) + view_memory(6404,11065) < time()){
            //Probably previously completed the reset password:
            return redirect_message('/-4269?input_email='.$_GET['email'], '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Magic transaction has expired. Try again.</div>');
        }



        //Fetch source:
        $es = $this->E_model->fetch(array(
            'e__id' => $validate_x[0]['x__source'],
        ));
        if(count($es) < 1){
            return redirect_message('/-4269?input_email='.$_GET['email'], '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Member not found</div>', true);
        }


        //Log them in:
        $this->E_model->activate_session($es[0]);


        //Take them to DISCOVERY HOME
        return redirect_message(($validate_x[0]['x__left'] > 0 ? '/x/x_start/'.$validate_x[0]['x__left'] : home_url() ), '<div class="msg alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully signed in.</div>');

    }

    function e_signin_email(){

        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['sign_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }


        //Cleanup input email:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));


        if(intval($_POST['sign_i__id']) > 0){
            //Fetch the idea:
            $referrer_i = $this->I_model->fetch(array(
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'i__id' => $_POST['sign_i__id'],
            ));
        } else {
            $referrer_i = array();
        }


        //Search for email to see if it exists...
        $u_emails = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__message' => $_POST['input_email'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Email
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
        ), array('x__down'));


        $u_passwords = array();
        if(count($u_emails)){
            //See if this user has set a password before:
            $u_passwords = $this->X_model->fetch(array(
                'x__up' => 3286, //Password
                'x__down' => $u_emails[0]['e__id'],
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            ));
        }


        if(!count($u_emails) || !count($u_passwords)){
            //Send Email Verification Pass Code
            email_send(array($_POST['input_email']), get_domain('m__title').' Email Verification Pass Code', 'Your pass code to create your new account is ['.substr(preg_replace('/[^0-9.]+/', '', md5($_POST['input_email'])), 0, 4).']', ( count($u_emails) ? $u_emails[0]['e__id'] : 0 ));
        }

        return view_json(array(
            'status' => 1,
            'email_existed_previously' => ( count($u_emails) ? 1 : 0 ),
            'password_existed_previously' => ( count($u_passwords) ? 1 : 0 ),
            'sign_e__id' => ( count($u_emails) ? $u_emails[0]['e__id'] : 0 ),
            'clean_email_input' => $_POST['input_email'],
        ));

    }



}