<!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8" />
    <meta name="theme-color" content="#FFFFFF">
    <link rel="icon" href="/img/sithlords/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sith Lords</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>

    <link href="https://fonts.googleapis.com/css?family=<?= view_memory(6404,29711) ?>&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="//pro.fontawesome.com/releases/v5.15.2/css/all.css">

    <script>

        function load_content(){
            $('.fixed-intro').removeClass('hidden');
            $('.starwars-page, .skip_intro').addClass('hidden');
        }

        $(document).ready(function(){
            scale();
            setTimeout(function () {
                load_content();
            }, 37000);

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
            background: #000 url('/img/sithlords/bg.png') center;
            font-size: 16px;
        }
        .starwars-page {
            margin: auto;
            width: 100%;
            height: 100%;
            position: absolute;
            bottom: 0;
            left:0;
            perspective: 500px;
            overflow: hidden;
            text-align: center;
        }
        .hidden {
            display: none;
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
        .info_box_title{
            font-size: 1.5em;
            padding:0 0 8px 0;
            font-weight: bold;
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
        a {
            color: #FFF !important;
        }
        .btn-yellow i, a.btn-yellow {
            color: #e8be1e !important;
        }
        .btn-yellow {
            border: 3px solid #e8be1e !important;
            color: #e8be1e !important;
            margin-bottom: 4px;
        }
        .btn-white i {
            color: #FFF !important;
        }
        .btn-white {
            border: 3px solid #FFF !important;
            color: #FFF !important;
            margin-bottom: 4px;
        }


        @-webkit-keyframes fa-spin-reverse {
            0% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg); }
            100% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg); } }

        @keyframes fa-spin-reverse {
            0% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg); }
            100% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg); }
        }
        .fa-spin-reverse-slow{
            -webkit-animation: fa-spin-reverse 20s infinite linear;
            animation: fa-spin-reverse 20s infinite linear;
        }
        .info_box_cover{
            font-size:4em;
            padding: 21px 0;
            text-align: center;
        }
        .main_title{
            font-size: 2.9em;
            color: #FFF !important;
            padding: 34px 0 0;
        }

        .center-icons {
            text-align: center;
        }
        .center-icons a{
            margin:4px 8px 4px 0;
            display: inline-block;
        }
        .center-icons a img{
            width: 40px;
            margin-bottom: 13px;
        }

        .container {
            background: transparent !important;
            max-width: 2560px;
        }

        .round{
            border-radius: 50%;
        }


        .logo_div{
            position: fixed;
            top:3px;
            left:5px;
        }
        .logo_div img{
            height:99px;
        }
        .call_to_action{
            position: fixed;
            top:5px;
            right:5px;
        }
        .fivesiths{
            display: block;
            margin-top: -366px;
        }

        .top_header{
            width: calc(100% + 30px);
            max-width: 2560px;
            margin-left: -15px;
        }

        .fivesiths img{
            width:66%;
        }

        .htb-link-video{
            position: relative;
            z-index: 2;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            width: 650px;
            height: 350px;
            margin-top: 2.5em;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            border: 10px solid #fff;
            border-radius: 24px;
            background-image: url("https://www.babycake.app//images/video_image.png");
            background-position: 50% 50%;
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-transition: all 150ms ease;
            transition: all 150ms ease;
            margin: 34px auto;
        }


        @media (max-width:1500px) {
            .fivesiths{
                margin-top: -222px;
            }
            .fivesiths img{
                width:77%;
            }
            .logo_div img{
                height:66px;
            }
            .htb-link-video {
                width: 560px;
                height: 290px;
            }
        }

        @media (max-width:767px) {
            .fivesiths{
                margin-top: -89px;
            }
            .logo_div img{
                height:44px;
            }
            .fivesiths img{
                width:89%;
            }
            .htb-link-video {
                width: 280px;
                height: 150px;
            }
        }


    </style>

</head>

<body style="padding-bottom: 100px;">


<div>
    <iframe src="/img/sithlords/white.mp3" allow="autoplay" id="audio" style="display: none"></iframe>
    <audio autoplay><source src="/img/sithlords/stars.mp3" type="audio/mp3"></audio>
</div>

