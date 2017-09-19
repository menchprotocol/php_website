<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$website = $this->config->item('website');
?></div>
</div>

 	<footer class="footer">
        <div class="container">
            <nav>
                <ul>
                    <li class="pull-left"><a href="/terms"><?= $this->lang->line('terms') ?></a></li>
                    <li class="pull-left"><a href="/contact"><?= $this->lang->line('contact_us') ?></a></li>
                    <?= (!isset($udata['u_id']) ? '<li class="pull-left"><a href="/login">'.$this->lang->line('login').'</a></li>' : ''); ?>
                    
                    <li class="pull-right"><i><?= $website['legaL_name'] ?></i></li>
                    <li class="pull-right"><i>v<?= $website['version'] ?></i></li>
                </ul>
            </nav>
        </div>
    </footer>

</body>
</html>
