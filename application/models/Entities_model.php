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




    function en_create($insert_columns, $external_sync = false, $ln_miner_entity_id = 0)
    {

        //What is required to create a new intent?
        if (detect_missing_columns($insert_columns, array('en_status', 'en_name'))) {
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

            if ($external_sync) {

                //Update Algolia:
                $algolia_sync = update_algolia('en', $insert_columns['en_id']);

                //Log link new entity:
                $this->Links_model->ln_create(array(
                    'ln_miner_entity_id' => ($ln_miner_entity_id > 0 ? $ln_miner_entity_id : $insert_columns['en_id']),
                    'ln_child_entity_id' => $insert_columns['en_id'],
                    'ln_type_entity_id' => 4251, //New Entity Created
                    'ln_metadata' => array(
                        'algolia_sync' => $algolia_sync,
                    ),
                ));

                //Fetch to return the complete entity data:
                $ens = $this->Entities_model->en_fetch(array(
                    'en_id' => $insert_columns['en_id'],
                ));

                return $ens[0];

            } else {

                //Return provided inputs plus the new entity ID:
                return $insert_columns;

            }

        } else {

            //Ooopsi, something went wrong!
            $this->Links_model->ln_create(array(
                'ln_parent_entity_id' => $ln_miner_entity_id,
                'ln_content' => 'en_create() failed to create a new entity',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_metadata' => $insert_columns,
            ));
            return false;

        }
    }

    function en_fetch($match_columns, $join_objects = array(), $limit = 0, $limit_offset = 0, $order_columns = array('en_trust_score' => 'DESC'), $select = '*', $group_by = null)
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

                //ACount children:
                $res[$key]['en__child_count'] = $this->Entities_model->en_child_count($val['en_id']);

            }

            //This will fetch Children up to a maximum of $this->config->item('items_per_page')
            if (in_array('en__children', $join_objects)) {

                $res[$key]['en__children'] = $this->Links_model->ln_fetch(array(
                    'ln_parent_entity_id' => $val['en_id'],
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    'ln_status >=' => 0, //New+
                    'en_status >=' => 0, //New+
                ), array('en_child'), $this->config->item('items_per_page'), 0, array('ln_order' => 'ASC', 'en_trust_score' => 'DESC'));

            }


            //Always fetch entity parents unless explicitly requested not to:
            if (in_array('skip_en__parents', $join_objects)) {

                $res[$key]['en__parents'] = array();

            } else {

                //Fetch parents by default:
                $res[$key]['en__parents'] = $this->Links_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    'ln_child_entity_id' => $val['en_id'], //This child entity
                    'ln_status >=' => 0, //New+
                    'en_status >=' => 0, //New+
                ), array('en_parent'), 0, 0, array('en_trust_score' => 'DESC'));

            }
        }

        return $res;
    }

    function en_update($id, $update_columns, $external_sync = false, $ln_miner_entity_id = 0)
    {

        /*
         *
         * $external_sync helps log a link for the new entity that is about to
         * be created but we yet dont have its entity ID to use in $ln_miner_entity_id!
         *
         * */

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current entity filed values so we can compare later on after we've updated it:
        if($external_sync){
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
        if ($affected_rows > 0 && $external_sync) {

            $fixed_fields = $this->config->item('fixed_fields');

            //Log modification link for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (!($before_data[0][$key] == $value) && !in_array($key, array('en_metadata', 'en_trust_score'))) {



                    //Value has changed, log link:
                    $this->Links_model->ln_create(array(
                        'ln_miner_entity_id' => ($ln_miner_entity_id > 0 ? $ln_miner_entity_id : $id),
                        'ln_type_entity_id' => 4263, //Entity Attribute Modified
                        'ln_child_entity_id' => $id,
                        'ln_content' => echo_clean_db_name($key) . ' changed from "' . ( $key=='en_status' ? $fixed_fields['en_status'][$before_data[0][$key]]['s_name'] : $before_data[0][$key] ) . '" to "' . ( $key=='en_status' ? $fixed_fields['en_status'][$value]['s_name'] : $value ) . '"',
                        'ln_metadata' => array(
                            'en_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));


                }

            }

            //Sync algolia:
            $algolia_sync = update_algolia('en', $id);

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->Links_model->ln_create(array(
                'ln_child_entity_id' => $id,
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'en_update() Failed to update',
                'ln_metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }


    function en_radio_set($en_parent_bucket_id, $set_en_child_id = 0, $en_student_id, $ln_miner_entity_id = 0)
    {

        /*
         * Treats an entity child group as a drop down menu where:
         *
         *  $en_parent_bucket_id is the parent of the drop down
         *  $en_student_id is the student entity ID that one of the children of $en_parent_bucket_id should be assigned (like a drop down)
         *  $set_en_child_id is the new value to be assigned, which could also be null (meaning just remove all current values)
         *
         * This function is helpful to manage things like Student communication levels
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
            'ln_child_entity_id' => $en_student_id,
            'ln_parent_entity_id IN (' . join(',', $children) . ')' => null, //Current children
            'ln_status >=' => 0,
        ), array(), $this->config->item('items_per_page')) as $ln) {

            if (!$already_assigned && $ln['ln_parent_entity_id'] == $set_en_child_id) {
                $already_assigned = true;
            } else {
                //Remove assignment:
                $updated_ln_id = $ln['ln_id'];

                //Do not log update link here as we would log it further below:
                $this->Links_model->ln_update($ln['ln_id'], array(
                    'ln_status' => -1, //Removed
                ));
            }

        }


        //Make sure $set_en_child_id belongs to parent if set (Could be null which means remove all)
        if (!$already_assigned) {
            //Let's go ahead and add desired entity as parent:
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $ln_miner_entity_id,
                'ln_child_entity_id' => $en_student_id,
                'ln_parent_entity_id' => $set_en_child_id,
                'ln_type_entity_id' => 4230, //Raw link
                'ln_parent_link_id' => $updated_ln_id,
            ));
        }

    }

    function en_unlink($en_id, $ln_miner_entity_id = 0, $merger_en_id = 0){

        //Fetch all entity links:
        $adjusted_count = 0;
        foreach(array_merge(
                //Entity references within intent notes:
                    $this->Links_model->ln_fetch(array(
                        'ln_status >=' => 0, //New+
                        'in_status >=' => 0, //New+
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
                        'ln_parent_entity_id' => $en_id,
                    ), array('in_child'), 0, 0, array('ln_order' => 'ASC')),
                    //Entity links:
                    $this->Links_model->ln_fetch(array(
                        'ln_status >=' => 0, //New+
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
                $adjusted_count += $this->Links_model->ln_update($adjust_tr['ln_id'], $updating_fields, $ln_miner_entity_id);

            } else {

                //Remove this link:
                $adjusted_count += $this->Links_model->ln_update($adjust_tr['ln_id'], array(
                    'ln_status' => -1, //Removed
                ), $ln_miner_entity_id);

            }
        }

        return $adjusted_count;
    }

    function en_sync_domain($url, $ln_miner_entity_id = 0, $page_title = null)
    {
        /*
         *
         * Either finds/returns existing domains or adds it
         * to the Domains entity if $ln_miner_entity_id > 0
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
            'en_status >=' => 0, //New+
            'ln_status >=' => 0, //New+
            'ln_type_entity_id' => 4256, //Generic URL (Domain home pages should always be generic, see above for logic)
            'ln_parent_entity_id' => 1326, //Domain Entity
            'ln_content' => $domain_analysis['url_clean_domain'],
        ), array('en_child'));


        //Do we need to create an entity for this domain?
        if (count($domain_links) > 0) {

            $domain_already_existed = 1;
            $en_domain = $domain_links[0];

        } elseif ($ln_miner_entity_id) {

            //Yes, let's add a new entity:
            $added_en = $this->Entities_model->en_verify_create(( $page_title ? $page_title : $domain_analysis['url_domain_name'] ), $ln_miner_entity_id, true, 2, detect_fav_icon($domain_analysis['url_clean_domain']));
            $en_domain = $added_en['en'];

            //And link entity to the domains entity:
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $ln_miner_entity_id,
                'ln_status' => 2, //Published
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

    function en_sync_url($url, $ln_miner_entity_id = 0, $add_to_parent_en_id = 0, $add_to_child_en_id = 0, $page_title = null)
    {

        /*
         *
         * Analyzes a URL to see if it and its domain exists.
         * Input legend:
         *
         * - $url:                  Input URL
         * - $ln_miner_entity_id:       IF > 0 will save URL (if not already there) and give credit to this entity as the miner
         * - $add_to_parent_en_id:  IF > 0 Will also add URL to this parent if present
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
        } elseif (($add_to_parent_en_id > 0 || $add_to_child_en_id > 0) && $ln_miner_entity_id < 1) {
            return array(
                'status' => 0,
                'message' => 'Miner is required to add parent URL',
            );
        }

        //Remember if entity name was passed:
        $name_was_passed = ( $page_title ? true : false );

        //Analyze domain:
        $domain_analysis = analyze_domain($url);

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

        //Only fetch URL content if not a direct file type:
        $url_content = null;
        if(!array_key_exists($ln_type_entity_id, $this->config->item('fb_convert_4537'))){

            //Make CURL call:
            $url_content = @file_get_contents($url);

            //See if we have a canonical metadata on page?
            if(substr_count($url_content,'rel="canonical"') > 0){
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
            $url_identified = substr(md5($url), 0, 8);


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
                    'en_status >=' => 0, //New+
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
        $domain_entity = $this->Entities_model->en_sync_domain($url, $ln_miner_entity_id, ( $domain_analysis['url_is_root'] && $name_was_passed ? $page_title : null ));


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
                'en_status >=' => 0, //New+
                'ln_status >=' => 0, //New+
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Entity URL Links
                'ln_content' => $url,
            ), array('en_child'));


            //Do we need to create an entity for this URL?
            if (count($url_links) > 0) {

                $en_url = $url_links[0];
                $url_already_existed = 1;

            } elseif ($ln_miner_entity_id) {

                //Create a new entity for this URL:
                $added_en = $this->Entities_model->en_verify_create($page_title, $ln_miner_entity_id, true);
                $en_url = $added_en['en'];

                //Always link URL to its parent domain:
                $this->Links_model->ln_create(array(
                    'ln_miner_entity_id' => $ln_miner_entity_id,
                    'ln_status' => 2, //Published
                    'ln_type_entity_id' => $ln_type_entity_id,
                    'ln_parent_entity_id' => $domain_entity['en_domain']['en_id'],
                    'ln_child_entity_id' => $en_url['en_id'],
                    'ln_content' => $url,
                ));

            }

        }


        //Have we been asked to also add URL to another parent or child?
        if (!$url_already_existed && $add_to_parent_en_id) {
            //Link URL to its parent domain:
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $ln_miner_entity_id,
                'ln_status' => 2, //Published
                'ln_type_entity_id' => 4230, //Raw
                'ln_parent_entity_id' => $add_to_parent_en_id,
                'ln_child_entity_id' => $en_url['en_id'],
            ));
        }

        if (!$url_already_existed && $add_to_child_en_id) {
            //Link URL to its parent domain:
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $ln_miner_entity_id,
                'ln_status' => 2, //Published
                'ln_type_entity_id' => 4230, //Raw
                'ln_child_entity_id' => $add_to_child_en_id,
                'ln_parent_entity_id' => $en_url['en_id'],
            ));
        }


        $return_data = array_merge(

            $domain_analysis, //Make domain analysis data available as well...

            array(
                'status' => ($url_already_existed && !$ln_miner_entity_id ? 0 : 1),
                'message' => ($url_already_existed && !$ln_miner_entity_id ? 'URL is already linked to @' . $en_url['en_id'] . ' ' . $en_url['en_name'] : 'Success'),
                'url_already_existed' => $url_already_existed,
                'cleaned_url' => $url,
                'ln_type_entity_id' => $ln_type_entity_id,
                'page_title' => html_entity_decode($page_title, ENT_QUOTES),
                'en_domain' => $domain_entity['en_domain'],
                'en_url' => $en_url,
            )
        );

        //Return results:
        return $return_data;
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
            'ln_status >=' => 0, //Pending or Active
        ), array(), 0);


        if (count($matching_entities) == 1) {

            //Bingo, return result:
            return intval($matching_entities[0]['ln_child_entity_id']);

        } else {

            //Ooooopsi, this value did not exist! Notify the admin so we can look into this:
            $this->Links_model->ln_create(array(
                'ln_content' => 'en_search_match() found [' . count($matching_entities) . '] results as the children of en_id=[' . $en_parent_id . '] that had the value of [' . $value . '].',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_child_entity_id' => $en_parent_id,
            ));

            return 0;
        }
    }

    function en_mass_update($en_id, $action_en_id, $action_command1, $action_command2, $ln_miner_entity_id)
    {

        //Fetch statuses:
        $fixed_fields = $this->config->item('fixed_fields');
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
            'ln_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_child'), 0);


        //Process request:
        foreach ($children as $en) {

            //Logic here must match items in en_mass_actions config variable

            //Take command-specific action:
            if ($action_en_id == 4998) { //Add Prefix String

                $this->Entities_model->en_update($en['en_id'], array(
                    'en_name' => $action_command1 . $en['en_name'],
                ), true, $ln_miner_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 4999) { //Add Postfix String

                $this->Entities_model->en_update($en['en_id'], array(
                    'en_name' => $en['en_name'] . $action_command1,
                ), true, $ln_miner_entity_id);

                $applied_success++;

            } elseif (in_array($action_en_id, array(5981, 5982))) { //Add/Remove parent entity

                //What miner searched for:
                $parent_en_id = intval(one_two_explode('@',' ',$action_command1));

                //See if child entity has searched parent entity:
                $child_parent_ens = $this->Links_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    'ln_child_entity_id' => $en['en_id'], //This child entity
                    'ln_parent_entity_id' => $parent_en_id,
                    'ln_status >=' => 0, //New+
                ));

                if($action_en_id==5981 && count($child_parent_ens)==0){ //Parent Entity Addition

                    //Does not exist, need to be added as parent:
                    $this->Links_model->ln_create(array(
                        'ln_status' => 2, //Published
                        'ln_miner_entity_id' => $ln_miner_entity_id,
                        'ln_type_entity_id' => 4230, //Raw
                        'ln_child_entity_id' => $en['en_id'], //This child entity
                        'ln_parent_entity_id' => $parent_en_id,
                    ));

                    $applied_success++;

                } elseif($action_en_id==5982 && count($child_parent_ens) > 0){ //Parent Entity Removal

                    //Already added as parent so it needs to be removed:
                    foreach($child_parent_ens as $remove_tr){

                        $this->Links_model->ln_update($remove_tr['ln_id'], array(
                            'ln_status' => -1, //Removed
                        ), $ln_miner_entity_id);

                        $applied_success++;
                    }

                }

            } elseif ($action_en_id == 5943) { //Entity Mass Update Entity Icon

                $this->Entities_model->en_update($en['en_id'], array(
                    'en_icon' => $action_command1,
                ), true, $ln_miner_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5000 && substr_count($en['en_name'], $action_command1) > 0) { //Replace Entity Matching String

                //Make sure the SEARCH string exists:
                $this->Entities_model->en_update($en['en_id'], array(
                    'en_name' => str_replace($action_command1, $action_command2, $en['en_name']),
                ), true, $ln_miner_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5001 && substr_count($en['ln_content'], $action_command1) > 0) { //Replace Link Matching String

                $this->Links_model->ln_update($en['ln_id'], array(
                    'ln_content' => str_replace($action_command1, $action_command2, $en['ln_content']),
                ), $ln_miner_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5003 && ($action_command1=='*' || $en['en_status']==$action_command1) && array_key_exists($action_command2, $fixed_fields['en_status'])) { //Update Matching Entity Status

                $this->Entities_model->en_update($en['en_id'], array(
                    'en_status' => $action_command2,
                ), true, $ln_miner_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5865 && ($action_command1=='*' || $en['ln_status']==$action_command1) && array_key_exists($action_command2, $fixed_fields['ln_status'])) { //Update Matching Link Status

                $this->Links_model->ln_update($en['ln_id'], array(
                    'ln_status' => $action_command2,
                ), $ln_miner_entity_id);

                $applied_success++;

            }
        }


        //Log mass entity edit link:
        $this->Links_model->ln_create(array(
            'ln_miner_entity_id' => $ln_miner_entity_id,
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

    function en_child_count($en_id, $min_en_status = 0)
    {

        //Count the active children of entity:
        $en__child_count = 0;

        //Do a child count:
        $child_links = $this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => $en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status >=' => 0, //New+
            'en_status >=' => $min_en_status,
        ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

        if (count($child_links) > 0) {
            $en__child_count = intval($child_links[0]['en__child_count']);
        }

        return $en__child_count;
    }

    function en_authenticate_psid($psid)
    {

        /*
         *
         * Detects the Student entity ID based on the
         * PSID provided by the Facebook Webhook Call.
         * This function returns the Student's entity object $en
         *
         */


        if ($psid < 1) {
            //Ooops, this should never happen:
            $this->Links_model->ln_create(array(
                'ln_content' => 'en_authenticate_psid() got called without a valid Facebook $psid variable',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));
            return false;
        }

        //Try matching Facebook PSID to existing Students:
        $ens = $this->Entities_model->en_fetch(array(
            'en_status >=' => 0, //New+
            'en_psid' => $psid,
        ), array('skip_en__parents'));

        //So, did we find them?
        if (count($ens) > 0) {

            //Student found...
            return $ens[0];

        } else {

            //Student not found, create new Student:
            return $this->Entities_model->en_messenger_add($psid);

        }

    }

    function en_verify_create($en_name, $ln_miner_entity_id = 0, $force_creation = false, $en_status = 0, $en_icon = null, $en_psid = null){

        if(strlen($en_name)<2){
            return array(
                'status' => 0,
                'message' => 'Entity name must be at-least 2 characters long',
            );
        }

        //Check to make sure name is not duplicate:
        $duplicate_name_ens = $this->Entities_model->en_fetch(array(
            'en_status >=' => 0, //New+
            'LOWER(en_name)' => strtolower(trim($en_name)),
        ));
        if(count($duplicate_name_ens) > 0){
            if($force_creation){
                //We're forcing a creation so append a postfix to name to make it unique:
                $en_name = $en_name.' '.rand(100000000, 999999999); //Slim possibility to be duplicate...
            } else {
                //No, return error:
                $fixed_fields = $this->config->item('fixed_fields');
                return array(
                    'status' => 0,
                    'message' => 'Entity name ['.$en_name.'] already in use by entity @'.$duplicate_name_ens[0]['en_id'].' with status ['.$fixed_fields['en_status'][$duplicate_name_ens[0]['en_status']]['s_name'].']',
                );
            }
        }

        //Create entity
        $entity_new = $this->Entities_model->en_create(array(
            'en_name' => trim($en_name),
            'en_icon' => $en_icon,
            'en_psid' => $en_psid,
            'en_status' => $en_status,
        ), true, $ln_miner_entity_id);

        //Return success:
        return array(
            'status' => 1,
            'en' => $entity_new,
        );

    }

    function en_messenger_add($psid)
    {

        /*
         *
         * This function will attempt to create a new Student Entity
         * Using the PSID provided by Facebook Graph API
         *
         * */

        if ($psid < 1) {
            //Ooops, this should never happen:
            $this->Links_model->ln_create(array(
                'ln_content' => 'en_messenger_add() got called without a valid Facebook $psid variable',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));
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

            //Create student entity:
            $added_en = $this->Entities_model->en_verify_create('Student '.rand(100000000, 999999999), 0, true, 2, null, $psid);

            //Completely failed at fetching profile data:
            $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 6389, //Messenger Profile Inaccessible
                'ln_miner_entity_id' => $added_en['en']['en_id'], //Student gets credit as miner
                'ln_content' => 'en_messenger_add() COMPLETE FAIL to fetch messenger profile',
                'ln_metadata' => array(
                    'psid' => $psid,
                    'graph_fetch' => $graph_fetch,
                )
            ));

        } else {

            //We did find the profile, move ahead:
            $fb_profile = $graph_fetch['ln_metadata']['result'];

            //Create student entity with their Facebook Graph name:
            $added_en = $this->Entities_model->en_verify_create($fb_profile['first_name'] . ' ' . $fb_profile['last_name'], 0, true, 2, null, $psid);



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
                            'ln_miner_entity_id' => $added_en['en']['en_id'], //Student gets credit as miner
                            'ln_parent_entity_id' => $ln_parent_entity_id,
                            'ln_child_entity_id' => $added_en['en']['en_id'],
                        ));

                    }
                }

            } else {

                //We failed to fetch full profile data, log details:
                $this->Links_model->ln_create(array(
                    'ln_type_entity_id' => 6389, //Messenger Profile Inaccessible
                    'ln_miner_entity_id' => $added_en['en']['en_id'], //Student gets credit as miner
                    'ln_content' => 'en_messenger_add() PARTIAL FAIL to fetch messenger profile',
                    'ln_metadata' => array(
                        'psid' => $psid,
                        'graph_fetch' => $graph_fetch,
                    )
                ));

            }

            //Do we have a profile image?
            if(isset($fb_profile['profile_pic'])){
                //Create link to save profile picture:
                $this->Links_model->ln_create(array(
                    'ln_status' => 0, //New
                    'ln_type_entity_id' => 4299, //Updated Profile Picture
                    'ln_miner_entity_id' => $added_en['en']['en_id'], //The Student who added this
                    'ln_content' => $fb_profile['profile_pic'], //Image to be saved to Mench CDN
                ));
            }
        }


        //Note that new entity link is already logged in the entity creation function
        //Now create more relevant links:

        //Add default Notification Level:
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_miner_entity_id' => $added_en['en']['en_id'],
            'ln_parent_entity_id' => 4456, //Receive Regular Notifications (Student can change later on...)
            'ln_child_entity_id' => $added_en['en']['en_id'],
        ));

        //Add them to Students group:
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_miner_entity_id' => $added_en['en']['en_id'],
            'ln_parent_entity_id' => 4430, //Mench Student
            'ln_child_entity_id' => $added_en['en']['en_id'],
        ));

        //Add them to People entity:
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_miner_entity_id' => $added_en['en']['en_id'],
            'ln_parent_entity_id' => 1278, //People
            'ln_child_entity_id' => $added_en['en']['en_id'],
        ));


        if(!$fetch_result){
            //Let them know to complete their profile:
            $this->Communication_model->dispatch_message(
                'Hi! You can start by completing your profile information so I know who I am speaking to 🤗 /link:Update 👤My Account:https://mench.com/messenger/myaccount',
                $added_en['en'],
                true
            );
        }

        //Return entity object:
        return $added_en['en'];

    }


}