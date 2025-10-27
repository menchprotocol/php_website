<?php

//Load the entire JSON object of the input post tree to be able to build our 1-page JS app:
$post_tree = object_to_array(json_decode(file_get_contents('https://mench.com/json/' . $focus_post['posthashtag'])));


?>

<style>
    /* CSS HERE */
</style>

<script>
    /* JS HERE */
</script>


<!-- HTML HERE -->
<div>Hello Slide</div>
<br/>
<div><?= nl2br(print_r($user_session, true)); ?></div>


<!-- Custom CSS -->
<style>

    .gallery-container {
        height: 100vh;
        overflow-y: auto;
        scroll-snap-type: y mandatory;
        scrollbar-width: none; /* Firefox */
    }

    .gallery-container::-webkit-scrollbar {
        display: none; /* Chrome, Safari */
    }

    .gallery-item {
        height: 100vh;
        width: 100%;
        position: relative;
        scroll-snap-align: start;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
    }

    .gallery-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .caption {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        max-width: 80%;
    }

    .nav-buttons {
        position: fixed;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .nav-buttons button {
        background: rgba(0, 0, 0, 0.5);
        border: none;
        color: white;
        padding: 10px;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.3s;
    }

    .nav-buttons button:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    @media (max-width: 576px) {
        .caption {
            font-size: 0.9rem;
            padding: 8px 12px;
        }

        .nav-buttons {
            right: 10px;
        }
    }
</style>


<div class="gallery-container">
    <div class="gallery-item">
        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e" class="gallery-img" alt="Beach">
        <div class="caption">Tropical beach paradise 🏖️</div>
    </div>
    <div class="gallery-item">
        <img src="https://images.unsplash.com/photo-1519681393784-d120267933ba" class="gallery-img" alt="Mountains">
        <div class="caption">Majestic mountain peaks 🏔️</div>
    </div>
    <div class="gallery-item">
        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e" class="gallery-img" alt="Beach">
        <div class="caption">City skyline at dusk 🌃</div>
    </div>
    <div class="gallery-item">
        <img src="https://images.unsplash.com/photo-1519681393784-d120267933ba" class="gallery-img" alt="Mountains">
        <div class="caption">Golden sunset glow 🌅</div>
    </div>
</div>
<div class="nav-buttons">
    <button id="prevBtn" title="Previous">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                  d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z"/>
        </svg>
    </button>
    <button id="nextBtn" title="Next">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                  d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
        </svg>
    </button>
</div>


<!-- Custom JS -->
<script>
    $(document).ready(function () {
        const $container = $('.gallery-container');
        const $items = $('.gallery-item');
        let currentIndex = 0;

        function scrollToItem(index) {
            if (index >= 0 && index < $items.length) {
                $items[index].scrollIntoView({behavior: 'smooth'});
                currentIndex = index;
            }
        }

        $('#nextBtn').click(function () {
            if (currentIndex < $items.length - 1) {
                scrollToItem(currentIndex + 1);
            }
        });

        $('#prevBtn').click(function () {
            if (currentIndex > 0) {
                scrollToItem(currentIndex - 1);
            }
        });

        // Optional: Add touch swipe support for mobile
        let touchStartY = 0;
        $container.on('touchstart', function (e) {
            touchStartY = e.originalEvent.touches[0].clientY;
        });

        $container.on('touchend', function (e) {
            const touchEndY = e.originalEvent.changedTouches[0].clientY;
            const deltaY = touchStartY - touchEndY;
            if (deltaY > 50) {
                scrollToItem(currentIndex + 1);
            } else if (deltaY < -50) {
                scrollToItem(currentIndex - 1);
            }
        });
    });
</script>
