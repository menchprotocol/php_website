
<?php
$en_all_6201 = $this->config->item('en_all_6201'); //Tree Table
$en_all_4485 = $this->config->item('en_all_4485'); //Tree Pads
$en_all_2738 = $this->config->item('en_all_2738');

$is_author = in_is_author($in['in_id']);
$is_active = in_array($in['in_status_source_id'], $this->config->item('en_ids_7356'));
?>

<style>
    .in_child_icon_<?= $in['in_id'] ?> { display:none; }
    <?= ( !$is_author ? '.pads-edit {display:none;}' : '' ) ?>
</style>


<script>
    //Include some cached sources:
    var in_loaded_id = <?= $in['in_id'] ?>;
</script>
<script src="/application/views/tree/tree_coin.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
<script src="/application/views/tree/tree_shared.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<?php

$source_focus_found = false; //Used to determine the first tab to be opened




//TREE PREVIOUS
$in__parents = $this->READ_model->ln_fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Tree Status Active
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Tree-to-Tree Links
    'ln_next_tree_id' => $in['in_id'],
), array('in_parent'), 0);
$in_previous = '<div id="list-in-' . $in['in_id'] . '-1" class="list-group previous_ins">';
foreach ($in__parents as $parent_in) {
    $in_previous .= echo_in($parent_in, 0, true, in_is_author($parent_in['in_id']));
}
if( $is_author && $is_active && $in['in_id']!=config_var(12156)){
    $in_previous .= '<div class="list-group-item itemtree '.superpower_active(10984).'" style="padding:5px 0;">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean" style="margin-top: 6px;"><span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control treeadder-level-2-parent form-control-thick algolia_search dotransparent"
                           maxlength="' . config_var(11071) . '"
                           tree-id="' . $in['in_id'] . '"
                           id="addtree-c-' . $in['in_id'] . '-1"
                           style="margin-bottom: 0; padding: 5px 0;"
                           placeholder="PREVIOUS TREE">
                </div><div class="algolia_pad_search hidden in_pad_top"></div></div>';
}
$in_previous .= '</div>';






echo '<div class="container" style="padding-bottom:42px;">';


if(!$is_author){
    echo '<div class="alert alert-warning no-margin"><span class="icon-block"><i class="fad fa-exclamation-triangle"></i></span>You are not an author of this tree, yet. <a href="/tree/in_request_invite/'.$in['in_id'].'" class="inline-block montserrat">REQUEST INVITE</a><span class="inline-block '.superpower_active(10985).'">&nbsp;or <a href="/tree/in_become_author/'.$in['in_id'].'" class="montserrat">BECOME AUTHOR</a></span></div>';
}

