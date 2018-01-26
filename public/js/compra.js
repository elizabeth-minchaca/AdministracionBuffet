(function ($) {
    // no se sobreescribe el namespace, si ya existe
    var _url = '';
    
    function _resetProducto() {
        $('#addCompra').hide();
        $('#marca').html('-vacío-');
        $('#categoria').html('-vacío-');
        $('#precio').html('-vacío-');
        $('#stock').html('-vacío-');
        $('#nombre').html('-vacío-');
        $('#prod_descripcion').html('-vacío-');
        $('#cantidad').val(1);
        $('#precioUnitario').val('');
        $('#codigo').val('');
    }

    $.Compra = $.Compra || {};
    $.Compra.init = function (url) {
        _url = url;

        $('#factura').on('change', function (e) {
            $("#facturaImgDesc strong").html(e.target.files[0].name);
            $("#facturaImgDesc span").html(" (" + e.target.files[0].size + " bytes - " + e.target.files[0].type + ")");
        });

        $("#search").click(function (event) {
            event.preventDefault();
            $.Compra.search();
        });

        function mostrarImagen(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img_destino').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        $('#detalleNewForm').submit(function (event) {
            event.preventDefault();
            $.Compra.agregarDetalle();
        });

        $("#factura").change(function () {
            mostrarImagen(this);
        });
        
//        $('#addCompra').on('click', function (event) {
//            event.preventDefault();
//            $.Compra.agregarDetalle();
//        });
//        $('#compraEditForm').submit(function (event) {
//            event.preventDefault();
//            $.Compra.editarCompra();
//        });
//        
//        $('#compraNewForm').submit(function (event) {
//            event.preventDefault();
//            $.Compra.crearCompra();
//        });
       
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '&#x3c;Ant',
            nextText: 'Sig&#x3e;',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
                'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre',
                'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
                'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles',
                'Jueves', 'Viernes', 'S&aacute;bado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mi&eacute;', 'Juv', 'Vie',
                'S&aacute;b'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);

    };
    $.Compra.uploadImg = function () {
        /*var files = $(this).files;
         var file = files[0];
         alert(escape(file.name));*/
        $('#factura').click();
    };
    $.Compra.search = function () {
        if ($('#codigo').val() === '') {
            swal('Cuidado!', 'Se debe ingresar un código de barra antes.', 'warning');
            return null;
        }
        $.ajax({
            method: "POST",
            url: _url + "compra.php?action=busqueda",
            dataType: "json",
            data: {"codigo": $("#codigo").val()},
            processData: true,
            beforeSend: function () {
                $('#search').hide();
                $('img.loading').show();
                $('#addCompra').hide();
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
                    $('#cantidad').val(1);
                    $('#addCompra').show();
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

//    $.Compra.agregarDetalle = function() {
//        var _result;
//        swal({
//            title: "¿Agregar detalle a la Compra?",
//            text: "Esta por agregar un detalle de producto a la compra!",
//            type: "warning",
//            showCancelButton: true,
//            confirmButtonColor: "#3085d6",
//            cancelButtonColor: '#d33',
//            confirmButtonText: "Si, agregar!",
//            cancelButtonText: "No, cancelar!",
//            preConfirm: function () {
//                return new Promise(function (resolve, reject) {
//                    $.post(_url + "compra.php?action=submit_detalle_egreso", {"idCompra":$("#idCompra").val(), 'codigo':$("#codigo").val(), 'cantidad':$("#cantidad").val(), 'precioUnitario':$("#precioUnitario").val() }).done(function (data) {
//                        _result = data;
//                        resolve();
//                    });
//                });
//            },
//            allowOutsideClick: false
//        }).then(function () {
//            if (!_result.error) {
//                swal({
//                    type: 'success',
//                    title: 'Felicitaciones!',
//                    html: _result.msj
//                }).then(function () {
//                    $(location).attr('href', _url + "compra.php?action=editar_compra_detalle_egreso&id="+_result.id);
//                }); 
//            } else {
//                swal({
//                    type: 'error',
//                    title: 'Error!',
//                    html: _result.msj
//                });
//            }           
//        }, function (dismiss) {
//        	  if (dismiss === 'cancel') {
//                  swal({
//                      type: 'info',
//                      title: 'Cancelado!',
//                      html: ' Se canceló la agregacion del detalle de producto al menu',
//                  }).then(function () {
//                	  _resetProducto();
//                  }); 
//        	  }
//        	});
//    };

    $.Compra.agregarDetalle = function(){
        var formData = new FormData(document.getElementById("detalleNewForm"));  
        $.ajax({
            type: 'POST',
            url: _url + "compra.php?action=submit_detalle_egreso",
            data: formData,
            contentType: false,
            cache: false,
            dataType: 'json',
            processData: false
        }).done(function (data) {
        	//_result = data;
        	if(!data.error){
		          swal({
		              type: 'success',
		              title: 'Felicitaciones!',
		              html: data.msj
		          }).then(function () {
		              $(location).attr('href', _url + "compra.php?action=editar_compra_detalle_egreso&id="+data.id);
		          }); 
        		
        	}else{
            	if(data.accessError){
  		          swal({
		              type: 'error',
		              title: 'Atención!',
		              html: data.msj
		          }).then(function () {
		              $(location).attr('href', _url + "index.php?action=logout");
		          }); 
            		
            	}else{
                    swal({
                        type: 'error',
                        title: 'Error!',
                        html: data.msj
                    }).then(function () {
                    	//_resetProducto();
                        location.reload();
                    });             		
            		
            	}
        		
        	}
        	resolve();
        });      	
    };
    
    $.Compra.eliminarDetalle = function(idDetalle) {
        var _result;
        swal({
            title: "¿Eliminar Detalle?",
            text: "Esta por eliminar un detalle de la compra!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, eliminar!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.post(_url + "compra.php?action=eliminar_egreso_detalle", {'id' : idDetalle}).done(function (data) {
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
                    $(location).attr('href', _url + "compra.php?action=editar_compra_detalle_egreso&id="+_result.id);
                }); 
            } else {
                swal({
                    type: 'error',
                    title: 'Error!',
                    html: _result.msj
                });
            }
        });
    };
    
    $.Compra.finalizarCompra = function(idCompra) {
        var _result;
        swal({
            title: "¿Finalizar Compra?",
            text: "Esta por finalizar la compra!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, finalizar!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.post(_url + "compra.php?action=finalizar", {"id":idCompra}).done(function (data) {
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
                    $(location).attr('href', _url + "compra.php?action=ver_compra&id="+_result.id);
                }); 
            } else {
                swal({
                    type: 'error',
                    title: 'Error!',
                    html: _result.msj
                });
            }
        });
    };
    
    $.Compra.editarCompra = function() {
        var _result;
        swal({
            title: "¿Editar Compra?",
            text: "Esta por editar una compra!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, editar!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    var formData = new FormData(document.getElementById("compraEditForm"));  
                    $.ajax({
                        type: 'POST',
                        url: _url + "compra.php?action=submit_editar_compra",
                        data: formData,
                        contentType: false,
                        cache: false,
                        dataType: 'json',
                        processData: false
                    }).done(function (data) {
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
                    $(location).attr('href', _url + "compra.php?action=editar_compra_detalle_egreso&id="+_result.id);
                }); 
            } else {
                swal({
                    type: 'error',
                    title: 'Error!',
                    html: _result.msj
                });
            }
        }, function (dismiss) {
        	var id = $("#idCompra").val();
        	  if (dismiss === 'cancel') {
                  swal({
                      type: 'info',
                      title: 'Cancelado!',
                      html: 'Se canceló la modificacion de la compra',
                  }).then(function () {
                      $(location).attr('href', _url + "compra.php?action=editar_compra_detalle_egreso&id="+id);
                  }); 
        	  }
        });
    };

    $.Compra.crearCompra = function() {
        var _result;
        swal({
            title: "¿Crear Compra?",
            text: "Esta por crear una nueva compra!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, crear!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    var formData = new FormData(document.getElementById("compraNewForm"));  
                    $.ajax({
                        type: 'POST',
                        url: _url + "compra.php?action=submit_compra",
                        data: formData,
                        contentType: false,
                        cache: false,
                        dataType: 'json',
                        processData: false
                    }).done(function (data) {
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
                    $(location).attr('href', _url + "compra.php?action=editar_compra_detalle_egreso&id="+_result.id);
                }); 
            } else {
                swal({
                    type: 'error',
                    title: 'Error!',
                    html: _result.msj
                });
            }
        }, function (dismiss) {
        	  if (dismiss === 'cancel') {
                  swal({
                      type: 'info',
                      title: 'Cancelado!',
                      html: 'Se canceló la creacion de una nueva compra',
                  }).then(function () {
                      $(location).attr('href', _url + "compra.php");
                  }); 
        	  }
        });
    };

    $.Compra.eliminarCompra = function(idCompra) {
        var _result;
        swal({
            title: "¿Eliminar Compra?",
            text: "Esta por eliminar una compra!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, eliminar!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.post(_url + "compra.php?action=eliminar_compra", {"id" : idCompra}).done(function (data) {
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
                    $(location).attr('href', _url + "compra.php");
                }); 
            } else {
                swal({
                    type: 'error',
                    title: 'Error!',
                    html: _result.msj
                });
            }
        });
    };

})(jQuery);

//$(document).ready(function() {
//	$.Compra.init();
//	$("#datepicker").datepicker({
//		changeMonth : true
//	});
//
//});
