<div class="pfooter" style="padding-top:30px;"><?= '<img src="/img/mench_white.png" />Mench v'. $this->config->item('app_version') ?></div>
</div>
</div>

<?php $this->load->view('view_shared/messenger_web_chat'); ?>
<?php
//Load modal only if in Action Plan:
if($this->uri->segment(1)=='actionplan'){
    ?>
    <div class="modal fade" id="markCompleteModal" tabindex="-1" role="dialog" aria-labelledby="markCompleteModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="padding-bottom:10px;">You are about to stop your intention to <b class="stop-title"></b>. Choose a reason to continue:</p>

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

                    <p style="font-size:1.1em !important; margin:0; padding: 0;">Any additional comments or feedback?</p>
                    <textarea id="stop_feedback" class="border" placeholder="I think Mench can improve by..." style="height:66px; width: 100%; padding: 5px;"></textarea>
                    <input type="hidden" id="stop_in_id" value="">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="apply_stop()" class="btn btn-primary"><i class="fas fa-hand-paper"></i> Stop</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>


</body>
</html>
