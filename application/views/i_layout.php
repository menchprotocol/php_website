    <?php
$e___14874 = $this->config->item('e___14874'); //COINS
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$e___4485 = $this->config->item('e___4485'); //NAVIGATION

$e_of_i = e_of_i($i_focus['i__id']);
$is_active = in_array($i_focus['i__type'], $this->config->item('n___7356'));
$is_public = in_array($i_focus['i__type'], $this->config->item('n___7355'));
$superpower_13422 = superpower_active(13422, true); //Advance Sourcing
$superpower_14005 = superpower_active(14005, true);

?>

<style>
    .i_child_icon_<?= $i_focus['i__id'] ?> { display:none; }
    <?= ( !$e_of_i ? '.note-editor {display:none;}' : '' ) ?>
</style>

<input type="hidden" id="focus__id" value="<?= $i_focus['i__id'] ?>" />
<script src="/application/views/i_layout.js?v=<?= view_memory(6404,11060) ?>" type="text/javascript"></script>

<?php

$e_focus_found = false; //Used to determine the first tab to be opened
$show_previous = $e_of_i && $is_active;
$is_in_my_ideas = count($this->X_model->fetch(array(
    'x__up' => $member_e['e__id'],
    'x__right' => $i_focus['i__id'],
    'x__type' => 10573, //MY IDEAS
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
)));




if(!$e_of_i){

    //DO they already have a request?
    $request_history = $this->X_model->fetch(array(
        'x__source' => $member_e['e__id'],
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'x__type' => 14577,
        'x__right' => $i_focus['i__id'],
    ), array(), 1, 0, array('x__id' => 'DESC'));

    if(count($request_history)){

        echo '<div class="msg alert alert-warning no-margin"><span class="icon-block"><i class="fas fa-exclamation-circle zq12274"></i></span>You submitted your request to join ' . view_time_difference(strtotime($request_history[0]['x__time'])) . ' ago. You will be notified soon.</span></div>';

    } else {

        echo '<div class="msg alert alert-warning no-margin"><span class="icon-block"><i class="fas fa-exclamation-circle zq12274"></i></span>You are not a source for this idea, yet. <span class="inline-block '.superpower_active(10939).'"><a href="/i/i_e_add/'.$i_focus['i__id'].'" class="inline-block css__title">REQUEST TO JOIN</a></span></div>';

    }
}



$previous_is = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
    'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
    'x__right' => $i_focus['i__id'],
), array('x__left'), 0, 0, array('i__spectrum' => 'DESC'));

$body = '';
if($show_previous){
    $body .= '<div class="new-list-11019 list-adder '.superpower_active(10939).'">
                    <div class="input-group border">
                        <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'#new-list-11019 .add-input\').focus();"><span class="icon-block">'.$e___11035[14016]['m__cover'].'</span></a>
                        <input type="text"
                               class="form-control form-control-thick add-input algolia_search dotransparent"
                               maxlength="' . view_memory(6404,4736) . '"
                               placeholder="'.$e___11035[14016]['m__title'].'">
                    </div></div>';
}
$body .= '<div id="list-in-11019" class="row justify-content-center dominHeight">';
foreach($previous_is as $previous_i) {
    $body .= view_i(11019, 0, null, $previous_i, $e_of_i);
}
$body .= '</div>';

echo view_headline(11019, count($previous_is), $e___11035[11019], $body, count($previous_is) > 0 && in_array(11019, $this->config->item('n___20424')));


if(isset($_GET['load__e']) && $superpower_14005){
    //Filtered Specific Source:
    $e_filters = $this->E_model->fetch(array(
        'e__id' => intval($_GET['load__e']),
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($e_filters)){
        echo view__load__e($e_filters[0]);
    }
}



echo '<div class="row justify-content-center">';
echo view_i(4250, 0, null, $i_focus, $e_of_i);
echo '</div>';



//IDEA MESSAGES:
echo view_i_note_list(4231, false, $i_focus, $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'x__type' => 4231,
    'x__right' => $i_focus['i__id'],
), array('x__source'), 0, 0, array('x__spectrum' => 'ASC')), $e_of_i, false);


