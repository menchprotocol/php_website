<?php

boost_power();
$mentions = $this->config->item('users___13550');
$ideas = $this->config->item('users___4486');
$count = 0;

//Translator
$table = '<table class="table table-sm table-striped stats-table mini-stats-table" border="1">';

if($focus_i['posthashtag']=='Discotique2024') {

    //Update
    /*
     *


    foreach(array(1733119,
                1733121,
                1733123,
                1733125,
                1733127,
                1733129,
                1733131
            ) as $daysofweek){

    }
    echo 'yayyy';


    $this->Ideachains->create(array(
        'chainusertype' => 43513,
        'chainusercreator' => 1,
        'chainpostoutput' => 1733119,
        'chainuserinput' => 1636421, //Discotique Leaders 25
    ));
    $this->Ideachains->create(array(
        'chainusertype' => 43513,
        'chainusercreator' => 1,
        'chainpostoutput' => 1733121,
        'chainuserinput' => 27093, //Trusted
    ));



*/


    echo 'done done';

    foreach($this->Ideachains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainusertype' => 12273,
    ), array(''), 0, 0, array('chainid' => 'ASC')) as $x){

    }

    foreach ($this->Posts->read(array(
        'LOWER(posthashtag)' => strtolower(view_valid_user_post($action_command1)),
    )) as $i) {

    }
    $this->Posts->update($ref['postid'], array(
        'posttext' => str_replace('#' . $is[0]['posthashtag'], '#' . trim($_POST['save_posthashtag']), $ref['posttext']),
    ), $user_session['userid']);


} elseif($focus_i['posthashtag']=='Discotique2025') {

    //USER
    $chainuseroutput = array();
    $stats = array(
        'users_all' => 0,
        'users_delete' => 0,
        'users_duplicate' => 0,
        'users_void' => 0,
        'users_voidcreaetor' => 0,
        'users_void_cachevalid' => 0,
        'users_valid_cachevoid' => 0,
    );

    foreach ($this->Ideachains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainusertype' => 12274,
    ), array(), 377, 0, array('chainid' => 'ASC')) as $x) {

        $is_duplicate = in_array($x['chainuseroutput'], $chainuseroutput);
        if (!$is_duplicate) {
            array_push($chainuseroutput, $x['chainuseroutput']);
        } else {
            $stats['users_duplicate']++;
        }

        $count++;
        $es_cache = $this->Users->read(array(
            'userid' => $x['chainuseroutput'],
        ));
        $es = $this->Users->read(array(
            'userid' => $x['chainusercreator'],
        ));

        $stats['users_all']++;
        if ($x['chainvoid'] > 0) {
            $stats['users_void']++;
        } elseif (!count($es)) {
            $stats['users_voidcreaetor']++;
        }
        if ($x['chainvoid'] > 0 && count($es_cache)) {
            $stats['users_void_cachevalid']++;
        }
        if (!$x['chainvoid'] && !count($es_cache)) {
            $stats['users_valid_cachevoid']++;
        }


        //Fetch from Cache table:
        if (count($es_cache)) {
            $posttext = '@' . $es_cache[0]['userhandle'] . "\n" . $es_cache[0]['username'] . "\n" . $es_cache[0]['usercover'];
        } else {
            $posttext = '@???' . $x['chainvalue'] . "\n" . $x['chainvalue'] . "\nfar fa-user";
        }

        //Append Description if any
        foreach ($this->Ideachains->read(array(
            'LENGTH(chainvalue) > 0' => null,
            'chainuserinput IN (11035,42628)' => null,
            'chainuseroutput' => $x['chainuseroutput'],
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array(), 0, 0) as $social_chain) {
            $posttext .= "\n" . $social_chain['chainvalue'];
        }

        $delete = $x['chainvoid'] > 0 || $is_duplicate || (!$x['chainvoid'] && !count($es)) || (!$x['chainvoid'] && !count($es_cache));
        if ($delete) {
            $stats['users_delete']++;
        }

        $table .= '<tr>';
        $table .= '<td>' . $x['chainid'] . '<br />V' . $x['chainvoid'] . '/' . $count . '/' .
            ($delete ? '[DELETE]' : '') .
            ($x['chainvoid'] > 0 ? '[VOID]' : '') .
            ($is_duplicate ? '[DUPLICATE]' : '') .
            (!$x['chainvoid'] && !count($es) ? '[users_voidcreaetor]' : '') .
            (!$x['chainvoid'] && !count($es_cache) ? '[users_valid_cachevoid]' : '') .
            '</td>';
        $table .= '<td>T@' . $x['chainusertype'] . '<br />C@' . $x['chainusercreator'] . '<br />@' . $x['chainuseroutput'] . '</td>';
        $table .= '<td><div style="max-width:233px;">' . nl2br(trim(htmlentities($posttext))) . '</div></td>';
        //$table .= '<td><div style="max-width:233px;">'.nl2br(trim(htmlentities($posttext))).'</div></td>';
        $table .= '</tr>';
    }

} elseif(0){

    //Scan/Fix 3x Media Links

    $table .= '<tr>';
    $table .= '<td>ID</td>';
    $table .= '<td>Type</td>';
    $table .= '<td>NOW</td>';
    $table .= '<td>FIXED</td>';
    $table .= '<td>DELETE?</td>';
    $table .= '</tr>';

    $success = array(
        1326 => 0,
        4258 => 0,
        4259 => 0,
        4260 => 0,
    );
    $fail = array(
        1326 => 0,
        4258 => 0,
        4259 => 0,
        4260 => 0,
    );
    $fixed = array(
        1326 => 0,
        4258 => 0,
        4259 => 0,
        4260 => 0,
    );
    $delete = array(
        1326 => 0,
        4258 => 0,
        4259 => 0,
        4260 => 0,
    );


    foreach ($this->Ideachains->read(array(
        'chainuserinput IN (' . join(',', $this->config->item('userids___1735577')) . ')' => null, //USER DISPLAY
        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
    ), array('chainuseroutput'), 0, 0, array('chainuserinput' => 'ASC')) as $x) {

        $must_delete = false;
        $newchainvalue = false;

        if ($x['chainuserinput'] == 1326) {

            if(filter_var($x['chainvalue'], FILTER_VALIDATE_URL)){
                $success[$x['chainuserinput']]++;
            } else {
                $fail[$x['chainuserinput']]++;
                //See if we can find it?
                foreach ($this->Ideachains->read(array(
                    'chainuseroutput' => $x['chainuseroutput'],
                    'LENGTH(chainvalue) > 0' => null,
                    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                ), array('chainuserinput'), 0, 0, array('chainuserinput' => 'ASC')) as $x2) {
                    if(filter_var($x2['chainvalue'], FILTER_VALIDATE_URL)){
                        $fixed[$x['chainuserinput']]++;
                        $newchainvalue = $x2['chainvalue'];
                        break;
                    }
                }
                if(!$newchainvalue){
                    $must_delete = true;
                }
            }

        } elseif ($x['chainuserinput'] == 4258) {

            //Video
            if(strlen($x['chainvalue'])){
                $success[$x['chainuserinput']]++;
            } else {
                $fail[$x['chainuserinput']]++;
                //See if we can find it?
                foreach ($this->Ideachains->read(array(
                    'chainuserinput' => 42660, //Media Public ID
                    'chainuseroutput' => $x['chainuseroutput'],
                    'LENGTH(chainvalue) > 0' => null,
                    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                ), array('chainuseroutput'), 0, 0, array('chainuserinput' => 'ASC')) as $x2) {
                    $fixed[$x['chainuserinput']]++;
                    $newchainvalue = $x2['chainvalue'];
                    break;
                }
                if(!$newchainvalue){
                    $must_delete = true;
                }
            }

        } elseif ($x['chainuserinput'] == 4259) {

            //Audio
            if(filter_var($x['chainvalue'], FILTER_VALIDATE_URL)){
                $success[$x['chainuserinput']]++;
            } else {
                $fail[$x['chainuserinput']]++;
                //See if we can find it?
                foreach ($this->Ideachains->read(array(
                    'chainuserinput' => 42693, //Secure URL
                    'chainuseroutput' => $x['chainuseroutput'],
                    'LENGTH(chainvalue) > 0' => null,
                    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                ), array('chainuseroutput'), 0, 0, array('chainuserinput' => 'ASC')) as $x2) {
                    if(filter_var($x2['chainvalue'], FILTER_VALIDATE_URL)){
                        $fixed[$x['chainuserinput']]++;
                        $newchainvalue = $x2['chainvalue'];
                        break;
                    }
                }
                if(!$newchainvalue){
                    $must_delete = true;
                }
            }

        } elseif ($x['chainuserinput'] == 4260) {

            //Image
            if(filter_var($x['chainvalue'], FILTER_VALIDATE_URL)){
                $success[$x['chainuserinput']]++;
            } else {
                $fail[$x['chainuserinput']]++;
                //See if we can find it?
                foreach ($this->Ideachains->read(array(
                    'chainuseroutput' => $x['chainuseroutput'],
                    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                ), array('chainuserinput'), 0) as $x2) {
                    if(filter_var($x2['chainvalue'], FILTER_VALIDATE_URL)){
                        $fixed[$x['chainuserinput']]++;
                        $newchainvalue = $x2['chainvalue'];
                        break;
                    }
                }
                if(!$newchainvalue){
                    if(filter_var($x['usercover'], FILTER_VALIDATE_URL)){
                        $fixed[$x['chainuserinput']]++;
                        $newchainvalue = $x['usercover'];
                    } else {
                        $must_delete = true;
                    }
                }
            }

        } else {

            $must_delete = true;

        }


        if(strlen($newchainvalue) && 0){
            $this->Ideachains->update($x['chainid'], array(
                'chainvalue' => $newchainvalue,
            ));
        } elseif($must_delete && 0){
            //Delete chain:
            $this->Ideachains->delete($x['chainid']);
        }



        $table .= '<tr>';

        $table .= '<td>'.$x['chainid'].'</td>';
        $table .= '<td>'.$x['chainuserinput'].'</td>';
        $table .= '<td><div style="max-width:233px;">'.$x['chainvalue'].'</div></td>';
        $table .= '<td><div style="max-width:233px;">'.( strlen($newchainvalue) ? 'FIXED: ' : '' ).$newchainvalue.'</div></td>';
        $table .= '<td>'.( $must_delete ? 'DELETE' : '' ).'</td>';

        $table .= '</tr>';

        if($must_delete){
            $delete[$x['chainuserinput']]++;
        }

    }

    $table .= '</table>';

    echo 'Success:';
    print_r($success);
    echo '<hr />Fail:';
    print_r($fail);
    echo '<hr />Fixed:';
    print_r($fixed);
    echo '<hr />Delete:';
    print_r($delete);
    echo $table;

} elseif($focus_i['posthashtag']=='YourBio') {



    if(isset($_GET['reset'])){
        $q = $this->db->query('Update ideachains SET chainpostinput=0 WHERE chainusertype=12273 AND chainpostinput>0;');
        print_r(array('reset_result' => $q->result_array()));
        die('done');
    }

    //POSTS
    $table .= '<tr>';
    $table .= '<td>&nbsp;</td>';
    $table .= '<td>&nbsp;</td>';
    $table .= '<td><div style="max-width:233px;">INITIAL</div></td>'; //RAW
    $table .= '<td><div style="max-width:233px;">INPUT</div></td>'; //RAW
    $table .= '<td><div style="max-width:233px;">postchain</div></td>'; //RAW
    $table .= '<td><div style="max-width:233px;">posttext</div></td>'; //TEXT
    $table .= '<td><div style="max-width:233px;">postdiscover</div></td>'; //DISCOVERY
    $table .= '<td><div style="max-width:233px;">postedit</div></td>'; //EDITOR
    $table .= '<td><div style="max-width:233px;">stats</div></td>'; //EDITOR
    $table .= '</tr>';

    $chainpostoutput = array();
    $stats = array(
        'posts_all' => 0,
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
        'chainvoid >=' => 0,
        'chainusertype' => 12273,
    );
    if(isset($_GET['id'])){
        $filters['(chainid='.$_GET['id'].' OR chainpostinput='.$_GET['id'].')'] = null;
    } else {
        //Filter for mass editing;
        $filters['chainpostinput'] = 0;
    }
    $has_media = false;
    foreach($this->Ideachains->read($filters, array('chainpostoutput'), ( isset($_GET['limit']) ? $_GET['limit'] : 1 ), ( isset($_GET['offset']) ? $_GET['offset'] : 0 ), array('chainid' => 'DESC')) as $x){

        $is_duplicate = in_array($x['chainpostoutput'], $chainpostoutput);

        if(!$is_duplicate){
            array_push($chainpostoutput, $x['chainpostoutput']);
        } else {
            $stats['posts_duplicate']++;
        }

        $count++;
        $is = $this->Posts->read(array(
            'postid' => $x['chainpostoutput'],
        ));
        $es = $this->Users->read(array(
            'userid' => $x['chainusercreator'],
        ));

        $stats['posts_all']++;
        if($x['chainvoid']>0){
            $stats['posts_void']++;
        } elseif(!count($es)){
            $stats['posts_voidcreaetor']++;
        }
        if($x['chainvoid']>0 && count($is)){
            $stats['posts_void_cachevalid']++;
        }
        if(!$x['chainvoid'] && !count($is)){
            $stats['posts_valid_cachevoid']++;
        }


        if(!count($is)){
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
        foreach ($this->Ideachains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___4486')) . ')' => null, //Ideas
            'chainpostinput' => $x['chainpostoutput'],
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
        foreach($this->Ideachains->read(array(
            'chainpostoutput' => $x['chainpostoutput'],
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
            if(!$x['chainvoid']){
                $stats['posts_empty_notvoid']++;
            }
        }

        $delete = $x['chainvoid']>0 || !strlen(trim($core_content)) || $is_duplicate || (!$x['chainvoid'] && !count($es)) || (!$x['chainvoid'] && !count($is));
        if($delete){
            $stats['posts_delete']++;
        }

        $post_index = post_index($posttext, $x['postid'], $x['chainusercreator'], $x['posthashtag']);
        $this->Posts->update($x['chainid'], array(
            'posttext' => $post_index['posttext'],
            'postdiscover' => $post_index['postdiscover'],
            'postedit' => $post_index['postedit'],
        ));

        if(!$x['chainpostinput']){
            $this->db->where('chainid', $x['chainid']);
            $this->db->update('ideachains', array(
                'chainpostinput' =>  $x['chainid'],
                'chainvalue' =>  '#'.$x['posthashtag']."\n".$post_index['postchain'],
            ));
        }


        $table .= '<tr>';

        $table .= '<td>'.$x['chainid'].'<br />V'.$x['chainvoid'].'/'.$count.'/'.
            ( $delete ? '[DELETE]' : '' ).
            ( $x['chainvoid']>0 ? '[VOID]' : '' ).
            ( $this_media ? '[ISMEDIA]' : '' ).
            ( !strlen(trim($core_content)) ? '[EMPTY]' : '' ).
            ( $is_duplicate ? '[DUPLICATE]' : '' ).
            ( !$x['chainvoid'] && !count($es) ? '[posts_voidcreaetor]' : '' ).
            ( !$x['chainvoid'] && !count($is) ? '[posts_valid_cachevoid]' : '' ).
            '</td>';

        $table .= '<td>T@'.$x['chainusertype'].'<br />C@'.$x['chainusercreator'].'<br />##'.$x['chainpostoutput'].'</td>';
        $table .= '<td><div style="max-width:233px;">'.nl2br($initial_posttext).'</div></td>'; //INPUT
        $table .= '<td><div style="max-width:233px;">'.nl2br($posttext).'</div></td>'; //INPUT
        $table .= '<td><div style="max-width:233px;">'.nl2br($post_index['postchain']).'</div></td>'; //RAW
        $table .= '<td><div style="max-width:233px;">'.nl2br($post_index['posttext']).'</div></td>'; //TEXT
        $table .= '<td><div style="max-width:233px;">'.($post_index['postdiscover']).'</div></td>'; //DISCOVER
        $table .= '<td><div style="max-width:233px;">'.($post_index['postedit']).'</div></td>'; //EDIT
        $table .= '<td><div style="max-width:233px;">'.print_r($post_index['actionstats'], true).'</div></td>'; //EDIT
        $table .= '</tr>';

    }
}

$table .= '</table>';


print_r($stats);
echo $table;
echo '<style> img { max-width: 100% !important;; }</style>';
