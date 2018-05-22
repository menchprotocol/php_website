<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Settime zone to PST:
date_default_timezone_set('America/Los_Angeles');

//Primary website variables:
$config['website'] = array(
    'version' => '3.51',
    'name' => 'Mench',
    'url' => 'https://mench.com/', //Important to end with "/" as other links depend on this.
    'email' => 'shervin@mench.com',
);

$config['fb_settings'] = array(
    'app_id'        => '1782431902047009', //Also repeated in global.js
    'client_secret' => '05aea76d11b062951b40a5bee4251620',
    'default_graph_version' => 'v2.10', //Also repeated in global.js
);

$config['class_settings'] = array(
    'create_weeks_ahead'        => 55, //How many weeks ahead should we create classes?
    'instructor_show_default'   => 10, //Visible by default in Console for Instructors
    'landing_page_visible'      => 5, //Classes & Tasks visible in the Landing Page by default
    'students_show_max'         => 13, //Maximum available for students to see
);

$config['required_fb_permissions'] = array(
    'public_profile' => 'Basic permission granted by Facebook so we can access your profile\'s publicly available information.', //Basic permission
    'pages_show_list' => 'Enables us to list all Facebook Pages you manage so you can choose which one to connect to this Bootcamp.',
    'manage_pages' => 'Enables us to connect Mench Personal Assistant to the Facebook Page you choose. You can disconnect at any time.',
    'pages_messaging' => 'Enables us to send and receive messages through your Facebook Page. Cannot be used to send promotional/advertising content.',
    'pages_messaging_subscriptions' => 'Enables us to send messages to your Students at any time after the first interaction for Task notification/reminders.',
);

//Used to generate application status links:
$config['application_status_salt'] = 'SALTs3cr3t777';
$config['bot_activation_salt'] = 'S@LTB0Ts3cr3t4';
$config['file_limit_mb'] = 25; //Server setting is 32MB. see here: mench.com/ses

//That is auto added to all Bootcamp teams as Adviser role:
$config['message_max'] = 420; //Max number of characters allowed in messages

//Learn more: https://console.aws.amazon.com/iam/home?region=us-west-2#/users/foundation?section=security_credentials
$config['aws_credentials'] = [
    'key'    => 'AKIAJOLBLKFSYCCYYDRA',
    'secret' => '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',
];

$config['pricing_model'] = array(
    'baseline_rate' => 0.10, //Applied to all transactions, covers Transaction Fee
    'affiliate_rate' => 0.00, //Additional charge only if Mench refers student to Bootcamp    'p1_rates' => array(0.00,8.00,15.00), //Per Week

    'p1_rates' => array(-1,0,8,18), //Per Week 8,15
    'p1_rate_default' => 0,

    'p2_rates' => array(55), //Per Week 85
    'p2_rate_default' => 55,
    'p2_max_seats' => array(0,2,6,12,20,30,50,80,130), //Defines how many Guided students would an instructor accept into each Class
    'p2_seat_default' => 6,

    'p3_rates' => array(0,1.12,1.8), //Per Minute, Next level is 4.6
    'p3_rate_default' => 0,
);

$config['mench_support_team'] = array(1,2); //Miguel and Shervin @ This Time

//The engagements that instructors are subscribed to:
$config['instructor_subscriptions'] = array(57,60,68,69,70,72);

//Email-based engagements subscriptions:
$config['engagement_subscriptions'] = array(
    array(
        'admin_emails' => array('miguel@mench.com'),
        'subscription' => array(9,15,60,65,68,72,73,75,88 ),
    ),
    array(
        'admin_emails' => array('shervin@mench.com'),
        'subscription' => array(9,15,60,65,68,72,73,75,88,    8,84,6909),
    ),
);

//Define what counts as a meaningful Bootcamp engagement by the instructor team:
$config['meaningful_b_engagements']  = array(14,15,16,17,18,19,20,21,22,23,34,35,36,38,39,43,44,73,74,75);

