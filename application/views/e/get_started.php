<?php
$e___11035 = $this->config->item('e___11035');
?>

<!-- HEADLINE -->
<div class="container fixed-top" style="padding-bottom: 0 !important;">
    <div class="row">
        <div class="col-8"><?= '<span class="mench-circle e_ui_icon_14517">'.$e___11035[14517]['m__icon'].'</span><b class="montserrat text__6197_14517">'.$e___11035[14517]['m__title'].'</b>' ?></div>
        <div class="col-4"><?= '<a href="'.( $i__id > 0 ? '/'.$i__id : home_url() ).'" style="margin-right: 8px;" class="pull-right">'.$e___11035[14521]['m__title'].' '.$e___11035[14521]['m__icon'].'</a>' ?></div>
    </div>
</div>


<div class="container">
    <?php echo view_e_settings(14517, false); ?>
</div>

