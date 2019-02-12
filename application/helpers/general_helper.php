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
        if (substr_count($string, $item) > 0) {
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
            $CI->Database_model->fn___tr_create(array(
                'tr_content' => 'Missing required field [' . $req_field . '] for inserting new DB row',
                'tr_metadata' => array(
                    'insert_columns' => $insert_columns,
                    'required_columns' => $required_columns,
                ),
                'tr_type_en_id' => 4246, //Platform Error
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
    $msg_references = array(
        'ref_urls' => array(),
        'ref_entities' => array(),
        'ref_commands' => array(),
    );

    //See what we can find:
    foreach ($parts as $part) {
        if (filter_var($part, FILTER_VALIDATE_URL)) {
            array_push($msg_references['ref_urls'], $part);
        } elseif (substr($part, 0, 1) == '@' && is_numeric(substr($part, 1))) {
            array_push($msg_references['ref_entities'], intval(substr($part, 1)));
        } else {
            //Check maybe it's a command?
            $command = fn___includes_any($part, $CI->config->item('message_commands'));
            if ($command) {
                //Yes!
                array_push($msg_references['ref_commands'], $command);
            }
        }
    }
    return $msg_references;
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

function fn___detect_tr_type_en_id($string)
{

    /*
     * Detect what type of entity-to-entity URL type should we create
     * based on options listed in this tree: https://mench.com/entities/4227
     * */

    $string = trim($string);

    if (is_null($string) || strlen($string) == 0) {
        //Naked:
        return array(
            'status' => 1,
            'tr_type_en_id' => 4230,
        );
    } elseif (strlen(intval($string)) == strlen($string) && (intval($string) > 0 || $string == '0')) {
        //Number:
        return array(
            'status' => 1,
            'tr_type_en_id' => 4319,
        );
    } elseif (filter_var($string, FILTER_VALIDATE_URL)) {

        //It's a URL, see what type (this could fail if duplicate, etc...):
        return fn___curl_html($string, true);

    } elseif (strlen($string) > 9 && (fn___isDate($string) || strtotime($string) > 0)) {
        //Date/time:
        return array(
            'status' => 1,
            'tr_type_en_id' => 4318,
        );
    } else {
        //Regular text link:
        return array(
            'status' => 1,
            'tr_type_en_id' => 4255,
        );
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
        return fn___redirect_message((isset($udata['en__parents'][0]) && fn___filter_array($udata['en__parents'], 'en_id', 1308) ? '/intents/' . $CI->config->item('in_tactic_id') : '/login?url=' . urlencode($_SERVER['REQUEST_URI'])), '<div class="alert alert-danger maxout" role="alert">' . (isset($udata['en_id']) ? 'Access not authorized.' : 'Login to access the matrix.') . '</div>');
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

            $CI->Database_model->fn___tr_create(array(
                'tr_type_en_id' => 4246, //Platform Error
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


function detect_download_file_url($url, $mime_code) {

    $mime_types = array(
        //Web sources:
        'swf' => 'application/x-shockwave-flash',

        //archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // adobe
        'pdf' => 'application/pdf',
        'ai'  => 'application/postscript',
        'eps' => 'application/postscript',
        'ps'  => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    $parts = explode('.', $url);
    $ext = strtolower(array_pop($parts));

    //Return if we found this file type:
    return (array_key_exists($ext, $mime_types) || in_array($mime_code, $mime_types));

}

function fn___curl_html($url, $return_breakdown = false)
{

    /*
     * A CURL function to fetch more details on $url
     * */

    //Validate URL:
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return array(
            'status' => 0,
            'message' => 'Enter a valid URL',
        );
    }


    //Detect domain parent:
    $parse = parse_url($url);
    $domain_url = $parse['scheme'] . '://' . $parse['host'];
    if ($domain_url . '/' == $url) {
        //Clean the URL by trimming trailing slashes after domain names to better find matching URLs.
        //TODO improve the logic behind this entire system to better detect duplicate URLs...
        $url = $domain_url;
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

        $CI =& get_instance();

        $body_html = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $content_type = fn___one_two_explode('', ';', curl_getinfo($ch, CURLINFO_CONTENT_TYPE));
        $embed_code = fn___echo_url_embed($url, $url, true);
        $en_all_4537 = $CI->config->item('en_all_4537');

        // Now see if this is a specific file type:
        // Audio File URL: https://s3foundation.s3-us-west-2.amazonaws.com/672b41ff20fece4b3e7ae2cf4b58389f.mp3
        // Video File URL: https://s3foundation.s3-us-west-2.amazonaws.com/8c5a1cc4e8558f422a4003d126502db9.mp4
        // Image File URL: https://s3foundation.s3-us-west-2.amazonaws.com/d673c17d7164817025a000416da3be3f.png
        // Downloadable File URL: https://s3foundation.s3-us-west-2.amazonaws.com/611695da5d0d199e2d95dd2eabe484cf.zip

        if (substr_count($content_type, 'image/') == 1) {
            //Image URL
            $tr_type_en_id = 4260;
        } elseif (substr_count($content_type, 'audio/') == 1) {
            //Audio URL
            $tr_type_en_id = 4259;
        } elseif (substr_count($content_type, 'video/') == 1) {
            //Video URL
            $tr_type_en_id = 4258;
        } elseif ($embed_code['status']) {
            //Embeddable URL:
            $tr_type_en_id = 4257;
        } elseif (detect_download_file_url($url, $content_type)) {
            //File URL
            $tr_type_en_id = 4261;
        } else {
            //Generic URL:
            $tr_type_en_id = 4256;
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
        $title = trim($title);
        if (strlen($title) > 0) {

            //Make sure this is not a duplicate name:
            $dup_name_us = $CI->Database_model->fn___en_fetch(array(
                'en_status >=' => 0, //New+
                'en_name' => $title,
            ));

            if (count($dup_name_us) > 0) {
                //Yes, we did find a duplicate name! Append a unique identifier:
                $title = $title . ' ' . substr(md5($url), 0, 8);
            }

        } else {

            //did not find a <title> tag:
            //Use URL Type as its name:
            $title = $en_all_4537[$tr_type_en_id]['m_name'] . ' ' . substr(md5($url), 0, 8); //Append a unique identifier

        }

        //Check to see if duplicate URL:
        $dup_url_trs = $CI->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0, //New+
            'tr_type_en_id IN (' . join(',', $CI->config->item('en_ids_4537')) . ')' => null, //Entity URL Links
            'tr_content' => $url,
        ), array('en_child'));


        //Prep return data:
        $return_data = array(
            //used all the time, also when updating en entity:
            'cleaned_url' => $url,
            'CURLINFO_CONTENT_TYPE' => curl_getinfo($ch, CURLINFO_CONTENT_TYPE),
            'domain_url' => $domain_url,
            'domain_host' => $parse['host'],
            'tr_type_en_id' => $tr_type_en_id,
            'tr_type_en' => $en_all_4537[$tr_type_en_id],
            'status' => (count($dup_url_trs) > 0 ? 0 : 1),
            'dup_en' => (count($dup_url_trs) > 0 ? $dup_url_trs[0] : array()),
            'message' => (count($dup_url_trs) > 0 ? 'URL already added for entity <a href="/entities/' . $dup_url_trs[0]['en_id'] . '"><b>' . $dup_url_trs[0]['en_name'] . '</b></a>' : 'Success'),
            'page_title' => $title,
        );

        //Return results:
        return $return_data;

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


