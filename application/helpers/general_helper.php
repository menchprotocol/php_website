<?php

function is_dev(){
    return ( isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='local.mench.co' );
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

function load_php_algolia($index_name){
    require_once('application/libraries/algoliasearch.php');
    $client = new \AlgoliaSearch\Client("49OCX1ZXLJ", "84a8df1fecf21978299e31c5b535ebeb");
    return $client->initIndex($index_name);
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


function fetch_entity_tree($u_id,$is_edit=false){

    $CI =& get_instance();
    $entities = $CI->Db_model->u_fetch(array(
        'u_id' => $u_id,
    ), array('u__outbound_count','u__urls'));

    if(count($entities)<1){
        return redirect_message('/entities','<div class="alert alert-danger" role="alert">Invalid Entity ID</div>');
    }

    $view_data = array(
        'inbound_u_id' => $u_id,
        'entity' => $entities[0],
        'breadcrumb' => array(),
        'title' => ( $is_edit ? 'Modify ' : '' ).$entities[0]['u_full_name'],
    );

    //Push this item to breadcrumb:
    if($is_edit){
        array_push( $view_data['breadcrumb'] , array(
            'link' => '/entities/'.$u_id,
            'anchor' => $view_data['entity']['u_full_name'],
        ));
        array_push( $view_data['breadcrumb'] , array(
            'link' => null,
            'anchor' => '<i class="fas fa-cog"></i> Modify',
        ));
    }

    return $view_data;
}



function fetch_action_plan_copy($b_id,$r_id=0,$current_b=null,$release_cache=array()){
    //TODO rewrite
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



function detect_active_enrollment($enrollments){

    //Determines the active enrollment of a student, especially useful if they have multiple enrollments
    if(count($enrollments)<1){

        return false;

    } elseif(count($enrollments)>1){

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

        $active_enrollment = null;

        foreach($enrollments as $enrollment){

            //Now see whatssup:
            if($enrollment['ru_status']>4 || $enrollment['r_status']>2){

                //This is a completed Class:
                $active_enrollment = $enrollment;

            } elseif($enrollment['ru_status']==4 && $enrollment['r_status']<2){

                //Class is not started yet:
                $active_enrollment = $enrollment;

            } elseif($enrollment['ru_status']==4 && $enrollment['r_status']==2){

                //Active class has highest priority, break after:
                $active_enrollment = $enrollment;
                break; //This is what we care about the most, so make it have the last say

            } elseif(!$active_enrollment){

                //Not sure what this could be:
                $active_enrollment = $enrollment;

            }
        }

        return $active_enrollment;

    } elseif(count($enrollments)==1){

        //This is typical, treat this as their Active Subscription since its the only one they got:
        return $enrollments[0];

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
        'c_id' => $c_id, //To be deprecated at some point...
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
                'anchor' => '<i class="fas fa-cube"></i> '.$b['c_outcome'],
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

            if($intent['c_status']<0){
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
                        'anchor' => '<i class="fas fa-cube"></i> '.$b['c_outcome'],
                    ),
                    array(
                        'link' => null,
                        'anchor' => $intent['c_outcome'],
                    ),
                );


                //Find the next intent:
                $next_intent = null;
                $next_level = 0;
                $next_key = $intent_key;

                while(!$next_intent){

                    $next_key++;

                    if(!isset($b['c__child_intents'][$next_key]['c_id'])){

                        //Next Task does not exist, return Bootcamp:
                        $next_intent = $b;
                        $next_level = 1;
                        break;

                    } elseif($b['c__child_intents'][$next_key]['c_status']>0){

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

                    if($step['c_status']<0){
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
                                'anchor' => $b['c_outcome'],
                            ),
                            array(
                                'link' => '/my/actionplan/'.$b['b_id'].'/'.$intent['c_id'],
                                'anchor' => $intent['c_outcome'],
                            ),
                            array(
                                'link' => null,
                                'anchor' => $step['c_outcome'],
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




function array_any_key_exists(array $keys, array $arr) {
    foreach($keys as $key){
        if(array_key_exists($key,$arr)){
            return true;
        }
    }
    return false;
}



function is_valid_intent($c_id){
    $CI =& get_instance();
    $intents = $CI->Db_model->c_fetch(array(
        'c.c_id' => intval($c_id),
        'c.c_status >' => 0, //Drafting or higher
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


function entity_type($entity){
    $entity_type = 0;
    if(in_array($entity['u_id'], array(1278,1326,2750))){
        $entity_type = $entity['u_id'];
    } else {
        foreach($entity['u__inbounds'] as $u_id=>$u_i){
            if(in_array($u_id, array(1278,1326,2750))){
                $entity_type = $u_id;
                break;
            }
        }
    }
    return $entity_type;
}


function auth($entity_groups=null,$force_redirect=0,$b_id=0,$u_id=0){
	
	$CI =& get_instance();
	$udata = $CI->session->userdata('user');
	
	//Let's start checking various ways we can give user access:
	if(!$entity_groups && !$b_id && is_array($udata) && count($udata)>0){
	    
	    //No minimum level required, grant access IF logged in:
	    return $udata;

    } elseif(isset($udata['u__inbounds']) && array_key_exists(1281, $udata['u__inbounds'])){

        //Always grant access to Admins:
        return $udata;

    } elseif($u_id>0 && $udata['u_id']==$u_id){

        //Always grant access to the user variable:
        return $udata;
	    
	} elseif(isset($udata['u_id']) && array_any_key_exists($entity_groups,$udata['u__inbounds'])){
	    
		//They are part of one of the levels assigned to them:
	    return $udata;
	    
	}
	
	//Still here?!
	//We could not find a reason to give user access, so block them:
	if(!$force_redirect){
	    return false;
	} else {
	    //Block access:
	    redirect_message( ( isset($udata['u_id']) && ( array_any_key_exists(array(1308,1281),$udata['u__inbounds']) || isset($udata['project_permissions'])) ? '/intents/6623' : '/login?url='.urlencode($_SERVER['REQUEST_URI']) ),'<div class="alert alert-danger maxout" role="alert">'.( isset($udata['u_id']) ? 'Access not authorized.' : 'Session Expired. Login to continue.' ).'</div>');
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

        $embed_code = echo_embed($effective_url, $effective_url, true);
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
            'url_is_broken' => ( in_array($httpcode,array(0,403,404)) && substr_count($url,'www.facebook.com')==0 ? 1 : 0 ),
            'x_type' => $x_type,
            'clean_url' => ( !$clean_url || $clean_url==$url ? null : $clean_url ),
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

function extract_references($prefix,$message){
    //$words = explode(' ',trim($message));
    $words = preg_split('/[\s]+/', trim($message) );
    $matches = array();
    foreach ($words as $word){
        if(substr($word,0,1)==$prefix){
            //Looks like it, is the rest all integers?
            $id = substr($word,1);
            if(strlen($id)==strlen(intval($id))){
                //Yea seems like all integers, append:
                array_push($matches,intval($id));
            }
        }
    }
    return $matches;
}

function message_validation($i_status,$i_message,$i_outbound_c_id){


    $CI =& get_instance();
    $message_max = $CI->config->item('message_max');

    //Extract details from this message:
    $urls = extract_urls($i_message);
    $u_ids = extract_references('@',$i_message);
    $c_ids = extract_references('#',$i_message);


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
    } elseif(substr_count($i_message,'/firstname')>1){
        return array(
            'status' => 0,
            'message' => '/firstname can be used only once',
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
        return array(
            'status' => 0,
            'message' => 'Message must be UTF8',
        );
    } elseif(count($u_ids)>1){
        return array(
            'status' => 0,
            'message' => 'You can reference a maximum of 1 entity per message',
        );
    } elseif(count($u_ids)>0 && count($urls)>0){
        return array(
            'status' => 0,
            'message' => 'You can either reference 1 entity or include 1 URL which would transform into an entity',
        );
    } elseif(count($urls)>1){
        return array(
            'status' => 0,
            'message' => 'Max 1 URL per Message',
        );
    } elseif((count($u_ids)==0 && count($urls)==0) && count($c_ids)>0){
        return array(
            'status' => 0,
            'message' => 'You must reference an entity before being able to affirm an intent',
        );
    } elseif((count($u_ids)==0 && count($urls)==0) && substr_count($i_message,'/slice')>0){
        return array(
            'status' => 0,
            'message' => '/slice command required an entity reference [@'.count($u_ids).']',
        );
    } elseif(count($c_ids)>1){
        return array(
            'status' => 0,
            'message' => 'You can reference a maximum of 1 intent per message',
        );
    }




    //Validate Intent:
    if(count($c_ids)>0){

        //Validate this:
        $i_inbound_cs = $CI->Db_model->c_fetch(array(
            'c.c_id' => $c_ids[0],
        ));

        $i_cs = $CI->Db_model->c_fetch(array(
            'c.c_id' => $i_outbound_c_id,
        ), 0, array('c__inbounds'));

        if(count($i_cs)==0){
            //Invalid ID:
            return array(
                'status' => 0,
                'message' => 'Parent Intent #'.$c_ids[0].' does not exist',
            );
        } elseif(count($i_inbound_cs)==0){
            //Invalid ID:
            return array(
                'status' => 0,
                'message' => 'Intent #'.$c_ids[0].' does not exist',
            );
        } elseif($c_ids[0]==$i_outbound_c_id){
            return array(
                'status' => 0,
                'message' => 'You cannot affirm the message intent itself. Choose another intent to continue',
            );
        } elseif($i_inbound_cs[0]['c_status']<1){
            //Inactive:
            return array(
                'status' => 0,
                'message' => 'Intent ['.$i_inbound_cs[0]['c_outcome'].'] is not active so you cannot link to it',
            );
        }

        $parent_found = false;
        foreach ($i_cs[0]['c__inbounds'] as $c){
            if($c['c_id']==$c_ids[0]){
                $parent_found = true;
                break;
            }
        }

        if(!$parent_found){
            //Inactive:
            return array(
                'status' => 0,
                'message' => 'Intent ['.$i_cs[0]['c_outcome'].'] is not associated with ['.$i_inbound_cs[0]['c_outcome'].'] so it cannot be used to affirm it. First add it as an inbound and then try affirming it.',
            );
        }
    }



    //Validate Entity:
    if(count($u_ids)>0){

        $i_outbound_us = $CI->Db_model->u_fetch(array(
            'u_id' => $u_ids[0],
        ), array('skip_u__inbounds','u__urls'));

        if(count($i_outbound_us)==0){
            //Invalid ID:
            return array(
                'status' => 0,
                'message' => 'Entity [@'.$u_ids[0].'] does not exist',
            );
        } elseif($i_outbound_us[0]['u_status']<=0){
            //Inactive:
            return array(
                'status' => 0,
                'message' => 'Entity ['.$i_outbound_us[0]['u_full_name'].'] is not active so you cannot link to it',
            );
        }

    } elseif(count($urls)>0){

        //No entity linked, but we have a URL that we should turn into an entity:
        $url_create = $CI->Db_model->x_sync($urls[0], 1326, false, true);

        //Did we have an error?
        if(!$url_create['status']){
            return $url_create;
        }

        $u_ids[0] = $url_create['u']['u_id'];

        //Replace the URL with this new @entity in message:
        $i_message = str_replace($urls[0],'@'.$u_ids[0], $i_message);

    }

    //Do we have any commands?
    if(substr_count($i_message,'/slice')>0){

        //Validate the format of this command:
        $slice_times = explode(':',one_two_explode('/slice:',' ',$i_message),2);
        if(intval($slice_times[0])<1 || intval($slice_times[1])<1 || strlen($slice_times[0])!=strlen(intval($slice_times[0])) || strlen($slice_times[1])!=strlen(intval($slice_times[1]))){
            //Not valid format!
            return array(
                'status' => 0,
                'message' => 'Invalid format for /slice command. For example, to slice first 60 seconds use: /slice:0:60',
            );
        } elseif((intval($slice_times[0])+3)>intval($slice_times[1])){
            //Not valid format!
            return array(
                'status' => 0,
                'message' => 'Sliced clip must be at-least 3 seconds long',
            );
        }

        //Ensure entity has a sliceable content
        //
        //currently supporting: YouTube Only! See error message below...
        //
        $found_slicable_url = false;
        foreach($i_outbound_us[0]['u__urls'] as $x){
            if($x['x_type']==1 && substr_count($x['x_url'],'youtube.com')>0){
                $found_slicable_url = true;
                break;
            }
        }
        if(!$found_slicable_url){
            return array(
                'status' => 0,
                'message' => 'The /slice command requires the entity to have a YouTube URL',
            );
        }

    }


    return array(
        'status' => 1,
        'message' => 'Success',
        //Return cleaned data:
        'i_message' => trim($i_message), //It might have been modified if URL was added
        'i_outbound_u_id' => ( count($u_ids)>0 ? $u_ids[0] : 0 ), //Referencing an entity?
        'i_inbound_c_id' => ( count($c_ids)>0 ? $c_ids[0] : 0 ), //Affirming an intent relation?
    );
}





function generate_hashtag($text){
    //These hashtags cannot be taken
    $CI =& get_instance();

    //Cleanup the text:
    $text = trim($text);
    $text = ucwords($text);
    $text = str_replace('&','And',$text);
    $text = preg_replace("/[^a-zA-Z0-9]/", "", $text);
    $text = substr($text,0,30);
    
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


































