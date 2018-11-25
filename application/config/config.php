<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Settime zone to PST:
date_default_timezone_set('America/Los_Angeles');

//Cache buster for static files
$config['app_version'] = '0.6095'.( ( isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='local.mench.co' ) ? microtime(true) : '' ); //Updates status css/js cache files

//User case: $this->config->item('timezones')

$config['primary_in_id'] = 6903; //The default platform intent that would be recommended to new students
$config['primary_en_id'] = 3463; //The default console entity that is loaded when Entities is clicked
$config['li_message_max'] = 610; //Max number of characters allowed in messages. Facebook's cap is 2000 characters/message
$config['max_counter'] = 999; //Used in counting things of engagements in console UI. If more that this will add a "+" sign to the end
$config['in_outcome_max'] = 89; //Max number of characters allowed in the title of intents
$config['en_name_max'] = 250; //Max number of characters allowed in the title of intents
$config['file_size_max'] = 25; //Server setting is 32MB. see here: mench.com/ses
$config['items_per_page'] = 50; //Even number
$config['in_points_options'] = array(0, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144, 233, 377, 610);
$config['exclude_es'] = array(1,2); //The engagements that should be ignored
$config['k_status_incomplete'] = array(1,0,-2); //The K statuses that indicate the task is not complete... Other statuses indicate completeness, see k_status below for more details

//This should mirror child intents of @3000, and the order of the items would be used in the Landing page:
$config['content_types'] = array(
    3147 => 'Online Course',
    3005 => 'Book',
    2999 => 'Podcast',
    2997 => 'Article',
    2998 => 'Video',
    3192 => 'Tool',
);

//Mench Facebook page & Bot settings:
$config['fb_max_message'] = 2000; //The maximum length of a Message accepted via Messenger API
$config['fb_settings'] = array(
    'page_id'        => '381488558920384', //Also repeated in global.js
    'app_id'        => '1782431902047009', //Also repeated in global.js
    'client_secret' => '05aea76d11b062951b40a5bee4251620',
    'default_graph_version' => 'v2.10', //Also repeated in global.js
    'mench_access_token' => 'EAAZAVHMRbmyEBAEfN8zsRJ3UOIUJJrNLqeFutPXVQZCoDZA3EO1rgkkzayMtNhisHHEhAos08AmKZCYD7zcZAPIDSMTcBjZAHxxWzbfWyTyp85Fna2bGDfv5JUIBuFTSeQOZBaDHRG7k0kbW8E7kQQN3W6x47VB1dZBPJAU1oNSW1QZDZD',
);

//Learn more: https://console.aws.amazon.com/iam/home?region=us-west-2#/users/foundation?section=security_credentials
$config['aws_credentials'] = [
    'key'    => 'AKIAJOLBLKFSYCCYYDRA',
    'secret' => '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',
];

//Email-based engagements subscriptions:
$config['notify_admins'] = array(
    1 => array(
        'admin_emails' => array('shervin@mench.com'),
        'subscription' => array(
            9, //User attention
            8, //System error
            10, //user login
            7703, //Search for New Intent Subscription
        ),
    ),
);

$config['transition'] = array(
    //Patternization Links
    20 => 4250, //Log intent creation
    6971 => 4251, //Log entity creation
    21 => 4252, //Log intent archived
    50 => 4254, //Log intent migration
    19 => 4264, //Log intent modification
    //0 => 4253, //Entity Archived (Did not have this!)

    36      => 4242, //Log intent message update
    7727    => 4242, //Log entity link note modification

    12      => 4263, //Log entity modification
    7001    => 4263, //Log cover photo set
    6924    => 4263, //Log cover photo removed

    89      => 4241, //Log intent unlinked
    7292    => 4241, //Log entity unlinked
    35      => 4241, //Log intent message archived
    6912    => 4241, //Log entity URL archived

    39      => 4262, //Log intent message sorting
    22      => 4262, //Log intent children sorted



    //Growth links
    27 => 4265, //Log user joined
    5 => 4266, //Log Messenger optin
    4 => 4267, //Log Messenger referral
    3 => 4268, //Log Messenger postback
    10 => 4269, //Log user sign in
    11 => 4270, //Log user sign out
    59 => 4271, //Log user password reset


    //Personal Assistant links
    40 => 4273, //Log console tip read
    //0 => 4235, //Log new Action Plan intent [to be implemented]
    7703 => 4275, //Log subscription intent search
    28 => 4276, //Log user email sent
    6 => 4277, //Log message received
    1 => 4278, //Log message read
    2 => 4279, //Log message delivered
    7 => 4280, //Log message sent
    52 => 4281, //Log message queued
    55 => 4282, //Log my account access
    32 => 4283, //Log action plan access
    33 => 4242, //Log action plan intent completion [Link updated]
    7730 => 4284, //Log Skip Initiation
    7731 => 4285, //Log Skip Cancellation
    7732 => 4286, //Log Skip Confirmation
    7718 => 4287, //Log unrecognized message

    //Platform Operations Links:
    8 => 4246, //Log system bug
    9 => 4247, //Log user attention request
    72 => 4248, //Log user review
);


