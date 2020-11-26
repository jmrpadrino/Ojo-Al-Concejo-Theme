<?php get_header();
global $wp_query;
the_post();
$ciudad = $wp_query->query_vars['city_slug'];
$item = get_page_by_path($ciudad, OBJECT, 'ciudad');
$city_primary_color = get_post_meta($item->ID, 'oda_ciudad_color', true);


$org_politicas          = get_organizaciones_politicas();
$documentos             = get_resoluciones_ciudad($item->ID);
$fases_ciudad           = get_post_meta($item->ID, 'oda_ciudad_fase_res', true);
$temas_ciudad           = get_temas_documento_ciudad($item->ID, 'tema_resolucion');

$iniciativa_tipo = array(
    'alcalde'       => 'Alcalde',
    'concejal'      => 'Concejal',
    'comisiones'    => 'Comisiones',
    'ciudadania'    => 'Ciudadanía',
);

?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .info-sign-filters,
    .city-text-color,
    .active-filter,
    .city-bkg-color .fase-item-counter {
        color: <?php echo $city_primary_color; ?> !important;
    }
    .city-bkg-color .fase-item-thumbnail { 
        border-color: <?php echo $city_primary_color; ?>;
    }
    .city-bkg-color .fase-separador:after,
    .city-bkg-color .fase-separador { background: <?php echo $city_primary_color; ?>; }
