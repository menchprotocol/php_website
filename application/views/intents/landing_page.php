<style>
    .body-container .msg, .body-container li, p, .body-container a { font-size:1.1em !important; }
    .msg { margin-top:10px !important; font-weight:300 !important; line-height: 120% !important; }
    .msg a { max-width: none; }
    .tooltip-inner { max-width:350px !important; }
</style>

<script>
    function confirm_child_go(c_id){
        $('.alink-'+c_id).attr('href','javascript:void(0);');
        var c_outcome_parent = $('#title-parent').text();
        var c_outcome_child = $('#title-'+c_id).text();
        var r = confirm("Press OK to " +c_outcome_child+"\nPress CANCEL to "+c_outcome_parent);
        if (r == true) {
            //Go to target intent:
            window.location = "/"+c_id;
        }
    }
</script>

<div id="landing_page">

    <?php
    $need_grandpa = !( $c['c_id']==6623 );
    $grandpa_intent = null;
    $parent_intents = null;
    //Show all instant messages for this intent:
    foreach($c['c__inbounds'] as $ci){
        $parent_intents .= '<a class="list-group-item" href="/'.$ci['c_id'].'"><span class="badge badge-primary"><i class="fas fa-angle-left"></i></span> '.$ci['c_outcome'].'</a>';
        if($ci['c_id']==6623){
            //Already included:
            $need_grandpa = false;
        }
    }
    if($need_grandpa){
        //Fetch top intent and include it here:
        $gps = $this->Db_model->c_fetch(array(
            'c_id' => 6623,
        ));
        $grandpa_intent = '<a class="list-group-item" href="/'.$gps[0]['c_id'].'"><span class="badge badge-primary"><i class="fas fa-angle-left"></i></span> '.$gps[0]['c_outcome'].'</a>';
    }

    //Display generated parents:
    echo '<div class="list-group" style="margin-top: 10px;">';
    echo ( $need_grandpa ? $grandpa_intent : '' );
    echo $parent_intents;
    echo '</div>';



    //Intent Title:
    echo '<h1 style="margin-bottom:30px;" id="title-parent">'.$c['c_outcome'].'</h1>';


    //Show all instant messages for this intent:
    foreach($c['c__messages'] as $i){
        if($i['i_status']==1){
            //Publish to Landing Page!
            echo echo_i($i);
        }
    }
    ?>


    <div class="price-box">

        <div class="support_p">
            <?= echo_concepts($c, 0) ?>
            <?= echo_completion_estimate($c, 0) ?>
            <?= echo_costs($c, 0) ?>
            <?= echo_contents($c, 0) ?>
            <?= echo_experts($c, 0) ?>
        </div>

        <!-- Call to Action -->
        <a class="btn btn-primary" href="https://m.me/askmench?ref=SUBSCRIBE10_<?= $c['c_id'] ?>" style="display: inline-block; padding: 12px 36px;">Subscribe [Free] <i class="fas fa-angle-right"></i></a>


        <!-- Additional Notes/Details -->
        <div style="font-size:0.9em; padding:10px 0 0 3px; margin-bottom: 0;">
            <p style="line-height:130%; font-size:0.9em !important;"><span data-toggle="tooltip" title="Mench Personal Assistant is currently offered via Facebook Messenger. Think of it as an expert friend on a mission to get you hired!" data-placement="top" class="underdot">Requires Messenger</span> but <a href="https://newsroom.fb.com/news/2015/06/sign-up-for-messenger-without-a-facebook-account/" target="_blank" data-toggle="tooltip" title="You can use Facebook Messenger without having a Facebook account. Click to learn more." data-placement="top" class="underdot">Not Facebook</a></p>
            <!-- <p style="line-height:130%; font-size:0.9em !important;"><span data-toggle="tooltip" title="We're committed to keeping Mench Personal Assistant always free. In the future we plan to offer optional coaching packages for a more personalized experience" data-placement="top" class="underdot">Always Free</span> on <span data-toggle="tooltip" title="Install Facebook Messenger's iPhone/Android app or visit www.messenger.com on a PC" data-placement="top" class="underdot">Smartphones and PCs</span></p> -->
        </div>

    </div>


    <?php if(count($c['c__child_intents'])>0){ ?>

        <h3><?= ( $c['c_is_any'] ? 'Choose a Pathway:' : 'Action Plan' ) ?></h3>
        <div class="list-group actionplan_list">
        <?php
        $c1_counter = 0;
        $landing_pagetask_visible = 5;
        foreach($c['c__child_intents'] as $c1_counter=>$c1){

            //We need messages or children to expand this intent:
            $requies_expansion = ( count($c1['c__messages'])>0 || count($c1['c__child_intents'])>0 );

            echo '<li class="list-group-item" id="c__'.$c1_counter.'">';

                echo ($c1_counter+1).'. <'.( $requies_expansion ? 'a href="javascript:void(0)" onclick="$(\'.c_'.$c1_counter.'\').toggle();"' : 'span' ).' id="title-'.$c1['c_id'].'" style="font-weight: normal;">'.$c1['c_outcome'].'</'.( $requies_expansion ? 'a' : 'span' ).'>';


                echo '<span style="font-size:0.8em; font-weight:300; margin-left:5px; display:inline-block;">';
                echo ( $c1['c__tree_all_count']>0 ? '<span style="padding-right:5px;"><i class="fas fa-lightbulb-on"></i>'.$c1['c__tree_all_count'].'</span>' : '' );
                echo '<span><i class="fas fa-clock"></i>'.echo_hour_range($c1, true).'</span>';
                echo '</span>';


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

                    echo '<div style="margin:0 0 5px; padding-top:10px; font-size:1.1em;">'.$c1['c_outcome'].' with '.$c1['c__tree_all_count'].' concepts'.( count($c1['c__child_intents'])<$c1['c__tree_all_count'] ? ' in '.count($c1['c__child_intents']).' branches' : ''  ).':</div>';
                    echo '<ul style="list-style:none; margin-left:-30px; font-size:1em;">';
                    $landing_pagetask_visible += ( count($c1['c__child_intents'])==$landing_pagetask_visible+1 ? 1 : 0 );
                    foreach($c1['c__child_intents'] as $c2_counter=>$c2){

                        if($c2_counter==$landing_pagetask_visible){
                            echo '<a href="javascript:void(0);" onclick="$(\'.show_full_list_'.$c1_counter.'\').toggle();" class="show_full_list_'.$c1_counter.'">List all Concepts &raquo;</a>';
                        }
                        echo '<li class="'.( $c2_counter>=$landing_pagetask_visible ? 'show_full_list_'.$c1_counter.'" style="display:none;"' : '"' ).'>';
                        echo ($c1_counter+1).'.'.($c2_counter+1).'. '.$c2['c_outcome'];
                        /*
                        echo '<span style="font-size:0.8em; font-weight:300; margin-left:5px; display:inline-block;">';
                        echo ( $c2['c__tree_all_count']>0 ? '<span style="padding-right:5px;"><i class="fas fa-lightbulb-on"></i>'.($c2['c__tree_all_count']).'</span>' : '' );
                        echo '<span><i class="fas fa-clock"></i>'.echo_hour_range($c2, true).'</span>';
                        echo '</span>';
                        */
                        echo '</li>';
                    }
                    echo '</ul>';

                    //Since it has children, lets also give the option to navigate downwards:
                    echo '<div>You can choose to <a href="/'.$c1['c_id'].'" '.( $c['c_id']==6623 ? 'onclick="confirm_child_go('.$c1['c_id'].')"' : '' ).' class="alink-'.$c1['c_id'].'">subscribe to this concept only</a>.';
                    echo '</div>';

                }

                echo '</div>';
            echo '</li>';

        }
        ?>
        </div>
        <br />
    <?php } ?>
