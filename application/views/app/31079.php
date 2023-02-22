<?php

if(!isset($_GET['i__id'])){
    $_GET['i__id'] = 19120; //Tech House 2023 New Years Celebration
}

function referral_line($i){

    $CI =& get_instance();


    $plays = view_covers_i(6255, $i['i__id'], 0, false);
    $x_count = $CI->X_model->fetch(array(
        'x__right' => $i['i__id'],
        'x__type' => 7610, //MEMBER VIEWED DISCOVERY
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    ), array(), 0, 0, array(), 'COUNT(x__id) as total_count');
    $income = 0;
    $tickets = 0;

    $link = 'https://'.get_domain('m__message').'/'.$i['i__id'];

    $is_locked = count($CI->X_model->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
        'x__right' => $i['i__id'],
    ))) >= 3; //Too many sources, cannot edit


    return '<div class="list-group-item list-group-item-action ref_item_'.$i['i__id'].'">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1 css__title" id="ref_id_'.$i['i__id'].'">'.$i['i__title'].'</h5>
      <small>'.( !$is_locked ? ( !$plays ? ' <a href="javascript:void(0);" onclick="delete_ref('.$i['i__id'].','.( isset($i['x__id']) ? $i['x__id'] : 0 ).')"><i class="fal fa-trash-alt"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : '' ).'<a href="javascript:void(0);" onclick="edit_ref('.$i['i__id'].')"><i class="fal fa-edit"></i></a>' : '<i class="fal fa-lock"></i>' ).'<a href="/~'.$i['i__id'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fal fa-cog"></i></a></small>
    </div>
    <p class="mb-1"><small><a href="'.$link.'">'.$link.'</a></small></p>
    <p class="mb-1" style="padding: 8px 0;">'.
        '<span class="data-block"><span class="icon-block-xxs"><i class="fal fa-eye"></i></span>'.$x_count[0]['total_count'].'</span>'.
        '<span class="data-block"><span class="icon-block-xxs"><i class="far fa-yin-yang fa-spin"></i></span>'.$plays.'</span>'.
        '<span class="data-block"><span class="icon-block-xxs"><i class="fal fa-ticket"></i></span>'.$tickets.'</span>'.
        '<span class="data-block"><span class="icon-block-xxs"><i class="fal fa-dollar-sign"></i></span>'.$income.'</span>'.
        '</p>
  </div>';
}

$is = $this->I_model->fetch(array(
    'i__id' => $_GET['i__id'],
));

if(count($is)){


    //Main Idea:
    echo '<div class="list-group list-group-flush mainref" style="margin-bottom: 34px; border-bottom: 1px solid #CCC; border-radius: 0;">';
    echo referral_line($is[0]);
    echo '</div>';


    //Add New:
    echo '<div style="padding: 21px 0 34px;" class="add_frame"><a class="btn btn-lrg btn-6255 go-next" href="javascript:void(0);" onclick="add_ref()"><i class="fas fa-plus-circle"></i> Add</a></div>';


    //Find Tickets:
    if(isset($_GET['new_id']) && $_GET['new_id']>0){
        foreach($this->I_model->fetch(array(
            'i__id' => $_GET['new_id'],
        )) as $added_i){
            echo '<div class="msg alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully added "'.$added_i['i__title'].'"</div>';
        }
    }


    //Referrals:
    echo '<div class="list-group list-group-flush">';
    foreach(view_covers_i(11019, $_GET['i__id'], 1, false) as $referral_i){
        echo referral_line($referral_i);
    }
    echo '</div>';

}


?>

<input type="hidden" id="focus_id" value="<?= $_GET['i__id'] ?>" />

<style>
    .mainref .css__title {
        font-size: 1.5em !important;
    }
</style>

<script>

    function edit_ref(i__id){
        var current_title = $('#ref_id_'+i__id).text();
        var new_title = prompt("Enter the new new referral link name to update:", current_title);
        if (new_title.length) {
            //See if anything changes:
            if( current_title == new_title ){
                //Nothing changed:
                return false;
            }
            $.post("/x/x_set_text", {
                s__id: i__id,
                cache_e__id: 4736, //Idea Title
                field_value: new_title
            }, function (data) {
                if (!data.status) {
                    //Show error:
                    alert(data.message);
                } else {
                    $('#ref_id_'+i__id).text(new_title);
                }
            });
        }
    }


    function delete_ref(i__id, x__id){
        var r = confirm("Permanently delete ["+$('#ref_id_'+i__id).text()+"] ?");
        if (r == true) {
            $('.ref_item_'+i__id).fadeOut();
            $.post("/x/update_dropdown", {
                focus_id:<?= $_GET['i__id'] ?>,
                o__id: i__id,
                element_id: 4737,
                new_e__id: 6182,
                migrate_s__id: 0,
                x__id: x__id
            }, function (data) {
                if(!data.status){
                    alert(data.message);
                }
            });
        }
    }

    function add_ref(){
        var new_title = prompt("Enter the new referral link name to create:", "");
        $('.add_frame').html('Adding...');
        if (new_title.length) {
            //Update backend:
            $.post("/i/i__add", {
                x__type: 11019,
                focus_card: 12273,
                focus_id: <?= $_GET['i__id'] ?>,
                i__title: new_title,
                link_i__id: 0
            }, function (data) {
                if (data.status) {
                    js_redirect('/-31079?i__id=<?= $_GET['i__id'] ?>&new_id='+data.new_i__id);
                } else {
                    //Show errors:
                    alert(data.message);
                }
            });
        }
    }



</script>
