<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
* Keep a cache of certain parts of the Blog tree for faster processing
* So we don't have to make DB calls to figure them out every time!
* See here for all players cached: https://mench.com/play/4527
*
* ATTENTION: Also search for "en_ids_" and "en_all_" when trying to manage these throughout the code base
*
*/

//Generated 2020-01-02 18:37:01 PST

//PLAYER NOTIFICATION CHANNEL:
$config['en_ids_12220'] = array(12221,12222);
$config['en_all_12220'] = array(
    12221 => array(
        'm_icon' => '<i class="fas fa-envelope-open ispink" aria-hidden="true"></i>',
        'm_name' => 'EMAIL FIRST',
        'm_desc' => '',
        'm_parents' => array(12220),
    ),
    12222 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger blue"></i>',
        'm_name' => 'MESSENGER FIRST',
        'm_desc' => '',
        'm_parents' => array(12220),
    ),
);

//BROWSE READS:
$config['en_ids_12201'] = array(10939,12198,10869,5008);
$config['en_all_12201'] = array(
    10939 => array(
        'm_icon' => '<i class="fas fa-pen-square yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOGGER',
        'm_desc' => '',
        'm_parents' => array(12201,6404,10957),
    ),
    12198 => array(
        'm_icon' => '<i class="fas fa-home" aria-hidden="true"></i>',
        'm_name' => 'HOME',
        'm_desc' => '',
        'm_parents' => array(12201,6771),
    ),
    10869 => array(
        'm_icon' => '<i class="fas fa-megaphone ispink" aria-hidden="true"></i>',
        'm_name' => 'TOPIC',
        'm_desc' => '',
        'm_parents' => array(12201,6771,4527),
    ),
    5008 => array(
        'm_icon' => '<i class="fas fa-tools blue" aria-hidden="true"></i>',
        'm_name' => 'VERB',
        'm_desc' => '',
        'm_parents' => array(12201,6768,4736,7777,6160),
    ),
);

//PLAYER TIMEZONE:
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

//PLAYER GENDER:
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
$config['en_ids_12141'] = array(4250,4251,6157,7489,7487,4559,6144,7485,7486,6997,12117);
$config['en_all_12141'] = array(
    4250 => array(
        'm_icon' => '<i class="fas fa-circle yellow"></i>',
        'm_name' => 'BLOG CREATED',
        'm_desc' => '',
        'm_parents' => array(12149,12141,10638,10593,10589,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle blue" aria-hidden="true"></i>',
        'm_name' => 'PLAY CREATED',
        'm_desc' => '',
        'm_parents' => array(12149,12145,12141,10645,10593,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="ispink fas fa-check-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,6255,6146,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="ispink fas fa-check-square ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    7487 => array(
        'm_icon' => '<i class="ispink fas fa-stopwatch ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER TIMELY',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    4559 => array(
        'm_icon' => '<i class="ispink fas fa-comments ispink" aria-hidden="true"></i>',
        'm_name' => 'READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,6255,4755,6146,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="ispink fas fa-keyboard ispink" aria-hidden="true"></i>',
        'm_name' => 'READ REPLIED',
        'm_desc' => '',
        'm_parents' => array(10590,12141,6255,4755,6146,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,6146,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4229,6255,4593,4755,6146),
    ),
    12117 => array(
        'm_icon' => '<i class="ispink fas fa-cloud-upload-alt ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UPLOADED',
        'm_desc' => '',
        'm_parents' => array(10590,12141,4593,4755,6146,6255),
    ),
);

//BEING:
$config['en_ids_12145'] = array(4251);
$config['en_all_12145'] = array(
    4251 => array(
        'm_icon' => '<i class="fas fa-circle blue" aria-hidden="true"></i>',
        'm_name' => 'PLAY CREATED',
        'm_desc' => '',
        'm_parents' => array(12149,12145,12141,10645,10593,4593),
    ),
);

//BLOG STATUSES FEATURED:
$config['en_ids_12138'] = array(12137);
$config['en_all_12138'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-megaphone" aria-hidden="true"></i>',
        'm_name' => 'BLOG FEATURE',
        'm_desc' => '',
        'm_parents' => array(10648,12138,7356,7355,4737),
    ),
);

//BLOG TEXT INPUTS:
$config['en_ids_12112'] = array(4736,4358,4362,4739,4735);
$config['en_all_12112'] = array(
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG TITLE',
        'm_desc' => '',
        'm_parents' => array(12112,11071,10644,6232,6201),
    ),
    4358 => array(
        'm_icon' => '<i class="far fa-coin" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => 'awarded for blog completion to calculate the unlock score range',
        'm_parents' => array(12112,10984,10663,6103,6410,6232),
    ),
    4362 => array(
        'm_icon' => '<i class="far fa-clock" aria-hidden="true"></i>',
        'm_name' => 'READ TIME',
        'm_desc' => 'Estimated number of seconds to read this blog',
        'm_parents' => array(12112,6232,4341),
    ),
    4739 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX SCORE',
        'm_desc' => '',
        'm_parents' => array(12112,6402,6232),
    ),
    4735 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN SCORE',
        'm_desc' => '',
        'm_parents' => array(12112,6402,6232),
    ),
);

//MENCH CHANNELS UPCOMING:
$config['en_ids_12105'] = array(10895,3314,10896,10899,10898);
$config['en_all_12105'] = array(
    10895 => array(
        'm_icon' => '<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Amazon_Alexa_blue_logo.svg/1024px-Amazon_Alexa_blue_logo.svg.png">',
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
        'm_icon' => '<i class="fab fa-telegram isblue"></i>',
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

//BLOG DROPDOWN INPUTS:
$config['en_ids_12079'] = array(4486,4737,7585);
$config['en_all_12079'] = array(
    4486 => array(
        'm_icon' => '<i class="fas fa-link yellow" aria-hidden="true"></i>',
        'm_name' => 'LINKS',
        'm_desc' => 'Set how blogs relate to each other',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => 'Set blog access',
        'm_parents' => array(12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random yellow" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => 'How to complete this blog & advance onwards',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
);

//ABOUT US:
$config['en_ids_12066'] = array();
$config['en_all_12066'] = array(
);

//MENCH GLOSSARY:
$config['en_ids_4463'] = array(4485,3084,4535,4536,6205,4430,3000,4755);
$config['en_all_4463'] = array(
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES',
        'm_desc' => 'Intent notes are various information collected around intentions that enable Mench to operate as a Personal Assistant for students looking to accomplish an intent.',
        'm_parents' => array(4535,4527,4463),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut" aria-hidden="true"></i>',
        'm_name' => 'EXPERTS',
        'm_desc' => 'People with experience in their respective industry that have shown a consistent commitment to advancing their industry.',
        'm_parents' => array(10571,4983,6827,4463),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle yellow" aria-hidden="true"></i>',
        'm_name' => 'MENCH BLOG',
        'm_desc' => 'Intents define the intention of an entity as defined similar to a SMART goal.',
        'm_parents' => array(12155,2738,4463),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle blue" aria-hidden="true"></i>',
        'm_name' => 'MENCH PLAY',
        'm_desc' => 'Entities represent people, objects and things.',
        'm_parents' => array(12155,2738,4463),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'MENCH READ',
        'm_desc' => 'An electronic log book containing a list of transactions and balances typically involving financial accounts.',
        'm_parents' => array(12155,2738,4463),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-user blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYERS',
        'm_desc' => 'Users connected to Mench Personal Assistant on Facebook Messenger.',
        'm_parents' => array(10573,4983,6827,4426,4463),
    ),
    3000 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'PLAY SOURCES',
        'm_desc' => 'We train the Mench personal assistant with sources produced by industry experts. Sources include videos, articles, books, online courses and other channels used by experts to share their knowledge.',
        'm_parents' => array(7303,10571,4506,4527,4463),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash" aria-hidden="true"></i>',
        'm_name' => 'PRIVATE READ',
        'm_desc' => 'Mench is open-source but most of our student generated content is private and accessible either by the student or Mench\'s core contributors.',
        'm_parents' => array(4755,6771,4463,4426,4527),
    ),
);

//BLOG NOTE STATUS:
$config['en_ids_12012'] = array(6176,6173);
$config['en_all_12012'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISH',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'ARCHIVE',
        'm_desc' => '',
        'm_parents' => array(12012,10686,10678,10673,6186),
    ),
);

//BLOGGING. REIMAGINED.:
$config['en_ids_11968'] = array(4762,11969,11980,11984,11972,11977);
$config['en_all_11968'] = array(
    4762 => array(
        'm_icon' => '<i class="fas fa-hand-holding-heart" aria-hidden="true"></i>',
        'm_name' => 'NON-PROFIT',
        'm_desc' => 'and on a mission to expand human potential by building and sharing consensus.',
        'm_parents' => array(11968,7315),
    ),
    11969 => array(
        'm_icon' => '<i class="fas fa-exchange rotate315" aria-hidden="true"></i>',
        'm_name' => 'INTERACTIVE',
        'm_desc' => 'where micro-blogs are linked together with a flow that\'s either pre-determined or reader-determined to create engaging conversations.',
        'm_parents' => array(11982,11968),
    ),
    11980 => array(
        'm_icon' => '<i class="fas fa-users" aria-hidden="true"></i>',
        'm_name' => 'SOCIAL',
        'm_desc' => 'where players can make new friends based on their interests to discuss or practice something important to them.',
        'm_parents' => array(11982,11968),
    ),
    11984 => array(
        'm_icon' => '<i class="fas fa-puzzle-piece" aria-hidden="true"></i>',
        'm_name' => 'MODULAR',
        'm_desc' => 'where all published blogs can be reused to easily and quickly create an engaging conversation.',
        'm_parents' => array(11968),
    ),
    11972 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'INCLUSIVE',
        'm_desc' => 'where everyone is welcome to share stories and ideas that matter to them, write code or even govern the platform.',
        'm_parents' => array(11968),
    ),
    11977 => array(
        'm_icon' => '<i class="fas fa-atom-alt" aria-hidden="true"></i>',
        'm_name' => 'SCIENCE-BASED',
        'm_desc' => 'where blogs can reference first-principles and best-practices from expert sources like books, videos and articles.',
        'm_parents' => array(11968),
    ),
);

//PLAY TABS:
$config['en_ids_11088'] = array(11089,11033);
$config['en_all_11088'] = array(
    11089 => array(
        'm_icon' => '<i class="fas fa-eye blue" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO TABS',
        'm_desc' => '',
        'm_parents' => array(10967,4527,11088),
    ),
    11033 => array(
        'm_icon' => '<i class="fas fa-toolbox blue" aria-hidden="true"></i>',
        'm_name' => 'PROFILE TABS',
        'm_desc' => '',
        'm_parents' => array(11088,4527),
    ),
);

//PLAY PORTFOLIO TABS:
$config['en_ids_11089'] = array(11029,7545,4997,11039);
$config['en_all_11089'] = array(
    11029 => array(
        'm_icon' => '<i class="fas fa-hand-holding-seedling blue" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => '',
        'm_parents' => array(11084,11089,11028),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-user-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAYER TAGS',
        'm_desc' => '',
        'm_parents' => array(11018,11089,10967,10589,7551,4593,4485),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-tools blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MASS UPDATE',
        'm_desc' => '',
        'm_parents' => array(10967,11089,4758,4506,4426,4527),
    ),
    11039 => array(
        'm_icon' => '<i class="fas fa-caret-down" aria-hidden="true"></i>',
        'm_name' => 'PLAY ADMIN MENU',
        'm_desc' => '',
        'm_parents' => array(10967,11089,4527,11040),
    ),
);

//ACADEMICS:
$config['en_ids_10725'] = array(10843,10844,10845,10846,10847,10848,3287,10850,10851,10852);
$config['en_all_10725'] = array(
    10843 => array(
        'm_icon' => '<i class="far fa-wrench"></i>',
        'm_name' => 'ENGINEERING',
        'm_desc' => '',
        'm_parents' => array(10725),
    ),
    10844 => array(
        'm_icon' => '<i class="far fa-landmark"></i>',
        'm_name' => 'HUMANITIES',
        'm_desc' => '',
        'm_parents' => array(10725),
    ),
    10845 => array(
        'm_icon' => '<i class="far fa-calculator-alt" aria-hidden="true"></i>',
        'm_name' => 'MATH TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10725),
    ),
    10846 => array(
        'm_icon' => '<i class="far fa-flask-potion" aria-hidden="true"></i>',
        'm_name' => 'SCIENCE TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10725),
    ),
    10847 => array(
        'm_icon' => '<i class="far fa-mouse-pointer"></i>',
        'm_name' => 'ONLINE EDUCATION',
        'm_desc' => '',
        'm_parents' => array(10725),
    ),
    10848 => array(
        'm_icon' => '<i class="far fa-city"></i>',
        'm_name' => 'SOCIAL SCIENCE',
        'm_desc' => '',
        'm_parents' => array(10725),
    ),
    3287 => array(
        'm_icon' => '<i class="fas fa-language isblue" aria-hidden="true"></i>',
        'm_name' => 'LANGUAGES',
        'm_desc' => '',
        'm_parents' => array(10725,6122),
    ),
    10850 => array(
        'm_icon' => '<i class="far fa-chalkboard-teacher"></i>',
        'm_name' => 'TEACHER TRAINING',
        'm_desc' => '',
        'm_parents' => array(10725),
    ),
    10851 => array(
        'm_icon' => '<i class="far fa-calendar-check"></i>',
        'm_name' => 'TEST PREP',
        'm_desc' => '',
        'm_parents' => array(10725),
    ),
    10852 => array(
        'm_icon' => '<i class="far fa-vial"></i>',
        'm_name' => 'OTHER TEACHING & ACADEMICS',
        'm_desc' => '',
        'm_parents' => array(10725),
    ),
);

//LIFESTYLE:
$config['en_ids_10721'] = array(10914,10915,10777,10916,10917,10824,10810,10825,10829,10834,10811,10812,10813,10814,10815,10816);
$config['en_all_10721'] = array(
    10914 => array(
        'm_icon' => '',
        'm_name' => 'ADDICTION TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10721),
    ),
    10915 => array(
        'm_icon' => '',
        'm_name' => 'CANNABIS TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10721),
    ),
    10777 => array(
        'm_icon' => '<i class="far fa-lightbulb-on" aria-hidden="true"></i>',
        'm_name' => 'CREATIVITY TOPIC',
        'm_desc' => '',
        'm_parents' => array(4305,3311,11097,10721,10711),
    ),
    10916 => array(
        'm_icon' => '',
        'm_name' => 'DISABILITY TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10721),
    ),
    10917 => array(
        'm_icon' => '',
        'm_name' => 'FAMILY TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10721),
    ),
    10824 => array(
        'm_icon' => '<i class="far fa-dumbbell" aria-hidden="true"></i>',
        'm_name' => 'FITNESS TOPIC',
        'm_desc' => '',
        'm_parents' => array(4305,3311,11097,10721),
    ),
    10810 => array(
        'm_icon' => '<i class="far fa-burger-soda" aria-hidden="true"></i>',
        'm_name' => 'FOOD TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10721),
    ),
    10825 => array(
        'm_icon' => '<i class="far fa-heart-rate" aria-hidden="true"></i>',
        'm_name' => 'HEALTH TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10721),
    ),
    10829 => array(
        'm_icon' => '<i class="far fa-brain" aria-hidden="true"></i>',
        'm_name' => 'MENTAL HEALTH TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10721),
    ),
    10834 => array(
        'm_icon' => '<i class="far fa-praying-hands" aria-hidden="true"></i>',
        'm_name' => 'MINDFULNESS TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10721),
    ),
    10811 => array(
        'm_icon' => '<i class="far fa-lips" aria-hidden="true"></i>',
        'm_name' => 'BEAUTY TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809,10721),
    ),
    10812 => array(
        'm_icon' => '<i class="far fa-plane-departure" aria-hidden="true"></i>',
        'm_name' => 'TRAVEL TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10721),
    ),
    10813 => array(
        'm_icon' => '<i class="far fa-dice" aria-hidden="true"></i>',
        'm_name' => 'GAMING TOPIC',
        'm_desc' => '',
        'm_parents' => array(4305,3311,11097,10809,10721),
    ),
    10814 => array(
        'm_icon' => '<i class="far fa-home-heart"></i>',
        'm_name' => 'HOME IMPROVEMENT',
        'm_desc' => '',
        'm_parents' => array(10721),
    ),
    10815 => array(
        'm_icon' => '<i class="far fa-dog-leashed"></i>',
        'm_name' => 'PET CARE & TRAINING',
        'm_desc' => '',
        'm_parents' => array(10721),
    ),
    10816 => array(
        'm_icon' => '<i class="far fa-dove"></i>',
        'm_name' => 'OTHER LIFESTYLE',
        'm_desc' => '',
        'm_parents' => array(10721),
    ),
);

