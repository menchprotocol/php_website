<?php
$sources__2738 = $this->config->item('sources__2738');
$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION

$is_source = idea_is_source($idea_focus['idea__id']);
$is_active = in_array($idea_focus['idea__status'], $this->config->item('sources_id_7356'));
$is_public = in_array($idea_focus['idea__status'], $this->config->item('sources_id_7355'));

?>

<style>
    .idea_child_icon_<?= $idea_focus['idea__id'] ?> { display:none; }
    <?= ( !$is_source ? '.note-editor {display:none;}' : '' ) ?>
</style>

<script>
    //Include some cached sources:
    var idea_loaded_id = <?= $idea_focus['idea__id'] ?>;
</script>
<script src="/application/views/idea/idea_coin.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<?php

$source_focus_found = false; //Used to determine the first tab to be opened

echo '<div class="container" style="padding-bottom:42px;">';

if(!$is_source){
    echo '<div class="alert alert-info no-margin"><span class="icon-block"><i class="fas fa-exclamation-circle source"></i></span>You are not a source for this idea, yet. <a href="/idea/idea_request_invite/'.$idea_focus['idea__id'].'" class="inline-block montserrat">REQUEST INVITE</a><span class="inline-block '.superpower_active(10984).'">&nbsp;or <a href="/idea/idea_become_source/'.$idea_focus['idea__id'].'" class="montserrat">ADD MYSELF AS SOURCE</a></span></div>';
}



//IDEA PREVIOUS
$ideas_previous = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
    'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
    'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
    'read__right' => $idea_focus['idea__id'],
), array('read__left'), 0);

echo '<div id="list-in-' . $idea_focus['idea__id'] . '-1" class="list-group previous_ideas">';
foreach($ideas_previous as $previous_idea) {
    echo view_idea($previous_idea, $idea_focus['idea__id'], true, idea_is_source($previous_idea['idea__id']));
}
if( $is_source && $is_active && $idea_focus['idea__id']!=$this->config->item('featured_idea__id')){
    echo '<div class="list-group-item list-adder itemidea '.superpower_active(10984).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$sources__2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control IdeaAddPrevious form-control-thick montserrat add-input algolia_search dotransparent"
                           maxlength="' . config_var(4736) . '"
                           idea-id="' . $idea_focus['idea__id'] . '"
                           id="addidea-c-' . $idea_focus['idea__id'] . '-1"
                           placeholder="PREVIOUS IDEA">
                </div><div class="algolia_pad_search hidden idea_pad_top"></div></div>';
}
echo '</div>';





//IDEA TITLE
echo '<div class="itemidea">';
echo view_input_text(4736, $idea_focus['idea__title'], $idea_focus['idea__id'], ($is_source && $is_active), 0, true);
echo '</div>';


//IDEA MESSAGES:
echo view_idea_note_mix(4231, $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
    'read__type' => 4231,
    'read__right' => $idea_focus['idea__id'],
), array(), 0, 0, array('read__sort' => 'ASC')), ($is_source && $is_active));


//IDEA TYPE
echo '<div class="inline-block pull-left both-margin left-margin">'.view_input_dropdown(7585, $idea_focus['idea__type'], 'btn-idea', $is_source && $is_active, true, $idea_focus['idea__id']).'</div>';

//IDEA STATUS
echo '<div class="inline-block pull-left both-margin left-half-margin">'.view_input_dropdown(4737, $idea_focus['idea__status'], 'btn-idea', $is_source, true, $idea_focus['idea__id']).'</div>';

//IDEA TIME
echo '<div class="inline-block pull-left both-margin left-half-margin '.superpower_active(10986).'">'.view_input_text(4356, $idea_focus['idea__duration'], $idea_focus['idea__id'], $is_source && $is_active, 0).'</div>';

//IDEA READ (IF PUBLIC)
echo '<div class="inline-block pull-right both-margin left-half-margin idea-read '.( $is_public ? '' : ' hidden ' ).'" style="margin-top:17px; margin-bottom:-12px;"><a class="btn btn-read btn-circle" href="/'.$idea_focus['idea__id'].'" data-toggle="tooltip" data-placement="top" title="'.$sources__11035[12750]['m_name'].'">'.$sources__11035[12750]['m_icon'].'</a></div>';

echo '<div class="doclear">&nbsp;</div>';




