<?php
$in_filters = in_get_filters(true);
?>
<script>
    //Define some global variables:
    var in_focus_id = <?= $in['in_id'] ?>;
    var js_in_filters = <?= json_encode($in_filters) ?>; //Passon current filters to user match list
</script>
<script src="/js/custom/intent-actionplan.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>


<div class="row">
    <div class="col-xs-7 cols">

        <?php

        //Do not show parent intent section for our top intention:
        if($in['in_id']!=7766 || count($in['in__parents']) > 0){
            //Parent intents:
            echo '<h5 class="badge badge-h intent_fadeout"><span class="li-parent-count parent-counter-' . $in['in_id'] . '">' . count($in['in__parents']) . '</span> Parent' . echo__s(count($in['in__parents'])) . '</h5>';
            echo '<div id="list-in-' . $in['in_id'] . '-1" class="list-group list-level-2">';

            //List current parent intents:
            foreach ($in['in__parents'] as $parent_in) {
                echo echo_in($parent_in, 2, 0, true);
            }

            //Add parent intent:
            echo '<div class="list-group-item list_input grey-block intent_fadeout">
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
        }

        //Focus intent:
        echo '<h5 class="badge badge-h indent1 intent_fadeout skip_fadeout_'.$in['in_id'].'" style="display: inline-block;">Intent #'.$in['in_id'].'</h5>';

        //Hidden Links:
        echo '<a class="secret" href="/intents/cron__sync_extra_insights/' . $in['in_id'] . '/1?redirect=/' . $in['in_id'] . '" style="margin-left: 5px;" onclick="turn_off()" data-toggle="tooltip" title="Updates intent tree cache" data-placement="top"><i class="fal fa-sync-alt"></i></a>';

        echo '<a class="secret" href="/intents/in_review_metadata/' . $in['in_id'] . '" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Review Intent Metadata" data-placement="top"><i class="fas fa-function"></i></a>';

        echo '<a class="secret" href="/links/cron__sync_algolia/in/' . $in['in_id'] . '" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Update Algolia Search Index" data-placement="top"><i class="fas fa-search"></i></a>';

        echo '<div class="list-group indent1">';
        echo echo_in($in, 1);
        echo '</div>';



        //Expand/Contract All buttons:
        $metadata = unserialize($in['in_metadata']);

        echo '<div class="indent2 intent_fadeout">';
        echo '<h5 class="badge badge-h intent_fadeout" style="display: inline-block;"><span class="li-children-count children-counter-' . $in['in_id'] . '">' . count($in['in__children']) . '</span> Children</h5>';

        echo '<div id="expand_intents" style="padding-left:8px; display: inline-block;">';
        echo '<i class="far fa-plus-circle expand_all" style="font-size: 1.2em;" data-toggle="tooltip" title="Expand Grandchildren" data-placement="top"></i> &nbsp;';
        echo '<i class="far fa-minus-circle close_all" style="font-size: 1.2em;" data-toggle="tooltip" title="Contact Grandchildren" data-placement="top"></i>';
        echo '<i class="far fa-filter toggle_filters" style="font-size: 1.2em; margin-left: 6px;" data-toggle="tooltip" title="Apply Intent Filters" data-placement="top"></i>';
        echo '</div>';


        echo '<div class="inline-box intent_fadeout in__filters '.( isset($_GET['filter_user']) || isset($_GET['filter_time']) ? '' : 'hidden' ).'">';
        echo '<form method="GET" action="" style="width: 100% !important;">';

        echo '<table><tr>';

        //Date Ranges:
        echo '<td style="padding-right: 10px;"><span class="mini-header">Filter Time</span><select name="filter_time" data-toggle="tooltip" title="Time Range" data-placement="top" class="form-control border">';
        foreach($in_filters['get_filter_options'] as $get_filter_option){
            echo '<option value="'.$get_filter_option['range_start'].'-'.$get_filter_option['range_end'].'" '.( isset($_GET['filter_time']) && $_GET['filter_time']==$get_filter_option['range_start'].'-'.$get_filter_option['range_end'] ? 'selected="selected"' : '' ).'>'.$get_filter_option['range_name'].'</option>';
        }
        echo '</select></td>';


        //Specific User:
        echo '<td style="padding-right: 10px;"><span class="mini-header">Filter User</span><input type="text" id="filter_user" data-toggle="tooltip" title="Filter by specific User" data-placement="top" name="filter_user" style="width:250px;" placeholder="Search Users..." class="form-control algolia_search border inline-block" value="'.( isset($_GET['filter_user']) ? $_GET['filter_user'] : '' ).'"></td>';


        //Go button:
        echo '<td><span class="mini-header">&nbsp;</span><input type="submit" value="Apply" class="btn btn-primary inline-block"></td>';


        echo '</tr></table>';
        echo '</form>';
        echo '</div>';



        echo '</div>';


        //List child intents:
        echo '<div id="list-in-' . $in['in_id'] . '-0" class="list-group list-is-children list-level-2 indent2">';
        foreach ($in['in__children'] as $child_in) {
            echo echo_in($child_in, 2, $in['in_id']);
        }

        //Add child intent:
        echo '<div class="list-group-item list_input grey-block intent_fadeout">
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