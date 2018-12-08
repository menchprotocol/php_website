<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
 * Global variables used throughout the platform.
 * Ctrl+F is your friend to explore where they are implemented and how they work ;)
 * use-case format: $this->config->item('en_name_max')
 *
 */

date_default_timezone_set('America/Los_Angeles'); //Settime zone to PST

//Global app variables:
$config['app_version'] = '0.63'; //Cache buster in URLs for static js/css files
$config['password_salt'] = '40s96As9ZkdAcwQ9PhZm'; //Used for hashing the user password for Mench logins
$config['in_primary_id'] = 6903; //The default platform intent that would be recommended to new students
$config['primary_in_name'] = 'advance your tech career'; //What is the purposes of Mench at this point?
$config['primary_en_id'] = 3463; //The default console entity that is loaded when Entities is clicked
$config['tr_content_max'] = 610; //Max number of characters allowed in messages. Facebook's cap is 2000 characters/message
$config['max_counter'] = 999; //Used in counting things of engagements in console UI. If more that this will add a "+" sign to the end
$config['in_outcome_max'] = 89; //Max number of characters allowed in the title of intents
$config['in_seconds_max'] = 28800; //The maximum seconds allowed per intent. If larger, the trainer is asked to break it down into smaller intents
$config['en_name_max'] = 250; //Max number of characters allowed in the title of intents
$config['file_size_max'] = 25; //Server setting is 32MB. see here: mench.com/ses
$config['items_per_page'] = 50; //Even number
$config['fb_max_message'] = 2000; //The maximum length of a Message accepted via Messenger API
$config['in_points_options'] = array(-89, -55, -34, -21, -13, -8, -5, -3, -2, -1, 0, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144, 233, 377, 610);
$config['exclude_es'] = array(1, 2); //The engagements that should be ignored
$config['k_status_incomplete'] = array(1, 0, -2); //The K statuses that indicate the task is not complete...
$config['fb_settings'] = array(
    'page_id' => '381488558920384',
    'app_id' => '1782431902047009',
    'client_secret' => '05aea76d11b062951b40a5bee4251620',
    'default_graph_version' => 'v2.10', //Note: This variable also exists in the Facebook Library. Search "v2.10"
    'mench_access_token' => 'EAAZAVHMRbmyEBAEfN8zsRJ3UOIUJJrNLqeFutPXVQZCoDZA3EO1rgkkzayMtNhisHHEhAos08AmKZCYD7zcZAPIDSMTcBjZAHxxWzbfWyTyp85Fna2bGDfv5JUIBuFTSeQOZBaDHRG7k0kbW8E7kQQN3W6x47VB1dZBPJAU1oNSW1QZDZD',
);
$config['aws_credentials'] = [ //Learn more: https://console.aws.amazon.com/iam/home?region=us-west-2#/users/foundation?section=security_credentials
    'key' => 'AKIAJOLBLKFSYCCYYDRA',
    'secret' => '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',
];


/*
 * Keep a cache of certain parts of the Intent tree for faster processing
 * So we don't have to make DB calls to figure them out every time:
 *
 */
