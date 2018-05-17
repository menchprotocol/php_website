<?php

function is_dev(){
    return ( isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='local.mench.co' );
}

function is_old(){
    return ( isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='mench.co' );
}

function lock_cron_for_processing($e_items){
    $CI =& get_instance();
    foreach($e_items as $e){
        if($e['e_id']>0 && $e['e_status']==0){
            $CI->Db_model->e_update( $e['e_id'] , array(
                'e_status' => -2, //Processing so other Cron jobs do not touch this...
            ));
        }
    }
}

function missing_required_db_fields($insert_columns,$field_array){
    foreach($field_array as $req_field){
        if(!isset($insert_columns[$req_field]) || strlen($insert_columns[$req_field])==0){
            //Ooops, we're missing this required field:
            $CI =& get_instance();
            $CI->Db_model->e_create(array(
                'e_text_value' => 'Missing required field ['.$req_field.'] for inserting new DB row',
                'e_json' => array(
                    'insert_columns' => $insert_columns,
                    'required_fields' => $field_array,
                ),
                'e_inbound_c_id' => 8, //Platform Error
            ));

            return true; //We have an issue
        }
    }

    //No errors found, all good:
    return false; //Not missing anything
}


function fetch_entity_tree($inbound_u_id,$is_edit=false,$entity_breadcrumb_prefix=false){

    $inbound_u_id = intval($inbound_u_id);
    $view_data = array(
        'inbound_u_id' => $inbound_u_id,
    );

    //Fetch parent name:
    if($inbound_u_id){

        $CI =& get_instance();
        $parent_id = $inbound_u_id; //Start our recursive loop here
        $this_entity = null; //Will be set during the loop below
        $breadcrumb = array(); //Populate as we go along

        while($parent_id){

            //Fetch parent details:
            $parent_entities = $CI->Db_model->u_fetch(array(
                'u_id' => $parent_id,
            ), array('count_child'));

            if(count($parent_entities)<1){
                redirect_message('/entities','<div class="alert alert-danger" role="alert">Invalid Entity ID</div>');
                break;
            } elseif(!$this_entity){
                $this_entity = $parent_entities[0];

                //Push this item to breadcrumb:
                if($is_edit){
                    array_push( $breadcrumb , array(
                        'link' => null,
                        'anchor' => '<i class="fas fa-cog"></i> Modify',
                    ));
                    array_push( $breadcrumb , array(
                        'link' => '/entities/'.$parent_entities[0]['u_id'],
                        'anchor' => $parent_entities[0]['u_full_name'],
                    ));
                } else {
                    array_push( $breadcrumb , array(
                        'link' => null,
                        'anchor' => $parent_entities[0]['u_full_name'],
                    ));
                }

            } else {
                //Push this item to breadcrumb:
                array_push( $breadcrumb , array(
                    'link' => '/entities'.( $parent_id ? '/'.$parent_id : '' ),
                    'anchor' => $parent_entities[0]['u_full_name'],
                ));
            }

            //Set new parent ID:
            $parent_id = intval($parent_entities[0]['u_inbound_u_id']);
        }

        //Add core entity item and reverse:
        if($entity_breadcrumb_prefix){
            array_push( $breadcrumb , array(
                'link' => '/entities',
                'anchor' => 'Entities',
            ));
        }

        $view_data['title'] = ( $is_edit ? 'Modify ' : '' ).$this_entity['u_full_name'];
        $view_data['breadcrumb'] = array_reverse($breadcrumb);
        $view_data['entity'] = $this_entity;

    } else {

        $view_data['entity'] = null;
        $view_data['title'] = 'Entities';
        $view_data['breadcrumb'] = array(
            array(
                'link' => null,
                'anchor' => $view_data['title'] . ' <span id="hb_6776" class="help_button" intent-id="6776"></span>',
            ),
        );

    }

    return $view_data;
}



function fetch_action_plan_copy($b_id,$r_id=0,$current_b=null,$release_cache=array()){

    $CI =& get_instance();
    $cache_action_plans = array();
    $bs = array();

    if($r_id){
        //See if we have a copy:
        $cache_action_plans = $CI->Db_model->e_fetch(array(
            'e_inbound_c_id' => 70,
            'e_r_id' => $r_id,
        ), 1, array('ej'));
    }

    if(count($cache_action_plans)>0){

        //Assign this cache to the Bootcamp:
        $b = unserialize($cache_action_plans[0]['ej_e_blob']);

        if($b){
            array_push($bs,$b);

            //Indicate this is a copy:
            $bs[0]['is_copy'] = 1;
            $bs[0]['copy_timestamp'] = $cache_action_plans[0]['e_timestamp'];

            //If we have this, we should replace it to have certain fields updated:
            if($current_b){

                //Any items that we'd like to release its cache?
                foreach($release_cache as $key){
                    //This replaces older values with new ones to ensures we get the most up to date view
                    $bs[0][$key] = $current_b[0][$key];
                }

                //Replace:
                $bs = array_replace_recursive($current_b,$bs);
            }
        }

    }

    if(count($bs)==0){

        //Fetch from live:
        $bs = $CI->Db_model->remix_bs(array(
            'b.b_id' => $b_id,
        ));

        //Indicate this is NOT a copy:
        $bs[0]['is_copy'] = 0;
        $bs[0]['copy_timestamp'] = null;
    }

    if($r_id){
        //Now Fetch Class:
        $classes = $CI->Db_model->r_fetch(array(
            'r_id' => $r_id,
        ), $bs[0] );

        if(count($classes)>0){
            $bs[0]['this_class'] = $classes[0];
        }
    }

    return $bs;
}


function join_keys($input_array,$joiner=','){
    $joined_string = null;
    foreach($input_array as $key=>$value){
        if($joined_string){
            $joined_string .= $joiner;
        }
        $joined_string .= $key;
    }
    return $joined_string;
}



