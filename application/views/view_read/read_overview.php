
<script>

    function tempo(beat){
        if(beat==0){
            return 1500;
            return getRandomInt(0,987);
        } else if(beat==1){
            return 2000;
            return getRandomInt(987,4181);
        } else if(beat==2){
            return 3000;
            return getRandomInt(2584,6765);
        } else {
            return 0;
        }
    }

    function speed(){
        return 75;
        return getRandomInt(55,89);
    }

    function stream(){

        $('.animate-trigger').addClass('hidden');
        $('.animate-box').removeClass('hidden');


        //The Story
        var tl = 0;
        var show_cursor = false;


        new TypeIt('.play_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(0) }).type('PLAY').go();

        new TypeIt('.read_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).type('READ').go();

        new TypeIt('.blog_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).type('BLOG').go();


        new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).type('a learning game').go();
        new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('a social game').go();

        new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).type('microblogs').go();
        new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('key ideas').go();
        new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('interactively').go();

        new TypeIt('.blog_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('your stories').go();
        new TypeIt('.blog_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('your ideas').go();

        new TypeIt('.blog_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('SAVE').go();
        new TypeIt('.blog_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('SORT').go();
        new TypeIt('.blog_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('LINK').go();
        new TypeIt('.blog_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('SHARE').go();
        new TypeIt('.blog_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('BLOG').go();

        new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('on the go').go();
        new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('on the web').go();

        new TypeIt('.blog_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('on the web').go();
        new TypeIt('.blog_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('for chat apps').go();
        new TypeIt('.blog_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('for Messenger').go();

        new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('on Messenger').go();

        new TypeIt('.blog_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('a conversation').go();

        new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('a conversation').go();

        new TypeIt('.blog_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('collaboratively').go();

        new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('interactively').go();

        new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl }).delete().go();
        new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).type('joyfully').go();
        new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).type('.').go();

        new TypeIt('.blog_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().go();
        new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl }).delete().go();
        new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl }).delete().go();

    }

</script>
<div class="container">

    <div class="animate-box hidden">
        <h1 class="no-margin play"><span class="play_title"></span> <span class="play_content"></span></h1>
        <h1 class="no-margin read"><span class="read_title"></span> <span class="read_content"></span></h1>
        <h1 class="no-margin blog"><span class="blog_title"></span> <span class="blog_content"></span></h1>
    </div>

    <div class="animate-trigger"><a href="javascript:void(0);" onclick="stream();" class="btn btn-lg btn-read montserrat inline-block" style="margin-top: 20px;"><i class="fas fa-play-circle"></i> WATCH 1 MIN INTRO</a> <a href="/play" class="btn btn-lg btn-play montserrat inline-block" style="margin-top: 20px;"><i class="fas fa-users-crown"></i> Top 100 players</a></div>

    <?php
    //Go through all categories and see which ones have published courses:
    foreach($this->config->item('en_all_10869') /* Course Categories */ as $en_id => $m) {

        //Count total published courses here:
        $published_ins = $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_12096')) . ')' => null,
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
