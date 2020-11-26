<?php get_header();
global $wp_query;
the_post();
$ciudad = $wp_query->query_vars['city_slug'];
$item = get_page_by_path($ciudad, OBJECT, 'ciudad');
$city_primary_color = get_post_meta($item->ID, 'oda_ciudad_color', true);
$ranking = get_ranking_votaciones($item->ID);
$id = array_column($ranking,'id');
$name = array_column($ranking,'apellidos');
$explain = '<p>El cuadro de datos responde no solo a un aspecto cuantitativo sino también a un orden alfabético.</p>';
?>
<style>
    .info-sign-filters { color: <?php echo $city_primary_color; ?>; }
    .bg-light { background-color: #F5F5F5; }
    .data-frame {
        position: relative;
        min-height: 350px;
        *background: #d1d1d1;
    }
    .data-placeholder,
    .data-header { display: none; }
    .data-placeholder.activated,
    .data-header.activated { display: block; }
    .podio-profile { max-width: 90px; }
    .podio-profile.order-1 { margin-right: -10px; }
    .podio-profile.order-2 { z-index:9; }
    .podio-profile.order-2 img { box-shadow: 1px 2px 5px rgba(0,0,0,0.5); }
    .podio-profile.order-3 { margin-left: -10px; }
    .podio-profile.podio-first {max-width: 140px;}
    .podio-profile img { background: #B4B1B4; }
    select.form-control { height: auto; }
    select:focus > option:checked { background: #c4c4c4 !important; }
    select:focus > option:hover { background: red; }
    .table .thead-light th { background-color: #D9D9D9; }
    .table-hover tbody tr:hover { background-color: #C6C6C6; }

    
</style>
<section class="main-container pt-4" data-cityname="<?php echo $item->post_name; ?>">
    <div class="container pt-4">
        <div class="row asistencias data-header activated">
            <div class="col-sm-12 col-lg-8 offset-lg-2 text-center mb-2">
                <h1>
                    <span class="bold">Miembros</span> del <span class="bold">Concejo</span> con más <span class="bold">asistencias</span> a <span class="bold">votaciones</span>
                </h1>
            </div>
            <div class="col-sm-12 my-3">
                <p>Número de asistencias de los miembros del Concejo respecto al total de posibles votaciones desde su incorporación. <strong>Las votaciones que se han realizado hasta el momento son: <?php echo get_mociones_ciudad($item->ID)->post_count; ?></strong>.</p>
            </div>
        </div>
        <div class="row ausencias data-header">
            <div class="col-sm-12 col-lg-8 offset-lg-2 text-center">
                <h1>
                    <span class="bold">Miembros</span> del <span class="bold">Concejo</span> con más <span class="bold">ausencias</span> a <span class="bold">votaciones</span>
                </h1>
            </div>
            <div class="col-sm-12 my-3">
                <p>Número de ausencias de los miembros del Concejo respecto al total de posibles votaciones desde su incorporación. <strong>Las votaciones que se han realizado hasta el momento son: <?php echo get_mociones_ciudad($item->ID)->post_count; ?></strong>.</p>
            </div>
        </div>
        <div class="row suplentes data-header">
            <div class="col-sm-12 col-lg-8 offset-lg-2 text-center"><h1><span class="bold">Participación</span> del <span class="bold">suplente</span> en <span class="bold">votaciones</span></h1></div>
            <div class="col-sm-12 my-3">
                <p>Número de participación del suplente respecto al total de posibles votaciones desde su incorporación. <strong>Las votaciones que se han realizado hasta el momento son: <?php echo get_mociones_ciudad($item->ID)->post_count; ?></strong>.</p>
            </div>
        </div>
        <div class="row ordenanzas data-header">
            <div class="col-sm-12 col-lg-8 offset-lg-2 text-center"><h1><span class="bold">Proyectos</span> de <span class="bold">ordenanas</span></h1></div>
            <div class="col-sm-12 my-3">
                <p>Número de proyectos de ordenanzas presentados por miembros del Concejo. Los proyectos de ordenanzas presentados hasta el momento son: <?php echo get_ordenanzas_ciudad($item->ID)->post_count; ?></strong>.</p>
            </div>
        </div>
        <div class="row resoluciones data-header">
            <div class="col-sm-12 col-lg-8 offset-lg-2 text-center"><h1><span class="bold">Proyectos</span> de <span class="bold">resoluciones</span></h1></div>
            <div class="col-sm-12 my-3">
                <p>Número de proyectos de resoluciones presentados por miembros del Concejo. Los proyectos de resoluciones presentados hasta el momento son: <?php echo get_resoluciones_ciudad($item->ID)->post_count; ?></strong>.</p>
            </div>
        </div>
        <div class="row observaciones data-header">
            <div class="col-sm-12 col-lg-8 offset-lg-2 text-center"><h1><span class="bold">Observaciones</span> de <span class="bold">proyectos</span> de <span class="bold">ordenanzas</span></h1></div>
            <div class="col-sm-12 my-3">
                <p>Número de observaciones a proyectos de ordenanzas presentadas por miembros del Concejo. Las observaciones a proyectos de ordenanzas presentadas hasta el momento son: <?php echo get_observaciones_ciudad($item->ID)->post_count; ?></strong>.</p>
            </div>
        </div>
        <div class="row solicitudes data-header">
            <div class="col-sm-12 col-lg-8 offset-lg-2 text-center"><h1><span class="bold">Solicitudes</span> de <span class="bold">información</span></h1></div>
            <div class="col-sm-12 my-3">
                <p>Número de solicitudes de información presentadas por miembros del Concejo. Las solicitudes de información presentadas hasta el momento son: <?php echo get_solicitudes_informacion($item->ID)->post_count; ?></strong>.</p>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 col-lg-3 pt-3">
                <div class="row mb-4 mt-3">
                    <div class="col-md-10">
                        <p class="bold fs-14 text-muted">Participación en votaciones</p>
                    </div>
                    <div class="col-md-2">
                        <a href="#" data-toggle="modal" data-target="#howtofilters">
                            <i class="fas fa-info-circle info-sign-filters fs-26 mb-3"></i>
                        </a>
                    </div>
                    <div class="modal fade" id="howtofilters">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
                                <div class="modal-body">
                                    <div class="row">
                                        <?php
                                            $image_url = 'http://placehold.it/800x600?text=Manual';
                                            $imagen_popup = get_post_meta($item->ID, 'oda_ciudad_popupinfo_ordenanza', true);
                                            $imagen_popup = '';
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
                    <div class="col-sm-12">
                        <select id="votaciones" class="form-control rounded-0 bg-light py-3 fs-18">
                            <option value="">Seleccione</option>
                            <option value="asistencias" selected>Asistencias a votaciones</option>
                            <option value="ausencias">Ausencias a votaciones</option>
                            <option value="suplentes">Participación suplente</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="bold fs-14 text-muted">¿Qué ha hecho el Concejo?</p>
                    </div>
                    <div class="col-sm-12">
                    <select id="concejo" class="form-control rounded-0 bg-light py-3 fs-18">
                            <option value="">Seleccione</option>
                            <option value="ordenanzas">Proyectos de ordenanzas</option>
                            <option value="resoluciones">Proyectos de resoluciones</option>
                            <option value="observaciones">Observaciones a proyectos de ordenanza</option>
                            <option value="solicitudes">Solicitudes de información</option>
                            <?php /*
                            <option value="comparecencias">Solicitudes de comparecencias</option>
                            */ ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-9 data-frame">

                <div data-city="<?php echo $item->ID; ?>" data-target="asistencias" id="asistencias" class="data-placeholder activated">
                    <?php if (get_mociones_ciudad($item->ID)->post_count > 0) { ?>
                    <div class="row my-3">
                        <div class="col-sm-12 col-lg-6 offset-lg-6 text-right">
                            <div class="btn-oda view-ranking">
                                <span class="button-name">Ver</span>
                                <span class="button-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
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
                    <?php include('evaluacion/datos-asistencia-template.php'); echo $explain; 
                    }else{ 
                        mostrar_imagen_twitter_evaluacion('mociones', $item->ID);
                    } ?>
                </div>
                <div data-city="<?php echo $item->ID; ?>" data-target="ausencias" id="ausencias" class="data-placeholder">
                    <?php if (get_mociones_ciudad($item->ID)->post_count > 0) { ?>
                    <div class="row my-3">
                        <div class="col-sm-12 col-lg-6 offset-lg-6 text-right">
                            <div class="btn-oda view-ranking">
                                <span class="button-name">Ver</span>
                                <span class="button-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
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
                <?php include('evaluacion/datos-ausencias-template.php'); echo $explain; 
                    }else{ 
                        mostrar_imagen_twitter_evaluacion('mociones', $item->ID);
                    } ?>
                </div>
                <div data-city="<?php echo $item->ID; ?>" data-target="suplentes" id="suplentes" class="data-placeholder">
                    <?php if (get_mociones_ciudad($item->ID)->post_count > 0) { ?>
                    <div class="row my-3">
                        <div class="col-sm-12 col-lg-6 offset-lg-6 text-right">
                            <div class="btn-oda view-ranking">
                                <span class="button-name">Ver</span>
                                <span class="button-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
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
                    <?php include('evaluacion/datos-suplentes-template.php'); echo $explain; 
                    }else{ 
                        mostrar_imagen_twitter_evaluacion('mociones', $item->ID);
                    } ?>
                </div>
                <div data-city="<?php echo $item->ID; ?>" data-target="ordenanzas" id="ordenanzas" class="data-placeholder">
                    <?php if (get_ordenanzas_ciudad($item->ID)->post_count > 0) { ?>
                    <div class="row my-3">
                        <div class="col-sm-12 col-lg-6 offset-lg-6 text-right">
                            <div class="btn-oda view-ranking">
                                <span class="button-name">Ver</span>
                                <span class="button-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
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
                <?php include('evaluacion/datos-ordenanzas-template.php'); echo $explain; 
                    }else{ 
                        mostrar_imagen_twitter_evaluacion('ordenanzas', $item->ID);
                    } ?>
                </div>
                <div data-city="<?php echo $item->ID; ?>" data-target="resoluciones" id="resoluciones" class="data-placeholder">
                    <?php if (get_resoluciones_ciudad($item->ID)->post_count > 0) { ?>
                    <div class="row my-3">
                        <div class="col-sm-12 col-lg-6 offset-lg-6 text-right">
                            <div class="btn-oda view-ranking">
                                <span class="button-name">Ver</span>
                                <span class="button-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
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
                <?php include('evaluacion/datos-resoluciones-template.php'); echo $explain; 
                    }else{ 
                        mostrar_imagen_twitter_evaluacion('resoluciones', $item->ID);
                    } ?>
                </div>
                <div data-city="<?php echo $item->ID; ?>" data-target="observaciones" id="observaciones" class="data-placeholder">
                    <?php if (get_observaciones_ciudad($item->ID)->post_count > 0) { ?>
                    <div class="row my-3">
                        <div class="col-sm-12 col-lg-6 offset-lg-6 text-right">
                            <div class="btn-oda view-ranking">
                                <span class="button-name">Ver</span>
                                <span class="button-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
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
                <?php include('evaluacion/datos-observaciones-template.php'); echo $explain; 
                    }else{ 
                        mostrar_imagen_twitter_evaluacion('observaciones', $item->ID);
                    } ?>
                </div>
                <div data-city="<?php echo $item->ID; ?>" data-target="solicitudes" id="solicitudes" class="data-placeholder">
                    <?php if (get_observaciones_ciudad($item->ID)->post_count > 0) { ?>
                    <div class="row my-3">
                        <div class="col-sm-12 col-lg-6 offset-lg-6 text-right">
                            <div class="btn-oda view-ranking">
                                <span class="button-name">Ver</span>
                                <span class="button-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
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
                <?php include('evaluacion/datos-solicitudes-template.php'); echo $explain; 
                    }else{ 
                        mostrar_imagen_twitter_evaluacion('solicitudes', $item->ID);
                    } ?>
                </div>
                <?php /*
                <div id="comparecencias" class="data-placeholder"><?php include('evaluacion/datos-solicitudes-template.php') ?></div>
                */ ?>
            </div>
        </div>
</section>
<?php get_footer(); ?>
<script>
    $(document).ready(function(){
        $('#votaciones').change(function(){
            console.log($(this). val())
            $('.data-placeholder').removeClass('activated')
            $('.data-header').removeClass('activated')
            $('#'+$(this). val()).addClass('activated');
            $('.'+$(this). val()).addClass('activated');
            $('#concejo').prop('selectedIndex',0);
            gotoTop();
        })
        $('#concejo').change(function(){
            console.log($(this). val())
            $('.data-placeholder').removeClass('activated')
            $('.data-header').removeClass('activated')
            $('#'+$(this). val()).addClass('activated');
            $('.'+$(this). val()).addClass('activated');
            $('#votaciones').prop('selectedIndex',0);
            gotoTop();
        })
        $('.table-show-more').click(function(e){
            var target = $(this).parents('.data-placeholder').find('.hidden-row').toggleClass('d-none');
            e.preventDefault;
            $(this).remove()
            return false;
        })
    })
    function gotoTop(){
        $('html, body').animate({scrollTop:0},500);
    }
</script>