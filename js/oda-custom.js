Date.prototype.getUnixTime = function() { return this.getTime()/1000|0 };
// Filtros 
var filtersOpended = false;
var activeMocion = '';
var activeMocionName = '';
$(document).ready(function() {
    // Filtros 
    $('.btn-link').addClass('collapsed');
    // Paginación de los listados
    make_pagination();
    var paginaDoc = 1;
    var grupomaximo = $('.pagination-list').data('maxgroup')

    // Botones paginacion listados
    $('.page-next').click( function(){
        if (paginaDoc < grupomaximo){
            paginaDoc++;
            $('.card.group').addClass('deactivated');
            $('.card.group-' + paginaDoc).removeClass('deactivated')
            $('#pag_indicator').html(paginaDoc);
        }
    })
    
    $('.page-prev').click( function(){
        if (paginaDoc <= grupomaximo){
            console.log(paginaDoc);
            if (paginaDoc != 1){
                paginaDoc--;
                $('.card.group').addClass('deactivated');
                $('.card.group-' + paginaDoc).removeClass('deactivated');
                $('#pag_indicator').html(paginaDoc);
            }
        }
    })


    var dateSearch = false;
    console.log('App Start');
    $('#buscar').click(oda_search)
    $('#query').on('keyup', function(e){
        if (e.key === 'Enter' || e.keyCode === 13){
            oda_search()
        }
    })
    /*
    $('input[name="date_test"]').daterangepicker(
        {
            locale: 'U'
        },
        function(start, end, label){
            console.log(start)
            console.log(end)
        }
    )*/
    $('#city_filters').change(function(e) {
        var fechaInicio = $('input[name="date_i"]');
        var fechaFin = $('input[name="date_e"]');
        if ( fechaInicio.val() != '' || fechaFin.val() != ''){ 
            dateSearch = true; 
            var unixInicio = new Date(fechaInicio.val() + 'T00:00:00Z').toUTCString();
            var unixFinal = new Date(fechaFin.val() + 'T00:00:00Z').toUTCString();
            unixInicio = new Date(unixInicio).getUnixTime();
            unixFinal = new Date(unixFinal).getUnixTime();
        }
        //console.log($('form').serialize());
        var documentos = $('.documento');
        var results = 0;
        var fields = [];
        var classes = [];
        var indice = [];
        var selectors = '';
        var selectorQuery = '';
        var patt = new RegExp('date');
        fields = $(this).serialize().split('&');
        documentos.removeClass('datefiltered');
        if (fields[0].length > 0) {
            $.each(fields, function(index, value) {     
                var band = true;   
                if ( !patt.test(value) ) {
                    indice[index] = value.split('=');
                }else{
                    if(dateSearch){
                        $.each(documentos, function(index, value){
                            var thisDate = $(this).data('date');
                            console.log(unixInicio, thisDate, unixFinal);
                            if(thisDate >= unixInicio && thisDate <= unixFinal){
                                $(this).addClass('datefiltered');
                                $(this).removeClass('deactivated');
                                indice.push('date=datefiltered'.split('=') );
                            }else{
                                $(this).addClass('deactivated');
                            }
                        })
                        dateSearch = false;
                    }
                }

            })
            $.each(indice, function(index, value) {
                if( selectors.indexOf('.datefiltered') > -1 ){
                    return true;
                }else{
                    if (index + 1 == indice.length) {
                        selectors += '.' + value[1];
                    } else {
                        selectors += '.' + value[1];
                    }
                }
                results++;
            })    
            console.log(selectors);
            if(selectors.length > 0){
                $('.card').addClass('deactivated');
                $('.card').removeClass('activated');
                $(selectors).removeClass('activated');
                $(selectors).removeClass('deactivated');
                $('.card:not(.deactivated)').addClass('activated');
            }else{
                $('.card').removeClass('deactivated')
            }
        } else {
            $('.card').removeClass('activated');
            $('.card').removeClass('deactivated');
        }
        $('#results_amount').text($('.listado-documentos .card:not(.deactivated)').length);
        $('#show_results').show();
        hidePagination();

        //selectorQuery = selectors.substring(0, selectors.length - 1);
        //console.log(selectors);
        //console.log($(selectors));
    })
    // Activar filtro de fechas
    $('.date-control').click(function(){
        dateSearch = true;
    })
    // Limpiar los Filtros
    $('#clear_filters').click(function() {
        $('#show_results').hide();
        $('.card').removeClass('deactivated');
        $('.card').removeClass('activated');
        $('#query').val('');
        $('.pagination-list').show();
        showPagination();
    })
    $('.clean-radio').click(function() {
        target = $(this).data('radio');
        if(target == 'fecha'){
            $('.date-control').val('');
        }else{
            $('input[name="' + target + '"]').prop('checked', false);
        }
        $('#'+target).find('.btn').removeClass("active-filter");
        $('#city_filters').change();
        $('#show_results').hide();
    })
    $('.form-check-input').click(function(){
        var radio = $(this).attr('name');
        $('#'+radio).find('.btn').addClass("active-filter");
    })
    $('.show_votacion').click(function(){
        var resultados = $(this).data('votacion');
        var partidosinfo = $(this).data('partidosinfo');
        activeMocion = $(this).data('mocion');
        activeMocionName = $(this).data('mocionname');
        $('#modal_title').html('');
        $('#modal_title').html($(this).data('modaltitle') + '<br/><span class="text-muted fs-12">Resultados de la votación para Aprobación de Proyecto');
        //console.log(partidosinfo);
        console.log(activeMocion, activeMocionName);        
        //console.log(partidosinfo.votos);
        
        am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("chartdiv", am4charts.XYChart);
            chart.height = 300;
            //chart.data = resultados;
            chart.data = partidosinfo.votos;
            chart.logo.disabled = true;

            var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "voto";
            categoryAxis.renderer.inversed = false;
            categoryAxis.renderer.grid.template.location = 0;
            //categoryAxis.renderer.grid.template.disabled = true;
            categoryAxis.renderer.grid.template.stroke = am4core.color("#c7c7c7");
            categoryAxis.renderer.labels.template.fill = am4core.color("#636363");
            categoryAxis.renderer.labels.template.fontSize = 16;
            categoryAxis.renderer.labels.template.fontWeight = 'bold';
            categoryAxis.renderer.grid.template.strokeWidth = 2;
            categoryAxis.renderer.grid.template.strokeOpacity = 0.8;           

            var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
            //valueAxis.renderer.inside = true;
            valueAxis.renderer.labels.template.disabled = true;
            valueAxis.min = 0;

            function createSeries(field, name, color) {

                // Set up series
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.name = name;
                series.dataFields.valueX = field;
                series.dataFields.categoryY = "voto";
                series.sequencedInterpolation = true;
                series.columns.template.stroke = am4core.color(color); // red outline
                series.columns.template.fill = am4core.color(color);
                series.columns.template.fontSize = 12;
                
                // Make it stacked
                series.stacked = true;
                /* series.dummyData = {
                    radius: 3
                }; */
                
                // Configure columns
                //series.columns.template.width = am4core.percent(20);
                series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:10px]{categoryY}: {valueX}";
                series.dummyData = {
                    radius: 20, // Marker Corder radius
                    color: color,
                    size: 15
                }
                series.columns.template.column.adapter.add("cornerRadiusTopRight", cornerRadius);
                series.columns.template.column.adapter.add("cornerRadiusBottomRight", cornerRadius);
                
                // Add label
                
                var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                labelBullet.label.text = "{valueX}";
                labelBullet.label.fontSize = 16;
                labelBullet.label.fontWeight = 'bold';
                labelBullet.locationY = 0.5;
                labelBullet.locationX = 0.5;
                labelBullet.label.hideOversized = true;
                
                return series;
            }

            $.each(partidosinfo.partidosinfo, function(index, value){
                var nombre = value.nombre;
                if(nombre.search('CREO')> 0){
                    nombre = 'CREO';
                }
                createSeries(index, nombre, value.color);
            })

            chart.legend = new am4charts.Legend();
            chart.legend.position = "top";
            chart.legend.contentAlign = "left";
            chart.legend.fontSize = 14;
            chart.legend.itemContainers.template.paddingBottom = 10;
            chart.legend.itemContainers.template.paddingTop = 10;
            //chart.bottomAxesContainer.layout = "horizontal";
            //chart.bottomAxesContainer.reverseOrder = true;
            chart.legend.useDefaultMarker = true;
            chart.legend.background.fill = am4core.color("#eeeeee");

            var marker = chart.legend.markers.template.children.getIndex(0);
            chart.legend.markers.template.width = 15;
            chart.legend.markers.template.height = 15;
            marker.cornerRadius(12, 12, 12, 12);
            marker.strokeWidth = 0;
            marker.strokeOpacity = 0;
            //marker.stroke = am4core.color("#ccc");
            //marker.stroke = "f123af";
            marker.adapter.add("fill", cambiarcolor);
            marker.adapter.add("cornerRadiusTopLeft", cornerRadiusAdapter);
            marker.adapter.add("cornerRadiusTopRight", cornerRadiusAdapter);
            marker.adapter.add("cornerRadiusBottomLeft", cornerRadiusAdapter);
            marker.adapter.add("cornerRadiusBottomRight", cornerRadiusAdapter);

            console.log(chart);

            function cornerRadiusAdapter(radius, target) {
                if (!target.dataItem) {
                    return radius;
                }
                var settings = target.dataItem.dataContext.dummyData;
                return settings && settings.radius !== undefined ? settings.radius : radius;
            };
            function cambiarcolor(color, target){
                if (!target.dataItem) {
                    return color;
                }
                var settings = target.dataItem.dataContext.dummyData;
                return settings.color;
            }
            function cornerRadius(radius, item) {
                let dataItem = item.dataItem;
                
                // Find the last series in this stack
                let lastSeries;
                chart.series.each(function(series) {
                    if (dataItem.dataContext[series.dataFields.valueX] && !series.isHidden && !series.isHiding) {
                    lastSeries = series;
                    }
                });

                
                // If current series is the one, use rounded corner
                return dataItem.component == lastSeries ? 30 : radius;
            }

        });
        
    })
})
	


