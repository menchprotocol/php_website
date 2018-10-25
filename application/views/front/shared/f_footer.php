<?php 
//Attempt to fetch session variables:
$website = $this->config->item('website');
?></div>
</div>

 	<footer class="footer">
        <div class="container">
            <nav>
                <ul class="pull-center footer-a">
                    <li class="social-li">
                        <a href="https://m.me/askmench?ref=SUBSCRIBE10_6623" class="social-link" data-toggle="tooltip" title="Get started with Mench Personal Assistant on Facebook Messenger" data-placement="top"><i class="fab fa-facebook-messenger"></i></a>
                        <a href="https://www.youtube.com/channel/UCOH64HiAIfJlz73tTSI8n-g" target="_blank" class="social-link" data-toggle="tooltip" title="Subscribe to our YouTube Channel" data-placement="top"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.facebook.com/askmench" target="_blank" class="social-link" data-toggle="tooltip" title="Like us on Facebook" data-placement="top"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.linkedin.com/company/askmench/" target="_blank" class="social-link" data-toggle="tooltip" title="Follow us on LinkedIn" data-placement="top"><i class="fab fa-linkedin"></i></a>
                        <a href="https://twitter.com/askmench" target="_blank" class="social-link" data-toggle="tooltip" title="Follow us on Twitter" data-placement="top"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/askmench/" target="_blank" class="social-link" data-toggle="tooltip" title="Follow us on Instagram" data-placement="top"><i class="fab fa-instagram"></i></a>
                        <a href="mailto:support@mench.com" class="social-link"><i class="fas fa-envelope" data-toggle="tooltip" title="Send us an email" data-placement="top"></i></a>
                        <a href="/terms" class="social-link"><i class="fas fa-balance-scale" data-toggle="tooltip" title="Review our terms of service and privacy policy" data-placement="top"></i></a>
                    </li>
                    <li class="legal-name"><i><span class="legal-name underdot" data-toggle="tooltip" title="Current version was released on <?= date("F j, Y",strtotime($website['released'])) ?>" data-placement="top">v<?= $website['version'] ?></span></i></li>
                </ul>
            </nav>
        </div>
    </footer>

</body>
</html>
