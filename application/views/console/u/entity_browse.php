<script>

$(document).ready(function() {
    //Detect any possible hashes that controll the menu?
    if(window.location.hash) {
        focus_hash(window.location.hash);
    }
});

function entity_load_more(page){

    //Show spinner:
    $('.load-more').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();

    $.post("/entities/entity_load_more/<?= $inbound_u_id ?>/<?= $entities_per_page ?>/"+page, {} , function(data) {
        $('.load-more').remove();
        //Update UI to confirm with user:
        $('#list-entities').append(data);

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    });
}
</script>


<?php

if(!$inbound_u_id){

    //Show help tip for Entities:
    echo '<div class="help_body below_h maxout" id="content_6776"></div>';

} else {

    //Show info box for this item:
    echo '<div id="entity-box" class="list-group maxout">';
    echo '<div id="u_'.$entity['u_id'].'" entity-id="'.$entity['u_id'].'" class="list-group-item">';

    //Right content:
    echo '<span class="pull-right">';
    echo echo_score($entity['u_impact_score']);
    echo '<a class="badge badge-primary stnd-btn" onclick="load_modify('.$entity['u_id'].')" href="/entities/'.$entity['u_id'].'/modify"><i class="fas fa-cog"></i></a>';
    echo '</span>';

    //Regular section:
    echo (strlen($entity['u_image_url'])>4 ? '<img src="'.$entity['u_image_url'].'" class="profile-icon2" />' : '');
    echo '<b id="u_title">'.$entity['u_full_name'].'</b>';
    echo ' <span class="obj-id">@'.$entity['u_id'].'</span>';

    //Public profiles:
    if(strlen($entity['u_website_url'])>0){
        echo '<a href="'.$entity['u_website_url'].'" data-toggle="tooltip" title="Visit Website" target="_blank" class="entity-head-icon"><i class="fab fa-chrome"></i></a>';
    }
    $u_social_account = $this->config->item('u_social_account');
    foreach($u_social_account as $sa_key=>$sa){
        if(strlen($entity[$sa_key])>0){
            echo '<a href="'.$sa['sa_prefix'].$entity[$sa_key].$sa['sa_postfix'].'" data-toggle="tooltip" title="'.$sa['sa_name'].'" target="_blank" class="entity-head-icon">'.$sa['sa_icon'].'</a>';
        }
    }

    echo '<div>'.$entity['u_bio'].'</div>';


    echo '</div>';
    echo '</div>';
}


//Start fetching related objects:
$admissions = $this->Db_model->ru_fetch(array(
    'ru_outbound_u_id' => $inbound_u_id,
), array(
    'ru.ru_id' => 'DESC',
), array('b'));

$b_team_member = $this->Db_model->ba_fetch(array(
    'ba.ba_outbound_u_id' => $inbound_u_id,
), array('b'));

$transactions = $this->Db_model->t_fetch(array(
    't_inbound_u_id' => $inbound_u_id,
));

$is_active = null;
?>

<ul id="topnav" class="nav nav-pills nav-pills-primary">
    <?php

    if(count($child_entities)>0){
        echo '<li id="nav_list" '.( !$is_active ? 'class="active"' : '' ).'><a href="#list"><i class="fas fa-list-ul"></i> List ('.count($child_entities).')</a></li>';
        if(!$is_active){
            $is_active = 'list';
        }
    }

    if(count($admissions)>0){
        echo '<li id="nav_admissions" '.( !$is_active ? 'class="active"' : '' ).'><a href="#admissions"><i class="fas fa-ticket"></i> Admissions ('.count($admissions).')</a></li>';
        if(!$is_active){
            $is_active = 'admissions';
        }
    }

    if(count($b_team_member)>0){
        echo '<li id="nav_bootcamps" '.( !$is_active ? 'class="active"' : '' ).'><a href="#bootcamps"><i class="fas fa-dot-circle"></i> Bootcamps ('.count($b_team_member).')</a></li>';
        if(!$is_active){
            $is_active = 'bootcamps';
        }
    }

    if(count($transactions)>0){
        echo '<li id="nav_transactions" '.( !$is_active ? 'class="active"' : '' ).'><a href="#transactions"><i class="fas fa-money-bill"></i> Transactions ('.count($transactions).')</a></li>';
        if(!$is_active){
            $is_active = 'transactions';
        }
    }

    ?>
</ul>


<div class="tab-content tab-space">

    <?php

    if(count($child_entities)>0){

        echo '<div class="tab-pane '.( $is_active=='list' ? 'active' : '' ).'" id="tablist">';
        echo '<div id="list-entities" class="list-group maxout">';
        foreach($child_entities as $u){
            echo_u($u);
        }

        if($entity['u__outbound_count']>count($child_entities)){
            echo_next_u(1, $entities_per_page, $entity['u__outbound_count']);
        }

        echo '</div>';
        echo '</div>';

    }


    if(count($admissions)>0){

        echo '<div class="tab-pane '.( $is_active=='admissions' ? 'active' : '' ).'" id="tabadmissions">';
        echo '<div id="list-admissions" class="list-group maxout">';
        foreach($admissions as $ru){
            echo_ru($ru);
        }
        echo '</div>';
        echo '</div>';

    }

    if(count($b_team_member)>0){

        echo '<div class="tab-pane '.( $is_active=='bootcamps' ? 'active' : '' ).'" id="tabbootcamps">';
        echo '<div id="list-bootcamps" class="list-group maxout">';
        foreach($b_team_member as $ba){
            echo_ba($ba);
        }
        echo '</div>';
        echo '</div>';

    }

    if(count($transactions)>0){

        echo '<div class="tab-pane '.( $is_active=='transactions' ? 'active' : '' ).'" id="tabtransactions">';
        echo '<div id="list-transactions" class="list-group maxout">';
        foreach($transactions as $t){
            echo_t($t);
        }
        echo '</div>';
        echo '</div>';

    }


    ?>

</div>
