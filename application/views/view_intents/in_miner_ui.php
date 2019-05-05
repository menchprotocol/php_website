<script>
    //Define some global variables:
    var in_focus_id = <?= $in['in_id'] ?>;
</script>
<script src="/js/custom/intent-actionplan.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>


<div class="row">
    <div class="col-xs-7 cols">

        <?php

        //Parent intents:
        echo '<h5 class="badge badge-h"><span class="li-parent-count parent-counter-' . $in['in_id'] . '">' . count($in['in__parents']) . '</span> Parent' . echo__s(count($in['in__parents'])) . '</h5>';
        echo '<div id="list-in-' . $in['in_id'] . '-1" class="list-group list-level-2">';

        //List current parent intents:
        foreach ($in['in__parents'] as $parent_in) {
            echo echo_in($parent_in, 2, 0, true);
        }

        //Add parent intent:
        echo '<div class="list-group-item list_input grey-block">
                    <div class="form-group is-empty" style="margin: 0; padding: 0;">
                        <input type="text"
                               class="form-control intentadder-level-2-top algolia_search"
                               intent-id="' . $in['in_id'] . '"
                               id="addintent-c-' . $in['in_id'] . '-1"
                               placeholder="Add #Intent">
                    </div>
                   <div class="algolia_search_pad in_pad_top hidden"><span>Search existing intents or create a new one...</span></div>
            </div>';
        echo '</div>';








        //Focus intent:
        echo '<h5 class="badge badge-h indent1" style="display: inline-block;">Intent #'.$in['in_id'].'</h5>';

        echo '<a class="secret" href="/intents/cron__sync_extra_insights/' . $in['in_id'] . '/1?redirect=/' . $in['in_id'] . '" style="margin-left: 5px;" onclick="turn_off()" data-toggle="tooltip" title="Updates intent tree cache" data-placement="top"><i class="fal fa-sync-alt"></i></a>';

        //Hidden link to Metadata:
        echo '<a class="secret" href="/intents/in_review_metadata/' . $in['in_id'] . '" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Review Intent Metadata" data-placement="top"><i class="fas fa-function"></i></a>';

        echo '<div class="list-group indent1">';
        echo echo_in($in, 1);
        echo '</div>';



        //Expand/Contract All buttons:
        $metadata = unserialize($in['in_metadata']);

        echo '<div class="indent2">';
        echo '<h5 class="badge badge-h" style="display: inline-block;"><span class="li-children-count children-counter-' . $in['in_id'] . '">' . count($in['in__children']) . '</span> Children</h5>';

        echo '<div id="expand_intents" style="padding-left:8px; display: inline-block;">';
        echo '<i class="fas fa-plus-circle expand_all" style="font-size: 1.2em;"></i> &nbsp;';
        echo '<i class="fas fa-minus-circle close_all" style="font-size: 1.2em;"></i>';
        echo '</div>';


        echo '</div>';

        //Show potential errors detected in the Action Plan via our JS functions:
        echo '<div id="in_children_errors indent2"></div>';

        //List child intents:
        echo '<div id="list-in-' . $in['in_id'] . '-0" class="list-group list-is-children list-level-2 indent2">';
        foreach ($in['in__children'] as $child_in) {
            echo echo_in($child_in, 2, $in['in_id']);
        }

        //Add child intent:
        echo '<div class="list-group-item list_input grey-block">
                    <div class="form-group is-empty" style="margin: 0; padding: 0;">
                        <input type="text"
                               class="form-control intentadder-level-2-bottom algolia_search"
                               maxlength="' . $this->config->item('in_outcome_max') . '"
                               intent-id="' . $in['in_id'] . '"
                               id="addintent-c-' . $in['in_id'] . '-0"
                               placeholder="Add #Intent">
                    </div>
                   <div class="algolia_search_pad in_pad_bottom hidden"><span>Search existing intents or create a new one...</span></div>
            </div>';
        echo '</div>';

        ?>

    </div>


    <div class="col-xs-5 cols">
        <?php $this->load->view('view_intents/in_right_column'); ?>
    </div>
</div>