<?php
global $post;
get_header();
the_post();
$city_color = get_post_meta(get_the_ID(), 'oda_ciudad_color', true);
$miembros_concejo_transparente = 0;
$miembros_varones = 0;
$miembros_rurales = 0;

$datetime1 = date_create(PERIOD_BEGINS);
$datetime2 = date_create(date('Y-m-d'));

$interval = date_diff($datetime1, $datetime2);

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
$args = apply_filters('listado_miembros', $args);
$miembros = new WP_Query($args);
while ($miembros->have_posts()) {
    $miembros->the_post();
    if (get_post_meta(get_the_ID(), 'oda_miembro_parte_concejo_transparente', true) == 'on') {
        $miembros_concejo_transparente++;
    };
    if (get_post_meta(get_the_ID(), 'oda_miembro_gerero', true) == 1) {
        $miembros_varones++;
    };
    if (get_post_meta(get_the_ID(), 'oda_miembro_rural', true) == 'on') {
        $miembros_rurales++;
    };
}
wp_reset_postdata();
?>

<style>
    .section-concejotransparente {
        background-image: linear-gradient(90deg, <?php echo $city_color; ?> 0%, <?php echo $city_color; ?> 50%, rgba(255, 255, 255, 0) 100%), url(<?php echo THEME_URL; ?>/img/apuntes.jpg);
        color: white;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    #modalinfo .modal-body{ padding: 0; }
    .btn-concejo-transparente,
    .data-box {
        color: <?php echo $city_color; ?>;
    }
    .section-folders {
        background-image: url(<?php echo THEME_URL; ?>/img/textura-carpeta.png);
        background-repeat: no-repeat;
        background-position: center top;
        background-size: contain;
    }
    .slider-container {
        background-image: url(<?php echo THEME_URL; ?>/img/banner-1.jpg);
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
        'post__in' => array(get_post_meta(get_the_ID(), 'oda_ciudad_slider', true))
    );
    $slider = new WP_Query($args);
    if($slider->have_posts()){
    $i = 0;
?>
<section class="main-container">
    <div class="slider-container">
        <div id="carruselciudad" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner">
                <?php 
                    while($slider->have_posts()){ 
                        $slider->the_post();
                        $slides = get_post_meta(get_the_ID(), 'oda_slides_metas', false);
                        foreach ($slides[0] as $index => $slide){
                            $text_color = '';
                            if(!empty($slide['oda_slide_bkg_img'])) { $text_color = 'color: white!important; ';}
                ?>
                <div class="carousel-item <?php echo ($i == 0) ? 'active' : 'fade'; ?>" style="<?php echo $text_color; ?>background-image: url(<?php echo $slide['oda_slide_bkg_img']; ?>);">
                    <div class="container">

                        <div class="row">
                            <div class="col-md-12">
                                <?php //ecopre($slide); ?>
                                <div class="slide-content">
                                    <?php echo $slide['oda_slide_content']; ?>
                                </div>
                                <?php
                                    if (
                                        !empty($slide['oda_slide_button_link']) &&
                                        !empty($slide['oda_slide_button_text'])
                                    ){
                                ?>
                                <a class="slide-btn" href="<?php echo $slide['oda_slide_button_link']; ?>" target="_blank"><?php echo $slide['oda_slide_button_text']; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $i++; } // END Foreach ?>
                <?php } // END While ?>
            </div>
            <!--
            <a class="carousel-control-prev" href="#carruselciudad" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carruselciudad" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            -->
            <ol class="carousel-indicators">
                <?php for($o = 0; $o < $i; $o++){ ?>
                <li data-target="#carruselciudad" data-slide-to="<?php echo $o; ?>" <?php echo ($o == 0) ? 'class="active"' : ''; ?>></li>
                <?php } //END For ?>
            </ol>
        </div>
    </div>
    <?php } wp_reset_postdata(); // END if ?>
    <div class="section-statistics">
        <div class="container">
            <div class="row mt-5 mb-3">
                <div class="col-sm-12">
                    <?php echo get_post_meta(get_the_ID(), 'oda_ciudad_texto_top', true); ?>
                </div>
            </div>
            <div class="row mb-5 mt-5">
                <div class="col-sm-12 col-md-6 mt-sm-3">
                    <div class="row  bg-ececec mb-2">
                        <div class="col-sm-9 p-1">
                            <span class="fs-26 data-box bold"><i class="fas fa-arrow-right"></i> <?php echo $miembros_varones; ?> de <?php echo count($miembros->posts); ?></span>
                            <hr />
                            <span class="fs-18">miembros del Concejo son hombres</span>
                        </div>
                        <div class="col-sm-3 d-flex justify-content-center align-items-center pt-3 pb-3" style="background: <?php echo $city_color; ?>;">
                            <i class="fa fa-user fs-60 text-white"></i>
                        </div>
                    </div>
                    <div class="row  bg-ececec mt-3">
                        <div class="col-sm-9 p-1">
                            <span class="fs-26 data-box bold"><i class="fas fa-arrow-right"></i> <?php echo $miembros_rurales; ?> de <?php echo count($miembros->posts); ?></span>
                            <hr />
                            <span class="fs-18">concejales representan a la ruralidad</span>
                        </div>
                        <div class="col-sm-3 d-flex justify-content-center align-items-center pt-3 pb-3" style="background: <?php echo $city_color; ?>;">
                            <i class="fas fa-tractor fs-60 text-white"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 offset-md-1 mt-sm-3">
                    <img class="img-fluid" src="<?php echo THEME_URL . '/img/Proximamente-Home.png'; ?>">
                </div>
            </div>
        </div>
    </div>
    <?php if('on' != get_post_meta(get_the_ID(), 'oda_ciudad__concejo_transver', true)) { ?>
    <div class="section-concejotransparente">
        <div class="container">
            <div class="row mb-5 mt-5">
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <!--
                    <h4 class="fs-28"><i class="fas fa-arrow-right"></i> Concejo <strong>Transparente</strong></h4>
                    <hr class="hr-white" />
                    <p class="fs-28 bold">¡Incide!</p>
                    <p class="mb-3">Pide a tus representantes en el Concejo Municipal que transparenten información sobre su gestión</p>
                    -->
                    <?php if(!empty(get_post_meta(get_the_ID(), 'oda_ciudad__concejo_transizq', true))){ echo get_post_meta(get_the_ID(), 'oda_ciudad__concejo_transizq', true);} ?>
                    <br />
                    <a class="btn-concejo-transparente mt-3" href="<?php echo home_url('/ciudad/' . $post->post_name . '/consejo-municipal/'); ?>">Revisa si tu Concejal es <strong>transparente</strong></a>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 offset-lg-3">
                    <?php 
                        $popup_image_url = get_post_meta(get_the_ID(), 'oda_ciudad_info_popup_image', true);
                        if (!empty($popup_image_url)){ 
                    ?>
                    <a class="text-white" href="#" data-toggle="modal" data-target="#modalinfo"><i class="fas fa-info-circle fs-26"></i></a>
                    <div class="modal fade" id="modalinfo">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                            <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
                                <div class="modal-body">
                                    <div class="col-sm-12 text-center">
                                        <img class="img-fluid" src="<?php echo $popup_image_url; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <?php } // END if ?>
                    <p class="fs-28"><span class="fs-80 bold"><?php echo $miembros_concejo_transparente; ?> de <?php echo count($miembros->posts); ?></span></p>
                    <?php if(!empty(get_post_meta(get_the_ID(), 'oda_ciudad__concejo_transder', true))){ echo get_post_meta(get_the_ID(), 'oda_ciudad__concejo_transder', true);} ?>
                    <!--
                    <p>Miembros del Concejo que forman parte<br />del Concejo Transparente</p>
                    -->
                </div>
            </div>
        </div>
    </div>
    <?php } // END if shows ?>
    <?php
    $carpetas = get_post_meta(get_the_ID(), 'oda_ciudad_carpeta', false);
    ?>
    <div class="section-folders pt-4 pb-4">
        <div class="container">
            <div class="row pt-3">
                <div class="col-sm-12 mt-4">
                    <?php echo get_post_meta(get_the_ID(), 'oda_ciudad_texto_bottom', true); ?>
                </div>
            </div>
            <?php if ($carpetas) { ?>
            <div class="row mb-5 mt-5">
                <div class="col-md-12 <?php echo (count($carpetas[0]) < 5)? 'col-lg-10 offset-lg-1': ''?>">
                    <div class="w-100 d-block d-sm-flex justify-content-around folder-list mt-5">
                        <?php
                            foreach ($carpetas[0] as $index => $carpeta) {
                        ?>
                                <a class="folder-icon" href="#" data-toggle="modal" data-target="#modalcarpeta-<?php echo $index; ?>">
                                    <i class="fs-36 <?php echo $carpeta['oda_carpeta_icon']; ?>"></i>
                                    <span class="bold"><?php echo $carpeta['oda_carpeta_copy']; ?></span>
                                </a>
                                <?php
                                if (
                                    !empty($carpeta['description']) ||
                                    !empty($carpeta['image'])
                                ) {
                                ?>
                                    <div class="modal fade" id="modalcarpeta-<?php echo $index; ?>">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                            <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="d-flex h-100 flex-column justify-content-center align-items-center">
                                                                <div class="folder-popup-image">
                                                                    <i class="fs-36 <?php echo $carpeta['oda_carpeta_icon']; ?>"></i>
                                                                    <span><?php echo $carpeta['oda_carpeta_copy']; ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="row">
                                                                <!--
                                                                <div class="col-sm-1"><i class="fas fa-arrow-right text-blue-main"></i></div>
                                                                -->
                                                                <div class="col-sm-12"><?php echo $carpeta['description']; ?></div>
                                                            </div>
                                                            <hr />
                                                            <p class="fs-28 text-center bold"><span class="text-blue-main"><?php echo $interval->format('%a'); ?></span> días <span class="text-blue-main">sin</span> transparentar</p>
                                                        </div>
                                                        <div class="col-sm-3 text-center">
                                                            <div class="d-flex h-100 flex-column justify-content-center align-items-center">
                                                                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_post_meta(get_the_ID(),'oda_ciudad_tweet_folders', true) . ' - ' . get_post_meta(get_the_ID(),'oda_ciudad_twitter_user', true)); ?>" style="text-decoration: none;">
                                                                    <span class="twitter-circle-icon"><i class="fab fa-twitter text-white fs-20"></i></span>
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
                                    <div class="modal fade" id="modalcarpeta-<?php echo $index; ?>">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <?php if (empty($carpeta['description'])) { ?>
                                                            <div class="col-md-12 text-center">
                                                                <img class="img-fluid" src="<?php echo $carpeta['image']; ?>">
                                                            </div>
                                                        <?php } else if (empty($carpeta['image'])) { ?>
                                                            <div class="col-md-12">
                                                                <?php echo $carpeta['description']; ?>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="col-md-4">
                                                                <img class="img-fluid" src="<?php echo $carpeta['image']; ?>">
                                                            </div>
                                                            <div class="col-md-8">
                                                                <?php echo $carpeta['description']; ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    */ ?>
                                <?php } // End if 
                                ?>
                            <?php } // End foreach 
                            ?>
                    </div>
                </div>
            </div>
            <?php } // End if ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>