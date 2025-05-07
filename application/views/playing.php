<?php

$chainsourcecreator = ( $source_session ? $source_session['sourceid'] : 14068 /* GUEST */ );
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


echo '<ul class="nav nav-tabs nav12274">';
foreach($this->config->item('sources___31916') as $chainsourcetype => $m) {

    $superpowers_required = array_intersect($this->config->item('sourceids___10957'), $m['m__following']);
    if(count($superpowers_required) && !source_session(end($superpowers_required))){
        continue;
    }

    $coins_count[$chainsourcetype] = sources_query($chainsourcetype, $focus_e['sourceid'], 0, false);
    if(!$coins_count[$chainsourcetype] && in_array($chainsourcetype, $this->config->item('sourceids___12144'))){ continue; }

    $input_content = '';
    if(source_session(10939)){

        if(in_array($chainsourcetype, $this->config->item('sourceids___11028'))){

            //ADD SOURCES
            $input_content .= '<div class="new_list new-list-'.$chainsourcetype.'"><div class="col-12 container-center"><div class="dropdown_'.$chainsourcetype.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__e algolia__ce dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Create New or Chain Existing @Sources">
                    </div></div></div></div>';
            $body_content .= '<script> $(document).ready(function () { source_load_finder('.$chainsourcetype.'); }); </script>';

        } elseif(0 && in_array($chainsourcetype, $this->config->item('sourceids___42261'))){

            //TODO Activate Later?
            //ADD IDEAS
            $input_content .= '<div class="new_list new-list-'.$chainsourcetype.'"><div class="col-12 container-center"><div class="dropdown_'.$chainsourcetype.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__i algolia__ci dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Create New or Chain Existing #ideas">
                    </div></div></div></div>';
            $body_content .= '<script> $(document).ready(function () { idea_load_search('.$chainsourcetype.'); }); </script>';

        }

    }

    if(in_array($chainsourcetype, $this->config->item('sourceids___42945')) || $coins_count[$chainsourcetype]>0){

        $body_content .= '<div class="headlinebody pillbody headline_body_'.$chainsourcetype.' hidden" read-counter="'.$coins_count[$chainsourcetype].'">'.$input_content.'<div class="tab_content"></div></div>';

        echo '<li class="nav-item thepill'.$chainsourcetype.'"><a class="nav-chain handle_nav_'.$m['m__handle'].'" chainsourcetype="'.$chainsourcetype.'" href="#'.$m['m__handle'].'" title="'.$m['m__title'].'">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$chainsourcetype.'">'. view_number($coins_count[$chainsourcetype]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$chainsourcetype.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';

    }
}
echo '</ul>';
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