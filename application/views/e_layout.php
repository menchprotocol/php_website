<?php


$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$this->X_model->create(array(
    'x__source' => $member_e['e__id'],
    'x__type' => 4994, //Member Viewed Source
    'x__down' => $e['e__id'],
    'x__spectrum' => $new_order,
));

$e___6177 = $this->config->item('e___6177'); //Source Status
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$source_of_e = source_of_e($e['e__id']);
$source_is_e = $e['e__id']==$member_e['e__id'];
$superpower_10939 = superpower_active(10939, true); //SUPERPOWER OF IDEAGING
$superpower_13422 = superpower_active(13422, true); //SUPERPOWER OF SOURCING
$superpower_12701 = superpower_active(12701, true); //SUPERPOWER OF GLASSES
$superpower_12703 = superpower_active(12703, true); //SUPERPOWER OF CHAIN LINK
$show_max_14435 = view_memory(6404,14435);
$show_max_14538 = view_memory(6404,14538);
$limit = view_memory(6404,11064);
$found_10573 = false;

?>

<script>
    //Set global variables:
    var e_focus_filter = -1; //No filter, show all
</script>
<input type="hidden" id="focus__type" value="12274" />
<input type="hidden" id="focus__id" value="<?= $e['e__id'] ?>" />
<script src="/application/views/e_layout.js?v=<?= view_memory(6404,11060) ?>" type="text/javascript"></script>

<?php

//PROFILE
if(!$source_is_e || $superpower_13422){

    $profiles = $this->X_model->fetch(array(
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        'x__down' => $e['e__id'],
    ), array('x__up'), 0, 0, array('e__spectrum' => 'DESC'));

    $counter = count($profiles);
    $has_more = $counter>($show_max_14538+1) && 0; //Disabled for now
    $trigger_hide = null;


    $profile_ui = '<div id="list-in-11030" class="row justify-content-center">';
    $counter = 0; //Recount
    foreach($profiles as $e_link) {

        if($counter==$show_max_14538 && $has_more){
            $profile_ui .= view_show_more(14538, 'see_all_11030');
        }

        if($counter>=$show_max_14538 && $has_more){
            $trigger_hide = 'see_all_11030 hidden';
        }

        $profile_ui .= view_e(11030, $e_link, $trigger_hide,  ($source_of_e || ($member_e && ($member_e['e__id']==$e_link['x__source']))));
        $counter++;

    }
    $profile_ui .= '</div>';

    if($superpower_13422){
        $profile_ui .= '<div class="'.$trigger_hide.'"><div class="headline-height"><div class="new-list-11030 list-adder">
                <div class="input-group border">
                    <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.new-list-11030 .add-input\').focus();"><span class="icon-block">'.$e___11035[14055]['m__cover'].'</span></a>
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent add-input"
                           maxlength="' . view_memory(6404,6197) . '"
                           placeholder="'.$e___11035[14055]['m__title'].'">
                </div><div class="algolia_pad_search row justify-content-center"></div></div></div></div>';
    }

    echo view_headline(11030, $counter, $e___11035[11030], $profile_ui, ($counter<=2));


}

//Focus Source:
echo '<div class="row justify-content-center">';
echo view_e(4251, $e, null, $source_of_e);
echo '</div>';


