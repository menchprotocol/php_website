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

//Generated 2019-05-14 14:57:50 PST

//Zero Time Estimate:
$config['en_ids_6766'] = array(6686);
$config['en_all_6766'] = array(
    6686 => array(
        'm_icon' => '<i class="fal fa-dice"></i>',
        'm_name' => 'OR Intent Random Selection',
        'm_desc' => '',
        'm_parents' => array(6766, 6193),
    ),
);

//Mench Contributors:
$config['en_ids_6827'] = array(1281, 1308, 4433, 6695, 6875);
$config['en_all_6827'] = array(
    1281 => array(
        'm_icon' => '<i class="fal fa-shield"></i>',
        'm_name' => 'Core Contributors',
        'm_desc' => 'The dedicated team that ensures the continuous operation of the Mench platform, governs its principles and empowers the rest of the community to achieve their full potential',
        'm_parents' => array(6827, 4463),
    ),
    1308 => array(
        'm_icon' => '<i class="fal fa-badge-check"></i>',
        'm_name' => 'Certified Miners',
        'm_desc' => 'Those who have completed the intention to become a Mench miner and have passed the assessment that validated their skills and understanding of the mining principles',
        'm_parents' => array(6827, 4463, 4426),
    ),
    4433 => array(
        'm_icon' => '<i class="fal fa-code"></i>',
        'm_name' => 'Open-Source Developers',
        'm_desc' => 'Those contributing to our open-source code base hosted on GitHub',
        'm_parents' => array(6827, 4463, 4426),
    ),
    6695 => array(
        'm_icon' => '<i class="fal fa-briefcase"></i>',
        'm_name' => 'Partner Employers',
        'm_desc' => 'Those who use Mench as a recruitment platform to assess their candidates and reach new candidates',
        'm_parents' => array(6827, 4426, 4463),
    ),
    6875 => array(
        'm_icon' => '<i class="fal fa-whistle"></i>',
        'm_name' => 'Career Coaches',
        'm_desc' => 'Coaches experts in the career development process that would help you land your dream job in the shortest possible time',
        'm_parents' => array(6827),
    ),
);

//Focus Entities:
$config['en_ids_3463'] = array(3000, 4432, 6827);
$config['en_all_3463'] = array(
    3000 => array(
        'm_icon' => '<i class="far fa-star"></i>',
        'm_name' => 'Expert Sources',
        'm_desc' => 'Mediums to access expert intelligence like books, videos, articles, courses, etc...',
        'm_parents' => array(3463, 4506, 4527, 4463),
    ),
    4432 => array(
        'm_icon' => '<i class="far fa-certificate"></i>',
        'm_name' => 'Verified Accounts',
        'm_desc' => 'Organizes humans into groups based on activity, location or other preferences.',
        'm_parents' => array(2738, 4527, 3463),
    ),
    6827 => array(
        'm_icon' => '<i class="far fa-plus-circle"></i>',
        'm_name' => 'Contributors',
        'm_desc' => 'Those who actively empower the Mench platform',
        'm_parents' => array(4527, 3463, 2738),
    ),
);

//Entity Link Content Requires Text:
$config['en_ids_6805'] = array(2999, 3005, 3147, 3192, 4763, 4883);
$config['en_all_6805'] = array(
    2999 => array(
        'm_icon' => '<i class="fal fa-microphone"></i>',
        'm_name' => 'Expert Podcasts',
        'm_desc' => '',
        'm_parents' => array(6805, 4990, 3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fal fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '',
        'm_parents' => array(6805, 4990, 3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fal fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '',
        'm_parents' => array(6805, 4990, 3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fal fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => '',
        'm_parents' => array(6805, 4990, 3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fal fa-bullhorn"></i>',
        'm_name' => 'Expert Marketing Channels',
        'm_desc' => '',
        'm_parents' => array(6805, 4990, 3000),
    ),
    4883 => array(
        'm_icon' => '<i class="fal fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => '',
        'm_parents' => array(6805, 4990, 3000),
    ),
);

//Intent Requires Manual Reply:
$config['en_ids_6794'] = array(6678, 6679, 6680, 6681, 6682, 6683);
$config['en_all_6794'] = array(
    6678 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image is required',
        'm_desc' => '',
        'm_parents' => array(6794, 6192),
    ),
    6679 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video is required',
        'm_desc' => '',
        'm_parents' => array(6794, 6192),
    ),
    6680 => array(
        'm_icon' => '<i class="fal fa-microphone"></i>',
        'm_name' => 'Voice Note is required',
        'm_desc' => '',
        'm_parents' => array(6794, 6192),
    ),
    6681 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File is required',
        'm_desc' => '',
        'm_parents' => array(6794, 6192),
    ),
    6682 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL is required',
        'm_desc' => '',
        'm_parents' => array(6794, 6192),
    ),
    6683 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text is required',
        'm_desc' => '',
        'm_parents' => array(6794, 6192),
    ),
);

//AND Intents:
$config['en_ids_6192'] = array(6677, 6678, 6679, 6680, 6681, 6682, 6683);
$config['en_all_6192'] = array(
    6677 => array(
        'm_icon' => '<i class="fal fa-check"></i>',
        'm_name' => 'No Response is required',
        'm_desc' => 'Students would complete the intent by simply reviewing its outcome and reading its messages if any. No inputs are required.',
        'm_parents' => array(6192),
    ),
    6678 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image is required',
        'm_desc' => 'Student must send an image to mark the intent as complete.',
        'm_parents' => array(6794, 6192),
    ),
    6679 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'Video is required',
        'm_desc' => 'Student must send a video to mark the intent as complete.',
        'm_parents' => array(6794, 6192),
    ),
    6680 => array(
        'm_icon' => '<i class="fal fa-microphone"></i>',
        'm_name' => 'Voice Note is required',
        'm_desc' => 'Student must send a voice note to mark the intent as complete.',
        'm_parents' => array(6794, 6192),
    ),
    6681 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File is required',
        'm_desc' => 'Student must upload a File to mark the intent as complete.',
        'm_parents' => array(6794, 6192),
    ),
    6682 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL is required',
        'm_desc' => 'Student must submit a URL to mark the intent as complete.',
        'm_parents' => array(6794, 6192),
    ),
    6683 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Text is required',
        'm_desc' => 'Student must submit a text message to mark the intent as complete.',
        'm_parents' => array(6794, 6192),
    ),
);

//Intent Type Entity:
$config['en_ids_6676'] = array(6192, 6193);
$config['en_all_6676'] = array(
    6192 => array(
        'm_icon' => '<i class="fal fa-sitemap"></i>',
        'm_name' => 'AND',
        'm_desc' => 'AND Intents are completed when ALL their children are complete',
        'm_parents' => array(4527, 6676),
    ),
    6193 => array(
        'm_icon' => '<i class="fal fa-code-merge"></i>',
        'm_name' => 'OR',
        'm_desc' => 'OR Intents are completed when ANY of their children are complete',
        'm_parents' => array(4527, 6676),
    ),
);

