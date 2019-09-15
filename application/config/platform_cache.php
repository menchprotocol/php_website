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

//Generated 2019-09-15 12:52:06 PST

//Mench Trainers:
$config['en_ids_10691'] = array(7512,1308,1281);
$config['en_all_10691'] = array(
    7512 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isorange"></i>',
        'm_name' => 'Mench Trainers Level 1',
        'm_desc' => 'Entry level trainers focused on intents and intent notes only',
        'm_parents' => array(10606,10691,10573,10571,4985),
    ),
    1308 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isturquoise"></i>',
        'm_name' => 'Mench Trainers Level 2',
        'm_desc' => 'Certified trainers who understand the core training principles',
        'm_parents' => array(10626,10691,10571,4463,4426),
    ),
    1281 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isnavy"></i>',
        'm_name' => 'Mench Trainers Level 3',
        'm_desc' => 'The dedicated team that ensures the continuous operation of the Mench platform, governs its principles and empowers the entire community to unleash more of their potential',
        'm_parents' => array(10618,10691,10571,4463),
    ),
);

//Link Connector Fields:
$config['en_ids_10692'] = array(4429,4369,4366,4368,4371);
$config['en_all_10692'] = array(
    4429 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Link Child Entity',
        'm_desc' => '',
        'm_parents' => array(10692,6160,6232,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Link Child Intent',
        'm_desc' => '',
        'm_parents' => array(10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Link Parent Entity',
        'm_desc' => '',
        'm_parents' => array(10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Link Parent Intent',
        'm_desc' => '',
        'm_parents' => array(10692,6202,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link ispink"></i>',
        'm_name' => 'Link Parent Link',
        'm_desc' => '',
        'm_parents' => array(10692,4367,6232,4341),
    ),
);

//Platform Cache:
$config['en_ids_4527'] = array(7758,7774,7756,6827,4997,7303,6194,6805,6177,7358,7357,6206,4592,4537,10627,6192,7712,7302,4229,6345,4485,4986,7551,7701,4983,6193,7596,7582,7767,10568,10567,7588,4737,7356,7355,7585,6201,4486,7309,10602,7751,7799,10692,7304,10591,7703,10658,6103,6186,7360,7364,7359,4341,4593,7347,10592,7529,7555,7372,7368,10691,6287,7369,4426,6225,10596,4527,7366,4755,10571,6204,6123,10593,4454,5969,3000,4600,6150,5967,4280,10570,4277,6102,7704,6144,6244,6255,6274,6146,7494,7203,10539,10594,10589,10590);
$config['en_all_4527'] = array(
    7758 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'Action Plan Intention Successful',
        'm_desc' => '',
        'm_parents' => array(4527,7493),
    ),
    7774 => array(
        'm_icon' => '<i class="fas fa-search-plus"></i>',
        'm_name' => 'Algolia Indexable',
        'm_desc' => '',
        'm_parents' => array(4428,7279,4527,7254),
    ),
    7756 => array(
        'm_icon' => '<i class="far fa-wand-magic"></i>',
        'm_name' => 'Auto Completable',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    6827 => array(
        'm_icon' => '<i class="far fa-users-crown"></i>',
        'm_name' => 'Community Members',
        'm_desc' => '&var_trimcache=Mench ',
        'm_parents' => array(3303,3314,2738,7303,4527),
    ),
    4997 => array(
        'm_icon' => '<i class="fas fa-list-alt ispink"></i>',
        'm_name' => 'Entities Mass Iterations',
        'm_desc' => '&var_trimcache=Entities ',
        'm_parents' => array(4536,4506,4426,4527),
    ),
    7303 => array(
        'm_icon' => '<i class="far fa-chart-bar blue"></i>',
        'm_name' => 'Entity Dashboard',
        'm_desc' => '&var_trimcache=Entity',
        'm_parents' => array(4527,7161,4536),
    ),
    6194 => array(
        'm_icon' => '<i class="far fa-database"></i>',
        'm_name' => 'Entity Database References',
        'm_desc' => '',
        'm_parents' => array(4758,4527,6212),
    ),
    6805 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'Entity Link Content Requires Text',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    6177 => array(
        'm_icon' => '<i class="far fa-sliders-h blue"></i>',
        'm_name' => 'Entity Status',
        'm_desc' => '&var_trimcache=Entity ',
        'm_parents' => array(6204,5003,10654,6160,6232,7303,6194,6206,4527),
    ),
    7358 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'Entity Statuses Active',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    7357 => array(
        'm_icon' => '<i class="fas fa-globe"></i>',
        'm_name' => 'Entity Statuses Public',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    6206 => array(
        'm_icon' => '<i class="far fa-table blue"></i>',
        'm_name' => 'Entity Table',
        'm_desc' => '&var_trimcache=Entity',
        'm_parents' => array(4527,7735,4536),
    ),
    4592 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Entity-to-Entity Links',
        'm_desc' => 'Links that connect entities to one another, and the type would be determined through the transaction content &var_trimcache=Entity Link',
        'm_parents' => array(5982,5981,4536,4527),
    ),
    4537 => array(
        'm_icon' => '<i class="fal fa-spider-web ispink"></i>',
        'm_name' => 'Entity-to-Entity URL Link Types',
        'm_desc' => 'Link types that connect two entities by referencing a URL to the public web &var_trimcache=Entity Link',
        'm_parents' => array(4758,4527),
    ),
    10627 => array(
        'm_icon' => '<i class="far fa-paperclip ispink"></i>',
        'm_name' => 'File Type Attachment',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    6192 => array(
        'm_icon' => '<i class="far fa-sitemap yellow"></i>',
        'm_name' => 'Intent AND',
        'm_desc' => '&var_trimcache=Intent',
        'm_parents' => array(4527,10602),
    ),
    7712 => array(
        'm_icon' => '<i class="far fa-question-circle"></i>',
        'm_name' => 'Intent Answer Types',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    7302 => array(
        'm_icon' => '<i class="far fa-chart-bar yellow"></i>',
        'm_name' => 'Intent Dashboard',
        'm_desc' => '&var_trimcache=Intent',
        'm_parents' => array(4527,7161,4535),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Intent Link Conditional',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
    6345 => array(
        'm_icon' => '<i class="fas fa-comment-check ispink"></i>',
        'm_name' => 'Intent Note Conversations',
        'm_desc' => '',
        'm_parents' => array(6768,4527),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus ispink"></i>',
        'm_name' => 'Intent Notes',
        'm_desc' => 'Links that are a message that will be sent to Masters by Mench. Think of each message as a mini user interface (UI) that will communicate a key point in a way that its easily understandable. Messages have different types based on when and how they are communicated to Masters. &var_trimcache=Intent Note',
        'm_parents' => array(7552,4535,4527,4463),
    ),
    4986 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Intent Notes Entity Referencing Optional',
        'm_desc' => 'Used to calculate the entity references for miners, experts, and sources within intent notes.',
        'm_parents' => array(4758,6768,4527),
    ),
    7551 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Intent Notes Entity Referencing Required',
        'm_desc' => '',
        'm_parents' => array(4527,6768,4758),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-rss ispink"></i>',
        'm_name' => 'Intent Note Subscriber',
        'm_desc' => 'Enforces Subscribers that are a grandchild of this entity.',
        'm_parents' => array(10594,10589,4527,7703,4593,7551,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => 'Enforces Authors that are a grandchild of this entity.',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    6193 => array(
        'm_icon' => '<i class="far fa-code-merge yellow"></i>',
        'm_name' => 'Intent OR',
        'm_desc' => '&var_trimcache=Intent',
        'm_parents' => array(10602,4527),
    ),
    7596 => array(
        'm_icon' => '<i class="fas fa-mountains isgreen"></i>',
        'm_name' => 'Intent Scope',
        'm_desc' => '&var_trimcache=Intent',
        'm_parents' => array(10610,6204,10649,7302,6160,6201,6194,6232,4527),
    ),
    7582 => array(
        'm_icon' => '<i class="fas fa-flag"></i>',
        'm_name' => 'Intent Scopes Get Started',
        'm_desc' => '',
        'm_parents' => array(7493,4527,6768),
    ),
    7767 => array(
        'm_icon' => '<i class="fas fa-hard-hat"></i>',
        'm_name' => 'Intent Scopes Mineable',
        'm_desc' => '&var_trimcache=Intent',
        'm_parents' => array(4428,4527,6768),
    ),
    10568 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Intent Scopes Searchable',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    10567 => array(
        'm_icon' => '<i class="far fa-equals"></i>',
        'm_name' => 'Intent Scopes Supports Equal',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    7588 => array(
        'm_icon' => '<i class="far fa-hand-pointer"></i>',
        'm_name' => 'Intent Select Publicly',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    4737 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow"></i>',
        'm_name' => 'Intent Status',
        'm_desc' => '&var_trimcache=Intent ',
        'm_parents' => array(6204,10648,6226,6160,6232,7302,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'Intent Statuses Active',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'Intent Statuses Public',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    7585 => array(
        'm_icon' => '<i class="far fa-shapes yellow"></i>',
        'm_name' => 'Intent Subtype',
        'm_desc' => '&var_trimcache=Intent',
        'm_parents' => array(10608,6204,10651,6160,6194,6232,4527,6201),
    ),
    6201 => array(
        'm_icon' => '<i class="far fa-table yellow"></i>',
        'm_name' => 'Intent Table',
        'm_desc' => '&var_trimcache=Intent',
        'm_parents' => array(4527,7735,4535),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Intent-to-Intent Links',
        'm_desc' => 'Transactions that link 2 intents to each other. &var_trimcache=Intent Link',
        'm_parents' => array(10662,4535,4527),
    ),
    7309 => array(
        'm_icon' => '<i class="far fa-cubes"></i>',
        'm_name' => 'Intent Type Requirement',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-shapes yellow"></i>',
        'm_name' => 'Intent Types',
        'm_desc' => '&var_trimcache=Intent',
        'm_parents' => array(6204,7302,4527,6768),
    ),
    7751 => array(
        'm_icon' => '<i class="far fa-upload"></i>',
        'm_name' => 'Intent Upload File',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    7799 => array(
        'm_icon' => '<i class="far fa-calendar"></i>',
        'm_name' => 'Leaderboard Time Frames',
        'm_desc' => '&var_trimcache=Leaderboard',
        'm_parents' => array(4527,7797),
    ),
    10692 => array(
        'm_icon' => '<i class="fas fa-bezier-curve ispink"></i>',
        'm_name' => 'Link Connector Fields',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    7304 => array(
        'm_icon' => '<i class="far fa-chart-bar ispink"></i>',
        'm_name' => 'Link Dashboard',
        'm_desc' => '&var_trimcache=Link',
        'm_parents' => array(4527,6205,7161),
    ),
    10591 => array(
        'm_icon' => '<i class="fas fa-directions ispink"></i>',
        'm_name' => 'Link Directions',
        'm_desc' => '',
        'm_parents' => array(6204,7797,7304,6771,4527,10588),
    ),
    7703 => array(
        'm_icon' => '<i class="fas fa-rss ispink"></i>',
        'm_name' => 'Link Intent Subscription Types',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    10658 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'Link Iterations',
        'm_desc' => '',
        'm_parents' => array(4527,6205),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda ispink"></i>',
        'm_name' => 'Link Metadata',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Link Status',
        'm_desc' => '&var_trimcache=Link ',
        'm_parents' => array(10677,10661,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    7360 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'Link Statuses Active',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    7364 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin"></i>',
        'm_name' => 'Link Statuses Incomplete',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    7359 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'Link Statuses Public',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    4341 => array(
        'm_icon' => '<i class="far fa-table ispink"></i>',
        'm_name' => 'Link Table',
        'm_desc' => '&var_trimcache=Link',
        'm_parents' => array(4527,7735,6205),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug ispink ispink"></i>',
        'm_name' => 'Link Type',
        'm_desc' => '',
        'm_parents' => array(10607,10659,6160,6232,6194,4527,4341),
    ),
    7347 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Link Type User Set Intention',
        'm_desc' => '&var_trimcache=User',
        'm_parents' => array(4527,6219),
    ),
    10592 => array(
        'm_icon' => '<i class="fas fa-weight ispink"></i>',
        'm_name' => 'Link Word Weight',
        'm_desc' => '',
        'm_parents' => array(6204,6771,4527,10588),
    ),
    7529 => array(
        'm_icon' => '<i class="fas fa-hat-wizard"></i>',
        'm_name' => 'Mench Conversation Templates',
        'm_desc' => '&var_trimcache=Mench',
        'm_parents' => array(2738,4527),
    ),
    7555 => array(
        'm_icon' => '<i class="fas fa-comments"></i>',
        'm_name' => 'Mench Platform Users',
        'm_desc' => '&var_trimcache=Mench ',
        'm_parents' => array(7303,7372,4527),
    ),
    7372 => array(
        'm_icon' => '<i class="fas fa-layer-group"></i>',
        'm_name' => 'Mench Products',
        'm_desc' => '',
        'm_parents' => array(2738,4527),
    ),
    7368 => array(
        'm_icon' => '<i class="far fa-user-hard-hat"></i>',
        'm_name' => 'Mench Trainer App',
        'm_desc' => '',
        'm_parents' => array(7372,4527),
    ),
    10691 => array(
        'm_icon' => '<i class="far fa-user-hard-hat"></i>',
        'm_name' => 'Mench Trainers',
        'm_desc' => '',
        'm_parents' => array(4527,7368,6827),
    ),
    6287 => array(
        'm_icon' => '<i class="far fa-tools"></i>',
        'm_name' => 'Mench Trainer Tools',
        'm_desc' => '',
        'm_parents' => array(7368,4527,7284),
    ),
    7369 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Mench User App',
        'm_desc' => '&var_trimcache=Mench',
        'm_parents' => array(7372,4527),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Modification Lock',
        'm_desc' => '',
        'm_parents' => array(3303,6771,4426,4527,4757,4428),
    ),
    6225 => array(
        'm_icon' => '<i class="fas fa-keyboard"></i>',
        'm_name' => 'My Account Inputs',
        'm_desc' => '&var_trimcache=Entity',
        'm_parents' => array(7552,4527,6137),
    ),
    10596 => array(
        'm_icon' => '<i class="fas fa-square-root ispink"></i>',
        'm_name' => 'Nod',
        'm_desc' => '',
        'm_parents' => array(4527,10592),
    ),
    4527 => array(
        'm_icon' => '<i class="fas fa-memory"></i>',
        'm_name' => 'Platform Cache',
        'm_desc' => '',
        'm_parents' => array(4527,7305,6404,7258,7254,4506),
    ),
    7366 => array(
        'm_icon' => '<i class="far fa-eye-slash"></i>',
        'm_name' => 'Private Intent Types',
        'm_desc' => '',
        'm_parents' => array(4527,6768),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash ispink"></i>',
        'm_name' => 'Private Link Types',
        'm_desc' => '',
        'm_parents' => array(6771,4463,4426,4527,4757),
    ),
    10571 => array(
        'm_icon' => '<i class="fas fa-globe"></i>',
        'm_name' => 'Public Entities',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    6204 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'Single Selectable',
        'm_desc' => '',
        'm_parents' => array(4428,4506,4527,4758),
    ),
    6123 => array(
        'm_icon' => '<i class="far fa-share-alt-square blue"></i>',
        'm_name' => 'Social Profiles',
        'm_desc' => '',
        'm_parents' => array(6225,4527),
    ),
    10593 => array(
        'm_icon' => '<i class="fas fa-union ispink"></i>',
        'm_name' => 'Statement + Connections',
        'm_desc' => '',
        'm_parents' => array(4527,10592),
    ),
    4454 => array(
        'm_icon' => '<i class="far fa-bells blue"></i>',
        'm_name' => 'Subscription Settings',
        'm_desc' => '',
        'm_parents' => array(7552,6225,6204,4527),
    ),
    5969 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'System Lock',
        'm_desc' => '',
        'm_parents' => array(3303,6771,4426,4527,4757,4428),
    ),
    3000 => array(
        'm_icon' => '<i class="far fa-whistle"></i>',
        'm_name' => 'Trained Expert Sources',
        'm_desc' => '',
        'm_parents' => array(10571,7303,3463,4506,4527,4463),
    ),
    4600 => array(
        'm_icon' => '<i class="far fa-badge-check"></i>',
        'm_name' => 'User Account Types',
        'm_desc' => '',
        'm_parents' => array(4758,4527),
    ),
    6150 => array(
        'm_icon' => '<i class="far fa-comment-check ispink"></i>',
        'm_name' => 'User Completed Intention',
        'm_desc' => '&var_trimcache=User',
        'm_parents' => array(4527,6219),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Link CC Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4506,4527,7569,4755,4593),
    ),
    4280 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Received Messages with Messenger',
        'm_desc' => 'Links related to Mench sending various types of messages',
        'm_parents' => array(6771,4527),
    ),
    10570 => array(
        'm_icon' => '<i class="far fa-hand-pointer"></i>',
        'm_name' => 'User Selectable Completion',
        'm_desc' => '&var_trimcache=User',
        'm_parents' => array(4527,7493),
    ),
    4277 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Sent Messages with Messenger',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    6102 => array(
        'm_icon' => '<i class="far fa-paperclip"></i>',
        'm_name' => 'User Sent/Received Attachment',
        'm_desc' => 'When we receive a media file from a student on messenger.',
        'm_parents' => array(6771,4527),
    ),
    7704 => array(
        'm_icon' => '<i class="far fa-check-circle ispink"></i>',
        'm_name' => 'User Step Answered Successfully',
        'm_desc' => '',
        'm_parents' => array(4527,7493),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'User Step Create New Content',
        'm_desc' => '&var_trimcache=Intent',
        'm_parents' => array(10596,10589,4527,6768,7703,6255,6244,4755,6146,4593),
    ),
    6244 => array(
        'm_icon' => '<i class="far fa-shoe-prints ispink"></i>',
        'm_name' => 'User Steps Double',
        'm_desc' => '',
        'm_parents' => array(7493,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-walking ispink"></i>',
        'm_name' => 'User Steps Progressed',
        'm_desc' => '',
        'm_parents' => array(7493,4527),
    ),
    6274 => array(
        'm_icon' => '<i class="fas fa-fast-forward ispink"></i>',
        'm_name' => 'User Steps Skippable',
        'm_desc' => '',
        'm_parents' => array(7493,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="far fa-check-square ispink"></i>',
        'm_name' => 'User Steps Taken',
        'm_desc' => '&var_trimcache=User Step',
        'm_parents' => array(6219,4527),
    ),
    7494 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Steps Unlock',
        'm_desc' => '',
        'm_parents' => array(4506,4527,7493),
    ),
    7203 => array(
        'm_icon' => '<i class="far fa-calendar-week"></i>',
        'm_name' => 'Weekly Leaderboard Message',
        'm_desc' => 'A message that gets sent out weekly to inform the Mench community of its top performers ending Sunday Midnight PST',
        'm_parents' => array(7800,7308,4527),
    ),
    10539 => array(
        'm_icon' => '<i class="far fa-file-word ispink"></i>',
        'm_name' => 'Word',
        'm_desc' => '',
        'm_parents' => array(4527,10592,5008),
    ),
    10594 => array(
        'm_icon' => '<i class="fas fa-value-absolute ispink"></i>',
        'm_name' => 'Word + Connections',
        'm_desc' => '',
        'm_parents' => array(4527,10592),
    ),
    10589 => array(
        'm_icon' => '<i class="far fa-download ispink"></i>',
        'm_name' => 'Words In',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
    10590 => array(
        'm_icon' => '<i class="far fa-upload ispink"></i>',
        'm_name' => 'Words Out',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
);

//Link Iterations:
$config['en_ids_10658'] = array(10657,10656,10659,10689,10673,10663,10664,10661,10662,10686,10660,10679,10677,10676,10678,10681,10675,7578,10690,10683,10687,10685);
$config['en_all_10658'] = array(
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Entity Link Iterated Content',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    10656 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entity Link Iterated Status',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10539,10658,10645),
    ),
    10659 => array(
        'm_icon' => '<i class="fas fa-plug ispink"></i>',
        'm_name' => 'Entity Link Iterated Type',
        'm_desc' => '',
        'm_parents' => array(10658,10589,4593,10596,10645),
    ),
    10689 => array(
        'm_icon' => '<i class="fas fa-share-alt rotate90 ispink"></i>',
        'm_name' => 'Entity Link Merged',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10658,10645),
    ),
    10673 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Entity Link Unlinked',
        'm_desc' => '',
        'm_parents' => array(10645,4593,10539,10589,10658),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-file-certificate ispink"></i>',
        'm_name' => 'Intent Link Iterated Marks',
        'm_desc' => '',
        'm_parents' => array(4228,7703,10539,10589,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Intent Link Iterated Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    10661 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Link Iterated Status',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Intent Link Iterated Type',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10686 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Link Unlinked',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10539,4593,10658,10638),
    ),
    10660 => array(
        'm_icon' => '<i class="fas fa-angle-double-right ispink"></i>',
        'm_name' => 'Intent Migrate Parent Link',
        'm_desc' => '',
        'm_parents' => array(7703,4593,10638,10589,10594,10658),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Intent Notes Iterated Content',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Notes Iterated Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10676 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intent Notes Ordered',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Notes Unlinked',
        'm_desc' => '',
        'm_parents' => array(10658,10539,10589,4593,10638),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered Automatically',
        'm_desc' => '',
        'm_parents' => array(10638,10589,10596,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered by Trainer',
        'm_desc' => '',
        'm_parents' => array(7703,10539,10589,4593,10658,10638),
    ),
    7578 => array(
        'm_icon' => '<i class="far fa-key ispink"></i>',
        'm_name' => 'User Iterated Password',
        'm_desc' => '',
        'm_parents' => array(6222,10658,6153,10539,10589,4755,4593),
    ),
    10690 => array(
        'm_icon' => '<i class="fas fa-upload ispink"></i>',
        'm_name' => 'User Media Uploaded',
        'm_desc' => '',
        'm_parents' => array(6153,10596,10589,4593,10658),
    ),
    10683 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Read Email',
        'm_desc' => '',
        'm_parents' => array(6153,10658,10596,10589,4593,7654),
    ),
    10687 => array(
        'm_icon' => '<i class="far fa-shield-check ispink"></i>',
        'm_name' => 'User Sent Requirement',
        'm_desc' => '',
        'm_parents' => array(4755,10589,10596,4593,6153,10658,6146,6255),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'User Step Iterated',
        'm_desc' => '',
        'm_parents' => array(4755,10596,10589,4593,10638,10658,6255,6146),
    ),
);

//Word:
$config['en_ids_10539'] = array(10672,10653,10654,4257,4319,10656,4230,10673,4256,10671,10650,10649,10648,10651,10663,10661,10662,10686,10677,10676,10678,10675,6132,6154,6155,4235,7757,7578,6224,4557,4299,4460,4547,6140);
$config['en_all_10539'] = array(
    10672 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Entity Iterated Archived',
        'm_desc' => '',
        'm_parents' => array(10539,4593,10589,10645),
    ),
    10653 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Entity Iterated Icon',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
    10654 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entity Iterated Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle ispink"></i>',
        'm_name' => 'Entity Link Embed',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537,4506),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down ispink"></i>',
        'm_name' => 'Entity Link Integer',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    10656 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entity Link Iterated Status',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10539,10658,10645),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-level-up rotate90 ispink"></i>',
        'm_name' => 'Entity Link Raw',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    10673 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Entity Link Unlinked',
        'm_desc' => '',
        'm_parents' => array(10645,4593,10539,10589,10658),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser ispink"></i>',
        'm_name' => 'Entity Link URL',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537),
    ),
    10671 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Iterated Archived',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,7703,10638),
    ),
    10650 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Intent Iterated Completion Time',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10649 => array(
        'm_icon' => '<i class="fas fa-mountains ispink"></i>',
        'm_name' => 'Intent Iterated Scope',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10648 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Iterated Status',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="far fa-shapes ispink"></i>',
        'm_name' => 'Intent Iterated Subtype',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-file-certificate ispink"></i>',
        'm_name' => 'Intent Link Iterated Marks',
        'm_desc' => '',
        'm_parents' => array(4228,7703,10539,10589,10638,4593,10658),
    ),
    10661 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Link Iterated Status',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Intent Link Iterated Type',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10686 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Link Unlinked',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10539,4593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Notes Iterated Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10676 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intent Notes Ordered',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10678 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Notes Unlinked',
        'm_desc' => '',
        'm_parents' => array(10658,10539,10589,4593,10638),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered by Trainer',
        'm_desc' => '',
        'm_parents' => array(7703,10539,10589,4593,10658,10638),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered by User',
        'm_desc' => '',
        'm_parents' => array(10638,10539,10639,10589,6153,4506,4755,4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'User Intent Accomplished',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle ispink"></i>',
        'm_name' => 'User Intent Cancelled',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Set',
        'm_desc' => '',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
    7757 => array(
        'm_icon' => '<i class="fas fa-times-octagon ispink"></i>',
        'm_name' => 'User Intent Terminated',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,4593,6150),
    ),
    7578 => array(
        'm_icon' => '<i class="far fa-key ispink"></i>',
        'm_name' => 'User Iterated Password',
        'm_desc' => '',
        'm_parents' => array(6222,10658,6153,10539,10589,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="far fa-sync ispink"></i>',
        'm_name' => 'User Iterated Profile',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,6222,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle ispink"></i>',
        'm_name' => 'User Sent Location',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-id-badge ispink"></i>',
        'm_name' => 'User Sent Profile Photo',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7654,6222,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'User Sent Text',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Link Unlock',
        'm_desc' => '',
        'm_parents' => array(10539,10589,6410,4229,4755,4593),
    ),
);

//File Type Attachment:
$config['en_ids_10627'] = array(4259,4261,4260,4258,4554,4556,4555,4553,4549,4551,4550,4548);
$config['en_all_10627'] = array(
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Entity Link Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Entity Link File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Entity Link Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Entity Link Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
);

//Word + Connections:
$config['en_ids_10594'] = array(4318,4229,10664,4228,10660,7545,7701,10573);
$config['en_all_10594'] = array(
    4318 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Entity Link Time',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,4592),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Intent Link Conditional',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Intent Link Iterated Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    4228 => array(
        'm_icon' => '<i class="fas fa-walking ispink"></i>',
        'm_name' => 'Intent Link Required',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,6410,4593,4486),
    ),
    10660 => array(
        'm_icon' => '<i class="fas fa-angle-double-right ispink"></i>',
        'm_name' => 'Intent Migrate Parent Link',
        'm_desc' => '',
        'm_parents' => array(7703,4593,10638,10589,10594,10658),
    ),
    7545 => array(
        'm_icon' => '<i class="far fa-tag ispink"></i>',
        'm_name' => 'Intent Note Entity Tag',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,7551,4593,4485),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-rss ispink"></i>',
        'm_name' => 'Intent Note Subscriber',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4527,7703,4593,7551,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="far fa-user-hard-hat ispink"></i>',
        'm_name' => 'Intent Note Trainer',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
);

