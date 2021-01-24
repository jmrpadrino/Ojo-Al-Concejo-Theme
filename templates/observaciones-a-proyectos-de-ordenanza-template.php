<?php get_header();
global $wp_query;
the_post();
$ciudad = $wp_query->query_vars['city_slug'];
$item = get_page_by_path($ciudad, OBJECT, 'ciudad');
$city_primary_color = get_post_meta($item->ID, 'oda_ciudad_color', true);


//$org_politicas          = get_organizaciones_politicas();
$org_politicas          = get_organizaciones_politicas_ciudad($item->ID);
$comisiones_city        = get_comisiones_ciudad($item->ID);
$documentos             = get_ordenanzas_ciudad($item->ID);
$miembros_ciudad        = get_miembro_ciudad($item->ID);
$fases_ciudad           = get_post_meta($item->ID, 'oda_ciudad_fase', true);
$temas_ciudad           = get_temas_documento_ciudad($item->ID, 'tema_ordenanza');

$iniciativa_tipo = array(
    'alcalde'       => 'Alcalde',
    'concejal'      => 'Concejal',
    'comisiones'    => 'Comisiones',
    'ciudadania'    => 'Ciudadanía',
);

//var_dump($miembros_ciudad);

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
    .city-bkg-color .fase-separador {
        background: <?php echo $city_primary_color; ?>;
    }
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
                        <h1 class="fs-18 bold">Observaciones a proyectos de ordenanzas</h1>
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
                        <input id="query" type="text" name="search" placeholder="Palabra clave" style="width: 145px; border: none; border-bottom: 1px solid;" class="mr-1 bg-transparent">
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
                                            $imagen_popup = get_post_meta($item->ID, 'oda_ciudad_popupinfo_observaciones', true);
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
                                <?php /* if ($miembros_ciudad) { ?>
                                    <div id="status">
                                        <div class="card">
                                            <div class="card-header" id="statush">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#statusc" aria-expanded="false" aria-controls="statusc">
                                                        <div class="row">
                                                            <div class="col-10 col-sm-10 text-left fs-14">Proponente</div>
                                                            <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                        </div>
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="statusc" class="collapse" aria-labelledby="statush" data-parent="#status">
                                                <div class="card-body">
                                                <?php while ($miembros_ciudad->have_posts()) {
                                                        $miembros_ciudad->the_post(); ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="temas" id="t_<?php echo get_the_ID(); ?>" value="t-<?php echo get_the_ID(); ?>">
                                                            <label class="form-check-label" for="t_<?php echo get_the_ID(); ?>">
                                                                <?php echo get_the_title(); ?>
                                                            </label>
                                                        </div>
                                                    <?php } // End While org politicas 
                                                    ?>
                                                    <p class="ta-r bold"><span class="clean-radio" data-radio="status">Desactivar filtro</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } // END Statuses */
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
                                <?php if ($comisiones_city->have_posts()) { ?>
                                    <div id="comision">
                                        <div class="card">
                                            <div class="card-header" id="comisionh">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#comisionc" aria-expanded="false" aria-controls="comisionc">
                                                        <div class="row">
                                                            <div class="col-10 col-sm-10 text-left fs-14">Comisión</div>
                                                            <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                        </div>
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="comisionc" class="collapse" aria-labelledby="comisionh" data-parent="#comision">
                                                <div class="card-body">
                                                    <?php while ($comisiones_city->have_posts()) {
                                                        $comisiones_city->the_post(); ?>
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
                                <?php } // END comisiones 
                                ?>
                                <!--
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
                <?php if ($documentos->have_posts()) { ?>                    
                    <div class="row oc-paginator d-none">
                        <div class="col">
                            <hr />
                            <ul class="list-no-style d-flex justify-content-end pagination-list" data-maxgroup="0">
                                <li id="pag_indicator">1</li>
                                <li>-</li>
                                <li><span id="maxgroup">0</span></li>
                                <li class="paginate-link page-prev-alt" title="Anterior"><i class="fa fa-chevron-left"></i></li>
                                <li class="paginate-link page-next-alt" title="Siguiente"><i class="fa fa-chevron-right"></i></li>
                            </ul>
                        </div>
                    </div>                       
                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="accordion listado-documentos no-paginator" id="listadodocumentos">
                                <?php
                                $counter = 0;
                                while ($documentos->have_posts()) {
                                    $documentos->the_post();
                                    $comision_ID = get_the_ID();
                                    $comision = '';
                                    $comision = get_post_meta(get_the_ID(), 'oda_ordenanza_comision', true);
                                    $proponente = get_post_meta(get_the_ID(), 'oda_ordenanza_proponente', true);
                                    $iniciativa = get_post_meta(get_the_ID(), 'oda_ordenanza_iniciativa', true);
                                    $estado = get_post_meta(get_the_ID(), 'oda_ordenanza_estado', true);
                                    $partidos_documento = get_partido_politico_documento(get_the_ID(), 'ordenanza');
                                    $fecha_documento = get_post_meta(get_the_ID(), 'oda_ordenanza_fecha', true);
                                    $tema_documento = get_post_meta(get_the_ID(), 'oda_ordenanza_incidencia_temas', true);
                                    $args = array(
                                        'post_type' => 'observacion',
                                        'posts_per_page' => -1,
                                        'meta_query' => array(
                                            array(
                                                'key' => 'oda_observacion_ordenanza',
                                                'value' => get_the_ID(),
                                                'compare' => '='
                                            )
                                        )
                                    );
                                    $orbservaciones = new WP_Query($args);
                                    if ($orbservaciones->have_posts()) {
                                ?>
                                <div class="card documento<?php
                                                            echo ($comision) ? ' com-' . $comision . '' : '';
                                                            echo ($estado) ? ' s-' . $estado . '' : '';
                                                            echo ($tema_documento) ? ' t-' . $tema_documento . '' : '';
                                                            if ($partidos_documento) {
                                                                foreach ($partidos_documento as $partido_documento) {
                                                                    echo ' org-' . $partido_documento;
                                                                }
                                                            }
                                                            ?>" data-date="<?php echo ($fecha_documento) ? date('U', strtotime($fecha_documento)) : ''; ?>">
                                    <div class="card-header px-0" id="heading-<?php echo $comision_ID; ?>">
                                        <h2 class="mb-0 fs-16 lh-1 hover-underlined">
                                            <a class="text-left text-black-light collapsed cursor-pointer" data-toggle="collapse" data-target="#collapse-<?php echo $comision_ID; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $comision_ID; ?>">
                                                <span class="documento-title"><?php echo get_the_title($comision_ID); ?></span>
                                            </a>
                                        </h2>
                                        <?php  if ($proponente) { ?>
                                        <div class="w-100 d-flex justify-content-between align-items-center">
                                            <span><strong>Proponente:</strong> <i><?php echo $proponente; ?></i></span>
                                        <?php } else { ?>
                                        <div class="w-100 d-flex justify-content-end align-items-center">
                                        <?php } ?>
                                            <a class="bold cursor-pointer" data-toggle="collapse" data-target="#collapse-<?php echo $comision_ID; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $comision_ID; ?>">(Ver más)</a>
                                        </div>
                                    </div>
                                    <div id="collapse-<?php echo $comision_ID; ?>" class="collapse" aria-labelledby="heading-<?php echo $comision_ID; ?>" data-parent="#listadodocumentos">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-8 offset-md-2">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th class="align-middle" style="text-align:center; line-height:1" scope="col">Proponente</th>
                                                                <th class="align-middle" style="text-align:center; line-height:1" scope="col">Observación</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                while ($orbservaciones->have_posts()) {
                                                                    $orbservaciones->the_post();
                                                                    $proponente = get_post_meta(get_the_ID(), 'oda_observacion_miembro', true);
                                                                    $documento_obs = get_post_meta(get_the_ID(), 'oda_observacion_documento', true);
                                                                    echo '<tr>';
                                                                    echo '<td class="align-middle" style="text-align:center; line-height:1">' . get_the_title($proponente). '</td>';
                                                                    echo '<td class="align-middle" style="text-align:center; line-height:1">';
                                                                    if ($documento_obs){
                                                                    ?>
                                                                    <a class="link-btn" href="<?php echo $documento_obs; ?>" target="_blank">
                                                                    <div class="btn-oda view-ranking">
                                                                        <span class="button-name">Ver</span>
                                                                        <span class="button-icon"><i class="fas fa-chevron-down"></i></span>
                                                                    </div>
                                                                    </a>
                                                                    <a class="link-btn" href="<?php echo $documento_obs; ?>" download>
                                                                    <div class="btn-oda excel-ranking">
                                                                        <span class="button-name">Descargar</span>
                                                                        <span class="button-icon"><i class="fas fa-download"></i></span>
                                                                    </div>
                                                                    </a>
                                                                    <?php 
                                                                    }
                                                                    echo '</td>';
                                                                    echo '</tr>';
                                                                } //E END While Observacione
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } //End IF Observaciones ?>
                                <?php $counter++; } // END While ?>
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
                    <button type="button" class="btn btn-secondary rounded-0">Excel <i class="fas fa-download"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-secondary rounded-0">CSV <i class="fas fa-download"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function() {
        // Paginacion
        var paginaDoc = 1;
        var groups = 0;
        var maxelements = 8;
        var maxgroup = 0;
        var elementos = $('.listado-documentos .card');
        $('.listado-documentos .card').addClass('group');
        if (elementos.length > maxelements){
            $('.oc-paginator').removeClass('d-none');
            $.each(elementos, function(i,v) {
                if(i % maxelements == 0){
                    groups++;
                }
                $(this).addClass('group-' + groups);
                if(groups > 1){
                    $(this).hide();
                }
                console.log(groups);
            })
            $('#maxgroup').html(groups);
        }

        // Botones paginacion listados
        $('.page-next-alt').click( function(){
            if (paginaDoc < groups){
                paginaDoc++;
                $('.card.group').hide();
                $('.card.group-' + paginaDoc).show();
                $('#pag_indicator').html(paginaDoc);
                console.log(paginaDoc);
            }
        })
        
        $('.page-prev-alt').click( function(){
            if (paginaDoc <= groups){
                console.log(paginaDoc);
                if (paginaDoc != 1){
                    paginaDoc--;
                    $('.card.group').hide();
                    $('.card.group-' + paginaDoc).show();
                    $('#pag_indicator').html(paginaDoc);
                    console.log(paginaDoc);
                }
            }
        })



        var cityid = <?php echo $item->ID; ?>;
        var citiName = '<?php echo $item->post_title; ?>';
        // Clic en EXCEL
        $('.excel-ranking').click( function(){            
            $.ajax({
                url: oda_dom_vars.ajaxurl,
                type: 'GET',
                data: {
                    action: 'oda_generate_observaciones_xls',
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
                    $a.attr("download","OC_observaciones_proyectos_ordenanzas_concejo_municipal_"+citiName+".xls");
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
                    action: 'oda_generate_csv_observaciones',
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
                    a.download = 'OC_observaciones_proyectos_ordenanzas_concejo_municipal_'+citiName+'.csv';
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
    
</script>