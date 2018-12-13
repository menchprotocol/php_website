<?php

function is_dev()
{
    return (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'local.mench.co');
}

function includes_any($string, $items)
{
    foreach ($items as $item) {
        if (substr_count($string, $items) > 0) {
            return true;
        }
    }
    return false;
}

function sortByScore($a, $b)
{
    return intval($b['en_trust_score']) - intval($a['en_trust_score']);
}

function u_essentials($full_array)
{
    $return_array = array();
    foreach (array('en_id', 'en_name', 'en_trust_score', 'x_url') as $key) {
        if (isset($full_array[$key])) {
            $return_array[$key] = $full_array[$key];
        }
    }
    return $return_array;
}

function load_php_algolia($index_name)
{
    $CI =& get_instance();
    if($CI->config->item('enable_algolia')){
        require_once('application/libraries/algoliasearch.php');
        $client = new \AlgoliaSearch\Client("49OCX1ZXLJ", "84a8df1fecf21978299e31c5b535ebeb");
        return $client->initIndex($index_name);
    }
}

function detect_missing_columns($insert_columns, $required_columns)
{
    foreach ($required_columns as $req_field) {
        if (!isset($insert_columns[$req_field]) || strlen($insert_columns[$req_field]) == 0) {
            //Ooops, we're missing this required field:
            $CI =& get_instance();
            $CI->Database_model->tr_create(array(
                'tr_content' => 'Missing required field [' . $req_field . '] for inserting new DB row',
                'tr_metadata' => array(
                    'insert_columns' => $insert_columns,
                    'required_columns' => $required_columns,
                ),
                'tr_en_type_id' => 4246, //Platform Error
            ));

            return true; //We have an issue
        }
    }

    //No errors found, all good:
    return false; //Not missing anything
}


//TODO Remove after migration:
function migrate_submissions($c_require_notes_to_complete, $c_require_url_to_complete)
{

}


function fetch_entity_tree($en_id, $is_edit = false)
{

    $CI =& get_instance();
    $entities = $CI->Database_model->en_fetch(array(
        'en_id' => $en_id,
    ), array('in__children_count', 'u__urls'));

    if (count($entities) < 1) {
        return redirect_message('/entities', '<div class="alert alert-danger" role="alert">Invalid Entity ID</div>');
    }

    $view_data = array(
        'parent_en_id' => $en_id,
        'entity' => $entities[0],
        'title' => ($is_edit ? 'Modify ' : '') . $entities[0]['en_name'],
    );

    return $view_data;
}

function join_keys($input_array, $joiner = ',')
{
    $joined_string = null;
    foreach ($input_array as $key => $value) {
        if ($joined_string) {
            $joined_string .= $joiner;
        }
        $joined_string .= $key;
    }
    return $joined_string;
}


function fetch_file_ext($url)
{
    //https://cdn.fbsbx.com/v/t59.3654-21/19359558_10158969505640587_4006997452564463616_n.aac/audioclip-1500335487327-1590.aac?oh=5344e3d423b14dee5efe93edd432d245&oe=596FEA95
    $url_parts = explode('?', $url, 2);
    $url_parts = explode('/', $url_parts[0]);
    $file_parts = explode('.', end($url_parts));
    return end($file_parts);
}


