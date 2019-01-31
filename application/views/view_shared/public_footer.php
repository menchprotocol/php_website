</div>
</div>

<footer class="footer" style="margin:0 0 30px 0;">
    <div class="container">
        <nav>
            <ul class="footer-a">

                <li class="social-li">

                    <a href="https://m.me/askmench?ref=<?= $this->config->item('in_tactic_id') ?>"
                       class="social-link" data-toggle="tooltip"
                       title="Want to <?= $this->config->item('in_strategy_name') ?>? Let's chat on Messenger" data-placement="top"><i
                                class="fab fa-facebook-messenger"></i></a>

                    <a href="/stats" class="social-link" data-toggle="tooltip"
                       title="View platform stats" data-placement="top"><i class="fas fa-chart-bar"></i></a>

                    <a href="https://angel.co/askmench/jobs" target="_blank"
                       class="social-link" data-toggle="tooltip" title="See openings on Angel List"
                       data-placement="top"><i class="fab fa-angellist"></i></a>

                    <a href="https://github.com/askmench" target="_blank"
                       class="social-link" data-toggle="tooltip" title="Mench is open-source. contribute on GitHub"
                       data-placement="top"><i class="fab fa-github"></i></a>

                    <a href="https://www.youtube.com/channel/UCOH64HiAIfJlz73tTSI8n-g" target="_blank"
                       class="social-link" data-toggle="tooltip" title="Subscribe on YouTube"
                       data-placement="top"><i class="fab fa-youtube"></i></a>

                    <a href="https://www.facebook.com/askmench" target="_blank" class="social-link"
                       data-toggle="tooltip" title="Follow on Facebook" data-placement="top"><i
                                class="fab fa-facebook"></i></a>

                    <a href="https://www.linkedin.com/company/askmench/" target="_blank" class="social-link"
                       data-toggle="tooltip" title="Follow on LinkedIn" data-placement="top"><i
                                class="fab fa-linkedin"></i></a>

                    <a href="https://twitter.com/askmench" target="_blank" class="social-link" data-toggle="tooltip"
                       title="Follow Mench on Twitter" data-placement="top"><i class="fab fa-twitter"></i></a>

                    <a href="https://www.instagram.com/askmench/" target="_blank" class="social-link"
                       data-toggle="tooltip" title="Follow on Instagram" data-placement="top"><i
                                class="fab fa-instagram"></i></a>

                    <a href="/8263?expand_mode=1&hide_subscribe=1" class="social-link" data-toggle="tooltip"
                       title="Read terms of service and privacy policy" data-placement="top"><i
                                class="fas fa-balance-scale"></i></a>

                </li>

                <li class="legal-name"><i>v<?= $this->config->item('app_version') ?></i></li>

            </ul>
        </nav>
    </div>
</footer>


<?php $this->load->view('view_shared/urlmodal_frame'); ?>



</body>
</html>
