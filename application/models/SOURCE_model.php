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





    function en_activate_session($en, $update_session = false, $session_6196_sign = 0){

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
            $session_data['session_6196_sign'] = $session_6196_sign;

            //LOG
            $this->READ_model->ln_create(array(
                'ln_creator_source_id' => $en['en_id'],
                'ln_type_source_id' => 7564, //PLAYER Signin on Website Success
            ));
        }

        foreach($this->READ_model->ln_fetch(array(
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
            'ln_child_source_id' => $en['en_id'], //This child source
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
        ), array('en_parent')) as $en_parent){

            //Push to parent IDs:
            array_push($session_data['session_parent_ids'], intval($en_parent['en_id']));

            if(in_array($en_parent['en_id'], $this->config->item('en_ids_10957'))){

                //It's assigned!
                array_push($session_data['session_superpowers_assigned'], intval($en_parent['en_id']));

                //Was the latest toggle to de-activate? If not, assume active:
                $last_advance_settings = $this->READ_model->ln_fetch(array(
                    'ln_creator_source_id' => $en['en_id'],
                    'ln_type_source_id' => 5007, //TOGGLE SUPERPOWER
                    'ln_parent_source_id' => $en_parent['en_id'],
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                ), array(), 1); //Fetch the single most recent supoerpower toggle only
                if(!count($last_advance_settings) || !substr_count($last_advance_settings[0]['ln_content'] , ' DEACTIVATED')){
                    array_push($session_data['session_superpowers_activated'], intval($en_parent['en_id']));
                }

            }
        }

        //SESSION
        $this->session->set_userdata($session_data);

        return $en;

    }




    function en_create($insert_columns, $external_sync = false, $ln_creator_source_id = 0)
    {

        //What is required to create a new Note?
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
        $this->db->insert('mench_sources', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['en_id'])) {
            $insert_columns['en_id'] = $this->db->insert_id();
        }

        if ($insert_columns['en_id'] > 0) {

            if($external_sync){
                //Update Algolia:
                $algolia_sync = update_algolia('en', $insert_columns['en_id']);
            }

            //Log link new source:
            $this->READ_model->ln_create(array(
                'ln_creator_source_id' => ($ln_creator_source_id > 0 ? $ln_creator_source_id : $insert_columns['en_id']),
                'ln_child_source_id' => $insert_columns['en_id'],
                'ln_type_source_id' => 4251, //New Source Created
                'ln_content' => $insert_columns['en_name'],
            ));

            //Fetch to return the complete source data:
            $ens = $this->SOURCE_model->en_fetch(array(
                'en_id' => $insert_columns['en_id'],
            ));

            return $ens[0];

        } else {

            //Ooopsi, something went wrong!
            $this->READ_model->ln_create(array(
                'ln_parent_source_id' => $ln_creator_source_id,
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
        $this->db->from('mench_sources');
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        if ($group_by) {
            $this->db->group_by($group_by);
        }
        foreach ($order_columns as $key => $value) {
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
        $this->db->update('mench_sources', $update_columns);
        $affected_rows = $this->db->affected_rows();

        if($affected_rows > 0 && $external_sync){
            //Sync algolia:
            $algolia_sync = update_algolia('en', $id);
        }

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $ln_creator_source_id > 0) {

            //Log modification link for every field changed:
            foreach ($update_columns as $key => $value) {

                if ($before_data[0][$key] == $value){
                    //Nothing changed:
                    continue;
                }

                //FYI: Unlike Notes, we cannot log parent/child source relations since the child source slot is already taken...

                if($key=='en_name') {

                    $ln_type_source_id = 10646; //Player Iterated Name
                    $ln_content = update_description($before_data[0][$key], $value);

                } elseif($key=='en_status_source_id') {

                    if(in_array($value, $this->config->item('en_ids_7358') /* Source Status Active */)){
                        $ln_type_source_id = 10654; //Source Iterated Status
                    } else {
                        $ln_type_source_id = 6178; //Source Archived
                    }
                    $en_all_6177 = $this->config->item('en_all_6177'); //Source Status
                    $ln_content = echo_clean_db_name($key) . ' iterated from [' . $en_all_6177[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_6177[$value]['m_name'] . ']';

                } elseif($key=='en_icon') {

                    $ln_type_source_id = 10653; //Player Iterated Icon
                    $ln_content = echo_clean_db_name($key) . ' iterated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }

                //Value has changed, log link:
                $this->READ_model->ln_create(array(
                    'ln_creator_source_id' => ($ln_creator_source_id > 0 ? $ln_creator_source_id : $id),
                    'ln_type_source_id' => $ln_type_source_id,
                    'ln_child_source_id' => $id,
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
            $this->READ_model->ln_create(array(
                'ln_child_source_id' => $id,
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


    function en_radio_set($en_parent_bucket_id, $set_en_child_id, $ln_creator_source_id)
    {

        /*
         * Treats an source child group as a drop down menu where:
         *
         *  $en_parent_bucket_id is the parent of the drop down
         *  $ln_creator_source_id is the user source ID that one of the children of $en_parent_bucket_id should be assigned (like a drop down)
         *  $set_en_child_id is the new value to be assigned, which could also be null (meaning just remove all current values)
         *
         * This function is helpful to manage things like User communication levels
         *
         * */


        //Fetch all the child sources for $en_parent_bucket_id and make sure they match $set_en_child_id
        $children = $this->config->item('en_ids_' . $en_parent_bucket_id);
        if ($en_parent_bucket_id < 1) {
            return false;
        } elseif (!$children) {
            return false;
        } elseif ($set_en_child_id > 0 && !in_array($set_en_child_id, $children)) {
            return false;
        }

        //First remove existing parent/child links for this drop down:
        $already_assigned = ($set_en_child_id < 1);
        $updated_ln_id = 0;
        foreach ($this->READ_model->ln_fetch(array(
            'ln_child_source_id' => $ln_creator_source_id,
            'ln_parent_source_id IN (' . join(',', $children) . ')' => null, //Current children
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
        ), array(), config_var(11064)) as $ln) {

            if (!$already_assigned && $ln['ln_parent_source_id'] == $set_en_child_id) {
                $already_assigned = true;
            } else {
                //Remove assignment:
                $updated_ln_id = $ln['ln_id'];

                //Do not log update link here as we would log it further below:
                $this->READ_model->ln_update($ln['ln_id'], array(
                    'ln_status_source_id' => 6173, //Link Removed
                ), $ln_creator_source_id, 6224 /* User Account Updated */);
            }

        }


        //Make sure $set_en_child_id belongs to parent if set (Could be null which means remove all)
        if (!$already_assigned) {
            //Let's go ahead and add desired source as parent:
            $this->READ_model->ln_create(array(
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_child_source_id' => $ln_creator_source_id,
                'ln_parent_source_id' => $set_en_child_id,
                'ln_type_source_id' => 4230, //Raw link
                'ln_parent_transaction_id' => $updated_ln_id,
            ));
        }

    }

    function en_unlink($en_id, $ln_creator_source_id = 0, $merger_en_id = 0){

        //Fetch all source links:
        $adjusted_count = 0;
        foreach(array_merge(
                //Player references within Note Pads:
                    $this->READ_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Note Status Active
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Note Pads
                        'ln_parent_source_id' => $en_id,
                    ), array('in_child'), 0, 0, array('ln_order' => 'ASC')),
                    //Player links:
                    $this->READ_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                        '(ln_child_source_id = ' . $en_id . ' OR ln_parent_source_id = ' . $en_id . ')' => null,
                    ), array(), 0)
                ) as $adjust_tr){

            //Merge only if merger ID provided and link not related to original link:
            if($merger_en_id > 0 && $adjust_tr['ln_parent_source_id']!=$merger_en_id && $adjust_tr['ln_child_source_id']!=$merger_en_id){

                //Update core field:
                $target_field = ($adjust_tr['ln_child_source_id'] == $en_id ? 'ln_child_source_id' : 'ln_parent_source_id');
                $updating_fields = array(
                    $target_field => $merger_en_id,
                );

                //Also update possible source references within Note Pads content:
                if(substr_count($adjust_tr['ln_content'], '@'.$adjust_tr[$target_field]) == 1){
                    $updating_fields['ln_content'] = str_replace('@'.$adjust_tr[$target_field],'@'.$merger_en_id, $adjust_tr['ln_content']);
                }

                //Update Link:
                $adjusted_count += $this->READ_model->ln_update($adjust_tr['ln_id'], $updating_fields, $ln_creator_source_id, 10689 /* Player Link Merged */);

            } else {

                //Remove this link:
                $adjusted_count += $this->READ_model->ln_update($adjust_tr['ln_id'], array(
                    'ln_status_source_id' => 6173, //Link Removed
                ), $ln_creator_source_id, 10673 /* Player Link Unlinked */);

            }
        }

        return $adjusted_count;
    }

    function en_sync_domain($url, $ln_creator_source_id = 0, $page_title = null)
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
        $domain_already_existed = 0; //Assume false
        $en_domain = false; //Have an empty placeholder:


        //Check to see if we have domain linked already:
        $domain_links = $this->READ_model->ln_fetch(array(
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            'ln_type_source_id' => 4256, //Generic URL (Domain home pages should always be generic, see above for logic)
            'ln_parent_source_id' => 1326, //Domain Player
            'ln_content' => $domain_analysis['url_clean_domain'],
        ), array('en_child'));


        //Do we need to create an source for this domain?
        if (count($domain_links) > 0) {

            $domain_already_existed = 1;
            $en_domain = $domain_links[0];

        } elseif ($ln_creator_source_id) {

            //Yes, let's add a new source:
            $added_en = $this->SOURCE_model->en_verify_create(( $page_title ? $page_title : $domain_analysis['url_domain_name'] ), $ln_creator_source_id, 6181, detect_fav_icon($domain_analysis['url_clean_domain']));
            $en_domain = $added_en['en'];

            //And link source to the domains source:
            $this->READ_model->ln_create(array(
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_type_source_id' => 4256, //Generic URL (Domains are always generic)
                'ln_parent_source_id' => 1326, //Domain Player
                'ln_child_source_id' => $en_domain['en_id'],
                'ln_content' => $domain_analysis['url_clean_domain'],
            ));

        }


        //Return data:
        return array_merge( $domain_analysis , array(
            'status' => 1,
            'message' => 'Success',
            'domain_already_existed' => $domain_already_existed,
            'en_domain' => $en_domain,
        ));

    }

    function en_sync_creation($ln_creator_source_id, $query= array()){

        //STATS
        $stats = array(
            'ln_type_source_id' => 4251, //Play Created
            'scanned' => 0,
            'missing_creation_fix' => 0,
            'status_sync' => 0,
        );

        //SOURCE STATUS
        $status_converter = array(
            12563 => 12399, //SOURCE FEATURED => TRANSACTION FEATURED
            6181 => 6176, //SOURCE PUBLISH => TRANSACTION PUBLISH
            6180 => 6175, //SOURCE DRAFT => TRANSACTION DRAFT
            6178 => 6173, //SOURCE ARCHIVE => TRANSACTION ARCHIVE
        );
        foreach($this->SOURCE_model->en_fetch($query) as $en){

            $stats['scanned']++;

            //Find creation read:
            $reads = $this->READ_model->ln_fetch(array(
                'ln_type_source_id' => $stats['ln_type_source_id'],
                'ln_child_source_id' => $en['en_id'],
            ));

            if(!count($reads)){

                $stats['missing_creation_fix']++;

                $this->READ_model->ln_create(array(
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_child_source_id' => $en['en_id'],
                    'ln_content' => $en['en_name'],
                    'ln_type_source_id' => $stats['ln_type_source_id'],
                    'ln_status_source_id' => $status_converter[$en['en_status_source_id']],
                ));

            } elseif($reads[0]['ln_status_source_id'] != $status_converter[$en['en_status_source_id']]){

                $stats['status_sync']++;
                $this->READ_model->ln_update($reads[0]['ln_id'], array(
                    'ln_status_source_id' => $status_converter[$en['en_status_source_id']],
                ));

            }

        }

        return $stats;
    }

    function en_sync_url($url, $ln_creator_source_id = 0, $link_parent_en_ids = array(), $add_to_child_en_id = 0, $page_title = null)
    {

        /*
         *
         * Analyzes a URL to see if it and its domain exists.
         * Input legend:
         *
         * - $url:                  Input URL
         * - $ln_creator_source_id:       IF > 0 will save URL (if not already there) and give credit to this source as the trainer
         * - $link_parent_en_ids:  IF array includes source IDs that will be added as parent source of this URL
         * - $add_to_child_en_id:   IF > 0 Will also add URL to this child if present
         * - $page_title:           If set it would override the source title that is auto generated (Used in Add Source Wizard to enable trainers to edit auto generated title)
         *
         * */


        //Validate URL:
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        } elseif ((count($link_parent_en_ids) > 0 || $add_to_child_en_id > 0) && $ln_creator_source_id < 1) {
            return array(
                'status' => 0,
                'message' => 'Parent source is required to add a parent URL',
            );
        }

        //Remember if source name was passed:
        $name_was_passed = ( $page_title ? true : false );

        //Initially assume Generic URL unless we can prove otherwise:
        $ln_type_source_id = 4256; //Generic URL

        //We'll check to see if URL already existed:
        $url_already_existed = 0;

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
                    $this->READ_model->ln_create(array(
                        'ln_content' => 'en_sync_url() detected unknown file extension ['.$domain_analysis['url_file_extension'].'] that needs to be added to @11080',
                        'ln_type_source_id' => 4246, //Platform Bug Reports
                        'ln_parent_source_id' => 11080,
                        'ln_metadata' => $domain_analysis,
                    ));
                }
            }
        }

        //Only fetch URL content in certain situations:
        $url_content = null;
        if(!in_array($ln_type_source_id, $this->config->item('en_ids_11059')) /* not a direct file type */ && !(substr_count($url,'youtube.com/embed/')>0 && (substr_count($url,'start=')>0 || substr_count($url,'end=')>0))){

            //Make CURL call:
            $url_content = @file_get_contents($url);

            //See if we have a canonical metadata on page?
            if(!$domain_analysis['url_is_root'] && substr_count($url_content,'rel="canonical"') > 0){
                //We seem to have it:
                $page_parts = explode('rel="canonical"',$url_content,2);
                $canonical_url = one_two_explode('href="', '"', $page_parts[1]);
                if(filter_var($canonical_url, FILTER_VALIDATE_URL)){
                    //Replace this with the input URL:
                    $url = $canonical_url;
                }
            }
        }


        //Fetch page title if source name not provided:
        if (!$name_was_passed) {

            //Define unique URL identifier string:
            $url_identified = substr(md5($url), 0, 16);

            //Attempt to fetch from page if we have content:
            if($url_content){
                $page_title = one_two_explode('>', '', one_two_explode('<title', '</title', $url_content));
                $title_exclusions = array('-', '|');
                foreach ($title_exclusions as $keyword) {
                    if (substr_count($page_title, $keyword) > 0) {
                        $parts = explode($keyword, $page_title);
                        $last_peace = $parts[(count($parts) - 1)];

                        //Should we remove the last part if not too long?
                        if (substr($last_peace, 0, 1) == ' ' && strlen($last_peace) < 16) {
                            $page_title = str_replace($keyword . $last_peace, '', $page_title);
                            break; //Only a single extension, so break the loop
                        }
                    }
                }
            }

            //Trip title:
            $page_title = trim($page_title);

            if (strlen($page_title) > 0) {

                //Make sure this is not a duplicate name:
                $dup_name_us = $this->SOURCE_model->en_fetch(array(
                    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
                    'en_name' => $page_title,
                ));

                if (count($dup_name_us) > 0) {
                    //Yes, we did find a duplicate name! Append a unique identifier:
                    $page_title = $page_title . ' ' . $url_identified;
                }

            } else {

                //did not find a <title> tag, so let's use URL Type & identifier as its name:
                $en_all_4537 = $this->config->item('en_all_4537');
                $page_title = $en_all_4537[$ln_type_source_id]['m_name'] . ' ' . $url_identified;

            }

        }


        //Fetch/Create domain source:
        $page_title = ( $domain_analysis['url_is_root'] && $name_was_passed ? $page_title : null );
        $domain_source = $this->SOURCE_model->en_sync_domain($url, $ln_creator_source_id, $page_title);
        if(!$domain_source['status']){
            //We had an issue:
            return $domain_source;
        }


        //Was this not a root domain? If so, also check to see if URL exists:
        if ($domain_analysis['url_is_root']) {

            //URL is the domain in this case:
            $en_url = $domain_source['en_domain'];

            //IF the URL exists since the domain existed and the URL is the domain!
            if ($domain_source['domain_already_existed']) {
                $url_already_existed = 1;
            }

        } else {

            //Check to see if URL already exists:
            $url_links = $this->READ_model->ln_fetch(array(
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Player URL Links
                'ln_content' => $url,
            ), array('en_child'));


            //Do we need to create an source for this URL?
            if (count($url_links) > 0) {

                //Nope, source already exists:
                $en_url = $url_links[0];
                $url_already_existed = 1;

            } elseif($ln_creator_source_id) {

                if(!$page_title){
                    //Assign a generic source name:
                    $en_all_4592 = $this->config->item('en_all_4592');
                    $page_title = $en_all_4592[$ln_type_source_id]['m_name'].' '.substr(md5($url), 0, 16);
                }

                //Create a new source for this URL ONLY If trainer source is provided...
                $added_en = $this->SOURCE_model->en_verify_create($page_title, $ln_creator_source_id, 6181);
                if($added_en['status']){

                    //All good:
                    $en_url = $added_en['en'];

                    //Always link URL to its parent domain:
                    $this->READ_model->ln_create(array(
                        'ln_creator_source_id' => $ln_creator_source_id,
                        'ln_type_source_id' => $ln_type_source_id,
                        'ln_parent_source_id' => $domain_source['en_domain']['en_id'],
                        'ln_child_source_id' => $en_url['en_id'],
                        'ln_content' => $url,
                    ));

                } else {
                    //Log error:
                    $this->READ_model->ln_create(array(
                        'ln_content' => 'en_sync_url['.$url.'] FAILED to en_verify_create['.$page_title.'] with message: '.$added_en['message'],
                        'ln_type_source_id' => 4246, //Platform Bug Reports
                        'ln_creator_source_id' => $ln_creator_source_id,
                        'ln_parent_source_id' => $domain_source['en_domain']['en_id'],
                        'ln_metadata' => array(
                            'url' => $url,
                            'ln_creator_source_id' => $ln_creator_source_id,
                            'link_parent_en_ids' => $link_parent_en_ids,
                            'add_to_child_en_id' => $add_to_child_en_id,
                            'page_title' => $page_title,
                        ),
                    ));
                }

            } else {
                //URL not found and no trainer source provided to create the URL:
                $en_url = array();
            }
        }


        //Have we been asked to also add URL to another parent or child?
        if (!$url_already_existed && count($link_parent_en_ids) > 0) {
            //Link URL to its parent domain:
            foreach($link_parent_en_ids as $p_en_id){
                $this->READ_model->ln_create(array(
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_type_source_id' => 4230, //Raw
                    'ln_parent_source_id' => $p_en_id,
                    'ln_child_source_id' => $en_url['en_id'],
                ));
            }
        }

        if (!$url_already_existed && $add_to_child_en_id) {
            //Link URL to its parent domain:
            $this->READ_model->ln_create(array(
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_type_source_id' => 4230, //Raw
                'ln_child_source_id' => $add_to_child_en_id,
                'ln_parent_source_id' => $en_url['en_id'],
            ));
        }


        //Return results:
        return array_merge(

            $domain_analysis, //Make domain analysis data available as well...

            array(
                'status' => ($url_already_existed && !$ln_creator_source_id ? 0 : 1),
                'message' => ($url_already_existed && !$ln_creator_source_id ? 'URL is already linked to @' . $en_url['en_id'] . ' ' . $en_url['en_name'].' [READ ID '.$en_url['ln_id'].']' : 'Success'),
                'url_already_existed' => $url_already_existed,
                'cleaned_url' => $url,
                'ln_type_source_id' => $ln_type_source_id,
                'page_title' => html_entity_decode($page_title, ENT_QUOTES),
                'en_domain' => $domain_source['en_domain'],
                'en_url' => $en_url,
            )
        );
    }

    function en_search_match($en_parent_id, $value)
    {

        if($en_parent_id<1 || strlen(trim($value))<1){
            return 0;
        }

        //Is this a timezone? We need to adjust the timezone according to our limited timezone sources
        if ($en_parent_id == 3289) {
            $valid_halfs = array(-4, -3, 3, 4, 9); //These are timezones with half values so far
            $decimal = fmod(doubleval($value), 1);
            if (!($decimal == 0)) {
                $whole = intval(str_replace('.' . $decimal, '', $value));
                if (in_array(intval($whole), $valid_halfs)) {
                    $value = $whole + ($whole < 0 ? -0.5 : +0.5);
                } else {
                    $value = round(doubleval($value));
                }
            }
        }


        //Search and see if we can find $value in the link content:
        $matching_sources = $this->READ_model->ln_fetch(array(
            'ln_parent_source_id' => $en_parent_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
            'ln_content' => trim($value),
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
        ), array(), 0);


        if (count($matching_sources) == 1) {

            //Bingo, return result:
            return intval($matching_sources[0]['ln_child_source_id']);

        } else {

            //Ooooopsi, this value did not exist! Notify the Trainer so we can look into this:
            $this->READ_model->ln_create(array(
                'ln_content' => 'en_search_match() found [' . count($matching_sources) . '] results as the children of en_id=[' . $en_parent_id . '] that had the value of [' . $value . '].',
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_child_source_id' => $en_parent_id,
            ));

            return 0;
        }
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

        } elseif(in_array($action_en_id, array(5981, 5982, 11956)) && !is_valid_en_string($action_command1)){

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
        $children = $this->READ_model->ln_fetch(array(
            'ln_parent_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
        ), array('en_child'), 0);


        //Process request:
        foreach ($children as $en) {

            //Logic here must match items in en_mass_actions config variable

            //Take command-specific action:
            if ($action_en_id == 4998) { //Add Prefix String

                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_name' => strtoupper($action_command1) . $en['en_name'],
                ), true, $ln_creator_source_id);

                $applied_success++;

            } elseif ($action_en_id == 4999) { //Add Postfix String

                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_name' => $en['en_name'] . strtoupper($action_command1),
                ), true, $ln_creator_source_id);

                $applied_success++;

            } elseif (in_array($action_en_id, array(5981, 5982, 11956))) { //Add/Remove parent source

                //What trainer searched for:
                $parent_en_id = intval(one_two_explode('@',' ',$action_command1));

                //See if child source has searched parent source:
                $child_parent_ens = $this->READ_model->ln_fetch(array(
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                    'ln_child_source_id' => $en['en_id'], //This child source
                    'ln_parent_source_id' => $parent_en_id,
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                ));

                if($action_en_id==5981 && count($child_parent_ens)==0){

                    //Parent Player Addition

                    //Does not exist, need to be added as parent:
                    $this->READ_model->ln_create(array(
                        'ln_creator_source_id' => $ln_creator_source_id,
                        'ln_type_source_id' => 4230, //Raw
                        'ln_child_source_id' => $en['en_id'], //This child source
                        'ln_parent_source_id' => $parent_en_id,
                    ));

                    $applied_success++;

                } elseif(count($child_parent_ens) > 0 && in_array($action_en_id, array(5982, 11956))){

                    if($action_en_id==5982){

                        //Parent Player Removal
                        foreach($child_parent_ens as $remove_tr){

                            $this->READ_model->ln_update($remove_tr['ln_id'], array(
                                'ln_status_source_id' => 6173, //Link Removed
                            ), $ln_creator_source_id, 10673 /* Player Link Unlinked  */);

                            $applied_success++;
                        }

                    } elseif($action_en_id==11956) {

                        $parent_new_en_id = intval(one_two_explode('@',' ',$action_command2));

                        //Add as a parent because it meets the condition
                        $this->READ_model->ln_create(array(
                            'ln_creator_source_id' => $ln_creator_source_id,
                            'ln_type_source_id' => 4230, //Raw
                            'ln_child_source_id' => $en['en_id'], //This child source
                            'ln_parent_source_id' => $parent_new_en_id,
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

                $this->READ_model->ln_update($en['ln_id'], array(
                    'ln_content' => str_replace($action_command1, $action_command2, $en['ln_content']),
                ), $ln_creator_source_id, 10657 /* Player Link Iterated Content  */);

                $applied_success++;

            } elseif ($action_en_id == 5003 && ($action_command1=='*' || $en['en_status_source_id']==$action_command1) && in_array($action_command2, $this->config->item('en_ids_6177'))) { //Update Matching Player Status

                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_status_source_id' => $action_command2,
                ), true, $ln_creator_source_id);

                $applied_success++;

            } elseif ($action_en_id == 5865 && ($action_command1=='*' || $en['ln_status_source_id']==$action_command1) && in_array($action_command2, $this->config->item('en_ids_6186') /* Transaction Status */)) { //Update Matching Transaction Status

                $this->READ_model->ln_update($en['ln_id'], array(
                    'ln_status_source_id' => $action_command2,
                ), $ln_creator_source_id, ( in_array($action_command2, $this->config->item('en_ids_7360') /* Transaction Status Active */) ? 10656 /* Player Link Iterated Status */ : 10673 /* Player Link Unlinked */ ));

                $applied_success++;

            }
        }


        //Log mass source edit link:
        $this->READ_model->ln_create(array(
            'ln_creator_source_id' => $ln_creator_source_id,
            'ln_type_source_id' => $action_en_id,
            'ln_child_source_id' => $en_id,
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
            'message' => '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>'.$applied_success . '/' . count($children) . ' sources updated</div>',
        );

    }

    function en_child_count($en_id, $en_statuses)
    {

        //Count the active children of source:
        $en__child_count = 0;

        //Do a child count:
        $child_links = $this->READ_model->ln_fetch(array(
            'ln_parent_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            'en_status_source_id IN (' . join(',', $en_statuses) . ')' => null,
        ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as totals');

        if (count($child_links) > 0) {
            $en__child_count = intval($child_links[0]['totals']);
        }

        return $en__child_count;
    }

    function en_messenger_auth($psid, $quick_reply_payload = null)
    {

        /*
         *
         * Detects the User source ID based on the
         * PSID provided by the Facebook Webhook Call.
         * This function returns the User's source object $en
         *
         */

        if ($psid < 1) {
            //Ooops, this should never happen:
            $this->READ_model->ln_create(array(
                'ln_content' => 'en_messenger_auth() got called without a valid Facebook $psid variable',
                'ln_type_source_id' => 4246, //Platform Bug Reports
            ));
            return false;
        }

        //Try matching Facebook PSID to existing Users:
        $user_messenger = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
            'ln_parent_source_id' => 6196, //Mench Messenger
            'ln_external_id' => $psid,
        ), array('en_child'));

        //So, did we find them?
        if (count($user_messenger) > 0) {

            //User found...
            return $user_messenger[0];

        } else {

            //User not found, create new User:
            return $this->SOURCE_model->en_messenger_add($psid, $quick_reply_payload);

        }

    }

    function en_verify_create($en_name, $ln_creator_source_id = 0, $en_status_source_id = 6181 /* Player Drafting */, $en_icon = null){

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
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
            'LOWER(en_name)' => strtolower(trim($en_name)),
        ));


        //Create source
        $source_new = $this->SOURCE_model->en_create(array(
            'en_name' => trim($en_name),
            'en_icon' => $en_icon,
            'en_status_source_id' => $en_status_source_id,
        ), true, $ln_creator_source_id);


        if(count($duplicate_ens) > 0){
            //Log a link to inform Trainer of this:
            $this->READ_model->ln_create(array(
                'ln_content' => 'Duplicate source names detected for ['.$duplicate_ens[0]['en_name'].']',
                'ln_type_source_id' => 7504, //Trainer Review Required
                'ln_child_source_id' => $source_new['en_id'],
                'ln_parent_source_id' => $duplicate_ens[0]['en_id'],
                'ln_creator_source_id' => $ln_creator_source_id,
            ));
        }

        //Return success:
        return array(
            'status' => 1,
            'en' => $source_new,
        );

    }

    function en_messenger_add($psid, $quick_reply_payload = null)
    {

        /*
         *
         * This function will attempt to create a new User Player
         * Using the PSID provided by Facebook Graph API
         *
         * */

        if ($psid < 1) {
            //Ooops, this should never happen:
            $this->READ_model->ln_create(array(
                'ln_content' => 'en_messenger_add() got called without a valid Facebook $psid variable',
                'ln_type_source_id' => 4246, //Platform Bug Reports
            ));
            return false;
        } elseif(count($this->READ_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_parent_source_id' => 6196, //Mench Messenger
                'ln_external_id' => $psid,
            )))>0){
            //PSID Already added:
            return false;
        }

        //Call facebook messenger API and get user graph profile:
        $graph_fetch = $this->READ_model->facebook_graph('GET', '/' . $psid, array());


        $fetch_result = ( isset($graph_fetch['status']) && $graph_fetch['status'] && isset($graph_fetch['ln_metadata']['result']['first_name']) && strlen($graph_fetch['ln_metadata']['result']['first_name']) > 0);


        //Did we find the profile from FB?
        if (!$fetch_result ) {

            /*
             *
             * No profile on Facebook! This happens when user has logged
             * into messenger with their phone number or for any reason
             * that Facebook does not provide profile details.
             *
             * */

            //Create user source:
            $added_en = $this->SOURCE_model->en_verify_create('User '.rand(100000000, 999999999), 0, 6181, random_source_avatar());

        } else {

            //We did find the profile, move ahead:
            $fb_profile = $graph_fetch['ln_metadata']['result'];

            //Create user source with their Facebook Graph name:
            $added_en = $this->SOURCE_model->en_verify_create($fb_profile['first_name'] . ' ' . $fb_profile['last_name'], 0, 6181, random_source_avatar());


            //See if we could fetch FULL profile data:
            if(isset($fb_profile['locale'])){

                //Split locale variable into language and country like "EN_GB" for English in England
                $locale = explode('_', $fb_profile['locale'], 2);

                //Try to match Facebook profile data to internal sources and create links for the ones we find:
                foreach (array(
                             $this->SOURCE_model->en_search_match(3289, $fb_profile['timezone']), //Timezone
                             $this->SOURCE_model->en_search_match(3290, strtolower(substr($fb_profile['gender'], 0, 1))), //Gender either m/f
                             $this->SOURCE_model->en_search_match(3287, strtolower($locale[0])), //Language
                             $this->SOURCE_model->en_search_match(3089, strtolower($locale[1])), //Country
                         ) as $ln_parent_source_id) {

                    //Did we find a relation? Create the link:
                    if ($ln_parent_source_id > 0) {

                        //Create new link:
                        $this->READ_model->ln_create(array(
                            'ln_type_source_id' => 4230, //Raw link
                            'ln_creator_source_id' => $added_en['en']['en_id'], //User gets credit as trainer
                            'ln_parent_source_id' => $ln_parent_source_id,
                            'ln_child_source_id' => $added_en['en']['en_id'],
                        ));

                    }
                }
            }
        }


        //New source link is already logged in the source creation function
        //Now create more relevant links:

        //Activate Mench Messenger
        $this->READ_model->ln_create(array(
            'ln_parent_source_id' => 6196, //Mench Messenger
            'ln_type_source_id' => 4230, //Raw link
            'ln_creator_source_id' => $added_en['en']['en_id'],
            'ln_child_source_id' => $added_en['en']['en_id'],
            'ln_external_id' => $psid,
        ));

        $this->READ_model->ln_create(array(
            'ln_type_source_id' => 4230, //Raw link
            'ln_parent_source_id' => 12222, //Notify on MESSENGER
            'ln_creator_source_id' => $added_en['en']['en_id'],
            'ln_child_source_id' => $added_en['en']['en_id'],
        ));

        //Add default Notification Level:
        $this->READ_model->ln_create(array(
            'ln_parent_source_id' => 4456, //Receive Regular Notifications (User can change later on...)
            'ln_type_source_id' => 4230, //Raw link
            'ln_creator_source_id' => $added_en['en']['en_id'],
            'ln_child_source_id' => $added_en['en']['en_id'],
        ));


        //Add Player:
        $this->SOURCE_model->en_create_player($added_en['en']['en_id']);


        if(!$fetch_result){
            //Let them know to complete their profile:
            $this->READ_model->dispatch_message(
                'Hi! I just added you as a new source. You can update your account at any time. 🤗 /link:Update My Account:https://mench.com/source/account',
                $added_en['en'],
                true
            );
        }

        //Return source object:
        return $added_en['en'];

    }


}

