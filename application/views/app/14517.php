<script>
    function loading(){
        $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');
    }
</script>
<?php

$e___11035 = $this->config->item('e___11035');
$i__id = ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 );

//Give them options to choose from:
echo view_e_settings(14517, false);

//CONTINUE:
echo '<div class="discover-controller">';
echo '<div><a class="controller-nav btn btn-lrg btn-discover go-next" href="'.( $i__id > 0 ? '/x/x_start/'.$i__id : home_url() ).'" onclick="loading()">'.$e___11035[14521]['m__title'].' '.$e___11035[14521]['m__icon'].'</a></div>';
echo '</div>';