function detect_active_admission($admissions){

    //Determines the active admission of a student, especially useful if they have multiple admissions
    if(count($admissions)<1){

        return false;

    } elseif(count($admissions)>1){

        /*
         * Ohh, let's try to figure this out. There are a few scenarios:
         *
         * 1. Multiple up-coming Bootcaps that do not overlap
         * 2. A mix of past Bootcamps already completed, and some upcoming ones
         * 3. A bunch of past Bootcamps that are all completed and none active
         * 4. A mix and match of above?!
         *
         * ru_status & r_status and are guiding lights here to crack this puzzle
         *
         */

        //TODO Ooptimize the loop below because I cannot fully wrap my head around it for now!
        //Should think further about priorities and various use cases of this function
        //So i'm leaving it as is to be tested further @ later date (Mar 6th 2018)

        $active_admission = null;

        foreach($admissions as $admission){

            //Now see whatssup:
            if($admission['ru_status']>4 || $admission['r_status']>2){

                //This is a completed Class:
                $active_admission = $admission;

            } elseif($admission['ru_status']==4 && $admission['r_status']<2){

                //Class is not started yet:
                $active_admission = $admission;

            } elseif($admission['ru_status']==4 && $admission['r_status']==2){

                //Active class has highest priority, break after:
                $active_admission = $admission;
                break; //This is what we care about the most, so make it have the last say

            } elseif(!$active_admission){

                //Not sure what this could be:
                $active_admission = $admission;

            }
        }

        return $active_admission;

    } elseif(count($admissions)==1){

        //This is typical, treat this as their Active Admission since its the only one they got:
        return $admissions[0];

    }
}

function fetch_file_ext($url){
	//https://cdn.fbsbx.com/v/t59.3654-21/19359558_10158969505640587_4006997452564463616_n.aac/audioclip-1500335487327-1590.aac?oh=5344e3d423b14dee5efe93edd432d245&oe=596FEA95
	$url_parts = explode('?',$url,2);
	$url_parts = explode('/',$url_parts[0]);
	$file_parts = explode('.',end($url_parts));
	return end($file_parts);
}



function parse_signed_request($signed_request) {

    //Fetch app settings:
    $CI =& get_instance();
    $fb_settings = $CI->config->item('fb_settings');

    list($encoded_sig, $payload) = explode('.', $signed_request, 2);

    // Decode the data
    $sig = base64_url_decode($encoded_sig);
    $data = json_decode(base64_url_decode($payload), true);
    
    // Confirm the signature
    $expected_sig = hash_hmac('sha256', $payload, $fb_settings['client_secret'], $raw = true);
    if ($sig !== $expected_sig) {
        //error_log('Bad Signed JSON signature!');
        return null;
    }
    
    return $data;
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}





function extract_level($b,$c_id){

    //This function uses
    
    $CI =& get_instance();
    //This is what we shall return:
    $view_data = array(
        'pid' => $c_id, //To be deprecated at some point...
        'c_id' => $c_id,
        'b' => $b,
    );

    if($b['c_id']==$c_id){
        
        //Level 1 (The Bootcamp itself)
        $view_data['level'] = 1;
        $view_data['task_index'] = 0;
        $view_data['intent'] = $b;
        $view_data['title'] = 'Action Plan | '.$b['c_outcome'];
        $view_data['breadcrumb_p'] = array(
            array(
                'link' => null,
                'anchor' => '<i class="fas fa-dot-circle"></i> '.$b['c_outcome'],
            ),
        );
        //Not applicable at Bootcamp Level:
        $view_data['next_intent'] = null; //Used in actionplan_ui view for Step Sequence Submission positioning to better understand next move
        $view_data['next_level'] = 0; //Used in actionplan_ui view for Step Sequence Submission positioning to better understand next move
        $view_data['previous_intent'] = null; //Used in actionplan_ui view for Step Sequence Submission positioning to better understand previous move
        $view_data['previous_level'] = 0; //Used in actionplan_ui view for Step Sequence Submission positioning to better understand previous move

        return $view_data;
        
    } else {

        //Keeps track of Tasks:
        $previous_intent = null;
        
        foreach($b['c__child_intents'] as $intent_key=>$intent){

            if($intent['c_status']<1){
                continue;
            }
            
            if($intent['c_id']==$c_id){

                //Found this as level 2:
                $view_data['level'] = 2;
                $view_data['task_index'] = $intent['cr_outbound_rank'];
                $view_data['intent'] = $intent;
                $view_data['title'] = 'Action Plan | '.$CI->lang->line('level_'.$view_data['level'].'_name').' '.$intent['cr_outbound_rank'].': '.$intent['c_outcome'];
                $view_data['breadcrumb_p'] = array(
                    array(
                        'link' => '/my/actionplan/'.$b['b_id'].'/'.$b['b_outbound_c_id'],
                        'anchor' => $CI->lang->line('level_'.( isset($b['b_is_parent']) ? $b['b_is_parent'] : 0 ).'_icon').' '.$b['c_outcome'],
                    ),
                    array(
                        'link' => null,
                        'anchor' => $CI->lang->line('level_'.$view_data['level'].'_icon').' '.$CI->lang->line('level_'.$view_data['level'].'_name').' '.$intent['cr_outbound_rank'].': '.$intent['c_outcome'],
                    ),
                );



                //Find the next intent:
                $next_intent = null;
                $next_level = 0;
                $next_key = $intent_key;

                while(!$next_intent){

                    $next_key++;

                    if(!isset($b['c__child_intents'][$next_key]['c_status'])){

                        //Next Task does not exist, return Bootcamp:
                        $next_intent = $b;
                        $next_level = 1;
                        break;

                    } elseif($b['c__child_intents'][$next_key]['c_status']>=1){

                        $next_intent = $b['c__child_intents'][$next_key];
                        $next_level = 2;
                        break;

                    }
                }

                $view_data['next_intent'] = $next_intent;
                $view_data['next_level'] = $next_level;
                $view_data['previous_intent'] = $previous_intent;
                $view_data['previous_level'] = ( $previous_intent ? 2 : 1 );
                
                return $view_data;
                
            } else {

                //Save this:
                $previous_intent = $intent;

                foreach($intent['c__child_intents'] as $step_key=>$step){

                    if($step['c_status']<1){
                        continue;
                    }

                    if($step['c_id']==$c_id){

                        //This is level 3:
                        $view_data['level'] = 3;
                        $view_data['step_goal'] = $intent; //Only available for Steps
                        $view_data['task_index'] = $intent['cr_outbound_rank'];
                        $view_data['intent'] = $step;
                        $view_data['title'] = 'Action Plan | '.$CI->lang->line('level_'.($view_data['level']-1).'_name').' '.$intent['cr_outbound_rank'].' '.$CI->lang->line('level_'.$view_data['level'].'_name').' '.$step['cr_outbound_rank'].': '.$step['c_outcome'];

                        $view_data['breadcrumb_p'] = array(
                            array(
                                'link' => '/my/actionplan/'.$b['b_id'].'/'.$b['b_outbound_c_id'],
                                'anchor' => $CI->lang->line('level_'.$b['b_is_parent'].'_icon').' '.$b['c_outcome'],
                            ),
                            array(
                                'link' => '/my/actionplan/'.$b['b_id'].'/'.$intent['c_id'],
                                'anchor' => $CI->lang->line('level_'.($view_data['level']-1).'_icon').' '.$CI->lang->line('level_'.($view_data['level']-1).'_name').' '.$intent['cr_outbound_rank'].': '.$intent['c_outcome'],
                            ),
                            array(
                                'link' => null,
                                'anchor' => $CI->lang->line('level_'.$view_data['level'].'_icon').' '.$CI->lang->line('level_'.$view_data['level'].'_name').' '.$step['cr_outbound_rank'].': '.$step['c_outcome'],
                            ),
                        );
                        
                        return $view_data;

                    }

                }

            }
        }
        
        //Still here?!
        return false;
    }
}




