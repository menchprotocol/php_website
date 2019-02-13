<?php
//Prepare some handy variables:
$metadata = unserialize($in['in_metadata']);
$expand_mode = ( isset($_GET['expand_mode']) && intval($_GET['expand_mode']) );
$is_primary_in = ( $in['in_id'] == $this->config->item('in_tactic_id') );
$hide_subscribe = ( isset($_GET['hide_subscribe']) && intval($_GET['hide_subscribe']) );
?>
<style>
    .body-container .msg, .body-container li, p, .body-container a {
        font-size: 1.1em !important;
    }

    .msg {
        margin-top: 10px !important;
        font-weight: 300 !important;
        line-height: 120% !important;
    }

    .msg a {
        max-width: none;
    }

    .tooltip-inner {
        max-width: 350px !important;
    }
</style>


<script>
    function confirm_child_go(in_id) {
        $('.alink-' + in_id).attr('href', 'javascript:void(0);');
        var in_outcome_parent = $('#title-parent').text();
        var in_outcome_child = $('#title-' + in_id).text();
        var r = confirm("Press OK to ONLY " + in_outcome_child + "\nPress CANCEL to " + in_outcome_parent);
        if (r == true) {
            //Go to target intent:
            window.location = "/" + in_id;
        }
    }
</script>


<div id="in_landing_page">

    <?php
    if (!$is_primary_in && count($in['in__parents']) > 0) {

        //Fetch Parent Intents:
        $parent_ins = null;

        //Show all parent intents for this intent:
        foreach ($in['in__parents'] as $in_parent) {
            if($in_parent['in_status'] >= 2){
                $parent_ins .= '<a class="list-group-item" href="/' . $in_parent['in_id'] . '"><span class="badge badge-primary"><i class="fas fa-angle-left"></i></span> ' . $in_parent['in_outcome'] . '</a>';
            }
        }

        if($parent_ins){
            //Display generated parents:
            echo '<div class="list-group" style="margin-top: 10px;">';
            echo $parent_ins;
            echo '</div>';
        }
    }

    //Intent Title:
    echo '<h1 style="margin-bottom:30px;" id="title-parent">' . $in['in_outcome'] . '</h1>';


    //Fetch & Display On-Start Messages for this intent:
    foreach ($this->Database_model->fn___tr_fetch(array(
        'tr_status >=' => 2, //Published+
        'tr_type_en_id' => 4231, //On-Start Messages
        'tr_in_child_id' => $in['in_id'],
    ), array(), 0, 0, array('tr_order' => 'ASC')) as $tr) {
        echo $this->Chat_model->fn___dispatch_message($tr['tr_content']);
    }
    ?>



    <br/>

    <?php if (!$hide_subscribe) { ?>
    <h3 style="margin-top:0px !important;">Overview:</h3>
    <div style="margin:12px 0 0 5px;">
        <?= fn___echo_in_overview           ($in, false, $expand_mode) ?>
        <?= fn___echo_in_referenced_content ($in, false, $expand_mode) ?>
        <?= fn___echo_in_experts            ($in, false, $expand_mode) ?>
        <?= fn___echo_in_time_estimate      ($in, false, $expand_mode) ?>
        <?= fn___echo_in_cost_range         ($in, false, $expand_mode) ?>
    </div>
    <?php } ?>



    <?php if (count($in['in__children']) > 0) { ?>

        <?php if (!$hide_subscribe) { ?>
        <h3>Action Plan:</h3>
        <?php } ?>

        <div class="list-group actionplan_list" style="margin:12px 0 0 5px;">
            <?php
            $in_level2_counter = 0;
            foreach ($in['in__children'] as $in_level2) {

                //Do not show if conditional:
                if($in_level2['tr_type_en_id'] == 4229){
                    continue;
                }

                echo '<div class="panel-group" id="open' . $in_level2_counter . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $in_level2_counter . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $in_level2_counter . '" href="#collapse' . $in_level2_counter . '" aria-expanded="'.( $expand_mode ? 'true' : 'false' ).'" aria-controls="collapse' . $in_level2_counter . '">
                       ' . ($in['in_is_any'] ? 'Option '.($in_level2_counter + 1).':' : '#'.($in_level2_counter + 1)) . ' <span id="title-' . $in_level2['in_id'] . '">' . $in_level2['in_outcome'] . '</span>';

                    //Show time if we have it:
                    if (isset($metadata['in__tree_max_seconds']) && $metadata['in__tree_max_seconds'] > 0) {
                        echo ' <span style="font-size: 0.9em; font-weight: 300;"><i class="fal fa-clock"></i> '. fn___echo_time_range($in_level2, true).'</span>';
                    }

                    echo '<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $in_level2_counter . '" class="panel-collapse collapse '.( $expand_mode ? 'in' : 'out' ).'" role="tabpanel" aria-labelledby="heading' . $in_level2_counter . '">
                <div class="panel-body" style="padding:5px 0 0 5px;">';


                //Fetch & Display On-Start Messages for this intent:
                foreach ($this->Database_model->fn___tr_fetch(array(
                    'tr_status >=' => 2, //Published+
                    'tr_type_en_id' => 4231, //On-Start Messages
                    'tr_in_child_id' => $in_level2['in_id'],
                ), array(), 0, 0, array('tr_order' => 'ASC')) as $tr) {
                    echo $this->Chat_model->fn___dispatch_message($tr['tr_content']);
                }

                if (count($in_level2['in__grandchildren']) > 0) {

                    $in_level3_counter = 0;
                    echo '<ul style="list-style:none; margin-left:-30px; font-size:1em;">';
                    foreach ($in_level2['in__grandchildren'] as $in_level3) {

                        //Do not show if conditional:
                        if($in_level3['tr_type_en_id'] == 4229){
                            continue;
                        }

                        echo '<li>#' . ($in_level2_counter + 1) . '.' . ($in_level3_counter + 1) . ' ' . $in_level3['in_outcome'];

                        //Show time if we have it:
                        $metadata3 = unserialize($in_level3['in_metadata']);
                        if (isset($metadata3['in__tree_max_seconds']) && $metadata3['in__tree_max_seconds'] > 0) {
                            echo ' <span style="font-size: 0.9em; font-weight: 300;"><i class="fal fa-clock"></i> '. fn___echo_time_range($in_level3, true).'</span>';
                        }

                        echo '</li>';

                        //Increase counter:
                        $in_level3_counter++;
                    }
                    echo '</ul>';

                    //Since it has children, lets also give the option to navigate downwards ONLY IF...
                    if ($in_level2['in_status'] >= 2 && !$expand_mode) {
                        echo '<div>You can choose to <a href="/' . $in_level2['in_id'] . '" ' . ( $is_primary_in ? 'onclick="confirm_child_go(' . $in_level2['in_id'] . ')"' : '') . ' class="alink-' . $in_level2['in_id'] . '" style="text-decoration:underline;">subscribe to this part only</a>.</div>';
                    }

                }

                echo '</div>
            </div>
        </div></div>';

                //Increase counter:
                $in_level2_counter++;

            }
            ?>
        </div>
        <br/>
    <?php } ?>


    <?php if(!$hide_subscribe){ ?>

    <p style="padding:5px 0 0 0;">Ready to <?= $in['in_outcome'] ?>?</p>

    <!-- Call to Actions -->
    <a class="btn btn-primary" href="https://m.me/askmench?ref=<?= $in['in_id'] ?>"
       style="display: inline-block; padding: 12px 36px;">Get Started [Free] <i class="fas fa-angle-right"></i></a>

    <div>
        <i class="fal fa-plus-circle learn_more_toggle"></i><i class="fal fa-minus-circle learn_more_toggle hidden"></i> <a href="#learnMore" onclick="$('.learn_more_toggle').toggleClass('hidden');" style="text-decoration:underline; display: inline-block;">Learn more</a>
        about Mench or
        <a href="/<?= $this->config->item('in_miner_start_id') ?>" style="text-decoration:underline;  display: inline-block;">contribute &raquo;</a></div>

    <?php } ?>