</style>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/dataviz.js"></script>
<script>
</script>
<section class="main-container pt-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 col-lg-3">
                <div class="row">
                    <div class="col">
                        <h1 class="fs-20 bold">Proyectos de resolución</h1>
                        <p>Búsqueda fácil según el título del documento o el nombre del proponente.</p>
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
                    <div class="col-sm-12 px-2 py-2 my-2 d-flex justify-space-around align-items-center bg-light border">
                        <input id="query" type="text" name="search" placeholder="Palabra clave" class="mr-1 bg-transparent">
                        <button id="buscar" type="button" class="btn btn-secondary btn-sm rounded-0 fs-12"><span class="fa fa-search"></span> BUSCAR</button>
                    </div>
                    <div class="modal fade" id="howtofilters">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
                                <div class="modal-body">
                                    <div class="row">
                                        <?php
                                            $image_url = 'http://placehold.it/800x600?text=Manual';
                                            $imagen_popup = get_post_meta($item->ID, 'oda_ciudad_popupinfo_resoluciones', true);
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
                            <?php if ($temas_ciudad->have_posts()) { ?>
                                    <div id="temas">
                                        <div class="card">
                                            <div class="card-header" id="temash">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#temasc" aria-expanded="false" aria-controls="temasc">
                                                        <div class="row">
                                                            <div class="col-10 col-sm-10 text-left fs-14">Tema</div>
                                                            <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                        </div>
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="temasc" class="collapse" aria-labelledby="temash" data-parent="#temas">
                                                <div class="card-body">
                                                    <?php while ($temas_ciudad->have_posts()) {
                                                        $temas_ciudad->the_post(); ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="temas" id="t_<?php echo get_the_ID(); ?>" value="t-<?php echo get_the_ID(); ?>">
                                                            <label class="form-check-label" for="t_<?php echo get_the_ID(); ?>">
                                                                <?php echo get_the_title(); ?>
                                                            </label>
                                                        </div>
                                                    <?php } // End While org politicas 
                                                    ?>
                                                    <p class="ta-r bold"><span class="clean-radio" data-radio="temas">Desactivar filtro</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } // END Org Politicas 
                                ?>
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
                                <?php if (ORDENANZA_STATUS) { ?>
                                    <div id="status">
                                        <div class="card">
                                            <div class="card-header" id="statush">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#statusc" aria-expanded="false" aria-controls="statusc">
                                                        <div class="row">
                                                            <div class="col-10 col-sm-10 text-left fs-14">Estado del trámite</div>
                                                            <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                            </div>
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="statusc" class="collapse" aria-labelledby="statush" data-parent="#status">
                                                <div class="card-body">
                                                    <?php foreach (RESOLUCION_STATUS as $index => $status) { ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="status" id="status_<?php echo $index; ?>" value="s-<?php echo $index; ?>">
                                                            <label class="form-check-label" for="status_<?php echo $index; ?>">
                                                                <?php echo $status; ?>
                                                            </label>
                                                        </div>
                                                    <?php } // End While org politicas 
                                                    ?>
                                                    <p class="ta-r bold"><span class="clean-radio" data-radio="status">Desactivar filtro</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } // END Statuses 
                                ?>
                                <div id="fecha">
                                    <div class="card">
                                        <div class="card-header" id="fechah">
                                            <h5 class="mb-0">
                                                <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#fechac" aria-expanded="false" aria-controls="fechac">
                                                    <div class="row">
                                                        <div class="col-10 col-sm-10 text-left fs-14">Fecha</div>
                                                        <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                        </div>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="fechac" class="collapse" aria-labelledby="comisionh" data-parent="#fecha">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="form-check-label" for="date_i">Desde
                                                            <input class="form-control date-control" type="date" name="date_i" max="<?php echo date('Y-m-d'); ?>" value="" />
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <label class="form-check-label" for="date_e">Hasta
                                                            <input class="form-control date-control" type="date" name="date_e" max="<?php echo date('Y-m-d'); ?>" value="" />
                                                        </label>
                                                    </div>
                                                </div>
                                                <br />
                                                <p><strong>Nota:</strong> use ambos elementos para buscar en un rango de fechas</p>
                                                <p class="ta-r bold"><span class="clean-radio" data-radio="fecha">Desactivar filtro</span></p>
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
            <div class="col-12 col-lg-9 pl-5">
                <h2 class="fs-16">Listado</h2>
                <?php if ($documentos->have_posts()) { ?>
                    <?php if ($documentos->post_count > 10) { ?>
                        <hr />
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
                    <?php } ?>
                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="accordion listado-documentos" id="listadodocumentos">
                                <?php
                                $counter = 0;
                                while ($documentos->have_posts()) {
                                    $documentos->the_post();
                                    $proponente = get_post_meta(get_the_ID(), 'oda_resolucion_proponente', true);
                                    $estado = get_post_meta(get_the_ID(), 'oda_resolucion_estado', true);
                                    $partidos_documento = get_partido_politico_documento(get_the_ID(), 'resolucion');
                                    $fecha_documento = get_post_meta(get_the_ID(), 'oda_resolucion_fecha', true);
                                    $tema_documento = get_post_meta(get_the_ID(), 'oda_resolucion_incidencia_temas', true);
                                ?>
                                    <div class="card documento<?php
                                                    echo ($estado) ? ' s-' . $estado . '' : '';
                                                    echo ($tema_documento) ? ' t-' . $tema_documento . '' : '';
                                                    if ($partidos_documento) {
                                                        foreach ($partidos_documento as $partido_documento) {
                                                            echo ' org-' . $partido_documento;
                                                        }
                                                    }
                                                    ?>" data-date="<?php echo ($fecha_documento) ? date('U', strtotime($fecha_documento)) : ''; ?>">
                                        <div class="card-header" id="heading-<?php echo get_the_ID(); ?>">
                                            <h2 class="mb-0 fs-16 lh-1 hover-underlined">
                                                <a class="text-left text-black-light collapsed cursor-pointer" data-toggle="collapse" data-target="#collapse-<?php echo get_the_ID(); ?>" aria-expanded="false" aria-controls="collapse-<?php echo get_the_ID(); ?>">
                                                    <span class="documento-title"><?php echo get_the_title(); ?></span>
                                                </a>
                                            </h2>
                                            <?php
                                            if ($proponente) {
                                            ?>
                                                <div class="w-100 d-flex justify-content-between align-items-center">
                                                    <span><strong>Proponente:</strong> <i><?php echo $proponente; ?></i></span>
                                                <?php } else { ?>
                                                    <div class="w-100 d-flex justify-content-end align-items-center">
                                                    <?php } ?>
                                                    <a class="bold cursor-pointer" data-toggle="collapse" data-target="#collapse-<?php echo get_the_ID(); ?>" aria-expanded="false" aria-controls="collapse-<?php echo get_the_ID(); ?>">(Ver más)</a>
                                                    </div>
                                                </div>
                                                <div id="collapse-<?php echo get_the_ID(); ?>" class="collapse" aria-labelledby="heading-<?php echo get_the_ID(); ?>" data-parent="#listadodocumentos">
                                                    <div class="card-body">
                                                        <!-- Metas -->
                                                        <div class="row mb-4">
                                                            <div class="col-sm-12">
                                                                <ul class="list-no-style">
                                                                    <?php if ($proponente) : ?>
                                                                        <li><strong>Proponente:</strong> <?php echo $proponente; ?></li>
                                                                    <?php endif ?>
                                                                    <?php if ($estado) : ?>
                                                                        <li><strong>Estado:</strong> <?php echo RESOLUCION_STATUS[$estado]; ?></li>
                                                                    <?php endif ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <!-- Fases -->
                                                        <div class="row">
                                                            <?php
                                                            if ($fases_ciudad) :
                                                                $fases_counter = 0;
                                                                echo '<div class="col-md-3"></div>';
                                                                foreach ($fases_ciudad as $index => $fase) {

                                                                    $meta_fase_name = $fase['oda_items_resolucion_fases'];
                                                                    $meta_fase_icon = $fase['res_fases_icon'];
                                                                    $meta_fase_votacion = $fase['res_fases_front'];
                                                                    $meta_fase_file = get_post_meta(get_the_ID(), 'oda_res_fases_item_' . $index, true);
                                                                    $meta_fase_date = get_post_meta(get_the_ID(), 'oda_res_fases_fecha_' . $index, true);
                                                                    $fase_placeholder = 'http://placehold.it/100x100/?text=Fase';
                                                            ?>
                                                                    <div class="col-md-2 fase-item-container">
                                                                        <div class="fase-item-counter-container <?php echo ($index % 2 != 0) ? '' : 'city-bkg-color'; ?>">
                                                                            <span class="fs-36 bold fase-item-counter"><?php echo $index + 1; ?></span>
                                                                            <div class="fase-item-thumbnail">
                                                                                <img class="img-fluid" src="<?php echo ($meta_fase_icon) ? $meta_fase_icon : $fase_placeholder; ?>">
                                                                            </div>
                                                                            <div class="fase-separador <?php echo ($fases_counter == 0) ? 'first-separator' : ''; ?>"></div>
                                                                            <div class="ta-c mt-2">
                                                                                <span class="fs-14 bold ta-c"><?php echo $meta_fase_name; ?></span><br />
                                                                                <?php if ($meta_fase_date) { ?>
                                                                                    <span class="fs-12 ta-c"><?php echo date('d/m/Y', strtotime($meta_fase_date)); ?></span><br />
                                                                                <?php } ?>
                                                                                <?php if ($meta_fase_file) { ?>
                                                                                    <span class="fs-12 ta-c"><a class="text-black-light" href="<?php echo $meta_fase_file; ?>" target="_blank">Ver</a> - <a class="text-black-light" href="<?php echo $meta_fase_file; ?>" download>Descargar</a></span>
                                                                                <?php } ?>
                                                                                <?php if (!$meta_fase_votacion) { ?>
                                                                                <?php } else { ?>
                                                                                    <?php
                                                                                    // Se configura con el slug del partido que se quiere representar en el grafico
                                                                                    $partidos_lista = array(
                                                                                        'fuerza-compromiso-social',
                                                                                        'izquierda-democratica',
                                                                                        'movimiento-creo',
                                                                                        'otras-organizaciones'
                                                                                    );
                                                                                    $votos = mocion_votacion_fase_aprobacion(get_the_ID(), $index, $partidos_lista);

                                                                                    $partidos_info = array(
                                                                                        'partidosinfo' => array(
                                                                                            /**
                                                                                                 * RESOLVER
                                                                                                 */
                                                                                            'fuerza-compromiso-social' => array(
                                                                                                'nombre' => get_page_by_path('fuerza-compromiso-social', OBJECT, 'partido')->post_title,
                                                                                                'color' => get_post_meta(get_page_by_path('fuerza-compromiso-social', OBJECT, 'partido')->ID, 'oda_partido_color_alternativo', true)
                                                                                            ),
                                                                                            'izquierda-democratica' => array(
                                                                                                'nombre' => get_page_by_path('izquierda-democratica', OBJECT, 'partido')->post_title,
                                                                                                'color' => get_post_meta(get_page_by_path('izquierda-democratica', OBJECT, 'partido')->ID, 'oda_partido_color_alternativo', true)
                                                                                            ),
                                                                                            'movimiento-creo' => array(
                                                                                                'nombre' => get_page_by_path('movimiento-creo', OBJECT, 'partido')->post_title,
                                                                                                'color' => get_post_meta(get_page_by_path('movimiento-creo', OBJECT, 'partido')->ID, 'oda_partido_color_alternativo', true)
                                                                                            ),
                                                                                            'otras-organizaciones' => array(
                                                                                                'nombre' => 'Otras Organizaciones Políticas',
                                                                                                'color' => '#555555'
                                                                                            )
                                                                                        ),
                                                                                        'votos' => $votos,
                                                                                    );

                                                                                    if ($votos) {
                                                                                    ?>
                                                                                        <span class="fs-12 ta-c"><a class="text-black-light show_votacion" href="#" data-toggle="modal" data-target="#votacion_ordenanza" data-modaltitle="<?php echo get_the_title(); ?>" data-partidosinfo='<?php echo json_encode($partidos_info); ?>'>Ver Votación</a></span>
                                                                                <?php }
                                                                                } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php $fases_counter++; }//End foreach 
                                                                ?>
                                                            <?php endif;//Endif 
                                                            ?>
                                                        </div>
                                                        <div class="row">
                                                            <?php /*
                                                                echo '<pre>';
                                                                var_dump( devuelve_porcentajes($votos) );
                                                                echo '</pre>';*/
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    <?php $counter++;
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
<div class="modal fade" id="votacion_ordenanza" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
            <!--
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Modal title</h5>
            </div>
            -->
            <div class="modal-body">
                <div id="chartdiv"></div>
                <div class="w-100 d-flex justify-content-center">
                    <div class="btn-oda excel-ranking">
                        <span class="button-name">Excel</span>
                        <span class="button-icon"><i class="fas fa-download"></i></span>
                    </div>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="btn-oda csv-ranking">
                        <span class="button-name">CSV</span>
                        <span class="button-icon"><i class="fas fa-download"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>

</script>