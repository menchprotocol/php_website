

$(document).ready(function () {
    load_leaderboard();
});

function load_leaderboard(){
    //Show loading icon:
    $('#load_leaderboard').html('<div class="alert montserrat source" style="background-color: #FFFFFF;"><span class="icon-block"><i class="far fa-yin-yang fa-spin source"></i></span>LOADING...</div>');
    $('.top-sources').addClass('hidden');

    $.post("/source/load_leaderboard/", { }, function (data) {
        $('#load_leaderboard').html(data);
        $('[data-toggle="tooltip"]').tooltip();
    });
}
