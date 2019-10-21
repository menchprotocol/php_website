<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Play extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_value(11079));
    }

    function echo_post(){
        print_r($_POST);
    }

    function bot(){

        $url = 'https://medium.com/_/graphql';
        $topic = 'books';


        $custom_header = array(
            'medium-frontend-app: lite/master-20191021-212205-4df9cf54be',
            'medium-frontend-route: topic',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-origin',
            'apollographql-client-name: lite',
            'apollographql-client-version: master-20191021-212205-4df9cf54be',

            'accept: */*',
            'accept-encoding: gzip, deflate, br',
            'accept-language: en-GB,en-US;q=0.9,en;q=0.8',


            'content-length: 6731',
            'content-type: application/json',
            'graphql-operation: TopicHandler',
            'origin: https://medium.com',
            'referer: https://medium.com/topic/'.$topic,
            'cookie: __cfduid=db6eef3c324dc50d96ef21938b6f00edc1559329362; _ga=GA1.2.2055208362.1565325511; lightstep_session_id=7e05aed248707e2e; lightstep_guid/medium-web=93bf19db9151b98a; tz=420; pr=2; lightstep_guid/lite-web=58f426d9268501ac; _gid=GA1.2.1980711924.1571614565; optimizelyEndUserId=lo_e8082354b03e; uid=lo_e8082354b03e; sid=1:/xbfZQ7E3E7EPoIIifaIKj/DmNhQCAKcI9h6hfo+EFoV1Vicr75acNYhuyD26dd9; __cfruid=119da54070c31f79db03af372b90931f3f9aa260-1571694671; _parsely_session={%22sid%22:38%2C%22surl%22:%22https://medium.com/%22%2C%22sref%22:%22%22%2C%22sts%22:1571694672029%2C%22slts%22:1571688510366}; _parsely_visitor={%22id%22:%22pid=acaaa24a25423adbd91231b52e769418%22%2C%22session_count%22:38%2C%22last_session_ts%22:1571694672029}; sz=1652',
        );


        $data = array(
            'operationName' => 'TopicHandler',
            'variables' => array(
                'feedPagingOptions' => array(
                    'limit' => 25,
                    'to' => '1571614526950',
                ),
                'sidebarPagingOptions' => array(
                    'limit' => 5,
                ),
                'topicSlug' => $topic,
            ),
            'query' => 'query TopicHandler($topicSlug: ID!, $feedPagingOptions: PagingOptions, $sidebarPagingOptions: PagingOptions) {
  topic(slug: $topicSlug) {
    canonicalSlug
    ...TopicScreen_topic
    __typename
  }
}

fragment PostListingItemFeed_postPreview on PostPreview {
  post {
    ...PostListingItemPreview_post
    ...PostListingItemByline_post
    ...PostListingItemImage_post
    ...PostPresentationTracker_post
    __typename
  }
  __typename
}

fragment PostListingItemPreview_post on Post {
  id
  mediumUrl
  title
  previewContent {
    subtitle
    isFullContent
    __typename
  }
  isPublished
  creator {
    id
    __typename
  }
  __typename
}

fragment PostListingItemByline_post on Post {
  id
  creator {
    id
    username
    name
    __typename
  }
  isLocked
  readingTime
  ...BookmarkButton_post
  firstPublishedAt
  statusForCollection
  collection {
    id
    name
    ...collectionUrl_collection
    __typename
  }
  __typename
}

fragment BookmarkButton_post on Post {
  ...SusiClickable_post
  ...WithSetReadingList_post
  __typename
}

fragment SusiClickable_post on Post {
  id
  mediumUrl
  ...SusiContainer_post
  __typename
}

fragment SusiContainer_post on Post {
  id
  __typename
}

fragment WithSetReadingList_post on Post {
  ...ReadingList_post
  __typename
}

fragment ReadingList_post on Post {
  id
  readingList
  __typename
}

fragment collectionUrl_collection on Collection {
  id
  domain
  slug
  __typename
}

fragment PostListingItemImage_post on Post {
  id
  mediumUrl
  previewImage {
    id
    focusPercentX
    focusPercentY
    __typename
  }
  __typename
}

fragment PostPresentationTracker_post on Post {
  id
  visibility
  previewContent {
    isFullContent
    __typename
  }
  collection {
    id
    __typename
  }
  __typename
}

fragment TopicScreen_topic on Topic {
  id
  ...TopicMetadata_topic
  ...TopicLandingHeader_topic
  ...TopicFeaturedAndLatest_topic
  ...TopicLandingRelatedTopics_topic
  ...TopicLandingPopular_posts
  __typename
}

fragment TopicMetadata_topic on Topic {
  name
  description
  image {
    id
    __typename
  }
  __typename
}

fragment TopicLandingHeader_topic on Topic {
  name
  description
  visibility
  ...TopicFollowButtonSignedIn_topic
  ...TopicFollowButtonSignedOut_topic
  __typename
}

fragment TopicFollowButtonSignedIn_topic on Topic {
  slug
  isFollowing
  __typename
}

fragment TopicFollowButtonSignedOut_topic on Topic {
  id
  slug
  ...SusiClickable_topic
  __typename
}

fragment SusiClickable_topic on Topic {
  ...SusiContainer_topic
  __typename
}

fragment SusiContainer_topic on Topic {
  ...SignInContainer_topic
  ...SignUpOptions_topic
  __typename
}

fragment SignInContainer_topic on Topic {
  ...SignInOptions_topic
  __typename
}

fragment SignInOptions_topic on Topic {
  id
  name
  __typename
}

fragment SignUpOptions_topic on Topic {
  id
  name
  __typename
}

fragment TopicFeaturedAndLatest_topic on Topic {
  featuredPosts {
    postPreviews {
      post {
        id
        ...TopicLandingFeaturedStory_post
        __typename
      }
      __typename
    }
    __typename
  }
  featuredTopicWriters(limit: 1) {
    ...FeaturedWriter_featuredTopicWriter
    __typename
  }
  latestPosts(paging: $feedPagingOptions) {
    postPreviews {
      post {
        id
        __typename
      }
      ...PostListingItemFeed_postPreview
      __typename
    }
    pagingInfo {
      next {
        limit
        to
        __typename
      }
      __typename
    }
    __typename
  }
  __typename
}

fragment TopicLandingFeaturedStory_post on Post {
  ...FeaturedPostPreview_post
  ...PostListingItemPreview_post
  ...PostListingItemBylineWithAvatar_post
  ...PostListingItemImage_post
  ...PostPresentationTracker_post
  __typename
}

fragment FeaturedPostPreview_post on Post {
  id
  title
  mediumUrl
  previewContent {
    subtitle
    isFullContent
    __typename
  }
  __typename
}

fragment PostListingItemBylineWithAvatar_post on Post {
  creator {
    username
    name
    id
    imageId
    mediumMemberAt
    __typename
  }
  isLocked
  readingTime
  updatedAt
  statusForCollection
  collection {
    id
    name
    ...collectionUrl_collection
    __typename
  }
  __typename
}

fragment FeaturedWriter_featuredTopicWriter on FeaturedTopicWriter {
  user {
    id
    username
    name
    bio
    ...UserAvatar_user
    ...UserFollowButton_user
    __typename
  }
  posts {
    postPreviews {
      ...PostListingItemFeaturedWriter_postPreview
      __typename
    }
    __typename
  }
  __typename
}

fragment UserAvatar_user on User {
  username
  id
  name
  imageId
  mediumMemberAt
  __typename
}

fragment UserFollowButton_user on User {
  ...UserFollowButtonSignedIn_user
  ...UserFollowButtonSignedOut_user
  __typename
}

fragment UserFollowButtonSignedIn_user on User {
  id
  isFollowing
  __typename
}

fragment UserFollowButtonSignedOut_user on User {
  id
  ...SusiClickable_user
  __typename
}

fragment SusiClickable_user on User {
  ...SusiContainer_user
  __typename
}

fragment SusiContainer_user on User {
  ...SignInContainer_user
  ...SignUpOptions_user
  __typename
}

fragment SignInContainer_user on User {
  ...SignInOptions_user
  __typename
}

fragment SignInOptions_user on User {
  id
  name
  __typename
}

fragment SignUpOptions_user on User {
  id
  name
  __typename
}

fragment PostListingItemFeaturedWriter_postPreview on PostPreview {
  postId
  post {
    readingTime
    id
    mediumUrl
    title
    ...PostListingItemImage_post
    ...PostPresentationTracker_post
    __typename
  }
  __typename
}

fragment TopicLandingRelatedTopics_topic on Topic {
  relatedTopics {
    topic {
      name
      slug
      __typename
    }
    __typename
  }
  __typename
}

fragment TopicLandingPopular_posts on Topic {
  name
  popularPosts(paging: $sidebarPagingOptions) {
    postPreviews {
      post {
        ...PostListingItemSidebar_post
        __typename
      }
      __typename
    }
    __typename
  }
  __typename
}

fragment PostListingItemSidebar_post on Post {
  id
  mediumUrl
  title
  readingTime
  ...PostListingItemImage_post
  ...PostPresentationTracker_post
  __typename
}',
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $custom_header);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        echo $server_output;

    }

    function overview(){
        $this->load->view('header', array(
            'title' => 'PLAY',
        ));
        $this->load->view('view_play/play_overview');
        $this->load->view('footer');
    }



    //Lists entities
    function play_modify($en_id)
    {

        $session_en = en_auth();

        //Do we have any mass action to process here?
        if (en_auth(10939 /* HONEY BADGER */) && isset($_POST['mass_action_en_id']) && isset($_POST['mass_value1_'.$_POST['mass_action_en_id']]) && isset($_POST['mass_value2_'.$_POST['mass_action_en_id']])) {

            //Process mass action:
            $process_mass_action = $this->PLAY_model->en_mass_update($en_id, intval($_POST['mass_action_en_id']), $_POST['mass_value1_'.$_POST['mass_action_en_id']], $_POST['mass_value2_'.$_POST['mass_action_en_id']], $session_en['en_id']);

            //Pass-on results to UI:
            $message = '<div class="alert '.( $process_mass_action['status'] ? 'alert-success' : 'alert-danger' ).'" role="alert">'.$process_mass_action['message'].'</div>';

        } else {

            //No mass action, just viewing...
            //Update session count and log link:
            $message = null; //No mass-action message to be appended...

            $new_order = ( $this->session->userdata('player_page_count') + 1 );
            $this->session->set_userdata('player_page_count', $new_order);
            $this->READ_model->ln_create(array(
                'ln_creator_entity_id' => $session_en['en_id'],
                'ln_type_entity_id' => 4994, //Trainer Opened Entity
                'ln_child_entity_id' => $en_id,
                'ln_order' => $new_order,
            ));

        }

        //Validate entity ID and fetch data:
        $ens = $this->PLAY_model->en_fetch(array(
            'en_id' => $en_id,
        ), array('en__child_count'));

        if (count($ens) < 1) {
            return redirect_message('/play', '<div class="alert alert-danger" role="alert">Invalid Entity ID</div>');
        }

        //Load views:
        $this->load->view('header', array(
            'title' => $ens[0]['en_name'] . ' | PLAY',
            'flash_message' => $message, //Possible mass-action message for UI:
        ));
        $this->load->view('view_play/play_modify', array(
            'entity' => $ens[0],
            'session_en' => $session_en,
        ));
        $this->load->view('footer');

    }

    function php_info(){
        echo phpinfo();
    }

    function my_session()
    {
        echo_json($this->session->all_userdata());
    }


    function leaderboard(){

        //Fetch top users per each direction
        $load_max = 100;
        $show_max = 10;


        //Create FILTERS:
        $filters = array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_10589')) . ')' => null, //BLOGGERS
            'ln_creator_entity_id >' => 0, //MUST HAVE LOGGED-IN PLAYER ASSIGNED
        );


        //Do we have a date filter?
        $start_date = null;
        /*
        if(1){ //Weekly

            //Week always starts on Monday:
            if(date('D') === 'Mon'){
                //Today is Monday:
                $start_date = date("Y-m-d");
            } else {
                $start_date = date("Y-m-d", strtotime('previous monday'));
            }
            $filters['ln_timestamp >='] = $start_date.' 00:00:00'; //From beginning of the day
        }
        */

        //Fetch leaderboard:
        $blog_coins = $this->READ_model->ln_fetch($filters, array('ln_creator'), $load_max, 0, array('total_words' => 'DESC'), 'SUM(ABS(ln_words)) as total_words, en_name, en_icon, en_id', 'en_id, en_name, en_icon');


        //Did we find anyone?
        if(count($blog_coins) > 0){
            foreach ($blog_coins as $count=>$ln) {

                if($count==$show_max){
                    echo '<tr class="see_more_who"></tr>';
                    echo '<tr class="see_more_who"><td colspan="3"><a href="javascript:void(0);" onclick="$(\'.see_more_who\').toggleClass(\'hidden\')" class="btn btn-play montserrat">SEE TOP '.$load_max.'</a></td></tr>';
                }
                if($ln['total_words'] < 1){
                    continue;
                }


                //COUNT this PLAYERS total READ COINS:
                $read_coins = $this->READ_model->ln_fetch(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_10590')) . ')' => null, //READERS
                    'ln_creator_entity_id' => $ln['en_id'],
                ), array(), 1, 0, array(), 'SUM(ABS(ln_words)) as total_words');


                echo '<tr class="'.( $count<$show_max ? '' : 'see_more_who hidden').'">';

                //PLAY
                echo '<td><span class="parent-icon icon-block">'.echo_en_icon($ln).'</span><a href="/play/'.$ln['en_id'].'">'.one_two_explode('',' ',$ln['en_name']).'</a></td>';

                //READ
                echo '<td><a href="/read/history?ln_status_entity_id='.join(',', $this->config->item('en_ids_7359')) /* Link Statuses Public */.'&ln_type_entity_id='.join(',', $this->config->item('en_ids_10590')).'&ln_creator_entity_id='.$ln['en_id'].( $start_date ? '&start_range='.$start_date : $start_date ).'" class="mono">'.number_format($read_coins[0]['total_words'], 0).'</a></td>';

                //BLOG
                echo '<td><a href="/read/history?ln_status_entity_id='.join(',', $this->config->item('en_ids_7359')) /* Link Statuses Public */.'&ln_type_entity_id='.join(',', $this->config->item('en_ids_10589')).'&ln_creator_entity_id='.$ln['en_id'].( $start_date ? '&start_range='.$start_date : $start_date ).'" class="mono">'.number_format($ln['total_words'], 0).'</a>'.echo_rank($count+1).'</td>';
                echo '</tr>';

            }

        } else {
            echo '<tr><td colspan="3"><div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> No Players Yet...</div></td></tr>';
        }


    }

    function sign($in_id = 0){

        //Check to see if they are already logged in?
        $session_en = en_auth();
        if (isset($session_en['en__parents'][0])) {
            //Lead trainer and above, go to console:
            if($in_id > 0){
                return redirect_message('/read/' . $in_id);
            } else {
                return redirect_message('/play');
            }
        }


        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
        $this->load->view('header', array(
            'hide_header' => 1,
            'title' => $en_all_11035[4269]['m_name'],
        ));
        $this->load->view('view_play/play_signing', array(
            'referrer_in_id' => intval($in_id),
        ));
        $this->load->view('footer');

    }


    function add_source_wizard()
    {
        //Authenticate Trainer, redirect if failed:
        $session_en = en_auth(null, true);

        //Show frame to be loaded in modal:
        $this->load->view('header', array(
            'title' => 'Add Source Wizard',
        ));
        $this->load->view('view_play/en_source_wizard');
        $this->load->view('footer');
    }


    function en_add_source_paste_url()
    {

        /*
         *
         * Validates the input URL to be added as a new source entity
         *
         * */

        $session_en = en_auth(null);
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
                'url_entity' => array(),
            ));
        }

        //All seems good, fetch URL:
        $url_entity = $this->PLAY_model->en_sync_url($_POST['input_url']);

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
            'entity_domain_ui' => '<span class="en_mini_ui_icon parent-icon">' . (isset($url_entity['en_domain']['en_icon']) && strlen($url_entity['en_domain']['en_icon']) > 0 ? $url_entity['en_domain']['en_icon'] : detect_fav_icon($url_entity['url_clean_domain'], true)) . '</span> ' . (isset($url_entity['en_domain']['en_name']) ? $url_entity['en_domain']['en_name'] . ' <a href="/play/' . $url_entity['en_domain']['en_id'] . '" class="underdot" data-toggle="tooltip" title="Click to open domain entity in a new windows" data-placement="top" target="_blank">@' . $url_entity['en_domain']['en_id'] . '</a>' : $url_entity['url_domain_name'] . ' [<span class="underdot" data-toggle="tooltip" title="Domain entity not yet added" data-placement="top">New</span>]'),
            'js_url_entity' => $url_entity,
        ));

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
        $detected_ln_type = ln_detect_type($_POST['ln_content']);

        if (!$detected_ln_type['status'] && isset($detected_ln_type['url_already_existed']) && $detected_ln_type['url_already_existed']) {

            //See if this is duplicate to either link:
            $en_lns = $this->READ_model->ln_fetch(array(
                'ln_id' => $_POST['ln_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Entity URL Links
            ));

            //Are they both different?
            if (count($en_lns) < 1 || ($en_lns[0]['ln_parent_entity_id'] != $detected_ln_type['en_url']['en_id'] && $en_lns[0]['ln_child_entity_id'] != $detected_ln_type['en_url']['en_id'])) {
                //return error:
                return echo_json($detected_ln_type);
            }
        }

        return echo_json(array(
            'status' => 1,
            'html_ui' => '<a href="/play/' . $detected_ln_type['ln_type_entity_id'] . '" style="font-weight: bold;" data-toggle="tooltip" data-placement="top" title="' . $en_all_4592[$detected_ln_type['ln_type_entity_id']]['m_desc'] . '">' . $en_all_4592[$detected_ln_type['ln_type_entity_id']]['m_icon'] . ' ' . $en_all_4592[$detected_ln_type['ln_type_entity_id']]['m_name'] . '</a>',
            'en_link_preview' => echo_url_type($_POST['ln_content'], $detected_ln_type['ln_type_entity_id']),
        ));
    }


    function en_save_file_upload()
    {

        //Authenticate Trainer:
        $session_en = en_auth(10939 /* HONEY BADGER */);
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
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
        } elseif ($_FILES[$_POST['upload_type']]['size'] > (config_value(11063) * 1024 * 1024)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . config_value(11063) . ' MB.',
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

        //Return the CDN uploader results:
        return echo_json(upload_to_cdn($temp_local, 0, $_FILES[$_POST['upload_type']], true));

    }


    function en_load_next_page()
    {

        $items_per_page = config_value(11064);
        $parent_en_id = intval($_POST['parent_en_id']);
        $en_focus_filter = intval($_POST['en_focus_filter']);
        $page = intval($_POST['page']);
        $filters = array(
            'ln_parent_entity_id' => $parent_en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
            'en_status_entity_id IN (' . join(',', ( $en_focus_filter<0 /* Remove Filters*/ ? $this->config->item('en_ids_7358') /* Entity Statuses Active */ : array($en_focus_filter) /* This specific filter*/ )) . ')' => null,
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        );

        //Fetch & display next batch of children, ordered by en_trust_score DESC which is aligned with other entity ordering:
        $child_entities = $this->READ_model->ln_fetch($filters, array('en_child'), $items_per_page, ($page * $items_per_page), array(
            'ln_order' => 'ASC',
            'en_name' => 'ASC'
        ));

        foreach ($child_entities as $en) {
            echo echo_en($en,false);
        }

        //Count total children:
        $child_entities_count = $this->READ_model->ln_fetch($filters, array('en_child'), 0, 0, array(), 'COUNT(ln_id) as totals');

        //Do we need another load more button?
        if ($child_entities_count[0]['totals'] > (($page * $items_per_page) + count($child_entities))) {
            echo echo_en_load_more(($page + 1), $items_per_page, $child_entities_count[0]['totals']);
        }

    }


    function en_add_or_link()
    {

        //Auth user and check required variables:
        $session_en = en_auth(10939 /* HONEY BADGER */);

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
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
        $current_en = $this->PLAY_model->en_fetch(array(
            'en_id' => $_POST['en_id'],
        ));
        if (count($current_en) < 1) {
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
            $ens = $this->PLAY_model->en_fetch(array(
                'en_id' => $_POST['en_existing_id'],
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
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
                $url_entity = $this->PLAY_model->en_sync_url($_POST['en_new_string']);
                if (!$url_entity['status']) {
                    return echo_json($url_entity);
                }

                //Is this a root domain? Add to domains if so:
                if($url_entity['url_is_root']){

                    //Link to domains parent:
                    $entity_new = array('en_id' => 1326);

                    //Update domain to stay synced:
                    $_POST['en_new_string'] = $url_entity['url_clean_domain'];

                } else {

                    //Let's first find/add the domain:
                    $domain_entity = $this->PLAY_model->en_sync_domain($_POST['en_new_string'], $session_en['en_id']);

                    //Link to this entity:
                    $entity_new = $domain_entity['en_domain'];
                }

            } else {

                //Create entity:
                $added_en = $this->PLAY_model->en_verify_create($_POST['en_new_string'], $session_en['en_id']);
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

                $ln_child_entity_id = $current_en[0]['en_id'];
                $ln_parent_entity_id = $entity_new['en_id'];

            } else {

                $ln_child_entity_id = $entity_new['en_id'];
                $ln_parent_entity_id = $current_en[0]['en_id'];

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
            $ur2 = $this->READ_model->ln_create(array(
                'ln_creator_entity_id' => $session_en['en_id'],
                'ln_type_entity_id' => $ln_type_entity_id,
                'ln_content' => $ln_content,
                'ln_child_entity_id' => $ln_child_entity_id,
                'ln_parent_entity_id' => $ln_parent_entity_id,
            ));
        }

        //Fetch latest version:
        $ens_latest = $this->PLAY_model->en_fetch(array(
            'en_id' => $entity_new['en_id'],
        ));

        //Return newly added or linked entity:
        return echo_json(array(
            'status' => 1,
            'en_new_status' => $ens_latest[0]['en_status_entity_id'],
            'en_new_echo' => echo_en(array_merge($ens_latest[0], $ur2), $_POST['is_parent']),
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
        $all_en_links = $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
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
        $session_en = en_auth(10939 /* HONEY BADGER */);
        $success_message = 'Saved'; //Default, might change based on what we do...

        //Fetch current data:
        $ens = $this->PLAY_model->en_fetch(array(
            'en_id' => intval($_POST['en_id']),
        ), array('en__parents'));

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
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
        } elseif (!isset($_POST['en_status_entity_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing status',
            ));
        } elseif (!isset($_POST['ln_id']) || !isset($_POST['ln_content']) || !isset($_POST['ln_status_entity_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity link data',
            ));
        } elseif (strlen($_POST['en_name']) > config_value(11072)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Name is longer than the allowed ' . config_value(11072) . ' characters. Shorten and try again.',
            ));
        } elseif(!isset($_POST['en_icon']) || !is_valid_icon($_POST['en_icon'])){
            //Check if valid icon:
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid icon: '. is_valid_icon(null, true),
            ));
        }

        $remove_redirect_url = null;
        $remove_from_ui = 0;
        $js_ln_type_entity_id = 0; //Detect link type based on content

        //Prepare data to be updated:
        $en_update = array(
            'en_name' => trim($_POST['en_name']),
            'en_icon' => trim($_POST['en_icon']),
            'en_status_entity_id' => intval($_POST['en_status_entity_id']),
        );

        //Is this being removed?
        if (!in_array($en_update['en_status_entity_id'], $this->config->item('en_ids_7358') /* Entity Statuses Active */) && !($en_update['en_status_entity_id'] == $ens[0]['en_status_entity_id'])) {


            //Make sure entity is not referenced in key DB reference fields:
            $en_count_references = en_count_references($_POST['en_id']);
            if(count($en_count_references) > 0){

                $en_all_6194 = $this->config->item('en_all_6194');

                //Construct the message:
                $error_message = 'Cannot be removed because entity is referenced as ';
                foreach($en_count_references as $en_id=>$en_count){
                    $error_message .= $en_all_6194[$en_id]['m_name'].' '.echo_number($en_count).' times ';
                }

                return echo_json(array(
                    'status' => 0,
                    'message' => $error_message,
                ));
            }



            //Count entity references in Intent Notes:
            $messages = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
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
                    $merged_ens = $this->PLAY_model->en_fetch(array(
                        'en_id' => $merger_en_id,
                        'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
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
            $links_adjusted = $this->PLAY_model->en_unlink($_POST['en_id'], $session_en['en_id'], $merger_en_id);

            //Show appropriate message based on action:
            if ($merger_en_id > 0) {

                if($_POST['en_id'] == $_POST['en_focus_id'] || $merged_ens[0]['en_id'] == $_POST['en_focus_id']){
                    //Entity is being Removed and merged into another entity:
                    $remove_redirect_url = '/play/' . $merged_ens[0]['en_id'];
                }

                $success_message = 'Entity removed and merged its ' . $links_adjusted . ' links here';

            } else {

                if($_POST['en_id'] == $_POST['en_focus_id']){
                    //Fetch parents to redirect to:
                    $remove_redirect_url = '/play' . (isset($ens[0]['en__parents'][0]['en_id']) ? '/' . $ens[0]['en__parents'][0]['en_id'] : '');
                }

                //Display proper message:
                $success_message = 'Entity and its ' . $links_adjusted . ' links removed successfully';

            }

        }


        if (intval($_POST['ln_id']) > 0) { //DO we have a link to update?

            //Yes, first validate entity link:
            $en_lns = $this->READ_model->ln_fetch(array(
                'ln_id' => $_POST['ln_id'],
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ));
            if (count($en_lns) < 1) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Entity Link ID',
                ));
            }


            //Status change?
            if($en_lns[0]['ln_status_entity_id']!=$_POST['ln_status_entity_id']){

                if (in_array($_POST['ln_status_entity_id'], $this->config->item('en_ids_7360') /* Link Statuses Active */)) {
                    $ln_status_entity_id = 10656; //Entity Link Iterated Status
                } else {
                    $remove_from_ui = 1;
                    $ln_status_entity_id = 10673; //Entity Link Unlinked
                }

                $this->READ_model->ln_update($_POST['ln_id'], array(
                    'ln_status_entity_id' => intval($_POST['ln_status_entity_id']),
                ), $session_en['en_id'], $ln_status_entity_id);
            }


            //Link content change?
            if ($en_lns[0]['ln_content'] == $_POST['ln_content']) {

                //Link content has not changed:
                $js_ln_type_entity_id = $en_lns[0]['ln_type_entity_id'];
                $ln_content = $en_lns[0]['ln_content'];

            } else {

                //Link content has changed:
                $detected_ln_type = ln_detect_type($_POST['ln_content']);

                if (!$detected_ln_type['status']) {

                    return echo_json($detected_ln_type);

                } elseif (in_array($detected_ln_type['ln_type_entity_id'], $this->config->item('en_ids_4537'))) {

                    //This is a URL, validate modification:

                    if ($detected_ln_type['url_is_root']) {

                        if ($en_lns[0]['ln_parent_entity_id'] == 1326) {

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

                        if ($en_lns[0]['ln_parent_entity_id'] == 1326) {

                            return echo_json(array(
                                'status' => 0,
                                'message' => 'Only domain URLs can be linked to Domain entity.',
                            ));

                        } elseif ($detected_ln_type['en_domain']) {
                            //We do have the domain mapped! Is this connected to the domain entity as its parent?
                            if ($detected_ln_type['en_domain']['en_id'] != $en_lns[0]['ln_parent_entity_id']) {
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


                $this->READ_model->ln_update($_POST['ln_id'], array(
                    'ln_content' => $ln_content,
                    'ln_creator_entity_id' => $session_en['en_id'],
                    'ln_timestamp' => date("Y-m-d H:i:s"),
                ), $session_en['en_id'], 10657 /* Entity Link Iterated Content */);


                //Also, did the link type change based on the content change?
                if($js_ln_type_entity_id!=$en_lns[0]['ln_type_entity_id']){
                    $this->READ_model->ln_update($_POST['ln_id'], array(
                        'ln_type_entity_id' => $js_ln_type_entity_id,
                    ), $session_en['en_id'], 10659 /* Entity Link Iterated Type */);
                }
            }
        }

        //Now update the DB:
        $this->PLAY_model->en_update(intval($_POST['en_id']), $en_update, true, $session_en['en_id']);


        //Reset user session data if this data belongs to the logged-in user:
        if ($_POST['en_id'] == $session_en['en_id']) {
            $ens = $this->PLAY_model->en_fetch(array(
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
            $lns = $this->READ_model->ln_fetch(array(
                'ln_id' => $_POST['ln_id'],
            ), array('ln_creator'));

            //Prep last updated:
            $return_array['ln_content'] = echo_ln_urls($ln_content, $js_ln_type_entity_id);
            $return_array['ln_content_final'] = $ln_content; //In case content was updated

        }

        //Show success:
        return echo_json($return_array);

    }




    function en_review_metadata($en_id){
        //Fetch Intent:
        $ens = $this->PLAY_model->en_fetch(array(
            'en_id' => $en_id,
        ));
        if(count($ens) > 0){
            echo_json(unserialize($ens[0]['en_metadata']));
        } else {
            echo 'Entity @'.$en_id.' not found!';
        }
    }

    function en_fetch_canonical_url(){

        //Auth user and check required variables:
        $session_en = en_auth(null);

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        } elseif (!isset($_POST['search_url']) || !filter_var($_POST['search_url'], FILTER_VALIDATE_URL)) {
            //This string was incorrectly detected as a URL by JS, return not found:
            return echo_json(array(
                'status' => 1,
                'url_already_existed' => 0,
            ));
        }

        //Fetch URL:
        $url_entity = $this->PLAY_model->en_sync_url($_POST['search_url']);

        if($url_entity['url_already_existed']){
            return echo_json(array(
                'status' => 1,
                'url_already_existed' => 1,
                'algolia_object' => update_algolia('en', $url_entity['en_url']['en_id'], 1),
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
        $session_en = en_auth(null);

        //Description type requirement:
        $contributor_type_requirement = array(4230, 4255); //Raw or Text string

        //Parent sources to be added:
        $parent_ens = array();

        //Load some config variables:
        $en_all_3000 = $this->config->item('en_all_3000');

        //Analyze domain:
        $domain_analysis = analyze_domain($_POST['source_url']);

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
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

            $detected_ln_type = ln_detect_type($this_parent_en['this_parent_en_desc']);

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
            $detected_role_ln_type = ln_detect_type($_POST['auth_role_' . $contributor_num]);

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
                $referenced_ens = $this->PLAY_model->en_fetch(array(
                    'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
                    'en_id' => $ln_en_link_id,
                ));
                if (count($referenced_ens) < 1) {
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Contributor #' . $contributor_num . ' entity ID @' . $ln_en_link_id . ' is invalid',
                    ));
                } elseif(count($this->READ_model->ln_fetch(array( //Make sure this entity is linked to industry experts:
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                        'ln_parent_entity_id' => 3084, //Industry Experts
                        'ln_child_entity_id' => $referenced_ens[0]['en_id'],
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    ))) == 0){
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Contributor #' . $contributor_num . ' is not linked to @3084 Industry Experts. If you believe '.$referenced_ens[0]['en_name'].' is an industry expert, first create a link to Industry Experts from <a href="/play/'.$referenced_ens[0]['en_id'].'" target="_blank"><b>here<i class="fas fa-external-link"></i></b></a> and then try saving this source again.',
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
                $contributor_url_entity = $this->PLAY_model->en_sync_url($_POST['ref_url_' . $contributor_num]);

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
                $detected_ln_type = ln_detect_type($_POST['why_expert_' . $contributor_num]);

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
                $sync_contributor = $this->PLAY_model->en_sync_url($_POST['ref_url_' . $contributor_num], $session_en['en_id'], array(), 0, $_POST['contributor_' . $contributor_num]);


                //Add contributor to People or Organizations entity:
                $this->READ_model->ln_create(array(
                    'ln_status_entity_id' => 6176, //Link Published
                    'ln_creator_entity_id' => $session_en['en_id'],
                    'ln_type_entity_id' => 4230, //Raw
                    'ln_parent_entity_id' => $_POST['entity_parent_id_' . $contributor_num], //People or Organizations
                    'ln_child_entity_id' => $sync_contributor['en_url']['en_id'],
                ), true);


                //Should we also link contributor to to Industry Experts entity?
                if (strlen($_POST['why_expert_' . $contributor_num]) > 0) {
                    //Add contributor to industry experts:
                    $this->READ_model->ln_create(array(
                        'ln_status_entity_id' => 6176, //Link Published
                        'ln_creator_entity_id' => $session_en['en_id'],
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
        $url_entity = $this->PLAY_model->en_sync_url($_POST['source_url'], $session_en['en_id'], array(), 0, $_POST['en_name']);
        if (!$url_entity['status']) {
            return echo_json($url_entity);
        }


        //Link content to all parent entities:
        foreach ($parent_ens as $this_parent_en) {
            //Insert new relation:
            $this->READ_model->ln_create(array(
                'ln_status_entity_id' => 6176, //Link Published
                'ln_creator_entity_id' => $session_en['en_id'],
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

        if($en_id < 0){
            //Gateway URL to give option to run...
            die('<a href="/play/cron__update_trust_score">Click here</a> to start running this function.');
        }

        //Algorithm Weights:
        $score_weights = array(
            'score_parent' => 5, //Score per each parent entity
            'score_children' => 2, //Score per each child entity
            'score_link' => 0.25, //Score per each link of any type and any status
            'score_trainer_words' => 0.10, // This is X where: 1 trainer credits = X score
        );

        //Fetch entities with/without filter:
        $ens = $this->PLAY_model->en_fetch(array(
            'en_id '.( $en_id > 0 ? '=' : '>=' ) => $en_id,
        ));

        //Fetch child entities:
        foreach ($ens as $en){

            //Calculate trust score:
            $score = 0;

            //Parents
            $en_parents = $this->READ_model->ln_fetch(array(
                'ln_child_entity_id' => $en['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
            $score += $en_parents[0]['totals'] * $score_weights['score_parent'];

            //Children:
            $en_children = $this->READ_model->ln_fetch(array(
                'ln_parent_entity_id' => $en['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
            $score += $en_children[0]['totals'] * $score_weights['score_children'];

            //READ HISTORY:
            $en_lns = $this->READ_model->ln_fetch(array(
                '(ln_parent_entity_id='.$en['en_id'].' OR ln_child_entity_id='.$en['en_id'].')' => null,
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
            $score += $en_lns[0]['totals'] * $score_weights['score_link'];

            //Mining credits:
            $en_trainer_words = $this->READ_model->ln_fetch(array(
                'ln_creator_entity_id' => $en['en_id'],
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ), array(), 0, 0, array(), 'SUM(ABS(ln_words)) as total_words');
            $score += $en_trainer_words[0]['total_words'] * $score_weights['score_trainer_words'];

            //Do we need to update?
            if($en['en_trust_score'] != $score){
                //Yes:
                $this->PLAY_model->en_update($en['en_id'], array(
                    'en_trust_score' => round($score, 0),
                ));
            }
        }

        echo 'Successfully updated trust score for '.count($ens).' entities.';
    }







    function singin_check_password(){

        if (!isset($_POST['login_en_id']) || intval($_POST['login_en_id'])<1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing user ID',
            ));
        } elseif (!isset($_POST['input_password']) || strlen($_POST['input_password']) < config_value(11066)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Password',
            ));
        } elseif (!isset($_POST['referrer_url'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing referrer URL',
            ));
        }



        //Validaye user ID
        $ens = $this->PLAY_model->en_fetch(array(
            'en_id' => $_POST['login_en_id'],
        ));
        if (!in_array($ens[0]['en_status_entity_id'], $this->config->item('en_ids_7357') /* Entity Statuses Public */)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Your account entity is not public. Contact us to adjust your account.',
            ));
        }

        //Authenticate password:
        $user_passwords = $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4255, //Text
            'ln_parent_entity_id' => 3286, //Password
            'ln_child_entity_id' => $ens[0]['en_id'],
        ));
        if (count($user_passwords) == 0) {
            //They do not have a password assigned yet!
            return echo_json(array(
                'status' => 0,
                'message' => 'An active login password has not been assigned to your account yet. You can assign a new password using the Forgot Password Button.',
            ));
        } elseif (!in_array($user_passwords[0]['ln_status_entity_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */)) {
            //They do not have a password assigned yet!
            return echo_json(array(
                'status' => 0,
                'message' => 'Password link is not public. Contact us to adjust your account.',
            ));
        } elseif ($user_passwords[0]['ln_content'] != hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password'] . $ens[0]['en_id'])) {
            //Bad password
            return echo_json(array(
                'status' => 0,
                'message' => 'Incorrect password',
            ));
        }


        //Assign session & log link:
        $this->PLAY_model->activate_session($ens[0]);


        if (isset($_POST['referrer_url']) && strlen($_POST['referrer_url']) > 0) {
            $login_url = urldecode($_POST['referrer_url']);
        } else {
            $login_url = '/read';
        }

        return echo_json(array(
            'status' => 1,
            'login_url' => $login_url,
        ));

    }

    function sign_reset_password_ui($ln_id){

        //Log all sessions out:
        $this->session->sess_destroy();

        //Make sure email input is provided:
        if(!isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
            //Missing email input:
            return redirect_message('/sign', '<div class="alert alert-danger" role="alert">Missing Email</div>');
        }

        //Validate link ID and matching email:
        $validate_links = $this->READ_model->ln_fetch(array(
            'ln_id' => $ln_id,
            'ln_content' => $_GET['email'],
            'ln_type_entity_id' => 7563, //User Signin Magic Link Email
        ), array('ln_creator')); //The user making the request

        if(count($validate_links) < 1){
            //Probably already completed the reset password:
            return redirect_message('/sign', '<div class="alert alert-danger" role="alert">Reset password link not found</div>');
        }

        $this->load->view('header', array(
            'hide_header' => 1,
            'title' => 'Reset Password',
        ));
        $this->load->view('view_play/password_reset', array(
            'validate_link' => $validate_links[0],
        ));
        $this->load->view('footer');

    }




    function sign_reset_password_apply()
    {

        //This function updates the user's new password as requested via a password reset:
        if (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1 || !isset($_POST['input_email']) || strlen($_POST['input_email']) < 1 || !isset($_POST['input_password'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        } elseif (strlen($_POST['input_password']) < config_value(11066)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Must be longer than '.config_value(11066).' characters',
            ));
        } else {

            //Validate link ID and matching email:
            $validate_links = $this->READ_model->ln_fetch(array(
                'ln_id' => $_POST['ln_id'],
                'ln_content' => $_POST['input_email'],
                'ln_type_entity_id' => 7563, //User Signin Magic Link Email
            )); //The user making the request
            if(count($validate_links) < 1){
                //Probably already completed the reset password:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Reset password link not found',
                ));
            }

            //Validate user:
            $ens = $this->PLAY_model->en_fetch(array(
                'en_id' => $validate_links[0]['ln_creator_entity_id'],
            ), array('skip_en__parents'));
            if(count($ens) < 1){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'User not found',
                ));
            }


            //Generate the password hash:
            $password_hash = hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password']. $ens[0]['en_id']);


            //Fetch their passwords to authenticate login:
            $user_passwords = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                'ln_parent_entity_id' => 3286, //Mench Sign In Password
                'ln_child_entity_id' => $ens[0]['en_id'],
            ));

            if (count($user_passwords) > 0) {

                $detected_ln_type = ln_detect_type($password_hash);
                if (!$detected_ln_type['status']) {
                    return echo_json($detected_ln_type);
                }

                //Update existing password:
                $this->READ_model->ln_update($user_passwords[0]['ln_id'], array(
                    'ln_content' => $password_hash,
                    'ln_type_entity_id' => $detected_ln_type['ln_type_entity_id'],
                ), $ens[0]['en_id'], 7578 /* User Iterated Password */);

            } else {

                //Create new password link:
                $this->READ_model->ln_create(array(
                    'ln_type_entity_id' => 4255, //Text link
                    'ln_content' => $password_hash,
                    'ln_parent_entity_id' => 3286, //Mench Password
                    'ln_creator_entity_id' => $ens[0]['en_id'],
                    'ln_child_entity_id' => $ens[0]['en_id'],
                ));

            }


            //Log password reset:
            $this->READ_model->ln_create(array(
                'ln_creator_entity_id' => $ens[0]['en_id'],
                'ln_type_entity_id' => 7578, //User Iterated Password
                'ln_content' => $password_hash, //A copy of their password set at this time
            ));


            //Log them in:
            $ens[0] = $this->PLAY_model->activate_session($ens[0]);

            //Their next intent in line:
            return echo_json(array(
                'status' => 1,
                'login_url' => '/read/next',
            ));


        }
    }


    function sign_create_account(){

        if (!isset($_POST['referrer_in_id']) || !isset($_POST['referrer_url'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        } elseif (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['input_name']) || strlen($_POST['input_name'])<1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing name',
                'focus_input_field' => 'input_name',
            ));
        }

        //Prep inputs & validate further:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));
        $_POST['input_name'] = trim($_POST['input_name']);
        $name_parts = explode(' ', trim($_POST['input_name']));
        if (strlen($_POST['input_name'])<5) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Full name must longer than 5 characters',
                'focus_input_field' => 'input_name',
            ));
        } elseif (!isset($name_parts[1])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'There must be a space between your your first and last name',
                'focus_input_field' => 'input_name',
            ));
        } elseif (strlen($name_parts[0])<2) {
            return echo_json(array(
                'status' => 0,
                'message' => 'First name must be 2 characters or longer',
                'focus_input_field' => 'input_name',
            ));
        } elseif (strlen($name_parts[1])<2) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Last name must be 2 characters or longer',
                'focus_input_field' => 'input_name',
            ));
        } elseif (strlen($_POST['input_name']) > config_value(11072)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Full name must be less than '.config_value(11072).' characters',
                'focus_input_field' => 'input_name',
            ));
        } elseif (!isset($_POST['new_password']) || strlen($_POST['new_password'])<1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing password',
                'focus_input_field' => 'new_password',
            ));
        } elseif (strlen($_POST['new_password']) < config_value(11066)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'New password must be '.config_value(11066).' characters or longer',
                'focus_input_field' => 'new_password',
            ));
        }



        //All good, create new entity:
        $user_en = $this->PLAY_model->en_verify_create(trim($_POST['input_name']), 0, 6181, random_user_icon());
        if(!$user_en['status']){
            //We had an error, return it:
            return echo_json($user_en);
        }


        //Create user links:
        $this->READ_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_parent_entity_id' => 4430, //Mench User
            'ln_creator_entity_id' => $user_en['en']['en_id'],
            'ln_child_entity_id' => $user_en['en']['en_id'],
        ));

        $this->READ_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_parent_entity_id' => 1278, //People
            'ln_creator_entity_id' => $user_en['en']['en_id'],
            'ln_child_entity_id' => $user_en['en']['en_id'],
        ));

        $this->READ_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_parent_entity_id' => 3504, //English Language (Since everything is in English so far)
            'ln_creator_entity_id' => $user_en['en']['en_id'],
            'ln_child_entity_id' => $user_en['en']['en_id'],
        ));
        $this->READ_model->ln_create(array(
            'ln_type_entity_id' => 4255, //Text link
            'ln_content' => trim(strtolower($_POST['input_email'])),
            'ln_parent_entity_id' => 3288, //Mench Email
            'ln_creator_entity_id' => $user_en['en']['en_id'],
            'ln_child_entity_id' => $user_en['en']['en_id'],
        ));
        $this->READ_model->ln_create(array(
            'ln_type_entity_id' => 4255, //Text link
            'ln_content' => strtolower(hash('sha256', $this->config->item('cred_password_salt') . $_POST['new_password'] . $user_en['en']['en_id'])),
            'ln_parent_entity_id' => 3286, //Mench Password
            'ln_creator_entity_id' => $user_en['en']['en_id'],
            'ln_child_entity_id' => $user_en['en']['en_id'],
        ));


        //Fetch referranl intent, if any:
        if(intval($_POST['referrer_in_id']) > 0){

            //Fetch the intent:
            $referrer_ins = $this->BLOG_model->in_fetch(array(
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'in_id' => $_POST['referrer_in_id'],
            ));

            //Add this intention to their Action Plan:
            $this->READ_model->read__intention_add($user_en['en']['en_id'], $_POST['referrer_in_id'], 0, false);

        } else {
            $referrer_ins = array();
        }


        ##Email Subject
        $subject =  ( count($referrer_ins) > 0 ? echo_in_outcome($referrer_ins[0]['in_outcome'], true).' with ' : 'Welcome to ' ) . 'MENCH';

        ##Email Body
        $html_message = '<div>Hi '.$name_parts[0].' 👋</div><br />';

        $html_message .= '<div>'.( count($referrer_ins) > 0 ? echo_in_outcome($referrer_ins[0]['in_outcome'], true) : 'Get started' ).':</div><br />';
        $actionplan_url = $this->config->item('base_url') . ( count($referrer_ins) > 0 ? 'actionplan/'.$referrer_ins[0]['in_id'] : '' );
        $html_message .= '<div><a href="'.$actionplan_url.'" target="_blank">' . $actionplan_url . '</a></div><br />';

        $html_message .= '<div>Connect on Messenger:</div><br />';
        $messenger_url = 'https://m.me/askmench' . ( count($referrer_ins) > 0 ? '?ref=' . $referrer_ins[0]['in_id'] : '' ) ;
        $html_message .= '<div><a href="'.$messenger_url.'" target="_blank">' . $messenger_url . '</a></div>';
        $html_message .= '<br /><br />';
        $html_message .= '<div>Cheers,</div><br />';
        $html_message .= '<div>Mench</div>';
        $html_message .= '<div><a href="https://mench.com?utm_source=mench&utm_medium=email&utm_campaign=signup" target="_blank">mench.com</a></div>';

        //Send Welcome Email:
        $email_log = $this->READ_model->dispatch_emails(array($_POST['input_email']), $subject, $html_message);

        //Log User Signin Joined Mench
        $invite_link = $this->READ_model->ln_create(array(
            'ln_type_entity_id' => 7562, //User Signin Joined Mench
            'ln_creator_entity_id' => $user_en['en']['en_id'],
            'ln_parent_intent_id' => intval($_POST['referrer_in_id']),
            'ln_metadata' => array(
                'email_log' => $email_log,
            ),
        ));

        //Assign session & log login link:
        $this->PLAY_model->activate_session($user_en['en']);


        if (strlen($_POST['referrer_url']) > 0) {
            $login_url = urldecode($_POST['referrer_url']);
        } elseif(intval($_POST['referrer_in_id']) > 0) {
            $login_url = '/actionplan/'.$_POST['referrer_in_id'];
        } else {
            //Go to home page and let them continue from there:
            $login_url = '/';
        }

        return echo_json(array(
            'status' => 1,
            'login_url' => $login_url,
        ));



    }

    function singin_magic_link_email(){


        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['referrer_in_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        //Cleanup/validate email:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));
        $user_emails = $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_content' => $_POST['input_email'],
            'ln_type_entity_id' => 4255, //Linked Entities Text (Email is text)
            'ln_parent_entity_id' => 3288, //Mench Email
        ), array('en_child'));
        if(count($user_emails) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Email not associated with a registered account',
            ));
        }

        //Log email search attempt:
        $reset_link = $this->READ_model->ln_create(array(
            'ln_type_entity_id' => 7563, //User Signin Magic Link Email
            'ln_content' => $_POST['input_email'],
            'ln_creator_entity_id' => $user_emails[0]['en_id'], //User making request
            'ln_parent_intent_id' => intval($_POST['referrer_in_id']),
        ));

        //This is a new email, send invitation to join:

        ##Email Subject
        $subject = 'Mench Login Magic Link';

        ##Email Body
        $html_message = '<div>Hi '.one_two_explode('',' ',$user_emails[0]['en_name']).' 👋</div><br /><br />';

        $magic_link_expiry_hours = (config_value(11065)/3600);
        $html_message .= '<div>Signin within '.$magic_link_expiry_hours.'-hour'.echo__s($magic_link_expiry_hours).':</div>';
        $magiclogin_url = 'https://mench.com/magiclogin/' . $reset_link['ln_id'] . '?email='.$_POST['input_email'];
        $html_message .= '<div><a href="'.$magiclogin_url.'" target="_blank">' . $magiclogin_url . '</a></div>';

        $password_reset_expiry_hours = ($this->config->item('password_reset_expiry')/3600);
        $html_message .= '<br /><br /><div>Or reset password within '.$password_reset_expiry_hours.'-hour'.echo__s($password_reset_expiry_hours).':</div>';
        $setpassword_url = 'https://mench.com/resetpassword/' . $reset_link['ln_id'] . '?email='.$_POST['input_email'];
        $html_message .= '<div><a href="'.$setpassword_url.'" target="_blank">' . $setpassword_url . '</a></div>';

        $html_message .= '<br /><br />';
        $html_message .= '<div>- <a href="https://mench.com?utm_source=mench&utm_medium=email&utm_campaign=resetpass" target="_blank">Mench</a></div>';

        //Send email:
        $this->READ_model->dispatch_emails(array($_POST['input_email']), $subject, $html_message);

        //Return success
        return echo_json(array(
            'status' => 1,
        ));
    }

    function singin_magic_link_login($ln_id){

        //Validate email:
        if(en_auth(null)){
            return redirect_message('/blog');
        } elseif(en_auth()){
            return redirect_message('/actionplan/next');
        } elseif(!isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
            //Missing email input:
            return redirect_message('/sign', '<div class="alert alert-danger" role="alert">Missing Email</div>');
        }

        //Validate link ID and matching email:
        $validate_links = $this->READ_model->ln_fetch(array(
            'ln_id' => $ln_id,
            'ln_content' => $_GET['email'],
            'ln_type_entity_id' => 7563, //User Signin Magic Link Email
        )); //The user making the request
        if(count($validate_links) < 1){
            //Probably already completed the reset password:
            return redirect_message('/sign?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert">Invalid data source</div>');
        } elseif(strtotime($validate_links[0]['ln_timestamp']) + config_value(11065) < time()){
            //Probably already completed the reset password:
            return redirect_message('/sign?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert">Magic link has expired. Try again.</div>');
        }

        //Fetch entity:
        $ens = $this->PLAY_model->en_fetch(array(
            'en_id' => $validate_links[0]['ln_creator_entity_id'],
        ));
        if(count($ens) < 1){
            return redirect_message('/sign?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert">User not found</div>');
        }

        //Log them in:
        $ens[0] = $this->PLAY_model->activate_session($ens[0]);

        //Take them to next step:
        return redirect_message( '/read/next' );
    }

    function singin_check_email(){

        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['referrer_in_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }


        //Cleanup input email:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));


        if(intval($_POST['referrer_in_id']) > 0){
            //Fetch the intent:
            $referrer_ins = $this->BLOG_model->in_fetch(array(
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'in_id' => $_POST['referrer_in_id'],
            ));
        } else {
            $referrer_ins = array();
        }


        //Search for email to see if it exists...
        $user_emails = $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_content' => $_POST['input_email'],
            'ln_type_entity_id' => 4255, //Linked Entities Text (Email is text)
            'ln_parent_entity_id' => 3288, //Mench Email
        ), array('en_child'));

        if(count($user_emails) > 0){

            return echo_json(array(
                'status' => 1,
                'email_existed_already' => 1,
                'login_en_id' => $user_emails[0]['en_id'],
                'clean_input_email' => $_POST['input_email'],
            ));

        } else {

            return echo_json(array(
                'status' => 1,
                'email_existed_already' => 0,
                'login_en_id' => 0,
                'clean_input_email' => $_POST['input_email'],
            ));

        }
    }



    function page_not_found(){
        $this->load->view('header', array(
            'title' => 'Page not found',
        ));
        $this->load->view('view_play/page_not_found');
        $this->load->view('footer');
    }



    function myaccount_radio_update()
    {
        /*
         *
         * Saves the radio selection of some account fields
         * that are displayed using echo_radio_entities()
         *
         * */

        if (!isset($_POST['en_creator_id']) || intval($_POST['en_creator_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid trainer ID',
            ));
        } elseif (!isset($_POST['parent_en_id']) || intval($_POST['parent_en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing parent entity',
            ));
        } elseif (!isset($_POST['selected_en_id']) || intval($_POST['selected_en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing selected entity',
            ));
        } elseif (!isset($_POST['enable_mulitiselect']) || !isset($_POST['was_already_selected'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing multi-select setting',
            ));
        }


        if(!$_POST['enable_mulitiselect'] || $_POST['was_already_selected']){
            //Since this is not a multi-select we want to remove all existing options...

            //Fetch all possible answers based on parent entity:
            $filters = array(
                'ln_parent_entity_id' => $_POST['parent_en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
            );

            if($_POST['enable_mulitiselect'] && $_POST['was_already_selected']){
                //Just remove this single item, not the other ones:
                $filters['ln_child_entity_id'] = $_POST['selected_en_id'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach($this->READ_model->ln_fetch($filters, array('en_child'), 0, 0) as $answer_en){
                array_push($possible_answers, $answer_en['en_id']);
            }

            //Remove selected options for this trainer:
            foreach($this->READ_model->ln_fetch(array(
                'ln_parent_entity_id IN (' . join(',', $possible_answers) . ')' => null,
                'ln_child_entity_id' => $_POST['en_creator_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            )) as $remove_en){
                //Should usually remove a single option:
                $this->READ_model->ln_update($remove_en['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                ), $_POST['en_creator_id'], 6224 /* User Account Updated */);
            }

        }

        //Add new option if not already there:
        if(!$_POST['enable_mulitiselect'] || !$_POST['was_already_selected']){
            $this->READ_model->ln_create(array(
                'ln_parent_entity_id' => $_POST['selected_en_id'],
                'ln_child_entity_id' => $_POST['en_creator_id'],
                'ln_creator_entity_id' => $_POST['en_creator_id'],
                'ln_type_entity_id' => 4230, //Raw
                'ln_status_entity_id' => 6176, //Link Published
            ));
        }


        //Log Account iteration link type:
        $_POST['account_update_function'] = 'myaccount_radio_update'; //Add this variable to indicate which My Account function created this link
        $this->READ_model->ln_create(array(
            'ln_creator_entity_id' => $_POST['en_creator_id'],
            'ln_type_entity_id' => 6224, //My Account Iterated
            'ln_content' => 'My Account '.( $_POST['enable_mulitiselect'] ? 'Multi-Select Radio Field ' : 'Single-Select Radio Field ' ).( $_POST['was_already_selected'] ? 'Removed' : 'Added' ),
            'ln_metadata' => $_POST,
            'ln_parent_entity_id' => $_POST['parent_en_id'],
            'ln_child_entity_id' => $_POST['selected_en_id'],
        ));

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => 'Updated', //Note: NOT shown in UI
        ));
    }


    function myaccount()
    {
        /*
         *
         * Loads user my account "frame" which would
         * then use JS/Facebook API to determine User
         * PSID which then loads their Account via
         * myaccount_load() function below.
         *
         * */

        $this->load->view('header', array(
            'title' => '👤 My Account',
        ));
        $this->load->view('view_play/myaccount_frame');
        $this->load->view('footer');
    }


    function myaccount_load($psid)
    {

        /*
         *
         * My Account Web UI used for both Messenger
         * Webview and web-browser login
         *
         * */

        //Authenticate user:
        $session_en = en_auth();
        if (!$psid && !isset($session_en['en_id'])) {
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif (!is_dev_environment() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Failed to authenticate your origin.</div>');
        } elseif (!isset($session_en['en_id'])) {
            //Messenger Webview, authenticate PSID:
            $session_en = $this->PLAY_model->en_messenger_auth($psid);
            //Make sure we found them:
            if (!$session_en) {
                //We could not authenticate the user!
                die('<div class="alert alert-danger" role="alert">Credentials could not be validated</div>');
            }
        }

        //Log My Account View:
        $this->READ_model->ln_create(array(
            'ln_type_entity_id' => 4282, //Opened My Account
            'ln_creator_entity_id' => $session_en['en_id'],
        ));

        //Load UI:
        $this->load->view('view_play/myaccount_manage', array(
            'session_en' => $session_en,
        ));

    }


    function signout()
    {
        //Destroys Session
        $this->session->sess_destroy();
        header('Location: /');
    }


    function myaccount_save_full_name()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['en_name']) || strlen($_POST['en_name']) < 2) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Name must be at-least 2 characters long',
            ));
        }

        //Cleanup:
        $_POST['en_name'] = trim($_POST['en_name']);

        //Check to make sure not duplicate:
        $duplicates = $this->PLAY_model->en_fetch(array(
            'en_id !=' => $_POST['en_id'],
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
            'LOWER(en_name)' => strtolower($_POST['en_name']),
        ));
        if (count($duplicates) > 0) {
            //This is a duplicate, disallow:
            return echo_json(array(
                'status' => 0,
                'message' => 'Name already in-use. Add a pre-fix or post-fix to make it unique.',
            ));
        }


        //Update name and notify
        $this->PLAY_model->en_update($_POST['en_id'], array(
            'en_name' => $_POST['en_name'],
        ), true, $_POST['en_id']);


        //Log Account iteration link type:
        $_POST['account_update_function'] = 'myaccount_save_full_name'; //Add this variable to indicate which My Account function created this link
        $this->READ_model->ln_create(array(
            'ln_creator_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 6224, //My Account Iterated
            'ln_content' => 'My Account Name Updated:'.$_POST['en_name'],
            'ln_metadata' => $_POST,
            'ln_child_entity_id' => $_POST['en_id'],
        ));

        return echo_json(array(
            'status' => 1,
            'message' => 'Name updated',
        ));
    }


    function myaccount_save_phone(){

        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['en_phone'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing phone number',
            ));
        } elseif (strlen($_POST['en_phone'])>0 && !is_numeric($_POST['en_phone'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid phone number: numbers only',
            ));
        } elseif (strlen($_POST['en_phone'])>0 && (strlen($_POST['en_phone'])<7 || strlen($_POST['en_phone'])>12)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Phone number must be between 7-12 characters long',
            ));
        }

        if (strlen($_POST['en_phone']) > 0) {

            //Cleanup starting 1:
            if (strlen($_POST['en_phone']) == 11) {
                $_POST['en_phone'] = preg_replace("/^1/", '',$_POST['en_phone']);
            }

            //Check to make sure not duplicate:
            $duplicates = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_entity_id' => 4319, //Phone are of type number
                'ln_parent_entity_id' => 4783, //Phone Number
                'ln_child_entity_id !=' => $_POST['en_id'],
                'ln_content' => $_POST['en_phone'],
            ));
            if (count($duplicates) > 0) {
                //This is a duplicate, disallow:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Phone already in-use. Use another number or contact support for assistance.',
                ));
            }
        }


        //Fetch existing phone:
        $user_phones = $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_child_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 4319, //Phone are of type number
            'ln_parent_entity_id' => 4783, //Phone Number
        ));
        if (count($user_phones) > 0) {

            if (strlen($_POST['en_phone']) == 0) {

                //Remove:
                $this->READ_model->ln_update($user_phones[0]['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                ), $_POST['en_id'], 6224 /* User Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Phone Removed',
                );

            } elseif ($user_phones[0]['ln_content'] != $_POST['en_phone']) {

                //Update if not duplicate:
                $this->READ_model->ln_update($user_phones[0]['ln_id'], array(
                    'ln_content' => $_POST['en_phone'],
                ), $_POST['en_id'], 6224 /* User Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Phone Updated',
                );

            } else {

                $return = array(
                    'status' => 0,
                    'message' => 'Phone Unchanged',
                );

            }

        } elseif (strlen($_POST['en_phone']) > 0) {

            //Create new link:
            $this->READ_model->ln_create(array(
                'ln_status_entity_id' => 6176, //Link Published
                'ln_creator_entity_id' => $_POST['en_id'],
                'ln_child_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 4319, //Phone are of type number
                'ln_parent_entity_id' => 4783, //Phone Number
                'ln_content' => $_POST['en_phone'],
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Phone Added',
            );

        } else {

            $return = array(
                'status' => 0,
                'message' => 'Phone Unchanged',
            );

        }


        //Log Account iteration link type:
        if($return['status']){
            $_POST['account_update_function'] = 'myaccount_save_phone'; //Add this variable to indicate which My Account function created this link
            $this->READ_model->ln_create(array(
                'ln_creator_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$return['message']. ( strlen($_POST['en_phone']) > 0 ? ': '.$_POST['en_phone'] : ''),
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));
        }

        return echo_json($return);

    }

    function myaccount_save_email()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['en_email']) || (strlen($_POST['en_email']) > 0 && !filter_var($_POST['en_email'], FILTER_VALIDATE_EMAIL))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        }


        if (strlen($_POST['en_email']) > 0) {
            //Cleanup:
            $_POST['en_email'] = trim(strtolower($_POST['en_email']));

            //Check to make sure not duplicate:
            $duplicates = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_entity_id' => 4255, //Emails are of type Text
                'ln_parent_entity_id' => 3288, //Mench Email
                'ln_child_entity_id !=' => $_POST['en_id'],
                'LOWER(ln_content)' => $_POST['en_email'],
            ));
            if (count($duplicates) > 0) {
                //This is a duplicate, disallow:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Email already in-use. Use another email or contact support for assistance.',
                ));
            }
        }


        //Fetch existing email:
        $user_emails = $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_child_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 4255, //Emails are of type Text
            'ln_parent_entity_id' => 3288, //Mench Email
        ));
        if (count($user_emails) > 0) {

            if (strlen($_POST['en_email']) == 0) {

                //Remove email:
                $this->READ_model->ln_update($user_emails[0]['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                ), $_POST['en_id'], 6224 /* User Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Email removed',
                );

            } elseif ($user_emails[0]['ln_content'] != $_POST['en_email']) {

                //Update if not duplicate:
                $this->READ_model->ln_update($user_emails[0]['ln_id'], array(
                    'ln_content' => $_POST['en_email'],
                ), $_POST['en_id'], 6224 /* User Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Email updated',
                );

            } else {

                $return = array(
                    'status' => 0,
                    'message' => 'Email unchanged',
                );

            }

        } elseif (strlen($_POST['en_email']) > 0) {

            //Create new link:
            $this->READ_model->ln_create(array(
                'ln_status_entity_id' => 6176, //Link Published
                'ln_creator_entity_id' => $_POST['en_id'],
                'ln_child_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 4255, //Emails are of type Text
                'ln_parent_entity_id' => 3288, //Mench Email
                'ln_content' => $_POST['en_email'],
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Email added',
            );

        } else {

            $return = array(
                'status' => 0,
                'message' => 'Email unchanged',
            );

        }


        if($return['status']){
            //Log Account iteration link type:
            $_POST['account_update_function'] = 'myaccount_save_email'; //Add this variable to indicate which My Account function created this link
            $this->READ_model->ln_create(array(
                'ln_creator_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$return['message']. ( strlen($_POST['en_email']) > 0 ? ': '.$_POST['en_email'] : ''),
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));
        }


        //Return results:
        return echo_json($return);


    }


    function myaccount_update_password()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['input_password']) || strlen($_POST['input_password']) < config_value(11066)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'New password must be '.config_value(11066).' characters or more',
            ));
        }


        //Fetch existing password:
        $user_passwords = $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4255, //Passwords are of type Text
            'ln_parent_entity_id' => 3286, //Password
            'ln_child_entity_id' => $_POST['en_id'],
        ));

        $hashed_password = strtolower(hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password'] . $_POST['en_id']));


        if (count($user_passwords) > 0) {

            if ($hashed_password == $user_passwords[0]['ln_content']) {

                $return = array(
                    'status' => 0,
                    'message' => 'Password Unchanged',
                );

            } else {

                //Update password:
                $this->READ_model->ln_update($user_passwords[0]['ln_id'], array(
                    'ln_content' => $hashed_password,
                ), $_POST['en_id'], 7578 /* User Iterated Password  */);

                $return = array(
                    'status' => 1,
                    'message' => 'Password Updated',
                );

            }

        } else {

            //Create new link:
            $this->READ_model->ln_create(array(
                'ln_status_entity_id' => 6176, //Link Published
                'ln_type_entity_id' => 4255, //Passwords are of type Text
                'ln_parent_entity_id' => 3286, //Password
                'ln_creator_entity_id' => $_POST['en_id'],
                'ln_child_entity_id' => $_POST['en_id'],
                'ln_content' => $hashed_password,
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Password Added',
            );

        }


        //Log Account iteration link type:
        if($return['status']){
            $_POST['account_update_function'] = 'myaccount_update_password'; //Add this variable to indicate which My Account function created this link
            $this->READ_model->ln_create(array(
                'ln_creator_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$return['message'],
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));
        }


        //Return results:
        return echo_json($return);

    }


    function myaccount_save_social_profiles()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['social_profiles']) || !is_array($_POST['social_profiles']) || count($_POST['social_profiles']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing social profiles',
            ));
        }

        $en_all_6123 = $this->config->item('en_all_6123');

        //Loop through and validate social profiles:
        $success_messages = '';
        foreach ($_POST['social_profiles'] as $social_profile) {


            //Validate to make sure either nothing OR URL:
            $social_en_id = intval($social_profile[0]);
            $social_url = trim($social_profile[1]);
            $profile_set = ( strlen($social_url) > 0 ? true : false );


            //This profile already added for this user, are we updating or removing?
            if ($profile_set) {

                //Valiodate URL and make sure it matches:
                $is_valid_url = false;
                if (filter_var($social_url, FILTER_VALIDATE_URL)) {
                    //Check to see if it's from the same domain and not in use:
                    $domain_entity = $this->PLAY_model->en_sync_domain($social_url);
                    if ($domain_entity['domain_already_existed'] && isset($domain_entity['en_domain']['en_id']) && $domain_entity['en_domain']['en_id'] == $social_en_id) {
                        //Seems to be a valid domain for this social profile:
                        $is_valid_url = true;
                    }
                }

                if (!$is_valid_url) {
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Invalid ' . $en_all_6123[$social_en_id]['m_name'] . ' URL',
                    ));
                }
            }


            //Does this user have a social URL already?
            $social_url_exists = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_entity_id' => 4256, //Generic URL
                'ln_parent_entity_id' => $social_en_id,
                'ln_child_entity_id' => $_POST['en_id'],
            ));

            if (count($social_url_exists) > 0) {

                //Make sure not for another entity:
                if ($social_url_exists[0]['ln_child_entity_id'] != $_POST['en_id']) {
                    return echo_json(array(
                        'status' => 0,
                        'message' => $en_all_6123[$social_en_id]['m_name'] . ' URL already taken by another entity.',
                    ));
                }

                //This profile already added for this user, are we updating or removing?
                if ($profile_set && $social_url_exists[0]['ln_content'] != $social_url) {

                    //Check to make sure not duplicate
                    $duplicates = $this->READ_model->ln_fetch(array(
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        'ln_type_entity_id' => 4256, //Generic URL
                        'ln_parent_entity_id' => $social_en_id,
                        'ln_child_entity_id !=' => $_POST['en_id'],
                        'ln_content' => $social_url,
                    ));
                    if(count($duplicates) > 0){
                        return echo_json(array(
                            'status' => 0,
                            'message' => 'Duplicates',
                        ));
                    }

                    //Update profile since different:
                    $this->READ_model->ln_update($social_url_exists[0]['ln_id'], array(
                        'ln_content' => $social_url,
                    ), $_POST['en_id'], 6224 /* User Account Updated */);

                    $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' Updated. ';

                } elseif(!$profile_set) {

                    //Remove profile:
                    $this->READ_model->ln_update($social_url_exists[0]['ln_id'], array(
                        'ln_status_entity_id' => 6173, //Link Removed
                    ), $_POST['en_id'], 6224 /* User Account Updated */);

                    $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' Removed. ';

                } else {



                }

            } elseif ($profile_set) {

                //Create new link:
                $this->READ_model->ln_create(array(
                    'ln_status_entity_id' => 6176, //Link Published
                    'ln_creator_entity_id' => $_POST['en_id'],
                    'ln_child_entity_id' => $_POST['en_id'],
                    'ln_type_entity_id' => 4256, //Generic URL
                    'ln_parent_entity_id' => $social_en_id,
                    'ln_content' => $social_url,
                ), true);

                $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' Added. ';

            }
        }

        if(strlen($success_messages) > 0){

            //Log Account iteration link type:
            $_POST['account_update_function'] = 'myaccount_save_social_profiles'; //Add this variable to indicate which My Account function created this link
            $this->READ_model->ln_create(array(
                'ln_creator_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$success_messages,
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));

            //All good, return combined success messages:
            return echo_json(array(
                'status' => 1,
                'message' => $success_messages,
            ));

        } else {

            //All good, return combined success messages:
            return echo_json(array(
                'status' => 0,
                'message' => 'Social Profiles Unchanged',
            ));

        }



    }



    function update_counters(){

        //Actually count PLAYERS:
        $en_count = $this->PLAY_model->en_fetch(array(
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
        ), array(), 0, 0, array(), 'COUNT(en_id) as total_public_entities');

        $session_en = en_auth(null);
        if (!$session_en) {

            //COUNT WORDS BLOG/READ:
            $words_blog = $this->READ_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_10589')) . ')' => null, //BLOGGERS
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            ), array(), 0, 0, array(), 'SUM(ln_words) as total_words');

            $words_read = $this->READ_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_10590')) . ')' => null, //READERS
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            ), array(), 0, 0, array(), 'SUM(ln_words) as total_words');

        } else {

            //COUNT WORDS BLOG/READ:
            $words_blog = $this->READ_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_10589')) . ')' => null, //BLOGGERS
                'ln_creator_entity_id' => $session_en['en_id'],
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            ), array(), 0, 0, array(), 'SUM(ln_words) as total_words');

            $words_read = $this->READ_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_10590')) . ')' => null, //READERS
                'ln_creator_entity_id' => $session_en['en_id'],
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            ), array(), 0, 0, array(), 'SUM(ln_words) as total_words');

        }



        return echo_json(array(
            'intents' => array(
                'current_count' => number_format($words_blog[0]['total_words'], 0),
            ),
            'entities' => array(
                'current_count' => number_format($en_count[0]['total_public_entities'], 0),
            ),
            'links' => array(
                'current_count' => number_format(abs($words_read[0]['total_words']), 0),
            )
        ));

    }




    function platform_cache(){

        /*
         *
         * This function prepares a PHP-friendly text to be copied to platform_cache.php
         * (which is auto loaded) to provide a cache image of some entities in
         * the tree for faster application processing.
         *
         * */

        //First first all entities that have Cache in PHP Config @4527 as their parent:
        $config_ens = $this->READ_model->ln_fetch(array(
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
            'ln_parent_entity_id' => 45270,
        ), array('en_child'), 0);

        echo htmlentities('<?php').'<br /><br />';
        echo 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'.'<br /><br />';

        echo '/*<br />
 * Keep a cache of certain parts of the Intent tree for faster processing<br />
 * So we don\'t have to make DB calls to figure them out every time!<br />
 * See here for all entities cached: https://mench.com/play/4527<br />
 * use-case format: $this->config->item(\'\')<br />
 *<br />
 * ATTENTION: Also search for "en_ids_" and "en_all_" when trying to manage these throughout the code base<br />
 *<br />
 */<br /><br />';
        echo '//Generated '.date("Y-m-d H:i:s").' PST<br />';


        foreach($config_ens as $en){

            //Now fetch all its children:
            $children = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
                'ln_parent_entity_id' => $en['ln_child_entity_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
            ), array('en_child'), 0, 0, array('ln_order' => 'ASC', 'en_name' => 'ASC'));


            $child_ids = array();
            foreach($children as $child){
                array_push($child_ids , $child['en_id']);
            }

            echo '<br />//'.$en['en_name'].':<br />';
            echo '$config[\'en_ids_'.$en['ln_child_entity_id'].'\'] = array('.join(',',$child_ids).');<br />';
            echo '$config[\'en_all_'.$en['ln_child_entity_id'].'\'] = array(<br />';
            foreach($children as $child){

                //Do we have an omit command?
                if(substr_count($en['ln_content'], '&trim=') == 1){
                    $trim_word = strtolower(one_two_explode('&trim=','',$en['ln_content']));
                    $trim_words = explode(' ', $trim_word);
                    $trim_check = 0;
                    $name_words = explode(' ', strtolower($child['en_name']));
                    foreach($name_words as $key => $value){
                        if($value==$trim_words[$trim_check]){
                            if(isset($trim_words[$trim_check+1])){
                                $trim_check++;
                            } else {
                                unset($name_words[$key]);
                                $trim_check = 0; //Reset counter
                            }
                        }
                    }

                    //Assign what's left:
                    $child['en_name'] = trim(join(' ', $name_words));
                }

                //Fetch all parents for this child:
                $child_parent_ids = array(); //To be populated soon
                $child_parents = $this->READ_model->ln_fetch(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
                    'ln_child_entity_id' => $child['en_id'],
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                ), array('en_parent'), 0);
                foreach($child_parents as $cp_en){
                    array_push($child_parent_ids, intval($cp_en['en_id']));
                }

                echo '&nbsp;&nbsp;&nbsp;&nbsp; '.$child['en_id'].' => array(<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_icon\' => \''.htmlentities($child['en_icon']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_name\' => \''.str_replace('\'','\\\'',$child['en_name']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_desc\' => \''.str_replace('\'','\\\'',$child['ln_content']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_parents\' => array('.join(',',$child_parent_ids).'),<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp; ),<br />';

            }
            echo ');<br />';
        }
    }




    function admin_tools($action = null, $command1 = null, $command2 = null)
    {

        boost_power();

        //Validate trainer:
        $session_en = en_auth(10967 /* BATMAN */, true);

        //Load tools:
        $this->load->view('header', array(
            'title' => 'Moderation Tools',
        ));

        $this->load->view('view_play/admin_tools' , array(
            'action' => $action,
            'command1' => $command1,
            'command2' => $command2,
            'session_en' => $session_en,
        ));

        $this->load->view('footer');

    }


}