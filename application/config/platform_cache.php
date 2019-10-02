<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
* Keep a cache of certain parts of the Intent tree for faster processing
* So we don't have to make DB calls to figure them out every time!
* See here for all entities cached: https://mench.com/entities/4527
* use-case format: $this->config->item('')
*
* ATTENTION: Also search for "en_ids_" and "en_all_" when trying to manage these throughout the code base
*
*/

//Generated 2019-10-01 20:31:57 PST

//Mench Platform Focused Topics:
$config['en_ids_10709'] = array(10865,10861,10862,10867,10864,10870);
$config['en_all_10709'] = array(
    10865 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'Join Our Team',
        'm_desc' => '',
        'm_parents' => array(10709),
    ),
    10861 => array(
        'm_icon' => '<i class="far fa-tachometer-fast"></i>',
        'm_name' => 'Assess Coding Skills',
        'm_desc' => '',
        'm_parents' => array(10709),
    ),
    10862 => array(
        'm_icon' => '<i class="far fa-user-circle"></i>',
        'm_name' => 'Assess Personality',
        'm_desc' => '',
        'm_parents' => array(10709),
    ),
    10867 => array(
        'm_icon' => '<i class="far fa-id-card-alt"></i>',
        'm_name' => 'Branding & Story Telling',
        'm_desc' => '',
        'm_parents' => array(10709),
    ),
    10864 => array(
        'm_icon' => '<i class="far fa-comment-smile"></i>',
        'm_name' => 'Interview Practice',
        'm_desc' => '',
        'm_parents' => array(10709),
    ),
    10870 => array(
        'm_icon' => '<i class="far fa-shapes"></i>',
        'm_name' => 'Others',
        'm_desc' => '',
        'm_parents' => array(10709),
    ),
);

//Mench Platform Moderators:
$config['en_ids_10704'] = array(1308);
$config['en_all_10704'] = array(
    1308 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isnavy"></i>',
        'm_name' => 'Mench Blogger Level 2',
        'm_desc' => '',
        'm_parents' => array(10573,7701,4983,10704,10618,10691,10571,4463,4426),
    ),
);

//Mench Bloggers:
$config['en_ids_10691'] = array(7512,1308);
$config['en_all_10691'] = array(
    7512 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isorange"></i>',
        'm_name' => 'Mench Blogger Level 1',
        'm_desc' => 'Entry level trainers focused on intents and intent notes only',
        'm_parents' => array(7701,4983,10606,10691,10573,10571),
    ),
    1308 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isnavy"></i>',
        'm_name' => 'Mench Blogger Level 2',
        'm_desc' => 'Certified trainers who understand the core training principles',
        'm_parents' => array(10573,7701,4983,10704,10618,10691,10571,4463,4426),
    ),
);

//Interaction Connectors:
$config['en_ids_10692'] = array(4429,4369,4366,4368,4371);
$config['en_all_10692'] = array(
    4429 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Link Child Entity',
        'm_desc' => '',
        'm_parents' => array(10692,6160,6232,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Link Child Intent',
        'm_desc' => '',
        'm_parents' => array(10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Link Parent Entity',
        'm_desc' => '',
        'm_parents' => array(10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Link Parent Intent',
        'm_desc' => '',
        'm_parents' => array(10692,6202,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Link Parent Link',
        'm_desc' => '',
        'm_parents' => array(10692,4367,6232,4341),
    ),
);

//Mench Platform Cache:
$config['en_ids_4527'] = array(7758,7774,6192,7712,7756,7701,4983,4485,6193,7596,7588,7302,4737,7356,7355,6201,4229,4486,7585,10602,7309,7751,6827,10627,7703,10692,6103,10591,10589,10590,7304,6186,7360,7364,7359,4341,4593,7347,10658,10592,7799,7368,10691,7529,6287,7555,4527,10709,10704,7372,7369,6225,10596,7366,4755,10571,6345,4600,6150,5967,4280,10570,4277,6102,7704,6244,6255,6274,6146,7494,6144,7767,10568,7582,6204,10539,10594,6123,10593,4454,6194,5969,6805,4997,4986,7551,4426,7303,6177,7358,7357,6206,4592,4537,3000,10567,7203);
$config['en_all_4527'] = array(
    7758 => array(
        'm_icon' => '<i class="far fa-mountain"></i>',
        'm_name' => 'Action Plan Blogion Successful',
        'm_desc' => '',
        'm_parents' => array(4527,7493),
    ),
    7774 => array(
        'm_icon' => '<i class="fas fa-search-plus"></i>',
        'm_name' => 'Algolia Indexable',
        'm_desc' => '',
        'm_parents' => array(4757,4428,7279,4527),
    ),
    6192 => array(
        'm_icon' => '<i class="far fa-sitemap "></i>',
        'm_name' => 'Blog AND',
        'm_desc' => '&trim=Blog',
        'm_parents' => array(4527,10602),
    ),
    7712 => array(
        'm_icon' => '<i class="far fa-question-circle"></i>',
        'm_name' => 'Blog Answer Types',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    7756 => array(
        'm_icon' => '<i class="far fa-wand-magic"></i>',
        'm_name' => 'Blog Auto Completable',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'Blog Note Followers',
        'm_desc' => '',
        'm_parents' => array(5007,10594,10589,4527,7703,4593,7551,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Blog Note References',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus"></i>',
        'm_name' => 'Blog Notes',
        'm_desc' => '&trim=Blog Note',
        'm_parents' => array(6205,7552,4535,4527,4463),
    ),
    6193 => array(
        'm_icon' => '<i class="far fa-code-merge "></i>',
        'm_name' => 'Blog OR',
        'm_desc' => '&trim=Blog',
        'm_parents' => array(10602,4527),
    ),
    7596 => array(
        'm_icon' => '<i class="fas fa-triangle"></i>',
        'm_name' => 'Blog Scope',
        'm_desc' => '&trim=Blog',
        'm_parents' => array(6204,10649,7302,6160,6201,6194,6232,4527),
    ),
    7588 => array(
        'm_icon' => '<i class="far fa-hand-pointer"></i>',
        'm_name' => 'Blog Select Publicly',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    7302 => array(
        'm_icon' => '<i class="far fa-chart-bar "></i>',
        'm_name' => 'Blog Stats',
        'm_desc' => '&trim=Blog',
        'm_parents' => array(4527,7161,4535),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h "></i>',
        'm_name' => 'Blog Status',
        'm_desc' => '&trim=Blog ',
        'm_parents' => array(6204,10648,6226,6160,6232,7302,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'Blog Statuses Active',
        'm_desc' => '',
        'm_parents' => array(10891,4527),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'Blog Statuses Public',
        'm_desc' => '',
        'm_parents' => array(10891,4527),
    ),
    6201 => array(
        'm_icon' => '<i class="far fa-table "></i>',
        'm_name' => 'Blog Table',
        'm_desc' => '&trim=Blog',
        'm_parents' => array(4527,7735,4535),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Blog-to-Blog Interaction Conditional',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Blog-to-Blog Interactions',
        'm_desc' => '&trim=Blog Interaction',
        'm_parents' => array(6205,10662,4535,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-puzzle-piece"></i>',
        'm_name' => 'Blog Type',
        'm_desc' => '&trim=Blog',
        'm_parents' => array(10608,6204,10651,6160,6194,6232,4527,6201),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece"></i>',
        'm_name' => 'Blog Type Groups',
        'm_desc' => '&trim=Blog',
        'm_parents' => array(10893,6204,7302,4527),
    ),
    7309 => array(
        'm_icon' => '<i class="far fa-cubes"></i>',
        'm_name' => 'Blog Type Requirement',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    7751 => array(
        'm_icon' => '<i class="far fa-upload"></i>',
        'm_name' => 'Blog Upload File',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    6827 => array(
        'm_icon' => '<i class="far fa-users-crown"></i>',
        'm_name' => 'Community Members',
        'm_desc' => '&trim=Mench ',
        'm_parents' => array(3303,3314,2738,7303,4527),
    ),
    10627 => array(
        'm_icon' => '<i class="far fa-paperclip"></i>',
        'm_name' => 'File Type Attachment',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    7703 => array(
        'm_icon' => '<i class="fas fa-rss"></i>',
        'm_name' => 'Interaction Blog Subscription Types',
        'm_desc' => '',
        'm_parents' => array(7701,4527,6771),
    ),
    10692 => array(
        'm_icon' => '<i class="fas fa-bezier-curve"></i>',
        'm_name' => 'Interaction Connectors',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'Interaction Metadata',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    10591 => array(
        'm_icon' => '<i class="fas fa-directions"></i>',
        'm_name' => 'Interaction Reader Groups',
        'm_desc' => '&trim=Interactions by ',
        'm_parents' => array(6204,7797,7304,6771,4527,10588),
    ),
    10589 => array(
        'm_icon' => '<i class="far fa-user-edit ispink"></i>',
        'm_name' => 'Interactions by Bloggers',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
    10590 => array(
        'm_icon' => '<i class="far fa-user ispink"></i>',
        'm_name' => 'Interactions by Readers',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
    7304 => array(
        'm_icon' => '<i class="far fa-chart-bar"></i>',
        'm_name' => 'Interaction Stats',
        'm_desc' => '&trim=Interaction',
        'm_parents' => array(4527,6205,7161),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h"></i>',
        'm_name' => 'Interaction Status',
        'm_desc' => '&trim=Interaction ',
        'm_parents' => array(10677,10661,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    7360 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'Interaction Statuses Active',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    7364 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'Interaction Statuses Incomplete',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    7359 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'Interaction Statuses Public',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    4341 => array(
        'm_icon' => '<i class="far fa-table"></i>',
        'm_name' => 'Interaction Table',
        'm_desc' => '&trim=Interaction',
        'm_parents' => array(4527,7735,6205),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug"></i>',
        'm_name' => 'Interaction Type',
        'm_desc' => '',
        'm_parents' => array(10607,10659,6160,6232,6194,4527,4341),
    ),
    7347 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'Interaction Type Reader Set Blogion',
        'm_desc' => '&trim=Reader',
        'm_parents' => array(4527,6219),
    ),
    10658 => array(
        'm_icon' => '<i class="fas fa-sync"></i>',
        'm_name' => 'Interaction Updates',
        'm_desc' => '',
        'm_parents' => array(4527,6205),
    ),
    10592 => array(
        'm_icon' => '<i class="fas fa-weight"></i>',
        'm_name' => 'Interaction Word Weight',
        'm_desc' => '',
        'm_parents' => array(6204,6771,4527,10588),
    ),
    7799 => array(
        'm_icon' => '<i class="far fa-calendar"></i>',
        'm_name' => 'Leaderboard Time Frames',
        'm_desc' => '&trim=Leaderboard',
        'm_parents' => array(4527,7797),
    ),
    7368 => array(
        'm_icon' => '<i class="far fa-user-edit"></i>',
        'm_name' => 'Mench Blogger Console',
        'm_desc' => '&trim=Mench',
        'm_parents' => array(7372,4527),
    ),
    10691 => array(
        'm_icon' => '<i class="far fa-user-edit"></i>',
        'm_name' => 'Mench Bloggers',
        'm_desc' => '',
        'm_parents' => array(4527,7368,6827),
    ),
    7529 => array(
        'm_icon' => '<i class="fas fa-hat-wizard"></i>',
        'm_name' => 'Mench Conversation Templates',
        'm_desc' => '&trim=Mench',
        'm_parents' => array(2738,4527),
    ),
    6287 => array(
        'm_icon' => '<i class="far fa-tools"></i>',
        'm_name' => 'Mench Moderation Tools',
        'm_desc' => '',
        'm_parents' => array(7368,4527,7284),
    ),
    7555 => array(
        'm_icon' => '<i class="far fa-comments"></i>',
        'm_name' => 'Mench Notifications',
        'm_desc' => '',
        'm_parents' => array(7303,7372,4527),
    ),
    4527 => array(
        'm_icon' => '<i class="fas fa-memory"></i>',
        'm_name' => 'Mench Platform Cache',
        'm_desc' => '',
        'm_parents' => array(4527,7305,6404,7258,7254,4506),
    ),
    10709 => array(
        'm_icon' => '<i class="fas fa-folder-tree"></i>',
        'm_name' => 'Mench Platform Focused Topics',
        'm_desc' => '',
        'm_parents' => array(10715,1,4527,7254),
    ),
    10704 => array(
        'm_icon' => '<i class="far fa-user-shield"></i>',
        'm_name' => 'Mench Platform Moderators',
        'm_desc' => '',
        'm_parents' => array(4527,7254),
    ),
    7372 => array(
        'm_icon' => '<i class="fas fa-layer-group"></i>',
        'm_name' => 'Mench Products',
        'm_desc' => '&trim=Mench',
        'm_parents' => array(2738,4527),
    ),
    7369 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Mench Reader Console',
        'm_desc' => '&trim=Mench',
        'm_parents' => array(7372,4527),
    ),
    6225 => array(
        'm_icon' => '<i class="fas fa-keyboard"></i>',
        'm_name' => 'My Account Inputs',
        'm_desc' => '&trim=Thing',
        'm_parents' => array(7552,4527,6137),
    ),
    10596 => array(
        'm_icon' => '<i class="fas fa-square-root"></i>',
        'm_name' => 'Nod',
        'm_desc' => '',
        'm_parents' => array(4527,10592),
    ),
    7366 => array(
        'm_icon' => '<i class="far fa-eye-slash"></i>',
        'm_name' => 'Private Blog Types',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash"></i>',
        'm_name' => 'Private Interactions',
        'm_desc' => '',
        'm_parents' => array(6771,4463,4426,4527,4757),
    ),
    10571 => array(
        'm_icon' => '<i class="fas fa-globe"></i>',
        'm_name' => 'Public Entities',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    6345 => array(
        'm_icon' => '<i class="fas fa-comment-check"></i>',
        'm_name' => 'Readable by Readers',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    4600 => array(
        'm_icon' => '<i class="far fa-badge-check"></i>',
        'm_name' => 'Reader Account Types',
        'm_desc' => '',
        'm_parents' => array(4758,4527),
    ),
    6150 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Reader Completed Blogion',
        'm_desc' => '&trim=Reader',
        'm_parents' => array(4527,6219),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Interaction CC Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4506,4527,7569,4755,4593),
    ),
    4280 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Reader Received Messages with Messenger',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    10570 => array(
        'm_icon' => '<i class="far fa-hand-pointer"></i>',
        'm_name' => 'Reader Selectable Completion',
        'm_desc' => '&trim=Reader',
        'm_parents' => array(4527,7493),
    ),
    4277 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Reader Sent Messages with Messenger',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    6102 => array(
        'm_icon' => '<i class="far fa-paperclip"></i>',
        'm_name' => 'Reader Sent/Received Attachment',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    7704 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'Reader Step Answered Successfully',
        'm_desc' => '',
        'm_parents' => array(4527,7493),
    ),
    6244 => array(
        'm_icon' => '<i class="far fa-shoe-prints"></i>',
        'm_name' => 'Reader Steps Double',
        'm_desc' => '',
        'm_parents' => array(7493,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-walking"></i>',
        'm_name' => 'Reader Steps Progressed',
        'm_desc' => '',
        'm_parents' => array(7493,4527),
    ),
    6274 => array(
        'm_icon' => '<i class="fas fa-fast-forward"></i>',
        'm_name' => 'Reader Steps Skippable',
        'm_desc' => '',
        'm_parents' => array(7493,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Reader Steps Taken',
        'm_desc' => '&trim=Reader Step',
        'm_parents' => array(6219,4527),
    ),
    7494 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Reader Steps Unlock',
        'm_desc' => '',
        'm_parents' => array(4506,4527,7493),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'Reader Submission Required',
        'm_desc' => '&trim=Blog',
        'm_parents' => array(10893,10596,10589,4527,7703,6255,6244,4755,6146,4593),
    ),
    7767 => array(
        'm_icon' => '<i class="far fa-user-edit"></i>',
        'm_name' => 'Reusable by Blogger',
        'm_desc' => '&trim=Blog',
        'm_parents' => array(10890,4428,4527),
    ),
    10568 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Searchable by Reader/Blogger',
        'm_desc' => '',
        'm_parents' => array(10890,4527),
    ),
    7582 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'Sign-in Required by Reader',
        'm_desc' => '',
        'm_parents' => array(10890,7493,4527),
    ),
    6204 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'Single Selectable',
        'm_desc' => '',
        'm_parents' => array(4428,4506,4527,4758),
    ),
    10539 => array(
        'm_icon' => '<i class="far fa-file-word"></i>',
        'm_name' => 'Single Word',
        'm_desc' => '',
        'm_parents' => array(4527,10592,5008),
    ),
    10594 => array(
        'm_icon' => '<i class="fas fa-value-absolute"></i>',
        'm_name' => 'Single Word + Connections',
        'm_desc' => '',
        'm_parents' => array(4527,10592),
    ),
    6123 => array(
        'm_icon' => '<i class="far fa-share-alt-square"></i>',
        'm_name' => 'Social Profiles',
        'm_desc' => '',
        'm_parents' => array(6225,4527),
    ),
    10593 => array(
        'm_icon' => '<i class="fas fa-union"></i>',
        'm_name' => 'Statement + Connections',
        'm_desc' => '',
        'm_parents' => array(4527,10592),
    ),
    4454 => array(
        'm_icon' => '<i class="far fa-bells"></i>',
        'm_name' => 'Subscription Settings',
        'm_desc' => '',
        'm_parents' => array(7552,6225,6204,4527),
    ),
    6194 => array(
        'm_icon' => '<i class="far fa-database"></i>',
        'm_name' => 'Thing Database References',
        'm_desc' => '',
        'm_parents' => array(4758,4527,6212),
    ),
    5969 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Thing Hard Lock',
        'm_desc' => '',
        'm_parents' => array(4758,3303,4426,4527,4757,4428),
    ),
    6805 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'Thing Interaction Content Requires Text',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-list-alt"></i>',
        'm_name' => 'Thing Mass Updates',
        'm_desc' => '&trim=Thing Mass ',
        'm_parents' => array(4536,4506,4426,4527),
    ),
    4986 => array(
        'm_icon' => '<i class="fal fa-at"></i>',
        'm_name' => 'Thing Reference Allowed',
        'm_desc' => '',
        'm_parents' => array(10889,4758,4527),
    ),
    7551 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Thing Reference Required',
        'm_desc' => '',
        'm_parents' => array(10889,4527,4758),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Thing Soft Lock',
        'm_desc' => '',
        'm_parents' => array(4758,3303,4426,4527,4757,4428),
    ),
    7303 => array(
        'm_icon' => '<i class="far fa-chart-bar"></i>',
        'm_name' => 'Thing Stats',
        'm_desc' => '&trim=Thing',
        'm_parents' => array(4527,7161,4536),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h"></i>',
        'm_name' => 'Thing Status',
        'm_desc' => '&trim=Thing ',
        'm_parents' => array(6204,5003,10654,6160,6232,7303,6194,6206,4527),
    ),
    7358 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'Thing Statuses Active',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    7357 => array(
        'm_icon' => '<i class="fas fa-globe"></i>',
        'm_name' => 'Thing Statuses Public',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    6206 => array(
        'm_icon' => '<i class="far fa-table"></i>',
        'm_name' => 'Thing Table',
        'm_desc' => '&trim=Thing',
        'm_parents' => array(4527,7735,4536),
    ),
    4592 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Thing-to-Thing Interactions',
        'm_desc' => '&trim=Thing-to-Thing Interaction',
        'm_parents' => array(6205,5982,5981,4536,4527),
    ),
    4537 => array(
        'm_icon' => '<i class="fal fa-spider-web"></i>',
        'm_name' => 'Thing-to-Thing URL Interaction Types',
        'm_desc' => '&trim=Thing Interaction',
        'm_parents' => array(4758,4527),
    ),
    3000 => array(
        'm_icon' => '<i class="far fa-whistle"></i>',
        'm_name' => 'Trained Expert Sources',
        'm_desc' => '',
        'm_parents' => array(10571,7303,4506,4527,4463),
    ),
    10567 => array(
        'm_icon' => '<i class="far fa-equals"></i>',
        'm_name' => 'Verb Skipping Allowed by Blogger',
        'm_desc' => '',
        'm_parents' => array(10890,4527),
    ),
    7203 => array(
        'm_icon' => '<i class="far fa-calendar-week"></i>',
        'm_name' => 'Weekly Leaderboard Message',
        'm_desc' => '',
        'm_parents' => array(7800,4527),
    ),
);

//Interaction Updates:
$config['en_ids_10658'] = array(10675,10686,10663,10664,10661,10662,10660,10676,10678,10679,10677,10681,10685,10690,10683,7578,10689,10673,10657,10656,10659);
$config['en_all_10658'] = array(
    10675 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blogger Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(7703,10539,10589,4593,10658,10638),
    ),
    10686 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Interaction Archived',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10539,4593,10658,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-coin ispink"></i>',
        'm_name' => 'Blog Interaction Update Points',
        'm_desc' => '',
        'm_parents' => array(4228,7703,10539,10589,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Blog Interaction Update Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    10661 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Interaction Update Status',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Blog Interaction Update Type',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10660 => array(
        'm_icon' => '<i class="fas fa-angle-double-right ispink"></i>',
        'm_name' => 'Blog Migrate Parent Blog',
        'm_desc' => '',
        'm_parents' => array(7703,4593,10638,10589,10594,10658),
    ),
    10676 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blog Notes Sorted',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Notes Unlinked',
        'm_desc' => '',
        'm_parents' => array(10658,10539,10589,4593,10638),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Blog Notes Update Content',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Notes Update Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(10638,10589,10596,4755,4593,10658),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'Blog Submission Update by Reader',
        'm_desc' => '',
        'm_parents' => array(4755,10596,10589,4593,10638,10658),
    ),
    10690 => array(
        'm_icon' => '<i class="fas fa-upload ispink"></i>',
        'm_name' => 'Reader Media Uploaded',
        'm_desc' => '',
        'm_parents' => array(6153,10596,10589,4593,10658),
    ),
    10683 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Read Email',
        'm_desc' => '',
        'm_parents' => array(6153,10658,10596,10589,4593,7654),
    ),
    7578 => array(
        'm_icon' => '<i class="far fa-key ispink"></i>',
        'm_name' => 'Reader Update Password',
        'm_desc' => '',
        'm_parents' => array(6222,10658,6153,10539,10589,4755,4593),
    ),
    10689 => array(
        'm_icon' => '<i class="fas fa-share-alt rotate90 ispink"></i>',
        'm_name' => 'Thing Merged into Thing',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10658,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Archived',
        'm_desc' => '',
        'm_parents' => array(10645,4593,10539,10589,10658),
    ),
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Content',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    10656 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Status',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10539,10658,10645),
    ),
    10659 => array(
        'm_icon' => '<i class="fas fa-plug ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Type',
        'm_desc' => '',
        'm_parents' => array(10658,10589,4593,10596,10645),
    ),
);

//Single Word:
$config['en_ids_10539'] = array(10671,10675,10686,10663,10661,10662,10676,10678,10677,10650,10649,10648,10651,6154,4235,6155,6132,7757,4557,4460,4547,6140,7578,6224,10672,10673,4230,4257,4319,10656,4256,10653,10654);
$config['en_all_10539'] = array(
    10671 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Archived',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,7703,10638),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blogger Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(7703,10539,10589,4593,10658,10638),
    ),
    10686 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Interaction Archived',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10539,4593,10658,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-coin ispink"></i>',
        'm_name' => 'Blog Interaction Update Points',
        'm_desc' => '',
        'm_parents' => array(4228,7703,10539,10589,10638,4593,10658),
    ),
    10661 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Interaction Update Status',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Blog Interaction Update Type',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10676 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blog Notes Sorted',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Notes Unlinked',
        'm_desc' => '',
        'm_parents' => array(10658,10539,10589,4593,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Notes Update Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10650 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Blog Update Completion Time',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10649 => array(
        'm_icon' => '<i class="fas fa-triangle ispink"></i>',
        'm_name' => 'Blog Update Scope',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10648 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Update Status',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="far fa-shapes ispink"></i>',
        'm_name' => 'Blog Update Type',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'Reader Blog Accomplished',
        'm_desc' => '',
        'm_parents' => array(10888,10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Reader Blog Bookmarked',
        'm_desc' => '',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Reader Blog Bookmark Removed',
        'm_desc' => '',
        'm_parents' => array(10888,10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Reader Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,6153,4506,4755,4593),
    ),
    7757 => array(
        'm_icon' => '<i class="fas fa-times-octagon ispink"></i>',
        'm_name' => 'Reader Blog Terminated',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,4593,6150),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle ispink"></i>',
        'm_name' => 'Reader Sent Location',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'Reader Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Reader Sent Text',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Interaction Unlock',
        'm_desc' => '',
        'm_parents' => array(10539,10589,6410,4229,4755,4593),
    ),
    7578 => array(
        'm_icon' => '<i class="far fa-key ispink"></i>',
        'm_name' => 'Reader Update Password',
        'm_desc' => '',
        'm_parents' => array(6222,10658,6153,10539,10589,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="far fa-sync ispink"></i>',
        'm_name' => 'Reader Update Profile',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,6222,4593),
    ),
    10672 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Thing Archived',
        'm_desc' => '',
        'm_parents' => array(10539,4593,10589,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Archived',
        'm_desc' => '',
        'm_parents' => array(10645,4593,10539,10589,10658),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-link rotate90 ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Basic',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Embed',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537,4506),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Integer',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    10656 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Status',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10539,10658,10645),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction URL',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537),
    ),
    10653 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Thing Update Icon',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
    10654 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing Update Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
);

