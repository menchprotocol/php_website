<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Keep a cache of certain parts of the Intent tree for faster processing
 * So we don't have to make DB calls to figure them out every time!
 * This is the cron function that creates this: fn___matrix_cache()
 * See here for all entities cached: https://mench.com/entities/4527
 * use-case format: $this->config->item('en_ids_3000')
 *
 * ATTENTION: Also search for "en_ids_" and "en_all_" when trying to manage these throughout the code base
 *
 */

//Generated 2019-03-18 19:04:55 PST

//User Groups:
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
        'm_name' => 'Mench Open-Source Developers',
        'm_desc' => '',
        'm_parents' => array(4463, 4432, 4426),
    ),
);

//System Modification Lock:
$config['en_ids_5969'] = array(3286);
$config['en_all_5969'] = array(
    3286 => array(
        'm_icon' => '<i class="fal fa-key"></i>',
        'm_name' => 'Matrix Password',
        'm_desc' => 'Managed through the Forgot Password section in the Login page',
        'm_parents' => array(5969, 4755, 4255, 3285),
    ),
);

//Transaction Type Email Subscription:
$config['en_ids_5966'] = array(4246, 4269);
$config['en_all_5966'] = array(
    4246 => array(
        'm_icon' => '<i class="fal fa-bug"></i>',
        'm_name' => 'Reported Bug',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(5966, 4594),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'Logged Into Matrix',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(5966, 4992, 4594),
    ),
);

//Linked Entities Text:
$config['en_ids_4255'] = array(2999, 3005, 3084, 3147, 3192, 3286, 4601, 4763, 4883);
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
        'm_icon' => '<i class="fal fa-key"></i>',
        'm_name' => 'Matrix Password',
        'm_desc' => 'Enter SHA256 encoded password string combined with our SALT variables',
        'm_parents' => array(5969, 4755, 4255, 3285),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-tags"></i>',
        'm_name' => 'Noted Intent Keyword',
        'm_desc' => 'Trigger statements can only contain text to enable Mench to detect alternatives forms a person might reference an intent',
        'm_parents' => array(4255, 4594, 4374, 4485),
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
);

//Advance Mode:
$config['en_ids_5005'] = array(4232, 4233, 4234, 4602, 4604, 4741, 4759, 4997, 5006);
$config['en_all_5005'] = array(
    4232 => array(
        'm_icon' => '<i class="fal fa-medal"></i>',
        'm_name' => 'Noted Intent Bonus Tip',
        'm_desc' => '',
        'm_parents' => array(5005, 4986, 4742, 4603, 4594, 4485, 4374),
    ),
    4233 => array(
        'm_icon' => '<i class="fal fa-clipboard-check"></i>',
        'm_name' => 'Noted Intent Parting Tip',
        'm_desc' => '',
        'm_parents' => array(5005, 4986, 4742, 4603, 4594, 4485, 4374),
    ),
    4234 => array(
        'm_icon' => '<i class="fal fa-random"></i>',
        'm_name' => 'Noted Intent Random Intro',
        'm_desc' => '',
        'm_parents' => array(5005, 4986, 4594, 4485, 4374),
    ),
    4602 => array(
        'm_icon' => '<i class="fal fa-cloud-upload"></i>',
        'm_name' => 'Noted Intent Webhook',
        'm_desc' => '',
        'm_parents' => array(5005, 4986, 4256, 4374, 4485, 4594),
    ),
    4604 => array(
        'm_icon' => '<i class="fal fa-comment-check"></i>',
        'm_name' => 'Intent Completion Requirement',
        'm_desc' => '',
        'm_parents' => array(5005, 4535),
    ),
    4741 => array(
        'm_icon' => '<i class="fas fa-search-dollar"></i>',
        'm_name' => 'Intent Completion Cost',
        'm_desc' => '',
        'm_parents' => array(5005, 4535),
    ),
    4759 => array(
        'm_icon' => '<i class="fas fa-flag"></i>',
        'm_name' => 'Intent Action Plans Widget',
        'm_desc' => '',
        'm_parents' => array(5005, 4535),
    ),
    4997 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Updates',
        'm_desc' => '',
        'm_parents' => array(4506, 4426, 5005, 4527, 4992),
    ),
    5006 => array(
        'm_icon' => '<i class="fas fa-atlas"></i>',
        'm_name' => 'Intent Ledger History',
        'm_desc' => '',
        'm_parents' => array(4535, 5005),
    ),
);

