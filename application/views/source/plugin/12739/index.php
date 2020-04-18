<?php

//UI to compose a test message:
echo '<form method="GET" action="">';

echo '<div class="mini-header">URL:</div>';
echo '<input type="url" class="form-control border maxout" name="url_to_analyze" value="'.@$_GET['url_to_analyze'].'"><br />';
echo '<input type="submit" class="btn btn-idea" value="Analyze">';


if(isset($_GET['url_to_analyze']) && strlen($_GET['url_to_analyze'])>0){

    //Show analysis results:
    echo '<hr />'.nl2br(str_replace(' ','&nbsp;', print_r(array(
            'analyze_domain' => analyze_domain($_GET['url_to_analyze']),
            'echo_url_embed' => echo_url_embed($_GET['url_to_analyze'], null, true),
            'en_url' => $this->SOURCE_model->en_url($_GET['url_to_analyze']),
        ), true))).'<hr />';

    echo 'Embed Code:<hr />'.echo_url_embed($_GET['url_to_analyze']);

} else {

    echo '<hr />Enter URL to get started.';

}

echo '</form>';