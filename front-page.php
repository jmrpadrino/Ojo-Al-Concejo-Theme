<?php get_header(); ?>
<style scoped>
    .hero-text {
        padding-top: 50px;
        padding-bottom: 50px;
    }

    /* The flip card container - set the width and height to whatever you want. We have added the border property to demonstrate that the flip itself goes out of the box on hover (remove perspective if you don't want the 3D effect */
    .flip-card {
        background-color: transparent;
        width: 250px;
        height: 250px;
        *border: 1px solid #f1f1f1;
        perspective: 1000px;
        /* Remove this if you don't want the 3D effect */
        margin: 0 auto;
        position: relative;
    }
    .flip-card .rounded-circle {
        box-shadow: 5px 6px 13px rgba(0,0,0,.2);
        background: white;
    }
    /* This container is needed to position the front and back side */
    .flip-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.4s;
        transform-style: preserve-3d;
    }

    /* Do an horizontal flip when you move the mouse over the flip box container */
    .flip-card:hover .flip-card-inner {
        transform: rotateY(180deg);
    }

    /* Position the front and back side */
    .flip-card-front,
    .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden;
        /* Safari */
        backface-visibility: hidden;
    }

    /* Style the front side (fallback if image is missing) */
    .flip-card-front {
        *background-color: #bbb;
        color: black;
    }

    /* Style the back side */
    .flip-card-back {
        color: #222222;
        transform: rotateY(180deg);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .organic-front-page{
        position: absolute;
        left: -75px;
        width: 80%;
        top: -134px;
    }
    .organic-front-page img{ width: 200%; }
    .flip-card.odd-element .organic-front-page{
        transform: rotate(180deg);
        left: 126px;
        top: 167px;
    }
</style>
<div class="container hero-text">
    <div class="row">
        <div class="col-sm-12 col-md-6 offset-md-3 text-center">
            <h1>Iniciativa <strong>ciudadana</strong> <br/>de <strong>monitoreo</strong> a los<br /><strong>Concejos Municipales</strong></h1>
        </div>
    </div>
</div>
<div class="city-list pt-3 pb-3">
    <div class="container pt-3 pb-3">
        <div class="row pt-3 pb-3">
            <?php
            $i = 0;
            $args = array(
                'post_type' => 'ciudad',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            );
            $ciudades = new WP_Query($args);
            if ($ciudades->have_posts()) {
                while ($ciudades->have_posts()) {
                    $ciudades->the_post();
                    $nombre = get_the_title();
                    $link = get_the_permalink();
            ?>
            <div class="col-md-4 text-center">
                <a class="text-black-light" href="<?php echo $link; ?>">
                    <div class="flip-card<?php echo ($i % 2 != 0) ? ' odd-element': ''; ?>">
                        <div class="organic-front-page">
                            <img src="<?php echo THEME_URL . '/img/textura-logo.png'; ?>">
                        </div>
                        <div class="flip-card-inner front-city-circle-container1">
                            <div class="flip-card-front">
                                <?php if ( has_post_thumbnail() ){ ?>
                                    <img class="img-fluid rounded-circle" src="<?php echo the_post_thumbnail_url(); ?>" alt="<?php echo $nombre; ?>">    
                                <?php }else { ?>
                                <img class="img-fluid rounded-circle" src="http://placehold.it/250x250?text=<?php echo $nombre; ?>" alt="<?php echo $nombre; ?>">
                                <?php } ?>
                            </div>
                            <div class="flip-card-back rounded-circle">
                                <p class="fs-24 p-3 m-0 lh-1">Accede a datos del <strong>Concejo</strong> Municipal de <strong><?php echo $nombre; ?></strong></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php
                $i++;
                }
            }
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>