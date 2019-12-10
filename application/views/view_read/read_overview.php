
<div class="container">

    <h1 class="play no-margin"><span class="htitle"><span class="play_title"></span></span><span class="play_content inline-block"></span></h1>
    <h1 class="read no-margin"><span class="htitle"><span class="read_title"></span></span><span class="read_content inline-block"></span></h1>
    <h1 class="blog no-margin"><span class="htitle"><span class="blog_title"></span></span><span class="blog_content inline-block"></span></h1>

    <script>

        $(document).ready(function () {

            //Load the three:
            new TypeIt('.play_title', {   speed: 33,  startDelay: 1000 }).type('PLAY').go().destroy();
            new TypeIt('.read_title', {   speed: 33,  startDelay: 2500 }).type('READ').go().destroy();
            new TypeIt('.blog_title', {   speed: 33,  startDelay: 4000 }).type('BLOG').go().destroy();


            var play_terms = ['A Publishing Game', 'A Crypto Game.', 'With Friends.'];
            var read_terms = ['Relevant Ideas', 'On Messenger', 'On the web', 'Interactively.'];
            var blog_terms = ['Your Ideas.', 'Quote Ideas.', 'For Messenger.', 'Collaboratively.'];


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