<?php

$max_load = 9999999;
boost_power();
$mentions = $this->config->item('users___13550');
$ideas = $this->config->item('users___4486');
$count = 0;

//Translator
$table = '<table class="table table-sm table-striped stats-table mini-stats-table" border="1">';

if($_GET['posthashtag']=='user') {

    //USER
    $stats = array(
        'users_all' => 0,
        'users_delete' => 0,
        'users_duplicate' => 0,
        'users_orphan' => 0,
        'users_void' => 0,
        'users_creaetor_not_found' => 0,
        'users_void_cachevalid' => 0,
        'users_valid_cachevoid' => 0,
    );

    foreach ($this->Chains->read(array(
        'chainusertype' => 12274,
    ), array(), $max_load, 0, array('chainid' => 'ASC')) as $x) {

        $stats['users_duplicate']++;
        $count++;
        $es_cache = $this->Users->read(array(
            'userid' => $x['chainuserinput'],
        ));
        if(!count($es_cache)){
            $es_cache = $this->Users->read(array(
                'userid' => $x['chainuserinput'],
            ));
        }
        $es = $this->Users->read(array(
            'userid' => $x['chainusercreator'],
        ));

        $stats['users_all']++;
        if ($x['chainvoid'] > 0) {
            $stats['users_void']++;
        } elseif (!count($es)) {

            $stats['users_creaetor_not_found']++;

            //Update to Shervin:
            $x['chainusercreator'] = 1;

            $this->Chains->update($x['chainid'], array(
                'chainusercreator' => $x['chainusercreator'],
            ));
            $this->Users->update($x['chainusercreator'], array(
                'usercreator' => $x['chainusercreator'],
            ));
            $es = $this->Users->read(array(
                'userid' => $x['chainusercreator'],
            ));

        }
        if ($x['chainvoid'] > 0 && count($es_cache)) {
            $stats['users_void_cachevalid']++;
        }
        if (!count($es_cache)) {
            $this->db->query("DELETE FROM ideachains WHERE chainid = " . $x['chainid'] . ";");
            $stats['users_valid_cachevoid']++;
        }

        //Orphan?
        $total_links = count($this->Chains->read(array(
            'chainid !=' => $x['chainid'],
            '(chainuserdomain='.$x['chainuserinput'].' OR chainusertype='.$x['chainuserinput'].' OR chainusercreator='.$x['chainuserinput'].' OR chainuserinput='.$x['chainuserinput'].' OR chainuseroutput='.$x['chainuserinput'].')' => null,
        )));

        //Fetch from Cache table:
        if (count($es_cache)) {
            $userbio = '@' . $es_cache[0]['userhandle'] . "\n" . $es_cache[0]['username'] . "\n" . $es_cache[0]['usercover'];
        } else {
            $userbio = '@???' . $x['chainvalue'] . "\n" . $x['chainvalue'] . "\nfar fa-user";
        }

        //Append Bio if any
        foreach ($this->Chains->read(array(
            'LENGTH(chainvalue) > 0' => null,
            'chainuserinput IN (11035,42628)' => null,
            'chainuseroutput' => $x['chainuserinput'],
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array(), 0, 0) as $social_chain) {
            $userbio .= "\n" . $social_chain['chainvalue'];
        }

        $delete = !$total_links || $x['chainvoid'] > 0;
        if ($delete) {
            $stats['users_delete']++;
        }

        if(!$total_links){
            $stats['users_orphan']++;
        }

        $table .= '<tr>';
        $table .= '<td>' . $x['chainid'] . '<br />V' . $x['chainvoid'] . '/' . $count . '/' .
            ($delete ? '[DELETED USER]' : '') .
            ( !$total_links ? '[ORPHAN]' : '') .
            ($x['chainvoid'] > 0 ? '[VOID]' : '') .
            (!count($es) ? '[users_creaetor_not_found]' : '') .
            (!count($es_cache) ? '[users_valid_cachevoid]' : '') .
            '</td>';
        $table .= '<td>T@' . $x['chainusertype'] . '<br />C@' . $x['chainusercreator'] . '<br />@' . $x['chainuserinput'] . '</td>';
        $table .= '<td><div style="max-width:233px;">' . nl2br(trim(htmlentities($userbio))) . '</div></td>';
        $table .= '</tr>';
    }


} elseif($_GET['posthashtag']=='post') {

    //POSTS
    $table .= '<tr>';
    $table .= '<td>&nbsp;</td>';
    $table .= '<td>&nbsp;</td>';
    $table .= '<td><div style="max-width:233px;">chainvalue</div></td>'; //RAW
    $table .= '<td><div style="max-width:233px;">posttext</div></td>'; //TEXT
    $table .= '<td><div style="max-width:233px;">postdiscover</div></td>'; //DISCOVERY
    $table .= '<td><div style="max-width:233px;">postedit</div></td>'; //EDITOR
    $table .= '</tr>';

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
    );

    $filters = array(
        'chainusertype' => 12273,
    );

    if(isset($_GET['id'])){
        $filters['chainpostinput'] = $_GET['id'];
    }

    $has_media = false;
    foreach($this->Chains->read($filters, array(), ( isset($_GET['limit']) ? $_GET['limit'] : $max_load ), ( isset($_GET['offset']) ? $_GET['offset'] : 0 ), array('chainid' => 'DESC')) as $x){

        $stats['posts_duplicate']++;
        $count++;
        $is = $this->Posts->read(array(
            'postid' => $x['chainpostinput'],
        ));
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

        $posts_empty = count($is) && !strlen($is[0]['posttext']);
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
                //'postid' => $x['chainid'],
                'posttext' => $x['chainvalue'],
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
                'posttext' => '',
                'postdiscover' => '',
                'postedit' => '',
                'posthashtag' => '',
            );
        }

        /*
        $post_index = post_index($posttext, $x['postid'], $x['chainusercreator'], $x['posthashtag']);
        $this->Posts->update($x['chainid'], array(
            'posttext' => $post_index['posttext'],
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

        $table .= '<td>'.$x['chainid'].'<br />V'.$x['chainvoid'].'/'.$count.'/'.
            ( $delete ? '[DELETED POST]' : '' ).
            ( !$total_links ? '[ORPHAN]' : '') .
            ( $x['chainvoid']>0 ? '[VOID]' : '' ).
            ( $posts_empty ? '[EMPTY]' : '' ).
            ( !count($es) ? '[posts_voidcreaetor]' : '' ).
            ( !count($is) ? '[posts_valid_cachevoid]' : '' ).
            '<br />#'.$post_index['posthashtag'].'</td>';

        $table .= '<td>T@'.$x['chainusertype'].'<br />C@'.$x['chainusercreator'].'</td>';
        $table .= '<td><div style="max-width:233px;">'.nl2br($x['chainvalue']).'</div></td>'; //RAW
        $table .= '<td><div style="max-width:233px;">'.nl2br($post_index['posttext']).'</div></td>'; //TEXT
        $table .= '<td><div style="max-width:233px;">'.($post_index['postdiscover']).'</div></td>'; //DISCOVER
        $table .= '<td><div style="max-width:233px;">'.($post_index['postedit']).'</div></td>'; //EDIT
        $table .= '</tr>';

    }
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