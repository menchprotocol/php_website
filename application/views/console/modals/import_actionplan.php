<?php
$udata = $this->session->userdata('user');

//Fetch this user's Bootcamps:
$bs = $this->Db_model->instructor_bs(array(
    'ba.ba_outbound_u_id' => $udata['u_id'],
    'ba.ba_status >=' => 0,
    'b.b_status >=' => 2,
    'b.b_id !=' => $b['b_id'], //Can't import from current Bootcamp
));
?>

<script>
    var current_section = 1; //The index for the wizard
    var import_from_b_id = 0; //To be selected...

    function move_ui(adjustment){

        //Any pre-check with submitted data?
        var processing_error = null;

        //Let's check the value of the current posstible ID for input validation checking:
        if(adjustment>0 && typeof $('.wizard-box').eq((current_section-1)).attr( "id" ) !== 'undefined' && $('.wizard-box').eq((current_section-1)).attr( "id" ).length){
            var the_id = $('.wizard-box').eq((current_section-1)).attr( "id" );
            if(the_id=='choose_b'){
                //This is a critical step as it would define which Bootcamp to load into the Import wizard...
                var import_from_b_id = $('#import_b_id').val();
                if(import_from_b_id<1){
                    alert('ERROR: Choose a Bootcamp to import from');
                    $('#import_b_id').focus();
                    return false;
                } else {

                    //Load this Bootcamp into the Import Wizard

                    //Show loader:
                    $('#choose_content').html('<img src="/img/round_load.gif" class="loader" /> Loading Action Plan...');
                    //Hide Next Button:
                    $('#btn_next').hide();

                    //Import the content:
                    $.post("/api_v1/c_import_ui", {import_from_b_id:import_from_b_id}, function(data) {

                        if(data.status){
                            //Reload this script so the checkbox UI works:
                            $.getScript('/js/console/material-dashboard.js', function() {
                                //All good, load the UI:
                                //Append data to view:
                                $( "#choose_content" ).html(data.ui);
                                //Show Next button:
                                $('#btn_next').fadeIn();
                            });
                        } else {
                            //Show error:
                            $( "#choose_content" ).html('<b style="color:#FF0000;">ERROR: '+data.message+'</b><p>Refresh the page and try again.</p>');
                        }
                    });

                }
            } else if(the_id=='choose_content'){
                //Make sure at-least 1 item is selected to be imported:
                if(!($('.import_checkbox:checked').length>0)){
                    //Nothing was checked!
                    alert('ERROR: Choose at-least 1 item to import');
                    return false;
                } else if($("input:checkbox[name=b_outbound_c_ids]:checked").length>0 && 0){
                    //TODO Enable later when we have it...
                    //Show the Task modality selector:
                    $('#task_mode').removeClass('hidden');
                } else {
                    //hide Task modality selector:
                    $('#task_mode').addClass('hidden');
                }
            }
        }

        //Variables:
        var total_steps = $('.wizard-box').length;
        if(adjustment<0 && current_section==1){
            return false;
        } else if(adjustment>0 && current_section==total_steps){
            return false;
        }

        //We're all good, lets continue:
        current_section = current_section+adjustment;
        var progress = Math.round((current_section/total_steps*100));

        //UI Adjustment
        $('.wizard-box').hide();
        $('.wizard-box').eq((current_section-1)).fadeIn(function(){
            $( this ).find( "input" ).focus();
            $( this ).find( ".ql-editor" ).focus();
        });

        //Previous Button adjustments:
        if(current_section==1){
            $('#btn_prev').hide();
        } else {
            $('#btn_prev').show();
        }

        if(adjustment==0){
            //We're at the very first section, show the Next button:
            $('#btn_next').fadeIn();
        }

        //Update progress:
        $('.progress-bar').attr('aria-valuenow',progress).css('width',progress+'%');
        $('#step_progress').html(progress+'% Done');


        //Submit data only if last item:
        if(current_section==total_steps){

            //Hide both buttons:
            $('#btn_next, #btn_prev').hide();

            //Organize Import Intents:
            var b_outbound_c_ids = [];
            $("input:checkbox[name=b_outbound_c_ids]:checked").each(function(){
                b_outbound_c_ids.push(parseInt($(this).val()));
            });

            //Send for processing:
            $.post("/api_v1/c_import_process", {

                import_from_b_id:parseInt($('#import_b_id').val()),
                import_to_b_id:<?= $b['b_id'] ?>,
                b_outbound_c_ids:b_outbound_c_ids,
                task_import_mode:parseInt($('input[name=task_import_mode]:checked').val()),
                b_level_messages:($('input[name=b_level_messages]:checked').val()=='on'?1:0),
                b_prerequisites:($('input[name=b_prerequisites]:checked').val()=='on'?1:0),
                b_transformations:($('input[name=b_transformations]:checked').val()=='on'?1:0),

            }, function(data) {
                //Append data to view:
                if(data.status){

                    //Show messages:
                    $( "#import_result" ).html(data.message).hide().fadeIn();

                    //Refresh after success:
                    setTimeout(function() {
                        window.location = "/console/<?= $b['b_id'] ?>/actionplan"
                    }, 1500);

                } else {

                    //Show error:
                    $( "#import_result" ).html('<b style="color:#FF0000;">ERROR: '+data.message+'</b>').hide().fadeIn();

                }
            });
        }
    }

    //$(window).on('load',function(){ $('#importActionPlan').modal('show'); });

    $(document).ready(function() {
        $('#importActionPlan').on('shown.bs.modal', function () {
            //Update progress bar:
            move_ui(0);
        });
    });
