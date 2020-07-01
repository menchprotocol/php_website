<?php
$e___2738 = $this->config->item('e___2738');
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION

$player_is_i_source = player_is_i_source($i_focus['i__id']);
$is_active = in_array($i_focus['i__status'], $this->config->item('e___n_7356'));
$is_public = in_array($i_focus['i__status'], $this->config->item('e___n_7355'));

?>

<style>
    .i_child_icon_<?= $i_focus['i__id'] ?> { display:none; }
    <?= ( !$player_is_i_source ? '.note-editor {display:none;}' : '' ) ?>
</style>

<script>
    //Include some cached sources:
    var i_loaded_id = <?= $i_focus['i__id'] ?>;
</script>
<script src="/application/views/i/layout.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<?php

$e_focus_found = false; //Used to determine the first tab to be opened

echo '<div class="container" style="padding-bottom:42px;">';

if(!$player_is_i_source){
    echo '<div class="alert alert-info no-margin"><span class="icon-block"><i class="fas fa-exclamation-circle source"></i></span>You are not a source for this idea, yet. <a href="/i/i_e_request/'.$i_focus['i__id'].'" class="inline-block montserrat">REQUEST INVITE</a><span class="inline-block '.superpower_active(10984).'">&nbsp;or <a href="/i/i_e_add/'.$i_focus['i__id'].'" class="montserrat">ADD MYSELF AS SOURCE</a></span></div>';
}

if(isset($_GET['focus__source'])){
    //Filtered Specific Source:
    $e_filters = $this->E_model->fetch(array(
        'e__id' => intval($_GET['focus__source']),
        'e__status IN (' . join(',', $this->config->item('e___n_7358')) . ')' => null, //ACTIVE
    ));
    if(count($e_filters)){
        echo '<div class="alert alert-danger no-margin"><span class="icon-block"><i class="fas fa-filter discover"></i></span>Showing Discoveries for ' . view_e__icon($e_filters[0]['e__icon']) . '&nbsp;<a href="/@'.$e_filters[0]['e__id'].'" class="'.extract_icon_color($e_filters[0]['e__icon']).'">' . $e_filters[0]['e__title'].'</a> Only (<a href="/'.$this->uri->segment(1).'">Remove Filter</a>)</div>';
    }
}



//IDEA PREVIOUS
$ideas_previous = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('e___n_7360')) . ')' => null, //ACTIVE
    'i__status IN (' . join(',', $this->config->item('e___n_7356')) . ')' => null, //ACTIVE
    'x__type IN (' . join(',', $this->config->item('e___n_4486')) . ')' => null, //IDEA LINKS
    'x__right' => $i_focus['i__id'],
), array('x__left'), 0);

echo '<div id="list-in-' . $i_focus['i__id'] . '-1" class="list-group previous_ideas">';
foreach($ideas_previous as $previous_idea) {
    echo view_i($previous_idea, $i_focus['i__id'], true, player_is_i_source($previous_idea['i__id']));
}
if( $player_is_i_source && $is_active && $i_focus['i__id']!=config_var(13405)){
    echo '<div class="list-group-item list-adder itemidea '.superpower_active(10984).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$e___2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control IdeaAddPrevious form-control-thick montserrat add-input algolia_search dotransparent"
                           maxlength="' . config_var(4736) . '"
                           idea-id="' . $i_focus['i__id'] . '"
                           id="addidea-c-' . $i_focus['i__id'] . '-1"
                           placeholder="PREVIOUS IDEA">
                </div><div class="algolia_pad_search hidden i_pad_top"></div></div>';
}
echo '</div>';





//IDEA TITLE
echo '<div class="itemidea">';
echo view_input_text(4736, $i_focus['i__title'], $i_focus['i__id'], ($player_is_i_source && $is_active), 0, true);
echo '</div>';


//IDEA MESSAGES:
echo view_i_note_mix(4231, $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('e___n_7360')) . ')' => null, //ACTIVE
    'x__type' => 4231,
    'x__right' => $i_focus['i__id'],
), array('x__player'), 0, 0, array('x__sort' => 'ASC')));


//IDEA TYPE
echo '<div class="inline-block pull-left both-margin left-margin">'.view_input_dropdown(7585, $i_focus['i__type'], 'btn-idea', $player_is_i_source && $is_active, true, $i_focus['i__id']).'</div>';

