(function ($) {
    // no se sobreescribe el namespace, si ya existe  
    $.Layout = $.Layout || {};
    $.Layout.init = function () {
        $(document).ready(function () {
            $('div.alert').fadeIn(2000).delay(3000).fadeOut(3500);
            $('div.alert .fa').on('click', function () {
                $(this).closest('.alert').hide();
            });
        });
    };
})(jQuery);

$(document).ready(function () {
    $.Layout.init();
});