//OR Intents:
$config['en_ids_6193'] = array(6684, 6685, 6686);
$config['en_all_6193'] = array(
    6684 => array(
        'm_icon' => '<i class="fal fa-check-circle"></i>',
        'm_name' => 'Normal Selection',
        'm_desc' => 'Students can take their time and choose one of the paths of the OR intent.',
        'm_parents' => array(6193),
    ),
    6685 => array(
        'm_icon' => '<i class="fal fa-hourglass-half"></i>',
        'm_name' => 'Timed Selection',
        'm_desc' => 'Student must make a selection within the time limit defines by the estimated intent time before their response chance expires.',
        'm_parents' => array(6193),
    ),
    6686 => array(
        'm_icon' => '<i class="fal fa-dice"></i>',
        'm_name' => 'Random Selection',
        'm_desc' => 'A random selection would be made when the student reaches this intent. Good for A/B testing.',
        'm_parents' => array(6766, 6193),
    ),
);

//Platform Glossary:
$config['en_ids_4463'] = array(1281, 1308, 3000, 3084, 4430, 4433, 4485, 4488, 4535, 4536, 4595, 4755, 6138, 6196, 6199, 6205, 6695);
$config['en_all_4463'] = array(
    1281 => array(
        'm_icon' => '<i class="fal fa-shield"></i>',
        'm_name' => 'Mench Core Contributors',
        'm_desc' => 'Mench Team members who serve the community by mediating and solving issues.',
        'm_parents' => array(6827, 4463),
    ),
    1308 => array(
        'm_icon' => '<i class="fal fa-badge-check"></i>',
        'm_name' => 'Mench Certified Miners',
        'm_desc' => 'Everyone on Mench is mining intelligence, but this group of individuals have set their intention to become a Mench miner and have graduated from our training program on how to Mine intelligence using Mench.',
        'm_parents' => array(6827, 4463, 4426),
    ),
    3000 => array(
        'm_icon' => '<i class="far fa-star"></i>',
        'm_name' => 'Expert Sources',
        'm_desc' => 'Our mining process is based on existing content produced by industry experts that will be mined from various reference types including videos, articles, books, online courses and more!',
        'm_parents' => array(3463, 4506, 4527, 4463),
    ),
    3084 => array(
        'm_icon' => '<i class="fal fa-star"></i>',
        'm_name' => 'Industry Experts',
        'm_desc' => 'People with experience in their respective industry that have shown a consistent commitment to advancing their industry.',
        'm_parents' => array(4432, 4990, 4463),
    ),
    4430 => array(
        'm_icon' => '<i class="fal fa-user"></i>',
        'm_name' => 'Mench Users',
        'm_desc' => 'Users connected to Mench Personal Assistant on Facebook Messenger.',
        'm_parents' => array(4426, 4463, 4432),
    ),
    4433 => array(
        'm_icon' => '<i class="fal fa-code"></i>',
        'm_name' => 'Mench Open-Source Developers',
        'm_desc' => 'Developers who are contributing to the Mench open-source project on GitHub: https://github.com/askmench',
        'm_parents' => array(6827, 4463, 4426),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus"></i>',
        'm_name' => 'Intent Notes',
        'm_desc' => 'Intent notes are various information collected around intentions that enable Mench to operate as a Personal Assistant for students looking to accomplish an intent.',
        'm_parents' => array(4535, 4603, 4527, 4463),
    ),
    4488 => array(
        'm_icon' => '<img src="https://mench.com/img/mench_white.png">',
        'm_name' => 'Mench Platform',
        'm_desc' => 'A web portal and GUI enabling Miners to mine intents, entities and links.',
        'm_parents' => array(2738, 4523, 3326, 3324, 3325, 3323, 4463),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intents',
        'm_desc' => 'Intents define the intention of an entity as defined similar to a SMART goal.',
        'm_parents' => array(2738, 4534, 4463),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entities',
        'm_desc' => 'Entities represent people, objects and things.',
        'm_parents' => array(2738, 4534, 4463),
    ),
    4595 => array(
        'm_icon' => '<i class="fas fa-award"></i>',
        'm_name' => 'Link Points',
        'm_desc' => 'Miners are awarded with points for each transaction they log as a way to measure their contribution to Mench. Points have no monetary value and are only used to rank relative contribution.',
        'm_parents' => array(6214, 4319, 4426, 4527, 4463, 4341),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash"></i>',
        'm_name' => 'Private Content',
        'm_desc' => 'Mench is open-source but most of our student generated content is private and accessible either by the student or Mench\'s core contributors.',
        'm_parents' => array(6771, 4463, 4426, 4527, 4757),
    ),
    6138 => array(
        'm_icon' => '🚩',
        'm_name' => 'Action Plan',
        'm_desc' => 'Each student has a collection of Intents that they want to accomplish, known as their Action Plan which is accessible via Facebook Messenger or by login into mench.com',
        'm_parents' => array(2738, 4463),
    ),
    6196 => array(
        'm_icon' => '<img src="https://mench.com/img/bp_128.png">',
        'm_name' => 'Mench Personal Assistant',
        'm_desc' => '',
        'm_parents' => array(4463, 2738, 4527, 3320),
    ),
    6199 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Entity Trust Score',
        'm_desc' => 'Our measure of trust to the entity which ranks them among their peers',
        'm_parents' => array(4463, 6214, 6206),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Links',
        'm_desc' => 'An electronic log book containing a list of transactions and balances typically involving financial accounts.',
        'm_parents' => array(2738, 4463, 4534),
    ),
    6695 => array(
        'm_icon' => '<i class="fal fa-briefcase"></i>',
        'm_name' => 'Mench Partner Employers',
        'm_desc' => 'Users who can manage the accounts of organizations they belong to.',
        'm_parents' => array(6827, 4426, 4463),
    ),
);

//Action Plan Reset Progress:
$config['en_ids_6415'] = array(4559, 6140, 6143, 6144, 6154, 6155, 6157, 6158);
$config['en_all_6415'] = array(
    4559 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Action Plan Messages Read',
        'm_desc' => '',
        'm_parents' => array(6415, 6274, 6255, 4755, 6146, 4593),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Action Plan Step Unlocked',
        'm_desc' => '',
        'm_parents' => array(6415, 6410, 6288, 4229, 4755, 4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-minus-square"></i>',
        'm_name' => 'Action Plan Step(s) Skipped',
        'm_desc' => '',
        'm_parents' => array(6415, 6146, 4755, 4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Action Plan Requirement Submitted',
        'm_desc' => '',
        'm_parents' => array(6415, 6255, 6244, 4755, 6146, 4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-badge-check"></i>',
        'm_name' => 'Action Plan Intention Accomplished',
        'm_desc' => '',
        'm_parents' => array(6415, 4506, 6150, 4755, 4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'Action Plan Intention Terminated',
        'm_desc' => '',
        'm_parents' => array(6415, 4506, 6150, 4593, 4755),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-clipboard-check"></i>',
        'm_name' => 'Action Plan Question Answered',
        'm_desc' => '',
        'm_parents' => array(6415, 6288, 6255, 6244, 6146, 4755, 4593, 4460),
    ),
    6158 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Action Plan Auto Complete',
        'm_desc' => '',
        'm_parents' => array(6415, 6274, 6255, 4593, 4755, 6146),
    ),
);

//Action Plan Completion Unlock Milestones Function:
$config['en_ids_6410'] = array(4228, 4229, 4358, 4735, 4739, 6140, 6402);
$config['en_all_6410'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-check-circle"></i>',
        'm_name' => 'Linked Intents Fixed Step',
        'm_desc' => 'Fixed steps provide the assessment marks needed to determine the outcome of conditional steps.',
        'm_parents' => array(6410, 4593, 4486, 4595),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-spin fa-question-circle"></i>',
        'm_name' => 'Linked Intents Conditional Step',
        'm_desc' => 'The outcome of processing the aggregate steps if a student\'s Action Plan and unlocking a specific intent based on the percentage outcome.',
        'm_parents' => array(6410, 6283, 4593, 4486, 4595),
    ),
    4358 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'Response Weight',
        'm_desc' => 'With each response, users are leaning towards a high or low weight which will correlate to two directions of an assessment',
        'm_parents' => array(6410, 6232, 6213, 6103, 4228),
    ),
    4735 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'Conditional Minimum Score',
        'm_desc' => '',
        'm_parents' => array(6410, 6402, 6232, 6213, 6103),
    ),
    4739 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'Conditional Maximum Score',
        'm_desc' => '',
        'm_parents' => array(6410, 6402, 6232, 6213, 6103),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Action Plan Step Unlocked',
        'm_desc' => 'Determined by this function which step should be unlocked',
        'm_parents' => array(6415, 6410, 6288, 4229, 4755, 4593),
    ),
    6402 => array(
        'm_icon' => '<i class="far fa-ruler"></i>',
        'm_name' => 'Conditional Score Range',
        'm_desc' => 'Defines the minimum/maximum fixed score a student must get in order to unlock this conditional step',
        'm_parents' => array(6410, 4229),
    ),
);

