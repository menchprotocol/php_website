<script>
    var in_loaded_id = <?= $in['in_id'] ?>;
</script>
<script src="/application/views/read/read_coin.js?v=v<?= config_var(11060) ?>"
        type="text/javascript"></script>


<div class="container container-wide">
    <?php

    //Show breadcrumbs
    echo echo_read_breadcrumbs($in['in_id']);

    echo '<div class="doclear">&nbsp;</div>';

    $this->READ_model->read_echo($in['in_id'], superpower_assigned());

    ?>
</div>
