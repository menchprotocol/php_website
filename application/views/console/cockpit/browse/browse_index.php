
<div class="help_body maxout below_h" id="content_6086"></div>

<ul class="nav nav-pills nav-pills-primary">
    <li class="<?= ( $object_name=='engagements' ? 'active' : '') ?>"><a href="/cockpit/browse/engagements"><i class="fas fa-square"></i> Engagements</a></li>
    <li class="<?= ( $object_name=='bootcamps' ? 'active' : '') ?>"><a href="/cockpit/browse/bootcamps"><i class="fas fa-circle"></i> Bootcamps</a></li>
    <li class="<?= ( $object_name=='classes' ? 'active' : '') ?>"><a href="/cockpit/browse/classes"><i class="fas fa-calendar"></i> Classes</a></li>
    <li class="<?= ( $object_name=='pages' ? 'active' : '') ?>"><a href="/cockpit/browse/pages"><i class="fab fa-facebook"></i> Pages</a></li>
</ul>
<hr />

<?php
if($object_name=='none'){
    echo '<p>Select an item from the menu above to get started.</p>';
    echo '<p>p.s. curiosity killed the cat mate 😉​</p>';
} else {
    $this->load->view('console/cockpit/browse/'.$object_name.'_browse');
}
?>