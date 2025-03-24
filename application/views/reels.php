

<style>

    html /* IMPORTANT: scroll-snap-type won't work with body selector, so replace it with html */ {
        background-color: #090909;
        scroll-snap-type: y mandatory; /* Define snapping behavior. "y" indicates vertical behavior, "mandatory" won't allow the user to stay in-between two pages. */
    }

    .video-box {
        display: flex;
        align-items: center;
        scroll-snap-align: start; /* Define snapping target. Can also be "center" or "end". */
    }

    .video-box div {
        box-sizing: border-box;
        padding: 8px;
        margin: 0 auto;
        max-height: 100vh;
        max-width: 100%;
    }
</style>

<h1>Reels</h1>

<section class="videos">
    <div class="video-box">
        <div class="inner-frame">Slide 1</div>
    </div>
    <div class="video-box">
        <div class="inner-frame">Slide 2</div>
    </div>
    <div class="video-box">
        <div class="inner-frame">Slide 3</div>
    </div>
    <div class="video-box">
        <div class="inner-frame">Slide 4</div>
    </div>
</section>
