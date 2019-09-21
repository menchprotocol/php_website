<?php $en_all_6805 = $this->config->item('en_all_6805'); ?>

<script src="/js/custom/en_source_wizard.js?v=v<?= $this->config->item('app_version').time() ?>"
        type="text/javascript"></script>


<h1 class="middle-h">Add Source Wizard</h1>


<div style="padding:0 10px; max-width: 560px; margin: 0 auto;">

    <div class="add_source_result"></div>
    <div class="add_source_body">

    <div class="title" style="margin-bottom:0; padding-bottom:0;">
        <h4>Source URL:</h4>
    </div>
    <span class="white-wrapper">
        <input type="text" id="source_url" value="<?= ( isset($_GET['url']) ? urldecode($_GET['url']) : '' ) ?>" placeholder="https://www.youtube.com/watch?v=ebEairg3G3w" class="form-control border">
    </span>

    <br />
    <div class="url-error"></div>
    <div class="url-parsed hidden">

        <div id="cleaned_url" style="font-size:0.7em;"></div>

        <div class="title" style="margin-top:17px;"><h4>Entity Name:</h4></div>
        <span class="white-wrapper" style="margin-bottom: 3px;">
            <textarea id="en_name_url" class="form-control border"style="height:66px; min-height:66px;"></textarea>
        </span>



        <div class="title" style=" margin-top:5px;"><h4>Expert Contributors:</h4></div>
        <?php for($contributor_num = 1; $contributor_num <= 5; $contributor_num++){ ?>

            <span class="white-wrapper contributor-addon">
                <input type="text" onkeyup="search_contributor(<?= $contributor_num ?>)" id="contributor_<?= $contributor_num ?>" value="" contributor-box="<?= $contributor_num ?>" data-toggle="tooltip" data-placement="top" title="Add people or organizations that contributed to this source" placeholder="Search/create contributor entities..." class="form-control border en-search algolia_search">
            </span>

            <span class="inline-block en_role_<?= $contributor_num ?> hidden">
                <input style="padding-left:3px;" type="text" id="auth_role_<?= $contributor_num ?>" class="form-control border" data-toggle="tooltip" data-placement="top" title="Define the role of this person/organization" placeholder="Contributor's Role..." maxlength="<?= $this->config->item('messages_max_length') ?>" value="" />
            </span>


            <div class="form-group explain_expert_<?= $contributor_num ?> label-floating is-empty hidden inline-box" style="margin:0px 19px 10px 10px;">

                <div class="white-wrapper">
                    <select class="form-control border" id="entity_parent_id_<?= $contributor_num ?>" style="display:inline-block !important; margin-bottom: 3px;">
                        <?php
                        //Show account types:
                        echo '<option value="">Add as...</option>';
                        foreach ($this->config->item('en_all_4600') as $en_id => $m) {
                            echo '<option value="'.$en_id.'">' . rtrim($m['m_name'], 's') . '</option>';
                        }
                        ?>
                    </select>
                    <a href="#" id="google_<?= $contributor_num ?>" style="font-size:0.8em; margin-left: 5px;" target="_blank">Search Google <i class="fas fa-external-link"></i></a>
                </div>

                <div class="input-group border" style="background-color: #FFF; margin-bottom: 3px;">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">Expert URL<b style="color:#FF0000;" data-toggle="tooltip" data-placement="top" title="Required">*</b></span>
                    <input style="padding-left:3px;" type="url" id="ref_url_<?= $contributor_num ?>" class="form-control">
                </div>

                <div class="form-group label-floating is-empty">
                    <div class="input-group border">
                        <span class="input-group-addon addon-lean addon-grey"
                              style="color:#2f2739; font-weight: 300;">Expert <b style="color:#FF0000;" data-toggle="tooltip" data-placement="top" title="Required">*</b><br />Summary
                        </span>
                        <span class="white-wrapper">
                            <textarea class="form-control right-textarea characterLimiter textarea_<?= $contributor_num ?>" id-postfix="<?= $contributor_num ?>" id="why_expert_<?= $contributor_num ?>"></textarea>
                        </span>
                    </div>
                </div>

                <div class="below-counter">[<span id="char_count_<?= $contributor_num ?>">0</span>/<?= $this->config->item('messages_max_length') ?>]</div>

            </div>
        <?php } ?>





        <div class="title" style="margin-top:15px;"><h4>Source Type:</h4></div>
        <?php

        //The Parent Entity based on domain:
        echo '<span class="checkbox" style="margin: 0;">
            <label style="display:inline-block !important; font-size: 0.8em !important; margin-left:5px;">
                <input type="checkbox" checked="checked" disabled value="url_clean_domain" />
                    <span class="entity_domain_ui"></span>
                    [<span class="underdot" data-toggle="tooltip" data-placement="top" title="Parent auto created based on URL domain">Domain Entity</span>]
            </label>
        </span>';


        foreach($this->config->item('en_all_3000') as $en_id => $m ){
            echo '<span class="checkbox" style="margin: 0;">

            <label style="display:inline-block !important; font-size: 0.8em !important; margin-left:5px;">
                <input type="checkbox" class="source_parent_ens" value="'.$en_id.'" />
                <span><span class="en_mini_ui_icon">'.$m['m_icon'].'</span> '.$m['m_name'].'</span>
            </label>
            
            <div class="extra_info_'.$en_id.' hidden" style="display:'.( in_array(6805 , $m['m_parents']) ? 'block' : 'none' ).';">
                <span class="mini-header" style="margin: 0 0 2px 30px;">'.(  in_array(6805 , $m['m_parents']) ? $en_all_6805[$en_id]['m_desc'] : '' ).':</span>
                <textarea id="en_desc_'.$en_id.'" id-postfix="'.$en_id.'" class="form-control border characterLimiter textarea_'.$en_id.'" style="height:78px; max-width: 490px; margin: 2px 0 2px 30px;" placeholder="'.str_replace('Expert ','', rtrim($m['m_name'], 's')).' overview..."></textarea>
                <div class="below-counter">[<span id="char_count_'.$en_id.'">0</span>/'.$this->config->item('messages_max_length').']</div>
            </div>
           
            
        </span>';
        }
        ?>







        <div style="padding:20px 0;">
            <a href="javascript:en_add_source_process();" class="btn btn-secondary btn-save">Add Source</a>
        </div>
    </div>
    </div>

</div>