//IDEA LAYOUT
foreach($this->config->item('e___11018') as $x__type => $m){

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
    if($require && $missing){
        //STRICT: Anything missing and it would be skipped!
        continue;
    }

    $counter = null; //Assume no counters
    $ui = '';

    if(in_array($x__type, $this->config->item('n___7551'))){

        //Reference Sources Only:
        $i_notes = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => $x__type,
            'x__right' => $i_focus['i__id'],
        ), array('x__up'), 0, 0, array('x__spectrum' => 'ASC'));
        $counter = count($i_notes);

        $ui .= '<div id="add-e-' .$x__type . '" class="row justify-content-center e-adder" style="margin-bottom:41px;">';
        foreach($i_notes as $i_note) {
            $ui .= view_e($x__type, $i_note,  null, $e_of_i && $is_active);
        }
        $ui .= '</div>';

        if($e_of_i && $is_active && !in_array($x__type, $this->config->item('n___12677'))) {
            $ui .= '<div class="list-adder e-only-7551 e-i-' . $x__type . '" x__type="' . $x__type . '">
                <div class="input-group border">
                    <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'#new_e_' . $x__type . '\').focus();"><span class="icon-block">'.$e___11035[14055]['m__cover'].'</span></a>
                    <input type="text"
                           class="form-control form-control-thick algolia_search input_note_'.$x__type.' dotransparent add-input"
                           id="new_e_' . $x__type . '"                          
                           maxlength="' . view_memory(6404,6197) . '"                          
                           placeholder="' . $e___11035[14055]['m__title'] . '">
                </div><div class="algolia_pad_search hidden pad_expand e-pad-' . $x__type . '">&nbsp;</div></div>';
        }

    } elseif($x__type==12273){

        //IDEAS
        $is_next = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $i_focus['i__id'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));
        $counter = count($is_next);

        if(superpower_active(12700, true)){

            //IDEA LIST EDITOR
            $ui .= '<div class="action-middle-btn grey toggle_12589"><a href="javascript:void(0);" onclick="$(\'.toggle_12589\').toggleClass(\'hidden\');" title="'.$e___11035[12589]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___11035[12589]['m__cover'].'</a></div>';


            $ui .= '<div class="toggle_12589 hidden" style="margin-bottom:41px;">';
            $ui .= '<div class="headline"><span class="icon-block">'.$e___11035[12589]['m__cover'].'</span>'.$e___11035[12589]['m__title'].'</div>';
            $dropdown_options = '';
            $input_options = '';
            $this_counter = 0;

            foreach($this->config->item('e___12589') as $action_e__id => $e_list_action) {

                $this_counter++;
                $dropdown_options .= '<option value="' . $action_e__id . '">' .$e_list_action['m__title'] . '</option>';


                //Start with the input wrapper:
                $input_options .= '<span id="mass_id_'.$action_e__id.'" title="'.$e_list_action['m__message'].'" class="inline-block '. ( $this_counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';

                if(in_array($action_e__id, array(12591, 12592))){

                    //Source search box:

                    //String command:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Sources..." class="form-control algolia_search e_text_search border css__title">';

                    //We don't need the second value field here:
                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

                } elseif(in_array($action_e__id, array(12611, 12612))){

                    //String command:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Ideas..." class="form-control algolia_search i_text_search border css__title">';

                    //We don't need the second value field here:
                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

                }

                $input_options .= '</span>';

            }

            $ui .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important; margin-left: 41px;">';

            //Drop Down
            $ui .= '<select class="form-control border" name="mass_action_e__id" id="set_mass_action">';
            $ui .= $dropdown_options;
            $ui .= '</select>';

            $ui .= $input_options;

            $ui .= '<div><input type="submit" value="APPLY" class="btn btn-12273 inline-block"></div>';

            $ui .= '</form>';
            $ui .= '</div>';

        }


        $ui .= '<div id="list-in-13542" class="row justify-content-center hideIfEmpty">';
        foreach($is_next as $next_i) {
            $ui .= view_i(13542, 0, $i_focus, $next_i, $e_of_i);
        }
        $ui .= '</div>';

        if($e_of_i && $is_active){
            $ui .= '<div class="new-list-13542 list-adder '.superpower_active(10939).'">
                <div class="input-group border">
                    <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'#new-list-13542 .add-input\').focus();"><span class="icon-block">'.$e___11035[14016]['m__cover'].'</span></a>
                    <input type="text"
                           class="form-control form-control-thick add-input algolia_search dotransparent"
                           maxlength="' . view_memory(6404,4736) . '"
                           placeholder="'.$e___11035[14016]['m__title'].'">
                </div><div class="algolia_pad_search hidden">&nbsp;</div></div>';
        }

    } elseif($x__type==6255) {

        //DISCOVERIES
        $counter = view_coins_i(6255,  $i_focus, false);

        if($counter){

            $query_filters = array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
                'x__left' => $i_focus['i__id'],
            );
            if(isset($_GET['load__e']) && $superpower_14005){
                $query_filters['x__source'] = intval($_GET['load__e']);
            }

            //Fetch Results:
            $query = $this->X_model->fetch($query_filters, array('x__source'), view_memory(6404,11064), 0, array('x__id' => 'DESC'));

            //Return UI:
            $ui .= '<div class="row justify-content-center">';
            foreach($query as $item){
                $ui .= view_e(6255, $item);
            }
            $ui .= '</div>';

        } else {

            //No Results:
            $e___14874 = $this->config->item('e___14874'); //COINS
            //$ui .= '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> No '.$e___14874[6255]['m__title'].' yet</div>';

        }

    } elseif($x__type==12274){

        $counter = 0;

        //Direct: Adjustable
        $ui .= '<div class="row justify-content-center" id="list-in-4983">';

        //Inherited: Non Adjustable
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__type !=' => 4983, //References
            'x__right' => $i_focus['i__id'],
            'x__up >' => 0,
        ), array('x__up'), 0, 0, array('e__spectrum' => 'DESC')) as $e_ref){
            $ui .= view_e($e_ref['x__type'], $e_ref, null, $e_of_i);
            $counter++;
        }

        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => 4983, //References
            'x__right' => $i_focus['i__id'],
            'x__up >' => 0,
        ), array('x__up'), 0, 0, array('x__id' => 'ASC')) as $e_ref){
            $ui .= view_e($e_ref['x__type'], $e_ref, null, $e_of_i);
            $counter++;
        }
        $ui .= '</div>';

        $ui .= '<div id="new_4983" class="list-adder '.superpower_active(10939).'">
                    <div class="input-group border">
                        <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'#New4983input\').focus();"><span class="icon-block">'.$e___11035[14055]['m__cover'].'</span></a>
                        <input type="text"
                               class="form-control form-control-thick algolia_search dotransparent add-input"
                               id="New4983input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="'.$e___11035[14055]['m__title'].'">
                    </div><div class="algolia_pad_search hidden pad_expand">&nbsp;</div></div>';

    } elseif(in_array($x__type, $this->config->item('n___4485'))){

        //IDEA NOTES
        $i_notes = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => $x__type,
            'x__right' => $i_focus['i__id'],
        ), array('x__source'), 0, 0, array('x__spectrum' => 'ASC'));
        $counter = count($i_notes);
        $ui .= view_i_note_list($x__type, false, $i_focus, $i_notes, $e_of_i, false);

    } elseif($x__type==12969){

        $u_x = $this->X_model->fetch(array(
            'x__left' => $i_focus['i__id'],
            'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //MY DISCOVERIES
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array('x__source'), 0, 0, array(), 'COUNT(x__id) as totals');
        $counter = $u_x[0]['totals'];
        if($counter > 0){

            $ui .= '<div class="row justify-content-center">';
            foreach($this->X_model->fetch(array(
                'x__left' => $i_focus['i__id'],
                'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //MY DISCOVERIES
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array('x__source')) as $u){
                $ui .= view_e(12969, $u);
            }
            $ui .= '</div>';

        }
    }

    if(!$counter && !in_array($x__type, $this->config->item('n___13530'))){
        //Hide since Zero:
        continue;
    }

    //Show headline:
    echo view_headline($x__type, $counter, $m, $ui, in_array($x__type, $this->config->item('n___20424')));

}