function extract_urls($text,$inverse=false){
    $text = preg_replace('/[[:^print:]]/', ' ', $text); //Replace non-ascii characters with space
    $parts = preg_split('/\s+/', $text);
    $return = array();
    foreach($parts as $part){
        if(!$inverse && filter_var($part, FILTER_VALIDATE_URL)){
            array_push($return,$part);
        } elseif($inverse && !filter_var($part, FILTER_VALIDATE_URL) && strlen($part)>0){
            array_push($return,$part);
        }
    }
    return $return;
}


function echo_big_num($number){
    if($number>=10000000){
        return '<span title="'.$number.'">'.round(($number/1000000),0).'m</span>';
    } elseif($number>=1000000){
        return '<span title="'.$number.'">'.round(($number/1000000), 1).'m</span>';
    } elseif($number>=10000){
        return '<span title="'.$number.'">'.round(($number/1000), 0).'k</span>';
    } elseif($number>=1000){
        return '<span title="'.$number.'">'.round(($number/1000),1).'k</span>';
    } else {
        return $number;
    }
}





function aggregate_field($input_array,$field){
    $return_array = array();
    foreach($input_array as $item){
        if(isset($item[$field])){
            array_push($return_array,$item[$field]);
        }
    }
    return $return_array;
}





function mime_type($mime){
    if(strstr($mime, "video/")){
        return 'video';
    } else if(strstr($mime, "image/")){
        return 'image';
    } else if(strstr($mime, "audio/")){
        return 'audio';
    } else {
        return 'file';
    }
}

function b_aggregate($b,$skip_parent=false){

    //Aggregate this from child-Bootcamps:
    $b_aggregate = array(
        'b_prerequisites'   => ( strlen($b['b_prerequisites'])>0 && !$skip_parent ? json_decode($b['b_prerequisites']) : array() ),
        'b_transformations' => ( strlen($b['b_transformations'])>0 && !$skip_parent ? json_decode($b['b_transformations']) : array() ),
    );

    //Unset some unnecessary fields that do not make sense for a parent Bootcamp:
    unset($b['b_support_email']);
    unset($b['b_calendly_url']);

    //Set price to zero:
    $b['child_bs'] = array();
    $b['b_p1_rate'] = 0;
    $b['b_p2_rate'] = 0;
    $b['b_p3_rate'] = 0;
    $b['b_p2_weeks'] = 0; //Defines how many weeks this is offered
    $b['b_p3_weeks'] = 0; //Defines how many weeks this is offered
    $b['b_p2_max_seats'] = 0; //Would be offered if any of sub-Bootcamps offer
    $b['b_difficulty_level'] = 0; //Not set

    $CI =& get_instance();

    //Fetch all child Bootcamp details:
    foreach($b['c__child_intents'] as $b7d){

        //Fetch Bootcamp URL key:
        $bs = $CI->Db_model->b_fetch(array(
            'b.b_id' => $b7d['cr_outbound_b_id'],
        ));

        if(count($bs)<1){
            continue;
        }

        //This this as child bootcamp
        $b['child_bs'][$b7d['cr_outbound_b_id']] = $bs[0];

        if(strlen($bs[0]['b_transformations'])>0){
            foreach (json_decode($bs[0]['b_transformations']) as $item){
                if(!in_array($item,$b_aggregate['b_transformations'])){
                    array_push($b_aggregate['b_transformations'],$item);
                }
            }
        }
        if(strlen($bs[0]['b_prerequisites'])>0){
            foreach (json_decode($bs[0]['b_prerequisites']) as $item){
                if(!in_array($item,$b_aggregate['b_prerequisites'])){
                    array_push($b_aggregate['b_prerequisites'],$item);
                }
            }
        }

        //Addup the rates:
        $b['b_p1_rate'] += doubleval($bs[0]['b_p1_rate']);
        if(intval($bs[0]['b_p2_max_seats'])>0){

            $b['b_p2_weeks']++;
            $b['b_p2_rate'] += doubleval($bs[0]['b_p2_rate']);
            $b['b_p3_rate'] += doubleval($bs[0]['b_p3_rate']);

            if($bs[0]['b_p2_max_seats']>$b['b_p2_max_seats']){
                //This is the most difficult child Bootcamp, set this as the overall difficulty:
                $b['b_p2_max_seats'] = $bs[0]['b_p2_max_seats'];
            }

            if($bs[0]['b_p3_rate']>0){
                $b['b_p3_weeks']++;
            }
        }

        //Max Difficulty level:
        if(intval($bs[0]['b_difficulty_level'])>$b['b_difficulty_level']){
            //This is the most difficult child Bootcamp, set this as the overall difficulty:
            $b['b_difficulty_level'] = intval($bs[0]['b_difficulty_level']);
        }
    }

    //Encode like original data:
    $b['b_transformations'] = ( count($b_aggregate['b_transformations'])>0 ? json_encode($b_aggregate['b_transformations']) : null);
    $b['b_prerequisites'] = ( count($b_aggregate['b_prerequisites'])>0 ? json_encode($b_aggregate['b_prerequisites']) : null);

    return $b;
}


