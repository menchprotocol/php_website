<div class="container" style="width: 100%;">

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

        new TypeIt('.play_title', { speed:speed(), startDelay:tl+=tempo(1) }).type('PLAY').go().destroy();
        new TypeIt('.read_title', { speed:speed(), startDelay:tl+=tempo(1) }).type('READ').go().destroy();
        new TypeIt('.blog_title', { speed:speed(), startDelay:tl+=tempo(1) }).type('BLOG').go().destroy();

        new TypeIt('.play_content', { speed:speed(), startDelay:tl+=tempo(2) }).type('a social game').pause(tempo(1)).go().destroy();
        new TypeIt('.read_content', { speed:speed(), startDelay:tl+=tempo(2) }).type('relevant ideas').pause(tempo(1)).go().destroy();
        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).type('your ideas').pause(tempo(1)).go().destroy();

        new TypeIt('.blog_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('SAVE').pause(tempo(1)).go().destroy();
        new TypeIt('.blog_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('ORGANIZE').pause(tempo(0)).go().destroy();
        new TypeIt('.blog_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('SHARE').pause(tempo(1)).go().destroy();


        new TypeIt('.read_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('on the web').pause(tempo(1)).go().destroy();
        new TypeIt('.read_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('LEARN').pause(tempo(0)).go().destroy();

        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('on the web').pause(tempo(1)).go();
        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('for the new world').pause(tempo(2)).go().destroy();
        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('for chat apps').pause(tempo(1)).go().destroy();
        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('for Messenger').pause(tempo(0)).go().destroy();
        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('for WhatsApp').pause(tempo(0)).go().destroy();

        new TypeIt('.read_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('READ').pause(tempo(0)).go().destroy();
        new TypeIt('.read_content', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('on Slack').pause(tempo(0)).go().destroy();

        new TypeIt('.play_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('a learning game').pause(tempo(1)).go().destroy();
        new TypeIt('.play_content', { speed:speed(), startDelay:tl+=tempo(0) }).delete().type('SUPERPOWERS').pause(tempo(1)).go();
        new TypeIt('.play_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('EARN').pause(tempo(0)).go().destroy();
        new TypeIt('.play_content', { speed:speed(), startDelay:tl+=tempo(0) }).delete().type('crypto-coins').pause(tempo(1)).go().destroy();


        new TypeIt('.blog_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('BLOG').pause(tempo(1)).go().destroy();
        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('collaboratively').pause(tempo(1)).go().destroy();

        new TypeIt('.read_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('interactively').pause(tempo(1)).go().destroy();

        new TypeIt('.play_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('PLAY').pause(tempo(0)).go().destroy();
        new TypeIt('.play_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().pause(tempo(2)).type('it\'s fun').pause(tempo(1)).go().destroy();

        console.log(tl + ' seconds runtime');

    }


    $(document).ready(function () {
        animate();
    });

</script>