//Entity Mass Updates:
$config['en_ids_4997'] = array(4998, 4999, 5000, 5001, 5003, 5865, 5943, 5981, 5982);
$config['en_all_4997'] = array(
    4998 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Name Prefix',
        'm_desc' => 'Adds string to the beginning of all child entities. Make sure to include a space for it to look good',
        'm_parents' => array(4594, 4997),
    ),
    4999 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Name Postfix',
        'm_desc' => 'Adds string to the end of all child entities',
        'm_parents' => array(4594, 4997),
    ),
    5000 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Name Replace',
        'm_desc' => 'Search for occurance of string in child entity names and if found, updates it with a replacement string',
        'm_parents' => array(4594, 4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Transaction Content Replace',
        'm_desc' => 'Search for occurance of string in child entity transaction contents and if found, updates it with a replacement string',
        'm_parents' => array(4594, 4997),
    ),
    5003 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Status Replace',
        'm_desc' => 'Updates all child entity statuses that match the initial entity status condition',
        'm_parents' => array(4594, 4997),
    ),
    5865 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Transaction Status Replace',
        'm_desc' => 'Updates all child entity transaction statuses that match the initial transaction status condition',
        'm_parents' => array(4594, 4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Icon Update',
        'm_desc' => 'Updates all child entity icons with string which needs to be a valid icon',
        'm_parents' => array(4594, 4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Parent Entity Addition',
        'm_desc' => 'If not already done so, will add searched entity as the parent of all child entities',
        'm_parents' => array(4594, 4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Parent Entity Removal',
        'm_desc' => 'If already added as the parent, this will remove searched entity as the parent of all child entities',
        'm_parents' => array(4594, 4997),
    ),
);

//Entity Message Reference Appendix:
$config['en_ids_4990'] = array(1326, 2793, 2997, 2998, 2999, 3005, 3084, 3147, 3192, 3300, 3301, 3320, 4257, 4258, 4259, 4260, 4446, 4763, 4883, 5948);
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
        'm_parents' => array(4990, 1326, 1326, 2750),
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
        'm_parents' => array(4990, 2750, 1326, 3304),
    ),
    3301 => array(
        'm_icon' => '<i class="fab fa-instagram"></i>',
        'm_name' => 'Instagram',
        'm_desc' => '',
        'm_parents' => array(4990, 1326, 2750),
    ),
    3320 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Messenger',
        'm_desc' => '',
        'm_parents' => array(4990, 1326, 1326, 2750, 2793),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Linked Entities Embed',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4506, 4374),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Linked Entities Video',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Linked Entities Audio',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Linked Entities Image',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
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
$config['en_ids_4426'] = array(1308, 3288, 4374, 4426, 4430, 4433, 4755, 4997, 5969);
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
        'm_parents' => array(4426, 4755, 3285),
    ),
    4374 => array(
        'm_icon' => '<i class="fal fa-coins"></i>',
        'm_name' => 'Transaction Type Coin Awards',
        'm_desc' => 'Only admins can modify coin rates for each transaction type',
        'm_parents' => array(4527, 4595, 4593, 4463, 4426, 4319),
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
        'm_name' => 'Mench Open-Source Developers',
        'm_desc' => 'Requires admin review and approval',
        'm_parents' => array(4463, 4432, 4426),
    ),
    4755 => array(
        'm_icon' => '<i class="fas fa-eye-slash"></i>',
        'm_name' => 'Only Moderators can View',
        'm_desc' => '',
        'm_parents' => array(4426, 4527, 4757),
    ),
    4997 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Updates',
        'm_desc' => '',
        'm_parents' => array(4506, 4426, 5005, 4527, 4992),
    ),
    5969 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'System Modification Lock',
        'm_desc' => '',
        'm_parents' => array(4426, 4527, 4757, 4428),
    ),
);

