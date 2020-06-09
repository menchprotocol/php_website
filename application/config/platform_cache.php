<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
* Keep a cache of certain parts of the idea for faster processing
* See source @4527 for more details
*
*/

//Generated 2020-06-09 15:18:09 PST

//READ TABS SHOW IF LOGGED-IN:
$config['sources_id_13304'] = array(12419,13023);
$config['sources__13304'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(13304,13291,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    13023 => array(
        'm_icon' => '<i class="fas fa-share"></i>',
        'm_name' => 'SHARE',
        'm_desc' => '',
        'm_parents' => array(13304,13291,12896,11035,4527,13024,7305),
    ),
);

//READ TABS HIDE IF ZERO:
$config['sources_id_13298'] = array(7545,12864,12273);
$config['sources__13298'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(13298,13291,12273,12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    12864 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT SOURCES',
        'm_desc' => '',
        'm_parents' => array(13298,4983,13291,13207,4600,4527,4758),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13291,13298,4535,12500,12571,12467,12321,11089,12228,4527),
    ),
);

//READ TABS DEFAULT SELECTED:
$config['sources_id_13300'] = array(4231);
$config['sources__13300'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,12322,10593,4986,4603,4593,4485),
    ),
);

//READ TABS:
$config['sources_id_13291'] = array(4231,12273,12419,12864,7545,13023);
$config['sources__13291'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,12322,10593,4986,4603,4593,4485),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13291,13298,4535,12500,12571,12467,12321,11089,12228,4527),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(13304,13291,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    12864 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT SOURCES',
        'm_desc' => '',
        'm_parents' => array(13298,4983,13291,13207,4600,4527,4758),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(13298,13291,12273,12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    13023 => array(
        'm_icon' => '<i class="fas fa-share"></i>',
        'm_name' => 'SHARE',
        'm_desc' => '',
        'm_parents' => array(13304,13291,12896,11035,4527,13024,7305),
    ),
);

//IDEATORS:
$config['sources_id_13202'] = array(4430);
$config['sources__13202'] = array(
    4430 => array(
        'm_icon' => '<i class="fas fa-user-crown source" aria-hidden="true"></i>',
        'm_name' => 'MENCH PLAYERS',
        'm_desc' => '',
        'm_parents' => array(13202,4536,4983,1278,11035,10573),
    ),
);

//SOURCE HOME:
$config['sources_id_13207'] = array(13202,12864,3000);
$config['sources__13207'] = array(
    13202 => array(
        'm_icon' => '<i class="fas fa-user-edit idea" aria-hidden="true"></i>',
        'm_name' => 'IDEATORS',
        'm_desc' => '',
        'm_parents' => array(4527,13207),
    ),
    12864 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT SOURCES',
        'm_desc' => '',
        'm_parents' => array(13298,4983,13291,13207,4600,4527,4758),
    ),
    3000 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT CONTENT',
        'm_desc' => '',
        'm_parents' => array(13207,11054,4600,4527),
    ),
);

//DONATE:
$config['sources_id_13037'] = array(13038,13039,13040,13041,13042);
$config['sources__13037'] = array(
    13038 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$5 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(13037),
    ),
    13039 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$10 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(13037),
    ),
    13040 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$20 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(13037),
    ),
    13041 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$50 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(13037),
    ),
    13042 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => 'CUSTOM AMOUNT',
        'm_desc' => '',
        'm_parents' => array(13037),
    ),
);

