<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
* Keep a cache of certain parts of the Blog tree for faster processing
* So we don't have to make DB calls to figure them out every time!
* See here for all players cached: https://mench.com/play/4527
*
* Note: Also search for "en_ids_" and "en_all_" when trying to manage these throughout the code base
*
*/

//Generated 2020-03-27 13:51:22 PST

//SIGN IN/UP:
$config['en_ids_4269'] = array(3288,6197,3286);
$config['en_all_4269'] = array(
    3288 => array(
        'm_icon' => '<i class="fad fa-envelope-open play" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => '',
        'm_parents' => array(4269,12221,12103,6225,4426,4755),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint play" aria-hidden="true"></i>',
        'm_name' => 'NICKNAME',
        'm_desc' => '',
        'm_parents' => array(4269,12412,12232,6225,11072,10646,5000,4998,4999,6232,6206),
    ),
    3286 => array(
        'm_icon' => '<i class="fad fa-key play" aria-hidden="true"></i>',
        'm_name' => 'PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4269,4426,7578,6225,4755),
    ),
);

//FILE UPLOADING ALLOWED:
$config['en_ids_12359'] = array(12419,4231);
$config['en_all_12359'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt blog" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment blog" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
);

//PLAY UPDATER UPPERCASE:
$config['en_ids_12577'] = array(4999,4998,5000,5981,11956,5982);
$config['en_all_12577'] = array(
    4999 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="play fad fa-layer-plus"></i>',
        'm_name' => 'PROFILE ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="play fad fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PROFILE IF ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="play fad fa-layer-minus"></i>',
        'm_name' => 'PROFILE REMOVE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
);

//LAYOUT SHOW EVEN IF ZERO:
$config['en_ids_12574'] = array(11029,4997,11030);
$config['en_all_12574'] = array(
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap play" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIOS',
        'm_desc' => '',
        'm_parents' => array(12574,12571,11089,11028),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-edit play" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIOS EDITOR',
        'm_desc' => '',
        'm_parents' => array(11029,12574,10967,11089,4758,4506,4527),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-user-circle play" aria-hidden="true"></i>',
        'm_name' => 'PROFILES',
        'm_desc' => '',
        'm_parents' => array(12574,11089,11028),
    ),
);

//PLAY STATUS SEARCHABLE:
$config['en_ids_12575'] = array(12563);
$config['en_all_12575'] = array(
    12563 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'PLAY SEARCHABLE',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
);

//LAYOUT OPEN BY DEFAULT:
$config['en_ids_12571'] = array(12273,11029);
$config['en_all_12571'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG COINS',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap play" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIOS',
        'm_desc' => '',
        'm_parents' => array(12574,12571,11089,11028),
    ),
);

//MESSAGE VISUAL MEDIA:
$config['en_ids_12524'] = array(4259,4261,4260,4258,4257);
$config['en_all_12524'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up play"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf play" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image play" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video play"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle play"></i>',
        'm_name' => 'WIDGET',
        'm_desc' => '',
        'm_parents' => array(12524,12403,4535,4593,4592,4537,4506),
    ),
);

//INHERIT PARENT ICON:
$config['en_ids_12523'] = array(2997,4446,3005,4763,3147,2999,3084,5948,3192,2998);
$config['en_all_12523'] = array(
    2997 => array(
        'm_icon' => '<i class="fad fa-file-word play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT ARTICLES',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fad fa-tachometer play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fad fa-book play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT BOOKS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT CHANNELS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT COURSES',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT PODCASTS',
        'm_desc' => '',
        'm_parents' => array(12523,10809,4983,7614,6805,3000),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut play" aria-hidden="true"></i>',
        'm_name' => 'EXPERTS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,6827),
    ),
    5948 => array(
        'm_icon' => '<i class="fad fa-file-invoice play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT TEMPLATES',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT TOOLS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fad fa-play play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT VIDEOS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
);

//MENCH JAVASCRIPT FUNCTIONS:
$config['en_ids_12502'] = array(10957);
$config['en_all_12502'] = array(
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt play" aria-hidden="true"></i>',
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
        'm_name' => 'PLAY MENU',
        'm_desc' => '',
        'm_parents' => array(12497,12501,4527),
    ),
);

//PLAY MENU:
$config['en_ids_12500'] = array(6225,12205,10957,6287,7291);
$config['en_all_12500'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-user-cog play" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(10876,12500,4536,11035,4527),
    ),
    12205 => array(
        'm_icon' => '<i class="fad fa-user-circle play" aria-hidden="true"></i>',
        'm_name' => 'PUBLIC PROFILE',
        'm_desc' => '',
        'm_parents' => array(12500,11035),
    ),
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt play" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(12502,12500,6225,11035,5007,4527),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-user-shield play" aria-hidden="true"></i>',
        'm_name' => 'ADMIN PANEL',
        'm_desc' => '',
        'm_parents' => array(12500,10985,10876,11035,4527),
    ),
    7291 => array(
        'm_icon' => '<i class="fad fa-power-off play" aria-hidden="true"></i>',
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

//PLAY ICON DROPDOWN:
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

//BLOG TEXT INPUT SHOW ICON:
$config['en_ids_12420'] = array(4356,4358,4739,4735);
$config['en_all_12420'] = array(
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch " aria-hidden="true"></i>',
        'm_name' => 'BLOG READ TIME',
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

//BLOG TREE NODES:
$config['en_ids_12413'] = array(11020,11019);
$config['en_all_12413'] = array(
    11020 => array(
        'm_icon' => '<i class="fas fa-step-forward blog" aria-hidden="true"></i>',
        'm_name' => 'NEXT',
        'm_desc' => '',
        'm_parents' => array(12413,11025,11018),
    ),
    11019 => array(
        'm_icon' => '<i class="fas fa-step-backward blog" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS',
        'm_desc' => '',
        'm_parents' => array(12365,12413,10990,11025),
    ),
);

//MENCH LINKS:
$config['en_ids_10876'] = array(6287,4535,10984,10939,10985,7291,11999,6225,4536,10986,10983,10967,6205,10964,10989,10988);
$config['en_all_10876'] = array(
    6287 => array(
        'm_icon' => '<i class="fad fa-user-shield play" aria-hidden="true"></i>',
        'm_name' => 'ADMIN PANEL',
        'm_desc' => 'https://mench.com/play/admin_panel',
        'm_parents' => array(12500,10985,10876,11035,4527),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG',
        'm_desc' => 'https://mench.com/blog',
        'm_parents' => array(12499,12112,10876,4527,12155,2738),
    ),
    10984 => array(
        'm_icon' => '<i class="fad fa-paint-brush-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG BRUSH',
        'm_desc' => 'https://mench.com/13274',
        'm_parents' => array(10876,10983,10957),
    ),
    10939 => array(
        'm_icon' => '<i class="fad fa-pen blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG PEN',
        'm_desc' => 'https://mench.com/13273',
        'm_parents' => array(10876,10957),
    ),
    10985 => array(
        'm_icon' => '<i class="fad fa-magic blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG WAND',
        'm_desc' => 'https://mench.com/13275',
        'm_parents' => array(10876,10984,10957),
    ),
    7291 => array(
        'm_icon' => '<i class="fad fa-power-off play" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => 'https://mench.com/play/signout',
        'm_parents' => array(10876,12500,11035),
    ),
    11999 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => 'https://mench.com/ledger',
        'm_parents' => array(10876,6771,11035),
    ),
    6225 => array(
        'm_icon' => '<i class="fad fa-user-cog play" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => 'https://mench.com/play/account',
        'm_parents' => array(10876,12500,4536,11035,4527),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY',
        'm_desc' => 'https://mench.com/play',
        'm_parents' => array(12499,10876,4527,5008,12155,2738),
    ),
    10986 => array(
        'm_icon' => '<i class="fad fa-dice play" aria-hidden="true"></i>',
        'm_name' => 'PLAY DICE',
        'm_desc' => 'https://mench.com/13278',
        'm_parents' => array(10876,10967,10957),
    ),
    10983 => array(
        'm_icon' => '<i class="fad fa-gamepad play" aria-hidden="true"></i>',
        'm_name' => 'PLAY JOYSTICK',
        'm_desc' => 'https://mench.com/13276',
        'm_parents' => array(10876,10964,10957),
    ),
    10967 => array(
        'm_icon' => '<i class="fad fa-turntable play" aria-hidden="true"></i>',
        'm_name' => 'PLAY TURNTABLE',
        'm_desc' => 'https://mench.com/13277',
        'm_parents' => array(10876,10985,10957),
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
);

//PLAY COINS:
$config['en_ids_12410'] = array(12273,6255);
$config['en_all_12410'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG COINS',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ COINS',
        'm_desc' => '',
        'm_parents' => array(12467,10964,11089,12410,12365,6771,12228,4527),
    ),
);

//PLAY LINK TYPE CUSTOM UI:
$config['en_ids_12403'] = array(4257);
$config['en_all_12403'] = array(
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle play"></i>',
        'm_name' => 'PLAY LINK WIDGET',
        'm_desc' => '',
        'm_parents' => array(12524,12403,4535,4593,4592,4537,4506),
    ),
);

