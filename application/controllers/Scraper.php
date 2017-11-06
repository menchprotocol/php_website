<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scraper extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	
	function coursereport_list($total_pages){
	    
	    $array_stats = array(
	        'existing' => array(),
	        'new' => array(),
	    );
	    
	    //This function goes through the paginated structure of Course Report to fetch all bootcamp	    
	    for ($i = 1; $i<=$total_pages; $i++) {
	        $page_html = file_get_contents('https://www.coursereport.com/schools?page='.$i);
	        $links = explode('"school-info"><a href="/schools/',$page_html);
	        foreach($links as $index=>$link){
	            if($index==0){
	                //Skip first item as it does not have a link!
	                continue;
	            }
	            
	            $url_key = explode('"',$link,2);
	            $school_url = 'https://www.coursereport.com/schools/'.$url_key[0];
	            
	            //Check if we already have this URL:
	            $already_exists = $this->Db_model->il_fetch(array(
	                'il_url' => $school_url,
	            ));
	            
	            if(count($already_exists)>0){
	                array_push($array_stats['existing'],$school_url);
	            } else {
	               //This is new:
	                $this->Db_model->il_create(array(
	                   'il_url' => $school_url,
	               ));
	               array_push($array_stats['new'],$school_url);
	            }
	        }
	    }
	    //Echo results:
	    echo_json($array_stats);
	}
	
	function coursereport_csv(){
	    $to_print = $this->Db_model->il_fetch(array(
	        'il_timestamp !=' => null, //Fetch courses that have never been updated before
	    ));
	    
	    $counter = 0;
	    foreach($to_print as $tp){
	        $counter++;
	        echo $counter;
	        echo "\t".$tp['il_company'];
	        echo "\t".$tp['il_email'];
	        echo "\t".$tp['il_review_count'];
	        echo "\t".$tp['il_rating_score'];
	        echo "\t".$tp['il_website'];
	        echo "\t".'https://www.facebook.com/'.$tp['il_facebook'];
	        echo "\t".'https://twitter.com/'.$tp['il_twitter'];
	        echo "\n";
	    }
	}
	
	function coursereport_sync(){
	    boost_power();
	    //This function goes through the indexed bootcamps in v5_leads and fetches more data for them.
	    $max_update_per_batch = 50;
	    $to_update = $this->Db_model->il_fetch(array(
	        'il_timestamp' => null, //Fetch courses that have never been updated before
	    ));
	    
	    $count = 0;
	    foreach($to_update as $tu){
	        
	        //Fetch item:
	        $html = file_get_contents($tu['il_url']);
	        
	        //Update this item:
	        $this->Db_model->il_update( $tu['il_id'] , array(
	            'il_timestamp' => date("Y-m-d H:i:s"),
	            'il_company' => one_two_explode('<h1>','</h1>',$html),
	            'il_overview' => strip_tags(one_two_explode('<div class="expandable">','</div>',$html)),
	            'il_email' => one_two_explode('itemprop="email" href="mailto:','"',$html),
	            'il_review_count' => one_two_explode('itemprop="reviewCount">','<',$html),
	            'il_rating_score' => one_two_explode('itemprop="ratingValue">','<',$html),
	            'il_website' => one_two_explode('itemprop="url" href="','"',$html),
	            'il_facebook' => one_two_explode('</span><a href="https://www.facebook.com/','"',$html),
	            'il_twitter' => one_two_explode('</span><a href="https://twitter.com/','"',$html),
	        ));
	        
	        //Counter:
	        $count++;
	        if($count>=$max_update_per_batch){
	            break;
	        }
	    }
	    
	    echo $count.' Updated.';
	}
}
