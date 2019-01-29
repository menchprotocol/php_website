

<script src="/js/custom/entity-source-add.js?v=v<?= $this->config->item('app_version').time() ?>"
        type="text/javascript"></script>


<div style="padding:0 10px;">

    <h3 style="margin: 0; padding:10px 0 0 0;">New Source Entity Wizard</h3>

    <div class="title" style="margin-bottom:0; padding-bottom:0;">
        <h4>
            <i class="fal fa-link"></i> Source URL
        </h4>
    </div>
    <span class="white-wrapper">
        <input type="url" id="source_url" value="" placeholder="https://www.youtube.com/watch?v=ebEairg3G3w" class="form-control border">
    </span>


    <div class="title" style="margin-top:15px;"><h4><i class="fas fa-sign-in"></i> Entity Parents</h4></div>
    <?php

    //The Parent Entity based on domain:
    echo '<span class="checkbox" style="margin: 0;">
            <label style="display:inline-block !important; font-size: 0.9em !important; margin-left:8px;">
                <input type="checkbox" checked="checked" disabled name="source_type" value="domain_url" />
                    <span class="entity-domain"><i class="fas fa-at grey-at"></i> samplewebsite.com</span>
                    [<span class="underdot" data-toggle="tooltip" data-placement="top" title="Parent auto created based on URL domain">Domain Entity</span>]
            </label>
        </span>';


    foreach($this->config->item('en_all_3000') as $en_id => $en ){
        echo '<span class="checkbox" style="margin: 0;">
            <label style="display:inline-block !important; font-size: 0.9em !important; margin-left:8px;">
                <input type="checkbox" name="source_type" value="'.$en_id.'" />
                <span '.( strlen($en['m_desc']) > 0 ? ' class="underdot" data-toggle="tooltip" data-placement="right" title="'.$en['m_desc'].'" ' : '' ).'>
                    '.$en['m_icon'].' '.$en['m_name'].'
                </span>
            </label>
        </span>';
    }
    ?>

    <?php foreach (array(1,2,3) as $num){ ?>
    <span class="white-wrapper author-addon">
        <input type="text" onkeyup="search_author(<?= $num ?>)" id="author_<?= $num ?>" value="" placeholder="Search/create author entities..." class="form-control border">
    </span>
    <span class="inline-block author_is_expert_<?= $num ?> hidden">
        <span class="white-wrapper">
            <select class="form-control border" id="entity_parent_id_<?= $num ?>" style="display:inline-block !important;">
                <option value="1278">People</option>
                <option value="2750">Organizations</option>
            </select>
        </span>
    </span>
    <div class="form-group explain_expert_<?= $num ?> label-floating is-empty hidden" style="margin:3px 0 7px 22px;">
        <div class="input-group border" style="background-color: #FFF; margin-bottom: 3px;">
            <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">URL:</span>
            <input style="padding-left:3px;" type="url" id="ref_url_<?= $num ?>" placeholder="URL referencing this entity" class="form-control">
        </div>
        <div class="form-group label-floating is-empty">
            <div class="input-group border">
                                <span class="input-group-addon addon-lean addon-grey"
                                      style="color:#2f2739; font-weight: 300;">Is Expert?</span>
                <span class="white-wrapper">
                    <textarea class="form-control right-textarea" id="why_expert_<?= $num ?>"></textarea>
                </span>
            </div>
        </div>
    </div>
    <?php } ?>


    <div class="title" style="margin-top:30px;"><h4><i class="fas fa-fingerprint"></i> Entity Name
        </h4></div>
    <span class="white-wrapper" style="margin-bottom: 3px;">
        <input type="text" id="en_name" class="form-control border">
    </span>



    <div style="padding:20px 0;">
        <a href="javascript:fn___en_add_source();" class="btn btn-secondary btn-save">Create Entity</a>
    </div>

</div>