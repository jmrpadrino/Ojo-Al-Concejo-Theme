<?php
global $post;
get_header();
the_post();
$city_color = get_post_meta( get_the_ID(), 'oda_ciudad_color', true );
$miembros_concejo_transparente = 0;
$miembros_varones = 0;
$miembros_rurales = 0;

$datetime1 = date_create( PERIOD_BEGINS );
$datetime2 = date_create( date( 'Y-m-d' ) );

$interval = date_diff( $datetime1, $datetime2 );

// presentar los miembros del concejo
$args = array(
    'post_type' => 'miembro',
    'posts_per_page' => -1,
    'meta_key' => 'oda_miembro_curul',
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'meta_query' => array(
        array(
            'key' => 'oda_ciudad_owner',
            'value' => get_the_ID(),
            'compare' => '=',
        )
    )
);
$args = apply_filters( 'listado_miembros', $args );
$miembros = new WP_Query( $args );
while ( $miembros->have_posts() ) {
    $miembros->the_post();
    if ( get_post_meta( get_the_ID(), 'oda_miembro_parte_concejo_transparente', true ) == 'on' ) {
        $miembros_concejo_transparente++;
    }
    ;
    if ( get_post_meta( get_the_ID(), 'oda_miembro_gerero', true ) == 1 ) {
        $miembros_varones++;
    }
    ;
    if ( get_post_meta( get_the_ID(), 'oda_miembro_rural', true ) == 'on' ) {
        $miembros_rurales++;
        //miembro_cargo
    }
    ;
}
wp_reset_postdata();
?>

<style>
.section-concejotransparente {
    background-image: linear-gradient(90deg, <?php echo $city_color;
            ?> 0%, <?php echo $city_color;
            ?> 50%, rgba(255, 255, 255, 0) 100%), url(<?php echo THEME_URL;
 ?>/img/apuntes.jpg);
    color: white;
    padding-top: 10px;
    padding-bottom: 10px;
}

.slide_name {
    background-color: <?php echo $city_color;
    ?>
}

.slide-counter-placeholder {
    min-height: 225px;
    display: flex;
    width: 100%;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.slide-counter-item {
    width: 80px;
    height: 80px;
    text-align: center;
    line-height: 1;
    background-color: <?php echo $city_color;
    ?>;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: bold;
    color: white;
}

#modalinfo .modal-body {
    padding: 0;
}

.btn-concejo-transparente,
.data-box {
    color: <?php echo $city_color;
    ?>;
}

.section-folders {
    background-image: url(<?php echo THEME_URL;
 ?>/img/textura-carpeta.png);
    background-repeat: no-repeat;
    background-position: center top;
    background-size: contain;
}

.slider-container {
    background-image: url(<?php echo THEME_URL;
 ?>/img/banner-1.jpg);
    background-size: cover;
    background-position: center center;
}

.slider-container .carousel-control-next,
.slider-container .carousel-control-prev {
    width: 5%;
}

.slider-container .carousel-item {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    padding-top: 100px;
    padding-bottom: 100px;
    height: 350px;

}

.slider-container .carousel-indicators li {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin-right: 10px;
    margin-left: 10px;
    background-color: #545b62;
}

.slide-btn {
    color: gray;
    text-align: center;
    font-weight: bold;
    border: 2px solid gray;
    border-radius: 7px;
    padding: 10px 30px;
    margin-top: 18px;
    display: table;
    box-shadow: 1px 2px 5px #c7c7c738;
}