function parse_signed_request($signed_request)
{

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

function base64_url_decode($input)
{
    return base64_decode(strtr($input, '-_', '+/'));
}


function extract_urls($text, $inverse = false)
{
    $text = preg_replace('/[[:^print:]]/', ' ', $text); //Replace non-ascii characters with space
    $parts = preg_split('/\s+/', $text);
    $return = array();
    foreach ($parts as $part) {
        if (!$inverse && filter_var($part, FILTER_VALIDATE_URL)) {
            array_push($return, $part);
        } elseif ($inverse && !filter_var($part, FILTER_VALIDATE_URL) && strlen($part) > 0) {
            array_push($return, $part);
        }
    }
    return $return;
}


function mime_type($mime)
{
    if (strstr($mime, "video/")) {
        return 'video';
    } else if (strstr($mime, "image/")) {
        return 'image';
    } else if (strstr($mime, "audio/")) {
        return 'audio';
    } else {
        return 'file';
    }
}


function isDate($value)
{
    if (!$value) {
        return false;
    }

    try {
        new \DateTime($value);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

function detect_tr_en_type_id($string)
{

    /*
     * Detect what type of entity-to-entity URL type should we create
     * based on options listed in this tree: https://mench.com/entities/4227
     * */

    $string = trim($string);

    if(!$string || strlen($string)==0){
        //Naked:
        return 4230;
    } elseif(isDate($string)){
        //Date/time:
        return 4318;
    } elseif(is_int($string) || is_double($string)){
        //Number:
        return 4319;
    } elseif(filter_var($string, FILTER_VALIDATE_URL)){
        //It's a URL, see what type:
        $curl = curl_html($string, true);
        return $curl['tr_en_type_id'];
    } else {
        $words = explode(' ',$string);
        //Regular text link:
        return ( count($words)==1 ? 4526 /* Single word */ : 4255 /* Multi-word */ );
    }
}

function array_any_key_exists(array $keys, array $arr)
{
    foreach ($keys as $key) {
        if (array_key_exists($key, $arr)) {
            return true;
        }
    }
    return false;
}






function is_valid_intent($in_id)
{
    $CI =& get_instance();
    $intents = $CI->Database_model->in_fetch(array(
        'in_id' => intval($in_id),
        'in_status >=' => 0,
    ));
    return (count($intents) == 1);
}

function filter_array($array, $match_key, $match_value)
{

    if (!is_array($array) || count($array) < 1) {
        return false;
    }
    foreach ($array as $key => $value) {
        if (isset($value[$match_key]) && $value[$match_key] == $match_value) {
            return $array[$key];
        }
    }
    //Could not find it!
    return false;
}

function clean_title($title)
{
    $common_end_exploders = array('-', '|');
    foreach ($common_end_exploders as $keyword) {
        if (substr_count($title, $keyword) > 0) {
            $parts = explode($keyword, $title);
            $last_peace = $parts[(count($parts) - 1)];

            //Should we remove the last part if not too long?
            if (substr($last_peace, 0, 1) == ' ' && strlen($last_peace) < 16) {
                $title = str_replace($keyword . $last_peace, '', $title);
                break; //Only a single extension, so break the loop
            }
        }
    }
    return trim($title);
}

function auth($entity_groups = null, $force_redirect = 0)
{

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');

    //Let's start checking various ways we can give user access:
    if (!$entity_groups && is_array($udata) && count($udata) > 0) {

        //No minimum level required, grant access IF logged in:
        return $udata;

    } elseif (isset($udata['en__parents']) && filter_array($udata['en__parents'], 'en_id', 1308)) {

        //Always grant access to miners:
        return $udata;

    } elseif (isset($udata['en_id']) && filter_array($udata['en__parents'], 'en_id', $entity_groups)) {

        //They are part of one of the levels assigned to them:
        return $udata;

    }

    //Still here?!
    //We could not find a reason to give user access, so block them:
    if (!$force_redirect) {
        return false;
    } else {
        //Block access:
        redirect_message((isset($udata['en__parents'][0]) && filter_array($udata['en__parents'], 'en_id', 1308) ? '/intents/' . $this->config->item('in_primary_id') : '/login?url=' . urlencode($_SERVER['REQUEST_URI'])), '<div class="alert alert-danger maxout" role="alert">' . (isset($udata['en_id']) ? 'Access not authorized.' : 'Session Expired. Login to continue.') . '</div>');
    }

}

function redirect_message($url, $message = null, $response_code = null)
{

    //Do we have a Message?
    if ($message) {
        $CI =& get_instance();
        $CI->session->set_flashdata('hm', $message);
    }

    //What's the default response code?
    $response_code = (!$response_code && !$message ? 301 : ($response_code ? $response_code : null));
    if ($response_code) {
        header("Location: " . $url, true, $response_code);
    } else {
        header("Location: " . $url, true);
    }
    die();
}

function remote_mime($file_url)
{
    //Fetch Remote:
    $ch = curl_init($file_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return $mime;
}

function save_file($file_url, $json_data, $is_local = false)
{
    $CI =& get_instance();

    $file_name = md5($file_url . 'fileSavingSa!t') . '.' . fetch_file_ext($file_url);

    if (!$is_local) {
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
        $fp = @fopen($file_path . $file_name, 'w');
    }

    //Then upload to AWS S3:
    if (($is_local || (isset($fp) && $fp)) && @require_once('application/libraries/aws/aws-autoloader.php')) {

        if (isset($fp)) {
            fwrite($fp, $result);
            fclose($fp);
        }

        $s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region' => 'us-west-2',
            'credentials' => $CI->config->item('aws_credentials'),
        ]);
        $result = $s3->putObject(array(
            'Bucket' => 's3foundation', //Same bucket for now
            'Key' => $file_name,
            'SourceFile' => ($is_local ? $file_url : $file_path . $file_name),
            'ACL' => 'public-read'
        ));

        if (isset($result['ObjectURL']) && strlen($result['ObjectURL']) > 10) {
            @unlink(($is_local ? $file_url : $file_path . $file_name));
            return $result['ObjectURL'];
        } else {
            $CI->Database_model->tr_create(array(
                'tr_content' => 'save_file() Unable to upload file [' . $file_url . '] to Mench cloud.',
                'tr_metadata' => $json_data,
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }

    } else {
        //Probably local, ignore this!
        return false;
    }
}

function fb_time($unix_time)
{
    //It has milliseconds like "1458668856253", which we need to tranform for DB insertion:
    return date("Y-m-d H:i:s", round($unix_time / 1000));
}


function curl_html($url, $return_breakdown = false)
{

    //Validate URL:
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
    curl_setopt($ch, CURLOPT_REFERER, "https://mench.com");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_POST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8); //If site takes longer than this to connect, we have an issue!

    if (is_dev()) {
        //SSL does not work on my local PC.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }
    $response = curl_exec($ch);

    if ($return_breakdown) {

        $body_html = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $content_type = one_two_explode('', ';', curl_getinfo($ch, CURLINFO_CONTENT_TYPE));
        $embed_code = echo_embed($url, $url, true);

        // Now see if this is a specific file type:
        // Audio File URL: https://s3foundation.s3-us-west-2.amazonaws.com/672b41ff20fece4b3e7ae2cf4b58389f.mp3
        // Video File URL: https://s3foundation.s3-us-west-2.amazonaws.com/8c5a1cc4e8558f422a4003d126502db9.mp4
        // Image File URL: https://s3foundation.s3-us-west-2.amazonaws.com/d673c17d7164817025a000416da3be3f.png
        // Downloadable File URL: https://s3foundation.s3-us-west-2.amazonaws.com/611695da5d0d199e2d95dd2eabe484cf.zip

        if (substr_count($content_type, 'application/') == 1) {
            //File URL
            $tr_en_type_id = 4261;
        } elseif (substr_count($content_type, 'image/') == 1) {
            //Image URL
            $tr_en_type_id = 4260;
        } elseif (substr_count($content_type, 'audio/') == 1) {
            //Audio URL
            $tr_en_type_id = 4259;
        } elseif (substr_count($content_type, 'video/') == 1) {
            //Video URL
            $tr_en_type_id = 4258;
        } elseif ($embed_code['status']) {
            //Embeddable URL:
            $tr_en_type_id = 4257;
        } else {
            //Generic URL:
            $tr_en_type_id = 4256;
        }

        return array(
            //used all the time, also when updating en entity:
            'tr_en_type_id' => $tr_en_type_id,
            'page_title' => clean_title(one_two_explode('>', '', one_two_explode('<title', '</title', $body_html))),
        );

    } else {
        //Simply return the response:
        return $response;
    }
}

function boost_power()
{
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0);
}


function objectToArray($object)
{
    if (!is_object($object) && !is_array($object)) {
        return $object;
    }
    if (is_object($object)) {
        $object = (array)$object;
    }
    return array_map('objectToArray', $object);
}


function arrayToObject($array)
{
    $obj = new stdClass;
    foreach ($array as $k => $v) {
        if (strlen($k)) {
            if (is_array($v)) {
                $obj->{$k} = arrayToObject($v); //RECURSION
            } else {
                $obj->{$k} = $v;
            }
        }
    }
    return $obj;
}

function extract_references($prefix, $message)
{
    //$words = explode(' ',trim($message));
    $words = preg_split('/[\s]+/', trim($message));
    $matches = array();
    foreach ($words as $word) {
        if (substr($word, 0, 1) == $prefix) {
            //Looks like it, is the rest all integers?
            $id = substr($word, 1);
            if (strlen($id) == strlen(intval($id))) {
                //Yea seems like all integers, append:
                array_push($matches, intval($id));
            }
        }
    }
    return $matches;
}

function message_validation($tr_content)
{


    $CI =& get_instance();
    $tr_content_max = $CI->config->item('tr_content_max');

    //Extract details from this message:
    $urls = extract_urls($tr_content);
    $en_ids = extract_references('@', $tr_content);


    if (!isset($tr_content) || strlen($tr_content) <= 0) {
        return array(
            'status' => 0,
            'message' => 'Missing Message',
        );
    } elseif (substr_count($tr_content, '/firstname') > 1) {
        return array(
            'status' => 0,
            'message' => '/firstname can be used only once',
        );
    } elseif (strlen($tr_content) > $tr_content_max) {
        return array(
            'status' => 0,
            'message' => 'Max is ' . $tr_content_max . ' Characters',
        );
    } elseif ($tr_content != strip_tags($tr_content)) {
        return array(
            'status' => 0,
            'message' => 'HTML Code is not allowed',
        );
    } elseif (!preg_match('//u', $tr_content)) {
        return array(
            'status' => 0,
            'message' => 'Message must be UTF8',
        );
    } elseif (count($en_ids) > 1) {
        return array(
            'status' => 0,
            'message' => 'You can reference a maximum of 1 entity per message',
        );
    } elseif (count($en_ids) > 0 && count($urls) > 0) {
        return array(
            'status' => 0,
            'message' => 'You can either reference 1 entity or include 1 URL which would transform into an entity',
        );
    } elseif (count($urls) > 1) {
        return array(
            'status' => 0,
            'message' => 'Max 1 URL per Message',
        );
    } elseif ((count($en_ids) == 0 && count($urls) == 0) && substr_count($tr_content, '/slice') > 0) {
        return array(
            'status' => 0,
            'message' => '/slice command required an entity reference [@' . count($en_ids) . ']',
        );
    }


    //Validate Entity:
    if (count($en_ids) > 0) {

        $i_children_us = $CI->Database_model->en_fetch(array(
            'en_id' => $en_ids[0],
        ), array('skip_en__parents', 'u__urls'));

        if (count($i_children_us) == 0) {
            //Invalid ID:
            return array(
                'status' => 0,
                'message' => 'Entity [@' . $en_ids[0] . '] does not exist',
            );
        } elseif ($i_children_us[0]['en_status'] < 0) {
            //Inactive:
            return array(
                'status' => 0,
                'message' => 'Entity [' . $i_children_us[0]['en_name'] . '] is not active so you cannot link to it',
            );
        }

    } elseif (count($urls) > 0) {

        //No entity linked, but we have a URL that we should turn into an entity:
        $url_create = $CI->Database_model->x_sync($urls[0], 1326, false, true);

        //Did we have an error?
        if (!$url_create['status']) {
            return $url_create;
        }

        $en_ids[0] = $url_create['en']['en_id'];

        //Replace the URL with this new @entity in message:
        $tr_content = str_replace($urls[0], '@' . $en_ids[0], $tr_content);

    }

    //Do we have any commands?
    if (substr_count($tr_content, '/slice') > 0) {

        //Validate the format of this command:
        $slice_times = explode(':', one_two_explode('/slice:', ' ', $tr_content), 2);
        if (intval($slice_times[0]) < 1 || intval($slice_times[1]) < 1 || strlen($slice_times[0]) != strlen(intval($slice_times[0])) || strlen($slice_times[1]) != strlen(intval($slice_times[1]))) {
            //Not valid format!
            return array(
                'status' => 0,
                'message' => 'Invalid format for /slice command. For example, to slice first 60 seconds use: /slice:0:60',
            );
        } elseif ((intval($slice_times[0]) + 3) > intval($slice_times[1])) {
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
        foreach ($i_children_us[0]['u__urls'] as $x) {
            if ($x['x_type'] == 1 && substr_count($x['x_url'], 'youtube.com') > 0) {
                $found_slicable_url = true;
                break;
            }
        }
        if (!$found_slicable_url) {
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
        'tr_content' => trim($tr_content), //It might have been modified if URL was added
        'tr_en_parent_id' => (count($en_ids) > 0 ? $en_ids[0] : 0), //Referencing an entity?
    );
}


function generate_hashtag($text)
{
    //These hashtags cannot be taken
    $CI =& get_instance();

    //Cleanup the text:
    $text = trim($text);
    $text = ucwords($text);
    $text = str_replace('&', 'And', $text);
    $text = preg_replace("/[^a-zA-Z0-9]/", "", $text);
    $text = substr($text, 0, 30);

    return $text;
}

function one_two_explode($one, $two, $content)
{
    if (strlen($one) > 0) {
        if (substr_count($content, $one) < 1) {
            return NULL;
        }
        $temp = explode($one, $content, 2);
        if (strlen($two) > 0) {
            $temp = explode($two, $temp[1], 2);
            return trim($temp[0]);
        } else {
            return trim($temp[1]);
        }
    } else {
        $temp = explode($two, $content, 2);
        return trim($temp[0]);
    }
}


function format_tr_content($tr_content)
{

    //Do replacements:
    if (substr_count($tr_content, '/attach ') > 0) {
        $attachments = explode('/attach ', $tr_content);
        foreach ($attachments as $key => $attachment) {
            if ($key == 0) {
                //We're gonna start buiolding this message from scrach:
                $tr_content = $attachment;
                continue;
            }
            $segments = explode(':', $attachment, 2);
            $sub_segments = preg_split('/[\s]+/', $segments[1]);

            if ($segments[0] == 'image') {
                $tr_content .= '<img src="' . $sub_segments[0] . '" style="max-width:100%" />';
            } elseif ($segments[0] == 'audio') {
                $tr_content .= '<audio controls><source src="' . $sub_segments[0] . '" type="audio/mpeg"></audio>';
            } elseif ($segments[0] == 'video') {
                $tr_content .= '<video width="100%" onclick="this.play()" controls><source src="' . $sub_segments[0] . '" type="video/mp4"></video>';
            } elseif ($segments[0] == 'file') {
                $tr_content .= '<a href="' . $sub_segments[0] . '" class="btn btn-primary" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';
            }

            //Do we have any leftovers after the URL? If so, append:
            if (isset($sub_segments[1])) {
                $tr_content = ' ' . $sub_segments[1];
            }
        }
    } else {
        $tr_content = echo_link($tr_content);
    }
    $tr_content = nl2br($tr_content);
    return $tr_content;
}


function bigintval($value)
{
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


































