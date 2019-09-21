
<div class="row">
    <div class="<?= $this->config->item('css_column_1') ?>">

        <div>&nbsp;</div>
        <h5 class="badge badge-h indent1 inline-block"><i class="far fa-bookmark"></i> My Bookmarks</h5>

        <?php

        //List child intents:
        echo '<div class="list-group list-is-children">';
        foreach($this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $in_id,
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $bookmarked_in){
            echo echo_in($child_in, 2, $in['in_id']);
        }

        //Add child intent:
        echo '<div class="list-group-item list_input grey-block">
                    <div class="form-group is-empty" style="margin: 0; padding: 0;">
                        <input type="text"
                               class="form-control intentadder-level-2-child algolia_search"
                               maxlength="' . $this->config->item('in_outcome_max') . '"
                               intent-id="' . $in['in_id'] . '"
                               id="addintent-c-' . $in['in_id'] . '-0"
                               placeholder="Add Intent">
                    </div>
                   <div class="algolia_search_pad in_pad_bottom hidden"><span>Search existing intents or create a new one...</span></div>
            </div>';
        echo '</div>';

        ?>

    </div>

    <div class="<?= $this->config->item('css_column_2') ?>">&nbsp;</div>
</div>

<div style="height: 50px;">&nbsp;</div>