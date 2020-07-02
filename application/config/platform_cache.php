<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
* Keep a cache of certain parts of the idea for faster processing
* See source @4527 for more details
*
*/

//Generated 2020-07-02 16:05:54 PST

//METADATA CLEAN VARIABLES:
$config['n___7277'] = array(6159,6172);
$config['e___7277'] = array(
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA METADATA',
        'm_desc' => '',
        'm_parents' => array(7277,4527,6212,11049,6201),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda source"></i>',
        'm_name' => 'SOURCE METADATA',
        'm_desc' => '',
        'm_parents' => array(7277,4527,6212,6206),
    ),
);

//SOURCE METADATA:
$config['n___6172'] = array(6207);
$config['e___6172'] = array(
    6207 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'SOURCE METADATA ALGOLIA ID',
        'm_desc' => 'algolia__id',
        'm_parents' => array(3323,6172),
    ),
);

//TRANSACTION TYPE UPDATE:
$config['n___13442'] = array(4486);
$config['e___13442'] = array(
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => 'x__type',
        'm_parents' => array(13442,13408,12700,11054,12079,10662,4527),
    ),
);

//IDEA METADATA:
$config['n___6159'] = array(7545,13339,3000,6208,6168,6283,12885,6228,6162,6161,6170,6169,13202);
$config['e___6159'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => 'i___7545',
        'm_parents' => array(6159,13421,13298,13291,12273,12197,11018,11089,7551,4593,4485),
    ),
    13339 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT AUTHORS',
        'm_desc' => 'i___13339',
        'm_parents' => array(6159,4251,12968,13428,13365,13207,12864),
    ),
    3000 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT CONTENT',
        'm_desc' => 'i___3000',
        'm_parents' => array(6159,4251,13428,13365,12864,13207,4527),
    ),
    6208 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA ALGOLIA ID',
        'm_desc' => 'algolia__id',
        'm_parents' => array(3323,6159),
    ),
    6168 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA COMMON STEPS',
        'm_desc' => 'i___6168',
        'm_parents' => array(6159),
    ),
    6283 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA EXPANSION CONDITIONAL',
        'm_desc' => 'i___6283',
        'm_parents' => array(6159),
    ),
    12885 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA EXPANSION SOME',
        'm_desc' => 'i___12885',
        'm_parents' => array(6159),
    ),
    6228 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA EXPANSION STEPS',
        'm_desc' => 'i___6228',
        'm_parents' => array(6159),
    ),
    6162 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'IDEA METADATA MAXIMUM SECONDS',
        'm_desc' => 'i___6162',
        'm_parents' => array(13292,4739,6159),
    ),
    6161 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA MINIMUM SECONDS',
        'm_desc' => 'i___6161',
        'm_parents' => array(13292,4735,6159),
    ),
    6170 => array(
        'm_icon' => '<i class="fas fa-circle idea"></i>',
        'm_name' => 'IDEA TREE MAX',
        'm_desc' => 'i___6170',
        'm_parents' => array(13443,13409,6159),
    ),
    6169 => array(
        'm_icon' => '<i class="fas fa-circle idea"></i>',
        'm_name' => 'IDEA TREE MIN',
        'm_desc' => 'i___6169',
        'm_parents' => array(13443,13409,6159),
    ),
    13202 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MENCH IDEATORS',
        'm_desc' => 'i___13202',
        'm_parents' => array(13451,6159,4251,13365,4527,13207),
    ),
);

//MENCH VARIABLES:
$config['n___6212'] = array(6201,6159,6206,6172,4341,6103,13442);
$config['e___6212'] = array(
    6201 => array(
        'm_icon' => '<i class="fas fa-table idea" aria-hidden="true"></i>',
        'm_name' => 'MENCH IDEAS',
        'm_desc' => '',
        'm_parents' => array(6212,11054,4527,7735,4535),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA METADATA',
        'm_desc' => '',
        'm_parents' => array(7277,4527,6212,11049,6201),
    ),
    6206 => array(
        'm_icon' => '<i class="fas fa-table source" aria-hidden="true"></i>',
        'm_name' => 'MENCH SOURCES',
        'm_desc' => '',
        'm_parents' => array(6212,4527,7735,4536),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda source"></i>',
        'm_name' => 'SOURCE METADATA',
        'm_desc' => '',
        'm_parents' => array(7277,4527,6212,6206),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas discover" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(6212,12887,11047,12707,10876,12588,11035,4527,7735),
    ),
    6103 => array(
        'm_icon' => '<i class="fas fa-lambda"></i>',
        'm_name' => 'TRANSACTION METADATA',
        'm_desc' => '',
        'm_parents' => array(6212,4527,4341),
    ),
    13442 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE UPDATE',
        'm_desc' => '',
        'm_parents' => array(4527,6212),
    ),
);

//SOURCE ADDED:
$config['n___4251'] = array(13202,3000,13339);
$config['e___4251'] = array(
    13202 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MENCH IDEATORS',
        'm_desc' => '',
        'm_parents' => array(13451,6159,4251,13365,4527,13207),
    ),
    3000 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT CONTENT',
        'm_desc' => '',
        'm_parents' => array(6159,4251,13428,13365,12864,13207,4527),
    ),
    13339 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT AUTHORS',
        'm_desc' => '',
        'm_parents' => array(6159,4251,12968,13428,13365,13207,12864),
    ),
);

//RANK BY DISCOVERIES:
$config['n___13439'] = array(13438);
$config['e___13439'] = array(
    13438 => array(
        'm_icon' => '<i class="fas fa-map-marker-check discover"></i>',
        'm_name' => 'MENCH READERS',
        'm_desc' => '',
        'm_parents' => array(13451,13207,4527,13439),
    ),
);

//MENCH READERS:
$config['n___13438'] = array(4430);
$config['e___13438'] = array(
    4430 => array(
        'm_icon' => '<i class="fad fa-users source" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMBERS',
        'm_desc' => '',
        'm_parents' => array(13451,13438,13202,4364,4536,1278,10573),
    ),
);

//SOURCE LAYOUT HIDE IF SOURCE:
$config['n___13424'] = array(12969,10573,12896);
$config['e___13424'] = array(
    12969 => array(
        'm_icon' => '<i class="fas fa-compass discover" aria-hidden="true"></i>',
        'm_name' => 'MY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13424,6205,13210,12228,11035,11018,12677,12701,4527,11089),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(10876,11035,13424,4535,11054,4527,10984,11018,11035,11089,4593,7551,4485),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark discover"></i>',
        'm_name' => 'SAVED IDEAS',
        'm_desc' => '',
        'm_parents' => array(13424,13210,12677,13289,4485,12701,7551,11089,11018,11035,4755,4593),
    ),
);

//SOURCE LAYOUT SHOW IF SOURCE:
$config['n___13425'] = array(6225);
$config['e___13425'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => '',
        'm_parents' => array(13425,12574,11089,11035,4527),
    ),
);

//REMOVE IDEA:
$config['n___13414'] = array(6155,13415);
$config['e___13414'] = array(
    6155 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'DISCOVERY',
        'm_desc' => '',
        'm_parents' => array(13414,6150,4593,4755),
    ),
    13415 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'IDEA',
        'm_desc' => '',
        'm_parents' => array(4755,4593,13414),
    ),
);

//SORT IDEA:
$config['n___13413'] = array(6132,13412);
$config['e___13413'] = array(
    6132 => array(
        'm_icon' => '<i class="fas fa-arrows"></i>',
        'm_name' => 'DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13413,6153,4755,4593),
    ),
    13412 => array(
        'm_icon' => '<i class="fas fa-arrows"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13413,4755,4593),
    ),
);

//IDEA TOOLBAR:
$config['n___13408'] = array(7585,4737,4486,4358,6402,12413);
$config['e___13408'] = array(
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(13408,13295,11054,12079,6204,10651,6160,6194,4527,6201),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13408,13295,11054,10990,12079,6204,6226,6160,6194,6201,4527),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => '',
        'm_parents' => array(13442,13408,12700,11054,12079,10662,4527),
    ),
    4358 => array(
        'm_icon' => '<i class="fas fa-comment-alt-check idea" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER MARKS',
        'm_desc' => '',
        'm_parents' => array(13408,12700,12112,10663,6103,6410),
    ),
    6402 => array(
        'm_icon' => '<i class="fas fa-temperature-high idea" aria-hidden="true"></i>',
        'm_name' => 'CONDITION SCORE RANGE',
        'm_desc' => '',
        'm_parents' => array(13408,12700,10664,6410),
    ),
    12413 => array(
        'm_icon' => '<i class="fas fa-sitemap idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TREE',
        'm_desc' => '',
        'm_parents' => array(13408,6768,4527),
    ),
);

//DISCOVER LAYOUT:
$config['n___12994'] = array(13400,4736,13291,13289);
$config['e___12994'] = array(
    13400 => array(
        'm_icon' => '<i class="fas fa-list discover"></i>',
        'm_name' => 'IDEA INDEX',
        'm_desc' => '',
        'm_parents' => array(12994),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TITLE',
        'm_desc' => '',
        'm_parents' => array(13407,13294,12994,6404,10990,12112,10644,6201),
    ),
    13291 => array(
        'm_icon' => '<i class="fas fa-bars discover"></i>',
        'm_name' => 'DISCOVER TABS',
        'm_desc' => '',
        'm_parents' => array(13299,4527,12994),
    ),
    13289 => array(
        'm_icon' => '<i class="fas fa-gamepad discover"></i>',
        'm_name' => 'DISCOVER CONTROLLER',
        'm_desc' => '',
        'm_parents' => array(12994),
    ),
);

//IDEA COVER UI:
$config['n___13369'] = array(12274,12273,6255,13292,13413,13414);
$config['e___13369'] = array(
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,4536,12467,12228,4527),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,13410,4983,4535,12571,12467,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13355,13369,13291,12701,6205,12677,10939,11018,12467,12228,4527),
    ),
    13292 => array(
        'm_icon' => '<i class="fas fa-stopwatch"></i>',
        'm_name' => 'AVERAGE DISCOVERY TIME',
        'm_desc' => '',
        'm_parents' => array(13369,4356),
    ),
    13413 => array(
        'm_icon' => '<i class="fas fa-arrows"></i>',
        'm_name' => 'SORT IDEA',
        'm_desc' => 'SORT from LEFT to RIGHT, TOP to BOTTOM ',
        'm_parents' => array(4527,13369),
    ),
    13414 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'REMOVE IDEA',
        'm_desc' => '',
        'm_parents' => array(4527,13369),
    ),
);

//RANK BY IDEAS:
$config['n___13365'] = array(13339,3000,13202);
$config['e___13365'] = array(
    13339 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT AUTHORS',
        'm_desc' => '',
        'm_parents' => array(6159,4251,12968,13428,13365,13207,12864),
    ),
    3000 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT CONTENT',
        'm_desc' => '',
        'm_parents' => array(6159,4251,13428,13365,12864,13207,4527),
    ),
    13202 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MENCH IDEATORS',
        'm_desc' => '',
        'm_parents' => array(13451,6159,4251,13365,4527,13207),
    ),
);

//MENCH OBJECTS:
$config['n___13355'] = array(12274,12273,6255,13362);
$config['e___13355'] = array(
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,4536,12467,12228,4527),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,13410,4983,4535,12571,12467,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13355,13369,13291,12701,6205,12677,10939,11018,12467,12228,4527),
    ),
    13362 => array(
        'm_icon' => '<i class="fas fa-book"></i>',
        'm_name' => 'TRANSACTIONS',
        'm_desc' => '',
        'm_parents' => array(13355),
    ),
);

//IDEA NOTES SORTING ALLOWED:
$config['n___4603'] = array(4231);
$config['e___4603'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,10593,4986,4603,4593,4485),
    ),
);

//DISCOVER TABS SHOW IF LOGGED-IN:
$config['n___13304'] = array(12419,12749,13023);
$config['e___13304'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(11089,13304,13291,11018,12359,5967,10593,4986,4593,4485),
    ),
    12749 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus"></i>',
        'm_name' => 'MAP IDEA',
        'm_desc' => '',
        'm_parents' => array(10939,13304,13291),
    ),
    13023 => array(
        'm_icon' => '<i class="fas fa-share"></i>',
        'm_name' => 'SHARE',
        'm_desc' => '',
        'm_parents' => array(13304,13291,4527,13024,7305),
    ),
);

//DISCOVER TABS HIDE IF ZERO:
$config['n___13298'] = array(7545,12273,12274);
$config['e___13298'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(6159,13421,13298,13291,12273,12197,11018,11089,7551,4593,4485),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,13410,4983,4535,12571,12467,12228,4527),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,4536,12467,12228,4527),
    ),
);

//DISCOVER TABS DEFAULT SELECTED:
$config['n___13300'] = array(4231);
$config['e___13300'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,10593,4986,4603,4593,4485),
    ),
);

//DISCOVER TABS:
$config['n___13291'] = array(4231,12419,7545,13023,12749,12274,12273,6255);
$config['e___13291'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,10593,4986,4603,4593,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(11089,13304,13291,11018,12359,5967,10593,4986,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(6159,13421,13298,13291,12273,12197,11018,11089,7551,4593,4485),
    ),
    13023 => array(
        'm_icon' => '<i class="fas fa-share"></i>',
        'm_name' => 'SHARE',
        'm_desc' => '',
        'm_parents' => array(13304,13291,4527,13024,7305),
    ),
    12749 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus"></i>',
        'm_name' => 'MAP IDEA',
        'm_desc' => '',
        'm_parents' => array(10939,13304,13291),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,4536,12467,12228,4527),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,13410,4983,4535,12571,12467,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13355,13369,13291,12701,6205,12677,10939,11018,12467,12228,4527),
    ),
);

//MENCH IDEATORS:
$config['n___13202'] = array(4430);
$config['e___13202'] = array(
    4430 => array(
        'm_icon' => '<i class="fad fa-users source" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMBERS',
        'm_desc' => '',
        'm_parents' => array(13451,13438,13202,4364,4536,1278,10573),
    ),
);

//LEADERBOARD:
$config['n___13207'] = array(13202,13438,13339,3000);
$config['e___13207'] = array(
    13202 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MENCH IDEATORS',
        'm_desc' => '',
        'm_parents' => array(13451,6159,4251,13365,4527,13207),
    ),
    13438 => array(
        'm_icon' => '<i class="fas fa-map-marker-check discover"></i>',
        'm_name' => 'MENCH READERS',
        'm_desc' => '',
        'm_parents' => array(13451,13207,4527,13439),
    ),
    13339 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT AUTHORS',
        'm_desc' => '',
        'm_parents' => array(6159,4251,12968,13428,13365,13207,12864),
    ),
    3000 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT CONTENT',
        'm_desc' => '',
        'm_parents' => array(6159,4251,13428,13365,12864,13207,4527),
    ),
);

