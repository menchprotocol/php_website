<style>
    .body-container .msg, .body-container li, p, .body-container a { font-size:1.1em !important; }
    .msg { margin-top:10px !important; font-weight:300 !important; line-height: 120% !important; }
    .msg a { max-width: none; }
    h3 { font-weight: bold; }
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
            
            
        <h3><i class="fas fa-flag" style="font-size: 0.7em;"></i> Action Plan</h3>
        <div style="font-size:0.85em; margin: 0px 0 12px 28px; line-height: 180%;">
            <span style="display:inline-block;width:123px;"><i class="fas fa-lightbulb-on"></i> <?= echo_concept($c) ?></span>
            <span style="display:inline-block;width:125px;"><i class="fas fa-clock" style="padding-left: 2px;"></i> <?= echo_hour_range($c) ?></span>

            <?php if($c['c__tree_max_cost']>0 && 0){ //Show the potential costs ?>
                <div class="dash-label"><span class="icon-left"><i class="fas fa-usd-circle"></i></span> <?= echo_cost_range($c) ?> in Purchases</div>

                <div class="dash-label"><span class="icon-left"><i class="fas fa-user-graduate"></i></span> <span data-toggle="tooltip" title="We curated concepts and best practices from industry experts" data-placement="top" class="underdot">14 Industry Experts</span></div>
            <?php } ?>
        </div>


        <div class="list-group actionplan_list">
        <?php
        $c1_counter = 0;
        $landing_pagetask_visible = 5;
        foreach($c['c__child_intents'] as $c1_counter=>$c1){

            echo '<li class="list-group-item" id="c__'.$c1_counter.'">';

            echo ($c1_counter+1).'. <a href="javascript:void(0)" onclick="$(\'.c_'.$c1_counter.'\').toggle();" style="font-weight: normal;">'.$c1['c_outcome'].'</a>';

            echo '<div style="font-size:0.8em; font-weight:300; padding-left: 15px; padding-top: 5px;">';
            echo ( $c1['c__tree_all_count']>1 ? '<span style="display:inline-block;width:127px;"><i class="fas fa-lightbulb-on"></i>'.echo_concept($c1).'</span>' : '' );
            echo '<span style="display:inline-block;width:125px;"><i class="fas fa-clock"></i>'.echo_hour_range($c1).'</span>';
            echo '</div>';

                echo '<div class="c_'.$c1_counter.'" style="display:none; margin-left:3px; font-size:0.9em; padding-left: 15px;">';

                //First show all messages for this intent:
                foreach($c1['c__messages'] as $i){
                    if($i['i_status']==1){
                        echo echo_i( array_merge( $i , array(
                            'noshow' => 1,
                        )) , 'Dear Student' ); //As they are a guest at this point
                    }
                }

                if(count($c1['c__child_intents'])>0){

                    echo '<div style="margin:0 0 5px; padding-top:10px; font-size:1.1em;">'.$c1['c_outcome'].' with '.($c1['c__tree_all_count']-1).' sub-concepts'.( count($c1['c__child_intents'])<($c1['c__tree_all_count']-1) ? ' across '.count($c1['c__child_intents']).' branches' : ''  ).':</div>';
                    echo '<ul style="list-style:none; margin-left:-30px; font-size:1em;">';
                    $landing_pagetask_visible += ( count($c1['c__child_intents'])==$landing_pagetask_visible+1 ? 1 : 0 );
                    foreach($c1['c__child_intents'] as $c2_counter=>$c2){

                        if($c2_counter==$landing_pagetask_visible){
                            echo '<a href="javascript:void(0);" onclick="$(\'.show_full_list_'.$c1_counter.'\').toggle();" class="show_full_list_'.$c1_counter.'">List all Concepts &raquo;</a>';
                        }
                        echo '<li class="'.( $c2_counter>=$landing_pagetask_visible ? 'show_full_list_'.$c1_counter.'" style="display:none;"' : '"' ).'>';
                        echo ($c1_counter+1).'.'.($c2_counter+1).'. '.$c2['c_outcome'];
                        echo '<span style="font-size:0.8em; font-weight:300; margin-left:5px; display:inline-block;">';
                        echo ( $c2['c__tree_all_count']>0 ? '<span style="padding-right:5px;"><i class="fas fa-lightbulb-on"></i>'.($c2['c__tree_all_count']).'</span>' : '' );
                        echo '<span><i class="fas fa-clock"></i>'.echo_hour_range($c2, true).'</span>';
                        echo '</span>';
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
            <h3 style="margin-top:30px;"><i class="fas fa-comment-plus" style="font-size: 0.8em;"></i> Subscribe for Free</h3>
            <div class="price-box">
                <div class="border" style="background-color: #FFF; padding: 6px 0 2px 6px; border: 1px solid #FFF !important;">

                    <?php $fb_settings = $this->config->item('fb_settings'); ?>

                    <div style="margin:30px auto; display:block; max-width:285px;">

                        <a class="btn btn-primary" href="https://m.me/askmench?ref=SUBSCRIBE10_<?= $c['c_id'] ?>">Get Started <i class="fas fa-angle-right"></i></a>
                        <div style="font-size:0.9em; padding:10px 0 0 3px; margin-bottom: 0;">
                            <p style="line-height:130%; font-size:0.9em !important;"><span data-toggle="tooltip" title="Mench Personal Assistant is currently offered via Facebook Messenger. Think of it as an expert friend on a mission to get you hired!" data-placement="top" class="underdot">Requires Messenger</span> but <a href="https://newsroom.fb.com/news/2015/06/sign-up-for-messenger-without-a-facebook-account/" target="_blank" data-toggle="tooltip" title="You can use Facebook Messenger without having a Facebook account. Click to learn more." data-placement="top" class="underdot">Not Facebook</a></p>
                            <p style="line-height:130%; font-size:0.9em !important;"><span data-toggle="tooltip" title="We're committed to keeping Mench Personal Assistant always free. In the future we plan to offer optional coaching packages for a more personalized experience" data-placement="top" class="underdot">Always Free</span> on <span data-toggle="tooltip" title="Install Facebook Messenger's iPhone/Android app or visit www.messenger.com on a PC" data-placement="top" class="underdot">Smartphones and PCs</span></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



</div>
</div>


<div class="main main-raised main-plain main-footer">
<div class="container">

    <h2 class="title" style="text-align:center; margin-top:0;">Why Mench?</h2>

    <div class="features text-center">

        <div class="row">

            <div class="col-md-4">
                <div class="info">
                    <div class="icon">
                        <i class="fas fa-heart" style="color:#2f2739;"></i>
                    </div>
                    <h3 class="info-title">A Job You'll Love</h3>
                    <p>It's easy to get "a job" as a junior developer in today's automation-hungry economy. But finding your dream job with a superb team that is aligned with your goals and values is a much harder challenge that we'll help you overcome.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info">
                    <div class="icon">
                        <i class="fas fa-dollar-sign" style="color:#2f2739;"></i>
                    </div>
                    <h3 class="info-title">Make More Money</h3>
                    <p>Every month of unemployment costs a junior developer $5,126 USD <a href="https://www.indeed.com/salaries/Junior-Developer-Salaries" target="_blank"><i class="fas fa-external-link-alt" style="color:#2f2739;"></i></a> of loss income. We help you land your dream job in the shortest time possible while also training you on salary negotiation techniques from industry experts.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info">
                    <div class="icon">
                        <i class="fas fa-calendar-check" style="color:#2f2739;"></i>
                    </div>
                    <h3 class="info-title">Flexible Scheduling</h3>
                    <p>Choose the number of hours you can commit each week to land your new job and Mench will streamline your progress based on your availability. Go as fast or slow as you like to achieve the right balance for success.</p>
                </div>
            </div>

        </div>


    </div>


    <div class="features text-center" style="margin:50px 0 20px;"><a class="btn btn-primary" href="https://m.me/askmench?ref=SUBSCRIBE10_<?= $c['c_id'] ?>" style="font-size: 1.3em;">Get Started [Free] <i class="fas fa-angle-right"></i></a></div>
