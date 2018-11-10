<?php

//Fetch Data Components
$udata = $this->session->userdata('user');

//Determine what type of entity is this? (Content, people or Organization)
$entity_type = entity_type($entity);

//This also gets passed to other pages via AJAX to be applied to u_echo() in ajax calls:
$can_edit           = ( $udata['u_id']==$entity['u_id'] || array_key_exists(1281, $udata['u__inbounds']));
$add_name           = ( in_array($entity['u_id'], array(1278,2750)) ? rtrim($entity['u_full_name'],'s') : 'Content' );
$add_id             = ( in_array($entity['u_id'], array(1278,2750)) ? $entity['u_id'] : 1326 /* Content */ );

//Fetch other data:
$child_entities = $this->Db_model->ur_outbound_fetch(array(
    'ur_inbound_u_id' => $entity['u_id'],
    'ur_status' => 1, //Only active
), array('u__outbound_count'), $this->config->item('items_per_page'));

//Intents subscribed:
$limit = (is_dev() ? 10 : 100);
$all_subscriptions = $this->Db_model->w_fetch(array(
    'w_outbound_u_id' => $entity['u_id'],
), array('u','c','w_stats'), array(
    'w_id' => 'DESC',
), $limit);



//Javascript Logic:
?>
<script>
    //Set global variables:
    var u_status_filter = -1; //No filter, show all!
    var is_compact = (is_mobile() || $(window).width()<767);
    var top_u_id = <?= $entity['u_id'] ?>;
    var can_u_edit = <?= ( $can_edit ? 1 : 0 ) ?>;
    var add_u_name = '<?= $add_name ?>';
    var add_u_id = <?= $add_id ?>;
    var message_max = <?= $this->config->item('message_max') ?>;
    var u_full_name_max = <?= $this->config->item('u_full_name_max') ?>;
    var entity_u_type = <?= $entity_type ?>;
</script>
<script src="/js/custom/entity-manage-js.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>


<div class="row">
    <div class="col-xs-6 cols">

<?php
//Entity & Components:

//Inbounds
if($entity['u_id']!=2738){
    echo '<h5>';
        echo '<span class="badge badge-h"><i class="fas fa-sign-in-alt"></i> <span class="li-inbound-count">'.count($entity['u__inbounds']).'</span> Ins</span>';
        if($can_edit) {
            echo ' <a class="add-new-btn" href="javascript:$(\'#new-inbound\').removeClass(\'hidden\');$(\'.add-new-btn\').hide();$(\'#new-inbound .new-input\').focus();"><i class="fas fa-plus-circle"></i></a>';
        }
    echo '</h5>';

    echo '<div id="list-inbound" class="list-group  grey-list">';
    foreach ($entity['u__inbounds'] as $ur) {
        echo echo_u($ur, 2, $can_edit, true);
    }
    //Input to add new inbounds:
    if($can_edit) {
        echo '<div id="new-inbound" class="list-group-item list_input grey-input hidden">
            <div class="input-group">
                <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search" data-lpignore="true" placeholder="Add Entity..."></div>
                <span class="input-group-addon">
                    <a class="badge badge-secondary new-btn" href="javascript:void(0);" onclick="alert(\'Note: Either choose an option from the suggestion menu to continue\')">ADD</a>
                </span>
            </div>
        </div>';
    }
    echo '</div>';
}





//Top/main entity
echo '<h5 class="badge badge-h"><i class="fas fa-at"></i> Entity</h5>';
echo '<div id="entity-box" class="list-group">';
echo echo_u($entity, 1, $can_edit);
echo '</div>';








//Outbounds
echo '<table width="100%" style="margin-top:10px;"><tr>';
echo '<td style="width: 100px;"><h5 class="badge badge-h"><i class="fas fa-sign-out-alt rotate90"></i> <span class="li-outbound-count">'.$entity['u__outbound_count'].'</span> Outs</h5></td>';
echo '<td style="text-align: right;"><div class="btn-group btn-group-sm" style="margin-top:-5px;" role="group">';

