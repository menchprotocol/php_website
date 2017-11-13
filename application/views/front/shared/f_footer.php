<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$website = $this->config->item('website');
?></div>
</div>

 	<footer class="footer">
        <div class="container">
            <nav>
                <ul class="pull-left">
                    <li><a href="/terms">Terms</a></li>
                    <li><a href="/contact">Contact</a></li>
				</ul>
				<ul class="pull-right">
                    <li class="legal-name"><i><img src="/img/bp_128.png" /><?= $website['legaL_name'] ?></i></li>
                    <li><i>v<?= $website['version'] ?></i></li>
                </ul>
            </nav>
        </div>
    </footer>

</body>
</html>
