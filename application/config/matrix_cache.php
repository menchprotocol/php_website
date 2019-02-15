<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Keep a cache of certain parts of the Intent tree for faster processing
 * So we don't have to make DB calls to figure them out every time!
 * This is the cron function that creates this: fn___matrix_cache()
 * See here for all entities cached: https://mench.com/entities/4527
 * use-case format: $this->config->item('en_all_4374')
 *
 * ATTENTION: Also search for "en_ids_" and "en_all_" when trying to manage these throughout the code base
 *
 */

//Generated 2019-02-14 02:28:28 PST

//Transaction Type Coin Rates:
$config['en_ids_4374'] = array(4228, 4229, 4230, 4231, 4232, 4233, 4234, 4235, 4242, 4250, 4251, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4263, 4264, 4318, 4319, 4331, 4460, 4601, 4602);
$config['en_all_4374'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-clipboard-check"></i>',
        'm_name' => 'Linked Intents Pre-Assessment',
        'm_desc' => '100',
        'm_parents' => array(4594, 4486, 4374),
    ),
    4229 => array(
        'm_icon' => '<i class="fal fa-question-circle fa-spin"></i>',
        'm_name' => 'Linked Intents Post-Assessment',
        'm_desc' => '100',
        'm_parents' => array(4594, 4486, 4374),
    ),
    4230 => array(
        'm_icon' => '<i class="fal fa-link"></i>',
        'm_name' => 'Empty Linked Entities',
        'm_desc' => '20',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4231 => array(
        'm_icon' => '<i class="fal fa-bolt"></i>',
        'm_name' => 'Intent Messages On-Start',
        'm_desc' => '100',
        'm_parents' => array(4742, 4603, 4594, 4485, 4374),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-comment-lines"></i>',
        'm_name' => 'Intent Messages Learn More',
        'm_desc' => '100',
        'm_parents' => array(4742, 4603, 4594, 4485, 4374),
    ),
    4233 => array(
        'm_icon' => '<i class="fal fa-calendar-check"></i>',
        'm_name' => 'Intent Messages On-Complete',
        'm_desc' => '100',
        'm_parents' => array(4742, 4603, 4594, 4485, 4374),
    ),
    4234 => array(
        'm_icon' => '<i class="fal fa-random"></i>',
        'm_name' => 'Intent Messages Random1',
        'm_desc' => '100',
        'm_parents' => array(4594, 4485, 4374),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-flag"></i>',
        'm_name' => 'Action Plan',
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
        'm_icon' => '<i class="fal fa-hashtag"></i>',
        'm_name' => 'New Intent',
        'm_desc' => '200',
        'm_parents' => array(4594, 4374),
    ),
    4251 => array(
        'm_icon' => '<i class="fal fa-at"></i>',
        'm_name' => 'New Entity',
        'm_desc' => '50',
        'm_parents' => array(4594, 4374),
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text Linked Entities',
        'm_desc' => '30',
        'm_parents' => array(4594, 4592, 4374, 4331),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL Linked Entities',
        'm_desc' => '50',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Embed Linked Entities',
        'm_desc' => '70',
        'm_parents' => array(4594, 4592, 4537, 4506, 4428, 4374),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video Linked Entities',
        'm_desc' => '90',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio Linked Entities',
        'm_desc' => '50',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image Linked Entities',
        'm_desc' => '50',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File Linked Entities',
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
        'm_name' => 'Time Linked Entities',
        'm_desc' => '20',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4319 => array(
        'm_icon' => '<i class="fal fa-sort-numeric-down"></i>',
        'm_name' => 'Integer Linked Entities',
        'm_desc' => '20',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4331 => array(
        'm_icon' => '<i class="fal fa-tasks"></i>',
        'm_name' => 'Intent Message Formats',
        'm_desc' => '20',
        'm_parents' => array(4594, 4527, 4374),
    ),
    4460 => array(
        'm_icon' => '<i class="fal fa-ballot-check"></i>',
        'm_name' => 'Quick Reply Answer Received',
        'm_desc' => '3',
        'm_parents' => array(4594, 4428, 4374, 4277),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-comment-check"></i>',
        'm_name' => 'Intent Messages Triggers',
        'm_desc' => '50',
        'm_parents' => array(4255, 4594, 4374, 4485),
    ),
    4602 => array(
        'm_icon' => '<i class="fal fa-cloud-upload"></i>',
        'm_name' => 'Intent Messages Webhooks',
        'm_desc' => '100',
        'm_parents' => array(4603, 4256, 4374, 4485, 4594),
    ),
);

//Account Types:
$config['en_ids_4600'] = array(1278, 2750);
$config['en_all_4600'] = array(
    1278 => array(
        'm_icon' => '<i class="fal fa-user"></i>',
        'm_name' => 'People',
        'm_desc' => '',
        'm_parents' => array(4600, 3463),
    ),
    2750 => array(
        'm_icon' => '<i class="fal fa-building"></i>',
        'm_name' => 'Groups',
        'm_desc' => '',
        'm_parents' => array(4600, 3463),
    ),
);

//Transaction Type Full List:
$config['en_ids_4594'] = array(4228, 4229, 4230, 4231, 4232, 4233, 4234, 4235, 4242, 4246, 4248, 4250, 4251, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4263, 4264, 4265, 4266, 4267, 4268, 4269, 4272, 4275, 4278, 4279, 4282, 4283, 4284, 4287, 4299, 4318, 4319, 4331, 4452, 4460, 4547, 4548, 4549, 4550, 4551, 4552, 4553, 4554, 4555, 4556, 4557, 4559, 4567, 4568, 4570, 4577, 4601, 4602);
$config['en_all_4594'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-clipboard-check"></i>',
        'm_name' => 'Linked Intents Pre-Assessment',
        'm_desc' => '',
        'm_parents' => array(4594, 4486, 4374),
    ),
    4229 => array(
        'm_icon' => '<i class="fal fa-question-circle fa-spin"></i>',
        'm_name' => 'Linked Intents Post-Assessment',
        'm_desc' => '',
        'm_parents' => array(4594, 4486, 4374),
    ),
    4230 => array(
        'm_icon' => '<i class="fal fa-link"></i>',
        'm_name' => 'Empty Linked Entities',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4231 => array(
        'm_icon' => '<i class="fal fa-bolt"></i>',
        'm_name' => 'Intent Messages On-Start',
        'm_desc' => '',
        'm_parents' => array(4742, 4603, 4594, 4485, 4374),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-comment-lines"></i>',
        'm_name' => 'Intent Messages Learn More',
        'm_desc' => '',
        'm_parents' => array(4742, 4603, 4594, 4485, 4374),
    ),
    4233 => array(
        'm_icon' => '<i class="fal fa-calendar-check"></i>',
        'm_name' => 'Intent Messages On-Complete',
        'm_desc' => '',
        'm_parents' => array(4742, 4603, 4594, 4485, 4374),
    ),
    4234 => array(
        'm_icon' => '<i class="fal fa-random"></i>',
        'm_name' => 'Intent Messages Random1',
        'm_desc' => '',
        'm_parents' => array(4594, 4485, 4374),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-flag"></i>',
        'm_name' => 'Action Plan',
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
        'm_name' => 'Developer Bug Report',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4248 => array(
        'm_icon' => '<i class="fas fa-star-half-alt"></i>',
        'm_name' => 'Net Promoter Score Received',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4428, 4277),
    ),
    4250 => array(
        'm_icon' => '<i class="fal fa-hashtag"></i>',
        'm_name' => 'New Intent',
        'm_desc' => '',
        'm_parents' => array(4594, 4374),
    ),
    4251 => array(
        'm_icon' => '<i class="fal fa-at"></i>',
        'm_name' => 'New Entity',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(4594, 4374),
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text Linked Entities',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374, 4331),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL Linked Entities',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Embed Linked Entities',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4506, 4428, 4374),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video Linked Entities',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio Linked Entities',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image Linked Entities',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File Linked Entities',
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
        'm_name' => 'New Member Joined',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Messenger Optin',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4268 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Messenger Postback',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4269 => array(
        'm_icon' => '<i class="fal fa-sign-in"></i>',
        'm_name' => 'Matrix login',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4272 => array(
        'm_icon' => '<i class="fas fa-question-circle"></i>',
        'm_name' => 'Action Plan Considered',
        'm_desc' => 'Logged every time a master reviews an intent message while considering to add it to their Action Plan. ',
        'm_parents' => array(4594, 4560, 4428),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Action Plan Searched',
        'm_desc' => 'When the Master invokes the [Lets] command and searches for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(4755, 4594, 4560),
    ),
    4278 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'Message Read',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4279 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'Message Delivered',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4282 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'My Account Opened',
        'm_desc' => '',
        'm_parents' => array(4594, 4428),
    ),
    4283 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'Action Plan Opened',
        'm_desc' => 'Once a Master has added an Intention to their Action Plan, this Transaction will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(4594, 4560),
    ),
    4284 => array(
        'm_icon' => '<i class="fas fa-fast-forward"></i>',
        'm_name' => 'Action Plan Intent Skipped',
        'm_desc' => 'Transaction logged every time the Master decides to skip an Action Plan.',
        'm_parents' => array(4594, 4560),
    ),
    4287 => array(
        'm_icon' => '<i class="fal fa-comment-exclamation"></i>',
        'm_name' => 'Received Unrecognized Message',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4299 => array(
        'm_icon' => '<i class="fal fa-cloud-upload"></i>',
        'm_name' => 'Save URL to Mench Cloud',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4318 => array(
        'm_icon' => '<i class="fal fa-clock"></i>',
        'm_name' => 'Time Linked Entities',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4319 => array(
        'm_icon' => '<i class="fal fa-sort-numeric-down"></i>',
        'm_name' => 'Integer Linked Entities',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4331 => array(
        'm_icon' => '<i class="fal fa-tasks"></i>',
        'm_name' => 'Intent Message Formats',
        'm_desc' => '',
        'm_parents' => array(4594, 4527, 4374),
    ),
    4452 => array(
        'm_icon' => '<i class="fal fa-code"></i>',
        'm_name' => 'Developer Code Github Push',
        'm_desc' => '',
        'm_parents' => array(4594, 4428),
    ),
    4460 => array(
        'm_icon' => '<i class="fal fa-ballot-check"></i>',
        'm_name' => 'Quick Reply Answer Received',
        'm_desc' => '',
        'm_parents' => array(4594, 4428, 4374, 4277),
    ),
    4547 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text Message Received',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4548 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video Message Received',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4549 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio Message Received',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4550 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image Message Received',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4551 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File Message Received',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4552 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text Message Sent',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4553 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video Message Sent',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4554 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio Message Sent',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4555 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image Message Sent',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4556 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File Message Sent',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4557 => array(
        'm_icon' => '<i class="fal fa-location-circle"></i>',
        'm_name' => 'Location Message Received',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4277),
    ),
    4559 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'Action Plan Intent',
        'm_desc' => 'Every Action Plan has a number of intentions that define what the Masters needs to complete in order to accomplish the intention of the Action Plan',
        'm_parents' => array(4594, 4560),
    ),
    4567 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Action Plan Intent Completed',
        'm_desc' => 'When Master marks an Action Plan Intent as Complete.',
        'm_parents' => array(4755, 4594, 4560),
    ),
    4568 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Action Plan Completed',
        'm_desc' => 'When the entire Action Plan tree is marked as Complete.',
        'm_parents' => array(4755, 4594, 4560),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'HTML Message Sent',
        'm_desc' => '',
        'm_parents' => array(4755, 4594, 4280),
    ),
    4577 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Message Request Accepted',
        'm_desc' => '',
        'm_parents' => array(4594),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-comment-check"></i>',
        'm_name' => 'Intent Messages Triggers',
        'm_desc' => '',
        'm_parents' => array(4255, 4594, 4374, 4485),
    ),
    4602 => array(
        'm_icon' => '<i class="fal fa-cloud-upload"></i>',
        'm_name' => 'Intent Messages Webhooks',
        'm_desc' => '',
        'm_parents' => array(4603, 4256, 4374, 4485, 4594),
    ),
);

//Linked Entities Transactions:
$config['en_ids_4592'] = array(4230, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4318, 4319);
$config['en_all_4592'] = array(
    4230 => array(
        'm_icon' => '<i class="fal fa-link"></i>',
        'm_name' => 'Empty',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374),
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374, 4331),
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
        'm_parents' => array(4594, 4592, 4537, 4506, 4428, 4374),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
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

//Intent Message Formats:
$config['en_ids_4331'] = array(4255, 4256, 4258, 4259, 4260, 4261);
$config['en_all_4331'] = array(
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4374, 4331),
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
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => '',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
);

