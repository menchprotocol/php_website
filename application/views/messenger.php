<?php

foreach ($this->Posts->read(array(
    'LOWER(posthashtag)' => strtolower(trim($_GET['posthashtag'])),
)) as $i) {

    echo $i['postmessageraw']."<hr />";

    //Now let's see who will receive this:
    $post_settings = post_settings($i['posthashtag'], true);

    $total_sent = $this->Chains->broadcast($post_settings['query_string_filtered'], $i, 0, true, false);

    echo view_post_title($i) . ' Sent ' . $total_sent . ' Messages to ' . count($post_settings['query_string_filtered']) . ' Members<hr />';

    echo str_replace('  ','    ',nl2br(print_r($post_settings['query_string_filtered'], true)));

    //Ready to be done:
    $this->Chains->post_discovered(($total_sent > 0 ? 1309378 /* Post Trigerred */ : 31022 /* Post Skipped */), 26582, 0, $i);

}

