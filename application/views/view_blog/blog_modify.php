
<style>
    .in_child_icon_<?= $in['in_id'] ?> { display:none; }
</style>


<script src="/js/lib/rangy/rangy-core.js" type="text/javascript"></script>
<script src="/js/lib/rangy/rangy-classapplier.js" type="text/javascript"></script>
<script src="/js/lib/undo.js" type="text/javascript"></script>
<script src="/js/lib/medium.js" type="text/javascript"></script>
<script>
    //Include some cached entities:
    var show_counter_threshold = 0.80;
    var in_loaded_id = <?= $in['in_id'] ?>;
    var js_en_all_4486 = <?= json_encode($this->config->item('en_all_4486')) ?>; // Intent Links
    var js_en_all_7585 = <?= json_encode($this->config->item('en_all_7585')) ?>; // Intent Subtypes


    $(document).ready(function () {

        new Medium({
            element: document.getElementById('MediumEditor'),
            maxLength:<?= config_var(11071) ?>,
            mode: Medium.inlineMode,
            autoHR: false,
            autofocus: true,
            placeholder: "Blog Title",
            cssClasses: {
                editor: 'Medium',
                pasteHook: 'Medium-paste-hook',
                placeholder: 'Medium-placeholder',
                clear: 'Medium-clear'
            }
        });

    });

</script>
<script src="/js/custom/in_notes.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
<script src="/js/custom/in_modify.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
<script src="/js/custom/in_train.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>


<?php

$en_all_4485 = $this->config->item('en_all_4485'); //Intent Notes
$play_focus_found = false; //Used to determine the first tab to be opened