//based on the fibonacci sequence for more realistic estimates
$config['c_time_options'] = array(0.05,0.116667,0.25,0.5,0.75,1,2,3,5); //,8,13

$config['default_class_prerequisites'] = array(
    'An internet-connected computer or smart-phone',
    'Fluent in English',
);

$config['object_statuses'] = array(
    'u' => array(
        -1 => array(
            's_name'  => 'Archived',
            's_desc'  => 'Entity has been removed',
            'limit_u_inbounds'  => array(1281), //Only admins can delete user accounts, or the user for their own account
            's_mini_icon' => 'fas fa-user-times',
        ),
        1 => array(
            's_name'  => 'Active',
            's_desc'  => 'Active entity',
            's_mini_icon' => 'fas fa-user',
            'limit_u_inbounds'  => array(1281), //Only admins can downgrade users from a leader status
        ),
    ),
    'b' => array(

        -1 => array(
            's_name'  => 'Archived',
            's_desc'  => 'Bootcamp no longer active',
            's_mini_icon' => 'fas fa-trash-alt',
        ),

        //The following two status ICONS are hard-coded in console.js for Algolia search
        2 => array(
            's_name'  => 'Published Privately',
            's_desc'  => 'Bootcamp open for admission for anyone who received the Private Landing Page URL',
            'limit_u_inbounds'  => array(1281), //Can only be done by admin
            's_mini_icon' => 'fas fa-cart-plus',
        ),
        3 => array(
            's_name'  => 'Mench Marketplace',
            's_desc'  => 'Bootcamp published on Mench.com Marketplace',
            'limit_u_inbounds'  => array(1281), //Can only be done by admin
            's_mini_icon' => 'fas fa-bullhorn',
        ),
    ),
    'df' => array(
        1 => array(
            's_name'  => 'Beginner',
            's_desc'  => 'No experience needed',
            's_mini_icon' => 'fas fa-thermometer-empty',
        ),
        2 => array(
            's_name'  => 'Intermediate',
            's_desc'  => 'Basic experience required',
            's_mini_icon' => 'fas fa-thermometer-half',
        ),
        3 => array(
            's_name'  => 'Advanced',
            's_desc'  => 'Practical experience required',
            's_mini_icon' => 'fas fa-thermometer-full',
        ),
    ),

    'ba' => array(
        -1 => array(
            's_name'  => 'Revoked',
            's_desc'  => 'Bootcamp access revoked',
            's_mini_icon' => 'fas fa-trash-alt',
        ),
        1 => array(
            's_name'  => 'Adviser',
            's_desc'  => 'Mench advisory team who extend your resources by reviewing and sharing feedback on ways to improve the Bootcamp configurations',
            's_mini_icon' => 'fas fa-comment-alt-smile',
            'limit_u_inbounds'  => array(1281), //For now this is NOT in use, just being hacked into the UI via team section of settings.php page view file
        ),
        2 => array(
            's_name'  => 'Co-Instructor',
            's_desc'  => 'Supports the lead instructor in Bootcamp operations based on specific privileges assigned to them',
            's_mini_icon' => 'fas fa-user-plus',
        ),
        3 => array(
            's_name'  => 'Lead Instructor',
            's_desc'  => 'The Bootcamp CEO who is responsible for the Bootcamp performance measured by its completion rate',
            's_mini_icon' => 'fas fa-star',
        ),
    ),
    'i' => array(
        -1 => array(
            's_name'  => 'Delete',
            's_desc'  => 'Message removed.',
            's_mini_icon' => 'fas fa-trash-alt',
        ),
        //No drafting for messages as it over-complicates things
        1 => array(
            's_name'  => 'On-Start',
            's_desc'  => 'Initial messages giving students instructions on how to effectively execute and complete this item',
            's_mini_icon' => 'fas fa-bolt',
        ),
        3 => array(
            's_name'  => 'On-Complete',
            's_desc'  => 'Messages sent when students complete this item. Re-iterate key insights to help students retain learnings',
            's_mini_icon' => 'fas fa-check-circle',
        ),
        2 => array(
            's_name'  => 'Drip',
            's_desc'  => 'Messages sent in intervals after students complete this item but before their Class ends. Re-iterate key insights to help students retain learnings',
            's_mini_icon' => 'fas fa-tint',
        ),
    ),
    'c' => array(
        -1 => array(
            's_name'  => 'Delete',
            's_desc'  => 'Item removed',
            's_mini_icon' => 'fas fa-minus-circle',
        ),
        0 => array(
            's_name'  => 'Drafting',
            's_desc'  => 'Step being drafted and not accessible by students until published live',
            's_mini_icon' => 'fas fa-pause-circle',
        ),
        1 => array(
            's_name'  => 'Published',
            's_desc'  => 'Step is active and accessible by students',
            's_mini_icon' => 'fas fa-clipboard-check',
        ),
    ),
    'cr' => array(
        -1 => array(
            's_name'  => 'Archived Link',
            's_desc'  => 'Step link removed',
            's_mini_icon' => 'fas fa-unlink',
        ),
        1 => array(
            's_name'  => 'Active Link',
            's_desc'  => 'Step link is active',
            's_mini_icon' => 'fas fa-link',
        ),
    ),
    'ex' => array(
        0 => array(
            's_name'  => 'Self',
            's_desc'  => 'Task does not have any child Steps and is complete when the Task its self is marked as complete',
            's_mini_icon' => 'fas fa-clipboard-check',
        ),
        1 => array(
            's_name'  => 'All Children',
            's_desc'  => 'Task is complete when all child Steps are completed',
            's_mini_icon' => 'fas fa-sitemap',
        ),
        2 => array(
            's_name'  => 'Any Child',
            's_desc'  => 'Task is complete when any child Step is completed',
            's_mini_icon' => 'fas fa-code-branch',
        ),
    ),

    'e_status' => array(
        -4 => array( //This does not exist in the DB and is manually invoked if item is not found in DB
            's_name'  => 'Pending Completion',
            's_desc'  => 'Item not yet completed',
            's_mini_icon' => 'fal fa-circle',
        ),
        -3 => array(
            's_name'  => 'Rejected',
            's_desc'  => 'Item was reviewed and rejected as it did not meet Mench guidelines',
            's_mini_icon' => 'fas fa-exclamation-circle',
        ),
        -2 => array(
            's_name'  => 'Processing',
            's_desc'  => 'Temporary status to prevent duplicate processing',
            's_mini_icon' => 'fas fa-spinner fa-spin',
        ),
        -1 => array(
            's_name'  => 'Auto Verified',
            's_desc'  => 'Item has been auto approved',
            's_mini_icon' => 'fal fa-check-circle',
        ),
        0 => array(
            's_name'  => 'Submitted',
            's_desc'  => 'Item submitted and pending verification',
            's_mini_icon' => 'fal fa-check-circle',
        ),
        1 => array(
            's_name'  => 'Verified',
            's_desc'  => 'Item has been processed and is now complete',
            's_mini_icon' => 'fas fa-check-circle',
        ),
    ),

    'r' => array(
        -3 => array(
            's_name'  => 'Cancelled',
            's_desc'  => 'Class cancelled after it had started, likely for reasons beyond the instructors control',
            'limit_u_inbounds'  => array(1281),
            's_mini_icon' => 'fas fa-times-circle',
        ),
        -2 => array(
            's_name'  => 'Expired',
            's_desc'  => 'Class start time passed without meeting the minimum student admission requirement',
            'limit_u_inbounds'  => array(1281),
            's_mini_icon' => 'fas fa-times-circle',
        ),
        1 => array(
            's_name'  => 'Open Admission',
            's_desc'  => 'Class is open for admission',
            's_mini_icon' => 'fas fa-cart-plus',
        ),
        2 => array(
            's_name'  => 'Class Running',
            's_desc'  => 'Class has admitted students and is currently running',
            'limit_u_inbounds'  => array(1281),
            's_mini_icon' => 'fas fa-play-circle',
        ),
        3 => array(
            's_name'  => 'Class Completed',
            's_desc'  => 'Class was operated completely until its last day',
            'limit_u_inbounds'  => array(1281),
            's_mini_icon' => 'fas fa-check-circle',
        ),
    ),
    'rs' => array(
        1 => array(
            's_name'  => 'Do It Yourself',
            's_desc'  => '- Step by Step Action Plan
- Peer Chat & Networking
- Notification & Reminders',
            's_mini_icon' => 'fas fa-wrench',
        ),
        2 => array(
            's_name'  => '1-on-1 Coaching (Upfront Payment)',
            's_desc'  => '- Get coaching from an Industry Expert
- 1-on-1 Chat Line & Email Support
- Assignment Review & Feedback
- Completion Certificate & LinkedIn Recommendation',
            's_mini_icon' => 'fas fa-whistle',
        ),
        3 => array(
            's_name'  => '1-on-1 Coaching (Deferred Payment)',
            's_desc'  => '- Get coaching from an Industry Expert
- 1-on-1 Chat Line & Email Support
- Assignment Review & Feedback
- Completion Certificate & LinkedIn Recommendation',
            's_mini_icon' => 'fas fa-whistle',
        ),

    ),

    'x_type' => array(
        0 => array(
            's_name'  => 'Web Page',
            's_fb_key'  => 'text',
            's_desc'  => 'URL point to a generic website on the internet',
            's_mini_icon' => 'fas fa-link',
        ),
        1 => array(
            's_name'  => 'Embeddable',
            's_fb_key'  => 'text',
            's_desc'  => 'A recognized URL with an embeddable widget',
            's_mini_icon' => 'fas fa-file-code',
        ),
        2 => array(
            's_name'  => 'Video File',
            's_fb_key'  => 'video',
            's_desc'  => 'URL of a raw video file',
            's_mini_icon' => 'fas fa-file-video',
        ),
        3 => array(
            's_name'  => 'Audio File',
            's_fb_key'  => 'audio',
            's_desc'  => 'URL of a raw audio file',
            's_mini_icon' => 'fas fa-file-audio',
        ),
        4 => array(
            's_name'  => 'Image File',
            's_fb_key'  => 'image',
            's_desc'  => 'URL of a raw image file',
            's_mini_icon' => 'fas fa-file-image',
        ),
        5 => array(
            's_name'  => 'Generic File',
            's_fb_key'  => 'file',
            's_desc'  => 'URL of a raw generic file',
            's_mini_icon' => 'fas fa-file-pdf',
        ),
    ),

    'x_status' => array(
        -2 => array(
            's_name'  => 'Deleted',
            's_desc'  => 'URL removed by User',
            's_mini_icon' => 'fas fa-trash-alt',
        ),
        -1 => array(
            's_name'  => 'Broken',
            's_desc'  => 'URL detected broken by MenchBot after several tries',
            's_mini_icon' => 'fas fa-unlink',
        ),
        1 => array(
            's_name'  => 'Seems Broken',
            's_desc'  => 'URL detected broken by MenchBot but not fully sure as we need to check again',
            's_mini_icon' => 'fas fa-unlink',
        ),
        2 => array(
            's_name'  => 'Live',
            's_desc'  => 'A URL Associated to the Entity',
            's_mini_icon' => 'fas fa-check-circle',
        ),
    ),


    'ru' => array(

        //Withrew after course has started:
        -3 => array(
            's_name'  => 'Student Removed',
            's_desc'  => 'Student removed from Class post-admission',
            's_mini_icon' => 'fas fa-times-hexagon',
        ),
        //Withrew prior to course has started:
        -2 => array(
            's_name'  => 'Student Withdrew',
            's_desc'  => 'Student withdrew before Class started',
            's_mini_icon' => 'fas fa-times-hexagon',
        ),

        0 => array(
            's_name'  => 'Admission Initiated',
            's_desc'  => 'Student initiated application but had not completed the checkout process',
            's_mini_icon' => 'fas fa-question-circle',
        ),
        4 => array(
            's_name'  => 'Admitted',
            's_desc'  => 'Student joined Class',
            's_mini_icon' => 'fas fa-user-circle',
        ),

        //Upon Class End Time:
        6 => array(
            's_name'  => 'Incomplete',
            's_desc'  => 'Student did not complete all Tasks by the last day of the Bootcamp',
            's_mini_icon' => 'fas fa-times-circle',
        ),
        7 => array(
            's_name'  => 'Completed',
            's_desc'  => 'Student successfully completed all Tasks by the Class end time and became a Bootcamp graduate',
            's_mini_icon' => 'fas fa-check-circle',
        ),
    ),

    't' => array(
        -2 => array(
            's_name'  => 'Instructor Payout',
            's_desc'  => 'Payment sent to instructors and affiliates',
            's_mini_icon' => 'fas fa-usd-circle',
        ),
        -1 => array(
            's_name'  => 'Refund Payout',
            's_desc'  => 'Transaction hold the exact amount of refund issues to the student',
            's_mini_icon' => 'fas fa-usd-circle',
        ),
        0 => array(
            's_name'  => 'Transaction Refunded',
            's_desc'  => 'Payment has been fully or partially refunded and a new transaction has been added to reflect the exact refund',
            's_mini_icon' => 'fas fa-usd-circle',
        ),
        1 => array(
            's_name'  => 'Payment Received',
            's_desc'  => 'Payment received from students for a class admission',
            's_mini_icon' => 'fas fa-usd-circle',
        ),
    ),
    'fp' => array(
        -1 => array(
            's_name'  => 'Archived',
            's_desc'  => 'Facebook Page not accessible by Mench',
            's_mini_icon' => 'fas fa-trash-alt',
        ),
        0 => array(
            's_name'  => 'Available',
            's_desc'  => 'Facebook Page available but not connected to a Mench Bootcamp yet',
            's_mini_icon' => 'fal fa-plug',
        ),
        1 => array(
            's_name'  => 'Connected',
            's_desc'  => 'Facebook Page connected to a Mench Bootcamp',
            's_mini_icon' => 'fas fa-plug',
        ),
    ),
    'fs' => array(
        -1 => array(
            's_name'  => 'Access Revoked',
            's_desc'  => 'Instructor is not authorized as the Facebook Page administrator',
            's_mini_icon' => 'fal fa-bookmark',
        ),
        1 => array(
            's_name'  => 'Access Authorized',
            's_desc'  => 'Instructor is an authorized Facebook Page administrator',
            's_mini_icon' => 'fas fa-bookmark',
        ),
    ),
);

