<?php

if(hashtag_is_startable($focus_i)){
    $next__url = $this->Chains->next_hashtags($handle_session['handleid'], $focus_i['hashtagterm'], $focus_i);
    //Go to URL:
    return get_redirected('/'.$focus_i['hashtagterm'].'/'.($next__url ? $next__url : 'start' ));
} else {
    foreach ($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
        'chainhashtaginput' => $focus_i['hashtagid'],
        'chainhandlecreator' => $handle_session['handleid'],
    ), array('chainhashtagoutput')) as $discovery) {
        $next__url = $this->Chains->next_hashtags($handle_session['handleid'], $discovery['hashtagterm'], $discovery);

        //Go to URL:
        return get_redirected('/'.$discovery['hashtagterm'].'/'.($next__url ? $next__url : 'start' ));
    }
}



