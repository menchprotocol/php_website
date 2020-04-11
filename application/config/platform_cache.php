<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
* Keep a cache of certain parts of the tree for faster processing
* See here for more details: https://mench.com/source/4527
*
*/

//Generated 2020-04-11 16:10:47 PST

//PLATFORM STATS:
$config['cache_timestamp'] = 1586646647;
$config['cache_count_transaction'] = 1114524;
$config['cache_count_read'] = 121006;
$config['cache_count_note'] = 3825;
$config['cache_count_source'] = 9209;


$config['cache_count_12640'] = 645; //EXPERT SOURCES
$config['cache_count_1308'] = 2; //MODERATORS
$config['cache_count_2997'] = 126; //NON-FICTION ARTICLES
$config['cache_count_4446'] = 34; //NON-FICTION ASSESSMENTS
$config['cache_count_3005'] = 2; //NON-FICTION BOOKS
$config['cache_count_4763'] = 10; //NON-FICTION CHANNELS
$config['cache_count_3147'] = 28; //NON-FICTION COURSES
$config['cache_count_2999'] = 8; //NON-FICTION PODCASTS
$config['cache_count_5948'] = 9; //NON-FICTION TEMPLATES
$config['cache_count_3192'] = 15; //NON-FICTION TOOLS
$config['cache_count_2998'] = 415; //NON-FICTION VIDEOS
$config['cache_count_4430'] = 2473; //PLAYERS

//MEMORY CACHE COUNT:
$config['en_ids_12639'] = array(12640,1308,2997,4446,3005,4763,3147,2999,5948,3192,2998,4430);
$config['en_all_12639'] = array(
    12640 => array(
        'm_icon' => '<i class="fad fa-shield-check source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT SOURCES',
        'm_desc' => '',
        'm_parents' => array(12639,4536),
    ),
    1308 => array(
        'm_icon' => '<i class="far fa-user-hard-hat" aria-hidden="true"></i>',
        'm_name' => 'MODERATORS',
        'm_desc' => '',
        'm_parents' => array(12639),
    ),
    2997 => array(
        'm_icon' => '<i class="fad fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION ARTICLES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fad fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fad fa-book source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION BOOKS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION CHANNELS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION COURSES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION PODCASTS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,10809,4983,7614,6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="fad fa-file-invoice source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION TEMPLATES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION TOOLS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION VIDEOS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-horse-head source" aria-hidden="true"></i>',
        'm_name' => 'PLAYERS',
        'm_desc' => '',
        'm_parents' => array(12639,12437,11035,10573,4983,6827,4426),
    ),
);

//NOTE PADS STATUS:
$config['en_ids_12012'] = array(6173,6176);
$config['en_all_12012'] = array(
    6173 => array(
        'm_icon' => '<i class="fad fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12012,10686,10678,10673,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
);

//MENCH COINS:
$config['en_ids_12467'] = array(12273,6255,12274);
$config['en_all_12467'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTES',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(12467,10964,11089,12410,12365,6771,12228,4527),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(12467,12228,4527,4758),
    ),
);

//NEXT EDITOR:
$config['en_ids_12589'] = array(12591,12592);
$config['en_all_12589'] = array(
    12591 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'ADD SOURCE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,12589),
    ),
    12592 => array(
        'm_icon' => '<i class="fas fa-minus-circle source" aria-hidden="true"></i>',
        'm_name' => 'REMOVE SOURCE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,12589),
    ),
);

//AVOID PREFIX REMOVAL:
$config['en_ids_12588'] = array(4341);
$config['en_all_12588'] = array(
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas read" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(12588,11035,4527,7735,6205),
    ),
);

//SIGN IN/UP:
$config['en_ids_4269'] = array(3288,6197,3286);
$config['en_all_4269'] = array(
    3288 => array(
        'm_icon' => '<i class="fad fa-envelope-open source" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => '',
        'm_parents' => array(4269,12221,12103,6225,4426,4755),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'NICKNAME',
        'm_desc' => '',
        'm_parents' => array(4269,12412,12232,6225,11072,10646,5000,4998,4999,6232,6206),
    ),
    3286 => array(
        'm_icon' => '<i class="fad fa-key source" aria-hidden="true"></i>',
        'm_name' => 'PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4269,4426,7578,6225,4755),
    ),
);

//FILE UPLOADING ALLOWED:
$config['en_ids_12359'] = array(12419,4231);
$config['en_all_12359'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt note" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment note" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
);

//PORTFOLIO EDITOR UPPERCASE:
$config['en_ids_12577'] = array(4999,4998,5000,5981,11956,5982);
$config['en_all_12577'] = array(
    4999 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="source fad fa-layer-plus"></i>',
        'm_name' => 'PROFILE ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="source fad fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PROFILE IF ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="source fad fa-layer-minus"></i>',
        'm_name' => 'PROFILE REMOVE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
);

//LAYOUT SHOW EVEN IF ZERO:
$config['en_ids_12574'] = array(4997,11029,11030);
$config['en_all_12574'] = array(
    4997 => array(
        'm_icon' => '<i class="fad fa-edit source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR',
        'm_desc' => '',
        'm_parents' => array(12590,11029,12574,10967,11089,4758,4506,4527),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIOS',
        'm_desc' => '',
        'm_parents' => array(12574,12571,11089,11028),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-id-badge source" aria-hidden="true"></i>',
        'm_name' => 'PROFILES',
        'm_desc' => '',
        'm_parents' => array(12574,11089,11028),
    ),
);

//SOURCE STATUS FEATURED:
$config['en_ids_12575'] = array(12563);
$config['en_all_12575'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'SOURCE FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
);

//LAYOUT OPEN BY DEFAULT:
$config['en_ids_12571'] = array(12273,11029);
$config['en_all_12571'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'MENCH NOTES',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIOS',
        'm_desc' => '',
        'm_parents' => array(12574,12571,11089,11028),
    ),
);

//MESSAGE VISUAL MEDIA:
$config['en_ids_12524'] = array(4259,4261,4260,4258,4257);
$config['en_all_12524'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source"></i>',
        'm_name' => 'WIDGET',
        'm_desc' => '',
        'm_parents' => array(12605,12524,12403,4535,4593,4592,4537,4506),
    ),
);

//SYNC ICONS:
$config['en_ids_12523'] = array(3084,2997,4446,3005,4763,3147,2999,5948,3192,2998,3308);
$config['en_all_12523'] = array(
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut source" aria-hidden="true"></i>',
        'm_name' => 'INDUSTRY EXPERTS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,6827),
    ),
    2997 => array(
        'm_icon' => '<i class="fad fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION ARTICLES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fad fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fad fa-book source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION BOOKS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION CHANNELS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION COURSES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION PODCASTS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,10809,4983,7614,6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="fad fa-file-invoice source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION TEMPLATES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION TOOLS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION VIDEOS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    3308 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'YOUTUBE VIDEOS',
        'm_desc' => '',
        'm_parents' => array(12640,12523,4763,4257,2750,1326),
    ),
);

//MENCH JAVASCRIPT FUNCTIONS:
$config['en_ids_12502'] = array(10957);
$config['en_all_12502'] = array(
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => 'toggle_nav(\'superpower_nav\')',
        'm_parents' => array(12502,12500,6225,11035,5007,4527),
    ),
);

//NAVIGATION MENUS:
$config['en_ids_12501'] = array(12500);
$config['en_all_12501'] = array(
    12500 => array(
        'm_icon' => '<i class="fad fa-user" aria-hidden="true"></i>',
        'm_name' => 'SOURCE MENU',
        'm_desc' => '',
        'm_parents' => array(12497,12501,4527),
    ),
);

//SOURCE MENU:
$config['en_ids_12500'] = array(6225,12205,10957,6287,7291);
$config['en_all_12500'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-user-cog source" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(10876,12500,4536,11035,4527),
    ),
    12205 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PUBLIC PROFILE',
        'm_desc' => '',
        'm_parents' => array(12500,11035),
    ),
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(12502,12500,6225,11035,5007,4527),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-user-shield source" aria-hidden="true"></i>',
        'm_name' => 'ADMIN PANEL',
        'm_desc' => '',
        'm_parents' => array(12500,10985,10876,11035,4527),
    ),
    7291 => array(
        'm_icon' => '<i class="fad fa-power-off source" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => '',
        'm_parents' => array(10876,12500,11035),
    ),
);

//READ ICONS:
$config['en_ids_12446'] = array(6146,12447,12448);
$config['en_all_12446'] = array(
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'INCOMPLETES',
        'm_desc' => '',
        'm_parents' => array(12446,10989,11089,12365,12228,4527),
    ),
    12447 => array(
        'm_icon' => '<i class="fad fa-spinner-third read fa-spin" aria-hidden="true"></i>',
        'm_name' => 'IN PROGRESS',
        'm_desc' => '',
        'm_parents' => array(12446),
    ),
    12448 => array(
        'm_icon' => '<i class="far fa-circle read"></i>',
        'm_name' => 'NOT STARTED',
        'm_desc' => '',
        'm_parents' => array(12446),
    ),
);

//SOURCE ICON DROPDOWN:
$config['en_ids_12421'] = array(12426,12422,12424,12423);
$config['en_all_12421'] = array(
    12426 => array(
        'm_icon' => '<i class="far fa-link" aria-hidden="true"></i>',
        'm_name' => 'IMAGE URL',
        'm_desc' => 'Paste image URL',
        'm_parents' => array(12421),
    ),
    12422 => array(
        'm_icon' => '<i class="far fa-trash-alt read" aria-hidden="true"></i>',
        'm_name' => 'REMOVE',
        'm_desc' => 'Remove current icon',
        'm_parents' => array(12421),
    ),
    12424 => array(
        'm_icon' => '<i class="far fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCH',
        'm_desc' => 'Search emojis & icon library',
        'm_parents' => array(12421),
    ),
    12423 => array(
        'm_icon' => '<i class="far fa-upload" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD',
        'm_desc' => 'Upload image',
        'm_parents' => array(12421),
    ),
);

//NOTE TEXT INPUT SHOW ICON:
$config['en_ids_12420'] = array(4356,4358,4739,4735);
$config['en_all_12420'] = array(
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch " aria-hidden="true"></i>',
        'm_name' => 'NOTE READ TIME',
        'm_desc' => '',
        'm_parents' => array(12112,12420,10888,10650,6232,6201),
    ),
    4358 => array(
        'm_icon' => '<i class="fad fa-comment-alt-check" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => '',
        'm_parents' => array(12420,10985,12112,10663,6103,6410,6232),
    ),
    4739 => array(
        'm_icon' => '<i class="fad fa-temperature-up" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(12420,12112,6402,6232),
    ),
    4735 => array(
        'm_icon' => '<i class="fad fa-temperature-down" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(12420,12112,6402,6232),
    ),
);

//NOTE TREE NODES:
$config['en_ids_12413'] = array(11020,11019);
$config['en_all_12413'] = array(
    11020 => array(
        'm_icon' => '<i class="fas fa-step-forward note" aria-hidden="true"></i>',
        'm_name' => 'NEXT',
        'm_desc' => '',
        'm_parents' => array(12413,11025,11018),
    ),
    11019 => array(
        'm_icon' => '<i class="fas fa-step-backward note" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS',
        'm_desc' => '',
        'm_parents' => array(12365,12413,10990,11025),
    ),
);

//MENCH LINKS:
$config['en_ids_10876'] = array(6287,7291,11999,6225,4535,10984,10939,10985,6205,10964,10989,10988,4536,10986,10983,10967);
$config['en_all_10876'] = array(
    6287 => array(
        'm_icon' => '<i class="fad fa-user-shield source" aria-hidden="true"></i>',
        'm_name' => 'ADMIN PANEL',
        'm_desc' => 'https://mench.com/source/admin_panel',
        'm_parents' => array(12500,10985,10876,11035,4527),
    ),
    7291 => array(
        'm_icon' => '<i class="fad fa-power-off source" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => 'https://mench.com/source/signout',
        'm_parents' => array(10876,12500,11035),
    ),
    11999 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER V4',
        'm_desc' => 'https://mench.com/ledger',
        'm_parents' => array(10876,6771),
    ),
    6225 => array(
        'm_icon' => '<i class="fad fa-user-cog source" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => 'https://mench.com/source/account',
        'm_parents' => array(10876,12500,4536,11035,4527),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE',
        'm_desc' => 'https://mench.com/note',
        'm_parents' => array(12499,12112,10876,4527,12155,2738),
    ),
    10984 => array(
        'm_icon' => '<i class="fad fa-paint-brush-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE BRUSH',
        'm_desc' => 'https://mench.com/13274',
        'm_parents' => array(10876,10983,10957),
    ),
    10939 => array(
        'm_icon' => '<i class="fad fa-pen note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PEN',
        'm_desc' => 'https://mench.com/13440',
        'm_parents' => array(10876,10957),
    ),
    10985 => array(
        'm_icon' => '<i class="fad fa-magic note" aria-hidden="true"></i>',
        'm_name' => 'NOTE WAND',
        'm_desc' => 'https://mench.com/13275',
        'm_parents' => array(10876,10984,10957),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ',
        'm_desc' => 'https://mench.com/read',
        'm_parents' => array(12499,10876,4527,5008,12155,2738),
    ),
    10964 => array(
        'm_icon' => '<i class="fad fa-glasses-alt read" aria-hidden="true"></i>',
        'm_name' => 'READ GLASSES',
        'm_desc' => 'https://mench.com/13279',
        'm_parents' => array(10876,10939,10957),
    ),
    10989 => array(
        'm_icon' => '<i class="fad fa-microscope read" aria-hidden="true"></i>',
        'm_name' => 'READ MICROSCOPE',
        'm_desc' => 'https://mench.com/13280',
        'm_parents' => array(10876,10985,10957),
    ),
    10988 => array(
        'm_icon' => '<i class="fad fa-telescope read" aria-hidden="true"></i>',
        'm_name' => 'READ TELESCOPE',
        'm_desc' => 'https://mench.com/13281',
        'm_parents' => array(10876,10989,10957),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => 'https://mench.com/source',
        'm_parents' => array(12499,10876,4527,5008,12155,2738),
    ),
    10986 => array(
        'm_icon' => '<i class="fad fa-dice source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE DICE',
        'm_desc' => 'https://mench.com/13278',
        'm_parents' => array(10876,10967,10957),
    ),
    10983 => array(
        'm_icon' => '<i class="fad fa-gamepad source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE JOYSTICK',
        'm_desc' => 'https://mench.com/13276',
        'm_parents' => array(10876,10964,10957),
    ),
    10967 => array(
        'm_icon' => '<i class="fad fa-turntable source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TURNTABLE',
        'm_desc' => 'https://mench.com/13277',
        'm_parents' => array(10876,10985,10957),
    ),
);

//SOURCE COINS:
$config['en_ids_12410'] = array(12273,6255);
$config['en_all_12410'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTES',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(12467,10964,11089,12410,12365,6771,12228,4527),
    ),
);

//SOURCE LINK TYPE CUSTOM UI:
$config['en_ids_12403'] = array(4257);
$config['en_all_12403'] = array(
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source"></i>',
        'm_name' => 'SOURCE LINK WIDGET',
        'm_desc' => '',
        'm_parents' => array(12605,12524,12403,4535,4593,4592,4537,4506),
    ),
);

//SOURCE SYNC STATUS:
$config['en_ids_12401'] = array(10672,4251,10654);
$config['en_all_12401'] = array(
    10672 => array(
        'm_icon' => '<i class="fad fa-trash-alt source"></i>',
        'm_name' => 'ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'CREATED',
        'm_desc' => '',
        'm_parents' => array(12401,12274,12149,12141,10645,10593,4593),
    ),
    10654 => array(
        'm_icon' => '<i class="fad fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
);

//NOTE SYNC STATUS:
$config['en_ids_12400'] = array(4250,10671,10648);
$config['en_all_12400'] = array(
    4250 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,4535,12149,12141,10593,4593),
    ),
    10671 => array(
        'm_icon' => '<i class="fad fa-trash-alt note" aria-hidden="true"></i>',
        'm_name' => 'STATUS ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593),
    ),
    10648 => array(
        'm_icon' => '<i class="fad fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593),
    ),
);

