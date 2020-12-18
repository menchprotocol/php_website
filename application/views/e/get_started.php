<?php
$e___11035 = $this->config->item('e___11035');



//MENCH SETUP
echo '<div class="container">';
echo '<div class="text-center"><img src="/img/mench.png" class="mench-spin mench-large" /></div>';
echo '<h1 class="top-margin '.extract_icon_color($e___11035[14517]['m__icon']).'">' . $e___11035[14517]['m__title'] . '</h1>';
if(strlen($e___11035[14517]['m__message']) > 0){
    echo '<div class="msg">' . $e___11035[14517]['m__message'] . '</div>';
}

echo view_e_settings(14517, false);
echo '</div>';



//CONTINUE:
echo '<div class="container fixed-bottom light-bg">';
echo '<div class="discover-controller">';
echo '<div><a class="controller-nav btn btn-lrg btn-discover go-next" href="'.( $i__id > 0 ? '/x/x_start/'.$i__id : home_url() ).'">'.$e___11035[14521]['m__title'].' '.$e___11035[14521]['m__icon'].'</a></div>';
echo '</div>';
echo '</div>';




?>