//Intent Notes Deliverable:
$config['en_ids_6345'] = array(4231, 4232, 6242);
$config['en_all_6345'] = array(
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'Intent Note On-Start Message',
        'm_desc' => '',
        'm_parents' => array(6345, 4986, 4603, 4593, 4485, 4595),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-lightbulb-on"></i>',
        'm_name' => 'Intent Note Bonus Message',
        'm_desc' => '',
        'm_parents' => array(5007, 6345, 4986, 4603, 4593, 4485, 4595),
    ),
    6242 => array(
        'm_icon' => '<i class="fal fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Message',
        'm_desc' => '',
        'm_parents' => array(5007, 6345, 4603, 4595, 4593, 4986, 4485),
    ),
);

//Mench:
$config['en_ids_2738'] = array(4432, 4488, 4535, 4536, 5007, 6137, 6138, 6196, 6205, 6287, 6827);
$config['en_all_2738'] = array(
    4432 => array(
        'm_icon' => '<i class="far fa-certificate"></i>',
        'm_name' => 'Mench Verified Accounts',
        'm_desc' => '',
        'm_parents' => array(2738, 4527, 3463),
    ),
    4488 => array(
        'm_icon' => '<img src="https://mench.com/img/mench_white.png">',
        'm_name' => 'Mench Platform',
        'm_desc' => 'On a mission to build and share consensus',
        'm_parents' => array(2738, 4523, 3326, 3324, 3325, 3323, 4463),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intents',
        'm_desc' => '',
        'm_parents' => array(2738, 4534, 4463),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entities',
        'm_desc' => '',
        'm_parents' => array(2738, 4534, 4463),
    ),
    5007 => array(
        'm_icon' => '<i class="fal fa-expand-arrows"></i>',
        'm_name' => 'Miner Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(2738, 4757, 4593),
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
        'm_name' => 'Mench Personal Assistant',
        'm_desc' => 'A personal assistant bot that automates the distribution of Mench\'s intent tree to students using Facebook Messenger',
        'm_parents' => array(4463, 2738, 4527, 3320),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Links',
        'm_desc' => '',
        'm_parents' => array(2738, 4463, 4534),
    ),
    6287 => array(
        'm_icon' => '<i class="fas fa-tools"></i>',
        'm_name' => 'Mench Admin Tools',
        'm_desc' => 'Series of tools to moderate the Mench platform',
        'm_parents' => array(2738),
    ),
    6827 => array(
        'm_icon' => '<i class="far fa-plus-circle"></i>',
        'm_name' => 'Mench Contributors',
        'm_desc' => 'The Mench community and its various groups',
        'm_parents' => array(4527, 3463, 2738),
    ),
);

//Action Plan Progression Automated:
$config['en_ids_6274'] = array(4559, 6158);
$config['en_all_6274'] = array(
    4559 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Action Plan Messages Read',
        'm_desc' => '',
        'm_parents' => array(6415, 6274, 6255, 4755, 6146, 4593),
    ),
    6158 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Action Plan Auto Complete',
        'm_desc' => '',
        'm_parents' => array(6415, 6274, 6255, 4593, 4755, 6146),
    ),
);

//Action Plan Progression Completion Triggers:
$config['en_ids_6255'] = array(4559, 6144, 6157, 6158);
$config['en_all_6255'] = array(
    4559 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Action Plan Messages Read',
        'm_desc' => '',
        'm_parents' => array(6415, 6274, 6255, 4755, 6146, 4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Action Plan Requirement Submitted',
        'm_desc' => '',
        'm_parents' => array(6415, 6255, 6244, 4755, 6146, 4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-clipboard-check"></i>',
        'm_name' => 'Action Plan Question Answered',
        'm_desc' => '',
        'm_parents' => array(6415, 6288, 6255, 6244, 6146, 4755, 4593, 4460),
    ),
    6158 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Action Plan Auto Complete',
        'm_desc' => '',
        'm_parents' => array(6415, 6274, 6255, 4593, 4755, 6146),
    ),
);

//Action Plan Progression 2-Step Steps:
$config['en_ids_6244'] = array(6144, 6157);
$config['en_all_6244'] = array(
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Requirement Submitted',
        'm_desc' => '',
        'm_parents' => array(6415, 6255, 6244, 4755, 6146, 4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-clipboard-check"></i>',
        'm_name' => 'Question Answered',
        'm_desc' => '',
        'm_parents' => array(6415, 6288, 6255, 6244, 6146, 4755, 4593, 4460),
    ),
);

