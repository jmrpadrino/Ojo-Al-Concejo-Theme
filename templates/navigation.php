<?php
global $wp_query;
$exclude = $ciudad = '';
$background_default_color = '#EDEDED';
$display = 'none';
$nav_shown = false;
$link_class = '';
$show_socials = true;
$current_city = '';
$current_view = '';
$oculta_transparente = $oculta_ordenanza = $oculta_resolucion = $oculta_observacion = $oculta_solinfo = $oculta_soltransp = '';

if (
    !is_front_page() ||
    is_page() ||
    isset($wp_query->query_vars['oda_template'])
) {
    if (is_singular()) {
        global $post;
        $ciudad = $wp_query->query['name'];
        $current_city = $post->ID;
    }
    if (isset($wp_query->query_vars['city_slug'])) {
        $ciudad = $wp_query->query_vars['city_slug'];
        $item = get_page_by_path($ciudad, OBJECT, 'ciudad');
        $current_city = $item->ID;
    }
    $facebook = get_post_meta($current_city, 'oda_ciudad_facebook', true);
    $twitter = get_post_meta($current_city, 'oda_ciudad_twitter', true);
    $instagram = get_post_meta($current_city, 'oda_ciudad_instagram', true);
    $args = array(
        'post_type' => 'ciudad',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'post__not_in' => array($current_city)
    );
    $otras_ciudades = new WP_Query($args);
    $primary_color = get_post_meta($current_city, 'oda_ciudad_color', true);
    $city_logo = get_the_post_thumbnail_url($current_city, 'post-thumbnail');
    $primary_color = get_post_meta($current_city, 'oda_ciudad_color', true);
    if (!empty($primary_color)) {
        $background_default_color = $primary_color;
    }
    $display = 'block';
    $nav_shown = true;
    if (is_page()) {
        $show_socials = false;
        $current_city = '';
        $link_class = 'class="uppercase"';
        $current_view = 'single-page';
    }
} else {
    $link_class = 'class="uppercase"';
    $show_socials = false;
    $current_view = 'front-page';
}

