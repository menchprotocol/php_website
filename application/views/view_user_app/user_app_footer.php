
</div>
</div>


<?php if(!isset($hide_footer) || !$hide_footer){ ?>
<?php if(!isset($hide_social) || !$hide_social){ ?>


<?php } else { ?>

    <footer class="footer" style="margin:0 0 50px 0;">
        <div class="container">
            <div class="pfooter"
                 style="font-size:0.8em;"><?= $this->config->item('system_icon').$this->config->item('system_name').' v' . $this->config->item('app_version') ?></div>
        </div>
    </footer>

    <?php
}


//Load modal only if in Action Plan:
if($this->uri->segment(1)=='actionplan'){
    ?>
    <div class="modal fade" id="markCompleteModal" tabindex="-1" role="dialog" aria-labelledby="markCompleteModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="padding-bottom:10px; font-size: 1.1em !important;">You are about to remove the intention to <b class="stop-title"></b> from your Action plan. Choose a reason to continue:</p>

                    <div class="form-group label-floating is-empty">
                        <?php
                        foreach($this->config->item('en_all_10570') as $en_id => $m){
                            echo '<span class="radio">
                        <label style="font-size:1em; font-weight: 300;">
                            <input type="radio" name="stop_type" value="' . $en_id . '" />
                            ' . $m['m_icon'] . ' <b>' . $m['m_name'] . '</b> ' . $m['m_desc'] . '
                        </label>
                    </span>';
                        }
                        ?>
                    </div>

                    <p style="font-size:1.1em !important; margin:20px 0 0 0; padding: 0;">Any additional feedback to help Mench improve?</p>
                    <textarea id="stop_feedback" class="border" placeholder="" style="height:66px; width: 100%; padding: 5px;"></textarea>
                    <input type="hidden" id="stop_in_id" value="">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="apply_stop()" class="btn btn-blog"><i class="fas fa-comment-times"></i> Remove</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php } ?>

</body>
</html>