</div>



<div class="why_mench">

    <h3>Why Mench?</h3>

    <h4><span><i class="fas fa-heart"></i></span> A Job You'll LOVE</h4>
    <p>It's easy to get "a job" as a junior developer in today's automation-hungry economy. But finding your dream job with a superb team that is aligned with your goals and values is a much harder challenge that we'll help you overcome.</p>

    <h4><span><i class="fas fa-dollar-sign"></i></span> Make More Money</h4>
    <p>Every month of unemployment costs a junior developer $5,126 USD <a href="https://www.indeed.com/salaries/Junior-Developer-Salaries" target="_blank"><i class="fas fa-external-link-alt" style="color:#2f2739;"></i></a> of loss income. We help you land your dream job in the shortest time possible while also training you on salary negotiation techniques from industry experts.</p>

    <h4><span><i class="fas fa-calendar-check"></i></span> Flexible Scheduling</h4>
    <p>Choose the number of hours you can commit each week to <?= $c['c_outcome'] ?> and Mench will streamline your progress based on your availability. Go as fast or slow as you like to achieve the right balance for success.</p>

</div>


<div class="features" style="margin:55px 0 20px;">
    <p>Are you ready to <?= $c['c_outcome'] ?>?</p>
    <a class="btn btn-primary" href="https://m.me/askmench?ref=SUBSCRIBE10_<?= $c['c_id'] ?>" style="font-size: 1.3em;">Subscribe [Free] <i class="fas fa-angle-right"></i></a>
</div>