.slide-btn:hover {
    text-decoration: none;
    color: black;
    background: white;
    border-color: black;
}
</style>
<?php
$args = array(
    'post_type' => 'oda_slider',
    'post__in' => array( get_post_meta( get_the_ID(), 'oda_ciudad_slider', true ) )
);
$slider = new WP_Query( $args );
if ( $slider->have_posts() ) {
    $i = 0;
    ?>
<section class='main-container'>
    <div class='slider-container'>
        <div id='carruselciudad' class='carousel slide carousel-fade' data-ride='carousel'>
            <div class='carousel-inner'>
                <?php
    while( $slider->have_posts() ) {

        $slider->the_post();
        $slides = get_post_meta( get_the_ID(), 'oda_slides_metas', false );
        foreach ( $slides[0] as $index => $slide ) {
            $text_color = '';
            if ( !empty( $slide['oda_slide_bkg_img'] ) ) {
                $text_color = 'color: white!important; ';
            }
            ?>
                <div class="carousel-item <?php echo ($i == 0) ? 'active' : 'fade'; ?>"
                    style="<?php echo $text_color; ?>background-image: url(<?php echo $slide['oda_slide_bkg_img']; ?>);">
                    <div class='container'>

                        <div class='row'>
                            <div class='col-md-12'>
                                <?php //ecopre( $slide );
            ?>
                                <div class='slide-content'>
                                    <?php echo $slide['oda_slide_content'];
            ?>
                                </div>
                                <?php
            if (
                !empty( $slide['oda_slide_button_link'] ) &&
                !empty( $slide['oda_slide_button_text'] )
            ) {
                ?>
                                <a class='slide-btn' href="<?php echo $slide['oda_slide_button_link']; ?>"
                                    target='_blank'><?php echo $slide['oda_slide_button_text'];
                ?></a>
                                <?php }
                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $i++;
            }
            // END Foreach ?>
                <?php }
            // END While ?>
            </div>
            <!--
            <a class = 'carousel-control-prev' href = '#carruselciudad' role = 'button' data-slide = 'prev'>
            <span class = 'carousel-control-prev-icon' aria-hidden = 'true'></span>
            <span class = 'sr-only'>Previous</span>
            </a>
            <a class = 'carousel-control-next' href = '#carruselciudad' role = 'button' data-slide = 'next'>
            <span class = 'carousel-control-next-icon' aria-hidden = 'true'></span>
            <span class = 'sr-only'>Next</span>
            </a>
            -->
            <ol class='carousel-indicators'>
                <?php for ( $o = 0; $o < $i; $o++ ) {
                ?>
                <li data-target='#carruselciudad' data-slide-to="<?php echo $o; ?>" <?php echo ( $o == 0 ) ? 'class="active"' : '';
                ?>></li>
                <?php }
                //END For ?>
            </ol>
        </div>
    </div>
    <?php }
                wp_reset_postdata();
                // END if ?>
    <div class='section-statistics'>
        <div class='container'>
            <div class='row mt-5 mb-3'>
                <div class='col-sm-12'>
                    <?php echo get_post_meta( get_the_ID(), 'oda_ciudad_texto_top', true );
                ?>
                </div>
            </div>
            <div class='row mb-5 mt-5'>
                <div class='col-sm-12 col-md-6 mt-sm-3'>
                    <div class='row  bg-ececec mb-2'>
                        <div class='col-sm-9 p-1'>
                            <span class='fs-26 data-box bold'><i class='fas fa-arrow-right'></i> <?php echo $miembros_varones;
                ?> de <?php echo count( $miembros->posts );
                ?></span>
                            <hr />
                            <span class='fs-18'>miembros del Concejo son hombres</span>
                        </div>
                        <div class='col-sm-3 d-flex justify-content-center align-items-center pt-3 pb-3'
                            style="background: <?php echo $city_color; ?>;">
                            <i class='fa fa-user fs-60 text-white'></i>
                        </div>
                    </div>
                    <div class='row  bg-ececec mt-3'>
                        <div class='col-sm-9 p-1'>
                            <span class='fs-26 data-box bold'><i class='fas fa-arrow-right'></i> <?php echo $miembros_rurales;
                ?> de <?php echo count( $miembros->posts );
                ?></span>
                            <hr />
                            <span class='fs-18'>concejales representan a la ruralidad</span>
                        </div>
                        <div class='col-sm-3 d-flex justify-content-center align-items-center pt-3 pb-3'
                            style="background: <?php echo $city_color; ?>;">
                            <i class='fas fa-tractor fs-60 text-white'></i>
                        </div>
                    </div>
                </div>
                <div class='col-sm-12 col-md-5 offset-md-1 mt-sm-3'>
                    <?php
                // Hacer indicadores INICIO                    
                    $ordenanzas_alcalde = get_ordenanzas_ciudad_tipomiembro(get_the_ID(), 'alcalde')->post_count;
                    $ordenanzas_concejales = get_ordenanzas_ciudad_tipomiembro(get_the_ID(), 'concejal')->post_count;
                    $ordenanzas_comisiones = get_ordenanzas_ciudad_tipomiembro(get_the_ID(), 'comisiones')->post_count;
                    $ordenanzas_aprobadas = get_ordenanzas_ciudad_aprobadas(get_the_ID())->post_count;
                    $resoluciones_aprobadas = get_resoluciones_ciudad_aprobadas(get_the_ID())->post_count;
                    if (
                        $ordenanzas_alcalde != 0 ||
                        $ordenanzas_concejales != 0 ||
                        $ordenanzas_comisiones != 0 ||
                        $ordenanzas_aprobadas != 0 ||
                        $resoluciones_aprobadas != 0 
                    ){
                ?>
                    <div id='carouselHomeCounter' class='carousel slide carousel-fade' data-ride='carousel'>
                        <div class='carousel-inner bg-ececec'>
                            <?php if($ordenanzas_alcalde): ?>
                            <div class='carousel-item active'>
                                <div class="slide-counter-placeholder">
                                    <span class="slide-counter-item"><?php echo $ordenanzas_alcalde; ?></span>
                                    <span class="fs-24 mt-3">Alcalde</span>
                                </div>
                                <div class="slide_name w-100 py-3 text-center">
                                    <span class="text-white bold">Proyectos de ordenanzas presentados</span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if($ordenanzas_concejales): ?>
                            <div class='carousel-item'>
                                <div class="slide-counter-placeholder">
                                    <span class="slide-counter-item"><?php echo $ordenanzas_concejales; ?></span>
                                    <span class="fs-24 mt-3">Concejales</span>
                                </div>
                                <div class="slide_name w-100 py-3 text-center">
                                    <span class="text-white bold">Proyectos de ordenanzas presentados</span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if($ordenanzas_comisiones): ?>
                            <div class='carousel-item'>
                                <div class="slide-counter-placeholder">
                                    <span class="slide-counter-item"><?php echo $ordenanzas_comisiones; ?></span>
                                    <span class="fs-24 mt-3">Comisiones</span>
                                </div>
                                <div class="slide_name w-100 py-3 text-center">
                                    <span class="text-white bold">Proyectos de ordenanzas presentados</span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if($ordenanzas_aprobadas): ?>
                            <div class='carousel-item'>
                                <div class="slide-counter-placeholder">
                                    <span class="slide-counter-item"><?php echo $ordenanzas_aprobadas; ?></span>
                                </div>
                                <div class="slide_name w-100 py-3 text-center">
                                    <span class="text-white bold">Proyectos de ordenanzas aprobados</span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if($resoluciones_aprobadas): ?>
                            <div class='carousel-item'>
                                <div class="slide-counter-placeholder">
                                    <span class="slide-counter-item"><?php echo $resoluciones_aprobadas; ?></span>
                                </div>
                                <div class="slide_name w-100 py-3 text-center">
                                    <span class="text-white bold">Proyectos de resoluciones aprobados</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <a class='carousel-control-prev' href='#carouselHomeCounter' role='button' data-slide='prev'>
                            <i class="fas fa-chevron-left fs-36 text-black-lighter"></i>
                            <span class='sr-only'>Anterior</span>
                        </a>
                        <a class='carousel-control-next' href='#carouselHomeCounter' role='button' data-slide='next'>
                            <i class="fas fa-chevron-right fs-36 text-black-lighter"></i>
                            <span class='sr-only'>Siguiente</span>
                        </a>
                    </div>
                    <?php }else{ // End if contadores ?>
                    <?php
                    $imagen_indicadores = get_post_meta( get_the_ID(), 'oda_ciudad_info_image_indicadores', true );

                    if ( $imagen_indicadores ) {
                        echo '<img class="img-fluid" src="'. $imagen_indicadores .'">';
                    } else {
                        ?>
                    <img class='img-fluid' src="<?php echo THEME_URL . '/img/Proximamente-Home.png'; ?>">
                    <?php
                    }
                }
                ?>

                </div>
            </div>
        </div>
    </div>
    <?php if ( 'on' != get_post_meta( get_the_ID(), 'oda_ciudad__concejo_transver', true ) ) {
                    ?>
    <div class='section-concejotransparente'>
        <div class='container'>
            <div class='row mb-5 mt-5'>
                <div class='col-sm-12 col-md-6 col-lg-4'>
                    <?php if ( !empty( get_post_meta( get_the_ID(), 'oda_ciudad__concejo_transizq', true ) ) ) {
                        echo get_post_meta( get_the_ID(), 'oda_ciudad__concejo_transizq', true );
                    }
                    ?>
                    <br />
                    <?php
                    $transparente_botton_texto = get_post_meta( get_the_ID(), 'oda_ciudad__concejo_trans_btntext', true );
                    $transparente_botton_URL = get_post_meta( get_the_ID(), 'oda_ciudad__concejo_trans_btnurl', true );
                    if ( $transparente_botton_texto ) {
                        ?>
                    <div class='mt-3'>
                        <a class='btn-concejo-transparente' href="<?php echo $transparente_botton_URL; ?>"><?php echo $transparente_botton_texto;
                        ?></a>
                    </div>
                    <?php }
                        ?>
                </div>
                <div class='col-sm-12 col-md-6 col-lg-4 offset-lg-3'>
                    <?php
                        $popup_image_url = get_post_meta( get_the_ID(), 'oda_ciudad_info_popup_image', true );
                        if ( !empty( $popup_image_url ) ) {

                            ?>
                    <a class='text-white' href='#' data-toggle='modal' data-target='#modalinfo'><i
                            class='fas fa-info-circle fs-26'></i></a>
                    <div class='modal fade' id='modalinfo'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content'>
                                <div class='dismis-modals' data-dismiss='modal'><i
                                        class='far fa-times-circle text-blue-main'></i></div>
                                <div class='modal-body'>
                                    <div class='col-sm-12 text-center'>
                                        <img class='img-fluid' src="<?php echo $popup_image_url; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }
                            // END if ?>
                    <p class='fs-28'><span class='fs-80 bold'><?php echo $miembros_concejo_transparente;
                            ?> de <?php echo count( $miembros->posts );
                            ?></span></p>
                    <?php if ( !empty( get_post_meta( get_the_ID(), 'oda_ciudad__concejo_transder', true ) ) ) {
                                echo get_post_meta( get_the_ID(), 'oda_ciudad__concejo_transder', true );
                            }
                            ?>
                    <!--
                            <p>Miembros del Concejo que forman parte<br />del Concejo Transparente</p>
                            -->
                </div>
            </div>
        </div>
    </div>
    <?php }
                            // END if shows ?>
    <?php
                            $carpetas = get_post_meta( get_the_ID(), 'oda_ciudad_carpeta', false );
                            ?>
    <div class='section-folders pt-4 pb-4'>
        <div class='container'>
            <div class='row pt-3'>
                <div class='col-sm-12 mt-4'>
                    <?php echo get_post_meta( get_the_ID(), 'oda_ciudad_texto_bottom', true );
                            ?>
                </div>
            </div>
            <?php if ( $carpetas ) {
                                ?>
            <div class='row mb-5 mt-5'>
                <div class="col-md-12 <?php echo (count($carpetas[0]) < 5)? 'col-lg-10 offset-lg-1': ''?>">
                    <div class='w-100 d-block d-sm-flex justify-content-around folder-list mt-5'>
                        <?php
                                foreach ( $carpetas[0] as $index => $carpeta ) {
                                    ?>
                        <a class='folder-icon' href='#' data-toggle='modal'
                            data-target="#modalcarpeta-<?php echo $index; ?>">
                            <i class="fs-36 <?php echo $carpeta['oda_carpeta_icon']; ?>"></i>
                            <span class='bold'><?php echo $carpeta['oda_carpeta_copy'];
                                    ?></span>
                        </a>
                        <?php
                                    if (
                                        !empty( $carpeta['description'] ) ||
                                        !empty( $carpeta['image'] )
                                    ) {
                                        ?>
                        <div class='modal fade' id="modalcarpeta-<?php echo $index; ?>">
                            <div class='modal-dialog modal-lg'>
                                <div class='modal-content'>
                                    <div class='dismis-modals' data-dismiss='modal'><i
                                            class='far fa-times-circle text-blue-main'></i></div>
                                    <div class='modal-body'>
                                        <div class='row'>
                                            <div class='col-sm-3'>
                                                <div
                                                    class='d-flex h-100 flex-column justify-content-center align-items-center'>
                                                    <div class='folder-popup-image'>
                                                        <i
                                                            class="fs-36 <?php echo $carpeta['oda_carpeta_icon']; ?>"></i>
                                                        <span><?php echo $carpeta['oda_carpeta_copy'];
                                        ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='col-sm-6'>
                                                <div class='row'>
                                                    <!--
                                        <div class = 'col-sm-1'><i class = 'fas fa-arrow-right text-blue-main'></i></div>
                                        -->
                                                    <div class='col-sm-12'><?php echo $carpeta['description'];
                                        ?></div>
                                                </div>
                                                <hr />
                                                <p class='fs-28 text-center bold'><span class='text-blue-main'><?php echo $interval->format( '%a' );
                                        ?></span> días <span class='text-blue-main'>sin</span> transparentar</p>
                                            </div>
                                            <div class='col-sm-3 text-center'>
                                                <div
                                                    class='d-flex h-100 flex-column justify-content-center align-items-center'>
                                                    <a target='_blank'
                                                        href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_post_meta(get_the_ID(),'oda_ciudad_tweet_folders', true) . ' - ' . get_post_meta(get_the_ID(),'oda_ciudad_twitter_user', true)); ?>"
                                                        style='text-decoration: none;'>
                                                        <span class='twitter-circle-icon'><i
                                                                class='fab fa-twitter text-white fs-20'></i></span>
                                                    </a>
                                                    <br />
                                                    <p>¡Envía un Tweet a tu Concejo!</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php /*
                                        <div class = 'modal fade' id = "modalcarpeta-<?php echo $index; ?>">
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content'>
                                <div class='modal-body'>
                                    <div class='row'>
                                        <?php if ( empty( $carpeta['description'] ) ) {
                                            ?>
                                        <div class='col-md-12 text-center'>
                                            <img class='img-fluid' src="<?php echo $carpeta['image']; ?>">
                                        </div>
                                        <?php } else if ( empty( $carpeta['image'] ) ) {
                                                ?>
                                        <div class='col-md-12'>
                                            <?php echo $carpeta['description'];
                                                ?>
                                        </div>
                                        <?php } else {
                                                    ?>
                                        <div class='col-md-4'>
                                            <img class='img-fluid' src="<?php echo $carpeta['image']; ?>">
                                        </div>
                                        <div class='col-md-8'>
                                            <?php echo $carpeta['description'];
                                                    ?>
                                        </div>
                                        <?php }
                                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    */ ?>
                    <?php }
                                                    // End if
                                                    ?>
                    <?php }
                                                    // End foreach
                                                    ?>
                </div>
            </div>
        </div>
        <?php 
            $documento_extra_carpetas = '';
            $documento_extra_carpetas = get_post_meta(get_the_ID(), 'oda_doc_post_carpetas', true);
            if ($documento_extra_carpetas){
        ?>
        <div class="row mt-3 mb-3">
            <div class="col-sm-12 d-flex justify-content-center">
                <a href="<?php echo $documento_extra_carpetas; ?>" target="_blank">
                <div class="btn-oda">
                    <span class="button-name">Ver más</span>
                    <span class="button-icon"><i class="fas fa-download"></i></span>
                </div>
                </a>
            </div>
        </div>            
        <?php } ?>
        <?php }
                                                    // End if ?>
    </div>
    </div>
</section>
<?php get_footer();
                                                    ?>