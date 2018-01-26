(function ($) {
    // no se sobreescribe el namespace, si ya existe  
    $.Usuario = $.Usuario || {};
    $.Usuario.init = function () { 
        $('#rol').change(function () {
            if ($('#rol option:selected').html() === 'USUARIO ONLINE') {
                $('#contenido_ubicacion').show('slow');
                $('#ubicacion').attr('required', 'required');

            } else {
                $('#contenido_ubicacion').hide('slow');
                $('#ubicacion').removeAttr('required');
            }
        });
        $(".eliminar_usuario").on('click',function (event) {
            event.preventDefault();
            url = $(this).attr('href');
            swal({
                title: 'Estas seguro?',
                text: "Se va a eliminar el usuario seleccionado!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonText: 'No, cancelar!',
                confirmButtonClass: 'btn btn-lg btn-success',
                cancelButtonClass: 'btn btn-lg btn-danger'
            }).then(function () {
                 window.location = url;
            }, function (dismiss) {

            });
        });
    };
})(jQuery);
