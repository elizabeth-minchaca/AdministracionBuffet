(function ($) {
    var _url = '';
    $.Producto = $.Producto || {};
    $.Producto.init = function (url) {
        _url = url;
//        $("#search").click(function (event) {
//            event.preventDefault();
//            $.Menu.search();
//        });
//        
//        $('#detalleNewForm').submit(function (event) {
//            event.preventDefault();
//            $.Menu.agregarDetalle();
//        });

    };
    
    $.Producto.eliminarProducto = function(idProd) {
        var _result;
        swal({
            title: "¿Eliminar Producto?",
            text: "Esta por eliminar un producto!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, eliminar!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.post(_url + "producto.php?action=eliminar_producto", {"id" : idProd}).done(function (data) {
                        _result = data;
                        resolve();
                    });
                });
            },
            allowOutsideClick: false
        }).then(function () {
            if (!_result.error) {
                swal({
                    type: 'success',
                    title: 'Felicitaciones!',
                    html: _result.msj
                }).then(function () {
                   // $(location).attr('href', _url + "producto.php");
                    location.reload();
                }); 
            } else {
                swal({
                    type: 'error',
                    title: 'Error!',
                    html: _result.msj
                }).then(function () {
                    location.reload();
                });             		
            }
        });
    };

	
})(jQuery);
