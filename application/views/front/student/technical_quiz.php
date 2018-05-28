<h3>Technical Quiz</h3>

<?php

//Quiz Settings
$b_id = 354; //Full stack junior Job Placement
$quiz_duration_minutes = 30; //Minutes
$quiz_url = 'https://docs.google.com/forms/d/e/1FAIpQLSdlmHhvniTzJ2s5JOa_eALgWbv9qWD5Rrd17OA2XNyGt6bZ6w/viewform?usp=pp_url&entry.366927620='.$u['u_email'];


if(!isset($_GET['start'])){

    ?>
    <div id="quiz_start" style="display: block;" >
        <p>A multiple-choice quiz designed to assess your know-how as a full-stack developer.</p>
        <br />
        <p>Applicant: <b><?= $u['u_full_name'] ?></b></p>
        <p>Email: <b><?= $u['u_email'] ?></b></p>
        <p>Quiz Time: <b><?= $quiz_duration_minutes ?> Minutes</b></p>
        <?php if(count($attempts)>0){ ?>
            <p>Attempts so far: <b><?= count($attempts) ?></b></p>
        <?php } ?>

        <br />
        <br />
        <div><a href="/my/quiz/<?= $u['u_id'] ?>?u_email=<?= $u['u_email'] ?>&start=1" class="btn btn-primary">Start <?= $quiz_duration_minutes ?>-Minute Quiz <i class="fas fa-chevron-right"></i></a></div>
    </div>
    <?php

} else {

    //Log attempt engagement:
    $this->Db_model->e_create(array(
        'e_inbound_c_id' => 6997, //Technical Quiz Attempts
        'e_inbound_u_id' => $u['u_id'], //This student
        'e_b_id' => $b_id,
    ));

    ?>
    <script>

        // Set the date we're counting down to
        var countDownDate = new Date("<?= date("Y/m/d H:i:s", strtotime("+".$quiz_duration_minutes." minutes")) ?>").getTime();

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
                $('#quiz_expired').fadeIn();
            }
        }, 1000);

    </script>

    <div id="quiz_expired" style="display:none;">
        <h1>Time is Up </h1>
        <p>Make sure you submit your quiz for your results to be saved. We will get back to you shortly after reviewing your answers.</p>
    </div>

    <div id="quiz_take" style="display:block;">
        <p>Time Remaining: <b id="demo">Loading...</b> [Do Not Refresh]</p>
        <div style="max-width: 700px;"><iframe src="<?= $quiz_url ?>" height="700" frameborder="0" marginheight="0" marginwidth="0" width="100%">Loading...</iframe></div>
    </div>


    <?php
}
?>