//Mench Database Objects:
$config['en_ids_4534'] = array(4341, 4535, 4536);
$config['en_all_4534'] = array(
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas"></i>',
        'm_name' => 'Ledger Transactions',
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

//Mench Notification Levels:
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

//Intent Messages:
$config['en_ids_4485'] = array(4231, 4234, 4232, 4233, 4601, 4602);
$config['en_all_4485'] = array(
    4231 => array(
        'm_icon' => '<i class="fal fa-bolt"></i>',
        'm_name' => 'On-Start',
        'm_desc' => 'All delivered in-order when student initially starts this intent. Goal is to give key insights that make students more effective in accomplishing the Intent\'s outcome.',
        'm_parents' => array(4742, 4603, 4594, 4485, 4374),
    ),
    4234 => array(
        'm_icon' => '<i class="fal fa-random"></i>',
        'm_name' => 'Random1',
        'm_desc' => 'One message randomly selected right after on-start messages. Goal is to make Mench feel more authentic by mixing things up. Also called in the code-base using compose_message().',
        'm_parents' => array(4594, 4485, 4374),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-comment-lines"></i>',
        'm_name' => 'Learn More',
        'm_desc' => 'Delivered in-order and one-by-one (drip-format) either during or after the intent completion. Goal is to re-iterate key insights to help students retain learnings over time.',
        'm_parents' => array(4742, 4603, 4594, 4485, 4374),
    ),
    4233 => array(
        'm_icon' => '<i class="fal fa-calendar-check"></i>',
        'm_name' => 'On-Complete',
        'm_desc' => 'All delivered in-order as soon as the student marks the intent as complete. Goal is to re-iterate key insights to help students retain learnings.',
        'm_parents' => array(4742, 4603, 4594, 4485, 4374),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-comment-check"></i>',
        'm_name' => 'Triggers',
        'm_desc' => 'Never delivered to students, instead, it maps alternative ways an intent could be referenced to better understand student commands.',
        'm_parents' => array(4255, 4594, 4374, 4485),
    ),
    4602 => array(
        'm_icon' => '<i class="fal fa-cloud-upload"></i>',
        'm_name' => 'Webhooks',
        'm_desc' => 'All URLs called along with POST variables that pass intent and completion details. Goal is to enable additional workflows like issuing a completion certificate.',
        'm_parents' => array(4603, 4256, 4374, 4485, 4594),
    ),
);

//Linked Intents Transactions:
$config['en_ids_4486'] = array(4228, 4229);
$config['en_all_4486'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-clipboard-check"></i>',
        'm_name' => 'Pre-Assessment',
        'm_desc' => 'Intent link published and added to user Action Plans up-front',
        'm_parents' => array(4594, 4486, 4374),
    ),
    4229 => array(
        'm_icon' => '<i class="fal fa-question-circle fa-spin"></i>',
        'm_name' => 'Post-Assessment',
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
        'm_parents' => array(4594, 4592, 4537, 4506, 4428, 4374),
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => 'Link notes contain a URL to a raw video file.',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => 'Link notes contain a URL to a raw file.',
        'm_parents' => array(4594, 4592, 4537, 4374, 4331),
    ),
);

//Expert Sources:
$config['en_ids_3000'] = array(2997, 2998, 2999, 3005, 3147, 4446);
$config['en_all_3000'] = array(
    2997 => array(
        'm_icon' => '<i class="fal fa-newspaper"></i>',
        'm_name' => 'Expert Articles',
        'm_desc' => '&var_weight=2',
        'm_parents' => array(3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fal fa-film"></i>',
        'm_name' => 'Expert Videos',
        'm_desc' => '&var_weight=5',
        'm_parents' => array(3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fal fa-microphone"></i>',
        'm_name' => 'Expert Podcasts',
        'm_desc' => '&var_weight=7',
        'm_parents' => array(3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fal fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '&var_weight=100',
        'm_parents' => array(3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fal fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '&var_weight=50',
        'm_parents' => array(3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fal fa-tachometer"></i>',
        'm_name' => 'Expert Assessments',
        'm_desc' => '&var_weight=10',
        'm_parents' => array(3000),
    ),
);