</div>


<a name="learnMore"></a>
<div class="learn_more_toggle hidden inline-box">

    <h3 style="margin-top:20px !important;">Advance Your Tech Career:</h3>
    <div style="margin:12px 0 0 5px;">

        <?php
        //Print 3 more menu items:
        $id = 'JobYouWillLove';
        echo '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">😍</i> Land a Job You\'ll LOVE<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">It\'s easy to get "a job" as a junior developer in today\'s automation-hungry economy. But finding your dream job with a superb team that is aligned with your goals and values is a much harder challenge that we\'ll help you overcome.</div>
            </div>
        </div></div>';


        $id = 'MakeMoreFaster';
        echo '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">💵</i> Make More Faster<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">Every month of unemployment costs a junior developer an average of $5,126 USD of loss income. We help you land your dream job in the shortest time possible while also training you on salary negotiation techniques from industry experts.</div>
            </div>
        </div></div>';


        $id = 'FlexibleHours';
        echo '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">⏰</i> Flexible Hours<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">Choose the number of hours you can commit each week to ' . $in['in_outcome'] . ' and Mench will streamline your progress based on your availability. Go as fast or slow as you like to achieve the right balance for success.</div>
            </div>
        </div></div>';

        ?>
    </div>


    <h3 style="margin-top: 30px !important;">How it Works:</h3>
    <div style="margin:12px 0 0 5px;">

        <?php
        //Print 3 more menu items:
        $id = 'Step1';
        echo '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">1️⃣</i> Connect with Mench on Messenger <i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">Mench Personal Assistant works on Facebook Messenger. Think of it as an expert friend on a mission to ' . $this->config->item('in_strategy_name') . '! If you do not have (or want to have) a Facebook account, you can easily use Facebook Messenger <a href="https://newsroom.fb.com/news/2015/06/sign-up-for-messenger-without-a-facebook-account/" target="_blank" style="text-decoration: underline;">without a Facebook account</a>.</div>
            </div>
        </div></div>';


        $id = 'Step2';
        echo '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">2️⃣</i> Add this intention to your Action Plan <i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">The first question that Mench will ask you is to confirm if you are interested to ' . $in['in_outcome'] . '. Answering Yes will add this intention to your Action Plan so Mench can help you accomplish it.</div>
            </div>
        </div></div>';


        $id = 'Step3';
        echo '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">3️⃣</i> Continue the Conversation <i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">Mench will continue the conversation and provide you with a step by step Action Plan that helps you ' . $in['in_outcome'] . '.</div>
            </div>
        </div></div>';

        ?>
    </div>


</div>


<?php
//Display other featured intents:
$featured_ins = $ins = $this->Database_model->fn___in_fetch(array(
    'in_status' => 3, //Featured Intents
    'in_id !=' => $in['in_id'],
));
if (count($featured_ins) > 0 && !$hide_subscribe) {
    echo '<div>';
    echo '<h3>Featured Intentions:</h3>';
    echo '<div class="list-group actionplan_list">';
    foreach ($featured_ins as $featured_c) {
        echo fn___echo_in_featured($featured_c);
    }
    echo '</div>';
    echo '</div>';
}
?>