//Action Plan Completion:
$config['en_ids_6150'] = array(6154, 6155);
$config['en_all_6150'] = array(
    6154 => array(
        'm_icon' => '<i class="far fa-badge-check"></i>',
        'm_name' => 'Intention Accomplished',
        'm_desc' => 'You successfully accomplished your intention so you no longer want to receive future updates',
        'm_parents' => array(6415, 4506, 6150, 4755, 4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'Intention Terminated',
        'm_desc' => 'You did NOT accomplish the intention and you want to stop all future updates on this intention',
        'm_parents' => array(6415, 4506, 6150, 4593, 4755),
    ),
);

//Intent Notes Entity Referencing:
$config['en_ids_4986'] = array(4231, 4232, 4983, 6093, 6242);
$config['en_all_4986'] = array(
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'Intent Note On-Start Message',
        'm_desc' => '',
        'm_parents' => array(6345, 4986, 4603, 4593, 4485, 4595),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-lightbulb-on"></i>',
        'm_name' => 'Intent Note Bonus Message',
        'm_desc' => '',
        'm_parents' => array(5007, 6345, 4986, 4603, 4593, 4485, 4595),
    ),
    4983 => array(
        'm_icon' => '<i class="fal fa-thumbs-up"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => '',
        'm_parents' => array(4986, 4985, 4595, 4593, 4485),
    ),
    6093 => array(
        'm_icon' => '<i class="fal fa-comments"></i>',
        'm_name' => 'Intent Note Miner Note',
        'm_desc' => '',
        'm_parents' => array(5007, 4595, 4593, 4986, 4485),
    ),
    6242 => array(
        'm_icon' => '<i class="fal fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Message',
        'm_desc' => '',
        'm_parents' => array(5007, 6345, 4603, 4595, 4593, 4986, 4485),
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
        'm_parents' => array(6225, 5969, 4755),
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
$config['en_ids_6196'] = array(6200, 6203, 6221);
$config['en_all_6196'] = array(
    6200 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Entity PSID',
        'm_desc' => '',
        'm_parents' => array(6196, 6215, 6206),
    ),
    6203 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'Facebook Attachment ID',
        'm_desc' => 'File caching offered by Facebook for media delivered over Messenger.',
        'm_parents' => array(6232, 6196, 6215, 2793, 6103),
    ),
    6221 => array(
        'm_icon' => '<i class="fas fa-comment-smile"></i>',
        'm_name' => 'Student Communications',
        'm_desc' => '',
        'm_parents' => array(6196),
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

//Intent Status:
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
$config['en_ids_6146'] = array(4559, 6143, 6144, 6157, 6158);
$config['en_all_6146'] = array(
    4559 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Messages Read',
        'm_desc' => 'Completed when students read the messages of an intent that does not have a completion requirement',
        'm_parents' => array(6415, 6274, 6255, 4755, 6146, 4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-minus-square"></i>',
        'm_name' => 'Step(s) Skipped',
        'm_desc' => 'Completed when students skip an intention and all its child intentions from their Action Plan',
        'm_parents' => array(6415, 6146, 4755, 4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Requirement Submitted',
        'm_desc' => 'Completed when students submit the intent completion requirements (text, URL, video, etc...) set by miners',
        'm_parents' => array(6415, 6255, 6244, 4755, 6146, 4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-clipboard-check"></i>',
        'm_name' => 'Question Answered',
        'm_desc' => 'Completed after the student answers the question to the OR intent.',
        'm_parents' => array(6415, 6288, 6255, 6244, 6146, 4755, 4593, 4460),
    ),
    6158 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Auto Complete',
        'm_desc' => 'Completed when students read the messages of an intent that does not have any messages or a completion requirement',
        'm_parents' => array(6415, 6274, 6255, 4593, 4755, 6146),
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

//Student Sent Message:
$config['en_ids_4277'] = array(4287, 4460, 4547, 4548, 4549, 4550, 4551, 4557, 6561);
$config['en_all_4277'] = array(
    4287 => array(
        'm_icon' => '<i class="fal fa-comment-exclamation"></i>',
        'm_name' => 'User Sent Unrecognized Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4277, 4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => 'When students select a quick reply answer of any kind',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4547 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'User Sent Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4548 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'User Sent Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4549 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'User Sent Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4550 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'User Sent Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4551 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'User Sent File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4557 => array(
        'm_icon' => '<i class="fal fa-location-circle"></i>',
        'm_name' => 'User Sent Location Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    6561 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'User Sent Manual Quick Reply',
        'm_desc' => 'When students manually type in the quick reply answer and we match it to their quick reply options',
        'm_parents' => array(4755, 4593, 4277),
    ),
);

//Student Sent/Received Media Message:
$config['en_ids_6102'] = array(4548, 4549, 4550, 4551, 4553, 4554, 4555, 4556);
$config['en_all_6102'] = array(
    4548 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'User Sent Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4549 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'User Sent Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4550 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'User Sent Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4551 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'User Sent File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4553 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'User Received Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4554 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'User Received Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4555 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'User Received Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4556 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'User Received File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
);

//Student Received Message:
$config['en_ids_4280'] = array(4552, 4553, 4554, 4555, 4556, 4570, 5967, 6563);
$config['en_all_4280'] = array(
    4552 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'User Received Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    4553 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'User Received Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4554 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'User Received Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4555 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'User Received Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4556 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'User Received File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'User Received HTML Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    5967 => array(
        'm_icon' => '<i class="fal fa-envelope-open"></i>',
        'm_name' => 'User Received Email Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => 'When we dispatch a quick reply to students and are waiting for their answer...',
        'm_parents' => array(4593, 4755, 4280),
    ),
);

//Mench Verified Accounts:
$config['en_ids_4432'] = array(2750, 3084, 4430);
$config['en_all_4432'] = array(
    2750 => array(
        'm_icon' => '<i class="fal fa-users"></i>',
        'm_name' => 'Organizations',
        'm_desc' => 'Companies, teams or groups that collaborate with a shared mission',
        'm_parents' => array(4432, 4600),
    ),
    3084 => array(
        'm_icon' => '<i class="fal fa-star"></i>',
        'm_name' => 'Industry Experts',
        'm_desc' => 'Domain masters considering their tangible accomplishments',
        'm_parents' => array(4432, 4990, 4463),
    ),
    4430 => array(
        'm_icon' => '<i class="fal fa-user"></i>',
        'm_name' => 'Mench Users',
        'm_desc' => 'All Mench users share a connection to Mench personal assistant and may also belong to other user groups',
        'm_parents' => array(4426, 4463, 4432),
    ),
);

//Dead Lock:
$config['en_ids_5969'] = array(3286);
$config['en_all_5969'] = array(
    3286 => array(
        'm_icon' => '<i class="fal fa-lock-open"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => 'Managed through the Forgot Password section in the Login page',
        'm_parents' => array(6225, 5969, 4755),
    ),
);

//Link Email Subscriptions:
$config['en_ids_5966'] = array(4246);
$config['en_all_5966'] = array(
    4246 => array(
        'm_icon' => '<i class="fal fa-bug"></i>',
        'm_name' => 'Platform Bug Reports',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(4755, 5966, 4593),
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
        'm_name' => 'Entity Link Content Replace',
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
        'm_name' => 'Entity Link Status Replace',
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
        'm_name' => 'Entity Parent Addition',
        'm_desc' => 'If not already done so, will add searched entity as the parent of all child entities',
        'm_parents' => array(4593, 4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Parent Removal',
        'm_desc' => 'If already added as the parent, this will remove searched entity as the parent of all child entities',
        'm_parents' => array(4593, 4997),
    ),
);

//Intent Notes Public Entity References:
$config['en_ids_4990'] = array(1326, 2793, 2997, 2998, 2999, 3005, 3084, 3147, 3192, 3300, 3301, 3302, 3303, 3308, 3314, 3320, 4257, 4258, 4259, 4260, 4395, 4399, 4446, 4763, 4883, 5948, 6334, 6336);
$config['en_all_4990'] = array(
    1326 => array(
        'm_icon' => '<i class="fal fa-bookmark"></i>',
        'm_name' => 'Domain',
        'm_desc' => '',
        'm_parents' => array(4990, 4506),
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
        'm_parents' => array(6805, 4990, 3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fal fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '',
        'm_parents' => array(6805, 4990, 3000),
    ),
    3084 => array(
        'm_icon' => '<i class="fal fa-star"></i>',
        'm_name' => 'Industry Experts',
        'm_desc' => '',
        'm_parents' => array(4432, 4990, 4463),
    ),
    3147 => array(
        'm_icon' => '<i class="fal fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '',
        'm_parents' => array(6805, 4990, 3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fal fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => '',
        'm_parents' => array(6805, 4990, 3000),
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
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Linked Entities Audio',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Linked Entities Image',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
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
        'm_parents' => array(6805, 4990, 3000),
    ),
    4883 => array(
        'm_icon' => '<i class="fal fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => '',
        'm_parents' => array(6805, 4990, 3000),
    ),
    5948 => array(
        'm_icon' => '<i class="fal fa-file-invoice"></i>',
        'm_name' => 'Expert Templates',
        'm_desc' => '',
        'm_parents' => array(4990, 3000),
    ),
    6334 => array(
        'm_icon' => '<img src="http://paypal.com/favicon.ico">',
        'm_name' => 'PayPal.com',
        'm_desc' => '',
        'm_parents' => array(4990, 1326),
    ),
    6336 => array(
        'm_icon' => '<img src="http://paypal.me/favicon.ico">',
        'm_name' => 'PayPal.me',
        'm_desc' => '',
        'm_parents' => array(4990, 1326),
    ),
);

//Modification Lock:
$config['en_ids_4426'] = array(1308, 3288, 4426, 4430, 4433, 4595, 4755, 4997, 5969, 6695);
$config['en_all_4426'] = array(
    1308 => array(
        'm_icon' => '<i class="fal fa-badge-check"></i>',
        'm_name' => 'Mench Certified Miners',
        'm_desc' => 'Requires admin review and approval',
        'm_parents' => array(6827, 4463, 4426),
    ),
    3288 => array(
        'm_icon' => '<i class="fal fa-envelope"></i>',
        'm_name' => 'Email Address',
        'm_desc' => '',
        'm_parents' => array(6225, 4426, 4755),
    ),
    4426 => array(
        'm_icon' => '<i class="far fa-lock"></i>',
        'm_name' => 'Modification Lock',
        'm_desc' => '',
        'm_parents' => array(6771, 4426, 4527, 4757, 4428),
    ),
    4430 => array(
        'm_icon' => '<i class="fal fa-user"></i>',
        'm_name' => 'Mench Users',
        'm_desc' => '',
        'm_parents' => array(4426, 4463, 4432),
    ),
    4433 => array(
        'm_icon' => '<i class="fal fa-code"></i>',
        'm_name' => 'Mench Open-Source Developers',
        'm_desc' => 'Requires admin review and approval',
        'm_parents' => array(6827, 4463, 4426),
    ),
    4595 => array(
        'm_icon' => '<i class="fas fa-award"></i>',
        'm_name' => 'Link Points',
        'm_desc' => '',
        'm_parents' => array(6214, 4319, 4426, 4527, 4463, 4341),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash"></i>',
        'm_name' => 'Private Content',
        'm_desc' => '',
        'm_parents' => array(6771, 4463, 4426, 4527, 4757),
    ),
    4997 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Updates',
        'm_desc' => '',
        'm_parents' => array(4758, 5007, 4506, 4426, 4527),
    ),
    5969 => array(
        'm_icon' => '<i class="fas fa-lock"></i>',
        'm_name' => 'Dead Lock',
        'm_desc' => '',
        'm_parents' => array(6771, 4426, 4527, 4757, 4428),
    ),
    6695 => array(
        'm_icon' => '<i class="fal fa-briefcase"></i>',
        'm_name' => 'Mench Partner Employers',
        'm_desc' => '',
        'm_parents' => array(6827, 4426, 4463),
    ),
);

//Private Content:
$config['en_ids_4755'] = array(3286, 3288, 4235, 4242, 4246, 4251, 4263, 4266, 4267, 4268, 4275, 4278, 4279, 4282, 4283, 4287, 4299, 4460, 4547, 4548, 4549, 4550, 4551, 4552, 4553, 4554, 4555, 4556, 4557, 4559, 4570, 4783, 5967, 6132, 6140, 6143, 6144, 6149, 6154, 6155, 6157, 6158, 6224, 6389, 6415, 6556, 6559, 6560, 6561, 6563, 6578);
$config['en_all_4755'] = array(
    3286 => array(
        'm_icon' => '<i class="fal fa-lock-open"></i>',
        'm_name' => 'Mench Password',
        'm_desc' => '',
        'm_parents' => array(6225, 5969, 4755),
    ),
    3288 => array(
        'm_icon' => '<i class="fal fa-envelope"></i>',
        'm_name' => 'Email Address',
        'm_desc' => '',
        'm_parents' => array(6225, 4426, 4755),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-bullseye-arrow"></i>',
        'm_name' => 'Action Plan Intention Set',
        'm_desc' => '',
        'm_parents' => array(6153, 4506, 4755, 4593),
    ),
    4242 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Link Updated',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4595),
    ),
    4246 => array(
        'm_icon' => '<i class="fal fa-bug"></i>',
        'm_name' => 'Platform Bug Reports',
        'm_desc' => '',
        'm_parents' => array(4755, 5966, 4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entity Created',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4595),
    ),
    4263 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Entity Updated',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4595),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4268 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Postback',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Text Commanded Intention',
        'm_desc' => '',
        'm_parents' => array(6554, 4755, 4593),
    ),
    4278 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'User Message Read',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4279 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'User Message Delivered',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4282 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'User Account Opened',
        'm_desc' => '',
        'm_parents' => array(4755, 6222, 4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'Action Plan Intentions Listed',
        'm_desc' => '',
        'm_parents' => array(6153, 4755, 4593),
    ),
    4287 => array(
        'm_icon' => '<i class="fal fa-comment-exclamation"></i>',
        'm_name' => 'User Sent Unrecognized Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4277, 4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-id-badge"></i>',
        'm_name' => 'User Set Profile Picture',
        'm_desc' => '',
        'm_parents' => array(6222, 4755, 4593),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4547 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'User Sent Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4548 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'User Sent Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4549 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'User Sent Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4550 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'User Sent Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4551 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'User Sent File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4552 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'User Received Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    4553 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'User Received Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4554 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'User Received Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4555 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'User Received Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4556 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'User Received File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4557 => array(
        'm_icon' => '<i class="fal fa-location-circle"></i>',
        'm_name' => 'User Sent Location Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Action Plan Messages Read',
        'm_desc' => '',
        'm_parents' => array(6415, 6274, 6255, 4755, 6146, 4593),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'User Received HTML Message',
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
        'm_name' => 'User Received Email Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90"></i>',
        'm_name' => 'Action Plan Intentions Sorted',
        'm_desc' => '',
        'm_parents' => array(6153, 4506, 4755, 4593),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Action Plan Step Unlocked',
        'm_desc' => '',
        'm_parents' => array(6415, 6410, 6288, 4229, 4755, 4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-minus-square"></i>',
        'm_name' => 'Action Plan Step(s) Skipped',
        'm_desc' => '',
        'm_parents' => array(6415, 6146, 4755, 4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Action Plan Requirement Submitted',
        'm_desc' => '',
        'm_parents' => array(6415, 6255, 6244, 4755, 6146, 4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus"></i>',
        'm_name' => 'Action Plan Intention Considered',
        'm_desc' => '',
        'm_parents' => array(4428, 6153, 4755, 4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-badge-check"></i>',
        'm_name' => 'Action Plan Intention Accomplished',
        'm_desc' => '',
        'm_parents' => array(6415, 4506, 6150, 4755, 4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'Action Plan Intention Terminated',
        'm_desc' => '',
        'm_parents' => array(6415, 4506, 6150, 4593, 4755),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-clipboard-check"></i>',
        'm_name' => 'Action Plan Question Answered',
        'm_desc' => '',
        'm_parents' => array(6415, 6288, 6255, 6244, 6146, 4755, 4593, 4460),
    ),
    6158 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Action Plan Auto Complete',
        'm_desc' => '',
        'm_parents' => array(6415, 6274, 6255, 4593, 4755, 6146),
    ),
    6224 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'User Account Updated',
        'm_desc' => '',
        'm_parents' => array(4755, 6222, 4593),
    ),
    6389 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Profile Inaccessible',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function"></i>',
        'm_name' => 'Action Plan Reset Progress',
        'm_desc' => '',
        'm_parents' => array(4755, 6418, 4593, 4527, 6414),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Text Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 6554),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Text Commanded Next',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Text Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 6554),
    ),
    6561 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'User Sent Manual Quick Reply',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(4593, 4755, 4280),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Text Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 6554),
    ),
);

//Link Points:
$config['en_ids_4595'] = array(4228, 4229, 4230, 4231, 4232, 4242, 4250, 4251, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4263, 4264, 4318, 4319, 4601, 4983, 6093, 6242);
$config['en_all_4595'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-check-circle"></i>',
        'm_name' => 'Linked Intents Fixed Step',
        'm_desc' => '100',
        'm_parents' => array(6410, 4593, 4486, 4595),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-spin fa-question-circle"></i>',
        'm_name' => 'Linked Intents Conditional Step',
        'm_desc' => '100',
        'm_parents' => array(6410, 6283, 4593, 4486, 4595),
    ),
    4230 => array(
        'm_icon' => '<i class="fal fa-level-up rotate90"></i>',
        'm_name' => 'Linked Entities Raw',
        'm_desc' => '10',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'Intent Note On-Start Message',
        'm_desc' => '100',
        'm_parents' => array(6345, 4986, 4603, 4593, 4485, 4595),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-lightbulb-on"></i>',
        'm_name' => 'Intent Note Bonus Message',
        'm_desc' => '100',
        'm_parents' => array(5007, 6345, 4986, 4603, 4593, 4485, 4595),
    ),
    4242 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Link Updated',
        'm_desc' => '20',
        'm_parents' => array(4755, 4593, 4595),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intent Created',
        'm_desc' => '200',
        'm_parents' => array(4593, 4595),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entity Created',
        'm_desc' => '30',
        'm_parents' => array(4755, 4593, 4595),
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Linked Entities Text Message',
        'm_desc' => '30',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'Linked Entities URL',
        'm_desc' => '50',
        'm_parents' => array(4593, 4592, 4537, 4595),
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
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Linked Entities Audio',
        'm_desc' => '50',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Linked Entities Image',
        'm_desc' => '50',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Linked Entities File',
        'm_desc' => '50',
        'm_parents' => array(6203, 4593, 4592, 4537, 4595),
    ),
    4263 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Entity Updated',
        'm_desc' => '10',
        'm_parents' => array(4755, 4593, 4595),
    ),
    4264 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Intent Updated',
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
        'm_parents' => array(4593, 4595, 4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fal fa-thumbs-up"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => '100',
        'm_parents' => array(4986, 4985, 4595, 4593, 4485),
    ),
    6093 => array(
        'm_icon' => '<i class="fal fa-comments"></i>',
        'm_name' => 'Intent Note Miner Note',
        'm_desc' => '100',
        'm_parents' => array(5007, 4595, 4593, 4986, 4485),
    ),
    6242 => array(
        'm_icon' => '<i class="fal fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Message',
        'm_desc' => '100',
        'm_parents' => array(5007, 6345, 4603, 4595, 4593, 4986, 4485),
    ),
);

//User Account Types:
$config['en_ids_4600'] = array(1278, 2750);
$config['en_all_4600'] = array(
    1278 => array(
        'm_icon' => '👪',
        'm_name' => 'People',
        'm_desc' => '',
        'm_parents' => array(4600),
    ),
    2750 => array(
        'm_icon' => '<i class="fal fa-users"></i>',
        'm_name' => 'Organizations',
        'm_desc' => '',
        'm_parents' => array(4432, 4600),
    ),
);

//Link Types:
$config['en_ids_4593'] = array(4228, 4229, 4230, 4231, 4232, 4235, 4242, 4246, 4250, 4251, 4255, 4256, 4257, 4258, 4259, 4260, 4261, 4263, 4264, 4266, 4267, 4268, 4269, 4275, 4278, 4279, 4282, 4283, 4287, 4299, 4318, 4319, 4455, 4460, 4547, 4548, 4549, 4550, 4551, 4552, 4553, 4554, 4555, 4556, 4557, 4559, 4570, 4577, 4601, 4983, 4993, 4994, 4996, 4998, 4999, 5000, 5001, 5003, 5007, 5865, 5943, 5967, 5981, 5982, 6093, 6132, 6140, 6143, 6144, 6149, 6154, 6155, 6157, 6158, 6224, 6226, 6242, 6389, 6415, 6556, 6559, 6560, 6561, 6563, 6578);
$config['en_all_4593'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-check-circle"></i>',
        'm_name' => 'Linked Intents Fixed Step',
        'm_desc' => '',
        'm_parents' => array(6410, 4593, 4486, 4595),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-spin fa-question-circle"></i>',
        'm_name' => 'Linked Intents Conditional Step',
        'm_desc' => '',
        'm_parents' => array(6410, 6283, 4593, 4486, 4595),
    ),
    4230 => array(
        'm_icon' => '<i class="fal fa-level-up rotate90"></i>',
        'm_name' => 'Linked Entities Raw',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'Intent Note On-Start Message',
        'm_desc' => '',
        'm_parents' => array(6345, 4986, 4603, 4593, 4485, 4595),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-lightbulb-on"></i>',
        'm_name' => 'Intent Note Bonus Message',
        'm_desc' => '',
        'm_parents' => array(5007, 6345, 4986, 4603, 4593, 4485, 4595),
    ),
    4235 => array(
        'm_icon' => '<i class="fas fa-bullseye-arrow"></i>',
        'm_name' => 'Action Plan Intention Set',
        'm_desc' => 'Top-level goals set by students that enable Mench to deliver the most relevant intelligence.',
        'm_parents' => array(6153, 4506, 4755, 4593),
    ),
    4242 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Link Updated',
        'm_desc' => 'Logged for each link column that is updated consciously by the user',
        'm_parents' => array(4755, 4593, 4595),
    ),
    4246 => array(
        'm_icon' => '<i class="fal fa-bug"></i>',
        'm_name' => 'Platform Bug Reports',
        'm_desc' => '',
        'm_parents' => array(4755, 5966, 4593),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intent Created',
        'm_desc' => '',
        'm_parents' => array(4593, 4595),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entity Created',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(4755, 4593, 4595),
    ),
    4255 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'Linked Entities Text Message',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'Linked Entities URL',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4537, 4595),
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
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Linked Entities Audio',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Linked Entities Image',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'Linked Entities File',
        'm_desc' => '',
        'm_parents' => array(6203, 4593, 4592, 4537, 4595),
    ),
    4263 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Entity Updated',
        'm_desc' => 'When a Miner modified an entity attribute like Name, Icon or Status.',
        'm_parents' => array(4755, 4593, 4595),
    ),
    4264 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Intent Updated',
        'm_desc' => 'When an intent field is updated',
        'm_parents' => array(4593, 4595),
    ),
    4266 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Opt-in',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4267 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Referral',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4268 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Postback',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'Miner Login',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4275 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Text Commanded Intention',
        'm_desc' => 'When students invokes the [I want to] command and search for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(6554, 4755, 4593),
    ),
    4278 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'User Message Read',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4279 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'User Message Delivered',
        'm_desc' => '',
        'm_parents' => array(4755, 4593),
    ),
    4282 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'User Account Opened',
        'm_desc' => '',
        'm_parents' => array(4755, 6222, 4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fas fa-eye"></i>',
        'm_name' => 'Action Plan Intentions Listed',
        'm_desc' => 'Once a student has added an Intention to their Action Plan, this link will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(6153, 4755, 4593),
    ),
    4287 => array(
        'm_icon' => '<i class="fal fa-comment-exclamation"></i>',
        'm_name' => 'User Sent Unrecognized Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4277, 4593),
    ),
    4299 => array(
        'm_icon' => '<i class="far fa-id-badge"></i>',
        'm_name' => 'User Set Profile Picture',
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
    4455 => array(
        'm_icon' => '<i class="fas fa-ban"></i>',
        'm_name' => 'User Unsubscribed',
        'm_desc' => 'Student requested that all communication with Mench to be stopped until further notice from the student.',
        'm_parents' => array(4593, 4454),
    ),
    4460 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'User Sent Quick Reply',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4547 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'User Sent Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4548 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'User Sent Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4549 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'User Sent Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4550 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'User Sent Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4551 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'User Sent File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4277),
    ),
    4552 => array(
        'm_icon' => '<i class="fal fa-align-left"></i>',
        'm_name' => 'User Received Text Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    4553 => array(
        'm_icon' => '<i class="fal fa-video"></i>',
        'm_name' => 'User Received Video Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4554 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'User Received Audio Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4555 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'User Received Image Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4556 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'User Received File Message',
        'm_desc' => '',
        'm_parents' => array(6102, 4755, 4593, 4280),
    ),
    4557 => array(
        'm_icon' => '<i class="fal fa-location-circle"></i>',
        'm_name' => 'User Sent Location Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-comment-check"></i>',
        'm_name' => 'Action Plan Messages Read',
        'm_desc' => 'Logged when a student receives the messages of an AND intent that does not have any completion requirements.',
        'm_parents' => array(6415, 6274, 6255, 4755, 6146, 4593),
    ),
    4570 => array(
        'm_icon' => '<i class="fab fa-html5"></i>',
        'm_name' => 'User Received HTML Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    4577 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'Messenger Request Accepted',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fal fa-tags"></i>',
        'm_name' => 'Intent Note Keyword',
        'm_desc' => '',
        'm_parents' => array(4593, 4595, 4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fal fa-thumbs-up"></i>',
        'm_name' => 'Intent Note Up-Vote',
        'm_desc' => 'Up-votes track intent correlations referenced within expert sources, and represent a core building block of intelligence. Up-votes are among the most precious transaction types because they indicate that IF you do A, you will likely accomplish B. As miners mine content from more experts, certain intent correlations will receive more Up-vites than others, thus gaining more credibility.',
        'm_parents' => array(4986, 4985, 4595, 4593, 4485),
    ),
    4993 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Miner Viewed Intent',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4994 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Miner Viewed Entity',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4996 => array(
        'm_icon' => '<i class="fas fa-sign-in"></i>',
        'm_name' => 'User Login',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4998 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Name Prefix',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    4999 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Name Postfix',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5000 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Name Replace',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5001 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Link Content Replace',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5003 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Status Replace',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5007 => array(
        'm_icon' => '<i class="fal fa-expand-arrows"></i>',
        'm_name' => 'Miner Toggle Advance Mode',
        'm_desc' => '',
        'm_parents' => array(2738, 4757, 4593),
    ),
    5865 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Link Status Replace',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Icon Update',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5967 => array(
        'm_icon' => '<i class="fal fa-envelope-open"></i>',
        'm_name' => 'User Received Email Message',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4280),
    ),
    5981 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Parent Addition',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    5982 => array(
        'm_icon' => '<i class="fal fa-list-alt"></i>',
        'm_name' => 'Entity Mass Parent Removal',
        'm_desc' => '',
        'm_parents' => array(4593, 4997),
    ),
    6093 => array(
        'm_icon' => '<i class="fal fa-comments"></i>',
        'm_name' => 'Intent Note Miner Note',
        'm_desc' => '',
        'm_parents' => array(5007, 4595, 4593, 4986, 4485),
    ),
    6132 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90"></i>',
        'm_name' => 'Action Plan Intentions Sorted',
        'm_desc' => 'Student re-prioritized their top-level intentions to focus on intentions that currently matter the most.',
        'm_parents' => array(6153, 4506, 4755, 4593),
    ),
    6140 => array(
        'm_icon' => '<i class="fas fa-lock-open"></i>',
        'm_name' => 'Action Plan Step Unlocked',
        'm_desc' => 'Created when the student responses to OR branches meets the right % points to unlock the pathway to a conditional intent link.',
        'm_parents' => array(6415, 6410, 6288, 4229, 4755, 4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-minus-square"></i>',
        'm_name' => 'Action Plan Step(s) Skipped',
        'm_desc' => 'Logged every time a student consciously skips an intent and it\'s recursive children.',
        'm_parents' => array(6415, 6146, 4755, 4593),
    ),
    6144 => array(
        'm_icon' => '<i class="fas fa-shield-check"></i>',
        'm_name' => 'Action Plan Requirement Submitted',
        'm_desc' => 'Logged when a student submits the requirements (text, video, etc...) of an AND intent which could not be completed by simply receiving messages.',
        'm_parents' => array(6415, 6255, 6244, 4755, 6146, 4593),
    ),
    6149 => array(
        'm_icon' => '<i class="fas fa-search-plus"></i>',
        'm_name' => 'Action Plan Intention Considered',
        'm_desc' => 'When a student chooses to review a given intention from the intentions they have searched or have been recommended after selecting GET STARTED from a mench.com intent landing page.',
        'm_parents' => array(4428, 6153, 4755, 4593),
    ),
    6154 => array(
        'm_icon' => '<i class="far fa-badge-check"></i>',
        'm_name' => 'Action Plan Intention Accomplished',
        'm_desc' => 'Student accomplished their intention 🎉🎉🎉',
        'm_parents' => array(6415, 4506, 6150, 4755, 4593),
    ),
    6155 => array(
        'm_icon' => '<i class="far fa-stop-circle"></i>',
        'm_name' => 'Action Plan Intention Terminated',
        'm_desc' => 'Student prematurely removed an intention from their Action Plan without accomplishing it.',
        'm_parents' => array(6415, 4506, 6150, 4593, 4755),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-clipboard-check"></i>',
        'm_name' => 'Action Plan Question Answered',
        'm_desc' => '',
        'm_parents' => array(6415, 6288, 6255, 6244, 6146, 4755, 4593, 4460),
    ),
    6158 => array(
        'm_icon' => '<i class="far fa-check-square"></i>',
        'm_name' => 'Action Plan Auto Complete',
        'm_desc' => 'The most basic type of intent completion for intents that do not have any messages, completion requirements or children to choose from.',
        'm_parents' => array(6415, 6274, 6255, 4593, 4755, 6146),
    ),
    6224 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'User Account Updated',
        'm_desc' => '',
        'm_parents' => array(4755, 6222, 4593),
    ),
    6226 => array(
        'm_icon' => '<i class="fal fa-sync"></i>',
        'm_name' => 'Intent Mass Status Updates',
        'm_desc' => ' When all intents within a recursive tree are updated at once.',
        'm_parents' => array(4593),
    ),
    6242 => array(
        'm_icon' => '<i class="fal fa-check-square"></i>',
        'm_name' => 'Intent Note On-Complete Message',
        'm_desc' => '',
        'm_parents' => array(5007, 6345, 4603, 4595, 4593, 4986, 4485),
    ),
    6389 => array(
        'm_icon' => '<i class="fab fa-facebook-messenger"></i>',
        'm_name' => 'User Messenger Profile Inaccessible',
        'm_desc' => 'When we cannot fetch user profile data via Messenger API, either because user has not linked their Facebook to Messenger or because they did not authorize Mench to access',
        'm_parents' => array(4755, 4593),
    ),
    6415 => array(
        'm_icon' => '<i class="far fa-function"></i>',
        'm_name' => 'Action Plan Reset Progress',
        'm_desc' => 'Removes certain links types as defined by its children from a Student\'s Action Plan. Currently only available for Miners.',
        'm_parents' => array(4755, 6418, 4593, 4527, 6414),
    ),
    6556 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Text Commanded Stats',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 6554),
    ),
    6559 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Text Commanded Next',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 6554),
    ),
    6560 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Text Commanded Skip',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 6554),
    ),
    6561 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'User Sent Manual Quick Reply',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 4277),
    ),
    6563 => array(
        'm_icon' => '<i class="far fa-ballot-check"></i>',
        'm_name' => 'User Received Quick Reply',
        'm_desc' => '',
        'm_parents' => array(4593, 4755, 4280),
    ),
    6578 => array(
        'm_icon' => '<i class="fas fa-wand-magic"></i>',
        'm_name' => 'User Text Commanded Stop',
        'm_desc' => '',
        'm_parents' => array(4755, 4593, 6554),
    ),
);

