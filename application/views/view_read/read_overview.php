

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

    function stream(){

        $('.no-margin').toggleClass('hidden');

        //The Story

        var tl = tempo(0);

        new TypeIt('.play_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).type('PLAY').go();
        new TypeIt('.read_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).type('READ').go();
        new TypeIt('.blog_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).type('BLOG').go();

        new TypeIt('.play_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).type('a social game').pause(tempo(1)).go();
        new TypeIt('.read_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).type('relevant ideas').pause(tempo(1)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).type('your ideas').pause(tempo(1)).go();
        new TypeIt('.blog_content', { cursor:false, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('ideas that matter').pause(tempo(1)).go();

        new TypeIt('.blog_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('SAVE').pause(tempo(1)).go();
        new TypeIt('.blog_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('ORGANIZE').pause(tempo(0)).go();
        new TypeIt('.blog_title', { cursor:false, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('SHARE').pause(tempo(1)).go();

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

        setTimeout(function () {
            $('.no-margin').toggleClass('hidden');
        }, tl);

    }

</script>

<div class="container">

    <h1 class="no-margin hidden play"<span class="play_title"></span> <span class="play_content"></span></h1>
    <h1 class="no-margin hidden read"><span class="read_title"></span> <span class="read_content"></span></h1>
    <h1 class="no-margin hidden blog"><span class="blog_title"></span> <span class="blog_content"></span></h1>

    <div class="no-margin"><a href="javascript:void(0);" onclick="stream();" class="btn btn-lg btn-play montserrat"><i class="fas fa-play-circle"></i> Watch Animation</a></div>

    <?php
    echo '<div style="margin-top: 10px;"></div>';

    //Go through all categories and see which ones have published courses:
    foreach($this->config->item('en_all_10869') /* Course Categories */ as $en_id => $m) {

        //Count total published courses here:
        $published_ins = $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //READ LOGIN REQUIRED
            'ln_type_entity_id' => 4601, //BLOG KEYWORDS
            'ln_parent_entity_id' => $en_id,
        ), array('in_child'), 0, 0, array('in_outcome' => 'ASC'));

        if(!count($published_ins)){
            continue;
        }

        //Show featured blogs in this category:
        echo '<div class="read-topic"><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'</div>';
        echo '<div class="list-group">';
        foreach($published_ins as $published_in){
            echo echo_in_read($published_in);
        }
        echo '</div>';

    }

    ?>

</div>
