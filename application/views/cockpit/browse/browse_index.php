
<div class="help_body maxout below_h" id="content_6086"></div>

<ul class="nav nav-pills nav-pills-primary">
    <li class="<?= ( $object_name=='engagements' ? 'active' : '') ?>"><a href="/cockpit/browse/engagements"><i class="fa fa-eye" aria-hidden="true"></i> Engagements</a></li>
    <li class="<?= ( $object_name=='bootcamps' ? 'active' : '') ?>"><a href="/cockpit/browse/bootcamps"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Bootcamps</a></li>
    <li class="<?= ( $object_name=='classes' ? 'active' : '') ?>"><a href="/cockpit/browse/classes"><i class="fa fa-calendar" aria-hidden="true"></i> Classes</a></li>
    <li class="<?= ( $object_name=='users' ? 'active' : '') ?>"><a href="/cockpit/browse/users"><i class="fa fa-user" aria-hidden="true"></i> Users</a></li>
    <li class="<?= ( $object_name=='pages' ? 'active' : '') ?>"><a href="/cockpit/browse/pages"><i class="fa fa-facebook-official" aria-hidden="true"></i> Pages</a></li>
</ul>
<hr />

<?php
if($object_name=='none'){
    echo '<p>Select an item from the menu above to get started.</p>';
    echo '<p>p.s. curiosity killed the cat mate 😉​</p>';
} else {
    $this->load->view('cockpit/browse/'.$object_name.'_browse');
}
?>