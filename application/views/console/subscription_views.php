
<style>
    .ajax-frame {
        width: 100%;
        height: 310px;
        border: 0;
    }
</style>
<script>

    //We'll also have all the JS here to have them close to each other...

    var is_compact = (is_mobile() || $(window).width()<767);

    $(document).ready(function() {

        if(is_compact){

            //Adjust columns:
            $('.cols').removeClass('col-xs-6').addClass('col-sm-6');
            $('.fixed-box').addClass('release-fixture');
            $('.dash').css('margin-bottom', '0px'); //For iframe to show better

        } else {

            //Adjust height of the messaging windows:
            $('.grey-box-w').css('height', (parseInt($( window ).height())-190)+'px');
            $('.grey-box').css('max-height', (parseInt($( window ).height())-190)+'px');

            $('.ajax-frame').css('height', (parseInt($( window ).height())-215)+'px');
            $('.ajax-frame').css('max-height', (parseInt($( window ).height())-215)+'px');

            //Make editing frames Sticky for scrolling longer lists
            $(".main-panel").scroll(function() {
                var top_position = $(this).scrollTop();
                clearTimeout($.data(this, 'scrollTimer'));
                $.data(this, 'scrollTimer', setTimeout(function() {
                    $(".fixed-box").css('top',(top_position-0)); //PX also set in style.css for initial load
                }, 34));
            });

        }

        if(window.location.hash){
            var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
            var hash_parts = hash.split("-");
            if(hash_parts.length>=2){
                //Fetch level if available:
                if(hash_parts[0]=='wactionplan'){
                    load_w_actionplan(hash_parts[1],hash_parts[2]);
                } else if(hash_parts[0]=='wengagements'){
                    load_u_engagements(hash_parts[1],hash_parts[2]);
                }
            }
        }


    });

    function frame_loader(w_id, u_id, hide_intent=false){

        //Start loading:
        $('.fixed-box, .ajax-frame').addClass('hidden');
        $('#load_w_frame, .frame-loader').removeClass('hidden');

        //Construct title:
        var w_entity = null;
        if(u_id>0 && $('.u_full_name_'+u_id+':first').length){
            w_entity = $('.u_full_name_'+u_id+':first').text();
        }

        var w_intent = null;
        if(w_id>0 && $('.w_intent_'+w_id).length){
            w_intent = ( w_entity ? ' / ' : '' );
            w_intent = w_intent + $('.w_intent_'+w_id).text();
        }

        return ( w_entity ? w_entity : '' )+( w_intent && !hide_intent ? w_intent : '' );

    }


    function confirm_w_delete(w_id){
        var r = confirm("Are you sure you want to permanently delete this subscription?");
        if (r == true) {

            //Make ajax call and remove item:
            $.post("/my/w_delete/"+w_id, {}, function (data) {
                if (data.status) {
                    //Hide boxed:
                    $('.frame-loader').addClass('hidden');

                    //Remove frame
                    $('#w_div_'+w_id).html('<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Deleted</span>');
                    setTimeout(function () {
                        $('#w_div_'+w_id).fadeOut();
                    }, 377);
                }
            });
        }
    }


    function load_w_actionplan(w_id,u_id){

        w_id = parseInt(w_id);
        u_id = parseInt(u_id);
        var frame_title = frame_loader(w_id,u_id);
        $('#w_title').html('<i class="fas fa-flag"></i> '+frame_title);

        //Is this user an admin? if so, give them a delete option:
        if(jQuery.inArray(1281, js_inbound_u_ids) !== -1){
            //Append delete button:
            $('#w_title').prepend('<a href="javascript:void(0);" onclick="confirm_w_delete('+w_id+')" data-toggle="tooltip" title="Permanently delete this subscription and its related data" data-placement="bottom"><i class="fas fa-trash-alt" style="color:#FFF;"></i></a> &nbsp;');
        }

        //Add via Ajax:
        $.post("/my/load_w_actionplan", { w_id:w_id }, function (data) {
            if (data.status) {

                //Load content:
                $('.frame-loader').addClass('hidden');
                $('.ajax-frame').attr('src',data.url).removeClass('hidden').css('margin-top','-25px');

                //Tooltips:
                $('[data-toggle="tooltip"]').tooltip();

            } else {
                //We had an error:
                alert('Error Loading Subscription Data: ' + data.message);
            }
        });
    }

</script>


<div id="load_w_frame" class="fixed-box hidden">
    <h5 class="badge badge-h badge-h-max" id="w_title"></h5>
    <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;"><a href="javascript:void(0)" onclick="$('#load_w_frame').addClass('hidden');$('#loaded-ws').html('');"><i class="fas fa-times-circle"></i></a></div>
    <div class="grey-box grey-box-w" style="padding-bottom: 10px;"><iframe class="ajax-frame hidden" src=""></iframe><span class="frame-loader hidden"><img src="/img/round_load.gif" class="loader" /> Loading...</span></div>
</div>

