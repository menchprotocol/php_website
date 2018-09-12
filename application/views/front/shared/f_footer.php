<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$website = $this->config->item('website');
?></div>
</div>

 	<footer class="footer">
        <div class="container">
            <nav>
                <ul class="pull-center footer-a">
                    <li><a href="/terms">Terms</a></li>
                    <li class="social-li">
                        <a href="https://www.youtube.com/channel/UCOH64HiAIfJlz73tTSI8n-g" target="_blank" class="social-link"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.facebook.com/askmench" target="_blank" class="social-link"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.linkedin.com/company/askmench/" target="_blank" class="social-link"><i class="fab fa-linkedin"></i></a>
                        <a href="https://twitter.com/askmench" target="_blank" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/askmench/" target="_blank" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="mailto:support@mench.com" class="social-link"><i class="fas fa-envelope"></i></a>
                    </li>
                    <li class="legal-name"><i>v<?= $website['version'] ?></i></li>
                </ul>
            </nav>
        </div>
    </footer>

</body>
</html>
