<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Keep a cache of certain parts of the Intent tree for faster processing
 * So we don't have to make DB calls to figure them out every time!
 * This is the cron function that creates this: matrix_cache()
 * See here for all entities cached: https://mench.com/entities/4527
 * use-case format: $this->config->item('en_all_4538')
 *
 * ATTENTION: Also search for "en_ids_" and "en_all_" when trying to manage these throughout the code base
 *
 */


//Messages Sent:
$config['en_ids_4280'] = array(4552, 4553, 4554, 4555, 4556);
$config['en_all_4280'] = array(
    4552 => array(
        'en_icon' => '',
        'en_name' => 'Text Message Sent',
        'tr_content' => '',
    ),
    4553 => array(
        'en_icon' => '',
        'en_name' => 'Video Message Sent',
        'tr_content' => '',
    ),
    4554 => array(
        'en_icon' => '',
        'en_name' => 'Audio Message Sent',
        'tr_content' => '',
    ),
    4555 => array(
        'en_icon' => '',
        'en_name' => 'Image Message Sent',
        'tr_content' => '',
    ),
    4556 => array(
        'en_icon' => '',
        'en_name' => 'File Message Sent',
        'tr_content' => '',
    ),
);

//Messages Received:
$config['en_ids_4277'] = array(4547, 4548, 4549, 4550, 4551, 4557, 4460);
$config['en_all_4277'] = array(
    4547 => array(
        'en_icon' => '',
        'en_name' => 'Text Message Received',
        'tr_content' => '',
    ),
    4548 => array(
        'en_icon' => '',
        'en_name' => 'Video Message Received',
        'tr_content' => '',
    ),
    4549 => array(
        'en_icon' => '',
        'en_name' => 'Audio Message Received',
        'tr_content' => '',
    ),
    4550 => array(
        'en_icon' => '',
        'en_name' => 'Image Message Received',
        'tr_content' => '',
    ),
    4551 => array(
        'en_icon' => '',
        'en_name' => 'File Message Received',
        'tr_content' => '',
    ),
    4557 => array(
        'en_icon' => '',
        'en_name' => 'Location Message Received',
        'tr_content' => '',
    ),
    4460 => array(
        'en_icon' => '',
        'en_name' => 'Quick Reply Answer Received',
        'tr_content' => '',
    ),
);

//Intent Response Limiters:
$config['en_ids_4331'] = array(4255, 4256);
$config['en_all_4331'] = array(
    4255 => array(
        'en_icon' => '<i class="fas fa-file-alt"></i>',
        'en_name' => 'Text Snippet',
        'tr_content' => 'At-least 10 characters or 2+ words of text',
    ),
    4256 => array(
        'en_icon' => '<i class="fas fa-external-link"></i>',
        'en_name' => 'Generic URL',
        'tr_content' => 'A URL that is not any other type of URL',
    ),
);

//Entity Non-URL Links:
$config['en_ids_4538'] = array(4230, 4255, 4318, 4319);
$config['en_all_4538'] = array(
    4230 => array(
        'en_icon' => '<i class="fas fa-link"></i>',
        'en_name' => 'Naked Link',
        'tr_content' => 'Entity is linked to another entity with no link notes.',
    ),
    4255 => array(
        'en_icon' => '<i class="fas fa-file-alt"></i>',
        'en_name' => 'Multi-word Text Link',
        'tr_content' => 'Link note contains a text snippet.',
    ),
    4318 => array(
        'en_icon' => '<i class="fas fa-calendar"></i>',
        'en_name' => 'Date & Time Link',
        'tr_content' => '',
    ),
    4319 => array(
        'en_icon' => '<i class="fas fa-sort-numeric-down"></i>',
        'en_name' => 'Number Link',
        'tr_content' => '',
    ),
);

//Entity URL Links:
$config['en_ids_4537'] = array(4256, 4257, 4258, 4259, 4260, 4261);
$config['en_all_4537'] = array(
    4256 => array(
        'en_icon' => '<i class="fas fa-external-link"></i>',
        'en_name' => 'Generic URL',
        'tr_content' => 'Link note contains a generic URL only.',
    ),
    4257 => array(
        'en_icon' => '<i class="fas fa-file-code"></i>',
        'en_name' => 'Embed Widget URL',
        'tr_content' => 'Link note contain a recognizable URL that offers an embed widget for a more engaging play-back experience.',
    ),
    4258 => array(
        'en_icon' => '<i class="fas fa-file-video"></i>',
        'en_name' => 'Video URL',
        'tr_content' => 'Link notes contain a URL to a raw video file.',
    ),
    4259 => array(
        'en_icon' => '<i class="fas fa-file-audio"></i>',
        'en_name' => 'Audio URL',
        'tr_content' => 'Link notes contain a URL to a raw audio file.',
    ),
    4260 => array(
        'en_icon' => '<i class="fas fa-file-image"></i>',
        'en_name' => 'Image URL',
        'tr_content' => 'Link notes contain a URL to a raw image file.',
    ),
    4261 => array(
        'en_icon' => '<i class="fas fa-file-pdf"></i>',
        'en_name' => 'File URL',
        'tr_content' => 'Link notes contain a URL to a raw file.',
    ),
);