//Only Moderators can View:
$config['en_ids_4755'] = array(3286, 3288, 4248, 4275, 4460, 4547, 4548, 4549, 4550, 4551, 4552, 4553, 4554, 4555, 4556, 4557, 4567, 4568, 4570, 4783, 5967);
$config['en_all_4755'] = array(
    3286 => array(
        'm_icon' => '<i class="fal fa-key"></i>',
        'm_name' => 'Matrix Password',
        'm_desc' => '',
        'm_parents' => array(5969, 4755, 4255, 3285),
    ),
    3288 => array(
        'm_icon' => '<i class="fal fa-envelope"></i>',
        'm_name' => 'Email Address',
        'm_desc' => '',
        'm_parents' => array(4426, 4755, 3285),
    ),
    4248 => array(
        'm_icon' => '<i class="fas fa-star-half-alt"></i>',
        'm_name' => 'Sent Net Promoter Score',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4428, 4277),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Searched Action Plan Intent',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4560),
    ),
    4460 => array(
        'm_icon' => '<i class="fal fa-ballot-check"></i>',
        'm_name' => 'Sent Quick Reply Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4428, 4374, 4277),
    ),
    4547 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Sent Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4548 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Sent Video Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4549 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Sent Audio Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4550 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Sent Image Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4551 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Sent File Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4552 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Received Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4553 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Received Video Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4554 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Received Audio Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4555 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Received Image Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4556 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Received File Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4557 => array(
        'm_icon' => '<i class="fal fa-location-circle"></i>',
        'm_name' => 'Sent Location Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4567 => array(
        'm_icon' => '<i class="fal fa-check-square"></i>',
        'm_name' => 'Completed Action Plan Step',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4560),
    ),
    4568 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Completed Action Plan Intent',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4560),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'Received HTML Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4783 => array(
        'm_icon' => '<i class="fal fa-phone"></i>',
        'm_name' => 'Phone Number',
        'm_desc' => '',
        'm_parents' => array(4755, 4319, 3285),
    ),
    5967 => array(
        'm_icon' => '<i class="fal fa-envelope-open"></i>',
        'm_name' => 'Received Email Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
);

