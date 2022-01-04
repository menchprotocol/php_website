
<script>

    function load_content(){
        $('.fixed-intro').removeClass('hidden');
        $('.starwars-page').addClass('hidden');
        $('.new-content').html($('.main_content').html());
    }


    $(document).ready(function(){

        scale();

        setTimeout(function () {
            load_content();
        }, 60000);

        let audioPlaying = true,
            backgroundAudio, browser;
        browser = navigator.userAgent.toLowerCase();
        $('<audio class="audio1" src="https://s3foundation.s3-us-west-2.amazonaws.com/7e873e11da5b88a38edabb21babdac48.mp3" loop></audio>').prependTo('body');
        if (!browser.indexOf('firefox') > -1) {
            $('<embed id="background-audio" src="https://s3foundation.s3-us-west-2.amazonaws.com/7e873e11da5b88a38edabb21babdac48.mp3" autostart="1"></embed>').prependTo('body');
            backgroundAudio = setInterval(function() {
                $("#background-audio").remove();
                $('<embed id="background-audio" src="https://s3foundation.s3-us-west-2.amazonaws.com/7e873e11da5b88a38edabb21babdac48.mp3"></embed>').prependTo('body');
            }, 120000); // 120000 is the duration of your audio which in this case 2 mins.
        }

    });

    $( "body" ).click(function() {
        load_content();
    });

    var size=3;
    var posY = 200;
    var ang = 60;
    var delta =1;
    var scaleDelta = 0.008;
    var speed = 40;

    function scale(){
        size = size - scaleDelta;
        posY = posY -delta;
        if(posY<80){
            delta = 0.4;
            scaleDelta = 0.006;
        }
        if(posY<40){
            delta = 0.2;
            scaleDelta = 0.003;
        }
        if(posY<20){
            delta = 0.1;
            scaleDelta = 0.001;
        }

        $(".starwars-intro").css({'top' : posY + "%","transform" : "rotateX(" + ang + "deg) scale(" + size + ")"})

        if(posY> -30){
            setTimeout(scale,speed);
        }else{
            $(".starwars-intro").animate({opacity:"0"},500);
        }
    }


</script>
<style>
    body, html {
        margin: 0;
        padding: 0;
        background: #000 url('https://kassellabs.us-east-1.linodeobjects.com/static-assets/websites/star-wars/bg-stars.png') center;
        font-size: 16px;
    }
    .starwars-page {
        margin: auto;
        width: 100%;
        height: 100%;
        position: absolute;
        bottom: 0;
        perspective: 500px;
        overflow: hidden;
        text-align: center;
    }
    .container{
        background: transparent !important;
    }

    .starwars-intro {
        top: 0%;
        margin: auto;
        width: 100%;
        max-width: 500px;
        position: relative;
        font-size: 1.8em;
        color: #e8be1e;
        text-align: justify;
        transform: rotateX(30deg) scale(2);
    }
    p, .text-center {
        line-height: 115% !important;
        text-align: center;
        max-width: 500px;
        color: #e8be1e !important;
        margin: 0 auto;
        display: block;
        padding-bottom: 13px;
    }
    .fixed-p {
        padding: 0 20px;
        margin-bottom: 34px;
    }
    .btn-yellow i, a {
        color: #e8be1e !important;
    }
    .btn-yellow {
        border: 3px solid #e8be1e !important;
        color: #e8be1e !important;
        margin-bottom: 4px;
    }
</style>

<audio autoplay loop><source src="https://s3foundation.s3-us-west-2.amazonaws.com/7e873e11da5b88a38edabb21babdac48.mp3" type="audio/mp3"></audio>

<div class="starwars-page">

    <div class="starwars-intro">

        <h1 class="text-center">AnakInu</h1>

        <div class="main_content">
            <p>It is a period of unrest as crypto is under attack. The financial systems are crumbling, and AnakInu has come to deal the final blow.</p>
            <p>Senator PalpatInu has turned the young Sith Lord, once believed to have the potential to become one of the most powerful JeDoges ever, to the Dark Side.</p>
            <p>After executing Order 69, AnakInu is now traveling many parsecs to capture LaunchPadme and bring her back to Moosetoofar. There, AnakInu will execute his plan to take over all the Inus and take crypto by storm. Nothing will stop the natural born Inu from achieving greatness.</p>
            <p><img src="https://s3foundation.s3-us-west-2.amazonaws.com/782cac4db51caf39afc04e3b2c0e66c3.jpeg" style="max-width: 300px; border-radius: 8px;"></p>
        </div>

        <p>(Click Anywhere to Continue)</p>

    </div>
</div>



<div class="fixed-intro hidden">
    <h1 class="text-center">AnakInu</h1>
    <p class="new-content fixed-p"></p>
    <p>
        <a class="btn btn-yellow" href="#"><i class="fab fa-telegram"></i> Telegram</a>
        <a class="btn btn-yellow" href="#"><i class="fab fa-twitter"></i> Twitter</a>
        <a class="btn btn-yellow" href="#"><i class="fas fa-file-certificate"></i> White Paper</a>
        <a class="btn btn-yellow" href="#"><i class="fas fa-usd-circle"></i> How to Buy</a>
        <a class="btn btn-yellow" href="#"><i class="fas fa-chart-line"></i> Chart</a>
        <a class="btn btn-yellow" href="#"><i class="fas fa-shopping-cart"></i> Buy Now</a>
    </p>
    <br />
    <br />
    <br />
    <br />

    <div class="row justify-content-center" style="text-align: center; color: #FFF;">
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover">1%</div>
            <div class="info_box_title css__title">Redistribution</div>
            <div class="info_box_message">Holders get rewarded! Simply hold AnakInu and you will earn more AnakInu passively!</div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover">5%</div>
            <div class="info_box_title css__title">Marketing</div>
            <div class="info_box_message">So the world hears about the best community on ETH</div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover">4%</div>
            <div class="info_box_title css__title">Development</div>
            <div class="info_box_message">All costs regarding project development</div>
        </div>
    </div>

    <br />
    <br />
    <br />
    <br />

    <h2 class="text-center">Burning Mechanism</h2>
    <p>We burn a specific amount of supply on milestones. Additionally, 5% of the 1% Redistribution tax gets burned with every transaction.</p>


    <br />
    <br />
    <br />
    <p>Contract: <a href="https://bscscan.com/address/0x277773135557a24b834288bb7e5592a8e95313be#code" target="_blank" style="font-size: 0.8em;">0x277773135557a24b834288bb7e5592a8e95313be</a></p>
    <br />
    <p>
        <a class="btn btn-yellow" href="#"><i class="fas fa-lock"></i> IP Lock</a>
        <a class="btn btn-yellow" href="#"><i class="fas fa-square"></i> Renounce</a>
    </p>





    <br />
    <br />
    <br />
    <h2 class="text-center" style="color: #FFF !important;">Team</h2>
    <div class="row justify-content-center" style="text-align: center; color: #FFF;">
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover"><i class="fad fa-user-astronaut"></i></div>
            <div class="info_box_title css__title">Jonathan MT</div>
            <div class="info_box_message">Lead Dev & Marketing</div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover"><i class="fad fa-user-headset"></i></div>
            <div class="info_box_title css__title">Mike BB</div>
            <div class="info_box_message">Community Manager</div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover"><i class="fad fa-user-visor"></i></div>
            <div class="info_box_title css__title">Coco</div>
            <div class="info_box_message">Design Expert & Dev</div>
        </div>
    </div>


</div>