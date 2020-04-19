<script>
    var in_loaded_id = <?= $in['in_id'] ?>;
</script>
<script src="/application/views/discover/discover_coin.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>


<div class="container container-wide">
    <?php
    $this->DISCOVER_model->discover_echo($in['in_id'], superpower_assigned());
    ?>
</div>