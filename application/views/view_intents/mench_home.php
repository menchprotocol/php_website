<?php

//Header:
echo '<h1>Land Your Dream Programming Job</h1>';

//Display intent messages:
echo '<div class="home-page-intro">';

echo '<div class="i_content"><div class="msg">Hi, I\'m Mench, a human-trained personal assistant designed to help you get hired at your dream programming job. Our community of "Miners" aggregate key ideas and actionable tasks from top industry experts, and I will communicate them to you via Messenger.</div></div>';

echo '<div class="i_content"><div class="msg">I\'m open-source, free and on a mission to expand your potential.</div></div>';


if(count($featurd_ins) > 0){

    //Add to intro:
    echo '<div class="i_content"><div class="msg">Let\'s get started:</div></div>';

    //Close intro:
    echo '</div>';


    //List Featured intents:
    echo '<div class="list-group actionplan_list grey_list maxout" style="margin-top:20px;">';
    foreach ($featurd_ins as $featured_in) {
        echo echo_in_featured($featured_in);
    }
    echo '</div>';

} else {

    //Close intro:
    echo '</div>';

    //Give call to Action for Messenger:
    echo '<a class="btn btn-primary" href="https://m.me/askmench" style="display: inline-block; padding:12px 36px; margin-top: 30px;">Get Started &nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a>';

}


?>