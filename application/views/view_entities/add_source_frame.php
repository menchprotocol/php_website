

<script src="/js/custom/entity-source-add.js?v=v<?= $this->config->item('app_version').time() ?>"
        type="text/javascript"></script>


<h1 class="middle-h">Add Source Wizard</h1>


<div style="padding:0 10px; max-width: 560px; margin: 0 auto;">

    <div class="add_source_result"></div>
    <div class="add_source_body">

    <div class="title" style="margin-bottom:0; padding-bottom:0;">
        <h4>Source URL:</h4>
    </div>
    <span class="white-wrapper">
        <input type="text" id="source_url" value="" placeholder="https://www.youtube.com/watch?v=ebEairg3G3w" class="form-control border">
    </span>

    <br />
    <div class="url-error"></div>
    <div class="url-parsed hidden">

        <div id="cleaned_url" style="font-size:0.7em;"></div>

        <div class="title" style="margin-top:17px;"><h4>Entity Name</h4></div>
        <span class="white-wrapper" style="margin-bottom: 3px;">
            <input type="text" id="en_name_url" class="form-control border">
        </span>



        <div class="title" style="margin-top:15px;"><h4>Entity Parents:</h4></div>
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
                <span><span class="en_icon_mini_ui">'.$m['m_icon'].'</span> '.$m['m_name'].'</span>
            </label>
            <textarea id="en_desc_'.$en_id.'" class="form-control border hidden" style="height:78px; max-width: 500px; margin: 2px 0 2px 30px;" placeholder="'.str_replace('Expert ','', rtrim($m['m_name'], 's')).' overview..."></textarea>
        </span>';
        }
        ?>


        <?php foreach (array(1,2,3) as $num){ ?>


            <span class="white-wrapper author-addon">
                <input type="text" onkeyup="search_author(<?= $num ?>)" id="author_<?= $num ?>" value="" author-box="<?= $num ?>" data-toggle="tooltip" data-placement="top" title="Add people or organizations that contributed to this source" placeholder="Search/create author entities..." class="form-control border en-search algolia_search">
            </span>

            <span class="inline-block en_role_<?= $num ?> hidden">
                <input style="padding-left:3px;" type="text" id="auth_role_<?= $num ?>" class="form-control border" data-toggle="tooltip" data-placement="top" title="Define the role of this person/organization" placeholder="Role..." value="Author" />
            </span>


            <div class="form-group explain_expert_<?= $num ?> label-floating is-empty hidden" style="margin:3px 0 7px 22px;">

                <div class="white-wrapper">
                    <select class="form-control border" id="entity_parent_id_<?= $num ?>" style="display:inline-block !important; margin-bottom: 3px;">
                        <?php
                        //Show account types:
                        foreach ($this->config->item('en_all_4600') as $en_id => $m) {
                            echo '<option value="'.$en_id.'">Add as new ' . rtrim($m['m_name'], 's') . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group border" style="background-color: #FFF; margin-bottom: 3px;">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">URL:</span>
                    <input style="padding-left:3px;" type="url" id="ref_url_<?= $num ?>" class="form-control">
                </div>
                <div class="form-group label-floating is-empty">
                    <div class="input-group border">
                                <span class="input-group-addon addon-lean addon-grey"
                                      style="color:#2f2739; font-weight: 300;">Is Expert?<div style="font-size: 0.8em; margin-top: 10px;">If so,<br />explain why...</div></span>
                        <span class="white-wrapper">
                    <textarea class="form-control right-textarea" id="why_expert_<?= $num ?>"></textarea>
                </span>
                    </div>
                </div>
            </div>
        <?php } ?>




        <div style="padding:20px 0;">
            <a href="javascript:fn___add_source_process();" class="btn btn-secondary btn-save">Add Source</a>
        </div>
    </div>
    </div>

</div>