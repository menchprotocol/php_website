<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
* Keep a cache of certain parts of the idea for faster processing
* See here for more details: https://mench.com/source/4527
*
*/

//Generated 2020-05-01 17:16:11 PST

//PLATFORM STATS:
$config['cache_timestamp'] = 1588378571;
$config['cache_count_transaction'] = 1173237;
$config['cache_count_read'] = 121153;
$config['cache_count_idea'] = 5433;
$config['cache_count_source'] = 5198;



//MAIN MENU:
$config['en_ids_12893'] = array(12581,7347,12896,12898,12749);
$config['en_all_12893'] = array(
    12581 => array(
        'm_icon' => '<i class="fas fa-home read" aria-hidden="true"></i>',
        'm_name' => 'HOME',
        'm_desc' => '/',
        'm_parents' => array(12893,11035),
    ),
    7347 => array(
        'm_icon' => '<i class="fas fa-book read" aria-hidden="true"></i>',
        'm_name' => 'BOOKSHELF',
        'm_desc' => '/read',
        'm_parents' => array(12893,12701,12677,11018,11035,11089,6205,12228,4527),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'HIGHLIGHTS',
        'm_desc' => '/read/highlight',
        'm_parents' => array(12701,12321,4485,7551,11089,11018,11035,4755,4593,12893),
    ),
    12898 => array(
        'm_icon' => '<i class="fas fa-pen idea"></i>',
        'm_name' => 'PUBLISH',
        'm_desc' => '/idea',
        'm_parents' => array(11035,10939,4535,10876,12893),
    ),
    12749 => array(
        'm_icon' => '<i class="fas fa-plus-circle idea"></i>',
        'm_name' => 'MODIFY',
        'm_desc' => '',
        'm_parents' => array(10984,12893,11035),
    ),
);

//SOURCE ADMIN MENU:
$config['en_ids_12887'] = array(12193,4341,12888,7267,12712,7279);
$config['en_all_12887'] = array(
    12193 => array(
        'm_icon' => '<i class="fab fa-google"></i>',
        'm_name' => 'GOOGLE',
        'm_desc' => '/source/search_google/',
        'm_parents' => array(12887,2750,3088),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '/ledger?any_en_id=',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735,6205),
    ),
    12888 => array(
        'm_icon' => '<i class="fas fa-shield-check source"></i>',
        'm_name' => 'SOURCE EXPLORE EXPERTS',
        'm_desc' => '/plugin/12888?en_id=',
        'm_parents' => array(12741,6287,12887),
    ),
    7267 => array(
        'm_icon' => '🔍',
        'm_name' => 'SOURCE ICON SEARCH',
        'm_desc' => '/source/search_icon/',
        'm_parents' => array(12887,6287),
    ),
    12712 => array(
        'm_icon' => '<i class="fad fa-lambda source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REVIEW JSON',
        'm_desc' => '/plugin/12712?en_id=',
        'm_parents' => array(12887,12741,6287),
    ),
    7279 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SYNC SEARCH INDEX',
        'm_desc' => '/cron/cron__7279/en/',
        'm_parents' => array(12887,11047,3323,7287,7274),
    ),
);

//IDEA TYPE SELECT ONE:
$config['en_ids_12883'] = array(6907,6684);
$config['en_all_12883'] = array(
    6907 => array(
        'm_icon' => '<i class="fas fa-cube idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ANY',
        'm_desc' => '',
        'm_parents' => array(12883,12700,12330,7486,7485,6140,7585,7309,6997,6193),
    ),
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT ONE',
        'm_desc' => '',
        'm_parents' => array(12883,12336,12129,7712,7585,6157,6193),
    ),
);

//IDEA TYPE SELECT SOME:
$config['en_ids_12884'] = array(7231);
$config['en_all_12884'] = array(
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SELECT SOME',
        'm_desc' => '',
        'm_parents' => array(12884,12334,12129,7712,7489,7585,6193),
    ),
);

//EXPERT SOURCES:
$config['en_ids_12864'] = array(2750,3084);
$config['en_all_12864'] = array(
    2750 => array(
        'm_icon' => '<i class="fas fa-building source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT ORGANIZATION',
        'm_desc' => '',
        'm_parents' => array(12864,4600),
    ),
    3084 => array(
        'm_icon' => '<i class="fad fa-user-astronaut source" aria-hidden="true"></i>',
        'm_name' => 'INDUSTRY EXPERT',
        'm_desc' => '',
        'm_parents' => array(4600,12864,4983,11035,1278,12523),
    ),
);

//IDEA LINK ONE-WAY:
$config['en_ids_12842'] = array(4229);
$config['en_all_12842'] = array(
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(12842,4527,6410,6283,4593,4486),
    ),
);

//IDEA LINK TWO-WAYS:
$config['en_ids_12840'] = array(4228);
$config['en_all_12840'] = array(
    4228 => array(
        'm_icon' => '<i class="fad fa-play-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK FIXED',
        'm_desc' => '',
        'm_parents' => array(12840,6410,4593,4486),
    ),
);

//SOURCE LINK MESSAGE DISPLAY:
$config['en_ids_12822'] = array(4259,4257,4261,4260,4255,4256,4258);
$config['en_all_12822'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'EMBED',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,6203,4593,4592,4537),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'TEXT',
        'm_desc' => '',
        'm_parents' => array(12822,10593,4593,4592),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
);

//BOOKMARKS:
$config['en_ids_10573'] = array(4430);
$config['en_all_10573'] = array(
    4430 => array(
        'm_icon' => '<i class="far fa-alicorn source" aria-hidden="true"></i>',
        'm_name' => 'MENCH PLAYER',
        'm_desc' => '',
        'm_parents' => array(4983,1278,11035,10573),
    ),
);

//MENCH APPLICATIONS:
$config['en_ids_12744'] = array(7274,6287);
$config['en_all_12744'] = array(
    7274 => array(
        'm_icon' => '<i class="far fa-magic" aria-hidden="true"></i>',
        'm_name' => 'CRON JOBS',
        'm_desc' => '/cron/cron__',
        'm_parents' => array(12744,6194,11035,12728,12500,10876,4527,6405),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => '/plugin/',
        'm_parents' => array(6405,12744,6194,12699,12500,10876,11035,4527),
    ),
);

//PLUGIN EXCLUDE MENCH UI:
$config['en_ids_12741'] = array(11049,12733,4527,12710,12709,12888,12732,12712,12722);
$config['en_all_12741'] = array(
    11049 => array(
        'm_icon' => '<i class="fad fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12741,6287,11047),
    ),
    12733 => array(
        'm_icon' => '<i class="fad fa-code read"></i>',
        'm_name' => 'IDEA REVIEW READ',
        'm_desc' => '',
        'm_parents' => array(11047,12741,12701,6287),
    ),
    4527 => array(
        'm_icon' => '<i class="fas fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY',
        'm_desc' => '',
        'm_parents' => array(4755,6403,12741,6287,4527),
    ),
    12710 => array(
        'm_icon' => '👤',
        'm_name' => 'MY SESSION VARIABLES',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12709 => array(
        'm_icon' => 'ℹ️',
        'm_name' => 'PHP INFO',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12888 => array(
        'm_icon' => '<i class="fas fa-shield-check source"></i>',
        'm_name' => 'SOURCE EXPLORE EXPERTS',
        'm_desc' => '',
        'm_parents' => array(12741,6287,12887),
    ),
    12732 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE/IDEA SYNC STATUSES',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12712 => array(
        'm_icon' => '<i class="fad fa-lambda source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12887,12741,6287),
    ),
    12722 => array(
        'm_icon' => '',
        'm_name' => 'TRANSACTION REVIEW JSON',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
);

//CRON JOBS:
$config['en_ids_7274'] = array(4356,7275,7276,12114,7277,7278,12523,7279,12569);
$config['en_all_7274'] = array(
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA READ TIME (SECONDS)',
        'm_desc' => 'Auto update idea read time based on messages',
        'm_parents' => array(11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    7275 => array(
        'm_icon' => '<i class="fad fa-sync idea"></i>',
        'm_name' => 'IDEA SYNC COMMON BASE',
        'm_desc' => '',
        'm_parents' => array(11047,7286,7274),
    ),
    7276 => array(
        'm_icon' => '<i class="fad fa-sync idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SYNC EXTRA INSIGHTS',
        'm_desc' => '',
        'm_parents' => array(11047,7286,7274),
    ),
    12114 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'MENCH WEEKLY GROWTH REPORT',
        'm_desc' => '',
        'm_parents' => array(12701,7274,7569),
    ),
    7277 => array(
        'm_icon' => '',
        'm_name' => 'METADATA CLEAN VARIABLES',
        'm_desc' => '',
        'm_parents' => array(7287,7274),
    ),
    7278 => array(
        'm_icon' => '',
        'm_name' => 'SYNC GEPHI INDEX',
        'm_desc' => '',
        'm_parents' => array(7311,7287,7274),
    ),
    12523 => array(
        'm_icon' => '<i class="fad fa-sync source" aria-hidden="true"></i>',
        'm_name' => 'SYNC ICONS IF MISSING',
        'm_desc' => '',
        'm_parents' => array(7274,4527,4758),
    ),
    7279 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SYNC SEARCH INDEX',
        'm_desc' => '',
        'm_parents' => array(12887,11047,3323,7287,7274),
    ),
    12569 => array(
        'm_icon' => '<i class="fad fa-weight"></i>',
        'm_name' => 'WEIGHT ALGORITHM',
        'm_desc' => '',
        'm_parents' => array(7274),
    ),
);

//MENCH MESSAGES:
$config['en_ids_12687'] = array(12691,12694,12695);
$config['en_all_12687'] = array(
    12691 => array(
        'm_icon' => '',
        'm_name' => 'EMAIL YOURS TRULY LINE',
        'm_desc' => 'Cheers, | Have an Awesome Day, | Have a Blast, | All The Best, | Enjoy, | Have Fun, | Many Thanks,',
        'm_parents' => array(12687),
    ),
    12694 => array(
        'm_icon' => '',
        'm_name' => 'LOADING MESSAGE',
        'm_desc' => 'Are you having a good day today? | Be gentle with yourself today. | Congratulate yourself for the great job you\'re doing | Crunching the latest data, just for you. Hang tight... | Have a glass of water nearby? Time for a sip! | Offer hugs. Someone probably needs them. | You are unique! | Get a drink of water. Stay hydrated! | Have you danced today? | Have you listened to your favourite song recently? 🎵 | Have you stretched recently? | Have you recently told someone you\'re proud of them? | Help is out there. Don\'t be afraid to ask. | Hey! Life is tough, but so are you! 💪 | Hey, jump up for a sec and stretch, yeah? 👐 | I know it\'s cheesey but I hope you have a grate day! | Is there a window you can look through? The world is beautiful. 🌆 | Is your seat comfortable? Can you adjust your chair properly? | It can be hard to get started, can\'t it? That\'s okay, you got this. | It\'s so great to have you here today | Keep growing, keep learning, keep moving forward! | Learning new things is important - open your eyes to the world around you! | Making things awesome... | Novel, new, silly, & unusual activities can help lift your mood. | Play for a few minutes. Doodle, learn solitaire, fold a paper airplane, do something fun. | Don\'t take yourself for granted. You\'re important. | Rest your eyes for a moment. Look at something in the distance and count to five! 🌳 | Self care is important, look after and love yourself, you\'re amazing! | Set aside time for a hobby. Gardening, drone building, knitting, do something for the pure pleasure of it. | So often our power lies not in ourselves, but in how we help others find their own strength | Sometimes doing something nice for somebody else is the best way to feel good about yourself! 👭 | Stop. Breathe. Be here now. | Stop. Take three slow deep breaths. | Take 5 minutes to straighten the space around you. Set a timer. | Take a break before you need it. It will make it easier to prevent burnout. | Take a moment to send a message to someone you love 😻 | Take care of yourself. We need you. | Technology is a tool. Use it wisely. | The impact you leave on the universe can never be erased. | There are no impostors here | There\'s someone who is so so grateful that you exist together. | Today is a great day to let a friend know how much you appreciate them. | Water is good for you year round. If you\'re thirsty, you\'re dehydrated. | We all have superpowers. You included. I hope you are using yours to make your life a joyful one. | When\'s the last time you treated yourself? | With the dawning of a new day comes a clean slate and lots of opportunity. | You are fantastic | You are loved. <3 | You are so very important 💛💛💕 | You can do this! | You cannot compare your successes to the apparent achievements of others. 🌄 | You deserve to be safe and to have nice things happen to you. | You have the power to change the world. | You\'re allowed to start small. 🐞 | have you hugged anyone lately? | it\'s time to check your thirst level, human. | 💗: don\'t forget to take a little bit of time to say hi to a friend | 🌸: remember to let your eyes rest, maybe by looking at a plant... | 🙌: take a second to adjust your posture | 😎🌈💕',
        'm_parents' => array(12687),
    ),
    12695 => array(
        'm_icon' => '',
        'm_name' => 'SAVING MESSAGE',
        'm_desc' => 'Learning everyday 😎 | Growing with you 🌸 | Getting smarter ^~^',
        'm_parents' => array(12687),
    ),
);

//IDEA LAYOUT DEFAULT SELECTED:
$config['en_ids_12675'] = array(11020);
$config['en_all_12675'] = array(
    11020 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NEXT',
        'm_desc' => '',
        'm_parents' => array(12675,12413,11018),
    ),
);

//IDEA LAYOUT HIDE IF ZERO:
$config['en_ids_12677'] = array(7347,6255,6146);
$config['en_all_12677'] = array(
    7347 => array(
        'm_icon' => '<i class="fas fa-book read" aria-hidden="true"></i>',
        'm_name' => 'BOOKSHELF',
        'm_desc' => '',
        'm_parents' => array(12893,12701,12677,11018,11035,11089,6205,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(12500,12677,10939,11018,12467,11089,12410,6771,12228,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'UNFINISHED',
        'm_desc' => '',
        'm_parents' => array(12701,12677,11018,12446,11089,12228,4527),
    ),
);

//PLAYER MENU:
$config['en_ids_12500'] = array(12205,12437,12274,12273,6255,6415,12899,6287,7274,7291);
$config['en_all_12500'] = array(
    12205 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(12500,11035),
    ),
    12437 => array(
        'm_icon' => '<i class="fas fa-medal source" aria-hidden="true"></i>',
        'm_name' => 'LEADERBOARD',
        'm_desc' => '',
        'm_parents' => array(12897,12500,10876,12489,11035),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(12500,12467,12228,4527,4758),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(4535,12500,12571,12467,12321,12410,11089,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(12500,12677,10939,11018,12467,11089,12410,6771,12228,4527),
    ),
    6415 => array(
        'm_icon' => '<i class="fas fa-trash-alt read" aria-hidden="true"></i>',
        'm_name' => 'CLEAR BOOKSHELF',
        'm_desc' => '',
        'm_parents' => array(12500,4755,4593),
    ),
    12899 => array(
        'm_icon' => '<i class="fas fa-headset"></i>',
        'm_name' => 'FEEDBACK/SUPPORT',
        'm_desc' => '',
        'm_parents' => array(12500,11035),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => '',
        'm_parents' => array(6405,12744,6194,12699,12500,10876,11035,4527),
    ),
    7274 => array(
        'm_icon' => '<i class="far fa-magic" aria-hidden="true"></i>',
        'm_name' => 'CRON JOBS',
        'm_desc' => '',
        'm_parents' => array(12744,6194,11035,12728,12500,10876,4527,6405),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => '',
        'm_parents' => array(12500,10876,11035),
    ),
);

//IDEA NOTES STATUS:
$config['en_ids_12012'] = array(6176,6173);
$config['en_all_12012'] = array(
    6176 => array(
        'm_icon' => '<i class="far fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="fas fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'UNLINKED',
        'm_desc' => '',
        'm_parents' => array(12012,10686,10678,10673,6186),
    ),
);

//MENCH COINS:
$config['en_ids_12467'] = array(12274,12273,6255);
$config['en_all_12467'] = array(
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(12500,12467,12228,4527,4758),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(4535,12500,12571,12467,12321,12410,11089,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(12500,12677,10939,11018,12467,11089,12410,6771,12228,4527),
    ),
);

//NEXT EDITOR:
$config['en_ids_12589'] = array(12591,12592);
$config['en_all_12589'] = array(
    12591 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'ADD SOURCE',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12592 => array(
        'm_icon' => '<i class="fas fa-minus-circle source" aria-hidden="true"></i>',
        'm_name' => 'REMOVE SOURCE',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
);

//AVOID PREFIX REMOVAL:
$config['en_ids_12588'] = array(4341);
$config['en_all_12588'] = array(
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735,6205),
    ),
);

//SIGN IN/UP:
$config['en_ids_4269'] = array(3288,6197,3286);
$config['en_all_4269'] = array(
    3288 => array(
        'm_icon' => '<i class="fad fa-envelope-open source" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => '',
        'm_parents' => array(4269,12103,6225,4755),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'FULL NAME',
        'm_desc' => '',
        'm_parents' => array(6404,12112,4269,12412,12232,10646,5000,4998,4999,6232,6206),
    ),
    3286 => array(
        'm_icon' => '<i class="fad fa-key source" aria-hidden="true"></i>',
        'm_name' => 'PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4269,7578,6225,4755),
    ),
);

//FILE UPLOADING ALLOWED:
$config['en_ids_12359'] = array(12419,4231);
$config['en_all_12359'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12273,10939,12359,12322,10593,4986,4603,4593,4485),
    ),
);

//PORTFOLIO EDITOR UPPERCASE:
$config['en_ids_12577'] = array(4999,4998,5000,5981,11956,5982);
$config['en_all_12577'] = array(
    4999 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fad fa-layer-plus source" aria-hidden="true"></i>',
        'm_name' => 'PROFILE ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="source fad fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PROFILE IF ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="source fad fa-layer-minus"></i>',
        'm_name' => 'PROFILE REMOVE',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
);

//LAYOUT SHOW EVEN IF ZERO:
$config['en_ids_12574'] = array(6225,11029,11030);
$config['en_all_12574'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => '',
        'm_parents' => array(12574,11089,12205,11035,4527),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => '',
        'm_parents' => array(12574,12571,11089,11028),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-id-badge source" aria-hidden="true"></i>',
        'm_name' => 'PROFILE',
        'm_desc' => '',
        'm_parents' => array(12571,12574,11089,11028),
    ),
);

//SOURCE STATUS FEATURED:
$config['en_ids_12575'] = array(12563);
$config['en_all_12575'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
);

//LAYOUT OPEN BY DEFAULT:
$config['en_ids_12571'] = array(12273,11029,11030);
$config['en_all_12571'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(4535,12500,12571,12467,12321,12410,11089,12228,4527),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => '',
        'm_parents' => array(12574,12571,11089,11028),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-id-badge source" aria-hidden="true"></i>',
        'm_name' => 'PROFILE',
        'm_desc' => '',
        'm_parents' => array(12571,12574,11089,11028),
    ),
);

//SOURCE LINK VISUAL:
$config['en_ids_12524'] = array(4259,4257,4261,4260,4258);
$config['en_all_12524'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'EMBED',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
);

