function typefunc() {
    var protocol = $('#type').val().split(' ')[1];
    $('#emptyoption').remove();
    console.log(protocol);
    if (protocol == 'SER') {
        $('#NET').find('input').prop('disabled', true);
        $('#SER').find('select').prop('disabled', false);
    } else if (protocol == 'NET') {
        $('#NET').find('input').prop('disabled', false);
        $('#SER').find('select').prop('disabled', true);
    }
}
$('#type').change(
    typefunc
);

function ValidateIPaddress(ipaddress) {
    if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(myForm.emailAddr.value)) {
        return (true)
    }
    return (false)
}


function isnotfilled() {
    var x = $('#type').val() && $('#name').val();
    if ($('#serialport').prop('disabled')) {
        x = x && !isNaN($('#netport').val()) && ($('#netport').val().length == 4) && ValidateIPaddress($('#ip').val());
    } else {
        x = x && $('#serialport').val();
    }
    return x;
}
$('form').submit(function (e) {
    if (! isnotfilled()) {
        e.preventDefault();
    } else {
        if ($(this).hasClass('submitted')) {
            e.preventDefault();
        } else {
            $('#submit').html('<i class="fa fa-spinner fa-spin m-2 ml-3"></i>');
            $('form').addClass('submitted');
        }
    }
});

$('#autoip').change(function () {
    if ($(this).prop('checked')) {
        $('#ip').prop('disabled', true);
    } else {
        $('#ip').prop('disabled', false);
    }

});
