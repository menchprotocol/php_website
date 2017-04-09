<?php
/*
 * This helper file is responsible to standardize
 * URL inputs for new sources to make their dusplicate
 * matching possible.
 * 
 * For example, This URL:
 * 
 * https://www.amazon.ca/dp/B004GKMPSA/ref=dp-kindle-redirect?_encoding=UTF8&btkr=1
 * 
 * Would be cleaned to this one:
 * 
 * https://www.amazon.com/dp/B004GKMPSA
 * 
 * And this way we can prevent duplicate entries and 
 * keep a clean record of all incoming sources.
 * 
 * */


function clean_url($url){
	
	if(str_count($url,'www.amazon.')==1 && str_count($url,'/dp/')){
		//This is an amazon Book URL:
		$temp = explode($url,'/dp/',2);
		$temp = explode($temp[1],'/',2);
		return 'https://www.amazon.com/dp/'.$temp[0];
	} else {
		//Did not match a specific website, return original URL:
		return $url;
	}
}

function fetch_metadata($url){
	//Fetch title, authors and publish date from URL based on meta data in <head>
	
}