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





    function activate_session($source, $update_session = false){

        //PROFILE
        $session_data = array(
            'session_profile' => $source,
            'session_parent_ids' => array(),
            'session_superpowers_assigned' => array(),
            'session_superpowers_activated' => array(),
        );

        if(!$update_session){

            //Append stats variables:
            $session_data['session_page_count'] = 0;

            $this->READ_model->create(array(
                'read__source' => $source['source__id'],
                'read__type' => 7564, //PLAYER SIGN
                'read__metadata' => $source,
            ));

        }

        foreach($this->READ_model->fetch(array(
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            'read__down' => $source['source__id'], //This child source
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
        ), array('read__up')) as $source_profile){

            //Push to parent IDs:
            array_push($session_data['session_parent_ids'], intval($source_profile['source__id']));

            if(in_array($source_profile['source__id'], $this->config->item('sources_id_10957'))){

                //It's assigned!
                array_push($session_data['session_superpowers_assigned'], intval($source_profile['source__id']));

                //Was the latest toggle to de-activate? If not, assume active:
                $last_advance_settings = $this->READ_model->fetch(array(
                    'read__source' => $source['source__id'],
                    'read__type' => 5007, //TOGGLE SUPERPOWER
                    'read__up' => $source_profile['source__id'],
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                ), array(), 1); //Fetch the single most recent supoerpower toggle only
                if(!count($last_advance_settings) || !substr_count($last_advance_settings[0]['read__message'] , ' DEACTIVATED')){
                    array_push($session_data['session_superpowers_activated'], intval($source_profile['source__id']));
                }

            }
        }

        //SESSION
        $this->session->set_userdata($session_data);

        return $source;

    }




    function create($add_fields, $external_sync = false, $read__source = 0)
    {

        //What is required to create a new Idea?
        if (detect_missing_columns($add_fields, array('source__status', 'source__title'), $read__source)) {
            return false;
        }

        //Transform text:
        $add_fields['source__title'] = strtoupper($add_fields['source__title']);

        if (isset($add_fields['source__metadata'])) {
            $add_fields['source__metadata'] = serialize($add_fields['source__metadata']);
        } else {
            $add_fields['source__metadata'] = null;
        }

        //Lets now add:
        $this->db->insert('mench_sources', $add_fields);

        //Fetch inserted id:
        if (!isset($add_fields['source__id'])) {
            $add_fields['source__id'] = $this->db->insert_id();
        }

        if ($add_fields['source__id'] > 0) {

            //Log link new source:
            $this->READ_model->create(array(
                'read__source' => ($read__source > 0 ? $read__source : $add_fields['source__id']),
                'read__down' => $add_fields['source__id'],
                'read__type' => 4251, //New Source Created
                'read__message' => $add_fields['source__title'],
            ));

            //Fetch to return the complete source data:
            $sources = $this->SOURCE_model->fetch(array(
                'source__id' => $add_fields['source__id'],
            ));

            if($external_sync){
                //Update Algolia:
                update_algolia(4536, $add_fields['source__id']);
            }

            return $sources[0];

        } else {

            //Ooopsi, something went wrong!
            $this->READ_model->create(array(
                'read__up' => $read__source,
                'read__message' => 'create() failed to create a new source',
                'read__type' => 4246, //Platform Bug Reports
                'read__source' => $read__source,
                'read__metadata' => $add_fields,
            ));
            return false;

        }
    }

    function fetch($match_columns = array(), $limit = 0, $limit_offset = 0, $order_columns = array('source__title' => 'ASC'), $select = '*', $group_by = null)
    {

        //Fetch the target sources:
        $this->db->select($select);
        $this->db->from('mench_sources');
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

    function update($id, $update_columns, $external_sync = false, $read__source = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current source filed values so we can compare later on after we've updated it:
        if($read__source > 0){
            $before_data = $this->SOURCE_model->fetch(array('source__id' => $id));
        }

        //Transform text:
        if(isset($update_columns['source__title'])){
            $update_columns['source__title'] = strtoupper($update_columns['source__title']);
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['source__metadata']) && is_array($update_columns['source__metadata'])){
            $update_columns['source__metadata'] = serialize($update_columns['source__metadata']);
        }

        //Update:
        $this->db->where('source__id', $id);
        $this->db->update('mench_sources', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $read__source > 0) {

            if($external_sync){
                //Sync algolia:
                update_algolia(4536, $id);
            }

            //Log modification link for every field changed:
            foreach($update_columns as $key => $value) {

                if ($before_data[0][$key] == $value){
                    //Nothing changed:
                    continue;
                }

                //FYI: Unlike Ideas, we cannot log parent/child source relations since the child source slot is previously taken...

                if($key=='source__title') {

                    $read__type = 10646; //Player Updated Name
                    $read__message = update_description($before_data[0][$key], $value);

                } elseif($key=='source__status') {

                    if(in_array($value, $this->config->item('sources_id_7358') /* ACTIVE */)){
                        $read__type = 10654; //Source Updated Status
                    } else {
                        $read__type = 6178; //Source Deleted
                    }
                    $sources__6177 = $this->config->item('sources__6177'); //Source Status
                    $read__message = view_db_field($key) . ' updated from [' . $sources__6177[$before_data[0][$key]]['m_name'] . '] to [' . $sources__6177[$value]['m_name'] . ']';

                } elseif($key=='source__icon') {

                    $read__type = 10653; //Player Updated Icon
                    $read__message = view_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }

                //Value has changed, log link:
                $this->READ_model->create(array(
                    'read__source' => ($read__source > 0 ? $read__source : $id),
                    'read__type' => $read__type,
                    'read__down' => $id,
                    'read__message' => $read__message,
                    'read__metadata' => array(
                        'source__id' => $id,
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ),
                ));

            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->READ_model->create(array(
                'read__down' => $id,
                'read__type' => 4246, //Platform Bug Reports
                'read__source' => $read__source,
                'read__message' => 'update() Failed to update',
                'read__metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }


    function radio_set($source_profile_bucket_id, $set_source_child_id, $read__source)
    {

        /*
         * Treats an source child group as a drop down menu where:
         *
         *  $source_profile_bucket_id is the parent of the drop down
         *  $read__source is the user source ID that one of the children of $source_profile_bucket_id should be assigned (like a drop down)
         *  $set_source_child_id is the new value to be assigned, which could also be null (meaning just delete all current values)
         *
         * This function is helpful to manage things like User communication levels
         *
         * */


        //Fetch all the child sources for $source_profile_bucket_id and make sure they match $set_source_child_id
        $children = $this->config->item('sources_id_' . $source_profile_bucket_id);
        if ($source_profile_bucket_id < 1) {
            return false;
        } elseif (!$children) {
            return false;
        } elseif ($set_source_child_id > 0 && !in_array($set_source_child_id, $children)) {
            return false;
        }

        //First delete existing parent/child links for this drop down:
        $previously_assigned = ($set_source_child_id < 1);
        $updated_read__id = 0;
        foreach($this->READ_model->fetch(array(
            'read__down' => $read__source,
            'read__up IN (' . join(',', $children) . ')' => null, //Current children
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        ), array(), config_var(11064)) as $ln) {

            if (!$previously_assigned && $ln['read__up'] == $set_source_child_id) {
                $previously_assigned = true;
            } else {
                //Delete assignment:
                $updated_read__id = $ln['read__id'];

                //Do not log update link here as we would log it further below:
                $this->READ_model->update($ln['read__id'], array(
                    'read__status' => 6173, //Link Deleted
                ), $read__source, 6224 /* User Account Updated */);
            }

        }


        //Make sure $set_source_child_id belongs to parent if set (Could be null which means delete all)
        if (!$previously_assigned) {
            //Let's go ahead and add desired source as parent:
            $this->READ_model->create(array(
                'read__source' => $read__source,
                'read__down' => $read__source,
                'read__up' => $set_source_child_id,
                'read__type' => source_link_type(),
                'read__reference' => $updated_read__id,
            ));
        }

    }

    function unlink($source__id, $read__source = 0, $merger_source__id = 0){

        //Fetch all SOURCE LINKS:
        $adjusted_count = 0;
        foreach(array_merge(
                //Player references within IDEA NOTES:
                    $this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                        'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
                        'read__type IN (' . join(',', $this->config->item('sources_id_4485')) . ')' => null, //IDEA NOTES
                        'read__up' => $source__id,
                    ), array('read__right'), 0, 0, array('read__sort' => 'ASC')),
                    //Player links:
                    $this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                        '(read__down = ' . $source__id . ' OR read__up = ' . $source__id . ')' => null,
                    ), array(), 0)
                ) as $adjust_tr){

            //Merge only if merger ID provided and link not related to original link:
            if($merger_source__id > 0 && $adjust_tr['read__up']!=$merger_source__id && $adjust_tr['read__down']!=$merger_source__id){

                //Update core field:
                $target_field = ($adjust_tr['read__down'] == $source__id ? 'read__down' : 'read__up');
                $updating_fields = array(
                    $target_field => $merger_source__id,
                );

                //Also update possible source references within IDEA NOTES content:
                if(substr_count($adjust_tr['read__message'], '@'.$adjust_tr[$target_field]) == 1){
                    $updating_fields['read__message'] = str_replace('@'.$adjust_tr[$target_field],'@'.$merger_source__id, $adjust_tr['read__message']);
                }

                //Update Link:
                $adjusted_count += $this->READ_model->update($adjust_tr['read__id'], $updating_fields, $read__source, 10689 /* Player Link Merged */);

            } else {

                //Delete this link:
                $adjusted_count += $this->READ_model->update($adjust_tr['read__id'], array(
                    'read__status' => 6173, //Link Deleted
                ), $read__source, 10673 /* Player Link Unpublished */);

            }
        }

        return $adjusted_count;
    }

    function assign_session_player($source__id){

        $session_source = superpower_assigned();
        if(!$session_source){
            return false;
        }

        //Assign to Creator:
        $this->READ_model->create(array(
            'read__type' => source_link_type(),
            'read__source' => $session_source['source__id'],
            'read__up' => $session_source['source__id'],
            'read__down' => $source__id,
        ));

        //Review source later:
        if(!superpower_assigned(10967)){

            //Add Pending Review:
            $this->READ_model->create(array(
                'read__type' => source_link_type(),
                'read__source' => $session_source['source__id'],
                'read__up' => 12775, //PENDING REVIEW
                'read__down' => $source__id,
            ));

            //SOURCE PENDING MODERATION TYPE:
            $this->READ_model->create(array(
                'read__type' => 7504, //SOURCE PENDING MODERATION
                'read__source' => $session_source['source__id'],
                'read__up' => 12775, //PENDING REVIEW
                'read__down' => $source__id,
            ));

        }

    }

    function domain($url, $read__source = 0, $page_title = null)
    {
        /*
         *
         * Either finds/returns existing domains or adds it
         * to the Domains source if $read__source > 0
         *
         * */

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        }


        //Analyze domain:
        $url_analysis = analyze_domain($url);
        $domaidea_previously_existed = 0; //Assume false
        $source_domain = false; //Have an empty placeholder:


        //Check to see if we have domain linked previously:
        $url_links = $this->READ_model->fetch(array(
            'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'read__type' => 4256, //Generic URL (Domain home pages should always be generic, see above for logic)
            'read__up' => 1326, //Domain Player
            'read__message' => $url_analysis['url_clean_domain'],
        ), array('read__down'));


        //Do we need to create an source for this domain?
        if (count($url_links) > 0) {

            $domaidea_previously_existed = 1;
            $source_domain = $url_links[0];

        } elseif ($read__source) {

            //Yes, let's add a new source:
            $added_source = $this->SOURCE_model->verify_create(( $page_title ? $page_title : $url_analysis['url_domain'] ), $read__source, 6181, detect_fav_icon($url_analysis['url_clean_domain']));
            $source_domain = $added_source['new_source'];

            //And link source to the domains source:
            $this->READ_model->create(array(
                'read__source' => $read__source,
                'read__type' => 4256, //Generic URL (Domains are always generic)
                'read__up' => 1326, //Domain Player
                'read__down' => $source_domain['source__id'],
                'read__message' => $url_analysis['url_clean_domain'],
            ));

        }


        //Return data:
        return array_merge( $url_analysis , array(
            'status' => 1,
            'message' => 'Success',
            'domaidea_previously_existed' => $domaidea_previously_existed,
            'source_domain' => $source_domain,
        ));

    }

    function match_read_status($read__source, $query= array()){

        //STATS
        $stats = array(
            'read__type' => 4251, //Play Created
            'scanned' => 0,
            'missing_creation_fix' => 0,
            'status_sync' => 0,
        );

        //SOURCE
        $status_converter = array(
            12563 => 12399, //SOURCE FEATURED => READ FEATURED
            6181 => 6176, //SOURCE PUBLISH => READ PUBLISH
            6180 => 6175, //SOURCE DRAFT => READ DRAFT
            6178 => 6173, //SOURCE DELETE => READ DELETE
        );
        foreach($this->SOURCE_model->fetch($query) as $source){

            $stats['scanned']++;

            //Find creation read:
            $reads = $this->READ_model->fetch(array(
                'read__type' => $stats['read__type'],
                'read__down' => $source['source__id'],
            ));

            if(!count($reads)){

                $stats['missing_creation_fix']++;

                $this->READ_model->create(array(
                    'read__source' => $read__source,
                    'read__down' => $source['source__id'],
                    'read__message' => $source['source__title'],
                    'read__type' => $stats['read__type'],
                    'read__status' => $status_converter[$source['source__status']],
                ));

            } elseif($reads[0]['read__status'] != $status_converter[$source['source__status']]){

                $stats['status_sync']++;
                $this->READ_model->update($reads[0]['read__id'], array(
                    'read__status' => $status_converter[$source['source__status']],
                ));

            }

        }

        return $stats;
    }




    function metadat_experts($source, $level = 1){

        //Goes through $max_search_levels of sources to find expert channels, people & organizations
        $max_search_levels = 3;
        $metadata_this = array(
            '__idea___experts' => array(),
            '__idea___content' => array(),
        );

        //SOURCE PROFILE
        foreach($this->READ_model->fetch(array(
            'read__down' => $source['source__id'],
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')).')' => null, //SOURCE LINKS
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
        ), array('read__up'), 0) as $source__profile){

            if(in_array($source__profile['source__id'], $this->config->item('sources_id_3000'))){
                //CONTENT CHANNELS
                $source['read__message'] = $source__profile['read__message']; //Update Description
                if (!isset($metadata_this['__idea___content'][$source['source__id']])) {
                    $metadata_this['__idea___content'][$source['source__id']] = $source;
                }
            } elseif(in_array($source__profile['source__id'], $this->config->item('sources_id_12864'))) {
                //EXPERT PEOPLE/ORGANIZATIONS
                $source['read__message'] = $source__profile['read__message']; //Update Description
                if (!isset($metadata_this['__idea___experts'][$source['source__id']])) {
                    $metadata_this['__idea___experts'][$source['source__id']] = $source;
                }
            }

            //Go another level?
            if($level < $max_search_levels){

                $metadata_recursion = $this->SOURCE_model->metadat_experts($source__profile, ($level + 1));

                //CONTENT CHANNELS
                foreach($metadata_recursion['__idea___content'] as $source__id => $source_content) {
                    if (!isset($metadata_this['__idea___content'][$source__id])) {
                        $metadata_this['__idea___content'][$source__id] = $source_content;
                    }
                }

                //EXPERT PEOPLE/ORGANIZATIONS
                foreach($metadata_recursion['__idea___experts'] as $source__id => $source_expert) {
                    if (!isset($metadata_this['__idea___experts'][$source__id])) {
                        $metadata_this['__idea___experts'][$source__id] = $source_expert;
                    }
                }
            }
        }

        return $metadata_this;
    }



    function url($url, $read__source = 0, $add_to_child_source__id = 0, $page_title = null)
    {

        /*
         *
         * Analyzes a URL to see if it and its domain exists.
         * Input legend:
         *
         * - $url:                  Input URL
         * - $read__source:       IF > 0 will save URL (if not previously there) and give credit to this source as the player
         * - $add_to_child_source__id:   IF > 0 Will also add URL to this child if present
         * - $page_title:           If set it would override the source title that is auto generated (Used in Add Source Wizard to enable players to edit auto generated title)
         *
         * */


        //Validate URL:
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        } elseif ($add_to_child_source__id > 0 && $read__source < 1) {
            return array(
                'status' => 0,
                'message' => 'Parent source is required to add a parent URL',
            );
        }

        //Remember if source name was passed:
        $name_was_passed = ( $page_title ? true : false );
        $sources__4537 = $this->config->item('sources__4537');
        $sources__4592 = $this->config->item('sources__4592');

        //Initially assume Generic URL unless we can prove otherwise:
        $read__type = 4256; //Generic URL

        //We'll check to see if URL previously existed:
        $url_previously_existed = 0;

        //Start with null and see if we can find/add:
        $source_url = null;

        //Analyze domain:
        $url_analysis = analyze_domain($url);

        //Now let's analyze further based on type:
        if ($url_analysis['url_is_root']) {

            //Update URL to keep synced:
            $url = $url_analysis['url_clean_domain'];

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
            $embed_code = view_url_embed($url, null, true);

            if ($embed_code['status']) {

                //URL Was detected as an embed URL:
                $read__type = 4257;
                $url = $embed_code['clean_url'];

            } elseif ($url_analysis['url_file_extension'] && is_https_url($url)) {

                $detected_extension = false;
                foreach($this->config->item('sources__11080') as $source__id => $m){
                    if(in_array($url_analysis['url_file_extension'], explode('|' , $m['m_desc']))){
                        $read__type = $source__id;
                        $detected_extension = true;
                        break;
                    }
                }

                if(!$detected_extension){
                    //Log error to notify admin:
                    $this->READ_model->create(array(
                        'read__message' => 'source_url() detected unknown file extension ['.$url_analysis['url_file_extension'].'] that needs to be added to @11080',
                        'read__type' => 4246, //Platform Bug Reports
                        'read__up' => 11080,
                        'read__metadata' => $url_analysis,
                    ));
                }
            }
        }



        //Update Name:
        if (!$name_was_passed) {

            //Only fetch URL content in certain situations:
            $url_content = ( in_array($read__type, $this->config->item('sources_id_11059')) /* not a direct file type */ ? null : @file_get_contents($url) );
            $page_title = source__title_validate(( $url_content ? one_two_explode('>', '', one_two_explode('<title', '</title', $url_content)) : $page_title ), $read__type);

        }


        //Fetch/Create domain source:
        $url_source = $this->SOURCE_model->domain($url, $read__source, ( $url_analysis['url_is_root'] && $name_was_passed ? $page_title : null ));
        if(!$url_source['status']){
            //We had an issue:
            return $url_source;
        }


        //Was this not a root domain? If so, also check to see if URL exists:
        if ($url_analysis['url_is_root']) {

            //URL is the domain in this case:
            $source_url = $url_source['source_domain'];

            //IF the URL exists since the domain existed and the URL is the domain!
            if ($url_source['domaidea_previously_existed']) {
                $url_previously_existed = 1;
            }

        } else {

            //Check to see if URL previously exists:
            $url_links = $this->READ_model->fetch(array(
                'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                'read__type IN (' . join(',', $this->config->item('sources_id_4537')) . ')' => null, //Player URL Links
                'read__message' => $url,
            ), array('read__down'));


            //Do we need to create an source for this URL?
            if (count($url_links) > 0) {

                //Nope, source previously exists:
                $source_url = $url_links[0];
                $url_previously_existed = 1;

            } elseif($read__source) {

                if(!$page_title){
                    //Assign a generic source name:
                    $page_title = $sources__4592[$read__type]['m_name'].' '.substr(md5($url), 0, 8);
                }

                //Prefix type in name:
                $page_title = $page_title;

                //Create a new source for this URL ONLY If player source is provided...
                $added_source = $this->SOURCE_model->verify_create($page_title, $read__source, 6181, $sources__4592[$read__type]['m_icon']);
                if($added_source['status']){

                    //All good:
                    $source_url = $added_source['new_source'];

                    //Always link URL to its parent domain:
                    $this->READ_model->create(array(
                        'read__source' => $read__source,
                        'read__type' => $read__type,
                        'read__up' => $url_source['source_domain']['source__id'],
                        'read__down' => $source_url['source__id'],
                        'read__message' => $url,
                    ));

                    //Assign to Player:
                    $this->SOURCE_model->assign_session_player($source_url['source__id']);

                    //Update Search Index:
                    update_algolia(4536, $source_url['source__id']);

                } else {

                    //Log error:
                    $this->READ_model->create(array(
                        'read__message' => 'source_url['.$url.'] FAILED to source_verify_create['.$page_title.'] with message: '.$added_source['message'],
                        'read__type' => 4246, //Platform Bug Reports
                        'read__source' => $read__source,
                        'read__up' => $url_source['source_domain']['source__id'],
                        'read__metadata' => array(
                            'url' => $url,
                            'read__source' => $read__source,
                            'add_to_child_source__id' => $add_to_child_source__id,
                            'page_title' => $page_title,
                        ),
                    ));

                }

            } else {
                //URL not found and no player source provided to create the URL:
                $source_url = array();
            }
        }


        //Have we been asked to also add URL to another parent or child?
        if(!$url_previously_existed && $add_to_child_source__id){
            //Link URL to its parent domain?
            $this->READ_model->create(array(
                'read__source' => $read__source,
                'read__type' => source_link_type(),
                'read__up' => $source_url['source__id'],
                'read__down' => $add_to_child_source__id,
            ));
        }


        //Return results:
        return array_merge(

            $url_analysis, //Make domain analysis data available as well...

            array(
                'status' => ($url_previously_existed && !$read__source ? 0 : 1),
                'message' => ($url_previously_existed && !$read__source ? 'URL already belongs to [' . $source_url['source__title'].'] with source ID @' . $source_url['source__id'] : 'Success'),
                'url_previously_existed' => $url_previously_existed,
                'clean_url' => $url,
                'read__type' => $read__type,
                'page_title' => html_entity_decode($page_title, ENT_QUOTES),
                'source_domain' => $url_source['source_domain'],
                'source_url' => $source_url,
            )
        );
    }

    function mass_update($source__id, $action_source__id, $action_command1, $action_command2, $read__source)
    {

        //Alert: Has a twin function called idea_mass_update()

        boost_power();

        $is_valid_icon = is_valid_icon($action_command1);

        if(!in_array($action_source__id, $this->config->item('sources_id_4997'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif($action_source__id != 5943 && $action_source__id != 12318 && strlen(trim($action_command1)) < 1){

            return array(
                'status' => 0,
                'message' => 'Missing primary command',
            );

        } elseif(in_array($action_source__id, array(5943,12318)) && !$is_valid_icon['status']){

            return array(
                'status' => 0,
                'message' => $is_valid_icon['message'],
            );

        } elseif(in_array($action_source__id, array(5981, 5982, 12928, 12930, 11956)) && !is_valid_source_string($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @123 Source Name',
            );

        } elseif($action_source__id==11956 && !is_valid_source_string($action_command2)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @123 Source Name',
            );

        }





        //Basic input validation done, let's continue...


        //Fetch all children:
        $applied_success = 0; //To be populated...
        $children = $this->READ_model->fetch(array(
            'read__up' => $source__id,
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
        ), array('read__down'), 0);


        //Process request:
        foreach($children as $source) {

            //Logic here must match items in source_mass_actions config variable

            //Take command-specific action:
            if ($action_source__id == 4998) { //Add Prefix String

                $this->SOURCE_model->update($source['source__id'], array(
                    'source__title' => $action_command1 . $source['source__title'],
                ), true, $read__source);

                $applied_success++;

            } elseif ($action_source__id == 4999) { //Add Postfix String

                $this->SOURCE_model->update($source['source__id'], array(
                    'source__title' => $source['source__title'] . $action_command1,
                ), true, $read__source);

                $applied_success++;

            } elseif (in_array($action_source__id, array(5981, 5982, 12928, 12930, 11956))) { //Add/Delete parent source

                //What player searched for:
                $parent_source__id = intval(one_two_explode('@',' ',$action_command1));

                //See if child source has searched parent source:
                $child_parent_sources = $this->READ_model->fetch(array(
                    'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                    'read__down' => $source['source__id'], //This child source
                    'read__up' => $parent_source__id,
                    'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                ));

                if(($action_source__id==5981 && count($child_parent_sources)==0) || ($action_source__id==12928 && view_coins_count_source(0,$source['source__id'],true) > 0) || ($action_source__id==12930 && !view_coins_count_source(0,$source['source__id'],true))){

                    //Parent Player Addition
                    $this->READ_model->create(array(
                        'read__source' => $read__source,
                        'read__type' => source_link_type(),
                        'read__down' => $source['source__id'], //This child source
                        'read__up' => $parent_source__id,
                    ));

                    $applied_success++;

                } elseif(in_array($action_source__id, array(5982, 11956)) && count($child_parent_sources) > 0){

                    if($action_source__id==5982){

                        //Parent Player Removal
                        foreach($child_parent_sources as $delete_tr){

                            $this->READ_model->update($delete_tr['read__id'], array(
                                'read__status' => 6173, //Link Deleted
                            ), $read__source, 10673 /* Player Link Unpublished  */);

                            $applied_success++;
                        }

                    } elseif($action_source__id==11956) {

                        $parent_new_source__id = intval(one_two_explode('@',' ',$action_command2));

                        //Add as a parent because it meets the condition
                        $this->READ_model->create(array(
                            'read__source' => $read__source,
                            'read__type' => source_link_type(),
                            'read__down' => $source['source__id'], //This child source
                            'read__up' => $parent_new_source__id,
                        ));

                        $applied_success++;

                    }

                }

            } elseif ($action_source__id == 5943) { //Player Mass Update Player Icon

                $this->SOURCE_model->update($source['source__id'], array(
                    'source__icon' => $action_command1,
                ), true, $read__source);

                $applied_success++;

            } elseif ($action_source__id == 12318 && !strlen($source['source__icon'])) { //Player Mass Update Player Icon

                $this->SOURCE_model->update($source['source__id'], array(
                    'source__icon' => $action_command1,
                ), true, $read__source);

                $applied_success++;

            } elseif ($action_source__id == 5000 && substr_count($source['source__title'], strtoupper($action_command1)) > 0) { //Replace Player Matching Name

                $this->SOURCE_model->update($source['source__id'], array(
                    'source__title' => str_replace(strtoupper($action_command1), strtoupper($action_command2), $source['source__title']),
                ), true, $read__source);

                $applied_success++;

            } elseif ($action_source__id == 10625 && substr_count($source['source__icon'], $action_command1) > 0) { //Replace Player Matching Icon

                $this->SOURCE_model->update($source['source__id'], array(
                    'source__icon' => str_replace($action_command1, $action_command2, $source['source__icon']),
                ), true, $read__source);

                $applied_success++;

            } elseif ($action_source__id == 5001 && substr_count($source['read__message'], $action_command1) > 0) { //Replace Link Matching String

                $this->READ_model->update($source['read__id'], array(
                    'read__message' => str_replace($action_command1, $action_command2, $source['read__message']),
                ), $read__source, 10657 /* Player Link Updated Content  */);

                $applied_success++;

            } elseif ($action_source__id == 5003 && ($action_command1=='*' || $source['source__status']==$action_command1) && in_array($action_command2, $this->config->item('sources_id_6177'))) {

                //Being deleted? Unlink as well if that's the case:
                if(!in_array($action_command2, $this->config->item('sources_id_7358'))){
                    $this->SOURCE_model->unlink($source['source__id'], $read__source);
                }

                //Update Matching Player Status:
                $this->SOURCE_model->update($source['source__id'], array(
                    'source__status' => $action_command2,
                ), true, $read__source);

                $applied_success++;

            } elseif ($action_source__id == 5865 && ($action_command1=='*' || $source['read__status']==$action_command1) && in_array($action_command2, $this->config->item('sources_id_6186') /* Read Status */)) { //Update Matching Read Status

                $this->READ_model->update($source['read__id'], array(
                    'read__status' => $action_command2,
                ), $read__source, ( in_array($action_command2, $this->config->item('sources_id_7360') /* ACTIVE */) ? 10656 /* Player Link Updated Status */ : 10673 /* Player Link Unpublished */ ));

                $applied_success++;

            }
        }


        //Log mass source edit link:
        $this->READ_model->create(array(
            'read__source' => $read__source,
            'read__type' => $action_source__id,
            'read__down' => $source__id,
            'read__metadata' => array(
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

    function child_count($source__id, $source_statuses)
    {

        //Count the active children of source:
        $source__portfolio_count = 0;

        //Do a child count:
        $source__portfolio_count = $this->READ_model->fetch(array(
            'read__up' => $source__id,
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'source__status IN (' . join(',', $source_statuses) . ')' => null,
        ), array('read__down'), 0, 0, array(), 'COUNT(source__id) as totals');

        if (count($source__portfolio_count) > 0) {
            $source__portfolio_count = intval($source__portfolio_count[0]['totals']);
        }

        return $source__portfolio_count;
    }


    function verify_create($source__title, $read__source = 0, $source__status = 6181 /* SOURCE PUBLISHED */, $source__icon = null){

        //If PSID exists, make sure it's not a duplicate:
        if(!in_array($source__status, $this->config->item('sources_id_6177'))){
            //Invalid Status ID
            return array(
                'status' => 0,
                'message' => 'Invalid Source Status',
            );
        }

        //Not found, so we need to create, and need a name by now:
        if(strlen($source__title)<2){
            return array(
                'status' => 0,
                'message' => 'Source name must be at-least 2 characters long',
            );
        }


        //Check to make sure name is not duplicate:
        $duplicate_sources = $this->SOURCE_model->fetch(array(
            'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
            'LOWER(source__title)' => strtolower(trim($source__title)),
        ));


        //Create source
        $focus_source = $this->SOURCE_model->create(array(
            'source__title' => trim($source__title),
            'source__icon' => $source__icon,
            'source__status' => $source__status,
        ), true, $read__source);


        //Return success:
        return array(
            'status' => 1,
            'new_source' => $focus_source,
        );

    }

}