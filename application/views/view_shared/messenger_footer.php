<div class="pfooter"><img src="/img/bp_128.png">Mench v<?= $this->config->item('app_version') ?></div>
</div>
</div>

<?php $this->load->view('view_shared/messenger_web_chat'); ?>

<div class="modal fade" id="markCompleteModal" tabindex="-1" role="dialog" aria-labelledby="markCompleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p style="padding-bottom:20px;">You are about to stop your intention to <b class="stop-title"></b>. Choose a reason to continue:</p>

                <div class="form-group label-floating is-empty">
                <?php
                foreach($this->config->item('en_all_6150') as $en_id => $m){
                    echo '<span class="radio">
                        <label style="font-size:1em; font-weight: 300;">
                            <input type="radio" name="stop_type" value="' . $en_id . '" />
                            ' . $m['m_icon'] . ' <b>' . $m['m_name'] . '</b> ' . $m['m_desc'] . '
                        </label>
                    </span>';
                }
                ?>
                </div>

                <p style="font-size:1em !important;">Note: You can re-add this intention back to your Action Plan later on if you decided that you want to <span class="stop-title"></span> again.</p>
                <input type="hidden" id="stop_in_id" value="">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" onclick="apply_stop()" class="btn btn-primary"><i class="fas fa-hand-paper"></i> Stop</button>
            </div>
        </div>
    </div>
</div>



</body>
</html>
