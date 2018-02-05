<script>

    $(document).ready(function() {


        if(window.location.hash){
            focus_hash(window.location.hash);
        }

        //Load leaderboard on start:
        load_leaderboard($('#r_id').val());

        $("#class_focus").on("change", function(){
            load_leaderboard($(this).val());
        });

        adjust_chat_height();
        $(window).on('resize', function(){
            adjust_chat_height();
        });

        //TODO Only enable for faster testing:
        //$("#class_focus").on("focus", function(){load_leaderboard($(this).val());});

    });

    function adjust_chat_height(){
        var min_chat_height = 420; //yeaaah
        var window_height = $(window).height();
        var chat_height = window_height - 266;
        if(chat_height<min_chat_height){
            chat_height = min_chat_height;
        }
        $('#chatwidget').css('height',chat_height);
    }

    function load_leaderboard(r_id){

        //Show spinner:
        $('#leaderboard_html').hide().fadeIn().html('<img src="/img/round_load.gif" style="margin:0 0 80px 0;" class="loader" />');

        //Save the rest of the content:
        $.post("/api_v1/load_leaderboard", {

            //Object IDs:
            b_id:$('#b_id').val(),
            r_id:r_id,
            is_instructor:1, //To load more columns compared to what students see!

        } , function(data) {

            //Update UI to confirm with user:
            $('#leaderboard_html').html(data).hide().fadeIn();

            //Activate Tooltip:
            $('[data-toggle="tooltip"]').tooltip();

        });
    }

</script>


<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />

<div class="help_body maxout below_h" id="content_2275"></div>

<ul id="topnav" class="nav nav-pills nav-pills-primary full-width">
    <li id="nav_chat" class="active"><a href="#chat"><i class="fa fa-comments" aria-hidden="true"></i> Chat</a></li>
    <li id="nav_leaderboard"><a href="#leaderboard"><i class="fa fa-trophy" aria-hidden="true"></i> Leaderboard</a></li>
</ul>

<div class="tab-content tab-space full-width">

    <div class="tab-pane active full-width" id="tabchat">
        <?= '<iframe src="https://chat.mench.co/?bootcampId='.$bootcamp['b_id'].'&instructorId='.$udata['u_id'].'&token='.md5($bootcamp['b_id'].'ChatiFrameS@lt'.$udata['u_id']).'" width="100%" id="chatwidget" frameborder="0" style="overflow:hidden; border:0; padding:0; margin:0;" scrolling="no"></iframe>'; ?>
    </div>

    <div class="tab-pane" id="tableaderboard">

        <?php
        //Generate list of classes based on the format accepted by the echo_status_dropdown() function
        $classes_array = array();
        $focus_class_id = 0;

        foreach($bootcamp['c__classes'] as $class){
            if($class['r_status']>=1){
                if(!$focus_class_id && date_is_past($class['r_start_date'])){
                    $focus_class_id = $class['r_id'];
                }
                $classes_array[$class['r_id']] = $class;
            }
        }

        //Did we find any? If not, lets grab the very first class this time:
        if(!$focus_class_id){
            foreach($bootcamp['c__classes'] as $class){
                if($class['r_status']>=1){
                    if(!$focus_class_id){
                        $focus_class_id = $class['r_id'];
                    }
                }
            }
        }


        if(count($classes_array)>0){

            ?>
            <input type="hidden" id="r_id" value="<?= $focus_class_id ?>" />

            <div class="title"><h4>Leaderboard for Class:
                    <span class="inlineform"><select id="class_focus" class="form-control input-mini border" style="display:inline !important;">
                    <?php
                    foreach($classes_array as $class){
                        echo '<option value="'.$class['r_id'].'" '.( $class['r_id']==$focus_class_id ? ' selected="selected"' : '' ).'>'.time_format($class['r_start_date'],2).' ('.$class['r__confirmed_admissions'].' Student'.show_s($class['r__confirmed_admissions']).')</option>';
                    }
                    ?>
                    </select></span> <span id="hb_2826" class="help_button" intent-id="2826"></span></h4></div>


            <div class="help_body maxout" id="content_2826"></div>
            <div id="leaderboard_html"></div>
            <br />

            <?php

        } else {

            echo '<div class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No active classes found! <a href="/console/'.$bootcamp['b_id'].'/classes"><b>Manage Classes &raquo;</b></a></div>';

        }
        ?>

    </div>

</div>