//Statement + Connections:
$config['en_ids_10593'] = array(4251,10646,4259,4261,4260,10657,4255,4258,4250,10644,6093,6242,4601,4231,10679,4983,4554,4570,4556,4555,7702,6563,4552,4553,4549,4551,4550,4548);
$config['en_all_10593'] = array(
    4251 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Entity Created',
        'm_desc' => '',
        'm_parents' => array(10645,10593,10589,4593),
    ),
    10646 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entity Iterated Name',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10645),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Entity Link Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Entity Link File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Entity Link Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Entity Link Iterated Content',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Entity Link Text',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4593,4592),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Entity Link Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Intent Created',
        'm_desc' => '',
        'm_parents' => array(10638,10593,10589,4593),
    ),
    10644 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Intent Iterated Outcome',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10593,4593,10638),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Intent Note Changelog',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-lightbulb-on ispink"></i>',
        'm_name' => 'Intent Note Endnote',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4603,4593,4986,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-search ispink"></i>',
        'm_name' => 'Intent Note Keyword',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Intent Notes Iterated Content',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="far fa-envelope ispink"></i>',
        'm_name' => 'User Received Email',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7702 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Received Intent Email',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'User Received Text',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
);

//Nod:
$config['en_ids_10596'] = array(10625,5943,5001,5865,4999,4998,5000,5981,5982,5003,10659,10689,10647,6226,10681,4246,7504,5007,4994,4993,6415,4275,6559,6560,6556,6578,6149,7611,6969,4283,7495,7542,7610,5967,7563,10690,4266,4267,4282,10683,4577,4278,4279,4268,10687,4287,7561,7564,7559,7560,7558,7488,7485,7486,6144,7741,10685,4559,7489,7492,6997,6157,7487,6143,7562);
$config['en_all_10596'] = array(
    10625 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Entities Icon Replace',
        'm_desc' => '',
        'm_parents' => array(10589,10596,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Entities Icon Update',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Entities Link Contents',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entities Link Status',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entities Name Postfix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entities Name Prefix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entities Name Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fas fa-layer-plus ispink"></i>',
        'm_name' => 'Entities Parent Add',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fas fa-layer-minus ispink"></i>',
        'm_name' => 'Entities Parent Remove',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entities Status Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    10659 => array(
        'm_icon' => '<i class="fas fa-plug ispink"></i>',
        'm_name' => 'Entity Link Iterated Type',
        'm_desc' => '',
        'm_parents' => array(10658,10589,4593,10596,10645),
    ),
    10689 => array(
        'm_icon' => '<i class="fas fa-share-alt rotate90 ispink"></i>',
        'm_name' => 'Entity Link Merged',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10658,10645),
    ),
    10647 => array(
        'm_icon' => '<i class="far fa-tools ispink"></i>',
        'm_name' => 'Intent Iterated Verb',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10638),
    ),
    6226 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intents Iterated Statuses',
        'm_desc' => '',
        'm_parents' => array(10655,10596,10589,4593),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered Automatically',
        'm_desc' => '',
        'm_parents' => array(10638,10589,10596,4755,4593,10658),
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
    5007 => array(
        'm_icon' => '<i class="fas fa-expand-arrows ispink"></i>',
        'm_name' => 'Trainer Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7368,4757,4593),
    ),
    4994 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Trainer View Entity',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4593),
    ),
    4993 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Trainer View Intent',
        'm_desc' => '',
        'm_parents' => array(10596,10590,7612,4593),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function ispink"></i>',
        'm_name' => 'User Cleared Action Plan',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,6418,4593,6414),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Intent',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10589,6554,4755,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Next',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus ispink"></i>',
        'm_name' => 'User Intent Considered',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7611 => array(
        'm_icon' => '<i class="fas fa-hand-pointer ispink"></i>',
        'm_name' => 'User Intent Expanded',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,7610,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="fas fa-megaphone ispink"></i>',
        'm_name' => 'User Intent Featured',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,4593,4755,6153),
    ),
    4283 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Intent Listed',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Recommended',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    7542 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Required',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Intent Viewed',
        'm_desc' => '',
        'm_parents' => array(10596,10590,7765,7612,4755,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Link CC Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4506,4527,7569,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Magic-Link Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    10690 => array(
        'm_icon' => '<i class="fas fa-upload ispink"></i>',
        'm_name' => 'User Media Uploaded',
        'm_desc' => '',
        'm_parents' => array(6153,10596,10589,4593,10658),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Opened Profile',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,6222,4593),
    ),
    10683 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Read Email',
        'm_desc' => '',
        'm_parents' => array(6153,10658,10596,10589,4593,7654),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'User Sent Access',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Sent Messenger Read',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download ispink"></i>',
        'm_name' => 'User Sent Messenger Received',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag ispink"></i>',
        'm_name' => 'User Sent Postback',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    10687 => array(
        'm_icon' => '<i class="far fa-shield-check ispink"></i>',
        'm_name' => 'User Sent Requirement',
        'm_desc' => '',
        'm_parents' => array(4755,10589,10596,4593,6153,10658,6146,6255),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'User Sent Unknown Message',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin Generally',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin Success',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fas fa-envelope-open ispink"></i>',
        'm_name' => 'User Signin with Email',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin with Intention',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Signin with Messenger',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7488 => array(
        'm_icon' => '<i class="far fa-calendar-times ispink"></i>',
        'm_name' => 'User Step Answer Timeout',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'User Step Create New Content',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4527,6768,7703,6255,6244,4755,6146,4593),
    ),
    7741 => array(
        'm_icon' => '<i class="far fa-times-circle ispink"></i>',
        'm_name' => 'User Step Intention Terminated',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,7740,6146),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'User Step Iterated',
        'm_desc' => '',
        'm_parents' => array(4755,10596,10589,4593,10638,10658,6255,6146),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comments ispink"></i>',
        'm_name' => 'User Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="far fa-check-double ispink"></i>',
        'm_name' => 'User Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="far fa-times-square ispink"></i>',
        'm_name' => 'User Step Path Not Found',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6255,4755,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="far fa-stopwatch ispink"></i>',
        'm_name' => 'User Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-fast-forward ispink"></i>',
        'm_name' => 'User Step Skipped',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6146,4755,4593),
    ),
    7562 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Welcome Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
);

