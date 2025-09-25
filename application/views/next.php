<?php

if(post_is_startable($focus_i)){
    $next__url = $this->Chains->next_posts($user_session['userid'], $focus_i['posthashtag'], $focus_i);
    //Go to URL:
    return get_redirected('/'.$focus_i['posthashtag'].'/'.($next__url ? $next__url : 'start' ));
} else {
    foreach ($this->Chains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
        'chainpostinput' => $focus_i['postid'],
        'chainusercreator' => $user_session['userid'],
    ), array('chainpostoutput')) as $discovery) {
        $next__url = $this->Chains->next_posts($user_session['userid'], $discovery['posthashtag'], $discovery);

        //Go to URL:
        return get_redirected('/'.$discovery['posthashtag'].'/'.($next__url ? $next__url : 'start' ));
    }
}



