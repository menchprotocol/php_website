<?php

$max_load = 1597;
boost_power();
$mentions = $this->config->item('users___13550');
$ideas = $this->config->item('users___4486');
$count = 0;

//Translator
$table = '<table class="table table-sm table-striped stats-table mini-stats-table" border="1">';

if($_GET['posthashtag']=='user') {

    //USER
    $chainuserinput = array();
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

        $is_duplicate = in_array($x['chainuserinput'], $chainuserinput);
        if (!$is_duplicate) {
            array_push($chainuserinput, $x['chainuserinput']);
        } else {
            $stats['users_duplicate']++;
        }

        $count++;
        $es_cache = $this->Users->read(array(
            'userid' => $x['chainuserinput'],
        ));
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
            $this->Users->update($x['chainid'], array(
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
            $stats['users_valid_cachevoid']++;
        }

        //Orphan?
        $total_links = count($this->Chains->read(array(
            '(chainuserdomain='.$x['chainuserinput'].' OR chainusertype='.$x['chainuserinput'].' OR chainusercreator='.$x['chainuserinput'].' OR chainuserinput='.$x['chainuserinput'].' OR chainuseroutput='.$x['chainuserinput'].')' => null,
        )));

        //Fetch from Cache table:
        if (count($es_cache)) {
            $posttext = '@' . $es_cache[0]['userhandle'] . "\n" . $es_cache[0]['username'] . "\n" . $es_cache[0]['usercover'];
        } else {
            $posttext = '@???' . $x['chainvalue'] . "\n" . $x['chainvalue'] . "\nfar fa-user";
        }

        //Append Description if any
        foreach ($this->Chains->read(array(
            'LENGTH(chainvalue) > 0' => null,
            'chainuserinput IN (11035,42628)' => null,
            'chainuseroutput' => $x['chainuserinput'],
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array(), 0, 0) as $social_chain) {
            $posttext .= "\n" . $social_chain['chainvalue'];
        }

        $delete = !$total_links || $x['chainvoid'] > 0 || $is_duplicate;
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
            ($is_duplicate ? '[DUPLICATE]' : '') .
            (!count($es) ? '[users_creaetor_not_found]' : '') .
            (!count($es_cache) ? '[users_valid_cachevoid]' : '') .
            '</td>';
        $table .= '<td>T@' . $x['chainusertype'] . '<br />C@' . $x['chainusercreator'] . '<br />@' . $x['chainuserinput'] . '</td>';
        $table .= '<td><div style="max-width:233px;">' . nl2br(trim(htmlentities($posttext))) . '</div></td>';
        //$table .= '<td><div style="max-width:233px;">'.nl2br(trim(htmlentities($posttext))).'</div></td>';
        $table .= '</tr>';
    }


} elseif($_GET['posthashtag']=='post') {

    //POSTS
    $table .= '<tr>';
    $table .= '<td>&nbsp;</td>';
    $table .= '<td>&nbsp;</td>';
    $table .= '<td><div style="max-width:233px;">INITIAL</div></td>'; //RAW
    $table .= '<td><div style="max-width:233px;">INPUT</div></td>'; //RAW
    $table .= '<td><div style="max-width:233px;">chainvalue</div></td>'; //RAW
    $table .= '<td><div style="max-width:233px;">posttext</div></td>'; //TEXT
    $table .= '<td><div style="max-width:233px;">postdiscover</div></td>'; //DISCOVERY
    $table .= '<td><div style="max-width:233px;">postedit</div></td>'; //EDITOR
    $table .= '<td><div style="max-width:233px;">stats</div></td>'; //EDITOR
    $table .= '</tr>';

    $chainpostinput = array();
    $stats = array(
        'posts_all' => 0,
        'posts_orphan' => 0,
        'posts_empty' => 0,
        'posts_delete' => 0,
        'posts_duplicate' => 0,
        'posts_empty_notvoid' => 0,
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
    foreach($this->Chains->read($filters, array('chainpostinput'), ( isset($_GET['limit']) ? $_GET['limit'] : $max_load ), ( isset($_GET['offset']) ? $_GET['offset'] : 0 ), array('chainid' => 'DESC')) as $x){

        $is_duplicate = in_array($x['chainpostinput'], $chainpostinput);

        if(!$is_duplicate){
            array_push($chainpostinput, $x['chainpostinput']);
        } else {
            $stats['posts_duplicate']++;
        }

        $count++;
        $is = $this->Posts->read(array(
            'postid' => $x['chainpostinput'],
        ));
        $es = $this->Users->read(array(
            'userid' => $x['chainusercreator'],
        ));

        //Orphan?
        $total_links = count($this->Chains->read(array(
            '(chainpostinput='.$x['chainpostinput'].' OR chainpostoutput='.$x['chainpostinput'].')' => null,
        )));
        if(!$total_links){
            $stats['posts_orphan']++;
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

        $core_content = trim($is[0]['posttext']);
        $posttext = $is[0]['posttext'];


        //Remove duplicate:
        $new_posttext = '';
        $current_lines = array();
        $all_lines = explode("\n", $posttext);
        foreach ($all_lines as $line_count => $line) {
            $term = substr(trim($line), 1);
            if(
                !$line_count
                && count($all_lines)>1
                && substr(trim($line), 0, 1)=='#'
                && ctype_alnum($term)
                && ($term==$x['posthashtag'] || !count($this->Posts->read(array(
                        'LOWER(posthashtag)' => strtolower($term),
                    ))))){
                //Remove this line:
                continue;
            }
            if(in_array(substr(trim($line), 0, 1), array('#','@')) || in_array(substr(trim($line), 1, 1), array('#','@'))){
                if(!in_array(trim($line), $current_lines) && strtolower(trim($line))!='@shervin' && strtolower(trim($line))!='@grumo'){
                    $new_posttext .= (strlen($new_posttext) ? "\n" : '').$line;
                    array_push($current_lines, trim($line));
                } else {
                    //Remove this line:
                    continue;
                }
            } else {
                $new_posttext .= (strlen($new_posttext) ? "\n" : '').$line;
            }
        }

        //Did we trim?
        if($new_posttext!=$posttext){
            //Yes, adjust:
            $posttext = $new_posttext;
        }

        $initial_posttext = $posttext;


        $this_media = false;

        //Add Ideas:
        foreach ($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___4486')) . ')' => null, //Ideas
            'chainpostinput' => $x['chainpostinput'],
        ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC')) as $count => $x2) {
            $user = ( strlen($ideas[$x2['chainusertype']]['m__cover'])>=1 && strlen($ideas[$x2['chainusertype']]['m__cover'])<=2 ? $ideas[$x2['chainusertype']]['m__cover'] : '#' );
            if(substr_count($posttext, $user.$x2['posthashtag'])){
                continue;
            }
            if(!$count){
                $core_content .= "\n";
                $posttext .= "\n";
            }

            $posttext .= "\n".$user.$x2['posthashtag'];
            $core_content .= "\n".$user.$x2['posthashtag'];
        }

        if($this_media){
            $has_media = true;
        }

        //Fetch Mentions
        foreach($this->Chains->read(array(
            'chainpostoutput' => $x['chainpostinput'],
            'chainuserinput NOT IN (1,2,32337)' => null,
            'chainusertype IN (' . join(',', $this->config->item('userids___13550')) . ')' => null, //Mentions
        ), array('chainuserinput')) as $count => $x2){

            //Define user:
            $user = ( strlen($mentions[$x2['chainusertype']]['m__cover'])>=1 && strlen($mentions[$x2['chainusertype']]['m__cover'])<=2 ? $mentions[$x2['chainusertype']]['m__cover'] : '@' );

            if(substr_count($posttext, $user.$x2['userhandle'])){
                //Reference already there:
                continue;
            }
            if(!$count){
                $core_content .= "\n";
                $posttext .= "\n";
            }
            $core_content .= "\n".$user.$x2['userhandle'];
            $posttext .= "\n".$user.$x2['userhandle'];
            if(strlen($x2['chainvalue'])){
                $posttext .= ' '.$x2['chainvalue'];
            }

        }


        if(!strlen(trim($core_content))){
            $stats['posts_empty']++;
            $stats['posts_empty_notvoid']++;
        }

        $delete = !$total_links || $x['chainvoid']>0 || !strlen(trim($core_content)) || $is_duplicate;
        if($delete){
            $stats['posts_delete']++;
        }

        $post_index = $x;

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
            'chainvalue' =>  '#'.$x['posthashtag']."\n".$post_index['chainvalue'],
        ));

        */


        $table .= '<tr>';

        $table .= '<td>'.$x['chainid'].'<br />V'.$x['chainvoid'].'/'.$count.'/'.
            ( $delete ? '[DELETED POST]' : '' ).
            ( !$total_links ? '[ORPHAN]' : '') .
            ( $x['chainvoid']>0 ? '[VOID]' : '' ).
            ( $this_media ? '[ISMEDIA]' : '' ).
            ( !strlen(trim($core_content)) ? '[EMPTY]' : '' ).
            ( $is_duplicate ? '[DUPLICATE]' : '' ).
            ( !count($es) ? '[posts_voidcreaetor]' : '' ).
            ( !count($is) ? '[posts_valid_cachevoid]' : '' ).
            '</td>';

        $table .= '<td>T@'.$x['chainusertype'].'<br />C@'.$x['chainusercreator'].'<br />##'.$x['chainpostinput'].'</td>';
        $table .= '<td><div style="max-width:233px;">'.nl2br($initial_posttext).'</div></td>'; //INPUT
        $table .= '<td><div style="max-width:233px;">'.nl2br($posttext).'</div></td>'; //INPUT
        $table .= '<td><div style="max-width:233px;">'.nl2br($post_index['chainvalue']).'</div></td>'; //RAW
        $table .= '<td><div style="max-width:233px;">'.nl2br($post_index['posttext']).'</div></td>'; //TEXT
        $table .= '<td><div style="max-width:233px;">'.($post_index['postdiscover']).'</div></td>'; //DISCOVER
        $table .= '<td><div style="max-width:233px;">'.($post_index['postedit']).'</div></td>'; //EDIT
        $table .= '<td><div style="max-width:233px;">'.print_r($post_index['actionstats'], true).'</div></td>'; //EDIT
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