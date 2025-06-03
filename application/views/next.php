<?php

$next__url = $this->Chains->next_ideas($source_session['sourceid'], $focus_i['ideahashtag'], $focus_i);

//Go to URL:
return get_redirected('/'.$focus_i['ideahashtag'].'/'.($next__url ? $next__url : 'start' ));