echo '<div class="container" style="padding-bottom:54px;">';
echo '<div class="row">';
$col_num = 0;
foreach ($this->config->item('en_all_11021') as $en_id => $m){

    $col_num++;
    $tab_content = '';
    $default_active = false;
    
    echo '<div class="col-lg-12">';

    if($col_num==1){

        echo '<div>';
            echo '<div class="inline-block">'.echo_dropdown(4737, $in['in_status_entity_id'], false, 'btn-blog').'</div>';
            echo '<div class="inline-block" style="margin-left: 5px;"><a href="javascript:void(0)" onclick="alert(\'Under Dev.\')" class="btn btn-sm btn-blog"><i class="far fa-bookmark"></i></a></div>';
            echo '<div class="inline-block" style="margin-left: 5px;"><a href="javascript:void(0)" onclick="$(\'.menu_bar\').toggleClass(\'hidden\')" class="btn btn-sm btn-blog"><i class="fas fa-cog"></i></a></div>';
        echo '</div>';

        echo '<h1 id="MediumEditor">'.echo_in_outcome($in['in_outcome']).'</h1>';

    } else {

        echo '<div class="center-right">';
            echo '<div class="inline-block">'.echo_dropdown(7585, $in['in_completion_method_entity_id'], false, 'btn-blog').'</div>';
        echo '</div>';

    }

    echo '<ul class="nav nav-tabs nav-tabs-sm menu_bar hidden">';

    foreach ($this->config->item('en_all_'.$en_id) as $en_id2 => $m2){


        //Is this a caret menu?
        if(in_array(11040 , $m2['m_parents'])){
            echo echo_caret($en_id2, $m2, $in['in_id']);
            continue;
        }


        //Determine counter:
        $show_tab_names = in_array($en_id2, $this->config->item('en_ids_11031'));
        $counter = null; //Assume no counters
        $this_tab = '';


        //BLOG
        if($en_id2==11019){

            //BLOG TREE PREVIOUS
            $fetch_11019 = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent-to-Intent Links
                'ln_child_intent_id' => $in['in_id'],
            ), array('in_parent'), 0, 0, array('ln_up_order' => 'ASC'));

            $counter = count($fetch_11019);

            $this_tab .= '<div id="list-in-' . $in['in_id'] . '-1" class="list-group list-level-2">';

            foreach ($fetch_11019 as $parent_in) {
                $this_tab .= echo_in($parent_in, true);
            }

            $this_tab .= '<div class="list_input grey-block '.superpower_active(10939).'">
                            <div class="form-group is-empty" style="margin: 0; padding: 0;">
                                <input type="text"
                                       class="form-control intentadder-level-2-parent form-control-thick algolia_search"
                                       intent-id="' . $in['in_id'] . '"
                                       id="addintent-c-' . $in['in_id'] . '-1"
                                       placeholder="+ BLOG">
                            </div>
                           <div class="algolia_search_pad in_pad_top hidden"><b class="montserrat"><span class="icon-block"><i class="fas fa-search-plus yellow"></i></span>Search blogs or create a new one...</b></div>
                    </div>';

            $this_tab .= '</div>';


        } elseif($en_id2==11020){

            //BLOG TREE NEXT
            $fetch_11020 = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent-to-Intent Links
                'ln_parent_intent_id' => $in['in_id'],
            ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

            $counter = count($fetch_11020);
            $default_active = true;

            //List child intents:
            $this_tab .= '<div id="list-in-' . $in['in_id'] . '-0" class="list-group list-is-children list-level-2">';
            foreach ($fetch_11020 as $child_in) {
                $this_tab .= echo_in($child_in, $in['in_id']);
            }

            //Add child intent:
            $this_tab .= '<div class="'.superpower_active(10939).'">';
            if(in_can_train($in['in_id'])){
                $this_tab .= '<div class="list_input grey-block">
                    <div class="form-group is-empty" style="margin: 0; padding: 0;">
                        <input type="text"
                               class="form-control intentadder-level-2-child form-control-thick algolia_search"
                               maxlength="' . config_var(11071) . '"
                               intent-id="' . $in['in_id'] . '"
                               id="addintent-c-' . $in['in_id'] . '-0"
                               placeholder="+ BLOG">
                    </div>
                   <div class="algolia_search_pad in_pad_bottom hidden"><b class="montserrat"><span class="icon-block"><i class="fas fa-search-plus yellow"></i></span>Search blogs or create a new one...</b></div>
            </div>';
            } else {
                //Give option to request to join as Author:

            }
            $this_tab .= '</div>';
            $this_tab .= '</div>';


        } elseif(in_array($en_id2, array(7347,6146))){

            //READER READS & BOOKMARKS
            $item_counters = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_'.$en_id2)) . ')' => null,
                'ln_parent_intent_id' => $in['in_id'],
            ), array(), 1, 0, array(), 'COUNT(ln_id) as totals');

            $counter = $item_counters[0]['totals'];

            $this_tab .= '<div>Under development</div>';

        } elseif(in_array($en_id2, $this->config->item('en_ids_4485'))){

            //BLOG NOTE
            $blog_notes = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_entity_id' => $en_id2,
                'ln_child_intent_id' => $in['in_id'],
            ), array(), 0, 0, array('ln_order' => 'ASC'));

            $counter = count($blog_notes);

            if($en_id2==4231){
                $default_active = true; //BLOG MESSAGES
            }



            //Show no-Message notifications for each message type:
            $this_tab .= '<div id="in_notes_list_'.$en_id2.'" class="list-group">';

            foreach ($blog_notes as $in_note) {
                $this_tab .= echo_in_note($in_note);
            }

            //ADD NEW NOTE:
            $this_tab .= '<div class="list-group-item add_note_' . $en_id2 . '">';
            $this_tab .= '<form class="box box' . $en_id2 . '" method="post" enctype="multipart/form-data">'; //Used for dropping files



            $this_tab .= '<textarea onkeyup="in_new_note_count('.$en_id2.')" class="form-control msg note-textarea algolia_search new-note" note-type-id="' . $en_id2 . '" id="ln_content' . $en_id2 . '" placeholder=" + MESSAGE"></textarea>';



            $this_tab .= '<table class="table table-condensed hidden" id="notes_control_'.$en_id2.'"><tr>';

            //Save button:
            $this_tab .= '<td style="width:85px; padding: 10px 0 0 0;"><a href="javascript:in_note_add('.$en_id2.');" data-toggle="tooltip" title="or hit CTRL+ENTER ;)" data-placement="right" class="btn btn-blog save_note_'.$en_id2.'">SAVE</a></td>';

            //File counter:
            $this_tab .= '<td class="remove_loading" class="remove_loading" style="padding: 10px 0 0 0; font-size: 0.85em;"><span id="blogNoteNewCount' . $en_id2 . '" class="hidden"><span id="charNum' . $en_id2 . '">0</span>/' . config_var(11073).'</span></td>';

            //First Name:
            $this_tab .= '<td class="remove_loading" style="width:42px; padding: 10px 0 0 0;"><a href="javascript:in_note_insert_string('.$en_id2.', \'/firstname \');" data-toggle="tooltip" title="Mention readers first name" data-placement="top"><span class="icon-block en-icon"><i class="far fa-fingerprint"></i></span></a></td>';

            //Reference Player
            $this_tab .= '<td class="remove_loading" style="width:42px; padding: 10px 0 0 0;"><a href="javascript:in_note_insert_string('.$en_id2.', \'@\');" data-toggle="tooltip" title="Reference players or content" data-placement="top"><span class="icon-block en-icon"><i class="far fa-at"></i></span></a></td>';

            //Upload File:
            $this_tab .= '<td class="remove_loading" style="width:42px; padding: 10px 0 0 0;">';
            $this_tab .= '<input class="inputfile hidden" type="file" name="file" id="file" />';
            $this_tab .= '<label class="file_label_'.$en_id2.'" for="file" data-toggle="tooltip" title="Upload files up to ' . config_var(11063) . 'MB" data-placement="top"><span class="icon-block en-icon"><i class="far fa-paperclip"></i></span></label>';
            $this_tab .= '</td>';

            //TODO ADD MORE OPTIONS HERE?
            //LIST PLAYERS
            //DRIP PLAYERS

            $this_tab .= '</tr></table>';


            //Response result:
            $this_tab .= '<div class="note_error_'.$en_id2.'"></div>';


            $this_tab .= '</form>';
            $this_tab .= '</div>';

            $this_tab .= '</div>';
        }



        $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m2['m_parents']);

        echo '<li class="nav-item '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a class="nav-link tab-nav-'.$en_id.' tab-head-'.$en_id2.' '.( $default_active ? ' active ' : '' ).'" href="javascript:void(0);" onclick="loadtab('.$en_id.','.$en_id2.')" data-toggle="tooltip" data-placement="top" title="'.( $show_tab_names ? '' : $m2['m_name'] ).'">'.$m2['m_icon'].( is_null($counter) ? '' : ' <span class="counter-'.$en_id2.'">'.echo_number($counter).'</span>' ).( $show_tab_names ? ' '.$m2['m_name'] : '' ).'</a></li>';


        $tab_content .= '<div class="tab-content tab-group-'.$en_id.' tab-data-'.$en_id2.( $default_active ? '' : ' hidden ' ).'">';
        $tab_content .= $this_tab;
        $tab_content .= '</div>';

        $default_active = false;

    }

    echo '</ul>';

    echo $tab_content;

    echo '</div>';

}

echo '</div>';
echo '</div>';

?>