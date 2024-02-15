<?php

defined('BASEPATH') or exit('No direct script access allowed');

$route['translate_uri_dashes'] = FALSE;
$route['default_controller'] = "view/index"; //Redirects to default app
$route['404_override'] = 'view/app_load'; //Page not found

$route['SignApp'] = "view/app_load/42816";
$route['uploader'] = "view/app_load/42363";
$route['InteractionType1'] = "view/app_load/4593";
$route['EventReminder'] = "view/app_load/42216";
$route['Routes'] = "view/app_load/42006";
$route['ServerTime'] = "view/app_load/41286";
$route['Contacts'] = "view/app_load/40947";
$route['TreeLister'] = "view/app_load/40355";
$route['EmailImporter1'] = "view/app_load/35983";
$route['Gameplay1'] = "view/app_load/33292";
$route['FlattenTree1'] = "view/app_load/13900";
$route['Apps1'] = "view/app_load/6287";
$route['Sourc92'] = "view/app_load/32103";
$route['Templates1'] = "view/app_load/31077";
$route['CheckinApp1'] = "view/app_load/31076";
$route['TermsofUse1'] = "view/app_load/14373";
$route['Ticket1'] = "view/app_load/26560";
$route['Sour214'] = "view/app_load/26611";
$route['YourResponse1'] = "view/app_load/13980";
$route['TimeLimit1'] = "view/app_load/28199";
$route['SnoozingSubscriber1'] = "view/app_load/28917";
$route['NotificationLevel1'] = "view/app_load/28904";
$route['MultiApply1'] = "view/app_load/27196";
$route['ReceivedSMS1'] = "view/app_load/27901";
$route['LoginAs1'] = "view/app_load/27238";
$route['PayPalApp1'] = "view/app_load/27004";
$route['paypalpayment'] = "view/app_load/26595";
$route['Messenger2'] = "view/app_load/26582";
$route['GuestLogin1'] = "view/app_load/14938";
$route['CacheAppSeconds1'] = "view/app_load/14599";
$route['CleanupDeletedIdeas1'] = "view/app_load/14573";
$route['HomePage1'] = "view/app_load/14565";
$route['SocialLoginCallback1'] = "view/app_load/14564";
$route['PageNotFound1'] = "view/app_load/14563";
$route['Ledger1'] = "view/app_load/4341";
$route['SocialLogin1'] = "view/app_load/14436";
$route['Communities1'] = "view/app_load/13207";
$route['AccountSetup1'] = "view/app_load/14517";
$route['logout'] = "view/app_load/7291";
$route['login'] = "view/app_load/4269";
$route['SpreadSheet1'] = "view/app_load/13790";
$route['MonthlyTokens1'] = "view/app_load/13602";
$route['InteractionID1'] = "view/app_load/4367";
$route['CronJobs1'] = "view/app_load/7274";
$route['SyncGephi1'] = "view/app_load/7278";
$route['SyncSearchIndex1'] = "view/app_load/7279";
$route['Reports1'] = "view/app_load/12114";
$route['Weights1'] = "view/app_load/12569";
$route['ORIdeas1'] = "view/app_load/7712";
$route['SourceRandomAvatars1'] = "view/app_load/12738";
$route['DiscoveryTreeInfo1'] = "view/app_load/12733";
$route['SourceIdeaSyncPrivacy1'] = "view/app_load/12732";
$route['SourceSearchReplace1'] = "view/app_load/12730";
$route['InteractionMetadataView1'] = "view/app_load/12722";
$route['Memory'] = "view/app_load/4527";
$route['PhpInfo1'] = "view/app_load/12709";
$route['IdeaOrphaned1'] = "view/app_load/7260";
$route['IdeaDuplicates1'] = "view/app_load/7261";
$route['IconSearch1'] = "view/app_load/7267";
$route['SourceDuplicates1'] = "view/app_load/7268";
$route['SourceOrphaned1'] = "view/app_load/7269";


$route['@([a-zA-Z0-9]+)'] = "view/e_layout/$1"; //Source
$route['~([a-zA-Z0-9]+)'] = "view/i_layout/$1"; //Ideate
$route['([a-zA-Z0-9]+)/start'] = "view/x_layout/0/$1"; //Discovery Sequence
$route['([a-zA-Z0-9]+)/([a-zA-Z0-9]+)'] = "view/x_layout/$1/$2"; //Discovery Sequence
$route['([a-zA-Z0-9]+)'] = "view/x_layout/0/$1/0"; //Discovery Single