$config['object_statuses'] = array(

    'en_status' => array(
        -1 => array(
            's_name'  => 'Removed',
            's_desc'  => 'Entity has been removed',
            's_icon' => 'fas fa-trash-alt',
        ),
        0 => array(
            's_name'  => 'New',
            's_desc'  => 'Entity newly added and pending work',
            's_icon' => 'fal fa-plus-circle',
        ),
        1 => array(
            's_name'  => 'Working On',
            's_desc'  => 'Entity is accepted and its being patternized',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name'  => 'Syncing',
            's_desc'  => 'Entity is completed and live',
            's_icon' => 'fas fa-check-circle',
        ),
    ),
    'in_status' => array(
        -1 => array(
            's_name'  => 'Removed',
            's_desc'  => 'Intent has been archived and all its links has been removed',
            's_icon' => 'fas fa-trash-alt',
        ),
        0 => array(
            's_name'  => 'New',
            's_desc'  => 'Intent is newly added and pending completion',
            's_icon' => 'fal fa-plus-circle',
        ),
        1 => array(
            's_name'  => 'Working On',
            's_desc'  => 'Intent tree/messages are being patternized from the internet',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name'  => 'Syncing',
            's_desc'  => 'Intent is published live and ready to accept subscriptions',
            's_icon' => 'fas fa-check-circle',
        ),
        3 => array(
            's_name'  => 'Featured',
            's_desc'  => 'Intent is recommended on mench.com home page',
            's_icon' => 'fas fa-badge-check',
        ),
    ),
    'li_status' => array(
        -1 => array(
            's_name'  => 'Removed',
            's_desc'  => 'User decided to skip this link',
            's_icon' => 'fal fa-minus-square',
        ),
        0 => array(
            's_name'  => 'New',
            's_desc'  => 'New link pending acceptance',
            's_icon' => 'fal fa-square',
        ),
        1 => array(
            's_name'  => 'Working On',
            's_desc'  => 'Work has started and but some intents are pending completion',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name'  => 'Syncing',
            's_desc'  => 'Completed and ready for updates to be synced',
            's_icon' => 'fas fa-check-square',
        ),
        3 => array(
            's_name'  => 'Accomplished',
            's_desc'  => 'Intent accomplished as verified by Mench',
            's_icon' => 'fas fa-badge-check',
        ),
    ),

    'en_communication' => array(
        -1 => array(
            's_name'  => 'Unsubscribed',
            's_desc'  => 'User requested to be removed from Mench',
            's_icon' => 'fas fa-minus-circle',
        ),
        1 => array(
            's_name'  => 'Regular',
            's_fb_key'  => 'REGULAR',
            's_desc'  => 'Triggers sound & vibration',
            's_icon' => 'fas fa-bell',
        ),
        2 => array(
            's_name'  => 'Silent Push',
            's_fb_key'  => 'SILENT_PUSH',
            's_desc'  => 'Triggers on-screen notification only',
            's_icon' => 'fal fa-bell',
        ),
        3 => array(
            's_name'  => 'No Push',
            's_fb_key'  => 'NO_PUSH',
            's_desc'  => 'Does not trigger any notification',
            's_icon' => 'fas fa-bell-slash',
        ),
    ),



    'u' => array(
        -2 => array(
            's_name'  => 'Unsubscribed',
            's_desc'  => 'User requested to be removed from Mench',
            's_icon' => 'fas fa-minus-circle',
        ),
        -1 => array(
            's_name'  => 'Archived',
            's_desc'  => 'Entity has been removed',
            's_icon' => 'fas fa-trash-alt',
        ),
        0 => array(
            's_name'  => 'New',
            's_desc'  => 'Entity does not have any parents, yet',
            's_icon' => 'fal fa-plus-circle',
        ),
        1 => array(
            's_name'  => 'Working On',
            's_desc'  => 'Entity is accepted and its being patternized',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name'  => 'Published',
            's_desc'  => 'Entity is completed and live',
            's_icon' => 'fas fa-check-circle',
        ),
    ),
    'ur' => array(
        -1 => array(
            's_name'  => 'Archived',
            's_desc'  => 'Entity link is removed',
            's_icon' => 'fas fa-trash-alt',
        ),
        1 => array(
            's_name'  => 'Published',
            's_desc'  => 'Entity link is active',
            's_icon' => 'fas fa-check-circle',
        ),
    ),
    'c' => array(
        -1 => array(
            's_name'  => 'Archived',
            's_desc'  => 'Intent has been archived and all its links has been removed',
            's_icon' => 'fas fa-trash-alt',
        ),
        0 => array(
            's_name'  => 'New',
            's_desc'  => 'Intent does not have any parents, yet',
            's_icon' => 'fal fa-plus-circle',
        ),
        1 => array(
            's_name'  => 'Working On',
            's_desc'  => 'Intent tree/messages are being patternized from the internet',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name'  => 'Published',
            's_desc'  => 'Intent is published live and ready to accept subscriptions',
            's_icon' => 'fas fa-check-circle',
        ),
        3 => array(
            's_name'  => 'Featured',
            's_desc'  => 'Intent is recommended on mench.com home page',
            's_icon' => 'fas fa-badge-check',
        ),
    ),
    'cr_status' => array(
        -1 => array(
            's_name'  => 'Archived',
            's_desc'  => 'Remove Intent link',
            's_icon' => 'fas fa-trash-alt',
        ),
        1 => array(
            's_name'  => 'Published',
            's_desc'  => 'Intent link published and added to user Action Plans up-front',
            's_icon' => 'fas fa-check-circle',
        ),
        2 => array(
            's_name'  => 'Conditional',
            's_desc'  => 'Intent added to Action Plans after parent intent is complete AND the user\'s % score falls within the defined min/max range',
            's_icon' => 'fas fa-question-circle fa-spin',
        ),
    ),

    'c_is_any' => array(
        0 => array(
            's_name'  => 'All Children',
            's_desc'  => 'Intent is complete when all children are marked as complete',
            's_icon' => 'fas fa-sitemap',
        ),
        1 => array(
            's_name'  => 'Any Child',
            's_desc'  => 'Intent is complete when a single child is marked as complete',
            's_icon' => 'fas fa-code-merge',
        ),
    ),

    'i_status' => array(
        -1 => array(
            's_name'  => 'Archived',
            's_desc'  => 'Message removed',
            's_icon' => 'fas fa-trash-alt',
        ),
        1 => array(
            's_name'  => 'On-Start',
            's_desc'  => 'Initial messages with instructions on how to effectively complete this intent',
            's_icon' => 'fas fa-bolt',
        ),
        2 => array(
            's_name'  => 'Extra Note',
            's_desc'  => 'Extra messages that student can receive, or else would be dripped in intervals after students complete intent to re-iterate key insights & retain learnings',
            's_icon' => 'fas fa-comment-exclamation',
        ),
        3 => array(
            's_name'  => 'On-Complete',
            's_desc'  => 'Messages sent when intent is complete to re-iterate key insights & retain learnings',
            's_icon' => 'fas fa-calendar-check',
        ),
    ),


    'k_status' => array(
        -2 => array(
            's_name'  => 'Revision Needed',
            's_desc'  => 'Mench moderator has reviewed submission and recommends additional work to better accomplish intent outcome',
            's_icon' => 'fas fa-exclamation-square',
        ),
        -1 => array(
            's_name'  => 'Skipped',
            's_desc'  => 'Student skipped intent',
            's_icon' => 'fal fa-minus-square',
        ),
        0 => array(
            's_name'  => 'Not Started',
            's_desc'  => 'Pending completion',
            's_icon' => 'fal fa-square',
        ),
        1 => array(
            's_name'  => 'Working On',
            's_desc'  => 'Work has started and but some intents are pending completion',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name'  => 'Completed',
            's_desc'  => 'Intent is complete',
            's_icon' => 'fas fa-check-square',
        ),
        3 => array(
            's_name'  => 'Approved',
            's_desc'  => 'Reviewed and approved by Mench moderator',
            's_icon' => 'fas fa-badge-check',
        ),
    ),

     'w_status' => array(
        -1 => array(
            's_name'  => 'Skipped',
            's_desc'  => 'User skipped their Action Plan',
            's_icon' => 'fas fa-minus-circle',
        ),
        0 => array(
            's_name'  => 'Suggested',
            's_desc'  => 'Intention has been recommended and pending user approval',
            's_icon' => 'fal fa-plus-circle',
        ),
        1 => array(
            's_name'  => 'Working On',
            's_desc'  => 'Work to accomplish intent has started and pending completion',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name'  => 'Syncing Updates',
            's_desc'  => 'All subscription intents are marked as complete and student is receiving updates from new changes happening to their subscription tree',
            's_icon' => 'fas fa-sync fa-spin',
        ),
        3 => array(
            's_name'  => 'Accomplished',
            's_desc'  => 'Student realized their intent and made it real',
            's_icon' => 'fas fa-badge-check',
        ),
    ),

    'u_fb_notification' => array(
        1 => array(
            's_name'  => 'Regular',
            's_fb_key'  => 'REGULAR',
            's_desc'  => 'Triggers sound & vibration',
            's_icon' => 'fas fa-bell',
        ),
        2 => array(
            's_name'  => 'Silent Push',
            's_fb_key'  => 'SILENT_PUSH',
            's_desc'  => 'Triggers on-screen notification only',
            's_icon' => 'fal fa-bell',
        ),
        3 => array(
            's_name'  => 'No Push',
            's_fb_key'  => 'NO_PUSH',
            's_desc'  => 'Does not trigger any notification',
            's_icon' => 'fas fa-bell-slash',
        ),
    ),


    'e_status' => array(
        0 => array(
            's_name'  => 'New',
            's_desc'  => 'New engagement is added and pending verification',
            's_icon' => 'fal fa-plus-circle',
        ),
        1 => array(
            's_name'  => 'Working On',
            's_desc'  => 'A cron job is processing this engagement',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name'  => 'Published',
            's_desc'  => 'Engagement is complete',
            's_icon' => 'fas fa-check-circle',
        ),
    ),


    'ur_note_type' => array(
        0 => array(
            's_name'  => 'Not Set',
            's_fb_key'  => null,
            's_desc'  => 'Entity link note not set',
            's_icon' => 'fas fa-link',
        ),
        1 => array(
            's_name'  => 'Text',
            's_fb_key'  => 'text',
            's_desc'  => 'Generic text',
            's_icon' => 'fas fa-link',
        ),
        2 => array(
            's_name'  => 'Web Page URL',
            's_fb_key'  => 'text',
            's_desc'  => 'URL point to a generic website on the internet',
            's_icon' => 'fas fa-link',
        ),
        3 => array(
            's_name'  => 'Embeddable URL',
            's_fb_key'  => 'text',
            's_desc'  => 'A recognized URL with an embeddable widget',
            's_icon' => 'fas fa-file-code',
        ),
        4 => array(
            's_name'  => 'Video URL',
            's_fb_key'  => 'video',
            's_desc'  => 'URL of a raw video file',
            's_icon' => 'fas fa-file-video',
        ),
        5 => array(
            's_name'  => 'Audio URL',
            's_fb_key'  => 'audio',
            's_desc'  => 'URL of a raw audio file',
            's_icon' => 'fas fa-file-audio',
        ),
        6 => array(
            's_name'  => 'Image URL',
            's_fb_key'  => 'image',
            's_desc'  => 'URL of a raw image file',
            's_icon' => 'fas fa-file-image',
        ),
        7 => array(
            's_name'  => 'File URL',
            's_fb_key'  => 'file',
            's_desc'  => 'URL of a raw generic file',
            's_icon' => 'fas fa-file-pdf',
        ),
    ),

    'x_type' => array(
        0 => array(
            's_name'  => 'Web Page',
            's_fb_key'  => 'text',
            's_desc'  => 'URL point to a generic website on the internet',
            's_icon' => 'fas fa-link',
        ),
        1 => array(
            's_name'  => 'Embeddable',
            's_fb_key'  => 'text',
            's_desc'  => 'A recognized URL with an embeddable widget',
            's_icon' => 'fas fa-file-code',
        ),
        2 => array(
            's_name'  => 'Video',
            's_fb_key'  => 'video',
            's_desc'  => 'URL of a raw video file',
            's_icon' => 'fas fa-file-video',
        ),
        3 => array(
            's_name'  => 'Audio',
            's_fb_key'  => 'audio',
            's_desc'  => 'URL of a raw audio file',
            's_icon' => 'fas fa-file-audio',
        ),
        4 => array(
            's_name'  => 'Image',
            's_fb_key'  => 'image',
            's_desc'  => 'URL of a raw image file',
            's_icon' => 'fas fa-file-image',
        ),
        5 => array(
            's_name'  => 'File',
            's_fb_key'  => 'file',
            's_desc'  => 'URL of a raw generic file',
            's_icon' => 'fas fa-file-pdf',
        ),
    ),

    'x_status' => array(
        -2 => array(
            's_name'  => 'Archived',
            's_desc'  => 'URL removed by User',
            's_icon' => 'fas fa-trash-alt',
        ),
        -1 => array(
            's_name'  => 'Seems Broken',
            's_desc'  => 'URL detected broken and pending moderator review',
            's_icon' => 'fas fa-exclamation-triangle',
        ),
        1 => array(
            's_name'  => 'Published',
            's_desc'  => 'URL is live and being distributed across Action Plans',
            's_icon' => 'fas fa-check-circle',
        ),
    ),
);


