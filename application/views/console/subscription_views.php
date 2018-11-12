
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
                    load_w_actionplan(hash_parts[1]);
                } else if(hash_parts[0]=='wengagements'){
                    load_w_engagements(hash_parts[1],hash_parts[2]);
                }
            }
        }


    });

    function frame_loader(w_id, hide_intent=false){

        //Start loading:
        $('.fixed-box, .ajax-frame').addClass('hidden');
        $('#load_w_frame, .frame-loader').removeClass('hidden');

        //Construct title:
        var w_entity = null;
        if($('.w_entity_'+w_id).length){
            w_entity = $('.w_entity_'+w_id).text();
        }

        var w_intent = null;
        if($('.w_intent_'+w_id).length){
            w_intent = ( w_entity ? ' / ' : '' );
            w_intent = w_intent+$('.w_intent_'+w_id).text();
        }

        return ( w_entity ? w_entity : '' )+( w_intent && !hide_intent ? w_intent : '' );

    }


    function load_w_actionplan(w_id){

        w_id = parseInt(w_id);
        var frame_title = frame_loader(w_id);
        $('#w_title').html('<i class="fas fa-flag"></i> '+frame_title);


        //Is this user an admin? if so, give them a delete option:
        if(jQuery.inArray(1281, js_inbound_u_ids) !== -1){
            //Append delete button:
            $('#w_title').append(' &nbsp;<a href="/my/w_delete/'+w_id+'"><i class="fas fa-trash-alt" style="color:#FFF;"></i></a>');
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


    function load_w_engagements(w_id,u_id){

        w_id = parseInt(w_id);
        u_id = parseInt(u_id);
        var frame_title = frame_loader(w_id, true);
        $('#w_title').html('<i class="fas fa-exchange"></i> '+frame_title);

        //Load content via a URL:
        $('.frame-loader').addClass('hidden');
        $('.ajax-frame').attr('src','/my/load_w_engagements/'+u_id+'/'+w_id).removeClass('hidden').css('margin-top','0');

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    }

</script>


<div id="load_w_frame" class="fixed-box hidden">
    <h5 class="badge badge-h badge-h-max" id="w_title"></h5>
    <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;"><a href="javascript:void(0)" onclick="$('#load_w_frame').addClass('hidden');$('#loaded-ws').html('');"><i class="fas fa-times-circle"></i></a></div>
    <div class="grey-box grey-box-w" style="padding-bottom: 10px;"><iframe class="ajax-frame hidden" src=""></iframe><span class="frame-loader hidden"><img src="/img/round_load.gif" class="loader" /> Loading...</span></div>
</div>