//Transaction Type Coin Awards:
$config['en_ids_4374'] = array(4228, 4229, 4230, 4231, 4232, 4233, 4234, 4235, 4242, 4250, 4251, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4263, 4264, 4318, 4319, 4331, 4460, 4601, 4602, 4983);
$config['en_all_4374'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-check-circle"></i>',
        'm_name' => 'Linked Intents Fixed',
        'm_desc' => '100',
        'm_parents' => array(4594, 4486, 4374),
    ),
    4229 => array(
        'm_icon' => '<i class="fal fa-spin fa-question-circle"></i>',
        'm_name' => 'Linked Intents Conditional',
        'm_desc' => '100',
        'm_parents' => array(4594, 4486, 4374),
    ),
    4230 => array(
        'm_icon' => '<i class="fal fa-link"></i>',
        'm_name' => 'Linked Entities Raw',
        'm_desc' => '20',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'Noted Intent Message',
        'm_desc' => '100',
        'm_parents' => array(4986, 4742, 4603, 4594, 4485, 4374),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-medal"></i>',
        'm_name' => 'Noted Intent Bonus Tip',
        'm_desc' => '100',
        'm_parents' => array(5005, 4986, 4742, 4603, 4594, 4485, 4374),
    ),
    4233 => array(
        'm_icon' => '<i class="fal fa-clipboard-check"></i>',
        'm_name' => 'Noted Intent Parting Tip',
        'm_desc' => '100',
        'm_parents' => array(5005, 4986, 4742, 4603, 4594, 4485, 4374),
    ),
    4234 => array(
        'm_icon' => '<i class="fal fa-random"></i>',
        'm_name' => 'Noted Intent Random Intro',
        'm_desc' => '100',
        'm_parents' => array(5005, 4986, 4594, 4485, 4374),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-flag"></i>',
        'm_name' => 'Added Action Plan Intent',
        'm_desc' => '1',
        'm_parents' => array(4594, 4560, 4463, 4374),
    ),
    4242 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Transaction',
        'm_desc' => '20',
        'm_parents' => array(4594, 4374),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Created Intent',
        'm_desc' => '200',
        'm_parents' => array(4594, 4374),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Created Entity',
        'm_desc' => '50',
        'm_parents' => array(4594, 4374),
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Linked Entities Text',
        'm_desc' => '30',
        'm_parents' => array(4527, 4594, 4592, 4374, 4331),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'Linked Entities URL',
        'm_desc' => '50',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Linked Entities Embed',
        'm_desc' => '70',
        'm_parents' => array(4990, 4594, 4592, 4537, 4506, 4374),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Linked Entities Video',
        'm_desc' => '90',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Linked Entities Audio',
        'm_desc' => '50',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Linked Entities Image',
        'm_desc' => '50',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Linked Entities File',
        'm_desc' => '50',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4263 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Entity',
        'm_desc' => '20',
        'm_parents' => array(4594, 4374),
    ),
    4264 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Intent',
        'm_desc' => '40',
        'm_parents' => array(4594, 4374),
    ),
    4318 => array(
        'm_icon' => '<i class="fal fa-clock"></i>',
        'm_name' => 'Linked Entities Time',
        'm_desc' => '20',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4319 => array(
        'm_icon' => '<i class="fal fa-sort-numeric-down"></i>',
        'm_name' => 'Linked Entities Integer',
        'm_desc' => '20',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4331 => array(
        'm_icon' => '<i class="fal fa-comment-check"></i>',
        'm_name' => 'Intent Completion Methods',
        'm_desc' => '20',
        'm_parents' => array(4365, 4527, 4374),
    ),
    4460 => array(
        'm_icon' => '<i class="fal fa-ballot-check"></i>',
        'm_name' => 'Sent Quick Reply Message',
        'm_desc' => '3',
        'm_parents' => array(4755, 4594, 4428, 4374, 4277),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-tags"></i>',
        'm_name' => 'Noted Intent Keyword',
        'm_desc' => '50',
        'm_parents' => array(4255, 4594, 4374, 4485),
    ),
    4602 => array(
        'm_icon' => '<i class="fal fa-cloud-upload"></i>',
        'm_name' => 'Noted Intent Webhook',
        'm_desc' => '100',
        'm_parents' => array(5005, 4986, 4256, 4374, 4485, 4594),
    ),
    4983 => array(
        'm_icon' => '<i class="fal fa-thumbs-up"></i>',
        'm_name' => 'Noted Intent Up-Vote',
        'm_desc' => '100',
        'm_parents' => array(4463, 4986, 4985, 4374, 4594, 4485),
    ),
);

//Account Types:
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
        'm_name' => 'Organizations / Groups',
        'm_desc' => '',
        'm_parents' => array(4600),
    ),
);