//File Type Attachment:
$config['en_ids_10627'] = array(4554,4556,4555,4553,4549,4551,4550,4548,4259,4261,4260,4258);
$config['en_all_10627'] = array(
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
);

//Single Word + Connections:
$config['en_ids_10594'] = array(10664,10660,10573,7701,7545,10715,4228,4229,4318);
$config['en_all_10594'] = array(
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Blog Interaction Update Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    10660 => array(
        'm_icon' => '<i class="fas fa-angle-double-right ispink"></i>',
        'm_name' => 'Blog Migrate Parent Blog',
        'm_desc' => '',
        'm_parents' => array(7703,4593,10638,10589,10594,10658),
    ),
    10573 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Blog Note Bookmarks',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'Blog Note Followers',
        'm_desc' => '',
        'm_parents' => array(5007,10594,10589,4527,7703,4593,7551,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="far fa-tags ispink"></i>',
        'm_name' => 'Blog Note Tags',
        'm_desc' => '',
        'm_parents' => array(5007,10594,10589,7703,7551,4593,4485),
    ),
    10715 => array(
        'm_icon' => '<i class="far fa-folder-tree ispink"></i>',
        'm_name' => 'Blog Note Topics',
        'm_desc' => '',
        'm_parents' => array(5007,7703,7551,10589,10594,4593,4485),
    ),
    4228 => array(
        'm_icon' => '<i class="far fa-link rotate90 ispink"></i>',
        'm_name' => 'Blog-to-Blog Interaction Basic',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,6410,4593,4486),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Blog-to-Blog Interaction Conditional',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Time',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,4592),
    ),
);

//Statement + Connections:
$config['en_ids_10593'] = array(4250,6093,4601,4231,4983,10679,10644,4554,7702,4570,4556,4555,6563,4552,4553,4549,4551,4550,4548,4251,4259,4261,4260,4255,10657,4258,10646);
$config['en_all_10593'] = array(
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Blog Created',
        'm_desc' => '',
        'm_parents' => array(10638,10593,10589,4593),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comments-alt ispink"></i>',
        'm_name' => 'Blog Note Discussions',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-search ispink"></i>',
        'm_name' => 'Blog Note Keywords',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Blog Note Messages',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Blog Note References',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Blog Notes Update Content',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Blog Update Title',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10593,4593,10638),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7702 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Received Blog Email',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    4570 => array(
        'm_icon' => '<i class="far fa-envelope ispink"></i>',
        'm_name' => 'Reader Received Email',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'Reader Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Reader Received Text',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Thing Created',
        'm_desc' => '',
        'm_parents' => array(10645,10593,10589,4593),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Text',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4593,4592),
    ),
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Content',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    10646 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Update Name',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10645),
    ),
);

//Nod:
$config['en_ids_10596'] = array(6226,7610,10681,10685,10647,4993,5007,6149,7495,6969,7542,4275,4283,6415,6559,6560,6556,6578,7611,5967,7563,10690,4266,4267,4282,10683,4577,4278,4279,4268,4287,7561,7564,7560,7559,7558,7488,7485,7741,7486,4559,7489,7492,6997,6157,7487,6143,6144,7562,10625,5943,5001,5865,4999,4998,5000,5981,5982,5003,10689,10659,4246,7504,4994);
$config['en_all_10596'] = array(
    6226 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Mass Update Statuses',
        'm_desc' => '',
        'm_parents' => array(10655,10596,10589,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Blog Read by Reader',
        'm_desc' => '',
        'm_parents' => array(10638,10596,10590,7765,4755,4593),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(10638,10589,10596,4755,4593,10658),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'Blog Submission Update by Reader',
        'm_desc' => '',
        'm_parents' => array(4755,10596,10589,4593,10638,10658),
    ),
    10647 => array(
        'm_icon' => '<i class="far fa-tools ispink"></i>',
        'm_name' => 'Blog Update Verb',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10638),
    ),
    4993 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Blog Viewed by Blogger',
        'm_desc' => '',
        'm_parents' => array(10638,10596,10590,4593),
    ),
    5007 => array(
        'm_icon' => '<i class="fas fa-expand-arrows ispink"></i>',
        'm_name' => 'Mench Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7368,4757,4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus ispink"></i>',
        'm_name' => 'Reader Blog Considered',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Reader Blog Recommended',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="fas fa-megaphone ispink"></i>',
        'm_name' => 'Reader Blog Recommended',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,4593,4755,6153),
    ),
    7542 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Reader Blog Required',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-search ispink"></i>',
        'm_name' => 'Reader Blog Search',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10589,6554,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Blogs Listed',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function ispink"></i>',
        'm_name' => 'Reader Cleared Action Plan',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,6418,4593,6414),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Next',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    7611 => array(
        'm_icon' => '<i class="fas fa-hand-pointer ispink"></i>',
        'm_name' => 'Reader Engaged Blog Post',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,7610,4755,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Interaction CC Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4506,4527,7569,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Magic-Interaction Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    10690 => array(
        'm_icon' => '<i class="fas fa-upload ispink"></i>',
        'm_name' => 'Reader Media Uploaded',
        'm_desc' => '',
        'm_parents' => array(6153,10596,10589,4593,10658),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Opened Profile',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,6222,4593),
    ),
    10683 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Read Email',
        'm_desc' => '',
        'm_parents' => array(6153,10658,10596,10589,4593,7654),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'Reader Sent Access',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Sent Messenger Read',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download ispink"></i>',
        'm_name' => 'Reader Sent Messenger Received',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag ispink"></i>',
        'm_name' => 'Reader Sent Postback',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Reader Sent Unknown Message',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin Generally',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin Success',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin with Blogion',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fas fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Signin with Email',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Signin with Messenger',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7488 => array(
        'm_icon' => '<i class="fas fa-calendar-times ispink"></i>',
        'm_name' => 'Reader Step Answer Timeout',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7741 => array(
        'm_icon' => '<i class="fas fa-times-circle ispink"></i>',
        'm_name' => 'Reader Step Blogion Terminated',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="fas fa-comments ispink"></i>',
        'm_name' => 'Reader Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-double ispink"></i>',
        'm_name' => 'Reader Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-square ispink"></i>',
        'm_name' => 'Reader Step Path Not Found',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6255,4755,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-question ispink"></i>',
        'm_name' => 'Reader Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-stopwatch ispink"></i>',
        'm_name' => 'Reader Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6143 => array(
        'm_icon' => '<i class="fas fa-fast-forward ispink"></i>',
        'm_name' => 'Reader Step Skipped',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6146,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'Reader Submission Required',
        'm_desc' => '',
        'm_parents' => array(10893,10596,10589,4527,7703,6255,6244,4755,6146,4593),
    ),
    7562 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Welcome Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    10625 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Thing Mass Icon Replace',
        'm_desc' => '',
        'm_parents' => array(10589,10596,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Thing Mass Icon Update',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Thing Mass Interaction Contents',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing Mass Interaction Status',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Mass Name Postfix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Mass Name Prefix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Mass Name Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fas fa-layer-plus ispink"></i>',
        'm_name' => 'Thing Mass Parent Add',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fas fa-layer-minus ispink"></i>',
        'm_name' => 'Thing Mass Parent Remove',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing Mass Status Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    10689 => array(
        'm_icon' => '<i class="fas fa-share-alt rotate90 ispink"></i>',
        'm_name' => 'Thing Merged into Thing',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10658,10645),
    ),
    10659 => array(
        'm_icon' => '<i class="fas fa-plug ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Type',
        'm_desc' => '',
        'm_parents' => array(10658,10589,4593,10596,10645),
    ),
    4246 => array(
        'm_icon' => '<i class="far fa-bug ispink"></i>',
        'm_name' => 'Trainer Bug Reports',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Trainer Review Trigger',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,4593),
    ),
    4994 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Trainer View Thing',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4593),
    ),
);

