jQuery(function ($) {
    $('.select2').select2({
        theme: "bootstrap"
    });

    $('[data-index]').on('click', function () {
        var _btn = $(this);
        var _index = _btn.data('index');

        $('[data-index]').each(function () {
            $(this).removeClass('btn-primary').addClass('btn-secondary');
        });

        _btn.removeClass('btn-secondary')
            .addClass('btn-primary');

        $('[data-' + _index + ']').each(function () {
            $(this).html($(this).data(_index));
        });
    });
});