//The core objects of the platform:
$config['engagement_references'] = array(
    'e_parent_u_id' => array(
        'name' => 'Initiator Entity',
        'object_code' => 'u',
    ),
    'e_child_u_id' => array(
        'name' => 'Recipient Entity',
        'object_code' => 'u',
    ),
    'e_parent_c_id' => array(
        'name' => 'Engagement Type',
        'object_code' => 'c',
    ),
    'e_child_c_id' => array(
        'name' => 'Intent',
        'object_code' => 'c',
    ),
);


$config['timezones'] = array(
    '-11'       => "GMT-11:00 Midway Island, Samoa",
    '-10'       => "GMT-10:00 Hawaii",
    '-9'        => "GMT-09:00 Alaska",
    '-8'        => "GMT-08:00 Pacific Standard Time, Tijuana",
    '-7'        => "GMT-07:00 Arizona, Mountain Time, Chihuahua",
    '-6'        => "GMT-06:00 Central Time, Mexico City ",
    '-5'        => "GMT-05:00 Eastern Time, Indiana, Bogota, Lima",
    '-4.5'      => "GMT-04:30 Caracas",
    '-4'        => "GMT-04:00 Atlantic Time, La Paz, Santiago",
    '-3.5'      => "GMT-03:30 Newfoundland",
    '-3'        => "GMT-03:00 Buenos Aires, Greenland",
    '-2'        => "GMT-02:00 Stanley",
    '-1'        => "GMT-01:00 Azores, Cape Verde Is.",
    '0'         => "GMT 0:00 London, Dublin, Lisbon, Casablanca",
    '1'         => "GMT+01:00 Amsterdam, Berlin, Paris ",
    '2'         => "GMT+02:00 Athens, Istanbul, Jerusalem ",
    '3'         => "GMT+03:00 Moscow, Baghdad, Kuwait, Riyadh",
    '3.5'       => "GMT+03:30 Tehran",
    '4'         => "GMT+04:00 Baku, Volgograd, Muscat ",
    '4.5'       => "GMT+04:30 Kabul",
    '5'         => "GMT+05:00 Karachi, Tashkent, Kolkata ",
    '6'         => "GMT+06:00 Ekaterinburg, Almaty, Dhaka",
    '7'         => "GMT+07:00 Novosibirsk, Bangkok, Jakarta",
    '8'         => "GMT+08:00 Hong Kong, Perth, Singapore ",
    '9'         => "GMT+09:00 Irkutsk, Seoul, Tokyo",
    '9.5'       => "GMT+09:30 Adelaide, Darwin",
    '10'        => "GMT+10:00 Melbourne, Sydney, Guam ",
    '11'        => "GMT+11:00 Vladivostok",
    '12'        => "GMT+12:00 Magadan, Auckland, Fiji",
);

