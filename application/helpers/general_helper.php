<?php

function fn___is_dev()
{
    //Determines if our development environment is development or not
    return (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'local.mench.co');
}

function fn___includes_any($string, $items)
{
    //Determines if any of the items in array $items includes $string
    foreach ($items as $item) {
        if (substr_count($string, $items) > 0) {
            return $item;
        }
    }
    return false;
}

function fn___sortByScore($a, $b)
{
    //An array sorting function for entities based on their trust score:
    return intval($b['en_trust_score']) - intval($a['en_trust_score']);
}

function fn___load_php_algolia($index_name)
{
    //Loads up algolia search engine functions
    $CI =& get_instance();
    if ($CI->config->item('enable_algolia')) {
        require_once('application/libraries/algoliasearch.php');
        $client = new \AlgoliaSearch\Client("49OCX1ZXLJ", "84a8df1fecf21978299e31c5b535ebeb");
        return $client->initIndex($index_name);
    }
}

function fn___detect_missing_columns($insert_columns, $required_columns)
{
    //A function used to review and require certain fields when inserting new rows in DB
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



function fn___fetch_file_ext($url)
{
    //A function that attempts to fetch the file extension of an input URL:
    //https://cdn.fbsbx.com/v/t59.3654-21/19359558_10158969505640587_4006997452564463616_n.aac/audioclip-1500335487327-1590.aac?oh=5344e3d423b14dee5efe93edd432d245&oe=596FEA95
    $url_parts = explode('?', $url, 2);
    $url_parts = explode('/', $url_parts[0]);
    $file_parts = explode('.', end($url_parts));
    return end($file_parts);
}


function fn___parse_signed_request($signed_request)
{

    //A function recommended by Facebook tp parse the signed request we receive from Facebook servers
    //Fetch app settings:
    $CI =& get_instance();
    $fb_settings = $CI->config->item('fb_settings');

    list($encoded_sig, $payload) = explode('.', $signed_request, 2);

    // Decode the data
    $sig = fn___base64_url_decode($encoded_sig);
    $data = json_decode(fn___base64_url_decode($payload), true);

    // Confirm the signature
    $expected_sig = hash_hmac('sha256', $payload, $fb_settings['client_secret'], $raw = true);
    if ($sig !== $expected_sig) {
        //error_log('Bad Signed JSON signature!');
        return null;
    }

    return $data;
}

function fn___base64_url_decode($input)
{
    //Another Facebook Recommended function that supports the fn___parse_signed_request() function
    return base64_decode(strtr($input, '-_', '+/'));
}


function fn___extract_message_references($tr_content)
{

    //Analyzes a message text to extract Entity References (Like @123) and URLs
    $CI =& get_instance();

    //Replace non-ascii characters with space:
    $tr_content = preg_replace('/[[:^print:]]/', ' ', $tr_content);
    $parts = preg_split('/\s+/', $tr_content);

    //Analyze the message to find referencing URLs and Entities in the message text:
    $obj_breakdown = array(
        'en_urls' => array(),
        'en_refs' => array(),
        'en_commands' => array(),
    );

    //See what we can find:
    foreach ($parts as $part) {
        if (filter_var($part, FILTER_VALIDATE_URL)) {
            array_push($obj_breakdown['en_urls'], $part);
        } elseif (substr($part, 0, 1) == '@' && intval($part) > 0) {
            array_push($obj_breakdown['en_refs'], intval($part));
        } else {
            //Check maybe it's a command?
            $command = fn___includes_any($part, $CI->config->item('message_commands'));
            if($command){
                //Yes!
                array_push($obj_breakdown['en_refs'], $command);
            }
        }
    }
    return $obj_breakdown;
}



function fn___isDate($string)
{
    //Determines if the input $string is a valid date
    if (!$string) {
        return false;
    }

    try {
        new \DateTime($string);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

function fn___detect_tr_en_type_id($string)
{

    /*
     * Detect what type of entity-to-entity URL type should we create
     * based on options listed in this tree: https://mench.com/entities/4227
     * */

    $string = trim($string);

    if (!$string || strlen($string) == 0) {
        //Naked:
        return 4230;
    } elseif (is_int($string) || is_double($string)) {
        //Number:
        return 4319;
    } elseif (filter_var($string, FILTER_VALIDATE_URL)) {
        //It's a URL, see what type:
        $curl = fn___curl_html($string, true);
        return $curl['tr_en_type_id'];
    } elseif (fn___isDate($string)) {
        //Date/time:
        return 4318;
    } else {
        //Regular text link:
        return 4255;
    }
}


function fn___filter_array($array, $match_key, $match_value)
{

    //Searches through $array and attempts to find $array[$match_key] = $match_value
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


function fn___en_auth($en_permission_group = null, $force_redirect = 0)
{

    //Authenticates logged-in users with their session information
    $CI =& get_instance();
    $udata = $CI->session->userdata('user');

    //Let's start checking various ways we can give user access:
    if (!$en_permission_group && is_array($udata) && count($udata) > 0) {

        //No minimum level required, grant access IF user is logged in:
        return $udata;

    } elseif (isset($udata['en__parents']) && fn___filter_array($udata['en__parents'], 'en_id', 1308)) {

        //Always grant access to miners:
        return $udata;

    } elseif (isset($udata['en_id']) && fn___filter_array($udata['en__parents'], 'en_id', $en_permission_group)) {

        //They are part of one of the levels assigned to them:
        return $udata;

    }

    //Still here?!
    //We could not find a reason to give user access, so block them:
    if (!$force_redirect) {
        return false;
    } else {
        //Block access:
        return fn___redirect_message((isset($udata['en__parents'][0]) && fn___filter_array($udata['en__parents'], 'en_id', 1308) ? '/intents/' . $CI->config->item('in_primary_id') : '/login?url=' . urlencode($_SERVER['REQUEST_URI'])), '<div class="alert alert-danger maxout" role="alert">' . (isset($udata['en_id']) ? 'Access not authorized.' : 'Session Expired. Login to continue.') . '</div>');
    }

}

function fn___redirect_message($url, $message = null)
{
    //An error handling function that would redirect user to $url with optional $message
    //Do we have a Message?
    if ($message) {
        $CI =& get_instance();
        $CI->session->set_flashdata('hm', $message);
    }

    if (!$message) {
        //Do a permanent redirect if message not available:
        return header("Location: " . $url, true, 301);
    } else {
        return header("Location: " . $url, true);
    }
}


function fn___upload_to_cdn($file_url, $json_data, $is_local = false)
{

    /*
     * A function that would save a file from URL to our Amazon CDN
     * */
    $CI =& get_instance();

    $file_name = md5($file_url . 'fileSavingSa!t') . '.' . fn___fetch_file_ext($file_url);

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
                'tr_en_type_id' => 4246, //Platform Error
                'tr_content' => 'fn___upload_to_cdn() Unable to upload file [' . $file_url . '] to Mench cloud.',
                'tr_metadata' => $json_data,
            ));
            return false;

        }

    } else {
        //Probably local, ignore this!
        return false;
    }
}