//PLAY SYNC STATUS:
$config['en_ids_12401'] = array(10672,4251,10654);
$config['en_all_12401'] = array(
    10672 => array(
        'm_icon' => '<i class="fad fa-trash-alt play"></i>',
        'm_name' => 'ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'CREATED',
        'm_desc' => '',
        'm_parents' => array(12401,12274,12149,12141,10645,10593,4593),
    ),
    10654 => array(
        'm_icon' => '<i class="fad fa-sliders-h play" aria-hidden="true"></i>',
        'm_name' => 'STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
);

//BLOG SYNC STATUS:
$config['en_ids_12400'] = array(10671,4250,10648);
$config['en_all_12400'] = array(
    10671 => array(
        'm_icon' => '<i class="fad fa-trash-alt blog" aria-hidden="true"></i>',
        'm_name' => 'ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593,10638),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,4535,12149,12141,10638,10593,4593),
    ),
    10648 => array(
        'm_icon' => '<i class="fad fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593,10638),
    ),
);

//BLOG BODY:
$config['en_ids_12365'] = array(11019,4231,4601,12419,10573,7347,6255,6146,4983,7545,11047);
$config['en_all_12365'] = array(
    11019 => array(
        'm_icon' => '<i class="fas fa-step-backward blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG PREVIOUS',
        'm_desc' => '',
        'm_parents' => array(12365,12413,10990,11025),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-list-ul blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    7347 => array(
        'm_icon' => '<i class="fas fa-list-ul read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10989,11089,12365,6205,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ COINS',
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
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAYS',
        'm_desc' => '',
        'm_parents' => array(10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag play" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAY TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down" aria-hidden="true"></i>',
        'm_name' => 'BLOG ADMIN MENU',
        'm_desc' => '',
        'm_parents' => array(10984,12365,4527,11040),
    ),
);

//PLAY:
$config['en_ids_4536'] = array(7305,6225,12289,11089,10645,4758,6206);
$config['en_all_4536'] = array(
    7305 => array(
        'm_icon' => '<i class="fas fa-layer-group play" aria-hidden="true"></i>',
        'm_name' => 'MENCH PLATFORM',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    6225 => array(
        'm_icon' => '<i class="fad fa-user-cog play" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(10876,12500,4536,11035,4527),
    ),
    12289 => array(
        'm_icon' => '<i class="fad fa-paw play" aria-hidden="true"></i>',
        'm_name' => 'PLAY AVATAR',
        'm_desc' => '',
        'm_parents' => array(4536,6225),
    ),
    11089 => array(
        'm_icon' => '<i class="fad fa-crop-alt play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4536,4527),
    ),
    10645 => array(
        'm_icon' => '<i class="fas fa-sync play" aria-hidden="true"></i>',
        'm_name' => 'PLAY READS',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    4758 => array(
        'm_icon' => '<i class="fas fa-cog play" aria-hidden="true"></i>',
        'm_name' => 'PLAY SETTINGS',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    6206 => array(
        'm_icon' => '<i class="far fa-table play" aria-hidden="true"></i>',
        'm_name' => 'PLAY TABLE',
        'm_desc' => '',
        'm_parents' => array(4527,7735,4536),
    ),
);

//BLOG:
$config['en_ids_4535'] = array(10671,12450,12419,4250,12453,4601,11021,4229,4228,10686,10663,10664,10643,10573,6226,4231,4485,10676,10678,10679,10677,4983,7545,11160,6768,10681,10675,7302,6201,10662,10648,10650,10644,10651,4993,10672,4246,10653,4259,10657,4261,10669,4260,4319,7657,4230,10656,4255,4318,10659,10673,4256,4258,4257,10689,10646,7504,10654,5007,5001,10625,5943,12318,5865,4999,4998,5000,5981,11956,5982,5003,4994);
$config['en_all_4535'] = array(
    10671 => array(
        'm_icon' => '<i class="fad fa-trash-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593,10638),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-pen-square blog"></i>',
        'm_name' => 'BLOG AUTHOR REQUEST',
        'm_desc' => '',
        'm_parents' => array(4593,4755,4535,5967),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,4535,12149,12141,10638,10593,4593),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone blog"></i>',
        'm_name' => 'BLOG FEATURE REQUEST',
        'm_desc' => '',
        'm_parents' => array(12137,4535,4755,4593,5967),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    11021 => array(
        'm_icon' => '<i class="fad fa-crop-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4527,4535),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(4535,4527,6410,6283,4593,4486),
    ),
    4228 => array(
        'm_icon' => '<i class="fad fa-play blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK FIXED',
        'm_desc' => '',
        'm_parents' => array(4535,6410,4593,4486),
    ),
    10686 => array(
        'm_icon' => '<i class="fad fa-unlink blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="fad fa-coin blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE MARKS',
        'm_desc' => '',
        'm_parents' => array(4535,4228,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4593,4229,10658),
    ),
    10643 => array(
        'm_icon' => '<i class="fas fa-yin-yang mench-spin blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK YIN YANG',
        'm_desc' => '',
        'm_parents' => array(4535,4593,6410,4486),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-list-ul blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    6226 => array(
        'm_icon' => '<i class="fad fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG MASS UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES',
        'm_desc' => '',
        'm_parents' => array(12408,4535,4527),
    ),
    10676 => array(
        'm_icon' => '<i class="fad fa-sort blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES SORTED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="fad fa-trash-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10658,4593,10638),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="fad fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAYS',
        'm_desc' => '',
        'm_parents' => array(10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag play" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAY TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
    11160 => array(
        'm_icon' => '<i class="fas fa-info-circle blog"></i>',
        'm_name' => 'BLOG READS',
        'm_desc' => '',
        'm_parents' => array(4535),
    ),
    6768 => array(
        'm_icon' => '<i class="far fa-cog blog"></i>',
        'm_name' => 'BLOG SETTINGS',
        'm_desc' => '',
        'm_parents' => array(4535),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-sort blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fad fa-sort blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    7302 => array(
        'm_icon' => '<i class="far fa-chart-bar blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATS',
        'm_desc' => '',
        'm_parents' => array(4527,4535),
    ),
    6201 => array(
        'm_icon' => '<i class="far fa-table blog"></i>',
        'm_name' => 'BLOG TABLE',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7735,4535),
    ),
    10662 => array(
        'm_icon' => '<i class="fad fa-hashtag blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE LINK',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4593,10658),
    ),
    10648 => array(
        'm_icon' => '<i class="fad fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593,10638),
    ),
    10650 => array(
        'm_icon' => '<i class="fad fa-clock blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TIME',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="fad fa-bullseye-arrow blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TITLE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="fad fa-shapes blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TYPE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10638),
    ),
    4993 => array(
        'm_icon' => '<i class="fad fa-eye blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG VIEWED',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4593),
    ),
    10672 => array(
        'm_icon' => '<i class="fad fa-trash-alt play"></i>',
        'm_name' => 'PLAY ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug play" aria-hidden="true"></i>',
        'm_name' => 'PLAY BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    10653 => array(
        'm_icon' => '<i class="fad fa-user-circle play"></i>',
        'm_name' => 'PLAY ICON UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10645),
    ),
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up play"></i>',
        'm_name' => 'PLAY LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10658,10645),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10669 => array(
        'm_icon' => '<i class="fab fa-font-awesome-alt play"></i>',
        'm_name' => 'PLAY LINK ICON',
        'm_desc' => '',
        'm_parents' => array(4535,4593,6198,4592),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="fad fa-sort-numeric-down play"></i>',
        'm_name' => 'PLAY LINK INTEGER',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    7657 => array(
        'm_icon' => '<i class="fas fa-divide play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK PERCENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="fad fa-link rotate90 play"></i>',
        'm_name' => 'PLAY LINK RAW',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    10656 => array(
        'm_icon' => '<i class="fad fa-sliders-h play"></i>',
        'm_name' => 'PLAY LINK STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10645),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left play"></i>',
        'm_name' => 'PLAY LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="fad fa-clock play"></i>',
        'm_name' => 'PLAY LINK TIME',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    10659 => array(
        'm_icon' => '<i class="fad fa-plug play"></i>',
        'm_name' => 'PLAY LINK TYPE UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10658,4593,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="fad fa-trash-alt play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10645,4593,10658),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK URL',
        'm_desc' => '',
        'm_parents' => array(11080,4535,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video play"></i>',
        'm_name' => 'PLAY LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle play"></i>',
        'm_name' => 'PLAY LINK WIDGET',
        'm_desc' => '',
        'm_parents' => array(12524,12403,4535,4593,4592,4537,4506),
    ),
    10689 => array(
        'm_icon' => '<i class="fad fa-share-alt rotate90 play"></i>',
        'm_name' => 'PLAY MERGED IN PLAY',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10645),
    ),
    10646 => array(
        'm_icon' => '<i class="fad fa-fingerprint play"></i>',
        'm_name' => 'PLAY NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10645),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation play" aria-hidden="true"></i>',
        'm_name' => 'PLAY REVIEW TRIGGER',
        'm_desc' => '',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    10654 => array(
        'm_icon' => '<i class="fad fa-sliders-h play" aria-hidden="true"></i>',
        'm_name' => 'PLAY STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
    5007 => array(
        'm_icon' => '<i class="fad fa-bolt play" aria-hidden="true"></i>',
        'm_name' => 'PLAY TOGGLE SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    5001 => array(
        'm_icon' => '<i class="play fad fa-sticky-note"></i>',
        'm_name' => 'PLAY UPDATER CONTENT REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="play fad fa-user-circle"></i>',
        'm_name' => 'PLAY UPDATER ICON REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fad fa-user-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY UPDATER ICON UPDATE FOR ALL',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    12318 => array(
        'm_icon' => '<i class="fad fa-user-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY UPDATER ICON UPDATE IF MISSING',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="play fad fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'PLAY UPDATER LINK STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'PLAY UPDATER NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'PLAY UPDATER NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'PLAY UPDATER NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="play fad fa-layer-plus"></i>',
        'm_name' => 'PLAY UPDATER PROFILE ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="play fad fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PLAY UPDATER PROFILE IF ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="play fad fa-layer-minus"></i>',
        'm_name' => 'PLAY UPDATER PROFILE REMOVE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="play fad fa-sliders-h"></i>',
        'm_name' => 'PLAY UPDATER STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    4994 => array(
        'm_icon' => '<i class="fad fa-eye play" aria-hidden="true"></i>',
        'm_name' => 'PLAY VIEWED',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
);

//READ:
$config['en_ids_6205'] = array(12024,6157,7489,12117,4559,6144,7485,7486,6997,12129,12336,12334,4554,6149,6969,4275,4283,7610,7757,6155,12106,6415,6559,6560,6556,6578,7611,4556,4555,7347,7563,10690,4266,4267,12489,4282,6563,5967,10683,6132,4570,7702,7495,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,6771,7560,7561,7564,7559,7558,6143,4235,7304,4341,12197,7492,4552,6140,12328,7578,6224,10658,4553,7562);
$config['en_all_6205'] = array(
    12024 => array(
        'm_icon' => '<i class="fas fa-flag read" aria-hidden="true"></i>',
        'm_name' => 'MENCH MILESTONES',
        'm_desc' => '',
        'm_parents' => array(1,6205),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'PLAY FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'PLAY READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'PLAY TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
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
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ BLOG CONSIDERED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="read fad fa-megaphone"></i>',
        'm_name' => 'READ BLOG RECOMMENDED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="read fad fa-search"></i>',
        'm_name' => 'READ BLOG SEARCH',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6554,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ BLOGS LISTED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="read fad fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ BLOG STARTED',
        'm_desc' => '',
        'm_parents' => array(6205,10638,4755,4593),
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
        'm_name' => 'READ ENGAGED BLOG POST',
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
        'm_icon' => '<i class="fas fa-list-ul read" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="read fad fa-sort read" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ RECEIVED BLOG',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4593,4755,7569),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-play read" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN FROM BLOG',
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
    4341 => array(
        'm_icon' => '<i class="far fa-table read"></i>',
        'm_name' => 'READ TABLE',
        'm_desc' => '',
        'm_parents' => array(4527,7735,6205),
    ),
    12197 => array(
        'm_icon' => '<i class="fad fa-tags read" aria-hidden="true"></i>',
        'm_name' => 'READ TAG PLAY',
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
);

//BLOG TYPE INSTANTLY DONE:
$config['en_ids_12330'] = array(6677,6914,6907);
$config['en_all_12330'] = array(
    6677 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'READ',
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

//READ BLOG LINKS:
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

//BLOG TYPE MANUAL INPUT:
$config['en_ids_12324'] = array(6683,7637);
$config['en_all_12324'] = array(
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard " aria-hidden="true"></i>',
        'm_name' => 'TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12324,6144,7585,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12324,12117,7751,7585,6192),
    ),
);

//TEMPLATE BLOG MESSAGES:
$config['en_ids_12322'] = array(12419,4601,4231);
$config['en_all_12322'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt blog" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search blog" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment blog" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
);

//TEMPLATE BLOG READ:
$config['en_ids_12321'] = array(12273,10573,7545);
$config['en_all_12321'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'COINS',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-list-ul blog" aria-hidden="true"></i>',
        'm_name' => 'LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag play" aria-hidden="true"></i>',
        'm_name' => 'PLAY TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
);

//PLAY AVATAR SUPER:
$config['en_ids_12279'] = array(12280,12281,12282,12286,12287,12288,12308,12309,12310,12234,12233,10965,12236,12235,10979,12295,12294,12293,12296,12297,12298,12300,12301,12299,12237,12238,10978,12314,12315,12316,12240,12239,10963,12241,12242,12207,12244,12243,10966,12245,12246,10976,12248,12247,10962,12249,12250,10975,12252,12251,10982,12253,12254,10970,12302,12303,12304,12256,12255,10972,12306,12307,12305,12257,12258,10969,12312,12313,12311,12260,12259,10960,12277,12276,12278,12439,12262,10981,12264,12263,10968,12265,12266,10974,12290,12291,12292,12268,12267,12206,12269,12270,10958,12285,12284,12283,12272,12271,12231);
$config['en_all_12279'] = array(
    12280 => array(
        'm_icon' => '<i class="fas fa-alicorn play"></i>',
        'm_name' => 'ALICORN BOLD',
        'm_desc' => '',
        'm_parents' => array(10983,12279),
    ),
    12281 => array(
        'm_icon' => '<i class="far fa-alicorn play"></i>',
        'm_name' => 'ALICORN LIGHT',
        'm_desc' => '',
        'm_parents' => array(10983,12279),
    ),
    12282 => array(
        'm_icon' => '<i class="fad fa-alicorn play"></i>',
        'm_name' => 'ALICORN MIX',
        'm_desc' => '',
        'm_parents' => array(10983,12279),
    ),
    12286 => array(
        'm_icon' => '<i class="fas fa-bat play" aria-hidden="true"></i>',
        'm_name' => 'BAT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12287 => array(
        'm_icon' => '<i class="far fa-bat play" aria-hidden="true"></i>',
        'm_name' => 'BAT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12288 => array(
        'm_icon' => '<i class="fad fa-bat play" aria-hidden="true"></i>',
        'm_name' => 'BAT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12308 => array(
        'm_icon' => '<i class="fas fa-spider-black-widow play"></i>',
        'm_name' => 'BLACK WIDOW BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12309 => array(
        'm_icon' => '<i class="far fa-spider-black-widow play"></i>',
        'm_name' => 'BLACK WIDOW LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12310 => array(
        'm_icon' => '<i class="fad fa-spider-black-widow play"></i>',
        'm_name' => 'BLACK WIDOW MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12234 => array(
        'm_icon' => '<i class="fas fa-dog play"></i>',
        'm_name' => 'DOGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12233 => array(
        'm_icon' => '<i class="far fa-dog play"></i>',
        'm_name' => 'DOGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10965 => array(
        'm_icon' => '<i class="fad fa-dog play"></i>',
        'm_name' => 'DOGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12236 => array(
        'm_icon' => '<i class="fas fa-duck play" aria-hidden="true"></i>',
        'm_name' => 'DONALD BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12235 => array(
        'm_icon' => '<i class="far fa-duck play" aria-hidden="true"></i>',
        'm_name' => 'DONALD LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10979 => array(
        'm_icon' => '<i class="fad fa-duck play"></i>',
        'm_name' => 'DONALD MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12295 => array(
        'm_icon' => '<i class="fas fa-dove play" aria-hidden="true"></i>',
        'm_name' => 'DOVE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12294 => array(
        'm_icon' => '<i class="far fa-dove play" aria-hidden="true"></i>',
        'm_name' => 'DOVE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12293 => array(
        'm_icon' => '<i class="fad fa-dove play" aria-hidden="true"></i>',
        'm_name' => 'DOVE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12296 => array(
        'm_icon' => '<i class="fas fa-dragon play"></i>',
        'm_name' => 'DRAGON BOLD',
        'm_desc' => '',
        'm_parents' => array(10967,12279),
    ),
    12297 => array(
        'm_icon' => '<i class="far fa-dragon play"></i>',
        'm_name' => 'DRAGON LIGHT',
        'm_desc' => '',
        'm_parents' => array(10967,12279),
    ),
    12298 => array(
        'm_icon' => '<i class="fad fa-dragon play"></i>',
        'm_name' => 'DRAGON MIX',
        'm_desc' => '',
        'm_parents' => array(10967,12279),
    ),
    12300 => array(
        'm_icon' => '<i class="fas fa-elephant play" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12301 => array(
        'm_icon' => '<i class="far fa-elephant play" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12299 => array(
        'm_icon' => '<i class="fad fa-elephant play" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12237 => array(
        'm_icon' => '<i class="fas fa-fish play" aria-hidden="true"></i>',
        'm_name' => 'FISHY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12238 => array(
        'm_icon' => '<i class="far fa-fish play" aria-hidden="true"></i>',
        'm_name' => 'FISHY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10978 => array(
        'm_icon' => '<i class="fad fa-fish play"></i>',
        'm_name' => 'FISHY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12314 => array(
        'm_icon' => '<i class="fas fa-frog play" aria-hidden="true"></i>',
        'm_name' => 'FROG BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12315 => array(
        'm_icon' => '<i class="far fa-frog play" aria-hidden="true"></i>',
        'm_name' => 'FROG LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12316 => array(
        'm_icon' => '<i class="fad fa-frog play" aria-hidden="true"></i>',
        'm_name' => 'FROG MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12240 => array(
        'm_icon' => '<i class="fas fa-hippo play" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12239 => array(
        'm_icon' => '<i class="far fa-hippo play" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10963 => array(
        'm_icon' => '<i class="fad fa-hippo play"></i>',
        'm_name' => 'HIPPOY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12241 => array(
        'm_icon' => '<i class="fas fa-badger-honey play" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12242 => array(
        'm_icon' => '<i class="far fa-badger-honey play" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12207 => array(
        'm_icon' => '<i class="fad fa-badger-honey play"></i>',
        'm_name' => 'HONEY BADGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12244 => array(
        'm_icon' => '<i class="fas fa-deer play" aria-hidden="true"></i>',
        'm_name' => 'HONEY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12243 => array(
        'm_icon' => '<i class="far fa-deer play" aria-hidden="true"></i>',
        'm_name' => 'HONEY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10966 => array(
        'm_icon' => '<i class="fad fa-deer play"></i>',
        'm_name' => 'HONEY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12245 => array(
        'm_icon' => '<i class="fas fa-horse play" aria-hidden="true"></i>',
        'm_name' => 'HORSY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12246 => array(
        'm_icon' => '<i class="far fa-horse play" aria-hidden="true"></i>',
        'm_name' => 'HORSY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10976 => array(
        'm_icon' => '<i class="fad fa-horse play"></i>',
        'm_name' => 'HORSY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12248 => array(
        'm_icon' => '<i class="fas fa-monkey play" aria-hidden="true"></i>',
        'm_name' => 'HUMAN BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12247 => array(
        'm_icon' => '<i class="far fa-monkey play" aria-hidden="true"></i>',
        'm_name' => 'HUMAN LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10962 => array(
        'm_icon' => '<i class="fad fa-monkey play"></i>',
        'm_name' => 'HUMAN MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12249 => array(
        'm_icon' => '<i class="fas fa-kiwi-bird play" aria-hidden="true"></i>',
        'm_name' => 'KIWI BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12250 => array(
        'm_icon' => '<i class="far fa-kiwi-bird play" aria-hidden="true"></i>',
        'm_name' => 'KIWI LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10975 => array(
        'm_icon' => '<i class="fad fa-kiwi-bird play"></i>',
        'm_name' => 'KIWI MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12252 => array(
        'm_icon' => '<i class="fas fa-cat play" aria-hidden="true"></i>',
        'm_name' => 'MIMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12251 => array(
        'm_icon' => '<i class="far fa-cat play" aria-hidden="true"></i>',
        'm_name' => 'MIMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10982 => array(
        'm_icon' => '<i class="fad fa-cat play"></i>',
        'm_name' => 'MIMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12253 => array(
        'm_icon' => '<i class="fas fa-cow play" aria-hidden="true"></i>',
        'm_name' => 'MOMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12254 => array(
        'm_icon' => '<i class="far fa-cow play" aria-hidden="true"></i>',
        'm_name' => 'MOMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10970 => array(
        'm_icon' => '<i class="fad fa-cow play"></i>',
        'm_name' => 'MOMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12302 => array(
        'm_icon' => '<i class="fas fa-narwhal play"></i>',
        'm_name' => 'NARWHAL BOLD',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12303 => array(
        'm_icon' => '<i class="far fa-narwhal play"></i>',
        'm_name' => 'NARWHAL LIGHT',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12304 => array(
        'm_icon' => '<i class="fad fa-narwhal play"></i>',
        'm_name' => 'NARWHAL MIX',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12256 => array(
        'm_icon' => '<i class="fas fa-turtle play" aria-hidden="true"></i>',
        'm_name' => 'NINJA BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12255 => array(
        'm_icon' => '<i class="far fa-turtle play" aria-hidden="true"></i>',
        'm_name' => 'NINJA LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10972 => array(
        'm_icon' => '<i class="fad fa-turtle play"></i>',
        'm_name' => 'NINJA MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12306 => array(
        'm_icon' => '<i class="fas fa-pegasus play"></i>',
        'm_name' => 'PEGASUS BOLD',
        'm_desc' => '',
        'm_parents' => array(10985,12279),
    ),
    12307 => array(
        'm_icon' => '<i class="far fa-pegasus play"></i>',
        'm_name' => 'PEGASUS LIGHT',
        'm_desc' => '',
        'm_parents' => array(10985,12279),
    ),
    12305 => array(
        'm_icon' => '<i class="fad fa-pegasus play" aria-hidden="true"></i>',
        'm_name' => 'PEGASUS MIX',
        'm_desc' => '',
        'm_parents' => array(10985,12279),
    ),
    12257 => array(
        'm_icon' => '<i class="fas fa-pig play" aria-hidden="true"></i>',
        'm_name' => 'PIGGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12258 => array(
        'm_icon' => '<i class="far fa-pig play" aria-hidden="true"></i>',
        'm_name' => 'PIGGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10969 => array(
        'm_icon' => '<i class="fad fa-pig play"></i>',
        'm_name' => 'PIGGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12312 => array(
        'm_icon' => '<i class="fas fa-ram play"></i>',
        'm_name' => 'RAM BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12313 => array(
        'm_icon' => '<i class="far fa-ram play"></i>',
        'm_name' => 'RAM LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12311 => array(
        'm_icon' => '<i class="fad fa-ram play"></i>',
        'm_name' => 'RAM MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12260 => array(
        'm_icon' => '<i class="fas fa-rabbit play" aria-hidden="true"></i>',
        'm_name' => 'ROGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12259 => array(
        'm_icon' => '<i class="far fa-rabbit play" aria-hidden="true"></i>',
        'm_name' => 'ROGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10960 => array(
        'm_icon' => '<i class="fad fa-rabbit play"></i>',
        'm_name' => 'ROGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12277 => array(
        'm_icon' => '<i class="fas fa-deer-rudolph play" aria-hidden="true"></i>',
        'm_name' => 'RUDOLPH BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12276 => array(
        'm_icon' => '<i class="far fa-deer-rudolph play" aria-hidden="true"></i>',
        'm_name' => 'RUDOLPH LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12278 => array(
        'm_icon' => '<i class="fad fa-deer-rudolph play" aria-hidden="true"></i>',
        'm_name' => 'RUDOLPH MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12439 => array(
        'm_icon' => '<i class="fad fa-crow play" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12262 => array(
        'm_icon' => '<i class="far fa-crow play" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10981 => array(
        'm_icon' => '<i class="fad fa-crow play"></i>',
        'm_name' => 'RUSSEL MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12264 => array(
        'm_icon' => '<i class="fas fa-sheep play" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12263 => array(
        'm_icon' => '<i class="far fa-sheep play" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10968 => array(
        'm_icon' => '<i class="fad fa-sheep play"></i>',
        'm_name' => 'SHEEPY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12265 => array(
        'm_icon' => '<i class="fas fa-snake play" aria-hidden="true"></i>',
        'm_name' => 'SNAKY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12266 => array(
        'm_icon' => '<i class="far fa-snake play" aria-hidden="true"></i>',
        'm_name' => 'SNAKY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10974 => array(
        'm_icon' => '<i class="fad fa-snake play"></i>',
        'm_name' => 'SNAKY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12290 => array(
        'm_icon' => '<i class="fas fa-cat-space play"></i>',
        'm_name' => 'SPACE CAT BOLD',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12291 => array(
        'm_icon' => '<i class="far fa-cat-space play"></i>',
        'm_name' => 'SPACE CAT LIGHT',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12292 => array(
        'm_icon' => '<i class="fad fa-cat-space play"></i>',
        'm_name' => 'SPACE CAT MIX',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12268 => array(
        'm_icon' => '<i class="fas fa-spider play" aria-hidden="true"></i>',
        'm_name' => 'SPIDER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12267 => array(
        'm_icon' => '<i class="far fa-spider play" aria-hidden="true"></i>',
        'm_name' => 'SPIDER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12206 => array(
        'm_icon' => '<i class="fad fa-spider play"></i>',
        'm_name' => 'SPIDER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12269 => array(
        'm_icon' => '<i class="fas fa-squirrel play" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12270 => array(
        'm_icon' => '<i class="far fa-squirrel play" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10958 => array(
        'm_icon' => '<i class="fad fa-squirrel play"></i>',
        'm_name' => 'SQUIRRELY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12285 => array(
        'm_icon' => '<i class="fas fa-unicorn play"></i>',
        'm_name' => 'UNICORN BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12284 => array(
        'm_icon' => '<i class="far fa-unicorn play"></i>',
        'm_name' => 'UNICORN LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12283 => array(
        'm_icon' => '<i class="fad fa-unicorn play"></i>',
        'm_name' => 'UNICORN MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12272 => array(
        'm_icon' => '<i class="fas fa-whale play" aria-hidden="true"></i>',
        'm_name' => 'WHALE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12271 => array(
        'm_icon' => '<i class="far fa-whale play" aria-hidden="true"></i>',
        'm_name' => 'WHALE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12231 => array(
        'm_icon' => '<i class="fad fa-whale play"></i>',
        'm_name' => 'WHALE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
);

//PLAY COINS:
$config['en_ids_12274'] = array(4251);
$config['en_all_12274'] = array(
    4251 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY CREATED',
        'm_desc' => '',
        'm_parents' => array(12401,12274,12149,12141,10645,10593,4593),
    ),
);

//BLOG COINS:
$config['en_ids_12273'] = array(4983);
$config['en_all_12273'] = array(
    4983 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAYS',
        'm_desc' => '',
        'm_parents' => array(10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
);

//READ COMPLETION:
$config['en_ids_12229'] = array(6157,7489,12117,4559,6144,7485,7486,6997,6143,7492);
$config['en_all_12229'] = array(
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'PLAY FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'PLAY READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'PLAY TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
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
);

//READ TYPE GROUPS:
$config['en_ids_12228'] = array(12273,4983,12274,7704,12326,6255,12229,6146,7347,12227,12327);
$config['en_all_12228'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG COINS',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAYS',
        'm_desc' => '',
        'm_parents' => array(10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY COINS',
        'm_desc' => '',
        'm_parents' => array(12467,12228,4527,4758),
    ),
    7704 => array(
        'm_icon' => '<i class="far fa-hand-pointer read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWERED',
        'm_desc' => '',
        'm_parents' => array(12228,4527),
    ),
    12326 => array(
        'm_icon' => '<i class="far fa-sort read" aria-hidden="true"></i>',
        'm_name' => 'READ BLOG LINKS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ COINS',
        'm_desc' => 'Read coin generated for a successful read',
        'm_parents' => array(12467,10964,11089,12410,12365,6771,12228,4527),
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
        'm_icon' => '<i class="fas fa-list-ul read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => 'The top of reading list where readers start their reading experience',
        'm_parents' => array(11035,10989,11089,12365,6205,12228,4527),
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
$config['en_ids_12227'] = array(6157,7489,12117,4559,6144,7485,7486,6997,12336,12334,7495,6143,4235,7492,6140);
$config['en_all_12227'] = array(
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'PLAY FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'PLAY READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'PLAY TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
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
    7495 => array(
        'm_icon' => '<i class="far fa-play read" aria-hidden="true"></i>',
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
);

//PLAY TIMEZONE:
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

//PLAY GENDER:
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
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,4535,12149,12141,10638,10593,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY CREATED',
        'm_desc' => '',
        'm_parents' => array(12401,12274,12149,12141,10645,10593,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'PLAY FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'PLAY READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'PLAY TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//BLOG STATUS SEARCHABLE:
$config['en_ids_12138'] = array(12137);
$config['en_all_12138'] = array(
    12137 => array(
        'm_icon' => '<i class="fad fa-search" aria-hidden="true"></i>',
        'm_name' => 'BLOG SEARCHABLE',
        'm_desc' => '',
        'm_parents' => array(10648,12138,7356,7355,4737),
    ),
);

//BLOG TEXT INPUTS:
$config['en_ids_12112'] = array(4535,4356,4736,4358,4739,4735);
$config['en_all_12112'] = array(
    4535 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG',
        'm_desc' => '',
        'm_parents' => array(12499,12112,10876,4527,12155,2738),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch " aria-hidden="true"></i>',
        'm_name' => 'BLOG READ TIME',
        'm_desc' => '',
        'm_parents' => array(12112,12420,10888,10650,6232,6201),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 " aria-hidden="true"></i>',
        'm_name' => 'BLOG TITLE',
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
        'm_icon' => '<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Amazon_Alexa_play_logo.svg/1024px-Amazon_Alexa_play_logo.svg.png">',
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
        'm_icon' => '<i class="fab fa-telegram play"></i>',
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

//BLOG DROPDOWNS:
$config['en_ids_12079'] = array(4737,4486,7585);
$config['en_all_12079'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link blog" aria-hidden="true"></i>',
        'm_name' => 'TREE LINKS',
        'm_desc' => '',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random blog" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(11018,12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
);

//BLOG NOTE STATUS:
$config['en_ids_12012'] = array(6176,6173);
$config['en_all_12012'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="fad fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12012,10686,10678,10673,6186),
    ),
);

//PLAY LAYOUT:
$config['en_ids_11089'] = array(12412,11030,10573,12273,12419,4231,4601,7545,7347,6255,6146,11029,4997);
$config['en_all_11089'] = array(
    12412 => array(
        'm_icon' => '<i class="fad fa-browser play" aria-hidden="true"></i>',
        'm_name' => 'PLAY HEADER',
        'm_desc' => '',
        'm_parents' => array(11089),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-user-circle play" aria-hidden="true"></i>',
        'm_name' => 'PROFILES',
        'm_desc' => '',
        'm_parents' => array(12574,11089,11028),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-list-ul blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG COINS',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag play" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAY TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
    7347 => array(
        'm_icon' => '<i class="fas fa-list-ul read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10989,11089,12365,6205,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ COINS',
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
        'm_icon' => '<i class="fad fa-sitemap play" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIOS',
        'm_desc' => '',
        'm_parents' => array(12574,12571,11089,11028),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-edit play" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIOS EDITOR',
        'm_desc' => '',
        'm_parents' => array(11029,12574,10967,11089,4758,4506,4527),
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
$config['en_ids_11081'] = array(4369,4429,4368,4366,4371,4364,4593);
$config['en_all_11081'] = array(
    4369 => array(
        'm_icon' => '<i class="fas fa-hashtag" aria-hidden="true"></i>',
        'm_name' => 'CHILD BLOG',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'CHILD PLAY',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-hashtag" aria-hidden="true"></i>',
        'm_name' => 'PARENT BLOG',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'PARENT PLAY',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link" aria-hidden="true"></i>',
        'm_name' => 'PARENT READ',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    4364 => array(
        'm_icon' => '<i class="far fa-user-edit read" aria-hidden="true"></i>',
        'm_name' => 'PLAYER',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug read" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//PLATFORM VARIABLES:
$config['en_ids_6232'] = array(6202,6159,4356,4737,4736,4486,7585,5008,6207,6203,6208,6168,6283,6228,6165,6162,6170,6161,6169,6167,6198,6160,6172,6197,6177,4369,4429,7694,4367,4358,4372,6103,4368,4366,4371,4364,4370,6186,4362,4593,4739,4735);
$config['en_all_6232'] = array(
    6202 => array(
        'm_icon' => '<i class="fas fa-plus-circle "></i>',
        'm_name' => 'BLOG ID',
        'm_desc' => 'in_id',
        'm_parents' => array(6232,6215,6201),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda " aria-hidden="true"></i>',
        'm_name' => 'BLOG METADATA',
        'm_desc' => 'in_metadata',
        'm_parents' => array(11049,6232,6201,6195),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch " aria-hidden="true"></i>',
        'm_name' => 'BLOG READ TIME',
        'm_desc' => 'in_read_time',
        'm_parents' => array(12112,12420,10888,10650,6232,6201),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS',
        'm_desc' => 'in_status_play_id',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 " aria-hidden="true"></i>',
        'm_name' => 'BLOG TITLE',
        'm_desc' => 'in_title',
        'm_parents' => array(10990,12112,11071,10644,6232,6201),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG TREE LINKS',
        'm_desc' => 'ln_type_play_id',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE',
        'm_desc' => 'in_type_play_id',
        'm_parents' => array(11018,12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    5008 => array(
        'm_icon' => '<i class="fad fa-tools " aria-hidden="true"></i>',
        'm_name' => 'BLOG VERB',
        'm_desc' => 'in_verb_play_id',
        'm_parents' => array(6232,6201,6768,4736,7777,6160),
    ),
    6207 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'ENTITY METADATA ALGOLIA ID',
        'm_desc' => 'en__algolia_id',
        'm_parents' => array(6232,6215,6172),
    ),
    6203 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'FACEBOOK ATTACHMENT ID',
        'm_desc' => 'fb_att_id',
        'm_parents' => array(6232,6215,2793,6103),
    ),
    6208 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'INTENT METADATA ALGOLIA ID',
        'm_desc' => 'in__algolia_id',
        'm_parents' => array(6232,6215,3323,6159),
    ),
    6168 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'INTENT METADATA COMMON STEPS',
        'm_desc' => 'in__metadata_common_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6283 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'INTENT METADATA EXPANSION CONDITIONAL',
        'm_desc' => 'in__metadata_expansion_conditional',
        'm_parents' => array(6214,6232,6159),
    ),
    6228 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'INTENT METADATA EXPANSION STEPS',
        'm_desc' => 'in__metadata_expansion_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6165 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'INTENT METADATA EXPERTS',
        'm_desc' => 'in__metadata_experts',
        'm_parents' => array(6232,6214,6159),
    ),
    6162 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'INTENT METADATA MAXIMUM SECONDS',
        'm_desc' => 'in__metadata_max_seconds',
        'm_parents' => array(4739,6232,6214,4356,6159),
    ),
    6170 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'INTENT METADATA MAXIMUM STEPS',
        'm_desc' => 'in__metadata_max_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6161 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'INTENT METADATA MINIMUM SECONDS',
        'm_desc' => 'in__metadata_min_seconds',
        'm_parents' => array(4735,6232,6214,4356,6159),
    ),
    6169 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'INTENT METADATA MINIMUM STEPS',
        'm_desc' => 'in__metadata_min_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6167 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'INTENT METADATA SOURCES',
        'm_desc' => 'in__metadata_sources',
        'm_parents' => array(6232,6214,6159),
    ),
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle play"></i>',
        'm_name' => 'PLAY ICON',
        'm_desc' => 'en_icon',
        'm_parents' => array(10653,5943,10625,6232,6206),
    ),
    6160 => array(
        'm_icon' => '<i class="fas fa-user-circle play"></i>',
        'm_name' => 'PLAY ID',
        'm_desc' => 'en_id',
        'm_parents' => array(6232,6215,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda play"></i>',
        'm_name' => 'PLAY METADATA',
        'm_desc' => 'en_metadata',
        'm_parents' => array(6232,3323,6206,6195),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint play" aria-hidden="true"></i>',
        'm_name' => 'PLAY NICKNAME',
        'm_desc' => 'en_name',
        'm_parents' => array(4269,12412,12232,6225,11072,10646,5000,4998,4999,6232,6206),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h play" aria-hidden="true"></i>',
        'm_name' => 'PLAY STATUS',
        'm_desc' => 'en_status_play_id',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-hashtag" aria-hidden="true"></i>',
        'm_name' => 'READ CHILD BLOG',
        'm_desc' => 'ln_child_blog_id',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'READ CHILD PLAY',
        'm_desc' => 'ln_child_play_id',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    7694 => array(
        'm_icon' => '<i class="fas fa-project-diagram"></i>',
        'm_name' => 'READ EXTERNAL ID',
        'm_desc' => 'ln_external_id',
        'm_parents' => array(6215,6232,4341),
    ),
    4367 => array(
        'm_icon' => '<i class="fas fa-info-circle"></i>',
        'm_name' => 'READ ID',
        'm_desc' => 'ln_id',
        'm_parents' => array(6232,6215,4341),
    ),
    4358 => array(
        'm_icon' => '<i class="fad fa-comment-alt-check" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => 'tr__assessment_points',
        'm_parents' => array(12420,10985,12112,10663,6103,6410,6232),
    ),
    4372 => array(
        'm_icon' => '<i class="fas fa-sticky-note"></i>',
        'm_name' => 'READ MESSAGE',
        'm_desc' => 'ln_content',
        'm_parents' => array(7578,10679,10657,5001,6232,4341),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'READ METADATA',
        'm_desc' => 'ln_metadata',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-hashtag" aria-hidden="true"></i>',
        'm_name' => 'READ PARENT BLOG',
        'm_desc' => 'ln_parent_blog_id',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'READ PARENT PLAY',
        'm_desc' => 'ln_parent_play_id',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link" aria-hidden="true"></i>',
        'm_name' => 'READ PARENT READ',
        'm_desc' => 'ln_parent_read_id',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    4364 => array(
        'm_icon' => '<i class="far fa-user-edit read" aria-hidden="true"></i>',
        'm_name' => 'READ PLAYER',
        'm_desc' => 'ln_player_play_id',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fas fa-sort"></i>',
        'm_name' => 'READ RANK',
        'm_desc' => 'ln_order',
        'm_parents' => array(10676,10675,6232,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h read" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => 'ln_status_play_id',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="far fa-clock" aria-hidden="true"></i>',
        'm_name' => 'READ TIMESTAMP',
        'm_desc' => 'ln_timestamp',
        'm_parents' => array(6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug read" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE',
        'm_desc' => 'ln_type_play_id',
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
        'm_icon' => '<i class="fad fa-volume-up play"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'pcm|wav|aiff|mp3|aac|ogg|wma|flac|alac|m4a|m4b|m4p',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf play" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'pdf|pdc|doc|docx|tex|txt|7z|rar|zip|csv|sql|tar|xml|exe',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image play" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'jpeg|jpg|png|gif|tiff|bmp|img|svg|ico|webp',
        'm_parents' => array(12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser play" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => 'htm|html',
        'm_parents' => array(11080,4535,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video play"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'mp4|m4v|m4p|avi|mov|flv|f4v|f4p|f4a|f4b|wmv|webm|mkv|vob|ogv|ogg|3gp|mpg|mpeg|m2v',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
);

//MESSENGER MEDIA CODES:
$config['en_ids_11059'] = array(4259,4261,4260,4258);
$config['en_all_11059'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up play"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'audio',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf play" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'file',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image play" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'image',
        'm_parents' => array(12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video play"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'video',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
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
$config['en_ids_6404'] = array(12176,12156,11071,12210,12130,12355,11077,11074,12124,11076,11075,11064,11986,11065,11063,11079,11060,12363,11073,11066,11072,12232,11057,11056,12331,12113,12427,12088,11061,11162,11163,12209,12208,12568,12565);
$config['en_all_6404'] = array(
    12176 => array(
        'm_icon' => '<i class="fad fa-clock blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG DEFAULT TIME SECONDS',
        'm_desc' => '30',
        'm_parents' => array(6404),
    ),
    12156 => array(
        'm_icon' => '<i class="fas fa-star blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NORTH STAR',
        'm_desc' => '7766',
        'm_parents' => array(6404),
    ),
    11071 => array(
        'm_icon' => '<i class="fad fa-ruler-horizontal blog"></i>',
        'm_name' => 'BLOG TITLE MAX LENGTH',
        'm_desc' => '89',
        'm_parents' => array(6404),
    ),
    12210 => array(
        'm_icon' => '',
        'm_name' => 'COINS REFRESH MILLISECONDS BLOGGER',
        'm_desc' => '4181',
        'm_parents' => array(6404),
    ),
    12130 => array(
        'm_icon' => '',
        'm_name' => 'COINS REFRESH MILLISECONDS READER',
        'm_desc' => '121393',
        'm_parents' => array(6404),
    ),
    12355 => array(
        'm_icon' => '',
        'm_name' => 'DATE FORMAT FULL & WEEKDAY',
        'm_desc' => 'D M j G:i:s T Y',
        'm_parents' => array(6404),
    ),
    11077 => array(
        'm_icon' => '',
        'm_name' => 'FACEBOOK DEFAULT GRAPH VERSION',
        'm_desc' => 'v3.2',
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
    11079 => array(
        'm_icon' => '',
        'm_name' => 'MENCH PLATFORM TIMEZONE',
        'm_desc' => 'America/Los_Angeles',
        'm_parents' => array(6404),
    ),
    11060 => array(
        'm_icon' => '',
        'm_name' => 'MENCH PLATFORM VERSION',
        'm_desc' => '1.31',
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
    11066 => array(
        'm_icon' => '',
        'm_name' => 'PASSWORD MIN CHARACTERS',
        'm_desc' => '6',
        'm_parents' => array(6404),
    ),
    11072 => array(
        'm_icon' => '',
        'm_name' => 'PLAY NAME MAX LENGTH',
        'm_desc' => '233',
        'm_parents' => array(6404),
    ),
    12232 => array(
        'm_icon' => '',
        'm_name' => 'PLAY NAME MIN LENGTH',
        'm_desc' => '2',
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
        'm_name' => 'READ TIME MAX',
        'm_desc' => '7200',
        'm_parents' => array(4356,6404),
    ),
    12427 => array(
        'm_icon' => '',
        'm_name' => 'READ TIME MIN',
        'm_desc' => '3',
        'm_parents' => array(6404,4356),
    ),
    12088 => array(
        'm_icon' => '',
        'm_name' => 'SHOW TEXT COUNTER THRESHOLD',
        'm_desc' => '0.8',
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
    12209 => array(
        'm_icon' => '',
        'm_name' => 'WEEKS PER MONTH',
        'm_desc' => '4.34524',
        'm_parents' => array(6404),
    ),
    12208 => array(
        'm_icon' => '',
        'm_name' => 'WEEKS PER YEAR',
        'm_desc' => '52.1775',
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
$config['en_ids_11054'] = array(4737,7356,7355,6201,4486,7585,2738,6404,6177,7357,6186);
$config['en_all_11054'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'BLOG STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    6201 => array(
        'm_icon' => '<i class="far fa-table blog"></i>',
        'm_name' => 'BLOG TABLE',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7735,4535),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG TREE LINKS',
        'm_desc' => '',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE',
        'm_desc' => '',
        'm_parents' => array(11018,12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    2738 => array(
        'm_icon' => '<img src="/img/mench.png" class="mench-spin no-radius">',
        'm_name' => 'MENCH',
        'm_desc' => '',
        'm_parents' => array(12497,11054,12041,3303,7524,3325,3326,3324,4527,1,7312,2750),
    ),
    6404 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'PLATFORM CONFIG VARIABLES',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7254,6403),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h play" aria-hidden="true"></i>',
        'm_name' => 'PLAY STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    7357 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PLAY STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(12572,11054,4527),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h read" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
);

//BLOG ADMIN MENU:
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
        'm_desc' => '/blog/in_review_metadata/',
        'm_parents' => array(11047),
    ),
    11050 => array(
        'm_icon' => '<img src="https://partners.algolia.com/images/logos/algolia-logo-badge.svg">',
        'm_name' => 'UPDATE ALGOLIA',
        'm_desc' => '/blog/cron__sync_algolia/in/',
        'm_parents' => array(7279,11047),
    ),
    11048 => array(
        'm_icon' => '<i class="far fa-magic" aria-hidden="true"></i>',
        'm_name' => 'UPDATE CACHE',
        'm_desc' => '/blog/cron__sync_extra_insights/',
        'm_parents' => array(11047),
    ),
);

//MENCH NAVIGATION MENU:
$config['en_ids_11035'] = array(6287,10573,12561,4430,11068,7291,12467,11999,6225,12581,12211,12275,12205,6415,7347,7256,4269,10957,7540,12437);
$config['en_all_11035'] = array(
    6287 => array(
        'm_icon' => '<i class="fad fa-user-shield play" aria-hidden="true"></i>',
        'm_name' => 'ADMIN PANEL',
        'm_desc' => '',
        'm_parents' => array(12500,10985,10876,11035,4527),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-list-ul blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    12561 => array(
        'm_icon' => '<i class="fas fa-bars blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LIST',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-horse-head play" aria-hidden="true"></i>',
        'm_name' => 'HUMAN PLAYERS',
        'm_desc' => '',
        'm_parents' => array(12437,11035,10573,4983,6827,4426),
    ),
    11068 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'LOGIN LINK',
        'm_desc' => '',
        'm_parents' => array(11035,11065),
    ),
    7291 => array(
        'm_icon' => '<i class="fad fa-power-off play" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => '',
        'm_parents' => array(10876,12500,11035),
    ),
    12467 => array(
        'm_icon' => '<i class="fas fa-circle" aria-hidden="true"></i>',
        'm_name' => 'MENCH COINS',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    11999 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(10876,6771,11035),
    ),
    6225 => array(
        'm_icon' => '<i class="fad fa-user-cog play" aria-hidden="true"></i>',
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
    12275 => array(
        'm_icon' => '<i class="fas fa-cog play" aria-hidden="true"></i>',
        'm_name' => 'PLAY MODIFY',
        'm_desc' => '',
        'm_parents' => array(12412,11035),
    ),
    12205 => array(
        'm_icon' => '<i class="fad fa-user-circle play" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fas fa-list-ul read" aria-hidden="true"></i>',
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
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt play" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fad fa-trophy play" aria-hidden="true"></i>',
        'm_name' => 'TOP PLAYERS',
        'm_desc' => '',
        'm_parents' => array(12489,11035,11986),
    ),
);

//PLAYS LINKS DIRECTION:
$config['en_ids_11028'] = array(11030,11029);
$config['en_all_11028'] = array(
    11030 => array(
        'm_icon' => '<i class="fas fa-user-circle play" aria-hidden="true"></i>',
        'm_name' => 'PROFILES',
        'm_desc' => 'Describe PLAYER. Where it comes from. It\'s origin.',
        'm_parents' => array(12574,11089,11028),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap play" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIOS',
        'm_desc' => 'What the PLAYER chooses to focus on. It\'s work. It\'s responsibility.',
        'm_parents' => array(12574,12571,11089,11028),
    ),
);

//BLOG FOOTER:
$config['en_ids_11018'] = array(11020,7585);
$config['en_all_11018'] = array(
    11020 => array(
        'm_icon' => '<i class="fas fa-step-forward blog" aria-hidden="true"></i>',
        'm_name' => 'NEXT',
        'm_desc' => '',
        'm_parents' => array(12413,11025,11018),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random blog" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(11018,12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
);

//BLOG LAYOUT:
$config['en_ids_11021'] = array(12365,11018);
$config['en_all_11021'] = array(
    12365 => array(
        'm_icon' => '<i class="fad fa-square blog" aria-hidden="true"></i>',
        'm_name' => 'BODY',
        'm_desc' => '',
        'm_parents' => array(4527,11021),
    ),
    11018 => array(
        'm_icon' => '<i class="fad fa-browser blog rotate180" aria-hidden="true"></i>',
        'm_name' => 'FOOTER',
        'm_desc' => '',
        'm_parents' => array(4527,11021),
    ),
);

//BLOG PREVIOUS SECTION:
$config['en_ids_10990'] = array(11019,4737,4736);
$config['en_all_10990'] = array(
    11019 => array(
        'm_icon' => '<i class="fas fa-step-backward blog" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS',
        'm_desc' => '',
        'm_parents' => array(12365,12413,10990,11025),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h blog" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fad fa-pen blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG PEN',
        'm_desc' => 'Basic Interactive Blogging',
        'm_parents' => array(10876,10957),
    ),
    10984 => array(
        'm_icon' => '<i class="fad fa-paint-brush-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG BRUSH',
        'm_desc' => 'Collaborative Interactive Blogging',
        'm_parents' => array(10876,10983,10957),
    ),
    10985 => array(
        'm_icon' => '<i class="fad fa-magic blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG WAND',
        'm_desc' => 'Advance Interactive Blogging',
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
        'm_icon' => '<i class="fad fa-gamepad play" aria-hidden="true"></i>',
        'm_name' => 'PLAY JOYSTICK',
        'm_desc' => 'Reference Players',
        'm_parents' => array(10876,10964,10957),
    ),
    10967 => array(
        'm_icon' => '<i class="fad fa-turntable play" aria-hidden="true"></i>',
        'm_name' => 'PLAY TURNTABLE',
        'm_desc' => 'Organize Players',
        'm_parents' => array(10876,10985,10957),
    ),
    10986 => array(
        'm_icon' => '<i class="fad fa-dice play" aria-hidden="true"></i>',
        'm_name' => 'PLAY DICE',
        'm_desc' => 'Advance Play Tools',
        'm_parents' => array(10876,10967,10957),
    ),
);

//PLAY AVATAR BASIC:
$config['en_ids_10956'] = array(12286,12287,12288,12234,12233,10965,12236,12235,10979,12295,12294,12293,12300,12301,12299,12237,12238,10978,12314,12315,12316,12240,12239,10963,12241,12242,12207,12244,12243,10966,12245,12246,10976,12248,12247,10962,12249,12250,10975,12252,12251,10982,12253,12254,10970,12256,12255,10972,12257,12258,10969,12260,12259,10960,12439,12262,10981,12264,12263,10968,12265,12266,10974,12268,12267,12206,12269,12270,10958,12272,12271,12231);
$config['en_all_10956'] = array(
    12286 => array(
        'm_icon' => '<i class="fas fa-bat play" aria-hidden="true"></i>',
        'm_name' => 'BAT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12287 => array(
        'm_icon' => '<i class="far fa-bat play" aria-hidden="true"></i>',
        'm_name' => 'BAT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12288 => array(
        'm_icon' => '<i class="fad fa-bat play" aria-hidden="true"></i>',
        'm_name' => 'BAT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12234 => array(
        'm_icon' => '<i class="fas fa-dog play"></i>',
        'm_name' => 'DOGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12233 => array(
        'm_icon' => '<i class="far fa-dog play"></i>',
        'm_name' => 'DOGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10965 => array(
        'm_icon' => '<i class="fad fa-dog play"></i>',
        'm_name' => 'DOGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12236 => array(
        'm_icon' => '<i class="fas fa-duck play" aria-hidden="true"></i>',
        'm_name' => 'DONALD BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12235 => array(
        'm_icon' => '<i class="far fa-duck play" aria-hidden="true"></i>',
        'm_name' => 'DONALD LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10979 => array(
        'm_icon' => '<i class="fad fa-duck play"></i>',
        'm_name' => 'DONALD MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12295 => array(
        'm_icon' => '<i class="fas fa-dove play" aria-hidden="true"></i>',
        'm_name' => 'DOVE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12294 => array(
        'm_icon' => '<i class="far fa-dove play" aria-hidden="true"></i>',
        'm_name' => 'DOVE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12293 => array(
        'm_icon' => '<i class="fad fa-dove play" aria-hidden="true"></i>',
        'm_name' => 'DOVE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12300 => array(
        'm_icon' => '<i class="fas fa-elephant play" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12301 => array(
        'm_icon' => '<i class="far fa-elephant play" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12299 => array(
        'm_icon' => '<i class="fad fa-elephant play" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12237 => array(
        'm_icon' => '<i class="fas fa-fish play" aria-hidden="true"></i>',
        'm_name' => 'FISHY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12238 => array(
        'm_icon' => '<i class="far fa-fish play" aria-hidden="true"></i>',
        'm_name' => 'FISHY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10978 => array(
        'm_icon' => '<i class="fad fa-fish play"></i>',
        'm_name' => 'FISHY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12314 => array(
        'm_icon' => '<i class="fas fa-frog play" aria-hidden="true"></i>',
        'm_name' => 'FROG BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12315 => array(
        'm_icon' => '<i class="far fa-frog play" aria-hidden="true"></i>',
        'm_name' => 'FROG LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12316 => array(
        'm_icon' => '<i class="fad fa-frog play" aria-hidden="true"></i>',
        'm_name' => 'FROG MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12240 => array(
        'm_icon' => '<i class="fas fa-hippo play" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12239 => array(
        'm_icon' => '<i class="far fa-hippo play" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10963 => array(
        'm_icon' => '<i class="fad fa-hippo play"></i>',
        'm_name' => 'HIPPOY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12241 => array(
        'm_icon' => '<i class="fas fa-badger-honey play" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12242 => array(
        'm_icon' => '<i class="far fa-badger-honey play" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12207 => array(
        'm_icon' => '<i class="fad fa-badger-honey play"></i>',
        'm_name' => 'HONEY BADGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12244 => array(
        'm_icon' => '<i class="fas fa-deer play" aria-hidden="true"></i>',
        'm_name' => 'HONEY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12243 => array(
        'm_icon' => '<i class="far fa-deer play" aria-hidden="true"></i>',
        'm_name' => 'HONEY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10966 => array(
        'm_icon' => '<i class="fad fa-deer play"></i>',
        'm_name' => 'HONEY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12245 => array(
        'm_icon' => '<i class="fas fa-horse play" aria-hidden="true"></i>',
        'm_name' => 'HORSY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12246 => array(
        'm_icon' => '<i class="far fa-horse play" aria-hidden="true"></i>',
        'm_name' => 'HORSY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10976 => array(
        'm_icon' => '<i class="fad fa-horse play"></i>',
        'm_name' => 'HORSY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12248 => array(
        'm_icon' => '<i class="fas fa-monkey play" aria-hidden="true"></i>',
        'm_name' => 'HUMAN BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12247 => array(
        'm_icon' => '<i class="far fa-monkey play" aria-hidden="true"></i>',
        'm_name' => 'HUMAN LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10962 => array(
        'm_icon' => '<i class="fad fa-monkey play"></i>',
        'm_name' => 'HUMAN MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12249 => array(
        'm_icon' => '<i class="fas fa-kiwi-bird play" aria-hidden="true"></i>',
        'm_name' => 'KIWI BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12250 => array(
        'm_icon' => '<i class="far fa-kiwi-bird play" aria-hidden="true"></i>',
        'm_name' => 'KIWI LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10975 => array(
        'm_icon' => '<i class="fad fa-kiwi-bird play"></i>',
        'm_name' => 'KIWI MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12252 => array(
        'm_icon' => '<i class="fas fa-cat play" aria-hidden="true"></i>',
        'm_name' => 'MIMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12251 => array(
        'm_icon' => '<i class="far fa-cat play" aria-hidden="true"></i>',
        'm_name' => 'MIMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10982 => array(
        'm_icon' => '<i class="fad fa-cat play"></i>',
        'm_name' => 'MIMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12253 => array(
        'm_icon' => '<i class="fas fa-cow play" aria-hidden="true"></i>',
        'm_name' => 'MOMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12254 => array(
        'm_icon' => '<i class="far fa-cow play" aria-hidden="true"></i>',
        'm_name' => 'MOMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10970 => array(
        'm_icon' => '<i class="fad fa-cow play"></i>',
        'm_name' => 'MOMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12256 => array(
        'm_icon' => '<i class="fas fa-turtle play" aria-hidden="true"></i>',
        'm_name' => 'NINJA BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12255 => array(
        'm_icon' => '<i class="far fa-turtle play" aria-hidden="true"></i>',
        'm_name' => 'NINJA LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10972 => array(
        'm_icon' => '<i class="fad fa-turtle play"></i>',
        'm_name' => 'NINJA MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12257 => array(
        'm_icon' => '<i class="fas fa-pig play" aria-hidden="true"></i>',
        'm_name' => 'PIGGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12258 => array(
        'm_icon' => '<i class="far fa-pig play" aria-hidden="true"></i>',
        'm_name' => 'PIGGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10969 => array(
        'm_icon' => '<i class="fad fa-pig play"></i>',
        'm_name' => 'PIGGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12260 => array(
        'm_icon' => '<i class="fas fa-rabbit play" aria-hidden="true"></i>',
        'm_name' => 'ROGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12259 => array(
        'm_icon' => '<i class="far fa-rabbit play" aria-hidden="true"></i>',
        'm_name' => 'ROGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10960 => array(
        'm_icon' => '<i class="fad fa-rabbit play"></i>',
        'm_name' => 'ROGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12439 => array(
        'm_icon' => '<i class="fad fa-crow play" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12262 => array(
        'm_icon' => '<i class="far fa-crow play" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10981 => array(
        'm_icon' => '<i class="fad fa-crow play"></i>',
        'm_name' => 'RUSSEL MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12264 => array(
        'm_icon' => '<i class="fas fa-sheep play" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12263 => array(
        'm_icon' => '<i class="far fa-sheep play" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10968 => array(
        'm_icon' => '<i class="fad fa-sheep play"></i>',
        'm_name' => 'SHEEPY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12265 => array(
        'm_icon' => '<i class="fas fa-snake play" aria-hidden="true"></i>',
        'm_name' => 'SNAKY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12266 => array(
        'm_icon' => '<i class="far fa-snake play" aria-hidden="true"></i>',
        'm_name' => 'SNAKY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10974 => array(
        'm_icon' => '<i class="fad fa-snake play"></i>',
        'm_name' => 'SNAKY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12268 => array(
        'm_icon' => '<i class="fas fa-spider play" aria-hidden="true"></i>',
        'm_name' => 'SPIDER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12267 => array(
        'm_icon' => '<i class="far fa-spider play" aria-hidden="true"></i>',
        'm_name' => 'SPIDER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12206 => array(
        'm_icon' => '<i class="fad fa-spider play"></i>',
        'm_name' => 'SPIDER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12269 => array(
        'm_icon' => '<i class="fas fa-squirrel play" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12270 => array(
        'm_icon' => '<i class="far fa-squirrel play" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10958 => array(
        'm_icon' => '<i class="fad fa-squirrel play"></i>',
        'm_name' => 'SQUIRRELY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12272 => array(
        'm_icon' => '<i class="fas fa-whale play" aria-hidden="true"></i>',
        'm_name' => 'WHALE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12271 => array(
        'm_icon' => '<i class="far fa-whale play" aria-hidden="true"></i>',
        'm_name' => 'WHALE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12231 => array(
        'm_icon' => '<i class="fad fa-whale play"></i>',
        'm_name' => 'WHALE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
);

//MENCH:
$config['en_ids_2738'] = array(4536,4535,6205);
$config['en_all_2738'] = array(
    4536 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY',
        'm_desc' => '',
        'm_parents' => array(12499,10876,4527,5008,12155,2738),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG',
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
$config['en_ids_10692'] = array(4369,4429,4368,4366,4371);
$config['en_all_10692'] = array(
    4369 => array(
        'm_icon' => '<i class="fas fa-hashtag" aria-hidden="true"></i>',
        'm_name' => 'CHILD BLOG',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'CHILD PLAY',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-hashtag" aria-hidden="true"></i>',
        'm_name' => 'PARENT BLOG',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'PARENT PLAY',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link" aria-hidden="true"></i>',
        'm_name' => 'PARENT READ',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
);

//PLATFORM MEMORY:
$config['en_ids_4527'] = array(6287,4535,11047,6192,12365,12273,12079,11018,11021,4229,4485,12012,6193,4983,10990,7302,4737,7356,7355,12138,12400,6201,12112,12420,4486,12413,7585,10602,12330,12324,7309,7712,7751,6150,3000,10627,12359,12523,10717,12571,12574,11080,2738,12105,12502,10876,11035,12524,11059,11058,6225,12501,6404,4527,11054,6232,4536,10956,12279,12410,12274,6194,3290,6827,12421,11089,4592,12403,4426,12500,7555,4986,7551,11028,4537,6177,7358,7357,12575,12401,6206,3289,12577,4997,4755,6205,11081,7704,12326,6255,12229,6345,4280,4277,6102,12446,6146,7347,6103,10692,12227,5967,7304,6186,7360,7364,7359,4341,4593,10593,12228,12141,12327,10658,4269,6204,10957,12322,12321,6805);
$config['en_all_4527'] = array(
    6287 => array(
        'm_icon' => '<i class="fad fa-user-shield play" aria-hidden="true"></i>',
        'm_name' => 'ADMIN PANEL',
        'm_desc' => '',
        'm_parents' => array(12500,10985,10876,11035,4527),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG',
        'm_desc' => '',
        'm_parents' => array(12499,12112,10876,4527,12155,2738),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down" aria-hidden="true"></i>',
        'm_name' => 'BLOG ADMIN MENU',
        'm_desc' => '',
        'm_parents' => array(10984,12365,4527,11040),
    ),
    6192 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'BLOG AND',
        'm_desc' => '',
        'm_parents' => array(4527,10602),
    ),
    12365 => array(
        'm_icon' => '<i class="fad fa-square blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG BODY',
        'm_desc' => '',
        'm_parents' => array(4527,11021),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG COINS',
        'm_desc' => '',
        'm_parents' => array(12571,12467,12321,12410,11089,12228,4527,6768),
    ),
    12079 => array(
        'm_icon' => '<i class="fas fa-caret-down" aria-hidden="true"></i>',
        'm_name' => 'BLOG DROPDOWNS',
        'm_desc' => '',
        'm_parents' => array(6768,4527),
    ),
    11018 => array(
        'm_icon' => '<i class="fad fa-browser blog rotate180" aria-hidden="true"></i>',
        'm_name' => 'BLOG FOOTER',
        'm_desc' => '',
        'm_parents' => array(4527,11021),
    ),
    11021 => array(
        'm_icon' => '<i class="fad fa-crop-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4527,4535),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(4535,4527,6410,6283,4593,4486),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES',
        'm_desc' => '',
        'm_parents' => array(12408,4535,4527),
    ),
    12012 => array(
        'm_icon' => '<i class="far fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTE STATUS',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    6193 => array(
        'm_icon' => '<i class="fad fa-code-branch rotate180 " aria-hidden="true"></i>',
        'm_name' => 'BLOG OR',
        'm_desc' => '',
        'm_parents' => array(10602,4527),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAYS',
        'm_desc' => '',
        'm_parents' => array(10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    10990 => array(
        'm_icon' => '<i class="fad fa-browser blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG PREVIOUS SECTION',
        'm_desc' => '',
        'm_parents' => array(4527),
    ),
    7302 => array(
        'm_icon' => '<i class="far fa-chart-bar blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATS',
        'm_desc' => '',
        'm_parents' => array(4527,4535),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'BLOG STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    12138 => array(
        'm_icon' => '<i class="fad fa-search blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS SEARCHABLE',
        'm_desc' => '',
        'm_parents' => array(4527,10891),
    ),
    12400 => array(
        'm_icon' => '<i class="fad fa-sync blog"></i>',
        'm_name' => 'BLOG SYNC STATUS',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    6201 => array(
        'm_icon' => '<i class="far fa-table blog"></i>',
        'm_name' => 'BLOG TABLE',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7735,4535),
    ),
    12112 => array(
        'm_icon' => '<i class="fas fa-text" aria-hidden="true"></i>',
        'm_name' => 'BLOG TEXT INPUTS',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    12420 => array(
        'm_icon' => '<i class="far fa-user-circle" aria-hidden="true"></i>',
        'm_name' => 'BLOG TEXT INPUT SHOW ICON',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG TREE LINKS',
        'm_desc' => '',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    12413 => array(
        'm_icon' => '<i class="fad fa-circle blog"></i>',
        'm_name' => 'BLOG TREE NODES',
        'm_desc' => '',
        'm_parents' => array(4527,11025),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE',
        'm_desc' => '',
        'm_parents' => array(11018,12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE GROUPS',
        'm_desc' => '',
        'm_parents' => array(10893,6204,7302,4527),
    ),
    12330 => array(
        'm_icon' => '<i class="fas fa-bolt"></i>',
        'm_name' => 'BLOG TYPE INSTANTLY DONE',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    12324 => array(
        'm_icon' => '<i class="fad fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE MANUAL INPUT',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    7309 => array(
        'm_icon' => '<i class="far fa-cubes"></i>',
        'm_name' => 'BLOG TYPE REQUIREMENT',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    7712 => array(
        'm_icon' => '<i class="far fa-question-circle" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE SELECT',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    7751 => array(
        'm_icon' => '<i class="far fa-upload" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    6150 => array(
        'm_icon' => '<i class="far fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARK REMOVED',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    3000 => array(
        'm_icon' => '<i class="fad fa-file-certificate play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT CONTENT',
        'm_desc' => '',
        'm_parents' => array(4527),
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
    12523 => array(
        'm_icon' => '<i class="fad fa-portrait play"></i>',
        'm_name' => 'INHERIT PARENT ICON',
        'm_desc' => '',
        'm_parents' => array(7274,4527,4758),
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
    12502 => array(
        'm_icon' => '<i class="fad fa-file-code"></i>',
        'm_name' => 'MENCH JAVASCRIPT FUNCTIONS',
        'm_desc' => '',
        'm_parents' => array(4527,7305),
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
    12524 => array(
        'm_icon' => '<i class="fad fa-film-alt play" aria-hidden="true"></i>',
        'm_name' => 'MESSAGE VISUAL MEDIA',
        'm_desc' => '',
        'm_parents' => array(4758,4527),
    ),
    11059 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger play"></i>',
        'm_name' => 'MESSENGER MEDIA CODES',
        'm_desc' => '',
        'm_parents' => array(6196,4527,7254),
    ),
    11058 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger play"></i>',
        'm_name' => 'MESSENGER NOTIFICATION CODES',
        'm_desc' => '',
        'm_parents' => array(7254,6196,4527),
    ),
    6225 => array(
        'm_icon' => '<i class="fad fa-user-cog play" aria-hidden="true"></i>',
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
    4536 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY',
        'm_desc' => '',
        'm_parents' => array(12499,10876,4527,5008,12155,2738),
    ),
    10956 => array(
        'm_icon' => '<i class="fad fa-paw-alt play" aria-hidden="true"></i>',
        'm_name' => 'PLAY AVATAR BASIC',
        'm_desc' => '',
        'm_parents' => array(12289,4527),
    ),
    12279 => array(
        'm_icon' => '<i class="fad fa-paw-claws play" aria-hidden="true"></i>',
        'm_name' => 'PLAY AVATAR SUPER',
        'm_desc' => '',
        'm_parents' => array(12289,4527),
    ),
    12410 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY COINS',
        'm_desc' => '',
        'm_parents' => array(12408,4527),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY COINS',
        'm_desc' => '',
        'm_parents' => array(12467,12228,4527,4758),
    ),
    6194 => array(
        'm_icon' => '<i class="fad fa-database . play" aria-hidden="true"></i>',
        'm_name' => 'PLAY CONNECTIONS',
        'm_desc' => '',
        'm_parents' => array(12412,4758,4527,6212),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender play" aria-hidden="true"></i>',
        'm_name' => 'PLAY GENDER',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    6827 => array(
        'm_icon' => '<i class="far fa-users-crown"></i>',
        'm_name' => 'PLAY GROUPS',
        'm_desc' => '',
        'm_parents' => array(3303,3314,4527),
    ),
    12421 => array(
        'm_icon' => '<i class="fas fa-icons"></i>',
        'm_name' => 'PLAY ICON DROPDOWN',
        'm_desc' => '',
        'm_parents' => array(3303,4428,4527,4758),
    ),
    11089 => array(
        'm_icon' => '<i class="fad fa-crop-alt play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4536,4527),
    ),
    4592 => array(
        'm_icon' => '<i class="fas fa-link play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINKS',
        'm_desc' => '',
        'm_parents' => array(11026,5982,5981,4527),
    ),
    12403 => array(
        'm_icon' => '<i class="far fa-object-ungroup" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK TYPE CUSTOM UI',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock" aria-hidden="true"></i>',
        'm_name' => 'PLAY LOCK',
        'm_desc' => '',
        'm_parents' => array(4758,3303,4426,4527),
    ),
    12500 => array(
        'm_icon' => '<i class="fad fa-user" aria-hidden="true"></i>',
        'm_name' => 'PLAY MENU',
        'm_desc' => '',
        'm_parents' => array(12497,12501,4527),
    ),
    7555 => array(
        'm_icon' => '<i class="fas fa-paper-plane" aria-hidden="true"></i>',
        'm_name' => 'PLAY READING CHANNELS',
        'm_desc' => '',
        'm_parents' => array(7305,4527),
    ),
    4986 => array(
        'm_icon' => '<i class="fal fa-at" aria-hidden="true"></i>',
        'm_name' => 'PLAY REFERENCE ALLOWED',
        'm_desc' => '',
        'm_parents' => array(10889,4758,4527),
    ),
    7551 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'PLAY REFERENCE REQUIRED',
        'm_desc' => '',
        'm_parents' => array(10889,4527,4758),
    ),
    11028 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90 play" aria-hidden="true"></i>',
        'm_name' => 'PLAYS LINKS DIRECTION',
        'm_desc' => '',
        'm_parents' => array(4527,11026),
    ),
    4537 => array(
        'm_icon' => '<i class="fal fa-spider-web" aria-hidden="true"></i>',
        'm_name' => 'PLAYS LINKS URLS',
        'm_desc' => '',
        'm_parents' => array(4758,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h play" aria-hidden="true"></i>',
        'm_name' => 'PLAY STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    7358 => array(
        'm_icon' => '<i class="far fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'PLAY STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(12572,4527),
    ),
    7357 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PLAY STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(12572,11054,4527),
    ),
    12575 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'PLAY STATUS SEARCHABLE',
        'm_desc' => '',
        'm_parents' => array(4527,12572),
    ),
    12401 => array(
        'm_icon' => '<i class="fad fa-sync play"></i>',
        'm_name' => 'PLAY SYNC STATUS',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    6206 => array(
        'm_icon' => '<i class="far fa-table play" aria-hidden="true"></i>',
        'm_name' => 'PLAY TABLE',
        'm_desc' => '',
        'm_parents' => array(4527,7735,4536),
    ),
    3289 => array(
        'm_icon' => '<i class="fas fa-map-marked play" aria-hidden="true"></i>',
        'm_name' => 'PLAY TIMEZONE',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    12577 => array(
        'm_icon' => '<i class="fad fa-text"></i>',
        'm_name' => 'PLAY UPDATER UPPERCASE',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-edit play" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIOS EDITOR',
        'm_desc' => '',
        'm_parents' => array(11029,12574,10967,11089,4758,4506,4527),
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
    12326 => array(
        'm_icon' => '<i class="far fa-sort read" aria-hidden="true"></i>',
        'm_name' => 'READ BLOG LINKS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ COINS',
        'm_desc' => '',
        'm_parents' => array(12467,10964,11089,12410,12365,6771,12228,4527),
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
        'm_icon' => '<i class="fas fa-list-ul read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10989,11089,12365,6205,12228,4527),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'READ METADATA',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
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
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h read" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
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
    4341 => array(
        'm_icon' => '<i class="far fa-table read"></i>',
        'm_name' => 'READ TABLE',
        'm_desc' => '',
        'm_parents' => array(4527,7735,6205),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug read" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
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
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt play" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(12502,12500,6225,11035,5007,4527),
    ),
    12322 => array(
        'm_icon' => '<i class="fas fa-comment read" aria-hidden="true"></i>',
        'm_name' => 'TEMPLATE BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(4527,12320),
    ),
    12321 => array(
        'm_icon' => '<i class="fad fa-object-group read"></i>',
        'm_name' => 'TEMPLATE BLOG READ',
        'm_desc' => '',
        'm_parents' => array(4527,12320),
    ),
    6805 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'THING INTERACTION CONTENT REQUIRES TEXT',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
);

//READ UPDATES:
$config['en_ids_10658'] = array(10686,10663,10664,10676,10678,10679,10677,10681,10675,10662,10657,10656,10659,10673,10689,10690,10683,12328,7578);
$config['en_all_10658'] = array(
    10686 => array(
        'm_icon' => '<i class="fad fa-unlink blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="fad fa-coin blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE MARKS',
        'm_desc' => '',
        'm_parents' => array(4535,4228,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4593,4229,10658),
    ),
    10676 => array(
        'm_icon' => '<i class="fad fa-sort blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES SORTED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="fad fa-trash-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10658,4593,10638),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="fad fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-sort blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fad fa-sort blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    10662 => array(
        'm_icon' => '<i class="fad fa-hashtag blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE LINK',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4593,10658),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10658,10645),
    ),
    10656 => array(
        'm_icon' => '<i class="fad fa-sliders-h play"></i>',
        'm_name' => 'PLAY LINK STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10645),
    ),
    10659 => array(
        'm_icon' => '<i class="fad fa-plug play"></i>',
        'm_name' => 'PLAY LINK TYPE UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10658,4593,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="fad fa-trash-alt play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10645,4593,10658),
    ),
    10689 => array(
        'm_icon' => '<i class="fad fa-share-alt rotate90 play"></i>',
        'm_name' => 'PLAY MERGED IN PLAY',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10645),
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
);

//FILE TYPE ATTACHMENT:
$config['en_ids_10627'] = array(4259,4261,4260,4258,4554,4556,4555,4549,4551,4550,4548,4553);
$config['en_all_10627'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up play"></i>',
        'm_name' => 'PLAY LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video play"></i>',
        'm_name' => 'PLAY LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
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
);

//READ TYPE ADD CONTENT:
$config['en_ids_10593'] = array(12419,4250,4601,4231,10679,4983,10644,4251,4259,10657,4261,4260,4255,4258,10646,4554,4556,4555,6563,4570,7702,4549,4551,4550,4548,4552,4553);
$config['en_all_10593'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,4535,12149,12141,10638,10593,4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10593,10658,10638),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAYS',
        'm_desc' => '',
        'm_parents' => array(10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    10644 => array(
        'm_icon' => '<i class="fad fa-bullseye-arrow blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TITLE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10638),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY CREATED',
        'm_desc' => '',
        'm_parents' => array(12401,12274,12149,12141,10645,10593,4593),
    ),
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up play"></i>',
        'm_name' => 'PLAY LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10658,10645),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left play"></i>',
        'm_name' => 'PLAY LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,4592),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video play"></i>',
        'm_name' => 'PLAY LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10646 => array(
        'm_icon' => '<i class="fad fa-fingerprint play"></i>',
        'm_name' => 'PLAY NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10645),
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
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ RECEIVED BLOG',
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
);

//READ LIST:
$config['en_ids_7347'] = array(7495,4235);
$config['en_all_7347'] = array(
    7495 => array(
        'm_icon' => '<i class="far fa-play read" aria-hidden="true"></i>',
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

//BLOG AND:
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
        'm_name' => 'UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12324,12117,7751,7585,6192),
    ),
    6677 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'READ',
        'm_desc' => '',
        'm_parents' => array(12330,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard " aria-hidden="true"></i>',
        'm_name' => 'TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12324,6144,7585,6192),
    ),
);

//BLOG TYPE GROUPS:
$config['en_ids_10602'] = array(6192,6193);
$config['en_all_10602'] = array(
    6192 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'AND',
        'm_desc' => 'Reader completes blog by completing ALL child blogs',
        'm_parents' => array(4527,10602),
    ),
    6193 => array(
        'm_icon' => '<i class="fad fa-code-branch rotate180 " aria-hidden="true"></i>',
        'm_name' => 'OR',
        'm_desc' => 'Reader completes blog by completing ANY child blog',
        'm_parents' => array(10602,4527),
    ),
);

//BLOG PLAYS:
$config['en_ids_4983'] = array(2997,4446,3005,4763,3147,2999,3084,5948,3192,2998,4430);
$config['en_all_4983'] = array(
    2997 => array(
        'm_icon' => '<i class="fad fa-file-word play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT ARTICLES',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fad fa-tachometer play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fad fa-book play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT BOOKS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT CHANNELS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT COURSES',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT PODCASTS',
        'm_desc' => '',
        'm_parents' => array(12523,10809,4983,7614,6805,3000),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut play" aria-hidden="true"></i>',
        'm_name' => 'EXPERTS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,6827),
    ),
    5948 => array(
        'm_icon' => '<i class="fad fa-file-invoice play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT TEMPLATES',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT TOOLS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fad fa-play play" aria-hidden="true"></i>',
        'm_name' => 'EXPERT VIDEOS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-horse-head play" aria-hidden="true"></i>',
        'm_name' => 'HUMAN PLAYERS',
        'm_desc' => '',
        'm_parents' => array(12437,11035,10573,4983,6827,4426),
    ),
);

//BLOG TYPE UPLOAD:
$config['en_ids_7751'] = array(7637);
$config['en_all_7751'] = array(
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12324,12117,7751,7585,6192),
    ),
);

//READ METADATA:
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

//READ TABLE:
$config['en_ids_4341'] = array(4369,4429,7694,4367,4372,6103,4368,4366,4371,4364,4370,6186,4362,4593);
$config['en_all_4341'] = array(
    4369 => array(
        'm_icon' => '<i class="fas fa-hashtag" aria-hidden="true"></i>',
        'm_name' => 'CHILD BLOG',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'CHILD PLAY',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    7694 => array(
        'm_icon' => '<i class="fas fa-project-diagram"></i>',
        'm_name' => 'EXTERNAL ID',
        'm_desc' => '',
        'm_parents' => array(6215,6232,4341),
    ),
    4367 => array(
        'm_icon' => '<i class="fas fa-info-circle"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,4341),
    ),
    4372 => array(
        'm_icon' => '<i class="fas fa-sticky-note"></i>',
        'm_name' => 'MESSAGE',
        'm_desc' => '',
        'm_parents' => array(7578,10679,10657,5001,6232,4341),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'METADATA',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-hashtag" aria-hidden="true"></i>',
        'm_name' => 'PARENT BLOG',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'PARENT PLAY',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link" aria-hidden="true"></i>',
        'm_name' => 'PARENT READ',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    4364 => array(
        'm_icon' => '<i class="far fa-user-edit read" aria-hidden="true"></i>',
        'm_name' => 'PLAYER',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fas fa-sort"></i>',
        'm_name' => 'RANK',
        'm_desc' => 'tr_order empowers the arrangement or disposition of intents, entities or transactions in relation to each other according to a particular sequence, pattern, or method defined by Miners or Masters.',
        'm_parents' => array(10676,10675,6232,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h read" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="far fa-clock" aria-hidden="true"></i>',
        'm_name' => 'TIMESTAMP',
        'm_desc' => '',
        'm_parents' => array(6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug read" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//PLAY TABLE:
$config['en_ids_6206'] = array(6198,6160,6172,6197,6177);
$config['en_all_6206'] = array(
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle play"></i>',
        'm_name' => 'ICON',
        'm_desc' => '',
        'm_parents' => array(10653,5943,10625,6232,6206),
    ),
    6160 => array(
        'm_icon' => '<i class="fas fa-user-circle play"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda play"></i>',
        'm_name' => 'METADATA',
        'm_desc' => '',
        'm_parents' => array(6232,3323,6206,6195),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint play" aria-hidden="true"></i>',
        'm_name' => 'NICKNAME',
        'm_desc' => '',
        'm_parents' => array(4269,12412,12232,6225,11072,10646,5000,4998,4999,6232,6206),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h play" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
);

//BLOG TABLE:
$config['en_ids_6201'] = array(6202,6159,4356,4737,4736,7585,5008);
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
        'm_icon' => '<i class="fas fa-sliders-h blog" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fas fa-random blog" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(11018,12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    5008 => array(
        'm_icon' => '<i class="fad fa-tools " aria-hidden="true"></i>',
        'm_name' => 'VERB',
        'm_desc' => '',
        'm_parents' => array(6232,6201,6768,4736,7777,6160),
    ),
);

//SINGLE SELECTABLE:
$config['en_ids_6204'] = array(4737,7585,10602,3290,6177,3289,6186,4593);
$config['en_all_6204'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE',
        'm_desc' => '',
        'm_parents' => array(11018,12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE GROUPS',
        'm_desc' => '',
        'm_parents' => array(10893,6204,7302,4527),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender play" aria-hidden="true"></i>',
        'm_name' => 'PLAY GENDER',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h play" aria-hidden="true"></i>',
        'm_name' => 'PLAY STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    3289 => array(
        'm_icon' => '<i class="fas fa-map-marked play" aria-hidden="true"></i>',
        'm_name' => 'PLAY TIMEZONE',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h read" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug read" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//BLOG TYPE SELECT:
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
$config['en_ids_7704'] = array(6157,7489,12336,12334);
$config['en_all_7704'] = array(
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
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
);

//BLOG LINK CONDITIONAL:
$config['en_ids_4229'] = array(10664,6997,6140);
$config['en_all_4229'] = array(
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4593,4229,10658),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => 'A step that has become available because of the score generated from student answers',
        'm_parents' => array(6205,12326,12227,6410,4229,4755,4593),
    ),
);

//BLOG OR:
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

//BLOG TYPE:
$config['en_ids_7585'] = array(6677,6683,7637,6914,6684,7231,6907);
$config['en_all_7585'] = array(
    6677 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'READ',
        'm_desc' => 'Read messages & complete all child blogs',
        'm_parents' => array(12330,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard " aria-hidden="true"></i>',
        'm_name' => 'TEXT REPLY',
        'm_desc' => 'Give a text response & complete all child blogs',
        'm_parents' => array(12324,6144,7585,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD',
        'm_desc' => 'Upload a file & complete all child blogs',
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
        'm_desc' => 'Select 1 blog from child blogs',
        'm_parents' => array(12336,12129,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square" aria-hidden="true"></i>',
        'm_name' => 'SELECT SOME',
        'm_desc' => 'Select 1 or more blogs from child blogs',
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
$config['en_ids_5967'] = array(12450,12419,4250,12453,4246,7504,4235);
$config['en_all_5967'] = array(
    12450 => array(
        'm_icon' => '<i class="fas fa-pen-square blog"></i>',
        'm_name' => 'BLOG AUTHOR REQUEST',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(4593,4755,4535,5967),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG COMMENTS',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG CREATED',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(5967,12400,4535,12149,12141,10638,10593,4593),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone blog"></i>',
        'm_name' => 'BLOG FEATURE REQUEST',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(12137,4535,4755,4593,5967),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug play" aria-hidden="true"></i>',
        'm_name' => 'PLAY BUG REPORTS',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation play" aria-hidden="true"></i>',
        'm_name' => 'PLAY REVIEW TRIGGER',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ STARTED',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(6205,12227,7347,5967,4755,4593),
    ),
);

//PLAY READING CHANNELS:
$config['en_ids_7555'] = array(12103);
$config['en_all_7555'] = array(
    12103 => array(
        'm_icon' => '<i class="fab fa-chrome" aria-hidden="true"></i>',
        'm_name' => 'PLAY WEBSITE',
        'm_desc' => 'Read using modern web browsers & receive notifications using email.',
        'm_parents' => array(7555),
    ),
);

//PLAY REFERENCE REQUIRED:
$config['en_ids_7551'] = array(10573,4983,7545);
$config['en_all_7551'] = array(
    10573 => array(
        'm_icon' => '<i class="fas fa-list-ul blog" aria-hidden="true"></i>',
        'm_name' => 'LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAYS',
        'm_desc' => '',
        'm_parents' => array(10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag play" aria-hidden="true"></i>',
        'm_name' => 'PLAY TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
);

//BLOG TYPE REQUIREMENT:
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
        'm_name' => 'READ DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
);

//READ STATUS ACTIVE:
$config['en_ids_7360'] = array(6175,6176,12399);
$config['en_all_7360'] = array(
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    12399 => array(
        'm_icon' => '<i class="fad fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCHABLE',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
);

//READ STATUS PUBLIC:
$config['en_ids_7359'] = array(6176,12399);
$config['en_all_7359'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    12399 => array(
        'm_icon' => '<i class="fad fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCHABLE',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
);

//PLAY STATUS ACTIVE:
$config['en_ids_7358'] = array(6180,6181,12563);
$config['en_all_7358'] = array(
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10654,7358,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
    12563 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SEARCHABLE',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
);

//PLAY STATUS PUBLIC:
$config['en_ids_7357'] = array(6181,12563);
$config['en_all_7357'] = array(
    6181 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
    12563 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SEARCHABLE',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
);

//BLOG STATUS ACTIVE:
$config['en_ids_7356'] = array(6183,6184,12137);
$config['en_all_7356'] = array(
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin " aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10648,7356,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
    12137 => array(
        'm_icon' => '<i class="fad fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCHABLE',
        'm_desc' => '',
        'm_parents' => array(10648,12138,7356,7355,4737),
    ),
);

//BLOG STATUS PUBLIC:
$config['en_ids_7355'] = array(6184,12137);
$config['en_all_7355'] = array(
    6184 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
    12137 => array(
        'm_icon' => '<i class="fad fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCHABLE',
        'm_desc' => '',
        'm_parents' => array(10648,12138,7356,7355,4737),
    ),
);

//BLOG STATS:
$config['en_ids_7302'] = array(4737,10602);
$config['en_all_7302'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece blog" aria-hidden="true"></i>',
        'm_name' => 'TYPE GROUPS',
        'm_desc' => '',
        'm_parents' => array(10893,6204,7302,4527),
    ),
);

//READ STATS:
$config['en_ids_7304'] = array(6186);
$config['en_all_7304'] = array(
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h read" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
);

//READ STATUS:
$config['en_ids_6186'] = array(12399,6176,6175,6173);
$config['en_all_6186'] = array(
    12399 => array(
        'm_icon' => '<i class="fad fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCHABLE',
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

//PLAY CONNECTIONS:
$config['en_ids_6194'] = array(4737,7585,6177,4364,6186,4593);
$config['en_all_6194'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_blog WHERE in_status_play_id=',
        'm_parents' => array(10990,12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_blog WHERE in_status_play_id IN (6183,6184) AND in_type_play_id=',
        'm_parents' => array(11018,12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h play" aria-hidden="true"></i>',
        'm_name' => 'PLAY STATUS',
        'm_desc' => 'SELECT count(en_id) as totals FROM table_play WHERE en_status_play_id=',
        'm_parents' => array(11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    4364 => array(
        'm_icon' => '<i class="far fa-user-edit read" aria-hidden="true"></i>',
        'm_name' => 'READ PLAYER',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_read WHERE ln_status_play_id IN (6175,6176) AND ln_player_play_id=',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h read" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_read WHERE ln_status_play_id=',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug read" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_read WHERE ln_status_play_id IN (6175,6176) AND ln_type_play_id=',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//PLAY GROUPS:
$config['en_ids_6827'] = array(12428,3084,4430);
$config['en_all_6827'] = array(
    12428 => array(
        'm_icon' => '<i class="far fa-lock"></i>',
        'm_name' => 'INACTIVE PLAYS',
        'm_desc' => '',
        'm_parents' => array(6827),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut play" aria-hidden="true"></i>',
        'm_name' => 'EXPERTS',
        'm_desc' => 'Experienced in their respective industry with a track record of advancing their field of knowldge',
        'm_parents' => array(12523,4983,6827),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-horse-head play" aria-hidden="true"></i>',
        'm_name' => 'HUMAN PLAYERS',
        'm_desc' => 'Users who are pursuing their intentions using Mench, mainly to get hired at their dream job',
        'm_parents' => array(12437,11035,10573,4983,6827,4426),
    ),
);

//THING INTERACTION CONTENT REQUIRES TEXT:
$config['en_ids_6805'] = array(3005,4763,3147,2999,3192);
$config['en_all_6805'] = array(
    3005 => array(
        'm_icon' => '<i class="fad fa-book play" aria-hidden="true"></i>',
        'm_name' => 'BOOKS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone play" aria-hidden="true"></i>',
        'm_name' => 'CHANNELS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation play" aria-hidden="true"></i>',
        'm_name' => 'COURSES',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone play" aria-hidden="true"></i>',
        'm_name' => 'PODCASTS',
        'm_desc' => '',
        'm_parents' => array(12523,10809,4983,7614,6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools play" aria-hidden="true"></i>',
        'm_name' => 'TOOLS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
);

//READER READABLE:
$config['en_ids_6345'] = array(4231);
$config['en_all_6345'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
);

//READ COINS:
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

//PLAY REFERENCE ALLOWED:
$config['en_ids_4986'] = array(12419,4601,4231);
$config['en_all_4986'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt blog" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search blog" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment blog" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
);

//MY ACCOUNT:
$config['en_ids_6225'] = array(10957,12289,6197,3288,3286);
$config['en_all_6225'] = array(
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt play" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(12502,12500,6225,11035,5007,4527),
    ),
    12289 => array(
        'm_icon' => '<i class="fad fa-paw play" aria-hidden="true"></i>',
        'm_name' => 'PLAY AVATAR',
        'm_desc' => '',
        'm_parents' => array(4536,6225),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint play" aria-hidden="true"></i>',
        'm_name' => 'PLAY NICKNAME',
        'm_desc' => '',
        'm_parents' => array(4269,12412,12232,6225,11072,10646,5000,4998,4999,6232,6206),
    ),
    3288 => array(
        'm_icon' => '<i class="fad fa-envelope-open play" aria-hidden="true"></i>',
        'm_name' => 'PLAY EMAIL',
        'm_desc' => '',
        'm_parents' => array(4269,12221,12103,6225,4426,4755),
    ),
    3286 => array(
        'm_icon' => '<i class="fad fa-key play" aria-hidden="true"></i>',
        'm_name' => 'PLAY PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4269,4426,7578,6225,4755),
    ),
);

//BLOG STATUS:
$config['en_ids_4737'] = array(12137,6184,6183,6182);
$config['en_all_4737'] = array(
    12137 => array(
        'm_icon' => '<i class="fad fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCHABLE',
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

//PLAY STATUS:
$config['en_ids_6177'] = array(12563,6181,6180,6178);
$config['en_all_6177'] = array(
    12563 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SEARCHABLE',
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

//PORTFOLIOS EDITOR:
$config['en_ids_4997'] = array(5000,4998,4999,5001,5003,5865,5943,12318,10625,5982,5981,11956);
$config['en_all_4997'] = array(
    5000 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'NAME REPLACE',
        'm_desc' => 'Search for occurrence of string in child entity names and if found, updates it with a replacement string',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'NAME PREFIX',
        'm_desc' => 'Adds string to the beginning of all child entities. Make sure to include a space for it to look good',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'NAME POSTFIX',
        'm_desc' => 'Adds string to the end of all child entities',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="play fad fa-sticky-note"></i>',
        'm_name' => 'CONTENT REPLACE',
        'm_desc' => 'Search for occurance of string in child entity link contents and if found, updates it with a replacement string',
        'm_parents' => array(4535,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="play fad fa-sliders-h"></i>',
        'm_name' => 'STATUS REPLACE',
        'm_desc' => 'Updates all child entity statuses that match the initial entity status condition',
        'm_parents' => array(4535,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="play fad fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'LINK STATUS REPLACE',
        'm_desc' => 'Updates all child entity link statuses that match the initial link status condition',
        'm_parents' => array(4535,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fad fa-user-circle play" aria-hidden="true"></i>',
        'm_name' => 'ICON UPDATE FOR ALL',
        'm_desc' => 'Updates all child entity icons with string which needs to be a valid icon',
        'm_parents' => array(4535,4593,4997),
    ),
    12318 => array(
        'm_icon' => '<i class="fad fa-user-circle play" aria-hidden="true"></i>',
        'm_name' => 'ICON UPDATE IF MISSING',
        'm_desc' => 'Updates all icons that are not set to the new value.',
        'm_parents' => array(4535,4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="play fad fa-user-circle"></i>',
        'm_name' => 'ICON REPLACE',
        'm_desc' => 'Search for occurrence of string in child entity icons and if found, updates it with a replacement string',
        'm_parents' => array(4535,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="play fad fa-layer-minus"></i>',
        'm_name' => 'PROFILE REMOVE',
        'm_desc' => 'If already added as the parent, this will remove searched entity as the parent of all child entities',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="play fad fa-layer-plus"></i>',
        'm_name' => 'PROFILE ADD',
        'm_desc' => 'If not already done so, will add searched entity as the parent of all child entities',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="play fad fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PROFILE IF ADD',
        'm_desc' => 'Adds a parent entity only IF the entity has another parent entity.',
        'm_parents' => array(12577,4535,4593,4997),
    ),
);

//PLAY LOCK:
$config['en_ids_4426'] = array(4430,3288,4426,6196,3286,4755);
$config['en_all_4426'] = array(
    4430 => array(
        'm_icon' => '<i class="fas fa-horse-head play" aria-hidden="true"></i>',
        'm_name' => 'HUMAN PLAYERS',
        'm_desc' => '',
        'm_parents' => array(12437,11035,10573,4983,6827,4426),
    ),
    3288 => array(
        'm_icon' => '<i class="fad fa-envelope-open play" aria-hidden="true"></i>',
        'm_name' => 'PLAY EMAIL',
        'm_desc' => '',
        'm_parents' => array(4269,12221,12103,6225,4426,4755),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock" aria-hidden="true"></i>',
        'm_name' => 'PLAY LOCK',
        'm_desc' => '',
        'm_parents' => array(4758,3303,4426,4527),
    ),
    6196 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger play" aria-hidden="true"></i>',
        'm_name' => 'PLAY MESSENGER',
        'm_desc' => '',
        'm_parents' => array(12222,4426,3320),
    ),
    3286 => array(
        'm_icon' => '<i class="fad fa-key play" aria-hidden="true"></i>',
        'm_name' => 'PLAY PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4269,4426,7578,6225,4755),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash" aria-hidden="true"></i>',
        'm_name' => 'PRIVATE READ',
        'm_desc' => '',
        'm_parents' => array(4755,6771,4426,4527),
    ),
);

//PRIVATE READ:
$config['en_ids_4755'] = array(12450,12453,10681,4783,6232,6157,7489,4246,3288,12117,3286,4559,7504,6144,7485,7486,6997,4755,12336,12334,4554,6149,6969,4275,4283,7610,7757,6155,6415,6559,6560,6556,6578,7611,4556,4555,7563,12360,4266,4267,12489,4282,6563,5967,6132,4570,7702,7495,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,6143,4235,12197,7492,4552,6140,12328,7578,6224,4553,7562);
$config['en_all_4755'] = array(
    12450 => array(
        'm_icon' => '<i class="fas fa-pen-square blog"></i>',
        'm_name' => 'BLOG AUTHOR REQUEST',
        'm_desc' => '',
        'm_parents' => array(4593,4755,4535,5967),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone blog"></i>',
        'm_name' => 'BLOG FEATURE REQUEST',
        'm_desc' => '',
        'm_parents' => array(12137,4535,4755,4593,5967),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-sort blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4755,4593,10658),
    ),
    4783 => array(
        'm_icon' => '<i class="far fa-phone play"></i>',
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
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug play" aria-hidden="true"></i>',
        'm_name' => 'PLAY BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    3288 => array(
        'm_icon' => '<i class="fad fa-envelope-open play" aria-hidden="true"></i>',
        'm_name' => 'PLAY EMAIL',
        'm_desc' => '',
        'm_parents' => array(4269,12221,12103,6225,4426,4755),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'PLAY FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    3286 => array(
        'm_icon' => '<i class="fad fa-key play" aria-hidden="true"></i>',
        'm_name' => 'PLAY PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4269,4426,7578,6225,4755),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'PLAY READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation play" aria-hidden="true"></i>',
        'm_name' => 'PLAY REVIEW TRIGGER',
        'm_desc' => '',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'PLAY TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
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
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ BLOG CONSIDERED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="read fad fa-megaphone"></i>',
        'm_name' => 'READ BLOG RECOMMENDED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="read fad fa-search"></i>',
        'm_name' => 'READ BLOG SEARCH',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6554,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ BLOGS LISTED',
        'm_desc' => '',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="read fad fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ BLOG STARTED',
        'm_desc' => '',
        'm_parents' => array(6205,10638,4755,4593),
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
        'm_name' => 'READ ENGAGED BLOG POST',
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
        'm_icon' => '<i class="read fad fa-sort read" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ RECEIVED BLOG',
        'm_desc' => '',
        'm_parents' => array(6205,10593,4593,4755,7569),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-play read" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN FROM BLOG',
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
        'm_name' => 'READ TAG PLAY',
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
);

//READ TYPE:
$config['en_ids_4593'] = array(10671,12450,12419,4250,12453,4601,4229,4228,10686,10663,10664,10643,10573,6226,4231,10676,10678,10679,10677,4983,7545,10681,10675,10662,10648,10650,10644,10651,4993,6157,7489,10672,4246,4251,12117,10653,4259,10657,4261,10669,4260,4319,7657,4230,10656,4255,4318,10659,10673,4256,4258,4257,10689,10646,4559,7504,10654,6144,5007,7485,7486,6997,5001,10625,5943,12318,5865,4999,4998,5000,5981,11956,5982,5003,4994,12129,12336,12334,4554,6149,6969,4275,4283,7610,7757,6155,12106,6415,6559,6560,6556,6578,7611,4556,4555,7563,12360,10690,4266,4267,12489,4282,6563,5967,10683,6132,4570,7702,7495,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,6143,4235,12197,7492,4552,6140,12328,7578,6224,4553,7562);
$config['en_all_4593'] = array(
    10671 => array(
        'm_icon' => '<i class="fad fa-trash-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593,10638),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-pen-square blog"></i>',
        'm_name' => 'BLOG AUTHOR REQUEST',
        'm_desc' => '',
        'm_parents' => array(4593,4755,4535,5967),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,4535,12149,12141,10638,10593,4593),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone blog"></i>',
        'm_name' => 'BLOG FEATURE REQUEST',
        'm_desc' => '',
        'm_parents' => array(12137,4535,4755,4593,5967),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(4535,4527,6410,6283,4593,4486),
    ),
    4228 => array(
        'm_icon' => '<i class="fad fa-play blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK FIXED',
        'm_desc' => '',
        'm_parents' => array(4535,6410,4593,4486),
    ),
    10686 => array(
        'm_icon' => '<i class="fad fa-unlink blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="fad fa-coin blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE MARKS',
        'm_desc' => '',
        'm_parents' => array(4535,4228,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4593,4229,10658),
    ),
    10643 => array(
        'm_icon' => '<i class="fas fa-yin-yang mench-spin blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK YIN YANG',
        'm_desc' => '',
        'm_parents' => array(4535,4593,6410,4486),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-list-ul blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG LIST',
        'm_desc' => 'Keeps track of the users who can manage/edit the intent',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    6226 => array(
        'm_icon' => '<i class="fad fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG MASS UPDATE STATUS',
        'm_desc' => ' When all intents within a recursive tree are updated at once.',
        'm_parents' => array(4535,4593),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    10676 => array(
        'm_icon' => '<i class="fad fa-sort blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES SORTED',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="fad fa-trash-alt blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10658,4593,10638),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="fad fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAYS',
        'm_desc' => 'References track intent correlations referenced within expert sources, and represent a core building block of intelligence. References are among the most precious transaction types because they indicate that IF you do A, you will likely accomplish B. As trainers add more sources from more experts, certain intent correlations will receive more references than others, thus gaining more credibility.',
        'm_parents' => array(10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag play" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAY TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-sort blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fad fa-sort blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10638),
    ),
    10662 => array(
        'm_icon' => '<i class="fad fa-hashtag blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE LINK',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4593,10658),
    ),
    10648 => array(
        'm_icon' => '<i class="fad fa-sliders-h blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(12400,4535,4593,10638),
    ),
    10650 => array(
        'm_icon' => '<i class="fad fa-clock blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TIME',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="fad fa-bullseye-arrow blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TITLE',
        'm_desc' => 'Logged when trainers update the intent outcome',
        'm_parents' => array(4535,10593,4593,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="fad fa-shapes blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TYPE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10638),
    ),
    4993 => array(
        'm_icon' => '<i class="fad fa-eye blog" aria-hidden="true"></i>',
        'm_name' => 'BLOG VIEWED',
        'm_desc' => '',
        'm_parents' => array(4535,10638,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'PLAY ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,7704,4755,6255,4593),
    ),
    10672 => array(
        'm_icon' => '<i class="fad fa-trash-alt play"></i>',
        'm_name' => 'PLAY ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug play" aria-hidden="true"></i>',
        'm_name' => 'PLAY BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY CREATED',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(12401,12274,12149,12141,10645,10593,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'PLAY FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(6205,12229,12227,12141,4593,4755,6255),
    ),
    10653 => array(
        'm_icon' => '<i class="fad fa-user-circle play"></i>',
        'm_name' => 'PLAY ICON UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10645),
    ),
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up play"></i>',
        'm_name' => 'PLAY LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10658,10645),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10669 => array(
        'm_icon' => '<i class="fab fa-font-awesome-alt play"></i>',
        'm_name' => 'PLAY LINK ICON',
        'm_desc' => '',
        'm_parents' => array(4535,4593,6198,4592),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="fad fa-sort-numeric-down play"></i>',
        'm_name' => 'PLAY LINK INTEGER',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    7657 => array(
        'm_icon' => '<i class="fas fa-divide play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK PERCENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="fad fa-link rotate90 play"></i>',
        'm_name' => 'PLAY LINK RAW',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    10656 => array(
        'm_icon' => '<i class="fad fa-sliders-h play"></i>',
        'm_name' => 'PLAY LINK STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,10658,10645),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left play"></i>',
        'm_name' => 'PLAY LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="fad fa-clock play"></i>',
        'm_name' => 'PLAY LINK TIME',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    10659 => array(
        'm_icon' => '<i class="fad fa-plug play"></i>',
        'm_name' => 'PLAY LINK TYPE UPDATE',
        'm_desc' => 'Iterations happens automatically based on link content',
        'm_parents' => array(4535,10658,4593,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="fad fa-trash-alt play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4535,10645,4593,10658),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser play" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINK URL',
        'm_desc' => '',
        'm_parents' => array(11080,4535,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video play"></i>',
        'm_name' => 'PLAY LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle play"></i>',
        'm_name' => 'PLAY LINK WIDGET',
        'm_desc' => '',
        'm_parents' => array(12524,12403,4535,4593,4592,4537,4506),
    ),
    10689 => array(
        'm_icon' => '<i class="fad fa-share-alt rotate90 play"></i>',
        'm_name' => 'PLAY MERGED IN PLAY',
        'm_desc' => 'When an entity is merged with another entity and the links are carried over',
        'm_parents' => array(4535,4593,10658,10645),
    ),
    10646 => array(
        'm_icon' => '<i class="fad fa-fingerprint play"></i>',
        'm_name' => 'PLAY NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,10645),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'PLAY READ MESSAGES',
        'm_desc' => 'Logged when a student receives the messages of an AND intent that does not have any completion requirements.',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation play" aria-hidden="true"></i>',
        'm_name' => 'PLAY REVIEW TRIGGER',
        'm_desc' => 'Certain links that match an unknown behavior would require an admin to review and ensure it\'s all good',
        'm_parents' => array(4535,5967,4755,4593),
    ),
    10654 => array(
        'm_icon' => '<i class="fad fa-sliders-h play" aria-hidden="true"></i>',
        'm_name' => 'PLAY STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(12401,4535,4593,10645),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'PLAY TEXT REPLY',
        'm_desc' => 'Logged when a student submits the requirements (text, video, etc...) of an AND intent which could not be completed by simply receiving messages.',
        'm_parents' => array(6205,12229,12227,12141,6255,4755,4593),
    ),
    5007 => array(
        'm_icon' => '<i class="fad fa-bolt play" aria-hidden="true"></i>',
        'm_name' => 'PLAY TOGGLE SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(6205,12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(6205,12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'PLAY UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6205,6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
    5001 => array(
        'm_icon' => '<i class="play fad fa-sticky-note"></i>',
        'm_name' => 'PLAY UPDATER CONTENT REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="play fad fa-user-circle"></i>',
        'm_name' => 'PLAY UPDATER ICON REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fad fa-user-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY UPDATER ICON UPDATE FOR ALL',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    12318 => array(
        'm_icon' => '<i class="fad fa-user-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAY UPDATER ICON UPDATE IF MISSING',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="play fad fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'PLAY UPDATER LINK STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'PLAY UPDATER NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'PLAY UPDATER NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="play fad fa-fingerprint"></i>',
        'm_name' => 'PLAY UPDATER NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="play fad fa-layer-plus"></i>',
        'm_name' => 'PLAY UPDATER PROFILE ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="play fad fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PLAY UPDATER PROFILE IF ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="play fad fa-layer-minus"></i>',
        'm_name' => 'PLAY UPDATER PROFILE REMOVE',
        'm_desc' => '',
        'm_parents' => array(12577,4535,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="play fad fa-sliders-h"></i>',
        'm_name' => 'PLAY UPDATER STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4997),
    ),
    4994 => array(
        'm_icon' => '<i class="fad fa-eye play" aria-hidden="true"></i>',
        'm_name' => 'PLAY VIEWED',
        'm_desc' => '',
        'm_parents' => array(4535,4593),
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
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ BLOG CONSIDERED',
        'm_desc' => 'When a student chooses to review a given intention from the intentions they have searched or have been recommended after selecting GET STARTED from a mench.com intent landing page.',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="read fad fa-megaphone"></i>',
        'm_name' => 'READ BLOG RECOMMENDED',
        'm_desc' => 'Logged every time an intention is recommended to a user by Mench',
        'm_parents' => array(6205,10639,4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="read fad fa-search"></i>',
        'm_name' => 'READ BLOG SEARCH',
        'm_desc' => 'When students invokes the [I want to] command and search for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(6205,10639,6554,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ BLOGS LISTED',
        'm_desc' => 'Once a student has added an Intention to their Action Plan, this link will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(6205,10639,6153,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="read fad fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ BLOG STARTED',
        'm_desc' => 'When a user viewes the public intent landing page.',
        'm_parents' => array(6205,10638,4755,4593),
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
        'm_name' => 'READ ENGAGED BLOG POST',
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
        'm_icon' => '<i class="read fad fa-sort read" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ RECEIVED BLOG',
        'm_desc' => 'Emails sent to intent subscribers who are looking for updates on an intent.',
        'm_parents' => array(6205,10593,4593,4755,7569),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-play read" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN FROM BLOG',
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
        'm_name' => 'READ TAG PLAY',
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
);

//PLAY LINKS:
$config['en_ids_4592'] = array(4259,4261,10669,4260,4319,7657,4230,4255,4318,4256,4258,4257);
$config['en_all_4592'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up play"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf play" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    10669 => array(
        'm_icon' => '<i class="fab fa-font-awesome-alt play"></i>',
        'm_name' => 'ICON',
        'm_desc' => 'Icons maping to the Font Awesome database',
        'm_parents' => array(4535,4593,6198,4592),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image play" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="fad fa-sort-numeric-down play"></i>',
        'm_name' => 'INTEGER',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    7657 => array(
        'm_icon' => '<i class="fas fa-divide play" aria-hidden="true"></i>',
        'm_name' => 'PERCENT',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="fad fa-link rotate90 play"></i>',
        'm_name' => 'RAW',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left play"></i>',
        'm_name' => 'TEXT',
        'm_desc' => '',
        'm_parents' => array(4535,10593,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="fad fa-clock play"></i>',
        'm_name' => 'TIME',
        'm_desc' => '',
        'm_parents' => array(4535,4593,4592),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser play" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(11080,4535,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video play"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle play"></i>',
        'm_name' => 'WIDGET',
        'm_desc' => '',
        'm_parents' => array(12524,12403,4535,4593,4592,4537,4506),
    ),
);

//BLOG NOTES:
$config['en_ids_4485'] = array(4231,12419,10573,4601,4983,7545);
$config['en_all_4485'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment blog" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11089,10939,12365,12359,4535,12322,10593,6345,4986,4603,4593,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt blog" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(12359,5967,10939,10593,12322,4986,4535,12365,11089,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-list-ul blog" aria-hidden="true"></i>',
        'm_name' => 'LIST',
        'm_desc' => '',
        'm_parents' => array(11035,10985,11089,12365,4535,12321,4593,7551,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-search blog" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10939,11089,12365,4535,12322,4986,10593,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle play" aria-hidden="true"></i>',
        'm_name' => 'PLAYS',
        'm_desc' => '',
        'm_parents' => array(10983,12273,12228,4535,12365,10593,4527,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-tag play" aria-hidden="true"></i>',
        'm_name' => 'PLAY TAGS',
        'm_desc' => '',
        'm_parents' => array(10986,11089,12365,4535,12321,7551,4593,4485),
    ),
);

//BLOG TREE LINKS:
$config['en_ids_4486'] = array(10643,4228,4229);
$config['en_all_4486'] = array(
    10643 => array(
        'm_icon' => '<i class="fas fa-yin-yang mench-spin blog" aria-hidden="true"></i>',
        'm_name' => 'YIN YANG',
        'm_desc' => 'Blogs with opposing concepts that are each valid on their own',
        'm_parents' => array(4535,4593,6410,4486),
    ),
    4228 => array(
        'm_icon' => '<i class="fad fa-play blog" aria-hidden="true"></i>',
        'm_name' => 'FIXED',
        'm_desc' => 'Blogs that always follow each other',
        'm_parents' => array(4535,6410,4593,4486),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle blog" aria-hidden="true"></i>',
        'm_name' => 'CONDITIONAL',
        'm_desc' => 'Blogs that sometimes follow each other',
        'm_parents' => array(4535,4527,6410,6283,4593,4486),
    ),
);

//PLAYS LINKS URLS:
$config['en_ids_4537'] = array(4259,4261,4260,4256,4258,4257);
$config['en_all_4537'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up play"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf play" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'Link notes contain a URL to a raw file.',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image play" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
        'm_parents' => array(12524,4535,6198,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser play" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => 'Link note contains a generic URL only.',
        'm_parents' => array(11080,4535,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video play"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'Link notes contain a URL to a raw video file.',
        'm_parents' => array(12524,4535,11080,11059,10627,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle play"></i>',
        'm_name' => 'WIDGET',
        'm_desc' => 'Link note contain a recognizable URL that offers an embed widget for a more engaging play-back experience.',
        'm_parents' => array(12524,12403,4535,4593,4592,4537,4506),
    ),
);

//EXPERT CONTENT:
$config['en_ids_3000'] = array(3005,2999,2998,2997,3147,4446,3192,4763,5948);
$config['en_all_3000'] = array(
    3005 => array(
        'm_icon' => '<i class="fad fa-book play" aria-hidden="true"></i>',
        'm_name' => 'BOOKS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone play" aria-hidden="true"></i>',
        'm_name' => 'PODCASTS',
        'm_desc' => '',
        'm_parents' => array(12523,10809,4983,7614,6805,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fad fa-play play" aria-hidden="true"></i>',
        'm_name' => 'VIDEOS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
    2997 => array(
        'm_icon' => '<i class="fad fa-file-word play" aria-hidden="true"></i>',
        'm_name' => 'ARTICLES',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation play" aria-hidden="true"></i>',
        'm_name' => 'COURSES',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fad fa-tachometer play" aria-hidden="true"></i>',
        'm_name' => 'ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools play" aria-hidden="true"></i>',
        'm_name' => 'TOOLS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone play" aria-hidden="true"></i>',
        'm_name' => 'CHANNELS',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="fad fa-file-invoice play" aria-hidden="true"></i>',
        'm_name' => 'TEMPLATES',
        'm_desc' => '',
        'm_parents' => array(12523,4983,7614,3000),
    ),
);