<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SOURCE_model extends CI_Model
{

    /*
     *
     * Player related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }





    function en_activate_session($en, $update_session = false){

        //PROFILE
        $session_data = array(
            'session_profile' => $en,
            'session_parent_ids' => array(),
            'session_superpowers_assigned' => array(),
            'session_superpowers_activated' => array(),
        );

        if(!$update_session){

            //Append stats variables:
            $session_data['session_page_count'] = 0;

            $this->LEDGER_model->ln_create(array(
                'ln_creator_source_id' => $en['en_id'],
                'ln_type_source_id' => 7564, //PLAYER SIGN
            ));

        }

        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_portfolio_source_id' => $en['en_id'], //This child source
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
        ), array('en_profile')) as $en_profile){

            //Push to parent IDs:
            array_push($session_data['session_parent_ids'], intval($en_profile['en_id']));

            if(in_array($en_profile['en_id'], $this->config->item('en_ids_10957'))){

                //It's assigned!
                array_push($session_data['session_superpowers_assigned'], intval($en_profile['en_id']));

                //Was the latest toggle to de-activate? If not, assume active:
                $last_advance_settings = $this->LEDGER_model->ln_fetch(array(
                    'ln_creator_source_id' => $en['en_id'],
                    'ln_type_source_id' => 5007, //TOGGLE SUPERPOWER
                    'ln_profile_source_id' => $en_profile['en_id'],
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                ), array(), 1); //Fetch the single most recent supoerpower toggle only
                if(!count($last_advance_settings) || !substr_count($last_advance_settings[0]['ln_content'] , ' DEACTIVATED')){
                    array_push($session_data['session_superpowers_activated'], intval($en_profile['en_id']));
                }

            }
        }

        //SESSION
        $this->session->set_userdata($session_data);

        return $en;

    }




    function en_create($insert_columns, $external_sync = false, $ln_creator_source_id = 0)
    {

        //What is required to create a new Idea?
        if (detect_missing_columns($insert_columns, array('en_status_source_id', 'en_name'), $ln_creator_source_id)) {
            return false;
        }

        //Transform text:
        $insert_columns['en_name'] = strtoupper($insert_columns['en_name']);

        if (isset($insert_columns['en_metadata'])) {
            $insert_columns['en_metadata'] = serialize($insert_columns['en_metadata']);
        } else {
            $insert_columns['en_metadata'] = null;
        }

        //Lets now add:
        $this->db->insert('mench_source', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['en_id'])) {
            $insert_columns['en_id'] = $this->db->insert_id();
        }

        if ($insert_columns['en_id'] > 0) {

            //Log link new source:
            $this->LEDGER_model->ln_create(array(
                'ln_creator_source_id' => ($ln_creator_source_id > 0 ? $ln_creator_source_id : $insert_columns['en_id']),
                'ln_portfolio_source_id' => $insert_columns['en_id'],
                'ln_type_source_id' => 4251, //New Source Created
                'ln_content' => $insert_columns['en_name'],
            ));

            //Fetch to return the complete source data:
            $ens = $this->SOURCE_model->en_fetch(array(
                'en_id' => $insert_columns['en_id'],
            ));

            if($external_sync){
                //Update Algolia:
                update_algolia('en', $insert_columns['en_id']);
            }

            return $ens[0];

        } else {

            //Ooopsi, something went wrong!
            $this->LEDGER_model->ln_create(array(
                'ln_profile_source_id' => $ln_creator_source_id,
                'ln_content' => 'en_create() failed to create a new source',
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_metadata' => $insert_columns,
            ));
            return false;

        }
    }

    function en_fetch($match_columns = array(), $limit = 0, $limit_offset = 0, $order_columns = array('en_name' => 'ASC'), $select = '*', $group_by = null)
    {

        //Fetch the target sources:
        $this->db->select($select);
        $this->db->from('mench_source');
        foreach($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        if ($group_by) {
            $this->db->group_by($group_by);
        }
        foreach($order_columns as $key => $value) {
            $this->db->order_by($key, $value);
        }
        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }

        $q = $this->db->get();
        return $q->result_array();
    }

    function en_update($id, $update_columns, $external_sync = false, $ln_creator_source_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current source filed values so we can compare later on after we've updated it:
        if($ln_creator_source_id > 0){
            $before_data = $this->SOURCE_model->en_fetch(array('en_id' => $id));
        }

        //Transform text:
        if(isset($update_columns['en_name'])){
            $update_columns['en_name'] = strtoupper($update_columns['en_name']);
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['en_metadata']) && is_array($update_columns['en_metadata'])){
            $update_columns['en_metadata'] = serialize($update_columns['en_metadata']);
        }

        //Update:
        $this->db->where('en_id', $id);
        $this->db->update('mench_source', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $ln_creator_source_id > 0) {

            if($external_sync){
                //Sync algolia:
                update_algolia('en', $id);
            }

            //Log modification link for every field changed:
            foreach($update_columns as $key => $value) {

                if ($before_data[0][$key] == $value){
                    //Nothing changed:
                    continue;
                }

                //FYI: Unlike Ideas, we cannot log parent/child source relations since the child source slot is previously taken...

                if($key=='en_name') {

                    $ln_type_source_id = 10646; //Player Updated Name
                    $ln_content = update_description($before_data[0][$key], $value);

                } elseif($key=='en_status_source_id') {

                    if(in_array($value, $this->config->item('en_ids_7358') /* ACTIVE */)){
                        $ln_type_source_id = 10654; //Source Updated Status
                    } else {
                        $ln_type_source_id = 6178; //Source Deleted
                    }
                    $en_all_6177 = $this->config->item('en_all_6177'); //Source Status
                    $ln_content = echo_db_field($key) . ' updated from [' . $en_all_6177[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_6177[$value]['m_name'] . ']';

                } elseif($key=='en_icon') {

                    $ln_type_source_id = 10653; //Player Updated Icon
                    $ln_content = echo_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }

                //Value has changed, log link:
                $this->LEDGER_model->ln_create(array(
                    'ln_creator_source_id' => ($ln_creator_source_id > 0 ? $ln_creator_source_id : $id),
                    'ln_type_source_id' => $ln_type_source_id,
                    'ln_portfolio_source_id' => $id,
                    'ln_content' => $ln_content,
                    'ln_metadata' => array(
                        'en_id' => $id,
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ),
                ));

            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->LEDGER_model->ln_create(array(
                'ln_portfolio_source_id' => $id,
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_content' => 'en_update() Failed to update',
                'ln_metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }


    function en_radio_set($en_profile_bucket_id, $set_en_child_id, $ln_creator_source_id)
    {

        /*
         * Treats an source child group as a drop down menu where:
         *
         *  $en_profile_bucket_id is the parent of the drop down
         *  $ln_creator_source_id is the user source ID that one of the children of $en_profile_bucket_id should be assigned (like a drop down)
         *  $set_en_child_id is the new value to be assigned, which could also be null (meaning just delete all current values)
         *
         * This function is helpful to manage things like User communication levels
         *
         * */


        //Fetch all the child sources for $en_profile_bucket_id and make sure they match $set_en_child_id
        $children = $this->config->item('en_ids_' . $en_profile_bucket_id);
        if ($en_profile_bucket_id < 1) {
            return false;
        } elseif (!$children) {
            return false;
        } elseif ($set_en_child_id > 0 && !in_array($set_en_child_id, $children)) {
            return false;
        }

        //First delete existing parent/child links for this drop down:
        $previously_assigned = ($set_en_child_id < 1);
        $updated_ln_id = 0;
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_portfolio_source_id' => $ln_creator_source_id,
            'ln_profile_source_id IN (' . join(',', $children) . ')' => null, //Current children
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
        ), array(), config_var(11064)) as $ln) {

            if (!$previously_assigned && $ln['ln_profile_source_id'] == $set_en_child_id) {
                $previously_assigned = true;
            } else {
                //Delete assignment:
                $updated_ln_id = $ln['ln_id'];

                //Do not log update link here as we would log it further below:
                $this->LEDGER_model->ln_update($ln['ln_id'], array(
                    'ln_status_source_id' => 6173, //Link Deleted
                ), $ln_creator_source_id, 6224 /* User Account Updated */);
            }

        }


        //Make sure $set_en_child_id belongs to parent if set (Could be null which means delete all)
        if (!$previously_assigned) {
            //Let's go ahead and add desired source as parent:
            $this->LEDGER_model->ln_create(array(
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_portfolio_source_id' => $ln_creator_source_id,
                'ln_profile_source_id' => $set_en_child_id,
                'ln_type_source_id' => en_link_type_id(),
                'ln_parent_transaction_id' => $updated_ln_id,
            ));
        }

    }

    function en_unlink($en_id, $ln_creator_source_id = 0, $merger_en_id = 0){

        //Fetch all SOURCE LINKS:
        $adjusted_count = 0;
        foreach(array_merge(
                //Player references within IDEA NOTES:
                    $this->LEDGER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //IDEA NOTES
                        'ln_profile_source_id' => $en_id,
                    ), array('in_next'), 0, 0, array('ln_order' => 'ASC')),
                    //Player links:
                    $this->LEDGER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
                        '(ln_portfolio_source_id = ' . $en_id . ' OR ln_profile_source_id = ' . $en_id . ')' => null,
                    ), array(), 0)
                ) as $adjust_tr){

            //Merge only if merger ID provided and link not related to original link:
            if($merger_en_id > 0 && $adjust_tr['ln_profile_source_id']!=$merger_en_id && $adjust_tr['ln_portfolio_source_id']!=$merger_en_id){

                //Update core field:
                $target_field = ($adjust_tr['ln_portfolio_source_id'] == $en_id ? 'ln_portfolio_source_id' : 'ln_profile_source_id');
                $updating_fields = array(
                    $target_field => $merger_en_id,
                );

                //Also update possible source references within IDEA NOTES content:
                if(substr_count($adjust_tr['ln_content'], '@'.$adjust_tr[$target_field]) == 1){
                    $updating_fields['ln_content'] = str_replace('@'.$adjust_tr[$target_field],'@'.$merger_en_id, $adjust_tr['ln_content']);
                }

                //Update Link:
                $adjusted_count += $this->LEDGER_model->ln_update($adjust_tr['ln_id'], $updating_fields, $ln_creator_source_id, 10689 /* Player Link Merged */);

            } else {

                //Delete this link:
                $adjusted_count += $this->LEDGER_model->ln_update($adjust_tr['ln_id'], array(
                    'ln_status_source_id' => 6173, //Link Deleted
                ), $ln_creator_source_id, 10673 /* Player Link Unpublished */);

            }
        }

        return $adjusted_count;
    }

    function en_assign_session_player($en_id){

        $session_en = superpower_assigned();
        if(!$session_en){
            return false;
        }

        //Assign to Creator:
        $this->LEDGER_model->ln_create(array(
            'ln_type_source_id' => en_link_type_id(),
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_profile_source_id' => $session_en['en_id'],
            'ln_portfolio_source_id' => $en_id,
        ));

        //Review source later:
        if(!superpower_assigned(10967)){

            //Add Pending Review:
            $this->LEDGER_model->ln_create(array(
                'ln_type_source_id' => en_link_type_id(),
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_profile_source_id' => 12775, //PENDING REVIEW
                'ln_portfolio_source_id' => $en_id,
            ));

            //SOURCE PENDING MODERATION TYPE:
            $this->LEDGER_model->ln_create(array(
                'ln_type_source_id' => 7504, //SOURCE PENDING MODERATION
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_profile_source_id' => 12775, //PENDING REVIEW
                'ln_portfolio_source_id' => $en_id,
            ));

        }

    }

    function en_domain($url, $ln_creator_source_id = 0, $page_title = null)
    {
        /*
         *
         * Either finds/returns existing domains or adds it
         * to the Domains source if $ln_creator_source_id > 0
         *
         * */

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        }


        //Analyze domain:
        $domain_analysis = analyze_domain($url);
        $domain_previously_existed = 0; //Assume false
        $en_domain = false; //Have an empty placeholder:


        //Check to see if we have domain linked previously:
        $domain_links = $this->LEDGER_model->ln_fetch(array(
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'ln_type_source_id' => 4256, //Generic URL (Domain home pages should always be generic, see above for logic)
            'ln_profile_source_id' => 1326, //Domain Player
            'ln_content' => $domain_analysis['url_clean_domain'],
        ), array('en_portfolio'));


        //Do we need to create an source for this domain?
        if (count($domain_links) > 0) {

            $domain_previously_existed = 1;
            $en_domain = $domain_links[0];

        } elseif ($ln_creator_source_id) {

            //Yes, let's add a new source:
            $added_en = $this->SOURCE_model->en_verify_create(( $page_title ? $page_title : $domain_analysis['url_domain_name'] ), $ln_creator_source_id, 6181, detect_fav_icon($domain_analysis['url_clean_domain']));
            $en_domain = $added_en['en'];

            //And link source to the domains source:
            $this->LEDGER_model->ln_create(array(
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_type_source_id' => 4256, //Generic URL (Domains are always generic)
                'ln_profile_source_id' => 1326, //Domain Player
                'ln_portfolio_source_id' => $en_domain['en_id'],
                'ln_content' => $domain_analysis['url_clean_domain'],
            ));

        }


        //Return data:
        return array_merge( $domain_analysis , array(
            'status' => 1,
            'message' => 'Success',
            'domain_previously_existed' => $domain_previously_existed,
            'en_domain' => $en_domain,
        ));

    }

    function en_match_ln_status($ln_creator_source_id, $query= array()){

        //STATS
        $stats = array(
            'ln_type_source_id' => 4251, //Play Created
            'scanned' => 0,
            'missing_creation_fix' => 0,
            'status_sync' => 0,
        );

        //SOURCE
        $status_converter = array(
            12563 => 12399, //SOURCE FEATURED => TRANSACTION FEATURED
            6181 => 6176, //SOURCE PUBLISH => TRANSACTION PUBLISH
            6180 => 6175, //SOURCE DRAFT => TRANSACTION DRAFT
            6178 => 6173, //SOURCE DELETE => TRANSACTION DELETE
        );
        foreach($this->SOURCE_model->en_fetch($query) as $en){

            $stats['scanned']++;

            //Find creation read:
            $reads = $this->LEDGER_model->ln_fetch(array(
                'ln_type_source_id' => $stats['ln_type_source_id'],
                'ln_portfolio_source_id' => $en['en_id'],
            ));

            if(!count($reads)){

                $stats['missing_creation_fix']++;

                $this->LEDGER_model->ln_create(array(
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_portfolio_source_id' => $en['en_id'],
                    'ln_content' => $en['en_name'],
                    'ln_type_source_id' => $stats['ln_type_source_id'],
                    'ln_status_source_id' => $status_converter[$en['en_status_source_id']],
                ));

            } elseif($reads[0]['ln_status_source_id'] != $status_converter[$en['en_status_source_id']]){

                $stats['status_sync']++;
                $this->LEDGER_model->ln_update($reads[0]['ln_id'], array(
                    'ln_status_source_id' => $status_converter[$en['en_status_source_id']],
                ));

            }

        }

        return $stats;
    }




    function en_metadat_experts($en, $level = 1){

        //Goes through $max_search_levels of sources to find expert channels, people & organizations
        $max_search_levels = 3;
        $metadata_this = array(
            '__in__metadata_experts' => array(),
            '__in__metadata_content' => array(),
        );

        //SOURCE PROFILE
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_portfolio_source_id' => $en['en_id'],
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')).')' => null, //SOURCE LINKS
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
        ), array('en_profile'), 0) as $en__profile){

            if(in_array($en__profile['en_id'], $this->config->item('en_ids_3000'))){
                //CONTENT CHANNELS
                $en['ln_content'] = $en__profile['ln_content']; //Update Description
                if (!isset($metadata_this['__in__metadata_content'][$en['en_id']])) {
                    $metadata_this['__in__metadata_content'][$en['en_id']] = $en;
                }
            } elseif(in_array($en__profile['en_id'], $this->config->item('en_ids_12864'))) {
                //EXPERT PEOPLE/ORGANIZATIONS
                $en['ln_content'] = $en__profile['ln_content']; //Update Description
                if (!isset($metadata_this['__in__metadata_experts'][$en['en_id']])) {
                    $metadata_this['__in__metadata_experts'][$en['en_id']] = $en;
                }
            }

            //Go another level?
            if($level < $max_search_levels){

                $metadata_recursion = $this->SOURCE_model->en_metadat_experts($en__profile, ($level + 1));

                //CONTENT CHANNELS
                foreach($metadata_recursion['__in__metadata_content'] as $en_id => $source_en) {
                    if (!isset($metadata_this['__in__metadata_content'][$en_id])) {
                        $metadata_this['__in__metadata_content'][$en_id] = $source_en;
                    }
                }

                //EXPERT PEOPLE/ORGANIZATIONS
                foreach($metadata_recursion['__in__metadata_experts'] as $en_id => $expert_en) {
                    if (!isset($metadata_this['__in__metadata_experts'][$en_id])) {
                        $metadata_this['__in__metadata_experts'][$en_id] = $expert_en;
                    }
                }
            }
        }

        return $metadata_this;
    }



    function en_url($url, $ln_creator_source_id = 0, $add_to_child_en_id = 0, $page_title = null)
    {

        /*
         *
         * Analyzes a URL to see if it and its domain exists.
         * Input legend:
         *
         * - $url:                  Input URL
         * - $ln_creator_source_id:       IF > 0 will save URL (if not previously there) and give credit to this source as the player
         * - $add_to_child_en_id:   IF > 0 Will also add URL to this child if present
         * - $page_title:           If set it would override the source title that is auto generated (Used in Add Source Wizard to enable players to edit auto generated title)
         *
         * */


        //Validate URL:
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        } elseif ($add_to_child_en_id > 0 && $ln_creator_source_id < 1) {
            return array(
                'status' => 0,
                'message' => 'Parent source is required to add a parent URL',
            );
        }

        //Remember if source name was passed:
        $name_was_passed = ( $page_title ? true : false );
        $en_all_4537 = $this->config->item('en_all_4537');
        $en_all_4592 = $this->config->item('en_all_4592');

        //Initially assume Generic URL unless we can prove otherwise:
        $ln_type_source_id = 4256; //Generic URL

        //We'll check to see if URL previously existed:
        $url_previously_existed = 0;

        //Start with null and see if we can find/add:
        $en_url = null;

        //Analyze domain:
        $domain_analysis = analyze_domain($url);

        //Now let's analyze further based on type:
        if ($domain_analysis['url_is_root']) {

            //Update URL to keep synced:
            $url = $domain_analysis['url_clean_domain'];

        } else {

            /*
             * URL Can only be non-generic if it's not the domain URL...
             *
             * Examples:
             *
             * Embed URL:      https://www.youtube.com/watch?v=-dVwv4wPA88
             * Audio URL:      https://s3foundation.s3-us-west-2.amazonaws.com/672b41ff20fece4b3e7ae2cf4b58389f.mp3
             * Video URL:      https://s3foundation.s3-us-west-2.amazonaws.com/8c5a1cc4e8558f422a4003d126502db9.mp4
             * Image URL:      https://s3foundation.s3-us-west-2.amazonaws.com/d673c17d7164817025a000416da3be3f.png
             * File URL:       https://s3foundation.s3-us-west-2.amazonaws.com/611695da5d0d199e2d95dd2eabe484cf.zip
             *
             * */

            //Is this an embed URL?
            $embed_code = echo_url_embed($url, null, true);

            if ($embed_code['status']) {

                //URL Was detected as an embed URL:
                $ln_type_source_id = 4257;
                $url = $embed_code['clean_url'];

            } elseif ($domain_analysis['url_file_extension'] && is_https_url($url)) {

                $detected_extension = false;
                foreach($this->config->item('en_all_11080') as $en_id => $m){
                    if(in_array($domain_analysis['url_file_extension'], explode('|' , $m['m_desc']))){
                        $ln_type_source_id = $en_id;
                        $detected_extension = true;
                        break;
                    }
                }

                if(!$detected_extension){
                    //Log error to notify admin:
                    $this->LEDGER_model->ln_create(array(
                        'ln_content' => 'en_url() detected unknown file extension ['.$domain_analysis['url_file_extension'].'] that needs to be added to @11080',
                        'ln_type_source_id' => 4246, //Platform Bug Reports
                        'ln_profile_source_id' => 11080,
                        'ln_metadata' => $domain_analysis,
                    ));
                }
            }
        }



        //Update Name:
        if (!$name_was_passed) {

            //Only fetch URL content in certain situations:
            $url_content = ( in_array($ln_type_source_id, $this->config->item('en_ids_11059')) /* not a direct file type */ ? null : @file_get_contents($url) );
            $page_title = en_name_validate(( $url_content ? one_two_explode('>', '', one_two_explode('<title', '</title', $url_content)) : $page_title ), $ln_type_source_id);

        }


        //Fetch/Create domain source:
        $domain_source = $this->SOURCE_model->en_domain($url, $ln_creator_source_id, ( $domain_analysis['url_is_root'] && $name_was_passed ? $page_title : null ));
        if(!$domain_source['status']){
            //We had an issue:
            return $domain_source;
        }


        //Was this not a root domain? If so, also check to see if URL exists:
        if ($domain_analysis['url_is_root']) {

            //URL is the domain in this case:
            $en_url = $domain_source['en_domain'];

            //IF the URL exists since the domain existed and the URL is the domain!
            if ($domain_source['domain_previously_existed']) {
                $url_previously_existed = 1;
            }

        } else {

            //Check to see if URL previously exists:
            $url_links = $this->LEDGER_model->ln_fetch(array(
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Player URL Links
                'ln_content' => $url,
            ), array('en_portfolio'));


            //Do we need to create an source for this URL?
            if (count($url_links) > 0) {

                //Nope, source previously exists:
                $en_url = $url_links[0];
                $url_previously_existed = 1;

            } elseif($ln_creator_source_id) {

                if(!$page_title){
                    //Assign a generic source name:
                    $page_title = $en_all_4592[$ln_type_source_id]['m_name'].' '.substr(md5($url), 0, 8);
                }

                //Prefix type in name:
                $page_title = $page_title;

                //Create a new source for this URL ONLY If player source is provided...
                $added_en = $this->SOURCE_model->en_verify_create($page_title, $ln_creator_source_id, 6181, $en_all_4592[$ln_type_source_id]['m_icon']);
                if($added_en['status']){

                    //All good:
                    $en_url = $added_en['en'];

                    //Always link URL to its parent domain:
                    $this->LEDGER_model->ln_create(array(
                        'ln_creator_source_id' => $ln_creator_source_id,
                        'ln_type_source_id' => $ln_type_source_id,
                        'ln_profile_source_id' => $domain_source['en_domain']['en_id'],
                        'ln_portfolio_source_id' => $en_url['en_id'],
                        'ln_content' => $url,
                    ));

                    //Assign to Player:
                    $this->SOURCE_model->en_assign_session_player($en_url['en_id']);

                    //Update Search Index:
                    update_algolia('en', $en_url['en_id']);

                } else {

                    //Log error:
                    $this->LEDGER_model->ln_create(array(
                        'ln_content' => 'en_url['.$url.'] FAILED to en_verify_create['.$page_title.'] with message: '.$added_en['message'],
                        'ln_type_source_id' => 4246, //Platform Bug Reports
                        'ln_creator_source_id' => $ln_creator_source_id,
                        'ln_profile_source_id' => $domain_source['en_domain']['en_id'],
                        'ln_metadata' => array(
                            'url' => $url,
                            'ln_creator_source_id' => $ln_creator_source_id,
                            'add_to_child_en_id' => $add_to_child_en_id,
                            'page_title' => $page_title,
                        ),
                    ));

                }

            } else {
                //URL not found and no player source provided to create the URL:
                $en_url = array();
            }
        }


        //Have we been asked to also add URL to another parent or child?
        if(!$url_previously_existed && $add_to_child_en_id){
            //Link URL to its parent domain?
            $this->LEDGER_model->ln_create(array(
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_type_source_id' => en_link_type_id(),
                'ln_profile_source_id' => $en_url['en_id'],
                'ln_portfolio_source_id' => $add_to_child_en_id,
            ));
        }


        //Return results:
        return array_merge(

            $domain_analysis, //Make domain analysis data available as well...

            array(
                'status' => ($url_previously_existed && !$ln_creator_source_id ? 0 : 1),
                'message' => ($url_previously_existed && !$ln_creator_source_id ? 'URL already belongs to [' . $en_url['en_name'].'] with source ID @' . $en_url['en_id'] : 'Success'),
                'url_previously_existed' => $url_previously_existed,
                'clean_url' => $url,
                'ln_type_source_id' => $ln_type_source_id,
                'page_title' => html_entity_decode($page_title, ENT_QUOTES),
                'en_domain' => $domain_source['en_domain'],
                'en_url' => $en_url,
            )
        );
    }

    function en_mass_update($en_id, $action_en_id, $action_command1, $action_command2, $ln_creator_source_id)
    {

        //Alert: Has a twin function called in_mass_update()

        boost_power();

        $is_valid_icon = is_valid_icon($action_command1);

        if(!in_array($action_en_id, $this->config->item('en_ids_4997'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif($action_en_id != 5943 && $action_en_id != 12318 && strlen(trim($action_command1)) < 1){

            return array(
                'status' => 0,
                'message' => 'Missing primary command',
            );

        } elseif(in_array($action_en_id, array(5943,12318)) && !$is_valid_icon['status']){

            return array(
                'status' => 0,
                'message' => $is_valid_icon['message'],
            );

        } elseif(in_array($action_en_id, array(5981, 5982, 12928, 12930, 11956)) && !is_valid_en_string($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @123 Source Name',
            );

        } elseif($action_en_id==11956 && !is_valid_en_string($action_command2)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @123 Source Name',
            );

        }





        //Basic input validation done, let's continue...


        //Fetch all children:
        $applied_success = 0; //To be populated...
        $children = $this->LEDGER_model->ln_fetch(array(
            'ln_profile_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
        ), array('en_portfolio'), 0);


        //Process request:
        foreach($children as $en) {

            //Logic here must match items in en_mass_actions config variable

            //Take command-specific action:
            if ($action_en_id == 4998) { //Add Prefix String

                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_name' => $action_command1 . $en['en_name'],
                ), true, $ln_creator_source_id);

                $applied_success++;

            } elseif ($action_en_id == 4999) { //Add Postfix String

                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_name' => $en['en_name'] . $action_command1,
                ), true, $ln_creator_source_id);

                $applied_success++;

            } elseif (in_array($action_en_id, array(5981, 5982, 12928, 12930, 11956))) { //Add/Delete parent source

                //What player searched for:
                $parent_en_id = intval(one_two_explode('@',' ',$action_command1));

                //See if child source has searched parent source:
                $child_parent_ens = $this->LEDGER_model->ln_fetch(array(
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
                    'ln_portfolio_source_id' => $en['en_id'], //This child source
                    'ln_profile_source_id' => $parent_en_id,
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                ));

                if(($action_en_id==5981 && count($child_parent_ens)==0) || ($action_en_id==12928 && echo_coins_count_source(0,$en['en_id'],true) > 0) || ($action_en_id==12930 && !echo_coins_count_source(0,$en['en_id'],true))){

                    //Parent Player Addition
                    $this->LEDGER_model->ln_create(array(
                        'ln_creator_source_id' => $ln_creator_source_id,
                        'ln_type_source_id' => en_link_type_id(),
                        'ln_portfolio_source_id' => $en['en_id'], //This child source
                        'ln_profile_source_id' => $parent_en_id,
                    ));

                    $applied_success++;

                } elseif(in_array($action_en_id, array(5982, 11956)) && count($child_parent_ens) > 0){

                    if($action_en_id==5982){

                        //Parent Player Removal
                        foreach($child_parent_ens as $delete_tr){

                            $this->LEDGER_model->ln_update($delete_tr['ln_id'], array(
                                'ln_status_source_id' => 6173, //Link Deleted
                            ), $ln_creator_source_id, 10673 /* Player Link Unpublished  */);

                            $applied_success++;
                        }

                    } elseif($action_en_id==11956) {

                        $parent_new_en_id = intval(one_two_explode('@',' ',$action_command2));

                        //Add as a parent because it meets the condition
                        $this->LEDGER_model->ln_create(array(
                            'ln_creator_source_id' => $ln_creator_source_id,
                            'ln_type_source_id' => en_link_type_id(),
                            'ln_portfolio_source_id' => $en['en_id'], //This child source
                            'ln_profile_source_id' => $parent_new_en_id,
                        ));

                        $applied_success++;

                    }

                }

            } elseif ($action_en_id == 5943) { //Player Mass Update Player Icon

                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_icon' => $action_command1,
                ), true, $ln_creator_source_id);

                $applied_success++;

            } elseif ($action_en_id == 12318 && !strlen($en['en_icon'])) { //Player Mass Update Player Icon

                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_icon' => $action_command1,
                ), true, $ln_creator_source_id);

                $applied_success++;

            } elseif ($action_en_id == 5000 && substr_count($en['en_name'], strtoupper($action_command1)) > 0) { //Replace Player Matching Name

                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_name' => str_replace(strtoupper($action_command1), strtoupper($action_command2), $en['en_name']),
                ), true, $ln_creator_source_id);

                $applied_success++;

            } elseif ($action_en_id == 10625 && substr_count($en['en_icon'], $action_command1) > 0) { //Replace Player Matching Icon

                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_icon' => str_replace($action_command1, $action_command2, $en['en_icon']),
                ), true, $ln_creator_source_id);

                $applied_success++;

            } elseif ($action_en_id == 5001 && substr_count($en['ln_content'], $action_command1) > 0) { //Replace Link Matching String

                $this->LEDGER_model->ln_update($en['ln_id'], array(
                    'ln_content' => str_replace($action_command1, $action_command2, $en['ln_content']),
                ), $ln_creator_source_id, 10657 /* Player Link Updated Content  */);

                $applied_success++;

            } elseif ($action_en_id == 5003 && ($action_command1=='*' || $en['en_status_source_id']==$action_command1) && in_array($action_command2, $this->config->item('en_ids_6177'))) {

                //Being deleted? Unlink as well if that's the case:
                if(!in_array($action_command2, $this->config->item('en_ids_7358'))){
                    $this->SOURCE_model->en_unlink($en['en_id'], $ln_creator_source_id);
                }

                //Update Matching Player Status:
                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_status_source_id' => $action_command2,
                ), true, $ln_creator_source_id);

                $applied_success++;

            } elseif ($action_en_id == 5865 && ($action_command1=='*' || $en['ln_status_source_id']==$action_command1) && in_array($action_command2, $this->config->item('en_ids_6186') /* Transaction Status */)) { //Update Matching Transaction Status

                $this->LEDGER_model->ln_update($en['ln_id'], array(
                    'ln_status_source_id' => $action_command2,
                ), $ln_creator_source_id, ( in_array($action_command2, $this->config->item('en_ids_7360') /* ACTIVE */) ? 10656 /* Player Link Updated Status */ : 10673 /* Player Link Unpublished */ ));

                $applied_success++;

            }
        }


        //Log mass source edit link:
        $this->LEDGER_model->ln_create(array(
            'ln_creator_source_id' => $ln_creator_source_id,
            'ln_type_source_id' => $action_en_id,
            'ln_portfolio_source_id' => $en_id,
            'ln_metadata' => array(
                'payload' => $_POST,
                'sources_total' => count($children),
                'sources_updated' => $applied_success,
                'command1' => $action_command1,
                'command2' => $action_command2,
            ),
        ));

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . '/' . count($children) . ' sources updated',
        );

    }

    function en_child_count($en_id, $en_statuses)
    {

        //Count the active children of source:
        $en__portfolios_count = 0;

        //Do a child count:
        $en__portfolios_count = $this->LEDGER_model->ln_fetch(array(
            'ln_profile_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'en_status_source_id IN (' . join(',', $en_statuses) . ')' => null,
        ), array('en_portfolio'), 0, 0, array(), 'COUNT(en_id) as totals');

        if (count($en__portfolios_count) > 0) {
            $en__portfolios_count = intval($en__portfolios_count[0]['totals']);
        }

        return $en__portfolios_count;
    }


    function en_verify_create($en_name, $ln_creator_source_id = 0, $en_status_source_id = 6181 /* SOURCE PUBLISHED */, $en_icon = null){

        //If PSID exists, make sure it's not a duplicate:
        if(!in_array($en_status_source_id, $this->config->item('en_ids_6177'))){
            //Invalid Status ID
            return array(
                'status' => 0,
                'message' => 'Invalid Source Status',
            );
        }

        //Not found, so we need to create, and need a name by now:
        if(strlen($en_name)<2){
            return array(
                'status' => 0,
                'message' => 'Source name must be at-least 2 characters long',
            );
        }


        //Check to make sure name is not duplicate:
        $duplicate_ens = $this->SOURCE_model->en_fetch(array(
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
            'LOWER(en_name)' => strtolower(trim($en_name)),
        ));


        //Create source
        $focus_en = $this->SOURCE_model->en_create(array(
            'en_name' => trim($en_name),
            'en_icon' => $en_icon,
            'en_status_source_id' => $en_status_source_id,
        ), true, $ln_creator_source_id);


        //Return success:
        return array(
            'status' => 1,
            'en' => $focus_en,
        );

    }

}