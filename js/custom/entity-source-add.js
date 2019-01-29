

$(document).ready(function() {

    fn___en_source_paste_url();

    //Load search:
    $(".en-search").on('autocomplete:selected', function (event, suggestion, dataset) {

        //Update to selected value:
        $(this).val('@' + suggestion.en_id + ' ' + suggestion.en_name );

        //Update the author metadata:
        search_author($(this).attr('author-box'));

    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{

        source: function (q, cb) {
            algolia_en_index.search(q, {
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
            return '@' + suggestion.en_id + ' ' + suggestion.en_name
        },
        templates: {
            suggestion: function (suggestion) {
                return '<i class="fal fa-link"></i> ' + echo_js_suggestion('en',suggestion, 0);
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



    //Load search:
    $(".en-verify").on('autocomplete:selected', function (event, suggestion, dataset) {

        //Open in new window to review/verify:
        window.open('/entities/' + suggestion.en_id , '_blank');

    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{

        source: function (q, cb) {
            algolia_en_index.search(q, {
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
            return ''
        },
        templates: {
            suggestion: function (suggestion) {
                return '<i class="fal fa-external-link-alt"></i> ' + echo_js_suggestion('en',suggestion, 0);
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


});



function fn___en_source_paste_url() {

    var input_url = $('#source_url').val();

    if(input_url.length > 0){

        //Show loading icon:
        $('.url-error').html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        $('.url-parsed').addClass('hidden');

        //Send for processing to see if all good:
        $.post("/entities/fn___en_source_paste_url", { input_url:input_url }, function (data) {

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
                $('#entity_domain_id').val(data.entity_domain_id);
                $('#en_name').val(data.page_title);

            }
        });

    } else {
        //Waiting for input:
        $('.url-error').html('Paste a URL to get started...');
        $('.url-parsed').addClass('hidden');
    }

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