$config['en_child_4487'] = array(4331, 4332); //Intent Settings
$config['en_child_4486'] = array(4228, 4229); //Intent-to-Intent Links
$config['en_child_4485'] = array(4231, 4232, 4233, 4234); //Intent messages
$config['en_child_4227'] = array(4230, 4256, 4318, 4260, 4259, 4261, 4255, 4257, 4319, 4258); //All entity link types based on content types
$config['en_child_4454'] = array(4455, 4456, 4457, 4458); //All entity link types based on content types
$config['en_convert_4454'] = array( //Mench Communication Levels to Facebook Messenger format which is only supported if NOT unsubscribed:
    4456 => 'REGULAR',
    4457 => 'SILENT_PUSH',
    4458 => 'NO_PUSH',
);
$config['en_convert_3000'] = array( //This should mirror child intents of @3000, and the order of the items would be used in the Landing page:
    3147 => 'Online Course',
    3005 => 'Book',
    2999 => 'Podcast',
    2997 => 'Article',
    2998 => 'Video',
    3192 => 'Tool',
    4446 => 'Assessment',
);
$config['notify_admins'] = array( //Email-based engagements subscriptions
    1 => array(
        'admin_emails' => array('shervin@mench.com'),
        'admin_notify' => array(
            9, //User attention
            8, //System error
            10, //user login
            7703, //Search for New Intent Subscription
        ),
    ),
);
$config['object_statuses'] = array(
    'en_status' => array(
        -3 => array(
            's_name' => 'Flagged',
            's_desc' => 'Removed because it violated community guidelines',
            's_icon' => 'fal fa-minus-square',
        ),
        -2 => array(
            's_name' => 'Merged',
            's_desc' => 'Entity merged with another entity',
            's_icon' => 'fal fa-minus-square',
        ),
        -1 => array(
            's_name' => 'Removed',
            's_desc' => 'Entity has been removed',
            's_icon' => 'fas fa-trash-alt',
        ),
        0 => array(
            's_name' => 'New',
            's_desc' => 'Entity newly added and pending work',
            's_icon' => 'fal fa-plus-circle',
        ),
        1 => array(
            's_name' => 'Working On',
            's_desc' => 'Entity is accepted and its being patternized',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name' => 'Published',
            's_desc' => 'Entity is completed and live',
            's_icon' => 'fas fa-check-circle',
        ),
        3 => array(
            's_name' => 'Claimed',
            's_desc' => 'Entity is claimed by its owner',
            's_icon' => 'fas fa-badge-check',
        ),
    ),
    'in_status' => array(
        -3 => array(
            's_name' => 'Flagged',
            's_desc' => 'Removed because it violated community guidelines',
            's_icon' => 'fal fa-minus-square',
        ),
        -2 => array(
            's_name' => 'Merged',
            's_desc' => 'Intent merged with another intent',
            's_icon' => 'fal fa-minus-square',
        ),
        -1 => array(
            's_name' => 'Removed',
            's_desc' => 'Intent has been archived and all its links has been removed',
            's_icon' => 'fas fa-trash-alt',
        ),
        0 => array(
            's_name' => 'New',
            's_desc' => 'Intent is newly added and pending completion',
            's_icon' => 'fal fa-plus-circle',
        ),
        1 => array(
            's_name' => 'Working On',
            's_desc' => 'Intent tree/messages are being patternized from the internet',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name' => 'Published',
            's_desc' => 'Intent is published live and ready to accept subscriptions',
            's_icon' => 'fas fa-check-circle',
        ),
        3 => array(
            's_name' => 'Featured',
            's_desc' => 'Intent is recommended on mench.com home page',
            's_icon' => 'fas fa-badge-check',
        ),
    ),

    'tr_status' => array(
        -3 => array(
            's_name' => 'Flagged',
            's_desc' => 'Removed because it violated community guidelines',
            's_icon' => 'fal fa-minus-square',
        ),
        -2 => array(
            's_name' => 'Iterated',
            's_desc' => 'Content updated with newer content',
            's_icon' => 'fal fa-minus-square',
        ),
        -1 => array(
            's_name' => 'Removed',
            's_desc' => 'User decided to skip this link',
            's_icon' => 'fal fa-minus-square',
        ),
        0 => array(
            's_name' => 'New',
            's_desc' => 'New link pending acceptance',
            's_icon' => 'fal fa-square',
        ),
        1 => array(
            's_name' => 'Working On',
            's_desc' => 'Work has started and but some intents are pending completion',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name' => 'Published',
            's_desc' => 'Completed and ready for updates to be synced',
            's_icon' => 'fas fa-check-square',
        ),
        3 => array(
            's_name' => 'Verified',
            's_desc' => 'Intent successfully accomplished as verified by Mench', //The most precious link :)
            's_icon' => 'fas fa-badge-check',
        ),
    ),




    'u' => array(
        -1 => array(
            's_name' => 'Archived',
            's_desc' => 'Entity has been removed',
            's_icon' => 'fas fa-trash-alt',
        ),
        0 => array(
            's_name' => 'New',
            's_desc' => 'Entity does not have any parents, yet',
            's_icon' => 'fal fa-plus-circle',
        ),
        1 => array(
            's_name' => 'Working On',
            's_desc' => 'Entity is accepted and its being patternized',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name' => 'Published',
            's_desc' => 'Entity is completed and live',
            's_icon' => 'fas fa-check-circle',
        ),
    ),
    'ur' => array(
        -1 => array(
            's_name' => 'Archived',
            's_desc' => 'Entity link is removed',
            's_icon' => 'fas fa-trash-alt',
        ),
        1 => array(
            's_name' => 'Published',
            's_desc' => 'Entity link is active',
            's_icon' => 'fas fa-check-circle',
        ),
    ),
    'in' => array(
        -1 => array(
            's_name' => 'Archived',
            's_desc' => 'Intent has been archived and all its links has been removed',
            's_icon' => 'fas fa-trash-alt',
        ),
        0 => array(
            's_name' => 'New',
            's_desc' => 'Intent does not have any parents, yet',
            's_icon' => 'fal fa-plus-circle',
        ),
        1 => array(
            's_name' => 'Working On',
            's_desc' => 'Intent tree/messages are being patternized from the internet',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name' => 'Published',
            's_desc' => 'Intent is published live and ready to accept subscriptions',
            's_icon' => 'fas fa-check-circle',
        ),
        3 => array(
            's_name' => 'Featured',
            's_desc' => 'Intent is recommended on mench.com home page',
            's_icon' => 'fas fa-badge-check',
        ),
    ),

    'in_is_any' => array(
        0 => array(
            's_name' => 'All Children',
            's_desc' => 'Intent is complete when all children are marked as complete',
            's_icon' => 'fas fa-sitemap',
        ),
        1 => array(
            's_name' => 'Any Child',
            's_desc' => 'Intent is complete when a single child is marked as complete',
            's_icon' => 'fas fa-code-merge',
        ),
    ),

    'k_status' => array(
        -2 => array(
            's_name' => 'Revision Needed',
            's_desc' => 'Mench moderator has reviewed submission and recommends additional work to better accomplish intent outcome',
            's_icon' => 'fas fa-exclamation-square',
        ),
        -1 => array(
            's_name' => 'Skipped',
            's_desc' => 'Student skipped intent',
            's_icon' => 'fal fa-minus-square',
        ),
        0 => array(
            's_name' => 'Not Started',
            's_desc' => 'Pending completion',
            's_icon' => 'fal fa-square',
        ),
        1 => array(
            's_name' => 'Working On',
            's_desc' => 'Work has started and but some intents are pending completion',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name' => 'Completed',
            's_desc' => 'Intent is complete',
            's_icon' => 'fas fa-check-square',
        ),
        3 => array(
            's_name' => 'Approved',
            's_desc' => 'Reviewed and approved by Mench moderator',
            's_icon' => 'fas fa-badge-check',
        ),
    ),

    'w_status' => array(
        -1 => array(
            's_name' => 'Skipped',
            's_desc' => 'User skipped their Action Plan',
            's_icon' => 'fas fa-minus-circle',
        ),
        0 => array(
            's_name' => 'Suggested',
            's_desc' => 'Intention has been recommended and pending user approval',
            's_icon' => 'fal fa-plus-circle',
        ),
        1 => array(
            's_name' => 'Working On',
            's_desc' => 'Work to accomplish intent has started and pending completion',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name' => 'Syncing Updates',
            's_desc' => 'All subscription intents are marked as complete and student is receiving updates from new changes happening to their subscription tree',
            's_icon' => 'fas fa-sync fa-spin',
        ),
        3 => array(
            's_name' => 'Accomplished',
            's_desc' => 'Student realized their intent and made it real',
            's_icon' => 'fas fa-badge-check',
        ),
    ),

    'x_type' => array(
        0 => array(
            's_name' => 'Web Page',
            's_fb_key' => 'text',
            's_desc' => 'URL point to a generic website on the internet',
            's_icon' => 'fas fa-atlas',
        ),
        1 => array(
            's_name' => 'Embeddable',
            's_fb_key' => 'text',
            's_desc' => 'A recognized URL with an embeddable widget',
            's_icon' => 'fas fa-file-code',
        ),
        2 => array(
            's_name' => 'Video',
            's_fb_key' => 'video',
            's_desc' => 'URL of a raw video file',
            's_icon' => 'fas fa-file-video',
        ),
        3 => array(
            's_name' => 'Audio',
            's_fb_key' => 'audio',
            's_desc' => 'URL of a raw audio file',
            's_icon' => 'fas fa-file-audio',
        ),
        4 => array(
            's_name' => 'Image',
            's_fb_key' => 'image',
            's_desc' => 'URL of a raw image file',
            's_icon' => 'fas fa-file-image',
        ),
        5 => array(
            's_name' => 'File',
            's_fb_key' => 'file',
            's_desc' => 'URL of a raw generic file',
            's_icon' => 'fas fa-file-pdf',
        ),
    ),

    'x_status' => array(
        -2 => array(
            's_name' => 'Archived',
            's_desc' => 'URL removed by User',
            's_icon' => 'fas fa-trash-alt',
        ),
        -1 => array(
            's_name' => 'Seems Broken',
            's_desc' => 'URL detected broken and pending moderator review',
            's_icon' => 'fas fa-exclamation-triangle',
        ),
        1 => array(
            's_name' => 'Published',
            's_desc' => 'URL is live and being distributed across Action Plans',
            's_icon' => 'fas fa-check-circle',
        ),
    ),
);


