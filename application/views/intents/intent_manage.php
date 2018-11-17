<?php
$udata = $this->session->userdata('user');
if(isset($orphan_cs)){
    $c['c_id'] = 0;
}
?>

<script>
    //Define some global variables:
    var c_top_id = <?= $c['c_id'] ?>;
    var current_time = '<?= date("H:i") ?>';
    var c_outcome_max = <?= $this->config->item('c_outcome_max') ?>;
</script>
<script src="/js/custom/intent-manage-js.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>


<div class="row">
    <div class="col-xs-6 cols">
        <?php
        if(isset($orphan_cs)){

            echo '<div id="bootcamp-objective" class="list-group">';
            foreach($orphan_cs as $oc){
                echo echo_c($oc,1);
            }
            echo '</div>';

        } else {

            echo '<h5 class="badge badge-h"><i class="fas fa-sign-in-alt"></i> <span class="li-inbound-count inbound-counter-'.$c['c_id'].'">'.count($c__inbounds).'</span> Parents</h5>';

            if(count($c__inbounds)>0){
                echo '<div class="list-group list-level-2">';
                foreach($c__inbounds as $sub_intent){
                    echo echo_c($sub_intent, 2, 0, true);
                }
                echo '</div>';
            } else {
                echo '<div class="alert alert-info" role="alert" style="margin-top: 0;"><i class="fas fa-exclamation-triangle"></i> No inbound intents linked yet</div>';
            }



            echo '<h5 class="badge badge-h"><i class="fas fa-hashtag"></i> Intent</h5>';
            echo '<div id="bootcamp-objective" class="list-group">';
                echo echo_c($c,1);
            echo '</div>';








            //Expand/Contract buttons
            echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-sign-out-alt rotate90"></i> <span class="li-outbound-count outbound-counter-'.$c['c_id'].'">'.$c['c__tree_all_count'].'</span> Children</h5>';
            echo '<div id="task_view" style="padding-left:8px; display: inline-block;">';
            echo '<i class="fas fa-plus-square expand_all" style="font-size: 1.2em;"></i> &nbsp;';
            echo '<i class="fas fa-minus-square close_all" style="font-size: 1.2em;"></i>';
            echo '</div>';
            if($orphan_c_count>0){
                echo '<div style="padding-left:8px; display: inline-block;"><a href="/intents/orphan">'.$orphan_c_count.' Orphans &raquo;</a></div>';
            }

            echo '<div id="outs_error"></div>'; //Show potential errors detected in the Action Plan via our JS functions...

            echo '<div id="list-c-'.$c['c_id'].'" class="list-group list-is-outbound list-level-2">';
            foreach($c['c__child_intents'] as $sub_intent){
                echo echo_c($sub_intent, 2, $c['c_id']);
            }
            ?>
            <div class="list-group-item list_input grey-block">
                <div class="input-group">
                    <div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control intentadder-level-2 algolia_search bottom-add"  maxlength="<?= $this->config->item('c_outcome_max') ?>" intent-id="<?= $c['c_id'] ?>" id="addintent-c-<?= $c['c_id'] ?>" placeholder="Add #Intent"></div>
                    <span class="input-group-addon" style="padding-right:8px;">
                                        <span id="dir_handle" data-toggle="tooltip" title="or press ENTER ;)" data-placement="top" class="badge badge-primary pull-right" style="cursor:pointer; margin: 1px 3px 0 6px;">
                                            <div><i class="fas fa-plus"></i></div>
                                        </span>
                                    </span>
                </div>
            </div>
            <?php
            echo '</div>';




            //Intent subscribers:
            $limit = (is_dev() ? 10 : 100);
            $all_subscriptions = $this->Db_model->w_fetch(array(
                'w_c_id' => $c['c_id'],
            ), array('u','u_x','w_stats'), array(
                'w_id' => 'DESC',
            ), $limit);

            if(count($all_subscriptions)>0){
                //Show these subscriptions:
                echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-comment-plus"></i> '.count($all_subscriptions).($limit==count($all_subscriptions)?'+':'').' Subscriptions</h5>';
                echo '<div class="list-group list-grey" style="margin-bottom: 40px;">';
                foreach($all_subscriptions as $w){
                    echo echo_w_console($w);
                }
                echo '</div>';
            }

        }
        ?>

    </div>


    <div class="col-xs-6 cols">


        <div id="modifybox" class="fixed-box hidden" intent-id="0" intent-link-id="0" level="0">

            <h5 class="badge badge-h"><i class="fas fa-cog"></i> Modify Intent</h5>
            <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;"><a href="javascript:void(0)" onclick="$('#modifybox').addClass('hidden')"><i class="fas fa-times-circle"></i></a></div>

            <div class="grey-box">


                <div>
                    <div class="title"><h4><i class="fas fa-bullseye-arrow"></i> Target Outcome [<span style="margin:0 0 10px 0; font-size:0.8em;"><span id="charNameNum">0</span>/<?= $this->config->item('c_outcome_max') ?></span>] <span id="hb_598" class="help_button" intent-id="598"></span></h4></div>
                    <div class="help_body maxout" id="content_598"></div>

                    <div class="form-group label-floating is-empty">
                        <div class="input-group border">
                            <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;">To</span>
                            <input style="padding-left:0;" type="text" id="c_outcome" onkeyup="c_outcome_word_count()" maxlength="<?= $this->config->item('c_outcome_max') ?>" value="" class="form-control algolia_search">
                        </div>
                    </div>
                </div>



                <div class="title" style="margin-top:15px;"><h4><i class="fas fa-comment-edit"></i> Trigger Statements <span id="hb_7724" class="help_button" intent-id="7724"></span></h4></div>
                <div class="help_body maxout" id="content_7724"></div>
                <textarea class="form-control text-edit border msg" id="c_trigger_statements" style="height:86px; background-color:#FFFFFF !important;"></textarea>




                <div class="row">
                    <div class="col-md-6" style="margin-top:20px;">
                        <div class="title"><h4><i class="fas fa-sliders-h"></i> Status</h4></div>
                        <select class="form-control" id="c_status">
                        <?php
                        foreach(echo_status('c') as $c_status_id=>$c_status){
                            echo '<option value="'.$c_status_id.'" title="'.$c_status['s_desc'].'">'.$c_status['s_name'].'</option>';
                        }
                        ?>
                        </select>
                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                        <div class="title"><h4><i class="fas fa-weight"></i> Assessment Points</h4></div>
                        <select class="form-control" id="c_points">
                            <?php
                            foreach($this->config->item('c_point_options') as $point){
                                echo '<option value="'.$point.'">'.$point.' Point'.echo__s($point).'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>






                <div class="row">
                    <div class="col-md-6" style="margin-top:20px;">

                        <div class="title"><h4><i class="fas fa-shield-check"></i> Completion Settings</h4></div>
                        <div class="form-group label-floating is-empty">

                            <div class="radio" style="display:inline-block; border-bottom:1px dotted #999; margin-right:10px; margin-top: 0 !important;" data-toggle="tooltip" title="Intent is completed when ALL outbound intents are marked as complete" data-placement="right">
                                <label style="display:inline-block;">
                                    <input type="radio" id="c_is_any_0" name="c_is_any" value="0" />
                                    <i class="fas fa-sitemap"></i> All Children
                                </label>
                            </div>
                            <div class="radio" style="display: inline-block; border-bottom:1px dotted #999; margin-top: 0 !important;" data-toggle="tooltip" title="Intent is completed when ANY outbound intent is marked as complete" data-placement="right">
                                <label style="display:inline-block;">
                                    <input type="radio" id="c_is_any_1" name="c_is_any" value="1" />
                                    <i class="fas fa-code-merge"></i> Any Child
                                </label>
                            </div>

                        </div>

                        <div class="form-group label-floating is-empty completion-settings">
                            <div class="checkbox is_task">
                                <label style="display: block; font-size: 0.9em !important; margin-left:8px;"><input type="checkbox" id="c_require_notes_to_complete" /><i class="fas fa-pencil"></i> Require a written note</label>
                                <label style="display: block; font-size: 0.9em !important; margin-left:8px;"><input type="checkbox" id="c_require_url_to_complete" /><i class="fas fa-link"></i> Require URL in response</label>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                        <div class="title"><h4><i class="fas fa-box-check"></i> Completion Resources</h4></div>

                        <div class="form-group label-floating is-empty" style="max-width:150px;">
                            <div class="input-group border">
                                <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;"><i class="fas fa-clock"></i></span>
                                <input style="padding-left:0;" type="number" step="1" min="0" max="300" id="c_time_estimate" value="" class="form-control">
                                <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;">Minutes</span>
                            </div>
                        </div>
                        <div id="child-hours" style="margin-left:6px;"></div>

                        <div class="form-group label-floating is-empty" style="max-width:150px;">
                            <div class="input-group border">
                                <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;"><i class="fas fa-usd-circle"></i></span>
                                <input style="padding-left:0;" type="number" step="0.01" min="0" max="5000" id="c_cost_estimate" value="" class="form-control">
                                <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;">USD</span>
                            </div>
                        </div>
                    </div>
                </div>


                <table width="100%" style="margin-top:10px;">
                    <tr>
                        <td class="save-td"><a href="javascript:c_save_modify();" class="btn btn-primary">Save</a></td>
                        <td><span class="save_intent_changes"></span></td>
                        <td style="width:80px; text-align:right;">

                            <div><a href="javascript:c_unlink();" class="unlink-intent" data-toggle="tooltip" title="Only remove intent link while NOT deleting the intent itself" data-placement="left" style="text-decoration:none;"><i class="fas fa-unlink"></i> Unlink</a></div>

                            <?php if(array_key_exists(1281, $udata['u__inbounds'])){ ?>
                                <div><a href="javascript:c_delete();" data-toggle="tooltip" title="Delete intent AND remove all its links, messages & references" data-placement="left" style="text-decoration:none;"><i class="fas fa-trash-alt"></i> Delete</a></div>
                            <?php } ?>

                        </td>
                    </tr>
                </table>
            </div>

        </div>

        <?php $this->load->view('console/subscription_views'); ?>


    </div>
</div>