
</div>
</div>


<?php if(!isset($hide_footer) || !$hide_footer){ ?>
<?php if(!isset($hide_social) || !$hide_social){ ?>

    <footer class="footer" style="margin:30px 0 50px 0;">
        <div class="container">
            <nav>
                <ul class="footer-a">
                    <li class="social-li">

                        <div class="icon-main" style="margin-bottom:-12px; margin-top:60px;"><?= $this->config->item('system_icon') ?></div>

                        <br />

                        <?php $en_all_7369 = $this->config->item('en_all_7369'); /* Mench User App */ ?>
                        <?php $en_all_7372 = $this->config->item('en_all_7372'); /* Mench Products */ ?>


                        <a href="/8263" class="social-link tag-manager-social-profile" data-toggle="tooltip"
                           title="<?= $en_all_7372[7540]['m_name'] ?>" data-placement="top"><?= $en_all_7372[7540]['m_icon'] ?></a>


                        <a href="/dashboard"
                           class="social-link tag-manager-footer-dashboard" data-toggle="tooltip" title="<?= $en_all_7369[7161]['m_name'] ?>"
                           data-placement="top"><?= $en_all_7369[7161]['m_icon'] ?></a>

                        <a href="/signin"
                           class="social-link tag-manager-sign-in" data-toggle="tooltip" title="<?= $en_all_7369[4269]['m_name'] ?>"
                           data-placement="top"><?= $en_all_7369[4269]['m_icon'] ?></a>

                        <br />

                        <a href="https://www.instagram.com/askmench/" target="_blank" class="social-link tag-manager-social-profile"
                           data-toggle="tooltip" title="Follow on Instagram" data-placement="top"><i
                                    class="fab fa-instagram"></i></a>

                        <a href="https://www.facebook.com/askmench" target="_blank" class="social-link tag-manager-social-profile"
                           data-toggle="tooltip" title="Follow on Facebook" data-placement="top"><i
                                    class="fab fa-facebook"></i></a>

                        <a href="https://twitter.com/askmench" target="_blank" class="social-link tag-manager-social-profile" data-toggle="tooltip"
                           title="Follow on Twitter" data-placement="top"><i class="fab fa-twitter"></i></a>

                        <a href="https://www.youtube.com/channel/UCOH64HiAIfJlz73tTSI8n-g" target="_blank"
                           class="social-link tag-manager-social-profile" data-toggle="tooltip" title="Subscribe on YouTube"
                           data-placement="top"><i class="fab fa-youtube"></i></a>

                        <a href="https://www.linkedin.com/company/askmench/" target="_blank" class="social-link tag-manager-social-profile"
                           data-toggle="tooltip" title="Follow on LinkedIn" data-placement="top"><i
                                    class="fab fa-linkedin"></i></a>


                    </li>
                </ul>
            </nav>

            <div class="pfooter" style="font-size:0.8em;"><?= '<a href="https://github.com/askmench/mench-web-app" target="_blank"
                           class="social-link tag-manager-social-profile" data-toggle="tooltip" title="Build to Mench on GitHub"
                           data-placement="top"><i class="fab fa-github" style="padding-right: 3px;"></i>Mench Open-Source v' . $this->config->item('app_version').'</a>' ?></div>

        </div>
    </footer>

<?php } else { ?>

    <footer class="footer" style="margin:0 0 50px 0;">
        <div class="container">
            <div class="pfooter"
                 style="font-size:0.8em;"><?= $this->config->item('system_icon').$this->config->item('system_name').' v' . $this->config->item('app_version') ?></div>
        </div>
    </footer>

    <?php
}

if(isset($show_chat) && $show_chat){
    $this->load->view('view_shared/messenger_web_chat');
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

                    <p style="font-size:1.1em !important; margin:20px 0 0 0; padding: 0;">Any additional feedback to help Mench improve?</p>
                    <textarea id="stop_feedback" class="border" placeholder="" style="height:66px; width: 100%; padding: 5px;"></textarea>
                    <input type="hidden" id="stop_in_id" value="">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="apply_stop()" class="btn btn-primary"><i class="fas fa-comment-times"></i> Remove</button>
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