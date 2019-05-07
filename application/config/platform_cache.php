<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Keep a cache of certain parts of the Intent tree for faster processing
 * So we don't have to make DB calls to figure them out every time!
 * See here for all entities cached: https://mench.com/entities/4527
 * use-case format: $this->config->item('en_all_2738')
 *
 * ATTENTION: Also search for "en_ids_" and "en_all_" when trying to manage these throughout the code base
 *
 */

//Generated 2019-05-06 19:43:52 PST

//Mench:
$config['en_ids_2738'] = array(4488, 6137, 6138, 6196, 6287);
$config['en_all_2738'] = array(
    4488 => array(
        'm_icon' => '<img src="https://mench.com/img/mench_white.png">',
        'm_name' => 'Mining Platform',
        'm_desc' => 'On a mission to build and share consensus 🤝',
        'm_parents' => array(2738, 4523, 3326, 3324, 3325, 3323, 4463),
    ),
    6137 => array(
        'm_icon' => '👤',
        'm_name' => 'My Account',
        'm_desc' => 'A web-based portal (also accessible via Messenger) enabling students to manage their account',
        'm_parents' => array(2738),
    ),
    6138 => array(
        'm_icon' => '🚩',
        'm_name' => 'Action Plan',
        'm_desc' => 'A web-based portal (also accessible via Messenger) enabling students to manage their intentions',
        'm_parents' => array(2738, 4463),
    ),
    6196 => array(
        'm_icon' => '<img src="https://mench.com/img/bp_128.png">',
        'm_name' => 'Personal Assistant',
        'm_desc' => 'A personal assistant bot that automates the distribution of Mench\'s intent tree to students using Facebook Messenger',
        'm_parents' => array(2738, 4527, 3320),
    ),
    6287 => array(
        'm_icon' => '<i class="fas fa-tools"></i>',
        'm_name' => 'Admin Tools',
        'm_desc' => 'Series of tools to moderate the Mench platform',
        'm_parents' => array(2738),
    ),
);

//Action Plan Progression Automated:
$config['en_ids_6274'] = array(4559, 6158);
$config['en_all_6274'] = array(
    4559 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Action Plan Read Messages',
        'm_desc' => '',
        'm_parents' => array(6274, 6255, 4755, 6146, 4593),
    ),
    6158 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Action Plan Review Outcome',
        'm_desc' => '',
        'm_parents' => array(6274, 6255, 4593, 4755, 6146),
    ),
);

//Action Plan Progression Completion Triggers:
$config['en_ids_6255'] = array(4235, 4559, 6144, 6157, 6158, 6289);
$config['en_all_6255'] = array(
    4235 => array(
        'm_icon' => '<i class="fas fa-bullseye-arrow"></i>',
        'm_name' => 'Action Plan Set Intention',
        'm_desc' => '',
        'm_parents' => array(6153, 6255, 4506, 4755, 4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Action Plan Read Messages',
        'm_desc' => '',
        'm_parents' => array(6274, 6255, 4755, 6146, 4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Action Plan Submit Requirements',
        'm_desc' => '',
        'm_parents' => array(6255, 6244, 4755, 6146, 4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-clipboard-check"></i>',
        'm_name' => 'Action Plan Answer Question',
        'm_desc' => '',
        'm_parents' => array(6288, 6255, 6244, 6146, 4755, 4593, 4460),
    ),
    6158 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Action Plan Review Outcome',
        'm_desc' => '',
        'm_parents' => array(6274, 6255, 4593, 4755, 6146),
    ),
    6289 => array(
        'm_icon' => '<i class="fas fa-envelope-open-dollar"></i>',
        'm_name' => 'Action Plan 1-time PayPal Payment',
        'm_desc' => '',
        'm_parents' => array(6291, 6290, 6255, 4755, 4593, 6244, 6146),
    ),
);

//Action Plan Progression 2-Step Steps:
$config['en_ids_6244'] = array(6144, 6157, 6289);
$config['en_all_6244'] = array(
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Submit Requirements',
        'm_desc' => '',
        'm_parents' => array(6255, 6244, 4755, 6146, 4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-clipboard-check"></i>',
        'm_name' => 'Answer Question',
        'm_desc' => '',
        'm_parents' => array(6288, 6255, 6244, 6146, 4755, 4593, 4460),
    ),
    6289 => array(
        'm_icon' => '<i class="fas fa-envelope-open-dollar"></i>',
        'm_name' => '1-time PayPal Payment',
        'm_desc' => '',
        'm_parents' => array(6291, 6290, 6255, 4755, 4593, 6244, 6146),
    ),
);

//Action Plan Completion:
$config['en_ids_6150'] = array(6154, 6155);
$config['en_all_6150'] = array(
    6154 => array(
        'm_icon' => '<i class="far fa-badge-check"></i>',
        'm_name' => 'Intention Accomplished',
        'm_desc' => 'You successfully accomplished your intention so you no longer want to receive future updates',
        'm_parents' => array(5966, 4506, 6150, 4755, 4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'Intention Terminated',
        'm_desc' => 'You did NOT accomplish the intention and you want to stop all future updates on this intention',
        'm_parents' => array(5966, 4506, 6150, 4593, 4755),
    ),
);

//Entity Referencing in Intent Notes:
$config['en_ids_4986'] = array(4231, 4232, 4983, 6093, 6242);
$config['en_all_4986'] = array(
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '',
        'm_parents' => array(4986, 4603, 4593, 4485, 4595),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-medal"></i>',
        'm_name' => 'Intent Note Bonus Tip',
        'm_desc' => '',
        'm_parents' => array(5005, 4986, 4603, 4593, 4485, 4595),
    ),
    4983 => array(
        'm_icon' => '<i class="fal fa-thumbs-up"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => '',
        'm_parents' => array(4986, 4985, 4595, 4593, 4485),
    ),
    6093 => array(
        'm_icon' => '<i class="fal fa-megaphone"></i>',
        'm_name' => 'Intent Note Changelog',
        'm_desc' => '',
        'm_parents' => array(4595, 4593, 4986, 5005, 4485),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Tip',
        'm_desc' => '',
        'm_parents' => array(4603, 4595, 4593, 4986, 5005, 4485),
    ),
);

//My Account Inputs:
$config['en_ids_6225'] = array(6197, 3288, 3286, 4783, 4454, 3290, 3287, 3089, 3289, 6123);
$config['en_all_6225'] = array(
    6197 => array(
        'm_icon' => '<i class="fas fa-fingerprint"></i>',
        'm_name' => 'Full Name',
        'm_desc' => 'Your first and last name:',
        'm_parents' => array(6225, 6213, 6206),
    ),
    3288 => array(
        'm_icon' => '<i class="fal fa-envelope"></i>',
        'm_name' => 'Email Address',
        'm_desc' => 'The email address used to login to your Action Plan on mench.com:',
        'm_parents' => array(6225, 4426, 4755),
    ),
    3286 => array(
        'm_icon' => '<i class="fal fa-lock-open"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => 'The password used to login to your Action Plan on mench.com:',
        'm_parents' => array(6225, 5969, 4755, 4255),
    ),
    4783 => array(
        'm_icon' => '<i class="fal fa-phone"></i>',
        'm_name' => 'Phone Number',
        'm_desc' => 'Share your current phone for coaching calls:',
        'm_parents' => array(6225, 4755, 4319),
    ),
    4454 => array(
        'm_icon' => '<i class="fal fa-bells"></i>',
        'm_name' => 'Subscription Settings',
        'm_desc' => 'Choose how you like to be notified for messages I send you via Messenger:',
        'm_parents' => array(6225, 6204, 4603, 4527),
    ),
    3290 => array(
        'm_icon' => '<i class="fal fa-transgender"></i>',
        'm_name' => 'Genders',
        'm_desc' => 'Choose one of the following:',
        'm_parents' => array(6225, 6204),
    ),
    3287 => array(
        'm_icon' => '<i class="fal fa-language"></i>',
        'm_name' => 'Languages',
        'm_desc' => 'Choose all the languages you speak fluently:',
        'm_parents' => array(6225, 6122, 4603),
    ),
    3089 => array(
        'm_icon' => '<i class="fal fa-globe"></i>',
        'm_name' => 'Countries',
        'm_desc' => 'Choose your current country of residence:',
        'm_parents' => array(6225, 6204),
    ),
    3289 => array(
        'm_icon' => '<i class="fal fa-map"></i>',
        'm_name' => 'Timezones',
        'm_desc' => 'Choose your current timezone:',
        'm_parents' => array(6225, 6204),
    ),
    6123 => array(
        'm_icon' => '<i class="fal fa-share-alt-square"></i>',
        'm_name' => 'Social Profiles',
        'm_desc' => 'Share your social profiles with the Mench community:',
        'm_parents' => array(6225, 4527),
    ),
);

//Mench Personal Assistant:
$config['en_ids_6196'] = array(6200, 6203);
$config['en_all_6196'] = array(
    6200 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Entity PSID',
        'm_desc' => '',
        'm_parents' => array(6196, 6215, 6206),
    ),
    6203 => array(
        'm_icon' => '<i class="fab fa-facebook"></i>',
        'm_name' => 'Link Metadata Facebook Attachment ID',
        'm_desc' => 'File caching offered by Facebook for media delivered over Messenger.',
        'm_parents' => array(6232, 6196, 6215, 2793, 6103),
    ),
);

//Intent Types:
$config['en_ids_4530'] = array(6192, 6193);
$config['en_all_4530'] = array(
    6192 => array(
        'm_icon' => '<i class="fal fa-sitemap"></i>',
        'm_name' => 'AND',
        'm_desc' => 'AND Intents are completed when ALL their children are complete',
        'm_parents' => array(4530),
    ),
    6193 => array(
        'm_icon' => '<i class="fal fa-code-merge"></i>',
        'm_name' => 'OR',
        'm_desc' => 'OR Intents are completed when ANY of their children are complete',
        'm_parents' => array(4530),
    ),
);

//Link Student Statuses:
$config['en_ids_6187'] = array(6188, 6189, 6190, 6191);
$config['en_all_6187'] = array(
    6188 => array(
        'm_icon' => '<i class="fal fa-minus-square"></i>',
        'm_name' => 'Skipped',
        'm_desc' => 'Step was skipped by student',
        'm_parents' => array(6187),
    ),
    6189 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'Not Started',
        'm_desc' => 'Pending completion',
        'm_parents' => array(6187),
    ),
    6190 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Working On',
        'm_desc' => 'Started but not yet complete',
        'm_parents' => array(6187),
    ),
    6191 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Completed',
        'm_desc' => 'Marked as complete and pending new updates',
        'm_parents' => array(6187),
    ),
);

