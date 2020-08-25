<?php
get_header();
global $wp_query;
$ciudad = $wp_query->query_vars['city_slug'];
$item = get_page_by_path($ciudad, OBJECT, 'ciudad');
$city_primary_color = get_post_meta($item->ID, 'oda_ciudad_color', true);

// Circunscripciones
$args = array(
    'post_type' => 'circunscripcion',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => 'oda_ciudad_owner',
            'value' => $item->ID,
            'compare' => '=',
        )
    )
);
$circunscripciones = new WP_Query($args);

// Circunscripciones
$args = array(
    'post_type' => 'comision',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => 'oda_ciudad_owner',
            'value' => $item->ID,
            'compare' => '=',
        )
    )
);
$comisiones = new WP_Query($args);

// Organizaciones políticas
$args = array(
    'post_type' => 'partido',
    'posts_per_page' => -1,
);
$org_politicas = new WP_Query($args);
?>
<style>
    .member-features {
        position: absolute;
        min-width: 300px;
        background: white;
        z-index: 99999;
        top: 0;
        left: 150px;
        box-shadow: 7px 6px 9px #00000026;
        display: none;
        max-height: 77px;
    }

    .member-container.right-box .member-features {
        right: 120px;
        left: auto;
    }
    .member-container.first-member .member-features{
        top: -90px;
        left: -44px;
    }

    .member-feature-img-placeholder {
        width: 100px;
    }

    .member-feature-content-placeholder {
        padding: 10px;
        width: 100%;
    }

    .member-feature-content-placeholder h2 {
        font-size: 20px;
    }
    .member-container { transition: transform ease-in .2s; }
    .member-container:not(.deactivated):hover .member-features {
        display: flex;
    }
    .member-container.activated { transform: scale(1.1); transition: transform ease-in .2s; }

    .info-sign-filters {
        color: <?php echo $city_primary_color; ?>;
    }

    #expand_text {
        cursor: pointer;
    }

    #clear_filters {
        border: none;
        background: transparent;
    }

    #show_results {
        padding: 3px 8px;
        border: 1px solid gray;
        background: lightgray;
    }

    .filter-box-content {
        background: #F5F5F5;
    }

    .filter-box-header,
    .filter-box-footer {
        background: #D9D9D9;
        padding-top: 3px;
        padding-bottom: 3px;
    }

    .filter-box-content .card {
        border: 0;
        background: transparent;
        margin-bottom: 15px;
    }

    .filter-box-content .btn-link {
        width: 100%;
        color: #222222;
    }

    .filter-box-content .card-header {
        padding: 0;
    }

    .filter-box-content .card-header:first-child {
        border-radius: 25px;
    }

    .filter-box-content .form-check {
        border-bottom: 1px solid #d0d0d0;
    }
