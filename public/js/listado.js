(function($) {
	// no se sobreescribe el namespace, si ya existe
	var _url = '';
	$.Listado = $.Listado || {};
	$.Listado.init = function(url) {
		_url = url;
      $("#showResult").hide();
      $("#listProd").html("");
     
      $('#listadoVentaForm').submit(function (event) {
          event.preventDefault();
          $.Listado.traerProductos();
      });

      $('#listadoGananciaForm').submit(function (event) {
          event.preventDefault();
          $.Listado.traerGanancias();
      });

      $("input").on('click', function(){
          $("#listProd").hide();
          $("#listadoPaginador").hide();
          $("#showResult").hide();
      }).keyup();

		$.datepicker.regional['es'] = {
			closeText : 'Cerrar',
			prevText : '&#x3c;Ant',
			nextText : 'Sig&#x3e;',
			currentText : 'Hoy',
			monthNames : [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
					'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre',
					'Noviembre', 'Diciembre' ],
			monthNamesShort : [ 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
					'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic' ],
			dayNames : [ 'Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles',
					'Jueves', 'Viernes', 'S&aacute;bado' ],
			dayNamesShort : [ 'Dom', 'Lun', 'Mar', 'Mi&eacute;', 'Juv', 'Vie',
					'S&aacute;b' ],
			dayNamesMin : [ 'Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;' ],
			weekHeader : 'Sm',
			dateFormat : 'dd/mm/yy',
			firstDay : 1,
			isRTL : false,
			showMonthAfterYear : false,
			yearSuffix : ''
		};
		$.datepicker.setDefaults($.datepicker.regional['es']);
	};
	
    $.Listado.traerProductos = function(){
//      $("#listProd").html("");	
//      $("#listadoPaginador").hide(); 
        var formData = new FormData(document.getElementById("listadoVentaForm"));  
        $.ajax({
            type: "POST",
            url: _url + "listado.php?action=buscar_ventas",
            data: formData,
            contentType: false,
            cache: false,
            dataType: "json",
            processData: false,
        })
          .done(function (data, textStatus, jqXHR) {
                $("#showResult p").html(data.msj);      
                $("#showResult").show();                                          
                if (!data.error) {
                    	$("#listProd").html(data.productos);
                        $("#listadoPaginador").html(data.paginador);
                    	$("#listProd").show();
                        $("#listadoPaginador").show();
                }else{     
                    swal({
                        type: 'warning',
                        title: 'Advertencia!',
                        html: data.msj
                    }).then(function () {
                        location.reload();
                    });
                }
           });            
           event.preventDefault();
};


$.Listado.traerPagProd = function(pagina){
        var fechaDesde = $("#datepicker").val();
        var fechaHasta = $("#datepicker2").val();
//        $("#listProd").html("");
//        $("#listadoPaginador").hide();
        $.post(_url+"listado.php?action=buscar_ventas", {"pag": pagina, "fechaDesde": fechaDesde, "fechaHasta": fechaHasta}, null, "json")
            .done(function (data, textStatus, jqXHR) {
                $("#showResult p").html(data.msj); 
                $("#showResult").show();                                          
                if (!data.error) {
                    setTimeout(function () {
                        $("#listProd").html(data.productos);
                        $("#listadoPaginador").html(data.paginador);
                      //  $("#listadoPaginador").show();
                    }, 500);                         
                }else{        
                    swal({
                        type: 'warning',
                        title: 'Advertencia!',
                        html: data.error_msj
                    }).then(function () {
                        location.reload();
                    });
                }
           });            

};


$.Listado.traerGanancias = function(){
//  $("#listProd").html("");	
//  $("#listadoPaginador").hide(); 
    var formData = new FormData(document.getElementById("listadoGananciaForm"));  
    $.ajax({
        type: "POST",
        url: _url + "listado.php?action=buscar_ganancias",
        data: formData,
        contentType: false,
        cache: false,
        dataType: "json",
        processData: false,
    })
      .done(function (data, textStatus, jqXHR) {
            $("#showResult p").html(data.msj);      
            $("#showResult").show();                                          
            if (!data.error) {
                	$("#listProd").html(data.productos);
                    $("#listadoPaginador").html(data.paginador);
                	$("#listProd").show();
                    $("#listadoPaginador").show();
            }else{     
                swal({
                    type: 'warning',
                    title: 'Advertencia!',
                    html: data.msj
                }).then(function () {
                    location.reload();
                });
            }
       });            
       event.preventDefault();
};


$.Listado.traerPagGanancias = function(pagina){
    var fechaDesde = $("#datepicker").val();
    var fechaHasta = $("#datepicker2").val();
//    $("#listProd").html("");
//    $("#listadoPaginador").hide();
    $.post(_url+"listado.php?action=buscar_ganancias", {"pag": pagina, "fechaDesde": fechaDesde, "fechaHasta": fechaHasta}, null, "json")
        .done(function (data, textStatus, jqXHR) {
            $("#showResult p").html(data.msj); 
            $("#showResult").show();                                          
            if (!data.error) {
                setTimeout(function () {
                    $("#listProd").html(data.productos);
                    $("#listadoPaginador").html(data.paginador);
                  //  $("#listadoPaginador").show();
                }, 500);                         
            }else{        
                swal({
                    type: 'warning',
                    title: 'Advertencia!',
                    html: data.error_msj
                }).then(function () {
                    location.reload();
                });
            }
       });            

};

})(jQuery);