$config['languages'] = array(
    'en' => 'English' ,
    
    'ar' => 'Arabic' ,
    'aa' => 'Afar' ,
    'ab' => 'Abkhazian' ,
    'af' => 'Afrikaans' ,
    'am' => 'Amharic' ,
    'as' => 'Assamese' ,
    'ay' => 'Aymara' ,
    'az' => 'Azerbaijani' ,
    'sq' => 'Albanian' ,
    'hy' => 'Armenian' ,
    
    'ba' => 'Bashkir' ,
    'be' => 'Byelorussian' ,
    'bg' => 'Bulgarian' ,
    'bh' => 'Bihari' ,
    'bi' => 'Bislama' ,
    'bn' => 'Bengali' ,
    'br' => 'Breton' ,
    'dz' => 'Bhutani' ,
    'eu' => 'Basque' ,
    'my' => 'Burmese' ,
    
    'zh' => 'Chinese' ,
    'ca' => 'Catalan' ,
    'co' => 'Corsican' ,
    'cs' => 'Czech' ,
    'hr' => 'Croatian' ,
    'km' => 'Cambodian' ,
    
    'da' => 'Danish' ,
    'nl' => 'Dutch' ,
    
    'eo' => 'Esperanto' ,
    'et' => 'Estonian' ,
    
    'fa' => 'Farsi' ,
    'fi' => 'Finnish' ,
    'fj' => 'Fiji' ,
    'fo' => 'Faeroese' ,
    'fr' => 'French' ,
    'fy' => 'Frisian' ,
    
    'de' => 'German' ,
    'el' => 'Greek' ,
    'gl' => 'Galician' ,
    'gn' => 'Guarani' ,
    'gu' => 'Gujarati' ,
    'ka' => 'Georgian' ,
    'kl' => 'Greenlandic' ,
    
    'ha' => 'Hausa' ,
    'hi' => 'Hindi' ,
    'hu' => 'Hungarian' ,
    'iw' => 'Hebrew' ,
    
    'it' => 'Italian' ,
    'ga' => 'Irish' ,
    'ia' => 'Interlingua' ,
    'ie' => 'Interlingue' ,
    'ik' => 'Inupiak' ,
    'in' => 'Indonesian' ,
    'is' => 'Icelandic' ,
    
    'ja' => 'Japanese' ,
    'jw' => 'Javanese' ,
    
    'kk' => 'Kazakh' ,
    'rn' => 'Kirundi' ,
    'kn' => 'Kannada' ,
    'ko' => 'Korean' ,
    'ks' => 'Kashmiri' ,
    'ku' => 'Kurdish' ,
    'ky' => 'Kirghiz' ,
    'rw' => 'Kinyarwanda' ,
    
    'la' => 'Latin' ,
    'ln' => 'Lingala' ,
    'lo' => 'Laothian' ,
    'lt' => 'Lithuanian' ,
    'lv' => 'Latvian' ,
    
    'mg' => 'Malagasy' ,
    'mi' => 'Maori' ,
    'mk' => 'Macedonian' ,
    'ml' => 'Malayalam' ,
    'mn' => 'Mongolian' ,
    'mo' => 'Moldavian' ,
    'mr' => 'Marathi' ,
    'ms' => 'Malay' ,
    'mt' => 'Maltese' ,
    
    'na' => 'Nauru' ,
    'ne' => 'Nepali' ,
    'no' => 'Norwegian' ,
    
    'oc' => 'Occitan' ,
    'om' => 'Oromoor' ,
    
    'pa' => 'Punjabi' ,
    'pl' => 'Polish' ,
    'ps' => 'Pashto' ,
    'pt' => 'Portuguese' ,
    
    'qu' => 'Quechua' ,
    
    'rm' => 'Rhaeto-Romance' ,
    'ro' => 'Romanian' ,
    'ru' => 'Russian' ,
    
    'es' => 'Spanish' ,
    'gd' => 'Scots' ,
    'sa' => 'Sanskrit' ,
    'sd' => 'Sindhi' ,
    'sg' => 'Sangro' ,
    'sh' => 'Serbo-Croatian' ,
    'si' => 'Singhalese' ,
    'sk' => 'Slovak' ,
    'sl' => 'Slovenian' ,
    'sm' => 'Samoan' ,
    'sn' => 'Shona' ,
    'so' => 'Somali' ,
    'sr' => 'Serbian' ,
    'ss' => 'Siswati' ,
    'st' => 'Sesotho' ,
    'su' => 'Sundanese' ,
    'sv' => 'Swedish' ,
    'sw' => 'Swahili' ,
    'tn' => 'Setswana' ,
    
    'bo' => 'Tibetan' ,
    'ta' => 'Tamil' ,
    'te' => 'Tegulu' ,
    'tg' => 'Tajik' ,
    'th' => 'Thai' ,
    'ti' => 'Tigrinya' ,
    'tk' => 'Turkmen' ,
    'tl' => 'Tagalog' ,
    'to' => 'Tonga' ,
    'tr' => 'Turkish' ,
    'ts' => 'Tsonga' ,
    'tt' => 'Tatar' ,
    'tw' => 'Twi' ,
    
    'uk' => 'Ukrainian' ,
    'ur' => 'Urdu' ,
    'uz' => 'Uzbek' ,
    
    'vi' => 'Vietnamese' ,
    'vo' => 'Volapuk' ,
    
    'cy' => 'Welsh' ,
    'wo' => 'Wolof' ,
    
    'xh' => 'Xhosa' ,
    
    'yo' => 'Yoruba' ,
    'ji' => 'Yiddish' ,
    
    'zu' => 'Zulu' ,
);

