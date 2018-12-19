<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
 *
 * Global variables used throughout the platform.
 * use-case format: $this->config->item('en_convert_4537')
 *
 */


//Settime zone to PST:
date_default_timezone_set('America/Los_Angeles');


//Matrix defaults:
$config['in_primary_name'] = 'advance your tech career'; //What is the purposes of Mench at this point?
$config['in_primary_id'] = 6903; //The default platform intent that would be recommended to new masters
$config['en_primary_id'] = 3463; //The default matrix entity that is loaded when Entities is clicked
$config['en_default_url_parent'] = 1326; //The entity that would be the parent to all new URLs added via Messages


//UI Display:
$config['app_version'] = '0.64'; //Cache buster in URLs for static js/css files
$config['en_per_page'] = 50; //Limits the maximum entities loaded per page
$config['tr_max_count'] = 999; //TODO Deprecate... (Used in counting things of engagements in matrix UI. If more that this will add a "+" sign to the end)


//App Functionality:
$config['enable_algolia'] = false; //Currently reached our monthly free quota
$config['file_size_max'] = 25; //Server setting is 32MB. see here: mench.com/ses
$config['password_salt'] = '40s96As9ZkdAcwQ9PhZm'; //Used for hashing the user password for Mench logins
$config['tr_types_exclude'] = array(4278, 4279); //These transaction types will be ignored in statistical models as there are too many of them!
$config['tr_status_incomplete'] = array(0, 1); //Transactions with these tr_status values are considered in-complete


//App Inputs:
$config['in_points_options'] = array(-89, -55, -34, -21, -13, -8, -5, -3, -2, -1, 0, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144, 233, 377, 610);
$config['in_seconds_max'] = 28800; //The maximum seconds allowed per intent. If larger, the miner is asked to break it down into smaller intents
$config['in_outcome_max'] = 89; //Max number of characters allowed in the title of intents
$config['en_name_max'] = 250; //Max number of characters allowed in the title of intents
$config['tr_content_max'] = 610; //Max number of characters allowed in messages. Facebook's cap is 2000 characters/message


//Third-Party Settings:
$config['fb_max_message'] = 2000; //The maximum length of a Message accepted via Messenger API (This used to be 610 before, then Facebook expanded it!)
$config['en_convert_4454'] = array( //Mench Notification Levels to Facebook Messenger - This is a manual converter of our internal entities to Facebook API language
    4456 => 'REGULAR',
    4457 => 'SILENT_PUSH',
    4458 => 'NO_PUSH',
    //There is also an Unsubscribe @4455 entity which is not here obviously since there would be no communication with the Master at all!
);
$config['en_convert_4537'] = array( //Used for saving media to Facebook Servers to speed-up delivery over Messenger
    4258 => 'video',
    4259 => 'audio',
    4260 => 'image',
    4261 => 'file',
);

$config['fb_settings'] = array(
    'page_id' => '381488558920384',
    'app_id' => '1782431902047009',
    'client_secret' => '05aea76d11b062951b40a5bee4251620',
    'default_graph_version' => 'v2.10', //Note: This variable also exists in the Facebook Library too! Search "v2.10"
    'mench_access_token' => 'EAAZAVHMRbmyEBAEfN8zsRJ3UOIUJJrNLqeFutPXVQZCoDZA3EO1rgkkzayMtNhisHHEhAos08AmKZCYD7zcZAPIDSMTcBjZAHxxWzbfWyTyp85Fna2bGDfv5JUIBuFTSeQOZBaDHRG7k0kbW8E7kQQN3W6x47VB1dZBPJAU1oNSW1QZDZD',
);
$config['aws_credentials'] = [ //Learn more: https://console.aws.amazon.com/iam/home?region=us-west-2#/users/foundation?section=security_credentials
    'key' => 'AKIAJOLBLKFSYCCYYDRA',
    'secret' => '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',
];

$config['eng_converter'] = array(
    //Patternization Links
    20 => 4250, //Log intent creation
    6971 => 4251, //Log entity creation
    21 => 4252, //Log intent archived
    50 => 4254, //Log intent migration
    19 => 4264, //Log intent modification
    //0 => 4253, //Entity Archived (Did not have this!)

    36 => 4242, //Log intent message update
    7727 => 4242, //Log entity link note modification

    12 => 4263, //Log entity modification
    7001 => 4299, //Log pending image upload sync to cloud

    89 => 4241, //Log intent unlinked
    7292 => 4241, //Log entity unlinked
    35 => 4241, //Log intent message archived
    6912 => 4241, //Log entity URL archived

    39 => 4262, //Log intent message sorting
    22 => 4262, //Log intent children sorted


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
    7703 => 4275, //Log subscription intent search
    28 => 4276, //Log user email sent
    6 => 4277, //Log message received
    1 => 4278, //Log message read
    2 => 4279, //Log message delivered
    7 => 4280, //Log message sent
    55 => 4282, //Log my account access
    32 => 4283, //Log action plan access
    33 => 4242, //Log action plan intent completion [Link updated]
    7718 => 4287, //Log unrecognized message

    //Platform Operations Links:
    8 => 4246, //Platform Error
    9 => 4247, //Log user attention request
    72 => 4248, //Log user review
);


