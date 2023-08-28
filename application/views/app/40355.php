<?php

foreach(array('i__id','custom_grid') as $input){
    if(!isset($_GET[$input])){
        $_GET[$input] = '';
    }
}

//Fetch Main Idea:
if(strlen($_GET['i__id'])){

    $recursive_i_ids = array();
    $is_with_action_es = array();
    $es_added = array();


    foreach($this->I_model->fetch(array(
        'i__id IN (' . $_GET['i__id'] . ')' => null, //SOURCE LINKS
    ), 0, 0, array('i__id' => 'ASC')) as $loaded_i) {
        echo '<h2 class="no-print"><a href="/~' . $loaded_i['i__id'] . '"><u>' . $loaded_i['i__title'] . '</u></a></h2>';
    }


    foreach($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
        'x__up' => $_GET['custom_grid'], //ACTIVE
        'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
    ), array('x__right'), 0, 0, array('x__weight' => 'ASC', 'i__title' => 'ASC')) as $link_i){

        $discoveries = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__left' => $link_i['i__id'],
        ), array('x__creator'), 0);

        if(!count($discoveries)){
            continue;
        }

        echo '<div class="frame">';
        echo '<h3 style="margin-top: 55px;">'.$link_i['i__title'].'</h3>';

        foreach($discoveries as $x){

            $u_names = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__up' => 30198, //Full Name
            ));

            echo '<p style="padding-left: 21px;">'.( count($u_names) && strlen($u_names[0]['x__message']) ? $u_names[0]['x__message'] : $x['e__title'] ).'</p>';

        }

        echo '</div>';


    }

} else {

    echo 'Missing Idea ID';

}

?>

<style>
    .frame {page-break-after:always;}
</style>
