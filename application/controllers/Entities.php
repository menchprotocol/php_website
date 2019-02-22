<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entities extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }


    function fn___add_source_wizard()
    {
        //Authenticate Miner, redirect if failed:
        $session_en = fn___en_auth(array(1308), true);

        //Show frame to be loaded in modal:
        $this->load->view('view_shared/matrix_header', array(
            'title' => 'Add Source Wizard',
        ));
        $this->load->view('view_entities/add_source_frame');
        $this->load->view('view_shared/matrix_footer');
    }


    function fn___add_source_paste_url()
    {

        /*
         *
         * Validates the input URL to be added as a new source entity
         *
         * */

        $session_en = fn___en_auth(array(1308));
        if (!$session_en) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh to Continue',
                'digested_url' => array(),
            ));
        }

        //All seems good, fetch URL:
        $digested_url = $this->Matrix_model->fn___digest_url($_POST['input_url']);

        if (!$digested_url['status']) {
            //Oooopsi, we had some error:
            return fn___echo_json(array(
                'status' => 0,
                'message' => $digested_url['message'],
            ));
        }

        //Return results:
        return fn___echo_json(array(
            'status' => 1,
            'entity_domain_ui' => '<span class="en_icon_mini_ui">'. echo_fav_icon($digested_url['url_clean_domain'], true) . '</span> '.( isset($digested_url['en_domain']['en_name']) ? $digested_url['en_domain']['en_name'].' <a href="/entities/'.$digested_url['en_domain']['en_id'].'" class="underdot" data-toggle="tooltip" title="Click to open domain entity in a new windows" data-placement="top" target="_blank">@'.$digested_url['en_domain']['en_id'].'</a>' : $digested_url['url_domain_name'].' [<span class="underdot" data-toggle="tooltip" title="Domain entity not yet added" data-placement="top">New</span>]' ),
            'js_digested_url' => $digested_url,
        ));

    }


    //Lists entities
    function en_miner_ui($en_id)
    {

        if ($en_id == 0) {
            //Set to default:
            $en_id = $this->config->item('en_start_here_id');
        }

        $session_en = fn___en_auth(null, true); //Just be logged in to browse

        //Do we have any mass actions?
        if (isset($_POST['action_type'])) {

            //Fetch children:
            $children = $this->Database_model->fn___tr_fetch(array(
                'tr_en_parent_id' => $en_id,
                'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'tr_status >=' => 0, //New+
                'en_status >=' => 0, //New+
            ), array('en_child'), 0);

            if (!isset($_POST['modify_text'])) {
                $this->session->set_flashdata('hm', '<div class="alert alert-danger" role="alert">Missing string</div>');
            } elseif (!array_key_exists($_POST['action_type'], $this->config->item('en_mass_actions'))) {
                $this->session->set_flashdata('hm', '<div class="alert alert-danger" role="alert">Unknown action type</div>');
            } elseif (count($children) < 1) {
                $this->session->set_flashdata('hm', '<div class="alert alert-danger" role="alert">No child entities found</div>');
            } else {

                $applied_success = 0;

                //Process request:
                foreach ($children as $en) {

                    //Logic here must match items in en_mass_actions config variable

                    //What is the action?
                    if ($_POST['action_type'] == 'replace_icon' && !($en['en_icon'] == trim($_POST['modify_text']))) {
                        //Update with new icon:
                        $this->Database_model->fn___en_update($en['en_id'], array(
                            'en_icon' => trim($_POST['modify_text']),
                        ), true, $session_en['en_id']);

                        $applied_success++;
                    } elseif ($_POST['action_type'] == 'prefix_add') {

                        //Update with new icon:
                        $this->Database_model->fn___en_update($en['en_id'], array(
                            'en_name' => $_POST['modify_text'] . $en['en_name'],
                        ), true, $session_en['en_id']);

                        $applied_success++;

                    } elseif ($_POST['action_type'] == 'postfix_add') {

                        //Update with new icon:
                        $this->Database_model->fn___en_update($en['en_id'], array(
                            'en_name' => $en['en_name'] . $_POST['modify_text'],
                        ), true, $session_en['en_id']);

                        $applied_success++;

                    } elseif ($_POST['action_type'] == 'replace_match' && substr_count($_POST['modify_text'], '>>') == 1) {

                        //Validate input:
                        $parts = explode('>>', $_POST['modify_text']);

                        if (count($parts) == 2 && strlen($parts[0]) > 0 && substr_count($en['en_name'], $parts[0]) > 0) {

                            //Update with new icon:
                            $this->Database_model->fn___en_update($en['en_id'], array(
                                'en_name' => str_replace($parts[0], $parts[1], $en['en_name']),
                            ), true, $session_en['en_id']);

                            $applied_success++;

                        }

                    } elseif ($_POST['action_type'] == 'replace_tr_match' && substr_count($_POST['modify_text'], '>>') == 1) {

                        //Validate input:
                        $parts = explode('>>', $_POST['modify_text']);

                        if (count($parts) == 2 && strlen($parts[0]) > 0 && substr_count($en['tr_content'], $parts[0]) > 0) {

                            //Update with new icon:
                            $this->Database_model->fn___tr_update($en['tr_id'], array(
                                'tr_content' => str_replace($parts[0], $parts[1], $en['tr_content']),
                            ), $session_en['en_id']);

                            $applied_success++;

                        }

                    }

                }


                $config['en_mass_actions'] = array( //Various mass actions to be taken on Entity children
                    '' => 'Add string as prefix',
                    '' => 'Add string as postfix',
                    '' => 'Replace matching strings',
                );

                if ($applied_success > 0) {
                    $this->session->set_flashdata('hm', '<div class="alert alert-success" role="alert">Successfully updated ' . $applied_success . '/' . count($children) . ' entities</div>');
                } else {
                    $this->session->set_flashdata('hm', '<div class="alert alert-warning" role="alert">Nothing was updated</div>');
                }

            }
        }

        //Fetch data:
        $ens = $this->Database_model->fn___en_fetch(array(
            'en_id' => $en_id,
        ), array('en__child_count', 'en__children', 'en__actionplans'));

        if (count($ens) < 1) {
            return fn___redirect_message('/entities', '<div class="alert alert-danger" role="alert">Invalid Entity ID</div>');
        }

        //Load views:
        $this->load->view('view_shared/matrix_header', array(
            'title' => $ens[0]['en_name'] . ' | Entities',
        ));
        $this->load->view('view_entities/en_miner_ui', array(
            'entity' => $ens[0],
        ));
        $this->load->view('view_shared/matrix_footer');
    }


    function reset_pass()
    {
        $data = array(
            'title' => 'Password Reset',
        );
        $this->load->view('view_shared/messenger_header', $data);
        $this->load->view('view_entities/en_pass_reset_ui');
        $this->load->view('view_shared/messenger_footer');
    }


    function fn___update_link_type()
    {

        if (!isset($_POST['tr_content']) || !isset($_POST['tr_id'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing inputs',
            ));
        }

        //Will Contain every possible Entity Link Connector:
        $en_all_4592 = $this->config->item('en_all_4592');

        //See what this is:
        $detected_tr_type = fn___detect_tr_type_en_id($_POST['tr_content']);

        if (!$detected_tr_type['status'] && isset($detected_tr_type['url_already_existed']) && $detected_tr_type['url_already_existed']) {

            //See if this is duplicate to either link:
            $en_trs = $this->Database_model->fn___tr_fetch(array(
                'tr_id' => $_POST['tr_id'],
                'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Entity URL Links
            ));

            //Are they both different?
            if(count($en_trs)<1 || ($en_trs[0]['tr_en_parent_id']!=$detected_tr_type['en_url']['en_id'] && $en_trs[0]['tr_en_child_id']!=$detected_tr_type['en_url']['en_id'])){
                //return error:
                return fn___echo_json($detected_tr_type);
            }
        }

        return fn___echo_json(array(
            'status' => 1,
            'html_ui' => '<a href="/entities/' . $detected_tr_type['tr_type_en_id'] . '" style="font-weight: bold;" data-toggle="tooltip" data-placement="top" title="' . $en_all_4592[$detected_tr_type['tr_type_en_id']]['m_desc'] . '">' . $en_all_4592[$detected_tr_type['tr_type_en_id']]['m_icon'] . ' ' . $en_all_4592[$detected_tr_type['tr_type_en_id']]['m_name'] . '</a>',
            'en_link_preview' => fn___echo_url_type($_POST['tr_content'], $detected_tr_type['tr_type_en_id']),
        ));
    }


    function fn___en_new_url_from_attachment()
    {

        //Authenticate Miner:
        $session_en = fn___en_auth(array(1308));
        if (!$session_en) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh to Continue',
            ));
        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));
        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name']) == 0 || intval($_FILES[$_POST['upload_type']]['size']) == 0) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Unknown error while trying to save file.',
            ));
        } elseif ($_FILES[$_POST['upload_type']]['size'] > ($this->config->item('file_size_max') * 1024 * 1024)) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . $this->config->item('file_size_max') . ' MB.',
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

        $new_file_url = trim(fn___upload_to_cdn($temp_local, $_FILES[$_POST['upload_type']], true));

        //What happened?
        if (!$new_file_url) {
            //Oops something went wrong:
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Failed to save file to Mench cloud',
            ));
        } else {
            return fn___echo_json(array(
                'status' => 1,
                'new__url' => $new_file_url,
            ));
        }
    }


    function fn___en_load_next_page()
    {

        $en_per_page = $this->config->item('en_per_page');
        $parent_en_id = intval($_POST['parent_en_id']);
        $en_focus_filter = intval($_POST['en_focus_filter']);
        $page = intval($_POST['page']);
        $session_en = fn___en_auth(array(1308));
        $filters = array(
            'tr_en_parent_id' => $parent_en_id,
            'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'en_status' . ($en_focus_filter < 0 ? ' >=' : '') => ($en_focus_filter < 0 ? 0 /* New+ */ : intval($en_focus_filter)), //Pending or Active
            'tr_status >=' => 0, //New+
        );

        if (!$session_en) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle" style="margin:0 8px 0 2px;"></i> Session expired. Refresh the page and try again.</div>';
            return false;
        }

        //Fetch & display next batch of children, ordered by en_trust_score DESC which is aligned with other entity ordering:
        $child_entities = $this->Database_model->fn___tr_fetch($filters, array('en_child'), $en_per_page, ($page * $en_per_page), array('en_trust_score' => 'DESC'));

        foreach ($child_entities as $en) {
            echo fn___echo_en($en, 2, false);
        }

        //Count total children:
        $child_entities_count = $this->Database_model->fn___tr_fetch($filters, array('en_child'), 0, 0, array(), 'COUNT(tr_id) as totals');

        //Do we need another load more button?
        if ($child_entities_count[0]['totals'] > (($page * $en_per_page) + count($child_entities))) {
            fn___echo_en_load_more(($page + 1), $en_per_page, $child_entities_count[0]['totals']);
        }

    }


    function fn___add_or_link_entities()
    {

        //Responsible to link parent/children entities to each other via a JS function on en_miner_ui.php

        //Auth user and check required variables:
        $session_en = fn___en_auth(array(1308));

        if (!$session_en) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        } elseif (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Parent Entity',
            ));
        } elseif (!isset($_POST['is_parent'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Entity Link Direction',
            ));
        } elseif (!isset($_POST['en_existing_id']) || !isset($_POST['en_new_string']) || (intval($_POST['en_existing_id']) < 1 && strlen($_POST['en_new_string']) < 1)) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Either New Entity ID or Name is required',
            ));
        }

        //Validate parent entity:
        $current_us = $this->Database_model->fn___en_fetch(array(
            'en_id' => $_POST['en_id'],
        ));
        if (count($current_us) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid current entity ID',
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
            $ens = $this->Database_model->fn___en_fetch(array(
                'en_id' => $_POST['en_existing_id'],
                'en_status >=' => 0, //New+
            ));

            if (count($ens) < 1) {
                return fn___echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid active entity',
                ));
            }

            //All good, assign:
            $entity_new = $ens[0];
            $linking_to_existing_u = true;

        } else {

            //Is this a URL?
            if(filter_var($_POST['en_new_string'], FILTER_VALIDATE_URL)){

                //Digest URL to see what type it is and if we have any errors:
                $digested_url = $this->Matrix_model->fn___digest_url($_POST['en_new_string']);
                if(!$digested_url['status']){
                    return fn___echo_json($digested_url);
                }

                //Let's first find/add the domain:
                $digested_domain = $this->Matrix_model->fn___digest_domain($_POST['en_new_string'], $session_en['en_id']);

                //Link to this entity:
                $entity_new = $digested_domain['en_domain'];

            } else {
                //It's a regular entity name:
                $entity_new = $this->Database_model->fn___en_create(array(
                    'en_name' => trim($_POST['en_new_string']),
                    'en_status' => 2, //Published
                ), true, $session_en['en_id']);

                if (!isset($entity_new['en_id']) || $entity_new['en_id'] < 1) {
                    return fn___echo_json(array(
                        'status' => 0,
                        'message' => 'Failed to create new entity for [' . $_POST['en_new_string'] . ']',
                    ));
                }
            }

        }


        //We need to check to ensure this is not a duplicate link if linking to an existing entity:
        $ur2 = array();

        if (!$is_url_input) {

            //Add links only if not already added by the URL function:
            if ($_POST['is_parent']) {

                $tr_en_child_id = $current_us[0]['en_id'];
                $tr_en_parent_id = $entity_new['en_id'];

            } else {

                $tr_en_child_id = $entity_new['en_id'];
                $tr_en_parent_id = $current_us[0]['en_id'];

            }


            if(isset($digested_domain['en_domain'])){

                $tr_type_en_id = $digested_url['tr_type_en_id'];
                $tr_content = $digested_url['cleaned_url'];

            } else {

                $tr_type_en_id = 4230; //Empty
                $tr_content = null;

            }

            // Link to new OR existing entity:
            $ur2 = $this->Database_model->fn___tr_create(array(
                'tr_miner_en_id' => $session_en['en_id'],
                'tr_type_en_id' => $tr_type_en_id,
                'tr_content' => $tr_content,
                'tr_en_child_id' => $tr_en_child_id,
                'tr_en_parent_id' => $tr_en_parent_id,
            ));
        }

        //Fetch latest version:
        $ens_latest = $this->Database_model->fn___en_fetch(array(
            'en_id' => $entity_new['en_id'],
        ));

        //Return newly added or linked entity:
        return fn___echo_json(array(
            'status' => 1,
            'en_new_status' => $ens_latest[0]['en_status'],
            'en_new_echo' => fn___echo_en(array_merge($ens_latest[0], $ur2), 2, $_POST['is_parent']),
        ));
    }

    function fn___en_modify_save()
    {

        //Auth user and check required variables:
        $session_en = fn___en_auth(array(1308));
        $tr_content_max = $this->config->item('tr_content_max');

        //Fetch current data:
        $ens = $this->Database_model->fn___en_fetch(array(
            'en_id' => intval($_POST['en_id']),
        ));

        if (!$session_en) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1 || !(count($ens) == 1)) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid ID',
            ));
        } elseif (!isset($_POST['en_name']) || strlen($_POST['en_name']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing name',
            ));
        } elseif (!isset($_POST['en_status'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing status',
            ));
        } elseif (!isset($_POST['tr_id']) || !isset($_POST['tr_content']) || !isset($_POST['tr_status'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing entity link data',
            ));
        } elseif (strlen($_POST['en_name']) > $this->config->item('en_name_max')) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Name is longer than the allowed ' . $this->config->item('en_name_max') . ' characters. Shorten and try again.',
            ));
        }

        $tr_has_updated = false;
        $remove_from_ui = 0;
        $js_tr_type_en_id = 0; //Detect link type based on content

        //Prepare data to be updated:
        $en_update = array(
            'en_name' => trim($_POST['en_name']),
            'en_icon' => trim($_POST['en_icon']),
            'en_status' => intval($_POST['en_status']),
        );


        //Is this being removed?
        if ($en_update['en_status'] < 0 && !($en_update['en_status'] == $ens[0]['en_status'])) {

            $remove_from_ui = 1;

            //Also remove all children/parent links:
            foreach ($this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 0, //New+
                'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                '(tr_en_child_id = ' . $_POST['en_id'] . ' OR tr_en_parent_id = ' . $_POST['en_id'] . ')' => null,
            )) as $unlink_tr) {

                $this->Database_model->fn___tr_update($unlink_tr['tr_id'], array(
                    'tr_status' => -1, //Unlink
                ), $session_en['en_id']);

            }

            //Remove the link:
            $_POST['tr_id'] = 0;

        }


        if (intval($_POST['tr_id']) > 0) { //DO we have a link to update?

            //Yes, first validate entity link:
            $en_trs = $this->Database_model->fn___tr_fetch(array(
                'tr_id' => $_POST['tr_id'],
                'tr_status >=' => 0, //New+
            ));

            if (count($en_trs) < 1) {
                return fn___echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Entity Link ID',
                ));
            }

            if ($en_trs[0]['tr_content'] == $_POST['tr_content']) {

                //Transaction content has not changed:
                $js_tr_type_en_id = $en_trs[0]['tr_type_en_id'];
                $tr_content = $en_trs[0]['tr_content'];

            } else {

                //Transaction content has changed:
                $detected_tr_type = fn___detect_tr_type_en_id($_POST['tr_content']);

                if (!$detected_tr_type['status']) {

                    return fn___echo_json($detected_tr_type);

                } elseif(in_array($detected_tr_type['tr_type_en_id'], $this->config->item('en_ids_4537'))){

                    //This is a URL, validate modification:

                    if($detected_tr_type['url_is_root']){

                        if($en_trs[0]['tr_en_parent_id']==1326){

                            //Override with the clean domain for consistency:
                            $_POST['tr_content'] = $detected_tr_type['url_clean_domain'];

                        } else {

                            //Domains can only be added to the domain entity:
                            return fn___echo_json(array(
                                'status' => 0,
                                'message' => 'Domain URLs must link to <b>@1326 Domains</b> as their parent entity',
                            ));

                        }

                    } else {

                        if($en_trs[0]['tr_en_parent_id']==1326){

                            return fn___echo_json(array(
                                'status' => 0,
                                'message' => 'Only domain URLs can be linked to Domain entity.',
                            ));

                        } elseif($detected_tr_type['en_domain']){
                            //We do have the domain mapped! Is this connected to the domain entity as its parent?
                            if($detected_tr_type['en_domain']['en_id']!=$en_trs[0]['tr_en_parent_id']){
                                return fn___echo_json(array(
                                    'status' => 0,
                                    'message' => 'Must link to <b>@'.$detected_tr_type['en_domain']['en_id'].' '.$detected_tr_type['en_domain']['en_name'].'</b> as their parent entity',
                                ));
                            }
                        } else {
                            //We don't have the domain mapped, this is for sure not allowed:
                            return fn___echo_json(array(
                                'status' => 0,
                                'message' => 'Requires a new parent entity for <b>'.$detected_tr_type['url_tld'].'</b>. Add by pasting URL into the [Add @Entity] input field.',
                            ));
                        }

                    }

                }

                //Update variables:
                $tr_content = $_POST['tr_content'];
                $js_tr_type_en_id = $detected_tr_type['tr_type_en_id'];
            }


            //Has the link content changes?
            if (!($en_trs[0]['tr_content'] == $_POST['tr_content']) || !($en_trs[0]['tr_status'] == $_POST['tr_status'])) {

                if ($_POST['tr_status'] < 0) {
                    $remove_from_ui = 1;
                }

                $tr_has_updated = true;

                //Something has changed, log this:
                $this->Database_model->fn___tr_update($_POST['tr_id'], array(
                    'tr_content' => $tr_content,
                    'tr_type_en_id' => $js_tr_type_en_id,
                    'tr_status' => intval($_POST['tr_status']),
                    //Auto append timestamp and most recent miner:
                    'tr_miner_en_id' => $session_en['en_id'],
                    'tr_timestamp' => date("Y-m-d H:i:s"),
                ), $session_en['en_id']);


            }

        }

        //Now update the DB:
        $this->Database_model->fn___en_update(intval($_POST['en_id']), $en_update, true, $session_en['en_id']);


        //Reset user session data if this data belongs to the logged-in user:
        if ($_POST['en_id'] == $session_en['en_id']) {
            $ens = $this->Database_model->fn___en_fetch(array(
                'en_id' => intval($_POST['en_id']),
            ));
            if (isset($ens[0])) {
                $this->session->set_userdata(array('user' => $ens[0]));
            }
        }


        //Fetch last entity update transaction:
        $updated_trs = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0, //New+
            'tr_type_en_id IN (4251, 4263)' => null, //Entity Created/Updated
            'tr_en_child_id' => $_POST['en_id'],
        ), array('en_miner'));
        if (count($updated_trs) < 1) {
            //Should never happen
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Entity Last Updated Data',
            ));
        }

        //Start return array:
        $return_array = array(
            'status' => 1,
            'message' => '<i class="fas fa-check"></i> Saved',
            'remove_from_ui' => $remove_from_ui,
            'js_tr_type_en_id' => intval($js_tr_type_en_id),
        );

        if (intval($_POST['tr_id']) > 0) {

            //Fetch entity link:
            $trs = $this->Database_model->fn___tr_fetch(array(
                'tr_id' => $_POST['tr_id'],
            ), array('en_miner'));

            //Prep last updated:
            $return_array['tr_content'] = fn___echo_tr_urls($tr_content, $js_tr_type_en_id);
            $return_array['tr_content_final'] = $tr_content; //In case content was updated

        }

        //Show success:
        return fn___echo_json($return_array);

    }


    function fn___load_en_messages($en_id)
    {

        $session_en = fn___en_auth();
        if (!$session_en) {
            //Display error:
            die('<span style="color:#FF0000;">Error: Invalid Session. Sign In again to continue.</span>');
        }

        $messages = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0, //New+
            'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent messages
            'tr_en_parent_id' => $en_id,
        ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));


        //Always skip header:
        $_GET['skip_header'] = 1;

        //Show frame to be loaded in modal:
        $this->load->view('view_shared/matrix_header', array(
            'title' => 'Managed Intent Messages',
        ));
        echo '<div id="list-messages" class="list-group grey-list">';
        foreach ($messages as $tr) {
            echo fn___echo_en_messages($tr);
        }
        echo '</div>';
        $this->load->view('view_shared/matrix_footer');
    }


    function en_login_ui()
    {
        //Check to see if they are already logged in?
        $session_en = $this->session->userdata('user');
        if (isset($session_en['en__parents'][0]) && fn___filter_array($session_en['en__parents'], 'en_id', 1308)) {
            //Lead miner and above, go to console:
            return fn___redirect_message('/intents/' . $this->config->item('in_tactic_id'));
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
            return fn___redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Enter valid email to continue.</div>');
        } elseif (!isset($_POST['input_password'])) {
            return fn___redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Enter valid password to continue.</div>');
        }

        //Validate user email:
        $trs = $this->Database_model->fn___tr_fetch(array(
            'tr_en_parent_id' => 3288, //Primary email
            'LOWER(tr_content)' => strtolower($_POST['input_email']),
        ));

        if (count($trs) == 0) {
            //Not found!
            return fn___redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: ' . $_POST['input_email'] . ' not found.</div>');
        }

        //Fetch full entity data with their active Action Plans:
        $ens = $this->Database_model->fn___en_fetch(array(
            'en_id' => $trs[0]['tr_en_child_id'],
        ), array('en__actionplans'));

        if ($ens[0]['en_status'] < 0 || $trs[0]['tr_status'] < 0) {

            return fn___redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Your account has been de-activated. Contact us to re-active your account.</div>');

        }

        //Authenticate their password:
        $login_passwords = $this->Database_model->fn___tr_fetch(array(
            'tr_en_parent_id' => 3286, //Mench Sign In Password
            'tr_en_child_id' => $ens[0]['en_id'],
        ), array(), 1 /* get the top status */, 0, array(
            //Order by highest status:
            'tr_status' => 'DESC',
        ));
        if (count($login_passwords) == 0) {
            //They do not have a password assigned yet!
            return fn___redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: An active login password has not been assigned to your account yet. You can assign a new password using the Forgot Password Button.</div>');
        } elseif ($login_passwords[0]['tr_status'] < 2) {
            //They do not have a password assigned yet!
            return fn___redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Password is not activated with status [' . $login_passwords[0]['tr_status'] . '].</div>');
        } elseif (!(strtolower($login_passwords[0]['tr_content']) == strtolower(hash('sha256', $this->config->item('password_salt') . $_POST['input_password'])))) {
            //Bad password
            return fn___redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Incorrect password for [' . $_POST['input_email'] . ']</div>');
        }

        //Now let's do a few more checks:

        //Make sure Student is connected to Mench:
        if (!intval($ens[0]['en_psid'])) {
            return fn___redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: You are not connected to Mench on Messenger, which is required to login to the Matrix.</div>');
        }

        //Make sure Student is not unsubscribed:
        if (count($this->Database_model->fn___tr_fetch(array(
                'tr_en_child_id' => $ens[0]['en_id'],
                'tr_en_parent_id' => 4455, //Unsubscribed
                'tr_status >=' => 0,
            ))) > 0) {
            return fn___redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: You cannot login to the Matrix because you are unsubscribed from Mench. You can re-active your account by sending a message to Mench on Messenger.</div>');
        }


        $session_data = array();
        $is_chrome = (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') !== false);
        $is_miner = false;
        $is_master = false;


        //Are they miner? Give them Sign In access:
        if (fn___filter_array($ens[0]['en__parents'], 'en_id', 1308)) {
            //They have admin rights:
            $session_data['user'] = $ens[0];
            $is_miner = true;
        }


        //Applicable for miners only:
        if (!$is_chrome) {

            if ($is_master) {

                //Remove miner privileges as they cannot use the matrix with non-chrome Browser:
                $is_miner = false;
                unset($session_data['user']);

            } else {

                return fn___redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Sign In Denied. The Matrix v' . $this->config->item('app_version') . ' supports <a href="https://www.google.com/chrome/browser/" target="_blank"><u>Google Chrome</u></a> only.</div>');

            }

        } elseif (!$is_miner && !$is_master) {

            //We assume this is a master request:
            return fn___redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: You have not added any intentions to your Action Plan yet.</div>');

        }

        //Log Sign In Transaction
        $this->Database_model->fn___tr_create(array(
            'tr_miner_en_id' => $ens[0]['en_id'],
            'tr_en_parent_id' => $ens[0]['en_id'], //Initiator
            'tr_metadata' => $ens[0],
            'tr_type_en_id' => 4269, //Logged into the matrix
        ));


        //All good to go!
        //Load session and redirect:
        $this->session->set_userdata($session_data);

        //Append user IP and agent information
        if (isset($_POST['input_password'])) {
            unset($_POST['input_password']); //Sensitive information to be removed and NOT logged
        }
        $ens[0]['login_ip'] = $_SERVER['REMOTE_ADDR'];
        $ens[0]['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $ens[0]['input_post_data'] = $_POST;


        if (isset($_POST['url']) && strlen($_POST['url']) > 0) {
            header('Location: ' . $_POST['url']);
        } else {
            //Default:
            if ($is_miner) {
                //miner default:
                header('Location: /intents/' . $this->config->item('in_tactic_id'));
            } else {
                //Student default:
                header('Location: /master/actionplan');
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
        $matching_users = $this->Database_model->fn___en_fetch(array(
            'input_email' => strtolower($_POST['email']),
        ));
        if (count($matching_users) > 0) {

            $timestamp = time();

            //Dispatch the password reset Intent:
            $this->Chat_model->fn___dispatch_message(
                'Hi /firstname 👋​ You can reset your Mench password here: /link:🔑 Reset Password:https://mench.com/entities/reset_pass?en_id=' . $matching_users[0]['en_id'] . '&timestamp=' . $timestamp . '&p_hash=' . md5($matching_users[0]['en_id'] . $this->config->item('password_salt') . $timestamp) . ' (Link active for 24 hours)',
                $matching_users[0],
                true
            );

        }

        //Show message:
        echo '<div class="alert alert-success">Password reset accepted. You will receive an email only if you have a registered Mench account.</div>';
        echo '<script> $(document).ready(function() { $(".pass_success").hide(); }); </script>';

    }


    function en_password_reset()
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
            $login_passwords = $this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 2, //Must be published or verified
                'tr_en_parent_id' => 3286, //Mench Sign In Password
                'tr_en_child_id' => $_POST['en_id'], //For this user
            ));

            $new_password = hash('sha256', $this->config->item('password_salt') . $_POST['new_pass']);

            if (count($login_passwords) > 0) {

                $detected_tr_type = fn___detect_tr_type_en_id($new_password);
                if (!$detected_tr_type['status']) {
                    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: ' . $detected_tr_type['message'] . '</div>';
                }

                //Update existing password:
                $this->Database_model->fn___tr_update($login_passwords[0]['tr_id'], array(
                    'tr_content' => $new_password,
                    'tr_type_en_id' => $detected_tr_type['tr_type_en_id'],
                ), $login_passwords[0]['tr_en_child_id']);

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


    function fn___add_source_process()
    {

        //Auth user and check required variables:
        $session_en = fn___en_auth(array(1308));

        //Analyze domain:
        $domain_analysis = fn___analyze_domain($_POST['source_url']);

        if (!$session_en) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['source_url']) || !filter_var($_POST['source_url'], FILTER_VALIDATE_URL)) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid URL',
            ));
        } elseif (!isset($_POST['source_parent_ens']) || count($_POST['source_parent_ens']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Select at-least 1 parent type',
            ));
        } elseif (!isset($_POST['en_name']) || strlen($_POST['en_name']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing entity name',
            ));
        } elseif($domain_analysis['url_is_root']){
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'A source URL cannot reference the root domain',
            ));
        }


        //Populate all parent entities for this source:
        $parent_ens = array();


        //Start with selected source-types:
        foreach ($_POST['source_parent_ens'] as $parent_en_id) {
            if (intval($parent_en_id) > 0) {
                array_push($parent_ens, intval($parent_en_id));
            }
        }


        //Now parse referenced authors:
        for ($x = 1; $x <= 3; $x++) {

            //Do we have an author?
            if (strlen($_POST['author_' . $x]) < 1) {
                continue;
            }

            //Is this referencing an existing entity or is it a new entity?
            $tr_en_link_id = 0; //Assume it's a new entity...

            if (substr($_POST['author_' . $x], 0, 1) == '@') {
                $parts = explode(' ', $_POST['author_' . $x]);
                $tr_en_link_id = intval(str_replace('@', '', $parts[0]));
            }

            if ($tr_en_link_id > 0) {

                //Validate existing entity reference:
                $referenced_ens = $this->Database_model->fn___en_fetch(array(
                    'en_status >=' => 0, //New+
                    'en_id' => $tr_en_link_id,
                ));
                if (count($referenced_ens) < 1) {
                    return fn___echo_json(array(
                        'status' => 0,
                        'message' => 'Author #' . $x . ' entity ID @' . $tr_en_link_id . ' is invalid',
                    ));
                }

                //Add author to parent source array:
                array_push($parent_ens, $tr_en_link_id);

            } else {

                //Seems to be a new author entity...

                //First analyze URL:
                $digest_author_url = $this->Matrix_model->fn___digest_url($_POST['ref_url_' . $x]);

                //Validate author inputs before creating anything:
                if (!$digest_author_url['status']) {

                    //Oooopsi, show errors:
                    return fn___echo_json(array(
                        'status' => 0,
                        'message' => 'Author #' . $x . ' URL error: ' . $digest_author_url['message'],
                    ));

                } elseif(strlen($_POST['why_expert_' . $x]) > 0) {

                    //Also validate Expert explanation:
                    $author_type_requirement = 4255; //Must be text only...
                    $en_all_4592 = $this->config->item('en_all_4592');
                    $detected_tr_type = fn___detect_tr_type_en_id($_POST['why_expert_' . $x]);

                    if (!$detected_tr_type['status']) {

                        return fn___echo_json(array(
                            'status' => 0,
                            'message' => 'Author #' . $x . ' error: ' . $detected_tr_type['message'],
                        ));

                    } elseif ($detected_tr_type['tr_type_en_id'] != $author_type_requirement) {

                        return fn___echo_json(array(
                            'status' => 0,
                            'message' => 'Author #' . $x . ' expert notes must be ['.$en_all_4592[$author_type_requirement]['m_name'].'] only',
                        ));

                    }

                }

                //All good...

                //Add author:
                $author_en = $this->Database_model->fn___en_create(array(
                    'en_name' => trim($_POST['author_' . $x]),
                    'en_status' => 0, //New
                ), true, $session_en['en_id']);

                //Add author to People or Organizations entity:
                $this->Database_model->fn___tr_create(array(
                    'tr_status' => 2, //Published
                    'tr_miner_en_id' => $session_en['en_id'],
                    'tr_type_en_id' => 4230, //Empty
                    'tr_en_parent_id' => $_POST['entity_parent_id_' . $x], //People or Organizations
                    'tr_en_child_id' => $author_en['en_id'],
                ), true);

                //Add author URL:
                $this->Matrix_model->fn___digest_url($_POST['ref_url_' . $x], $session_en['en_id'], 0, $author_en['en_id'], $_POST['author_' . $x]);

                //Should we also link author to to Industry Experts entity?
                if(strlen($_POST['why_expert_' . $x]) > 0){
                    //Add author to industry experts:
                    $this->Database_model->fn___tr_create(array(
                        'tr_status' => 2, //Published
                        'tr_miner_en_id' => $session_en['en_id'],
                        'tr_content' => trim($_POST['why_expert_' . $x]),
                        'tr_type_en_id' => $author_type_requirement,
                        'tr_en_parent_id' => 3084, //Industry Experts
                        'tr_en_child_id' => $author_en['en_id'],
                    ), true);
                }

                //Add author to parent source array:
                array_push($parent_ens, $author_en['en_id']);

            }
        }


        //Save URL & domain:
        $digested_url = $this->Matrix_model->fn___digest_url($_POST['source_url'], $session_en['en_id'], 0, 0, $_POST['en_name']);
        if(!$digested_url['status']){
            return fn___echo_json($digested_url);
        }


        //Link content to all parent entities:
        foreach ($parent_ens as $parent_en_id) {
            $this->Database_model->fn___tr_create(array(
                'tr_status' => 2, //Published
                'tr_miner_en_id' => $session_en['en_id'],
                'tr_type_en_id' => 4230, //Empty
                'tr_en_parent_id' => $parent_en_id,
                'tr_en_child_id' => $digested_url['en_url']['en_id'],
            ), true);
        }


        //Success:
        return fn___echo_json(array(
            'status' => 1,
            'new_source_id' => $digested_url['en_url']['en_id'], //Redirects to this entity...
        ));

    }


}