//Intent Statuses:
$config['en_ids_4737'] = array(6182, 6183, 6184, 6185);
$config['en_all_4737'] = array(
    6182 => array(
        'm_icon' => '<i class="fal fa-minus-square"></i>',
        'm_name' => 'Removed',
        'm_desc' => 'Intent is in-active',
        'm_parents' => array(4737),
    ),
    6183 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'New',
        'm_desc' => 'Intent is newly added and pending to be mined',
        'm_parents' => array(4737),
    ),
    6184 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Drafting',
        'm_desc' => 'Intent is being worked-on so it can be published',
        'm_parents' => array(4737),
    ),
    6185 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Published',
        'm_desc' => 'Intent is live and publicly accessible',
        'm_parents' => array(4737),
    ),
);

//Entity Statuses:
$config['en_ids_6177'] = array(6178, 6179, 6180, 6181);
$config['en_all_6177'] = array(
    6178 => array(
        'm_icon' => '<i class="fal fa-minus-square"></i>',
        'm_name' => 'Removed',
        'm_desc' => 'Entity is in-active',
        'm_parents' => array(6177),
    ),
    6179 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'New',
        'm_desc' => 'Entity is newly added and pending to be mined',
        'm_parents' => array(6177),
    ),
    6180 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Drafting',
        'm_desc' => 'Entity is being worked-on so it can be published',
        'm_parents' => array(6177),
    ),
    6181 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Published',
        'm_desc' => 'Entity is live and publicly accessible',
        'm_parents' => array(6177),
    ),
);

//Link Miner Statuses:
$config['en_ids_4363'] = array(6173, 6174, 6175, 6176);
$config['en_all_4363'] = array(
    6173 => array(
        'm_icon' => '<i class="fal fa-minus-square"></i>',
        'm_name' => 'Removed',
        'm_desc' => 'Link is in-active',
        'm_parents' => array(4363),
    ),
    6174 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'New',
        'm_desc' => 'Link is newly added and pending to be mined',
        'm_parents' => array(4363),
    ),
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner fa-spin"></i>',
        'm_name' => 'Drafting',
        'm_desc' => 'Link is being worked-on so it can be published',
        'm_parents' => array(4363),
    ),
    6176 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Published',
        'm_desc' => 'Link is complete, ready and live',
        'm_parents' => array(4363),
    ),
);

