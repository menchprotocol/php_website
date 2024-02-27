<?php

//Just Viewing:
$access__i = access__i($focus_i['i__hashtag'], 0, $focus_i);

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
echo '<div class="main_item view_12273 row justify-content">';
echo view_card_i(42288,  $focus_i);
echo '</div>';


echo view_i_nav(false, $focus_i, $access__i);

?>

<script>
    $(document).ready(function () {
        show_more(<?= $focus_i['i__id'] ?>);
    });
</script>