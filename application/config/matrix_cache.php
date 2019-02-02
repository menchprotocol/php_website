<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Keep a cache of certain parts of the Intent tree for faster processing
 * So we don't have to make DB calls to figure them out every time!
 * This is the cron function that creates this: fn___matrix_cache()
 * See here for all entities cached: https://mench.com/entities/4527
 * use-case format: $this->config->item('en_all_4594')
 *
 * ATTENTION: Also search for "en_ids_" and "en_all_" when trying to manage these throughout the code base
 *
 */


//Transaction Types Full List:
$config['en_ids_4594'] = array(4228, 4229, 4230, 4231, 4232, 4233, 4234, 4235, 4242, 4246, 4248, 4250, 4251, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4263, 4264, 4265, 4266, 4267, 4268, 4269, 4272, 4275, 4278, 4279, 4281, 4282, 4283, 4284, 4287, 4299, 4318, 4319, 4331, 4452, 4460, 4547, 4548, 4549, 4550, 4551, 4552, 4553, 4554, 4555, 4556, 4557, 4559, 4567, 4568, 4577);
$config['en_all_4594'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-clipboard-check"></i>',
        'm_name' => 'Linked Intents Pre-Assessment',
        'm_desc' => '',
    ),
    4229 => array(
        'm_icon' => '<i class="fal fa-question-circle fa-spin"></i>',
        'm_name' => 'Linked Intents Post-Assessment',
        'm_desc' => '',
    ),
    4230 => array(
        'm_icon' => '<i class="fal fa-link"></i>',
        'm_name' => 'Linked Entities Raw',
        'm_desc' => '',
    ),
    4231 => array(
        'm_icon' => '<i class="fal fa-bolt"></i>',
        'm_name' => 'Intent Message On-Start',
        'm_desc' => '',
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-comment-lines"></i>',
        'm_name' => 'Intent Message Learn More',
        'm_desc' => '',
    ),
    4233 => array(
        'm_icon' => '<i class="fal fa-calendar-check"></i>',
        'm_name' => 'Intent Message On-Complete',
        'm_desc' => '',
    ),
    4234 => array(
        'm_icon' => '<i class="fal fa-random"></i>',
        'm_name' => 'Intent Message Rotating',
        'm_desc' => '',
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-flag"></i>',
        'm_name' => 'Action Plan',
        'm_desc' => 'Created when the Master adds an intent tree to their Action Plan. We will create 1 link for each intent link to create a cache of the intent tree at that point in time.',
    ),
    4242 => array(
        'm_icon' => '<i class="fal fa-plus-hexagon"></i>',
        'm_name' => 'Iterated Transaction',
        'm_desc' => 'Logged for each transaction column that is updated consciously by the user',
    ),
    4246 => array(
        'm_icon' => '<i class="fas fa-bug"></i>',
        'm_name' => 'Developer Bug Report',
        'm_desc' => '',
    ),
    4248 => array(
        'm_icon' => '<i class="fas fa-star-half-alt"></i>',
        'm_name' => 'Net Promoter Score Received',
        'm_desc' => '',
    ),
    4250 => array(
        'm_icon' => '<i class="fal fa-hashtag"></i>',
        'm_name' => 'New Intent',
        'm_desc' => '',
    ),
    4251 => array(
        'm_icon' => '<i class="fal fa-at"></i>',
        'm_name' => 'New Entity',
        'm_desc' => 'Logged when a new entity is created.',
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Linked Entities Text',
        'm_desc' => '',
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'Linked Entities URL',
        'm_desc' => '',
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Linked Entities Embed',
        'm_desc' => '',
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Linked Entities Video',
        'm_desc' => '',
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Linked Entities Audio',
        'm_desc' => '',
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Linked Entities Image',
        'm_desc' => '',
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Linked Entities File',
        'm_desc' => '',
    ),
    4263 => array(
        'm_icon' => '<i class="fal fa-plus-hexagon"></i>',
        'm_name' => 'Iterated Entity',
        'm_desc' => 'When a Miner modified an entity attribute like Name, Icon or Status.',
    ),
    4264 => array(
        'm_icon' => '<i class="fal fa-plus-hexagon"></i>',
        'm_name' => 'Iterated Intent',
        'm_desc' => 'When an intent field is updated',
    ),
    4265 => array(
        'm_icon' => '',
        'm_name' => 'New Member Joined',
        'm_desc' => '',
    ),
    4266 => array(
        'm_icon' => '',
        'm_name' => 'Messenger Optin',
        'm_desc' => '',
    ),
    4267 => array(
        'm_icon' => '',
        'm_name' => 'Messenger Referral',
        'm_desc' => '',
    ),
    4268 => array(
        'm_icon' => '',
        'm_name' => 'Messenger Postback',
        'm_desc' => '',
    ),
    4269 => array(
        'm_icon' => '',
        'm_name' => 'Logged into the matrix',
        'm_desc' => '',
    ),
    4272 => array(
        'm_icon' => '<i class="fas fa-question-circle"></i>',
        'm_name' => 'Action Plan Considered',
        'm_desc' => 'Logged every time a master reviews an intent message while considering to add it to their Action Plan. ',
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-search"></i>',
        'm_name' => 'Action Plan Searched',
        'm_desc' => 'When the Master invokes the [Lets] command and searches for a new intention that they would like to add to their Action Plan.',
    ),
    4278 => array(
        'm_icon' => '',
        'm_name' => 'Message Read',
        'm_desc' => '',
    ),
    4279 => array(
        'm_icon' => '',
        'm_name' => 'Message Delivered',
        'm_desc' => '',
    ),
    4281 => array(
        'm_icon' => '',
        'm_name' => 'Messaged Queued',
        'm_desc' => '',
    ),
    4282 => array(
        'm_icon' => '',
        'm_name' => 'My Account Opened',
        'm_desc' => '',
    ),
    4283 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'Action Plan Opened',
        'm_desc' => 'Once a Master has added an Intention to their Action Plan, this Transaction will be logged every time they access that Action Plan and view its intentions.',
    ),
    4284 => array(
        'm_icon' => '<i class="fas fa-fast-forward"></i>',
        'm_name' => 'Action Plan Intent Skipped',
        'm_desc' => 'Transaction logged every time the Master decides to skip an Action Plan.',
    ),
    4287 => array(
        'm_icon' => '',
        'm_name' => 'Received Unrecognized Message',
        'm_desc' => '',
    ),
    4299 => array(
        'm_icon' => '<i class="fas fa-cloud-upload"></i>',
        'm_name' => 'Save URL to Mench Cloud',
        'm_desc' => '',
    ),
    4318 => array(
        'm_icon' => '<i class="fal fa-clock"></i>',
        'm_name' => 'Linked Entities Time',
        'm_desc' => '',
    ),
    4319 => array(
        'm_icon' => '<i class="fal fa-sort-numeric-down"></i>',
        'm_name' => 'Linked Entities Integer',
        'm_desc' => '',
    ),
    4331 => array(
        'm_icon' => '<i class="fal fa-tasks"></i>',
        'm_name' => 'Intent Completion Requirements',
        'm_desc' => '',
    ),
    4452 => array(
        'm_icon' => '',
        'm_name' => 'Developer Code Github Push',
        'm_desc' => '',
    ),
    4460 => array(
        'm_icon' => '',
        'm_name' => 'Quick Reply Answer Received',
        'm_desc' => '',
    ),
    4547 => array(
        'm_icon' => '',
        'm_name' => 'Text Message Received',
        'm_desc' => '',
    ),
    4548 => array(
        'm_icon' => '',
        'm_name' => 'Video Message Received',
        'm_desc' => '',
    ),
    4549 => array(
        'm_icon' => '',
        'm_name' => 'Audio Message Received',
        'm_desc' => '',
    ),
    4550 => array(
        'm_icon' => '',
        'm_name' => 'Image Message Received',
        'm_desc' => '',
    ),
    4551 => array(
        'm_icon' => '',
        'm_name' => 'File Message Received',
        'm_desc' => '',
    ),
    4552 => array(
        'm_icon' => '',
        'm_name' => 'Text Message Sent',
        'm_desc' => '',
    ),
    4553 => array(
        'm_icon' => '',
        'm_name' => 'Video Message Sent',
        'm_desc' => '',
    ),
    4554 => array(
        'm_icon' => '',
        'm_name' => 'Audio Message Sent',
        'm_desc' => '',
    ),
    4555 => array(
        'm_icon' => '',
        'm_name' => 'Image Message Sent',
        'm_desc' => '',
    ),
    4556 => array(
        'm_icon' => '',
        'm_name' => 'File Message Sent',
        'm_desc' => '',
    ),
    4557 => array(
        'm_icon' => '',
        'm_name' => 'Location Message Received',
        'm_desc' => '',
    ),
    4559 => array(
        'm_icon' => '<i class="fal fa-square"></i>',
        'm_name' => 'Action Plan Intent',
        'm_desc' => 'Every Action Plan has a number of intentions that define what the Masters needs to complete in order to accomplish the intention of the Action Plan',
    ),
    4567 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Action Plan Intent Completed',
        'm_desc' => 'When Master marks an Action Plan Intent as Complete.',
    ),
    4568 => array(
        'm_icon' => '<i class="fas fa-check-square"></i>',
        'm_name' => 'Action Plan Completed',
        'm_desc' => 'When the entire Action Plan tree is marked as Complete.',
    ),
    4577 => array(
        'm_icon' => '',
        'm_name' => 'Message Request Accepted',
        'm_desc' => '',
    ),
);

