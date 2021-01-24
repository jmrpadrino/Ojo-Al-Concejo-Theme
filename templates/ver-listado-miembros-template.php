<?php
get_header();
global $wp_query;
$ciudad = $wp_query->query_vars['city_slug'];
$item = get_page_by_path($ciudad, OBJECT, 'ciudad');
$city_primary_color = get_post_meta($item->ID, 'oda_ciudad_color', true);

$circunscripciones = get_circunscripciones_ciudad($item->ID);
$comisiones = get_comisiones_ciudad($item->ID);
$org_politicas = get_organizaciones_politicas();

?>
<style>
    .info-sign-filters {
        color: <?php echo $city_primary_color; ?>;
    }
    table td {
        border: 1px solid black;
        padding: 5px;
    }
</style>
<div class="container main-container">
    <div class="row pt-3 pb-3">
        <div class="col-sm-12 pt-3 pb-3">
            <?php echo get_post_meta($item->ID, 'oda_ciudad_intro_concejo_ver', true); ?>
        </div>
    </div>
    <div class="row pb-3">
        <div class="col-sm-12">
            <table class="table-hover">
                <thead class="thead-dark" style="color: white; background: #4A4F55; font-size: 12px; font-weight:bold;">
                    <tr>
                        <td class="align-middle" align="center" width="160">Miembro del Concejo</td>
                        <td class="align-middle" align="center">Votaciones asistidas</td>
                        <td class="align-middle" align="center">Votaciones ausentes</td>
                        <td class="align-middle" align="center">Participación de suplente en votaciones</td>
                        <td class="align-middle" align="center">Votaciones Consideradas</td>
                        <td class="align-middle" align="center">Proyectos de ordenanzas</td>
                        <td class="align-middle" align="center">Proyectos de resoluciones</td>
                        <td class="align-middle" align="center">Observaciones a proyectos de ordenanza</td>
                        <td class="align-middle" align="center">Solicitudes de información</td>                        
                    </tr>
                </thead>
                <tbody>
                <?php foreach( get_ranking_votaciones($item->ID) as $miembro ){ ?>
                    <tr>
                        <td align="center"><?php echo $miembro['nombres'] .' '. $miembro['apellidos'];?></td>
                        <td align="center"><?php echo $miembro['as']; ?></td>
                        <td align="center"><?php echo $miembro['au']; ?></td>
                        <td align="center"><?php echo $miembro['de']; ?></td>
                        <td align="center"><?php echo $miembro['total']; ?></td>
                        <td align="center"><?php echo $miembro['or']; ?></td>
                        <td align="center"><?php echo $miembro['re']; ?></td>
                        <td align="center"><?php echo $miembro['ob']; ?></td>
                        <td align="center"><?php echo $miembro['so']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php get_footer(); ?>