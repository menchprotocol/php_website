<?php
$e___11035 = $this->config->item('e___11035');
$i__id = ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 );
?>
<script>
    function complete_setup(){

        $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');

        //Log transaction:
        if(x_create({
            x__source: js_pl_id,
            x__type: 14517,
            x__left: <?= $i__id ?>,
        })){
            setTimeout(function () {
                window.location = '<?= ( $i__id > 0 ? '/x/x_start/'.$i__id : ( isset($_GET['url']) ? urldecode($_GET['url']) : home_url() ) ) ?>';
            }, 89);
        }

    }
</script>
<?php

//Give them options to choose from:
echo view_e_settings(14517, false);

//CONTINUE:
echo '<div class="discover-controller">';
echo '<div><a class="controller-nav btn btn-lrg btn-discover go-next" href="javascript:void(0);" onclick="complete_setup()">'.$e___11035[14521]['m__title'].' '.$e___11035[14521]['m__icon'].'</a></div>';
echo '</div>';

