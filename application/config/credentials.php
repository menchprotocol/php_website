<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *
 * Separate file that will not be stored on GitHub as we have a public repo
 *
 */

$config['password_salt']            = '40s96As9ZkdAcwQ9PhZm'; //Used for hashing the user password for Mench logins

$config['fb_settings']              = array(
    'page_id' => '381488558920384',
    'app_id' => '1782431902047009',
    'client_secret' => '05aea76d11b062951b40a5bee4251620',
    'default_graph_version' => 'v2.10', // ATT: Also exists in Facebook Library! Search "v2.10"
    'mench_access_token' => 'EAAZAVHMRbmyEBAEfN8zsRJ3UOIUJJrNLqeFutPXVQZCoDZA3EO1rgkkzayMtNhisHHEhAos08AmKZCYD7zcZAPIDSMTcBjZAHxxWzbfWyTyp85Fna2bGDfv5JUIBuFTSeQOZBaDHRG7k0kbW8E7kQQN3W6x47VB1dZBPJAU1oNSW1QZDZD',
);

//Learn more: https://console.aws.amazon.com/iam/home?region=us-west-2#/users/foundation?section=security_credentials
$config['aws_credentials']          = [
    'key' => 'AKIAJOLBLKFSYCCYYDRA',
    'secret' => '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',
];
