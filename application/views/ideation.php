<?php

$chainusercreator = ( $user_session ? $user_session['userid'] : 14068 /* GUEST */ );

//Log view:
$this->Ideachains->create(array(
    'chainusertype' => 1309378, //Post Viewed
    'chainusercreator' => $chainusercreator,
    'chainuserinput' => $chainusercreator,
    'chainpostinput' => $focus_i['postid'],
));

//See if we need to redirect to starting point?
if($user_session && !user_session(10939) && count($this->Ideachains->read(array(
        'chainusercreator' => $user_session['userid'],
        'chainusertype' => 4235, //Get started
        'chainpostinput' => $focus_i['postid'],
    )))){
    //User without editing superpowers has viewed an post they have post discovered already, so get them there:
    js_php_redirect('/'.$focus_i['posthashtag'].'/start', 13);
}

//Focus Post:
echo '<div class="view_12273 row justify-content">';
echo post_view(42288,  $focus_i);
echo '</div>';

if(user_session(10939) || isset($_GET['open'])){
    echo view_post_nav(false, $focus_i);
}

?>

<script>
    $(document).ready(function () {
        load_post_menu();
    });
</script>