function prep_prerequisites($b){
    $week_count = ( $b['b_is_parent'] ? count($b['c__child_intents']) : 1 );
    //Appends system-enforced prerequisites based on Bootcamp settings:
    $pre_req_array = ( strlen($b['b_prerequisites'])>0 ? json_decode($b['b_prerequisites']) : array() );
    if($b['c__estimated_hours']>0){
        array_unshift($pre_req_array, 'Commitment to invest <i class="fal fa-clock"></i> <b>'.echo_hours($b['c__estimated_hours']).' in '.$week_count.' Week'.echo__s($week_count).'</b> anytime that works best for you. This is an average of '.echo_hours($b['c__estimated_hours']/($week_count*7)) .' per day.');
    }
    return $pre_req_array;
}


function b_progress($b){
    
    $CI =& get_instance();
    $udata = $CI->session->userdata('user');

    //This must exist:
    $bl = ( isset($b['b__admins'][0]) ? $b['b__admins'][0] : null );

    //A function used on the dashboard to indicate what is left before launching the Bootcamp
    $progress_possible = 0; //Total points of progress
    $progress_gained = 0; //Points granted for completion
    $checklist = array();



    if(!$b['b_is_parent']){
        //Facebook Page
        $estimated_minutes = 15;
        $progress_possible += $estimated_minutes;
        $e_status = ( $b['b_fp_id']>0 && (!($b['b_fp_id']==4) || $bl['u_inbound_u_id']==1281) ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
        $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/settings#pages',
            'anchor' => '<b>Connect your <i class="fab fa-facebook" style="color:#4267b2;"></i> Facebook Page</b> in Settings (also activates Landing Page)',
            'e_status' => $e_status,
            'time_min' => $estimated_minutes,
        ));
    }



    //Do we have enough Children?
    $estimated_minutes = 60;
    $required_children = ( $b['b_is_parent'] ? 2 : 3 );
    $child_name = ( $b['b_is_parent'] ? 'Bootcamp' : 'Task' );
    $progress_possible += $estimated_minutes;
    $e_status = ( count($b['c__child_intents'])>=$required_children ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
    $progress_gained += ( $e_status==1 ? $estimated_minutes : (count($b['c__child_intents'])/$required_children)*$estimated_minutes );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan',
        'anchor' => '<b>Add '.$required_children.' or more '.$child_name.'s</b>'.( count($b['c__child_intents'])>0 && $e_status==-4 ?' ('.($required_children-count($b['c__child_intents'])).' more)':'').' in Action Plan',
        'e_status' => $e_status,
        'time_min' => $estimated_minutes,
    ));

    
    
    if(count($b['c__child_intents'])>0 && !$b['b_is_parent']){
        //Now check each Task and its Step List:
        foreach($b['c__child_intents'] as $intent_num=>$c){

            if($c['c_status']<0){
                continue; //Don't check Archived Tasks
            }

            //Prepare key variables:
            $intent_anchor = ' #'.$c['cr_outbound_rank'].' ';


            //Task On Start Messages
            $estimated_minutes = 15;
            $progress_possible += $estimated_minutes;
            $qualified_messages = 0;
            if(count($c['c__messages'])>0){
                foreach($c['c__messages'] as $i){
                    $qualified_messages += ( $i['i_status']==1 ? 1 : 0 );
                }
            }
            $e_status = ( $qualified_messages ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
            $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
            array_push( $checklist , array(
                'href' => '/console/'.$b['b_id'].'/actionplan#messages-'.$c['c_id'],
                'anchor' => '<b>Add '.echo_status('i',1).' Message</b> to '.$intent_anchor.$c['c_outcome'],
                'e_status' => $e_status,
                'time_min' => $estimated_minutes,
            ));
            
            //TODO check steps if needed here...
        }
    }


    //Bootcamp Messages:
    $estimated_minutes = 15;
    $progress_possible += $estimated_minutes;
    $qualified_messages = 0;
    if(count($b['c__messages'])>0){
        foreach($b['c__messages'] as $i){
            $qualified_messages += ( $i['i_status']==1 && ( $i['i_media_type']=='image' || ($i['i_media_type']=='text' && strlen($i['i_url'])>0 && echo_embed($i['i_url'],$i['i_message'],true))) ? 1 : 0 );
        }
    }
    $e_status = ( $qualified_messages ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
    $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan#messages-'.$b['b_outbound_c_id'],
        'anchor' => '<b>Upload an Image or add YouTube Link</b> for your cover photo in Action Plan',
        'e_status' => $e_status,
        'time_min' => $estimated_minutes,
    ));







    if(!$b['b_is_parent']){
        //Prerequisites
        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $e_status = ( strlen($b['b_prerequisites'])>0 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
        $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/actionplan#prerequisites',
            'anchor' => '<b>Set 1 or more Prerequisites</b> for your Bootcamp in Action Plan',
            'e_status' => $e_status,
            'time_min' => $estimated_minutes,
        ));


        //Skills You Will Gain
        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $e_status = ( strlen($b['b_transformations'])>0 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
        $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/actionplan#skills',
            'anchor' => '<b>Define Skills You Will Gain</b> in Action Plan',
            'e_status' => $e_status,
            'time_min' => $estimated_minutes,
        ));
    }





    
    
    /* *******************************
     *  Leader profile (for them only)
     *********************************/
    if($bl){
        $is_my_account = ( $bl['u_id']==$udata['u_id'] );
        $account_anchor = ( $is_my_account ? 'My Account' : $bl['u_full_name'].'\'s Account' );
        $account_href = ( $is_my_account ? '/console/account' : null );


        //u_phone
        $estimated_minutes = 5;
        $progress_possible += $estimated_minutes;
        $e_status = ( strlen($bl['u_phone'])>0 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
        $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => ( $account_href ? $account_href.'#communication' : null ),
            'anchor' => '<b>Set Private Phone Number</b> in '.$account_anchor,
            'e_status' => $e_status,
            'time_min' => $estimated_minutes,
        ));

        //u_cover_x_id
        $estimated_minutes = 10;
        $progress_possible += $estimated_minutes;
        $e_status = ( intval($bl['u_cover_x_id'])>0 ? 1 /*Has Cover Photo*/ : -4 /*Pending Completion*/ );
        $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => $account_href,
            'anchor' => '<b>Set Cover Photo</b> in '.$account_anchor,
            'e_status' => $e_status,
            'time_min' => $estimated_minutes,
        ));

        //u_country_code && u_current_city
        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $e_status = ( strlen($bl['u_country_code'])>0 && strlen($bl['u_current_city'])>0 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
        $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => $account_href,
            'anchor' => '<b>Set Location</b> in '.$account_anchor,
            'e_status' => $e_status,
            'time_min' => $estimated_minutes,
        ));


        //u_timezone
        $estimated_minutes = 15;
        $progress_possible += $estimated_minutes;
        $e_status = ( strlen($bl['u_timezone'])>0 && strlen($bl['u_timezone'])>0 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
        $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => ( $account_href ? $account_href.'#communication' : null ),
            'anchor' => '<b>Set Timezone</b> in '.$account_anchor,
            'e_status' => $e_status,
            'time_min' => $estimated_minutes,
        ));

        //u_language
        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $e_status = ( strlen($bl['u_language'])>0 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
        $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => ( $account_href ? $account_href.'#communication' : null ),
            'anchor' => '<b>Set Languages</b> in '.$account_anchor,
            'e_status' => $e_status,
            'time_min' => $estimated_minutes,
        ));

        //u_bio
        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $e_status = ( strlen($bl['u_bio'])>0 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
        $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => $account_href,
            'anchor' => '<b>Set Introductory Message</b> in '.$account_anchor,
            'e_status' => $e_status,
            'time_min' => $estimated_minutes,
        ));

        //Profile counter:
        $profile_counter = ( strlen($bl['u_primary_url'])>0 ? 1 : 0 );
        $profile_counter = 1;
        $u_social_account = $CI->config->item('u_social_account');
        foreach($u_social_account as $sa_key=>$sa){
            $profile_counter += ( strlen($bl[$sa_key])>0 ? 1 : 0 );
        }

        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $required_social_profiles = 3;
        $e_status = ( $profile_counter>=$required_social_profiles ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
        $progress_gained += ( $e_status==1 ? $estimated_minutes : ($profile_counter/$required_social_profiles)*$estimated_minutes );
        array_push( $checklist , array(
            'href' => ( $account_href ? $account_href.'#communication' : null ),
            'anchor' => '<b>Set '.$required_social_profiles.' or more Social Profiles</b> in '.$account_anchor,
            'e_status' => $e_status,
            'time_min' => $estimated_minutes,
        ));


        //u_paypal_email
        $estimated_minutes = 15;
        $progress_possible += $estimated_minutes;
        $e_status = ( strlen($bl['u_paypal_email'])>0 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
        $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => ( $account_href ? $account_href.'#details' : null ),
            'anchor' => '<b>Set Paypal Email for Payouts</b> in '.$account_anchor,
            'e_status' => $e_status,
            'time_min' => $estimated_minutes,
        ));


        //u_terms_agreement_time
        if(in_array($bl['u_inbound_u_id'],array(1308,1280,1281))){
            $estimated_minutes = 45;
            $progress_possible += $estimated_minutes;
            $e_status = ( strlen($bl['u_terms_agreement_time'])>0 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
            $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
            array_push( $checklist , array(
                'href' => ( $account_href ? $account_href.'#details' : null ),
                'anchor' => '<b>Check Instructor Agreement</b> in '.$account_anchor,
                'e_status' => $e_status,
                'time_min' => $estimated_minutes,
            ));
        }
    }



    
    /* *****************************
     *  Settings
     *******************************/

    if(!$b['b_is_parent']){
        if($b['b_p2_max_seats']>0){
            $estimated_minutes = 15;
            $progress_possible += $estimated_minutes;
            $e_status = ( strlen($b['b_support_email'])>=1 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
            $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
            array_push( $checklist , array(
                'href' => '/console/'.$b['b_id'].'/settings#support',
                'anchor' => '<b>Enter Support Email Address</b> in Settings',
                'e_status' => $e_status,
                'time_min' => $estimated_minutes,
            ));
        }


        //Offer Tutoring?
        if($b['b_p3_rate']>0){
            $estimated_minutes = 15;
            $progress_possible += $estimated_minutes;
            $e_status = ( strlen($b['b_calendly_url'])>=1 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
            $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
            array_push( $checklist , array(
                'href' => '/console/'.$b['b_id'].'/settings#support',
                'anchor' => '<b>Enter Calendly URL</b> for Tutoring Bookings in Settings',
                'e_status' => $e_status,
                'time_min' => $estimated_minutes,
            ));
        }
    }



    //Landing Page Category
    $current_inbounds = $CI->Db_model->cr_inbound_fetch(array(
        'cr.cr_outbound_c_id' => $b['b_outbound_c_id'],
        'cr.cr_status' => 1,
    ));
    $estimated_minutes = 15;
    $progress_possible += $estimated_minutes;
    $e_status = ( count($current_inbounds)>0 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
    $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/settings#landingpage',
        'anchor' => '<b>Choose Category</b> in Settings',
        'e_status' => $e_status,
        'time_min' => $estimated_minutes,
    ));


    // Required Experience Level
    $estimated_minutes = 15;
    $progress_possible += $estimated_minutes;
    $e_status = ( $b['b_difficulty_level']>0 ? 1 /*Verified*/ : -4 /*Pending Completion*/ );
    $progress_gained += ( $e_status==1 ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/settings#landingpage',
        'anchor' => '<b>Choose Required Experience Level</b> in Settings',
        'e_status' => $e_status,
        'time_min' => $estimated_minutes,
    ));




    
    
    //Return the final message:
    return array(
        'stage' => '<i class="fas fa-rocket"></i> Launch Checklist',
        'progress' => round($progress_gained/$progress_possible*100),
        'check_list' => $checklist,
    );
}


