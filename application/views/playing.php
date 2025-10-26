<?php


//Focus User:
echo '<div class="view_12274 row justify-content">';
echo user_view(42287, $focus_e, null);
echo '</div>';

$users___focus = $this->config->item('users___32596');

$coins_count = array();
$body_content = '';


$mainmenu = '<ul class="nav nav-tabs nav12274">';
$submenus = '';
foreach($this->config->item('users___31916') as $chainusertype => $m) {

    $superpowers_required = array_intersect($this->config->item('userids___10957'), $m['m__following']);
    if(count($superpowers_required) && !user_session(end($superpowers_required))){
        continue;
    }

    $coins_count[$chainusertype] = users_query($chainusertype, $focus_e['userid'], 0, false);
    if(!$coins_count[$chainusertype] && in_array($chainusertype, $this->config->item('userids___12144'))){ continue; }

    $input_content = '';
    if(user_session(10939) && in_array($chainusertype, $this->config->item('userids___11028'))){

        //ADD USERS
        $input_content .= '<div class="new_list new-list-'.$chainusertype.'"><div class="col-12 container-center"><div class="dropdown_'.$chainusertype.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__e algolia__ce dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Create New or Chain Existing @Users">
                    </div></div></div></div>';
        $body_content .= '<script> $(document).ready(function () { user_load_finder('.$chainusertype.'); }); </script>';

    }

    if(($user_session && in_array($chainusertype, $this->config->item('userids___42945'))) || $coins_count[$chainusertype]>0){

        $body_content .= '<div class="headlinebody pillbody headline_body_'.$chainusertype.' hidden" read-counter="'.$coins_count[$chainusertype].'">'.$input_content.'<div class="tab_content"></div></div>';

        $mainmenu .= '<li class="nav-item thepill'.$chainusertype.'"><a class="nav-chain user_nav_'.$m['m__handle'].'" chainusertype="'.$chainusertype.'" href="#'.$m['m__handle'].'" title="'.$m['m__name'].'">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$chainusertype.'">'. view_number($coins_count[$chainusertype]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$chainusertype.'">&nbsp;'. $m['m__name'] . '&nbsp;</span></a></li>';

    }

    //Now generate sub menu:
    if($chainusertype!=12273 && $chainusertype!=12274){

        $submenu_content = null;
        foreach($this->config->item('users___'.$chainusertype) as $chainusertype2 => $m2) {

            $superpowers_required = array_intersect($this->config->item('userids___10957'), $m2['m__following']);
            if(count($superpowers_required) && !user_session(end($superpowers_required))){
                continue;
            }

            $coins_count[$chainusertype2] = users_query($chainusertype, $focus_e['userid'], 0, false, $chainusertype2);
            if(!$coins_count[$chainusertype2]){ continue; }

            $submenu_content .= '<li class="nav-item thepill'.$chainusertype2.'"><a class="nav-chain user_nav_'.$m2['m__handle'].'" chainusertype="'.$chainusertype2.'" href="#'.$m2['m__handle'].'" title="'.$m2['m__name'].'">&nbsp;<span class="icon-block">'.$m2['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$chainusertype2.'">'. view_number($coins_count[$chainusertype2]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$chainusertype2.'">&nbsp;'. $m2['m__name'] . '&nbsp;</span></a></li>';
        }

        if($submenu_content){
            $submenus .= '<ul class="nav nav-tabs nav12274 nav_sub nav_sub_'.$chainusertype.' hidden">';
            $submenus .= $submenu_content;
            $submenus .= '</ul>';
        }

    }
}
$mainmenu .= '</ul>';

echo $mainmenu;
echo $submenus;
echo $body_content;


$focus_tab = 0;
foreach($users___focus as $chainusertype => $m) {
    if(isset($coins_count[$chainusertype]) && $coins_count[$chainusertype] > 0){
        $focus_tab = $chainusertype;
        echo '<script> $(document).ready(function () { if(!document.location.hash) { load_post_menu(\''.$m['m__handle'].'\'); } }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($users___focus as $chainusertype => $m) {
        $focus_tab = $chainusertype;
        echo '<script> $(document).ready(function () { if(!document.location.hash) { load_post_menu(\''.$m['m__handle'].'\'); } }); </script>';
        break;
    }
}

?>

<script>
    $(document).ready(function () {
        load_post_menu();
    });
</script>