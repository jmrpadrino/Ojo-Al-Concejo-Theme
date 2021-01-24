<?php 
get_header(); 
$page = get_page_by_path('sobre-nosotros');
$page_type = 'city';

if (is_page()){
    $page_type = 'general';
}else{
    global $wp_query;
    $city_slug = $wp_query->query_vars['city_slug'];
    $city = get_page_by_path( $city_slug, OBJECT, 'ciudad');
    $city_color = get_post_meta($city->ID, 'oda_ciudad_color', true);
}
?>
<style>
    .btn-secondary {
        border-radius: 25px;
    }
    .address-box {
        padding-top: 50px!important;
        padding-bottom: 30px!important;
    }
    .hero-video {
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        <?php if($page_type == 'city'){?>
        background-color: <?php echo $city_color; ?>;
        background-blend-mode: multiply;
        <?php } ?>
        background-image: url(<?php echo THEME_URL . '/img/about-us-' . $page_type . '.jpg'; ?>);
    }
</style>
<section class="hero-video pt-2 pb-2">
    <div class="container ">
        <div class="row">
            <div class="col-md-6">
                <?php echo get_post_meta($page->ID, 'oca_texto_sup', true); ?>
            </div>
            <div class="col-md-6">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe src="https://www.youtube.com/embed/<?php echo get_post_meta($page->ID, 'oca_videp_sup', true); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="why">
    <div class="container mt-4 mb-4">
        <div class="row">
            <div class="col-md-6">
                <?php echo get_post_meta($page->ID, 'oca_texto_inf', true); ?>
                <p class="fs-26"><strong>Apoya</strong> a esta <strong>iniciativa</strong></p>
                <div class="row">
                    <div class="col-md-3">
                        <img class="img-fluid" src="<?php echo THEME_URL . '/img/NED-logo.png'; ?>" alt="NED">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3 class="fs-36 bold">Metodología</h3>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe src="https://www.youtube.com/embed/<?php echo get_post_meta($page->ID, 'oca_videp_inf', true); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-3">
                        <a href="#" class="btn btn-secondary fs-14">Ver&nbsp;&nbsp;&nbsp;<i class="far fa-file-alt"></i></a>
                    </div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-secondary fs-14">Descargar&nbsp;&nbsp;&nbsp;<i class="fas fa-file-download"></i></a>
                    </div>
                    <div class="col-sm-4"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php echo $page->post_content; ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>