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


<div id="in_public_ui">

    <?php
    if (!($c['in_id'] == $this->config->item('in_primary_id')) && 0) {
        //TODO Re-active later... For now we have the bottom section for related intentions
        $need_grandpa = true;
        $grandpa_intent = null;
        $parent_ins = null;
        //Show all parent intents for this intent:
        foreach ($c['in__parents'] as $ci) {
            $parent_ins .= '<a class="list-group-item" href="/' . $ci['in_id'] . '"><span class="badge badge-primary"><i class="fas fa-angle-left"></i></span> ' . $ci['in_outcome'] . '</a>';
            if ($ci['in_id'] == $this->config->item('in_primary_id')) {
                //Already included:
                $need_grandpa = false;
            }
        }

        if ($need_grandpa) {
            //Fetch top intent and include it here:
            $gps = $this->Database_model->in_fetch(array(
                'in_id' => $this->config->item('in_primary_id'),
            ));
            $grandpa_intent = '<a class="list-group-item" href="/' . $gps[0]['in_id'] . '"><span class="badge badge-primary"><i class="fas fa-angle-left"></i></span> ' . $gps[0]['in_outcome'] . '</a>';
        }

        //Display generated parents:
        echo '<div class="list-group" style="margin-top: 10px;">';
        echo($need_grandpa ? $grandpa_intent : '');
        echo $parent_ins;
        echo '</div>';

    }

    //Intent Title:
    echo '<h1 style="margin-bottom:30px;" id="title-parent">' . $c['in_outcome'] . '</h1>';


    //Show all instant messages for this intent:
    foreach ($c['in__messages'] as $i) {
        if ($i['tr_status'] == 1) {
            //Publish to Landing Page!
            echo echo_message_chat($i);
        }
    }
    ?>

    <br/>

    <h3 style="margin-top:0px !important;">Overview:</h3>
    <div style="margin:12px 0 0 5px;">
        <?= echo_overview_in($c, 0) ?>
        <?= echo_contents($c, 0) ?>
        <?= echo_experts($c, 0) ?>
        <?= echo_completion_estimate($c, 0) ?>
        <?= echo_costs($c, 0) ?>
    </div>


    <?php if (count($c['in__children']) > 0) { ?>

        <h3>Action Plan:</h3>
        <div class="list-group actionplan_list" style="margin:12px 0 0 5px;">
            <?php
            $do_expand = ( isset($_GET['do_expand']) && intval($_GET['do_expand']) );
            $c1_counter = 0;
            foreach ($c['in__children'] as $c1_counter => $c1) {


                echo '<div class="panel-group" id="open' . $c1_counter . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $c1_counter . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $c1_counter . '" href="#collapse' . $c1_counter . '" aria-expanded="'.( $do_expand ? 'true' : 'false' ).'" aria-controls="collapse' . $c1_counter . '">
                       ' . ($c['in_is_any'] ? 'Option' : 'Step') . ' ' . ($c1_counter + 1) . ': <span id="title-' . $c1['in_id'] . '">' . $c1['in_outcome'] . '</span><i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $c1_counter . '" class="panel-collapse collapse '.( $do_expand ? 'in' : 'out' ).'" role="tabpanel" aria-labelledby="heading' . $c1_counter . '">
                <div class="panel-body" style="padding:5px 0 0 5px;">';


                //Show time if we have it:
                if ($c['in__tree_max_seconds'] > 0) {
                    echo '<div style="margin:0 0 5px; padding-top:5px; font-size:1.1em;">It is estimated to take ' . fn___echo_hours_range($c1) . ' to complete this part.</div>';
                }


                //First show all messages for this intent:
                foreach ($c1['in__messages'] as $i) {
                    if ($i['tr_status'] == 1) {
                        echo echo_message_chat(array_merge($i, array(
                            'noshow' => 1,
                        )), 'Dear candidate'); //As they are a guest at this point
                    }
                }

                if (count($c1['in__grandchildren']) > 0) {

                    echo '<ul style="list-style:none; margin-left:-30px; font-size:1em;">';
                    foreach ($c1['in__grandchildren'] as $c2_counter => $c2) {
                        echo '<li>Part ' . ($c1_counter + 1) . '.' . ($c2_counter + 1) . '. ' . $c2['in_outcome'] . '</li>';
                    }
                    echo '</ul>';

                    //Since it has children, lets also give the option to navigate downwards ONLY IF...
                    if ($c1['in_status'] >= 2) {
                        echo '<div>You can choose to <a href="/' . $c1['in_id'] . '" ' . ($c['in_id'] == $this->config->item('in_primary_id') ? 'onclick="confirm_child_go(' . $c1['in_id'] . ')"' : '') . ' class="alink-' . $c1['in_id'] . '" style="text-decoration:underline;">subscribe to this part only</a>.</div>';
                    }

                }

                echo '</div>
            </div>
        </div></div>';

            }
            ?>
        </div>
        <br/>
    <?php } ?>


    <p style="padding:15px 0 0 0;">Ready to <?= $c['in_outcome'] ?>?</p>

    <!-- Call to Actions -->
    <a class="btn btn-primary" href="https://m.me/askmench?ref=ACTIONPLAN-ADD-INITIATE_<?= $c['in_id'] ?>"
       style="display: inline-block; padding: 12px 36px;">Get Started [Free] <i class="fas fa-angle-right"></i></a>

    <span class="learn_more_toggle" style="display: inline-block;">or <a class="btn btn-primary grey" href="#learnMore"
                                                                         onclick="$('.learn_more_toggle').toggle();"
                                                                         style="display: inline-block; padding:12px 16px;">Learn More <i
                    class="fas fa-info-circle"></i></a></span>

</div>


<a name="learnMore"></a>
<div style="display: none;" class="learn_more_toggle">

    <h3 style="margin-top: 0px !important;">Advance Your Tech Career:</h3>
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
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">Choose the number of hours you can commit each week to ' . $c['in_outcome'] . ' and Mench will streamline your progress based on your availability. Go as fast or slow as you like to achieve the right balance for success.</div>
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
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">Mench Personal Assistant works on Facebook Messenger. Think of it as an expert friend on a mission to ' . $this->config->item('in_primary_name') . '! If you do not have (or want to have) a Facebook account, you can easily use Facebook Messenger <a href="https://newsroom.fb.com/news/2015/06/sign-up-for-messenger-without-a-facebook-account/" target="_blank" style="text-decoration: underline;">without a Facebook account</a>.</div>
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
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">The first question that Mench will ask you is to confirm if you are interested to ' . $c['in_outcome'] . '. Answering Yes will add this intention to your Action Plan so Mench can help you accomplish it.</div>
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
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">Mench will continue the conversation and provide you with a step by step Action Plan that helps you ' . $c['in_outcome'] . '.</div>
            </div>
        </div></div>';

        ?>
    </div>


</div>


<?php
$featured_cs = $ins = $this->Database_model->in_fetch(array(
    'in_status' => 3, //Featured Intents
    'in_id !=' => $c['in_id'],
));
if (count($featured_cs) > 0) {
    echo '<div>';
    echo '<h3>More Intentions:</h3>';
    echo '<div class="list-group actionplan_list">';
    foreach ($featured_cs as $featured_c) {
        echo echo_featured_c($featured_c);
    }
    echo '</div>';
    echo '</div>';
}
?>