//Words Out:
$config['en_ids_10590'] = array(4994,4993,6149,7611,6969,4283,7495,7542,7610,5967,7563,4282,4554,4570,4556,4555,7702,6563,4552,4553,7562);
$config['en_all_10590'] = array(
    4994 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Trainer View Entity',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4593),
    ),
    4993 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Trainer View Intent',
        'm_desc' => '',
        'm_parents' => array(10596,10590,7612,4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus ispink"></i>',
        'm_name' => 'User Intent Considered',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7611 => array(
        'm_icon' => '<i class="fas fa-hand-pointer ispink"></i>',
        'm_name' => 'User Intent Expanded',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,7610,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="fas fa-megaphone ispink"></i>',
        'm_name' => 'User Intent Featured',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,4593,4755,6153),
    ),
    4283 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Intent Listed',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Recommended',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    7542 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Required',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Intent Viewed',
        'm_desc' => '',
        'm_parents' => array(10596,10590,7765,7612,4755,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Link CC Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4506,4527,7569,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Magic-Link Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Opened Profile',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,6222,4593),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="far fa-envelope ispink"></i>',
        'm_name' => 'User Received Email',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7702 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Received Intent Email',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'User Received Text',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7562 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Welcome Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
);

//Words In:
$config['en_ids_10589'] = array(10625,5943,5001,5865,4999,4998,5000,5981,5982,5003,4251,10672,10653,10646,10654,4259,4257,4261,4260,4319,10657,10656,10659,10689,4230,4255,4318,10673,4256,4258,4250,10671,10650,10644,10649,10648,10651,10647,4229,10663,10664,10661,10662,4228,10686,10660,6093,6242,7545,4601,4231,10679,10677,10676,7701,10678,10573,4983,6226,10681,10675,6132,4246,7504,5007,6415,4275,6559,6560,6556,6578,6154,6155,4235,7757,7578,6224,10690,4266,4267,10683,4577,4549,4551,4550,4557,4278,4279,4268,4299,4460,10687,4547,4287,4548,7561,7564,7559,7560,7558,7488,7485,7486,6144,7741,10685,6140,4559,7489,7492,6997,6157,7487,6143);
$config['en_all_10589'] = array(
    10625 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Entities Icon Replace',
        'm_desc' => '',
        'm_parents' => array(10589,10596,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Entities Icon Update',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Entities Link Contents',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entities Link Status',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entities Name Postfix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entities Name Prefix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entities Name Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fas fa-layer-plus ispink"></i>',
        'm_name' => 'Entities Parent Add',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fas fa-layer-minus ispink"></i>',
        'm_name' => 'Entities Parent Remove',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entities Status Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Entity Created',
        'm_desc' => '',
        'm_parents' => array(10645,10593,10589,4593),
    ),
    10672 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Entity Iterated Archived',
        'm_desc' => '',
        'm_parents' => array(10539,4593,10589,10645),
    ),
    10653 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Entity Iterated Icon',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
    10646 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entity Iterated Name',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10645),
    ),
    10654 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entity Iterated Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Entity Link Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle ispink"></i>',
        'm_name' => 'Entity Link Embed',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537,4506),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Entity Link File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Entity Link Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down ispink"></i>',
        'm_name' => 'Entity Link Integer',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Entity Link Iterated Content',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    10656 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entity Link Iterated Status',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10539,10658,10645),
    ),
    10659 => array(
        'm_icon' => '<i class="fas fa-plug ispink"></i>',
        'm_name' => 'Entity Link Iterated Type',
        'm_desc' => '',
        'm_parents' => array(10658,10589,4593,10596,10645),
    ),
    10689 => array(
        'm_icon' => '<i class="fas fa-share-alt rotate90 ispink"></i>',
        'm_name' => 'Entity Link Merged',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10658,10645),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-level-up rotate90 ispink"></i>',
        'm_name' => 'Entity Link Raw',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Entity Link Text',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Entity Link Time',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,4592),
    ),
    10673 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Entity Link Unlinked',
        'm_desc' => '',
        'm_parents' => array(10645,4593,10539,10589,10658),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser ispink"></i>',
        'm_name' => 'Entity Link URL',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Entity Link Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Intent Created',
        'm_desc' => '',
        'm_parents' => array(10638,10593,10589,4593),
    ),
    10671 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Iterated Archived',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,7703,10638),
    ),
    10650 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Intent Iterated Completion Time',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Intent Iterated Outcome',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10593,4593,10638),
    ),
    10649 => array(
        'm_icon' => '<i class="fas fa-mountains ispink"></i>',
        'm_name' => 'Intent Iterated Scope',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10648 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Iterated Status',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="far fa-shapes ispink"></i>',
        'm_name' => 'Intent Iterated Subtype',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10647 => array(
        'm_icon' => '<i class="far fa-tools ispink"></i>',
        'm_name' => 'Intent Iterated Verb',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10638),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Intent Link Conditional',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-file-certificate ispink"></i>',
        'm_name' => 'Intent Link Iterated Marks',
        'm_desc' => '',
        'm_parents' => array(4228,7703,10539,10589,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Intent Link Iterated Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    10661 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Link Iterated Status',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Intent Link Iterated Type',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    4228 => array(
        'm_icon' => '<i class="fas fa-walking ispink"></i>',
        'm_name' => 'Intent Link Required',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,6410,4593,4486),
    ),
    10686 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Link Unlinked',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10539,4593,10658,10638),
    ),
    10660 => array(
        'm_icon' => '<i class="fas fa-angle-double-right ispink"></i>',
        'm_name' => 'Intent Migrate Parent Link',
        'm_desc' => '',
        'm_parents' => array(7703,4593,10638,10589,10594,10658),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Intent Note Changelog',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-lightbulb-on ispink"></i>',
        'm_name' => 'Intent Note Endnote',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4603,4593,4986,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="far fa-tag ispink"></i>',
        'm_name' => 'Intent Note Entity Tag',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,7551,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-search ispink"></i>',
        'm_name' => 'Intent Note Keyword',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Intent Notes Iterated Content',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Notes Iterated Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10676 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intent Notes Ordered',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-rss ispink"></i>',
        'm_name' => 'Intent Note Subscriber',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4527,7703,4593,7551,4485),
    ),
    10678 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Notes Unlinked',
        'm_desc' => '',
        'm_parents' => array(10658,10539,10589,4593,10638),
    ),
    10573 => array(
        'm_icon' => '<i class="far fa-user-hard-hat ispink"></i>',
        'm_name' => 'Intent Note Trainer',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    6226 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intents Iterated Statuses',
        'm_desc' => '',
        'm_parents' => array(10655,10596,10589,4593),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered Automatically',
        'm_desc' => '',
        'm_parents' => array(10638,10589,10596,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered by Trainer',
        'm_desc' => '',
        'm_parents' => array(7703,10539,10589,4593,10658,10638),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered by User',
        'm_desc' => '',
        'm_parents' => array(10638,10539,10639,10589,6153,4506,4755,4593),
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
    5007 => array(
        'm_icon' => '<i class="fas fa-expand-arrows ispink"></i>',
        'm_name' => 'Trainer Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7368,4757,4593),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function ispink"></i>',
        'm_name' => 'User Cleared Action Plan',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,6418,4593,6414),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Intent',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10589,6554,4755,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Next',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'User Intent Accomplished',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle ispink"></i>',
        'm_name' => 'User Intent Cancelled',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Set',
        'm_desc' => '',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
    7757 => array(
        'm_icon' => '<i class="fas fa-times-octagon ispink"></i>',
        'm_name' => 'User Intent Terminated',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,4593,6150),
    ),
    7578 => array(
        'm_icon' => '<i class="far fa-key ispink"></i>',
        'm_name' => 'User Iterated Password',
        'm_desc' => '',
        'm_parents' => array(6222,10658,6153,10539,10589,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="far fa-sync ispink"></i>',
        'm_name' => 'User Iterated Profile',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,6222,4593),
    ),
    10690 => array(
        'm_icon' => '<i class="fas fa-upload ispink"></i>',
        'm_name' => 'User Media Uploaded',
        'm_desc' => '',
        'm_parents' => array(6153,10596,10589,4593,10658),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    10683 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Read Email',
        'm_desc' => '',
        'm_parents' => array(6153,10658,10596,10589,4593,7654),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'User Sent Access',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle ispink"></i>',
        'm_name' => 'User Sent Location',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Sent Messenger Read',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download ispink"></i>',
        'm_name' => 'User Sent Messenger Received',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag ispink"></i>',
        'm_name' => 'User Sent Postback',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-id-badge ispink"></i>',
        'm_name' => 'User Sent Profile Photo',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7654,6222,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    10687 => array(
        'm_icon' => '<i class="far fa-shield-check ispink"></i>',
        'm_name' => 'User Sent Requirement',
        'm_desc' => '',
        'm_parents' => array(4755,10589,10596,4593,6153,10658,6146,6255),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'User Sent Text',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'User Sent Unknown Message',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin Generally',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin Success',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fas fa-envelope-open ispink"></i>',
        'm_name' => 'User Signin with Email',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin with Intention',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Signin with Messenger',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7488 => array(
        'm_icon' => '<i class="far fa-calendar-times ispink"></i>',
        'm_name' => 'User Step Answer Timeout',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'User Step Create New Content',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4527,6768,7703,6255,6244,4755,6146,4593),
    ),
    7741 => array(
        'm_icon' => '<i class="far fa-times-circle ispink"></i>',
        'm_name' => 'User Step Intention Terminated',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,7740,6146),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'User Step Iterated',
        'm_desc' => '',
        'm_parents' => array(4755,10596,10589,4593,10638,10658,6255,6146),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Link Unlock',
        'm_desc' => '',
        'm_parents' => array(10539,10589,6410,4229,4755,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comments ispink"></i>',
        'm_name' => 'User Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="far fa-check-double ispink"></i>',
        'm_name' => 'User Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="far fa-times-square ispink"></i>',
        'm_name' => 'User Step Path Not Found',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6255,4755,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="far fa-stopwatch ispink"></i>',
        'm_name' => 'User Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-fast-forward ispink"></i>',
        'm_name' => 'User Step Skipped',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6146,4755,4593),
    ),
);

//Link Type User Set Intention:
$config['en_ids_7347'] = array(7495,7542,4235);
$config['en_all_7347'] = array(
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Intent Recommended',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    7542 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Intent Required',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Intent Set',
        'm_desc' => '',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
);

