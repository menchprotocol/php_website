<div>
    <h3>Hi, I'm Mench, a human-trained personal assistant on a mission to <?= $this->config->item('in_strategy_name') ?>.</h3>
    <h3>How can I help you today?</h3>
    <div class="list-group actionplan_list maxout" style="margin-top:40px;">
        <?php
        foreach ($featured_ins as $featured_c) {
            echo fn___echo_in_featured($featured_c);
        }
        ?>
    </div>
</div>