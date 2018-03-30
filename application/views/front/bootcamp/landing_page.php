<?php 
//Calculate office hours:
$class_settings = $this->config->item('class_settings');
$instructor_has_off = ($b['b_p2_max_seats']>0 && strlen($b['b__admins'][0]['u_weeks_off'])>0);
$classroom_closed = ($instructor_has_off && in_array($focus_class['r_start_date'],unserialize($b['b__admins'][0]['u_weeks_off'])));
$highest_price = echo_price($b,99);
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
	$("#reg1, #reg2, #reg3").countdowntimer({
		startDate : "<?php echo date('Y/m/d H:i:s'); ?>",
        dateAndTime : "<?php echo date('Y/m/d' , time_format($focus_class['r_start_date'],3,-1)); ?> 23:59:59",
		size : "lg",
		regexpMatchFormat: "([0-9]{1,3}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
      		regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
	});
});

</script>


<h1 style="margin-bottom:30px;"><?= $b['c_objective'] ?></h1>

<div class="row" id="landing_page">

	<div class="col-md-4">
        <div id="sidebar">
        	
        	<h3 style="margin-top:0;">Bootcamp Snapshot</h3>

            <ul style="list-style:none; margin-left:0; padding:5px 10px; background-color:#EFEFEF; border-radius:5px;">
                <li>Duration: <b>1 Week</b></li>
                <li>Dates: <b><?= time_format($focus_class['r_start_date'],2).' - '.time_format($focus_class['r__class_end_time'],2) ?></b></li>
                <li>Commitment: <b><?= echo_hours($b['c__estimated_hours']/7) ?> Per Day</b></li>
                <li>Price Range: <b><?= echo_price($b).( $highest_price ? ' - '.$highest_price.' <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Price depends on the support level you choose when joining this Class"></i>' : '' ) ?></b></li>
                <?php
                if($b['b_difficulty_level']>0){
                    echo '<li>Difficulty Level: '.status_bible('df',$b['b_difficulty_level'],0,'top').'</li>';
                }
                ?>
            </ul>
            
            <div style="padding:10px 0 0; text-align:center;">
                <div class="btn btn-primary btn-round countdown"><span id="reg1"></span></div>
                <div><a href="/<?= $b['b_url_key'] ?>/apply/<?= $focus_class['r_id'] ?>" class="btn btn-primary btn-round">Join Class of <u><?= time_format($focus_class['r_start_date'],4) ?></u> &nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>
            </div>


            <?php
            if($classroom_closed){
                //Classroom is closed
                echo '<div class="alert alert-info" role="alert" style="margin-top:20px; border-radius:5px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Note: Classroom is closed this week but you can still join and '.status_bible('rs',1).'</div>';
            }
            ?>


            <h3>Upcoming Classes</h3>
            <div id="class_list" class="list-group" style="max-width:none !important;">
                <?php
                $counter = 0;
                foreach($classes as $class){

                    if($counter==$class_settings['landing_page_visible']){
                        echo '<a href="javascript:void(0);" onclick="$(\'.show_all_classes\').toggle();" class="show_all_classes list-group-item"><i class="fa fa-plus-square" aria-hidden="true" style="margin: 0 4px 0 2px; color:#999;"></i> See More Classes</a>';
                    }

                    if($class['r_id']==$focus_class['r_id']){
                        echo '<li class="list-group-item grey">';
                        echo '<span class="pull-right"><span class="label label-default grey" style="color:#000;">CURRENTLY VIEWING</span></span>';
                    } else {
                        echo '<a href="/'.$b['b_url_key'].'/'.$class['r_id'].'" class="list-group-item '.( $counter>=$class_settings['landing_page_visible'] ? 'show_all_classes" style="display:none;"' : '"' ).' >';
                        echo '<span class="pull-right"><span class="badge badge-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
                    }

                    echo '<i class="fa fa-calendar" aria-hidden="true"></i> <b>'.time_format($class['r_start_date'],2).'</b>';
                    if($instructor_has_off && in_array($class['r_start_date'],unserialize($b['b__admins'][0]['u_weeks_off']))){
                        //Classroom is closed
                        echo '<span class="badge badge-primary grey">'.status_bible('rs',1,1,'top').'</span>';
                    }

                    echo ( $class['r_id']==$focus_class['r_id'] ? '</li>' : '</a>' );
                    $counter++;
                }
                ?>
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
        <div id="c_goals_list">
        <?php

        echo '<div class="list-group maxout">';
        $counter = 0;
        foreach($b['c__child_intents'] as $task){
            if($task['c_status']>=1){
                if($counter==$class_settings['landing_page_visible']){
                    echo '<a href="javascript:void(0);" onclick="$(\'.show_all_tasks\').toggle();" class="show_all_tasks list-group-item"><i class="fa fa-plus-square" aria-hidden="true" style="margin: 0 4px 0 2px; color:#999;"></i> See All Tasks</a>';
                }
                echo '<li class="list-group-item '.( $counter>=$class_settings['landing_page_visible'] ? 'show_all_tasks" style="display:none;"' : '"' ).'>';
                //echo '<span class="pull-right">'.($task['c__estimated_hours']>0 ? echo_time($task['c__estimated_hours'],1) : '').'</span>';
                echo '<i class="fa fa-check-square" aria-hidden="true" style="margin: 0 4px 0 2px; color:#000;"></i> ';
                echo 'Task '.$task['cr_outbound_rank'].': '.$task['c_objective'];
                echo '</li>';
                $counter++;
            }
        }
        echo '</div>';
        //echo '<p>To complete this Bootcamp, you should complete the above '.$counter.' Task'.show_s($counter).' anytime during the weekly Bootcamp window.</p>';
        ?>
        </div>
        <div class="show_all_tasks" style="display: none;"><a href="/<?= $b['b_url_key'] ?>/apply/<?= $focus_class['r_id'] ?>" class="btn btn-primary btn-round">GET STARTED ON <u><?= time_format($focus_class['r_start_date'],4) ?></u>&nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>

    		
    		
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
                    $leader_fname = $admin['u_fname'];
                }
                echo '<h4 class="userheader"><img src="'.$admin['u_image_url'].'" /> '.$admin['u_fname'].' '.$admin['u_lname'].'<span><img src="/img/flags/'.strtolower($admin['u_country_code']).'.png" class="flag" style="margin-top:-4px;" /> '.$admin['u_current_city'].'</span></h4>';
                echo '<p id="u_bio">'.$admin['u_bio'].'</p>';
                
                //Any languages other than English?
                if(strlen($admin['u_language'])>0 && $admin['u_language']!=='en'){
                    $all_languages = $this->config->item('languages');
                    //They know more than enligh!
                    $langs = explode(',',$admin['u_language']);
                    echo '<i class="fa fa-language ic-lrg" aria-hidden="true"></i>Fluent in ';
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
                if(strlen($admin['u_website_url'])>0){
                    echo '<a href="'.$admin['u_website_url'].'" data-toggle="tooltip" title="Visit Website" target="_blank"><i class="fa fa-chrome" aria-hidden="true"></i></a>';
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
            <p>Class starts in:</p>
    		
    </div>
</div>


<div style="padding:20px 0 30px; text-align:center;">
	<div class="btn btn-primary btn-round countdown"><span id="reg3"></span></div>
    <br />
    <a href="/<?= $b['b_url_key'] ?>/apply/<?= $focus_class['r_id'] ?>" class="btn btn-primary btn-round">Join Class of <u><?= time_format($focus_class['r_start_date'],4) ?></u> &nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></a>
</div>


</div>
</div>


<div>
<div class="container">
	
<?php $this->load->view('front/shared/bootcamps_include'); ?>
<br /><br />


