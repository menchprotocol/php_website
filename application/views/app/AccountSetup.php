<?php
$e___11035 = $this->config->item('e___11035');
$i__id = 0;

if(isset($_GET['i__hashtag']) && strlen($_GET['i__hashtag'])){
    foreach($this->I_model->fetch(array(
        'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
    )) as $i){
        $i__id = $i['i__id'];
    }
}

?>
    <script>
        function complete_setup(){
            //Log transaction:
            if(x_create({
                x__player: js_pl_id,
                x__type: 14517,
                x__previous: <?= $i__id ?>,
            })){
                $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');
                js_redirect('<?= ( isset($_GET['i__hashtag']) ? '/ajax/x_start/'.$_GET['i__hashtag'] : ( isset($_GET['url']) ? urldecode($_GET['url']) : '/' /* Home Page */ ) ) ?>');
            }

        }
    </script>
<?php

//Give them options to choose from:
foreach($this->config->item('e___14517') as $x__type => $m) {
    if(in_array($x__type, $this->config->item('n___33331')) || in_array($x__type, $this->config->item('n___33332'))){
        echo view_instant_select($x__type, $player_e['e__id']);
    }
}


//CONTINUE:
echo '<div class="nav-controller">';
echo '<div><a class="btn btn-lrg btn-6255 go-next" href="javascript:void(0);" onclick="complete_setup()">'.$e___11035[14521]['m__title'].' '.$e___11035[14521]['m__cover'].'</a></div>';
echo '</div>';