//NOTE BODY:
$config['en_ids_12365'] = array(11019,4231,4601,12419,10573,12589,7347,6255,6146,4983,7545,11047);
$config['en_all_12365'] = array(
    11019 => array(
        'm_icon' => '<i class="fas fa-step-backward note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PREVIOUS',
        'm_desc' => '',
        'm_parents' => array(12365,12413,10990,11025),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment note" aria-hidden="true"></i>',
        'm_name' => 'NOTE MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search note" aria-hidden="true"></i>',
        'm_name' => 'NOTE KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark note" aria-hidden="true"></i>',
        'm_name' => 'NOTE BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    12589 => array(
        'm_icon' => '<i class="fad fa-edit note" aria-hidden="true"></i>',
        'm_name' => 'NEXT EDITOR',
        'm_desc' => '',
        'm_parents' => array(12365,4527,10985,4535,12590),
    ),
    7347 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10989,11089,12365,6205,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'MENCH READS',
        'm_desc' => '',
        'm_parents' => array(12467,10964,11089,12410,12365,6771,12228,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ INCOMPLETES',
        'm_desc' => '',
        'm_parents' => array(12446,10989,11089,12365,12228,4527),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCES',
        'm_desc' => '',
        'm_parents' => array(12450,10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCE TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down" aria-hidden="true"></i>',
        'm_name' => 'NOTE ADMIN MENU',
        'm_desc' => '',
        'm_parents' => array(10984,12365,4527,11040),
    ),
);

//SOURCE:
$config['en_ids_4536'] = array(12640,12467,7305,6225,3000,12634,12289,11089,10645,4758,6206,12437);
$config['en_all_4536'] = array(
    12640 => array(
        'm_icon' => '<i class="fad fa-shield-check source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT SOURCES',
        'm_desc' => '',
        'm_parents' => array(12639,4536),
    ),
    12467 => array(
        'm_icon' => '<i class="fas fa-circle" aria-hidden="true"></i>',
        'm_name' => 'MENCH COINS',
        'm_desc' => '',
        'm_parents' => array(4527,4536,11035),
    ),
    7305 => array(
        'm_icon' => '<i class="fas fa-layer-group source" aria-hidden="true"></i>',
        'm_name' => 'MENCH PLATFORM',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    6225 => array(
        'm_icon' => '<i class="fad fa-user-cog source" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(10876,12500,4536,11035,4527),
    ),
    3000 => array(
        'm_icon' => '<i class="fad fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION SOURCE TYPES',
        'm_desc' => '',
        'm_parents' => array(4536,4527),
    ),
    12634 => array(
        'm_icon' => '<i class="fad fa-gift source" aria-hidden="true"></i>',
        'm_name' => 'PLAYER EARLY SIGNUP GIFTS',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    12289 => array(
        'm_icon' => '<i class="fad fa-paw source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE AVATAR',
        'm_desc' => '',
        'm_parents' => array(4536,6225),
    ),
    11089 => array(
        'm_icon' => '<i class="fad fa-crop-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4536,4527),
    ),
    10645 => array(
        'm_icon' => '<i class="fas fa-sync source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE READS',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    4758 => array(
        'm_icon' => '<i class="fas fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE SETTINGS',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    6206 => array(
        'm_icon' => '<i class="far fa-table source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TABLE',
        'm_desc' => '',
        'm_parents' => array(4527,7735,4536),
    ),
    12437 => array(
        'm_icon' => '<i class="fad fa-trophy source" aria-hidden="true"></i>',
        'm_name' => 'TOP SOURCES',
        'm_desc' => '',
        'm_parents' => array(4536,12489,11035,11986),
    ),
);

//NOTE:
$config['en_ids_4535'] = array(12589,12591,12592,10573,12419,4250,12453,4601,11021,4229,4228,10686,10663,10664,10643,6226,4231,4485,10676,10678,10679,10677,11160,6768,10681,10675,12450,4983,7545,7302,10671,6201,10662,10648,10650,10644,10651,4993,5001,10625,5943,12318,5865,4999,4998,5000,5981,11956,5982,5003,10672,4246,10653,4259,10657,4261,10669,4260,4319,7657,4230,10656,4255,4318,10659,10673,4256,4258,4257,10689,10646,7504,10654,5007,4994);
$config['en_all_4535'] = array(
    12589 => array(
        'm_icon' => '<i class="fad fa-edit note" aria-hidden="true"></i>',
        'm_name' => 'NEXT EDITOR',
        'm_desc' => '',
        'm_parents' => array(12365,4527,10985,4535,12590),
    ),
    12591 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'NEXT EDITOR ADD SOURCE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,12589),
    ),
    12592 => array(
        'm_icon' => '<i class="fas fa-minus-circle source" aria-hidden="true"></i>',
        'm_name' => 'NEXT EDITOR REMOVE SOURCE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,12589),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark note" aria-hidden="true"></i>',
        'm_name' => 'NOTE BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,4535,12149,12141,10593,4593),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone note"></i>',
        'm_name' => 'NOTE FEATURE REQUEST',
        'm_desc' => '',
        'm_parents' => array(12137,4535,4755,4593,5967),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search note" aria-hidden="true"></i>',
        'm_name' => 'NOTE KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    11021 => array(
        'm_icon' => '<i class="fad fa-crop-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4527,4535),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(4535,4527,6410,6283,4593,4486),
    ),
    4228 => array(
        'm_icon' => '<i class="fad fa-play-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK FIXED',
        'm_desc' => '',
        'm_parents' => array(4535,6410,4593,4486),
    ),
    10686 => array(
        'm_icon' => '<i class="fad fa-times note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10663 => array(
        'm_icon' => '<i class="fad fa-coin note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK UPDATE MARKS',
        'm_desc' => '',
        'm_parents' => array(4535,4228,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4229,10658),
    ),
    10643 => array(
        'm_icon' => '<i class="fas fa-yin-yang mench-spin note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK YIN YANG',
        'm_desc' => '',
        'm_parents' => array(4535,4593,6410,4486),
    ),
    6226 => array(
        'm_icon' => '<i class="fad fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE MASS UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment note" aria-hidden="true"></i>',
        'm_name' => 'NOTE MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS',
        'm_desc' => '',
        'm_parents' => array(12408,4535,4527),
    ),
    10676 => array(
        'm_icon' => '<i class="fad fa-bars note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS SORTED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10678 => array(
        'm_icon' => '<i class="fad fa-trash-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10658,4593),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10593,10658),
    ),
    10677 => array(
        'm_icon' => '<i class="fad fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    11160 => array(
        'm_icon' => '<i class="fas fa-info-circle note"></i>',
        'm_name' => 'NOTE READS',
        'm_desc' => '',
        'm_parents' => array(4535),
    ),
    6768 => array(
        'm_icon' => '<i class="far fa-cog note" aria-hidden="true"></i>',
        'm_name' => 'NOTE SETTINGS',
        'm_desc' => '',
        'm_parents' => array(4535),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-bars note" aria-hidden="true"></i>',
        'm_name' => 'NOTE SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4535,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fad fa-bars note" aria-hidden="true"></i>',
        'm_name' => 'NOTE SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-pen-square source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCE REQUEST',
        'm_desc' => '',
        'm_parents' => array(4593,4755,4535,5967),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCES',
        'm_desc' => '',
        'm_parents' => array(12450,10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCE TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
    7302 => array(
        'm_icon' => '<i class="far fa-chart-bar note" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATS',
        'm_desc' => '',
        'm_parents' => array(4527,4535),
    ),
    10671 => array(
        'm_icon' => '<i class="fad fa-trash-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATUS ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593),
    ),
    6201 => array(
        'm_icon' => '<i class="far fa-table note"></i>',
        'm_name' => 'NOTE TABLE',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7735,4535),
    ),
    10662 => array(
        'm_icon' => '<i class="fad fa-hashtag note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE LINK',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10648 => array(
        'm_icon' => '<i class="fad fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593),
    ),
    10650 => array(
        'm_icon' => '<i class="fad fa-clock note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE TIME',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    10644 => array(
        'm_icon' => '<i class="fad fa-bullseye-arrow note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE TITLE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593),
    ),
    10651 => array(
        'm_icon' => '<i class="fad fa-shapes note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE TYPE',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    4993 => array(
        'm_icon' => '<i class="fad fa-eye note" aria-hidden="true"></i>',
        'm_name' => 'NOTE VIEWED',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    5001 => array(
        'm_icon' => '<i class="source fad fa-sticky-note"></i>',
        'm_name' => 'PORTFOLIO EDITOR CONTENT REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="source fad fa-user-circle"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON UPDATE FOR ALL',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    12318 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON UPDATE IF MISSING',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="source fad fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR LINK STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="source fad fa-layer-plus"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="source fad fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE IF ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="source fad fa-layer-minus"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE REMOVE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="source fad fa-sliders-h"></i>',
        'm_name' => 'PORTFOLIO EDITOR STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    10672 => array(
        'm_icon' => '<i class="fad fa-trash-alt source"></i>',
        'm_name' => 'SOURCE ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    10653 => array(
        'm_icon' => '<i class="fad fa-user-circle source"></i>',
        'm_name' => 'SOURCE ICON UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10645),
    ),
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'SOURCE LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10658,10645),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10669 => array(
        'm_icon' => '<i class="fab fa-font-awesome-alt source"></i>',
        'm_name' => 'SOURCE LINK ICON',
        'm_desc' => '',
        'm_parents' => array(4535,4593,6198,4592),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="fad fa-sort-numeric-down source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK INTEGER',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    7657 => array(
        'm_icon' => '<i class="fas fa-divide source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK PERCENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="fad fa-link rotate90 source"></i>',
        'm_name' => 'SOURCE LINK RAW',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    10656 => array(
        'm_icon' => '<i class="fad fa-sliders-h source"></i>',
        'm_name' => 'SOURCE LINK STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10645),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'SOURCE LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="fad fa-clock source"></i>',
        'm_name' => 'SOURCE LINK TIME',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    10659 => array(
        'm_icon' => '<i class="fad fa-plug source"></i>',
        'm_name' => 'SOURCE LINK TYPE UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10658,4593,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="fad fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10645,4593,10658),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK URL',
        'm_desc' => '',
        'm_parents' => array(11080,4535,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'SOURCE LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source"></i>',
        'm_name' => 'SOURCE LINK WIDGET',
        'm_desc' => '',
        'm_parents' => array(12605,12524,12403,4535,4593,4592,4537,4506),
    ),
    10689 => array(
        'm_icon' => '<i class="fad fa-share-alt rotate90 source"></i>',
        'm_name' => 'SOURCE MERGED IN SOURCE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10645),
    ),
    10646 => array(
        'm_icon' => '<i class="fad fa-fingerprint source"></i>',
        'm_name' => 'SOURCE NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10645),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REVIEW TRIGGER',
        'm_desc' => '',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    10654 => array(
        'm_icon' => '<i class="fad fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
    5007 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TOGGLE SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    4994 => array(
        'm_icon' => '<i class="fad fa-eye source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE VIEWED',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
);

//READ:
$config['en_ids_6205'] = array(4341,12024,12129,12336,12334,4554,7757,6155,12106,6415,6559,6560,6556,6578,7611,4556,4555,7347,7563,10690,4266,4267,6149,4283,6969,4275,7610,12489,4282,6563,5967,10683,6132,4570,7702,7495,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,6771,7560,7561,7564,7559,7558,6143,4235,7304,12197,7492,4552,6140,12328,7578,6224,10658,4553,7562,6157,7489,12117,4559,6144,7485,7486,6997);
$config['en_all_6205'] = array(
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas read" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(12588,11035,4527,7735,6205),
    ),
    12024 => array(
        'm_icon' => '<i class="fas fa-flag read" aria-hidden="true"></i>',
        'm_name' => 'MENCH MILESTONES',
        'm_desc' => '',
        'm_parents' => array(1,6205),
    ),
    12129 => array(
        'm_icon' => '<i class="fas fa-times-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(6205,6153,4593),
    ),
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    7757 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED MANUAL',
        'm_desc' => '',
        'm_parents' => array(6205,10888,10639,4506,6150,4593,4755),
    ),
    12106 => array(
        'm_icon' => '<i class="read fad fa-vote-yea read" aria-hidden="true"></i>',
        'm_name' => 'READ CHANNEL VOTE',
        'm_desc' => '',
        'm_parents' => array(6205,4593),
    ),
    6415 => array(
        'm_icon' => '<i class="fad fa-trash-alt read" aria-hidden="true"></i>',
        'm_name' => 'READ CLEAR ALL',
        'm_desc' => '',
        'm_parents' => array(6205,11035,4755,6418,4593,6414),
    ),
    6559 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    7611 => array(
        'm_icon' => '<i class="read fad fa-hand-pointer"></i>',
        'm_name' => 'READ ENGAGED NOTE POST',
        'm_desc' => '',
        'm_parents' => array(6205,10639,7610,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    7347 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10989,11089,12365,6205,12228,4527),
    ),
    7563 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ MAGIC-READ',
        'm_desc' => '',
        'm_parents' => array(6205,4755,7569,4593),
    ),
    10690 => array(
        'm_icon' => '<i class="read fad fa-upload"></i>',
        'm_name' => 'READ MEDIA UPLOADED',
        'm_desc' => '',
        'm_parents' => array(6205,6153,4593,10658),
    ),
    4266 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER OPT-IN',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ NOTE CONSIDERED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ NOTE LISTED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="read fad fa-megaphone"></i>',
        'm_name' => 'READ NOTE RECOMMENDED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="read fad fa-search"></i>',
        'm_name' => 'READ NOTE SEARCH',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6554,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="read fad fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ NOTE STARTED',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593),
    ),
    12489 => array(
        'm_icon' => '<i class="far fa-medal read"></i>',
        'm_name' => 'READ OPENED LEADERBOARD',
        'm_desc' => '',
        'm_parents' => array(4755,6205,4593,6222),
    ),
    4282 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(6205,4755,6222,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4593,4755,4280),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ READ CC',
        'm_desc' => '',
        'm_parents' => array(6205,4506,4527,7569,4755,4593),
    ),
    10683 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ READ EMAIL',
        'm_desc' => '',
        'm_parents' => array(6205,6153,10658,4593,7654),
    ),
    6132 => array(
        'm_icon' => '<i class="fad fa-bars read" aria-hidden="true"></i>',
        'm_name' => 'READ READS SORTED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6153,4506,4755,4593),
    ),
    4570 => array(
        'm_icon' => '<i class="read fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED',
        'm_desc' => '',
        'm_parents' => array(6205,10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED NOTE',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4593,4755,7569),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(6205,12227,7347,4755,4593),
    ),
    4577 => array(
        'm_icon' => '<i class="read fad fa-user-plus"></i>',
        'm_name' => 'READ SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ SENT FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="read fad fa-location-circle"></i>',
        'm_name' => 'READ SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(6205,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ SENT MESSENGER READ',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="read fad fa-cloud-download"></i>',
        'm_name' => 'READ SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="read fad fa-user-tag"></i>',
        'm_name' => 'READ SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(6205,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="read fad fa-comment-exclamation"></i>',
        'm_name' => 'READ SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    6771 => array(
        'm_icon' => '<i class="far fa-cog read" aria-hidden="true"></i>',
        'm_name' => 'READ SETTINGS',
        'm_desc' => '',
        'm_parents' => array(6205),
    ),
    7560 => array(
        'm_icon' => '<i class="fad fa-sign-in read" aria-hidden="true"></i>',
        'm_name' => 'READ SIGNIN FROM NOTE',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,6146,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ STARTED',
        'm_desc' => '',
        'm_parents' => array(6205,12227,7347,5967,4755,4593),
    ),
    7304 => array(
        'm_icon' => '<i class="far fa-chart-bar read"></i>',
        'm_name' => 'READ STATS',
        'm_desc' => 'An overview of key link stats',
        'm_parents' => array(10888,4527,6205),
    ),
    12197 => array(
        'm_icon' => '<i class="fad fa-tags read" aria-hidden="true"></i>',
        'm_name' => 'READ TAG SOURCE',
        'm_desc' => '',
        'm_parents' => array(6205,7545,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4755,4593,4280),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(6205,12326,12227,6410,4229,4755,4593),
    ),
    12328 => array(
        'm_icon' => '<i class="fad fa-sync read"></i>',
        'm_name' => 'READ UPDATE COMPLETION',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,10658,6153),
    ),
    7578 => array(
        'm_icon' => '<i class="read fad fa-key"></i>',
        'm_name' => 'READ UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(6205,6222,10658,6153,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="read fad fa-sync"></i>',
        'm_name' => 'READ UPDATE PROFILE',
        'm_desc' => '',
        'm_parents' => array(6205,4755,6222,4593),
    ),
    10658 => array(
        'm_icon' => '<i class="fas fa-sync read"></i>',
        'm_name' => 'READ UPDATES',
        'm_desc' => '',
        'm_parents' => array(4527,6205),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    7562 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ WELCOME',
        'm_desc' => '',
        'm_parents' => array(6205,4755,7569,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//NOTE TYPE INSTANTLY DONE:
$config['en_ids_12330'] = array(6677,6914,6907);
$config['en_all_12330'] = array(
    6677 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'READ & NEXT',
        'm_desc' => '',
        'm_parents' => array(12330,7585,4559,6192),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes " aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ALL',
        'm_desc' => '',
        'm_parents' => array(10985,12330,7486,7485,6140,6192,7585,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube " aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ANY',
        'm_desc' => '',
        'm_parents' => array(10985,12330,7486,7485,6140,7585,7309,6997,6193),
    ),
);

//READ UNLOCKS:
$config['en_ids_12327'] = array(7485,7486,6997);
$config['en_all_12327'] = array(
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//READ NOTE LINKS:
$config['en_ids_12326'] = array(12336,12334,6140);
$config['en_all_12326'] = array(
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(6205,12326,12227,6410,4229,4755,4593),
    ),
);

//NOTE TYPE MANUAL INPUT:
$config['en_ids_12324'] = array(6683,7637);
$config['en_all_12324'] = array(
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard " aria-hidden="true"></i>',
        'm_name' => 'REPLY & NEXT',
        'm_desc' => '',
        'm_parents' => array(12324,6144,7585,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD & NEXT',
        'm_desc' => '',
        'm_parents' => array(12324,12117,7751,7585,6192),
    ),
);

//TEMPLATE NOTE MESSAGES:
$config['en_ids_12322'] = array(12419,4601,4231);
$config['en_all_12322'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt note" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search note" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment note" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
);

//TEMPLATE NOTE READ:
$config['en_ids_12321'] = array(12273,10573,7545);
$config['en_all_12321'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'MENCH NOTES',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark note" aria-hidden="true"></i>',
        'm_name' => 'NOTE BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCE TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
);

//SOURCE AVATAR SUPER:
$config['en_ids_12279'] = array(12280,12281,12282,12286,12287,12288,12308,12309,12310,12234,12233,10965,12236,12235,10979,12295,12294,12293,12296,12297,12298,12300,12301,12299,12237,12238,10978,12314,12315,12316,12240,12239,10963,12241,12242,12207,12244,12243,10966,12245,12246,10976,12248,12247,10962,12249,12250,10975,12252,12251,10982,12253,12254,10970,12302,12303,12304,12256,12255,10972,12306,12307,12305,12257,12258,10969,12312,12313,12311,12260,12259,10960,12277,12276,12278,12439,12262,10981,12264,12263,10968,12265,12266,10974,12290,12291,12292,12268,12267,12206,12269,12270,10958,12285,12284,12283,12272,12271,12231);
$config['en_all_12279'] = array(
    12280 => array(
        'm_icon' => '<i class="fas fa-alicorn source"></i>',
        'm_name' => 'ALICORN BOLD',
        'm_desc' => '',
        'm_parents' => array(10983,12279),
    ),
    12281 => array(
        'm_icon' => '<i class="far fa-alicorn source"></i>',
        'm_name' => 'ALICORN LIGHT',
        'm_desc' => '',
        'm_parents' => array(10983,12279),
    ),
    12282 => array(
        'm_icon' => '<i class="fad fa-alicorn source"></i>',
        'm_name' => 'ALICORN MIX',
        'm_desc' => '',
        'm_parents' => array(10983,12279),
    ),
    12286 => array(
        'm_icon' => '<i class="fas fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12287 => array(
        'm_icon' => '<i class="far fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12288 => array(
        'm_icon' => '<i class="fad fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12308 => array(
        'm_icon' => '<i class="fas fa-spider-black-widow source"></i>',
        'm_name' => 'BLACK WIDOW BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12309 => array(
        'm_icon' => '<i class="far fa-spider-black-widow source"></i>',
        'm_name' => 'BLACK WIDOW LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12310 => array(
        'm_icon' => '<i class="fad fa-spider-black-widow source"></i>',
        'm_name' => 'BLACK WIDOW MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12234 => array(
        'm_icon' => '<i class="fas fa-dog source"></i>',
        'm_name' => 'DOGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12233 => array(
        'm_icon' => '<i class="far fa-dog source"></i>',
        'm_name' => 'DOGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10965 => array(
        'm_icon' => '<i class="fad fa-dog source"></i>',
        'm_name' => 'DOGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12236 => array(
        'm_icon' => '<i class="fas fa-duck source" aria-hidden="true"></i>',
        'm_name' => 'DONALD BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12235 => array(
        'm_icon' => '<i class="far fa-duck source" aria-hidden="true"></i>',
        'm_name' => 'DONALD LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10979 => array(
        'm_icon' => '<i class="fad fa-duck source"></i>',
        'm_name' => 'DONALD MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12295 => array(
        'm_icon' => '<i class="fas fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12294 => array(
        'm_icon' => '<i class="far fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12293 => array(
        'm_icon' => '<i class="fad fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12296 => array(
        'm_icon' => '<i class="fas fa-dragon source"></i>',
        'm_name' => 'DRAGON BOLD',
        'm_desc' => '',
        'm_parents' => array(10967,12279),
    ),
    12297 => array(
        'm_icon' => '<i class="far fa-dragon source"></i>',
        'm_name' => 'DRAGON LIGHT',
        'm_desc' => '',
        'm_parents' => array(10967,12279),
    ),
    12298 => array(
        'm_icon' => '<i class="fad fa-dragon source"></i>',
        'm_name' => 'DRAGON MIX',
        'm_desc' => '',
        'm_parents' => array(10967,12279),
    ),
    12300 => array(
        'm_icon' => '<i class="fas fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12301 => array(
        'm_icon' => '<i class="far fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12299 => array(
        'm_icon' => '<i class="fad fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12237 => array(
        'm_icon' => '<i class="fas fa-fish source" aria-hidden="true"></i>',
        'm_name' => 'FISHY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12238 => array(
        'm_icon' => '<i class="far fa-fish source" aria-hidden="true"></i>',
        'm_name' => 'FISHY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10978 => array(
        'm_icon' => '<i class="fad fa-fish source"></i>',
        'm_name' => 'FISHY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12314 => array(
        'm_icon' => '<i class="fas fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12315 => array(
        'm_icon' => '<i class="far fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12316 => array(
        'm_icon' => '<i class="fad fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12240 => array(
        'm_icon' => '<i class="fas fa-hippo source" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12239 => array(
        'm_icon' => '<i class="far fa-hippo source" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10963 => array(
        'm_icon' => '<i class="fad fa-hippo source"></i>',
        'm_name' => 'HIPPOY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12241 => array(
        'm_icon' => '<i class="fas fa-badger-honey source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12242 => array(
        'm_icon' => '<i class="far fa-badger-honey source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12207 => array(
        'm_icon' => '<i class="fad fa-badger-honey source"></i>',
        'm_name' => 'HONEY BADGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12244 => array(
        'm_icon' => '<i class="fas fa-deer source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12243 => array(
        'm_icon' => '<i class="far fa-deer source" aria-hidden="true"></i>',
        'm_name' => 'HONEY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10966 => array(
        'm_icon' => '<i class="fad fa-deer source"></i>',
        'm_name' => 'HONEY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12245 => array(
        'm_icon' => '<i class="fas fa-horse source" aria-hidden="true"></i>',
        'm_name' => 'HORSY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12246 => array(
        'm_icon' => '<i class="far fa-horse source" aria-hidden="true"></i>',
        'm_name' => 'HORSY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10976 => array(
        'm_icon' => '<i class="fad fa-horse source"></i>',
        'm_name' => 'HORSY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12248 => array(
        'm_icon' => '<i class="fas fa-monkey source" aria-hidden="true"></i>',
        'm_name' => 'HUMAN BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12247 => array(
        'm_icon' => '<i class="far fa-monkey source" aria-hidden="true"></i>',
        'm_name' => 'HUMAN LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10962 => array(
        'm_icon' => '<i class="fad fa-monkey source"></i>',
        'm_name' => 'HUMAN MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12249 => array(
        'm_icon' => '<i class="fas fa-kiwi-bird source" aria-hidden="true"></i>',
        'm_name' => 'KIWI BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12250 => array(
        'm_icon' => '<i class="far fa-kiwi-bird source" aria-hidden="true"></i>',
        'm_name' => 'KIWI LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10975 => array(
        'm_icon' => '<i class="fad fa-kiwi-bird source"></i>',
        'm_name' => 'KIWI MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12252 => array(
        'm_icon' => '<i class="fas fa-cat source" aria-hidden="true"></i>',
        'm_name' => 'MIMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12251 => array(
        'm_icon' => '<i class="far fa-cat source" aria-hidden="true"></i>',
        'm_name' => 'MIMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10982 => array(
        'm_icon' => '<i class="fad fa-cat source"></i>',
        'm_name' => 'MIMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12253 => array(
        'm_icon' => '<i class="fas fa-cow source" aria-hidden="true"></i>',
        'm_name' => 'MOMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12254 => array(
        'm_icon' => '<i class="far fa-cow source" aria-hidden="true"></i>',
        'm_name' => 'MOMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10970 => array(
        'm_icon' => '<i class="fad fa-cow source"></i>',
        'm_name' => 'MOMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12302 => array(
        'm_icon' => '<i class="fas fa-narwhal source"></i>',
        'm_name' => 'NARWHAL BOLD',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12303 => array(
        'm_icon' => '<i class="far fa-narwhal source"></i>',
        'm_name' => 'NARWHAL LIGHT',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12304 => array(
        'm_icon' => '<i class="fad fa-narwhal source"></i>',
        'm_name' => 'NARWHAL MIX',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12256 => array(
        'm_icon' => '<i class="fas fa-turtle source" aria-hidden="true"></i>',
        'm_name' => 'NINJA BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12255 => array(
        'm_icon' => '<i class="far fa-turtle source" aria-hidden="true"></i>',
        'm_name' => 'NINJA LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10972 => array(
        'm_icon' => '<i class="fad fa-turtle source"></i>',
        'm_name' => 'NINJA MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12306 => array(
        'm_icon' => '<i class="fas fa-pegasus source"></i>',
        'm_name' => 'PEGASUS BOLD',
        'm_desc' => '',
        'm_parents' => array(10985,12279),
    ),
    12307 => array(
        'm_icon' => '<i class="far fa-pegasus source"></i>',
        'm_name' => 'PEGASUS LIGHT',
        'm_desc' => '',
        'm_parents' => array(10985,12279),
    ),
    12305 => array(
        'm_icon' => '<i class="fad fa-pegasus source" aria-hidden="true"></i>',
        'm_name' => 'PEGASUS MIX',
        'm_desc' => '',
        'm_parents' => array(10985,12279),
    ),
    12257 => array(
        'm_icon' => '<i class="fas fa-pig source" aria-hidden="true"></i>',
        'm_name' => 'PIGGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12258 => array(
        'm_icon' => '<i class="far fa-pig source" aria-hidden="true"></i>',
        'm_name' => 'PIGGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10969 => array(
        'm_icon' => '<i class="fad fa-pig source"></i>',
        'm_name' => 'PIGGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12312 => array(
        'm_icon' => '<i class="fas fa-ram source"></i>',
        'm_name' => 'RAM BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12313 => array(
        'm_icon' => '<i class="far fa-ram source"></i>',
        'm_name' => 'RAM LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12311 => array(
        'm_icon' => '<i class="fad fa-ram source"></i>',
        'm_name' => 'RAM MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12260 => array(
        'm_icon' => '<i class="fas fa-rabbit source" aria-hidden="true"></i>',
        'm_name' => 'ROGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12259 => array(
        'm_icon' => '<i class="far fa-rabbit source" aria-hidden="true"></i>',
        'm_name' => 'ROGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10960 => array(
        'm_icon' => '<i class="fad fa-rabbit source"></i>',
        'm_name' => 'ROGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12277 => array(
        'm_icon' => '<i class="fas fa-deer-rudolph source" aria-hidden="true"></i>',
        'm_name' => 'RUDOLPH BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12276 => array(
        'm_icon' => '<i class="far fa-deer-rudolph source" aria-hidden="true"></i>',
        'm_name' => 'RUDOLPH LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12278 => array(
        'm_icon' => '<i class="fad fa-deer-rudolph source" aria-hidden="true"></i>',
        'm_name' => 'RUDOLPH MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12439 => array(
        'm_icon' => '<i class="fad fa-crow source" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12262 => array(
        'm_icon' => '<i class="far fa-crow source" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10981 => array(
        'm_icon' => '<i class="fad fa-crow source"></i>',
        'm_name' => 'RUSSEL MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12264 => array(
        'm_icon' => '<i class="fas fa-sheep source" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12263 => array(
        'm_icon' => '<i class="far fa-sheep source" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10968 => array(
        'm_icon' => '<i class="fad fa-sheep source"></i>',
        'm_name' => 'SHEEPY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12265 => array(
        'm_icon' => '<i class="fas fa-snake source" aria-hidden="true"></i>',
        'm_name' => 'SNAKY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12266 => array(
        'm_icon' => '<i class="far fa-snake source" aria-hidden="true"></i>',
        'm_name' => 'SNAKY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10974 => array(
        'm_icon' => '<i class="fad fa-snake source"></i>',
        'm_name' => 'SNAKY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12290 => array(
        'm_icon' => '<i class="fas fa-cat-space source"></i>',
        'm_name' => 'SPACE CAT BOLD',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12291 => array(
        'm_icon' => '<i class="far fa-cat-space source"></i>',
        'm_name' => 'SPACE CAT LIGHT',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12292 => array(
        'm_icon' => '<i class="fad fa-cat-space source"></i>',
        'm_name' => 'SPACE CAT MIX',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12268 => array(
        'm_icon' => '<i class="fas fa-spider source" aria-hidden="true"></i>',
        'm_name' => 'SPIDER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12267 => array(
        'm_icon' => '<i class="far fa-spider source" aria-hidden="true"></i>',
        'm_name' => 'SPIDER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12206 => array(
        'm_icon' => '<i class="fad fa-spider source"></i>',
        'm_name' => 'SPIDER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12269 => array(
        'm_icon' => '<i class="fas fa-squirrel source" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12270 => array(
        'm_icon' => '<i class="far fa-squirrel source" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10958 => array(
        'm_icon' => '<i class="fad fa-squirrel source"></i>',
        'm_name' => 'SQUIRRELY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12285 => array(
        'm_icon' => '<i class="fas fa-unicorn source"></i>',
        'm_name' => 'UNICORN BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12284 => array(
        'm_icon' => '<i class="far fa-unicorn source"></i>',
        'm_name' => 'UNICORN LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12283 => array(
        'm_icon' => '<i class="fad fa-unicorn source"></i>',
        'm_name' => 'UNICORN MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12272 => array(
        'm_icon' => '<i class="fas fa-whale source" aria-hidden="true"></i>',
        'm_name' => 'WHALE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12271 => array(
        'm_icon' => '<i class="far fa-whale source" aria-hidden="true"></i>',
        'm_name' => 'WHALE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12231 => array(
        'm_icon' => '<i class="fad fa-whale source"></i>',
        'm_name' => 'WHALE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
);

//MENCH SOURCES:
$config['en_ids_12274'] = array(4251);
$config['en_all_12274'] = array(
    4251 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE CREATED',
        'm_desc' => '',
        'm_parents' => array(12401,12274,12149,12141,10645,10593,4593),
    ),
);

//MENCH NOTES:
$config['en_ids_12273'] = array(4983);
$config['en_all_12273'] = array(
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCES',
        'm_desc' => '',
        'm_parents' => array(12450,10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
);

//READ COMPLETION:
$config['en_ids_12229'] = array(6143,7492,6157,7489,12117,4559,6144,7485,7486,6997);
$config['en_all_12229'] = array(
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,4755,4593,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//READ TYPE GROUPS:
$config['en_ids_12228'] = array(12273,6255,12274,4983,7704,12229,6146,7347,12326,12227,12327);
$config['en_all_12228'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'MENCH NOTES',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'MENCH READS',
        'm_desc' => 'Read coin generated for a successful read',
        'm_parents' => array(12467,10964,11089,12410,12365,6771,12228,4527),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'MENCH SOURCES',
        'm_desc' => '',
        'm_parents' => array(12467,12228,4527,4758),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCES',
        'm_desc' => '',
        'm_parents' => array(12450,10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7704 => array(
        'm_icon' => '<i class="far fa-hand-pointer read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWERED',
        'm_desc' => '',
        'm_parents' => array(12228,4527),
    ),
    12229 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ COMPLETION',
        'm_desc' => 'Either complete or incomplete',
        'm_parents' => array(4527,12228),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ INCOMPLETES',
        'm_desc' => 'Read was skipped or failed to complete',
        'm_parents' => array(12446,10989,11089,12365,12228,4527),
    ),
    7347 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => 'The top of reading list where readers start their reading experience',
        'm_parents' => array(11035,10989,11089,12365,6205,12228,4527),
    ),
    12326 => array(
        'm_icon' => '<i class="fad fa-bars read" aria-hidden="true"></i>',
        'm_name' => 'READ NOTE LINKS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    12227 => array(
        'm_icon' => '<i class="fas fa-walking read" aria-hidden="true"></i>',
        'm_name' => 'READ PROGRESS',
        'm_desc' => 'Complete, Incomplete or Start',
        'm_parents' => array(12228,4527),
    ),
    12327 => array(
        'm_icon' => '<i class="fas fa-lock-open read"></i>',
        'm_name' => 'READ UNLOCKS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
);

//READ PROGRESS:
$config['en_ids_12227'] = array(12336,12334,7495,6143,4235,7492,6140,6157,7489,12117,4559,6144,7485,7486,6997);
$config['en_all_12227'] = array(
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(6205,12227,7347,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,6146,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ STARTED',
        'm_desc' => '',
        'm_parents' => array(6205,12227,7347,5967,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,4755,4593,6146),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(6205,12326,12227,6410,4229,4755,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//SOURCE TIMEZONE:
$config['en_ids_3289'] = array(3486,3487,3485,3488,3484,3483,3489,3482,3490,3481,3491,3480,3492,3479,3493,3478,3494,3495,3477,3496,3476,3475,3497,3498,3474,3499,3473,3500,3501);
$config['en_all_3289'] = array(
    3486 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT 0:00 LONDON',
        'm_desc' => '0',
        'm_parents' => array(3289),
    ),
    3487 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+01:00 AMSTERDAM/PARIS',
        'm_desc' => '1',
        'm_parents' => array(3289),
    ),
    3485 => array(
        'm_icon' => '<i class="fal fa-map" aria-hidden="true"></i>',
        'm_name' => 'GMT-01:00 AZORES',
        'm_desc' => '-1',
        'm_parents' => array(3289),
    ),
    3488 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+02:00 ATHENS',
        'm_desc' => '2',
        'm_parents' => array(3289),
    ),
    3484 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-02:00 STANLEY',
        'm_desc' => '-2',
        'm_parents' => array(3289),
    ),
    3483 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-03:00 BUENOS AIRES',
        'm_desc' => '-3',
        'm_parents' => array(3289),
    ),
    3489 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+03:00 MOSCOW',
        'm_desc' => '3',
        'm_parents' => array(3289),
    ),
    3482 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-03:30 NEWFOUNDLAND',
        'm_desc' => '-3.5',
        'm_parents' => array(3289),
    ),
    3490 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+03:30 TEHRAN',
        'm_desc' => '3.5',
        'm_parents' => array(3289),
    ),
    3481 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-04:00 ATLANTIC TIME',
        'm_desc' => '-4',
        'm_parents' => array(3289),
    ),
    3491 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+04:00 BAKU',
        'm_desc' => '4',
        'm_parents' => array(3289),
    ),
    3480 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-04:30 CARACAS',
        'm_desc' => '-4.5',
        'm_parents' => array(3289),
    ),
    3492 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+04:30 KABUL',
        'm_desc' => '4.5',
        'm_parents' => array(3289),
    ),
    3479 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-05:00 EASTERN TIME',
        'm_desc' => '-5',
        'm_parents' => array(3289),
    ),
    3493 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+05:00 KARACHI',
        'm_desc' => '5',
        'm_parents' => array(3289),
    ),
    3478 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-06:00 CENTRAL TIME',
        'm_desc' => '-6',
        'm_parents' => array(3289),
    ),
    3494 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+06:00 EKATERINBURG',
        'm_desc' => '6',
        'm_parents' => array(3289),
    ),
    3495 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+07:00 BANGKOK',
        'm_desc' => '7',
        'm_parents' => array(3289),
    ),
    3477 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-07:00 MOUNTAIN TIME',
        'm_desc' => '-7',
        'm_parents' => array(3289),
    ),
    3496 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+08:00 HONG KONG/PERTH',
        'm_desc' => '8',
        'm_parents' => array(3289),
    ),
    3476 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-08:00 PACIFIC STANDARD TIME',
        'm_desc' => '-8',
        'm_parents' => array(3289),
    ),
    3475 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-09:00 ALASKA',
        'm_desc' => '-9',
        'm_parents' => array(3289),
    ),
    3497 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+09:00 TOKYO',
        'm_desc' => '9',
        'm_parents' => array(3289),
    ),
    3498 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+09:30 DARWIN',
        'm_desc' => '9.5',
        'm_parents' => array(3289),
    ),
    3474 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-10:00 HAWAII',
        'm_desc' => '-10',
        'm_parents' => array(3289),
    ),
    3499 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+10:00 SYDNEY',
        'm_desc' => '10',
        'm_parents' => array(3289),
    ),
    3473 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT-11:00 SAMOA',
        'm_desc' => '-11',
        'm_parents' => array(3289),
    ),
    3500 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+11:00 VLADIVOSTOK',
        'm_desc' => '11',
        'm_parents' => array(3289),
    ),
    3501 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'GMT+12:00 FIJI',
        'm_desc' => '12',
        'm_parents' => array(3289),
    ),
);

