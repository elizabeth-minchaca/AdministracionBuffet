//Espacio de nombres
(function ($) {
	var _url = '';
    // no se sobreescribe el namespace, si ya existe 
    $.GraficoVentas = $.GraficoVentas || {};
    $.GraficoVentas.init = function (url) {
    	_url = url;
        $('#contenedor').highcharts({
        	chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'Porcentaje',
                colorByPoint: true,
                data: []
            }]
        });
    };
    
    $.GraficoVentas.cargar = function () {
        if ($("#datepicker").val() !== '' && $("#datepicker2").val() !== '') {
            $.ajax({
                url: 'listado.php?action=grafico_ventas',
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
	                    var chart = $('#contenedor').highcharts();
	                    chart.series[0].setData(data.resultado, true);
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