$config['engagement_references'] = array( //The core objects of the platform:
    'tr_en_creator_id' => array(
        'name' => 'Initiator Entity',
        'object_code' => 'en',
    ),
    'tr_en_child_id' => array(
        'name' => 'Child Entity',
        'object_code' => 'en',
    ),
    'tr_en_parent_id' => array(
        'name' => 'Parent Entity',
        'object_code' => 'en',
    ),
    'tr_in_child_id' => array(
        'name' => 'Child Intent',
        'object_code' => 'in',
    ),
    'tr_in_parent_id' => array(
        'name' => 'Parent Intent',
        'object_code' => 'in',
    ),
);


//Copy/paste this into the config.php file and replace with old variable:

$config['en_metadata'] = array(
    'en_countries' => array(
        'ca' => 3923,
        'ax' => 3886,
        'al' => 3887,
        'dz' => 3888,
        'as' => 3889,
        'ad' => 3890,
        'ao' => 3891,
        'ai' => 3892,
        'aq' => 3893,
        'ag' => 3894,
        'ar' => 3895,
        'am' => 3896,
        'aw' => 3897,
        'au' => 3898,
        'at' => 3899,
        'az' => 3900,
        'bs' => 3901,
        'bh' => 3902,
        'bd' => 3903,
        'bb' => 3904,
        'by' => 3905,
        'be' => 3906,
        'bz' => 3907,
        'bj' => 3908,
        'bm' => 3909,
        'bt' => 3910,
        'bo' => 3911,
        'ba' => 3912,
        'bw' => 3913,
        'bv' => 3914,
        'br' => 3915,
        'io' => 3916,
        'bn' => 3917,
        'bg' => 3918,
        'bf' => 3919,
        'bi' => 3920,
        'kh' => 3921,
        'cm' => 3922,
        'cv' => 3924,
        'ky' => 3925,
        'cf' => 3926,
        'td' => 3927,
        'cl' => 3928,
        'cn' => 3929,
        'cx' => 3930,
        'cc' => 3931,
        'co' => 3932,
        'km' => 3933,
        'cg' => 3934,
        'cd' => 3935,
        'ck' => 3936,
        'cr' => 3937,
        'ci' => 3938,
        'hr' => 3939,
        'cu' => 3940,
        'cy' => 3941,
        'cz' => 3942,
        'dk' => 3943,
        'dj' => 3944,
        'dm' => 3945,
        'do' => 3946,
        'ec' => 3947,
        'eg' => 3948,
        'sv' => 3949,
        'gq' => 3950,
        'er' => 3951,
        'ee' => 3952,
        'et' => 3953,
        'fk' => 3954,
        'fo' => 3955,
        'fj' => 3956,
        'fi' => 3957,
        'fr' => 3958,
        'gf' => 3959,
        'pf' => 3960,
        'tf' => 3961,
        'ga' => 3962,
        'gm' => 3963,
        'ge' => 3964,
        'de' => 3965,
        'gh' => 3966,
        'gi' => 3967,
        'gr' => 3968,
        'gl' => 3969,
        'gd' => 3970,
        'gp' => 3971,
        'gu' => 3972,
        'gt' => 3973,
        'gg' => 3974,
        'gn' => 3975,
        'gw' => 3976,
        'gy' => 3977,
        'ht' => 3978,
        'hm' => 3979,
        'va' => 3980,
        'hn' => 3981,
        'hk' => 3982,
        'hu' => 3983,
        'is' => 3984,
        'in' => 3985,
        'id' => 3986,
        'ir' => 3987,
        'iq' => 3988,
        'ie' => 3989,
        'im' => 3990,
        'il' => 3991,
        'it' => 3992,
        'jm' => 3993,
        'jp' => 3994,
        'je' => 3995,
        'jo' => 3996,
        'kz' => 3997,
        'ke' => 3998,
        'ki' => 3999,
        'kp' => 4000,
        'kr' => 4001,
        'kw' => 4002,
        'kg' => 4003,
        'la' => 4004,
        'lv' => 4005,
        'lb' => 4006,
        'ls' => 4007,
        'lr' => 4008,
        'ly' => 4009,
        'li' => 4010,
        'lt' => 4011,
        'lu' => 4012,
        'mo' => 4013,
        'mk' => 4014,
        'mg' => 4015,
        'mw' => 4016,
        'my' => 4017,
        'mv' => 4018,
        'ml' => 4019,
        'mt' => 4020,
        'mh' => 4021,
        'mq' => 4022,
        'mr' => 4023,
        'mu' => 4024,
        'yt' => 4025,
        'mx' => 4026,
        'fm' => 4027,
        'md' => 4028,
        'mc' => 4029,
        'mn' => 4030,
        'me' => 4031,
        'ms' => 4032,
        'ma' => 4033,
        'mz' => 4034,
        'mm' => 4035,
        'na' => 4036,
        'nr' => 4037,
        'np' => 4038,
        'nl' => 4039,
        'nc' => 4040,
        'nz' => 4041,
        'ni' => 4042,
        'ne' => 4043,
        'ng' => 4044,
        'nu' => 4045,
        'nf' => 4046,
        'mp' => 4047,
        'no' => 4048,
        'om' => 4049,
        'pk' => 4050,
        'pw' => 4051,
        'ps' => 4052,
        'pa' => 4053,
        'pg' => 4054,
        'py' => 4055,
        'pe' => 4056,
        'ph' => 4057,
        'pn' => 4058,
        'pl' => 4059,
        'pt' => 4060,
        'pr' => 4061,
        'qa' => 4062,
        're' => 4063,
        'ro' => 4064,
        'ru' => 4065,
        'rw' => 4066,
        'sh' => 4067,
        'kn' => 4068,
        'lc' => 4069,
        'pm' => 4070,
        'vc' => 4071,
        'ws' => 4072,
        'sm' => 4073,
        'st' => 4074,
        'sa' => 4075,
        'sn' => 4076,
        'rs' => 4077,
        'sc' => 4078,
        'sl' => 4079,
        'sg' => 4080,
        'sk' => 4081,
        'si' => 4083,
        'sb' => 4084,
        'so' => 4085,
        'za' => 4086,
        'gs' => 4087,
        'es' => 4088,
        'lk' => 4089,
        'sd' => 4090,
        'sr' => 4091,
        'sj' => 4092,
        'sz' => 4093,
        'se' => 4094,
        'ch' => 4095,
        'sy' => 4096,
        'tw' => 4097,
        'tj' => 4098,
        'tz' => 4099,
        'th' => 4100,
        'tl' => 4101,
        'tg' => 4102,
        'tk' => 4103,
        'to' => 4104,
        'tt' => 4105,
        'tn' => 4106,
        'tr' => 4107,
        'tm' => 4108,
        'tc' => 4109,
        'tv' => 4110,
        'ug' => 4111,
        'ua' => 4112,
        'ae' => 4113,
        'gb' => 4114,
        'us' => 4115,
        'uy' => 4116,
        'uz' => 4117,
        'vu' => 4118,
        've' => 4119,
        'vn' => 4120,
        'vg' => 4121,
        'vi' => 4122,
        'wf' => 4123,
        'eh' => 4124,
        'ye' => 4125,
        'zm' => 4126,
        'af' => 3885,
        'zw' => 4127,
    ),
    'en_languages' => array(
        'bp' => 4514,
        'en' => 3504,
        'ar' => 3505,
        'aa' => 3506,
        'ab' => 3507,
        'af' => 3508,
        'am' => 3509,
        'as' => 3510,
        'ay' => 3511,
        'az' => 3512,
        'sq' => 3513,
        'hy' => 3514,
        'ba' => 3515,
        'be' => 3516,
        'bg' => 3517,
        'bh' => 3518,
        'bi' => 3519,
        'bn' => 3520,
        'br' => 3521,
        'dz' => 3522,
        'eu' => 3523,
        'my' => 3524,
        'zh' => 3525,
        'ca' => 3526,
        'co' => 3527,
        'cs' => 3528,
        'hr' => 3529,
        'km' => 3530,
        'da' => 3531,
        'nl' => 3532,
        'eo' => 3533,
        'et' => 3534,
        'fa' => 3535,
        'fi' => 3536,
        'fj' => 3537,
        'fo' => 3538,
        'fr' => 3539,
        'fy' => 3540,
        'de' => 3541,
        'el' => 3542,
        'gl' => 3543,
        'gn' => 3544,
        'gu' => 3545,
        'ka' => 3546,
        'kl' => 3547,
        'ha' => 3548,
        'hi' => 3549,
        'hu' => 3550,
        'iw' => 3551,
        'it' => 3552,
        'ga' => 3553,
        'ia' => 3554,
        'ie' => 3555,
        'ik' => 3556,
        'in' => 3557,
        'is' => 3558,
        'ja' => 3559,
        'jw' => 3560,
        'kk' => 3561,
        'rn' => 3562,
        'kn' => 3563,
        'ko' => 3564,
        'ks' => 3565,
        'ku' => 3566,
        'ky' => 3567,
        'rw' => 3568,
        'la' => 3569,
        'ln' => 3570,
        'lo' => 3571,
        'lt' => 3572,
        'lv' => 3573,
        'mg' => 3574,
        'mi' => 3575,
        'mk' => 3576,
        'ml' => 3577,
        'mn' => 3578,
        'mo' => 3579,
        'mr' => 3580,
        'ms' => 3581,
        'mt' => 3582,
        'na' => 3583,
        'ne' => 3584,
        'no' => 3585,
        'oc' => 3586,
        'om' => 3587,
        'pa' => 3588,
        'pl' => 3589,
        'ps' => 3590,
        'pt' => 3591,
        'qu' => 3592,
        'rm' => 3593,
        'ro' => 3594,
        'ru' => 3595,
        'es' => 3596,
        'gd' => 3597,
        'sa' => 3598,
        'sd' => 3599,
        'sg' => 3600,
        'sh' => 3601,
        'si' => 3602,
        'sk' => 3603,
        'sl' => 3604,
        'sm' => 3605,
        'sn' => 3606,
        'so' => 3607,
        'sr' => 3608,
        'ss' => 3609,
        'st' => 3610,
        'su' => 3611,
        'sv' => 3612,
        'sw' => 3613,
        'tn' => 3614,
        'bo' => 3615,
        'ta' => 3616,
        'te' => 3617,
        'tg' => 3618,
        'th' => 3619,
        'ti' => 3620,
        'tk' => 3621,
        'tl' => 3622,
        'to' => 3623,
        'tr' => 3624,
        'ts' => 3625,
        'tt' => 3626,
        'tw' => 3627,
        'uk' => 3628,
        'ur' => 3629,
        'uz' => 3630,
        'vi' => 3631,
        'vo' => 3632,
        'cy' => 3633,
        'wo' => 3634,
        'xh' => 3635,
        'yo' => 3636,
        'ji' => 3637,
        'zu' => 3638,
    ),
    'en_timezones' => array(
        '-11' => 3473,
        '-10' => 3474,
        '-9' => 3475,
        '-8' => 3476,
        '-7' => 3477,
        '-6' => 3478,
        '-5' => 3479,
        '-4.5' => 3480,
        '-4' => 3481,
        '-3.5' => 3482,
        '-3' => 3483,
        '-2' => 3484,
        '-1' => 3485,
        '0' => 3486,
        '1' => 3487,
        '2' => 3488,
        '3' => 3489,
        '3.5' => 3490,
        '4' => 3491,
        '4.5' => 3492,
        '5' => 3493,
        '6' => 3494,
        '7' => 3495,
        '8' => 3496,
        '9' => 3497,
        '9.5' => 3498,
        '10' => 3499,
        '11' => 3500,
        '12' => 3501,
    ),
    'en_gender' => array(
        'm' => 3291,
        'f' => 3292,
    ),
);

