<?php

//Just Viewing:
$limit = view_memory(6404,11064);
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$e___11035 = $this->config->item('e___11035'); //Summary
$e___26005 = $this->config->item('e___26005');
$write_privacy_i = write_privacy_i($focus_i['i__hashtag']);
$this->X_model->create(array(
    'x__creator' => $member_e['e__id'],
    'x__type' => 4993, //Member Opened Idea
    'x__next' => $focus_i['i__id'],
    'x__weight' => $new_order,
));


if($write_privacy_i && count($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
        'x__next' => $focus_i['i__id'],
        'x__following' => 4235,
    )))){
    echo '<div class="alert alert-default" role="alert"><span class="icon-block-xs">'.$e___11035[30795]['m__cover'].'</span>You can discover this idea in <a href="/'.$focus_i['i__hashtag'].'/start"><b><u>'.$e___11035[30795]['m__title'].'</u></b></a></div>';
}

//Focusing on a certain source?
if(isset($_GET['focus__e']) && superpower_unlocked(12701)){
    //Filtered Specific Source:
    $e_filters = $this->E_model->fetch(array(
        'e__id' => intval($_GET['focus__e']),
        'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($e_filters)){
        echo view__focus__e($e_filters[0]);
    }
}



//Focus Idea:
echo '<div class="main_item row justify-content">';
echo view_card_i(42288,  $focus_i);
echo '</div>';


//Tab content:
foreach($this->config->item('e___31890') as $x__type => $m) {
    $can_add = $write_privacy_i && in_array($x__type, $CI->config->item('n___42262'));
    echo '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="0">'.( $can_add ? '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="+ Add @source">
                    </div></div></div><div class="algolia_pad_finder row justify-content dropdown_'.$x__type.'"></div></div>'.
            '<script> $(document).ready(function () { load_finder(12273, '.$x__type.'); }); </script>' : '').'<div class="tab_content"></div></div>';

}




?>

<input type="hidden" id="page_limit" value="<?= $limit ?>" />
<input type="hidden" id="focus_card" value="12273" />
<input type="hidden" id="focus_handle" value="<?= $focus_i['i__hashtag'] ?>" />
<input type="hidden" id="focus_id" value="<?= $focus_i['i__id'] ?>" />
<script>

    $(document).ready(function () {

        show_more(<?= $focus_i['i__id'] ?>);

        //Look for power editor updates:
        $('.x_set_class_text').keypress(function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code==13) {
                x_set_text(this);
                e.preventDefault();
            }
        }).change(function() {
            x_set_text(this);
        });

    });

</script>

