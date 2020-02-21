
<?php
$en_all_6201 = $this->config->item('en_all_6201'); //Idea Table
$en_all_4485 = $this->config->item('en_all_4485'); //Idea Notes
$en_all_2738 = $this->config->item('en_all_2738');

$is_author = in_is_author($in['in_id']);
$is_active = in_array($in['in_status_play_id'], $this->config->item('en_ids_7356'));
?>

<style>
    .in_child_icon_<?= $in['in_id'] ?> { display:none; }
    <?= ( !$is_author ? '.note-edit {display:none;}' : '' ) ?>
</style>


<script>
    //Include some cached players:
    var in_loaded_id = <?= $in['in_id'] ?>;
</script>
<script src="/application/views/idea/idea_coin.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
<script src="/application/views/idea/idea_shared.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<?php

$play_focus_found = false; //Used to determine the first tab to be opened



echo '<div class="container" style="padding-bottom:42px;">';


if(!$is_author){
    echo '<div class="alert alert-warning no-margin"><i class="fad fa-exclamation-triangle"></i> You are not an author of this idea, yet. <a href="/idea/in_request_invite/'.$in['in_id'].'" class="inline-block montserrat">REQUEST INVITE</a><span class="inline-block '.superpower_active(10985).'">&nbsp;or <a href="/idea/in_become_author/'.$in['in_id'].'" class="montserrat">BECOME AUTHOR</a></span></div>';
}