//Interactions by Readers:
$config['en_ids_10590'] = array(7610,4993,6149,6969,7495,7542,4283,7611,5967,7563,4282,4554,7702,4570,4556,4555,6563,4552,4553,7562,4994);
$config['en_all_10590'] = array(
    7610 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Blog Read by Reader',
        'm_desc' => '',
        'm_parents' => array(10638,10596,10590,7765,4755,4593),
    ),
    4993 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Blog Viewed by Blogger',
        'm_desc' => '',
        'm_parents' => array(10638,10596,10590,4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus ispink"></i>',
        'm_name' => 'Reader Blog Considered',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="fas fa-megaphone ispink"></i>',
        'm_name' => 'Reader Blog Recommended',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,4593,4755,6153),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Reader Blog Recommended',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    7542 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Reader Blog Required',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Blogs Listed',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7611 => array(
        'm_icon' => '<i class="fas fa-hand-pointer ispink"></i>',
        'm_name' => 'Reader Engaged Blog Post',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,7610,4755,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Interaction CC Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4506,4527,7569,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Magic-Interaction Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Opened Profile',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,6222,4593),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7702 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Received Blog Email',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    4570 => array(
        'm_icon' => '<i class="far fa-envelope ispink"></i>',
        'm_name' => 'Reader Received Email',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'Reader Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Reader Received Text',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7562 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Welcome Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    4994 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Trainer View Thing',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4593),
    ),
);

//Interactions by Bloggers:
$config['en_ids_10589'] = array(10671,4250,10675,10686,10663,10664,10661,10662,6226,10660,10573,6093,7701,4601,4231,4983,10676,10678,10679,10677,7545,10715,10681,10685,4228,4229,10650,10649,10648,10644,10651,10647,5007,6154,4235,6155,4275,6132,7757,6415,6559,6560,6556,6578,10690,4266,4267,10683,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7561,7564,7560,7559,7558,7488,7485,7741,7486,6140,4559,7489,7492,6997,6157,7487,6143,6144,7578,6224,10672,4251,10625,5943,5001,5865,4999,4998,5000,5981,5982,5003,10689,10673,4259,4230,4257,4261,4260,4319,4255,4318,10657,10656,10659,4256,4258,10653,10646,10654,4246,7504);
$config['en_all_10589'] = array(
    10671 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Archived',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,7703,10638),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Blog Created',
        'm_desc' => '',
        'm_parents' => array(10638,10593,10589,4593),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blogger Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(7703,10539,10589,4593,10658,10638),
    ),
    10686 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Interaction Archived',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10539,4593,10658,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-coin ispink"></i>',
        'm_name' => 'Blog Interaction Update Points',
        'm_desc' => '',
        'm_parents' => array(4228,7703,10539,10589,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Blog Interaction Update Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    10661 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Interaction Update Status',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Blog Interaction Update Type',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    6226 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Mass Update Statuses',
        'm_desc' => '',
        'm_parents' => array(10655,10596,10589,4593),
    ),
    10660 => array(
        'm_icon' => '<i class="fas fa-angle-double-right ispink"></i>',
        'm_name' => 'Blog Migrate Parent Blog',
        'm_desc' => '',
        'm_parents' => array(7703,4593,10638,10589,10594,10658),
    ),
    10573 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Blog Note Bookmarks',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comments-alt ispink"></i>',
        'm_name' => 'Blog Note Discussions',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'Blog Note Followers',
        'm_desc' => '',
        'm_parents' => array(5007,10594,10589,4527,7703,4593,7551,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-search ispink"></i>',
        'm_name' => 'Blog Note Keywords',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Blog Note Messages',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Blog Note References',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    10676 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blog Notes Sorted',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Notes Unlinked',
        'm_desc' => '',
        'm_parents' => array(10658,10539,10589,4593,10638),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Blog Notes Update Content',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Notes Update Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    7545 => array(
        'm_icon' => '<i class="far fa-tags ispink"></i>',
        'm_name' => 'Blog Note Tags',
        'm_desc' => '',
        'm_parents' => array(5007,10594,10589,7703,7551,4593,4485),
    ),
    10715 => array(
        'm_icon' => '<i class="far fa-folder-tree ispink"></i>',
        'm_name' => 'Blog Note Topics',
        'm_desc' => '',
        'm_parents' => array(5007,7703,7551,10589,10594,4593,4485),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(10638,10589,10596,4755,4593,10658),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'Blog Submission Update by Reader',
        'm_desc' => '',
        'm_parents' => array(4755,10596,10589,4593,10638,10658),
    ),
    4228 => array(
        'm_icon' => '<i class="far fa-link rotate90 ispink"></i>',
        'm_name' => 'Blog-to-Blog Interaction Basic',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,6410,4593,4486),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Blog-to-Blog Interaction Conditional',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
    10650 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Blog Update Completion Time',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10649 => array(
        'm_icon' => '<i class="fas fa-triangle ispink"></i>',
        'm_name' => 'Blog Update Scope',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10648 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Update Status',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Blog Update Title',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10593,4593,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="far fa-shapes ispink"></i>',
        'm_name' => 'Blog Update Type',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10647 => array(
        'm_icon' => '<i class="far fa-tools ispink"></i>',
        'm_name' => 'Blog Update Verb',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10638),
    ),
    5007 => array(
        'm_icon' => '<i class="fas fa-expand-arrows ispink"></i>',
        'm_name' => 'Mench Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7368,4757,4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'Reader Blog Accomplished',
        'm_desc' => '',
        'm_parents' => array(10888,10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Reader Blog Bookmarked',
        'm_desc' => '',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Reader Blog Bookmark Removed',
        'm_desc' => '',
        'm_parents' => array(10888,10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-search ispink"></i>',
        'm_name' => 'Reader Blog Search',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10589,6554,4755,4593),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Reader Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,6153,4506,4755,4593),
    ),
    7757 => array(
        'm_icon' => '<i class="fas fa-times-octagon ispink"></i>',
        'm_name' => 'Reader Blog Terminated',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,4593,6150),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function ispink"></i>',
        'm_name' => 'Reader Cleared Action Plan',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,6418,4593,6414),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Next',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    10690 => array(
        'm_icon' => '<i class="fas fa-upload ispink"></i>',
        'm_name' => 'Reader Media Uploaded',
        'm_desc' => '',
        'm_parents' => array(6153,10596,10589,4593,10658),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    10683 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Read Email',
        'm_desc' => '',
        'm_parents' => array(6153,10658,10596,10589,4593,7654),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'Reader Sent Access',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle ispink"></i>',
        'm_name' => 'Reader Sent Location',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Sent Messenger Read',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download ispink"></i>',
        'm_name' => 'Reader Sent Messenger Received',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag ispink"></i>',
        'm_name' => 'Reader Sent Postback',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'Reader Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Reader Sent Text',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Reader Sent Unknown Message',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin Generally',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin Success',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin with Blogion',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fas fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Signin with Email',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Signin with Messenger',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7488 => array(
        'm_icon' => '<i class="fas fa-calendar-times ispink"></i>',
        'm_name' => 'Reader Step Answer Timeout',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7741 => array(
        'm_icon' => '<i class="fas fa-times-circle ispink"></i>',
        'm_name' => 'Reader Step Blogion Terminated',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Interaction Unlock',
        'm_desc' => '',
        'm_parents' => array(10539,10589,6410,4229,4755,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="fas fa-comments ispink"></i>',
        'm_name' => 'Reader Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-double ispink"></i>',
        'm_name' => 'Reader Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-square ispink"></i>',
        'm_name' => 'Reader Step Path Not Found',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6255,4755,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-question ispink"></i>',
        'm_name' => 'Reader Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-stopwatch ispink"></i>',
        'm_name' => 'Reader Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6143 => array(
        'm_icon' => '<i class="fas fa-fast-forward ispink"></i>',
        'm_name' => 'Reader Step Skipped',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6146,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'Reader Submission Required',
        'm_desc' => '',
        'm_parents' => array(10893,10596,10589,4527,7703,6255,6244,4755,6146,4593),
    ),
    7578 => array(
        'm_icon' => '<i class="far fa-key ispink"></i>',
        'm_name' => 'Reader Update Password',
        'm_desc' => '',
        'm_parents' => array(6222,10658,6153,10539,10589,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="far fa-sync ispink"></i>',
        'm_name' => 'Reader Update Profile',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,6222,4593),
    ),
    10672 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Thing Archived',
        'm_desc' => '',
        'm_parents' => array(10539,4593,10589,10645),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Thing Created',
        'm_desc' => '',
        'm_parents' => array(10645,10593,10589,4593),
    ),
    10625 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Thing Mass Icon Replace',
        'm_desc' => '',
        'm_parents' => array(10589,10596,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Thing Mass Icon Update',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Thing Mass Interaction Contents',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing Mass Interaction Status',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Mass Name Postfix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Mass Name Prefix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Mass Name Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fas fa-layer-plus ispink"></i>',
        'm_name' => 'Thing Mass Parent Add',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fas fa-layer-minus ispink"></i>',
        'm_name' => 'Thing Mass Parent Remove',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing Mass Status Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    10689 => array(
        'm_icon' => '<i class="fas fa-share-alt rotate90 ispink"></i>',
        'm_name' => 'Thing Merged into Thing',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10658,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Archived',
        'm_desc' => '',
        'm_parents' => array(10645,4593,10539,10589,10658),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-link rotate90 ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Basic',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Embed',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537,4506),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Integer',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Text',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Time',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,4592),
    ),
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Content',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    10656 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Status',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10539,10658,10645),
    ),
    10659 => array(
        'm_icon' => '<i class="fas fa-plug ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Type',
        'm_desc' => '',
        'm_parents' => array(10658,10589,4593,10596,10645),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction URL',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    10653 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Thing Update Icon',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
    10646 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Update Name',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10645),
    ),
    10654 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing Update Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
    4246 => array(
        'm_icon' => '<i class="far fa-bug ispink"></i>',
        'm_name' => 'Trainer Bug Reports',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Trainer Review Trigger',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,4593),
    ),
);

//Interaction Type Reader Set Blogion:
$config['en_ids_7347'] = array(4235,7495,7542);
$config['en_all_7347'] = array(
    4235 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Blog Bookmarked',
        'm_desc' => '',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Blog Recommended',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    7542 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Blog Required',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
);

