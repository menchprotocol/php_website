

$(document).ready(function() {

    //Load search:
    $(".en-search").on('autocomplete:selected', function (event, suggestion, dataset) {

        //Update to selected value:
        $(this).val('@' + suggestion.alg_obj_id + ' ' + suggestion.alg_obj_name );

        //Update the contributor metadata:
        search_contributor($(this).attr('contributor-box'));

    }).autocomplete({hint: false, minLength: 2, keyboardShortcuts: ['a']}, [{

        source: function (q, cb) {
            algolia_index.search(q, {
                filters: 'alg_obj_is_in=0', //Only search for entities
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
        }

    }]);

    //Watch for counter changes:
    $( ".characterLimiter" ).keyup(function() {
        ln_content_word_count('.textarea_'+$(this).attr('id-postfix'),'#char_count_'+$(this).attr('id-postfix'));
    });


    //Show/Hide parent descriptions when checked:
    $('.source_parent_ens').change(function() {
        if($(this).is(":checked")) {
            $('.extra_info_' + $(this).val()).removeClass('hidden');
        } else {
            $('.extra_info_' + $(this).val()).addClass('hidden');
        }
    });


    if($('#source_url').val().length > 0){
        en_add_source_paste_url();
    }

    //Watchout for source URL change
    var textInput = document.getElementById('source_url');

    // Init a timeout variable to be used below
    var timeout = null;

    // Listen for keystroke events
    textInput.onkeyup = function (e) {

        // Clear the timeout if it has already been set.
        // This will prevent the previous step from executing
        // if it has been less than <MILLISECONDS>
        clearTimeout(timeout);

        // Make a new timeout set to go off in 800ms
        timeout = setTimeout(function () {
            en_add_source_paste_url();
        }, 610);
    };


});



function en_add_source_paste_url() {

    var input_url = $('#source_url').val();
    $('#cleaned_url').html('');

    if(input_url.length > 0){

        //Show loading icon:
        $('.url-error').html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        $('.url-parsed').addClass('hidden');

        //Send for processing to see if all good:
        $.post("/entities/en_add_source_paste_url", { input_url:input_url }, function (data) {

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
                $('#en_name_url').val(data.js_url_entity.page_title);

                if(data.js_url_entity.cleaned_url != input_url){
                    //URL has been cleaned, show the new version as well:
                    $('#cleaned_url').html('<i class="fas fa-exchange"></i> <a data-toggle="tooltip" title="Found Canonical URL: ' + data.js_url_entity.cleaned_url + '" data-placement="top" href="' + data.js_url_entity.cleaned_url + '" target="_blank" class="url_truncate" style="max-width:520px; text-decoration:underline;">' + data.js_url_entity.cleaned_url + '</a>');
                }

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


function en_add_source_process(){

    //Compile parent entities and their descriptions:
    var source_parent_ens = $(".source_parent_ens:checkbox:checked").map(function(){
        //Return a multi-dimensional array:
        return {
            'this_parent_en_id': $(this).val(),
            'this_parent_en_desc': $('#en_desc_' + $(this).val()).val(),
        };
    }).get();

    //Set title:
    $('.add_source_body').addClass('hidden');
    $('.add_source_result').html('<div class="center"><span><i class="fas fa-spinner fa-spin"></i></span> Processing...</div>');

    //Fetch Intent Data to load modify widget:
    $.post("/entities/en_add_source_process", {

        source_url: $('#source_url').val(),
        source_parent_ens: source_parent_ens,
        en_name: $('#en_name_url').val(),
        en_desc: $('#en_desc').val(),

        contributor_1             : $('#contributor_1').val(),
        auth_role_1          : $('#auth_role_1').val(),
        entity_parent_id_1   : $('#entity_parent_id_1').val(),
        ref_url_1            : $('#ref_url_1').val(),
        why_expert_1         : $('#why_expert_1').val(),


        contributor_2             : $('#contributor_2').val(),
        auth_role_2          : $('#auth_role_2').val(),
        entity_parent_id_2   : $('#entity_parent_id_2').val(),
        ref_url_2            : $('#ref_url_2').val(),
        why_expert_2         : $('#why_expert_2').val(),

        contributor_3             : $('#contributor_3').val(),
        auth_role_3          : $('#auth_role_3').val(),
        entity_parent_id_3   : $('#entity_parent_id_3').val(),
        ref_url_3            : $('#ref_url_3').val(),
        why_expert_3         : $('#why_expert_3').val(),

        contributor_4             : $('#contributor_4').val(),
        auth_role_4          : $('#auth_role_4').val(),
        entity_parent_id_4   : $('#entity_parent_id_4').val(),
        ref_url_4            : $('#ref_url_4').val(),
        why_expert_4         : $('#why_expert_4').val(),

        contributor_5             : $('#contributor_5').val(),
        auth_role_5          : $('#auth_role_5').val(),
        entity_parent_id_5   : $('#entity_parent_id_5').val(),
        ref_url_5            : $('#ref_url_5').val(),
        why_expert_5         : $('#why_expert_5').val(),

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



function search_contributor(contributor_box){

    if(contributor_box < 1){
        //Invalid!
        return false;
    }

    //What's the current search value?
    var current_val = $('#contributor_'+contributor_box).val();

    if(current_val.length > 0 && current_val.indexOf('@') == -1 ) {

        //Populate Google Search Link:
        $('#google_' + contributor_box).attr('href','https://www.google.com/search?q='+current_val);

        $('.contributor_is_expert_' + contributor_box).removeClass('hidden');
        $('.explain_expert_' + contributor_box).removeClass('hidden');

        $('#ref_url_' + contributor_box).attr('placeholder', 'URL referencing '+( current_val.length > 0 ? current_val : 'this entity' )+'...');
        $('#why_expert_' + contributor_box).attr('placeholder', 'List expertise/accomplishments of '+( current_val.length > 0 ? current_val : 'this entity' )+'...');

    } else{

        //Hhide all options
        $('.contributor_is_expert_' + contributor_box).addClass('hidden');
        $('.explain_expert_' + contributor_box).addClass('hidden');
    }


    //Role box:
    if(current_val.length > 0 ) {

        $('.en_role_' + contributor_box).removeClass('hidden');

    } else{

        $('.en_role_' + contributor_box).addClass('hidden');

    }




}