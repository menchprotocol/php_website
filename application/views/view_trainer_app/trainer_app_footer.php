</div>
</div>
</div>
</div>

<div class="app-version hide-mini">v<?= $this->config->item('app_version') ?></div>

<?php
if (!isset($_GET['skip_header'])) {
    //Include the chat plugin:
    $this->load->view('view_shared/messenger_web_chat');
}

//Load modal only if in Action Plan:
if($this->uri->segment(1)=='intents'){
    ?>
    <div class="modal fade" id="addUpVote" tabindex="-1" role="dialog" aria-labelledby="addUpVoteLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="padding-bottom:10px; font-size: 1.1em !important;">Join the intention to <b class="upvote_intent"></b> by casting your up-vote.</p>

                    <p style="padding-bottom:10px; font-size: 1.1em !important;">IF <b class="upvote_intent"></b> THEN:</p>

                    <div class="list-group grey_list" id="upvote_parents"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

</body>
</html>