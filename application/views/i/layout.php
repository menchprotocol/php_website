<?php
$e___12467 = $this->config->item('e___12467');
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION

$e_owns_i = e_owns_i($i_focus['i__id']);
$is_active = in_array($i_focus['i__status'], $this->config->item('n___7356'));
$is_public = in_array($i_focus['i__status'], $this->config->item('n___7355'));

?>

<style>
    .i_child_icon_<?= $i_focus['i__id'] ?> { display:none; }
    <?= ( !$e_owns_i ? '.note-editor {display:none;}' : '' ) ?>
</style>

<script>
    //Include some cached sources:
    var focus_i__id = <?= $i_focus['i__id'] ?>;
</script>
<script src="/application/views/i/layout.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<?php

$e_focus_found = false; //Used to determine the first tab to be opened

echo '<div class="container" style="padding-bottom:42px;">';

if(!$e_owns_i){
    echo '<div class="alert alert-info no-margin"><span class="icon-block"><i class="fas fa-exclamation-circle source"></i></span>You are not a source for this idea, yet. <a href="/i/i_e_request/'.$i_focus['i__id'].'" class="inline-block montserrat">REQUEST INVITE</a><span class="inline-block '.superpower_active(10984).'">&nbsp;or <a href="/i/i_e_add/'.$i_focus['i__id'].'" class="montserrat">ADD MYSELF AS SOURCE</a></span></div>';
}

if(isset($_GET['focus__e'])){
    //Filtered Specific Source:
    $e_filters = $this->E_model->fetch(array(
        'e__id' => intval($_GET['focus__e']),
        'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($e_filters)){
        echo '<div class="alert alert-danger no-margin"><span class="icon-block"><i class="fas fa-filter discover"></i></span>Showing Discoveries for ' . view_e__icon($e_filters[0]['e__icon']) . '&nbsp;<a href="/@'.$e_filters[0]['e__id'].'" class="'.extract_icon_color($e_filters[0]['e__icon']).'">' . $e_filters[0]['e__title'].'</a> Only (<a href="/'.$this->uri->segment(1).'">Remove Filter</a>)</div>';
    }
}



//IDEA PREVIOUS
$is_previous = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
    'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
    'x__right' => $i_focus['i__id'],
), array('x__left'), 0);

echo '<div id="list-in-' . $i_focus['i__id'] . '-1" class="list-group previous_is">';
foreach($is_previous as $previous_i) {
    echo view_i($previous_i, $i_focus['i__id'], true, e_owns_i($previous_i['i__id']));
}
if( $e_owns_i && $is_active && $i_focus['i__id']!=config_var(12137)){
    echo '<div class="list-group-item list-adder itemidea '.superpower_active(10984).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$e___12467[12273]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control IdeaAddPrevious form-control-thick montserrat add-input algolia_search dotransparent"
                           maxlength="' . config_var(4736) . '"
                           i-id="' . $i_focus['i__id'] . '"
                           id="addi-c-' . $i_focus['i__id'] . '-1"
                           placeholder="PREVIOUS IDEA">
                </div><div class="algolia_pad_search hidden i_pad_top"></div></div>';
}
echo '</div>';





//IDEA TITLE
echo '<div class="itemidea">';
echo view_input_text(4736, $i_focus['i__title'], $i_focus['i__id'], ($e_owns_i && $is_active), 0, true, view_cache(4737 /* Idea Status */, $i_focus['i__status'], true, 'top', $i_focus['i__id']));
echo '</div>';


//IDEA MESSAGES:
echo view_i_note_mix(4231, $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'x__type' => 4231,
    'x__right' => $i_focus['i__id'],
), array('x__source'), 0, 0, array('x__sort' => 'ASC')));


//IDEA TYPE
echo '<div class="pull-left both-margin left-margin">'.view_input_dropdown(7585, $i_focus['i__type'], 'btn-i', $e_owns_i && $is_active, true, $i_focus['i__id']).'</div>';

//IDEA STATUS
echo '<div class="inline-block pull-left both-margin left-half-margin">'.view_input_dropdown(4737, $i_focus['i__status'], 'btn-i', $e_owns_i, true, $i_focus['i__id']).'</div>';

//IDEA TIME
echo '<div class="inline-block pull-left both-margin left-half-margin '.superpower_active(10986).'">'.view_input_text(4356, $i_focus['i__duration'], $i_focus['i__id'], $e_owns_i && $is_active, 0).'</div>';

echo '<div class="doclear">&nbsp;</div>';




