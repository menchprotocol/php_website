<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

    }




    function i_editor_load()
    {

        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['i__id']) || !isset($_POST['x__id']) || !isset($_POST['current_i__type'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }


        $i__id = 0; //New idea
        $i__type = intval($_POST['current_i__type']);
        $created_i__id = 0;

        if($_POST['i__id'] > 0){

            $is = $this->I_model->fetch(array(
                'i__id' => $_POST['i__id'],
            ));
            if (!count($is)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Idea is no longer active',
                ));
            } elseif (!write_privacy_i($is[0]['i__hashtag'])) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'You are missing permission to edit this idea',
                ));
            }

            $i__id = intval($is[0]['i__id']);
            if(!$i__type){
                $i__type = intval($is[0]['i__type']);
            }

        } else {

            //Create a new idea:
            $i_new = $this->I_model->create(array(
                'i__message' => null,
                'i__type' => $_POST['current_i__type'],
                'i__privacy' => 42636, //Pre-drafting idea
            ), $member_e['e__id']);

            $i__id = $i_new['i__id'];
            $created_i__id = $i__id;

        }


        //Fetch dynamic data based on idea type:
        $return_inputs = array();
        $e___4737 = $this->config->item('e___4737'); // Idea Status
        $e___42179 = $this->config->item('e___42179'); //Dynamic Input Fields
        $e___11035 = $this->config->item('e___11035'); //Summary

        foreach(array_intersect($this->config->item('n___'.$i__type), $this->config->item('n___42179')) as $dynamic_e__id){

            //Let's first determine the data type:
            $data_types = array_intersect($e___42179[$dynamic_e__id]['m__following'], $this->config->item('n___4592'));

            if(count($data_types)!=1) {
                //This is strange, we are expecting 1 match only report this:
                $this->X_model->create(array(
                    'x__type' => 4246, //Platform Bug Reports
                    'x__creator' => $member_e['e__id'],
                    'x__following' => 42179, //Dynamic Input Fields
                    'x__follower' => $dynamic_e__id,
                    'x__next' => $i__id,
                    'x__reference' => $_POST['x__id'],
                    'x__message' => 'Found ' . count($data_types) . ' Data Types (Expecting exactly 1) for @' . $dynamic_e__id . ': Check @4592 to see what is wrong',
                ));
                continue; //Go to the next dynamic data type
            }

            //We found 1 match as expected:
            foreach($data_types as $data_type_this){
                $data_type = $data_type_this;
                break;
            }

            if(in_array($data_type, $this->config->item('n___42188'))){

                //Single or Multiple Choice:
                array_push($return_inputs, array(
                    'd__id' => $dynamic_e__id,
                    'd__is_radio' => 1,
                    'd_x__id' => 0,
                    'd__html' => view_instant_select($dynamic_e__id, 0, $i__id),
                    'd__value' => ( $i__id>0 ? $i__id : '' ),
                    'd__type_name' => '',
                    'd__placeholder' => '',
                    'd__profile_header' => '',
                ));

            } else {

                $this_data_type = $this->config->item('e___'.$data_type);
                $e___4592 = $this->config->item('e___4592'); //Data types
                $e___6177 = $this->config->item('e___6177'); //Source Privacy
                $e___42179 = $this->config->item('e___42179'); //Dynamic Input Field
                $e___11035 = $this->config->item('e___11035'); //Summary

                //Fetch the current value:
                $counted = 0;
                $unique_values = array();
                if($i__id > 0){ //Must have an original ID to possibly have a value...
                    foreach($this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
                        'x__next' => $i__id,
                        'x__following' => $dynamic_e__id,
                    ), array('x__following')) as $selected_e){
                        if(strlen($selected_e['x__message']) && !in_array($selected_e['x__message'], $unique_values)){
                            $counted++;
                            array_push($unique_values, $selected_e['x__message']);
                            array_push($return_inputs, array(
                                'd__id' => $dynamic_e__id,
                                'd__is_radio' => 0,
                                'd_x__id' => $selected_e['x__id'],
                                'd__html' => dynamic_headline($dynamic_e__id, $e___42179[$dynamic_e__id], $selected_e),
                                'd__value' => $selected_e['x__message'],
                                'd__type_name' => html_input_type($data_type),
                                'd__placeholder' => ( strlen($this_data_type[$dynamic_e__id]['m__message']) ? $this_data_type[$dynamic_e__id]['m__message'] : $e___4592[$data_type]['m__title'].'...' ),
                                'd__profile_header' => '',
                            ));
                        }
                    }
                }


                if(!$counted){
                    foreach($this->E_model->fetch(array(
                        'e__id' => $dynamic_e__id,
                    )) as $selected_e){
                        array_push($return_inputs, array(
                            'd__id' => $dynamic_e__id,
                            'd__is_radio' => 0,
                            'd_x__id' => 0,
                            'd__html' => dynamic_headline($dynamic_e__id, $e___42179[$dynamic_e__id], $selected_e),
                            'd__value' => '',
                            'd__type_name' => html_input_type($data_type),
                            'd__placeholder' => ( strlen($this_data_type[$dynamic_e__id]['m__message']) ? $this_data_type[$dynamic_e__id]['m__message'] : $e___4592[$data_type]['m__title'].'...' ),
                            'd__profile_header' => '',
                        ));
                    }
                }
            }
        }

        $return_array = array(
            'status' => 1,
            'return_inputs' => $return_inputs,
            'created_i__id' => $created_i__id,
        );

        //Log Modal View:
        $this->X_model->create(array(
            'x__creator' => $member_e['e__id'],
            'x__type' => 14576, //MODAL VIEWED
            'x__following' => 31911, //Edit Idea
            'x__next' => $i__id,
            'x__reference' => $_POST['x__id'],
            'x__metadata' => $return_array,
        ));

        //Return everything we found:
        return view_json($return_array);

    }

    function i_editor_save(){


        $member_e = superpower_unlocked();
        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif(!isset($_POST['save_i__message'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea',
            ));

        } elseif(!isset($_POST['focus_card']) || !isset($_POST['focus_id'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing focus Card/ID',
            ));

        } elseif(!isset($_POST['save_i__hashtag'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing hashtag',
            ));

        } elseif(!isset($_POST['save_i__id']) || !intval($_POST['save_i__id'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));

        } elseif(!isset($_POST['next_i__id']) || !isset($_POST['previous_i__id']) || !isset($_POST['save_x__type'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Next/Previous ID',
            ));

        } elseif(!isset($_POST['save_x__id']) || !isset($_POST['save_x__message'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Transaction Data',
            ));

        } elseif (!isset($_POST['save_i__type']) || !in_array($_POST['save_i__type'], $this->config->item('n___4737'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid idea Type',
            ));
        } elseif (!isset($_POST['save_i__privacy']) || !in_array($_POST['save_i__privacy'], $this->config->item('n___31004'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid idea Privacy',
            ));
        } elseif(strlen($_POST['save_i__message'])>view_memory(6404,4736)){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea message must be less than '.view_memory(6404,4736).' characters.',
            ));
        }



        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['save_i__id'],
        ));
        if(!count($is)){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea Not Valid',
            ));
        }


        $focus_card = ( $_POST['focus_card']==12273 && $_POST['focus_id']==$_POST['save_i__id'] );
        $has_media = (isset($_POST['save_media']) && is_array($_POST['save_media']) && count($_POST['save_media'])>0);


        //Might be new if pre-drafting:
        if( $is[0]['i__privacy']==42636 ){

            //See if references only:
            if(strlen($_POST['save_i__message']) && !$has_media && !substr_count($_POST['save_i__message'], "\n") && intval($_POST['save_x__type']) && (intval($_POST['next_i__id']) || intval($_POST['previous_i__id']))){

                $all_hashtags = true;
                $i_references = array();
                foreach(explode(' ', trim($_POST['save_i__message'])) as $word){
                    $found_hashtag = false;
                    if(substr($word, 0, 1)=='#'){
                        $valid_hashtag = false;
                        foreach($this->I_model->fetch(array(
                            'LOWER(i__hashtag)' => strtolower(substr($word, 1)),
                            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                        )) as $i_found){
                            $found_hashtag = true;
                            $valid_hashtag = true;
                            array_push($i_references, $i_found);
                        }
                        if(!$valid_hashtag && superpower_unlocked(10939)){
                            return view_json(array(
                                'status' => 0,
                                'message' => 'ERROR: '.$word.' is not a valid/active Idea',
                            ));
                        }
                    }
                    if(!$found_hashtag){
                        $all_hashtags = false;
                        break; //It must be a hashtag only reference
                    }
                }

                if($all_hashtags && count($i_references) && $_POST['save_x__type']>0){
                    //Return success:
                    foreach($this->I_model->fetch(array(
                        'i__id' => ( intval($_POST['next_i__id'])>0 ? intval($_POST['next_i__id']) : intval($_POST['previous_i__id']) ),
                    )) as $focus_i){

                        //Append all of these hashtags:
                        foreach($i_references as $reference_i){
                            if(intval($_POST['next_i__id'])>0){
                                $status = $this->I_model->i_link($focus_i, $_POST['save_x__type'], $reference_i, $member_e['e__id']);
                            } elseif(intval($_POST['previous_i__id'])>0){
                                $status = $this->I_model->i_link($reference_i, $_POST['save_x__type'], $focus_i, $member_e['e__id']);
                            }
                            if(!$status['status']){
                                return view_json($status);
                            }
                        }

                        //What to focus on depends on how many total ideas added:
                        $return_i = ( count($i_references)>=2 ? $focus_i : $reference_i );

                        return view_json(array(
                            'status' => 1,
                            'return_i__cache' => '',
                            'return_i__cache_links' => '',
                            'return_i__cache_full' => view_card_i($_POST['focus_x__group'], $return_i),
                            'redirect_idea' => '/~'.$return_i['i__hashtag'],
                            'message' => count($i_references).' ideas linked',
                        ));
                    }
                }
            }



            //Update new idea fields:
            $this->I_model->update($is[0]['i__id'], array(
                'i__type' => $_POST['save_i__type'],
                'i__privacy' => $_POST['save_i__privacy'],
            ), true, $member_e['e__id']);
            $is[0]['i__type'] = trim($_POST['save_i__type']);
            $is[0]['i__privacy'] = trim($_POST['save_i__privacy']);
        }



        //Update Media...
        $media_stats = array(
            'total_current' => 0,
            'total_submitted' => 0,
            'adjust_created' => 0,
            'adjust_duplicated' => 0,
            'adjust_updated' => 0,
            'adjust_removed' => 0,
        );
        $full_media = array();
        $current_media_e__ids = array();
        $sort_count = 0;
        //Fetch current media:
        foreach($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___42294')) . ')' => null, //Media
            'x__next' => $is[0]['i__id'],
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
        ), array('x__following'), 0, 0, array('x__weight' => 'ASC')) as $media){
            $media_stats['total_current']++;
            $current_media_e__ids[$sort_count] = intval($media['x__following']);
            $full_media[$media['x__following']] = $media;
            $sort_count++;
        }

        //Fetch submitted media:
        $submitted_media_e__ids = array();
        $sort_count = 0; //Reset sorting to compare to submitted media...
        if($has_media){

            //We have media to process:
            foreach($_POST['save_media'] as $submitted_media){

                if($submitted_media['e__id']>0){

                    $adjust_updated = false;

                    //Update media order?
                    if($current_media_e__ids[$sort_count]!=$submitted_media['e__id']){
                        //Order has changed, update it:
                        $adjust_updated = true;
                        $this->X_model->update($full_media[$submitted_media['e__id']]['x__id'], array(
                            'x__weight' => $sort_count,
                        ), $member_e['e__id'], 13006 /* SOURCE SORT MANUAL */);
                    }

                    //Update the source title?
                    $validate_e__title = validate_e__title($submitted_media['e__title']);
                    if($validate_e__title['status'] && $full_media[$submitted_media['e__id']]['e__title']!=$submitted_media['e__title']){
                        $adjust_updated = true;
                        $this->E_model->update($submitted_media['e__id'], array(
                            'e__title' => trim($submitted_media['e__title']),
                        ), true, $member_e['e__id']);
                    }

                    if($adjust_updated){
                        $media_stats['adjust_updated']++;
                    }

                } else {

                    //Adding new media...
                    //Search eTag to see if we already have it:
                    $etag_detected = false;
                    if(isset($submitted_media['media_cache']['etag']) && strlen($submitted_media['media_cache']['etag'])){
                        //We we already have this asset, link to that source without giving this new source the authority over it...
                        //First person to upload a source will get authority over its created source...
                        foreach($this->X_model->fetch(array(
                            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'x__following' => 42662, //etag
                            'x__message' => $submitted_media['media_cache']['etag'],
                        ), array('x__follower'), 1) as $existing_media){
                            $media_stats['adjust_duplicated']++;
                            $submitted_media['e__id'] = $existing_media['e__id'];
                            $etag_detected = true;
                        }
                    }

                    if(!$submitted_media['e__id']){

                        //Create Source for this new media:
                        $added_e = $this->E_model->verify_create($submitted_media['e__title'], $member_e['e__id'], ( $submitted_media['media_e__id']==4259 /* Audio has no thumbnail! */ ? 'fas fa-volume-up' : $submitted_media['e__cover'] ), true);
                        if(!$added_e['status']){
                            $this->X_model->create(array(
                                'x__type' => 4246, //Platform Bug Reports
                                'x__message' => 'Failed to create a new source for ['.$submitted_media['e__title'].'] with cover ['.$submitted_media['e__cover'].']',
                                'x__metadata' => array(
                                    'submitted_media' => $submitted_media,
                                    'post' => $_POST,
                                ),
                            ));
                            continue;
                        }

                        //Create new media and assign ID:
                        $media_stats['adjust_created']++;
                        $submitted_media['e__id'] = $added_e['new_e']['e__id'];

                        //new asset, create new source and insert tags...
                        $e___32088 = $this->config->item('e___32088'); //Platform Variables
                        foreach($this->config->item('e___42679') as $x__type => $m) {

                            //Ensure variable name exists so we can check the API call:
                            $target_variable = false;
                            if(isset($e___32088[$x__type]['m__message'])){
                                //Determine if variable exists...
                                if(in_array($x__type, $this->config->item('n___42763')) && isset($submitted_media['media_cache']['video'][$e___32088[$x__type]['m__message']])){
                                    //Video info:
                                    $target_variable = $submitted_media['media_cache']['video'][$e___32088[$x__type]['m__message']];
                                } elseif(in_array($x__type, $this->config->item('n___42675')) && isset($submitted_media['media_cache']['audio'][$e___32088[$x__type]['m__message']])){
                                    //Audio info:
                                    $target_variable = $submitted_media['media_cache']['audio'][$e___32088[$x__type]['m__message']];
                                } elseif(isset($submitted_media['media_cache'][$e___32088[$x__type]['m__message']])) {
                                    //Media info:
                                    $target_variable = $submitted_media['media_cache'][$e___32088[$x__type]['m__message']];
                                }
                            }
                            if(!strlen($target_variable) || $target_variable=='0'){
                                //This variable does not have a value, move on...
                                continue;
                            }

                            //We have a variable, see what it is...
                            if(in_array($x__type, $this->config->item('n___33331'))){

                                //Single select that needs auto creation of sources if missing:
                                $child_id = 0;
                                foreach($this->X_model->fetch(array(
                                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                    'x__following' => $x__type,
                                    'e__title' => $target_variable,
                                ), array('x__follower'), 1, 0, array('x__id' => 'ASC')) as $child_source){
                                    $child_id = $child_source['e__id'];
                                }

                                //If not found create the child:
                                if(!$child_id){
                                    $added_child = $this->E_model->verify_create($target_variable, $member_e['e__id']);
                                    if(!$added_child['status']){
                                        $this->X_model->create(array(
                                            'x__type' => 4246, //Platform Bug Reports
                                            'x__message' => 'Failed to create a new source for ['.$target_variable.']',
                                            'x__metadata' => array(
                                                'submitted_media' => $submitted_media,
                                                'post' => $_POST,
                                            ),
                                        ));
                                        continue;
                                    }

                                    //Add links for this new source:
                                    $this->X_model->create(array(
                                        'x__creator' => $member_e['e__id'],
                                        'x__following' => $x__type,
                                        'x__follower' => $added_child['new_e']['e__id'],
                                        'x__type' => 4230,
                                    ));

                                    //Assign child source:
                                    $child_id = $added_child['new_e']['e__id'];

                                }

                                if($child_id){
                                    //Child source found, simply link:
                                    $this->X_model->create(array(
                                        'x__creator' => $member_e['e__id'],
                                        'x__following' => $child_id,
                                        'x__follower' => $submitted_media['e__id'],
                                        'x__type' => 4230,
                                    ));
                                }

                            } else {

                                //Save variable as is:
                                $this->X_model->create(array(
                                    'x__creator' => $member_e['e__id'],
                                    'x__following' => $x__type,
                                    'x__follower' => $submitted_media['e__id'],
                                    'x__message' => $target_variable,
                                    'x__type' => 4230,
                                ));

                            }
                        }
                    }


                    //By now have the media source, create necessary links:
                    if($submitted_media['e__id'] && $submitted_media['media_e__id']){

                        //Link to Idea:
                        if(!count($this->X_model->fetch(array(
                            'x__next' => $is[0]['i__id'],
                            'x__following' => $submitted_media['e__id'],
                            'x__type' => $submitted_media['media_e__id'],
                            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        )))){
                            $this->X_model->create(array(
                                'x__creator' => $member_e['e__id'],
                                'x__next' => $is[0]['i__id'],
                                'x__following' => $submitted_media['e__id'],
                                'x__type' => $submitted_media['media_e__id'],
                                'x__message' => $submitted_media['playback_code'],
                                'x__weight' => $sort_count,
                            ));
                        }


                        //Link to Source as Uploader:
                        $link_type = ( $etag_detected ? 4230 : 42659 );
                        if(!count($this->X_model->fetch(array(
                            'x__following' => $member_e['e__id'],
                            'x__follower' => $submitted_media['e__id'],
                            'x__type' => $link_type,
                            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        )))){
                            $this->X_model->create(array(
                                'x__creator' => $member_e['e__id'],
                                'x__following' => $member_e['e__id'],
                                'x__follower' => $submitted_media['e__id'],
                                'x__type' => $link_type,
                                'x__message' => $submitted_media['playback_code'],
                            ));
                        }


                        //Link to Media Type:
                        if(!count($this->X_model->fetch(array(
                            'x__following' => $submitted_media['media_e__id'],
                            'x__follower' => $submitted_media['e__id'],
                            'x__type' => 4230,
                            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        )))){
                            $this->X_model->create(array(
                                'x__creator' => $member_e['e__id'],
                                'x__following' => $submitted_media['media_e__id'],
                                'x__follower' => $submitted_media['e__id'],
                                'x__type' => 4230,
                                'x__metadata' => $submitted_media,
                            ));
                        }

                    }
                }

                //Add this to the submitted ones:
                $submitted_media_e__ids[$sort_count] = $submitted_media['e__id'];
                $media_stats['total_submitted']++;
                $sort_count++;

            }
        }

        //Remove current media missing from submitted (Removed during editing):
        foreach(array_diff($current_media_e__ids, $submitted_media_e__ids) as $deleted_media_e__id){
            $media_stats['adjust_removed']++;
            $this->X_model->update($full_media[$deleted_media_e__id]['x__id'], array(
                'x__privacy' => 6173, //Transaction Removed
            ), $member_e['e__id'], 42694); //Media Removed
        }




        //Validate Idea Message:
        $final_media_count = $media_stats['total_current'] + $media_stats['adjust_duplicated'] + $media_stats['adjust_created'] - $media_stats['adjust_removed'];
        if(!$final_media_count && !strlen(trim($_POST['save_i__message']))){
            //Since we do not have media, we must have a message:
            return view_json(array(
                'status' => 0,
                'message' => 'Write or Upload something to save.',
            ));
        }



        //Process dynamic inputs if any:
        $e___42179 = $this->config->item('e___42179'); //Dynamic Input Fields
        if($_POST['save_i__id'] > 0){
            for ($p = 1; $p <= view_memory(6404,42206); $p++) {

                if(!isset($_POST['save_dynamic_' . $p])){
                    break; //Nothing more to process
                }

                $input_parts = explode('____', $_POST['save_dynamic_' . $p], 3);
                $d_x__id = $input_parts[0];
                $dynamic_e__id = $input_parts[1];
                $dynamic_value = trim($input_parts[2]);

                //Required fields must have an input:
                if(in_array($dynamic_e__id, $this->config->item('n___42174')) && !strlen($dynamic_value) && !in_array($dynamic_e__id, $this->config->item('n___33331')) && !in_array($dynamic_e__id, $this->config->item('n___33332'))){
                    return view_json(array(
                        'status' => 0,
                        'message' => 'Missing Required Field: '.$e___42179[$dynamic_e__id]['m__title'],
                    ));
                }

                //Validate input based on its data type, if provided:
                if (strlen($dynamic_value)) {
                    foreach(array_intersect($e___42179[$dynamic_e__id]['m__following'], $this->config->item('n___4592')) as $data_type_this){
                        $data_type_validate = data_type_validate($data_type_this, $dynamic_value, $e___42179[$dynamic_e__id]['m__title']);
                        if (!$data_type_validate['status']) {
                            //We had an error:
                            return view_json($data_type_validate);
                        }
                    }
                }


                //Fetch the current value:
                if($d_x__id > 0){
                    $values = $this->X_model->fetch(array(
                        'x__id' => $d_x__id,
                    ));
                }

                if(!$d_x__id || !count($values)){
                    $values = $this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
                        'x__next' => $is[0]['i__id'],
                        'x__following' => $dynamic_e__id,
                    ));
                }


                //Update if needed:
                if(!strlen($dynamic_value)){

                    //Remove Link if we have one:
                    if(count($values) && $dynamic_e__id!=11035 /* HACK: Summary are key links that should not be removed */){
                        $this->X_model->update($values[0]['x__id'], array(
                            'x__privacy' => 6173, //Transaction Removed
                        ), $member_e['e__id'], 42175 /* Dynamic Link Content Removed */);
                    }

                } elseif(!count($values)){

                    //Create New Link:
                    $this->X_model->create(array(
                        'x__creator' => $member_e['e__id'],
                        'x__type' => 4983, //Co-Author
                        'x__following' => $dynamic_e__id,
                        'x__next' => $is[0]['i__id'],
                        'x__message' => $dynamic_value,
                        'x__weight' => number_x__weight($dynamic_value),
                    ));

                } elseif($values[0]['x__message']!=$dynamic_value){

                    //Update Link:
                    $this->X_model->update($values[0]['x__id'], array(
                        'x__message' => $dynamic_value,
                        'x__weight' => number_x__weight($dynamic_value),
                    ), $member_e['e__id'], 42176 /* Dynamic Link Content Updated */);

                }
            }
        }



        if(strlen($_POST['save_i__hashtag']) && $is[0]['i__hashtag']!==trim($_POST['save_i__hashtag'])){

            $validate_handle = validate_handle($_POST['save_i__hashtag'], $is[0]['i__id'], null);
            if(!$validate_handle['status']){
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_handle['message'],
                ));
            }

            //Save hashtag since changed:
            $this->I_model->update($is[0]['i__id'], array(
                'i__hashtag' => trim($_POST['save_i__hashtag']),
            ), true, $member_e['e__id']);

            //Now Handles everywhere they are referenced:
            foreach ($this->X_model->fetch(array(
                'x__previous' => $is[0]['i__id'],
                'x__type IN (' . join(',', $this->config->item('n___42341')) . ')' => null, //Idea References
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            ), array('x__next')) as $ref) {
                view_sync_links(str_replace('#'.$is[0]['i__hashtag'], '#'.trim($_POST['save_i__hashtag']), $ref['i__message']), true, $ref['i__id']);
            }

            //Assign new value:
            $is[0]['i__hashtag'] = trim($_POST['save_i__hashtag']);

        }


        //Also have to add as a comment to another idea?
        if(intval($_POST['next_i__id'])>0 && $_POST['save_x__type']>0){
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__previous' => $_POST['next_i__id'],
                'x__next' => $is[0]['i__id'],
                'x__type' => $_POST['save_x__type'],
            ));
        } elseif(intval($_POST['previous_i__id'])>0 && $_POST['save_x__type']>0){
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__previous' => $is[0]['i__id'],
                'x__next' => $_POST['previous_i__id'],
                'x__type' => $_POST['save_x__type'],
            ));
        }


        //Do we have a link reference message that need to be saved?
        if($_POST['save_x__id']>0 && $_POST['save_x__message']!='IGNORE_INPUT'){
            //Fetch transaction:
            foreach($this->X_model->fetch(array(
                'x__id' => $_POST['save_x__id'],
            )) as $this_x){

                $is[0] = array_merge($is[0], $this_x);

                if($this_x['x__message'] != trim($_POST['save_x__message'])){
                    $this->X_model->update($this_x['x__id'], array(
                        'x__message' => trim($_POST['save_x__message']),
                        'x__weight' => number_x__weight(trim($_POST['save_x__message'])),
                    ), $member_e['e__id'], 42171);
                }
            }
        }


        //Update Links based on save_i__message / Sync Idea Synonym & Source References links:
        $view_sync_links = view_sync_links($_POST['save_i__message'], true, $is[0]['i__id']);
        $is[0]['i__message'] = trim($_POST['save_i__message']);
        $is[0]['i__cache'] = $view_sync_links['i__cache'];


        //Update Search Index:
        flag_for_search_indexing(12273, $is[0]['i__id']);


        return view_json(array(
            'status' => 1,
            'return_i__cache' => $view_sync_links['i__cache'],
            'return_i__cache_links' => view_i__links($is[0], $focus_card, $focus_card),
            'return_i__cache_full' => view_card_i($_POST['focus_x__group'], $is[0]),
            'redirect_idea' => ( isset($is[0]['i__hashtag']) ? '/~'.$is[0]['i__hashtag'] : null ),
            'message' => $media_stats['total_current'].' current & '.$media_stats['total_submitted'].' submitted media: '.$media_stats['total_submitted'].' Created, '.$media_stats['adjust_updated'].' Updated & '.$media_stats['adjust_removed'].' Removed while detected '.$media_stats['adjust_duplicated'].' duplicate uploads. '.$view_sync_links['sync_stats']['old_links_removed'].' old links removed, '.$view_sync_links['sync_stats']['old_links_kept'].' old links kept, '.$view_sync_links['sync_stats']['new_links_added'].' new links added.',
        ));

    }

    function i_copy(){

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'messagCloe' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Following Source',
            ));
        } elseif (!isset($_POST['do_recursive'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing template parameter',
            ));
        }

        return view_json($this->I_model->recursive_clone(intval($_POST['i__id']), intval($_POST['do_recursive']), $member_e['e__id']));

    }

    function i_load_cover(){

        if (!isset($_POST['i__id']) || !isset($_POST['x__type']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {

            if(in_array($_POST['x__type'], $this->config->item('n___42376')) && !write_privacy_i(null, $_POST['i__id'])){

                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-lock"></i></span>Private</div>';

            } else {

                $ui = '';
                $listed_items = 0;
                if(in_array($_POST['x__type'], $this->config->item('n___42261')) || in_array($_POST['x__type'], $this->config->item('n___42284'))){

                    //SOURCES
                    $e___6177 = $this->config->item('e___6177'); //Source Types
                    $e___4593 = $this->config->item('e___4593'); //Transaction Types
                    $current_e__handle = view_valid_handle_e($_POST['first_segment']);
                    foreach(view_i_covers($_POST['x__type'], $_POST['i__id'], 1, false) as $e_e) {
                        if(isset($e_e['e__id'])){
                            $ui .= view_card('/@'.$e_e['e__handle'], $current_e__handle && $e_e['e__handle']==$current_e__handle, $e_e['x__type'], $e_e['e__privacy'], view_cover($e_e['e__cover'], true), $e_e['e__title'], $e_e['x__message']);
                            $listed_items++;
                        }
                    }

                } elseif(in_array($_POST['x__type'], $this->config->item('n___11020'))){

                    //IDEAS
                    $e___4737 = $this->config->item('e___4737'); //Idea Types
                    $e___4593 = $this->config->item('e___4593'); //Transaction Types
                    $current_i__hashtag = ( substr($_POST['first_segment'], 0, 1)=='~' ? substr($_POST['first_segment'], 1) : false );

                    foreach(view_i_covers($_POST['x__type'], $_POST['i__id'], 1, false) as $next_i) {
                        if(isset($next_i['i__id'])){
                            $ui .= view_card('/~'.$next_i['i__hashtag'], $next_i['i__hashtag']==$current_i__hashtag, $next_i['x__type'], null, ( in_array($next_i['i__type'], $this->config->item('n___32172')) ? $e___4737[$next_i['i__type']]['m__cover'] : '' ), view_i_title($next_i), $next_i['x__message']);
                            $listed_items++;
                        }
                    }

                }

                if($listed_items < $_POST['counter']){
                    //We have more to show:
                    foreach($this->I_model->fetch(array(
                        'i__id' => $_POST['i__id'],
                    )) as $i){
                        $ui .= view_more('/~'.$i['i__hashtag'], false, '&nbsp;', '&nbsp;', '&nbsp;', 'View all '.number_format($_POST['counter'], 0));
                    }
                }

                echo $ui;

            }
        }
    }

    function i_sort_load()
    {

        /*
         *
         * Saves the order of read ideas based on
         * member preferences.
         *
         * */

        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['new_x_order']) || !is_array($_POST['new_x_order']) || count($_POST['new_x_order']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing sorting ideas',
            ));
        } elseif (!isset($_POST['x__type']) || !in_array($_POST['x__type'], $this->config->item('n___4603'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Transaction Type',
            ));
        }

        //Update the order of their discoveries:
        $updated = 0;
        foreach($_POST['new_x_order'] as $x__weight => $x__id){
            if(intval($x__id) > 0 && intval($x__weight) > 0){
                //Update order of this transaction:
                if($this->X_model->update(intval($x__id), array(
                    'x__weight' => $x__weight,
                ), $member_e['e__id'], 4603)){
                    $updated++;
                }
            }
        }

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => $updated.' Sorted',
        ));
    }

    function i_view_body(){
        //Authenticate Member:
        if (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1 || !isset($_POST['counter']) || !isset($_POST['x__type']) || intval($_POST['x__type']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {
            echo i_view_body($_POST['x__type'], $_POST['counter'], $_POST['i__id']);
        }
    }



    function e_view_body(){
        //Authenticate Member:
        if (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1 || !isset($_POST['x__type']) || intval($_POST['x__type']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {
            echo e_view_body($_POST['x__type'], $_POST['counter'], $_POST['e__id']);
        }
    }

    function e_load_cover(){

        if (!isset($_POST['e__id']) || !isset($_POST['x__type']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {

            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';

        } else {

            if(in_array($_POST['x__type'], $this->config->item('n___42376')) && !write_privacy_e(null, $_POST['e__id'])){

                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-lock"></i></span>Private</div>';

            } else {

                $ui = '';
                $listed_items = 0;

                if(in_array($_POST['x__type'], $this->config->item('n___11028'))){

                    //SOURCES
                    $current_e__handle = view_valid_handle_e($_POST['first_segment']);
                    $e___6177 = $this->config->item('e___6177'); //Source Privacy
                    $e___4593 = $this->config->item('e___4593'); //Transaction Types

                    foreach(view_e_covers($_POST['x__type'], $_POST['e__id'], 1, false) as $e_e) {
                        if(isset($e_e['e__id'])){
                            $ui .= view_card('/@'.$e_e['e__handle'], $e_e['e__handle']==$current_e__handle, $e_e['x__type'], $e_e['e__privacy'], view_cover($e_e['e__cover'], true), $e_e['e__title'], $e_e['x__message']);
                            $listed_items++;
                        }
                    }

                } elseif(in_array($_POST['x__type'], $this->config->item('n___42261')) || in_array($_POST['x__type'], $this->config->item('n___42284'))){

                    //IDEAS
                    $current_i__hashtag = ( substr($_POST['first_segment'], 0, 1)=='~' ? substr($_POST['first_segment'], 1) : false );
                    $e___4737 = $this->config->item('e___4737'); //Idea Types
                    $e___4593 = $this->config->item('e___4593'); //Transaction Types

                    foreach(view_e_covers($_POST['x__type'], $_POST['e__id'], 1, false) as $next_i) {
                        if(isset($next_i['i__id'])){
                            $ui .= view_card('/~'.$next_i['i__hashtag'], $next_i['i__hashtag']==$current_i__hashtag, $next_i['x__type'], null, ( in_array($next_i['i__type'], $this->config->item('n___32172')) ? $e___4737[$next_i['i__type']]['m__cover'] : '' ), view_i_title($next_i), $next_i['x__message']);
                            $listed_items++;
                        }
                    }

                }

                if($listed_items < $_POST['counter']){
                    //We have more to show:
                    foreach($this->E_model->fetch(array(
                        'e__id' => $_POST['e__id'],
                    )) as $e_this){
                        $ui .= view_more('/@'.$e_this['e__handle'], false, '&nbsp;', '&nbsp;', '&nbsp;', 'View all '.number_format($_POST['counter'], 0));
                    }
                }

                echo $ui;

            }
        }
    }

    function e_sort_save()
    {

        //Authenticate Member:
        $member_e = superpower_unlocked(10939);
        if (!$member_e) {
            view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid e__id',
            ));
        } elseif (!isset($_POST['new_x__weight']) || !is_array($_POST['new_x__weight']) || count($_POST['new_x__weight']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Source:
            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['e__id'],
                'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));

            //Count followers:
            $list_e_count = $this->X_model->fetch(array(
                'x__following' => $_POST['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ), array('x__follower'), 0, 0, array(), 'COUNT(e__id) as totals');

            if (count($es) < 1) {

                view_json(array(
                    'status' => 0,
                    'message' => 'Invalid e__id',
                ));

            } elseif($list_e_count[0]['totals'] > view_memory(6404,11064)){

                view_json(array(
                    'status' => 0,
                    'message' => 'Cannot sort sources if greater than '.view_memory(6404,11064),
                ));

            } else {

                //Update them all:
                foreach($_POST['new_x__weight'] as $rank => $x__id) {
                    $this->X_model->update($x__id, array(
                        'x__weight' => intval($rank),
                    ), $member_e['e__id'], 13006 /* SOURCE SORT MANUAL */);
                }

                //Display message:
                view_json(array(
                    'status' => 1,
                ));

            }
        }
    }

    function e_delete(){

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Transaction ID',
            ));
        }

        //Archive Transaction:
        $this->X_model->update($_POST['x__id'], array(
            'x__privacy' => 6173,
        ), $member_e['e__id'], 10673 /* IDEA NOTES Unpublished */);

        return view_json(array(
            'status' => 1,
        ));

    }

    function e_copy(){

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['e__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source',
            ));
        } elseif (!strlen($_POST['copy_source_title'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Title',
            ));
        }


        //Validate Source:
        $fetch_o = $this->E_model->fetch(array(
            'e__id' => $_POST['e__id'],
            'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if (count($fetch_o) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid followings source ID',
            ));
        }



        //Create:
        $added_e = $this->E_model->verify_create($_POST['copy_source_title'], $member_e['e__id'], $fetch_o[0]['e__cover']);
        if(!$added_e['status']){
            //We had an error, return it:
            return view_json($added_e);
        } else {
            //Assign new source:
            $focus_e = $added_e['new_e'];
        }


        //Followers:
        foreach($this->X_model->fetch(array(
            'x__following' => $_POST['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___41303')) . ')' => null, //Clone Source Links
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0) as $x) {
            //Make sure none existent in new source:
            if(!count($this->X_model->fetch(array(
                'x__type' => $x['x__type'],
                'x__following' => $focus_e['e__id'],
                'x__follower' => $x['x__follower'],
                'x__message' => $x['x__message'],
                'x__reference' => $x['x__reference'],
                'x__privacy' => $x['x__privacy'],
                'x__metadata' => $x['x__metadata'],
            )))){
                $this->X_model->create(array(
                    'x__creator' => $member_e['e__id'],
                    'x__weight' => $x['x__weight'],

                    'x__type' => $x['x__type'],
                    'x__following' => $focus_e['e__id'],
                    'x__follower' => $x['x__follower'],
                    'x__message' => $x['x__message'],
                    'x__reference' => $x['x__reference'],
                    'x__privacy' => $x['x__privacy'],
                    'x__metadata' => $x['x__metadata'],
                ));
            }
        }


        //Followings:
        foreach($this->X_model->fetch(array(
            'x__follower' => $_POST['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___41303')) . ')' => null, //Clone Source Links
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0) as $x) {
            if(!count($this->X_model->fetch(array(
                'x__type' => $x['x__type'],
                'x__following' => $x['x__following'],
                'x__follower' => $focus_e['e__id'],
                'x__message' => $x['x__message'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__privacy' => $x['x__privacy'],
            )))){
                $this->X_model->create(array(
                    'x__creator' => $member_e['e__id'],
                    'x__weight' => $x['x__weight'],

                    'x__type' => $x['x__type'],
                    'x__following' => $x['x__following'],
                    'x__follower' => $focus_e['e__id'],
                    'x__message' => $x['x__message'],
                    'x__reference' => $x['x__reference'],
                    'x__metadata' => $x['x__metadata'],
                    'x__privacy' => $x['x__privacy'],
                ));
            }
        }

        //Ideas:
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___41302')) . ')' => null, //Clone Idea Source Links
            'x__following' => $_POST['e__id'],
        ), array(), 0) as $x){
            if(!count($this->X_model->fetch(array(
                'x__type' => $x['x__type'],
                'x__following' => $focus_e['e__id'],
                'x__follower' => $x['x__follower'],
                'x__previous' => $x['x__previous'],
                'x__next' => $x['x__next'],
                'x__message' => $x['x__message'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__privacy' => $x['x__privacy'],
            )))){
                $this->X_model->create(array(
                    'x__creator' => $member_e['e__id'],
                    'x__weight' => $x['x__weight'],

                    'x__type' => $x['x__type'],
                    'x__following' => $focus_e['e__id'],
                    'x__follower' => $x['x__follower'],
                    'x__previous' => $x['x__previous'],
                    'x__next' => $x['x__next'],
                    'x__message' => $x['x__message'],
                    'x__reference' => $x['x__reference'],
                    'x__metadata' => $x['x__metadata'],
                    'x__privacy' => $x['x__privacy'],
                ));
            }
        }

        return view_json(array(
            'status' => 1,
            'new_e__handle' => $focus_e['e__handle'],
        ));


    }

    function e__add()
    {

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['focus_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Following Source',
            ));
        } elseif (!isset($_POST['x__type'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Creation Type',
            ));
        } elseif (!isset($_POST['e_existing_id']) || !isset($_POST['e_new_string']) || (intval($_POST['e_existing_id']) < 1 && strlen($_POST['e_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
            ));
        }

        $adding_to_i = ($_POST['focus_card']==12273);

        if($adding_to_i){

            //Validate Idea:
            $fetch_o = $this->I_model->fetch(array(
                'i__id' => $_POST['focus_id'],
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid followings source ID',
                ));
            }

        } else {

            //Validate Source:
            $fetch_o = $this->E_model->fetch(array(
                'e__id' => $_POST['focus_id'],
                'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid followings source ID',
                ));
            }

        }



        //Set some variables:
        $_POST['x__type'] = intval($_POST['x__type']);
        $is_upwards = in_array($_POST['x__type'], $this->config->item('n___14686'));

        if(!intval($_POST['e_existing_id']) && view_valid_handle_e($_POST['e_new_string'])){
            foreach($this->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower(substr($_POST['e_new_string'], 1)),
            )) as $e){
                $_POST['e_existing_id'] = $e['e__id'];
            }
        }
        $adding_to_existing = ( intval($_POST['e_existing_id']) > 0 );

        //Are we adding an existing source?
        if ($adding_to_existing) {

            //Validate this existing source:
            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['e_existing_id'],
                'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));

            if (count($es) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Source @'.$_POST['e_existing_id'].' is not active',
                ));
            }

            //All good, assign:
            $focus_e = $es[0];

        } else {

            //We are creating a new source:
            $added_e = $this->E_model->verify_create($_POST['e_new_string'], $member_e['e__id']);
            if(!$added_e['status']){
                //We had an error, return it:
                return view_json($added_e);
            } else {
                //Assign new source:
                $focus_e = $added_e['new_e'];
            }

        }


        //We need to check to ensure this is not a duplicate transaction if adding an existing source:
        $ur2 = array();
        $e_already_linked = 0;

        if($adding_to_i) {

            $e_already_linked = count($this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__following' => $focus_e['e__id'],
                'x__next' => $fetch_o[0]['i__id'],
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            )));

            //Add Reference:
            $ur2 = $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => 4983, //Co-Author
                'x__following' => $focus_e['e__id'],
                'x__next' => $fetch_o[0]['i__id'],
            ));

        } else {

            //Add Up/Down Source:

            //Add transactions only if not previously added by the URL function:
            if ($is_upwards) {

                //Following
                $x__follower = $fetch_o[0]['e__id'];
                $x__following = $focus_e['e__id'];
                $x__weight = 0; //Never sort following, only sort followers

            } else {

                //Followers
                $x__following = $fetch_o[0]['e__id'];
                $x__follower = $focus_e['e__id'];
                $x__weight = 0;

            }


            $x__message = null;

            $e_already_linked = count($this->X_model->fetch(array(
                'x__follower' => $x__follower,
                'x__following' => $x__following,
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            )));

            //Create transaction:
            if(!count($this->X_model->fetch(array(
                'x__type' => 4251,
                'x__follower' => $x__follower,
                'x__following' => $x__following,
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
                $ur2 = $this->X_model->create(array(
                    'x__creator' => $member_e['e__id'],
                    'x__type' => 4251,
                    'x__message' => $x__message,
                    'x__follower' => $x__follower,
                    'x__following' => $x__following,
                    'x__weight' => $x__weight,
                ));
            }
        }


        //Return source:
        return view_json(array(
            'status' => 1,
            'e_new_echo' => view_card_e($_POST['x__type'], array_merge($focus_e, $ur2), null),
            'e_already_linked' => $e_already_linked,
        ));

    }

    function e_editor_load()
    {

        $member_e = superpower_unlocked();
        $e___11035 = $this->config->item('e___11035');
        $e___42776 = $this->config->item('e___42776');
        $e___4592 = $this->config->item('e___4592'); //Data types
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['e__id']) || !isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }

        $es = $this->E_model->fetch(array(
            'e__id' => $_POST['e__id'],
            'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if (!count($es)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Source is no longer active',
            ));
        } elseif (!write_privacy_e($es[0]['e__handle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to edit this Source',
            ));
        }


        //Fetch dynamic data based on idea type:
        $order_42145 = sort_by(42145);
        $scanned_sources = array();
        $return_inputs = array();
        $input_pointer = 0;
        $profile_header = '';

        //Fetch Source Templates, if any:
        foreach($this->X_model->fetch(array(
            'x__following IN (' . join(',', $this->config->item('n___42178')) . ')' => null, //Dynamic Sources
            'x__follower' => $es[0]['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array('x__following'), 0, 0, sort_by(42178)) as $e_group) {

            if(in_array($e_group['e__id'], $scanned_sources)){
                continue;
            }
            array_push($scanned_sources, $e_group['e__id']);

            foreach($this->X_model->fetch(array(
                'x__follower' => $e_group['e__id'],
                'x__following IN (' . join(',', $this->config->item('n___42145')) . ')' => null, //Dynamic Input Templates
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            ), array('x__following'), 0, 0, $order_42145) as $e_template) {

                $profile_header = '<div class="profile_header main__title"><span class="icon-block-xs">'.view_cover($e_template['e__cover']).'</span>'.$e_template['e__title'].'<a href="/@'.$e_group['e__handle'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="Because you follow '.$e_group['e__title'].'... Click to Open in a New Window"><span class="icon-block-xs">'.view_cover($e_group['e__cover']).'</span></a></div>';


                //Load template:
                if(!is_array($this->config->item('e___'.$e_template['e__id']))){
                    //Report Error:
                    $this->X_model->create(array(
                        'x__type' => 4246, //Platform Bug Reports
                        'x__message' => 'e_editor_load() ERROR: @'.$e_template['e__id'].' is NOT in memory cache',
                    ));
                    continue;
                } elseif(in_array($e_template['e__id'], $scanned_sources)){
                    continue;
                }
                array_push($scanned_sources, $e_template['e__id']);


                foreach($this->config->item('e___'.$e_template['e__id']) as $dynamic_e__id => $m) {

                    //Make sure it's a dynamic input field:
                    if(!in_array($dynamic_e__id, $this->config->item('n___42179'))){
                        continue;
                    } elseif(in_array($dynamic_e__id, $scanned_sources)){
                        continue;
                    }
                    array_push($scanned_sources, $dynamic_e__id);

                    //Let's first determine the data type:
                    $data_types = array_intersect($m['m__following'], $this->config->item('n___4592'));

                    if(count($data_types)!=1){

                        //This is strange, we are expecting 1 match only report this:
                        $this->X_model->create(array(
                            'x__type' => 4246, //Platform Bug Reports
                            'x__creator' => $member_e['e__id'],
                            'x__following' => 31912, //Edit Source
                            'x__follower' => $dynamic_e__id,
                            'x__reference' => $_POST['x__id'],
                            'x__message' => 'Found '.count($data_types).' Data Types (@'.$es[0]['e__id'].') (Expecting exactly 1) for @'.$dynamic_e__id.': Check @4592 to see what is wrong',
                        ));
                        continue; //Go to the next dynamic data type

                    } elseif ($input_pointer >= view_memory(6404, 42206)) {
                        //Monitor if we ever reach the maximum:
                        $this->X_model->create(array(
                            'x__type' => 4246, //Platform Bug Reports
                            'x__creator' => $member_e['e__id'],
                            'x__following' => 42179, //Dynamic Input Fields
                            'x__follower' => $dynamic_e__id,
                            'x__next' => $_POST['e__id'],
                            'x__reference' => $_POST['x__id'],
                            'x__metadata' => $_POST,
                            'x__message' => 'Dynamic Fields Reach their maximum limit of ' . view_memory(6404, 42206) . '  which may require field expansion',
                        ));
                    }

                    //We found 1 match as expected:
                    $input_pointer++;
                    foreach($data_types as $data_type_this){
                        $data_type = $data_type_this;
                        break;
                    }

                    if(in_array($data_type, $this->config->item('n___42188'))){

                        //Single or Multiple Choice:
                        array_push($return_inputs, array(
                            'd__id' => $dynamic_e__id,
                            'd__is_radio' => 1,
                            'd_x__id' => 0,
                            'd__html' => view_instant_select($dynamic_e__id, $es[0]['e__id'], 0),
                            'd__value' => ( $es[0]['e__id']>0 ? $es[0]['e__id'] : '' ),
                            'd__type_name' => '',
                            'd__placeholder' => '',
                            'd__profile_header' => $profile_header,
                        ));

                    } else {

                        $this_data_type = $this->config->item('e___'.$data_type);
                        $e___6177 = $this->config->item('e___6177'); //Source Privacy
                        $e___42179 = $this->config->item('e___42179'); //Dynamic Input Field
                        $e___11035 = $this->config->item('e___11035'); //Summary

                        //Fetch the current value(s):
                        $counted = 0;
                        $unique_values = array();
                        foreach($this->X_model->fetch(array(
                            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'x__follower' => $es[0]['e__id'],
                            'x__following' => $dynamic_e__id,
                        ), array('x__following')) as $selected_e){
                            if(strlen($selected_e['x__message']) && !in_array($selected_e['x__message'], $unique_values)){
                                array_push($unique_values, $selected_e['x__message']);
                                $counted++;
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_e__id,
                                    'd__is_radio' => 0,
                                    'd_x__id' => $selected_e['x__id'],
                                    'd__html' => dynamic_headline($dynamic_e__id, $m, $selected_e),
                                    'd__value' => $selected_e['x__message'],
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => ( strlen($this_data_type[$dynamic_e__id]['m__message']) ? $this_data_type[$dynamic_e__id]['m__message'] : $e___4592[$data_type]['m__title'].'...' ),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }

                        if(!$counted){
                            foreach($this->E_model->fetch(array(
                                'e__id' => $dynamic_e__id,
                            )) as $selected_e){
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_e__id,
                                    'd__is_radio' => 0,
                                    'd_x__id' => 0,
                                    'd__html' => dynamic_headline($dynamic_e__id, $m, $selected_e),
                                    'd__value' => '',
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => ( strlen($this_data_type[$dynamic_e__id]['m__message']) ? $this_data_type[$dynamic_e__id]['m__message'] : $e___4592[$data_type]['m__title'].'...' ),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }
                    }
                }
            }
        }


        if(!count($return_inputs)){
            //Add universal inputs only if missing any other input:
            foreach($this->E_model->fetch(array(
                'e__id IN (' . join(',', $this->config->item('n___42776')) . ')' => null, //Universal Dynamic Inputs
            )) as $selected_e){
                foreach(array_intersect($e___42776[$selected_e['e__id']]['m__following'], $this->config->item('n___4592')) as $data_type){
                    //Any value?
                    $values = $this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__follower' => $es[0]['e__id'],
                        'x__following' => $selected_e['e__id'],
                    ));
                    array_push($return_inputs, array(
                        'd__id' => $selected_e['e__id'],
                        'd__is_radio' => 0,
                        'd_x__id' => 0,
                        'd__html' => dynamic_headline($selected_e['e__id'], $e___42776[$selected_e['e__id']], $selected_e),
                        'd__value' => ( isset($values[0]['x__message']) && strlen($values[0]['x__message'])>0 ? $values[0]['x__message'] : '' ),
                        'd__type_name' => html_input_type($data_type),
                        'd__placeholder' => ( strlen($e___42776[$selected_e['e__id']]['m__message']) ? $e___42776[$selected_e['e__id']]['m__message'] : $e___4592[$data_type]['m__title'].'...' ),
                        'd__profile_header' => '', //No header for universals
                    ));
                    break;
                }
            }
        }


        //Find Past Selected Covers for Source:
        $cover_history_content = array();
        $unique_covers = array();
        foreach($this->X_model->fetch(array(
            'x__follower' => $_POST['e__id'],
            'x__type' => 10653, //Source Cover Update
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0, 0, array('x__id' => 'DESC')) as $x) {
            $x__metadata = unserialize($x['x__metadata']);
            if(strlen($x__metadata['before'])){
                $cover = ( substr_count($x__metadata['before'], 'class="') ? one_two_explode('class="','"',$x__metadata['before']) : $x__metadata['before'] );
                if((filter_var($cover, FILTER_VALIDATE_URL) || string_is_icon($cover) || string_is_emoji($cover)) && !in_array($cover, $unique_covers)){
                    array_push($unique_covers, $cover);
                    array_push($cover_history_content, array(
                        'cover_preview' => $cover,
                        'cover_apply' => $cover,
                        'new_title' => $x['x__time'],
                    ));
                }
            }
        }

        $return_array = array(
            'status' => 1,
            'return_inputs' => $return_inputs,
            'cover_history_content' => $cover_history_content, //Past covers for quick editing
        );

        //Log Modal View:
        $this->X_model->create(array(
            'x__creator' => $member_e['e__id'],
            'x__type' => 14576, //MODAL VIEWED
            'x__following' => 31912, //Edit Source
            'x__follower' => $es[0]['e__id'],
            'x__reference' => $_POST['x__id'],
            'x__metadata' => $return_array,
        ));

        //Return everything we found:
        return view_json($return_array);

    }

    function e_editor_save()
    {

        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['save_e__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin ID',
            ));
        } elseif (!isset($_POST['save_e__title'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Title',
            ));
        } elseif (!isset($_POST['save_e__handle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Handle',
            ));
        } elseif (!isset($_POST['save_e__privacy']) || !in_array($_POST['save_e__privacy'], $this->config->item('n___6177'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Privacy',
            ));
        } elseif (!isset($_POST['save_e__cover'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Cover',
            ));
        } elseif(!isset($_POST['save_x__id']) || !isset($_POST['save_x__message'])){
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Transaction Data',
            ));
        }



        $es = $this->E_model->fetch(array(
            'e__id' => $_POST['save_e__id'],
            'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if(!count($es)){
            return view_json(array(
                'status' => 0,
                'message' => 'Source Not Active',
            ));
        }


        //Validate Dynamic Inputs:
        $e___42179 = $this->config->item('e___42179'); //Dynamic Input Fields

        //Process dynamic inputs if any:
        for ($p = 1; $p <= view_memory(6404,42206); $p++) {

            if(!isset($_POST['save_dynamic_' . $p])){
                break; //Nothing more to process
            }

            $input_parts = explode('____', $_POST['save_dynamic_' . $p], 3);
            $d_x__id = $input_parts[0];
            $dynamic_e__id = $input_parts[1];
            $dynamic_value = trim($input_parts[2]);


            //Required fields must have an input:
            if(in_array($dynamic_e__id, $this->config->item('n___42174')) && !strlen($dynamic_value) && !in_array($dynamic_e__id, $this->config->item('n___33331')) && !in_array($dynamic_e__id, $this->config->item('n___33332'))){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Missing Required Field: '.$e___42179[$dynamic_e__id]['m__title'],
                ));
            }

            //Validate input based on its data type, if provided:
            if (strlen($dynamic_value)) {
                foreach(array_intersect($e___42179[$dynamic_e__id]['m__following'], $this->config->item('n___4592')) as $data_type_this){
                    $data_type_validate = data_type_validate($data_type_this, $dynamic_value, $e___42179[$dynamic_e__id]['m__title']);
                    if (!$data_type_validate['status']) {
                        //We had an error:
                        return view_json($data_type_validate);
                    }
                }
            }


            //Fetch the current value:
            if($d_x__id > 0){
                $values = $this->X_model->fetch(array(
                    'x__id' => $d_x__id,
                ));
            }

            if(!$d_x__id || !count($values)){
                $values = $this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__following' => $dynamic_e__id,
                    'x__follower' => $es[0]['e__id'],
                ));
            }


            //Update if needed:
            if (!strlen($dynamic_value)) {

                //Remove Link if we have one:
                if(count($values) && $dynamic_e__id!=11035 /* HACK: Summary are key links that should not be removed */){
                    $this->X_model->update($values[0]['x__id'], array(
                        'x__privacy' => 6173, //Transaction Removed
                    ), $member_e['e__id'], 42175 /* Dynamic Link Content Removed */);
                }

            } elseif (!count($values)) {

                //Create Link:
                $this->X_model->create(array(
                    'x__creator' => $member_e['e__id'],
                    'x__type' => 4230,
                    'x__following' => $dynamic_e__id,
                    'x__follower' => $es[0]['e__id'],
                    'x__message' => $dynamic_value,
                    'x__weight' => number_x__weight($dynamic_value),
                ));

            } elseif ($values[0]['x__message'] != $dynamic_value) {

                //Update Link:
                $this->X_model->update($values[0]['x__id'], array(
                    'x__message' => $dynamic_value,
                    'x__weight' => number_x__weight($dynamic_value),
                ), $member_e['e__id'], 42176 /* Dynamic Link Content Updated */);

            }
        }




        //Validate Source Handle & save if needed:
        if($es[0]['e__handle'] !== trim($_POST['save_e__handle'])){
            $validate_handle = validate_handle(trim($_POST['save_e__handle']), null, $es[0]['e__id']);
            if(!$validate_handle['status']){
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_handle['message'],
                ));
            }
        }

        //Validate Source Title & save if needed:
        $validate_e__title = validate_e__title($_POST['save_e__title']);
        if($es[0]['e__title'] != trim($_POST['save_e__title'])){
            if(!$validate_e__title['status']){
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_e__title['message'],
                ));
            }
            $es[0]['e__title'] = $validate_e__title['e__title_clean'];
        }

        //Save Source Cover if needed:
        if($es[0]['e__cover'] != trim($_POST['save_e__cover'])){
            //TODO validate e__cover?
            $es[0]['e__cover'] = trim($_POST['save_e__cover']);
        }

        //Update:
        $this->E_model->update($es[0]['e__id'], array(
            'e__title' => $validate_e__title['e__title_clean'],
            'e__cover' => trim($_POST['save_e__cover']),
            'e__handle' => trim($_POST['save_e__handle']),
            'e__privacy' => $_POST['save_e__privacy'],
        ), true, $member_e['e__id']);


        if($es[0]['e__handle'] !== trim($_POST['save_e__handle'])){

            //Update Handles everywhere they are referenced:
            foreach ($this->X_model->fetch(array(
                'x__following' => $es[0]['e__id'],
                'x__type' => 31835, //Source Mention
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            ), array('x__next')) as $ref) {
                view_sync_links(str_replace('@'.$es[0]['e__handle'], '@'.trim($_POST['save_e__handle']), $ref['i__message']), true, $ref['i__id']);
            }
            $es[0]['e__handle'] = trim($_POST['save_e__handle']);

        }

        //Do we have a link reference message that need to be saved?
        if($_POST['save_x__id']>0 && $_POST['save_x__message']!='IGNORE_INPUT'){

            //Fetch transaction:
            foreach($this->X_model->fetch(array(
                'x__id' => $_POST['save_x__id'],
            )) as $this_x){

                $es[0] = array_merge($es[0], $this_x);

                if($this_x['x__message'] != trim($_POST['save_x__message'])){
                    $this->X_model->update($this_x['x__id'], array(
                        'x__message' => trim($_POST['save_x__message']),
                        'x__weight' => number_x__weight(trim($_POST['save_x__message'])),
                    ), $member_e['e__id'], 42171);
                }
            }
        }


        //Reset member session data if this data belongs to the logged-in member:
        if ($_POST['save_e__id']==$member_e['e__id']) {
            $this->E_model->activate_session($es[0], true);
        }


        return view_json(array(
            'status' => 1,
            'message' => 'Updated ',
        ));


    }

    function e_select_apply()
    {
        /*
         *
         * Saves the radio selection of some account fields
         *
         * */

        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['focus_id']) || intval($_POST['focus_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing followings source',
            ));
        } elseif (!isset($_POST['selected_e__id']) || intval($_POST['selected_e__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing selected source',
            ));
        } elseif (!isset($_POST['down_e__id']) || !isset($_POST['right_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Down/Right Element',
            ));
        } elseif (!isset($_POST['enable_mulitiselect']) || !isset($_POST['was_previously_selected'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing multi-select setting',
            ));
        }

        $stats = array(
            'total' => 0,
            'was_previously_selected' => intval($_POST['was_previously_selected']),
            'deleted' => 0,
            'added' => 0,
        );


        if($_POST['down_e__id'] > 0){

            //Dispatch Any Emails Necessary:
            if(isset($_POST['selected_e__id']) && intval($_POST['selected_e__id'])>0){
                foreach($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type' => 33600, //Draft
                    'x__following' => $_POST['selected_e__id'],
                ), array('x__next'), 0) as $i){
                    if(count($this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type' => 33600, //Draft
                        'x__following' => 31065, //Choice Update Email Templates
                        'x__next' => $i['i__id'], //Is this the template?
                    )))){
                        //Found the email template to send:
                        $total_sent = $this->X_model->send_i_dm(array($member_e), $i, website_setting(0), false);
                        break; //Just the first template match
                    }
                }
            }


            if($_POST['focus_id']==28904){

                //Add special transaction to monitor unsubscribes:
                if(in_array($_POST['selected_e__id'], $this->config->item('n___29648'))){
                    $this->X_model->create(array(
                        'x__creator' => $member_e['e__id'],
                        'x__type' => 29648, //Communication Downgraded
                        'x__following' => $_POST['focus_id'],
                        'x__follower' => $_POST['selected_e__id'],
                    ));
                }
            }
        }

        $is_required = in_array($_POST['focus_id'], $this->config->item('n___42174')); //Required Settings

        if(!$_POST['enable_mulitiselect'] || $_POST['was_previously_selected']){

            //Since this is not a multi-select we want to delete all existing options

            //Fetch all possible answers based on followings source:
            $query_filters = array(
                'x__following' => $_POST['focus_id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            );

            if((!$is_required || $_POST['enable_mulitiselect']) && $_POST['was_previously_selected']){
                //Just delete this single item, not the other ones:
                $query_filters['x__follower'] = $_POST['selected_e__id'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach($this->X_model->fetch($query_filters, array('x__follower'), 0, 0) as $answer_e){
                $stats['total']++;
                array_push($possible_answers, $answer_e['e__id']);
            }

            //Delete previously selected options:
            if($_POST['down_e__id']){
                $delete_query = $this->X_model->fetch(array(
                    'x__following IN (' . join(',', $possible_answers) . ')' => null,
                    'x__follower' => $_POST['down_e__id'],
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ));
            } elseif($_POST['right_i__id']){
                $delete_query = $this->X_model->fetch(array(
                    'x__following IN (' . join(',', $possible_answers) . ')' => null,
                    'x__next' => $_POST['right_i__id'],
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ));
            }

            foreach($delete_query as $delete){
                $stats['deleted']++;
                //Should usually delete a single option:
                $this->X_model->update($delete['x__id'], array(
                    'x__privacy' => 6173, //Transaction Removed
                ), $member_e['e__id'], 6224 /* Member Account Updated */);
            }

        }

        //Add new option if not previously there:
        if((!$_POST['enable_mulitiselect'] && $is_required) || !$_POST['was_previously_selected']){
            if($_POST['down_e__id']){
                $stats['added']++;
                $this->X_model->create(array(
                    'x__creator' => $member_e['e__id'],
                    'x__following' => $_POST['selected_e__id'],
                    'x__type' => 4230,
                    'x__follower' => $_POST['down_e__id'],
                ));
            } elseif($_POST['right_i__id']){

                if(!count($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
                    'x__following' => $_POST['selected_e__id'],
                    'x__next' => $_POST['right_i__id'],
                    'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                )))){
                    $stats['added']++;
                    $this->X_model->create(array(
                        'x__creator' => $member_e['e__id'],
                        'x__type' => 4983, //Co-Author
                        'x__following' => $_POST['selected_e__id'],
                        'x__next' => $_POST['right_i__id'],
                    ));
                }

            }
        }


        //Update Session:
        if($_POST['down_e__id'] && count($member_e)){
            $this->E_model->activate_session($member_e, true);
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Updated: '.print_r($stats, true),
        ));
    }

    function e_contact_auth(){


        if (!isset($_POST['account_id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing user ID',
            ));
        } elseif (!isset($_POST['input_code']) || !intval($_POST['input_code'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid code',
            ));
        } elseif (!isset($_POST['account_email_phone'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing account_email_phone',
            ));
        } elseif (!isset($_POST['referrer_url'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing referrer URL',
            ));
        } elseif (!isset($_POST['sign_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea referrer',
            ));
        } elseif (!isset($_POST['new_username'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing account name',
            ));
        }

        $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));

        //Validate member ID
        if($_POST['account_id'] > 0){

            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['account_id'],
            ));
            if(!count($es)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid account ID.',
                ));
            }

        } else {

            if(strlen($_POST['new_username'])<2){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Missing account name.',
                ));
            }


            $_POST['new_account_email'] = trim(strtolower($_POST['new_account_email']));
            if(!filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL) && !filter_var($_POST['new_account_email'], FILTER_VALIDATE_EMAIL)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Enter your email to continue',
                ));
            }

        }



        //Auth Code:
        $is_authenticated = false;
        foreach($this->X_model->fetch(array(
            'x__type' => 32078, //Sign In Key
            'x__privacy' => 6175, //Still Pending
            'x__message' => $_POST['account_email_phone'],
        ), array(), 1, 0, array('x__id' => 'DESC')) as $sent_key){

            $x__metadata = unserialize($sent_key['x__metadata']);
            $session_key = $this->session->userdata('session_key');

            if(strlen($session_key) && $x__metadata['hash_code']==md5($session_key.$_POST['input_code'])){

                //Complete access code:
                $is_authenticated = $this->X_model->update($sent_key['x__id'], array(
                    'x__privacy' => 6176, //Published
                ), $_POST['account_id'], 32569); //Code Verified

            }

        }
        if(!$is_authenticated){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid code, try again.',
            ));
        }





        //Validate member ID
        if($_POST['account_id'] > 0){

            //Assign session & log transaction:
            $this->E_model->activate_session($es[0]);

        } else {

            //Add new account
            $_POST['account_email_phone'] =  trim(strtolower($_POST['account_email_phone']));
            $is_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);

            //Prep inputs & validate further:
            $member_result = $this->E_model->add_member($_POST['new_username'], ( $is_email ? $_POST['account_email_phone'] : $_POST['new_account_email'] ), ( !$is_email ? $_POST['account_email_phone'] : '' ));
            if (!$member_result['status']) {
                return view_json($member_result);
            }

            $es[0] = $member_result['e'];

        }


        //Set default sign in URL:
        $sign_url = '/@'.$es[0]['e__handle'];

        //See if we can find a better one:
        if (intval($_POST['sign_i__id']) > 0) {
            foreach($this->I_model->fetch(array(
                'i__id' => $_POST['sign_i__id'],
            )) as $i){
                $sign_url = '/ajax/x_start/'.$i['i__hashtag'];
            }
        } elseif (isset($_POST['referrer_url']) && strlen(urldecode($_POST['referrer_url'])) > 1) {
            $sign_url = urldecode($_POST['referrer_url']);
        }

        return view_json(array(
            'status' => 1,
            'sign_url' => $sign_url,
        ));

    }

    function e_toggle_e(){

        $member_e = superpower_unlocked(28714);
        if(!$member_e){

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(28714),
            ));

        } elseif(!isset($_POST['x__creator']) || !isset($_POST['e__id']) || !isset($_POST['i__id']) || !isset($_POST['x__id'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variable',
            ));

        } else {

            $already_added = $this->X_model->fetch(array(
                'x__following' => $_POST['e__id'],
                'x__follower' => $_POST['x__creator'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array('x__following'));

            if(count($already_added)){

                if(intval($_POST['input_modal']) && trim($_POST['modal_value'])!=$already_added[0]['x__message']){

                    //Updating current value:
                    $this->X_model->update($already_added[0]['x__id'], array(
                        'x__message' => trim($_POST['modal_value']),
                    ));
                    return view_json(array(
                        'status' => 1,
                        'message' => ( intval($_POST['input_modal']) && strlen($_POST['modal_value']) ? trim($_POST['modal_value']) : $already_added[0]['e__cover'] ),
                    ));

                } else {

                    //Already exists, let's remove:
                    $this->X_model->update($already_added[0]['x__id'], array(
                        'x__privacy' => 6173, //Transaction Deleted
                    ), $member_e['e__id'], 10673 /* Member Transaction Unpublished */);

                    return view_json(array(
                        'status' => 1,
                        'message' => ' ',
                    ));

                }

            } else {

                foreach($this->E_model->fetch(array(
                    'e__id' => $_POST['e__id'],
                )) as $e){

                    //Does not exist, Add:
                    $this->X_model->create(array(
                        'x__following' => $_POST['e__id'],
                        'x__follower' => $_POST['x__creator'],
                        'x__creator' => $member_e['e__id'],
                        'x__message' => ( intval($_POST['input_modal']) && strlen($_POST['modal_value']) ? trim($_POST['modal_value']) : null ),
                        'x__type' => 4230,
                    ));

                    return view_json(array(
                        'status' => 1,
                        'message' => ( intval($_POST['input_modal']) && strlen($_POST['modal_value']) ? trim($_POST['modal_value']) : $e['e__cover'] ),
                    ));

                }



            }

        }

    }

    function e_verify_contact(){

        if(!isset($_POST['account_email_phone'])){
            return view_json(array(
                'status' => 0,
                'message' => 'missing account details',
            ));
        }

        //Cleanup input email:
        $e___11035 = $this->config->item('e___11035'); //Summary
        $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));
        $valid_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);
        if(!$valid_email && strlen($_POST['account_email_phone'])>=10){
            $_POST['account_email_phone'] = preg_replace('/[^0-9]+/', '', $_POST['account_email_phone']);
        }
        $possible_phone = !$valid_email && strlen($_POST['account_email_phone'])>=10;

        if (!$valid_email && !$possible_phone) {
            return view_json(array(
                'status' => 0,
                'message' => '['.$_POST['account_email_phone'].'] is an Invalid Email Address or Phone Number',
            ));
        } elseif (!isset($_POST['sign_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing data ID',
            ));
        }


        if(intval($_POST['sign_i__id']) > 0){
            //Fetch the idea:
            $referrer_i = $this->I_model->fetch(array(
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'i__id' => $_POST['sign_i__id'],
            ));
        } else {
            $referrer_i = array();
        }


        //Search for email/phone to see if it exists
        $x__creator = 0;
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__message' => $_POST['account_email_phone'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__following' => ( filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL) ? 3288 : 4783 ), //Email / Phone
            'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__follower')) as $map_e){
            $u = $map_e;
            $x__creator = $map_e['e__id'];
            break;
        }

        //Send Sign In Key
        $passcode = rand(1000,9999);
        $session_key = random_string(55);

        //Append to session:
        $session_data = $this->session->all_userdata();
        $session_data['session_key'] = $session_key;
        $this->session->set_userdata($session_data);

        $html_message = $passcode.' is your '.$e___11035[32078]['m__title'].' for your '.get_domain('m__title').' account.';

        if($valid_email) {

            //Email:
            send_email(array($_POST['account_email_phone']), $html_message, '<div class="line">'.$html_message.'</div>', $x__creator, array(), 0, 0, false);


            //Log new key:
            $this->X_model->create(array(
                'x__creator' => $x__creator, //Member making request
                'x__previous' => intval($_POST['sign_i__id']),
                'x__type' => 32078, //Sign In Key
                'x__privacy' => 6175, //Pending until used (if used)
                'x__message' => $_POST['account_email_phone'],
                'x__metadata' => array(
                    'hash_code' => md5($session_key.$passcode),
                ),
            ));

        } elseif($possible_phone) {

            //SMS:
            send_sms($_POST['account_email_phone'], $html_message, 0, array(), 0, 0, false);

            //Log new key:
            $this->X_model->create(array(
                'x__creator' => $x__creator, //Member making request
                'x__previous' => intval($_POST['sign_i__id']),
                'x__type' => 32078, //Sign In Key
                'x__privacy' => 6175, //Pending until used (if used)
                'x__message' => $_POST['account_email_phone'],
                'x__metadata' => array(
                    'hash_code' => md5($session_key.$passcode),
                ),
            ));

        }


        return view_json(array(
            'status' => 1,
            'account_id' => $x__creator,
            'valid_email' => ( $valid_email ? 1 : 0 ),
            'account_preview' => ( $x__creator ? '<span class="icon-block">'.view_cover($u['e__cover'], true). '</span>'.$u['e__title'] : '' ),
            'clean_contact' => $_POST['account_email_phone'],
        ));

    }

    function e_reset_discoveries($e__id = 0){

        $member_e = superpower_unlocked(null, true);
        $e__id = ( $e__id > 0 || !$member_e ? $e__id : $member_e['e__id'] );

        //Fetch their current progress transactions:
        $progress_x = $this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //EXPANDED DISCOVERIES
            'x__creator' => $e__id,
        ), array(), 0);

        if(count($progress_x) > 0){

            //Yes they did have some:
            $message = 'Deleted all '.count($progress_x).' discoveries';

            //Log transaction:
            $clear_all_x = $this->X_model->create(array(
                'x__message' => $message,
                'x__type' => 6415,
                'x__creator' => $e__id,
            ));

            //Delete all progressions:
            foreach($progress_x as $progress_x){
                $this->X_model->update($progress_x['x__id'], array(
                    'x__privacy' => 6173, //Transaction Removed
                    'x__reference' => $clear_all_x['x__id'], //To indicate when it was deleted
                ), $e__id, 6415 /* Reset All discoveries */);
            }

        } else {

            //Nothing to do:
            $message = 'Nothing found to be removed';

        }

        //Show basic UI for now:
        foreach($this->E_model->fetch(array('e__id' => $e__id)) as $e){
            return redirect_message('/@'.$e['e__handle'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>'.$message.'</div>');
        }

    }





    function x_create(){
        return view_json($this->X_model->create($_POST));
    }

    function x_set_text(){

        //Authenticate Member:
        $member_e = superpower_unlocked();
        $e___12112 = $this->config->item('e___12112');

        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
                'original_val' => '',
            ));

        } elseif(!isset($_POST['s__id']) || !isset($_POST['cache_e__id']) || !isset($_POST['new_i__message'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif($_POST['cache_e__id']==6197 /* SOURCE FULL NAME */){

            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['s__id'],
                'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));
            if(!count($es)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Source ID #3',
                    'original_val' => '',
                ));
            }


            $validate_e__title = validate_e__title($_POST['new_i__message']);
            if(!$validate_e__title['status']){
                return view_json(array_merge($validate_e__title, array(
                    'original_val' => $es[0]['e__title'],
                )));
            }

            //All good, go ahead and update:
            $this->E_model->update($es[0]['e__id'], array(
                'e__title' => $validate_e__title['e__title_clean'],
            ), true, $member_e['e__id']);

            //Reset member session data if this data belongs to the logged-in member:
            if ($es[0]['e__id']==$member_e['e__id']) {
                //Re-activate Session with new data:
                $es[0]['e__title'] = $validate_e__title['e__title_clean'];
                $this->E_model->activate_session($es[0], true);
            }

            return view_json(array(
                'status' => 1,
            ));

        } else {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown Update Type ['.$_POST['cache_e__id'].']',
                'original_val' => '',
            ));

        }
    }

    function x_mass_apply_preview()
    {

        if(!isset($_POST['apply_id']) || !isset($_POST['s__id'])){
            die('Missing core data');
        }

        //Log Modal View
        $member_e = superpower_unlocked();
        $this->X_model->create(array(
            'x__creator' => ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 ),
            'x__type' => 14576, //MODAL VIEWED
            'x__following' => $_POST['apply_id'],
            'x__follower' => ( $_POST['apply_id']==4997 ? $_POST['s__id'] : 0 ),
            'x__next' => ( $_POST['apply_id']==12589 ? $_POST['s__id'] : 0 ),
        ));

        if(!isset($_POST['apply_id']) || !isset($_POST['s__id'])){
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing Core Data</div>';
        } else {
            if($_POST['apply_id']==4997){

                //Source list:
                $counter = view_e_covers(12274, $_POST['s__id'], 0, false);
                if(!$counter){
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>No Sources yet</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="fas fa-list"></i></span>Will apply to '.$counter.' source'.view__s($counter).':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach(view_e_covers(12274, $_POST['s__id'], 1, true) as $e) {
                        array_push($ids, $e['e__id']);
                        echo view_card_e(12274, $e);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent" title="Total of '.count($ids).'">'.join(', ',$ids).'</div>';
                }

            } elseif($_POST['apply_id']==12589){

                //idea list:
                $is_next = $this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //IDEA LINKS
                    'x__previous' => $_POST['s__id'],
                ), array('x__next'), 0, 0, array('x__weight' => 'ASC'));
                $counter = count($is_next);

                if(!$counter){
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>No Ideas yet</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="fas fa-list"></i></span>Will apply to '.$counter.' idea'.view__s($counter).':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach($is_next as $i) {
                        array_push($ids, $i['i__id']);
                        echo view_card_i(12273, $i);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent">'.join(',',$ids).'</div>';
                }

            } else {
                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Unknown Apply ID</div>';
            }
        }
    }

    function x_start($focus_i__hashtag){

        //Adds Idea to the Members read

        $member_e = superpower_unlocked();
        $e___11035 = $this->config->item('e___11035'); //Summary

        //valid idea?
        $is = $this->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower($focus_i__hashtag),
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        if(!count($is)){
            return redirect_message('/', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Idea #'.$focus_i__hashtag.' is not active</div>');
        }

        //Check to see if added to read for logged-in members:
        if(!$member_e){
            return redirect_message(view_app_link(4269).'?i__hashtag='.$focus_i__hashtag);
        }

        //Add this Idea to their read If not there:
        $next_i__hashtag = $focus_i__hashtag;

        if(!in_array($is[0]['i__id'], $this->X_model->started_ids($member_e['e__id']))){

            //is available?
            $i_is_discoverable = i_is_discoverable($is[0]['i__id'], true);
            if(!$i_is_discoverable['status']){
                return redirect_message('/'.$i_is_discoverable['return_i__hashtag'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$i_is_discoverable['message'].'</div>');
            }

            //Is startable?
            if(!count($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                'x__next' => $is[0]['i__id'],
                'x__following' => 4235,
            )))){
                return redirect_message('/'.$focus_i__hashtag, '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>This idea is not startable.</div>');
            }

            //Add Starting Point:
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => 4235, //Get started
                'x__next' => $is[0]['i__id'],
                'x__previous' => $is[0]['i__id'],
            ));

            //Mark as complete:
            $this->X_model->x_read_only_complete($member_e['e__id'], $is[0]['i__id'], $is[0]);

            //Now return next idea:
            $next_i__hashtag = $this->X_model->find_next($member_e['e__id'], $is[0]['i__hashtag'], $is[0]);
            if(!$next_i__hashtag){
                //Failed to add to read:
                return redirect_message(home_url());
            }
        }

        //Go to this newly added idea:
        return redirect_message('/'.$focus_i__hashtag.'/'.$next_i__hashtag);

    }

    function x_next($top_i__hashtag, $focus_i__hashtag){

        $member_e = superpower_unlocked();
        $is = $this->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower($focus_i__hashtag),
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));

        if(!$member_e){
            return redirect_message(view_app_link(4269).'?i__hashtag='.$top_i__hashtag);
        } elseif(!$this->X_model->started_ids($member_e['e__id'], $top_i__hashtag)) {
            return redirect_message('/'.$top_i__hashtag);
        } elseif(!count($is)) {
            return redirect_message('/'.$top_i__hashtag, '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>Idea #'.$focus_i__hashtag.' is not currently active.</div>');
        }

        $i_is_discoverable = i_is_discoverable($is[0]['i__id'], true, false);
        if(!$i_is_discoverable['status']){
            return redirect_message('/'.$top_i__hashtag.'/'.$i_is_discoverable['return_i__hashtag'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$i_is_discoverable['message'].'</div>');
        }

        //Go to Next Idea:
        $next_i__hashtag = $this->X_model->find_next($member_e['e__id'], $is[0]['i__hashtag'], $is[0]);
        if($next_i__hashtag){

            return redirect_message('/'.$top_i__hashtag.'/'.$next_i__hashtag );

        } else {

            //Mark as Complete
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => 14730, //COMPLETED 100%
                'x__next' => $is[0]['i__id'],
                //TODO Maybe log additional details like total ideas, time, etc
            ));

            return redirect_message('/'.$top_i__hashtag);

            //TODO Go to Rating or Checkout App since the entire tree is discovered

        }
    }

    function x_view_load_page()
    {

        $focus_e = array();
        $previous_i = array();

        if(!isset($_POST['focus_card'])){
            die('Missing input. Refresh and try again.');
        }
        $success = false;

        if($_POST['focus_card']==12274){

            //SOURCE
            $focus_es = $this->E_model->fetch(array(
                'e__id' => $_POST['focus_id'],
            ));
            $focus_e = $focus_es[0];

            foreach(view_e_covers($_POST['x__type'], $_POST['focus_id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['x__type'], $this->config->item('n___11028'))) {
                    echo view_card_e($_POST['x__type'], $s);
                    $success = true;
                } else if ($_POST['x__type']==6255 || in_array($_POST['x__type'], $this->config->item('n___42284')) || in_array($_POST['x__type'], $this->config->item('n___42261')) || in_array($_POST['x__type'], $this->config->item('n___11020'))) {
                    echo view_card_i($_POST['x__type'], $s, $previous_i, null, $focus_e);
                    $success = true;
                }
            }

        } elseif($_POST['focus_card']==12273) {

            //IDEA
            $previous_is = $this->I_model->fetch(array(
                'i__id' => $_POST['focus_id'],
            ));
            $previous_i = $previous_is[0];

            foreach(view_i_covers($_POST['x__type'], $_POST['focus_id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['x__type'], $this->config->item('n___11020'))) {
                    echo view_card_i($_POST['x__type'], $s, $previous_i, null, $focus_e);
                    $success = true;
                } else if ($_POST['x__type']==6255 || in_array($_POST['x__type'], $this->config->item('n___42261')) || in_array($_POST['x__type'], $this->config->item('n___42284')) || in_array($_POST['x__type'], $this->config->item('n___11028'))) {
                    echo view_card_e($_POST['x__type'], $s);
                    $success = true;
                }
            }
        }

        if(!$success){
            die('Nothing more to load :)');
        }

    }

    function x_reset_sorting()
    {

        //Authenticate Member:
        $member_e = superpower_unlocked(13422);

        if (!$member_e) {
            view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(13422),
            ));
        } elseif (!isset($_POST['focus_card']) || !in_array($_POST['focus_card'], $this->config->item('n___28956'))) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid focus_card',
            ));
        } elseif (!isset($_POST['focus_id']) || intval($_POST['focus_id']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid focus_id',
            ));
        }

        if($_POST['focus_card']==12273){
            //Ideas order based on alphabetical order
            $order = 0;
            foreach($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //IDEA LINKS
                'x__previous' => $_POST['focus_id'],
            ), array('x__next'), 0, 0, array('i__message' => 'ASC')) as $x) {
                $order++;
                $this->X_model->update($x['x__id'], array(
                    'x__weight' => $order,
                ), $member_e['e__id'], 13007 /* SOURCE SORT RESET */);
            }
        } elseif($_POST['focus_card']==12274){
            //Sources reset order
            foreach($this->X_model->fetch(array(
                'x__following' => $_POST['focus_id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ), array('x__follower'), 0, 0, array()) as $x) {
                $this->X_model->update($x['x__id'], array(
                    'x__weight' => 0,
                ), $member_e['e__id'], 13007 /* SOURCE SORT RESET */);
            }
        }


        //Display message:
        view_json(array(
            'status' => 1,
        ));
    }

    function x_upload()
    {

        //Authenticate Member:
        $member_e = superpower_unlocked();
        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['i__id'])) {

            //Log this error!
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__creator' => $member_e['e__id'],
                'x__message' => 'x_upload() Missing POST ERROR',
                'x__metadata' => array(
                    'post' => $_POST,
                ),
            ));

            return view_json(array(
                'status' => 0,
                'post' => $_POST,
                'get' => $_GET,
                'req' => $_REQUEST,
                'message' => 'Missing IDEA',
            ));

        } elseif (!isset($_POST['top_i__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Top IDEA',
            ));

        }

        //Validate Idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        if(count($is)<1){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }


        //Delete previous answer(s):
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__previous' => $is[0]['i__id'],
            'x__creator' => $member_e['e__id'],
        )) as $x_progress){
            $this->X_model->update($x_progress['x__id'], array(
                'x__privacy' => 6173, //Transaction Removed
            ), $member_e['e__id'], 12129 /* DISCOVERY ANSWER DELETED */);
        }

        //Save new answer:
        $this->X_model->mark_complete(12117, $member_e['e__id'], $_POST['top_i__id'], $is[0], array(
            'x__message' => '', //TODO Needs URL
        ));

        //All good:
        $e___11035 = $this->config->item('e___11035'); //Summary
        return view_json(array(
            'status' => 1,
            'message' => '', //TODO Needs URL
        ));

    }

    function x_read_only_complete(){

        //Validate/Fetch idea:
        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['i__id']) || !intval($_POST['i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea ID.',
            ));
        } elseif (!isset($_POST['top_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Top idea ID.',
            ));
        }

        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        if(count($is) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        }


        //Mark as complete?
        if($this->X_model->x_read_only_complete($member_e['e__id'], $_POST['top_i__id'], $is[0])){
            //All good:
            return view_json(array(
                'status' => 1,
                'message' => 'Saved & Next',
            ));
        } else {
            return view_json(array(
                'status' => 0,
                'message' => 'Cannot complete this idea',
            ));
        }


    }

    function x_skip(){


        //Validate/Fetch idea:
        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['i__id']) || !intval($_POST['i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea ID.',
            ));
        } elseif (!isset($_POST['top_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Top idea ID.',
            ));
        }

        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));

        //Log Skipped:
        $this->X_model->mark_complete(31022, $member_e['e__id'], intval($_POST['top_i__id']), $is[0]);

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Skipped & Next',
        ));

    }

    function x_free_ticket(){

        if (!isset($_POST['i__id']) || !intval($_POST['i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea ID.',
            ));
        }

        //Validate/Fetch idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['top_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Top idea ID.',
            ));
        } elseif (!isset($_POST['paypal_quantity'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Count.',
            ));
        } elseif(count($is) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        } elseif($is[0]['i__type']!=26560){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea Type',
            ));
        }


        if(!count($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___30469')) . ')' => null, //Tickets
            'x__creator' => $member_e['e__id'],
            'x__previous' => $is[0]['i__id'],
        )))){
            //Free Ticket:
            $this->X_model->mark_complete(42332, $member_e['e__id'], $_POST['top_i__id'], $is[0], array(
                'x__weight' => $_POST['paypal_quantity'],
            ));
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Saved & Next',
        ));

    }

    function x_write(){

        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['i__id']) || !intval($_POST['i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea ID.',
            ));
        } elseif (!isset($_POST['top_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Top idea ID.',
            ));
        } elseif (!isset($_POST['x_write'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Response Variable.',
            ));
        }


        //Validate/Fetch idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        if(count($is) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        } elseif (!in_array($is[0]['i__type'] , $this->config->item('n___34849'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Idea Type is not writable.',
            ));
        }



        //Any Preg Remove?
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
            'x__next' => $_POST['i__id'],
            'x__following' => 32103, //Preg Remove
        )) as $preg_query){
            $new_form = preg_replace($preg_query['x__message'], "", $_POST['x_write'] );
            if($new_form != $_POST['x_write']) {
                $_POST['x_write'] = $new_form;
                //Log preg removal
                $this->X_model->create(array(
                    'x__type' => 32103, //Preg Remove
                    'x__creator' => $member_e['e__id'],
                    'x__message' => '['.$_POST['x_write'].'] Transformed to ['.$new_form.']',
                    'x__previous' => $_POST['top_i__id'],
                    'x__next' => $_POST['i__id'],
                ));
            }
        }

        $_POST['x_write'] = trim($_POST['x_write']);

        //Trying to Skip?
        if(!strlen($_POST['x_write'])){
            if(count($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                'x__next' => $_POST['i__id'],
                'x__following' => 28239, //Can Skip
            )))){
                //Log Skip:
                $this->X_model->mark_complete(31022, $member_e['e__id'], intval($_POST['top_i__id']), $is[0], array(
                    'x__message' => $_POST['x_write'],
                ));
                //All good:
                return view_json(array(
                    'status' => 1,
                    'message' => 'Skipped & Next',
                ));
            } else {
                //Cannot Skip:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Write a response before going next.',
                ));
            }
        }



        //Type Specific Requirements?
        if ($is[0]['i__type']==30350) {

            $x__type = 31798; //Set Time

            $time_starts = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                'x__next' => $_POST['i__id'],
                'x__following' => 42203, //Time Equal or Greater Than
            ), array(), 1);
            $time_ends = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                'x__next' => $_POST['i__id'],
                'x__following' => 26557, //Time Ends
            ), array(), 1);

            if(!strtotime($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Please enter a valid time.',
                ));
            } elseif(count($time_starts) && strtotime($time_starts[0]['x__message'])<strtotime($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a date after '.$time_starts[0]['x__message'],
                ));
            } elseif(count($time_ends) && strtotime($time_ends[0]['x__message'])>strtotime($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a date before '.$time_ends[0]['x__message'],
                ));
            }

        } elseif ($is[0]['i__type']==31794) {

            $x__type = 31797; //Entered Number

            $min_value = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                'x__next' => $_POST['i__id'],
                'x__following' => 31800, //Min Value
            ), array(), 1);
            $max_value = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                'x__next' => $_POST['i__id'],
                'x__following' => 31801, //Max Value
            ), array(), 1);

            if(!is_numeric($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Please enter a valid number.',
                ));
            } elseif(count($min_value) && floatval($min_value[0]['x__message'])<floatval($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a number bigger than '.$min_value[0]['x__message'],
                ));
            } elseif(count($max_value) && floatval($max_value[0]['x__message'])>floatval($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a number smaller than '.$max_value[0]['x__message'],
                ));
            }

        } elseif ($is[0]['i__type']==31795) {

            $x__type = 31799; //Entered URL

            if(!filter_var($_POST['x_write'], FILTER_VALIDATE_URL)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Please enter a valid URL.',
                ));
            }

        } elseif ($is[0]['i__type']==6683) {

            $x__type = 6144; //Text Replied

        } elseif ($is[0]['i__type']==32603) {

            $x__type = 33614; //Agreement Signed

            if(strlen($_POST['x_write'])<5) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Legal Name is too short',
                ));
            } elseif(!substr_count($_POST['x_write'], ' ')){
                return view_json(array(
                    'status' => 0,
                    'message' => 'You must enter both your first name & last name!',
                ));
            }

        } else {

            //Unknown type!
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__message' => 'x_write() Unknown text reply',
                'x__metadata' => array(
                    'post' => $_POST,
                ),
            ));
            return false;

        }


        //Any Preg Match?
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
            'x__next' => $_POST['i__id'],
            'x__following' => 26611, //Preg Match
        )) as $preg_query){
            if(!preg_match($preg_query['x__message'], $_POST['x_write'])) {

                //Do we have a custom message:
                $preg_query_message = $this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                    'x__next' => $_POST['i__id'],
                    'x__following' => 30998, //Preg Match Error
                ));

                $error_message = ( count($preg_query_message) && strlen($preg_query_message[0]['x__message']) ? $preg_query_message[0]['x__message'] : 'Invalid Input, Please try again' );

                //Log preg match failure
                $this->X_model->create(array(
                    'x__type' => 30998, //Preg Match Error Message
                    'x__creator' => $member_e['e__id'],
                    'x__message' => $error_message,
                    'x__previous' => $_POST['top_i__id'],
                    'x__next' => $_POST['i__id'],
                ));

                //We have an error, let the user know:
                return view_json(array(
                    'status' => 0,
                    'message' => $error_message,
                ));

            }
        }


        //Delete previous answer(s) if any:
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__previous' => $is[0]['i__id'],
            'x__creator' => $member_e['e__id'],
        )) as $x_progress){
            $this->X_model->update($x_progress['x__id'], array(
                'x__privacy' => 6173, //Transaction Removed
            ), $member_e['e__id'], 12129 /* DISCOVERY ANSWER DELETED */);
        }

        //Save new answer:
        $this->X_model->mark_complete($x__type, $member_e['e__id'], intval($_POST['top_i__id']), $is[0], array(
            'x__message' => $_POST['x_write'],
        ));

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Saved & Next',
        ));

    }

    function x_update_instant_select(){

        if(!isset($_POST['focus_id']) || !isset($_POST['o__id']) || !isset($_POST['element_id']) || !isset($_POST['new_e__id']) || !isset($_POST['migrate_s__handle']) || !isset($_POST['x__id'])){
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        //Validate migration handles if any:
        $_POST['migrate_s__handle'] = trim($_POST['migrate_s__handle']);
        $first_letter = substr($_POST['migrate_s__handle'], 0, 1);
        if($first_letter=='@' && strlen($_POST['migrate_s__handle'])>1){
            if (!count($this->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower(substr($_POST['migrate_s__handle'], 1)),
            )))) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migrate_s__handle'].' is an invalid Source Handle. Try again if you want to migrate this source links or leave the field blank.',
                ));
            }
        } elseif($first_letter=='#' && strlen($_POST['migrate_s__handle'])>1){
            if(!count($this->I_model->fetch(array(
                'LOWER(i__hashtag)' => strtolower(substr($_POST['migrate_s__handle'], 1)),
            )))){
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migrate_s__handle'].' is an invalid Idea Hashtag. Try again if you want to migrate this idea links or leave the field blank.',
                ));
            }
        } else {
            $_POST['migrate_s__handle'] = '';
        }

        if(is_array($_POST['o__id'])){
            $mass_result = array();
            foreach($_POST['o__id'] as $o__id){
                array_push($mass_result, $this->X_model->x_update_instant_select($_POST['focus_id'],$o__id,$_POST['element_id'],$_POST['new_e__id'],$_POST['migrate_s__handle'],$_POST['x__id']));
            }
            return view_json($mass_result);
        } else {
            return view_json($this->X_model->x_update_instant_select($_POST['focus_id'],$_POST['o__id'],$_POST['element_id'],$_POST['new_e__id'],$_POST['migrate_s__handle'],$_POST['x__id']));
        }

    }

    function x_select(){

        if(!isset($_POST['top_i__id']) || !isset($_POST['focus_id'])){
            die('missing core data');
        }

        return view_json($this->X_model->x_select($_POST['top_i__id'], $_POST['focus_id'], ( !isset($_POST['selection_i__id']) || !count($_POST['selection_i__id']) ? array() : $_POST['selection_i__id'] )));

    }

    function x_link_toggle(){

        //Authenticate Member:
        $member_e = superpower_unlocked();
        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['i__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));

        } elseif (!isset($_POST['x__type'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Type',
            ));

        } elseif (!isset($_POST['top_i__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Top Idea ID',
            ));

        }

        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        if (!count($is)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }

        //Save IDEA:
        $x = $this->X_model->create(array(
            'x__creator' => $member_e['e__id'],
            'x__following' => $member_e['e__id'],
            'x__previous' => $_POST['top_i__id'],
            'x__next' => $_POST['i__id'],
            'x__type' => $_POST['x__type'],
        ));

        //All Good:
        return view_json(array(
            'status' => 1,
            'x__id' => $x['x__id'],
        ));

    }

    function x_remove(){

        /*
         *
         * When members indicate they want to stop
         * a IDEA this function saves the changes
         * necessary and delete the idea from their
         * discoveries.
         *
         * */

        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['x__id']) || intval($_POST['x__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));
        }

        //Remove Idea
        $this->X_model->update($_POST['x__id'], array(
            'x__privacy' => 6173, //DELETED
        ), $member_e['e__id'], 10673);

        return view_json(array(
            'status' => 1,
        ));
    }

    function x_4341(){

        /*
         * Loads the list of transactions based on the
         * filters passed on.
         *
         * */

        $query_filters = unserialize($_POST['x_filters']);
        $joined_by = unserialize($_POST['x_joined_by']);
        $page_num = ( isset($_POST['page_num']) && intval($_POST['page_num'])>=2 ? intval($_POST['page_num']) : 1 );
        $next_page = ($page_num+1);
        $query_offset = (($page_num-1)*view_memory(6404,11064));
        $member_e = superpower_unlocked();

        $message = '';

        //Fetch transactions and total transaction counts:
        $x = $this->X_model->fetch($query_filters, $joined_by, view_memory(6404,11064), $query_offset);
        $x_count = $this->X_model->fetch($query_filters, $joined_by, 0, 0, array(), 'COUNT(x__id) as total_count');
        $total_items_loaded = ($query_offset+count($x));
        $has_more_x = ($x_count[0]['total_count'] > 0 && $total_items_loaded < $x_count[0]['total_count']);


        //Display filter:
        if($total_items_loaded > 0){

            //Subsequent messages:
            $message .= '<div class="main__title x-info grey">'.( $x_count[0]['total_count']>$total_items_loaded ? ( $has_more_x && $query_offset==0  ? 'FIRST ' : ($query_offset+1).' - ' ) . ( $total_items_loaded >= ($query_offset+1) ?  $total_items_loaded . ' OF ' : '' ) : '') . number_format($x_count[0]['total_count'] , 0) .' TRANSACTIONS:</div>';

        }


        if(count($x)>0){

            $message .= '<div class="list-group list-grey">';
            foreach($x as $x) {

                $message .= view_card_x($x);

                if($member_e && strlen($x['x__message'])>0 && strlen($_POST['x__message_find'])>0 && strlen($_POST['x__message_replace'])>0 && substr_count($x['x__message'], $_POST['x__message_find'])>0){

                    $new_content = str_replace($_POST['x__message_find'],trim($_POST['x__message_replace']),$x['x__message']);

                    $this->X_model->update($x['x__id'], array(
                        'x__message' => $new_content,
                    ), $member_e['e__id'], 12360, update_description($x['x__message'], $new_content));

                    $message .= '<div class="alert alert-info" role="alert"><i class="fas fa-check-circle"></i> Replaced ['.$_POST['x__message_find'].'] with ['.trim($_POST['x__message_replace']).']</div>';

                }

            }
            $message .= '</div>';

            //Do we have more to show?
            if($has_more_x){
                $message .= '<div id="x_page_'.$next_page.'"><a href="javascript:void(0);" style="margin:10px 0 72px 0;" class="btn btn-6255" onclick="x_4341(x_filters, x_joined_by, '.$next_page.');"><span class="icon-block"><i class="fas fa-search-plus"></i></span>Page '.$next_page.'</a></div>';
                $message .= '';
            } else {
                $message .= '<div style="margin:10px 0 72px 0;"><span class="icon-block"><i class="fas fa-check-circle"></i></span>All '.$x_count[0]['total_count'].' transactions have been loaded</div>';

            }

        } else {

            //Show no transaction warning:
            $message .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>No Transactions found with the selected filters. Modify filters and try again.</div>';

        }


        return view_json(array(
            'status' => 1,
            'message' => $message,
        ));


    }

    function x_33292(){

        $miscstats = '';

        //See if we have any idea or source targets to limit our stats:
        if(isset($_POST['e__handle']) && strlen($_POST['e__handle'])){

            //See stats for this source:
            $es = $this->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower($_POST['e__handle']),
            ));
            if(!count($es)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Handle',
                ));
            }

        } elseif(isset($_POST['i__hashtag']) && strlen($_POST['i__hashtag'])){

            //See stats for this idea:
            $is = $this->I_model->fetch(array(
                'LOWER(i__hashtag)' => strtolower($_POST['i__hashtag']),
            ));
            if(!count($is)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Hashtag',
                ));
            }

            $recursive_down_ids = $this->I_model->recursive_down_ids($is[0], 'ALL');

            //List stats:
            $miscstats .= '<div>Tree Ideas: '.number_format(count($recursive_down_ids['recursive_i_ids']), 0).'</div>';

        }


        //Count transactions:
        $return_array = array();
        foreach($this->config->item('e___33292') as $x__type1 => $m1) {
            $level1_total = 0;
            foreach($this->config->item('e___'.$x__type1) as $x__type2 => $m2) {
                $map_primary_links = map_primary_links($x__type2);
                $level2_total = 0;
                if(!$map_primary_links ||!is_array($this->config->item('e___'.$map_primary_links)) || !count($this->config->item('e___'.$map_primary_links)) ){
                    continue;
                }
                foreach($this->config->item('e___'.$map_primary_links) as $x__type3 => $m3) {

                    if($x__type2==12273){

                        if(isset($_POST['e__handle']) && strlen($_POST['e__handle'])){

                            $sub_counter = $this->X_model->fetch(array(
                                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                                'x__following' => $es[0]['e__id'],
                                'i__type' => $x__type3,
                                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                            ), array('x__next'), 0, 0, array(), 'COUNT(x__id) as totals');

                        } elseif(isset($_POST['i__hashtag']) && strlen($_POST['i__hashtag']) && count($recursive_down_ids['recursive_i_ids'])){

                            //See stats for this idea:
                            $sub_counter = $this->I_model->fetch(array(
                                'i__type' => $x__type3,
                                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                                'i__id IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . ')' => null,
                            ), 0, 0, array(), 'COUNT(i__id) as totals');

                        } else {

                            $sub_counter = $this->I_model->fetch(array(
                                'i__type' => $x__type3,
                                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                            ), 0, 0, array(), 'COUNT(i__id) as totals');

                        }

                    } elseif($x__type2==12274){

                        if(isset($_POST['e__handle']) && strlen($_POST['e__handle'])){

                            $sub_counter = $this->X_model->fetch(array(
                                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'x__following' => $es[0]['e__id'],
                                'e__privacy' => $x__type3,
                            ), array('x__follower'), 0, 0, array(), 'COUNT(x__id) as totals');

                        } elseif(isset($_POST['i__hashtag']) && strlen($_POST['i__hashtag']) && count($recursive_down_ids['recursive_i_ids'])){

                            //See stats for this idea:
                            $sub_counter = $this->X_model->fetch(array(
                                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                                'x__next IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . ')' => null,
                                'e__privacy' => $x__type3,
                            ), array('x__following'), 0, 0, array(), 'COUNT(x__id) as totals');

                        } else {

                            $sub_counter = $this->E_model->fetch(array(
                                'e__privacy' => $x__type3,
                            ), 0, 0, array(), 'COUNT(e__id) as totals');

                        }

                    } else {

                        if(isset($_POST['e__handle']) && strlen($_POST['e__handle'])){

                            $sub_counter = $this->X_model->fetch(array(
                                'x__type' => $x__type3,
                                '( x__follower = ' . $es[0]['e__id'] . ' OR x__following = ' . $es[0]['e__id'] . ' OR x__creator = ' . $es[0]['e__id'] . ' )' => null,
                                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

                        } elseif(isset($_POST['i__hashtag']) && strlen($_POST['i__hashtag']) && count($recursive_down_ids['recursive_i_ids'])){

                            $sub_counter = $this->X_model->fetch(array(
                                'x__type' => $x__type3,
                                '( x__previous IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . ') OR x__next IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . '))' => null,
                                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

                        } else {

                            $sub_counter = $this->X_model->fetch(array(
                                'x__type' => $x__type3,
                                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

                        }

                    }

                    $level2_total += $sub_counter[0]['totals'];
                    array_push($return_array , array(
                        'sub_id' => $x__type3,
                        'sub_counter' => number_format($sub_counter[0]['totals'], 0),
                    ));

                }

                $level1_total += $level2_total;
                array_push($return_array , array(
                    'sub_id' => $x__type2,
                    'sub_counter' => number_format($level2_total, 0),
                ));

            }

            array_push($return_array , array(
                'sub_id' => $x__type1,
                'sub_counter' => number_format($level1_total, 0),
            ));

        }
        return view_json(array(
            'status' => 1,
            'return_array' => $return_array,
            'miscstats' => $miscstats,
        ));
    }

}