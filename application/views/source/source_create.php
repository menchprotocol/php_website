<?php
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
$en_all_12762 = $this->config->item('en_all_12762'); //IDEA SOURCE CREATOR
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
?>

<script src="/application/views/source/source_create.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php

    //SOURCE CREATOR TITLE
    echo '<h1 class="'.extract_icon_color($en_all_11035[12762]['m_icon']).'" style="padding-top:5px;"><span class="icon-block">'.echo_en_icon($en_all_11035[12762]['m_icon']).'</span>'.$en_all_11035[12762]['m_name'].'</h1>';


    //Full Name
    echo '<span class="medium-header"><span class="icon-block">'.$en_all_12762[6197]['m_icon'].'</span>'.$en_all_12762[6197]['m_name'].'</span>';
    echo '<div class="form-group is-empty"><input type="text" id="source_name" '.( isset($_GET['source_name']) ? ' value="'.$_GET['source_name'].'" ' : '' ).' class="form-control border"></div>';


    //URL
    echo '<span class="medium-header"><span class="icon-block">'.$en_all_12762[12763]['m_icon'].'</span>'.$en_all_12762[12763]['m_name'].'</span>';
    echo '<div class="form-group is-empty"><input type="url" id="source_url" '.( isset($_GET['source_url']) ? ' value="'.$_GET['source_url'].'" ' : '' ).' class="form-control border"></div>';


    //Source Type
    echo '<span class="medium-header"><span class="icon-block">'.$en_all_12762[12769]['m_icon'].'</span>'.$en_all_12762[12769]['m_name'].'</span>';
    $ui .= echo_in_dropdown(12769, 3084, 'btn-source');


    //Content Type
    echo '<span class="medium-header"><span class="icon-block">'.$en_all_11035[3000]['m_icon'].'</span>'.$en_all_11035[3000]['m_name'].'</span>';
    $ui .= echo_in_dropdown(3000, 3084, 'btn-source');


    //Author(s)
    echo '<span class="medium-header"><span class="icon-block">'.$en_all_12762[12764]['m_icon'].'</span>'.$en_all_12762[12764]['m_name'].'</span>';
    echo '<div id="new-children" class="list-group-item list-adder itemsource no-side-padding">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$en_all_2738[4536]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control source form-control-thick montserrat doupper algolia_search dotransparent add-input"
                           maxlength="' . config_var(11072) . '"
                           id="authorName"
                           placeholder="AUTHOR NAME">
                </div><div class="algolia_pad_search hidden pad_expand"></div></div>';


    ?>
</div>