//SOURCE GENDER:
$config['en_ids_3290'] = array(3292,3291,6121);
$config['en_all_3290'] = array(
    3292 => array(
        'm_icon' => '<i class="fal fa-female" aria-hidden="true"></i>',
        'm_name' => 'FEMALE',
        'm_desc' => 'f',
        'm_parents' => array(3290),
    ),
    3291 => array(
        'm_icon' => '<i class="fal fa-male" aria-hidden="true"></i>',
        'm_name' => 'MALE',
        'm_desc' => 'm',
        'm_parents' => array(3290),
    ),
    6121 => array(
        'm_icon' => '<i class="fal fa-venus-mars"></i>',
        'm_name' => 'OTHER GENDER',
        'm_desc' => '',
        'm_parents' => array(3290),
    ),
);

//READ TYPE ISSUE COINS:
$config['en_ids_12141'] = array(4250,6157,7489,4251,12117,4559,6144,7485,7486,6997);
$config['en_all_12141'] = array(
    4250 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,4535,12149,12141,10593,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE CREATED',
        'm_desc' => '',
        'm_parents' => array(12401,12274,12149,12141,10645,10593,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//NOTE STATUS FEATURED:
$config['en_ids_12138'] = array(12137);
$config['en_all_12138'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'NOTE FEATURED',
        'm_desc' => '',
        'm_parents' => array(10648,12138,7356,7355,4737),
    ),
);

//NOTE TEXT INPUTS:
$config['en_ids_12112'] = array(4535,4356,4736,4358,4739,4735);
$config['en_all_12112'] = array(
    4535 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE',
        'm_desc' => '',
        'm_parents' => array(12499,12112,10876,4527,12155,2738),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch " aria-hidden="true"></i>',
        'm_name' => 'NOTE READ TIME',
        'm_desc' => '',
        'm_parents' => array(12112,12420,10888,10650,6232,6201),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 " aria-hidden="true"></i>',
        'm_name' => 'NOTE TITLE',
        'm_desc' => '',
        'm_parents' => array(10990,12112,11071,10644,6232,6201),
    ),
    4358 => array(
        'm_icon' => '<i class="fad fa-comment-alt-check" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => '',
        'm_parents' => array(12420,10985,12112,10663,6103,6410,6232),
    ),
    4739 => array(
        'm_icon' => '<i class="fad fa-temperature-up" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(12420,12112,6402,6232),
    ),
    4735 => array(
        'm_icon' => '<i class="fad fa-temperature-down" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(12420,12112,6402,6232),
    ),
);

//MENCH CHANNELS UPCOMING:
$config['en_ids_12105'] = array(10895,3314,10896,10899,10898);
$config['en_all_12105'] = array(
    10895 => array(
        'm_icon' => '<i class="fab fa-amazon"></i>',
        'm_name' => 'ALEXA',
        'm_desc' => '',
        'm_parents' => array(12105),
    ),
    3314 => array(
        'm_icon' => '<i class="fab fa-slack"></i>',
        'm_name' => 'SLACK',
        'm_desc' => '',
        'm_parents' => array(12105,1326,2750),
    ),
    10896 => array(
        'm_icon' => '<i class="fab fa-telegram source"></i>',
        'm_name' => 'TELEGRAM',
        'm_desc' => '',
        'm_parents' => array(12105),
    ),
    10899 => array(
        'm_icon' => '<i class="fab fa-weixin isgreen"></i>',
        'm_name' => 'WECHAT',
        'm_desc' => '',
        'm_parents' => array(12105),
    ),
    10898 => array(
        'm_icon' => '<i class="fab fa-whatsapp-square isgreen"></i>',
        'm_name' => 'WHATSAPP',
        'm_desc' => '',
        'm_parents' => array(12105),
    ),
);

//NOTE DROPDOWNS:
$config['en_ids_12079'] = array(4737,4486,7585);
$config['en_all_12079'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link note" aria-hidden="true"></i>',
        'm_name' => 'TREE LINKS',
        'm_desc' => '',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random note" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
);

//SOURCE LAYOUT:
$config['en_ids_11089'] = array(12412,11030,10573,12273,12419,4231,4601,7545,7347,6255,6146,11029,4997);
$config['en_all_11089'] = array(
    12412 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE HEADER',
        'm_desc' => '',
        'm_parents' => array(11089),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-id-badge source" aria-hidden="true"></i>',
        'm_name' => 'PROFILES',
        'm_desc' => '',
        'm_parents' => array(12574,11089,11028),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark note" aria-hidden="true"></i>',
        'm_name' => 'NOTE BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'MENCH NOTES',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment note" aria-hidden="true"></i>',
        'm_name' => 'NOTE MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search note" aria-hidden="true"></i>',
        'm_name' => 'NOTE KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCE TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
    7347 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10989,11089,12365,6205,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'MENCH READS',
        'm_desc' => '',
        'm_parents' => array(12467,10964,11089,12410,12365,6771,12228,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ INCOMPLETES',
        'm_desc' => '',
        'm_parents' => array(12446,10989,11089,12365,12228,4527),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIOS',
        'm_desc' => '',
        'm_parents' => array(12574,12571,11089,11028),
    ),
    4997 => array(
        'm_icon' => '<i class="fad fa-edit source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR',
        'm_desc' => '',
        'm_parents' => array(12590,11029,12574,10967,11089,4758,4506,4527),
    ),
);

//IT:
$config['en_ids_10717'] = array(10761,10762,10763,10764,10765);
$config['en_all_10717'] = array(
    10761 => array(
        'm_icon' => '<i class="far fa-file-certificate"></i>',
        'm_name' => 'IT CERTIFICATION',
        'm_desc' => '',
        'm_parents' => array(10717),
    ),
    10762 => array(
        'm_icon' => '<i class="far fa-network-wired"></i>',
        'm_name' => 'NETWORK & SECURITY',
        'm_desc' => '',
        'm_parents' => array(10717),
    ),
    10763 => array(
        'm_icon' => '<i class="far fa-hdd"></i>',
        'm_name' => 'HARDWARE',
        'm_desc' => '',
        'm_parents' => array(10717),
    ),
    10764 => array(
        'm_icon' => '<i class="far fa-laptop-code"></i>',
        'm_name' => 'OPERATING SYSTEMS',
        'm_desc' => '',
        'm_parents' => array(10717),
    ),
    10765 => array(
        'm_icon' => '<i class="far fa-window"></i>',
        'm_name' => 'OTHER IT & SOFWTARE',
        'm_desc' => '',
        'm_parents' => array(10717),
    ),
);