//Entity-to-Entity Links:
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
        'm_name' => 'Text Message',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4595),
    ),
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(4593, 4592, 4537, 4595),
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
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => '',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => '',
        'm_parents' => array(6203, 4593, 4592, 4537, 4595),
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

//Platform Objects:
$config['en_ids_4534'] = array(4535, 4536, 6205);
$config['en_all_4534'] = array(
    4535 => array(
        'm_icon' => '<i class="fas fa-hashtag"></i>',
        'm_name' => 'Intents',
        'm_desc' => '',
        'm_parents' => array(2738, 4534, 4463),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-at"></i>',
        'm_name' => 'Entities',
        'm_desc' => '',
        'm_parents' => array(2738, 4534, 4463),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-link"></i>',
        'm_name' => 'Links',
        'm_desc' => '',
        'm_parents' => array(2738, 4463, 4534),
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
        'm_name' => 'User Unsubscribed',
        'm_desc' => 'Stop all communications until you re-subscribe',
        'm_parents' => array(4593, 4454),
    ),
);

//Intent Notes:
$config['en_ids_4485'] = array(4231, 4983, 4601, 4232, 6242, 6093);
$config['en_all_4485'] = array(
    4231 => array(
        'm_icon' => '<i class="fal fa-comment"></i>',
        'm_name' => 'On-Start Message',
        'm_desc' => 'Delivered in-order when student initially starts this intent. Goal is to give key insights that streamline the execution of the intention.',
        'm_parents' => array(6345, 4986, 4603, 4593, 4485, 4595),
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
        'm_parents' => array(4593, 4595, 4485),
    ),
    4232 => array(
        'm_icon' => '<i class="fal fa-lightbulb-on"></i>',
        'm_name' => 'Bonus Message',
        'm_desc' => 'Delivered in-order and one-by-one (drip-format) either during or after the intent completion. Goal is to re-iterate key insights to help students retain learnings over time.',
        'm_parents' => array(5007, 6345, 4986, 4603, 4593, 4485, 4595),
    ),
    6242 => array(
        'm_icon' => '<i class="fal fa-check-square"></i>',
        'm_name' => 'On-Complete Message',
        'm_desc' => 'Message delivered to students when they complete an intention.',
        'm_parents' => array(5007, 6345, 4603, 4595, 4593, 4986, 4485),
    ),
    6093 => array(
        'm_icon' => '<i class="fal fa-comments"></i>',
        'm_name' => 'Miner Note',
        'm_desc' => 'Similar to Wikipedia\'s Talk pages, the Mench changelog helps miners track the history and evolution of a intent and explain/propose changes/improvements.',
        'm_parents' => array(5007, 4595, 4593, 4986, 4485),
    ),
);

