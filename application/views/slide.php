<?php


$post_tree = json_decode(file_get_contents('https://mench.com/json/'.$focus_post['posthashtag']));

?>

<style>
    <!-- CSS HERE -->
</style>

<script>
    <!-- JS HERE -->
</script>


<!-- HTML HERE -->
<div>Hello Slide</div>
<div><?= print_r($post_tree['poststats']); ?></div>
