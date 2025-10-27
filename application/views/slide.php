<?php


$post_tree = object_to_array(json_decode(file_get_contents('https://mench.com/json/'.$focus_post['posthashtag'])));

?>

<style>
    <!-- CSS HERE -->
</style>

<script>
    <!-- JS HERE -->
</script>


<!-- HTML HERE -->
<div>Hello Slide</div>
<div><?= nl2br(str_replace('    ','    ',print_r($post_tree['poststats'], true))); ?></div>
