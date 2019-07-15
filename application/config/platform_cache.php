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

//Generated 2019-07-15 14:01:55 PST

//Mench Platform Wizards:
$config['en_ids_7529'] = array(7530,7531,7532,7533);
$config['en_all_7529'] = array(
    7530 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'Sign In / Sign Up',
        'm_desc' => '',
        'm_parents' => array(7529),
    ),
    7531 => array(
        'm_icon' => '<i class="fas fa-file-plus"></i>',
        'm_name' => 'Add New Source',
        'm_desc' => '',
        'm_parents' => array(7529),
    ),
    7532 => array(
        'm_icon' => '<i class="fas fa-user-tie"></i>',
        'm_name' => 'Partner Employer Signup',
        'm_desc' => '',
        'm_parents' => array(7529),
    ),
    7533 => array(
        'm_icon' => '<i class="fas fa-briefcase"></i>',
        'm_name' => 'Add Job Posting',
        'm_desc' => '',
        'm_parents' => array(7529),
    ),
);

//User Step Answered:
$config['en_ids_6157'] = array(6684,6685,7231);
$config['en_all_6157'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'OR Intent Single Answer',
        'm_desc' => '',
        'm_parents' => array(6914,6157,6193),
    ),
    6685 => array(
        'm_icon' => '<i class="fas fa-stopwatch"></i>',
        'm_name' => 'OR Intent Timed Answer',
        'm_desc' => '',
        'm_parents' => array(6914,7366,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'OR Intent Multiple Answers',
        'm_desc' => '',
        'm_parents' => array(6914,6157,6193),
    ),
);

//User Steps Unlock:
$config['en_ids_7494'] = array(6997,7298,7485,7486);
$config['en_all_7494'] = array(
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,4229,6255,4595,4593,4755,6146),
    ),
    7298 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Funnel Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,1,6146,6255),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,4595,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,6244,6146,4755,4593,4595,6255),
    ),
);

//AND Intent AND Lock:
$config['en_ids_6914'] = array(6684,6685,7231,7297);
$config['en_all_6914'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'OR Intent Single Answer',
        'm_desc' => 'For Single Answer the default newly created child intent type is AND Lock',
        'm_parents' => array(6914,6157,6193),
    ),
    6685 => array(
        'm_icon' => '<i class="fas fa-stopwatch"></i>',
        'm_name' => 'OR Intent Timed Answer',
        'm_desc' => 'For Timed Answer the default newly created child intent type is AND Lock',
        'm_parents' => array(6914,7366,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'OR Intent Multiple Answers',
        'm_desc' => 'For Multiple Answer the default newly created child intent type is AND Lock',
        'm_parents' => array(6914,6157,6193),
    ),
    7297 => array(
        'm_icon' => '<i class="fas fa-filter"></i>',
        'm_name' => 'AND Intent Funnel Manager',
        'm_desc' => 'For Funnel steps that the user can only unlock with the help of a miner',
        'm_parents' => array(6914,4428,3303,3303,7298,6192),
    ),
);

//Locked Intents:
$config['en_ids_7309'] = array(6907,6914);
$config['en_all_7309'] = array(
    6907 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'OR Intent OR Lock',
        'm_desc' => '',
        'm_parents' => array(7486,7485,7309,6997,6193),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'AND Intent AND Lock',
        'm_desc' => '',
        'm_parents' => array(4527,7486,7485,7309,6997,6192),
    ),
);

//Mench Platform Products:
$config['en_ids_7372'] = array(6196,7369,7368);
$config['en_all_7372'] = array(
    6196 => array(
        'm_icon' => '<img src="https://mench.com/img/bp_128.png">',
        'm_name' => 'Mench Personal Assistant',
        'm_desc' => 'A chatbot that matches software engineers to their dream jobs.',
        'm_parents' => array(7372,3320,4463),
    ),
    7369 => array(
        'm_icon' => '<i class="fas fa-user-graduate"></i>',
        'm_name' => 'Mench User App',
        'm_desc' => 'A web portal for software engineers to assess/improve their skills & get matched with top companies.',
        'm_parents' => array(7372,6196,4527),
    ),
    7368 => array(
        'm_icon' => '<i class="fas fa-user-hard-hat"></i>',
        'm_name' => 'Mench Miner App',
        'm_desc' => 'A web portal for industry researchers to mine expert intelligence as Mench intents/entities.',
        'm_parents' => array(7372,4527),
    ),
);

//Mench Admin Tools:
$config['en_ids_6287'] = array(1281,7257,7258,7274);
$config['en_all_6287'] = array(
    1281 => array(
        'm_icon' => '<i class="fas fa-user-shield"></i>',
        'm_name' => 'Mench Admins',
        'm_desc' => '',
        'm_parents' => array(6287,6827,4463),
    ),
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
$config['en_ids_7369'] = array(4430,6137,6138,7291);
$config['en_all_7369'] = array(
    4430 => array(
        'm_icon' => '<i class="fas fa-user-graduate"></i>',
        'm_name' => 'Mench Users',
        'm_desc' => '',
        'm_parents' => array(7369,6196,6827,4426,4463),
    ),
    6137 => array(
        'm_icon' => '👤',
        'm_name' => 'My Account',
        'm_desc' => 'A web-based portal (also accessible via Messenger) enabling students to manage their account',
        'm_parents' => array(7369),
    ),
    6138 => array(
        'm_icon' => '🚩',
        'm_name' => 'Action Plan',
        'm_desc' => 'A web-based portal (also accessible via Messenger) enabling students to manage their intentions',
        'm_parents' => array(7369,4463),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off"></i>',
        'm_name' => 'Logout',
        'm_desc' => '',
        'm_parents' => array(7368,7369),
    ),
);

//Mench Miner App:
$config['en_ids_7368'] = array(1308,4535,4536,5007,6205,6287,7161,7256,7291);
$config['en_all_7368'] = array(
    1308 => array(
        'm_icon' => '<i class="fas fa-user-hard-hat"></i>',
        'm_name' => 'Mench Miners',
        'm_desc' => '',
        'm_parents' => array(7368,6827,4463,4426),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intents',
        'm_desc' => '',
        'm_parents' => array(7368,4534,4463),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entities',
        'm_desc' => '',
        'm_parents' => array(7368,4534,4463),
    ),
    5007 => array(
        'm_icon' => '<i class="fas fa-expand-arrows"></i>',
        'm_name' => 'Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(7368,4527,4595,4757,4593),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Links',
        'm_desc' => '',
        'm_parents' => array(7368,4534,4463),
    ),
    6287 => array(
        'm_icon' => '<i class="fas fa-user-shield"></i>',
        'm_name' => 'Mench Admin Tools',
        'm_desc' => 'Tools for moderating the Mench platform',
        'm_parents' => array(7368,4527,7284),
    ),
    7161 => array(
        'm_icon' => '<i class="far fa-tachometer-alt-fast"></i>',
        'm_name' => 'Mench Dashboard',
        'm_desc' => '',
        'm_parents' => array(7368,7305),
    ),
    7256 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Platform Search Bar',
        'm_desc' => 'Intents, Entities & URLs',
        'm_parents' => array(7368,3323),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off"></i>',
        'm_name' => 'Logout',
        'm_desc' => '',
        'm_parents' => array(7368,7369),
    ),
);

//Private Intents:
$config['en_ids_7366'] = array(6685);
$config['en_all_7366'] = array(
    6685 => array(
        'm_icon' => '<i class="fas fa-stopwatch"></i>',
        'm_name' => 'OR Intent Timed Answer',
        'm_desc' => '',
        'm_parents' => array(6914,7366,6157,6193),
    ),
);

//Link Statuses Incomplete:
$config['en_ids_7364'] = array(6174,6175);
$config['en_all_7364'] = array(
    6174 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'Link New',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Link Drafting',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
);

//Link Statuses Active:
$config['en_ids_7360'] = array(6174,6175,6176);
$config['en_all_7360'] = array(
    6174 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'Link New',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Link Drafting',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Link Published',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
);

//Link Statuses Public:
$config['en_ids_7359'] = array(6176);
$config['en_all_7359'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Link Published',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
);

//Entity Statuses Active:
$config['en_ids_7358'] = array(6179,6180,6181);
$config['en_all_7358'] = array(
    6179 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'Entity New',
        'm_desc' => '',
        'm_parents' => array(7358,6177),
    ),
    6180 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Entity Drafting',
        'm_desc' => '',
        'm_parents' => array(7357,7358,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Entity Published',
        'm_desc' => '',
        'm_parents' => array(7358,7357,6177),
    ),
);

//Entity Statuses Public:
$config['en_ids_7357'] = array(6180,6181);
$config['en_all_7357'] = array(
    6180 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Entity Drafting',
        'm_desc' => '',
        'm_parents' => array(7357,7358,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Entity Published',
        'm_desc' => '',
        'm_parents' => array(7358,7357,6177),
    ),
);

//Intent Statuses Active:
$config['en_ids_7356'] = array(6183,6184,6185,7351);
$config['en_all_7356'] = array(
    6183 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'Intent New',
        'm_desc' => '',
        'm_parents' => array(7356,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Intent Drafting',
        'm_desc' => '',
        'm_parents' => array(7356,4737),
    ),
    6185 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Intent Published',
        'm_desc' => '',
        'm_parents' => array(7356,7355,4737),
    ),
    7351 => array(
        'm_icon' => '<i class="fas fa-flag"></i>',
        'm_name' => 'Intent Starting Point',
        'm_desc' => '',
        'm_parents' => array(7356,7355,4737),
    ),
);

//Intent Statuses Public:
$config['en_ids_7355'] = array(6185,7351);
$config['en_all_7355'] = array(
    6185 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Intent Published',
        'm_desc' => '',
        'm_parents' => array(7356,7355,4737),
    ),
    7351 => array(
        'm_icon' => '<i class="fas fa-flag"></i>',
        'm_name' => 'Intent Starting Point',
        'm_desc' => '',
        'm_parents' => array(7356,7355,4737),
    ),
);

//Action Plan Intention Set:
$config['en_ids_7347'] = array(4235,7495,7511);
$config['en_all_7347'] = array(
    4235 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'User Intent Set',
        'm_desc' => '',
        'm_parents' => array(7347,4595,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'User Intent Recommended',
        'm_desc' => '',
        'm_parents' => array(4755,4595,4593,7347),
    ),
    7511 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'Company Intent Set',
        'm_desc' => '',
        'm_parents' => array(4755,4595,4593,7483,7347),
    ),
);

//Intent Stats:
$config['en_ids_7302'] = array(4737,5008,6676,7166);
$config['en_all_7302'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h"></i>',
        'm_name' => 'Intent Statuses',
        'm_desc' => '',
        'm_parents' => array(6160,6232,7302,6194,6213,6201,4527),
    ),
    5008 => array(
        'm_icon' => '<i class="fal fa-tools"></i>',
        'm_name' => 'Intent Verbs',
        'm_desc' => '',
        'm_parents' => array(6160,6232,7302,4506,6213,6194,6201),
    ),
    6676 => array(
        'm_icon' => '<i class="fas fa-chart-network"></i>',
        'm_name' => 'Intent Types',
        'm_desc' => '',
        'm_parents' => array(6160,6232,7302,6194,6213,4527,6201),
    ),
    7166 => array(
        'm_icon' => '<i class="far fa-chart-pie"></i>',
        'm_name' => 'Intent Mining Stats',
        'm_desc' => '',
        'm_parents' => array(7302,4527),
    ),
);