//IDEA STATUS
echo '<div class="inline-block pull-left both-margin left-half-margin">'.view_input_dropdown(4737, $i_focus['i__status'], 'btn-idea', $player_is_i_source, true, $i_focus['i__id']).'</div>';

//IDEA TIME
echo '<div class="inline-block pull-left both-margin left-half-margin '.superpower_active(10986).'">'.view_input_text(4356, $i_focus['i__duration'], $i_focus['i__id'], $player_is_i_source && $is_active, 0).'</div>';

//IDEA DISCOVER (IF PUBLIC)
echo '<div class="inline-block pull-right both-margin left-half-margin idea-discover '.( $is_public ? '' : ' hidden ' ).'" style="margin-top:17px; margin-bottom:-12px;"><a class="btn btn-discover btn-circle" href="/'.$i_focus['i__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___11035[12750]['m_name'].'">'.$e___11035[12750]['m_icon'].'</a></div>';

echo '<div class="doclear">&nbsp;</div>';




//IDEA LAYOUT
$tab_group = 11018;
$tab_content = '';
echo '<ul class="nav nav-pills nav-sm">';
foreach($this->config->item('e___'.$tab_group) as $x__type => $m){


    //Is this a caret menu?
    if(in_array(11040 , $m['m_parents'])){
        echo view_caret($x__type, $m, $i_focus['i__id']);
        continue;
    }

    //Have Needed Superpowers?
    $superpower_actives = array_intersect($this->config->item('e___n_10957'), $m['m_parents']);
    if(count($superpower_actives) && !superpower_assigned(end($superpower_actives))){
        continue;
    }



    $disable_manual_add = in_array($x__type, $this->config->item('e___n_12677'));
    $counter = null; //Assume no counters
    $this_tab = '';


    if($x__type==11020){

        //IDEA NEXT
        $ideas_next = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('e___n_7360')) . ')' => null, //ACTIVE
            'i__status IN (' . join(',', $this->config->item('e___n_7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('e___n_4486')) . ')' => null, //IDEA LINKS
            'x__left' => $i_focus['i__id'],
        ), array('x__right'), 0, 0, array('x__sort' => 'ASC'));

        //CHILD IDEAS
        $counter = count($ideas_next);

        $this_tab .= '<div id="list-in-' . $i_focus['i__id'] . '-0" class="list-group next_ideas">';
        foreach($ideas_next as $next_idea) {
            $this_tab .= view_i($next_idea, $i_focus['i__id'], false, $player_is_i_source);
        }

        if($player_is_i_source && $is_active){
            $this_tab .= '<div class="list-group-item list-adder itemidea '.superpower_active(10939).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$e___2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control ideaadder-level-2-child form-control-thick add-input montserrat algolia_search dotransparent"
                           maxlength="' . config_var(4736) . '"
                           idea-id="' . $i_focus['i__id'] . '"
                           id="addidea-c-' . $i_focus['i__id'] . '-0"
                           placeholder="NEXT IDEA">
                </div><div class="algolia_pad_search hidden i_pad_bottom"></div></div>';
        }

        $this_tab .= '</div>';

    } elseif(in_array($x__type, $this->config->item('e___n_7551'))){

        //Reference Sources Only:
        $i_notes = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('e___n_7360')) . ')' => null, //ACTIVE
            'x__type' => $x__type,
            'x__right' => $i_focus['i__id'],
        ), array('x__up'), 0, 0, array('x__sort' => 'ASC'));

        $counter = count($i_notes);

        $this_tab .= '<div id="add-source-' .$x__type . '" class="list-group source-adder">';

        foreach($i_notes as $i_note) {
            $this_tab .= view_e($i_note, 0, null, $player_is_i_source && $is_active, $player_is_i_source);
        }

        if($player_is_i_source && $is_active && !$disable_manual_add) {
            $this_tab .= '<div class="list-group-item list-adder itemsource no-side-padding source-only source-idea-' . $x__type . '" note_type_id="' . $x__type . '">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">' . $e___2738[4536]['m_icon'] . '</span></span>
                    <input type="text"
                           class="form-control form-control-thick algolia_search input_note_'.$x__type.' dotransparent add-input"
                           maxlength="' . config_var(6197) . '"                          
                           placeholder="NEW SOURCE">
                </div><div class="algolia_pad_search hidden pad_expand source-pad-' . $x__type . '"></div></div>';
        }

        $this_tab .= '</div>';

    } elseif(in_array($x__type, $this->config->item('e___n_12467'))){

        //MENCH COINS
        $counter = x_coins_idea($x__type, $i_focus['i__id']);
        $this_tab = x_coins_idea($x__type, $i_focus['i__id'], 1);

    } elseif(in_array($x__type, $this->config->item('e___n_4485'))){

        //IDEA NOTES
        $i_notes = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('e___n_7360')) . ')' => null, //ACTIVE
            'x__type' => $x__type,
            'x__right' => $i_focus['i__id'],
        ), array('x__player'), 0, 0, array('x__sort' => 'ASC'));

        $counter = count($i_notes);
        $this_tab .= view_i_note_mix($x__type, $i_notes);

    } elseif($x__type==12969){

        $player_discoveries = $this->X_model->fetch(array(
            'x__left' => $i_focus['i__id'],
            'x__type IN (' . join(',', $this->config->item('e___n_12969')) . ')' => null, //MY DISCOVERIES
            'x__status IN (' . join(',', $this->config->item('e___n_7359')) . ')' => null, //PUBLIC
        ), array('x__player'), 0, 0, array(), 'COUNT(x__id) as totals');
        $counter = $player_discoveries[0]['totals'];
        if($counter > 0){

            $this_tab .= '<div class="list-group">';
            foreach($this->X_model->fetch(array(
                'x__left' => $i_focus['i__id'],
                'x__type IN (' . join(',', $this->config->item('e___n_12969')) . ')' => null, //MY DISCOVERIES
                'x__status IN (' . join(',', $this->config->item('e___n_7359')) . ')' => null, //PUBLIC
            ), array('x__player')) as $player){
                $this_tab .= view_e($player);
            }
            $this_tab .= '</div>';

        }

    } elseif($x__type==12589){

        //IDAE LIST EDITOR
        $dropdown_options = '';
        $input_options = '';
        $counter = 0;

        foreach($this->config->item('e___12589') as $action_e__id => $e_list_action) {

            $counter++;
            $dropdown_options .= '<option value="' . $action_e__id . '">' .$e_list_action['m_name'] . '</option>';


            //Start with the input wrapper:
            $input_options .= '<span id="mass_id_'.$action_e__id.'" title="'.$e_list_action['m_desc'].'" class="inline-block '. ( $counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';

            if(in_array($action_e__id, array(12591, 12592))){

                //Source search box:

                //String command:
                $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Sources..." class="form-control algolia_search e_text_search border montserrat">';

                //We don't need the second value field here:
                $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

            } elseif(in_array($action_e__id, array(12611, 12612))){

                //String command:
                $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Ideas..." class="form-control algolia_search i_text_search border montserrat">';

                //We don't need the second value field here:
                $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

            }

            $input_options .= '</span>';

        }

        $counter = null;
        $this_tab .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important; margin-left: 33px;">';
        $this_tab .= '<div class="inline-box">';

        //Drop Down
        $this_tab .= '<select class="form-control border" name="mass_action_e__id" id="set_mass_action">';
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


    if(!$counter && $disable_manual_add){
        //Hide since Zero:
        continue;
    }


    $default_active = in_array($x__type, $this->config->item('e___n_12675'));

    echo '<li class="nav-item '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a class="nav-link tab-nav-'.$tab_group.' tab-head-'.$x__type.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m_icon']).'" href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$x__type.')" data-toggle="tooltip" data-placement="top" title="'.$m['m_name'].( strlen($m['m_desc']) ? ': '.$m['m_desc'] : '' ).'">'.$m['m_icon'].( is_null($counter) ? '' : ' <span class="en-type-counter-'.$x__type.'">'.view_number($counter).'</span>' ).'</a></li>';


    $tab_content .= '<div class="tab-content tab-group-'.$tab_group.' tab-data-'.$x__type.( $default_active ? '' : ' hidden ' ).'">';
    $tab_content .= $this_tab;
    $tab_content .= '</div>';

}
echo '</ul>';


//Show All Tab Content:
echo $tab_content;

echo '</div>';