//Fetch current count for each status from DB:
$counts = $this->Db_model->ur_outbound_fetch(array(
    'ur_inbound_u_id' => $entity['u_id'],
    'ur_status' => 1, //Only active
    'u_status >=' => 0,
), array(), 0, 0, 'COUNT(u_id) as u_counts, u_status', 'u_status', array(
    'u.u_status' => 'ASC',
));

//Only show filtering UI if we find entities with multiple statuses
if(count($counts)>0 && $counts[0]['u_counts']<$entity['u__outbound_count']){

    //Load status definitions:
    $status_index = $this->config->item('object_statuses');

    //Show fixed All button:
    echo '<a href="javascript:void(0)" onclick="filter_u_status(-1)" class="btn btn-default btn-secondary u-status-filter u-status--1" data-toggle="tooltip" data-placement="top" title="View all entities"><i class="fas fa-at"></i><span class="hide-small"> All</span> [<span class="li-outbound-count">'.$entity['u__outbound_count'].'</span>]</a>';

    //Show each specific filter based on DB counts:
    foreach($counts as $c_c){
        $st = $status_index['u'][$c_c['u_status']];
        echo '<a href="#status-'.$c_c['u_status'].'" onclick="filter_u_status('.$c_c['u_status'].')" class="btn btn-default u-status-filter u-status-'.$c_c['u_status'].'" data-toggle="tooltip" data-placement="top" title="'.$st['s_desc'].'"><i class="'.$st['s_icon'].'"></i><span class="hide-small"> '.$st['s_name'].'</span> [<span class="count-u-status-'.$c_c['u_status'].'">'.$c_c['u_counts'].'</span>]</a>';
    }

}

echo '</div></td>';
echo '</tr></table>';



echo '<div id="list-outbound" class="list-group grey-list">';

foreach($child_entities as $u){
    echo echo_u($u, 2, $can_edit);
}
if($entity['u__outbound_count'] > count($child_entities)) {
    echo_next_u(1, $this->config->item('items_per_page'), $entity['u__outbound_count']);
}

//Input to add new inbounds:
if($can_edit){
    echo '<div id="new-outbound" class="list-group-item list_input grey-input">
        <div class="input-group">
            <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search bottom-add" data-lpignore="true" placeholder="Add '.$add_name.' by Name/URL"></div>
            <span class="input-group-addon">
                <a class="badge badge-secondary new-btn" href="javascript:ur_add(0,'.$add_id.', 0);">ADD</a>
            </span>
        </div>
    </div>';
}

echo '</div>';







//Only show if data exists (users cannot modify this anyways)
if(count($all_subscriptions)>0){
    //Show these subscriptions:
    echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-comment-plus"></i> '.count($all_subscriptions).($limit==count($all_subscriptions)?'+':'').' Subscriptions</h5>';
    echo '<div class="list-group list-grey" style="margin-bottom:10px;">';
    foreach($all_subscriptions as $w){
        echo echo_w_console($w);
    }
    echo '</div>';
}













//URLs
if(!in_array($entity['u_id'], array(1278,1326,2750))){
    echo '<h5 class="badge badge-h"><i class="fas fa-link"></i> <span class="li-urls-count">'.count($entity['u__urls']).'</span> URLs</h5>';
    echo '<div id="list-urls" class="list-group  grey-list" style="margin-bottom:40px;">';
    foreach ($entity['u__urls'] as $x) {
        echo echo_x($entity, $x);
    }

    //Add new Reference:
    if ($can_edit) {
        echo '<div class="list-group-item list_input grey-input">
            <div class="input-group">
                <div class="form-group is-empty"><input type="url" class="form-control" data-lpignore="true" id="add_url_input" placeholder="Paste URL here..."></div>
                <span class="input-group-addon">
                    <a class="badge badge-secondary" id="add_url_btn" href="javascript:x_add();">ADD</a>
                </span>
            </div>
        </div>';
    }
    echo '</div>';
}