//Action Plan Progression Steps:
$config['en_ids_6146'] = array(4559, 6140, 6143, 6144, 6157, 6158, 6289);
$config['en_all_6146'] = array(
    4559 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Read Messages',
        'm_desc' => 'Completed when students read the messages of an intent that does not have a completion requirement',
        'm_parents' => array(6274, 6255, 4755, 6146, 4593),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Unlock Milestone',
        'm_desc' => 'Expands the Action Plan when the student meets the Milestone conditions',
        'm_parents' => array(6146, 6288, 4229, 4755, 4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-minus-square"></i>',
        'm_name' => 'Skipped Step',
        'm_desc' => 'Completed when students skip an intention and all its child intentions from their Action Plan',
        'm_parents' => array(6146, 4755, 4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Submit Requirements',
        'm_desc' => 'Completed when students submit the intent completion requirements (text, URL, video, etc...) set by miners',
        'm_parents' => array(6255, 6244, 4755, 6146, 4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-clipboard-check"></i>',
        'm_name' => 'Answer Question',
        'm_desc' => 'Completed after the student answers the question to the OR intent.',
        'm_parents' => array(6288, 6255, 6244, 6146, 4755, 4593, 4460),
    ),
    6158 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Review Outcome',
        'm_desc' => 'Completed when students read the messages of an intent that does not have any messages or a completion requirement',
        'm_parents' => array(6274, 6255, 4593, 4755, 6146),
    ),
    6289 => array(
        'm_icon' => '<i class="fas fa-envelope-open-dollar"></i>',
        'm_name' => '1-time PayPal Payment',
        'm_desc' => 'When the student is required to complete an intention with a 1-time payment set by Miners.',
        'm_parents' => array(6291, 6290, 6255, 4755, 4593, 6244, 6146),
    ),
);

//Social Profiles:
$config['en_ids_6123'] = array(2793, 3300, 3301, 3302, 3303, 3320);
$config['en_all_6123'] = array(
    2793 => array(
        'm_icon' => '<i class="fab fa-facebook"></i>',
        'm_name' => 'Facebook',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 1326, 1326, 2750),
    ),
    3300 => array(
        'm_icon' => '<i class="fab fa-twitter"></i>',
        'm_name' => 'Twitter',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 2750, 1326, 3304),
    ),
    3301 => array(
        'm_icon' => '<i class="fab fa-instagram"></i>',
        'm_name' => 'Instagram',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 1326, 2750),
    ),
    3302 => array(
        'm_icon' => '<i class="fab fa-linkedin"></i>',
        'm_name' => 'LinkedIn',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 1326, 4763, 2750),
    ),
    3303 => array(
        'm_icon' => '<i class="fab fa-github"></i>',
        'm_name' => 'Github',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 4763, 1326, 2750),
    ),
    3320 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Facebook Messenger',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 1326, 1326, 2750, 2793),
    ),
);

