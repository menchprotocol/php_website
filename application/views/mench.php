<style>
    .container{
        max-width: none;
    }

    @media (max-width:767px) { h1{ font-size: 2.6em; } }
    @media (max-width:999px) { h1{ font-size: 3.2em; } }
    @media (max-width:1500px) { h1{ font-size: 4.2em; } }
    @media (max-width:1900px) { h1{ font-size: 4.7em; } }

</style>

<div class="container">

    <h1 class="no-margin"><a href="/play/signin" class="play" style="text-decoration: none;"><span class="play_title"></span> <span class="play_content"></span></a></h1>
    <h1 class="no-margin"><a href="/read" class="read" style="text-decoration: none;"><span class="read_title"></span> <span class="read_content"></span></a></h1>
    <h1 class="no-margin"><a href="/blog" class="blog" style="text-decoration: none;"><span class="blog_title"></span> <span class="blog_content"></span></a></h1>

</div>
<script>

    function tempo(beat){
        if(beat==0){
            return getRandomInt(0,987);
        } else if(beat==1){
            return getRandomInt(987,4181);
        } else if(beat==2){
            return getRandomInt(2584,6765);
        }
    }

    function speed(){
        return getRandomInt(55,89);
        //return 50;
    }

    function animate(){

        //The Story

        var tl = tempo(0);

        new TypeIt('.play_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).type('PLAY').go();
        new TypeIt('.read_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).type('READ').go();
        new TypeIt('.blog_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).type('BLOG').go();

        new TypeIt('.play_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).type('a social game').pause(tempo(1)).go();
        new TypeIt('.read_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).type('relevant ideas').pause(tempo(1)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).type('your ideas').pause(tempo(1)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('anyone\'s ideas').pause(tempo(1)).go();

        new TypeIt('.blog_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('SAVE').pause(tempo(1)).go();
        new TypeIt('.blog_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('ORGANIZE').pause(tempo(0)).go();
        new TypeIt('.blog_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('SHARE').pause(tempo(1)).go();

        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).delete(4).type('stories').pause(tempo(1)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).delete(7).type('inspirations').pause(tempo(1)).go();

        new TypeIt('.read_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('on the web').pause(tempo(1)).go();
        new TypeIt('.read_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('LEARN').pause(tempo(1)).go();

        new TypeIt('.blog_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('WRITE').pause(tempo(1)).go();

        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('on the web').pause(tempo(1)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('for a new world').pause(tempo(2)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('for chat apps').pause(tempo(1)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('for Messenger').pause(tempo(0)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(0) }).delete().type('for WhatsApp').pause(tempo(0)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(0) }).delete().type('for Slack').pause(tempo(0)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('for Alexa').pause(tempo(0)).go();


        new TypeIt('.play_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('superpowers').pause(tempo(1)).go();
        new TypeIt('.play_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('EARN').pause(tempo(0)).go();
        new TypeIt('.play_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(0) }).delete().type('crypto-coins').pause(tempo(1)).go();


        new TypeIt('.blog_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('BLOG').pause(tempo(1)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('collaboratively').pause(tempo(1)).go();

        new TypeIt('.read_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('READ').pause(tempo(0)).go();
        new TypeIt('.read_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('interactively').pause(tempo(1)).go();

        new TypeIt('.play_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('PLAY').go();
        new TypeIt('.play_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(0) }).delete().pause(tempo(2)).type('for the fun of it').pause(tempo(1)).go();

        console.log(tl + ' seconds runtime');

    }


    $(document).ready(function () {
        animate();
    });

</script>