//IDEA LAYOUT
$tab_group = 1;
$tab_content = '';
echo '<ul class="nav nav-tabs nav-sm">';
foreach($this->config->item('sources__11018') as $read__type => $m){


    //Is this a caret menu?
    if(in_array(11040 , $m['m_parents'])){
        echo view_caret($read__type, $m, $idea_focus['idea__id']);
        continue;
    }

    //Have Needed Superpowers?
    $superpower_actives = array_intersect($this->config->item('sources_id_10957'), $m['m_parents']);
    if(count($superpower_actives) && !superpower_assigned(end($superpower_actives))){
        continue;
    }



    $counter = null; //Assume no counters
    $this_tab = '';


    if($read__type==11020){


        //IDEA NEXT
        $ideas_next = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
            'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            'read__left' => $idea_focus['idea__id'],
        ), array('read__right'), 0, 0, array('read__sort' => 'ASC'));


        //CHILD IDEAS
        $counter = count($ideas_next);


        $this_tab .= '<div id="list-in-' . $idea_focus['idea__id'] . '-0" class="list-group next_ideas">';
        foreach($ideas_next as $next_idea) {
            $this_tab .= view_idea($next_idea, $idea_focus['idea__id'], false, $is_source);
        }

        if($is_source && $is_active){
            $this_tab .= '<div class="list-group-item list-adder itemidea '.superpower_active(10939).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$sources__2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control ideaadder-level-2-child form-control-thick add-input montserrat algolia_search dotransparent"
                           maxlength="' . config_var(4736) . '"
                           idea-id="' . $idea_focus['idea__id'] . '"
                           id="addidea-c-' . $idea_focus['idea__id'] . '-0"
                           placeholder="NEXT IDEA">
                </div><div class="algolia_pad_search hidden idea_pad_bottom"></div></div>';
        }

        $this_tab .= '</div>';

    } elseif(in_array($read__type, $this->config->item('sources_id_7551'))){

        //Reference Sources Only:
        $idea_notes = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'read__type' => $read__type,
            'read__right' => $idea_focus['idea__id'],
        ), array('read__up'), 0, 0, array('read__sort' => 'ASC'));

        $counter = count($idea_notes);

        $this_tab .= '<div id="add-source-' .$read__type . '" class="list-group source-adder">';

        foreach($idea_notes as $idea_note) {
            $this_tab .= view_source($idea_note, 0, null, $is_source && $is_active, $is_source);
        }

        if($is_source && $is_active) {
            $this_tab .= '<div class="list-group-item list-adder itemsource no-side-padding source-mapper source-map-' . $read__type . '" note_type_id="' . $read__type . '">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">' . $sources__2738[4536]['m_icon'] . '</span></span>
                    <input type="text"
                           class="form-control source form-control-thick montserrat doupper algolia_search dotransparent add-input"
                           maxlength="' . config_var(6197) . '"                          
                           placeholder="NEW SOURCE">
                </div><div class="algolia_pad_search hidden pad_expand source-pad-' . $read__type . '"></div></div>';
        }

        $this_tab .= '</div>';

    } elseif(in_array($read__type, $this->config->item('sources_id_12467'))){

        //MENCH COINS
        $counter = read_coins_idea($read__type, $idea_focus['idea__id']);
        $this_tab = read_coins_idea($read__type, $idea_focus['idea__id'], 1);

    } elseif(in_array($read__type, $this->config->item('sources_id_4485'))){

        //IDEA NOTES
        $idea_notes = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'read__type' => $read__type,
            'read__right' => $idea_focus['idea__id'],
        ), array(), 0, 0, array('read__sort' => 'ASC'));

        $counter = count($idea_notes);
        $this_tab .= view_idea_note_mix($read__type, $idea_notes, ($is_source && $is_active));

    } elseif($read__type==12589){

        //NEXT EDITOR
        $dropdown_options = '';
        $input_options = '';
        $counter = 0;

        foreach($this->config->item('sources__12589') as $action_source__id => $source_list_action) {

            $counter++;
            $dropdown_options .= '<option value="' . $action_source__id . '">' .$source_list_action['m_name'] . '</option>';


            //Start with the input wrapper:
            $input_options .= '<span id="mass_id_'.$action_source__id.'" title="'.$source_list_action['m_desc'].'" class="inline-block '. ( $counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';

            if(in_array($action_source__id, array(12591, 12592))){

                //Source search box:

                //String command:
                $input_options .= '<input type="text" name="mass_value1_'.$action_source__id.'"  placeholder="Search Sources..." class="form-control algolia_search source_text_search border montserrat">';

                //We don't need the second value field here:
                $input_options .= '<input type="hidden" name="mass_value2_'.$action_source__id.'" value="" />';

            } elseif(in_array($action_source__id, array(12611, 12612))){

                $input_options .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Ideas will be deleted.</div>';

                //String command:
                $input_options .= '<input type="text" name="mass_value1_'.$action_source__id.'"  placeholder="Search Ideas..." class="form-control algolia_search idea_text_search border montserrat">';

                //We don't need the second value field here:
                $input_options .= '<input type="hidden" name="mass_value2_'.$action_source__id.'" value="" />';

            }

            $input_options .= '</span>';

        }

        $this_tab .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important; margin-left: 33px;">';
        $this_tab .= '<div class="inline-box">';

        //Drop Down
        $this_tab .= '<select class="form-control border" name="mass_action_source__id" id="set_mass_action">';
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



    if(!$counter && in_array($read__type, $this->config->item('sources_id_12677'))){
        //Hide since Zero:
        continue;
    }


    $default_active = in_array($read__type, $this->config->item('sources_id_12675'));

    echo '<li class="nav-item '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a class="nav-link tab-nav-'.$tab_group.' tab-head-'.$read__type.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m_icon']).'" href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$read__type.', '.$idea_focus['idea__id'].', 0)" data-toggle="tooltip" data-placement="top" title="'.$m['m_name'].( strlen($m['m_desc']) ? ': '.$m['m_desc'] : '' ).'">'.$m['m_icon'].( is_null($counter) ? '' : ' <span class="en-type-counter-'.$read__type.'">'.view_number($counter).'</span>' ).'</a></li>';


    $tab_content .= '<div class="tab-content tab-group-'.$tab_group.' tab-data-'.$read__type.( $default_active ? '' : ' hidden ' ).'">';
    $tab_content .= $this_tab;
    $tab_content .= '</div>';

}
echo '</ul>';


//Show All Tab Content:
echo $tab_content;

echo '</div>';

