<style>
    .body-container .msg, .body-container li, p, .body-container a { font-size:1.1em !important; }
    .msg { margin-top:10px !important; font-weight:300 !important; line-height: 120% !important; }
    .msg a { max-width: none; }
</style>

<div class="row" id="landing_page">
    
    <div class="col-md-8">

        <h1 style="margin-bottom:30px;"><?= $c['c_outcome'] ?></h1>

        <?php
        foreach($c['c__messages'] as $i){
            if($i['i_status']==1){
                //Publish to Landing Page!
                echo echo_i($i);
            }
        }
        ?>


        <?php if(count($c['c__child_intents'])>0){ ?>
            
            
        <h3><i class="fas fa-flag"></i> Action Plan <span style="font-size:0.5em; display:inline-block; margin-left:5px; line-height:140%;"><i class="fas fa-lightbulb-on"></i> <?= $c['c__tree_all_count'] ?> Concepts <span style="display:inline-block;"><i class="fas fa-clock" style="padding-left: 2px;"></i> <?= echo_hours(($c['c__tree_max_hours']),false) ?></span></span></h3>





        <div class="list-group actionplan_list">
        <?php
        $c1_counter = 0;
        $landing_pagetask_visible = 5;
        foreach($c['c__child_intents'] as $c1_counter=>$c1){

            echo '<li class="list-group-item" id="c__'.$c1_counter.'">';
            echo '<a href="javascript:void(0)" onclick="$(\'.c_'.$c1_counter.'\').toggle();" style="font-weight: normal;">'.$c1['c_outcome'].' &raquo;</a>';
            echo '<span style="font-size:0.9em; font-weight:300; display:block;"><i class="fas fa-lightbulb-on"></i>'.$c1['c__tree_all_count'].' Concept'.echo__s($c1['c__tree_all_count']).' <i class="fas fa-clock"></i>'.echo_hours($c1['c__tree_max_hours'],0).'</span>';

                echo '<div class="c_'.$c1_counter.'" style="display:none; margin-left:3px; font-size:0.9em;">';

                //First show all messages for this intent:
                foreach($c1['c__messages'] as $i){
                    if($i['i_status']==1){
                        echo echo_i( array_merge( $i , array(
                            'noshow' => 1,
                        )) , 'Dear Student' ); //As they are a guest at this point
                    }
                }

                if(count($c1['c__child_intents'])>0){

                    echo '<div style="margin:0 0 5px; padding-top:10px; font-size:1.1em;">This concept covers:</div>';
                    echo '<ul style="list-style:decimal; margin-left:-15px; font-size:1em;">';
                    $landing_pagetask_visible += ( count($c1['c__child_intents'])==$landing_pagetask_visible+1 ? 1 : 0 );
                    foreach($c1['c__child_intents'] as $c2_counter=>$c2){

                        if($c2_counter==$landing_pagetask_visible){
                            echo '<a href="javascript:void(0);" onclick="$(\'.show_full_list_'.$c1_counter.'\').toggle();" class="show_full_list_'.$c1_counter.'">List all Concepts &raquo;</a>';
                        }
                        echo '<li class="'.( $c2_counter>=$landing_pagetask_visible ? 'show_full_list_'.$c1_counter.'" style="display:none;"' : '"' ).'>';
                        echo $c2['c_outcome'];
                        echo '<span style="font-size:0.8em; font-weight:300; margin-left:5px;"><i class="fas fa-lightbulb-on"></i>'.$c2['c__tree_all_count'].' &nbsp;<i class="fas fa-clock"></i>'.echo_hours($c2['c__tree_max_hours'],1).'</span>';
                        echo '</li>';
                    }
                    echo '</ul>';

                }

                echo '</div>';

            echo '</li>';

        }
        ?>
        </div>
        <br />
        <?php } ?>

    </div>

    <div class="col-md-4">
        <div id="sidebar">

            <h3 style="margin-top:30px;"><i class="fas fa-comment-plus"></i> Subscribe for Free</h3>

            <div class="price-box">

                <div class="support_p">
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-lightbulb-on"></i></span> <?= $c['c__tree_all_count'] ?> Concepts/Best-Practices</div>
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-clock"></i></span> <?= echo_hours(($c['c__tree_max_hours']),false) ?> To Complete</div>
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-user-graduate"></i></span> <span data-toggle="tooltip" title="We curated concepts and best practices from industry experts" data-placement="top" class="underdot">Trained with 14 Industry Expert</span></div>
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-comment"></i></span> <?= $c['c__tree_messages'] ?> Curated Messages/Insights</div>
                </div>


                <div class="border" style="background-color: #FFF; padding: 6px 0 2px 6px;">
                    <?php echo_subscribe_button($c['c_id']); ?>
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