//TOPICS:
$config['sources_id_10869'] = array(10712,13033,10735,10739,10809,10719,13031,10711,10781,10774,10782,10775,10769,10721,10737,13034,10738,11125,10773,7325,13036);
$config['sources__10869'] = array(
    10712 => array(
        'm_icon' => '<i class="fas fa-chart-line" aria-hidden="true"></i>',
        'm_name' => 'PROFESSIONAL SKILLS',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    13033 => array(
        'm_icon' => '<i class="fas fa-code"></i>',
        'm_name' => 'SOFTWARE & TECHNOLOGY',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10735 => array(
        'm_icon' => '<i class="fas fa-usd-circle mench-spin"></i>',
        'm_name' => 'FINANCE & INVESTMENTS',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10739 => array(
        'm_icon' => '<i class="fas fa-bullseye-arrow mench-spin" aria-hidden="true"></i>',
        'm_name' => 'MARKETING & SALES',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10809 => array(
        'm_icon' => '<i class="fad fa-camera" aria-hidden="true"></i>',
        'm_name' => 'CONTENT CREATION',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10719 => array(
        'm_icon' => '<i class="fas fa-pencil-ruler mench-spin" aria-hidden="true"></i>',
        'm_name' => 'GRAPHICS & DESIGN',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    13031 => array(
        'm_icon' => '<i class="fas fa-atom mench-spin"></i>',
        'm_name' => 'SCIENCE & ACADEMICS',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10711 => array(
        'm_icon' => '<i class="fas fa-yin-yang mench-spin" aria-hidden="true"></i>',
        'm_name' => 'SELF CARE',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10781 => array(
        'm_icon' => '<i class="far fa-head-side-brain mirror-h" aria-hidden="true"></i>',
        'm_name' => 'MINDFULNESS & CREATIVITY',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10774 => array(
        'm_icon' => '<i class="fas fa-egg"></i>',
        'm_name' => 'FOOD & NUTRITIONS',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10782 => array(
        'm_icon' => '<i class="far fa-futbol mench-spin"></i>',
        'm_name' => 'FITNESS & SPORTS',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10775 => array(
        'm_icon' => '<i class="far fa-pray" aria-hidden="true"></i>',
        'm_name' => 'RELIGION & SPIRITUALITY',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10769 => array(
        'm_icon' => '<i class="fas fa-spade"></i>',
        'm_name' => 'MUSIC & GAMING',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10721 => array(
        'm_icon' => '<i class="fas fa-plane mench-spin" aria-hidden="true"></i>',
        'm_name' => 'LIFESTYLE & TRAVEL',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10737 => array(
        'm_icon' => '<i class="fas fa-user-friends mirror-h"></i>',
        'm_name' => 'RELATIONSHIPS',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    13034 => array(
        'm_icon' => '<i class="fas fa-comments"></i>',
        'm_name' => 'LISTENING & COMMUNICATION',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10738 => array(
        'm_icon' => '<i class="fas fa-location-circle mench-spin"></i>',
        'm_name' => 'LEADERSHIP & TEAMWORK',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    11125 => array(
        'm_icon' => '<i class="fas fa-heart-circle mench-spin"></i>',
        'm_name' => 'LOVE & FAMILY',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    10773 => array(
        'm_icon' => '<i class="fas fa-child" aria-hidden="true"></i>',
        'm_name' => 'CHILDREN & PARENTING',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    7325 => array(
        'm_icon' => '<i class="fas fa-users"></i>',
        'm_name' => 'NETWORKING & RECRUITEMENT',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
    13036 => array(
        'm_icon' => '<i class="fas fa-suitcase"></i>',
        'm_name' => 'CORPORATE CULTURE',
        'm_desc' => '',
        'm_parents' => array(10869),
    ),
);

//SEARCH INDEX:
$config['sources_id_12761'] = array(4535,4536);
$config['sources__12761'] = array(
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEATE',
        'm_desc' => '',
        'm_parents' => array(10939,12893,10876,11035,12761,12112,2738),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(10939,12489,11035,10876,12893,12761,4527,2738),
    ),
);

//SHARE:
$config['sources_id_13023'] = array(12889,12890,3300,3302,3288,13026,3099);
$config['sources__13023'] = array(
    12889 => array(
        'm_icon' => '<i class="fab fa-facebook source"></i>',
        'm_name' => 'FACEBOOK',
        'm_desc' => 'facebook',
        'm_parents' => array(13023,12891),
    ),
    12890 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger source" aria-hidden="true"></i>',
        'm_name' => 'MESSENGER',
        'm_desc' => 'messenger',
        'm_parents' => array(13023,12889),
    ),
    3300 => array(
        'm_icon' => '<i class="fab fa-twitter source" aria-hidden="true"></i>',
        'm_name' => 'TWITTER',
        'm_desc' => 'twitter',
        'm_parents' => array(13023,12891,1326),
    ),
    3302 => array(
        'm_icon' => '<i class="fab fa-linkedin source" aria-hidden="true"></i>',
        'm_name' => 'LINKEDIN',
        'm_desc' => 'linkedin',
        'm_parents' => array(13023,12891,1326),
    ),
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => 'email',
        'm_parents' => array(6404,13023,4269,12103,6225,4755),
    ),
    13026 => array(
        'm_icon' => '<i class="fab fa-whatsapp-square isgreen"></i>',
        'm_name' => 'WHATSAPP',
        'm_desc' => 'whatsapp',
        'm_parents' => array(12891,13023),
    ),
    3099 => array(
        'm_icon' => '<i class="fab fa-reddit"></i>',
        'm_name' => 'REDDIT',
        'm_desc' => 'reddit',
        'm_parents' => array(12891,13023,1326),
    ),
);

//IDEA TYPE ALL NEXT:
$config['sources_id_13022'] = array(6677,6683,7637);
$config['sources__13022'] = array(
    6677 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'READ & NEXT',
        'm_desc' => '',
        'm_parents' => array(13022,12330,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard idea" aria-hidden="true"></i>',
        'm_name' => 'REPLY & NEXT',
        'm_desc' => '',
        'm_parents' => array(13022,12955,6144,7585,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip idea" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD & NEXT',
        'm_desc' => '',
        'm_parents' => array(13022,12955,12117,7751,7585,6192),
    ),
);

//SOURCE LAYOUT RESTRICT COUNTS:
$config['sources_id_13004'] = array(11029,11030);
$config['sources__13004'] = array(
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => '',
        'm_parents' => array(13004,12574,12571,11089,11028),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PROFILE',
        'm_desc' => '',
        'm_parents' => array(13004,12571,12574,11089,11028),
    ),
);

//MY READS:
$config['sources_id_12969'] = array(4235,7495);
$config['sources__12969'] = array(
    4235 => array(
        'm_icon' => '<i class="fas fa-play read" aria-hidden="true"></i>',
        'm_name' => 'GET STARTED',
        'm_desc' => '',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone read" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(12969,12227,4755,4593),
    ),
);

//SYNC ICONS IF DIFFERENT:
$config['sources_id_12968'] = array(2997,4446,3005,3147,4763,3084,2750,3192,2998);
$config['sources__12968'] = array(
    2997 => array(
        'm_icon' => '<i class="fas fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'ARTICLE',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fas fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'ASSESSMENT',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fas fa-book source" aria-hidden="true"></i>',
        'm_name' => 'BOOK',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fas fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'COURSE',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fas fa-megaphone source" aria-hidden="true"></i>',
        'm_name' => 'MARKETING CHANNEL',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    3084 => array(
        'm_icon' => '<i class="fad fa-user-astronaut source"></i>',
        'm_name' => 'PERSON',
        'm_desc' => '',
        'm_parents' => array(12968,12864,4983,11035,1278),
    ),
    2750 => array(
        'm_icon' => '<i class="fas fa-space-shuttle source rotate270"></i>',
        'm_name' => 'PUBLICATION',
        'm_desc' => '',
        'm_parents' => array(12968,12864),
    ),
    3192 => array(
        'm_icon' => '<i class="fas fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'TOOL',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fas fa-play-circle source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
);

//IDEA TYPE TAKES COMPLETION TIME:
$config['sources_id_12955'] = array(6683,6684,7231,7637);
$config['sources__12955'] = array(
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard idea" aria-hidden="true"></i>',
        'm_name' => 'REPLY & NEXT',
        'm_desc' => '60',
        'm_parents' => array(13022,12955,6144,7585,6192),
    ),
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT ONE',
        'm_desc' => '15',
        'm_parents' => array(12955,12883,12336,12129,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT SOME',
        'm_desc' => '20',
        'm_parents' => array(12955,12884,12334,12129,7712,7489,7585,6193),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip idea" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD & NEXT',
        'm_desc' => '30',
        'm_parents' => array(13022,12955,12117,7751,7585,6192),
    ),
);

//MENCH MAIN MENU:
$config['sources_id_12893'] = array(10876,12896,6205,4536,4535,12749);
$config['sources__12893'] = array(
    10876 => array(
        'm_icon' => '<i class="fas fa-home read" aria-hidden="true"></i>',
        'm_name' => 'HOME',
        'm_desc' => '/',
        'm_parents' => array(12893,4527,1326),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'SAVED',
        'm_desc' => '/s',
        'm_parents' => array(13289,4485,10876,12701,12321,7551,11089,11018,11035,4755,4593,12893),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ',
        'm_desc' => '/r',
        'm_parents' => array(12893,10876,2738),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '/@',
        'm_parents' => array(10939,12489,11035,10876,12893,12761,4527,2738),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEATE',
        'm_desc' => '/i',
        'm_parents' => array(10939,12893,10876,11035,12761,12112,2738),
    ),
    12749 => array(
        'm_icon' => '<i class="fas fa-pen-square idea"></i>',
        'm_name' => 'EDIT',
        'm_desc' => '',
        'm_parents' => array(10984,12893,11035),
    ),
);

//SOURCE MENU:
$config['sources_id_12887'] = array(12193,4341,12888,7267,12712,7279);
$config['sources__12887'] = array(
    12193 => array(
        'm_icon' => '<i class="fab fa-google"></i>',
        'm_name' => 'GOOGLE',
        'm_desc' => '/source/search_google/',
        'm_parents' => array(12891,12887,3088),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas read" aria-hidden="true"></i>',
        'm_name' => 'MENCH INTERACTIONS',
        'm_desc' => '/x?any_source__id=',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735),
    ),
    12888 => array(
        'm_icon' => '<i class="fas fa-shield-check source"></i>',
        'm_name' => 'SOURCE EXPLORE EXPERTS',
        'm_desc' => '/@p12888?source__id=',
        'm_parents' => array(12741,6287,12887),
    ),
    7267 => array(
        'm_icon' => '🔍',
        'm_name' => 'SOURCE ICON SEARCH',
        'm_desc' => '/source/search_icon/',
        'm_parents' => array(12887,6287),
    ),
    12712 => array(
        'm_icon' => '<i class="fad fa-lambda source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REVIEW JSON',
        'm_desc' => '/@p12712?source__id=',
        'm_parents' => array(12887,12741,6287),
    ),
    7279 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SYNC SEARCH INDEX',
        'm_desc' => '/@p7279?obj=4536&object__id=',
        'm_parents' => array(6287,12741,12887,11047,3323,7287),
    ),
);

//IDEA TYPE SELECT ONE:
$config['sources_id_12883'] = array(6907,6684);
$config['sources__12883'] = array(
    6907 => array(
        'm_icon' => '<i class="fas fa-cube idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ANY',
        'm_desc' => '',
        'm_parents' => array(12330,12883,12700,7486,7485,6140,7585,7309,6997,6193),
    ),
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT ONE',
        'm_desc' => '',
        'm_parents' => array(12955,12883,12336,12129,7712,7585,6157,6193),
    ),
);

//IDEA TYPE SELECT SOME:
$config['sources_id_12884'] = array(7231);
$config['sources__12884'] = array(
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SELECT SOME',
        'm_desc' => '',
        'm_parents' => array(12955,12884,12334,12129,7712,7489,7585,6193),
    ),
);

//EXPERT SOURCES:
$config['sources_id_12864'] = array(3084,2750);
$config['sources__12864'] = array(
    3084 => array(
        'm_icon' => '<i class="fad fa-user-astronaut source"></i>',
        'm_name' => 'PERSON',
        'm_desc' => '',
        'm_parents' => array(12968,12864,4983,11035,1278),
    ),
    2750 => array(
        'm_icon' => '<i class="fas fa-space-shuttle source rotate270"></i>',
        'm_name' => 'PUBLICATION',
        'm_desc' => '',
        'm_parents' => array(12968,12864),
    ),
);

//IDEA LINK ONE-WAY:
$config['sources_id_12842'] = array(4229);
$config['sources__12842'] = array(
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(12842,4527,6410,6283,4593,4486),
    ),
);

//IDEA LINK TWO-WAYS:
$config['sources_id_12840'] = array(4228);
$config['sources__12840'] = array(
    4228 => array(
        'm_icon' => '<i class="fas fa-play-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK FIXED',
        'm_desc' => '',
        'm_parents' => array(12840,6410,4593,4486),
    ),
);

//SOURCE LINK MESSAGE DISPLAY:
$config['sources_id_12822'] = array(4259,4257,4261,4260,7657,4255,4256,4258);
$config['sources__12822'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'EMBED',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,4593,4592,4537),
    ),
    7657 => array(
        'm_icon' => '<i class="fas fa-divide source" aria-hidden="true"></i>',
        'm_name' => 'PERCENT',
        'm_desc' => '',
        'm_parents' => array(12822,4593,4592),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'TEXT',
        'm_desc' => '',
        'm_parents' => array(12822,10593,4593,4592),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
);

//MY IDEAS:
$config['sources_id_10573'] = array(4430);
$config['sources__10573'] = array(
    4430 => array(
        'm_icon' => '<i class="fas fa-user-crown source" aria-hidden="true"></i>',
        'm_name' => 'MENCH PLAYERS',
        'm_desc' => '',
        'm_parents' => array(13202,4536,4983,1278,11035,10573),
    ),
);

//PLUGIN RETURN CODE ONLY:
$config['sources_id_12741'] = array(4356,11049,12733,7275,7276,4527,12114,7277,12710,12709,12722,12888,12732,12712,7278,12967,7279,12569);
$config['sources__12741'] = array(
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION IN SECONDS',
        'm_desc' => '',
        'm_parents' => array(13295,6287,12741,11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    11049 => array(
        'm_icon' => '<i class="fad fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12741,6287,11047),
    ),
    12733 => array(
        'm_icon' => '<i class="fad fa-code read"></i>',
        'm_name' => 'IDEA REVIEW READ',
        'm_desc' => '',
        'm_parents' => array(11047,12741,12701,6287),
    ),
    7275 => array(
        'm_icon' => '<i class="fad fa-sync idea mench-spin"></i>',
        'm_name' => 'IDEA SYNC COMMON BASE',
        'm_desc' => '',
        'm_parents' => array(6287,12741,11047,7286,7274),
    ),
    7276 => array(
        'm_icon' => '<i class="fad fa-sync idea mench-spin" aria-hidden="true"></i>',
        'm_name' => 'IDEA SYNC EXTRA INSIGHTS',
        'm_desc' => '',
        'm_parents' => array(6287,12741,11047,7286,7274),
    ),
    4527 => array(
        'm_icon' => '<i class="fas fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY',
        'm_desc' => '',
        'm_parents' => array(4755,6403,12741,6287,4527),
    ),
    12114 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'MENCH WEEKLY GROWTH REPORT',
        'm_desc' => '',
        'm_parents' => array(6287,12741,12701,7274,7569),
    ),
    7277 => array(
        'm_icon' => '',
        'm_name' => 'METADATA CLEAN VARIABLES',
        'm_desc' => '',
        'm_parents' => array(6287,12741,7287,7274),
    ),
    12710 => array(
        'm_icon' => '👤',
        'm_name' => 'MY SESSION VARIABLES',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12709 => array(
        'm_icon' => 'ℹ️',
        'm_name' => 'PHP INFO',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12722 => array(
        'm_icon' => '',
        'm_name' => 'READ REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12888 => array(
        'm_icon' => '<i class="fas fa-shield-check source"></i>',
        'm_name' => 'SOURCE EXPLORE EXPERTS',
        'm_desc' => '',
        'm_parents' => array(12741,6287,12887),
    ),
    12732 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE/IDEA SYNC STATUSES',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12712 => array(
        'm_icon' => '<i class="fad fa-lambda source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12887,12741,6287),
    ),
    7278 => array(
        'm_icon' => '',
        'm_name' => 'SYNC GEPHI INDEX',
        'm_desc' => '',
        'm_parents' => array(6287,12741,7287,7274),
    ),
    12967 => array(
        'm_icon' => '<i class="fad fa-sync source mench-spin" aria-hidden="true"></i>',
        'm_name' => 'SYNC ICONS',
        'm_desc' => '',
        'm_parents' => array(6287,12741,4758,7274),
    ),
    7279 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SYNC SEARCH INDEX',
        'm_desc' => '',
        'm_parents' => array(6287,12741,12887,11047,3323,7287),
    ),
    12569 => array(
        'm_icon' => '<i class="fad fa-weight"></i>',
        'm_name' => 'WEIGHT ALGORITHM',
        'm_desc' => '',
        'm_parents' => array(6287,12741,7274),
    ),
);

//MENCH MESSAGES:
$config['sources_id_12687'] = array(12691,12694,12695);
$config['sources__12687'] = array(
    12691 => array(
        'm_icon' => '',
        'm_name' => 'EMAIL YOURS TRULY LINE',
        'm_desc' => 'Cheers, | Have an Awesome Day, | Have a Blast, | All The Best, | Enjoy, | Have Fun, | Many Thanks,',
        'm_parents' => array(12687),
    ),
    12694 => array(
        'm_icon' => '',
        'm_name' => 'LOADING MESSAGE',
        'm_desc' => 'Are you having a good day today? | Be gentle with yourself today. | Congratulate yourself for the great job you\'re doing | Crunching the latest data, just for you. Hang tight... | Have a glass of water nearby? Time for a sip! | Offer hugs. Someone probably needs them. | You are unique! | Get a drink of water. Stay hydrated! | Have you danced today? | Have you listened to your favourite song recently? 🎵 | Have you stretched recently? | Have you recently told someone you\'re proud of them? | Help is out there. Don\'t be afraid to ask. | Hey! Life is tough, but so are you! 💪 | Hey, jump up for a sec and stretch, yeah? 👐 | I know it\'s cheesey but I hope you have a grate day! | Is there a window you can look through? The world is beautiful. 🌆 | Is your seat comfortable? Can you adjust your chair properly? | It can be hard to get started, can\'t it? That\'s okay, you got this. | It\'s so great to have you here today | Keep growing, keep learning, keep moving forward! | Learning new things is important - open your eyes to the world around you! | Making things awesome... | Novel, new, silly, & unusual activities can help lift your mood. | Play for a few minutes. Doodle, learn solitaire, fold a paper airplane, do something fun. | Don\'t take yourself for granted. You\'re important. | Rest your eyes for a moment. Look at something in the distance and count to five! 🌳 | Self care is important, look after and love yourself, you\'re amazing! | Set aside time for a hobby. Gardening, drone building, knitting, do something for the pure pleasure of it. | So often our power lies not in ourselves, but in how we help others find their own strength | Sometimes doing something nice for somebody else is the best way to feel good about yourself! 👭 | Stop. Breathe. Be here now. | Stop. Take three slow deep breaths. | Take 5 minutes to straighten the space around you. Set a timer. | Take a break before you need it. It will make it easier to prevent burnout. | Take a moment to send a message to someone you love 😻 | Take care of yourself. We need you. | Technology is a tool. Use it wisely. | The impact you leave on the universe can never be erased. | There are no impostors here | There\'s someone who is so so grateful that you exist together. | Today is a great day to let a friend know how much you appreciate them. | Water is good for you year round. If you\'re thirsty, you\'re dehydrated. | We all have superpowers. You included. I hope you are using yours to make your life a joyful one. | When\'s the last time you treated yourself? | With the dawning of a new day comes a clean slate and lots of opportunity. | You are fantastic | You are loved. <3 | You are so very important 💛💛💕 | You can do this! | You cannot compare your successes to the apparent achievements of others. 🌄 | You deserve to be safe and to have nice things happen to you. | You have the power to change the world. | You\'re allowed to start small. 🐞 | have you hugged anyone lately? | it\'s time to check your thirst level, human. | 💗: don\'t forget to take a little bit of time to say hi to a friend | 🌸: remember to let your eyes rest, maybe by looking at a plant... | 🙌: take a second to adjust your posture | 😎🌈💕',
        'm_parents' => array(12687),
    ),
    12695 => array(
        'm_icon' => '',
        'm_name' => 'SAVING MESSAGE',
        'm_desc' => 'Learning everyday 😎 | Growing with you 🌸 | Getting smarter ^~^',
        'm_parents' => array(12687),
    ),
);

//IDEA TABS DEFAULT SELECTED:
$config['sources_id_12675'] = array(11020);
$config['sources__12675'] = array(
    11020 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'NEXT IDEAS',
        'm_desc' => '',
        'm_parents' => array(12675,12413,11018),
    ),
);

//IDEA TABS HIDE IF ZERO:
$config['sources_id_12677'] = array(12969,6255,6146);
$config['sources__12677'] = array(
    12969 => array(
        'm_icon' => '<i class="fas fa-eye read"></i>',
        'm_name' => 'MY READS',
        'm_desc' => '',
        'm_parents' => array(13210,12228,11035,11018,12677,12701,4527,11089),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(6205,12500,12677,10939,11018,12467,11089,12228,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'UNFINISHED',
        'm_desc' => '',
        'm_parents' => array(12701,12677,11018,12446,11089,12228,4527),
    ),
);

//PLAYER MENU:
$config['sources_id_12500'] = array(12205,12274,12273,6255,6415,6287,7291);
$config['sources__12500'] = array(
    12205 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(12500,11035),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(10939,11089,4536,12500,12467,12228,4527),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13291,13298,4535,12500,12571,12467,12321,11089,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(6205,12500,12677,10939,11018,12467,11089,12228,4527),
    ),
    6415 => array(
        'm_icon' => '<i class="fas fa-trash-alt read" aria-hidden="true"></i>',
        'm_name' => 'CLEAR ALL READS',
        'm_desc' => '',
        'm_parents' => array(12500,4755,4593),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => '',
        'm_parents' => array(13297,6405,6194,12699,12500,10876,11035,4527),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => '',
        'm_parents' => array(12500,10876,11035),
    ),
);

//IDEA NOTES STATUS:
$config['sources_id_12012'] = array(6176,6173);
$config['sources__12012'] = array(
    6176 => array(
        'm_icon' => '<i class="far fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="fas fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'UNPUBLISH',
        'm_desc' => '',
        'm_parents' => array(12012,10686,10678,10673,6186),
    ),
);

//MENCH COINS:
$config['sources_id_12467'] = array(12274,12273,6255);
$config['sources__12467'] = array(
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(10939,11089,4536,12500,12467,12228,4527),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13291,13298,4535,12500,12571,12467,12321,11089,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(6205,12500,12677,10939,11018,12467,11089,12228,4527),
    ),
);

//IDEA LIST EDITOR:
$config['sources_id_12589'] = array(12591,12592,12611,12612);
$config['sources__12589'] = array(
    12591 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE+',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12592 => array(
        'm_icon' => '<i class="fas fa-minus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE-',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12611 => array(
        'm_icon' => '<i class="fad fa-layer-plus idea" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS+',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12612 => array(
        'm_icon' => '<i class="fad fa-layer-minus idea" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS-',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
);

//AVOID PREFIX REMOVAL:
$config['sources_id_12588'] = array(4341);
$config['sources__12588'] = array(
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas read" aria-hidden="true"></i>',
        'm_name' => 'MENCH INTERACTIONS',
        'm_desc' => '',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735),
    ),
);

//SIGN IN/UP:
$config['sources_id_4269'] = array(3288,13025,3286);
$config['sources__4269'] = array(
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => '',
        'm_parents' => array(6404,13023,4269,12103,6225,4755),
    ),
    13025 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'FULL NAME',
        'm_desc' => '',
        'm_parents' => array(4269),
    ),
    3286 => array(
        'm_icon' => '<i class="fas fa-key" aria-hidden="true"></i>',
        'm_name' => 'PASSWORD',
        'm_desc' => '',
        'm_parents' => array(13014,4269,7578,6225,4755),
    ),
);

//FILE UPLOADING ALLOWED:
$config['sources_id_12359'] = array(12419,4231);
$config['sources__12359'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(13304,13291,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,12322,10593,4986,4603,4593,4485),
    ),
);

//PORTFOLIO EDITOR UPPERCASE:
$config['sources_id_12577'] = array(4999,4998,5000,5982,5981,11956);
$config['sources__12577'] = array(
    4999 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fad fa-layer-minus source"></i>',
        'm_name' => 'PROFILE- ALL',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE+ ALL',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE+ IF @SOURCE',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
);

//SOURCE LAYOUT SHOW EVEN IF ZERO:
$config['sources_id_12574'] = array(6225,11029,11030);
$config['sources__12574'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => '',
        'm_parents' => array(12574,11089,12205,11035,4527),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => '',
        'm_parents' => array(13004,12574,12571,11089,11028),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PROFILE',
        'm_desc' => '',
        'm_parents' => array(13004,12571,12574,11089,11028),
    ),
);

//SOURCE STATUS FEATURED:
$config['sources_id_12575'] = array(12563);
$config['sources__12575'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
);

//SOURCE LAYOUT OPEN BY DEFAULT:
$config['sources_id_12571'] = array(12273,11029,11030,13046);
$config['sources__12571'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13291,13298,4535,12500,12571,12467,12321,11089,12228,4527),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => '',
        'm_parents' => array(13004,12574,12571,11089,11028),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PROFILE',
        'm_desc' => '',
        'm_parents' => array(13004,12571,12574,11089,11028),
    ),
    13046 => array(
        'm_icon' => '<i class="fas fa-exchange rotate source"></i>',
        'm_name' => 'RELATED',
        'm_desc' => '',
        'm_parents' => array(12571,11089),
    ),
);

//SOURCE LINK VISUAL:
$config['sources_id_12524'] = array(4259,4257,4261,4260,4258);
$config['sources__12524'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'EMBED',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
);

//SYNC ICONS IF NEW:
$config['sources_id_12523'] = array(6293);
$config['sources__12523'] = array(
    6293 => array(
        'm_icon' => '<i class="fas fa-image source"></i>',
        'm_name' => 'GIPHY GIFS',
        'm_desc' => '',
        'm_parents' => array(12523,12891,1326),
    ),
);

//READ ICONS:
$config['sources_id_12446'] = array(12447,12448,6146);
$config['sources__12446'] = array(
    12447 => array(
        'm_icon' => '<i class="fad fa-spinner-third read fa-spin" aria-hidden="true"></i>',
        'm_name' => 'READ IN PROGRESS',
        'm_desc' => '',
        'm_parents' => array(12446),
    ),
    12448 => array(
        'm_icon' => '<i class="far fa-circle read"></i>',
        'm_name' => 'READ NOT STARTED',
        'm_desc' => '',
        'm_parents' => array(12446),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'UNFINISHED',
        'm_desc' => '',
        'm_parents' => array(12701,12677,11018,12446,11089,12228,4527),
    ),
);

//IDEA TEXT INPUT SHOW ICON:
$config['sources_id_12420'] = array(4356);
$config['sources__12420'] = array(
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION IN SECONDS',
        'm_desc' => '',
        'm_parents' => array(13295,6287,12741,11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
);

//IDEA TREE:
$config['sources_id_12413'] = array(6170,6169,13177,11020,11019);
$config['sources__12413'] = array(
    6170 => array(
        'm_icon' => '<i class="fas fa-sitemap idea"></i>',
        'm_name' => 'IDEA TREE MAX',
        'm_desc' => '',
        'm_parents' => array(12413,6232,6214,6159),
    ),
    6169 => array(
        'm_icon' => '<i class="fal fa-sitemap idea"></i>',
        'm_name' => 'IDEA TREE MIN',
        'm_desc' => '',
        'm_parents' => array(12413,6232,6214,6159),
    ),
    13177 => array(
        'm_icon' => '<i class="fas fa-circle idea"></i>',
        'm_name' => 'IDEA TREE RANGE',
        'm_desc' => '',
        'm_parents' => array(12413),
    ),
    11020 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'NEXT IDEAS',
        'm_desc' => '',
        'm_parents' => array(12675,12413,11018),
    ),
    11019 => array(
        'm_icon' => '<i class="fas fa-step-backward idea" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS IDEAS',
        'm_desc' => '',
        'm_parents' => array(13294,12413,10990),
    ),
);

//HOME:
$config['sources_id_10876'] = array(4535,7291,4341,6287,6205,12896,4269,4536);
$config['sources__10876'] = array(
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEATE',
        'm_desc' => 'https://mench.com/i',
        'm_parents' => array(10939,12893,10876,11035,12761,12112,2738),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => 'https://mench.com/@o',
        'm_parents' => array(12500,10876,11035),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas read" aria-hidden="true"></i>',
        'm_name' => 'MENCH INTERACTIONS',
        'm_desc' => 'https://mench.com/x',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => 'https://mench.com/@p',
        'm_parents' => array(13297,6405,6194,12699,12500,10876,11035,4527),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ',
        'm_desc' => 'https://mench.com/r',
        'm_parents' => array(12893,10876,2738),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'SAVED',
        'm_desc' => 'https://mench.com/s',
        'm_parents' => array(13289,4485,10876,12701,12321,7551,11089,11018,11035,4755,4593,12893),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in-alt" aria-hidden="true"></i>',
        'm_name' => 'SIGN IN/UP',
        'm_desc' => 'https://mench.com/@s',
        'm_parents' => array(10876,4527,11035),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => 'https://mench.com/@',
        'm_parents' => array(10939,12489,11035,10876,12893,12761,4527,2738),
    ),
);

//SOURCE LINK TYPE CUSTOM UI:
$config['sources_id_12403'] = array(4257);
$config['sources__12403'] = array(
    4257 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK EMBED',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
);

//SOURCE STATUS SYNC:
$config['sources_id_12401'] = array(4251,6178,10654);
$config['sources__12401'] = array(
    4251 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'ADDED',
        'm_desc' => '',
        'm_parents' => array(12274,12401,12149,12141,10593,4593),
    ),
    6178 => array(
        'm_icon' => '<i class="fad fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'DELETED',
        'm_desc' => '',
        'm_parents' => array(4593,12401,6177),
    ),
    10654 => array(
        'm_icon' => '<i class="fad fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(12401,4593),
    ),
);

//IDEA SYNC STATUS:
$config['sources_id_12400'] = array(4250,6182,10648);
$config['sources__12400'] = array(
    4250 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,12149,10593,4593),
    ),
    6182 => array(
        'm_icon' => '<i class="fad fa-trash-alt idea" aria-hidden="true"></i>',
        'm_name' => 'DELETED',
        'm_desc' => '',
        'm_parents' => array(12400,4593,4737),
    ),
    10648 => array(
        'm_icon' => '<i class="fad fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(12400,4593),
    ),
);