function tree_menu($c,$current_c_ids,$format='list',$level=1){

    $CI =& get_instance();
    $ui = null;

    if(!is_array($c) && intval($c)>0){
        $cs = $CI->Db_model->c_fetch(array(
            'c_id' => $c,
        ));
        $c = $cs[0];
    }

    //Fetch children:
    $c_child = $CI->Db_model->cr_outbound_fetch(array(
        'cr.cr_inbound_c_id' => $c['c_id'],
        'cr.cr_status >' => 0,
        'c.c_status >' => 0, //Use status to control menu item visibility
    ));


    if($level==1){
        if($format=='list'){
            $ui .= '<div class="list-group">';
        }
    }



    if($format=='list'){

        //Show the item:
        $ui .= '<a href="/'.$c['c_id'].'" class="list-group-item '.( in_array($c['c_id'],$current_c_ids) ? 'active' :'').'" style="'.($level==3 ? 'padding-left:20px;' : '').'; text-decoration:none;">';
        $ui .= '<span class="pull-right">';
        $ui .= '<span class="badge badge-primary">'.count($c_child).' <i class="fas fa-chevron-right"></i></span>';
        $ui .= '</span>';
        $ui .= '<span style="font-weight:'.($level<=2 ? 'bold' :'normal').';">'.$c['c_outcome'].'</span>';
        $ui .= '</a>';

    } elseif($format=='select' && $level<=2){

        $ui .= '<select data-c-id="'.$c['c_id'].'" id="c_s_'.$c['c_id'].'" class="border c_select level'.$level.' '.( isset($c['cr_outbound_c_id']) ? 'outbound_c_'.$c['cr_outbound_c_id'] : '' ).' '.( $level==2 ? 'hidden' : '' ).'" style="width:100%; margin-bottom:10px; max-width:380px;">';
        //$ui .= '<option value="0">Choose...</option>'; //Not needed for now as we transition to single level categories
        foreach($c_child as $child_intent){
            $ui .= '<option value="'.$child_intent['c_id'].'" '.( in_array($child_intent['c_id'],$current_c_ids) ?'selected="selected"':'').'>'.$child_intent['c_outcome'].'</option>';
        }
        $ui .= '</select>';

    }

    //Which level?
    if($level<=2){
        //Loop through children
        foreach($c_child as $child_intent){
            $ui .= tree_menu($child_intent,$current_c_ids,$format,($level+1));
        }
    }


    if($level==1){
        if($format=='list'){
            $ui .= '</div>';
        }
    }

    return $ui;
}




