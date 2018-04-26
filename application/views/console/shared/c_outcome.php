<?php
$tip_index = array(
    0 => 598,
    1 => 598,
    2 => 606,
    3 => 607,
);
?>
<div class="title"><h4><?= $this->lang->line('level_'.$level.'_icon') .' '. $this->lang->line('level_'.$level.'_name') ?> Outcome <span id="hb_<?= $tip_index[$level] ?>" class="help_button" intent-id="<?= $tip_index[$level] ?>"></span></h4></div>
<div class="help_body maxout" id="content_<?= $tip_index[$level] ?>"></div>

<div class="form-group label-floating is-empty">
    <div class="input-group border">
        <span class="input-group-addon addon-lean" style="color:#222; font-weight: 300;">To</span>
        <input type="text" id="c_outcome<?= $level ?>" maxlength="70" value="<?= (isset($c_outcome) ? htmlentities($c_outcome) : '') ?>" class="form-control c_outcome_input">
    </div>
</div>