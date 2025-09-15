<?php

$chainhandlecreator = ( $handle_session ? $handle_session['handleid'] : 14068 /* GUEST */ );

//Log view:
$this->Chains->create(array(
    'chainhandletype' => 1309378, //Hashtag Viewed
    'chainhandlecreator' => $chainhandlecreator,
    'chainhandleinput' => $chainhandlecreator,
    'chainhashtaginput' => $focus_i['hashtagid'],
));

//See if we need to redirect to starting point?
if($handle_session && !handle_session(10939) && count($this->Chains->read(array(
        'chainhandlecreator' => $handle_session['handleid'],
        'chainhandletype' => 4235, //Get started
        'chainhashtaginput' => $focus_i['hashtagid'],
    )))){
    //Handle without editing superpowers has viewed an hashtag they have hashtag discovered already, so get them there:
    js_php_redirect('/'.$focus_i['hashtagterm'].'/start', 13);
}

//Focus Hashtag:
echo '<div class="view_12273 row justify-content">';
echo hashtag_view(42288,  $focus_i);
echo '</div>';

if(handle_session(10939) || isset($_GET['open'])){
    echo view_hashtag_nav(false, $focus_i);
}

?>

<script>
    $(document).ready(function () {
        load_hashtag_menu();
    });
</script>