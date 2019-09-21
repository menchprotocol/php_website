<?php

foreach($pending_messages as $pending_message){
    echo $this->Communication_model->dispatch_message($pending_message['ln_content']);
}

echo '<a class="btn btn-primary" href="/actionplan/next" style="display: inline-block; padding:12px 36px; font-size: 1.3em;">Next &nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a>';
