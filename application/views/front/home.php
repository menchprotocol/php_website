
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
    .jsm-bottom-bar .jsm-get-started{
        height: 120px;
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
    $(function() {
        $('.phone')
        // Initialize the plugin
            .fbMessenger({
                // options go here
                botLogoUrl:"/img/bp_128.png",
                persistentMenu:[{"label":"My Dashboard"},{"label":"+ Add New Goal"}],
                botName:"Mench",
                botCategory:"Education",
                botWelcomeMessage:"Land your dream job as a programmer"
            })
            // Schedule some interaction:
            .fbMessenger('start', { delay: 3000 })
            .fbMessenger('message', 'user', 'Get Started', { delay: 250 })
            .fbMessenger('typingIndicator', { delay: 250 })
            .fbMessenger('message', 'bot', 'Hi! As your personal assistant im on a mission to help you get hired at your next awesome job.', { delay: 1500 })
            .fbMessenger('typingIndicator', { delay: 2000 })
            .fbMessenger('message', 'bot', 'My knowledge is curated by industry experts and includes key concepts and actionable tasks that increase your effectiveness as a candidate.', { delay: 1500 })
            .fbMessenger('typingIndicator', { delay: 3000 })
            .fbMessenger('message', 'bot', 'Choose a Career to continue:', { delay: 1500 })
            .fbMessenger('message', 'bot', '/1 Get Hired as Full-Stack Developer<br />/2 Get Hired as Software Engineer<br />/3 Get Hired as UI/UX Designer', { delay:2000 })
            .fbMessenger('showQuickReplies', [ '/1', '/2', '/3' ], { delay:0 })
            .fbMessenger('selectQuickReply', 0, { delay: 10000 })
            .fbMessenger('message', 'bot', 'OK, I can help you Get Hired as Full-Stack Web Developer. Ready to get started?', { delay:2000 })
            .fbMessenger('message', 'user', '/restart', { delay: 24000 })
            .fbMessenger('run'); // And trigger the execution
    });
</script>



<div class="row">
    <div class="col-md-6">
        <h1 class="center"><?= $title ?></h1>
        <p class="home_line_2 center">Finding a job you love is about your skills, preferences and ability to craft a story to stand out from the crowd. With Mench personal assistant you'll get curated insights from industry experts to land your next awesome programming job.</p>

        <?php echo_messenger(); ?>

        <div class="border hidden" style="background-color: #FFF; padding:6px 0 2px 6px; margin: 30px auto; max-width:320px;">
            <div class="input-group" style="width:100%;">
                <input style="padding-left:3px; margin-right:7px; width:100%;" type="email" data-lpignore="true" autocomplete="off" id="u_email" value="" class="form-control" placeholder="Email Address" />
                <span class="input-group-addon">
                    <a class="badge badge-primary" onclick="alert('start')" href="javascript:void(0);">Get Started</a>
                </span>
            </div>

            <p style="line-height:130%; font-size: 0.9em; margin:5px 0;"><b>7-Day Free Trial, Then $7 per Week</b>. <span style="display: inline-block;">No credit</span> card needed. Cancel anytime.</p>
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
