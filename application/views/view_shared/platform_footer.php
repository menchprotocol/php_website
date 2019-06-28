</div>
</div>
</div>
</div>

<?php
if (!isset($_GET['skip_header'])) {
    //Include the chat plugin:
    $this->load->view('view_shared/messenger_web_chat');
}
?>

<div class="app-version hide-mini <?= advance_mode() ?>">v<?= $this->config->item('app_version') ?></div>

</body>
</html>