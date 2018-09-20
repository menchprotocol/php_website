
<script src="/js/lib/jquery.fbmessenger.min.js"></script>
<link href="/css/lib/jquery.fbmessenger.css" rel="stylesheet" />
<link href="/css/lib/devices.min.css" rel="stylesheet" />

<style>
    .iphone-x{
        display: block;
        width: 340px !important;
        height: 640px !important;
        position: relative !important;
        top: unset !important;
        right: unset !important;
        left: unset !important;
        margin:0 auto !important;
    }

    .iphone-x .notch{
        width: 180px !important;
        height: 28px !important;
        top: 26px !important;
        left: 106px !important;
    }

    .jsm-status-navbar{
        top:0;
        height:10%;
    }

    .jsm-nav-title-replies-in{
        line-height: 100%;
        margin-top: -6px;
    }

    .jsm-status-navbar .jsm-status-bar .jsm-battery {
        right:6.8%;
    }

    .jsm-status-navbar .jsm-status-bar .jsm-carrier{
        left:7%;
    }

    .jsm-status-navbar .jsm-nav-left, .jsm-status-navbar .jsm-nav-right {
        top:57%;
    }
    .jsm-status-navbar .jsm-nav-title .jsm-nav-title-bot-name {
        margin-top:6px;
        margin-bottom:1px;
    }

    .jsm-bottom-bar{
        display: block;
        height: 125px !important;
    }

    .jsm-chat-message{
        line-height:140% !important;
        font-size: 1.5em !important;
    }

    .jsm-bot-welcome-status p {
        margin-top: -5px !important;
    }
</style>

<script>

    function start_demo(){

        //Adjust buttons:
        $('.see-demo').remove();


        setTimeout(function () {
            $('html,body').animate({
                scrollTop: $('#iphone-screen').offset().top - 28
            }, 150);
        }, 1000);

        //Get conversation started:
        $('.phone')
        // Initialize the plugin
            .fbMessenger({
                // options go here
                botLogoUrl:"/img/bp_128.png",
                persistentMenu:[
                    {"label": "🚩 Action Plan" },
                    {"label": "👤 My Account" }
                ],
                botName:"Mench",
                botCategory:"Education",
                botWelcomeMessage:"Land your dream job as a programmer"
            })
            // Schedule some interaction:
            .fbMessenger('start', { delay: 2000 })
            .fbMessenger('message', 'user', 'Get Started', { delay: 1000 })
            .fbMessenger('typingIndicator', { delay: 0 })
            .fbMessenger('message', 'bot', 'Hi, I\'m Mench, a personal assistant that will get you hired at your next awesome job 🙌', { delay: 3000 })
            .fbMessenger('typingIndicator', { delay: 5000 })
            .fbMessenger('message', 'bot', 'My human trainers reference videos, books, articles and podcasts from industry experts which I will deliver to you in a timely manner as a roadmap to land your dream job 🌈', { delay: 5000 })
            .fbMessenger('typingIndicator', { delay: 10000 })
            .fbMessenger('message', 'bot', 'Are you interested to land a fabulous programming job?', { delay: 1500 })
            .fbMessenger('message', 'bot', '/1 Yes 👍<br />/2 No 👎<br />/3 Explain more 🤔', { delay:2000 })
            .fbMessenger('showQuickReplies', [ '/1', '/2', '/3' ], { delay:0 })
            .fbMessenger('selectQuickReply', 0, { delay:5000 })
            .fbMessenger('message', 'bot', 'Sweet 🙏 you are now subscribed to [land a fabulous programming job]. You can manage your intentions using the [🚩Action Plan] button below', { delay:3000 })
            .fbMessenger('typingIndicator', { delay:10000 })
            .fbMessenger('message', 'bot', 'To land a fabulous programming job you need to invest 152-hour to learn 92 key concepts while also completing 61 actionable tasks...', { delay:5000 })
            .fbMessenger('typingIndicator', { delay:10000 })
            .fbMessenger('message', 'bot', 'Considering your current schedule, how many hours per week can you invest to land a fabulous programming job?<br /><br />/1 8 Hours/Week (19 Weeks Long)<br />/2 12 Hours/Week (13 Weeks Long)<br />/3 20 Hours/Week (8 Weeks Long)<br />/4 40 Hours/Week (4 Weeks Long)<br />/5 Not Sure Yet', { delay:5000 })
            .fbMessenger('showQuickReplies', [ '/1', '/2', '/3', '/4', '/5' ], { delay:0 })
            .fbMessenger('selectQuickReply', 2, { delay:4000 })
            .fbMessenger('typingIndicator', { delay:0 })
            .fbMessenger('message', 'bot', 'Ok, I will adjust your Action Plan pace to 20 hours per week to land a fabulous programming job. We should be done in 8 weeks if you choose to continue with this this pace. You can always change pace to go slower or faster.', { delay: 2000 })
            .fbMessenger('typingIndicator', { delay:6000 })
            .fbMessenger('message', 'bot', 'Which 8-week window works best for you?<br /><br />/1 Now until <?= echo_time((time()+(8*7*24*3600)),2) ?><br />/2 <?= echo_time((strtotime('next monday')),2) ?> until <?= echo_time((strtotime('next monday')+(8*7*24*3600)),2) ?><br />/3 <?= echo_time((strtotime('+4 weeks monday')),2) ?> until <?= echo_time((strtotime('+4 weeks monday')+(8*7*24*3600)),2) ?><br />/4 Not Sure Yet', { delay:2000 })
            .fbMessenger('showQuickReplies', [ '/1', '/2', '/3', '/4' ], { delay:0 })
            .fbMessenger('selectQuickReply', 1, { delay:4000 })
            .fbMessenger('message', 'bot', 'Ok, I\'ll message you on <?= echo_time((strtotime('next monday')),2) ?> to get started, talk soon 🙌', { delay: 1500 })
            .fbMessenger('typingIndicator', { delay:5000 })
            .fbMessenger('message', 'bot', 'Hi, it\'s <?= echo_time((strtotime('next monday')),2) ?> and time to land a fabulous programming job together 🙌', { delay:5000 })
            .fbMessenger('message', 'bot', 'To get started, choose your career path:<br /><br />/1 Get Hired as a Web Developer<br />/2 Get Hired as a Data Scientist<br />/3 Get Hired as a UI/UX Designer<br />/4 Request a new career path', { delay:2000 })
            .fbMessenger('showQuickReplies', [ '/1', '/2', '/3', '/4' ], { delay:0 })
            .fbMessenger('selectQuickReply', 0, { delay:4000 })
            .fbMessenger('typingIndicator', { delay:3000 })
            .fbMessenger('message', 'bot', 'Ok, here is what we need to cover to Get Hired as a Web Developer:<br /><br />/1 Understand What it Takes to Become a Web Develope (1h)<br />/2 Craft Your Career Stor (10h)<br />/3 Secure Interviews (44h)<br />/4 Succeed at Interviews (26h)<br /><br />Lets start with 1/ Understand What it Takes to Become a Web Developer estimated to take 1 hour to complete', { delay:10000 })
            .fbMessenger('typingIndicator', { delay:3000 })
            .fbMessenger('message', 'bot', 'In this day and age becoming a web developer is not about knowing a few programming languages, but instead, it\'s about knowing how the web works and the inter-connectivity of many programming languages and APIs', { delay:10000 })
            .fbMessenger('typingIndicator', { delay:3000 })
            .fbMessenger('message', 'bot', 'Ok, here is what we need to cover to Understand What it Takes to Become a Web Developer:<br /><br />/1 Read "A Guide to Becoming a Full-Stack Developer" on Medium (15m)<br />2/ Complete the Programmer Competency Matrix (30m)<br />/3 Learn what employers look for when hiring developers (15m)<br /><br />Lets start with 1/ Read "A Guide to Becoming a Full-Stack Developer" on Medium which is estimated to take 15 minutes to complete', { delay:10000 })

            .fbMessenger('typingIndicator', { delay:3000 })
            .fbMessenger('message', 'bot', 'This is a great article explaining what it takes to become a full-stack developer. You can read it here: <a href="https://medium.com/coderbyte/a-guide-to-becoming-a-full-stack-developer-in-2017-5c3c08a1600c" target="_blank" style="color:#0000FF;">medium.com/coderbyte/a-guide-to-becoming-a-full-stack-developer-in-2017-5c3c08a1600c</a>', { delay:10000 })

            .fbMessenger('typingIndicator', { delay:3000 })
            .fbMessenger('message', 'bot', 'To complete this task click on the [🚩Action Plan] button below and explain in one paragraph the core message of this article from your perspective', { delay:10000 })

            .fbMessenger('message', 'user', 'Restart', { delay:6000000 })
            .fbMessenger('run'); // And trigger the execution
    }