//SYNC ICONS IF MISSING:
$config['en_ids_12523'] = array(2997,4446,3005,3147,4763,2999,3192,2998,6293,3084,3308);
$config['en_all_12523'] = array(
    2997 => array(
        'm_icon' => '<i class="fad fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT ARTICLE',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fad fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT ASSESSMENT',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fad fa-book source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT BOOK',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT COURSE',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT MARKETING',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT PODCAST',
        'm_desc' => '',
        'm_parents' => array(4983,12523,10809,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT TOOL',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT VIDEO',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    6293 => array(
        'm_icon' => '<i class="fad fa-image source"></i>',
        'm_name' => 'GIPHY',
        'm_desc' => '',
        'm_parents' => array(12523,1326),
    ),
    3084 => array(
        'm_icon' => '<i class="fad fa-user-astronaut source" aria-hidden="true"></i>',
        'm_name' => 'INDUSTRY EXPERT',
        'm_desc' => '',
        'm_parents' => array(4600,12864,4983,11035,1278,12523),
    ),
    3308 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'YOUTUBE URL',
        'm_desc' => '',
        'm_parents' => array(12523,4257,1326),
    ),
);

//READ ICONS:
$config['en_ids_12446'] = array(12447,12448,6146);
$config['en_all_12446'] = array(
    12447 => array(
        'm_icon' => '<i class="fad fa-spinner-third read fa-spin" aria-hidden="true"></i>',
        'm_name' => 'READ IN PROGRESS',
        'm_desc' => '',
        'm_parents' => array(12446),
    ),
    12448 => array(
        'm_icon' => '<i class="far fa-circle read"></i>',
        'm_name' => 'READ NOT STARTED',
        'm_desc' => '',
        'm_parents' => array(12446),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'UNFINISHED',
        'm_desc' => '',
        'm_parents' => array(12701,12677,11018,12446,11089,12228,4527),
    ),
);

//IDEA TEXT INPUT SHOW ICON:
$config['en_ids_12420'] = array(4356,4358,4739,4735);
$config['en_all_12420'] = array(
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA READ TIME (SECONDS)',
        'm_desc' => '',
        'm_parents' => array(11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    4358 => array(
        'm_icon' => '<i class="fas fa-comment-alt-check idea" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => '',
        'm_parents' => array(12700,12420,12112,10663,6103,6410,6232),
    ),
    4739 => array(
        'm_icon' => '<i class="fas fa-temperature-up idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(12420,12112,6402,6232),
    ),
    4735 => array(
        'm_icon' => '<i class="fas fa-temperature-down idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(12420,12112,6402,6232),
    ),
);

//IDEA TREE:
$config['en_ids_12413'] = array(11020,11019);
$config['en_all_12413'] = array(
    11020 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'NEXT',
        'm_desc' => '',
        'm_parents' => array(12675,12413,11018),
    ),
    11019 => array(
        'm_icon' => '<i class="fas fa-step-backward idea" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS',
        'm_desc' => '',
        'm_parents' => array(12413,10990),
    ),
);

//MENCH URL:
$config['en_ids_10876'] = array(7274,10939,12437,7291,4341,6287,12898);
$config['en_all_10876'] = array(
    7274 => array(
        'm_icon' => '<i class="far fa-magic" aria-hidden="true"></i>',
        'm_name' => 'CRON JOBS',
        'm_desc' => 'https://mench.com/cron',
        'm_parents' => array(12744,6194,11035,12728,12500,10876,4527,6405),
    ),
    10939 => array(
        'm_icon' => '<i class="fad fa-pen idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA PEN',
        'm_desc' => 'https://mench.com/13467',
        'm_parents' => array(10876,10957),
    ),
    12437 => array(
        'm_icon' => '<i class="fas fa-medal source" aria-hidden="true"></i>',
        'm_name' => 'LEADERBOARD',
        'm_desc' => 'https://mench.com/source',
        'm_parents' => array(12897,12500,10876,12489,11035),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => 'https://mench.com/source/signout',
        'm_parents' => array(12500,10876,11035),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => 'https://mench.com/ledger',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735,6205),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => 'https://mench.com/plugin',
        'm_parents' => array(6405,12744,6194,12699,12500,10876,11035,4527),
    ),
    12898 => array(
        'm_icon' => '<i class="fas fa-pen idea"></i>',
        'm_name' => 'PUBLISH',
        'm_desc' => 'https://mench.com/idea',
        'm_parents' => array(11035,10939,4535,10876,12893),
    ),
);

//PLAYER EARNED COINS:
$config['en_ids_12410'] = array(12273,6255);
$config['en_all_12410'] = array(
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(4535,12500,12571,12467,12321,12410,11089,12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(12500,12677,10939,11018,12467,11089,12410,6771,12228,4527),
    ),
);

//SOURCE LINK TYPE CUSTOM UI:
$config['en_ids_12403'] = array(4257);
$config['en_all_12403'] = array(
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK EMBED',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
);

//SOURCE STATUS SYNC:
$config['en_ids_12401'] = array(4251,6178,10654);
$config['en_all_12401'] = array(
    4251 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'CREATED',
        'm_desc' => '',
        'm_parents' => array(12274,12401,12149,12141,10593,4593),
    ),
    6178 => array(
        'm_icon' => '<i class="fad fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'DELETED',
        'm_desc' => '',
        'm_parents' => array(4593,12401,6177),
    ),
    10654 => array(
        'm_icon' => '<i class="fad fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(12401,4593),
    ),
);

//IDEA SYNC STATUS:
$config['en_ids_12400'] = array(4250,6182,10648);
$config['en_all_12400'] = array(
    4250 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,12149,10593,4593),
    ),
    6182 => array(
        'm_icon' => '<i class="fad fa-trash-alt idea" aria-hidden="true"></i>',
        'm_name' => 'DELETED',
        'm_desc' => '',
        'm_parents' => array(12400,4593,4737),
    ),
    10648 => array(
        'm_icon' => '<i class="fad fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(12400,4593),
    ),
);

