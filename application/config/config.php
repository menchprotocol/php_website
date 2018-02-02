<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Settime zone to PST:
date_default_timezone_set('America/Los_Angeles');

//Primary website variables:
$config['website'] = array(
    'version' => 1.94,
    'name' => 'Mench',
    'legaL_name' => 'Mench Media Inc.',
    'url' => 'https://mench.co/', //Important to end with "/" as other links depend on this.
    'email' => 'shervin@mench.co',
);

//This would hide sensitive config variables from the /api_v1/config end point used to sync data among other servers
$config['show_in_api'] = array(
    'language',
    'charset',
    'file_limit_mb',
    'message_max',
    'website',
    'mench_advisers',
    'mench_pricing',
    'core_objects',
    'object_statuses',
    'timezones',
    'languages',
    'countries_all',
);

$config['mench_pricing'] = array(
    'share_operator' => 0.7, //This goes to Bootcamp Lead Instructor, and he decides how to gets divided among his team
    'share_distributor' => 0.2, //What goes to the distributor who got the student to purchase the seat, paid along side the performance payout
    'share_platform' => 0.1, //Includes CC processing fee's via Paypal to get money to instructor
    //Top three should total 1
    'instant_payout' => 0.4, //What percentage of money paid to instructor on Day 1 of the Bootcamp, Remainder is paid via Performance Payout
);

