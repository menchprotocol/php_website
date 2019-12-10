
<div class="container">

    <h1 class="play no-margin"><span class="play_title"></span> <span class="play_content"></span></h1>
    <h1 class="read no-margin"><span class="read_title"></span> <span class="read_content"></span></h1>
    <h1 class="blog no-margin"><span class="blog_title"></span> <span class="blog_content"></span></h1>

    <script>

        $(document).ready(function () {

            //Terminology Index
            var play_titles = ['PLAY', 'EARN'];
            var play_terms = ['a learning-game', 'a free-game', 'a crypto-game', 'crypto-coins'];
            var read_titles = ['READ', 'DISCOVER', 'LEARN'];
            var read_terms = ['top ideas', 'on the web', 'on Messenger', '5K words/mo free', 'all for $5/mo', 'interactively'];
            var blog_titles = ['BLOG', 'CREATE', 'WRITE'];
            var blog_terms = ['expert ideas', 'your ideas', 'on the web', 'for Messenger', 'for cash income', 'collaboratively'];

            //The Story
            var story_timeline = 334;
            console.log(story_timeline);
            new TypeIt('.play_title', {   speed: 33,  startDelay:(story_timeline+=1500) }).type(play_titles[0]).go().destroy();
            console.log(story_timeline);
            new TypeIt('.read_title', {   speed: 33,  startDelay:(story_timeline+=1500) }).type(read_titles[0]).go().destroy();
            new TypeIt('.blog_title', {   speed: 33,  startDelay:(story_timeline+=1500) }).type(blog_titles[0]).go().destroy();



            new TypeIt('.blog_content', {
                speed: 50,
                startDelay:6000
            })
                .type(blog_terms[0])
                .pause(500)
                .delete(blog_terms[0].length)
                .pause(100)
                .type(blog_terms[1])
                .pause(750)
                .options({speed: 100, deleteSpeed: 75})
                .delete(blog_terms[1].length)
                .pause(750)
                .type(blog_terms[2])
                .go()
                .destroy();


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