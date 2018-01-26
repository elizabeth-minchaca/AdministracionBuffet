//Espacio de nombres
(function ($) {
    // no se sobreescribe el namespace, si ya existe  
    $.GraficoGanancias = $.GraficoGanancias || {};
    $.GraficoGanancias.init = function () {
        
        $('#contenedor').highcharts({
       
            chart: {
                type: 'bar'                	
            },
            title: {
                text: ''
            },
            xAxis: {
            	categories: []
            },
            yAxis: {
                title: {
                    text: '$'
                }
            },
            series: [{          
                    name: 'Ganancia',
                    data: []
                }]
        });
    };
    $.GraficoGanancias.cargar = function () {
        if ($("#datepicker").val() !== '' && $("#datepicker2").val() !== '') {
            $.ajax({
                url: 'listado.php?action=grafico_ganancias',
                type: "POST",
                dataType: 'json',
                data: {'fechaDesde': $("#datepicker").val(), 'fechaHasta': $("#datepicker2").val()},
                success: function (data, textStatus, jqXHR) {
                	if (!data.valido){
	                    swal({
	                        type: 'warning',
	                        title: 'Advertencia!',
	                        html: data.error_msj
	                    }).then(function () {
	                        location.reload();
	                    });
                	}else {
                        $('#contenedor').highcharts({
                            
                            chart: {
                                type: 'bar',
                                height: data.alto
                            },
                            title: {
                                text: ''
                            },
                            xAxis: {
                            	categories: data.fechas	
                            },
                            yAxis: {
                                title: {
                                    text: '$'
                                }
                            },
                            series: [{            
                                    name: 'Ganancia',
                                    data: data.montos
                                }]
                        });
                	}
                },
                error: function (error) {
//                    alert("Error");
                	alert(JSON.stringify(error));
                }
            });
        }
    };
})(jQuery);