//DONATE:
$config['n___13037'] = array(13367,13038,13039,13040,13041,13042);
$config['e___13037'] = array(
    13367 => array(
        'm_icon' => '<i class="fas fa-minus-circle"></i>',
        'm_name' => 'NONE FOR NOW',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
    ),
    13038 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$5 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
    ),
    13039 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$10 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
    ),
    13040 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$20 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
    ),
    13041 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$50 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
    ),
    13042 => array(
        'm_icon' => '<i class="far fa-usd-circle"></i>',
        'm_name' => 'ONE-TIME DONATION',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
    ),
);

//TOPICS OF INTEREST:
$config['n___10869'] = array(10712,13033,10735,10739,10809,10719,13031,10711,10781,10774,10782,10775,10769,10721,10737,13034,10738,11125,10773,7325,13036);
$config['e___10869'] = array(
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
$config['n___12761'] = array(4535,4536);
$config['e___12761'] = array(
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'MAP',
        'm_desc' => '',
        'm_parents' => array(10939,12893,11035,12761,12112,2738),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(10939,12489,11035,12893,12761,4527,2738),
    ),
);

//SHARE:
$config['n___13023'] = array(12889,12890,3300,3302,3288,13026,3099);
$config['e___13023'] = array(
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
$config['n___13022'] = array(6677,6683,7637);
$config['e___13022'] = array(
    6677 => array(
        'm_icon' => '<i class="fas fa-eye idea" aria-hidden="true"></i>',
        'm_name' => 'READ & NEXT',
        'm_desc' => '',
        'm_parents' => array(13022,12330,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="fas fa-keyboard idea" aria-hidden="true"></i>',
        'm_name' => 'REPLY & NEXT',
        'm_desc' => '',
        'm_parents' => array(13022,12955,6144,7585,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="fas fa-cloud-upload idea" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD & NEXT',
        'm_desc' => '',
        'm_parents' => array(13022,12955,12117,7751,7585,6192),
    ),
);

//SOURCE LAYOUT RESTRICT COUNTS:
$config['n___13004'] = array(11029,11030);
$config['e___13004'] = array(
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
        'm_parents' => array(7545,13411,13004,12574,11089,11028),
    ),
);

//MY DISCOVERIES:
$config['n___12969'] = array(4235,7495);
$config['e___12969'] = array(
    4235 => array(
        'm_icon' => '<i class="fas fa-plus discover" aria-hidden="true"></i>',
        'm_name' => 'ADD TO MY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone discover" aria-hidden="true"></i>',
        'm_name' => 'RECOMMENDED DISCOVERY',
        'm_desc' => '',
        'm_parents' => array(12969,12227,4755,4593),
    ),
);

//SYNC ICONS IF DIFFERENT:
$config['n___12968'] = array(13339,2997,4446,3005,3147,13350,3192,2998);
$config['e___12968'] = array(
    13339 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'AUTHORS',
        'm_desc' => '',
        'm_parents' => array(6159,4251,12968,13428,13365,13207,12864),
    ),
    2997 => array(
        'm_icon' => '<i class="fas fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'ARTICLES',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fas fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fas fa-book source" aria-hidden="true"></i>',
        'm_name' => 'BOOKS',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fas fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'COURSES',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    13350 => array(
        'm_icon' => '<i class="fas fa-file-chart-line source"></i>',
        'm_name' => 'REPORTS',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fas fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'TOOLS',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fas fa-play-circle source"></i>',
        'm_name' => 'VIDEOS',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
);

//IDEA TYPE TAKES COMPLETION TIME:
$config['n___12955'] = array(6683,6684,7231,7637);
$config['e___12955'] = array(
    6683 => array(
        'm_icon' => '<i class="fas fa-keyboard idea" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fas fa-cloud-upload idea" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD & NEXT',
        'm_desc' => '30',
        'm_parents' => array(13022,12955,12117,7751,7585,6192),
    ),
);

//HORIZONTAL MENU:
$config['n___12893'] = array(4536,4535,6205);
$config['e___12893'] = array(
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(10939,12489,11035,12893,12761,4527,2738),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'MAP',
        'm_desc' => '',
        'm_parents' => array(10939,12893,11035,12761,12112,2738),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER',
        'm_desc' => '',
        'm_parents' => array(12893,2738),
    ),
);

//SOURCE MENU:
$config['n___12887'] = array(12193,4341,12888,7267,12712,7279);
$config['e___12887'] = array(
    12193 => array(
        'm_icon' => '<i class="fab fa-google"></i>',
        'm_name' => 'GOOGLE',
        'm_desc' => '/e/search_google/',
        'm_parents' => array(12891,12887,3088),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas discover" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '/x?any_e__id=',
        'm_parents' => array(6212,12887,11047,12707,10876,12588,11035,4527,7735),
    ),
    12888 => array(
        'm_icon' => '<i class="fas fa-shield-check source"></i>',
        'm_name' => 'SOURCE EXPLORE EXPERTS',
        'm_desc' => '/e/plugin/12888?e__id=',
        'm_parents' => array(12741,6287,12887),
    ),
    7267 => array(
        'm_icon' => '🔍',
        'm_name' => 'SOURCE ICON SEARCH',
        'm_desc' => '/e/search_icon/',
        'm_parents' => array(12887,6287),
    ),
    12712 => array(
        'm_icon' => '<i class="fad fa-lambda source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REVIEW JSON',
        'm_desc' => '/e/plugin/12712?e__id=',
        'm_parents' => array(12887,12741,6287),
    ),
    7279 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SYNC SEARCH INDEX',
        'm_desc' => '/e/plugin/7279?obj=4536&object__id=',
        'm_parents' => array(6287,12741,12887,11047,3323,7287),
    ),
);

//IDEA TYPE SELECT ONE:
$config['n___12883'] = array(6907,6684);
$config['e___12883'] = array(
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
$config['n___12884'] = array(7231);
$config['e___12884'] = array(
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SELECT SOME',
        'm_desc' => '',
        'm_parents' => array(12955,12884,12334,12129,7712,7489,7585,6193),
    ),
);

//IDEA LINK ONE-WAY:
$config['n___12842'] = array(4229);
$config['e___12842'] = array(
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(12842,4527,6410,6283,4593,4486),
    ),
);

//IDEA LINK TWO-WAYS:
$config['n___12840'] = array(4228);
$config['e___12840'] = array(
    4228 => array(
        'm_icon' => '<i class="fas fa-play-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK FIXED',
        'm_desc' => '',
        'm_parents' => array(12840,6410,4593,4486),
    ),
);

//SOURCE LINK MESSAGE DISPLAY:
$config['n___12822'] = array(4259,4257,4261,4260,7657,4255,4256,4258);
$config['e___12822'] = array(
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
        'm_icon' => '<i class="fas fa-external-link source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(13433,12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
);

//MY IDEAS:
$config['n___10573'] = array(4430);
$config['e___10573'] = array(
    4430 => array(
        'm_icon' => '<i class="fad fa-users source" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMBERS',
        'm_desc' => '',
        'm_parents' => array(13451,13438,13202,4364,4536,1278,10573),
    ),
);

//PLUGIN RETURN CODE ONLY:
$config['n___12741'] = array(12733,12722,4356,11049,7275,7276,4527,12114,7277,12710,12709,12888,12732,12712,7278,12967,7279,12569);
$config['e___12741'] = array(
    12733 => array(
        'm_icon' => '<i class="fad fa-code discover"></i>',
        'm_name' => 'DISCOVER REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(11047,12741,12701,6287),
    ),
    12722 => array(
        'm_icon' => '',
        'm_name' => 'DISCOVER REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION (SECONDS)',
        'm_desc' => '',
        'm_parents' => array(10986,13295,6287,12741,11047,7274,6404,12112,12420,10650,6201),
    ),
    11049 => array(
        'm_icon' => '<i class="fad fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12741,6287,11047),
    ),
    7275 => array(
        'm_icon' => '<i class="fad fa-sync idea mench-spin"></i>',
        'm_name' => 'IDEA SYNC COMMON BASE',
        'm_desc' => '',
        'm_parents' => array(6287,12741,7286,7274),
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
        'm_icon' => '<i class="fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'MENCH WEEKLY GROWTH REPORT',
        'm_desc' => '',
        'm_parents' => array(6287,12741,12701,7274,7569),
    ),
    7277 => array(
        'm_icon' => '',
        'm_name' => 'METADATA CLEAN VARIABLES',
        'm_desc' => '',
        'm_parents' => array(4527,6287,12741,7287,7274),
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
$config['n___12687'] = array(12691,12694,12695);
$config['e___12687'] = array(
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
$config['n___12675'] = array(11020);
$config['e___12675'] = array(
    11020 => array(
        'm_icon' => '<i class="fas fa-circle idea"></i>',
        'm_name' => 'NEXT IDEAS',
        'm_desc' => '',
        'm_parents' => array(12675,12413,11018),
    ),
);

//IDEA TABS NO MANUAL ADD / HIDE IF ZERO:
$config['n___12677'] = array(6255,12969,12896);
$config['e___12677'] = array(
    6255 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13355,13369,13291,12701,6205,12677,10939,11018,12467,12228,4527),
    ),
    12969 => array(
        'm_icon' => '<i class="fas fa-compass discover" aria-hidden="true"></i>',
        'm_name' => 'MY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13424,6205,13210,12228,11035,11018,12677,12701,4527,11089),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark discover"></i>',
        'm_name' => 'SAVED IDEAS',
        'm_desc' => '',
        'm_parents' => array(13424,13210,12677,13289,4485,12701,7551,11089,11018,11035,4755,4593),
    ),
);

//VERTICAL MENU:
$config['n___12500'] = array(13449,13207,6415,6287,7291);
$config['e___12500'] = array(
    13449 => array(
        'm_icon' => '<i class="fas fa-user source"></i>',
        'm_name' => 'MY SOURCE',
        'm_desc' => '',
        'm_parents' => array(12500),
    ),
    13207 => array(
        'm_icon' => '<i class="fas fa-medal source"></i>',
        'm_name' => 'LEADERBOARD',
        'm_desc' => '',
        'm_parents' => array(10876,12500,11035,10939,4527,4536),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-trash-alt discover" aria-hidden="true"></i>',
        'm_name' => 'RESET DISCOVERIES',
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
        'm_name' => 'SIGNOUT',
        'm_desc' => '',
        'm_parents' => array(12500,10876),
    ),
);

//IDEA NOTES STATUSES:
$config['n___12012'] = array(6176,6173);
$config['e___12012'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="far fa-trash-alt discover" aria-hidden="true"></i>',
        'm_name' => 'UNPUBLISH',
        'm_desc' => '',
        'm_parents' => array(12012,10686,10678,10673,6186),
    ),
);

//MENCH COINS:
$config['n___12467'] = array(12274,12273,6255);
$config['e___12467'] = array(
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,4536,12467,12228,4527),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,13410,4983,4535,12571,12467,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13355,13369,13291,12701,6205,12677,10939,11018,12467,12228,4527),
    ),
);

//IDEA LIST EDITOR:
$config['n___12589'] = array(12591,12592,12611,12612);
$config['e___12589'] = array(
    12591 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ADD',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12592 => array(
        'm_icon' => '<i class="fas fa-minus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REMOVE',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12611 => array(
        'm_icon' => '<i class="fad fa-layer-plus idea" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS IDEA ADD',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12612 => array(
        'm_icon' => '<i class="fad fa-layer-minus idea" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS IDEA REMOVE',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
);

//AVOID PREFIX REMOVAL:
$config['n___12588'] = array(4341);
$config['e___12588'] = array(
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas discover" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(6212,12887,11047,12707,10876,12588,11035,4527,7735),
    ),
);

//SIGN IN/UP:
$config['n___4269'] = array(3288,13025,3286);
$config['e___4269'] = array(
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

//IDEA NOTES FILE UPLOADING ALLOWED:
$config['n___12359'] = array(12419,4231);
$config['e___12359'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(11089,13304,13291,11018,12359,5967,10593,4986,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,10593,4986,4603,4593,4485),
    ),
);

//PORTFOLIO EDITOR UPPERCASE:
$config['n___12577'] = array(4999,4998,5000,11956,5981,13441,5982);
$config['e___12577'] = array(
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
    11956 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE ADD @ IF HAS @',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE ADD @ IF MISSING',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    13441 => array(
        'm_icon' => '<i class="fad fa-arrow-right source"></i>',
        'm_name' => 'PROFILE MOVE @ IF MISSING',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fad fa-layer-minus source"></i>',
        'm_name' => 'PROFILE REMOVE @ IF THERE',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
);

//SOURCE LAYOUT SHOW EVEN IF ZERO:
$config['n___12574'] = array(6225,11029,11030);
$config['e___12574'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => '',
        'm_parents' => array(13425,12574,11089,11035,4527),
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
        'm_parents' => array(7545,13411,13004,12574,11089,11028),
    ),
);

//SOURCE STATUS FEATURED:
$config['n___12575'] = array(12563);
$config['e___12575'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
);

//SOURCE LAYOUT OPEN BY DEFAULT:
$config['n___12571'] = array(12273,11029);
$config['e___12571'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,13410,4983,4535,12571,12467,12228,4527),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => '',
        'm_parents' => array(13004,12574,12571,11089,11028),
    ),
);

//SOURCE LINK VISUAL:
$config['n___12524'] = array(4259,4257,4261,4260,4258);
$config['e___12524'] = array(
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

//SYNC ICONS IF MISSING ICON:
$config['n___12523'] = array(6293,13450);
$config['e___12523'] = array(
    6293 => array(
        'm_icon' => '<i class="fas fa-image source"></i>',
        'm_name' => 'GIPHY GIFS',
        'm_desc' => '',
        'm_parents' => array(12523,12891,1326),
    ),
    13450 => array(
        'm_icon' => '<i class="fas fa-text"></i>',
        'm_name' => 'MENCH TERMINOLOGY INDEX',
        'm_desc' => '',
        'm_parents' => array(12523,7305),
    ),
);

//DISCOVER ICON LEGEND:
$config['n___12446'] = array(12448,12447,13338);
$config['e___12446'] = array(
    12448 => array(
        'm_icon' => '<i class="fas fa-circle idea"></i>',
        'm_name' => 'IDEA NOT DISCOVERED',
        'm_desc' => '',
        'm_parents' => array(12446),
    ),
    12447 => array(
        'm_icon' => '<i class="fas fa-play-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER IN PROGRESS',
        'm_desc' => '',
        'm_parents' => array(12446),
    ),
    13338 => array(
        'm_icon' => '<i class="fas fa-check-circle discover"></i>',
        'm_name' => 'DISCOVER COMPLETED',
        'm_desc' => '',
        'm_parents' => array(12446),
    ),
);

//IDEA TEXT INPUT SHOW ICON:
$config['n___12420'] = array(4356);
$config['e___12420'] = array(
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION (SECONDS)',
        'm_desc' => '',
        'm_parents' => array(10986,13295,6287,12741,11047,7274,6404,12112,12420,10650,6201),
    ),
);

