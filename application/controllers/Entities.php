<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entities extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }

    function add_source_wizard()
    {
        //Authenticate Miner, redirect if failed:
        $session_en = en_auth(array(1308), true);

        //Show frame to be loaded in modal:
        $this->load->view('view_shared/matrix_header', array(
            'title' => 'Add Source Wizard',
        ));
        $this->load->view('view_entities/add_source_frame');
        $this->load->view('view_shared/matrix_footer');
    }


    function en_add_source_paste_url()
    {

        /*
         *
         * Validates the input URL to be added as a new source entity
         *
         * */

        $session_en = en_auth(array(1308));
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh to Continue',
                'url_entity' => array(),
            ));
        }

        //All seems good, fetch URL:
        $url_entity = $this->Matrix_model->en_sync_url($_POST['input_url']);

        if (!$url_entity['status']) {
            //Oooopsi, we had some error:
            return echo_json(array(
                'status' => 0,
                'message' => $url_entity['message'],
            ));
        }

        //Return results:
        return echo_json(array(
            'status' => 1,
            'entity_domain_ui' => '<span class="en_mini_ui_icon parent-icon">' . (isset($url_entity['en_domain']['en_icon']) && strlen($url_entity['en_domain']['en_icon']) > 0 ? $url_entity['en_domain']['en_icon'] : detect_fav_icon($url_entity['url_clean_domain'], true)) . '</span> ' . (isset($url_entity['en_domain']['en_name']) ? $url_entity['en_domain']['en_name'] . ' <a href="/entities/' . $url_entity['en_domain']['en_id'] . '" class="underdot" data-toggle="tooltip" title="Click to open domain entity in a new windows" data-placement="top" target="_blank">@' . $url_entity['en_domain']['en_id'] . '</a>' : $url_entity['url_domain_name'] . ' [<span class="underdot" data-toggle="tooltip" title="Domain entity not yet added" data-placement="top">New</span>]'),
            'js_url_entity' => $url_entity,
        ));

    }


    //Lists entities
    function en_miner_ui($en_id)
    {

        if ($en_id == 0) {
            //Set to default:
            $en_id = $this->config->item('en_top_focus_id');
        }

        $session_en = en_auth(array(1308), true); //Just be logged in to browse


        //Do we have any mass action to process here?
        if (en_auth(array(1281)) && isset($_POST['mass_action_en_id']) && isset($_POST['mass_value1_'.$_POST['mass_action_en_id']]) && isset($_POST['mass_value2_'.$_POST['mass_action_en_id']])) {

            //Process mass action:
            $process_mass_action = $this->Matrix_model->en_mass_update($en_id, intval($_POST['mass_action_en_id']), $_POST['mass_value1_'.$_POST['mass_action_en_id']], $_POST['mass_value2_'.$_POST['mass_action_en_id']], $session_en['en_id']);

            //Pass-on results to UI:
            $message = '<div class="alert '.( $process_mass_action['status'] ? 'alert-success' : 'alert-danger' ).'" role="alert">'.$process_mass_action['message'].'</div>';

        } else {

            //No mass action, just viewing...
            //Update session count and log link:
            $message = null; //No mass-action message to be appended...

            $new_order = ( $this->session->userdata('miner_session_count') + 1 );
            $this->session->set_userdata('miner_session_count', $new_order);
            $this->Database_model->ln_create(array(
                'ln_miner_entity_id' => $session_en['en_id'],
                'ln_type_entity_id' => 4994, //Miner Opened Entity
                'ln_child_entity_id' => $en_id,
                'ln_order' => $new_order,
            ));

        }

        //Validate entity ID and fetch data:
        $ens = $this->Database_model->en_fetch(array(
            'en_id' => $en_id,
        ), array('en__child_count', 'en__children'));
        if (count($ens) < 1) {
            return redirect_message('/entities', '<div class="alert alert-danger" role="alert">Invalid Entity ID</div>');
        }

        //Load views:
        $this->load->view('view_shared/matrix_header', array(
            'title' => $ens[0]['en_name'] . ' | Entities',
            'message' => $message, //Possible mass-action message for UI:
        ));
        $this->load->view('view_entities/en_miner_ui', array(
            'entity' => $ens[0],
        ));
        $this->load->view('view_shared/matrix_footer');

    }


    function password_reset()
    {
        $data = array(
            'title' => 'Password Reset',
        );
        $this->load->view('view_shared/messenger_header', $data);
        $this->load->view('view_entities/en_pass_reset_ui');
        $this->load->view('view_shared/messenger_footer');
    }


    function en_ln_type_preview()
    {

        if (!isset($_POST['ln_content']) || !isset($_POST['ln_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing inputs',
            ));
        }

        //Will Contain every possible Entity Link Connector:
        $en_all_4592 = $this->config->item('en_all_4592');

        //See what this is:
        $detected_ln_type = detect_ln_type_entity_id($_POST['ln_content']);

        if (!$detected_ln_type['status'] && isset($detected_ln_type['url_already_existed']) && $detected_ln_type['url_already_existed']) {

            //See if this is duplicate to either link:
            $en_trs = $this->Database_model->ln_fetch(array(
                'ln_id' => $_POST['ln_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Entity URL Links
            ));

            //Are they both different?
            if (count($en_trs) < 1 || ($en_trs[0]['ln_parent_entity_id'] != $detected_ln_type['en_url']['en_id'] && $en_trs[0]['ln_child_entity_id'] != $detected_ln_type['en_url']['en_id'])) {
                //return error:
                return echo_json($detected_ln_type);
            }
        }

        return echo_json(array(
            'status' => 1,
            'html_ui' => '<a href="/entities/' . $detected_ln_type['ln_type_entity_id'] . '" style="font-weight: bold;" data-toggle="tooltip" data-placement="top" title="' . $en_all_4592[$detected_ln_type['ln_type_entity_id']]['m_desc'] . '">' . $en_all_4592[$detected_ln_type['ln_type_entity_id']]['m_icon'] . ' ' . $en_all_4592[$detected_ln_type['ln_type_entity_id']]['m_name'] . '</a>',
            'en_link_preview' => echo_url_type($_POST['ln_content'], $detected_ln_type['ln_type_entity_id']),
        ));
    }


    function en_save_file_upload()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308));
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh to Continue',
            ));
        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));
        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name']) == 0 || intval($_FILES[$_POST['upload_type']]['size']) == 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown error while trying to save file.',
            ));
        } elseif ($_FILES[$_POST['upload_type']]['size'] > ($this->config->item('en_file_max_size') * 1024 * 1024)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . $this->config->item('en_file_max_size') . ' MB.',
            ));
        }


        //Attempt to save file locally:
        $file_parts = explode('.', $_FILES[$_POST['upload_type']]["name"]);
        $temp_local = "application/cache/temp_files/" . md5($file_parts[0] . $_FILES[$_POST['upload_type']]["type"] . $_FILES[$_POST['upload_type']]["size"]) . '.' . $file_parts[(count($file_parts) - 1)];
        move_uploaded_file($_FILES[$_POST['upload_type']]['tmp_name'], $temp_local);


        //Attempt to store in Mench Cloud on Amazon S3:
        if (isset($_FILES[$_POST['upload_type']]['type']) && strlen($_FILES[$_POST['upload_type']]['type']) > 0) {
            $mime = $_FILES[$_POST['upload_type']]['type'];
        } else {
            $mime = mime_content_type($temp_local);
        }

        $new_file_url = trim(upload_to_cdn($temp_local, $_FILES[$_POST['upload_type']], true));

        //What happened?
        if (!$new_file_url) {
            //Oops something went wrong:
            return echo_json(array(
                'status' => 0,
                'message' => 'Failed to save file to Mench cloud',
            ));
        } else {
            return echo_json(array(
                'status' => 1,
                'new__url' => $new_file_url,
            ));
        }
    }


    function en_load_next_page()
    {

        $en_per_page = $this->config->item('en_per_page');
        $parent_en_id = intval($_POST['parent_en_id']);
        $en_focus_filter = intval($_POST['en_focus_filter']);
        $page = intval($_POST['page']);
        $session_en = en_auth(array(1308));
        $filters = array(
            'ln_parent_entity_id' => $parent_en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'en_status' . ($en_focus_filter < 0 ? ' >=' : '') => ($en_focus_filter < 0 ? 0 /* New+ */ : intval($en_focus_filter)), //Pending or Active
            'ln_status >=' => 0, //New+
        );

        if (!$session_en) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle" style="margin:0 8px 0 2px;"></i> Session expired. Refresh the page and try again.</div>';
            return false;
        }

        //Fetch & display next batch of children, ordered by en_trust_score DESC which is aligned with other entity ordering:
        $child_entities = $this->Database_model->ln_fetch($filters, array('en_child'), $en_per_page, ($page * $en_per_page), array('en_trust_score' => 'DESC'));

        foreach ($child_entities as $en) {
            echo echo_en($en, 2, false);
        }

        //Count total children:
        $child_entities_count = $this->Database_model->ln_fetch($filters, array('en_child'), 0, 0, array(), 'COUNT(ln_id) as totals');

        //Do we need another load more button?
        if ($child_entities_count[0]['totals'] > (($page * $en_per_page) + count($child_entities))) {
            echo_en_load_more(($page + 1), $en_per_page, $child_entities_count[0]['totals']);
        }

    }


    function en_add_or_link()
    {

        //Responsible to link parent/children entities to each other via a JS function on en_miner_ui.php

        //Auth user and check required variables:
        $session_en = en_auth(array(1308));

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        } elseif (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Parent Entity',
            ));
        } elseif (!isset($_POST['is_parent'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Entity Link Direction',
            ));
        } elseif (!isset($_POST['en_existing_id']) || !isset($_POST['en_new_string']) || (intval($_POST['en_existing_id']) < 1 && strlen($_POST['en_new_string']) < 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Either New Entity ID or Name is required',
            ));
        }

        //Validate parent entity:
        $current_us = $this->Database_model->en_fetch(array(
            'en_id' => $_POST['en_id'],
        ));
        if (count($current_us) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid parent entity ID',
            ));
        }


        //Set some variables:
        $_POST['is_parent'] = intval($_POST['is_parent']);
        $_POST['en_existing_id'] = intval($_POST['en_existing_id']);
        $linking_to_existing_u = false;
        $is_url_input = false;
        $ur1 = array();

        //Are we linking to an existing entity?
        if (intval($_POST['en_existing_id']) > 0) {

            //Validate this existing entity:
            $ens = $this->Database_model->en_fetch(array(
                'en_id' => $_POST['en_existing_id'],
                'en_status >=' => 0, //New+
            ));

            if (count($ens) < 1) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid active entity',
                ));
            }

            //All good, assign:
            $entity_new = $ens[0];
            $linking_to_existing_u = true;

        } else {

            //We are creating a new entity OR adding a URL...

            //Is this a URL?
            if (filter_var($_POST['en_new_string'], FILTER_VALIDATE_URL)) {

                //Digest URL to see what type it is and if we have any errors:
                $url_entity = $this->Matrix_model->en_sync_url($_POST['en_new_string']);
                if (!$url_entity['status']) {
                    return echo_json($url_entity);
                }

                //Is this a root domain? Add to domains if so:
                if($url_entity['url_is_root']){

                    //Link to domains parent:
                    $entity_new = array('en_id' => 1326);

                } else {

                    //Let's first find/add the domain:
                    $domain_entity = $this->Matrix_model->en_sync_domain($_POST['en_new_string'], $session_en['en_id']);

                    //Link to this entity:
                    $entity_new = $domain_entity['en_domain'];
                }

            } else {

                //Create entity:
                $added_en = $this->Matrix_model->en_verify_create($_POST['en_new_string'], $session_en['en_id']);
                if(!$added_en['status']){
                    //We had an error, return it:
                    return echo_json($added_en);
                } else {
                    //Assign new entity:
                    $entity_new = $added_en['en'];
                }

            }

        }


        //We need to check to ensure this is not a duplicate link if linking to an existing entity:
        $ur2 = array();

        if (!$is_url_input) {

            //Add links only if not already added by the URL function:
            if ($_POST['is_parent']) {

                $ln_child_entity_id = $current_us[0]['en_id'];
                $ln_parent_entity_id = $entity_new['en_id'];

            } else {

                $ln_child_entity_id = $entity_new['en_id'];
                $ln_parent_entity_id = $current_us[0]['en_id'];

            }


            if (isset($url_entity['url_is_root']) && $url_entity['url_is_root']) {

                $ln_type_entity_id = 4256; //Generic URL (Domains always are generic)
                $ln_content = $url_entity['cleaned_url'];

            } elseif (isset($domain_entity['en_domain'])) {

                $ln_type_entity_id = $url_entity['ln_type_entity_id'];
                $ln_content = $url_entity['cleaned_url'];

            } else {

                $ln_type_entity_id = 4230; //Raw
                $ln_content = null;

            }

            // Link to new OR existing entity:
            $ur2 = $this->Database_model->ln_create(array(
                'ln_miner_entity_id' => $session_en['en_id'],
                'ln_type_entity_id' => $ln_type_entity_id,
                'ln_content' => $ln_content,
                'ln_child_entity_id' => $ln_child_entity_id,
                'ln_parent_entity_id' => $ln_parent_entity_id,
            ));
        }

        //Fetch latest version:
        $ens_latest = $this->Database_model->en_fetch(array(
            'en_id' => $entity_new['en_id'],
        ));

        //Return newly added or linked entity:
        return echo_json(array(
            'status' => 1,
            'en_new_status' => $ens_latest[0]['en_status'],
            'en_new_echo' => echo_en(array_merge($ens_latest[0], $ur2), 2, $_POST['is_parent']),
        ));
    }

    function en_count_to_be_removed_links()
    {

        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Entity ID',
            ));
        }

        //Simply counts the links for a given entity:
        $all_en_links = $this->Database_model->ln_fetch(array(
            'ln_status >=' => 0, //New+
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            '(ln_child_entity_id = ' . $_POST['en_id'] . ' OR ln_parent_entity_id = ' . $_POST['en_id'] . ')' => null,
        ), array(), 999999);

        return echo_json(array(
            'status' => 1,
            'message' => 'Success',
            'en_link_count' => count($all_en_links),
        ));

    }

    function en_modify_save()
    {

        //Auth user and check required variables:
        $session_en = en_auth(array(1308));
        $ln_content_max_length = $this->config->item('ln_content_max_length');
        $success_message = 'Saved'; //Default, might change based on what we do...

        //Fetch current data:
        $ens = $this->Database_model->en_fetch(array(
            'en_id' => intval($_POST['en_id']),
        ), array('en__parents'));

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1 || !(count($ens) == 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid ID',
            ));
        } elseif (!isset($_POST['en_focus_id']) || intval($_POST['en_focus_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Focus ID',
            ));
        } elseif (!isset($_POST['en_name']) || strlen($_POST['en_name']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing name',
            ));
        } elseif (!isset($_POST['en_status'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing status',
            ));
        } elseif (!isset($_POST['ln_id']) || !isset($_POST['ln_content']) || !isset($_POST['ln_status'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity link data',
            ));
        } elseif (strlen($_POST['en_name']) > $this->config->item('en_name_max_length')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Name is longer than the allowed ' . $this->config->item('en_name_max_length') . ' characters. Shorten and try again.',
            ));
        } elseif(!isset($_POST['en_icon']) || !is_valid_icon($_POST['en_icon'])){
            //Check if valid icon:
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid icon: '. is_valid_icon(null, true),
            ));
        }

        $ln_has_updated = false;
        $remove_from_ui = 0;
        $remove_redirect_url = null;
        $js_ln_type_entity_id = 0; //Detect link type based on content

        //Prepare data to be updated:
        $en_update = array(
            'en_name' => trim($_POST['en_name']),
            'en_icon' => trim($_POST['en_icon']),
            'en_status' => intval($_POST['en_status']),
        );

        //Check to make sure name is not duplicate:
        $duplicate_name_ens = $this->Database_model->en_fetch(array(
            'en_id !=' => $_POST['en_id'],
            'en_status >=' => 0, //New+
            'LOWER(en_name)' => strtolower($en_update['en_name']),
        ));
        if(count($duplicate_name_ens) > 0){
            //This is a duplicate, disallow:
            return echo_json(array(
                'status' => 0,
                'message' => 'Name ['.$en_update['en_name'].'] already in use by entity @'.$duplicate_name_ens[0]['en_id'],
            ));
        }


        //Is this being removed?
        if ($en_update['en_status'] < 0 && !($en_update['en_status'] == $ens[0]['en_status'])) {


            //Make sure entity is not referenced in key DB reference fields:
            $en_miners = $this->Database_model->ln_fetch(array(
                'ln_miner_entity_id' => $_POST['en_id'],
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
            $en_link_types = $this->Database_model->ln_fetch(array(
                'ln_type_entity_id' => $_POST['en_id'],
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
            $en_verbs = $this->Database_model->in_fetch(array(
                'in_verb_entity_id' => $_POST['en_id'],
            ), array(), 0, 0, array(), 'COUNT(in_id) as totals');
            $en_requirements = $this->Database_model->in_fetch(array(
                'in_requirement_entity_id' => $_POST['en_id'],
            ), array(), 0, 0, array(), 'COUNT(in_id) as totals');

            if(count($en_miners) > 0 && $en_miners[0]['totals'] > 0){
                //Cannot delete this entity until intent references are removed:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Cannot be removed because entity has mined '.echo_number($en_miners[0]['totals']).' links',
                ));
            } elseif(count($en_link_types) > 0 && $en_link_types[0]['totals'] > 0){
                //Cannot delete this entity until intent references are removed:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Cannot be removed because entity is a link type with '.echo_number($en_link_types[0]['totals']).' links',
                ));
            } elseif(count($en_verbs) > 0 && $en_verbs[0]['totals'] > 0){
                //Cannot delete this entity until intent references are removed:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Cannot be removed because entity is a verb for '.echo_number($en_verbs[0]['totals']).' intents',
                ));
            } elseif(count($en_requirements) > 0 && $en_requirements[0]['totals'] > 0){
                //Cannot delete this entity until intent references are removed:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Cannot be removed because entity is a submission requirement for '.echo_number($en_requirements[0]['totals']).' intents',
                ));
            }





            //Count entity references in Intent Notes:
            $messages = $this->Database_model->ln_fetch(array(
                'ln_status >=' => 0, //New+
                'in_status >=' => 0, //New+
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
                'ln_parent_entity_id' => $_POST['en_id'],
            ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

            //Assume no merge:
            $merged_ens = array();

            //See if we have merger entity:
            if (strlen($_POST['en_merge']) > 0) {

                //Yes, validate this entity:

                //Validate the input for updating linked intent:
                $merger_en_id = 0;
                if (substr($_POST['en_merge'], 0, 1) == '@') {
                    $parts = explode(' ', $_POST['en_merge']);
                    $merger_en_id = intval(str_replace('@', '', $parts[0]));
                }

                if ($merger_en_id < 1) {

                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Unrecognized merger entity [' . $_POST['en_merge'] . ']',
                    ));

                } elseif ($merger_en_id == $_POST['en_id']) {

                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Cannot merge entity into itself',
                    ));

                } else {

                    //Finally validate merger entity:
                    $merged_ens = $this->Database_model->en_fetch(array(
                        'en_id' => $merger_en_id,
                        'en_status >=' => 0, //New+
                    ));
                    if (count($merged_ens) == 0) {
                        return echo_json(array(
                            'status' => 0,
                            'message' => 'Could not find entity @' . $merger_en_id,
                        ));
                    }

                }

            } elseif(count($messages) > 0){

                //Cannot delete this entity until intent references are removed:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'You can remove entity after removing all its intent note references',
                ));

            }

            //Remove/merge entity links:
            $_POST['ln_id'] = 0; //Do not consider the link as the entity is being Removed
            $remove_from_ui = 1; //Removing entity
            $merger_en_id = (count($merged_ens) > 0 ? $merged_ens[0]['en_id'] : 0);
            $links_adjusted = $this->Matrix_model->en_unlink($_POST['en_id'], $session_en['en_id'], $merger_en_id);

            //Show appropriate message based on action:
            if ($merger_en_id > 0) {

                if($_POST['en_id'] == $_POST['en_focus_id'] || $merged_ens[0]['en_id'] == $_POST['en_focus_id']){
                    //Entity is being Removed and merged into another entity:
                    $remove_redirect_url = '/entities/' . $merged_ens[0]['en_id'];
                }

                $success_message = 'Entity removed and merged its ' . $links_adjusted . ' links here';

            } else {

                if($_POST['en_id'] == $_POST['en_focus_id']){
                    //Fetch parents to redirect to:
                    $remove_redirect_url = '/entities' . (isset($ens[0]['en__parents'][0]['en_id']) ? '/' . $ens[0]['en__parents'][0]['en_id'] : '');
                }

                //Display proper message:
                $success_message = 'Entity and its ' . $links_adjusted . ' links removed successfully';

            }

        }


        if (intval($_POST['ln_id']) > 0) { //DO we have a link to update?

            //Yes, first validate entity link:
            $en_trs = $this->Database_model->ln_fetch(array(
                'ln_id' => $_POST['ln_id'],
                'ln_status >=' => 0, //New+
            ));

            if (count($en_trs) < 1) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Entity Link ID',
                ));
            }

            if ($en_trs[0]['ln_content'] == $_POST['ln_content']) {

                //Link content has not changed:
                $js_ln_type_entity_id = $en_trs[0]['ln_type_entity_id'];
                $ln_content = $en_trs[0]['ln_content'];

            } else {

                //Link content has changed:
                $detected_ln_type = detect_ln_type_entity_id($_POST['ln_content']);

                if (!$detected_ln_type['status']) {

                    return echo_json($detected_ln_type);

                } elseif (in_array($detected_ln_type['ln_type_entity_id'], $this->config->item('en_ids_4537'))) {

                    //This is a URL, validate modification:

                    if ($detected_ln_type['url_is_root']) {

                        if ($en_trs[0]['ln_parent_entity_id'] == 1326) {

                            //Override with the clean domain for consistency:
                            $_POST['ln_content'] = $detected_ln_type['url_clean_domain'];

                        } else {

                            //Domains can only be added to the domain entity:
                            return echo_json(array(
                                'status' => 0,
                                'message' => 'Domain URLs must link to <b>@1326 Domains</b> as their parent entity',
                            ));

                        }

                    } else {

                        if ($en_trs[0]['ln_parent_entity_id'] == 1326) {

                            return echo_json(array(
                                'status' => 0,
                                'message' => 'Only domain URLs can be linked to Domain entity.',
                            ));

                        } elseif ($detected_ln_type['en_domain']) {
                            //We do have the domain mapped! Is this connected to the domain entity as its parent?
                            if ($detected_ln_type['en_domain']['en_id'] != $en_trs[0]['ln_parent_entity_id']) {
                                return echo_json(array(
                                    'status' => 0,
                                    'message' => 'Must link to <b>@' . $detected_ln_type['en_domain']['en_id'] . ' ' . $detected_ln_type['en_domain']['en_name'] . '</b> as their parent entity',
                                ));
                            }
                        } else {
                            //We don't have the domain mapped, this is for sure not allowed:
                            return echo_json(array(
                                'status' => 0,
                                'message' => 'Requires a new parent entity for <b>' . $detected_ln_type['url_tld'] . '</b>. Add by pasting URL into the [Add @Entity] input field.',
                            ));
                        }

                    }

                }

                //Update variables:
                $ln_content = $_POST['ln_content'];
                $js_ln_type_entity_id = $detected_ln_type['ln_type_entity_id'];
            }


            //Has the link content changes?
            if (!($en_trs[0]['ln_content'] == $_POST['ln_content']) || !($en_trs[0]['ln_status'] == $_POST['ln_status'])) {

                if ($_POST['ln_status'] < 0) {
                    $remove_from_ui = 1;
                }

                $ln_has_updated = true;

                //Something has changed, log this:
                $this->Database_model->ln_update($_POST['ln_id'], array(
                    'ln_content' => $ln_content,
                    'ln_type_entity_id' => $js_ln_type_entity_id,
                    'ln_status' => intval($_POST['ln_status']),
                    //Auto append timestamp and most recent miner:
                    'ln_miner_entity_id' => $session_en['en_id'],
                    'ln_timestamp' => date("Y-m-d H:i:s"),
                ), $session_en['en_id']);

            }

        }

        //Now update the DB:
        $this->Database_model->en_update(intval($_POST['en_id']), $en_update, true, $session_en['en_id']);


        //Reset user session data if this data belongs to the logged-in user:
        if ($_POST['en_id'] == $session_en['en_id']) {
            $ens = $this->Database_model->en_fetch(array(
                'en_id' => intval($_POST['en_id']),
            ));
            if (isset($ens[0])) {
                $this->session->set_userdata(array('user' => $ens[0]));
            }
        }


        if ($remove_redirect_url) {
            //Page will be refresh, set flash message to be shown after restart:
            $this->session->set_flashdata('flash_message', '<div class="alert alert-success" role="alert">' . $success_message . '</div>');
        }

        //Start return array:
        $return_array = array(
            'status' => 1,
            'message' => '<i class="fas fa-check"></i> ' . $success_message,
            'remove_from_ui' => $remove_from_ui,
            'remove_redirect_url' => $remove_redirect_url,
            'js_ln_type_entity_id' => intval($js_ln_type_entity_id),
        );

        if (intval($_POST['ln_id']) > 0) {

            //Fetch entity link:
            $lns = $this->Database_model->ln_fetch(array(
                'ln_id' => $_POST['ln_id'],
            ), array('en_miner'));

            //Prep last updated:
            $return_array['ln_content'] = echo_tr_urls($ln_content, $js_ln_type_entity_id);
            $return_array['ln_content_final'] = $ln_content; //In case content was updated

        }

        //Show success:
        return echo_json($return_array);

    }


    function en_load_messages($en_id)
    {

        $session_en = en_auth();
        if (!$session_en) {
            //Display error:
            die('<span style="color:#FF0000;">Error: Invalid Session. Sign In again to continue.</span>');
        }

        $messages = $this->Database_model->ln_fetch(array(
            'ln_status >=' => 0, //New+
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
            'ln_parent_entity_id' => $en_id,
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));


        //Always skip header:
        $_GET['skip_header'] = 1;

        //Show frame to be loaded in modal:
        $this->load->view('view_shared/matrix_header', array(
            'title' => 'Managed Intent Notes',
        ));
        echo '<div id="list-messages" class="list-group grey-list">';
        foreach ($messages as $ln) {
            echo echo_en_messages($ln);
        }
        echo '</div>';
        $this->load->view('view_shared/matrix_footer');
    }


    function en_login_ui()
    {
        //Check to see if they are already logged in?
        $session_en = $this->session->userdata('user');
        if (isset($session_en['en__parents'][0]) && filter_array($session_en['en__parents'], 'en_id', 1308)) {
            //Lead miner and above, go to console:
            return redirect_message('/intents/' . $this->config->item('in_miner_start'));
        }

        $this->load->view('view_shared/public_header', array(
            'title' => 'Sign In',
        ));
        $this->load->view('view_entities/en_login_ui');
        $this->load->view('view_shared/public_footer');
    }

    function en_login_process()
    {

        //Setting for admin Sign Ins:

        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Enter valid email to continue.</div>');
        } elseif (!isset($_POST['en_password'])) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Enter valid password to continue.</div>');
        }

        //Validate user email:
        $lns = $this->Database_model->ln_fetch(array(
            'ln_parent_entity_id' => 3288, //Primary email
            'LOWER(ln_content)' => strtolower($_POST['input_email']),
        ));

        if (count($lns) == 0) {
            //Not found!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: ' . $_POST['input_email'] . ' not found.</div>');
        }

        //Fetch full entity data with their active Action Plans:
        $ens = $this->Database_model->en_fetch(array(
            'en_id' => $lns[0]['ln_child_entity_id'],
        ));

        if ($ens[0]['en_status'] < 0 || $lns[0]['ln_status'] < 0) {

            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Your account has been de-activated. Contact us to re-active your account.</div>');

        }

        //Authenticate their password:
        $student_passwords = $this->Database_model->ln_fetch(array(
            'ln_status' => 2,
            'ln_type_entity_id' => 4255, //Text
            'ln_parent_entity_id' => 3286, //Password
            'ln_child_entity_id' => $ens[0]['en_id'],
        ));
        if (count($student_passwords) == 0) {
            //They do not have a password assigned yet!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: An active login password has not been assigned to your account yet. You can assign a new password using the Forgot Password Button.</div>');
        } elseif ($student_passwords[0]['ln_status'] < 2) {
            //They do not have a password assigned yet!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Password is not activated with status [' . $student_passwords[0]['ln_status'] . '].</div>');
        } elseif ($student_passwords[0]['ln_content'] != strtolower(hash('sha256', $this->config->item('password_salt') . $_POST['en_password']))) {
            //Bad password
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Incorrect password for [' . $_POST['input_email'] . ']</div>');
        }

        //Now let's do a few more checks:

        //Make sure Student is connected to Mench:
        if (!intval($ens[0]['en_psid'])) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: You are not connected to Mench on Messenger, which is required to login to the Matrix.</div>');
        }

        //Make sure Student is not unsubscribed:
        if (count($this->Database_model->ln_fetch(array(
                'ln_child_entity_id' => $ens[0]['en_id'],
                'ln_parent_entity_id' => 4455, //Unsubscribed
                'ln_status >=' => 0,
            ))) > 0) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: You cannot login to the Matrix because you are unsubscribed from Mench. You can re-active your account by sending a message to Mench on Messenger.</div>');
        }


        $session_data = array();
        $is_chrome = (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') !== false);
        $is_miner = false;
        $is_student = false;


        //Are they miner? Give them Sign In access:
        if (filter_array($ens[0]['en__parents'], 'en_id', 1308)) {

            //Check their advance mode status:
            $last_advance_settings = $this->Database_model->ln_fetch(array(
                'ln_miner_entity_id' => $ens[0]['en_id'],
                'ln_type_entity_id' => 5007, //Toggled Advance Mode
                'ln_status >=' => 0, //New+
            ), array(), 1, 0, array('ln_id' => 'DESC'));

            //They have admin rights:
            $session_data['user'] = $ens[0];
            $session_data['miner_session_count'] = 0;
            $session_data['advance_view_enabled'] = ( count($last_advance_settings) > 0 && substr_count($last_advance_settings[0]['ln_content'] , ' ON')==1 ? 1 : 0 );
            $is_miner = true;
        }


        //Applicable for miners only:
        if (!$is_chrome) {

            if ($is_student) {

                //Remove miner privileges as they cannot use the matrix with non-chrome Browser:
                $is_miner = false;
                unset($session_data['user']);

            } else {

                return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Sign In Denied. The Matrix v' . $this->config->item('app_version') . ' supports <a href="https://www.google.com/chrome/browser/" target="_blank"><u>Google Chrome</u></a> only.</div>');

            }

        } elseif (!$is_miner && !$is_student) {

            //We assume this is a student request:
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: You have not added any intentions to your Action Plan yet.</div>');

        }


        //Append user IP and agent information
        if (isset($_POST['en_password'])) {
            unset($_POST['en_password']); //Sensitive information to be removed and NOT logged
        }

        //Log additional information:
        $ens[0]['login_ip'] = $_SERVER['REMOTE_ADDR'];
        $ens[0]['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $ens[0]['input_post_data'] = $_POST;


        //Log Sign In Link:
        if($is_miner){
            $this->Database_model->ln_create(array(
                'ln_miner_entity_id' => $ens[0]['en_id'],
                'ln_metadata' => $ens[0],
                'ln_type_entity_id' => 4269, //Miner Matrix Login
                'ln_order' => $session_data['miner_session_count'], //First Action
            ));
        } else {
            $this->Database_model->ln_create(array(
                'ln_miner_entity_id' => $ens[0]['en_id'],
                'ln_metadata' => $ens[0],
                'ln_type_entity_id' => 4996, //Action Plan Web Login
            ));
        }


        //All good to go!
        //Load session and redirect:
        $this->session->set_userdata($session_data);


        if (isset($_POST['url']) && strlen($_POST['url']) > 0) {
            header('Location: ' . $_POST['url']);
        } else {
            //Default:
            if ($is_miner) {
                //miner default:
                header('Location: /intents/' . $this->config->item('in_miner_start'));
            } else {
                //Student default:
                header('Location: /messenger/actionplan');
            }
        }
    }

    function logout()
    {
        //Destroys Session
        $this->session->sess_destroy();
        header('Location: /');
    }


    function password_initiate_reset()
    {

        //We need an email input:
        if (!isset($_POST['email'])) {
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Missing Email.</div>');
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Invalid Email.</div>');
        }


        //Attempt to fetch this user:
        $matching_users = $this->Database_model->en_fetch(array(
            'input_email' => strtolower($_POST['email']),
        ));
        if (count($matching_users) > 0) {

            $timestamp = time();

            //Dispatch the password reset Intent:
            $this->Chat_model->dispatch_message(
                'Hi /firstname 👋​ You can reset your Mench password here: /link:🔑 Reset Password:https://mench.com/entities/password_reset?en_id=' . $matching_users[0]['en_id'] . '&timestamp=' . $timestamp . '&p_hash=' . md5($matching_users[0]['en_id'] . $this->config->item('password_salt') . $timestamp) . ' (Link active for 24 hours)',
                $matching_users[0],
                true
            );

        }

        //Show message:
        echo '<div class="alert alert-success">Password reset accepted. You will receive an email only if you have a registered Mench account.</div>';
        echo '<script> $(document).ready(function() { $(".pass_success").hide(); }); </script>';

    }


    function password_process_reset()
    {
        //This function updates the user's new password as requested via a password reset:
        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1 || !isset($_POST['timestamp']) || intval($_POST['timestamp']) < 1 || !isset($_POST['p_hash']) || strlen($_POST['p_hash']) < 10) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Missing Core Variables.</div>';
        } elseif (!($_POST['p_hash'] == md5($_POST['en_id'] . $this->config->item('password_salt') . $_POST['timestamp']))) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Invalid hash key.</div>';
        } elseif (!isset($_POST['new_pass']) || strlen($_POST['new_pass']) < 6) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: New password must be longer than 6 characters. Try again.</div>';
        } else {

            //Fetch their passwords to authenticate login:
            $student_passwords = $this->Database_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'ln_parent_entity_id' => 3286, //Mench Sign In Password
                'ln_child_entity_id' => $_POST['en_id'], //For this user
            ));

            $new_password = hash('sha256', $this->config->item('password_salt') . $_POST['new_pass']);

            if (count($student_passwords) > 0) {

                $detected_ln_type = detect_ln_type_entity_id($new_password);
                if (!$detected_ln_type['status']) {
                    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: ' . $detected_ln_type['message'] . '</div>';
                }

                //Update existing password:
                $this->Database_model->ln_update($student_passwords[0]['ln_id'], array(
                    'ln_content' => $new_password,
                    'ln_type_entity_id' => $detected_ln_type['ln_type_entity_id'],
                ), $student_passwords[0]['ln_child_entity_id']);

            } else {
                //Create new password link:

            }


            //Log all sessions out:
            $this->session->sess_destroy();

            //Show message:
            echo '<div class="alert alert-success">Passsword reset successful. You can <a href="/login"><u>login here</u></a>.</div>';
            echo '<script> $(document).ready(function() { $(".pass_success").hide(); }); </script>';
        }
    }


    function en_fetch_canonical_url(){

        //Auth user and check required variables:
        $session_en = en_auth(array(1308));

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['search_url']) || !filter_var($_POST['search_url'], FILTER_VALIDATE_URL)) {
            //This string was incorrectly detected as a URL by JS, return not found:
            return echo_json(array(
                'status' => 1,
                'url_already_existed' => 0,
            ));
        }

        //Fetch URL:
        $url_entity = $this->Matrix_model->en_sync_url($_POST['search_url']);

        if($url_entity['url_already_existed']){
            return echo_json(array(
                'status' => 1,
                'url_already_existed' => 1,
                'algolia_object' => $this->Database_model->update_algolia('en', $url_entity['en_url']['en_id'], 1),
            ));
        } else {
            return echo_json(array(
                'status' => 1,
                'url_already_existed' => 0,
            ));
        }
    }


    function en_add_source_process()
    {

        //Auth user and check required variables:
        $session_en = en_auth(array(1308));

        //Description type requirement:
        $contributor_type_requirement = array(4230, 4255); //Raw or Text string

        //Parent sources to be added:
        $parent_ens = array();

        //Load some config variables:
        $en_all_3000 = $this->config->item('en_all_3000');
        $en_all_4592 = $this->config->item('en_all_4592');

        //Analyze domain:
        $domain_analysis = analyze_domain($_POST['source_url']);

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['source_url']) || !filter_var($_POST['source_url'], FILTER_VALIDATE_URL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid URL',
            ));
        } elseif (!isset($_POST['source_parent_ens']) || count($_POST['source_parent_ens']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Select at-least 1 source type',
            ));
        } elseif (!isset($_POST['en_name']) || strlen($_POST['en_name']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity name',
            ));
        } elseif ($domain_analysis['url_is_root']) {
            return echo_json(array(
                'status' => 0,
                'message' => 'A source URL cannot reference the root domain',
            ));
        }


        //Validate Parent descriptions:
        foreach ($_POST['source_parent_ens'] as $this_parent_en) {

            $detected_ln_type = detect_ln_type_entity_id($this_parent_en['this_parent_en_desc']);

            if (!$detected_ln_type['status']) {

                return echo_json(array(
                    'status' => 0,
                    'message' => $en_all_3000[$this_parent_en['this_parent_en_id']]['m_name'] . ' description error: ' . $detected_ln_type['message'],
                ));

            } elseif (!in_array($detected_ln_type['ln_type_entity_id'], $contributor_type_requirement)) {

                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid ' . $en_all_3000[$this_parent_en['this_parent_en_id']]['m_name'] . ' description type.',
                ));

            }

            //Add expert source type to parent source array:
            array_push($parent_ens, array(
                'this_parent_en_id' => $this_parent_en['this_parent_en_id'],
                'this_parent_en_type' => $detected_ln_type['ln_type_entity_id'],
                'this_parent_en_desc' => trim($this_parent_en['this_parent_en_desc']),
            ));

        }


        //Now parse referenced contributors:
        $found_contributors = 0;
        for ($contributor_num = 1; $contributor_num <= 5; $contributor_num++) {

            //Do we have an contributor?
            if (strlen($_POST['contributor_' . $contributor_num]) < 1) {
                continue;
            }

            //Validate role information:
            $detected_role_ln_type = detect_ln_type_entity_id($_POST['auth_role_' . $contributor_num]);

            if (!$detected_role_ln_type['status']) {

                return echo_json(array(
                    'status' => 0,
                    'message' => 'Contributor #' . $contributor_num . ' role error: ' . $detected_role_ln_type['message'],
                ));

            } elseif (!in_array($detected_role_ln_type['ln_type_entity_id'], $contributor_type_requirement)) {

                return echo_json(array(
                    'status' => 0,
                    'message' => 'Contributor #' . $contributor_num . ' has an invalid role',
                ));

            }


            //Is this referencing an existing entity or is it a new entity?
            $ln_en_link_id = 0; //Assume it's a new entity...

            if (substr($_POST['contributor_' . $contributor_num], 0, 1) == '@') {
                $parts = explode(' ', $_POST['contributor_' . $contributor_num]);
                $ln_en_link_id = intval(str_replace('@', '', $parts[0]));
            }

            if ($ln_en_link_id > 0) {

                //Validate existing entity reference:
                $referenced_ens = $this->Database_model->en_fetch(array(
                    'en_status >=' => 0, //New+
                    'en_id' => $ln_en_link_id,
                ));
                if (count($referenced_ens) < 1) {
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Contributor #' . $contributor_num . ' entity ID @' . $ln_en_link_id . ' is invalid',
                    ));
                } elseif(count($this->Database_model->ln_fetch(array( //Make sure this entity is linked to industry experts:
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                        'ln_parent_entity_id' => 3084, //Industry Experts
                        'ln_child_entity_id' => $referenced_ens[0]['en_id'],
                        'ln_status >=' => 0, //New+
                    ))) == 0){
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Contributor #' . $contributor_num . ' is not linked to @3084 Industry Experts. If you believe '.$referenced_ens[0]['en_name'].' is an industry expert, first create a link to Industry Experts from <a href="/entities/'.$referenced_ens[0]['en_id'].'" target="_blank"><b>here<i class="fas fa-external-link"></i></b></a> and then try saving this source again.',
                    ));
                }

                //Add contributor to parent source array:
                array_push($parent_ens, array(
                    'this_parent_en_id' => $ln_en_link_id,
                    'this_parent_en_type' => $detected_role_ln_type['ln_type_entity_id'],
                    'this_parent_en_desc' => trim($_POST['auth_role_' . $contributor_num]),
                ));

            } else {

                //Seems to be a new contributor entity...

                //First analyze URL:
                $contributor_url_entity = $this->Matrix_model->en_sync_url($_POST['ref_url_' . $contributor_num]);

                //Validate contributor inputs before creating anything:
                if (!$contributor_url_entity['status']) {

                    //Oooopsi, show errors:
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Contributor #' . $contributor_num . ' URL error: ' . $contributor_url_entity['message'],
                    ));

                } elseif (!in_array($_POST['entity_parent_id_' . $contributor_num], $this->config->item('en_ids_4600'))) {

                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Contributor #' . $contributor_num . ' missing [Add as...] type',
                    ));


                } elseif (strlen($_POST['why_expert_' . $contributor_num]) < 1) {

                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Contributor #' . $contributor_num . ' missing expert summary.',
                    ));

                }

                //Validate Expert summary notes:
                $detected_ln_type = detect_ln_type_entity_id($_POST['why_expert_' . $contributor_num]);

                if (!$detected_ln_type['status']) {

                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Contributor #' . $contributor_num . ' error: ' . $detected_ln_type['message'],
                    ));

                } elseif (!in_array($detected_ln_type['ln_type_entity_id'], $contributor_type_requirement)) {

                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Invalid Contributor #' . $contributor_num . ' expert note content type.',
                    ));

                }

                //Add contributor with its URL:
                $sync_contributor = $this->Matrix_model->en_sync_url($_POST['ref_url_' . $contributor_num], $session_en['en_id'], 0, 0, $_POST['contributor_' . $contributor_num]);


                //Add contributor to People or Organizations entity:
                $this->Database_model->ln_create(array(
                    'ln_status' => 2, //Published
                    'ln_miner_entity_id' => $session_en['en_id'],
                    'ln_type_entity_id' => 4230, //Raw
                    'ln_parent_entity_id' => $_POST['entity_parent_id_' . $contributor_num], //People or Organizations
                    'ln_child_entity_id' => $sync_contributor['en_url']['en_id'],
                ), true);


                //Should we also link contributor to to Industry Experts entity?
                if (strlen($_POST['why_expert_' . $contributor_num]) > 0) {
                    //Add contributor to industry experts:
                    $this->Database_model->ln_create(array(
                        'ln_status' => 2, //Published
                        'ln_miner_entity_id' => $session_en['en_id'],
                        'ln_content' => trim($_POST['why_expert_' . $contributor_num]),
                        'ln_type_entity_id' => $detected_ln_type['ln_type_entity_id'],
                        'ln_parent_entity_id' => 3084, //Industry Experts
                        'ln_child_entity_id' => $sync_contributor['en_url']['en_id'],
                    ), true);
                }

                //Add contributor to parent source array:
                array_push($parent_ens, array(
                    'this_parent_en_id' => $sync_contributor['en_url']['en_id'],
                    'this_parent_en_type' => $detected_role_ln_type['ln_type_entity_id'],
                    'this_parent_en_desc' => trim($_POST['auth_role_' . $contributor_num]),
                ));

            }

            //We found an contributor:
            $found_contributors++;
        }


        //Did we have any expert contributors?
        if($found_contributors < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Define at-least 1 expert contributor',
            ));
        }


        //Save URL & domain:
        $url_entity = $this->Matrix_model->en_sync_url($_POST['source_url'], $session_en['en_id'], 0, 0, $_POST['en_name']);
        if (!$url_entity['status']) {
            return echo_json($url_entity);
        }


        //Link content to all parent entities:
        foreach ($parent_ens as $this_parent_en) {
            //Insert new relation:
            $this->Database_model->ln_create(array(
                'ln_status' => 2, //Published
                'ln_miner_entity_id' => $session_en['en_id'],
                'ln_child_entity_id' => $url_entity['en_url']['en_id'],
                'ln_parent_entity_id' => $this_parent_en['this_parent_en_id'],
                'ln_type_entity_id' => $this_parent_en['this_parent_en_type'],
                'ln_content' => $this_parent_en['this_parent_en_desc'],
            ), true);
        }


        //Success:
        return echo_json(array(
            'status' => 1,
            'new_source_id' => $url_entity['en_url']['en_id'], //Redirects to this entity...
        ));

    }




    function cron__update_trust_score($en_id = 0)
    {

        /*
         *
         * Entities are measured through a custom algorithm that measure their "Trust Score"
         * It's how we primarily assess the weight of each entity in our network.
         * This function defines this algorithm.
         *
         * If $en_id not provided it would update all entities...
         *
         * */

        //Algorithm Weights:
        $score_weights = array(
            'score_parent' => 5, //Score per each parent entity
            'score_children' => 2, //Score per each child entity
            'score_link' => 0.25, //Score per each link of any type and any status
            'score_miner_points' => 0.10, // This is X where: 1 miner points = X score
        );

        //Fetch entities with/without filter:
        $ens = $this->Database_model->en_fetch(array(
            'en_id '.( $en_id > 0 ? '=' : '>=' ) => $en_id,
        ));

        //Fetch child entities:
        foreach ($ens as $en){

            //Calculate trust score:
            $score = 0;

            //Parents
            $en_parents = $this->Database_model->ln_fetch(array(
                'ln_child_entity_id' => $en['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status >=' => 0,
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
            $score += $en_parents[0]['totals'] * $score_weights['score_parent'];

            //Children:
            $en_children = $this->Database_model->ln_fetch(array(
                'ln_parent_entity_id' => $en['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status >=' => 0,
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
            $score += $en_children[0]['totals'] * $score_weights['score_children'];

            //Links:
            $en_trs = $this->Database_model->ln_fetch(array(
                '(ln_parent_entity_id='.$en['en_id'].' OR ln_child_entity_id='.$en['en_id'].')' => null,
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
            $score += $en_trs[0]['totals'] * $score_weights['score_link'];

            //Mining points:
            $en_miner_points = $this->Database_model->ln_fetch(array(
                'ln_miner_entity_id' => $en['en_id'],
                'ln_status >=' => 0,
            ), array(), 0, 0, array(), 'SUM(ln_points) as total_points');
            $score += $en_miner_points[0]['total_points'] * $score_weights['score_miner_points'];

            //Do we need to update?
            if($en['en_trust_score'] != $score){
                //Yes:
                $this->Database_model->en_update($en['en_id'], array(
                    'en_trust_score' => round($score, 0),
                ));
            }
        }

        echo 'Successfully updated trust score for '.count($ens).' entities.';
    }


}
