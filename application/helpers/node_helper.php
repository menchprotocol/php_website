<?php

/*
 * This helper would have node specific functions,
 * where the {value} is curated via any PHP function
 * before displayed to the user.
 * 
 * This makes powerful integrations possible, while
 * managing micro snippets of code.
 * 
 * */


function formatPhoneNumber($phoneNumber) {
	//http://us.foundation/25
	$phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);
	
	if(strlen($phoneNumber) > 10) {
		$countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
		$areaCode = substr($phoneNumber, -10, 3);
		$nextThree = substr($phoneNumber, -7, 3);
		$lastFour = substr($phoneNumber, -4, 4);
		
		$phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
	}
	else if(strlen($phoneNumber) == 10) {
		$areaCode = substr($phoneNumber, 0, 3);
		$nextThree = substr($phoneNumber, 3, 3);
		$lastFour = substr($phoneNumber, 6, 4);
		
		$phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
	}
	else if(strlen($phoneNumber) == 7) {
		$nextThree = substr($phoneNumber, 0, 3);
		$lastFour = substr($phoneNumber, 3, 4);
		
		$phoneNumber = $nextThree.'-'.$lastFour;
	}
	
	return $phoneNumber;
}


function formatBirthday($yyyymmdd){
	//http://us.foundation/27
	return 'Happy Birthday! '.$yyyymmdd;
}