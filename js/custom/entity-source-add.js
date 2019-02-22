

$(document).ready(function() {

    //Load search:
    $(".en-search").on('autocomplete:selected', function (event, suggestion, dataset) {

        //Update to selected value:
        $(this).val('@' + suggestion.alg_obj_id + ' ' + suggestion.alg_obj_name );

        //Update the author metadata:
        search_author($(this).attr('author-box'));

    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{

        source: function (q, cb) {
            algolia_index.search(q, {
                filters: 'alg_obj_is_in=0 AND (_tags:tag_en_1278 OR _tags:tag_en_2750)', //Only search people or organizations
                hitsPerPage: 7,
            }, function (error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        displayKey: function (suggestion) {
            return '@' + suggestion.alg_obj_id + ' ' + suggestion.alg_obj_name
        },
        templates: {
            suggestion: function (suggestion) {
                return echo_js_suggestion(suggestion, 0);
            },
            header: function (data) {
                if (!data.isEmpty) {
                    return '';
                }
            },
            empty: function (data) {
                return '';
            },
        }

    }]);



    //Watchout for source URL change
    var textInput = document.getElementById('source_url');

    // Init a timeout variable to be used below
    var timeout = null;

    // Listen for keystroke events
    textInput.onkeyup = function (e) {

        // Clear the timeout if it has already been set.
        // This will prevent the previous task from executing
        // if it has been less than <MILLISECONDS>
        clearTimeout(timeout);

        // Make a new timeout set to go off in 800ms
        timeout = setTimeout(function () {
            fn___add_source_paste_url();
        }, 377);
    };


});



function fn___add_source_paste_url() {

    var input_url = $('#source_url').val();

    if(input_url.length > 0){

        //Show loading icon:
        $('.url-error').html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        $('.url-parsed').addClass('hidden');

        //Send for processing to see if all good:
        $.post("/entities/fn___add_source_paste_url", { input_url:input_url }, function (data) {

            //For admin debugging and URL analysis:
            console.log("Admin URL Analysis:");
            console.log(data.digested_url);

            //Update sorts in both lists:
            if (!data.status) {

                //There was some sort of an error returned:
                $('.url-error').html('<span style="color:#FF0000;">Error: '+ data.message +'</span>');

            } else {

                //All good, show content:
                $('.url-error').html('');
                $('.url-parsed').removeClass('hidden');

                //Update input fields:
                $('.entity_domain_ui').html(data.entity_domain_ui);
                $('#en_name_url').val(data.page_title);

                //Load tooldip:
                $('[data-toggle="tooltip"]').tooltip();

            }
        });

    } else {
        //Waiting for input:
        $('.url-error').html('Paste a URL to get started...');
        $('.url-parsed').addClass('hidden');
    }

}


function fn___add_source_process(){

    //Set title:
    $('.add_source_body').addClass('hidden');
    $('.add_source_result').html('<span><i class="fas fa-spinner fa-spin"></i></span> Processing...');

    var source_parent_ens = $(".source_parent_ens:checkbox:checked").map(function(){
        return $(this).val();
    }).get();

    //Fetch Intent Data to load modify widget:
    $.post("/entities/fn___add_source_process", {

        source_url: $('#source_url').val(),
        source_parent_ens: source_parent_ens,
        en_name: $('#en_name_url').val(),

        author_1             : $('#author_1').val(),
        entity_parent_id_1   : $('#entity_parent_id_1').val(),
        ref_url_1            : $('#ref_url_1').val(),
        why_expert_1         : $('#why_expert_1').val(),

        author_2             : $('#author_2').val(),
        entity_parent_id_2   : $('#entity_parent_id_2').val(),
        ref_url_2            : $('#ref_url_2').val(),
        why_expert_2         : $('#why_expert_2').val(),

        author_3             : $('#author_3').val(),
        entity_parent_id_3   : $('#entity_parent_id_3').val(),
        ref_url_3            : $('#ref_url_3').val(),
        why_expert_3         : $('#why_expert_3').val(),

    }, function (data) {

        if (!data.status) {

            //Opppsi, show the error:
            $('.add_source_result').html('<span style="color:#FF0000;">Error: '+ data.message +'</span>');
            $('.add_source_body').removeClass('hidden');

        } else {

            //All good, go to newly added source:
            window.location = '/entities/' + data.new_source_id;

        }
    });

}



function search_author(author_box){

    if(author_box < 1){
        //Invalid!
        return false;
    }

    //What's the current search value?
    var current_val = $('#author_'+author_box).val();

    if(current_val.length > 0 && current_val.indexOf('@') == -1 ) {

        $('.author_is_expert_' + author_box).removeClass('hidden');
        $('.explain_expert_' + author_box).removeClass('hidden');

        $('#ref_url_' + author_box).attr('placeholder', 'URL referencing the bio of '+( current_val.length > 0 ? current_val : 'this entity' )+'...');
        $('#why_expert_' + author_box).attr('placeholder', 'If so, list accomplishments supporting the expertise of '+( current_val.length > 0 ? current_val : 'this entity' )+'...');

    } else{

        //Hhide all options
        $('.author_is_expert_' + author_box).addClass('hidden');
        $('.explain_expert_' + author_box).addClass('hidden');
        $('#why_expert_' + author_box).val('');
        $('#ref_url_' + author_box).val('');
    }

}