//Student Sent Message Link Types:
$config['en_ids_4277'] = array(4460, 4547, 4548, 4549, 4550, 4551, 4557);
$config['en_all_4277'] = array(
    4460 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'Sent Quick Reply',
        'm_desc' => 'When students select a quick reply answer of any kind',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4547 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Sent Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4548 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Sent Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4549 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Sent Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4550 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Sent Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4551 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Sent File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4557 => array(
        'm_icon' => '<i class="fal fa-location-circle"></i>',
        'm_name' => 'Sent Location Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
);

//Student Media Communication Link Types:
$config['en_ids_6102'] = array(4548, 4549, 4550, 4551, 4553, 4554, 4555, 4556);
$config['en_all_6102'] = array(
    4548 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Sent Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4549 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Sent Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4550 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Sent Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4551 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Sent File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4553 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Received Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4554 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Received Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4555 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Received Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4556 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Received File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
);

//Student Received Message Link Types:
$config['en_ids_4280'] = array(4552, 4553, 4554, 4555, 4556, 4570, 5967);
$config['en_all_4280'] = array(
    4552 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Received Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    4553 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Received Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4554 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Received Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4555 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Received Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4556 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Received File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'Received HTML Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    5967 => array(
        'm_icon' => '<i class="fal fa-envelope-open"></i>',
        'm_name' => 'Received Email Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
);

//Human Groups:
$config['en_ids_4432'] = array(1281, 1308, 3084, 4430, 4433);
$config['en_all_4432'] = array(
    1281 => array(
        'm_icon' => '🛡️',
        'm_name' => 'Mench Moderators',
        'm_desc' => '',
        'm_parents' => array(4757, 4463, 4432),
    ),
    1308 => array(
        'm_icon' => '⛏️',
        'm_name' => 'Mench Miners',
        'm_desc' => '',
        'm_parents' => array(4463, 4432, 4426),
    ),
    3084 => array(
        'm_icon' => '⭐',
        'm_name' => 'Industry Experts',
        'm_desc' => '',
        'm_parents' => array(4990, 4432, 4255, 4463),
    ),
    4430 => array(
        'm_icon' => '🎓',
        'm_name' => 'Mench Students',
        'm_desc' => '',
        'm_parents' => array(4426, 4463, 4432),
    ),
    4433 => array(
        'm_icon' => '⌨️',
        'm_name' => 'Mench Developers',
        'm_desc' => '',
        'm_parents' => array(4463, 4432, 4426),
    ),
);

//System Modification Lock:
$config['en_ids_5969'] = array(3286);
$config['en_all_5969'] = array(
    3286 => array(
        'm_icon' => '<i class="fal fa-lock-open"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => 'Managed through the Forgot Password section in the Login page',
        'm_parents' => array(6225, 5969, 4755, 4255),
    ),
);

//Link Type Email Subscription:
$config['en_ids_5966'] = array(4246, 6154, 6155);
$config['en_all_5966'] = array(
    4246 => array(
        'm_icon' => '<i class="fal fa-bug"></i>',
        'm_name' => 'Reported Bug',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(5966, 4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-badge-check"></i>',
        'm_name' => 'Action Plan Intention Accomplished',
        'm_desc' => '&var_en_subscriber_ids=1,2',
        'm_parents' => array(5966, 4506, 6150, 4755, 4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'Action Plan Intention Terminated',
        'm_desc' => '&var_en_subscriber_ids=1,2',
        'm_parents' => array(5966, 4506, 6150, 4593, 4755),
    ),
);

//Linked Entities Text:
$config['en_ids_4255'] = array(2999, 3005, 3084, 3147, 3192, 3286, 4601, 4763, 4883, 6232);
$config['en_all_4255'] = array(
    2999 => array(
        'm_icon' => '<i class="fal fa-microphone"></i>',
        'm_name' => 'Expert Podcasts',
        'm_desc' => 'Podcast summary',
        'm_parents' => array(4990, 4255, 3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fal fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => 'Book summary',
        'm_parents' => array(4990, 4255, 3000),
    ),
    3084 => array(
        'm_icon' => '⭐',
        'm_name' => 'Industry Experts',
        'm_desc' => 'List accomplishments supporting the expertise of this entity',
        'm_parents' => array(4990, 4432, 4255, 4463),
    ),
    3147 => array(
        'm_icon' => '<i class="fal fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => 'Course summary',
        'm_parents' => array(4990, 4255, 3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fal fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => 'Explain the process that this software automates and it\'s ideal target segment',
        'm_parents' => array(4990, 4255, 3000),
    ),
    3286 => array(
        'm_icon' => '<i class="fal fa-lock-open"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => 'Enter SHA256 encoded password string combined with our SALT variables',
        'm_parents' => array(6225, 5969, 4755, 4255),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-tags"></i>',
        'm_name' => 'Intent Note Keyword',
        'm_desc' => 'Trigger statements can only contain text to enable Mench to detect alternatives forms a person might reference an intent',
        'm_parents' => array(4255, 4593, 4595, 4485),
    ),
    4763 => array(
        'm_icon' => '<i class="fal fa-bullhorn"></i>',
        'm_name' => 'Expert Marketing Channels',
        'm_desc' => 'Describe marketplace community size and ideal target segments and key value propositions',
        'm_parents' => array(4990, 4255, 3000),
    ),
    4883 => array(
        'm_icon' => '<i class="fal fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => 'What services is offered and how much they cost',
        'm_parents' => array(4990, 4255, 3000),
    ),
    6232 => array(
        'm_icon' => '<i class="far fa-file-signature"></i>',
        'm_name' => 'Variable Names',
        'm_desc' => 'Requires variable name',
        'm_parents' => array(4255, 6212),
    ),
);

//Advance Mode:
$config['en_ids_5005'] = array(4232, 4331, 4997, 6093, 6242);
$config['en_all_5005'] = array(
    4232 => array(
        'm_icon' => '<i class="fal fa-medal"></i>',
        'm_name' => 'Intent Note Bonus Tip',
        'm_desc' => '',
        'm_parents' => array(5005, 4986, 4603, 4593, 4485, 4595),
    ),
    4331 => array(
        'm_icon' => '<i class="fal fa-comment-check"></i>',
        'm_name' => 'Intent Completion Methods',
        'm_desc' => '',
        'm_parents' => array(6213, 6201, 6194, 5005, 4527),
    ),
    4997 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Updates',
        'm_desc' => '',
        'm_parents' => array(6220, 4506, 4426, 5005, 4527),
    ),
    6093 => array(
        'm_icon' => '<i class="fal fa-megaphone"></i>',
        'm_name' => 'Intent Note Changelog',
        'm_desc' => '',
        'm_parents' => array(4595, 4593, 4986, 5005, 4485),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Tip',
        'm_desc' => '',
        'm_parents' => array(4603, 4595, 4593, 4986, 5005, 4485),
    ),
);

//Entity Mass Updates:
$config['en_ids_4997'] = array(4998, 4999, 5000, 5001, 5003, 5865, 5943, 5981, 5982);
$config['en_all_4997'] = array(
    4998 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Name Prefix',
        'm_desc' => 'Adds string to the beginning of all child entities. Make sure to include a space for it to look good',
        'm_parents' => array(4593, 4997),
    ),
    4999 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Name Postfix',
        'm_desc' => 'Adds string to the end of all child entities',
        'm_parents' => array(4593, 4997),
    ),
    5000 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Name Replace',
        'm_desc' => 'Search for occurance of string in child entity names and if found, updates it with a replacement string',
        'm_parents' => array(4593, 4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Link Content Replace',
        'm_desc' => 'Search for occurance of string in child entity link contents and if found, updates it with a replacement string',
        'm_parents' => array(4593, 4997),
    ),
    5003 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Status Replace',
        'm_desc' => 'Updates all child entity statuses that match the initial entity status condition',
        'm_parents' => array(4593, 4997),
    ),
    5865 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Link Status Replace',
        'm_desc' => 'Updates all child entity link statuses that match the initial link status condition',
        'm_parents' => array(4593, 4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Icon Update',
        'm_desc' => 'Updates all child entity icons with string which needs to be a valid icon',
        'm_parents' => array(4593, 4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Parent Entity Addition',
        'm_desc' => 'If not already done so, will add searched entity as the parent of all child entities',
        'm_parents' => array(4593, 4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Parent Entity Removal',
        'm_desc' => 'If already added as the parent, this will remove searched entity as the parent of all child entities',
        'm_parents' => array(4593, 4997),
    ),
);

//Intent Notes Public Entity References:
$config['en_ids_4990'] = array(1326, 2793, 2997, 2998, 2999, 3005, 3084, 3147, 3192, 3300, 3301, 3302, 3303, 3308, 3314, 3320, 4257, 4258, 4259, 4260, 4395, 4399, 4446, 4763, 4883, 5948);
$config['en_all_4990'] = array(
    1326 => array(
        'm_icon' => '<i class="fal fa-bookmark"></i>',
        'm_name' => 'Domain',
        'm_desc' => '',
        'm_parents' => array(4990, 4256, 4506),
    ),
    2793 => array(
        'm_icon' => '<i class="fab fa-facebook"></i>',
        'm_name' => 'Facebook',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 1326, 1326, 2750),
    ),
    2997 => array(
        'm_icon' => '<i class="fal fa-newspaper"></i>',
        'm_name' => 'Expert Articles',
        'm_desc' => '',
        'm_parents' => array(4990, 3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fal fa-film"></i>',
        'm_name' => 'Expert Videos',
        'm_desc' => '',
        'm_parents' => array(4990, 3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fal fa-microphone"></i>',
        'm_name' => 'Expert Podcasts',
        'm_desc' => '',
        'm_parents' => array(4990, 4255, 3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fal fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '',
        'm_parents' => array(4990, 4255, 3000),
    ),
    3084 => array(
        'm_icon' => '⭐',
        'm_name' => 'Industry Experts',
        'm_desc' => '',
        'm_parents' => array(4990, 4432, 4255, 4463),
    ),
    3147 => array(
        'm_icon' => '<i class="fal fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '',
        'm_parents' => array(4990, 4255, 3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fal fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => '',
        'm_parents' => array(4990, 4255, 3000),
    ),
    3300 => array(
        'm_icon' => '<i class="fab fa-twitter"></i>',
        'm_name' => 'Twitter',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 2750, 1326, 3304),
    ),
    3301 => array(
        'm_icon' => '<i class="fab fa-instagram"></i>',
        'm_name' => 'Instagram',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 1326, 2750),
    ),
    3302 => array(
        'm_icon' => '<i class="fab fa-linkedin"></i>',
        'm_name' => 'LinkedIn',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 1326, 4763, 2750),
    ),
    3303 => array(
        'm_icon' => '<i class="fab fa-github"></i>',
        'm_name' => 'Github',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 4763, 1326, 2750),
    ),
    3308 => array(
        'm_icon' => '<i class="fab fa-youtube"></i>',
        'm_name' => 'YouTube',
        'm_desc' => '',
        'm_parents' => array(4990, 4763, 4257, 2750, 1326),
    ),
    3314 => array(
        'm_icon' => '<i class="fab fa-slack"></i>',
        'm_name' => 'Slack',
        'm_desc' => '',
        'm_parents' => array(4990, 1326, 2750),
    ),
    3320 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Facebook Messenger',
        'm_desc' => '',
        'm_parents' => array(6123, 4990, 1326, 1326, 2750, 2793),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Linked Entities Embed URL',
        'm_desc' => '',
        'm_parents' => array(4990, 4593, 4592, 4537, 4506, 4595),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Linked Entities Video',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Linked Entities Audio',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Linked Entities Image',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4395 => array(
        'm_icon' => '',
        'm_name' => 'Calendly',
        'm_desc' => '',
        'm_parents' => array(4990, 2750, 1326),
    ),
    4399 => array(
        'm_icon' => '<i class="fab fa-angellist"></i>',
        'm_name' => 'Angel Co',
        'm_desc' => '',
        'm_parents' => array(4990, 4763, 2750, 1326),
    ),
    4446 => array(
        'm_icon' => '<i class="fal fa-tachometer"></i>',
        'm_name' => 'Expert Assessments',
        'm_desc' => '',
        'm_parents' => array(4990, 3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fal fa-bullhorn"></i>',
        'm_name' => 'Expert Marketing Channels',
        'm_desc' => '',
        'm_parents' => array(4990, 4255, 3000),
    ),
    4883 => array(
        'm_icon' => '<i class="fal fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => '',
        'm_parents' => array(4990, 4255, 3000),
    ),
    5948 => array(
        'm_icon' => '<i class="fal fa-file-invoice"></i>',
        'm_name' => 'Expert Templates',
        'm_desc' => '',
        'm_parents' => array(4990, 3000),
    ),
);

//Only Moderators can Modify:
$config['en_ids_4426'] = array(1308, 3288, 4426, 4430, 4433, 4595, 4755, 4997, 5969);
$config['en_all_4426'] = array(
    1308 => array(
        'm_icon' => '⛏️',
        'm_name' => 'Mench Miners',
        'm_desc' => 'Requires admin review and approval',
        'm_parents' => array(4463, 4432, 4426),
    ),
    3288 => array(
        'm_icon' => '<i class="fal fa-envelope"></i>',
        'm_name' => 'Email Address',
        'm_desc' => '',
        'm_parents' => array(6225, 4426, 4755),
    ),
    4426 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Only Moderators can Modify',
        'm_desc' => '',
        'm_parents' => array(4426, 4527, 4757, 4428),
    ),
    4430 => array(
        'm_icon' => '🎓',
        'm_name' => 'Mench Students',
        'm_desc' => '',
        'm_parents' => array(4426, 4463, 4432),
    ),
    4433 => array(
        'm_icon' => '⌨️',
        'm_name' => 'Mench Developers',
        'm_desc' => 'Requires admin review and approval',
        'm_parents' => array(4463, 4432, 4426),
    ),
    4595 => array(
        'm_icon' => '<i class="fas fa-award"></i>',
        'm_name' => 'Link Points',
        'm_desc' => '',
        'm_parents' => array(6214, 4319, 4426, 4527, 4463, 4341),
    ),
    4755 => array(
        'm_icon' => '<i class="fas fa-eye-slash"></i>',
        'm_name' => 'Link Visible to Moderators Only',
        'm_desc' => '',
        'm_parents' => array(4426, 4527, 4757),
    ),
    4997 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Updates',
        'm_desc' => '',
        'm_parents' => array(6220, 4506, 4426, 5005, 4527),
    ),
    5969 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'System Modification Lock',
        'm_desc' => '',
        'm_parents' => array(4426, 4527, 4757, 4428),
    ),
);