//READ ALL CONNECTIONS:
$config['en_ids_11081'] = array(4364,4369,4429,4368,4366,4371,4593);
$config['en_all_11081'] = array(
    4364 => array(
        'm_icon' => '<i class="fad fa-user-edit" aria-hidden="true"></i>',
        'm_name' => 'CREATOR',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fad fa-step-forward" aria-hidden="true"></i>',
        'm_name' => 'NEXT NOTE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fad fa-step-backward" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS NOTE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-id-badge" aria-hidden="true"></i>',
        'm_name' => 'PROFILE SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fad fa-link" aria-hidden="true"></i>',
        'm_name' => 'REFERENCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//PLATFORM VARIABLES:
$config['en_ids_6232'] = array(6203,6202,6159,6208,6168,6283,6228,6165,6162,6170,6161,6169,6167,4356,4737,4736,4486,7585,4358,6198,6160,6172,6207,6197,6177,4364,7694,4367,4372,6103,4369,4429,4368,4366,4370,4371,6186,4362,4593,4739,4735);
$config['en_all_6232'] = array(
    6203 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'FACEBOOK ATTACHMENT ID',
        'm_desc' => 'fb_att_id',
        'm_parents' => array(6232,6215,2793,6103),
    ),
    6202 => array(
        'm_icon' => '<i class="fas fa-plus-circle "></i>',
        'm_name' => 'NOTE ID',
        'm_desc' => 'in_id',
        'm_parents' => array(6232,6215,6201),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda " aria-hidden="true"></i>',
        'm_name' => 'NOTE METADATA',
        'm_desc' => 'in_metadata',
        'm_parents' => array(11049,6232,6201,6195),
    ),
    6208 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'NOTE METADATA ALGOLIA ID',
        'm_desc' => 'in__algolia_id',
        'm_parents' => array(6232,6215,3323,6159),
    ),
    6168 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'NOTE METADATA COMMON STEPS',
        'm_desc' => 'in__metadata_common_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6283 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'NOTE METADATA EXPANSION CONDITIONAL',
        'm_desc' => 'in__metadata_expansion_conditional',
        'm_parents' => array(6214,6232,6159),
    ),
    6228 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'NOTE METADATA EXPANSION STEPS',
        'm_desc' => 'in__metadata_expansion_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6165 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'NOTE METADATA EXPERTS',
        'm_desc' => 'in__metadata_experts',
        'm_parents' => array(6232,6214,6159),
    ),
    6162 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'NOTE METADATA MAXIMUM SECONDS',
        'm_desc' => 'in__metadata_max_seconds',
        'm_parents' => array(4739,6232,6214,4356,6159),
    ),
    6170 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'NOTE METADATA MAXIMUM STEPS',
        'm_desc' => 'in__metadata_max_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6161 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'NOTE METADATA MINIMUM SECONDS',
        'm_desc' => 'in__metadata_min_seconds',
        'm_parents' => array(4735,6232,6214,4356,6159),
    ),
    6169 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'NOTE METADATA MINIMUM STEPS',
        'm_desc' => 'in__metadata_min_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6167 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'NOTE METADATA SOURCES',
        'm_desc' => 'in__metadata_sources',
        'm_parents' => array(6232,6214,6159),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch " aria-hidden="true"></i>',
        'm_name' => 'NOTE READ TIME',
        'm_desc' => 'in_read_time',
        'm_parents' => array(12112,12420,10888,10650,6232,6201),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATUS',
        'm_desc' => 'in_status_source_id',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 " aria-hidden="true"></i>',
        'm_name' => 'NOTE TITLE',
        'm_desc' => 'in_title',
        'm_parents' => array(10990,12112,11071,10644,6232,6201),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link note" aria-hidden="true"></i>',
        'm_name' => 'NOTE TREE LINKS',
        'm_desc' => 'ln_type_source_id',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random note" aria-hidden="true"></i>',
        'm_name' => 'NOTE TYPE',
        'm_desc' => 'in_type_source_id',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    4358 => array(
        'm_icon' => '<i class="fad fa-comment-alt-check" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => 'tr__assessment_points',
        'm_parents' => array(12420,10985,12112,10663,6103,6410,6232),
    ),
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ICON',
        'm_desc' => 'en_icon',
        'm_parents' => array(12605,10653,5943,10625,6232,6206),
    ),
    6160 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ID',
        'm_desc' => 'en_id',
        'm_parents' => array(6232,6215,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda source"></i>',
        'm_name' => 'SOURCE METADATA',
        'm_desc' => 'en_metadata',
        'm_parents' => array(6232,3323,6206,6195),
    ),
    6207 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'SOURCE METADATA ALGOLIA ID',
        'm_desc' => 'en__algolia_id',
        'm_parents' => array(6232,6215,6172),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE NICKNAME',
        'm_desc' => 'en_name',
        'm_parents' => array(4269,12412,12232,6225,11072,10646,5000,4998,4999,6232,6206),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => 'en_status_source_id',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    4364 => array(
        'm_icon' => '<i class="fad fa-user-edit" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION CREATOR',
        'm_desc' => 'ln_creator_source_id',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    7694 => array(
        'm_icon' => '<i class="fad fa-project-diagram"></i>',
        'm_name' => 'TRANSACTION EXTERNAL ID',
        'm_desc' => 'ln_external_id',
        'm_parents' => array(6215,6232,4341),
    ),
    4367 => array(
        'm_icon' => '<i class="fad fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION ID',
        'm_desc' => 'ln_id',
        'm_parents' => array(6232,6215,4341),
    ),
    4372 => array(
        'm_icon' => '<i class="fad fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION MESSAGE',
        'm_desc' => 'ln_content',
        'm_parents' => array(7578,10679,10657,5001,6232,4341),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'TRANSACTION METADATA',
        'm_desc' => 'ln_metadata',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fad fa-step-forward" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION NEXT NOTE',
        'm_desc' => 'ln_next_note_id',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PORTFOLIO SOURCE',
        'm_desc' => 'ln_child_source_id',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fad fa-step-backward" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PREVIOUS NOTE',
        'm_desc' => 'ln_previous_note_id',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-id-badge" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PROFILE SOURCE',
        'm_desc' => 'ln_parent_source_id',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fad fa-bars" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION RANK',
        'm_desc' => 'ln_order',
        'm_parents' => array(10676,10675,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fad fa-link" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION REFERENCE',
        'm_desc' => 'ln_parent_transaction_id',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => 'ln_status_source_id',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="fad fa-clock" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TIME',
        'm_desc' => 'ln_timestamp',
        'm_parents' => array(6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => 'ln_type_source_id',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
    4739 => array(
        'm_icon' => '<i class="fad fa-temperature-up" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX PERCENTAGE',
        'm_desc' => 'tr__conditional_score_max',
        'm_parents' => array(12420,12112,6402,6232),
    ),
    4735 => array(
        'm_icon' => '<i class="fad fa-temperature-down" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN PERCENTAGE',
        'm_desc' => 'tr__conditional_score_min',
        'm_parents' => array(12420,12112,6402,6232),
    ),
);

//MEDIA FILE EXTENSIONS:
$config['en_ids_11080'] = array(4259,4261,4260,4256,4258);
$config['en_all_11080'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'pcm|wav|aiff|mp3|aac|ogg|wma|flac|alac|m4a|m4b|m4p',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'pdf|pdc|doc|docx|tex|txt|7z|rar|zip|csv|sql|tar|xml|exe',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'jpeg|jpg|png|gif|tiff|bmp|img|svg|ico|webp',
        'm_parents' => array(12605,12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => 'htm|html',
        'm_parents' => array(11080,4535,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'mp4|m4v|m4p|avi|mov|flv|f4v|f4p|f4a|f4b|wmv|webm|mkv|vob|ogv|ogg|3gp|mpg|mpeg|m2v',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
);

//MESSENGER MEDIA CODES:
$config['en_ids_11059'] = array(4259,4261,4260,4258);
$config['en_all_11059'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'audio',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'file',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'image',
        'm_parents' => array(12605,12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'video',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
);

//MESSENGER NOTIFICATION CODES:
$config['en_ids_11058'] = array(4458,4456,4457);
$config['en_all_11058'] = array(
    4458 => array(
        'm_icon' => '<i class="far fa-volume-mute" aria-hidden="true"></i>',
        'm_name' => 'DISABLED',
        'm_desc' => 'NO_PUSH',
        'm_parents' => array(11058),
    ),
    4456 => array(
        'm_icon' => '<i class="far fa-volume-up" aria-hidden="true"></i>',
        'm_name' => 'REGULAR',
        'm_desc' => 'REGULAR',
        'm_parents' => array(11058),
    ),
    4457 => array(
        'm_icon' => '<i class="far fa-volume-down" aria-hidden="true"></i>',
        'm_name' => 'SILENT',
        'm_desc' => 'SILENT_PUSH',
        'm_parents' => array(11058),
    ),
);

//PLATFORM CONFIG VARIABLES:
$config['en_ids_6404'] = array(12355,11077,11074,12124,11076,12587,11075,11064,11986,11065,11063,12156,11079,11060,12363,11073,12176,11071,11066,11057,11056,12331,12113,12427,12088,11072,12232,11061,11162,11163,12568,12565);
$config['en_all_6404'] = array(
    12355 => array(
        'm_icon' => '',
        'm_name' => 'DATE FORMAT FULL & WEEKDAY',
        'm_desc' => 'D M j G:i:s T Y',
        'm_parents' => array(6404),
    ),
    11077 => array(
        'm_icon' => '',
        'm_name' => 'FACEBOOK GRAPH VERSION',
        'm_desc' => 'v6.0',
        'm_parents' => array(4506,6404),
    ),
    11074 => array(
        'm_icon' => '',
        'm_name' => 'FACEBOOK MAX MESSAGE LENGTH',
        'm_desc' => '2000',
        'm_parents' => array(6404),
    ),
    12124 => array(
        'm_icon' => '',
        'm_name' => 'FACEBOOK MAX QUICK REPLIES',
        'm_desc' => '13',
        'm_parents' => array(6404),
    ),
    11076 => array(
        'm_icon' => '',
        'm_name' => 'FACEBOOK MENCH APP ID',
        'm_desc' => '1782431902047009',
        'm_parents' => array(6404),
    ),
    12587 => array(
        'm_icon' => '',
        'm_name' => 'FACEBOOK MENCH PAGE HANDLER',
        'm_desc' => 'menchdotcom',
        'm_parents' => array(6404),
    ),
    11075 => array(
        'm_icon' => '',
        'm_name' => 'FACEBOOK MENCH PAGE ID',
        'm_desc' => '381488558920384',
        'm_parents' => array(6404),
    ),
    11064 => array(
        'm_icon' => '',
        'm_name' => 'ITEMS PER PAGE',
        'm_desc' => '100',
        'm_parents' => array(6404),
    ),
    11986 => array(
        'm_icon' => '',
        'm_name' => 'LEADERBOARD VISIBLE',
        'm_desc' => '10',
        'm_parents' => array(6404),
    ),
    11065 => array(
        'm_icon' => '',
        'm_name' => 'LOGIN LINK VALID SECONDS',
        'm_desc' => '3600',
        'm_parents' => array(6404),
    ),
    11063 => array(
        'm_icon' => '',
        'm_name' => 'MAX FILE SIZE [MB]',
        'm_desc' => '25',
        'm_parents' => array(6404),
    ),
    12156 => array(
        'm_icon' => '<i class="fas fa-star note" aria-hidden="true"></i>',
        'm_name' => 'MENCH HOME NOTE ID',
        'm_desc' => '7766',
        'm_parents' => array(6404),
    ),
    11079 => array(
        'm_icon' => '',
        'm_name' => 'MENCH PLATFORM TIMEZONE',
        'm_desc' => 'America/Los_Angeles',
        'm_parents' => array(6404),
    ),
    11060 => array(
        'm_icon' => '',
        'm_name' => 'MENCH PLATFORM VERSION',
        'm_desc' => '1.3223',
        'm_parents' => array(6404),
    ),
    12363 => array(
        'm_icon' => '',
        'm_name' => 'MESSAGE FEATURED MAX LENGTH',
        'm_desc' => '233',
        'm_parents' => array(6404),
    ),
    11073 => array(
        'm_icon' => '',
        'm_name' => 'MESSAGE MAX LENGTH',
        'm_desc' => '1000',
        'm_parents' => array(6404),
    ),
    12176 => array(
        'm_icon' => '<i class="fad fa-clock note" aria-hidden="true"></i>',
        'm_name' => 'NOTE DEFAULT TIME SECONDS',
        'm_desc' => '30',
        'm_parents' => array(6404),
    ),
    11071 => array(
        'm_icon' => '<i class="fad fa-ruler-horizontal note" aria-hidden="true"></i>',
        'm_name' => 'NOTE TITLE MAX LENGTH',
        'm_desc' => '100',
        'm_parents' => array(6404),
    ),
    11066 => array(
        'm_icon' => '',
        'm_name' => 'PASSWORD MIN CHARACTERS',
        'm_desc' => '6',
        'm_parents' => array(6404),
    ),
    11057 => array(
        'm_icon' => '',
        'm_name' => 'READ MARKS MAX',
        'm_desc' => '89',
        'm_parents' => array(6404,4358),
    ),
    11056 => array(
        'm_icon' => '',
        'm_name' => 'READ MARKS MIN',
        'm_desc' => '-89',
        'm_parents' => array(6404,4358),
    ),
    12331 => array(
        'm_icon' => '',
        'm_name' => 'READ MIN TIME SHOW',
        'm_desc' => '120',
        'm_parents' => array(6404),
    ),
    12113 => array(
        'm_icon' => '',
        'm_name' => 'READ TIME MAX. SECONDS',
        'm_desc' => '7200',
        'm_parents' => array(4356,6404),
    ),
    12427 => array(
        'm_icon' => '',
        'm_name' => 'READ TIME MIN. SECONDS',
        'm_desc' => '3',
        'm_parents' => array(6404,4356),
    ),
    12088 => array(
        'm_icon' => '',
        'm_name' => 'SHOW TEXT COUNTER THRESHOLD',
        'm_desc' => '0.8',
        'm_parents' => array(6404),
    ),
    11072 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE NAME MAX LENGTH',
        'm_desc' => '233',
        'm_parents' => array(6404),
    ),
    12232 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE NAME MIN LENGTH',
        'm_desc' => '2',
        'm_parents' => array(6404),
    ),
    11061 => array(
        'm_icon' => '',
        'm_name' => 'SUBSCRIPTION FREE WEEKLY READS',
        'm_desc' => '55',
        'm_parents' => array(6404),
    ),
    11162 => array(
        'm_icon' => '',
        'm_name' => 'SUBSCRIPTION USD RATE MONTHLY',
        'm_desc' => '5',
        'm_parents' => array(6404),
    ),
    11163 => array(
        'm_icon' => '',
        'm_name' => 'SUBSCRIPTION USD RATE YEARLY',
        'm_desc' => '50',
        'm_parents' => array(6404),
    ),
    12568 => array(
        'm_icon' => '',
        'm_name' => 'WEIGHT ALGORITHM TRANSACTION RATE',
        'm_desc' => '1',
        'm_parents' => array(12569,6404),
    ),
    12565 => array(
        'm_icon' => '',
        'm_name' => 'WEIGHT ALGORITHM TREE RATE',
        'm_desc' => '89',
        'm_parents' => array(12569,6404),
    ),
);

//PLATFORM MEMORY JAVASCRIPT:
$config['en_ids_11054'] = array(2738,4737,7356,7355,6201,4486,7585,6404,6177,7357,6186);
$config['en_all_11054'] = array(
    2738 => array(
        'm_icon' => '<img src="/img/mench.png" class="mench-spin no-radius">',
        'm_name' => 'MENCH',
        'm_desc' => '',
        'm_parents' => array(12497,11054,12041,3303,7524,3325,3326,3324,4527,1,7312,2750),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'NOTE STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    6201 => array(
        'm_icon' => '<i class="far fa-table note"></i>',
        'm_name' => 'NOTE TABLE',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7735,4535),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link note" aria-hidden="true"></i>',
        'm_name' => 'NOTE TREE LINKS',
        'm_desc' => '',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random note" aria-hidden="true"></i>',
        'm_name' => 'NOTE TYPE',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    6404 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'PLATFORM CONFIG VARIABLES',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7254,6403),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    7357 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(12572,11054,4527),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
);

//NOTE ADMIN MENU:
$config['en_ids_11047'] = array(11051,11049,11050,11048);
$config['en_all_11047'] = array(
    11051 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'HISTORY',
        'm_desc' => '/ledger?any_in_id=',
        'm_parents' => array(11047),
    ),
    11049 => array(
        'm_icon' => '<i class="fas fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'REVIEW METADATA',
        'm_desc' => '/note/in_review_metadata/',
        'm_parents' => array(11047),
    ),
    11050 => array(
        'm_icon' => '<img src="https://partners.algolia.com/images/logos/algolia-logo-badge.svg">',
        'm_name' => 'UPDATE ALGOLIA',
        'm_desc' => '/note/cron__sync_algolia/in/',
        'm_parents' => array(7279,11047),
    ),
    11048 => array(
        'm_icon' => '<i class="far fa-magic" aria-hidden="true"></i>',
        'm_name' => 'UPDATE CACHE',
        'm_desc' => '/note/cron__sync_extra_insights/',
        'm_parents' => array(11047),
    ),
);

//MENCH NAVIGATION MENU:
$config['en_ids_11035'] = array(6287,11068,7291,12467,4341,6225,12581,12211,10573,4430,12205,6415,7347,7256,4269,12275,10957,7540,12437);
$config['en_all_11035'] = array(
    6287 => array(
        'm_icon' => '<i class="fad fa-user-shield source" aria-hidden="true"></i>',
        'm_name' => 'ADMIN PANEL',
        'm_desc' => '',
        'm_parents' => array(12500,10985,10876,11035,4527),
    ),
    11068 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'LOGIN LINK',
        'm_desc' => '',
        'm_parents' => array(11035,11065),
    ),
    7291 => array(
        'm_icon' => '<i class="fad fa-power-off source" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => '',
        'm_parents' => array(10876,12500,11035),
    ),
    12467 => array(
        'm_icon' => '<i class="fas fa-circle" aria-hidden="true"></i>',
        'm_name' => 'MENCH COINS',
        'm_desc' => '',
        'm_parents' => array(4527,4536,11035),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas read" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(12588,11035,4527,7735,6205),
    ),
    6225 => array(
        'm_icon' => '<i class="fad fa-user-cog source" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => 'Manage avatar, superpowers, subscription & name',
        'm_parents' => array(10876,12500,4536,11035,4527),
    ),
    12581 => array(
        'm_icon' => '<i class="fad fa-plus read" aria-hidden="true"></i>',
        'm_name' => 'NEW READ',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    12211 => array(
        'm_icon' => '<i class="fad fa-step-forward read" aria-hidden="true"></i>',
        'm_name' => 'NEXT READ',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark note" aria-hidden="true"></i>',
        'm_name' => 'NOTE BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-horse-head source" aria-hidden="true"></i>',
        'm_name' => 'PLAYERS',
        'm_desc' => '',
        'm_parents' => array(12639,12437,11035,10573,4983,6827,4426),
    ),
    12205 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PUBLIC PROFILE',
        'm_desc' => '',
        'm_parents' => array(12500,11035),
    ),
    6415 => array(
        'm_icon' => '<i class="fad fa-trash-alt read" aria-hidden="true"></i>',
        'm_name' => 'READ CLEAR ALL',
        'm_desc' => '',
        'm_parents' => array(6205,11035,4755,6418,4593,6414),
    ),
    7347 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10989,11089,12365,6205,12228,4527),
    ),
    7256 => array(
        'm_icon' => '<i class="fad fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCH MENCH',
        'm_desc' => '',
        'm_parents' => array(12497,10967,11035,3323),
    ),
    4269 => array(
        'm_icon' => '<i class="fad fa-sign-in-alt" aria-hidden="true"></i>',
        'm_name' => 'SIGN IN/UP',
        'm_desc' => '',
        'm_parents' => array(4527,11035),
    ),
    12275 => array(
        'm_icon' => '<i class="fas fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE MODIFY',
        'm_desc' => '',
        'm_parents' => array(12412,11035),
    ),
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(12502,12500,6225,11035,5007,4527),
    ),
    7540 => array(
        'm_icon' => '<i class="fad fa-university" aria-hidden="true"></i>',
        'm_name' => 'TERMS OF SERVICE',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    12437 => array(
        'm_icon' => '<i class="fad fa-trophy source" aria-hidden="true"></i>',
        'm_name' => 'TOP SOURCES',
        'm_desc' => '',
        'm_parents' => array(4536,12489,11035,11986),
    ),
);

//SOURCES LINKS DIRECTION:
$config['en_ids_11028'] = array(11030,11029);
$config['en_all_11028'] = array(
    11030 => array(
        'm_icon' => '<i class="fas fa-id-badge source" aria-hidden="true"></i>',
        'm_name' => 'PROFILES',
        'm_desc' => '',
        'm_parents' => array(12574,11089,11028),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIOS',
        'm_desc' => '',
        'm_parents' => array(12574,12571,11089,11028),
    ),
);

//NOTE FOOTER:
$config['en_ids_11018'] = array(11020);
$config['en_all_11018'] = array(
    11020 => array(
        'm_icon' => '<i class="fas fa-step-forward note" aria-hidden="true"></i>',
        'm_name' => 'NOTE NEXT',
        'm_desc' => '',
        'm_parents' => array(12413,11025,11018),
    ),
);

