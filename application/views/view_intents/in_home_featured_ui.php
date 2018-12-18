<div>
    <h3>Hi, I'm Mench, a human-trained personal assistant on a mission to <?= $this->config->item('in_primary_name') ?>
        .</h3>
    <h3>How can I help you today?</h3>
    <div class="list-group actionplan_list" style="margin-top: 10px;">
        <?php
        foreach ($featured_cs as $featured_c) {
            echo fn___echo_in_featured($featured_c);
        }
        ?>
    </div>
</div>