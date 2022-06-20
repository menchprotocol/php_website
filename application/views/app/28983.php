<?php

$social_nav = '<div class="center-icons">
    <a href="https://t.me/mcbrokebsc"><img src="/img/mcbroke/telegram.svg" class="Footer_link__DBs2K" style="background-color:#FFF; border-radius: 50%;"></a>
    <a href="javascript:alert(\'Twitter Coming Soon...\')" target="_blank"><img src="/img/mcbroke/twitter.png"></a>
    <a href="javascript:alert(\'Coming Soon...\')"><img src="/img/mcbroke/light-bscscan.svg" class="Footer_link__DBs2K" style="background-color:#FFF; border-radius: 50%;"></a>
    <a href="javascript:alert(\'Coming Soon...\')"><img src="/img/mcbroke/light-cmc.svg" class="Footer_link__DBs2K" style="background-color:#FFF; border-radius: 50%;"></a>
    <a href="javascript:alert(\'Coin Gecko Listing Coming Soon...\')"><img src="/img/mcbroke/coingecko.svg"></a>
    <a href="javascript:alert(\'DexTools Link Coming Soon...\')"><img src="/img/mcbroke/dextools.svg"></a>
    <a href="javascript:alert(\'PooCoin Coming Soon...\')"><img src="/img/mcbroke/poocoin.svg"></a>
</div>';

?><!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8" />
    <meta name="theme-color" content="#FFFFFF">
    <link rel="icon" href="/img/mcbroke/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McBroke || $BROKE - The Official BSC Token</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="//pro.fontawesome.com/releases/v5.15.2/css/all.css">

    <style>
        body, html {
            margin: 0;
            padding: 0;
            background: #FFFFFF;
            font-size: 16px;
            font-family: 'Lato', sans-serif;
        }

        .hidden {
            display: none;
        }

        .info_box_title{
            font-size: 1.5em;
            padding:0 0 8px 0;
            font-weight: bold;
            color: #e50201;
        }
        p, .text-center {
            line-height: 115% !important;
            text-align: center;
            max-width: 720px;
            color: #000 !important;
            margin: 0 auto;
            display: block;
            padding-bottom: 13px;
        }
        p, .info_box_message{
            font-size: 1.2em;
        }
        p {
            max-width: 80%;
        }
        .fixed-p {
            padding: 0 20px;
            margin-bottom: 34px;
        }
        a {
            color: #FFF !important;
        }
        .btn {
            font-weight: bold !important;
            border-radius: 13px;
        }
        .btn-white i {
            color: #000 !important;
        }
        .btn-white {
            border: 3px solid #000000 !important;
            background-color: #FFCC00;
            color: #000 !important;
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
            padding: 34px 0 3px;
            text-align: center;
        }
        .info_box_cover img{
            border-radius: 13px;
        }
        .main_title{
            font-size: 2.9em;
            color: #000 !important;
            padding: 34px 0 0;
        }

        .center-icons {
            text-align: center;
        }
        .center-icons a{
            margin:8px 8px 8px 0;
            display: inline-block;
        }
        .center-icons a img{
            width: 34px;
        }
        .main-content img {
            margin-top: 34px;
            max-width: 420px;
        }

        .container {
            background: transparent !important;
            max-width: 2560px;
            padding: 0;
        }

        .round{
            border-radius: 50%;
        }


        .logo_div{
            margin-top:3px;
            margin-left:5px;
            float:left;

        }
        .logo_div img{
            height:69px;
        }
        .call_to_action{
            position: relative;
            margin-top:5px;
            float:right;
            margin-right:5px;
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
            border-radius: 13px;
            background-image: url("https://www.babycake.app//images/video_image.png");
            background-position: 50% 50%;
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-transition: all 150ms ease;
            transition: all 150ms ease;
            margin: 34px auto;
        }

        .rounded-corner{
            border-radius: 13px;
        }

        @media (max-width:1500px) {
            .htb-link-video {
                width: 560px;
                height: 290px;
            }
        }

        @media (max-width:767px) {
            .htb-link-video {
                width: 280px;
                height: 150px;
            }
            .center-icons a img{
                width: 28px;
            }
            .main-content img {
                max-width: 350px;
            }
        }

        hr {
            max-width: 820px;
            margin: 0 auto;
        }

        .fixed-top{
            padding: 0 !important;
            background-color: #FFCC00 !important; /* e8be1e */
            height: 52px;
            margin: 0 auto;
        }
        .fixed-bottom{
            padding: 0 !important;
            background-color: #d5d0ca !important;
            margin: 0 auto;
        }
        .container .row {
            padding:0;
            margin: 0 auto;
        }
        .framed {
            background-color: #e50201;
            color: #FFCC00;
            font-size: 1.8em;
            margin: 13px auto;
            display: block;
            max-width: 80%;
            padding: 8px 10px;
            border-radius: 13px;
            line-height: 150%;
        }

    </style>

