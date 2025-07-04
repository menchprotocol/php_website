<?php

$chainhandlecreator = ( $handle_session && isset($handle_session['handleid']) ? $handle_session['handleid'] : 14068 /* GUEST */ );
//Log view:
if($focus_e['handleid']!=$chainhandlecreator){
    $this->Chains->create(array(
        'chainhandletype' => 44176, //Handle View
        'chainhandleinput' => $focus_e['handleid'],
        'chainhandleoutput' => $chainhandlecreator,
        'chainhandlecreator' => $chainhandlecreator,
    ));
}


//Focus Handle:
echo '<div class="view_12274 row justify-content">';
echo handle_view(42287, $focus_e, null);
echo '</div>';

$handles___focus = $this->config->item('handles___32596');

$coins_count = array();
$body_content = '';


$mainmenu = '<ul class="nav nav-tabs nav12274">';
$submenus = '';
foreach($this->config->item('handles___31916') as $chainhandletype => $m) {

    $superpowers_required = array_intersect($this->config->item('handleids___10957'), $m['m__following']);
    if(count($superpowers_required) && !handle_session(end($superpowers_required))){
        continue;
    }

    $coins_count[$chainhandletype] = handles_query($chainhandletype, $focus_e['handleid'], 0, false);
    if(!$coins_count[$chainhandletype] && in_array($chainhandletype, $this->config->item('handleids___12144'))){ continue; }

    $input_content = '';
    if(handle_session(10939) && in_array($chainhandletype, $this->config->item('handleids___11028'))){

        //ADD HANDLES
        $input_content .= '<div class="new_list new-list-'.$chainhandletype.'"><div class="col-12 container-center"><div class="dropdown_'.$chainhandletype.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__e algolia__ce dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Create New or Chain Existing @Handles">
                    </div></div></div></div>';
        $body_content .= '<script> $(document).ready(function () { handle_load_finder('.$chainhandletype.'); }); </script>';

    }

    $chainhandlecreator = ( $handle_session && isset($handle_session['handleid']) ? $handle_session['handleid'] : 14068 /* GUEST */ );
    if(($handle_session && in_array($chainhandletype, $this->config->item('handleids___42945'))) || $coins_count[$chainhandletype]>0){

        $body_content .= '<div class="headlinebody pillbody headline_body_'.$chainhandletype.' hidden" read-counter="'.$coins_count[$chainhandletype].'">'.$input_content.'<div class="tab_content"></div></div>';

        $mainmenu .= '<li class="nav-item thepill'.$chainhandletype.'"><a class="nav-chain handle_nav_'.$m['m__handle'].'" chainhandletype="'.$chainhandletype.'" href="#'.$m['m__handle'].'" title="'.$m['m__title'].'">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$chainhandletype.'">'. view_number($coins_count[$chainhandletype]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$chainhandletype.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';

    }

    //Now generate sub menu:
    if($chainhandletype!=12273 && $chainhandletype!=12274){

        $submenu_content = null;
        foreach($this->config->item('handles___'.$chainhandletype) as $chainhandletype2 => $m2) {

            $superpowers_required = array_intersect($this->config->item('handleids___10957'), $m2['m__following']);
            if(count($superpowers_required) && !handle_session(end($superpowers_required))){
                continue;
            }

            $coins_count[$chainhandletype2] = handles_query($chainhandletype, $focus_e['handleid'], 0, false, $chainhandletype2);
            if(!$coins_count[$chainhandletype2]){ continue; }

            $submenu_content .= '<li class="nav-item thepill'.$chainhandletype2.'"><a class="nav-chain handle_nav_'.$m2['m__handle'].'" chainhandletype="'.$chainhandletype2.'" href="#'.$m2['m__handle'].'" title="'.$m2['m__title'].'">&nbsp;<span class="icon-block">'.$m2['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$chainhandletype2.'">'. view_number($coins_count[$chainhandletype2]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$chainhandletype2.'">&nbsp;'. $m2['m__title'] . '&nbsp;</span></a></li>';
        }

        if($submenu_content){
            $submenus .= '<ul class="nav nav-tabs nav12274 nav_sub nav_sub_'.$chainhandletype.' hidden">';
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
foreach($handles___focus as $chainhandletype => $m) {
    if(isset($coins_count[$chainhandletype]) && $coins_count[$chainhandletype] > 0){
        $focus_tab = $chainhandletype;
        echo '<script> $(document).ready(function () { if(!document.location.hash) { load_hashtag_menu(\''.$m['m__handle'].'\'); } }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($handles___focus as $chainhandletype => $m) {
        $focus_tab = $chainhandletype;
        echo '<script> $(document).ready(function () { if(!document.location.hash) { load_hashtag_menu(\''.$m['m__handle'].'\'); } }); </script>';
        break;
    }
}

?>

<script>
    $(document).ready(function () {
        load_hashtag_menu();
    });
</script>