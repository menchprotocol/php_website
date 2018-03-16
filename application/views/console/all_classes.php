<?php
$max_threashold = 10;
?>
<div class="help_body maxout below_h" id="content_2274"></div>


<input type="hidden" id="focus_r_id" value="0" />

<table class="table">
    <tr>
        <td class="class_nav">

            <ul id="topnav" class="nav nav-pills nav-pills-primary" style="margin-bottom:12px;">
                <li id="nav_active" class="active"><a href="#active"><i class="fa fa-play-circle initial"></i> Active</a></li>
                <li id="nav_past"><a href="#past"><i class="fa fa-graduation-cap initial"></i> Complete</a></li>
            </ul>

            <div class="tab-content tab-space">

                <div class="tab-pane active" id="tabactive">
                    <?php
                    $active_classes = $this->Db_model->r_fetch(array(
                        'r.r_b_id'	        => $b['b_id'],
                        'r.r_status >='	    => 0, //No Support
                        'r.r_status <='	    => 2, //Running
                    ), $b, 'ASC');

                    if(count($active_classes)>0){
                        echo '<div class="list-group maxout">';
                        foreach($active_classes as $key=>$class){
                            echo_r($b['b_id'],$class,($key>=$max_threashold?'active_extra hidden':''));
                        }
                        if(count($active_classes)>$max_threashold){
                            echo '<a href="javascript:void(0);" onclick="toggle_hidden_class(\'active_extra\')" class="list-group-item active_extra" style="text-decoration:none;"><i class="fa fa-plus-square-o" style="margin: 0 6px 0 4px; font-size: 19px;" aria-hidden="true"></i> See all '.count($active_classes).'</a>';
                        }
                        echo '</div>';

                    } else {
                        //Show none
                        echo '<div class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> None</div>';
                        //Log Error, this should not happen!
                        $this->Db_model->e_create(array(
                            'e_message' => 'Project Class was missing when Classes where loaded',
                            'e_b_id' => $b['b_id'],
                            'e_type_id' => 8, //Platform Error
                        ));
                    }
                    ?>
                </div>
                <div class="tab-pane" id="tabpast">
                    <?php
                    $past_classes = $this->Db_model->r_fetch(array(
                        'r.r_b_id'	        => $b['b_id'],
                        'r.r_status'	    => 3, //Completed
                    ), $b, 'DESC');

                    if(count($past_classes)>0){
                        echo '<div class="list-group maxout">';
                        foreach($past_classes as $key=>$class){
                            echo_r($b['b_id'],$class,($key>=$max_threashold?'past_extra hidden':''));
                        }
                        if(count($past_classes)>$max_threashold){
                            echo '<a href="javascript:void(0);" onclick="toggle_hidden_class(\'past_extra\')" class="list-group-item past_extra" style="text-decoration:none;"><i class="fa fa-plus-square-o" style="margin: 0 6px 0 4px; font-size: 19px;" aria-hidden="true"></i> See all '.count($past_classes).'</a>';
                        }
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> None</div>';
                    }
                    ?>
                </div>
            </div>


        </td>
        <td style="padding-top:0;">
            <div id="load_leaderboard"></div>
        </td>
    </tr>
</table>