$col_num = 0;
foreach ($this->config->item('en_all_11021') as $en_id => $m){

    $col_num++;
    $tab_content = '';
    $default_active = false;
    $show_tab_menu_count = 0;
    $show_tab_ui = '';


    if($col_num==2){

        //IDEA BODY


        //IDEA STATUS
        echo '<div class="inline-block top-margin">'.echo_in_dropdown(4737, $in['in_status_play_id'], 'btn-idea', $is_author, true, $in['in_id']).'</div>';


        //IDEA TITLE
        echo '<div class="itemidea">';
        echo echo_in_text(4736, $in['in_title'], $in['in_id'], ($is_author && $is_active), 0, true);
        echo '<div class="title_counter hidden grey montserrat doupper" style="text-align: right;"><span id="charTitleNum">0</span>/'.config_var(11071).' CHARACTERS</div>';
        echo '</div>';


    } elseif($col_num==3){

        //IDEA FOOTER
        echo '<div class="top-margin">';

        //IDEA TIME
        echo '<div class="'.superpower_active(10985).'">'.echo_in_text(4356, $in['in_read_time'], $in['in_id'], $is_author && $is_active, 0).'</div>';

        //IDEA TYPE
        echo echo_in_dropdown(7585, $in['in_type_play_id'], 'btn-idea', $is_author && $is_active, true, $in['in_id']);

        echo '</div>';

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


        //IDEA
        if($en_id2==11019){

            //IDEA TREE PREVIOUS
            $default_active = true;
            $idea__parents = $this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Idea-to-Idea Links
                'ln_child_idea_id' => $in['in_id'],
            ), array('in_parent'), 0);

            $counter = count($idea__parents);

            $this_tab .= '<div id="list-in-' . $in['in_id'] . '-1" class="list-group">';

            foreach ($idea__parents as $parent_in) {
                $this_tab .= echo_in($parent_in, 0, true, in_is_author($parent_in['in_id']));
            }

            if( $is_author && $is_active && $in['in_id']!=config_var(12156)){
                $this_tab .= '<div class="list-group-item itemidea '.superpower_active(10984).'" style="padding:5px 0;">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean" style="margin-top: 6px;"><span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control ideaadder-level-2-parent form-control-thick algolia_search dotransparent"
                           maxlength="' . config_var(11071) . '"
                           idea-id="' . $in['in_id'] . '"
                           id="addidea-c-' . $in['in_id'] . '-1"
                           style="margin-bottom: 0; padding: 5px 0;"
                           placeholder="ADD PREVIOUS IDEA">
                </div><div class="algolia_pad_search hidden in_pad_top"></div></div>';
            }

            $this_tab .= '</div>';

        } elseif($en_id2==11020){

            //IDEA TREE NEXT
            $idea__children = $this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Idea-to-Idea Links
                'ln_parent_idea_id' => $in['in_id'],
            ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

            $counter = count($idea__children);
            $default_active = true;

            //List child ideas:
            $this_tab .= '<div id="list-in-' . $in['in_id'] . '-0" class="list-group list-is-children">';
            foreach ($idea__children as $child_in) {
                $this_tab .= echo_in($child_in, $in['in_id'], false, in_is_author($child_in['in_id']));
            }

            if($is_author && $is_active){
                $this_tab .= '<div class="list-group-item itemidea '.superpower_active(10939).'" style="padding:5px 0;">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean" style="margin-top: 6px;"><span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control ideaadder-level-2-child form-control-thick algolia_search dotransparent"
                           maxlength="' . config_var(11071) . '"
                           idea-id="' . $in['in_id'] . '"
                           id="addidea-c-' . $in['in_id'] . '-0"
                           style="margin-bottom: 0; padding: 5px 0;"
                           placeholder="ADD NEXT IDEA">
                </div><div class="algolia_pad_search hidden in_pad_bottom"></div></div>';
            }


        } elseif(in_array($en_id2, $this->config->item('en_ids_4485'))){

            //IDEA NOTE
            $idea_notes = $this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_play_id' => $en_id2,
                'ln_child_idea_id' => $in['in_id'],
            ), array(), 0, 0, array('ln_order' => 'ASC'));

            $counter = count($idea_notes);

            if($en_id2==4231){
                $default_active = true; //IDEA MESSAGES
            }



            //Show no-Message notifications for each message type:
            $this_tab .= '<div id="in_notes_list_'.$en_id2.'" class="list-group">';

            foreach ($idea_notes as $in_note) {
                $this_tab .= echo_in_note($in_note);
            }

            //ADD NEW NOTE:
            $this_tab .= '<div class="list-group-item itemidea add_note_' . $en_id2 . ( $is_author && $is_active ? '' : ' hidden ' ).'">';
            $this_tab .= '<div class="add_note_form">';
            $this_tab .= '<form class="box box' . $en_id2 . '" method="post" enctype="multipart/form-data" class="'.superpower_active(10939).'">'; //Used for dropping files



            $this_tab .= '<textarea onkeyup="in_new_note_count('.$en_id2.')" class="form-control msg note-textarea algolia_search new-note" note-type-id="' . $en_id2 . '" id="ln_content' . $en_id2 . '" placeholder="'.'ADD '.rtrim(strtoupper($en_all_4485[$en_id2]['m_name']), 'S').( in_array(12359, $en_all_4485[$en_id2]['m_parents']) ? ', PASTE URL OR DROP FILE' : '' ).'" style="margin-top:6px;"></textarea>';



            $this_tab .= '<table class="table table-condensed hidden" id="notes_control_'.$en_id2.'"><tr>';

            //Save button:
            $this_tab .= '<td style="width:85px; padding: 10px 0 0 0;"><a href="javascript:in_note_add('.$en_id2.');" class="btn btn-idea save_note_'.$en_id2.'">ADD</a></td>';

            //File counter:
            $this_tab .= '<td class="remove_loading" class="remove_loading" style="padding: 10px 0 0 0; font-size: 0.85em;"><span id="ideaNoteNewCount' . $en_id2 . '" class="hidden"><span id="charNum' . $en_id2 . '">0</span>/' . config_var(11073).'</span></td>';

            //First Name:
            $this_tab .= '<td class="remove_loading '.superpower_active(10967).'" style="width:42px; padding: 10px 0 0 0;"><a href="javascript:in_note_insert_string('.$en_id2.', \'/firstname \');" data-toggle="tooltip" title="Mention readers first name" data-placement="top"><span class="icon-block"><i class="far fa-fingerprint"></i></span></a></td>';

            //YouTube Embed
            $this_tab .= '<td class="remove_loading '.superpower_active(10984).'" style="width:42px; padding: 10px 0 0 0;"><a href="javascript:in_note_insert_string('.$en_id2.', \'https://www.youtube.com/embed/VIDEOIDHERE?start=&end=\');" data-toggle="tooltip" title="Insert YouTube Clip URL" data-placement="top"><span class="icon-block"><i class="fab fa-youtube"></i></span></a></td>';

            //Reference Player
            $this_tab .= '<td class="remove_loading '.superpower_active(10983).'" style="width:42px; padding: 10px 0 0 0;"><a href="javascript:in_note_insert_string('.$en_id2.', \'@\');" data-toggle="tooltip" title="Reference PLAYER" data-placement="top"><span class="icon-block"><i class="far fa-at"></i></span></a></td>';

            //Upload File:
            if(in_array(12359, $en_all_4485[$en_id2]['m_parents'])){
                $this_tab .= '<td class="remove_loading" style="width:36px; padding: 10px 0 0 0;">';
                $this_tab .= '<input class="inputfile hidden" type="file" name="file" id="fileIdeaType'.$en_id2.'" />';
                $this_tab .= '<label class="file_label_'.$en_id2.'" for="fileIdeaType'.$en_id2.'" data-toggle="tooltip" title="Upload files up to ' . config_var(11063) . 'MB" data-placement="top"><span class="icon-block"><i class="far fa-paperclip"></i></span></label>';
                $this_tab .= '</td>';
            }


            //TODO ADD MORE OPTIONS HERE?
            //LIST PLAYERS
            //DRIP PLAYERS

            $this_tab .= '</tr></table>';


            //Response result:
            $this_tab .= '<div class="note_error_'.$en_id2.'"></div>';


            $this_tab .= '</form>';
            $this_tab .= '</div>';
            $this_tab .= '</div>';

            $this_tab .= '</div>';

        } elseif(in_array($en_id2, $this->config->item('en_ids_12410'))){

            //READER READS & BOOKMARKS
            $item_counters = $this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_'.$en_id2)) . ')' => null,
                'ln_parent_idea_id' => $in['in_id'],
            ), array(), 1, 0, array(), 'COUNT(ln_id) as totals');

            $counter = $item_counters[0]['totals'];

            if($counter > 0){

                //Dynamic Loading when clicked:
                $this_tab .= '<div class="dynamic-reads"></div>';

            } else {

                //Inform that nothing was found:
                $en_all_12410 = $this->config->item('en_all_12410');
                $this_tab .= '<div class="alert alert-warning">No <span class="montserrat '.extract_icon_color($en_all_12410[$en_id2]['m_icon']).'">'.$en_all_12410[$en_id2]['m_icon'].' '.$en_all_12410[$en_id2]['m_name'].'</span> added yet.</div>';

            }

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
        $show_tab_ui .= '<li class="nav-item '.( !$default_active && count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a class="nav-link tab-nav-'.$en_id.' tab-head-'.$en_id2.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m2['m_icon']).'" href="javascript:void(0);" onclick="loadtab('.$en_id.','.$en_id2.', '.$in['in_id'].', 0)" data-toggle="tooltip" data-placement="top" title="'.$m2['m_name'].'">'.$m2['m_icon'].( is_null($counter) ? '' : ' <span class="counter-'.$en_id2.'">'.echo_number($counter).'</span>' ).'</a></li>';


        $tab_content .= '<div class="tab-content tab-group-'.$en_id.' tab-data-'.$en_id2.( $default_active ? '' : ' hidden ' ).'">';
        $tab_content .= $this_tab;
        $tab_content .= '</div>';

        $default_active = false;

    }


    if($show_tab_menu_count >= 2){
        echo '<ul class="nav nav-tabs nav-tabs-sm">';
        echo $show_tab_ui;
        echo '</ul>';
    }

    echo $tab_content;

}

echo '</div>';

?>