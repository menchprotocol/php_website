<style>
    .body-container .msg, .body-container li, p, .body-container a { font-size:1.1em !important; }
    .msg { margin-top:10px !important; font-weight:300 !important; line-height: 120%; }
    .msg a { max-width: none; }
</style>

<script>

function toggleview(object_key){
	if($('#'+object_key+' .pointer').hasClass('fa-caret-right')){
		//Opening an item!
		//Make sure all other items are closed:
		$('.pointer').removeClass('fa-caret-down').addClass('fa-caret-right');
		$('.toggleview').hide();
		//Now show this item:
		$('#'+object_key+' .pointer').removeClass('fa-caret-right').addClass('fa-caret-down');
		$('.'+object_key).fadeIn();
		//Now adjust screen view port:
		$('html,body').animate({
			scrollTop: $('#'+object_key).offset().top - 65
		}, 150);
		
	} else if($('#'+object_key+' .pointer').hasClass('fa-caret-down')){
		//Close this specific item:
		$('#'+object_key+' .pointer').removeClass('fa-caret-down').addClass('fa-caret-right');
		$('.'+object_key).hide();
	}
}

</script>


<h1 style="margin-bottom:30px;"><?= $b['c_outcome'] ?></h1>

<div class="row" id="landing_page">
    
    <div class="col-md-8">
    
        <?php
        foreach($b['c__messages'] as $i){
            if($i['i_status']==1){
                //Publish to Landing Page!
                echo echo_i($i);
            }
        }
        ?>

        <h3><i class="fas fa-trophy"></i> Skills You Will Gain</h3>
        <div id="b_transformations"><?= ( strlen($b['b_transformations'])>0 ? '<ol><li>'.join('</li><li>',json_decode($b['b_transformations'])).'</li></ol>' : 'Not Set Yet' ) ?></div>



        <h3><i class="fas fa-shield-check"></i> Prerequisites</h3>
        <?php $pre_req_array = prep_prerequisites($b); ?>
        <div id="b_prerequisites"><?= ( count($pre_req_array)>0 /* Should always be true! */ ? '<ol><li>'.join('</li><li>',$pre_req_array).'</li></ol>' : 'None' ) ?></div>



        <h3><i class="fas fa-flag"></i> Action Plan</h3>
        <div id="c_tasks_list">
            <?php
            foreach($b['c__child_intents'] as $key=>$b7d){

                echo '<div id="c_'.$key.'">';
                echo '<h4><a href="javascript:toggleview(\'c_'.$key.'\');" style="font-weight: normal;"><i class="pointer fas fa-caret-right"></i> '.$b7d['c_outcome'];
                if($b7d['c__estimated_hours']>0){
                    echo ' &nbsp;<i class="fas fa-clock"></i> <span style="border-bottom:1px dotted #999;" data-toggle="tooltip" data-placement="top" title="This week is estimated to need '.echo_hours($b7d['c__estimated_hours'],0).' to complete all Tasks">'.echo_hours($b7d['c__estimated_hours'],1).'</span> &nbsp; ';
                }
                echo '</a></h4>';


                echo '<div class="toggleview c_'.$key.'" style="display:none;">';

                    //First show all messages for this Bootcamp:
                    foreach($b7d['c__messages'] as $i){
                        if($i['i_status']==1){
                            echo '<div class="tip_bubble">';
                            echo echo_i( array_merge( $i , array(
                                'noshow' => 1,
                                'e_b_id'=>$b['b_id'],
                            )) , 'Dear Student' ); //As they are a guest at this point
                            echo '</div>';
                        }
                    }


                    if(count($b7d['c__child_intents'])>0){
                        echo '<div class="list-group actionplan_list">';
                        $counter = 0;
                        $landing_pagetask_visible = 3;
                        foreach($b7d['c__child_intents'] as $child_intent){
                            if($child_intent['c_status']>0){
                                if($counter==$landing_pagetask_visible){
                                    echo '<a href="javascript:void(0);" onclick="$(\'.show_full_list_'.$key.'\').toggle();" class="show_full_list_'.$key.' list-group-item">See All <i class="fas fa-chevron-right"></i></a>';
                                }
                                echo '<li class="list-group-item '.( $counter>=$landing_pagetask_visible ? 'show_full_list_'.$key.'" style="display:none;"' : '"' ).'>';
                                //echo '<span class="pull-right">'.($child_intent['c__estimated_hours']>0 ? echo_estimated_time($child_intent['c__estimated_hours'],1) : '').'</span>';
                                echo '<i class="fas fa-badge-check"></i> ';
                                echo $child_intent['c_outcome'];
                                echo '</li>';
                                $counter++;
                            }
                        }
                        echo '</div>';
                    }

                echo '</div>';

                echo '</div>';
            }
            ?>
        </div>

        <br />
    </div>

    <div class="col-md-4">
        <div id="sidebar">

            <h3 style="margin-top:5px;"><i class="fas fa-comment-plus"></i> Subscribe</h3>

            <div class="price-box">

                <span id="p_name_1" style="padding-bottom:10px !important; display: block !important;"><?= $b['c_outcome'] ?> with:</span>

                <div class="support_p">
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-user-graduate"></i></span> 14 Industry Experts <a href="alert('show')"><u style="font-size:1em; margin-left:3px; display: inline-block;">See List</u></a></div>
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-lightbulb"></i></span> <?= $b['c__tree']['c__count'] ?> Key Concepts</div>
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-check-square"></i></span> <?= round($b['c__tree']['c__count']/2) ?> Actionable Tasks</div>
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-comment"></i></span> <?= $b['c__message_tree_count'] ?> Curated Messages</div>
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-clock"></i></span> <?= echo_hours(($b['c__estimated_hours']),false) ?> To Complete</div>
                </div>


                <div class="border" style="background-color: #FFF; padding: 6px 0 2px 6px;">


                    <div class="input-group" style="width:100%;">
                        <input style="padding-left:3px; margin-right:7px; width:100%;" type="email" data-lpignore="true" autocomplete="off" id="u_email" value="" class="form-control" placeholder="Email Address" />
                        <span class="input-group-addon hidden">
                            <a class="badge badge-primary" onclick="alert('start')" href="javascript:void(0);">Get Started</a>
                        </span>
                    </div>


                    <div class="input-group hidden" style="width:100%; margin-top:3px;">
                        <input style="padding-left:3px;" type="text" id="u_full_name" data-lpignore="true" autocomplete="off" value="" class="form-control" placeholder="Full Name" />
                    </div>
                    <div class="input-group" style="width:100%; margin-top:3px;">
                        <input style="padding-left:3px;" type="password" id="u_password" data-lpignore="true" autocomplete="off" value="" class="form-control" placeholder="Password" />
                    </div>
                    <div class="input-group hidden" style="width:100%; margin-top:3px;">
                        <input style="padding-left:3px;" type="password" id="u_password_repeat" data-lpignore="true" autocomplete="off" value="" class="form-control" placeholder="Repeat Password" />
                    </div>
                    <div class="input-group hidden" style="width:100%; margin:8px 0 5px;">
                        <a class="badge badge-primary" onclick="alert('create')" href="javascript:void(0);">Create Account & Login</a>
                    </div>

                    <div class="input-group" style="width:100%; margin:8px 0 5px;">
                        <a class="badge badge-primary" onclick="alert('login')" href="javascript:void(0);">Login</a>
                    </div>

                 </div>


                <div style="font-size:0.9em; padding:10px 0 0 3px; margin-bottom: 0;">
                    <p style="line-height:130%;"><b>7-Day free trial, then $7 per week</b>. <span style="display: inline-block;">No credit</span> card needed. Cancel anytime.</p>
                </div>
            </div>

        </div>
    </div>
</div>



</div>
</div>


<div class="main main-raised main-plain main-footer">
<div class="container">
	
<?php $this->load->view('front/b/bs_include'); ?>