//Entity Stats:
$config['en_ids_7303'] = array(3000,6177,6827,7167);
$config['en_all_7303'] = array(
    3000 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'Expert Sources',
        'm_desc' => '',
        'm_parents' => array(7303,3463,4506,4527,4463),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h"></i>',
        'm_name' => 'Entity Statuses',
        'm_desc' => '',
        'm_parents' => array(6160,6232,7303,6194,6213,6206,4527),
    ),
    6827 => array(
        'm_icon' => '<i class="fas fa-users"></i>',
        'm_name' => 'Mench Community',
        'm_desc' => '',
        'm_parents' => array(3303,3314,2738,7303,4527),
    ),
    7167 => array(
        'm_icon' => '<i class="far fa-chart-pie"></i>',
        'm_name' => 'Entity Mining Stats',
        'm_desc' => '',
        'm_parents' => array(7303,4527),
    ),
);

//Link Stats:
$config['en_ids_7304'] = array(4593,6186,7159,7162,7163);
$config['en_all_7304'] = array(
    4593 => array(
        'm_icon' => '<i class="fas fa-plug"></i>',
        'm_name' => 'Link Types',
        'm_desc' => '',
        'm_parents' => array(6160,6232,7304,6213,6194,4527,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="fas fa-sliders-h"></i>',
        'm_name' => 'Link Statuses',
        'm_desc' => '',
        'm_parents' => array(6160,6232,7304,4527,6194,6213,4341),
    ),
    7159 => array(
        'm_icon' => '<i class="far fa-chart-pie"></i>',
        'm_name' => 'User Engagements Stats',
        'm_desc' => '',
        'm_parents' => array(7304,4527),
    ),
    7162 => array(
        'm_icon' => '<i class="far fa-medal"></i>',
        'm_name' => 'Top Miners',
        'm_desc' => '',
        'm_parents' => array(7304),
    ),
    7163 => array(
        'm_icon' => '<i class="far fa-medal"></i>',
        'm_name' => 'Top Users',
        'm_desc' => '',
        'm_parents' => array(7304),
    ),
);

//Toggle Advance Mode:
$config['en_ids_5007'] = array(4232,4997,6093,6242);
$config['en_all_5007'] = array(
    4232 => array(
        'm_icon' => '<i class="far fa-tint"></i>',
        'm_name' => 'Intent Note Drip Message',
        'm_desc' => '',
        'm_parents' => array(5007,4986,4603,4593,4485,4595),
    ),
    4997 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Updates',
        'm_desc' => '',
        'm_parents' => array(5007,4758,4506,4426,4527),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comments"></i>',
        'm_name' => 'Intent Note Chatlog',
        'm_desc' => '',
        'm_parents' => array(5007,4595,4593,4986,4485),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Message',
        'm_desc' => '',
        'm_parents' => array(5007,6345,4603,4595,4593,4986,4485),
    ),
);

//Link Type Filter Groups:
$config['en_ids_7233'] = array(4277,4280,4485,4592,6146,6255,7164);
$config['en_all_7233'] = array(
    4277 => array(
        'm_icon' => '<i class="far fa-ear"></i>',
        'm_name' => 'User Sent Manual Messages',
        'm_desc' => '',
        'm_parents' => array(7233,6221,4527),
    ),
    4280 => array(
        'm_icon' => '<i class="far fa-paper-plane"></i>',
        'm_name' => 'User Received Messages',
        'm_desc' => '',
        'm_parents' => array(7233,6221,4527),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus"></i>',
        'm_name' => 'Intent Notes',
        'm_desc' => '',
        'm_parents' => array(7233,7166,4535,4603,4527,4463),
    ),
    4592 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Entity Links',
        'm_desc' => '',
        'm_parents' => array(7233,7167,4536,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'User Steps Taken',
        'm_desc' => '',
        'm_parents' => array(7233,6219,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-walking"></i>',
        'm_name' => 'User Steps Progressed',
        'm_desc' => '',
        'm_parents' => array(7233,7493,7203,7159,4527),
    ),
    7164 => array(
        'm_icon' => '<i class="far fa-comments"></i>',
        'm_name' => 'Action Plan Messages Exchanged',
        'm_desc' => '',
        'm_parents' => array(7233,7203,7159,4527,6221),
    ),
);

//Weekly Leaderboard Message:
$config['en_ids_7203'] = array(4250,4486,6255,7164);
$config['en_all_7203'] = array(
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intent Created',
        'm_desc' => '',
        'm_parents' => array(7203,7166,4593,4595),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Intent Links',
        'm_desc' => '',
        'm_parents' => array(7203,7166,4535,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-walking"></i>',
        'm_name' => 'User Steps Progressed',
        'm_desc' => '',
        'm_parents' => array(7233,7493,7203,7159,4527),
    ),
    7164 => array(
        'm_icon' => '<i class="far fa-comments"></i>',
        'm_name' => 'Action Plan Messages Exchanged',
        'm_desc' => '',
        'm_parents' => array(7233,7203,7159,4527,6221),
    ),
);

//Entity Mining Stats:
$config['en_ids_7167'] = array(4251,4592);
$config['en_all_7167'] = array(
    4251 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entity Created',
        'm_desc' => '',
        'm_parents' => array(7167,4593,4595),
    ),
    4592 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Entity Links',
        'm_desc' => '',
        'm_parents' => array(7233,7167,4536,4527),
    ),
);

//Intent Mining Stats:
$config['en_ids_7166'] = array(4250,4486,4485);
$config['en_all_7166'] = array(
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intent Created',
        'm_desc' => '',
        'm_parents' => array(7203,7166,4593,4595),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Intent Links',
        'm_desc' => '',
        'm_parents' => array(7203,7166,4535,4527),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus"></i>',
        'm_name' => 'Intent Notes',
        'm_desc' => '',
        'm_parents' => array(7233,7166,4535,4603,4527,4463),
    ),
);

//Action Plan Messages Exchanged:
$config['en_ids_7164'] = array(4268,4278,4279,4287,4299,4460,4547,4548,4549,4550,4551,4552,4553,4554,4555,4556,4557,4570,4577,5967,6563);
$config['en_all_7164'] = array(
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag"></i>',
        'm_name' => 'User Sent Postback',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'User Sent Read',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download"></i>',
        'm_name' => 'User Sent Received',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation"></i>',
        'm_name' => 'User Sent Unknown Message',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-id-badge"></i>',
        'm_name' => 'User Sent Profile Photo',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,6222,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'User Sent Text',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'User Received Text',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle"></i>',
        'm_name' => 'User Sent Location',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'User Received HTML',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'User Sent Access',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4755,4595,4593),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open"></i>',
        'm_name' => 'User Received Email',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4593,4755,4280),
    ),
);

//Link Statuses:
$config['en_ids_6186'] = array(6176,6175,6174,6173);
$config['en_all_6186'] = array(
    6176 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Published',
        'm_desc' => 'live and ready to be shared with users',
        'm_parents' => array(7360,7359,6186),
    ),
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Drafting',
        'm_desc' => 'being mined, to be published soon',
        'm_parents' => array(7364,7360,6186),
    ),
    6174 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'New',
        'm_desc' => 'newly added, pending review',
        'm_parents' => array(7364,7360,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="fal fa-minus-square"></i>',
        'm_name' => 'Removed',
        'm_desc' => 'archived',
        'm_parents' => array(6186),
    ),
);

//User Engagements Stats:
$config['en_ids_7159'] = array(7347,6255,7164);
$config['en_all_7159'] = array(
    7347 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'Intention Set',
        'm_desc' => 'Intentions set by users enabling Mench to deliver relevant intelligence.',
        'm_parents' => array(7159,4527,6219),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-walking"></i>',
        'm_name' => 'User Steps Progressed',
        'm_desc' => 'Key insights or actionable tasks completed by users that gets them closer to their set intentions.',
        'm_parents' => array(7233,7493,7203,7159,4527),
    ),
    7164 => array(
        'm_icon' => '<i class="far fa-comments"></i>',
        'm_name' => 'Messages Exchanged',
        'm_desc' => 'Messages send and received by users that gets them closer to their set intentions.',
        'm_parents' => array(7233,7203,7159,4527,6221),
    ),
);

