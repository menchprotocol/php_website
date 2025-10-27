<?php

//See if we need to redirect to starting point?
if($user_session && !user_session(10939) && count($this->Chains->read(array(
        'chainusercreator' => $user_session['userid'],
        'chainusertype' => 4235, //Get started
        'chainpostinput' => $focus_post['postid'],
    )))){
    //User without editing superpowers has viewed an post they have post discovered already, so get them there:
    js_php_redirect('/'.$focus_post['posthashtag'].'/start', 13);
}

//Focus Post:
echo '<div class="view_12273 row justify-content">';
echo post_view(42288,  $focus_post);
echo '</div>';

if(user_session(10939) || isset($_GET['open'])){
    echo view_post_nav(false, $focus_post);
}

?>

<script>
    $(document).ready(function () {
        load_post_menu();
    });
</script>