//Transaction Type Full List:
$config['en_ids_4594'] = array(4228, 4229, 4230, 4231, 4232, 4233, 4234, 4235, 4242, 4246, 4248, 4250, 4251, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4263, 4264, 4265, 4266, 4267, 4268, 4269, 4272, 4275, 4278, 4279, 4282, 4283, 4284, 4287, 4299, 4318, 4319, 4452, 4460, 4547, 4548, 4549, 4550, 4551, 4552, 4553, 4554, 4555, 4556, 4557, 4559, 4567, 4568, 4570, 4577, 4601, 4602, 4983, 4993, 4994, 4996, 4998, 4999, 5000, 5001, 5003, 5007, 5865, 5943, 5967, 5981, 5982);
$config['en_all_4594'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-check-circle"></i>',
        'm_name' => 'Linked Intents Fixed',
        'm_desc' => '',
        'm_parents' => array(4594, 4486, 4374),
    ),
    4229 => array(
        'm_icon' => '<i class="fal fa-spin fa-question-circle"></i>',
        'm_name' => 'Linked Intents Conditional',
        'm_desc' => '',
        'm_parents' => array(4594, 4486, 4374),
    ),
    4230 => array(
        'm_icon' => '<i class="fal fa-link"></i>',
        'm_name' => 'Linked Entities Raw',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'Noted Intent Message',
        'm_desc' => '',
        'm_parents' => array(4986, 4742, 4603, 4594, 4485, 4374),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-medal"></i>',
        'm_name' => 'Noted Intent Bonus Tip',
        'm_desc' => '',
        'm_parents' => array(5005, 4986, 4742, 4603, 4594, 4485, 4374),
    ),
    4233 => array(
        'm_icon' => '<i class="fal fa-clipboard-check"></i>',
        'm_name' => 'Noted Intent Parting Tip',
        'm_desc' => '',
        'm_parents' => array(5005, 4986, 4742, 4603, 4594, 4485, 4374),
    ),
    4234 => array(
        'm_icon' => '<i class="fal fa-random"></i>',
        'm_name' => 'Noted Intent Random Intro',
        'm_desc' => '',
        'm_parents' => array(5005, 4986, 4594, 4485, 4374),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-flag"></i>',
        'm_name' => 'Added Action Plan Intent',
        'm_desc' => 'Created when the Master adds an intent tree to their Action Plan. We will create 1 link for each intent link to create a cache of the intent tree at that point in time.',
        'm_parents' => array(4594, 4560, 4463, 4374),
    ),
    4242 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Transaction',
        'm_desc' => 'Logged for each transaction column that is updated consciously by the user',
        'm_parents' => array(4594, 4374),
    ),
    4246 => array(
        'm_icon' => '<i class="fal fa-bug"></i>',
        'm_name' => 'Reported Bug',
        'm_desc' => '',
        'm_parents' => array(5966, 4594),
    ),
    4248 => array(
        'm_icon' => '<i class="fas fa-star-half-alt"></i>',
        'm_name' => 'Sent Net Promoter Score',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4428, 4277),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Created Intent',
        'm_desc' => '',
        'm_parents' => array(4594, 4374),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Created Entity',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(4594, 4374),
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Linked Entities Text',
        'm_desc' => '',
        'm_parents' => array(4527, 4594, 4592, 4374, 4331),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'Linked Entities URL',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Linked Entities Embed',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4506, 4374),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Linked Entities Video',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Linked Entities Audio',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Linked Entities Image',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Linked Entities File',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4263 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Entity',
        'm_desc' => 'When a Miner modified an entity attribute like Name, Icon or Status.',
        'm_parents' => array(4594, 4374),
    ),
    4264 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Iterated Intent',
        'm_desc' => 'When an intent field is updated',
        'm_parents' => array(4594, 4374),
    ),
    4265 => array(
        'm_icon' => '<i class="fal fa-user-plus"></i>',
        'm_name' => 'Joined Mench as Student',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Opted into Messenger',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Followed Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4268 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Messenger Postback Initiated',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'Logged Into Matrix',
        'm_desc' => '',
        'm_parents' => array(5966, 4992, 4594),
    ),
    4272 => array(
        'm_icon' => '<i class="fas fa-question-circle"></i>',
        'm_name' => 'Considered Action Plan Intent',
        'm_desc' => 'Logged every time a master reviews an intent message while considering to add it to their Action Plan. ',
        'm_parents' => array(4594, 4560, 4428),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Searched Action Plan Intent',
        'm_desc' => 'When the Master invokes the [Lets] command and searches for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(4755, 4594, 4560),
    ),
    4278 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'Read Message',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4279 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'Received Message',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4282 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'Opened My Account',
        'm_desc' => '',
        'm_parents' => array(4594, 4428),
    ),
    4283 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'Opened Action Plan',
        'm_desc' => 'Once a Master has added an Intention to their Action Plan, this Transaction will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(4594, 4560),
    ),
    4284 => array(
        'm_icon' => '<i class="fal fa-fast-forward"></i>',
        'm_name' => 'Skipped Action Plan Step',
        'm_desc' => 'Transaction logged every time the Master decides to skip an Action Plan.',
        'm_parents' => array(4594, 4560),
    ),
    4287 => array(
        'm_icon' => '<i class="fal fa-comment-exclamation"></i>',
        'm_name' => 'Sent Unrecognized Message',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4299 => array(
        'm_icon' => '<i class="fal fa-cloud-upload"></i>',
        'm_name' => 'Requested File Storage',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4318 => array(
        'm_icon' => '<i class="fal fa-clock"></i>',
        'm_name' => 'Linked Entities Time',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4319 => array(
        'm_icon' => '<i class="fal fa-sort-numeric-down"></i>',
        'm_name' => 'Linked Entities Integer',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4452 => array(
        'm_icon' => '<i class="fab fa-git"></i>',
        'm_name' => 'Pushed Code to Github',
        'm_desc' => '',
        'm_parents' => array(4594, 4428),
    ),
    4460 => array(
        'm_icon' => '<i class="fal fa-ballot-check"></i>',
        'm_name' => 'Sent Quick Reply Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4428, 4374, 4277),
    ),
    4547 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Sent Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4548 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Sent Video Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4549 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Sent Audio Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4550 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Sent Image Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4551 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Sent File Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4552 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Received Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4553 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Received Video Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4554 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Received Audio Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4555 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Received Image Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4556 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Received File Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4557 => array(
        'm_icon' => '<i class="fal fa-location-circle"></i>',
        'm_name' => 'Sent Location Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4559 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'Added Action Plan Step',
        'm_desc' => 'Every Action Plan has a number of intentions that define what the Masters needs to complete in order to accomplish the intention of the Action Plan',
        'm_parents' => array(4594, 4560),
    ),
    4567 => array(
        'm_icon' => '<i class="fal fa-check-square"></i>',
        'm_name' => 'Completed Action Plan Step',
        'm_desc' => 'When Master marks an Action Plan Intent as Complete.',
        'm_parents' => array(4755, 4594, 4560),
    ),
    4568 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Completed Action Plan Intent',
        'm_desc' => 'When the entire Action Plan tree is marked as Complete.',
        'm_parents' => array(4755, 4594, 4560),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'Received HTML Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4577 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Accepted Message Request',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-tags"></i>',
        'm_name' => 'Noted Intent Keyword',
        'm_desc' => '',
        'm_parents' => array(4255, 4594, 4374, 4485),
    ),
    4602 => array(
        'm_icon' => '<i class="fal fa-cloud-upload"></i>',
        'm_name' => 'Noted Intent Webhook',
        'm_desc' => '',
        'm_parents' => array(5005, 4986, 4256, 4374, 4485, 4594),
    ),
    4983 => array(
        'm_icon' => '<i class="fal fa-thumbs-up"></i>',
        'm_name' => 'Noted Intent Up-Vote',
        'm_desc' => '',
        'm_parents' => array(4463, 4986, 4985, 4374, 4594, 4485),
    ),
    4993 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Viewed Intent',
        'm_desc' => '',
        'm_parents' => array(4594, 4992),
    ),
    4994 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Viewed Entity',
        'm_desc' => '',
        'm_parents' => array(4594, 4992),
    ),
    4996 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'Logged into Web Action Plan',
        'm_desc' => '',
        'm_parents' => array(4594, 4560),
    ),
    4998 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Entity Name Prefix',
        'm_desc' => '',
        'm_parents' => array(4594, 4997),
    ),
    4999 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Entity Name Postfix',
        'm_desc' => '',
        'm_parents' => array(4594, 4997),
    ),
    5000 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Entity Name Replace',
        'm_desc' => '',
        'm_parents' => array(4594, 4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Transaction Content Replace',
        'm_desc' => '',
        'm_parents' => array(4594, 4997),
    ),
    5003 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Entity Status Replace',
        'm_desc' => '',
        'm_parents' => array(4594, 4997),
    ),
    5007 => array(
        'm_icon' => '<i class="fal fa-expand-arrows"></i>',
        'm_name' => 'Toggled Advance Mode',
        'm_desc' => '',
        'm_parents' => array(4594, 4992),
    ),
    5865 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Transaction Status Replace',
        'm_desc' => '',
        'm_parents' => array(4594, 4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Entity Icon Update',
        'm_desc' => '',
        'm_parents' => array(4594, 4997),
    ),
    5967 => array(
        'm_icon' => '<i class="fal fa-envelope-open"></i>',
        'm_name' => 'Received Email Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    5981 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Parent Entity Addition',
        'm_desc' => '',
        'm_parents' => array(4594, 4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Mass Parent Entity Removal',
        'm_desc' => '',
        'm_parents' => array(4594, 4997),
    ),
);

