$('.white').click(function (e) {
    e.preventDefault();
    if (!$('#flipform').hasClass('submitted')) {
        $('#flipform').submit();
        console.log('should be submitting');
        $('#flipform').addClass('submitted');
        $(this).text('.....');
        $(this).prop('disabled',true);
    }
});
setInterval(() => {
    $.ajax({
        type: "get",
        url: "url",
        data: "",
    }).success(function () {

    });
}, 10);
