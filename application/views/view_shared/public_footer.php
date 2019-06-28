
</div>
</div>

<footer class="footer" style="margin:0 0 50px 0;">
    <div class="container">
        <nav>
            <ul class="footer-a">

                <li class="social-li">

                    <a href="https://github.com/askmench" target="_blank"
                       class="social-link tag-manager-social-profile" data-toggle="tooltip" title="Mench is open-source. contribute on GitHub"
                       data-placement="top"><i class="fab fa-github"></i></a>

                    <a href="https://askmench.slack.com" target="_blank"
                       class="social-link tag-manager-social-profile" data-toggle="tooltip" title="Join the Conversation on Slack"
                       data-placement="top"><i class="fab fa-slack"></i></a>


                    <span>|</span>


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

                    <span>|</span>


                    <?php $en_all_7368 = $this->config->item('en_all_7368'); ?>
                    <a href="/dashboard"
                       class="social-link tag-manager-footer-dashboard" data-toggle="tooltip" title="<?= $en_all_7368[7161]['m_name'] ?>"
                       data-placement="top"><?= $en_all_7368[7161]['m_icon'] ?></a>

                    <a href="/login"
                       class="social-link tag-manager-sign-in" data-toggle="tooltip" title="Sign In"
                       data-placement="top"><i class="fas fa-sign-in"></i></a>

                    <a href="/8263?autoexpand=1" class="social-link tag-manager-social-profile" data-toggle="tooltip"
                       title="Terms of service & privacy policy" data-placement="top"><i
                                class="fas fa-balance-scale"></i></a>

                </li>
            </ul>
        </nav>

        <div class="pfooter" style="font-size:0.8em;"><?= '<img src="/img/mench_white.png" />Mench v'. $this->config->item('app_version') ?></div>

    </div>
</footer>

</body>
</html>
