<?php

//Focus Idea:
echo '<div class="main_item view_12273 row justify-content">';
echo view_card_i(42288,  $focus_i);
echo '</div>';


echo view_i_nav(false, $focus_i);

?>

<script>
    $(document).ready(function () {
        show_more(<?= $focus_i['i__id'] ?>);
    });
</script>