//Linked Entities Transactions:
$config['en_ids_4592'] = array(4230, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4318, 4319);
$config['en_all_4592'] = array(
    4230 => array(
        'm_icon' => '<i class="fal fa-link"></i>',
        'm_name' => 'Raw',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text',
        'm_desc' => '',
        'm_parents' => array(4527, 4594, 4592, 4374, 4331),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Embed',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4506, 4374),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4318 => array(
        'm_icon' => '<i class="fal fa-clock"></i>',
        'm_name' => 'Time',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4319 => array(
        'm_icon' => '<i class="fal fa-sort-numeric-down"></i>',
        'm_name' => 'Integer',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374),
    ),
);

//Intent Completion Methods:
$config['en_ids_4331'] = array(4255, 4256, 4258, 4259, 4260, 4261, 6087);
$config['en_all_4331'] = array(
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text',
        'm_desc' => '',
        'm_parents' => array(4527, 4594, 4592, 4374, 4331),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => '',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    6087 => array(
        'm_icon' => '<i class="fal fa-check"></i>',
        'm_name' => 'No Response',
        'm_desc' => 'Student does not need to submit anything to mark intent as complete.',
        'm_parents' => array(4331),
    ),
);

//Mench Core Objects:
$config['en_ids_4534'] = array(4341, 4535, 4536);
$config['en_all_4534'] = array(
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas"></i>',
        'm_name' => 'Transactions',
        'm_desc' => '',
        'm_parents' => array(4534, 4463),
    ),
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
);

