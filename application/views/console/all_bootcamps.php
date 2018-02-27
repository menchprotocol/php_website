

<script>
    $(document).ready(function() {
        var isMobile = false; //initiate as false
// device detection
        if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
            || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { isMobile = true; }
        if(isMobile){
            $('#mobile-no').show();
        }
    });
</script>
<?php $website = $this->config->item('website'); ?>
<div class="alert alert-info" role="alert" id="mobile-no" style="display:none; margin-top:30px;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Mench Console v<?= $website['version'] ?> is not fully optimized for a mobile device. We recommend using a desktop computer instead.</div>



<?php

//Instructor has already activated their Instructor Bot
if(count($bootcamps)>0){
    echo '<div class="list-group maxout">';
    foreach($bootcamps as $bootcamp){
        
        echo '<a href="/console/'.$bootcamp['b_id'].'" class="list-group-item">';
        echo '<span class="pull-right"><span class="badge badge-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
        echo '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> <b>'.$bootcamp['c_objective'].'</b>';

        if($bootcamp['b_fp_id']>0){
            //Show the connected Facebook Page
            echo ' &nbsp;<span style="font-size:0.8em;" title="Bootcamp Connected to this Facebook Page" data-toggle="tooltip"><i class="fa fa-plug" aria-hidden="true"></i> '.$bootcamp['fp_name'].'</span>';
        }

        /*

        //Calculate their progress:
        $launch_status = calculate_bootcamp_status($bootcamp);

        echo '<ul class="below_list">';
        
        echo '<li style="min-width:100px;"><i class="fa fa-flag" aria-hidden="true"></i> '.$bootcamp['c__milestone_units'].' '.ucwords($bootcamp['b_sprint_unit']).( $bootcamp['c__milestone_units']==1 ? '' : 's' ).'</li>';
        echo '<li style="min-width:115px;"><i class="fa fa-tasks" aria-hidden="true"></i> '.$launch_status['progress'].'% Ready</li>';
        echo '<li>'.status_bible('b',$bootcamp['b_status'],0,'right').'</li>';
        
        echo '</ul>';
        */
        echo '</a>';
    }
    echo '</div>';
} else {
    echo '<div class="alert alert-info" role="alert">No Bootcamps created yet.</div>';
}

//New Bootcamp Button:
echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#newBootcampModal">New</a>';
if($udata['u_status']>=3){
    echo '<span> &nbsp; or <a href="/cockpit/browse/bootcamps"><u>Browse All</u></a></span>';
}

?>