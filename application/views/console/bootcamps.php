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


    var processing_project = 0;
    function project_create(){

        if(processing_project){
            //Do nothing while processing a current project:
            return false;
        }

        if($('#b_c_objective').val().length<2){
            alert('ERROR: Bootcamp Outcome Required');
            $('#b_c_objective').focus();
            return false;
        }

        //Show loader:
        processing_project = 1;
        var c_objective = $('#b_c_objective').val();
        $('.no-b-div').remove(); //It may exist...
        $('#b_c_objective').val('').prop('disabled',true);
        $('.new-b').hide();

        $( ".list_input" ).before( '<div class="list-group-item loader-div" style="padding:10px 10px;"><img src="/img/round_load.gif" class="loader" /> Creating New Bootcamp...</div>' );

        $.post("/api_v1/project_create", {
            c_objective:c_objective,
        }, function(data) {

            //Processing is done:
            processing_project = 0;
            $( ".loader-div" ).remove(); //Remove loader...
            $('#b_c_objective').prop('disabled',false);
            $('.new-b').fadeIn();

            if(data.status){
                //All good, show it:
                $('#b_c_objective').focus();
                $( ".list_input" ).before( data.message );
            } else {
                //Show error:
                alert('ERROR: '+data.message);
            }

        });
    }
</script>
<?php $website = $this->config->item('website'); ?>
<div class="alert alert-info" role="alert" id="mobile-no" style="display:none; margin-top:30px;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Mench Console v<?= $website['version'] ?> is not fully optimized for a mobile device. We recommend using a desktop computer instead.</div>



<?php

echo '<div class="list-group maxout">';

if(count($bs)>0){
    foreach($bs as $b){
        echo echo_b($b);
    }
} else {
    echo '<div class="list-group-item alert alert-info no-b-div" style="padding: 15px 10px;"><i class="fa fa-exclamation-triangle" style="margin:0 8px 0 2px;" aria-hidden="true"></i> No Bootcamps found. Create a new Bootcamp below:</div>';
}

//Input to create new Bootcamp:
echo '<div class="list-group-item list_input new-step-input" style="padding: 5px 7px;">
        <div class="input-group">
            <span class="input-group-addon addon-lean" style="color:#222; font-weight: 300;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i></span>
            <div class="form-group is-empty"  style="margin: 0; padding: 0;"><form action="#" onsubmit="project_create();"><input type="text" class="form-control"  maxlength="70" id="b_c_objective" placeholder="Build a Todo list web app using AngularJS" /></form></div>
            <span class="input-group-addon" style="padding-right:8px;">
                <span data-toggle="tooltip" data-placement="top" onclick="project_create();" class="badge badge-primary pull-right new-b" style="cursor:pointer; margin: 6px -5px 4px 8px;">
                    <div><i class="fa fa-plus"></i></div>
                </span>
            </span>
        </div>
    </div>';

echo '</div>';

?>