function en_create_player($en_id){

    //Add them to Users group:
    $this->READ_model->ln_create(array(
        'ln_parent_source_id' => 4430, //Mench User
        'ln_type_source_id' => 4230, //Raw link
        'ln_creator_source_id' => $en_id,
        'ln_child_source_id' => $en_id,
    ));

    $this->READ_model->ln_create(array(
        'ln_type_source_id' => 4230, //Raw link
        'ln_parent_source_id' => 1278, //People
        'ln_creator_source_id' => $en_id,
        'ln_child_source_id' => $en_id,
    ));


    //See which promo to assign:
    $child_links = $this->READ_model->ln_fetch(array(
        'ln_parent_source_id' => 4430, //Count Players
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
        'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
    ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as totals');

    //Early account signup gift:
    if(child_links[0]['totals'] <= 10000){

        $this->READ_model->ln_create(array(
            'ln_type_source_id' => 4230, //Raw link
            'ln_parent_source_id' => ( $child_links[0]['totals']<=5000 ? 12635 /* 5 Years */ : ( $child_links[0]['totals']<=8000 ? 12636 /* 3 Years */ : ( $child_links[0]['totals']<=10000 ? 12637 /* 2 Years */ : 12638 /* 1 Year which is inactive for now */ ) ) ), //FREE ACCOUNT
            'ln_creator_source_id' => $en_id,
            'ln_child_source_id' => $en_id,
        ));

    }

}