//All active bots connected to the platform:
$config['mench_bots'] = array(
    '381488558920384' => array(
        'name' => 'MenchBot', //For Students
        'fb_page_id' => '381488558920384',
        'fb_app_id' => '1782431902047009',
        'bot_ref_url' => 'https://m.me/menchbot',
        'access_token' => 'EAAZAVHMRbmyEBAJET1DMKAAQPOoHZABRs9hXxTk6l7g0ETkwETgJatVIaMIXRJ9DclVB61xkNidUYZC512MJKpfNlIGGDaFzHuIq6JrfbOtZADlxH39cI2AIOYSMXEw7pg4W3faIFm5Ynq4nPTArSOUD3vQ2xNoGNh9TDT57BQZDZD',
        'settings' => array(
            'get_started' => array(
                'payload' => 'GET_STARTED',
            ),
            'greeting' => array(
                array(
                    'locale' => 'default',
                    'text' => 'I\'m Mench, An assistant Bot that would help you with:

- Notifications/Reminders
- Managing your Action Plan
- Chat with your Instructor',
                ),
            ),
            'whitelisted_domains' => array(
                'http://local.mench.co',
                'https://mench.co',
            ),
            'persistent_menu' => array(
                array(
                    'locale' => 'default',
                    'composer_input_disabled' => false,
                    'call_to_actions' => array(
                        array(
                            'title' => '🚩 Action Plan',
                            'type' => 'web_url',
                            'url' => 'https://mench.co/my/actionplan',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                        array(
                            'title' => '🏆 Leaderboard',
                            'type' => 'web_url',
                            'url' => 'https://mench.co/my/leaderboard',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                        /*
                        array(
                        'title' => '⚙ My Account',
                        'type' => 'web_url',
                        'url' => 'https://mench.co/my/account',
                        'messenger_extensions' => true,
                        'webview_height_ratio' => 'tall',
                        'webview_share_button' => 'hide',
                        ),
                        /*
                        array(
                        'title' => 'My Dashboard',
                        'type' => 'nested',
                        'call_to_actions' => array(
                        array(
                        'title' => 'Leaderboard',
                        'type' => 'web_url',
                        'url' => 'https://mench.co/my/ledaerboard',
                        'webview_height_ratio' => 'tall',
                        'webview_share_button' => 'hide',
                        ),
                        array(
                        'title' => 'Assignments',
                        'type' => 'web_url',
                        'url' => 'https://mench.co/my/assignments',
                        'webview_height_ratio' => 'tall',
                        'webview_share_button' => 'hide',
                        ),
                        ),
                        ),
                        array(
                        'title' => 'Help & Support',
                        'type' => 'postback',
                        'payload' => 'HISTORY_PAYLOAD',
                        ),
                        */
                    ),
                ),
            ),
        ),
    ),
);

$config['core_objects'] = array(
    'u' => array(
        'o_name' => 'User',
        'o_names' => 'Users',
    ),
    'us' => array(
        'o_name' => 'Task Completion Report',
        'o_names' => 'Task Completion Reports',
    ),
    'b' => array(
        'o_name' => 'Bootcamp',
        'o_names' => 'Bootcamps',
    ),
    'ba' => array(
        'o_name' => 'Bootcamp Instructor',
        'o_names' => 'Bootcamp Instructors',
    ),
    'c' => array(
        'o_name' => 'Intent',
        'o_names' => 'Intent',
        'maxlength' => 70,//Applies to all intents inputs
    ),
    'cr' => array(
        'o_name' => 'Intent Link',
        'o_names' => 'Intent Links',
    ),
    'r' => array(
        'o_name' => 'Class',
        'o_names' => 'Classes',
    ),
    'ru' => array(
        'o_name' => 'Admission',
        'o_names' => 'Admissions',
    ),
    't' => array(
        'o_name' => 'Transaction',
        'o_names' => 'Transactions',
    ),
    'i' => array(
        'o_name' => 'Message',
        'o_names' => 'Messages',
    ),
    'level_0' => array(
        'o_name' => 'Bootcamp',
        'o_names' => 'Bootcamps',
        'o_icon' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i>',
    ),
    'level_1' => array(
        'o_name' => 'Milestone',
        'o_names' => 'Milestones',
        'o_icon' => '<i class="fa fa-flag" aria-hidden="true"></i>',
    ),
    'level_2' => array(
        'o_name' => 'Task',
        'o_names' => 'Tasks',
        'o_icon' => '<i class="fa fa-check-square-o" aria-hidden="true"></i>',
    ),
);

$config['object_statuses'] = array(
    'b' => array(
        -1 => array(
            's_name'  => 'Archived',
            's_desc'  => 'Bootcamp archived by lead instructor',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-trash',
        ),
        0 => array(
            's_name'  => 'Drafting',
            's_desc'  => 'Bootcamp under development. Admissions starts when published live',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-pencil-square',
        ),
        1 => array(
            's_name'  => 'Request To Publish',
            's_desc'  => 'Bootcamp submitted for review by Mench team to be published live',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-eye',
        ),
        2 => array(
            's_name'  => 'Published Privately',
            's_desc'  => 'Students can join only if they know the Landing Page URL',
            'u_min_status'  => 3, //Can only be done by admin
            's_mini_icon' => 'fa-bullhorn',
        ),
        3 => array(
            's_name'  => 'Published to Mench',
            's_desc'  => 'A Bootcamp published on the Mench marketplace',
            'u_min_status'  => 3, //Can only be done by admin
            's_mini_icon' => 'fa-bullhorn',
        ),
    ),
    'c' => array(
        -1 => array(
            's_name'  => 'Delete',
            's_desc'  => 'Item removed',
            'u_min_status'  => 1, //Not possible for now.
            's_mini_icon' => 'fa-trash',
        ),
        0 => array(
            's_name'  => 'Drafting',
            's_desc'  => 'Task being drafted and not accessible by students until published live',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-pencil-square',
        ),
        1 => array(
            's_name'  => 'Published',
            's_desc'  => 'Task is active and accessible by students',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-bullhorn',
        ),
    ),
    'r' => array(
        -3 => array(
            's_name'  => 'Cancelled',
            's_desc'  => 'Class cancelled after it had started, likely for reasons beyond the instructors control',
            'u_min_status'  => 3,
            's_mini_icon' => 'fa-times-circle',
        ),
        -2 => array(
            's_name'  => 'Expired',
            's_desc'  => 'Class start time passed without meeting the minimum student admission requirement',
            'u_min_status'  => 3,
            's_mini_icon' => 'fa-times-circle',
        ),
        -1 => array(
            's_name'  => 'Archived',
            's_desc'  => 'Class archived by instructor before any students getting admitted',
            'u_min_status'  => 2,
            's_mini_icon' => 'fa-trash',
        ),
        0 => array(
            's_name'  => 'Drafting',
            's_desc'  => 'Class under development and not listed on landing page',
            'u_min_status'  => 2,
            's_mini_icon' => 'fa-pencil-square',
        ),
        1 => array(
            's_name'  => 'Admission Open',
            's_desc'  => 'Class published live and is open for student admission',
            'u_min_status'  => 2,
            's_mini_icon' => 'fa-bullhorn',
        ),
        2 => array(
            's_name'  => 'Running',
            's_desc'  => 'Class has admitted students and is currently running',
            'u_min_status'  => 3,
            's_mini_icon' => 'fa-play-circle',
        ),
        3 => array(
            's_name'  => 'Completed',
            's_desc'  => 'Class was operated completely until its last day',
            'u_min_status'  => 3,
            's_mini_icon' => 'fa-graduation-cap',
        ),
    ),
    'i' => array(
        -1 => array(
            's_name'  => 'Delete',
            's_desc'  => 'Message removed.',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-trash',
        ),
        /*
        0 => array(
            's_name'  => 'Drafting',
            's_desc'  => 'Message not visible to students while drafting',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-pencil-square',
        ),
        */
        1 => array(
            's_name'  => 'On Start',
            's_desc'  => 'Messages sent to student when milestone starts',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-bolt',
        ),
        2 => array(
            's_name'  => 'Drip',
            's_desc'  => 'Messages sent to enrolled students sometime during the milestone. Drip messages sent in same order you choose',
            's_mini_icon' => 'fa-tint',
            'u_min_status'  => 1,
        ),
        /*
        3 => array(
            's_name'  => 'Landing Page',
            's_desc'  => 'Messages published on the Landing Page giving prospect students an overview of your Bootcamp',
            's_mini_icon' => 'fa-bullhorn',
            'u_min_status'  => 1,
        ),
        4 => array(
            's_name'  => 'Private Note',
            's_desc'  => 'This Message is taken by the instructor team on a particular student and is visible to the entire team',
            's_mini_icon' => 'fa-eye-slash',
            'u_min_status'  => 1,
        ),
        */
    ),

    'cr' => array(
        -1 => array(
            's_name'  => 'Archived',
            's_desc'  => 'Task link removed',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-trash',
        ),
        1 => array(
            's_name'  => 'Publish',
            's_desc'  => 'Task link is active',
            'u_min_status'  => 1,
        ),
    ),

    //User related statuses:

    'ba' => array(
        -1 => array(
            's_name'  => 'Revoked',
            's_desc'  => 'Bootcamp access revoked',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-minus-circle',
        ),
        1 => array(
            's_name'  => 'Adviser',
            's_desc'  => 'Mench advisory team who extend your resources by reviewing and sharing feedback on ways to improve the bootcamp configurations',
            's_mini_icon' => 'fa-comments-o',
            'u_min_status'  => 3, //For now this is NOT in use, just being hacked into the UI via team.php view file
        ),
        2 => array(
            's_name'  => 'Co-Instructor',
            's_desc'  => 'Supports the lead instructor in bootcamp operations based on specific privileges assigned to them',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-user-plus',
        ),
        3 => array(
            's_name'  => 'Lead Instructor',
            's_desc'  => 'The bootcamp CEO who is responsible for the bootcamp performance measured by its completion rate',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-star',
        ),
    ),

    'u' => array(
        -2 => array(
            's_name'  => 'Merged',
            's_desc'  => 'User merged with another user',
            'u_min_status'  => 3, //Only admins can delete user accounts, or the user for their own account
            's_mini_icon' => 'fa-user-times',
        ),
        -1 => array(
            's_name'  => 'Delete',
            's_desc'  => 'User no longer active',
            'u_min_status'  => 3, //Only admins can delete user accounts, or the user for their own account
            's_mini_icon' => 'fa-user-times',
        ),
        0 => array(
            's_name'  => 'Pending',
            's_desc'  => 'User added by the students but has not yet claimed their account',
            'u_min_status'  => 999, //System only
            's_mini_icon' => 'fa-user-o',
        ),
        1 => array(
            's_name'  => 'Active',
            's_desc'  => 'User active',
            's_mini_icon' => 'fa-user',
            'u_min_status'  => 3, //Only admins can downgrade users from a leader status
        ),
        2 => array(
            's_name'  => 'Lead Instructor',
            's_desc'  => 'User onboarded as bootcamp leader and can create/manage their own bootcamps',
            's_mini_icon' => 'fa-star',
            'u_min_status'  => 3, //Only admins can approve leaders
        ),
        3 => array(
            's_name'  => 'Mench Admin',
            's_desc'  => 'User part of Mench team who facilitates bootcamp operations',
            's_mini_icon' => 'fa-shield',
            'u_min_status'  => 3, //Only admins can create other admins
        ),
    ),

    'us' => array(
        //This is not stored in DB:
        -2 => array(
            's_name'  => 'Locked',
            's_desc'  => 'Pending milestone start',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-lock',
        ),
        -1 => array(
            's_name'  => 'Requires Revision',
            's_desc'  => 'Submission has been reviewed and improvement suggestions are pending implementation',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-exclamation-triangle',
        ),
        0 => array(
            's_name'  => 'Pending Completion',
            's_desc'  => 'Task is pending completion',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-square-o',
        ),
        1 => array(
            's_name'  => 'Marked as Complete',
            's_desc'  => 'Student marked as complete',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-check-square',
        ),
    ),
    //This is not in the DB but it reflects the timing of when students submitted their tasks:
    'us_time' => array(
        //This is not stored in DB:
        0 => array(
            's_name'  => 'Really Late',
            's_desc'  => 'Student completed task much later than expected and did not earn any points',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-thumbs-o-down',
        ),
        1 => array(
            's_name'  => 'A Little Late',
            's_desc'  => 'Student completed task a little later than expected and received half points',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-hand-rock-o',
        ),
        2 => array(
            's_name'  => 'On-Time',
            's_desc'  => 'Student completed task on time and received full points',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-thumbs-o-up',
        ),
    ),

    't' => array(
        -2 => array(
            's_name'  => 'Payout',
            's_desc'  => 'Payment sent to instructors and affiliates',
            'u_min_status'  => 999,
        ),
        -1 => array(
            's_name'  => 'Refund Payment',
            's_desc'  => 'Transaction hold the exact amount of refund issues to the student',
            'u_min_status'  => 999,
        ),
        0 => array(
            's_name'  => 'Payment Refunded',
            's_desc'  => 'Payment has been fully or partially refunded and a new transaction has been added to reflect the exact refund',
            'u_min_status'  => 999,
        ),
        1 => array(
            's_name'  => 'Payment Received',
            's_desc'  => 'Payment received from students for a class admission',
            'u_min_status'  => 999,
        ),
    ),

    'ru' => array(

        //Withrew after course has started:
        -3 => array(
            's_name'  => 'Student Removed',
            's_desc'  => 'Student was removed from class for reasons known to the instructor',
            'u_min_status'  => 2,
            's_mini_icon' => 'fa-times-circle',
        ),
        //Withrew prior to course has started:
        -2 => array(
            's_name'  => 'Student Withdrew',
            's_desc'  => 'Student withdrew from the bootcamp. Refund given based on the class refund policy & withdrawal date',
            'u_min_status'  => 999, //Only done by Student themselves
            's_mini_icon' => 'fa-times-circle',
        ),
        -1 => array(
            's_name'  => 'Application Rejected',
            's_desc'  => 'Application rejected before start date. Incomplete applications will be auto rejected on class start date',
            'u_min_status'  => 1,
            's_mini_icon' => 'fa-times-circle',
        ),
        0 => array(
            's_name'  => 'Application Started',
            's_desc'  => 'Student has started the application process but has not completed it yet',
            'u_min_status'  => 999, //System insertion only
            's_mini_icon' => 'fa-pencil-square',
        ),
        /*
        1 => array(
            's_name'  => 'Applied - Pending Full Payment',
            's_desc'  => 'Student has applied but has not paid in full yet, pending bootcamp leader approval before paying in full',
            'u_min_status'  => 999, //System insertion only
        ),
        */
        2 => array(
            's_name'  => 'Pending Admission',
            's_desc'  => 'Student has completed application and payment (for paid classes) and is pending admission',
            's_mini_icon' => 'fa-pause-circle',
            'u_min_status'  => 999, //System insertion only
        ),

        /*
        3 => array(
            's_name'  => 'Invitation Sent',
            's_desc'  => 'Admins have full access to all bootcamp features',
            'u_min_status'  => 1,
        ),
        */

        4 => array(
            's_name'  => 'Bootcamp Student',
            's_desc'  => 'Student admitted making them ready to participate in bootcamp',
            's_mini_icon' => 'fa-user',
            'u_min_status'  => 1,
        ),

        //Completion
        7 => array(
            's_name'  => 'Bootcamp Graduate',
            's_desc'  => 'Student completed class and completed all milestones as approved by lead instructor',
            's_mini_icon' => 'fa-graduation-cap',
            'u_min_status'  => 1,
        ),
    ),
);


//Used to generate application status links:
$config['application_status_salt'] = 'SALTs3cr3t777';
$config['bot_activation_salt'] = 'S@LTB0Ts3cr3t4';
$config['file_limit_mb'] = 30; //The max file size to be uploaded


//No bootcamps can be created using these hashtags
//URL structure is: https://mench.co/URLKEY
$config['reserved_hashtags'] = array(
    'bootcamps',
    'bootcamp',
    'mench',
    'login',
    'logout',
    'user',
    'users',
    'account',
    'accounts',
    'profile',
    'profiles',
    'terms',
    'start',
    'launch',
    'contact',
    'contactus',
    'faq',
    'ses',
    'application_status',
    'application',
    'apply',
    'ref',
    'console',
    'help',
    'hashtag',
    'instructor',
    'instructors',
    'student',
    'students',
);

//The core objects of the platform:

$config['engagement_references'] = array(
    'e_initiator_u_id' => array(
        'name' => 'Initiator',
        'object_code' => 'u',
    ),
    'e_recipient_u_id' => array(
        'name' => 'Recipient',
        'object_code' => 'u',
    ),
    'e_b_id' => array(
        'name' => 'Bootcamp',
        'object_code' => 'b',
    ),
    'e_r_id' => array(
        'name' => 'Class',
        'object_code' => 'r',
    ),
    'e_c_id' => array(
        'name' => 'Intent',
        'object_code' => 'c',
    ),
    'e_cr_id' => array(
        'name' => 'Intent Link',
        'object_code' => 'cr',
    ),
    'e_i_id' => array(
        'name' => 'Message',
        'object_code' => 'i',
    ),
    'e_t_id' => array(
        'name' => 'Transaction',
        'object_code' => 't',
    ),
);

//The engagements that need to be communicated to instructors:
$config['instructor_subscriptions'] = array(30,52,56,57,60,61,66,67,68);
$config['mench_advisers'] = array(1,2); //Miguel and Shervin @ This Time //TODO Merge with variable below
$config['mench_cs_fb_ids'] = array(
    array(
        'u_fname' => 'Shervin',
        'u_lname' => 'Enayati',
        'u_id' => 1,
        'u_fb_id' => '1443101719058431',
    ),
    array(
        'u_fname' => 'Miguel',
        'u_lname' => 'Hernandez',
        'u_id' => 2,
        'u_fb_id' => '1234880879950857',
    )
);

//User specific engagement subscriptions:
$config['engagement_subscriptions'] = array(
    array(
        'admin_emails' => array('miguel@mench.co'),
        'subscription' => array(9,15,37,56,57,58,60,61,63,64,65,66,67,68),
    ),
    array(
        'admin_emails' => array('shervin@mench.co'),
        'subscription' => array(8,9,15,37,56,57,58,60,61,63,64,65,66,67,68),
    ),
);


//based on the fibonacci sequence for more realistic estimates
$config['c_time_options'] = array('0.05','0.1166667','0.25','0.5','1','2','3','5','8','13');

//That is auto added to all bootcamp teams as Adviser role:
$config['message_max'] = 420; //Max number of characters allowed in messages


$config['refund_policies'] = array(
    'flexible' => array(
        'full' => 0.10,
        'prorated' => 0.60,
    ),
    'moderate' => array(
        'full' => 0,
        'prorated' => 0.30,
    ),
    'strict' => array(
        'full' => 0,
        'prorated' => 0,
    ),
);


$config['sprint_units'] = array(
    'day' => array(
        'name' => 'Daily',
        'desc' => 'Usually 3-30 days',
    ),
    'week' => array(
        'name' => 'Weekly',
        'desc' => 'Usually 2-14 weeks',
    ),
);

//Learn more: https://console.aws.amazon.com/iam/home?region=us-west-2#/users/foundation?section=security_credentials
$config['aws_credentials'] = [
    'key'    => 'AKIAJOLBLKFSYCCYYDRA',
    'secret' => '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',
];


$config['default_class_questions'] = array(
    'Why did you choose to join this bootcamp?',
    'etc... (Delete this)',
);
$config['default_class_prizes'] = array(
    'A branded T-Shirt',
    '1 Year unlimited access to my online courses',
    '30-Minutes consultation session to explore your next best move',
    'etc... (Delete this)',
);
$config['default_class_prerequisites'] = array(
    'An internet-connected computer',
    'Fluent in English',
    'Facebook Messenger (Facebook Account Not Required)',
    'etc... (Delete this)',
);


$config['start_times'] = array(
    0 => 'Midnight',
    30 => '12:30am',
    60 => '1am',
    90 => '1:30am',
    120 => '2am',
    150 => '2:30am',
    180 => '3am',
    210 => '3:30am',
    240 => '4am',
    270 => '4:30am',
    300 => '5am',
    330 => '5:30am',
    360 => '6am',
    390 => '6:30am',
    420 => '7am',
    450 => '7:30am',
    480 => '8am',
    510 => '8:30am',
    540 => '9am',
    570 => '9:30am',
    600 => '10am',
    630 => '10:30am',
    660 => '11am',
    690 => '11:30am',
    720 => 'Noon',
    750 => '12:30pm',
    780 => '1pm',
    810 => '1:30pm',
    840 => '2pm',
    870 => '2:30pm',
    900 => '3pm',
    930 => '3:30pm',
    960 => '4pm',
    990 => '4:30pm',
    1020 => '5pm',
    1050 => '5:30pm',
    1080 => '6pm',
    1110 => '6:30pm',
    1140 => '7pm',
    1170 => '7:30pm',
    1200 => '8pm',
    1230 => '8:30pm',
    1260 => '9pm',
    1290 => '9:30pm',
    1320 => '10pm',
    1350 => '10:30pm',
    1380 => '11pm',
    1410 => '11:30pm',
);


//NOTE: If changed, also make corresponding changes to update_account() function in global.js file
$config['u_social_account'] = array(
    'u_fb_username' => array(
        'sa_name' => 'Facebook',
        'sa_prefix' => 'https://www.facebook.com/',
        'sa_postfix' => '',
        'sa_icon' => '<i class="fa fa-facebook-official" aria-hidden="true"></i>',
    ),
    'u_instagram_username' => array(
        'sa_name' => 'Instagram',
        'sa_prefix' => 'https://www.instagram.com/',
        'sa_postfix' => '/',
        'sa_icon' => '<i class="fa fa-instagram" aria-hidden="true"></i>',
    ),
    'u_twitter_username' => array(
        'sa_name' => 'Twitter',
        'sa_prefix' => 'https://twitter.com/',
        'sa_postfix' => '',
        'sa_icon' => '<i class="fa fa-twitter" aria-hidden="true"></i>',
    ),
    'u_youtube_username' => array(
        'sa_name' => 'YouTube',
        'sa_prefix' => 'https://www.youtube.com/',
        'sa_postfix' => '',
        'sa_icon' => '<i class="fa fa-youtube-play" aria-hidden="true"></i>',
    ),
    'u_linkedin_username' => array(
        'sa_name' => 'Linkedin',
        'sa_prefix' => 'https://www.linkedin.com/in/',
        'sa_postfix' => '/',
        'sa_icon' => '<i class="fa fa-linkedin-square" aria-hidden="true"></i>',
    ),
    'u_github_username' => array(
        'sa_name' => 'Github',
        'sa_prefix' => 'https://github.com/',
        'sa_postfix' => '',
        'sa_icon' => '<i class="fa fa-github" aria-hidden="true"></i>',
    ),
    'u_calendly_username' => array(
        'sa_name' => 'Calendly',
        'sa_prefix' => 'https://calendly.com/',
        'sa_postfix' => '',
        'sa_icon' => '<i class="fa fa-calendar-check-o" aria-hidden="true"></i>',
    ),
);


$config['timezones'] = array(
    '-11'       => "(GMT-11:00) Midway Island, Samoa",
    '-10'       => "(GMT-10:00) Hawaii",
    '-9'        => "(GMT-09:00) Alaska",
    '-8'        => "(GMT-08:00) Pacific Standard Time, Tijuana",
    '-7'        => "(GMT-07:00) Arizona, Mountain Time, Chihuahua",
    '-6'        => "(GMT-06:00) Central Time, Mexico City, etc...",
    '-5'        => "(GMT-05:00) Eastern Time, Indiana, Bogota, Lima",
    '-4.5'      => "(GMT-04:30) Caracas",
    '-4'        => "(GMT-04:00) Atlantic Time, La Paz, Santiago",
    '-3.5'      => "(GMT-03:30) Newfoundland",
    '-3'        => "(GMT-03:00) Buenos Aires, Greenland",
    '-2'        => "(GMT-02:00) Stanley",
    '-1'        => "(GMT-01:00) Azores, Cape Verde Is.",
    '0'         => "(GMT 0:00) London, Dublin, Lisbon, Casablanca",
    '1'         => "(GMT+01:00) Amsterdam, Berlin, Paris, etc...",
    '2'         => "(GMT+02:00) Athens, Istanbul, Jerusalem, etc...",
    '3'         => "(GMT+03:00) Moscow, Baghdad, Kuwait, Riyadh",
    '3.5'       => "(GMT+03:30) Tehran",
    '4'         => "(GMT+04:00) Baku, Volgograd, Muscat, etc...",
    '4.5'       => "(GMT+04:30) Kabul",
    '5'         => "(GMT+05:00) Karachi, Tashkent, Kolkata, etc...",
    '6'         => "(GMT+06:00) Ekaterinburg, Almaty, Dhaka",
    '7'         => "(GMT+07:00) Novosibirsk, Bangkok, Jakarta",
    '8'         => "(GMT+08:00) Hong Kong, Perth, Singapore, etc...",
    '9'         => "(GMT+09:00) Irkutsk, Seoul, Tokyo",
    '9.5'       => "(GMT+09:30) Adelaide, Darwin",
    '10'        => "(GMT+10:00) Melbourne, Sydney, Guam, etc...",
    '11'        => "(GMT+11:00) Vladivostok",
    '12'        => "(GMT+12:00) Magadan, Auckland, Fiji",
);

$config['languages'] = array(
    'en' => 'English' ,
    
    'ar' => 'Arabic' ,
    'aa' => 'Afar' ,
    'ab' => 'Abkhazian' ,
    'af' => 'Afrikaans' ,
    'am' => 'Amharic' ,
    'as' => 'Assamese' ,
    'ay' => 'Aymara' ,
    'az' => 'Azerbaijani' ,
    'sq' => 'Albanian' ,
    'hy' => 'Armenian' ,
    
    'ba' => 'Bashkir' ,
    'be' => 'Byelorussian' ,
    'bg' => 'Bulgarian' ,
    'bh' => 'Bihari' ,
    'bi' => 'Bislama' ,
    'bn' => 'Bengali' ,
    'br' => 'Breton' ,
    'dz' => 'Bhutani' ,
    'eu' => 'Basque' ,
    'my' => 'Burmese' ,
    
    'zh' => 'Chinese' ,
    'ca' => 'Catalan' ,
    'co' => 'Corsican' ,
    'cs' => 'Czech' ,
    'hr' => 'Croatian' ,
    'km' => 'Cambodian' ,
    
    'da' => 'Danish' ,
    'nl' => 'Dutch' ,
    
    'eo' => 'Esperanto' ,
    'et' => 'Estonian' ,
    
    'fa' => 'Farsi' ,
    'fi' => 'Finnish' ,
    'fj' => 'Fiji' ,
    'fo' => 'Faeroese' ,
    'fr' => 'French' ,
    'fy' => 'Frisian' ,
    
    'de' => 'German' ,
    'el' => 'Greek' ,
    'gl' => 'Galician' ,
    'gn' => 'Guarani' ,
    'gu' => 'Gujarati' ,
    'ka' => 'Georgian' ,
    'kl' => 'Greenlandic' ,
    
    'ha' => 'Hausa' ,
    'hi' => 'Hindi' ,
    'hu' => 'Hungarian' ,
    'iw' => 'Hebrew' ,
    
    'it' => 'Italian' ,
    'ga' => 'Irish' ,
    'ia' => 'Interlingua' ,
    'ie' => 'Interlingue' ,
    'ik' => 'Inupiak' ,
    'in' => 'Indonesian' ,
    'is' => 'Icelandic' ,
    
    'ja' => 'Japanese' ,
    'jw' => 'Javanese' ,
    
    'kk' => 'Kazakh' ,
    'rn' => 'Kirundi' ,
    'kn' => 'Kannada' ,
    'ko' => 'Korean' ,
    'ks' => 'Kashmiri' ,
    'ku' => 'Kurdish' ,
    'ky' => 'Kirghiz' ,
    'rw' => 'Kinyarwanda' ,
    
    'la' => 'Latin' ,
    'ln' => 'Lingala' ,
    'lo' => 'Laothian' ,
    'lt' => 'Lithuanian' ,
    'lv' => 'Latvian' ,
    
    'mg' => 'Malagasy' ,
    'mi' => 'Maori' ,
    'mk' => 'Macedonian' ,
    'ml' => 'Malayalam' ,
    'mn' => 'Mongolian' ,
    'mo' => 'Moldavian' ,
    'mr' => 'Marathi' ,
    'ms' => 'Malay' ,
    'mt' => 'Maltese' ,
    
    'na' => 'Nauru' ,
    'ne' => 'Nepali' ,
    'no' => 'Norwegian' ,
    
    'oc' => 'Occitan' ,
    'om' => 'Oromoor' ,
    
    'pa' => 'Punjabi' ,
    'pl' => 'Polish' ,
    'ps' => 'Pashto' ,
    'pt' => 'Portuguese' ,
    
    'qu' => 'Quechua' ,
    
    'rm' => 'Rhaeto-Romance' ,
    'ro' => 'Romanian' ,
    'ru' => 'Russian' ,
    
    'es' => 'Spanish' ,
    'gd' => 'Scots' ,
    'sa' => 'Sanskrit' ,
    'sd' => 'Sindhi' ,
    'sg' => 'Sangro' ,
    'sh' => 'Serbo-Croatian' ,
    'si' => 'Singhalese' ,
    'sk' => 'Slovak' ,
    'sl' => 'Slovenian' ,
    'sm' => 'Samoan' ,
    'sn' => 'Shona' ,
    'so' => 'Somali' ,
    'sr' => 'Serbian' ,
    'ss' => 'Siswati' ,
    'st' => 'Sesotho' ,
    'su' => 'Sundanese' ,
    'sv' => 'Swedish' ,
    'sw' => 'Swahili' ,
    'tn' => 'Setswana' ,
    
    'bo' => 'Tibetan' ,
    'ta' => 'Tamil' ,
    'te' => 'Tegulu' ,
    'tg' => 'Tajik' ,
    'th' => 'Thai' ,
    'ti' => 'Tigrinya' ,
    'tk' => 'Turkmen' ,
    'tl' => 'Tagalog' ,
    'to' => 'Tonga' ,
    'tr' => 'Turkish' ,
    'ts' => 'Tsonga' ,
    'tt' => 'Tatar' ,
    'tw' => 'Twi' ,
    
    'uk' => 'Ukrainian' ,
    'ur' => 'Urdu' ,
    'uz' => 'Uzbek' ,
    
    'vi' => 'Vietnamese' ,
    'vo' => 'Volapuk' ,
    
    'cy' => 'Welsh' ,
    'wo' => 'Wolof' ,
    
    'xh' => 'Xhosa' ,
    
    'yo' => 'Yoruba' ,
    'ji' => 'Yiddish' ,
    
    'zu' => 'Zulu' ,
);

$config['countries_all'] = array(
    "AF" => "Afghanistan",
    "AX" => "Aland Islands",
    "AL" => "Albania",
    "DZ" => "Algeria",
    "AS" => "American Samoa",
    "AD" => "Andorra",
    "AO" => "Angola",
    "AI" => "Anguilla",
    "AQ" => "Antarctica",
    "AG" => "Antigua and Barbuda",
    "AR" => "Argentina",
    "AM" => "Armenia",
    "AW" => "Aruba",
    "AU" => "Australia",
    "AT" => "Austria",
    "AZ" => "Azerbaijan",
    "BS" => "Bahamas",
    "BH" => "Bahrain",
    "BD" => "Bangladesh",
    "BB" => "Barbados",
    "BY" => "Belarus",
    "BE" => "Belgium",
    "BZ" => "Belize",
    "BJ" => "Benin",
    "BM" => "Bermuda",
    "BT" => "Bhutan",
    "BO" => "Bolivia",
    "BA" => "Bosnia and Herzegovina",
    "BW" => "Botswana",
    "BV" => "Bouvet Island",
    "BR" => "Brazil",
    "IO" => "British Indian Ocean",
    "BN" => "Brunei Darussalam",
    "BG" => "Bulgaria",
    "BF" => "Burkina Faso",
    "BI" => "Burundi",
    "KH" => "Cambodia",
    "CM" => "Cameroon",
    "CA" => "Canada",
    "CV" => "Cape Verde",
    "KY" => "Cayman Islands",
    "CF" => "Central African Republic",
    "TD" => "Chad",
    "CL" => "Chile",
    "CN" => "China",
    "CX" => "Christmas Island",
    "CC" => "Cocos (Keeling) Islands",
    "CO" => "Colombia",
    "KM" => "Comoros",
    "CG" => "Congo",
    "CD" => "Congo",
    "CK" => "Cook Islands",
    "CR" => "Costa Rica",
    "CI" => "Cote D'ivoire",
    "HR" => "Croatia",
    "CU" => "Cuba",
    "CY" => "Cyprus",
    "CZ" => "Czech Republic",
    "DK" => "Denmark",
    "DJ" => "Djibouti",
    "DM" => "Dominica",
    "DO" => "Dominican Republic",
    "EC" => "Ecuador",
    "EG" => "Egypt",
    "SV" => "El Salvador",
    "GQ" => "Equatorial Guinea",
    "ER" => "Eritrea",
    "EE" => "Estonia",
    "ET" => "Ethiopia",
    "FK" => "Falkland Islands",
    "FO" => "Faroe Islands",
    "FJ" => "Fiji",
    "FI" => "Finland",
    "FR" => "France",
    "GF" => "French Guiana",
    "PF" => "French Polynesia",
    "TF" => "French S. Territories",
    "GA" => "Gabon",
    "GM" => "Gambia",
    "GE" => "Georgia",
    "DE" => "Germany",
    "GH" => "Ghana",
    "GI" => "Gibraltar",
    "GR" => "Greece",
    "GL" => "Greenland",
    "GD" => "Grenada",
    "GP" => "Guadeloupe",
    "GU" => "Guam",
    "GT" => "Guatemala",
    "GG" => "Guernsey",
    "GN" => "Guinea",
    "GW" => "Guinea-bissau",
    "GY" => "Guyana",
    "HT" => "Haiti",
    "HM" => "Heard & Mcdonald Island",
    "VA" => "Vatican City State",
    "HN" => "Honduras",
    "HK" => "Hong Kong",
    "HU" => "Hungary",
    "IS" => "Iceland",
    "IN" => "India",
    "ID" => "Indonesia",
    "IR" => "Iran",
    "IQ" => "Iraq",
    "IE" => "Ireland",
    "IM" => "Isle of Man",
    "IL" => "Israel",
    "IT" => "Italy",
    "JM" => "Jamaica",
    "JP" => "Japan",
    "JE" => "Jersey",
    "JO" => "Jordan",
    "KZ" => "Kazakhstan",
    "KE" => "Kenya",
    "KI" => "Kiribati",
    "KP" => "Korea North",
    "KR" => "Korea South",
    "KW" => "Kuwait",
    "KG" => "Kyrgyzstan",
    "LA" => "Lao",
    "LV" => "Latvia",
    "LB" => "Lebanon",
    "LS" => "Lesotho",
    "LR" => "Liberia",
    "LY" => "Libyan Arab Jamahiriya",
    "LI" => "Liechtenstein",
    "LT" => "Lithuania",
    "LU" => "Luxembourg",
    "MO" => "Macao",
    "MK" => "Macedonia",
    "MG" => "Madagascar",
    "MW" => "Malawi",
    "MY" => "Malaysia",
    "MV" => "Maldives",
    "ML" => "Mali",
    "MT" => "Malta",
    "MH" => "Marshall Islands",
    "MQ" => "Martinique",
    "MR" => "Mauritania",
    "MU" => "Mauritius",
    "YT" => "Mayotte",
    "MX" => "Mexico",
    "FM" => "Micronesia",
    "MD" => "Moldova",
    "MC" => "Monaco",
    "MN" => "Mongolia",
    "ME" => "Montenegro",
    "MS" => "Montserrat",
    "MA" => "Morocco",
    "MZ" => "Mozambique",
    "MM" => "Myanmar",
    "NA" => "Namibia",
    "NR" => "Nauru",
    "NP" => "Nepal",
    "NL" => "Netherlands",
    "NC" => "New Caledonia",
    "NZ" => "New Zealand",
    "NI" => "Nicaragua",
    "NE" => "Niger",
    "NG" => "Nigeria",
    "NU" => "Niue",
    "NF" => "Norfolk Island",
    "MP" => "Northern Mariana Islands",
    "NO" => "Norway",
    "OM" => "Oman",
    "PK" => "Pakistan",
    "PW" => "Palau",
    "PS" => "Palestinian Territory",
    "PA" => "Panama",
    "PG" => "Papua New Guinea",
    "PY" => "Paraguay",
    "PE" => "Peru",
    "PH" => "Philippines",
    "PN" => "Pitcairn",
    "PL" => "Poland",
    "PT" => "Portugal",
    "PR" => "Puerto Rico",
    "QA" => "Qatar",
    "RE" => "Reunion",
    "RO" => "Romania",
    "RU" => "Russian Federation",
    "RW" => "Rwanda",
    "SH" => "Saint Helena",
    "KN" => "Saint Kitts and Nevis",
    "LC" => "Saint Lucia",
    "PM" => "Saint Pierre",
    "VC" => "Saint Vincent",
    "WS" => "Samoa",
    "SM" => "San Marino",
    "ST" => "Sao Tome and Principe",
    "SA" => "Saudi Arabia",
    "SN" => "Senegal",
    "RS" => "Serbia",
    "SC" => "Seychelles",
    "SL" => "Sierra Leone",
    "SG" => "Singapore",
    "SK" => "Slovakia",
    "SI" => "Slovenia",
    "SB" => "Solomon Islands",
    "SO" => "Somalia",
    "ZA" => "South Africa",
    "GS" => "South Georgia",
    "ES" => "Spain",
    "LK" => "Sri Lanka",
    "SD" => "Sudan",
    "SR" => "Suriname",
    "SJ" => "Svalbard and Jan Mayen",
    "SZ" => "Swaziland",
    "SE" => "Sweden",
    "CH" => "Switzerland",
    "SY" => "Syrian Arab Republic",
    "TW" => "Taiwan",
    "TJ" => "Tajikistan",
    "TZ" => "Tanzania",
    "TH" => "Thailand",
    "TL" => "Timor-leste",
    "TG" => "Togo",
    "TK" => "Tokelau",
    "TO" => "Tonga",
    "TT" => "Trinidad and Tobago",
    "TN" => "Tunisia",
    "TR" => "Turkey",
    "TM" => "Turkmenistan",
    "TC" => "Turks/Caicos Islands",
    "TV" => "Tuvalu",
    "UG" => "Uganda",
    "UA" => "Ukraine",
    "AE" => "United Arab Emirates",
    "GB" => "United Kingdom",
    "US" => "United States",
    "UY" => "Uruguay",
    "UZ" => "Uzbekistan",
    "VU" => "Vanuatu",
    "VE" => "Venezuela",
    "VN" => "Viet Nam",
    "VG" => "Virgin Islands, British",
    "VI" => "Virgin Islands, U.S.",
    "WF" => "Wallis and Futuna",
    "EH" => "Western Sahara",
    "YE" => "Yemen",
    "ZM" => "Zambia",
    "ZW" => "Zimbabwe"
);

$config['r_response_options'] = array('6','12','24','48');

//IF Changed, Also adjust echo_mentorship() & gross_mentorship() functions in general_helper()
$config['r_meeting_frequency'] = array(
    "0" => "None",
    "d1" => "1 Per Day",
    "w1" => "1 Per Week",
    "w2" => "2 Per Week",
    "w3" => "3 Per Week",
    "w5" => "5 Per Week",
    "1" => "1 Session Total",
    "2" => "2 Sessions Total",
    "3" => "3 Sessions Total",
    "4" => "4 Sessions Total",
    "6" => "6 Sessions Total",
    "12" => "12 Sessions Total",
    "18" => "18 Sessions Total",
    "24" => "24 Sessions Total",
);
$config['r_meeting_duration'] = array('0.25','0.5','1','2','3','5');



/*
 |--------------------------------------------------------------------------
 | Base Site URL
 |--------------------------------------------------------------------------
 |
 | URL to your CodeIgniter root. Typically this will be your base URL,
 | WITH a trailing slash:
 |
 |	http://example.com/
 |
 | WARNING: You MUST set this value!
 |
 | If it is not set, then CodeIgniter will try guess the protocol and path
 | your installation, but due to security concerns the hostname will be set
 | to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
 | The auto-detection mechanism exists only for convenience during
 | development and MUST NOT be used in production!
 |
 | If you need to allow multiple domains, remember that this file is still
 | a PHP script and you can easily do that on your own.
 |
 */
$config['base_url'] = '';

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = 'index.php';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'REQUEST_URI' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'REQUEST_URI'    Uses $_SERVER['REQUEST_URI']
| 'QUERY_STRING'   Uses $_SERVER['QUERY_STRING']
| 'PATH_INFO'      Uses $_SERVER['PATH_INFO']
|
| WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
*/
$config['uri_protocol']	= 'REQUEST_URI';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/urls.html
*/
$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
| See http://php.net/htmlspecialchars for a list of supported charsets.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = FALSE;

/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/core_classes.html
| https://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
|
| Enabling this setting will tell CodeIgniter to look for a Composer
| package auto-loader script in application/vendor/autoload.php.
|
|	$config['composer_autoload'] = TRUE;
|
| Or if you have your vendor/ directory located somewhere else, you
| can opt to set a specific path as well:
|
|	$config['composer_autoload'] = '/path/to/vendor/autoload.php';
|
| For more information about Composer, please visit http://getcomposer.org/
|
| Note: This will NOT disable or override the CodeIgniter-specific
|	autoloading (application/config/autoload.php)
*/
$config['composer_autoload'] = FALSE;

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted within your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| The configured value is actually a regular expression character group
| and it will be executed as: ! preg_match('/^[<permitted_uri_chars>]+$/i
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| You can also pass an array with threshold levels to show individual error types
|
| 	array(2) = Debug Messages, without Error Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 1;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ directory. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Log File Extension
|--------------------------------------------------------------------------
|
| The default filename extension for log files. The default 'php' allows for
| protecting the log files via basic scripting, when they are to be stored
| under a publicly accessible directory.
|
| Note: Leaving it blank will default to 'php'.
|
*/
$config['log_file_extension'] = '';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/
$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
$config['error_views_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/cache/ directory.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Whether to take the URL query string into consideration when generating
| output cache files. Valid options are:
|
|	FALSE      = Disabled
|	TRUE       = Enabled, take all query parameters into account.
|	             Please be aware that this may result in numerous cache
|	             files generated for the same page over and over again.
|	array('q') = Enabled, but only take into account the specified list
|	             of query parameters.
|
*/
$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info.
|
| https://codeigniter.com/user_guide/libraries/encryption.html
|
*/
$config['encryption_key'] = '';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_driver'
|
|	The storage driver to use: files, database, redis, memcached
|
| 'sess_cookie_name'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| 'sess_expiration'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| 'sess_save_path'
|
|	The location to save sessions to, driver dependent.
|
|	For the 'files' driver, it's a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the 'database' driver, it's a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| 'sess_match_ip'
|
|	Whether to match the user's IP address when reading the session data.
|
|	WARNING: If you're using the database driver, don't forget to update
|	         your session table's PRIMARY KEY when changing this setting.
|
| 'sess_time_to_update'
|
|	How many seconds between CI regenerating the session ID.
|
| 'sess_regenerate_destroy'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix'   = Set a cookie name prefix if you need to avoid collisions
| 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
| 'cookie_path'     = Typically will be a forward slash
| 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
| 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
|
| Note: These settings (with the exception of 'cookie_prefix' and
|       'cookie_httponly') will also affect sessions.
|
*/
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;

/*
|--------------------------------------------------------------------------
| Standardize newlines
|--------------------------------------------------------------------------
|
| Determines whether to standardize newline characters in input data,
| meaning to replace \r\n, \r, \n occurrences with the PHP_EOL value.
|
| This is particularly useful for portability between UNIX-based OSes,
| (usually \n) and Windows (\r\n).
|
*/
$config['standardize_newlines'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
| 'csrf_regenerate' = Regenerate token on every submission
| 'csrf_exclude_uris' = Array of URIs which ignore CSRF checks
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| Only used if zlib.output_compression is turned off in your php.ini.
| Please do not use it together with httpd-level output compression.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or any PHP supported timezone. This preference tells
| the system whether to use your server's local time as the master 'now'
| reference, or convert it to the configured one timezone. See the 'date
| helper' page of the user guide for information regarding date handling.
|
*/
$config['time_reference'] = 'local';

/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
| Note: You need to have eval() enabled for this to work.
|
*/
$config['rewrite_short_tags'] = FALSE;

/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy
| IP addresses from which CodeIgniter should trust headers such as
| HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
| the visitor's IP address.
|
| You can use both an array or a comma-separated list of proxy addresses,
| as well as specifying whole subnets. Here are a few examples:
|
| Comma-separated:	'10.0.1.200,192.168.5.0/24'
| Array:		array('10.0.1.200', '192.168.5.0/24')
*/
$config['proxy_ips'] = '';
