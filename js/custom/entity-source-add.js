

$(document).ready(function() {



});


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