</script>



<style>
    .wizard-box * { line-height:110%; }
    .wizard-box { font-size:1.2em; }
    .wizard-box label { font-size:0.8em; }
    .wizard-box p, .wizard-box ul { margin-bottom:20px; }
    .wizard-box ul li { margin-bottom:10px; }
    .wizard-box a { text-decoration:underline; }
    .wizard-box h4 { margin:0 0 5px 0; padding:0; font-size:1.1em; }
    .wizard-box h5 { margin-top:20px; }
    .wizard-box .form-group { margin-left:20px; }
    .aligned-list>li>i { width:36px; display:inline-block; text-align:center; }
    .radio label p {font-style:normal; font-weight: 300; font-size:1.1em;}
    .large-fa {font-size: 60px; margin-top:15px;}
    .xlarge-fa {font-size: 68px; margin-top:15px;}
</style>


<div class="modal fade" id="importActionPlan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title">Action Plan Import Wizard</h3>
            </div>
            <div class="modal-body" style="min-height:300px;">

                <div class="wizard-box" id="choose_b">
                    <p>Import from:</p>
                    <div class="form-group label-floating is-empty">
                        <select class="form-control input-mini border" id="import_b_id">
                            <option value="0">Choose Bootcamp...</option>
                            <?php
                            foreach($bs as $this_b){
                                echo '<option value="'.$this_b['b_id'].'">'.( $this_b['b_old_format'] ? '[OLD FORMAT] ' : '' ).$this_b['c_objective'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <br />
                    <p>Import to this Bootcamp:</p>
                    <div class="form-group"><b><?= $b['c_objective'] ?></b></div>
                </div>

                <!-- Content to be dynamically loaded based on Bootcamp -->
                <div class="wizard-box" id="choose_content"></div>

                <div class="wizard-box" id="import_mode">


                    <div id="task_mode" class="hidden">
                        <h4 style="margin-bottom:20px;"><i class="fa fa-check-square-o" aria-hidden="true"></i> Task Import Mode</h4>
                        <p><i class="fa fa-link" aria-hidden="true"></i> <b>Link:</b> New copy is linked to original item. Settings & Messages are mirrored and would remain in-sync if edited from either Action Plan.</p>
                        <p><i class="fa fa-clone" aria-hidden="true"></i> <b>Copy:</b> A copy is made. Changes to Settings & Messages of either copy would not affect the other copy as they are independent from one another.</p>
                        <div class="radio">
                            <label>
                                <input type="radio" name="task_import_mode" value="1" disabled />
                                <i class="fa fa-link" aria-hidden="true"></i> Link Tasks <i class="fa fa-link" aria-hidden="true"></i> Link Steps <b class="badge">UPCOMING</b>
                                <p>Keeps all Tasks in-sync. Changes made to the Settings/Messages of Tasks and Steps would be synced.</p>
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="task_import_mode" value="2" disabled />
                                <i class="fa fa-clone" aria-hidden="true"></i> Copy Tasks <i class="fa fa-link" aria-hidden="true"></i> Link Steps <b class="badge">UPCOMING</b>
                                <p>Ideal for re-structuring Tasks by making your Action Plan longer or shorter. Settings/Messages for Tasks would be independent while Settings/Messages for Steps would be synced.</p>
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="task_import_mode" value="3" checked="true" />
                                <i class="fa fa-clone" aria-hidden="true"></i> Copy Tasks <i class="fa fa-clone" aria-hidden="true"></i> Copy Steps
                                <p>A fresh copy is made for both Tasks and Steps, and their Settings/Messages would be independent.</p>
                            </label>
                        </div>
                        <br />
                    </div>


                    <div>
                        <p>Pressing "Next" would start importing your selection.</p>
                    </div>
                </div>

                <div class="wizard-box">
                    <p style="text-align:center;"><b>Importing Action Plan...</b></p>
                    <br />
                    <div id="import_result" style="text-align:center; height:200px;"><img src="/img/round_load.gif" class="loader" /></div>
                </div>

            </div>
            <div class="modal-footer" style="text-align:left;">
                <a id="btn_prev" href="javascript:move_ui(-1)" class="btn btn-primary" style="padding-left:10px;padding-right:12px; display:none;"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
                <span id="btn_next"><a href="javascript:move_ui(1)" class="btn btn-primary">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a></span>

                <div style="text-align:right; margin:-30px 2px 0;"><b id="step_progress"></b></div>
                <div class="progress" style="margin:auto 2px;">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
