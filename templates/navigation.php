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
    if (
        !is_front_page() ||
        is_page() ||
        isset( $wp_query->query_vars['oda_template'] )
    ){ 
        if ( is_singular() ){
            global $post;
            $ciudad = $wp_query->query['name'];
            $current_city = $post->ID;
        }
        if ( isset( $wp_query->query_vars['city_slug'] ) ){
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
        $city_logo = get_the_post_thumbnail_url($current_city, 'post-thumbnail' );
        $primary_color = get_post_meta($current_city, 'oda_ciudad_color', true);
        if(!empty( $primary_color )){
            $background_default_color = $primary_color;
        }
        $display = 'block';
        $nav_shown = true;
        if (is_page()){
            $show_socials = false;
            $current_city = '';
            $link_class = 'class="uppercase"';
            $current_view = 'single-page';

        }
    }else{
        $link_class = 'class="uppercase"';
        $show_socials = false;
        $current_view = 'front-page';
    }    
?>
<style scoped>
    .main-nav {
        display: <?php echo $display; ?>;
    }
    .pre-nav {
        background: rgb(255,255,255);
        background: linear-gradient(90deg, #fff 0%, #fff 25%, <?php echo $background_default_color; ?> 50%, <?php echo $background_default_color; ?> 100%);
    }
    .pre-nav.front-page ul li a ,
    .pre-nav.single-page ul li a {
        color: #212529;
    }
    .custom-nav li{
        background-color: <?php echo $background_default_color; ?>;
    }
    .custom-nav li:hover{
        filter: brightness(75%);
    }
    .folder-list .folder-popup-image,
    .folder-list a.folder-icon:hover,
    .folder-list a.folder-icon:hover:before{ 
        background-color: <?php echo $background_default_color; ?>;
    }
</style>
<div id="fixed-nav">
    <div class="pre-nav <?php echo $current_view;?>">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 d-flex justify-content-end">
                    <ul class="d-flex list-no-style fs-14">
                        <?php if (is_page()){ ?>
                        <li><a <?php echo $link_class; ?> href="<?php echo home_url('/')?>">Inicio</a></li>
                        <?php } ?>
                        <li><a <?php echo $link_class; ?> href="<?php echo (!empty($current_city)) ? home_url('/ciudad/'.$ciudad.'/contactanos/') : home_url('contactanos'); ?>">Contáctanos</a></li>
                        <li><a <?php echo $link_class; ?> href="<?php echo (!empty($current_city)) ? home_url('/ciudad/'.$ciudad.'/sobre-nosotros/') : home_url('sobre-nosotros'); ?> ">Sobre Nosotros</a></li>
                        <?php if($show_socials){ ?>
                        <?php if (!empty($facebook)){ ?>
                        <li><a href="<?php echo $facebook; ?>"><i class="fab fa-facebook-f"></i></a></li>
                        <?php } ?>
                        <?php if (!empty($twitter)){ ?>
                        <li><a href="<?php echo $twitter; ?>"><i class="fab fa-twitter"></i></a></li>
                        <?php } ?>
                        <?php if (!empty($instagram)){ ?>
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
            <div class="row pt-1 pb-1">
                <div class="col-6">
                    <?php if(!empty($city_logo)){ ?>
                    <img class="img-fluid city-logo" width="200" src="<?php echo $city_logo; ?>">
                    <?php } ?>
                </div>
                <div class="col-6 text-right">
                    <span class="logo-owner-nav">
                        Una iniciativa de: <img class="img-fluid company-logo mt-1" width="250" src="<?php echo THEME_URL . '/img/FCD-top.png'; ?>" alt="Observatorio Logo">
                    </span>
                </div>
            </div>
        </div>
        <?php if (!is_page()){ ?>
        <div>
            <nav>
                <ul class="w-100 d-flex justify-content-start list-no-style mb-0 mt-3 custom-nav">
                    <li><a class="text-white" href="<?php echo home_url('/ciudad/'.$ciudad.'/'); ?>">Inicio</a></li>
                    <li><a class="text-white" href="<?php echo home_url('/ciudad/'.$ciudad.'/consejo-municipal/'); ?>">Concejo Municipal</a></li>
                    <li><a class="text-white" href="<?php echo home_url('/ciudad/'.$ciudad.'/evaluacion-de-gestion/'); ?>">Evaluación de gestión</a></li>
                <?php
                    if (isset($otras_ciudades)){
                        if ($otras_ciudades->have_posts()){
                            while ($otras_ciudades->have_posts()){
                                $otras_ciudades->the_post();
                                $next_city_primary_color = get_post_meta(get_the_ID(), 'oda_ciudad_color', true);
                                echo '<li class="no-bkg"><a href="' . get_the_permalink() . '">' . get_the_title() . ' <strong><i class="fas fa-chevron-right" style="color: '. $next_city_primary_color .';"></i></strong></a></li>';
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

