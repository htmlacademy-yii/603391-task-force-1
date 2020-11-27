$('select.town-select').change(function () {
    const id = $(this).val();
    $.ajax({
        type: 'GET',
        url: '/site/city/' + id
    });
});