//MARKETING:
$config['en_ids_10720'] = array(10795,10796,10798,10799,10800,10801,10802,10803,10804,10805,10806,10807,10808);
$config['en_all_10720'] = array(
    10795 => array(
        'm_icon' => '<i class="far fa-file-chart-line"></i>',
        'm_name' => 'DIGITAL MARKETING',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10796 => array(
        'm_icon' => '<i class="far fa-list-ol"></i>',
        'm_name' => 'SEARCH ENGINE OPTIMIZATION',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10798 => array(
        'm_icon' => '<i class="far fa-font"></i>',
        'm_name' => 'BRANDING',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10799 => array(
        'm_icon' => '<i class="far fa-megaphone"></i>',
        'm_name' => 'MARKETING FUNDAMENTALS',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10800 => array(
        'm_icon' => '<i class="far fa-robot"></i>',
        'm_name' => 'ANALYTICS & AUTOMATION',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10801 => array(
        'm_icon' => '<i class="far fa-user-headset"></i>',
        'm_name' => 'PUBLIC RELATIONS',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10802 => array(
        'm_icon' => '<i class="far fa-ad"></i>',
        'm_name' => 'ADVERTISING',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10803 => array(
        'm_icon' => '<i class="far fa-film"></i>',
        'm_name' => 'VIDEO & MOBILE MARKETING',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10804 => array(
        'm_icon' => '<i class="far fa-folder-open"></i>',
        'm_name' => 'CONTENT MARKETING',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10805 => array(
        'm_icon' => '<i class="far fa-chart-line"></i>',
        'm_name' => 'GROWTH HACKING',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10806 => array(
        'm_icon' => '<i class="far fa-users-medical"></i>',
        'm_name' => 'AFFILIATE MARKETING',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10807 => array(
        'm_icon' => '<i class="far fa-sunglasses"></i>',
        'm_name' => 'PRODUCT MARKETING',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
    10808 => array(
        'm_icon' => '<i class="far fa-bullhorn"></i>',
        'm_name' => 'OTHER MARKETING',
        'm_desc' => '',
        'm_parents' => array(10720),
    ),
);

//DESIGN:
$config['en_ids_10719'] = array(10784,10785,10786,10787,10788,10789,10790,10791,10792,10793,10794);
$config['en_all_10719'] = array(
    10784 => array(
        'm_icon' => '<i class="far fa-object-group"></i>',
        'm_name' => 'WEB DESIGN',
        'm_desc' => '',
        'm_parents' => array(10719),
    ),
    10785 => array(
        'm_icon' => '<i class="far fa-pencil-paintbrush"></i>',
        'm_name' => 'GRAPHIC DESIGN',
        'm_desc' => '',
        'm_parents' => array(10719),
    ),
    10786 => array(
        'm_icon' => '<i class="far fa-pen-nib"></i>',
        'm_name' => 'DESIGN TOOLS',
        'm_desc' => '',
        'm_parents' => array(10719),
    ),
    10787 => array(
        'm_icon' => '<i class="far fa-hand-pointer"></i>',
        'm_name' => 'USER EXPERIENCE',
        'm_desc' => '',
        'm_parents' => array(10719),
    ),
    10788 => array(
        'm_icon' => '<i class="far fa-puzzle-piece"></i>',
        'm_name' => 'GAME DESIGN',
        'm_desc' => '',
        'm_parents' => array(10719),
    ),
    10789 => array(
        'm_icon' => '<i class="far fa-magic"></i>',
        'm_name' => 'DESIGN THINKING',
        'm_desc' => '',
        'm_parents' => array(10719),
    ),
    10790 => array(
        'm_icon' => '<i class="far fa-play-circle"></i>',
        'm_name' => '3D & ANIMATION',
        'm_desc' => '',
        'm_parents' => array(10719),
    ),
    10791 => array(
        'm_icon' => '<i class="far fa-user-crown"></i>',
        'm_name' => 'FASHION',
        'm_desc' => '',
        'm_parents' => array(10719),
    ),
    10792 => array(
        'm_icon' => '<i class="far fa-synagogue"></i>',
        'm_name' => 'ARCHITECTURAL DESIGN',
        'm_desc' => '',
        'm_parents' => array(10719),
    ),
    10793 => array(
        'm_icon' => '<i class="far fa-lamp"></i>',
        'm_name' => 'INTERIOR DESIGN',
        'm_desc' => '',
        'm_parents' => array(10719),
    ),
    10794 => array(
        'm_icon' => '<i class="far fa-drafting-compass"></i>',
        'm_name' => 'OTHER DESIGN',
        'm_desc' => '',
        'm_parents' => array(10719),
    ),
);

//PRODUCTIVITY:
$config['en_ids_10718'] = array(4626,4796,2792,10766,10767,10768);
$config['en_all_10718'] = array(
    4626 => array(
        'm_icon' => '<i class="fab fa-microsoft"></i>',
        'm_name' => 'MICROSOFT',
        'm_desc' => '',
        'm_parents' => array(1326,10718,3084,2750),
    ),
    4796 => array(
        'm_icon' => '<i class="fab fa-apple"></i>',
        'm_name' => 'APPLE',
        'm_desc' => '',
        'm_parents' => array(3084,10718,2750,1326),
    ),
    2792 => array(
        'm_icon' => '<i class="fab fa-google" aria-hidden="true"></i>',
        'm_name' => 'GOOGLE',
        'm_desc' => '',
        'm_parents' => array(3088,10718,3084,1326,2750),
    ),
    10766 => array(
        'm_icon' => '<i class="far fa-building"></i>',
        'm_name' => 'SAP',
        'm_desc' => '',
        'm_parents' => array(1326,2750,3084,10718),
    ),
    10767 => array(
        'm_icon' => '<i class="far fa-building"></i>',
        'm_name' => 'ORACLE',
        'm_desc' => '',
        'm_parents' => array(1326,2750,3084,10718),
    ),
    10768 => array(
        'm_icon' => '<i class="far fa-phone-office"></i>',
        'm_name' => 'OTHER OFFICE PRODUCTIVITY',
        'm_desc' => '',
        'm_parents' => array(10718),
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

//FINANCE:
$config['en_ids_10716'] = array(11640,11172,11643,11296,11638,11249,11167,11641,11525,11642,11639);
$config['en_all_10716'] = array(
    11640 => array(
        'm_icon' => '<i class="fal fa-pegasus ismatt"></i>',
        'm_name' => 'BENLEFORT1988',
        'm_desc' => '',
        'm_parents' => array(10716,3311,11158,1278),
    ),
    11172 => array(
        'm_icon' => '<i class="far fa-squirrel ismatt"></i>',
        'm_name' => 'DANNYMAIORCA',
        'm_desc' => '',
        'm_parents' => array(10716,10753,3311,11158,1278),
    ),
    11643 => array(
        'm_icon' => '<i class="far fa-fish yellow"></i>',
        'm_name' => 'GK_',
        'm_desc' => '',
        'm_parents' => array(10716,3311,11158,1278),
    ),
    11296 => array(
        'm_icon' => '<i class="fas fa-pig ispink"></i>',
        'm_name' => 'KIMDUKE_68108',
        'm_desc' => '',
        'm_parents' => array(10716,10810,3311,11158,1278),
    ),
    11638 => array(
        'm_icon' => '<i class="far fa-bat yellow"></i>',
        'm_name' => 'KRISTINWONG5',
        'm_desc' => '',
        'm_parents' => array(10716,3311,11158,1278),
    ),
    11249 => array(
        'm_icon' => '<i class="fas fa-badger-honey blue"></i>',
        'm_name' => 'MINUTESMAG',
        'm_desc' => '',
        'm_parents' => array(11965,11105,10716,3311,11158,1278),
    ),
    11167 => array(
        'm_icon' => '<i class="far fa-elephant ispink"></i>',
        'm_name' => 'NAUTILUSMAG',
        'm_desc' => '',
        'm_parents' => array(10845,10846,10114,10716,11123,11131,11150,10753,3311,11158,1278),
    ),
    11641 => array(
        'm_icon' => '<i class="fal fa-hippo blue"></i>',
        'm_name' => 'PAULJALVAREZ',
        'm_desc' => '',
        'm_parents' => array(10716,3311,11158,1278),
    ),
    11525 => array(
        'm_icon' => '<i class="fas fa-narwhal yellow"></i>',
        'm_name' => 'THEATLANTIC',
        'm_desc' => '',
        'm_parents' => array(10846,10716,11130,3311,11158,1278),
    ),
    11642 => array(
        'm_icon' => '<i class="fal fa-frog yellow"></i>',
        'm_name' => 'THEMOTLEYFOOL',
        'm_desc' => '',
        'm_parents' => array(10716,3311,11158,1278),
    ),
    11639 => array(
        'm_icon' => '<i class="far fa-deer ismatt"></i>',
        'm_name' => 'TIMDENNING',
        'm_desc' => '',
        'm_parents' => array(11962,11959,10797,10913,11098,11118,10777,10716,3311,11158,1278),
    ),
);

//BUSINESS:
$config['en_ids_10712'] = array(10735,10736,10737,10738,10739,10740,10741,10742,10743,10744,10745,7325,10748);
$config['en_all_10712'] = array(
    10735 => array(
        'm_icon' => '<i class="far fa-box-usd"></i>',
        'm_name' => 'BUSINESS FINANCE',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    10736 => array(
        'm_icon' => '<i class="far fa-lightbulb-dollar"></i>',
        'm_name' => 'ENTREPRENEURSHIP',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    10737 => array(
        'm_icon' => '<i class="far fa-comments-alt"></i>',
        'm_name' => 'COMMUNICATIONS',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    10738 => array(
        'm_icon' => '<i class="far fa-piggy-bank"></i>',
        'm_name' => 'MANAGEMENT',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    10739 => array(
        'm_icon' => '<i class="far fa-briefcase"></i>',
        'm_name' => 'SALES',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    10740 => array(
        'm_icon' => '<i class="far fa-bullseye"></i>',
        'm_name' => 'STRATEGY',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    10741 => array(
        'm_icon' => '<i class="far fa-calculator"></i>',
        'm_name' => 'OPERATIONS',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    10742 => array(
        'm_icon' => '<i class="far fa-sitemap"></i>',
        'm_name' => 'PROJECT MANAGEMENT',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    10743 => array(
        'm_icon' => '<i class="far fa-balance-scale"></i>',
        'm_name' => 'BUSINESS LAW',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    10744 => array(
        'm_icon' => '<i class="far fa-analytics"></i>',
        'm_name' => 'DATA & ANALYTICS',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    10745 => array(
        'm_icon' => '<i class="far fa-home"></i>',
        'm_name' => 'HOME BUSINESS',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    7325 => array(
        'm_icon' => '<i class="far fa-users"></i>',
        'm_name' => 'HUMAN RESOURCES',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
    10748 => array(
        'm_icon' => '<i class="far fa-hotel"></i>',
        'm_name' => 'REAL ESTATE',
        'm_desc' => '',
        'm_parents' => array(10712),
    ),
);

//SELF:
$config['en_ids_10711'] = array(10769,10770,10771,10772,7392,10773,10774,10775,10776,10777,10778,10779,10780,10781,10782,10783);
$config['en_all_10711'] = array(
    10769 => array(
        'm_icon' => '<i class="far fa-head-side-medical"></i>',
        'm_name' => 'PERSONAL TRANSFORMATION',
        'm_desc' => '',
        'm_parents' => array(10711),
    ),
    10770 => array(
        'm_icon' => '<i class="far fa-user-chart" aria-hidden="true"></i>',
        'm_name' => 'PRODUCTIVITY TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10711),
    ),
    10771 => array(
        'm_icon' => '<i class="far fa-mountain" aria-hidden="true"></i>',
        'm_name' => 'LEADERSHIP TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10711),
    ),
    10772 => array(
        'm_icon' => '<i class="far fa-wallet"></i>',
        'm_name' => 'PERSONAL FINANCE',
        'm_desc' => '',
        'm_parents' => array(10711),
    ),
    7392 => array(
        'm_icon' => '<i class="far fa-user-tie"></i>',
        'm_name' => 'CAREER DEVELOPMENT',
        'm_desc' => '',
        'm_parents' => array(10711),
    ),
    10773 => array(
        'm_icon' => '<i class="far fa-hands-helping" aria-hidden="true"></i>',
        'm_name' => 'PARENTING TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10711),
    ),
    10774 => array(
        'm_icon' => '<i class="far fa-hand-holding-heart"></i>',
        'm_name' => 'HAPPINESS',
        'm_desc' => '',
        'm_parents' => array(10711),
    ),
    10775 => array(
        'm_icon' => '<i class="far fa-pray" aria-hidden="true"></i>',
        'm_name' => 'SPIRITUALITY TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10711),
    ),
    10776 => array(
        'm_icon' => '<i class="far fa-user-circle"></i>',
        'm_name' => 'PERSONAL BRAND BUILDING',
        'm_desc' => '',
        'm_parents' => array(10711),
    ),
    10777 => array(
        'm_icon' => '<i class="far fa-lightbulb-on" aria-hidden="true"></i>',
        'm_name' => 'CREATIVITY TOPIC',
        'm_desc' => '',
        'm_parents' => array(4305,3311,11097,10721,10711),
    ),
    10778 => array(
        'm_icon' => '<i class="far fa-expand-arrows"></i>',
        'm_name' => 'INFLUENCE',
        'm_desc' => '',
        'm_parents' => array(10711),
    ),
    10779 => array(
        'm_icon' => '<i class="far fa-grin-hearts" aria-hidden="true"></i>',
        'm_name' => 'SELF TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10711),
    ),
    10780 => array(
        'm_icon' => '<i class="far fa-user-clock"></i>',
        'm_name' => 'STRESS MANAGEMENT',
        'm_desc' => '',
        'm_parents' => array(10711),
    ),
    10781 => array(
        'm_icon' => '<i class="far fa-head-side-brain"></i>',
        'm_name' => 'MEMORY & STUDY SKILLS',
        'm_desc' => '',
        'm_parents' => array(10711),
    ),
    10782 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'MOTIVATION',
        'm_desc' => '',
        'm_parents' => array(10711),
    ),
    10783 => array(
        'm_icon' => '<i class="far fa-star-christmas"></i>',
        'm_name' => 'OTHER PERSONAL DEVELOPMENT',
        'm_desc' => '',
        'm_parents' => array(10711),
    ),
);

//SOFTWARE:
$config['en_ids_10710'] = array(10717,10726,10727,10728,10729,10730,10731,10733,10734);
$config['en_all_10710'] = array(
    10717 => array(
        'm_icon' => '<i class="fas fa-desktop" aria-hidden="true"></i>',
        'm_name' => 'IT',
        'm_desc' => '',
        'm_parents' => array(10710,4527),
    ),
    10726 => array(
        'm_icon' => '<i class="far fa-browser"></i>',
        'm_name' => 'WEB DEVELOPMENT',
        'm_desc' => '',
        'm_parents' => array(10710),
    ),
    10727 => array(
        'm_icon' => '<i class="far fa-mobile"></i>',
        'm_name' => 'MOBILE APPS',
        'm_desc' => '',
        'm_parents' => array(10710),
    ),
    10728 => array(
        'm_icon' => '<i class="far fa-file-code"></i>',
        'm_name' => 'PROGRAMMING LANGUAGES',
        'm_desc' => '',
        'm_parents' => array(10710),
    ),
    10729 => array(
        'm_icon' => '<i class="far fa-gamepad"></i>',
        'm_name' => 'GAME DEVELOPMENT',
        'm_desc' => '',
        'm_parents' => array(10710),
    ),
    10730 => array(
        'm_icon' => '<i class="far fa-database"></i>',
        'm_name' => 'DATABASES',
        'm_desc' => '',
        'm_parents' => array(10710),
    ),
    10731 => array(
        'm_icon' => '<i class="far fa-laptop-code"></i>',
        'm_name' => 'SOFTWARE TESTING',
        'm_desc' => '',
        'm_parents' => array(10710),
    ),
    10733 => array(
        'm_icon' => '<i class="far fa-phone-laptop"></i>',
        'm_name' => 'DEVELOPMENT TOOLS',
        'm_desc' => '',
        'm_parents' => array(10710),
    ),
    10734 => array(
        'm_icon' => '<i class="far fa-shopping-cart"></i>',
        'm_name' => 'E-COMMERCE',
        'm_desc' => '',
        'm_parents' => array(10710),
    ),
);

//ARTS/FUN:
$config['en_ids_10809'] = array(10811,11958,10907,10908,10909,8866,10813,10910,10724,10722,2999,10911,10797,10826,10227,10912,10913);
$config['en_all_10809'] = array(
    10811 => array(
        'm_icon' => '<i class="far fa-lips" aria-hidden="true"></i>',
        'm_name' => 'BEAUTY TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809,10721),
    ),
    11958 => array(
        'm_icon' => '',
        'm_name' => 'BOOKS TOPIC',
        'm_desc' => '',
        'm_parents' => array(10809,3311,11097),
    ),
    10907 => array(
        'm_icon' => '',
        'm_name' => 'COMICS TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809),
    ),
    10908 => array(
        'm_icon' => '',
        'm_name' => 'CULTURE TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809),
    ),
    10909 => array(
        'm_icon' => '',
        'm_name' => 'FICTION TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809),
    ),
    8866 => array(
        'm_icon' => '',
        'm_name' => 'FILM TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809,5008),
    ),
    10813 => array(
        'm_icon' => '<i class="far fa-dice" aria-hidden="true"></i>',
        'm_name' => 'GAMING TOPIC',
        'm_desc' => '',
        'm_parents' => array(4305,3311,11097,10809,10721),
    ),
    10910 => array(
        'm_icon' => '',
        'm_name' => 'HUMOR TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809),
    ),
    10724 => array(
        'm_icon' => '<i class="far fa-music" aria-hidden="true"></i>',
        'm_name' => 'MUSIC TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809),
    ),
    10722 => array(
        'm_icon' => '<i class="far fa-camera-retro" aria-hidden="true"></i>',
        'm_name' => 'PHOTOGRAPHY TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809),
    ),
    2999 => array(
        'm_icon' => '<i class="far fa-microphone" aria-hidden="true"></i>',
        'm_name' => 'PODCASTS',
        'm_desc' => '',
        'm_parents' => array(10809,10571,4983,7614,6805,3000),
    ),
    10911 => array(
        'm_icon' => '',
        'm_name' => 'POETRY TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809),
    ),
    10797 => array(
        'm_icon' => '<i class="far fa-share-alt" aria-hidden="true"></i>',
        'm_name' => 'SOCIAL MEDIA TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809),
    ),
    10826 => array(
        'm_icon' => '<i class="far fa-futbol"></i>',
        'm_name' => 'SPORTS',
        'm_desc' => '',
        'm_parents' => array(10809),
    ),
    10227 => array(
        'm_icon' => '',
        'm_name' => 'STYLE TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809,5008),
    ),
    10912 => array(
        'm_icon' => '',
        'm_name' => 'TV TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809),
    ),
    10913 => array(
        'm_icon' => '',
        'm_name' => 'WRITING TOPIC',
        'm_desc' => '',
        'm_parents' => array(3311,11097,10809),
    ),
);

//INDUSTRY:
$config['en_ids_10746'] = array();
$config['en_all_10746'] = array(
);

//TOPIC:
$config['en_ids_10869'] = array(12066,10809,10746,10725,10721,10720,10719,10718,10716,10712,10711,10710);
$config['en_all_10869'] = array(
    12066 => array(
        'm_icon' => '<i class="far fa-info-circle" aria-hidden="true"></i>',
        'm_name' => 'ABOUT US',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10809 => array(
        'm_icon' => '<i class="fas fa-palette mench-spin" aria-hidden="true"></i>',
        'm_name' => 'ARTS/FUN',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10746 => array(
        'm_icon' => '<i class="fas fa-industry"></i>',
        'm_name' => 'INDUSTRY',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10725 => array(
        'm_icon' => '<i class="fas fa-atom-alt mench-spin" aria-hidden="true"></i>',
        'm_name' => 'ACADEMICS',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10721 => array(
        'm_icon' => '<i class="fas fa-hand-peace mench-spin" aria-hidden="true"></i>',
        'm_name' => 'LIFESTYLE',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10720 => array(
        'm_icon' => '<i class="fas fa-bullseye-arrow mench-spin" aria-hidden="true"></i>',
        'm_name' => 'MARKETING',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10719 => array(
        'm_icon' => '<i class="fas fa-pencil-ruler mench-spin" aria-hidden="true"></i>',
        'm_name' => 'DESIGN',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10718 => array(
        'm_icon' => '<i class="fas fa-clipboard-list-check" aria-hidden="true"></i>',
        'm_name' => 'PRODUCTIVITY',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10716 => array(
        'm_icon' => '<i class="fas fa-usd-circle mench-spin" aria-hidden="true"></i>',
        'm_name' => 'FINANCE',
        'm_desc' => '',
        'm_parents' => array(3311,11097,4527,10869),
    ),
    10712 => array(
        'm_icon' => '<i class="fas fa-chart-line" aria-hidden="true"></i>',
        'm_name' => 'BUSINESS',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10711 => array(
        'm_icon' => '<i class="fas fa-yin-yang mench-spin" aria-hidden="true"></i>',
        'm_name' => 'SELF',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10710 => array(
        'm_icon' => '<i class="fas fa-code" aria-hidden="true"></i>',
        'm_name' => 'SOFTWARE',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
);

//PLAYER SUBSCRIPTION LEVEL:
$config['en_ids_11007'] = array(11010,11011,11012,12223);
$config['en_all_11007'] = array(
    11010 => array(
        'm_icon' => '<i class="fas fa-check-circle" aria-hidden="true"></i>',
        'm_name' => '100 READS/WEEK ALWAYS FREE',
        'm_desc' => '',
        'm_parents' => array(11061,11007),
    ),
    11011 => array(
        'm_icon' => '<i class="fas fa-usd-circle blue" aria-hidden="true"></i>',
        'm_name' => 'UNLIMITED USD $5/MONTH = $1.15/WEEK',
        'm_desc' => '',
        'm_parents' => array(11162,11007),
    ),
    11012 => array(
        'm_icon' => '<i class="fas fa-usd-circle yellow" aria-hidden="true"></i>',
        'm_name' => 'UNLIMITED USD $50/YEAR = $0.96/WEEK [SAVE 20%]',
        'm_desc' => '',
        'm_parents' => array(11163,11007),
    ),
    12223 => array(
        'm_icon' => '<i class="fas fa-heart-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'UNLIMITED FREE IF LOW-INCOME/NON-PROFIT',
        'm_desc' => '',
        'm_parents' => array(11007),
    ),
);

//SHOW PLAY TAB NAMES:
$config['en_ids_11084'] = array(11029,11030);
$config['en_all_11084'] = array(
    11029 => array(
        'm_icon' => '<i class="fas fa-hand-holding-seedling blue" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => '',
        'm_parents' => array(11084,11089,11028),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-id-badge blue" aria-hidden="true"></i>',
        'm_name' => 'PROFILE',
        'm_desc' => '',
        'm_parents' => array(11084,11033,11028),
    ),
);

//PLAY PROFILE TABS:
$config['en_ids_11033'] = array(11030,6146,7347,10573,4983,4231,4601);
$config['en_all_11033'] = array(
    11030 => array(
        'm_icon' => '<i class="fas fa-id-badge blue" aria-hidden="true"></i>',
        'm_name' => 'PROFILE',
        'm_desc' => '',
        'm_parents' => array(11084,11033,11028),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-eye ispink" aria-hidden="true"></i>',
        'm_name' => 'READ COMPLETE',
        'm_desc' => '',
        'm_parents' => array(11018,11033,10964,6771,4527),
    ),
    7347 => array(
        'm_icon' => '<i class="fas fa-play ispink" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => '',
        'm_parents' => array(2992,11033,10964,6771,4527),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(11018,10984,11033,10589,4593,7551,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG AUTHORS',
        'm_desc' => '',
        'm_parents' => array(11018,11033,10593,10589,4527,7551,4985,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11033,10990,10593,10589,6345,4986,4603,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(11018,11033,4986,10593,10589,4593,4485),
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
        'm_name' => 'CHILD PLAYER',
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
        'm_name' => 'PARENT PLAYER',
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
        'm_icon' => '<i class="far fa-user-edit" aria-hidden="true"></i>',
        'm_name' => 'PLAYER',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//PLATFORM VARIABLES:
$config['en_ids_6232'] = array(6202,4486,6159,4356,4737,4736,7585,6207,6203,6208,6168,6283,6228,6165,6162,6170,6161,6169,6167,6197,6198,6160,6172,6177,4369,4429,7694,4367,4358,4372,6103,4368,4366,4371,4364,4370,6186,4362,4593,4739,4735);
$config['en_all_6232'] = array(
    6202 => array(
        'm_icon' => '<i class="fas fa-plus-circle yellow"></i>',
        'm_name' => 'BLOG ID',
        'm_desc' => 'in_id',
        'm_parents' => array(6232,6215,6201),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINKS',
        'm_desc' => 'ln_type_play_id',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG METADATA',
        'm_desc' => 'in_metadata',
        'm_parents' => array(11049,6232,6201,6195),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG READ TIME',
        'm_desc' => 'in_read_time',
        'm_parents' => array(10888,10650,6232,6201),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS',
        'm_desc' => 'in_status_play_id',
        'm_parents' => array(12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG TITLE',
        'm_desc' => 'in_title',
        'm_parents' => array(12112,11071,10644,6232,6201),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE',
        'm_desc' => 'in_type_play_id',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
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
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'INTENT METADATA MAXIMUM SECONDS',
        'm_desc' => 'in__metadata_max_seconds',
        'm_parents' => array(6232,6214,4356,6159),
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
        'm_parents' => array(6232,6214,4356,6159),
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
    6197 => array(
        'm_icon' => '<i class="fas fa-fingerprint" aria-hidden="true"></i>',
        'm_name' => 'PLAYER FULL NAME',
        'm_desc' => 'en_name',
        'm_parents' => array(6225,11072,10646,5000,4998,4999,6232,6206),
    ),
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle isblue"></i>',
        'm_name' => 'PLAYER ICON',
        'm_desc' => 'en_icon',
        'm_parents' => array(10653,5943,10625,6232,6206),
    ),
    6160 => array(
        'm_icon' => '<i class="fas fa-user-circle blue"></i>',
        'm_name' => 'PLAYER ID',
        'm_desc' => 'en_id',
        'm_parents' => array(6232,6215,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda isblue"></i>',
        'm_name' => 'PLAYER METADATA',
        'm_desc' => 'en_metadata',
        'm_parents' => array(11044,6232,3323,6206,6195),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h isblue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER STATUS',
        'm_desc' => 'en_status_play_id',
        'm_parents' => array(11054,7303,6204,5003,10654,6160,6232,6194,6206,4527),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-hashtag" aria-hidden="true"></i>',
        'm_name' => 'READ CHILD BLOG',
        'm_desc' => 'ln_child_blog_id',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'READ CHILD PLAYER',
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
        'm_icon' => '<i class="far fa-coin" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => 'tr__assessment_points',
        'm_parents' => array(12112,10984,10663,6103,6410,6232),
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
        'm_name' => 'READ PARENT PLAYER',
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
        'm_icon' => '<i class="far fa-user-edit" aria-hidden="true"></i>',
        'm_name' => 'READ PLAYER',
        'm_desc' => 'ln_creator_play_id',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fas fa-sort"></i>',
        'm_name' => 'READ RANK',
        'm_desc' => 'ln_order',
        'm_parents' => array(10676,10675,6232,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => 'ln_status_play_id',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="far fa-clock" aria-hidden="true"></i>',
        'm_name' => 'READ TIME',
        'm_desc' => 'ln_timestamp',
        'm_parents' => array(12112,6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE',
        'm_desc' => 'ln_type_play_id',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
    4739 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX SCORE',
        'm_desc' => 'tr__conditional_score_max',
        'm_parents' => array(12112,6402,6232),
    ),
    4735 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN SCORE',
        'm_desc' => 'tr__conditional_score_min',
        'm_parents' => array(12112,6402,6232),
    ),
);

//MEDIA FILE EXTENSIONS:
$config['en_ids_11080'] = array(4259,4261,4260,4258);
$config['en_all_11080'] = array(
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up blue"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'pcm|wav|aiff|mp3|aac|ogg|wma|flac|alac|m4a|m4b|m4p',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf blue"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'pdc|doc|docx|tex|txt|7z|rar|zip|csv|sql|tar|xml|exe',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image blue" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'jpeg|jpg|png|gif|tiff|bmp|img|svg|ico|webp',
        'm_parents' => array(6198,11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video blue"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'mp4|m4v|m4p|avi|mov|flv|f4v|f4p|f4a|f4b|wmv|webm|mkv|vob|ogv|ogg|3gp|mpg|mpeg|m2v',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
);

//MESSENGER MEDIA CODES:
$config['en_ids_11059'] = array(4259,4261,4260,4258);
$config['en_all_11059'] = array(
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up blue"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'audio',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf blue"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'file',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image blue" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'image',
        'm_parents' => array(6198,11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video blue"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'video',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
);

//MESSENGER NOTIFICATION CODES:
$config['en_ids_11058'] = array(4458,4456,4457);
$config['en_all_11058'] = array(
    4458 => array(
        'm_icon' => '<i class="far fa-volume-mute" aria-hidden="true"></i>',
        'm_name' => 'DISABLED',
        'm_desc' => 'NO_PUSH',
        'm_parents' => array(11058,4454),
    ),
    4456 => array(
        'm_icon' => '<i class="far fa-volume-up" aria-hidden="true"></i>',
        'm_name' => 'REGULAR',
        'm_desc' => 'REGULAR',
        'm_parents' => array(11058,4454),
    ),
    4457 => array(
        'm_icon' => '<i class="far fa-volume-down" aria-hidden="true"></i>',
        'm_name' => 'SILENT',
        'm_desc' => 'SILENT_PUSH',
        'm_parents' => array(11058,4454),
    ),
);

//PLATFORM CONFIG VARIABLES:
$config['en_ids_6404'] = array(12176,10939,12156,11071,12210,12130,11077,11074,12124,11076,11075,11064,11985,11986,11065,11063,11060,11079,11073,11066,11072,11057,11056,12113,12088,11061,11162,11163,12209,12208);
$config['en_all_6404'] = array(
    12176 => array(
        'm_icon' => '',
        'm_name' => 'BLOG DEFAULT TIME',
        'm_desc' => '29',
        'm_parents' => array(6404),
    ),
    10939 => array(
        'm_icon' => '<i class="fas fa-pen-square yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOGGER',
        'm_desc' => '13008',
        'm_parents' => array(12201,6404,10957),
    ),
    12156 => array(
        'm_icon' => '',
        'm_name' => 'BLOG NORTH STAR',
        'm_desc' => '7766',
        'm_parents' => array(6404),
    ),
    11071 => array(
        'm_icon' => '',
        'm_name' => 'BLOG TITLE MAX LENGTH',
        'm_desc' => '89',
        'm_parents' => array(6404),
    ),
    12210 => array(
        'm_icon' => '',
        'm_name' => 'COINS REFRESH MILLISECONDS BLOGGER',
        'm_desc' => '2584',
        'm_parents' => array(6404),
    ),
    12130 => array(
        'm_icon' => '',
        'm_name' => 'COINS REFRESH MILLISECONDS READER',
        'm_desc' => '121393',
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
    11985 => array(
        'm_icon' => '',
        'm_name' => 'LEADERBOARD TOTAL',
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
        'm_name' => 'MAGIC LINK VALID SECONDS',
        'm_desc' => '3600',
        'm_parents' => array(6404),
    ),
    11063 => array(
        'm_icon' => '',
        'm_name' => 'MAX FILE SIZE [MB]',
        'm_desc' => '25',
        'm_parents' => array(6404),
    ),
    11060 => array(
        'm_icon' => '',
        'm_name' => 'MENCH PLATFORM VERSION',
        'm_desc' => '1.2020',
        'm_parents' => array(6404),
    ),
    11079 => array(
        'm_icon' => '',
        'm_name' => 'MENCH TIMEZONE',
        'm_desc' => 'America/Los_Angeles',
        'm_parents' => array(6404),
    ),
    11073 => array(
        'm_icon' => '',
        'm_name' => 'MESSAGE MAX LENGTH',
        'm_desc' => '610',
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
        'm_name' => 'PLAYER NAME MAX LENGTH',
        'm_desc' => '233',
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
    12113 => array(
        'm_icon' => '',
        'm_name' => 'READ TIME MAX',
        'm_desc' => '5400',
        'm_parents' => array(4362,6404),
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
        'm_desc' => '100',
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
);

//PLATFORM MEMORY JAVASCRIPT:
$config['en_ids_11054'] = array(4486,4737,7356,7355,6201,7585,6404,6177,6186);
$config['en_all_11054'] = array(
    4486 => array(
        'm_icon' => '<i class="fas fa-link yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINKS',
        'm_desc' => '',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'BLOG STATUSES ACTIVE',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUSES PUBLIC',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    6201 => array(
        'm_icon' => '<i class="far fa-table yellow"></i>',
        'm_name' => 'BLOG TABLE',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7735,4535),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE',
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
        'm_icon' => '<i class="fas fa-sliders-h isblue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,7303,6204,5003,10654,6160,6232,6194,6206,4527),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
);

//BLOG ADMIN MENU:
$config['en_ids_11047'] = array(11051,11049,11050,11048);
$config['en_all_11047'] = array(
    11051 => array(
        'm_icon' => '<i class="fas fa-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'FULL READ HISTORY',
        'm_desc' => '/read/history?any_in_id=',
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
        'm_desc' => '/read/cron__sync_algolia/in/',
        'm_parents' => array(7279,11047),
    ),
    11048 => array(
        'm_icon' => '<i class="far fa-magic" aria-hidden="true"></i>',
        'm_name' => 'UPDATE REFERENCE CACHE',
        'm_desc' => '/blog/cron__sync_extra_insights/',
        'm_parents' => array(11047),
    ),
);

//PLAY ADMIN MENU:
$config['en_ids_11039'] = array(11044,11045,11046);
$config['en_all_11039'] = array(
    11044 => array(
        'm_icon' => '<i class="fas fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'PLAYER METADATA',
        'm_desc' => '/play/en_review_metadata/',
        'm_parents' => array(11039),
    ),
    11045 => array(
        'm_icon' => '<img src="https://partners.algolia.com/images/logos/algolia-logo-badge.svg">',
        'm_name' => 'PLAYER SYNC ALGOLIA',
        'm_desc' => '/read/cron__sync_algolia/en/',
        'm_parents' => array(7279,11039),
    ),
    11046 => array(
        'm_icon' => '<i class="fad fa-atlas ispink" aria-hidden="true"></i>',
        'm_name' => 'READ HISTORY',
        'm_desc' => '/read/history?any_en_id=',
        'm_parents' => array(11035,11039),
    ),
);

//MENCH NAVIGATION:
$config['en_ids_11035'] = array(12215,12201,7291,12213,6225,12205,12212,12214,12211,6287,11046,7256,4269,7540,11087);
$config['en_all_11035'] = array(
    12215 => array(
        'm_icon' => '<i class="fad fa-atlas yellow"></i>',
        'm_name' => 'BLOG HISTORY',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    12201 => array(
        'm_icon' => '<i class="fad fa-plus ispink" aria-hidden="true"></i>',
        'm_name' => 'BROWSE READS',
        'm_desc' => '',
        'm_parents' => array(11035,4527,4536),
    ),
    7291 => array(
        'm_icon' => '<i class="fad fa-power-off" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    12213 => array(
        'm_icon' => '<i class="fas fa-circle yellow"></i>',
        'm_name' => 'MY BLOGS',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    6225 => array(
        'm_icon' => '<i class="fas fa-circle blue"></i>',
        'm_name' => 'MY PLAYER',
        'm_desc' => 'Manage avatar, superpowers, subscription & name',
        'm_parents' => array(11035,4758,4527),
    ),
    12205 => array(
        'm_icon' => '<i class="fad fa-user-circle blue" aria-hidden="true"></i>',
        'm_name' => 'MY PUBLIC PROFILE',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    12212 => array(
        'm_icon' => '<i class="fas fa-circle ispink"></i>',
        'm_name' => 'MY READS',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    12214 => array(
        'm_icon' => '<i class="fad fa-plus yellow" aria-hidden="true"></i>',
        'm_name' => 'NEW BLOG',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    12211 => array(
        'm_icon' => '<i class="fad fa-arrow-right ispink" aria-hidden="true"></i>',
        'm_name' => 'NEXT READ',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-tools" aria-hidden="true"></i>',
        'm_name' => 'PRO TOOLS',
        'm_desc' => '',
        'm_parents' => array(11035,4527,7284),
    ),
    11046 => array(
        'm_icon' => '<i class="fad fa-atlas ispink" aria-hidden="true"></i>',
        'm_name' => 'READ HISTORY',
        'm_desc' => '',
        'm_parents' => array(11035,11039),
    ),
    7256 => array(
        'm_icon' => '<i class="fad fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCH MENCH',
        'm_desc' => '',
        'm_parents' => array(11993,11035,3323),
    ),
    4269 => array(
        'm_icon' => '<i class="fad fa-sign-in-alt" aria-hidden="true"></i>',
        'm_name' => 'SIGN IN/UP',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    7540 => array(
        'm_icon' => '<i class="fad fa-university" aria-hidden="true"></i>',
        'm_name' => 'TERMS OF SERVICE',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    11087 => array(
        'm_icon' => '<i class="fad fa-users-crown blue" aria-hidden="true"></i>',
        'm_name' => 'TOP PLAYERS',
        'm_desc' => '',
        'm_parents' => array(4758,11035),
    ),
);

//SHOW BLOG TAB NAMES:
$config['en_ids_11031'] = array();
$config['en_all_11031'] = array(
);

//PLAYERS LINKS DIRECTION:
$config['en_ids_11028'] = array(11030,11029);
$config['en_all_11028'] = array(
    11030 => array(
        'm_icon' => '<i class="fas fa-id-badge blue" aria-hidden="true"></i>',
        'm_name' => 'PROFILE',
        'm_desc' => 'Describe PLAYER. Where it comes from. It\'s origin.',
        'm_parents' => array(11084,11033,11028),
    ),
    11029 => array(
        'm_icon' => '<i class="fas fa-hand-holding-seedling blue" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => 'What the PLAYER chooses to focus on. It\'s work. It\'s responsibility.',
        'm_parents' => array(11084,11089,11028),
    ),
);

//BLOG 2ND GROUP:
$config['en_ids_11018'] = array(11019,11020,4601,4983,10573,11161,6146,7545,11047);
$config['en_all_11018'] = array(
    11019 => array(
        'm_icon' => '<i class="fas fa-fast-backward yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG PREVIOUS',
        'm_desc' => 'BLOGS that recommended this blog once READ',
        'm_parents' => array(11018),
    ),
    11020 => array(
        'm_icon' => '<i class="fas fa-fast-forward yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NEXT',
        'm_desc' => 'BLOGS to READ after this BLOG',
        'm_parents' => array(11018),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG KEYWORDS',
        'm_desc' => 'Alternative terms, keywords and verbs that describe this BLOG. Also used to feature blog.',
        'm_parents' => array(11018,11033,4986,10593,10589,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG AUTHORS',
        'm_desc' => 'BLOGGERS who are managing this blog and also referenced authors of the original content.',
        'm_parents' => array(11018,11033,10593,10589,4527,7551,4985,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG BOOKMARKS',
        'm_desc' => 'BLOGGERS who bookmarked this BLOG.',
        'm_parents' => array(11018,10984,11033,10589,4593,7551,4485),
    ),
    11161 => array(
        'm_icon' => '<i class="fas fa-tools yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG MASS UPDATE',
        'm_desc' => '',
        'm_parents' => array(10984,11018,11160),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-eye ispink" aria-hidden="true"></i>',
        'm_name' => 'READ COMPLETE',
        'm_desc' => '',
        'm_parents' => array(11018,11033,10964,6771,4527),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-user-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAYER TAGS',
        'm_desc' => '',
        'm_parents' => array(11018,11089,10967,10589,7551,4593,4485),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down"></i>',
        'm_name' => 'BLOG ADMIN MENU',
        'm_desc' => '',
        'm_parents' => array(11018,10984,4527,11040),
    ),
);

//BLOG TAB GROUPS:
$config['en_ids_11021'] = array(10990,11018);
$config['en_all_11021'] = array(
    10990 => array(
        'm_icon' => '<i class="fas fa-toolbox yellow" aria-hidden="true"></i>',
        'm_name' => '1ST GROUP',
        'm_desc' => '',
        'm_parents' => array(11021,4527),
    ),
    11018 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90 yellow" aria-hidden="true"></i>',
        'm_name' => '2ND GROUP',
        'm_desc' => '',
        'm_parents' => array(4527,11025,11021),
    ),
);

//BLOG 1ST GROUP:
$config['en_ids_10990'] = array(4231);
$config['en_all_10990'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => 'READ over web or Messenger',
        'm_parents' => array(11033,10990,10593,10589,6345,4986,4603,4593,4485),
    ),
);

//PLAYER SUPERPOWERS:
$config['en_ids_10957'] = array(10939,10984,10964,10983,10967);
$config['en_all_10957'] = array(
    10939 => array(
        'm_icon' => '<i class="fas fa-pen-square yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOGGER',
        'm_desc' => 'THE POWER TO BLOG',
        'm_parents' => array(12201,6404,10957),
    ),
    10984 => array(
        'm_icon' => '<i class="far fa-deer-rudolph yellow" aria-hidden="true"></i>',
        'm_name' => 'RUDOLPH',
        'm_desc' => 'THE POWER TO BLOG PROFESSIONALLY',
        'm_parents' => array(10957),
    ),
    10964 => array(
        'm_icon' => '<i class="far fa-elephant ispink" aria-hidden="true"></i>',
        'm_name' => 'DUMBO',
        'm_desc' => 'THE POWER TO READ',
        'm_parents' => array(10957),
    ),
    10983 => array(
        'm_icon' => '<i class="far fa-narwhal blue" aria-hidden="true"></i>',
        'm_name' => 'NARWHAL',
        'm_desc' => 'THE POWER TO PLAY',
        'm_parents' => array(10957),
    ),
    10967 => array(
        'm_icon' => '<i class="far fa-bat blue" aria-hidden="true"></i>',
        'm_name' => 'BATMAN',
        'm_desc' => 'THE POWER TO PLAY PROFESSIONALLY',
        'm_parents' => array(10957),
    ),
);

//PLAYER AVATAR:
$config['en_ids_10956'] = array(10965,10979,10978,10963,10966,12207,10976,10962,10975,10982,10970,10972,10969,10959,10960,10981,10968,10974,12206,10958);
$config['en_all_10956'] = array(
    10965 => array(
        'm_icon' => '<i class="far fa-dog blue"></i>',
        'm_name' => 'DOGY',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10979 => array(
        'm_icon' => '<i class="far fa-duck blue"></i>',
        'm_name' => 'DONALD',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10978 => array(
        'm_icon' => '<i class="far fa-fish blue"></i>',
        'm_name' => 'FISHY',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10963 => array(
        'm_icon' => '<i class="far fa-hippo blue"></i>',
        'm_name' => 'HIPPOY',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10966 => array(
        'm_icon' => '<i class="far fa-deer blue"></i>',
        'm_name' => 'HONEY',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    12207 => array(
        'm_icon' => '<i class="far fa-badger-honey blue"></i>',
        'm_name' => 'HONEY BADGER',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10976 => array(
        'm_icon' => '<i class="far fa-horse blue"></i>',
        'm_name' => 'HORSY',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10962 => array(
        'm_icon' => '<i class="far fa-monkey blue"></i>',
        'm_name' => 'HUMAN',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10975 => array(
        'm_icon' => '<i class="far fa-kiwi-bird blue"></i>',
        'm_name' => 'KIWI',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10982 => array(
        'm_icon' => '<i class="far fa-cat blue"></i>',
        'm_name' => 'MIMY',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10970 => array(
        'm_icon' => '<i class="far fa-cow blue"></i>',
        'm_name' => 'MOMY',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10972 => array(
        'm_icon' => '<i class="far fa-turtle blue"></i>',
        'm_name' => 'NINJA',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10969 => array(
        'm_icon' => '<i class="far fa-pig blue"></i>',
        'm_name' => 'PIGGY',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10959 => array(
        'm_icon' => '<i class="far fa-ram blue"></i>',
        'm_name' => 'RAMRAM',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10960 => array(
        'm_icon' => '<i class="far fa-rabbit blue"></i>',
        'm_name' => 'ROGER',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10981 => array(
        'm_icon' => '<i class="far fa-crow blue"></i>',
        'm_name' => 'RUSSEL',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10968 => array(
        'm_icon' => '<i class="far fa-sheep blue"></i>',
        'm_name' => 'SHEEPY',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10974 => array(
        'm_icon' => '<i class="far fa-snake blue"></i>',
        'm_name' => 'SNAKY',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    12206 => array(
        'm_icon' => '<i class="far fa-spider blue"></i>',
        'm_name' => 'SPIDER',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
    10958 => array(
        'm_icon' => '<i class="far fa-squirrel blue"></i>',
        'm_name' => 'SQUIRRELY',
        'm_desc' => '',
        'm_parents' => array(10956),
    ),
);

//MENCH:
$config['en_ids_2738'] = array(4536,6205,4535);
$config['en_all_2738'] = array(
    4536 => array(
        'm_icon' => '<i class="fas fa-circle blue" aria-hidden="true"></i>',
        'm_name' => 'PLAY',
        'm_desc' => 'a publishing game by earning crypto-coins as you read or blog. Customize your avatar, unlock superpowers and collaborate with other players.',
        'm_parents' => array(12155,2738,4463),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'READ',
        'm_desc' => 'microblogs interactively by choosing your unique reading path. Earn 1x READ COIN for each word you read over the web or Facebook Messenger.',
        'm_parents' => array(12155,2738,4463),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG',
        'm_desc' => 'ideas collaboratively by saving, organizing and publishing microblogs. Earn 1x BLOG COIN for each word you blog and generate monthly revenues.',
        'm_parents' => array(12155,2738,4463),
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
        'm_name' => 'CHILD PLAYER',
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
        'm_name' => 'PARENT PLAYER',
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
$config['en_ids_4527'] = array(12066,10725,7758,10809,12145,10990,11018,11047,4983,12079,10589,11968,4229,4486,4485,12012,7302,4737,7356,12138,7355,11021,6201,12112,7585,10602,7309,7712,7751,6150,12201,10712,10719,10627,10716,10746,7364,10717,10721,10720,11080,2738,12105,4463,11035,7555,11059,11058,6225,6404,4527,11054,6232,11039,6194,10956,3290,6827,4426,4997,12220,4454,4986,7551,11028,4537,6177,11007,10957,6206,3289,4592,11089,11033,3000,7303,11088,4755,10718,6287,10571,7357,6192,11081,6193,6146,6345,5967,4280,10570,4277,6102,7704,7494,10590,7347,6103,10692,6255,7304,6186,7360,7359,4341,4593,10593,10591,12141,10658,10711,11031,11084,6204,10710,6805,7358,10869);
$config['en_all_4527'] = array(
    12066 => array(
        'm_icon' => '<i class="far fa-info-circle" aria-hidden="true"></i>',
        'm_name' => 'ABOUT US',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10725 => array(
        'm_icon' => '<i class="fas fa-atom-alt mench-spin" aria-hidden="true"></i>',
        'm_name' => 'ACADEMICS',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    7758 => array(
        'm_icon' => '<i class="far fa-mountain"></i>',
        'm_name' => 'ACTION PLAN BLOGION SUCCESSFUL',
        'm_desc' => '',
        'm_parents' => array(4527,7493),
    ),
    10809 => array(
        'm_icon' => '<i class="fas fa-palette mench-spin" aria-hidden="true"></i>',
        'm_name' => 'ARTS/FUN',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    12145 => array(
        'm_icon' => '<i class="fas fa-dot-circle blue" aria-hidden="true"></i>',
        'm_name' => 'BEING',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
    10990 => array(
        'm_icon' => '<i class="fas fa-toolbox yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG 1ST GROUP',
        'm_desc' => '',
        'm_parents' => array(11021,4527),
    ),
    11018 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90 yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG 2ND GROUP',
        'm_desc' => '',
        'm_parents' => array(4527,11025,11021),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down"></i>',
        'm_name' => 'BLOG ADMIN MENU',
        'm_desc' => '',
        'm_parents' => array(11018,10984,4527,11040),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG AUTHORS',
        'm_desc' => '',
        'm_parents' => array(11018,11033,10593,10589,4527,7551,4985,4593,4485),
    ),
    12079 => array(
        'm_icon' => '<i class="fas fa-caret-down" aria-hidden="true"></i>',
        'm_name' => 'BLOG DROPDOWN INPUTS',
        'm_desc' => '',
        'm_parents' => array(6768,4527),
    ),
    10589 => array(
        'm_icon' => '<i class="fas fa-plus-circle yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOGGING',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
    11968 => array(
        'm_icon' => '<i class="far fa-lightbulb-on" aria-hidden="true"></i>',
        'm_name' => 'BLOGGING. REIMAGINED.',
        'm_desc' => '',
        'm_parents' => array(4527,4536),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-question-circle yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(10589,4527,6410,6283,4593,4486),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINKS',
        'm_desc' => '',
        'm_parents' => array(6232,12079,11054,10984,11025,10662,4527),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES',
        'm_desc' => '',
        'm_parents' => array(4535,4527,4463),
    ),
    12012 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTE STATUS',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    7302 => array(
        'm_icon' => '<i class="far fa-chart-bar yellow"></i>',
        'm_name' => 'BLOG STATS',
        'm_desc' => '',
        'm_parents' => array(4527,4535),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'BLOG STATUSES ACTIVE',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    12138 => array(
        'm_icon' => '<i class="far fa-megaphone" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUSES FEATURED',
        'm_desc' => '',
        'm_parents' => array(4527,10891),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUSES PUBLIC',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    11021 => array(
        'm_icon' => '<i class="fas fa-list-alt yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG TAB GROUPS',
        'm_desc' => '',
        'm_parents' => array(4527,4535),
    ),
    6201 => array(
        'm_icon' => '<i class="far fa-table yellow"></i>',
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
    7585 => array(
        'm_icon' => '<i class="fas fa-random yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE AND/OR',
        'm_desc' => '',
        'm_parents' => array(10893,6204,7302,4527),
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
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'BOOKMARK REMOVED',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    12201 => array(
        'm_icon' => '<i class="fad fa-plus ispink" aria-hidden="true"></i>',
        'm_name' => 'BROWSE READS',
        'm_desc' => '',
        'm_parents' => array(11035,4527,4536),
    ),
    10712 => array(
        'm_icon' => '<i class="fas fa-chart-line" aria-hidden="true"></i>',
        'm_name' => 'BUSINESS',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10719 => array(
        'm_icon' => '<i class="fas fa-pencil-ruler mench-spin" aria-hidden="true"></i>',
        'm_name' => 'DESIGN',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10627 => array(
        'm_icon' => '<i class="far fa-paperclip"></i>',
        'm_name' => 'FILE TYPE ATTACHMENT',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    10716 => array(
        'm_icon' => '<i class="fas fa-usd-circle mench-spin" aria-hidden="true"></i>',
        'm_name' => 'FINANCE',
        'm_desc' => '',
        'm_parents' => array(3311,11097,4527,10869),
    ),
    10746 => array(
        'm_icon' => '<i class="fas fa-industry"></i>',
        'm_name' => 'INDUSTRY',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    7364 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'INTERACTION STATUSES INCOMPLETE',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    10717 => array(
        'm_icon' => '<i class="fas fa-desktop" aria-hidden="true"></i>',
        'm_name' => 'IT',
        'm_desc' => '',
        'm_parents' => array(10710,4527),
    ),
    10721 => array(
        'm_icon' => '<i class="fas fa-hand-peace mench-spin" aria-hidden="true"></i>',
        'm_name' => 'LIFESTYLE',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    10720 => array(
        'm_icon' => '<i class="fas fa-bullseye-arrow mench-spin" aria-hidden="true"></i>',
        'm_name' => 'MARKETING',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    11080 => array(
        'm_icon' => '<i class="far fa-file"></i>',
        'm_name' => 'MEDIA FILE EXTENSIONS',
        'm_desc' => '',
        'm_parents' => array(7254,4527),
    ),
    2738 => array(
        'm_icon' => '<img src="/mench.png" class="mench-spin no-radius">',
        'm_name' => 'MENCH',
        'm_desc' => '',
        'm_parents' => array(12041,2792,3303,7524,3325,3326,3324,4527,1,7312,2750),
    ),
    12105 => array(
        'm_icon' => '<i class="fas fa-vote-yea"></i>',
        'm_name' => 'MENCH CHANNELS UPCOMING',
        'm_desc' => '',
        'm_parents' => array(4527,4758,6771),
    ),
    4463 => array(
        'm_icon' => '<i class="far fa-lightbulb-on" aria-hidden="true"></i>',
        'm_name' => 'MENCH GLOSSARY',
        'm_desc' => '',
        'm_parents' => array(4527,7254),
    ),
    11035 => array(
        'm_icon' => '<i class="fas fa-list"></i>',
        'm_name' => 'MENCH NAVIGATION',
        'm_desc' => '',
        'm_parents' => array(4527,7305),
    ),
    7555 => array(
        'm_icon' => '<i class="fas fa-paper-plane" aria-hidden="true"></i>',
        'm_name' => 'MENCH READING CHANNELS',
        'm_desc' => '',
        'm_parents' => array(7305,4527),
    ),
    11059 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger isblue"></i>',
        'm_name' => 'MESSENGER MEDIA CODES',
        'm_desc' => '',
        'm_parents' => array(6196,4527,7254),
    ),
    11058 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger isblue"></i>',
        'm_name' => 'MESSENGER NOTIFICATION CODES',
        'm_desc' => '',
        'm_parents' => array(7254,6196,4527),
    ),
    6225 => array(
        'm_icon' => '<i class="fas fa-circle blue"></i>',
        'm_name' => 'MY PLAYER',
        'm_desc' => '',
        'm_parents' => array(11035,4758,4527),
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
    11039 => array(
        'm_icon' => '<i class="fas fa-caret-down" aria-hidden="true"></i>',
        'm_name' => 'PLAY ADMIN MENU',
        'm_desc' => '',
        'm_parents' => array(10967,11089,4527,11040),
    ),
    6194 => array(
        'm_icon' => '<i class="far fa-database"></i>',
        'm_name' => 'PLAY CONNECTIONS',
        'm_desc' => '',
        'm_parents' => array(4755,4758,4527,6212),
    ),
    10956 => array(
        'm_icon' => '<i class="fas fa-paw" aria-hidden="true"></i>',
        'm_name' => 'PLAYER AVATAR',
        'm_desc' => '',
        'm_parents' => array(6225,6204,11008,4527),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER GENDER',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    6827 => array(
        'm_icon' => '<i class="far fa-users-crown"></i>',
        'm_name' => 'PLAYER GROUPS',
        'm_desc' => '',
        'm_parents' => array(3303,3314,7303,4527),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'PLAYER LOCK',
        'm_desc' => '',
        'm_parents' => array(4758,3303,4426,4527),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-tools blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MASS UPDATE',
        'm_desc' => '',
        'm_parents' => array(10967,11089,4758,4506,4426,4527),
    ),
    12220 => array(
        'm_icon' => '<i class="fas fa-flag" aria-hidden="true"></i>',
        'm_name' => 'PLAYER NOTIFICATION CHANNEL',
        'm_desc' => '',
        'm_parents' => array(6204,6225,4527,7305),
    ),
    4454 => array(
        'm_icon' => '<i class="fas fa-volume" aria-hidden="true"></i>',
        'm_name' => 'PLAYER NOTIFICATION VOLUME',
        'm_desc' => '',
        'm_parents' => array(6225,6204,4527),
    ),
    4986 => array(
        'm_icon' => '<i class="fal fa-at" aria-hidden="true"></i>',
        'm_name' => 'PLAYER REFERENCE ALLOWED',
        'm_desc' => '',
        'm_parents' => array(10889,4758,4527),
    ),
    7551 => array(
        'm_icon' => '<i class="fas fa-at" aria-hidden="true"></i>',
        'm_name' => 'PLAYER REFERENCE REQUIRED',
        'm_desc' => '',
        'm_parents' => array(10889,4527,4758),
    ),
    11028 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90 blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYERS LINKS DIRECTION',
        'm_desc' => '',
        'm_parents' => array(4527,11026),
    ),
    4537 => array(
        'm_icon' => '<i class="fal fa-spider-web" aria-hidden="true"></i>',
        'm_name' => 'PLAYERS LINKS URLS',
        'm_desc' => '',
        'm_parents' => array(4758,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h isblue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,7303,6204,5003,10654,6160,6232,6194,6206,4527),
    ),
    11007 => array(
        'm_icon' => '<i class="fas fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'PLAYER SUBSCRIPTION LEVEL',
        'm_desc' => '',
        'm_parents' => array(4527,6204,6225),
    ),
    10957 => array(
        'm_icon' => '<i class="fas fa-magic blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(5007,11008,4527),
    ),
    6206 => array(
        'm_icon' => '<i class="far fa-table isblue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER TABLE',
        'm_desc' => '',
        'm_parents' => array(4527,7735,4536),
    ),
    3289 => array(
        'm_icon' => '<i class="fas fa-map-marked isblue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER TIMEZONE',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    4592 => array(
        'm_icon' => '<i class="fas fa-link blue" aria-hidden="true"></i>',
        'm_name' => 'PLAY LINKS',
        'm_desc' => '',
        'm_parents' => array(11026,5982,5981,4527),
    ),
    11089 => array(
        'm_icon' => '<i class="fas fa-eye blue" aria-hidden="true"></i>',
        'm_name' => 'PLAY PORTFOLIO TABS',
        'm_desc' => '',
        'm_parents' => array(10967,4527,11088),
    ),
    11033 => array(
        'm_icon' => '<i class="fas fa-toolbox blue" aria-hidden="true"></i>',
        'm_name' => 'PLAY PROFILE TABS',
        'm_desc' => '',
        'm_parents' => array(11088,4527),
    ),
    3000 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'PLAY SOURCES',
        'm_desc' => '',
        'm_parents' => array(7303,10571,4506,4527,4463),
    ),
    7303 => array(
        'm_icon' => '<i class="far fa-chart-bar blue"></i>',
        'm_name' => 'PLAY STATS',
        'm_desc' => '',
        'm_parents' => array(10888,4527,4536),
    ),
    11088 => array(
        'm_icon' => '<i class="fas fa-list-alt blue"></i>',
        'm_name' => 'PLAY TABS',
        'm_desc' => '',
        'm_parents' => array(4527,4536),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash" aria-hidden="true"></i>',
        'm_name' => 'PRIVATE READ',
        'm_desc' => '',
        'm_parents' => array(4755,6771,4463,4426,4527),
    ),
    10718 => array(
        'm_icon' => '<i class="fas fa-clipboard-list-check" aria-hidden="true"></i>',
        'm_name' => 'PRODUCTIVITY',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-tools" aria-hidden="true"></i>',
        'm_name' => 'PRO TOOLS',
        'm_desc' => '',
        'm_parents' => array(11035,4527,7284),
    ),
    10571 => array(
        'm_icon' => '<i class="fas fa-megaphone blue" aria-hidden="true"></i>',
        'm_name' => 'PUBLIC PLAYERS',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    7357 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLIC PLAYER STATUSES',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    6192 => array(
        'm_icon' => '<i class="fas fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'READ ALL',
        'm_desc' => '',
        'm_parents' => array(4527,10602),
    ),
    11081 => array(
        'm_icon' => '<i class="far fa-bezier-curve ispink"></i>',
        'm_name' => 'READ ALL CONNECTIONS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    6193 => array(
        'm_icon' => '<i class="fas fa-code-branch rotate180" aria-hidden="true"></i>',
        'm_name' => 'READ ANY',
        'm_desc' => '',
        'm_parents' => array(10602,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-eye ispink" aria-hidden="true"></i>',
        'm_name' => 'READ COMPLETE',
        'm_desc' => '',
        'm_parents' => array(11018,11033,10964,6771,4527),
    ),
    6345 => array(
        'm_icon' => '<i class="fas fa-comment-check" aria-hidden="true"></i>',
        'm_name' => 'READER READABLE',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    5967 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER READ CC EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4506,4527,7569,4755,4593),
    ),
    4280 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'READER RECEIVED MESSAGES WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    10570 => array(
        'm_icon' => '<i class="far fa-hand-pointer"></i>',
        'm_name' => 'READER SELECTABLE COMPLETION',
        'm_desc' => '',
        'm_parents' => array(4527,7493),
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
    7704 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'READER STEP ANSWERED SUCCESSFULLY',
        'm_desc' => '',
        'm_parents' => array(4527,7493),
    ),
    7494 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'READER STEPS UNLOCK',
        'm_desc' => '',
        'm_parents' => array(4506,4527,7493),
    ),
    10590 => array(
        'm_icon' => '<i class="fas fa-info-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'READING',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
    7347 => array(
        'm_icon' => '<i class="fas fa-play ispink" aria-hidden="true"></i>',
        'm_name' => 'READ LIST',
        'm_desc' => '',
        'm_parents' => array(2992,11033,10964,6771,4527),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'READ METADATA',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    10692 => array(
        'm_icon' => '<i class="fas fa-bezier-curve ispink"></i>',
        'm_name' => 'READ OPTIONAL CONNECTIONS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-step-forward ispink" aria-hidden="true"></i>',
        'm_name' => 'READ PROGRESS',
        'm_desc' => '',
        'm_parents' => array(7493,4527),
    ),
    7304 => array(
        'm_icon' => '<i class="far fa-chart-bar ispink"></i>',
        'm_name' => 'READ STATS',
        'm_desc' => '',
        'm_parents' => array(10888,4527,6205),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
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
    7359 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    4341 => array(
        'm_icon' => '<i class="far fa-table ispink"></i>',
        'm_name' => 'READ TABLE',
        'm_desc' => '',
        'm_parents' => array(4527,7735,6205),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug" aria-hidden="true"></i>',
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
    10591 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE DIRECTIONS',
        'm_desc' => '',
        'm_parents' => array(12144,6204,7304,4527),
    ),
    12141 => array(
        'm_icon' => '<i class="fas fa-coin" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE ISSUE COINS',
        'm_desc' => '',
        'm_parents' => array(12144,4527),
    ),
    10658 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'READ UPDATES',
        'm_desc' => '',
        'm_parents' => array(4527,6205),
    ),
    10711 => array(
        'm_icon' => '<i class="fas fa-yin-yang mench-spin" aria-hidden="true"></i>',
        'm_name' => 'SELF',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    11031 => array(
        'm_icon' => '<i class="fas fa-text yellow" aria-hidden="true"></i>',
        'm_name' => 'SHOW BLOG TAB NAMES',
        'm_desc' => '',
        'm_parents' => array(6768,4527),
    ),
    11084 => array(
        'm_icon' => '<i class="fas fa-text blue" aria-hidden="true"></i>',
        'm_name' => 'SHOW PLAY TAB NAMES',
        'm_desc' => '',
        'm_parents' => array(4758,4527),
    ),
    6204 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'SINGLE SELECTABLE',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    10710 => array(
        'm_icon' => '<i class="fas fa-code" aria-hidden="true"></i>',
        'm_name' => 'SOFTWARE',
        'm_desc' => '',
        'm_parents' => array(4527,10869),
    ),
    6805 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'THING INTERACTION CONTENT REQUIRES TEXT',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    7358 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'THING STATUSES ACTIVE',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    10869 => array(
        'm_icon' => '<i class="fas fa-megaphone ispink" aria-hidden="true"></i>',
        'm_name' => 'TOPIC',
        'm_desc' => '',
        'm_parents' => array(12201,6771,4527),
    ),
);

//READ UPDATES:
$config['en_ids_10658'] = array(10686,10663,10664,10676,10678,10679,10677,10681,10675,10662,10657,10656,10659,10673,10689,10690,10683,7578);
$config['en_all_10658'] = array(
    10686 => array(
        'm_icon' => '<i class="fas fa-unlink yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10658,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-coin yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE MARKS',
        'm_desc' => '',
        'm_parents' => array(4228,10589,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(10638,10589,4593,4229,10658),
    ),
    10676 => array(
        'm_icon' => '<i class="fas fa-sort yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES SORTED',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="far fa-trash-alt yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UNLINKED',
        'm_desc' => '',
        'm_parents' => array(10658,10589,4593,10638),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10658,10638),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(10638,10589,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10658,10638),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE LINK',
        'm_desc' => '',
        'm_parents' => array(10589,11027,10638,4593,10658),
    ),
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note blue"></i>',
        'm_name' => 'PLAYER LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    10656 => array(
        'm_icon' => '<i class="far fa-sliders-h blue"></i>',
        'm_name' => 'PLAYER LINK STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10658,10645),
    ),
    10659 => array(
        'm_icon' => '<i class="fas fa-plug blue"></i>',
        'm_name' => 'PLAYER LINK TYPE UPDATE',
        'm_desc' => '',
        'm_parents' => array(10658,10589,4593,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="far fa-trash-alt blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(10645,4593,10589,10658),
    ),
    10689 => array(
        'm_icon' => '<i class="fas fa-share-alt rotate90 blue"></i>',
        'm_name' => 'PLAYER MERGED IN PLAYER',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10658,10645),
    ),
    10690 => array(
        'm_icon' => '<i class="ispink fas fa-upload"></i>',
        'm_name' => 'READER MEDIA UPLOADED',
        'm_desc' => '',
        'm_parents' => array(10590,6153,4593,10658),
    ),
    10683 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER READ EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,6153,10658,4593,7654),
    ),
    7578 => array(
        'm_icon' => '<i class="ispink far fa-key"></i>',
        'm_name' => 'READER UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(10590,6222,10658,6153,4755,4593),
    ),
);

//FILE TYPE ATTACHMENT:
$config['en_ids_10627'] = array(4259,4261,4260,4258,4554,4549,4551,4550,4548,4556,4555,4553);
$config['en_all_10627'] = array(
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up blue"></i>',
        'm_name' => 'PLAYER LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf blue"></i>',
        'm_name' => 'PLAYER LINK FILE',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(6198,11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video blue"></i>',
        'm_name' => 'PLAYER LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4554 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4549 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READER SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READER SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READER SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READER SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
);

//READ TYPE ADD CONTENT:
$config['en_ids_10593'] = array(4983,4250,4601,4231,10679,10644,4251,4259,10657,4261,4260,4255,4258,10646,4554,7702,4570,4549,4551,4550,4548,4556,4555,6563,4552,4553);
$config['en_all_10593'] = array(
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG AUTHORS',
        'm_desc' => '',
        'm_parents' => array(11018,11033,10593,10589,4527,7551,4985,4593,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle yellow"></i>',
        'm_name' => 'BLOG CREATED',
        'm_desc' => '',
        'm_parents' => array(12149,12141,10638,10593,10589,4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(11018,11033,4986,10593,10589,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11033,10990,10593,10589,6345,4986,4603,4593,4485),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TITLE',
        'm_desc' => '',
        'm_parents' => array(10589,10593,4593,10638),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle blue" aria-hidden="true"></i>',
        'm_name' => 'PLAY CREATED',
        'm_desc' => '',
        'm_parents' => array(12149,12145,12141,10645,10593,4593),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up blue"></i>',
        'm_name' => 'PLAYER LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note blue"></i>',
        'm_name' => 'PLAYER LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf blue"></i>',
        'm_name' => 'PLAYER LINK FILE',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(6198,11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left blue"></i>',
        'm_name' => 'PLAYER LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4593,4592),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video blue"></i>',
        'm_name' => 'PLAYER LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    10646 => array(
        'm_icon' => '<i class="far fa-fingerprint blue"></i>',
        'm_name' => 'PLAYER NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10645),
    ),
    4554 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7702 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER RECEIVED BLOG EMAIL',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    4570 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READER RECEIVED EMAIL',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READER SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READER SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READER SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READER SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="ispink far fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="ispink far fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
);

//READING:
$config['en_ids_10590'] = array(12129,12119,6157,7489,7487,7488,4554,7610,7757,6155,6149,6969,4275,4283,12106,6559,6560,6556,6578,6415,7611,7563,10690,4266,4267,4282,5967,10683,6132,7702,4570,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,6140,7578,6224,7562,4556,4555,4559,6563,7495,6144,6143,4235,12197,7492,4552,7485,7486,6997,12117,4553);
$config['en_all_10590'] = array(
    12129 => array(
        'm_icon' => '<i class="fas fa-trash ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(10590,6146,4593),
    ),
    12119 => array(
        'm_icon' => '<i class="ispink fas fa-comment-times"></i>',
        'm_name' => 'READ ANSWER MISSING',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="ispink fas fa-check-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,6255,6146,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="ispink fas fa-check-square ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    7487 => array(
        'm_icon' => '<i class="ispink fas fa-stopwatch ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER TIMELY',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    7488 => array(
        'm_icon' => '<i class="ispink fas fa-calendar-times ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER TIMEOUT',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    4554 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7610 => array(
        'm_icon' => '<i class="ispink fas fa-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'READ BLOG STARTED',
        'm_desc' => '',
        'm_parents' => array(10638,10590,4755,4593),
    ),
    7757 => array(
        'm_icon' => '<i class="ispink far fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="ispink far fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED MANUAL',
        'm_desc' => '',
        'm_parents' => array(10590,10888,10639,10570,4506,6150,4593,4755),
    ),
    6149 => array(
        'm_icon' => '<i class="far fa-search-plus ispink" aria-hidden="true"></i>',
        'm_name' => 'READER BLOG CONSIDERED',
        'm_desc' => '',
        'm_parents' => array(10639,10590,6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="ispink fas fa-megaphone"></i>',
        'm_name' => 'READER BLOG RECOMMENDED',
        'm_desc' => '',
        'm_parents' => array(10639,10590,4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="ispink fas fa-search"></i>',
        'm_name' => 'READER BLOG SEARCH',
        'm_desc' => '',
        'm_parents' => array(10590,10639,6554,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="ispink far fa-eye"></i>',
        'm_name' => 'READER BLOGS LISTED',
        'm_desc' => '',
        'm_parents' => array(10639,10590,6153,4755,4593),
    ),
    12106 => array(
        'm_icon' => '<i class="ispink far fa-vote-yea ispink" aria-hidden="true"></i>',
        'm_name' => 'READER CHANNEL VOTE',
        'm_desc' => '',
        'm_parents' => array(10590,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-trash ispink" aria-hidden="true"></i>',
        'm_name' => 'READER EMPTIED READING LIST',
        'm_desc' => '',
        'm_parents' => array(10590,5967,4755,6418,4593,6414),
    ),
    7611 => array(
        'm_icon' => '<i class="ispink fas fa-hand-pointer"></i>',
        'm_name' => 'READER ENGAGED BLOG POST',
        'm_desc' => '',
        'm_parents' => array(10639,10590,7610,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER MAGIC-READ EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,7569,4593),
    ),
    10690 => array(
        'm_icon' => '<i class="ispink fas fa-upload"></i>',
        'm_name' => 'READER MEDIA UPLOADED',
        'm_desc' => '',
        'm_parents' => array(10590,6153,4593,10658),
    ),
    4266 => array(
        'm_icon' => '<i class="ispink fab fa-facebook-messenger"></i>',
        'm_name' => 'READER MESSENGER OPT-IN',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="ispink fab fa-facebook-messenger"></i>',
        'm_name' => 'READER MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="ispink far fa-eye"></i>',
        'm_name' => 'READER OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(10590,4755,6222,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER READ CC EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4506,4527,7569,4755,4593),
    ),
    10683 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER READ EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,6153,10658,4593,7654),
    ),
    6132 => array(
        'm_icon' => '<i class="ispink fas fa-sort ispink" aria-hidden="true"></i>',
        'm_name' => 'READER READS SORTED',
        'm_desc' => '',
        'm_parents' => array(10590,10639,6153,4506,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER RECEIVED BLOG EMAIL',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    4570 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READER RECEIVED EMAIL',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4577 => array(
        'm_icon' => '<i class="ispink far fa-user-plus"></i>',
        'm_name' => 'READER SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READER SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READER SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READER SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="ispink far fa-location-circle"></i>',
        'm_name' => 'READER SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(10590,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="ispink far fa-eye"></i>',
        'm_name' => 'READER SENT MESSENGER READ',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="ispink far fa-cloud-download"></i>',
        'm_name' => 'READER SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="ispink far fa-user-tag"></i>',
        'm_name' => 'READER SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="ispink far fa-check"></i>',
        'm_name' => 'READER SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10590,7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="ispink far fa-align-left"></i>',
        'm_name' => 'READER SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(10590,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="ispink far fa-comment-exclamation"></i>',
        'm_name' => 'READER SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READER SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="ispink fas fa-sign-in ispink" aria-hidden="true"></i>',
        'm_name' => 'READER SIGNIN FROM BLOG',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="ispink fas fa-sign-in"></i>',
        'm_name' => 'READER SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="ispink fas fa-sign-in"></i>',
        'm_name' => 'READER SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="ispink fas fa-envelope-open"></i>',
        'm_name' => 'READER SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="ispink fab fa-facebook-messenger"></i>',
        'm_name' => 'READER SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    6140 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open"></i>',
        'm_name' => 'READER STEP READ UNLOCK',
        'm_desc' => '',
        'm_parents' => array(10590,6410,4229,4755,4593),
    ),
    7578 => array(
        'm_icon' => '<i class="ispink far fa-key"></i>',
        'm_name' => 'READER UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(10590,6222,10658,6153,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="ispink far fa-sync"></i>',
        'm_name' => 'READER UPDATE PROFILE',
        'm_desc' => '',
        'm_parents' => array(10590,4755,6222,4593),
    ),
    7562 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER WELCOME EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,7569,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4559 => array(
        'm_icon' => '<i class="ispink fas fa-comments ispink" aria-hidden="true"></i>',
        'm_name' => 'READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,6255,4755,6146,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="ispink far fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    7495 => array(
        'm_icon' => '<i class="ispink fas fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(7347,10590,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="ispink fas fa-keyboard ispink" aria-hidden="true"></i>',
        'm_name' => 'READ REPLIED',
        'm_desc' => '',
        'm_parents' => array(10590,12141,6255,4755,6146,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="ispink fas fa-fast-forward ispink" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(10590,6146,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="ispink fas fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ START',
        'm_desc' => '',
        'm_parents' => array(10590,7347,5967,4755,4593),
    ),
    12197 => array(
        'm_icon' => '<i class="far fa-tag ispink"></i>',
        'm_name' => 'READ TAG PLAYER',
        'm_desc' => '',
        'm_parents' => array(7545,4755,4593,10590),
    ),
    7492 => array(
        'm_icon' => '<i class="ispink fas fa-times-square ispink" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="ispink far fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    7485 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,6146,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4229,6255,4593,4755,6146),
    ),
    12117 => array(
        'm_icon' => '<i class="ispink fas fa-cloud-upload-alt ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UPLOADED',
        'm_desc' => '',
        'm_parents' => array(10590,12141,4593,4755,6146,6255),
    ),
    4553 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
);

//BLOGGING:
$config['en_ids_10589'] = array(10671,4983,10573,4250,4601,4229,4228,10686,10663,10664,6226,4231,10676,10678,10679,10677,7545,10681,10675,10662,10648,10650,10644,10651,4993,10672,4246,10653,4259,10657,4261,10669,4260,4319,4230,10656,4255,4318,10659,10673,4256,4258,4257,5001,10625,5943,5865,4999,4998,5000,5981,11956,5982,5003,10689,10646,7504,10654,5007,4994);
$config['en_all_10589'] = array(
    10671 => array(
        'm_icon' => '<i class="fas fa-trash-alt yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10638),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG AUTHORS',
        'm_desc' => '',
        'm_parents' => array(11018,11033,10593,10589,4527,7551,4985,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(11018,10984,11033,10589,4593,7551,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle yellow"></i>',
        'm_name' => 'BLOG CREATED',
        'm_desc' => '',
        'm_parents' => array(12149,12141,10638,10593,10589,4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(11018,11033,4986,10593,10589,4593,4485),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-question-circle yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(10589,4527,6410,6283,4593,4486),
    ),
    4228 => array(
        'm_icon' => '<i class="far fa-play yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK FIXED',
        'm_desc' => '',
        'm_parents' => array(10589,6410,4593,4486),
    ),
    10686 => array(
        'm_icon' => '<i class="fas fa-unlink yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10658,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-coin yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE MARKS',
        'm_desc' => '',
        'm_parents' => array(4228,10589,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(10638,10589,4593,4229,10658),
    ),
    6226 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG MASS UPDATE STATUSES',
        'm_desc' => '',
        'm_parents' => array(11161,10589,4593),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11033,10990,10593,10589,6345,4986,4603,4593,4485),
    ),
    10676 => array(
        'm_icon' => '<i class="fas fa-sort yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES SORTED',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="far fa-trash-alt yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UNLINKED',
        'm_desc' => '',
        'm_parents' => array(10658,10589,4593,10638),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10658,10638),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-user-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAYER TAGS',
        'm_desc' => '',
        'm_parents' => array(11018,11089,10967,10589,7551,4593,4485),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(10638,10589,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10658,10638),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE LINK',
        'm_desc' => '',
        'm_parents' => array(10589,11027,10638,4593,10658),
    ),
    10648 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10638),
    ),
    10650 => array(
        'm_icon' => '<i class="far fa-clock yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TIME',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TITLE',
        'm_desc' => '',
        'm_parents' => array(10589,10593,4593,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="far fa-shapes yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TYPE',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10638),
    ),
    4993 => array(
        'm_icon' => '<i class="far fa-eye yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG VIEWED',
        'm_desc' => '',
        'm_parents' => array(10589,10638,4593),
    ),
    10672 => array(
        'm_icon' => '<i class="far fa-trash-alt blue"></i>',
        'm_name' => 'PLAYER ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10645),
    ),
    4246 => array(
        'm_icon' => '<i class="far fa-bug blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(10589,5967,4755,4593),
    ),
    10653 => array(
        'm_icon' => '<i class="far fa-user-circle blue"></i>',
        'm_name' => 'PLAYER ICON UPDATE',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10645),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up blue"></i>',
        'm_name' => 'PLAYER LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note blue"></i>',
        'm_name' => 'PLAYER LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf blue"></i>',
        'm_name' => 'PLAYER LINK FILE',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    10669 => array(
        'm_icon' => '<i class="fab fa-font-awesome-alt blue"></i>',
        'm_name' => 'PLAYER LINK ICON',
        'm_desc' => '',
        'm_parents' => array(10589,4593,6198,4592),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(6198,11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down blue"></i>',
        'm_name' => 'PLAYER LINK INTEGER',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-link rotate90 blue"></i>',
        'm_name' => 'PLAYER LINK RAW',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592),
    ),
    10656 => array(
        'm_icon' => '<i class="far fa-sliders-h blue"></i>',
        'm_name' => 'PLAYER LINK STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10658,10645),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left blue"></i>',
        'm_name' => 'PLAYER LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock blue"></i>',
        'm_name' => 'PLAYER LINK TIME',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592),
    ),
    10659 => array(
        'm_icon' => '<i class="fas fa-plug blue"></i>',
        'm_name' => 'PLAYER LINK TYPE UPDATE',
        'm_desc' => '',
        'm_parents' => array(10658,10589,4593,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="far fa-trash-alt blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(10645,4593,10589,10658),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser blue"></i>',
        'm_name' => 'PLAYER LINK URL',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video blue"></i>',
        'm_name' => 'PLAYER LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle blue"></i>',
        'm_name' => 'PLAYER LINK WIDGET',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592,4537,4506),
    ),
    5001 => array(
        'm_icon' => '<i class="blue fas fa-sticky-note"></i>',
        'm_name' => 'PLAYER MASS CONTENT REPLACE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="blue far fa-user-circle"></i>',
        'm_name' => 'PLAYER MASS ICON REPLACE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="blue far fa-user-circle"></i>',
        'm_name' => 'PLAYER MASS ICON UPDATE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="blue far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MASS LINK STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="blue far fa-fingerprint"></i>',
        'm_name' => 'PLAYER MASS NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="blue far fa-fingerprint"></i>',
        'm_name' => 'PLAYER MASS NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="blue far fa-fingerprint"></i>',
        'm_name' => 'PLAYER MASS NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="blue fas fa-layer-plus"></i>',
        'm_name' => 'PLAYER MASS PROFILE ADD',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="blue far fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MASS PROFILE IF ADD',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="blue fas fa-layer-minus"></i>',
        'm_name' => 'PLAYER MASS PROFILE REMOVE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="blue far fa-sliders-h"></i>',
        'm_name' => 'PLAYER MASS STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    10689 => array(
        'm_icon' => '<i class="fas fa-share-alt rotate90 blue"></i>',
        'm_name' => 'PLAYER MERGED IN PLAYER',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10658,10645),
    ),
    10646 => array(
        'm_icon' => '<i class="far fa-fingerprint blue"></i>',
        'm_name' => 'PLAYER NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10645),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER REVIEW TRIGGER',
        'm_desc' => '',
        'm_parents' => array(10589,5967,4755,4593),
    ),
    10654 => array(
        'm_icon' => '<i class="far fa-sliders-h blue"></i>',
        'm_name' => 'PLAYER STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10645),
    ),
    5007 => array(
        'm_icon' => '<i class="fas fa-toggle-on blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER TOGGLE SUPERPOWER',
        'm_desc' => '',
        'm_parents' => array(10589,4593),
    ),
    4994 => array(
        'm_icon' => '<i class="far fa-eye blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER VIEWED',
        'm_desc' => '',
        'm_parents' => array(10589,4593),
    ),
);

//READ LIST:
$config['en_ids_7347'] = array(7495,4235);
$config['en_all_7347'] = array(
    7495 => array(
        'm_icon' => '<i class="ispink fas fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(7347,10590,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="ispink fas fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'START',
        'm_desc' => '',
        'm_parents' => array(10590,7347,5967,4755,4593),
    ),
);

//READ ALL:
$config['en_ids_6192'] = array(6914,7637,6677,6683);
$config['en_all_6192'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes " aria-hidden="true"></i>',
        'm_name' => 'MEET ALL REQUIREMENTS',
        'm_desc' => '',
        'm_parents' => array(10984,6192,7585,7486,7485,7309,6997),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12117,7751,7585,6192),
    ),
    6677 => array(
        'm_icon' => '<i class="far fa-comment" aria-hidden="true"></i>',
        'm_name' => 'READ',
        'm_desc' => '',
        'm_parents' => array(7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard " aria-hidden="true"></i>',
        'm_name' => 'REPLY',
        'm_desc' => '',
        'm_parents' => array(6144,7585,6192),
    ),
);

//BLOG TYPE AND/OR:
$config['en_ids_10602'] = array(6192,6193);
$config['en_all_10602'] = array(
    6192 => array(
        'm_icon' => '<i class="fas fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'ALL',
        'm_desc' => 'Reader is recommended to continue reading all next blogs.',
        'm_parents' => array(4527,10602),
    ),
    6193 => array(
        'm_icon' => '<i class="fas fa-code-branch rotate180" aria-hidden="true"></i>',
        'm_name' => 'ANY',
        'm_desc' => 'Reader is asked to choose a personalized path forward.',
        'm_parents' => array(10602,4527),
    ),
);

//READ TYPE DIRECTIONS:
$config['en_ids_10591'] = array(10589,12145,10590);
$config['en_all_10591'] = array(
    10589 => array(
        'm_icon' => '<i class="fas fa-plus-circle yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOGGING',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
    12145 => array(
        'm_icon' => '<i class="fas fa-dot-circle blue" aria-hidden="true"></i>',
        'm_name' => 'BEING',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
    10590 => array(
        'm_icon' => '<i class="fas fa-info-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'READING',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
);

//PUBLIC PLAYERS:
$config['en_ids_10571'] = array(2997,4446,3005,4763,3147,3084,3000,2999,4883,3192,5948,2998);
$config['en_all_10571'] = array(
    2997 => array(
        'm_icon' => '<i class="far fa-newspaper" aria-hidden="true"></i>',
        'm_name' => 'ARTICLES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="far fa-tachometer" aria-hidden="true"></i>',
        'm_name' => 'ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="far fa-book" aria-hidden="true"></i>',
        'm_name' => 'BOOKS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="far fa-megaphone" aria-hidden="true"></i>',
        'm_name' => 'CHANNELS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="far fa-presentation" aria-hidden="true"></i>',
        'm_name' => 'COURSES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut" aria-hidden="true"></i>',
        'm_name' => 'EXPERTS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,6827,4463),
    ),
    3000 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'PLAY SOURCES',
        'm_desc' => '',
        'm_parents' => array(7303,10571,4506,4527,4463),
    ),
    2999 => array(
        'm_icon' => '<i class="far fa-microphone" aria-hidden="true"></i>',
        'm_name' => 'PODCASTS',
        'm_desc' => '',
        'm_parents' => array(10809,10571,4983,7614,6805,3000),
    ),
    4883 => array(
        'm_icon' => '<i class="far fa-concierge-bell" aria-hidden="true"></i>',
        'm_name' => 'SERVICES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="far fa-compact-disc" aria-hidden="true"></i>',
        'm_name' => 'SOFTWARE',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="far fa-file-invoice" aria-hidden="true"></i>',
        'm_name' => 'TEMPLATES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="far fa-film" aria-hidden="true"></i>',
        'm_name' => 'VIDEOS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
);

//READER SELECTABLE COMPLETION:
$config['en_ids_10570'] = array(6155);
$config['en_all_10570'] = array(
    6155 => array(
        'm_icon' => '<i class="ispink far fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED MANUAL',
        'm_desc' => 'You did NOT accomplish the intention and you want to stop all future updates on this intention',
        'm_parents' => array(10590,10888,10639,10570,4506,6150,4593,4755),
    ),
);

//BLOG AUTHORS:
$config['en_ids_4983'] = array(2997,4446,3005,4763,3147,3084,4430,2999,4883,3192,5948,2998);
$config['en_all_4983'] = array(
    2997 => array(
        'm_icon' => '<i class="far fa-newspaper" aria-hidden="true"></i>',
        'm_name' => 'ARTICLES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="far fa-tachometer" aria-hidden="true"></i>',
        'm_name' => 'ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="far fa-book" aria-hidden="true"></i>',
        'm_name' => 'BOOKS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="far fa-megaphone" aria-hidden="true"></i>',
        'm_name' => 'CHANNELS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="far fa-presentation" aria-hidden="true"></i>',
        'm_name' => 'COURSES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut" aria-hidden="true"></i>',
        'm_name' => 'EXPERTS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,6827,4463),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-user blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYERS',
        'm_desc' => '',
        'm_parents' => array(10573,4983,6827,4426,4463),
    ),
    2999 => array(
        'm_icon' => '<i class="far fa-microphone" aria-hidden="true"></i>',
        'm_name' => 'PODCASTS',
        'm_desc' => '',
        'm_parents' => array(10809,10571,4983,7614,6805,3000),
    ),
    4883 => array(
        'm_icon' => '<i class="far fa-concierge-bell" aria-hidden="true"></i>',
        'm_name' => 'SERVICES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="far fa-compact-disc" aria-hidden="true"></i>',
        'm_name' => 'SOFTWARE',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="far fa-file-invoice" aria-hidden="true"></i>',
        'm_name' => 'TEMPLATES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="far fa-film" aria-hidden="true"></i>',
        'm_name' => 'VIDEOS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
);

//ACTION PLAN BLOGION SUCCESSFUL:
$config['en_ids_7758'] = array();
$config['en_all_7758'] = array(
);

//BLOG TYPE UPLOAD:
$config['en_ids_7751'] = array(7637);
$config['en_all_7751'] = array(
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12117,7751,7585,6192),
    ),
);

//READ METADATA:
$config['en_ids_6103'] = array(6402,6203,4358);
$config['en_all_6103'] = array(
    6402 => array(
        'm_icon' => '<i class="fas fa-bolt"></i>',
        'm_name' => 'CONDITION SCORE RANGE',
        'm_desc' => '',
        'm_parents' => array(10984,10664,6103,6410),
    ),
    6203 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'FACEBOOK ATTACHMENT ID',
        'm_desc' => 'For media files such as videos, audios, images and other files, we cache them with the Facebook Server so we can instantly deliver them to students. This variables in the link metadata is where we store the attachment ID. See the children to better understand which links types support this caching feature.',
        'm_parents' => array(6232,6215,2793,6103),
    ),
    4358 => array(
        'm_icon' => '<i class="far fa-coin" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => '',
        'm_parents' => array(12112,10984,10663,6103,6410,6232),
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
        'm_name' => 'CHILD PLAYER',
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
        'm_name' => 'PARENT PLAYER',
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
        'm_icon' => '<i class="far fa-user-edit" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="far fa-clock" aria-hidden="true"></i>',
        'm_name' => 'TIME',
        'm_desc' => '',
        'm_parents' => array(12112,6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//PLAYER TABLE:
$config['en_ids_6206'] = array(6197,6198,6160,6172,6177);
$config['en_all_6206'] = array(
    6197 => array(
        'm_icon' => '<i class="fas fa-fingerprint" aria-hidden="true"></i>',
        'm_name' => 'FULL NAME',
        'm_desc' => '',
        'm_parents' => array(6225,11072,10646,5000,4998,4999,6232,6206),
    ),
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle isblue"></i>',
        'm_name' => 'ICON',
        'm_desc' => '',
        'm_parents' => array(10653,5943,10625,6232,6206),
    ),
    6160 => array(
        'm_icon' => '<i class="fas fa-user-circle blue"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda isblue"></i>',
        'm_name' => 'METADATA',
        'm_desc' => '',
        'm_parents' => array(11044,6232,3323,6206,6195),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h isblue" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,7303,6204,5003,10654,6160,6232,6194,6206,4527),
    ),
);

//BLOG TABLE:
$config['en_ids_6201'] = array(6202,6159,4356,4737,4736,7585);
$config['en_all_6201'] = array(
    6202 => array(
        'm_icon' => '<i class="fas fa-plus-circle yellow"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6201),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda yellow" aria-hidden="true"></i>',
        'm_name' => 'METADATA',
        'm_desc' => 'Intent metadata contains variables that have been automatically calculated and automatically updates using a cron job. Intent Metadata are the backbone of key functions and user interfaces like the intent landing page or Action Plan completion workflows.',
        'm_parents' => array(11049,6232,6201,6195),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch yellow" aria-hidden="true"></i>',
        'm_name' => 'READ TIME',
        'm_desc' => '',
        'm_parents' => array(10888,10650,6232,6201),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 yellow" aria-hidden="true"></i>',
        'm_name' => 'TITLE',
        'm_desc' => '',
        'm_parents' => array(12112,11071,10644,6232,6201),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random yellow" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
);

//SINGLE SELECTABLE:
$config['en_ids_6204'] = array(4737,7585,10602,10956,3290,12220,4454,6177,11007,3289,6186,4593,10591);
$config['en_all_6204'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE AND/OR',
        'm_desc' => '',
        'm_parents' => array(10893,6204,7302,4527),
    ),
    10956 => array(
        'm_icon' => '<i class="fas fa-paw" aria-hidden="true"></i>',
        'm_name' => 'PLAYER AVATAR',
        'm_desc' => '',
        'm_parents' => array(6225,6204,11008,4527),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER GENDER',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    12220 => array(
        'm_icon' => '<i class="fas fa-flag" aria-hidden="true"></i>',
        'm_name' => 'PLAYER NOTIFICATION CHANNEL',
        'm_desc' => '',
        'm_parents' => array(6204,6225,4527,7305),
    ),
    4454 => array(
        'm_icon' => '<i class="fas fa-volume" aria-hidden="true"></i>',
        'm_name' => 'PLAYER NOTIFICATION VOLUME',
        'm_desc' => '',
        'm_parents' => array(6225,6204,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h isblue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,7303,6204,5003,10654,6160,6232,6194,6206,4527),
    ),
    11007 => array(
        'm_icon' => '<i class="fas fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'PLAYER SUBSCRIPTION LEVEL',
        'm_desc' => '',
        'm_parents' => array(4527,6204,6225),
    ),
    3289 => array(
        'm_icon' => '<i class="fas fa-map-marked isblue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER TIMEZONE',
        'm_desc' => '',
        'm_parents' => array(4527,6204),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
    10591 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE DIRECTIONS',
        'm_desc' => '',
        'm_parents' => array(12144,6204,7304,4527),
    ),
);

//BLOG TYPE SELECT:
$config['en_ids_7712'] = array(6684,7231);
$config['en_all_7712'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'ONE',
        'm_desc' => '',
        'm_parents' => array(12129,12119,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square" aria-hidden="true"></i>',
        'm_name' => 'SOME',
        'm_desc' => '',
        'm_parents' => array(12129,12119,7712,7489,7585,6193),
    ),
);

//READER STEP ANSWERED SUCCESSFULLY:
$config['en_ids_7704'] = array(6157,7489,7487);
$config['en_all_7704'] = array(
    6157 => array(
        'm_icon' => '<i class="ispink fas fa-check-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'ONE',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,6255,6146,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="ispink fas fa-check-square ispink" aria-hidden="true"></i>',
        'm_name' => 'SOME',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    7487 => array(
        'm_icon' => '<i class="ispink fas fa-stopwatch ispink" aria-hidden="true"></i>',
        'm_name' => 'TIMELY',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
);

//BLOG LINK CONDITIONAL:
$config['en_ids_4229'] = array(10664,6140,6997);
$config['en_all_4229'] = array(
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(10638,10589,4593,4229,10658),
    ),
    6140 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open"></i>',
        'm_name' => 'READER STEP READ UNLOCK',
        'm_desc' => 'A step that has become available because of the score generated from student answers',
        'm_parents' => array(10590,6410,4229,4755,4593),
    ),
    6997 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4229,6255,4593,4755,6146),
    ),
);

//READ ANY:
$config['en_ids_6193'] = array(6684,7231,6907);
$config['en_all_6193'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'SELECT ONE',
        'm_desc' => '',
        'm_parents' => array(12129,12119,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square" aria-hidden="true"></i>',
        'm_name' => 'SELECT SOME',
        'm_desc' => '',
        'm_parents' => array(12129,12119,7712,7489,7585,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube " aria-hidden="true"></i>',
        'm_name' => 'MEET ONE REQUIREMENT',
        'm_desc' => '',
        'm_parents' => array(10984,7585,7486,7485,7309,6997,6193),
    ),
);

//BLOG TYPE:
$config['en_ids_7585'] = array(6677,6683,7637,6684,7231,6907,6914);
$config['en_all_7585'] = array(
    6677 => array(
        'm_icon' => '<i class="far fa-comment" aria-hidden="true"></i>',
        'm_name' => 'READ',
        'm_desc' => 'Read messages & move to the next blog.',
        'm_parents' => array(7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard " aria-hidden="true"></i>',
        'm_name' => 'REPLY',
        'm_desc' => 'Give a text response & move to the next blog.',
        'm_parents' => array(6144,7585,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD',
        'm_desc' => 'Upload a file & move to the next blog.',
        'm_parents' => array(12117,7751,7585,6192),
    ),
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'SELECT ONE',
        'm_desc' => 'Select 1 blog from the list of blogs.',
        'm_parents' => array(12129,12119,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square" aria-hidden="true"></i>',
        'm_name' => 'SELECT SOME',
        'm_desc' => 'Select 1 or more blogs from the list of blogs.',
        'm_parents' => array(12129,12119,7712,7489,7585,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube " aria-hidden="true"></i>',
        'm_name' => 'MEET ONE REQUIREMENT',
        'm_desc' => 'Complete by (a) choosing intent as their answer or by (b) completing any child intent',
        'm_parents' => array(10984,7585,7486,7485,7309,6997,6193),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes " aria-hidden="true"></i>',
        'm_name' => 'MEET ALL REQUIREMENTS',
        'm_desc' => 'Complete by (a) choosing intent as their answer or by (b) completing all child intents',
        'm_parents' => array(10984,6192,7585,7486,7485,7309,6997),
    ),
);

//READER READ CC EMAIL:
$config['en_ids_5967'] = array(4246,7504,6415,4235);
$config['en_all_5967'] = array(
    4246 => array(
        'm_icon' => '<i class="far fa-bug blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER BUG REPORTS',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(10589,5967,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER REVIEW TRIGGER',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(10589,5967,4755,4593),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-trash ispink" aria-hidden="true"></i>',
        'm_name' => 'READER EMPTIED READING LIST',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(10590,5967,4755,6418,4593,6414),
    ),
    4235 => array(
        'm_icon' => '<i class="ispink fas fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ START',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(10590,7347,5967,4755,4593),
    ),
);

//MENCH READING CHANNELS:
$config['en_ids_7555'] = array(6196,12103);
$config['en_all_7555'] = array(
    6196 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger blue" aria-hidden="true"></i>',
        'm_name' => 'MESSENGER',
        'm_desc' => 'Read & receive notifications using Messenger. (Recommended)',
        'm_parents' => array(12222,4426,7555,3320),
    ),
    12103 => array(
        'm_icon' => '<i class="fab fa-chrome" aria-hidden="true"></i>',
        'm_name' => 'WEBSITE',
        'm_desc' => 'Read using modern web browsers & receive notifications using email.',
        'm_parents' => array(7555),
    ),
);

//PLAYER REFERENCE REQUIRED:
$config['en_ids_7551'] = array(4983,10573,7545);
$config['en_all_7551'] = array(
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit yellow" aria-hidden="true"></i>',
        'm_name' => 'AUTHORS',
        'm_desc' => '',
        'm_parents' => array(11018,11033,10593,10589,4527,7551,4985,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark yellow" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(11018,10984,11033,10589,4593,7551,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-user-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'PLAYER TAGS',
        'm_desc' => '',
        'm_parents' => array(11018,11089,10967,10589,7551,4593,4485),
    ),
);

//READER STEPS UNLOCK:
$config['en_ids_7494'] = array(4559,7485,7486,6997);
$config['en_all_7494'] = array(
    4559 => array(
        'm_icon' => '<i class="ispink fas fa-comments ispink" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,6255,4755,6146,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,6146,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4229,6255,4593,4755,6146),
    ),
);

//BLOG TYPE REQUIREMENT:
$config['en_ids_7309'] = array(6914,6907);
$config['en_all_7309'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes " aria-hidden="true"></i>',
        'm_name' => 'ALL REQUIREMENTS',
        'm_desc' => '',
        'm_parents' => array(10984,6192,7585,7486,7485,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube " aria-hidden="true"></i>',
        'm_name' => 'ONE REQUIREMENT',
        'm_desc' => '',
        'm_parents' => array(10984,7585,7486,7485,7309,6997,6193),
    ),
);

//PRO TOOLS:
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
        'm_icon' => '<i class="far fa-magic"></i>',
        'm_name' => 'PLATFORM CRON JOBS',
        'm_desc' => '',
        'm_parents' => array(6403,6287),
    ),
);

//INTERACTION STATUSES INCOMPLETE:
$config['en_ids_7364'] = array(6175);
$config['en_all_7364'] = array(
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'READ DRAFT',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
);

//READ STATUS ACTIVE:
$config['en_ids_7360'] = array(6175,6176);
$config['en_all_7360'] = array(
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'DRAFT',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISH',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
);

//READ STATUS PUBLIC:
$config['en_ids_7359'] = array(6176);
$config['en_all_7359'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'READ PUBLISH',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
);

//THING STATUSES ACTIVE:
$config['en_ids_7358'] = array(6180,6181);
$config['en_all_7358'] = array(
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'DRAFT',
        'm_desc' => '',
        'm_parents' => array(7358,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISH',
        'm_desc' => '',
        'm_parents' => array(7358,7357,6177),
    ),
);

//PUBLIC PLAYER STATUSES:
$config['en_ids_7357'] = array(6181);
$config['en_all_7357'] = array(
    6181 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PLAYER PUBLISH',
        'm_desc' => '',
        'm_parents' => array(7358,7357,6177),
    ),
);

//BLOG STATUSES ACTIVE:
$config['en_ids_7356'] = array(6183,12137,6184);
$config['en_all_7356'] = array(
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin " aria-hidden="true"></i>',
        'm_name' => 'DRAFT',
        'm_desc' => '',
        'm_parents' => array(10648,7356,4737),
    ),
    12137 => array(
        'm_icon' => '<i class="fas fa-megaphone" aria-hidden="true"></i>',
        'm_name' => 'FEATURE',
        'm_desc' => '',
        'm_parents' => array(10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISH',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
);

//BLOG STATUSES PUBLIC:
$config['en_ids_7355'] = array(12137,6184);
$config['en_all_7355'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-megaphone" aria-hidden="true"></i>',
        'm_name' => 'FEATURE',
        'm_desc' => '',
        'm_parents' => array(10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISH',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
);

//BLOG STATS:
$config['en_ids_7302'] = array(4737,10602);
$config['en_all_7302'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece yellow" aria-hidden="true"></i>',
        'm_name' => 'TYPE AND/OR',
        'm_desc' => '',
        'm_parents' => array(10893,6204,7302,4527),
    ),
);

//PLAY STATS:
$config['en_ids_7303'] = array(6827,6177,3000);
$config['en_all_7303'] = array(
    6827 => array(
        'm_icon' => '<i class="far fa-users-crown"></i>',
        'm_name' => 'PLAYER GROUPS',
        'm_desc' => '',
        'm_parents' => array(3303,3314,7303,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h isblue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,7303,6204,5003,10654,6160,6232,6194,6206,4527),
    ),
    3000 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'PLAY SOURCES',
        'm_desc' => '',
        'm_parents' => array(7303,10571,4506,4527,4463),
    ),
);

//READ STATS:
$config['en_ids_7304'] = array(6186,10591);
$config['en_all_7304'] = array(
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    10591 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90" aria-hidden="true"></i>',
        'm_name' => 'TYPE DIRECTIONS',
        'm_desc' => '',
        'm_parents' => array(12144,6204,7304,4527),
    ),
);

//READ STATUS:
$config['en_ids_6186'] = array(6176,6175,6173);
$config['en_all_6186'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISH',
        'm_desc' => 'live and ready to be shared with users',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'DRAFT',
        'm_desc' => 'being mined, to be published soon',
        'm_parents' => array(7364,7360,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'ARCHIVE',
        'm_desc' => 'archived',
        'm_parents' => array(12012,10686,10678,10673,6186),
    ),
);

//PLAY CONNECTIONS:
$config['en_ids_6194'] = array(4737,7585,6177,4364,6186,4593);
$config['en_all_6194'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG STATUS',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_blog WHERE in_status_play_id=',
        'm_parents' => array(12079,11054,6204,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG TYPE',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_blog WHERE in_status_play_id IN (6183,6184) AND in_type_play_id=',
        'm_parents' => array(12079,11054,6204,10651,6160,6194,6232,4527,6201),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h isblue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER STATUS',
        'm_desc' => 'SELECT count(en_id) as totals FROM table_play WHERE en_status_play_id=',
        'm_parents' => array(11054,7303,6204,5003,10654,6160,6232,6194,6206,4527),
    ),
    4364 => array(
        'm_icon' => '<i class="far fa-user-edit" aria-hidden="true"></i>',
        'm_name' => 'READ PLAYER',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_read WHERE ln_status_play_id IN (6175,6176) AND ln_creator_play_id=',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_read WHERE ln_status_play_id=',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_read WHERE ln_status_play_id IN (6175,6176) AND ln_type_play_id=',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//PLAYER GROUPS:
$config['en_ids_6827'] = array(3084,4430);
$config['en_all_6827'] = array(
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut" aria-hidden="true"></i>',
        'm_name' => 'EXPERTS',
        'm_desc' => 'Experienced in their respective industry with a track record of advancing their field of knowldge',
        'm_parents' => array(10571,4983,6827,4463),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-user blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYERS',
        'm_desc' => 'Users who are pursuing their intentions using Mench, mainly to get hired at their dream job',
        'm_parents' => array(10573,4983,6827,4426,4463),
    ),
);

//THING INTERACTION CONTENT REQUIRES TEXT:
$config['en_ids_6805'] = array(3005,4763,3147,2999,4883,3192);
$config['en_all_6805'] = array(
    3005 => array(
        'm_icon' => '<i class="far fa-book" aria-hidden="true"></i>',
        'm_name' => 'BOOKS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="far fa-megaphone" aria-hidden="true"></i>',
        'm_name' => 'CHANNELS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="far fa-presentation" aria-hidden="true"></i>',
        'm_name' => 'COURSES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="far fa-microphone" aria-hidden="true"></i>',
        'm_name' => 'PODCASTS',
        'm_desc' => '',
        'm_parents' => array(10809,10571,4983,7614,6805,3000),
    ),
    4883 => array(
        'm_icon' => '<i class="far fa-concierge-bell" aria-hidden="true"></i>',
        'm_name' => 'SERVICES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="far fa-compact-disc" aria-hidden="true"></i>',
        'm_name' => 'SOFTWARE',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
);

//READER READABLE:
$config['en_ids_6345'] = array(4231);
$config['en_all_6345'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11033,10990,10593,10589,6345,4986,4603,4593,4485),
    ),
);

//READ PROGRESS:
$config['en_ids_6255'] = array(6157,7489,7487,4559,6144,7485,7486,6997,12117);
$config['en_all_6255'] = array(
    6157 => array(
        'm_icon' => '<i class="ispink fas fa-check-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,6255,6146,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="ispink fas fa-check-square ispink" aria-hidden="true"></i>',
        'm_name' => 'ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    7487 => array(
        'm_icon' => '<i class="ispink fas fa-stopwatch ispink" aria-hidden="true"></i>',
        'm_name' => 'ANSWER TIMELY',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    4559 => array(
        'm_icon' => '<i class="ispink fas fa-comments ispink" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,6255,4755,6146,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="ispink fas fa-keyboard ispink" aria-hidden="true"></i>',
        'm_name' => 'REPLIED',
        'm_desc' => '',
        'm_parents' => array(10590,12141,6255,4755,6146,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,6146,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4229,6255,4593,4755,6146),
    ),
    12117 => array(
        'm_icon' => '<i class="ispink fas fa-cloud-upload-alt ispink" aria-hidden="true"></i>',
        'm_name' => 'UPLOADED',
        'm_desc' => '',
        'm_parents' => array(10590,12141,4593,4755,6146,6255),
    ),
);

//BOOKMARK REMOVED:
$config['en_ids_6150'] = array(7757,6155);
$config['en_all_6150'] = array(
    7757 => array(
        'm_icon' => '<i class="ispink far fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'AUTO',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="ispink far fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'MANUAL',
        'm_desc' => '',
        'm_parents' => array(10590,10888,10639,10570,4506,6150,4593,4755),
    ),
);

//PLAYER REFERENCE ALLOWED:
$config['en_ids_4986'] = array(4601,4231);
$config['en_all_4986'] = array(
    4601 => array(
        'm_icon' => '<i class="fas fa-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => 'In case it happens it should be referencing verbs',
        'm_parents' => array(11018,11033,4986,10593,10589,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment yellow" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11033,10990,10593,10589,6345,4986,4603,4593,4485),
    ),
);

//MY PLAYER:
$config['en_ids_6225'] = array(10956,6197,3288,3286,11007,4454,12220);
$config['en_all_6225'] = array(
    10956 => array(
        'm_icon' => '<i class="fas fa-paw" aria-hidden="true"></i>',
        'm_name' => 'AVATAR',
        'm_desc' => '',
        'm_parents' => array(6225,6204,11008,4527),
    ),
    6197 => array(
        'm_icon' => '<i class="fas fa-fingerprint" aria-hidden="true"></i>',
        'm_name' => 'FULL NAME',
        'm_desc' => '',
        'm_parents' => array(6225,11072,10646,5000,4998,4999,6232,6206),
    ),
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => '',
        'm_parents' => array(12221,12103,6225,4426,4755),
    ),
    3286 => array(
        'm_icon' => '<i class="fas fa-key" aria-hidden="true"></i>',
        'm_name' => 'PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4426,7578,6225,4755),
    ),
    11007 => array(
        'm_icon' => '<i class="fas fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'SUBSCRIPTION LEVEL',
        'm_desc' => '',
        'm_parents' => array(4527,6204,6225),
    ),
    4454 => array(
        'm_icon' => '<i class="fas fa-volume" aria-hidden="true"></i>',
        'm_name' => 'NOTIFICATION VOLUME',
        'm_desc' => '',
        'm_parents' => array(6225,6204,4527),
    ),
    12220 => array(
        'm_icon' => '<i class="fas fa-flag" aria-hidden="true"></i>',
        'm_name' => 'NOTIFICATION CHANNEL',
        'm_desc' => '',
        'm_parents' => array(6204,6225,4527,7305),
    ),
);

//BLOG STATUS:
$config['en_ids_4737'] = array(12137,6184,6183,6182);
$config['en_all_4737'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-megaphone" aria-hidden="true"></i>',
        'm_name' => 'FEATURE',
        'm_desc' => 'Considered to be featured on mench.com',
        'm_parents' => array(10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISH',
        'm_desc' => 'visible to anyone who has the blog link',
        'm_parents' => array(10648,7355,7356,4737),
    ),
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin " aria-hidden="true"></i>',
        'm_name' => 'DRAFT',
        'm_desc' => 'visible to you & blog authors only',
        'm_parents' => array(10648,7356,4737),
    ),
    6182 => array(
        'm_icon' => '<i class="far fa-trash-alt " aria-hidden="true"></i>',
        'm_name' => 'ARCHIVE',
        'm_desc' => 'removed & unlinked from all blogs',
        'm_parents' => array(10671,4737),
    ),
);

//PLAYER STATUS:
$config['en_ids_6177'] = array(6181,6180,6178);
$config['en_all_6177'] = array(
    6181 => array(
        'm_icon' => '<i class="fas fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISH',
        'm_desc' => 'live and ready to be shared with users',
        'm_parents' => array(7358,7357,6177),
    ),
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'DRAFT',
        'm_desc' => 'being mined, to be published soon',
        'm_parents' => array(7358,6177),
    ),
    6178 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'ARCHIVE',
        'm_desc' => 'archived',
        'm_parents' => array(10672,6177),
    ),
);

//READ COMPLETE:
$config['en_ids_6146'] = array(12129,12119,6157,7489,7487,7488,4559,6144,6143,7492,7485,7486,6997,12117);
$config['en_all_6146'] = array(
    12129 => array(
        'm_icon' => '<i class="fas fa-trash ispink" aria-hidden="true"></i>',
        'm_name' => 'ANSWER ARCHIVED',
        'm_desc' => 'When player chooses to remove an answer already saved',
        'm_parents' => array(10590,6146,4593),
    ),
    12119 => array(
        'm_icon' => '<i class="ispink fas fa-comment-times"></i>',
        'm_name' => 'ANSWER MISSING',
        'm_desc' => 'Player was unable to answer a question as there was no published child blogs to select from.',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="ispink fas fa-check-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'ANSWER ONE',
        'm_desc' => 'Completed after the student answers the question to the OR intent.',
        'm_parents' => array(10590,12141,7704,6255,6146,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="ispink fas fa-check-square ispink" aria-hidden="true"></i>',
        'm_name' => 'ANSWER SOME',
        'm_desc' => 'User made a selection as part of a multiple-choice answer question',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    7487 => array(
        'm_icon' => '<i class="ispink fas fa-stopwatch ispink" aria-hidden="true"></i>',
        'm_name' => 'ANSWER TIMELY',
        'm_desc' => 'When the user answers a question within the defined timeframe',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    7488 => array(
        'm_icon' => '<i class="ispink fas fa-calendar-times ispink" aria-hidden="true"></i>',
        'm_name' => 'ANSWER TIMEOUT',
        'm_desc' => 'User failed to answer the question within the allocated time',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    4559 => array(
        'm_icon' => '<i class="ispink fas fa-comments ispink" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => 'Completed when students complete a basic AND intent without any submission requirements',
        'm_parents' => array(10590,12141,7494,6255,4755,6146,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="ispink fas fa-keyboard ispink" aria-hidden="true"></i>',
        'm_name' => 'REPLIED',
        'm_desc' => 'Completed when students submit the intent completion requirements (text, URL, video, etc...) set by bloggers',
        'm_parents' => array(10590,12141,6255,4755,6146,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="ispink fas fa-fast-forward ispink" aria-hidden="true"></i>',
        'm_name' => 'SKIPPED',
        'm_desc' => 'Completed when students skip an intention and all its child intentions from their Action Plan',
        'm_parents' => array(10590,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="ispink fas fa-times-square ispink" aria-hidden="true"></i>',
        'm_name' => 'TERMINATE',
        'm_desc' => 'Logged when users arrive at a locked intent that has no public OR parents or no children, which means there is no way to unlock it.',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    7485 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK ANSWER',
        'm_desc' => 'When users unlock locked AND or OR intents by simply answering an open OR question',
        'm_parents' => array(10590,12141,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CHILDREN',
        'm_desc' => 'When users unlock locked AND or OR intents by completing ALL or ANY of their children',
        'm_parents' => array(10590,12141,7494,6146,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CONDITION',
        'm_desc' => 'When users unlock locked AND or OR intents by scoring within the range of a conditional intent link',
        'm_parents' => array(10590,12141,7494,4229,6255,4593,4755,6146),
    ),
    12117 => array(
        'm_icon' => '<i class="ispink fas fa-cloud-upload-alt ispink" aria-hidden="true"></i>',
        'm_name' => 'UPLOADED',
        'm_desc' => '',
        'm_parents' => array(10590,12141,4593,4755,6146,6255),
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
$config['en_ids_6102'] = array(4554,4549,4551,4550,4548,4556,4555,4553);
$config['en_all_6102'] = array(
    4554 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4549 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READER SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READER SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READER SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READER SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
);

//READER RECEIVED MESSAGES WITH MESSENGER:
$config['en_ids_4280'] = array(4554,4556,4555,6563,4552,4553);
$config['en_all_4280'] = array(
    4554 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="ispink far fa-check"></i>',
        'm_name' => 'QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="ispink far fa-align-left"></i>',
        'm_name' => 'TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
);

//PLAYER MASS UPDATE:
$config['en_ids_4997'] = array(5001,10625,5943,5865,4999,4998,5000,5981,11956,5982,5003);
$config['en_all_4997'] = array(
    5001 => array(
        'm_icon' => '<i class="blue fas fa-sticky-note"></i>',
        'm_name' => 'CONTENT REPLACE',
        'm_desc' => 'Search for occurance of string in child entity link contents and if found, updates it with a replacement string',
        'm_parents' => array(10589,4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="blue far fa-user-circle"></i>',
        'm_name' => 'ICON REPLACE',
        'm_desc' => 'Search for occurrence of string in child entity icons and if found, updates it with a replacement string',
        'm_parents' => array(10589,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="blue far fa-user-circle"></i>',
        'm_name' => 'ICON UPDATE',
        'm_desc' => 'Updates all child entity icons with string which needs to be a valid icon',
        'm_parents' => array(10589,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="blue far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'LINK STATUS REPLACE',
        'm_desc' => 'Updates all child entity link statuses that match the initial link status condition',
        'm_parents' => array(10589,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="blue far fa-fingerprint"></i>',
        'm_name' => 'NAME POSTFIX',
        'm_desc' => 'Adds string to the end of all child entities',
        'm_parents' => array(10589,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="blue far fa-fingerprint"></i>',
        'm_name' => 'NAME PREFIX',
        'm_desc' => 'Adds string to the beginning of all child entities. Make sure to include a space for it to look good',
        'm_parents' => array(10589,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="blue far fa-fingerprint"></i>',
        'm_name' => 'NAME REPLACE',
        'm_desc' => 'Search for occurrence of string in child entity names and if found, updates it with a replacement string',
        'm_parents' => array(10589,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="blue fas fa-layer-plus"></i>',
        'm_name' => 'PROFILE ADD',
        'm_desc' => 'If not already done so, will add searched entity as the parent of all child entities',
        'm_parents' => array(10589,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="blue far fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PROFILE IF ADD',
        'm_desc' => 'Adds a parent entity only IF the entity has another parent entity.',
        'm_parents' => array(10589,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="blue fas fa-layer-minus"></i>',
        'm_name' => 'PROFILE REMOVE',
        'm_desc' => 'If already added as the parent, this will remove searched entity as the parent of all child entities',
        'm_parents' => array(10589,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="blue far fa-sliders-h"></i>',
        'm_name' => 'STATUS REPLACE',
        'm_desc' => 'Updates all child entity statuses that match the initial entity status condition',
        'm_parents' => array(10589,4593,4997),
    ),
);

//PLAYER LOCK:
$config['en_ids_4426'] = array(3288,4426,4997,6196,3286,4430,4755);
$config['en_all_4426'] = array(
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'PLAYER EMAIL',
        'm_desc' => '',
        'm_parents' => array(12221,12103,6225,4426,4755),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'PLAYER LOCK',
        'm_desc' => '',
        'm_parents' => array(4758,3303,4426,4527),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-tools blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MASS UPDATE',
        'm_desc' => '',
        'm_parents' => array(10967,11089,4758,4506,4426,4527),
    ),
    6196 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MESSENGER',
        'm_desc' => '',
        'm_parents' => array(12222,4426,7555,3320),
    ),
    3286 => array(
        'm_icon' => '<i class="fas fa-key" aria-hidden="true"></i>',
        'm_name' => 'PLAYER PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4426,7578,6225,4755),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-user blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYERS',
        'm_desc' => '',
        'm_parents' => array(10573,4983,6827,4426,4463),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash" aria-hidden="true"></i>',
        'm_name' => 'PRIVATE READ',
        'm_desc' => '',
        'm_parents' => array(4755,6771,4463,4426,4527),
    ),
);

//PRIVATE READ:
$config['en_ids_4755'] = array(10681,4783,6232,6194,4246,3288,3286,7504,4755,12119,6157,7489,7487,7488,4554,7610,7757,6155,6149,6969,4275,4283,6559,6560,6556,6578,6415,7611,7563,4266,4267,4282,5967,6132,7702,4570,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,6140,7578,6224,7562,4556,4555,4559,6563,7495,6144,6143,4235,12197,7492,4552,7485,7486,6997,12117,4553);
$config['en_all_4755'] = array(
    10681 => array(
        'm_icon' => '<i class="fas fa-sort yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(10638,10589,4755,4593,10658),
    ),
    4783 => array(
        'm_icon' => '<i class="far fa-phone blue"></i>',
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
    6194 => array(
        'm_icon' => '<i class="far fa-database"></i>',
        'm_name' => 'PLAY CONNECTIONS',
        'm_desc' => '',
        'm_parents' => array(4755,4758,4527,6212),
    ),
    4246 => array(
        'm_icon' => '<i class="far fa-bug blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(10589,5967,4755,4593),
    ),
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'PLAYER EMAIL',
        'm_desc' => '',
        'm_parents' => array(12221,12103,6225,4426,4755),
    ),
    3286 => array(
        'm_icon' => '<i class="fas fa-key" aria-hidden="true"></i>',
        'm_name' => 'PLAYER PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4426,7578,6225,4755),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER REVIEW TRIGGER',
        'm_desc' => '',
        'm_parents' => array(10589,5967,4755,4593),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash" aria-hidden="true"></i>',
        'm_name' => 'PRIVATE READ',
        'm_desc' => '',
        'm_parents' => array(4755,6771,4463,4426,4527),
    ),
    12119 => array(
        'm_icon' => '<i class="ispink fas fa-comment-times"></i>',
        'm_name' => 'READ ANSWER MISSING',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="ispink fas fa-check-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,6255,6146,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="ispink fas fa-check-square ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    7487 => array(
        'm_icon' => '<i class="ispink fas fa-stopwatch ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER TIMELY',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    7488 => array(
        'm_icon' => '<i class="ispink fas fa-calendar-times ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER TIMEOUT',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    4554 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7610 => array(
        'm_icon' => '<i class="ispink fas fa-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'READ BLOG STARTED',
        'm_desc' => '',
        'm_parents' => array(10638,10590,4755,4593),
    ),
    7757 => array(
        'm_icon' => '<i class="ispink far fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="ispink far fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED MANUAL',
        'm_desc' => '',
        'm_parents' => array(10590,10888,10639,10570,4506,6150,4593,4755),
    ),
    6149 => array(
        'm_icon' => '<i class="far fa-search-plus ispink" aria-hidden="true"></i>',
        'm_name' => 'READER BLOG CONSIDERED',
        'm_desc' => '',
        'm_parents' => array(10639,10590,6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="ispink fas fa-megaphone"></i>',
        'm_name' => 'READER BLOG RECOMMENDED',
        'm_desc' => '',
        'm_parents' => array(10639,10590,4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="ispink fas fa-search"></i>',
        'm_name' => 'READER BLOG SEARCH',
        'm_desc' => '',
        'm_parents' => array(10590,10639,6554,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="ispink far fa-eye"></i>',
        'm_name' => 'READER BLOGS LISTED',
        'm_desc' => '',
        'm_parents' => array(10639,10590,6153,4755,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-trash ispink" aria-hidden="true"></i>',
        'm_name' => 'READER EMPTIED READING LIST',
        'm_desc' => '',
        'm_parents' => array(10590,5967,4755,6418,4593,6414),
    ),
    7611 => array(
        'm_icon' => '<i class="ispink fas fa-hand-pointer"></i>',
        'm_name' => 'READER ENGAGED BLOG POST',
        'm_desc' => '',
        'm_parents' => array(10639,10590,7610,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER MAGIC-READ EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,7569,4593),
    ),
    4266 => array(
        'm_icon' => '<i class="ispink fab fa-facebook-messenger"></i>',
        'm_name' => 'READER MESSENGER OPT-IN',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="ispink fab fa-facebook-messenger"></i>',
        'm_name' => 'READER MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="ispink far fa-eye"></i>',
        'm_name' => 'READER OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(10590,4755,6222,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER READ CC EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4506,4527,7569,4755,4593),
    ),
    6132 => array(
        'm_icon' => '<i class="ispink fas fa-sort ispink" aria-hidden="true"></i>',
        'm_name' => 'READER READS SORTED',
        'm_desc' => '',
        'm_parents' => array(10590,10639,6153,4506,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER RECEIVED BLOG EMAIL',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    4570 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READER RECEIVED EMAIL',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4577 => array(
        'm_icon' => '<i class="ispink far fa-user-plus"></i>',
        'm_name' => 'READER SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READER SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READER SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READER SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="ispink far fa-location-circle"></i>',
        'm_name' => 'READER SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(10590,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="ispink far fa-eye"></i>',
        'm_name' => 'READER SENT MESSENGER READ',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="ispink far fa-cloud-download"></i>',
        'm_name' => 'READER SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="ispink far fa-user-tag"></i>',
        'm_name' => 'READER SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="ispink far fa-check"></i>',
        'm_name' => 'READER SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10590,7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="ispink far fa-align-left"></i>',
        'm_name' => 'READER SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(10590,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="ispink far fa-comment-exclamation"></i>',
        'm_name' => 'READER SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READER SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="ispink fas fa-sign-in ispink" aria-hidden="true"></i>',
        'm_name' => 'READER SIGNIN FROM BLOG',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="ispink fas fa-sign-in"></i>',
        'm_name' => 'READER SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="ispink fas fa-sign-in"></i>',
        'm_name' => 'READER SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="ispink fas fa-envelope-open"></i>',
        'm_name' => 'READER SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="ispink fab fa-facebook-messenger"></i>',
        'm_name' => 'READER SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    6140 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open"></i>',
        'm_name' => 'READER STEP READ UNLOCK',
        'm_desc' => '',
        'm_parents' => array(10590,6410,4229,4755,4593),
    ),
    7578 => array(
        'm_icon' => '<i class="ispink far fa-key"></i>',
        'm_name' => 'READER UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(10590,6222,10658,6153,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="ispink far fa-sync"></i>',
        'm_name' => 'READER UPDATE PROFILE',
        'm_desc' => '',
        'm_parents' => array(10590,4755,6222,4593),
    ),
    7562 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER WELCOME EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,7569,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4559 => array(
        'm_icon' => '<i class="ispink fas fa-comments ispink" aria-hidden="true"></i>',
        'm_name' => 'READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,6255,4755,6146,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="ispink far fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    7495 => array(
        'm_icon' => '<i class="ispink fas fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(7347,10590,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="ispink fas fa-keyboard ispink" aria-hidden="true"></i>',
        'm_name' => 'READ REPLIED',
        'm_desc' => '',
        'm_parents' => array(10590,12141,6255,4755,6146,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="ispink fas fa-fast-forward ispink" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(10590,6146,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="ispink fas fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ START',
        'm_desc' => '',
        'm_parents' => array(10590,7347,5967,4755,4593),
    ),
    12197 => array(
        'm_icon' => '<i class="far fa-tag ispink"></i>',
        'm_name' => 'READ TAG PLAYER',
        'm_desc' => '',
        'm_parents' => array(7545,4755,4593,10590),
    ),
    7492 => array(
        'm_icon' => '<i class="ispink fas fa-times-square ispink" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="ispink far fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    7485 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,6146,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4229,6255,4593,4755,6146),
    ),
    12117 => array(
        'm_icon' => '<i class="ispink fas fa-cloud-upload-alt ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UPLOADED',
        'm_desc' => '',
        'm_parents' => array(10590,12141,4593,4755,6146,6255),
    ),
    4553 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
);

//READ TYPE:
$config['en_ids_4593'] = array(10671,4983,10573,4250,4601,4229,4228,10686,10663,10664,6226,4231,10676,10678,10679,10677,7545,10681,10675,10662,10648,10650,10644,10651,4993,4251,10672,4246,10653,4259,10657,4261,10669,4260,4319,4230,10656,4255,4318,10659,10673,4256,4258,4257,5001,10625,5943,5865,4999,4998,5000,5981,11956,5982,5003,10689,10646,7504,10654,5007,4994,12129,12119,6157,7489,7487,7488,4554,7610,7757,6155,6149,6969,4275,4283,12106,6559,6560,6556,6578,6415,7611,7563,10690,4266,4267,4282,5967,10683,6132,7702,4570,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,6140,7578,6224,7562,4556,4555,4559,6563,7495,6144,6143,4235,12197,7492,4552,7485,7486,6997,12117,4553);
$config['en_all_4593'] = array(
    10671 => array(
        'm_icon' => '<i class="fas fa-trash-alt yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10638),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG AUTHORS',
        'm_desc' => 'References track intent correlations referenced within expert sources, and represent a core building block of intelligence. References are among the most precious transaction types because they indicate that IF you do A, you will likely accomplish B. As trainers add more sources from more experts, certain intent correlations will receive more references than others, thus gaining more credibility.',
        'm_parents' => array(11018,11033,10593,10589,4527,7551,4985,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG BOOKMARKS',
        'm_desc' => 'Keeps track of the users who can manage/edit the intent',
        'm_parents' => array(11018,10984,11033,10589,4593,7551,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle yellow"></i>',
        'm_name' => 'BLOG CREATED',
        'm_desc' => '',
        'm_parents' => array(12149,12141,10638,10593,10589,4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(11018,11033,4986,10593,10589,4593,4485),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-question-circle yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(10589,4527,6410,6283,4593,4486),
    ),
    4228 => array(
        'm_icon' => '<i class="far fa-play yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK FIXED',
        'm_desc' => '',
        'm_parents' => array(10589,6410,4593,4486),
    ),
    10686 => array(
        'm_icon' => '<i class="fas fa-unlink yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10658,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-coin yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE MARKS',
        'm_desc' => '',
        'm_parents' => array(4228,10589,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(10638,10589,4593,4229,10658),
    ),
    6226 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG MASS UPDATE STATUSES',
        'm_desc' => ' When all intents within a recursive tree are updated at once.',
        'm_parents' => array(11161,10589,4593),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11033,10990,10593,10589,6345,4986,4603,4593,4485),
    ),
    10676 => array(
        'm_icon' => '<i class="fas fa-sort yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES SORTED',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="far fa-trash-alt yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UNLINKED',
        'm_desc' => '',
        'm_parents' => array(10658,10589,4593,10638),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG NOTES UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10658,10638),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-user-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG PLAYER TAGS',
        'm_desc' => '',
        'm_parents' => array(11018,11089,10967,10589,7551,4593,4485),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(10638,10589,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10658,10638),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE LINK',
        'm_desc' => '',
        'm_parents' => array(10589,11027,10638,4593,10658),
    ),
    10648 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10638),
    ),
    10650 => array(
        'm_icon' => '<i class="far fa-clock yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TIME',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TITLE',
        'm_desc' => 'Logged when trainers update the intent outcome',
        'm_parents' => array(10589,10593,4593,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="far fa-shapes yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG UPDATE TYPE',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10638),
    ),
    4993 => array(
        'm_icon' => '<i class="far fa-eye yellow" aria-hidden="true"></i>',
        'm_name' => 'BLOG VIEWED',
        'm_desc' => '',
        'm_parents' => array(10589,10638,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle blue" aria-hidden="true"></i>',
        'm_name' => 'PLAY CREATED',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(12149,12145,12141,10645,10593,4593),
    ),
    10672 => array(
        'm_icon' => '<i class="far fa-trash-alt blue"></i>',
        'm_name' => 'PLAYER ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10645),
    ),
    4246 => array(
        'm_icon' => '<i class="far fa-bug blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(10589,5967,4755,4593),
    ),
    10653 => array(
        'm_icon' => '<i class="far fa-user-circle blue"></i>',
        'm_name' => 'PLAYER ICON UPDATE',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10645),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up blue"></i>',
        'm_name' => 'PLAYER LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note blue"></i>',
        'm_name' => 'PLAYER LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf blue"></i>',
        'm_name' => 'PLAYER LINK FILE',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    10669 => array(
        'm_icon' => '<i class="fab fa-font-awesome-alt blue"></i>',
        'm_name' => 'PLAYER LINK ICON',
        'm_desc' => '',
        'm_parents' => array(10589,4593,6198,4592),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(6198,11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down blue"></i>',
        'm_name' => 'PLAYER LINK INTEGER',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-link rotate90 blue"></i>',
        'm_name' => 'PLAYER LINK RAW',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592),
    ),
    10656 => array(
        'm_icon' => '<i class="far fa-sliders-h blue"></i>',
        'm_name' => 'PLAYER LINK STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10658,10645),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left blue"></i>',
        'm_name' => 'PLAYER LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock blue"></i>',
        'm_name' => 'PLAYER LINK TIME',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592),
    ),
    10659 => array(
        'm_icon' => '<i class="fas fa-plug blue"></i>',
        'm_name' => 'PLAYER LINK TYPE UPDATE',
        'm_desc' => 'Iterations happens automatically based on link content',
        'm_parents' => array(10658,10589,4593,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="far fa-trash-alt blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(10645,4593,10589,10658),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser blue"></i>',
        'm_name' => 'PLAYER LINK URL',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video blue"></i>',
        'm_name' => 'PLAYER LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle blue"></i>',
        'm_name' => 'PLAYER LINK WIDGET',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592,4537,4506),
    ),
    5001 => array(
        'm_icon' => '<i class="blue fas fa-sticky-note"></i>',
        'm_name' => 'PLAYER MASS CONTENT REPLACE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="blue far fa-user-circle"></i>',
        'm_name' => 'PLAYER MASS ICON REPLACE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="blue far fa-user-circle"></i>',
        'm_name' => 'PLAYER MASS ICON UPDATE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="blue far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MASS LINK STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="blue far fa-fingerprint"></i>',
        'm_name' => 'PLAYER MASS NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="blue far fa-fingerprint"></i>',
        'm_name' => 'PLAYER MASS NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="blue far fa-fingerprint"></i>',
        'm_name' => 'PLAYER MASS NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="blue fas fa-layer-plus"></i>',
        'm_name' => 'PLAYER MASS PROFILE ADD',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="blue far fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MASS PROFILE IF ADD',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="blue fas fa-layer-minus"></i>',
        'm_name' => 'PLAYER MASS PROFILE REMOVE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="blue far fa-sliders-h"></i>',
        'm_name' => 'PLAYER MASS STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4997),
    ),
    10689 => array(
        'm_icon' => '<i class="fas fa-share-alt rotate90 blue"></i>',
        'm_name' => 'PLAYER MERGED IN PLAYER',
        'm_desc' => 'When an entity is merged with another entity and the links are carried over',
        'm_parents' => array(10589,4593,10658,10645),
    ),
    10646 => array(
        'm_icon' => '<i class="far fa-fingerprint blue"></i>',
        'm_name' => 'PLAYER NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10645),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER REVIEW TRIGGER',
        'm_desc' => 'Certain links that match an unknown behavior would require an admin to review and ensure it\'s all good',
        'm_parents' => array(10589,5967,4755,4593),
    ),
    10654 => array(
        'm_icon' => '<i class="far fa-sliders-h blue"></i>',
        'm_name' => 'PLAYER STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10645),
    ),
    5007 => array(
        'm_icon' => '<i class="fas fa-toggle-on blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER TOGGLE SUPERPOWER',
        'm_desc' => '',
        'm_parents' => array(10589,4593),
    ),
    4994 => array(
        'm_icon' => '<i class="far fa-eye blue" aria-hidden="true"></i>',
        'm_name' => 'PLAYER VIEWED',
        'm_desc' => '',
        'm_parents' => array(10589,4593),
    ),
    12129 => array(
        'm_icon' => '<i class="fas fa-trash ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ARCHIVED',
        'm_desc' => '',
        'm_parents' => array(10590,6146,4593),
    ),
    12119 => array(
        'm_icon' => '<i class="ispink fas fa-comment-times"></i>',
        'm_name' => 'READ ANSWER MISSING',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="ispink fas fa-check-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,6255,6146,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="ispink fas fa-check-square ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    7487 => array(
        'm_icon' => '<i class="ispink fas fa-stopwatch ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER TIMELY',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7704,4755,6255,4593,6146),
    ),
    7488 => array(
        'm_icon' => '<i class="ispink fas fa-calendar-times ispink" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER TIMEOUT',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    4554 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7610 => array(
        'm_icon' => '<i class="ispink fas fa-circle ispink" aria-hidden="true"></i>',
        'm_name' => 'READ BLOG STARTED',
        'm_desc' => 'When a user viewes the public intent landing page.',
        'm_parents' => array(10638,10590,4755,4593),
    ),
    7757 => array(
        'm_icon' => '<i class="ispink far fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="ispink far fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED MANUAL',
        'm_desc' => 'Student prematurely removed an intention from their Action Plan without accomplishing it.',
        'm_parents' => array(10590,10888,10639,10570,4506,6150,4593,4755),
    ),
    6149 => array(
        'm_icon' => '<i class="far fa-search-plus ispink" aria-hidden="true"></i>',
        'm_name' => 'READER BLOG CONSIDERED',
        'm_desc' => 'When a student chooses to review a given intention from the intentions they have searched or have been recommended after selecting GET STARTED from a mench.com intent landing page.',
        'm_parents' => array(10639,10590,6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="ispink fas fa-megaphone"></i>',
        'm_name' => 'READER BLOG RECOMMENDED',
        'm_desc' => 'Logged every time an intention is recommended to a user by Mench',
        'm_parents' => array(10639,10590,4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="ispink fas fa-search"></i>',
        'm_name' => 'READER BLOG SEARCH',
        'm_desc' => 'When students invokes the [I want to] command and search for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(10590,10639,6554,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="ispink far fa-eye"></i>',
        'm_name' => 'READER BLOGS LISTED',
        'm_desc' => 'Once a student has added an Intention to their Action Plan, this link will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(10639,10590,6153,4755,4593),
    ),
    12106 => array(
        'm_icon' => '<i class="ispink far fa-vote-yea ispink" aria-hidden="true"></i>',
        'm_name' => 'READER CHANNEL VOTE',
        'm_desc' => '',
        'm_parents' => array(10590,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="ispink fas fa-wand-magic"></i>',
        'm_name' => 'READER COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6554),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-trash ispink" aria-hidden="true"></i>',
        'm_name' => 'READER EMPTIED READING LIST',
        'm_desc' => 'Removes certain links types as defined by its children from a Student\'s Action Plan. Currently only available for trainers.',
        'm_parents' => array(10590,5967,4755,6418,4593,6414),
    ),
    7611 => array(
        'm_icon' => '<i class="ispink fas fa-hand-pointer"></i>',
        'm_name' => 'READER ENGAGED BLOG POST',
        'm_desc' => 'Logged when a user expands a section of the intent',
        'm_parents' => array(10639,10590,7610,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER MAGIC-READ EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,7569,4593),
    ),
    10690 => array(
        'm_icon' => '<i class="ispink fas fa-upload"></i>',
        'm_name' => 'READER MEDIA UPLOADED',
        'm_desc' => 'When a file added by the user is synced to the CDN',
        'm_parents' => array(10590,6153,4593,10658),
    ),
    4266 => array(
        'm_icon' => '<i class="ispink fab fa-facebook-messenger"></i>',
        'm_name' => 'READER MESSENGER OPT-IN',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="ispink fab fa-facebook-messenger"></i>',
        'm_name' => 'READER MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="ispink far fa-eye"></i>',
        'm_name' => 'READER OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(10590,4755,6222,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER READ CC EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4506,4527,7569,4755,4593),
    ),
    10683 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER READ EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,6153,10658,4593,7654),
    ),
    6132 => array(
        'm_icon' => '<i class="ispink fas fa-sort ispink" aria-hidden="true"></i>',
        'm_name' => 'READER READS SORTED',
        'm_desc' => 'Student re-prioritized their top-level intentions to focus on intentions that currently matter the most.',
        'm_parents' => array(10590,10639,6153,4506,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER RECEIVED BLOG EMAIL',
        'm_desc' => 'Emails sent to intent subscribers who are looking for updates on an intent.',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    4570 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READER RECEIVED EMAIL',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4577 => array(
        'm_icon' => '<i class="ispink far fa-user-plus"></i>',
        'm_name' => 'READER SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="ispink far fa-volume-up"></i>',
        'm_name' => 'READER SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READER SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READER SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="ispink far fa-location-circle"></i>',
        'm_name' => 'READER SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(10590,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="ispink far fa-eye"></i>',
        'm_name' => 'READER SENT MESSENGER READ',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="ispink far fa-cloud-download"></i>',
        'm_name' => 'READER SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="ispink far fa-user-tag"></i>',
        'm_name' => 'READER SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="ispink far fa-check"></i>',
        'm_name' => 'READER SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10590,7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="ispink far fa-align-left"></i>',
        'm_name' => 'READER SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(10590,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="ispink far fa-comment-exclamation"></i>',
        'm_name' => 'READER SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(10590,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READER SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10590,10627,10593,7653,6102,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="ispink fas fa-sign-in ispink" aria-hidden="true"></i>',
        'm_name' => 'READER SIGNIN FROM BLOG',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="ispink fas fa-sign-in"></i>',
        'm_name' => 'READER SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="ispink fas fa-sign-in"></i>',
        'm_name' => 'READER SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="ispink fas fa-envelope-open"></i>',
        'm_name' => 'READER SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="ispink fab fa-facebook-messenger"></i>',
        'm_name' => 'READER SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593),
    ),
    6140 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open"></i>',
        'm_name' => 'READER STEP READ UNLOCK',
        'm_desc' => 'Created when the student responses to OR branches meets the right % points to unlock the pathway to a conditional intent link.',
        'm_parents' => array(10590,6410,4229,4755,4593),
    ),
    7578 => array(
        'm_icon' => '<i class="ispink far fa-key"></i>',
        'm_name' => 'READER UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(10590,6222,10658,6153,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="ispink far fa-sync"></i>',
        'm_name' => 'READER UPDATE PROFILE',
        'm_desc' => '',
        'm_parents' => array(10590,4755,6222,4593),
    ),
    7562 => array(
        'm_icon' => '<i class="ispink far fa-envelope-open"></i>',
        'm_name' => 'READER WELCOME EMAIL',
        'm_desc' => '',
        'm_parents' => array(10590,4755,7569,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="ispink far fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="ispink far fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4559 => array(
        'm_icon' => '<i class="ispink fas fa-comments ispink" aria-hidden="true"></i>',
        'm_name' => 'READ MESSAGES',
        'm_desc' => 'Logged when a student receives the messages of an AND intent that does not have any completion requirements.',
        'm_parents' => array(10590,12141,7494,6255,4755,6146,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="ispink far fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    7495 => array(
        'm_icon' => '<i class="ispink fas fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => 'Intention recommended by Mench and added to Action Plan to enable the user to complete their intention',
        'm_parents' => array(7347,10590,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="ispink fas fa-keyboard ispink" aria-hidden="true"></i>',
        'm_name' => 'READ REPLIED',
        'm_desc' => 'Logged when a student submits the requirements (text, video, etc...) of an AND intent which could not be completed by simply receiving messages.',
        'm_parents' => array(10590,12141,6255,4755,6146,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="ispink fas fa-fast-forward ispink" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => 'Logged every time a student consciously skips an intent and it\'s recursive children.',
        'm_parents' => array(10590,6146,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="ispink fas fa-bookmark ispink" aria-hidden="true"></i>',
        'm_name' => 'READ START',
        'm_desc' => '',
        'm_parents' => array(10590,7347,5967,4755,4593),
    ),
    12197 => array(
        'm_icon' => '<i class="far fa-tag ispink"></i>',
        'm_name' => 'READ TAG PLAYER',
        'm_desc' => '',
        'm_parents' => array(7545,4755,4593,10590),
    ),
    7492 => array(
        'm_icon' => '<i class="ispink fas fa-times-square ispink" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(10590,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="ispink far fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    7485 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,6146,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="ispink fas fa-lock-open ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(10590,12141,7494,4229,6255,4593,4755,6146),
    ),
    12117 => array(
        'm_icon' => '<i class="ispink fas fa-cloud-upload-alt ispink" aria-hidden="true"></i>',
        'm_name' => 'READ UPLOADED',
        'm_desc' => '',
        'm_parents' => array(10590,12141,4593,4755,6146,6255),
    ),
    4553 => array(
        'm_icon' => '<i class="ispink far fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
);

//PLAY LINKS:
$config['en_ids_4592'] = array(4259,4261,10669,4260,4319,4230,4255,4318,4256,4258,4257);
$config['en_all_4592'] = array(
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up blue"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf blue"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    10669 => array(
        'm_icon' => '<i class="fab fa-font-awesome-alt blue"></i>',
        'm_name' => 'ICON',
        'm_desc' => 'Icons maping to the Font Awesome database',
        'm_parents' => array(10589,4593,6198,4592),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image blue" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(6198,11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down blue"></i>',
        'm_name' => 'INTEGER',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-link rotate90 blue"></i>',
        'm_name' => 'RAW',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left blue"></i>',
        'm_name' => 'TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock blue"></i>',
        'm_name' => 'TIME',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser blue"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video blue"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle blue"></i>',
        'm_name' => 'WIDGET',
        'm_desc' => '',
        'm_parents' => array(10589,4593,4592,4537,4506),
    ),
);

//PLAYER NOTIFICATION VOLUME:
$config['en_ids_4454'] = array(4456,4457,4458);
$config['en_all_4454'] = array(
    4456 => array(
        'm_icon' => '<i class="far fa-volume-up" aria-hidden="true"></i>',
        'm_name' => 'REGULAR',
        'm_desc' => 'User is connected and will be notified by sound & vibration for new Mench messages',
        'm_parents' => array(11058,4454),
    ),
    4457 => array(
        'm_icon' => '<i class="far fa-volume-down" aria-hidden="true"></i>',
        'm_name' => 'SILENT',
        'm_desc' => 'User is connected and will be notified by on-screen notification only for new Mench messages',
        'm_parents' => array(11058,4454),
    ),
    4458 => array(
        'm_icon' => '<i class="far fa-volume-mute" aria-hidden="true"></i>',
        'm_name' => 'DISABLED',
        'm_desc' => 'User is connected but will not be notified for new Mench messages except the red icon indicator on the Messenger app which would indicate the total number of new messages they have',
        'm_parents' => array(11058,4454),
    ),
);

//BLOG NOTES:
$config['en_ids_4485'] = array(4231,4983,4601,10573,7545);
$config['en_all_4485'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment yellow" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(11033,10990,10593,10589,6345,4986,4603,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit yellow" aria-hidden="true"></i>',
        'm_name' => 'AUTHORS',
        'm_desc' => '',
        'm_parents' => array(11018,11033,10593,10589,4527,7551,4985,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(11018,11033,4986,10593,10589,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-bookmark yellow" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(11018,10984,11033,10589,4593,7551,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-user-tag yellow" aria-hidden="true"></i>',
        'm_name' => 'PLAYER TAGS',
        'm_desc' => '',
        'm_parents' => array(11018,11089,10967,10589,7551,4593,4485),
    ),
);

//BLOG LINKS:
$config['en_ids_4486'] = array(4228,4229);
$config['en_all_4486'] = array(
    4228 => array(
        'm_icon' => '<i class="far fa-play yellow" aria-hidden="true"></i>',
        'm_name' => 'FIXED',
        'm_desc' => 'Blogs that follow another blog no matter what',
        'm_parents' => array(10589,6410,4593,4486),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-question-circle yellow" aria-hidden="true"></i>',
        'm_name' => 'CONDITIONAL',
        'm_desc' => 'Blogs that follow another blog if the conditional score is met',
        'm_parents' => array(10589,4527,6410,6283,4593,4486),
    ),
);

//PLAYERS LINKS URLS:
$config['en_ids_4537'] = array(4259,4261,4260,4256,4258,4257);
$config['en_all_4537'] = array(
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up blue"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf blue"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'Link notes contain a URL to a raw file.',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image blue" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
        'm_parents' => array(6198,11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser blue"></i>',
        'm_name' => 'URL',
        'm_desc' => 'Link note contains a generic URL only.',
        'm_parents' => array(10589,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video blue"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'Link notes contain a URL to a raw video file.',
        'm_parents' => array(11080,11059,10627,10593,10589,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle blue"></i>',
        'm_name' => 'WIDGET',
        'm_desc' => 'Link note contain a recognizable URL that offers an embed widget for a more engaging play-back experience.',
        'm_parents' => array(10589,4593,4592,4537,4506),
    ),
);

//PLAY SOURCES:
$config['en_ids_3000'] = array(2997,4446,3005,4763,3147,2999,4883,3192,5948,2998);
$config['en_all_3000'] = array(
    2997 => array(
        'm_icon' => '<i class="far fa-newspaper" aria-hidden="true"></i>',
        'm_name' => 'ARTICLES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="far fa-tachometer" aria-hidden="true"></i>',
        'm_name' => 'ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="far fa-book" aria-hidden="true"></i>',
        'm_name' => 'BOOKS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="far fa-megaphone" aria-hidden="true"></i>',
        'm_name' => 'CHANNELS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="far fa-presentation" aria-hidden="true"></i>',
        'm_name' => 'COURSES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="far fa-microphone" aria-hidden="true"></i>',
        'm_name' => 'PODCASTS',
        'm_desc' => '',
        'm_parents' => array(10809,10571,4983,7614,6805,3000),
    ),
    4883 => array(
        'm_icon' => '<i class="far fa-concierge-bell" aria-hidden="true"></i>',
        'm_name' => 'SERVICES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="far fa-compact-disc" aria-hidden="true"></i>',
        'm_name' => 'SOFTWARE',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="far fa-file-invoice" aria-hidden="true"></i>',
        'm_name' => 'TEMPLATES',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="far fa-film" aria-hidden="true"></i>',
        'm_name' => 'VIDEOS',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
);