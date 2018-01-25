$(document).ready(function() {
    var triggeredByChild = false;

    $('#checkall').on('ifChecked', function (event) {
        $('.checker input[type="checkbox"]').iCheck('check');
        triggeredByChild = false;
    });

    $('#checkall').on('ifUnchecked', function (event) {
        if (!triggeredByChild) {
            $('.checker input[type="checkbox"]').iCheck('uncheck');
        }
        triggeredByChild = false;
    });
// Removed the checked state from "All" if any checkbox is unchecked
    $('.checker input[type="checkbox"]').on('ifUnchecked', function (event) {
        triggeredByChild = true;
        $('#checkall').iCheck('uncheck');
    });

    $('.checker input[type="checkbox"]').on('ifChecked', function (event) {
        if ($('.checker input[type="checkbox"]').filter(':checked').length == $('.check').length) {
            $('#checkall').iCheck('check');
        }
    });
    $('#slimscrollside').slimscroll({
        height: '700px',
        size: '3px',
        color: 'black',
        opacity: .3
    });
    $('input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        increaseArea: '20%'
    });
});