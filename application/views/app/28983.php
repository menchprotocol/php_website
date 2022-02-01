<!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8" />
    <meta name="theme-color" content="#f0f0f0">
    <link rel="icon" href="/img/mcbroke/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McBroke</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="//pro.fontawesome.com/releases/v5.15.2/css/all.css">

    <style>
        body, html {
            margin: 0;
            padding: 0;
            background: #FFFFFF;
            font-size: 16px;
        }

        .hidden {
            display: none;
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
            color: #000 !important;
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
            color: #000 !important;
        }
        .btn-white {
            border: 3px solid #000 !important;
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
            padding: 21px 0;
            text-align: center;
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
            height:53px;
        }
        .call_to_action{
            position: fixed;
            top:5px;
            right:5px;
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
            .logo_div img{
                height:66px;
            }
            .htb-link-video {
                width: 560px;
                height: 290px;
            }
        }

        @media (max-width:767px) {
            .logo_div img{
                height:44px;
            }
            .htb-link-video {
                width: 280px;
                height: 150px;
            }
        }


    </style>

</head>

<body style="padding-bottom: 100px;">


<div class="container fixed-top" style="padding-bottom: 0 !important;">
    <div class="logo_div"><img src="/img/mcbroke/McBroke-logo@2x.png"></div>
    <div class="call_to_action"><a class="btn btn-yellow" href="javascript:alert('Launching Soon...')"><i class="fas fa-lock"></i> Buy Now</a></div>
</div>


<div class="container" style="text-align: center; padding-bottom:147px !important;">

    <div class="row justify-content-center" style="padding:55px 0;">
        <div class="col-12 col-md-5">
            <h1 style="color: #e50201; font-size: 3em;">Welcome to McBroke.</h1>
            <p>A community. An idea. A new force. A new technology. A new future.</p>
        </div>
        <div class="col-12 col-md-3">
            <img src="/img/mcbroke/McBroke-trucker-hat@2x.jpg" style="max-width: 300px;">
        </div>
    </div>

        <br />
        <br />
        <p>
            <a class="btn btn-white" href="javascript:alert('Telegram Coming Soon...')"><i class="fab fa-telegram"></i> Telegram</a>
            <a class="btn btn-white" href="#howtobuy"><i class="fas fa-usd-circle"></i> How to Buy</a>
            <a class="btn btn-white" href="#roadmap"><i class="fas fa-route"></i> Roadmap</a>
        </p>

        <br />
        <br />
        <br />
        <br />
        <h2 class="text-center main_title">Tokenomics</h2>
        <div class="row justify-content-center" style="text-align: center; color: #000;">
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover">1%</div>
                <div class="info_box_title">Redistribution</div>
                <div class="info_box_message">Simply hold and earn more McBroke passively!</div>
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
        <p style="text-align: center; color: #000 !important;">We also burn a specific amount of supply on milestones. Additionally, 5% of the 1% Redistribution tax gets burned with every transaction.</p>
        <br />
        <p>
            <a class="btn btn-white" href="javascript:alert('Coming Soon...')"><i class="fas fa-file-certificate"></i> Contract</a>
            <a class="btn btn-white" href="javascript:alert('Coming Soon...')"><i class="fas fa-lock"></i> IP Lock</a>
            <a class="btn btn-white" href="javascript:alert('Coming Soon...')"><i class="fas fa-square"></i> Renounce</a>
        </p>
        <br />
        <div class="center-icons">
            <a href="javascript:alert('Coming Soon...')"><img src="/img/mcbroke/light-bscscan.svg" class="Footer_link__DBs2K" style="background-color:#FFF; border-radius: 50%;"></a>
            <a href="javascript:alert('Coming Soon...')"><img src="/img/mcbroke/light-cmc.svg" class="Footer_link__DBs2K" style="background-color:#FFF; border-radius: 50%;"></a>
            <a href="javascript:alert('Coinbase Listing Coming Soon...')"><img src="/img/mcbroke/coinbase.png" style="border-radius: 50%;"></a>
            <a href="javascript:alert('Coin Gecko Listing Coming Soon...')"><img src="/img/mcbroke/coingecko.svg"></a>
            <a href="javascript:alert('DexTools Link Coming Soon...')"><img src="/img/mcbroke/dextools.svg"></a>
            <a href="javascript:alert('PooCoin Coming Soon...')"><img src="/img/mcbroke/poocoin.svg"></a>
            <a href="javascript:alert('Twitter Coming Soon...')" target="_blank"><img src="/img/mcbroke/twitter.png"></a>
        </div>








        <br />
        <br />
        <br />
        <br />
        <a name="howtobuy">&nbsp;</a>
        <h2 class="text-center main_title">How To Buy</h2>
        <div class="row justify-content-center" style="text-align: center; color: #000;">
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/mcbroke/metamask-2728406-2261817.png" style="width:110px;"></div>
                <div class="info_box_title">Setup MetaMask</div>
                <div class="info_box_message">First download MetaMask (a crypto wallet in the form of a browser extension) or TrustWallet (an app for your phone). After that, you will have to add the Binance Smart Chain to your network-list. (Click here for a step-by-step tutorial).</div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/mcbroke/coinbase.png" style="width:110px;" class="round"></div>
                <div class="info_box_title">Buy & Send BNB</div>
                <div class="info_box_message">Then Buy BNB on an exchange (i.e. Binance, Kraken, Coinbase etc.). Transfer the tokens to your MetaMask wallet address. BEP-20 addresses start with a "0x".</div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/mcbroke/pancakeswap-cake-logo.png" style="width:110px;"></div>
                <div class="info_box_title">Swap on Pancake</div>
                <div class="info_box_message">Finally click here to buy McBroke on PancakeSwap. Select McBroke or use our contract address. Set the slippage tolerance to 12% (sometimes it may be 18%, depending on how much demand there is).</div>
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
        <div class="row justify-content-center" style="text-align: center; color: #000;">
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><i class="fad fa-coffee-pot"></i></div>
                <div class="info_box_title">Phase 1</div>
                <div class="info_box_message">
                    Lock & Burn Tokens
                    <br />Listings CMC+BSCSCAN
                    <br />Contract Audits
                    <br />Rewards Dashboard
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><i class="fad fa-french-fries"></i></div>
                <div class="info_box_title">Phase 2</div>
                <div class="info_box_message">Youtube/Twitter Campaigns
                    <br />McBroke NFT
                    <br />Promotional Contests
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><i class="fad fa-burger-soda"></i></div>
                <div class="info_box_title">Phase 3</div>
                <div class="info_box_message">McBroke DAO
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
        <div class="row justify-content-center" style="text-align: center; color: #000;">
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/mcbroke/crying.jpg" style="height:150px;"></div>
                <div class="info_box_title">McTech</div>
                <div class="info_box_message">CTO & Developer</div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/mcbroke/crying.jpg" style="height:150px;"></div>
                <div class="info_box_title">McSell</div>
                <div class="info_box_message">CMO & Product</div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-xl-2 col-lg-3">
                <div class="info_box_cover"><img src="/img/mcbroke/crying.jpg" style="height:150px;"></div>
                <div class="info_box_title">McTalk</div>
                <div class="info_box_message">CCO & Community Growth</div>
            </div>
        </div>



    </div><!-- Container -->



</body>
</html>