//IDEA TREE:
$config['n___12413'] = array(11019,11020);
$config['e___12413'] = array(
    11019 => array(
        'm_icon' => '<i class="fas fa-circle idea"></i>',
        'm_name' => 'PREVIOUS IDEAS',
        'm_desc' => '',
        'm_parents' => array(13294,12413,10990),
    ),
    11020 => array(
        'm_icon' => '<i class="fas fa-circle idea"></i>',
        'm_name' => 'NEXT IDEAS',
        'm_desc' => '',
        'm_parents' => array(12675,12413,11018),
    ),
);

//MENCH URL:
$config['n___10876'] = array(13207,4341,10573,6287,4269,7291);
$config['e___10876'] = array(
    13207 => array(
        'm_icon' => '<i class="fas fa-medal source"></i>',
        'm_name' => 'LEADERBOARD',
        'm_desc' => 'https://mench.com/@',
        'm_parents' => array(10876,12500,11035,10939,4527,4536),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas discover" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => 'https://mench.com/x',
        'm_parents' => array(6212,12887,11047,12707,10876,12588,11035,4527,7735),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => 'https://mench.com/~',
        'm_parents' => array(10876,11035,13424,4535,11054,4527,10984,11018,11035,11089,4593,7551,4485),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => 'https://mench.com/e/plugin',
        'm_parents' => array(13297,6405,6194,12699,12500,10876,11035,4527),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in-alt" aria-hidden="true"></i>',
        'm_name' => 'SIGN IN/UP',
        'm_desc' => 'https://mench.com/e/signin',
        'm_parents' => array(12497,10876,4527,11035),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off" aria-hidden="true"></i>',
        'm_name' => 'SIGNOUT',
        'm_desc' => 'https://mench.com/e/signout',
        'm_parents' => array(12500,10876),
    ),
);

//SOURCE LINK TYPE CUSTOM UI:
$config['n___12403'] = array(4257);
$config['e___12403'] = array(
    4257 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK EMBED',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
);

