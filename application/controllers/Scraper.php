<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scraper extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
		boost_power();
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function udemy_import(){
	    
	    //Define key variables:
	    $client_id = 'g8m7YhcqLAXuJsWxGXXeEjQ1KZ82eh9WEeCPbPGG';
	    $client_secret = 'Q3MhsS1d8DMjmaB6mbifp0KcpYkG51HFvrA8t0h1ZFXvTDEiHVplggrF0U0PdOBuzKmkJta46YRpv9Rf6QLptoiI4AsGHU9MWaIjkf4whX5eWL0WASXo2LCVuTdOcNhA';
	    $stats = array(
	        'category' => 'Development', //Academics Business Design Development Health & Fitness IT & Software Language Lifestyle Marketing Music Office Productivity Personal Development Photography Teacher Training Test Prep
	        'page_start' => 76,
	        'page_end' => 85,
	        'total_results' => 0,
	        'newly_added' => 0,
	        'already_existed' => 0,
	    );
	    
	    for ($p = $stats['page_start']; $p <= $stats['page_end']; $p++) {
	        $api_url = 'https://www.udemy.com/api-2.0/courses/?category='.$stats['category'].'&ordering=most-reviewed&page_size=100&page='.$p;
	        $context = stream_context_create(array(
	            'http' => array(
	                'header' => "Authorization: Basic " . base64_encode("$client_id:$client_secret"),
	            ),
	        ));
	        $results = objectToArray(json_decode(file_get_contents($api_url, false, $context)));
	        $stats['total_results'] = $results['count'];
	        
	        //Go through all the results of this page:
	        foreach($results['results'] as $result){
	            //GO through all the instructors of this course:
	            foreach($result['visible_instructors'] as $instructor){
	                //See if they eixist in DB or not:
	                $already_exists = $this->Db_model->il_fetch(array(
	                    'il_udemy_user_id' => $instructor['id'],
	                ));
	                if(count($already_exists)>0){
	                    //Exists already!
	                    $stats['already_existed']++;
	                } else {
	                    $stats['newly_added']++;
	                    $names = explode(' ',$instructor['display_name'],2);
	                    
	                    $this->Db_model->il_create(array(
	                        'il_udemy_user_id' => $instructor['id'],
	                        'il_url' => 'https://www.udemy.com'.$instructor['url'],
	                        'il_overview' => $instructor['job_title'],
	                        'il_first_name' => $names[0],
	                        'il_last_name' => $names[1],
	                        'il_udemy_category' => $stats['category'],
	                    ));
	                }
	            }
	        }
	    }
	    
	    //Echo stats:
	    print_r($stats);
	}
	
	function udemy_extract(){
	    //Goes through the indexed Udemy users in our DB and fetches more details on each user
	    $max_update_per_batch = 50;
	    $to_update = $this->Db_model->il_fetch(array(
	        'il_timestamp' => null, //Fetch courses that have never been updated before
	        'il_udemy_user_id >' => 0,
	        'il_udemy_category' => 'Development',
	    ));
	    
	    $count = 0;
	    foreach($to_update as $tu){
	        
	        //Fetch item:
	        $html = file_get_contents($tu['il_url']);
	        
	        //Update this item:
	        $update_data = array(
	            'il_timestamp' => date("Y-m-d H:i:s"),
	            'il_overview' => ( strlen($tu['il_overview'])>0 ? $tu['il_overview']."\n\n" : '' ).preg_replace('/\s+/', ' ',strip_tags(one_two_explode('<div class="js-simple-collapse-inner">','</div>',$html))),
	            'il_review_count' => intval(str_replace(',','',strip_tags(one_two_explode('Reviews','</li>',one_two_explode('<ul class="instructor__stats">','</ul>',$html))))),
	            'il_course_count' => intval(str_replace(',','',strip_tags(one_two_explode('Courses','</li>',one_two_explode('<ul class="instructor__stats">','</ul>',$html))))),
	            'il_student_count' => intval(str_replace(',','',strip_tags(one_two_explode('Students','</li>',one_two_explode('<ul class="instructor__stats">','</ul>',$html))))),
	            'il_website' => (substr_count($html,'<i class="udi udi-globe"></i>')==1 ? one_two_explode('href="','"',one_two_explode('<div class="instructor__social">','<i class="udi udi-globe"></i>',$html)) : null),
	            'il_facebook' => (substr_count($html,'<i class="udi udi-facebook-f"></i>')==1 ? 'https://www.facebook.com'.one_two_explode('href="https://www.facebook.com','"',one_two_explode('<div class="instructor__social">','<i class="udi udi-facebook-f"></i>',$html)) : null),
	            'il_twitter' => (substr_count($html,'<i class="udi udi-twitter"></i>')==1 ? 'https://twitter.com'.one_two_explode('href="https://twitter.com','"',one_two_explode('<div class="instructor__social">','<i class="udi udi-twitter"></i>',$html)) : null),
	            'il_youtube' => (substr_count($html,'<i class="udi udi-youtube"></i>')==1 ? 'https://www.youtube.com'.one_two_explode('href="https://www.youtube.com','"',one_two_explode('<div class="instructor__social">','<i class="udi udi-youtube"></i>',$html)) : null),
	            'il_linkedin' => (substr_count($html,'<i class="udi udi-linkedin"></i>')==1 ? 'https://linkedin.com'.one_two_explode('href="https://linkedin.com','"',one_two_explode('<div class="instructor__social">','<i class="udi udi-linkedin"></i>',$html)) : null),
	        );
	        $this->Db_model->il_update( $tu['il_id'] , $update_data );
	        
	        //print_r($tu);
	        //print_r($update_data);
	        
	        //Counter:
	        $count++;
	        if($count>=$max_update_per_batch){
	            break;
	        }
	    }
	    
	    echo '<head><meta http-equiv="refresh" content="2"></head>';
	    echo $count.' Updated.';
	}
	
	function udemy_csv(){
	    $to_print = $this->Db_model->il_fetch(array(
	        'il_timestamp !=' => null, //Fetch courses that have never been updated before
	        'il_udemy_user_id >' => 0,
	        'il_udemy_category' => 'Development',
	    ));
	    
	    header("Content-type: application/octet-stream");
	    header("Content-Disposition: attachment; filename=your_desired_name.xls");
	    header("Pragma: no-cache");
	    header("Expires: 0");
	    
	    echo "#";
	    echo "\tFirst Name";
	    echo "\tLast Name";
	    echo "\tCategory";
	    
	    echo "\tStudent Count";
	    echo "\tCourse Count";
	    echo "\tReview Count";
	    
	    echo "\tOverview";
	    
	    echo "\tUdemy URL";
	    echo "\tWebsite";
	    echo "\tFacebook";
	    echo "\tTwitter";
	    echo "\tYouTube";
	    echo "\tLinkedIn";
	    echo "\r\n";
	    
	    $counter = 0;
	    foreach($to_print as $tp){
	        $counter++;
	        echo $counter;
	        echo "\t".$tp['il_first_name'];
	        echo "\t".$tp['il_last_name'];
	        echo "\t".$tp['il_udemy_category'];
	        
	        echo "\t".intval($tp['il_student_count']);
	        echo "\t".intval($tp['il_course_count']);
	        echo "\t".intval($tp['il_review_count']);
	        
	        echo "\t".trim(str_replace("\n",' ',$tp['il_overview']));
	        
	        
	        echo "\t".$tp['il_url'];
	        echo "\t".trim($tp['il_website']);
	        echo "\t".trim($tp['il_facebook']);
	        echo "\t".trim($tp['il_twitter']);
	        echo "\t".trim($tp['il_youtube']);
	        echo "\t".trim($tp['il_linkedin']);
	        echo "\r\n";
	    }
	}
}