function is_valid_intent($c_id){
    $CI =& get_instance();
    $intents = $CI->Db_model->c_fetch(array(
        'c.c_id' => intval($c_id),
        'c.c_status >=' => 0, //Drafting or higher
    ));
    return (count($intents)==1);
}

function filter($array,$ikey,$ivalue){
	if(!is_array($array) || count($array)<=0){
		return null;
	}
	foreach($array as $key=>$value){
		if(isset($value[$ikey]) && $value[$ikey]==$ivalue){
			return $array[$key];
		}
	}
	return null;
}


function auth($entity_groups=null,$force_redirect=0,$b_id=0){
	
	$CI =& get_instance();
	$udata = $CI->session->userdata('user');
	
	//Let's start checking various ways we can give user access:
	if(!$entity_groups && !$b_id && is_array($udata) && count($udata)>0){
	    
	    //No minimum level required, grant access IF logged in:
	    return $udata;
	    
	} elseif(isset($udata['u_inbound_u_id']) && $udata['u_inbound_u_id']==1281){
	    
	    //Always grant access to Admins:
	    return $udata;
	    
	} elseif(isset($udata['u_id']) && $b_id){
	    
	    //Fetch Bootcamp admins and see if they have access to this:
	    $b_instructors = $CI->Db_model->ba_fetch(array(
	        'ba.ba_b_id' => $b_id,
	        'ba.ba_status >=' => 1, //Actively assigned team member
	        'u.u_status' => 1, //Active entity
	        'u.u_id' => $udata['u_id'],
	    ));
	    
	    if(count($b_instructors)>0){
	        //Append permissions here:
	        $udata['project_permissions'] = $b_instructors[0];
	        //Instructor is part of the Bootcamp:
	        return $udata;
	    }
	    
	} elseif(isset($udata['u_id']) && in_array($udata['u_inbound_u_id'],$entity_groups)){
	    
		//They meet the minimum level requirement:
	    return $udata;
	    
	}
	
	//Still here?!
	//We could not find a reason to give user access, so block them:
	if(!$force_redirect){
	    return false;
	} else {
	    //Block access:
	    redirect_message( ( isset($udata['u_id']) && (in_array($udata['u_inbound_u_id'], array(1280,1308,1281)) || isset($udata['project_permissions'])) ? '/console' : '/login?url='.urlencode($_SERVER['REQUEST_URI']) ),'<div class="alert alert-danger maxout" role="alert">'.( isset($udata['u_id']) ? 'Access not authorized.' : 'Session Expired. Login to continue.' ).'</div>');
	}
	
}