//Database Connector Fields:
$config['en_ids_6194'] = array(4364,4366,4368,4369,4371,4429,4593,4737,5008,6177,6186,6676);
$config['en_all_6194'] = array(
    4364 => array(
        'm_icon' => '⛏️',
        'm_name' => 'Link Miner Entity',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_links WHERE ln_miner_entity_id=',
        'm_parents' => array(6160,6232,6213,6194,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Link Parent Entity',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_links WHERE ln_parent_entity_id=',
        'm_parents' => array(6160,6232,6213,6194,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Link Parent Intent',
        'm_desc' => '',
        'm_parents' => array(6202,6232,6213,6194,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Link Child Intent',
        'm_desc' => '',
        'm_parents' => array(6202,6232,6213,6194,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Link Parent Link',
        'm_desc' => '',
        'm_parents' => array(4367,6232,6213,6194,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Link Child Entity',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_links WHERE ln_child_entity_id=',
        'm_parents' => array(6160,6232,6213,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fas fa-plug"></i>',
        'm_name' => 'Link Types',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_links WHERE ln_type_entity_id=',
        'm_parents' => array(6160,6232,7304,6213,6194,4527,4341),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h"></i>',
        'm_name' => 'Intent Statuses',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_intents WHERE in_status_entity_id=',
        'm_parents' => array(6160,6232,7302,6194,6213,6201,4527),
    ),
    5008 => array(
        'm_icon' => '<i class="fal fa-tools"></i>',
        'm_name' => 'Intent Verbs',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_intents WHERE in_verb_entity_id=',
        'm_parents' => array(6160,6232,7302,4506,6213,6194,6201),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h"></i>',
        'm_name' => 'Entity Statuses',
        'm_desc' => 'SELECT count(en_id) as totals FROM table_entities WHERE en_status_entity_id=',
        'm_parents' => array(6160,6232,7303,6194,6213,6206,4527),
    ),
    6186 => array(
        'm_icon' => '<i class="fas fa-sliders-h"></i>',
        'm_name' => 'Link Statuses',
        'm_desc' => 'SELECT count(ln_id) as totals FROM table_links WHERE ln_status_entity_id=',
        'm_parents' => array(6160,6232,7304,4527,6194,6213,4341),
    ),
    6676 => array(
        'm_icon' => '<i class="fas fa-chart-network"></i>',
        'm_name' => 'Intent Types',
        'm_desc' => 'SELECT count(in_id) as totals FROM table_intents WHERE in_type_entity_id=',
        'm_parents' => array(6160,6232,7302,6194,6213,4527,6201),
    ),
);

//Mench Community:
$config['en_ids_6827'] = array(1281,1308,3084,4430,4433,6695,7512);
$config['en_all_6827'] = array(
    1281 => array(
        'm_icon' => '<i class="fas fa-user-shield"></i>',
        'm_name' => 'Mench Admins',
        'm_desc' => 'The dedicated team that ensures the continuous operation of the Mench platform, governs its principles and empowers the rest of the community to achieve their full potential',
        'm_parents' => array(6287,6827,4463),
    ),
    1308 => array(
        'm_icon' => '<i class="fas fa-user-hard-hat"></i>',
        'm_name' => 'Mench Miners',
        'm_desc' => 'Those who have completed the intention to become a Mench miner and have passed the assessment that validated their skills and understanding of the mining principles',
        'm_parents' => array(7368,6827,4463,4426),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut"></i>',
        'm_name' => 'Mench Industry Experts',
        'm_desc' => 'Experienced in their respective industry with a track record of advancing their field of knowldge.',
        'm_parents' => array(6827,4463),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-user-graduate"></i>',
        'm_name' => 'Mench Users',
        'm_desc' => 'Users who are pursuing their intentions using Mench',
        'm_parents' => array(7369,6196,6827,4426,4463),
    ),
    4433 => array(
        'm_icon' => '<i class="fas fa-user-ninja"></i>',
        'm_name' => 'Mench Open-Source Developers',
        'm_desc' => 'Those contributing to our open-source code base hosted on GitHub',
        'm_parents' => array(6827,4463,4426),
    ),
    6695 => array(
        'm_icon' => '<i class="fas fa-user-tie"></i>',
        'm_name' => 'Mench Partner Companies',
        'm_desc' => 'Those who use Mench as a recruitment platform to assess their candidates and reach new candidates',
        'm_parents' => array(6827,4426,4463),
    ),
    7512 => array(
        'm_icon' => '<i class="fas fa-user-tie"></i>',
        'm_name' => 'Mench Partner Employees',
        'm_desc' => 'Company staff who manage job postings',
        'm_parents' => array(6827),
    ),
);

//Entity Link Content Requires Text:
$config['en_ids_6805'] = array(2999,3005,3147,3192,4763,4883);
$config['en_all_6805'] = array(
    2999 => array(
        'm_icon' => '<i class="fas fa-microphone"></i>',
        'm_name' => 'Expert Podcasts',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fas fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fas fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fas fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fas fa-bullhorn"></i>',
        'm_name' => 'Expert Channels',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
    4883 => array(
        'm_icon' => '<i class="fas fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
);

//Intent Requires Manual Reply:
$config['en_ids_6794'] = array(6678,6679,6680,6681,6682,6683);
$config['en_all_6794'] = array(
    6678 => array(
        'm_icon' => '<i class="fas fa-image"></i>',
        'm_name' => 'Image Response',
        'm_desc' => '',
        'm_parents' => array(6144,6794,6192),
    ),
    6679 => array(
        'm_icon' => '<i class="fas fa-video"></i>',
        'm_name' => 'Video Response',
        'm_desc' => '',
        'm_parents' => array(6144,6794,6192),
    ),
    6680 => array(
        'm_icon' => '<i class="fas fa-microphone"></i>',
        'm_name' => 'Audio Response',
        'm_desc' => '',
        'm_parents' => array(6144,6794,6192),
    ),
    6681 => array(
        'm_icon' => '<i class="fas fa-file-pdf"></i>',
        'm_name' => 'File Response',
        'm_desc' => '',
        'm_parents' => array(6144,6794,6192),
    ),
    6682 => array(
        'm_icon' => '<i class="fas fa-browser"></i>',
        'm_name' => 'URL Response',
        'm_desc' => '',
        'm_parents' => array(6144,6794,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="fas fa-align-left"></i>',
        'm_name' => 'Text Response',
        'm_desc' => '',
        'm_parents' => array(6144,6794,6192),
    ),
);

//AND Intents:
$config['en_ids_6192'] = array(6677,6683,6682,6679,6680,6678,6681,7297,6914);
$config['en_all_6192'] = array(
    6677 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'Got It',
        'm_desc' => 'Users would complete the intent by simply reviewing its outcome and reading its messages if any. No inputs are required.',
        'm_parents' => array(4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="fas fa-align-left"></i>',
        'm_name' => 'Text Response',
        'm_desc' => 'User must submit a text message to mark the intent as complete.',
        'm_parents' => array(6144,6794,6192),
    ),
    6682 => array(
        'm_icon' => '<i class="fas fa-browser"></i>',
        'm_name' => 'URL Response',
        'm_desc' => 'User must submit a URL to mark the intent as complete.',
        'm_parents' => array(6144,6794,6192),
    ),
    6679 => array(
        'm_icon' => '<i class="fas fa-video"></i>',
        'm_name' => 'Video Response',
        'm_desc' => 'User must send a video to mark the intent as complete.',
        'm_parents' => array(6144,6794,6192),
    ),
    6680 => array(
        'm_icon' => '<i class="fas fa-microphone"></i>',
        'm_name' => 'Audio Response',
        'm_desc' => 'User must send a voice note to mark the intent as complete.',
        'm_parents' => array(6144,6794,6192),
    ),
    6678 => array(
        'm_icon' => '<i class="fas fa-image"></i>',
        'm_name' => 'Image Response',
        'm_desc' => 'User must send an image to mark the intent as complete.',
        'm_parents' => array(6144,6794,6192),
    ),
    6681 => array(
        'm_icon' => '<i class="fas fa-file-pdf"></i>',
        'm_name' => 'File Response',
        'm_desc' => 'User must upload a File to mark the intent as complete.',
        'm_parents' => array(6144,6794,6192),
    ),
    7297 => array(
        'm_icon' => '<i class="fas fa-filter"></i>',
        'm_name' => 'Funnel Manager',
        'm_desc' => 'Users are held at this intent until a miner advances them through each child intent using the funnel manager.',
        'm_parents' => array(6914,4428,3303,3303,7298,6192),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'AND Lock',
        'm_desc' => 'Completed after all children have been completed indirectly.',
        'm_parents' => array(4527,7486,7485,7309,6997,6192),
    ),
);

//Intent Types:
$config['en_ids_6676'] = array(6192,6193);
$config['en_all_6676'] = array(
    6192 => array(
        'm_icon' => '<i class="far fa-sitemap"></i>',
        'm_name' => 'AND',
        'm_desc' => 'AND Intents are completed when ALL their children are complete',
        'm_parents' => array(4527,6676),
    ),
    6193 => array(
        'm_icon' => '<i class="far fa-code-merge"></i>',
        'm_name' => 'OR',
        'm_desc' => 'OR Intents are completed when ANY of their children are complete',
        'm_parents' => array(4527,6676),
    ),
);

//OR Intents:
$config['en_ids_6193'] = array(6684,6685,7231,6907);
$config['en_all_6193'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'Single Answer',
        'm_desc' => 'Students can take their time and choose one of the paths of the OR intent.',
        'm_parents' => array(6914,6157,6193),
    ),
    6685 => array(
        'm_icon' => '<i class="fas fa-stopwatch"></i>',
        'm_name' => 'Timed Answer',
        'm_desc' => 'Student must make a selection within the time limit defines by the estimated intent time before their response chance expires.',
        'm_parents' => array(6914,7366,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'Multiple Answers',
        'm_desc' => 'Allows the user to choose multiple answers from the list of children',
        'm_parents' => array(6914,6157,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'OR Lock',
        'm_desc' => 'Completed after a single child is completed indirectly.',
        'm_parents' => array(7486,7485,7309,6997,6193),
    ),
);

//Platform Glossary:
$config['en_ids_4463'] = array(1281,1308,3000,3084,4430,4433,4485,4488,4535,4536,4595,4755,6138,6196,6199,6205,6695);
$config['en_all_4463'] = array(
    1281 => array(
        'm_icon' => '<i class="fas fa-user-shield"></i>',
        'm_name' => 'Mench Admins',
        'm_desc' => 'Mench Team members who serve the community by mediating and solving issues.',
        'm_parents' => array(6287,6827,4463),
    ),
    1308 => array(
        'm_icon' => '<i class="fas fa-user-hard-hat"></i>',
        'm_name' => 'Mench Miners',
        'm_desc' => 'Everyone on Mench is mining intelligence, but this group of individuals have set their intention to become a Mench miner and have graduated from our training program on how to Mine intelligence using Mench.',
        'm_parents' => array(7368,6827,4463,4426),
    ),
    3000 => array(
        'm_icon' => '<i class="fas fa-star"></i>',
        'm_name' => 'Expert Sources',
        'm_desc' => 'Our mining process is based on existing content produced by industry experts that will be mined from various reference types including videos, articles, books, online courses and more!',
        'm_parents' => array(7303,3463,4506,4527,4463),
    ),
    3084 => array(
        'm_icon' => '<i class="fas fa-user-astronaut"></i>',
        'm_name' => 'Mench Industry Experts',
        'm_desc' => 'People with experience in their respective industry that have shown a consistent commitment to advancing their industry.',
        'm_parents' => array(6827,4463),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-user-graduate"></i>',
        'm_name' => 'Mench Users',
        'm_desc' => 'Users connected to Mench Personal Assistant on Facebook Messenger.',
        'm_parents' => array(7369,6196,6827,4426,4463),
    ),
    4433 => array(
        'm_icon' => '<i class="fas fa-user-ninja"></i>',
        'm_name' => 'Mench Open-Source Developers',
        'm_desc' => 'Developers who are contributing to the Mench open-source project on GitHub: https://github.com/askmench',
        'm_parents' => array(6827,4463,4426),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus"></i>',
        'm_name' => 'Intent Notes',
        'm_desc' => 'Intent notes are various information collected around intentions that enable Mench to operate as a Personal Assistant for students looking to accomplish an intent.',
        'm_parents' => array(7233,7166,4535,4603,4527,4463),
    ),
    4488 => array(
        'm_icon' => '<img src="https://mench.com/img/mench_white.png">',
        'm_name' => 'Mench Platform',
        'm_desc' => 'A web portal and GUI enabling Miners to mine intents, entities and links.',
        'm_parents' => array(2738,4463),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intents',
        'm_desc' => 'Intents define the intention of an entity as defined similar to a SMART goal.',
        'm_parents' => array(7368,4534,4463),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entities',
        'm_desc' => 'Entities represent people, objects and things.',
        'm_parents' => array(7368,4534,4463),
    ),
    4595 => array(
        'm_icon' => '<i class="fas fa-award"></i>',
        'm_name' => 'Link Credits',
        'm_desc' => 'Contribution credits awarded to link creator, also known as the miner.',
        'm_parents' => array(6232,6214,4319,4426,4527,4463,4341),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash"></i>',
        'm_name' => 'Private Links',
        'm_desc' => 'Mench is open-source but most of our student generated content is private and accessible either by the student or Mench\'s core contributors.',
        'm_parents' => array(6771,4463,4426,4527,4757),
    ),
    6138 => array(
        'm_icon' => '🚩',
        'm_name' => 'Action Plan',
        'm_desc' => 'Each student has a collection of Intents that they want to accomplish, known as their Action Plan which is accessible via Facebook Messenger or by login into mench.com',
        'm_parents' => array(7369,4463),
    ),
    6196 => array(
        'm_icon' => '<img src="https://mench.com/img/bp_128.png">',
        'm_name' => 'Mench Personal Assistant',
        'm_desc' => '',
        'm_parents' => array(7372,3320,4463),
    ),
    6199 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Entity Trust Score',
        'm_desc' => 'Our measure of trust to the entity which ranks them among their peers',
        'm_parents' => array(6232,4463,6214,6206),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Links',
        'm_desc' => 'An electronic log book containing a list of transactions and balances typically involving financial accounts.',
        'm_parents' => array(7368,4534,4463),
    ),
    6695 => array(
        'm_icon' => '<i class="fas fa-user-tie"></i>',
        'm_name' => 'Mench Partner Companies',
        'm_desc' => 'Users who can manage the accounts of organizations they belong to.',
        'm_parents' => array(6827,4426,4463),
    ),
);

//Action Plan Completion Recursive Up:
$config['en_ids_6410'] = array(4228,4229,4358,6140,6402);
$config['en_all_6410'] = array(
    4228 => array(
        'm_icon' => '<i class="fas fa-walking"></i>',
        'm_name' => 'Intent Link Regular Step',
        'm_desc' => 'Fixed steps provide the assessment marks needed to determine the outcome of conditional steps.',
        'm_parents' => array(6410,4593,4486,4595),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Intent Link Locked Step',
        'm_desc' => 'The outcome of processing the aggregate steps if a student\'s Action Plan and unlocking a specific intent based on the percentage outcome.',
        'm_parents' => array(6410,6283,4593,4486,4595),
    ),
    4358 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'Completion Marks',
        'm_desc' => 'With each response, users are leaning towards a high or low completion mark which will correlate to two directions of an assessment.',
        'm_parents' => array(6103,6410,6232,6213,4228),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Link Unlock',
        'm_desc' => '',
        'm_parents' => array(4595,6410,4229,4755,4593),
    ),
    6402 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Unlock Score Range',
        'm_desc' => 'Defines the minimum/maximum fixed score a student must get in order to unlock this conditional step',
        'm_parents' => array(6410,4229),
    ),
);

//Intent Notes Deliverable:
$config['en_ids_6345'] = array(4231,6242);
$config['en_all_6345'] = array(
    4231 => array(
        'm_icon' => '<i class="far fa-comment"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '',
        'm_parents' => array(6345,4986,4603,4593,4485,4595),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Message',
        'm_desc' => '',
        'm_parents' => array(5007,6345,4603,4595,4593,4986,4485),
    ),
);

//User Steps Skippable:
$config['en_ids_6274'] = array(4559);
$config['en_all_6274'] = array(
    4559 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'User Step Got It',
        'm_desc' => '',
        'm_parents' => array(4595,6274,6255,4755,6146,4593),
    ),
);

//User Steps Progressed:
$config['en_ids_6255'] = array(4559,6144,6157,6997,7298,7485,7486,7487,7489);
$config['en_all_6255'] = array(
    4559 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'User Step Got It',
        'm_desc' => '',
        'm_parents' => array(4595,6274,6255,4755,6146,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'User Step Requirement Sent',
        'm_desc' => '',
        'm_parents' => array(4595,6255,6244,4755,6146,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle"></i>',
        'm_name' => 'User Step Answered',
        'm_desc' => '',
        'm_parents' => array(4527,4595,6255,6244,6146,4755,4593),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,4229,6255,4595,4593,4755,6146),
    ),
    7298 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Funnel Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,1,6146,6255),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,4595,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,6244,6146,4755,4593,4595,6255),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-calendar-check"></i>',
        'm_name' => 'User Step Answered Timely',
        'm_desc' => '',
        'm_parents' => array(6244,4755,6255,4593,4595,6146),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'User Step Selected',
        'm_desc' => '',
        'm_parents' => array(4755,6255,4593,4595,6146),
    ),
);

//User Steps Double:
$config['en_ids_6244'] = array(6144,6157,7486,7487);
$config['en_all_6244'] = array(
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'User Step Requirement Sent',
        'm_desc' => 'Logged initially when the user starts an intent that has a requirement submission (Text, URL, Video, Image, etc...) and is completed when they submit the requirement.',
        'm_parents' => array(4595,6255,6244,4755,6146,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle"></i>',
        'm_name' => 'User Step Answered',
        'm_desc' => 'Logged initially when the user arrives at a regular OR intent, and completed when they submit their answer.',
        'm_parents' => array(4527,4595,6255,6244,6146,4755,4593),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => 'Logged initially when the user arrives at a locked intent that has no immediate OR parents to mark it as complete and has children, which means the only way through is to complete all its children. Marks as complete when ANY/ALL children are complete dependant on if its a AND/OR locked intent.',
        'm_parents' => array(7494,6244,6146,4755,4593,4595,6255),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-calendar-check"></i>',
        'm_name' => 'User Step Answered Timely',
        'm_desc' => 'Logged initially when the user starts to answer a timed OR intent, and will be published if they are successful at answering it on time. If not, will update link type to User Step Answer Timeout.',
        'm_parents' => array(6244,4755,6255,4593,4595,6146),
    ),
);

//Action Plan Intention Completed:
$config['en_ids_6150'] = array(6154,6155);
$config['en_all_6150'] = array(
    6154 => array(
        'm_icon' => '<i class="far fa-mountain"></i>',
        'm_name' => 'Intent Accomplished',
        'm_desc' => 'You successfully accomplished your intention so you no longer want to receive future updates',
        'm_parents' => array(4595,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'Intent Cancelled',
        'm_desc' => 'You did NOT accomplish the intention and you want to stop all future updates on this intention',
        'm_parents' => array(4595,4506,6150,4593,4755),
    ),
);

//Intent Notes Entity Referencing:
$config['en_ids_4986'] = array(4231,4232,4983,6093,6242);
$config['en_all_4986'] = array(
    4231 => array(
        'm_icon' => '<i class="far fa-comment"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '',
        'm_parents' => array(6345,4986,4603,4593,4485,4595),
    ),
    4232 => array(
        'm_icon' => '<i class="far fa-tint"></i>',
        'm_name' => 'Intent Note Drip Message',
        'm_desc' => '',
        'm_parents' => array(5007,4986,4603,4593,4485,4595),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-bookmark"></i>',
        'm_name' => 'Intent Note Reference',
        'm_desc' => '',
        'm_parents' => array(4986,4985,4595,4593,4485),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comments"></i>',
        'm_name' => 'Intent Note Chatlog',
        'm_desc' => '',
        'm_parents' => array(5007,4595,4593,4986,4485),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Message',
        'm_desc' => '',
        'm_parents' => array(5007,6345,4603,4595,4593,4986,4485),
    ),
);

//My Account Inputs:
$config['en_ids_6225'] = array(6197,3288,3286,4783,3290,3287,3089,3289,6123,4454);
$config['en_all_6225'] = array(
    6197 => array(
        'm_icon' => '<i class="far fa-fingerprint"></i>',
        'm_name' => 'Full Name',
        'm_desc' => 'Your first and last name:',
        'm_parents' => array(6232,6225,6213,6206),
    ),
    3288 => array(
        'm_icon' => '<i class="far fa-envelope"></i>',
        'm_name' => 'Email Address',
        'm_desc' => 'Your email address is also used to login to Mench:',
        'm_parents' => array(6225,4426,4755),
    ),
    3286 => array(
        'm_icon' => '<i class="far fa-key"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => 'The password used to login to Mench:',
        'm_parents' => array(6225,5969,4755),
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
        'm_parents' => array(6225,6122,4603),
    ),
    3089 => array(
        'm_icon' => '<i class="far fa-globe"></i>',
        'm_name' => 'Countries',
        'm_desc' => 'Choose your current country of residence:',
        'm_parents' => array(6204,6225),
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
        'm_parents' => array(6225,6204,4603,4527),
    ),
);

//Intent Statuses:
$config['en_ids_4737'] = array(7351,6185,6184,6183,6182);
$config['en_all_4737'] = array(
    7351 => array(
        'm_icon' => '<i class="fas fa-flag"></i>',
        'm_name' => 'Starting Point',
        'm_desc' => 'can be added as action plan intention',
        'm_parents' => array(7356,7355,4737),
    ),
    6185 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Published',
        'm_desc' => 'live and ready to be shared with users',
        'm_parents' => array(7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Drafting',
        'm_desc' => 'being mined, to be published soon',
        'm_parents' => array(7356,4737),
    ),
    6183 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'New',
        'm_desc' => 'newly added, pending review',
        'm_parents' => array(7356,4737),
    ),
    6182 => array(
        'm_icon' => '<i class="fal fa-minus-square"></i>',
        'm_name' => 'Removed',
        'm_desc' => 'archived',
        'm_parents' => array(4737),
    ),
);

//Entity Statuses:
$config['en_ids_6177'] = array(6181,6180,6179,6178);
$config['en_all_6177'] = array(
    6181 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Published',
        'm_desc' => 'live and ready to be shared with users',
        'm_parents' => array(7358,7357,6177),
    ),
    6180 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Drafting',
        'm_desc' => 'being mined, to be published soon',
        'm_parents' => array(7357,7358,6177),
    ),
    6179 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'New',
        'm_desc' => 'newly added, pending review',
        'm_parents' => array(7358,6177),
    ),
    6178 => array(
        'm_icon' => '<i class="fal fa-minus-square"></i>',
        'm_name' => 'Removed',
        'm_desc' => 'archived',
        'm_parents' => array(6177),
    ),
);

