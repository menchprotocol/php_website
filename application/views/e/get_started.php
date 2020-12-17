<?php
$e___11035 = $this->config->item('e___11035');
?>

<!-- HEADLINE -->
<div class="container fixed-top" style="padding-bottom: 0 !important;">
    <?= '<a href="'.( $i__id > 0 ? '/x/x_start/'.$i__id : home_url() ).'" style="margin-right: 8px;" class="pull-right"><b class="montserrat text-logo text__6197_14521">'.$e___11035[14521]['m__title'].'</b> '.$e___11035[14521]['m__icon'].'</a>' ?>
</div>

<div class="container">
    <?php

    if(strlen($e___11035[14517]['m__message']) > 0){
        echo '<div class="msg">' . $e___11035[14517]['m__message'] . '</div>';
    } else {
        echo '<h1 class="'.extract_icon_color($e___11035[14517]['m__icon']).'">' . $e___11035[14517]['m__title'] . '</h1>';
    }

    echo view_e_settings(14517, false);

    ?>
</div>

