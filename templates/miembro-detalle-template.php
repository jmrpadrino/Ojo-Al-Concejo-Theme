<?php get_header();
the_post(); ?>
<?php
global $wp_query;
$the_member = get_page_by_path($wp_query->query_vars['member_name'], OBJECT, 'miembro');
$the_member_city = get_post_meta($the_member->ID, 'oda_ciudad_owner', true);
$plan = get_post_meta($the_member->ID, 'oda_miembro_pdf_plan', true);
$link_radiografia = get_post_meta($the_member->ID, 'oda_miembro_rpurl', true);
$circunscripcion = get_post(
    get_post_meta($the_member->ID, 'oda_circunscripcion_owner', true),
    OBJECT
);

$thumbnail_url = get_the_post_thumbnail_url($the_member->ID, 'medium');

// Organizacion politica
$org_politica = get_post_meta($the_member->ID, 'oda_partido_owner', true);
$partido_politico_object = get_post($org_politica, OBJECT);
$partido_politico_color_principal = get_post_meta($org_politica, 'oda_partido_color_principal', true);
$partido_politico_color_secundario = get_post_meta($org_politica, 'oda_partido_color_secundario', true);
$partido_politico_logo = get_the_post_thumbnail_url($org_politica);

// Comisiones
$args = array(
    'post_type' => 'comision',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => 'oda_ciudad_owner',
            'value' => $the_member_city,
            'compare' => '=',
        )
    )
);
$comisiones_miembro = new WP_Query($args);
if ( $comisiones_miembro->have_posts() ){
    $comisiones = array();
    while ( $comisiones_miembro->have_posts() ){
        $comisiones_miembro->the_post();
        $presidente = get_post_meta(get_the_ID(), 'oda_comision_composicion_presidente', true);
        $videpresidente = get_post_meta(get_the_ID(), 'oda_comision_composicion_vicepresidente', true);
        $demas = get_post_meta(get_the_ID(), 'oda_comision_composicion_miembros', true);
        //echo $member_id . ' - ' . $presidente . ' - ' .get_the_ID() .'<br />';
        if ($presidente == $the_member->ID){
            $comisiones[] = array(
                'nombre' => get_the_title(),
                'cargo' => 'Presidente'
            );
            continue;
        }
        if ($videpresidente == $the_member->ID){
            $comisiones[] = array(
                'nombre' => get_the_title(),
                'cargo' => 'Vicepresidente'
            );
            continue;
        }
        if( $demas ){
            foreach($demas as $otro){
                if($otro == $the_member->ID){
                    $comisiones[] = array(
                        'nombre' => get_the_title(),
                        'cargo' => 'Miembro'
                    );
                }
            }
        }
        
    }
}
wp_reset_postdata();

$comisiones_ordenadas = array_reverse($comisiones);


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
        height: 36px;
        position: absolute;
        bottom: 0;
        background: linear-gradient(0deg, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 100%);
        width: 100%;
    }
</style>
<div class="container pt-3 pb-3">
    <!--
    <div class="row pt-4 pb-4">
        <div class="col-sm-12 text-center">
            <ul class="list-unstyled">
                <li><a href="#">Perfil</a></li>
                <li><a href="#">Estadísticas</a></li>
            </ul>
        </div>
    </div>
    -->
    <div class="row pt-3 pb-3">
        <div class="col-md-3 bg-ececec pt-3">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="text-center fs-18 bold">Organización Política</h3>
                    <div class="row">
                        <div class="col-sm-4">
                            <?php
                            if (!empty($partido_politico_logo)) {
                                echo '<img class="img-fluid" src="' . $partido_politico_logo . '" alt="">';
                            }
                            ?>
                        </div>
                        <div class="col-sm-8">
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
                        <div class="col-sm-3 offset-sm-3 text-center pt-1">
                            <?php
                            if (!empty($plan)) {
                                echo '<a class="ta-c mt-2 text-black-light" href="' . $plan . '" target="_blank"><i class="far fa-file-alt fs-36"></i></a>';
                            }else{
                            ?>
                            <a class="ta-c mt-2 text-black-light" href="#" data-toggle="modal" data-target="#reques_program"><i class="far fa-file-alt fs-36"></i></a>
                            <div class="modal fade" id="reques_program">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="d-flex h-100 flex-column justify-content-center align-items-center">
                                            <a href="https://twitter.com/intent/tweet?text=<?php echo get_post_meta(get_post_meta($the_member->ID,'oda_ciudad_owner', true),'oda_ciudad_tweet_folders', true) . ' - ' . get_post_meta($the_member->ID,'oda_miembro_twitter', true); ?>" style="text-decoration: none;">
                                                    <span class="twitter-circle-icon"><i class="fab fa-twitter text-white fs-20"></i></span>
                                                </a>
                                                <br />
                                                <p>¡Envía un Tweet a tu Concejo!</p>
                                            </div>
                                            <p>Exige al Concejal que transparente esta información</p>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            <?php 
                            }
                            ?>
                        </div>
                        <div class="col-sm-3 text-center">
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
                    <hr />
                </div>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <h1 class="fs-36"><?php echo get_post_meta($the_member->ID, 'oda_miembro_nombres', true);//$the_member->post_title; ?><br /><strong><?php echo get_post_meta($the_member->ID, 'oda_miembro_apellidos', true); ?></strong></h1>
            <p class="mb-1">Concejal <span class="bold"><?php echo $circunscripcion->post_title; ?></span></p>
            <?php
            if (!empty($thumbnail_url)) {
                echo '<div class="single_miembro_thumbnail">';
                echo '<img class="img-fluid" src="' . $thumbnail_url . '">';
                echo '<div class="white-gradient"></div>';
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
                <a href="https://twitter.com/intent/tweet?text=<?php echo get_post_meta(get_post_meta($the_member->ID,'oda_ciudad_owner', true),'oda_ciudad_tweet_folders', true) . ' - ' . get_post_meta($the_member->ID,'oda_miembro_twitter', true); ?>" style="text-decoration: none;">
                        <span class="twitter-circle-icon"><i class="fab fa-twitter text-white fs-20"></i></span>
                    </a>
                    <p class="mt-1"><strong>Mándale un tweet</strong><br />y pídele que sea parte de Concejo Transparente</p>
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
                    left: 30px;
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
<?php get_footer(); ?>