//SOURCE:
$config['en_ids_4536'] = array(11089,7305,12897,4758,6206,4600);
$config['en_all_4536'] = array(
    11089 => array(
        'm_icon' => '<i class="fad fa-crop-alt source" aria-hidden="true"></i>',
        'm_name' => 'LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4536,4527),
    ),
    7305 => array(
        'm_icon' => '<i class="fas fa-layer-group source" aria-hidden="true"></i>',
        'm_name' => 'PLATFORM',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    12897 => array(
        'm_icon' => '<i class="fas fa-gamepad source"></i>',
        'm_name' => 'PLAYERS',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    4758 => array(
        'm_icon' => '<i class="fas fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'SETTINGS',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
    6206 => array(
        'm_icon' => '<i class="fas fa-table source" aria-hidden="true"></i>',
        'm_name' => 'TABLE',
        'm_desc' => '',
        'm_parents' => array(4527,7735,4536),
    ),
    4600 => array(
        'm_icon' => '<i class="fad fa-shapes source"></i>',
        'm_name' => 'TYPES',
        'm_desc' => '',
        'm_parents' => array(4536),
    ),
);

//IDEA TYPE INSTANTLY DONE:
$config['en_ids_12330'] = array(6677,6914,6907);
$config['en_all_12330'] = array(
    6677 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'READ & NEXT',
        'm_desc' => '',
        'm_parents' => array(12330,7585,4559,6192),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ALL',
        'm_desc' => '',
        'm_parents' => array(12700,12330,7486,7485,6140,6192,7585,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ANY',
        'm_desc' => '',
        'm_parents' => array(12883,12700,12330,7486,7485,6140,7585,7309,6997,6193),
    ),
);

//READ UNLOCKS:
$config['en_ids_12327'] = array(7485,7486,6997);
$config['en_all_12327'] = array(
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//READ EXPANSIONS:
$config['en_ids_12326'] = array(12336,12334,6140);
$config['en_all_12326'] = array(
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
);

//IDEA TYPE MANUAL INPUT:
$config['en_ids_12324'] = array(6683,7637);
$config['en_all_12324'] = array(
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard idea" aria-hidden="true"></i>',
        'm_name' => 'REPLY & NEXT',
        'm_desc' => '',
        'm_parents' => array(12324,6144,7585,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip idea" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD & NEXT',
        'm_desc' => '',
        'm_parents' => array(12324,12117,7751,7585,6192),
    ),
);

//TEMPLATE IDEA MESSAGES:
$config['en_ids_12322'] = array(12419,4601,4231);
$config['en_all_12322'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags idea" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12322,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12273,10939,12359,12322,10593,4986,4603,4593,4485),
    ),
);

//TEMPLATE IDEA READ:
$config['en_ids_12321'] = array(7545,10573,12896,12273,12682);
$config['en_all_12321'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-user-plus source" aria-hidden="true"></i>',
        'm_name' => 'ADD PROFILE',
        'm_desc' => '',
        'm_parents' => array(12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(12898,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'HIGHLIGHTS',
        'm_desc' => '',
        'm_parents' => array(12701,12321,4485,7551,11089,11018,11035,4755,4593,12893),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(4535,12500,12571,12467,12321,12410,11089,12228,4527),
    ),
    12682 => array(
        'm_icon' => '<i class="fas fa-lightbulb-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'REQUIREMENTS',
        'm_desc' => '',
        'm_parents' => array(4593,12321,7551,11089,10986,11018,4485),
    ),
);

//AVATARS SUPER:
$config['en_ids_12279'] = array(12280,12281,12282,12286,12287,12288,12308,12309,12310,12234,12233,10965,12236,12235,10979,12295,12294,12293,12296,12297,12298,12300,12301,12299,12237,12238,10978,12314,12315,12316,12240,12239,10963,12241,12242,12207,12244,12243,10966,12245,12246,10976,12248,12247,10962,12249,12250,10975,12252,12251,10982,12253,12254,10970,12302,12303,12304,12256,12255,10972,12306,12307,12305,12257,12258,10969,12312,12313,12311,12260,12259,10960,12277,12276,12278,12439,12262,10981,12264,12263,10968,12265,12266,10974,12290,12291,12292,12268,12267,12206,12269,12270,10958,12285,12284,12283,12272,12271,12231);
$config['en_all_12279'] = array(
    12280 => array(
        'm_icon' => '<i class="fas fa-alicorn source"></i>',
        'm_name' => 'ALICORN BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12281 => array(
        'm_icon' => '<i class="far fa-alicorn source"></i>',
        'm_name' => 'ALICORN LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12282 => array(
        'm_icon' => '<i class="fad fa-alicorn source"></i>',
        'm_name' => 'ALICORN MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12286 => array(
        'm_icon' => '<i class="fas fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12287 => array(
        'm_icon' => '<i class="far fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12288 => array(
        'm_icon' => '<i class="fad fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12308 => array(
        'm_icon' => '<i class="fas fa-spider-black-widow source"></i>',
        'm_name' => 'BLACK WIDOW BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12309 => array(
        'm_icon' => '<i class="far fa-spider-black-widow source"></i>',
        'm_name' => 'BLACK WIDOW LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12310 => array(
        'm_icon' => '<i class="fad fa-spider-black-widow source"></i>',
        'm_name' => 'BLACK WIDOW MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12234 => array(
        'm_icon' => '<i class="fas fa-dog source"></i>',
        'm_name' => 'DOGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12233 => array(
        'm_icon' => '<i class="far fa-dog source"></i>',
        'm_name' => 'DOGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10965 => array(
        'm_icon' => '<i class="fad fa-dog source"></i>',
        'm_name' => 'DOGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12236 => array(
        'm_icon' => '<i class="fas fa-duck source" aria-hidden="true"></i>',
        'm_name' => 'DONALD BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12235 => array(
        'm_icon' => '<i class="far fa-duck source" aria-hidden="true"></i>',
        'm_name' => 'DONALD LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10979 => array(
        'm_icon' => '<i class="fad fa-duck source"></i>',
        'm_name' => 'DONALD MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12295 => array(
        'm_icon' => '<i class="fas fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12294 => array(
        'm_icon' => '<i class="far fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12293 => array(
        'm_icon' => '<i class="fad fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12296 => array(
        'm_icon' => '<i class="fas fa-dragon source"></i>',
        'm_name' => 'DRAGON BOLD',
        'm_desc' => '',
        'm_parents' => array(10967,12279),
    ),
    12297 => array(
        'm_icon' => '<i class="far fa-dragon source"></i>',
        'm_name' => 'DRAGON LIGHT',
        'm_desc' => '',
        'm_parents' => array(10967,12279),
    ),
    12298 => array(
        'm_icon' => '<i class="fad fa-dragon source"></i>',
        'm_name' => 'DRAGON MIX',
        'm_desc' => '',
        'm_parents' => array(10967,12279),
    ),
    12300 => array(
        'm_icon' => '<i class="fas fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12301 => array(
        'm_icon' => '<i class="far fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12299 => array(
        'm_icon' => '<i class="fad fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12237 => array(
        'm_icon' => '<i class="fas fa-fish source" aria-hidden="true"></i>',
        'm_name' => 'FISHY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12238 => array(
        'm_icon' => '<i class="far fa-fish source" aria-hidden="true"></i>',
        'm_name' => 'FISHY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10978 => array(
        'm_icon' => '<i class="fad fa-fish source"></i>',
        'm_name' => 'FISHY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12314 => array(
        'm_icon' => '<i class="fas fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12315 => array(
        'm_icon' => '<i class="far fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12316 => array(
        'm_icon' => '<i class="fad fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12240 => array(
        'm_icon' => '<i class="fas fa-hippo source" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12239 => array(
        'm_icon' => '<i class="far fa-hippo source" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10963 => array(
        'm_icon' => '<i class="fad fa-hippo source"></i>',
        'm_name' => 'HIPPOY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12241 => array(
        'm_icon' => '<i class="fas fa-badger-honey source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12242 => array(
        'm_icon' => '<i class="far fa-badger-honey source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12207 => array(
        'm_icon' => '<i class="fad fa-badger-honey source"></i>',
        'm_name' => 'HONEY BADGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12244 => array(
        'm_icon' => '<i class="fas fa-deer source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12243 => array(
        'm_icon' => '<i class="far fa-deer source" aria-hidden="true"></i>',
        'm_name' => 'HONEY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10966 => array(
        'm_icon' => '<i class="fad fa-deer source"></i>',
        'm_name' => 'HONEY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12245 => array(
        'm_icon' => '<i class="fas fa-horse source" aria-hidden="true"></i>',
        'm_name' => 'HORSY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12246 => array(
        'm_icon' => '<i class="far fa-horse source" aria-hidden="true"></i>',
        'm_name' => 'HORSY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10976 => array(
        'm_icon' => '<i class="fad fa-horse source"></i>',
        'm_name' => 'HORSY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12248 => array(
        'm_icon' => '<i class="fas fa-monkey source" aria-hidden="true"></i>',
        'm_name' => 'HUMAN BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12247 => array(
        'm_icon' => '<i class="far fa-monkey source" aria-hidden="true"></i>',
        'm_name' => 'HUMAN LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10962 => array(
        'm_icon' => '<i class="fad fa-monkey source"></i>',
        'm_name' => 'HUMAN MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12249 => array(
        'm_icon' => '<i class="fas fa-kiwi-bird source" aria-hidden="true"></i>',
        'm_name' => 'KIWI BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12250 => array(
        'm_icon' => '<i class="far fa-kiwi-bird source" aria-hidden="true"></i>',
        'm_name' => 'KIWI LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10975 => array(
        'm_icon' => '<i class="fad fa-kiwi-bird source"></i>',
        'm_name' => 'KIWI MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12252 => array(
        'm_icon' => '<i class="fas fa-cat source" aria-hidden="true"></i>',
        'm_name' => 'MIMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12251 => array(
        'm_icon' => '<i class="far fa-cat source" aria-hidden="true"></i>',
        'm_name' => 'MIMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10982 => array(
        'm_icon' => '<i class="fad fa-cat source"></i>',
        'm_name' => 'MIMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12253 => array(
        'm_icon' => '<i class="fas fa-cow source" aria-hidden="true"></i>',
        'm_name' => 'MOMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12254 => array(
        'm_icon' => '<i class="far fa-cow source" aria-hidden="true"></i>',
        'm_name' => 'MOMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10970 => array(
        'm_icon' => '<i class="fad fa-cow source"></i>',
        'm_name' => 'MOMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12302 => array(
        'm_icon' => '<i class="fas fa-narwhal source"></i>',
        'm_name' => 'NARWHAL BOLD',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12303 => array(
        'm_icon' => '<i class="far fa-narwhal source"></i>',
        'm_name' => 'NARWHAL LIGHT',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12304 => array(
        'm_icon' => '<i class="fad fa-narwhal source"></i>',
        'm_name' => 'NARWHAL MIX',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12256 => array(
        'm_icon' => '<i class="fas fa-turtle source" aria-hidden="true"></i>',
        'm_name' => 'NINJA BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12255 => array(
        'm_icon' => '<i class="far fa-turtle source" aria-hidden="true"></i>',
        'm_name' => 'NINJA LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10972 => array(
        'm_icon' => '<i class="fad fa-turtle source"></i>',
        'm_name' => 'NINJA MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12306 => array(
        'm_icon' => '<i class="fas fa-pegasus source"></i>',
        'm_name' => 'PEGASUS BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12307 => array(
        'm_icon' => '<i class="far fa-pegasus source"></i>',
        'm_name' => 'PEGASUS LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12305 => array(
        'm_icon' => '<i class="fad fa-pegasus source" aria-hidden="true"></i>',
        'm_name' => 'PEGASUS MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12257 => array(
        'm_icon' => '<i class="fas fa-pig source" aria-hidden="true"></i>',
        'm_name' => 'PIGGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12258 => array(
        'm_icon' => '<i class="far fa-pig source" aria-hidden="true"></i>',
        'm_name' => 'PIGGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10969 => array(
        'm_icon' => '<i class="fad fa-pig source"></i>',
        'm_name' => 'PIGGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12312 => array(
        'm_icon' => '<i class="fas fa-ram source"></i>',
        'm_name' => 'RAM BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12313 => array(
        'm_icon' => '<i class="far fa-ram source"></i>',
        'm_name' => 'RAM LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12311 => array(
        'm_icon' => '<i class="fad fa-ram source"></i>',
        'm_name' => 'RAM MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12260 => array(
        'm_icon' => '<i class="fas fa-rabbit source" aria-hidden="true"></i>',
        'm_name' => 'ROGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12259 => array(
        'm_icon' => '<i class="far fa-rabbit source" aria-hidden="true"></i>',
        'm_name' => 'ROGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10960 => array(
        'm_icon' => '<i class="fad fa-rabbit source"></i>',
        'm_name' => 'ROGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12277 => array(
        'm_icon' => '<i class="fas fa-deer-rudolph source" aria-hidden="true"></i>',
        'm_name' => 'RUDOLPH BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12276 => array(
        'm_icon' => '<i class="far fa-deer-rudolph source" aria-hidden="true"></i>',
        'm_name' => 'RUDOLPH LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12278 => array(
        'm_icon' => '<i class="fad fa-deer-rudolph source" aria-hidden="true"></i>',
        'm_name' => 'RUDOLPH MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12439 => array(
        'm_icon' => '<i class="fad fa-crow source" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12262 => array(
        'm_icon' => '<i class="far fa-crow source" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10981 => array(
        'm_icon' => '<i class="fad fa-crow source"></i>',
        'm_name' => 'RUSSEL MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12264 => array(
        'm_icon' => '<i class="fas fa-sheep source" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12263 => array(
        'm_icon' => '<i class="far fa-sheep source" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10968 => array(
        'm_icon' => '<i class="fad fa-sheep source"></i>',
        'm_name' => 'SHEEPY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12265 => array(
        'm_icon' => '<i class="fas fa-snake source" aria-hidden="true"></i>',
        'm_name' => 'SNAKY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12266 => array(
        'm_icon' => '<i class="far fa-snake source" aria-hidden="true"></i>',
        'm_name' => 'SNAKY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10974 => array(
        'm_icon' => '<i class="fad fa-snake source"></i>',
        'm_name' => 'SNAKY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12290 => array(
        'm_icon' => '<i class="fas fa-cat-space source"></i>',
        'm_name' => 'SPACE CAT BOLD',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12291 => array(
        'm_icon' => '<i class="far fa-cat-space source"></i>',
        'm_name' => 'SPACE CAT LIGHT',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12292 => array(
        'm_icon' => '<i class="fad fa-cat-space source"></i>',
        'm_name' => 'SPACE CAT MIX',
        'm_desc' => '',
        'm_parents' => array(10984,12279),
    ),
    12268 => array(
        'm_icon' => '<i class="fas fa-spider source" aria-hidden="true"></i>',
        'm_name' => 'SPIDER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12267 => array(
        'm_icon' => '<i class="far fa-spider source" aria-hidden="true"></i>',
        'm_name' => 'SPIDER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12206 => array(
        'm_icon' => '<i class="fad fa-spider source"></i>',
        'm_name' => 'SPIDER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12269 => array(
        'm_icon' => '<i class="fas fa-squirrel source" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12270 => array(
        'm_icon' => '<i class="far fa-squirrel source" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10958 => array(
        'm_icon' => '<i class="fad fa-squirrel source"></i>',
        'm_name' => 'SQUIRRELY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12285 => array(
        'm_icon' => '<i class="fas fa-unicorn source"></i>',
        'm_name' => 'UNICORN BOLD',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12284 => array(
        'm_icon' => '<i class="far fa-unicorn source"></i>',
        'm_name' => 'UNICORN LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12283 => array(
        'm_icon' => '<i class="fad fa-unicorn source"></i>',
        'm_name' => 'UNICORN MIX',
        'm_desc' => '',
        'm_parents' => array(12279),
    ),
    12272 => array(
        'm_icon' => '<i class="fas fa-whale source" aria-hidden="true"></i>',
        'm_name' => 'WHALE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12271 => array(
        'm_icon' => '<i class="far fa-whale source" aria-hidden="true"></i>',
        'm_name' => 'WHALE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12231 => array(
        'm_icon' => '<i class="fad fa-whale source"></i>',
        'm_name' => 'WHALE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
);

//SOURCES:
$config['en_ids_12274'] = array(4251);
$config['en_all_12274'] = array(
    4251 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE CREATED',
        'm_desc' => '',
        'm_parents' => array(12274,12401,12149,12141,10593,4593),
    ),
);

//IDEAS:
$config['en_ids_12273'] = array(4983,4231);
$config['en_all_12273'] = array(
    4983 => array(
        'm_icon' => '<i class="fas fa-comment source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12273,10939,12359,12322,10593,4986,4603,4593,4485),
    ),
);

//READ COMPLETION:
$config['en_ids_12229'] = array(6143,7492,6157,7489,12117,4559,6144,7485,7486,6997);
$config['en_all_12229'] = array(
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//READ PROGRESS:
$config['en_ids_12227'] = array(12336,12334,4235,7495,6143,7492,6140,6157,7489,12117,4559,6144,7485,7486,6997);
$config['en_all_12227'] = array(
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    4235 => array(
        'm_icon' => '<i class="fad fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ INITIATED',
        'm_desc' => '',
        'm_parents' => array(12227,7347,5967,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone read" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(12227,7347,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//TRANSACTION TYPE COIN AWARD:
$config['en_ids_12141'] = array(4983,6157,7489,4251,12117,4559,6144,7485,7486,6997);
$config['en_all_12141'] = array(
    4983 => array(
        'm_icon' => '<i class="fas fa-comment source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE CREATED',
        'm_desc' => '',
        'm_parents' => array(12274,12401,12149,12141,10593,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//IDEA STATUS FEATURED:
$config['en_ids_12138'] = array(12137);
$config['en_all_12138'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA FEATURED',
        'm_desc' => '',
        'm_parents' => array(10986,10648,12138,7356,7355,4737),
    ),
);

//MENCH TEXT INPUTS:
$config['en_ids_12112'] = array(4535,4356,4736,4358,6197,4739,4735);
$config['en_all_12112'] = array(
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA',
        'm_desc' => '',
        'm_parents' => array(12761,12112,12155,2738),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA READ TIME (SECONDS)',
        'm_desc' => '',
        'm_parents' => array(11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TITLE',
        'm_desc' => '',
        'm_parents' => array(6404,10990,12112,10644,6232,6201),
    ),
    4358 => array(
        'm_icon' => '<i class="fas fa-comment-alt-check idea" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => '',
        'm_parents' => array(12700,12420,12112,10663,6103,6410,6232),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FULL NAME',
        'm_desc' => '',
        'm_parents' => array(6404,12112,4269,12412,12232,10646,5000,4998,4999,6232,6206),
    ),
    4739 => array(
        'm_icon' => '<i class="fas fa-temperature-up idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(12420,12112,6402,6232),
    ),
    4735 => array(
        'm_icon' => '<i class="fas fa-temperature-down idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN PERCENTAGE',
        'm_desc' => '',
        'm_parents' => array(12420,12112,6402,6232),
    ),
);

//MENCH DROPDOWN MENUS:
$config['en_ids_12079'] = array(4486,4737,7585,12500);
$config['en_all_12079'] = array(
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => '',
        'm_parents' => array(12700,11054,6232,12079,10662,4527),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    12500 => array(
        'm_icon' => '<i class="fas fa-user" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MENU',
        'm_desc' => '',
        'm_parents' => array(12079,12497,12823,4527),
    ),
);

//SOURCE LAYOUT:
$config['en_ids_11089'] = array(6225,10573,12273,12419,7347,12896,6255,6146,11030,12682,7545,11029);
$config['en_all_11089'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => '',
        'm_parents' => array(12574,11089,12205,11035,4527),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(12898,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(4535,12500,12571,12467,12321,12410,11089,12228,4527),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    7347 => array(
        'm_icon' => '<i class="fas fa-book read" aria-hidden="true"></i>',
        'm_name' => 'BOOKSHELF',
        'm_desc' => '',
        'm_parents' => array(12893,12701,12677,11018,11035,11089,6205,12228,4527),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'HIGHLIGHTS',
        'm_desc' => '',
        'm_parents' => array(12701,12321,4485,7551,11089,11018,11035,4755,4593,12893),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(12500,12677,10939,11018,12467,11089,12410,6771,12228,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'UNFINISHED',
        'm_desc' => '',
        'm_parents' => array(12701,12677,11018,12446,11089,12228,4527),
    ),
    11030 => array(
        'm_icon' => '<i class="fas fa-id-badge source" aria-hidden="true"></i>',
        'm_name' => 'PROFILE',
        'm_desc' => '',
        'm_parents' => array(12571,12574,11089,11028),
    ),
    12682 => array(
        'm_icon' => '<i class="fas fa-lightbulb-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'REQUIREMENTS',
        'm_desc' => '',
        'm_parents' => array(4593,12321,7551,11089,10986,11018,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-user-plus source" aria-hidden="true"></i>',
        'm_name' => 'ADD PROFILE',
        'm_desc' => '',
        'm_parents' => array(12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    11029 => array(
        'm_icon' => '<i class="fad fa-sitemap source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO',
        'm_desc' => '',
        'm_parents' => array(12574,12571,11089,11028),
    ),
);

//IT:
$config['en_ids_10717'] = array(10761,10762,10763,10764,10765);
$config['en_all_10717'] = array(
    10761 => array(
        'm_icon' => '<i class="far fa-file-certificate"></i>',
        'm_name' => 'IT CERTIFICATION',
        'm_desc' => '',
        'm_parents' => array(10717),
    ),
    10762 => array(
        'm_icon' => '<i class="far fa-network-wired"></i>',
        'm_name' => 'NETWORK & SECURITY',
        'm_desc' => '',
        'm_parents' => array(10717),
    ),
    10763 => array(
        'm_icon' => '<i class="far fa-hdd"></i>',
        'm_name' => 'HARDWARE',
        'm_desc' => '',
        'm_parents' => array(10717),
    ),
    10764 => array(
        'm_icon' => '<i class="far fa-laptop-code"></i>',
        'm_name' => 'OPERATING SYSTEMS',
        'm_desc' => '',
        'm_parents' => array(10717),
    ),
    10765 => array(
        'm_icon' => '<i class="far fa-window"></i>',
        'm_name' => 'OTHER IT & SOFWTARE',
        'm_desc' => '',
        'm_parents' => array(10717),
    ),
);

//READ ALL CONNECTIONS:
$config['en_ids_11081'] = array(4364,4369,4429,4368,4366,4371,4593);
$config['en_all_11081'] = array(
    4364 => array(
        'm_icon' => '<i class="fad fa-user-edit" aria-hidden="true"></i>',
        'm_name' => 'CREATOR',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-step-forward" aria-hidden="true"></i>',
        'm_name' => 'NEXT IDEA',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-step-backward" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS IDEA',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-id-badge" aria-hidden="true"></i>',
        'm_name' => 'PROFILE SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fad fa-link" aria-hidden="true"></i>',
        'm_name' => 'REFERENCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//MENCH VARIABLE:
$config['en_ids_6232'] = array(6203,6202,4486,6159,6208,6168,6283,12885,6228,6165,6162,6170,6161,6169,6167,4356,4737,4736,7585,4358,6197,6198,6160,6172,6207,6177,4364,7694,4367,4372,6103,4369,4429,4368,4366,4370,4371,6186,4362,4593,4739,4735);
$config['en_all_6232'] = array(
    6203 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'FACEBOOK ATTACHMENT ID',
        'm_desc' => 'fb_att_id',
        'm_parents' => array(6232,6215,2793,6103),
    ),
    6202 => array(
        'm_icon' => '<i class="fas fa-hashtag idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA ID',
        'm_desc' => 'in_id',
        'm_parents' => array(6232,6215,6201),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => 'ln_type_source_id',
        'm_parents' => array(12700,11054,6232,12079,10662,4527),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA METADATA',
        'm_desc' => 'in_metadata',
        'm_parents' => array(11049,6232,6201,6195),
    ),
    6208 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA ALGOLIA ID',
        'm_desc' => 'in__algolia_id',
        'm_parents' => array(6232,6215,3323,6159),
    ),
    6168 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA COMMON STEPS',
        'm_desc' => 'in__metadata_common_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6283 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA EXPANSION CONDITIONAL',
        'm_desc' => 'in__metadata_expansion_conditional',
        'm_parents' => array(6214,6232,6159),
    ),
    12885 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA EXPANSION SOME',
        'm_desc' => 'in__metadata_expansion_some',
        'm_parents' => array(6214,6232,6159),
    ),
    6228 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA EXPANSION STEPS',
        'm_desc' => 'in__metadata_expansion_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6165 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA EXPERTS',
        'm_desc' => 'in__metadata_experts',
        'm_parents' => array(6232,6214,6159),
    ),
    6162 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'IDEA METADATA MAXIMUM SECONDS',
        'm_desc' => 'in__metadata_max_seconds',
        'm_parents' => array(4739,6232,6214,4356,6159),
    ),
    6170 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA MAXIMUM STEPS',
        'm_desc' => 'in__metadata_max_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6161 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA MINIMUM SECONDS',
        'm_desc' => 'in__metadata_min_seconds',
        'm_parents' => array(4735,6232,6214,4356,6159),
    ),
    6169 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA MINIMUM STEPS',
        'm_desc' => 'in__metadata_min_steps',
        'm_parents' => array(6232,6214,6159),
    ),
    6167 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'IDEA METADATA SOURCES',
        'm_desc' => 'in__metadata_sources',
        'm_parents' => array(6232,6214,6159),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA READ TIME (SECONDS)',
        'm_desc' => 'in_time_seconds',
        'm_parents' => array(11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => 'in_status_source_id',
        'm_parents' => array(11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TITLE',
        'm_desc' => 'in_title',
        'm_parents' => array(6404,10990,12112,10644,6232,6201),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => 'in_type_source_id',
        'm_parents' => array(11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    4358 => array(
        'm_icon' => '<i class="fas fa-comment-alt-check idea" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => 'tr__assessment_points',
        'm_parents' => array(12700,12420,12112,10663,6103,6410,6232),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FULL NAME',
        'm_desc' => 'en_name',
        'm_parents' => array(6404,12112,4269,12412,12232,10646,5000,4998,4999,6232,6206),
    ),
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ICON',
        'm_desc' => 'en_icon',
        'm_parents' => array(12605,10653,5943,10625,6232,6206),
    ),
    6160 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ID',
        'm_desc' => 'en_id',
        'm_parents' => array(6232,6215,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda source"></i>',
        'm_name' => 'SOURCE METADATA',
        'm_desc' => 'en_metadata',
        'm_parents' => array(6232,6206,6195),
    ),
    6207 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'SOURCE METADATA ALGOLIA ID',
        'm_desc' => 'en__algolia_id',
        'm_parents' => array(3323,6232,6215,6172),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => 'en_status_source_id',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    4364 => array(
        'm_icon' => '<i class="fad fa-user-edit" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION CREATOR',
        'm_desc' => 'ln_creator_source_id',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    7694 => array(
        'm_icon' => '<i class="fad fa-project-diagram"></i>',
        'm_name' => 'TRANSACTION EXTERNAL ID',
        'm_desc' => 'ln_external_id',
        'm_parents' => array(6215,6232,4341),
    ),
    4367 => array(
        'm_icon' => '<i class="fad fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION ID',
        'm_desc' => 'ln_id',
        'm_parents' => array(6232,6215,4341),
    ),
    4372 => array(
        'm_icon' => '<i class="fad fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION MESSAGE',
        'm_desc' => 'ln_content',
        'm_parents' => array(7578,10679,10657,5001,6232,4341),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'TRANSACTION METADATA',
        'm_desc' => 'ln_metadata',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-step-forward" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION NEXT IDEA',
        'm_desc' => 'ln_next_idea_id',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PORTFOLIO SOURCE',
        'm_desc' => 'ln_portfolio_source_id',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-step-backward" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PREVIOUS IDEA',
        'm_desc' => 'ln_previous_idea_id',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-id-badge" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PROFILE SOURCE',
        'm_desc' => 'ln_profile_source_id',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fad fa-bars" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION RANK',
        'm_desc' => 'ln_order',
        'm_parents' => array(10676,10675,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fad fa-link" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION REFERENCE',
        'm_desc' => 'ln_parent_transaction_id',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => 'ln_status_source_id',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="fad fa-clock" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TIME',
        'm_desc' => 'ln_timestamp',
        'm_parents' => array(6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => 'ln_type_source_id',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
    4739 => array(
        'm_icon' => '<i class="fas fa-temperature-up idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MAX PERCENTAGE',
        'm_desc' => 'tr__conditional_score_max',
        'm_parents' => array(12420,12112,6402,6232),
    ),
    4735 => array(
        'm_icon' => '<i class="fas fa-temperature-down idea" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK MIN PERCENTAGE',
        'm_desc' => 'tr__conditional_score_min',
        'm_parents' => array(12420,12112,6402,6232),
    ),
);

//SOURCE LINK FILE EXTENSIONS:
$config['en_ids_11080'] = array(4259,4261,4260,4256,4258);
$config['en_all_11080'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'pcm|wav|aiff|mp3|aac|ogg|wma|flac|alac|m4a|m4b|m4p',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'pdf|pdc|doc|docx|tex|txt|7z|rar|zip|csv|sql|tar|xml|exe',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'jpeg|jpg|png|gif|tiff|bmp|img|svg|ico|webp',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,6203,4593,4592,4537),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => 'htm|html',
        'm_parents' => array(12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'mp4|m4v|m4p|avi|mov|flv|f4v|f4p|f4a|f4b|wmv|webm|mkv|vob|ogv|ogg|3gp|mpg|mpeg|m2v',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
);

//SOURCE LINK UPLOAD FILE:
$config['en_ids_11059'] = array(4259,4261,4260,4258);
$config['en_all_11059'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'audio',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'file',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'image',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,6203,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'video',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
);

//MENCH CONFIG VARIABLES:
$config['en_ids_6404'] = array(12678,12176,4485,4356,4736,11064,11065,11063,11060,12156,11079,11066,11057,11056,12331,12427,12088,6197,11986,12232,12565,12568);
$config['en_all_6404'] = array(
    12678 => array(
        'm_icon' => '',
        'm_name' => 'ALGOLIA SEARCH ENABLED (0 OR 1)',
        'm_desc' => '1',
        'm_parents' => array(3323,6404),
    ),
    12176 => array(
        'm_icon' => '<i class="fad fa-clock idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DEFAULT TIME SECONDS',
        'm_desc' => '30',
        'm_parents' => array(6404),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES',
        'm_desc' => '1000',
        'm_parents' => array(6404,4535,4527),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA READ TIME (SECONDS)',
        'm_desc' => '7200',
        'm_parents' => array(11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TITLE',
        'm_desc' => '100',
        'm_parents' => array(6404,10990,12112,10644,6232,6201),
    ),
    11064 => array(
        'm_icon' => '',
        'm_name' => 'ITEMS PER PAGE',
        'm_desc' => '100',
        'm_parents' => array(6404),
    ),
    11065 => array(
        'm_icon' => '',
        'm_name' => 'MAGIC LINK VALID SECONDS',
        'm_desc' => '3600',
        'm_parents' => array(6404),
    ),
    11063 => array(
        'm_icon' => '',
        'm_name' => 'MAX FILE SIZE [MB]',
        'm_desc' => '25',
        'm_parents' => array(6404),
    ),
    11060 => array(
        'm_icon' => '',
        'm_name' => 'MENCH PLATFORM VERSION',
        'm_desc' => 'v1.361',
        'm_parents' => array(6404),
    ),
    12156 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'MENCH PRIMARY IDEA',
        'm_desc' => '7766',
        'm_parents' => array(6404),
    ),
    11079 => array(
        'm_icon' => '',
        'm_name' => 'MENCH TIMEZONE',
        'm_desc' => 'America/Los_Angeles',
        'm_parents' => array(6404),
    ),
    11066 => array(
        'm_icon' => '',
        'm_name' => 'PASSWORD MIN CHARACTERS',
        'm_desc' => '6',
        'm_parents' => array(6404),
    ),
    11057 => array(
        'm_icon' => '',
        'm_name' => 'READ MARKS MAX',
        'm_desc' => '89',
        'm_parents' => array(6404,4358),
    ),
    11056 => array(
        'm_icon' => '',
        'm_name' => 'READ MARKS MIN',
        'm_desc' => '-89',
        'm_parents' => array(6404,4358),
    ),
    12331 => array(
        'm_icon' => '',
        'm_name' => 'READ MIN TIME SHOW',
        'm_desc' => '120',
        'm_parents' => array(6404),
    ),
    12427 => array(
        'm_icon' => '',
        'm_name' => 'READ TIME MIN. SECONDS',
        'm_desc' => '3',
        'm_parents' => array(6404,4356),
    ),
    12088 => array(
        'm_icon' => '',
        'm_name' => 'SHOW TEXT COUNTER THRESHOLD',
        'm_desc' => '0.8',
        'm_parents' => array(6404),
    ),
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FULL NAME',
        'm_desc' => '233',
        'm_parents' => array(6404,12112,4269,12412,12232,10646,5000,4998,4999,6232,6206),
    ),
    11986 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE LIST VISIBLE',
        'm_desc' => '10',
        'm_parents' => array(6404),
    ),
    12232 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE NAME MIN LENGTH',
        'm_desc' => '2',
        'm_parents' => array(6404),
    ),
    12565 => array(
        'm_icon' => '',
        'm_name' => 'WEIGHT ALGORITHM RATE',
        'm_desc' => '89',
        'm_parents' => array(12569,6404),
    ),
    12568 => array(
        'm_icon' => '',
        'm_name' => 'WEIGHT ALGORITHM TRANSACTION RATE',
        'm_desc' => '1',
        'm_parents' => array(12569,6404),
    ),
);

//MENCH MEMORY JAVASCRIPT:
$config['en_ids_11054'] = array(10573,3000,4486,4983,4737,7356,7355,6201,7585,2738,6404,12687,4592,6177,7357,6186);
$config['en_all_11054'] = array(
    10573 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(12898,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    3000 => array(
        'm_icon' => '<i class="fad fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT CHANNEL',
        'm_desc' => '',
        'm_parents' => array(11054,11035,4600,4527),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => '',
        'm_parents' => array(12700,11054,6232,12079,10662,4527),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-comment source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'IDEA STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    6201 => array(
        'm_icon' => '<i class="fas fa-table idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TABLE',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7735,4535),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    2738 => array(
        'm_icon' => '<img src="https://mench.com/img/mench.png" class="mench-spin no-radius">',
        'm_name' => 'MENCH',
        'm_desc' => '',
        'm_parents' => array(12497,2,11054,12041,4527,1,7312),
    ),
    6404 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'MENCH CONFIG VARIABLES',
        'm_desc' => '',
        'm_parents' => array(11054,4527,6403),
    ),
    12687 => array(
        'm_icon' => '<i class="fad fa-comments-alt" aria-hidden="true"></i>',
        'm_name' => 'MENCH MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6403,11054,4527),
    ),
    4592 => array(
        'm_icon' => '<i class="fas fa-link source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINKS',
        'm_desc' => '',
        'm_parents' => array(11054,5982,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    7357 => array(
        'm_icon' => '<i class="far fa-globe source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(12572,11054,4527),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
);

//IDEA ADMIN MENU:
$config['en_ids_11047'] = array(7264,4356,11049,12733,7275,7276,4341,7279);
$config['en_all_11047'] = array(
    7264 => array(
        'm_icon' => '<i class="fad fa-sitemap idea"></i>',
        'm_name' => 'IDEA BIRDS EYE MARKS',
        'm_desc' => '/plugin/7264?in_id=',
        'm_parents' => array(11047,6287),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA READ TIME (SECONDS)',
        'm_desc' => '/cron/cron__4356/',
        'm_parents' => array(11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    11049 => array(
        'm_icon' => '<i class="fad fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA REVIEW JSON',
        'm_desc' => '/plugin/11049?in_id=',
        'm_parents' => array(12741,6287,11047),
    ),
    12733 => array(
        'm_icon' => '<i class="fad fa-code read"></i>',
        'm_name' => 'IDEA REVIEW READ',
        'm_desc' => '/plugin/12733?in_id=',
        'm_parents' => array(11047,12741,12701,6287),
    ),
    7275 => array(
        'm_icon' => '<i class="fad fa-sync idea"></i>',
        'm_name' => 'IDEA SYNC COMMON BASE',
        'm_desc' => '/cron/cron__7275/',
        'm_parents' => array(11047,7286,7274),
    ),
    7276 => array(
        'm_icon' => '<i class="fad fa-sync idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SYNC EXTRA INSIGHTS',
        'm_desc' => '/cron/cron__7276/',
        'm_parents' => array(11047,7286,7274),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '/ledger?any_in_id=',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735,6205),
    ),
    7279 => array(
        'm_icon' => '<i class="fad fa-search"></i>',
        'm_name' => 'SYNC SEARCH INDEX',
        'm_desc' => '/cron/cron__7279/in/',
        'm_parents' => array(12887,11047,3323,7287,7274),
    ),
);

//MENCH NAVIGATION:
$config['en_ids_11035'] = array(11068,6225,10573,7347,7274,3000,12899,12707,12896,12581,3084,12437,7291,4341,4430,12749,12205,12211,6287,12750,12898,7256,4269,4997,12275,10957,7540);
$config['en_all_11035'] = array(
    11068 => array(
        'm_icon' => '<i class="fas fa-envelope-open" aria-hidden="true"></i>',
        'm_name' => '1-CLICK LOGIN',
        'm_desc' => '',
        'm_parents' => array(11035,11065),
    ),
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => 'Manage avatar, superpowers, subscription & name',
        'm_parents' => array(12574,11089,12205,11035,4527),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(12898,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    7347 => array(
        'm_icon' => '<i class="fas fa-book read" aria-hidden="true"></i>',
        'm_name' => 'BOOKSHELF',
        'm_desc' => '',
        'm_parents' => array(12893,12701,12677,11018,11035,11089,6205,12228,4527),
    ),
    7274 => array(
        'm_icon' => '<i class="far fa-magic" aria-hidden="true"></i>',
        'm_name' => 'CRON JOBS',
        'm_desc' => '',
        'm_parents' => array(12744,6194,11035,12728,12500,10876,4527,6405),
    ),
    3000 => array(
        'm_icon' => '<i class="fad fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT CHANNEL',
        'm_desc' => '',
        'm_parents' => array(11054,11035,4600,4527),
    ),
    12899 => array(
        'm_icon' => '<i class="fas fa-headset"></i>',
        'm_name' => 'FEEDBACK/SUPPORT',
        'm_desc' => '',
        'm_parents' => array(12500,11035),
    ),
    12707 => array(
        'm_icon' => '<i class="far fa-filter" aria-hidden="true"></i>',
        'm_name' => 'FILTER TRANSACTIONS',
        'm_desc' => '',
        'm_parents' => array(11035,12701),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'HIGHLIGHTS',
        'm_desc' => '',
        'm_parents' => array(12701,12321,4485,7551,11089,11018,11035,4755,4593,12893),
    ),
    12581 => array(
        'm_icon' => '<i class="fas fa-home read" aria-hidden="true"></i>',
        'm_name' => 'HOME',
        'm_desc' => '',
        'm_parents' => array(12893,11035),
    ),
    3084 => array(
        'm_icon' => '<i class="fad fa-user-astronaut source" aria-hidden="true"></i>',
        'm_name' => 'INDUSTRY EXPERT',
        'm_desc' => '',
        'm_parents' => array(4600,12864,4983,11035,1278,12523),
    ),
    12437 => array(
        'm_icon' => '<i class="fas fa-medal source" aria-hidden="true"></i>',
        'm_name' => 'LEADERBOARD',
        'm_desc' => '',
        'm_parents' => array(12897,12500,10876,12489,11035),
    ),
    7291 => array(
        'm_icon' => '<i class="fas fa-power-off" aria-hidden="true"></i>',
        'm_name' => 'LOGOUT',
        'm_desc' => '',
        'm_parents' => array(12500,10876,11035),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735,6205),
    ),
    4430 => array(
        'm_icon' => '<i class="far fa-alicorn source" aria-hidden="true"></i>',
        'm_name' => 'MENCH PLAYER',
        'm_desc' => '',
        'm_parents' => array(4983,1278,11035,10573),
    ),
    12749 => array(
        'm_icon' => '<i class="fas fa-plus-circle idea"></i>',
        'm_name' => 'MODIFY',
        'm_desc' => '',
        'm_parents' => array(10984,12893,11035),
    ),
    12205 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'MY ACCOUNT',
        'm_desc' => '',
        'm_parents' => array(12500,11035),
    ),
    12211 => array(
        'm_icon' => '<i class="fas fa-step-forward read" aria-hidden="true"></i>',
        'm_name' => 'NEXT',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => '',
        'm_parents' => array(6405,12744,6194,12699,12500,10876,11035,4527),
    ),
    12750 => array(
        'm_icon' => '<i class="fas fa-step-forward read"></i>',
        'm_name' => 'PREVIEW IDEA READ',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
    12898 => array(
        'm_icon' => '<i class="fas fa-pen idea"></i>',
        'm_name' => 'PUBLISH',
        'm_desc' => '',
        'm_parents' => array(11035,10939,4535,10876,12893),
    ),
    7256 => array(
        'm_icon' => '<i class="fas fa-search" aria-hidden="true"></i>',
        'm_name' => 'SEARCH MENCH',
        'm_desc' => '',
        'm_parents' => array(12701,12497,11035,3323),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in-alt" aria-hidden="true"></i>',
        'm_name' => 'SIGN IN/UP',
        'm_desc' => '',
        'm_parents' => array(4527,11035),
    ),
    4997 => array(
        'm_icon' => '<i class="fad fa-edit source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LIST EDITOR',
        'm_desc' => '',
        'm_parents' => array(11035,12703,12590,11029,4527),
    ),
    12275 => array(
        'm_icon' => '<i class="fas fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE MODIFY',
        'm_desc' => '',
        'm_parents' => array(12412,11035),
    ),
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(6225,11035,5007,4527),
    ),
    7540 => array(
        'm_icon' => '<i class="fad fa-university" aria-hidden="true"></i>',
        'm_name' => 'TERMS OF SERVICE',
        'm_desc' => '',
        'm_parents' => array(11035),
    ),
);

//IDEA LAYOUT:
$config['en_ids_11018'] = array(11020,4601,12419,10573,12589,7347,12896,6255,6146,4983,12682,7545,11047);
$config['en_all_11018'] = array(
    11020 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NEXT',
        'm_desc' => 'Define reading flow',
        'm_parents' => array(12675,12413,11018),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags idea" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => 'Improve idea search',
        'm_parents' => array(10984,11018,12322,10593,4593,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => 'Contributor-only chats',
        'm_parents' => array(10984,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => 'Active contributors',
        'm_parents' => array(12898,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    12589 => array(
        'm_icon' => '<i class="fad fa-edit idea" aria-hidden="true"></i>',
        'm_name' => 'NEXT EDITOR',
        'm_desc' => 'Mass modify next ideas',
        'm_parents' => array(12702,11018,4527,12590),
    ),
    7347 => array(
        'm_icon' => '<i class="fas fa-book read" aria-hidden="true"></i>',
        'm_name' => 'BOOKSHELF',
        'm_desc' => 'Players who started here',
        'm_parents' => array(12893,12701,12677,11018,11035,11089,6205,12228,4527),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'HIGHLIGHTS',
        'm_desc' => '',
        'm_parents' => array(12701,12321,4485,7551,11089,11018,11035,4755,4593,12893),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => 'Players who read idea',
        'm_parents' => array(12500,12677,10939,11018,12467,11089,12410,6771,12228,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'UNFINISHED',
        'm_desc' => 'Players who failed to complete read',
        'm_parents' => array(12701,12677,11018,12446,11089,12228,4527),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-comment source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => 'People & Content referencing this idea',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    12682 => array(
        'm_icon' => '<i class="fas fa-lightbulb-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'REQUIREMENTS',
        'm_desc' => 'Requirements to start reading this idea',
        'm_parents' => array(4593,12321,7551,11089,10986,11018,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-user-plus source" aria-hidden="true"></i>',
        'm_name' => 'ADD PROFILE',
        'm_desc' => 'Profile(s) enhancements once idea is read',
        'm_parents' => array(12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA ADMIN MENU',
        'm_desc' => '',
        'm_parents' => array(12700,11018,4527,11040),
    ),
);

//IDEA PREVIOUS SECTION:
$config['en_ids_10990'] = array(11019,4737,4736);
$config['en_all_10990'] = array(
    11019 => array(
        'm_icon' => '<i class="fas fa-step-backward idea" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS',
        'm_desc' => '',
        'm_parents' => array(12413,10990),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'TITLE',
        'm_desc' => '',
        'm_parents' => array(6404,10990,12112,10644,6232,6201),
    ),
);

//SOURCE SUPERPOWERS:
$config['en_ids_10957'] = array(12700,12702,10939,10986,12673,10984,12701,12705,12728,10967,12703,12699,12706);
$config['en_all_10957'] = array(
    12700 => array(
        'm_icon' => '<i class="fad fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA CHAIN',
        'm_desc' => 'Advance Idea Linking',
        'm_parents' => array(10957),
    ),
    12702 => array(
        'm_icon' => '<i class="fad fa-list idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LIST EDIT',
        'm_desc' => 'Mass Edit Ideas',
        'm_parents' => array(10957),
    ),
    10939 => array(
        'm_icon' => '<i class="fad fa-pen idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA PEN',
        'm_desc' => 'Basic Publishing Powers',
        'm_parents' => array(10876,10957),
    ),
    10986 => array(
        'm_icon' => '<i class="fad fa-scrubber idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCING',
        'm_desc' => 'Advance Source Tools',
        'm_parents' => array(10957),
    ),
    12673 => array(
        'm_icon' => '<i class="fad fa-rectangle-wide idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TOOLBAR',
        'm_desc' => 'Edit next & previous ideas on the fly',
        'm_parents' => array(10957),
    ),
    10984 => array(
        'm_icon' => '<i class="fas fa-walkie-talkie idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA WALKIE TALKIE',
        'm_desc' => 'Collaborative Publishing Powers',
        'm_parents' => array(10957),
    ),
    12701 => array(
        'm_icon' => '<i class="fad fa-glasses read" aria-hidden="true"></i>',
        'm_name' => 'READ GLASSES',
        'm_desc' => 'Read info from all players',
        'm_parents' => array(10957),
    ),
    12705 => array(
        'm_icon' => '<i class="fad fa-list read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST EDIT',
        'm_desc' => 'Mass Edit Ledger Transactions',
        'm_parents' => array(10957),
    ),
    12728 => array(
        'm_icon' => '<i class="fad fa-alarm-plus source"></i>',
        'm_name' => 'SOURCE CRON',
        'm_desc' => 'Manage Cron Jobs',
        'm_parents' => array(10957),
    ),
    10967 => array(
        'm_icon' => '<i class="fad fa-edit source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE EDITOR',
        'm_desc' => 'Organize Sources',
        'm_parents' => array(10957),
    ),
    12703 => array(
        'm_icon' => '<i class="fad fa-list source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LIST EDIT',
        'm_desc' => 'Mass Source Edit',
        'm_parents' => array(10957),
    ),
    12699 => array(
        'm_icon' => '<i class="fad fa-plug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PLUG',
        'm_desc' => 'Access Mench Plugins',
        'm_parents' => array(10957),
    ),
    12706 => array(
        'm_icon' => '<i class="fad fa-rectangle-wide source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TOOLBAR',
        'm_desc' => 'List Parent Sources',
        'm_parents' => array(10957),
    ),
);

//AVATARS BASIC:
$config['en_ids_10956'] = array(12286,12287,12288,12234,12233,10965,12236,12235,10979,12295,12294,12293,12300,12301,12299,12237,12238,10978,12314,12315,12316,12240,12239,10963,12241,12242,12207,12244,12243,10966,12245,12246,10976,12248,12247,10962,12249,12250,10975,12252,12251,10982,12253,12254,10970,12256,12255,10972,12257,12258,10969,12260,12259,10960,12439,12262,10981,12264,12263,10968,12265,12266,10974,12268,12267,12206,12269,12270,10958,12272,12271,12231);
$config['en_all_10956'] = array(
    12286 => array(
        'm_icon' => '<i class="fas fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12287 => array(
        'm_icon' => '<i class="far fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12288 => array(
        'm_icon' => '<i class="fad fa-bat source" aria-hidden="true"></i>',
        'm_name' => 'BAT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12234 => array(
        'm_icon' => '<i class="fas fa-dog source"></i>',
        'm_name' => 'DOGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12233 => array(
        'm_icon' => '<i class="far fa-dog source"></i>',
        'm_name' => 'DOGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10965 => array(
        'm_icon' => '<i class="fad fa-dog source"></i>',
        'm_name' => 'DOGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12236 => array(
        'm_icon' => '<i class="fas fa-duck source" aria-hidden="true"></i>',
        'm_name' => 'DONALD BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12235 => array(
        'm_icon' => '<i class="far fa-duck source" aria-hidden="true"></i>',
        'm_name' => 'DONALD LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10979 => array(
        'm_icon' => '<i class="fad fa-duck source"></i>',
        'm_name' => 'DONALD MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12295 => array(
        'm_icon' => '<i class="fas fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12294 => array(
        'm_icon' => '<i class="far fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12293 => array(
        'm_icon' => '<i class="fad fa-dove source" aria-hidden="true"></i>',
        'm_name' => 'DOVE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12300 => array(
        'm_icon' => '<i class="fas fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12301 => array(
        'm_icon' => '<i class="far fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12299 => array(
        'm_icon' => '<i class="fad fa-elephant source" aria-hidden="true"></i>',
        'm_name' => 'ELEPHANT MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12237 => array(
        'm_icon' => '<i class="fas fa-fish source" aria-hidden="true"></i>',
        'm_name' => 'FISHY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12238 => array(
        'm_icon' => '<i class="far fa-fish source" aria-hidden="true"></i>',
        'm_name' => 'FISHY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10978 => array(
        'm_icon' => '<i class="fad fa-fish source"></i>',
        'm_name' => 'FISHY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12314 => array(
        'm_icon' => '<i class="fas fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12315 => array(
        'm_icon' => '<i class="far fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12316 => array(
        'm_icon' => '<i class="fad fa-frog source" aria-hidden="true"></i>',
        'm_name' => 'FROG MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12240 => array(
        'm_icon' => '<i class="fas fa-hippo source" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12239 => array(
        'm_icon' => '<i class="far fa-hippo source" aria-hidden="true"></i>',
        'm_name' => 'HIPPOY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10963 => array(
        'm_icon' => '<i class="fad fa-hippo source"></i>',
        'm_name' => 'HIPPOY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12241 => array(
        'm_icon' => '<i class="fas fa-badger-honey source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12242 => array(
        'm_icon' => '<i class="far fa-badger-honey source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BADGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12207 => array(
        'm_icon' => '<i class="fad fa-badger-honey source"></i>',
        'm_name' => 'HONEY BADGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12244 => array(
        'm_icon' => '<i class="fas fa-deer source" aria-hidden="true"></i>',
        'm_name' => 'HONEY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12243 => array(
        'm_icon' => '<i class="far fa-deer source" aria-hidden="true"></i>',
        'm_name' => 'HONEY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10966 => array(
        'm_icon' => '<i class="fad fa-deer source"></i>',
        'm_name' => 'HONEY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12245 => array(
        'm_icon' => '<i class="fas fa-horse source" aria-hidden="true"></i>',
        'm_name' => 'HORSY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12246 => array(
        'm_icon' => '<i class="far fa-horse source" aria-hidden="true"></i>',
        'm_name' => 'HORSY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10976 => array(
        'm_icon' => '<i class="fad fa-horse source"></i>',
        'm_name' => 'HORSY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12248 => array(
        'm_icon' => '<i class="fas fa-monkey source" aria-hidden="true"></i>',
        'm_name' => 'HUMAN BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12247 => array(
        'm_icon' => '<i class="far fa-monkey source" aria-hidden="true"></i>',
        'm_name' => 'HUMAN LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10962 => array(
        'm_icon' => '<i class="fad fa-monkey source"></i>',
        'm_name' => 'HUMAN MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12249 => array(
        'm_icon' => '<i class="fas fa-kiwi-bird source" aria-hidden="true"></i>',
        'm_name' => 'KIWI BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12250 => array(
        'm_icon' => '<i class="far fa-kiwi-bird source" aria-hidden="true"></i>',
        'm_name' => 'KIWI LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10975 => array(
        'm_icon' => '<i class="fad fa-kiwi-bird source"></i>',
        'm_name' => 'KIWI MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12252 => array(
        'm_icon' => '<i class="fas fa-cat source" aria-hidden="true"></i>',
        'm_name' => 'MIMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12251 => array(
        'm_icon' => '<i class="far fa-cat source" aria-hidden="true"></i>',
        'm_name' => 'MIMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10982 => array(
        'm_icon' => '<i class="fad fa-cat source"></i>',
        'm_name' => 'MIMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12253 => array(
        'm_icon' => '<i class="fas fa-cow source" aria-hidden="true"></i>',
        'm_name' => 'MOMY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12254 => array(
        'm_icon' => '<i class="far fa-cow source" aria-hidden="true"></i>',
        'm_name' => 'MOMY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10970 => array(
        'm_icon' => '<i class="fad fa-cow source"></i>',
        'm_name' => 'MOMY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12256 => array(
        'm_icon' => '<i class="fas fa-turtle source" aria-hidden="true"></i>',
        'm_name' => 'NINJA BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12255 => array(
        'm_icon' => '<i class="far fa-turtle source" aria-hidden="true"></i>',
        'm_name' => 'NINJA LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10972 => array(
        'm_icon' => '<i class="fad fa-turtle source"></i>',
        'm_name' => 'NINJA MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12257 => array(
        'm_icon' => '<i class="fas fa-pig source" aria-hidden="true"></i>',
        'm_name' => 'PIGGY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12258 => array(
        'm_icon' => '<i class="far fa-pig source" aria-hidden="true"></i>',
        'm_name' => 'PIGGY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10969 => array(
        'm_icon' => '<i class="fad fa-pig source"></i>',
        'm_name' => 'PIGGY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12260 => array(
        'm_icon' => '<i class="fas fa-rabbit source" aria-hidden="true"></i>',
        'm_name' => 'ROGER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12259 => array(
        'm_icon' => '<i class="far fa-rabbit source" aria-hidden="true"></i>',
        'm_name' => 'ROGER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10960 => array(
        'm_icon' => '<i class="fad fa-rabbit source"></i>',
        'm_name' => 'ROGER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12439 => array(
        'm_icon' => '<i class="fad fa-crow source" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12262 => array(
        'm_icon' => '<i class="far fa-crow source" aria-hidden="true"></i>',
        'm_name' => 'RUSSEL LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10981 => array(
        'm_icon' => '<i class="fad fa-crow source"></i>',
        'm_name' => 'RUSSEL MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12264 => array(
        'm_icon' => '<i class="fas fa-sheep source" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12263 => array(
        'm_icon' => '<i class="far fa-sheep source" aria-hidden="true"></i>',
        'm_name' => 'SHEEPY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10968 => array(
        'm_icon' => '<i class="fad fa-sheep source"></i>',
        'm_name' => 'SHEEPY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12265 => array(
        'm_icon' => '<i class="fas fa-snake source" aria-hidden="true"></i>',
        'm_name' => 'SNAKY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12266 => array(
        'm_icon' => '<i class="far fa-snake source" aria-hidden="true"></i>',
        'm_name' => 'SNAKY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10974 => array(
        'm_icon' => '<i class="fad fa-snake source"></i>',
        'm_name' => 'SNAKY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12268 => array(
        'm_icon' => '<i class="fas fa-spider source" aria-hidden="true"></i>',
        'm_name' => 'SPIDER BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12267 => array(
        'm_icon' => '<i class="far fa-spider source" aria-hidden="true"></i>',
        'm_name' => 'SPIDER LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12206 => array(
        'm_icon' => '<i class="fad fa-spider source"></i>',
        'm_name' => 'SPIDER MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12269 => array(
        'm_icon' => '<i class="fas fa-squirrel source" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12270 => array(
        'm_icon' => '<i class="far fa-squirrel source" aria-hidden="true"></i>',
        'm_name' => 'SQUIRRELY LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    10958 => array(
        'm_icon' => '<i class="fad fa-squirrel source"></i>',
        'm_name' => 'SQUIRRELY MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12272 => array(
        'm_icon' => '<i class="fas fa-whale source" aria-hidden="true"></i>',
        'm_name' => 'WHALE BOLD',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12271 => array(
        'm_icon' => '<i class="far fa-whale source" aria-hidden="true"></i>',
        'm_name' => 'WHALE LIGHT',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
    12231 => array(
        'm_icon' => '<i class="fad fa-whale source"></i>',
        'm_name' => 'WHALE MIX',
        'm_desc' => '',
        'm_parents' => array(12279,10956),
    ),
);

//MENCH:
$config['en_ids_2738'] = array(4536,4535,6205);
$config['en_all_2738'] = array(
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(12761,4527,12155,2738),
    ),
    4535 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA',
        'm_desc' => '',
        'm_parents' => array(12761,12112,12155,2738),
    ),
    6205 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ',
        'm_desc' => '',
        'm_parents' => array(12155,2738),
    ),
);

//LEDGER FIVE LINKS:
$config['en_ids_10692'] = array(4366,4429,4368,4369,4371);
$config['en_all_10692'] = array(
    4366 => array(
        'm_icon' => '<i class="fas fa-id-badge" aria-hidden="true"></i>',
        'm_name' => 'PROFILE SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-step-backward" aria-hidden="true"></i>',
        'm_name' => 'PREVIOUS IDEA',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-step-forward" aria-hidden="true"></i>',
        'm_name' => 'NEXT IDEA',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fad fa-link" aria-hidden="true"></i>',
        'm_name' => 'REFERENCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
);

//MENCH MEMORY:
$config['en_ids_4527'] = array(6225,10956,12279,12588,6150,10573,7347,7274,3000,12864,12359,11047,6192,11018,12675,12677,4229,12842,4486,12840,4485,12012,6193,10990,12273,4983,4737,7356,12138,7355,12400,6201,12420,12413,7585,12330,12324,7309,7712,12883,12884,7751,10717,12571,12574,10692,12893,2738,12744,12467,6404,12079,4341,4527,11054,12687,11035,12112,10876,6232,12589,12410,12500,12741,6287,12577,4755,11081,7704,5967,12229,12326,12446,12227,6255,7304,7360,7364,7359,12327,4269,6204,4536,12887,6194,11089,11080,12822,4592,12403,11059,4537,12524,4997,4986,7551,12274,6177,7358,12575,7357,12401,10957,6206,12523,12322,12321,6103,6186,4593,10593,12141,6146);
$config['en_all_4527'] = array(
    6225 => array(
        'm_icon' => '<i class="fad fa-cog source" aria-hidden="true"></i>',
        'm_name' => 'ACCOUNT SETTINGS',
        'm_desc' => '',
        'm_parents' => array(12574,11089,12205,11035,4527),
    ),
    10956 => array(
        'm_icon' => '<i class="fad fa-paw-alt source" aria-hidden="true"></i>',
        'm_name' => 'AVATARS BASIC',
        'm_desc' => '',
        'm_parents' => array(12289,4527),
    ),
    12279 => array(
        'm_icon' => '<i class="fad fa-paw-claws source" aria-hidden="true"></i>',
        'm_name' => 'AVATARS SUPER',
        'm_desc' => '',
        'm_parents' => array(12289,4527),
    ),
    12588 => array(
        'm_icon' => '<i class="fad fa-text"></i>',
        'm_name' => 'AVOID PREFIX REMOVAL',
        'm_desc' => '',
        'm_parents' => array(7254,4527),
    ),
    6150 => array(
        'm_icon' => '<i class="far fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARK REMOVED',
        'm_desc' => '',
        'm_parents' => array(6771,4527),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(12898,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    7347 => array(
        'm_icon' => '<i class="fas fa-book read" aria-hidden="true"></i>',
        'm_name' => 'BOOKSHELF',
        'm_desc' => '',
        'm_parents' => array(12893,12701,12677,11018,11035,11089,6205,12228,4527),
    ),
    7274 => array(
        'm_icon' => '<i class="far fa-magic" aria-hidden="true"></i>',
        'm_name' => 'CRON JOBS',
        'm_desc' => '',
        'm_parents' => array(12744,6194,11035,12728,12500,10876,4527,6405),
    ),
    3000 => array(
        'm_icon' => '<i class="fad fa-file-certificate source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT CHANNEL',
        'm_desc' => '',
        'm_parents' => array(11054,11035,4600,4527),
    ),
    12864 => array(
        'm_icon' => '<i class="fas fa-shield-check source"></i>',
        'm_name' => 'EXPERT SOURCES',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    12359 => array(
        'm_icon' => '<i class="fad fa-file-check idea"></i>',
        'm_name' => 'FILE UPLOADING ALLOWED',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    11047 => array(
        'm_icon' => '<i class="fas fa-caret-down idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA ADMIN MENU',
        'm_desc' => '',
        'm_parents' => array(12700,11018,4527,11040),
    ),
    6192 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'IDEA AND',
        'm_desc' => '',
        'm_parents' => array(4527,10602),
    ),
    11018 => array(
        'm_icon' => '<i class="fad fa-crop-alt idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LAYOUT',
        'm_desc' => '',
        'm_parents' => array(12676,4535,4527),
    ),
    12675 => array(
        'm_icon' => '<i class="fad fa-badge-check idea"></i>',
        'm_name' => 'IDEA LAYOUT DEFAULT SELECTED',
        'm_desc' => '',
        'm_parents' => array(4527,12676),
    ),
    12677 => array(
        'm_icon' => '<i class="fad fa-eye-slash idea"></i>',
        'm_name' => 'IDEA LAYOUT HIDE IF ZERO',
        'm_desc' => '',
        'm_parents' => array(4527,12676),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(12842,4527,6410,6283,4593,4486),
    ),
    12842 => array(
        'm_icon' => '<i class="fas fa-long-arrow-down idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK ONE-WAY',
        'm_desc' => '',
        'm_parents' => array(4527,12841),
    ),
    4486 => array(
        'm_icon' => '<i class="fas fa-link idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINKS',
        'm_desc' => '',
        'm_parents' => array(12700,11054,6232,12079,10662,4527),
    ),
    12840 => array(
        'm_icon' => '<i class="fas fa-exchange rotate90 idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK TWO-WAYS',
        'm_desc' => '',
        'm_parents' => array(4527,12841),
    ),
    4485 => array(
        'm_icon' => '<i class="fas fa-comment-plus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES',
        'm_desc' => '',
        'm_parents' => array(6404,4535,4527),
    ),
    12012 => array(
        'm_icon' => '<i class="far fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES STATUS',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    6193 => array(
        'm_icon' => '<i class="fad fa-code-branch rotate180 " aria-hidden="true"></i>',
        'm_name' => 'IDEA OR',
        'm_desc' => '',
        'm_parents' => array(10602,4527),
    ),
    10990 => array(
        'm_icon' => '<i class="fad fa-browser idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA PREVIOUS SECTION',
        'm_desc' => '',
        'm_parents' => array(4527),
    ),
    12273 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEAS',
        'm_desc' => '',
        'm_parents' => array(4535,12500,12571,12467,12321,12410,11089,12228,4527),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-comment source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    7356 => array(
        'm_icon' => '<i class="far fa-check-circle"></i>',
        'm_name' => 'IDEA STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    12138 => array(
        'm_icon' => '<i class="fad fa-search idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS FEATURED',
        'm_desc' => '',
        'm_parents' => array(4527,10891),
    ),
    7355 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(11054,10891,4527),
    ),
    12400 => array(
        'm_icon' => '<i class="fad fa-sync idea"></i>',
        'm_name' => 'IDEA SYNC STATUS',
        'm_desc' => '',
        'm_parents' => array(10891,12732,4527),
    ),
    6201 => array(
        'm_icon' => '<i class="fas fa-table idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TABLE',
        'm_desc' => '',
        'm_parents' => array(11054,4527,7735,4535),
    ),
    12420 => array(
        'm_icon' => '<i class="far fa-user-circle" aria-hidden="true"></i>',
        'm_name' => 'IDEA TEXT INPUT SHOW ICON',
        'm_desc' => '',
        'm_parents' => array(7254,4527),
    ),
    12413 => array(
        'm_icon' => '<i class="fad fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TREE',
        'm_desc' => '',
        'm_parents' => array(4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    12330 => array(
        'm_icon' => '<i class="fas fa-bolt"></i>',
        'm_name' => 'IDEA TYPE INSTANTLY DONE',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    12324 => array(
        'm_icon' => '<i class="fad fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE MANUAL INPUT',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    7309 => array(
        'm_icon' => '<i class="far fa-cubes"></i>',
        'm_name' => 'IDEA TYPE REQUIREMENT',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    7712 => array(
        'm_icon' => '<i class="far fa-question-circle" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE SELECT',
        'm_desc' => '',
        'm_parents' => array(6287,10893,4527),
    ),
    12883 => array(
        'm_icon' => '<i class="fas fa-check"></i>',
        'm_name' => 'IDEA TYPE SELECT ONE',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    12884 => array(
        'm_icon' => '<i class="fas fa-check-double"></i>',
        'm_name' => 'IDEA TYPE SELECT SOME',
        'm_desc' => '',
        'm_parents' => array(4527,10893),
    ),
    7751 => array(
        'm_icon' => '<i class="far fa-upload" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(10893,4527),
    ),
    10717 => array(
        'm_icon' => '<i class="fas fa-desktop" aria-hidden="true"></i>',
        'm_name' => 'IT',
        'm_desc' => '',
        'm_parents' => array(10710,4527),
    ),
    12571 => array(
        'm_icon' => '<i class="fas fa-expand" aria-hidden="true"></i>',
        'm_name' => 'LAYOUT OPEN BY DEFAULT',
        'm_desc' => '',
        'm_parents' => array(12573,4527),
    ),
    12574 => array(
        'm_icon' => '<i class="fad fa-check-double"></i>',
        'm_name' => 'LAYOUT SHOW EVEN IF ZERO',
        'm_desc' => '',
        'm_parents' => array(4527,12573),
    ),
    10692 => array(
        'm_icon' => '<i class="fas fa-bezier-curve"></i>',
        'm_name' => 'LEDGER FIVE LINKS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    12893 => array(
        'm_icon' => '<i class="fas fa-ellipsis-h"></i>',
        'm_name' => 'MAIN MENU',
        'm_desc' => '',
        'm_parents' => array(6403,4527),
    ),
    2738 => array(
        'm_icon' => '<img src="https://mench.com/img/mench.png" class="mench-spin no-radius">',
        'm_name' => 'MENCH',
        'm_desc' => '',
        'm_parents' => array(12497,2,11054,12041,4527,1,7312),
    ),
    12744 => array(
        'm_icon' => '<i class="fad fa-mobile"></i>',
        'm_name' => 'MENCH APPLICATIONS',
        'm_desc' => '',
        'm_parents' => array(6403,4527),
    ),
    12467 => array(
        'm_icon' => '<i class="fas fa-circle" aria-hidden="true"></i>',
        'm_name' => 'MENCH COINS',
        'm_desc' => '',
        'm_parents' => array(7305,4527),
    ),
    6404 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'MENCH CONFIG VARIABLES',
        'm_desc' => '',
        'm_parents' => array(11054,4527,6403),
    ),
    12079 => array(
        'm_icon' => '<i class="fas fa-caret-down" aria-hidden="true"></i>',
        'm_name' => 'MENCH DROPDOWN MENUS',
        'm_desc' => '',
        'm_parents' => array(12829,6403,4527),
    ),
    4341 => array(
        'm_icon' => '<i class="fas fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'MENCH LEDGER',
        'm_desc' => '',
        'm_parents' => array(12887,11047,12707,10876,12588,11035,4527,7735,6205),
    ),
    4527 => array(
        'm_icon' => '<i class="fas fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY',
        'm_desc' => '',
        'm_parents' => array(4755,6403,12741,6287,4527),
    ),
    11054 => array(
        'm_icon' => '<i class="fal fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY JAVASCRIPT',
        'm_desc' => '',
        'm_parents' => array(4755,6403,4527),
    ),
    12687 => array(
        'm_icon' => '<i class="fad fa-comments-alt" aria-hidden="true"></i>',
        'm_name' => 'MENCH MESSAGES',
        'm_desc' => '',
        'm_parents' => array(6403,11054,4527),
    ),
    11035 => array(
        'm_icon' => '<i class="fad fa-compass" aria-hidden="true"></i>',
        'm_name' => 'MENCH NAVIGATION',
        'm_desc' => '',
        'm_parents' => array(4527,7305),
    ),
    12112 => array(
        'm_icon' => '<i class="fas fa-text" aria-hidden="true"></i>',
        'm_name' => 'MENCH TEXT INPUTS',
        'm_desc' => '',
        'm_parents' => array(12829,6403,4527),
    ),
    10876 => array(
        'm_icon' => '<i class="fas fa-browser" aria-hidden="true"></i>',
        'm_name' => 'MENCH URL',
        'm_desc' => '',
        'm_parents' => array(4527,1326,7305),
    ),
    6232 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'MENCH VARIABLE',
        'm_desc' => '',
        'm_parents' => array(6403,4755,4527,6212),
    ),
    12589 => array(
        'm_icon' => '<i class="fad fa-edit idea" aria-hidden="true"></i>',
        'm_name' => 'NEXT EDITOR',
        'm_desc' => '',
        'm_parents' => array(12702,11018,4527,12590),
    ),
    12410 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'PLAYER EARNED COINS',
        'm_desc' => '',
        'm_parents' => array(12823,4527),
    ),
    12500 => array(
        'm_icon' => '<i class="fas fa-user" aria-hidden="true"></i>',
        'm_name' => 'PLAYER MENU',
        'm_desc' => '',
        'm_parents' => array(12079,12497,12823,4527),
    ),
    12741 => array(
        'm_icon' => '',
        'm_name' => 'PLUGIN EXCLUDE MENCH UI',
        'm_desc' => '',
        'm_parents' => array(7254,4527),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => '',
        'm_parents' => array(6405,12744,6194,12699,12500,10876,11035,4527),
    ),
    12577 => array(
        'm_icon' => '<i class="fad fa-text" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR UPPERCASE',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash" aria-hidden="true"></i>',
        'm_name' => 'PRIVATE TRANSACTION',
        'm_desc' => '',
        'm_parents' => array(12701,4755,6771,4527),
    ),
    11081 => array(
        'm_icon' => '<i class="far fa-bezier-curve read"></i>',
        'm_name' => 'READ ALL CONNECTIONS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    7704 => array(
        'm_icon' => '<i class="far fa-hand-pointer read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWERED',
        'm_desc' => '',
        'm_parents' => array(12228,4527),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ CARBON COPY',
        'm_desc' => '',
        'm_parents' => array(4527,7569,4755,4593),
    ),
    12229 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ COMPLETION',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    12326 => array(
        'm_icon' => '<i class="fad fa-expand read" aria-hidden="true"></i>',
        'm_name' => 'READ EXPANSIONS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    12446 => array(
        'm_icon' => '<i class="fad fa-question-circle read"></i>',
        'm_name' => 'READ ICONS',
        'm_desc' => '',
        'm_parents' => array(4527,6771),
    ),
    12227 => array(
        'm_icon' => '<i class="fas fa-walking read" aria-hidden="true"></i>',
        'm_name' => 'READ PROGRESS',
        'm_desc' => '',
        'm_parents' => array(12228,4527),
    ),
    6255 => array(
        'm_icon' => '<i class="fas fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READS',
        'm_desc' => '',
        'm_parents' => array(12500,12677,10939,11018,12467,11089,12410,6771,12228,4527),
    ),
    7304 => array(
        'm_icon' => '<i class="far fa-chart-bar read"></i>',
        'm_name' => 'READ STATS',
        'm_desc' => '',
        'm_parents' => array(10888,4527,6205),
    ),
    7360 => array(
        'm_icon' => '<i class="far fa-check-circle" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    7364 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS INCOMPLETE',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    7359 => array(
        'm_icon' => '<i class="far fa-eye" aria-hidden="true"></i>',
        'm_name' => 'READ STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(10624,4527),
    ),
    12327 => array(
        'm_icon' => '<i class="fas fa-lock-open read"></i>',
        'm_name' => 'READ UNLOCKS',
        'm_desc' => '',
        'm_parents' => array(4527,12228),
    ),
    4269 => array(
        'm_icon' => '<i class="fas fa-sign-in-alt" aria-hidden="true"></i>',
        'm_name' => 'SIGN IN/UP',
        'm_desc' => '',
        'm_parents' => array(4527,11035),
    ),
    6204 => array(
        'm_icon' => '<i class="fas fa-check" aria-hidden="true"></i>',
        'm_name' => 'SINGLE SELECTABLE',
        'm_desc' => '',
        'm_parents' => array(4527,4758),
    ),
    4536 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE',
        'm_desc' => '',
        'm_parents' => array(12761,4527,12155,2738),
    ),
    12887 => array(
        'm_icon' => '<i class="fas fa-caret-down source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ADMIN MENU',
        'm_desc' => '',
        'm_parents' => array(12703,4527,11040),
    ),
    6194 => array(
        'm_icon' => '<i class="fad fa-database source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE DATABASE REFERENCES',
        'm_desc' => '',
        'm_parents' => array(12412,12701,4758,4527),
    ),
    11089 => array(
        'm_icon' => '<i class="fad fa-crop-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LAYOUT',
        'm_desc' => '',
        'm_parents' => array(4536,4527),
    ),
    11080 => array(
        'm_icon' => '<i class="far fa-file source"></i>',
        'm_name' => 'SOURCE LINK FILE EXTENSIONS',
        'm_desc' => '',
        'm_parents' => array(12821,4527),
    ),
    12822 => array(
        'm_icon' => '<i class="fad fa-eye source"></i>',
        'm_name' => 'SOURCE LINK MESSAGE DISPLAY',
        'm_desc' => '',
        'm_parents' => array(4527,12821),
    ),
    4592 => array(
        'm_icon' => '<i class="fas fa-link source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINKS',
        'm_desc' => '',
        'm_parents' => array(11054,5982,4527),
    ),
    12403 => array(
        'm_icon' => '<i class="far fa-object-ungroup source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK TYPE CUSTOM UI',
        'm_desc' => '',
        'm_parents' => array(12821,4527),
    ),
    11059 => array(
        'm_icon' => '<i class="fas fa-file-plus source"></i>',
        'm_name' => 'SOURCE LINK UPLOAD FILE',
        'm_desc' => '',
        'm_parents' => array(12821,6196,4527),
    ),
    4537 => array(
        'm_icon' => '<i class="fad fa-spider-web source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK URLS',
        'm_desc' => '',
        'm_parents' => array(12821,4527),
    ),
    12524 => array(
        'm_icon' => '<i class="fad fa-film-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK VISUAL',
        'm_desc' => '',
        'm_parents' => array(12821,4527),
    ),
    4997 => array(
        'm_icon' => '<i class="fad fa-edit source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LIST EDITOR',
        'm_desc' => '',
        'm_parents' => array(11035,12703,12590,11029,4527),
    ),
    4986 => array(
        'm_icon' => '<i class="fal fa-at" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REFERENCE ALLOWED',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    7551 => array(
        'm_icon' => '<i class="fas fa-at source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REFERENCE ONLY',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    12274 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCES',
        'm_desc' => '',
        'm_parents' => array(12500,12467,12228,4527,4758),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    7358 => array(
        'm_icon' => '<i class="far fa-check-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS ACTIVE',
        'm_desc' => '',
        'm_parents' => array(12572,4527),
    ),
    12575 => array(
        'm_icon' => '<i class="fas fa-star source"></i>',
        'm_name' => 'SOURCE STATUS FEATURED',
        'm_desc' => '',
        'm_parents' => array(4527,12572),
    ),
    7357 => array(
        'm_icon' => '<i class="far fa-globe source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS PUBLIC',
        'm_desc' => '',
        'm_parents' => array(12572,11054,4527),
    ),
    12401 => array(
        'm_icon' => '<i class="fad fa-sync source"></i>',
        'm_name' => 'SOURCE STATUS SYNC',
        'm_desc' => '',
        'm_parents' => array(12572,12732,4527),
    ),
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(6225,11035,5007,4527),
    ),
    6206 => array(
        'm_icon' => '<i class="fas fa-table source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TABLE',
        'm_desc' => '',
        'm_parents' => array(4527,7735,4536),
    ),
    12523 => array(
        'm_icon' => '<i class="fad fa-sync source" aria-hidden="true"></i>',
        'm_name' => 'SYNC ICONS IF MISSING',
        'm_desc' => '',
        'm_parents' => array(7274,4527,4758),
    ),
    12322 => array(
        'm_icon' => '<i class="fas fa-comment read" aria-hidden="true"></i>',
        'm_name' => 'TEMPLATE IDEA MESSAGES',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    12321 => array(
        'm_icon' => '<i class="fad fa-object-group read" aria-hidden="true"></i>',
        'm_name' => 'TEMPLATE IDEA READ',
        'm_desc' => '',
        'm_parents' => array(10889,4527),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'TRANSACTION METADATA',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
    10593 => array(
        'm_icon' => '<i class="fas fa-file-alt" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE ADD CONTENT',
        'm_desc' => '',
        'm_parents' => array(12144,4527),
    ),
    12141 => array(
        'm_icon' => '<i class="fad fa-coin" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE COIN AWARD',
        'm_desc' => '',
        'm_parents' => array(12144,4527),
    ),
    6146 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'UNFINISHED',
        'm_desc' => '',
        'm_parents' => array(12701,12677,11018,12446,11089,12228,4527),
    ),
);

//TRANSACTION TYPE ADD CONTENT:
$config['en_ids_10593'] = array(12419,4250,10679,4983,10644,4601,4231,4554,4556,4555,6563,4570,7702,4549,4551,4550,4548,4552,4553,4251,4259,10657,4261,4260,4255,4258,10646);
$config['en_all_10593'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,12149,10593,4593),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4593,10593),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-comment source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    10644 => array(
        'm_icon' => '<i class="fad fa-bullseye-arrow idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE TITLE',
        'm_desc' => '',
        'm_parents' => array(10593,4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags idea" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12322,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12273,10939,12359,12322,10593,4986,4603,4593,4485),
    ),
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6563 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="read fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED IDEA',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,7569),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4552 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE CREATED',
        'm_desc' => '',
        'm_parents' => array(12274,12401,12149,12141,10593,4593),
    ),
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'SOURCE LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,6203,4593,4592,4537),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'SOURCE LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(12822,10593,4593,4592),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'SOURCE LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    10646 => array(
        'm_icon' => '<i class="fad fa-fingerprint source"></i>',
        'm_name' => 'SOURCE NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593),
    ),
);

//BOOKSHELF:
$config['en_ids_7347'] = array(4235,7495);
$config['en_all_7347'] = array(
    4235 => array(
        'm_icon' => '<i class="fad fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'INITIATED',
        'm_desc' => 'Bookmarked to begin with',
        'm_parents' => array(12227,7347,5967,4755,4593),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone read" aria-hidden="true"></i>',
        'm_name' => 'RECOMMEND',
        'm_desc' => 'Bookmarked as a requirement',
        'm_parents' => array(12227,7347,4755,4593),
    ),
);

//IDEA AND:
$config['en_ids_6192'] = array(6914,7637,6677,6683);
$config['en_all_6192'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ALL',
        'm_desc' => '',
        'm_parents' => array(12700,12330,7486,7485,6140,6192,7585,7309,6997),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip idea" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD & NEXT',
        'm_desc' => '',
        'm_parents' => array(12324,12117,7751,7585,6192),
    ),
    6677 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'READ & NEXT',
        'm_desc' => '',
        'm_parents' => array(12330,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard idea" aria-hidden="true"></i>',
        'm_name' => 'REPLY & NEXT',
        'm_desc' => '',
        'm_parents' => array(12324,6144,7585,6192),
    ),
);

//IDEA SOURCES:
$config['en_ids_4983'] = array(2997,4446,3005,3147,4763,2999,3192,2998,3084,4430);
$config['en_all_4983'] = array(
    2997 => array(
        'm_icon' => '<i class="fad fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT ARTICLE',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fad fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT ASSESSMENT',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    3005 => array(
        'm_icon' => '<i class="fad fa-book source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT BOOK',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT COURSE',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT MARKETING',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT PODCAST',
        'm_desc' => '',
        'm_parents' => array(4983,12523,10809,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT TOOL',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'EXPERT VIDEO',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    3084 => array(
        'm_icon' => '<i class="fad fa-user-astronaut source" aria-hidden="true"></i>',
        'm_name' => 'INDUSTRY EXPERT',
        'm_desc' => '',
        'm_parents' => array(4600,12864,4983,11035,1278,12523),
    ),
    4430 => array(
        'm_icon' => '<i class="far fa-alicorn source" aria-hidden="true"></i>',
        'm_name' => 'MENCH PLAYER',
        'm_desc' => '',
        'm_parents' => array(4983,1278,11035,10573),
    ),
);

//IDEA TYPE UPLOAD:
$config['en_ids_7751'] = array(7637);
$config['en_all_7751'] = array(
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPLOAD & NEXT',
        'm_desc' => '',
        'm_parents' => array(12324,12117,7751,7585,6192),
    ),
);

//TRANSACTION METADATA:
$config['en_ids_6103'] = array(6402,6203,4358);
$config['en_all_6103'] = array(
    6402 => array(
        'm_icon' => '<i class="fas fa-temperature-high idea" aria-hidden="true"></i>',
        'm_name' => 'CONDITION SCORE RANGE',
        'm_desc' => '',
        'm_parents' => array(12700,10664,6103,6410),
    ),
    6203 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'FACEBOOK ATTACHMENT ID',
        'm_desc' => 'For media files such as videos, audios, images and other files, we cache them with the Facebook Server so we can instantly deliver them to students. This variables in the link metadata is where we store the attachment ID. See the children to better understand which links types support this caching feature.',
        'm_parents' => array(6232,6215,2793,6103),
    ),
    4358 => array(
        'm_icon' => '<i class="fas fa-comment-alt-check idea" aria-hidden="true"></i>',
        'm_name' => 'READ MARKS',
        'm_desc' => '',
        'm_parents' => array(12700,12420,12112,10663,6103,6410,6232),
    ),
);

//MENCH LEDGER:
$config['en_ids_4341'] = array(4364,7694,4367,4372,6103,4369,4429,4368,4366,4370,4371,6186,4362,4593);
$config['en_all_4341'] = array(
    4364 => array(
        'm_icon' => '<i class="fad fa-user-edit" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION CREATOR',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    7694 => array(
        'm_icon' => '<i class="fad fa-project-diagram"></i>',
        'm_name' => 'TRANSACTION EXTERNAL ID',
        'm_desc' => '',
        'm_parents' => array(6215,6232,4341),
    ),
    4367 => array(
        'm_icon' => '<i class="fad fa-atlas" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,4341),
    ),
    4372 => array(
        'm_icon' => '<i class="fad fa-comment-lines" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION MESSAGE',
        'm_desc' => '',
        'm_parents' => array(7578,10679,10657,5001,6232,4341),
    ),
    6103 => array(
        'm_icon' => '<i class="far fa-lambda"></i>',
        'm_name' => 'TRANSACTION METADATA',
        'm_desc' => '',
        'm_parents' => array(4527,6232,6195,4341),
    ),
    4369 => array(
        'm_icon' => '<i class="fas fa-step-forward" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION NEXT IDEA',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4429 => array(
        'm_icon' => '<i class="fad fa-sitemap" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PORTFOLIO SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4368 => array(
        'm_icon' => '<i class="fas fa-step-backward" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PREVIOUS IDEA',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6202,6232,4341),
    ),
    4366 => array(
        'm_icon' => '<i class="fas fa-id-badge" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION PROFILE SOURCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,6160,6232,4341),
    ),
    4370 => array(
        'm_icon' => '<i class="fad fa-bars" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION RANK',
        'm_desc' => '',
        'm_parents' => array(10676,10675,6232,4341),
    ),
    4371 => array(
        'm_icon' => '<i class="fad fa-link" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION REFERENCE',
        'm_desc' => '',
        'm_parents' => array(11081,10692,4367,6232,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4362 => array(
        'm_icon' => '<i class="fad fa-clock" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TIME',
        'm_desc' => '',
        'm_parents' => array(6232,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//SOURCE TABLE:
$config['en_ids_6206'] = array(6197,6198,6160,6172,6177);
$config['en_all_6206'] = array(
    6197 => array(
        'm_icon' => '<i class="fad fa-fingerprint source" aria-hidden="true"></i>',
        'm_name' => 'FULL NAME',
        'm_desc' => '',
        'm_parents' => array(6404,12112,4269,12412,12232,10646,5000,4998,4999,6232,6206),
    ),
    6198 => array(
        'm_icon' => '<i class="fas fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'ICON',
        'm_desc' => '',
        'm_parents' => array(12605,10653,5943,10625,6232,6206),
    ),
    6160 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6206),
    ),
    6172 => array(
        'm_icon' => '<i class="fas fa-lambda source"></i>',
        'm_name' => 'METADATA',
        'm_desc' => '',
        'm_parents' => array(6232,6206,6195),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
);

//IDEA TABLE:
$config['en_ids_6201'] = array(6202,6159,4356,4737,4736,7585);
$config['en_all_6201'] = array(
    6202 => array(
        'm_icon' => '<i class="fas fa-hashtag idea" aria-hidden="true"></i>',
        'm_name' => 'ID',
        'm_desc' => '',
        'm_parents' => array(6232,6215,6201),
    ),
    6159 => array(
        'm_icon' => '<i class="fas fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'METADATA',
        'm_desc' => '',
        'm_parents' => array(11049,6232,6201,6195),
    ),
    4356 => array(
        'm_icon' => '<i class="fas fa-stopwatch idea" aria-hidden="true"></i>',
        'm_name' => 'READ TIME (SECONDS)',
        'm_desc' => '',
        'm_parents' => array(11047,7274,6404,12112,12420,10888,10650,6232,6201),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    4736 => array(
        'm_icon' => '<i class="fas fa-h1 idea" aria-hidden="true"></i>',
        'm_name' => 'TITLE',
        'm_desc' => '',
        'm_parents' => array(6404,10990,12112,10644,6232,6201),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random idea" aria-hidden="true"></i>',
        'm_name' => 'TYPE',
        'm_desc' => '',
        'm_parents' => array(11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
);

//SINGLE SELECTABLE:
$config['en_ids_6204'] = array(4737,7585,10602,3290,6177,6186,4593);
$config['en_all_6204'] = array(
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    10602 => array(
        'm_icon' => '<i class="far fa-puzzle-piece idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE GROUPS',
        'm_desc' => '',
        'm_parents' => array(10893,6204),
    ),
    3290 => array(
        'm_icon' => '<i class="far fa-transgender source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE GENDER',
        'm_desc' => '',
        'm_parents' => array(6204),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//IDEA TYPE SELECT:
$config['en_ids_7712'] = array(6684,7231);
$config['en_all_7712'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle idea" aria-hidden="true"></i>',
        'm_name' => 'ONE',
        'm_desc' => '',
        'm_parents' => array(12883,12336,12129,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square idea" aria-hidden="true"></i>',
        'm_name' => 'SOME',
        'm_desc' => '',
        'm_parents' => array(12884,12334,12129,7712,7489,7585,6193),
    ),
);

//READ ANSWERED:
$config['en_ids_7704'] = array(12336,12334,6157,7489);
$config['en_all_7704'] = array(
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
);

//IDEA LINK CONDITIONAL:
$config['en_ids_4229'] = array(10664,6140,6997);
$config['en_all_4229'] = array(
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4593,4229),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => 'A step that has become available because of the score generated from student answers',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//IDEA OR:
$config['en_ids_6193'] = array(6684,7231,6907);
$config['en_all_6193'] = array(
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT ONE',
        'm_desc' => '',
        'm_parents' => array(12883,12336,12129,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT SOME',
        'm_desc' => '',
        'm_parents' => array(12884,12334,12129,7712,7489,7585,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ANY',
        'm_desc' => '',
        'm_parents' => array(12883,12700,12330,7486,7485,6140,7585,7309,6997,6193),
    ),
);

//IDEA TYPE:
$config['en_ids_7585'] = array(6677,6683,7637,6914,6684,7231,6907);
$config['en_all_7585'] = array(
    6677 => array(
        'm_icon' => '<i class="fas fa-step-forward idea" aria-hidden="true"></i>',
        'm_name' => 'READ & NEXT',
        'm_desc' => 'Read messages & go next',
        'm_parents' => array(12330,7585,4559,6192),
    ),
    6683 => array(
        'm_icon' => '<i class="far fa-keyboard idea" aria-hidden="true"></i>',
        'm_name' => 'REPLY & NEXT',
        'm_desc' => 'Reply with text & go next',
        'm_parents' => array(12324,6144,7585,6192),
    ),
    7637 => array(
        'm_icon' => '<i class="far fa-paperclip idea" aria-hidden="true"></i>',
        'm_name' => 'UPLOAD & NEXT',
        'm_desc' => 'Upload a file & go next',
        'm_parents' => array(12324,12117,7751,7585,6192),
    ),
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ALL',
        'm_desc' => 'Complete by reading all next ideas',
        'm_parents' => array(12700,12330,7486,7485,6140,6192,7585,7309,6997),
    ),
    6684 => array(
        'm_icon' => '<i class="fas fa-check-circle idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT ONE',
        'm_desc' => 'Select a single next idea',
        'm_parents' => array(12883,12336,12129,7712,7585,6157,6193),
    ),
    7231 => array(
        'm_icon' => '<i class="fas fa-check-square idea" aria-hidden="true"></i>',
        'm_name' => 'SELECT SOME',
        'm_desc' => 'Select 1 or more next idea(s)',
        'm_parents' => array(12884,12334,12129,7712,7489,7585,6193),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube idea" aria-hidden="true"></i>',
        'm_name' => 'REQUIRE ANY',
        'm_desc' => 'Complete by reading one of the next ideas',
        'm_parents' => array(12883,12700,12330,7486,7485,6140,7585,7309,6997,6193),
    ),
);

//READ CARBON COPY:
$config['en_ids_5967'] = array(12419,12773,4250,12453,12450,4235,4246,7504);
$config['en_all_5967'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(10984,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    12773 => array(
        'm_icon' => '<i class="far fa-plus-circle idea"></i>',
        'm_name' => 'IDEA APPEND CONTENT',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(5967,4755,4593),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA CREATED',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(5967,12400,12149,10593,4593),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone idea"></i>',
        'm_name' => 'IDEA FEATURE REQUEST',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(12137,4755,4593,5967),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCE REQUEST',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(4593,4755,5967),
    ),
    4235 => array(
        'm_icon' => '<i class="fad fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ INITIATED',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(12227,7347,5967,4755,4593),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE BUG REPORTS',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(5967,4755,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PENDING MODERATION',
        'm_desc' => '&var_en_subscriber_ids=1',
        'm_parents' => array(5967,4755,4593),
    ),
);

//SOURCE REFERENCE ONLY:
$config['en_ids_7551'] = array(7545,10573,12896,4983,12682);
$config['en_all_7551'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-user-plus source" aria-hidden="true"></i>',
        'm_name' => 'ADD PROFILE',
        'm_desc' => '',
        'm_parents' => array(12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(12898,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'HIGHLIGHTS',
        'm_desc' => '',
        'm_parents' => array(12701,12321,4485,7551,11089,11018,11035,4755,4593,12893),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-comment source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    12682 => array(
        'm_icon' => '<i class="fas fa-lightbulb-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'REQUIREMENTS',
        'm_desc' => '',
        'm_parents' => array(4593,12321,7551,11089,10986,11018,4485),
    ),
);

//IDEA TYPE REQUIREMENT:
$config['en_ids_7309'] = array(6914,6907);
$config['en_all_7309'] = array(
    6914 => array(
        'm_icon' => '<i class="fas fa-cubes idea" aria-hidden="true"></i>',
        'm_name' => 'ALL',
        'm_desc' => '',
        'm_parents' => array(12700,12330,7486,7485,6140,6192,7585,7309,6997),
    ),
    6907 => array(
        'm_icon' => '<i class="fas fa-cube idea" aria-hidden="true"></i>',
        'm_name' => 'ANY',
        'm_desc' => '',
        'm_parents' => array(12883,12700,12330,7486,7485,6140,7585,7309,6997,6193),
    ),
);

//PLUGINS:
$config['en_ids_6287'] = array(7264,7261,12731,12734,7260,7263,11049,12733,7259,12735,7712,4527,12710,12709,12729,12888,7267,12732,7268,7269,12730,12738,12712,12737,12736,12739,12722);
$config['en_all_6287'] = array(
    7264 => array(
        'm_icon' => '<i class="fad fa-sitemap idea"></i>',
        'm_name' => 'IDEA BIRDS EYE MARKS',
        'm_desc' => '',
        'm_parents' => array(11047,6287),
    ),
    7261 => array(
        'm_icon' => '',
        'm_name' => 'IDEA LIST DUPLICATES',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12731 => array(
        'm_icon' => '',
        'm_name' => 'IDEA LIST INVALID TITLES',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12734 => array(
        'm_icon' => '',
        'm_name' => 'IDEA LIST NEXT/PREVIOUS CROSSOVERS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7260 => array(
        'm_icon' => '👶',
        'm_name' => 'IDEA LIST ORPHANED',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7263 => array(
        'm_icon' => '',
        'm_name' => 'IDEA MARKS LIST ALL',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    11049 => array(
        'm_icon' => '<i class="fad fa-lambda idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA REVIEW JSON',
        'm_desc' => '?in_id=',
        'm_parents' => array(12741,6287,11047),
    ),
    12733 => array(
        'm_icon' => '<i class="fad fa-code read"></i>',
        'm_name' => 'IDEA REVIEW READ',
        'm_desc' => '?in_id=',
        'm_parents' => array(11047,12741,12701,6287),
    ),
    7259 => array(
        'm_icon' => '',
        'm_name' => 'IDEA SEARCH & REPLACE',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12735 => array(
        'm_icon' => '',
        'm_name' => 'IDEA SYNC/FIX SOURCES',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7712 => array(
        'm_icon' => '<i class="far fa-question-circle" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE SELECT',
        'm_desc' => '',
        'm_parents' => array(6287,10893,4527),
    ),
    4527 => array(
        'm_icon' => '<i class="fas fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY',
        'm_desc' => '',
        'm_parents' => array(4755,6403,12741,6287,4527),
    ),
    12710 => array(
        'm_icon' => '👤',
        'm_name' => 'MY SESSION VARIABLES',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12709 => array(
        'm_icon' => 'ℹ️',
        'm_name' => 'PHP INFO',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    12729 => array(
        'm_icon' => '',
        'm_name' => 'PLATFORM COIN STATS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12888 => array(
        'm_icon' => '<i class="fas fa-shield-check source"></i>',
        'm_name' => 'SOURCE EXPLORE EXPERTS',
        'm_desc' => '?en_id=',
        'm_parents' => array(12741,6287,12887),
    ),
    7267 => array(
        'm_icon' => '🔍',
        'm_name' => 'SOURCE ICON SEARCH',
        'm_desc' => '',
        'm_parents' => array(12887,6287),
    ),
    12732 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE/IDEA SYNC STATUSES',
        'm_desc' => '',
        'm_parents' => array(12741,6287),
    ),
    7268 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE LIST DUPLICATES',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    7269 => array(
        'm_icon' => '👶',
        'm_name' => 'SOURCE LIST ORPHANED',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12730 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE NAME SEARCH & REPLACE',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12738 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE PLAYER RANDOM AVATARS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12712 => array(
        'm_icon' => '<i class="fad fa-lambda source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE REVIEW JSON',
        'm_desc' => '?en_id=',
        'm_parents' => array(12887,12741,6287),
    ),
    12737 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE SYNC & FIX LINK TRANSACTIONS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12736 => array(
        'm_icon' => '',
        'm_name' => 'SOURCE SYNC & FIX PLAYERS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12739 => array(
        'm_icon' => '',
        'm_name' => 'TRANSACTION ANALYZE URLS',
        'm_desc' => '',
        'm_parents' => array(6287),
    ),
    12722 => array(
        'm_icon' => '',
        'm_name' => 'TRANSACTION REVIEW JSON',
        'm_desc' => '?ln_id=',
        'm_parents' => array(12741,6287),
    ),
);

//READ STATUS INCOMPLETE:
$config['en_ids_7364'] = array(6175);
$config['en_all_7364'] = array(
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner-third fa-spin" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
);

//READ STATUS ACTIVE:
$config['en_ids_7360'] = array(6175,12399,6176);
$config['en_all_7360'] = array(
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner-third fa-spin" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    12399 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="far fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
);

//READ STATUS PUBLIC:
$config['en_ids_7359'] = array(12399,6176);
$config['en_all_7359'] = array(
    12399 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="far fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
);

//SOURCE STATUS ACTIVE:
$config['en_ids_7358'] = array(6180,12563,6181);
$config['en_all_7358'] = array(
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin source" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10654,7358,6177),
    ),
    12563 => array(
        'm_icon' => '<i class="fas fa-star source" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="far fa-globe source" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
);

//SOURCE STATUS PUBLIC:
$config['en_ids_7357'] = array(12563,6181);
$config['en_all_7357'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star source" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="far fa-globe source" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
);

//IDEA STATUS ACTIVE:
$config['en_ids_7356'] = array(6183,12137,6184);
$config['en_all_7356'] = array(
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin idea" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10648,7356,4737),
    ),
    12137 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(10986,10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="far fa-globe idea" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
);

//IDEA STATUS PUBLIC:
$config['en_ids_7355'] = array(12137,6184);
$config['en_all_7355'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(10986,10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="far fa-globe idea" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10648,7355,7356,4737),
    ),
);

//READ STATS:
$config['en_ids_7304'] = array(6186);
$config['en_all_7304'] = array(
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
);

//TRANSACTION STATUS:
$config['en_ids_6186'] = array(12399,6176,6175,6173);
$config['en_all_6186'] = array(
    12399 => array(
        'm_icon' => '<i class="fas fa-star" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(7360,7359,6186),
    ),
    6176 => array(
        'm_icon' => '<i class="far fa-globe" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(12012,7360,7359,6186),
    ),
    6175 => array(
        'm_icon' => '<i class="fas fa-spinner-third fa-spin" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(7364,7360,6186),
    ),
    6173 => array(
        'm_icon' => '<i class="fas fa-trash-alt" aria-hidden="true"></i>',
        'm_name' => 'UNLINKED',
        'm_desc' => '',
        'm_parents' => array(12012,10686,10678,10673,6186),
    ),
);

//SOURCE DATABASE REFERENCES:
$config['en_ids_6194'] = array(7274,4737,7585,6287,6177,4364,6186,4593);
$config['en_all_6194'] = array(
    7274 => array(
        'm_icon' => '<i class="far fa-magic" aria-hidden="true"></i>',
        'm_name' => 'CRON JOBS',
        'm_desc' => '',
        'm_parents' => array(12744,6194,11035,12728,12500,10876,4527,6405),
    ),
    4737 => array(
        'm_icon' => '<i class="fas fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10990,12079,6204,6226,6160,6232,6194,6201,4527),
    ),
    7585 => array(
        'm_icon' => '<i class="fas fa-random idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA TYPE',
        'm_desc' => '',
        'm_parents' => array(11054,12079,6204,10651,6160,6194,6232,4527,6201),
    ),
    6287 => array(
        'm_icon' => '<i class="fad fa-plug" aria-hidden="true"></i>',
        'm_name' => 'PLUGINS',
        'm_desc' => '',
        'm_parents' => array(6405,12744,6194,12699,12500,10876,11035,4527),
    ),
    6177 => array(
        'm_icon' => '<i class="fas fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS',
        'm_desc' => '',
        'm_parents' => array(12766,11054,6204,5003,6160,6232,6194,6206,4527),
    ),
    4364 => array(
        'm_icon' => '<i class="fad fa-user-edit" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION CREATOR',
        'm_desc' => '',
        'm_parents' => array(11081,6160,6232,6194,4341),
    ),
    6186 => array(
        'm_icon' => '<i class="far fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION STATUS',
        'm_desc' => '',
        'm_parents' => array(11054,10677,10656,6204,5865,6160,6232,7304,4527,6194,4341),
    ),
    4593 => array(
        'm_icon' => '<i class="fad fa-shapes" aria-hidden="true"></i>',
        'm_name' => 'TRANSACTION TYPE',
        'm_desc' => '',
        'm_parents' => array(6204,11081,10659,6160,6232,6194,4527,4341),
    ),
);

//READS:
$config['en_ids_6255'] = array(6157,7489,12117,4559,6144,7485,7486,6997);
$config['en_all_6255'] = array(
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
);

//BOOKMARK REMOVED:
$config['en_ids_6150'] = array(7757,6155);
$config['en_all_6150'] = array(
    7757 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'MANUAL',
        'm_desc' => '',
        'm_parents' => array(10888,6150,4593,4755),
    ),
);

//SOURCE REFERENCE ALLOWED:
$config['en_ids_4986'] = array(12419,4231);
$config['en_all_4986'] = array(
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12273,10939,12359,12322,10593,4986,4603,4593,4485),
    ),
);

//ACCOUNT SETTINGS:
$config['en_ids_6225'] = array(12289,10957,3288,3286);
$config['en_all_6225'] = array(
    12289 => array(
        'm_icon' => '<i class="fad fa-paw source" aria-hidden="true"></i>',
        'm_name' => 'AVATARS',
        'm_desc' => '',
        'm_parents' => array(6225,12897),
    ),
    10957 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(6225,11035,5007,4527),
    ),
    3288 => array(
        'm_icon' => '<i class="fad fa-envelope-open source" aria-hidden="true"></i>',
        'm_name' => 'EMAIL',
        'm_desc' => '',
        'm_parents' => array(4269,12103,6225,4755),
    ),
    3286 => array(
        'm_icon' => '<i class="fad fa-key source" aria-hidden="true"></i>',
        'm_name' => 'PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4269,7578,6225,4755),
    ),
);

//IDEA STATUS:
$config['en_ids_4737'] = array(12137,6184,6183,6182);
$config['en_all_4737'] = array(
    12137 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => 'Starting point idea, Searchable by all players',
        'm_parents' => array(10986,10648,12138,7356,7355,4737),
    ),
    6184 => array(
        'm_icon' => '<i class="far fa-globe idea" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => 'Continuation idea, accessible to anyone who has the URL',
        'm_parents' => array(10648,7355,7356,4737),
    ),
    6183 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin idea" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => 'Unreadable idea until published live',
        'm_parents' => array(10648,7356,4737),
    ),
    6182 => array(
        'm_icon' => '<i class="fad fa-trash-alt idea" aria-hidden="true"></i>',
        'm_name' => 'DELETED',
        'm_desc' => 'Archived idea',
        'm_parents' => array(12400,4593,4737),
    ),
);

//SOURCE STATUS:
$config['en_ids_6177'] = array(12563,6181,6180,6178);
$config['en_all_6177'] = array(
    12563 => array(
        'm_icon' => '<i class="fas fa-star source" aria-hidden="true"></i>',
        'm_name' => 'FEATURED',
        'm_desc' => '',
        'm_parents' => array(12575,10654,7358,7357,6177),
    ),
    6181 => array(
        'm_icon' => '<i class="far fa-globe source" aria-hidden="true"></i>',
        'm_name' => 'PUBLISHED',
        'm_desc' => '',
        'm_parents' => array(10654,7358,7357,6177),
    ),
    6180 => array(
        'm_icon' => '<i class="far fa-spinner fa-spin source" aria-hidden="true"></i>',
        'm_name' => 'DRAFTING',
        'm_desc' => '',
        'm_parents' => array(10654,7358,6177),
    ),
    6178 => array(
        'm_icon' => '<i class="fad fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'DELETED',
        'm_desc' => '',
        'm_parents' => array(4593,12401,6177),
    ),
);

//UNFINISHED:
$config['en_ids_6146'] = array(6143,7492);
$config['en_all_6146'] = array(
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'SKIPPED',
        'm_desc' => 'Completed when students skip an intention and all its child intentions from their Action Plan',
        'm_parents' => array(12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'TERMINATE',
        'm_desc' => 'Logged when users arrive at a locked intent that has no public OR parents or no children, which means there is no way to unlock it.',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
);

//SOURCE LIST EDITOR:
$config['en_ids_4997'] = array(5000,4998,4999,5001,5003,5865,5943,12318,10625,5982,5981,11956);
$config['en_all_4997'] = array(
    5000 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME REPLACE',
        'm_desc' => 'Search for occurrence of string in child entity names and if found, updates it with a replacement string',
        'm_parents' => array(12577,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME PREFIX',
        'm_desc' => 'Adds string to the beginning of all child entities. Make sure to include a space for it to look good',
        'm_parents' => array(12577,4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'NAME POSTFIX',
        'm_desc' => 'Adds string to the end of all child entities',
        'm_parents' => array(12577,4593,4997),
    ),
    5001 => array(
        'm_icon' => '<i class="source fad fa-sticky-note"></i>',
        'm_name' => 'CONTENT REPLACE',
        'm_desc' => 'Search for occurance of string in child entity link contents and if found, updates it with a replacement string',
        'm_parents' => array(4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="source fad fa-sliders-h"></i>',
        'm_name' => 'STATUS REPLACE',
        'm_desc' => 'Updates all child entity statuses that match the initial entity status condition',
        'm_parents' => array(4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="source fad fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'LINK STATUS REPLACE',
        'm_desc' => 'Updates all child entity link statuses that match the initial link status condition',
        'm_parents' => array(4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'ICON UPDATE FOR ALL',
        'm_desc' => 'Updates all child entity icons with string which needs to be a valid icon',
        'm_parents' => array(4593,4997),
    ),
    12318 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'ICON UPDATE IF MISSING',
        'm_desc' => 'Updates all icons that are not set to the new value.',
        'm_parents' => array(4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="source fad fa-user-circle"></i>',
        'm_name' => 'ICON REPLACE',
        'm_desc' => 'Search for occurrence of string in child entity icons and if found, updates it with a replacement string',
        'm_parents' => array(4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="source fad fa-layer-minus"></i>',
        'm_name' => 'PROFILE REMOVE',
        'm_desc' => 'If already added as the parent, this will remove searched entity as the parent of all child entities',
        'm_parents' => array(12577,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fad fa-layer-plus source" aria-hidden="true"></i>',
        'm_name' => 'PROFILE ADD',
        'm_desc' => 'If not already done so, will add searched entity as the parent of all child entities',
        'm_parents' => array(12577,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="source fad fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PROFILE IF ADD',
        'm_desc' => 'Adds a parent entity only IF the entity has another parent entity.',
        'm_parents' => array(12577,4593,4997),
    ),
);

//PRIVATE TRANSACTION:
$config['en_ids_4755'] = array(6415,12896,12773,12453,10681,12450,4527,11054,6232,4783,4755,12336,12334,12197,4554,7757,6155,5967,6559,6560,6556,6578,4556,6149,4283,6969,4275,7610,4555,4235,6132,12360,4266,4267,4282,6563,4570,7702,7495,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,6143,7492,4552,6140,12328,7578,6224,4553,7562,7563,6157,7489,4246,3288,12117,3286,7504,4559,6144,7485,7486,6997,12489);
$config['en_all_4755'] = array(
    6415 => array(
        'm_icon' => '<i class="fas fa-trash-alt read" aria-hidden="true"></i>',
        'm_name' => 'CLEAR BOOKSHELF',
        'm_desc' => '',
        'm_parents' => array(12500,4755,4593),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'HIGHLIGHTS',
        'm_desc' => '',
        'm_parents' => array(12701,12321,4485,7551,11089,11018,11035,4755,4593,12893),
    ),
    12773 => array(
        'm_icon' => '<i class="far fa-plus-circle idea"></i>',
        'm_name' => 'IDEA APPEND CONTENT',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone idea"></i>',
        'm_name' => 'IDEA FEATURE REQUEST',
        'm_desc' => '',
        'm_parents' => array(12137,4755,4593,5967),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-bars idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCE REQUEST',
        'm_desc' => '',
        'm_parents' => array(4593,4755,5967),
    ),
    4527 => array(
        'm_icon' => '<i class="fas fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY',
        'm_desc' => '',
        'm_parents' => array(4755,6403,12741,6287,4527),
    ),
    11054 => array(
        'm_icon' => '<i class="fal fa-memory" aria-hidden="true"></i>',
        'm_name' => 'MENCH MEMORY JAVASCRIPT',
        'm_desc' => '',
        'm_parents' => array(4755,6403,4527),
    ),
    6232 => array(
        'm_icon' => '<i class="far fa-lambda" aria-hidden="true"></i>',
        'm_name' => 'MENCH VARIABLE',
        'm_desc' => '',
        'm_parents' => array(6403,4755,4527,6212),
    ),
    4783 => array(
        'm_icon' => '<i class="far fa-phone source"></i>',
        'm_name' => 'PHONE',
        'm_desc' => '',
        'm_parents' => array(4755,4319),
    ),
    4755 => array(
        'm_icon' => '<i class="fal fa-eye-slash" aria-hidden="true"></i>',
        'm_name' => 'PRIVATE TRANSACTION',
        'm_desc' => '',
        'm_parents' => array(12701,4755,6771,4527),
    ),
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12197 => array(
        'm_icon' => '<i class="fad fa-user-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ APPEND PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7757 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED MANUAL',
        'm_desc' => '',
        'm_parents' => array(10888,6150,4593,4755),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ CARBON COPY',
        'm_desc' => '',
        'm_parents' => array(4527,7569,4755,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA CONSIDERED',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA LISTED',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="read fad fa-megaphone"></i>',
        'm_name' => 'READ IDEA RECOMMENDED',
        'm_desc' => '',
        'm_parents' => array(4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="read fad fa-search"></i>',
        'm_name' => 'READ IDEA SEARCH',
        'm_desc' => '',
        'm_parents' => array(6554,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="fad fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA VIEW',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4235 => array(
        'm_icon' => '<i class="fad fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ INITIATED',
        'm_desc' => '',
        'm_parents' => array(12227,7347,5967,4755,4593),
    ),
    6132 => array(
        'm_icon' => '<i class="fad fa-bars read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST SORTED',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    12360 => array(
        'm_icon' => '<i class="fad fa-pen read"></i>',
        'm_name' => 'READ MASS CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(6771,4593,4755),
    ),
    4266 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER OPT-IN',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="read fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED IDEA',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,7569),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone read" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => '',
        'm_parents' => array(12227,7347,4755,4593),
    ),
    4577 => array(
        'm_icon' => '<i class="read fad fa-user-plus"></i>',
        'm_name' => 'READ SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="read fad fa-location-circle"></i>',
        'm_name' => 'READ SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ SENT MESSENGER',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="read fad fa-cloud-download"></i>',
        'm_name' => 'READ SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="read fad fa-user-tag"></i>',
        'm_name' => 'READ SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="read fad fa-comment-exclamation"></i>',
        'm_name' => 'READ SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fad fa-sign-in read" aria-hidden="true"></i>',
        'm_name' => 'READ SIGNIN FROM IDEA',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => '',
        'm_parents' => array(12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => '',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    12328 => array(
        'm_icon' => '<i class="fad fa-sync read"></i>',
        'm_name' => 'READ UPDATE COMPLETION',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6153),
    ),
    7578 => array(
        'm_icon' => '<i class="read fad fa-key"></i>',
        'm_name' => 'READ UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="read fad fa-sync"></i>',
        'm_name' => 'READ UPDATE PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7562 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ WELCOME',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
    ),
    7563 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'SIGN MAGIC EMAIL',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    3288 => array(
        'm_icon' => '<i class="fad fa-envelope-open source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE EMAIL',
        'm_desc' => '',
        'm_parents' => array(4269,12103,6225,4755),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    3286 => array(
        'm_icon' => '<i class="fad fa-key source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(4269,7578,6225,4755),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PENDING MODERATION',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
    12489 => array(
        'm_icon' => '<i class="far fa-medal read"></i>',
        'm_name' => 'VIEWED LEADERBOARD',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
);

//TRANSACTION TYPE:
$config['en_ids_4593'] = array(7545,10573,6415,12419,12896,12773,4250,6182,12453,4229,4228,10686,10663,10664,6226,10676,10678,10679,10677,10681,10675,12450,4983,10662,10648,10650,10644,10651,4993,4601,4231,12591,12592,5001,10625,5943,12318,5865,4999,4998,5000,5981,11956,5982,5003,12129,12336,12334,12197,4554,7757,6155,5967,6559,6560,6556,6578,10683,4556,6149,4283,6969,4275,7610,4555,4235,6132,12360,10690,4266,4267,4282,6563,4570,7702,7495,4577,4549,4551,4550,4557,4278,4279,4268,4460,4547,4287,4548,7560,7561,7564,7559,7558,6143,7492,4552,6140,12328,7578,6224,4553,7562,12682,7563,6157,7489,4246,4251,6178,12117,10653,4259,10657,4257,4261,10669,4260,4319,7657,4230,10656,4255,4318,10659,10673,4256,4258,12827,10689,10646,7504,4559,10654,6144,5007,7485,7486,6997,4994,12489);
$config['en_all_4593'] = array(
    7545 => array(
        'm_icon' => '<i class="fas fa-user-plus source" aria-hidden="true"></i>',
        'm_name' => 'ADD PROFILE',
        'm_desc' => '',
        'm_parents' => array(12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(12898,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    6415 => array(
        'm_icon' => '<i class="fas fa-trash-alt read" aria-hidden="true"></i>',
        'm_name' => 'CLEAR BOOKSHELF',
        'm_desc' => 'Removes all player read coins so everything is reset to 0% again.',
        'm_parents' => array(12500,4755,4593),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'HIGHLIGHTS',
        'm_desc' => '',
        'm_parents' => array(12701,12321,4485,7551,11089,11018,11035,4755,4593,12893),
    ),
    12773 => array(
        'm_icon' => '<i class="far fa-plus-circle idea"></i>',
        'm_name' => 'IDEA APPEND CONTENT',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    4250 => array(
        'm_icon' => '<i class="fas fa-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA CREATED',
        'm_desc' => '',
        'm_parents' => array(5967,12400,12149,10593,4593),
    ),
    6182 => array(
        'm_icon' => '<i class="fad fa-trash-alt idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA DELETED',
        'm_desc' => '',
        'm_parents' => array(12400,4593,4737),
    ),
    12453 => array(
        'm_icon' => '<i class="fad fa-megaphone idea"></i>',
        'm_name' => 'IDEA FEATURE REQUEST',
        'm_desc' => '',
        'm_parents' => array(12137,4755,4593,5967),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK CONDITIONAL',
        'm_desc' => '',
        'm_parents' => array(12842,4527,6410,6283,4593,4486),
    ),
    4228 => array(
        'm_icon' => '<i class="fad fa-play-circle idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK FIXED',
        'm_desc' => '',
        'm_parents' => array(12840,6410,4593,4486),
    ),
    10686 => array(
        'm_icon' => '<i class="fad fa-times idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10663 => array(
        'm_icon' => '<i class="fad fa-coin idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK UPDATE MARKS',
        'm_desc' => '',
        'm_parents' => array(4228,4593),
    ),
    10664 => array(
        'm_icon' => '<i class="fad fa-bolt idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA LINK UPDATE SCORE',
        'm_desc' => '',
        'm_parents' => array(4593,4229),
    ),
    6226 => array(
        'm_icon' => '<i class="fad fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA MASS UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10676 => array(
        'm_icon' => '<i class="fad fa-bars idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES SORTED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10678 => array(
        'm_icon' => '<i class="fad fa-trash-alt idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10679 => array(
        'm_icon' => '<i class="fad fa-comment-plus idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES UPDATE CONTENT',
        'm_desc' => '',
        'm_parents' => array(4593,10593),
    ),
    10677 => array(
        'm_icon' => '<i class="fad fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA NOTES UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10681 => array(
        'm_icon' => '<i class="fad fa-bars idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SORT AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    10675 => array(
        'm_icon' => '<i class="fad fa-bars idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA SORT MANUAL',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    12450 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCE REQUEST',
        'm_desc' => '',
        'm_parents' => array(4593,4755,5967),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-comment source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    10662 => array(
        'm_icon' => '<i class="fad fa-hashtag idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE LINK',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10648 => array(
        'm_icon' => '<i class="fad fa-sliders-h idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE STATUS',
        'm_desc' => '',
        'm_parents' => array(12400,4593),
    ),
    10650 => array(
        'm_icon' => '<i class="fad fa-clock idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE TIME',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    10644 => array(
        'm_icon' => '<i class="fad fa-bullseye-arrow idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE TITLE',
        'm_desc' => 'Logged when trainers update the intent outcome',
        'm_parents' => array(10593,4593),
    ),
    10651 => array(
        'm_icon' => '<i class="fad fa-shapes idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA UPDATE TYPE',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4993 => array(
        'm_icon' => '<i class="fad fa-eye idea" aria-hidden="true"></i>',
        'm_name' => 'IDEA VIEWED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags idea" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12322,10593,4593,4485),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12273,10939,12359,12322,10593,4986,4603,4593,4485),
    ),
    12591 => array(
        'm_icon' => '<i class="fas fa-plus-circle source" aria-hidden="true"></i>',
        'm_name' => 'NEXT EDITOR ADD SOURCE',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    12592 => array(
        'm_icon' => '<i class="fas fa-minus-circle source" aria-hidden="true"></i>',
        'm_name' => 'NEXT EDITOR REMOVE SOURCE',
        'm_desc' => '',
        'm_parents' => array(4593,12589),
    ),
    5001 => array(
        'm_icon' => '<i class="source fad fa-sticky-note"></i>',
        'm_name' => 'PORTFOLIO EDITOR CONTENT REPLACE',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    10625 => array(
        'm_icon' => '<i class="source fad fa-user-circle"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON REPLACE',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    5943 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON UPDATE FOR ALL',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    12318 => array(
        'm_icon' => '<i class="fad fa-user-circle source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR ICON UPDATE IF MISSING',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    5865 => array(
        'm_icon' => '<i class="source fad fa-sliders-h" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR LINK STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    4999 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME POSTFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    4998 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME PREFIX',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5000 => array(
        'm_icon' => '<i class="source fad fa-fingerprint"></i>',
        'm_name' => 'PORTFOLIO EDITOR NAME REPLACE',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5981 => array(
        'm_icon' => '<i class="fad fa-layer-plus source" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    11956 => array(
        'm_icon' => '<i class="source fad fa-layer-plus" aria-hidden="true"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE IF ADD',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5982 => array(
        'm_icon' => '<i class="source fad fa-layer-minus"></i>',
        'm_name' => 'PORTFOLIO EDITOR PROFILE REMOVE',
        'm_desc' => '',
        'm_parents' => array(12577,4593,4997),
    ),
    5003 => array(
        'm_icon' => '<i class="source fad fa-sliders-h"></i>',
        'm_name' => 'PORTFOLIO EDITOR STATUS REPLACE',
        'm_desc' => '',
        'm_parents' => array(4593,4997),
    ),
    12129 => array(
        'm_icon' => '<i class="fas fa-times-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER DELETED',
        'm_desc' => '',
        'm_parents' => array(6153,4593),
    ),
    12336 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER ONE LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12334 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'READ ANSWER SOME LINK',
        'm_desc' => '',
        'm_parents' => array(7704,4755,4593,12326,12227),
    ),
    12197 => array(
        'm_icon' => '<i class="fad fa-user-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ APPEND PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4554 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7757 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED AUTO',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6150),
    ),
    6155 => array(
        'm_icon' => '<i class="read fad fa-bookmark read" aria-hidden="true"></i>',
        'm_name' => 'READ BOOKMARK REMOVED MANUAL',
        'm_desc' => 'Student prematurely removed an intention from their Action Plan without accomplishing it.',
        'm_parents' => array(10888,6150,4593,4755),
    ),
    5967 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ CARBON COPY',
        'm_desc' => '',
        'm_parents' => array(4527,7569,4755,4593),
    ),
    6559 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED NEXT',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6560 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED SKIP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6556 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STATS',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    6578 => array(
        'm_icon' => '<i class="read fad fa-wand-magic"></i>',
        'm_name' => 'READ COMMANDED STOP',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6554),
    ),
    10683 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ EMAIL',
        'm_desc' => '',
        'm_parents' => array(6153,4593,7654),
    ),
    4556 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ FILE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6149 => array(
        'm_icon' => '<i class="fad fa-search-plus read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA CONSIDERED',
        'm_desc' => 'When a student chooses to review a given intention from the intentions they have searched or have been recommended after selecting GET STARTED from a mench.com intent landing page.',
        'm_parents' => array(6153,4755,4593),
    ),
    4283 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA LISTED',
        'm_desc' => 'Once a student has added an Intention to their Action Plan, this link will be logged every time they access that Action Plan and view its intentions.',
        'm_parents' => array(6153,4755,4593),
    ),
    6969 => array(
        'm_icon' => '<i class="read fad fa-megaphone"></i>',
        'm_name' => 'READ IDEA RECOMMENDED',
        'm_desc' => 'Logged every time an intention is recommended to a user by Mench',
        'm_parents' => array(4593,4755,6153),
    ),
    4275 => array(
        'm_icon' => '<i class="read fad fa-search"></i>',
        'm_name' => 'READ IDEA SEARCH',
        'm_desc' => 'When students invokes the [I want to] command and search for a new intention that they would like to add to their Action Plan.',
        'm_parents' => array(6554,4755,4593),
    ),
    7610 => array(
        'm_icon' => '<i class="fad fa-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ IDEA VIEW',
        'm_desc' => 'When a user viewes the public intent landing page.',
        'm_parents' => array(4755,4593),
    ),
    4555 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    4235 => array(
        'm_icon' => '<i class="fad fa-play-circle read" aria-hidden="true"></i>',
        'm_name' => 'READ INITIATED',
        'm_desc' => '',
        'm_parents' => array(12227,7347,5967,4755,4593),
    ),
    6132 => array(
        'm_icon' => '<i class="fad fa-bars read" aria-hidden="true"></i>',
        'm_name' => 'READ LIST SORTED',
        'm_desc' => 'Student re-prioritized their top-level intentions to focus on intentions that currently matter the most.',
        'm_parents' => array(6153,4755,4593),
    ),
    12360 => array(
        'm_icon' => '<i class="fad fa-pen read"></i>',
        'm_name' => 'READ MASS CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(6771,4593,4755),
    ),
    10690 => array(
        'm_icon' => '<i class="read fad fa-upload"></i>',
        'm_name' => 'READ MEDIA UPLOADED',
        'm_desc' => 'When a file added by the user is synced to the CDN',
        'm_parents' => array(6153,4593),
    ),
    4266 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER OPT-IN',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4267 => array(
        'm_icon' => '<i class="read fab fa-facebook-messenger"></i>',
        'm_name' => 'READ MESSENGER REFERRAL',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4282 => array(
        'm_icon' => '<i class="read fad fa-eye"></i>',
        'm_name' => 'READ OPENED PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    6563 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(10593,4593,4755,4280),
    ),
    4570 => array(
        'm_icon' => '<i class="read fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED',
        'm_desc' => '',
        'm_parents' => array(10683,10593,7569,4755,4593),
    ),
    7702 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'READ RECEIVED IDEA',
        'm_desc' => 'Emails sent to intent subscribers who are looking for updates on an intent.',
        'm_parents' => array(10593,4593,4755,7569),
    ),
    7495 => array(
        'm_icon' => '<i class="fad fa-megaphone read" aria-hidden="true"></i>',
        'm_name' => 'READ RECOMMEND',
        'm_desc' => 'Intention recommended by Mench and added to Action Plan to enable the user to complete their intention',
        'm_parents' => array(12227,7347,4755,4593),
    ),
    4577 => array(
        'm_icon' => '<i class="read fad fa-user-plus"></i>',
        'm_name' => 'READ SENT ACCESS',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4549 => array(
        'm_icon' => '<i class="read fad fa-volume-up"></i>',
        'm_name' => 'READ SENT AUDIO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4551 => array(
        'm_icon' => '<i class="read fad fa-file-pdf"></i>',
        'm_name' => 'READ SENT FILE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4550 => array(
        'm_icon' => '<i class="read fad fa-image"></i>',
        'm_name' => 'READ SENT IMAGE',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    4557 => array(
        'm_icon' => '<i class="read fad fa-location-circle"></i>',
        'm_name' => 'READ SENT LOCATION',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4278 => array(
        'm_icon' => '<i class="fad fa-eye read" aria-hidden="true"></i>',
        'm_name' => 'READ SENT MESSENGER',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4279 => array(
        'm_icon' => '<i class="read fad fa-cloud-download"></i>',
        'm_name' => 'READ SENT MESSENGER RECEIVED',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4268 => array(
        'm_icon' => '<i class="read fad fa-user-tag"></i>',
        'm_name' => 'READ SENT POSTBACK',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4460 => array(
        'm_icon' => '<i class="read fad fa-check"></i>',
        'm_name' => 'READ SENT QUICK REPLY',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4547 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ SENT TEXT',
        'm_desc' => '',
        'm_parents' => array(7653,4755,4593),
    ),
    4287 => array(
        'm_icon' => '<i class="read fad fa-comment-exclamation"></i>',
        'm_name' => 'READ SENT UNKNOWN MESSAGE',
        'm_desc' => '',
        'm_parents' => array(7654,4755,4593),
    ),
    4548 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ SENT VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,7653,4755,4593),
    ),
    7560 => array(
        'm_icon' => '<i class="fad fa-sign-in read" aria-hidden="true"></i>',
        'm_name' => 'READ SIGNIN FROM IDEA',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7561 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN GENERALLY',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7564 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN SUCCESS',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7559 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH EMAIL',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    7558 => array(
        'm_icon' => '<i class="fad fa-sign-in read"></i>',
        'm_name' => 'READ SIGNIN WITH MESSENGER',
        'm_desc' => '',
        'm_parents' => array(12351,4755,4593),
    ),
    6143 => array(
        'm_icon' => '<i class="far fa-comment-times read" aria-hidden="true"></i>',
        'm_name' => 'READ SKIPPED',
        'm_desc' => 'Logged every time a student consciously skips an intent and it\'s recursive children.',
        'm_parents' => array(12229,12227,6146,4755,4593),
    ),
    7492 => array(
        'm_icon' => '<i class="fas fa-times-octagon read" aria-hidden="true"></i>',
        'm_name' => 'READ TERMINATE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,4755,4593,6146),
    ),
    4552 => array(
        'm_icon' => '<i class="read fad fa-align-left"></i>',
        'm_name' => 'READ TEXT',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    6140 => array(
        'm_icon' => '<i class="fad fa-lock-open read" aria-hidden="true"></i>',
        'm_name' => 'READ UNLOCK CONDITION LINK',
        'm_desc' => 'Created when the student responses to OR branches meets the right % points to unlock the pathway to a conditional intent link.',
        'm_parents' => array(12326,12227,6410,4229,4755,4593),
    ),
    12328 => array(
        'm_icon' => '<i class="fad fa-sync read"></i>',
        'm_name' => 'READ UPDATE COMPLETION',
        'm_desc' => '',
        'm_parents' => array(4755,4593,6153),
    ),
    7578 => array(
        'm_icon' => '<i class="read fad fa-key"></i>',
        'm_name' => 'READ UPDATE PASSWORD',
        'm_desc' => '',
        'm_parents' => array(6153,4755,4593),
    ),
    6224 => array(
        'm_icon' => '<i class="read fad fa-sync"></i>',
        'm_name' => 'READ UPDATE PROFILE',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
    4553 => array(
        'm_icon' => '<i class="read fad fa-video"></i>',
        'm_name' => 'READ VIDEO',
        'm_desc' => '',
        'm_parents' => array(10593,4755,4593,4280),
    ),
    7562 => array(
        'm_icon' => '<i class="read fad fa-envelope-open"></i>',
        'm_name' => 'READ WELCOME',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
    ),
    12682 => array(
        'm_icon' => '<i class="fas fa-lightbulb-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'REQUIREMENTS',
        'm_desc' => '',
        'm_parents' => array(4593,12321,7551,11089,10986,11018,4485),
    ),
    7563 => array(
        'm_icon' => '<i class="fad fa-envelope-open read" aria-hidden="true"></i>',
        'm_name' => 'SIGN MAGIC EMAIL',
        'm_desc' => '',
        'm_parents' => array(4755,7569,4593),
    ),
    6157 => array(
        'm_icon' => '<i class="fas fa-check-circle read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER ONE',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,6255,4755,4593),
    ),
    7489 => array(
        'm_icon' => '<i class="fas fa-check-square read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE ANSWER SOME',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,7704,4755,6255,4593),
    ),
    4246 => array(
        'm_icon' => '<i class="fad fa-bug source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE BUG REPORTS',
        'm_desc' => '',
        'm_parents' => array(5967,4755,4593),
    ),
    4251 => array(
        'm_icon' => '<i class="fas fa-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE CREATED',
        'm_desc' => 'Logged when a new entity is created.',
        'm_parents' => array(12274,12401,12149,12141,10593,4593),
    ),
    6178 => array(
        'm_icon' => '<i class="fad fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE DELETED',
        'm_desc' => '',
        'm_parents' => array(4593,12401,6177),
    ),
    12117 => array(
        'm_icon' => '<i class="far fa-paperclip read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE FILE UPLOAD',
        'm_desc' => '',
        'm_parents' => array(12229,12227,12141,4593,4755,6255),
    ),
    10653 => array(
        'm_icon' => '<i class="fad fa-user-circle source"></i>',
        'm_name' => 'SOURCE ICON UPDATE',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'SOURCE LINK AUDIO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    10657 => array(
        'm_icon' => '<i class="fad fa-comment-plus source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK CONTENT UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK EMBED',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK FILE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    10669 => array(
        'm_icon' => '<i class="fab fa-font-awesome-alt source"></i>',
        'm_name' => 'SOURCE LINK ICON',
        'm_desc' => '',
        'm_parents' => array(4593,6198,4592),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK IMAGE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="fad fa-sort-numeric-down source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK INTEGER',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    7657 => array(
        'm_icon' => '<i class="fas fa-divide source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK PERCENT',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="fad fa-link rotate90 source"></i>',
        'm_name' => 'SOURCE LINK RAW',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    10656 => array(
        'm_icon' => '<i class="fad fa-sliders-h source"></i>',
        'm_name' => 'SOURCE LINK STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'SOURCE LINK TEXT',
        'm_desc' => '',
        'm_parents' => array(12822,10593,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="fad fa-clock source"></i>',
        'm_name' => 'SOURCE LINK TIME',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    10659 => array(
        'm_icon' => '<i class="fad fa-plug source"></i>',
        'm_name' => 'SOURCE LINK TYPE UPDATE',
        'm_desc' => 'Iterations happens automatically based on link content',
        'm_parents' => array(4593),
    ),
    10673 => array(
        'm_icon' => '<i class="fad fa-trash-alt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK UNLINKED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE LINK URL',
        'm_desc' => '',
        'm_parents' => array(12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'SOURCE LINK VIDEO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    12827 => array(
        'm_icon' => '<i class="fad fa-font source"></i>',
        'm_name' => 'SOURCE LINK WORD',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    10689 => array(
        'm_icon' => '<i class="fad fa-share-alt rotate90 source"></i>',
        'm_name' => 'SOURCE MERGED IN SOURCE',
        'm_desc' => 'When an entity is merged with another entity and the links are carried over',
        'm_parents' => array(4593),
    ),
    10646 => array(
        'm_icon' => '<i class="fad fa-fingerprint source"></i>',
        'm_name' => 'SOURCE NAME UPDATE',
        'm_desc' => '',
        'm_parents' => array(10593,4593),
    ),
    7504 => array(
        'm_icon' => '<i class="fad fa-comment-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE PENDING MODERATION',
        'm_desc' => 'Certain links that match an unknown behavior would require an admin to review and ensure it\'s all good',
        'm_parents' => array(5967,4755,4593),
    ),
    4559 => array(
        'm_icon' => '<i class="far fa-eye read"></i>',
        'm_name' => 'SOURCE READ MESSAGES',
        'm_desc' => 'Logged when a student receives the messages of an AND intent that does not have any completion requirements.',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    10654 => array(
        'm_icon' => '<i class="fad fa-sliders-h source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE STATUS UPDATE',
        'm_desc' => '',
        'm_parents' => array(12401,4593),
    ),
    6144 => array(
        'm_icon' => '<i class="far fa-keyboard read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TEXT REPLY',
        'm_desc' => 'Logged when a student submits the requirements (text, video, etc...) of an AND intent which could not be completed by simply receiving messages.',
        'm_parents' => array(12229,12227,12141,6255,4755,4593),
    ),
    5007 => array(
        'm_icon' => '<i class="fad fa-bolt source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE TOGGLE SUPERPOWERS',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    7485 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK ANSWER',
        'm_desc' => '',
        'm_parents' => array(12334,12336,7489,6157,12327,12229,12227,12141,4593,4755,6255),
    ),
    7486 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CHILDREN',
        'm_desc' => '',
        'm_parents' => array(12327,12229,12227,12141,4755,4593,6255),
    ),
    6997 => array(
        'm_icon' => '<i class="fas fa-clipboard-check read" aria-hidden="true"></i>',
        'm_name' => 'SOURCE UNLOCK CONDITION',
        'm_desc' => '',
        'm_parents' => array(6140,12327,12229,12227,12141,4229,6255,4593,4755),
    ),
    4994 => array(
        'm_icon' => '<i class="fad fa-eye source" aria-hidden="true"></i>',
        'm_name' => 'SOURCE VIEWED',
        'm_desc' => '',
        'm_parents' => array(4593),
    ),
    12489 => array(
        'm_icon' => '<i class="far fa-medal read"></i>',
        'm_name' => 'VIEWED LEADERBOARD',
        'm_desc' => '',
        'm_parents' => array(4755,4593),
    ),
);

//SOURCE LINKS:
$config['en_ids_4592'] = array(4259,4257,4261,10669,4260,4319,7657,4230,4255,4318,4256,4258,12827);
$config['en_all_4592'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'EMBED',
        'm_desc' => 'Embeddable videos',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    10669 => array(
        'm_icon' => '<i class="fab fa-font-awesome-alt source"></i>',
        'm_name' => 'ICON',
        'm_desc' => 'Icons maping to the Font Awesome database',
        'm_parents' => array(4593,6198,4592),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => '',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,6203,4593,4592,4537),
    ),
    4319 => array(
        'm_icon' => '<i class="fad fa-sort-numeric-down source" aria-hidden="true"></i>',
        'm_name' => 'INTEGER',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    7657 => array(
        'm_icon' => '<i class="fas fa-divide source" aria-hidden="true"></i>',
        'm_name' => 'PERCENT',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    4230 => array(
        'm_icon' => '<i class="fad fa-link rotate90 source"></i>',
        'm_name' => 'RAW',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    4255 => array(
        'm_icon' => '<i class="fad fa-align-left source"></i>',
        'm_name' => 'TEXT',
        'm_desc' => '',
        'm_parents' => array(12822,10593,4593,4592),
    ),
    4318 => array(
        'm_icon' => '<i class="fad fa-clock source"></i>',
        'm_name' => 'TIME',
        'm_desc' => '',
        'm_parents' => array(4593,4592),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => '',
        'm_parents' => array(12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'Uploaded videos',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    12827 => array(
        'm_icon' => '<i class="fad fa-font source"></i>',
        'm_name' => 'WORD',
        'm_desc' => 'Single Word',
        'm_parents' => array(4593,4592),
    ),
);

//IDEA NOTES:
$config['en_ids_4485'] = array(12896,4231,12419,10573,4601,4983,12682,7545);
$config['en_all_4485'] = array(
    12896 => array(
        'm_icon' => '<i class="fas fa-bookmark read"></i>',
        'm_name' => 'HIGHLIGHTS',
        'm_desc' => '',
        'm_parents' => array(12701,12321,4485,7551,11089,11018,11035,4755,4593,12893),
    ),
    4231 => array(
        'm_icon' => '<i class="fas fa-comment idea" aria-hidden="true"></i>',
        'm_name' => 'MESSAGES',
        'm_desc' => '',
        'm_parents' => array(12273,10939,12359,12322,10593,4986,4603,4593,4485),
    ),
    12419 => array(
        'm_icon' => '<i class="fas fa-comments-alt idea" aria-hidden="true"></i>',
        'm_name' => 'COMMENTS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12359,5967,10593,12322,4986,11089,4593,4485),
    ),
    10573 => array(
        'm_icon' => '<i class="fas fa-star idea" aria-hidden="true"></i>',
        'm_name' => 'BOOKMARKS',
        'm_desc' => '',
        'm_parents' => array(12898,11054,4527,10984,11018,11035,11089,12321,4593,7551,4485),
    ),
    4601 => array(
        'm_icon' => '<i class="fas fa-tags idea" aria-hidden="true"></i>',
        'm_name' => 'KEYWORDS',
        'm_desc' => '',
        'm_parents' => array(10984,11018,12322,10593,4593,4485),
    ),
    4983 => array(
        'm_icon' => '<i class="fas fa-comment source" aria-hidden="true"></i>',
        'm_name' => 'IDEA SOURCES',
        'm_desc' => '',
        'm_parents' => array(11054,4485,11018,12141,10939,12450,12273,12228,10593,4527,7551,4593),
    ),
    12682 => array(
        'm_icon' => '<i class="fas fa-lightbulb-exclamation source" aria-hidden="true"></i>',
        'm_name' => 'REQUIREMENTS',
        'm_desc' => '',
        'm_parents' => array(4593,12321,7551,11089,10986,11018,4485),
    ),
    7545 => array(
        'm_icon' => '<i class="fas fa-user-plus source" aria-hidden="true"></i>',
        'm_name' => 'ADD PROFILE',
        'm_desc' => '',
        'm_parents' => array(12197,11030,11018,10986,11089,12321,7551,4593,4485),
    ),
);

//IDEA LINKS:
$config['en_ids_4486'] = array(4228,4229);
$config['en_all_4486'] = array(
    4228 => array(
        'm_icon' => '<i class="fad fa-play-circle idea" aria-hidden="true"></i>',
        'm_name' => 'FIXED',
        'm_desc' => 'Follow each other',
        'm_parents' => array(12840,6410,4593,4486),
    ),
    4229 => array(
        'm_icon' => '<i class="fad fa-question-circle idea" aria-hidden="true"></i>',
        'm_name' => 'CONDITIONAL',
        'm_desc' => 'May follow each other',
        'm_parents' => array(12842,4527,6410,6283,4593,4486),
    ),
);

//SOURCE LINK URLS:
$config['en_ids_4537'] = array(4259,4257,4261,4260,4256,4258);
$config['en_all_4537'] = array(
    4259 => array(
        'm_icon' => '<i class="fad fa-volume-up source"></i>',
        'm_name' => 'AUDIO',
        'm_desc' => 'URL to a raw audio file',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4257 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'EMBED',
        'm_desc' => 'Recognizable URL that offers an embed widget for a more engaging play-back experience',
        'm_parents' => array(12822,12605,12524,12403,4593,4592,4537),
    ),
    4261 => array(
        'm_icon' => '<i class="fad fa-file-pdf source" aria-hidden="true"></i>',
        'm_name' => 'FILE',
        'm_desc' => 'URL to a raw file',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
    4260 => array(
        'm_icon' => '<i class="fad fa-image source" aria-hidden="true"></i>',
        'm_name' => 'IMAGE',
        'm_desc' => 'URL to a raw image file',
        'm_parents' => array(12822,12605,12524,6198,11080,11059,10593,6203,4593,4592,4537),
    ),
    4256 => array(
        'm_icon' => '<i class="fad fa-browser source" aria-hidden="true"></i>',
        'm_name' => 'URL',
        'm_desc' => 'Link note contains a generic URL only',
        'm_parents' => array(12822,11080,4593,4592,4537),
    ),
    4258 => array(
        'm_icon' => '<i class="fad fa-video source"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => 'URL to a raw video file',
        'm_parents' => array(12822,12605,12524,11080,11059,10593,6203,4593,4592,4537),
    ),
);

//EXPERT CHANNEL:
$config['en_ids_3000'] = array(3005,2999,2998,2997,3147,4446,3192,4763);
$config['en_all_3000'] = array(
    3005 => array(
        'm_icon' => '<i class="fad fa-book source" aria-hidden="true"></i>',
        'm_name' => 'BOOK',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    2999 => array(
        'm_icon' => '<i class="fad fa-microphone source" aria-hidden="true"></i>',
        'm_name' => 'PODCAST',
        'm_desc' => '',
        'm_parents' => array(4983,12523,10809,3000),
    ),
    2998 => array(
        'm_icon' => '<i class="fad fa-play-circle source" aria-hidden="true"></i>',
        'm_name' => 'VIDEO',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    2997 => array(
        'm_icon' => '<i class="fad fa-newspaper source" aria-hidden="true"></i>',
        'm_name' => 'ARTICLE',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    3147 => array(
        'm_icon' => '<i class="fad fa-presentation source" aria-hidden="true"></i>',
        'm_name' => 'COURSE',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    4446 => array(
        'm_icon' => '<i class="fad fa-tachometer source" aria-hidden="true"></i>',
        'm_name' => 'ASSESSMENT',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    3192 => array(
        'm_icon' => '<i class="fad fa-tools source" aria-hidden="true"></i>',
        'm_name' => 'TOOL',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
    4763 => array(
        'm_icon' => '<i class="fad fa-megaphone source" aria-hidden="true"></i>',
        'm_name' => 'MARKETING',
        'm_desc' => '',
        'm_parents' => array(4983,12523,3000),
    ),
);