//All Entity Links:
$config['en_ids_4592'] = array(4230, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4318, 4319);
$config['en_all_4592'] = array(
    4230 => array(
        'm_icon' => '<i class="fal fa-link"></i>',
        'm_name' => 'Raw',
        'm_desc' => '',
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text',
        'm_desc' => '',
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Embed',
        'm_desc' => '',
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => '',
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => '',
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => '',
    ),
    4318 => array(
        'm_icon' => '<i class="fal fa-clock"></i>',
        'm_name' => 'Time',
        'm_desc' => '',
    ),
    4319 => array(
        'm_icon' => '<i class="fal fa-sort-numeric-down"></i>',
        'm_name' => 'Integer',
        'm_desc' => '',
    ),
);

//Intent Completion Requirements:
$config['en_ids_4331'] = array(4255, 4256, 4258, 4259, 4260, 4261);
$config['en_all_4331'] = array(
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text',
        'm_desc' => '',
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => '',
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => '',
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => '',
    ),
);

//Mench Database Objects:
$config['en_ids_4534'] = array(4341, 4535, 4536);
$config['en_all_4534'] = array(
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas"></i>',
        'm_name' => 'Ledger Transactions',
        'm_desc' => '',
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intents',
        'm_desc' => '',
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entities',
        'm_desc' => '',
    ),
);

