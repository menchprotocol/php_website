<script>
    var in_loaded_id = <?= $in['in_id'] ?>;
</script>
<script src="/application/views/read/read_echo.js?v=v<?= config_var(11060) ?>"
        type="text/javascript"></script>


<div class="container" style="padding-bottom: 50px;">
    <?php

    $this->READ_model->read_echo($in['in_id'], superpower_assigned());

    if($autoexpand){
        echo echo_tree_actionplan($in, $autoexpand);
    }

    echo '<div class="inline-block pull-right '.superpower_active(10939).'"><a class="btn btn-blog" href="/blog/'.$in['in_id'].'">EDIT <i class="fas fa-pen-square"></i></a></div>';

    ?>
    <div style="clear: both;">&nbsp;</div>
</div>
