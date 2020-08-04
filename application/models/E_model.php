<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class E_model extends CI_Model
{

    /*
     *
     * User related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }





    function activate_session($e, $update_session = false){

        //PROFILE
        $session_data = array(
            'session_profile' => $e,
            'session_var_13491' => 13493, //Medium
            'session_parent_ids' => array(),
            'session_superpowers_assigned' => array(),
            'session_superpowers_activated' => array(),
        );

        if(!$update_session){

            //Append stats variables:
            $session_data['session_page_count'] = 0;

            $this->X_model->create(array(
                'x__source' => $e['e__id'],
                'x__type' => 7564, //USER SIGN
                'x__metadata' => $e,
            ));

        }

        //PROFILE
        foreach($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__down' => $e['e__id'], //This child source
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        ), array('x__up')) as $e_profile){

            //FONT SIZE?
            if(in_array($e_profile['e__id'], $this->config->item('n___13491'))){
                $session_data['session_var_13491'] = intval($e_profile['e__id']);
            }

            //Push to parent IDs:
            array_push($session_data['session_parent_ids'], intval($e_profile['e__id']));

            if(in_array($e_profile['e__id'], $this->config->item('n___10957'))){

                //It's assigned!
                array_push($session_data['session_superpowers_assigned'], intval($e_profile['e__id']));

                //Was the latest toggle to de-activate? If not, assume active:
                $last_advance_settings = $this->X_model->fetch(array(
                    'x__source' => $e['e__id'],
                    'x__type' => 5007, //TOGGLE SUPERPOWER
                    'x__up' => $e_profile['e__id'],
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array(), 1); //Fetch the single most recent supoerpower toggle only
                if(!count($last_advance_settings) || !substr_count($last_advance_settings[0]['x__message'] , ' DEACTIVATED')){
                    array_push($session_data['session_superpowers_activated'], intval($e_profile['e__id']));
                }

            }
        }

        //SESSION
        $this->session->set_userdata($session_data);

        return $e;

    }




    function create($add_fields, $external_sync = false, $x__source = 0)
    {

        //What is required to create a new Idea?
        if (detect_missing_columns($add_fields, array('e__status', 'e__title'), $x__source)) {
            return false;
        }

        //Transform text:
        $add_fields['e__title'] = strtoupper($add_fields['e__title']);

        if (isset($add_fields['e__metadata'])) {
            $add_fields['e__metadata'] = serialize($add_fields['e__metadata']);
        } else {
            $add_fields['e__metadata'] = null;
        }

        //Lets now add:
        $this->db->insert('mench__e', $add_fields);

        //Fetch inserted id:
        if (!isset($add_fields['e__id'])) {
            $add_fields['e__id'] = $this->db->insert_id();
        }

        if ($add_fields['e__id'] > 0) {

            //Log transaction new source:
            $this->X_model->create(array(
                'x__source' => ($x__source > 0 ? $x__source : $add_fields['e__id']),
                'x__down' => $add_fields['e__id'],
                'x__type' => 4251, //New Source Created
                'x__message' => $add_fields['e__title'],
            ));

            //Fetch to return the complete source data:
            $es = $this->E_model->fetch(array(
                'e__id' => $add_fields['e__id'],
            ));

            if($external_sync){
                //Update Algolia:
                update_algolia(12274, $add_fields['e__id']);
            }

            return $es[0];

        } else {

            //Ooopsi, something went wrong!
            $this->X_model->create(array(
                'x__up' => $x__source,
                'x__message' => 'create() failed to create a new source',
                'x__type' => 4246, //Platform Bug Reports
                'x__source' => $x__source,
                'x__metadata' => $add_fields,
            ));
            return false;

        }
    }

    function fetch($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array('e__title' => 'ASC'), $select = '*', $group_by = null)
    {

        //Fetch the target sources:
        $this->db->select($select);
        $this->db->from('mench__e');
        foreach($query_filters as $key => $value) {
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

    function update($id, $update_columns, $external_sync = false, $x__source = 0)
    {

        $id = intval($id);
        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current source filed values so we can compare later on after we've updated it:
        if($x__source > 0){
            $before_data = $this->E_model->fetch(array('e__id' => $id));
        }

        //Transform text:
        if(isset($update_columns['e__title'])){
            $update_columns['e__title'] = strtoupper($update_columns['e__title']);
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['e__metadata']) && is_array($update_columns['e__metadata'])){
            $update_columns['e__metadata'] = serialize($update_columns['e__metadata']);
        }

        //Update:
        $this->db->where('e__id', $id);
        $this->db->update('mench__e', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $x__source > 0) {

            if($external_sync){
                //Sync algolia:
                update_algolia(12274, $id);
            }

            //Log modification transaction for every field changed:
            foreach($update_columns as $key => $value) {

                if ($before_data[0][$key] == $value){
                    //Nothing changed:
                    continue;
                }

                //FYI: Unlike Ideas, we cannot log parent/child source relations since the child source slot is previously taken...

                if($key=='e__title') {

                    $x__type = 10646; //User Updated Name
                    $x__message = update_description($before_data[0][$key], $value);

                } elseif($key=='e__status') {

                    if(in_array($value, $this->config->item('n___7358') /* ACTIVE */)){
                        $x__type = 10654; //Source Updated Status
                    } else {
                        $x__type = 6178; //Source Deleted
                    }
                    $e___6177 = $this->config->item('e___6177'); //Source Status
                    $x__message = view_db_field($key) . ' updated from [' . $e___6177[$before_data[0][$key]]['m_title'] . '] to [' . $e___6177[$value]['m_title'] . ']';

                } elseif($key=='e__icon') {

                    $x__type = 10653; //User Updated Icon
                    $x__message = view_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }

                //Value has changed, log transaction:
                $this->X_model->create(array(
                    'x__source' => ($x__source > 0 ? $x__source : $id),
                    'x__type' => $x__type,
                    'x__down' => $id,
                    'x__message' => $x__message,
                    'x__metadata' => array(
                        'e__id' => $id,
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ),
                ));

            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->X_model->create(array(
                'x__down' => $id,
                'x__type' => 4246, //Platform Bug Reports
                'x__source' => $x__source,
                'x__message' => 'update() Failed to update',
                'x__metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }


    function radio_set($e_profile_bucket_id, $set_e_child_id, $x__source)
    {

        /*
         * Treats an source child group as a drop down menu where:
         *
         *  $e_profile_bucket_id is the parent of the drop down
         *  $x__source is the user source ID that one of the children of $e_profile_bucket_id should be assigned (like a drop down)
         *  $set_e_child_id is the new value to be assigned, which could also be null (meaning just delete all current values)
         *
         * This function is helpful to manage things like User communication levels
         *
         * */


        //Fetch all the child sources for $e_profile_bucket_id and make sure they match $set_e_child_id
        $children = $this->config->item('n___' . $e_profile_bucket_id);
        if ($e_profile_bucket_id < 1) {
            return false;
        } elseif (!$children) {
            return false;
        } elseif ($set_e_child_id > 0 && !in_array($set_e_child_id, $children)) {
            return false;
        }

        //First delete existing parent/child transactions for this drop down:
        $previously_assigned = ($set_e_child_id < 1);
        $updated_x__id = 0;
        foreach($this->X_model->fetch(array(
            'x__down' => $x__source,
            'x__up IN (' . join(',', $children) . ')' => null, //Current children
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), config_var(11064)) as $x) {

            if (!$previously_assigned && $x['x__up'] == $set_e_child_id) {
                $previously_assigned = true;
            } else {
                //Delete assignment:
                $updated_x__id = $x['x__id'];

                //Do not log update transaction here as we would log it further below:
                $this->X_model->update($x['x__id'], array(
                    'x__status' => 6173, //Transaction Deleted
                ), $x__source, 6224 /* User Account Updated */);
            }

        }


        //Make sure $set_e_child_id belongs to parent if set (Could be null which means delete all)
        if (!$previously_assigned) {
            //Let's go ahead and add desired source as parent:
            $this->X_model->create(array(
                'x__source' => $x__source,
                'x__down' => $x__source,
                'x__up' => $set_e_child_id,
                'x__type' => e_x__type(),
                'x__reference' => $updated_x__id,
            ));
        }

    }

    function remove($e__id, $x__source = 0){

        //Fetch all SOURCE LINKS:
        $adjusted_count = 0;
        foreach(array_merge(
                //User references within IDEA NOTES:
                    $this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                        'x__type IN (' . join(',', $this->config->item('n___4485')) . ')' => null, //IDEA NOTES
                        'x__up' => $e__id,
                    ), array('x__right'), 0, 0, array('x__sort' => 'ASC')),
                    //User transactions:
                    $this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                        '(x__down = ' . $e__id . ' OR x__up = ' . $e__id . ')' => null,
                    ), array(), 0)
                ) as $adjust_tr){

            //Delete this transaction:
            $adjusted_count += $this->X_model->update($adjust_tr['x__id'], array(
                'x__status' => 6173, //Transaction Deleted
            ), $x__source, 10673 /* User Transaction Unpublished */);

        }

        return $adjusted_count;
    }

    function create_session($e__id){

        $user_e = superpower_assigned();
        if(!$user_e){
            return false;
        }

        //Assign to Creator:
        $this->X_model->create(array(
            'x__type' => e_x__type(),
            'x__source' => $user_e['e__id'],
            'x__up' => $user_e['e__id'],
            'x__down' => $e__id,
        ));

        //Review source later:
        if(!superpower_assigned(13422)){

            //Add Pending Review:
            $this->X_model->create(array(
                'x__type' => e_x__type(),
                'x__source' => $user_e['e__id'],
                'x__up' => 12775, //PENDING REVIEW
                'x__down' => $e__id,
            ));

            //SOURCE PENDING MODERATION TYPE:
            $this->X_model->create(array(
                'x__type' => 7504, //SOURCE PENDING MODERATION
                'x__source' => $user_e['e__id'],
                'x__up' => 12775, //PENDING REVIEW
                'x__down' => $e__id,
            ));

        }

    }

    function domain($url, $x__source = 0, $page_title = null)
    {
        /*
         *
         * Either finds/returns existing domains or adds it
         * to the Domains source if $x__source > 0
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
        $domai_previously_existed = 0; //Assume false
        $e_domain = false; //Have an empty placeholder:


        //Check to see if we have domain:
        $url_x = $this->X_model->fetch(array(
            'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => 4256, //Generic URL (Domain home pages should always be generic, see above for logic)
            'x__up' => 1326, //Domain User
            'x__message' => $url_analysis['url_clean_domain'],
        ), array('x__down'));


        //Do we need to create an source for this domain?
        if (count($url_x) > 0) {

            $domai_previously_existed = 1;
            $e_domain = $url_x[0];

        } elseif ($x__source) {

            //Yes, let's add a new source:
            $added_e = $this->E_model->verify_create(( $page_title ? $page_title : $url_analysis['url_domain'] ), $x__source, 6181, detect_fav_icon($url_analysis['url_clean_domain']));
            $e_domain = $added_e['new_e'];

            //And transaction source to the domains source:
            $this->X_model->create(array(
                'x__source' => $x__source,
                'x__type' => 4256, //Generic URL (Domains are always generic)
                'x__up' => 1326, //Domain User
                'x__down' => $e_domain['e__id'],
                'x__message' => $url_analysis['url_clean_domain'],
            ));

        }


        //Return data:
        return array_merge( $url_analysis , array(
            'status' => 1,
            'message' => 'Success',
            'domai_previously_existed' => $domai_previously_existed,
            'e_domain' => $e_domain,
        ));

    }

    function match_x_status($x__source, $query= array()){

        //STATS
        $stats = array(
            'x__type' => 4251, //Play Created
            'scanned' => 0,
            'missing_creation_fix' => 0,
            'status_sync' => 0,
        );

        //SOURCE
        $status_converter = array(
            12563 => 12399, //SOURCE FEATURED => DISCOVER FEATURED
            6181 => 6176, //SOURCE PUBLISH => DISCOVER PUBLISH
            6180 => 6175, //SOURCE DRAFT => DISCOVER DRAFT
            6178 => 6173, //SOURCE DELETE => DISCOVER DELETE
        );
        foreach($this->E_model->fetch($query) as $e){

            $stats['scanned']++;

            //Find creation discover:
            $x = $this->X_model->fetch(array(
                'x__type' => $stats['x__type'],
                'x__down' => $e['e__id'],
            ));

            if(!count($x)){

                $stats['missing_creation_fix']++;

                $this->X_model->create(array(
                    'x__source' => $x__source,
                    'x__down' => $e['e__id'],
                    'x__message' => $e['e__title'],
                    'x__type' => $stats['x__type'],
                    'x__status' => $status_converter[$e['e__status']],
                ));

            } elseif($x[0]['x__status'] != $status_converter[$e['e__status']]){

                $stats['status_sync']++;
                $this->X_model->update($x[0]['x__id'], array(
                    'x__status' => $status_converter[$e['e__status']],
                ));

            }

        }

        return $stats;
    }




    function metadata_experts($e, $level = 1){

        //Goes through $max_search_levels of sources to find expert channels, people & organizations
        $max_search_levels = 3;
        $metadata_this = array(
            'p___13339' => array(),
            'p___3000' => array(),
        );

        //SOURCE PROFILE
        foreach($this->X_model->fetch(array(
            'x__down' => $e['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')).')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        ), array('x__up'), 0) as $e__profile){


            if(in_array($e__profile['e__id'], $this->config->item('n___3000'))){
                //EXPERT CONTENT
                if (!isset($metadata_this['p___3000'][$e['e__id']])) {
                    $metadata_this['p___3000'][$e['e__id']] = $e;
                }
            } elseif($e__profile['e__id']==13339) {
                //EXPERT AUTHORS
                $e['x__message'] = $e__profile['x__message']; //Update Description
                if (!isset($metadata_this['p___13339'][$e['e__id']])) {
                    $metadata_this['p___13339'][$e['e__id']] = $e;
                }
            }

            //Go another level?
            if($level < $max_search_levels){

                $metadata_recursion = $this->E_model->metadata_experts($e__profile, ($level + 1));

                //CONTENT CHANNELS
                foreach($metadata_recursion['p___3000'] as $e__id => $e_content) {
                    if (!isset($metadata_this['p___3000'][$e__id])) {
                        $metadata_this['p___3000'][$e__id] = $e_content;
                    }
                }

                //EXPERT PEOPLE/ORGANIZATIONS
                foreach($metadata_recursion['p___13339'] as $e__id => $e_expert) {
                    if (!isset($metadata_this['p___13339'][$e__id])) {
                        $metadata_this['p___13339'][$e__id] = $e_expert;
                    }
                }
            }
        }

        return $metadata_this;
    }



    function url($url, $x__source = 0, $add_to_child_e__id = 0, $page_title = null)
    {

        /*
         *
         * Analyzes a URL to see if it and its domain exists.
         * Input legend:
         *
         * - $url:                  Input URL
         * - $x__source:       IF > 0 will save URL (if not previously there) and give credit to this source as the user
         * - $add_to_child_e__id:   IF > 0 Will also add URL to this child if present
         * - $page_title:           If set it would override the source title that is auto generated (Used in Add Source Wizard to enable users to edit auto generated title)
         *
         * */


        //Validate URL:
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        } elseif ($add_to_child_e__id > 0 && $x__source < 1) {
            return array(
                'status' => 0,
                'message' => 'Parent source is required to add a parent URL',
            );
        }

        //Reuser if source name was passed:
        $page_title_generic = 0;
        $name_was_passed = ( $page_title ? true : false );
        $e___4537 = $this->config->item('e___4537');
        $e___4592 = $this->config->item('e___4592');

        //Initially assume Generic URL unless we can prove otherwise:
        $x__type = 4256; //Generic URL

        //We'll check to see if URL previously existed:
        $url_previously_existed = 0;

        //Start with null and see if we can find/add:
        $e_url = null;

        //Analyze domain:
        $url_analysis = analyze_domain($url);

        //Now let's analyze further based on type:
        if ($url_analysis['url_root']) {

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
                $x__type = 4257;
                $url = $embed_code['clean_url'];

            } elseif ($url_analysis['url_file_extension'] && is_https_url($url)) {

                $detected_extension = false;
                foreach($this->config->item('e___11080') as $e__id => $m){
                    if(in_array($url_analysis['url_file_extension'], explode('|' , $m['m_message']))){
                        $x__type = $e__id;
                        $detected_extension = true;
                        break;
                    }
                }

                if(!$detected_extension){
                    //Log error to notify admin:
                    $this->X_model->create(array(
                        'x__message' => 'e_url() detected unknown file extension ['.$url_analysis['url_file_extension'].'] that needs to be added to @11080',
                        'x__type' => 4246, //Platform Bug Reports
                        'x__up' => 11080,
                        'x__metadata' => $url_analysis,
                    ));
                }
            }
        }



        //Update Name:
        if (!$name_was_passed) {

            //Only fetch URL content in certain situations:
            $url_content = ( in_array($x__type, $this->config->item('n___11059')) /* not a direct file type */ ? null : @file_get_contents($url) );
            $page_title = e__title_validate(( $url_content ? one_two_explode('>', '', one_two_explode('<title', '</title', html_entity_decode($url_content))) : $page_title ), $x__type);
            if(!$url_content || !substr_count($url_content, '<title')){
                $url_previously_existed = 1;
            }

        }


        //Fetch/Create domain source:
        $url_e = $this->E_model->domain($url, $x__source, ( $url_analysis['url_root'] && $name_was_passed ? $page_title : null ));
        if(!$url_e['status']){
            //We had an issue:
            return $url_e;
        }


        //Was this not a root domain? If so, also check to see if URL exists:
        if ($url_analysis['url_root']) {

            //URL is the domain in this case:
            $e_url = $url_e['e_domain'];

            //IF the URL exists since the domain existed and the URL is the domain!
            if ($url_e['domai_previously_existed']) {
                $url_previously_existed = 1;
            }

        } else {

            //Check to see if URL previously exists:
            $url_x = $this->X_model->fetch(array(
                'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___4537')) . ')' => null, //User URL Transactions
                'x__message' => $url,
            ), array('x__down'));


            //Do we need to create an source for this URL?
            if (count($url_x) > 0) {

                //Nope, source previously exists:
                $e_url = $url_x[0];
                $url_previously_existed = 1;

            } elseif($x__source) {

                if(!$page_title){
                    //Assign a generic source name:
                    $page_title = $e___4592[$x__type]['m_title'].' '.substr(md5($url), 0, 8);
                    $page_title_generic = 1;
                }

                //Create a new source for this URL ONLY If user source is provided...
                $added_e = $this->E_model->verify_create($page_title, $x__source, 6181, $e___4592[$x__type]['m_icon']);
                if($added_e['status']){

                    //All good:
                    $e_url = $added_e['new_e'];

                    //Always transaction URL to its parent domain:
                    $this->X_model->create(array(
                        'x__source' => $x__source,
                        'x__type' => $x__type,
                        'x__up' => $url_e['e_domain']['e__id'],
                        'x__down' => $e_url['e__id'],
                        'x__message' => $url,
                    ));

                    //Assign to User:
                    $this->E_model->create_session($e_url['e__id']);

                    //Update Search Index:
                    update_algolia(12274, $e_url['e__id']);

                } else {

                    //Log error:
                    $this->X_model->create(array(
                        'x__message' => 'e_url['.$url.'] FAILED to create ['.$page_title.'] with message: '.$added_e['message'],
                        'x__type' => 4246, //Platform Bug Reports
                        'x__source' => $x__source,
                        'x__up' => $url_e['e_domain']['e__id'],
                        'x__metadata' => array(
                            'url' => $url,
                            'x__source' => $x__source,
                            'add_to_child_e__id' => $add_to_child_e__id,
                            'page_title' => $page_title,
                            'page_title_generic' => $page_title_generic,
                        ),
                    ));

                }

            } else {
                //URL not found and no user source provided to create the URL:
                $e_url = array();
            }
        }


        //Have we been asked to also add URL to another parent or child?
        if(!$url_previously_existed && $add_to_child_e__id){
            //Transaction URL to its parent domain?
            $this->X_model->create(array(
                'x__source' => $x__source,
                'x__type' => e_x__type(),
                'x__up' => $e_url['e__id'],
                'x__down' => $add_to_child_e__id,
            ));
        }

        $url_already_linked = $url_previously_existed && !$x__source && isset($e_url['e__id']);

        //Return results:
        return array_merge(

            $url_analysis, //Make domain analysis data available as well...

            array(
                'status' => ( $url_already_linked ? 0 : 1),
                'message' => ( $url_already_linked ? 'URL already added to <a href="/@'.$e_url['e__id'].'">'.$e_url['e__title'].'</a>' : 'Success'),
                'url_previously_existed' => $url_previously_existed,
                'clean_url' => $url,
                'x__type' => $x__type,
                'page_title' => html_entity_decode($page_title, ENT_QUOTES),
                'page_title_generic' => $page_title_generic,
                'e_domain' => $url_e['e_domain'],
                'e_url' => $e_url,
            )
        );
    }

    function mass_update($e__id, $action_e__id, $action_command1, $action_command2, $x__source)
    {

        //Alert: Has a twin function called i_mass_update()

        boost_power();

        $is_valid_icon = is_valid_icon($action_command1);

        if(!in_array($action_e__id, $this->config->item('n___4997'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif($action_e__id != 5943 && $action_e__id != 12318 && strlen(trim($action_command1)) < 1){

            return array(
                'status' => 0,
                'message' => 'Missing primary command',
            );

        } elseif(in_array($action_e__id, array(5943,12318)) && !$is_valid_icon['status']){

            return array(
                'status' => 0,
                'message' => $is_valid_icon['message'],
            );

        } elseif(in_array($action_e__id, array(5981, 5982, 12928, 12930, 11956, 13441)) && !is_valid_e_string($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @123 Source Name',
            );

        } elseif(in_array($action_e__id, array(11956)) && !is_valid_e_string($action_command2)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @123 Source Name',
            );

        }





        //Basic input validation done, let's continue...


        //Fetch all children:
        $applied_success = 0; //To be populated...
        $children = $this->X_model->fetch(array(
            'x__up' => $e__id,
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__down'), 0);


        //Process request:
        foreach($children as $x) {

            //Logic here must match items in e_mass_actions config variable

            //Take command-specific action:
            if ($action_e__id == 4998) { //Add Prefix String

                $this->E_model->update($x['e__id'], array(
                    'e__title' => $action_command1 . $x['e__title'],
                ), true, $x__source);

                $applied_success++;

            } elseif ($action_e__id == 4999) { //Add Postfix String

                $this->E_model->update($x['e__id'], array(
                    'e__title' => $x['e__title'] . $action_command1,
                ), true, $x__source);

                $applied_success++;

            } elseif (in_array($action_e__id, array(5981, 5982, 12928, 12930, 11956, 13441))) { //Add/Delete/Migrate parent source

                //What user searched for:
                $parent_e__id = intval(one_two_explode('@',' ',$action_command1));

                //See if child source has searched parent source:
                $child_parent_e = $this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__down' => $x['e__id'], //This child source
                    'x__up' => $parent_e__id,
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                ));

                if((in_array($action_e__id, array(5981, 13441)) && count($child_parent_e)==0) || ($action_e__id==12928 && view_coins_e(12273, $x['e__id'],0, false) > 0) || ($action_e__id==12930 && !view_coins_e(12273, $x['e__id'],0, false))){

                    $add_fields = array(
                        'x__source' => $x__source,
                        'x__type' => e_x__type(),
                        'x__down' => $x['e__id'], //This child source
                        'x__up' => $parent_e__id,
                    );

                    if($action_e__id==13441){
                        //Copy message only if moving:
                        $add_fields['x__message'] = $x['x__message'];
                    }

                    //Parent User Addition
                    $this->X_model->create($add_fields);

                    $applied_success++;

                    if($action_e__id==13441){
                        //Since we're migrating we should remove from here:
                        $this->X_model->update($x['x__id'], array(
                            'x__status' => 6173, //Transaction Deleted
                        ), $x__source, 10673 /* User Transaction Unpublished  */);
                    }

                } elseif(in_array($action_e__id, array(5982, 11956)) && count($child_parent_e) > 0){

                    if($action_e__id==5982){

                        //Parent User Removal
                        foreach($child_parent_e as $delete_tr){

                            $this->X_model->update($delete_tr['x__id'], array(
                                'x__status' => 6173, //Transaction Deleted
                            ), $x__source, 10673 /* User Transaction Unpublished  */);

                            $applied_success++;
                        }

                    } elseif($action_e__id==11956) {

                        $parent_new_e__id = intval(one_two_explode('@',' ',$action_command2));

                        //Add as a parent because it meets the condition
                        $this->X_model->create(array(
                            'x__source' => $x__source,
                            'x__type' => e_x__type(),
                            'x__down' => $x['e__id'], //This child source
                            'x__up' => $parent_new_e__id,
                        ));

                        $applied_success++;

                    }

                }

            } elseif ($action_e__id == 5943) { //User Mass Update User Icon

                $this->E_model->update($x['e__id'], array(
                    'e__icon' => $action_command1,
                ), true, $x__source);

                $applied_success++;

            } elseif ($action_e__id == 12318 && !strlen($x['e__icon'])) { //User Mass Update User Icon

                $this->E_model->update($x['e__id'], array(
                    'e__icon' => $action_command1,
                ), true, $x__source);

                $applied_success++;

            } elseif ($action_e__id == 5000 && substr_count($x['e__title'], strtoupper($action_command1)) > 0) { //Replace User Matching Name

                $this->E_model->update($x['e__id'], array(
                    'e__title' => str_replace(strtoupper($action_command1), strtoupper($action_command2), $x['e__title']),
                ), true, $x__source);

                $applied_success++;

            } elseif ($action_e__id == 10625 && substr_count($x['e__icon'], $action_command1) > 0) { //Replace User Matching Icon

                $this->E_model->update($x['e__id'], array(
                    'e__icon' => str_replace($action_command1, $action_command2, $x['e__icon']),
                ), true, $x__source);

                $applied_success++;

            } elseif ($action_e__id == 5001 && substr_count($x['x__message'], $action_command1) > 0) { //Replace Transaction Matching String

                $this->X_model->update($x['x__id'], array(
                    'x__message' => str_replace($action_command1, $action_command2, $x['x__message']),
                ), $x__source, 10657 /* SOURCE LINK CONTENT UPDATE  */);

                $applied_success++;

            } elseif ($action_e__id == 5003 && ($action_command1=='*' || $x['e__status']==$action_command1) && in_array($action_command2, $this->config->item('n___6177'))) {

                //Being deleted? Remove as well if that's the case:
                if(!in_array($action_command2, $this->config->item('n___7358'))){
                    $this->E_model->remove($x['e__id'], $x__source);
                }

                //Update Matching User Status:
                $this->E_model->update($x['e__id'], array(
                    'e__status' => $action_command2,
                ), true, $x__source);

                $applied_success++;

            } elseif ($action_e__id == 5865 && ($action_command1=='*' || $x['x__status']==$action_command1) && in_array($action_command2, $this->config->item('n___6186') /* Transaction Status */)) { //Update Matching Transaction Status

                $this->X_model->update($x['x__id'], array(
                    'x__status' => $action_command2,
                ), $x__source, ( in_array($action_command2, $this->config->item('n___7360') /* ACTIVE */) ? 10656 /* User Transaction Updated Status */ : 10673 /* User Transaction Unpublished */ ));

                $applied_success++;

            }
        }


        //Log mass source edit transaction:
        $this->X_model->create(array(
            'x__source' => $x__source,
            'x__type' => $action_e__id,
            'x__down' => $e__id,
            'x__metadata' => array(
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

    function child_count($e__id, $e_statuses)
    {

        //Count the active children of source:
        $list_e_count = 0;

        //Do a child count:
        $list_e_count = $this->X_model->fetch(array(
            'x__up' => $e__id,
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'e__status IN (' . join(',', $e_statuses) . ')' => null,
        ), array('x__down'), 0, 0, array(), 'COUNT(e__id) as totals');

        if (count($list_e_count) > 0) {
            $list_e_count = intval($list_e_count[0]['totals']);
        }

        return $list_e_count;
    }


    function verify_create($e__title, $x__source = 0, $e__status = 6181, $e__icon = null){

        if(!in_array($e__status, $this->config->item('n___6177'))){
            //Invalid Status ID
            return array(
                'status' => 0,
                'message' => 'Invalid Source Status',
            );
        }

        //Validate Title
        $e__title_validate = e__title_validate($e__title);
        if(!$e__title_validate['status']){
            return $e__title_validate;
        }

        //Create source
        $focus_e = $this->E_model->create(array(
            'e__title' => $e__title_validate['e__title_clean'],
            'e__icon' => $e__icon,
            'e__status' => $e__status,
        ), true, $x__source);

        //Return success:
        return array(
            'status' => 1,
            'new_e' => $focus_e,
        );

    }

}