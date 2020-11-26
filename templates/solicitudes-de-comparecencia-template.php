<?php get_header();
global $wp_query;
the_post();
$ciudad = $wp_query->query_vars['city_slug'];
$item = get_page_by_path($ciudad, OBJECT, 'ciudad');
$city_primary_color = get_post_meta($item->ID, 'oda_ciudad_color', true);


$comisiones             = get_comisiones_ciudad($item->ID);
$org_politicas          = get_organizaciones_politicas();
$ordenanzas             = get_ordenanzas_ciudad($item->ID);

$solicitudes = get_solicitudes_informacion($item->ID);

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
<section class="main-container pt-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 col-lg-3">
                <div class="row">
                    <div class="col">
                        <h1 class="fs-20 bold">Solicitudes de comparecencia</h1>
                        <p>Búsqueda fácil según el título del documento o el nombre del solicitante.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <a href="#" data-toggle="modal" data-target="#howtofilters">
                            <i class="fas fa-info-circle info-sign-filters fs-26"></i>
                        </a>
                    </div>
                    <div class="col-md-10 fs-14 text-center">
                        <div id="show_results" style="display: none;">Resultados encontrados: <span id="results_amount" class="bold"></span></div>
                    </div>
                    <div class="modal fade" id="howtofilters">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
                                <div class="modal-body">
                                    <div class="row">
                                        <?php
                                            $image_url = 'http://placehold.it/800x600?text=Manual';
                                            $imagen_popup = get_post_meta($item->ID, 'oda_ciudad_popupinfo_solicitud_comp', true);
                                            if($imagen_popup){
                                                $image_url = $imagen_popup;
                                            }
                                        ?>
                                        <img class="img-fluid" src="<?php echo $image_url; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter-box">
                    <form id="city_filters" role="form">
                        <div class="row filter-box-header bold">
                            <div class="col-5 col-sm-5">Filtros</div>
                            <div class="col-7 col-sm-7 text-right"> |&nbsp;&nbsp<span onclick="expandAll()"><span id="expand_text">;Expandir</span> todo</span></div>
                        </div>
                        <div class="row filter-box-content">
                            <div class="col-sm-12 p-3">
                                <?php if ($org_politicas->have_posts()) { ?>
                                    <div id="organizacion">
                                        <div class="card">
                                            <div class="card-header" id="organizacionh">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#organizacionc" aria-expanded="false" aria-controls="organizacionc">
                                                        <div class="row">
                                                            <div class="col-10 col-sm-10 text-left fs-14">Organización Política</div>
                                                            <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                        </div>
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="organizacionc" class="collapse" aria-labelledby="organizacionh" data-parent="#organizacion">
                                                <div class="card-body">
                                                    <?php while ($org_politicas->have_posts()) {
                                                        $org_politicas->the_post(); ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="organizacion" id="org_<?php echo get_the_ID(); ?>" value="org-<?php echo get_the_ID(); ?>">
                                                            <label class="form-check-label" for="org_<?php echo get_the_ID(); ?>">
                                                                <?php echo get_the_title(); ?>
                                                            </label>
                                                        </div>
                                                    <?php } // End While org politicas 
                                                    ?>
                                                    <p class="ta-r bold"><span class="clean-radio" data-radio="organizacion">Desactivar filtro</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } // END Org Politicas 
                                ?>
                                <div id="comision">
                                    <div class="card">
                                        <div class="card-header" id="comisionh">
                                            <h5 class="mb-0">
                                                <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#comisionc" aria-expanded="false" aria-controls="comisionc">
                                                    <div class="row">
                                                        <div class="col-10 col-sm-10 text-left fs-14">Comisión</div>
                                                        <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                        </d2>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="comisionc" class="collapse" aria-labelledby="comisionh" data-parent="#comision">
                                            <div class="card-body">
                                                <?php while ($comisiones->have_posts()) {
                                                    $comisiones->the_post(); ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="comision" id="com_<?php echo get_the_ID(); ?>" value="com-<?php echo get_the_ID(); ?>">
                                                        <label class="form-check-label" for="com_<?php echo get_the_ID(); ?>">
                                                            <?php echo get_the_title(); ?>
                                                        </label>
                                                    </div>
                                                <?php } // End While org politicas 
                                                ?>
                                                <p class="ta-r bold"><span class="clean-radio" data-radio="comision">Desactivar filtro</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="fecha">
                                    <div class="card">
                                        <div class="card-header" id="fechah">
                                            <h5 class="mb-0">
                                                <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#fechac" aria-expanded="false" aria-controls="fechac">
                                                    <div class="row">
                                                        <div class="col-10 col-sm-10 text-left fs-14">Fecha</div>
                                                        <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                        </d2>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="fechac" class="collapse" aria-labelledby="comisionh" data-parent="#fecha">
                                            <div class="card-body">
                                                <!--
                                                <input class="form-control" type="text" name="date_test" value="" />
                                                -->
                                                <p class="ta-r bold"><span class="clean-radio" data-radio="comision">Desactivar filtro</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row filter-box-footer">
                            <div class="col-4 col-sm-4"></div>
                            <div class="col-8 col-sm-8 text-right"><button id="clear_filters" type="reset" class="btn-clear-filters bold">|&nbsp;&nbsp;Borrar filtros</button></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 col-lg-9">
                <h2 class="fs-16">Listado</h2>
                <hr />
                <?php if ($solicitudes->have_posts()) { ?>
                    <div class="row">
                        <div class="col">
                            <ul class="list-no-style d-flex justify-content-end pagination-list">
                                <li><a href="#">1</a></li>
                                <li>-</li>
                                <li><a href="#">4</a></li>
                                <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                                <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="accordion listado-documentos" id="listadoOrdenanzas">
                                <?php
                                while ($solicitudes->have_posts()) {
                                    $solicitudes->the_post();
                                    $comision = '';
                                    $comision = get_post_meta(get_the_ID(), 'oda_ordenanza_comision', true);

                                ?>
                                    <div class="card com-<?php echo $comision; ?>">
                                        <div class="card-header" id="heading-<?php echo get_the_ID(); ?>">
                                            <h2 class="mb-0 fs-16 lh-1 hover-underlined">
                                                <a class="text-left text-black-light collapsed cursor-pointer" data-toggle="collapse" data-target="#collapse-<?php echo get_the_ID(); ?>" aria-expanded="false" aria-controls="collapse-<?php echo get_the_ID(); ?>">
                                                    <?php echo get_the_title(); ?>
                                                </a>
                                            </h2>
                                            <div class="w-100 d-flex justify-content-between align-items-center">
                                                <?php
                                                    $proponente = get_post_meta(get_the_ID(), 'oda_ordenanza_proponente', true);
                                                    if($proponente){
                                                ?>
                                                <span><strong>Proponente:</strong> <i><?php echo $proponente; ?></i></span>
                                                <?php } ?>
                                                <a class="bold cursor-pointer" data-toggle="collapse" data-target="#collapse-<?php echo get_the_ID(); ?>" aria-expanded="false" aria-controls="collapse-<?php echo get_the_ID(); ?>">(Ver más)</a>
                                            </div>
                                        </div>
                                        <div id="collapse-<?php echo get_the_ID(); ?>" class="collapse" aria-labelledby="heading-<?php echo get_the_ID(); ?>" data-parent="#listadoOrdenanzas">
                                            <div class="card-body">
                                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                            </div>
                                        </div>
                                    </div>
                                <?php } // END While 
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <p class="text-warning">No existen proyectos de ordenanza registrados en este Concejo Municipal</p>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
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