$config['countries_all'] = array(
    "AF" => "Afghanistan",
    "AX" => "Aland Islands",
    "AL" => "Albania",
    "DZ" => "Algeria",
    "AS" => "American Samoa",
    "AD" => "Andorra",
    "AO" => "Angola",
    "AI" => "Anguilla",
    "AQ" => "Antarctica",
    "AG" => "Antigua and Barbuda",
    "AR" => "Argentina",
    "AM" => "Armenia",
    "AW" => "Aruba",
    "AU" => "Australia",
    "AT" => "Austria",
    "AZ" => "Azerbaijan",
    "BS" => "Bahamas",
    "BH" => "Bahrain",
    "BD" => "Bangladesh",
    "BB" => "Barbados",
    "BY" => "Belarus",
    "BE" => "Belgium",
    "BZ" => "Belize",
    "BJ" => "Benin",
    "BM" => "Bermuda",
    "BT" => "Bhutan",
    "BO" => "Bolivia",
    "BA" => "Bosnia and Herzegovina",
    "BW" => "Botswana",
    "BV" => "Bouvet Island",
    "BR" => "Brazil",
    "IO" => "British Indian Ocean",
    "BN" => "Brunei Darussalam",
    "BG" => "Bulgaria",
    "BF" => "Burkina Faso",
    "BI" => "Burundi",
    "KH" => "Cambodia",
    "CM" => "Cameroon",
    "CA" => "Canada",
    "CV" => "Cape Verde",
    "KY" => "Cayman Islands",
    "CF" => "Central African Republic",
    "TD" => "Chad",
    "CL" => "Chile",
    "CN" => "China",
    "CX" => "Christmas Island",
    "CC" => "Cocos Keeling Islands",
    "CO" => "Colombia",
    "KM" => "Comoros",
    "CG" => "Congo",
    "CD" => "Congo",
    "CK" => "Cook Islands",
    "CR" => "Costa Rica",
    "CI" => "Cote D'ivoire",
    "HR" => "Croatia",
    "CU" => "Cuba",
    "CY" => "Cyprus",
    "CZ" => "Czech Republic",
    "DK" => "Denmark",
    "DJ" => "Djibouti",
    "DM" => "Dominica",
    "DO" => "Dominican Republic",
    "EC" => "Ecuador",
    "EG" => "Egypt",
    "SV" => "El Salvador",
    "GQ" => "Equatorial Guinea",
    "ER" => "Eritrea",
    "EE" => "Estonia",
    "ET" => "Ethiopia",
    "FK" => "Falkland Islands",
    "FO" => "Faroe Islands",
    "FJ" => "Fiji",
    "FI" => "Finland",
    "FR" => "France",
    "GF" => "French Guiana",
    "PF" => "French Polynesia",
    "TF" => "French S. Territories",
    "GA" => "Gabon",
    "GM" => "Gambia",
    "GE" => "Georgia",
    "DE" => "Germany",
    "GH" => "Ghana",
    "GI" => "Gibraltar",
    "GR" => "Greece",
    "GL" => "Greenland",
    "GD" => "Grenada",
    "GP" => "Guadeloupe",
    "GU" => "Guam",
    "GT" => "Guatemala",
    "GG" => "Guernsey",
    "GN" => "Guinea",
    "GW" => "Guinea-bissau",
    "GY" => "Guyana",
    "HT" => "Haiti",
    "HM" => "Heard & Mcdonald Island",
    "VA" => "Vatican City State",
    "HN" => "Honduras",
    "HK" => "Hong Kong",
    "HU" => "Hungary",
    "IS" => "Iceland",
    "IN" => "India",
    "ID" => "Indonesia",
    "IR" => "Iran",
    "IQ" => "Iraq",
    "IE" => "Ireland",
    "IM" => "Isle of Man",
    "IL" => "Israel",
    "IT" => "Italy",
    "JM" => "Jamaica",
    "JP" => "Japan",
    "JE" => "Jersey",
    "JO" => "Jordan",
    "KZ" => "Kazakhstan",
    "KE" => "Kenya",
    "KI" => "Kiribati",
    "KP" => "Korea North",
    "KR" => "Korea South",
    "KW" => "Kuwait",
    "KG" => "Kyrgyzstan",
    "LA" => "Lao",
    "LV" => "Latvia",
    "LB" => "Lebanon",
    "LS" => "Lesotho",
    "LR" => "Liberia",
    "LY" => "Libyan Arab Jamahiriya",
    "LI" => "Liechtenstein",
    "LT" => "Lithuania",
    "LU" => "Luxembourg",
    "MO" => "Macao",
    "MK" => "Macedonia",
    "MG" => "Madagascar",
    "MW" => "Malawi",
    "MY" => "Malaysia",
    "MV" => "Maldives",
    "ML" => "Mali",
    "MT" => "Malta",
    "MH" => "Marshall Islands",
    "MQ" => "Martinique",
    "MR" => "Mauritania",
    "MU" => "Mauritius",
    "YT" => "Mayotte",
    "MX" => "Mexico",
    "FM" => "Micronesia",
    "MD" => "Moldova",
    "MC" => "Monaco",
    "MN" => "Mongolia",
    "ME" => "Montenegro",
    "MS" => "Montserrat",
    "MA" => "Morocco",
    "MZ" => "Mozambique",
    "MM" => "Myanmar",
    "NA" => "Namibia",
    "NR" => "Nauru",
    "NP" => "Nepal",
    "NL" => "Netherlands",
    "NC" => "New Caledonia",
    "NZ" => "New Zealand",
    "NI" => "Nicaragua",
    "NE" => "Niger",
    "NG" => "Nigeria",
    "NU" => "Niue",
    "NF" => "Norfolk Island",
    "MP" => "Northern Mariana Islands",
    "NO" => "Norway",
    "OM" => "Oman",
    "PK" => "Pakistan",
    "PW" => "Palau",
    "PS" => "Palestinian Territory",
    "PA" => "Panama",
    "PG" => "Papua New Guinea",
    "PY" => "Paraguay",
    "PE" => "Peru",
    "PH" => "Philippines",
    "PN" => "Pitcairn",
    "PL" => "Poland",
    "PT" => "Portugal",
    "PR" => "Puerto Rico",
    "QA" => "Qatar",
    "RE" => "Reunion",
    "RO" => "Romania",
    "RU" => "Russian Federation",
    "RW" => "Rwanda",
    "SH" => "Saint Helena",
    "KN" => "Saint Kitts and Nevis",
    "LC" => "Saint Lucia",
    "PM" => "Saint Pierre",
    "VC" => "Saint Vincent",
    "WS" => "Samoa",
    "SM" => "San Marino",
    "ST" => "Sao Tome and Principe",
    "SA" => "Saudi Arabia",
    "SN" => "Senegal",
    "RS" => "Serbia",
    "SC" => "Seychelles",
    "SL" => "Sierra Leone",
    "SG" => "Singapore",
    "SK" => "Slovakia",
    "SI" => "Slovenia",
    "SB" => "Solomon Islands",
    "SO" => "Somalia",
    "ZA" => "South Africa",
    "GS" => "South Georgia",
    "ES" => "Spain",
    "LK" => "Sri Lanka",
    "SD" => "Sudan",
    "SR" => "Suriname",
    "SJ" => "Svalbard and Jan Mayen",
    "SZ" => "Swaziland",
    "SE" => "Sweden",
    "CH" => "Switzerland",
    "SY" => "Syrian Arab Republic",
    "TW" => "Taiwan",
    "TJ" => "Tajikistan",
    "TZ" => "Tanzania",
    "TH" => "Thailand",
    "TL" => "Timor-leste",
    "TG" => "Togo",
    "TK" => "Tokelau",
    "TO" => "Tonga",
    "TT" => "Trinidad and Tobago",
    "TN" => "Tunisia",
    "TR" => "Turkey",
    "TM" => "Turkmenistan",
    "TC" => "Turks/Caicos Islands",
    "TV" => "Tuvalu",
    "UG" => "Uganda",
    "UA" => "Ukraine",
    "AE" => "United Arab Emirates",
    "GB" => "United Kingdom",
    "US" => "United States",
    "UY" => "Uruguay",
    "UZ" => "Uzbekistan",
    "VU" => "Vanuatu",
    "VE" => "Venezuela",
    "VN" => "Viet Nam",
    "VG" => "Virgin Islands, British",
    "VI" => "Virgin Islands, U.S.",
    "WF" => "Wallis and Futuna",
    "EH" => "Western Sahara",
    "YE" => "Yemen",
    "ZM" => "Zambia",
    "ZW" => "Zimbabwe"
);

