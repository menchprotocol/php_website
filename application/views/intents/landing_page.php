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


<h1 style="margin-bottom:30px;"><?= $c['c_outcome'] ?></h1>

<div class="row" id="landing_page">
    
    <div class="col-md-8">
    
        <?php
        foreach($c['c__messages'] as $i){
            if($i['i_status']==1){
                //Publish to Landing Page!
                echo echo_i($i);
            }
        }
        ?>


        <?php if(count($c['c__child_intents'])>0){ ?>
        <h3><i class="fas fa-flag"></i> Action Plan</h3>
        <div id="c_tasks_list">
            <?php
            foreach($c['c__child_intents'] as $key=>$c1){

                echo '<div id="c_'.$key.'">';
                echo '<h4><a href="javascript:toggleview(\'c_'.$key.'\');" style="font-weight: normal;"><i class="pointer fas fa-caret-right"></i> '.$c1['c_outcome'];
                if($c1['c__tree_hours']>0){
                    echo ' &nbsp;<i class="fas fa-clock"></i> <span style="border-bottom:1px dotted #999;" data-toggle="tooltip" data-placement="top" title="Estimated to take '.echo_hours($c1['c__tree_hours'],0).' to complete">'.echo_hours($c1['c__tree_hours'],1).'</span> &nbsp; ';
                }
                echo '</a></h4>';


                echo '<div class="toggleview c_'.$key.'" style="display:none;">';

                    //First show all messages for this intent:
                    foreach($c1['c__messages'] as $i){
                        if($i['i_status']==1){
                            echo '<div class="tip_bubble">';
                            echo echo_i( array_merge( $i , array(
                                'noshow' => 1,
                            )) , 'Dear Student' ); //As they are a guest at this point
                            echo '</div>';
                        }
                    }


                    if(count($c1['c__child_intents'])>0){
                        echo '<div class="list-group actionplan_list">';
                        $counter = 0;
                        $landing_pagetask_visible = 5;
                        foreach($c1['c__child_intents'] as $child_intent){
                            if($child_intent['c_status']>0){
                                if($counter==$landing_pagetask_visible){
                                    echo '<a href="javascript:void(0);" onclick="$(\'.show_full_list_'.$key.'\').toggle();" class="show_full_list_'.$key.' list-group-item">See All <i class="fas fa-chevron-right"></i></a>';
                                }
                                echo '<li class="list-group-item '.( $counter>=$landing_pagetask_visible ? 'show_full_list_'.$key.'" style="display:none;"' : '"' ).'>';
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
        <?php } ?>

    </div>

    <div class="col-md-4">
        <div id="sidebar">

            <h3 style="margin-top:5px;"><i class="fas fa-comment-plus"></i> Subscribe</h3>

            <div class="price-box">

                <span id="p_name_1" style="padding-bottom:10px !important; display: block !important;"><?= $c['c_outcome'] ?> with:</span>

                <div class="support_p">
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-user-graduate"></i></span> 14 Industry Experts</div>
                    <!-- <a href="alert('show')"><u style="font-size:1em; margin-left:3px; display: inline-block;">See List</u></a> -->
                    <?php if($c['c__tree_inputs']>0){ ?>
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-lightbulb-on"></i></span> <?= $c['c__tree_inputs'] ?> Key Concepts</div>
                    <?php } ?>

                    <?php if($c['c__tree_outputs']>0){ ?>
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-check-square"></i></span> <?= $c['c__tree_outputs'] ?> Actionable Tasks</div>
                    <?php } ?>

                    <div class="dash-label"><span class="icon-left"><i class="fas fa-comment"></i></span> <?= $c['c__tree_messages'] ?> Curated Messages</div>
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-clock"></i></span> <?= echo_hours(($c['c__tree_hours']),false) ?> To Complete</div>
                </div>


                <div class="border" style="background-color: #FFF; padding: 6px 0 2px 6px;">
                    <?php echo_support_chat($c['c_id']); ?>
                </div>
            </div>

        </div>
    </div>
</div>



</div>
</div>


<div class="main main-raised main-plain main-footer">
<div class="container">
	
<?php $this->load->view('front/shared/why_mench'); ?>