function expandAll() {
    if(!filtersOpended){
        filtersOpended = true;
        $('#expand_text').text('Contraer')
        $.each($('.btn-link'), function(index,value){
            if($(this).hasClass('collapsed')){
                $(this).click();
            }
        })
    }else{
        filtersOpended = false;
        $('#expand_text').text('Expandir')
        $.each($('.btn-link'), function(index,value){
            if(!$(this).hasClass('collapsed')){
                $(this).click();
            }
        })
    }
    
}

function oda_search(){
    var encontrados = 0;
    query = $('#query').val();
        $('.documento').addClass('deactivated');
        $.each($('.documento-title'), function(index, value){
            if ( $(this).text().toLowerCase().includes(query.toLowerCase()) ){
                $(this).parents('.documento').removeClass('deactivated');
                encontrados++;
            }
        })
        $('#results_amount').text($('.listado-documentos .card:not(.deactivated)').length);
        $('#show_results').show();
        hidePagination();
}

function make_pagination(){
    var elementos = $('.listado-documentos:not(no-paginator) .card');
    if (elementos.length > 0){
        $('.card.group').addClass('deactivated');
        $('.card.group-1').removeClass('deactivated');
    }else{
        return false;
    }
}

function hidePagination(){
    $('.pagination-list').removeClass('d-flex');
    $('.pagination-list').addClass('d-none');
    $('.pagination-list').parents('.row').prev('hr').hide();
}
function showPagination(){
    $('.pagination-list').removeClass('d-none');
    $('.pagination-list').addClass('d-flex');
    $('.pagination-list').parents('.row').prev('hr').show();
}