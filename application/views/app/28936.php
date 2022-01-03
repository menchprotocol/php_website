
<script>

    $(document).ready(function(){
        scale();
    })
    var size=3;
    var posY = 230;
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
    @font-face {
        font-family: 'jedi';
        src: URL('/img/StarJedi-DGRW.ttf') format('truetype');
    }
    .jedi {
        font-family: 'jedi' !important;
    }
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
        <h1 class="text-center jedi">VADER</h1>
        <br>
        <p class="jedi">
            It is a period of insurgence. Rebel spaceships, striking from a hidden base on a moon of Yahin, have won a shocking surprise victory against the rightful reign of the Galactic Empire.
        </p>
        <p>
            The Empire's ultimate peacekeeping force, THE DEATH STAR, was destroyed due to an unforseen design flaw. Without this deterrent, the rule of law is in danger. Chaos looms!
        </p>
        <p>
            For the nineteen years after the vaniquishing of the Jedi and his painful rebirth on Volcanic Mustafarm Sith Lord DARTH VADER has faithfully served his master. But now, he has failed the Emperor and must pay the price...
        </p>
    </div>
</div>