//NOTE LAYOUT:
$config['en_ids_11021'] = array(12365,11018);
$config['en_all_11021'] = array(
    12365 => array(
        'm_icon' => '<i class="fad fa-square note" aria-hidden="true"></i>',
        'm_name' => 'BODY',
        'm_desc' => '',
        'm_parents' => array(4527,11021),
    ),
    11018 => array(
        'm_icon' => '<i class="fad fa-browser note rotate180" aria-hidden="true"></i>',
        'm_name' => 'FOOTER',
        'm_desc' => '',
        'm_parents' => array(4527,11021),
    ),
);

//NOTE PREVIOUS SECTION:
$config['en_ids_10990'] = array(11019,4737,4736);
$config['en_all_10990'] = array(
    11019 => array(
        'm_icon' => '<i class="fas fa-step-backward note" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS',
        'm_desc' => '',
        'm_parents' => array(12365,12413,10990,11025),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 " aria-hidden="true"></i>',
        'm_name' => 'TITLE',
        'm_desc' => '',
        'm_parents' => array(10990,12112,11071,10644,6232,6201),
    ),
);

//SUPERPOWERS:
$config['en_ids_10957'] = array(10939,10984,10985,10964,10989,10988,10983,10967,10986);
$config['en_all_10957'] = array(
    10939 => array(
        'm_icon' => '<i class="fad fa-pen note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PEN',
        'm_desc' => 'Basic Note Taking',
        'm_parents' => array(10876,10957),
    ),
    10984 => array(
        'm_icon' => '<i class="fad fa-paint-brush-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE BRUSH',
        'm_desc' => 'Collaborative Note Taking',
        'm_parents' => array(10876,10983,10957),
    ),
    10985 => array(
        'm_icon' => '<i class="fad fa-magic note" aria-hidden="true"></i>',
        'm_name' => 'NOTE WAND',
        'm_desc' => 'Advance Note Taking',
        'm_parents' => array(10876,10984,10957),
    ),
    10964 => array(
        'm_icon' => '<i class="fad fa-glasses-alt read" aria-hidden="true"></i>',
        'm_name' => 'READ GLASSES',
        'm_desc' => 'View Players Read History',
        'm_parents' => array(10876,10939,10957),
    ),
    10989 => array(
        'm_icon' => '<i class="fad fa-microscope read" aria-hidden="true"></i>',
        'm_name' => 'READ MICROSCOPE',
        'm_desc' => 'View Read Times',
        'm_parents' => array(10876,10985,10957),
    ),
    10988 => array(
        'm_icon' => '<i class="fad fa-telescope read" aria-hidden="true"></i>',
        'm_name' => 'READ TELESCOPE',
        'm_desc' => 'View All Reads',
        'm_parents' => array(10876,10989,10957),
    ),
    10983 => array(
        'm_icon' => '<i class="fad fa-gamepad source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE JOYSTICK',
        'm_desc' => 'Reference Sources',
        'm_parents' => array(10876,10964,10957),
    ),
    10967 => array(
        'm_icon' => '<i class="fad fa-turntable source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TURNTABLE',
        'm_desc' => 'Organize Sources',
        'm_parents' => array(10876,10985,10957),
    ),
    10986 => array(
        'm_icon' => '<i class="fad fa-dice source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE DICE',
        'm_desc' => 'Advance Source Tools',
        'm_parents' => array(10876,10967,10957),
    ),
);

//SOURCE AVATAR BASIC:
$config['en_ids_10956'] = array(12286,12287,12288,12234,12233,10965,12236,12235,10979,12295,12294,12293,12300,12301,12299,12237,12238,10978,12314,12315,12316,12240,12239,10963,12241,12242,12207,12244,12243,10966,12245,12246,10976,12248,12247,10962,12249,12250,10975,12252,12251,10982,12253,12254,10970,12256,12255,10972,12257,12258,10969,12260,12259,10960,12439,12262,10981,12264,12263,10968,12265,12266,10974,12268,12267,12206,12269,12270,10958,12272,12271,12231);
$config['en_all_10956'] = array(
    12286 => array(
        'm_icon' => '<i class="fas fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12287 => array(
        'm_icon' => '<i class="far fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12288 => array(
        'm_icon' => '<i class="fad fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12234 => array(
        'm_icon' => '<i class="fas fa-dog source"></i>',
        'm_name' => 'DOGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12233 => array(
        'm_icon' => '<i class="far fa-dog source"></i>',
        'm_name' => 'DOGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10965 => array(
        'm_icon' => '<i class="fad fa-dog source"></i>',
        'm_name' => 'DOGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12236 => array(
        'm_icon' => '<i class="fas fa-duck source" aria-hidden="true"></i>',
        'm_name' => 'DONALD BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12235 => array(
        'm_icon' => '<i class="far fa-duck source" aria-hidden="true"></i>',
        'm_name' => 'DONALD LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10979 => array(
        'm_icon' => '<i class="fad fa-duck source"></i>',
        'm_name' => 'DONALD MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12295 => array(
        'm_icon' => '<i class="fas fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12294 => array(
        'm_icon' => '<i class="far fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12293 => array(
        'm_icon' => '<i class="fad fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12300 => array(
        'm_icon' => '<i class="fas fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12301 => array(
        'm_icon' => '<i class="far fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12299 => array(
        'm_icon' => '<i class="fad fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12237 => array(
        'm_icon' => '<i class="fas fa-fish source" aria-hidden="true"></i>',
        'm_name' => 'FISHY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12238 => array(
        'm_icon' => '<i class="far fa-fish source" aria-hidden="true"></i>',
        'm_name' => 'FISHY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10978 => array(
        'm_icon' => '<i class="fad fa-fish source"></i>',
        'm_name' => 'FISHY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12314 => array(
        'm_icon' => '<i class="fas fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12315 => array(
        'm_icon' => '<i class="far fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12316 => array(
        'm_icon' => '<i class="fad fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12240 => array(
        'm_icon' => '<i class="fas fa-hippo source" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12239 => array(
        'm_icon' => '<i class="far fa-hippo source" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10963 => array(
        'm_icon' => '<i class="fad fa-hippo source"></i>',
        'm_name' => 'HIPPOY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12241 => array(
        'm_icon' => '<i class="fas fa-badger-honey source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12242 => array(
        'm_icon' => '<i class="far fa-badger-honey source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12207 => array(
        'm_icon' => '<i class="fad fa-badger-honey source"></i>',
        'm_name' => 'HONEY BADGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12244 => array(
        'm_icon' => '<i class="fas fa-deer source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12243 => array(
        'm_icon' => '<i class="far fa-deer source" aria-hidden="true"></i>',
        'm_name' => 'HONEY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10966 => array(
        'm_icon' => '<i class="fad fa-deer source"></i>',
        'm_name' => 'HONEY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12245 => array(
        'm_icon' => '<i class="fas fa-horse source" aria-hidden="true"></i>',
        'm_name' => 'HORSY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12246 => array(
        'm_icon' => '<i class="far fa-horse source" aria-hidden="true"></i>',
        'm_name' => 'HORSY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10976 => array(
        'm_icon' => '<i class="fad fa-horse source"></i>',
        'm_name' => 'HORSY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12248 => array(
        'm_icon' => '<i class="fas fa-monkey source" aria-hidden="true"></i>',
        'm_name' => 'HUMAN BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12247 => array(
        'm_icon' => '<i class="far fa-monkey source" aria-hidden="true"></i>',
        'm_name' => 'HUMAN LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10962 => array(
        'm_icon' => '<i class="fad fa-monkey source"></i>',
        'm_name' => 'HUMAN MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12249 => array(
        'm_icon' => '<i class="fas fa-kiwi-bird source" aria-hidden="true"></i>',
        'm_name' => 'KIWI BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12250 => array(
        'm_icon' => '<i class="far fa-kiwi-bird source" aria-hidden="true"></i>',
        'm_name' => 'KIWI LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10975 => array(
        'm_icon' => '<i class="fad fa-kiwi-bird source"></i>',
        'm_name' => 'KIWI MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12252 => array(
        'm_icon' => '<i class="fas fa-cat source" aria-hidden="true"></i>',
        'm_name' => 'MIMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12251 => array(
        'm_icon' => '<i class="far fa-cat source" aria-hidden="true"></i>',
        'm_name' => 'MIMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10982 => array(
        'm_icon' => '<i class="fad fa-cat source"></i>',
        'm_name' => 'MIMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12253 => array(
        'm_icon' => '<i class="fas fa-cow source" aria-hidden="true"></i>',
        'm_name' => 'MOMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12254 => array(
        'm_icon' => '<i class="far fa-cow source" aria-hidden="true"></i>',
        'm_name' => 'MOMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10970 => array(
        'm_icon' => '<i class="fad fa-cow source"></i>',
        'm_name' => 'MOMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12256 => array(
        'm_icon' => '<i class="fas fa-turtle source" aria-hidden="true"></i>',
        'm_name' => 'NINJA BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12255 => array(
        'm_icon' => '<i class="far fa-turtle source" aria-hidden="true"></i>',
        'm_name' => 'NINJA LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10972 => array(
        'm_icon' => '<i class="fad fa-turtle source"></i>',
        'm_name' => 'NINJA MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12257 => array(
        'm_icon' => '<i class="fas fa-pig source" aria-hidden="true"></i>',
        'm_name' => 'PIGGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12258 => array(
        'm_icon' => '<i class="far fa-pig source" aria-hidden="true"></i>',
        'm_name' => 'PIGGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10969 => array(
        'm_icon' => '<i class="fad fa-pig source"></i>',
        'm_name' => 'PIGGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12260 => array(
        'm_icon' => '<i class="fas fa-rabbit source" aria-hidden="true"></i>',
        'm_name' => 'ROGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12259 => array(
        'm_icon' => '<i class="far fa-rabbit source" aria-hidden="true"></i>',
        'm_name' => 'ROGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10960 => array(
        'm_icon' => '<i class="fad fa-rabbit source"></i>',
        'm_name' => 'ROGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12439 => array(
        'm_icon' => '<i class="fad fa-crow source" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12262 => array(
        'm_icon' => '<i class="far fa-crow source" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10981 => array(
        'm_icon' => '<i class="fad fa-crow source"></i>',
        'm_name' => 'RUSSEL MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12264 => array(
        'm_icon' => '<i class="fas fa-sheep source" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12263 => array(
        'm_icon' => '<i class="far fa-sheep source" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10968 => array(
        'm_icon' => '<i class="fad fa-sheep source"></i>',
        'm_name' => 'SHEEPY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12265 => array(
        'm_icon' => '<i class="fas fa-snake source" aria-hidden="true"></i>',
        'm_name' => 'SNAKY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12266 => array(
        'm_icon' => '<i class="far fa-snake source" aria-hidden="true"></i>',
        'm_name' => 'SNAKY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10974 => array(
        'm_icon' => '<i class="fad fa-snake source"></i>',
        'm_name' => 'SNAKY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12268 => array(
        'm_icon' => '<i class="fas fa-spider source" aria-hidden="true"></i>',
        'm_name' => 'SPIDER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12267 => array(
        'm_icon' => '<i class="far fa-spider source" aria-hidden="true"></i>',
        'm_name' => 'SPIDER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12206 => array(
        'm_icon' => '<i class="fad fa-spider source"></i>',
        'm_name' => 'SPIDER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12269 => array(
        'm_icon' => '<i class="fas fa-squirrel source" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12270 => array(
        'm_icon' => '<i class="far fa-squirrel source" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10958 => array(
        'm_icon' => '<i class="fad fa-squirrel source"></i>',
        'm_name' => 'SQUIRRELY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12272 => array(
        'm_icon' => '<i class="fas fa-whale source" aria-hidden="true"></i>',
        'm_name' => 'WHALE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12271 => array(
        'm_icon' => '<i class="far fa-whale source" aria-hidden="true"></i>',
        'm_name' => 'WHALE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12231 => array(
        'm_icon' => '<i class="fad fa-whale source"></i>',
        'm_name' => 'WHALE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
);

//MENCH:
$config['en_ids_2738'] = array(4536,4535,6205);
$config['en_all_2738'] = array(
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(12499,10876,4527,5008,12155,2738),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE',
        'm_desc' => '',
        'm_parents' => array(12499,12112,10876,4527,12155,2738),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ',
        'm_desc' => '',
        'm_parents' => array(12499,10876,4527,5008,12155,2738),
    ),
);