function redirect_message($url,$message=null, $response_code=null){

    //Do we have a Message?
    if($message){
        $CI =& get_instance();
        $CI->session->set_flashdata('hm', $message);
    }

    //What's the default response code?
    $response_code = ( !$response_code && !$message ? 301 : ( $response_code ? $response_code : null ) );
    if($response_code) {
        header("Location: ".$url, true, $response_code);
    } else {
        header("Location: ".$url, true);
    }
	die();
}

function remote_mime($file_url){
    //Fetch Remote:
    $ch = curl_init($file_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return $mime;
}

function save_file($file_url,$json_data,$is_local=false){
    $CI =& get_instance();
    
    $file_name = md5($file_url.'fileSavingSa!t').'.'.fetch_file_ext($file_url);
    
    if(!$is_local){
        //Save this remote file to local first:
        $file_path = 'application/cache/temp_files/';
        
        
        //Fetch Remote:
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $file_url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        
        //Write in directory:
        $fp = @fopen( $file_path.$file_name , 'w');
    }
    
    //Then upload to AWS S3:
    if(($is_local || (isset($fp) && $fp)) && @require_once( 'application/libraries/aws/aws-autoloader.php' )){
        
        if(isset($fp)){
            fwrite($fp, $result);
            fclose($fp);
        }
        
        $s3 = new Aws\S3\S3Client([
            'version' 		=> 'latest',
            'region'  		=> 'us-west-2',
            'credentials' 	=> $CI->config->item('aws_credentials'),
        ]);
        $result = $s3->putObject(array(
            'Bucket'       => 's3foundation', //Same bucket for now
            'Key'          => $file_name,
            'SourceFile'   => ( $is_local ? $file_url : $file_path.$file_name ),
            'ACL'          => 'public-read'
        ));
        
        if(isset($result['ObjectURL']) && strlen($result['ObjectURL'])>10){
            @unlink(( $is_local ? $file_url : $file_path.$file_name ));
            return $result['ObjectURL'];
        } else {
            $CI->Db_model->e_create(array(
                'e_text_value' => 'save_file() Unable to upload file ['.$file_url.'] to Mench cloud.',
                'e_json' => $json_data,
                'e_inbound_c_id' => 8, //Platform Error
            ));
            return false;
        }
        
    } else {
        //Probably local, ignore this!
        return false;
    }
}

function readable_updates($before,$after,$remove_prefix){
    $message = null;
    foreach($after as $key=>$after_value){
        if(isset($before[$key]) && !($before[$key]==$after_value)){
            //Change detected!
            if($message){
                $message .= "\n";
            }
            $message .= '- Updated '.ucwords(str_replace('_',' ',str_replace($remove_prefix,'',$key))).' from ['.strip_tags($before[$key]).'] to ['.strip_tags($after_value).']';
        }
    }
    
    if(!$message){
        //No changes detected!
        $message = 'Nothing updated!';
    }
    
    return $message;
}

function fb_time($unix_time){
	//It has milliseconds like "1458668856253", which we need to tranform for DB insertion:
	return date("Y-m-d H:i:s",round($unix_time/1000));
}


function curl_html($url,$return_breakdown=false){

    //Validate URL:
    if(!filter_var($url, FILTER_VALIDATE_URL)){
        return false;
    }

	$ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
    curl_setopt($ch, CURLOPT_REFERER, "https://www.mench.com");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_POST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8); //If site takes longer than this to connect, we have an issue!

    if(is_dev()){
	    //SSL does not work on my local PC.
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	}
    $response = curl_exec($ch);

	if($return_breakdown){

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $clean_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $effective_url = ( strlen($clean_url)<1 || $clean_url==$url ? $url : $clean_url );

        $url_parts = parse_url($effective_url);
        $body_html = substr($response, $header_size);
        $content_type = one_two_explode('',';',curl_getinfo($ch, CURLINFO_CONTENT_TYPE));

        $embed_code = echo_embed($effective_url, $effective_url, false, true);
        $clean_url = ( $embed_code['status'] && !($clean_url==$embed_code['clean_url']) ? $embed_code['clean_url'] : $clean_url );

        // Now see if this is a specific file type:
        // Audio File URL: https://s3foundation.s3-us-west-2.amazonaws.com/672b41ff20fece4b3e7ae2cf4b58389f.mp3
        // Video File URL: https://s3foundation.s3-us-west-2.amazonaws.com/8c5a1cc4e8558f422a4003d126502db9.mp4
        // Image File URL: https://s3foundation.s3-us-west-2.amazonaws.com/d673c17d7164817025a000416da3be3f.png
        // Reglr File URL: https://s3foundation.s3-us-west-2.amazonaws.com/611695da5d0d199e2d95dd2eabe484cf.zip

        if(substr_count($content_type,'application/')==1){
            $x_type = 5;
        } elseif(substr_count($content_type,'image/')==1){
            $x_type = 4;
        } elseif(substr_count($content_type,'audio/')==1){
            $x_type = 3;
        } elseif(substr_count($content_type,'video/')==1){
            $x_type = 2;
        } elseif($embed_code['status']){
            //Embed enabled URL:
            $x_type = 1;
        } else {
            //Generic URL:
            $x_type = 0;
        }

        $return_array = array(
            //used all the time, also when updating en entity:
            'input_url' => $url,
            'url_is_broken' => ( in_array($httpcode,array(0,403,404)) ? 1 : 0 ),
            'x_type' => $x_type,
            'clean_url' => ( !$clean_url || $clean_url==$url ? null : $clean_url ),
            'last_domain' => strtolower(str_replace('www.','',$url_parts['host'])),
            'httpcode' => $httpcode,
            'page_title' => one_two_explode('>','',one_two_explode('<title','</title',$body_html)),
        );

        return $return_array;

    } else {
        //Simply return the response:
        return $response;
    }
}

