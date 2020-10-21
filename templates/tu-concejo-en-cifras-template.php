<?php get_header();
global $wp_query;
the_post();
$ciudad = $wp_query->query_vars['city_slug'];
$item = get_page_by_path($ciudad, OBJECT, 'ciudad');
$city_primary_color = get_post_meta($item->ID, 'oda_ciudad_color', true);


$org_politicas          = get_organizaciones_politicas();
$comisiones_city        = get_comisiones_ciudad($item->ID);
$documentos             = get_ordenanzas_ciudad($item->ID);

$iniciativa_tipo = array(
    'alcalde'       => 'Alcalde',
    'concejal'      => 'Concejal',
    'comisiones'    => 'Comisiones',
    'ciudadania'    => 'Ciudadanía',
);

?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .info-sign-filters {
        color: <?php echo $city_primary_color; ?>;
    }
    .pagination-list li {
        margin-left: 10px;
    }
    .listado-documentos .card {
        border: none;
        border-radius: 0;
        border-top: 1px solid gray;
        background: transparent;
    }
    .listado-documentos .card .card-header { 
        background: transparent;
    }
    .listado-documentos .card.deactivated {
        display: none;
    }
</style>
<div class="container main-container">
    <div class="row">
        <div class="col-md-12 text-center pt-3 pb-3">
            <img class="img-fluid mt-3 mb-3" src="<?php echo THEME_URL . '/img/Proximamente-evaluacion-de-gestion.jpg'; ?>">
        </div>
    </div>
</div>
<?php get_footer(); ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $('input[name="date_test"]').daterangepicker();
        $('#city_filters').change(function(e) {
            console.log($('form').serialize());
            var results = 0;
            var fields = [];
            var classes = [];
            var indice = [];
            var selectors = '';
            var selectorQuery = '';
            fields = $(this).serialize().split('&');
            console.log(fields[0]);
            if (fields[0].length > 0) {
                $.each(fields, function(index, value) {
                    indice[index] = value.split('=');
                })
                $.each(indice, function(index, value) {
                    if (index + 1 == indice.length) {
                        selectors += '.' + value[1];
                    } else {
                        selectors += '.' + value[1];
                    }
                    results++;
                })
                $('.card').addClass('deactivated');
                $('.card').removeClass('activated');
                $(selectors).removeClass('activated');
                $(selectors).removeClass('deactivated');
                $('.card:not(.deactivated)').addClass('activated');
            } else {
                $('.card').removeClass('activated');
                $('.card').removeClass('deactivated');
            }
            $('#results_amount').text($('.card:not(.deactivated)').length);
            $('#show_results').show();

            //selectorQuery = selectors.substring(0, selectors.length - 1);
            //console.log(selectors);
            //console.log($(selectors));
        })
        // Limpiar los Filtros
        $('#clear_filters').click(function() {
            $('#show_results').hide();
            $('.card').removeClass('deactivated');
            $('.card').removeClass('activated');
        })
        $('.clean-radio').click(function(){
            target = $(this).data('radio');
            $('input[name="'+target+'"]').prop('checked', false);
            $('#city_filters').change();
        })
    })
    
    function expandAll() {
        console.log('click');
        if ($('#expand_text').text() == 'Expandir') {
            $('#expand_text').text('Contraer')
        } else {
            $('#expand_text').text('Expandir')
        }
        $('#circunscripcion .btn').click();
        $('#genero .btn').click();
        $('#organizacion .btn').click();
        $('#comision .btn').click();
    }
</script>