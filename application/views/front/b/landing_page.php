<?php 
//Calculate office hours:
$class_settings = $this->config->item('class_settings');
$child_name = ( $b['c_level'] ? 'Week' : $this->lang->line('level_2_name') );
$udata = $this->session->userdata('user');
$b = ( $b['c_level'] && count($b['c__child_intents'])>0 ? b_aggregate($b) : $b ); //Replace $b with the new aggregated $b
?>

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

$( document ).ready(function() {
    $(".next_start_date").countdowntimer({
        startDate : "<?php echo date('Y/m/d H:i:s'); ?>",
        dateAndTime : "<?php echo date('Y/m/d' , echo_time($next_classes[0]['r_start_date'],3,-1)); ?> 23:59:59",
        size : "lg",
        regexpMatchFormat: "([0-9]{1,3}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
        regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
    });
});

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
            if($b['c_level']){

                foreach($b['c__child_intents'] as $key=>$b7d){

                    echo '<div id="c_'.$key.'">';
                    echo '<h4><a href="javascript:toggleview(\'c_'.$key.'\');" style="font-weight: normal;"><i class="pointer fas fa-caret-right"></i> Week '.$b7d['cr_outbound_rank'].': '.$b7d['c_outcome'];
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


                        //Regular weekly Bootcamp:
                        echo '<div class="list-group actionplan_list">';
                        $counter = 0;
                        foreach($b7d['c__child_intents'] as $child_intent){
                            if($child_intent['c_status']>=1){
                                if($counter==$class_settings['landing_pagetask_visible']){
                                    echo '<a href="javascript:void(0);" onclick="$(\'.show_full_list_'.$key.'\').toggle();" class="show_full_list_'.$key.' list-group-item">Review All Tasks for This Week <i class="fas fa-chevron-right"></i></a>';
                                }
                                echo '<li class="list-group-item '.( $counter>=$class_settings['landing_pagetask_visible'] ? 'show_full_list_'.$key.'" style="display:none;"' : '"' ).'>';
                                //echo '<span class="pull-right">'.($child_intent['c__estimated_hours']>0 ? echo_estimated_time($child_intent['c__estimated_hours'],1) : '').'</span>';
                                echo $this->lang->line('level_2_icon').' ';
                                echo $child_intent['c_outcome'];
                                echo '</li>';
                                $counter++;
                            }
                        }
                        echo '</div>';
                    echo '</div>';




                    echo '</div>';
                }

            } else {

                //Regular weekly Bootcamp:
                echo '<div class="list-group actionplan_list">';
                $counter = 0;
                foreach($b['c__child_intents'] as $child_intent){
                    if($child_intent['c_status']>=1){
                        if($counter==$class_settings['landing_page_visible']){
                            echo '<a href="javascript:void(0);" onclick="$(\'.show_full_list\').toggle();" class="show_full_list list-group-item"><i class="fas fa-plus-circle" style="margin: 0 4px 0 2px; color:#999;"></i> See All '.$child_name.'s</a>';
                        }
                        echo '<li class="list-group-item '.( $counter>=$class_settings['landing_page_visible'] ? 'show_full_list" style="display:none;"' : '"' ).'>';
                        //echo '<span class="pull-right">'.($child_intent['c__estimated_hours']>0 ? echo_estimated_time($child_intent['c__estimated_hours'],1) : '').'</span>';
                        echo ( $b['c_level'] ? $this->lang->line('level_0_icon') : $this->lang->line('level_2_icon') ).' ';
                        echo $child_name.' '.$child_intent['cr_outbound_rank'].': '.$child_intent['c_outcome'];
                        echo '</li>';
                        $counter++;
                    }
                }
                echo '</div>';

            }
            ?>
        </div>

        <!--<div class="show_full_list" style="display: none;"><a href="<?= '/'.$b['b_url_key'].'/enroll' ?>" class="btn btn-primary btn-round">Enroll &nbsp;<i class="fas fa-chevron-right"></i></a></div>-->


        <?php
        if($b['b_offers_coaching']){
            echo '<h3><i class="fas fa-whistle"></i> Coaches</h3>';

            echo '<div class="row">';

            $count = 0;
            foreach($b['b__coaches'] as $coach){
                if(!$coach['u_booking_x_id']){
                    //Coach does not have their Booking ID, which is required to be listed:
                    continue;
                }

                if($count>0 && fmod($count,2)==0){
                    //A new row:
                    echo '</div><div class="row">';
                }
                echo_coach($coach, $b,1);
                $count++;
            }

            echo '</div>';
        }
        ?>

        <br />
    </div>

    <div class="col-md-4">
        <div id="sidebar">

            <h3 style="margin-top:5px;"><i class="fas fa-shopping-cart"></i> Enrollment</h3>

            <?php

            if($b['b_requires_assessment']){
                echo '<div class="price-box">';
                echo '<p><i class="fas fa-tachometer"></i> Bootcamp offers a free instant assessment by simply answering a few multiple-choice questions.</p>';
                //echo '<a href="/'.$b['b_url_key'].'/enroll?start=assessment" class="btn btn-primary" style="margin-top: 15px !important;">Start Free Assessment <i class="fas fa-chevron-right"></i></a>';
                echo '</div>';
            }

            echo_package($b,1,1);
            echo_package($b,0,1);

            ?>
            <div style="padding:0; text-align:center; width: 100%;">
                <div style="margin:0px 0 15px !important; width: 100%;" class="btn btn-primary btn-round countdown"><div>NEXT CLASS IN:</div><span class="next_start_date"></span></div>
            </div>

        </div>
    </div>
</div>



<!--<div style="padding:20px 0 30px; text-align:center;"><div class="lp_action"><a href="<?= '/'.$b['b_url_key'].'/enroll' ?>" class="btn btn-primary btn-round">Enroll &nbsp;<i class="fas fa-chevron-right"></i></a></div></div>-->


</div>
</div>


<div>
<div class="container">
	
<?php $this->load->view('front/b/bs_include'); ?>


