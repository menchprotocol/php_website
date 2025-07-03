<?php

$chainsourcecreator = ( $source_session && isset($source_session['sourceid']) ? $source_session['sourceid'] : 14068 /* GUEST */ );
//Log view:
$this->Chains->create(array(
    'chainsourcetype' => 44176, //Source View
    'chainsourceup' => $focus_e['sourceid'],
    'chainsourcedown' => $chainsourcecreator,
    'chainsourcecreator' => $chainsourcecreator,
));

//Focus Source:
echo '<div class="view_12274 row justify-content">';
echo source_view(42287, $focus_e, null);
echo '</div>';

$sources___focus = $this->config->item('sources___32596');

$coins_count = array();
$body_content = '';


$mainmenu = '<ul class="nav nav-tabs nav12274">';
$submenus = '';
foreach($this->config->item('sources___31916') as $chainsourcetype => $m) {

    $superpowers_required = array_intersect($this->config->item('sourceids___10957'), $m['m__following']);
    if(count($superpowers_required) && !source_session(end($superpowers_required))){
        continue;
    }

    $coins_count[$chainsourcetype] = sources_query($chainsourcetype, $focus_e['sourceid'], 0, false);
    if(!$coins_count[$chainsourcetype] && in_array($chainsourcetype, $this->config->item('sourceids___12144'))){ continue; }

    $input_content = '';
    if(source_session(10939) && in_array($chainsourcetype, $this->config->item('sourceids___11028'))){

        //ADD SOURCES
        $input_content .= '<div class="new_list new-list-'.$chainsourcetype.'"><div class="col-12 container-center"><div class="dropdown_'.$chainsourcetype.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__e algolia__ce dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Create New or Chain Existing @Sources">
                    </div></div></div></div>';
        $body_content .= '<script> $(document).ready(function () { source_load_finder('.$chainsourcetype.'); }); </script>';

    }

    $chainsourcecreator = ( $source_session && isset($source_session['sourceid']) ? $source_session['sourceid'] : 14068 /* GUEST */ );
    if(($source_session && in_array($chainsourcetype, $this->config->item('sourceids___42945'))) || $coins_count[$chainsourcetype]>0){

        $body_content .= '<div class="headlinebody pillbody headline_body_'.$chainsourcetype.' hidden" read-counter="'.$coins_count[$chainsourcetype].'">'.$input_content.'<div class="tab_content"></div></div>';

        $mainmenu .= '<li class="nav-item thepill'.$chainsourcetype.'"><a class="nav-chain handle_nav_'.$m['m__handle'].'" chainsourcetype="'.$chainsourcetype.'" href="#'.$m['m__handle'].'" title="'.$m['m__title'].'">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$chainsourcetype.'">'. view_number($coins_count[$chainsourcetype]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$chainsourcetype.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';

    }

    //Now generate sub menu:
    if($chainsourcetype!=12273 && $chainsourcetype!=12274 && is_array($this->config->item('sources___'.$chainsourcetype))){

        $submenus = '<ul class="nav nav-tabs nav12274 nav_tab_'.$chainsourcetype.'">';
        foreach($this->config->item('sources___'.$chainsourcetype) as $chainsourcetype2 => $m2) {

            $coins_count[$chainsourcetype2] = sources_query($chainsourcetype2, $focus_e['sourceid'], 0, false);

            $mainmenu .= '<li class="nav-item thepill'.$chainsourcetype2.'"><a class="nav-chain handle_nav_'.$m2['m__handle'].'" chainsourcetype="'.$chainsourcetype2.'" href="#'.$m2['m__handle'].'" title="'.$m2['m__title'].'">&nbsp;<span class="icon-block">'.$m2['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$chainsourcetype2.'">'. view_number($coins_count[$chainsourcetype2]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$chainsourcetype2.'">&nbsp;'. $m2['m__title'] . '&nbsp;</span></a></li>';
        }
        $submenus .= '</ul>';
    }
}
$mainmenu .= '</ul>';

echo $mainmenu;
echo $submenus;

echo $body_content;


$focus_tab = 0;
foreach($sources___focus as $chainsourcetype => $m) {
    if(isset($coins_count[$chainsourcetype]) && $coins_count[$chainsourcetype] > 0){
        $focus_tab = $chainsourcetype;
        echo '<script> $(document).ready(function () { if(!document.location.hash) { load_hashtag_menu(\''.$m['m__handle'].'\'); } }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($sources___focus as $chainsourcetype => $m) {
        $focus_tab = $chainsourcetype;
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