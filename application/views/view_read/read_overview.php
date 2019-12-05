
<div class="container">

    <?php $en_all_4463 = $this->config->item('en_all_4463') ?>

    <div class="alert alert-info" style="margin-top: 0;">
        <div><b class="montserrat"><?= $en_all_4463[2738]['m_name'] ?></b> <?= $en_all_4463[2738]['m_desc'] ?></div>
        <span class="top-players inline-block" style="padding: 10px 0;"><i class="fas fa-medal"></i> <a href="javascript:void(0);" onclick="load_leaderboard()" class="montserrat">SEE LEADERBOARD</a></span>
    </div>

    <div id="load_top_players"></div>

    <?php

    //Go through all categories and see which ones have published courses:
    foreach($this->config->item('en_all_10869') /* Course Categories */ as $en_id => $m) {

        //Count total published courses here:
        $published_ins = $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //READ LOGIN REQUIRED
            'ln_type_entity_id' => 4601, //BLOG KEYWORDS
            'ln_parent_entity_id' => $en_id,
        ), array('in_child'), 0, 0, array('in_outcome' => 'ASC'));

        if(!count($published_ins)){
            continue;
        }

        $common_prefix = common_prefix($published_ins, 'in_outcome');
        $common_prefix = null;

        //Show featured blogs in this category:
        echo '<div class="read-topic"><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'</div>';
        echo '<div class="list-group">';
        foreach($published_ins as $published_in){
            echo echo_in_read($published_in, $common_prefix);
        }
        echo '</div>';

    }

    ?>

</div>