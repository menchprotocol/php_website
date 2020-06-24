

$(document).ready(function () {

    discover_remove();

});

function discover_clear_all(){

    $('.clear-discovery-list').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">REMOVING ALL...</b>');

    //Redirect:
    window.location = '/discover/x_clear_coins';

}