//Ledger filters:
$config['ledger_filters'] = array(
    'tr_en_credit_id' => 'en',
    'tr_en_child_id'  => 'en',
    'tr_en_parent_id' => 'en',
    'tr_in_child_id'  => 'in',
    'tr_in_parent_id' => 'in',
    'tr_tr_parent_id' => 'tr',
);


//3x Table Statuses:
$config['object_statuses'] = array(

    //Entity 7 Statuses:
    'en_status' => array(
        -3 => array(
            's_name' => 'Denied',
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

    //Transaction 7 Statuses:
    'tr_status' => array(
        -3 => array(
            's_name' => 'Denied',
            's_desc' => 'Removed because it violated community guidelines',
            's_icon' => 'fal fa-minus-square',
        ),
        -2 => array(
            's_name' => 'Iterated',
            's_desc' => 'Content updated with newer content',
            's_icon' => 'fal fa-minus-square',
        ),
        -1 => array(
            's_name' => 'Removed', //or skipped
            's_desc' => 'User decided to skip this link',
            's_icon' => 'fal fa-minus-square',
        ),
        0 => array( //This status is considered incomplete, see tr_status_incomplete variable above
            's_name' => 'New',
            's_desc' => 'New link pending acceptance',
            's_icon' => 'fal fa-square',
        ),
        1 => array( //This status is considered incomplete, see tr_status_incomplete variable above
            's_name' => 'Working On',
            's_desc' => 'Work has started and but some intents are pending completion',
            's_icon' => 'fas fa-spinner fa-spin',
        ),
        2 => array(
            's_name' => 'Syncing',
            's_desc' => 'Completed and ready for updates to be synced',
            's_icon' => 'fas fa-check-square',
        ),
        3 => array(
            's_name' => 'Verified',
            's_desc' => 'Intent successfully accomplished as verified by Mench', //The most precious link :)
            's_icon' => 'fas fa-badge-check',
        ),
    ),


    //Intent 7 Statuses:
    'in_status' => array(
        -3 => array(
            's_name' => 'Denied',
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
            's_desc' => 'Intent is published live and ready to be added to Action Plans',
            's_icon' => 'fas fa-check-circle',
        ),
        3 => array(
            's_name' => 'Featured',
            's_desc' => 'Intent is recommended on mench.com home page',
            's_icon' => 'fas fa-badge-check',
        ),
    ),

    //Intent Is Any setting:
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

    //TODO Deprecate:
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
            's_name' => 'Removed',
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




//TODO Deprecating:
$config['notify_admins'] = array( //Email-based engagements
    1 => array(
        'admin_emails' => array('shervin@mench.com'),
        'admin_notify' => array(
            9, //User attention
            8, //System error
            10, //user login
        ),
    ),
);
$config['en_user_metadata'] = array(
    'en_countries' => array(
        'vg' => 4121,
        'vi' => 4122,
        'wf' => 4123,
        'eh' => 4124,
        'ye' => 4125,
        'zm' => 4126,
        'zw' => 4127,
        'vn' => 4120,
        'ua' => 4112,
        'ug' => 4111,
        'tc' => 4109,
        'tv' => 4110,
        'vu' => 4118,
        've' => 4119,
        'uz' => 4117,
        'uy' => 4116,
        'us' => 4115,
        'gb' => 4114,
        'ae' => 4113,
        'tm' => 4108,
        'tr' => 4107,
        'tn' => 4106,
        'tt' => 4105,
        'to' => 4104,
        'tk' => 4103,
        'tg' => 4102,
        'tl' => 4101,
        'th' => 4100,
        'tz' => 4099,
        'tj' => 4098,
        'tw' => 4097,
        'sy' => 4096,
        'ch' => 4095,
        'se' => 4094,
        'sz' => 4093,
        'sj' => 4092,
        'sr' => 4091,
        'sd' => 4090,
        'lk' => 4089,
        'es' => 4088,
        'gs' => 4087,
        'sa' => 4075,
        'sn' => 4076,
        'za' => 4086,
        'st' => 4074,
        'so' => 4085,
        'sb' => 4084,
        'si' => 4083,
        'sk' => 4081,
        'sg' => 4080,
        'sl' => 4079,
        'sc' => 4078,
        'rs' => 4077,
        'kn' => 4068,
        'sm' => 4073,
        'ws' => 4072,
        'vc' => 4071,
        'pm' => 4070,
        'lc' => 4069,
        'sh' => 4067,
        'rw' => 4066,
        'ru' => 4065,
        'ro' => 4064,
        're' => 4063,
        'ps' => 4052,
        'qa' => 4062,
        'pr' => 4061,
        'pt' => 4060,
        'pl' => 4059,
        'pn' => 4058,
        'ph' => 4057,
        'pe' => 4056,
        'py' => 4055,
        'pg' => 4054,
        'pa' => 4053,
        'ni' => 4042,
        'pw' => 4051,
        'pk' => 4050,
        'om' => 4049,
        'no' => 4048,
        'mp' => 4047,
        'nf' => 4046,
        'nu' => 4045,
        'ng' => 4044,
        'ne' => 4043,
        'nz' => 4041,
        'mn' => 4030,
        'nc' => 4040,
        'nl' => 4039,
        'np' => 4038,
        'nr' => 4037,
        'na' => 4036,
        'mm' => 4035,
        'mz' => 4034,
        'ma' => 4033,
        'ms' => 4032,
        'me' => 4031,
        'ml' => 4019,
        'mc' => 4029,
        'md' => 4028,
        'fm' => 4027,
        'mx' => 4026,
        'yt' => 4025,
        'mu' => 4024,
        'mr' => 4023,
        'mq' => 4022,
        'mh' => 4021,
        'mt' => 4020,
        'lr' => 4008,
        'mv' => 4018,
        'my' => 4017,
        'mk' => 4014,
        'mo' => 4013,
        'lu' => 4012,
        'mg' => 4015,
        'lt' => 4011,
        'li' => 4010,
        'ly' => 4009,
        'mw' => 4016,
        'ls' => 4007,
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
        'je' => 3995,
        'jp' => 3994,
        'jm' => 3993,
        'it' => 3992,
        'il' => 3991,
        'im' => 3990,
        'ie' => 3989,
        'iq' => 3988,
        'jo' => 3996,
        'hm' => 3979,
        'gy' => 3977,
        'ht' => 3978,
        'ir' => 3987,
        'id' => 3986,
        'in' => 3985,
        'is' => 3984,
        'hu' => 3983,
        'hk' => 3982,
        'hn' => 3981,
        'va' => 3980,
        'gp' => 3971,
        'gw' => 3976,
        'gn' => 3975,
        'gg' => 3974,
        'gt' => 3973,
        'gu' => 3972,
        'gd' => 3970,
        'gl' => 3969,
        'gr' => 3968,
        'gi' => 3967,
        'gh' => 3966,
        'fo' => 3955,
        'de' => 3965,
        'ge' => 3964,
        'gm' => 3963,
        'ga' => 3962,
        'tf' => 3961,
        'pf' => 3960,
        'gf' => 3959,
        'fr' => 3958,
        'fi' => 3957,
        'fj' => 3956,
        'do' => 3946,
        'fk' => 3954,
        'et' => 3953,
        'ee' => 3952,
        'er' => 3951,
        'gq' => 3950,
        'dj' => 3944,
        'sv' => 3949,
        'eg' => 3948,
        'ec' => 3947,
        'dm' => 3945,
        'cr' => 3937,
        'hr' => 3939,
        'ci' => 3938,
        'cu' => 3940,
        'ck' => 3936,
        'cd' => 3935,
        'cg' => 3934,
        'km' => 3933,
        'co' => 3932,
        'dk' => 3943,
        'cz' => 3942,
        'cy' => 3941,
        'cm' => 3922,
        'kh' => 3921,
        'cl' => 3928,
        'cn' => 3929,
        'cx' => 3930,
        'cc' => 3931,
        'ca' => 3923,
        'td' => 3927,
        'cf' => 3926,
        'ky' => 3925,
        'cv' => 3924,
        'io' => 3916,
        'br' => 3915,
        'bi' => 3920,
        'bf' => 3919,
        'bg' => 3918,
        'bn' => 3917,
        'bv' => 3914,
        'bw' => 3913,
        'ba' => 3912,
        'bo' => 3911,
        'bt' => 3910,
        'at' => 3899,
        'bm' => 3909,
        'bj' => 3908,
        'bz' => 3907,
        'be' => 3906,
        'by' => 3905,
        'bb' => 3904,
        'bd' => 3903,
        'bh' => 3902,
        'bs' => 3901,
        'az' => 3900,
        'ag' => 3894,
        'aq' => 3893,
        'ai' => 3892,
        'ao' => 3891,
        'ad' => 3890,
        'as' => 3889,
        'dz' => 3888,
        'am' => 3896,
        'aw' => 3897,
        'au' => 3898,
        'ar' => 3895,
        'al' => 3887,
        'ax' => 3886,
        'af' => 3885,
    ),
    'en_languages' => array(
        'bp' => 4514,
        'yo' => 3636,
        'ji' => 3637,
        'zu' => 3638,
        'xh' => 3635,
        'ur' => 3629,
        'cy' => 3633,
        'uk' => 3628,
        'tw' => 3627,
        'tt' => 3626,
        'ts' => 3625,
        'to' => 3623,
        'tr' => 3624,
        'vo' => 3632,
        'vi' => 3631,
        'wo' => 3634,
        'uz' => 3630,
        'sv' => 3612,
        'tl' => 3622,
        'tk' => 3621,
        'ti' => 3620,
        'th' => 3619,
        'tg' => 3618,
        'te' => 3617,
        'ta' => 3616,
        'bo' => 3615,
        'tn' => 3614,
        'sw' => 3613,
        'su' => 3611,
        'sg' => 3600,
        'st' => 3610,
        'ss' => 3609,
        'sr' => 3608,
        'so' => 3607,
        'sn' => 3606,
        'sm' => 3605,
        'sl' => 3604,
        'sk' => 3603,
        'si' => 3602,
        'sh' => 3601,
        'sd' => 3599,
        'om' => 3587,
        'sa' => 3598,
        'gd' => 3597,
        'es' => 3596,
        'ru' => 3595,
        'ro' => 3594,
        'rm' => 3593,
        'qu' => 3592,
        'pt' => 3591,
        'ps' => 3590,
        'pl' => 3589,
        'pa' => 3588,
        'mr' => 3580,
        'oc' => 3586,
        'no' => 3585,
        'ne' => 3584,
        'na' => 3583,
        'mt' => 3582,
        'mo' => 3579,
        'mn' => 3578,
        'ml' => 3577,
        'mk' => 3576,
        'mi' => 3575,
        'ms' => 3581,
        'lt' => 3572,
        'kn' => 3563,
        'ko' => 3564,
        'ks' => 3565,
        'ku' => 3566,
        'ky' => 3567,
        'rw' => 3568,
        'la' => 3569,
        'ln' => 3570,
        'lo' => 3571,
        'lv' => 3573,
        'mg' => 3574,
        'rn' => 3562,
        'kk' => 3561,
        'jw' => 3560,
        'ja' => 3559,
        'is' => 3558,
        'in' => 3557,
        'ik' => 3556,
        'ie' => 3555,
        'ia' => 3554,
        'ga' => 3553,
        'it' => 3552,
        'iw' => 3551,
        'fr' => 3539,
        'hu' => 3550,
        'hi' => 3549,
        'ha' => 3548,
        'ka' => 3546,
        'kl' => 3547,
        'gu' => 3545,
        'gn' => 3544,
        'gl' => 3543,
        'el' => 3542,
        'de' => 3541,
        'fy' => 3540,
        'eo' => 3533,
        'fo' => 3538,
        'fj' => 3537,
        'fi' => 3536,
        'fa' => 3535,
        'et' => 3534,
        'nl' => 3532,
        'da' => 3531,
        'km' => 3530,
        'hr' => 3529,
        'cs' => 3528,
        'co' => 3527,
        'ca' => 3526,
        'zh' => 3525,
        'my' => 3524,
        'eu' => 3523,
        'dz' => 3522,
        'br' => 3521,
        'bn' => 3520,
        'bi' => 3519,
        'bh' => 3518,
        'bg' => 3517,
        'be' => 3516,
        'ba' => 3515,
        'hy' => 3514,
        'sq' => 3513,
        'az' => 3512,
        'ay' => 3511,
        'as' => 3510,
        'am' => 3509,
        'af' => 3508,
        'ab' => 3507,
        'aa' => 3506,
        'ar' => 3505,
        'en' => 3504,
    ),
    'en_timezones' => array(
        '9' => 3497,
        '8' => 3496,
        '6' => 3494,
        '7' => 3495,
        '12' => 3501,
        '11' => 3500,
        '10' => 3499,
        '9.5' => 3498,
        '5' => 3493,
        '3.5' => 3490,
        '4' => 3491,
        '4.5' => 3492,
        '1' => 3487,
        '-4' => 3481,
        '-3.5' => 3482,
        '-3' => 3483,
        '-2' => 3484,
        '-1' => 3485,
        '0' => 3486,
        '2' => 3488,
        '3' => 3489,
        '-11' => 3473,
        '-4.5' => 3480,
        '-5' => 3479,
        '-6' => 3478,
        '-7' => 3477,
        '-8' => 3476,
        '-9' => 3475,
        '-10' => 3474,
    ),
    'en_gender' => array(
        'f' => 3292,
        'm' => 3291,
    ),
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
