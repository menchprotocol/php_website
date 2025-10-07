<?php

$delete_missing = true;

$stats = array(
    'posts_all' => 0,
    'posts_orphan' => 0,
    'posts_empty' => 0,
    'posts_delete' => 0,
    'posts_duplicate' => 0,
    'posts_void' => 0,
    'posts_voidcreaetor' => 0,
    'posts_void_cachevalid' => 0,
    'posts_valid_cachevoid' => 0,
    'cache_valid_postvoid' => 0,
);

$has_media = false;
foreach($this->Chains->read(array(
    'chainusertype' => 12273,
), array(), ( isset($_GET['limit']) ? $_GET['limit'] : 0 ), ( isset($_GET['offset']) ? $_GET['offset'] : 0 ), array('chainid' => 'DESC')) as $x){

    $stats['posts_duplicate']++;

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

    $stats['posts_all']++;
    if($x['chainvoid']>0){
        $stats['posts_void']++;
    } elseif(!count($es)){
        $stats['posts_voidcreaetor']++;
        //Update to Shervin:
        $this->Chains->update($x['chainid'], array(
            'chainusercreator' => 1,
        ));
    }
    if($x['chainvoid']>0 && count($is)){
        $stats['posts_void_cachevalid']++;
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


    $delete = !$total_links;
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


    $table .= '<tr>';

    $table .= '<td>'.$x['chainid'].'<br />V'.$x['chainvoid'].'/'.
        ( $delete ? '[DELETED POST]' : '' ).
        ( !$total_links ? '[ORPHAN]' : '') .
        ( $x['chainvoid']>0 ? '[VOID]' : '' ).
        ( $posts_empty ? '[EMPTY]' : '' ).
        ( !count($es) ? '[posts_voidcreaetor]' : '' ).
        ( !count($is) ? '[posts_valid_cachevoid]' : '' ).
        '<br />#'.$post_index['posthashtag'].'</td>';

    $table .= '<td>T@'.$x['chainusertype'].'<br />C@'.$x['chainusercreator'].'</td>';
    $table .= '<td><div style="max-width:233px;">'.nl2br($x['chainvalue']).'</div></td>'; //RAW
    $table .= '<td><div style="max-width:233px;">'.nl2br($post_index['postmessage']).'</div></td>'; //TEXT
    $table .= '<td><div style="max-width:233px;">'.($post_index['postdiscover']).'</div></td>'; //DISCOVER
    $table .= '<td><div style="max-width:233px;">'.($post_index['postedit']).'</div></td>'; //EDIT
    $table .= '</tr>';

}

$table .= '</table>';

if(isset($stats)){
    print_r($stats);
}

echo $table;
echo '<style> 

    img { max-width: 100% !important; } 
    
    .container {
        max-width: calc(100% - 16px) !important;
    }
        
</style>';