//Link Visible to Moderators Only:
$config['en_ids_4755'] = array(3286, 3288, 4235, 4266, 4267, 4268, 4275, 4278, 4279, 4282, 4283, 4299, 4460, 4547, 4548, 4549, 4550, 4551, 4552, 4553, 4554, 4555, 4556, 4557, 4559, 4570, 4783, 5967, 6132, 6140, 6143, 6144, 6149, 6154, 6155, 6157, 6158, 6224, 6289);
$config['en_all_4755'] = array(
    3286 => array(
        'm_icon' => '<i class="fal fa-lock-open"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => '',
        'm_parents' => array(6225, 5969, 4755, 4255),
    ),
    3288 => array(
        'm_icon' => '<i class="fal fa-envelope"></i>',
        'm_name' => 'Email Address',
        'm_desc' => '',
        'm_parents' => array(6225, 4426, 4755),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-bullseye-arrow"></i>',
        'm_name' => 'Action Plan Set Intention',
        'm_desc' => '',
        'm_parents' => array(6153, 6255, 4506, 4755, 4593),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Opted into Messenger',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Followed Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4268 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Messenger Postback Initiated',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Action Plan Search Intention',
        'm_desc' => '',
        'm_parents' => array(6153, 4755, 4593),
    ),
    4278 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'Read Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4279 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'Received Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4282 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'My Account Webview Opened',
        'm_desc' => '',
        'm_parents' => array(4755, 6222, 4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'Action Plan List Intentions',
        'm_desc' => '',
        'm_parents' => array(6153, 4755, 4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'Entity Picture Updated',
        'm_desc' => '',
        'm_parents' => array(6222, 4755, 4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4547 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Sent Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4548 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Sent Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4549 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Sent Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4550 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Sent Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4551 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Sent File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4552 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Received Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    4553 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Received Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4554 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Received Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4555 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Received Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4556 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Received File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4557 => array(
        'm_icon' => '<i class="fal fa-location-circle"></i>',
        'm_name' => 'Sent Location Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Action Plan Read Messages',
        'm_desc' => '',
        'm_parents' => array(6274, 6255, 4755, 6146, 4593),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'Received HTML Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    4783 => array(
        'm_icon' => '<i class="fal fa-phone"></i>',
        'm_name' => 'Phone Number',
        'm_desc' => '',
        'm_parents' => array(6225, 4755, 4319),
    ),
    5967 => array(
        'm_icon' => '<i class="fal fa-envelope-open"></i>',
        'm_name' => 'Received Email Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90"></i>',
        'm_name' => 'Action Plan Sort Intentions',
        'm_desc' => '',
        'm_parents' => array(6153, 4506, 4755, 4593),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Action Plan Unlock Milestone',
        'm_desc' => '',
        'm_parents' => array(6146, 6288, 4229, 4755, 4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-minus-square"></i>',
        'm_name' => 'Action Plan Skipped Step',
        'm_desc' => '',
        'm_parents' => array(6146, 4755, 4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Action Plan Submit Requirements',
        'm_desc' => '',
        'm_parents' => array(6255, 6244, 4755, 6146, 4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus"></i>',
        'm_name' => 'Action Plan Consider Intention',
        'm_desc' => '',
        'm_parents' => array(4428, 6153, 4755, 4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-badge-check"></i>',
        'm_name' => 'Action Plan Intention Accomplished',
        'm_desc' => '',
        'm_parents' => array(5966, 4506, 6150, 4755, 4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'Action Plan Intention Terminated',
        'm_desc' => '',
        'm_parents' => array(5966, 4506, 6150, 4593, 4755),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-clipboard-check"></i>',
        'm_name' => 'Action Plan Answer Question',
        'm_desc' => '',
        'm_parents' => array(6288, 6255, 6244, 6146, 4755, 4593, 4460),
    ),
    6158 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Action Plan Review Outcome',
        'm_desc' => '',
        'm_parents' => array(6274, 6255, 4593, 4755, 6146),
    ),
    6224 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'My Account Input Iterated',
        'm_desc' => '',
        'm_parents' => array(4755, 6222, 4593),
    ),
    6289 => array(
        'm_icon' => '<i class="fas fa-envelope-open-dollar"></i>',
        'm_name' => 'Action Plan 1-time PayPal Payment',
        'm_desc' => '',
        'm_parents' => array(6291, 6290, 6255, 4755, 4593, 6244, 6146),
    ),
);

//Link Points:
$config['en_ids_4595'] = array(4228, 4229, 4230, 4231, 4232, 4242, 4250, 4251, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4263, 4264, 4318, 4319, 4601, 4983, 6093, 6242);
$config['en_all_4595'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-check-circle"></i>',
        'm_name' => 'Linked Intents Fixed Step',
        'm_desc' => '100',
        'm_parents' => array(4593, 4486, 4595),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-spin fa-question-circle"></i>',
        'm_name' => 'Linked Intents Conditional Milestone',
        'm_desc' => '100',
        'm_parents' => array(4593, 4486, 4595),
    ),
    4230 => array(
        'm_icon' => '<i class="fal fa-level-up rotate90"></i>',
        'm_name' => 'Linked Entities Raw',
        'm_desc' => '10',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '100',
        'm_parents' => array(4986, 4603, 4593, 4485, 4595),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-medal"></i>',
        'm_name' => 'Intent Note Bonus Tip',
        'm_desc' => '100',
        'm_parents' => array(5005, 4986, 4603, 4593, 4485, 4595),
    ),
    4242 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Link',
        'm_desc' => '20',
        'm_parents' => array(4593, 4595),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Created Intent',
        'm_desc' => '200',
        'm_parents' => array(4593, 4595),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Created Entity',
        'm_desc' => '30',
        'm_parents' => array(4593, 4595),
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Linked Entities Text',
        'm_desc' => '30',
        'm_parents' => array(4527, 4593, 4592, 4595, 4331),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'Linked Entities Generic URL',
        'm_desc' => '50',
        'm_parents' => array(4593, 4592, 4537, 4595, 4331),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Linked Entities Embed URL',
        'm_desc' => '50',
        'm_parents' => array(4990, 4593, 4592, 4537, 4506, 4595),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Linked Entities Video',
        'm_desc' => '90',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Linked Entities Audio',
        'm_desc' => '50',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Linked Entities Image',
        'm_desc' => '50',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Linked Entities File',
        'm_desc' => '50',
        'm_parents' => array(6203, 4593, 4592, 4537, 4595, 4331),
    ),
    4263 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Entity',
        'm_desc' => '10',
        'm_parents' => array(4593, 4595),
    ),
    4264 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Intent',
        'm_desc' => '40',
        'm_parents' => array(4593, 4595),
    ),
    4318 => array(
        'm_icon' => '<i class="fal fa-clock"></i>',
        'm_name' => 'Linked Entities Time',
        'm_desc' => '20',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4319 => array(
        'm_icon' => '<i class="fal fa-sort-numeric-down"></i>',
        'm_name' => 'Linked Entities Integer',
        'm_desc' => '20',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-tags"></i>',
        'm_name' => 'Intent Note Keyword',
        'm_desc' => '50',
        'm_parents' => array(4255, 4593, 4595, 4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fal fa-thumbs-up"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => '100',
        'm_parents' => array(4986, 4985, 4595, 4593, 4485),
    ),
    6093 => array(
        'm_icon' => '<i class="fal fa-megaphone"></i>',
        'm_name' => 'Intent Note Changelog',
        'm_desc' => '100',
        'm_parents' => array(4595, 4593, 4986, 5005, 4485),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Tip',
        'm_desc' => '100',
        'm_parents' => array(4603, 4595, 4593, 4986, 5005, 4485),
    ),
);

//Account Holders:
$config['en_ids_4600'] = array(1278, 2750);
$config['en_all_4600'] = array(
    1278 => array(
        'm_icon' => '👪',
        'm_name' => 'People',
        'm_desc' => '',
        'm_parents' => array(4600),
    ),
    2750 => array(
        'm_icon' => '🏢',
        'm_name' => 'Organizations',
        'm_desc' => '',
        'm_parents' => array(4600),
    ),
);

//All Link Types:
$config['en_ids_4593'] = array(4228, 4229, 4230, 4231, 4232, 4235, 4242, 4246, 4250, 4251, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4263, 4264, 4266, 4267, 4268, 4269, 4275, 4278, 4279, 4282, 4283, 4287, 4299, 4318, 4319, 4452, 4455, 4460, 4547, 4548, 4549, 4550, 4551, 4552, 4553, 4554, 4555, 4556, 4557, 4559, 4570, 4577, 4601, 4983, 4993, 4994, 4996, 4998, 4999, 5000, 5001, 5003, 5007, 5865, 5943, 5967, 5981, 5982, 6093, 6132, 6140, 6143, 6144, 6149, 6154, 6155, 6157, 6158, 6224, 6226, 6242, 6255, 6278, 6289);
$config['en_all_4593'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-check-circle"></i>',
        'm_name' => 'Linked Intents Fixed Step',
        'm_desc' => '',
        'm_parents' => array(4593, 4486, 4595),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-spin fa-question-circle"></i>',
        'm_name' => 'Linked Intents Conditional Milestone',
        'm_desc' => '',
        'm_parents' => array(4593, 4486, 4595),
    ),
    4230 => array(
        'm_icon' => '<i class="fal fa-level-up rotate90"></i>',
        'm_name' => 'Linked Entities Raw',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'Intent Note Message',
        'm_desc' => '',
        'm_parents' => array(4986, 4603, 4593, 4485, 4595),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-medal"></i>',
        'm_name' => 'Intent Note Bonus Tip',
        'm_desc' => '',
        'm_parents' => array(5005, 4986, 4603, 4593, 4485, 4595),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-bullseye-arrow"></i>',
        'm_name' => 'Action Plan Set Intention',
        'm_desc' => 'Top-level goals set by students that enable Mench to deliver the most relevant intelligence.',
        'm_parents' => array(6153, 6255, 4506, 4755, 4593),
    ),
    4242 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Link',
        'm_desc' => 'Logged for each link column that is updated consciously by the user',
        'm_parents' => array(4593, 4595),
    ),
    4246 => array(
        'm_icon' => '<i class="fal fa-bug"></i>',
        'm_name' => 'Reported Bug',
        'm_desc' => '',
        'm_parents' => array(5966, 4593),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Created Intent',
        'm_desc' => '',
        'm_parents' => array(4593, 4595),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Created Entity',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(4593, 4595),
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Linked Entities Text',
        'm_desc' => '',
        'm_parents' => array(4527, 4593, 4592, 4595, 4331),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'Linked Entities Generic URL',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4537, 4595, 4331),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Linked Entities Embed URL',
        'm_desc' => '',
        'm_parents' => array(4990, 4593, 4592, 4537, 4506, 4595),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Linked Entities Video',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Linked Entities Audio',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Linked Entities Image',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Linked Entities File',
        'm_desc' => '',
        'm_parents' => array(6203, 4593, 4592, 4537, 4595, 4331),
    ),
    4263 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Entity',
        'm_desc' => 'When a Miner modified an entity attribute like Name, Icon or Status.',
        'm_parents' => array(4593, 4595),
    ),
    4264 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Intent',
        'm_desc' => 'When an intent field is updated',
        'm_parents' => array(4593, 4595),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Opted into Messenger',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Followed Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4268 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Messenger Postback Initiated',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'Logged In as Miner',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Action Plan Search Intention',
        'm_desc' => 'When students invokes the [I want to] command and search for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(6153, 4755, 4593),
    ),
    4278 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'Read Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4279 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'Received Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4282 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'My Account Webview Opened',
        'm_desc' => '',
        'm_parents' => array(4755, 6222, 4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'Action Plan List Intentions',
        'm_desc' => 'Once a student has added an Intention to their Action Plan, this link will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(6153, 4755, 4593),
    ),
    4287 => array(
        'm_icon' => '<i class="fal fa-comment-exclamation"></i>',
        'm_name' => 'Sent Unrecognized Message',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-image"></i>',
        'm_name' => 'Entity Picture Updated',
        'm_desc' => '',
        'm_parents' => array(6222, 4755, 4593),
    ),
    4318 => array(
        'm_icon' => '<i class="fal fa-clock"></i>',
        'm_name' => 'Linked Entities Time',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4319 => array(
        'm_icon' => '<i class="fal fa-sort-numeric-down"></i>',
        'm_name' => 'Linked Entities Integer',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4452 => array(
        'm_icon' => '<i class="fab fa-git"></i>',
        'm_name' => 'Pushed Code to Github',
        'm_desc' => '',
        'm_parents' => array(4593, 4428),
    ),
    4455 => array(
        'm_icon' => '<i class="fas fa-ban"></i>',
        'm_name' => 'Unsubscribed from Mench',
        'm_desc' => 'Student requested that all communication with Mench to be stopped until further notice from the student.',
        'm_parents' => array(4593, 4454),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4547 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Sent Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4548 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Sent Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4549 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Sent Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4550 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Sent Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4551 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Sent File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4552 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Received Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    4553 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Received Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4554 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Received Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4555 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Received Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4556 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Received File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4557 => array(
        'm_icon' => '<i class="fal fa-location-circle"></i>',
        'm_name' => 'Sent Location Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Action Plan Read Messages',
        'm_desc' => 'Logged when a student receives the messages of an AND intent that does not have any completion requirements.',
        'm_parents' => array(6274, 6255, 4755, 6146, 4593),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'Received HTML Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    4577 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Accepted Message Request',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-tags"></i>',
        'm_name' => 'Intent Note Keyword',
        'm_desc' => '',
        'm_parents' => array(4255, 4593, 4595, 4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fal fa-thumbs-up"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => 'Up-votes track intent correlations referenced within expert sources, and represent a core building block of intelligence. Up-votes are among the most precious transaction types because they indicate that IF you do A, you will likely accomplish B. As miners mine content from more experts, certain intent correlations will receive more Up-vites than others, thus gaining more credibility.',
        'm_parents' => array(4986, 4985, 4595, 4593, 4485),
    ),
    4993 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Viewed Intent',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4994 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Viewed Entity',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4996 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'Logged In as Student',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4998 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Entity Name Prefix',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    4999 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Entity Name Postfix',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5000 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Entity Name Replace',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Link Content Replace',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5003 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Entity Status Replace',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5007 => array(
        'm_icon' => '<i class="fal fa-expand-arrows"></i>',
        'm_name' => 'Miner Toggled Advance Mode',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    5865 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Link Status Replace',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Entity Icon Update',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5967 => array(
        'm_icon' => '<i class="fal fa-envelope-open"></i>',
        'm_name' => 'Received Email Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    5981 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Parent Entity Addition',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Parent Entity Removal',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    6093 => array(
        'm_icon' => '<i class="fal fa-megaphone"></i>',
        'm_name' => 'Intent Note Changelog',
        'm_desc' => '',
        'm_parents' => array(4595, 4593, 4986, 5005, 4485),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90"></i>',
        'm_name' => 'Action Plan Sort Intentions',
        'm_desc' => 'Student re-prioritized their top-level intentions to focus on intentions that currently matter the most.',
        'm_parents' => array(6153, 4506, 4755, 4593),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Action Plan Unlock Milestone',
        'm_desc' => 'Created when the student responses to OR branches meets the right % points to unlock the pathway to a conditional intent link.',
        'm_parents' => array(6146, 6288, 4229, 4755, 4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-minus-square"></i>',
        'm_name' => 'Action Plan Skipped Step',
        'm_desc' => 'Logged every time a student consciously skips an intent and it\'s recursive children.',
        'm_parents' => array(6146, 4755, 4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Action Plan Submit Requirements',
        'm_desc' => 'Logged when a student submits the requirements (text, video, etc...) of an AND intent which could not be completed by simply receiving messages.',
        'm_parents' => array(6255, 6244, 4755, 6146, 4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus"></i>',
        'm_name' => 'Action Plan Consider Intention',
        'm_desc' => 'When a student chooses to review a given intention from the intentions they have searched or have been recommended after selecting GET STARTED from a mench.com intent landing page.',
        'm_parents' => array(4428, 6153, 4755, 4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-badge-check"></i>',
        'm_name' => 'Action Plan Intention Accomplished',
        'm_desc' => 'Student accomplished their intention 🎉🎉🎉',
        'm_parents' => array(5966, 4506, 6150, 4755, 4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'Action Plan Intention Terminated',
        'm_desc' => 'Student prematurely removed an intention from their Action Plan without accomplishing it.',
        'm_parents' => array(5966, 4506, 6150, 4593, 4755),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-clipboard-check"></i>',
        'm_name' => 'Action Plan Answer Question',
        'm_desc' => '',
        'm_parents' => array(6288, 6255, 6244, 6146, 4755, 4593, 4460),
    ),
    6158 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Action Plan Review Outcome',
        'm_desc' => 'The most basic type of intent completion for intents that do not have any messages, completion requirements or children to choose from.',
        'm_parents' => array(6274, 6255, 4593, 4755, 6146),
    ),
    6224 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'My Account Input Iterated',
        'm_desc' => '',
        'm_parents' => array(4755, 6222, 4593),
    ),
    6226 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Intent Tree',
        'm_desc' => ' When all intents within a recursive tree are updated at once.',
        'm_parents' => array(4593),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Tip',
        'm_desc' => '',
        'm_parents' => array(4603, 4595, 4593, 4986, 5005, 4485),
    ),
    6255 => array(
        'm_icon' => '<i class="far fa-calendar-check"></i>',
        'm_name' => 'Action Plan Progression Completion Triggers',
        'm_desc' => '',
        'm_parents' => array(6275, 4506, 4593, 6242, 4527),
    ),
    6278 => array(
        'm_icon' => '<i class="far fa-tachometer-alt"></i>',
        'm_name' => 'Action Plan Evaluate Milestone',
        'm_desc' => '',
        'm_parents' => array(6153, 4229, 4506, 4593),
    ),
    6289 => array(
        'm_icon' => '<i class="fas fa-envelope-open-dollar"></i>',
        'm_name' => 'Action Plan 1-time PayPal Payment',
        'm_desc' => '',
        'm_parents' => array(6291, 6290, 6255, 4755, 4593, 6244, 6146),
    ),
);

//Entity-to-Entity All Link Types:
$config['en_ids_4592'] = array(4230, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4318, 4319);
$config['en_all_4592'] = array(
    4230 => array(
        'm_icon' => '<i class="fal fa-level-up rotate90"></i>',
        'm_name' => 'Raw',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text',
        'm_desc' => '',
        'm_parents' => array(4527, 4593, 4592, 4595, 4331),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'Generic URL',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4537, 4595, 4331),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Embed URL',
        'm_desc' => '',
        'm_parents' => array(4990, 4593, 4592, 4537, 4506, 4595),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => '',
        'm_parents' => array(6203, 4593, 4592, 4537, 4595, 4331),
    ),
    4318 => array(
        'm_icon' => '<i class="fal fa-clock"></i>',
        'm_name' => 'Time',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4319 => array(
        'm_icon' => '<i class="fal fa-sort-numeric-down"></i>',
        'm_name' => 'Integer',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4595),
    ),
);

//Intent Completion Methods:
$config['en_ids_4331'] = array(4255, 4256, 4258, 4259, 4260, 4261, 6087, 6291);
$config['en_all_4331'] = array(
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text',
        'm_desc' => '',
        'm_parents' => array(4527, 4593, 4592, 4595, 4331),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'Generic URL',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4537, 4595, 4331),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => '',
        'm_parents' => array(6203, 4593, 4592, 4537, 4595, 4331),
    ),
    6087 => array(
        'm_icon' => '<i class="fal fa-check"></i>',
        'm_name' => 'No Response',
        'm_desc' => 'Student does not need to submit anything to mark intent as complete.',
        'm_parents' => array(4331),
    ),
    6291 => array(
        'm_icon' => '<i class="far fa-file-invoice-dollar"></i>',
        'm_name' => 'Payment',
        'm_desc' => 'The USD amount students must pay via Paypal to complete the intent and move forward in this direction. Alternatively students can skip the intention tree.',
        'm_parents' => array(4331),
    ),
);

//Platform Objects:
$config['en_ids_4534'] = array(4535, 4536, 6205);
$config['en_all_4534'] = array(
    4535 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intents',
        'm_desc' => '',
        'm_parents' => array(4534, 4463),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entities',
        'm_desc' => '',
        'm_parents' => array(4534, 4463),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Links',
        'm_desc' => '',
        'm_parents' => array(4463, 4534),
    ),
);

//Subscription Settings:
$config['en_ids_4454'] = array(4456, 4457, 4458, 4455);
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
        'm_name' => 'Unsubscribed from Mench',
        'm_desc' => 'Stop all communications until you re-subscribe',
        'm_parents' => array(4593, 4454),
    ),
);