/*
 |--------------------------------------------------------------------------
 | Base Site URL
 |--------------------------------------------------------------------------
 |
 | URL to your CodeIgniter root. Typically this will be your base URL,
 | WITH a trailing slash:
 |
 |	http://example.com/
 |
 | WARNING: You MUST set this value!
 |
 | If it is not set, then CodeIgniter will try guess the protocol and path
 | your installation, but due to security concerns the hostname will be set
 | to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
 | The auto-detection mechanism exists only for convenience during
 | development and MUST NOT be used in production!
 |
 | If you need to allow multiple domains, remember that this file is still
 | a PHP script and you can easily do that on your own.
 |
 */
$config['base_url'] = '';

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = 'index.php';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'REQUEST_URI' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'REQUEST_URI'    Uses $_SERVER['REQUEST_URI']
| 'QUERY_STRING'   Uses $_SERVER['QUERY_STRING']
| 'PATH_INFO'      Uses $_SERVER['PATH_INFO']
|
| WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
*/
$config['uri_protocol']	= 'REQUEST_URI';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/urls.html
*/
$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
| See http://php.net/htmlspecialchars for a list of supported charsets.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = FALSE;

/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/core_classes.html
| https://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
|
| Enabling this setting will tell CodeIgniter to look for a Composer
| package auto-loader script in application/vendor/autoload.php.
|
|	$config['composer_autoload'] = TRUE;
|
| Or if you have your vendor/ directory located somewhere else, you
| can opt to set a specific path as well:
|
|	$config['composer_autoload'] = '/path/to/vendor/autoload.php';
|
| For more information about Composer, please visit http://getcomposer.org/
|
| Note: This will NOT disable or override the CodeIgniter-specific
|	autoloading (application/config/autoload.php)
*/
$config['composer_autoload'] = FALSE;

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted within your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| The configured value is actually a regular expression character group
| and it will be executed as: ! preg_match('/^[<permitted_uri_chars>]+$/i
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| You can also pass an array with threshold levels to show individual error types
|
| 	array(2) = Debug Messages, without Error Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 1;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ directory. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Log File Extension
|--------------------------------------------------------------------------
|
| The default filename extension for log files. The default 'php' allows for
| protecting the log files via basic scripting, when they are to be stored
| under a publicly accessible directory.
|
| Note: Leaving it blank will default to 'php'.
|
*/
$config['log_file_extension'] = '';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/
$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
$config['error_views_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/cache/ directory.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Whether to take the URL query string into consideration when generating
| output cache files. Valid options are:
|
|	FALSE      = Disabled
|	TRUE       = Enabled, take all query parameters into account.
|	             Please be aware that this may result in numerous cache
|	             files generated for the same page over and over again.
|	array('q') = Enabled, but only take into account the specified list
|	             of query parameters.
|
*/
$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info.
|
| https://codeigniter.com/user_guide/libraries/encryption.html
|
*/
$config['encryption_key'] = '';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_driver'
|
|	The storage driver to use: files, database, redis, memcached
|
| 'sess_cookie_name'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| 'sess_expiration'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| 'sess_save_path'
|
|	The location to save sessions to, driver dependent.
|
|	For the 'files' driver, it's a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the 'database' driver, it's a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| 'sess_match_ip'
|
|	Whether to match the user's IP address when reading the session data.
|
|	WARNING: If you're using the database driver, don't forget to update
|	         your session table's PRIMARY KEY when changing this setting.
|
| 'sess_time_to_update'
|
|	How many seconds between CI regenerating the session ID.
|
| 'sess_regenerate_destroy'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix'   = Set a cookie name prefix if you need to avoid collisions
| 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
| 'cookie_path'     = Typically will be a forward slash
| 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
| 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
|
| Note: These settings (with the exception of 'cookie_prefix' and
|       'cookie_httponly') will also affect sessions.
|
*/
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;

/*
|--------------------------------------------------------------------------
| Standardize newlines
|--------------------------------------------------------------------------
|
| Determines whether to standardize newline characters in input data,
| meaning to replace \r\n, \r, \n occurrences with the PHP_EOL value.
|
| This is particularly useful for portability between UNIX-based OSes,
| (usually \n) and Windows (\r\n).
|
*/
$config['standardize_newlines'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
| 'csrf_regenerate' = Regenerate token on every submission
| 'csrf_exclude_uris' = Array of URIs which ignore CSRF checks
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| Only used if zlib.output_compression is turned off in your php.ini.
| Please do not use it together with httpd-level output compression.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or any PHP supported timezone. This preference tells
| the system whether to use your server's local time as the master 'now'
| reference, or convert it to the configured one timezone. See the 'date
| helper' page of the user guide for information regarding date handling.
|
*/
$config['time_reference'] = 'local';

/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
| Note: You need to have eval() enabled for this to work.
|
*/
$config['rewrite_short_tags'] = FALSE;

/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy
| IP addresses from which CodeIgniter should trust headers such as
| HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
| the visitor's IP address.
|
| You can use both an array or a comma-separated list of proxy addresses,
| as well as specifying whole subnets. Here are a few examples:
|
| Comma-separated:	'10.0.1.200,192.168.5.0/24'
| Array:		array('10.0.1.200', '192.168.5.0/24')
*/
$config['proxy_ips'] = '';
