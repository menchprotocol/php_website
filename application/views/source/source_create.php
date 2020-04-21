<?php


$ins = $this->IDEA_model->in_fetch(array(
    'in_id' => @$_GET['in_id'],
    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
));

if(!isset($ins[0]['in_id'])){
    die('Missing Idea ID (in_id) variable');
}



$en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
$en_all_12762 = $this->config->item('en_all_12762'); //IDEA SOURCE CREATOR
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
?>

<script>
    var in_loaded_id = <?= $ins[0]['in_id'] ?>;
</script>
<script src="/application/views/source/source_create.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php

    //SOURCE CREATOR TITLE
    echo '<h1 class="'.extract_icon_color($en_all_11035[12762]['m_icon']).'" style="padding-top:5px;"><span class="icon-block">'.echo_en_icon($en_all_11035[12762]['m_icon']).'</span>'.$en_all_11035[12762]['m_name'].'</h1>';



    //IDEA REFERENCE
    echo '<h2 style="margin-top: 21px;"><span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span><a href="/idea/'.$ins[0]['in_id'].'">'.echo_in_title($ins[0]).'</a></h2>';


    //Full Name
    echo '<h2 style="margin-top: 21px;"><span class="icon-block">'.$en_all_12762[6197]['m_icon'].'</span>'.$en_all_12762[6197]['m_name'].'</h2>';
    echo '<div class="form-group is-empty"><input type="text" id="source_name" '.( isset($_GET['source_name']) ? ' value="'.$_GET['source_name'].'" ' : '' ).' class="form-control border" placeholder="'.$en_all_12762[6197]['m_desc'].'"></div>';


    //URL
    echo '<h2 style="margin-top: 21px;"><span class="icon-block">'.$en_all_12762[12763]['m_icon'].'</span>'.$en_all_12762[12763]['m_name'].'</h2>';
    echo '<div class="form-group is-empty"><input type="url" id="source_url" '.( isset($_GET['source_url']) ? ' value="'.$_GET['source_url'].'" ' : '' ).' class="form-control border" placeholder="'.$en_all_12762[12763]['m_desc'].'"></div>';


    //Source Type
    echo '<h2><span class="icon-block">'.$en_all_12762[12769]['m_icon'].'</span>'.$en_all_12762[12769]['m_name'].'</h2>';
    echo echo_in_dropdown(12769, 3084, 'btn-source');



    //Content Type
    echo '<div class="content_type_only hidden">';

    echo echo_in_dropdown(3000, 3005, 'btn-source');

    //Author(s)
    echo '<h2 style="margin-top: 21px;"><span class="icon-block">'.$en_all_12762[12764]['m_icon'].'</span>'.$en_all_12762[12764]['m_name'].'</h2>';
    echo '<div id="new-children" class="list-group-item list-adder itemsource no-side-padding">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$en_all_2738[4536]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control source form-control-thick montserrat doupper algolia_search dotransparent add-input"
                           maxlength="' . config_var(11072) . '"
                           id="authorName"
                           placeholder="PERSON NAME">
                </div><div class="algolia_pad_search hidden pad_expand"></div></div>';


    echo '</div>';


    //CREATE BUTTON:
    echo '<div style="margin-top: 21px;"><a href="javascript:void();" onclick="create_process()" class="btn btn-source">'.$en_all_12762[12771]['m_name'].'</a></div>';


    ?>
</div>