?>
</div>

    <div class="col-xs-6 cols ">


      <div id="modifybox" class="fixed-box hidden" entity-id="0" entity-link-id="0">

          <h5 class="badge badge-h"><i class="fas fa-cog"></i> Modify Entity</h5>
          <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;"><a href="javascript:void(0)" onclick="$('#modifybox').addClass('hidden')"><i class="fas fa-times-circle"></i></a></div>

          <div class="grey-box">
              <div class="title" style="margin-bottom:0; padding-bottom:0;"><h4><i class="fas fa-fingerprint"></i> Name [<span style="margin:0 0 10px 0; font-size:0.8em;"><span id="charNameNum">0</span>/<?= $this->config->item('u_full_name_max') ?></span>]</h4></div>
              <input type="text" id="u_full_name" value="" onkeyup="changeName()" maxlength="<?= $this->config->item('u_full_name_max') ?>" data-lpignore="true" placeholder="Name" class="form-control border">

              <div class="title" style="margin-top:15px;"><h4><i class="fas fa-comment-dots"></i> Introductory Message [<span style="margin:0 0 10px 0; font-size:0.8em;"><span id="charNum">0</span>/<?= $this->config->item('message_max') ?></span>]</h4></div>
              <textarea class="form-control text-edit border msg" id="u_bio" style="height:146px; background-color:#FFFFFF !important;" onkeyup="changeBio()"></textarea>


              <!-- Password credential management -->
              <div style="margin-top:15px; display:<?= (array_key_exists(1281, $udata['u__inbounds']) ? 'block' : 'none') ?>;"><a href="javascript:$('.changepass').toggle();" class="changepass changepass_a" data-toggle="tooltip" title="Manage email/password that enables this entity to login" data-placement="top" style="text-decoration:none;"></a></div>
              <div class="changepass" style="display:none;">
                  <div class="title" style="margin-top:15px;"><h4><i class="fas fa-envelope"></i> Email</h4></div>
                  <input type="email" id="u_email" data-lpignore="true" style="max-width:260px;" value="" data-lpignore="true" class="form-control border">
              </div>
              <div class="changepass" style="display:none;">
                  <div class="title" style="margin-top:15px;"><h4><i class="fas fa-asterisk"></i> Set New Password</h4></div>
                  <div class="form-group label-floating is-empty">
                      <input type="password" id="u_password_new" style="max-width:260px;" autocomplete="new-password" data-lpignore="true" class="form-control border">
                      <span class="material-input"></span>
                  </div>
              </div>


              <table width="100%" style="margin-top:10px;">
                  <tr>
                      <td class="save-td"><a href="javascript:save_u_modify();" class="btn btn-secondary">Save</a></td>
                      <td><span class="save_entity_changes"></span></td>
                      <td style="width:100px; text-align:right;">

                          <div class="unlink-entity"><a href="javascript:ur_unlink();" data-toggle="tooltip" title="Only remove entity link while NOT deleting the entity itself" data-placement="left" style="text-decoration:none;"><i class="fas fa-unlink"></i> Unlink</a></div>

                          <?php if(array_key_exists(1281, $udata['u__inbounds'])){ ?>
                              <div><a href="javascript:u_delete();" data-toggle="tooltip" title="Delete entity AND remove all its URLs, messages & references" data-placement="left" style="text-decoration:none;"><i class="fas fa-trash-alt"></i> Delete</a></div>
                          <?php } ?>

                      </td>
                  </tr>
              </table>
          </div>

      </div>



      <div id="message-frame" class="fixed-box hidden" entity-id="">

          <h5 class="badge badge-h" data-toggle="tooltip" title="Message management can only be done using Intents. Entity messages are listed below for view-only" data-placement="bottom"><i class="fas fa-comment-dots"></i> Entity Messages <i class="fas fa-lock"></i></h5>
          <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;"><a href="javascript:void(0)" onclick="$('#message-frame').addClass('hidden');$('#loaded-messages').html('');"><i class="fas fa-times-circle"></i></a></div>
          <div class="grey-box"><div id="loaded-messages"></div></div>

      </div>


      <?php $this->load->view('console/subscription_views'); ?>



    </div>
</div>