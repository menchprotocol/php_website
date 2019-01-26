</div>
</div>

<footer class="footer" style="margin:0 0 30px 0;">
    <div class="container">
        <nav>
            <ul class="footer-a">

                <li class="social-li">

                    <a href="https://m.me/askmench?ref=<?= $this->config->item('in_tactic_id') ?>"
                       class="social-link" data-toggle="tooltip"
                       title="Connect to Mench Personal Assistant on Messenger" data-placement="top"><i
                                class="fab fa-facebook-messenger"></i></a>

                    <a href="https://angel.co/askmench/jobs" target="_blank"
                       class="social-link" data-toggle="tooltip" title="See Openings on Angel List"
                       data-placement="top"><i class="fab fa-angellist"></i></a>

                    <a href="https://github.com/askmench" target="_blank"
                       class="social-link" data-toggle="tooltip" title="Contribute to Mench on GitHub"
                       data-placement="top"><i class="fab fa-github"></i></a>

                    <a href="https://www.youtube.com/channel/UCOH64HiAIfJlz73tTSI8n-g" target="_blank"
                       class="social-link" data-toggle="tooltip" title="Subscribe on YouTube"
                       data-placement="top"><i class="fab fa-youtube"></i></a>

                    <a href="https://www.facebook.com/askmench" target="_blank" class="social-link"
                       data-toggle="tooltip" title="Follow Mench on Facebook" data-placement="top"><i
                                class="fab fa-facebook"></i></a>

                    <a href="https://www.linkedin.com/company/askmench/" target="_blank" class="social-link"
                       data-toggle="tooltip" title="Follow Mench on LinkedIn" data-placement="top"><i
                                class="fab fa-linkedin"></i></a>

                    <a href="https://twitter.com/askmench" target="_blank" class="social-link" data-toggle="tooltip"
                       title="Follow Mench on Twitter" data-placement="top"><i class="fab fa-twitter"></i></a>

                    <a href="https://www.instagram.com/askmench/" target="_blank" class="social-link"
                       data-toggle="tooltip" title="Follow Mench on Instagram" data-placement="top"><i
                                class="fab fa-instagram"></i></a>

                    <a href="mailto:support@mench.com" class="social-link" data-toggle="tooltip"
                       title="Send us an email" data-placement="top"><i class="fas fa-envelope"></i></a>

                    <a href="/8263?expand_mode=1&hide_subscribe=1" class="social-link" data-toggle="tooltip"
                       title="Understand Mench's Terms of Service" data-placement="top"><i
                                class="fas fa-balance-scale"></i></a>

                    <a href="/stats" class="social-link" data-toggle="tooltip"
                       title="View Platform Stats" data-placement="top"><i class="fas fa-chart-bar"></i> Stats</a>

                </li>

                <li class="legal-name"><i>v<?= $this->config->item('app_version') ?></i></li>

            </ul>
        </nav>
    </div>
</footer>


<div class="modal fade" id="loadUrlModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>


</body>
</html>