</head>

<body>

<div class="container fixed-top">
    <div class="row" style="max-width: 1200px;">
        <div class="col-12">
            <div class="logo_div"><img src="/img/mcbroke/McBroke-logo@2x.png"></div>
            <div class="call_to_action"><a class="btn btn-white" href="javascript:alert('Launching Soon...')"><i class="fas fa-exchange"></i> Buy Now</a></div>
        </div>
    </div>
</div>


<div class="container main-content" style="text-align: center; padding-bottom:147px !important;">

    <div class="row justify-content-center" style="padding:110px 0 34px; max-width: 800px;">

        <img src="/img/mcbroke/McBroke-logo-Wojak%402x.png" style="margin: 21px 0; max-width: 300px; ">

        <div class="col-12">
            <h1 style="color: #e50201; font-size: 3.5em; font-weight: bold;">Welcome to McBroke.</h1>
            <h3 style=" color: #000000; font-size: 1.1em;">The Binance Smart Chain's home of the GibRope Sandwich & The McRekt.</h3> <br />
            <a class="btn btn-white" href="javascript:alert('Launching Soon...')"><i class="fas fa-exchange"></i> Buy Now</a>
            <a class="btn btn-white" href="#learn"><i class="fas fa-info"></i> Learn More</a><br />
            <br/>

            <h3 class="framed">Ba Da Ba Ba Bah, <span style="display: inline-block;">We goin' to make it!</span></h3>
            <br />
            <h3 style="color: #000000; font-size: 1.3em; font-weight: bold;">Now Accepting Applications <i class="fas fa-door-open"></i></h3>




            <p style="font-size: 1.1em;">Or see details below for entry.</p><br /><br /><br />

            <a name="learn">&nbsp;</a><br />
            <br /><br /><hr /><br /><br />
            <div class="row justify-content-center">
                <div class="col-12 col-sm-4">

                    <img src="/img/mcbroke/skeeping.jpg" class="rounded-corner" style="max-width:100%;">
                </div>
                <div class="col-12 col-sm-8" style= "font-size: 1.5em; line-height: 110%; text-align: left;">
                    <br />
                    <br />
                    We are a community of once rich now broke investors wandering crypto looking to make it back to the 🔝
                    <br />
                    <br />
                    Together we will be rich again and we can finally quit our wagecuck jobs and find our way back to wife-changing 💸

                </div>
            </div>
        </div>
    </div>
    <br />
    <p>
        <a class="btn btn-white" href="#tokenomics"><i class="fas fa-coins"></i> Tokenomics</a>
        <a class="btn btn-white" href="#howtobuy"><i class="fas fa-usd-circle"></i> How to Buy</a>
        <a class="btn btn-white" href="#roadmap"><i class="fas fa-clipboard-list-check"></i> Roadmap</a>
        <a class="btn btn-white" href="#team"><i class="fas fa-users"></i> Team</a>
    </p>
    <?php echo $social_nav ?>

    <br />
    <br />


    <br />
    <br />
    <br />
    <hr />
    <br />
    <br />
    <a name="tokenomics">&nbsp;</a>
    <h2 class="text-center main_title">$BROKE Tokenomics</h2>
    <p style="color: #e50201 !important; font-weight: bold;">Would you like gains with that?</p>
    <img src="/img/mcbroke/serve.jpg" class="rounded-corner">

    <br />
    <div class="row justify-content-center" style="text-align: center; color: #000;">
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover">2%</div>
            <div class="info_box_title" style="color: #e50201 !important;">Reflections</div>
            <div class="info_box_message">Simply hold and earn more McBroke passively!</div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover">4%</div>
            <div class="info_box_title" style="color: #e50201 !important;">Buybacks</div>
            <div class="info_box_message">We burn a specific amount of supply on milestones</div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover">7%</div>
            <div class="info_box_title" style="color: #e50201 !important;">Marketing</div>
            <div class="info_box_message">So the world hears about our community on Binance</div>
        </div>
    </div>
    <br />
    <br />

    <p style="text-align: center; color: #000 !important; font-weight: bold; font-size: 1.1em">We serve you our 24/7 model to sustain the growth and success of the McBroke ecosystem. Additionally, 5% of the 2% reflections tax gets burned with every order.</p>

    <br />
    <br />
    <p>
        <a class="btn btn-white" href="javascript:alert('Coming Soon...')"><i class="fas fa-file-certificate"></i> Contract</a>
        <a class="btn btn-white" href="javascript:alert('Coming Soon...')"><i class="fas fa-lock"></i> IP Lock</a>
        <a class="btn btn-white" href="javascript:alert('Coming Soon...')"><i class="fas fa-square"></i> Renounce</a>
    </p>

    <br />
    <br />
    <hr />
    <br />
    <br />
    <h2 class="text-center main_title">The $BROKE House Project</h2>
    <br />
    <p style="text-align: center; color: #000 !important;">We will donate project funds from order fees to homeless charities. Every month we will look to make a donation to a charity that is voted by our community.</p>
    <img src="/img/mcbroke/home.jpg" class="rounded-corner" style="margin: 0;">








    <br />
    <br />
    <hr />
    <br />
    <a name="howtobuy">&nbsp;</a>
    <h2 class="text-center main_title">How To Be $BROKE?</h2>
    <br />
    <p style="color: #e50201 !important; font-weight: bold; font-size: 1.5em;" >in 3 easy steps...</p>
    <img src="/img/mcbroke/window-order.jpg" class="rounded-corner">

    <div style="text-align: center; position: relative; display: none;">
        <a href="https://www.youtube.com/watch?v=KpF41eS3YZQ" target="_blank" class="htb-link-video"><img src="https://www.babycake.app/images/play.svg" loading="lazy" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;" alt="" class="play">
            <div style="opacity: 0;" class="video-overlay"></div>
        </a>
    </div>


    <div class="row justify-content-center">
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover"><img src="/img/mcbroke/metamask-2728406-2261817.png" style="width:110px;"></div>
            <div class="info_box_title">Setup MetaMask</div>
            <div class="info_box_message" style="text-align: left;">
                <ul>
                    <li>
                        Download MetaMask or TrustWallet.
                    </li>
                    <li>
                        Add the Binance Smart Chain to your network-list.
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover"><img src="/img/mcbroke/coinbase.png" style="width:110px;" class="round"></div>
            <div class="info_box_title">Buy & Send BNB</div>
            <div class="info_box_message" style="text-align: left;">
                <ul>
                    <li>
                        Buy BNB on an exchange. (i.e. Binance, Kraken, Coinbase etc.).
                    </li>
                    <li>
                        Transfer the tokens to your MetaMask wallet address. BEP-20 addresses start with a "0x".
                    </li>
                </ul>

            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover"><img src="/img/mcbroke/pancakeswap-cake-logo.png" style="width:110px;"></div>
            <div class="info_box_title">Swap on Pancake</div>
            <div class="info_box_message" style="text-align: left;">

                <ul>
                    <li>
                        You can <a class="btn btn-white" href="#howtobuy">BUY NOW</a> on PancakeSwap.
                    </li>
                    <li>
                        Select $BROKE or copy/paste contract address.
                    </li>
                    <li>
                        Set slippage tolerance to 12-18%
                    </li>

                </ul>

            </div>
        </div>
    </div>


    <br />
    <br />
    <br />
    <br />
    <a name="roadmap">&nbsp;</a>



    <div style="text-align: center; color: #000; background-color: #FFCC00; padding: 21px 0 55px;">

        <h2 class="text-center main_title">Our $BROKE Roadmap</h2>
        <div style="text-align: center;"><img src="/img/mcbroke/mirror.jpg" class="rounded-corner"></div>

        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><i class="fad fa-coffee-pot"></i></div>
                <div class="info_box_title">Phase 1</div>
                <div class="info_box_message" style="text-align: left; margin-left: 13px">
                    <i class="fas fa-check-circle"></i> Website V1
                    <br /><i class="fas fa-check-circle"></i> Lock & Burn Tokens
                    <br /><i class="fal fa-circle"></i> List on CMC & CG
                    <br /><i class="fal fa-circle"></i> Contract Audits
                    <br /><i class="fal fa-circle"></i> Rewards Dashboard
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><i class="fad fa-french-fries"></i></div>
                <div class="info_box_title">Phase 2</div>
                <div class="info_box_message" style="text-align: left; margin-left: 13px">
                    <i class="fal fa-circle"></i> Website V2
                    <br /><i class="fal fa-circle"></i> Social Campaigns
                    <br /><i class="fal fa-circle"></i> Promotional Contests
                    <br /><i class="fal fa-circle"></i> The $BROKE House Project
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><i class="fad fa-burger-soda"></i></div>
                <div class="info_box_title">Phase 3</div>
                <div class="info_box_message" style="text-align: left; margin-left: 13px">
                    <i class="fal fa-circle"></i> The McBroke DAO
                    <br /><i class="fal fa-circle"></i> The McBroke NFT
                    <br /><i class="fal fa-circle"></i> More Partnerships
                    <br /><i class="fal fa-circle"></i> Livestream Giveaways</div>
            </div>
        </div>
    </div>





    <br />
    <br />
    <br />
    <br />
    <a name="team">&nbsp;</a>
    <h2 class="text-center main_title">You $BROKE? We are too, join us!</h2>
    <img src="/img/mcbroke/apply.jpg" class="rounded-corner" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">



    <br />
    <br />
    <br />
    <br />

    <h2 class="text-center main_title">Our $BROKE Team</h2>

    <div class="row justify-content-center" style="text-align: center; color: #000; ">

        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover"><img src="/img/mcbroke/oero.jpg" style="height:150px;"></div>
            <div class="info_box_title">McFlurry</div>
            <div class="info_box_message">CTO & Developer</div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover"><img src="/img/mcbroke/chicken.jpg" style="height:150px;"></div>
            <div class="info_box_title">McNuggets</div>
            <div class="info_box_message">CMO & Product</div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
            <div class="info_box_cover"><img src="/img/mcbroke/rib.jpg" style="height:150px;"></div>
            <div class="info_box_title">McRib</div>
            <div class="info_box_message">CCO & Community</div>
        </div>
    </div>


    <br />
    <br />
    <br />
    <br />
    <img src="/img/mcbroke/McBroke-trucker-hat@2x.jpg" style="max-width: 300px; margin: 34px 0; ">
    <br />
    <br />
    <br />
    <br />

    <a name="team">&nbsp;</a>
    <?php echo $social_nav ?>

    <br />
    <br />
    <p style="font-size: 0.8em; color: #CCC !important;">All rights not reserved, only some of them ✌️</p>


</div><!-- Container -->


<div class="container fixed-bottom hidden">
    <div class="row">
        <div class="col-12">

        </div>
    </div>
</div>


</body>
</html>
