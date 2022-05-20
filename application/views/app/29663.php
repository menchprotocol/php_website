<!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8" />
    <meta name="theme-color" content="#f0f0f0">
    <link rel="icon" href="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presale Ending Soon</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Quicksand:700|Roboto+Mono:500|Montserrat:800|Rubik|Bitter:800|Roboto+Slab:400|Righteous|Libre+Baskerville|Lato&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="//pro.fontawesome.com/releases/v5.15.2/css/all.css">

    <script>

    </script>
    <style>

        body, html {
            margin: 0;
            padding: 0;
            background: #1b1a22;
            font-size: 16px;
            color: #FFF !important;
        }
        h1 {
            font-family:'Montserrat', sans-serif;
        }
        a {
            color: #FFF !important;
        }
        .btn-white i {
            color: #FFF !important;
        }
        .btn-white {
            color: #FFF !important;
            background-color: #6457d0;
            border-radius: 5px;
        }

        .isbox .btn-white {
            background-color: #1b1a22;
        }
        .isbox a {
            color: #FFF !important;
        }
        .isbox {
            background-color: #bd344c !important;
            color:#FFF !important;
            border-radius: 5px;
            padding: 20px;
        }


    </style>
    <script>
        // Set the date we're counting down to
        var countDownDate = new Date("May 24, 2022 14:00:00").getTime();

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
                document.getElementById("countdown").innerHTML = "PRESALE STARTED";
            }
        }, 1000);
    </script>

</head>

<body style="padding-bottom: 100px;">

<div class="container">


    <p style="font-size:0.8em;">Welcome</p>
    <h1>PRESALE STARTS SOON</h1>

    <br />

    <a class="btn btn-white" href="#">Tokenomics</a>
    <a class="btn btn-white" href="#">Sales Guide</a>

    <br />
    <br />

    <div class="row">
        <div class="isbox col-12 col-sm-6">
            <p>CLAIM & STAKE</p>
            <input type="text" class="form-control border" name="claim_stake" placeholder="1 BNB = 135000000 MM" value="">
            <a class="btn btn-white" style="display: block;" href="#">Contribute</a>
            <hr />
            <p>Your Contributed Amount</p>
            <p>Your Reserved Tokens</p>
        </div>
        <div class="isbox col-12 col-sm-6">
            <p>Presale is about to go live</p>
            <p>Starts in:</p>
            <p id="countdown" style="display: inline-block; font-size: 2em;"></p>
            <p>Exclusively available on Arbitrium Network</p>
            <a class="btn btn-white" href="#">Bridge</a>
        </div>
    </div>
    <div class="row">
        <div class="isbox col-12 col-sm-6">
            <p>POST SALE $MM PRICE</p>
            <p style="font-size: 2em;">$7.70 USD</p>
            <p>Graph here...</p>
        </div>
        <div class="isbox col-12 col-sm-6">
            <p>Total Filled</p>
            <p style="font-size: 2em;">0 / 250 BNB</p>
            <p>Graph here...</p>
        </div>
    </div>

    <br />
    <br />
    <br />

    <p style="text-align: center; color: #FFF !important;">If presale is not sold out, remaining tokens are redistributed back into the rewards pools for staking, farming & activity.</p>

    <div class="row justify-content-center" style="text-align: center; color: #FFF;">
        <div class="col-12 col-sm-6">
            <div class="info_box_cover">50%</div>
            <div class="info_box_title">Liquidity</div>
            <div class="info_box_message">Half of the pre-sale will be auto-sent to support our liquidity pool.</div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="info_box_cover">50%</div>
            <div class="info_box_title">Treasury</div>
            <div class="info_box_message">Half of the pre-sale will be stored in our treasury for further development and marketing.</div>
        </div>
    </div>


</div>

</body>
</html>