$config['timezones'] = array(
    '-11' => "GMT-11:00 Midway Island, Samoa",
    '-10' => "GMT-10:00 Hawaii",
    '-9' => "GMT-09:00 Alaska",
    '-8' => "GMT-08:00 Pacific Standard Time, Tijuana",
    '-7' => "GMT-07:00 Arizona, Mountain Time, Chihuahua",
    '-6' => "GMT-06:00 Central Time, Mexico City ",
    '-5' => "GMT-05:00 Eastern Time, Indiana, Bogota, Lima",
    '-4.5' => "GMT-04:30 Caracas",
    '-4' => "GMT-04:00 Atlantic Time, La Paz, Santiago",
    '-3.5' => "GMT-03:30 Newfoundland",
    '-3' => "GMT-03:00 Buenos Aires, Greenland",
    '-2' => "GMT-02:00 Stanley",
    '-1' => "GMT-01:00 Azores, Cape Verde Is.",
    '0' => "GMT 0:00 London, Dublin, Lisbon, Casablanca",
    '1' => "GMT+01:00 Amsterdam, Berlin, Paris ",
    '2' => "GMT+02:00 Athens, Istanbul, Jerusalem ",
    '3' => "GMT+03:00 Moscow, Baghdad, Kuwait, Riyadh",
    '3.5' => "GMT+03:30 Tehran",
    '4' => "GMT+04:00 Baku, Volgograd, Muscat ",
    '4.5' => "GMT+04:30 Kabul",
    '5' => "GMT+05:00 Karachi, Tashkent, Kolkata ",
    '6' => "GMT+06:00 Ekaterinburg, Almaty, Dhaka",
    '7' => "GMT+07:00 Novosibirsk, Bangkok, Jakarta",
    '8' => "GMT+08:00 Hong Kong, Perth, Singapore ",
    '9' => "GMT+09:00 Irkutsk, Seoul, Tokyo",
    '9.5' => "GMT+09:30 Adelaide, Darwin",
    '10' => "GMT+10:00 Melbourne, Sydney, Guam ",
    '11' => "GMT+11:00 Vladivostok",
    '12' => "GMT+12:00 Magadan, Auckland, Fiji",
);