//Mench Notification Levels:
$config['en_ids_4454'] = array(4455, 4456, 4457, 4458);
$config['en_all_4454'] = array(
    4455 => array(
        'm_icon' => '<i class="fas fa-minus-circle"></i>',
        'm_name' => 'Unsubscribed from Mench',
        'm_desc' => 'User was connected but requested to be unsubscribed, so we can no longer reach-out to them',
    ),
    4456 => array(
        'm_icon' => '<i class="fas fa-bell"></i>',
        'm_name' => 'Receive Regular Notifications',
        'm_desc' => 'User is connected and will be notified by sound & vibration for new Mench messages',
    ),
    4457 => array(
        'm_icon' => '<i class="fal fa-bell"></i>',
        'm_name' => 'Receive Silent Push Notifications',
        'm_desc' => 'User is connected and will be notified by on-screen notification only for new Mench messages',
    ),
    4458 => array(
        'm_icon' => '<i class="fas fa-bell-slash"></i>',
        'm_name' => 'Do Not Receive Push Notifications',
        'm_desc' => 'User is connected but will not be notified for new Mench messages except the red icon indicator on the Messenger app which would indicate the total number of new messages they have',
    ),
);

//Intent Messages:
$config['en_ids_4485'] = array(4231, 4232, 4233, 4234);
$config['en_all_4485'] = array(
    4231 => array(
        'm_icon' => '<i class="fal fa-bolt"></i>',
        'm_name' => 'On-Start',
        'm_desc' => 'Mench dispatches these messages, in order, when the Master reaches the intent that this message is assigned to. Miners write or upload media to create these messages, and their goal is/should-be to give an introduction of the intention, why its important and the latest overview of how Mench will empower the Master to accomplish the intent. On-start messaged are listed on the intent landing pages e.g. https://mench.com/6903 while also being dispatched when a Master is considering to add a new intent to their Action Plan. These on-start messages give them an overview of what to expect with this intent.',
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-comment-lines"></i>',
        'm_name' => 'Learn More',
        'm_desc' => 'Authored by Miners and ordered, [Learn More] messages offer Masters a Quick Reply options to get more perspectives on the intention with an additional message batch. If Masters choose to move on without learning more, Mench will communicate the message batch at a later time to deliver the extra perspective on the intention. This is known as "dripping content" that helps re-enforce their learnings and act as a effective reminder of the best-practice, and perhaps a a new twist on how to execute towards it. Learn-More messages will always be delivered, the Master chooses the timing of it.',
    ),
    4233 => array(
        'm_icon' => '<i class="fal fa-calendar-check"></i>',
        'm_name' => 'On-Complete',
        'm_desc' => 'Authored by Miners, these messages are dispatched in-order as a batch of knowledge as soon as the Intent is marked as complete by the Master. On-complete messages can re-iterate key insights to help Masters retain their learnings.',
    ),
    4234 => array(
        'm_icon' => '<i class="fal fa-random"></i>',
        'm_name' => 'Rotating',
        'm_desc' => 'Triggered in various spots of the code base that powers the logic of Mench personal assistant. Search for the compose_messages() function which is part of the Comm Model.',
    ),
);