function boost_power(){
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
}


function objectToArray( $object ) {
	if( !is_object( $object ) && !is_array( $object ) ) {
		return $object;
	}
	if( is_object( $object ) ) {
		$object = (array) $object;
	}
	return array_map( 'objectToArray', $object );
}


function arrayToObject($array){
	$obj = new stdClass;
	foreach($array as $k => $v) {
		if(strlen($k)) {
			if(is_array($v)) {
				$obj->{$k} = arrayToObject($v); //RECURSION
			} else {
				$obj->{$k} = $v;
			}
		}
	}
	return $obj;
}



function message_validation($i_status,$i_message,$i_media_type=null /*Only set for editing*/){

    if(in_array($i_media_type,array('video','image','audio','file'))){
        return array(
            'status' => 1,
            'urls' => 'Media images cannot be edited',
        );
    }

    $CI =& get_instance();
    $message_max = $CI->config->item('message_max');
    $check_urls = (!$i_media_type || $i_media_type=='text');
    if($check_urls){
        $urls = extract_urls($i_message);
    }

    if(!isset($i_status) || !(intval($i_status)==$i_status)){
        return array(
            'status' => 0,
            'message' => 'Missing Status',
        );
    } elseif(!isset($i_message) || strlen($i_message)<=0){
        return array(
            'status' => 0,
            'message' => 'Missing Message',
        );
    } elseif($check_urls && count($urls)>1){
        return array(
            'status' => 0,
            'message' => 'Max 1 URL per Message',
        );
    } elseif(substr_count($i_message,'{first_name}')>1){
        return array(
            'status' => 0,
            'message' => '{first_name} can be used only once',
        );
    } elseif(strlen($i_message)>$message_max){
        return array(
            'status' => 0,
            'message' => 'Max is '.$message_max.' Characters',
        );
    } elseif($i_message!=strip_tags($i_message)){
        return array(
            'status' => 0,
            'message' => 'HTML Code is not allowed',
        );
    } elseif(!preg_match('//u', $i_message)){
        //Log engagement for this:
        return array(
            'status' => 0,
            'message' => 'Message must be UTF8',
        );
    } else {
        return array(
            'status' => 1,
            'urls' => ( $check_urls ? $urls : null ),
        );
    }
}





function generate_hashtag($text){
    //These hashtags cannot be taken
    $CI =& get_instance();
    $reserved_hashtags = $CI->config->item('reserved_hashtags');
    
    //Cleanup the text:
    $text = trim($text);
    $text = ucwords($text);
    $text = str_replace('&','And',$text);
    $text = preg_replace("/[^a-zA-Z0-9]/", "", $text);
    $text = substr($text,0,30);
    
    //Now check to make sure its all good!
    if(in_array(strtolower($text),$reserved_hashtags)){
        //Oops, they cannot pick this, lets add a random number to this!
        $text .= rand(1,9999);
    }
    
    return $text;    
}

function one_two_explode($one,$two,$content){
    if(strlen($one)>0){
        if(substr_count($content, $one)<1){
            return NULL;
        }
        $temp = explode($one,$content,2);
        if(strlen($two)>0){
            $temp = explode($two,$temp[1],2);
            return trim($temp[0]);
        } else {
            return trim($temp[1]);
        }
    } else {
        $temp = explode($two,$content,2);
        return trim($temp[0]);
    }
}


function format_e_text_value($e_text_value){
    
    //Do replacements:
    if(substr_count($e_text_value,'/attach ')>0){
        $attachments = explode('/attach ',$e_text_value);
        foreach($attachments as $key=>$attachment){
            if($key==0){
                //We're gonna start buiolding this message from scrach:
                $e_text_value = $attachment;
                continue;
            }
            $segments = explode(':',$attachment,2);
            $sub_segments = preg_split('/[\s]+/', $segments[1] );

            if($segments[0]=='image'){
                $e_text_value .= '<img src="'.$sub_segments[0].'" style="max-width:100%" />';
            } elseif($segments[0]=='audio'){
                $e_text_value .= '<audio controls><source src="'.$sub_segments[0].'" type="audio/mpeg"></audio>';
            } elseif($segments[0]=='video'){
                $e_text_value .= '<video width="100%" onclick="this.play()" controls><source src="'.$sub_segments[0].'" type="video/mp4"></video>';
            } elseif($segments[0]=='file'){
                $e_text_value .= '<a href="'.$sub_segments[0].'" class="btn btn-primary" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';
            }
            
            //Do we have any leftovers after the URL? If so, append:
            if(isset($sub_segments[1])){
                $e_text_value = ' '.$sub_segments[1];
            }
        }
    } else {
        $e_text_value = echo_link($e_text_value);
    }
    $e_text_value = nl2br($e_text_value);
    return $e_text_value;
}


function bigintval($value) {
    $value = trim($value);
    if (ctype_digit($value)) {
        return $value;
    }
    $value = preg_replace("/[^0-9](.*)$/", '', $value);
    if (ctype_digit($value)) {
        return $value;
    }
    return 0;
}


