//READ OPTIONAL CONNECTIONS:
$config['en_ids_10692'] = array(4366,4429,4368,4369,4371);
$config['en_all_10692'] = array(
    4366 => array(
        'm_icon' => '<i class="fas fa-id-badge" aria-hidden="true"></i>',
        'm_name' => 'PROFILE SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fad fa-step-backward" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS NOTE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fad fa-step-forward" aria-hidden="true"></i>',
        'm_name' => 'NEXT NOTE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fad fa-link" aria-hidden="true"></i>',
        'm_name' => 'REFERENCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
);

//PLATFORM MEMORY:
$config['en_ids_4527'] = array(6287,12588,6150,10627,12359,10717,12571,12574,11080,12639,2738,12105,12467,12502,4341,10876,11035,12273,6255,12274,12524,11059,11058,6225,12501,12589,3000,4535,11047,6192,12365,12079,11018,11021,4229,6193,4485,12012,10990,4983,7302,4737,7356,12138,7355,12400,6201,12112,12420,4486,12413,7585,10602,12330,12324,7309,7712,7751,6404,4527,11054,6232,4997,12577,4755,6205,11081,7704,12229,6345,4280,4277,6102,12446,6146,7347,12326,10692,12227,5967,7304,7360,7364,7359,10593,12228,12141,12327,10658,4269,6204,4536,10956,12279,12410,6194,3290,6827,12421,11089,4592,12403,4426,12500,7555,4986,7551,11028,4537,6177,7358,12575,7357,12401,6206,3289,10957,12523,12322,12321,6805,6103,6186,4593);
$config['en_all_4527'] = array(
    6287 => array(
        'm_icon' => '<i class="fad fa-user-shield source" aria-hidden="true"></i>',
        'm_name' => 'ADMIN PANEL',
        'm_desc' => '',
        'm_parents' => array(12500,10985,10876,11035,4527),
    ),
    12588 => array(
        'm_icon' => '',
        'm_name' => 'AVOID PREFIX REMOVAL',
        'm_desc' => '',
        'm_parents' => array(4527,7254),
    ),
    6150 => array(
        'm_icon' => '<i class="far fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARK REMOVED',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    10627 => array(
        'm_icon' => '<i class="far fa-paperclip"></i>',
        'm_name' => 'FILE TYPE ATTACHMENT',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    12359 => array(
        'm_icon' => '<i class="fad fa-file-check"></i>',
        'm_name' => 'FILE UPLOADING ALLOWED',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    10717 => array(
        'm_icon' => '<i class="fas fa-desktop" aria-hidden="true"></i>',
        'm_name' => 'IT',
        'm_desc' => '',
        'm_parents' => array(10710,4527),
    ),
    12571 => array(
        'm_icon' => '<i class="fas fa-expand" aria-hidden="true"></i>',
        'm_name' => 'LAYOUT OPEN BY DEFAULT',
        'm_desc' => '',
        'm_parents' => array(12573,4527),
    ),
    12574 => array(
        'm_icon' => '<i class="fad fa-check-double"></i>',
        'm_name' => 'LAYOUT SHOW EVEN IF ZERO',
        'm_desc' => '',
        'm_parents' => array(4527,12573),
    ),
    11080 => array(
        'm_icon' => '<i class="far fa-file"></i>',
        'm_name' => 'MEDIA FILE EXTENSIONS',
        'm_desc' => '',
        'm_parents' => array(7254,4527),
    ),
    12639 => array(
        'm_icon' => '',
        'm_name' => 'MEMORY CACHE COUNT',
        'm_desc' => '',
        'm_parents' => array(4527,7254),
    ),
    2738 => array(
        'm_icon' => '<img src="/img/mench.png" class="mench-spin no-radius">',
        'm_name' => 'MENCH',
        'm_desc' => '',
        'm_parents' => array(12497,11054,12041,3303,7524,3325,3326,3324,4527,1,7312,2750),
    ),
    12105 => array(
        'm_icon' => '<i class="fas fa-vote-yea"></i>',
        'm_name' => 'MENCH CHANNELS UPCOMING',
        'm_desc' => '',
        'm_parents' => array(4527,4758,6771),
    ),
    12467 => array(
        'm_icon' => '<i class="fas fa-circle" aria-hidden="true"></i>',
        'm_name' => 'MENCH COINS',
        'm_desc' => '',
        'm_parents' => array(4527,4536,11035),
    ),
    12502 => array(
        'm_icon' => '<i class="fad fa-file-code"></i>',
        'm_name' => 'MENCH JAVASCRIPT FUNCTIONS',
        'm_desc' => '',
        'm_parents' => array(4527,7305),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas read" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(12588,11035,4527,7735,6205),
    ),
    10876 => array(
        'm_icon' => '<i class="fas fa-browser" aria-hidden="true"></i>',
        'm_name' => 'MENCH LINKS',
        'm_desc' => '',
        'm_parents' => array(4527,1326,7305),
    ),
    11035 => array(
        'm_icon' => '<i class="fad fa-compass" aria-hidden="true"></i>',
        'm_name' => 'MENCH NAVIGATION MENU',
        'm_desc' => '',
        'm_parents' => array(4527,7305),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'MENCH NOTES',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'MENCH READS',
        'm_desc' => '',
        'm_parents' => array(12467,10964,11089,12410,12365,6771,12228,4527),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'MENCH SOURCES',
        'm_desc' => '',
        'm_parents' => array(12467,12228,4527,4758),
    ),
    12524 => array(
        'm_icon' => '<i class="fad fa-film-alt source" aria-hidden="true"></i>',
        'm_name' => 'MESSAGE VISUAL MEDIA',
        'm_desc' => '',
        'm_parents' => array(4758,4527),
    ),
    11059 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger source"></i>',
        'm_name' => 'MESSENGER MEDIA CODES',
        'm_desc' => '',
        'm_parents' => array(6196,4527,7254),
    ),
    11058 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger source"></i>',
        'm_name' => 'MESSENGER NOTIFICATION CODES',
        'm_desc' => '',
        'm_parents' => array(7254,6196,4527),
    ),
    6225 => array(
        'm_icon' => '<i class="fad fa-user-cog source" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(10876,12500,4536,11035,4527),
    ),
    12501 => array(
        'm_icon' => '<i class="fad fa-list" aria-hidden="true"></i>',
        'm_name' => 'NAVIGATION MENUS',
        'm_desc' => '',
        'm_parents' => array(4527,7254),
    ),
    12589 => array(
        'm_icon' => '<i class="fad fa-edit note" aria-hidden="true"></i>',
        'm_name' => 'NEXT EDITOR',
        'm_desc' => '',
        'm_parents' => array(12365,4527,10985,4535,12590),
    ),
    3000 => array(
        'm_icon' => '<i class="fad fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION SOURCE TYPES',
        'm_desc' => '',
        'm_parents' => array(4536,4527),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE',
        'm_desc' => '',
        'm_parents' => array(12499,12112,10876,4527,12155,2738),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down" aria-hidden="true"></i>',
        'm_name' => 'NOTE ADMIN MENU',
        'm_desc' => '',
        'm_parents' => array(10984,12365,4527,11040),
    ),
    6192 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'NOTE AND',
        'm_desc' => '',
        'm_parents' => array(4527,10602),
    ),
    12365 => array(
        'm_icon' => '<i class="fad fa-square note" aria-hidden="true"></i>',
        'm_name' => 'NOTE BODY',
        'm_desc' => '',
        'm_parents' => array(4527,11021),
    ),
    12079 => array(
        'm_icon' => '<i class="fas fa-caret-down" aria-hidden="true"></i>',
        'm_name' => 'NOTE DROPDOWNS',
        'm_desc' => '',
        'm_parents' => array(6768,4527),
    ),
    11018 => array(
        'm_icon' => '<i class="fad fa-browser note rotate180" aria-hidden="true"></i>',
        'm_name' => 'NOTE FOOTER',
        'm_desc' => '',
        'm_parents' => array(4527,11021),
    ),
    11021 => array(
        'm_icon' => '<i class="fad fa-crop-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4527,4535),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(4535,4527,6410,6283,4593,4486),
    ),
    6193 => array(
        'm_icon' => '<i class="fad fa-code-branch rotate180 " aria-hidden="true"></i>',
        'm_name' => 'NOTE OR',
        'm_desc' => '',
        'm_parents' => array(10602,4527),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS',
        'm_desc' => '',
        'm_parents' => array(12408,4535,4527),
    ),
    12012 => array(
        'm_icon' => '<i class="far fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS STATUS',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    10990 => array(
        'm_icon' => '<i class="fad fa-browser note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PREVIOUS SECTION',
        'm_desc' => '',
        'm_parents' => array(4527),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCES',
        'm_desc' => '',
        'm_parents' => array(12450,10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7302 => array(
        'm_icon' => '<i class="far fa-chart-bar note" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATS',
        'm_desc' => '',
        'm_parents' => array(4527,4535),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'NOTE STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    12138 => array(
        'm_icon' => '<i class="fad fa-search note" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATUS FEATURED',
        'm_desc' => '',
        'm_parents' => array(4527,10891),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    12400 => array(
        'm_icon' => '<i class="fad fa-sync note"></i>',
        'm_name' => 'NOTE SYNC STATUS',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    6201 => array(
        'm_icon' => '<i class="far fa-table note"></i>',
        'm_name' => 'NOTE TABLE',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7735,4535),
    ),
    12112 => array(
        'm_icon' => '<i class="fas fa-text" aria-hidden="true"></i>',
        'm_name' => 'NOTE TEXT INPUTS',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    12420 => array(
        'm_icon' => '<i class="far fa-user-circle" aria-hidden="true"></i>',
        'm_name' => 'NOTE TEXT INPUT SHOW ICON',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link note" aria-hidden="true"></i>',
        'm_name' => 'NOTE TREE LINKS',
        'm_desc' => '',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    12413 => array(
        'm_icon' => '<i class="fad fa-circle note"></i>',
        'm_name' => 'NOTE TREE NODES',
        'm_desc' => '',
        'm_parents' => array(4527,11025),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random note" aria-hidden="true"></i>',
        'm_name' => 'NOTE TYPE',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece note" aria-hidden="true"></i>',
        'm_name' => 'NOTE TYPE GROUPS',
        'm_desc' => '',
        'm_parents' => array(10893,6204,7302,4527),
    ),
    12330 => array(
        'm_icon' => '<i class="fas fa-bolt"></i>',
        'm_name' => 'NOTE TYPE INSTANTLY DONE',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    12324 => array(
        'm_icon' => '<i class="fad fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'NOTE TYPE MANUAL INPUT',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    7309 => array(
        'm_icon' => '<i class="far fa-cubes"></i>',
        'm_name' => 'NOTE TYPE REQUIREMENT',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    7712 => array(
        'm_icon' => '<i class="far fa-question-circle" aria-hidden="true"></i>',
        'm_name' => 'NOTE TYPE SELECT',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    7751 => array(
        'm_icon' => '<i class="far fa-upload" aria-hidden="true"></i>',
        'm_name' => 'NOTE TYPE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    6404 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'PLATFORM CONFIG VARIABLES',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7254,6403),
    ),
    4527 => array(
        'm_icon' => '<i class="fas fa-memory" aria-hidden="true"></i>',
        'm_name' => 'PLATFORM MEMORY',
        'm_desc' => '',
        'm_parents' => array(4527,7258,7254),
    ),
    11054 => array(
        'm_icon' => '<i class="fal fa-memory" aria-hidden="true"></i>',
        'm_name' => 'PLATFORM MEMORY JAVASCRIPT',
        'm_desc' => '',
        'm_parents' => array(4527,7258,7254),
    ),
    6232 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'PLATFORM VARIABLES',
        'm_desc' => '',
        'm_parents' => array(4755,4527,6212),
    ),
    4997 => array(
        'm_icon' => '<i class="fad fa-edit source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR',
        'm_desc' => '',
        'm_parents' => array(12590,11029,12574,10967,11089,4758,4506,4527),
    ),
    12577 => array(
        'm_icon' => '<i class="fad fa-text" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR UPPERCASE',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash" aria-hidden="true"></i>',
        'm_name' => 'PRIVATE READ',
        'm_desc' => '',
        'm_parents' => array(4755,6771,4426,4527),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ',
        'm_desc' => '',
        'm_parents' => array(12499,10876,4527,5008,12155,2738),
    ),
    11081 => array(
        'm_icon' => '<i class="far fa-bezier-curve read"></i>',
        'm_name' => 'READ ALL CONNECTIONS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    7704 => array(
        'm_icon' => '<i class="far fa-hand-pointer read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWERED',
        'm_desc' => '',
        'm_parents' => array(12228,4527),
    ),
    12229 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ COMPLETION',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    6345 => array(
        'm_icon' => '<i class="fas fa-comment-check" aria-hidden="true"></i>',
        'm_name' => 'READER READABLE',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    4280 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'READER RECEIVED MESSAGES WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    4277 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'READER SENT MESSAGES WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    6102 => array(
        'm_icon' => '<i class="far fa-paperclip"></i>',
        'm_name' => 'READER SENT/RECEIVED ATTACHMENT',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    12446 => array(
        'm_icon' => '<i class="fad fa-question-circle read"></i>',
        'm_name' => 'READ ICONS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ INCOMPLETES',
        'm_desc' => '',
        'm_parents' => array(12446,10989,11089,12365,12228,4527),
    ),
    7347 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10989,11089,12365,6205,12228,4527),
    ),
    12326 => array(
        'm_icon' => '<i class="fad fa-bars read" aria-hidden="true"></i>',
        'm_name' => 'READ NOTE LINKS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    10692 => array(
        'm_icon' => '<i class="fas fa-bezier-curve read"></i>',
        'm_name' => 'READ OPTIONAL CONNECTIONS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    12227 => array(
        'm_icon' => '<i class="fas fa-walking read" aria-hidden="true"></i>',
        'm_name' => 'READ PROGRESS',
        'm_desc' => '',
        'm_parents' => array(12228,4527),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ READ CC',
        'm_desc' => '',
        'm_parents' => array(6205,4506,4527,7569,4755,4593),
    ),
    7304 => array(
        'm_icon' => '<i class="far fa-chart-bar read"></i>',
        'm_name' => 'READ STATS',
        'm_desc' => '',
        'm_parents' => array(10888,4527,6205),
    ),
    7360 => array(
        'm_icon' => '<i class="far fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    7364 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS INCOMPLETE',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    7359 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    10593 => array(
        'm_icon' => '<i class="fas fa-file-alt" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE ADD CONTENT',
        'm_desc' => '',
        'm_parents' => array(12144,4527),
    ),
    12228 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE GROUPS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    12141 => array(
        'm_icon' => '<i class="fad fa-coin" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE ISSUE COINS',
        'm_desc' => '',
        'm_parents' => array(12144,4527),
    ),
    12327 => array(
        'm_icon' => '<i class="fas fa-lock-open read"></i>',
        'm_name' => 'READ UNLOCKS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    10658 => array(
        'm_icon' => '<i class="fas fa-sync read"></i>',
        'm_name' => 'READ UPDATES',
        'm_desc' => '',
        'm_parents' => array(4527,6205),
    ),
    4269 => array(
        'm_icon' => '<i class="fad fa-sign-in-alt" aria-hidden="true"></i>',
        'm_name' => 'SIGN IN/UP',
        'm_desc' => '',
        'm_parents' => array(4527,11035),
    ),
    6204 => array(
        'm_icon' => '<i class="fas fa-check" aria-hidden="true"></i>',
        'm_name' => 'SINGLE SELECTABLE',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(12499,10876,4527,5008,12155,2738),
    ),
    10956 => array(
        'm_icon' => '<i class="fad fa-paw-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE AVATAR BASIC',
        'm_desc' => '',
        'm_parents' => array(12289,4527),
    ),
    12279 => array(
        'm_icon' => '<i class="fad fa-paw-claws source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE AVATAR SUPER',
        'm_desc' => '',
        'm_parents' => array(12289,4527),
    ),
    12410 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE COINS',
        'm_desc' => '',
        'm_parents' => array(12408,4527),
    ),
    6194 => array(
        'm_icon' => '<i class="fad fa-database . source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE CONNECTIONS',
        'm_desc' => '',
        'm_parents' => array(12412,4758,4527,6212),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE GENDER',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    6827 => array(
        'm_icon' => '<i class="far fa-users-crown"></i>',
        'm_name' => 'SOURCE GROUPS',
        'm_desc' => '',
        'm_parents' => array(3303,3314,4527),
    ),
    12421 => array(
        'm_icon' => '<i class="fas fa-icons"></i>',
        'm_name' => 'SOURCE ICON DROPDOWN',
        'm_desc' => '',
        'm_parents' => array(3303,4428,4527,4758),
    ),
    11089 => array(
        'm_icon' => '<i class="fad fa-crop-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4536,4527),
    ),
    4592 => array(
        'm_icon' => '<i class="fas fa-link source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINKS',
        'm_desc' => '',
        'm_parents' => array(11026,5982,5981,4527),
    ),
    12403 => array(
        'm_icon' => '<i class="far fa-object-ungroup" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK TYPE CUSTOM UI',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LOCK',
        'm_desc' => '',
        'm_parents' => array(4758,3303,4426,4527),
    ),
    12500 => array(
        'm_icon' => '<i class="fad fa-user" aria-hidden="true"></i>',
        'm_name' => 'SOURCE MENU',
        'm_desc' => '',
        'm_parents' => array(12497,12501,4527),
    ),
    7555 => array(
        'm_icon' => '<i class="fas fa-paper-plane" aria-hidden="true"></i>',
        'm_name' => 'SOURCE READING CHANNELS',
        'm_desc' => '',
        'm_parents' => array(7305,4527),
    ),
    4986 => array(
        'm_icon' => '<i class="fal fa-at" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REFERENCE ALLOWED',
        'm_desc' => '',
        'm_parents' => array(10889,4758,4527),
    ),
    7551 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REFERENCE REQUIRED',
        'm_desc' => '',
        'm_parents' => array(10889,4527,4758),
    ),
    11028 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90 source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES LINKS DIRECTION',
        'm_desc' => '',
        'm_parents' => array(4527,11026),
    ),
    4537 => array(
        'm_icon' => '<i class="fal fa-spider-web" aria-hidden="true"></i>',
        'm_name' => 'SOURCES LINKS URLS',
        'm_desc' => '',
        'm_parents' => array(4758,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    7358 => array(
        'm_icon' => '<i class="far fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(12572,4527),
    ),
    12575 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'SOURCE STATUS FEATURED',
        'm_desc' => '',
        'm_parents' => array(4527,12572),
    ),
    7357 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(12572,11054,4527),
    ),
    12401 => array(
        'm_icon' => '<i class="fad fa-sync source"></i>',
        'm_name' => 'SOURCE SYNC STATUS',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    6206 => array(
        'm_icon' => '<i class="far fa-table source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TABLE',
        'm_desc' => '',
        'm_parents' => array(4527,7735,4536),
    ),
    3289 => array(
        'm_icon' => '<i class="fas fa-map-marked source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TIMEZONE',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(12502,12500,6225,11035,5007,4527),
    ),
    12523 => array(
        'm_icon' => '<i class="fad fa-portrait source" aria-hidden="true"></i>',
        'm_name' => 'SYNC ICONS',
        'm_desc' => '',
        'm_parents' => array(7274,4527,4758),
    ),
    12322 => array(
        'm_icon' => '<i class="fas fa-comment read" aria-hidden="true"></i>',
        'm_name' => 'TEMPLATE NOTE MESSAGES',
        'm_desc' => '',
        'm_parents' => array(4527,12320),
    ),
    12321 => array(
        'm_icon' => '<i class="fad fa-object-group read"></i>',
        'm_name' => 'TEMPLATE NOTE READ',
        'm_desc' => '',
        'm_parents' => array(4527,12320),
    ),
    6805 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'THING INTERACTION CONTENT REQUIRES TEXT',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'TRANSACTION METADATA',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//READ UPDATES:
$config['en_ids_10658'] = array(10686,10663,10664,10676,10678,10679,10677,10681,10675,10662,10690,10683,12328,7578,10657,10656,10659,10673,10689);
$config['en_all_10658'] = array(
    10686 => array(
        'm_icon' => '<i class="fad fa-times note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10663 => array(
        'm_icon' => '<i class="fad fa-coin note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK UPDATE MARKS',
        'm_desc' => '',
        'm_parents' => array(4535,4228,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4229,10658),
    ),
    10676 => array(
        'm_icon' => '<i class="fad fa-bars note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS SORTED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10678 => array(
        'm_icon' => '<i class="fad fa-trash-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10658,4593),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10593,10658),
    ),
    10677 => array(
        'm_icon' => '<i class="fad fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-bars note" aria-hidden="true"></i>',
        'm_name' => 'NOTE SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4535,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fad fa-bars note" aria-hidden="true"></i>',
        'm_name' => 'NOTE SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10662 => array(
        'm_icon' => '<i class="fad fa-hashtag note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE LINK',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10690 => array(
        'm_icon' => '<i class="read fad fa-upload"></i>',
        'm_name' => 'READ MEDIA UPLOADED',
        'm_desc' => '',
        'm_parents' => array(6205,6153,4593,10658),
    ),
    10683 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ READ EMAIL',
        'm_desc' => '',
        'm_parents' => array(6205,6153,10658,4593,7654),
    ),
    12328 => array(
        'm_icon' => '<i class="fad fa-sync read"></i>',
        'm_name' => 'READ UPDATE COMPLETION',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,10658,6153),
    ),
    7578 => array(
        'm_icon' => '<i class="read fad fa-key"></i>',
        'm_name' => 'READ UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(6205,6222,10658,6153,4755,4593),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10658,10645),
    ),
    10656 => array(
        'm_icon' => '<i class="fad fa-sliders-h source"></i>',
        'm_name' => 'SOURCE LINK STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10645),
    ),
    10659 => array(
        'm_icon' => '<i class="fad fa-plug source"></i>',
        'm_name' => 'SOURCE LINK TYPE UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10658,4593,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="fad fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10645,4593,10658),
    ),
    10689 => array(
        'm_icon' => '<i class="fad fa-share-alt rotate90 source"></i>',
        'm_name' => 'SOURCE MERGED IN SOURCE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10645),
    ),
);

//FILE TYPE ATTACHMENT:
$config['en_ids_10627'] = array(4554,4556,4555,4549,4551,4550,4548,4553,4259,4261,4260,4258);
$config['en_all_10627'] = array(
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ SENT FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'SOURCE LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'SOURCE LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
);

//READ TYPE ADD CONTENT:
$config['en_ids_10593'] = array(12419,4250,4601,4231,10679,4983,10644,4554,4556,4555,6563,4570,7702,4549,4551,4550,4548,4552,4553,4251,4259,10657,4261,4260,4255,4258,10646);
$config['en_all_10593'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,4535,12149,12141,10593,4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search note" aria-hidden="true"></i>',
        'm_name' => 'NOTE KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment note" aria-hidden="true"></i>',
        'm_name' => 'NOTE MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10593,10658),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCES',
        'm_desc' => '',
        'm_parents' => array(12450,10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    10644 => array(
        'm_icon' => '<i class="fad fa-bullseye-arrow note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE TITLE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593),
    ),
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4593,4755,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="read fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED',
        'm_desc' => '',
        'm_parents' => array(6205,10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED NOTE',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4593,4755,7569),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ SENT FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4552 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE CREATED',
        'm_desc' => '',
        'm_parents' => array(12401,12274,12149,12141,10645,10593,4593),
    ),
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'SOURCE LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10658,10645),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'SOURCE LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,4592),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'SOURCE LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10646 => array(
        'm_icon' => '<i class="fad fa-fingerprint source"></i>',
        'm_name' => 'SOURCE NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10645),
    ),
);

//READ LIST:
$config['en_ids_7347'] = array(7495,4235);
$config['en_all_7347'] = array(
    7495 => array(
        'm_icon' => '<i class="far fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(6205,12227,7347,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'STARTED',
        'm_desc' => '',
        'm_parents' => array(6205,12227,7347,5967,4755,4593),
    ),
);

//NOTE AND:
$config['en_ids_6192'] = array(6914,7637,6677,6683);
$config['en_all_6192'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes " aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ALL',
        'm_desc' => '',
        'm_parents' => array(10985,12330,7486,7485,6140,6192,7585,7309,6997),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD & NEXT',
        'm_desc' => '',
        'm_parents' => array(12324,12117,7751,7585,6192),
    ),
    6677 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'READ & NEXT',
        'm_desc' => '',
        'm_parents' => array(12330,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard " aria-hidden="true"></i>',
        'm_name' => 'REPLY & NEXT',
        'm_desc' => '',
        'm_parents' => array(12324,6144,7585,6192),
    ),
);

//NOTE TYPE GROUPS:
$config['en_ids_10602'] = array(6192,6193);
$config['en_all_10602'] = array(
    6192 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'AND',
        'm_desc' => 'Reader completes note by reading off of the next notes',
        'm_parents' => array(4527,10602),
    ),
    6193 => array(
        'm_icon' => '<i class="fad fa-code-branch rotate180 " aria-hidden="true"></i>',
        'm_name' => 'OR',
        'm_desc' => 'Reader completes note by reading some of the next notes',
        'm_parents' => array(10602,4527),
    ),
);

//NOTE SOURCES:
$config['en_ids_4983'] = array(3084,2997,4446,3005,4763,3147,2999,5948,3192,2998,4430);
$config['en_all_4983'] = array(
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut source" aria-hidden="true"></i>',
        'm_name' => 'INDUSTRY EXPERTS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,6827),
    ),
    2997 => array(
        'm_icon' => '<i class="fad fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION ARTICLES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fad fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fad fa-book source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION BOOKS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION CHANNELS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION COURSES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION PODCASTS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,10809,4983,7614,6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="fad fa-file-invoice source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION TEMPLATES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION TOOLS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'NON-FICTION VIDEOS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-horse-head source" aria-hidden="true"></i>',
        'm_name' => 'PLAYERS',
        'm_desc' => '',
        'm_parents' => array(12639,12437,11035,10573,4983,6827,4426),
    ),
);

//NOTE TYPE UPLOAD:
$config['en_ids_7751'] = array(7637);
$config['en_all_7751'] = array(
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPLOAD & NEXT',
        'm_desc' => '',
        'm_parents' => array(12324,12117,7751,7585,6192),
    ),
);

//TRANSACTION METADATA:
$config['en_ids_6103'] = array(6402,6203,4358);
$config['en_all_6103'] = array(
    6402 => array(
        'm_icon' => '<i class="fas fa-bolt" aria-hidden="true"></i>',
        'm_name' => 'CONDITION SCORE RANGE',
        'm_desc' => '',
        'm_parents' => array(10985,10664,6103,6410),
    ),
    6203 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'FACEBOOK ATTACHMENT ID',
        'm_desc' => 'For media files such as videos, audios, images and other files, we cache them with the Facebook Server so we can instantly deliver them to students. This variables in the link metadata is where we store the attachment ID. See the children to better understand which links types support this caching feature.',
        'm_parents' => array(6232,6215,2793,6103),
    ),
    4358 => array(
        'm_icon' => '<i class="fad fa-comment-alt-check" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => '',
        'm_parents' => array(12420,10985,12112,10663,6103,6410,6232),
    ),
);

//MENCH LEDGER:
$config['en_ids_4341'] = array(4364,7694,4367,4372,6103,4369,4429,4368,4366,4370,4371,6186,4362,4593);
$config['en_all_4341'] = array(
    4364 => array(
        'm_icon' => '<i class="fad fa-user-edit" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION CREATOR',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    7694 => array(
        'm_icon' => '<i class="fad fa-project-diagram"></i>',
        'm_name' => 'TRANSACTION EXTERNAL ID',
        'm_desc' => '',
        'm_parents' => array(6215,6232,4341),
    ),
    4367 => array(
        'm_icon' => '<i class="fad fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,4341),
    ),
    4372 => array(
        'm_icon' => '<i class="fad fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION MESSAGE',
        'm_desc' => '',
        'm_parents' => array(7578,10679,10657,5001,6232,4341),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'TRANSACTION METADATA',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fad fa-step-forward" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION NEXT NOTE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PORTFOLIO SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fad fa-step-backward" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PREVIOUS NOTE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-id-badge" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PROFILE SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fad fa-bars" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION RANK',
        'm_desc' => '',
        'm_parents' => array(10676,10675,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fad fa-link" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION REFERENCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="fad fa-clock" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TIME',
        'm_desc' => '',
        'm_parents' => array(6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//SOURCE TABLE:
$config['en_ids_6206'] = array(6198,6160,6172,6197,6177);
$config['en_all_6206'] = array(
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'ICON',
        'm_desc' => '',
        'm_parents' => array(12605,10653,5943,10625,6232,6206),
    ),
    6160 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda source"></i>',
        'm_name' => 'METADATA',
        'm_desc' => '',
        'm_parents' => array(6232,3323,6206,6195),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'NICKNAME',
        'm_desc' => '',
        'm_parents' => array(4269,12412,12232,6225,11072,10646,5000,4998,4999,6232,6206),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
);

//NOTE TABLE:
$config['en_ids_6201'] = array(6202,6159,4356,4737,4736,7585);
$config['en_all_6201'] = array(
    6202 => array(
        'm_icon' => '<i class="fas fa-plus-circle "></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6201),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda " aria-hidden="true"></i>',
        'm_name' => 'METADATA',
        'm_desc' => 'Intent metadata contains variables that have been automatically calculated and automatically updates using a cron job. Intent Metadata are the backbone of key functions and user interfaces like the intent landing page or Action Plan completion workflows.',
        'm_parents' => array(11049,6232,6201,6195),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch " aria-hidden="true"></i>',
        'm_name' => 'READ TIME',
        'm_desc' => '',
        'm_parents' => array(12112,12420,10888,10650,6232,6201),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 " aria-hidden="true"></i>',
        'm_name' => 'TITLE',
        'm_desc' => '',
        'm_parents' => array(10990,12112,11071,10644,6232,6201),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random note" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
);

//SINGLE SELECTABLE:
$config['en_ids_6204'] = array(4737,7585,10602,3290,6177,3289,6186,4593);
$config['en_all_6204'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random note" aria-hidden="true"></i>',
        'm_name' => 'NOTE TYPE',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece note" aria-hidden="true"></i>',
        'm_name' => 'NOTE TYPE GROUPS',
        'm_desc' => '',
        'm_parents' => array(10893,6204,7302,4527),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE GENDER',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    3289 => array(
        'm_icon' => '<i class="fas fa-map-marked source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TIMEZONE',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//NOTE TYPE SELECT:
$config['en_ids_7712'] = array(6684,7231);
$config['en_all_7712'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'ONE',
        'm_desc' => '',
        'm_parents' => array(12336,12129,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square" aria-hidden="true"></i>',
        'm_name' => 'SOME',
        'm_desc' => '',
        'm_parents' => array(12334,12129,7712,7489,7585,6193),
    ),
);

//READ ANSWERED:
$config['en_ids_7704'] = array(12336,12334,6157,7489);
$config['en_all_7704'] = array(
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
);

//NOTE LINK CONDITIONAL:
$config['en_ids_4229'] = array(10664,6140,6997);
$config['en_all_4229'] = array(
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4229,10658),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => 'A step that has become available because of the score generated from student answers',
        'm_parents' => array(6205,12326,12227,6410,4229,4755,4593),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//NOTE OR:
$config['en_ids_6193'] = array(6684,7231,6907);
$config['en_all_6193'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'SELECT ONE',
        'm_desc' => '',
        'm_parents' => array(12336,12129,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square" aria-hidden="true"></i>',
        'm_name' => 'SELECT SOME',
        'm_desc' => '',
        'm_parents' => array(12334,12129,7712,7489,7585,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube " aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ANY',
        'm_desc' => '',
        'm_parents' => array(10985,12330,7486,7485,6140,7585,7309,6997,6193),
    ),
);

//NOTE TYPE:
$config['en_ids_7585'] = array(6677,6683,7637,6914,6684,7231,6907);
$config['en_all_7585'] = array(
    6677 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'READ & NEXT',
        'm_desc' => 'Read messages & complete all child notes',
        'm_parents' => array(12330,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard " aria-hidden="true"></i>',
        'm_name' => 'REPLY & NEXT',
        'm_desc' => 'Give a text response & complete all child notes',
        'm_parents' => array(12324,6144,7585,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD & NEXT',
        'm_desc' => 'Upload a file & complete all child notes',
        'm_parents' => array(12324,12117,7751,7585,6192),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes " aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ALL',
        'm_desc' => 'Complete by (a) choosing intent as their answer or by (b) completing all child intents',
        'm_parents' => array(10985,12330,7486,7485,6140,6192,7585,7309,6997),
    ),
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'SELECT ONE',
        'm_desc' => 'Select 1 note from child notes',
        'm_parents' => array(12336,12129,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square" aria-hidden="true"></i>',
        'm_name' => 'SELECT SOME',
        'm_desc' => 'Select 1 or more notes from child notes',
        'm_parents' => array(12334,12129,7712,7489,7585,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube " aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ANY',
        'm_desc' => 'Complete by (a) choosing intent as their answer or by (b) completing any child intent',
        'm_parents' => array(10985,12330,7486,7485,6140,7585,7309,6997,6193),
    ),
);

//READ READ CC:
$config['en_ids_5967'] = array(12419,4250,12453,12450,4235,4246,7504);
$config['en_all_5967'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE COMMENTS',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE CREATED',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(5967,12400,4535,12149,12141,10593,4593),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone note"></i>',
        'm_name' => 'NOTE FEATURE REQUEST',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(12137,4535,4755,4593,5967),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-pen-square source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCE REQUEST',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(4593,4755,4535,5967),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ STARTED',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(6205,12227,7347,5967,4755,4593),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE BUG REPORTS',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REVIEW TRIGGER',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(4535,5967,4755,4593),
    ),
);

//SOURCE READING CHANNELS:
$config['en_ids_7555'] = array(12103);
$config['en_all_7555'] = array(
    12103 => array(
        'm_icon' => '<i class="fab fa-chrome" aria-hidden="true"></i>',
        'm_name' => 'SOURCE WEBSITE',
        'm_desc' => 'Read using modern web browsers & receive notifications using email.',
        'm_parents' => array(7555),
    ),
);

//SOURCE REFERENCE REQUIRED:
$config['en_ids_7551'] = array(10573,4983,7545);
$config['en_all_7551'] = array(
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark note" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(12450,10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
);

//NOTE TYPE REQUIREMENT:
$config['en_ids_7309'] = array(6914,6907);
$config['en_all_7309'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes " aria-hidden="true"></i>',
        'm_name' => 'ALL',
        'm_desc' => '',
        'm_parents' => array(10985,12330,7486,7485,6140,6192,7585,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube " aria-hidden="true"></i>',
        'm_name' => 'ANY',
        'm_desc' => '',
        'm_parents' => array(10985,12330,7486,7485,6140,7585,7309,6997,6193),
    ),
);

//ADMIN PANEL:
$config['en_ids_6287'] = array(7257,7258,7274);
$config['en_all_6287'] = array(
    7257 => array(
        'm_icon' => '<i class="fab fa-app-store-ios"></i>',
        'm_name' => 'MENCH MODERATION APPS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7258 => array(
        'm_icon' => '<i class="far fa-bookmark"></i>',
        'm_name' => 'MENCH PLATFORM BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7274 => array(
        'm_icon' => '<i class="far fa-magic" aria-hidden="true"></i>',
        'm_name' => 'PLATFORM CRON JOBS',
        'm_desc' => '',
        'm_parents' => array(6403,6287),
    ),
);

//READ STATUS INCOMPLETE:
$config['en_ids_7364'] = array(6175);
$config['en_all_7364'] = array(
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
);

//READ STATUS ACTIVE:
$config['en_ids_7360'] = array(6175,12399,6176);
$config['en_all_7360'] = array(
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    12399 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
);

//READ STATUS PUBLIC:
$config['en_ids_7359'] = array(12399,6176);
$config['en_all_7359'] = array(
    12399 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
);

//SOURCE STATUS ACTIVE:
$config['en_ids_7358'] = array(6180,12563,6181);
$config['en_all_7358'] = array(
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10654,7358,6177),
    ),
    12563 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
);

//SOURCE STATUS PUBLIC:
$config['en_ids_7357'] = array(12563,6181);
$config['en_all_7357'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
);

//NOTE STATUS ACTIVE:
$config['en_ids_7356'] = array(6183,12137,6184);
$config['en_all_7356'] = array(
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin " aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10648,7356,4737),
    ),
    12137 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
);

//NOTE STATUS PUBLIC:
$config['en_ids_7355'] = array(12137,6184);
$config['en_all_7355'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
);

//NOTE STATS:
$config['en_ids_7302'] = array(4737,10602);
$config['en_all_7302'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece note" aria-hidden="true"></i>',
        'm_name' => 'TYPE GROUPS',
        'm_desc' => '',
        'm_parents' => array(10893,6204,7302,4527),
    ),
);

//READ STATS:
$config['en_ids_7304'] = array(6186);
$config['en_all_7304'] = array(
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
);

//TRANSACTION STATUS:
$config['en_ids_6186'] = array(12399,6176,6175,6173);
$config['en_all_6186'] = array(
    12399 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="fad fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12012,10686,10678,10673,6186),
    ),
);

//SOURCE CONNECTIONS:
$config['en_ids_6194'] = array(4737,7585,6177,4364,6186,4593);
$config['en_all_6194'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATUS',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_note WHERE in_status_source_id=',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random note" aria-hidden="true"></i>',
        'm_name' => 'NOTE TYPE',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_note WHERE in_status_source_id IN (6183,6184) AND in_type_source_id=',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => 'SELECT count(en_id) as totals FROM table_source WHERE en_status_source_id=',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    4364 => array(
        'm_icon' => '<i class="fad fa-user-edit" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION CREATOR',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_read WHERE ln_status_source_id IN (6175,6176) AND ln_creator_source_id=',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_read WHERE ln_status_source_id=',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_read WHERE ln_status_source_id IN (6175,6176) AND ln_type_source_id=',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//SOURCE GROUPS:
$config['en_ids_6827'] = array(12428,3084,4430);
$config['en_all_6827'] = array(
    12428 => array(
        'm_icon' => '<i class="far fa-lock"></i>',
        'm_name' => 'INACTIVE SOURCES',
        'm_desc' => '',
        'm_parents' => array(6827),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut source" aria-hidden="true"></i>',
        'm_name' => 'INDUSTRY EXPERTS',
        'm_desc' => 'Experienced in their respective industry with a track record of advancing their field of knowldge',
        'm_parents' => array(12523,4983,6827),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-horse-head source" aria-hidden="true"></i>',
        'm_name' => 'PLAYERS',
        'm_desc' => 'Users who are pursuing their intentions using Mench, mainly to get hired at their dream job',
        'm_parents' => array(12639,12437,11035,10573,4983,6827,4426),
    ),
);

//THING INTERACTION CONTENT REQUIRES TEXT:
$config['en_ids_6805'] = array(3005,4763,3147,2999,3192);
$config['en_all_6805'] = array(
    3005 => array(
        'm_icon' => '<i class="fad fa-book source" aria-hidden="true"></i>',
        'm_name' => 'BOOKS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone source" aria-hidden="true"></i>',
        'm_name' => 'CHANNELS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'COURSES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone source" aria-hidden="true"></i>',
        'm_name' => 'PODCASTS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,10809,4983,7614,6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'TOOLS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
);

//READER READABLE:
$config['en_ids_6345'] = array(4231);
$config['en_all_6345'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment note" aria-hidden="true"></i>',
        'm_name' => 'NOTE MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
);

//MENCH READS:
$config['en_ids_6255'] = array(6157,7489,12117,4559,6144,7485,7486,6997);
$config['en_all_6255'] = array(
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//BOOKMARK REMOVED:
$config['en_ids_6150'] = array(7757,6155);
$config['en_all_6150'] = array(
    7757 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'AUTO',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'MANUAL',
        'm_desc' => '',
        'm_parents' => array(6205,10888,10639,4506,6150,4593,4755),
    ),
);

//SOURCE REFERENCE ALLOWED:
$config['en_ids_4986'] = array(12419,4601,4231);
$config['en_all_4986'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt note" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search note" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment note" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
);

//MY ACCOUNT:
$config['en_ids_6225'] = array(10957,12289,6197,3288,3286);
$config['en_all_6225'] = array(
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(12502,12500,6225,11035,5007,4527),
    ),
    12289 => array(
        'm_icon' => '<i class="fad fa-paw source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE AVATAR',
        'm_desc' => '',
        'm_parents' => array(4536,6225),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE NICKNAME',
        'm_desc' => '',
        'm_parents' => array(4269,12412,12232,6225,11072,10646,5000,4998,4999,6232,6206),
    ),
    3288 => array(
        'm_icon' => '<i class="fad fa-envelope-open source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE EMAIL',
        'm_desc' => '',
        'm_parents' => array(4269,12221,12103,6225,4426,4755),
    ),
    3286 => array(
        'm_icon' => '<i class="fad fa-key source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4269,4426,7578,6225,4755),
    ),
);

//NOTE STATUS:
$config['en_ids_4737'] = array(12137,6184,6183,6182);
$config['en_all_4737'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin " aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10648,7356,4737),
    ),
    6182 => array(
        'm_icon' => '<i class="fad fa-trash-alt " aria-hidden="true"></i>',
        'm_name' => 'ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(10671,4737),
    ),
);

//SOURCE STATUS:
$config['en_ids_6177'] = array(12563,6181,6180,6178);
$config['en_all_6177'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10654,7358,6177),
    ),
    6178 => array(
        'm_icon' => '<i class="fad fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(10672,6177),
    ),
);

//READ INCOMPLETES:
$config['en_ids_6146'] = array(6143,7492);
$config['en_all_6146'] = array(
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'SKIPPED',
        'm_desc' => 'Completed when students skip an intention and all its child intentions from their Action Plan',
        'm_parents' => array(6205,12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'TERMINATE',
        'm_desc' => 'Logged when users arrive at a locked intent that has no public OR parents or no children, which means there is no way to unlock it.',
        'm_parents' => array(6205,12229,12227,4755,4593,6146),
    ),
);

//READER SENT MESSAGES WITH MESSENGER:
$config['en_ids_4277'] = array(7654,6554,7653);
$config['en_all_4277'] = array(
    7654 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'AUTOMATED MESSAGES',
        'm_desc' => '',
        'm_parents' => array(4277),
    ),
    6554 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'COMMAND MESSAGES',
        'm_desc' => '',
        'm_parents' => array(4277),
    ),
    7653 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'MANUAL MESSAGES',
        'm_desc' => '',
        'm_parents' => array(4277),
    ),
);

