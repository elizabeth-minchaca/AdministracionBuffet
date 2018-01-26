(function ($) {
    function _calcularPrecio() {
        var _total = 0;
        $("tbody tr input:checked").each(function () {
            var _elem = $(this).parent().parent();
            _total += parseFloat(_elem.find('input:hidden.precioUnitario').val()) * parseInt(_elem.find('input:hidden.cantidad').val());
        });
        $('tfoot span.precioMenu').html(_total);
    }

    // no se sobreescribe el namespace, si ya existe  
    $.Pedido = $.Pedido || {};
    $.Pedido.init = function () {
        $("tbody tr").click(function (event) {
            $(this).find('td input:checkbox').click();
        });
        $('tbody tr td input:checkbox').on('change', function () {
            _calcularPrecio();
        });
        $('#pedido_aceptar').on('click', function (event) {
            event.preventDefault();
            swal({
                title: 'Estas seguro?',
                text: "Se va a aceptar el pedido!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar!',
                cancelButtonText: 'No, cancelar!',
                confirmButtonClass: 'btn btn-lg btn-success',
                cancelButtonClass: 'btn btn-lg btn-danger'
            }).then(function () {
                $("form:first").submit();
            }, function (dismiss) {});

        });
        $('#pedido_submit').on('click', function (event) {
            event.preventDefault();
            if ($('form input:checked').length == 0) {
                swal('Atención!', 'Debe seleccionar al menos un producto del menú', 'warning');
            } else {
                swal({
                    title: 'Estas seguro?',
                    text: "Se va a realizar el pedido!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, realizar!',
                    cancelButtonText: 'No, cancelar!',
                    confirmButtonClass: 'btn btn-lg btn-success',
                    cancelButtonClass: 'btn btn-lg btn-danger'
                }).then(function () {
                    $("form:first").submit();
                }, function (dismiss) {});
            }
        });
        $('#pedido_cancelar').on('click', function (event) {
            event.preventDefault();
            swal({
                title: 'Estas seguro?',
                text: "Se va a cancelar el pedido online!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar!',
                cancelButtonText: 'No, abortar!',
                confirmButtonClass: 'btn btn-lg btn-success',
                cancelButtonClass: 'btn btn-lg btn-danger'
            }).then(function () {
                $("form:first").submit();
            }, function (dismiss) {});
        });
    };
})(jQuery);