//All Intent Links:
$config['en_ids_4486'] = array(4228, 4229);
$config['en_all_4486'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-clipboard-check"></i>',
        'm_name' => 'Pre-Assessment',
        'm_desc' => 'Intent link published and added to user Action Plans up-front',
    ),
    4229 => array(
        'm_icon' => '<i class="fal fa-question-circle fa-spin"></i>',
        'm_name' => 'Post-Assessment',
        'm_desc' => 'Intent added to Action Plans after parent intent is complete AND the user\'s % score falls within the defined min/max range',
    ),
);

//URL-based entity links:
$config['en_ids_4537'] = array(4256, 4257, 4258, 4259, 4260, 4261);
$config['en_all_4537'] = array(
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL',
        'm_desc' => 'Link note contains a generic URL only.',
    ),
    4257 => array(
        'm_icon' => '<i class="fal fa-play-circle"></i>',
        'm_name' => 'Embed',
        'm_desc' => 'Link note contain a recognizable URL that offers an embed widget for a more engaging play-back experience.',
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video',
        'm_desc' => 'Link notes contain a URL to a raw video file.',
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => 'Link notes contain a URL to a raw file.',
    ),
);

//Expert Sources:
$config['en_ids_3000'] = array(2997, 2998, 2999, 3005, 3147, 4446);
$config['en_all_3000'] = array(
    2997 => array(
        'm_icon' => '<i class="fas fa-newspaper"></i>',
        'm_name' => 'Articles',
        'm_desc' => '&weight=2',
    ),
    2998 => array(
        'm_icon' => '<i class="fab fa-youtube"></i>',
        'm_name' => 'Videos',
        'm_desc' => '&weight=5',
    ),
    2999 => array(
        'm_icon' => '<i class="fas fa-microphone"></i>',
        'm_name' => 'Podcasts',
        'm_desc' => '&weight=7',
    ),
    3005 => array(
        'm_icon' => '<i class="fas fa-book"></i>',
        'm_name' => 'Books',
        'm_desc' => '&weight=100',
    ),
    3147 => array(
        'm_icon' => '<i class="fas fa-presentation"></i>',
        'm_name' => 'Online Courses',
        'm_desc' => '&weight=50',
    ),
    4446 => array(
        'm_icon' => '<i class="fas fa-tachometer"></i>',
        'm_name' => 'Assessments',
        'm_desc' => '&weight=10',
    ),
);