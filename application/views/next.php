<?php

if(idea_is_startable($focus_i)){
    $next__url = $this->Chains->next_ideas($source_session['sourceid'], $focus_i['ideahashtag'], $focus_i);
    //Go to URL:
    return get_redirected('/'.$focus_i['ideahashtag'].'/'.($next__url ? $next__url : 'start' ));
} else {
    foreach ($this->Chains->read(array(
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___31777')) . ')' => null, //DISCOVERIES
        'chainidealeft' => $focus_i['ideaid'],
        'chainsourcecreator' => $source_session['sourceid'],
    ), array('chainidearight')) as $discovery) {
        $next__url = $this->Chains->next_ideas($source_session['sourceid'], $discovery['ideahashtag'], $focus_i);

        //Go to URL:
        return get_redirected('/'.$discovery['ideahashtag'].'/'.($next__url ? $next__url : 'start' ));
    }
}