//Intent Notes:
$config['en_ids_4485'] = array(4231, 4983, 4601, 4232, 6242, 6093);
$config['en_all_4485'] = array(
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'Message',
        'm_desc' => 'Delivered in-order when student initially starts this intent. Goal is to give key insights that streamline the execution of the intention.',
        'm_parents' => array(4986, 4603, 4593, 4485, 4595),
    ),
    4983 => array(
        'm_icon' => '<i class="fal fa-thumbs-up"></i>',
        'm_name' => 'Up-Vote',
        'm_desc' => 'Tracks intent correlations mined from expert sources and miner perspectives. Up-votes give crediblity to intent correlations. Never communicated with Students and only used for weighting purposes, like how Google uses link correlations for its pagerank algorithm.',
        'm_parents' => array(4986, 4985, 4595, 4593, 4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-tags"></i>',
        'm_name' => 'Keyword',
        'm_desc' => 'Never delivered to students, instead, it maps alternative ways an intent could be named so we can better understand student commands.',
        'm_parents' => array(4255, 4593, 4595, 4485),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-medal"></i>',
        'm_name' => 'Bonus Tip',
        'm_desc' => 'Delivered in-order and one-by-one (drip-format) either during or after the intent completion. Goal is to re-iterate key insights to help students retain learnings over time.',
        'm_parents' => array(5005, 4986, 4603, 4593, 4485, 4595),
    ),
    6242 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'On-Complete Tip',
        'm_desc' => 'Message delivered to students when they complete an intention.',
        'm_parents' => array(4603, 4595, 4593, 4986, 5005, 4485),
    ),
    6093 => array(
        'm_icon' => '<i class="fal fa-megaphone"></i>',
        'm_name' => 'Changelog',
        'm_desc' => 'Similar to Wikipedia\'s Talk pages, the Mench changelog helps miners track the history and evolution of a intent and explain/propose changes/improvements.',
        'm_parents' => array(4595, 4593, 4986, 5005, 4485),
    ),
);

