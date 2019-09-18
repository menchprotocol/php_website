<?php
$in_filters = in_get_filters(true);
$en_all_7369 = $this->config->item('en_all_7369');
?>
<script>
    //Define some global variables:
    var in_loaded_id = <?= $in['in_id'] ?>;
    var js_in_filters = <?= json_encode($in_filters) ?>; //Passon current filters to user match list
</script>
<script src="/js/custom/intent-manage-js.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>

<style>
    .in_child_icon_<?= $in['in_id'] ?>{ display:none; }
</style>

<div class="row">
    <div class="<?= $this->config->item('css_column_1') ?>">
        <?php
        if($in['in_id'] == $this->config->item('in_mission_id')){

            //Focus intent:
            echo '<div>&nbsp;</div>'; //Give some top space since we don't have parents here...
            echo '<h5 class="badge badge-h indent1 inline-block"><i class="far fa-globe"></i> Our Mission</h5>';

        } else {

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
                                   class="form-control intentadder-level-2-parent algolia_search"
                                   intent-id="' . $in['in_id'] . '"
                                   id="addintent-c-' . $in['in_id'] . '-1"
                                   placeholder="+ Intent">
                        </div>
                       <div class="algolia_search_pad in_pad_top hidden"><span>Search existing intents or create a new one...</span></div>
                </div>';
            echo '</div>';




            echo '<div style="min-height:11px;">';
            echo '<div class="intent-header">';

                //Focus intent:
            echo '<h5 class="badge badge-h indent1 inline-block">Intent #'.$in['in_id'].'</h5>';

            echo '<h5 class="badge badge-h indent1 inline-block">'.$en_all_7369[7765]['m_icon'].' &nbsp;<input id="landing_page_url" data-toggle="tooltip" title="Click to Copy URL" data-placement="bottom" type="url" value="mench.com/' . $in['in_id'] .'" style="padding:0; margin:-2px 0; width:144px; background-color:transparent; border:0; color:#FFF; cursor:copy !important;" /><a href="/' . $in['in_id'] . '" target="_blank" style="margin-left:7px; color:#FFF !important;" data-toggle="tooltip" title="Open Landing Page (New Window)" data-placement="bottom"><i class="fas fa-external-link"></i></a><span id="landing_page_state"></span></h5>';

            //Hidden Links for Trainers ONLY:
            echo '<span class="'.advance_mode().'">';

            echo '<a class="secret" href="/intents/cron__sync_extra_insights/' . $in['in_id'] . '/1?redirect=/' . $in['in_id'] . '" style="margin-left:20px;" onclick="turn_off()" data-toggle="tooltip" title="Updates intent tree cache" data-placement="bottom"><i class="fal fa-sync-alt"></i></a>';

            echo '<a class="secret" href="/intents/in_review_metadata/' . $in['in_id'] . '" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Review Intent Metadata" data-placement="bottom"><i class="fas fa-function"></i></a>';

            echo '<a class="secret" href="/links/cron__sync_algolia/in/' . $in['in_id'] . '" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Update Algolia Search Index" data-placement="bottom"><i class="fas fa-search"></i></a>';

            echo '</span>';


            echo '</div>';
            echo '</div>';

        }


        //Main intent:
        echo '<div class="list-group indent1">';
        echo echo_in($in, 1);
        echo '</div>';



        //Expand/Contract All buttons:
        $metadata = unserialize($in['in_metadata']);

        echo '<div class="indent2 intent-children-header">';

            echo '<h5 class="badge badge-h" style="display: inline-block;"><span class="li-children-count children-counter-' . $in['in_id'] . '">' . count($in['in__children']) . '</span> Children</h5>';

            echo '<div id="expand_intents" style="padding-left:8px; display:'. ( count($in['in__children']) > 0 ? 'inline-block' : 'none' ) .';">';
            echo '<i class="fas fa-plus-circle expand_all" style="font-size: 1.2em;" data-toggle="tooltip" title="Expand All Children" data-placement="top"></i> &nbsp;';
            echo '<i class="fas fa-minus-circle close_all" style="font-size: 1.2em;" data-toggle="tooltip" title="Contact All Children" data-placement="top"></i>';
            echo '<i class="far fa-filter toggle_filters '.advance_mode().'" style="font-size: 1.2em; margin-left: 6px;" data-toggle="tooltip" title="Apply Intent Filters" data-placement="top"></i>';
            echo '</div>';


            echo '<div class="inline-box in__filters '.( isset($_GET['filter_user']) || isset($_GET['filter_time']) ? '' : 'hidden' ).'">';
            echo '<form method="GET" action="" style="width: 100% !important;">';

            echo '<table><tr>';

            //Date Ranges:
            echo '<td style="padding-right: 10px;"><span class="mini-header">Filter Time</span><select name="filter_time" data-toggle="tooltip" title="Time Range" data-placement="top" class="form-control border">';
            foreach($in_filters['get_filter_options'] as $get_filter_option){
                echo '<option value="'.$get_filter_option['range_start'].' - '.$get_filter_option['range_end'].'" '.( isset($_GET['filter_time']) && $_GET['filter_time']==$get_filter_option['range_start'].'-'.$get_filter_option['range_end'] ? 'selected="selected"' : '' ).'>'.$get_filter_option['range_name'].'</option>';
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
        echo '<div class="list-group-item list_input grey-block '.advance_mode(in_trainer_class($in['in_id'])).'">
                    <div class="form-group is-empty" style="margin: 0; padding: 0;">
                        <input type="text"
                               class="form-control intentadder-level-2-child algolia_search"
                               maxlength="' . $this->config->item('in_outcome_max') . '"
                               intent-id="' . $in['in_id'] . '"
                               id="addintent-c-' . $in['in_id'] . '-0"
                               placeholder="+ Intent">
                    </div>
                   <div class="algolia_search_pad in_pad_bottom hidden"><span>Search existing intents or create a new one...</span></div>
            </div>';
        echo '</div>';

        ?>

    </div>


    <div class="<?= $this->config->item('css_column_2') ?>">
        <?php $this->load->view('view_trainer_app/in_right_column'); ?>
    </div>
</div>

<div style="height: 50px;">&nbsp;</div>