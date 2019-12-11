<div class="container" style="width: 100%;">

    <h1 class="no-margin"><a href="/play/signin" class="play"><span class="play_title" style="text-decoration: none;"></span> <span class="play_content"></span></a></h1>
    <h1 class="no-margin"><a href="/read" class="read"><span class="read_title" style="text-decoration: none;"></span> <span class="read_content"></span></a></h1>
    <h1 class="no-margin"><a href="/blog" class="blog"><span class="blog_title" style="text-decoration: none;"></span> <span class="blog_content"></span></a></h1>

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
        return getRandomInt(50,100);
        //return 50;
    }

    function animate(){

        //The Story

        var tl = tempo(0);

        new TypeIt('.play_title', { speed:speed(), startDelay:tl+=tempo(1) }).type('PLAY').go().destroy();
        new TypeIt('.read_title', { speed:speed(), startDelay:tl+=tempo(1) }).type('READ').go().destroy();
        new TypeIt('.blog_title', { speed:speed(), startDelay:tl+=tempo(1) }).type('BLOG').go().destroy();

        new TypeIt('.play_content', { speed:speed(), startDelay:tl+=tempo(2) }).type('a free-game').pause(tempo(1)).go().destroy();
        new TypeIt('.read_content', { speed:speed(), startDelay:tl+=tempo(2) }).type('top ideas').pause(tempo(1)).go().destroy();
        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).type('single ideas').pause(tempo(1)).go().destroy();

        new TypeIt('.play_content', { speed:speed(), startDelay:tl+=tempo(2) }).type('a learning-game').pause(tempo(1)).go().destroy();


        new TypeIt('.read_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('DISCOVER').pause(tempo(1)).go().destroy();
        new TypeIt('.read_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('LEARN').pause(tempo(1)).go().destroy();

        new TypeIt('.read_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('on the web').pause(tempo(1)).go().destroy();

        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('on the web').pause(tempo(1)).go().destroy();
        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('for Messenger').pause(tempo(0)).go().destroy();

        new TypeIt('.read_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('on Messenger').pause(tempo(1)).go().destroy();

        new TypeIt('.play_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('UNLOCK').pause(tempo(1)).go().destroy();
        new TypeIt('.play_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('SUPERPOWERS').pause(tempo(1)).go().destroy();

        new TypeIt('.play_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('EARN').pause(tempo(1)).go().destroy();
        new TypeIt('.play_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('crypto-coins').pause(tempo(1)).go().destroy();

        new TypeIt('.play_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('RANK').pause(tempo(1)).go().destroy();
        new TypeIt('.play_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('in leaderboard').pause(tempo(1)).go().destroy();


        new TypeIt('.read_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('READ').pause(tempo(1)).go().destroy();
        new TypeIt('.read_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('5K words/mo free').pause(tempo(1)).go().destroy();
        new TypeIt('.read_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('all for $5/mo').pause(tempo(1)).go().destroy();


        new TypeIt('.blog_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('CREATE').pause(tempo(1)).go().destroy();
        new TypeIt('.blog_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('WRITE').pause(tempo(1)).go().destroy();
        new TypeIt('.blog_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type('BLOG').pause(tempo(1)).go().destroy();
        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('for income/mo').pause(tempo(1)).go().destroy();
        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type('collaboratively').pause(tempo(1)).go().destroy();



    }


    $(document).ready(function () {
        animate();
    });

</script>