<div class="container" style="width: 100%;">

    <h1 class="no-margin"><a href="/play/signin" class="play"><span class="play_title"></span> <span class="play_content"></span></a></h1>
    <h1 class="no-margin"><a href="/read" class="read"><span class="read_title"></span> <span class="read_content"></span></a></h1>
    <h1 class="no-margin"><a href="/blog" class="blog"><span class="blog_title"></span> <span class="blog_content"></span></a></h1>

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
        return getRandomInt(5,55);
        //return 50;
    }

    function animate(){

        //Terminology Index
        var play_titls = ['PLAY', 'EARN', 'UNLOCK', 'RANK'];
        var play_terms = ['a learning-game', 'a free-game', 'a crypto-game', 'crypto-coins', 'SUPERPOWERS', 'in leaderboard'];
        var read_titls = ['READ', 'DISCOVER', 'LEARN'];
        var read_terms = ['top ideas', 'on the web', 'on Messenger', '5K words/mo free', 'all for $5/mo', 'interactively'];
        var blog_titls = ['BLOG', 'CREATE', 'WRITE'];
        var blog_terms = ['single ideas', 'on the web', 'for Messenger', 'for cash income', 'collaboratively'];

        //The Story
        var tl = tempo(0);

        new TypeIt('.play_title', { speed:speed(), startDelay:tl+=tempo(1) }).type(play_titls[0]).go().destroy();
        new TypeIt('.read_title', { speed:speed(), startDelay:tl+=tempo(1) }).type(read_titls[0]).go().destroy();
        new TypeIt('.blog_title', { speed:speed(), startDelay:tl+=tempo(1) }).type(blog_titls[0]).go().destroy();

        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).type(blog_terms[0]).pause(tempo(1)).go().destroy();
        new TypeIt('.read_content', { speed:speed(), startDelay:tl+=tempo(2) }).type(read_terms[0]).pause(tempo(1)).go().destroy();
        new TypeIt('.play_content', { speed:speed(), startDelay:tl+=tempo(2) }).type(play_terms[0]).pause(tempo(1)).go().destroy();

        new TypeIt('.read_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type(read_titls[1]).pause(tempo(1)).go().destroy();
        new TypeIt('.read_title', { speed:speed(), startDelay:tl+=tempo(1) }).delete().type(read_titls[2]).pause(tempo(1)).go().destroy();

        new TypeIt('.read_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type(read_terms[1]).pause(tempo(1)).go().destroy();

        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type(blog_terms[1]).pause(tempo(1)).go().destroy();
        new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).delete().type(blog_terms[2]).pause(tempo(0)).go().destroy();
    }


    $(document).ready(function () {
        animate();
    });

</script>