<div class="container">

    <?php

    $session_en = superpower_assigned();

    /*
     *
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

            new TypeIt('.idea_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).type('IDEA').go();


            new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).type('a learning game').go();
            new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('a social game').go();

            new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).type('microideas').go();
            new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('key ideas').go();
            new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('interactively').go();

            new TypeIt('.idea_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('your stories').go();
            new TypeIt('.idea_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('your ideas').go();

            new TypeIt('.idea_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('SAVE').go();
            new TypeIt('.idea_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('SORT').go();
            new TypeIt('.idea_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('LINK').go();
            new TypeIt('.idea_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('SHARE').go();
            new TypeIt('.idea_title', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('IDEA').go();

            new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('on the go').go();
            new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('on the web').go();

            new TypeIt('.idea_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('on the web').go();
            new TypeIt('.idea_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('for chat apps').go();
            new TypeIt('.idea_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('for Messenger').go();

            new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('on Messenger').go();

            new TypeIt('.idea_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('a conversation').go();

            new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).delete().type('a conversation').go();

            new TypeIt('.idea_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('collaboratively').go();

            new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().type('interactively').go();

            new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl }).delete().go();
            new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).type('joyfully').go();
            new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(1) }).type('.').go();

            new TypeIt('.idea_content', { cursor:show_cursor, speed:speed(), startDelay:tl+=tempo(2) }).delete().go();
            new TypeIt('.read_content', { cursor:show_cursor, speed:speed(), startDelay:tl }).delete().go();
            new TypeIt('.play_content', { cursor:show_cursor, speed:speed(), startDelay:tl }).delete().go();

        }

    </script>

    <div class="animate-box hidden">
        <h1 class="no-margin play"><span class="play_title"></span> <span class="play_content"></span></h1>
        <h1 class="no-margin read"><span class="read_title"></span> <span class="read_content"></span></h1>
        <h1 class="no-margin idea"><span class="idea_title"></span> <span class="idea_content"></span></h1>
    </div>

    <div class="animate-trigger hidden"><a href="javascript:void(0);" onclick="stream();" class="btn btn-lg btn-read montserrat inline-block" style="margin-top: 20px;"><i class="fas fa-play-circle"></i> WATCH 1 MIN INTRO</a></div>
     */


    //Player Navigation
    if($session_en && count($this->READ_model->ln_fetch(array(
            'ln_owner_play_id' => $session_en['en_id'],
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Idea Set
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ), array(), 1))){

        //echo '<div class="pull-left">' . echo_menu(12201, 'btn-read') . '</div>';

        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION


        echo '<div class="pull-right inline-block side-margin">';

        echo '<a href="/read/next" class="btn btn-read btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12211]['m_name'].'">'.$en_all_11035[12211]['m_icon'].'</a>';

        echo '</div>';

        echo '<div class="doclear">&nbsp;</div>';


    }



    //Fetch all home page ideas:
    $home_page_ins = array();
    foreach($this->READ_model->ln_fetch(array(
        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_status_play_id IN (' . join(',', $this->config->item('en_ids_12138')) . ')' => null, //Idea Statuses Featured
        'ln_type_play_id' => 4601, //IDEA KEYWORDS
        'ln_parent_play_id' => 12198, //HOME FEATURED
    ), array('in_child'), 0) as $home_in){
        array_push($home_page_ins, $home_in['in_id']);
    }

    //Go through all categories and see which ones have published courses:
    if(count($home_page_ins) > 0){
        $listed_in_ids = array();
        foreach($this->config->item('en_all_10869') /* Course Categories */ as $en_id => $m) {

            //Count total published courses here:
            $published_ins = $this->READ_model->ln_fetch(array(
                'in_id IN (' . join(',', $home_page_ins) . ')' => null,
                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_12138')) . ')' => null, //Idea Statuses Featured
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id' => 4601, //IDEA KEYWORDS
                'ln_parent_play_id' => $en_id,
            ), array('in_child'), 0, 0, array('in_title' => 'ASC'));

            if(!count($published_ins)){
                continue;
            }

            //Show featured ideas in this category:
            $topic_in_count = 0;
            $featured_ui = '';
            foreach($published_ins as $published_in){
                if(in_array($published_in['in_id'], $listed_in_ids)){
                    continue;
                }
                array_push($listed_in_ids, $published_in['in_id']);
                $this_read = echo_in_read($published_in, true);
                if($this_read){
                    $featured_ui .= $this_read;
                    $topic_in_count++;
                }
            }

            if($topic_in_count > 0){
                echo '<div class="read-topic"><span class="icon-block-sm">'.$m['m_icon'].'</span>'.$m['m_name'].'</div>';
                echo '<div class="list-group">';
                echo $featured_ui;
                echo '</div>';
            }
        }
    }

    ?>

</div>
