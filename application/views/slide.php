<?php

//Requires a user $user_session to be logged in so their view history can be loaded

//Load the entire JSON object of the input post tree to be able to build our 1-page JS app:
$post_tree = object_to_array(json_decode(file_get_contents('https://mench.com/json/' . $focus_post['posthashtag'])));


?>


<style>
    body, html {
        height: 100%;
        margin: 0;
        overflow: hidden;
        background: #000;
        touch-action: manipulation;
    }

    .tiktok-container {
        height: 100vh;
        width: 100%;
        overflow-y: scroll;
        scroll-snap-type: y mandatory;
        -webkit-overflow-scrolling: touch;
    }

    .post {
        height: 100vh;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        scroll-snap-align: start;
        position: relative;
        overflow: hidden;
    }

    .post-content {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        display: block;
    }

    .video-post, .photo-post {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .album-container {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .album-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: none;
    }

    .album-img.active {
        display: block;
    }

    .album-nav {
        position: absolute;
        top: 50%;
        width: 100%;
        display: flex;
        justify-content: space-between;
        transform: translateY(-50%);
    }

    .album-nav button {
        background: rgba(0, 0, 0, 0.5);
        border: none;
        color: white;
        padding: 10px;
        cursor: pointer;
    }

    .caption {
        position: absolute;
        bottom: 20px;
        left: 20px;
        color: white;
        background: rgba(0, 0, 0, 0.7);
        padding: 5px 10px;
        border-radius: 5px;
    }
</style>
</head>
<body>
<div class="tiktok-container">
    <!-- Post 1: Tall Video -->
    <div class="post">
        <video class="post-content video-post" controls>
            <source src="https://www.pexels.com/download/video/6789777/" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="caption">Tall video: Nature waterfall</div>
    </div>
    <!-- Post 2: Wide Video -->
    <div class="post">
        <video class="post-content video-post" controls>
            <source src="https://www.pexels.com/download/video/3195397/" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="caption">Wide video: City skyline</div>
    </div>
    <!-- Post 3: Single Tall Photo -->
    <div class="post">
        <img class="post-content photo-post" src="https://images.pexels.com/photos/462162/pexels-photo-462162.jpeg"
             alt="Tall photo">
        <div class="caption">Single tall photo: Mountain view</div>
    </div>
    <!-- Post 4: Single Wide Photo -->
    <div class="post">
        <img class="post-content photo-post" src="https://images.pexels.com/photos/933054/pexels-photo-933054.jpeg"
             alt="Wide photo">
        <div class="caption">Single wide photo: Beach sunset</div>
    </div>
    <!-- Post 5: Album Tall Photos -->
    <div class="post">
        <div class="album-container">
            <img class="album-img active" src="https://images.pexels.com/photos/1363876/pexels-photo-1363876.jpeg"
                 alt="Tall album 1">
            <img class="album-img" src="https://images.pexels.com/photos/1024960/pexels-photo-1024960.jpeg"
                 alt="Tall album 2">
            <img class="album-img" src="https://images.pexels.com/photos/1054289/pexels-photo-1054289.jpeg"
                 alt="Tall album 3">
            <div class="album-nav">
                <button class="prev">←</button>
                <button class="next">→</button>
            </div>
            <div class="caption">Tall photo album: Landscapes</div>
        </div>
    </div>
    <!-- Post 6: Album Wide Photos -->
    <div class="post">
        <div class="album-container">
            <img class="album-img active" src="https://images.pexels.com/photos/161246/pexels-photo-161246.jpeg"
                 alt="Wide album 1">
            <img class="album-img" src="https://images.pexels.com/photos/572897/pexels-photo-572897.jpeg"
                 alt="Wide album 2">
            <img class="album-img" src="https://images.pexels.com/photos/1058759/pexels-photo-1058759.jpeg"
                 alt="Wide album 3">
            <div class="album-nav">
                <button class="prev">←</button>
                <button class="next">→</button>
            </div>
            <div class="caption">Wide photo album: Cityscapes</div>
        </div>
    </div>
    <!-- Post 7: Tall Video -->
    <div class="post">
        <video class="post-content video-post" controls>
            <source src="https://www.pexels.com/download/video/6789784/" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="caption">Tall video: Forest stream</div>
    </div>
    <!-- Post 8: Wide Video -->
    <div class="post">
        <video class="post-content video-post" controls>
            <source src="https://www.pexels.com/download/video/3195592/" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="caption">Wide video: Ocean waves</div>
    </div>
    <!-- Post 9: Single Tall Photo -->
    <div class="post">
        <img class="post-content photo-post" src="https://images.pexels.com/photos/1323550/pexels-photo-1323550.jpeg"
             alt="Tall photo">
        <div class="caption">Single tall photo: Forest path</div>
    </div>
    <!-- Post 10: Single Wide Photo -->
    <div class="post">
        <img class="post-content photo-post" src="https://images.pexels.com/photos/346529/pexels-photo-346529.jpeg"
             alt="Wide photo">
        <div class="caption">Single wide photo: Desert dunes</div>
    </div>
</div>

<!-- Custom JS -->
<script>
    $(document).ready(function () {
        // Pause all videos when scrolling to a new post
        $('.tiktok-container').on('scroll', function () {
            $('video').each(function () {
                $(this)[0].pause();
            });
        });

        // Album navigation
        $('.album-container').each(function () {
            const $container = $(this);
            const $images = $container.find('.album-img');
            let currentIndex = 0;

            function showImage(index) {
                $images.removeClass('active').eq(index).addClass('active');
            }

            $container.find('.next').on('click', function () {
                currentIndex = (currentIndex + 1) % $images.length;
                showImage(currentIndex);
            });

            $container.find('.prev').on('click', function () {
                currentIndex = (currentIndex - 1 + $images.length) % $images.length;
                showImage(currentIndex);
            });
        });

        // Play video when it comes into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const video = $(entry.target).find('video')[0];
                if (video) {
                    if (entry.isIntersecting) {
                        video.play();
                    } else {
                        video.pause();
                    }
                }
            });
        }, {threshold: 0.5});

        $('.post').each(function () {
            observer.observe(this);
        });
    });
</script>