foreach ($this->config->item('en_all_11021') as $en_id => $m){

    $tab_content = '';
    $tab_is_active = false;
    $show_tab_menu_count = 0;
    $show_tab_ui = '';

    if($en_id==12365){

        //TREE BODY

        //TREE PREVIOUS
        echo $in_previous;

        //TREE TITLE
        echo '<div class="itemtree">';
        echo echo_in_text(4736, $in['in_title'], $in['in_id'], ($is_author && $is_active), 0, true);
        echo '<div class="title_counter hidden grey montserrat doupper" style="text-align: right;"><span id="charTitleNum">0</span>/'.config_var(11071).' CHARACTERS</div>';
        echo '</div>';


    } elseif($en_id==11018){

        //TREE CONTROLLER

        //TREE STATUS
        echo '<div class="inline-block both-margin left-margin">'.echo_in_dropdown(4737, $in['in_status_source_id'], 'btn-tree', $is_author, true, $in['in_id']).'</div>';

        //TREE TYPE
        echo '<span class="inline-block both-margin left-half-margin">'.echo_in_dropdown(7585, $in['in_type_source_id'], 'btn-tree', $is_author && $is_active, true, $in['in_id']).'</span>';

        //TREE TIME
        echo '<div class="inline-block both-margin left-half-margin '.superpower_active(10984).'">'.echo_in_text(4356, $in['in_read_time'], $in['in_id'], $is_author && $is_active, 0).'</div>';

    }


    //Display the content:
    foreach ($this->config->item('en_all_'.$en_id) as $en_id2 => $m2){


        //Is this a caret menu?
        if(in_array(11040 , $m2['m_parents'])){
            $show_tab_ui .= echo_caret($en_id2, $m2, $in['in_id']);
            continue;
        }


        $counter = null; //Assume no counters
        $this_tab = '';


        //TREE
        if($en_id2==11019 && 0){

            $this_tab .= $in_previous;
            $counter = count($in__parents);

        } elseif($en_id2==11020){

            //TREE NEXT
            $in__children = $this->READ_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Tree Status Active
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Tree-to-Tree Links
                'ln_previous_tree_id' => $in['in_id'],
            ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

            $counter = count($in__children);
            $tab_is_active = true;

            //List child trees:
            //$this_tab .= '<div class="read-topic"><span class="icon-block"><i class="fad fa-step-forward"></i></span>NEXT:</div>';
            $this_tab .= '<div id="list-in-' . $in['in_id'] . '-0" class="list-group next_ins">';
            foreach ($in__children as $child_in) {
                $this_tab .= echo_in($child_in, $in['in_id'], false, $is_author);
            }

            if($is_author && $is_active){
                $this_tab .= '<div class="list-group-item itemtree '.superpower_active(10939).'" style="padding:5px 0;">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean" style="margin-top: 6px;"><span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control treeadder-level-2-child form-control-thick algolia_search dotransparent"
                           maxlength="' . config_var(11071) . '"
                           tree-id="' . $in['in_id'] . '"
                           id="addtree-c-' . $in['in_id'] . '-0"
                           style="margin-bottom: 0; padding: 5px 0;"
                           placeholder="NEXT TREE">
                </div><div class="algolia_pad_search hidden in_pad_bottom"></div></div>';
            }

        } elseif(in_array($en_id2, $this->config->item('en_ids_4485'))){

            //TREE PADS
            $in_pads = $this->READ_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'ln_type_source_id' => $en_id2,
                'ln_next_tree_id' => $in['in_id'],
            ), array(), 0, 0, array('ln_order' => 'ASC'));

            $counter = count($in_pads);

            if($en_id2==4231){
                $tab_is_active = true; //TREE MESSAGES
            }



            //Show no-Message notifications for each message type:
            $this_tab .= '<div id="in_pads_list_'.$en_id2.'" class="list-group">';

            foreach ($in_pads as $in_pads) {
                $this_tab .= echo_in_pads($in_pads);
            }

            //ADD NEW Alert:
            $this_tab .= '<div class="list-group-item itemtree add_pads_' . $en_id2 . ( $is_author && $is_active ? '' : ' hidden ' ).'">';
            $this_tab .= '<div class="add_pads_form">';
            $this_tab .= '<form class="box box' . $en_id2 . '" method="post" enctype="multipart/form-data" class="'.superpower_active(10939).'">'; //Used for dropping files



            $this_tab .= '<textarea onkeyup="in_new_pads_count('.$en_id2.')" class="form-control msg pads-textarea algolia_search new-pads" pads-type-id="' . $en_id2 . '" id="ln_content' . $en_id2 . '" placeholder="WRITE'.( in_array($en_id2, $this->config->item('en_ids_7551')) || in_array($en_id2, $this->config->item('en_ids_4986')) ? ', PASTE URL' : '' ).( in_array($en_id2, $this->config->item('en_ids_12359')) ? ', DRAG FILE' : '' ).'" style="margin-top:6px;"></textarea>';



            $this_tab .= '<table class="table table-condensed hidden" id="pads_control_'.$en_id2.'"><tr>';

            //Save button:
            $this_tab .= '<td style="width:85px; padding: 10px 0 0 0;"><a href="javascript:in_pads_add('.$en_id2.');" class="btn btn-tree save_pads_'.$en_id2.'">ADD</a></td>';

            //File counter:
            $this_tab .= '<td class="remove_loading" class="remove_loading" style="padding: 10px 0 0 0; font-size: 0.85em;"><span id="treePadsNewCount' . $en_id2 . '" class="hidden"><span id="charNum' . $en_id2 . '">0</span>/' . config_var(11073).'</span></td>';

            //First Name:
            $this_tab .= '<td class="remove_loading '.superpower_active(10967).'" style="width:42px; padding: 10px 0 0 0;"><a href="javascript:in_pads_insert_string('.$en_id2.', \'/firstname \');" data-toggle="tooltip" title="Mention readers first name" data-placement="top"><span class="icon-block"><i class="far fa-fingerprint"></i></span></a></td>';

            //YouTube Embed
            $this_tab .= '<td class="remove_loading '.superpower_active(10984).'" style="width:42px; padding: 10px 0 0 0;"><a href="javascript:in_pads_insert_string('.$en_id2.', \'https://www.youtube.com/embed/VIDEO_ID_HERE?start=&end=\');" data-toggle="tooltip" title="YouTube Clip with Start & End Seconds" data-placement="top"><span class="icon-block"><i class="fab fa-youtube"></i></span></a></td>';

            //Reference Player
            $this_tab .= '<td class="remove_loading '.superpower_active(10983).'" style="width:42px; padding: 10px 0 0 0;"><a href="javascript:in_pads_insert_string('.$en_id2.', \'@\');" data-toggle="tooltip" title="Reference SOURCE" data-placement="top"><span class="icon-block"><i class="far fa-at"></i></span></a></td>';

            //Upload File:
            if(in_array(12359, $en_all_4485[$en_id2]['m_parents'])){
                $this_tab .= '<td class="remove_loading" style="width:36px; padding: 10px 0 0 0;">';
                $this_tab .= '<input class="inputfile hidden" type="file" name="file" id="fileTreeType'.$en_id2.'" />';
                $this_tab .= '<label class="file_label_'.$en_id2.'" for="fileTreeType'.$en_id2.'" data-toggle="tooltip" title="Upload files up to ' . config_var(11063) . 'MB" data-placement="top"><span class="icon-block"><i class="far fa-paperclip"></i></span></label>';
                $this_tab .= '</td>';
            }


            $this_tab .= '</tr></table>';


            //Response result:
            $this_tab .= '<div class="pads_error_'.$en_id2.'"></div>';


            $this_tab .= '</form>';
            $this_tab .= '</div>';
            $this_tab .= '</div>';

            $this_tab .= '</div>';

        } elseif(in_array($en_id2, $this->config->item('en_ids_12410'))){

            //READER READS & BOOKMARKS
            $item_counters = $this->READ_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_'.$en_id2)) . ')' => null,
                'ln_previous_tree_id' => $in['in_id'],
            ), array(), 1, 0, array(), 'COUNT(ln_id) as totals');

            $counter = $item_counters[0]['totals'];

            if($counter > 0){

                //Dynamic Loading when clicked:
                $this_tab .= '<div class="dynamic-reads"></div>';

            } else {

                //Inform that nothing was found:
                $en_all_12410 = $this->config->item('en_all_12410');
                $this_tab .= '<div class="alert alert-warning"><span class="icon-block">'.$en_all_12410[$en_id2]['m_icon'].'</span><span class="montserrat '.extract_icon_color($en_all_12410[$en_id2]['m_icon']).'">'.$en_all_12410[$en_id2]['m_name'].'</span> is not added yet.</div>';

            }

        } elseif($en_id2==12589){

            //NEXT EDITOR

            $dropdown_options = '';
            $input_options = '';
            $counter = 0;

            foreach ($this->config->item('en_all_12589') as $action_en_id => $mass_action_en) {

                $counter++;
                $dropdown_options .= '<option value="' . $action_en_id . '">' .$mass_action_en['m_name'] . '</option>';
                $is_upper = false;


                //Start with the input wrapper:
                $input_options .= '<span id="mass_id_'.$action_en_id.'" title="'.$mass_action_en['m_desc'].'" class="inline-block '. ( $counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';

                if(in_array($action_en_id, array(12591, 12592))){

                    //Source search box:

                    //String command:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'"  placeholder="Search Sources..." class="form-control algolia_search en_quick_search border montserrat '.$is_upper.'">';

                    //We don't need the second value field here:
                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';

                } elseif(in_array($action_en_id, array(12611, 12612))){

                    //Tree search box:

                    //String command:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'"  placeholder="Search Trees..." class="form-control algolia_search in_quick_search border montserrat '.$is_upper.'">';

                    //We don't need the second value field here:
                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';

                } elseif(in_array($action_en_id, array(12611, 12612))){

                    $input_options .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fad fa-exclamation-triangle"></i></span>Trees will be archived.</div>';

                    //No values for this:
                    $input_options .= '<input type="hidden" name="mass_value1_'.$action_en_id.'" value="" />';
                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';

                }

                $input_options .= '</span>';

            }

            $this_tab .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important; margin-left: 33px;">';
            $this_tab .= '<div class="inline-box">';

            //Drop Down
            $this_tab .= '<select class="form-control border" name="mass_action_en_id" id="set_mass_action">';
            $this_tab .= $dropdown_options;
            $this_tab .= '</select>';

            $this_tab .= $input_options;

            $this_tab .= '<div><input type="submit" value="APPLY" class="btn btn-tree inline-block"></div>';

            $this_tab .= '</div>';
            $this_tab .= '</form>';

        } else {

            //Not supported via here:
            continue;

        }


        $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m2['m_parents']);
        if((count($superpower_actives) && !superpower_assigned(end($superpower_actives))) || (in_array($en_id2, $this->config->item('en_ids_12410')) && intval($counter) < 1)){
            continue;
        }

        //Populate tab content:
        $show_tab_menu_count++;
        $show_tab_ui .= '<li class="nav-item '.( !$tab_is_active && count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a class="nav-link tab-nav-'.$en_id.' tab-head-'.$en_id2.' '.( $tab_is_active ? ' active ' : '' ).extract_icon_color($m2['m_icon']).'" href="javascript:void(0);" onclick="loadtab('.$en_id.','.$en_id2.', '.$in['in_id'].', 0)" data-toggle="tooltip" data-placement="top" title="'.$m2['m_name'].'">'.$m2['m_icon'].( is_null($counter) ? '' : ' <span class="counter-'.$en_id2.'">'.echo_number($counter).'</span>' ).'</a></li>';


        $tab_content .= '<div class="tab-content tab-group-'.$en_id.' tab-data-'.$en_id2.( $tab_is_active ? '' : ' hidden ' ).'">';
        $tab_content .= $this_tab;
        $tab_content .= '</div>';

        $tab_is_active = false;

    }


    if($show_tab_menu_count > 0){
        echo '<ul class="nav nav-tabs nav-sm '.superpower_active(10984).'">';
        echo $show_tab_ui;
        echo '</ul>';
    }

    echo $tab_content;

}

echo '</div>';

?>