

<h1 class="center"><?= $title ?></h1>
<p class="home_line_2 center">Mench is a personal assistant for landing your dream tech job.</p>
<p class="home_line_2 center" style="margin:12px 0 50px 0;">It's trained exclusively with curated insights from industry experts.</p>



<?php
//Fetch bs:
$bs = $this->Db_model->remix_bs(array(
    'b.b_status' => 3,
    'b.b_fp_id >' => 0,
));

echo '<div class="row">';
echo '<div class="list-group maxout" style="display: block; margin: 0 auto;">';
foreach($bs as $count=>$b){
    echo '<a href="/'.$b['b_url_key'].'" class="list-group-item">';
    echo '<span class="pull-right"><span class="badge badge-primary"><i class="fas fa-chevron-right"></i></span></span>';
    echo '<i class="fas fa-usd-circle" style="margin: 0 8px 0 2px;"></i>';
    echo $b['c_outcome'];
    echo '</a>';
}

echo '</div>';
echo '</div>';


?>
</div>
</div>




</div>
</div>

<div class="main main-raised main-plain main-footer">
    <div class="container">

        <?php $this->load->view('front/b/bs_include'); ?>
