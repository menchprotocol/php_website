<?php
$e___11035 = $this->config->item('e___11035');
$next_link = '<a href="'.( $i__id > 0 ? '/x/x_start/'.$i__id : home_url() ).'" style="margin-right: 8px;" class="pull-right inline-block"><b class="css__title special-text text__6197_14521 '.extract_icon_color($e___11035[14521]['m__icon']).'">'.$e___11035[14521]['m__title'].'</b>'.$e___11035[14521]['m__icon'].'</a>';
?>

<!-- HEADLINE -->
<div class="container fixed-top" style="padding: 10px 0 !important;">
    <?php echo $next_link; ?>
</div>

<div class="container">
    <?php

    echo '<h1 class="top-margin '.extract_icon_color($e___11035[14517]['m__icon']).'">' . $e___11035[14517]['m__title'] . '</h1>';
    if(strlen($e___11035[14517]['m__message']) > 0){
        echo '<div class="msg">' . $e___11035[14517]['m__message'] . '</div>';
    }

    echo view_e_settings(14517, false);

    echo '<div style="padding: 34px 0;">'.$next_link.'</div>';

    ?>
</div>