foreach($this->config->item('e___11089') as $x__type => $m) {

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

    $counter = null;
    $ui = null;

    if(strlen($m['m__message']) > 0){
        $ui .= '<div class="msg" style="padding-bottom: 13px;"><span class="icon-block"><i class="fas fa-info-circle black"></i></span>'.$m['m__message'].'</div>';
    }

    if(in_array($x__type, $this->config->item('n___6194'))){

        //SOURCE REFERENCE:
        $e_count_6194 = e_count_6194($e['e__id'], $x__type);
        $counter = ( isset($e_count_6194[$x__type]) ? $e_count_6194[$x__type] : 0 );
        if($counter){
            $ui .= '<div><span class="icon-block">&nbsp;</span>Source referenced as '.$m['m__cover'].' '.$m['m__title'].' '.number_format($counter, 0).' times.</div>';
        }

    } elseif($x__type==12274){

        //SOURCES
        $counter = view_coins_e(12274, $e['e__id'], 0, false);
        $list_e = view_coins_e(12274, $e['e__id'], 1);

        //SOURCE MASS EDITOR
        //Source Status Filters:
        if(superpower_active(14005, true)){

            $e_count = $this->E_model->child_count($e['e__id'], $this->config->item('n___7358') /* ACTIVE */);
            $child__filters = $this->X_model->fetch(array(
                'x__up' => $e['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ), array('x__down'), 0, 0, array('e__type' => 'ASC'), 'COUNT(e__id) as totals, e__type', 'e__type');

            //Only show filtering UI if we find child sources with different Status (Otherwise no need to filter):
            if (count($child__filters) > 0 && $child__filters[0]['totals'] < $e_count) {

                //Add 2nd Navigation to UI
                $ui .= '<div class="nav nav-pills nav-sm">';

                //Show fixed All button:
                $ui .= '<li class="nav-item"><a href="javascript:void(0)" onclick="e_filter_status(11029, -1)" class="nav-x e_filter_status_11029 active en_status_11029_-1" data-toggle="tooltip" data-placement="top" title="View all sources"><i class="fas fa-asterisk zq12274"></i><span class="zq12274">&nbsp;' . $e_count . '</span></a></li>';

                //Show each specific filter based on DB counts:
                foreach($child__filters as $c_c) {
                    $st = $e___6177[$c_c['e__type']];
                    $ui .= '<li class="nav-item"><a href="javascript:void(0)" onclick="e_filter_status(11029, ' . $c_c['e__type'] . ')" class="nav-x nav-link e_filter_status_11029 en_status_11029_' . $c_c['e__type'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['m__message'] . '">' . $st['m__cover'] . '&nbsp;' . $c_c['totals'] . '<span class="show-max">&nbsp;' . $st['m__title'] . '</span></a></li>';
                }

                $ui .= '</div>';

            }
        }

        $ui .= '<div id="list-in-11029" class="row justify-content-center hideIfEmpty">';


        $count = 0;
        $has_more = count($list_e)>($show_max_14435+1);
        $trigger_hide = null;
        $count_totals = array();
        foreach($list_e as $e_link) {

            if($count==$show_max_14435 && $has_more){
                $ui .= view_show_more(14435, 'see_all_11029');
            }

            if($count>=$show_max_14435 && $has_more){
                $trigger_hide = 'see_all_11029 hidden';
            }

            if(strlen($e_link['x__message'])>0 && in_array($e_link['x__type'], $this->config->item('n___26111'))){
                if(!isset($count_totals[$e_link['x__type']])){
                    $count_totals[$e_link['x__type']] = 0;
                }
                $count_totals[$e_link['x__type']] += preg_replace("/[^0-9.]/", '', $e_link['x__message']);
            }

            $ui .= view_e(11029, $e_link, $trigger_hide,  ($source_of_e || ($member_e && ($member_e['e__id']==$e_link['x__source']))));
            $count++;
        }

        if ($counter > count($list_e)) {
            //Load even more if there...
            $ui .= e_load_page(11029, 1, $limit, $counter, $trigger_hide);
        }

        $ui .= '</div>';
        //Input to add new child:
        if($superpower_13422){

            $ui .= '<div class="'.$trigger_hide.'"><div current-count="'.$counter.'" class="new-list-11029 list-adder '.superpower_active(10939).'">
                    <div class="input-group border '.$trigger_hide.'">
                        <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.new-list-11029 .add-input\').focus();"><span class="icon-block">'.$e___11035[14055]['m__cover'].'</span></a>
                        <input type="text"
                               class="form-control form-control-thick algolia_search dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="'.$e___11035[14055]['m__title'].'">
                    </div><div class="algolia_pad_search row justify-content-center"></div></div></div>';

        } else {

            $ui .= '<div class="hideIfEmpty new-list-11029"></div>';

        }

        if(count($count_totals)){
            $e___26111 = $this->config->item('e___26111');
            $ui .= '<div style="padding: 21px 0 0 0;">Totals: ';
            foreach($count_totals as $total_type => $total_value){
                $ui .= $e___26111[$total_type]['m__title'].' '.number_format($total_value, 2).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            $ui .= '</div>';
        }

    } elseif($x__type==10573){

        //STARRED IDEAS
        $i_stars = $this->X_model->fetch(array(
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 10573, //STARRED
            'x__up' => $e['e__id'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));
        $counter = count($i_stars);

        if(!$source_is_e && !$counter){
            continue;
        }

        $found_10573 = ( $counter > 0);


        //Any Ideas?
        if($counter || $source_is_e){

            //Need 2 or more to sort...
            $ui .= ( count($i_stars) >= 2 ? '<script> $(document).ready(function () {x_sort_load(10573)}); </script>' : '<style> #list-in-10573 .x_sort {display:none !important;} </style>' );

            $ui .= '<div class="row justify-content-center hideIfEmpty" id="list-in-10573">';
            foreach($i_stars as $item){
                $ui .= view_i(10573, 0, null, $item,null, $e);
            }
            $ui .= '</div>';

        }

        //Add Idea:
        if($superpower_10939 && $source_is_e){

            //Give Option to Add New Idea:
            $ui .= '<div class="new-list-10573 list-group"><div class="list-group-item list-adder">
                <div class="input-group border">
                    <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.new-list-10573 .add-input\').focus();"><span class="icon-block">'.$e___11035[14016]['m__cover'].'</span></a>
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent add-input"
                           maxlength="' . view_memory(6404,4736) . '"
                           placeholder="'.$e___11035[14016]['m__title'].'">
                </div><div class="algolia_pad_search row justify-content-center"></div></div></div>';

            $ui .= '<script> $(document).ready(function () { i_load_search(10573); }); </script>';

        }

    } elseif($x__type==12273){

        //IDEAS (Referenced)
        $count = 0;
        $counter = view_coins_e(12273, $e['e__id'], 0, false);
        $list_i = view_coins_e(12273, $e['e__id'], 1, true);
        $has_more = $counter>($show_max_14435+1);
        $trigger_hide = null;
        $max_seconds = intval(view_memory(6404,14841));
        $max_i__spectrum = 0;

        $ui .= '<div class="row justify-content-center hideIfEmpty" id="list-in-13550">';
        foreach($list_i as $count => $item){

            $i_stats = i_stats($item['i__metadata']);
            $show_message = strlen($item['x__message']) && trim($item['x__message'])!=$this->uri->segment(1); //Basic references only

            //SHow or Hide?
            if($has_more && $count==$show_max_14435){
                $ui .= view_show_more(14435, 'see_all_13550');
                $trigger_hide = 'see_all_13550 hidden';
            }

            //UI
            $ui .= view_i(13550, 0, null, $item,( $show_message ? $this->X_model->message_view($item['x__message'], true) : null), $e, null, $trigger_hide);

            $max_i__spectrum = $item['i__spectrum'];

        }

        if ($counter > count($list_i)) {
            //We have even more:
            $ui .= i_load_page(13550, 1, $limit, $counter, $trigger_hide);
        }

        $ui .= '</div>';

        $ui .= ( $counter >= 2 ? '<script> $(document).ready(function () {x_sort_load(13550)}); </script>' : '<style> #list-in-13550 .x_sort {display:none !important;} </style>' ); //Need 2 or more to sort



        if($superpower_10939 && !$source_is_e){

            //Give Option to Add New Idea:
            $ui .= '<div class="new-list-13550 list-group"><div class="list-group-item list-adder">
                <div class="input-group border">
                    <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.new-list-13550 .add-input\').focus();"><span class="icon-block">'.$e___11035[14016]['m__cover'].'</span></a>
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent add-input"
                           maxlength="' . view_memory(6404,4736) . '"
                           placeholder="'.$e___11035[14016]['m__title'].'">
                </div><div class="algolia_pad_search row justify-content-center"></div></div></div>';

            $ui .= '<script> $(document).ready(function () { i_load_search(13550); }); </script>';

        }

    } elseif($x__type==12896){

        //SAVED DISCOVERIES
        $i_notes_query = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type' => 12896,
            'x__up' => $e['e__id'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC', 'x__id' => 'DESC'));
        $counter = count($i_notes_query);

        if($counter > 0){

            $ui .= '<div class="row justify-content-center hideIfEmpty" id="list-in-12896">';
            foreach($i_notes_query as $count => $i_notes) {
                $ui .= view_i(12896, $i_notes['x__left'], null, $i_notes);
            }
            $ui .= '</div>';

            $ui .= ( $counter >= 2 ? '<script> $(document).ready(function () {x_sort_load(12896)}); </script>' : '<style> #list-in-12896 .x_sort {display:none !important;} </style>' ); //Need 2 or more to sort

        }

    } elseif($x__type==12969 || $x__type==6255){

        //STARTED & DISCOVERIES
        $counter = view_coins_e($x__type, $e['e__id'], 0, false);

        //Show My discoveries
        if($counter){

            $list_x  = view_coins_e($x__type, $e['e__id'], 1);

            $ui .= '<div class="row justify-content-center hideIfEmpty" id="list-in-12969">';
            foreach($list_x as $item){
                $ui .= view_i($x__type, $item['i__id'], null, $item,null, $e);
            }
            $ui .= '</div>';

        }

        if($source_is_e && $x__type==12969){

            //Sorting
            $ui .= ( $counter >= 2 ? '<script> $(document).ready(function () {x_sort_load(12969)}); </script>' : '<style> #list-in-12969 .x_sort {display:none !important;} </style>' ); //Need 2 or more to sort

        }

    } elseif(in_array($x__type, $this->config->item('n___4485'))){

        //IDEA NOTES
        $i_notes_filters = array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type' => $x__type,
            'x__up' => $e['e__id'],
        );

        //COUNT ONLY
        $item_counters = $this->X_model->fetch($i_notes_filters, array('x__right'), 0, 0, array(), 'COUNT(i__id) as totals');
        $counter = $item_counters[0]['totals'];
        if($counter>0){
            $ui .= '<div class="row justify-content-center">';
            $i_notes_query = $this->X_model->fetch($i_notes_filters, array('x__right'), $limit, 0, array('i__spectrum' => 'DESC'));
            foreach($i_notes_query as $count => $i_notes) {
                $ui .= view_i(4485, 0, null, $i_notes);
            }
            $ui .= '</div>';
        }

    } elseif(in_array($x__type, $this->config->item('n___12149'))){

        //Ideas/Sources Owned
        $owner_filters = array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => $x__type,
            'x__source' => $e['e__id'],
        );
        $item_counters = $this->X_model->fetch($owner_filters, array(), 0, 0, array(), 'COUNT(x__id) as totals');
        $counter = $item_counters[0]['totals'];
        if($counter){
            $ui .= '<div class="row justify-content-center">';
            if($x__type==4250){
                foreach($this->X_model->fetch($owner_filters, array('x__right'), $limit, 0, array('i__spectrum' => 'DESC')) as $count => $i_own) {
                    $ui .= view_i(4250, 0, null, $i_own);
                }
            } elseif($x__type==4251) {
                foreach($this->X_model->fetch($owner_filters, array('x__down'), $limit, 0, array('e__spectrum' => 'DESC')) as $count => $e_own) {
                    $ui .= view_e(4251, $e_own, source_of_e($e['e__id']));
                }
            }

            $ui .= '</div>';
        }

    }

    echo view_headline($x__type, $counter, $m, $ui, in_array($x__type, $this->config->item('n___26005')) && $counter > 0);

}


//Add New Discovery Button:
//if($source_is_e){ echo '<div class="center" style="padding-top:89px;"><a class="btn btn-lrg btn-6255" href="/">'.$e___11035[18995]['m__cover'].' '.$e___11035[18995]['m__title'].' <i class="far fa-arrow-right"></i></a></div>'; }


?>