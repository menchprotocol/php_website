<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Platform_model extends CI_Model
{

    /*
     *
     * This model contains all Database functions that
     * interpret the Platform from a particular perspective
     * to gain understanding from it and to perform pre-defined
     * operations.
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function actionplan_recursive_next_step($en_id, $in){

        /*
         *
         * Searches within a student Action Plan to find
         * first incomplete step.
         *
         * */

        $in_metadata = unserialize($in['in_metadata']);
        foreach(array_flatten($in_metadata['in__metadata_common_steps']) as $common_step_in_id){

            //Is this an expansion step?
            $is_expansion = (isset($in_metadata['in__metadata_expansion_steps'][$common_step_in_id]));

            $completed_steps = $this->Database_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',' , $this->config->item('en_ids_6146')) . ')' => null,
                'ln_miner_entity_id' => $en_id, //Belongs to this Student
                'ln_parent_intent_id' => $common_step_in_id,
                'ln_status >=' => 0, //New+
            ), ( $is_expansion ? array('in_child') : array() ));

            //Have they completed this?
            if(count($completed_steps) == 0 || $completed_steps[0]['ln_status'] < 2){

                //Not completed yet, this is the next step:
                return $common_step_in_id;

            } elseif($is_expansion){

                //Completed step that has OR expansions, check recursively to see if next step within here:
                $found_in_id = $this->Platform_model->actionplan_recursive_next_step($en_id, $completed_steps[0]);

                if($found_in_id > 0){
                    return $found_in_id;
                }

            }

        }

        //Nothing found!
        return 0;

    }

    function actionplan_find_next_step($en_id, $advance_step, $send_title_message = false)
    {

        /*
         *
         * Searches for the next Action Plan step
         * and advance it IF $advance_step = TRUE
         *
         * */

        $student_intents = $this->Database_model->ln_fetch(array(
            'ln_miner_entity_id' => $en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6147')) . ')' => null, //Action Plan Intentions
            'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
            'in_status' => 2, //Published
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

        if(count($student_intents) == 0){

            if($advance_step){

                $this->Communication_model->dispatch_message(
                    'You have no intentions in your Action Plan.',
                    array('en_id' => $en_id),
                    true
                );

                //List featured intents and let them choose:
                $this->Communication_model->suggest_featured_intents($en_id);

            }

            //No Action Plans found!
            return false;

        }


        //Looop through Action Plan intentions and see what's next:
        foreach($student_intents as $student_intent){

            //Find first incomplete step for this Action Plan intention:
            $next_in_id = $this->Platform_model->actionplan_recursive_next_step($en_id, $student_intent);

            if($next_in_id > 0){
                //We found the next incomplete step, return:
                break;
            }
        }

        if($advance_step){

            //Did we find a next step?
            if($next_in_id > 0){

                if($send_title_message){

                    //Fetch and append the title to be more informative:

                    //Yes, we do have a next step, fetch it and give student more details:
                    $next_step_ins = $this->Database_model->in_fetch(array(
                        'in_id' => $next_in_id,
                    ));

                    if(substr_count($next_step_ins[0]['in_outcome'], '::')==0){
                        $this->Communication_model->dispatch_message(
                            'Let\'s '. $next_step_ins[0]['in_outcome'],
                            array('en_id' => $en_id),
                            true
                        );
                    }

                }

                //Yes, communicate it:
                $this->Platform_model->actionplan_advance_step(array('en_id' => $en_id), $next_in_id);

            } else {

                //Inform user that they are now complete with all steps:
                $this->Communication_model->dispatch_message(
                    'You have no pending steps in your Action Plan 🙌 I will keep you updated on new steps as they become available. You may also stop receiving updates by saying "stop".',
                    array('en_id' => $en_id),
                    true
                );

                //List featured intents and let them choose:
                $this->Communication_model->suggest_featured_intents($en_id);

            }
        }

        //Return next step intent or false:
        return $next_in_id;

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
        ), array(), $this->config->item('items_per_page')) as $ln) {

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
                    'ln_status' => -1, //Removed
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
                'ln_status' => -1, //Removed
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
            $added_en = $this->Platform_model->en_verify_create(( $page_title ? $page_title : $domain_analysis['url_domain_name'] ), $ln_miner_entity_id, true, 2, detect_fav_icon($domain_analysis['url_clean_domain']));
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
        $domain_entity = $this->Platform_model->en_sync_domain($url, $ln_miner_entity_id, ( $domain_analysis['url_is_root'] && $name_was_passed ? $page_title : null ));


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
                $added_en = $this->Platform_model->en_verify_create($page_title, $ln_miner_entity_id, true);
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


    function in_req_completion($in, $fb_messenger_format = false)
    {

        /*
         *
         * Sometimes to mark an intent as complete the Students might
         * need to meet certain requirements in what they submit to do so.
         * This function fetches those requirements from the Platform and
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
        $message = 'Marking as complete requires a ' . $en_all_4331[$in['in_requirement_entity_id']]['m_name'].' message which you can send me ';

        //Give clear directions to complete if Action Plan ID is provided...
        if ($fb_messenger_format) {
            $message .= 'right here using Messenger.';
        } else {
            $message .= 'on Messenger.';
        }

        //Return Student-friendly message for completion requirements:
        return $message;

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
            $this->Database_model->ln_create(array(
                'ln_content' => 'en_authenticate_psid() got called without a valid Facebook $psid variable',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));
            return false;
        }

        //Try matching Facebook PSID to existing Students:
        $ens = $this->Database_model->en_fetch(array(
            'en_status >=' => 0, //New+
            'en_psid' => $psid,
        ), array('skip_en__parents'));

        //So, did we find them?
        if (count($ens) > 0) {

            //Student found...
            return $ens[0];

        } else {

            //Student not found, create new Student:
            return $this->Platform_model->en_messenger_add($psid);

        }

    }


    function actionplan_skip_recursive_down($en_id, $in_id)
    {

        //Fetch intent common steps:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status' => 2, //Published
        ));
        if(count($ins) < 1){
            $this->Database_model->ln_create(array(
                'ln_content' => 'actionplan_skip_recursive_down() failed to locate published intent',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_parent_entity_id' => $en_id,
                'ln_parent_intent_id' => $in_id,
            ));
            return 0;
        }


        $in_metadata = unserialize( $ins[0]['in_metadata'] );

        if(!isset($in_metadata['in__metadata_common_steps'])){
            $this->Database_model->ln_create(array(
                'ln_content' => 'actionplan_skip_recursive_down() failed to locate metadata common steps',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_parent_entity_id' => $en_id,
                'ln_parent_intent_id' => $in_id,
            ));
            return 0;
        }

        //Fetch common base and expansion paths from intent metadata:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);

        //Add Action Plan Skipped Step Progression Links:
        foreach($flat_common_steps as $common_in_id){

            //Fetch current progression links, if any:
            $current_progression_links = $this->Database_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $common_in_id,
                'ln_status >=' => 0, //New+
            ));

            //Add skip link:
            $new_progression_link = $this->Database_model->ln_create(array(
                'ln_type_entity_id' => 6143, //Action Plan Skipped Step
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $common_in_id,
                'ln_status' => 2, //Published
            ));

            //Archive current progression links:
            foreach($current_progression_links as $ln){
                $this->Database_model->ln_update($ln['ln_id'], array(
                    'ln_parent_link_id' => $new_progression_link['ln_id'],
                    'ln_status' => -1,
                ), $en_id);
            }

        }

        //Return number of skipped steps:
        return count($flat_common_steps);
    }

    function in_recursive_update($in_id = 0, $in_field, $match_value, $replace_value, $ln_miner_entity_id, $filters = null, $recursive_in = null)
    {
        /*
         *
         * Updates a matching variable within an intent tree
         *
         * */

        $update_count = 0;
        if(!$filters || !is_array($filters)){
            //Set default filters:
            $filters = array(
                'ln_status >=' => 0, //New+
                'in_status >=' => 0, //New+
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            );
        }

        if($match_value==$replace_value){

            //Nothing to do here...
            return 0;

        } elseif($in_id > 0){

            //This is the first round of the recursive function:
            $ins = $this->Database_model->in_fetch(array(
                'in_id' => $in_id,
            ));

            if(count($ins) < 1){
                return false;
            }

            $recursive_in = $ins[0];

        } elseif(!$recursive_in){

            return 0;

        }

        //Go through Children FIRST:
        foreach($this->Database_model->ln_fetch(array_merge($filters , array(
            'ln_parent_intent_id' => $recursive_in['in_id'],
        )), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $in_child){

            //Run function on child:
            $update_count += $this->Platform_model->in_recursive_update(0, $in_field, $match_value, $replace_value, $ln_miner_entity_id, $filters, $in_child);

        }

        //See if we need to update this intent:
        if(isset($recursive_in[$in_field]) && $recursive_in[$in_field]==$match_value){
            //Matched! Update the filed:
            $this->Database_model->in_update($recursive_in['in_id'], array( $in_field => $replace_value ), true, $ln_miner_entity_id);
            $update_count++;
        }

        //Log Link for recursive Intent Update:
        if($in_id > 0 && $update_count > 0){
            $this->Database_model->ln_create(array(
                'ln_miner_entity_id' => $ln_miner_entity_id,
                'ln_type_entity_id' => 6226, //Intent Tree Iterated
                'ln_parent_intent_id' => $in_id,
                'ln_content' => 'Successfully updated '.$update_count.' '.echo_clean_db_name($in_field).' from ['.$match_value.'] to ['.$replace_value.']',
                'ln_metadata' => array(
                    'filters' => $filters,
                    'in_field' => $in_field,
                    'match_value' => $match_value,
                    'replace_value' => $replace_value,
                ),
            ));
        }

        return $update_count;

    }

    function in_fetch_recursive_parents($in_id, $min_level, $first_level = true){

        $grand_parents = array();

        //Fetch parents:
        foreach($this->Database_model->ln_fetch(array(
            'in_status >=' => $min_level,
            'ln_status >=' => $min_level,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_child_intent_id' => $in_id,
        ), array('in_parent')) as $in_parent){

            //Prep ID:
            $p_id = intval($in_parent['in_id']);

            //Add to appropriate array:
            if(!$first_level){
                array_push($grand_parents, $p_id);
            }

            //Fetch parents of parents:
            $recursive_parents = $this->Platform_model->in_fetch_recursive_parents($p_id, $min_level, false);

            if($first_level){
                array_push($recursive_parents, $p_id);
                $grand_parents[$p_id] = $recursive_parents;
            } elseif(!$first_level && count($recursive_parents) > 0){
                $grand_parents = array_merge($grand_parents, $recursive_parents);
            }
        }

        return $grand_parents;
    }


    function in_metadata_common_base($focus_in){

        //Set variables:
        $is_first_intent = ( !isset($focus_in['ln_id']) ); //First intent does not have a link, just the intent
        $has_or_parent = ( $focus_in['in_type']==1 );
        $or_children = array(); //To be populated only if $focus_in is an OR intent
        $metadata_this = array(
            '__in__metadata_common_steps' => array(), //The tree structure that would be shared with all students regardless of their quick replies (OR Intent Answers)
            '__in__metadata_expansion_steps' => array(), //Intents that may exist as a link to expand an Action Plan tree
        );


        //Fetch children:
        foreach($this->Database_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'in_status' => 2, //Published
            'ln_type_entity_id' => 4228, //Fixed intent links only
            'ln_parent_intent_id' => $focus_in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $in_child){

            //Determine action based on parent intent type:
            if($has_or_parent){

                //OR Intent:
                array_push($or_children, intval($in_child['in_id']));

            } else {

                //Add to common step:
                array_push($metadata_this['__in__metadata_common_steps'], intval($in_child['in_id']));

                //Run function on child:
                $child_recursion = $this->Platform_model->in_metadata_common_base($in_child);

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
            }
        }

        //Was this an OR branch that needs it's children added to the array?
        if($has_or_parent && count($or_children) > 0){
            $metadata_this['__in__metadata_expansion_steps'][$focus_in['in_id']] = $or_children;
        }


        //Save common base:
        if($is_first_intent){

            //Make sure to add main intent to common tree:
            if(count($metadata_this['__in__metadata_common_steps']) > 0){
                $metadata_this['__in__metadata_common_steps'] = array_merge( array(intval($focus_in['in_id'])) , array($metadata_this['__in__metadata_common_steps']));
            } else {
                $metadata_this['__in__metadata_common_steps'] = array(intval($focus_in['in_id']));
            }

            $this->Database_model->update_metadata('in', $focus_in['in_id'], array(
                'in__metadata_common_steps' => $metadata_this['__in__metadata_common_steps'],
                'in__metadata_expansion_steps'     => $metadata_this['__in__metadata_expansion_steps'],
            ));
        }

        //Return results:
        return $metadata_this;

    }


    function in_metadata_extra_insights($in_id, $update_db = true)
    {

        /*
         *
         * Generates additional insights like
         * min/max steps, time, cost and
         * referenced entities in intent notes.
         *
         * */

        //Fetch this intent:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status' => 2, //Published
        ));
        if(count($ins) < 1){
            return false;
        }

        $in_metadata = unserialize( $ins[0]['in_metadata'] );
        if(!isset($in_metadata['in__metadata_common_steps'])){
            return false;
        }

        //Fetch common base and expansion paths from intent metadata:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);
        $expansion_steps = ( isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0 ? $in_metadata['in__metadata_expansion_steps'] : array() );

        //Fetch totals for published common step intents:
        $common_totals = $this->Database_model->in_fetch(array(
            'in_id IN ('.join(',',$flat_common_steps).')' => null,
            'in_status' => 2, //Published
        ), array(), 0, 0, array(), 'COUNT(in_id) as total_steps, SUM(in_seconds_cost) as total_seconds');

        $common_base_resources = array(
            'steps' => $common_totals[0]['total_steps'],
            'seconds' => $common_totals[0]['total_seconds'],
        );

        $metadata_this = array(
            //Required steps/intents range to complete tree:
            '__in__metadata_min_steps' => $common_base_resources['steps'],
            '__in__metadata_max_steps' => $common_base_resources['steps'],
            //Required time range to complete tree:
            '__in__metadata_min_seconds' => $common_base_resources['seconds'],
            '__in__metadata_max_seconds' => $common_base_resources['seconds'],
            //Milestone Mark ranges (Always zero for common base):
            '__in__metadata_min_mark' => 0,
            '__in__metadata_max_mark' => 0,
            //Entity references within intent notes:
            '__in__metadata_experts' => array(),
            '__in__metadata_sources' => array(),
        );



        //Add-up Intent Note References:
        //The entities we need to check and see if they are industry experts:
        foreach ($this->Database_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4986')) . ')' => null, //Intent Notes that could possibly reference an entity
            'ln_parent_entity_id >' => 0, //Intent Notes that actually do reference an entity
            '(ln_child_intent_id='.$in_id.( count($flat_common_steps) > 0 ? ' OR ln_child_intent_id IN ('.join(',',$flat_common_steps).')' : '' ).')' => null,
            'ln_status' => 2, //Published
            'en_status' => 2, //Published
        ), array('en_parent'), 0) as $note_en) {

            //Referenced entity in intent notes... Fetch parents:
            foreach($this->Database_model->ln_fetch(array(
                'ln_child_entity_id' => $note_en['ln_parent_entity_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')).')' => null, //Entity Link Connectors
                'ln_status' => 2, //Published
            ), array(), 0) as $parent_en){

                if(in_array($parent_en['ln_parent_entity_id'], $this->config->item('en_ids_3000'))){

                    //Expert Source:
                    if (!isset($metadata_this['__in__metadata_sources'][$parent_en['ln_parent_entity_id']][$note_en['en_id']])) {
                        //Add since it's not there:
                        $metadata_this['__in__metadata_sources'][$parent_en['ln_parent_entity_id']][$note_en['en_id']] = $note_en;
                    }

                } elseif($parent_en['ln_parent_entity_id']==3084) {

                    //Industry Expert:
                    if (!isset($metadata_this['__in__metadata_experts'][$note_en['en_id']])) {
                        $metadata_this['__in__metadata_experts'][$note_en['en_id']] = $note_en;
                    }

                } else {

                    //Industry Expert?
                    $expert_parents = $this->Database_model->ln_fetch(array(
                        'en_status' => 2, //Published
                        'ln_status' => 2, //Published
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')).')' => null, //Entity Link Connectors
                        'ln_parent_entity_id' => 3084, //Industry Experts
                        'ln_child_entity_id' => $parent_en['ln_parent_entity_id'],
                    ), array('en_child'), 0);

                    if(count($expert_parents) > 0){

                        //Yes, Industry Expert:
                        if (!isset($metadata_this['__in__metadata_experts'][$parent_en['ln_parent_entity_id']])) {
                            $metadata_this['__in__metadata_experts'][$parent_en['ln_parent_entity_id']] = $expert_parents[0];
                        }

                    } else {
                        //TODO Maybe this is an expert source that is a slice of another expert source? Go another level-up and check parents...
                    }
                }
            }
        }



        //Go through expansion paths, if any:
        foreach($expansion_steps as $or_expansion){

            //Determine OR Answer local min/max:
            $metadata_local = array(
                'local__in__metadata_min_steps'=> null,
                'local__in__metadata_max_steps'=> null,
                'local__in__metadata_min_seconds'=> null,
                'local__in__metadata_max_seconds'=> null,
            );

            foreach($or_expansion as $or_in_id){

                $metadata_recursion = $this->Platform_model->in_metadata_extra_insights($or_in_id, false);

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
             * Sort Miners, Experts & Sources by trust score
             *
             * */
            usort($metadata_this['__in__metadata_experts'], 'sort_by_en_trust_score');
            foreach ($metadata_this['__in__metadata_sources'] as $type_en_id => $current_us) {
                usort($metadata_this['__in__metadata_sources'][$type_en_id], 'sort_by_en_trust_score');
            }


            /*
             *
             * Save to database
             *
             * */
            $this->Database_model->update_metadata('in', $in_id, array(
                'in__metadata_min_steps' => intval($metadata_this['__in__metadata_min_steps']),
                'in__metadata_max_steps' => intval($metadata_this['__in__metadata_max_steps']),
                'in__metadata_min_seconds' => intval($metadata_this['__in__metadata_min_seconds']),
                'in__metadata_max_seconds' => intval($metadata_this['__in__metadata_max_seconds']),
                'in__metadata_experts' => $metadata_this['__in__metadata_experts'],
                'in__metadata_sources' => $metadata_this['__in__metadata_sources'],
                'in__metadata_extra_insights_timestamp' => time(), //Use to check
            ));

        }


        //Return data:
        return $metadata_this;

    }


    function actionplan_top_priority($en_id){

        /*
         *
         * A function that goes through the Action Plan
         * and finds the top-priority that the student
         * is currently working on.
         *
         * */

        $top_priority_in = false;
        foreach($this->Database_model->ln_fetch(array(
            'ln_miner_entity_id' => $en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6147')) . ')' => null, //Action Plan Intentions
            'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
            'in_status' => 2, //Published
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC')) as $actionplan_in){

            //See progress rate so far:
            $completion_rate = $this->Platform_model->actionplan_completion_rate($actionplan_in, $en_id);

            if($completion_rate['completion_percentage'] < 100){
                //This is the top priority now:
                $top_priority_in = $actionplan_in;
                break;
            }

        }

        if(!$top_priority_in){
            return false;
        }

        //Return what's found:
        return array(
            'in' => $top_priority_in,
            'completion_rate' => $completion_rate,
        );

    }

    function actionplan_add($en_id, $in_id){

        //Validate Intent ID and ensure it's published:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status' => 2, //Published
        ));

        if (count($ins) != 1) {
            return false;
        }

        //Add intent to Student's Action Plan:
        $actionplan = $this->Database_model->ln_create(array(
            'ln_type_entity_id' => 4235, //Student Intent
            'ln_status' => 1, //Drafting
            'ln_miner_entity_id' => $en_id, //Belongs to this Student
            'ln_parent_intent_id' => $ins[0]['in_id'], //The Intent they are adding
            'ln_order' => 1 + $this->Database_model->ln_max_order(array( //Place this intent at the end of all intents the Student is drafting...
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6147')) . ')' => null, //Action Plan Intentions
                    'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
                    'ln_miner_entity_id' => $en_id, //Belongs to this Student
                )),
        ));

        //Confirm with them that we're now ready:
        $this->Communication_model->dispatch_message(
            'I have successfully added the intention to ' . $ins[0]['in_outcome'] . ' to your Action Plan 🙌 /link:Open 🚩Action Plan:https://mench.com/messenger/actionplan/' . $ins[0]['in_id'],
            array('en_id' => $en_id),
            true,
            array(),
            array(
                'ln_parent_intent_id' => $ins[0]['in_id'],
            )
        );

        /*
         *
         * Not the immediate priority, so let them
         * know that we will get to this when we
         * get to it, unless they want to re-sort
         * their Action Plan.
         *
         * */

        //Fetch top intention that being workined on now:
        $top_priority = $this->Platform_model->actionplan_top_priority($en_id);

        if($top_priority){
            if($top_priority['in']['in_id']==$ins[0]['in_id']){

                //The newly added intent is the top priority, so let's initiate first message for action plan tree:
                $this->Platform_model->actionplan_advance_step(array('en_id' => $en_id), $ins[0]['in_id']);

            } else {

                //A previously added intent is top-priority, so let them know:
                $this->Communication_model->dispatch_message(
                    'But we will work on this intention later because based on your Action Plan\'s priorities, your current focus is to '.$top_priority['in']['in_outcome'].' which you have made '.$top_priority['completion_rate']['completion_percentage'].'% progress so far. Alternatively, you can sort your Action Plan\'s priorities. /link:Sort 🚩Action Plan:https://mench.com/messenger/actionplan',
                    array('en_id' => $en_id),
                    true
                );

            }
        } else {

            //It seems the student already have this intention as completed:
            $this->Communication_model->dispatch_message(
                'You seem to have completed this intention before, so there is nothing else to do now.',
                array('en_id' => $en_id),
                true
            );

            //List featured intents and let them choose:
            $this->Communication_model->suggest_featured_intents($en_id);

        }

        return true;

    }


    function actionplan_complete_if_empty($en_id, $in){

        /*
         *
         * A function that marks an intent as complete IF
         * the intent has nothing of substance to be
         * further communicated.
         *
         * */

        $no_message = (count($this->Database_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'ln_type_entity_id' => 4231, //Intent Note Messages
                'ln_child_intent_id' => $in['in_id'],
            )))==0);

        $no_children = (count($this->Database_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'in_status' => 2, //Published
                'ln_type_entity_id' => 4228, //Fixed intent links only
                'ln_parent_intent_id' => $in['in_id'],
            ), array('in_child')))==0);

        $no_requirements = ($in['in_requirement_entity_id']==6087);


        if($no_message && $no_children && $no_requirements){

            //It should be auto completed:
            $new_progression_link = $this->Database_model->ln_create(array(
                'ln_type_entity_id' => 6158, //Action Plan Outcome Review
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $in['in_id'],
                'ln_status' => 2, //Published
            ));

        }
    }

    function actionplan_advance_step($recipient_en, $in_id, $fb_messenger_format = true)
    {

        /*
         *
         * Advance the student action plan by 1 step
         *
         * - $in_id:            The next step intent to be completed now
         *
         * - $recipient_en:     The recipient who will receive the messages via
         *                      Facebook Messenger. Note that this function does
         *                      not support an HTML format, only Messenger.
         *
         * */


        //Basic input validation:
        if ($in_id < 1) {

            return array(
                'status' => 0,
                'message' => 'Missing Intent ID',
            );

        } elseif (!isset($recipient_en['en_id'])) {

            return array(
                'status' => 0,
                'message' => 'Missing recipient entity ID',
            );

        }

        //Fetch/Validate intent:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $in_id,
        ));

        if (count($ins) < 1) {

            $this->Database_model->ln_create(array(
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'actionplan_advance_step() called invalid intent',
                'ln_child_entity_id' => $recipient_en['en_id'],
                'ln_parent_intent_id' => $ins[0]['in_id'],
            ));

            return array(
                'status' => 0,
                'message' => 'Invalid Intent #' . $ins[0]['in_id'],
            );

        } elseif ($ins[0]['in_status'] != 2) {

            $this->Database_model->ln_create(array(
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'actionplan_advance_step() called unpublished intent',
                'ln_child_entity_id' => $recipient_en['en_id'],
                'ln_parent_intent_id' => $ins[0]['in_id'],
            ));

            return array(
                'status' => 0,
                'message' => 'Invalid #' . $ins[0]['in_id'].' is not yet published',
            );

        }


        /*
         *
         * Make sure we have full student information
         * as it might be needed for the messages
         * we're about to send out.
         *
         * */
        if(!isset($recipient_en['en_name'])){
            //Let's fetch full details:
            $ens = $this->Database_model->en_fetch(array('en_id' => $recipient_en['en_id']));
            $recipient_en = $ens[0];
        }



        /*
         *
         * There are different ways to complete an intent
         * as listed under Action Plan Progression Link Types:
         *
         * https://mench.com/entities/6146
         *
         * We'll start by assuming the most basic form of
         * completion (Action Plan Outcome Review) and
         * build-up to more advance forms of completion
         * as we gather more data through-out this function.
         *
         * Also note that some progression types follow a
         * 2-step completion method where students are
         * required to submit their response in order
         * to move to the next step as defined by
         * Action Plan 2-Step Link Types:
         *
         * https://mench.com/entities/6244
         *
         * */

        //Set variables:
        $progression_messages = ''; //To be populated for webview
        $progression_type_entity_id = 6158; //Action Plan Outcome Review
        $next_in_id = 0; //If we don't have any children or requirements we will attempt to find the next intent to take appropriate Action
        $next_step_message = null; //To be populated if there is a next step.
        $next_step_quick_replies = array(); //To be populated with appropriate options to further progress form here...
        $message_in_requirements = $this->Platform_model->in_req_completion($ins[0], $fb_messenger_format); //See if we have intent requirements
        $in__messages = $this->Database_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'ln_type_entity_id' => 4231, //Intent Note Messages
            'ln_child_intent_id' => $ins[0]['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));
        $in__children = $this->Database_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'in_status' => 2, //Published
            'ln_type_entity_id' => 4228, //Fixed intent links only
            'ln_parent_intent_id' => $ins[0]['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
        $current_progression_links = $this->Database_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
            'ln_miner_entity_id' => $recipient_en['en_id'],
            'ln_parent_intent_id' => $ins[0]['in_id'],
            'ln_status >=' => 0, //New+
        ));
        $has_published_progression = false;
        $has_non_skippable_proression = false; //Assume false unless proven otherwise...
        foreach($current_progression_links as $current_progression_link){
            //Action Plan Skippable Progression Llink Types
            if($current_progression_link['ln_status']==2){
                $has_published_progression = true;
            }
            if($current_progression_link['ln_status']==2 && !in_array($current_progression_link['ln_type_entity_id'], $this->config->item('en_ids_6274'))){
                $has_non_skippable_proression = true;
            }
        }



        /*
         *
         * Now let's see the intent type (AND or OR)
         * and also count its children to see how
         * we would need to advance the student.
         *
         * */

        //Do we have any requirements?
        if ($ins[0]['in_type']==0 /* AND intent */ && $message_in_requirements) {

            //Yes! Set appropriate variables:
            $progression_type_entity_id = 6144; //Action Plan Requirement Submitted
            $next_step_message = $message_in_requirements;

        } elseif(count($in__children) > 0){

            //We have children, now see if its AND or OR intent to act accordingly...

            if ($ins[0]['in_type']==1) {

                //OR Intent

                $progression_type_entity_id = 6157; //Action Plan Question Answered
                $next_step_message = ''; //Select one of the following options to continue:

                if($fb_messenger_format){

                    //See if we have answer referencing in messages...
                    $answer_referencing = array(); //Start with nothing...
                    foreach ($in__messages as $message_ln) {
                        //Let's see if we can find a reference:
                        for ($num = 1; $num <= 10; $num++) {
                            if(substr_count($message_ln['ln_content'] , $num.'. ')==1 || substr_count($message_ln['ln_content'] , $num.".\n")==1){
                                //Make sure we have have the previous number:
                                if($num==1 || in_array(($num-1),$answer_referencing)){
                                    array_push($answer_referencing, $num);
                                }
                            }
                        }
                    }

                } else {
                    $next_step_message .= '<div class="list-group" style="margin-top:10px;">';
                }

                foreach ($in__children as $key => $child_in) {

                    if ($fb_messenger_format && ($key >= 10 || strlen($next_step_message) > ($this->config->item('fb_max_message') - 150))) {
                        //Log error link so we can look into it:
                        $this->Database_model->ln_create(array(
                            'ln_miner_entity_id' => 1, //Shervin/Developer
                            'ln_content' => 'actionplan_advance_step() encountered intent with too many children to be listed as OR Intent options! Trim and iterate that intent tree.',
                            'ln_type_entity_id' => 4246, //Platform Error
                            'ln_parent_intent_id' => $ins[0]['in_id'],
                            'ln_child_intent_id' => $child_in['in_id'],
                            'ln_child_entity_id' => $recipient_en['en_id'],
                        ));

                        //Quick reply accepts 11 options max:
                        break;
                    }


                    //Is this selected?
                    $was_selected = ( $has_published_progression && $current_progression_links[0]['ln_child_intent_id']==$child_in['in_id'] );

                    //Fetch history if selected:
                    if($was_selected){
                        $child_progression_steps = $this->Database_model->ln_fetch(array(
                            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
                            'ln_miner_entity_id' => $recipient_en['en_id'],
                            'ln_parent_intent_id' => $current_progression_links[0]['ln_child_intent_id'],
                            'ln_status >=' => 0,
                        ));
                    }


                    if($fb_messenger_format){

                        if(!in_array(($key+1), $answer_referencing)){
                            $next_step_message .= "\n\n" . ($key+1).'. '.echo_in_outcome($child_in['in_outcome'], true);
                        }

                        if($was_selected){
                            $next_step_message .= '['.($key+1).' Was Selected] ';
                        } elseif(!$has_published_progression) {
                            //For messenger only:
                            array_push($next_step_quick_replies, array(
                                'content_type' => 'text',
                                'title' => ($key+1),
                                'payload' => 'ANSWERQUESTION_' . $ins[0]['in_id'] . '_' . $child_in['in_id'],
                            ));
                        }

                    } else {

                        if(!$has_published_progression){
                            //Need to select answer:
                            $next_step_message .= '<a href="/messenger/actionplan_answer_question/' . $recipient_en['en_id'] . '/' . $ins[0]['in_id'] . '/' . $child_in['in_id'] . '/' . md5($this->config->item('actionplan_salt') . $child_in['in_id'] . $ins[0]['in_id'] . $recipient_en['en_id']) . '" class="list-group-item">';
                        } elseif($was_selected){
                            //This was selected:
                            $next_step_message .= '<a href="/messenger/actionplan/'.$child_in['in_id'] . '" class="list-group-item">';
                        } else {
                            //This was NOT selected:
                            $next_step_message .= '<span class="list-group-item" style="text-decoration: line-through;">';
                        }


                        if($was_selected){

                            //Selected Icon:
                            $next_step_message .= '<i class="fas fa-check-circle"></i> ';

                        } else {

                            //Not selected icon:
                            $next_step_message .= '<i class="far fa-circle"></i> ';

                        }

                        //Add to answer list:
                        $next_step_message .= echo_in_outcome($child_in['in_outcome'], true);

                    }


                    //HTML?
                    if(!$fb_messenger_format){

                        if($was_selected) {
                            //Status Icon:
                            $next_step_message .= '&nbsp;' . echo_fixed_fields('ln_student_status', (count($child_progression_steps) > 0 ? $child_progression_steps[0]['ln_status'] : 0), false, null);
                        }

                        //Close tags:
                        if(!$has_published_progression || $was_selected){

                            //Simple right icon
                            $next_step_message .= '<span class="pull-right" style="margin-top: -6px;">';
                            $next_step_message .= '<span class="badge badge-primary"><i class="fas fa-angle-right"></i>&nbsp;</span>';
                            $next_step_message .= '</span>';

                            $next_step_message .= '</a>';

                        } else {

                            $next_step_message .= '</span>';

                        }

                    }
                }

                if(!$fb_messenger_format){
                    $next_step_message .= '</div>';
                }

            } else {

                //Simple AND intent that we'd just need to move forward:

                if(count($in__children) == 1){
                    //A single next step:
                    $next_step_message = 'There is a single step to ' . echo_in_outcome($ins[0]['in_outcome'], true, true);
                } else {
                    //Multiple next steps:
                    $next_step_message = 'There are ' . count($in__children) . ' steps to ' . echo_in_outcome($ins[0]['in_outcome'], true, true);
                }




                //List children:
                $key = 0;
                foreach ($in__children as $child_in) {

                    //Make sure this AND child has a "useful" outcome by NOT referencing it's ID in its outcome:
                    if($fb_messenger_format && substr_count($child_in['in_outcome'], '#'.$child_in['in_id']) > 0){
                        //This is likely a "useless" outcome like "Answer question #8704" which should not be listed...
                        continue;
                    }

                    if($key==0){

                        $next_step_message .= ':';

                        if(!$fb_messenger_format){
                            $next_step_message .= '<div class="list-group" style="margin-top:10px;">';
                        }
                    }

                    //We know that the $next_step_message length cannot surpass the limit defined by fb_max_message variable!
                    //make sure message is within range:
                    if ($fb_messenger_format && ($key >= 7 || strlen($next_step_message) > ($this->config->item('fb_max_message') - 150))) {
                        //We cannot add any more, indicate truncating:
                        $remainder = count($in__children) - $key;
                        $next_step_message .= "\n\n" . 'Plus ' . $remainder . ' more step' . echo__s($remainder) . '.';
                        break;
                    }


                    //Fetch progression data:
                    $child_progression_steps = $this->Database_model->ln_fetch(array(
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
                        'ln_miner_entity_id' => $recipient_en['en_id'],
                        'ln_parent_intent_id' => $child_in['in_id'],
                        'ln_status >=' => 0,
                    ));


                    if(!$fb_messenger_format){

                        //Completion Percentage so far:
                        $completion_rate = $this->Platform_model->actionplan_completion_rate($child_in, $recipient_en['en_id']);

                        //Open list:
                        $next_step_message .= '<a href="/messenger/actionplan/'.$child_in['in_id']. '" class="list-group-item">';

                        //Simple right icon
                        $next_step_message .= '<span class="pull-right" style="margin-top: -6px;">';
                        $next_step_message .= '<span class="badge badge-primary"  data-toggle="tooltip" data-placement="top" title="'.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Steps Completed" style="text-decoration:none;"><span style="font-size:0.7em;">'.$completion_rate['completion_percentage'].'%</span> <i class="fas fa-angle-right"></i>&nbsp;</span>';
                        $next_step_message .= '</span>';

                        //Determine what icon to show:
                        if(count($child_progression_steps) > 0){
                            //We do have a progression link...
                            if($child_progression_steps[0]['ln_status']==2){
                                //Status is complete, show the progression type icon:
                                $en_all_6146 = $this->config->item('en_all_6146');
                                $next_step_message .= $en_all_6146[$child_progression_steps[0]['ln_type_entity_id']]['m_icon'];
                            } else {
                                //Status is not yet complete, so show the status icon:
                                $next_step_message .= echo_fixed_fields('ln_student_status', $child_progression_steps[0]['ln_status'], true, null);
                            }
                        } else {
                            //No progression, so show a new icon:
                            $next_step_message .= echo_fixed_fields('ln_student_status', 0, true, null);
                        }

                        $next_step_message .= '&nbsp;';

                    } else {

                        //Add message:
                        $next_step_message .= "\n\n" . ($key + 1) . '. ';

                    }


                    $next_step_message .= echo_in_outcome($child_in['in_outcome'], true);

                    if(!$fb_messenger_format){

                        $next_step_message .= '</a>';

                    }

                    $key++;
                }

                if($fb_messenger_format){
                    //Show only the first step forward for Messenger view:
                    array_push($next_step_quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Next',
                        'payload' => 'GONEXT',
                    ));
                    //Give option to skip:
                    array_push($next_step_quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Skip',
                        'payload' => 'SKIP-ACTIONPLAN_1_' . $ins[0]['in_id'],
                    ));
                } else {
                    if($key > 0){
                        $next_step_message .= '</div>';
                    }
                }

            }

        } else {

            //No requirements OR children... So let's see if we have a next step:
            $next_in_id = $this->Platform_model->actionplan_find_next_step($recipient_en['en_id'], false);

            //Intent has no requirements and no children, so give call to Action:
            if($next_in_id > 0 && !$fb_messenger_format){
                //Show button for next step:
                $next_step_message .= '<div style="margin: 15px 0 0;"><a href="/messenger/actionplan/next" class="btn btn-md btn-primary">Next Step <i class="fas fa-angle-right"></i></a></div>';
            }

        }





        //Always communicate intent messages if any:
        if(count($in__messages) > 0){

            //Update progression type if its still basic:
            if($progression_type_entity_id==6158){
                $progression_type_entity_id = 4559; //Action Plan Messages Read
            }

            //Dispatch intent messages if not skipped:
            foreach ($in__messages as $count => $message_ln) {
                $progression_messages .= $this->Communication_model->dispatch_message(
                    $message_ln['ln_content'],
                    $recipient_en,
                    $fb_messenger_format,
                    //This is when we have messages and need to append the "Next" Quick Reply to the last message:
                    ( $next_in_id > 0 && $fb_messenger_format && !$next_step_message && count($next_step_quick_replies)==0 && ($count+1)==count($in__messages) ? array(array(
                        'content_type' => 'text',
                        'title' => 'Next',
                        'payload' => 'GONEXT',
                    )) : ( count($next_step_quick_replies) > 0 && ($count+1)==count($in__messages) && $fb_messenger_format && !$next_step_message ? array_merge($next_step_quick_replies, array(array(
                        'content_type' => 'text',
                        'title' => 'Skip',
                        'payload' => 'SKIP-ACTIONPLAN_1_' . $ins[0]['in_id'],
                    ))) : array() ) ),
                    array(
                        'ln_parent_intent_id' => $ins[0]['in_id'],
                        'ln_parent_link_id' => $message_ln['ln_id'], //This message
                    )
                );
            }
        }




        /*
         *
         * Log progression link
         *
         * */

        //Is this a 2-step Link type where we insert a pending link and later update it when requirements submitted?
        $is_two_step = in_array($progression_type_entity_id , $this->config->item('en_ids_6244'));

        //Log the progression link if not logged before:
        if(!$is_two_step || count($current_progression_links)==0){

            //Log new link:
            $new_progression_link = $this->Database_model->ln_create(array(
                'ln_type_entity_id' => $progression_type_entity_id,
                'ln_miner_entity_id' => $recipient_en['en_id'],
                'ln_parent_intent_id' => $ins[0]['in_id'],
                'ln_status' => ( $is_two_step ? 1 : 2 ),
            ));

            //Since we logged a new progression, let's remove the old ones if any:
            if(!$is_two_step && count($current_progression_links) > 0){

                //Archive previous progression links since new one was logged:
                foreach($current_progression_links as $key=>$ln){

                    $this->Database_model->ln_update($ln['ln_id'], array(
                        'ln_parent_link_id' => $new_progression_link['ln_id'],
                        'ln_status' => -1,
                    ), $recipient_en['en_id']);

                    //Remove from array:
                    unset($current_progression_links[$key]);

                }

            }

            if(!$is_two_step){
                $has_published_progression = true;
            }

            //Add new progression link:
            array_push($current_progression_links, $new_progression_link);


            /*
             *
             * Post-Progression Automated Actions now...
             *
             * */


            //Check to see if completion notes needs to be dispatched via Messenger?
            if($fb_messenger_format && $new_progression_link['ln_status']==2 && in_array($new_progression_link['ln_type_entity_id'], $this->config->item('en_ids_6255'))){

                //Fetch on-complete messages:
                $on_complete_messages = $this->Database_model->ln_fetch(array(
                    'ln_status' => 2, //Published
                    'ln_type_entity_id' => 6242, //On-Complete Tips
                    'ln_child_intent_id' => $ins[0]['in_id'],
                ), array(), 0, 0, array('ln_order' => 'ASC'));


                //First let's make sure we have on-complete messages to send as most intentions do not have this (less likely to happen):
                if(count($on_complete_messages) > 0){

                    //Prep filter for search & insert:
                    $filter = array(
                        'ln_status' => 2,
                        'ln_miner_entity_id' => $recipient_en['en_id'],
                        'ln_type_entity_id' => 6255, //Action Plan Trigger On-Complete Tips
                        'ln_parent_intent_id' => $ins[0]['in_id'], //First Action
                    );

                    //Make sure we have not sent this before (they might be completing this again...)
                    if( count($this->Database_model->ln_fetch($filter))==0 ){

                        //Never sent before, so let's log a new link:
                        $link_log = $this->Database_model->ln_create($filter);

                        //Dispatch all on-complete notes:
                        foreach($on_complete_messages as $complete_note){

                            //Send to student:
                            $this->Communication_model->dispatch_message(
                                $complete_note['ln_content'],
                                $recipient_en,
                                true,
                                array(),
                                array(
                                    'ln_parent_link_id' => $link_log['ln_id'],
                                    'ln_parent_intent_id' => $ins[0]['in_id']
                                )
                            );

                        }
                    }
                }
            }


            //If we have no children and this link is complete, it's time to do a recursive up-wards check and see if any Web-hooks need to be trigerred...
            if($new_progression_link['ln_status']==2 && count($in__children)==0){

                //Trigger webhooks:
                $this->Platform_model->actionplan_trigger_webhooks($ins[0]['in_id'], $recipient_en['en_id']);

            }
        }





        $trigger_recommendations = false;
        if(!$next_step_message && count($next_step_quick_replies)==0){

            if($next_in_id < 1){
                //No next step found! This seems to be the end:
                $next_step_message = 'This was your final step 🎉🎉🎉';
                $trigger_recommendations = true;
            } else {
                //Maybe do something here?
            }

        } elseif(!$has_non_skippable_proression) {

            //Give option to skip:
            if($fb_messenger_format){

                //Give an option to confirm IF has submission requirements:
                if($progression_type_entity_id==6144){
                    array_push($next_step_quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'OK',
                        'payload' => 'INFORMREQUIREMENT_' . $ins[0]['in_id'],
                    ));
                }

                //Give option to skip Student Intent:
                array_push($next_step_quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Skip',
                    'payload' => 'SKIP-ACTIONPLAN_1_' . $ins[0]['in_id'],
                ));

            } else {

                $next_step_message .= '<div style="font-size: 0.7em; margin-top: 10px;">Or <a href="javascript:void(0);" onclick="actionplan_skip_steps(' . $recipient_en['en_id'] . ', ' . $ins[0]['in_id'] . ')"><u>Skip</u></a>.</div>';

            }

        }



        //Dispatch instructional message if any:
        if($fb_messenger_format) {

            if(strlen($next_step_message) > 0){
                //Send messages over Messenger IF we have a message
                $this->Communication_model->dispatch_message(
                    $next_step_message,
                    $recipient_en,
                    true,
                    $next_step_quick_replies,
                    array(
                        'ln_parent_intent_id' => $ins[0]['in_id'], //Focus Intent
                    )
                );
            }

            if($trigger_recommendations){
                //List featured intents and let them choose:
                $this->Communication_model->suggest_featured_intents($recipient_en['en_id']);
            }

        } else {

            //Format for HTML:
            $progression_messages .= '<div class="msg" style="margin-top: 15px;">'.nl2br($next_step_message).'</div>';

        }

        //Return data:
        return array(
            'status' => 1,
            'message' => $progression_messages, //Used for HTML webview
            'progression_links' => $current_progression_links,
        );

    }

    function actionplan_completion_rate($in, $miner_en_id, $top_level = true)
    {

        //Fetch/validate Action Plan Common Steps:
        $in_metadata = unserialize($in['in_metadata']);
        if(!isset($in_metadata['in__metadata_common_steps'])){

            //Should not happen, log error:
            $this->Database_model->ln_create(array(
                'ln_content' => 'actionplan_completion_rate() Detected student Action Plan without in__metadata_common_steps value!',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_parent_entity_id' => $miner_en_id,
                'ln_parent_intent_id' => $in['in_id'],
            ));

            return 0;
        }

        //Generate flat steps:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);

        //Count totals:
        $common_totals = $this->Database_model->in_fetch(array(
            'in_id IN ('.join(',',$flat_common_steps).')' => null,
            'in_status' => 2, //Published
        ), array(), 0, 0, array(), 'COUNT(in_id) as total_steps, SUM(in_seconds_cost) as total_seconds');

        //Count completed for student:
        $common_completed = $this->Database_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
            'ln_miner_entity_id' => $miner_en_id, //Belongs to this Student
            'ln_parent_intent_id IN (' . join(',', $flat_common_steps ) . ')' => null,
            'ln_status' => 2, //Published
            'in_status' => 2, //Published
        ), array('in_parent'), 0, 0, array(), 'COUNT(in_id) as completed_steps, SUM(in_seconds_cost) as completed_seconds');

        //Calculate common steps and expansion steps recursively for this student:
        $metadata_this = array(
            'steps_total' => intval($common_totals[0]['total_steps']),
            'steps_completed' => intval($common_completed[0]['completed_steps']),
            'seconds_total' => intval($common_totals[0]['total_seconds']),
            'seconds_completed' => intval($common_completed[0]['completed_seconds']),
        );

        //Fetch expansion steps recursively, if any:
        if(isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0){

            foreach($this->Database_model->ln_fetch(array(
                'ln_type_entity_id' => 6157, //Action Plan Question Answered
                'ln_miner_entity_id' => $miner_en_id, //Belongs to this Student
                'ln_parent_intent_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_child_intent_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_steps'])) . ')' => null,
                'ln_status' => 2, //Published
                'in_status' => 2, //Published
            ), array('in_child')) as $expansion_in){

                //Fetch recursive:
                $recursive_stats = $this->Platform_model->actionplan_completion_rate($expansion_in, $miner_en_id, false);

                //Addup completion stats for this:
                $metadata_this['steps_total'] += $recursive_stats['steps_total'];
                $metadata_this['steps_completed'] += $recursive_stats['steps_completed'];
                $metadata_this['seconds_total'] += $recursive_stats['seconds_total'];
                $metadata_this['seconds_completed'] += $recursive_stats['seconds_completed'];

            }
        }

        if($top_level){

            /*
             *
             * Completing an Action Plan depends on two factors:
             *
             * 1) number of steps (some may have 0 time estimate)
             * 2) estimated seconds (usually accurate)
             *
             * To increase the accurate of our completion % function,
             * We would also assign a default time to the average step
             * so we can calculate more accurately even if none of the
             * steps have an estimated time.
             *
             * */

            //Set default seconds per step:
            $step_default_seconds = 60;

            //Calculate completion rate based on estimated time cost:
            $metadata_this['completion_percentage'] = floor( ($metadata_this['seconds_completed']+($step_default_seconds*$metadata_this['steps_completed'])) / ($metadata_this['seconds_total']+($step_default_seconds*$metadata_this['steps_total'])) * 100 );

        }

        //Return results:
        return $metadata_this;

    }


    function actionplan_trigger_webhooks($in_id, $en_id, $student_in_ids = null){

        //Search and see if this intent has any Webhooks:
        foreach($this->Database_model->ln_fetch(array(
            'in_status' => 2, //Published
            'ln_status' => 2, //Published
            'ln_type_entity_id' => 4602, //Intent Note Webhooks
            'ln_child_intent_id' => $in_id,
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $webhook_entity){

            //Find all the URLs for this Webhook:
            foreach($this->Database_model->ln_fetch(array(
                'ln_type_entity_id' => 4256, //Linked Entities Generic URL
                'ln_child_entity_id' => $webhook_entity['ln_parent_entity_id'], //This child entity
                'ln_status' => 2, //Published
            )) as $webhook_url){

                //Prep filter for search/insert:
                $filter = array(
                    'ln_status' => 2,
                    'ln_miner_entity_id' => $en_id,
                    'ln_type_entity_id' => 6277, //Action Plan Progression Trigger Webhook
                    'ln_parent_intent_id' => $in_id,
                    'ln_parent_entity_id' => $webhook_entity['ln_parent_entity_id'],
                    'ln_parent_link_id' => $webhook_entity['ln_id'],
                );

                //Make sure we have not sent this before (they might be completing this again...)
                if( count($this->Database_model->ln_fetch($filter))==0 ) {

                    //Never triggered before, so let's do it now:
                    $trigger_results = webhook_curl_post($webhook_url['ln_content'], $in_id, $en_id);

                    //Did we face any issues?
                    if(!isset($trigger_results['status']) || intval($trigger_results['status'])!=1){
                        //It seemed that the trigger did not work properly, log an error for the admin:
                        $this->Database_model->ln_create(array_merge($filter , array(
                            'ln_type_entity_id' => 4246, //Platform Error
                            'ln_miner_entity_id' => 1, //Shervin/Developer
                            'ln_child_entity_id' => $en_id,
                            'ln_content' => 'actionplan_trigger_webhooks() failed when calling webhook URL ['.$webhook_url['ln_content'].']',
                            'ln_metadata' => $trigger_results,
                        )));
                    }

                    //Log a new link while saving the trigger results:
                    $this->Database_model->ln_create(array_merge($filter , array(
                        'ln_metadata' => $trigger_results,
                    )));
                }
            }
        }


        //Go through parents and detect intersects with student intentions. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
        foreach ($this->Platform_model->in_fetch_recursive_parents($in_id, 2) as $parent_in_id => $grand_parent_ids) {

            if(!$student_in_ids){
                //Fetch all student intention IDs:
                $student_in_ids = array();
                foreach($this->Database_model->ln_fetch(array(
                    'ln_miner_entity_id' => $en_id,
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6147')) . ')' => null, //Action Plan Intentions
                    'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
                    'in_status' => 2, //Published
                ), array('in_parent'), 0) as $student_in){
                    array_push($student_in_ids, $student_in['in_id']);
                }
            }

            //Does this parent and its grandparents have an intersection with the student intentions?
            if(array_intersect($grand_parent_ids, $student_in_ids)){

                //Fetch parent intent & show:
                $parent_ins = $this->Database_model->in_fetch(array(
                    'in_id' => $parent_in_id,
                ));

                //See if this is complete:
                $completion_rate = $this->Platform_model->actionplan_completion_rate($parent_ins[0], $en_id);
                if($completion_rate['completion_percentage']==100){

                    //Yes it is! Trigger Webhook recursively:
                    $this->Platform_model->actionplan_trigger_webhooks($parent_in_id, $en_id, $student_in_ids);

                }
            }
        }
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
        $matching_entities = $this->Database_model->ln_fetch(array(
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
            $this->Database_model->ln_create(array(
                'ln_content' => 'en_search_match() found [' . count($matching_entities) . '] results as the children of en_id=[' . $en_parent_id . '] that had the value of [' . $value . '].',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_child_entity_id' => $en_parent_id,
            ));

            return 0;
        }
    }


    function in_force_verb_creation($in_outcome, $ln_miner_entity_id = 0){

        //Fetch related variables:
        $outcome_words = explode(' ', $in_outcome);
        $starting_verb = trim($outcome_words[0]);
        $in_verb_entity_id = detect_starting_verb_id($in_outcome);

        //Run some checks on the intent outcome:
        if(count($outcome_words) < 3) {

            //The /force is a word, so Verb is too short:
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

            //Not a acceptable Verb:
            return array(
                'status' => 0,
                'message' => '/force command is only available to moderators',
            );

        } elseif(strlen($starting_verb) < 2) {

            //Verb is too short:
            return array(
                'status' => 0,
                'message' => 'Verb must be at-least 2 characters long',
            );

        } elseif(!ctype_alpha($starting_verb)){

            //Not a acceptable Verb:
            return array(
                'status' => 0,
                'message' => 'Verb should only consist of letters A-Z',
            );

        }

        //Create the supporting verb if not already there:
        if(!$in_verb_entity_id){

            //Add and link verb:
            $added_en = $this->Platform_model->en_verify_create(ucwords(strtolower($starting_verb)), $ln_miner_entity_id, true);

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


    function in_validate_outcome($in_outcome, $ln_miner_entity_id = 0, $skip_in_id = 0){

        //Assign verb variables:
        $in_verb_entity_id = detect_starting_verb_id($in_outcome);

        //Validate outcome:
        if(strlen($in_outcome) < 5){

            return array(
                'status' => 0,
                'message' => 'Outcome must be at-least 5 characters long',
            );

        } elseif(substr_count($in_outcome , ' ') < 1){

            return array(
                'status' => 0,
                'message' => 'Outcome must have at-least two words',
            );

        } elseif(substr_count($in_outcome , '  ') > 0){

            return array(
                'status' => 0,
                'message' => 'Outcome cannot include double spaces',
            );

        } elseif(substr_count($in_outcome , '::') >= 2){

            return array(
                'status' => 0,
                'message' => 'You can only use the double colon command once',
            );

        } elseif(strlen($in_outcome) < 5){

            return array(
                'status' => 0,
                'message' => 'Outcome must be at-least 5 characters long',
            );

        } elseif(substr_count($in_outcome , '/force') > 0){

            //Force command detected, pass it on to the force function:
            $force_outcome = $this->Platform_model->in_force_verb_creation($in_outcome, $ln_miner_entity_id);

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

            //Not a acceptable Verb:
            return array(
                'status' => 0,
                'message' => 'Verb is not yet supported. Manage supported verbs via entity @5008'.( en_auth(array(1281)) ? ' or use the /force command to add this verb to the supported list.' : '' ),
            );

        }

        //Check length now that potential /force command is removed:
        if (strlen($in_outcome) > $this->config->item('in_outcome_max')) {
            return array(
                'status' => 0,
                'message' => 'Intent outcome cannot be longer than '.$this->config->item('in_outcome_max').' characters',
            );
        }

        //Do we have a double colon command? If so, make sure we also have an intent reference:
        if(substr_count($in_outcome , '::') ==1){

            //Does the outcome have a parent intent reference?
            $msg_references = extract_message_references($in_outcome);

            if(count($msg_references['ref_intents']) != 1){
                return array(
                    'status' => 0,
                    'message' => 'Double colon required an intent reference (like #1234) in the outcome',
                );
            } elseif(strpos($in_outcome,'#') > strpos($in_outcome,'::')){
                return array(
                    'status' => 0,
                    'message' => 'Intent reference must appear before double colon',
                );
            }
        }

        //Check to make sure it's not a duplicate outcome:
        $in_dup_search_filters = array(
            'in_status >=' => 0, //New+
            'LOWER(in_outcome)' => strtolower(trim($in_outcome)),
        );
        if($skip_in_id > 0){
            $in_dup_search_filters['in_id !='] = $skip_in_id;
        }
        foreach($this->Database_model->in_fetch($in_dup_search_filters) as $dup_in){
            //This is a duplicate, disallow:
            $fixed_fields = $this->config->item('fixed_fields');
            return array(
                'status' => 0,
                'message' => 'Outcome ['.$in_outcome.'] already in-use by intent #'.$dup_in['in_id'].' with status ['.$fixed_fields['in_status'][$dup_in['in_status']]['s_name'].']',
            );
        }

        //All good, return success:
        return array(
            'status' => 1,
            'in_cleaned_outcome' => trim($in_outcome),
            'detected_verb_entity_id' => $in_verb_entity_id,
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
        $graph_fetch = $this->Communication_model->facebook_graph('GET', '/' . $psid, array());
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
            $added_en = $this->Platform_model->en_verify_create('Student '.rand(100000000, 999999999), 0, true, 2, null, $psid);

        } else {

            //We did find the profile, move ahead:
            $fb_profile = $graph_fetch['ln_metadata']['result'];

            //Create student entity with their Facebook Graph name:
            $added_en = $this->Platform_model->en_verify_create($fb_profile['first_name'] . ' ' . $fb_profile['last_name'], 0, true, 2, null, $psid);

            //Split locale variable into language and country like "EN_GB" for English in England
            $locale = explode('_', $fb_profile['locale'], 2);

            //Try to match Facebook profile data to internal entities and create links for the ones we find:
            foreach (array(
                         $this->Platform_model->en_search_match(3289, $fb_profile['timezone']), //Timezone
                         $this->Platform_model->en_search_match(3290, strtolower(substr($fb_profile['gender'], 0, 1))), //Gender either m/f
                         $this->Platform_model->en_search_match(3287, strtolower($locale[0])), //Language
                         $this->Platform_model->en_search_match(3089, strtolower($locale[1])), //Country
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
            $this->Communication_model->dispatch_message(
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

            //check all parents as this intent cannot be duplicated with any of its parents as it created an infinity loop:
            if (in_array($intent_new['in_id'], array_flatten($this->Platform_model->in_fetch_recursive_parents($actionplan_in_id, 0)))) {
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
                $in_outcome = $parent_in_outcome_words[0].' #'.$parent_ins[0]['in_id'].' :: '.trim(substr($in_outcome, 2));

            }

            //Validate Intent Outcome:
            $in_outcome_validation = $this->Platform_model->in_validate_outcome($in_outcome, $ln_miner_entity_id);
            if(!$in_outcome_validation['status']){
                //We had an error, return it:
                return $in_outcome_validation;
            }

            //All good, let's create the intent:
            $intent_new = $this->Database_model->in_create(array(
                'in_outcome' => $in_outcome_validation['in_cleaned_outcome'],
                'in_verb_entity_id' => $in_outcome_validation['detected_verb_entity_id'],
            ), true, $ln_miner_entity_id);

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
        );

    }

}