//Intent AND:
$config['en_ids_6192'] = array(6914,7637,6677,6683,6682,6679,6680,6678,6681);
$config['en_all_6192'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes yellow"></i>',
        'm_name' => 'Require All',
        'm_desc' => '',
        'm_parents' => array(6192,7756,7585,7486,7485,7309,6997),
    ),
    7637 => array(
        'm_icon' => '<i class="fas fa-film yellow"></i>',
        'm_name' => 'Upload Multimedia',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6677 => array(
        'm_icon' => '<i class="far fa-comments yellow"></i>',
        'm_name' => 'Read-Only',
        'm_desc' => '',
        'm_parents' => array(7756,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard yellow"></i>',
        'm_name' => 'Send Text Message',
        'm_desc' => '',
        'm_parents' => array(10687,7585,6144,6192),
    ),
    6682 => array(
        'm_icon' => '<i class="far fa-external-link yellow"></i>',
        'm_name' => 'Send URL',
        'm_desc' => '',
        'm_parents' => array(10687,7585,6144,6192),
    ),
    6679 => array(
        'm_icon' => '<i class="far fa-video yellow"></i>',
        'm_name' => 'Upload Video',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6680 => array(
        'm_icon' => '<i class="far fa-microphone yellow"></i>',
        'm_name' => 'Upload Audio',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6678 => array(
        'm_icon' => '<i class="far fa-image yellow"></i>',
        'm_name' => 'Upload Image',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6681 => array(
        'm_icon' => '<i class="far fa-file-pdf yellow"></i>',
        'm_name' => 'Upload File',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
);

//Intent Types:
$config['en_ids_10602'] = array(6192,6193);
$config['en_all_10602'] = array(
    6192 => array(
        'm_icon' => '<i class="far fa-sitemap yellow"></i>',
        'm_name' => 'AND',
        'm_desc' => 'AND Intents are completed when ALL their children are complete',
        'm_parents' => array(4527,10602),
    ),
    6193 => array(
        'm_icon' => '<i class="far fa-code-merge yellow"></i>',
        'm_name' => 'OR',
        'm_desc' => 'OR Intents are completed when ANY of their children are complete',
        'm_parents' => array(10602,4527),
    ),
);

//Link Word Weight:
$config['en_ids_10592'] = array(10596,10593,10539,10594);
$config['en_all_10592'] = array(
    10596 => array(
        'm_icon' => '<i class="fas fa-square-root ispink"></i>',
        'm_name' => 'Nod',
        'm_desc' => 'A fraction of a word',
        'm_parents' => array(4527,10592),
    ),
    10593 => array(
        'm_icon' => '<i class="fas fa-union ispink"></i>',
        'm_name' => 'Statement + Connections',
        'm_desc' => 'Multiple words based on content plus connections',
        'm_parents' => array(4527,10592),
    ),
    10539 => array(
        'm_icon' => '<i class="far fa-file-word ispink"></i>',
        'm_name' => 'Word',
        'm_desc' => 'A single word only',
        'm_parents' => array(4527,10592,5008),
    ),
    10594 => array(
        'm_icon' => '<i class="fas fa-value-absolute ispink"></i>',
        'm_name' => 'Word + Connections',
        'm_desc' => 'A single word plus connections',
        'm_parents' => array(4527,10592),
    ),
);

//Link Directions:
$config['en_ids_10591'] = array(10589,10590);
$config['en_all_10591'] = array(
    10589 => array(
        'm_icon' => '<i class="far fa-download ispink"></i>',
        'm_name' => 'Words In',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
    10590 => array(
        'm_icon' => '<i class="far fa-upload ispink"></i>',
        'm_name' => 'Words Out',
        'm_desc' => '',
        'm_parents' => array(4527,10591),
    ),
);

//Public Entities:
$config['en_ids_10571'] = array(2997,4446,3005,4763,3147,2999,4883,3192,5948,2998,4433,3084,7512,1308,1281,3000);
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
    4433 => array(
        'm_icon' => '<i class="far fa-user-ninja"></i>',
        'm_name' => 'Mench Coders',
        'm_desc' => '',
        'm_parents' => array(10571,6827,4463,4426),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut"></i>',
        'm_name' => 'Mench Experts',
        'm_desc' => '',
        'm_parents' => array(10571,4983,6827,4463),
    ),
    7512 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isorange"></i>',
        'm_name' => 'Mench Trainers Level 1',
        'm_desc' => '',
        'm_parents' => array(10606,10691,10573,10571,4985),
    ),
    1308 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isturquoise"></i>',
        'm_name' => 'Mench Trainers Level 2',
        'm_desc' => '',
        'm_parents' => array(10626,10691,10571,4463,4426),
    ),
    1281 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isnavy"></i>',
        'm_name' => 'Mench Trainers Level 3',
        'm_desc' => '',
        'm_parents' => array(10618,10691,10571,4463),
    ),
    3000 => array(
        'm_icon' => '<i class="far fa-whistle"></i>',
        'm_name' => 'Trained Expert Sources',
        'm_desc' => '',
        'm_parents' => array(10571,7303,3463,4506,4527,4463),
    ),
);

//User Selectable Completion:
$config['en_ids_10570'] = array(6154,6155);
$config['en_all_10570'] = array(
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'Intent Accomplished',
        'm_desc' => 'You successfully accomplished your intention so you no longer want to receive future updates',
        'm_parents' => array(10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle ispink"></i>',
        'm_name' => 'Intent Cancelled',
        'm_desc' => 'You did NOT accomplish the intention and you want to stop all future updates on this intention',
        'm_parents' => array(10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
);

//Intent Scopes Searchable:
$config['en_ids_10568'] = array(7598);
$config['en_all_10568'] = array(
    7598 => array(
        'm_icon' => '<i class="fas fa-tree-large isgreen"></i>',
        'm_name' => 'Intent Tree',
        'm_desc' => '',
        'm_parents' => array(10568,7767,7582,7596),
    ),
);

//Intent Scopes Supports Equal:
$config['en_ids_10567'] = array(7597);
$config['en_all_10567'] = array(
    7597 => array(
        'm_icon' => '<i class="far fa-leaf isgreen"></i>',
        'm_name' => 'Intent Leaf',
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
$config['en_ids_7774'] = array(6180,6181,6183,6184,6175,6176);
$config['en_all_7774'] = array(
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin blue"></i>',
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
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin yellow"></i>',
        'm_name' => 'Intent Drafting',
        'm_desc' => '',
        'm_parents' => array(7774,7356,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-globe yellow"></i>',
        'm_name' => 'Intent Published',
        'm_desc' => '',
        'm_parents' => array(7774,7355,7356,4737),
    ),
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin ispink"></i>',
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

//Intent Scopes Mineable:
$config['en_ids_7767'] = array(7598,7766);
$config['en_all_7767'] = array(
    7598 => array(
        'm_icon' => '<i class="fas fa-tree-large isgreen"></i>',
        'm_name' => 'Tree',
        'm_desc' => '',
        'm_parents' => array(10568,7767,7582,7596),
    ),
    7766 => array(
        'm_icon' => '<i class="fas fa-code-branch rotate74 isgreen"></i>',
        'm_name' => 'Branch',
        'm_desc' => '',
        'm_parents' => array(7767,7596),
    ),
);

//Intent Note Subscriber:
$config['en_ids_7701'] = array(4430);
$config['en_all_7701'] = array(
    4430 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Mench Users',
        'm_desc' => '',
        'm_parents' => array(4983,7701,7369,6827,4426,4463),
    ),
);

//Intent Note Up-Vote:
$config['en_ids_4983'] = array(2997,4446,3005,4763,3147,2999,4883,3192,5948,2998,3084,4430);
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
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut"></i>',
        'm_name' => 'Mench Experts',
        'm_desc' => '',
        'm_parents' => array(10571,4983,6827,4463),
    ),
    4430 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Mench Users',
        'm_desc' => '',
        'm_parents' => array(4983,7701,7369,6827,4426,4463),
    ),
);

//Action Plan Intention Successful:
$config['en_ids_7758'] = array(6154);
$config['en_all_7758'] = array(
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'User Intent Accomplished',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
);

//Auto Completable:
$config['en_ids_7756'] = array(6677,6914,6907);
$config['en_all_7756'] = array(
    6677 => array(
        'm_icon' => '<i class="far fa-comments yellow"></i>',
        'm_name' => 'Intent Read-Only',
        'm_desc' => '',
        'm_parents' => array(7756,7585,4559,6192),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes yellow"></i>',
        'm_name' => 'Intent Require All',
        'm_desc' => '',
        'm_parents' => array(6192,7756,7585,7486,7485,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube yellow"></i>',
        'm_name' => 'Intent Require Any',
        'm_desc' => '',
        'm_parents' => array(7756,7585,7486,7485,7309,6997,6193),
    ),
);

//Intent Upload File:
$config['en_ids_7751'] = array(6680,6681,6678,7637,6679);
$config['en_all_7751'] = array(
    6680 => array(
        'm_icon' => '<i class="far fa-microphone yellow"></i>',
        'm_name' => 'Intent Upload Audio',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6681 => array(
        'm_icon' => '<i class="far fa-file-pdf yellow"></i>',
        'm_name' => 'Intent Upload File',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6678 => array(
        'm_icon' => '<i class="far fa-image yellow"></i>',
        'm_name' => 'Intent Upload Image',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="fas fa-film yellow"></i>',
        'm_name' => 'Intent Upload Multimedia',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6679 => array(
        'm_icon' => '<i class="far fa-video yellow"></i>',
        'm_name' => 'Intent Upload Video',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
);

//User Step Create New Content:
$config['en_ids_6144'] = array(6683,6682,6680,6681,6678,7637,6679);
$config['en_all_6144'] = array(
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard yellow"></i>',
        'm_name' => 'Send Text Message',
        'm_desc' => '',
        'm_parents' => array(10687,7585,6144,6192),
    ),
    6682 => array(
        'm_icon' => '<i class="far fa-external-link yellow"></i>',
        'm_name' => 'Send URL',
        'm_desc' => '',
        'm_parents' => array(10687,7585,6144,6192),
    ),
    6680 => array(
        'm_icon' => '<i class="far fa-microphone yellow"></i>',
        'm_name' => 'Upload Audio',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6681 => array(
        'm_icon' => '<i class="far fa-file-pdf yellow"></i>',
        'm_name' => 'Upload File',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6678 => array(
        'm_icon' => '<i class="far fa-image yellow"></i>',
        'm_name' => 'Upload Image',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="fas fa-film yellow"></i>',
        'm_name' => 'Upload Multimedia',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6679 => array(
        'm_icon' => '<i class="far fa-video yellow"></i>',
        'm_name' => 'Upload Video',
        'm_desc' => '',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
);

//Link Metadata:
$config['en_ids_6103'] = array(4358,6402,6203);
$config['en_all_6103'] = array(
    4358 => array(
        'm_icon' => '<i class="far fa-file-certificate ispink"></i>',
        'm_name' => 'Completion Marks',
        'm_desc' => '',
        'm_parents' => array(10663,6103,6410,6232),
    ),
    6402 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Condition Score Range',
        'm_desc' => '',
        'm_parents' => array(10664,6103,6410),
    ),
    6203 => array(
        'm_icon' => '<i class="far fa-lambda ispink"></i>',
        'm_name' => 'Facebook Attachment ID',
        'm_desc' => 'For media files such as videos, audios, images and other files, we cache them with the Facebook Server so we can instantly deliver them to students. This variables in the link metadata is where we store the attachment ID. See the children to better understand which links types support this caching feature.',
        'm_parents' => array(6232,6215,2793,6103),
    ),
);

//Link Table:
$config['en_ids_4341'] = array(4429,4369,4372,4364,7694,4367,6103,4370,4366,4368,4371,6186,4362,4593,10588);
$config['en_all_4341'] = array(
    4429 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Child Entity',
        'm_desc' => '',
        'm_parents' => array(10692,6160,6232,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Child Intent',
        'm_desc' => '',
        'm_parents' => array(10692,6202,6232,4341),
    ),
    4372 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Content',
        'm_desc' => '',
        'm_parents' => array(7578,10679,10657,5001,6232,4341),
    ),
    4364 => array(
        'm_icon' => '<i class="far fa-user-edit ispink"></i>',
        'm_name' => 'Creator',
        'm_desc' => '',
        'm_parents' => array(6160,6232,6194,4341),
    ),
    7694 => array(
        'm_icon' => '<i class="fas fa-project-diagram ispink"></i>',
        'm_name' => 'External ID',
        'm_desc' => '',
        'm_parents' => array(6215,6232,4341),
    ),
    4367 => array(
        'm_icon' => '<i class="fas fa-link rotate90 ispink"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,4341),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda ispink"></i>',
        'm_name' => 'Metadata',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Order',
        'm_desc' => 'tr_order empowers the arrangement or disposition of intents, entities or transactions in relation to each other according to a particular sequence, pattern, or method defined by Miners or Masters.',
        'm_parents' => array(10676,10675,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Parent Entity',
        'm_desc' => '',
        'm_parents' => array(10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Parent Intent',
        'm_desc' => '',
        'm_parents' => array(10692,6202,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link ispink"></i>',
        'm_name' => 'Parent',
        'm_desc' => '',
        'm_parents' => array(10692,4367,6232,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(10677,10661,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Timestamp',
        'm_desc' => '',
        'm_parents' => array(6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug ispink ispink"></i>',
        'm_name' => 'Type',
        'm_desc' => '',
        'm_parents' => array(10607,10659,6160,6232,6194,4527,4341),
    ),
    10588 => array(
        'm_icon' => '<i class="fas fa-file-word ispink ispink"></i>',
        'm_name' => 'Words',
        'm_desc' => '',
        'm_parents' => array(6214,4506,4341),
    ),
);

//Entity Table:
$config['en_ids_6206'] = array(6198,6160,6172,6197,6177,6199);
$config['en_all_6206'] = array(
    6198 => array(
        'm_icon' => '<i class="far fa-user-circle blue"></i>',
        'm_name' => 'Icon',
        'm_desc' => '',
        'm_parents' => array(10653,5943,10625,6232,6206),
    ),
    6160 => array(
        'm_icon' => '<i class="fas fa-at blue"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="far fa-lambda blue"></i>',
        'm_name' => 'Metadata',
        'm_desc' => '',
        'm_parents' => array(6232,3323,6206,6195),
    ),
    6197 => array(
        'm_icon' => '<i class="far fa-fingerprint blue"></i>',
        'm_name' => 'Name',
        'm_desc' => '',
        'm_parents' => array(10646,5000,4998,4999,6232,6225,6206),
    ),
    6177 => array(
        'm_icon' => '<i class="far fa-sliders-h blue"></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(6204,5003,10654,6160,6232,7303,6194,6206,4527),
    ),
    6199 => array(
        'm_icon' => '<i class="far fa-shield-check blue"></i>',
        'm_name' => 'Trust Score',
        'm_desc' => '',
        'm_parents' => array(6232,4463,6214,6206),
    ),
);

//Intent Table:
$config['en_ids_6201'] = array(4356,6202,6159,4736,7596,4737,7585,5008);
$config['en_all_6201'] = array(
    4356 => array(
        'm_icon' => '<i class="far fa-clock yellow"></i>',
        'm_name' => 'Completion Time',
        'm_desc' => '',
        'm_parents' => array(10650,6232,6201),
    ),
    6202 => array(
        'm_icon' => '<i class="fas fa-hashtag yellow"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6201),
    ),
    6159 => array(
        'm_icon' => '<i class="far fa-lambda yellow"></i>',
        'm_name' => 'Metadata',
        'm_desc' => 'Intent metadata contains variables that have been automatically calculated and automatically updates using a cron job. Intent Metadata are the backbone of key functions and user interfaces like the intent landing page or Action Plan completion workflows.',
        'm_parents' => array(6232,6201,6195),
    ),
    4736 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow yellow"></i>',
        'm_name' => 'Outcome',
        'm_desc' => '',
        'm_parents' => array(10644,6232,6201),
    ),
    7596 => array(
        'm_icon' => '<i class="fas fa-mountains isgreen"></i>',
        'm_name' => 'Scope',
        'm_desc' => '',
        'm_parents' => array(10610,6204,10649,7302,6160,6201,6194,6232,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow"></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(6204,10648,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="far fa-shapes yellow"></i>',
        'm_name' => 'Subtype',
        'm_desc' => '',
        'm_parents' => array(10608,6204,10651,6160,6194,6232,4527,6201),
    ),
    5008 => array(
        'm_icon' => '<i class="far fa-tools yellow"></i>',
        'm_name' => 'Verb',
        'm_desc' => '',
        'm_parents' => array(6204,10647,4736,7777,6160,6232,7302,4506,6194,6201),
    ),
);

//Single Selectable:
$config['en_ids_6204'] = array(6177,3290,7596,4737,7585,10602,5008,10591,6186,10592,4454,3289);
$config['en_all_6204'] = array(
    6177 => array(
        'm_icon' => '<i class="far fa-sliders-h blue"></i>',
        'm_name' => 'Entity Status',
        'm_desc' => '',
        'm_parents' => array(6204,5003,10654,6160,6232,7303,6194,6206,4527),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender blue"></i>',
        'm_name' => 'Genders',
        'm_desc' => '',
        'm_parents' => array(6225,6204),
    ),
    7596 => array(
        'm_icon' => '<i class="fas fa-mountains isgreen"></i>',
        'm_name' => 'Intent Scope',
        'm_desc' => '',
        'm_parents' => array(10610,6204,10649,7302,6160,6201,6194,6232,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow"></i>',
        'm_name' => 'Intent Status',
        'm_desc' => '',
        'm_parents' => array(6204,10648,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="far fa-shapes yellow"></i>',
        'm_name' => 'Intent Subtype',
        'm_desc' => '',
        'm_parents' => array(10608,6204,10651,6160,6194,6232,4527,6201),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-shapes yellow"></i>',
        'm_name' => 'Intent Types',
        'm_desc' => '',
        'm_parents' => array(6204,7302,4527,6768),
    ),
    5008 => array(
        'm_icon' => '<i class="far fa-tools yellow"></i>',
        'm_name' => 'Intent Verb',
        'm_desc' => '',
        'm_parents' => array(6204,10647,4736,7777,6160,6232,7302,4506,6194,6201),
    ),
    10591 => array(
        'm_icon' => '<i class="fas fa-directions ispink"></i>',
        'm_name' => 'Link Directions',
        'm_desc' => '',
        'm_parents' => array(6204,7797,7304,6771,4527,10588),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Link Status',
        'm_desc' => '',
        'm_parents' => array(10677,10661,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    10592 => array(
        'm_icon' => '<i class="fas fa-weight ispink"></i>',
        'm_name' => 'Link Word Weight',
        'm_desc' => '',
        'm_parents' => array(6204,6771,4527,10588),
    ),
    4454 => array(
        'm_icon' => '<i class="far fa-bells blue"></i>',
        'm_name' => 'Subscription Settings',
        'm_desc' => '',
        'm_parents' => array(7552,6225,6204,4527),
    ),
    3289 => array(
        'm_icon' => '<i class="far fa-map blue"></i>',
        'm_name' => 'Timezones',
        'm_desc' => '',
        'm_parents' => array(6204,6225),
    ),
);

//Intent Answer Types:
$config['en_ids_7712'] = array(7231,6684,6685);
$config['en_all_7712'] = array(
    7231 => array(
        'm_icon' => '<i class="far fa-check-double yellow"></i>',
        'm_name' => 'Intent Multi-Choice',
        'm_desc' => '',
        'm_parents' => array(7712,7489,7588,7585,6193),
    ),
    6684 => array(
        'm_icon' => '<i class="far fa-check yellow"></i>',
        'm_name' => 'Intent Single-Choice',
        'm_desc' => '',
        'm_parents' => array(7712,7588,7585,6157,6193),
    ),
    6685 => array(
        'm_icon' => '<i class="far fa-stopwatch yellow"></i>',
        'm_name' => 'Intent Single-Choice Timed',
        'm_desc' => '',
        'm_parents' => array(7712,7488,7487,7585,7366,6193),
    ),
);

//User Step Answered Successfully:
$config['en_ids_7704'] = array(7489,6157,7487);
$config['en_all_7704'] = array(
    7489 => array(
        'm_icon' => '<i class="far fa-check-double ispink"></i>',
        'm_name' => 'User Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="far fa-stopwatch ispink"></i>',
        'm_name' => 'User Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
);

//Link Intent Subscription Types:
$config['en_ids_7703'] = array(10671,10650,10644,10649,10648,10651,4229,10663,10664,10661,10662,4228,10686,10660,6093,6242,7545,4601,4231,7701,10573,4983,10675,6154,6155,7485,7486,6144,4559,7489,6997,6157,7487);
$config['en_all_7703'] = array(
    10671 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Iterated Archived',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,7703,10638),
    ),
    10650 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Intent Iterated Completion Time',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Intent Iterated Outcome',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10593,4593,10638),
    ),
    10649 => array(
        'm_icon' => '<i class="fas fa-mountains ispink"></i>',
        'm_name' => 'Intent Iterated Scope',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10648 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Iterated Status',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="far fa-shapes ispink"></i>',
        'm_name' => 'Intent Iterated Subtype',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Intent Link Conditional',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-file-certificate ispink"></i>',
        'm_name' => 'Intent Link Iterated Marks',
        'm_desc' => '',
        'm_parents' => array(4228,7703,10539,10589,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Intent Link Iterated Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    10661 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Link Iterated Status',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Intent Link Iterated Type',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    4228 => array(
        'm_icon' => '<i class="fas fa-walking ispink"></i>',
        'm_name' => 'Intent Link Required',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,6410,4593,4486),
    ),
    10686 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Link Unlinked',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10539,4593,10658,10638),
    ),
    10660 => array(
        'm_icon' => '<i class="fas fa-angle-double-right ispink"></i>',
        'm_name' => 'Intent Migrate Parent Link',
        'm_desc' => '',
        'm_parents' => array(7703,4593,10638,10589,10594,10658),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Intent Note Changelog',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-lightbulb-on ispink"></i>',
        'm_name' => 'Intent Note Endnote',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4603,4593,4986,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="far fa-tag ispink"></i>',
        'm_name' => 'Intent Note Entity Tag',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,7551,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-search ispink"></i>',
        'm_name' => 'Intent Note Keyword',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-rss ispink"></i>',
        'm_name' => 'Intent Note Subscriber',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4527,7703,4593,7551,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="far fa-user-hard-hat ispink"></i>',
        'm_name' => 'Intent Note Trainer',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered by Trainer',
        'm_desc' => '',
        'm_parents' => array(7703,10539,10589,4593,10658,10638),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'User Intent Accomplished',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle ispink"></i>',
        'm_name' => 'User Intent Cancelled',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'User Step Create New Content',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4527,6768,7703,6255,6244,4755,6146,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comments ispink"></i>',
        'm_name' => 'User Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="far fa-check-double ispink"></i>',
        'm_name' => 'User Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="far fa-stopwatch ispink"></i>',
        'm_name' => 'User Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
);

//Intent Scope:
$config['en_ids_7596'] = array(7597,7766,7598);
$config['en_all_7596'] = array(
    7597 => array(
        'm_icon' => '<i class="far fa-leaf isgreen"></i>',
        'm_name' => 'Leaf',
        'm_desc' => 'Not searchable and only accessible through its parents',
        'm_parents' => array(10567,7596),
    ),
    7766 => array(
        'm_icon' => '<i class="fas fa-code-branch rotate74 isgreen"></i>',
        'm_name' => 'Branch',
        'm_desc' => 'Users can find intent by searching it or arriving at it from the parent',
        'm_parents' => array(7767,7596),
    ),
    7598 => array(
        'm_icon' => '<i class="fas fa-tree-large isgreen"></i>',
        'm_name' => 'Tree',
        'm_desc' => 'Intent is searchable and requires the user to register in order to continue',
        'm_parents' => array(10568,7767,7582,7596),
    ),
);

//Intent Link Conditional:
$config['en_ids_4229'] = array(10664,6140,6997);
$config['en_all_4229'] = array(
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Intent Link Iterated Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Link Unlock',
        'm_desc' => 'A step that has become available because of the score generated from student answers',
        'm_parents' => array(10539,10589,6410,4229,4755,4593),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
);

//Intent Select Publicly:
$config['en_ids_7588'] = array(7231,6684);
$config['en_all_7588'] = array(
    7231 => array(
        'm_icon' => '<i class="far fa-check-double yellow"></i>',
        'm_name' => 'Intent Multi-Choice',
        'm_desc' => '',
        'm_parents' => array(7712,7489,7588,7585,6193),
    ),
    6684 => array(
        'm_icon' => '<i class="far fa-check yellow"></i>',
        'm_name' => 'Intent Single-Choice',
        'm_desc' => '',
        'm_parents' => array(7712,7588,7585,6157,6193),
    ),
);

//Intent OR:
$config['en_ids_6193'] = array(6684,6685,7231,6907);
$config['en_all_6193'] = array(
    6684 => array(
        'm_icon' => '<i class="far fa-check yellow"></i>',
        'm_name' => 'Single-Choice',
        'm_desc' => '',
        'm_parents' => array(7712,7588,7585,6157,6193),
    ),
    6685 => array(
        'm_icon' => '<i class="far fa-stopwatch yellow"></i>',
        'm_name' => 'Single-Choice Timed',
        'm_desc' => '',
        'm_parents' => array(7712,7488,7487,7585,7366,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="far fa-check-double yellow"></i>',
        'm_name' => 'Multi-Choice',
        'm_desc' => '',
        'm_parents' => array(7712,7489,7588,7585,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube yellow"></i>',
        'm_name' => 'Require Any',
        'm_desc' => '',
        'm_parents' => array(7756,7585,7486,7485,7309,6997,6193),
    ),
);

//Intent Subtype:
$config['en_ids_7585'] = array(6677,6683,6682,6680,6678,6679,7637,6681,6684,6685,7231,6907,6914);
$config['en_all_7585'] = array(
    6677 => array(
        'm_icon' => '<i class="far fa-comments yellow"></i>',
        'm_name' => 'Read-Only',
        'm_desc' => 'User will complete by reading intent messages only. No inputs required.',
        'm_parents' => array(7756,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard yellow"></i>',
        'm_name' => 'Send Text Message',
        'm_desc' => 'User will complete by sending a text message',
        'm_parents' => array(10687,7585,6144,6192),
    ),
    6682 => array(
        'm_icon' => '<i class="far fa-external-link yellow"></i>',
        'm_name' => 'Send URL',
        'm_desc' => 'User will complete by sending a URL message',
        'm_parents' => array(10687,7585,6144,6192),
    ),
    6680 => array(
        'm_icon' => '<i class="far fa-microphone yellow"></i>',
        'm_name' => 'Upload Audio',
        'm_desc' => 'User will complete by sending an audio message',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6678 => array(
        'm_icon' => '<i class="far fa-image yellow"></i>',
        'm_name' => 'Upload Image',
        'm_desc' => 'User will complete by sending an image message',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6679 => array(
        'm_icon' => '<i class="far fa-video yellow"></i>',
        'm_name' => 'Upload Video',
        'm_desc' => 'User will complete by sending a video message',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="fas fa-film yellow"></i>',
        'm_name' => 'Upload Multimedia',
        'm_desc' => 'User completes by uploading a video, audio or image file',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6681 => array(
        'm_icon' => '<i class="far fa-file-pdf yellow"></i>',
        'm_name' => 'Upload File',
        'm_desc' => 'User will complete by sending a file (PDF, DOC, etc...) message',
        'm_parents' => array(10687,7751,7585,6144,6192),
    ),
    6684 => array(
        'm_icon' => '<i class="far fa-check yellow"></i>',
        'm_name' => 'Single-Choice',
        'm_desc' => 'User will complete by choosing a child intent as their answer',
        'm_parents' => array(7712,7588,7585,6157,6193),
    ),
    6685 => array(
        'm_icon' => '<i class="far fa-stopwatch yellow"></i>',
        'm_name' => 'Single-Choice Timed',
        'm_desc' => 'User will complete by choosing a child intent as their answer within a time limit',
        'm_parents' => array(7712,7488,7487,7585,7366,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="far fa-check-double yellow"></i>',
        'm_name' => 'Multi-Choice',
        'm_desc' => 'User will complete by choosing one or more child intents as their answer',
        'm_parents' => array(7712,7489,7588,7585,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube yellow"></i>',
        'm_name' => 'Require Any',
        'm_desc' => 'User will complete by (a) choosing intent as their answer or by (b) completing any child intent',
        'm_parents' => array(7756,7585,7486,7485,7309,6997,6193),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes yellow"></i>',
        'm_name' => 'Require All',
        'm_desc' => 'User will complete by (a) choosing intent as their answer or by (b) completing all child intents',
        'm_parents' => array(6192,7756,7585,7486,7485,7309,6997),
    ),
);

//Intent Scopes Get Started:
$config['en_ids_7582'] = array(7598);
$config['en_all_7582'] = array(
    7598 => array(
        'm_icon' => '<i class="fas fa-tree-large isgreen"></i>',
        'm_name' => 'Intent Tree',
        'm_desc' => '',
        'm_parents' => array(10568,7767,7582,7596),
    ),
);

//User Link CC Email:
$config['en_ids_5967'] = array(4246,7504,6415,4235);
$config['en_all_5967'] = array(
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
    6415 => array(
        'm_icon' => '<i class="far fa-function ispink"></i>',
        'm_name' => 'User Cleared Action Plan',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(10596,10589,5967,4755,6418,4593,6414),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Set',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
);

//Mench Platform Users:
$config['en_ids_7555'] = array(6196,3288);
$config['en_all_7555'] = array(
    6196 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Messenger',
        'm_desc' => 'Establish a consistent connection with Mench on Facebook Messenger to seamlessly get everything done in one place. (RECOMMENDED)',
        'm_parents' => array(5969,7555,3320),
    ),
    3288 => array(
        'm_icon' => '<i class="far fa-envelope-open blue"></i>',
        'm_name' => 'Email',
        'm_desc' => 'Connect with Mench on Google Chrome but get your notifications via Email.',
        'm_parents' => array(7555,6225,4426,4755),
    ),
);

//Intent Notes Entity Referencing Required:
$config['en_ids_7551'] = array(7545,7701,10573,4983);
$config['en_all_7551'] = array(
    7545 => array(
        'm_icon' => '<i class="far fa-tag ispink"></i>',
        'm_name' => 'Intent Note Entity Tag',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,7551,4593,4485),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-rss ispink"></i>',
        'm_name' => 'Intent Note Subscriber',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4527,7703,4593,7551,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="far fa-user-hard-hat ispink"></i>',
        'm_name' => 'Intent Note Trainer',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
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

//User Steps Unlock:
$config['en_ids_7494'] = array(7485,7486,6997);
$config['en_all_7494'] = array(
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
);

//Intent Type Requirement:
$config['en_ids_7309'] = array(6914,6907);
$config['en_all_7309'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes yellow"></i>',
        'm_name' => 'Intent Require All',
        'm_desc' => '',
        'm_parents' => array(6192,7756,7585,7486,7485,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube yellow"></i>',
        'm_name' => 'Intent Require Any',
        'm_desc' => '',
        'm_parents' => array(7756,7585,7486,7485,7309,6997,6193),
    ),
);

//Mench Products:
$config['en_ids_7372'] = array(7735,6403,7555,7540,7305,7369,7368);
$config['en_all_7372'] = array(
    7735 => array(
        'm_icon' => '<i class="far fa-database"></i>',
        'm_name' => 'Mench Database Tables',
        'm_desc' => '',
        'm_parents' => array(7372),
    ),
    6403 => array(
        'm_icon' => '<i class="fas fa-code"></i>',
        'm_name' => 'Mench PHP Repository',
        'm_desc' => 'So far all our products are built using the same PHP application',
        'm_parents' => array(7372,3324,7391,7390,4523,3325,3323,3326),
    ),
    7555 => array(
        'm_icon' => '<i class="fas fa-comments"></i>',
        'm_name' => 'Mench Platform Users',
        'm_desc' => '',
        'm_parents' => array(7303,7372,4527),
    ),
    7540 => array(
        'm_icon' => '<i class="fas fa-balance-scale"></i>',
        'm_name' => 'Mench Terms Of Service',
        'm_desc' => '#8272',
        'm_parents' => array(7372,7305),
    ),
    7305 => array(
        'm_icon' => '<img src="https://mench.com/img/bp_128.png">',
        'm_name' => 'Mench Website',
        'm_desc' => '',
        'm_parents' => array(7372,1326),
    ),
    7369 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Mench User App',
        'm_desc' => 'A web portal for software engineers to assess/improve their skills & get matched with top companies.',
        'm_parents' => array(7372,4527),
    ),
    7368 => array(
        'm_icon' => '<i class="far fa-user-hard-hat"></i>',
        'm_name' => 'Mench Trainer App',
        'm_desc' => 'A web portal for industry researchers to mine expert intelligence as Mench intents/entities.',
        'm_parents' => array(7372,4527),
    ),
);

//Mench Trainer Tools:
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

//Mench User App:
$config['en_ids_7369'] = array(6138,7765,7161,7291,7256,4269,10563,4430,6137);
$config['en_all_7369'] = array(
    6138 => array(
        'm_icon' => '🚩',
        'm_name' => 'Action Plan',
        'm_desc' => 'A web-based portal (also accessible via Messenger) enabling students to manage their intentions',
        'm_parents' => array(7369,4463),
    ),
    7765 => array(
        'm_icon' => '<i class="fas fa-globe yellow"></i>',
        'm_name' => 'Intent Landing Page',
        'm_desc' => '',
        'm_parents' => array(4535,7369),
    ),
    7161 => array(
        'm_icon' => '<i class="far fa-tachometer-alt-fast"></i>',
        'm_name' => 'Dashboard',
        'm_desc' => '',
        'm_parents' => array(7369,7368,7305),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off"></i>',
        'm_name' => 'Logout',
        'm_desc' => '',
        'm_parents' => array(7368,7369),
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
    10563 => array(
        'm_icon' => '<i class="far fa-sitemap"></i>',
        'm_name' => 'Sitemap',
        'm_desc' => 'A list of all published intent trees that users can get started at.',
        'm_parents' => array(7369),
    ),
    4430 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Users',
        'm_desc' => '',
        'm_parents' => array(4983,7701,7369,6827,4426,4463),
    ),
    6137 => array(
        'm_icon' => '👤',
        'm_name' => 'My Account',
        'm_desc' => 'A web-based portal (also accessible via Messenger) enabling students to manage their account',
        'm_parents' => array(7369),
    ),
);

//Mench Trainer App:
$config['en_ids_7368'] = array(4536,4535,6205,7161,7291,7256,10691,6287,5007);
$config['en_all_7368'] = array(
    4536 => array(
        'm_icon' => '<i class="fas fa-at blue"></i>',
        'm_name' => 'Entities',
        'm_desc' => '',
        'm_parents' => array(10605,7368,4534,4463),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-hashtag yellow"></i>',
        'm_name' => 'Intents',
        'm_desc' => '',
        'm_parents' => array(10608,7368,4534,4463),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-link ispink"></i>',
        'm_name' => 'Links',
        'm_desc' => '',
        'm_parents' => array(10607,7368,4534,4463),
    ),
    7161 => array(
        'm_icon' => '<i class="far fa-tachometer-alt-fast"></i>',
        'm_name' => 'Mench Dashboard',
        'm_desc' => '',
        'm_parents' => array(7369,7368,7305),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off"></i>',
        'm_name' => 'Mench Logout',
        'm_desc' => '',
        'm_parents' => array(7368,7369),
    ),
    7256 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Mench Search',
        'm_desc' => 'Intents, Entities & URLs',
        'm_parents' => array(7369,7368,3323),
    ),
    10691 => array(
        'm_icon' => '<i class="far fa-user-hard-hat"></i>',
        'm_name' => 'Mench Trainers',
        'm_desc' => '',
        'm_parents' => array(4527,7368,6827),
    ),
    6287 => array(
        'm_icon' => '<i class="far fa-tools"></i>',
        'm_name' => 'Mench Trainer Tools',
        'm_desc' => 'Tools for moderating the Mench platform',
        'm_parents' => array(7368,4527,7284),
    ),
    5007 => array(
        'm_icon' => '<i class="fas fa-expand-arrows ispink"></i>',
        'm_name' => 'Trainer Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7368,4757,4593),
    ),
);

//Private Intent Types:
$config['en_ids_7366'] = array(6685);
$config['en_all_7366'] = array(
    6685 => array(
        'm_icon' => '<i class="far fa-stopwatch yellow"></i>',
        'm_name' => 'Intent Single-Choice Timed',
        'm_desc' => '',
        'm_parents' => array(7712,7488,7487,7585,7366,6193),
    ),
);

//Link Statuses Incomplete:
$config['en_ids_7364'] = array(6175);
$config['en_all_7364'] = array(
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin ispink"></i>',
        'm_name' => 'Link Drafting',
        'm_desc' => '',
        'm_parents' => array(7774,7364,7360,6186),
    ),
);

//Link Statuses Active:
$config['en_ids_7360'] = array(6175,6176);
$config['en_all_7360'] = array(
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin ispink"></i>',
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

//Link Statuses Public:
$config['en_ids_7359'] = array(6176);
$config['en_all_7359'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-globe ispink"></i>',
        'm_name' => 'Link Published',
        'm_desc' => '',
        'm_parents' => array(7774,7360,7359,6186),
    ),
);

//Entity Statuses Active:
$config['en_ids_7358'] = array(6180,6181);
$config['en_all_7358'] = array(
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin blue"></i>',
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

//Entity Statuses Public:
$config['en_ids_7357'] = array(6181);
$config['en_all_7357'] = array(
    6181 => array(
        'm_icon' => '<i class="fas fa-globe blue"></i>',
        'm_name' => 'Entity Published',
        'm_desc' => '',
        'm_parents' => array(7774,7358,7357,6177),
    ),
);

//Intent Statuses Active:
$config['en_ids_7356'] = array(6183,6184);
$config['en_all_7356'] = array(
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin yellow"></i>',
        'm_name' => 'Intent Drafting',
        'm_desc' => '',
        'm_parents' => array(7774,7356,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-globe yellow"></i>',
        'm_name' => 'Intent Published',
        'm_desc' => '',
        'm_parents' => array(7774,7355,7356,4737),
    ),
);

//Intent Statuses Public:
$config['en_ids_7355'] = array(6184);
$config['en_all_7355'] = array(
    6184 => array(
        'm_icon' => '<i class="fas fa-globe yellow"></i>',
        'm_name' => 'Intent Published',
        'm_desc' => '',
        'm_parents' => array(7774,7355,7356,4737),
    ),
);

//Intent Dashboard:
$config['en_ids_7302'] = array(7596,4737,10602,5008);
$config['en_all_7302'] = array(
    7596 => array(
        'm_icon' => '<i class="fas fa-mountains isgreen"></i>',
        'm_name' => 'Scope',
        'm_desc' => 'Defines who and how can access intent. Note that all intents are accessible to all users, it\'s just the level of visibility/engagement that is different.',
        'm_parents' => array(10610,6204,10649,7302,6160,6201,6194,6232,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow"></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(6204,10648,6226,6160,6232,7302,6194,6201,4527),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-shapes yellow"></i>',
        'm_name' => 'Types',
        'm_desc' => '',
        'm_parents' => array(6204,7302,4527,6768),
    ),
    5008 => array(
        'm_icon' => '<i class="far fa-tools yellow"></i>',
        'm_name' => 'Verb',
        'm_desc' => '',
        'm_parents' => array(6204,10647,4736,7777,6160,6232,7302,4506,6194,6201),
    ),
);

//Entity Dashboard:
$config['en_ids_7303'] = array(6827,6177,7555,3000);
$config['en_all_7303'] = array(
    6827 => array(
        'm_icon' => '<i class="far fa-users-crown"></i>',
        'm_name' => 'Community Members',
        'm_desc' => '',
        'm_parents' => array(3303,3314,2738,7303,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="far fa-sliders-h blue"></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(6204,5003,10654,6160,6232,7303,6194,6206,4527),
    ),
    7555 => array(
        'm_icon' => '<i class="fas fa-comments"></i>',
        'm_name' => 'Mench Platform Users',
        'm_desc' => '',
        'm_parents' => array(7303,7372,4527),
    ),
    3000 => array(
        'm_icon' => '<i class="far fa-whistle"></i>',
        'm_name' => 'Trained Expert Sources',
        'm_desc' => '',
        'm_parents' => array(10571,7303,3463,4506,4527,4463),
    ),
);

//Link Dashboard:
$config['en_ids_7304'] = array(7797,10591,6186);
$config['en_all_7304'] = array(
    7797 => array(
        'm_icon' => '<i class="fas fa-trophy"></i>',
        'm_name' => 'Leaderboard',
        'm_desc' => '',
        'm_parents' => array(7304,7254),
    ),
    10591 => array(
        'm_icon' => '<i class="fas fa-directions ispink"></i>',
        'm_name' => 'Directions',
        'm_desc' => '',
        'm_parents' => array(6204,7797,7304,6771,4527,10588),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Status',
        'm_desc' => '',
        'm_parents' => array(10677,10661,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
);

//Weekly Leaderboard Message:
$config['en_ids_7203'] = array();
$config['en_all_7203'] = array(
);

//Link Status:
$config['en_ids_6186'] = array(6176,6175,6173);
$config['en_all_6186'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-globe ispink"></i>',
        'm_name' => 'Published',
        'm_desc' => 'live and ready to be shared with users',
        'm_parents' => array(7774,7360,7359,6186),
    ),
    6175 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin ispink"></i>',
        'm_name' => 'Drafting',
        'm_desc' => 'being mined, to be published soon',
        'm_parents' => array(7774,7364,7360,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Unlinked',
        'm_desc' => 'archived',
        'm_parents' => array(10686,10678,10673,6186),
    ),
);

//Entity Database References:
$config['en_ids_6194'] = array(6177,7596,4737,7585,5008,4364,6186,4593);
$config['en_all_6194'] = array(
    6177 => array(
        'm_icon' => '<i class="far fa-sliders-h blue"></i>',
        'm_name' => 'Entity Status',
        'm_desc' => 'SELECT count(en_id) as totals FROM table_entities WHERE en_status_entity_id=',
        'm_parents' => array(6204,5003,10654,6160,6232,7303,6194,6206,4527),
    ),
    7596 => array(
        'm_icon' => '<i class="fas fa-mountains isgreen"></i>',
        'm_name' => 'Intent Scope',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_intents WHERE in_status_entity_id IN (6183,6184) AND in_scope_entity_id=',
        'm_parents' => array(10610,6204,10649,7302,6160,6201,6194,6232,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="far fa-sliders-h yellow"></i>',
        'm_name' => 'Intent Status',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_intents WHERE in_status_entity_id=',
        'm_parents' => array(6204,10648,6226,6160,6232,7302,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="far fa-shapes yellow"></i>',
        'm_name' => 'Intent Subtype',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_intents WHERE in_status_entity_id IN (6183,6184) AND in_subtype_entity_id=',
        'm_parents' => array(10608,6204,10651,6160,6194,6232,4527,6201),
    ),
    5008 => array(
        'm_icon' => '<i class="far fa-tools yellow"></i>',
        'm_name' => 'Intent Verb',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_intents WHERE in_status_entity_id IN (6183,6184) AND in_verb_entity_id=',
        'm_parents' => array(6204,10647,4736,7777,6160,6232,7302,4506,6194,6201),
    ),
    4364 => array(
        'm_icon' => '<i class="far fa-user-edit ispink"></i>',
        'm_name' => 'Link Creator',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_links WHERE ln_status_entity_id IN (6175,6176) AND ln_creator_entity_id=',
        'm_parents' => array(6160,6232,6194,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Link Status',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_links WHERE ln_status_entity_id=',
        'm_parents' => array(10677,10661,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug ispink ispink"></i>',
        'm_name' => 'Link Type',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_links WHERE ln_status_entity_id IN (6175,6176) AND ln_type_entity_id=',
        'm_parents' => array(10607,10659,6160,6232,6194,4527,4341),
    ),
);

//Community Members:
$config['en_ids_6827'] = array(4430,3084,4433,10691,6695);
$config['en_all_6827'] = array(
    4430 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Users',
        'm_desc' => 'Users who are pursuing their intentions using Mench, mainly to get hired at their dream job',
        'm_parents' => array(4983,7701,7369,6827,4426,4463),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut"></i>',
        'm_name' => 'Experts',
        'm_desc' => 'Experienced in their respective industry with a track record of advancing their field of knowldge',
        'm_parents' => array(10571,4983,6827,4463),
    ),
    4433 => array(
        'm_icon' => '<i class="far fa-user-ninja"></i>',
        'm_name' => 'Coders',
        'm_desc' => 'Software Engineers contributing to our open-source codebase hosted on GitHub',
        'm_parents' => array(10571,6827,4463,4426),
    ),
    10691 => array(
        'm_icon' => '<i class="far fa-user-hard-hat"></i>',
        'm_name' => 'Trainers',
        'm_desc' => 'Users who actively train the Mench personal assistant',
        'm_parents' => array(4527,7368,6827),
    ),
    6695 => array(
        'm_icon' => '<i class="far fa-users"></i>',
        'm_name' => 'Employers',
        'm_desc' => 'Companies who partner with Mench to automate their job posting conversations',
        'm_parents' => array(6827,4426,4463),
    ),
);

//Entity Link Content Requires Text:
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

//Intent Note Conversations:
$config['en_ids_6345'] = array(6242,4231);
$config['en_all_6345'] = array(
    6242 => array(
        'm_icon' => '<i class="far fa-lightbulb-on ispink"></i>',
        'm_name' => 'Intent Note Endnote',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4603,4593,4986,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
);

//User Steps Skippable:
$config['en_ids_6274'] = array(4559);
$config['en_all_6274'] = array(
    4559 => array(
        'm_icon' => '<i class="far fa-comments ispink"></i>',
        'm_name' => 'User Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
);

//User Steps Progressed:
$config['en_ids_6255'] = array(10687,7485,7486,6144,10685,4559,7489,7492,6997,6157,7487);
$config['en_all_6255'] = array(
    10687 => array(
        'm_icon' => '<i class="far fa-shield-check ispink"></i>',
        'm_name' => 'User Sent Requirement',
        'm_desc' => '',
        'm_parents' => array(4755,10589,10596,4593,6153,10658,6146,6255),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'User Step Create New Content',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4527,6768,7703,6255,6244,4755,6146,4593),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'User Step Iterated',
        'm_desc' => '',
        'm_parents' => array(4755,10596,10589,4593,10638,10658,6255,6146),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comments ispink"></i>',
        'm_name' => 'User Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="far fa-check-double ispink"></i>',
        'm_name' => 'User Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="far fa-times-square ispink"></i>',
        'm_name' => 'User Step Path Not Found',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6255,4755,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="far fa-stopwatch ispink"></i>',
        'm_name' => 'User Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
);

//User Steps Double:
$config['en_ids_6244'] = array(7486,6144,6157,7487);
$config['en_all_6244'] = array(
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => 'Logged initially when the user arrives at a locked intent that has no immediate OR parents to mark it as complete and has children, which means the only way through is to complete all its children. Marks as complete when ANY/ALL children are complete dependant on if its a AND/OR locked intent.',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'User Step Create New Content',
        'm_desc' => 'Logged initially when the user starts an intent that has a requirement submission (Text, URL, Video, Image, etc...) and is completed when they submit the requirement.',
        'm_parents' => array(10596,10589,4527,6768,7703,6255,6244,4755,6146,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Step Single-Answered',
        'm_desc' => 'Logged initially when the user arrives at a regular OR intent, and completed when they submit their answer.',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="far fa-stopwatch ispink"></i>',
        'm_name' => 'User Step Single-Answered Timely',
        'm_desc' => 'Logged initially when the user starts to answer a timed OR intent, and will be published if they are successful at answering it on time. If not, will update link type to User Step Answer Timeout.',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
);

//User Completed Intention:
$config['en_ids_6150'] = array(6154,6155,7757);
$config['en_all_6150'] = array(
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'Intent Accomplished',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle ispink"></i>',
        'm_name' => 'Intent Cancelled',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    7757 => array(
        'm_icon' => '<i class="fas fa-times-octagon ispink"></i>',
        'm_name' => 'Intent Terminated',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,4593,6150),
    ),
);

//Intent Notes Entity Referencing Optional:
$config['en_ids_4986'] = array(6093,6242,4231);
$config['en_all_4986'] = array(
    6093 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Intent Note Changelog',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-lightbulb-on ispink"></i>',
        'm_name' => 'Intent Note Endnote',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4603,4593,4986,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
);

//My Account Inputs:
$config['en_ids_6225'] = array(6197,3288,3286,4783,3290,3287,3089,3289,6123,4454);
$config['en_all_6225'] = array(
    6197 => array(
        'm_icon' => '<i class="far fa-fingerprint blue"></i>',
        'm_name' => 'Name',
        'm_desc' => 'Your first and last name:',
        'm_parents' => array(10646,5000,4998,4999,6232,6225,6206),
    ),
    3288 => array(
        'm_icon' => '<i class="far fa-envelope-open blue"></i>',
        'm_name' => 'Mench Email',
        'm_desc' => 'Your email address is also used to login to Mench:',
        'm_parents' => array(7555,6225,4426,4755),
    ),
    3286 => array(
        'm_icon' => '<i class="far fa-key blue"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => 'The password used to login to Mench:',
        'm_parents' => array(7578,6225,5969,4755),
    ),
    4783 => array(
        'm_icon' => '<i class="far fa-phone blue"></i>',
        'm_name' => 'Phone Number',
        'm_desc' => 'Your number for potential employers to call you at:',
        'm_parents' => array(6225,4755,4319),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender blue"></i>',
        'm_name' => 'Genders',
        'm_desc' => 'Choose one of the following:',
        'm_parents' => array(6225,6204),
    ),
    3287 => array(
        'm_icon' => '<i class="far fa-language blue"></i>',
        'm_name' => 'Languages',
        'm_desc' => 'Choose all the languages you speak fluently:',
        'm_parents' => array(7552,6225,6122),
    ),
    3089 => array(
        'm_icon' => '<i class="far fa-globe blue"></i>',
        'm_name' => 'Countries',
        'm_desc' => 'Choose your current country of residence:',
        'm_parents' => array(6122,6225),
    ),
    3289 => array(
        'm_icon' => '<i class="far fa-map blue"></i>',
        'm_name' => 'Timezones',
        'm_desc' => 'Choose your current timezone:',
        'm_parents' => array(6204,6225),
    ),
    6123 => array(
        'm_icon' => '<i class="far fa-share-alt-square blue"></i>',
        'm_name' => 'Social Profiles',
        'm_desc' => 'Social profiles you\'d like to share with potential employers:',
        'm_parents' => array(6225,4527),
    ),
    4454 => array(
        'm_icon' => '<i class="far fa-bells blue"></i>',
        'm_name' => 'Subscription Settings',
        'm_desc' => 'Choose how you like to be notified for messages I send you via Messenger:',
        'm_parents' => array(7552,6225,6204,4527),
    ),
);

//Intent Status:
$config['en_ids_4737'] = array(6184,6183,6182);
$config['en_all_4737'] = array(
    6184 => array(
        'm_icon' => '<i class="fas fa-globe yellow"></i>',
        'm_name' => 'Published',
        'm_desc' => 'newly added by miner but not yet checked by moderator',
        'm_parents' => array(7774,7355,7356,4737),
    ),
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin yellow"></i>',
        'm_name' => 'Drafting',
        'm_desc' => 'newly added, pending review',
        'm_parents' => array(7774,7356,4737),
    ),
    6182 => array(
        'm_icon' => '<i class="far fa-trash-alt yellow"></i>',
        'm_name' => 'Archived',
        'm_desc' => 'archived',
        'm_parents' => array(10671,4737),
    ),
);

//Entity Status:
$config['en_ids_6177'] = array(6181,6180,6178);
$config['en_all_6177'] = array(
    6181 => array(
        'm_icon' => '<i class="fas fa-globe blue"></i>',
        'm_name' => 'Published',
        'm_desc' => 'live and ready to be shared with users',
        'm_parents' => array(7774,7358,7357,6177),
    ),
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin blue"></i>',
        'm_name' => 'Drafting',
        'm_desc' => 'being mined, to be published soon',
        'm_parents' => array(7774,7358,6177),
    ),
    6178 => array(
        'm_icon' => '<i class="far fa-trash-alt blue"></i>',
        'm_name' => 'Archived',
        'm_desc' => 'archived',
        'm_parents' => array(10672,6177),
    ),
);

//User Steps Taken:
$config['en_ids_6146'] = array(10687,7488,7485,7486,6144,7741,10685,4559,7489,7492,6997,6157,7487,6143);
$config['en_all_6146'] = array(
    10687 => array(
        'm_icon' => '<i class="far fa-shield-check ispink"></i>',
        'm_name' => 'User Sent Requirement',
        'm_desc' => '',
        'm_parents' => array(4755,10589,10596,4593,6153,10658,6146,6255),
    ),
    7488 => array(
        'm_icon' => '<i class="far fa-calendar-times ispink"></i>',
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
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'Children Unlock',
        'm_desc' => 'When users unlock locked AND or OR intents by completing ALL or ANY of their children',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'Create New Content',
        'm_desc' => 'Completed when students submit the intent completion requirements (text, URL, video, etc...) set by miners',
        'm_parents' => array(10596,10589,4527,6768,7703,6255,6244,4755,6146,4593),
    ),
    7741 => array(
        'm_icon' => '<i class="far fa-times-circle ispink"></i>',
        'm_name' => 'Intention Terminated',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,7740,6146),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'Iterated',
        'm_desc' => '',
        'm_parents' => array(4755,10596,10589,4593,10638,10658,6255,6146),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comments ispink"></i>',
        'm_name' => 'Messages Only',
        'm_desc' => 'Completed when students complete a basic AND intent without any submission requirements',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="far fa-check-double ispink"></i>',
        'm_name' => 'Multi-Answered',
        'm_desc' => 'User made a selection as part of a multiple-choice answer question',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="far fa-times-square ispink"></i>',
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
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'Single-Answered',
        'm_desc' => 'Completed after the student answers the question to the OR intent.',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="far fa-stopwatch ispink"></i>',
        'm_name' => 'Single-Answered Timely',
        'm_desc' => 'When the user answers a question within the defined timeframe',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-fast-forward ispink"></i>',
        'm_name' => 'Skipped',
        'm_desc' => 'Completed when students skip an intention and all its child intentions from their Action Plan',
        'm_parents' => array(10596,10589,6146,4755,4593),
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

//User Sent Messages with Messenger:
$config['en_ids_4277'] = array(7654,6554,7653);
$config['en_all_4277'] = array(
    7654 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Sent Messenger Automated Messages',
        'm_desc' => '',
        'm_parents' => array(4277),
    ),
    6554 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
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

//User Sent/Received Attachment:
$config['en_ids_6102'] = array(4554,4556,4555,4553,4549,4551,4550,4548);
$config['en_all_6102'] = array(
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
);

//User Received Messages with Messenger:
$config['en_ids_4280'] = array(4554,4556,4555,6563,4552,4553);
$config['en_all_4280'] = array(
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'User Received Text',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
);

//System Lock:
$config['en_ids_5969'] = array(6196,3286);
$config['en_all_5969'] = array(
    6196 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Mench Messenger',
        'm_desc' => '',
        'm_parents' => array(5969,7555,3320),
    ),
    3286 => array(
        'm_icon' => '<i class="far fa-key blue"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => 'Managed through the Forgot Password section in the Login page',
        'm_parents' => array(7578,6225,5969,4755),
    ),
);

//Entities Mass Iterations:
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
        'm_name' => 'Link Contents',
        'm_desc' => 'Search for occurance of string in child entity link contents and if found, updates it with a replacement string',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Link Status',
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

//Modification Lock:
$config['en_ids_4426'] = array(4997,4433,3288,6695,1308,4430,4426,4755,5969);
$config['en_all_4426'] = array(
    4997 => array(
        'm_icon' => '<i class="fas fa-list-alt ispink"></i>',
        'm_name' => 'Entities Mass Iterations',
        'm_desc' => '',
        'm_parents' => array(4536,4506,4426,4527),
    ),
    4433 => array(
        'm_icon' => '<i class="far fa-user-ninja"></i>',
        'm_name' => 'Mench Coders',
        'm_desc' => 'Requires admin review and approval',
        'm_parents' => array(10571,6827,4463,4426),
    ),
    3288 => array(
        'm_icon' => '<i class="far fa-envelope-open blue"></i>',
        'm_name' => 'Mench Email',
        'm_desc' => '',
        'm_parents' => array(7555,6225,4426,4755),
    ),
    6695 => array(
        'm_icon' => '<i class="far fa-users"></i>',
        'm_name' => 'Mench Employers',
        'm_desc' => '',
        'm_parents' => array(6827,4426,4463),
    ),
    1308 => array(
        'm_icon' => '<i class="far fa-user-hard-hat isturquoise"></i>',
        'm_name' => 'Mench Trainers Level 2',
        'm_desc' => 'Requires admin review and approval',
        'm_parents' => array(10626,10691,10571,4463,4426),
    ),
    4430 => array(
        'm_icon' => '<i class="far fa-user"></i>',
        'm_name' => 'Mench Users',
        'm_desc' => '',
        'm_parents' => array(4983,7701,7369,6827,4426,4463),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Modification Lock',
        'm_desc' => '',
        'm_parents' => array(3303,6771,4426,4527,4757,4428),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash ispink"></i>',
        'm_name' => 'Private Link Types',
        'm_desc' => '',
        'm_parents' => array(6771,4463,4426,4527,4757),
    ),
    5969 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'System Lock',
        'm_desc' => '',
        'm_parents' => array(3303,6771,4426,4527,4757,4428),
    ),
);

//Private Link Types:
$config['en_ids_4755'] = array(10681,6132,3288,3286,4783,4246,7504,6415,4275,6559,6560,6556,6578,6154,6155,6149,7611,6969,4283,7495,7542,4235,7757,7610,7578,6224,5967,7563,4266,4267,4282,4554,4570,4556,4555,7702,6563,4552,4553,4577,4549,4551,4550,4557,4278,4279,4268,4299,4460,10687,4547,4287,4548,7561,7564,7559,7560,7558,7488,7485,7486,6144,7741,10685,6140,4559,7489,7492,6997,6157,7487,6143,7562);
$config['en_all_4755'] = array(
    10681 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered Automatically',
        'm_desc' => '',
        'm_parents' => array(10638,10589,10596,4755,4593,10658),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered by User',
        'm_desc' => '',
        'm_parents' => array(10638,10539,10639,10589,6153,4506,4755,4593),
    ),
    3288 => array(
        'm_icon' => '<i class="far fa-envelope-open blue"></i>',
        'm_name' => 'Mench Email',
        'm_desc' => '',
        'm_parents' => array(7555,6225,4426,4755),
    ),
    3286 => array(
        'm_icon' => '<i class="far fa-key blue"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => '',
        'm_parents' => array(7578,6225,5969,4755),
    ),
    4783 => array(
        'm_icon' => '<i class="far fa-phone blue"></i>',
        'm_name' => 'Phone Number',
        'm_desc' => '',
        'm_parents' => array(6225,4755,4319),
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
    6415 => array(
        'm_icon' => '<i class="far fa-function ispink"></i>',
        'm_name' => 'User Cleared Action Plan',
        'm_desc' => '',
        'm_parents' => array(10596,10589,5967,4755,6418,4593,6414),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Intent',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10589,6554,4755,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Next',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'User Intent Accomplished',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle ispink"></i>',
        'm_name' => 'User Intent Cancelled',
        'm_desc' => '',
        'm_parents' => array(10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus ispink"></i>',
        'm_name' => 'User Intent Considered',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7611 => array(
        'm_icon' => '<i class="fas fa-hand-pointer ispink"></i>',
        'm_name' => 'User Intent Expanded',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,7610,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="fas fa-megaphone ispink"></i>',
        'm_name' => 'User Intent Featured',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,4593,4755,6153),
    ),
    4283 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Intent Listed',
        'm_desc' => '',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Recommended',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    7542 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Required',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Set',
        'm_desc' => '',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
    7757 => array(
        'm_icon' => '<i class="fas fa-times-octagon ispink"></i>',
        'm_name' => 'User Intent Terminated',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,4593,6150),
    ),
    7610 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Intent Viewed',
        'm_desc' => '',
        'm_parents' => array(10596,10590,7765,7612,4755,4593),
    ),
    7578 => array(
        'm_icon' => '<i class="far fa-key ispink"></i>',
        'm_name' => 'User Iterated Password',
        'm_desc' => '',
        'm_parents' => array(6222,10658,6153,10539,10589,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="far fa-sync ispink"></i>',
        'm_name' => 'User Iterated Profile',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,6222,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Link CC Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4506,4527,7569,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Magic-Link Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Opened Profile',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,6222,4593),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="far fa-envelope ispink"></i>',
        'm_name' => 'User Received Email',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7702 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Received Intent Email',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'User Received Text',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'User Sent Access',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle ispink"></i>',
        'm_name' => 'User Sent Location',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Sent Messenger Read',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download ispink"></i>',
        'm_name' => 'User Sent Messenger Received',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag ispink"></i>',
        'm_name' => 'User Sent Postback',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-id-badge ispink"></i>',
        'm_name' => 'User Sent Profile Photo',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7654,6222,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    10687 => array(
        'm_icon' => '<i class="far fa-shield-check ispink"></i>',
        'm_name' => 'User Sent Requirement',
        'm_desc' => '',
        'm_parents' => array(4755,10589,10596,4593,6153,10658,6146,6255),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'User Sent Text',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'User Sent Unknown Message',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin Generally',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin Success',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fas fa-envelope-open ispink"></i>',
        'm_name' => 'User Signin with Email',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin with Intention',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Signin with Messenger',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7488 => array(
        'm_icon' => '<i class="far fa-calendar-times ispink"></i>',
        'm_name' => 'User Step Answer Timeout',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'User Step Create New Content',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4527,6768,7703,6255,6244,4755,6146,4593),
    ),
    7741 => array(
        'm_icon' => '<i class="far fa-times-circle ispink"></i>',
        'm_name' => 'User Step Intention Terminated',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,7740,6146),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'User Step Iterated',
        'm_desc' => '',
        'm_parents' => array(4755,10596,10589,4593,10638,10658,6255,6146),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Link Unlock',
        'm_desc' => '',
        'm_parents' => array(10539,10589,6410,4229,4755,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comments ispink"></i>',
        'm_name' => 'User Step Messages Only',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="far fa-check-double ispink"></i>',
        'm_name' => 'User Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="far fa-times-square ispink"></i>',
        'm_name' => 'User Step Path Not Found',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6255,4755,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="far fa-stopwatch ispink"></i>',
        'm_name' => 'User Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-fast-forward ispink"></i>',
        'm_name' => 'User Step Skipped',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6146,4755,4593),
    ),
    7562 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Welcome Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
);

//User Account Types:
$config['en_ids_4600'] = array(2750,1278);
$config['en_all_4600'] = array(
    2750 => array(
        'm_icon' => '<i class="fas fa-user-tie"></i>',
        'm_name' => 'Companies',
        'm_desc' => '',
        'm_parents' => array(3463,4600),
    ),
    1278 => array(
        'm_icon' => '👪',
        'm_name' => 'People',
        'm_desc' => '',
        'm_parents' => array(4600),
    ),
);

//Link Type:
$config['en_ids_4593'] = array(10625,5943,5001,5865,4999,4998,5000,5981,5982,5003,4251,10672,10653,10646,10654,4259,4257,4261,4260,4319,10657,10656,10659,10689,4230,4255,4318,10673,4256,4258,4250,10671,10650,10644,10649,10648,10651,10647,4229,10663,10664,10661,10662,4228,10686,10660,6093,6242,7545,4601,4231,10679,10677,10676,7701,10678,10573,4983,6226,10681,10675,6132,4246,7504,5007,4994,4993,6415,4275,6559,6560,6556,6578,6154,6155,6149,7611,6969,4283,7495,7542,4235,7757,7610,7578,6224,5967,7563,10690,4266,4267,4282,10683,4554,4570,4556,4555,7702,6563,4552,4553,4577,4549,4551,4550,4557,4278,4279,4268,4299,4460,10687,4547,4287,4548,7561,7564,7559,7560,7558,7488,7485,7486,6144,7741,10685,6140,4559,7489,7492,6997,6157,7487,6143,7562);
$config['en_all_4593'] = array(
    10625 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Entities Icon Replace',
        'm_desc' => '',
        'm_parents' => array(10589,10596,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Entities Icon Update',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Entities Link Contents',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entities Link Status',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entities Name Postfix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entities Name Prefix',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entities Name Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fas fa-layer-plus ispink"></i>',
        'm_name' => 'Entities Parent Add',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fas fa-layer-minus ispink"></i>',
        'm_name' => 'Entities Parent Remove',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entities Status Replace',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,4997),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at ispink"></i>',
        'm_name' => 'Entity Created',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(10645,10593,10589,4593),
    ),
    10672 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Entity Iterated Archived',
        'm_desc' => '',
        'm_parents' => array(10539,4593,10589,10645),
    ),
    10653 => array(
        'm_icon' => '<i class="far fa-user-circle ispink"></i>',
        'm_name' => 'Entity Iterated Icon',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
    10646 => array(
        'm_icon' => '<i class="far fa-fingerprint ispink"></i>',
        'm_name' => 'Entity Iterated Name',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10645),
    ),
    10654 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entity Iterated Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10645),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Entity Link Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle ispink"></i>',
        'm_name' => 'Entity Link Embed',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537,4506),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'Entity Link File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Entity Link Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down ispink"></i>',
        'm_name' => 'Entity Link Integer',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    10657 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Entity Link Iterated Content',
        'm_desc' => '',
        'm_parents' => array(10593,4593,10589,10658,10645),
    ),
    10656 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Entity Link Iterated Status',
        'm_desc' => '',
        'm_parents' => array(4593,10589,10539,10658,10645),
    ),
    10659 => array(
        'm_icon' => '<i class="fas fa-plug ispink"></i>',
        'm_name' => 'Entity Link Iterated Type',
        'm_desc' => 'Iterations happens automatically based on link content',
        'm_parents' => array(10658,10589,4593,10596,10645),
    ),
    10689 => array(
        'm_icon' => '<i class="fas fa-share-alt rotate90 ispink"></i>',
        'm_name' => 'Entity Link Merged',
        'm_desc' => 'When an entity is merged with another entity and the links are carried over',
        'm_parents' => array(10596,10589,4593,10658,10645),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-level-up rotate90 ispink"></i>',
        'm_name' => 'Entity Link Raw',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'Entity Link Text',
        'm_desc' => '',
        'm_parents' => array(10593,10589,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Entity Link Time',
        'm_desc' => '',
        'm_parents' => array(10594,10589,4593,4592),
    ),
    10673 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Entity Link Unlinked',
        'm_desc' => '',
        'm_parents' => array(10645,4593,10539,10589,10658),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser ispink"></i>',
        'm_name' => 'Entity Link URL',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Entity Link Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Intent Created',
        'm_desc' => '',
        'm_parents' => array(10638,10593,10589,4593),
    ),
    10671 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Iterated Archived',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,7703,10638),
    ),
    10650 => array(
        'm_icon' => '<i class="far fa-clock ispink"></i>',
        'm_name' => 'Intent Iterated Completion Time',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10644 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'Intent Iterated Outcome',
        'm_desc' => 'Logged when trainers update the intent outcome',
        'm_parents' => array(7703,10589,10593,4593,10638),
    ),
    10649 => array(
        'm_icon' => '<i class="fas fa-mountains ispink"></i>',
        'm_name' => 'Intent Iterated Scope',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10648 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Iterated Status',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10651 => array(
        'm_icon' => '<i class="far fa-shapes ispink"></i>',
        'm_name' => 'Intent Iterated Subtype',
        'm_desc' => '',
        'm_parents' => array(7703,10539,4593,10589,10638),
    ),
    10647 => array(
        'm_icon' => '<i class="far fa-tools ispink"></i>',
        'm_name' => 'Intent Iterated Verb',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4593,10638),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Intent Link Conditional',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
    10663 => array(
        'm_icon' => '<i class="far fa-file-certificate ispink"></i>',
        'm_name' => 'Intent Link Iterated Marks',
        'm_desc' => '',
        'm_parents' => array(4228,7703,10539,10589,10638,4593,10658),
    ),
    10664 => array(
        'm_icon' => '<i class="fas fa-bolt ispink"></i>',
        'm_name' => 'Intent Link Iterated Score',
        'm_desc' => '',
        'm_parents' => array(7703,10638,10594,10589,4593,4229,10658),
    ),
    10661 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Link Iterated Status',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    10662 => array(
        'm_icon' => '<i class="fas fa-hashtag ispink"></i>',
        'm_name' => 'Intent Link Iterated Type',
        'm_desc' => '',
        'm_parents' => array(10638,7703,10539,10589,4593,10658),
    ),
    4228 => array(
        'm_icon' => '<i class="fas fa-walking ispink"></i>',
        'm_name' => 'Intent Link Required',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,6410,4593,4486),
    ),
    10686 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Link Unlinked',
        'm_desc' => '',
        'm_parents' => array(7703,10589,10539,4593,10658,10638),
    ),
    10660 => array(
        'm_icon' => '<i class="fas fa-angle-double-right ispink"></i>',
        'm_name' => 'Intent Migrate Parent Link',
        'm_desc' => '',
        'm_parents' => array(7703,4593,10638,10589,10594,10658),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Intent Note Changelog',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-lightbulb-on ispink"></i>',
        'm_name' => 'Intent Note Endnote',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4603,4593,4986,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="far fa-tag ispink"></i>',
        'm_name' => 'Intent Note Entity Tag',
        'm_desc' => '',
        'm_parents' => array(10594,10589,7703,7551,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-search ispink"></i>',
        'm_name' => 'Intent Note Keyword',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
    10679 => array(
        'm_icon' => '<i class="fas fa-sticky-note ispink"></i>',
        'm_name' => 'Intent Notes Iterated Content',
        'm_desc' => '',
        'm_parents' => array(10589,4593,10593,10658,10638),
    ),
    10677 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intent Notes Iterated Status',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    10676 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intent Notes Ordered',
        'm_desc' => '',
        'm_parents' => array(4593,10539,10589,10658,10638),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-rss ispink"></i>',
        'm_name' => 'Intent Note Subscriber',
        'm_desc' => 'When trainers subscribe to receive intent updates and manage the intent.',
        'm_parents' => array(10594,10589,4527,7703,4593,7551,4485),
    ),
    10678 => array(
        'm_icon' => '<i class="far fa-trash-alt ispink"></i>',
        'm_name' => 'Intent Notes Unlinked',
        'm_desc' => '',
        'm_parents' => array(10658,10539,10589,4593,10638),
    ),
    10573 => array(
        'm_icon' => '<i class="far fa-user-hard-hat ispink"></i>',
        'm_name' => 'Intent Note Trainer',
        'm_desc' => 'Keeps track of the users who can manage/edit the intent',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => 'References track intent correlations referenced within expert sources, and represent a core building block of intelligence. References are among the most precious transaction types because they indicate that IF you do A, you will likely accomplish B. As trainers add more sources from more experts, certain intent correlations will receive more references than others, thus gaining more credibility.',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    6226 => array(
        'm_icon' => '<i class="far fa-sliders-h ispink"></i>',
        'm_name' => 'Intents Iterated Statuses',
        'm_desc' => ' When all intents within a recursive tree are updated at once.',
        'm_parents' => array(10655,10596,10589,4593),
    ),
    10681 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered Automatically',
        'm_desc' => '',
        'm_parents' => array(10638,10589,10596,4755,4593,10658),
    ),
    10675 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered by Trainer',
        'm_desc' => '',
        'm_parents' => array(7703,10539,10589,4593,10658,10638),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-sort ispink"></i>',
        'm_name' => 'Intents Ordered by User',
        'm_desc' => 'Student re-prioritized their top-level intentions to focus on intentions that currently matter the most.',
        'm_parents' => array(10638,10539,10639,10589,6153,4506,4755,4593),
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
    5007 => array(
        'm_icon' => '<i class="fas fa-expand-arrows ispink"></i>',
        'm_name' => 'Trainer Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7368,4757,4593),
    ),
    4994 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Trainer View Entity',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4593),
    ),
    4993 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'Trainer View Intent',
        'm_desc' => '',
        'm_parents' => array(10596,10590,7612,4593),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function ispink"></i>',
        'm_name' => 'User Cleared Action Plan',
        'm_desc' => 'Removes certain links types as defined by its children from a Student\'s Action Plan. Currently only available for trainers.',
        'm_parents' => array(10596,10589,5967,4755,6418,4593,6414),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Intent',
        'm_desc' => 'When students invokes the [I want to] command and search for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(10639,10596,10589,6554,4755,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Next',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic ispink"></i>',
        'm_name' => 'User Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6554),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain ispink"></i>',
        'm_name' => 'User Intent Accomplished',
        'm_desc' => 'Student accomplished their intention 🎉🎉🎉',
        'm_parents' => array(10539,10639,10589,10570,7758,7703,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle ispink"></i>',
        'm_name' => 'User Intent Cancelled',
        'm_desc' => 'Student prematurely removed an intention from their Action Plan without accomplishing it.',
        'm_parents' => array(10539,10639,10589,10570,7703,4506,6150,4593,4755),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus ispink"></i>',
        'm_name' => 'User Intent Considered',
        'm_desc' => 'When a student chooses to review a given intention from the intentions they have searched or have been recommended after selecting GET STARTED from a mench.com intent landing page.',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7611 => array(
        'm_icon' => '<i class="fas fa-hand-pointer ispink"></i>',
        'm_name' => 'User Intent Expanded',
        'm_desc' => 'Logged when a user expands a section of the intent',
        'm_parents' => array(10639,10596,10590,7610,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="fas fa-megaphone ispink"></i>',
        'm_name' => 'User Intent Featured',
        'm_desc' => 'Logged every time an intention is recommended to a user by Mench',
        'm_parents' => array(10639,10596,10590,4593,4755,6153),
    ),
    4283 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Intent Listed',
        'm_desc' => 'Once a student has added an Intention to their Action Plan, this link will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(10639,10596,10590,6153,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Recommended',
        'm_desc' => 'Intention recommended by Mench and added to Action Plan to enable the user to complete their intention',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    7542 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Required',
        'm_desc' => '',
        'm_parents' => array(10596,7347,10590,4755,4593),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow ispink"></i>',
        'm_name' => 'User Intent Set',
        'm_desc' => 'Intentions set by users which will be completed by taking steps using the Action Plan',
        'm_parents' => array(10539,7347,10589,5967,4755,4593),
    ),
    7757 => array(
        'm_icon' => '<i class="fas fa-times-octagon ispink"></i>',
        'm_name' => 'User Intent Terminated',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,4593,6150),
    ),
    7610 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Intent Viewed',
        'm_desc' => 'When a user viewes the public intent landing page.',
        'm_parents' => array(10596,10590,7765,7612,4755,4593),
    ),
    7578 => array(
        'm_icon' => '<i class="far fa-key ispink"></i>',
        'm_name' => 'User Iterated Password',
        'm_desc' => '',
        'm_parents' => array(6222,10658,6153,10539,10589,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="far fa-sync ispink"></i>',
        'm_name' => 'User Iterated Profile',
        'm_desc' => '',
        'm_parents' => array(10539,10589,4755,6222,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Link CC Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4506,4527,7569,4755,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Magic-Link Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
    10690 => array(
        'm_icon' => '<i class="fas fa-upload ispink"></i>',
        'm_name' => 'User Media Uploaded',
        'm_desc' => 'When a file added by the user is synced to the CDN',
        'm_parents' => array(6153,10596,10589,4593,10658),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Opened Profile',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,6222,4593),
    ),
    10683 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Read Email',
        'm_desc' => '',
        'm_parents' => array(6153,10658,10596,10589,4593,7654),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="far fa-envelope ispink"></i>',
        'm_name' => 'User Received Email',
        'm_desc' => '',
        'm_parents' => array(10683,10593,10590,7569,4755,4593),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    7702 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Received Intent Email',
        'm_desc' => 'Emails sent to intent subscribers who are looking for updates on an intent.',
        'm_parents' => array(10593,10590,4593,4755,7569),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4593,4755,4280),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'User Received Text',
        'm_desc' => '',
        'm_parents' => array(10593,10590,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10590,6102,4755,4593,4280),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus ispink"></i>',
        'm_name' => 'User Sent Access',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle ispink"></i>',
        'm_name' => 'User Sent Location',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye ispink"></i>',
        'm_name' => 'User Sent Messenger Read',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download ispink"></i>',
        'm_name' => 'User Sent Messenger Received',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag ispink"></i>',
        'm_name' => 'User Sent Postback',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-id-badge ispink"></i>',
        'm_name' => 'User Sent Profile Photo',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7654,6222,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    10687 => array(
        'm_icon' => '<i class="far fa-shield-check ispink"></i>',
        'm_name' => 'User Sent Requirement',
        'm_desc' => '',
        'm_parents' => array(4755,10589,10596,4593,6153,10658,6146,6255),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left ispink"></i>',
        'm_name' => 'User Sent Text',
        'm_desc' => '',
        'm_parents' => array(10539,10589,7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'User Sent Unknown Message',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,7653,6102,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin Generally',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin Success',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fas fa-envelope-open ispink"></i>',
        'm_name' => 'User Signin with Email',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fas fa-sign-in ispink"></i>',
        'm_name' => 'User Signin with Intention',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger ispink"></i>',
        'm_name' => 'User Signin with Messenger',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593),
    ),
    7488 => array(
        'm_icon' => '<i class="far fa-calendar-times ispink"></i>',
        'm_name' => 'User Step Answer Timeout',
        'm_desc' => '',
        'm_parents' => array(10596,10589,4755,4593,6146),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,6244,6146,4755,4593,6255),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-cloud-upload-alt ispink"></i>',
        'm_name' => 'User Step Create New Content',
        'm_desc' => 'Logged when a student submits the requirements (text, video, etc...) of an AND intent which could not be completed by simply receiving messages.',
        'm_parents' => array(10596,10589,4527,6768,7703,6255,6244,4755,6146,4593),
    ),
    7741 => array(
        'm_icon' => '<i class="far fa-times-circle ispink"></i>',
        'm_name' => 'User Step Intention Terminated',
        'm_desc' => 'User ended their Action Plan prematurely',
        'm_parents' => array(10596,10589,4755,4593,7740,6146),
    ),
    10685 => array(
        'm_icon' => '<i class="fas fa-sync ispink"></i>',
        'm_name' => 'User Step Iterated',
        'm_desc' => 'When users update their a step they made previous progress',
        'm_parents' => array(4755,10596,10589,4593,10638,10658,6255,6146),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Link Unlock',
        'm_desc' => 'Created when the student responses to OR branches meets the right % points to unlock the pathway to a conditional intent link.',
        'm_parents' => array(10539,10589,6410,4229,4755,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comments ispink"></i>',
        'm_name' => 'User Step Messages Only',
        'm_desc' => 'Logged when a student receives the messages of an AND intent that does not have any completion requirements.',
        'm_parents' => array(10596,10589,7703,6274,6255,4755,6146,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="far fa-check-double ispink"></i>',
        'm_name' => 'User Step Multi-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,4755,6255,4593,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="far fa-times-square ispink"></i>',
        'm_name' => 'User Step Path Not Found',
        'm_desc' => '',
        'm_parents' => array(10596,10589,6255,4755,4593,6146),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open ispink"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7703,7494,4229,6255,4593,4755,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="far fa-check ispink"></i>',
        'm_name' => 'User Step Single-Answered',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6255,6244,6146,4755,4593),
    ),
    7487 => array(
        'm_icon' => '<i class="far fa-stopwatch ispink"></i>',
        'm_name' => 'User Step Single-Answered Timely',
        'm_desc' => '',
        'm_parents' => array(10596,10589,7704,7703,6244,4755,6255,4593,6146),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-fast-forward ispink"></i>',
        'm_name' => 'User Step Skipped',
        'm_desc' => 'Logged every time a student consciously skips an intent and it\'s recursive children.',
        'm_parents' => array(10596,10589,6146,4755,4593),
    ),
    7562 => array(
        'm_icon' => '<i class="far fa-envelope-open ispink"></i>',
        'm_name' => 'User Welcome Email',
        'm_desc' => '',
        'm_parents' => array(10596,10590,4755,7569,4593),
    ),
);

//Entity-to-Entity Links:
$config['en_ids_4592'] = array(4259,4257,4261,4260,4319,4230,4255,4318,4256,4258);
$config['en_all_4592'] = array(
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
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
    4230 => array(
        'm_icon' => '<i class="far fa-level-up rotate90 ispink"></i>',
        'm_name' => 'Raw',
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

//Intent Notes:
$config['en_ids_4485'] = array(4231,4983,4601,6242,7545,6093,7701,10573);
$config['en_all_4485'] = array(
    4231 => array(
        'm_icon' => '<i class="far fa-comment ispink"></i>',
        'm_name' => 'Message',
        'm_desc' => 'Delivered in-order when student initially starts this intent. Goal is to give key insights that streamline the execution of the intention.',
        'm_parents' => array(10593,10589,7703,6345,4986,4603,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-thumbs-up ispink"></i>',
        'm_name' => 'Up-Vote',
        'm_desc' => 'Tracks intent correlations mined from expert sources and miner perspectives. References give credibility to intent correlations. Never communicated with Students and only used for weighting purposes, like how Google uses link correlations for its PageRank algorithm.',
        'm_parents' => array(10593,10589,4527,7703,7551,4985,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-search ispink"></i>',
        'm_name' => 'Keyword',
        'm_desc' => 'Never delivered to students, instead, it maps alternative ways an intent could be named so we can better understand student commands.',
        'm_parents' => array(10593,10589,7703,4593,4485),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-lightbulb-on ispink"></i>',
        'm_name' => 'Endnote',
        'm_desc' => 'Message delivered to students when they complete an intention.',
        'm_parents' => array(10593,10589,7703,6345,4603,4593,4986,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="far fa-tag ispink"></i>',
        'm_name' => 'Entity Tag',
        'm_desc' => 'When completed by the user, adds the user as the child of these entities, and stores potential user response as link content.',
        'm_parents' => array(10594,10589,7703,7551,4593,4485),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comment-exclamation ispink"></i>',
        'm_name' => 'Changelog',
        'm_desc' => 'Similar to Wikipedia\'s Talk pages, the Mench changelog helps miners track the history and evolution of a intent and explain/propose changes/improvements.',
        'm_parents' => array(10593,10589,7703,4593,4986,4485),
    ),
    7701 => array(
        'm_icon' => '<i class="far fa-rss ispink"></i>',
        'm_name' => 'Subscriber',
        'm_desc' => 'Allows miners to follow an intent to receive updates.',
        'm_parents' => array(10594,10589,4527,7703,4593,7551,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="far fa-user-hard-hat ispink"></i>',
        'm_name' => 'Trainer',
        'm_desc' => 'Tracks users who can administrate this intent.',
        'm_parents' => array(10594,10589,4593,7703,7551,4485),
    ),
);

//Intent-to-Intent Links:
$config['en_ids_4486'] = array(4228,4229);
$config['en_all_4486'] = array(
    4228 => array(
        'm_icon' => '<i class="fas fa-walking ispink"></i>',
        'm_name' => 'Required',
        'm_desc' => 'Intent link published and added to user Action Plans up-front',
        'm_parents' => array(10594,10589,7703,6410,4593,4486),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock ispink"></i>',
        'm_name' => 'Conditional',
        'm_desc' => 'Intent added to Action Plans after parent intent is complete AND the user\'s % score falls within the defined min/max range',
        'm_parents' => array(10594,10589,7703,4527,6410,6283,4593,4486),
    ),
);

//Entity-to-Entity URL Link Types:
$config['en_ids_4537'] = array(4259,4257,4261,4260,4256,4258);
$config['en_all_4537'] = array(
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up ispink"></i>',
        'm_name' => 'Audio',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle ispink"></i>',
        'm_name' => 'Embed',
        'm_desc' => 'Link note contain a recognizable URL that offers an embed widget for a more engaging play-back experience.',
        'm_parents' => array(10539,10589,4593,4592,4537,4506),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf ispink"></i>',
        'm_name' => 'File',
        'm_desc' => 'Link notes contain a URL to a raw file.',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image ispink"></i>',
        'm_name' => 'Image',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
        'm_parents' => array(10627,10593,10589,6203,4593,4592,4537),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser ispink"></i>',
        'm_name' => 'URL',
        'm_desc' => 'Link note contains a generic URL only.',
        'm_parents' => array(10539,10589,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video ispink"></i>',
        'm_name' => 'Video',
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