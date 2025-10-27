<?php

//Requires a user $user_session to be logged in so their view history can be loaded

//Load the entire JSON object of the input post tree to be able to build our 1-page JS app:
$post_tree = object_to_array(json_decode(file_get_contents('https://mench.com/json/' . $focus_post['posthashtag'])));


?>


    <style>
        body, html {
            height: 100%;
            margin: 0;
            background: #000;
            overflow: hidden;
        }
        .slider-container {
            height: 100%;
            width: 100%;
            position: relative;
        }
        .slide {
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000;
        }
        .slide img, .slide video {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }
        .hidden { display: none; }
        .album {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            height: 100%;
            align-items: center;
        }
        .album img {
            max-height: 90%;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="slider-container">
    <!-- Example slides -->
    <div class="slide" data-index="0">
        <video src="https://www.w3schools.com/html/mov_bbb.mp4" autoplay loop muted></video>
    </div>
    <div class="slide hidden" data-index="1">
        <video src="https://www.w3schools.com/html/movie.mp4" autoplay loop muted></video>
    </div>
    <div class="slide hidden" data-index="2">
        <img src="https://picsum.photos/600/1000" alt="tall photo" />
    </div>
    <div class="slide hidden" data-index="3">
        <img src="https://picsum.photos/1000/600" alt="wide photo" />
    </div>
    <div class="slide hidden" data-index="4">
        <div class="album">
            <img src="https://picsum.photos/600/900?random=1" />
            <img src="https://picsum.photos/600/900?random=2" />
            <img src="https://picsum.photos/600/900?random=3" />
        </div>
    </div>
    <div class="slide hidden" data-index="5">
        <div class="album">
            <img src="https://picsum.photos/1000/600?random=4" />
            <img src="https://picsum.photos/1000/600?random=5" />
            <img src="https://picsum.photos/1000/600?random=6" />
        </div>
    </div>
    <div class="slide hidden" data-index="6">
        <video src="https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.mp4" autoplay loop muted></video>
    </div>
    <div class="slide hidden" data-index="7">
        <img src="https://picsum.photos/700/1200" alt="tall photo 2" />
    </div>
    <div class="slide hidden" data-index="8">
        <img src="https://picsum.photos/1200/700" alt="wide photo 2" />
    </div>
    <div class="slide hidden" data-index="9">
        <div class="album">
            <img src="https://picsum.photos/600/900?random=7" />
            <img src="https://picsum.photos/600/900?random=8" />
            <img src="https://picsum.photos/600/900?random=9" />
        </div>
    </div>
</div>

<script>
    let currentIndex = 0;
    const totalSlides = $(".slide").length;

    function showSlide(index) {
        $(".slide").addClass("hidden");
        const currentSlide = $(".slide[data-index="+index+"]");
        currentSlide.removeClass("hidden");

        // Pause all videos except current
        $("video").each(function(){ this.pause(); });
        currentSlide.find("video").each(function(){ this.play(); });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalSlides;
        showSlide(currentIndex);
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        showSlide(currentIndex);
    }

    $(document).on("keydown", function(e) {
        if (e.key === "ArrowDown" || e.key === "ArrowRight") nextSlide();
        if (e.key === "ArrowUp" || e.key === "ArrowLeft") prevSlide();
    });

    let startY = 0;
    $(document).on("touchstart", function(e){
        startY = e.originalEvent.touches[0].clientY;
    });
    $(document).on("touchend", function(e){
        let endY = e.originalEvent.changedTouches[0].clientY;
        if (startY - endY > 50) nextSlide();
        if (endY - startY > 50) prevSlide();
    });

    showSlide(currentIndex);
</script>


