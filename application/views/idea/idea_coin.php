<?php
$en_all_2738 = $this->config->item('en_all_2738');
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION

$is_source = in_is_source($in['in_id']);
$is_active = in_array($in['in_status_source_id'], $this->config->item('en_ids_7356'));
$is_public = in_array($in['in_status_source_id'], $this->config->item('en_ids_7355'));

?>

<style>
    .in_child_icon_<?= $in['in_id'] ?> { display:none; }
    <?= ( !$is_source ? '.note-editor {display:none;}' : '' ) ?>
</style>

<script>
    //Include some cached sources:
    var in_loaded_id = <?= $in['in_id'] ?>;
</script>
<script src="/application/views/idea/idea_coin.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<?php

$source_focus_found = false; //Used to determine the first tab to be opened

echo '<div class="container" style="padding-bottom:42px;">';

if(!$is_source){
    echo '<div class="alert alert-info no-margin"><span class="icon-block"><i class="fas fa-exclamation-circle source"></i></span>You are not a source for this idea, yet. <a href="/idea/in_request_invite/'.$in['in_id'].'" class="inline-block montserrat">REQUEST INVITE</a><span class="inline-block '.superpower_active(10984).'">&nbsp;or <a href="/idea/in_become_source/'.$in['in_id'].'" class="montserrat">ADD MYSELF AS SOURCE</a></span></div>';
}



//IDEA PREVIOUS
$in__previous = $this->READ_model->fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
    'ln_next_idea_id' => $in['in_id'],
), array('in_previous'), 0);

echo '<div id="list-in-' . $in['in_id'] . '-1" class="list-group previous_ins">';
foreach($in__previous as $parent_in) {
    echo echo_in($parent_in, 0, true, in_is_source($parent_in['in_id']));
}
if( $is_source && $is_active && $in['in_id']!=config_var(12156)){
    echo '<div class="list-group-item list-adder itemidea '.superpower_active(10984).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control IdeaAddPrevious form-control-thick montserrat add-input algolia_search dotransparent"
                           maxlength="' . config_var(4736) . '"
                           idea-id="' . $in['in_id'] . '"
                           id="addidea-c-' . $in['in_id'] . '-1"
                           placeholder="PREVIOUS IDEA">
                </div><div class="algolia_pad_search hidden in_pad_top"></div></div>';
}
echo '</div>';





//IDEA TITLE
echo '<div class="itemidea">';
echo echo_input_text(4736, $in['in_title'], $in['in_id'], ($is_source && $is_active), 0, true);
echo '</div>';


//IDEA MESSAGES:
echo echo_in_note_mix(4231, $this->READ_model->fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
    'ln_type_source_id' => 4231,
    'ln_next_idea_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC')), ($is_source && $is_active));


//IDEA TYPE
echo '<div class="inline-block pull-left both-margin left-margin">'.echo_input_dropdown(7585, $in['in_type_source_id'], 'btn-idea', $is_source && $is_active, true, $in['in_id']).'</div>';

//IDEA STATUS
echo '<div class="inline-block pull-left both-margin left-half-margin">'.echo_input_dropdown(4737, $in['in_status_source_id'], 'btn-idea', $is_source, true, $in['in_id']).'</div>';

//IDEA TIME
echo '<div class="inline-block pull-left both-margin left-half-margin '.superpower_active(10986).'">'.echo_input_text(4356, $in['in_time_seconds'], $in['in_id'], $is_source && $is_active, 0).'</div>';

//IDEA READ (IF PUBLIC)
echo '<div class="inline-block pull-right both-margin left-half-margin idea-read '.( $is_public ? '' : ' hidden ' ).'" style="margin-top:17px; margin-bottom:-12px;"><a class="btn btn-read btn-circle" href="/'.$in['in_id'].'" data-toggle="tooltip" data-placement="top" title="'.$en_all_11035[12750]['m_name'].'">'.$en_all_11035[12750]['m_icon'].'</a></div>';

echo '<div class="doclear">&nbsp;</div>';




