<?php 
if($users[0]['u_cache__fp_psid']>0){

    //Oh nice, its activated!
    //Update session:
    $this->session->set_userdata(array(
        'user' => $users[0],
    ));
    
    //Inform user to continue:
    echo '<div class="maxout">';
        echo '<div class="alert alert-success" role="alert"><i class="fa fa-check-square" aria-hidden="true"></i> <b>Success!</b> We\'re now connected through Facebook Messenger.</div>';
        echo '<a href="/console" class="btn btn-primary">My Bootcamps &nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
    echo '</div>';
    
} else {
    
    echo '<div class="maxout">';
        
        //Instructor Bot has NOT been activated by the instructor yet:
        echo '<p>Activate Mench to get a taste of how we automate communications over Facebook Messenger:</p>';
        echo '<a href="'.$this->Facebook_model->fb_activation_url($users[0]['u_id'],4 /*Mench Facebook Page*/ ).'" class="btn btn-primary"> Say "Hi" to Mench &nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
    
    echo '</div>';
}
?>