//Intent-to-Intent Link Types:
$config['en_ids_4486'] = array(4228, 4229);
$config['en_all_4486'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-check-circle"></i>',
        'm_name' => 'Fixed Step',
        'm_desc' => 'Intent link published and added to user Action Plans up-front',
        'm_parents' => array(4593, 4486, 4595),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-spin fa-question-circle"></i>',
        'm_name' => 'Conditional Milestone',
        'm_desc' => 'Intent added to Action Plans after parent intent is complete AND the user\'s % score falls within the defined min/max range',
        'm_parents' => array(4593, 4486, 4595),
    ),
);

//Entity-to-Entity URL Link Types:
$config['en_ids_4537'] = array(4256, 4257, 4258, 4259, 4260, 4261);
$config['en_all_4537'] = array(
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'Generic URL',
        'm_desc' => 'Link note contains a generic URL only.',
        'm_parents' => array(4593, 4592, 4537, 4595, 4331),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Embed URL',
        'm_desc' => 'Link note contain a recognizable URL that offers an embed widget for a more engaging play-back experience.',
        'm_parents' => array(4990, 4593, 4592, 4537, 4506, 4595),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => 'Link notes contain a URL to a raw video file.',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => 'Link notes contain a URL to a raw file.',
        'm_parents' => array(6203, 4593, 4592, 4537, 4595, 4331),
    ),
);