$oculta_transparente    = get_post_meta($current_city, 'oda_ciudad__concejo_transver', true);
$oculta_ordenanza       = get_post_meta($current_city, 'oda_ciudad_ocula_ordenanza', true);
$oculta_resolucion      = get_post_meta($current_city, 'oda_ciudad_ocula_resoluciones', true);
$oculta_observacion     = get_post_meta($current_city, 'oda_ciudad_ocula_observaciones', true);
$oculta_solinfo         = get_post_meta($current_city, 'oda_ciudad_ocula_solicitud_info', true);
$oculta_soltransp       = get_post_meta($current_city, 'oda_ciudad_ocula_solicitud_comp', true);
?>
<style scoped>
    .main-nav {
        display: <?php echo $display; ?>;
    }

    .pre-nav {
        background: rgb(255, 255, 255);
        background: linear-gradient(90deg, #fff 0%, #fff 25%, <?php echo $background_default_color; ?> 50%, <?php echo $background_default_color; ?> 100%);
    }

    .pre-nav.front-page ul li a,
    .pre-nav.single-page ul li a {
        color: #212529;
    }

    .custom-nav>li {
        background-color: <?php echo $background_default_color; ?>;
        transition: background-color .2s ease;
    }

    .custom-nav>li:hover {
        background-color: <?php echo darken_color($background_default_color, 2); ?>;
    }

    .folder-list .folder-popup-image,
    .folder-list a.folder-icon:hover,
    .folder-list a.folder-icon:hover:before {
        background-color: <?php echo $background_default_color; ?>;
    }

    .sub-menu {
        top: calc(100% + 1px);
        left: 0;
        z-index: 9999;
        width: 100%;
        background: #F6F6F6;
        visibility: hidden;
        opacity: 0;
        position: absolute;
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: left;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    }

    .sub-menu * {
        color: #5A5C5B;
    }

    .sub-menu li {
        position: relative;
        display: block;
    }

    .item-has-children:hover {
        visibility: inherit;
    }

    .item-has-children:hover .sub-menu {
        visibility: visible;
        opacity: 1;
    }

    .is-dropdown {
        position: relative;
        display: inline-block;
    }

    .sidemenu-indicator {
        position: absolute;
        right: 10px;
        top: 10px
    }

    .sub-menu-side {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        width: 100%;
        top: 0;
        left: 100%;
        padding: 0;
    }

    .is-dropdown:hover .sub-menu-side {
        display: block;
    }
</style>
<div id="fixed-nav">
    <div class="pre-nav <?php echo $current_view; ?>">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 d-flex justify-content-end">
                    <ul class="d-flex list-no-style fs-14">
                        <?php if (is_page()) { ?>
                            <li><a <?php echo $link_class; ?> href="<?php echo home_url('/') ?>">Inicio</a></li>
                        <?php } ?>
                        <li><a <?php echo $link_class; ?> href="<?php echo (!empty($current_city)) ? home_url('/ciudad/' . $ciudad . '/contactanos/') : home_url('contactanos'); ?>">Contáctanos</a></li>
                        <li><a <?php echo $link_class; ?> href="<?php echo (!empty($current_city)) ? home_url('/ciudad/' . $ciudad . '/sobre-nosotros/') : home_url('sobre-nosotros'); ?> ">Sobre nosotros</a></li>
                        <?php if ($show_socials) { ?>
                            <?php if (!empty($facebook)) { ?>
                                <li><a href="<?php echo $facebook; ?>"><i class="fab fa-facebook-f"></i></a></li>
                            <?php } ?>
                            <?php if (!empty($twitter)) { ?>
                                <li><a href="<?php echo $twitter; ?>"><i class="fab fa-twitter"></i></a></li>
                            <?php } ?>
                            <?php if (!empty($instagram)) { ?>
                                <li><a href="<?php echo $instagram; ?>"><i class="fab fa-instagram"></i></a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="main-nav">
        <div class="container-fluid top-container">
            <div class="row pt-1 pb-1 d-none d-sm-flex">
                <div class="col-6 col-md-6">
                    <?php if (!empty($city_logo)) { ?>
                        <img class="img-fluid city-logo" width="200" src="<?php echo $city_logo; ?>">
                    <?php } else { ?>
                        <a href="<?php echo home_url(); ?>">
                            <img class="img-fluid nocity-logo" width="200" src="<?php echo THEME_URL . '/img/Ojo-al-Concejo-nocity.png'; ?>">
                        </a>
                    <?php } ?>
                </div>
                <div class="col-6 col-md-6 hidden-sm text-right">
                    <span class="logo-owner-nav">
                        Una iniciativa de: <img class="img-fluid company-logo mt-1" width="250" src="<?php echo THEME_URL . '/img/FCD-top.png'; ?>" alt="Observatorio Logo">
                    </span>
                </div>
            </div>
            <div class="row d-flex d-sm-none movile-nav-elements">
                <div class="col-3 text-center pt-2">
                    <span id="expand-menu" class="d-block mt-3"><i class="fas fa-bars fs-28 text-black-lighter"></i></span>
                </div>
                <div class="col-6 text-center">
                    <?php if (!empty($city_logo)) { ?>
                        <img class="img-fluid" width="100" src="<?php echo $city_logo; ?>">
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php if (!is_page()) { ?>
            <div class="floating-nav">
                <span class="close-mobile-nav d-block d-sm-none ta-r fs-32"><i class="far fa-times-circle"></i></span>
                <nav>
                    <ul class="w-100 d-block d-sm-flex justify-content-start list-no-style mb-0 mt-3 custom-nav">
                        <li class="menu-item"><a class="text-white" href="<?php echo home_url('/ciudad/' . $ciudad . '/'); ?>">Inicio</a></li>
                        <li class="menu-item item-has-children"><a class="text-white" href="#">Concejo Municipal</a>
                            <ul class="sub-menu">
                                <li class="ta-l b-gray">
                                    <a href="<?php echo home_url('/ciudad/' . $ciudad . '/consejo-municipal/'); ?>">¿Quiénes lo integran?</a>
                                </li>
                                <li class="ta-l b-gray is-dropdown">
                                    <a href="#">¿Qué hace?</a>
                                    <span class="sidemenu-indicator"><strong><i class="fas fa-chevron-right" style="color: '. $next_city_primary_color .';"></i></strong></span>
                                    <ul class="sub-menu-side">
                                        <?php if (empty($oculta_ordenanza)) { ?>
                                            <li class="ta-l b-gray">
                                            <?php if (get_ordenanzas_ciudad($current_city)->post_count > 0) { ?>
                                                <a href="<?php echo home_url('/ciudad/' . $ciudad . '/proyectos-de-ordenanza/'); ?>">Proyectos de ordenanza</a>
                                            <?php }else{ ?>
                                                <a data-toggle="modal" data-target="#modal_menu_ordenanzas">Proyectos de ordenanza</a>
                                            <?php } ?>
                                            </li>
                                        <?php } ?>
                                        <?php if (empty($oculta_resolucion)) { ?>
                                            <li class="ta-l b-gray">
                                            <?php if (get_resoluciones_ciudad($current_city)->post_count > 0) { ?>
                                                <a href="<?php echo home_url('/ciudad/' . $ciudad . '/proyectos-de-resolucion/'); ?>">Proyectos de resolución</a>
                                            <?php }else{ ?>
                                                <a data-toggle="modal" data-target="#modal_menu_resoluciones">Proyectos de resolución</a>
                                            <?php } ?>
                                            </li>
                                        <?php } ?>
                                        <?php if (empty($oculta_observacion)) { ?>
                                            <li class="ta-l b-gray">
                                            <?php if (get_observaciones_ciudad($current_city)->post_count > 0) { ?>
                                                <a href="<?php echo home_url('/ciudad/' . $ciudad . '/observaciones-a-proyectos-de-ordenanza/'); ?>">Observaciones a proyectos de ordenanza</a>
                                            <?php }else{ ?>
                                                <a data-toggle="modal" data-target="#modal_menu_observaciones">Observaciones a proyectos de ordenanza</a>
                                            <?php } ?>
                                            </li>
                                        <?php } ?>
                                        <?php if (empty($oculta_solinfo)) { ?>
                                            <li class="ta-l b-gray">
                                            <?php if (get_solicitudes_informacion($current_city)->post_count > 0) { ?>
                                                <a href="<?php echo home_url('/ciudad/' . $ciudad . '/solicitudes-de-informacion/'); ?>">Solicitudes de información</a>
                                            <?php }else{ ?>
                                                <a data-toggle="modal" data-target="#modal_menu_solicitudes">Solicitudes de información</a>
                                            <?php } ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-item item-has-children">
                            <a class="text-white" href="#">Evaluación de gestión</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="<?php echo home_url('/ciudad/' . $ciudad . '/tu-concejo-en-cifras/'); ?>">Tu Concejo en cifras</a>
                                </li>
                            </ul>
                        </li>
                        <?php
                        if (isset($otras_ciudades)) {
                            if ($otras_ciudades->have_posts()) {
                                while ($otras_ciudades->have_posts()) {
                                    $otras_ciudades->the_post();
                                    $next_city_primary_color = get_post_meta(get_the_ID(), 'oda_ciudad_color', true);
                                    echo '<li class="no-bkg"><a href="' . get_the_permalink() . '">' . get_the_title() . '&nbsp;&nbsp;&nbsp;&nbsp;<strong><i class="fas fa-chevron-right fs-18 bold" style="color: ' . $next_city_primary_color . ';"></i></strong></a></li>';
                                }
                            }
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        <?php } ?>
    </div>
</div>
<div class="modal fade" id="modal_menu_ordenanzas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
            <div class="modal-body">
                <div class="row">
                    <?php
                        $image = 'http://placehold.it/800x600?text=Concejo%20Transparente';
                        $tuit = '';
                        $imagen_meta = get_post_meta($current_city, 'oda_ciudad_image_menu_ordenanzas', true);
                        $tuit_meta = get_post_meta($current_city, 'oda_ciudad_tweet_menu_ordenanzas', true);
                        $image = ($imagen_meta) ? $imagen_meta : $image;
                        $tuit = ($tuit_meta) ? $tuit_meta : '';
                    ?>
                    <div class="col-sm-12 mb-3 text-center">
                        <img src="<?php echo $image; ?>" class="img-fluid">
                    </div>
                    <div class="col-sm-12 text-center mt-3">
                        <?php if($tuit){ ?>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $tuit; ?>" style="text-decoration: none;">
                            <span class="twitter-circle-icon"><i class="fab fa-twitter text-white fs-20"></i></span>
                        </a>
                        <br />
                        <p>¡Envía un Tweet a tu Concejo!</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_menu_resoluciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
            <div class="modal-body">
                <div class="row">
                    <?php
                        $image = 'http://placehold.it/800x600?text=Concejo%20Transparente';
                        $tuit = '';
                        $imagen_meta = get_post_meta($current_city, 'oda_ciudad_image_menu_resoluciones', true);
                        $tuit_meta = get_post_meta($current_city, 'oda_ciudad_tweet_menu_resoluciones', true);
                        $image = ($imagen_meta) ? $imagen_meta : $image;
                        $tuit = ($tuit_meta) ? $tuit_meta : '';
                    ?>
                    <div class="col-sm-12 mb-3 text-center">
                        <img src="<?php echo $image; ?>" class="img-fluid">
                    </div>
                    <div class="col-sm-12 text-center mt-3">
                        <?php if($tuit){ ?>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $tuit; ?>" style="text-decoration: none;">
                            <span class="twitter-circle-icon"><i class="fab fa-twitter text-white fs-20"></i></span>
                        </a>
                        <br />
                        <p>¡Envía un Tweet a tu Concejo!</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_menu_observaciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
            <div class="modal-body">
                <div class="row">
                    <?php
                        $image = 'http://placehold.it/800x600?text=Concejo%20Transparente';
                        $tuit = '';
                        $imagen_meta = get_post_meta($current_city, 'oda_ciudad_image_menu_observaciones', true);
                        $tuit_meta = get_post_meta($current_city, 'oda_ciudad_tweet_menu_observaciones', true);
                        $image = ($imagen_meta) ? $imagen_meta : $image;
                        $tuit = ($tuit_meta) ? $tuit_meta : '';
                    ?>
                    <div class="col-sm-12 mb-3 text-center">
                        <img src="<?php echo $image; ?>" class="img-fluid">
                    </div>
                    <div class="col-sm-12 text-center mt-3">
                        <?php if($tuit){ ?>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $tuit; ?>" style="text-decoration: none;">
                            <span class="twitter-circle-icon"><i class="fab fa-twitter text-white fs-20"></i></span>
                        </a>
                        <br />
                        <p>¡Envía un Tweet a tu Concejo!</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_menu_solicitudes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
            <div class="modal-body">
                <div class="row">
                    <?php
                        $image = 'http://placehold.it/800x600?text=Concejo%20Transparente';
                        $tuit = '';
                        $imagen_meta = get_post_meta($current_city, 'oda_ciudad_image_menu_solicitudes', true);
                        $tuit_meta = get_post_meta($current_city, 'oda_ciudad_tweet_menu_solicitudes', true);
                        $image = ($imagen_meta) ? $imagen_meta : $image;
                        $tuit = ($tuit_meta) ? $tuit_meta : '';
                    ?>
                    <div class="col-sm-12 mb-3 text-center">
                        <img src="<?php echo $image; ?>" class="img-fluid">
                    </div>
                    <div class="col-sm-12 text-center mt-3">
                        <?php if($tuit){ ?>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $tuit; ?>" style="text-decoration: none;">
                            <span class="twitter-circle-icon"><i class="fab fa-twitter text-white fs-20"></i></span>
                        </a>
                        <br />
                        <p>¡Envía un Tweet a tu Concejo!</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>