<div class="container">
    <div class="starwars-page">
        <div style="position: absolute; top:0; width: 100%; text-align: center; padding-top: 5px;" class="skip_intro"><a class="btn btn-yellow" href="javascript:void(0)" onclick="load_content()">SKIP INTRO <i class="fas fa-arrow-right"></i></a></div>

        <div class="starwars-intro">

            <h1 class="text-center">Sith Lords Club</h1>

            <p>It is a period of unrest as crypto is under attack. The Jedi are getting weaker as traditional finance and utility are dying. The financial systems are crumbling, and Sith Lords have come to deal the final blow. The only path is into the metaverse searching for The Lost Tribe of the Sith Lords, where we are building a community of investors and crypto enthusiasts to usher in the wave of decentralized blockchain.</p>
            <p>Once inside the metaverse and executing Order 69, Sith Lords travel many parsecs moons to offer exclusivity, governance, and utility to all its investors and strong dark Jedi's minds. After that, Sith Lords will execute their plan to take over the crypto space with their Inu minions and destroy all those who look to nuke or sell.</p>
            <p>Nothing will stop the natural-born Lords from success and turn The Rule of Two into The Rule of Many. So join the Darkside today and enter the Sith Lords Club to access the next crypto movement.</p>

            <!--
            <p>It is a period of unrest as crypto is under attack. The financial systems are crumbling, and Sith Lords have come to deal the final blow.</p>
            <p>Senator PalpatInu has turned the young Sith Lord, once believed to have the potential to become one of the most powerful JeDoges ever, to the Dark Side.</p>
            <p>After executing Order 69, Sith Lords are now traveling many parsecs to capture LaunchPadme and bring her back to Moosetoofar. There, Sith Lords will execute their plan to take over all the Inus and take crypto by storm. Nothing will stop the natural born Inu from achieving greatness.</p>
            -->

        </div>
    </div>
</div>


<div class="fixed-intro hidden container fixed-top" style="padding-bottom: 0 !important;">
    <div class="logo_div"><img src="/img/sithlords/Sith Lords Logo Mark@2x.png"></div>
    <div class="call_to_action"><a class="btn btn-white" href="javascript:alert('Launching Soon...')"><i class="fas fa-lock"></i> Buy Now</a></div>
</div>


