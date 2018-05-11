<?php 
//Calculate office hours:
$class_settings = $this->config->item('class_settings');
$week_count = ( $b['b_is_parent'] ? count($b['c__child_intents']) : 1 );
$child_name = ( $b['b_is_parent'] ? 'Week' : $this->lang->line('level_2_name') );
$udata = $this->session->userdata('user');

if($b['b_is_parent'] && count($b['c__child_intents'])>0){
    //Replace $b with the new aggregated $b
    $b = b_aggregate($b);
}
?>

<style>
    .msg { font-size:18px !important; font-weight:300 !important;}
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
        dateAndTime : "<?php echo date('Y/m/d' , time_format(strtotime('next monday'),3,-1)); ?> 23:59:59",
		size : "lg",
		regexpMatchFormat: "([0-9]{1,3}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
      		regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
	});
});

</script>


<h1 style="margin-bottom:30px;"><?= $b['c_outcome'] ?></h1>

<div class="row" id="landing_page">

	<div class="col-md-4">
        <div id="sidebar">
        	
        	<h3 style="margin-top:0;">Bootcamp Snapshot</h3>

            <ul style="list-style:none; margin-left:0; padding:5px 10px; background-color:#E5E5E5; border-radius:5px;">
                <li>Commitment: <b><?= format_hours($b['c__estimated_hours']) ?> in <?= $week_count.' Week'.show_s($week_count) ?></b></li>
                <li>Starts: <b>Every Monday</b></li>
                <li><?= ( echo_price($b,99, true) ? 'Tuition Range: <b>'.echo_price($b).' - '.echo_price($b,99).' <i class="fas fa-info-circle" data-toggle="tooltip" title="Tuition depends on the support level you choose"></i></b>' : 'Tuition: <b>'.echo_price($b).'</b>' ) ?></li>

                <?php
                if($b['b_difficulty_level']>0){
                    echo '<li>Experience Level: '.status_bible('df',$b['b_difficulty_level'],0,'top').'</li>';
                }
                ?>
            </ul>
            
            <div style="padding:10px 0 0; text-align:center;">
                <div class="lp_action"><a href="/<?= $b['b_url_key'] ?>/apply" class="btn btn-primary btn-round">Enroll&nbsp;<i class="fas fa-chevron-right"></i></a></div>
                <div class="btn btn-primary btn-round countdown"><div>NEXT CLASS IN:</div><span class="next_start_date"></span></div>
            </div>

        </div>
    </div>
    
    <div class="col-md-8">
    
        <?php
        foreach($b['c__messages'] as $i){
            if($i['i_status']==1){
                //Publish to Landing Page!
                echo echo_i($i);
            }
        }
        ?>


        <h3>Skills You Will Gain</h3>
        <div id="b_transformations"><?= ( strlen($b['b_transformations'])>0 ? '<ol><li>'.join('</li><li>',json_decode($b['b_transformations'])).'</li></ol>' : 'Not Set Yet' ) ?></div>

        <h3>Prerequisites</h3>
        <?php $pre_req_array = prep_prerequisites($b); ?>
        <div id="b_prerequisites"><?= ( count($pre_req_array)>0 /* Should always be true! */ ? '<ol><li>'.join('</li><li>',$pre_req_array).'</li></ol>' : 'None' ) ?></div>



        <h3>Action Plan</h3>
        <div id="c_tasks_list">
            <?php
            if($b['b_is_parent']){

                foreach($b['c__child_intents'] as $key=>$b7d){

                    echo '<div id="c_'.$key.'">';
                    echo '<h4><a href="javascript:toggleview(\'c_'.$key.'\');" style="font-weight: normal;"><i class="pointer fas fa-caret-right"></i> Week '.$b7d['cr_outbound_rank'].': '.$b7d['c_outcome'];
                    if($b7d['c__estimated_hours']>0){
                        echo ' &nbsp;<i class="fal fa-clock"></i> <span style="border-bottom:1px dotted #999;" data-toggle="tooltip" data-placement="top" title="This week is estimated to need '.format_hours($b7d['c__estimated_hours'],0).' to complete all Tasks">'.format_hours($b7d['c__estimated_hours'],1).'</span> &nbsp; ';
                    }
                    echo '</a></h4>';
                    echo '<div class="toggleview c_'.$key.'" style="display:none;">';
                    //Display all Active Tasks:
                    $intent_count = 0;
                    if(count($b7d['c__child_intents'])>0){
                        echo '<ul style="list-style:none; margin-left:-40px;">';
                        foreach($b7d['c__child_intents'] as $intent){
                            if($intent['c_status']<1){
                                continue; //Not published yet
                            }
                            $intent_count++;
                            echo '<li>Task '.$intent['cr_outbound_rank'].': '.$intent['c_outcome'].'</li>';
                        }
                        echo '</ul>';
                    }

                    if($b['child_bs'][$b7d['cr_outbound_b_id']]['b_status']==3){
                        //This is a Public Bootcamp, show link to Landing Page:
                        echo '<div class="title-sub">';
                        echo $this->lang->line('level_0_icon').' <a href="/'.$b['child_bs'][$b7d['cr_outbound_b_id']]['b_url_key'].'" style="border-bottom:1px dotted #999;" data-toggle="tooltip" data-placement="top" title="['.$b7d['c_outcome'].'] is also offered as a '.$this->lang->line('level_0_name').'">'.$this->lang->line('level_0_name').' &raquo;</a>';
                        echo '</div>';
                    }


                    echo '</div>';
                    echo '</div>';
                }

            } else {

                //Regular 7-day Bootcamp:
                echo '<div class="list-group maxout actionplan_list">';
                $counter = 0;
                foreach($b['c__child_intents'] as $child_intent){
                    if($child_intent['c_status']>=1){
                        if($counter==$class_settings['landing_page_visible']){
                            echo '<a href="javascript:void(0);" onclick="$(\'.show_full_list\').toggle();" class="show_full_list list-group-item"><i class="fas fa-plus-circle" style="margin: 0 4px 0 2px; color:#999;"></i> See All '.$child_name.'s</a>';
                        }
                        echo '<li class="list-group-item '.( $counter>=$class_settings['landing_page_visible'] ? 'show_full_list" style="display:none;"' : '"' ).'>';
                        //echo '<span class="pull-right">'.($child_intent['c__estimated_hours']>0 ? echo_time($child_intent['c__estimated_hours'],1) : '').'</span>';
                        echo ( $b['b_is_parent'] ? $this->lang->line('level_0_icon') : $this->lang->line('level_2_icon') ).' ';
                        echo $child_name.' '.$child_intent['cr_outbound_rank'].': '.$child_intent['c_outcome'];
                        echo '</li>';
                        $counter++;
                    }
                }
                echo '</div>';

            }

            ?>
        </div>
        <div class="show_full_list" style="display: none;"><a href="/<?= $b['b_url_key'] ?>/apply" class="btn btn-primary btn-round">ENROLL &nbsp;<i class="fas fa-chevron-right"></i></a></div>

    		
    		
    		<h3>Content By</h3>
    		<?php
            $admin_count = 0;
            $leader_fname = '';
            foreach($b['b__admins'] as $admin){
                if($admin['ba_team_display']!=='t'){
                    continue;
                }
                if($admin_count>0){
                    echo '<hr />';
                }
                
                if($admin['ba_status']==3){
                    $leader_fname = one_two_explode('', ' ', $admin['u_full_name']);
                }
                echo '<h4 class="userheader"><img src="'.$admin['u_image_url'].'" /> '.( $udata['u_inbound_u_id']==1281 ? ' <a href="/entities/'.$admin['u_id'].'/modify">'.$admin['u_full_name'].' <i class="fas fa-cog"></i></a>' : $admin['u_full_name'] ).'<span><img src="/img/flags/'.strtolower($admin['u_country_code']).'.png" class="flag" style="margin-top:-4px;" /> '.$admin['u_current_city'].'</span></h4>';
                echo '<p id="u_bio">'.$admin['u_bio'].'</p>';
                
                //Any languages other than English?
                if(strlen($admin['u_language'])>0 && $admin['u_language']!=='en'){
                    $all_languages = $this->config->item('languages');
                    //They know more than enligh!
                    $langs = explode(',',$admin['u_language']);
                    echo '<i class="fas fa-language ic-lrg"></i>Fluent in ';
                    $count = 0;
                    foreach($langs as $lang){
                        if($count>0){
                            echo ', ';
                        }
                        echo $all_languages[$lang];
                        $count++;
                    }
                }
                
                //Public profiles:
                echo '<div class="public-profiles" style="margin-top:10px;">';
                if(strlen($admin['u_primary_url'])>0){
                    echo '<a href="'.$admin['u_primary_url'].'" target="_blank"><i class="fas fa-link"></i></a>';
                }
                $u_social_account = $this->config->item('u_social_account');
                foreach($u_social_account as $sa_key=>$sa){
                    if(strlen($admin[$sa_key])>0){
                        echo '<a href="'.$sa['sa_prefix'].$admin[$sa_key].$sa['sa_postfix'].'" data-toggle="tooltip" title="'.$sa['sa_name'].'" target="_blank">'.$sa['sa_icon'].'</a>';
                    }
                }
                echo '</div>';
                
                $admin_count++;
            }
            ?>

            <hr />
            <p>Ready to unleash your full potential?</p>

    </div>
</div>



<div style="padding:20px 0 30px; text-align:center;">
    <div class="lp_action"><a href="/<?= $b['b_url_key'] ?>/apply" class="btn btn-primary btn-round">Enroll&nbsp;<i class="fas fa-chevron-right"></i></a></div>
    <div class="btn btn-primary btn-round countdown"><div>NEXT CLASS IN:</div><span class="next_start_date"></span></div>
</div>


</div>
</div>


<div>
<div class="container">
	
<?php $this->load->view('front/b/bs_include'); ?>
<br /><br />


