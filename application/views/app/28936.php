
<script>

    var load_time = 42000;
    load_time = 400;
    $(document).ready(function(){
        scale();

        setTimeout(function () {
            $('.fixed-intro').removeClass('hidden');
            $('.starwars-page').addClass('hidden');
        }, load_time);

    })
    var size=3;
    var posY = 200;
    var ang = 60;
    var delta =1;
    var scaleDelta = 0.008;
    var speed = 50;

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
    }
    .fixed-p {
        padding: 0 20px;
        margin-bottom: 34px;
    }
    .btn-yellow i {
        color: #e8be1e !important;
    }
    .btn-yellow {
        border: 3px solid #e8be1e !important;
        color: #e8be1e !important;
        margin-bottom: 3px;
    }
</style>


<div class="starwars-page">
    <div class="starwars-intro">

        <h1 class="text-center">AnakInu</h1>
        <br>
        <p>It is a period of crypto unrest.</p>
        <br>
        <p>Discovered as the leader of the Inus by Obi-Wan Kenobi, AnakInu has the potential to become one of the most powerful JeDoges ever, and was believed by some to be the prophesied Chosen One who would bring balance to the crypto world.</p>
        <br>
        <p>A hero of the clone wars, Anakin was caring and compassionate, but also had a fear of loss that would prove to be his downfall. Nothing will EVER stop the great AnakInu from achieving greatness.</p>
        <br>
        <p>AnakInu plans to take over all Inus and the Binance network by storm. Nothing will EVER stop the great AnakInu from achieving greatness.</p>

    </div>
</div>




<div class="fixed-intro hidden">
    <h1 class="text-center">AnakInu</h1>
    <p class="fixed-p">Discovered as the leader of the Inus by Obi-Wan Kenobi, AnakInu has the potential to become one of the most powerful JeDoges ever, and was believed by some to be the prophesied Chosen One who would bring balance to the crypto world.</p>
    <p class="fixed-p"><img src="https://s3foundation.s3-us-west-2.amazonaws.com/782cac4db51caf39afc04e3b2c0e66c3.jpeg" style="max-width: 300px;"></p>
    <p>
        <a class="btn btn-yellow" href="#"><i class="fab fa-telegram"></i> Telegram</a>
        <a class="btn btn-yellow" href="#"><i class="fab fa-twitter"></i> Twitter</a>
        <a class="btn btn-yellow" href="#"><i class="far fa-file-certificate"></i> White Paper</a>
        <a class="btn btn-yellow" href="#"><i class="far fa-chart-line"></i> Chart</a>
        <a class="btn btn-yellow" href="#"><i class="far fa-usd-circle"></i> How to Buy</a>
        <a class="btn btn-yellow" href="#"><i class="far fa-shopping-cart"></i> Buy Now</a>
    </p>
    <br />
    <br />

    <div class="row text-center">
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
            <div class="info_box_title css__title">General Development</div>
            <div class="info_box_message">All costs regarding project development</div>
        </div>
    </div>


    <br />
    <br />

    <h2>Burning Mechanism</h2>
    <p>We burn a specific amount of supply on milestones. Additionally, 5% of the 1% Redistribution tax gets burned with every transaction.</p>



</div>