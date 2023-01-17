<?php
$e___11035 = $this->config->item('e___11035');
$i__id = ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 );
?>
<script>
    function complete_setup(){
        //Log transaction:
        if(x_create({
            x__creator: js_pl_id,
            x__type: 14517,
            x__left: <?= $i__id ?>,
        })){
            $('.go-next').html('<i class="fal fa-yin-yang fa-spin"></i>');
            window.location = '<?= ( $i__id > 0 ? '/x/x_start/'.$i__id : ( isset($_GET['url']) ? urldecode($_GET['url']) : '/' /* Home Page */ ) ) ?>';
        }

    }
</script>
<?php

//Give them options to choose from:
echo view_e_settings(14517, true);

//CONTINUE:
echo '<div class="nav-controller">';
echo '<div><a class="btn btn-lrg btn-6255 go-next" href="javascript:void(0);" onclick="complete_setup()">'.$e___11035[14521]['m__title'].' '.$e___11035[14521]['m__cover'].'</a></div>';
echo '</div>';