$config['languages'] = array(
    'en' => 'English',

    'ar' => 'Arabic',
    'aa' => 'Afar',
    'ab' => 'Abkhazian',
    'af' => 'Afrikaans',
    'am' => 'Amharic',
    'as' => 'Assamese',
    'ay' => 'Aymara',
    'az' => 'Azerbaijani',
    'sq' => 'Albanian',
    'hy' => 'Armenian',

    'ba' => 'Bashkir',
    'be' => 'Byelorussian',
    'bg' => 'Bulgarian',
    'bh' => 'Bihari',
    'bi' => 'Bislama',
    'bn' => 'Bengali',
    'br' => 'Breton',
    'dz' => 'Bhutani',
    'eu' => 'Basque',
    'my' => 'Burmese',

    'zh' => 'Chinese',
    'ca' => 'Catalan',
    'co' => 'Corsican',
    'cs' => 'Czech',
    'hr' => 'Croatian',
    'km' => 'Cambodian',

    'da' => 'Danish',
    'nl' => 'Dutch',

    'eo' => 'Esperanto',
    'et' => 'Estonian',

    'fa' => 'Farsi',
    'fi' => 'Finnish',
    'fj' => 'Fiji',
    'fo' => 'Faeroese',
    'fr' => 'French',
    'fy' => 'Frisian',

    'de' => 'German',
    'el' => 'Greek',
    'gl' => 'Galician',
    'gn' => 'Guarani',
    'gu' => 'Gujarati',
    'ka' => 'Georgian',
    'kl' => 'Greenlandic',

    'ha' => 'Hausa',
    'hi' => 'Hindi',
    'hu' => 'Hungarian',
    'iw' => 'Hebrew',

    'it' => 'Italian',
    'ga' => 'Irish',
    'ia' => 'Interlingua',
    'ie' => 'Interlingue',
    'ik' => 'Inupiak',
    'in' => 'Indonesian',
    'is' => 'Icelandic',

    'ja' => 'Japanese',
    'jw' => 'Javanese',

    'kk' => 'Kazakh',
    'rn' => 'Kirundi',
    'kn' => 'Kannada',
    'ko' => 'Korean',
    'ks' => 'Kashmiri',
    'ku' => 'Kurdish',
    'ky' => 'Kirghiz',
    'rw' => 'Kinyarwanda',

    'la' => 'Latin',
    'ln' => 'Lingala',
    'lo' => 'Laothian',
    'lt' => 'Lithuanian',
    'lv' => 'Latvian',

    'mg' => 'Malagasy',
    'mi' => 'Maori',
    'mk' => 'Macedonian',
    'ml' => 'Malayalam',
    'mn' => 'Mongolian',
    'mo' => 'Moldavian',
    'mr' => 'Marathi',
    'ms' => 'Malay',
    'mt' => 'Maltese',

    'na' => 'Nauru',
    'ne' => 'Nepali',
    'no' => 'Norwegian',

    'oc' => 'Occitan',
    'om' => 'Oromoor',

    'pa' => 'Punjabi',
    'pl' => 'Polish',
    'ps' => 'Pashto',
    'pt' => 'Portuguese',

    'qu' => 'Quechua',

    'rm' => 'Rhaeto-Romance',
    'ro' => 'Romanian',
    'ru' => 'Russian',

    'es' => 'Spanish',
    'gd' => 'Scots',
    'sa' => 'Sanskrit',
    'sd' => 'Sindhi',
    'sg' => 'Sangro',
    'sh' => 'Serbo-Croatian',
    'si' => 'Singhalese',
    'sk' => 'Slovak',
    'sl' => 'Slovenian',
    'sm' => 'Samoan',
    'sn' => 'Shona',
    'so' => 'Somali',
    'sr' => 'Serbian',
    'ss' => 'Siswati',
    'st' => 'Sesotho',
    'su' => 'Sundanese',
    'sv' => 'Swedish',
    'sw' => 'Swahili',
    'tn' => 'Setswana',

    'bo' => 'Tibetan',
    'ta' => 'Tamil',
    'te' => 'Tegulu',
    'tg' => 'Tajik',
    'th' => 'Thai',
    'ti' => 'Tigrinya',
    'tk' => 'Turkmen',
    'tl' => 'Tagalog',
    'to' => 'Tonga',
    'tr' => 'Turkish',
    'ts' => 'Tsonga',
    'tt' => 'Tatar',
    'tw' => 'Twi',

    'uk' => 'Ukrainian',
    'ur' => 'Urdu',
    'uz' => 'Uzbek',

    'vi' => 'Vietnamese',
    'vo' => 'Volapuk',

    'cy' => 'Welsh',
    'wo' => 'Wolof',

    'xh' => 'Xhosa',

    'yo' => 'Yoruba',
    'ji' => 'Yiddish',

    'zu' => 'Zulu',
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
$config['uri_protocol'] = 'REQUEST_URI';

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
$config['language'] = 'english';

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
$config['controller_trigger'] = 'in';
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
$config['cookie_prefix'] = '';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE;
$config['cookie_httponly'] = FALSE;

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
