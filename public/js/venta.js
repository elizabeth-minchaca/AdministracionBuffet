(function ($) {

    var _url = '';

    function _footerReload() {
        var _sum = 0;
        $('table .td-precio').each(function (index) {
            _str = $(this).html();
            _float = parseFloat(_str.substring(1, _str.length));
            _elem = $(this).parent().find('td.td-cantidad');
            _sum += _float * parseInt(_elem.html());
        });
        $('tfoot .total-valor').html('$' + _sum);
    }

    function _indexReload() {
        var _cant = 1;
        $('table tbody tr[class!=tr-vacio]').each(function (index) {
            $(this).find('th:first-child').html(_cant++);
        });
    }

    function _addVenta() {
        var _tr = $('<tr></tr>');
        _tr.append('<th class="text-center">' + $('table tbody tr').size() + '</th>');
        _tr.append('<td class="text-center td-codigo">' + $('#codigo').val() + '</td>');
        _tr.append('<td class="text-center">' + $('#marca').html() + '</td>');
        _tr.append('<td class="text-center">' + $('#nombre').html() + '</td>');
        _tr.append('<td class="text-center td-descripcion">' + $('#descripcion').val() + '</td>');
        _tr.append('<td class="text-center td-precio">' + $('#precio').html() + '</td>');
        _tr.append('<td class="text-center td-cantidad">' + $('#cantidad').val() + '</td>');
        _tr.append('<td class="text-center td-accion"><a class="btn btn-danger eliminar-detalle" onclick="event.preventDefault(); $.Venta.eliminarDetalle($(this));"  title="Eliminar detalle" href="#"> <i class="fa fa-trash" aria-hidden="true"></i></a></td>');
        $('table tbody tr.tr-vacio').hide();
        $('table tbody').append(_tr);
        $('tfoot').show();
        _footerReload();
    }

    function _resetProducto() {
        $('#addVenta').hide();
        $('#marca').html('-vacío-');
        $('#categoria').html('-vacío-');
        $('#precio').html('-vacío-');
        $('#stock').html('-vacío-');
        $('#nombre').html('-vacío-');
        $('#prod_descripcion').html('-vacío-');
        $('#cantidad').val(1);
        $('#descripcion').val('');
        $('#codigo').val('');
    }
    function _resetVenta() {
        $('table tbody tr[class!=tr-vacio]').each(function (index) {
            $(this).remove();
        });
        $('tfoot').hide();
        $('table tbody tr.tr-vacio').show();
        _resetProducto();
    }

    // no se sobreescribe el namespace, si ya existe  
    $.Venta = $.Venta || {};
    $.Venta.init = function (url) {
        _url = url;
        $(".boton-venta").click(function (event) {
            event.preventDefault();
        });
        $("#search").click(function (event) {
            event.preventDefault();
            $.Venta.search();
        });
        $('#addVenta').on('click', function (event) {
            event.preventDefault();
            _addVenta();
            _resetProducto();
            $('#codigo').focus();

        });
    };
    $.Venta.eliminarDetalle = function (elemento) {
        swal({
            title: 'Estas seguro?',
            text: "Se va a eliminar el detalle seleccionado!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar!',
            confirmButtonClass: 'btn btn-lg btn-success',
            cancelButtonClass: 'btn btn-lg btn-danger'
        }).then(function () {
            elemento.closest("tr").remove();
            if ($('table tbody tr[class!=tr-vacio]').length === 0) {
                $('tfoot').hide();
                $('table tbody tr.tr-vacio').show();
            }
            _footerReload();
            _indexReload();
        }, function (dismiss) {

        });
    };
    $.Venta.search = function () {
        if ($('#codigo').val() === '') {
            swal('Cuidado!', 'Se debe ingresar un código de barra antes.', 'warning');
            return null;
        }
        $.ajax({
            method: "POST",
            url: _url + "venta.php?action=busqueda",
            dataType: "json",
            data: {"codigo": $("#codigo").val()},
            processData: true,
            beforeSend: function () {
                $('#search').hide();
                $('img.loading').show();
                $('#addVenta').hide();
            },
            success: function (data, textStatus, jqXHR) {
                $('img.loading').hide();
                $('#search').show();
                if (data.encontrado) {
                    $('#categoria').html(data.categoria);
                    $('#nombre').html(data.nombre);
                    $('#marca').html(data.marca);
                    $('#stock').html(data.stock);
                    $('#precio').html('$' + data.precio);
                    $('#prod_descripcion').html(data.descripcion);
                    if (data.stock > 0) {
                        $('#addVenta').show();
                    }
                    $('#cantidad').val(1);
                } else {
                    $('#categoria').html('No encontrado');
                    $('#nombre').html('No encontrado');
                    $('#stock').html('No encontrado');
                    $('#marca').html('No encontrado');
                    $('#precio').html('No encontrado');
                    $('#prod_descripcion').html('No encontrado');
                }
            }
        });
    };
    $.Venta.inc = function () {
        $('#cantidad').val(parseInt($('#cantidad').val()) + 1);
    };
    $.Venta.dec = function () {
        if (parseInt($('#cantidad').val()) > 1) {
            $('#cantidad').val(parseInt($('#cantidad').val()) - 1);
        }
    };
    $.Venta.cancelar = function (id_venta) {
        var _result;
        swal({
            title: 'Estas seguro?',
            text: "Se va a cancelar la venta!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continuar!',
            cancelButtonText: 'Cancelar!',
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.post(_url + "venta.php?action=cancelar", {'id_venta': id_venta}).done(function (data) {
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
                    html: 'Se ha cancelado la venta correctamente'
                }).then(function () {
                    location.reload();
                });
            } else {
                swal({
                    type: 'error',
                    title: 'Error!',
                    html: 'No se ha podido cancelar la venta correctamente. Verifique sus permisos e intente nuevamente.'
                });
            }
        });
    };
    $.Venta.submit = function () {
        var _result;
        swal({
            title: 'Estas seguro?',
            text: "Se va a finalizar la venta!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Finalizar!',
            cancelButtonText: 'Cancelar!',
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    var _data = {};
                    $('table tbody tr[class!=tr-vacio]').each(function (index) {
                        var _elem = {};
                        _elem['codigo'] = $(this).children(".td-codigo").html();
                        _elem['cantidad'] = $(this).children(".td-cantidad").html();
                        _elem['descripcion'] = $(this).children(".td-descripcion").html();
                        _data[index] = _elem;
                    });
                    $.post(_url + "venta.php?action=registrar", {'data': _data}).done(function (data) {
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
                    html: 'Se ha registrado correctamente la venta'
                }).then(function () {
                    $('#codigo').focus();
                });
                _resetVenta();

            } else {
                swal({
                    type: 'error',
                    title: 'Error!',
                    html: 'No se ha podido registrar correctamente la venta. Verifique el stock de los productos e intente nuevamente.'
                });
            }

        });
    };
})(jQuery);


