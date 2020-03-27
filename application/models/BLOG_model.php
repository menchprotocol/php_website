<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class BLOG_model extends CI_Model
{

    /*
     *
     * Blog related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function in_create($insert_columns, $external_sync = false, $ln_player_play_id = 0)
    {

        //What is required to create a new Blog?
        if (detect_missing_columns($insert_columns, array('in_title', 'in_type_play_id', 'in_status_play_id'), $ln_player_play_id)) {
            return false;
        }

        if(!isset($insert_columns['in_read_time']) || $insert_columns['in_read_time'] < 0){
            $insert_columns['in_read_time'] = config_var(12176);
        }

        //Lets now add:
        $this->db->insert('table_blog', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['in_id'])) {
            $insert_columns['in_id'] = $this->db->insert_id();
        }

        if ($insert_columns['in_id'] > 0) {

            if ($ln_player_play_id > 0) {

                if($external_sync){
                    //Update Algolia:
                    $algolia_sync = update_algolia('in', $insert_columns['in_id']);
                }

                //Log link new Blog:
                $this->READ_model->ln_create(array(
                    'ln_player_play_id' => $ln_player_play_id,
                    'ln_child_blog_id' => $insert_columns['in_id'],
                    'ln_content' => $insert_columns['in_title'],
                    'ln_type_play_id' => 4250, //New Blog Created
                    'ln_status_play_id' => 6175, //Drafting
                ));

                //Also add as author:
                $this->READ_model->ln_create(array(
                    'ln_player_play_id' => $ln_player_play_id,
                    'ln_parent_play_id' => $ln_player_play_id,
                    'ln_type_play_id' => 4983,
                    'ln_content' => '@'.$ln_player_play_id,
                    'ln_child_blog_id' => $insert_columns['in_id'],
                ), $external_sync);

                //Fetch to return the complete player data:
                $ins = $this->BLOG_model->in_fetch(array(
                    'in_id' => $insert_columns['in_id'],
                ));

                return $ins[0];

            } else {

                //Return provided inputs plus the new player ID:
                return $insert_columns;

            }

        } else {

            //Ooopsi, something went wrong!
            $this->READ_model->ln_create(array(
                'ln_content' => 'in_create() failed to create a new blog',
                'ln_type_play_id' => 4246, //Platform Bug Reports
                'ln_player_play_id' => $ln_player_play_id,
                'ln_metadata' => $insert_columns,
            ));
            return false;

        }
    }

    function in_fetch($match_columns = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for Blogs
        $this->db->select($select);
        $this->db->from('table_blog');

        foreach ($match_columns as $key => $value) {
            $this->db->where($key, $value);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }
        if (count($order_columns) > 0) {
            foreach ($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }
        $q = $this->db->get();
        return $q->result_array();
    }

    function in_update($id, $update_columns, $external_sync = false, $ln_player_play_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current Blog filed values so we can compare later on after we've updated it:
        if($ln_player_play_id > 0){
            $before_data = $this->BLOG_model->in_fetch(array('in_id' => $id));
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['in_metadata']) && is_array($update_columns['in_metadata'])) {
            $update_columns['in_metadata'] = serialize($update_columns['in_metadata']);
        }

        //Update:
        $this->db->where('in_id', $id);
        $this->db->update('table_blog', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $ln_player_play_id > 0) {


            //Note that unlike player modification, we require a trainer player ID to log the modification link:
            //Log modification link for every field changed:
            foreach ($update_columns as $key => $value) {

                if ($before_data[0][$key] == $value){
                    //Nothing changed:
                    continue;
                }

                //Assume no player links unless specifically defined:
                $ln_child_play_id = 0;
                $ln_parent_play_id = 0;


                if($key=='in_title') {

                    $ln_type_play_id = 10644; //Blog Iterated Outcome
                    $ln_content = update_description($before_data[0][$key], $value);

                } elseif($key=='in_status_play_id'){

                    if(in_array($value, $this->config->item('en_ids_7356') /* Blog Statuses Active */)){
                        $ln_type_play_id = 10648; //Blog Iterated Status
                    } else {
                        $ln_type_play_id = 10671; //Blog Iterated Archived
                    }
                    $en_all_4737 = $this->config->item('en_all_4737'); //Blog Statuses
                    $ln_content = echo_clean_db_name($key) . ' iterated from [' . $en_all_4737[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_4737[$value]['m_name'] . ']';
                    $ln_parent_play_id = $value;
                    $ln_child_play_id = $before_data[0][$key];

                } elseif($key=='in_type_play_id'){

                    $ln_type_play_id = 10651; //Blog Iterated Subtype
                    $en_all_7585 = $this->config->item('en_all_7585'); //Blog Subtypes
                    $ln_content = echo_clean_db_name($key) . ' iterated from [' . $en_all_7585[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_7585[$value]['m_name'] . ']';
                    $ln_parent_play_id = $value;
                    $ln_child_play_id = $before_data[0][$key];

                } elseif($key=='in_read_time') {

                    $ln_type_play_id = 10650; //Blog Iterated Completion Time
                    $ln_content = echo_clean_db_name($key) . ' iterated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }


                //Value has changed, log link:
                $this->READ_model->ln_create(array(
                    'ln_player_play_id' => $ln_player_play_id,
                    'ln_type_play_id' => $ln_type_play_id,
                    'ln_child_blog_id' => $id,
                    'ln_child_play_id' => $ln_child_play_id,
                    'ln_parent_play_id' => $ln_parent_play_id,
                    'ln_content' => $ln_content,
                    'ln_metadata' => array(
                        'in_id' => $id,
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ),
                ));

            }

            if($external_sync){
                //Sync algolia:
                update_algolia('in', $id);
            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->READ_model->ln_create(array(
                'ln_child_blog_id' => $id,
                'ln_type_play_id' => 4246, //Platform Bug Reports
                'ln_player_play_id' => $ln_player_play_id,
                'ln_content' => 'in_update() Failed to update',
                'ln_metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function in_unlink($in_id, $ln_player_play_id = 0){

        //Remove blog relations:
        $links_removed = 0;
        foreach($this->READ_model->ln_fetch(array( //Blog Links
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
            '(ln_child_blog_id = '.$in_id.' OR ln_parent_blog_id = '.$in_id.')' => null,
        ), array(), 0) as $ln){
            //Remove this link:
            $links_removed += $this->READ_model->ln_update($ln['ln_id'], array(
                'ln_status_play_id' => 6173, //Link Removed
            ), $ln_player_play_id, 10686 /* Blog Link Unlinked */);
        }

        //Return links removed:
        return $links_removed;
    }

    function in_sync_creation($ln_player_play_id, $query= array()){

        //STATS
        $stats = array(
            'ln_type_play_id' => 4250, //Blog Created
            'scanned' => 0,
            'missing_creation_fix' => 0,
            'status_sync' => 0,
        );

        //BLOGS
        $status_converter = array(
            12137 => 12399, //BLOG FEATURE => READ FEATURE
            6184 => 6176, //BLOG PUBLISH => READ PUBLISH
            6183 => 6175, //BLOG DRAFT => READ DRAFT
            6182 => 6173, //BLOG ARCHIVE => READ ARCHIVE
        );
        foreach($this->BLOG_model->in_fetch($query) as $in){

            $stats['scanned']++;

            //Find creation read:
            $reads = $this->READ_model->ln_fetch(array(
                'ln_type_play_id' => $stats['ln_type_play_id'],
                'ln_child_blog_id' => $in['in_id'],
            ));

            if(!count($reads)){

                $stats['missing_creation_fix']++;

                $this->READ_model->ln_create(array(
                    'ln_player_play_id' => $ln_player_play_id,
                    'ln_child_blog_id' => $in['in_id'],
                    'ln_content' => $in['in_title'],
                    'ln_type_play_id' => $stats['ln_type_play_id'],
                    'ln_status_play_id' => $status_converter[$in['in_status_play_id']],
                ));

            } elseif($reads[0]['ln_status_play_id'] != $status_converter[$in['in_status_play_id']]){

                $stats['status_sync']++;
                $this->READ_model->ln_update($reads[0]['ln_id'], array(
                    'ln_status_play_id' => $status_converter[$in['in_status_play_id']],
                ));

            }

        }

        return $stats;
    }

    function in_link_or_create($in_title, $ln_player_play_id, $link_to_blog_id = 0, $is_parent = false, $new_in_status = 6184, $in_type_play_id = 6677 /* Blog Read-Only */, $link_in_id = 0)
    {

        /*
         *
         * The main blog creation function that would create
         * appropriate links and return the blog view.
         *
         * Either creates an BLOG link between $link_to_blog_id & $link_in_id
         * (IF $link_in_id>0) OR will create a new blog with outcome $in_title
         * and link it to $link_to_blog_id (In this case $link_in_id will be 0)
         *
         * p.s. Inputs have already been validated via blogs/in_link_or_create() function
         *
         * */

        //Validate Original blog:
        if($link_to_blog_id > 0){
            $linked_ins = $this->BLOG_model->in_fetch(array(
                'in_id' => intval($link_to_blog_id),
            ));

            if (count($linked_ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Blog ID',
                );
            } elseif (!in_array($linked_ins[0]['in_status_play_id'], $this->config->item('en_ids_7356')) /* Blog Statuses Active */) {
                return array(
                    'status' => 0,
                    'message' => 'You can only link to active blogs. This blog is not active.',
                );
            }
        }


        if ($link_in_id > 0) {

            //We are linking to $link_in_id, We are NOT creating any new blogs...

            //Fetch more details on the child blog we're about to link:
            $ins = $this->BLOG_model->in_fetch(array(
                'in_id' => $link_in_id,
            ));

            //Determine which is parent Blog, and which is child
            if($is_parent){

                $parent_in = $ins[0];
                $child_in = $linked_ins[0];

                /*
                //Prevent child duplicates:
                $recursive_children = $this->BLOG_model->in_recursive_child_ids($child_in['in_id'], false);
                if (in_array($parent_in['in_id'], $recursive_children)) {
                    return array(
                        'status' => 0,
                        'message' => 'Blog already set as child, so it cannot be added as parent',
                    );
                }
                */

            } else {

                $parent_in = $linked_ins[0];
                $child_in = $ins[0];

                //Prevent parent duplicate:
                $recursive_parents = $this->BLOG_model->in_fetch_recursive_parents($parent_in['in_id']);
                foreach ($recursive_parents as $grand_parent_ids) {
                    if (in_array($child_in['in_id'], $grand_parent_ids)) {
                        return array(
                            'status' => 0,
                            'message' => 'Blog already set as parent, so it cannot be added as child',
                        );
                    }
                }
            }


            if (count($ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Linked Blog ID',
                );
            } elseif (!in_array($ins[0]['in_status_play_id'], $this->config->item('en_ids_7356') /* Blog Statuses Active */)) {
                return array(
                    'status' => 0,
                    'message' => 'You can only link to active blogs. This blog is not active.',
                );
            }

            //All good so far, continue with linking:
            $blog_new = $ins[0];

            //Make sure this is not a duplicate Blog for its parent:
            $dup_links = $this->READ_model->ln_fetch(array(
                'ln_parent_blog_id' => $parent_in['in_id'],
                'ln_child_blog_id' => $child_in['in_id'],
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ));

            //Check for issues:
            if (count($dup_links) > 0) {

                //Ooopsi, this is a duplicate!
                return array(
                    'status' => 0,
                    'message' => '[' . $blog_new['in_title'] . '] is already linked here.',
                );

            } elseif ($link_to_blog_id > 0 && $link_in_id == $link_to_blog_id) {

                //Make sure none of the parents are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "' . $blog_new['in_title'] . '" as its own '.( $is_parent ? 'parent' : 'child' ).'.',
                );

            }

        } else {

            //We are NOT linking to an existing Blog, but instead, we're creating a new Blog

            //Validate Blog Outcome:
            $in_titlevalidation = $this->BLOG_model->in_titlevalidate($in_title);
            if(!$in_titlevalidation['status']){
                //We had an error, return it:
                return $in_titlevalidation;
            }


            //Create new Blog:
            $blog_new = $this->BLOG_model->in_create(array(
                'in_title' => $in_titlevalidation['in_cleaned_outcome'],
                'in_type_play_id' => $in_type_play_id,
                'in_status_play_id' => $new_in_status,
            ), true, $ln_player_play_id);

        }


        //Create Blog Link:
        if($link_to_blog_id > 0){

            $relation = $this->READ_model->ln_create(array(
                'ln_player_play_id' => $ln_player_play_id,
                'ln_type_play_id' => 4228, //Blog Link Regular Read
                ( $is_parent ? 'ln_child_blog_id' : 'ln_parent_blog_id' ) => $link_to_blog_id,
                ( $is_parent ? 'ln_parent_blog_id' : 'ln_child_blog_id' ) => $blog_new['in_id'],
                'ln_order' => 1 + $this->READ_model->ln_max_order(array(
                        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                        'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
                        'ln_parent_blog_id' => ( $is_parent ? $blog_new['in_id'] : $link_to_blog_id ),
                    )),
            ), true);

            //Fetch and return full data to be properly shown on the UI using the echo_in() function
            $new_ins = $this->READ_model->ln_fetch(array(
                ( $is_parent ? 'ln_child_blog_id' : 'ln_parent_blog_id' ) => $link_to_blog_id,
                ( $is_parent ? 'ln_parent_blog_id' : 'ln_child_blog_id' ) => $blog_new['in_id'],
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            ), array(($is_parent ? 'in_parent' : 'in_child')), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation


            $child_in_html = echo_in($new_ins[0], $link_to_blog_id, $is_parent, true /* Since they added it! */);

        } else {

            $child_in_html = null;

        }

        //Return result:
        return array(
            'status' => 1,
            'new_in_id' => $blog_new['in_id'],
            'in_child_html' => $child_in_html,
        );

    }


    function in_fetch_recursive_parents($in_id, $first_level = true, $public_only = true){

        $grand_parents = array();

        //Fetch parents:
        foreach($this->READ_model->ln_fetch(array(
            'in_status_play_id IN (' . join(',', $this->config->item(($public_only ? 'en_ids_7355' : 'en_ids_7356' ))) . ')' => null,
            'ln_status_play_id IN (' . join(',', $this->config->item(($public_only ? 'en_ids_7359' : 'en_ids_7360' ))) . ')' => null,
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
            'ln_child_blog_id' => $in_id,
        ), array('in_parent')) as $in_parent){

            //Prep ID:
            $p_id = intval($in_parent['in_id']);

            //Add to appropriate array:
            if(!$first_level){
                array_push($grand_parents, $p_id);
            }


            //Fetch parents of parents:
            $recursive_parents = $this->BLOG_model->in_fetch_recursive_parents($p_id, false);
            if(count($recursive_parents) > 0){
                if($first_level){
                    array_push($grand_parents, array_merge(array($p_id), $recursive_parents));
                } else {
                    $grand_parents = array_merge($grand_parents, $recursive_parents);
                }
            } elseif($first_level){
                array_push($grand_parents, array($p_id));
            }
        }

        return $grand_parents;
    }



    function in_recursive_child_ids($in_id, $first_level = true){

        $child_ids = array();

        //Fetch parents:
        foreach($this->READ_model->ln_fetch(array(
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
            'ln_parent_blog_id' => $in_id,
        ), array('in_child')) as $child_in){

            array_push($child_ids, intval($child_in['in_id']));

            //Fetch parents of parents:
            $recursive_children = $this->BLOG_model->in_recursive_child_ids($child_in['in_id'], false);

            //Add to current array if we found anything:
            if(count($recursive_children) > 0){
                $child_ids = array_merge($child_ids, $recursive_children);
            }
        }

        if($first_level){
            return array_unique($child_ids);
        } else {
            return $child_ids;
        }
    }



    function in_metadata_common_base($focus_in){

        //Set variables:
        $is_first_blog = ( !isset($focus_in['ln_id']) ); //First blog does not have a link, just the blog
        $has_or_parent = in_array($focus_in['in_type_play_id'] , $this->config->item('en_ids_6193') /* OR Blogs */ );
        $or_children = array(); //To be populated only if $focus_in is an OR blog
        $conditional_steps = array(); //To be populated only for Conditional Reads
        $metadata_this = array(
            '__in__metadata_common_steps' => array(), //The tree structure that would be shared with all users regardless of their quick replies (OR Blog Answers)
            '__in__metadata_expansion_steps' => array(), //Blogs that may exist as a link to expand an 🔴 READING LIST tree by answering OR blogs
            '__in__metadata_expansion_conditional' => array(), //Blogs that may exist as a link to expand an 🔴 READING LIST tree via Conditional Read links
        );

        //Fetch children:
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
            'ln_parent_blog_id' => $focus_in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $child_in){

            //Determine action based on parent blog type:
            if($child_in['ln_type_play_id']==4229){

                //Conditional Read Link:
                array_push($conditional_steps, intval($child_in['in_id']));

            } elseif($has_or_parent){

                //OR parent Blog with Fixed Read Link:
                array_push($or_children, intval($child_in['in_id']));

            } else {

                //AND parent Blog with Fixed Read Link:
                array_push($metadata_this['__in__metadata_common_steps'], intval($child_in['in_id']));

                //Go recursively down:
                $child_recursion = $this->BLOG_model->in_metadata_common_base($child_in);


                //Aggregate recursion data:
                if(count($child_recursion['__in__metadata_common_steps']) > 0){
                    array_push($metadata_this['__in__metadata_common_steps'], $child_recursion['__in__metadata_common_steps']);
                }

                //Merge expansion steps:
                if(count($child_recursion['__in__metadata_expansion_steps']) > 0){
                    foreach($child_recursion['__in__metadata_expansion_steps'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['__in__metadata_expansion_steps'])){
                            $metadata_this['__in__metadata_expansion_steps'][$key] = $value;
                        }
                    }
                }
                if(count($child_recursion['__in__metadata_expansion_conditional']) > 0){
                    foreach($child_recursion['__in__metadata_expansion_conditional'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['__in__metadata_expansion_conditional'])){
                            $metadata_this['__in__metadata_expansion_conditional'][$key] = $value;
                        }
                    }
                }
            }
        }


        //Was this an OR branch that needs it's children added to the array?
        if($has_or_parent && count($or_children) > 0){
            $metadata_this['__in__metadata_expansion_steps'][$focus_in['in_id']] = $or_children;
        }

        if(count($conditional_steps) > 0){
            $metadata_this['__in__metadata_expansion_conditional'][$focus_in['in_id']] = $conditional_steps;
        }


        //Save common base:
        if($is_first_blog){

            //Make sure to add main blog to common tree:
            if(count($metadata_this['__in__metadata_common_steps']) > 0){
                $metadata_this['__in__metadata_common_steps'] = array_merge( array(intval($focus_in['in_id'])) , array($metadata_this['__in__metadata_common_steps']));
            } else {
                $metadata_this['__in__metadata_common_steps'] = array(intval($focus_in['in_id']));
            }

            update_metadata('in', $focus_in['in_id'], array(
                'in__metadata_common_steps' => $metadata_this['__in__metadata_common_steps'],
                'in__metadata_expansion_steps' => $metadata_this['__in__metadata_expansion_steps'],
                'in__metadata_expansion_conditional' => $metadata_this['__in__metadata_expansion_conditional'],
            ));

        }

        //Return results:
        return $metadata_this;

    }

    function in_tree_weight($in_id)
    {

        /*
         *
         * Addup weights recursively
         *
         * */


        $total_child_weights = 0;

        foreach($this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            'ln_type_play_id' => 4228, //Fixed Blog Link
            'ln_parent_blog_id' => $in_id,
        ), array('in_child'), 0, 0, array(), 'in_id, in_weight') as $in_child){
            $total_child_weights += $in_child['in_weight'] + $this->BLOG_model->in_tree_weight($in_child['in_id']);
        }

        //Update This Level:
        if($total_child_weights > 0){
            $this->db->query("UPDATE table_blog SET in_weight=in_weight+".$total_child_weights." WHERE in_id=".$in_id.";");
        }

        //Return data:
        return $total_child_weights;

    }

    function in_metadata_extra_insights($in_id, $update_db = true)
    {

        /*
         *
         * Generates additional insights like
         * min/max steps, time, cost and
         * referenced players in blog notes.
         *
         * */

        //Fetch this blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ));
        if(count($ins) < 1){
            return false;
        }

        $in_metadata = unserialize( $ins[0]['in_metadata'] );
        if(!isset($in_metadata['in__metadata_common_steps'])){
            return false;
        }

        //Fetch common base and expansion paths from blog metadata:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);
        $expansion_steps = ( isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0 ? $in_metadata['in__metadata_expansion_steps'] : array() );
        $locked_steps = ( isset($in_metadata['in__metadata_expansion_conditional']) && count($in_metadata['in__metadata_expansion_conditional']) > 0 ? $in_metadata['in__metadata_expansion_conditional'] : array() );

        //Fetch totals for published common step blogs:
        $common_totals = $this->BLOG_model->in_fetch(array(
            'in_id IN ('.join(',',$flat_common_steps).')' => null,
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ), 0, 0, array(), 'COUNT(in_id) as total_steps, SUM(in_read_time) as total_seconds');

        $common_base_resources = array(
            'steps' => $common_totals[0]['total_steps'],
            'seconds' => $common_totals[0]['total_seconds'],
        );

        $metadata_this = array(

            //Required steps/blogs range to complete tree:
            '__in__metadata_min_steps' => $common_base_resources['steps'],
            '__in__metadata_max_steps' => $common_base_resources['steps'],

            //Required time range to complete tree:
            '__in__metadata_min_seconds' => $common_base_resources['seconds'],
            '__in__metadata_max_seconds' => $common_base_resources['seconds'],

            //Player references within blog notes:
            '__in__metadata_experts' => array(),
            '__in__metadata_sources' => array(),

        );



        //Add-up Blog Note References:
        //The players we need to check and see if they are industry experts:
        foreach ($this->READ_model->ln_fetch(array(
            'ln_parent_play_id >' => 0, //Blog Notes that reference an player
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4485')).')' => null, //Blog Notes
            '(ln_child_blog_id = ' . $in_id . ( count($flat_common_steps) > 0 ? ' OR ln_child_blog_id IN ('.join(',',$flat_common_steps).')' : '' ).')' => null,
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
        ), array('en_parent'), 0) as $note_en) {

            //Referenced player in blog notes... Fetch parents:
            foreach($this->READ_model->ln_fetch(array(
                'ln_child_play_id' => $note_en['ln_parent_play_id'],
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')).')' => null, //Player-to-Player Links
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            ), array(), 0) as $parent_en){

                if(in_array($parent_en['ln_parent_play_id'], $this->config->item('en_ids_3000'))){

                    //Expert Source:
                    if (!isset($metadata_this['__in__metadata_sources'][$parent_en['ln_parent_play_id']][$note_en['en_id']])) {
                        //Add since it's not there:
                        $metadata_this['__in__metadata_sources'][$parent_en['ln_parent_play_id']][$note_en['en_id']] = $note_en;
                    }

                } elseif($parent_en['ln_parent_play_id']==3084) {

                    //Industry Expert:
                    if (!isset($metadata_this['__in__metadata_experts'][$note_en['en_id']])) {
                        $metadata_this['__in__metadata_experts'][$note_en['en_id']] = $note_en;
                    }

                } else {

                    //Industry Expert?
                    $expert_parents = $this->READ_model->ln_fetch(array(
                        'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
                        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')).')' => null, //Player-to-Player Links
                        'ln_parent_play_id' => 3084, //Industry Experts
                        'ln_child_play_id' => $parent_en['ln_parent_play_id'],
                    ), array('en_child'), 0);

                    if(count($expert_parents) > 0){

                        //Yes, Industry Expert:
                        if (!isset($metadata_this['__in__metadata_experts'][$parent_en['ln_parent_play_id']])) {
                            $metadata_this['__in__metadata_experts'][$parent_en['ln_parent_play_id']] = $expert_parents[0];
                        }

                    } else {

                        //TODO Maybe this is an expert source that is a child of another expert source? Go another level-up and check parents...
                        //We might want to discourage this via mining principles... Need to think more on this.

                    }
                }
            }
        }


        //Go through expansion paths, if any:
        foreach(array_merge($expansion_steps, $locked_steps) as $expansion_group){

            //Determine OR Answer local min/max:
            $metadata_local = array(
                'local__in__metadata_min_steps'=> null,
                'local__in__metadata_max_steps'=> null,
                'local__in__metadata_min_seconds'=> null,
                'local__in__metadata_max_seconds'=> null,
            );

            foreach($expansion_group as $expansion_in_id){

                $metadata_recursion = $this->BLOG_model->in_metadata_extra_insights($expansion_in_id, false);

                if(!$metadata_recursion){
                    continue;
                }

                //MIN/MAX updates:
                if(is_null($metadata_local['local__in__metadata_min_steps']) || $metadata_recursion['__in__metadata_min_steps'] < $metadata_local['local__in__metadata_min_steps']){
                    $metadata_local['local__in__metadata_min_steps'] = $metadata_recursion['__in__metadata_min_steps'];
                }
                if(is_null($metadata_local['local__in__metadata_max_steps']) || $metadata_recursion['__in__metadata_max_steps'] > $metadata_local['local__in__metadata_max_steps']){
                    $metadata_local['local__in__metadata_max_steps'] = $metadata_recursion['__in__metadata_max_steps'];
                }
                if(is_null($metadata_local['local__in__metadata_min_seconds']) || $metadata_recursion['__in__metadata_min_seconds'] < $metadata_local['local__in__metadata_min_seconds']){
                    $metadata_local['local__in__metadata_min_seconds'] = $metadata_recursion['__in__metadata_min_seconds'];
                }
                if(is_null($metadata_local['local__in__metadata_max_seconds']) || $metadata_recursion['__in__metadata_max_seconds'] > $metadata_local['local__in__metadata_max_seconds']){
                    $metadata_local['local__in__metadata_max_seconds'] = $metadata_recursion['__in__metadata_max_seconds'];
                }


                //Addup Experts:
                foreach ($metadata_recursion['__in__metadata_experts'] as $en_id => $expert_en) {
                    //Is this a new expert?
                    if (!isset($metadata_this['__in__metadata_experts'][$en_id])) {
                        //Yes, add them to the list:
                        $metadata_this['__in__metadata_experts'][$en_id] = $expert_en;
                    }
                }

                //Addup Sources:
                foreach ($metadata_recursion['__in__metadata_sources'] as $type_en_id => $source_ens) {
                    foreach ($source_ens as $en_id => $source_en) {
                        if (!isset($metadata_this['__in__metadata_sources'][$type_en_id][$en_id])) {
                            $metadata_this['__in__metadata_sources'][$type_en_id][$en_id] = $source_en;
                        }
                    }
                }
            }

            //Add to totals if set:
            if(!is_null($metadata_local['local__in__metadata_min_steps'])){
                $metadata_this['__in__metadata_min_steps'] += intval($metadata_local['local__in__metadata_min_steps']);
            }
            if(!is_null($metadata_local['local__in__metadata_max_steps'])){
                $metadata_this['__in__metadata_max_steps'] += intval($metadata_local['local__in__metadata_max_steps']);
            }
            if(!is_null($metadata_local['local__in__metadata_min_seconds'])){
                $metadata_this['__in__metadata_min_seconds'] += intval($metadata_local['local__in__metadata_min_seconds']);
            }
            if(!is_null($metadata_local['local__in__metadata_max_seconds'])){
                $metadata_this['__in__metadata_max_seconds'] += intval($metadata_local['local__in__metadata_max_seconds']);
            }

        }


        if($update_db){

            /*
             *
             * Save to database
             *
             * */
            update_metadata('in', $in_id, array(
                'in__metadata_min_steps' => intval($metadata_this['__in__metadata_min_steps']),
                'in__metadata_max_steps' => intval($metadata_this['__in__metadata_max_steps']),
                'in__metadata_min_seconds' => intval($metadata_this['__in__metadata_min_seconds']),
                'in__metadata_max_seconds' => intval($metadata_this['__in__metadata_max_seconds']),
                'in__metadata_experts' => $metadata_this['__in__metadata_experts'],
                'in__metadata_sources' => $metadata_this['__in__metadata_sources'],
            ));

        }


        //Return data:
        return $metadata_this;

    }


    function in_unlock_paths($in)
    {
        /*
         *
         * Finds the pathways, if any, on how to unlock $in
         *
         * */


        //Validate this locked blog:
        if(!in_is_unlockable($in)){
            return array();
        }

        $child_unlock_paths = array();


        //Read 1: Is there an OR parent that we can simply answer and unlock?
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            'ln_type_play_id' => 4228, //Blog Link Regular Read
            'ln_child_blog_id' => $in['in_id'],
            'in_type_play_id IN (' . join(',', $this->config->item('en_ids_7712')) . ')' => null,
        ), array('in_parent'), 0) as $in_or_parent){
            if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $in_or_parent['in_id'])) {
                array_push($child_unlock_paths, $in_or_parent);
            }
        }


        //Read 2: Are there any locked link parents that the user might be able to unlock?
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            'ln_type_play_id' => 4229, //Blog Link Locked Read
            'ln_child_blog_id' => $in['in_id'],
        ), array('in_parent'), 0) as $in_locked_parent){
            if(in_is_unlockable($in_locked_parent)){
                //Need to check recursively:
                foreach($this->BLOG_model->in_unlock_paths($in_locked_parent) as $locked_path){
                    if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $locked_path['in_id'])) {
                        array_push($child_unlock_paths, $locked_path);
                    }
                }
            } elseif(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $in_locked_parent['in_id'])) {
                array_push($child_unlock_paths, $in_locked_parent);
            }
        }


        //Return if we have options for step 1 OR step 2:
        if(count($child_unlock_paths) > 0){
            //Return OR parents for unlocking this blog:
            return $child_unlock_paths;
        }


        //Read 3: We don't have any OR parents, let's see how we can complete all children to meet the requirements:
        $in__children = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            'ln_type_play_id' => 4228, //Blog Link Regular Read
            'ln_parent_blog_id' => $in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
        if(count($in__children) < 1){
            //No children, no path:
            return array();
        }

        //Go through children to see if any/all can be completed:
        foreach($in__children as $child_in){
            if(in_is_unlockable($child_in)){

                //Need to check recursively:
                foreach($this->BLOG_model->in_unlock_paths($child_in) as $locked_path){
                    if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $locked_path['in_id'])) {
                        array_push($child_unlock_paths, $locked_path);
                    }
                }

            } elseif(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $child_in['in_id'])) {

                //Not locked, so this can be completed:
                array_push($child_unlock_paths, $child_in);

            }
        }
        return $child_unlock_paths;

    }

    function in_titlevalidate($in_title){

        //Validate:
        if(!strlen(trim($in_title))){

            return array(
                'status' => 0,
                'message' => 'Title missing',
            );

        } elseif(substr_count($in_title , '  ') > 0){

            return array(
                'status' => 0,
                'message' => 'Title cannot include double spaces',
            );

        } elseif (strlen($in_title) > config_var(11071)) {

            return array(
                'status' => 0,
                'message' => 'Title must be '.config_var(11071).' characters or less',
            );

        }

        //All good, return success:
        return array(
            'status' => 1,
            'in_cleaned_outcome' => trim($in_title),
        );

    }


}