//IDEA LAYOUT
$tab_group = 1;
$tab_content = '';
echo '<ul class="nav nav-tabs nav-sm">';
foreach($this->config->item('en_all_11018') as $ln_type_source_id => $m){


    //Is this a caret menu?
    if(in_array(11040 , $m['m_parents'])){
        echo echo_caret($ln_type_source_id, $m, $in['in_id']);
        continue;
    }

    //Have Needed Superpowers?
    $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m['m_parents']);
    if(count($superpower_actives) && !superpower_assigned(end($superpower_actives))){
        continue;
    }



    $counter = null; //Assume no counters
    $this_tab = '';


    if($ln_type_source_id==11020){


        //IDEA NEXT
        $in__next = $this->READ_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
            'ln_previous_idea_id' => $in['in_id'],
        ), array('in_next'), 0, 0, array('ln_order' => 'ASC'));


        //CHILD IDEAS
        $counter = count($in__next);


        $this_tab .= '<div id="list-in-' . $in['in_id'] . '-0" class="list-group next_ins">';
        foreach($in__next as $child_in) {
            $this_tab .= echo_in($child_in, $in['in_id'], false, $is_source);
        }

        if($is_source && $is_active){
            $this_tab .= '<div class="list-group-item list-adder itemidea '.superpower_active(10939).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control ideaadder-level-2-child form-control-thick add-input montserrat algolia_search dotransparent"
                           maxlength="' . config_var(4736) . '"
                           idea-id="' . $in['in_id'] . '"
                           id="addidea-c-' . $in['in_id'] . '-0"
                           placeholder="NEXT IDEA">
                </div><div class="algolia_pad_search hidden in_pad_bottom"></div></div>';
        }

        $this_tab .= '</div>';

    } elseif(in_array($ln_type_source_id, $this->config->item('en_ids_7551'))){

        //Reference Sources Only:
        $in_notes = $this->READ_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'ln_type_source_id' => $ln_type_source_id,
            'ln_next_idea_id' => $in['in_id'],
        ), array('en_profile'), 0, 0, array('ln_order' => 'ASC'));

        $counter = count($in_notes);

        $this_tab .= '<div id="add-source-' .$ln_type_source_id . '" class="list-group source-adder">';

        foreach($in_notes as $in_note) {
            $this_tab .= echo_en($in_note, 0, null, $is_source && $is_active, $is_source);
        }

        if($is_source && $is_active) {
            $this_tab .= '<div class="list-group-item list-adder itemsource no-side-padding source-mapper source-map-' . $ln_type_source_id . '" note_type_id="' . $ln_type_source_id . '">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">' . $en_all_2738[4536]['m_icon'] . '</span></span>
                    <input type="text"
                           class="form-control source form-control-thick montserrat doupper algolia_search dotransparent add-input"
                           maxlength="' . config_var(6197) . '"                          
                           placeholder="NEW SOURCE">
                </div><div class="algolia_pad_search hidden pad_expand source-pad-' . $ln_type_source_id . '"></div></div>';
        }

        $this_tab .= '</div>';

    } elseif(in_array($ln_type_source_id, $this->config->item('en_ids_12467'))){

        //MENCH COINS
        $counter = ln_coins_in($ln_type_source_id, $in['in_id']);
        $this_tab = ln_coins_in($ln_type_source_id, $in['in_id'], 1);

    } elseif(in_array($ln_type_source_id, $this->config->item('en_ids_4485'))){

        //IDEA NOTES
        $in_notes = $this->READ_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'ln_type_source_id' => $ln_type_source_id,
            'ln_next_idea_id' => $in['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));

        $counter = count($in_notes);
        $this_tab .= echo_in_note_mix($ln_type_source_id, $in_notes, ($is_source && $is_active));

    } elseif($ln_type_source_id==12589){

        //NEXT EDITOR
        $dropdown_options = '';
        $input_options = '';
        $counter = 0;

        foreach($this->config->item('en_all_12589') as $action_en_id => $mass_action_en) {

            $counter++;
            $dropdown_options .= '<option value="' . $action_en_id . '">' .$mass_action_en['m_name'] . '</option>';
            $is_upper = false;


            //Start with the input wrapper:
            $input_options .= '<span id="mass_id_'.$action_en_id.'" title="'.$mass_action_en['m_desc'].'" class="inline-block '. ( $counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';

            if(in_array($action_en_id, array(12591, 12592))){

                //Source search box:

                //String command:
                $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'"  placeholder="Search Sources..." class="form-control algolia_search en_text_search border montserrat '.$is_upper.'">';

                //We don't need the second value field here:
                $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';

            } elseif(in_array($action_en_id, array(12611, 12612))){

                $input_options .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Ideas will be deleted.</div>';

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

        $this_tab .= '<div><input type="submit" value="APPLY" class="btn btn-idea inline-block"></div>';

        $this_tab .= '</div>';
        $this_tab .= '</form>';

    } else {

        //Not supported via here:
        continue;

    }



    if(!$counter && in_array($ln_type_source_id, $this->config->item('en_ids_12677'))){
        //Hide since Zero:
        continue;
    }


    $default_active = in_array($ln_type_source_id, $this->config->item('en_ids_12675'));

    echo '<li class="nav-item '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a class="nav-link tab-nav-'.$tab_group.' tab-head-'.$ln_type_source_id.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m_icon']).'" href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$ln_type_source_id.', '.$in['in_id'].', 0)" data-toggle="tooltip" data-placement="top" title="'.$m['m_name'].( strlen($m['m_desc']) ? ': '.$m['m_desc'] : '' ).'">'.$m['m_icon'].( is_null($counter) ? '' : ' <span class="en-type-counter-'.$ln_type_source_id.'">'.echo_number($counter).'</span>' ).'</a></li>';


    $tab_content .= '<div class="tab-content tab-group-'.$tab_group.' tab-data-'.$ln_type_source_id.( $default_active ? '' : ' hidden ' ).'">';
    $tab_content .= $this_tab;
    $tab_content .= '</div>';

}
echo '</ul>';


//Show All Tab Content:
echo $tab_content;

echo '</div>';