</script>



<div class="row">
    <div class="main-message col-md-6">
        <h1 class="center"><?= $title ?></h1>
        <p class="home_line_2 center">Finding a job you love is about your skills, preferences and ability to craft a story to stand out from the crowd. With Mench personal assistant you'll get curated insights from industry experts to land your next awesome programming job.</p>

        <div style="padding:20px 0;">
            <?php echo_messenger(); ?>
        </div>

    </div>
    <div class="col-md-6">
        <div class="marvel-device iphone-x" id="iphonex" intent-id="">
            <div class="notch">
                <div class="camera"></div>
                <div class="speaker"></div>
            </div>
            <div class="top-bar"></div>
            <div class="sleep"></div>
            <div class="bottom-bar"></div>
            <div class="volume"></div>
            <div class="overflow">
                <div class="shadow shadow--tr"></div>
                <div class="shadow shadow--tl"></div>
                <div class="shadow shadow--br"></div>
                <div class="shadow shadow--bl"></div>
            </div>
            <div class="inner-shadow"></div>
            <div class="screen" id="iphone-screen">

                <div class="see-demo" style="text-align: center; padding-top:200px;">
                    <a class="btn btn-primary" href="#startdemo" onclick="start_demo()">Start Demo <i class="fas fa-chevron-right"></i></a>
                </div>

                <div class="phone"></div>
            </div>
        </div>

    </div>
</div>







<?php
/*

//Fetch home page intents:
$child_cs = $this->Db_model->cr_outbound_fetch(array(
    'cr.cr_inbound_c_id' => 7241, //Get hired as a programmer intents that are published
    'cr.cr_status >=' => 0,
    'c.c_status >' => 0,
));

echo '<h2 class="title" style="text-align:center; margin-top:50px;">Trained Career Paths:</h2>';
echo '<div class="row">';
echo '<div class="list-group maxout" style="display: block; margin: 0 auto;">';
foreach($child_cs as $count=>$c){
    echo '<a href="/'.$c['c_id'].'" class="list-group-item">';
    echo '<span class="pull-right"><span class="badge badge-primary"><i class="fas fa-chevron-right"></i></span></span>';
    echo '<i class="fas fa-bullseye-arrow" style="margin: 0 8px 0 2px;"></i>';
    echo $c['c_outcome'];
    echo '</a>';
}

echo '</div>';
echo '</div>';

*/
?>
</div>
</div>


</div>
</div>

<div class="main main-raised main-plain main-footer">
    <div class="container">

        <?php $this->load->view('front/shared/why_mench'); ?>