//READER SENT/RECEIVED ATTACHMENT:
$config['en_ids_6102'] = array(4554,4556,4555,4549,4551,4550,4548,4553);
$config['en_all_6102'] = array(
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'SENT FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
);

//READER RECEIVED MESSAGES WITH MESSENGER:
$config['en_ids_4280'] = array(4554,4556,4555,6563,4552,4553);
$config['en_all_4280'] = array(
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'TEXT',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
);

//PORTFOLIO EDITOR:
$config['en_ids_4997'] = array(5000,4998,4999,5001,5003,5865,5943,12318,10625,5982,5981,11956);
$config['en_all_4997'] = array(
    5000 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME REPLACE',
        'm_desc' => 'Search for occurrence of string in child entity names and if found, updates it with a replacement string',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME PREFIX',
        'm_desc' => 'Adds string to the beginning of all child entities. Make sure to include a space for it to look good',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME POSTFIX',
        'm_desc' => 'Adds string to the end of all child entities',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="source fad fa-sticky-note"></i>',
        'm_name' => 'CONTENT REPLACE',
        'm_desc' => 'Search for occurance of string in child entity link contents and if found, updates it with a replacement string',
        'm_parents' => array(4535,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="source fad fa-sliders-h"></i>',
        'm_name' => 'STATUS REPLACE',
        'm_desc' => 'Updates all child entity statuses that match the initial entity status condition',
        'm_parents' => array(4535,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="source fad fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'LINK STATUS REPLACE',
        'm_desc' => 'Updates all child entity link statuses that match the initial link status condition',
        'm_parents' => array(4535,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'ICON UPDATE FOR ALL',
        'm_desc' => 'Updates all child entity icons with string which needs to be a valid icon',
        'm_parents' => array(4535,4593,4997),
    ),
    12318 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'ICON UPDATE IF MISSING',
        'm_desc' => 'Updates all icons that are not set to the new value.',
        'm_parents' => array(4535,4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="source fad fa-user-circle"></i>',
        'm_name' => 'ICON REPLACE',
        'm_desc' => 'Search for occurrence of string in child entity icons and if found, updates it with a replacement string',
        'm_parents' => array(4535,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="source fad fa-layer-minus"></i>',
        'm_name' => 'PROFILE REMOVE',
        'm_desc' => 'If already added as the parent, this will remove searched entity as the parent of all child entities',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="source fad fa-layer-plus"></i>',
        'm_name' => 'PROFILE ADD',
        'm_desc' => 'If not already done so, will add searched entity as the parent of all child entities',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="source fad fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PROFILE IF ADD',
        'm_desc' => 'Adds a parent entity only IF the entity has another parent entity.',
        'm_parents' => array(12577,4535,4593,4997),
    ),
);

//SOURCE LOCK:
$config['en_ids_4426'] = array(4430,4755,3288,4426,6196,3286);
$config['en_all_4426'] = array(
    4430 => array(
        'm_icon' => '<i class="fas fa-horse-head source" aria-hidden="true"></i>',
        'm_name' => 'PLAYERS',
        'm_desc' => '',
        'm_parents' => array(12639,12437,11035,10573,4983,6827,4426),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash" aria-hidden="true"></i>',
        'm_name' => 'PRIVATE READ',
        'm_desc' => '',
        'm_parents' => array(4755,6771,4426,4527),
    ),
    3288 => array(
        'm_icon' => '<i class="fad fa-envelope-open source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE EMAIL',
        'm_desc' => '',
        'm_parents' => array(4269,12221,12103,6225,4426,4755),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LOCK',
        'm_desc' => '',
        'm_parents' => array(4758,3303,4426,4527),
    ),
    6196 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE MESSENGER',
        'm_desc' => '',
        'm_parents' => array(12222,4426,3320),
    ),
    3286 => array(
        'm_icon' => '<i class="fad fa-key source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4269,4426,7578,6225,4755),
    ),
);

//PRIVATE READ:
$config['en_ids_4755'] = array(12453,10681,12450,4783,6232,4755,12336,12334,4554,7757,6155,6415,6559,6560,6556,6578,7611,4556,4555,7563,12360,4266,4267,6149,4283,6969,4275,7610,12489,4282,6563,5967,6132,4570,7702,7495,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,6143,4235,12197,7492,4552,6140,12328,7578,6224,4553,7562,6157,7489,4246,3288,12117,3286,4559,7504,6144,7485,7486,6997);
$config['en_all_4755'] = array(
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone note"></i>',
        'm_name' => 'NOTE FEATURE REQUEST',
        'm_desc' => '',
        'm_parents' => array(12137,4535,4755,4593,5967),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-bars note" aria-hidden="true"></i>',
        'm_name' => 'NOTE SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4535,4755,4593,10658),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-pen-square source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCE REQUEST',
        'm_desc' => '',
        'm_parents' => array(4593,4755,4535,5967),
    ),
    4783 => array(
        'm_icon' => '<i class="far fa-phone source"></i>',
        'm_name' => 'PHONE',
        'm_desc' => '',
        'm_parents' => array(4755,4319),
    ),
    6232 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'PLATFORM VARIABLES',
        'm_desc' => '',
        'm_parents' => array(4755,4527,6212),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash" aria-hidden="true"></i>',
        'm_name' => 'PRIVATE READ',
        'm_desc' => '',
        'm_parents' => array(4755,6771,4426,4527),
    ),
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    7757 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED MANUAL',
        'm_desc' => '',
        'm_parents' => array(6205,10888,10639,4506,6150,4593,4755),
    ),
    6415 => array(
        'm_icon' => '<i class="fad fa-trash-alt read" aria-hidden="true"></i>',
        'm_name' => 'READ CLEAR ALL',
        'm_desc' => '',
        'm_parents' => array(6205,11035,4755,6418,4593,6414),
    ),
    6559 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    7611 => array(
        'm_icon' => '<i class="read fad fa-hand-pointer"></i>',
        'm_name' => 'READ ENGAGED NOTE POST',
        'm_desc' => '',
        'm_parents' => array(6205,10639,7610,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    7563 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ MAGIC-READ',
        'm_desc' => '',
        'm_parents' => array(6205,4755,7569,4593),
    ),
    12360 => array(
        'm_icon' => '<i class="fad fa-pen read"></i>',
        'm_name' => 'READ MASS CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(6771,4593,4755),
    ),
    4266 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER OPT-IN',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ NOTE CONSIDERED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ NOTE LISTED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="read fad fa-megaphone"></i>',
        'm_name' => 'READ NOTE RECOMMENDED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="read fad fa-search"></i>',
        'm_name' => 'READ NOTE SEARCH',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6554,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="read fad fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ NOTE STARTED',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593),
    ),
    12489 => array(
        'm_icon' => '<i class="far fa-medal read"></i>',
        'm_name' => 'READ OPENED LEADERBOARD',
        'm_desc' => '',
        'm_parents' => array(4755,6205,4593,6222),
    ),
    4282 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(6205,4755,6222,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4593,4755,4280),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ READ CC',
        'm_desc' => '',
        'm_parents' => array(6205,4506,4527,7569,4755,4593),
    ),
    6132 => array(
        'm_icon' => '<i class="fad fa-bars read" aria-hidden="true"></i>',
        'm_name' => 'READ READS SORTED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6153,4506,4755,4593),
    ),
    4570 => array(
        'm_icon' => '<i class="read fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED',
        'm_desc' => '',
        'm_parents' => array(6205,10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED NOTE',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4593,4755,7569),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(6205,12227,7347,4755,4593),
    ),
    4577 => array(
        'm_icon' => '<i class="read fad fa-user-plus"></i>',
        'm_name' => 'READ SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ SENT FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="read fad fa-location-circle"></i>',
        'm_name' => 'READ SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(6205,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ SENT MESSENGER READ',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="read fad fa-cloud-download"></i>',
        'm_name' => 'READ SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="read fad fa-user-tag"></i>',
        'm_name' => 'READ SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(6205,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="read fad fa-comment-exclamation"></i>',
        'm_name' => 'READ SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fad fa-sign-in read" aria-hidden="true"></i>',
        'm_name' => 'READ SIGNIN FROM NOTE',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,6146,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ STARTED',
        'm_desc' => '',
        'm_parents' => array(6205,12227,7347,5967,4755,4593),
    ),
    12197 => array(
        'm_icon' => '<i class="fad fa-tags read" aria-hidden="true"></i>',
        'm_name' => 'READ TAG SOURCE',
        'm_desc' => '',
        'm_parents' => array(6205,7545,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4755,4593,4280),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(6205,12326,12227,6410,4229,4755,4593),
    ),
    12328 => array(
        'm_icon' => '<i class="fad fa-sync read"></i>',
        'm_name' => 'READ UPDATE COMPLETION',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,10658,6153),
    ),
    7578 => array(
        'm_icon' => '<i class="read fad fa-key"></i>',
        'm_name' => 'READ UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(6205,6222,10658,6153,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="read fad fa-sync"></i>',
        'm_name' => 'READ UPDATE PROFILE',
        'm_desc' => '',
        'm_parents' => array(6205,4755,6222,4593),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    7562 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ WELCOME',
        'm_desc' => '',
        'm_parents' => array(6205,4755,7569,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    3288 => array(
        'm_icon' => '<i class="fad fa-envelope-open source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE EMAIL',
        'm_desc' => '',
        'm_parents' => array(4269,12221,12103,6225,4426,4755),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    3286 => array(
        'm_icon' => '<i class="fad fa-key source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4269,4426,7578,6225,4755),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REVIEW TRIGGER',
        'm_desc' => '',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//TRANSACTION TYPE:
$config['en_ids_4593'] = array(12591,12592,10573,12419,4250,12453,4601,4229,4228,10686,10663,10664,10643,6226,4231,10676,10678,10679,10677,10681,10675,12450,4983,7545,10671,10662,10648,10650,10644,10651,4993,5001,10625,5943,12318,5865,4999,4998,5000,5981,11956,5982,5003,12129,12336,12334,4554,7757,6155,12106,6415,6559,6560,6556,6578,7611,4556,4555,7563,12360,10690,4266,4267,6149,4283,6969,4275,7610,12489,4282,6563,5967,10683,6132,4570,7702,7495,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,6143,4235,12197,7492,4552,6140,12328,7578,6224,4553,7562,6157,7489,10672,4246,4251,12117,10653,4259,10657,4261,10669,4260,4319,7657,4230,10656,4255,4318,10659,10673,4256,4258,4257,10689,10646,4559,7504,10654,6144,5007,7485,7486,6997,4994);
$config['en_all_4593'] = array(
    12591 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'NEXT EDITOR ADD SOURCE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,12589),
    ),
    12592 => array(
        'm_icon' => '<i class="fas fa-minus-circle source" aria-hidden="true"></i>',
        'm_name' => 'NEXT EDITOR REMOVE SOURCE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,12589),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark note" aria-hidden="true"></i>',
        'm_name' => 'NOTE BOOKMARKS',
        'm_desc' => 'Keeps track of the users who can manage/edit the intent',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,4535,12149,12141,10593,4593),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone note"></i>',
        'm_name' => 'NOTE FEATURE REQUEST',
        'm_desc' => '',
        'm_parents' => array(12137,4535,4755,4593,5967),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search note" aria-hidden="true"></i>',
        'm_name' => 'NOTE KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(4535,4527,6410,6283,4593,4486),
    ),
    4228 => array(
        'm_icon' => '<i class="fad fa-play-circle note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK FIXED',
        'm_desc' => '',
        'm_parents' => array(4535,6410,4593,4486),
    ),
    10686 => array(
        'm_icon' => '<i class="fad fa-times note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10663 => array(
        'm_icon' => '<i class="fad fa-coin note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK UPDATE MARKS',
        'm_desc' => '',
        'm_parents' => array(4535,4228,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4229,10658),
    ),
    10643 => array(
        'm_icon' => '<i class="fas fa-yin-yang mench-spin note" aria-hidden="true"></i>',
        'm_name' => 'NOTE LINK YIN YANG',
        'm_desc' => '',
        'm_parents' => array(4535,4593,6410,4486),
    ),
    6226 => array(
        'm_icon' => '<i class="fad fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE MASS UPDATE STATUS',
        'm_desc' => ' When all intents within a recursive tree are updated at once.',
        'm_parents' => array(4535,4593),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment note" aria-hidden="true"></i>',
        'm_name' => 'NOTE MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    10676 => array(
        'm_icon' => '<i class="fad fa-bars note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS SORTED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10678 => array(
        'm_icon' => '<i class="fad fa-trash-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10658,4593),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10593,10658),
    ),
    10677 => array(
        'm_icon' => '<i class="fad fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE PADS UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-bars note" aria-hidden="true"></i>',
        'm_name' => 'NOTE SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4535,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fad fa-bars note" aria-hidden="true"></i>',
        'm_name' => 'NOTE SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-pen-square source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCE REQUEST',
        'm_desc' => '',
        'm_parents' => array(4593,4755,4535,5967),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCES',
        'm_desc' => 'References track intent correlations referenced within expert sources, and represent a core building block of intelligence. References are among the most precious transaction types because they indicate that IF you do A, you will likely accomplish B. As trainers add more sources from more experts, certain intent correlations will receive more references than others, thus gaining more credibility.',
        'm_parents' => array(12450,10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag source" aria-hidden="true"></i>',
        'm_name' => 'NOTE SOURCE TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
    10671 => array(
        'm_icon' => '<i class="fad fa-trash-alt note" aria-hidden="true"></i>',
        'm_name' => 'NOTE STATUS ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593),
    ),
    10662 => array(
        'm_icon' => '<i class="fad fa-hashtag note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE LINK',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658),
    ),
    10648 => array(
        'm_icon' => '<i class="fad fa-sliders-h note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593),
    ),
    10650 => array(
        'm_icon' => '<i class="fad fa-clock note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE TIME',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    10644 => array(
        'm_icon' => '<i class="fad fa-bullseye-arrow note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE TITLE',
        'm_desc' => 'Logged when trainers update the intent outcome',
        'm_parents' => array(4535,10593,4593),
    ),
    10651 => array(
        'm_icon' => '<i class="fad fa-shapes note" aria-hidden="true"></i>',
        'm_name' => 'NOTE UPDATE TYPE',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    4993 => array(
        'm_icon' => '<i class="fad fa-eye note" aria-hidden="true"></i>',
        'm_name' => 'NOTE VIEWED',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    5001 => array(
        'm_icon' => '<i class="source fad fa-sticky-note"></i>',
        'm_name' => 'PORTFOLIO EDITOR CONTENT REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="source fad fa-user-circle"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON UPDATE FOR ALL',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    12318 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON UPDATE IF MISSING',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="source fad fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR LINK STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="source fad fa-layer-plus"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="source fad fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE IF ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="source fad fa-layer-minus"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE REMOVE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="source fad fa-sliders-h"></i>',
        'm_name' => 'PORTFOLIO EDITOR STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    12129 => array(
        'm_icon' => '<i class="fas fa-times-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(6205,6153,4593),
    ),
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(6205,7704,4755,4593,12326,12227),
    ),
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    7757 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED MANUAL',
        'm_desc' => 'Student prematurely removed an intention from their Action Plan without accomplishing it.',
        'm_parents' => array(6205,10888,10639,4506,6150,4593,4755),
    ),
    12106 => array(
        'm_icon' => '<i class="read fad fa-vote-yea read" aria-hidden="true"></i>',
        'm_name' => 'READ CHANNEL VOTE',
        'm_desc' => '',
        'm_parents' => array(6205,4593),
    ),
    6415 => array(
        'm_icon' => '<i class="fad fa-trash-alt read" aria-hidden="true"></i>',
        'm_name' => 'READ CLEAR ALL',
        'm_desc' => 'Removes certain links types as defined by its children from a Student\'s Action Plan. Currently only available for trainers.',
        'm_parents' => array(6205,11035,4755,6418,4593,6414),
    ),
    6559 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,6554),
    ),
    7611 => array(
        'm_icon' => '<i class="read fad fa-hand-pointer"></i>',
        'm_name' => 'READ ENGAGED NOTE POST',
        'm_desc' => 'Logged when a user expands a section of the intent',
        'm_parents' => array(6205,10639,7610,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    7563 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ MAGIC-READ',
        'm_desc' => '',
        'm_parents' => array(6205,4755,7569,4593),
    ),
    12360 => array(
        'm_icon' => '<i class="fad fa-pen read"></i>',
        'm_name' => 'READ MASS CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(6771,4593,4755),
    ),
    10690 => array(
        'm_icon' => '<i class="read fad fa-upload"></i>',
        'm_name' => 'READ MEDIA UPLOADED',
        'm_desc' => 'When a file added by the user is synced to the CDN',
        'm_parents' => array(6205,6153,4593,10658),
    ),
    4266 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER OPT-IN',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ NOTE CONSIDERED',
        'm_desc' => 'When a student chooses to review a given intention from the intentions they have searched or have been recommended after selecting GET STARTED from a mench.com intent landing page.',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ NOTE LISTED',
        'm_desc' => 'Once a student has added an Intention to their Action Plan, this link will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="read fad fa-megaphone"></i>',
        'm_name' => 'READ NOTE RECOMMENDED',
        'm_desc' => 'Logged every time an intention is recommended to a user by Mench',
        'm_parents' => array(6205,10639,4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="read fad fa-search"></i>',
        'm_name' => 'READ NOTE SEARCH',
        'm_desc' => 'When students invokes the [I want to] command and search for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(6205,10639,6554,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="read fad fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ NOTE STARTED',
        'm_desc' => 'When a user viewes the public intent landing page.',
        'm_parents' => array(6205,4755,4593),
    ),
    12489 => array(
        'm_icon' => '<i class="far fa-medal read"></i>',
        'm_name' => 'READ OPENED LEADERBOARD',
        'm_desc' => '',
        'm_parents' => array(4755,6205,4593,6222),
    ),
    4282 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(6205,4755,6222,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4593,4755,4280),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ READ CC',
        'm_desc' => '',
        'm_parents' => array(6205,4506,4527,7569,4755,4593),
    ),
    10683 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ READ EMAIL',
        'm_desc' => '',
        'm_parents' => array(6205,6153,10658,4593,7654),
    ),
    6132 => array(
        'm_icon' => '<i class="fad fa-bars read" aria-hidden="true"></i>',
        'm_name' => 'READ READS SORTED',
        'm_desc' => 'Student re-prioritized their top-level intentions to focus on intentions that currently matter the most.',
        'm_parents' => array(6205,10639,6153,4506,4755,4593),
    ),
    4570 => array(
        'm_icon' => '<i class="read fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED',
        'm_desc' => '',
        'm_parents' => array(6205,10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED NOTE',
        'm_desc' => 'Emails sent to intent subscribers who are looking for updates on an intent.',
        'm_parents' => array(6205,10593,4593,4755,7569),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => 'Intention recommended by Mench and added to Action Plan to enable the user to complete their intention',
        'm_parents' => array(6205,12227,7347,4755,4593),
    ),
    4577 => array(
        'm_icon' => '<i class="read fad fa-user-plus"></i>',
        'm_name' => 'READ SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ SENT FILE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="read fad fa-location-circle"></i>',
        'm_name' => 'READ SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(6205,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ SENT MESSENGER READ',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="read fad fa-cloud-download"></i>',
        'm_name' => 'READ SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="read fad fa-user-tag"></i>',
        'm_name' => 'READ SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(6205,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="read fad fa-comment-exclamation"></i>',
        'm_name' => 'READ SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(6205,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,7653,6102,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fad fa-sign-in read" aria-hidden="true"></i>',
        'm_name' => 'READ SIGNIN FROM NOTE',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(6205,12351,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => 'Logged every time a student consciously skips an intent and it\'s recursive children.',
        'm_parents' => array(6205,12229,12227,6146,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ STARTED',
        'm_desc' => '',
        'm_parents' => array(6205,12227,7347,5967,4755,4593),
    ),
    12197 => array(
        'm_icon' => '<i class="fad fa-tags read" aria-hidden="true"></i>',
        'm_name' => 'READ TAG SOURCE',
        'm_desc' => '',
        'm_parents' => array(6205,7545,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4755,4593,4280),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => 'Created when the student responses to OR branches meets the right % points to unlock the pathway to a conditional intent link.',
        'm_parents' => array(6205,12326,12227,6410,4229,4755,4593),
    ),
    12328 => array(
        'm_icon' => '<i class="fad fa-sync read"></i>',
        'm_name' => 'READ UPDATE COMPLETION',
        'm_desc' => '',
        'm_parents' => array(6205,4755,4593,10658,6153),
    ),
    7578 => array(
        'm_icon' => '<i class="read fad fa-key"></i>',
        'm_name' => 'READ UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(6205,6222,10658,6153,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="read fad fa-sync"></i>',
        'm_name' => 'READ UPDATE PROFILE',
        'm_desc' => '',
        'm_parents' => array(6205,4755,6222,4593),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(6205,10627,10593,6102,4755,4593,4280),
    ),
    7562 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ WELCOME',
        'm_desc' => '',
        'm_parents' => array(6205,4755,7569,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    10672 => array(
        'm_icon' => '<i class="fad fa-trash-alt source"></i>',
        'm_name' => 'SOURCE ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE CREATED',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(12401,12274,12149,12141,10645,10593,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    10653 => array(
        'm_icon' => '<i class="fad fa-user-circle source"></i>',
        'm_name' => 'SOURCE ICON UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10645),
    ),
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'SOURCE LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10658,10645),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10669 => array(
        'm_icon' => '<i class="fab fa-font-awesome-alt source"></i>',
        'm_name' => 'SOURCE LINK ICON',
        'm_desc' => '',
        'm_parents' => array(4535,4593,6198,4592),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="fad fa-sort-numeric-down source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK INTEGER',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    7657 => array(
        'm_icon' => '<i class="fas fa-divide source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK PERCENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="fad fa-link rotate90 source"></i>',
        'm_name' => 'SOURCE LINK RAW',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    10656 => array(
        'm_icon' => '<i class="fad fa-sliders-h source"></i>',
        'm_name' => 'SOURCE LINK STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10645),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'SOURCE LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="fad fa-clock source"></i>',
        'm_name' => 'SOURCE LINK TIME',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    10659 => array(
        'm_icon' => '<i class="fad fa-plug source"></i>',
        'm_name' => 'SOURCE LINK TYPE UPDATE',
        'm_desc' => 'Iterations happens automatically based on link content',
        'm_parents' => array(4535,10658,4593,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="fad fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10645,4593,10658),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK URL',
        'm_desc' => '',
        'm_parents' => array(11080,4535,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'SOURCE LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source"></i>',
        'm_name' => 'SOURCE LINK WIDGET',
        'm_desc' => '',
        'm_parents' => array(12605,12524,12403,4535,4593,4592,4537,4506),
    ),
    10689 => array(
        'm_icon' => '<i class="fad fa-share-alt rotate90 source"></i>',
        'm_name' => 'SOURCE MERGED IN SOURCE',
        'm_desc' => 'When an entity is merged with another entity and the links are carried over',
        'm_parents' => array(4535,4593,10658,10645),
    ),
    10646 => array(
        'm_icon' => '<i class="fad fa-fingerprint source"></i>',
        'm_name' => 'SOURCE NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10645),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => 'Logged when a student receives the messages of an AND intent that does not have any completion requirements.',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REVIEW TRIGGER',
        'm_desc' => 'Certain links that match an unknown behavior would require an admin to review and ensure it\'s all good',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    10654 => array(
        'm_icon' => '<i class="fad fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => 'Logged when a student submits the requirements (text, video, etc...) of an AND intent which could not be completed by simply receiving messages.',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    5007 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TOGGLE SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
    4994 => array(
        'm_icon' => '<i class="fad fa-eye source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE VIEWED',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
);

//SOURCE LINKS:
$config['en_ids_4592'] = array(4259,4261,10669,4260,4319,7657,4230,4255,4318,4256,4258,4257);
$config['en_all_4592'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10669 => array(
        'm_icon' => '<i class="fab fa-font-awesome-alt source"></i>',
        'm_name' => 'ICON',
        'm_desc' => 'Icons maping to the Font Awesome database',
        'm_parents' => array(4535,4593,6198,4592),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="fad fa-sort-numeric-down source" aria-hidden="true"></i>',
        'm_name' => 'INTEGER',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    7657 => array(
        'm_icon' => '<i class="fas fa-divide source" aria-hidden="true"></i>',
        'm_name' => 'PERCENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="fad fa-link rotate90 source"></i>',
        'm_name' => 'RAW',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'TEXT',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="fad fa-clock source"></i>',
        'm_name' => 'TIME',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(11080,4535,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source"></i>',
        'm_name' => 'WIDGET',
        'm_desc' => '',
        'm_parents' => array(12605,12524,12403,4535,4593,4592,4537,4506),
    ),
);

//NOTE PADS:
$config['en_ids_4485'] = array(4231,12419,10573,4601,4983,7545);
$config['en_all_4485'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment note" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt note" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark note" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search note" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(12450,10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
);

//NOTE TREE LINKS:
$config['en_ids_4486'] = array(10643,4228,4229);
$config['en_all_4486'] = array(
    10643 => array(
        'm_icon' => '<i class="fas fa-yin-yang mench-spin note" aria-hidden="true"></i>',
        'm_name' => 'YIN YANG',
        'm_desc' => 'Notes with opposing concepts that are each valid on their own',
        'm_parents' => array(4535,4593,6410,4486),
    ),
    4228 => array(
        'm_icon' => '<i class="fad fa-play-circle note" aria-hidden="true"></i>',
        'm_name' => 'FIXED',
        'm_desc' => 'Notes that always follow each other',
        'm_parents' => array(4535,6410,4593,4486),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle note" aria-hidden="true"></i>',
        'm_name' => 'CONDITIONAL',
        'm_desc' => 'Notes that sometimes follow each other',
        'm_parents' => array(4535,4527,6410,6283,4593,4486),
    ),
);

//SOURCES LINKS URLS:
$config['en_ids_4537'] = array(4259,4261,4260,4256,4258,4257);
$config['en_all_4537'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'Link notes contain a URL to a raw file.',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
        'm_parents' => array(12605,12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => 'Link note contains a generic URL only.',
        'm_parents' => array(11080,4535,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'Link notes contain a URL to a raw video file.',
        'm_parents' => array(12605,12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source"></i>',
        'm_name' => 'WIDGET',
        'm_desc' => 'Link note contain a recognizable URL that offers an embed widget for a more engaging play-back experience.',
        'm_parents' => array(12605,12524,12403,4535,4593,4592,4537,4506),
    ),
);

//NON-FICTION SOURCE TYPES:
$config['en_ids_3000'] = array(3005,2999,2998,2997,3147,4446,3192,4763,5948);
$config['en_all_3000'] = array(
    3005 => array(
        'm_icon' => '<i class="fad fa-book source" aria-hidden="true"></i>',
        'm_name' => 'BOOKS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone source" aria-hidden="true"></i>',
        'm_name' => 'PODCASTS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,10809,4983,7614,6805,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'VIDEOS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    2997 => array(
        'm_icon' => '<i class="fad fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'ARTICLES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'COURSES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fad fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'TOOLS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone source" aria-hidden="true"></i>',
        'm_name' => 'CHANNELS',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="fad fa-file-invoice source" aria-hidden="true"></i>',
        'm_name' => 'TEMPLATES',
        'm_desc' => '',
        'm_parents' => array(12639,12523,4983,7614,3000),
    ),
);