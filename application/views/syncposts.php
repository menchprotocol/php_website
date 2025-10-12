<?php

$delete_missing = true;
$_GET['limit'] = 0;

$stats = array(
    'posts_oncache' => 0,
    'posts_oncache_notonchain' => 0,
    'posts_oncache_chainadded' => 0,
    'posts_oncache_synced' => 0,
    'posts_onchain' => 0,
    'posts_orphan' => 0,
    'posts_empty' => 0,
    'posts_delete' => 0,
    'posts_voidcreaetor' => 0,
    'posts_valid_cachevoid' => 0,
    'cache_valid_postvoid' => 0,
    'message' => '',
);


//First start with cache and see what might be missing:
foreach ($this->Posts->read(array(
    'postid >' => 0,
    'LENGTH(postmessage) < 1' => null,
), $_GET['limit']) as $post) {

    $stats['posts_oncache']++;
    $cache_chains = $this->Chains->read(array(
        'chainusertype' => 12273,
        'chainpostinput' => $post['postid'],
    ), array(), 1);

    if (count($cache_chains)) {

        //Also exists on chain:
        $update_cache = array();
        if ($cache_chains[0]['chainusercreator'] != $post['postcreator']) {
            $update_cache['postcreator'] = $cache_chains[0]['chainusercreator'];
        }
        if (!strlen($post['posttime'])) {
            $update_cache['posttime'] = date("Y-m-d H:i:s", strtotime($cache_chains[0]['chaintime']));
        }

        //Update if there is anything:
        if (count($update_cache) && $this->Posts->update($post['postid'], $update_cache)) {
            $stats['posts_oncache_synced']++;
        }

    } else {

        $stats['posts_oncache_notonchain']++;

        $post_index = post_index($post['postmessage'], 0, 0, $post['posthashtag']);

        $new_x = $this->Chains->create(array(
            'chainusertype' => 12273,
            'chainusercreator' => 1,
            'chainpostinput' => $post['postid'],
            'chainvalue' => "#" . $post['posthashtag']
                . "\n" . $post_index['chainvalue']
        ));

        if ($new_x['chainid'] > 0) {

            $stats['posts_oncache_chainadded']++;

            $stats['message'] .= "#" . $post['posthashtag'] . " Added to Chain\n";

            //Edit ID
            if (!count($this->Chains->read(array(
                'chainvoid >=' => 0,
                'chainid' => $post['postid'],
            ), array(), 1))) {
                $this->db->query("UPDATE ideachains SET chainid = " . $post['postid'] . " WHERE chainid = " . $new_x['chainid'] . ";");
            }
        }

    }
}



$_GET['limit'] = 1;

//Posts on chain:
foreach($this->Chains->read(array(
    'chainusertype' => 12273,
), array(), $_GET['limit'], 0, array('chainid' => 'DESC')) as $x){

    //Extra hashtag:
    $is = array();
    $chain_hashtag = ( substr($x['chainvalue'], 0, 1)=='#' ? one_two_explode('#',"\n",$x['chainvalue']) : false );
    if(strlen($chain_hashtag)){
        $is = $this->Posts->read(array(
            'LOWER(posthashtag)' => strtolower($chain_hashtag),
        ));
    }

    if(count($is)){

        //See if IDs match:


    } else {
        //Search the ID:
        $is = $this->Posts->read(array(
            'postid' => $x['chainpostinput'],
        ));

        if(!count($is)){
            $is = $this->Posts->read(array(
                'postid' => $x['chainid'],
            ));
        }
    }



    $es = $this->Users->read(array(
        'userid' => $x['chainusercreator'],
    ));

    //Orphan?
    $total_links = count($this->Chains->read(array(
        'chainid !=' => $x['chainid'],
        '(chainpostinput='.$x['chainpostinput'].' OR chainpostoutput='.$x['chainpostinput'].')' => null,
    )));
    if(!$total_links){
        $stats['posts_orphan']++;
    }

    $posts_empty = count($is) && !strlen($is[0]['postmessage']);
    if($posts_empty){
        $stats['posts_empty']++;
    }

    $stats['posts_onchain']++;
    if(!count($es)){
        $stats['posts_voidcreaetor']++;
        //Update to Shervin:
        /*
        $this->Chains->update($x['chainid'], array(
            'chainusercreator' => 1,
        ));
        */
    }
    if(!count($is)){
        $stats['posts_valid_cachevoid']++;
    }


    if(!count($is) && 0){
        //Add post:
        $post_new = $this->Posts->create(array(
            'postmessage' => $x['chainvalue'],
        ), $x['chainusercreator']);
        $is[0] = $post_new['post_create'];
    }


    $delete = !$total_links || $posts_empty;
    if($delete){
        $stats['posts_delete']++;
    }

    if(count($is)){
        $post_index = $is[0];
    } else {
        $post_index = array(
            'chainvalue' => $x['chainvalue'],
            'postmessage' => '',
            'postdiscover' => '',
            'postedit' => '',
            'posthashtag' => '',
        );
    }

    /*
    $post_index = post_index($postmessage, $x['postid'], $x['chainusercreator'], $x['posthashtag']);
    $this->Posts->update($x['chainid'], array(
        'postmessage' => $post_index['postmessage'],
        'postdiscover' => $post_index['postdiscover'],
        'postedit' => $post_index['postedit'],
    ));

    $this->db->where('chainid', $x['chainid']);
    $this->db->update('ideachains', array(
        'chainpostinput' =>  $x['chainid'],
        'chainvalue' =>  '#'.$post_index['posthashtag']."\n".$post_index['chainvalue'],
    ));
    */

    if($delete || !$total_links || $posts_empty || !count($es) || !count($is)){

        $stats['message'] .= '#'.$x['chainpostinput'].' #'.$post_index['posthashtag'].' '.
            ( $delete ? '[DELETED POST]' : '' ).
            ( !$total_links ? '[ORPHAN]' : '') .
            ( $posts_empty ? '[EMPTY]' : '' ).
            ( !count($es) ? '[posts_voidcreaetor]' : '' ).
            ( !count($is) ? '[posts_valid_cachevoid]' : '' )."\n";
    }


}

view_json($stats);