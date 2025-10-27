<?php

//Load the entire JSON object of the input post tree to be able to build our 1-page JS app:
$post_tree = object_to_array(json_decode(file_get_contents('https://mench.com/json/'.$focus_post['posthashtag'])));


?>

<style>
    /* CSS HERE */
</style>

<script>
    /* JS HERE */
</script>


<!-- HTML HERE -->
<div>Hello Slide</div>
<br />
<div><?= nl2br(print_r($user_session, true)); ?></div>
<div><?= nl2br(print_r($post_tree['poststats'], true)); ?></div>
<div><?= nl2br(print_r($focus_post, true)); ?></div>
