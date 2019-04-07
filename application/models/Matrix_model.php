<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Matrix_model extends CI_Model
{

    /*
     *
     * This model contains all Database functions that
     * interpret the Matrix from a particular perspective
     * to gain understanding from it and to perform pre-defined
     * operations.
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function actionplan_fetch_next($actionplan_ln_id)
    {

        /*
         *
         * Attempts to find the next item in the Action Plan
         * And if not, it would mark the Action Plan as complete!
         *
         * */

        //Let's first check if we have an OR Intent that is drafting, which means it's children have not been answered!
        $first_pending_or_intent = $this->Database_model->ln_fetch(array(
            'ln_parent_link_id' => $actionplan_ln_id, //This action Plan
            'in_status' => 2, //Published
            'in_type' => 1, //OR Branch
            'ln_status' => 1, //drafting, which means OR branch has not been answered yet
        ), array('in_child'), 1, 0, array('ln_order' => 'ASC'));

        if (count($first_pending_or_intent) > 0) {
            return $first_pending_or_intent;
        }


        //Now check the next AND intent that has not been started:
        $next_new_intent = $this->Database_model->ln_fetch(array(
            'ln_parent_link_id' => $actionplan_ln_id, //This action Plan
            'in_status' => 2, //Published
            'ln_status' => 0, //New (not started yet) for either AND/OR branches
        ), array('in_child'), 1, 0, array('ln_order' => 'ASC'));

        if (count($next_new_intent) > 0) {
            return $next_new_intent;
        }


        //Now check the next AND intent that is drafting:
        //I don't think this situation should ever happen...
        //Because if we don't have any of the previous ones,
        //how can we have this? 🤔 But let's keep it for now...
        $next_working_on_intent = $this->Database_model->ln_fetch(array(
            'ln_parent_link_id' => $actionplan_ln_id, //This action Plan
            'in_status' => 2, //Published
            'in_type' => 0, //AND Branch
            'ln_status' => 1, //drafting
        ), array('in_child'), 1, 0, array('ln_order' => 'ASC'));

        if (count($next_working_on_intent) > 0) {
            return $next_working_on_intent;
        }


        /*
         *
         * The Action Plan seems to be completed as we could not find any pending intent!
         * Nothing else left to do, we must be done with this Action Plan:
         * What is the Action Plan Status?
         *
         * */

        $actionplans = $this->Database_model->ln_fetch(array(
            'ln_id' => $actionplan_ln_id,
        ), array('in_child'));

        if (count($actionplans) > 0 && in_array($actionplans[0]['ln_status'], $this->config->item('ln_status_incomplete'))) {

            //Inform user that they are now complete with all steps:
            $this->Chat_model->dispatch_message(
                'You completed all the steps to ' . $actionplans[0]['in_outcome'] . ' 🙌 I will keep you updated on new steps and you can at any time stop these updates by saying "stop".',
                array('en_id' => $actionplans[0]['ln_parent_entity_id']),
                true,
                array(),
                array(
                    'ln_child_intent_id' => $actionplans[0]['ln_child_intent_id'],
                    'ln_parent_link_id' => $actionplans[0]['ln_id'],
                )
            );

            $this->Chat_model->dispatch_message(
                'How else can I help you with your tech career?',
                array('en_id' => $actionplans[0]['ln_parent_entity_id']),
                true,
                array(),
                array(
                    'ln_child_intent_id' => $actionplans[0]['ln_child_intent_id'],
                    'ln_parent_link_id' => $actionplans[0]['ln_id'],
                )
            );

            //The entire Action Plan is now complete!
            $this->Database_model->ln_update($actionplan_ln_id, array(
                'ln_status' => 2, //Completed
            ), $actionplans[0]['ln_parent_entity_id']);

            //List featured intents and let them choose:
            $this->Chat_model->compose_message($this->config->item('in_featured'), array('en_id' => $actionplans[0]['ln_parent_entity_id']));

        }

        return false;

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
        foreach ($this->Database_model->ln_fetch(array(
            'ln_child_entity_id' => $en_student_id,
            'ln_parent_entity_id IN (' . join(',', $children) . ')' => null, //Current children
            'ln_status >=' => 0,
        ), array(), 200) as $ln) {

            if (!$already_assigned && $ln['ln_parent_entity_id'] == $set_en_child_id) {
                $already_assigned = true;
            } else {
                //Remove assignment:
                $updated_ln_id = $ln['ln_id'];

                //Do not log update link here as we would log it further below:
                $this->Database_model->ln_update($ln['ln_id'], array(
                    'ln_status' => -1, //Removed
                ));
            }

        }


        //Make sure $set_en_child_id belongs to parent if set (Could be null which means remove all)
        if (!$already_assigned) {
            //Let's go ahead and add desired entity as parent:
            $this->Database_model->ln_create(array(
                'ln_miner_entity_id' => $ln_miner_entity_id,
                'ln_child_entity_id' => $en_student_id,
                'ln_parent_entity_id' => $set_en_child_id,
                'ln_type_entity_id' => 4230, //Raw link
                'ln_parent_link_id' => $updated_ln_id,
            ));
        }

    }

    function trs_set_drafting($lns){
        /*
         *
         * A function that simply updates the status
         * of input links so other cron jobs
         * do not pick them up and re-process them.
         *
         * */

        foreach ($lns as $ln) {
            if($ln['ln_status'] == 0){
                $this->Database_model->ln_update($ln['ln_id'], array(
                    'ln_status' => 1, //Drafting
                ));
            }
        }
    }

    function en_unlink($en_id, $ln_miner_entity_id = 0, $merger_en_id = 0){

        //Fetch all entity links:
        $adjusted_count = 0;
        foreach(array_merge(
                //Entity references within intent notes:
                $this->Database_model->ln_fetch(array(
                    'ln_status >=' => 0, //New+
                    'in_status >=' => 0, //New+
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
                    'ln_parent_entity_id' => $en_id,
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC')),
                //Entity links:
                $this->Database_model->ln_fetch(array(
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
                $adjusted_count += $this->Database_model->ln_update($adjust_tr['ln_id'], $updating_fields, $ln_miner_entity_id);

            } else {

                //Remove this link:
                $adjusted_count += $this->Database_model->ln_update($adjust_tr['ln_id'], array(
                    'ln_status' => -1, //Unlink
                ), $ln_miner_entity_id);

            }
        }

        return $adjusted_count;
    }

    function in_unlink($in_id, $ln_miner_entity_id = 0){

        //Remove intent relations:
        $adjust_trs = array_merge(
            $this->Database_model->ln_fetch(array( //Intent Links
                'ln_status >=' => 0, //New+
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                '(ln_child_intent_id = '.$in_id.' OR ln_parent_intent_id = '.$in_id.')' => null,
            ), array(), 0),
            $this->Database_model->ln_fetch(array( //Intent Notes
                'ln_status >=' => 0, //New+
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
                '(ln_child_intent_id = '.$in_id.' OR ln_parent_intent_id = '.$in_id.')' => null,
            ), array(), 0)
        );

        foreach($adjust_trs as $adjust_tr){
            //Remove this link:
            $this->Database_model->ln_update($adjust_tr['ln_id'], array(
                'ln_status' => -1, //Unlink
            ), $ln_miner_entity_id);
        }

        return count($adjust_trs);
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
        $domain_links = $this->Database_model->ln_fetch(array(
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
            $added_en = $this->Matrix_model->en_verify_create(( $page_title ? $page_title : $domain_analysis['url_domain_name'] ), $ln_miner_entity_id, true, 2, detect_fav_icon($domain_analysis['url_clean_domain']));
            $en_domain = $added_en['en'];

            //And link entity to the domains entity:
            $this->Database_model->ln_create(array(
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
                if(in_array($domain_analysis['url_file_extension'], $this->config->item('image_extensions'))){
                    //Image URL
                    $ln_type_entity_id = 4260;
                } elseif(in_array($domain_analysis['url_file_extension'], $this->config->item('audio_extensions'))){
                    //Audio URL
                    $ln_type_entity_id = 4259;
                } elseif(in_array($domain_analysis['url_file_extension'], $this->config->item('video_extensions'))){
                    //Video URL
                    $ln_type_entity_id = 4258;
                } elseif(in_array($domain_analysis['url_file_extension'], $this->config->item('file_extensions'))){
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
                $dup_name_us = $this->Database_model->en_fetch(array(
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
        $domain_entity = $this->Matrix_model->en_sync_domain($url, $ln_miner_entity_id, ( $domain_analysis['url_is_root'] && $name_was_passed ? $page_title : null ));


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
            $url_links = $this->Database_model->ln_fetch(array(
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
                $added_en = $this->Matrix_model->en_verify_create($page_title, $ln_miner_entity_id, true);
                $en_url = $added_en['en'];

                //Always link URL to its parent domain:
                $this->Database_model->ln_create(array(
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
            $this->Database_model->ln_create(array(
                'ln_miner_entity_id' => $ln_miner_entity_id,
                'ln_status' => 2, //Published
                'ln_type_entity_id' => 4230, //Raw
                'ln_parent_entity_id' => $add_to_parent_en_id,
                'ln_child_entity_id' => $en_url['en_id'],
            ));
        }

        if (!$url_already_existed && $add_to_child_en_id) {
            //Link URL to its parent domain:
            $this->Database_model->ln_create(array(
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
        $children = $this->Database_model->ln_fetch(array(
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

                $this->Database_model->en_update($en['en_id'], array(
                    'en_name' => $action_command1 . $en['en_name'],
                ), true, $ln_miner_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 4999) { //Add Postfix String

                $this->Database_model->en_update($en['en_id'], array(
                    'en_name' => $en['en_name'] . $action_command1,
                ), true, $ln_miner_entity_id);

                $applied_success++;

            } elseif (in_array($action_en_id, array(5981, 5982))) { //Add/Remove parent entity

                //What miner searched for:
                $parent_en_id = intval(one_two_explode('@',' ',$action_command1));

                //See if child entity has searched parent entity:
                $child_parent_ens = $this->Database_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    'ln_child_entity_id' => $en['en_id'], //This child entity
                    'ln_parent_entity_id' => $parent_en_id,
                    'ln_status >=' => 0, //New+
                ));

                if($action_en_id==5981 && count($child_parent_ens)==0){ //Parent Entity Addition

                    //Does not exist, need to be added as parent:
                    $this->Database_model->ln_create(array(
                        'ln_status' => 2,
                        'ln_miner_entity_id' => $ln_miner_entity_id,
                        'ln_type_entity_id' => 4230, //Raw
                        'ln_child_entity_id' => $en['en_id'], //This child entity
                        'ln_parent_entity_id' => $parent_en_id,
                    ));

                    $applied_success++;

                } elseif($action_en_id==5982 && count($child_parent_ens) > 0){ //Parent Entity Removal

                    //Already added as parent so it needs to be removed:
                    foreach($child_parent_ens as $remove_tr){

                        $this->Database_model->ln_update($remove_tr['ln_id'], array(
                            'ln_status' => -1, //Removed
                        ), $ln_miner_entity_id);

                        $applied_success++;
                    }

                }

            } elseif ($action_en_id == 5943) { //Entity Mass Update Entity Icon

                $this->Database_model->en_update($en['en_id'], array(
                    'en_icon' => $action_command1,
                ), true, $ln_miner_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5000 && substr_count($en['en_name'], $action_command1) > 0) { //Replace Entity Matching String

                //Make sure the SEARCH string exists:
                $this->Database_model->en_update($en['en_id'], array(
                    'en_name' => str_replace($action_command1, $action_command2, $en['en_name']),
                ), true, $ln_miner_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5001 && substr_count($en['ln_content'], $action_command1) > 0) { //Replace Link Matching String

                $this->Database_model->ln_update($en['ln_id'], array(
                    'ln_content' => str_replace($action_command1, $action_command2, $en['ln_content']),
                ), $ln_miner_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5003 && ($action_command1=='*' || $en['en_status']==$action_command1) && array_key_exists($action_command2, $fixed_fields['en_status'])) { //Update Matching Entity Status

                $this->Database_model->en_update($en['en_id'], array(
                    'en_status' => $action_command2,
                ), true, $ln_miner_entity_id);

                $applied_success++;

            } elseif ($action_en_id == 5865 && ($action_command1=='*' || $en['ln_status']==$action_command1) && array_key_exists($action_command2, $fixed_fields['ln_status'])) { //Update Matching Link Status

                $this->Database_model->ln_update($en['ln_id'], array(
                    'ln_status' => $action_command2,
                ), $ln_miner_entity_id);

                $applied_success++;

            }
        }


        //Log mass entity edit link:
        $this->Database_model->ln_create(array(
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
        $child_trs = $this->Database_model->ln_fetch(array(
            'ln_parent_entity_id' => $en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status >=' => 0, //New+
            'en_status >=' => $min_en_status,
        ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

        if (count($child_trs) > 0) {
            $en__child_count = intval($child_trs[0]['en__child_count']);
        }

        return $en__child_count;
    }


    function in_req_completion($in, $offer_instructions = false)
    {

        /*
         *
         * Sometimes to mark an intent as complete the Students might
         * need to meet certain requirements in what they submit to do so.
         * This function fetches those requirements from the Matrix and
         * Provides an easy to understand message to communicate
         * these requirements to Student.
         *
         * Will return NULL if it detects no requirements...
         *
         * */

        if ($in['in_requirement_entity_id'] == 6087) {
            //Does not have any requirements:
            return null;
        }

        //Construct the message accordingly...

        //Fetch latest cache tree:
        $en_all_4331 = $this->config->item('en_all_4331'); //Intent Completion Requirements

        //Single option:
        $message = 'Marking as complete requires a ' . $en_all_4331[$in['in_requirement_entity_id']]['m_name'].' Message';

        //Give clear directions to complete if Action Plan ID is provided...
        if ($offer_instructions) {
            $message .= ', which you can submit using your Action Plan. /link:See in 🚩Action Plan:https://mench.com/messenger/actionplan/' . $in['in_id'];
        }

        //Return Student-friendly message for completion requirements:
        return $message;

    }


    function en_student_messenger_authenticate($psid)
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
            $this->Database_model->ln_create(array(
                'ln_content' => 'en_student_messenger_authenticate() got called without a valid Facebook $psid variable',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));
            return false;
        }

        //Try matching Facebook PSID to existing Students:
        $ens = $this->Database_model->en_fetch(array(
            'en_status >=' => 0, //New+
            'en_psid' => intval($psid),
        ), array('skip_en__parents'));

        //So, did we find them?
        if (count($ens) > 0) {

            //Student found:
            return $ens[0];

        } else {

            //Student not found, create new Student:
            return $this->Matrix_model->en_messenger_add($psid);

        }

    }


    function actionplan_update_status($ln_id, $new_ln_status)
    {

        /*
         *
         * Marks an Action Plan as complete
         *
         * */

        //Validate Action Plan:
        $actionplan_ins = $this->Database_model->ln_fetch(array(
            'ln_id' => $ln_id,
        ), array('in_child', 'en_parent'));
        if (count($actionplan_ins) < 1) {
            return false;
        }

        //Update status:
        $this->Database_model->ln_update($ln_id, array(
            'ln_status' => $new_ln_status,
        ), $actionplan_ins[0]['ln_parent_entity_id']);

        //Take additional action if Action Plan is Complete:
        if ($new_ln_status == 2) {

            //It's complete!

            //Dispatch all on-complete messages if we have any:
            $on_complete_messages = $this->Database_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'ln_type_entity_id' => 4233, //On-Complete Messages
                'ln_child_intent_id' => $actionplan_ins[0]['ln_child_intent_id'],
            ), array('en_parent'), 0, 0, array('ln_order' => 'ASC'));

            foreach ($on_complete_messages as $ln) {
                $this->Chat_model->dispatch_message(
                    $ln['ln_content'], //Message content
                    $actionplan_ins[0], //Includes entity data for Action Plan Student
                    true,
                    array(),
                    array(
                        'ln_parent_link_id' => $actionplan_ins[0]['ln_parent_link_id'],
                    )
                );
            }

            //TODO Update Action Plan progress (In ln_metadata) at this point
            //TODO implement drip?

        }
    }


    function metadata_recursive_update($obj_type, $focus_obj_id, $metadata_new = array(), $direction_is_downward = false)
    {

        /*
         *
         * Keeps intent/entity metadata field in-sync when adjustments are made to tree items
         *
         * */

        //Currently only supports intents:
        if (count($metadata_new) == 0) {
            return false;
        }


        if (in_array($obj_type, array('in'))) {

            //Fetch tree that needs adjustment:
            $tree = $this->Matrix_model->in_fetch_recursive($focus_obj_id, $direction_is_downward);

            if (count($tree['in_flat_tree']) == 0) {
                return false;
            }

            //Now fetch them all:
            $objects = $this->Database_model->in_fetch(array(
                'in_id IN (' . join(',', $tree['in_flat_tree']) . ')' => null,
            ));

        } elseif (in_array($obj_type, array('en'))) {

            //TODO add entity support

        }

        //Apply relative changes to all objects:
        $affected_rows = 0;
        foreach ($objects as $obj) {
            //Make a relative adjustment compared to what is currently there:
            $affected_rows += $this->Matrix_model->metadata_single_update($obj_type, $obj[$obj_type . '_id'], $metadata_new, false);
        }

        //Return total affected rows:
        return $affected_rows;

    }


    function actionplan_skip_recursive_down($ln_id, $apply_skip = true)
    {

        //Fetch/validate Completed Step:
        $actionplan_steps = $this->Database_model->ln_fetch(array(
            'ln_id' => $ln_id,
            'ln_type_entity_id' => 4559, //Completed Step
            'ln_status >=' => 0, //New+
        ));

        if(count($actionplan_steps) < 1){
            //Ooooopsi, could not find it:
            $this->Database_model->ln_create(array(
                'ln_parent_link_id' => $ln_id,
                'ln_content' => 'actionplan_skip_recursive_down() failed to locate step [Apply: '.( $apply_skip ? 'YES' : 'NO' ).']',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));

            return false;
        }


        //See how many children there are:
        $dwn_tree = $this->Matrix_model->deprecate__actionplan_fetch_recursive($actionplan_steps[0]['ln_parent_link_id'], $actionplan_steps[0]['ln_child_intent_id'], true);

        //Combine this step with child steps to determine total steps that would be skipped:
        $skippable_trs = array_merge(array(intval($ln_id)), $dwn_tree['actionplan_links_flat']);

        //Count how many of the skippable links are incomplete (Would actually be skipped):
        $skipping_steps = $this->Database_model->ln_fetch(array(
            'ln_id IN (' . join(',', $skippable_trs) . ')' => null,
            'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete
        ));

        if ($apply_skip) {
            //Now start skipping:
            foreach ($skipping_steps as $ln) {
                $this->Matrix_model->actionplan_update_status($ln['ln_id'], -1);
            }
        }

        //Returned skippable steps:
        return $skipping_steps;

    }


    function deprecate__actionplan_fetch_recursive($actionplan_ln_id, $in_id, $direction_is_downward, $previous_in = array(), $metadata_aggregate = null)
    {
        //TODO DEPRECATE
    }


    function actionplan_append_in($in_append_id, $ln_miner_entity_id, $actionplan_in_id = 0)
    {

        /*
         *
         * Used when Students choose an OR Intent path in their Action Plan.
         * When a user chooses an answer to an ANY intent, this function
         * would mark that answer as complete while marking all siblings
         * as Removed/Skipped (ln_status = -1)
         *
         * Inputs:
         *
         * $in_append_id:       The selected child intent
         *
         * $ln_miner_entity_id: The Student who is adding this new intent to their Action Plan
         *
         * $actionplan_in_id:   Determines if we're adding as a Step to an existing Action Plan ($actionplan_in_id > 0)
         *                      OR if we're adding as a new Student Intent ($actionplan_in_id = 0)
         *
         * */


        //Check to see if this intent has already been added to this student's Action Plan:
        $dup_actionplans = $this->Database_model->ln_fetch(array(
            'ln_type_entity_id IN ('.join(',',$this->config->item('en_ids_6107')).')' => null, //Student Action Plan
            'ln_miner_entity_id' => $ln_miner_entity_id, //Belongs to this Student
            'ln_child_intent_id' => $in_append_id,
            'ln_status >=' => 0, //New+
        ), array('in_child'));

        if(count($dup_actionplans) > 0){
            //This has already been added and cannot add again, inform student and abort:

            $this->Chat_model->dispatch_message(
                'The intention to '.$dup_actionplans[0]['in_outcome'].' has already been added to your action plan ' . echo_time_difference(strtotime($dup_actionplans[0]['ln_timestamp'])) . ' ago so it cannot be added again.',
                array('en_id' => $ln_miner_entity_id),
                true,
                array()
            );

            return false;
        }


        //Fetch intent that's being appended:
        $append_ins = $this->Database_model->in_fetch(array(
            'in_id' => $in_append_id,
            'in_status' => 2,
        ));
        if(count($append_ins) == 0){
            //Ooooopsi, we were unable to fetch this intention as it's likely not published?
            $this->Database_model->ln_create(array(
                'ln_parent_entity_id' => $ln_miner_entity_id,
                'ln_child_intent_id' => $in_append_id,
                'ln_content' => 'actionplan_append_in() failed to locate child Student Intent',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));

            return false;
        }


        //Determine where we are adding this intention to:
        if($actionplan_in_id > 0){

            //We're adding as a Step to an existing Action Plan, let's fetch that:
            $current_actionplans = $this->Database_model->ln_fetch(array(
                'ln_type_entity_id' => 4559, //Completed Step
                'ln_miner_entity_id' => $ln_miner_entity_id, //Belongs to this Student
                'ln_child_intent_id' => $actionplan_in_id,
                'ln_status >=' => 0, //New+
            ));

            if(count($current_actionplans) < 1){

                //Ooooopsi, we were unable to locate this intention in the student Action Plan:
                $this->Database_model->ln_create(array(
                    'ln_parent_entity_id' => $ln_miner_entity_id,
                    'ln_parent_intent_id' => $actionplan_in_id,
                    'ln_child_intent_id' => $in_append_id,
                    'ln_content' => 'actionplan_append_in() failed to locate parent Student Intent',
                    'ln_type_entity_id' => 4246, //Platform Error
                    'ln_miner_entity_id' => 1, //Shervin/Developer
                ));

                return false;
            }

            //Now examine the completion requirements and child entities for this intent:
            $message_in_requirements = $this->Matrix_model->in_req_completion($append_ins[0]);
            $child_ins = $this->Database_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'in_status' => 2, //Published
                'ln_type_entity_id' => 4228, //Fixed intent links only
                'ln_parent_intent_id' => $in_append_id,
            ), array('in_child'));

            //Determine if the Student needs to do more work to complete this intention:
            $needs_more_work = ( $message_in_requirements || count($child_ins) > 0 );

            //Add all steps recursively down:
            $this->Matrix_model->in_fetch_recursive($in_append_id, true, false, $current_actionplans[0]);

            //Try to mark intent as complete (might not be depending on how many new intents where added as a result of the OR answer):
            $this->Matrix_model->actionplan_complete_recursive_up($current_actionplans[0], ($needs_more_work ? 1 /* drafting */ : null));

        } else {

            //New Student Intention:
            $actionplan = $this->Database_model->ln_create(array(
                'ln_status' => 0, //New
                'ln_type_entity_id' => 4235, //Student Intent
                'ln_miner_entity_id' => $ln_miner_entity_id,
                'ln_child_intent_id' => $in_append_id,
                'ln_order' => 1 + $this->Database_model->ln_max_order(array( //Append to the end of existing Student Intents
                    'ln_type_entity_id' => 4235, //Student Intent
                    'ln_status >=' => 0, //New+
                    'ln_miner_entity_id' => $ln_miner_entity_id,
                )),
            ));

            //Add all steps recursively down:
            $this->Matrix_model->in_fetch_recursive($in_append_id, true, false, $actionplan);

        }

        //Successful:
        return true;

    }

    function in_fetch_recursive($in_id, $direction_is_downward, $update_metadata = false, $actionplan = array(), $previous_in = array(), $metadata_aggregate = null)
    {

        /*
         *
         * The nature of the Intent tree is best suited for a recursive function
         * that will travel up/down and fetch all intent branches.
         *
         * Inputs:
         *
         * - $in_id:                    The point to get started in the tree
         *
         * - $direction_is_downward:    Whether to go up or down, sets the direction
         *
         * - $update_metadata:          Whether or not to update a copy of the
         *                              $metadata_this array into in_metadata
         *
         * - $actionplan:               The Action Plan object which if provided, will
         *                              get this function to create a copy of the intents
         *                              of $in_id downwards
         *
         * - $previous_in:              Keeps track of the state of recursion by passing
         *                              down/up the previous intent
         *
         * - $metadata_aggregate:       A variable used to addup the $metadata_this
         *                              values that will eventually be stored in in_metadata
         *
         * */


        //Are we also caching intent tree to the Action Plan? If so, analyze the $actionplan:
        if(count($actionplan) > 0) {

            //Yes, we are adding to Action Plan:
            $add_to_actionplan = true;

            //Is $actionplan referencing an Intent or Step?
            $is_actionplan_intent = ($actionplan['ln_type_entity_id']==4235);

            //Determine Action Plan ID based on type:
            $actionplan_ln_id = ( $is_actionplan_intent ? $actionplan['ln_id'] : $actionplan['ln_parent_link_id']);

        } else {

            //Nope:
            $add_to_actionplan = false;

        }


        //Do basic input validation:
        if ($in_id < 1) {
            //Invalid Intent ID:
            return false;
        } elseif ($add_to_actionplan && (!$direction_is_downward || $update_metadata)) {
            //Adding Completed Steps for a given intention only works downwards and should not update DB concurrently:
            return false;
        }


        //Calculate metadata variables:
        $metadata_this = array(

            //Fetch for New+ intents:
            '___tree_active_count' => 0, //A count of all active (in_status >= 0) intents within the tree
            '___metadatas_count' => 0, //A count of all messages for this intent only
            'in_tree' => array(), //Fetches the intent tree with its full 2-dimensional & hierarchical beauty
            'in_flat_tree' => array(), //Puts all the tree's intent IDs in a flat array, useful for quick processing
            'in_flat_unique_published_tree' => array(), //Unique IDs

            //Fetched for Published Intents:
            '___metadata_tree_count' => 0, //A count of all messages for all tree intents that are published
            '___tree_min_seconds_cost' => 0, //The minimum number of seconds required to complete tree
            '___tree_max_seconds' => 0, //The maximum number of seconds required to complete tree
            '___tree_min_cost' => 0, //The minimum cost of third-party product purchases recommended to complete tree
            '___tree_max_cost' => 0, //The maximum cost of third-party product purchases recommended to complete tree
            '___tree_experts' => array(), //Expert references across all contributions
            '___tree_miners' => array(), //miner references considering Intent Notes
            '___tree_contents' => array(), //Content types entity references on messages
            'metadatas_updated' => 0, //Keeps count of database metadata fields that were not in sync with the latest version of the cahced data

        );

        if (!$metadata_aggregate) {
            //First level, no aggregate yet, set $metadata_this as the starting point to then start aggregating:
            $metadata_aggregate = $metadata_this;
        }

        //Are we 1+ recursions deep? If so, we'll have $previous_in set
        if (isset($previous_in['ln_id'])) {

            //Yes, so now we can fetch children:

            if ($direction_is_downward) {

                //Fetch children:
                $ins = $this->Database_model->ln_fetch(array(
                    'ln_status' => 2, //Published
                    'in_status >=' => 0, //New+
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_id' => $previous_in['ln_id'],
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC')); //Child intents must be ordered

            } else {

                //Fetch parents:
                $ins = $this->Database_model->ln_fetch(array(
                    'ln_status' => 2, //Published
                    'in_status >=' => 0, //New+
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_id' => $previous_in['ln_id'],
                ), array('in_parent')); //Note that parents do not need any sorting, since we only sort child intents

            }

        } else {

            //This is the very first recursion, fetch intention itself as we don't have any links yet:
            $ins = $this->Database_model->in_fetch(array(
                'in_id' => $in_id,
            ));

        }


        //We should have found an item by now:
        if (count($ins) < 1) {
            return false;
        }

        //Set the current intent:
        $this_in = $ins[0];

        //Terminate Completed Step adding for conditional intent links and unpublished intents:
        $is_conditional = (isset($this_in['ln_type_entity_id']) && $this_in['ln_type_entity_id']==4229);
        $is_unpublished = ($this_in['in_status'] < 2);
        if ($add_to_actionplan && ($is_conditional || $is_unpublished)) {
            return false;
        }

        //Always add intent to the flat intent tree which is part of the metadata:
        array_push($metadata_this['in_flat_tree'], intval($in_id));

        //Add to published flat tree if not already there:
        if ($this_in['in_status'] == 2 && !in_array(intval($in_id), $metadata_this['in_flat_unique_published_tree'])) {
            array_push($metadata_this['in_flat_unique_published_tree'], intval($in_id));
        }

        //Are we adding steps to Action Plan?
        if ($add_to_actionplan) {

            /*
             *
             * Ok so there are two general scenarios where we would be adding intents to an Action Plan:
             *
             * 1) Student Intention: in this scenario we would
             *    add a new intent to the Student Action Plan.
             *    The Student Intent would already be added
             *    before calling this function and then passed on
             *    here where $is_actionplan_intent = TRUE
             *
             * 2) Completed Steps by answering OR branches:
             *    In this case we would be appending $in_id to
             *    an existing Completed Step once the student
             *    selects their answer to an OR branch.
             *
             * */

            if(isset($this_in['ln_id'])){

                /*
                 *
                 * We are 1+ levels deep in recursion, so
                 * we need to add this step in either
                 * scenario above...
                 *
                 * */

                $this->Database_model->ln_create(array(
                    'ln_status' => 0, //New
                    'ln_type_entity_id' => 4559, //Completed Step
                    'ln_miner_entity_id' => $actionplan['ln_miner_entity_id'], //Action Plan owner
                    'ln_parent_intent_id' => $this_in['ln_parent_intent_id'],
                    'ln_child_intent_id' => $this_in['ln_child_intent_id'],
                    'ln_order' => $this_in['ln_order'],
                    'ln_parent_link_id' => $actionplan_ln_id,
                ));

            } elseif(!$is_actionplan_intent){

                /*
                 * We are at the very first intent, so we would
                 * only add if this is Scenario 2) which means
                 * $is_actionplan_intent = FALSE as we're
                 * appending to an existing Completed Step.
                 *
                 * */

                $this->Database_model->ln_create(array(
                    'ln_status' => 0, //New
                    'ln_type_entity_id' => 4559, //Completed Step
                    'ln_miner_entity_id' => $actionplan['ln_miner_entity_id'],
                    'ln_parent_intent_id' => $actionplan['ln_child_intent_id'],
                    'ln_child_intent_id' => $this_in['in_id'],
                    'ln_order' => 1, //OR Branches would only have a single response...
                    'ln_parent_link_id' => $actionplan_ln_id,
                ));

            }
        }


        //Terminate OR branches for Action Plan caching:
        if (isset($this_in['ln_id']) && $this_in['in_type']==1 && $add_to_actionplan) {
            /*
             *
             * We do this as we don't know which OR path will be
             * chosen by Student so no point in adding every branch
             * possible! We will then add a new Student Intent
             * every time an OR branch poth is chosen.
             *
             * */
            return false;
        }


        //A recursive function to fetch all Tree for a given intent, either upwards or downwards
        if ($direction_is_downward) {

            //Fetch children:
            $fetch_tree_ins = $this->Database_model->ln_fetch(array(
                'ln_parent_intent_id' => $in_id,
                'ln_status' => 2, //Published
                'in_status >=' => 0, //New+
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            ), array('in_child'), 0, 0, array('ln_order' => 'ASC')); //Child intents must be ordered

        } else {

            //Fetch parents:
            $fetch_tree_ins = $this->Database_model->ln_fetch(array(
                'ln_child_intent_id' => $in_id,
                'ln_status' => 2, //Published
                'in_status >=' => 0, //New+
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            ), array('in_parent')); //Note that parents do not need any sorting, since we only sort child intents

        }


        //Do we have any next level intents (up or down)?
        if (count($fetch_tree_ins) > 0) {

            //$resource_estimates are determined based on the intent's AND/OR type:
            $resource_estimates = array(
                'in___tree_min_seconds_cost' => null,
                'in___tree_max_seconds' => null,
                'in___tree_min_cost' => null,
                'in___tree_max_cost' => null,
            );

            foreach ($fetch_tree_ins as $current_in) {

                if (in_array($current_in['in_id'], $metadata_aggregate['in_flat_tree'])) {

                    //Duplicate intent detected within tree
                    //terminate function to prevent an infinite loop:
                    return false;

                } else {

                    //Recursively go the next level (either up or down):
                    $recursion = $this->Matrix_model->in_fetch_recursive($current_in['in_id'], $direction_is_downward, $update_metadata, $actionplan, $current_in, $metadata_this);

                    if (!$recursion) {
                        continue;
                    }

                    //Addup if any:
                    $metadata_this['___tree_active_count'] += $recursion['___tree_active_count'];
                    array_push($metadata_this['in_flat_tree'], $recursion['in_flat_tree']);
                    array_push($metadata_this['in_flat_unique_published_tree'], $recursion['in_flat_unique_published_tree']);
                    array_push($metadata_this['in_tree'], $recursion['in_tree']);


                    //Abort if not yet published:
                    if ($current_in['in_status'] < 2) {
                        continue;
                    }


                    //Do calculations based on intent type (AND or OR)
                    if ($this_in['in_type']) {
                        //OR Branch, figure out the logic:
                        if ($recursion['___tree_min_seconds_cost'] < $resource_estimates['in___tree_min_seconds_cost'] || is_null($resource_estimates['in___tree_min_seconds_cost'])) {
                            $resource_estimates['in___tree_min_seconds_cost'] = $recursion['___tree_min_seconds_cost'];
                        }
                        if ($recursion['___tree_max_seconds'] > $resource_estimates['in___tree_max_seconds'] || is_null($resource_estimates['in___tree_max_seconds'])) {
                            $resource_estimates['in___tree_max_seconds'] = $recursion['___tree_max_seconds'];
                        }
                        if ($recursion['___tree_min_cost'] < $resource_estimates['in___tree_min_cost'] || is_null($resource_estimates['in___tree_min_cost'])) {
                            $resource_estimates['in___tree_min_cost'] = $recursion['___tree_min_cost'];
                        }
                        if ($recursion['___tree_max_cost'] > $resource_estimates['in___tree_max_cost'] || is_null($resource_estimates['in___tree_max_cost'])) {
                            $resource_estimates['in___tree_max_cost'] = $recursion['___tree_max_cost'];
                        }
                    } else {
                        //AND Branch, add them all up:
                        $resource_estimates['in___tree_min_seconds_cost'] += intval($recursion['___tree_min_seconds_cost']);
                        $resource_estimates['in___tree_max_seconds'] += intval($recursion['___tree_max_seconds']);
                        $resource_estimates['in___tree_min_cost'] += number_format($recursion['___tree_min_cost'], 2);
                        $resource_estimates['in___tree_max_cost'] += number_format($recursion['___tree_max_cost'], 2);
                    }


                    if ($update_metadata) {

                        //Update DB requested:
                        $metadata_this['___metadata_tree_count'] += $recursion['___metadata_tree_count'];
                        $metadata_this['metadatas_updated'] += $recursion['metadatas_updated'];

                        //Addup unique experts:
                        foreach ($recursion['___tree_experts'] as $en_id => $tex) {
                            //Is this a new expert?
                            if (!isset($metadata_this['___tree_experts'][$en_id])) {
                                //Yes, add them to the list:
                                $metadata_this['___tree_experts'][$en_id] = $tex;
                            }
                        }

                        //Addup unique miners:
                        foreach ($recursion['___tree_miners'] as $en_id => $tet) {
                            //Is this a new expert?
                            if (!isset($metadata_this['___tree_miners'][$en_id])) {
                                //Yes, add them to the list:
                                $metadata_this['___tree_miners'][$en_id] = $tet;
                            }
                        }

                        //Addup content types:
                        foreach ($recursion['___tree_contents'] as $type_en_id => $current_us) {
                            foreach ($current_us as $en_id => $u_obj) {
                                if (!isset($metadata_this['___tree_contents'][$type_en_id][$en_id])) {
                                    //Yes, add them to the list:
                                    $metadata_this['___tree_contents'][$type_en_id][$en_id] = $u_obj;
                                }
                            }
                        }
                    }
                }
            }

            //Addup the totals from this tree:
            $metadata_this['___tree_min_seconds_cost'] += $resource_estimates['in___tree_min_seconds_cost'];
            $metadata_this['___tree_max_seconds'] += $resource_estimates['in___tree_max_seconds'];
            $metadata_this['___tree_min_cost'] += $resource_estimates['in___tree_min_cost'];
            $metadata_this['___tree_max_cost'] += $resource_estimates['in___tree_max_cost'];
        }


        //Increase metadata counters for this intent:
        $metadata_this['___tree_active_count']++;
        $this_in['___tree_active_count'] = $metadata_this['___tree_active_count'];

        $metadata_this['___tree_min_seconds_cost'] += intval($this_in['in_seconds_cost']);
        $metadata_this['___tree_max_seconds'] += intval($this_in['in_seconds_cost']);
        $metadata_this['___tree_min_cost'] += number_format(doubleval($this_in['in_dollar_cost']), 2);
        $metadata_this['___tree_max_cost'] += number_format(doubleval($this_in['in_dollar_cost']), 2);

        //Set the data for this intent:
        $this_in['___tree_min_seconds_cost'] = $metadata_this['___tree_min_seconds_cost'];
        $this_in['___tree_max_seconds'] = $metadata_this['___tree_max_seconds'];
        $this_in['___tree_min_cost'] = $metadata_this['___tree_min_cost'];
        $this_in['___tree_max_cost'] = $metadata_this['___tree_max_cost'];


        //Count messages only if DB updating:
        if ($update_metadata) {

            $this_in['___tree_experts'] = array();
            $this_in['___tree_miners'] = array();
            $this_in['___tree_contents'] = array();

            //Fetch Intent Notes to see who is involved:
            $in__messages = $this->Database_model->ln_fetch(array(
                'ln_status >=' => 0, //New+
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
                'ln_child_intent_id' => $this_in['in_id'],
            ), array('en_miner'), 0, 0, array('ln_order' => 'ASC'));

            $this_in['___metadatas_count'] = count($in__messages);
            $metadata_this['___metadata_tree_count'] += $this_in['___metadatas_count'];
            $this_in['___metadata_tree_count'] = $metadata_this['___metadata_tree_count'];


            $parent_ids = array();

            if ($this_in['in_status'] >= 2) {
                foreach ($in__messages as $ln) {

                    //Who are the Miners of this message?
                    if (!in_array($ln['ln_miner_entity_id'], $parent_ids)) {
                        array_push($parent_ids, $ln['ln_miner_entity_id']);
                    }

                    //Check the Miners of this message in the miner array:
                    if (!isset($this_in['___tree_miners'][$ln['ln_miner_entity_id']])) {
                        //Add the entire message which would also hold the miner details:
                        $this_in['___tree_miners'][$ln['ln_miner_entity_id']] = $ln;
                    }
                    //How about the parent of this one?
                    if (!isset($metadata_this['___tree_miners'][$ln['ln_miner_entity_id']])) {
                        //Yes, add them to the list:
                        $metadata_this['___tree_miners'][$ln['ln_miner_entity_id']] = $ln;
                    }


                    //Does this message have any entity references?
                    if ($ln['ln_parent_entity_id'] > 0) {

                        //Add the reference it self:
                        if (!in_array($ln['ln_parent_entity_id'], $parent_ids)) {
                            array_push($parent_ids, $ln['ln_parent_entity_id']);
                        }

                        //Yes! Let's see if any of the parents/creators are industry experts:
                        $ens = $this->Database_model->en_fetch(array(
                            'en_id' => $ln['ln_parent_entity_id'],
                        ), array('en__parents'));

                        if (isset($ens[0]) && count($ens[0]['en__parents']) > 0) {
                            //We found it, let's loop through the parents and aggregate their IDs for a single search:
                            foreach ($ens[0]['en__parents'] as $en) {

                                //We only accept published parent entities:
                                if ($en['en_status'] < 2) {
                                    //Not yet ready:
                                    continue;
                                }

                                //Is this a particular content type?
                                if (in_array($en['en_id'], $this->config->item('en_ids_3000'))) {
                                    //yes! Add it to the list if it does not already exist:
                                    if (!isset($this_in['___tree_contents'][$en['en_id']][$ens[0]['en_id']])) {
                                        $this_in['___tree_contents'][$en['en_id']][$ens[0]['en_id']] = $ens[0];
                                    }

                                    //How about the parent tree?
                                    if (!isset($metadata_this['___tree_contents'][$en['en_id']][$ens[0]['en_id']])) {
                                        $metadata_this['___tree_contents'][$en['en_id']][$ens[0]['en_id']] = $ens[0];
                                    }
                                }

                                if (!in_array($en['en_id'], $parent_ids)) {
                                    array_push($parent_ids, $en['en_id']);
                                }
                            }
                        }
                    }
                }
            }


            //Who was involved in mining this content?
            if (count($parent_ids) > 0) {

                //Lets make a query search to see how many of those involved are industry experts:
                $expert_ens = $this->Database_model->ln_fetch(array(
                    'ln_parent_entity_id' => 3084, //Industry expert entity
                    'ln_child_entity_id IN (' . join(',', $parent_ids) . ')' => null,
                    'ln_status' => 2, //Published
                ), array('en_child'));

                //Put unique IDs in array key for faster searching:
                foreach ($expert_ens as $en) {
                    if (!isset($this_in['___tree_experts'][$en['en_id']])) {
                        $this_in['___tree_experts'][$en['en_id']] = $en;
                    }
                }
            }


            //Did we find any new industry experts?
            if (count($this_in['___tree_experts']) > 0) {

                //Yes, lets add them uniquely to the mother array assuming they are not already there:
                foreach ($this_in['___tree_experts'] as $new_ixs) {
                    //Is this a new expert?
                    if (!isset($metadata_this['___tree_experts'][$new_ixs['en_id']])) {
                        //Yes, add them to the list:
                        $metadata_this['___tree_experts'][$new_ixs['en_id']] = $new_ixs;
                    }
                }
            }
        }

        array_push($metadata_this['in_tree'], $this_in);


        //Flatten intent ID array:
        $result = array();
        array_walk_recursive($metadata_this['in_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $metadata_this['in_flat_tree'] = $result;


        $result = array();
        array_walk_recursive($metadata_this['in_flat_unique_published_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $metadata_this['in_flat_unique_published_tree'] = $result;



        if ($update_metadata) {

            //Assign aggregates:
            $this_in['___tree_experts'] = $metadata_this['___tree_experts'];
            $this_in['___tree_miners'] = $metadata_this['___tree_miners'];
            $this_in['___tree_contents'] = $metadata_this['___tree_contents'];

            //Start sorting:
            if (is_array($this_in['___tree_experts']) && count($this_in['___tree_experts']) > 0) {
                usort($this_in['___tree_experts'], 'sort_by_en_trust_score');
            }
            if (is_array($this_in['___tree_miners']) && count($this_in['___tree_miners']) > 0) {
                usort($this_in['___tree_miners'], 'sort_by_en_trust_score');
            }
            foreach ($this_in['___tree_contents'] as $type_en_id => $current_us) {
                if (isset($this_in['___tree_contents'][$type_en_id]) && count($this_in['___tree_contents'][$type_en_id]) > 0) {
                    usort($this_in['___tree_contents'][$type_en_id], 'sort_by_en_trust_score');
                }
            }

            //Update DB only if any of these metadata fields have changed:
            $metadata = unserialize($this_in['in_metadata']);
            if (!(
                intval($this_in['___tree_min_seconds_cost']) == intval(@$metadata['in__tree_min_seconds']) &&
                intval($this_in['___tree_max_seconds']) == intval(@$metadata['in__tree_max_seconds']) &&
                number_format($this_in['___tree_min_cost'], 2) == number_format(@$metadata['in__tree_min_cost'], 2) &&
                number_format($this_in['___tree_max_cost'], 2) == number_format(@$metadata['in__tree_max_cost'], 2) &&
                ((!@$metadata['in__tree_experts'] && count($this_in['___tree_experts']) < 1) || (serialize($this_in['___tree_experts']) == @$metadata['in__tree_experts'])) &&
                ((!@$metadata['in__tree_miners'] && count($this_in['___tree_miners']) < 1) || (serialize($this_in['___tree_miners']) == @$metadata['in__tree_miners'])) &&
                ((!@$metadata['in__tree_contents'] && count($this_in['___tree_contents']) < 1) || (serialize($this_in['___tree_contents']) == @$metadata['in__tree_contents'])) &&
                ((!@$metadata['in__tree_in_published'] && count($metadata_this['in_flat_unique_published_tree']) < 1) || (serialize($metadata_this['in_flat_unique_published_tree']) == @$metadata['in__tree_in_published'])) &&
                $this_in['___tree_active_count'] == @$metadata['in__tree_in_active_count'] &&
                $this_in['___metadatas_count'] == @$metadata['in__metadata_count'] &&
                $this_in['___metadata_tree_count'] == @$metadata['in__message_tree_count']
            )) {

                //Something was not up to date, let's update:
                if ($this->Matrix_model->metadata_single_update('in', $this_in['in_id'], array(

                    'in__tree_min_seconds' => intval($this_in['___tree_min_seconds_cost']),
                    'in__tree_max_seconds' => intval($this_in['___tree_max_seconds']),

                    'in__tree_min_cost' => number_format($this_in['___tree_min_cost'], 2),
                    'in__tree_max_cost' => number_format($this_in['___tree_max_cost'], 2),

                    'in__tree_in_active_count' => $this_in['___tree_active_count'],
                    'in__tree_in_published' => $metadata_this['in_flat_unique_published_tree'],
                    'in__metadata_count' => $this_in['___metadatas_count'],
                    'in__message_tree_count' => $this_in['___metadata_tree_count'],
                    'in__tree_experts' => $this_in['___tree_experts'],
                    'in__tree_miners' => $this_in['___tree_miners'],
                    'in__tree_contents' => $this_in['___tree_contents'],
                ))) {
                    //Yes update was successful:
                    $metadata_this['metadatas_updated']++;
                }
            }
        }


        //Return data:
        return $metadata_this;
    }

    function actionplan_completion_rate($in, $miner_en_id)
    {
        //Determine Action Plan completion rate:
        $in_metadata = unserialize($in['in_metadata']);

        if(!isset($in_metadata['in__tree_in_published']) || count($in_metadata['in__tree_in_published']) < 1){

            //Should not happen, log error:
            $this->Database_model->ln_create(array(
                'ln_content' => 'Detected student Action Plan without in__tree_in_published value!',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_parent_entity_id' => $miner_en_id,
                'ln_child_intent_id' => $in['in_id'],
            ));

            return 0;

        } else {

            //Fetch total completed & skipped:
            $completed_steps = $this->Database_model->ln_fetch(array(
                'ln_type_entity_id' => 4559, //Completed Step
                'ln_miner_entity_id' => $miner_en_id, //Belongs to this Student
                'ln_child_intent_id IN (' . join(',', $in_metadata['in__tree_in_published']) . ')' => null,
                'ln_status NOT IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //complete
            ), array(), 0, 0, array(), 'COUNT(ln_id) as completed_steps');

            return round($completed_steps[0]['completed_steps']/count($in_metadata['in__tree_in_published'])*100);
        }

    }


    function metadata_single_update($obj_type, $obj_id, $new_fields, $absolute_adjustment = true)
    {

        /*
         *
         * Enables the easy manipulation of the text metadata field which holds cache data for developers
         *
         * $obj_type:               Either in, en or tr
         *
         * $obj:                    The Entity, Intent or Link itself.
         *                          We're looking for the $obj ID and METADATA
         *
         * $new_fields:             The new array of metadata fields to be Set,
         *                          Updated or Removed (If set to null)
         *
         * $absolute_adjustment:    TRUE by default, meaning that values within
         *                          $new_fields will be updated as they are. If
         *                          this is FALSE, then this would be a relative
         *                          adjustment (add or subtract) compared to what
         *                          is already in the metadata field of $obj.
         *
         * */

        if (!in_array($obj_type, array('in', 'en', 'ln')) || $obj_id < 1 || count($new_fields) < 1) {
            return false;
        }

        //Fetch metadata for this object:
        if ($obj_type == 'in') {

            $db_objects = $this->Database_model->in_fetch(array(
                $obj_type . '_id' => $obj_id,
            ));

        } elseif ($obj_type == 'en') {

            $db_objects = $this->Database_model->en_fetch(array(
                $obj_type . '_id' => $obj_id,
            ));

        } elseif ($obj_type == 'ln') {

            $db_objects = $this->Database_model->ln_fetch(array(
                $obj_type . '_id' => $obj_id,
            ));

        }

        if (count($db_objects) < 1) {
            return false;
        }


        //Prepare newly fetched metadata:
        if (strlen($db_objects[0][$obj_type . '_metadata']) > 0) {
            $metadata = unserialize($db_objects[0][$obj_type . '_metadata']);
        } else {
            $metadata = array();
        }

        //Go through all the new fields and see if they differ from current metadata fields:
        foreach ($new_fields as $metadata_key => $metadata_value) {
            if (!$absolute_adjustment) {

                //We need to do a relative adjustment:
                $metadata[$metadata_key] = (isset($metadata[$metadata_key]) ? $metadata[$metadata_key] : 0) + $metadata_value;

            } else {

                //We are doing an absolute adjustment if needed:
                if (is_null($metadata_value) && isset($metadata[$metadata_key])) {

                    //User asked to remove this value:
                    unset($metadata[$metadata_key]);

                } elseif (!is_null($metadata_value) && (!isset($metadata[$metadata_key]) || !($metadata[$metadata_key] === $metadata_value))) {

                    //Value has changed, adjust:
                    $metadata[$metadata_key] = $metadata_value;

                }
            }
        }

        //Now update DB without logging any links as this is considered a back-end update:
        if ($obj_type == 'in') {

            $affected_rows = $this->Database_model->in_update($obj_id, array(
                'in_metadata' => $metadata,
            ));

        } elseif ($obj_type == 'en') {

            $affected_rows = $this->Database_model->en_update($obj_id, array(
                'en_metadata' => $metadata,
            ));

        } elseif ($obj_type == 'ln') {

            $affected_rows = $this->Database_model->ln_update($obj_id, array(
                'ln_metadata' => $metadata,
            ));

        }

        //Should be all good:
        return $affected_rows;

    }


    function en_search_match($en_parent_id, $value)
    {

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
        $matching_entities = $this->Database_model->ln_fetch(array(
            'ln_parent_entity_id' => $en_parent_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_content' => $value,
            'ln_status >=' => 0, //Pending or Active
        ), array(), 0);


        if (count($matching_entities) == 1) {

            //Bingo, return result:
            return intval($matching_entities[0]['ln_child_entity_id']);

        } else {

            //Ooooopsi, this value did not exist! Notify the admin so we can look into this:
            $this->Database_model->ln_create(array(
                'ln_content' => 'en_search_match() found [' . count($matching_entities) . '] results as the children of en_id=[' . $en_parent_id . '] that had the value of [' . $value . '].',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_child_entity_id' => $en_parent_id,
            ));

            return 0;
        }
    }


    function actionplan_complete_recursive_up($actionplan, $force_ln_status = null)
    {

        /*
         *
         * When an intent is marked as complete OR when
         * and OR child intent is chosen by the student,
         * we need to determine of the child tree is
         * complete and adjust the status of the parent
         * intent accordingly. If the child tree is
         * completed then the parent intent can be
         * marked as complete, and if that results in the
         * grandparent tree to be completed then we have
         * to keep moving upwards until we reach a
         * grandparent tree that has an incomplete child.
         * If the child intent is not yet complete, we
         * simply have to update parent ln_status from
         * 0 to 1 to indicate that we're working on it.
         *
         * Inputs:
         *
         * $actionplan:         The Action Plan object
         *
         * $force_ln_status:    If set, would force a particular status
         *                      (usually 1) instead of ln_status=2 (complete)
         *
         * */

        //Determine Action Plan ID based on $actionplan input (Could be Intent OR Step):
        $actionplan_ln_id = ( $actionplan['ln_type_entity_id']==4235 ? $actionplan['ln_id'] : $actionplan['ln_parent_link_id'] );

        //Check to see if parent of this item is NEW, if so, we need to update its status to DRAFTING:
        $parent_ins = $this->Database_model->ln_fetch(array(
            'ln_type_entity_id' => 4559, //Completed Step
            'ln_miner_entity_id' => $actionplan['ln_miner_entity_id'],
            'ln_status' => 0, //ignore intents that are not drafting...
            'ln_child_intent_id' => $actionplan['ln_parent_intent_id'],
        ), array('in_parent'));
        if (count($parent_ins) > 0) {
            //Found it! set to DRAFTING:
            $this->Matrix_model->actionplan_update_status($parent_ins[0]['ln_id'], 1);
        }

        //See if current intent children are complete:
        $down_is_complete = true; //Start by assume its complete unless proven otherwise...

        if (!$force_ln_status) {

            /*
             *
             * For both AND/OR intents we need all children
             * to be complete if we are to mark parent as
             * complete. If not complete we will mark parent
             * as drafting instead. So lets fetch the down
             * tree and see what is happening:
             *
             * */

            $dwn_tree = $this->Matrix_model->deprecate__actionplan_fetch_recursive($actionplan_ln_id, $actionplan['ln_child_intent_id'], true);

            //Did we find any children?
            if (count($dwn_tree['actionplan_links_flat']) > 0) {

                //YES! let's see if we can find any incomplete links among all child intents:
                if (count($this->Database_model->ln_fetch(array(
                        'ln_id IN (' . join(',', $dwn_tree['actionplan_links_flat']) . ')' => null, //All children
                        'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete
                    ))) > 0) {
                    //We do have some incomplete children, so this parent intent is NOT complete:
                    $down_is_complete = false;
                }
            }
        }


        //Ok now define the new status here:
        $new_ln_status = (!is_null($force_ln_status) ? $force_ln_status : ($down_is_complete ? 2 : 1));

        //Update this intent:
        $this->Matrix_model->actionplan_update_status($actionplan['ln_id'], $new_ln_status);


        //We are done with this branch if the status = 2
        if ($new_ln_status==2) {

            /*
             *
             * Yes, down tree seems complete, now let's check
             * to see if up tree needs completion as well:
             *
             * */

            //Fetch all parents:
            $up_tree = $this->Matrix_model->deprecate__actionplan_fetch_recursive($actionplan_ln_id, $actionplan['ln_child_intent_id'], false);

            //Loop through incomplete parents to see if they should now be marked as complete:
            foreach ($this->Database_model->ln_fetch(array(
                'ln_id IN (' . join(',', $up_tree['actionplan_links_flat']) . ')' => null, //All parents
                'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete
            )) as $incomplete_parent) {

                /*
                 *
                 * For this parent ot be complete all its children
                 * now need to be complete, so let's see if we
                 * have any in-complete child intents in this
                 * student's Action Plan:
                 *
                 * */

                //Parent is complete if it has zero incomplete children:
                if (count($this->Database_model->ln_fetch(array(
                        'ln_miner_entity_id' => $actionplan['ln_miner_entity_id'],
                        'ln_parent_intent_id' => $incomplete_parent['ln_child_intent_id'],
                        'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete
                    ))) == 0) {

                    //Parent now seems to be complete:
                    $this->Matrix_model->actionplan_update_status($incomplete_parent['ln_id'], (!is_null($force_ln_status) ? $force_ln_status : 2));

                } elseif ($incomplete_parent['ln_status'] == 0) {

                    //This parent is not yet completed, but we can update the status to DRAFTING since the status is NEW:
                    $this->Matrix_model->actionplan_update_status($incomplete_parent['ln_id'], 1);

                }
            }
        }
    }


    function in_force_verb_creation($in_outcome, $ln_miner_entity_id = 0){

        //Fetch related variables:
        $outcome_words = explode(' ', $in_outcome);
        $starting_verb = trim($outcome_words[0]);
        $in_verb_entity_id = detect_starting_verb_id($in_outcome);

        //Run some checks on the intent outcome:
        if(count($outcome_words) < 3) {

            //The /force is a word, so starting verb is too short:
            return array(
                'status' => 0,
                'message' => 'Outcome must have at-least two words',
            );

        } elseif(!(substr($in_outcome, -7) == ' /force')){

            //not positioned correctly:
            return array(
                'status' => 0,
                'message' => '/force command must be the last word of the outcome',
            );

        } elseif(!en_auth(array(1281))){

            //Not a acceptable starting verb:
            return array(
                'status' => 0,
                'message' => '/force command is only available to moderators',
            );

        } elseif(strlen($starting_verb) < 2) {

            //Starting verb is too short:
            return array(
                'status' => 0,
                'message' => 'Starting verb must be at-least 2 characters long',
            );

        } elseif(!ctype_alpha($starting_verb)){

            //Not a acceptable starting verb:
            return array(
                'status' => 0,
                'message' => 'Starting verb should only consist of letters A-Z',
            );

        }

        //Create the supporting verb if not already there:
        if(!$in_verb_entity_id){

            //Add and link verb:
            $added_en = $this->Matrix_model->en_verify_create(ucwords(strtolower($starting_verb)), $ln_miner_entity_id, true);

            //Link to supported verbs:
            $this->Database_model->ln_create(array(
                'ln_miner_entity_id' => $ln_miner_entity_id,
                'ln_status' => 2, //Published
                'ln_type_entity_id' => 4230, //Raw
                'ln_parent_entity_id' => 5008, //Intent Supported Verbs
                'ln_child_entity_id' => $added_en['en']['en_id'],
            ));

            //Assign new verb ID to this intent:
            $in_verb_entity_id = $added_en['en']['en_id'];
        }


        //All good, return results:
        return array(
            'status' => 1,
            'in_cleaned_outcome' => str_replace(' /force' , '', $in_outcome),
            'in_verb_entity_id' => $in_verb_entity_id,
        );

    }


    function in_verify_create($in_outcome, $ln_miner_entity_id = 0, $in_status = 0){

        //Assign verb variables:
        $in_verb_entity_id = detect_starting_verb_id($in_outcome);

        if(substr_count($in_outcome , ' ') < 1){

            return array(
                'status' => 0,
                'message' => 'Outcome must have at-least two words',
            );

        } elseif(strlen($in_outcome) < 5){

            return array(
                'status' => 0,
                'message' => 'Outcome must be at-least 5 characters long',
            );

        } elseif(substr_count($in_outcome , '/force') > 0){

            //Force command detected, pass it on to the force function:
            $force_outcome = $this->Matrix_model->in_force_verb_creation($in_outcome, $ln_miner_entity_id);

            if(!$force_outcome['status']){
                //We had some errors in outcome structure:
                return $force_outcome['status'];
            }

            //Update forced variables:
            $in_outcome = $force_outcome['in_cleaned_outcome'];

            //Update supporting verb ID if it was not set:
            if(!$in_verb_entity_id){
                $in_verb_entity_id = $force_outcome['in_verb_entity_id'];
            }

        } elseif(!$in_verb_entity_id) {

            //Not a acceptable starting verb:
            return array(
                'status' => 0,
                'message' => 'Starting verb is not yet supported. Manage supported verbs via entity @5008'.( en_auth(array(1281)) ? ' or use the /force command to add this verb to the supported list.' : '' ),
            );

        }


        //Check to make sure it's not a duplicate outcome:
        $duplicate_outcome_ins = $this->Database_model->in_fetch(array(
            'in_status >=' => 0, //New+
            'LOWER(in_outcome)' => strtolower(trim($in_outcome)),
        ));
        if(count($duplicate_outcome_ins) > 0){
            //This is a duplicate, disallow:
            $fixed_fields = $this->config->item('fixed_fields');
            return array(
                'status' => 0,
                'message' => 'Outcome ['.$in_outcome.'] already in use by intent #'.$duplicate_outcome_ins[0]['in_id'].' with status ['.$fixed_fields['in_status'][$duplicate_outcome_ins[0]['in_status']]['s_name'].']',
            );
        }

        //Prepare recursive update:
        $in_metadata_modify = array(
            'in__tree_in_active_count' => 1, //We just added 1 new intent to this tree
        );

        //Create child intent:
        $intent_new = $this->Database_model->in_create(array(
            'in_status' => $in_status,
            'in_outcome' => trim($in_outcome),
            'in_verb_entity_id' => $in_verb_entity_id,
            'in_metadata' => $in_metadata_modify,
        ), true, $ln_miner_entity_id);

        //Sync the metadata of this new intent:
        $this->Matrix_model->in_fetch_recursive($intent_new['in_id'], true, true);

        //Return success:
        return array(
            'status' => 1,
            'in' => $intent_new,
            'in_metadata_modify' => $in_metadata_modify,
        );

    }

    function en_verify_create($en_name, $ln_miner_entity_id = 0, $force_creation = false, $en_status = 0, $en_icon = null, $en_psid = null){

        if(strlen($en_name)<2){
            return array(
                'status' => 0,
                'message' => 'Entity name must be at-least 2 characters long',
            );
        }

        //Check to make sure name is not duplicate:
        $duplicate_name_ens = $this->Database_model->en_fetch(array(
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
        $entity_new = $this->Database_model->en_create(array(
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
            $this->Database_model->ln_create(array(
                'ln_content' => 'en_messenger_add() got called without a valid Facebook $psid variable',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));
            return false;
        }

        //Call facebook messenger API and get user graph profile:
        $graph_fetch = $this->Chat_model->facebook_graph('GET', '/' . $psid, array());
        $fetched_fb_info = ($graph_fetch['status'] && isset($graph_fetch['ln_metadata']['result']['first_name']) && strlen($graph_fetch['ln_metadata']['result']['first_name']) > 0);


        //Did we find the profile from FB?
        if (!$fetched_fb_info) {

            /*
             *
             * No profile on Facebook! This happens when user has logged
             * into messenger with their phone number or for any reason
             * that Facebook does not provide profile details.
             *
             * */

            //Create student entity:
            $added_en = $this->Matrix_model->en_verify_create('Student '.rand(100000000, 999999999), 0, true, 2, null, $psid);

        } else {

            //We did find the profile, move ahead:
            $fb_profile = $graph_fetch['ln_metadata']['result'];

            //Create student entity with their Facebook Graph name:
            $added_en = $this->Matrix_model->en_verify_create($fb_profile['first_name'] . ' ' . $fb_profile['last_name'], 0, true, 2, null, $psid);

            //Split locale variable into language and country like "EN_GB" for English in England
            $locale = explode('_', $fb_profile['locale'], 2);

            //Try to match Facebook profile data to internal entities and create links for the ones we find:
            foreach (array(
                         $this->Matrix_model->en_search_match(3289, $fb_profile['timezone']), //Timezone
                         $this->Matrix_model->en_search_match(3290, strtolower(substr($fb_profile['gender'], 0, 1))), //Gender either m/f
                         $this->Matrix_model->en_search_match(3287, strtolower($locale[0])), //Language
                         $this->Matrix_model->en_search_match(3089, strtolower($locale[1])), //Country
                     ) as $ln_parent_entity_id) {

                //Did we find a relation? Create the link:
                if ($ln_parent_entity_id > 0) {

                    //Create new link:
                    $this->Database_model->ln_create(array(
                        'ln_type_entity_id' => 4230, //Raw link
                        'ln_miner_entity_id' => $added_en['en']['en_id'], //Student gets credit as miner
                        'ln_parent_entity_id' => $ln_parent_entity_id,
                        'ln_child_entity_id' => $added_en['en']['en_id'],
                    ));

                }
            }

            //Create link to save profile picture:
            $this->Database_model->ln_create(array(
                'ln_status' => 0, //New
                'ln_type_entity_id' => 4299, //Updated Profile Picture
                'ln_miner_entity_id' => $added_en['en']['en_id'], //The Student who added this
                'ln_content' => $fb_profile['profile_pic'], //Image to be saved to Mench CDN
            ));

        }


        //Note that new entity link is already logged in the entity creation function
        //Now create more relevant links:

        //Add default Notification Level:
        $this->Database_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_miner_entity_id' => $added_en['en']['en_id'],
            'ln_parent_entity_id' => 4456, //Receive Regular Notifications (Student can change later on...)
            'ln_child_entity_id' => $added_en['en']['en_id'],
        ));

        //Add them to Students group:
        $this->Database_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_miner_entity_id' => $added_en['en']['en_id'],
            'ln_parent_entity_id' => 4430, //Mench Student
            'ln_child_entity_id' => $added_en['en']['en_id'],
        ));

        //Add them to People entity:
        $this->Database_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_miner_entity_id' => $added_en['en']['en_id'],
            'ln_parent_entity_id' => 1278, //People
            'ln_child_entity_id' => $added_en['en']['en_id'],
        ));


        if(!$fetched_fb_info){
            //Let them know to complete their profile:
            $this->Chat_model->dispatch_message(
                'Hi stranger! Let\'s get started by completing your profile information by opening the My Account tab in the menu below. /link:Open 👤My Account:https://mench.com/messenger/account',
                $added_en['en'],
                true
            );
        }

        //Return entity object:
        return $added_en['en'];

    }


    function in_link_or_create($actionplan_in_id, $is_parent, $in_outcome, $link_in_id, $next_level, $ln_miner_entity_id)
    {

        /*
         *
         * The main intent creation function that would create
         * appropriate links and return the intent view.
         *
         * Either creates an intent link between $actionplan_in_id & $link_in_id
         * (IF $link_in_id>0) OR will create a new intent with outcome $in_outcome
         * and link it to $actionplan_in_id (In this case $link_in_id will be 0)
         *
         * p.s. Inputs have already been validated via intents/in_link_or_create() function
         *
         * */

        //Validate Original intent:
        $parent_ins = $this->Database_model->in_fetch(array(
            'in_id' => intval($actionplan_in_id),
        ));

        if (count($parent_ins) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            );
        } elseif (!in_array($next_level, array(2,3))) {
            return array(
                'status' => 0,
                'message' => 'Intent level must be either 2 or 3.',
            );
        } elseif ($parent_ins[0]['in_status'] < 0) {
            return array(
                'status' => 0,
                'message' => 'Cannot link to removed intents',
            );
        }

        if (intval($link_in_id) > 0) {

            //We are linking to $link_in_id, We are NOT creating any new intents...

            //Fetch more details on the child intent we're about to link:
            $ins = $this->Database_model->in_fetch(array(
                'in_id' => $link_in_id,
            ));

            if (count($ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Linked Intent ID',
                );
            } elseif ($ins[0]['in_status'] < 0) {
                return array(
                    'status' => 0,
                    'message' => 'Cannot link to removed intents',
                );
            }

            //All good so far, continue with linking:
            $intent_new = $ins[0];

            //check all parents as this intent cannot be duplicated with any of its parents:
            $parent_tree = $this->Matrix_model->in_fetch_recursive($actionplan_in_id, false);
            if (in_array($intent_new['in_id'], $parent_tree['in_flat_tree'])) {
                return array(
                    'status' => 0,
                    'message' => 'You cannot link to "' . $intent_new['in_outcome'] . '" as it already belongs to the parent/grandparent tree.',
                );
            }

            //Make sure this is not a duplicate intent for its parent:
            $dup_links = $this->Database_model->ln_fetch(array(
                ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $actionplan_in_id,
                ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $link_in_id,
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                'ln_status >=' => 0, //New+
            ));

            //Check for issues:
            if (count($dup_links) > 0) {

                //Ooopsi, this is a duplicate!
                return array(
                    'status' => 0,
                    'message' => '[' . $intent_new['in_outcome'] . '] is already linked here.',
                );

            } elseif ($link_in_id == $actionplan_in_id) {

                //Make sure none of the parents are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "' . $intent_new['in_outcome'] . '" as its own '.( $is_parent ? 'parent' : 'child' ).'.',
                );

            }

            //Prepare recursive update:
            $metadata = unserialize($intent_new['in_metadata']);
            //Fetch and adjust the intent tree based on these values:
            $in_metadata_modify = array(
                'in__tree_in_active_count' => (isset($metadata['in__tree_in_active_count']) ? intval($metadata['in__tree_in_active_count']) : 0),
                'in__tree_max_seconds' => (isset($metadata['in__tree_max_seconds']) ? intval($metadata['in__tree_max_seconds']) : 0),
                'in__message_tree_count' => (isset($metadata['in__message_tree_count']) ? intval($metadata['in__message_tree_count']) : 0),
            );

        } else {

            //We are NOT linking to an existing intent, but instead, we're creating a new intent:

            //See if we have the double column shortcut:
            if(substr($in_outcome, 0, 2) == '::'){

                //Yes, validate this command:
                if($is_parent){
                    return array(
                        'status' => 0,
                        'message' => 'You can use the double column shortcut for child entities only.',
                    );
                }

                //Apply shortcut and update the intent outcome:
                $parent_in_outcome_words = explode(' ', $parent_ins[0]['in_outcome']);
                $in_outcome = $parent_in_outcome_words[0].' #'.$parent_ins[0]['in_id'].' with :: '.trim(substr($in_outcome, 2));

            }

            $added_in = $this->Matrix_model->in_verify_create($in_outcome, $ln_miner_entity_id);
            if(!$added_in['status']){
                //We had an error, return it:
                return $added_in;
            } else {
                //Passon variables:
                $intent_new = $added_in['in'];
                $in_metadata_modify = $added_in['in_metadata_modify'];
            }

        }


        //Create Intent Link:
        $relation = $this->Database_model->ln_create(array(
            'ln_miner_entity_id' => $ln_miner_entity_id,
            'ln_type_entity_id' => 4228,
            ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $actionplan_in_id,
            ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
            'ln_order' => 1 + $this->Database_model->ln_max_order(array(
                    'ln_status >=' => 0, //New+
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_parent_intent_id' => ( $is_parent ? $intent_new['in_id'] : $actionplan_in_id ),
                )),
        ), true);



        //Add Up-Vote if not yet added for this miner:
        if($ln_miner_entity_id > 0){

            $ln_miner_upvotes = $this->Database_model->ln_fetch(array(
                ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $actionplan_in_id,
                ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
                'ln_parent_entity_id' => $ln_miner_entity_id,
                'ln_type_entity_id' => 4983, //Up-votes
                'ln_status >=' => 0, //New+
            ));

            if(count($ln_miner_upvotes) == 0){
                //Add new up-vote
                //No need to sync external sources via ln_create()
                $up_vote = $this->Database_model->ln_create(array(
                    'ln_miner_entity_id' => $ln_miner_entity_id,
                    'ln_parent_entity_id' => $ln_miner_entity_id,
                    'ln_type_entity_id' => 4983, //Up-votes
                    'ln_content' => '@'.$ln_miner_entity_id.' #'.( $is_parent ? $intent_new['in_id'] : $actionplan_in_id ), //Message content
                    ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $actionplan_in_id,
                    ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
                ));
            }

        }



        //Update Metadata for tree:
        $this->Matrix_model->metadata_recursive_update('in', $actionplan_in_id, $in_metadata_modify);


        //Fetch and return full data to be properly shown on the UI using the echo_in() function
        $new_ins = $this->Database_model->ln_fetch(array(
            ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $actionplan_in_id,
            ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_status >=' => 0,
            'in_status >=' => 0,
        ), array(($is_parent ? 'in_parent' : 'in_child')), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation


        //Return result:
        return array(
            'status' => 1,
            'in_child_id' => $intent_new['in_id'],
            'in_child_html' => echo_in($new_ins[0], $next_level, $actionplan_in_id, $is_parent),
            //Also append some tree data for UI modifications via JS functions:
            'in__tree_max_seconds' => (isset($in_metadata_modify['in__tree_max_seconds']) && !$is_parent ? intval($in_metadata_modify['in__tree_max_seconds']) : 0), //Seconds added because of this
            'in__tree_in_active_count' => ( $is_parent ? 0 : intval($in_metadata_modify['in__tree_in_active_count']) ), //We must have this (Either if we're linking OR creating) to show new intents in the tree
        );

    }

}