
<script>

    $(document).ready(function(){
        scale();
    })
    var size=3;
    var posY = 180;
    var ang = 60;
    var delta =1;
    var scaleDelta = 0.008;
    var speed = 100;

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
            $(".starwars-intro").animate({opacity:"0"},1000);
        }
    }

</script>
<style>
    body, html {
        margin: 0;
        padding: 0;
        background: #000 url('https://kassellabs.us-east-1.linodeobjects.com/static-assets/websites/star-wars/bg-stars.png') center;
        font-size: 16px;
        font-family: impact;
        font-weight: 500;
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
        max-width: 400px;
        position: relative;
        font-size: 1.8em;
        color: #e8be1e;
        text-align: justify;
        transform: rotateX(30deg) scale(2);
    }
</style>
<div class="starwars-page">
    <div class="starwars-intro">
        <h1 class="text-center">AnakInu</h1>
        <br>
        <p>It is a period of crypto unrest.</p>
        <br>
        <p>Discovered as the leader of the Inus by Obi-Wan Kenobi, AnakInu has the potential to become one of the most powerful JeDoge ever, and was believed by some to be the prophesied Chosen One who would bring balance to the metaverse.</p>
        <br>
        <p>A hero of the Clone Wars, Anakin was caring and compassionate, but also had a fear of loss that would prove to be his downfall. Nothing will EVER stop the great AnakInu from achieving greatness.</p>
        <br>
        <p>AnakInu possesses mighty force and intelligence, as well as being capable of understanding and speaking human languages. She plans to take over all Inus and the Binance network by storm. Nothing will EVER stop the great AnakInu from achieving greatness.</p>



    </div>
</div>