//Messenger Notification Levels:
$config['en_ids_4454'] = array(4455, 4456, 4457, 4458);
$config['en_all_4454'] = array(
    4455 => array(
        'm_icon' => '<i class="fas fa-ban"></i>',
        'm_name' => 'Unsubscribed from Mench',
        'm_desc' => 'User was connected but requested to be unsubscribed, so we can no longer reach-out to them',
        'm_parents' => array(4454),
    ),
    4456 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Receive Regular Notifications',
        'm_desc' => 'User is connected and will be notified by sound & vibration for new Mench messages',
        'm_parents' => array(4454),
    ),
    4457 => array(
        'm_icon' => '<i class="fal fa-volume-down"></i>',
        'm_name' => 'Receive Silent Push Notifications',
        'm_desc' => 'User is connected and will be notified by on-screen notification only for new Mench messages',
        'm_parents' => array(4454),
    ),
    4458 => array(
        'm_icon' => '<i class="fal fa-volume-mute"></i>',
        'm_name' => 'Do Not Receive Push Notifications',
        'm_desc' => 'User is connected but will not be notified for new Mench messages except the red icon indicator on the Messenger app which would indicate the total number of new messages they have',
        'm_parents' => array(4454),
    ),
);

//Intent Notes:
$config['en_ids_4485'] = array(4231, 4983, 4601, 4232, 4233, 4602, 4234);
$config['en_all_4485'] = array(
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'Message',
        'm_desc' => 'Delivered in-order when student initially starts this intent. Goal is to give key insights that streamline the execution of the intention.',
        'm_parents' => array(4986, 4742, 4603, 4594, 4485, 4374),
    ),
    4983 => array(
        'm_icon' => '<i class="fal fa-thumbs-up"></i>',
        'm_name' => 'Up-Vote',
        'm_desc' => 'Tracks intent correlations mined from expert sources and miner perspectives. Up-votes give crediblity to intent correlations. Never communicated with Students and only used for weighting purposes, like how Google uses link correlations for its pagerank algorithm.',
        'm_parents' => array(4463, 4986, 4985, 4374, 4594, 4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-tags"></i>',
        'm_name' => 'Keyword',
        'm_desc' => 'Never delivered to students, instead, it maps alternative ways an intent could be named so we can better understand student commands.',
        'm_parents' => array(4255, 4594, 4374, 4485),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-medal"></i>',
        'm_name' => 'Bonus Tip',
        'm_desc' => 'Delivered in-order and one-by-one (drip-format) either during or after the intent completion. Goal is to re-iterate key insights to help students retain learnings over time.',
        'm_parents' => array(5005, 4986, 4742, 4603, 4594, 4485, 4374),
    ),
    4233 => array(
        'm_icon' => '<i class="fal fa-clipboard-check"></i>',
        'm_name' => 'Parting Tip',
        'm_desc' => 'All delivered in-order as soon as the student marks the intent as complete. Goal is to re-iterate key insights to help students retain learnings.',
        'm_parents' => array(5005, 4986, 4742, 4603, 4594, 4485, 4374),
    ),
    4602 => array(
        'm_icon' => '<i class="fal fa-cloud-upload"></i>',
        'm_name' => 'Webhook',
        'm_desc' => 'All URLs called along with POST variables that pass intent and completion details. Goal is to enable additional workflows like issuing a completion certificate.',
        'm_parents' => array(5005, 4986, 4256, 4374, 4485, 4594),
    ),
    4234 => array(
        'm_icon' => '<i class="fal fa-random"></i>',
        'm_name' => 'Random Intro',
        'm_desc' => 'One message randomly selected right after on-start messages. Goal is to make Mench feel more authentic by mixing things up. Also called in the code-base using compose_message().',
        'm_parents' => array(5005, 4986, 4594, 4485, 4374),
    ),
);