//Intent-to-Intent Links:
$config['en_ids_4486'] = array(4228, 4229);
$config['en_all_4486'] = array(
    4228 => array(
        'm_icon' => '<i class="fal fa-check-circle"></i>',
        'm_name' => 'Fixed Step',
        'm_desc' => 'Intent link published and added to user Action Plans up-front',
        'm_parents' => array(6410, 4593, 4486, 4595),
    ),
    4229 => array(
        'm_icon' => '<i class="fas fa-spin fa-question-circle"></i>',
        'm_name' => 'Conditional Step',
        'm_desc' => 'Intent added to Action Plans after parent intent is complete AND the user\'s % score falls within the defined min/max range',
        'm_parents' => array(6410, 6283, 4593, 4486, 4595),
    ),
);

//Entity-to-Entity URL Link Types:
$config['en_ids_4537'] = array(4256, 4257, 4258, 4259, 4260, 4261);
$config['en_all_4537'] = array(
    4256 => array(
        'm_icon' => '<i class="fal fa-browser"></i>',
        'm_name' => 'URL',
        'm_desc' => 'Link note contains a generic URL only.',
        'm_parents' => array(4593, 4592, 4537, 4595),
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
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4259 => array(
        'm_icon' => '<i class="fal fa-volume-up"></i>',
        'm_name' => 'Audio',
        'm_desc' => 'Link notes contain a URL to a raw audio file.',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4260 => array(
        'm_icon' => '<i class="fal fa-image"></i>',
        'm_name' => 'Image',
        'm_desc' => 'Link notes contain a URL to a raw image file.',
        'm_parents' => array(6203, 4990, 4593, 4592, 4537, 4595),
    ),
    4261 => array(
        'm_icon' => '<i class="fal fa-file-pdf"></i>',
        'm_name' => 'File',
        'm_desc' => 'Link notes contain a URL to a raw file.',
        'm_parents' => array(6203, 4593, 4592, 4537, 4595),
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
        'm_parents' => array(6805, 4990, 3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fal fa-book"></i>',
        'm_name' => 'Expert Books',
        'm_desc' => '&var_weight=100',
        'm_parents' => array(6805, 4990, 3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fal fa-presentation"></i>',
        'm_name' => 'Expert Courses',
        'm_desc' => '&var_weight=50',
        'm_parents' => array(6805, 4990, 3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fal fa-compact-disc"></i>',
        'm_name' => 'Expert Software',
        'm_desc' => '&var_weight=1',
        'm_parents' => array(6805, 4990, 3000),
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
        'm_parents' => array(6805, 4990, 3000),
    ),
    4883 => array(
        'm_icon' => '<i class="fal fa-concierge-bell"></i>',
        'm_name' => 'Expert Services',
        'm_desc' => '&var_weight=1',
        'm_parents' => array(6805, 4990, 3000),
    ),
    5948 => array(
        'm_icon' => '<i class="fal fa-file-invoice"></i>',
        'm_name' => 'Expert Templates',
        'm_desc' => '&var_weight=1',
        'm_parents' => array(4990, 3000),
    ),
);