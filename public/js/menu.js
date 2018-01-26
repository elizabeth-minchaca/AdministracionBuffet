(function ($) {
    // no se sobreescribe el namespace, si ya existe
    var _url = '';
    function _resetProducto() {
        $('#addMenu').hide();
        $('#marca').html('-vacío-');
        $('#categoria').html('-vacío-');
        $('#precio').html('-vacío-');
        $('#stock').html('-vacío-');
        $('#nombre').html('-vacío-');
        $('#prod_descripcion').html('-vacío-');
        $('#cantidad').val(1);
        $('#codigo').val('');
    }

    $.Menu = $.Compra || {};
    $.Menu.init = function (url) {
        _url = url;
        $("#search").click(function (event) {
            event.preventDefault();
            $.Menu.search();
        });
        
        $('#detalleNewForm').submit(function (event) {
            event.preventDefault();
            $.Menu.agregarDetalle();
        });

//        $('#menuNewForm').submit(function (event) {
//            event.preventDefault();
//            $.Menu.crear();
//        });
//        $('#addMenu').on('click', function (event) {
//            event.preventDefault();
//            $.Menu.agregarDetalle();
//        });
//        $('#menuEditForm').submit(function (event) {
//            event.preventDefault();
//            $.Menu.editarMenu();
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
    $.Menu.search = function () {
        if ($('#codigo').val() === '') {
            swal('Cuidado!', 'Se debe ingresar un código de barra antes.', 'warning');
            return null;
        }
        $.ajax({
            method: "POST",
            url: _url + "menu.php?action=busqueda",
            dataType: "json",
            data: {"codigo": $("#codigo").val()},
            processData: true,
            beforeSend: function () {
                $('#search').hide();
                $('img.loading').show();
                $('#addMenu').hide();
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
                        $('#addMenu').show();
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

    $.Menu.enviar = function() {
        var _result;
        swal({
            title: "¿Enviar Men&uacute;?",
            text: "Esta por enviar el men&uacute; del dia a los usuarios Telegram!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, enviar!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.get(_url + "pruebasbot.php", {'action': 'enviar_menu'}).done(function (data) {
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
                    location.reload();
                });
            } else {
                swal({
                    type: 'warning',
                    title: 'Aviso!',
                    html: _result.msj
                });
            }
        });
    };
    
    $.Menu.crear = function() {
        var _result;
        swal({
            title: "¿Crear Menu?",
            text: "Esta por crear un nuevo men&uacute;!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, crear!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.post(_url + "menu.php?action=submit_menu", {"fechaMenu" : $("#datepicker").val()}).done(function (data) {
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
//                    location.reload();
                    $(location).attr('href', _url + "menu.php?action=editar_menu_detalle&id="+_result.id);

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
                      html: 'Se canceló la creacion de un nuevo menú.',
                  }).then(function () {
                      $(location).attr('href', _url + "menu.php");
                  }); 
        	  }
        });
    };
    $.Menu.editarMenu = function() {
        var _result;
        swal({
            title: "¿Editar Menu?",
            text: "Esta por editar la fecha del menu!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, editar!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.post(_url + "menu.php?action=submit_editar_menu", {"idMenu":$("#idMenu").val() , "fechaMenu" : $("#datepicker").val()}).done(function (data) {
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
                    $(location).attr('href', _url + "menu.php?action=editar_menu_detalle&id="+_result.id);

                }); 
            } else {
                swal({
                    type: 'error',
                    title: 'Error!',
                    html: _result.msj
                });
            }
        }, function (dismiss) {
        	var id = $("#idMenu").val();
        	  if (dismiss === 'cancel') {
                  swal({
                      type: 'info',
                      title: 'Cancelado!',
                      html: 'Canceló la modificacion del menu',
                  }).then(function () {
                      $(location).attr('href', _url + "menu.php?action=editar_menu_detalle&id="+id);
                  }); 
        	  }
        });
    };
    
    $.Menu.eliminarMenu = function(idMenu) {
        var _result;
        swal({
            title: "¿Eliminar Men&uacute;?",
            text: "Esta por eliminar un men&uacute;!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, eliminar!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.post(_url + "menu.php?action=eliminar_menu", {"id" : idMenu}).done(function (data) {
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
                    $(location).attr('href', _url + "menu.php");
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
    
    $.Menu.habilitarMenu = function(idMenu) {
        var _result;
        swal({
            title: "¿Habilitar Men&uacute;?",
            text: "Esta por habilitar el men&uacute;!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, habilitar!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.post(_url + "menu.php?action=habilitar", {"id":idMenu}).done(function (data) {
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
                    $(location).attr('href', _url + "menu.php?action=ver_menu&id="+_result.id);
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

//    $.Menu.agregarDetalle = function() {
//        var _result;
//        swal({
//            title: "¿Agregar detalle al Menu?",
//            text: "Esta por agregar un detalle de producto al menu!",
//            type: "warning",
//            showCancelButton: true,
//            confirmButtonColor: "#3085d6",
//            cancelButtonColor: '#d33',
//            confirmButtonText: "Si, agregar!",
//            cancelButtonText: "No, cancelar!",
//            preConfirm: function () {
//                return new Promise(function (resolve, reject) {
//                    $.post(_url + "menu.php?action=submit_detalle", {"idMenu":$("#idMenu").val(), 'codigo':$("#codigo").val(), 'cantidad':$("#cantidad").val() }).done(function (data) {
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
//                    $(location).attr('href', _url + "menu.php?action=editar_menu_detalle&id="+_result.id);
//                }); 
//            } else {
//                swal({
//                    type: 'error',
//                    title: 'Error!',
//                    html: _result.msj
//                }).then(function () {
//                	_resetProducto();
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
   
    $.Menu.agregarDetalle = function(){
        var formData = new FormData(document.getElementById("detalleNewForm"));  
        $.ajax({
            type: 'POST',
            url: _url + "menu.php?action=submit_detalle",
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
		              $(location).attr('href', _url + "menu.php?action=editar_menu_detalle&id="+data.id);
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
    
    $.Menu.eliminarDetalle = function(idDetalle) {
        var _result;
        swal({
            title: "¿Eliminar Detalle?",
            text: "Esta por eliminar un detalle del menu!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, eliminar!",
            cancelButtonText: "No, cancelar!",
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.post(_url + "menu.php?action=eliminar_detalle", {"id" : idDetalle}).done(function (data) {
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
                    $(location).attr('href', _url + "menu.php?action=editar_menu_detalle&id="+_result.id);

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

