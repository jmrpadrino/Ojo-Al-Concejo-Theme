<?php get_header();
global $wp_query;
the_post();
$ciudad = $wp_query->query_vars['city_slug'];
$item = get_page_by_path($ciudad, OBJECT, 'ciudad');
$city_primary_color = get_post_meta($item->ID, 'oda_ciudad_color', true);


$comisiones             = get_comisiones_ciudad($item->ID);
//$org_politicas          = get_organizaciones_politicas();
$org_politicas          = get_organizaciones_politicas_ciudad($item->ID);
$ordenanzas             = get_ordenanzas_ciudad($item->ID);

$solicitudes = get_solicitudes_informacion($item->ID);

?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .info-sign-filters,
    .city-bkg-color .fase-item-counter {
        color: <?php echo $city_primary_color; ?> !important;
    }
    .city-bkg-color .fase-item-thumbnail { 
        border-color: <?php echo $city_primary_color; ?>;
    }
    .city-bkg-color .fase-separador:after,
    .city-bkg-color .fase-separador { background: <?php echo $city_primary_color; ?>; }
</style>
<section class="main-container pt-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 col-lg-3">
                <div class="row">
                    <div class="col">
                        <h1 class="fs-18 bold">Solicitudes de información y sus respuestas</h1>
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
                                            $imagen_popup = get_post_meta($item->ID, 'oda_ciudad_popupinfo_solicitud_info', true);
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
                            <div class="col-7 col-sm-7 text-right"> |&nbsp;&nbsp;<span onclick="expandAll()"><span id="expand_text">Expandir</span> todo</span></div>
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
                                                            <div class="col-10 col-sm-10 text-left fs-12">Organización Política</div>
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
                                <?php 
                                    $instituciones = new WP_Query($args = array(
                                        'post_type' => 'instituciones',
                                        'posts_per_page' => -1,
                                        'meta_query' => array(
                                            array(
                                                'key' => 'oda_ciudad_owner',
                                                'value' => $item->ID,
                                                'comprare' => '='
                                            )
                                        )
                                    ));
                                    if ($instituciones->have_posts()) { ?>
                                    <div id="institucion">
                                        <div class="card">
                                            <div class="card-header" id="institucionh">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#institucionc" aria-expanded="false" aria-controls="institucionc">
                                                        <div class="row">
                                                            <div class="col-10 col-sm-10 text-left fs-12">Información solicitada a</div>
                                                            <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                        </div>
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="institucionc" class="collapse" aria-labelledby="institucionh" data-parent="#institucion">
                                                <div class="card-body">
                                                    <?php while ($instituciones->have_posts()) {
                                                        $instituciones->the_post(); ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="institucion" id="ins_<?php echo get_the_ID(); ?>" value="ins-<?php echo get_the_ID(); ?>">
                                                            <label class="form-check-label" for="ins_<?php echo get_the_ID(); ?>">
                                                                <?php echo get_the_title(); ?>
                                                            </label>
                                                        </div>
                                                    <?php } // End While org politicas 
                                                    ?>
                                                    <p class="ta-r bold"><span class="clean-radio" data-radio="institucion">Desactivar filtro</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } // END Org Politicas 
                                ?>
                                <div id="estado">
                                    <div class="card">
                                        <div class="card-header" id="estadoh">
                                            <h5 class="mb-0">
                                                <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#estadoc" aria-expanded="false" aria-controls="estadoc">
                                                    <div class="row">
                                                        <div class="col-10 col-sm-10 text-left fs-12">Estado</div>
                                                        <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                    </div>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="estadoc" class="collapse" aria-labelledby="estadoh" data-parent="#estado">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="estado" id="est_1" value="est-1">
                                                    <label class="form-check-label" for="est_1">
                                                        Solicitud Presentada
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="estado" id="est_2" value="est-2">
                                                    <label class="form-check-label" for="est_2">
                                                        Respuesta
                                                    </label>
                                                </div>
                                                <p class="ta-r bold"><span class="clean-radio" data-radio="estado">Desactivar filtro</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--
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
                                                
                                                <input class="form-control" type="text" name="date_test" value="" />
                                            
                                                <p class="ta-r bold"><span class="clean-radio" data-radio="comision">Desactivar filtro</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                -->
                            </div>
                        </div>
                        <div class="row filter-box-footer">
                            <div class="col-4 col-sm-4"></div>
                            <div class="col-8 col-sm-8 text-right"><button id="clear_filters" type="reset" class="btn-clear-filters bold">|&nbsp;&nbsp;Borrar filtros</button></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 col-lg-9 pl-5">
                <div class="row my-3">
                    <div class="col-sm-12 col-lg-6 offset-lg-6 text-right">
                        <div class="btn-oda excel-ranking">
                            <span class="button-name">Excel</span>
                            <span class="button-icon"><i class="fas fa-download"></i></span>
                        </div>
                        <div class="btn-oda csv-ranking">
                            <span class="button-name">CSV</span>
                            <span class="button-icon"><i class="fas fa-download"></i></span>
                        </div>
                    </div>
                </div>
                <h2 class="fs-16">Listado</h2>
                <?php if ($solicitudes->have_posts()) { ?>
                    <?php if ($solicitudes->post_count > 8) { ?>
                        <hr />
                        <div class="row">
                            <div class="col">
                                <ul class="list-no-style d-flex justify-content-end pagination-list" data-maxgroup="<?php echo ceil($solicitudes->post_count / 8); ?>">
                                    <li id="pag_indicator">1</li>
                                    <li>-</li>
                                    <li><?php echo ceil($solicitudes->post_count / 8); ?></li>
                                    <li class="paginate-link page-prev" title="Anterior"><i class="fa fa-chevron-left"></i></li>
                                    <li class="paginate-link page-next" title="Siguiente"><i class="fa fa-chevron-right"></i></li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="accordion listado-documentos" id="listadoOrdenanzas">
                                <?php
                                $counter = 1;
                                $grupo = 1;
                                while ($solicitudes->have_posts()) {
                                    $solicitudes->the_post();
                                    $solicitante = get_post_meta(get_the_ID(), 'oda_solicitud_info_iniciativa', true);
                                    $informacion_solicitada_a = get_post_meta(get_the_ID(), 'oda_solicitud_instituciones', true);
                                    $estado = get_post_meta(get_the_ID(), 'oda_solicitud_info_estado', true);
                                    $fecha = get_post_meta(get_the_ID(), 'oda_solicitud_info_vigencia', true);
                                    $pdf_solicitud = get_post_meta(get_the_ID(), 'oda_solicitud_pdf', true);
                                    $pdf_solicitud_fecha = get_post_meta(get_the_ID(), 'oda_solicitud_pdf_fecha', true);
                                    $pdf_respuesta = get_post_meta(get_the_ID(), 'oda_respuesta_pdf', true);
                                    $pdf_respuesta_fecha = get_post_meta(get_the_ID(), 'oda_respuesta_pdf_fecha', true);

                                    switch($solicitante){
                                        case 'alcalde': 
                                            $alcalde = new WP_Query(array(
                                                'post_type' => 'miembro',
                                                'posts_per_page' => -1,
                                                'meta_query' => array(
                                                    'relation' => 'AND',
                                                    array(
                                                        'key' => 'oda_ciudad_owner',
                                                        'value' => $item->ID,
                                                        'compare' => '='
                                                    ),
                                                    array(
                                                        'key' => 'oda_miembro_cargo',
                                                        'value' => 1,
                                                        'compare' => '='
                                                    )
                                                )
                                            ));
                                            $solicitante = $alcalde->posts[0]->post_title;
                                            break;
                                        case 'concejal': 
                                            $concejal = get_post(get_post_meta(get_the_ID(), 'oda_solicitud_info_iniciativa_solicitante_concejal', true))->post_title;
                                            $solicitante = $concejal;
                                            break;
                                        case 'comision': 
                                            $comision = get_post(get_post_meta(get_the_ID(), 'oda_solicitud_info_iniciativa_solicitante_comision', true))->post_title;
                                            $solicitante = $comision;
                                            break;
                                        case 'ciudadania': 
                                            $ciudadano = get_post_meta(get_the_ID(), 'oda_solicitud_info_iniciativa_solicitante_ciudadania', true);
                                            $solicitante = $ciudadano;
                                            break;
                                    }
                                ?>
                                    <div class="card group group-<?php echo $grupo; ?> <?php 
                                                echo ($estado) ? ' est-'.$estado : ''; 
                                                echo ($informacion_solicitada_a) ? ' ins-'.$informacion_solicitada_a : ''; 
                                    ?>" data-fecha="<?php echo date('U', $fecha); ?>">
                                        <div class="card-header px-0" id="heading-<?php echo get_the_ID(); ?>">
                                            <h2 class="mb-0 fs-16 lh-1 hover-underlined">
                                                <a class="text-left text-black-light collapsed cursor-pointer" data-toggle="collapse" data-target="#collapse-<?php echo get_the_ID(); ?>" aria-expanded="false" aria-controls="collapse-<?php echo get_the_ID(); ?>">
                                                    <?php echo get_the_title(); ?>
                                                </a>
                                            </h2>
                                            <div class="w-100 d-flex justify-content-end align-items-end">
                                                <a class="bold cursor-pointer" data-toggle="collapse" data-target="#collapse-<?php echo get_the_ID(); ?>" aria-expanded="false" aria-controls="collapse-<?php echo get_the_ID(); ?>">(Ver más)</a>
                                            </div>
                                        </div>
                                        <div id="collapse-<?php echo get_the_ID(); ?>" class="collapse" aria-labelledby="heading-<?php echo get_the_ID(); ?>" data-parent="#listadoOrdenanzas">
                                            <div class="card-body">
                                                <!-- Metas -->
                                                <div class="row mb-4">
                                                    <div class="col-sm-12">
                                                        <ul class="list-no-style">
                                                            <?php if ($solicitante) : ?>
                                                                <li><strong>Solicitante:</strong> <?php echo $solicitante; ?></li>
                                                            <?php endif ?>
                                                            <?php if ($informacion_solicitada_a) : ?>
                                                                <li><strong>Información solicitada a:</strong> <?php echo get_the_title($informacion_solicitada_a); ?></li>
                                                            <?php endif ?>
                                                            <?php if ($estado) :
                                                                switch ($estado) {
                                                                    case '1': $estado = 'Solicitud presentada'; break;
                                                                    case '2': $estado = 'Respuesta'; break;
                                                                }
                                                            ?>
                                                                <li><strong>Estado:</strong> <?php echo $estado; ?></li>
                                                            <?php endif ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- Fases -->
                                                <div class="row">
                                                    <?php
                                                    $index = 0;
                                                    echo '<div class="col-md-4"></div>';
                                                    
                                                    ?>
                                                    <div class="col-md-2 fase-item-container">
                                                        <div class="fase-item-counter-container city-bkg-color">
                                                            <span class="fs-36 bold fase-item-counter">1</span>
                                                            <div class="fase-item-thumbnail">
                                                                <img class="img-fluid" src="https://www.flaticon.es/svg/static/icons/svg/702/702814.svg">
                                                            </div>
                                                            <div class="fase-separador first-separator"></div>
                                                            <div class="ta-c mt-2">
                                                                <span class="fs-14 bold ta-c">Solicitud presentada</span><br />
                                                                <?php if ($pdf_solicitud_fecha) { ?>
                                                                    <span class="fs-12 ta-c"><?php echo date('d/m/Y', strtotime($pdf_solicitud_fecha)); ?></span><br />
                                                                <?php } ?>
                                                                <?php if ($pdf_solicitud) { ?>
                                                                    <span class="fs-12 ta-c"><a class="text-black-light" href="<?php echo $pdf_solicitud; ?>" target="_blank">Ver</a> - <a class="text-black-light" href="<?php echo $pdf_solicitud; ?>" download>Descargar</a></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 fase-item-container">
                                                        <div class="fase-item-counter-container">
                                                            <span class="fs-36 bold fase-item-counter">2</span>
                                                            <div class="fase-item-thumbnail">
                                                                <img class="img-fluid" src="https://www.flaticon.com/svg/static/icons/svg/858/858171.svg">
                                                            </div>
                                                            <div class="fase-separador"></div>
                                                            <div class="ta-c mt-2">
                                                                <span class="fs-14 bold ta-c">Respuesta</span><br />
                                                                <?php if ($pdf_respuesta_fecha) { ?>
                                                                    <span class="fs-12 ta-c"><?php echo date('d/m/Y', strtotime($pdf_respuesta_fecha)); ?></span><br />
                                                                <?php } ?>
                                                                <?php if ($pdf_respuesta) { ?>
                                                                    <span class="fs-12 ta-c"><a class="text-black-light" href="<?php echo $pdf_respuesta; ?>" target="_blank">Ver</a> - <a class="text-black-light" href="<?php echo $pdf_respuesta; ?>" download>Descargar</a></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php 
                                    if($counter % 8 == 0){
                                        $grupo++;
                                    }
                                    $counter++; 
                                } // END While 
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


        var cityid = <?php echo $item->ID; ?>;
        var citiName = '<?php echo $item->post_title; ?>';
        // Clic en EXCEL
        $('.excel-ranking').click( function(){            
            $.ajax({
                url: oda_dom_vars.ajaxurl,
                type: 'GET',
                data: {
                    action: 'oda_generate_listado_resoluciones_xls',
                    city: cityid,
                    cityname:citiName
                },
                beforeSend: function(){
                    $('body').toggleClass('loading-overlay-showing');
                },
                success: function(data){
                    $('body').toggleClass('loading-overlay-showing');
                    console.log(data);
                    var $a = $("<a>");
                    $a.attr("href",data.file);
                    $("body").append($a);
                    $a.attr("download","OC_listado_resoluciones_concejo_municipal_"+citiName+".xls");
                    $a[0].click();
                    $a.remove();                   
                },
                error: function(xhr,err){
                    console.log(err);
                    console.log(xhr);
                }

            })

        })
        // Clic en CSV 
        $('.csv-ranking').click( function(){
            $.ajax({
                url: oda_dom_vars.ajaxurl,
                type: 'GET',
                data: {
                    action: 'oda_generate_csv_listado_resoliciones',
                    city: cityid
                },
                
                xhrFields: {
                    responseType: 'blob'
                },
                
                beforeSend: function(){
                    $('body').toggleClass('loading-overlay-showing');
                },
                success: function(data){
                    $('body').toggleClass('loading-overlay-showing');
                    console.log(data);
                    
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(data);
                    a.href = url;
                    a.download = 'OC_listado_resoluciones_concejo_municipal_'+citiName+'.csv';
                    document.body.append(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                    
                },
                error: function(xhr,err){
                    console.log(err);
                    console.log(xhr);
                }

            })

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