<div class="fixed-intro hidden container" style="text-align: center; padding-bottom:147px !important;">


    <img src="/img/sithlords/Background@2x.jpg" class="top_header">
    <div class="fivesiths"><img src="/img/sithlords/Sith Lord Comp@2x.png"></div>

    <div class="container">

        <p class="fixed-p hidden">A community. An idea. A new force. A new technology. A new future.</p>

        <br />
        <br />
        <p>
            <a class="btn btn-white" href="javascript:alert('Telegram Coming Soon...')"><i class="fab fa-telegram"></i> Telegram</a>
            <a class="btn btn-white" href="#howtobuy"><i class="fas fa-usd-circle"></i> How to Buy</a>
            <a class="btn btn-white" href="#roadmap"><i class="fas fa-route"></i> Roadmap</a>
        </p>

        <br />
        <br />
        <p style="font-size: 2.5em; color: #FFF !important;" class="hidden">Stealth launch in <span id="countdown" style="display: inline-block;"></span></p>


        <script>
            // Set the date we're counting down to
            var countDownDate = new Date("Jan 24, 2022 14:00:00").getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {

                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"
                document.getElementById("countdown").innerHTML = days + "D " + hours + "H "
                    + minutes + "M " + seconds + "S";

                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("countdown").innerHTML = "COIN IS LAUNCHED: YOU CAN BUY NOW";
                }
            }, 1000);
        </script>

        <br />
        <br />
        <br />
        <br />
        <h2 class="text-center main_title">Tokenomics</h2>
        <div class="row justify-content-center" style="text-align: center; color: #FFF;">
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover">1%</div>
                <div class="info_box_title">Redistribution</div>
                <div class="info_box_message">Simply hold and earn more Sith Lords passively!</div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover">4%</div>
                <div class="info_box_title">Development</div>
                <div class="info_box_message">All costs regarding project development</div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover">5%</div>
                <div class="info_box_title">Marketing</div>
                <div class="info_box_message">So the world hears about the best community on Binance</div>
            </div>
        </div>
        <br />
        <p style="text-align: center; color: #FFF !important;">We also burn a specific amount of supply on milestones. Additionally, 5% of the 1% Redistribution tax gets burned with every transaction.</p>
        <br />
        <p>
            <a class="btn btn-white" href="javascript:alert('Coming Soon...')"><i class="fas fa-file-certificate"></i> Contract</a>
            <a class="btn btn-white" href="javascript:alert('Coming Soon...')"><i class="fas fa-lock"></i> IP Lock</a>
            <a class="btn btn-white" href="javascript:alert('Coming Soon...')"><i class="fas fa-square"></i> Renounce</a>
        </p>
        <br />
        <div class="center-icons">
            <a href="javascript:alert('Coming Soon...')"><img src="/img/sithlords/light-bscscan.svg" class="Footer_link__DBs2K" style="background-color:#FFF; border-radius: 50%;"></a>
            <a href="javascript:alert('Coming Soon...')"><img src="/img/sithlords/light-cmc.svg" class="Footer_link__DBs2K" style="background-color:#FFF; border-radius: 50%;"></a>
            <a href="javascript:alert('Coinbase Listing Coming Soon...')"><img src="/img/sithlords/coinbase.png" style="border-radius: 50%;"></a>
            <a href="javascript:alert('Coin Gecko Listing Coming Soon...')"><img src="/img/sithlords/coingecko.svg"></a>
            <a href="javascript:alert('DexTools Link Coming Soon...')"><img src="/img/sithlords/dextools.svg"></a>
            <a href="javascript:alert('PooCoin Coming Soon...')"><img src="/img/sithlords/poocoin.svg"></a>
            <a href="https://twitter.com/sithlordsclub" target="_blank"><img src="/img/sithlords/twitter.png"></a>
        </div>








        <br />
        <br />
        <br />
        <br />
        <a name="howtobuy">&nbsp;</a>
        <h2 class="text-center main_title">How To Buy</h2>
        <div class="row justify-content-center" style="text-align: center; color: #FFF;">
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/sithlords/metamask-2728406-2261817.png" style="width:110px;"></div>
                <div class="info_box_title">Setup MetaMask</div>
                <div class="info_box_message">First download MetaMask (a crypto wallet in the form of a browser extension) or TrustWallet (an app for your phone). After that, you will have to add the Binance Smart Chain to your network-list. (Click here for a step-by-step tutorial).</div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/sithlords/coinbase.png" style="width:110px;" class="round"></div>
                <div class="info_box_title">Buy & Send BNB</div>
                <div class="info_box_message">Then Buy BNB on an exchange (i.e. Binance, Kraken, Coinbase etc.). Transfer the tokens to your MetaMask wallet address. BEP-20 addresses start with a "0x".</div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/sithlords/pancakeswap-cake-logo.png" style="width:110px;"></div>
                <div class="info_box_title">Swap on Pancake</div>
                <div class="info_box_message">Finally click here to buy Sith Lords on PancakeSwap. Select SithLords or use our contract address. Set the slippage tolerance to 12% (sometimes it may be 18%, depending on how much demand there is).</div>
            </div>
        </div>

        <div style="text-align: center; position: relative;">
            <a href="https://www.youtube.com/watch?v=KpF41eS3YZQ" target="_blank" class="htb-link-video"><img src="https://www.babycake.app/images/play.svg" loading="lazy" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;" alt="" class="play">
                <div style="opacity: 0;" class="video-overlay"></div>
            </a>
        </div>






        <br />
        <br />
        <br />
        <br />
        <a name="roadmap">&nbsp;</a>
        <h2 class="text-center main_title">Roadmap</h2>
        <div class="row justify-content-center" style="text-align: center; color: #FFF;">
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><i class="fad fa-starship-freighter fa-spin-reverse-slow"></i></div>
                <div class="info_box_title">Phase 1</div>
                <div class="info_box_message">
                    Lock & Burn Tokens
                    <br />Listings CMC+BSCSCAN
                    <br />Contract Audits
                    <br />Rewards Dashboard
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><i class="fad fa-starfighter-alt fa-spin-reverse-slow"></i></div>
                <div class="info_box_title">Phase 2</div>
                <div class="info_box_message">Youtube/Twitter Campaigns
                    <br />Sith Lords NFT
                    <br />Promotional Contests
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><i class="fad fa-starfighter fa-spin-reverse-slow"></i></div>
                <div class="info_box_title">Phase 3</div>
                <div class="info_box_message">Sith Lords DAO
                    <br />Partnerships & Livestreams
                    <br />Battle Farming Beta Release</div>
            </div>
        </div>






        <br />
        <br />
        <br />
        <br />
        <a name="team">&nbsp;</a>
        <h2 class="text-center main_title">Team</h2>
        <div class="row justify-content-center" style="text-align: center; color: #FFF;">
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/sithlords/Starkiller@2x.png" style="height:150px;"></div>
                <div class="info_box_title">Starkiller</div>
                <div class="info_box_message">CTO & Developer</div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/sithlords/Sith Emperor@2x.png" style="height:150px;"></div>
                <div class="info_box_title">Sith Emperor</div>
                <div class="info_box_message">CMO & Product</div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/sithlords/Darth Lord@2x.png" style="height:150px;"></div>
                <div class="info_box_title">Darth Lord</div>
                <div class="info_box_message">CCO & Community Growth</div>
            </div>
        </div>







    </div>
</div><!-- Container -->



</body>
</html>