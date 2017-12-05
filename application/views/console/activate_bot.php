<?php 
if($users[0]['u_fb_id']>0){
    //Oh nice, its not activated!
    //Update session varible:
    $this->session->set_userdata(array(
        'user' => $users[0],
    ));
    
    //Tell user to continue:
    echo '<div class="maxout">';
        echo '<div class="alert alert-success" role="alert"><i class="fa fa-check-square" aria-hidden="true"></i> <b>Success!</b> We\'re now connected through Facebook Messenger.</div>';
        echo '<a href="/console" class="btn btn-primary">Go To My Bootcamps &nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
    echo '</div>';
    
} else {
    
    echo '<div class="maxout">';
    
        //Still not activated!
        $mench_bots = $this->config->item('mench_bots');
        
        //Instructor Bot has NOT been activated by the instructor yet:
        echo '<p>'.nl2br($mench_bots['381488558920384']['settings']['greeting'][0]['text']).'</p>';
        echo '<a href="'.messenger_activation_url('381488558920384',$users[0]['u_id']).'" class="btn btn-primary"> Activate Bot &nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
        
        
        
        /*
        if(isset($_GET['if_activated'])){
            echo '<div class="alert alert-warning" style="margin-top:20px;" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>Not Yet Activated!</b> start chatting with your MenchBot to get started.</div>';
        }
        echo '<a href="/console?if_activated=1" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>';
        */
    
    echo '</div>';
}
?>