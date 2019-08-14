<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Entities_model extends CI_Model
{

    /*
     *
     * Entity related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }




    function en_create($insert_columns, $external_sync = false, $ln_creator_entity_id = 0)
    {

        //What is required to create a new intent?
        if (detect_missing_columns($insert_columns, array('en_status_entity_id', 'en_name'), $ln_creator_entity_id)) {
            return false;
        }

        if (isset($insert_columns['en_metadata'])) {
            $insert_columns['en_metadata'] = serialize($insert_columns['en_metadata']);
        } else {
            $insert_columns['en_metadata'] = null;
        }

        if (!isset($insert_columns['en_trust_score'])) {
            //Will be later calculated via a cron job:
            $insert_columns['en_trust_score'] = 0;
        }

        //Lets now add:
        $this->db->insert('table_entities', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['en_id'])) {
            $insert_columns['en_id'] = $this->db->insert_id();
        }

        if ($insert_columns['en_id'] > 0) {

            if($external_sync){
                //Update Algolia:
                $algolia_sync = update_algolia('en', $insert_columns['en_id']);
            }

            //Log link new entity:
            $this->Links_model->ln_create(array(
                'ln_creator_entity_id' => ($ln_creator_entity_id > 0 ? $ln_creator_entity_id : $insert_columns['en_id']),
                'ln_child_entity_id' => $insert_columns['en_id'],
                'ln_type_entity_id' => 4251, //New Entity Created
            ));

            //Fetch to return the complete entity data:
            $ens = $this->Entities_model->en_fetch(array(
                'en_id' => $insert_columns['en_id'],
            ));

            return $ens[0];

        } else {

            //Ooopsi, something went wrong!
            $this->Links_model->ln_create(array(
                'ln_parent_entity_id' => $ln_creator_entity_id,
                'ln_content' => 'en_create() failed to create a new entity',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_creator_entity_id' => $ln_creator_entity_id,
                'ln_metadata' => $insert_columns,
            ));
            return false;

        }
    }

    function en_fetch($match_columns = array(), $join_objects = array(), $limit = 0, $limit_offset = 0, $order_columns = array('en_name' => 'ASC'), $select = '*', $group_by = null)
    {

        //Fetch the target entities:
        $this->db->select($select);
        $this->db->from('table_entities');
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
        $res = $q->result_array();


        //Now fetch parents:
        foreach ($res as $key => $val) {

            //This will Count ALL the children:
            if (in_array('en__child_count', $join_objects)) {

                //Count children:
                $res[$key]['en__child_count'] = $this->Entities_model->en_child_count($val['en_id'], $this->config->item('en_ids_7358') /* Entity Statuses Active */);
            }


            //Always fetch entity parents unless explicitly requested not to:
            if (in_array('skip_en__parents', $join_objects) || !isset($val['en_id'])) {

                $res[$key]['en__parents'] = array();

            } else {

                //Fetch parents by default:
                $res[$key]['en__parents'] = $this->Links_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    'ln_child_entity_id' => $val['en_id'], //This child entity
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
                ), array('en_parent'), 0, 0, array('en_name' => 'ASC'));

            }
        }

        return $res;
    }

    function en_update($id, $update_columns, $external_sync = false, $ln_creator_entity_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current entity filed values so we can compare later on after we've updated it:
        if($ln_creator_entity_id > 0){
            $before_data = $this->Entities_model->en_fetch(array('en_id' => $id));
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['en_metadata']) && is_array($update_columns['en_metadata'])){
            $update_columns['en_metadata'] = serialize($update_columns['en_metadata']);
        }


        //Update:
        $this->db->where('en_id', $id);
        $this->db->update('table_entities', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $ln_creator_entity_id > 0) {

            $en_all_6177 = $this->config->item('en_all_6177'); //Entity Statuses

            //Log modification link for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (!($before_data[0][$key] == $value) && !in_array($key, array('en_metadata', 'en_trust_score'))) {



                    //Value has changed, log link:
                    $this->Links_model->ln_create(array(
                        'ln_creator_entity_id' => ($ln_creator_entity_id > 0 ? $ln_creator_entity_id : $id),
                        'ln_type_entity_id' => 4263, //Entity Attribute Modified
                        'ln_child_entity_id' => $id,
                        'ln_content' => echo_clean_db_name($key) . ' changed from "' . ( $key=='en_status_entity_id' ? $en_all_6177[$before_data[0][$key]]['m_name'] : $before_data[0][$key] ) . '" to "' . ( $key=='en_status_entity_id' ? $en_all_6177[$value]['m_name'] : $value ) . '"',
                        'ln_metadata' => array(
                            'en_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));


                }

            }

            if($external_sync){
                //Sync algolia:
                $algolia_sync = update_algolia('en', $id);
            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->Links_model->ln_create(array(
                'ln_child_entity_id' => $id,
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_creator_entity_id' => $ln_creator_entity_id,
                'ln_content' => 'en_update() Failed to update',
                'ln_metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }


    function en_radio_set($en_parent_bucket_id, $set_en_child_id = 0, $en_user_id, $ln_creator_entity_id = 0)
    {

        /*
         * Treats an entity child group as a drop down menu where:
         *
         *  $en_parent_bucket_id is the parent of the drop down
         *  $en_user_id is the user entity ID that one of the children of $en_parent_bucket_id should be assigned (like a drop down)
         *  $set_en_child_id is the new value to be assigned, which could also be null (meaning just remove all current values)
         *
         * This function is helpful to manage things like User communication levels
         *
         * */


        //Fetch all the child entities for $en_parent_bucket_id and make sure they match $set_en_child_id
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
        foreach ($this->Links_model->ln_fetch(array(
            'ln_child_entity_id' => $en_user_id,
            'ln_parent_entity_id IN (' . join(',', $children) . ')' => null, //Current children
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ), array(), $this->config->item('items_per_page')) as $ln) {

            if (!$already_assigned && $ln['ln_parent_entity_id'] == $set_en_child_id) {
                $already_assigned = true;
            } else {
                //Remove assignment:
                $updated_ln_id = $ln['ln_id'];

                //Do not log update link here as we would log it further below:
                $this->Links_model->ln_update($ln['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                ));
            }

        }


        //Make sure $set_en_child_id belongs to parent if set (Could be null which means remove all)
        if (!$already_assigned) {
            //Let's go ahead and add desired entity as parent:
            $this->Links_model->ln_create(array(
                'ln_creator_entity_id' => $ln_creator_entity_id,
                'ln_child_entity_id' => $en_user_id,
                'ln_parent_entity_id' => $set_en_child_id,
                'ln_type_entity_id' => 4230, //Raw link
                'ln_parent_link_id' => $updated_ln_id,
            ));
        }

    }

    function en_unlink($en_id, $ln_creator_entity_id = 0, $merger_en_id = 0){

        //Fetch all entity links:
        $adjusted_count = 0;
        foreach(array_merge(
                //Entity references within intent notes:
                    $this->Links_model->ln_fetch(array(
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
                        'ln_parent_entity_id' => $en_id,
                    ), array('in_child'), 0, 0, array('ln_order' => 'ASC')),
                    //Entity links:
                    $this->Links_model->ln_fetch(array(
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                        '(ln_child_entity_id = ' . $en_id . ' OR ln_parent_entity_id = ' . $en_id . ')' => null,
                    ), array(), 0)
                ) as $adjust_tr){

            //Merge only if merger ID provided and link not related to original link:
            if($merger_en_id > 0 && $adjust_tr['ln_parent_entity_id']!=$merger_en_id && $adjust_tr['ln_child_entity_id']!=$merger_en_id){

                //Update core field:
                $target_field = ($adjust_tr['ln_child_entity_id'] == $en_id ? 'ln_child_entity_id' : 'ln_parent_entity_id');
                $updating_fields = array(
                    $target_field => $merger_en_id,
                );

                //Also update possible entity references within Intent Notes content:
                if(substr_count($adjust_tr['ln_content'], '@'.$adjust_tr[$target_field]) == 1){
                    $updating_fields['ln_content'] = str_replace('@'.$adjust_tr[$target_field],'@'.$merger_en_id, $adjust_tr['ln_content']);
                }

                //Update Link:
                $adjusted_count += $this->Links_model->ln_update($adjust_tr['ln_id'], $updating_fields, $ln_creator_entity_id);

            } else {

                //Remove this link:
                $adjusted_count += $this->Links_model->ln_update($adjust_tr['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                ), $ln_creator_entity_id);

            }
        }

        return $adjusted_count;
    }

    function en_sync_domain($url, $ln_creator_entity_id = 0, $page_title = null)
    {
        /*
         *
         * Either finds/returns existing domains or adds it
         * to the Domains entity if $ln_creator_entity_id > 0
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
        $domain_links = $this->Links_model->ln_fetch(array(
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_entity_id' => 4256, //Generic URL (Domain home pages should always be generic, see above for logic)
            'ln_parent_entity_id' => 1326, //Domain Entity
            'ln_content' => $domain_analysis['url_clean_domain'],
        ), array('en_child'));


        //Do we need to create an entity for this domain?
        if (count($domain_links) > 0) {

            $domain_already_existed = 1;
            $en_domain = $domain_links[0];

        } elseif ($ln_creator_entity_id) {

            //Yes, let's add a new entity:
            $added_en = $this->Entities_model->en_verify_create(( $page_title ? $page_title : $domain_analysis['url_domain_name'] ), $ln_creator_entity_id, 6181, detect_fav_icon($domain_analysis['url_clean_domain']));
            $en_domain = $added_en['en'];

            //And link entity to the domains entity:
            $this->Links_model->ln_create(array(
                'ln_creator_entity_id' => $ln_creator_entity_id,
                'ln_status_entity_id' => 6176, //Link Published
                'ln_type_entity_id' => 4256, //Generic URL (Domains are always generic)
                'ln_parent_entity_id' => 1326, //Domain Entity
                'ln_child_entity_id' => $en_domain['en_id'],
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

    function en_sync_url($url, $ln_creator_entity_id = 0, $link_parent_en_ids = array(), $add_to_child_en_id = 0, $page_title = null)
    {

        /*
         *
         * Analyzes a URL to see if it and its domain exists.
         * Input legend:
         *
         * - $url:                  Input URL
         * - $ln_creator_entity_id:       IF > 0 will save URL (if not already there) and give credit to this entity as the miner
         * - $link_parent_en_ids:  IF array includes entity IDs that will be added as parent entity of this URL
         * - $add_to_child_en_id:   IF > 0 Will also add URL to this child if present
         * - $page_title:           If set it would override the entity title that is auto generated (Used in Add Source Wizard to enable miners to edit auto generated title)
         *
         * */


        //Validate URL:
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        } elseif ((count($link_parent_en_ids) > 0 || $add_to_child_en_id > 0) && $ln_creator_entity_id < 1) {
            return array(
                'status' => 0,
                'message' => 'Parent entity is required to add a parent URL',
            );
        }

        //Remember if entity name was passed:
        $name_was_passed = ( $page_title ? true : false );

        //Analyze domain:
        $domain_analysis = analyze_domain($url);

        if ($domain_analysis['url_is_root']) {
            //Update URL to keep synced:
            $url = $domain_analysis['url_clean_domain'];
        }

        //Initially assume Generic URL unless we can prove otherwise:
        $ln_type_entity_id = 4256; //Generic URL

        //We'll check to see if URL already existed:
        $url_already_existed = 0;

        //Start with null and see if we can find/add:
        $en_url = null;

        //Now let's analyze further based on type:
        if ($domain_analysis['url_is_root']) {

            //Since this is the root, update to the clean URL:
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
            $embed_code = echo_url_embed($url, $url, true);

            if ($embed_code['status']) {

                //URL Was detected as an embed URL:
                $ln_type_entity_id = 4257;

            } elseif ($domain_analysis['url_file_extension']) {

                //URL ends with a file extension, try to detect file type based on that extension:
                if(in_array($domain_analysis['url_file_extension'], array('jpeg','jpg','png','gif','tiff','bmp','img','svg','ico'))){
                    //Image URL
                    $ln_type_entity_id = 4260;
                } elseif(in_array($domain_analysis['url_file_extension'], array('pcm','wav','aiff','mp3','aac','ogg','wma','flac','alac','m4a','m4b','m4p'))){
                    //Audio URL
                    $ln_type_entity_id = 4259;
                } elseif(in_array($domain_analysis['url_file_extension'], array('mp4','m4v','m4p','avi','mov','flv','f4v','f4p','f4a','f4b','wmv','webm','mkv','vob','ogv','ogg','3gp','mpg','mpeg','m2v'))){
                    //Video URL
                    $ln_type_entity_id = 4258;
                } elseif(in_array($domain_analysis['url_file_extension'], array('pdc','doc','docx','tex','txt','7z','rar','zip','csv','sql','tar','xml','exe'))){
                    //File URL
                    $ln_type_entity_id = 4261;
                }

            }

        }

        //Only fetch URL content in certain situations:
        $url_content = null;
        if(!array_key_exists($ln_type_entity_id, $this->config->item('fb_convert_4537')) /* not a direct file type */ && !(substr_count($url,'youtube.com/embed/')>0 && (substr_count($url,'start=')>0 || substr_count($url,'end=')>0))){

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


        //Fetch page title if entity name not provided:
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
                $dup_name_us = $this->Entities_model->en_fetch(array(
                    'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
                    'en_name' => $page_title,
                ));

                if (count($dup_name_us) > 0) {
                    //Yes, we did find a duplicate name! Append a unique identifier:
                    $page_title = $page_title . ' ' . $url_identified;
                }

            } else {

                //did not find a <title> tag, so let's use URL Type & identifier as its name:
                $en_all_4537 = $this->config->item('en_all_4537');
                $page_title = $en_all_4537[$ln_type_entity_id]['m_name'] . ' ' . $url_identified;

            }

        }


        //Fetch/Create domain entity:
        $page_title = ( $domain_analysis['url_is_root'] && $name_was_passed ? $page_title : null );
        $domain_entity = $this->Entities_model->en_sync_domain($url, $ln_creator_entity_id, $page_title);
        if(!$domain_entity['status']){
            //We had an issue:
            return $domain_entity;
        }


        //Was this not a root domain? If so, also check to see if URL exists:
        if ($domain_analysis['url_is_root']) {

            //URL is the domain in this case:
            $en_url = $domain_entity['en_domain'];

            //IF the URL exists since the domain existed and the URL is the domain!
            if ($domain_entity['domain_already_existed']) {
                $url_already_existed = 1;
            }

        } else {

            //Check to see if URL already exists:
            $url_links = $this->Links_model->ln_fetch(array(
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_6177')) . ')' => null, //Entity Statuses (ALL)
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_6186')) . ')' => null, //Link Statuses (ALL)
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Entity URL Links
                'ln_content' => $url,
            ), array('en_child'));


            //Do we need to create an entity for this URL?
            if (count($url_links) > 0) {

                //Nope, entity already exists:
                $en_url = $url_links[0];
                $url_already_existed = 1;

            } elseif($ln_creator_entity_id) {

                if(!$page_title){
                    //Assign a generic entity name:
                    $en_all_4592 = $this->config->item('en_all_4592');
                    $page_title = $en_all_4592[$ln_type_entity_id]['m_name'].' '.substr(md5($url), 0, 16);
                }

                //Create a new entity for this URL ONLY If miner entity is provided...
                $added_en = $this->Entities_model->en_verify_create($page_title, $ln_creator_entity_id, 6181);
                if($added_en['status']){

                    //All good:
                    $en_url = $added_en['en'];

                    //Always link URL to its parent domain:
                    $this->Links_model->ln_create(array(
                        'ln_creator_entity_id' => $ln_creator_entity_id,
                        'ln_status_entity_id' => 6176, //Link Published
                        'ln_type_entity_id' => $ln_type_entity_id,
                        'ln_parent_entity_id' => $domain_entity['en_domain']['en_id'],
                        'ln_child_entity_id' => $en_url['en_id'],
                        'ln_content' => $url,
                    ));

                } else {
                    //Log error:
                    $this->Links_model->ln_create(array(
                        'ln_content' => 'en_sync_url['.$url.'] FAILED to en_verify_create['.$page_title.'] with error: '.$added_en['message'],
                        'ln_type_entity_id' => 4246, //Platform Bug Reports
                        'ln_creator_entity_id' => $ln_creator_entity_id,
                        'ln_parent_entity_id' => $domain_entity['en_domain']['en_id'],
                        'ln_metadata' => array(
                            'url' => $url,
                            'ln_creator_entity_id' => $ln_creator_entity_id,
                            'link_parent_en_ids' => $link_parent_en_ids,
                            'add_to_child_en_id' => $add_to_child_en_id,
                            'page_title' => $page_title,
                        ),
                    ));
                }

            } else {
                //URL not found and no miner entity provided to create the URL:
                $en_url = array();
            }
        }


        //Have we been asked to also add URL to another parent or child?
        if (!$url_already_existed && count($link_parent_en_ids) > 0) {
            //Link URL to its parent domain:
            foreach($link_parent_en_ids as $p_en_id){
                $this->Links_model->ln_create(array(
                    'ln_creator_entity_id' => $ln_creator_entity_id,
                    'ln_status_entity_id' => 6176, //Link Published
                    'ln_type_entity_id' => 4230, //Raw
                    'ln_parent_entity_id' => $p_en_id,
                    'ln_child_entity_id' => $en_url['en_id'],
                ));
            }
        }

        if (!$url_already_existed && $add_to_child_en_id) {
            //Link URL to its parent domain:
            $this->Links_model->ln_create(array(
                'ln_creator_entity_id' => $ln_creator_entity_id,
                'ln_status_entity_id' => 6176, //Link Published
                'ln_type_entity_id' => 4230, //Raw
                'ln_child_entity_id' => $add_to_child_en_id,
                'ln_parent_entity_id' => $en_url['en_id'],
            ));
        }


        //Return results:
        return array_merge(

            $domain_analysis, //Make domain analysis data available as well...

            array(
                'status' => ($url_already_existed && !$ln_creator_entity_id ? 0 : 1),
                'message' => ($url_already_existed && !$ln_creator_entity_id ? 'URL is already linked to @' . $en_url['en_id'] . ' ' . $en_url['en_name'].' [Link ID '.$en_url['ln_id'].']' : 'Success'),
                'url_already_existed' => $url_already_existed,
                'cleaned_url' => $url,
                'ln_type_entity_id' => $ln_type_entity_id,
                'page_title' => html_entity_decode($page_title, ENT_QUOTES),
                'en_domain' => $domain_entity['en_domain'],
                'en_url' => $en_url,
            )
        );
    }

    function en_search_match($en_parent_id, $value)
    {

        if($en_parent_id<1 || strlen(trim($value))<1){
            return 0;
        }

        //Is this a timezone? We need to adjust the timezone according to our limited timezone entities
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
        $matching_entities = $this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => $en_parent_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_content' => trim($value),
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ), array(), 0);


        if (count($matching_entities) == 1) {

            //Bingo, return result:
            return intval($matching_entities[0]['ln_child_entity_id']);

        } else {

            //Ooooopsi, this value did not exist! Notify the admin so we can look into this:
            $this->Links_model->ln_create(array(
                'ln_content' => 'en_search_match() found [' . count($matching_entities) . '] results as the children of en_id=[' . $en_parent_id . '] that had the value of [' . $value . '].',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_child_entity_id' => $en_parent_id,
            ));

            return 0;
        }
    }

    function en_mass_update($en_id, $action_en_id, $action_command1, $action_command2, $ln_creator_entity_id)
    {

        //Fetch statuses:
        $en_all_4997 = $this->config->item('en_all_4997');

        if(!in_array($action_en_id, $this->config->item('en_ids_4997'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif($action_en_id != 5943 && strlen(trim($action_command1)) < 1){

            return array(
                'status' => 0,
                'message' => 'Missing primary command',
            );

        } elseif($action_en_id == 5943 && !is_valid_icon($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Invalid icon: '. is_valid_icon(null, true),
            );

        } elseif(in_array($action_en_id, array(5981, 5982)) && !(substr($action_command1, 0, 1) == '@' && is_numeric(one_two_explode('@',' ',$action_command1)))){

            return array(
                'status' => 0,
                'message' => 'Unknown searched entity. Format must be: @123 Entity Name',
            );

        }



        //Basic input validation done, let's continue...


        //Fetch all children:
        $applied_success = 0; //To be populated...
        $children = $this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => $en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
        ), array('en_child'), 0);


        //Process request:
        foreach ($children as $en) {

            //Logic here must match items in en_mass_actions config variable

            //Take command-specific action:
            if ($action_en_id == 4998) { //Add Prefix String

                $this->Entities_model->en_update($en['en_id'], array(
                    'en_name' => $action_command1 . $en['en_name'],
                ), true, $ln_creator_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 4999) { //Add Postfix String

                $this->Entities_model->en_update($en['en_id'], array(
                    'en_name' => $en['en_name'] . $action_command1,
                ), true, $ln_creator_entity_id);

                $applied_success++;

            } elseif (in_array($action_en_id, array(5981, 5982))) { //Add/Remove parent entity

                //What miner searched for:
                $parent_en_id = intval(one_two_explode('@',' ',$action_command1));

                //See if child entity has searched parent entity:
                $child_parent_ens = $this->Links_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    'ln_child_entity_id' => $en['en_id'], //This child entity
                    'ln_parent_entity_id' => $parent_en_id,
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                ));

                if($action_en_id==5981 && count($child_parent_ens)==0){ //Parent Entity Addition

                    //Does not exist, need to be added as parent:
                    $this->Links_model->ln_create(array(
                        'ln_status_entity_id' => 6176, //Link Published
                        'ln_creator_entity_id' => $ln_creator_entity_id,
                        'ln_type_entity_id' => 4230, //Raw
                        'ln_child_entity_id' => $en['en_id'], //This child entity
                        'ln_parent_entity_id' => $parent_en_id,
                    ));

                    $applied_success++;

                } elseif($action_en_id==5982 && count($child_parent_ens) > 0){ //Parent Entity Removal

                    //Already added as parent so it needs to be removed:
                    foreach($child_parent_ens as $remove_tr){

                        $this->Links_model->ln_update($remove_tr['ln_id'], array(
                            'ln_status_entity_id' => 6173, //Link Removed
                        ), $ln_creator_entity_id);

                        $applied_success++;
                    }

                }

            } elseif ($action_en_id == 5943) { //Entity Mass Update Entity Icon

                $this->Entities_model->en_update($en['en_id'], array(
                    'en_icon' => $action_command1,
                ), true, $ln_creator_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5000 && substr_count($en['en_name'], $action_command1) > 0) { //Replace Entity Matching String

                //Make sure the SEARCH string exists:
                $this->Entities_model->en_update($en['en_id'], array(
                    'en_name' => str_replace($action_command1, $action_command2, $en['en_name']),
                ), true, $ln_creator_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5001 && substr_count($en['ln_content'], $action_command1) > 0) { //Replace Link Matching String

                $this->Links_model->ln_update($en['ln_id'], array(
                    'ln_content' => str_replace($action_command1, $action_command2, $en['ln_content']),
                ), $ln_creator_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5003 && ($action_command1=='*' || $en['en_status_entity_id']==$action_command1) && in_array($action_command2, $this->config->item('en_ids_6177'))) { //Update Matching Entity Status

                $this->Entities_model->en_update($en['en_id'], array(
                    'en_status_entity_id' => $action_command2,
                ), true, $ln_creator_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5865 && ($action_command1=='*' || $en['ln_status_entity_id']==$action_command1) && in_array($action_command2, $this->config->item('en_ids_6186'))) { //Update Matching Link Status

                $this->Links_model->ln_update($en['ln_id'], array(
                    'ln_status_entity_id' => $action_command2,
                ), $ln_creator_entity_id);

                $applied_success++;

            }
        }


        //Log mass entity edit link:
        $this->Links_model->ln_create(array(
            'ln_creator_entity_id' => $ln_creator_entity_id,
            'ln_type_entity_id' => $action_en_id,
            'ln_child_entity_id' => $en_id,
            'ln_metadata' => array(
                'payload' => $_POST,
                'entities_total' => count($children),
                'entities_updated' => $applied_success,
                'command1' => $action_command1,
                'command2' => $action_command2,
            ),
        ));

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . '/' . count($children) . ' entities updated',
        );

    }

    function en_child_count($en_id, $en_statuses)
    {

        //Count the active children of entity:
        $en__child_count = 0;

        //Do a child count:
        $child_links = $this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => $en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'en_status_entity_id IN (' . join(',', $en_statuses) . ')' => null,
        ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

        if (count($child_links) > 0) {
            $en__child_count = intval($child_links[0]['en__child_count']);
        }

        return $en__child_count;
    }

    function en_messenger_auth($psid, $quick_reply_payload = null)
    {

        /*
         *
         * Detects the User entity ID based on the
         * PSID provided by the Facebook Webhook Call.
         * This function returns the User's entity object $en
         *
         */

        if ($psid < 1) {
            //Ooops, this should never happen:
            $this->Links_model->ln_create(array(
                'ln_content' => 'en_messenger_auth() got called without a valid Facebook $psid variable',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
            ));
            return false;
        }

        //Try matching Facebook PSID to existing Users:
        $user_messenger = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_parent_entity_id' => 6196, //Mench Messenger
            'ln_external_id' => $psid,
        ), array('en_child'));

        //So, did we find them?
        if (count($user_messenger) > 0) {

            //User found...
            return $user_messenger[0];

        } else {

            //User not found, create new User:
            return $this->Entities_model->en_messenger_add($psid, $quick_reply_payload);

        }

    }

    function en_verify_create($en_name, $ln_creator_entity_id = 0, $en_status_entity_id = 6180 /* Entity Drafting */, $en_icon = null){

        //If PSID exists, make sure it's not a duplicate:
        if(!in_array($en_status_entity_id, $this->config->item('en_ids_6177'))){
            //Invalid Status ID
            return array(
                'status' => 0,
                'message' => 'Invalid Entity Status',
            );
        }

        //Not found, so we need to create, and need a name by now:
        if(strlen($en_name)<2){
            return array(
                'status' => 0,
                'message' => 'Entity name must be at-least 2 characters long',
            );
        }


        //Check to make sure name is not duplicate:
        $duplicate_ens = $this->Entities_model->en_fetch(array(
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
            'LOWER(en_name)' => strtolower(trim($en_name)),
        ));


        //Create entity
        $entity_new = $this->Entities_model->en_create(array(
            'en_name' => trim($en_name),
            'en_icon' => $en_icon,
            'en_status_entity_id' => $en_status_entity_id,
        ), true, $ln_creator_entity_id);


        if(count($duplicate_ens) > 0){
            //Log a link to inform admin of this:
            $this->Links_model->ln_create(array(
                'ln_content' => 'Duplicate entity names detected for ['.$duplicate_ens[0]['en_name'].']',
                'ln_type_entity_id' => 7504, //Admin Review Required
                'ln_child_entity_id' => $entity_new['en_id'],
                'ln_parent_entity_id' => $duplicate_ens[0]['en_id'],
                'ln_creator_entity_id' => $ln_creator_entity_id,
            ));
        }

        //Return success:
        return array(
            'status' => 1,
            'en' => $entity_new,
        );

    }

    function en_messenger_add($psid, $quick_reply_payload = null)
    {

        /*
         *
         * This function will attempt to create a new User Entity
         * Using the PSID provided by Facebook Graph API
         *
         * */

        if ($psid < 1) {
            //Ooops, this should never happen:
            $this->Links_model->ln_create(array(
                'ln_content' => 'en_messenger_add() got called without a valid Facebook $psid variable',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
            ));
            return false;
        } elseif(count($this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_parent_entity_id' => 6196, //Mench Messenger
                'ln_external_id' => $psid,
            )))>0){
            //PSID Already added:
            return false;
        }

        //Call facebook messenger API and get user graph profile:
        $graph_fetch = $this->Communication_model->facebook_graph('GET', '/' . $psid, array());


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

            //Create user entity:
            $added_en = $this->Entities_model->en_verify_create('User '.rand(100000000, 999999999), 0, 6181);

        } else {

            //We did find the profile, move ahead:
            $fb_profile = $graph_fetch['ln_metadata']['result'];

            //Create user entity with their Facebook Graph name:
            $added_en = $this->Entities_model->en_verify_create($fb_profile['first_name'] . ' ' . $fb_profile['last_name'], 0, 6181);


            //See if we could fetch FULL profile data:
            if(isset($fb_profile['locale'])){

                //Split locale variable into language and country like "EN_GB" for English in England
                $locale = explode('_', $fb_profile['locale'], 2);

                //Try to match Facebook profile data to internal entities and create links for the ones we find:
                foreach (array(
                             $this->Entities_model->en_search_match(3289, $fb_profile['timezone']), //Timezone
                             $this->Entities_model->en_search_match(3290, strtolower(substr($fb_profile['gender'], 0, 1))), //Gender either m/f
                             $this->Entities_model->en_search_match(3287, strtolower($locale[0])), //Language
                             $this->Entities_model->en_search_match(3089, strtolower($locale[1])), //Country
                         ) as $ln_parent_entity_id) {

                    //Did we find a relation? Create the link:
                    if ($ln_parent_entity_id > 0) {

                        //Create new link:
                        $this->Links_model->ln_create(array(
                            'ln_type_entity_id' => 4230, //Raw link
                            'ln_creator_entity_id' => $added_en['en']['en_id'], //User gets credit as miner
                            'ln_parent_entity_id' => $ln_parent_entity_id,
                            'ln_child_entity_id' => $added_en['en']['en_id'],
                        ));

                    }
                }
            }

            //Do we have a profile image?
            if(isset($fb_profile['profile_pic'])){
                //Create link to save profile picture:
                $this->Links_model->ln_create(array(
                    'ln_status_entity_id' => 6175, //Link Drafting
                    'ln_type_entity_id' => 4299, //Updated Profile Picture
                    'ln_creator_entity_id' => $added_en['en']['en_id'], //The User who added this
                    'ln_content' => $fb_profile['profile_pic'], //Image to be saved to Mench CDN
                ));
            }
        }


        //Note that new entity link is already logged in the entity creation function
        //Now create more relevant links:

        //Activate Mench Messenger
        $this->Links_model->ln_create(array(
            'ln_parent_entity_id' => 6196, //Mench Messenger
            'ln_type_entity_id' => 4230, //Raw link
            'ln_creator_entity_id' => $added_en['en']['en_id'],
            'ln_child_entity_id' => $added_en['en']['en_id'],
            'ln_external_id' => $psid,
        ));

        //Add them to Users group:
        $this->Links_model->ln_create(array(
            'ln_parent_entity_id' => 4430, //Mench User
            'ln_type_entity_id' => 4230, //Raw link
            'ln_creator_entity_id' => $added_en['en']['en_id'],
            'ln_child_entity_id' => $added_en['en']['en_id'],
        ));

        //Add default Notification Level:
        $this->Links_model->ln_create(array(
            'ln_parent_entity_id' => 4456, //Receive Regular Notifications (User can change later on...)
            'ln_type_entity_id' => 4230, //Raw link
            'ln_creator_entity_id' => $added_en['en']['en_id'],
            'ln_child_entity_id' => $added_en['en']['en_id'],
        ));

        //Have they been referred by someone?
        if(substr_count($quick_reply_payload, 'REFERUSER_') == 1){

            //See what the payload is:
            $append_link_ids = explode('_', one_two_explode('REFERUSER_', '', $quick_reply_payload));
            $referrer_en_id = intval($append_link_ids[0]);
            $in_id = intval($append_link_ids[1]);


            //Validate referer:
            //Fetch and validate entity referrer:
            $referrer_ens = $this->Entities_model->en_fetch(array(
                'en_id' => $referrer_en_id,
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
            ));

            if(count($referrer_ens) > 0){

                //Add them as the child of the referer:
                $this->Links_model->ln_create(array(
                    'ln_type_entity_id' => 4255, //Text link
                    'ln_content' => 'Referrer',
                    'ln_creator_entity_id' => $referrer_en_id,
                    'ln_parent_entity_id' => $referrer_en_id,
                    'ln_child_entity_id' => $added_en['en']['en_id'],
                ));

                //Log referrer link type:
                $this->Links_model->ln_create(array(
                    'ln_type_entity_id' => 7484, //User Referred User
                    'ln_creator_entity_id' => $referrer_en_id,
                    'ln_child_entity_id' => $added_en['en']['en_id'],
                    'ln_child_intent_id' => $in_id,
                ));

            }
        }


        if(!$fetch_result){
            //Let them know to complete their profile:
            $this->Communication_model->dispatch_message(
                'Hi! You can start by completing your profile information so I know who I am speaking to 🤗 /link:Update 👤My Account:https://mench.com/myaccount',
                $added_en['en'],
                true
            );
        }

        //Return entity object:
        return $added_en['en'];

    }


}