//SOURCE:
$config['sources_id_4536'] = array(4430,6206,13207,13296,12897,12274,4758,4600);
$config['sources__4536'] = array(
    4430 => array(
        'm_icon' => '<i class="fas fa-user-crown source" aria-hidden="true"></i>',
        'm_name' => 'MENCH PLAYERS',
        'm_desc' => '',
        'm_parents' => array(13202,4536,4983,1278,11035,10573),
    ),
    6206 => array(
        'm_icon' => '<i class="fas fa-table source" aria-hidden="true"></i>',
        'm_name' => 'MENCH SOURCES',
        'm_desc' => '',
        'm_parents' => array(4527,7735,4536),
    ),
    13207 => array(
        'm_icon' => '<i class="fas fa-home source"></i>',
        'm_name' => 'SOURCE HOME',
        'm_desc' => '',
        'm_parents' => array(4527,4536),
    ),
    13296 => array(
        'm_icon' => '<i class="fad fa-crop-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    12897 => array(
        'm_icon' => '<i class="fas fa-gamepad source"></i>',
        'm_name' => 'SOURCE PLAYERS',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(10939,11089,4536,12500,12467,12228,4527),
    ),
    4758 => array(
        'm_icon' => '<i class="fas fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE SETTINGS',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    4600 => array(
        'm_icon' => '<i class="fad fa-shapes source"></i>',
        'm_name' => 'SOURCE TYPES',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
);

//IDEA TYPE COMPLETE IF EMPTY:
$config['sources_id_12330'] = array(6677,6914,6907);
$config['sources__12330'] = array(
    6677 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'READ & NEXT',
        'm_desc' => '',
        'm_parents' => array(13022,12330,7585,4559,6192),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ALL',
        'm_desc' => '',
        'm_parents' => array(12330,12700,7486,7485,6140,6192,7585,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ANY',
        'm_desc' => '',
        'm_parents' => array(12330,12883,12700,7486,7485,6140,7585,7309,6997,6193),
    ),
);

//READ UNLOCKS:
$config['sources_id_12327'] = array(7485,7486,6997);
$config['sources__12327'] = array(
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//READ EXPANSIONS:
$config['sources_id_12326'] = array(12336,12334,6140);
$config['sources__12326'] = array(
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
);

//TEMPLATE IDEA MESSAGES:
$config['sources_id_12322'] = array(12419,4601,4231);
$config['sources__12322'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(13304,13291,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags idea" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12322,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,12322,10593,4986,4603,4593,4485),
    ),
);

//TEMPLATE IDEA READ:
$config['sources_id_12321'] = array(7545,12273,10573,12896);
$config['sources__12321'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(13298,13291,12273,12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13291,13298,4535,12500,12571,12467,12321,11089,12228,4527),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-lightbulb-on idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(13211,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'SAVED',
        'm_desc' => '',
        'm_parents' => array(13289,4485,10876,12701,12321,7551,11089,11018,11035,4755,4593,12893),
    ),
);

//AVATARS SUPER:
$config['sources_id_12279'] = array(12280,12281,12282,12286,12287,12288,12308,12309,12310,12234,12233,10965,12236,12235,10979,12295,12294,12293,12296,12297,12298,12300,12301,12299,12237,12238,10978,12314,12315,12316,12240,12239,10963,12241,12242,12207,12244,12243,10966,12245,12246,10976,12248,12247,10962,12249,12250,10975,12252,12251,10982,12253,12254,10970,12302,12303,12304,12256,12255,10972,12306,12307,12305,12257,12258,10969,12312,12313,12311,12260,12259,10960,12277,12276,12278,12439,12262,10981,12264,12263,10968,12265,12266,10974,12290,12291,12292,12268,12267,12206,12269,12270,10958,12285,12284,12283,12272,12271,12231);
$config['sources__12279'] = array(
    12280 => array(
        'm_icon' => '<i class="fas fa-alicorn source"></i>',
        'm_name' => 'ALICORN BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12281 => array(
        'm_icon' => '<i class="far fa-alicorn source"></i>',
        'm_name' => 'ALICORN LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12282 => array(
        'm_icon' => '<i class="fad fa-alicorn source"></i>',
        'm_name' => 'ALICORN MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
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
        'm_parents' => array(12279),
    ),
    12307 => array(
        'm_icon' => '<i class="far fa-pegasus source"></i>',
        'm_name' => 'PEGASUS LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12305 => array(
        'm_icon' => '<i class="fad fa-pegasus source" aria-hidden="true"></i>',
        'm_name' => 'PEGASUS MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
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

//SOURCES:
$config['sources_id_12274'] = array(4251);
$config['sources__12274'] = array(
    4251 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ADDED',
        'm_desc' => '',
        'm_parents' => array(12274,12401,12149,12141,10593,4593),
    ),
);

//IDEAS:
$config['sources_id_12273'] = array(7545,4983,4231);
$config['sources__12273'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(13298,13291,12273,12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,12322,10593,4986,4603,4593,4485),
    ),
);

//READ COMPLETION:
$config['sources_id_12229'] = array(6143,7492,6157,7489,12117,4559,6144,7485,7486,6997);
$config['sources__12229'] = array(
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//READ PROGRESS:
$config['sources_id_12227'] = array(4235,12336,12334,7495,6143,7492,6140,6157,7489,12117,4559,6144,7485,7486,6997);
$config['sources__12227'] = array(
    4235 => array(
        'm_icon' => '<i class="fas fa-play read" aria-hidden="true"></i>',
        'm_name' => 'GET STARTED',
        'm_desc' => '',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone read" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(12969,12227,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//READ TYPE COIN AWARD:
$config['sources_id_12141'] = array(4983,4251,6157,7489,12117,4559,6144,7485,7486,6997);
$config['sources__12141'] = array(
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ADDED',
        'm_desc' => '',
        'm_parents' => array(12274,12401,12149,12141,10593,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//IDEA STATUS FEATURED:
$config['sources_id_12138'] = array(12137);
$config['sources__12138'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA FEATURED',
        'm_desc' => '',
        'm_parents' => array(10986,10648,12138,7356,7355,4737),
    ),
);

//MENCH TEXT INPUTS:
$config['sources_id_12112'] = array(4356,4535,4736,4358,6197,4739,4735);
$config['sources__12112'] = array(
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION IN SECONDS',
        'm_desc' => '',
        'm_parents' => array(13295,6287,12741,11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEATE',
        'm_desc' => '',
        'm_parents' => array(10939,12893,10876,11035,12761,12112,2738),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TITLE',
        'm_desc' => '',
        'm_parents' => array(13294,12994,6404,10990,12112,10644,6232,6201),
    ),
    4358 => array(
        'm_icon' => '<i class="fas fa-comment-alt-check idea" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => '',
        'm_parents' => array(12700,12112,10663,6103,6410,6232),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TITLE',
        'm_desc' => '',
        'm_parents' => array(13296,13025,6404,12112,12232,10646,5000,4998,4999,6232,6206),
    ),
    4739 => array(
        'm_icon' => '<i class="fas fa-temperature-up idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(12112,6402,6232),
    ),
    4735 => array(
        'm_icon' => '<i class="fas fa-temperature-down idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(12112,6402,6232),
    ),
);

//MENCH DROPDOWN MENUS:
$config['sources_id_12079'] = array(4486,4737,7585,12500,10869);
$config['sources__12079'] = array(
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => '',
        'm_parents' => array(6232,12700,11054,12079,10662,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13295,11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(13295,11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    12500 => array(
        'm_icon' => '<i class="fas fa-user" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MENU',
        'm_desc' => '',
        'm_parents' => array(7524,12079,12497,12823,4527),
    ),
    10869 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'TOPICS',
        'm_desc' => '',
        'm_parents' => array(12079,6225,6122,7305,4527),
    ),
);

//SOURCE TABS:
$config['sources_id_11089'] = array(6225,10573,12969,12896,12274,12273,6255,6146,11030,11029,13046,12419,7545);
$config['sources__11089'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => '',
        'm_parents' => array(12574,11089,12205,11035,4527),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-lightbulb-on idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(13211,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    12969 => array(
        'm_icon' => '<i class="fas fa-eye read"></i>',
        'm_name' => 'MY READS',
        'm_desc' => '',
        'm_parents' => array(13210,12228,11035,11018,12677,12701,4527,11089),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'SAVED',
        'm_desc' => '',
        'm_parents' => array(13289,4485,10876,12701,12321,7551,11089,11018,11035,4755,4593,12893),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(10939,11089,4536,12500,12467,12228,4527),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13291,13298,4535,12500,12571,12467,12321,11089,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(6205,12500,12677,10939,11018,12467,11089,12228,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'UNFINISHED',
        'm_desc' => '',
        'm_parents' => array(12701,12677,11018,12446,11089,12228,4527),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PROFILE',
        'm_desc' => '',
        'm_parents' => array(13004,12571,12574,11089,11028),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => '',
        'm_parents' => array(13004,12574,12571,11089,11028),
    ),
    13046 => array(
        'm_icon' => '<i class="fas fa-exchange rotate source"></i>',
        'm_name' => 'RELATED',
        'm_desc' => '',
        'm_parents' => array(12571,11089),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(13304,13291,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(13298,13291,12273,12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
);

//READ ALL CONNECTIONS:
$config['sources_id_11081'] = array(4429,4368,4371,4369,4364,4593,4366);
$config['sources__11081'] = array(
    4429 => array(
        'm_icon' => '<i class="fas fa-triangle rotate180" aria-hidden="true"></i>',
        'm_name' => 'DOWN',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-triangle rotate270" aria-hidden="true"></i>',
        'm_name' => 'LEFT',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link" aria-hidden="true"></i>',
        'm_name' => 'REFERENCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-triangle rotate90" aria-hidden="true"></i>',
        'm_name' => 'RIGHT',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4364 => array(
        'm_icon' => '<i class="fas fa-user" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-triangle" aria-hidden="true"></i>',
        'm_name' => 'UP',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
);

//MENCH VARIABLE:
$config['sources_id_6232'] = array(4356,6202,4486,6159,6208,13301,6168,6283,12885,6228,6165,6162,6161,6167,4737,4736,6170,6169,7585,13029,4429,4367,4368,4358,4372,6103,4371,4369,4370,4364,6186,4362,4593,4366,6198,6160,6172,6207,6177,6197,13030,4739,4735);
$config['sources__6232'] = array(
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION IN SECONDS',
        'm_desc' => 'idea__duration',
        'm_parents' => array(13295,6287,12741,11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    6202 => array(
        'm_icon' => '<i class="fas fa-hashtag idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA ID',
        'm_desc' => 'idea__id',
        'm_parents' => array(6232,6215,6201),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => 'read__type',
        'm_parents' => array(6232,12700,11054,12079,10662,4527),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA METADATA',
        'm_desc' => 'idea__metadata',
        'm_parents' => array(11049,6232,6201,6195),
    ),
    6208 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA ALGOLIA ID',
        'm_desc' => 'algolia__id',
        'm_parents' => array(6232,6215,3323,6159),
    ),
    13301 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA CERTIFICATES',
        'm_desc' => 'idea___certificates',
        'm_parents' => array(6214,6159,6232),
    ),
    6168 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA COMMON STEPS',
        'm_desc' => 'idea___common_reads',
        'm_parents' => array(6232,6214,6159),
    ),
    6283 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA EXPANSION CONDITIONAL',
        'm_desc' => 'idea___expansion_conditional',
        'm_parents' => array(6214,6232,6159),
    ),
    12885 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA EXPANSION SOME',
        'm_desc' => 'idea___expansion_some',
        'm_parents' => array(6214,6232,6159),
    ),
    6228 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA EXPANSION STEPS',
        'm_desc' => 'idea___expansion_reads',
        'm_parents' => array(6232,6214,6159),
    ),
    6165 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA EXPERTS',
        'm_desc' => 'idea___experts',
        'm_parents' => array(6232,6214,6159),
    ),
    6162 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'IDEA METADATA MAXIMUM SECONDS',
        'm_desc' => 'idea___max_seconds',
        'm_parents' => array(13292,4739,6232,6214,6159),
    ),
    6161 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA MINIMUM SECONDS',
        'm_desc' => 'idea___min_seconds',
        'm_parents' => array(13292,4735,6232,6214,6159),
    ),
    6167 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA SOURCES',
        'm_desc' => 'idea___content',
        'm_parents' => array(6232,6214,6159),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => 'idea__status',
        'm_parents' => array(13295,11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TITLE',
        'm_desc' => 'idea__title',
        'm_parents' => array(13294,12994,6404,10990,12112,10644,6232,6201),
    ),
    6170 => array(
        'm_icon' => '<i class="fas fa-sitemap idea"></i>',
        'm_name' => 'IDEA TREE MAX',
        'm_desc' => 'idea___max_reads',
        'm_parents' => array(12413,6232,6214,6159),
    ),
    6169 => array(
        'm_icon' => '<i class="fal fa-sitemap idea"></i>',
        'm_name' => 'IDEA TREE MIN',
        'm_desc' => 'idea___min_reads',
        'm_parents' => array(12413,6232,6214,6159),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => 'idea__type',
        'm_parents' => array(13295,11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    13029 => array(
        'm_icon' => '<i class="fas fa-weight idea"></i>',
        'm_name' => 'IDEA WEIGHT',
        'm_desc' => 'idea__weight',
        'm_parents' => array(6214,6232,6201),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-triangle rotate180" aria-hidden="true"></i>',
        'm_name' => 'READ DOWN',
        'm_desc' => 'read__down',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4367 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'READ ID',
        'm_desc' => 'read__id',
        'm_parents' => array(6232,6215,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-triangle rotate270" aria-hidden="true"></i>',
        'm_name' => 'READ LEFT',
        'm_desc' => 'read__left',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4358 => array(
        'm_icon' => '<i class="fas fa-comment-alt-check idea" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => 'tr__assessment_points',
        'm_parents' => array(12700,12112,10663,6103,6410,6232),
    ),
    4372 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'READ MESSAGE',
        'm_desc' => 'read__message',
        'm_parents' => array(7578,10679,10657,5001,6232,4341),
    ),
    6103 => array(
        'm_icon' => '<i class="fas fa-lambda"></i>',
        'm_name' => 'READ METADATA',
        'm_desc' => 'read__metadata',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link" aria-hidden="true"></i>',
        'm_name' => 'READ REFERENCE',
        'm_desc' => 'read__reference',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-triangle rotate90" aria-hidden="true"></i>',
        'm_name' => 'READ RIGHT',
        'm_desc' => 'read__right',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fas fa-bars" aria-hidden="true"></i>',
        'm_name' => 'READ SORT',
        'm_desc' => 'read__sort',
        'm_parents' => array(13007,13006,10676,10675,6232,4341),
    ),
    4364 => array(
        'm_icon' => '<i class="fas fa-user" aria-hidden="true"></i>',
        'm_name' => 'READ SOURCE',
        'm_desc' => 'read__source',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => 'read__status',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="fas fa-clock" aria-hidden="true"></i>',
        'm_name' => 'READ TIME',
        'm_desc' => 'read__time',
        'm_parents' => array(6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE',
        'm_desc' => 'read__type',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-triangle" aria-hidden="true"></i>',
        'm_name' => 'READ UP',
        'm_desc' => 'read__up',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ICON',
        'm_desc' => 'source__icon',
        'm_parents' => array(12605,10653,5943,10625,6232,6206),
    ),
    6160 => array(
        'm_icon' => '<i class="fas fa-at source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ID',
        'm_desc' => 'source__id',
        'm_parents' => array(6232,6215,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda source"></i>',
        'm_name' => 'SOURCE METADATA',
        'm_desc' => 'source__metadata',
        'm_parents' => array(6232,6206,6195),
    ),
    6207 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'SOURCE METADATA ALGOLIA ID',
        'm_desc' => 'algolia__id',
        'm_parents' => array(3323,6232,6215,6172),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => 'source__status',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TITLE',
        'm_desc' => 'source__title',
        'm_parents' => array(13296,13025,6404,12112,12232,10646,5000,4998,4999,6232,6206),
    ),
    13030 => array(
        'm_icon' => '<i class="fas fa-weight source"></i>',
        'm_name' => 'SOURCE WEIGHT',
        'm_desc' => 'source__weight',
        'm_parents' => array(6214,6232,6206),
    ),
    4739 => array(
        'm_icon' => '<i class="fas fa-temperature-up idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX PERCENTAGE',
        'm_desc' => 'tr__conditional_score_max',
        'm_parents' => array(12112,6402,6232),
    ),
    4735 => array(
        'm_icon' => '<i class="fas fa-temperature-down idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN PERCENTAGE',
        'm_desc' => 'tr__conditional_score_min',
        'm_parents' => array(12112,6402,6232),
    ),
);

//SOURCE LINK FILE EXTENSIONS:
$config['sources_id_11080'] = array(4259,4261,4260,4256,4258);
$config['sources__11080'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'pcm|wav|aiff|mp3|aac|ogg|wma|flac|alac|m4a|m4b|m4p',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'pdf|pdc|doc|docx|tex|txt|7z|rar|zip|csv|sql|tar|xml|exe',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'jpeg|jpg|png|gif|tiff|bmp|img|svg|ico|webp',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,4593,4592,4537),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => 'htm|html',
        'm_parents' => array(12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'mp4|m4v|m4p|avi|mov|flv|f4v|f4p|f4a|f4b|wmv|webm|mkv|vob|ogv|ogg|3gp|mpg|mpeg|m2v',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
);

//SOURCE LINK UPLOAD FILE:
$config['sources_id_11059'] = array(4259,4261,4260,4258);
$config['sources__11059'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'audio',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'file',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'image',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'video',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
);

//MENCH CONFIG VARIABLES:
$config['sources_id_6404'] = array(12678,7274,12904,3288,12176,4356,4485,4736,11064,11065,13014,11063,13005,11060,11079,11066,11057,11056,12331,12427,12088,13206,11986,12232,6197,12568,12565);
$config['sources__6404'] = array(
    12678 => array(
        'm_icon' => '',
        'm_name' => 'ALGOLIA SEARCH ENABLED (0 OR 1)',
        'm_desc' => '1',
        'm_parents' => array(3323,6404),
    ),
    7274 => array(
        'm_icon' => '<i class="fas fa-clock mench-spin" aria-hidden="true"></i>',
        'm_name' => 'CRON JOBS',
        'm_desc' => '/usr/bin/php /var/www/platform/index.php source plugin',
        'm_parents' => array(6404,6287,6403,12999),
    ),
    12904 => array(
        'm_icon' => '<i class="fad fa-book" aria-hidden="true"></i>',
        'm_name' => 'DEFAULT BOOK COVER',
        'm_desc' => '//s3foundation.s3-us-west-2.amazonaws.com/4981b7cace14d274a4865e2a416b372b.jpg',
        'm_parents' => array(6404,1,7524),
    ),
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => 'support@mench.com',
        'm_parents' => array(6404,13023,4269,12103,6225,4755),
    ),
    12176 => array(
        'm_icon' => '<i class="fad fa-clock idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DEFAULT TIME SECONDS',
        'm_desc' => '30',
        'm_parents' => array(6404),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION IN SECONDS',
        'm_desc' => '7200',
        'm_parents' => array(13295,6287,12741,11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES',
        'm_desc' => '1000',
        'm_parents' => array(6404,4535,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TITLE',
        'm_desc' => '100',
        'm_parents' => array(13294,12994,6404,10990,12112,10644,6232,6201),
    ),
    11064 => array(
        'm_icon' => '',
        'm_name' => 'ITEMS PER PAGE',
        'm_desc' => '100',
        'm_parents' => array(6404),
    ),
    11065 => array(
        'm_icon' => '',
        'm_name' => 'MAGIC LINK VALID SECONDS',
        'm_desc' => '3600',
        'm_parents' => array(6404),
    ),
    13014 => array(
        'm_icon' => '<i class="fad fa-key idea" aria-hidden="true"></i>',
        'm_name' => 'MASTER PASSWORD',
        'm_desc' => 'fb618a7622654d8fd68f85da02c0ae0b7b8765fffeecedd5c32bda80fc2cf9e8',
        'm_parents' => array(6404),
    ),
    11063 => array(
        'm_icon' => '',
        'm_name' => 'MAX FILE SIZE [MB]',
        'm_desc' => '25',
        'm_parents' => array(6404),
    ),
    13005 => array(
        'm_icon' => '<i class="fas fa-bars source"></i>',
        'm_name' => 'MAX SOURCES SORTABLE',
        'm_desc' => '34',
        'm_parents' => array(6404),
    ),
    11060 => array(
        'm_icon' => '<i class="fad fa-code"></i>',
        'm_name' => 'MENCH PLATFORM VERSION',
        'm_desc' => 'v1.413',
        'm_parents' => array(6404),
    ),
    11079 => array(
        'm_icon' => '',
        'm_name' => 'MENCH TIMEZONE',
        'm_desc' => 'America/Los_Angeles',
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
    12427 => array(
        'm_icon' => '',
        'm_name' => 'READ TIME MINIMUM SECONDS',
        'm_desc' => '3',
        'm_parents' => array(6404,4356),
    ),
    12088 => array(
        'm_icon' => '',
        'm_name' => 'SHOW TEXT COUNTER THRESHOLD',
        'm_desc' => '0.8',
        'm_parents' => array(6404),
    ),
    13206 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE LIST MAX',
        'm_desc' => '20',
        'm_parents' => array(6404),
    ),
    11986 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE LIST VISIBLE',
        'm_desc' => '5',
        'm_parents' => array(6404),
    ),
    12232 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE NAME MIN LENGTH',
        'm_desc' => '2',
        'm_parents' => array(6404),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TITLE',
        'm_desc' => '233',
        'm_parents' => array(13296,13025,6404,12112,12232,10646,5000,4998,4999,6232,6206),
    ),
    12568 => array(
        'm_icon' => '',
        'm_name' => 'WEIGHT ALGORITHM INTERACTIONS',
        'm_desc' => '1',
        'm_parents' => array(12569,6404),
    ),
    12565 => array(
        'm_icon' => '',
        'm_name' => 'WEIGHT ALGORITHM RATE',
        'm_desc' => '89',
        'm_parents' => array(12569,6404),
    ),
);

//MENCH MEMORY JAVASCRIPT:
$config['sources_id_11054'] = array(3000,4486,4983,4737,7356,7355,7585,2738,6404,6201,12687,10573,6186,4592,6177,7357);
$config['sources__11054'] = array(
    3000 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT CONTENT',
        'm_desc' => '',
        'm_parents' => array(13207,11054,4600,4527),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => '',
        'm_parents' => array(6232,12700,11054,12079,10662,4527),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13295,11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'IDEA STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(13295,11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    2738 => array(
        'm_icon' => '<img src="https://mench.com/img/mench.png" class="mench-spin no-radius">',
        'm_name' => 'MENCH',
        'm_desc' => '',
        'm_parents' => array(7305,12891,12497,2,11054,12041,4527,1,7312),
    ),
    6404 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'MENCH CONFIG VARIABLES',
        'm_desc' => '',
        'm_parents' => array(11054,4527,6403),
    ),
    6201 => array(
        'm_icon' => '<i class="fas fa-table idea" aria-hidden="true"></i>',
        'm_name' => 'MENCH IDEAS',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7735,4535),
    ),
    12687 => array(
        'm_icon' => '<i class="fad fa-comments-alt" aria-hidden="true"></i>',
        'm_name' => 'MENCH MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6403,11054,4527),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-lightbulb-on idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(13211,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4592 => array(
        'm_icon' => '<i class="fas fa-link source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINKS',
        'm_desc' => '',
        'm_parents' => array(11054,5982,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    7357 => array(
        'm_icon' => '<i class="far fa-globe source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(12572,11054,4527),
    ),
);

//IDEA MENU:
$config['sources_id_11047'] = array(7264,4356,11049,12733,7275,7276,4341,7279);
$config['sources__11047'] = array(
    7264 => array(
        'm_icon' => '<i class="fad fa-sitemap idea"></i>',
        'm_name' => 'IDEA BIRDS EYE MARKS',
        'm_desc' => '/@p7264?idea__id=',
        'm_parents' => array(11047,6287),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION IN SECONDS',
        'm_desc' => '/@p4356?idea__id=',
        'm_parents' => array(13295,6287,12741,11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    11049 => array(
        'm_icon' => '<i class="fad fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA REVIEW JSON',
        'm_desc' => '/@p11049?idea__id=',
        'm_parents' => array(12741,6287,11047),
    ),
    12733 => array(
        'm_icon' => '<i class="fad fa-code read"></i>',
        'm_name' => 'IDEA REVIEW READ',
        'm_desc' => '/@p12733?idea__id=',
        'm_parents' => array(11047,12741,12701,6287),
    ),
    7275 => array(
        'm_icon' => '<i class="fad fa-sync idea mench-spin"></i>',
        'm_name' => 'IDEA SYNC COMMON BASE',
        'm_desc' => '/@p7275?idea__id=',
        'm_parents' => array(6287,12741,11047,7286,7274),
    ),
    7276 => array(
        'm_icon' => '<i class="fad fa-sync idea mench-spin" aria-hidden="true"></i>',
        'm_name' => 'IDEA SYNC EXTRA INSIGHTS',
        'm_desc' => '/@p7276?idea__id=',
        'm_parents' => array(6287,12741,11047,7286,7274),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas read" aria-hidden="true"></i>',
        'm_name' => 'MENCH INTERACTIONS',
        'm_desc' => '/x?any_idea__id=',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735),
    ),
    7279 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SYNC SEARCH INDEX',
        'm_desc' => '/@p7279?obj=4535&object__id=',
        'm_parents' => array(6287,12741,12887,11047,3323,7287),
    ),
);

//MENCH NAVIGATION:
$config['sources_id_11035'] = array(11068,6225,12749,3084,13216,12707,4235,12991,12211,4535,7291,4341,4430,12205,10573,12969,6287,12750,12896,7256,13023,4269,4536,4997,12275,13007,10957,7540);
$config['sources__11035'] = array(
    11068 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => '1-CLICK LOGIN',
        'm_desc' => '',
        'm_parents' => array(11035,11065),
    ),
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => 'Manage avatar, superpowers, subscription & name',
        'm_parents' => array(12574,11089,12205,11035,4527),
    ),
    12749 => array(
        'm_icon' => '<i class="fas fa-pen-square idea"></i>',
        'm_name' => 'EDIT',
        'm_desc' => '',
        'm_parents' => array(10984,12893,11035),
    ),
    3084 => array(
        'm_icon' => '<i class="fad fa-user-astronaut source"></i>',
        'm_name' => 'EXPERT PERSON',
        'm_desc' => '',
        'm_parents' => array(12968,12864,4983,11035,1278),
    ),
    13216 => array(
        'm_icon' => '<i class="fas fa-star idea"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    12707 => array(
        'm_icon' => '<i class="far fa-filter" aria-hidden="true"></i>',
        'm_name' => 'FILTER INTERACTIONS',
        'm_desc' => '',
        'm_parents' => array(11035,12701),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-play read" aria-hidden="true"></i>',
        'm_name' => 'GET STARTED',
        'm_desc' => '',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    12991 => array(
        'm_icon' => '<i class="fas fa-step-backward read" aria-hidden="true"></i>',
        'm_name' => 'GO BACK',
        'm_desc' => '',
        'm_parents' => array(13289,11035),
    ),
    12211 => array(
        'm_icon' => '<i class="fas fa-step-forward read" aria-hidden="true"></i>',
        'm_name' => 'GO NEXT',
        'm_desc' => '',
        'm_parents' => array(13289,11035),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEATE',
        'm_desc' => '',
        'm_parents' => array(10939,12893,10876,11035,12761,12112,2738),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => '',
        'm_parents' => array(12500,10876,11035),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas read" aria-hidden="true"></i>',
        'm_name' => 'MENCH INTERACTIONS',
        'm_desc' => '',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-user-crown source" aria-hidden="true"></i>',
        'm_name' => 'MENCH PLAYERS',
        'm_desc' => '',
        'm_parents' => array(13202,4536,4983,1278,11035,10573),
    ),
    12205 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(12500,11035),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-lightbulb-on idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(13211,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    12969 => array(
        'm_icon' => '<i class="fas fa-eye read"></i>',
        'm_name' => 'MY READS',
        'm_desc' => '',
        'm_parents' => array(13210,12228,11035,11018,12677,12701,4527,11089),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => '',
        'm_parents' => array(13297,6405,6194,12699,12500,10876,11035,4527),
    ),
    12750 => array(
        'm_icon' => '<i class="fas fa-step-forward read"></i>',
        'm_name' => 'PREVIEW IDEA READ',
        'm_desc' => '',
        'm_parents' => array(13295,11035),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'SAVED',
        'm_desc' => '',
        'm_parents' => array(13289,4485,10876,12701,12321,7551,11089,11018,11035,4755,4593,12893),
    ),
    7256 => array(
        'm_icon' => '<i class="fas fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCH MENCH',
        'm_desc' => '',
        'm_parents' => array(12701,12497,11035,3323),
    ),
    13023 => array(
        'm_icon' => '<i class="fas fa-share"></i>',
        'm_name' => 'SHARE',
        'm_desc' => '',
        'm_parents' => array(13304,13291,12896,11035,4527,13024,7305),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in-alt" aria-hidden="true"></i>',
        'm_name' => 'SIGN IN/UP',
        'm_desc' => '',
        'm_parents' => array(10876,4527,11035),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(10939,12489,11035,10876,12893,12761,4527,2738),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-list source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LIST EDITOR',
        'm_desc' => '',
        'm_parents' => array(11035,12703,12590,11029,4527),
    ),
    12275 => array(
        'm_icon' => '<i class="fas fa-pen-square source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE MODIFY',
        'm_desc' => '',
        'm_parents' => array(13297,11035),
    ),
    13007 => array(
        'm_icon' => '<i class="fad fa-sort-alpha-down source"></i>',
        'm_name' => 'SOURCE SORT RESET TO ALPHABETICAL',
        'm_desc' => '',
        'm_parents' => array(11035,4593),
    ),
    10957 => array(
        'm_icon' => '<i class="fas fa-bolt" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(6225,11035,5007,4527),
    ),
    7540 => array(
        'm_icon' => '<i class="fad fa-university" aria-hidden="true"></i>',
        'm_name' => 'TERMS OF SERVICE',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
);

//IDEA TABS:
$config['sources_id_11018'] = array(11020,4601,12419,4983,7545,12969,6255,12896,6146,10573,12589,11047);
$config['sources__11018'] = array(
    11020 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'NEXT IDEAS',
        'm_desc' => 'Define reading flow',
        'm_parents' => array(12675,12413,11018),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags idea" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => 'Improve idea search',
        'm_parents' => array(10984,11018,12322,10593,4593,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => 'Contributor-only chats',
        'm_parents' => array(13304,13291,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => 'People & Content referencing this idea',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => 'Profile(s) enhancements once idea is read',
        'm_parents' => array(13298,13291,12273,12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    12969 => array(
        'm_icon' => '<i class="fas fa-eye read"></i>',
        'm_name' => 'MY READS',
        'm_desc' => '',
        'm_parents' => array(13210,12228,11035,11018,12677,12701,4527,11089),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(6205,12500,12677,10939,11018,12467,11089,12228,4527),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'SAVED',
        'm_desc' => '',
        'm_parents' => array(13289,4485,10876,12701,12321,7551,11089,11018,11035,4755,4593,12893),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'UNFINISHED',
        'm_desc' => 'Players who failed to complete read',
        'm_parents' => array(12701,12677,11018,12446,11089,12228,4527),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-lightbulb-on idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => 'Active contributors',
        'm_parents' => array(13211,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    12589 => array(
        'm_icon' => '<i class="fad fa-edit idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR',
        'm_desc' => 'Mass modify next ideas',
        'm_parents' => array(12702,11018,4527,12590),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA MENU',
        'm_desc' => '',
        'm_parents' => array(12700,11018,4527,11040),
    ),
);

//IDEA PREVIOUS SECTION:
$config['sources_id_10990'] = array(4737,4736,11019);
$config['sources__10990'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13295,11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TITLE',
        'm_desc' => '',
        'm_parents' => array(13294,12994,6404,10990,12112,10644,6232,6201),
    ),
    11019 => array(
        'm_icon' => '<i class="fas fa-step-backward idea" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS IDEAS',
        'm_desc' => '',
        'm_parents' => array(13294,12413,10990),
    ),
);

//SUPERPOWERS:
$config['sources_id_10957'] = array(12700,12702,10986,10939,12673,10984,12701,12705,10967,12703,12699,12706);
$config['sources__10957'] = array(
    12700 => array(
        'm_icon' => '<i class="fad fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA CHAIN',
        'm_desc' => 'Advance Idea Linking',
        'm_parents' => array(10957),
    ),
    12702 => array(
        'm_icon' => '<i class="fad fa-list idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDIT',
        'm_desc' => 'Mass Edit Ideas',
        'm_parents' => array(10957),
    ),
    10986 => array(
        'm_icon' => '<i class="fad fa-scrubber idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCING',
        'm_desc' => 'Advance Source Tools',
        'm_parents' => array(10957),
    ),
    10939 => array(
        'm_icon' => '<i class="fad fa-pen idea" aria-hidden="true"></i>',
        'm_name' => 'IDEATION',
        'm_desc' => 'Basic Publishing Powers',
        'm_parents' => array(10957),
    ),
    12673 => array(
        'm_icon' => '<i class="fad fa-rectangle-wide idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TOOLBAR',
        'm_desc' => 'Edit next & previous ideas on the fly',
        'm_parents' => array(10957),
    ),
    10984 => array(
        'm_icon' => '<i class="fas fa-walkie-talkie idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA WALKIE TALKIE',
        'm_desc' => 'Collaborative Publishing Powers',
        'm_parents' => array(10957),
    ),
    12701 => array(
        'm_icon' => '<i class="fad fa-glasses read" aria-hidden="true"></i>',
        'm_name' => 'READ GLASSES',
        'm_desc' => 'Read info from all players',
        'm_parents' => array(10957),
    ),
    12705 => array(
        'm_icon' => '<i class="fad fa-list read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST EDIT',
        'm_desc' => 'Mass Edit Reads',
        'm_parents' => array(10957),
    ),
    10967 => array(
        'm_icon' => '<i class="fad fa-edit source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE EDITOR',
        'm_desc' => 'Organize Sources',
        'm_parents' => array(10957),
    ),
    12703 => array(
        'm_icon' => '<i class="fad fa-list source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LIST EDIT',
        'm_desc' => 'Mass Source Edit',
        'm_parents' => array(10957),
    ),
    12699 => array(
        'm_icon' => '<i class="fad fa-plug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PLUG',
        'm_desc' => 'Access Mench Plugins',
        'm_parents' => array(10957),
    ),
    12706 => array(
        'm_icon' => '<i class="fad fa-rectangle-wide source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TOOLBAR',
        'm_desc' => 'List Parent Sources',
        'm_parents' => array(10957),
    ),
);

//AVATARS BASIC:
$config['sources_id_10956'] = array(12286,12287,12288,12234,12233,10965,12236,12235,10979,12295,12294,12293,12300,12301,12299,12237,12238,10978,12314,12315,12316,12240,12239,10963,12241,12242,12207,12244,12243,10966,12245,12246,10976,12248,12247,10962,12249,12250,10975,12252,12251,10982,12253,12254,10970,12256,12255,10972,12257,12258,10969,12260,12259,10960,12439,12262,10981,12264,12263,10968,12265,12266,10974,12268,12267,12206,12269,12270,10958,12272,12271,12231);
$config['sources__10956'] = array(
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
$config['sources_id_2738'] = array(4536,4535,6205);
$config['sources__2738'] = array(
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(10939,12489,11035,10876,12893,12761,4527,2738),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEATE',
        'm_desc' => '',
        'm_parents' => array(10939,12893,10876,11035,12761,12112,2738),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ',
        'm_desc' => '',
        'm_parents' => array(12893,10876,2738),
    ),
);

//THE FIVE LINKS:
$config['sources_id_10692'] = array(4366,4429,4368,4369,4371);
$config['sources__10692'] = array(
    4366 => array(
        'm_icon' => '<i class="fas fa-triangle" aria-hidden="true"></i>',
        'm_name' => 'UP',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-triangle rotate180" aria-hidden="true"></i>',
        'm_name' => 'DOWN',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-triangle rotate270" aria-hidden="true"></i>',
        'm_name' => 'LEFT',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-triangle rotate90" aria-hidden="true"></i>',
        'm_name' => 'RIGHT',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link" aria-hidden="true"></i>',
        'm_name' => 'REFERENCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
);

//MENCH MEMORY:
$config['sources_id_4527'] = array(6225,10956,12279,12588,6150,13037,3000,12864,12359,10876,4229,12842,4486,12840,12589,11047,4485,12012,6193,10990,12273,4983,4737,7356,12138,7355,12400,11018,12675,12677,12420,13202,12413,7585,13022,12330,7309,7712,12883,12884,12955,7751,2738,12467,6404,12079,6201,4341,12893,4527,11054,12687,11035,6206,12112,6232,10573,12969,12500,12741,6287,12577,4755,11081,7704,5967,12229,12326,12446,6103,12227,6255,7304,6186,7360,7364,7359,13291,13300,13298,13304,4593,10593,12141,12327,12761,13023,4269,6204,4536,13207,12571,13004,12574,11080,12822,4592,12403,11059,4537,12524,4997,12887,4986,7551,6194,12274,6177,7358,12575,7357,12401,11089,10957,12968,12523,12322,12321,10692,10869,6146);
$config['sources__4527'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => '',
        'm_parents' => array(12574,11089,12205,11035,4527),
    ),
    10956 => array(
        'm_icon' => '<i class="fad fa-paw-alt source" aria-hidden="true"></i>',
        'm_name' => 'AVATARS BASIC',
        'm_desc' => '',
        'm_parents' => array(12289,4527),
    ),
    12279 => array(
        'm_icon' => '<i class="fad fa-paw-claws source" aria-hidden="true"></i>',
        'm_name' => 'AVATARS SUPER',
        'm_desc' => '',
        'm_parents' => array(12289,4527),
    ),
    12588 => array(
        'm_icon' => '<i class="fad fa-text"></i>',
        'm_name' => 'AVOID PREFIX REMOVAL',
        'm_desc' => '',
        'm_parents' => array(7254,4527),
    ),
    6150 => array(
        'm_icon' => '<i class="far fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARK REMOVED',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    13037 => array(
        'm_icon' => '<i class="fas fa-hand-holding-heart"></i>',
        'm_name' => 'DONATE',
        'm_desc' => '',
        'm_parents' => array(4527,6204,6225),
    ),
    3000 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT CONTENT',
        'm_desc' => '',
        'm_parents' => array(13207,11054,4600,4527),
    ),
    12864 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT SOURCES',
        'm_desc' => '',
        'm_parents' => array(13298,4983,13291,13207,4600,4527,4758),
    ),
    12359 => array(
        'm_icon' => '<i class="fad fa-file-check idea"></i>',
        'm_name' => 'FILE UPLOADING ALLOWED',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    10876 => array(
        'm_icon' => '<i class="fas fa-home read" aria-hidden="true"></i>',
        'm_name' => 'HOME',
        'm_desc' => '',
        'm_parents' => array(12893,4527,1326),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(12842,4527,6410,6283,4593,4486),
    ),
    12842 => array(
        'm_icon' => '<i class="fas fa-long-arrow-down idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK ONE-WAY',
        'm_desc' => '',
        'm_parents' => array(4527,12841),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => '',
        'm_parents' => array(6232,12700,11054,12079,10662,4527),
    ),
    12840 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK TWO-WAYS',
        'm_desc' => '',
        'm_parents' => array(4527,12841),
    ),
    12589 => array(
        'm_icon' => '<i class="fad fa-edit idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR',
        'm_desc' => '',
        'm_parents' => array(12702,11018,4527,12590),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA MENU',
        'm_desc' => '',
        'm_parents' => array(12700,11018,4527,11040),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES',
        'm_desc' => '',
        'm_parents' => array(6404,4535,4527),
    ),
    12012 => array(
        'm_icon' => '<i class="far fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES STATUS',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    6193 => array(
        'm_icon' => '<i class="fad fa-code-branch rotate180 " aria-hidden="true"></i>',
        'm_name' => 'IDEA OR',
        'm_desc' => '',
        'm_parents' => array(10602,4527),
    ),
    10990 => array(
        'm_icon' => '<i class="fad fa-browser idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA PREVIOUS SECTION',
        'm_desc' => '',
        'm_parents' => array(4527),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13291,13298,4535,12500,12571,12467,12321,11089,12228,4527),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13295,11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'IDEA STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    12138 => array(
        'm_icon' => '<i class="fad fa-search idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS FEATURED',
        'm_desc' => '',
        'm_parents' => array(6287,4527,10891),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    12400 => array(
        'm_icon' => '<i class="fad fa-sync idea"></i>',
        'm_name' => 'IDEA SYNC STATUS',
        'm_desc' => '',
        'm_parents' => array(10891,12732,4527),
    ),
    11018 => array(
        'm_icon' => '<i class="fas fa-bars idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TABS',
        'm_desc' => '',
        'm_parents' => array(12676,13294,4527),
    ),
    12675 => array(
        'm_icon' => '<i class="fad fa-badge-check idea"></i>',
        'm_name' => 'IDEA TABS DEFAULT SELECTED',
        'm_desc' => '',
        'm_parents' => array(4527,12676),
    ),
    12677 => array(
        'm_icon' => '<i class="fad fa-eye-slash idea"></i>',
        'm_name' => 'IDEA TABS HIDE IF ZERO',
        'm_desc' => '',
        'm_parents' => array(4527,12676),
    ),
    12420 => array(
        'm_icon' => '<i class="far fa-user-circle" aria-hidden="true"></i>',
        'm_name' => 'IDEA TEXT INPUT SHOW ICON',
        'm_desc' => '',
        'm_parents' => array(7254,4527),
    ),
    13202 => array(
        'm_icon' => '<i class="fas fa-user-edit idea" aria-hidden="true"></i>',
        'm_name' => 'IDEATORS',
        'm_desc' => '',
        'm_parents' => array(4527,13207),
    ),
    12413 => array(
        'm_icon' => '<i class="fas fa-tree idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TREE',
        'm_desc' => '',
        'm_parents' => array(4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(13295,11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    13022 => array(
        'm_icon' => '<i class="fas fa-sitemap idea"></i>',
        'm_name' => 'IDEA TYPE ALL NEXT',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    12330 => array(
        'm_icon' => '<i class="fas fa-bolt idea"></i>',
        'm_name' => 'IDEA TYPE COMPLETE IF EMPTY',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    7309 => array(
        'm_icon' => '<i class="far fa-cubes idea"></i>',
        'm_name' => 'IDEA TYPE MEET REQUIREMENT',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    7712 => array(
        'm_icon' => '<i class="far fa-question-circle idea"></i>',
        'm_name' => 'IDEA TYPE SELECT NEXT',
        'm_desc' => '',
        'm_parents' => array(13028,6287,10893,4527),
    ),
    12883 => array(
        'm_icon' => '<i class="fas fa-check idea"></i>',
        'm_name' => 'IDEA TYPE SELECT ONE',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    12884 => array(
        'm_icon' => '<i class="fas fa-check-double idea"></i>',
        'm_name' => 'IDEA TYPE SELECT SOME',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    12955 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea"></i>',
        'm_name' => 'IDEA TYPE TAKES COMPLETION TIME',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    7751 => array(
        'm_icon' => '<i class="far fa-upload idea"></i>',
        'm_name' => 'IDEA TYPE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    2738 => array(
        'm_icon' => '<img src="https://mench.com/img/mench.png" class="mench-spin no-radius">',
        'm_name' => 'MENCH',
        'm_desc' => '',
        'm_parents' => array(7305,12891,12497,2,11054,12041,4527,1,7312),
    ),
    12467 => array(
        'm_icon' => '<i class="fas fa-circle" aria-hidden="true"></i>',
        'm_name' => 'MENCH COINS',
        'm_desc' => '',
        'm_parents' => array(7305,4527),
    ),
    6404 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'MENCH CONFIG VARIABLES',
        'm_desc' => '',
        'm_parents' => array(11054,4527,6403),
    ),
    12079 => array(
        'm_icon' => '<i class="fas fa-caret-down" aria-hidden="true"></i>',
        'm_name' => 'MENCH DROPDOWN MENUS',
        'm_desc' => '',
        'm_parents' => array(12829,6403,4527),
    ),
    6201 => array(
        'm_icon' => '<i class="fas fa-table idea" aria-hidden="true"></i>',
        'm_name' => 'MENCH IDEAS',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7735,4535),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas read" aria-hidden="true"></i>',
        'm_name' => 'MENCH INTERACTIONS',
        'm_desc' => '',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735),
    ),
    12893 => array(
        'm_icon' => '<i class="fas fa-ellipsis-h"></i>',
        'm_name' => 'MENCH MAIN MENU',
        'm_desc' => '',
        'm_parents' => array(6403,4527),
    ),
    4527 => array(
        'm_icon' => '<i class="fas fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY',
        'm_desc' => '',
        'm_parents' => array(4755,6403,12741,6287,4527),
    ),
    11054 => array(
        'm_icon' => '<i class="fal fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY JAVASCRIPT',
        'm_desc' => '',
        'm_parents' => array(4755,6403,4527),
    ),
    12687 => array(
        'm_icon' => '<i class="fad fa-comments-alt" aria-hidden="true"></i>',
        'm_name' => 'MENCH MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6403,11054,4527),
    ),
    11035 => array(
        'm_icon' => '<i class="fad fa-compass" aria-hidden="true"></i>',
        'm_name' => 'MENCH NAVIGATION',
        'm_desc' => '',
        'm_parents' => array(4527,7305),
    ),
    6206 => array(
        'm_icon' => '<i class="fas fa-table source" aria-hidden="true"></i>',
        'm_name' => 'MENCH SOURCES',
        'm_desc' => '',
        'm_parents' => array(4527,7735,4536),
    ),
    12112 => array(
        'm_icon' => '<i class="fas fa-text" aria-hidden="true"></i>',
        'm_name' => 'MENCH TEXT INPUTS',
        'm_desc' => '',
        'm_parents' => array(12829,6403,4527),
    ),
    6232 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'MENCH VARIABLE',
        'm_desc' => '',
        'm_parents' => array(6403,4755,4527,6212),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-lightbulb-on idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(13211,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    12969 => array(
        'm_icon' => '<i class="fas fa-eye read"></i>',
        'm_name' => 'MY READS',
        'm_desc' => '',
        'm_parents' => array(13210,12228,11035,11018,12677,12701,4527,11089),
    ),
    12500 => array(
        'm_icon' => '<i class="fas fa-user" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MENU',
        'm_desc' => '',
        'm_parents' => array(7524,12079,12497,12823,4527),
    ),
    12741 => array(
        'm_icon' => '<i class="fas fa-code"></i>',
        'm_name' => 'PLUGIN RETURN CODE ONLY',
        'm_desc' => '',
        'm_parents' => array(12999,4527),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => '',
        'm_parents' => array(13297,6405,6194,12699,12500,10876,11035,4527),
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
        'm_parents' => array(12701,4755,6771,4527),
    ),
    11081 => array(
        'm_icon' => '<i class="far fa-bezier-curve read"></i>',
        'm_name' => 'READ ALL CONNECTIONS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    7704 => array(
        'm_icon' => '<i class="far fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER',
        'm_desc' => '',
        'm_parents' => array(13028,12228,4527),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ CARBON COPY',
        'm_desc' => '',
        'm_parents' => array(4527,7569,4755,4593),
    ),
    12229 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ COMPLETION',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    12326 => array(
        'm_icon' => '<i class="fad fa-expand read" aria-hidden="true"></i>',
        'm_name' => 'READ EXPANSIONS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    12446 => array(
        'm_icon' => '<i class="fad fa-question-circle read"></i>',
        'm_name' => 'READ ICONS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    6103 => array(
        'm_icon' => '<i class="fas fa-lambda"></i>',
        'm_name' => 'READ METADATA',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    12227 => array(
        'm_icon' => '<i class="fas fa-walking read" aria-hidden="true"></i>',
        'm_name' => 'READ PROGRESS',
        'm_desc' => '',
        'm_parents' => array(12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(6205,12500,12677,10939,11018,12467,11089,12228,4527),
    ),
    7304 => array(
        'm_icon' => '<i class="far fa-chart-bar read"></i>',
        'm_name' => 'READ STATS',
        'm_desc' => '',
        'm_parents' => array(6771,10888,4527),
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
    13291 => array(
        'm_icon' => '<i class="fas fa-bars read"></i>',
        'm_name' => 'READ TABS',
        'm_desc' => '',
        'm_parents' => array(13299,4527,12994),
    ),
    13300 => array(
        'm_icon' => '<i class="fas fa-badge-check read"></i>',
        'm_name' => 'READ TABS DEFAULT SELECTED',
        'm_desc' => '',
        'm_parents' => array(4527,13299),
    ),
    13298 => array(
        'm_icon' => '<i class="fad fa-eye-slash read"></i>',
        'm_name' => 'READ TABS HIDE IF ZERO',
        'm_desc' => '',
        'm_parents' => array(4527,13299),
    ),
    13304 => array(
        'm_icon' => '<i class="fas fa-user-check read"></i>',
        'm_name' => 'READ TABS SHOW IF LOGGED-IN',
        'm_desc' => '',
        'm_parents' => array(4527,13299),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
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
    12141 => array(
        'm_icon' => '<i class="fad fa-coin" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE COIN AWARD',
        'm_desc' => '',
        'm_parents' => array(12144,4527),
    ),
    12327 => array(
        'm_icon' => '<i class="fas fa-lock-open read"></i>',
        'm_name' => 'READ UNLOCKS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    12761 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'SEARCH INDEX',
        'm_desc' => '',
        'm_parents' => array(4527,3323),
    ),
    13023 => array(
        'm_icon' => '<i class="fas fa-share"></i>',
        'm_name' => 'SHARE',
        'm_desc' => '',
        'm_parents' => array(13304,13291,12896,11035,4527,13024,7305),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in-alt" aria-hidden="true"></i>',
        'm_name' => 'SIGN IN/UP',
        'm_desc' => '',
        'm_parents' => array(10876,4527,11035),
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
        'm_parents' => array(10939,12489,11035,10876,12893,12761,4527,2738),
    ),
    13207 => array(
        'm_icon' => '<i class="fas fa-home source"></i>',
        'm_name' => 'SOURCE HOME',
        'm_desc' => '',
        'm_parents' => array(4527,4536),
    ),
    12571 => array(
        'm_icon' => '<i class="fas fa-expand" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LAYOUT OPEN BY DEFAULT',
        'm_desc' => '',
        'm_parents' => array(12573,4527),
    ),
    13004 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'SOURCE LAYOUT RESTRICT COUNTS',
        'm_desc' => '',
        'm_parents' => array(10967,4527,12573),
    ),
    12574 => array(
        'm_icon' => '<i class="fad fa-check-double"></i>',
        'm_name' => 'SOURCE LAYOUT SHOW EVEN IF ZERO',
        'm_desc' => '',
        'm_parents' => array(4527,12573),
    ),
    11080 => array(
        'm_icon' => '<i class="far fa-file source"></i>',
        'm_name' => 'SOURCE LINK FILE EXTENSIONS',
        'm_desc' => '',
        'm_parents' => array(12821,4527),
    ),
    12822 => array(
        'm_icon' => '<i class="fad fa-eye source"></i>',
        'm_name' => 'SOURCE LINK MESSAGE DISPLAY',
        'm_desc' => '',
        'm_parents' => array(4527,12821),
    ),
    4592 => array(
        'm_icon' => '<i class="fas fa-link source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINKS',
        'm_desc' => '',
        'm_parents' => array(11054,5982,4527),
    ),
    12403 => array(
        'm_icon' => '<i class="far fa-object-ungroup source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK TYPE CUSTOM UI',
        'm_desc' => '',
        'm_parents' => array(12821,4527),
    ),
    11059 => array(
        'm_icon' => '<i class="fas fa-file-plus source"></i>',
        'm_name' => 'SOURCE LINK UPLOAD FILE',
        'm_desc' => '',
        'm_parents' => array(12821,6196,4527),
    ),
    4537 => array(
        'm_icon' => '<i class="fad fa-spider-web source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK URLS',
        'm_desc' => '',
        'm_parents' => array(12821,4527),
    ),
    12524 => array(
        'm_icon' => '<i class="fad fa-film-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK VISUAL',
        'm_desc' => '',
        'm_parents' => array(12821,4527),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-list source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LIST EDITOR',
        'm_desc' => '',
        'm_parents' => array(11035,12703,12590,11029,4527),
    ),
    12887 => array(
        'm_icon' => '<i class="fas fa-caret-down source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE MENU',
        'm_desc' => '',
        'm_parents' => array(13297,12703,4527,11040),
    ),
    4986 => array(
        'm_icon' => '<i class="fal fa-at" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REFERENCE ALLOWED',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    7551 => array(
        'm_icon' => '<i class="fas fa-at source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REFERENCE ONLY',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    6194 => array(
        'm_icon' => '<i class="fas fa-link source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REFERENCES',
        'm_desc' => '',
        'm_parents' => array(13297,12701,4758,4527),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(10939,11089,4536,12500,12467,12228,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    7358 => array(
        'm_icon' => '<i class="far fa-check-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(12572,4527),
    ),
    12575 => array(
        'm_icon' => '<i class="fas fa-star source"></i>',
        'm_name' => 'SOURCE STATUS FEATURED',
        'm_desc' => '',
        'm_parents' => array(4527,12572),
    ),
    7357 => array(
        'm_icon' => '<i class="far fa-globe source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(12572,11054,4527),
    ),
    12401 => array(
        'm_icon' => '<i class="fad fa-sync source"></i>',
        'm_name' => 'SOURCE STATUS SYNC',
        'm_desc' => '',
        'm_parents' => array(12572,12732,4527),
    ),
    11089 => array(
        'm_icon' => '<i class="fas fa-bars source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TABS',
        'm_desc' => '',
        'm_parents' => array(13296,4527),
    ),
    10957 => array(
        'm_icon' => '<i class="fas fa-bolt" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(6225,11035,5007,4527),
    ),
    12968 => array(
        'm_icon' => '<i class="fas fa-sync source fa-spin" aria-hidden="true"></i>',
        'm_name' => 'SYNC ICONS IF DIFFERENT',
        'm_desc' => '',
        'm_parents' => array(6204,4527,12967),
    ),
    12523 => array(
        'm_icon' => '<i class="fad fa-sync source fa-spin" aria-hidden="true"></i>',
        'm_name' => 'SYNC ICONS IF NEW',
        'm_desc' => '',
        'm_parents' => array(12967,4527),
    ),
    12322 => array(
        'm_icon' => '<i class="fas fa-comment read" aria-hidden="true"></i>',
        'm_name' => 'TEMPLATE IDEA MESSAGES',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    12321 => array(
        'm_icon' => '<i class="fad fa-object-group read" aria-hidden="true"></i>',
        'm_name' => 'TEMPLATE IDEA READ',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    10692 => array(
        'm_icon' => '<i class="fas fa-bezier-curve"></i>',
        'm_name' => 'THE FIVE LINKS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    10869 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'TOPICS',
        'm_desc' => '',
        'm_parents' => array(12079,6225,6122,7305,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'UNFINISHED',
        'm_desc' => '',
        'm_parents' => array(12701,12677,11018,12446,11089,12228,4527),
    ),
);

//READ TYPE ADD CONTENT:
$config['sources_id_10593'] = array(12419,4250,10679,4983,10644,4601,4231,4554,4556,4555,6563,4570,7702,4549,4551,4550,4548,4552,4553,4251,4259,10657,4261,4260,4255,4258,10646);
$config['sources__10593'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(13304,13291,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,12149,10593,4593),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4593,10593),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    10644 => array(
        'm_icon' => '<i class="fad fa-bullseye-arrow idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE TITLE',
        'm_desc' => '',
        'm_parents' => array(10593,4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags idea" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12322,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,12322,10593,4986,4603,4593,4485),
    ),
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="read fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED IDEA',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,7569),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4552 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ADDED',
        'm_desc' => '',
        'm_parents' => array(12274,12401,12149,12141,10593,4593),
    ),
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'SOURCE LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,4593,4592,4537),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'SOURCE LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(12822,10593,4593,4592),
    ),
    4258 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    10646 => array(
        'm_icon' => '<i class="fad fa-fingerprint source"></i>',
        'm_name' => 'SOURCE NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593),
    ),
);

//IDEA SOURCES:
$config['sources_id_4983'] = array(2997,4446,3005,3147,4763,3084,12864,3192,2998,4430);
$config['sources__4983'] = array(
    2997 => array(
        'm_icon' => '<i class="fas fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT ARTICLE',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fas fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT ASSESSMENT',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fas fa-book source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT BOOK',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fas fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT COURSE',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fas fa-megaphone source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT MARKETING CHANNEL',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    3084 => array(
        'm_icon' => '<i class="fad fa-user-astronaut source"></i>',
        'm_name' => 'EXPERT PERSON',
        'm_desc' => '',
        'm_parents' => array(12968,12864,4983,11035,1278),
    ),
    12864 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT SOURCES',
        'm_desc' => '',
        'm_parents' => array(13298,4983,13291,13207,4600,4527,4758),
    ),
    3192 => array(
        'm_icon' => '<i class="fas fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT TOOL',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fas fa-play-circle source"></i>',
        'm_name' => 'EXPERT VIDEO',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-user-crown source" aria-hidden="true"></i>',
        'm_name' => 'MENCH PLAYERS',
        'm_desc' => '',
        'm_parents' => array(13202,4536,4983,1278,11035,10573),
    ),
);

//IDEA TYPE UPLOAD:
$config['sources_id_7751'] = array(7637);
$config['sources__7751'] = array(
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPLOAD & NEXT',
        'm_desc' => '',
        'm_parents' => array(13022,12955,12117,7751,7585,6192),
    ),
);

//READ METADATA:
$config['sources_id_6103'] = array(6402,4358);
$config['sources__6103'] = array(
    6402 => array(
        'm_icon' => '<i class="fas fa-temperature-high idea" aria-hidden="true"></i>',
        'm_name' => 'CONDITION SCORE RANGE',
        'm_desc' => '',
        'm_parents' => array(12700,10664,6103,6410),
    ),
    4358 => array(
        'm_icon' => '<i class="fas fa-comment-alt-check idea" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => '',
        'm_parents' => array(12700,12112,10663,6103,6410,6232),
    ),
);

//MENCH INTERACTIONS:
$config['sources_id_4341'] = array(4367,6186,4362,4593,4364,4372,4366,4429,4368,4369,4371,4370,6103);
$config['sources__4341'] = array(
    4367 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'READ ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="fas fa-clock" aria-hidden="true"></i>',
        'm_name' => 'READ TIME',
        'm_desc' => '',
        'm_parents' => array(6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
    4364 => array(
        'm_icon' => '<i class="fas fa-user" aria-hidden="true"></i>',
        'm_name' => 'READ SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    4372 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'READ MESSAGE',
        'm_desc' => '',
        'm_parents' => array(7578,10679,10657,5001,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-triangle" aria-hidden="true"></i>',
        'm_name' => 'READ UP',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-triangle rotate180" aria-hidden="true"></i>',
        'm_name' => 'READ DOWN',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-triangle rotate270" aria-hidden="true"></i>',
        'm_name' => 'READ LEFT',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-triangle rotate90" aria-hidden="true"></i>',
        'm_name' => 'READ RIGHT',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link" aria-hidden="true"></i>',
        'm_name' => 'READ REFERENCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fas fa-bars" aria-hidden="true"></i>',
        'm_name' => 'READ SORT',
        'm_desc' => '',
        'm_parents' => array(13007,13006,10676,10675,6232,4341),
    ),
    6103 => array(
        'm_icon' => '<i class="fas fa-lambda"></i>',
        'm_name' => 'READ METADATA',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
);

//MENCH SOURCES:
$config['sources_id_6206'] = array(6160,6177,6197,6198,13030,6172);
$config['sources__6206'] = array(
    6160 => array(
        'm_icon' => '<i class="fas fa-at source" aria-hidden="true"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6206),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'TITLE',
        'm_desc' => '',
        'm_parents' => array(13296,13025,6404,12112,12232,10646,5000,4998,4999,6232,6206),
    ),
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'ICON',
        'm_desc' => '',
        'm_parents' => array(12605,10653,5943,10625,6232,6206),
    ),
    13030 => array(
        'm_icon' => '<i class="fas fa-weight source"></i>',
        'm_name' => 'WEIGHT',
        'm_desc' => '',
        'm_parents' => array(6214,6232,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda source"></i>',
        'm_name' => 'METADATA',
        'm_desc' => '',
        'm_parents' => array(6232,6206,6195),
    ),
);

//MENCH IDEAS:
$config['sources_id_6201'] = array(6202,4737,7585,4736,4356,13029,6159);
$config['sources__6201'] = array(
    6202 => array(
        'm_icon' => '<i class="fas fa-hashtag idea" aria-hidden="true"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6201),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(13295,11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(13295,11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'TITLE',
        'm_desc' => '',
        'm_parents' => array(13294,12994,6404,10990,12112,10644,6232,6201),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'DURATION IN SECONDS',
        'm_desc' => '',
        'm_parents' => array(13295,6287,12741,11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    13029 => array(
        'm_icon' => '<i class="fas fa-weight idea"></i>',
        'm_name' => 'WEIGHT',
        'm_desc' => '',
        'm_parents' => array(6214,6232,6201),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'METADATA',
        'm_desc' => '',
        'm_parents' => array(11049,6232,6201,6195),
    ),
);

//SINGLE SELECTABLE:
$config['sources_id_6204'] = array(13037,4737,7585,10602,13158,13172,13167,13166,13153,13174,13171,13152,13162,13156,13157,13155,13173,13170,13164,13160,13168,13165,13169,13159,13163,13161,13154,6186,4593,3290,6177,12968);
$config['sources__6204'] = array(
    13037 => array(
        'm_icon' => '<i class="fas fa-hand-holding-heart"></i>',
        'm_name' => 'DONATE',
        'm_desc' => '',
        'm_parents' => array(4527,6204,6225),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13295,11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(13295,11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece idea"></i>',
        'm_name' => 'IDEA TYPE GROUPS',
        'm_desc' => '',
        'm_parents' => array(10893,6204),
    ),
    13158 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE ANGULAR',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13172 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE AWS',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13167 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE C#',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13166 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE C++',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13153 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE CSS',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13174 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE DOCKER',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13171 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE GIT',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13152 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE HTML',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13162 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE JAVA',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13156 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE JAVASCRIPT',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13157 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE JQUERY',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13155 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE JSON',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13173 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE LINUX',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13170 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE MONGODB',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13164 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE MYSQL',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13160 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE NODE.JS',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13168 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE PHP',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13165 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE POSTGRESQL',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13169 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE PYTHON',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13159 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE REACT',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13163 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE SQL',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13161 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE TYPESCRIPT',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    13154 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'RATE VUE.JS',
        'm_desc' => '',
        'm_parents' => array(6204,13151),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE GENDER',
        'm_desc' => '',
        'm_parents' => array(6204),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    12968 => array(
        'm_icon' => '<i class="fas fa-sync source fa-spin" aria-hidden="true"></i>',
        'm_name' => 'SYNC ICONS IF DIFFERENT',
        'm_desc' => '',
        'm_parents' => array(6204,4527,12967),
    ),
);

//IDEA TYPE SELECT NEXT:
$config['sources_id_7712'] = array(6684,7231);
$config['sources__7712'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle idea" aria-hidden="true"></i>',
        'm_name' => 'ONE',
        'm_desc' => '',
        'm_parents' => array(12955,12883,12336,12129,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square idea" aria-hidden="true"></i>',
        'm_name' => 'SOME',
        'm_desc' => '',
        'm_parents' => array(12955,12884,12334,12129,7712,7489,7585,6193),
    ),
);

//READ ANSWER:
$config['sources_id_7704'] = array(12336,12334,6157,7489);
$config['sources__7704'] = array(
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
);

//IDEA LINK CONDITIONAL:
$config['sources_id_4229'] = array(10664,6140,6997);
$config['sources__4229'] = array(
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4593,4229),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => 'A step that has become available because of the score generated from student answers',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//IDEA OR:
$config['sources_id_6193'] = array(6684,7231,6907);
$config['sources__6193'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT ONE',
        'm_desc' => '',
        'm_parents' => array(12955,12883,12336,12129,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT SOME',
        'm_desc' => '',
        'm_parents' => array(12955,12884,12334,12129,7712,7489,7585,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ANY',
        'm_desc' => '',
        'm_parents' => array(12330,12883,12700,7486,7485,6140,7585,7309,6997,6193),
    ),
);

//IDEA TYPE:
$config['sources_id_7585'] = array(6677,6683,7637,6914,6907,6684,7231);
$config['sources__7585'] = array(
    6677 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'READ & NEXT',
        'm_desc' => 'Read messages & go next',
        'm_parents' => array(13022,12330,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard idea" aria-hidden="true"></i>',
        'm_name' => 'REPLY & NEXT',
        'm_desc' => 'Reply with text & go next',
        'm_parents' => array(13022,12955,6144,7585,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip idea" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD & NEXT',
        'm_desc' => 'Upload a file & go next',
        'm_parents' => array(13022,12955,12117,7751,7585,6192),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ALL',
        'm_desc' => 'Complete by reading all next ideas',
        'm_parents' => array(12330,12700,7486,7485,6140,6192,7585,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ANY',
        'm_desc' => 'Complete by reading one of the next ideas',
        'm_parents' => array(12330,12883,12700,7486,7485,6140,7585,7309,6997,6193),
    ),
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT ONE',
        'm_desc' => 'Select a single next idea',
        'm_parents' => array(12955,12883,12336,12129,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT SOME',
        'm_desc' => 'Select 1 or more next idea(s)',
        'm_parents' => array(12955,12884,12334,12129,7712,7489,7585,6193),
    ),
);

//READ CARBON COPY:
$config['sources_id_5967'] = array(12419,4235,12773,4250,12453,12450,6224,4246,7504);
$config['sources__5967'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '1',
        'm_parents' => array(13304,13291,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-play read" aria-hidden="true"></i>',
        'm_name' => 'GET STARTED',
        'm_desc' => '1',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    12773 => array(
        'm_icon' => '<i class="far fa-plus-circle idea"></i>',
        'm_name' => 'IDEA APPEND CONTENT',
        'm_desc' => '1',
        'm_parents' => array(5967,4755,4593),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA CREATED',
        'm_desc' => '1',
        'm_parents' => array(5967,12400,12149,10593,4593),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone idea"></i>',
        'm_name' => 'IDEA FEATURE REQUEST',
        'm_desc' => '1',
        'm_parents' => array(12137,4755,4593,5967),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCE REQUEST',
        'm_desc' => '1',
        'm_parents' => array(4593,4755,5967),
    ),
    6224 => array(
        'm_icon' => '<i class="fad fa-sync read"></i>',
        'm_name' => 'READ UPDATE ACCOUNT',
        'm_desc' => '1',
        'm_parents' => array(5967,4755,4593),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE BUG REPORTS',
        'm_desc' => '1',
        'm_parents' => array(5967,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PENDING MODERATION',
        'm_desc' => '1',
        'm_parents' => array(5967,4755,4593),
    ),
);

//SOURCE REFERENCE ONLY:
$config['sources_id_7551'] = array(7545,4983,10573,12896);
$config['sources__7551'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(13298,13291,12273,12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-lightbulb-on idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(13211,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'SAVED',
        'm_desc' => '',
        'm_parents' => array(13289,4485,10876,12701,12321,7551,11089,11018,11035,4755,4593,12893),
    ),
);

//IDEA TYPE MEET REQUIREMENT:
$config['sources_id_7309'] = array(6914,6907);
$config['sources__7309'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes idea" aria-hidden="true"></i>',
        'm_name' => 'ALL',
        'm_desc' => '',
        'm_parents' => array(12330,12700,7486,7485,6140,6192,7585,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube idea" aria-hidden="true"></i>',
        'm_name' => 'ANY',
        'm_desc' => '',
        'm_parents' => array(12330,12883,12700,7486,7485,6140,7585,7309,6997,6193),
    ),
);

//PLUGINS:
$config['sources_id_6287'] = array(7274,7264,4356,7261,12731,12734,7260,7263,11049,12733,7259,12138,7275,7276,12735,7712,4527,12114,7277,12710,12709,12729,12739,12722,12888,7267,12732,7268,7269,12730,12738,12712,12737,12736,7278,12967,7279,12569);
$config['sources__6287'] = array(
    7274 => array(
        'm_icon' => '<i class="fas fa-clock mench-spin" aria-hidden="true"></i>',
        'm_name' => 'CRON JOBS',
        'm_desc' => '',
        'm_parents' => array(6404,6287,6403,12999),
    ),
    7264 => array(
        'm_icon' => '<i class="fad fa-sitemap idea"></i>',
        'm_name' => 'IDEA BIRDS EYE MARKS',
        'm_desc' => '',
        'm_parents' => array(11047,6287),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION IN SECONDS',
        'm_desc' => '',
        'm_parents' => array(13295,6287,12741,11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    7261 => array(
        'm_icon' => '',
        'm_name' => 'IDEA LIST DUPLICATES',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12731 => array(
        'm_icon' => '',
        'm_name' => 'IDEA LIST INVALID TITLES',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12734 => array(
        'm_icon' => '',
        'm_name' => 'IDEA LIST NEXT/PREVIOUS CROSSOVERS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7260 => array(
        'm_icon' => '👶',
        'm_name' => 'IDEA LIST ORPHANED',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7263 => array(
        'm_icon' => '',
        'm_name' => 'IDEA MARKS LIST ALL',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    11049 => array(
        'm_icon' => '<i class="fad fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12741,6287,11047),
    ),
    12733 => array(
        'm_icon' => '<i class="fad fa-code read"></i>',
        'm_name' => 'IDEA REVIEW READ',
        'm_desc' => '',
        'm_parents' => array(11047,12741,12701,6287),
    ),
    7259 => array(
        'm_icon' => '',
        'm_name' => 'IDEA SEARCH & REPLACE',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12138 => array(
        'm_icon' => '<i class="fad fa-search idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS FEATURED',
        'm_desc' => '',
        'm_parents' => array(6287,4527,10891),
    ),
    7275 => array(
        'm_icon' => '<i class="fad fa-sync idea mench-spin"></i>',
        'm_name' => 'IDEA SYNC COMMON BASE',
        'm_desc' => '',
        'm_parents' => array(6287,12741,11047,7286,7274),
    ),
    7276 => array(
        'm_icon' => '<i class="fad fa-sync idea mench-spin" aria-hidden="true"></i>',
        'm_name' => 'IDEA SYNC EXTRA INSIGHTS',
        'm_desc' => '',
        'm_parents' => array(6287,12741,11047,7286,7274),
    ),
    12735 => array(
        'm_icon' => '',
        'm_name' => 'IDEA SYNC/FIX SOURCES',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7712 => array(
        'm_icon' => '<i class="far fa-question-circle idea"></i>',
        'm_name' => 'IDEA TYPE SELECT NEXT',
        'm_desc' => '',
        'm_parents' => array(13028,6287,10893,4527),
    ),
    4527 => array(
        'm_icon' => '<i class="fas fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY',
        'm_desc' => '',
        'm_parents' => array(4755,6403,12741,6287,4527),
    ),
    12114 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'MENCH WEEKLY GROWTH REPORT',
        'm_desc' => '',
        'm_parents' => array(6287,12741,12701,7274,7569),
    ),
    7277 => array(
        'm_icon' => '',
        'm_name' => 'METADATA CLEAN VARIABLES',
        'm_desc' => '',
        'm_parents' => array(6287,12741,7287,7274),
    ),
    12710 => array(
        'm_icon' => '👤',
        'm_name' => 'MY SESSION VARIABLES',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12709 => array(
        'm_icon' => 'ℹ️',
        'm_name' => 'PHP INFO',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12729 => array(
        'm_icon' => '',
        'm_name' => 'PLATFORM COIN STATS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12739 => array(
        'm_icon' => '',
        'm_name' => 'READ ANALYZE URLS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12722 => array(
        'm_icon' => '',
        'm_name' => 'READ REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12888 => array(
        'm_icon' => '<i class="fas fa-shield-check source"></i>',
        'm_name' => 'SOURCE EXPLORE EXPERTS',
        'm_desc' => '',
        'm_parents' => array(12741,6287,12887),
    ),
    7267 => array(
        'm_icon' => '🔍',
        'm_name' => 'SOURCE ICON SEARCH',
        'm_desc' => '',
        'm_parents' => array(12887,6287),
    ),
    12732 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE/IDEA SYNC STATUSES',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    7268 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE LIST DUPLICATES',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7269 => array(
        'm_icon' => '👶',
        'm_name' => 'SOURCE LIST ORPHANED',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12730 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE NAME SEARCH & REPLACE',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12738 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE RANDOM AVATARS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12712 => array(
        'm_icon' => '<i class="fad fa-lambda source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12887,12741,6287),
    ),
    12737 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE SYNC & FIX LINK READS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12736 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE SYNC & FIX PLAYERS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7278 => array(
        'm_icon' => '',
        'm_name' => 'SYNC GEPHI INDEX',
        'm_desc' => '',
        'm_parents' => array(6287,12741,7287,7274),
    ),
    12967 => array(
        'm_icon' => '<i class="fad fa-sync source mench-spin" aria-hidden="true"></i>',
        'm_name' => 'SYNC ICONS',
        'm_desc' => '',
        'm_parents' => array(6287,12741,4758,7274),
    ),
    7279 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SYNC SEARCH INDEX',
        'm_desc' => '',
        'm_parents' => array(6287,12741,12887,11047,3323,7287),
    ),
    12569 => array(
        'm_icon' => '<i class="fad fa-weight"></i>',
        'm_name' => 'WEIGHT ALGORITHM',
        'm_desc' => '',
        'm_parents' => array(6287,12741,7274),
    ),
);

//READ STATUS INCOMPLETE:
$config['sources_id_7364'] = array(6175);
$config['sources__7364'] = array(
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner-third fa-spin" aria-hidden="true"></i>',
        'm_name' => 'READ DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
);

//READ STATUS ACTIVE:
$config['sources_id_7360'] = array(6175,12399,6176);
$config['sources__7360'] = array(
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner-third fa-spin" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    12399 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="far fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
);

//READ STATUS PUBLIC:
$config['sources_id_7359'] = array(12399,6176);
$config['sources__7359'] = array(
    12399 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="far fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
);

//SOURCE STATUS ACTIVE:
$config['sources_id_7358'] = array(6180,12563,6181);
$config['sources__7358'] = array(
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin source" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10654,7358,6177),
    ),
    12563 => array(
        'm_icon' => '<i class="fas fa-star source" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="far fa-globe source" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
);

//SOURCE STATUS PUBLIC:
$config['sources_id_7357'] = array(12563,6181);
$config['sources__7357'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star source" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="far fa-globe source" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
);

//IDEA STATUS ACTIVE:
$config['sources_id_7356'] = array(6183,12137,6184);
$config['sources__7356'] = array(
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin idea" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10648,7356,4737),
    ),
    12137 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(10986,10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="far fa-globe idea" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
);

//IDEA STATUS PUBLIC:
$config['sources_id_7355'] = array(12137,6184);
$config['sources__7355'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(10986,10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="far fa-globe idea" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
);

//READ STATS:
$config['sources_id_7304'] = array(6186);
$config['sources__7304'] = array(
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
);

//READ STATUS:
$config['sources_id_6186'] = array(12399,6176,6175,6173);
$config['sources__6186'] = array(
    12399 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="far fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner-third fa-spin" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="fas fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'UNPUBLISH',
        'm_desc' => '',
        'm_parents' => array(12012,10686,10678,10673,6186),
    ),
);

//SOURCE REFERENCES:
$config['sources_id_6194'] = array(4737,7585,6287,4364,6186,4593,6177);
$config['sources__6194'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13295,11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(13295,11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => '',
        'm_parents' => array(13297,6405,6194,12699,12500,10876,11035,4527),
    ),
    4364 => array(
        'm_icon' => '<i class="fas fa-user" aria-hidden="true"></i>',
        'm_name' => 'READ SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'READ TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
);

//READS:
$config['sources_id_6255'] = array(6157,7489,12117,4559,6144,7485,7486,6997);
$config['sources__6255'] = array(
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//BOOKMARK REMOVED:
$config['sources_id_6150'] = array(7757,6155);
$config['sources__6150'] = array(
    7757 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'MANUAL',
        'm_desc' => '',
        'm_parents' => array(10888,6150,4593,4755),
    ),
);

//SOURCE REFERENCE ALLOWED:
$config['sources_id_4986'] = array(12419,4231);
$config['sources__4986'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(13304,13291,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,12322,10593,4986,4603,4593,4485),
    ),
);

//ACCOUNT SETTINGS:
$config['sources_id_6225'] = array(12289,10869,10957,3288,3286,13037);
$config['sources__6225'] = array(
    12289 => array(
        'm_icon' => '<i class="fas fa-paw" aria-hidden="true"></i>',
        'm_name' => 'AVATAR',
        'm_desc' => '',
        'm_parents' => array(6225,12897),
    ),
    10869 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'TOPICS',
        'm_desc' => '',
        'm_parents' => array(12079,6225,6122,7305,4527),
    ),
    10957 => array(
        'm_icon' => '<i class="fas fa-bolt" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(6225,11035,5007,4527),
    ),
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => '',
        'm_parents' => array(6404,13023,4269,12103,6225,4755),
    ),
    3286 => array(
        'm_icon' => '<i class="fas fa-key" aria-hidden="true"></i>',
        'm_name' => 'PASSWORD',
        'm_desc' => '',
        'm_parents' => array(13014,4269,7578,6225,4755),
    ),
    13037 => array(
        'm_icon' => '<i class="fas fa-hand-holding-heart"></i>',
        'm_name' => 'DONATE',
        'm_desc' => '',
        'm_parents' => array(4527,6204,6225),
    ),
);

//IDEA STATUS:
$config['sources_id_4737'] = array(12137,6184,6183,6182);
$config['sources__4737'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => 'Starting point idea, Searchable by all players',
        'm_parents' => array(10986,10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="far fa-globe idea" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => 'Continuation idea, accessible to anyone who has the URL',
        'm_parents' => array(10648,7355,7356,4737),
    ),
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin idea" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => 'Unreadable idea until published live',
        'm_parents' => array(10648,7356,4737),
    ),
    6182 => array(
        'm_icon' => '<i class="fad fa-trash-alt idea" aria-hidden="true"></i>',
        'm_name' => 'DELETED',
        'm_desc' => 'Archived idea',
        'm_parents' => array(12400,4593,4737),
    ),
);

//SOURCE STATUS:
$config['sources_id_6177'] = array(12563,6181,6180,6178);
$config['sources__6177'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star source" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="far fa-globe source" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin source" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10654,7358,6177),
    ),
    6178 => array(
        'm_icon' => '<i class="fad fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'DELETED',
        'm_desc' => '',
        'm_parents' => array(4593,12401,6177),
    ),
);

//UNFINISHED:
$config['sources_id_6146'] = array(6143,7492);
$config['sources__6146'] = array(
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'SKIPPED',
        'm_desc' => 'Completed when students skip an intention and all its child intentions from their Action Plan',
        'm_parents' => array(12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'TERMINATE',
        'm_desc' => 'Logged when users arrive at a locked intent that has no public OR parents or no children, which means there is no way to unlock it.',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
);

//SOURCE LIST EDITOR:
$config['sources_id_4997'] = array(5000,4998,4999,5001,5003,5865,5943,12318,10625,5982,5981,11956,12928,12930);
$config['sources__4997'] = array(
    5000 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME REPLACE',
        'm_desc' => 'Search for occurrence of string in child entity names and if found, updates it with a replacement string',
        'm_parents' => array(12577,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME PREFIX',
        'm_desc' => 'Adds string to the beginning of all child entities. Make sure to include a space for it to look good',
        'm_parents' => array(12577,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME POSTFIX',
        'm_desc' => 'Adds string to the end of all child entities',
        'm_parents' => array(12577,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="source fad fa-sticky-note"></i>',
        'm_name' => 'CONTENT REPLACE',
        'm_desc' => 'Search for occurance of string in child entity link contents and if found, updates it with a replacement string',
        'm_parents' => array(4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="source fad fa-sliders-h"></i>',
        'm_name' => 'STATUS',
        'm_desc' => 'Updates all child entity statuses that match the initial entity status condition',
        'm_parents' => array(4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="source fad fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'LINK STATUS',
        'm_desc' => 'Updates all child entity link statuses that match the initial link status condition',
        'm_parents' => array(4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'ICON SET ALL',
        'm_desc' => 'Updates all child entity icons with string which needs to be a valid icon',
        'm_parents' => array(4593,4997),
    ),
    12318 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'ICON SET IF NONE',
        'm_desc' => 'Updates all icons that are not set to the new value.',
        'm_parents' => array(4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="source fad fa-user-circle"></i>',
        'm_name' => 'ICON REPLACE',
        'm_desc' => 'Search for occurrence of string in child entity icons and if found, updates it with a replacement string',
        'm_parents' => array(4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fad fa-layer-minus source"></i>',
        'm_name' => 'PROFILE- ALL',
        'm_desc' => 'If already added as the parent, this will remove searched entity as the parent of all child entities',
        'm_parents' => array(12577,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE+ ALL',
        'm_desc' => 'If not already done so, will add searched entity as the parent of all child entities',
        'm_parents' => array(12577,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE+ IF @SOURCE',
        'm_desc' => 'Adds a parent entity only IF the entity has another parent entity.',
        'm_parents' => array(12577,4593,4997),
    ),
    12928 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE+ IF 1+ IDEA',
        'm_desc' => 'Adds a profile source if the source has 1 or more ideas',
        'm_parents' => array(4997),
    ),
    12930 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE+ IF NO IDEA',
        'm_desc' => 'Adds a profile source if the source has 0 ideas',
        'm_parents' => array(4997),
    ),
);

//PRIVATE READ:
$config['sources_id_4755'] = array(6415,3288,4235,12773,12453,10681,12450,4527,11054,6232,3286,4783,4755,12336,12334,12197,4554,7757,6155,5967,6559,6560,6556,6578,4556,6149,4283,6969,4275,7610,4555,6132,12360,4266,4267,4282,6563,4570,7702,7495,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,6143,7492,4552,6140,6224,12328,7578,4553,7562,12896,7563,6157,7489,4246,12117,7504,4559,6144,7485,7486,6997,12489,12906);
$config['sources__4755'] = array(
    6415 => array(
        'm_icon' => '<i class="fas fa-trash-alt read" aria-hidden="true"></i>',
        'm_name' => 'CLEAR ALL READS',
        'm_desc' => '',
        'm_parents' => array(12500,4755,4593),
    ),
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => '',
        'm_parents' => array(6404,13023,4269,12103,6225,4755),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-play read" aria-hidden="true"></i>',
        'm_name' => 'GET STARTED',
        'm_desc' => '',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    12773 => array(
        'm_icon' => '<i class="far fa-plus-circle idea"></i>',
        'm_name' => 'IDEA APPEND CONTENT',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone idea"></i>',
        'm_name' => 'IDEA FEATURE REQUEST',
        'm_desc' => '',
        'm_parents' => array(12137,4755,4593,5967),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-bars idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCE REQUEST',
        'm_desc' => '',
        'm_parents' => array(4593,4755,5967),
    ),
    4527 => array(
        'm_icon' => '<i class="fas fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY',
        'm_desc' => '',
        'm_parents' => array(4755,6403,12741,6287,4527),
    ),
    11054 => array(
        'm_icon' => '<i class="fal fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY JAVASCRIPT',
        'm_desc' => '',
        'm_parents' => array(4755,6403,4527),
    ),
    6232 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'MENCH VARIABLE',
        'm_desc' => '',
        'm_parents' => array(6403,4755,4527,6212),
    ),
    3286 => array(
        'm_icon' => '<i class="fas fa-key" aria-hidden="true"></i>',
        'm_name' => 'PASSWORD',
        'm_desc' => '',
        'm_parents' => array(13014,4269,7578,6225,4755),
    ),
    4783 => array(
        'm_icon' => '<i class="far fa-phone source"></i>',
        'm_name' => 'PHONE',
        'm_desc' => '',
        'm_parents' => array(4755),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash" aria-hidden="true"></i>',
        'm_name' => 'PRIVATE READ',
        'm_desc' => '',
        'm_parents' => array(12701,4755,6771,4527),
    ),
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12197 => array(
        'm_icon' => '<i class="fad fa-user-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ APPEND PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7757 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED MANUAL',
        'm_desc' => '',
        'm_parents' => array(10888,6150,4593,4755),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ CARBON COPY',
        'm_desc' => '',
        'm_parents' => array(4527,7569,4755,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA CONSIDERED',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA LISTED',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="read fad fa-megaphone"></i>',
        'm_name' => 'READ IDEA RECOMMENDED',
        'm_desc' => '',
        'm_parents' => array(4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="read fad fa-search"></i>',
        'm_name' => 'READ IDEA SEARCH',
        'm_desc' => '',
        'm_parents' => array(6554,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="fad fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA VIEW',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6132 => array(
        'm_icon' => '<i class="fad fa-bars read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST SORTED',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
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
        'm_parents' => array(4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="read fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED IDEA',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,7569),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone read" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(12969,12227,4755,4593),
    ),
    4577 => array(
        'm_icon' => '<i class="read fad fa-user-plus"></i>',
        'm_name' => 'READ SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="read fad fa-location-circle"></i>',
        'm_name' => 'READ SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ SENT MESSENGER',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="read fad fa-cloud-download"></i>',
        'm_name' => 'READ SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="read fad fa-user-tag"></i>',
        'm_name' => 'READ SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="read fad fa-comment-exclamation"></i>',
        'm_name' => 'READ SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fad fa-sign-in read" aria-hidden="true"></i>',
        'm_name' => 'READ SIGNIN FROM IDEA',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="fad fa-sync read"></i>',
        'm_name' => 'READ UPDATE ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    12328 => array(
        'm_icon' => '<i class="fad fa-sync read"></i>',
        'm_name' => 'READ UPDATE COMPLETION',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6153),
    ),
    7578 => array(
        'm_icon' => '<i class="read fad fa-key"></i>',
        'm_name' => 'READ UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7562 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ WELCOME',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'SAVED',
        'm_desc' => '',
        'm_parents' => array(13289,4485,10876,12701,12321,7551,11089,11018,11035,4755,4593,12893),
    ),
    7563 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'SIGN MAGIC EMAIL',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PENDING MODERATION',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
    12489 => array(
        'm_icon' => '<i class="far fa-medal source"></i>',
        'm_name' => 'SOURCE VIEW',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    12906 => array(
        'm_icon' => '<i class="fal fa-bookmark read"></i>',
        'm_name' => 'UNSAVED',
        'm_desc' => '',
        'm_parents' => array(12896,4755,4593),
    ),
);

//READ TYPE:
$config['sources_id_4593'] = array(7545,6415,12419,4235,12773,4250,6182,12453,4229,4228,10686,10663,10664,12612,12611,12592,12591,6226,10676,10678,10679,10677,10681,10675,12450,4983,10662,10648,10650,10644,10651,4993,4601,4231,10573,5001,10625,5943,12318,5865,4999,4998,5000,5982,5981,11956,5003,12129,12336,12334,12197,4554,7757,6155,5967,6559,6560,6556,6578,10683,4556,6149,4283,6969,4275,7610,4555,6132,12360,10690,4266,4267,4282,6563,4570,7702,7495,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,6143,7492,4552,6140,6224,12328,7578,4553,7562,12896,7563,4251,6157,7489,4246,6178,12117,10653,4259,10657,4257,4261,4260,4319,7657,4230,10656,4255,4318,10659,10673,4256,4258,12827,10689,10646,7504,4559,13006,13007,10654,6144,5007,7485,7486,6997,12489,4994,12906);
$config['sources__4593'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(13298,13291,12273,12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    6415 => array(
        'm_icon' => '<i class="fas fa-trash-alt read" aria-hidden="true"></i>',
        'm_name' => 'CLEAR ALL READS',
        'm_desc' => 'Removes all player read coins so everything is reset to 0% again.',
        'm_parents' => array(12500,4755,4593),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(13304,13291,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-play read" aria-hidden="true"></i>',
        'm_name' => 'GET STARTED',
        'm_desc' => '',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    12773 => array(
        'm_icon' => '<i class="far fa-plus-circle idea"></i>',
        'm_name' => 'IDEA APPEND CONTENT',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,12149,10593,4593),
    ),
    6182 => array(
        'm_icon' => '<i class="fad fa-trash-alt idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DELETED',
        'm_desc' => '',
        'm_parents' => array(12400,4593,4737),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone idea"></i>',
        'm_name' => 'IDEA FEATURE REQUEST',
        'm_desc' => '',
        'm_parents' => array(12137,4755,4593,5967),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(12842,4527,6410,6283,4593,4486),
    ),
    4228 => array(
        'm_icon' => '<i class="fas fa-play-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK FIXED',
        'm_desc' => '',
        'm_parents' => array(12840,6410,4593,4486),
    ),
    10686 => array(
        'm_icon' => '<i class="fad fa-times idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10663 => array(
        'm_icon' => '<i class="fad fa-coin idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK UPDATE MARKS',
        'm_desc' => '',
        'm_parents' => array(4228,4593),
    ),
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4593,4229),
    ),
    12612 => array(
        'm_icon' => '<i class="fad fa-layer-minus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR PREVIOUS-',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12611 => array(
        'm_icon' => '<i class="fad fa-layer-plus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR PREVIOUS+',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12592 => array(
        'm_icon' => '<i class="fas fa-minus-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR SOURCE-',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12591 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR SOURCE+',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    6226 => array(
        'm_icon' => '<i class="fad fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA MASS UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10676 => array(
        'm_icon' => '<i class="fad fa-bars idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES SORTED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10678 => array(
        'm_icon' => '<i class="fad fa-trash-alt idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4593,10593),
    ),
    10677 => array(
        'm_icon' => '<i class="fad fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-bars idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    10675 => array(
        'm_icon' => '<i class="fad fa-bars idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCE REQUEST',
        'm_desc' => '',
        'm_parents' => array(4593,4755,5967),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    10662 => array(
        'm_icon' => '<i class="fad fa-hashtag idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE LINK',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10648 => array(
        'm_icon' => '<i class="fad fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(12400,4593),
    ),
    10650 => array(
        'm_icon' => '<i class="fad fa-clock idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE TIME',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10644 => array(
        'm_icon' => '<i class="fad fa-bullseye-arrow idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE TITLE',
        'm_desc' => 'Logged when trainers update the intent outcome',
        'm_parents' => array(10593,4593),
    ),
    10651 => array(
        'm_icon' => '<i class="fad fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE TYPE',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4993 => array(
        'm_icon' => '<i class="fad fa-eye idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA VIEWED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags idea" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12322,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,12322,10593,4986,4603,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-lightbulb-on idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(13211,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    5001 => array(
        'm_icon' => '<i class="source fad fa-sticky-note"></i>',
        'm_name' => 'PORTFOLIO EDITOR CONTENT REPLACE',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="source fad fa-user-circle"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON REPLACE',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON SET ALL',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    12318 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON SET IF NONE',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="source fad fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR LINK STATUS',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fad fa-layer-minus source"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE- ALL',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE+ ALL',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE+ IF @SOURCE',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="source fad fa-sliders-h"></i>',
        'm_name' => 'PORTFOLIO EDITOR STATUS',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    12129 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER DELETED',
        'm_desc' => '',
        'm_parents' => array(6153,4593),
    ),
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12197 => array(
        'm_icon' => '<i class="fad fa-user-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ APPEND PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7757 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED MANUAL',
        'm_desc' => 'Student prematurely removed an intention from their Action Plan without accomplishing it.',
        'm_parents' => array(10888,6150,4593,4755),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ CARBON COPY',
        'm_desc' => '',
        'm_parents' => array(4527,7569,4755,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    10683 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ EMAIL',
        'm_desc' => '',
        'm_parents' => array(6153,4593,7654),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA CONSIDERED',
        'm_desc' => 'When a student chooses to review a given intention from the intentions they have searched or have been recommended after selecting GET STARTED from a mench.com intent landing page.',
        'm_parents' => array(6153,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA LISTED',
        'm_desc' => 'Once a student has added an Intention to their Action Plan, this link will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="read fad fa-megaphone"></i>',
        'm_name' => 'READ IDEA RECOMMENDED',
        'm_desc' => 'Logged every time an intention is recommended to a user by Mench',
        'm_parents' => array(4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="read fad fa-search"></i>',
        'm_name' => 'READ IDEA SEARCH',
        'm_desc' => 'When students invokes the [I want to] command and search for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(6554,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="fad fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA VIEW',
        'm_desc' => 'When a user viewes the public intent landing page.',
        'm_parents' => array(4755,4593),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6132 => array(
        'm_icon' => '<i class="fad fa-bars read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST SORTED',
        'm_desc' => 'Student re-prioritized their top-level intentions to focus on intentions that currently matter the most.',
        'm_parents' => array(6153,4755,4593),
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
        'm_parents' => array(6153,4593),
    ),
    4266 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER OPT-IN',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="read fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED IDEA',
        'm_desc' => 'Emails sent to intent subscribers who are looking for updates on an intent.',
        'm_parents' => array(10593,4593,4755,7569),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone read" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => 'Intention recommended by Mench and added to Action Plan to enable the user to complete their intention',
        'm_parents' => array(12969,12227,4755,4593),
    ),
    4577 => array(
        'm_icon' => '<i class="read fad fa-user-plus"></i>',
        'm_name' => 'READ SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="read fad fa-location-circle"></i>',
        'm_name' => 'READ SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ SENT MESSENGER',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="read fad fa-cloud-download"></i>',
        'm_name' => 'READ SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="read fad fa-user-tag"></i>',
        'm_name' => 'READ SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="read fad fa-comment-exclamation"></i>',
        'm_name' => 'READ SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fad fa-sign-in read" aria-hidden="true"></i>',
        'm_name' => 'READ SIGNIN FROM IDEA',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => 'Logged every time a student consciously skips an intent and it\'s recursive children.',
        'm_parents' => array(12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => 'Created when the student responses to OR branches meets the right % points to unlock the pathway to a conditional intent link.',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="fad fa-sync read"></i>',
        'm_name' => 'READ UPDATE ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    12328 => array(
        'm_icon' => '<i class="fad fa-sync read"></i>',
        'm_name' => 'READ UPDATE COMPLETION',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6153),
    ),
    7578 => array(
        'm_icon' => '<i class="read fad fa-key"></i>',
        'm_name' => 'READ UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7562 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ WELCOME',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'SAVED',
        'm_desc' => '',
        'm_parents' => array(13289,4485,10876,12701,12321,7551,11089,11018,11035,4755,4593,12893),
    ),
    7563 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'SIGN MAGIC EMAIL',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ADDED',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(12274,12401,12149,12141,10593,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    6178 => array(
        'm_icon' => '<i class="fad fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE DELETED',
        'm_desc' => '',
        'm_parents' => array(4593,12401,6177),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    10653 => array(
        'm_icon' => '<i class="fad fa-user-circle source"></i>',
        'm_name' => 'SOURCE ICON UPDATE',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'SOURCE LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593),
    ),
    4257 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK EMBED',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="fad fa-sort-numeric-down source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK INTEGER',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    7657 => array(
        'm_icon' => '<i class="fas fa-divide source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK PERCENT',
        'm_desc' => '',
        'm_parents' => array(12822,4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="fad fa-link rotate90 source"></i>',
        'm_name' => 'SOURCE LINK RAW',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    10656 => array(
        'm_icon' => '<i class="fad fa-sliders-h source"></i>',
        'm_name' => 'SOURCE LINK STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'SOURCE LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(12822,10593,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="fad fa-clock source"></i>',
        'm_name' => 'SOURCE LINK TIME',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    10659 => array(
        'm_icon' => '<i class="fad fa-plug source"></i>',
        'm_name' => 'SOURCE LINK TYPE UPDATE',
        'm_desc' => 'Iterations happens automatically based on link content',
        'm_parents' => array(4593),
    ),
    10673 => array(
        'm_icon' => '<i class="fad fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK URL',
        'm_desc' => '',
        'm_parents' => array(12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    12827 => array(
        'm_icon' => '<i class="fad fa-font source"></i>',
        'm_name' => 'SOURCE LINK WORD',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    10689 => array(
        'm_icon' => '<i class="fad fa-share-alt rotate90 source"></i>',
        'm_name' => 'SOURCE MERGED IN SOURCE',
        'm_desc' => 'When an entity is merged with another entity and the links are carried over',
        'm_parents' => array(4593),
    ),
    10646 => array(
        'm_icon' => '<i class="fad fa-fingerprint source"></i>',
        'm_name' => 'SOURCE NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PENDING MODERATION',
        'm_desc' => 'Certain links that match an unknown behavior would require an admin to review and ensure it\'s all good',
        'm_parents' => array(5967,4755,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => 'Logged when a student receives the messages of an AND intent that does not have any completion requirements.',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    13006 => array(
        'm_icon' => '<i class="fad fa-bars source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    13007 => array(
        'm_icon' => '<i class="fad fa-sort-alpha-down source"></i>',
        'm_name' => 'SOURCE SORT RESET TO ALPHABETICAL',
        'm_desc' => 'Removes the sort value of all portfolio sources which would sort it by alphabetical value',
        'm_parents' => array(11035,4593),
    ),
    10654 => array(
        'm_icon' => '<i class="fad fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(12401,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => 'Logged when a student submits the requirements (text, video, etc...) of an AND intent which could not be completed by simply receiving messages.',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    5007 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TOGGLE SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
    12489 => array(
        'm_icon' => '<i class="far fa-medal source"></i>',
        'm_name' => 'SOURCE VIEW',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4994 => array(
        'm_icon' => '<i class="fad fa-eye source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE VIEWED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    12906 => array(
        'm_icon' => '<i class="fal fa-bookmark read"></i>',
        'm_name' => 'UNSAVED',
        'm_desc' => '',
        'm_parents' => array(12896,4755,4593),
    ),
);

//SOURCE LINKS:
$config['sources_id_4592'] = array(4259,4257,4261,4260,4319,7657,4230,4255,4318,4256,4258,12827);
$config['sources__4592'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'EMBED',
        'm_desc' => 'Embeddable videos',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="fad fa-sort-numeric-down source" aria-hidden="true"></i>',
        'm_name' => 'INTEGER',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    7657 => array(
        'm_icon' => '<i class="fas fa-divide source" aria-hidden="true"></i>',
        'm_name' => 'PERCENT',
        'm_desc' => '',
        'm_parents' => array(12822,4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="fad fa-link rotate90 source"></i>',
        'm_name' => 'RAW',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'TEXT',
        'm_desc' => '',
        'm_parents' => array(12822,10593,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="fad fa-clock source"></i>',
        'm_name' => 'TIME',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'Uploaded videos',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    12827 => array(
        'm_icon' => '<i class="fad fa-font source"></i>',
        'm_name' => 'WORD',
        'm_desc' => 'Single Word',
        'm_parents' => array(4593,4592),
    ),
);

//IDEA NOTES:
$config['sources_id_4485'] = array(12419,4983,4601,4231,10573,7545,12896);
$config['sources__4485'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(13304,13291,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-user-edit source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags idea" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12322,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,12322,10593,4986,4603,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-lightbulb-on idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(13211,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(13298,13291,12273,12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'SAVED',
        'm_desc' => '',
        'm_parents' => array(13289,4485,10876,12701,12321,7551,11089,11018,11035,4755,4593,12893),
    ),
);

//IDEA LINKS:
$config['sources_id_4486'] = array(4228,4229);
$config['sources__4486'] = array(
    4228 => array(
        'm_icon' => '<i class="fas fa-play-circle idea" aria-hidden="true"></i>',
        'm_name' => 'FIXED',
        'm_desc' => 'Follow each other',
        'm_parents' => array(12840,6410,4593,4486),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle idea" aria-hidden="true"></i>',
        'm_name' => 'CONDITIONAL',
        'm_desc' => 'May follow each other',
        'm_parents' => array(12842,4527,6410,6283,4593,4486),
    ),
);

//SOURCE LINK URLS:
$config['sources_id_4537'] = array(4259,4257,4261,4260,4256,4258);
$config['sources__4537'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'URL to a raw audio file',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'EMBED',
        'm_desc' => 'Recognizable URL that offers an embed widget for a more engaging play-back experience',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'URL to a raw file',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'URL to a raw image file',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,4593,4592,4537),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => 'Link note contains a generic URL only',
        'm_parents' => array(12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'URL to a raw video file',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
);

//EXPERT CONTENT:
$config['sources_id_3000'] = array(3005,2998,2997,13218,3147,4446,3192,4763);
$config['sources__3000'] = array(
    3005 => array(
        'm_icon' => '<i class="fas fa-book source" aria-hidden="true"></i>',
        'm_name' => 'BOOK',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fas fa-play-circle source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    2997 => array(
        'm_icon' => '<i class="fas fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'ARTICLE',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    13218 => array(
        'm_icon' => '<i class="fas fa-microphone source"></i>',
        'm_name' => 'PODCAST',
        'm_desc' => '',
        'm_parents' => array(3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fas fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'COURSE',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fas fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'ASSESSMENT',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fas fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'TOOL',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fas fa-megaphone source" aria-hidden="true"></i>',
        'm_name' => 'MARKETING CHANNEL',
        'm_desc' => '',
        'm_parents' => array(12968,4983,3000),
    ),
);