//IDEA LAYOUT
$tab_group = 11018;
$tab_content = '';
echo '<ul class="nav nav-tabs nav-sm">';
foreach($this->config->item('e___'.$tab_group) as $x__type => $m){


    //Is this a caret menu?
    if(in_array(11040 , $m['m_parents'])){
        echo view_caret($x__type, $m, $i_focus['i__id']);
        continue;
    }

    //Have Needed Superpowers?
    $superpower_actives = array_intersect($this->config->item('n___10957'), $m['m_parents']);
    if(count($superpower_actives) && !superpower_assigned(end($superpower_actives))){
        continue;
    }



    $counter = null; //Assume no counters
    $focus_tab = '';


    if($x__type==11020){

        //IDEA TREE

        //IDEA TREE
        $is_next = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $i_focus['i__id'],
        ), array('x__right'), 0, 0, array('x__sort' => 'ASC'));

        //CHILD IDEAS
        $counter = count($is_next);

        $focus_tab .= '<div id="list-in-' . $i_focus['i__id'] . '-0" class="list-group next_is">';
        foreach($is_next as $next_i) {
            $focus_tab .= view_i($next_i, $i_focus['i__id'], false, $e_owns_i);
        }

        if($e_owns_i && $is_active){
            $focus_tab .= '<div class="list-group-item list-adder itemidea '.superpower_active(10939).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$e___12467[12273]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control ideaadder-level-2-child form-control-thick add-input montserrat algolia_search dotransparent"
                           maxlength="' . config_var(4736) . '"
                           i-id="' . $i_focus['i__id'] . '"
                           id="addi-c-' . $i_focus['i__id'] . '-0"
                           placeholder="NEXT IDEA">
                </div><div class="algolia_pad_search hidden i_pad_bottom"></div></div>';
        }

        $focus_tab .= '</div>';

    } elseif(in_array($x__type, $this->config->item('n___7551'))){

        //Reference Sources Only:
        $i_notes = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => $x__type,
            'x__right' => $i_focus['i__id'],
        ), array('x__up'), 0, 0, array('x__sort' => 'ASC'));

        $counter = count($i_notes);

        $focus_tab .= '<div id="add-e-' .$x__type . '" class="list-group e-adder">';

        foreach($i_notes as $i_note) {
            $focus_tab .= view_e($i_note, 0, null, $e_owns_i && $is_active, $e_owns_i);
        }

        if($e_owns_i && $is_active && !in_array($x__type, $this->config->item('n___12677'))) {
            $focus_tab .= '<div class="list-group-item list-adder itemsource no-side-padding e-only e-i-' . $x__type . '" note_type_id="' . $x__type . '">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">' . $e___12467[12274]['m_icon'] . '</span></span>
                    <input type="text"
                           class="form-control form-control-thick algolia_search input_note_'.$x__type.' dotransparent add-input"
                           maxlength="' . config_var(6197) . '"                          
                           placeholder="SOURCE TITLE/URL">
                </div><div class="algolia_pad_search hidden pad_expand e-pad-' . $x__type . '"></div></div>';
        }

        $focus_tab .= '</div>';

    } elseif(in_array($x__type, $this->config->item('n___12467'))){

        //MENCH COINS
        $counter = x_coins_i($x__type, $i_focus['i__id']);
        $focus_tab = x_coins_i($x__type, $i_focus['i__id'], 1);

    } elseif(in_array($x__type, $this->config->item('n___4485'))){

        //IDEA NOTES
        $i_notes = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => $x__type,
            'x__right' => $i_focus['i__id'],
        ), array('x__source'), 0, 0, array('x__sort' => 'ASC'));

        $counter = count($i_notes);
        $focus_tab .= view_i_note_mix($x__type, $i_notes);

    } elseif($x__type==12969){

        $miner_x = $this->X_model->fetch(array(
            'x__left' => $i_focus['i__id'],
            'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //MY DISCOVERIES
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array('x__source'), 0, 0, array(), 'COUNT(x__id) as totals');
        $counter = $miner_x[0]['totals'];
        if($counter > 0){

            $focus_tab .= '<div class="list-group">';
            foreach($this->X_model->fetch(array(
                'x__left' => $i_focus['i__id'],
                'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //MY DISCOVERIES
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array('x__source')) as $miner){
                $focus_tab .= view_e($miner);
            }
            $focus_tab .= '</div>';

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
        $focus_tab .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important; margin-left: 33px;">';
        $focus_tab .= '<div class="inline-box">';

        //Drop Down
        $focus_tab .= '<select class="form-control border" name="mass_action_e__id" id="set_mass_action">';
        $focus_tab .= $dropdown_options;
        $focus_tab .= '</select>';

        $focus_tab .= $input_options;

        $focus_tab .= '<div><input type="submit" value="APPLY" class="btn btn-i inline-block"></div>';

        $focus_tab .= '</div>';
        $focus_tab .= '</form>';

    } else {

        //Not supported via here:
        continue;

    }


    if(!$counter && !in_array($x__type, $this->config->item('n___13530'))){
        //Hide since Zero:
        continue;
    }

    $default_active = in_array($x__type, $this->config->item('n___12675'));


    echo '<li class="nav-item '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a class="nav-x tab-nav-'.$tab_group.' tab-head-'.$x__type.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m_icon']).'" href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$x__type.')" data-toggle="tooltip" data-placement="top" title="'.$m['m_name'].( strlen($m['m_desc']) ? ': '.$m['m_desc'] : '' ).'">'.$m['m_icon'].( is_null($counter) ? '' : ' <span class="en-type-counter-'.$x__type.'">'.view_number($counter).'</span>' ).'</a></li>';


    $tab_content .= '<div class="tab-content tab-group-'.$tab_group.' tab-data-'.$x__type.( $default_active ? '' : ' hidden ' ).'">';
    $tab_content .= $focus_tab;
    $tab_content .= '</div>';

}
echo '</ul>';


//Show All Tab Content:
echo $tab_content;

echo '</div>';

