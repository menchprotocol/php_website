<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }


    function ss(){
        echo htmlentities(file_get_contents('https://www.codementor.io/blog/angularjs-interview-questions-answers-du1081n7p'));
    }

    //Cache of cron jobs as of now [keep in sync when updating cron file]
    //* * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron fn___facebook_attachment_sync
    //*/5 * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron message_drip
    //*/6 * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron fn___save_media_to_cdn
    //31 * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron fn___in_metadata_update
    //30 2 * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron fn___update_algolia b 0
    //30 4 * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron fn___update_algolia u 0
    //30 3 * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron e_score_recursive
    //30 3 * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron gephi

    function gephi(){

        //Populates the nodes and edges table for Gephi https://gephi.org network visualizer
        //TODO Fix issue that node IDs can be overlapping as data grows...

        //Boost processing power:
        fn___boost_power();

        //Empty both tables:
        $this->db->query("TRUNCATE TABLE public.gephi_edges CONTINUE IDENTITY RESTRICT;");
        $this->db->query("TRUNCATE TABLE public.gephi_nodes CONTINUE IDENTITY RESTRICT;");

        //Load intent link types:
        $en_all_4594 = $this->config->item('en_all_4594');

        //To make sure intent/entity IDs are unique:
        $id_prefix = array(
            'in' => 100,
            'en' => 200,
        );

        //Size of nodes:
        $node_size = array(
            'in' => 3,
            'en' => 2,
            'msg' => 1,
        );

        //Add intents:
        $ins = $this->Database_model->fn___in_fetch(array('in_status >=' => 0));
        foreach($ins as $in){

            //Prep metadata:
            $in_metadata = ( strlen($in['in_metadata']) > 0 ? unserialize($in['in_metadata']) : array());

            //Add intent node:
            $this->db->insert('gephi_nodes', array(
                'id' => $id_prefix['in'].$in['in_id'],
                'label' => $in['in_outcome'],
                //'size' => ( isset($in_metadata['in__tree_max_seconds']) ? round(($in_metadata['in__tree_max_seconds']/3600),0) : 0 ), //Max time
                'size' => $node_size['in'],
                'node_type' => 1, //Intent
                'node_status' => $in['in_status'],
            ));

            //Fetch children:
            foreach($this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 0, //New+
                'in_status >=' => 0, //New+
                'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                'tr_parent_intent_id' => $in['in_id'],
            ), array('in_child'), 0, 0) as $in_child){

                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['in'].$in_child['tr_parent_intent_id'],
                    'target' => $id_prefix['in'].$in_child['tr_child_intent_id'],
                    'label' => $en_all_4594[$in_child['tr_type_entity_id']]['m_name'], //TODO maybe give visibility to points/condition here?
                    'weight' => 1, //TODO Maybe update later?
                    'edge_type_en_id' => $in_child['tr_type_entity_id'],
                    'edge_status' => $in_child['tr_status'],
                ));

            }
        }


        //Add entities:
        $ens = $this->Database_model->fn___en_fetch(array('en_status >=' => 0));
        foreach($ens as $en){

            //Add entity node:
            $this->db->insert('gephi_nodes', array(
                'id' => $id_prefix['en'].$en['en_id'],
                'label' => $en['en_name'],
                'size' => $node_size['en'],
                'node_type' => 2, //Entity
                'node_status' => $en['en_status'],
            ));

            //Fetch children:
            foreach($this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 0, //New+
                'en_status >=' => 0, //New+
                'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'tr_parent_entity_id' => $en['en_id'],
            ), array('en_child'), 0, 0) as $en_child){

                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$en_child['tr_parent_entity_id'],
                    'target' => $id_prefix['en'].$en_child['tr_child_entity_id'],
                    'label' => $en_all_4594[$en_child['tr_type_entity_id']]['m_name'].': '.$en_child['tr_content'],
                    'weight' => 1, //TODO Maybe update later?
                    'edge_type_en_id' => $en_child['tr_type_entity_id'],
                    'edge_status' => $en_child['tr_status'],
                ));

            }
        }

        //Add messages:
        $messages = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0, //New+
            'in_status >=' => 0, //New+
            'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
            //'tr_type_entity_id' => 4231, //Intent Messages only
        ), array('in_child'), 0, 0);
        foreach($messages as $message) {

            //Add message node:
            $this->db->insert('gephi_nodes', array(
                'id' => $message['tr_id'],
                'label' => $en_all_4594[$message['tr_type_entity_id']]['m_name'] . ': ' . $message['tr_content'],
                'size' => $node_size['msg'],
                'node_type' => $message['tr_type_entity_id'], //Message type
                'node_status' => $message['tr_status'],
            ));

            //Add child intent link:
            $this->db->insert('gephi_edges', array(
                'source' => $message['tr_id'],
                'target' => $id_prefix['in'].$message['tr_child_intent_id'],
                'label' => 'Child Intent',
                'weight' => 1, //TODO Maybe update later?
            ));

            //Add parent intent link?
            if ($message['tr_parent_intent_id'] > 0) {
                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['in'].$message['tr_parent_intent_id'],
                    'target' => $message['tr_id'],
                    'label' => 'Parent Intent',
                    'weight' => 1, //TODO Maybe update later?
                ));
            }

            //Add parent entity link?
            if ($message['tr_parent_entity_id'] > 0) {
                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$message['tr_parent_entity_id'],
                    'target' => $message['tr_id'],
                    'label' => 'Parent Entity',
                    'weight' => 1, //TODO Maybe update later?
                ));
            }

        }

        echo count($ins).' intents & '.count($ens).' entities & '.count($messages).' messages synced.';
    }

    function clear_removed(){

        exit;

        //A function to delete removed intents, entities and transactions:
        $stats = array(
            'deleted_ins' => 0,
            'deleted_ens' => 0,
            'deleted_trs' => 0,
        );

        //Removed intents:
        foreach($this->Database_model->fn___in_fetch(array(
            'in_status' => -1, //Removed
        )) as $in){

            //Remove intent transactions:
            $this->db->query("DELETE from table_ledger WHERE (tr_parent_intent_id=".$in['in_id']." OR tr_child_intent_id=".$in['in_id'].")");
            $stats['deleted_trs'] += $this->db->affected_rows();

            //Remove intent:
            $this->db->query("DELETE from table_intents WHERE in_id=".$in['in_id']);
            $stats['deleted_ins'] += $this->db->affected_rows();

        }

        //Removed entities:
        foreach($this->Database_model->fn___en_fetch(array(
            'en_status' => -1, //Removed
        )) as $en){

            //Remove entity transactions:
            $this->db->query("DELETE from table_ledger WHERE (tr_parent_entity_id=".$en['en_id']." OR tr_child_entity_id=".$en['en_id'].")");
            $stats['deleted_trs'] += $this->db->affected_rows();

            //Remove entity:
            $this->db->query("DELETE from table_entities WHERE en_id=".$en['en_id']);
            $stats['deleted_ens'] += $this->db->affected_rows();

        }

        fn___echo_json($stats);
    }

    function name_updates($limit, $adjust = 0){

        //Intent verb start
        foreach($this->Database_model->fn___in_fetch(array('in_status >=' => 0), array(), $limit) as $in){

            $in_verb_entity_id = starting_verb_id($in['in_outcome']);

            if(!$in_verb_entity_id){

                echo '<a href="/intents/'.$in['in_id'].'">'.$in['in_outcome'].'</a>';

                if($adjust){

                    $this->Database_model->fn___in_update($in['in_id'], array(
                        'in_status' => -1
                    ), true, 1);

                    $links_removed = $this->Matrix_model->unlink_intent($in['in_id'] , 1);

                    echo ' Intent and its '.$links_removed.' links REMOVED';

                }

                echo '<br />';
            } elseif($adjust) {
                $this->Database_model->fn___in_update($in['in_id'], array(
                    'in_verb_entity_id' => $in_verb_entity_id
                ), true, 1);
            }
        }

    }


    function info()
    {
        echo phpinfo();
    }


    function urls(){

        //Migrate from URL to People/ORG
        $current_urls = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0,
            'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Entity URL Links
        ), array('en_child'), 999999, 0, array('tr_content' => 'ASC'));

        //Echo table:
        echo '<table class="table table-condensed table-striped stats-table sources-mined hidden" style="max-width:100%;">';

        //Object Header:
        echo '<tr style="font-weight: bold;">';
        echo '<td style="text-align: left;"></td>';
        echo '<td style="text-align: left;">Parent</td>';
        echo '<td style="text-align: left;"></td>';
        echo '<td style="text-align: left;">Current URL</td>';
        echo '<td style="text-align: left;"></td>';
        echo '<td style="text-align: left;">Child</td>';
        echo '</tr>';


        foreach ($current_urls as $i=>$tr){

            $ff = null;

            //Detect domain parent:
            $domain_analysis = fn___analyze_domain($tr['tr_content']);

            //Fetch parent:
            $parent_ens = $this->Database_model->fn___en_fetch(array(
                'en_id' => $tr['tr_parent_entity_id'],
            ), array('en__parents'));

            //See if we have a parent error:
            $domain_not_domain = ($domain_analysis['url_is_root'] && $tr['tr_parent_entity_id']!=1326);

            $domain_unsync = ($domain_analysis['url_is_root'] && !($domain_analysis['url_clean_domain']==$tr['tr_content']));

            //If not a domain, it's parent should be connected to domains:
            if(!$domain_analysis['url_is_root']){
                
                $grandpa_error = true; //Assume we have an issue unless proven otherwise...
                foreach($parent_ens[0]['en__parents'] as $grandpa_en){
                    if($grandpa_en['en_id']==1326){
                        //This is connected to domains, see if the value is the domain:
                        $gp_domain_analysis = fn___analyze_domain($grandpa_en['tr_content']);
                        if($gp_domain_analysis['url_is_root']){
                            $grandpa_error = false;
                        }
                    }
                }

                $domain_entity = $this->Matrix_model->fn___sync_domain($tr['tr_content'], 1);


                if($grandpa_error && isset($domain_entity['en_domain']['en_id'])){

                    //Remove domain link:
                    $this->Database_model->fn___tr_update($tr['tr_id'], array(
                        'tr_content' => null,
                        'tr_type_entity_id' => 4230, //Raw
                    ), 1);

                    //Link to domain entity:
                    $this->Database_model->fn___tr_create(array(
                        'tr_status' => 2,
                        'tr_miner_entity_id' => 1,
                        'tr_type_entity_id' => $tr['tr_type_entity_id'],
                        'tr_parent_entity_id' => $domain_entity['en_domain']['en_id'],
                        'tr_child_entity_id' => $tr['en_id'],
                        'tr_content' => $tr['tr_content'],
                    ));

                    $ff = 'DONEDONEDONE';

                }

            } else {
                
                $grandpa_error = false;
                
            }

            //Fix root:
            if($domain_unsync){
                $this->Database_model->fn___tr_update($tr['tr_id'], array(
                    'tr_content' => $domain_analysis['url_clean_domain'],
                ), 1);
            }


            if($domain_not_domain){

                //move domain to domain entity:
                $this->Database_model->fn___tr_create(array(
                    'tr_status' => 2,
                    'tr_miner_entity_id' => 1,
                    'tr_type_entity_id' => 4256, //Generic URL (Domain home pages should always be generic, see above for logic)
                    'tr_parent_entity_id' => 1326, //Domain Entity
                    'tr_child_entity_id' => $tr['en_id'],
                    'tr_content' => $tr['tr_content'],
                ));

                //Move URL to domain:
                $this->Database_model->fn___tr_update($tr['tr_id'], array(
                    'tr_content' => null,
                    'tr_type_entity_id' => 4230, //Raw
                ));
            }


            //Check to see all good:

            //Object Header:
            echo '<tr>';
            echo '<td style="text-align: left; border-top: 1px solid #CCC;">'.($i+1).'</td>';
            echo '<td style="text-align: left; border-top: 1px solid #CCC;"><a href="/entities/'.$parent_ens[0]['en_id'].'" target="_blank" '.( $domain_not_domain || $grandpa_error ? 'style="font-weight:bold; color:#FF0000; "' : '').'>'.$parent_ens[0]['en_name'].'</a></td>';
            echo '<td style="text-align: left; border-top: 1px solid #CCC;">'.( $domain_analysis['url_is_root'] ? ' <b style="color:#0000FF; ">*</b>' : '' ).'</td>';
            echo '<td style="text-align: left; border-top: 1px solid #CCC;"><a href="'.$tr['tr_content'].'" target="_blank">'.$tr['tr_content'].'</a><div>'.$domain_analysis['url_clean_domain'].( $domain_unsync ? ' SYNC TO: '.$domain_analysis['url_clean_domain'] : '').'</div></td>';
            echo '<td style="text-align: left; border-top: 1px solid #CCC;">'.( !$domain_analysis['url_is_root'] ? ( isset($domain_entity['en_domain']['en_id']) ? '@'.$domain_entity['en_domain']['en_id'].' '.$domain_entity['en_domain']['en_name']  : 'MISSING!!!' ) : ''  ).$ff.'</td>';
            echo '<td style="text-align: left; border-top: 1px solid #CCC;"><a href="/entities/'.$tr['en_id'].'" target="_blank">'.$tr['en_name'].'</a></td>';
            echo '</tr>';

        }

        echo '</table>';

    }

    function test($fb_messenger_format = 0){

        $quick_replies = array();

        if(isset($_POST['inputt'])){

            $p = $this->Chat_model->fn___dispatch_message($_POST['inputt'], ( intval($_POST['recipient_en']) ? array('en_id' => $_POST['recipient_en']) : array() ), $fb_messenger_format, $quick_replies);

            if($fb_messenger_format || !$p['status']){
                fn___echo_json(array(
                    'analyze' => fn___extract_message_references($_POST['inputt']),
                    'results' => $p,
                ));
            } else {
                //HTML:
                echo $p['output_messages'][0]['message_body'];
            }

        } else {
            echo '<form method="POST" action="">';
            echo '<textarea name="inputt" style="width:400px; height: 200px;"></textarea><br />';
            echo '<input type="number" name="recipient_en" value="1"><br />';
            echo '<input type="submit" value="GO">';
            echo '</form>';
        }

    }


    function pay(){

        exit; //Maybe use to update all rates if needed?

        //Issue coins for each transaction type:
        $all_engs = $this->Database_model->fn___tr_fetch(array(), array('en_type'), 0, 0, array('trs_count' => 'DESC'), 'COUNT(tr_type_entity_id) as trs_count, en_name, tr_type_entity_id', 'tr_type_entity_id, en_name');

        //return fn___echo_json($all_engs);

        //Give option to select:
        foreach ($all_engs as $tr) {

            //DOes it have a rate?
            $rate_trs = $this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 2, //Published+
                'en_status >=' => 2, //Published+
                'tr_type_entity_id' => 4319, //Number
                'tr_parent_entity_id' => 4374, //Mench Coins
                'tr_child_entity_id' => $tr['tr_type_entity_id'],
            ), array('en_child'), 1);

            if(count($rate_trs) > 0){
                //Issue coins at this rate:
                $this->db->query("UPDATE table_ledger SET tr_coins = '".$rate_trs[0]['tr_content']."' WHERE tr_type_entity_id = " . $tr['tr_type_entity_id']);
            }

        }

        echo 'done';

    }

    function fn___matrix_cache(){

        /*
         *
         * This function prepares a PHP-friendly text to be copies to matrix_cache.php
         * (which is auto loaded) to provide a cache image of some entities in
         * the tree for faster application processing.
         *
         * */

        //First first all entities that have Cache in PHP Config @4527 as their parent:
        $config_ens = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0,
            'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'tr_parent_entity_id' => 4527,
        ), array('en_child'), 0);

        echo '//Generated '.date("Y-m-d H:i:s").' PST<br />';

        foreach($config_ens as $en){

            //Now fetch all its children:
            $children = $this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 2,
                'en_status >=' => 2,
                'tr_parent_entity_id' => $en['tr_child_entity_id'],
                'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            ), array('en_child'), 0, 0, array('tr_order' => 'ASC', 'en_id' => 'ASC'));


            $child_ids = array();
            foreach($children as $child){
                array_push($child_ids , $child['en_id']);
            }

            echo '<br />//'.$en['en_name'].':<br />';
            echo '$config[\'en_ids_'.$en['tr_child_entity_id'].'\'] = array('.join(', ',$child_ids).');<br />';
            echo '$config[\'en_all_'.$en['tr_child_entity_id'].'\'] = array(<br />';
            foreach($children as $child){

                //Do we have an omit command?
                if(substr_count($en['tr_content'], '&var_trimcache=') == 1){
                    $child['en_name'] = trim(str_replace( str_replace('&var_trimcache=','',$en['tr_content']) , '', $child['en_name']));
                }

                //Fetch all parents for this child:
                $child_parent_ids = array(); //To be populated soon
                $child_parents = $this->Database_model->fn___tr_fetch(array(
                    'tr_status >=' => 2,
                    'en_status >=' => 2,
                    'tr_child_entity_id' => $child['en_id'],
                    'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                ), array('en_parent'), 0);
                foreach($child_parents as $cp_en){
                    array_push($child_parent_ids, $cp_en['en_id']);
                }

                echo '&nbsp;&nbsp;&nbsp;&nbsp; '.$child['en_id'].' => array(<br />';

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_icon\' => \''.htmlentities($child['en_icon']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_name\' => \''.$child['en_name'].'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_desc\' => \''.str_replace('\'','\\\'',$child['tr_content']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_parents\' => array('.join(', ',$child_parent_ids).'),<br />';

                echo '&nbsp;&nbsp;&nbsp;&nbsp; ),<br />';

            }
            echo ');<br />';
        }
    }

    function fn___in_metadata_update($in_id = 0, $update_c_table = 1)
    {

        /*
         *
         * Updates the metadata cache data for intents starting at $in_id.
         *
         * If $in_id is not provided, it defaults to in_mission_id which
         * is the highest level of intent in the Mench tree.
         *
         * */

        if(!$in_id){
            $in_id = $this->config->item('in_mission_id');
        }
        //Cron Settings: 31 * * * *
        //Syncs intents with latest caching data:

        $sync = $this->Matrix_model->fn___in_recursive_fetch($in_id, true, $update_c_table);
        if (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) {
            //Now redirect;
            header('Location: ' . $_GET['redirect']);
        } else {
            //Remove the long "in_tree" variable which makes the page load slow:
            unset($sync['in_tree']);

            //Show json:
            fn___echo_json($sync);
        }
    }


    //I cannot update algolia from my local server so if fn___is_dev() is true I will call mench.com/cron/fn___update_algolia to sync my local change using a live end-point:
    function fn___update_algolia($obj = null, $obj_id = 0)
    {
        fn___echo_json($this->Database_model->fn___update_algolia($obj, $obj_id));
    }


    function fn___list_duplicate_ins()
    {

        //Do a query to detect intents with the exact same title:
        $q = $this->db->query('select in1.* from table_intents in1 where (select count(*) from table_intents in2 where in2.in_outcome = in1.in_outcome) > 1 ORDER BY in1.in_outcome ASC');
        $duplicates = $q->result_array();

        $prev_title = null;
        foreach ($duplicates as $in) {
            if ($prev_title != $in['in_outcome']) {
                echo '<hr />';
                $prev_title = $in['in_outcome'];
            }

            echo '<a href="/intents/' . $in['in_id'] . '">#' . $in['in_id'] . '</a> ' . $in['in_outcome'] . '<br />';
        }
    }

    function fn___list_duplicate_ens()
    {

        $q = $this->db->query('select en1.* from table_entities en1 where (select count(*) from table_entities en2 where en2.en_name = en1.en_name) > 1 ORDER BY en1.en_name ASC');
        $duplicates = $q->result_array();

        $prev_title = null;
        foreach ($duplicates as $u) {
            if ($prev_title != $u['en_name']) {
                echo '<hr />';
                $prev_title = $u['en_name'];
            }

            echo '<a href="/entities/' . $u['en_id'] . '">#' . $u['en_id'] . '</a> ' . $u['en_name'] . '<br />';
        }
    }


    function e_score_recursive($u = array())
    {

        //Updates en_trust_score based on number/value of connections to other intents/entities
        //Cron Settings: 2 * * * 30

        //Define weights:
        $score_weights = array(
            'u__childrens' => 0, //Child entities are just containers, no score on the link

            'tr_child_entity_id' => 1, //Transaction initiator
            'tr_miner_entity_id' => 1, //Transaction recipient

            'tr_parent_entity_id' => 13, //Action Plan Items
        );

        //Fetch child entities:
        $ens = array();

        //Recursively loops through child entities:
        $score = 0;
        foreach ($ens as $$en) {
            //Addup all child sores:
            $score += $this->e_score_recursive($$en);
        }

        //Anything to update?
        if (count($u) > 0) {

            //Update this row:
            $score += count($ens) * $score_weights['u__childrens'];

            $score += count($this->Database_model->fn___tr_fetch(array(
                    'tr_child_entity_id' => $u['en_id'],
                ), array(), 5000)) * $score_weights['tr_child_entity_id'];
            $score += count($this->Database_model->fn___tr_fetch(array(
                    'tr_miner_entity_id' => $u['en_id'],
                ), array(), 5000)) * $score_weights['tr_miner_entity_id'];
            $score += count($this->Database_model->w_fetch(array(
                    'tr_parent_entity_id' => $u['en_id'],
                ))) * $score_weights['tr_parent_entity_id'];

            //Update the score:
            $this->Database_model->fn___en_update($u['en_id'], array(
                'en_trust_score' => $score,
            ));

            //return the score:
            return $score;

        }
    }


    function fn___save_media_to_cdn()
    {

        /*
         *
         * Every time we receive a media file from Facebook
         * we need to upload it to our own CDNs using the
         * short-lived URL provided by Facebook so we can
         * access it indefinitely without restriction.
         * This process is managed by creating a @4299
         * Transaction Type which this cron job grabs and
         * uploads to Mench CDN
         *
         * */

        $max_per_batch = 20; //Max number of scans per run

        $tr_pending = $this->Database_model->fn___tr_fetch(array(
            'tr_status' => 0, //Pending
            'tr_type_entity_id' => 4299, //Save media file to Mench cloud
        ), array(), $max_per_batch);


        //Lock item so other Cron jobs don't pick this up:
        foreach ($tr_pending as $tr) {
            if ($tr['tr_id'] > 0 && $tr['tr_status'] == 0) {
                $this->Database_model->fn___tr_update($tr['tr_id'], array(
                    'tr_status' => 1, //Working on... (So other cron jobs do not pickup this item again)
                ));
            }
        }

        //Go through and upload to CDN:
        foreach ($tr_pending as $u) {

            $detected_tr_type = fn___detect_tr_type_entity_id($new_file_url);
            if(!$detected_tr_type['status']){
                //Opppsi, there was some error:
                //TODO Log error
                continue;
            }

            //Update transaction data:
            $this->Database_model->fn___tr_update($trp['tr_id'], array(
                'tr_content' => $new_file_url,
                'tr_type_entity_id' => $detected_tr_type['tr_type_entity_id'],
                'tr_status' => 2, //Publish
            ));


            //Save the file to S3
            $new_file_url = fn___upload_to_cdn($u['tr_content'], $u);

            if ($new_file_url) {

                //Success! Is this an image to be added as the entity icon?
                if (strlen($u['en_icon'])<1) {
                    //Update Cover ID:
                    $this->Database_model->fn___en_update($u['en_id'], array(
                        'en_icon' => '<img class="profile-icon" src="' . $new_file_url . '" />',
                    ), true);
                }

                //Update transaction:
                $this->Database_model->fn___tr_update($u['tr_id'], array(
                    'tr_status' => 2, //Publish
                ));

            } else {

                //Error has already been logged in the CDN function, so just update transaction:
                $this->Database_model->fn___tr_update($u['tr_id'], array(
                    'tr_status' => -1, //Removed
                ));

            }
        }

        fn___echo_json($tr_pending);
    }

    function fn___facebook_attachment_sync()
    {

        /*
         * This cron job looks for all requests to sync
         * Media files with Facebook so we can instantly
         * deliver them over Messenger.
         *
         * Cron Settings: * * * * *
         *
         */

        $max_per_batch = 20; //Max number of syncs per cron run
        $success_count = 0; //Track success
        $fb_convert_4537 = $this->config->item('fb_convert_4537'); //Supported Media Types
        $tr_metadata = array();


        //Let's fetch all Media files without a Facebook attachment ID:
        $pending_urls = $this->Database_model->fn___tr_fetch(array(
            'tr_type_entity_id IN (' . join(',',array_keys($fb_convert_4537)) . ')' => null,
            'tr_metadata' => null, //Missing Facebook Attachment ID
        ), array(), $max_per_batch, 0 , array('tr_id' => 'ASC')); //Sort by oldest added first

        foreach ($pending_urls as $tr) {

            $payload = array(
                'message' => array(
                    'attachment' => array(
                        'type' => $fb_convert_4537[$tr['tr_type_entity_id']],
                        'payload' => array(
                            'is_reusable' => true,
                            'url' => $tr['tr_content'], //The URL to the media file
                        ),
                    ),
                )
            );

            //Attempt to sync Media to Facebook:
            $result = $this->Chat_model->fn___facebook_graph('POST', '/me/message_attachments', $payload);
            $db_result = false;

            if ($result['status'] && isset($result['tr_metadata']['result']['attachment_id'])) {

                //Save Facebook Attachment ID to DB:
                $db_result = $this->Matrix_model->fn___metadata_update('tr', $tr['tr_id'], array(
                    'fb_att_id' => intval($result['tr_metadata']['result']['attachment_id']),
                ));

            }

            //Did it go well?
            if ($db_result) {

                $success_count++;

            } else {

                //Log error:
                $this->Database_model->fn___tr_create(array(
                    'tr_type_entity_id' => 4246, //Platform Error
                    'tr_content' => 'fn___facebook_attachment_sync() Failed to sync attachment using Facebook API',
                    'tr_metadata' => array(
                        'payload' => $payload,
                        'result' => $result,
                    ),
                ));

                //Also disable future attempts for this transaction:
                $db_result = $this->Matrix_model->fn___metadata_update('tr', $tr['tr_id'], array(
                    'fb_att_id_failed' => true,
                ));

            }

            //Save stats:
            array_push($tr_metadata, array(
                'payload' => $payload,
                'fb_result' => $result,
            ));

        }

        //Echo message:
        fn___echo_json(array(
            'status' => ($success_count == count($pending_urls) && $success_count > 0 ? 1 : 0),
            'message' => $success_count . '/' . count($pending_urls) . ' synced using Facebook Attachment API',
            'tr_metadata' => $tr_metadata,
        ));

    }


}