</style>
<div class="container">
    <div class="row pt-3 pb-3">
        <div class="col-sm-12 pt-3 pb-3">
            <?php echo get_post_meta($item->ID, 'oda_ciudad_intro_concejo', true); ?>
        </div>
    </div>
    <div class="row pb-3">
        <div class="col-md-3 mb-3">
            <div class="row">
                <div class="col-md-2">
                    <a href="#" data-toggle="modal" data-target="#howtofilters">
                        <i class="fas fa-info-circle info-sign-filters fs-26 mb-3"></i>
                    </a>
                </div>
                <div class="col-md-10 fs-14 text-center">
                    <div id="show_results" style="display: none;">Resultados encontrados: <span id="results_amount" class="bold"></span></div>
                </div>
            </div>
            <div class="modal fade" id="howtofilters">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
                        <div class="modal-body">
                            <div class="row">
                                <img class="img-fluid" src="http://placehold.it/600x350?text=Manual%20para%20Filtros">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="filter-box">
                <form id="city_filters" role="form">
                    <div class="row filter-box-header bold">
                        <div class="col-5 col-sm-5">Filtros</div>
                        <div class="col-7 col-sm-7 text-right"> <span onclick="expandAll()"><span id="expand_text">|&nbsp;&nbsp;Expandir</span> todo</span></div>
                    </div>
                    <div class="row filter-box-content">
                        <div class="col-sm-12 p-3">
                            <?php if ($circunscripciones->have_posts()) { ?>
                                <div id="circunscripcion">
                                    <div class="card">
                                        <div class="card-header" id="circunscripcionh">
                                            <h5 class="mb-0">
                                                <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#circunscripcionc" aria-expanded="false" aria-controls="circunscripcionc">
                                                    <div class="row">
                                                        <div class="col-10  col-sm-10 text-left fs-14">Circunscripción</div>
                                                        <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                    </d2>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="circunscripcionc" class="collapse" aria-labelledby="circunscripcionh" data-parent="#circunscripcion">
                                            <div class="card-body">
                                                <?php while ($circunscripciones->have_posts()) {
                                                    $circunscripciones->the_post(); ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="circunscripcion" id="circ_<?php echo get_the_ID(); ?>" value="circunscripcion-<?php echo get_the_ID(); ?>">
                                                        <label class="form-check-label" for="circ_<?php echo get_the_ID(); ?>">
                                                            <?php echo get_the_title(); ?>
                                                        </label>
                                                    </div>
                                                <?php } // END While 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } // END if 
                            ?>
                            <div id="genero">
                                <div class="card">
                                    <div class="card-header" id="generoh">
                                        <h5 class="mb-0">
                                            <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#generoc" aria-expanded="false" aria-controls="generoc">
                                                <div class="row">
                                                    <div class="col-10 col-sm-10 text-left fs-14">Género</div>
                                                    <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                </div>
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="generoc" class="collapse" aria-labelledby="generoh" data-parent="#genero">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="genero" id="genero_1" value="genero-1">
                                                <label class="form-check-label" for="genero_1">
                                                    Masculino
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="genero" id="genero_2" value="genero-2">
                                                <label class="form-check-label" for="genero_2">
                                                    Femenino
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="genero" id="genero_3" value="genero-3">
                                                <label class="form-check-label" for="genero_3">
                                                    Sin especificar
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } // END Org Politicas 
                            ?>
                            <div id="comision">
                                <div class="card">
                                    <div class="card-header" id="comisionh">
                                        <h5 class="mb-0">
                                            <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#comisionc" aria-expanded="false" aria-controls="comisionc">
                                                <div class="row">
                                                    <div class="col-10 col-sm-10 text-left fs-14">Comisión</div>
                                                    <div class="col-2 col-sm-2 text-right"><i class="fas fa-chevron-down"></i></div>
                                                </d2>
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="comisionc" class="collapse" aria-labelledby="comisionh" data-parent="#comision">
                                        <div class="card-body">
                                            <?php while ($comisiones->have_posts()) {
                                                $comisiones->the_post(); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="comision" id="com_<?php echo get_the_ID(); ?>" value="com-<?php echo get_the_ID(); ?>">
                                                    <label class="form-check-label" for="com_<?php echo get_the_ID(); ?>">
                                                        <?php echo get_the_title(); ?>
                                                    </label>
                                                </div>
                                            <?php } // End While org politicas 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row filter-box-footer">
                        <div class="col-4 col-sm-4"></div>
                        <div class="col-8 col-sm-8 text-right"><button id="clear_filters" type="reset" class="btn-clear-filters bold">|&nbsp;&nbsp;Borrar filtros</button></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-8 offset-md-1">
            <!--
            <div class="row">
                <div class="col-sm-12 text-right">
                    <ul class="export-button-list list-no-style d-flex">
                        <li>Ver lista</li>
                        <li>Excel</li>
                        <li>CSV</li>
                    </ul>
                </div>
            </div>
            -->
            <div class="row">
                <div class="col-sm-12">
                    <?php
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
                                'value' => $item->ID,
                                'compare' => '=',
                            )
                        )
                    );
                    $args = apply_filters('listado_miembros', $args);
                    $miembros = new WP_Query($args);
                    if ($miembros->have_posts()) {
                        $i = 0;
                        $primero = true;
                        while ($miembros->have_posts()) {
                            $miembros->the_post();

                            $classes = array();

                            // Metadata
                            $member_id = get_the_ID();
                            $name = get_post_meta($member_id, 'oda_miembro_nombres', true);
                            $lastname = get_post_meta($member_id, 'oda_miembro_apellidos', true);
                            $circunscripcion = get_post_meta(get_the_ID(), 'oda_circunscripcion_owner', true);
                            $genero = get_post_meta(get_the_ID(), 'oda_miembro_gerero', true);
                            $tipo_cir = get_post(
                                get_post_meta($member_id, 'oda_circunscripcion_owner', true),
                                OBJECT
                            );

                            // Organizacion politica
                            $org_politica = get_post_meta(get_the_ID(), 'oda_partido_owner', true);
                            $partido_politico_object = get_post($org_politica, OBJECT);
                            $partido_politico_color_principal = get_post_meta($org_politica, 'oda_partido_color_principal', true);
                            $partido_politico_color_secundario = get_post_meta($org_politica, 'oda_partido_color_secundario', true);
                            $partido_politico_logo = get_the_post_thumbnail_url($org_politica);

                            // Filters
                            $classes[] = 'org-' . $org_politica;
                            $classes[] = 'circunscripcion-' . $circunscripcion;
                            $classes[] = 'genero-' . $genero;

                            // Comisiones
                            $args = array(
                                'post_type' => 'comision',
                                'posts_per_page' => -1,
                                'meta_query' => array(
                                    array(
                                        'key' => 'oda_ciudad_owner',
                                        'value' => $item->ID,
                                        'compare' => '=',
                                    )
                                )
                            );
                            //echo $member_id;
                            $comisiones_miembro = new WP_Query($args);
                            if ($comisiones_miembro->have_posts()) {
                                while ($comisiones_miembro->have_posts()) {
                                    $comisiones_miembro->the_post();
                                    $presidente = get_post_meta(get_the_ID(), 'oda_comision_composicion_presidente', true);
                                    $videpresidente = get_post_meta(get_the_ID(), 'oda_comision_composicion_vicepresidente', true);
                                    $demas = get_post_meta(get_the_ID(), 'oda_comision_composicion_miembros', true);
                                    //echo $member_id . ' - ' . $presidente . ' - ' .get_the_ID() .'<br />';
                                    if ($presidente == $member_id) {
                                        $classes[] = 'com-' . get_the_ID();
                                        continue;
                                    }
                                    if ($videpresidente == $member_id) {
                                        $classes[] = 'com-' . get_the_ID();
                                        continue;
                                    }
                                    if ($demas) {
                                        foreach ($demas as $otro) {
                                            if ($otro == $member_id) {
                                                $classes[] = 'com-' . get_the_ID();
                                            }
                                        }
                                    }
                                }
                            }
                            $miembros->reset_postdata();

                            $i++;
                            if (1 == $i) {
                                $primero_id = $member_id;
                                $primero_nombre = get_the_title();
                                $primero_classes = $classes;
                                $primero_link = get_the_permalink();
                                $primero_thumbnail = get_the_post_thumbnail_url();
                                $primero_logo_partido = $partido_politico_logo;
                                $primero_circunscripcion = $tipo_cir->post_title;
                    ?>
                                <div class="row d-block d-sm-none">
                                    <div class="col-sm-12 col-md-2 offset-md-5 text-center">
                                        <div data-memberid="<?php echo $member_id; ?>" class="member-container<?php foreach ($classes as $class) {
                                                                        echo ' ' . $class;
                                                                    } ?>">
                                            <div class="member-thumbnail">
                                                <a href="<?php echo get_the_permalink(); ?>">
                                                    <?php
                                                    if (has_post_thumbnail()) {
                                                        echo '<img width="100" class="img-fluid rounded-circle" src="' . get_the_post_thumbnail_url() . '" alt="' . get_the_title() . '" title="' . get_the_title() . '">';
                                                    } else {
                                                        echo '<img class="img-fluid rounded-circle" style="border: 5px solid ' . $partido_politico_color_secundario . ';" src="https://via.placeholder.com/100/'
                                                            . strtoupper(str_replace('#', '', $partido_politico_color_principal)) . '/000000'
                                                            . '?text=' . $name[0] . $lastname[0]
                                                            . '" alt="' . get_the_title() . '" title="' . get_the_title() . '">';
                                                    }
                                                    ?>
                                                </a>
                                                <div class="member-features">
                                                    <div class="member-feature-img-placeholder">
                                                        <img class="img-fluid no-bkg" src="<?php echo $partido_politico_logo; ?>">
                                                    </div>
                                                    <div class="member-feature-content-placeholder lh-0">
                                                        <h2 class="fs-18 bold"><?php echo get_the_title(); ?></h2>
                                                        <p class="lh-1 fs-16">Presidente <?php echo $tipo_cir->post_title; ?></p>
                                                        <?php /*
                                                        <p><?php echo $partido_politico_object->post_title; ?></p>
                                                        */ ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <?php
                            } // End if is he first
                            if ($i >= 2) {

                                if ($i >= 4) {
                                    $classes[] = 'right-box';
                                }
                                echo '<div class="col-md-2">';
                                // Content
                                ?>
                                    <div class="member-container<?php foreach ($classes as $class) {
                                                                    echo ' ' . $class;
                                                                } ?>">
                                        <div class="member-thumbnail">
                                            <a href="<?php echo get_the_permalink(); ?>">
                                                <?php
                                                if (has_post_thumbnail()) {
                                                    echo '<img width="100" class="img-fluid rounded-circle" src="' . get_the_post_thumbnail_url() . '" alt="' . get_the_title() . '" title="' . get_the_title() . '">';
                                                } else {
                                                    echo '<img class="rounded-circle" style="border: 5px solid ' . $partido_politico_color_secundario . ';" src="https://via.placeholder.com/100/'
                                                        . strtoupper(str_replace('#', '', $partido_politico_color_principal)) . '/000000'
                                                        . '?text=' . $name[0] . $lastname[0]
                                                        . '" alt="' . get_the_title() . '" title="' . get_the_title() . '">';
                                                }
                                                ?>
                                            </a>
                                            <div class="member-features">
                                                <div class="member-feature-img-placeholder">
                                                    <img class="img-fluid no-bkg" src="<?php echo $partido_politico_logo; ?>">
                                                </div>
                                                <div class="member-feature-content-placeholder text-center lh-0">
                                                    <h2 class="fs-18 bold"><?php echo get_the_title(); ?></h2>
                                                    <p class="lh-1 fs-16">Concejal <?php echo $tipo_cir->post_title; ?></p>
                                                    <?php /*
                                                    <p><?php echo $partido_politico_object->post_title; ?></p>
                                                    */ ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        <?php
                                // End Content
                                if ($i == 3) {
                                    if($primero == true){
                                        $primero = false;
                                        echo '</div><div class="col-md-4 text-center">';
                        ?>
                        <div data-memberid="<?php echo $primero_id; ?>" class="member-container first-member<?php foreach ($primero_classes as $class) {
                                                                        echo ' ' . $class;
                                                                    } ?>">
                            <div class="member-thumbnail">
                                <a href="<?php echo $primero_link; ?>">
                                    <?php
                                        echo '<img width="100" class="img-fluid rounded-circle" src="' . $primero_thumbnail . '" alt="' . $primero_nombre . '" title="' . $primero_nombre . '">';
                                    ?>
                                </a>
                                <div class="member-features">
                                    <div class="member-feature-img-placeholder">
                                        <img class="img-fluid no-bkg" src="<?php echo $primero_logo_partido; ?>">
                                    </div>
                                    <div class="member-feature-content-placeholder lh-0">
                                        <h2 class="fs-18 bold"><?php echo $primero_nombre; ?></h2>
                                        <p class="lh-1 fs-16">Presidente <?php echo $primero_circunscripcion; ?></p>
                                        <?php /*
                                        <p><?php echo $partido_politico_object->post_title; ?></p>
                                        */ ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                                        echo '</div>';
                                    }else{
                                        echo '</div><div class="col-md-4"></div>';
                                    }
                                } else {
                                    echo '</div>';
                                }
                            }
                            if ($i == 5) {
                                echo '</div><div class="row mt-4">';
                                $i = 1;
                            }
                        }
                    }
                        ?>
                                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
