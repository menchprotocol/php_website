<?php

function is_dev(){
    return ( isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='local.mench.co' );
}

function lock_cron_for_processing($e_items){
    $CI =& get_instance();
    foreach($e_items as $e){
        if($e['e_id']>0 && $e['e_status']==0){
            $CI->Db_model->e_update( $e['e_id'] , array(
                'e_status' => 1, //Working on...
            ));
        }
    }
}

function sortByScore($a, $b) {
    return intval($b['u__e_score']) - intval($a['u__e_score']);
}

function u_essentials($full_array){
    $return_array = array();
    foreach(array('u_id','u_full_name','u_intro_message','u__e_score','x_url') as $key){
        if(isset($full_array[$key])){
            $return_array[$key] = $full_array[$key];
        }
    }
    return $return_array;
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
                'e_value' => 'Missing required field ['.$req_field.'] for inserting new DB row',
                'e_json' => array(
                    'insert_columns' => $insert_columns,
                    'required_fields' => $field_array,
                ),
                'e_parent_c_id' => 8, //Platform Error
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
    ), array('u__children_count','u__urls'));

    if(count($entities)<1){
        return redirect_message('/entities','<div class="alert alert-danger" role="alert">Invalid Entity ID</div>');
    }

    $view_data = array(
        'parent_u_id' => $u_id,
        'entity' => $entities[0],
        'title' => ( $is_edit ? 'Modify ' : '' ).$entities[0]['u_full_name'],
    );

    return $view_data;
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
        'c.c_status >=' => 0,
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
        foreach($entity['u__parents'] as $u_id=>$u_i){
            if(in_array($u_id, array(1278,1326,2750))){
                $entity_type = $u_id;
                break;
            }
        }
    }
    return $entity_type;
}

function clean_title($title){
    $common_end_exploders = array('-','|');
    foreach($common_end_exploders as $keyword){
        if(substr_count($title,$keyword)>0){
            $parts = explode($keyword, $title);
            $last_peace = $parts[(count($parts)-1)];

            //Should we remove the last part if not too long?
            if(substr($last_peace, 0,1)==' ' && strlen($last_peace)<16){
                $title = str_replace($keyword.$last_peace,'',$title);
                break; //Only a single extension, so break the loop
            }
        }
    }
    return trim($title);
}