//User Steps Taken:
$config['en_ids_6146'] = array(4559,6143,6144,6157,6997,7298,7485,7486,7487,7488,7489,7492);
$config['en_all_6146'] = array(
    4559 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'Got It',
        'm_desc' => 'Completed when students complete a basic AND intent without any submission requirements',
        'm_parents' => array(4595,6274,6255,4755,6146,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-minus-square"></i>',
        'm_name' => 'Skipped',
        'm_desc' => 'Completed when students skip an intention and all its child intentions from their Action Plan',
        'm_parents' => array(4595,6146,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Requirement Sent',
        'm_desc' => 'Completed when students submit the intent completion requirements (text, URL, video, etc...) set by miners',
        'm_parents' => array(4595,6255,6244,4755,6146,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle"></i>',
        'm_name' => 'Answered',
        'm_desc' => 'Completed after the student answers the question to the OR intent.',
        'm_parents' => array(4527,4595,6255,6244,6146,4755,4593),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Score Unlock',
        'm_desc' => 'When users unlock locked AND or OR intents by scoring within the range of a conditional intent link',
        'm_parents' => array(7494,4229,6255,4595,4593,4755,6146),
    ),
    7298 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Funnel Unlock',
        'm_desc' => 'When users unlock a locked intent by a miner that moved them to that intention',
        'm_parents' => array(7494,1,6146,6255),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Answer Unlock',
        'm_desc' => 'When users unlock locked AND or OR intents by simply answering an open OR question',
        'm_parents' => array(7494,4595,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Children Unlock',
        'm_desc' => 'When users unlock locked AND or OR intents by completing ALL or ANY of their children',
        'm_parents' => array(7494,6244,6146,4755,4593,4595,6255),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-calendar-check"></i>',
        'm_name' => 'Answered Timely',
        'm_desc' => 'When the user answers a question within the defined timeframe',
        'm_parents' => array(6244,4755,6255,4593,4595,6146),
    ),
    7488 => array(
        'm_icon' => '<i class="far fa-calendar-times"></i>',
        'm_name' => 'Answer Timeout',
        'm_desc' => 'User failed to answer the question within the allocated time',
        'm_parents' => array(4755,4593,4595,6146),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Selected',
        'm_desc' => 'User made a selection as part of a multiple-choice answer question',
        'm_parents' => array(4755,6255,4593,4595,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="far fa-times-square"></i>',
        'm_name' => 'Dead End',
        'm_desc' => 'Logged when users arrive at a locked intent that has no public OR parents or no children, which means there is no way to unlock it.',
        'm_parents' => array(4755,4593,4595,6146),
    ),
);

//Social Profiles:
$config['en_ids_6123'] = array(3300,3302,3303);
$config['en_all_6123'] = array(
    3300 => array(
        'm_icon' => '<i class="fab fa-twitter"></i>',
        'm_name' => 'Twitter',
        'm_desc' => '',
        'm_parents' => array(6123,2750,1326,3304),
    ),
    3302 => array(
        'm_icon' => '<i class="fab fa-linkedin"></i>',
        'm_name' => 'LinkedIn',
        'm_desc' => '',
        'm_parents' => array(6123,1326,4763,2750),
    ),
    3303 => array(
        'm_icon' => '<i class="fab fa-github"></i>',
        'm_name' => 'Github',
        'm_desc' => '',
        'm_parents' => array(6123,4763,1326,2750),
    ),
);

//User Sent Manual Messages:
$config['en_ids_4277'] = array(4460,4547,4548,4549,4550,4551,4557);
$config['en_all_4277'] = array(
    4460 => array(
        'm_icon' => '<i class="far fa-check"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => 'When students select a quick reply answer of any kind',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'User Sent Text',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle"></i>',
        'm_name' => 'User Sent Location',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
);

//User Media Exchanged:
$config['en_ids_6102'] = array(4548,4549,4550,4551,4553,4554,4555,4556);
$config['en_all_6102'] = array(
    4548 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
);

//User Received Messages:
$config['en_ids_4280'] = array(4552,4553,4554,4555,4556,4570,5967,6563);
$config['en_all_4280'] = array(
    4552 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'User Received Text',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'User Received HTML',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open"></i>',
        'm_name' => 'User Received Email',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => 'When we dispatch a quick reply to students and are waiting for their answer...',
        'm_parents' => array(7164,4595,4593,4755,4280),
    ),
);

//System Lock:
$config['en_ids_5969'] = array(3286);
$config['en_all_5969'] = array(
    3286 => array(
        'm_icon' => '<i class="far fa-key"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => 'Managed through the Forgot Password section in the Login page',
        'm_parents' => array(6225,5969,4755),
    ),
);

//Link Email Subscriptions:
$config['en_ids_5966'] = array(4246,7504,7505);
$config['en_all_5966'] = array(
    4246 => array(
        'm_icon' => '<i class="far fa-bug"></i>',
        'm_name' => 'Admin Bug Reports',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(4595,4755,5966,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation"></i>',
        'm_name' => 'Admin Review Required',
        'm_desc' => '&var_en_subscriber_ids=1,2',
        'm_parents' => array(5966,4755,4595,4593),
    ),
    7505 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'Company Joined Mench',
        'm_desc' => '&var_en_subscriber_ids=1,2',
        'm_parents' => array(7483,4755,4595,4593,5966),
    ),
);

//Entity Mass Updates:
$config['en_ids_4997'] = array(4998,4999,5000,5001,5003,5865,5943,5981,5982);
$config['en_all_4997'] = array(
    4998 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Name Prefix',
        'm_desc' => 'Adds string to the beginning of all child entities. Make sure to include a space for it to look good',
        'm_parents' => array(4595,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Name Postfix',
        'm_desc' => 'Adds string to the end of all child entities',
        'm_parents' => array(4595,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Name Replace',
        'm_desc' => 'Search for occurance of string in child entity names and if found, updates it with a replacement string',
        'm_parents' => array(4595,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Link Contents',
        'm_desc' => 'Search for occurance of string in child entity link contents and if found, updates it with a replacement string',
        'm_parents' => array(4595,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Status Replace',
        'm_desc' => 'Updates all child entity statuses that match the initial entity status condition',
        'm_parents' => array(4595,4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Link Status',
        'm_desc' => 'Updates all child entity link statuses that match the initial link status condition',
        'm_parents' => array(4595,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Icon Replace',
        'm_desc' => 'Updates all child entity icons with string which needs to be a valid icon',
        'm_parents' => array(4595,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Parent Add',
        'm_desc' => 'If not already done so, will add searched entity as the parent of all child entities',
        'm_parents' => array(4595,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Parent Remove',
        'm_desc' => 'If already added as the parent, this will remove searched entity as the parent of all child entities',
        'm_parents' => array(4595,4593,4997),
    ),
);

//Modification Lock:
$config['en_ids_4426'] = array(1308,3288,4426,4430,4433,4595,4755,4997,5969,6695);
$config['en_all_4426'] = array(
    1308 => array(
        'm_icon' => '<i class="fas fa-user-hard-hat"></i>',
        'm_name' => 'Mench Miners',
        'm_desc' => 'Requires admin review and approval',
        'm_parents' => array(7368,6827,4463,4426),
    ),
    3288 => array(
        'm_icon' => '<i class="far fa-envelope"></i>',
        'm_name' => 'Email Address',
        'm_desc' => '',
        'm_parents' => array(6225,4426,4755),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Modification Lock',
        'm_desc' => '',
        'm_parents' => array(3303,6771,4426,4527,4757,4428),
    ),
    4430 => array(
        'm_icon' => '<i class="fas fa-user-graduate"></i>',
        'm_name' => 'Mench Users',
        'm_desc' => '',
        'm_parents' => array(7369,6196,6827,4426,4463),
    ),
    4433 => array(
        'm_icon' => '<i class="fas fa-user-ninja"></i>',
        'm_name' => 'Mench Open-Source Developers',
        'm_desc' => 'Requires admin review and approval',
        'm_parents' => array(6827,4463,4426),
    ),
    4595 => array(
        'm_icon' => '<i class="fas fa-award"></i>',
        'm_name' => 'Link Credits',
        'm_desc' => '',
        'm_parents' => array(6232,6214,4319,4426,4527,4463,4341),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash"></i>',
        'm_name' => 'Private Links',
        'm_desc' => '',
        'm_parents' => array(6771,4463,4426,4527,4757),
    ),
    4997 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Updates',
        'm_desc' => '',
        'm_parents' => array(5007,4758,4506,4426,4527),
    ),
    5969 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'System Lock',
        'm_desc' => '',
        'm_parents' => array(3303,6771,4426,4527,4757,4428),
    ),
    6695 => array(
        'm_icon' => '<i class="fas fa-user-tie"></i>',
        'm_name' => 'Mench Partner Companies',
        'm_desc' => '',
        'm_parents' => array(6827,4426,4463),
    ),
);

//Private Links:
$config['en_ids_4755'] = array(3286,3288,4235,4242,4246,4263,4266,4267,4268,4269,4275,4278,4279,4282,4283,4287,4299,4460,4547,4548,4549,4550,4551,4552,4553,4554,4555,4556,4557,4559,4570,4577,4783,5967,6132,6140,6143,6144,6149,6154,6155,6157,6224,6415,6556,6559,6560,6563,6578,6969,6997,7484,7485,7486,7487,7488,7489,7492,7495,7504,7505,7506,7508,7509,7511);
$config['en_all_4755'] = array(
    3286 => array(
        'm_icon' => '<i class="far fa-key"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => '',
        'm_parents' => array(6225,5969,4755),
    ),
    3288 => array(
        'm_icon' => '<i class="far fa-envelope"></i>',
        'm_name' => 'Email Address',
        'm_desc' => '',
        'm_parents' => array(6225,4426,4755),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'User Intent Set',
        'm_desc' => '',
        'm_parents' => array(7347,4595,4755,4593),
    ),
    4242 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'Link Updated',
        'm_desc' => '',
        'm_parents' => array(4755,4593,4595),
    ),
    4246 => array(
        'm_icon' => '<i class="far fa-bug"></i>',
        'm_name' => 'Admin Bug Reports',
        'm_desc' => '',
        'm_parents' => array(4595,4755,5966,4593),
    ),
    4263 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'Entity Updated',
        'm_desc' => '',
        'm_parents' => array(4755,4593,4595),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag"></i>',
        'm_name' => 'User Sent Postback',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'User Login',
        'm_desc' => '',
        'm_parents' => array(4755,4595,4593),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Intent',
        'm_desc' => '',
        'm_parents' => array(4595,6554,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'User Sent Read',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download"></i>',
        'm_name' => 'User Sent Received',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'User Account Opened',
        'm_desc' => '',
        'm_parents' => array(4595,4755,6222,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'User Intent Listed',
        'm_desc' => '',
        'm_parents' => array(4595,6153,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation"></i>',
        'm_name' => 'User Sent Unknown Message',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-id-badge"></i>',
        'm_name' => 'User Sent Profile Photo',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,6222,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'User Sent Text',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'User Received Text',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle"></i>',
        'm_name' => 'User Sent Location',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'User Step Got It',
        'm_desc' => '',
        'm_parents' => array(4595,6274,6255,4755,6146,4593),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'User Received HTML',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'User Sent Access',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4755,4595,4593),
    ),
    4783 => array(
        'm_icon' => '<i class="far fa-phone"></i>',
        'm_name' => 'Phone Number',
        'm_desc' => '',
        'm_parents' => array(6225,4755,4319),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open"></i>',
        'm_name' => 'User Received Email',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90"></i>',
        'm_name' => 'User Intent Prioritized',
        'm_desc' => '',
        'm_parents' => array(4595,6153,4506,4755,4593),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Link Unlock',
        'm_desc' => '',
        'm_parents' => array(4595,6410,4229,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-minus-square"></i>',
        'm_name' => 'User Step Skipped',
        'm_desc' => '',
        'm_parents' => array(4595,6146,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'User Step Requirement Sent',
        'm_desc' => '',
        'm_parents' => array(4595,6255,6244,4755,6146,4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus"></i>',
        'm_name' => 'User Intent Considered',
        'm_desc' => '',
        'm_parents' => array(4595,6153,4755,4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain"></i>',
        'm_name' => 'User Intent Accomplished',
        'm_desc' => '',
        'm_parents' => array(4595,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'User Intent Cancelled',
        'm_desc' => '',
        'm_parents' => array(4595,4506,6150,4593,4755),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle"></i>',
        'm_name' => 'User Step Answered',
        'm_desc' => '',
        'm_parents' => array(4527,4595,6255,6244,6146,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'User Account Updated',
        'm_desc' => '',
        'm_parents' => array(4595,4755,6222,4593),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function"></i>',
        'm_name' => 'User Progress Reset',
        'm_desc' => '',
        'm_parents' => array(4595,4755,6418,4593,6414),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Next',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4593,4755,4280),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6969 => array(
        'm_icon' => '<i class="fas fa-megaphone"></i>',
        'm_name' => 'User Intent Featured',
        'm_desc' => '',
        'm_parents' => array(4595,4593,4755,6153),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,4229,6255,4595,4593,4755,6146),
    ),
    7484 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'Company Referred User',
        'm_desc' => '',
        'm_parents' => array(4755,4595,4593,7483),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,4595,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,6244,6146,4755,4593,4595,6255),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-calendar-check"></i>',
        'm_name' => 'User Step Answered Timely',
        'm_desc' => '',
        'm_parents' => array(6244,4755,6255,4593,4595,6146),
    ),
    7488 => array(
        'm_icon' => '<i class="far fa-calendar-times"></i>',
        'm_name' => 'User Step Answer Timeout',
        'm_desc' => '',
        'm_parents' => array(4755,4593,4595,6146),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'User Step Selected',
        'm_desc' => '',
        'm_parents' => array(4755,6255,4593,4595,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="far fa-times-square"></i>',
        'm_name' => 'User Step Dead End',
        'm_desc' => '',
        'm_parents' => array(4755,4593,4595,6146),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'User Intent Recommended',
        'm_desc' => '',
        'm_parents' => array(4755,4595,4593,7347),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation"></i>',
        'm_name' => 'Admin Review Required',
        'm_desc' => '',
        'm_parents' => array(5966,4755,4595,4593),
    ),
    7505 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'Company Joined Mench',
        'm_desc' => '',
        'm_parents' => array(7483,4755,4595,4593,5966),
    ),
    7506 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Company Intent created',
        'm_desc' => '',
        'm_parents' => array(4755,4595,4593,7483),
    ),
    7508 => array(
        'm_icon' => '<i class="far fa-comment"></i>',
        'm_name' => 'Company Message Created',
        'm_desc' => '',
        'm_parents' => array(4755,4593,4595,7483),
    ),
    7509 => array(
        'm_icon' => '<i class="far fa-comment-edit"></i>',
        'm_name' => 'Company Message Modified',
        'm_desc' => '',
        'm_parents' => array(4755,4595,4593,7483),
    ),
    7511 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'Company Intent Set',
        'm_desc' => '',
        'm_parents' => array(4755,4595,4593,7483,7347),
    ),
);

//Link Credits:
$config['en_ids_4595'] = array(4228,4229,4230,4231,4232,4235,4242,4246,4250,4251,4255,4256,4257,4258,4259,4260,4261,4263,4264,4266,4267,4268,4269,4275,4278,4279,4282,4283,4287,4299,4318,4319,4460,4547,4548,4549,4550,4551,4552,4553,4554,4555,4556,4557,4559,4570,4577,4601,4983,4993,4994,4998,4999,5000,5001,5003,5007,5865,5943,5967,5981,5982,6093,6132,6140,6143,6144,6149,6154,6155,6157,6224,6226,6242,6415,6556,6559,6560,6563,6578,6969,6997,7484,7485,7486,7487,7488,7489,7492,7495,7504,7505,7506,7508,7509,7511);
$config['en_all_4595'] = array(
    4228 => array(
        'm_icon' => '<i class="fas fa-walking"></i>',
        'm_name' => 'Intent Link Regular Step',
        'm_desc' => '2000',
        'm_parents' => array(6410,4593,4486,4595),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Intent Link Locked Step',
        'm_desc' => '2000',
        'm_parents' => array(6410,6283,4593,4486,4595),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-level-up rotate90"></i>',
        'm_name' => 'Entity Link Raw',
        'm_desc' => '25',
        'm_parents' => array(4593,4592,4595),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '2000',
        'm_parents' => array(6345,4986,4603,4593,4485,4595),
    ),
    4232 => array(
        'm_icon' => '<i class="far fa-tint"></i>',
        'm_name' => 'Intent Note Drip Message',
        'm_desc' => '2000',
        'm_parents' => array(5007,4986,4603,4593,4485,4595),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'User Intent Set',
        'm_desc' => '50',
        'm_parents' => array(7347,4595,4755,4593),
    ),
    4242 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'Link Updated',
        'm_desc' => '5',
        'm_parents' => array(4755,4593,4595),
    ),
    4246 => array(
        'm_icon' => '<i class="far fa-bug"></i>',
        'm_name' => 'Admin Bug Reports',
        'm_desc' => '500',
        'm_parents' => array(4595,4755,5966,4593),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intent Created',
        'm_desc' => '10000',
        'm_parents' => array(7203,7166,4593,4595),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entity Created',
        'm_desc' => '200',
        'm_parents' => array(7167,4593,4595),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'Entity Link Text',
        'm_desc' => '100',
        'm_parents' => array(4593,4592,4595),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser"></i>',
        'm_name' => 'Entity Link URL',
        'm_desc' => '100',
        'm_parents' => array(4593,4592,4537,4595),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle"></i>',
        'm_name' => 'Entity Link Embed Player',
        'm_desc' => '200',
        'm_parents' => array(4593,4592,4537,4506,4595),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'Entity Link Video',
        'm_desc' => '200',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'Entity Link Audio',
        'm_desc' => '200',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'Entity Link Image',
        'm_desc' => '200',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'Entity Link File',
        'm_desc' => '200',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4263 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'Entity Updated',
        'm_desc' => '50',
        'm_parents' => array(4755,4593,4595),
    ),
    4264 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'Intent Updated',
        'm_desc' => '1000',
        'm_parents' => array(4593,4595),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Opt-in',
        'm_desc' => '50',
        'm_parents' => array(4595,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Referral',
        'm_desc' => '50',
        'm_parents' => array(4595,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag"></i>',
        'm_name' => 'User Sent Postback',
        'm_desc' => '50',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'User Login',
        'm_desc' => '10',
        'm_parents' => array(4755,4595,4593),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Intent',
        'm_desc' => '5',
        'm_parents' => array(4595,6554,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'User Sent Read',
        'm_desc' => '1',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download"></i>',
        'm_name' => 'User Sent Received',
        'm_desc' => '1',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'User Account Opened',
        'm_desc' => '1',
        'm_parents' => array(4595,4755,6222,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'User Intent Listed',
        'm_desc' => '2',
        'm_parents' => array(4595,6153,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation"></i>',
        'm_name' => 'User Sent Unknown Message',
        'm_desc' => '10',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-id-badge"></i>',
        'm_name' => 'User Sent Profile Photo',
        'm_desc' => '25',
        'm_parents' => array(7307,7164,4595,6222,4755,4593),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock"></i>',
        'm_name' => 'Entity Link Time',
        'm_desc' => '100',
        'm_parents' => array(4593,4592,4595),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down"></i>',
        'm_name' => 'Entity Link Integer',
        'm_desc' => '50',
        'm_parents' => array(4593,4592,4595),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => '5',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'User Sent Text',
        'm_desc' => '10',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '75',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '50',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '50',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '50',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'User Received Text',
        'm_desc' => '2',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '5',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '4',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '3',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '3',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle"></i>',
        'm_name' => 'User Sent Location',
        'm_desc' => '50',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'User Step Got It',
        'm_desc' => '5',
        'm_parents' => array(4595,6274,6255,4755,6146,4593),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'User Received HTML',
        'm_desc' => '2',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'User Sent Access',
        'm_desc' => '50',
        'm_parents' => array(7307,7164,4755,4595,4593),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-bolt"></i>',
        'm_name' => 'Intent Note Trigger',
        'm_desc' => '500',
        'm_parents' => array(4593,4595,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-bookmark"></i>',
        'm_name' => 'Intent Note Reference',
        'm_desc' => '750',
        'm_parents' => array(4986,4985,4595,4593,4485),
    ),
    4993 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Miner Viewed Intent',
        'm_desc' => '1',
        'm_parents' => array(4595,4593),
    ),
    4994 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Miner Viewed Entity',
        'm_desc' => '1',
        'm_parents' => array(4595,4593),
    ),
    4998 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Name Prefix',
        'm_desc' => '50',
        'm_parents' => array(4595,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Name Postfix',
        'm_desc' => '50',
        'm_parents' => array(4595,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Name Replace',
        'm_desc' => '50',
        'm_parents' => array(4595,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Link Contents',
        'm_desc' => '50',
        'm_parents' => array(4595,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Status Replace',
        'm_desc' => '50',
        'm_parents' => array(4595,4593,4997),
    ),
    5007 => array(
        'm_icon' => '<i class="fas fa-expand-arrows"></i>',
        'm_name' => 'Toggle Advance Mode',
        'm_desc' => '1',
        'm_parents' => array(7368,4527,4595,4757,4593),
    ),
    5865 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Link Status',
        'm_desc' => '50',
        'm_parents' => array(4595,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Icon Replace',
        'm_desc' => '50',
        'm_parents' => array(4595,4593,4997),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open"></i>',
        'm_name' => 'User Received Email',
        'm_desc' => '5',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    5981 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Parent Add',
        'm_desc' => '50',
        'm_parents' => array(4595,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Parent Remove',
        'm_desc' => '50',
        'm_parents' => array(4595,4593,4997),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comments"></i>',
        'm_name' => 'Intent Note Chatlog',
        'm_desc' => '500',
        'm_parents' => array(5007,4595,4593,4986,4485),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90"></i>',
        'm_name' => 'User Intent Prioritized',
        'm_desc' => '25',
        'm_parents' => array(4595,6153,4506,4755,4593),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Link Unlock',
        'm_desc' => '25',
        'm_parents' => array(4595,6410,4229,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-minus-square"></i>',
        'm_name' => 'User Step Skipped',
        'm_desc' => '1',
        'm_parents' => array(4595,6146,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'User Step Requirement Sent',
        'm_desc' => '50',
        'm_parents' => array(4595,6255,6244,4755,6146,4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus"></i>',
        'm_name' => 'User Intent Considered',
        'm_desc' => '5',
        'm_parents' => array(4595,6153,4755,4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain"></i>',
        'm_name' => 'User Intent Accomplished',
        'm_desc' => '10',
        'm_parents' => array(4595,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'User Intent Cancelled',
        'm_desc' => '10',
        'm_parents' => array(4595,4506,6150,4593,4755),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle"></i>',
        'm_name' => 'User Step Answered',
        'm_desc' => '5',
        'm_parents' => array(4527,4595,6255,6244,6146,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'User Account Updated',
        'm_desc' => '25',
        'm_parents' => array(4595,4755,6222,4593),
    ),
    6226 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'Intent Mass Updates',
        'm_desc' => '500',
        'm_parents' => array(4595,4593),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Message',
        'm_desc' => '2000',
        'm_parents' => array(5007,6345,4603,4595,4593,4986,4485),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function"></i>',
        'm_name' => 'User Progress Reset',
        'm_desc' => '5',
        'm_parents' => array(4595,4755,6418,4593,6414),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Stats',
        'm_desc' => '5',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Next',
        'm_desc' => '5',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Skip',
        'm_desc' => '5',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => '4',
        'm_parents' => array(7164,4595,4593,4755,4280),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Stop',
        'm_desc' => '5',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6969 => array(
        'm_icon' => '<i class="fas fa-megaphone"></i>',
        'm_name' => 'User Intent Featured',
        'm_desc' => '2',
        'm_parents' => array(4595,4593,4755,6153),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '10',
        'm_parents' => array(7494,4229,6255,4595,4593,4755,6146),
    ),
    7484 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'Company Referred User',
        'm_desc' => '2000',
        'm_parents' => array(4755,4595,4593,7483),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '10',
        'm_parents' => array(7494,4595,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '10',
        'm_parents' => array(7494,6244,6146,4755,4593,4595,6255),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-calendar-check"></i>',
        'm_name' => 'User Step Answered Timely',
        'm_desc' => '20',
        'm_parents' => array(6244,4755,6255,4593,4595,6146),
    ),
    7488 => array(
        'm_icon' => '<i class="far fa-calendar-times"></i>',
        'm_name' => 'User Step Answer Timeout',
        'm_desc' => '1',
        'm_parents' => array(4755,4593,4595,6146),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'User Step Selected',
        'm_desc' => '5',
        'm_parents' => array(4755,6255,4593,4595,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="far fa-times-square"></i>',
        'm_name' => 'User Step Dead End',
        'm_desc' => '1',
        'm_parents' => array(4755,4593,4595,6146),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'User Intent Recommended',
        'm_desc' => '10',
        'm_parents' => array(4755,4595,4593,7347),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation"></i>',
        'm_name' => 'Admin Review Required',
        'm_desc' => '100',
        'm_parents' => array(5966,4755,4595,4593),
    ),
    7505 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'Company Joined Mench',
        'm_desc' => '10000',
        'm_parents' => array(7483,4755,4595,4593,5966),
    ),
    7506 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Company Intent created',
        'm_desc' => '250',
        'm_parents' => array(4755,4595,4593,7483),
    ),
    7508 => array(
        'm_icon' => '<i class="far fa-comment"></i>',
        'm_name' => 'Company Message Created',
        'm_desc' => '50',
        'm_parents' => array(4755,4593,4595,7483),
    ),
    7509 => array(
        'm_icon' => '<i class="far fa-comment-edit"></i>',
        'm_name' => 'Company Message Modified',
        'm_desc' => '25',
        'm_parents' => array(4755,4595,4593,7483),
    ),
    7511 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'Company Intent Set',
        'm_desc' => '100',
        'm_parents' => array(4755,4595,4593,7483,7347),
    ),
);

//User Account Types:
$config['en_ids_4600'] = array(1278,2750);
$config['en_all_4600'] = array(
    1278 => array(
        'm_icon' => '👪',
        'm_name' => 'People',
        'm_desc' => '',
        'm_parents' => array(4600),
    ),
    2750 => array(
        'm_icon' => '<i class="fas fa-user-tie"></i>',
        'm_name' => 'Companies',
        'm_desc' => '',
        'm_parents' => array(3463,4600),
    ),
);

//Link Types:
$config['en_ids_4593'] = array(4228,4229,4230,4231,4232,4235,4242,4246,4250,4251,4255,4256,4257,4258,4259,4260,4261,4263,4264,4266,4267,4268,4269,4275,4278,4279,4282,4283,4287,4299,4318,4319,4460,4547,4548,4549,4550,4551,4552,4553,4554,4555,4556,4557,4559,4570,4577,4601,4983,4993,4994,4998,4999,5000,5001,5003,5007,5865,5943,5967,5981,5982,6093,6132,6140,6143,6144,6149,6154,6155,6157,6224,6226,6242,6415,6556,6559,6560,6563,6578,6969,6997,7484,7485,7486,7487,7488,7489,7492,7495,7504,7505,7506,7508,7509,7511);
$config['en_all_4593'] = array(
    4228 => array(
        'm_icon' => '<i class="fas fa-walking"></i>',
        'm_name' => 'Intent Link Regular Step',
        'm_desc' => '',
        'm_parents' => array(6410,4593,4486,4595),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Intent Link Locked Step',
        'm_desc' => '',
        'm_parents' => array(6410,6283,4593,4486,4595),
    ),
    4230 => array(
        'm_icon' => '<i class="far fa-level-up rotate90"></i>',
        'm_name' => 'Entity Link Raw',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4595),
    ),
    4231 => array(
        'm_icon' => '<i class="far fa-comment"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '',
        'm_parents' => array(6345,4986,4603,4593,4485,4595),
    ),
    4232 => array(
        'm_icon' => '<i class="far fa-tint"></i>',
        'm_name' => 'Intent Note Drip Message',
        'm_desc' => '',
        'm_parents' => array(5007,4986,4603,4593,4485,4595),
    ),
    4235 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'User Intent Set',
        'm_desc' => 'Intentions set by users which will be completed by taking steps using the Action Plan',
        'm_parents' => array(7347,4595,4755,4593),
    ),
    4242 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'Link Updated',
        'm_desc' => 'Logged for each link column that is updated consciously by the user',
        'm_parents' => array(4755,4593,4595),
    ),
    4246 => array(
        'm_icon' => '<i class="far fa-bug"></i>',
        'm_name' => 'Admin Bug Reports',
        'm_desc' => '',
        'm_parents' => array(4595,4755,5966,4593),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intent Created',
        'm_desc' => '',
        'm_parents' => array(7203,7166,4593,4595),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entity Created',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(7167,4593,4595),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'Entity Link Text',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4595),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser"></i>',
        'm_name' => 'Entity Link URL',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4537,4595),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle"></i>',
        'm_name' => 'Entity Link Embed Player',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4537,4506,4595),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'Entity Link Video',
        'm_desc' => '',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'Entity Link Audio',
        'm_desc' => '',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'Entity Link Image',
        'm_desc' => '',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'Entity Link File',
        'm_desc' => '',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4263 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'Entity Updated',
        'm_desc' => 'When a Miner modified an entity attribute like Name, Icon or Status.',
        'm_parents' => array(4755,4593,4595),
    ),
    4264 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'Intent Updated',
        'm_desc' => 'When an intent field is updated',
        'm_parents' => array(4593,4595),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="far fa-user-tag"></i>',
        'm_name' => 'User Sent Postback',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'User Login',
        'm_desc' => '',
        'm_parents' => array(4755,4595,4593),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Intent',
        'm_desc' => 'When students invokes the [I want to] command and search for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(4595,6554,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'User Sent Read',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="far fa-cloud-download"></i>',
        'm_name' => 'User Sent Received',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'User Account Opened',
        'm_desc' => '',
        'm_parents' => array(4595,4755,6222,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="far fa-eye"></i>',
        'm_name' => 'User Intent Listed',
        'm_desc' => 'Once a student has added an Intention to their Action Plan, this link will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(4595,6153,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="far fa-comment-exclamation"></i>',
        'm_name' => 'User Sent Unknown Message',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,4755,4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-id-badge"></i>',
        'm_name' => 'User Sent Profile Photo',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4595,6222,4755,4593),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock"></i>',
        'm_name' => 'Entity Link Time',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4595),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down"></i>',
        'm_name' => 'Entity Link Integer',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4595),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-check"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4547 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'User Sent Text',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4548 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Sent Video',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4549 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Sent Audio',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4550 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Sent Image',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4551 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Sent File',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4277),
    ),
    4552 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'User Received Text',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'User Received Video',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4554 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'User Received Audio',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'User Received Image',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'User Received File',
        'm_desc' => '',
        'm_parents' => array(7164,4595,6102,4755,4593,4280),
    ),
    4557 => array(
        'm_icon' => '<i class="far fa-location-circle"></i>',
        'm_name' => 'User Sent Location',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4277),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-thumbs-up"></i>',
        'm_name' => 'User Step Got It',
        'm_desc' => 'Logged when a student receives the messages of an AND intent that does not have any completion requirements.',
        'm_parents' => array(4595,6274,6255,4755,6146,4593),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'User Received HTML',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    4577 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'User Sent Access',
        'm_desc' => '',
        'm_parents' => array(7307,7164,4755,4595,4593),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-bolt"></i>',
        'm_name' => 'Intent Note Trigger',
        'm_desc' => '',
        'm_parents' => array(4593,4595,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-bookmark"></i>',
        'm_name' => 'Intent Note Reference',
        'm_desc' => 'References track intent correlations referenced within expert sources, and represent a core building block of intelligence. References are among the most precious transaction types because they indicate that IF you do A, you will likely accomplish B. As miners mine content from more experts, certain intent correlations will receive more references than others, thus gaining more credibility.',
        'm_parents' => array(4986,4985,4595,4593,4485),
    ),
    4993 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Miner Viewed Intent',
        'm_desc' => '',
        'm_parents' => array(4595,4593),
    ),
    4994 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Miner Viewed Entity',
        'm_desc' => '',
        'm_parents' => array(4595,4593),
    ),
    4998 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Name Prefix',
        'm_desc' => '',
        'm_parents' => array(4595,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Name Postfix',
        'm_desc' => '',
        'm_parents' => array(4595,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Name Replace',
        'm_desc' => '',
        'm_parents' => array(4595,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Link Contents',
        'm_desc' => '',
        'm_parents' => array(4595,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Status Replace',
        'm_desc' => '',
        'm_parents' => array(4595,4593,4997),
    ),
    5007 => array(
        'm_icon' => '<i class="fas fa-expand-arrows"></i>',
        'm_name' => 'Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(7368,4527,4595,4757,4593),
    ),
    5865 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Link Status',
        'm_desc' => '',
        'm_parents' => array(4595,4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Icon Replace',
        'm_desc' => '',
        'm_parents' => array(4595,4593,4997),
    ),
    5967 => array(
        'm_icon' => '<i class="far fa-envelope-open"></i>',
        'm_name' => 'User Received Email',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4755,4593,4280),
    ),
    5981 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Parent Add',
        'm_desc' => '',
        'm_parents' => array(4595,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entities Parent Remove',
        'm_desc' => '',
        'm_parents' => array(4595,4593,4997),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comments"></i>',
        'm_name' => 'Intent Note Chatlog',
        'm_desc' => '',
        'm_parents' => array(5007,4595,4593,4986,4485),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90"></i>',
        'm_name' => 'User Intent Prioritized',
        'm_desc' => 'Student re-prioritized their top-level intentions to focus on intentions that currently matter the most.',
        'm_parents' => array(4595,6153,4506,4755,4593),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Link Unlock',
        'm_desc' => 'Created when the student responses to OR branches meets the right % points to unlock the pathway to a conditional intent link.',
        'm_parents' => array(4595,6410,4229,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-minus-square"></i>',
        'm_name' => 'User Step Skipped',
        'm_desc' => 'Logged every time a student consciously skips an intent and it\'s recursive children.',
        'm_parents' => array(4595,6146,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'User Step Requirement Sent',
        'm_desc' => 'Logged when a student submits the requirements (text, video, etc...) of an AND intent which could not be completed by simply receiving messages.',
        'm_parents' => array(4595,6255,6244,4755,6146,4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus"></i>',
        'm_name' => 'User Intent Considered',
        'm_desc' => 'When a student chooses to review a given intention from the intentions they have searched or have been recommended after selecting GET STARTED from a mench.com intent landing page.',
        'm_parents' => array(4595,6153,4755,4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-mountain"></i>',
        'm_name' => 'User Intent Accomplished',
        'm_desc' => 'Student accomplished their intention 🎉🎉🎉',
        'm_parents' => array(4595,4506,6150,4755,4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'User Intent Cancelled',
        'm_desc' => 'Student prematurely removed an intention from their Action Plan without accomplishing it.',
        'm_parents' => array(4595,4506,6150,4593,4755),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle"></i>',
        'm_name' => 'User Step Answered',
        'm_desc' => '',
        'm_parents' => array(4527,4595,6255,6244,6146,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'User Account Updated',
        'm_desc' => '',
        'm_parents' => array(4595,4755,6222,4593),
    ),
    6226 => array(
        'm_icon' => '<i class="far fa-sync"></i>',
        'm_name' => 'Intent Mass Updates',
        'm_desc' => ' When all intents within a recursive tree are updated at once.',
        'm_parents' => array(4595,4593),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Message',
        'm_desc' => '',
        'm_parents' => array(5007,6345,4603,4595,4593,4986,4485),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function"></i>',
        'm_name' => 'User Progress Reset',
        'm_desc' => 'Removes certain links types as defined by its children from a Student\'s Action Plan. Currently only available for Miners.',
        'm_parents' => array(4595,4755,6418,4593,6414),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Next',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-check"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(7164,4595,4593,4755,4280),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(4595,4755,4593,6554),
    ),
    6969 => array(
        'm_icon' => '<i class="fas fa-megaphone"></i>',
        'm_name' => 'User Intent Featured',
        'm_desc' => 'Logged every time an intention is recommended to a user by Mench',
        'm_parents' => array(4595,4593,4755,6153),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Score Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,4229,6255,4595,4593,4755,6146),
    ),
    7484 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'Company Referred User',
        'm_desc' => 'When a company refers a new user to Mench using their intent referral URL',
        'm_parents' => array(4755,4595,4593,7483),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Answer Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,4595,4593,4755,6146,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'User Step Children Unlock',
        'm_desc' => '',
        'm_parents' => array(7494,6244,6146,4755,4593,4595,6255),
    ),
    7487 => array(
        'm_icon' => '<i class="fas fa-calendar-check"></i>',
        'm_name' => 'User Step Answered Timely',
        'm_desc' => '',
        'm_parents' => array(6244,4755,6255,4593,4595,6146),
    ),
    7488 => array(
        'm_icon' => '<i class="far fa-calendar-times"></i>',
        'm_name' => 'User Step Answer Timeout',
        'm_desc' => '',
        'm_parents' => array(4755,4593,4595,6146),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'User Step Selected',
        'm_desc' => '',
        'm_parents' => array(4755,6255,4593,4595,6146),
    ),
    7492 => array(
        'm_icon' => '<i class="far fa-times-square"></i>',
        'm_name' => 'User Step Dead End',
        'm_desc' => '',
        'm_parents' => array(4755,4593,4595,6146),
    ),
    7495 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'User Intent Recommended',
        'm_desc' => 'Intention recommended by Mench and added to Action Plan to enable the user to complete their intention',
        'm_parents' => array(4755,4595,4593,7347),
    ),
    7504 => array(
        'm_icon' => '<i class="far fa-comment-exclamation"></i>',
        'm_name' => 'Admin Review Required',
        'm_desc' => 'Certain links that match an unknown behavior would require an admin to review and ensure it\'s all good',
        'm_parents' => array(5966,4755,4595,4593),
    ),
    7505 => array(
        'm_icon' => '<i class="far fa-user-plus"></i>',
        'm_name' => 'Company Joined Mench',
        'm_desc' => 'When a new company joins the Mench platform to automate their recruiting process',
        'm_parents' => array(7483,4755,4595,4593,5966),
    ),
    7506 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Company Intent created',
        'm_desc' => 'An intent created by the company team',
        'm_parents' => array(4755,4595,4593,7483),
    ),
    7508 => array(
        'm_icon' => '<i class="far fa-comment"></i>',
        'm_name' => 'Company Message Created',
        'm_desc' => '',
        'm_parents' => array(4755,4593,4595,7483),
    ),
    7509 => array(
        'm_icon' => '<i class="far fa-comment-edit"></i>',
        'm_name' => 'Company Message Modified',
        'm_desc' => '',
        'm_parents' => array(4755,4595,4593,7483),
    ),
    7511 => array(
        'm_icon' => '<i class="far fa-bullseye-arrow"></i>',
        'm_name' => 'Company Intent Set',
        'm_desc' => '',
        'm_parents' => array(4755,4595,4593,7483,7347),
    ),
);

//Entity Links:
$config['en_ids_4592'] = array(4230,4255,4256,4257,4258,4259,4260,4261,4318,4319);
$config['en_all_4592'] = array(
    4230 => array(
        'm_icon' => '<i class="far fa-level-up rotate90"></i>',
        'm_name' => 'Raw',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4595),
    ),
    4255 => array(
        'm_icon' => '<i class="far fa-align-left"></i>',
        'm_name' => 'Text',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4595),
    ),
    4256 => array(
        'm_icon' => '<i class="far fa-browser"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4537,4595),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle"></i>',
        'm_name' => 'Embed Player',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4537,4506,4595),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => '',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => '',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => '',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4318 => array(
        'm_icon' => '<i class="far fa-clock"></i>',
        'm_name' => 'Time',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4595),
    ),
    4319 => array(
        'm_icon' => '<i class="far fa-sort-numeric-down"></i>',
        'm_name' => 'Integer',
        'm_desc' => '',
        'm_parents' => array(4593,4592,4595),
    ),
);

//Subscription Settings:
$config['en_ids_4454'] = array(4456,4457,4458,4455);
$config['en_all_4454'] = array(
    4456 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Regular Notifications',
        'm_desc' => 'User is connected and will be notified by sound & vibration for new Mench messages',
        'm_parents' => array(4454),
    ),
    4457 => array(
        'm_icon' => '<i class="fal fa-volume-down"></i>',
        'm_name' => 'Silent Notifications',
        'm_desc' => 'User is connected and will be notified by on-screen notification only for new Mench messages',
        'm_parents' => array(4454),
    ),
    4458 => array(
        'm_icon' => '<i class="fal fa-volume-mute"></i>',
        'm_name' => 'No Notifications',
        'm_desc' => 'User is connected but will not be notified for new Mench messages except the red icon indicator on the Messenger app which would indicate the total number of new messages they have',
        'm_parents' => array(4454),
    ),
    4455 => array(
        'm_icon' => '<i class="fas fa-ban"></i>',
        'm_name' => 'User Unsubscribed',
        'm_desc' => 'Stop all communications until you re-subscribe',
        'm_parents' => array(4454),
    ),
);

//Intent Notes:
$config['en_ids_4485'] = array(4231,4983,4601,4232,6242,6093);
$config['en_all_4485'] = array(
    4231 => array(
        'm_icon' => '<i class="far fa-comment"></i>',
        'm_name' => 'Message',
        'm_desc' => 'Delivered in-order when student initially starts this intent. Goal is to give key insights that streamline the execution of the intention.',
        'm_parents' => array(6345,4986,4603,4593,4485,4595),
    ),
    4983 => array(
        'm_icon' => '<i class="far fa-bookmark"></i>',
        'm_name' => 'Reference',
        'm_desc' => 'Tracks intent correlations mined from expert sources and miner perspectives. References give credibility to intent correlations. Never communicated with Students and only used for weighting purposes, like how Google uses link correlations for its PageRank algorithm.',
        'm_parents' => array(4986,4985,4595,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="far fa-bolt"></i>',
        'm_name' => 'Trigger',
        'm_desc' => 'Never delivered to students, instead, it maps alternative ways an intent could be named so we can better understand student commands.',
        'm_parents' => array(4593,4595,4485),
    ),
    4232 => array(
        'm_icon' => '<i class="far fa-tint"></i>',
        'm_name' => 'Drip Message',
        'm_desc' => 'Delivered in-order and one-by-one (drip-format) either during or after the intent completion. Goal is to re-iterate key insights to help students retain learnings over time.',
        'm_parents' => array(5007,4986,4603,4593,4485,4595),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'On-Complete Message',
        'm_desc' => 'Message delivered to students when they complete an intention.',
        'm_parents' => array(5007,6345,4603,4595,4593,4986,4485),
    ),
    6093 => array(
        'm_icon' => '<i class="far fa-comments"></i>',
        'm_name' => 'Chatlog',
        'm_desc' => 'Similar to Wikipedia\'s Talk pages, the Mench changelog helps miners track the history and evolution of a intent and explain/propose changes/improvements.',
        'm_parents' => array(5007,4595,4593,4986,4485),
    ),
);

//Intent Links:
$config['en_ids_4486'] = array(4228,4229);
$config['en_all_4486'] = array(
    4228 => array(
        'm_icon' => '<i class="fas fa-walking"></i>',
        'm_name' => 'Regular Step',
        'm_desc' => 'Intent link published and added to user Action Plans up-front',
        'm_parents' => array(6410,4593,4486,4595),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Locked Step',
        'm_desc' => 'Intent added to Action Plans after parent intent is complete AND the user\'s % score falls within the defined min/max range',
        'm_parents' => array(6410,6283,4593,4486,4595),
    ),
);

//Entity-to-Entity URL Link Types:
$config['en_ids_4537'] = array(4256,4257,4258,4259,4260,4261);
$config['en_all_4537'] = array(
    4256 => array(
        'm_icon' => '<i class="far fa-browser"></i>',
        'm_name' => 'URL',
        'm_desc' => 'Link note contains a generic URL only.',
        'm_parents' => array(4593,4592,4537,4595),
    ),
    4257 => array(
        'm_icon' => '<i class="far fa-play-circle"></i>',
        'm_name' => 'Embed Player',
        'm_desc' => 'Link note contain a recognizable URL that offers an embed widget for a more engaging play-back experience.',
        'm_parents' => array(4593,4592,4537,4506,4595),
    ),
    4258 => array(
        'm_icon' => '<i class="far fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => 'Link notes contain a URL to a raw video file.',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4259 => array(
        'm_icon' => '<i class="far fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4260 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
    4261 => array(
        'm_icon' => '<i class="far fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => 'Link notes contain a URL to a raw file.',
        'm_parents' => array(6203,4593,4592,4537,4595),
    ),
);

//Expert Sources:
$config['en_ids_3000'] = array(2997,2998,2999,3005,3147,3192,4446,4763,4883,5948);
$config['en_all_3000'] = array(
    2997 => array(
        'm_icon' => '<i class="fas fa-newspaper"></i>',
        'm_name' => 'Expert Articles',
        'm_desc' => '',
        'm_parents' => array(3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fas fa-film"></i>',
        'm_name' => 'Expert Videos',
        'm_desc' => '',
        'm_parents' => array(3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fas fa-microphone"></i>',
        'm_name' => 'Expert Podcasts',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fas fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fas fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fas fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fas fa-tachometer"></i>',
        'm_name' => 'Expert Assessments',
        'm_desc' => '',
        'm_parents' => array(3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fas fa-bullhorn"></i>',
        'm_name' => 'Expert Channels',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
    4883 => array(
        'm_icon' => '<i class="fas fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => '',
        'm_parents' => array(6805,3000),
    ),
    5948 => array(
        'm_icon' => '<i class="fas fa-file-invoice"></i>',
        'm_name' => 'Expert Templates',
        'm_desc' => '',
        'm_parents' => array(3000),
    ),
);