//These URLs are recognized as Social Profiles
$config['social_urls'] =array(
    'https://www.facebook.com/'         => 'fab fa-facebook',
    'https://www.instagram.com/'        => 'fab fa-instagram',
    'https://twitter.com/'              => 'fab fa-twitter',
    'https://www.youtube.com/'          => 'fab fa-youtube',
    'https://www.linkedin.com/in/'      => 'fab fa-linkedin',
    'https://github.com/'               => 'fab fa-github',
    'https://join.skype.com/'           => 'fab fa-skype',
);


//No Bootcamps can be created using these hashtags
//URL structure is: https://mench.com/URLKEY
$config['reserved_hashtags'] = array(
    'projects',
    'bootcamp',
    'mench',
    'login',
    'logout',
    'user',
    'users',
    'account',
    'accounts',
    'profile',
    'profiles',
    'terms',
    'start',
    'launch',
    'contact',
    'contactus',
    'faq',
    'ses',
    'application_status',
    'application',
    'apply',
    'ref',
    'console',
    'help',
    'hashtag',
    'instructor',
    'instructors',
    'student',
    'students',
);

//The core objects of the platform:
$config['engagement_references'] = array(
    'e_inbound_u_id' => array(
        'name' => 'Initiator',
        'object_code' => 'u',
    ),
    'e_outbound_u_id' => array(
        'name' => 'Recipient',
        'object_code' => 'u',
    ),
    'e_b_id' => array(
        'name' => 'bootcamp',
        'object_code' => 'b',
    ),
    'e_r_id' => array(
        'name' => 'Class',
        'object_code' => 'r',
    ),
    'e_outbound_c_id' => array(
        'name' => 'Intent',
        'object_code' => 'c',
    ),
    'e_x_id' => array(
        'name' => 'Reference',
        'object_code' => 'x',
    ),
    'e_cr_id' => array(
        'name' => 'Intent Link',
        'object_code' => 'cr',
    ),
    'e_i_id' => array(
        'name' => 'Message',
        'object_code' => 'i',
    ),
    'e_fp_id' => array(
        'name' => 'Facebook Page',
        'object_code' => 'fp',
    ),

);