function auth($entity_groups=null,$force_redirect=0){
	
	$CI =& get_instance();
	$udata = $CI->session->userdata('user');
	
	//Let's start checking various ways we can give user access:
	if(!$entity_groups && is_array($udata) && count($udata)>0){
	    
	    //No minimum level required, grant access IF logged in:
	    return $udata;

    } elseif(isset($udata['u__parents']) && array_key_exists(1281, $udata['u__parents'])){

        //Always grant access to Admins:
        return $udata;
	    
	} elseif(isset($udata['u_id']) && array_any_key_exists($entity_groups,$udata['u__parents'])){
	    
		//They are part of one of the levels assigned to them:
	    return $udata;
	    
	}
	
	//Still here?!
	//We could not find a reason to give user access, so block them:
	if(!$force_redirect){
	    return false;
	} else {
	    //Block access:
	    redirect_message( ( isset($udata['u_id']) && ( array_any_key_exists(array(1308,1281),$udata['u__parents']) || isset($udata['project_permissions'])) ? '/intents/'.$this->config->item('primary_c') : '/login?url='.urlencode($_SERVER['REQUEST_URI']) ),'<div class="alert alert-danger maxout" role="alert">'.( isset($udata['u_id']) ? 'Access not authorized.' : 'Session Expired. Login to continue.' ).'</div>');
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
                'e_value' => 'save_file() Unable to upload file ['.$file_url.'] to Mench cloud.',
                'e_json' => $json_data,
                'e_parent_c_id' => 8, //Platform Error
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
            'page_title' => clean_title(one_two_explode('>','',one_two_explode('<title','</title',$body_html))),
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

function message_validation($i_status,$i_message,$i_c_id){


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
        $i_parent_cs = $CI->Db_model->c_fetch(array(
            'c.c_id' => $c_ids[0],
        ));

        $i_cs = $CI->Db_model->c_fetch(array(
            'c.c_id' => $i_c_id,
        ), 0, array('c__parents'));

        if(count($i_cs)==0){
            //Invalid ID:
            return array(
                'status' => 0,
                'message' => 'Parent Intent #'.$c_ids[0].' does not exist',
            );
        } elseif(count($i_parent_cs)==0){
            //Invalid ID:
            return array(
                'status' => 0,
                'message' => 'Intent #'.$c_ids[0].' does not exist',
            );
        } elseif($c_ids[0]==$i_c_id){
            return array(
                'status' => 0,
                'message' => 'You cannot affirm the message intent itself. Choose another intent to continue',
            );
        } elseif($i_parent_cs[0]['c_status']<0){
            //Inactive:
            return array(
                'status' => 0,
                'message' => 'Intent ['.$i_parent_cs[0]['c_outcome'].'] is not active so you cannot link to it',
            );
        }

        $parent_found = false;
        foreach ($i_cs[0]['c__parents'] as $c){
            if($c['c_id']==$c_ids[0]){
                $parent_found = true;
                break;
            }
        }

        if(!$parent_found){
            //Inactive:
            return array(
                'status' => 0,
                'message' => 'Intent ['.$i_cs[0]['c_outcome'].'] is not associated with ['.$i_parent_cs[0]['c_outcome'].'] so it cannot be used to affirm it. First add it as a parent and then try affirming it.',
            );
        }
    }



    //Validate Entity:
    if(count($u_ids)>0){

        $i_children_us = $CI->Db_model->u_fetch(array(
            'u_id' => $u_ids[0],
        ), array('skip_u__parents','u__urls'));

        if(count($i_children_us)==0){
            //Invalid ID:
            return array(
                'status' => 0,
                'message' => 'Entity [@'.$u_ids[0].'] does not exist',
            );
        } elseif($i_children_us[0]['u_status']<0){
            //Inactive:
            return array(
                'status' => 0,
                'message' => 'Entity ['.$i_children_us[0]['u_full_name'].'] is not active so you cannot link to it',
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
        foreach($i_children_us[0]['u__urls'] as $x){
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
        'i_u_id' => ( count($u_ids)>0 ? $u_ids[0] : 0 ), //Referencing an entity?
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


function format_e_value($e_value){
    
    //Do replacements:
    if(substr_count($e_value,'/attach ')>0){
        $attachments = explode('/attach ',$e_value);
        foreach($attachments as $key=>$attachment){
            if($key==0){
                //We're gonna start buiolding this message from scrach:
                $e_value = $attachment;
                continue;
            }
            $segments = explode(':',$attachment,2);
            $sub_segments = preg_split('/[\s]+/', $segments[1] );

            if($segments[0]=='image'){
                $e_value .= '<img src="'.$sub_segments[0].'" style="max-width:100%" />';
            } elseif($segments[0]=='audio'){
                $e_value .= '<audio controls><source src="'.$sub_segments[0].'" type="audio/mpeg"></audio>';
            } elseif($segments[0]=='video'){
                $e_value .= '<video width="100%" onclick="this.play()" controls><source src="'.$sub_segments[0].'" type="video/mp4"></video>';
            } elseif($segments[0]=='file'){
                $e_value .= '<a href="'.$sub_segments[0].'" class="btn btn-primary" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';
            }
            
            //Do we have any leftovers after the URL? If so, append:
            if(isset($sub_segments[1])){
                $e_value = ' '.$sub_segments[1];
            }
        }
    } else {
        $e_value = echo_link($e_value);
    }
    $e_value = nl2br($e_value);
    return $e_value;
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


































