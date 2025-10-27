<?php


$post_tree = json_decode(file_get_contents('https://mench.com/json/'.$focus_post['posthashtag']));

print_r($post_tree['poststats']);

?>

<style>
    <!-- CSS HERE -->
</style>

<script>
    <!-- JS HERE -->
</script>


<!-- HTML HERE -->
<div>Hello Slide</div>
