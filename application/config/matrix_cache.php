<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Keep a cache of certain parts of the Intent tree for faster processing
 * So we don't have to make DB calls to figure them out every time!
 * This is the cron function that creates this: fn___matrix_cache()
 * See here for all entities cached: https://mench.com/entities/4527
 * use-case format: $this->config->item('en_all_4534')
 *
 * ATTENTION: Also search for "en_ids_" and "en_all_" when trying to manage these throughout the code base
 *
 */


//Mench Core Objects:
$config['en_ids_4534'] = array(4341, 4535, 4536);
$config['en_all_4534'] = array(
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas"></i>',
        'm_name' => 'Ledger',
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

//Master Sent Message Transactions:
$config['en_ids_4280'] = array(4276, 4552, 4553, 4554, 4555, 4556);
$config['en_all_4280'] = array(
    4276 => array(
        'm_icon' => '',
        'm_name' => 'Email Message Sent',
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
);

//Master Received Message Transactions:
$config['en_ids_4277'] = array(4248, 4460, 4547, 4548, 4549, 4550, 4551, 4557);
$config['en_all_4277'] = array(
    4248 => array(
        'm_icon' => '<i class="fas fa-star-half-alt"></i>',
        'm_name' => 'Net Promoter Score Received',
        'm_desc' => 'Logged when masters submit their Net Promoter Score rating of Mench that shares how likely are they to share Mench with a friend from a scale of 1-10.',
    ),
    4460 => array(
        'm_icon' => '',
        'm_name' => 'Quick Reply Answer Received',
        'm_desc' => 'When Master chooses their answer to an OR branch',
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
    4557 => array(
        'm_icon' => '',
        'm_name' => 'Location Message Received',
        'm_desc' => '',
    ),
);

//Intent Completion Requirements:
$config['en_ids_4331'] = array(4255, 4256, 4258, 4259, 4260, 4261);
$config['en_all_4331'] = array(
    4255 => array(
        'm_icon' => '<i class="fal fa-comment-alt-lines"></i>',
        'm_name' => 'Text Message Entity Link',
        'm_desc' => '',
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-link"></i>',
        'm_name' => 'URL Entity Link',
        'm_desc' => '',
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-file-video"></i>',
        'm_name' => 'Video Entity Link',
        'm_desc' => '',
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-file-audio"></i>',
        'm_name' => 'Audio Entity Link',
        'm_desc' => '',
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-file-image"></i>',
        'm_name' => 'Image Entity Link',
        'm_desc' => '',
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File Entity Link',
        'm_desc' => '',
    ),
);

//Non-URL Entity Links:
$config['en_ids_4538'] = array(4230, 4255, 4318, 4319);
$config['en_all_4538'] = array(
    4230 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Naked Entity Link',
        'm_desc' => 'Entity is linked to another entity with no link notes.',
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-comment-alt-lines"></i>',
        'm_name' => 'Text Message Entity Link',
        'm_desc' => 'Link note contains a Text Message.',
    ),
    4318 => array(
        'm_icon' => '<i class="fas fa-calendar"></i>',
        'm_name' => 'Time Entity Link',
        'm_desc' => '',
    ),
    4319 => array(
        'm_icon' => '<i class="fas fa-sort-numeric-down"></i>',
        'm_name' => 'Number Entity Link',
        'm_desc' => '',
    ),
);

//URL Entity Links:
$config['en_ids_4537'] = array(4256, 4257, 4258, 4259, 4260, 4261);
$config['en_all_4537'] = array(
    4256 => array(
        'm_icon' => '<i class="fal fa-link"></i>',
        'm_name' => 'URL Entity Link',
        'm_desc' => 'Link note contains a generic URL only.',
    ),
    4257 => array(
        'm_icon' => '<i class="fas fa-file-code"></i>',
        'm_name' => 'Embed URL Entity Link',
        'm_desc' => 'Link note contain a recognizable URL that offers an embed widget for a more engaging play-back experience.',
    ),
    4258 => array(
        'm_icon' => '<i class="fal fa-file-video"></i>',
        'm_name' => 'Video Entity Link',
        'm_desc' => 'Link notes contain a URL to a raw video file.',
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-file-audio"></i>',
        'm_name' => 'Audio Entity Link',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-file-image"></i>',
        'm_name' => 'Image Entity Link',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File Entity Link',
        'm_desc' => 'Link notes contain a URL to a raw file.',
    ),
);

//Content Reference Types:
$config['en_ids_3000'] = array(2997, 2998, 2999, 3005, 3147, 3192, 4446);
$config['en_all_3000'] = array(
    2997 => array(
        'm_icon' => '<i class="fas fa-newspaper"></i>',
        'm_name' => 'Articles',
        'm_desc' => '',
    ),
    2998 => array(
        'm_icon' => '<i class="fab fa-youtube"></i>',
        'm_name' => 'Videos',
        'm_desc' => '',
    ),
    2999 => array(
        'm_icon' => '<i class="fas fa-microphone"></i>',
        'm_name' => 'Podcast',
        'm_desc' => '',
    ),
    3005 => array(
        'm_icon' => '<i class="fas fa-book"></i>',
        'm_name' => 'Books',
        'm_desc' => '',
    ),
    3147 => array(
        'm_icon' => '<i class="fas fa-presentation"></i>',
        'm_name' => 'Online Courses',
        'm_desc' => '',
    ),
    3192 => array(
        'm_icon' => '<i class="fas fa-wrench"></i>',
        'm_name' => 'Tools',
        'm_desc' => 'Websites, guides, software or any other tool that could be used to streamline progress.',
    ),
    4446 => array(
        'm_icon' => '<i class="fas fa-tachometer"></i>',
        'm_name' => 'Assessments',
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
        'm_icon' => '<i class="fas fa-bolt"></i>',
        'm_name' => 'On-Start Intent Message',
        'm_desc' => 'Mench dispatches these messages, in order, when the Master reaches the intent that this message is assigned to. Miners write or upload media to create these messages, and their goal is/should-be to give an introduction of the intention, why its important and the latest overview of how Mench will empower the Master to accomplish the intent. On-start messaged are listed on the intent landing pages e.g. https://mench.com/6903 while also being dispatched when a Master is considering to add a new intent to their Action Plan. These on-start messages give them an overview of what to expect with this intent.',
    ),
    4232 => array(
        'm_icon' => '<i class="fas fa-comment-lines"></i>',
        'm_name' => 'Learn More Intent Message',
        'm_desc' => 'Authored by Miners and ordered, [Learn More] messages offer Students a Quick Reply options to get more perspectives on the intention with an additional message batch. If Students choose to move on without learning more, Mench will communicate the message batch at a later time to deliver the extra perspective on the intention. This is known as "dripping content" that helps re-enforce their learnings and act as a effective reminder of the best-practice, and perhaps a a new twist on how to execute towards it. Learn-More messages will always be delivered, the Master chooses the timing of it.',
    ),
    4233 => array(
        'm_icon' => '<i class="fas fa-calendar-check"></i>',
        'm_name' => 'On-Complete Intent Message',
        'm_desc' => 'Authored by Miners, these messages are dispatched in-order as a batch of knowledge as soon as the Intent is marked as complete by the Master. On-complete messages can re-iterate key insights to help Students retain their learnings.',
    ),
    4234 => array(
        'm_icon' => '<i class="fas fa-random"></i>',
        'm_name' => 'Rotating Intent Message',
        'm_desc' => 'Triggered in various spots of the code base that powers the logic of Mench personal assistant. Search for the compose_messages() function which is part of the Comm Model.',
    ),
);

//Intent Links:
$config['en_ids_4486'] = array(4228, 4229);
$config['en_all_4486'] = array(
    4228 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Fixed Intent Link',
        'm_desc' => 'Intent link published and added to user Action Plans up-front',
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-question-circle fa-spin"></i>',
        'm_name' => 'Conditional Intent Link',
        'm_desc' => 'Intent added to Action Plans after parent intent is complete AND the user\'s % score falls within the defined min/max range',
    ),
);

//Intent Settings:
$config['en_ids_4487'] = array(4331, 4332);
$config['en_all_4487'] = array(
    4331 => array(
        'm_icon' => '<i class="fas fa-clipboard-list"></i>',
        'm_name' => 'Intent Completion Requirements',
        'm_desc' => 'If applied as the parent of a child intent, would limit the type of responses users can submit for that intent when marking it as complete. Multiple links will enable multiple response types to be accepted, which the user will be informed by Mench.',
    ),
    4332 => array(
        'm_icon' => '<i class="fas fa-cloud-upload"></i>',
        'm_name' => 'Intent Webhook',
        'm_desc' => 'If set as the parent of an intent, would call the corresponding webhook URL and pass-on the user submission data for processing via the webhook.',
    ),
);