//Expert Sources:
$config['en_ids_3000'] = array(2997, 2998, 2999, 3005, 3147, 3192, 4446, 4763, 4883, 5948);
$config['en_all_3000'] = array(
    2997 => array(
        'm_icon' => '<i class="fal fa-newspaper"></i>',
        'm_name' => 'Expert Articles',
        'm_desc' => '&var_weight=2',
        'm_parents' => array(4990, 3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fal fa-film"></i>',
        'm_name' => 'Expert Videos',
        'm_desc' => '&var_weight=5',
        'm_parents' => array(4990, 3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fal fa-microphone"></i>',
        'm_name' => 'Expert Podcasts',
        'm_desc' => '&var_weight=7',
        'm_parents' => array(4990, 4255, 3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fal fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '&var_weight=100',
        'm_parents' => array(4990, 4255, 3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fal fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '&var_weight=50',
        'm_parents' => array(4990, 4255, 3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fal fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => '&var_weight=1',
        'm_parents' => array(4990, 4255, 3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fal fa-tachometer"></i>',
        'm_name' => 'Expert Assessments',
        'm_desc' => '&var_weight=10',
        'm_parents' => array(4990, 3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fal fa-bullhorn"></i>',
        'm_name' => 'Expert Marketing Channels',
        'm_desc' => '&var_weight=1',
        'm_parents' => array(4990, 4255, 3000),
    ),
    4883 => array(
        'm_icon' => '<i class="fal fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => '&var_weight=1',
        'm_parents' => array(4990, 4255, 3000),
    ),
    5948 => array(
        'm_icon' => '<i class="fal fa-file-invoice"></i>',
        'm_name' => 'Expert Templates',
        'm_desc' => '&var_weight=1',
        'm_parents' => array(4990, 3000),
    ),
);