<script>
    $(document).ready(function() {
        $('#city_filters').change(function(e) {
            var results = 0;
            var fields = [];
            var classes = [];
            var indice = [];
            var selectors = '';
            var selectorQuery = '';
            fields = $(this).serialize().split('&');
            console.log(fields[0]);
            if (fields[0].length > 0) {
                $.each(fields, function(index, value) {
                    indice[index] = value.split('=');
                })
                $.each(indice, function(index, value) {
                    if (index + 1 == indice.length) {
                        selectors += '.' + value[1];
                    } else {
                        selectors += '.' + value[1];
                    }
                    results++;
                })
                $('.member-container').addClass('deactivated');
                $('.member-container').removeClass('activated');
                $(selectors).removeClass('activated');
                $(selectors).removeClass('deactivated');
            } else {
                $('.member-container').removeClass('activated');
                $('.member-container').removeClass('deactivated');
            }
            $('.member-container:not(.deactivated)').addClass('activated');
            $('#results_amount').text($('.member-container:not(.deactivated)').length);
            $('#show_results').show();

            //selectorQuery = selectors.substring(0, selectors.length - 1);
            //console.log(selectors);
            //console.log($(selectors));
        })
        // Limpiar los Filtros
        $('#clear_filters').click(function() {
            $('#show_results').hide();
            $('.member-container').removeClass('deactivated');
            $('.member-container').removeClass('activated');
        })
    })

    function expandAll() {
        console.log('click');
        if ($('#expand_text').text() == 'Expandir') {
            $('#expand_text').text('Contraer')
        } else {
            $('#expand_text').text('Expandir')
        }
        $('#circunscripcion .btn').click();
        $('#genero .btn').click();
        $('#organizacion .btn').click();
        $('#comision .btn').click();
    }
</script>