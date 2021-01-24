<?php
get_header();
global $wp_query;
$ciudad = $wp_query->query_vars['city_slug'];
$item = get_page_by_path($ciudad, OBJECT, 'ciudad');
$city_primary_color = get_post_meta($item->ID, 'oda_ciudad_color', true);

$circunscripciones = get_circunscripciones_ciudad($item->ID);
$comisiones = get_comisiones_ciudad($item->ID);
//$org_politicas = get_organizaciones_politicas();
$org_politicas = get_organizaciones_politicas_ciudad($item->ID);

?>
<style>
    .info-sign-filters {
        color: <?php echo $city_primary_color; ?>;
    }
</style>
<div class="container main-container">
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
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="dismis-modals" data-dismiss="modal"><i class="far fa-times-circle text-blue-main"></i></div>
                        <div class="modal-body">
                            <div class="row">
                                <?php
                                    $image_url = 'http://placehold.it/800x600?text=Manual';
                                    $imagen_popup = get_post_meta($item->ID, 'oda_ciudad_popupinfo_listado_miembros', true);
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

                                        <div id="circunscripcionc" class="collapse no-scroll" aria-labelledby="circunscripcionh" data-parent="#circunscripcion">
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
                                                <p class="ta-r bold"><span class="clean-radio" data-radio="circunscripcion">Desactivar filtro</span></p>
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

                                    <div id="generoc" class="collapse no-scroll" aria-labelledby="generoh" data-parent="#genero">
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
                                            <p class="ta-r bold"><span class="clean-radio" data-radio="genero">Desactivar filtro</span></p>
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
                                                <p class="ta-r bold"><span class="clean-radio" data-radio="organizacion">Desactivar filtro</span></p>
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
                                            <p class="ta-r bold"><span class="clean-radio" data-radio="comision">Desactivar filtro</span></p>
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
            <div class="row my-3">
                <div class="col-sm-12 col-lg-6 offset-lg-6 text-right">
                    <a class="ver-listado" href="<?php echo home_url('/ciudad/' . $item->post_name . '/ver-listado-de-miembros-de-concejo/'); ?>" target="_blank">
                    <div class="btn-oda view-ranking" data-doc="pdf_consolidado">
                        <span class="button-name">Ver lista</span>
                        <span class="button-icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    </a>
                    <div class="btn-oda excel-ranking" data-doc="excel_consolidado">
                        <span class="button-name">Excel</span>
                        <span class="button-icon"><i class="fas fa-download"></i></span>
                    </div>
                    <div class="btn-oda csv-ranking" data-doc="csv_consolidado">
                        <span class="button-name">CSV</span>
                        <span class="button-icon"><i class="fas fa-download"></i></span>
                    </div>
                </div>
            </div>
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
                                            </div>
                                            <div class="member-features">
                                                <div class="member-feature-img-placeholder">
                                                    <img class="img-fluid no-bkg" src="<?php echo $partido_politico_logo; ?>">
                                                </div>
                                                <div class="member-feature-content-placeholder lh-0">
                                                    <h2 class="fs-18 bold"><?php echo get_the_title(); ?></h2>
                                                    <p class="lh-1 fs-16">Alcalde <?php //echo $tipo_cir->post_title; ?></p>
                                                    <?php /*
                                                    <p><?php echo $partido_politico_object->post_title; ?></p>
                                                    */ ?>
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
                                        </div>
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
                                        <p class="lh-1 fs-16">Alcalde <?php //echo $primero_circunscripcion; ?></p>
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
            <div class="row mt-3">
                <div class="col-sm-12">
                    <p><?php echo '<p>' . DESCARGOS['periodo'] . '</p>'; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
<script>
    $(document).ready(function() {
        var cityid = <?php echo $item->ID; ?>;
        var citiName = '<?php echo $item->post_title; ?>';
        $('#city_filters').change(function(e) {
            console.log($('form').serialize());
            var results = 0;
            var fields = [];
            var classes = [];
            var indice = [];
            var selectors = '';
            var selectorQuery = '';
            fields = $(this).serialize().split('&');
            //console.log(fields[0]);
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
                $('.member-container:not(.deactivated)').addClass('activated');
            } else {
                $('.member-container').removeClass('activated');
                $('.member-container').removeClass('deactivated');
            }
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
        /*
        $('.clean-radio').click(function(){
            target = $(this).data('radio');
            $('input[name="'+target+'"]').prop('checked', false);
            $('#city_filters').change();
        })
        */
        // Clic en EXCEL
        $('.excel-ranking').click( function(){
            $.ajax({
                url: oda_dom_vars.ajaxurl,
                type: 'GET',
                data: {
                    action: 'oda_generate_consolidado_miembros_xls',                    
                    city: cityid,
                    cityname:citiName
                },
                beforeSend: function(){
                    $('body').toggleClass('loading-overlay-showing');
                },
                success: function(data){
                    $('body').toggleClass('loading-overlay-showing');
                    console.log(data);
                    var $a = $("<a>");
                    $a.attr("href",data.file);
                    $("body").append($a);
                    $a.attr("download","OC_listado_miembros_concejo_municipal_"+citiName+".xls");
                    $a[0].click();
                    $a.remove();                   
                },
                error: function(xhr,err){
                    console.log(err);
                    console.log(xhr);
                }

            })

        })
        // Clic en CSV 
        $('.csv-ranking').click( function(){
            $.ajax({
                url: oda_dom_vars.ajaxurl,
                type: 'GET',
                data: {
                    action: 'oda_generate_csv_consolidado_miembros',
                    city: cityid,
                },
                
                xhrFields: {
                    responseType: 'blob'
                },
                
                beforeSend: function(){
                    $('body').toggleClass('loading-overlay-showing');
                },
                success: function(data){
                    $('body').toggleClass('loading-overlay-showing');
                    console.log(data);
                    
                    
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(data);
                    a.href = url;
                    a.download = 'OC_listado_miembros_concejo_municipal_'+citiName+'.csv';
                    document.body.append(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                    
                    
                },
                error: function(xhr,err){
                    console.log(err);
                    console.log(xhr);
                }

            })

        })


    })
</script>