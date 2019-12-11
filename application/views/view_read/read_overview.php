
<div class="container">

    <h1 class="play no-margin"><span class="play_title"></span> <span class="play_content"></span></h1>
    <h1 class="read no-margin"><span class="read_title"></span> <span class="read_content"></span></h1>
    <h1 class="blog no-margin"><span class="blog_title"></span> <span class="blog_content"></span></h1>

    <script>

        function tempo(beat){
            if(beat==0){
                return getRandomInt(0,980);
            } else if(beat==1){
                return getRandomInt(980,2500);
            } else if(beat==2){
                return getRandomInt(2200,4900);
            }
        }

        function speed(){
            return getRandomInt(22,55);
            //return 50;
        }

        $(document).ready(function () {

            //Terminology Index
            var play_titls = ['PLAY', 'EARN', 'UNLOCK', 'RANK'];
            var play_terms = ['a learning-game', 'a free-game', 'a crypto-game', 'crypto-coins', 'SUPERPOWERS', 'in leaderboard'];
            var read_titls = ['READ', 'DISCOVER', 'LEARN'];
            var read_terms = ['top ideas', 'on the web', 'on Messenger', '5K words/mo free', 'all for $5/mo', 'interactively'];
            var blog_titls = ['BLOG', 'CREATE', 'WRITE'];
            var blog_terms = ['expert ideas', 'your ideas', 'on the web', 'for Messenger', 'for cash income', 'collaboratively'];

            //The Story
            var tl = tempo(0);

            new TypeIt('.play_title', { speed:speed(), startDelay:tl+=tempo(1) }).type(play_titls[0]).go().destroy();
            new TypeIt('.read_title', { speed:speed(), startDelay:tl+=tempo(1) }).type(read_titls[0]).go().destroy();
            new TypeIt('.blog_title', { speed:speed(), startDelay:tl+=tempo(1) }).type(blog_titls[0]).go().destroy();

            new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).type(blog_terms[0]).pause(tempo(1)).go();
            new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).empty().type(blog_terms[1]).pause(tempo(1)).go();
            new TypeIt('.blog_content', { speed:speed(), startDelay:tl+=tempo(2) }).empty().type(blog_terms[2]).pause(tempo(0)).go();

        });

    </script>

    <?php

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