<?php 
get_header();
global $wp_query;

the_post(); 
$the_member = get_page_by_path($wp_query->query_vars['member_name'], OBJECT, 'miembro');
$the_member_city = get_post_meta($the_member->ID, 'oda_ciudad_owner', true);
$curul = get_post_meta($the_member->ID, 'oda_miembro_curul', true);
$plan = get_post_meta($the_member->ID, 'oda_miembro_pdf_plan', true);
$link_radiografia = get_post_meta($the_member->ID, 'oda_miembro_rpurl', true);
$thumbnail_url = get_the_post_thumbnail_url($the_member->ID, 'medium');


// Circunscripcion
$circunscripcion = get_circunscripcion_miembro($the_member->ID);
// Organizacion politica
$org_politica = get_post_meta($the_member->ID, 'oda_partido_owner', true);
$partido_politico_object = get_post($org_politica, OBJECT);
$partido_politico_color_principal = get_post_meta($org_politica, 'oda_partido_color_principal', true);
$partido_politico_color_secundario = get_post_meta($org_politica, 'oda_partido_color_secundario', true);
$partido_politico_logo = get_the_post_thumbnail_url($org_politica);
// Comisiones del miembro
$comisiones_ordenadas = get_comisiones_miembro($the_member->ID, $the_member_city);
$city_color = get_post_meta($the_member_city, 'oda_ciudad_color', true);
?>
<style>
    .fa-chevron-left,
    .fa-chevron-right {
        color: black;
    }
    .carousel-control-next, .carousel-control-prev {
        width: 13%;
    }
    .carousel-control-next { right: -5%;}
    .carousel-control-prev { left: -5%; }
    .otros-miembros .fa-chevron-left,
    .otros-miembros .fa-chevron-right{
        color: #A4A4A4!important;
    }
    .single_miembro_thumbnail {
        position: relative;
    }
    .white-gradient {
        height: 80px;
        position: absolute;
        bottom: 0;
        background: linear-gradient(0deg, rgba(236,236,236,1) 10%, rgba(236,236,236,0) 100%);
        width: 100%;
        opacity: 1;
    }
    .profile-content-selector { 
        display: flex;
        max-width: 240px;
        margin: 0 auto;
        justify-content: space-around;
        align-items: center;
        border-radius: 20px;
        overflow: hidden;
        background: gray;
    }
    .profile-content-selector li { padding: 5px 20px; }
    .profile-content-selector li.member-info { width: 100px; }
    .profile-content-selector li.member-stats { width: 140px; }
    .profile-content-selector li:hover { cursor: pointer; background: #2b2b2b; transition: all ease-in .2s; }
    .profile-content-selector li.active { background: <?php echo $city_color; ?>; font-weight: bold; }
    .member-container.hidden { display: none; }
    .stat-item-number {
        width:150px;
        height:150px;
        margin: 0 auto;
        display: flex;
        border-radius: 50%;
        background: <?php echo $city_color; ?>;
        font-size: 60px;
        font-weight: bold;
        color: white;
        justify-content: center;
        align-items: center;
    }
</style>
<section class="main-container">
    <div class="container pt-3 pb-3">
        <div class="row pt-4 pb-4">
            <div class="col-sm-12 text-center">
                <ul class="list-unstyled profile-content-selector">
                    <li class="member-container-selector member-info active" data-target="member-info"><span class="text-white">Perfil</span></li>
                    <li class="member-container-selector member-stats" data-target="member-stats"><span class="text-white">Estadísticas</span></li>
                </ul>
            </div>
        </div>
        <div id="member-info" class="member-container member-info-container">
            <div class="row pt-3 pb-3">
                <div class="col-md-3 bg-ececec pt-3 mb-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="text-center fs-18 bold">Organización Política</h3>
                            <div class="row">
                                <div class="col-4 col-sm-4">
                                    <?php
                                    if (!empty($partido_politico_logo)) {
                                        echo '<img class="img-fluid" src="' . $partido_politico_logo . '" alt="">';
                                    }
                                    ?>
                                </div>
                                <div class="col-8 col-sm-8">
                                    <h4 class="fs-16 mt-2"><?php echo $partido_politico_object->post_title; ?></h4>
                                </div>
                            </div>
                            <hr />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h3 class=" text-center fs-18 bold">Plan de trabajo del partido</h3>
                            <div class="row">
                                <div class="col-3 offset-3 col-sm-3 offset-sm-3 text-center pt-1">
                                    <?php
                                    if (!empty($plan)) {
                                        echo '<a class="ta-c mt-2 text-black-light" href="' . $plan . '" target="_blank"><i class="far fa-file-alt fs-36"></i></a>';
                                    }else{
                                    ?>
                                    <a class="ta-c mt-2 text-black-light" href="#" data-toggle="modal" data-target="#reques_program"><i class="far fa-file-alt fs-36"></i></a>
                                    <div class="modal fade" id="reques_program">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
                                                <div class="modal-body">
                                                    <img class="img-fluid" src="<?php echo THEME_URL . '/img/pla-de-trabajo-pop-up.png'; ?>">
                                                    <!--
                                                    <div class="d-flex h-100 flex-column justify-content-center align-items-center">
                                                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_post_meta(get_post_meta($the_member->ID,'oda_ciudad_owner', true),'oda_ciudad_tweet_folders', true) . ' - ' . get_post_meta($the_member->ID,'oda_miembro_twitter', true)); ?>" style="text-decoration: none;">
                                                            <span class="twitter-circle-icon"><i class="fab fa-twitter text-white fs-20"></i></span>
                                                        </a>
                                                        <br />
                                                        <p>¡Envía un Tweet a tu Concejo!</p>
                                                    </div>
                                                    <p>Exige al Concejal que transparente esta información</p>
                                                    -->
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    <?php 
                                    }
                                    ?>
                                </div>
                                <div class="col-3 col-sm-3 text-center">
                                    <p class="lh-0 mt-3"><?php echo date('Y', strtotime(PERIOD_BEGINS)); ?></p>
                                    <p class="lh-0"><?php echo date('Y', strtotime(PERIOD_BEGINS)) + 4; ?></p>
                                </div>
                            </div>
                            <hr />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="text-center fs-18 bold">Comisiones que integra</h3>
                            <?php if (count($comisiones_ordenadas) > 0 ){ ?>
                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <?php 
                                        $i = 0;
                                        foreach( $comisiones_ordenadas as $comision ){
                                    ?>
                                    <div class="carousel-item text-center pl-1 pr-1 <?php echo ($i == 0) ? 'active' : ''; ?>">
                                        <h4 class="fs-18 mt-3"><?php echo $comision['nombre']; ?></h4>
                                        <p><i><?php echo $comision['cargo']; ?></i></p>
                                    </div>
                                    <?php $i++; } ?>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                            <?php }else{ ?>
                                <h4 class="fs-18 mt-3 ta-c">No pertenece a ninguna comisión</h4>
                            <?php } ?>
                            <hr />
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center mb-3">
                    <h1 class="fs-36"><?php echo get_post_meta($the_member->ID, 'oda_miembro_nombres', true);//$the_member->post_title; ?><br /><strong><?php echo get_post_meta($the_member->ID, 'oda_miembro_apellidos', true); ?></strong></h1>
                    <p class="mb-1"><?php echo ($curul == 1) ? 'Alcalde' : 'Concejal <span class="bold"> ' . $circunscripcion->post_title . '</span>'; ?> </p>
                    <?php
                    if (!empty($thumbnail_url)) {
                        echo '<div class="single_miembro_thumbnail">';
                        echo '<img class="img-fluid" src="' . $thumbnail_url . '">';
                        echo '<div class="white-gradient1"></div>';
                        echo '</div>';
                    }
                    ?>
                </div>
                <div class="col-md-3 bg-ececec pt-3 pb-2">
                    <?php if (!empty($link_radiografia)) { ?>
                    <div class="row pt-2 pb-1">
                        <div class="col-sm-12 text-center">
                            <h3 class="text-center fs-18 mb-3">Conoce más a tu <strong>Concejal</strong>, ingresando a:</h3>
                            <a class="mb-3 mt-3" href="<?php echo $link_radiografia; ?>" target="_blank">
                                <img class="img-fluid" src="<?php echo THEME_URL; ?>/img/radiografia-politica-logo.PNG">
                            </a>                    
                        </div>
                    </div>
                    <div class="row mt-2 mb-2">
                        <div class="col-sm-12">
                            <hr />
                        </div>
                    </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_post_meta(get_post_meta($the_member->ID,'oda_ciudad_owner', true),'oda_ciudad_tweet_profile', true) . ' - ' . get_post_meta($the_member->ID,'oda_miembro_twitter', true)); ?>" target="_blank" style="text-decoration: none;">
                                <span class="twitter-circle-icon"><i class="fab fa-twitter text-white fs-20"></i></span>
                            </a>
                            <p class="mt-1"><strong>Mándale un tweet</strong><br />y pídele que sea parte de Concejo Transparente</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="member-stats" class="member-container member-stats-container hidden">
            <div class="row pt-3 pb-3">
                <div class="col-md-4 bg-ececec pt-3 mb-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-12" style="position: relative;">
                                    <?php
                                    if (!empty($partido_politico_logo)) {
                                        echo '<img style="position: absolute;" width="50" src="' . $partido_politico_logo . '" alt="">';
                                    }
                                    ?>
                                </div>
                                <?php
                                if (!empty($thumbnail_url)) {
                                    echo '<div class="single_miembro_thumbnail w-100">';
                                    echo '<img class="img-fluid m0-auto d-block" src="' . $thumbnail_url . '">';
                                    echo '<div class="white-gradient1"></div>';
                                    echo '</div>';
                                }
                                ?>
                                <div class="col-12 mt-3">
                                <h1 class="fs-18 ta-c"><?php echo get_post_meta($the_member->ID, 'oda_miembro_nombres', true);//$the_member->post_title; ?> <strong><?php echo get_post_meta($the_member->ID, 'oda_miembro_apellidos', true); ?></strong></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <?php 
                        $stats = estadisticas_del_miembro($the_member->ID); 
                        $docs = documentos_del_miembro($the_member->ID);    
                        //var_dump($docs);
                    ?>
                    <div id="carouselStats" class="carousel slide pl-3 pr-3" data-ride="carousel">
                        <div class="carousel-inner pl-3 pr-3">
                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h3 class="fs-20 ta-c">Total de <strong>votaciones posibles: <?php echo ($stats['asistencias'] + $stats['ausencias'] + $stats['delego']) ?></strong></h3>
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="stat-item-placeholder">
                                                    <div class="stat-item-number">
                                                        <span><?php echo ($stats['asistencias']); ?></span>
                                                    </div>
                                                    <div class="stat-item-element"></div>
                                                </div>
                                                <p class="ta-c bold">Asistencias</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="stat-item-placeholder">
                                                    <div class="stat-item-number">
                                                        <span><?php echo ($stats['ausencias']); ?></span>
                                                    </div>
                                                    <div class="stat-item-element"></div>
                                                </div>
                                                <p class="ta-c bold">Ausencias</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="stat-item-placeholder">
                                                    <div class="stat-item-number">
                                                        <span><?php echo ($stats['delego']); ?></span>
                                                    </div>
                                                    <div class="stat-item-element"></div>
                                                </div>
                                                <p class="ta-c">Realizadas por el <strong>suplente</strong></p>
                                            </div>
                                        </div>
                                        <br />
                                        <?php 
                                            $titularizado = get_post_meta($the_member->ID, 'oda_miembro_titularizado', true); 
                                            if($titularizado){
                                        ?>
                                        <p class="ta-c">* El total de votaciones posibles de este miembro del Concejo difiere del resto debido a que se titularizó tras la renuncia/ausencia del titular.</p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h3 class="fs-20 ta-c"><strong>Gestión</strong> de tu representante</h3>
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-5 offset-md-1">
                                                <div class="stat-item-placeholder">
                                                    <div class="stat-item-number">
                                                        <span><?php echo ($docs['ordenanzas']); ?></span>
                                                    </div>
                                                    <div class="stat-item-element"></div>
                                                </div>
                                                <p class="ta-c">Proyectos de <strong>ordenanzas presentados</strong></p>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="stat-item-placeholder">
                                                    <div class="stat-item-number">
                                                        <span><?php echo ($docs['resoluciones']); ?></span>
                                                    </div>
                                                    <div class="stat-item-element"></div>
                                                </div>
                                                <p class="ta-c">Proyectos de <strong>resoluciones presentados</strong></p>
                                            </div>
                                        </div>
                                        <br />
                                        <br />
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                            <div class="row">
                                    <div class="col-sm-12">
                                        <h3 class="fs-20 ta-c"><strong>Gestión</strong> de tu representante</h3>
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-5 offset-md-1">
                                                <div class="stat-item-placeholder">
                                                    <div class="stat-item-number">
                                                        <span><?php echo ($docs['ordenanzas']); ?></span>
                                                    </div>
                                                    <div class="stat-item-element"></div>
                                                </div>
                                                <p class="ta-c">Observaciones a <br/><strong>Proyectos de ordenanzas</strong></p>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="stat-item-placeholder">
                                                    <div class="stat-item-number">
                                                        <span><?php echo ($docs['resoluciones']); ?></span>
                                                    </div>
                                                    <div class="stat-item-element"></div>
                                                </div>
                                                <p class="ta-c">Solucitudes de <br /><strong>información</strong></p>
                                            </div>
                                        </div>
                                        <br />
                                        <br />
                                        <br />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselStats" role="button" data-slide="prev">
                            <i class="fas fa-chevron-left fs-36"></i>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselStats" role="button" data-slide="next">
                            <i class="fas fa-chevron-right fs-36"></i>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $args = array(
            'post_type' => 'miembro',
            'posts_per_page' => -1,
            'meta_key' => 'oda_miembro_curul',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'post__not_in' => array($the_member->ID),
            'meta_query' => array(
                array(
                    'key' => 'oda_ciudad_owner',
                    'value' => $the_member_city,
                    'compare' => '=',
                )
            )
        );
        $otros_miembros = new WP_Query($args);
        if ($otros_miembros->have_posts()) {
            $i = 1;
        ?>
        <div class="row">
            <div class="col-sm-12 text-center otros-miembros carousel-placeholder bg-ececec pt-3 pb-3">
                <style scoped>
                    .item-placeholder {
                        position: relative;
                    }
                    .partido-logo-carousel {
                        position: absolute;
                        top: 10px;
                        left: 0;
                    }
                </style>
                <div id="carouselExampleControls" class="carousel slide pl-3 pr-3" data-ride="carousel">

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner pl-3 pr-3">
                        <div class="carousel-item active">
                            <div class="row">
                                <?php 
                                    while ($otros_miembros->have_posts()) {
                                        $otros_miembros->the_post(); 
                                        $partido_logo = get_post_meta(get_the_ID(), 'oda_partido_owner', true);
                                        $partido_logo = get_the_post_thumbnail_url($partido_logo, 'thumbnail');
                                        $nombre = explode(' ', get_post_meta(get_the_ID(), 'oda_miembro_nombres', true));
                                        $apellido = explode(' ', get_post_meta(get_the_ID(), 'oda_miembro_apellidos', true));
                                ?>
                                    <div class="col-md-3 item-placeholder">
                                        <a href="<?php echo get_the_permalink(get_the_ID()); ?>">
                                        <div class="single_miembro_thumbnail">
                                        <img class="partido-logo-carousel" width="40" src="<?php echo $partido_logo; ?>">
                                        <?php if (has_post_thumbnail()) { ?>
                                            <img class="img-fluid" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>">
                                        <?php } else { ?>
                                            <img class="img-fluid" src="https://via.placeholder.com/250/?text=<?php echo $nombre[0] . '%20' . $apellido[0]; ?>">
                                        <?php } ?>
                                            <div class="white-gradient"></div>
                                        </div>
                                        <h4 class="fs-16 text-center text-black-light mt-2 mb-3"><?php echo get_the_title(); ?></h4>
                                        </a>
                                    </div>
                                <?php 
                                    if ( $i % 4 == 0){
                                ?>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <?php
                                    }
                                ?>
                                <?php 
                                        $i++;
                                    } // End While 
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Controls -->
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <i class="fas fa-chevron-left fs-36"></i>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <i class="fas fa-chevron-right fs-36"></i>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
        <?php
        } // END if
        wp_reset_query();
        ?>
    </div>
</section>
<?php get_footer(); ?>
<script>
    $(document).ready( function() {
        $('.member-container-selector').click( function() {
            var target = $(this).data('target')
            $('.member-container-selector').removeClass('active');
            $('.'+ target).addClass('active');
            $('.member-container').addClass('hidden');
            $('#'+ target).removeClass('hidden');

        })
    })
</script>