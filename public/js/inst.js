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