function fn___curl_html($url, $return_breakdown = false)
{

    /*
     * A CURL function to fetch more details on $url
     * */

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

    if (fn___is_dev()) {
        //SSL does not work on my local PC.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }
    $response = curl_exec($ch);

    if ($return_breakdown) {

        $body_html = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $content_type = fn___one_two_explode('', ';', curl_getinfo($ch, CURLINFO_CONTENT_TYPE));
        $embed_code = fn___echo_url_embed($url, $url, true);

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


        //Cleanup Page Title:
        $title = fn___one_two_explode('>', '', fn___one_two_explode('<title', '</title', $body_html));
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

        return array(
            //used all the time, also when updating en entity:
            'tr_en_type_id' => $tr_en_type_id,
            'page_title' => trim($title),
        );

    } else {
        //Simply return the response:
        return $response;
    }
}

function fn___boost_power()
{
    //Give php page instance more processing power
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0);
}


function fn___objectToArray($object)
{
    //Transform an object into an array
    if (!is_object($object) && !is_array($object)) {
        return $object;
    }
    if (is_object($object)) {
        $object = (array)$object;
    }
    return array_map('fn___objectToArray', $object);
}


function fn___validate_message($tr_content)
{

    /*
     *
     * Validate Intent messages based on various factors
     *
     * */

    $CI =& get_instance();
    $tr_content_max = $CI->config->item('tr_content_max'); //Maximum allowed length
    $status_index = $CI->config->item('object_statuses');
    $tr_content = trim($tr_content);

    //Extract references from this message including its URLs and referenced entities (like "@123")
    $obj_breakdown = fn___extract_message_references($tr_content);

    if (strlen($tr_content) < 1) {
        return array(
            'status' => 0,
            'message' => 'Missing Message',
        );
    } elseif (substr_count($tr_content, '/firstname') > 1) {
        return array(
            'status' => 0,
            'message' => '/firstname command can be used only once',
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
    } elseif (count($obj_breakdown['en_refs']) > 1) {
        return array(
            'status' => 0,
            'message' => 'You can reference a maximum of 1 entity per message',
        );
    } elseif (count($obj_breakdown['en_urls']) > 1) {
        return array(
            'status' => 0,
            'message' => 'You can reference a maximum of 1 URL per message',
        );
    } elseif (count($obj_breakdown['en_refs']) > 0 && count($obj_breakdown['en_urls']) > 0) {
        return array(
            'status' => 0,
            'message' => 'You can either reference 1 entity OR 1 URL (As the URL will be transformed into an entity)',
        );
    } elseif (substr_count($tr_content, '/slice') > 1) {
        return array(
            'status' => 0,
            'message' => '/slice command can be used only once',
        );
    } elseif (count($obj_breakdown['en_refs']) == 0 && count($obj_breakdown['en_urls']) == 0 && substr_count($tr_content, '/slice') > 0) {
        return array(
            'status' => 0,
            'message' => '/slice command required an entity reference',
        );
    }


    //Validate Entity Reference if Any:
    if (count($obj_breakdown['en_refs']) > 0) {

        $ens = $CI->Database_model->en_fetch(array(
            'en_id' => $obj_breakdown['en_refs'][0],
        ));

        if (count($ens) == 0) {
            //Invalid ID:
            return array(
                'status' => 0,
                'message' => 'Entity [@' . $obj_breakdown['en_refs'][0] . '] does not exist',
            );
        } elseif ($ens[0]['en_status'] < 0) {
            //Inactive:
            return array(
                'status' => 0,
                'message' => 'Entity [' . $ens[0]['en_name'] . '] status is ['.$status_index['en_status'][$ens[0]['en_status']]['s_name'].'] so its unavailable for referencing.',
            );
        }

    } elseif (count($obj_breakdown['en_urls']) > 0) {

        //No entity linked, but we have a URL that we should turn into an entity:
        $created_url = $CI->Matrix_model->fn___create_en_from_url($obj_breakdown['en_urls'][0]);

        //Did we have an error?
        if (!$created_url['status']) {
            return $created_url;
        }

        //Transform this URL into an entity:
        $obj_breakdown['en_refs'][0] = $created_url['en_from_url']['en_id'];

        //Replace the URL with this new @entity in message:
        $tr_content = str_replace($obj_breakdown['en_urls'][0], '@' . $obj_breakdown['en_refs'][0], $tr_content);

    }


    //Do we have any commands?
    if (substr_count($tr_content, '/slice') > 0) {

        //Validate the format of this command:
        $slice_times = explode(':', fn___one_two_explode('/slice:', ' ', $tr_content), 2);
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

        /*
         *
         * Ensure entity has a sliceable content
         * currently supporting: YouTube Only!
         * See error message below...
         *
         * TODO This logic is not mapped on the Matrix in any way!
         * TODO Maybe create a @SlicableURL entity to index all objects that accept the /slice command
         *
         * */

        $found_slicable_url = false;
        foreach ($ens[0]['en__parents'] as $en) {
            if (substr_count($en['tr_content'], 'youtube.com') > 0) {
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


    //All seems good, return success:
    return array(
        'status' => 1,
        'message' => 'Success',
        //Return cleaned data:
        'tr_content' => trim($tr_content), //It might have been modified if URL was added
        'tr_en_parent_id' => (count($obj_breakdown['en_refs']) > 0 ? $obj_breakdown['en_refs'][0] : 0), //Referencing an entity?
    );

}

function fn___one_two_explode($one, $two, $string)
{
    //A quick function to extract a subset of $string between $one and $two
    if (strlen($one) > 0) {
        if (substr_count($string, $one) < 1) {
            return NULL;
        }
        $temp = explode($one, $string, 2);
        if (strlen($two) > 0) {
            $temp = explode($two, $temp[1], 2);
            return trim($temp[0]);
        } else {
            return trim($temp[1]);
        }
    } else {
        $temp = explode($two, $string, 2);
        return trim($temp[0]);
    }
}