$config['timezones'] = array(
    '-11'       => "(GMT-11:00) Midway Island, Samoa",
    '-10'       => "(GMT-10:00) Hawaii",
    '-9'        => "(GMT-09:00) Alaska",
    '-8'        => "(GMT-08:00) Pacific Standard Time, Tijuana",
    '-7'        => "(GMT-07:00) Arizona, Mountain Time, Chihuahua",
    '-6'        => "(GMT-06:00) Central Time, Mexico City, etc...",
    '-5'        => "(GMT-05:00) Eastern Time, Indiana, Bogota, Lima",
    '-4.5'      => "(GMT-04:30) Caracas",
    '-4'        => "(GMT-04:00) Atlantic Time, La Paz, Santiago",
    '-3.5'      => "(GMT-03:30) Newfoundland",
    '-3'        => "(GMT-03:00) Buenos Aires, Greenland",
    '-2'        => "(GMT-02:00) Stanley",
    '-1'        => "(GMT-01:00) Azores, Cape Verde Is.",
    '0'         => "(GMT 0:00) London, Dublin, Lisbon, Casablanca",
    '1'         => "(GMT+01:00) Amsterdam, Berlin, Paris, etc...",
    '2'         => "(GMT+02:00) Athens, Istanbul, Jerusalem, etc...",
    '3'         => "(GMT+03:00) Moscow, Baghdad, Kuwait, Riyadh",
    '3.5'       => "(GMT+03:30) Tehran",
    '4'         => "(GMT+04:00) Baku, Volgograd, Muscat, etc...",
    '4.5'       => "(GMT+04:30) Kabul",
    '5'         => "(GMT+05:00) Karachi, Tashkent, Kolkata, etc...",
    '6'         => "(GMT+06:00) Ekaterinburg, Almaty, Dhaka",
    '7'         => "(GMT+07:00) Novosibirsk, Bangkok, Jakarta",
    '8'         => "(GMT+08:00) Hong Kong, Perth, Singapore, etc...",
    '9'         => "(GMT+09:00) Irkutsk, Seoul, Tokyo",
    '9.5'       => "(GMT+09:30) Adelaide, Darwin",
    '10'        => "(GMT+10:00) Melbourne, Sydney, Guam, etc...",
    '11'        => "(GMT+11:00) Vladivostok",
    '12'        => "(GMT+12:00) Magadan, Auckland, Fiji",
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
    "CC" => "Cocos (Keeling) Islands",
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