//Blog AND:
$config['en_ids_6192'] = array(6914,7637,7636,6677,6683,6682,6679,6680,6678,6681);
$config['en_all_6192'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes "></i>',
        'm_name' => 'Read All Children',
        'm_desc' => '',
        'm_parents' => array(6192,7756,7585,7486,7485,7309,6997),
    ),
    7637 => array(
        'm_icon' => '<i class="fas fa-film "></i>',
        'm_name' => 'Submit Multimedia',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    7636 => array(
        'm_icon' => '<i class="fas fa-calendar-plus "></i>',
        'm_name' => 'Submit Schedule',
        'm_desc' => '',
        'm_parents' => array(7585,6192),
    ),
    6677 => array(
        'm_icon' => '<i class="fas fa-comments "></i>',
        'm_name' => 'Read Only',
        'm_desc' => '',
        'm_parents' => array(7756,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="fas fa-keyboard "></i>',
        'm_name' => 'Submit Text',
        'm_desc' => '',
        'm_parents' => array(7585,6144,6192),
    ),
    6682 => array(
        'm_icon' => '<i class="fas fa-external-link "></i>',
        'm_name' => 'Submit URL',
        'm_desc' => '',
        'm_parents' => array(7585,6144,6192),
    ),
    6679 => array(
        'm_icon' => '<i class="fas fa-video "></i>',
        'm_name' => 'Submit Video',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6680 => array(
        'm_icon' => '<i class="fas fa-microphone "></i>',
        'm_name' => 'Submit Audio',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6678 => array(
        'm_icon' => '<i class="fas fa-image "></i>',
        'm_name' => 'Submit Image',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6681 => array(
        'm_icon' => '<i class="fas fa-file-pdf "></i>',
        'm_name' => 'Submit File',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
);

//Blog Type Groups:
$config['en_ids_10602'] = array(6192,6193);
$config['en_all_10602'] = array(
    6192 => array(
        'm_icon' => '<i class="far fa-sitemap "></i>',
        'm_name' => 'AND',
        'm_desc' => 'AND Intents are completed when ALL their children are complete',
        'm_parents' => array(4527,10602),
    ),
    6193 => array(
        'm_icon' => '<i class="far fa-code-merge "></i>',
        'm_name' => 'OR',
        'm_desc' => 'OR Intents are completed when ANY of their children are complete',
        'm_parents' => array(10602,4527),
    ),
);

//Interaction Word Weight:
$config['en_ids_10592'] = array(10596,10539,10594,10593);
$config['en_all_10592'] = array(
    10596 => array(
        'm_icon' => '<i class="fas fa-square-root"></i>',
        'm_name' => 'Nod',
        'm_desc' => 'A fraction of a word',
        'm_parents' => array(4527,10592),
    ),
    10539 => array(
        'm_icon' => '<i class="far fa-file-word"></i>',
        'm_name' => 'Single Word',
        'm_desc' => 'A single word only',
        'm_parents' => array(4527,10592,5008),
    ),
    10594 => array(
        'm_icon' => '<i class="fas fa-value-absolute"></i>',
        'm_name' => 'Single Word + Connections',
        'm_desc' => 'A single word plus connections',
        'm_parents' => array(4527,10592),
    ),
    10593 => array(
        'm_icon' => '<i class="fas fa-union"></i>',
        'm_name' => 'Statement + Connections',
        'm_desc' => 'Multiple words based on content plus connections',
        'm_parents' => array(4527,10592),
    ),
);

//Interaction Reader Groups:
$config['en_ids_10591'] = array(10589,10590);
$config['en_all_10591'] = array(
    10589 => array(
        'm_icon' => '<i class="far fa-user-edit ispink"></i>',
        'm_name' => 'Bloggers',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
    10590 => array(
        'm_icon' => '<i class="far fa-user ispink"></i>',
        'm_name' => 'Readers',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
);

//Public Entities:
$config['en_ids_10571'] = array(2997,4446,3005,4763,3147,2999,4883,3192,5948,2998,7512,1308,3084,3000);
$config['en_all_10571'] = array(
    2997 => array(
        'm_icon' => '<i class="far fa-newspaper"></i>',
        'm_name' => 'Expert Articles',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="far fa-tachometer"></i>',
        'm_name' => 'Expert Assessments',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="far fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="far fa-megaphone"></i>',
        'm_name' => 'Expert Channels',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="far fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="far fa-microphone"></i>',
        'm_name' => 'Expert Podcasts',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4883 => array(
        'm_icon' => '<i class="far fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="far fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="far fa-file-invoice"></i>',
        'm_name' => 'Expert Templates',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="far fa-film"></i>',
        'm_name' => 'Expert Videos',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    7512 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isorange"></i>',
        'm_name' => 'Mench Blogger Level 1',
        'm_desc' => '',
        'm_parents' => array(7701,4983,10606,10691,10573,10571),
    ),
    1308 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isnavy"></i>',
        'm_name' => 'Mench Blogger Level 2',
        'm_desc' => '',
        'm_parents' => array(10573,7701,4983,10704,10618,10691,10571,4463,4426),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut"></i>',
        'm_name' => 'Mench Experts',
        'm_desc' => '',
        'm_parents' => array(10571,4983,6827,4463),
    ),
    3000 => array(
        'm_icon' => '<i class="far fa-whistle"></i>',
        'm_name' => 'Trained Expert Sources',
        'm_desc' => '',
        'm_parents' => array(10571,7303,4506,4527,4463),
    ),
);

//Reader Selectable Completion:
$config['en_ids_10570'] = array(6154,6155);
$config['en_all_10570'] = array(
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'Blog Accomplished',
        'm_desc' => 'You successfully accomplished your intention so you no longer want to receive future updates',
        'm_parents' => array(10888,10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Blog Bookmark Removed',
        'm_desc' => 'You did NOT accomplish the intention and you want to stop all future updates on this intention',
        'm_parents' => array(10888,10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
);

//Searchable by Reader/Blogger:
$config['en_ids_10568'] = array(7598);
$config['en_all_10568'] = array(
    7598 => array(
        'm_icon' => '<i class="far fa-newspaper"></i>',
        'm_name' => 'Blog Post',
        'm_desc' => '',
        'm_parents' => array(10568,7767,7582,7596),
    ),
);

//Verb Skipping Allowed by Blogger:
$config['en_ids_10567'] = array(7766,7597);
$config['en_all_10567'] = array(
    7766 => array(
        'm_icon' => '<i class="far fa-scroll"></i>',
        'm_name' => 'Blog Page',
        'm_desc' => '',
        'm_parents' => array(10567,7767,7596),
    ),
    7597 => array(
        'm_icon' => '<i class="far fa-comment-alt-lines"></i>',
        'm_name' => 'Blog Snippet',
        'm_desc' => '',
        'm_parents' => array(10567,7596),
    ),
);

//Leaderboard Time Frames:
$config['en_ids_7799'] = array(7802,7801);
$config['en_all_7799'] = array(
    7802 => array(
        'm_icon' => '',
        'm_name' => 'All Time',
        'm_desc' => '',
        'm_parents' => array(7799),
    ),
    7801 => array(
        'm_icon' => '',
        'm_name' => 'This Week',
        'm_desc' => '',
        'm_parents' => array(7799),
    ),
);

//Algolia Indexable:
$config['en_ids_7774'] = array(6183,6184,6180,6181,6175,6176);
$config['en_all_7774'] = array(
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin "></i>',
        'm_name' => 'Blog Drafting',
        'm_desc' => '',
        'm_parents' => array(7774,7356,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-globe yellow "></i>',
        'm_name' => 'Blog Published',
        'm_desc' => '',
        'm_parents' => array(7774,7355,7356,4737),
    ),
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'Entity Drafting',
        'm_desc' => '',
        'm_parents' => array(7774,7358,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-globe blue"></i>',
        'm_name' => 'Entity Published',
        'm_desc' => '',
        'm_parents' => array(7774,7358,7357,6177),
    ),
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'Link Drafting',
        'm_desc' => '',
        'm_parents' => array(7774,7364,7360,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-globe ispink"></i>',
        'm_name' => 'Link Published',
        'm_desc' => '',
        'm_parents' => array(7774,7360,7359,6186),
    ),
);

//Reusable by Blogger:
$config['en_ids_7767'] = array(7598,7766);
$config['en_all_7767'] = array(
    7598 => array(
        'm_icon' => '<i class="far fa-newspaper"></i>',
        'm_name' => 'Post',
        'm_desc' => '',
        'm_parents' => array(10568,7767,7582,7596),
    ),
    7766 => array(
        'm_icon' => '<i class="far fa-scroll"></i>',
        'm_name' => 'Page',
        'm_desc' => '',
        'm_parents' => array(10567,7767,7596),
    ),
);

//Blog Note Followers:
$config['en_ids_7701'] = array(7703,7512,1308,4430);
$config['en_all_7701'] = array(
    7703 => array(
        'm_icon' => '<i class="fas fa-rss"></i>',
        'm_name' => 'Interaction Blog Subscription Types',
        'm_desc' => '',
        'm_parents' => array(7701,4527,6771),
    ),
    7512 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isorange"></i>',
        'm_name' => 'Mench Blogger Level 1',
        'm_desc' => '',
        'm_parents' => array(7701,4983,10606,10691,10573,10571),
    ),
    1308 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isnavy"></i>',
        'm_name' => 'Mench Blogger Level 2',
        'm_desc' => '',
        'm_parents' => array(10573,7701,4983,10704,10618,10691,10571,4463,4426),
    ),
    4430 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Mench Readers',
        'm_desc' => '',
        'm_parents' => array(4983,7701,7369,6827,4426,4463),
    ),
);

//Blog Note References:
$config['en_ids_4983'] = array(2997,4446,3005,4763,3147,2999,4883,3192,5948,2998,7512,1308,3084,4430);
$config['en_all_4983'] = array(
    2997 => array(
        'm_icon' => '<i class="far fa-newspaper"></i>',
        'm_name' => 'Expert Articles',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="far fa-tachometer"></i>',
        'm_name' => 'Expert Assessments',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="far fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="far fa-megaphone"></i>',
        'm_name' => 'Expert Channels',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="far fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="far fa-microphone"></i>',
        'm_name' => 'Expert Podcasts',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4883 => array(
        'm_icon' => '<i class="far fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="far fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="far fa-file-invoice"></i>',
        'm_name' => 'Expert Templates',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="far fa-film"></i>',
        'm_name' => 'Expert Videos',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    7512 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isorange"></i>',
        'm_name' => 'Mench Blogger Level 1',
        'm_desc' => '',
        'm_parents' => array(7701,4983,10606,10691,10573,10571),
    ),
    1308 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isnavy"></i>',
        'm_name' => 'Mench Blogger Level 2',
        'm_desc' => '',
        'm_parents' => array(10573,7701,4983,10704,10618,10691,10571,4463,4426),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut"></i>',
        'm_name' => 'Mench Experts',
        'm_desc' => '',
        'm_parents' => array(10571,4983,6827,4463),
    ),
    4430 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Mench Readers',
        'm_desc' => '',
        'm_parents' => array(4983,7701,7369,6827,4426,4463),
    ),
);

//Action Plan Blogion Successful:
$config['en_ids_7758'] = array(6154);
$config['en_all_7758'] = array(
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'Reader Blog Accomplished',
        'm_desc' => '',
        'm_parents' => array(10888,10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
);

//Blog Auto Completable:
$config['en_ids_7756'] = array(6914,6907,6677);
$config['en_all_7756'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes "></i>',
        'm_name' => 'Blog Read All Children',
        'm_desc' => '',
        'm_parents' => array(6192,7756,7585,7486,7485,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube "></i>',
        'm_name' => 'Blog Read Any Child',
        'm_desc' => '',
        'm_parents' => array(7756,7585,7486,7485,7309,6997,6193),
    ),
    6677 => array(
        'm_icon' => '<i class="fas fa-comments "></i>',
        'm_name' => 'Blog Read Only',
        'm_desc' => '',
        'm_parents' => array(7756,7585,4559,6192),
    ),
);

//Blog Upload File:
$config['en_ids_7751'] = array(6680,6681,6678,7637,6679);
$config['en_all_7751'] = array(
    6680 => array(
        'm_icon' => '<i class="fas fa-microphone "></i>',
        'm_name' => 'Blog Submit Audio',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6681 => array(
        'm_icon' => '<i class="fas fa-file-pdf "></i>',
        'm_name' => 'Blog Submit File',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6678 => array(
        'm_icon' => '<i class="fas fa-image "></i>',
        'm_name' => 'Blog Submit Image',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="fas fa-film "></i>',
        'm_name' => 'Blog Submit Multimedia',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6679 => array(
        'm_icon' => '<i class="fas fa-video "></i>',
        'm_name' => 'Blog Submit Video',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
);

//Reader Submission Required:
$config['en_ids_6144'] = array(6680,6681,6678,7637,6683,6682,6679);
$config['en_all_6144'] = array(
    6680 => array(
        'm_icon' => '<i class="fas fa-microphone "></i>',
        'm_name' => 'Submit Audio',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6681 => array(
        'm_icon' => '<i class="fas fa-file-pdf "></i>',
        'm_name' => 'Submit File',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6678 => array(
        'm_icon' => '<i class="fas fa-image "></i>',
        'm_name' => 'Submit Image',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="fas fa-film "></i>',
        'm_name' => 'Submit Multimedia',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="fas fa-keyboard "></i>',
        'm_name' => 'Submit Text',
        'm_desc' => '',
        'm_parents' => array(7585,6144,6192),
    ),
    6682 => array(
        'm_icon' => '<i class="fas fa-external-link "></i>',
        'm_name' => 'Submit URL',
        'm_desc' => '',
        'm_parents' => array(7585,6144,6192),
    ),
    6679 => array(
        'm_icon' => '<i class="fas fa-video "></i>',
        'm_name' => 'Submit Video',
        'm_desc' => '',
        'm_parents' => array(7751,7585,6144,6192),
    ),
);

//Interaction Metadata:
$config['en_ids_6103'] = array(4358,6402,6203);
$config['en_all_6103'] = array(
    4358 => array(
        'm_icon' => '<i class="far fa-coin"></i>',
        'm_name' => 'Blog Points',
        'm_desc' => '',
        'm_parents' => array(10663,6103,6410,6232),
    ),
    6402 => array(
        'm_icon' => '<i class="fas fa-bolt"></i>',
        'm_name' => 'Condition Score Range',
        'm_desc' => '',
        'm_parents' => array(10664,6103,6410),
    ),
    6203 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'Facebook Attachment ID',
        'm_desc' => 'For media files such as videos, audios, images and other files, we cache them with the Facebook Server so we can instantly deliver them to students. This variables in the link metadata is where we store the attachment ID. See the children to better understand which links types support this caching feature.',
        'm_parents' => array(6232,6215,2793,6103),
    ),
);

//Interaction Table:
$config['en_ids_4341'] = array(6103,6186,4593,4429,4369,4372,4364,7694,4367,4370,4366,4368,4371,4362,10588);
$config['en_all_4341'] = array(
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'Metadata',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h"></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(10677,10661,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug"></i>',
        'm_name' => 'Type',
        'm_desc' => '',
        'm_parents' => array(10607,10659,6160,6232,6194,4527,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Link Child Entity',
        'm_desc' => '',
        'm_parents' => array(10692,6160,6232,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Link Child Intent',
        'm_desc' => '',
        'm_parents' => array(10692,6202,6232,4341),
    ),
    4372 => array(
        'm_icon' => '<i class="fas fa-sticky-note"></i>',
        'm_name' => 'Link Content',
        'm_desc' => '',
        'm_parents' => array(7578,10679,10657,5001,6232,4341),
    ),
    4364 => array(
        'm_icon' => '<i class="far fa-user-edit"></i>',
        'm_name' => 'Link Creator',
        'm_desc' => '',
        'm_parents' => array(6160,6232,6194,4341),
    ),
    7694 => array(
        'm_icon' => '<i class="fas fa-project-diagram"></i>',
        'm_name' => 'Link External ID',
        'm_desc' => '',
        'm_parents' => array(6215,6232,4341),
    ),
    4367 => array(
        'm_icon' => '<i class="fas fa-link rotate90"></i>',
        'm_name' => 'Link ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fas fa-sort"></i>',
        'm_name' => 'Link Order',
        'm_desc' => 'tr_order empowers the arrangement or disposition of intents, entities or transactions in relation to each other according to a particular sequence, pattern, or method defined by Miners or Masters.',
        'm_parents' => array(10676,10675,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Link Parent Entity',
        'm_desc' => '',
        'm_parents' => array(10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Link Parent Intent',
        'm_desc' => '',
        'm_parents' => array(10692,6202,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Link Parent Link',
        'm_desc' => '',
        'm_parents' => array(10692,4367,6232,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="far fa-clock"></i>',
        'm_name' => 'Link Timestamp',
        'm_desc' => '',
        'm_parents' => array(6232,4341),
    ),
    10588 => array(
        'm_icon' => '<i class="fas fa-file-word"></i>',
        'm_name' => 'Link Words',
        'm_desc' => '',
        'm_parents' => array(6214,4506,4341),
    ),
);

//Thing Table:
$config['en_ids_6206'] = array(6198,6160,6172,6197,6177,6199);
$config['en_all_6206'] = array(
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle"></i>',
        'm_name' => 'Icon',
        'm_desc' => '',
        'm_parents' => array(10653,5943,10625,6232,6206),
    ),
    6160 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda"></i>',
        'm_name' => 'Metadata',
        'm_desc' => '',
        'm_parents' => array(6232,3323,6206,6195),
    ),
    6197 => array(
        'm_icon' => '<i class="fas fa-fingerprint"></i>',
        'm_name' => 'Name',
        'm_desc' => '',
        'm_parents' => array(10646,5000,4998,4999,6232,6225,6206),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h"></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(6204,5003,10654,6160,6232,7303,6194,6206,4527),
    ),
    6199 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Trust Score',
        'm_desc' => '',
        'm_parents' => array(6232,4463,6214,6206),
    ),
);

//Blog Table:
$config['en_ids_6201'] = array(6202,6159,4356,7596,4737,4736,7585,5008);
$config['en_all_6201'] = array(
    6202 => array(
        'm_icon' => '<i class="fas fa-hashtag "></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6201),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda "></i>',
        'm_name' => 'Metadata',
        'm_desc' => 'Intent metadata contains variables that have been automatically calculated and automatically updates using a cron job. Intent Metadata are the backbone of key functions and user interfaces like the intent landing page or Action Plan completion workflows.',
        'm_parents' => array(6232,6201,6195),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch"></i>',
        'm_name' => 'Read Time',
        'm_desc' => '',
        'm_parents' => array(10650,6232,6201),
    ),
    7596 => array(
        'm_icon' => '<i class="fas fa-triangle"></i>',
        'm_name' => 'Scope',
        'm_desc' => '',
        'm_parents' => array(6204,10649,7302,6160,6201,6194,6232,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h "></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(6204,10648,6226,6160,6232,7302,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1"></i>',
        'm_name' => 'Title',
        'm_desc' => '',
        'm_parents' => array(10644,6232,6201),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-puzzle-piece"></i>',
        'm_name' => 'Type',
        'm_desc' => '',
        'm_parents' => array(10608,6204,10651,6160,6194,6232,4527,6201),
    ),
    5008 => array(
        'm_icon' => '<i class="fas fa-tools "></i>',
        'm_name' => 'Verb',
        'm_desc' => '',
        'm_parents' => array(6204,10647,4736,7777,6160,6232,7302,4506,6194,6201),
    ),
);

//Single Selectable:
$config['en_ids_6204'] = array(7596,4737,7585,10602,5008,3290,10591,6186,10592,4454,6177,3289);
$config['en_all_6204'] = array(
    7596 => array(
        'm_icon' => '<i class="fas fa-triangle"></i>',
        'm_name' => 'Blog Scope',
        'm_desc' => '',
        'm_parents' => array(6204,10649,7302,6160,6201,6194,6232,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h "></i>',
        'm_name' => 'Blog Status',
        'm_desc' => '',
        'm_parents' => array(6204,10648,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-puzzle-piece"></i>',
        'm_name' => 'Blog Type',
        'm_desc' => '',
        'm_parents' => array(10608,6204,10651,6160,6194,6232,4527,6201),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece"></i>',
        'm_name' => 'Blog Type Groups',
        'm_desc' => '',
        'm_parents' => array(10893,6204,7302,4527),
    ),
    5008 => array(
        'm_icon' => '<i class="fas fa-tools "></i>',
        'm_name' => 'Blog Verb',
        'm_desc' => '',
        'm_parents' => array(6204,10647,4736,7777,6160,6232,7302,4506,6194,6201),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender"></i>',
        'm_name' => 'Genders',
        'm_desc' => '',
        'm_parents' => array(6225,6204),
    ),
    10591 => array(
        'm_icon' => '<i class="fas fa-directions"></i>',
        'm_name' => 'Interaction Reader Groups',
        'm_desc' => '',
        'm_parents' => array(6204,7797,7304,6771,4527,10588),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h"></i>',
        'm_name' => 'Interaction Status',
        'm_desc' => '',
        'm_parents' => array(10677,10661,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    10592 => array(
        'm_icon' => '<i class="fas fa-weight"></i>',
        'm_name' => 'Interaction Word Weight',
        'm_desc' => '',
        'm_parents' => array(6204,6771,4527,10588),
    ),
    4454 => array(
        'm_icon' => '<i class="far fa-bells"></i>',
        'm_name' => 'Subscription Settings',
        'm_desc' => '',
        'm_parents' => array(7552,6225,6204,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h"></i>',
        'm_name' => 'Thing Status',
        'm_desc' => '',
        'm_parents' => array(6204,5003,10654,6160,6232,7303,6194,6206,4527),
    ),
    3289 => array(
        'm_icon' => '<i class="far fa-map"></i>',
        'm_name' => 'Timezones',
        'm_desc' => '',
        'm_parents' => array(6204,6225),
    ),
);

//Blog Answer Types:
$config['en_ids_7712'] = array(7231,6684,6685);
$config['en_all_7712'] = array(
    7231 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'Blog Choose Multiple Childred',
        'm_desc' => '',
        'm_parents' => array(7712,7489,7588,7585,6193),
    ),
    6684 => array(
        'm_icon' => '<i class="fas fa-question"></i>',
        'm_name' => 'Blog Choose Single Child',
        'm_desc' => '',
        'm_parents' => array(7712,7588,7585,6157,6193),
    ),
    6685 => array(
        'm_icon' => '<i class="fas fa-stopwatch "></i>',
        'm_name' => 'Blog Choose Single Child Timed',
        'm_desc' => '',
        'm_parents' => array(7712,7488,7487,7585,7366,6193),
    ),
);

//Reader Step Answered Successfully:
$config['en_ids_7704'] = array(7489,6157,7487);
$config['en_all_7704'] = array(
    7489 => array(
        'm_icon' => '<i class="fas fa-check-double ispink"></i>',
        'm_name' => 'Reader Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-question ispink"></i>',
        'm_name' => 'Reader Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-stopwatch ispink"></i>',
        'm_name' => 'Reader Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
);

//Interaction Blog Subscription Types:
$config['en_ids_7703'] = array(10671,10675,10686,10663,10664,10661,10662,10660,10573,6093,7701,4601,4231,4983,7545,10715,4228,4229,10650,10649,10648,10644,10651,6154,6155,7485,7486,4559,7489,6997,6157,7487,6144);
$config['en_all_7703'] = array(
    10671 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Archived',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,7703,10638),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blogger Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(7703,10539,10589,4593,10658,10638),
    ),
    10686 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Interaction Archived',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10539,4593,10658,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-coin ispink"></i>',
        'm_name' => 'Blog Interaction Update Points',
        'm_desc' => '',
        'm_parents' => array(4228,7703,10539,10589,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Blog Interaction Update Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    10661 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Interaction Update Status',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Blog Interaction Update Type',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10660 => array(
        'm_icon' => '<i class="fas fa-angle-double-right ispink"></i>',
        'm_name' => 'Blog Migrate Parent Blog',
        'm_desc' => '',
        'm_parents' => array(7703,4593,10638,10589,10594,10658),
    ),
    10573 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Blog Note Bookmarks',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comments-alt ispink"></i>',
        'm_name' => 'Blog Note Discussions',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'Blog Note Followers',
        'm_desc' => '',
        'm_parents' => array(5007,10594,10589,4527,7703,4593,7551,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-search ispink"></i>',
        'm_name' => 'Blog Note Keywords',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Blog Note Messages',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Blog Note References',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="far fa-tags ispink"></i>',
        'm_name' => 'Blog Note Tags',
        'm_desc' => '',
        'm_parents' => array(5007,10594,10589,7703,7551,4593,4485),
    ),
    10715 => array(
        'm_icon' => '<i class="far fa-folder-tree ispink"></i>',
        'm_name' => 'Blog Note Topics',
        'm_desc' => '',
        'm_parents' => array(5007,7703,7551,10589,10594,4593,4485),
    ),
    4228 => array(
        'm_icon' => '<i class="far fa-link rotate90 ispink"></i>',
        'm_name' => 'Blog-to-Blog Interaction Basic',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,6410,4593,4486),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Blog-to-Blog Interaction Conditional',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
    10650 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Blog Update Completion Time',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10649 => array(
        'm_icon' => '<i class="fas fa-triangle ispink"></i>',
        'm_name' => 'Blog Update Scope',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10648 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Update Status',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Blog Update Title',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10593,4593,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="far fa-shapes ispink"></i>',
        'm_name' => 'Blog Update Type',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'Reader Blog Accomplished',
        'm_desc' => '',
        'm_parents' => array(10888,10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Reader Blog Bookmark Removed',
        'm_desc' => '',
        'm_parents' => array(10888,10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="fas fa-comments ispink"></i>',
        'm_name' => 'Reader Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-double ispink"></i>',
        'm_name' => 'Reader Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-question ispink"></i>',
        'm_name' => 'Reader Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-stopwatch ispink"></i>',
        'm_name' => 'Reader Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'Reader Submission Required',
        'm_desc' => '',
        'm_parents' => array(10893,10596,10589,4527,7703,6255,6244,4755,6146,4593),
    ),
);

//Blog Scope:
$config['en_ids_7596'] = array(7597,7766,7598);
$config['en_all_7596'] = array(
    7597 => array(
        'm_icon' => '<i class="far fa-comment-alt-lines"></i>',
        'm_name' => 'Snippet',
        'm_desc' => 'Only accessible through parent blogs',
        'm_parents' => array(10567,7596),
    ),
    7766 => array(
        'm_icon' => '<i class="far fa-scroll"></i>',
        'm_name' => 'Page',
        'm_desc' => 'Searchable by readers, Reusable by bloggers and accessible through parent blogs',
        'm_parents' => array(10567,7767,7596),
    ),
    7598 => array(
        'm_icon' => '<i class="far fa-newspaper"></i>',
        'm_name' => 'Post',
        'm_desc' => 'Searchable by readers, reusable by bloggers, accessible through parent blogs & requires readers to sign-in to continue',
        'm_parents' => array(10568,7767,7582,7596),
    ),
);

//Blog-to-Blog Interaction Conditional:
$config['en_ids_4229'] = array(10664,6140,6997);
$config['en_all_4229'] = array(
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Blog Interaction Update Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Interaction Unlock',
        'm_desc' => 'A step that has become available because of the score generated from student answers',
        'm_parents' => array(10539,10589,6410,4229,4755,4593),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
);

//Blog Select Publicly:
$config['en_ids_7588'] = array(7231,6684);
$config['en_all_7588'] = array(
    7231 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'Blog Choose Multiple Childred',
        'm_desc' => '',
        'm_parents' => array(7712,7489,7588,7585,6193),
    ),
    6684 => array(
        'm_icon' => '<i class="fas fa-question"></i>',
        'm_name' => 'Blog Choose Single Child',
        'm_desc' => '',
        'm_parents' => array(7712,7588,7585,6157,6193),
    ),
);

//Blog OR:
$config['en_ids_6193'] = array(6684,6685,7231,6907);
$config['en_all_6193'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-question"></i>',
        'm_name' => 'Choose Single Child',
        'm_desc' => '',
        'm_parents' => array(7712,7588,7585,6157,6193),
    ),
    6685 => array(
        'm_icon' => '<i class="fas fa-stopwatch "></i>',
        'm_name' => 'Choose Single Child Timed',
        'm_desc' => '',
        'm_parents' => array(7712,7488,7487,7585,7366,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'Choose Multiple Childred',
        'm_desc' => '',
        'm_parents' => array(7712,7489,7588,7585,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube "></i>',
        'm_name' => 'Read Any Child',
        'm_desc' => '',
        'm_parents' => array(7756,7585,7486,7485,7309,6997,6193),
    ),
);

//Blog Type:
$config['en_ids_7585'] = array(7636,6677,6683,6682,6680,6678,6679,7637,6681,6684,6685,7231,6907,6914);
$config['en_all_7585'] = array(
    7636 => array(
        'm_icon' => '<i class="fas fa-calendar-plus "></i>',
        'm_name' => 'Submit Schedule',
        'm_desc' => '',
        'm_parents' => array(7585,6192),
    ),
    6677 => array(
        'm_icon' => '<i class="fas fa-comments "></i>',
        'm_name' => 'Read Only',
        'm_desc' => 'User will complete by reading intent messages only. No inputs required.',
        'm_parents' => array(7756,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="fas fa-keyboard "></i>',
        'm_name' => 'Submit Text',
        'm_desc' => 'User will complete by sending a text message',
        'm_parents' => array(7585,6144,6192),
    ),
    6682 => array(
        'm_icon' => '<i class="fas fa-external-link "></i>',
        'm_name' => 'Submit URL',
        'm_desc' => 'User will complete by sending a URL message',
        'm_parents' => array(7585,6144,6192),
    ),
    6680 => array(
        'm_icon' => '<i class="fas fa-microphone "></i>',
        'm_name' => 'Submit Audio',
        'm_desc' => 'User will complete by sending an audio message',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6678 => array(
        'm_icon' => '<i class="fas fa-image "></i>',
        'm_name' => 'Submit Image',
        'm_desc' => 'User will complete by sending an image message',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6679 => array(
        'm_icon' => '<i class="fas fa-video "></i>',
        'm_name' => 'Submit Video',
        'm_desc' => 'User will complete by sending a video message',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="fas fa-film "></i>',
        'm_name' => 'Submit Multimedia',
        'm_desc' => 'User completes by uploading a video, audio or image file',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6681 => array(
        'm_icon' => '<i class="fas fa-file-pdf "></i>',
        'm_name' => 'Submit File',
        'm_desc' => 'User will complete by sending a file (PDF, DOC, etc...) message',
        'm_parents' => array(7751,7585,6144,6192),
    ),
    6684 => array(
        'm_icon' => '<i class="fas fa-question"></i>',
        'm_name' => 'Choose Single Child',
        'm_desc' => 'User will complete by choosing a child intent as their answer',
        'm_parents' => array(7712,7588,7585,6157,6193),
    ),
    6685 => array(
        'm_icon' => '<i class="fas fa-stopwatch "></i>',
        'm_name' => 'Choose Single Child Timed',
        'm_desc' => 'User will complete by choosing a child intent as their answer within a time limit',
        'm_parents' => array(7712,7488,7487,7585,7366,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'Choose Multiple Childred',
        'm_desc' => 'User will complete by choosing one or more child intents as their answer',
        'm_parents' => array(7712,7489,7588,7585,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube "></i>',
        'm_name' => 'Read Any Child',
        'm_desc' => 'User will complete by (a) choosing intent as their answer or by (b) completing any child intent',
        'm_parents' => array(7756,7585,7486,7485,7309,6997,6193),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes "></i>',
        'm_name' => 'Read All Children',
        'm_desc' => 'User will complete by (a) choosing intent as their answer or by (b) completing all child intents',
        'm_parents' => array(6192,7756,7585,7486,7485,7309,6997),
    ),
);

//Sign-in Required by Reader:
$config['en_ids_7582'] = array(7598);
$config['en_all_7582'] = array(
    7598 => array(
        'm_icon' => '<i class="far fa-newspaper"></i>',
        'm_name' => 'Blog Post',
        'm_desc' => '',
        'm_parents' => array(10568,7767,7582,7596),
    ),
);

//Reader Interaction CC Email:
$config['en_ids_5967'] = array(4235,6415,4246,7504);
$config['en_all_5967'] = array(
    4235 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Reader Blog Bookmarked',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function ispink"></i>',
        'm_name' => 'Reader Cleared Action Plan',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(10596,10589,5967,4755,6418,4593,6414),
    ),
    4246 => array(
        'm_icon' => '<i class="far fa-bug ispink"></i>',
        'm_name' => 'Trainer Bug Reports',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(10596,10589,5967,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Trainer Review Trigger',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(10596,10589,5967,4755,4593),
    ),
);

//Mench Notifications:
$config['en_ids_7555'] = array(3288,6196);
$config['en_all_7555'] = array(
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open"></i>',
        'm_name' => 'Email',
        'm_desc' => 'Connect with Mench on a web browser like Chrome or Safari and receive notifications via Email.',
        'm_parents' => array(7555,6225,4426,4755),
    ),
    6196 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Messenger',
        'm_desc' => 'Establish a consistent connection with Mench on Messenger and get everything done in one place. (RECOMMENDED)',
        'm_parents' => array(5969,7555,3320),
    ),
);

//Thing Reference Required:
$config['en_ids_7551'] = array(10573,7701,4983,7545,10715);
$config['en_all_7551'] = array(
    10573 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Blog Note Bookmarks',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'Blog Note Followers',
        'm_desc' => '',
        'm_parents' => array(5007,10594,10589,4527,7703,4593,7551,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Blog Note References',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="far fa-tags ispink"></i>',
        'm_name' => 'Blog Note Tags',
        'm_desc' => '',
        'm_parents' => array(5007,10594,10589,7703,7551,4593,4485),
    ),
    10715 => array(
        'm_icon' => '<i class="far fa-folder-tree ispink"></i>',
        'm_name' => 'Blog Note Topics',
        'm_desc' => '',
        'm_parents' => array(5007,7703,7551,10589,10594,4593,4485),
    ),
);

//Mench Conversation Templates:
$config['en_ids_7529'] = array(7609,7608,7533,7531,7567,7568);
$config['en_all_7529'] = array(
    7609 => array(
        'm_icon' => '<i class="fas fa-smile-plus"></i>',
        'm_name' => 'Add candidate profile',
        'm_desc' => '#11584',
        'm_parents' => array(7529),
    ),
    7608 => array(
        'm_icon' => '<i class="fas fa-plus-hexagon"></i>',
        'm_name' => 'Add company profile',
        'm_desc' => '#11965',
        'm_parents' => array(7529),
    ),
    7533 => array(
        'm_icon' => '<i class="fas fa-layer-plus"></i>',
        'm_name' => 'Add Job Posting',
        'm_desc' => '#11964',
        'm_parents' => array(7529),
    ),
    7531 => array(
        'm_icon' => '<i class="fas fa-file-plus"></i>',
        'm_name' => 'Add New Source',
        'm_desc' => '',
        'm_parents' => array(7529),
    ),
    7567 => array(
        'm_icon' => '<i class="fas fa-user-plus"></i>',
        'm_name' => 'Create Account',
        'm_desc' => '',
        'm_parents' => array(7529),
    ),
    7568 => array(
        'm_icon' => '<i class="fas fa-asterisk"></i>',
        'm_name' => 'Reset Password',
        'm_desc' => '',
        'm_parents' => array(7529),
    ),
);

//Reader Steps Unlock:
$config['en_ids_7494'] = array(7485,7486,6997);
$config['en_all_7494'] = array(
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
);

//Blog Type Requirement:
$config['en_ids_7309'] = array(6914,6907);
$config['en_all_7309'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes "></i>',
        'm_name' => 'Blog Read All Children',
        'm_desc' => '',
        'm_parents' => array(6192,7756,7585,7486,7485,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube "></i>',
        'm_name' => 'Blog Read Any Child',
        'm_desc' => '',
        'm_parents' => array(7756,7585,7486,7485,7309,6997,6193),
    ),
);

//Mench Products:
$config['en_ids_7372'] = array(7735,7555,6403,7540,7305,7369,7368);
$config['en_all_7372'] = array(
    7735 => array(
        'm_icon' => '<i class="far fa-database"></i>',
        'm_name' => 'Database Tables',
        'm_desc' => '',
        'm_parents' => array(7372),
    ),
    7555 => array(
        'm_icon' => '<i class="far fa-comments"></i>',
        'm_name' => 'Notifications',
        'm_desc' => '',
        'm_parents' => array(7303,7372,4527),
    ),
    6403 => array(
        'm_icon' => '<i class="fas fa-code"></i>',
        'm_name' => 'PHP Code',
        'm_desc' => 'So far all our products are built using the same PHP application',
        'm_parents' => array(7372,3324,7391,7390,4523,3325,3323,3326),
    ),
    7540 => array(
        'm_icon' => '<i class="fas fa-balance-scale"></i>',
        'm_name' => 'Terms',
        'm_desc' => '#8272',
        'm_parents' => array(7372,7305),
    ),
    7305 => array(
        'm_icon' => '<img src="https://mench.com/img/bp_128.png">',
        'm_name' => 'Website',
        'm_desc' => '',
        'm_parents' => array(7372,1326),
    ),
    7369 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Reader Console',
        'm_desc' => 'A web-based application for readers to to read and interact with blog posts',
        'm_parents' => array(7372,4527),
    ),
    7368 => array(
        'm_icon' => '<i class="far fa-user-edit"></i>',
        'm_name' => 'Blogger Console',
        'm_desc' => 'A web-based application for bloggers to create interactive blogs',
        'm_parents' => array(7372,4527),
    ),
);

//Mench Moderation Tools:
$config['en_ids_6287'] = array(7257,7258,7274);
$config['en_all_6287'] = array(
    7257 => array(
        'm_icon' => '<i class="fab fa-app-store-ios"></i>',
        'm_name' => 'Mench Moderation Apps',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7258 => array(
        'm_icon' => '<i class="far fa-bookmark"></i>',
        'm_name' => 'Mench Platform Bookmarks',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7274 => array(
        'm_icon' => '<i class="far fa-magic"></i>',
        'm_name' => 'Platform Cron Jobs',
        'm_desc' => '',
        'm_parents' => array(6403,6287),
    ),
);

//Mench Reader Console:
$config['en_ids_7369'] = array(7765,7291,4430,7256,4269,7161,6137,6138);
$config['en_all_7369'] = array(
    7765 => array(
        'm_icon' => '<i class="fas fa-globe "></i>',
        'm_name' => 'Blog Post',
        'm_desc' => '',
        'm_parents' => array(4535,7369),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off"></i>',
        'm_name' => 'Logout',
        'm_desc' => '',
        'm_parents' => array(7368,7369),
    ),
    4430 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Readers',
        'm_desc' => '',
        'm_parents' => array(4983,7701,7369,6827,4426,4463),
    ),
    7256 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Search',
        'm_desc' => 'A limited version of the search bar focused on published intent trees.',
        'm_parents' => array(7369,7368,3323),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'Sign In',
        'm_desc' => '',
        'm_parents' => array(7369),
    ),
    7161 => array(
        'm_icon' => '<i class="far fa-chart-bar"></i>',
        'm_name' => 'Stats',
        'm_desc' => '',
        'm_parents' => array(7369,7368,7305),
    ),
    6137 => array(
        'm_icon' => '👤',
        'm_name' => 'My Account',
        'm_desc' => 'A web-based portal (also accessible via Messenger) enabling students to manage their account',
        'm_parents' => array(7369),
    ),
    6138 => array(
        'm_icon' => '🔖',
        'm_name' => 'My Bookmarks',
        'm_desc' => 'A web-based portal (also accessible via Messenger) enabling students to manage their intentions',
        'm_parents' => array(7369,4463),
    ),
);

//Mench Blogger Console:
$config['en_ids_7368'] = array(10691,4535,6205,7291,6287,7256,7161,4536,5007);
$config['en_all_7368'] = array(
    10691 => array(
        'm_icon' => '<i class="far fa-user-edit"></i>',
        'm_name' => 'Bloggers',
        'm_desc' => '',
        'm_parents' => array(4527,7368,6827),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-hashtag yellow"></i>',
        'm_name' => 'Blogs',
        'm_desc' => '',
        'm_parents' => array(10608,7368,4534,4463),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90 ispink"></i>',
        'm_name' => 'Interactions',
        'm_desc' => '',
        'm_parents' => array(10607,7368,4534,4463),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off"></i>',
        'm_name' => 'Logout',
        'm_desc' => '',
        'm_parents' => array(7368,7369),
    ),
    6287 => array(
        'm_icon' => '<i class="far fa-tools"></i>',
        'm_name' => 'Moderation Tools',
        'm_desc' => 'Tools for moderating the Mench platform',
        'm_parents' => array(7368,4527,7284),
    ),
    7256 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Search',
        'm_desc' => 'Intents, Entities & URLs',
        'm_parents' => array(7369,7368,3323),
    ),
    7161 => array(
        'm_icon' => '<i class="far fa-chart-bar"></i>',
        'm_name' => 'Stats',
        'm_desc' => '',
        'm_parents' => array(7369,7368,7305),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-at blue"></i>',
        'm_name' => 'Things',
        'm_desc' => '',
        'm_parents' => array(10605,7368,4534,4463),
    ),
    5007 => array(
        'm_icon' => '<i class="fas fa-expand-arrows ispink"></i>',
        'm_name' => 'Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7368,4757,4593),
    ),
);

//Private Blog Types:
$config['en_ids_7366'] = array(6685);
$config['en_all_7366'] = array(
    6685 => array(
        'm_icon' => '<i class="fas fa-stopwatch "></i>',
        'm_name' => 'Blog Choose Single Child Timed',
        'm_desc' => '',
        'm_parents' => array(7712,7488,7487,7585,7366,6193),
    ),
);

//Interaction Statuses Incomplete:
$config['en_ids_7364'] = array(6175);
$config['en_all_7364'] = array(
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'Link Drafting',
        'm_desc' => '',
        'm_parents' => array(7774,7364,7360,6186),
    ),
);

//Interaction Statuses Active:
$config['en_ids_7360'] = array(6175,6176);
$config['en_all_7360'] = array(
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'Link Drafting',
        'm_desc' => '',
        'm_parents' => array(7774,7364,7360,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-globe ispink"></i>',
        'm_name' => 'Link Published',
        'm_desc' => '',
        'm_parents' => array(7774,7360,7359,6186),
    ),
);

//Interaction Statuses Public:
$config['en_ids_7359'] = array(6176);
$config['en_all_7359'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-globe ispink"></i>',
        'm_name' => 'Link Published',
        'm_desc' => '',
        'm_parents' => array(7774,7360,7359,6186),
    ),
);

//Thing Statuses Active:
$config['en_ids_7358'] = array(6180,6181);
$config['en_all_7358'] = array(
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'Entity Drafting',
        'm_desc' => '',
        'm_parents' => array(7774,7358,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-globe blue"></i>',
        'm_name' => 'Entity Published',
        'm_desc' => '',
        'm_parents' => array(7774,7358,7357,6177),
    ),
);

//Thing Statuses Public:
$config['en_ids_7357'] = array(6181);
$config['en_all_7357'] = array(
    6181 => array(
        'm_icon' => '<i class="fas fa-globe blue"></i>',
        'm_name' => 'Entity Published',
        'm_desc' => '',
        'm_parents' => array(7774,7358,7357,6177),
    ),
);

//Blog Statuses Active:
$config['en_ids_7356'] = array(6183,6184);
$config['en_all_7356'] = array(
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin "></i>',
        'm_name' => 'Blog Drafting',
        'm_desc' => '',
        'm_parents' => array(7774,7356,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-globe yellow "></i>',
        'm_name' => 'Blog Published',
        'm_desc' => '',
        'm_parents' => array(7774,7355,7356,4737),
    ),
);

//Blog Statuses Public:
$config['en_ids_7355'] = array(6184);
$config['en_all_7355'] = array(
    6184 => array(
        'm_icon' => '<i class="fas fa-globe yellow "></i>',
        'm_name' => 'Blog Published',
        'm_desc' => '',
        'm_parents' => array(7774,7355,7356,4737),
    ),
);

//Blog Stats:
$config['en_ids_7302'] = array(7596,4737,10602,5008);
$config['en_all_7302'] = array(
    7596 => array(
        'm_icon' => '<i class="fas fa-triangle"></i>',
        'm_name' => 'Scope',
        'm_desc' => 'Defines who and how can access intent. Note that all intents are accessible to all users, it\'s just the level of visibility/engagement that is different.',
        'm_parents' => array(6204,10649,7302,6160,6201,6194,6232,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h "></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(6204,10648,6226,6160,6232,7302,6194,6201,4527),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece"></i>',
        'm_name' => 'Type Groups',
        'm_desc' => '',
        'm_parents' => array(10893,6204,7302,4527),
    ),
    5008 => array(
        'm_icon' => '<i class="fas fa-tools "></i>',
        'm_name' => 'Verb',
        'm_desc' => '',
        'm_parents' => array(6204,10647,4736,7777,6160,6232,7302,4506,6194,6201),
    ),
);

//Thing Stats:
$config['en_ids_7303'] = array(6827,7555,6177,3000);
$config['en_all_7303'] = array(
    6827 => array(
        'm_icon' => '<i class="far fa-users-crown"></i>',
        'm_name' => 'Community Members',
        'm_desc' => '',
        'm_parents' => array(3303,3314,2738,7303,4527),
    ),
    7555 => array(
        'm_icon' => '<i class="far fa-comments"></i>',
        'm_name' => 'Mench Notifications',
        'm_desc' => '',
        'm_parents' => array(7303,7372,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h"></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(6204,5003,10654,6160,6232,7303,6194,6206,4527),
    ),
    3000 => array(
        'm_icon' => '<i class="far fa-whistle"></i>',
        'm_name' => 'Trained Expert Sources',
        'm_desc' => '',
        'm_parents' => array(10571,7303,4506,4527,4463),
    ),
);

//Interaction Stats:
$config['en_ids_7304'] = array(10591,6186,7797);
$config['en_all_7304'] = array(
    10591 => array(
        'm_icon' => '<i class="fas fa-directions"></i>',
        'm_name' => 'Reader Groups',
        'm_desc' => '',
        'm_parents' => array(6204,7797,7304,6771,4527,10588),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h"></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(10677,10661,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    7797 => array(
        'm_icon' => '<i class="fas fa-trophy"></i>',
        'm_name' => 'Mench Platform Leaderboards',
        'm_desc' => '',
        'm_parents' => array(7304,7254),
    ),
);

//Weekly Leaderboard Message:
$config['en_ids_7203'] = array();
$config['en_all_7203'] = array(
);

//Interaction Status:
$config['en_ids_6186'] = array(6176,6175,6173);
$config['en_all_6186'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-globe ispink"></i>',
        'm_name' => 'Link Published',
        'm_desc' => 'live and ready to be shared with users',
        'm_parents' => array(7774,7360,7359,6186),
    ),
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'Link Drafting',
        'm_desc' => 'being mined, to be published soon',
        'm_parents' => array(7774,7364,7360,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="far fa-trash-alt"></i>',
        'm_name' => 'Link Archived',
        'm_desc' => 'archived',
        'm_parents' => array(10686,10678,10673,6186),
    ),
);

//Thing Database References:
$config['en_ids_6194'] = array(7596,4737,7585,5008,6186,4593,4364,6177);
$config['en_all_6194'] = array(
    7596 => array(
        'm_icon' => '<i class="fas fa-triangle"></i>',
        'm_name' => 'Blog Scope',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_intents WHERE in_status_entity_id IN (6183,6184) AND in_level_entity_id=',
        'm_parents' => array(6204,10649,7302,6160,6201,6194,6232,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h "></i>',
        'm_name' => 'Blog Status',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_intents WHERE in_status_entity_id=',
        'm_parents' => array(6204,10648,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-puzzle-piece"></i>',
        'm_name' => 'Blog Type',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_intents WHERE in_status_entity_id IN (6183,6184) AND in_completion_method_entity_id=',
        'm_parents' => array(10608,6204,10651,6160,6194,6232,4527,6201),
    ),
    5008 => array(
        'm_icon' => '<i class="fas fa-tools "></i>',
        'm_name' => 'Blog Verb',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_intents WHERE in_status_entity_id IN (6183,6184) AND in_verb_entity_id=',
        'm_parents' => array(6204,10647,4736,7777,6160,6232,7302,4506,6194,6201),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h"></i>',
        'm_name' => 'Interaction Status',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_links WHERE ln_status_entity_id=',
        'm_parents' => array(10677,10661,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug"></i>',
        'm_name' => 'Interaction Type',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_links WHERE ln_status_entity_id IN (6175,6176) AND ln_type_entity_id=',
        'm_parents' => array(10607,10659,6160,6232,6194,4527,4341),
    ),
    4364 => array(
        'm_icon' => '<i class="far fa-user-edit"></i>',
        'm_name' => 'Link Creator',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_links WHERE ln_status_entity_id IN (6175,6176) AND ln_creator_entity_id=',
        'm_parents' => array(6160,6232,6194,4341),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h"></i>',
        'm_name' => 'Thing Status',
        'm_desc' => 'SELECT count(en_id) as totals FROM table_entities WHERE en_status_entity_id=',
        'm_parents' => array(6204,5003,10654,6160,6232,7303,6194,6206,4527),
    ),
);

//Community Members:
$config['en_ids_6827'] = array(3084,10691,4430);
$config['en_all_6827'] = array(
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut"></i>',
        'm_name' => 'Experts',
        'm_desc' => 'Experienced in their respective industry with a track record of advancing their field of knowldge',
        'm_parents' => array(10571,4983,6827,4463),
    ),
    10691 => array(
        'm_icon' => '<i class="far fa-user-edit"></i>',
        'm_name' => 'Bloggers',
        'm_desc' => 'Users who actively train the Mench personal assistant',
        'm_parents' => array(4527,7368,6827),
    ),
    4430 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Readers',
        'm_desc' => 'Users who are pursuing their intentions using Mench, mainly to get hired at their dream job',
        'm_parents' => array(4983,7701,7369,6827,4426,4463),
    ),
);

//Thing Interaction Content Requires Text:
$config['en_ids_6805'] = array(3005,4763,3147,2999,4883,3192);
$config['en_all_6805'] = array(
    3005 => array(
        'm_icon' => '<i class="far fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="far fa-megaphone"></i>',
        'm_name' => 'Expert Channels',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="far fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="far fa-microphone"></i>',
        'm_name' => 'Expert Podcasts',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4883 => array(
        'm_icon' => '<i class="far fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="far fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
);

//Readable by Readers:
$config['en_ids_6345'] = array(4231);
$config['en_all_6345'] = array(
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Blog Note Messages',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
);

//Reader Steps Skippable:
$config['en_ids_6274'] = array(4559);
$config['en_all_6274'] = array(
    4559 => array(
        'm_icon' => '<i class="fas fa-comments ispink"></i>',
        'm_name' => 'Reader Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
);

//Reader Steps Progressed:
$config['en_ids_6255'] = array(7485,7486,4559,7489,7492,6997,6157,7487,6144);
$config['en_all_6255'] = array(
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="fas fa-comments ispink"></i>',
        'm_name' => 'Reader Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-double ispink"></i>',
        'm_name' => 'Reader Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-square ispink"></i>',
        'm_name' => 'Reader Step Path Not Found',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6255,4755,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-question ispink"></i>',
        'm_name' => 'Reader Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-stopwatch ispink"></i>',
        'm_name' => 'Reader Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'Reader Submission Required',
        'm_desc' => '',
        'm_parents' => array(10893,10596,10589,4527,7703,6255,6244,4755,6146,4593),
    ),
);

//Reader Steps Double:
$config['en_ids_6244'] = array(7486,6157,7487,6144);
$config['en_all_6244'] = array(
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Children Unlock',
        'm_desc' => 'Logged initially when the user arrives at a locked intent that has no immediate OR parents to mark it as complete and has children, which means the only way through is to complete all its children. Marks as complete when ANY/ALL children are complete dependant on if its a AND/OR locked intent.',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-question ispink"></i>',
        'm_name' => 'Reader Step Single-Answered',
        'm_desc' => 'Logged initially when the user arrives at a regular OR intent, and completed when they submit their answer.',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-stopwatch ispink"></i>',
        'm_name' => 'Reader Step Single-Answered Timely',
        'm_desc' => 'Logged initially when the user starts to answer a timed OR intent, and will be published if they are successful at answering it on time. If not, will update link type to User Step Answer Timeout.',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'Reader Submission Required',
        'm_desc' => 'Logged initially when the user starts an intent that has a requirement submission (Text, URL, Video, Image, etc...) and is completed when they submit the requirement.',
        'm_parents' => array(10893,10596,10589,4527,7703,6255,6244,4755,6146,4593),
    ),
);

//Reader Completed Blogion:
$config['en_ids_6150'] = array(6154,6155,7757);
$config['en_all_6150'] = array(
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'Blog Accomplished',
        'm_desc' => '',
        'm_parents' => array(10888,10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Blog Bookmark Removed',
        'm_desc' => '',
        'm_parents' => array(10888,10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    7757 => array(
        'm_icon' => '<i class="fas fa-times-octagon ispink"></i>',
        'm_name' => 'Blog Terminated',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,4593,6150),
    ),
);

//Thing Reference Allowed:
$config['en_ids_4986'] = array(6093,4231);
$config['en_all_4986'] = array(
    6093 => array(
        'm_icon' => '<i class="far fa-comments-alt ispink"></i>',
        'm_name' => 'Blog Note Discussions',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Blog Note Messages',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
);

//My Account Inputs:
$config['en_ids_6225'] = array(6197,3288,3286,4783,3290,3287,3089,3289,6123,4454);
$config['en_all_6225'] = array(
    6197 => array(
        'm_icon' => '<i class="fas fa-fingerprint"></i>',
        'm_name' => 'Name',
        'm_desc' => 'Your first and last name:',
        'm_parents' => array(10646,5000,4998,4999,6232,6225,6206),
    ),
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open"></i>',
        'm_name' => 'Email',
        'm_desc' => 'Your email address is also used to login to Mench:',
        'm_parents' => array(7555,6225,4426,4755),
    ),
    3286 => array(
        'm_icon' => '<i class="far fa-key"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => 'The password used to login to Mench:',
        'm_parents' => array(7578,6225,5969,4755),
    ),
    4783 => array(
        'm_icon' => '<i class="far fa-phone"></i>',
        'm_name' => 'Phone Number',
        'm_desc' => 'Your number for potential employers to call you at:',
        'm_parents' => array(6225,4755,4319),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender"></i>',
        'm_name' => 'Genders',
        'm_desc' => 'Choose one of the following:',
        'm_parents' => array(6225,6204),
    ),
    3287 => array(
        'm_icon' => '<i class="far fa-language"></i>',
        'm_name' => 'Languages',
        'm_desc' => 'Choose all the languages you speak fluently:',
        'm_parents' => array(10725,7552,6225,6122),
    ),
    3089 => array(
        'm_icon' => '<i class="far fa-globe"></i>',
        'm_name' => 'Countries',
        'm_desc' => 'Choose your current country of residence:',
        'm_parents' => array(6122,6225),
    ),
    3289 => array(
        'm_icon' => '<i class="far fa-map"></i>',
        'm_name' => 'Timezones',
        'm_desc' => 'Choose your current timezone:',
        'm_parents' => array(6204,6225),
    ),
    6123 => array(
        'm_icon' => '<i class="far fa-share-alt-square"></i>',
        'm_name' => 'Social Profiles',
        'm_desc' => 'Social profiles you\'d like to share with potential employers:',
        'm_parents' => array(6225,4527),
    ),
    4454 => array(
        'm_icon' => '<i class="far fa-bells"></i>',
        'm_name' => 'Subscription Settings',
        'm_desc' => 'Choose how you like to be notified for messages I send you via Messenger:',
        'm_parents' => array(7552,6225,6204,4527),
    ),
);

//Blog Status:
$config['en_ids_4737'] = array(6184,6183,6182);
$config['en_all_4737'] = array(
    6184 => array(
        'm_icon' => '<i class="fas fa-globe yellow "></i>',
        'm_name' => 'Published',
        'm_desc' => 'newly added by miner but not yet checked by moderator',
        'm_parents' => array(7774,7355,7356,4737),
    ),
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin "></i>',
        'm_name' => 'Drafting',
        'm_desc' => 'newly added, pending review',
        'm_parents' => array(7774,7356,4737),
    ),
    6182 => array(
        'm_icon' => '<i class="far fa-trash-alt "></i>',
        'm_name' => 'Archived',
        'm_desc' => 'archived',
        'm_parents' => array(10671,4737),
    ),
);

//Thing Status:
$config['en_ids_6177'] = array(6181,6180,6178);
$config['en_all_6177'] = array(
    6181 => array(
        'm_icon' => '<i class="fas fa-globe blue"></i>',
        'm_name' => 'Entity Published',
        'm_desc' => 'live and ready to be shared with users',
        'm_parents' => array(7774,7358,7357,6177),
    ),
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'Entity Drafting',
        'm_desc' => 'being mined, to be published soon',
        'm_parents' => array(7774,7358,6177),
    ),
    6178 => array(
        'm_icon' => '<i class="far fa-trash-alt"></i>',
        'm_name' => 'Entity Archived',
        'm_desc' => 'archived',
        'm_parents' => array(10672,6177),
    ),
);

//Reader Steps Taken:
$config['en_ids_6146'] = array(7488,7485,7741,7486,4559,7489,7492,6997,6157,7487,6143,6144);
$config['en_all_6146'] = array(
    7488 => array(
        'm_icon' => '<i class="fas fa-calendar-times ispink"></i>',
        'm_name' => 'Answer Timeout',
        'm_desc' => 'User failed to answer the question within the allocated time',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Answer Unlock',
        'm_desc' => 'When users unlock locked AND or OR intents by simply answering an open OR question',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7741 => array(
        'm_icon' => '<i class="fas fa-times-circle ispink"></i>',
        'm_name' => 'Blogion Terminated',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Children Unlock',
        'm_desc' => 'When users unlock locked AND or OR intents by completing ALL or ANY of their children',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="fas fa-comments ispink"></i>',
        'm_name' => 'Messages Only',
        'm_desc' => 'Completed when students complete a basic AND intent without any submission requirements',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-double ispink"></i>',
        'm_name' => 'Multi-Answered',
        'm_desc' => 'User made a selection as part of a multiple-choice answer question',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-square ispink"></i>',
        'm_name' => 'Path Not Found',
        'm_desc' => 'Logged when users arrive at a locked intent that has no public OR parents or no children, which means there is no way to unlock it.',
        'm_parents' => array(10596,10589,6255,4755,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Score Unlock',
        'm_desc' => 'When users unlock locked AND or OR intents by scoring within the range of a conditional intent link',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-question ispink"></i>',
        'm_name' => 'Single-Answered',
        'm_desc' => 'Completed after the student answers the question to the OR intent.',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-stopwatch ispink"></i>',
        'm_name' => 'Single-Answered Timely',
        'm_desc' => 'When the user answers a question within the defined timeframe',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6143 => array(
        'm_icon' => '<i class="fas fa-fast-forward ispink"></i>',
        'm_name' => 'Skipped',
        'm_desc' => 'Completed when students skip an intention and all its child intentions from their Action Plan',
        'm_parents' => array(10596,10589,6146,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'Reader Submission Required',
        'm_desc' => 'Completed when students submit the intent completion requirements (text, URL, video, etc...) set by miners',
        'm_parents' => array(10893,10596,10589,4527,7703,6255,6244,4755,6146,4593),
    ),
);

//Social Profiles:
$config['en_ids_6123'] = array(3303,3302,3300);
$config['en_all_6123'] = array(
    3303 => array(
        'm_icon' => '<i class="fab fa-github"></i>',
        'm_name' => 'Github',
        'm_desc' => '',
        'm_parents' => array(6123,4763,1326,2750),
    ),
    3302 => array(
        'm_icon' => '<i class="fab fa-linkedin"></i>',
        'm_name' => 'LinkedIn',
        'm_desc' => '',
        'm_parents' => array(6123,1326,4763,2750),
    ),
    3300 => array(
        'm_icon' => '<i class="fab fa-twitter"></i>',
        'm_name' => 'Twitter',
        'm_desc' => '',
        'm_parents' => array(6123,2750,1326,3304),
    ),
);

//Reader Sent Messages with Messenger:
$config['en_ids_4277'] = array(7654,6554,7653);
$config['en_all_4277'] = array(
    7654 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Sent Messenger Automated Messages',
        'm_desc' => '',
        'm_parents' => array(4277),
    ),
    6554 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Sent Messenger Command Messages',
        'm_desc' => '',
        'm_parents' => array(4277),
    ),
    7653 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Sent Messenger Manual Messages',
        'm_desc' => '',
        'm_parents' => array(4277),
    ),
);

//Reader Sent/Received Attachment:
$config['en_ids_6102'] = array(4554,4556,4555,4553,4549,4551,4550,4548);
$config['en_all_6102'] = array(
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
);

//Reader Received Messages with Messenger:
$config['en_ids_4280'] = array(4554,4556,4555,6563,4552,4553);
$config['en_all_4280'] = array(
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'Reader Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Reader Received Text',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
);

//Thing Hard Lock:
$config['en_ids_5969'] = array(3286,6196);
$config['en_all_5969'] = array(
    3286 => array(
        'm_icon' => '<i class="far fa-key"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => 'Managed through the Forgot Password section in the Login page',
        'm_parents' => array(7578,6225,5969,4755),
    ),
    6196 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Messenger',
        'm_desc' => '',
        'm_parents' => array(5969,7555,3320),
    ),
);

//Thing Mass Updates:
$config['en_ids_4997'] = array(10625,5943,5001,5865,4999,4998,5000,5981,5982,5003);
$config['en_all_4997'] = array(
    10625 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Icon Replace',
        'm_desc' => 'Search for occurrence of string in child entity icons and if found, updates it with a replacement string',
        'm_parents' => array(10589,10596,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Icon Update',
        'm_desc' => 'Updates all child entity icons with string which needs to be a valid icon',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Interaction Contents',
        'm_desc' => 'Search for occurance of string in child entity link contents and if found, updates it with a replacement string',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Interaction Status',
        'm_desc' => 'Updates all child entity link statuses that match the initial link status condition',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Name Postfix',
        'm_desc' => 'Adds string to the end of all child entities',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Name Prefix',
        'm_desc' => 'Adds string to the beginning of all child entities. Make sure to include a space for it to look good',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Name Replace',
        'm_desc' => 'Search for occurrence of string in child entity names and if found, updates it with a replacement string',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fas fa-layer-plus ispink"></i>',
        'm_name' => 'Parent Add',
        'm_desc' => 'If not already done so, will add searched entity as the parent of all child entities',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fas fa-layer-minus ispink"></i>',
        'm_name' => 'Parent Remove',
        'm_desc' => 'If already added as the parent, this will remove searched entity as the parent of all child entities',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Status Replace',
        'm_desc' => 'Updates all child entity statuses that match the initial entity status condition',
        'm_parents' => array(10596,10589,4593,4997),
    ),
);

//Thing Soft Lock:
$config['en_ids_4426'] = array(3288,1308,4430,4755,5969,4997,4426);
$config['en_all_4426'] = array(
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open"></i>',
        'm_name' => 'Email',
        'm_desc' => '',
        'm_parents' => array(7555,6225,4426,4755),
    ),
    1308 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isnavy"></i>',
        'm_name' => 'Mench Blogger Level 2',
        'm_desc' => 'Requires admin review and approval',
        'm_parents' => array(10573,7701,4983,10704,10618,10691,10571,4463,4426),
    ),
    4430 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Mench Readers',
        'm_desc' => '',
        'm_parents' => array(4983,7701,7369,6827,4426,4463),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash"></i>',
        'm_name' => 'Private Interactions',
        'm_desc' => '',
        'm_parents' => array(6771,4463,4426,4527,4757),
    ),
    5969 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Thing Hard Lock',
        'm_desc' => '',
        'm_parents' => array(4758,3303,4426,4527,4757,4428),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-list-alt"></i>',
        'm_name' => 'Thing Mass Updates',
        'm_desc' => '',
        'm_parents' => array(4536,4506,4426,4527),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Thing Soft Lock',
        'm_desc' => '',
        'm_parents' => array(4758,3303,4426,4527,4757,4428),
    ),
);

//Private Interactions:
$config['en_ids_4755'] = array(7610,10681,10685,3288,3286,4783,6154,4235,6155,6149,7495,6969,7542,4275,4283,6132,7757,6415,6559,6560,6556,6578,7611,5967,7563,4266,4267,4282,4554,7702,4570,4556,4555,6563,4552,4553,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7561,7564,7560,7559,7558,7488,7485,7741,7486,6140,4559,7489,7492,6997,6157,7487,6143,6144,7578,6224,7562,4246,7504);
$config['en_all_4755'] = array(
    7610 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Blog Read by Reader',
        'm_desc' => '',
        'm_parents' => array(10638,10596,10590,7765,4755,4593),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(10638,10589,10596,4755,4593,10658),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'Blog Submission Update by Reader',
        'm_desc' => '',
        'm_parents' => array(4755,10596,10589,4593,10638,10658),
    ),
    3288 => array(
        'm_icon' => '<i class="fas fa-envelope-open"></i>',
        'm_name' => 'Email',
        'm_desc' => '',
        'm_parents' => array(7555,6225,4426,4755),
    ),
    3286 => array(
        'm_icon' => '<i class="far fa-key"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => '',
        'm_parents' => array(7578,6225,5969,4755),
    ),
    4783 => array(
        'm_icon' => '<i class="far fa-phone"></i>',
        'm_name' => 'Phone Number',
        'm_desc' => '',
        'm_parents' => array(6225,4755,4319),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'Reader Blog Accomplished',
        'm_desc' => '',
        'm_parents' => array(10888,10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Reader Blog Bookmarked',
        'm_desc' => '',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Reader Blog Bookmark Removed',
        'm_desc' => '',
        'm_parents' => array(10888,10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus ispink"></i>',
        'm_name' => 'Reader Blog Considered',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Reader Blog Recommended',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="fas fa-megaphone ispink"></i>',
        'm_name' => 'Reader Blog Recommended',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,4593,4755,6153),
    ),
    7542 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Reader Blog Required',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-search ispink"></i>',
        'm_name' => 'Reader Blog Search',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10589,6554,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Blogs Listed',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Reader Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,6153,4506,4755,4593),
    ),
    7757 => array(
        'm_icon' => '<i class="fas fa-times-octagon ispink"></i>',
        'm_name' => 'Reader Blog Terminated',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,4593,6150),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function ispink"></i>',
        'm_name' => 'Reader Cleared Action Plan',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,6418,4593,6414),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Next',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    7611 => array(
        'm_icon' => '<i class="fas fa-hand-pointer ispink"></i>',
        'm_name' => 'Reader Engaged Blog Post',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,7610,4755,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Interaction CC Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4506,4527,7569,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Magic-Interaction Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Opened Profile',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,6222,4593),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7702 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Received Blog Email',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    4570 => array(
        'm_icon' => '<i class="far fa-envelope ispink"></i>',
        'm_name' => 'Reader Received Email',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'Reader Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Reader Received Text',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'Reader Sent Access',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle ispink"></i>',
        'm_name' => 'Reader Sent Location',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Sent Messenger Read',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download ispink"></i>',
        'm_name' => 'Reader Sent Messenger Received',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag ispink"></i>',
        'm_name' => 'Reader Sent Postback',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'Reader Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Reader Sent Text',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Reader Sent Unknown Message',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin Generally',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin Success',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin with Blogion',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fas fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Signin with Email',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Signin with Messenger',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7488 => array(
        'm_icon' => '<i class="fas fa-calendar-times ispink"></i>',
        'm_name' => 'Reader Step Answer Timeout',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7741 => array(
        'm_icon' => '<i class="fas fa-times-circle ispink"></i>',
        'm_name' => 'Reader Step Blogion Terminated',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Interaction Unlock',
        'm_desc' => '',
        'm_parents' => array(10539,10589,6410,4229,4755,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="fas fa-comments ispink"></i>',
        'm_name' => 'Reader Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-double ispink"></i>',
        'm_name' => 'Reader Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-square ispink"></i>',
        'm_name' => 'Reader Step Path Not Found',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6255,4755,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-question ispink"></i>',
        'm_name' => 'Reader Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-stopwatch ispink"></i>',
        'm_name' => 'Reader Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6143 => array(
        'm_icon' => '<i class="fas fa-fast-forward ispink"></i>',
        'm_name' => 'Reader Step Skipped',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6146,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'Reader Submission Required',
        'm_desc' => '',
        'm_parents' => array(10893,10596,10589,4527,7703,6255,6244,4755,6146,4593),
    ),
    7578 => array(
        'm_icon' => '<i class="far fa-key ispink"></i>',
        'm_name' => 'Reader Update Password',
        'm_desc' => '',
        'm_parents' => array(6222,10658,6153,10539,10589,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="far fa-sync ispink"></i>',
        'm_name' => 'Reader Update Profile',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,6222,4593),
    ),
    7562 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Welcome Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    4246 => array(
        'm_icon' => '<i class="far fa-bug ispink"></i>',
        'm_name' => 'Trainer Bug Reports',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Trainer Review Trigger',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,4593),
    ),
);

//Reader Account Types:
$config['en_ids_4600'] = array(2750,1278);
$config['en_all_4600'] = array(
    2750 => array(
        'm_icon' => '<i class="fas fa-user-tie"></i>',
        'm_name' => 'Companies',
        'm_desc' => '',
        'm_parents' => array(4600),
    ),
    1278 => array(
        'm_icon' => '👪',
        'm_name' => 'People',
        'm_desc' => '',
        'm_parents' => array(4600),
    ),
);

//Interaction Type:
$config['en_ids_4593'] = array(10671,4250,10675,10686,10663,10664,10661,10662,6226,10660,10573,6093,7701,4601,4231,4983,10676,10678,10679,10677,7545,10715,7610,10681,10685,4228,4229,10650,10649,10648,10644,10651,10647,4993,5007,6154,4235,6155,6149,7495,6969,7542,4275,4283,6132,7757,6415,6559,6560,6556,6578,7611,5967,7563,10690,4266,4267,4282,10683,4554,7702,4570,4556,4555,6563,4552,4553,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7561,7564,7560,7559,7558,7488,7485,7741,7486,6140,4559,7489,7492,6997,6157,7487,6143,6144,7578,6224,7562,10672,4251,10625,5943,5001,5865,4999,4998,5000,5981,5982,5003,10689,10673,4259,4230,4257,4261,4260,4319,4255,4318,10657,10656,10659,4256,4258,10653,10646,10654,4246,7504,4994);
$config['en_all_4593'] = array(
    10671 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Archived',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,7703,10638),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Blog Created',
        'm_desc' => '',
        'm_parents' => array(10638,10593,10589,4593),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blogger Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(7703,10539,10589,4593,10658,10638),
    ),
    10686 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Interaction Archived',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10539,4593,10658,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-coin ispink"></i>',
        'm_name' => 'Blog Interaction Update Points',
        'm_desc' => '',
        'm_parents' => array(4228,7703,10539,10589,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Blog Interaction Update Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    10661 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Interaction Update Status',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Blog Interaction Update Type',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    6226 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Mass Update Statuses',
        'm_desc' => ' When all intents within a recursive tree are updated at once.',
        'm_parents' => array(10655,10596,10589,4593),
    ),
    10660 => array(
        'm_icon' => '<i class="fas fa-angle-double-right ispink"></i>',
        'm_name' => 'Blog Migrate Parent Blog',
        'm_desc' => '',
        'm_parents' => array(7703,4593,10638,10589,10594,10658),
    ),
    10573 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Blog Note Bookmarks',
        'm_desc' => 'Keeps track of the users who can manage/edit the intent',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comments-alt ispink"></i>',
        'm_name' => 'Blog Note Discussions',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'Blog Note Followers',
        'm_desc' => 'When trainers subscribe to receive intent updates and manage the intent.',
        'm_parents' => array(5007,10594,10589,4527,7703,4593,7551,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-search ispink"></i>',
        'm_name' => 'Blog Note Keywords',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Blog Note Messages',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Blog Note References',
        'm_desc' => 'References track intent correlations referenced within expert sources, and represent a core building block of intelligence. References are among the most precious transaction types because they indicate that IF you do A, you will likely accomplish B. As trainers add more sources from more experts, certain intent correlations will receive more references than others, thus gaining more credibility.',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    10676 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blog Notes Sorted',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Blog Notes Unlinked',
        'm_desc' => '',
        'm_parents' => array(10658,10539,10589,4593,10638),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Blog Notes Update Content',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Notes Update Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    7545 => array(
        'm_icon' => '<i class="far fa-tags ispink"></i>',
        'm_name' => 'Blog Note Tags',
        'm_desc' => '',
        'm_parents' => array(5007,10594,10589,7703,7551,4593,4485),
    ),
    10715 => array(
        'm_icon' => '<i class="far fa-folder-tree ispink"></i>',
        'm_name' => 'Blog Note Topics',
        'm_desc' => '',
        'm_parents' => array(5007,7703,7551,10589,10594,4593,4485),
    ),
    7610 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Blog Read by Reader',
        'm_desc' => 'When a user viewes the public intent landing page.',
        'm_parents' => array(10638,10596,10590,7765,4755,4593),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Blogs Sorted',
        'm_desc' => '',
        'm_parents' => array(10638,10589,10596,4755,4593,10658),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'Blog Submission Update by Reader',
        'm_desc' => 'When users update their a step they made previous progress',
        'm_parents' => array(4755,10596,10589,4593,10638,10658),
    ),
    4228 => array(
        'm_icon' => '<i class="far fa-link rotate90 ispink"></i>',
        'm_name' => 'Blog-to-Blog Interaction Basic',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,6410,4593,4486),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Blog-to-Blog Interaction Conditional',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
    10650 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Blog Update Completion Time',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10649 => array(
        'm_icon' => '<i class="fas fa-triangle ispink"></i>',
        'm_name' => 'Blog Update Scope',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10648 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Blog Update Status',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Blog Update Title',
        'm_desc' => 'Logged when trainers update the intent outcome',
        'm_parents' => array(7703,10589,10593,4593,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="far fa-shapes ispink"></i>',
        'm_name' => 'Blog Update Type',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10647 => array(
        'm_icon' => '<i class="far fa-tools ispink"></i>',
        'm_name' => 'Blog Update Verb',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10638),
    ),
    4993 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Blog Viewed by Blogger',
        'm_desc' => '',
        'm_parents' => array(10638,10596,10590,4593),
    ),
    5007 => array(
        'm_icon' => '<i class="fas fa-expand-arrows ispink"></i>',
        'm_name' => 'Mench Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7368,4757,4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'Reader Blog Accomplished',
        'm_desc' => 'Student accomplished their intention 🎉🎉🎉',
        'm_parents' => array(10888,10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Reader Blog Bookmarked',
        'm_desc' => 'Intentions set by users which will be completed by taking steps using the Action Plan',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Reader Blog Bookmark Removed',
        'm_desc' => 'Student prematurely removed an intention from their Action Plan without accomplishing it.',
        'm_parents' => array(10888,10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus ispink"></i>',
        'm_name' => 'Reader Blog Considered',
        'm_desc' => 'When a student chooses to review a given intention from the intentions they have searched or have been recommended after selecting GET STARTED from a mench.com intent landing page.',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Reader Blog Recommended',
        'm_desc' => 'Intention recommended by Mench and added to Action Plan to enable the user to complete their intention',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="fas fa-megaphone ispink"></i>',
        'm_name' => 'Reader Blog Recommended',
        'm_desc' => 'Logged every time an intention is recommended to a user by Mench',
        'm_parents' => array(10639,10596,10590,4593,4755,6153),
    ),
    7542 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Reader Blog Required',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-search ispink"></i>',
        'm_name' => 'Reader Blog Search',
        'm_desc' => 'When students invokes the [I want to] command and search for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(10639,10596,10589,6554,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Blogs Listed',
        'm_desc' => 'Once a student has added an Intention to their Action Plan, this link will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Reader Blogs Sorted',
        'm_desc' => 'Student re-prioritized their top-level intentions to focus on intentions that currently matter the most.',
        'm_parents' => array(10539,10639,10589,6153,4506,4755,4593),
    ),
    7757 => array(
        'm_icon' => '<i class="fas fa-times-octagon ispink"></i>',
        'm_name' => 'Reader Blog Terminated',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,4593,6150),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function ispink"></i>',
        'm_name' => 'Reader Cleared Action Plan',
        'm_desc' => 'Removes certain links types as defined by its children from a Student\'s Action Plan. Currently only available for trainers.',
        'm_parents' => array(10596,10589,5967,4755,6418,4593,6414),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Next',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'Reader Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    7611 => array(
        'm_icon' => '<i class="fas fa-hand-pointer ispink"></i>',
        'm_name' => 'Reader Engaged Blog Post',
        'm_desc' => 'Logged when a user expands a section of the intent',
        'm_parents' => array(10639,10596,10590,7610,4755,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Interaction CC Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4506,4527,7569,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Magic-Interaction Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    10690 => array(
        'm_icon' => '<i class="fas fa-upload ispink"></i>',
        'm_name' => 'Reader Media Uploaded',
        'm_desc' => 'When a file added by the user is synced to the CDN',
        'm_parents' => array(6153,10596,10589,4593,10658),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Opened Profile',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,6222,4593),
    ),
    10683 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Read Email',
        'm_desc' => '',
        'm_parents' => array(6153,10658,10596,10589,4593,7654),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7702 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Received Blog Email',
        'm_desc' => 'Emails sent to intent subscribers who are looking for updates on an intent.',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    4570 => array(
        'm_icon' => '<i class="far fa-envelope ispink"></i>',
        'm_name' => 'Reader Received Email',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'Reader Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Reader Received Text',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'Reader Sent Access',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Reader Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Reader Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Reader Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle ispink"></i>',
        'm_name' => 'Reader Sent Location',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Reader Sent Messenger Read',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download ispink"></i>',
        'm_name' => 'Reader Sent Messenger Received',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag ispink"></i>',
        'm_name' => 'Reader Sent Postback',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'Reader Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Reader Sent Text',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Reader Sent Unknown Message',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Reader Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin Generally',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin Success',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'Reader Signin with Blogion',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fas fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Signin with Email',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'Reader Signin with Messenger',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7488 => array(
        'm_icon' => '<i class="fas fa-calendar-times ispink"></i>',
        'm_name' => 'Reader Step Answer Timeout',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7741 => array(
        'm_icon' => '<i class="fas fa-times-circle ispink"></i>',
        'm_name' => 'Reader Step Blogion Terminated',
        'm_desc' => 'User ended their Action Plan prematurely',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Interaction Unlock',
        'm_desc' => 'Created when the student responses to OR branches meets the right % points to unlock the pathway to a conditional intent link.',
        'm_parents' => array(10539,10589,6410,4229,4755,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="fas fa-comments ispink"></i>',
        'm_name' => 'Reader Step Messages Only',
        'm_desc' => 'Logged when a student receives the messages of an AND intent that does not have any completion requirements.',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-double ispink"></i>',
        'm_name' => 'Reader Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-square ispink"></i>',
        'm_name' => 'Reader Step Path Not Found',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6255,4755,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Reader Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-question ispink"></i>',
        'm_name' => 'Reader Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-stopwatch ispink"></i>',
        'm_name' => 'Reader Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6143 => array(
        'm_icon' => '<i class="fas fa-fast-forward ispink"></i>',
        'm_name' => 'Reader Step Skipped',
        'm_desc' => 'Logged every time a student consciously skips an intent and it\'s recursive children.',
        'm_parents' => array(10596,10589,6146,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'Reader Submission Required',
        'm_desc' => 'Logged when a student submits the requirements (text, video, etc...) of an AND intent which could not be completed by simply receiving messages.',
        'm_parents' => array(10893,10596,10589,4527,7703,6255,6244,4755,6146,4593),
    ),
    7578 => array(
        'm_icon' => '<i class="far fa-key ispink"></i>',
        'm_name' => 'Reader Update Password',
        'm_desc' => '',
        'm_parents' => array(6222,10658,6153,10539,10589,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="far fa-sync ispink"></i>',
        'm_name' => 'Reader Update Profile',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,6222,4593),
    ),
    7562 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'Reader Welcome Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    10672 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Thing Archived',
        'm_desc' => '',
        'm_parents' => array(10539,4593,10589,10645),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Thing Created',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(10645,10593,10589,4593),
    ),
    10625 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Thing Mass Icon Replace',
        'm_desc' => '',
        'm_parents' => array(10589,10596,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Thing Mass Icon Update',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Thing Mass Interaction Contents',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing Mass Interaction Status',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Mass Name Postfix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Mass Name Prefix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Mass Name Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fas fa-layer-plus ispink"></i>',
        'm_name' => 'Thing Mass Parent Add',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fas fa-layer-minus ispink"></i>',
        'm_name' => 'Thing Mass Parent Remove',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing Mass Status Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    10689 => array(
        'm_icon' => '<i class="fas fa-share-alt rotate90 ispink"></i>',
        'm_name' => 'Thing Merged into Thing',
        'm_desc' => 'When an entity is merged with another entity and the links are carried over',
        'm_parents' => array(10596,10589,4593,10658,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Archived',
        'm_desc' => '',
        'm_parents' => array(10645,4593,10539,10589,10658),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-link rotate90 ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Basic',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Embed',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537,4506),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Integer',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Text',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Time',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,4592),
    ),
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Content',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    10656 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Status',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10539,10658,10645),
    ),
    10659 => array(
        'm_icon' => '<i class="fas fa-plug ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Update Type',
        'm_desc' => 'Iterations happens automatically based on link content',
        'm_parents' => array(10658,10589,4593,10596,10645),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction URL',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Thing-to-Thing Interaction Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    10653 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Thing Update Icon',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
    10646 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Thing Update Name',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10645),
    ),
    10654 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Thing Update Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
    4246 => array(
        'm_icon' => '<i class="far fa-bug ispink"></i>',
        'm_name' => 'Trainer Bug Reports',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Trainer Review Trigger',
        'm_desc' => 'Certain links that match an unknown behavior would require an admin to review and ensure it\'s all good',
        'm_parents' => array(10596,10589,5967,4755,4593),
    ),
    4994 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Trainer View Thing',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4593),
    ),
);

//Thing-to-Thing Interactions:
$config['en_ids_4592'] = array(4259,4230,4257,4261,4260,4319,4255,4318,4256,4258);
$config['en_all_4592'] = array(
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-link rotate90 ispink"></i>',
        'm_name' => 'Basic',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle ispink"></i>',
        'm_name' => 'Embed',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537,4506),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down ispink"></i>',
        'm_name' => 'Integer',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Text',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Time',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,4592),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser ispink"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
);

//Subscription Settings:
$config['en_ids_4454'] = array(4456,4457,4458,4455);
$config['en_all_4454'] = array(
    4456 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'Regular Notifications',
        'm_desc' => 'User is connected and will be notified by sound & vibration for new Mench messages',
        'm_parents' => array(4454),
    ),
    4457 => array(
        'm_icon' => '<i class="far fa-volume-down"></i>',
        'm_name' => 'Silent Notifications',
        'm_desc' => 'User is connected and will be notified by on-screen notification only for new Mench messages',
        'm_parents' => array(4454),
    ),
    4458 => array(
        'm_icon' => '<i class="far fa-volume-mute"></i>',
        'm_name' => 'No Notifications',
        'm_desc' => 'User is connected but will not be notified for new Mench messages except the red icon indicator on the Messenger app which would indicate the total number of new messages they have',
        'm_parents' => array(4454),
    ),
    4455 => array(
        'm_icon' => '<i class="far fa-ban"></i>',
        'm_name' => 'Unsubscribe',
        'm_desc' => 'Stop all communications until you re-subscribe',
        'm_parents' => array(5008,4454),
    ),
);

//Blog Notes:
$config['en_ids_4485'] = array(4231,4983,4601,10573,6093,7545,10715,7701);
$config['en_all_4485'] = array(
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Messages',
        'm_desc' => 'Messages sent to readers over web or Messenger',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'References',
        'm_desc' => 'References people and expert sources who agree with this blog',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-search ispink"></i>',
        'm_name' => 'Keywords',
        'm_desc' => 'Maps alternative terms used to search for this blog',
        'm_parents' => array(10593,10589,7703,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="far fa-bookmark ispink"></i>',
        'm_name' => 'Bookmarks',
        'm_desc' => 'Saved to bloggers blog dashboard for faster access',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comments-alt ispink"></i>',
        'm_name' => 'Discussions',
        'm_desc' => 'Background blogger discussions on this blog',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="far fa-tags ispink"></i>',
        'm_name' => 'Tags',
        'm_desc' => 'Things to be added as the parent of readers after blog is read',
        'm_parents' => array(5007,10594,10589,7703,7551,4593,4485),
    ),
    10715 => array(
        'm_icon' => '<i class="far fa-folder-tree ispink"></i>',
        'm_name' => 'Topics',
        'm_desc' => 'Defines the categories this intent belongs to in the Mench marketplace',
        'm_parents' => array(5007,7703,7551,10589,10594,4593,4485),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'Followers',
        'm_desc' => 'Keep readers & bloggers updated of updates',
        'm_parents' => array(5007,10594,10589,4527,7703,4593,7551,4485),
    ),
);

//Blog-to-Blog Interactions:
$config['en_ids_4486'] = array(4228,4229);
$config['en_all_4486'] = array(
    4228 => array(
        'm_icon' => '<i class="far fa-link rotate90 ispink"></i>',
        'm_name' => 'Blog-to- Basic',
        'm_desc' => 'Intent link published and added to user Action Plans up-front',
        'm_parents' => array(10594,10589,7703,6410,4593,4486),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Blog-to- Conditional',
        'm_desc' => 'Intent added to Action Plans after parent intent is complete AND the user\'s % score falls within the defined min/max range',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
);

//Thing-to-Thing URL Interaction Types:
$config['en_ids_4537'] = array(4259,4257,4261,4260,4256,4258);
$config['en_all_4537'] = array(
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Thing-to- Audio',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle ispink"></i>',
        'm_name' => 'Thing-to- Embed',
        'm_desc' => 'Link note contain a recognizable URL that offers an embed widget for a more engaging play-back experience.',
        'm_parents' => array(10539,10589,4593,4592,4537,4506),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Thing-to- File',
        'm_desc' => 'Link notes contain a URL to a raw file.',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Thing-to- Image',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser ispink"></i>',
        'm_name' => 'Thing-to- URL',
        'm_desc' => 'Link note contains a generic URL only.',
        'm_parents' => array(10539,10589,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Thing-to- Video',
        'm_desc' => 'Link notes contain a URL to a raw video file.',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
);

//Trained Expert Sources:
$config['en_ids_3000'] = array(2997,4446,3005,4763,3147,2999,4883,3192,5948,2998);
$config['en_all_3000'] = array(
    2997 => array(
        'm_icon' => '<i class="far fa-newspaper"></i>',
        'm_name' => 'Expert Articles',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="far fa-tachometer"></i>',
        'm_name' => 'Expert Assessments',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="far fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="far fa-megaphone"></i>',
        'm_name' => 'Expert Channels',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="far fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="far fa-microphone"></i>',
        'm_name' => 'Expert Podcasts',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    4883 => array(
        'm_icon' => '<i class="far fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="far fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="far fa-file-invoice"></i>',
        'm_name' => 'Expert Templates',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="far fa-film"></i>',
        'm_name' => 'Expert Videos',
        'm_desc' => '',
        'm_parents' => array(10571,4983,7614,3000),
    ),
);