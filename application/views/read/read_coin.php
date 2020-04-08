<script>
    var in_loaded_id = <?= $in['in_id'] ?>;
</script>
<script src="/application/views/read/read_coin.js?v=v<?= config_var(11060) ?>"
        type="text/javascript"></script>


<div class="container container-wide">
    <?php
    $this->READ_model->read_echo($in['in_id'], superpower_assigned());

    //Home Page Stats?
    if($in['in_id']==config_var(12156)){

        echo '<div>As of <span data-toggle="tooltip" data-placement="top" title="'.date("Y-m-d H:i:s", $this->config->item('ps_timestamp')).'" class="">'.date("F jS", $this->config->item('ps_timestamp')).'</span>, <b class="montserrat">MENCH</b> has awarded <span class="montserrat source"><span data-toggle="tooltip" data-placement="top" title="'.number_format($this->config->item('ps_source_count'), 0).' SOURCES (People & Content)">'.echo_number($this->config->item('ps_source_count')).'</span> <i class="fas fa-circle source"></i> SOURCE COINS</span> that collectively earned <span class="montserrat blog"><span data-toggle="tooltip" data-placement="top" title="'.number_format($this->config->item('ps_blog_count'), 0).' BLOGS">'.echo_number($this->config->item('ps_blog_count')).'</span> <i class="fas fa-circle blog"></i> BLOG COINS</span> that generated <span class="montserrat read"><span data-toggle="tooltip" data-placement="top" title="'.number_format($this->config->item('ps_read_count'), 0).'">'.echo_number($this->config->item('ps_read_count')).'</span> <i class="fas fa-circle read"></i> READ COINS</span></div>';

    }
    ?>
</div>