//Content Reference Types:
$config['en_ids_3000'] = array(2997, 2998, 2999, 3005, 3147, 3192, 4446);
$config['en_all_3000'] = array(
    2997 => array(
        'en_icon' => '<i class="fas fa-newspaper"></i>',
        'en_name' => 'Articles',
        'tr_content' => '',
    ),
    2998 => array(
        'en_icon' => '<i class="fab fa-youtube"></i>',
        'en_name' => 'Videos',
        'tr_content' => '',
    ),
    2999 => array(
        'en_icon' => '<i class="fas fa-microphone"></i>',
        'en_name' => 'Podcast',
        'tr_content' => '',
    ),
    3005 => array(
        'en_icon' => '<i class="fas fa-book"></i>',
        'en_name' => 'Books',
        'tr_content' => '',
    ),
    3147 => array(
        'en_icon' => '<i class="fas fa-presentation"></i>',
        'en_name' => 'Online Courses',
        'tr_content' => '',
    ),
    3192 => array(
        'en_icon' => '<i class="fas fa-wrench"></i>',
        'en_name' => 'Tools',
        'tr_content' => 'Websites, guides, software or any other tool that could be used to streamline progress.',
    ),
    4446 => array(
        'en_icon' => '<i class="fas fa-tachometer"></i>',
        'en_name' => 'Assessments',
        'tr_content' => '',
    ),
);

//Mench Communication Levels:
$config['en_ids_4454'] = array(4455, 4456, 4457, 4458);
$config['en_all_4454'] = array(
    4455 => array(
        'en_icon' => '<i class="fas fa-minus-circle"></i>',
        'en_name' => 'Unsubscribed from Mench',
        'tr_content' => 'User was connected but requested to be unsubscribed, so we can no longer reach-out to them',
    ),
    4456 => array(
        'en_icon' => '<i class="fas fa-bell"></i>',
        'en_name' => 'Receive Regular Notifications',
        'tr_content' => 'User is connected and will be notified by sound & vibration for new Mench messages',
    ),
    4457 => array(
        'en_icon' => '<i class="fal fa-bell"></i>',
        'en_name' => 'Receive Silent Push Notifications',
        'tr_content' => 'User is connected and will be notified by on-screen notification only for new Mench messages',
    ),
    4458 => array(
        'en_icon' => '<i class="fas fa-bell-slash"></i>',
        'en_name' => 'Do Not Receive Push Notifications',
        'tr_content' => 'User is connected but will not be notified for new Mench messages except the red icon indicator on the Messenger app which would indicate the total number of new messages they have',
    ),
);

//Intent Messages:
$config['en_ids_4485'] = array(4231, 4232, 4233, 4234);
$config['en_all_4485'] = array(
    4231 => array(
        'en_icon' => '<i class="fas fa-bolt"></i>',
        'en_name' => 'On-Start Intent Message',
        'tr_content' => 'Mench dispatches these messages, in order, when the Master reaches the intent that this message is assigned to. Miners write or upload media to create these messages, and their goal is/should-be to give an introduction of the intention, why its important and the latest overview of how Mench will empower the Master to accomplish the intent. On-start messaged are listed on the intent landing pages e.g. https://mench.com/6903 while also being dispatched when a Master is considering to add a new intent to their Action Plan. These on-start messages give them an overview of what to expect with this intent.',
    ),
    4232 => array(
        'en_icon' => '<i class="fas fa-comment-lines"></i>',
        'en_name' => 'Learn More Intent Message',
        'tr_content' => 'Authored by Miners and ordered, [Learn More] messages offer Masters a Quick Reply options to get more perspectives on the intention with an additional message batch. If Masters choose to move on without learning more, Mench will communicate the message batch at a later time to deliver the extra perspective on the intention. This is known as "dripping content" that helps re-enforce their learnings and act as a effective reminder of the best-practice, and perhaps a a new twist on how to execute towards it. Learn-More messages will always be delivered, the Master chooses the timing of it.',
    ),
    4233 => array(
        'en_icon' => '<i class="fas fa-calendar-check"></i>',
        'en_name' => 'On-Complete Intent Message',
        'tr_content' => 'Authored by Miners, these messages are dispatched in-order as a batch of knowledge as soon as the Intent is marked as complete by the Master. On-complete messages can re-iterate key insights to help Masters retain their learnings.',
    ),
    4234 => array(
        'en_icon' => '<i class="fas fa-random"></i>',
        'en_name' => 'Rotating Intent Message',
        'tr_content' => 'Triggered in various spots of the code base that powers the logic of Mench personal assistant. Search for the compose_messages() function which is part of the Comm Model.',
    ),
);

//Intent Link Types:
$config['en_ids_4486'] = array(4228, 4229);
$config['en_all_4486'] = array(
    4228 => array(
        'en_icon' => '<i class="fas fa-link"></i>',
        'en_name' => 'Fixed Intent Link',
        'tr_content' => 'Intent link published and added to user Action Plans up-front',
    ),
    4229 => array(
        'en_icon' => '<i class="fas fa-question-circle fa-spin"></i>',
        'en_name' => 'Conditional Intent Link',
        'tr_content' => 'Intent added to Action Plans after parent intent is complete AND the user\'s % score falls within the defined min/max range',
    ),
);

//Intent Settings:
$config['en_ids_4487'] = array(4331, 4332);
$config['en_all_4487'] = array(
    4331 => array(
        'en_icon' => '<i class="fas fa-clipboard-list"></i>',
        'en_name' => 'Intent Response Limiters',
        'tr_content' => 'If applied as the parent of a child intent, would limit the type of responses users can submit for that intent when marking it as complete. Multiple links will enable multiple response types to be accepted, which the user will be informed by Mench.',
    ),
    4332 => array(
        'en_icon' => '<i class="fas fa-cloud-upload"></i>',
        'en_name' => 'Intent Webhook',
        'tr_content' => 'If set as the parent of an intent, would call the corresponding webhook URL and pass-on the user submission data for processing via the webhook.',
    ),
);