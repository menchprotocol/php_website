<?php

echo echo_b_header($b);

if(!isset($_GET['start'])){

    ?>
    <div id="assessment_start" style="display: block;" >
        <p style="font-weight: 300;">The next step is to take an instant assessment designed to ensure you have what it takes to <?= strtolower($b['c_outcome']) ?>. We will email your results right after you submit your assessment.</p>
        <br />

        <div class="dash-label"><span class="icon-left"><i class="fas fa-user"></i></span> <?= $u['u_full_name'] ?></div>
        <div class="dash-label"><span class="icon-left"><i class="fas fa-envelope"></i></span> <?= $u['u_email'] ?></div>

        <?php if($b['b_assessment_minutes']>0){ ?>
            <div class="dash-label"><span class="icon-left"><i class="fas fa-clock"></i></span> <?= $b['b_assessment_minutes'] ?> Minute Time Limit</div>
        <?php } ?>
        <?php if(count($attempts)>0){ ?>
            <div class="dash-label"><span class="icon-left"><i class="fas fa-clipboard"></i></span> <?= count($attempts) ?> Attempts so far</div>
        <?php } ?>

        <br />
        <a href="/<?= $b['b_url_key'] ?>/assessment?u_email=<?= $u['u_email'] ?>&start=1" class="btn btn-funnel" style="color:#FFF; font-size:1em;">Take <?= ( $b['b_assessment_minutes']>0 ? $b['b_assessment_minutes'].' Minute ' : '' ) ?>Assessment &nbsp;<i class="fas fa-chevron-circle-right" style="font-size:1.1em;"></i><div class="ripple-container"></div></a>


    </div>
    <?php

} else {

    //Log attempt engagement:
    $this->Db_model->e_create(array(
        'e_inbound_c_id' => 6997, //Instant Assessment Attempts
        'e_inbound_u_id' => $u['u_id'], //This student
        'e_b_id' => $b['b_id'],
    ));

    ?>

    <?php if($b['b_assessment_minutes']>0){ ?>
        <script>

            // Set the date we're counting down to
            var countDownDate = new Date("<?= date("Y/m/d H:i:s", strtotime("+".$b['b_assessment_minutes']." minutes")) ?>").getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {

                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now an the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display the result in the element with id="demo"
                document.getElementById("demo").innerHTML = minutes + " Minutes " + seconds + " Seconds ";

                // If the count down is finished, write some text
                if (distance < 0) {
                    //Redirect to finished page:
                    $('.initial_info').hide();
                    $('#assessment_expired').fadeIn();
                }
            }, 1000);

        </script>
        <div id="assessment_expired" style="display:none;">
            <h1 style="color: #FF0000;"><i class="fas fa-exclamation-triangle"></i> Time is Up</h1>
            <p style="font-size: 0.9em; color: #FF0000;">Submit your assessment and check your email to see your results.</p>
        </div>
        <p style="font-size: 0.9em;" class="initial_info">Time Remaining: <b id="demo">Loading...</b> [Do Not Refresh Page]</p>
        <p style="font-size: 0.9em;" class="initial_info">Answer all questions before time ends:</p>
    <?php } ?>

    <div style="max-width: 700px;"><iframe src="<?= str_replace('{u_email}',$u['u_email'],str_replace('{u_full_name}',$u['u_full_name'],$b['b_assessment_url'])) ?>" height="700" frameborder="0" marginheight="0" marginwidth="0" width="100%">Loading Assessment...</iframe></div>

    <?php
}
?>