//Linked Intents Transactions:
$config['en_ids_4486'] = array(4228, 4229);
$config['en_all_4486'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-check-circle"></i>',
        'm_name' => 'Fixed',
        'm_desc' => 'Intent link published and added to user Action Plans up-front',
        'm_parents' => array(4594, 4486, 4374),
    ),
    4229 => array(
        'm_icon' => '<i class="fal fa-spin fa-question-circle"></i>',
        'm_name' => 'Conditional',
        'm_desc' => 'Intent added to Action Plans after parent intent is complete AND the user\'s % score falls within the defined min/max range',
        'm_parents' => array(4594, 4486, 4374),
    ),
);

//URL Linked Entities Transactions:
$config['en_ids_4537'] = array(4256, 4257, 4258, 4259, 4260, 4261);
$config['en_all_4537'] = array(
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL',
        'm_desc' => 'Link note contains a generic URL only.',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Embed',
        'm_desc' => 'Link note contain a recognizable URL that offers an embed widget for a more engaging play-back experience.',
        'm_parents' => array(4990, 4594, 4592, 4537, 4506, 4374),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => 'Link notes contain a URL to a raw video file.',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
        'm_parents' => array(4990, 4594, 4592, 4537, 4374, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => 'Link notes contain a URL to a raw file.',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
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