//SOURCE STATUS SYNC:
$config['n___12401'] = array(4251,6178,10654);
$config['e___12401'] = array(
    4251 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'ADDED',
        'm_desc' => '',
        'm_parents' => array(4527,12274,12401,12149,12141,10593,4593),
    ),
    6178 => array(
        'm_icon' => '<i class="far fa-trash-alt source" aria-hidden="true"></i>',
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
$config['n___12400'] = array(4250,6182,10648);
$config['e___12400'] = array(
    4250 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,12149,10593,4593),
    ),
    6182 => array(
        'm_icon' => '<i class="far fa-trash-alt idea" aria-hidden="true"></i>',
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
$config['n___4536'] = array(1326,12891,12864,13207,4430,6206,1278,13296,12897,12274,4758);
$config['e___4536'] = array(
    1326 => array(
        'm_icon' => '<i class="fad fa-browser" aria-hidden="true"></i>',
        'm_name' => 'DOMAIN',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    12891 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT PLATFORM',
        'm_desc' => '',
        'm_parents' => array(12864,4536),
    ),
    12864 => array(
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT SOURCES',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    13207 => array(
        'm_icon' => '<i class="fas fa-medal source"></i>',
        'm_name' => 'LEADERBOARD',
        'm_desc' => '',
        'm_parents' => array(10876,12500,11035,10939,4527,4536),
    ),
    4430 => array(
        'm_icon' => '<i class="fad fa-users source" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMBERS',
        'm_desc' => '',
        'm_parents' => array(13451,13438,13202,4364,4536,1278,10573),
    ),
    6206 => array(
        'm_icon' => '<i class="fas fa-table source" aria-hidden="true"></i>',
        'm_name' => 'MENCH SOURCES',
        'm_desc' => '',
        'm_parents' => array(6212,4527,7735,4536),
    ),
    1278 => array(
        'm_icon' => '<i class="fad fa-users source" aria-hidden="true"></i>',
        'm_name' => 'PEOPLE',
        'm_desc' => '',
        'm_parents' => array(4536),
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
        'm_parents' => array(13298,13355,13369,13291,4536,12467,12228,4527),
    ),
    4758 => array(
        'm_icon' => '<i class="fas fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE SETTINGS',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
);

//IDEA TYPE COMPLETE IF EMPTY:
$config['n___12330'] = array(6677,6914,6907);
$config['e___12330'] = array(
    6677 => array(
        'm_icon' => '<i class="fas fa-eye idea" aria-hidden="true"></i>',
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

//DISCOVER UNLOCKS:
$config['n___12327'] = array(7485,7486,6997);
$config['e___12327'] = array(
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//DISCOVER EXPANSIONS:
$config['n___12326'] = array(12336,12334,6140);
$config['e___12326'] = array(
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
        'm_name' => 'ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open discover" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
);

//AVATARS SUPER:
$config['n___12279'] = array(12280,12281,12282,12286,12287,12288,12308,12309,12310,12234,12233,10965,12236,12235,10979,12295,12294,12293,12296,12297,12298,12300,12301,12299,12237,12238,10978,12314,12315,12316,12240,12239,10963,12241,12242,12207,12244,12243,10966,12245,12246,10976,12248,12247,10962,12249,12250,10975,12252,12251,10982,12253,12254,10970,12302,12303,12304,12256,12255,10972,12306,12307,12305,12257,12258,10969,12312,12313,12311,12260,12259,10960,12277,12276,12278,12439,12262,10981,12264,12263,10968,12265,12266,10974,12290,12291,12292,12268,12267,12206,12269,12270,10958,12285,12284,12283,12272,12271,12231);
$config['e___12279'] = array(
    12280 => array(
        'm_icon' => '<i class="fas fa-alicorn source"></i>',
        'm_name' => 'ALICORN BOLD',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12281 => array(
        'm_icon' => '<i class="far fa-alicorn source"></i>',
        'm_name' => 'ALICORN LIGHT',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12282 => array(
        'm_icon' => '<i class="fad fa-alicorn source"></i>',
        'm_name' => 'ALICORN MIX',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
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
        'm_parents' => array(12700,12279),
    ),
    12307 => array(
        'm_icon' => '<i class="far fa-pegasus source"></i>',
        'm_name' => 'PEGASUS LIGHT',
        'm_desc' => '',
        'm_parents' => array(12700,12279),
    ),
    12305 => array(
        'm_icon' => '<i class="fad fa-pegasus source" aria-hidden="true"></i>',
        'm_name' => 'PEGASUS MIX',
        'm_desc' => '',
        'm_parents' => array(12700,12279),
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
$config['n___12274'] = array(4251);
$config['e___12274'] = array(
    4251 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ADDED',
        'm_desc' => '',
        'm_parents' => array(4527,12274,12401,12149,12141,10593,4593),
    ),
);

//IDEAS:
$config['n___12273'] = array(7545,4983,4231);
$config['e___12273'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(6159,13421,13298,13291,12273,12197,11018,11089,7551,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(13407,11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,10593,4986,4603,4593,4485),
    ),
);

//DISCOVER COMPLETION:
$config['n___12229'] = array(7492,6157,7489,4559,12117,6144,7485,7486,6997);
$config['e___12229'] = array(
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye discover"></i>',
        'm_name' => 'SOURCE DISCOVER MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="fas fa-cloud-upload discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//DISCOVER PROGRESS:
$config['n___12227'] = array(4235,12336,12334,7492,6140,7495,6157,7489,4559,12117,6144,7485,7486,6997);
$config['e___12227'] = array(
    4235 => array(
        'm_icon' => '<i class="fas fa-plus discover" aria-hidden="true"></i>',
        'm_name' => 'ADD TO MY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone discover" aria-hidden="true"></i>',
        'm_name' => 'RECOMMENDED DISCOVERY',
        'm_desc' => '',
        'm_parents' => array(12969,12227,4755,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye discover"></i>',
        'm_name' => 'SOURCE DISCOVER MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="fas fa-cloud-upload discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//DISCOVER TYPE COIN AWARD:
$config['n___12141'] = array(4983,4251,6157,7489,4559,12117,6144,7485,7486,6997);
$config['e___12141'] = array(
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(13407,11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ADDED',
        'm_desc' => '',
        'm_parents' => array(4527,12274,12401,12149,12141,10593,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye discover"></i>',
        'm_name' => 'SOURCE DISCOVER MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="fas fa-cloud-upload discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//IDEA STATUS FEATURED:
$config['n___12138'] = array(12137);
$config['e___12138'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA FEATURED',
        'm_desc' => '',
        'm_parents' => array(13420,10648,12138,7356,7355,4737),
    ),
);

//MENCH TEXT INPUTS:
$config['n___12112'] = array(4358,4356,4736,4535,6197,4739,4735);
$config['e___12112'] = array(
    4358 => array(
        'm_icon' => '<i class="fas fa-comment-alt-check idea" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER MARKS',
        'm_desc' => '',
        'm_parents' => array(13408,12700,12112,10663,6103,6410),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION (SECONDS)',
        'm_desc' => '',
        'm_parents' => array(10986,13295,6287,12741,11047,7274,6404,12112,12420,10650,6201),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TITLE',
        'm_desc' => '',
        'm_parents' => array(13407,13294,12994,6404,10990,12112,10644,6201),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'MAP',
        'm_desc' => '',
        'm_parents' => array(10939,12893,11035,12761,12112,2738),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TITLE',
        'm_desc' => '',
        'm_parents' => array(13428,13410,13296,13025,6404,12112,12232,10646,5000,4998,4999,6206),
    ),
    4739 => array(
        'm_icon' => '<i class="fas fa-temperature-up idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(6103,12112,6402),
    ),
    4735 => array(
        'm_icon' => '<i class="fas fa-temperature-down idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(6103,12112,6402),
    ),
);

//MENCH DROPDOWN MENUS:
$config['n___12079'] = array(4486,4737,7585,10869,12500);
$config['e___12079'] = array(
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => '',
        'm_parents' => array(13442,13408,12700,11054,12079,10662,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13408,13295,11054,10990,12079,6204,6226,6160,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(13408,13295,11054,12079,6204,10651,6160,6194,4527,6201),
    ),
    10869 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'TOPICS OF INTEREST',
        'm_desc' => '',
        'm_parents' => array(12079,6225,6122,7305,4527),
    ),
    12500 => array(
        'm_icon' => '<i class="fas fa-ellipsis-v" aria-hidden="true"></i>',
        'm_name' => 'VERTICAL MENU',
        'm_desc' => '',
        'm_parents' => array(13356,12079,12823,4527),
    ),
);

//SOURCE TABS:
$config['n___11089'] = array(6225,11030,11029,12419,7545,10573,12969,12896);
$config['e___11089'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => '',
        'm_parents' => array(13425,12574,11089,11035,4527),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PROFILE',
        'm_desc' => '',
        'm_parents' => array(7545,13411,13004,12574,11089,11028),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => '',
        'm_parents' => array(13004,12574,12571,11089,11028),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(11089,13304,13291,11018,12359,5967,10593,4986,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(6159,13421,13298,13291,12273,12197,11018,11089,7551,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(10876,11035,13424,4535,11054,4527,10984,11018,11035,11089,4593,7551,4485),
    ),
    12969 => array(
        'm_icon' => '<i class="fas fa-compass discover" aria-hidden="true"></i>',
        'm_name' => 'MY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13424,6205,13210,12228,11035,11018,12677,12701,4527,11089),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark discover"></i>',
        'm_name' => 'SAVED IDEAS',
        'm_desc' => '',
        'm_parents' => array(13424,13210,12677,13289,4485,12701,7551,11089,11018,11035,4755,4593),
    ),
);

//DISCOVER ALL CONNECTIONS:
$config['n___11081'] = array(4429,4368,4364,4371,4369,4593,4366);
$config['e___11081'] = array(
    4429 => array(
        'm_icon' => '<i class="fas fa-triangle rotate180" aria-hidden="true"></i>',
        'm_name' => 'DOWN',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-triangle rotate270" aria-hidden="true"></i>',
        'm_name' => 'LEFT',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,4341),
    ),
    4364 => array(
        'm_icon' => '<i class="fas fa-user" aria-hidden="true"></i>',
        'm_name' => 'MEMBER',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6194,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="far fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'REFERENCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-triangle rotate90" aria-hidden="true"></i>',
        'm_name' => 'RIGHT',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6194,4527,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-triangle" aria-hidden="true"></i>',
        'm_name' => 'UP',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,4341),
    ),
);

//SOURCE LINK FILE EXTENSIONS:
$config['n___11080'] = array(4259,4261,4260,4256,4258);
$config['e___11080'] = array(
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
        'm_icon' => '<i class="fas fa-external-link source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => 'htm|html',
        'm_parents' => array(13433,12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'mp4|m4v|m4p|avi|mov|flv|f4v|f4p|f4a|f4b|wmv|webm|mkv|vob|ogv|ogg|3gp|mpg|mpeg|m2v',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
);

//SOURCE LINK UPLOAD FILE:
$config['n___11059'] = array(4259,4261,4260,4258);
$config['e___11059'] = array(
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
$config['n___6404'] = array(12678,7274,12904,13210,11057,11056,12331,12427,3288,13405,12176,4356,4485,4736,13406,11064,11065,13014,11063,13005,11060,11079,11066,12088,13206,11986,12232,6197,12565,12568);
$config['e___6404'] = array(
    12678 => array(
        'm_icon' => '',
        'm_name' => 'ALGOLIA SEARCH ENABLED (0 OR 1)',
        'm_desc' => '1',
        'm_parents' => array(3323,6404),
    ),
    7274 => array(
        'm_icon' => '<i class="fas fa-clock mench-spin" aria-hidden="true"></i>',
        'm_name' => 'CRON JOBS',
        'm_desc' => '/usr/bin/php /var/www/platform/index.php e plugin',
        'm_parents' => array(6404,6287,6403,12999),
    ),
    12904 => array(
        'm_icon' => '<i class="fad fa-book" aria-hidden="true"></i>',
        'm_name' => 'DEFAULT BOOK COVER',
        'm_desc' => '//s3foundation.s3-us-west-2.amazonaws.com/4981b7cace14d274a4865e2a416b372b.jpg',
        'm_parents' => array(6404,1,7524),
    ),
    13210 => array(
        'm_icon' => '<i class="fas fa-location-arrow discover"></i>',
        'm_name' => 'DISCOVER HOME',
        'm_desc' => 'Social Ideation Network',
        'm_parents' => array(6404,11035,6205),
    ),
    11057 => array(
        'm_icon' => '',
        'm_name' => 'DISCOVER MARKS MAX',
        'm_desc' => '89',
        'm_parents' => array(6404,4358),
    ),
    11056 => array(
        'm_icon' => '',
        'm_name' => 'DISCOVER MARKS MIN',
        'm_desc' => '-89',
        'm_parents' => array(6404,4358),
    ),
    12331 => array(
        'm_icon' => '',
        'm_name' => 'DISCOVER MIN TIME SHOW',
        'm_desc' => '120',
        'm_parents' => array(6404),
    ),
    12427 => array(
        'm_icon' => '',
        'm_name' => 'DISCOVER TIME MINIMUM SECONDS',
        'm_desc' => '3',
        'm_parents' => array(6404,4356),
    ),
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => 'support@mench.com',
        'm_parents' => array(6404,13023,4269,12103,6225,4755),
    ),
    13405 => array(
        'm_icon' => '<i class="fas fa-lightbulb-on idea"></i>',
        'm_name' => 'HOME PAGE IDEA',
        'm_desc' => '7766',
        'm_parents' => array(13210,6404),
    ),
    12176 => array(
        'm_icon' => '<i class="fad fa-clock idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DEFAULT TIME SECONDS',
        'm_desc' => '30',
        'm_parents' => array(6404),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION (SECONDS)',
        'm_desc' => '7200',
        'm_parents' => array(10986,13295,6287,12741,11047,7274,6404,12112,12420,10650,6201),
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
        'm_parents' => array(13407,13294,12994,6404,10990,12112,10644,6201),
    ),
    13406 => array(
        'm_icon' => '<i class="fas fa-info-circle idea"></i>',
        'm_name' => 'INTRODUCTION IDEA ID',
        'm_desc' => '13804',
        'm_parents' => array(6404),
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
        'm_desc' => 'v1.4591',
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
        'm_parents' => array(13446,6404),
    ),
    11986 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE LIST VISIBLE',
        'm_desc' => '5',
        'm_parents' => array(13446,6404),
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
        'm_parents' => array(13428,13410,13296,13025,6404,12112,12232,10646,5000,4998,4999,6206),
    ),
    12565 => array(
        'm_icon' => '',
        'm_name' => 'WEIGHT ALGORITHM RATE',
        'm_desc' => '89',
        'm_parents' => array(12569,6404),
    ),
    12568 => array(
        'm_icon' => '',
        'm_name' => 'WEIGHT ALGORITHM TRANSACTIONS',
        'm_desc' => '1',
        'm_parents' => array(12569,6404),
    ),
);

//MENCH MEMORY JAVASCRIPT:
$config['n___11054'] = array(4486,4983,4737,7356,7355,7585,2738,6404,6201,12687,10573,4592,6177,7357,6186);
$config['e___11054'] = array(
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => '',
        'm_parents' => array(13442,13408,12700,11054,12079,10662,4527),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(13407,11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13408,13295,11054,10990,12079,6204,6226,6160,6194,6201,4527),
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
        'm_parents' => array(13408,13295,11054,12079,6204,10651,6160,6194,4527,6201),
    ),
    2738 => array(
        'm_icon' => '<img src="https://mench.com/img/mench.png" class="mench-spin no-radius">',
        'm_name' => 'MENCH',
        'm_desc' => '',
        'm_parents' => array(13436,7305,12891,12497,2,11054,12041,4527,1,7312),
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
        'm_parents' => array(6212,11054,4527,7735,4535),
    ),
    12687 => array(
        'm_icon' => '<i class="fad fa-comments-alt" aria-hidden="true"></i>',
        'm_name' => 'MENCH MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6403,11054,4527),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(10876,11035,13424,4535,11054,4527,10984,11018,11035,11089,4593,7551,4485),
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
        'm_parents' => array(12766,11054,6204,5003,6160,6194,6206,4527),
    ),
    7357 => array(
        'm_icon' => '<i class="far fa-globe source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(12572,11054,4527),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,7304,4527,6194,4341),
    ),
);

//IDEA MENU:
$config['n___11047'] = array(4356,7276,7264,11049,12733,4341,7279);
$config['e___11047'] = array(
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION (SECONDS)',
        'm_desc' => '/e/plugin/4356?i__id=',
        'm_parents' => array(10986,13295,6287,12741,11047,7274,6404,12112,12420,10650,6201),
    ),
    7276 => array(
        'm_icon' => '<i class="fad fa-sync idea mench-spin" aria-hidden="true"></i>',
        'm_name' => 'IDEA SYNC EXTRA INSIGHTS',
        'm_desc' => '/e/plugin/7276?i__id=',
        'm_parents' => array(6287,12741,11047,7286,7274),
    ),
    7264 => array(
        'm_icon' => '<i class="fad fa-sitemap idea"></i>',
        'm_name' => 'IDEA BIRDS EYE MARKS',
        'm_desc' => '/e/plugin/7264?i__id=',
        'm_parents' => array(11047,6287),
    ),
    11049 => array(
        'm_icon' => '<i class="fad fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA REVIEW JSON',
        'm_desc' => '/e/plugin/11049?i__id=',
        'm_parents' => array(12741,6287,11047),
    ),
    12733 => array(
        'm_icon' => '<i class="fad fa-code discover"></i>',
        'm_name' => 'DISCOVER REVIEW JSON',
        'm_desc' => '/e/plugin/12733?i__id=',
        'm_parents' => array(11047,12741,12701,6287),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas discover" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '/x?any_i__id=',
        'm_parents' => array(6212,12887,11047,12707,10876,12588,11035,4527,7735),
    ),
    7279 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SYNC SEARCH INDEX',
        'm_desc' => '/e/plugin/7279?obj=4535&object__id=',
        'm_parents' => array(6287,12741,12887,11047,3323,7287),
    ),
);

//MENCH NAVIGATION:
$config['n___11035'] = array(6225,4235,13401,12211,13210,12750,13427,12707,12991,13207,11068,4535,4341,12969,10573,10573,6287,12896,7256,4269,4536,4997,12275,13007,10957,7540);
$config['e___11035'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => 'Manage avatar, superpowers, subscription & name',
        'm_parents' => array(13425,12574,11089,11035,4527),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-plus discover" aria-hidden="true"></i>',
        'm_name' => 'ADD TO MY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    13401 => array(
        'm_icon' => '<i class="fas fa-search-minus"></i>',
        'm_name' => 'CLOSE SEARCH',
        'm_desc' => '',
        'm_parents' => array(11035,7256),
    ),
    12211 => array(
        'm_icon' => '<i class="fas fa-step-forward discover" aria-hidden="true"></i>',
        'm_name' => 'COMPLETE & NEXT',
        'm_desc' => '',
        'm_parents' => array(13289,11035),
    ),
    13210 => array(
        'm_icon' => '<i class="fas fa-location-arrow discover"></i>',
        'm_name' => 'DISCOVER HOME',
        'm_desc' => '',
        'm_parents' => array(6404,11035,6205),
    ),
    12750 => array(
        'm_icon' => '<i class="fas fa-step-forward discover"></i>',
        'm_name' => 'DISCOVER THIS IDEA',
        'm_desc' => '',
        'm_parents' => array(13295,11035),
    ),
    13427 => array(
        'm_icon' => '<i class="fas fa-star idea"></i>',
        'm_name' => 'FEATURED IDEAS',
        'm_desc' => '',
        'm_parents' => array(11035,13405),
    ),
    12707 => array(
        'm_icon' => '<i class="far fa-filter" aria-hidden="true"></i>',
        'm_name' => 'FILTER TRANSACTIONS',
        'm_desc' => '',
        'm_parents' => array(11035,12701),
    ),
    12991 => array(
        'm_icon' => '<i class="fas fa-step-backward discover" aria-hidden="true"></i>',
        'm_name' => 'GO BACK',
        'm_desc' => '',
        'm_parents' => array(13289,11035),
    ),
    13207 => array(
        'm_icon' => '<i class="fas fa-medal source"></i>',
        'm_name' => 'LEADERBOARD',
        'm_desc' => '',
        'm_parents' => array(10876,12500,11035,10939,4527,4536),
    ),
    11068 => array(
        'm_icon' => '<i class="far fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'MAGIC LOGIN',
        'm_desc' => '',
        'm_parents' => array(11035,11065),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'MAP',
        'm_desc' => '',
        'm_parents' => array(10939,12893,11035,12761,12112,2738),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas discover" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(6212,12887,11047,12707,10876,12588,11035,4527,7735),
    ),
    12969 => array(
        'm_icon' => '<i class="fas fa-compass discover" aria-hidden="true"></i>',
        'm_name' => 'MY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13424,6205,13210,12228,11035,11018,12677,12701,4527,11089),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(10876,11035,13424,4535,11054,4527,10984,11018,11035,11089,4593,7551,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(10876,11035,13424,4535,11054,4527,10984,11018,11035,11089,4593,7551,4485),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => '',
        'm_parents' => array(13297,6405,6194,12699,12500,10876,11035,4527),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark discover"></i>',
        'm_name' => 'SAVED IDEAS',
        'm_desc' => '',
        'm_parents' => array(13424,13210,12677,13289,4485,12701,7551,11089,11018,11035,4755,4593),
    ),
    7256 => array(
        'm_icon' => '<i class="fas fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCH MENCH',
        'm_desc' => '',
        'm_parents' => array(13356,12701,12497,11035,3323),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in-alt" aria-hidden="true"></i>',
        'm_name' => 'SIGN IN/UP',
        'm_desc' => 'Mench is a non-profit Social Ideation Network on a mission to expand your potential by discovering ideas that matter.',
        'm_parents' => array(12497,10876,4527,11035),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(10939,12489,11035,12893,12761,4527,2738),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-list source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LIST EDITOR',
        'm_desc' => '',
        'm_parents' => array(13429,11035,12703,12590,4527),
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
        'm_parents' => array(13429,11035,4593),
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
$config['n___11018'] = array(11020,4983,6255,12969,12419,4601,7545,12896,10573,12589,11047);
$config['e___11018'] = array(
    11020 => array(
        'm_icon' => '<i class="fas fa-circle idea"></i>',
        'm_name' => 'NEXT IDEAS',
        'm_desc' => '',
        'm_parents' => array(12675,12413,11018),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(13407,11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13355,13369,13291,12701,6205,12677,10939,11018,12467,12228,4527),
    ),
    12969 => array(
        'm_icon' => '<i class="fas fa-compass discover" aria-hidden="true"></i>',
        'm_name' => 'MY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13424,6205,13210,12228,11035,11018,12677,12701,4527,11089),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(11089,13304,13291,11018,12359,5967,10593,4986,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,10593,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(6159,13421,13298,13291,12273,12197,11018,11089,7551,4593,4485),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark discover"></i>',
        'm_name' => 'SAVED IDEAS',
        'm_desc' => '',
        'm_parents' => array(13424,13210,12677,13289,4485,12701,7551,11089,11018,11035,4755,4593),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(10876,11035,13424,4535,11054,4527,10984,11018,11035,11089,4593,7551,4485),
    ),
    12589 => array(
        'm_icon' => '<i class="fas fa-list idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR',
        'm_desc' => '',
        'm_parents' => array(13403,11018,4527,12590),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA MENU',
        'm_desc' => '',
        'm_parents' => array(12700,11018,4527,11040),
    ),
);

//IDEA PREVIOUS SECTION:
$config['n___10990'] = array(4737,4736,11019);
$config['e___10990'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13408,13295,11054,10990,12079,6204,6226,6160,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TITLE',
        'm_desc' => '',
        'm_parents' => array(13407,13294,12994,6404,10990,12112,10644,6201),
    ),
    11019 => array(
        'm_icon' => '<i class="fas fa-circle idea"></i>',
        'm_name' => 'PREVIOUS IDEAS',
        'm_desc' => '',
        'm_parents' => array(13294,12413,10990),
    ),
);

//SUPERPOWERS:
$config['n___10957'] = array(10939,13354,12673,13403,10984,10986,13420,12700,10967,13402,12706,12703,13422,13421,12699,13404,12701,12705);
$config['e___10957'] = array(
    10939 => array(
        'm_icon' => '<i class="fas fa-map-marker-alt idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA MAPPING',
        'm_desc' => 'Add & Organize Ideas',
        'm_parents' => array(10957),
    ),
    13354 => array(
        'm_icon' => '<i class="fas fa-money-check-edit idea"></i>',
        'm_name' => 'INLINE EDITING',
        'm_desc' => 'Rename Idea Titles Inline',
        'm_parents' => array(10957),
    ),
    12673 => array(
        'm_icon' => '<i class="fas fa-ellipsis-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TOOLBAR',
        'm_desc' => 'Edit next & previous ideas on the fly',
        'm_parents' => array(10957),
    ),
    13403 => array(
        'm_icon' => '<i class="fas fa-list idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDIT',
        'm_desc' => 'Mass Idea Edit',
        'm_parents' => array(10957),
    ),
    10984 => array(
        'm_icon' => '<i class="fas fa-walkie-talkie idea" aria-hidden="true"></i>',
        'm_name' => 'WALKIE TALKIE',
        'm_desc' => 'Collaborative Publishing Powers',
        'm_parents' => array(10957),
    ),
    10986 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TIMING',
        'm_desc' => 'Advance Source Tools',
        'm_parents' => array(10957),
    ),
    13420 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA FEATURING',
        'm_desc' => 'Feature Ideas',
        'm_parents' => array(10957),
    ),
    12700 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'ADVANCE MAPPING',
        'm_desc' => 'Advance Idea Linking',
        'm_parents' => array(10957),
    ),
    10967 => array(
        'm_icon' => '<i class="fas fa-map-marker-alt source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCING',
        'm_desc' => 'Add & Organize Ideas',
        'm_parents' => array(10957),
    ),
    13402 => array(
        'm_icon' => '<i class="fas fa-money-check-edit source"></i>',
        'm_name' => 'INLINE SOURCING',
        'm_desc' => 'Rename Source Titles Inline',
        'm_parents' => array(10957),
    ),
    12706 => array(
        'm_icon' => '<i class="fas fa-ellipsis-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TOOLBAR',
        'm_desc' => 'List Parent Sources',
        'm_parents' => array(10957),
    ),
    12703 => array(
        'm_icon' => '<i class="fas fa-list source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LIST EDIT',
        'm_desc' => 'Mass Source Edit',
        'm_parents' => array(10957),
    ),
    13422 => array(
        'm_icon' => '<i class="fas fa-link source" aria-hidden="true"></i>',
        'm_name' => 'ADVANCE SOURCING',
        'm_desc' => '',
        'm_parents' => array(10957),
    ),
    13421 => array(
        'm_icon' => '<i class="fas fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATIONS',
        'm_desc' => 'Manage Player Certificates',
        'm_parents' => array(10957),
    ),
    12699 => array(
        'm_icon' => '<i class="fas fa-plug source" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => 'Access Mench Plugins',
        'm_parents' => array(10957),
    ),
    13404 => array(
        'm_icon' => '<i class="fas fa-fast-forward discover"></i>',
        'm_name' => 'DISCOVER SKIP AHEAD',
        'm_desc' => 'Allows Players to Navigate All Ideas',
        'm_parents' => array(10957),
    ),
    12701 => array(
        'm_icon' => '<i class="fas fa-glasses discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER GLASSES',
        'm_desc' => 'Read info from all players',
        'm_parents' => array(10957),
    ),
    12705 => array(
        'm_icon' => '<i class="fas fa-list discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER LIST EDIT',
        'm_desc' => 'Mass Edit Reads',
        'm_parents' => array(10957),
    ),
);

//AVATARS BASIC:
$config['n___10956'] = array(12286,12287,12288,12234,12233,10965,12236,12235,10979,12295,12294,12293,12300,12301,12299,12237,12238,10978,12314,12315,12316,12240,12239,10963,12241,12242,12207,12244,12243,10966,12245,12246,10976,12248,12247,10962,12249,12250,10975,12252,12251,10982,12253,12254,10970,12256,12255,10972,12257,12258,10969,12260,12259,10960,12439,12262,10981,12264,12263,10968,12265,12266,10974,12268,12267,12206,12269,12270,10958,12272,12271,12231);
$config['e___10956'] = array(
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
$config['n___2738'] = array(4536,4535,6205);
$config['e___2738'] = array(
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(10939,12489,11035,12893,12761,4527,2738),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'MAP',
        'm_desc' => '',
        'm_parents' => array(10939,12893,11035,12761,12112,2738),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER',
        'm_desc' => '',
        'm_parents' => array(12893,2738),
    ),
);

//THE FIVE LINKS:
$config['n___10692'] = array(4366,4429,4368,4369,4371);
$config['e___10692'] = array(
    4366 => array(
        'm_icon' => '<i class="fas fa-triangle" aria-hidden="true"></i>',
        'm_name' => 'UP',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-triangle rotate180" aria-hidden="true"></i>',
        'm_name' => 'DOWN',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-triangle rotate270" aria-hidden="true"></i>',
        'm_name' => 'LEFT',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-triangle rotate90" aria-hidden="true"></i>',
        'm_name' => 'RIGHT',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="far fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'REFERENCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,4341),
    ),
);

//MENCH MEMORY:
$config['n___4527'] = array(6225,10956,12279,12588,11081,7704,5967,12229,12326,12446,6255,12994,12227,7304,7360,7364,7359,13291,13300,13298,13304,10593,12141,12327,6150,13037,3000,12893,13369,4229,12842,4486,12840,12589,11047,6159,4485,7551,4986,12359,4603,12012,6193,10990,12273,4983,4737,7356,12138,7355,12400,11018,12675,12677,12420,13408,12413,7585,13022,12330,7309,7712,12883,12884,12955,7751,13207,2738,12467,6404,12079,6201,13202,4341,4527,11054,12687,11035,13355,13438,6206,12112,10876,6212,7277,12969,10573,12741,6287,12577,4755,13439,13365,13414,12761,13023,4269,6204,13413,4536,4251,13424,12571,13004,12574,13425,11080,12822,4592,12403,11059,4537,12524,4997,12887,6172,6194,12274,6177,7358,12575,7357,12401,11089,10957,12968,12523,10692,10869,6103,6186,4593,13442,6146,12500);
$config['e___4527'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => '',
        'm_parents' => array(13425,12574,11089,11035,4527),
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
    11081 => array(
        'm_icon' => '<i class="far fa-bezier-curve discover"></i>',
        'm_name' => 'DISCOVER ALL CONNECTIONS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    7704 => array(
        'm_icon' => '<i class="far fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER ANSWER',
        'm_desc' => '',
        'm_parents' => array(13028,12228,4527),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER CARBON COPY',
        'm_desc' => '',
        'm_parents' => array(4527,7569,4755,4593),
    ),
    12229 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER COMPLETION',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    12326 => array(
        'm_icon' => '<i class="fad fa-expand discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER EXPANSIONS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    12446 => array(
        'm_icon' => '<i class="fad fa-question-circle discover"></i>',
        'm_name' => 'DISCOVER ICON LEGEND',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13355,13369,13291,12701,6205,12677,10939,11018,12467,12228,4527),
    ),
    12994 => array(
        'm_icon' => '<i class="fad fa-crop-alt discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4527,6205),
    ),
    12227 => array(
        'm_icon' => '<i class="fas fa-walking discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER PROGRESS',
        'm_desc' => '',
        'm_parents' => array(12228,4527),
    ),
    7304 => array(
        'm_icon' => '<i class="far fa-chart-bar discover"></i>',
        'm_name' => 'DISCOVER STATS',
        'm_desc' => '',
        'm_parents' => array(6771,10888,4527),
    ),
    7360 => array(
        'm_icon' => '<i class="far fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    7364 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER STATUS INCOMPLETE',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    7359 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    13291 => array(
        'm_icon' => '<i class="fas fa-bars discover"></i>',
        'm_name' => 'DISCOVER TABS',
        'm_desc' => '',
        'm_parents' => array(13299,4527,12994),
    ),
    13300 => array(
        'm_icon' => '<i class="fas fa-badge-check discover"></i>',
        'm_name' => 'DISCOVER TABS DEFAULT SELECTED',
        'm_desc' => '',
        'm_parents' => array(4527,13299),
    ),
    13298 => array(
        'm_icon' => '<i class="fad fa-eye-slash discover"></i>',
        'm_name' => 'DISCOVER TABS HIDE IF ZERO',
        'm_desc' => '',
        'm_parents' => array(4527,13299),
    ),
    13304 => array(
        'm_icon' => '<i class="fas fa-user-check discover"></i>',
        'm_name' => 'DISCOVER TABS SHOW IF LOGGED-IN',
        'm_desc' => '',
        'm_parents' => array(4527,13299),
    ),
    10593 => array(
        'm_icon' => '<i class="fas fa-file-alt" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER TYPE ADD CONTENT',
        'm_desc' => '',
        'm_parents' => array(12144,4527),
    ),
    12141 => array(
        'm_icon' => '<i class="fad fa-coin" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER TYPE COIN AWARD',
        'm_desc' => '',
        'm_parents' => array(12144,4527),
    ),
    12327 => array(
        'm_icon' => '<i class="fas fa-lock-open discover"></i>',
        'm_name' => 'DISCOVER UNLOCKS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    6150 => array(
        'm_icon' => '<i class="far fa-bookmark discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVERY REMOVED',
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
        'm_icon' => '<i class="fas fa-badge-check source"></i>',
        'm_name' => 'EXPERT CONTENT',
        'm_desc' => '',
        'm_parents' => array(6159,4251,13428,13365,12864,13207,4527),
    ),
    12893 => array(
        'm_icon' => '<i class="fas fa-ellipsis-h"></i>',
        'm_name' => 'HORIZONTAL MENU',
        'm_desc' => '',
        'm_parents' => array(13356,6403,4527),
    ),
    13369 => array(
        'm_icon' => '<i class="fas fa-book idea"></i>',
        'm_name' => 'IDEA COVER UI',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
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
        'm_parents' => array(13442,13408,12700,11054,12079,10662,4527),
    ),
    12840 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK TWO-WAYS',
        'm_desc' => '',
        'm_parents' => array(4527,12841),
    ),
    12589 => array(
        'm_icon' => '<i class="fas fa-list idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR',
        'm_desc' => '',
        'm_parents' => array(13403,11018,4527,12590),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA MENU',
        'm_desc' => '',
        'm_parents' => array(12700,11018,4527,11040),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA METADATA',
        'm_desc' => '',
        'm_parents' => array(7277,4527,6212,11049,6201),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES',
        'm_desc' => '',
        'm_parents' => array(6404,4535,4527),
    ),
    7551 => array(
        'm_icon' => '<i class="fas fa-at source" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES 1X SOURCE REQUIRED',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    4986 => array(
        'm_icon' => '<i class="fad fa-at" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES 2X SOURCES ALLOWED',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    12359 => array(
        'm_icon' => '<i class="fad fa-upload"></i>',
        'm_name' => 'IDEA NOTES FILE UPLOADING ALLOWED',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    4603 => array(
        'm_icon' => '<i class="fas fa-sort" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES SORTING ALLOWED',
        'm_desc' => '',
        'm_parents' => array(4527,10889),
    ),
    12012 => array(
        'm_icon' => '<i class="far fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES STATUSES',
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
        'm_parents' => array(13298,13355,13369,13291,13410,4983,4535,12571,12467,12228,4527),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(13407,11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13408,13295,11054,10990,12079,6204,6226,6160,6194,6201,4527),
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
        'm_name' => 'IDEA TABS NO MANUAL ADD / HIDE IF ZERO',
        'm_desc' => '',
        'm_parents' => array(4527,12676),
    ),
    12420 => array(
        'm_icon' => '<i class="far fa-user-circle" aria-hidden="true"></i>',
        'm_name' => 'IDEA TEXT INPUT SHOW ICON',
        'm_desc' => '',
        'm_parents' => array(7254,4527),
    ),
    13408 => array(
        'm_icon' => '<i class="fas fa-ellipsis-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TOOLBAR',
        'm_desc' => '',
        'm_parents' => array(4527,12673,13407),
    ),
    12413 => array(
        'm_icon' => '<i class="fas fa-sitemap idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TREE',
        'm_desc' => '',
        'm_parents' => array(13408,6768,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(13408,13295,11054,12079,6204,10651,6160,6194,4527,6201),
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
        'm_icon' => '<i class="fas fa-cloud-upload idea"></i>',
        'm_name' => 'IDEA TYPE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    13207 => array(
        'm_icon' => '<i class="fas fa-medal source"></i>',
        'm_name' => 'LEADERBOARD',
        'm_desc' => '',
        'm_parents' => array(10876,12500,11035,10939,4527,4536),
    ),
    2738 => array(
        'm_icon' => '<img src="https://mench.com/img/mench.png" class="mench-spin no-radius">',
        'm_name' => 'MENCH',
        'm_desc' => '',
        'm_parents' => array(13436,7305,12891,12497,2,11054,12041,4527,1,7312),
    ),
    12467 => array(
        'm_icon' => '<img src="https://mench.com/img/mench.png" class="mench-spin no-radius">',
        'm_name' => 'MENCH COINS',
        'm_desc' => '',
        'm_parents' => array(13296,7305,4527),
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
        'm_parents' => array(6212,11054,4527,7735,4535),
    ),
    13202 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MENCH IDEATORS',
        'm_desc' => '',
        'm_parents' => array(13451,6159,4251,13365,4527,13207),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas discover" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(6212,12887,11047,12707,10876,12588,11035,4527,7735),
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
    13355 => array(
        'm_icon' => '<i class="fas fa-chart-network"></i>',
        'm_name' => 'MENCH OBJECTS',
        'm_desc' => '',
        'm_parents' => array(4527,7305),
    ),
    13438 => array(
        'm_icon' => '<i class="fas fa-map-marker-check discover"></i>',
        'm_name' => 'MENCH READERS',
        'm_desc' => '',
        'm_parents' => array(13451,13207,4527,13439),
    ),
    6206 => array(
        'm_icon' => '<i class="fas fa-table source" aria-hidden="true"></i>',
        'm_name' => 'MENCH SOURCES',
        'm_desc' => '',
        'm_parents' => array(6212,4527,7735,4536),
    ),
    12112 => array(
        'm_icon' => '<i class="fas fa-text" aria-hidden="true"></i>',
        'm_name' => 'MENCH TEXT INPUTS',
        'm_desc' => '',
        'm_parents' => array(12829,6403,4527),
    ),
    10876 => array(
        'm_icon' => '<i class="fas fa-browser"></i>',
        'm_name' => 'MENCH URL',
        'm_desc' => '',
        'm_parents' => array(7305,4527,1326),
    ),
    6212 => array(
        'm_icon' => '<i class="fas fa-subscript" aria-hidden="true"></i>',
        'm_name' => 'MENCH VARIABLES',
        'm_desc' => '',
        'm_parents' => array(6403,4527),
    ),
    7277 => array(
        'm_icon' => '',
        'm_name' => 'METADATA CLEAN VARIABLES',
        'm_desc' => '',
        'm_parents' => array(4527,6287,12741,7287,7274),
    ),
    12969 => array(
        'm_icon' => '<i class="fas fa-compass discover" aria-hidden="true"></i>',
        'm_name' => 'MY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13424,6205,13210,12228,11035,11018,12677,12701,4527,11089),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(10876,11035,13424,4535,11054,4527,10984,11018,11035,11089,4593,7551,4485),
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
        'm_name' => 'PRIVATE TRANSACTION',
        'm_desc' => '',
        'm_parents' => array(12701,4755,6771,4527),
    ),
    13439 => array(
        'm_icon' => '<i class="fas fa-circle discover"></i>',
        'm_name' => 'RANK BY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13446,4527,4758),
    ),
    13365 => array(
        'm_icon' => '<i class="fas fa-circle idea"></i>',
        'm_name' => 'RANK BY IDEAS',
        'm_desc' => '',
        'm_parents' => array(13446,4527,4758),
    ),
    13414 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'REMOVE IDEA',
        'm_desc' => '',
        'm_parents' => array(4527,13369),
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
        'm_parents' => array(13304,13291,4527,13024,7305),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in-alt" aria-hidden="true"></i>',
        'm_name' => 'SIGN IN/UP',
        'm_desc' => '',
        'm_parents' => array(12497,10876,4527,11035),
    ),
    6204 => array(
        'm_icon' => '<i class="fas fa-check" aria-hidden="true"></i>',
        'm_name' => 'SINGLE SELECTABLE',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    13413 => array(
        'm_icon' => '<i class="fas fa-arrows"></i>',
        'm_name' => 'SORT IDEA',
        'm_desc' => '',
        'm_parents' => array(4527,13369),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(10939,12489,11035,12893,12761,4527,2738),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ADDED',
        'm_desc' => '',
        'm_parents' => array(4527,12274,12401,12149,12141,10593,4593),
    ),
    13424 => array(
        'm_icon' => '<i class="fas fa-eye-slash"></i>',
        'm_name' => 'SOURCE LAYOUT HIDE IF SOURCE',
        'm_desc' => '',
        'm_parents' => array(4527,12573),
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
        'm_parents' => array(13422,4527,12573),
    ),
    12574 => array(
        'm_icon' => '<i class="fad fa-check-double"></i>',
        'm_name' => 'SOURCE LAYOUT SHOW EVEN IF ZERO',
        'm_desc' => '',
        'm_parents' => array(4527,12573),
    ),
    13425 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'SOURCE LAYOUT SHOW IF SOURCE',
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
        'm_parents' => array(13429,11035,12703,12590,4527),
    ),
    12887 => array(
        'm_icon' => '<i class="fas fa-caret-down source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE MENU',
        'm_desc' => '',
        'm_parents' => array(13297,12703,4527,11040),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda source"></i>',
        'm_name' => 'SOURCE METADATA',
        'm_desc' => '',
        'm_parents' => array(7277,4527,6212,6206),
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
        'm_parents' => array(13298,13355,13369,13291,4536,12467,12228,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6194,6206,4527),
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
        'm_name' => 'SYNC ICONS IF MISSING ICON',
        'm_desc' => '',
        'm_parents' => array(12967,4527),
    ),
    10692 => array(
        'm_icon' => '<i class="fas fa-bezier-curve"></i>',
        'm_name' => 'THE FIVE LINKS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    10869 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'TOPICS OF INTEREST',
        'm_desc' => '',
        'm_parents' => array(12079,6225,6122,7305,4527),
    ),
    6103 => array(
        'm_icon' => '<i class="fas fa-lambda"></i>',
        'm_name' => 'TRANSACTION METADATA',
        'm_desc' => '',
        'm_parents' => array(6212,4527,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6194,4527,4341),
    ),
    13442 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE UPDATE',
        'm_desc' => '',
        'm_parents' => array(4527,6212),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle discover" aria-hidden="true"></i>',
        'm_name' => 'UNFINISHED',
        'm_desc' => '',
        'm_parents' => array(12228,4527),
    ),
    12500 => array(
        'm_icon' => '<i class="fas fa-ellipsis-v" aria-hidden="true"></i>',
        'm_name' => 'VERTICAL MENU',
        'm_desc' => '',
        'm_parents' => array(13356,12079,12823,4527),
    ),
);

//DISCOVER TYPE ADD CONTENT:
$config['n___10593'] = array(12419,4554,4556,4555,6563,4570,7702,4549,4551,4550,4548,4552,4553,4250,10679,4983,10644,4601,4231,4251,4259,10657,4261,4260,4255,4258,10646);
$config['e___10593'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(11089,13304,13291,11018,12359,5967,10593,4986,4593,4485),
    ),
    4554 => array(
        'm_icon' => '<i class="discover fad fa-volume-up"></i>',
        'm_name' => 'DISCOVER AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="discover fad fa-file-pdf"></i>',
        'm_name' => 'DISCOVER FILE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="discover fad fa-image"></i>',
        'm_name' => 'DISCOVER IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="discover fad fa-check"></i>',
        'm_name' => 'DISCOVER QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="discover fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER RECEIVED IDEA',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,7569),
    ),
    4549 => array(
        'm_icon' => '<i class="discover fad fa-volume-up"></i>',
        'm_name' => 'DISCOVER SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="discover fad fa-file-pdf"></i>',
        'm_name' => 'DISCOVER SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="discover fad fa-image"></i>',
        'm_name' => 'DISCOVER SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="discover fad fa-video"></i>',
        'm_name' => 'DISCOVER SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4552 => array(
        'm_icon' => '<i class="discover fad fa-align-left"></i>',
        'm_name' => 'DISCOVER TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="discover fad fa-video"></i>',
        'm_name' => 'DISCOVER VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
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
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(13407,11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    10644 => array(
        'm_icon' => '<i class="fad fa-bullseye-arrow idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE TITLE',
        'm_desc' => '',
        'm_parents' => array(10593,4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,10593,4986,4603,4593,4485),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ADDED',
        'm_desc' => '',
        'm_parents' => array(4527,12274,12401,12149,12141,10593,4593),
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
$config['n___4983'] = array(12273);
$config['e___4983'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(13298,13355,13369,13291,13410,4983,4535,12571,12467,12228,4527),
    ),
);

//IDEA TYPE UPLOAD:
$config['n___7751'] = array(7637);
$config['e___7751'] = array(
    7637 => array(
        'm_icon' => '<i class="fas fa-cloud-upload idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPLOAD & NEXT',
        'm_desc' => '',
        'm_parents' => array(13022,12955,12117,7751,7585,6192),
    ),
);

//TRANSACTION METADATA:
$config['n___6103'] = array(4358,4739,4735);
$config['e___6103'] = array(
    4358 => array(
        'm_icon' => '<i class="fas fa-comment-alt-check idea" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER MARKS',
        'm_desc' => 'tr__assessment_points',
        'm_parents' => array(13408,12700,12112,10663,6103,6410),
    ),
    4739 => array(
        'm_icon' => '<i class="fas fa-temperature-up idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX PERCENTAGE',
        'm_desc' => 'tr__conditional_score_max',
        'm_parents' => array(6103,12112,6402),
    ),
    4735 => array(
        'm_icon' => '<i class="fas fa-temperature-down idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN PERCENTAGE',
        'm_desc' => 'tr__conditional_score_min',
        'm_parents' => array(6103,12112,6402),
    ),
);

//MENCH LEDGER:
$config['n___4341'] = array(4367,6186,4362,4593,4364,4372,4371,4370,6103,4366,4429,4368,4369);
$config['e___4341'] = array(
    4367 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION ID',
        'm_desc' => 'x__id',
        'm_parents' => array(4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => 'x__status',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="fas fa-clock" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TIME',
        'm_desc' => 'x__time',
        'm_parents' => array(4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => 'x__type',
        'm_parents' => array(6204,11081,10659,6160,6194,4527,4341),
    ),
    4364 => array(
        'm_icon' => '<i class="fas fa-user" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION MEMBER',
        'm_desc' => 'x__member',
        'm_parents' => array(11081,6160,6194,4341),
    ),
    4372 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION MESSAGE',
        'm_desc' => 'x__message',
        'm_parents' => array(13410,7578,10679,10657,5001,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="far fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION REFERENCE',
        'm_desc' => 'x__reference',
        'm_parents' => array(11081,10692,4367,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fas fa-bars" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION SORT',
        'm_desc' => 'x__sort',
        'm_parents' => array(13007,13006,10676,10675,4341),
    ),
    6103 => array(
        'm_icon' => '<i class="fas fa-lambda"></i>',
        'm_name' => 'TRANSACTION METADATA',
        'm_desc' => 'x__metadata',
        'm_parents' => array(6212,4527,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-triangle" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION UP',
        'm_desc' => 'x__up',
        'm_parents' => array(11081,10692,6160,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-triangle rotate180" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION DOWN',
        'm_desc' => 'x__down',
        'm_parents' => array(11081,10692,6160,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-triangle rotate270" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION LEFT',
        'm_desc' => 'x__left',
        'm_parents' => array(11081,10692,6202,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-triangle rotate90" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION RIGHT',
        'm_desc' => 'x__right',
        'm_parents' => array(11081,10692,6202,4341),
    ),
);

//MENCH SOURCES:
$config['n___6206'] = array(6160,6177,6197,6198,13030,6172);
$config['e___6206'] = array(
    6160 => array(
        'm_icon' => '<i class="fas fa-at source" aria-hidden="true"></i>',
        'm_name' => 'ID',
        'm_desc' => 'e__id',
        'm_parents' => array(6206),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => 'e__status',
        'm_parents' => array(12766,11054,6204,5003,6160,6194,6206,4527),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'TITLE',
        'm_desc' => 'e__title',
        'm_parents' => array(13428,13410,13296,13025,6404,12112,12232,10646,5000,4998,4999,6206),
    ),
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'ICON',
        'm_desc' => 'e__icon',
        'm_parents' => array(12605,10653,5943,10625,6206),
    ),
    13030 => array(
        'm_icon' => '<i class="fas fa-weight source"></i>',
        'm_name' => 'WEIGHT',
        'm_desc' => 'e__weight',
        'm_parents' => array(6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda source"></i>',
        'm_name' => 'METADATA',
        'm_desc' => 'e__metadata',
        'm_parents' => array(7277,4527,6212,6206),
    ),
);

//MENCH IDEAS:
$config['n___6201'] = array(6202,4737,7585,4736,4356,13029,6159);
$config['e___6201'] = array(
    6202 => array(
        'm_icon' => '<i class="fas fa-hashtag idea" aria-hidden="true"></i>',
        'm_name' => 'ID',
        'm_desc' => 'i__id',
        'm_parents' => array(6201),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => 'i__status',
        'm_parents' => array(13408,13295,11054,10990,12079,6204,6226,6160,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => 'i__type',
        'm_parents' => array(13408,13295,11054,12079,6204,10651,6160,6194,4527,6201),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'TITLE',
        'm_desc' => 'i__title',
        'm_parents' => array(13407,13294,12994,6404,10990,12112,10644,6201),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'DURATION (SECONDS)',
        'm_desc' => 'i__duration',
        'm_parents' => array(10986,13295,6287,12741,11047,7274,6404,12112,12420,10650,6201),
    ),
    13029 => array(
        'm_icon' => '<i class="fas fa-weight idea"></i>',
        'm_name' => 'WEIGHT',
        'm_desc' => 'i__weight',
        'm_parents' => array(6201),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'METADATA',
        'm_desc' => 'i__metadata',
        'm_parents' => array(7277,4527,6212,11049,6201),
    ),
);

//SINGLE SELECTABLE:
$config['n___6204'] = array(13037,4737,7585,10602,13158,13172,13167,13166,13153,13174,13171,13152,13162,13156,13157,13155,13173,13170,13164,13160,13168,13165,13169,13159,13163,13161,13154,3290,6177,12968,6186,4593);
$config['e___6204'] = array(
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
        'm_parents' => array(13408,13295,11054,10990,12079,6204,6226,6160,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(13408,13295,11054,12079,6204,10651,6160,6194,4527,6201),
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
        'm_parents' => array(12766,11054,6204,5003,6160,6194,6206,4527),
    ),
    12968 => array(
        'm_icon' => '<i class="fas fa-sync source fa-spin" aria-hidden="true"></i>',
        'm_name' => 'SYNC ICONS IF DIFFERENT',
        'm_desc' => '',
        'm_parents' => array(6204,4527,12967),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6194,4527,4341),
    ),
);

//IDEA TYPE SELECT NEXT:
$config['n___7712'] = array(6684,7231);
$config['e___7712'] = array(
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

//DISCOVER ANSWER:
$config['n___7704'] = array(12336,12334,6157,7489);
$config['e___7704'] = array(
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
);

//IDEA LINK CONDITIONAL:
$config['n___4229'] = array(6140,10664,6997);
$config['e___4229'] = array(
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER UNLOCK CONDITION LINK',
        'm_desc' => 'A step that has become available because of the score generated from student answers',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4593,4229),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//IDEA OR:
$config['n___6193'] = array(6684,7231,6907);
$config['e___6193'] = array(
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
$config['n___7585'] = array(6677,6683,7637,6914,6907,6684,7231);
$config['e___7585'] = array(
    6677 => array(
        'm_icon' => '<i class="fas fa-eye idea" aria-hidden="true"></i>',
        'm_name' => 'READ & NEXT',
        'm_desc' => 'Read messages & go next',
        'm_parents' => array(13022,12330,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="fas fa-keyboard idea" aria-hidden="true"></i>',
        'm_name' => 'REPLY & NEXT',
        'm_desc' => 'Reply with text & go next',
        'm_parents' => array(13022,12955,6144,7585,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="fas fa-cloud-upload idea" aria-hidden="true"></i>',
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

//DISCOVER CARBON COPY:
$config['n___5967'] = array(4235,12419,6224,12773,4250,12453,12450,4246,7504);
$config['e___5967'] = array(
    4235 => array(
        'm_icon' => '<i class="fas fa-plus discover" aria-hidden="true"></i>',
        'm_name' => 'ADD TO MY DISCOVERIES',
        'm_desc' => '1',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '1',
        'm_parents' => array(11089,13304,13291,11018,12359,5967,10593,4986,4593,4485),
    ),
    6224 => array(
        'm_icon' => '<i class="fad fa-sync discover"></i>',
        'm_name' => 'DISCOVER UPDATE ACCOUNT',
        'm_desc' => '1',
        'm_parents' => array(5967,4755,4593),
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

//IDEA NOTES 1X SOURCE REQUIRED:
$config['n___7551'] = array(7545,4983,10573,12896);
$config['e___7551'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(6159,13421,13298,13291,12273,12197,11018,11089,7551,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(13407,11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(10876,11035,13424,4535,11054,4527,10984,11018,11035,11089,4593,7551,4485),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark discover"></i>',
        'm_name' => 'SAVED IDEAS',
        'm_desc' => '',
        'm_parents' => array(13424,13210,12677,13289,4485,12701,7551,11089,11018,11035,4755,4593),
    ),
);

//IDEA TYPE MEET REQUIREMENT:
$config['n___7309'] = array(6914,6907);
$config['e___7309'] = array(
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
$config['n___6287'] = array(7274,12739,12733,12722,7264,4356,7261,12731,12734,7260,7263,11049,7259,12138,7275,7276,12735,7712,4527,12114,7277,12710,12709,12729,12888,7267,12732,7268,7269,12730,12738,12712,12737,12736,7278,12967,7279,12569);
$config['e___6287'] = array(
    7274 => array(
        'm_icon' => '<i class="fas fa-clock mench-spin" aria-hidden="true"></i>',
        'm_name' => 'CRON JOBS',
        'm_desc' => '',
        'm_parents' => array(6404,6287,6403,12999),
    ),
    12739 => array(
        'm_icon' => '',
        'm_name' => 'DISCOVER ANALYZE URLS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12733 => array(
        'm_icon' => '<i class="fad fa-code discover"></i>',
        'm_name' => 'DISCOVER REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(11047,12741,12701,6287),
    ),
    12722 => array(
        'm_icon' => '',
        'm_name' => 'DISCOVER REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    7264 => array(
        'm_icon' => '<i class="fad fa-sitemap idea"></i>',
        'm_name' => 'IDEA BIRDS EYE MARKS',
        'm_desc' => '',
        'm_parents' => array(11047,6287),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DURATION (SECONDS)',
        'm_desc' => '',
        'm_parents' => array(10986,13295,6287,12741,11047,7274,6404,12112,12420,10650,6201),
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
    7259 => array(
        'm_icon' => '🔍',
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
        'm_parents' => array(6287,12741,7286,7274),
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
        'm_icon' => '<i class="fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'MENCH WEEKLY GROWTH REPORT',
        'm_desc' => '',
        'm_parents' => array(6287,12741,12701,7274,7569),
    ),
    7277 => array(
        'm_icon' => '',
        'm_name' => 'METADATA CLEAN VARIABLES',
        'm_desc' => '',
        'm_parents' => array(4527,6287,12741,7287,7274),
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
        'm_icon' => '🔍',
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
        'm_name' => 'SOURCE SYNC & FIX LINKS',
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

//DISCOVER STATUS INCOMPLETE:
$config['n___7364'] = array(6175);
$config['e___7364'] = array(
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner-third fa-spin discover" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
);

//DISCOVER STATUS ACTIVE:
$config['n___7360'] = array(6175,12399,6176);
$config['e___7360'] = array(
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner-third fa-spin discover" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    12399 => array(
        'm_icon' => '<i class="fas fa-star discover" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
);

//DISCOVER STATUS PUBLIC:
$config['n___7359'] = array(12399,6176);
$config['e___7359'] = array(
    12399 => array(
        'm_icon' => '<i class="fas fa-star discover" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
);

//SOURCE STATUS ACTIVE:
$config['n___7358'] = array(6180,12563,6181);
$config['e___7358'] = array(
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
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
);

//SOURCE STATUS PUBLIC:
$config['n___7357'] = array(12563,6181);
$config['e___7357'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star source" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
);

//IDEA STATUS ACTIVE:
$config['n___7356'] = array(6183,12137,6184);
$config['e___7356'] = array(
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
        'm_parents' => array(13420,10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
);

//IDEA STATUS PUBLIC:
$config['n___7355'] = array(12137,6184);
$config['e___7355'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(13420,10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
);

//DISCOVER STATS:
$config['n___7304'] = array(6186);
$config['e___7304'] = array(
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,7304,4527,6194,4341),
    ),
);

//TRANSACTION STATUS:
$config['n___6186'] = array(12399,6176,6175,6173);
$config['e___6186'] = array(
    12399 => array(
        'm_icon' => '<i class="fas fa-star discover" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner-third fa-spin discover" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="far fa-trash-alt discover" aria-hidden="true"></i>',
        'm_name' => 'UNPUBLISH',
        'm_desc' => '',
        'm_parents' => array(12012,10686,10678,10673,6186),
    ),
);

//SOURCE REFERENCES:
$config['n___6194'] = array(4737,7585,6287,6177,4364,6186,4593);
$config['e___6194'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(13408,13295,11054,10990,12079,6204,6226,6160,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(13408,13295,11054,12079,6204,10651,6160,6194,4527,6201),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => '',
        'm_parents' => array(13297,6405,6194,12699,12500,10876,11035,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6194,6206,4527),
    ),
    4364 => array(
        'm_icon' => '<i class="fas fa-user" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION MEMBER',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6194,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6194,4527,4341),
    ),
);

//DISCOVERIES:
$config['n___6255'] = array(6157,7489,4559,12117,6144,7485,7486,6997);
$config['e___6255'] = array(
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
        'm_name' => 'ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye discover"></i>',
        'm_name' => 'DISCOVER MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="fas fa-cloud-upload discover" aria-hidden="true"></i>',
        'm_name' => 'FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard discover" aria-hidden="true"></i>',
        'm_name' => 'TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//DISCOVERY REMOVED:
$config['n___6150'] = array(7757,6155);
$config['e___6150'] = array(
    7757 => array(
        'm_icon' => '<i class="discover fad fa-bookmark discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'REMOVE DISCOVERY',
        'm_desc' => '',
        'm_parents' => array(13414,6150,4593,4755),
    ),
);

//IDEA NOTES 2X SOURCES ALLOWED:
$config['n___4986'] = array(12419,4231);
$config['e___4986'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(11089,13304,13291,11018,12359,5967,10593,4986,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,10593,4986,4603,4593,4485),
    ),
);

//ACCOUNT SETTINGS:
$config['n___6225'] = array(12289,10869,10957,3288,3286,13037);
$config['e___6225'] = array(
    12289 => array(
        'm_icon' => '<i class="fas fa-paw" aria-hidden="true"></i>',
        'm_name' => 'AVATAR',
        'm_desc' => '',
        'm_parents' => array(6225,12897),
    ),
    10869 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'TOPICS OF INTEREST',
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
$config['n___4737'] = array(12137,6184,6183,6182);
$config['e___4737'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => 'Searchable by all players',
        'm_parents' => array(13420,10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => 'Ready to be Discovered',
        'm_parents' => array(10648,7355,7356,4737),
    ),
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin idea" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => 'Not Discoverable Yet',
        'm_parents' => array(10648,7356,4737),
    ),
    6182 => array(
        'm_icon' => '<i class="far fa-trash-alt idea" aria-hidden="true"></i>',
        'm_name' => 'DELETED',
        'm_desc' => 'Archived',
        'm_parents' => array(12400,4593,4737),
    ),
);

//SOURCE STATUS:
$config['n___6177'] = array(12563,6181,6180,6178);
$config['e___6177'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star source" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => 'Searchable by all Players',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="far fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'DELETED',
        'm_desc' => '',
        'm_parents' => array(4593,12401,6177),
    ),
);

//UNFINISHED:
$config['n___6146'] = array(7492);
$config['e___6146'] = array(
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER TERMINATE',
        'm_desc' => 'Logged when users arrive at a locked intent that has no public OR parents or no children, which means there is no way to unlock it.',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
);

//SOURCE LIST EDITOR:
$config['n___4997'] = array(5000,4998,4999,5001,5003,5865,5943,12318,10625,13441,5981,11956,5982,12928,12930);
$config['e___4997'] = array(
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
        'm_name' => 'ICON SET FOR ALL',
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
    13441 => array(
        'm_icon' => '<i class="fad fa-arrow-right source"></i>',
        'm_name' => 'PROFILE MOVE @ IF MISSING',
        'm_desc' => 'Migrates all portfolio sources to another source while preserves their message',
        'm_parents' => array(12577,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE ADD @ IF MISSING',
        'm_desc' => 'If not already done so, will add searched entity as the parent of all child entities',
        'm_parents' => array(12577,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE ADD @ IF HAS @',
        'm_desc' => 'Adds a parent entity only IF the entity has another parent entity.',
        'm_parents' => array(12577,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fad fa-layer-minus source"></i>',
        'm_name' => 'PROFILE REMOVE @ IF THERE',
        'm_desc' => 'If already added as the parent, this will remove searched entity as the parent of all child entities',
        'm_parents' => array(12577,4593,4997),
    ),
    12928 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE ADD @ IF HAS 1+ IDEAS',
        'm_desc' => 'Adds a profile source if the source has 1 or more ideas',
        'm_parents' => array(4997),
    ),
    12930 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PROFILE ADD @ IF HAS 0 IDEAS',
        'm_desc' => 'Adds a profile source if the source has 0 ideas',
        'm_parents' => array(4997),
    ),
);

//PRIVATE TRANSACTION:
$config['n___4755'] = array(13039,13040,13041,13038,4235,12336,12334,12197,4554,7757,5967,6559,6560,6556,6578,4556,6149,4283,6969,4275,7610,4555,12360,4266,4267,4282,6563,4570,7702,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,7492,4552,6140,6224,12328,7578,4553,7562,3288,12773,12453,10681,12450,4527,11054,13367,13042,3286,4783,4755,7495,6155,13415,6415,12896,7563,6132,13412,6157,7489,4246,4559,12117,7504,6144,7485,7486,6997,12489,12906);
$config['e___4755'] = array(
    13039 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$10 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
    ),
    13040 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$20 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
    ),
    13041 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$50 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
    ),
    13038 => array(
        'm_icon' => '<i class="fas fa-usd-circle"></i>',
        'm_name' => '$5 PER MONTH',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-plus discover" aria-hidden="true"></i>',
        'm_name' => 'ADD TO MY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12197 => array(
        'm_icon' => '<i class="fad fa-user-plus discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER APPEND PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4554 => array(
        'm_icon' => '<i class="discover fad fa-volume-up"></i>',
        'm_name' => 'DISCOVER AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7757 => array(
        'm_icon' => '<i class="discover fad fa-bookmark discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6150),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER CARBON COPY',
        'm_desc' => '',
        'm_parents' => array(4527,7569,4755,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="discover fad fa-wand-magic"></i>',
        'm_name' => 'DISCOVER COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="discover fad fa-wand-magic"></i>',
        'm_name' => 'DISCOVER COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="discover fad fa-wand-magic"></i>',
        'm_name' => 'DISCOVER COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="discover fad fa-wand-magic"></i>',
        'm_name' => 'DISCOVER COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    4556 => array(
        'm_icon' => '<i class="discover fad fa-file-pdf"></i>',
        'm_name' => 'DISCOVER FILE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER IDEA CONSIDERED',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fad fa-eye discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER IDEA LISTED',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="discover fad fa-megaphone"></i>',
        'm_name' => 'DISCOVER IDEA RECOMMENDED',
        'm_desc' => '',
        'm_parents' => array(4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="discover fad fa-search"></i>',
        'm_name' => 'DISCOVER IDEA SEARCH',
        'm_desc' => '',
        'm_parents' => array(6554,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="fad fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER IDEA VIEW',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4555 => array(
        'm_icon' => '<i class="discover fad fa-image"></i>',
        'm_name' => 'DISCOVER IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    12360 => array(
        'm_icon' => '<i class="fad fa-pen discover"></i>',
        'm_name' => 'DISCOVER MASS CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(6771,4593,4755),
    ),
    4266 => array(
        'm_icon' => '<i class="discover fab fa-facebook-messenger"></i>',
        'm_name' => 'DISCOVER MESSENGER OPT-IN',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="discover fab fa-facebook-messenger"></i>',
        'm_name' => 'DISCOVER MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="discover fad fa-eye"></i>',
        'm_name' => 'DISCOVER OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="discover fad fa-check"></i>',
        'm_name' => 'DISCOVER QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="discover fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER RECEIVED IDEA',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,7569),
    ),
    4577 => array(
        'm_icon' => '<i class="discover fad fa-user-plus"></i>',
        'm_name' => 'DISCOVER SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="discover fad fa-volume-up"></i>',
        'm_name' => 'DISCOVER SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="discover fad fa-file-pdf"></i>',
        'm_name' => 'DISCOVER SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="discover fad fa-image"></i>',
        'm_name' => 'DISCOVER SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="discover fad fa-location-circle"></i>',
        'm_name' => 'DISCOVER SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="fad fa-eye discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER SENT MESSENGER',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="discover fad fa-cloud-download"></i>',
        'm_name' => 'DISCOVER SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="discover fad fa-user-tag"></i>',
        'm_name' => 'DISCOVER SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="discover fad fa-check"></i>',
        'm_name' => 'DISCOVER SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="discover fad fa-align-left"></i>',
        'm_name' => 'DISCOVER SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="discover fad fa-comment-exclamation"></i>',
        'm_name' => 'DISCOVER SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="discover fad fa-video"></i>',
        'm_name' => 'DISCOVER SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fad fa-sign-in discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER SIGNIN FROM IDEA',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fad fa-sign-in discover"></i>',
        'm_name' => 'DISCOVER SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fad fa-sign-in discover"></i>',
        'm_name' => 'DISCOVER SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fad fa-sign-in discover"></i>',
        'm_name' => 'DISCOVER SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fad fa-sign-in discover"></i>',
        'm_name' => 'DISCOVER SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="discover fad fa-align-left"></i>',
        'm_name' => 'DISCOVER TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="fad fa-sync discover"></i>',
        'm_name' => 'DISCOVER UPDATE ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    12328 => array(
        'm_icon' => '<i class="fad fa-sync discover"></i>',
        'm_name' => 'DISCOVER UPDATE COMPLETION',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6153),
    ),
    7578 => array(
        'm_icon' => '<i class="discover fad fa-key"></i>',
        'm_name' => 'DISCOVER UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    4553 => array(
        'm_icon' => '<i class="discover fad fa-video"></i>',
        'm_name' => 'DISCOVER VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7562 => array(
        'm_icon' => '<i class="discover fad fa-envelope-open"></i>',
        'm_name' => 'DISCOVER WELCOME',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
    ),
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => '',
        'm_parents' => array(6404,13023,4269,12103,6225,4755),
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
    13367 => array(
        'm_icon' => '<i class="fas fa-minus-circle"></i>',
        'm_name' => 'NONE FOR NOW',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
    ),
    13042 => array(
        'm_icon' => '<i class="far fa-usd-circle"></i>',
        'm_name' => 'ONE-TIME DONATION',
        'm_desc' => '',
        'm_parents' => array(4755,13037),
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
        'm_name' => 'PRIVATE TRANSACTION',
        'm_desc' => '',
        'm_parents' => array(12701,4755,6771,4527),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone discover" aria-hidden="true"></i>',
        'm_name' => 'RECOMMENDED DISCOVERY',
        'm_desc' => '',
        'm_parents' => array(12969,12227,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'REMOVE DISCOVERY',
        'm_desc' => '',
        'm_parents' => array(13414,6150,4593,4755),
    ),
    13415 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'REMOVE IDEA',
        'm_desc' => '',
        'm_parents' => array(4755,4593,13414),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-trash-alt discover" aria-hidden="true"></i>',
        'm_name' => 'RESET DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(12500,4755,4593),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark discover"></i>',
        'm_name' => 'SAVED IDEAS',
        'm_desc' => '',
        'm_parents' => array(13424,13210,12677,13289,4485,12701,7551,11089,11018,11035,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'SIGN-IN MAGIC LINK',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-arrows"></i>',
        'm_name' => 'SORT DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13413,6153,4755,4593),
    ),
    13412 => array(
        'm_icon' => '<i class="fas fa-arrows"></i>',
        'm_name' => 'SORT IDEAS',
        'm_desc' => '',
        'm_parents' => array(13413,4755,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
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
    4559 => array(
        'm_icon' => '<i class="far fa-eye discover"></i>',
        'm_name' => 'SOURCE DISCOVER MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="fas fa-cloud-upload discover" aria-hidden="true"></i>',
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
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fal fa-bookmark discover"></i>',
        'm_name' => 'UNSAVE IDEA',
        'm_desc' => '',
        'm_parents' => array(12896,4755,4593),
    ),
);

//TRANSACTION TYPE:
$config['n___4593'] = array(4235,7545,12419,12129,12336,12334,12197,4554,7757,5967,6559,6560,6556,6578,10683,4556,6149,4283,6969,4275,7610,4555,12360,10690,4266,4267,4282,6563,4570,7702,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,7492,4552,6140,6224,12328,7578,4553,7562,12773,4250,6182,12453,4229,4228,10686,10663,10664,12611,12612,12591,12592,6226,10676,10678,10679,10677,10681,10675,12450,4983,10662,10648,10650,10644,10651,4993,4601,4231,10573,5001,10625,5943,12318,5865,4999,4998,5000,11956,5981,13441,5982,5003,7495,6155,13415,6415,12896,7563,6132,13412,4251,6157,7489,4246,6178,4559,12117,10653,4259,10657,4257,4261,4260,4319,7657,4230,10656,4255,4318,10659,10673,4256,4258,12827,10689,10646,7504,13006,13007,10654,6144,5007,7485,7486,6997,12489,4994,12906);
$config['e___4593'] = array(
    4235 => array(
        'm_icon' => '<i class="fas fa-plus discover" aria-hidden="true"></i>',
        'm_name' => 'ADD TO MY DISCOVERIES',
        'm_desc' => '',
        'm_parents' => array(13289,11035,12969,12227,5967,4755,4593),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(6159,13421,13298,13291,12273,12197,11018,11089,7551,4593,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(11089,13304,13291,11018,12359,5967,10593,4986,4593,4485),
    ),
    12129 => array(
        'm_icon' => '<i class="fas fa-times-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER ANSWER DELETED',
        'm_desc' => '',
        'm_parents' => array(6153,4593),
    ),
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12197 => array(
        'm_icon' => '<i class="fad fa-user-plus discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER APPEND PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4554 => array(
        'm_icon' => '<i class="discover fad fa-volume-up"></i>',
        'm_name' => 'DISCOVER AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7757 => array(
        'm_icon' => '<i class="discover fad fa-bookmark discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6150),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER CARBON COPY',
        'm_desc' => '',
        'm_parents' => array(4527,7569,4755,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="discover fad fa-wand-magic"></i>',
        'm_name' => 'DISCOVER COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="discover fad fa-wand-magic"></i>',
        'm_name' => 'DISCOVER COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="discover fad fa-wand-magic"></i>',
        'm_name' => 'DISCOVER COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="discover fad fa-wand-magic"></i>',
        'm_name' => 'DISCOVER COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    10683 => array(
        'm_icon' => '<i class="fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER EMAIL',
        'm_desc' => '',
        'm_parents' => array(6153,4593,7654),
    ),
    4556 => array(
        'm_icon' => '<i class="discover fad fa-file-pdf"></i>',
        'm_name' => 'DISCOVER FILE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER IDEA CONSIDERED',
        'm_desc' => 'When a student chooses to review a given intention from the intentions they have searched or have been recommended after selecting GET STARTED from a mench.com intent landing page.',
        'm_parents' => array(6153,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fad fa-eye discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER IDEA LISTED',
        'm_desc' => 'Once a student has added an Intention to their Action Plan, this link will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="discover fad fa-megaphone"></i>',
        'm_name' => 'DISCOVER IDEA RECOMMENDED',
        'm_desc' => 'Logged every time an intention is recommended to a user by Mench',
        'm_parents' => array(4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="discover fad fa-search"></i>',
        'm_name' => 'DISCOVER IDEA SEARCH',
        'm_desc' => 'When students invokes the [I want to] command and search for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(6554,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="fad fa-circle discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER IDEA VIEW',
        'm_desc' => 'When a user viewes the public intent landing page.',
        'm_parents' => array(4755,4593),
    ),
    4555 => array(
        'm_icon' => '<i class="discover fad fa-image"></i>',
        'm_name' => 'DISCOVER IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    12360 => array(
        'm_icon' => '<i class="fad fa-pen discover"></i>',
        'm_name' => 'DISCOVER MASS CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(6771,4593,4755),
    ),
    10690 => array(
        'm_icon' => '<i class="discover fad fa-upload"></i>',
        'm_name' => 'DISCOVER MEDIA UPLOADED',
        'm_desc' => 'When a file added by the user is synced to the CDN',
        'm_parents' => array(6153,4593),
    ),
    4266 => array(
        'm_icon' => '<i class="discover fab fa-facebook-messenger"></i>',
        'm_name' => 'DISCOVER MESSENGER OPT-IN',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="discover fab fa-facebook-messenger"></i>',
        'm_name' => 'DISCOVER MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="discover fad fa-eye"></i>',
        'm_name' => 'DISCOVER OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="discover fad fa-check"></i>',
        'm_name' => 'DISCOVER QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="discover fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER RECEIVED IDEA',
        'm_desc' => 'Emails sent to intent subscribers who are looking for updates on an intent.',
        'm_parents' => array(10593,4593,4755,7569),
    ),
    4577 => array(
        'm_icon' => '<i class="discover fad fa-user-plus"></i>',
        'm_name' => 'DISCOVER SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="discover fad fa-volume-up"></i>',
        'm_name' => 'DISCOVER SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="discover fad fa-file-pdf"></i>',
        'm_name' => 'DISCOVER SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="discover fad fa-image"></i>',
        'm_name' => 'DISCOVER SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="discover fad fa-location-circle"></i>',
        'm_name' => 'DISCOVER SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="fad fa-eye discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER SENT MESSENGER',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="discover fad fa-cloud-download"></i>',
        'm_name' => 'DISCOVER SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="discover fad fa-user-tag"></i>',
        'm_name' => 'DISCOVER SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="discover fad fa-check"></i>',
        'm_name' => 'DISCOVER SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="discover fad fa-align-left"></i>',
        'm_name' => 'DISCOVER SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="discover fad fa-comment-exclamation"></i>',
        'm_name' => 'DISCOVER SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="discover fad fa-video"></i>',
        'm_name' => 'DISCOVER SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fad fa-sign-in discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER SIGNIN FROM IDEA',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fad fa-sign-in discover"></i>',
        'm_name' => 'DISCOVER SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fad fa-sign-in discover"></i>',
        'm_name' => 'DISCOVER SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fad fa-sign-in discover"></i>',
        'm_name' => 'DISCOVER SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fad fa-sign-in discover"></i>',
        'm_name' => 'DISCOVER SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="discover fad fa-align-left"></i>',
        'm_name' => 'DISCOVER TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open discover" aria-hidden="true"></i>',
        'm_name' => 'DISCOVER UNLOCK CONDITION LINK',
        'm_desc' => 'Created when the student responses to OR branches meets the right % points to unlock the pathway to a conditional intent link.',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="fad fa-sync discover"></i>',
        'm_name' => 'DISCOVER UPDATE ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    12328 => array(
        'm_icon' => '<i class="fad fa-sync discover"></i>',
        'm_name' => 'DISCOVER UPDATE COMPLETION',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6153),
    ),
    7578 => array(
        'm_icon' => '<i class="discover fad fa-key"></i>',
        'm_name' => 'DISCOVER UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    4553 => array(
        'm_icon' => '<i class="discover fad fa-video"></i>',
        'm_name' => 'DISCOVER VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7562 => array(
        'm_icon' => '<i class="discover fad fa-envelope-open"></i>',
        'm_name' => 'DISCOVER WELCOME',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
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
        'm_icon' => '<i class="far fa-trash-alt idea" aria-hidden="true"></i>',
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
    12611 => array(
        'm_icon' => '<i class="fad fa-layer-plus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR PREVIOUS IDEA ADD',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12612 => array(
        'm_icon' => '<i class="fad fa-layer-minus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR PREVIOUS IDEA REMOVE',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12591 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR SOURCE ADD',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12592 => array(
        'm_icon' => '<i class="fas fa-minus-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDITOR SOURCE REMOVE',
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
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(13407,11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
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
        'm_icon' => '<i class="fas fa-tags" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,10593,4986,4603,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(10876,11035,13424,4535,11054,4527,10984,11018,11035,11089,4593,7551,4485),
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
        'm_name' => 'PORTFOLIO EDITOR ICON SET FOR ALL',
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
    11956 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE ADD @ IF HAS @',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fad fa-layer-plus source"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE ADD @ IF MISSING',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    13441 => array(
        'm_icon' => '<i class="fad fa-arrow-right source"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE MOVE @ IF MISSING',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fad fa-layer-minus source"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE REMOVE @ IF THERE',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="source fad fa-sliders-h"></i>',
        'm_name' => 'PORTFOLIO EDITOR STATUS',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone discover" aria-hidden="true"></i>',
        'm_name' => 'RECOMMENDED DISCOVERY',
        'm_desc' => 'Intention recommended by Mench and added to Action Plan to enable the user to complete their intention',
        'm_parents' => array(12969,12227,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'REMOVE DISCOVERY',
        'm_desc' => 'Student prematurely removed an intention from their Action Plan without accomplishing it.',
        'm_parents' => array(13414,6150,4593,4755),
    ),
    13415 => array(
        'm_icon' => '<i class="far fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'REMOVE IDEA',
        'm_desc' => '',
        'm_parents' => array(4755,4593,13414),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-trash-alt discover" aria-hidden="true"></i>',
        'm_name' => 'RESET DISCOVERIES',
        'm_desc' => 'Removes all player read coins so everything is reset to 0% again.',
        'm_parents' => array(12500,4755,4593),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark discover"></i>',
        'm_name' => 'SAVED IDEAS',
        'm_desc' => '',
        'm_parents' => array(13424,13210,12677,13289,4485,12701,7551,11089,11018,11035,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="fad fa-envelope-open discover" aria-hidden="true"></i>',
        'm_name' => 'SIGN-IN MAGIC LINK',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-arrows"></i>',
        'm_name' => 'SORT DISCOVERIES',
        'm_desc' => 'Student re-prioritized their top-level intentions to focus on intentions that currently matter the most.',
        'm_parents' => array(13413,6153,4755,4593),
    ),
    13412 => array(
        'm_icon' => '<i class="fas fa-arrows"></i>',
        'm_name' => 'SORT IDEAS',
        'm_desc' => '',
        'm_parents' => array(13413,4755,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ADDED',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(4527,12274,12401,12149,12141,10593,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square discover" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="far fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE DELETED',
        'm_desc' => '',
        'm_parents' => array(4593,12401,6177),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye discover"></i>',
        'm_name' => 'SOURCE DISCOVER MESSAGES',
        'm_desc' => 'Logged when a student receives the messages of an AND intent that does not have any completion requirements.',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="fas fa-cloud-upload discover" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fas fa-external-link source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK URL',
        'm_desc' => '',
        'm_parents' => array(13433,12822,11080,4593,4592,4537),
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
        'm_parents' => array(13429,11035,4593),
    ),
    10654 => array(
        'm_icon' => '<i class="fad fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(12401,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard discover" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check discover" aria-hidden="true"></i>',
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
        'm_icon' => '<i class="fal fa-bookmark discover"></i>',
        'm_name' => 'UNSAVE IDEA',
        'm_desc' => '',
        'm_parents' => array(12896,4755,4593),
    ),
);

//SOURCE LINKS:
$config['n___4592'] = array(4259,4257,4261,4260,4319,7657,4230,4255,4318,4256,4258,12827);
$config['e___4592'] = array(
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
        'm_icon' => '<i class="fas fa-external-link source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(13433,12822,11080,4593,4592,4537),
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
$config['n___4485'] = array(4231,12419,4601,4983,7545,10573,12896);
$config['e___4485'] = array(
    4231 => array(
        'm_icon' => '<i class="fas fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(13300,13294,13291,7524,12273,12359,10593,4986,4603,4593,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(11089,13304,13291,11018,12359,5967,10593,4986,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,10593,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(13407,11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-file-certificate" aria-hidden="true"></i>',
        'm_name' => 'CERTIFICATES',
        'm_desc' => '',
        'm_parents' => array(6159,13421,13298,13291,12273,12197,11018,11089,7551,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-map-marker-plus idea" aria-hidden="true"></i>',
        'm_name' => 'MY IDEAS',
        'm_desc' => '',
        'm_parents' => array(10876,11035,13424,4535,11054,4527,10984,11018,11035,11089,4593,7551,4485),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark discover"></i>',
        'm_name' => 'SAVED IDEAS',
        'm_desc' => '',
        'm_parents' => array(13424,13210,12677,13289,4485,12701,7551,11089,11018,11035,4755,4593),
    ),
);

//IDEA LINKS:
$config['n___4486'] = array(4228,4229);
$config['e___4486'] = array(
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
$config['n___4537'] = array(4259,4257,4261,4260,4256,4258);
$config['e___4537'] = array(
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
        'm_icon' => '<i class="fas fa-external-link source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => 'Link note contains a generic URL only',
        'm_parents' => array(13433,12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fas fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'URL to a raw video file',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,4593,4592,4537),
    ),
);

//EXPERT CONTENT:
$config['n___3000'] = array(3005,2998,2997,13218,3147,4446,13350,3192,13432);
$config['e___3000'] = array(
    3005 => array(
        'm_icon' => '<i class="fas fa-book source" aria-hidden="true"></i>',
        'm_name' => 'BOOKS',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fas fa-play-circle source"></i>',
        'm_name' => 'VIDEOS',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    2997 => array(
        'm_icon' => '<i class="fas fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'ARTICLES',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    13218 => array(
        'm_icon' => '<i class="fas fa-microphone source"></i>',
        'm_name' => 'PODCASTS',
        'm_desc' => '',
        'm_parents' => array(3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fas fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'COURSES',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fas fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'ASSESSMENTS',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    13350 => array(
        'm_icon' => '<i class="fas fa-file-chart-line source"></i>',
        'm_name' => 'REPORTS',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fas fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'TOOLS',
        'm_desc' => '',
        'm_parents' => array(12968,3000),
    ),
    13432 => array(
        'm_icon' => '<i class="fas fa-circle source"></i>',
        'm_name' => 'OTHER',
        'm_desc' => '',
        'm_parents' => array(3000),
    ),
);