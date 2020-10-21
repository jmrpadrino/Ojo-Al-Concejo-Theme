<?php
// Comision
function get_comisiones_ciudad($city_id = ''){
    wp_reset_query();
    $args = array(
        'post_type' => 'comision',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'oda_ciudad_owner',
                'value' => $city_id,
                'compare' => '=',
            )
        )
    );
    $comision = new WP_Query($args);
    return $comision;
}

// Organizaciones políticas
function get_organizaciones_politicas(){
    wp_reset_query();
    $args = array(
        'post_type' => 'partido',
        'posts_per_page' => -1,
    );
    $org_politicas = new WP_Query($args);
    return $org_politicas;
}

// Circunscripciones por ciudad
function get_circunscripciones_ciudad($city_id = ''){
    $args = array(
        'post_type' => 'circunscripcion',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'oda_ciudad_owner',
                'value' => $city_id,
                'compare' => '=',
            )
        )
    );
    return new WP_Query($args);
}

// Ordenanzas por ciudad
function get_ordenanzas_ciudad($city_id = ''){
    $args = array(
        'post_type' => 'ordenanza',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'oda_ciudad_owner',
                'value' => $city_id,
                'compare' => '=',
            )
        )
    );
    return new WP_Query($args);
}

// Temas por ciudad
function get_temas_documento_ciudad($city_id = '', $documento = ''){
    $args = array(
        'post_type' => $documento,
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'ID',
        'meta_query' => array(
            array(
                'key' => 'oda_ciudad_owner',
                'value' => $city_id,
                'compare' => '=',
            )
        )
    );
    return new WP_Query($args);
}

function mocion_votacion_fase_aprobacion($ord_id = '', $fase_index = '', $partidos_lista = array()){

    $args = array(
        'post_type' => 'mocion',
        'posts_per_page' => 1,
        'meta_key' => 'oda_ciudad_owner',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'mocion_documento',
                'value' => $ord_id,
                'compare' => '=',
            ),
            array(
                'key' => 'mocion_fase',
                'value' => $fase_index,
                'compare' => '=',
            )
        )
    );
    $mocion = new WP_Query($args);
    //return $mocion->post_count;
    if($mocion->post_count > 0){
        $votos = array();
        $votos_si = $votos_no = $votos_ab = $votos_bl = array();

        $mocion_id = $mocion->posts[0]->ID;
        $total = $si = $no = $ab = $bl = 0;
        $votaciones = get_post_meta($mocion_id,'oda_sesion_mocion', true);
        foreach($votaciones as $voto){
            $partido_obj = get_post(get_post_meta($voto['member_id'], 'oda_partido_owner', true));
            $partido_slug = $partido_obj->post_name;
            if( !in_array($partido_slug, $partidos_lista) ){
                $partido_slug = 'otras-organizaciones';
            }
            switch ($voto['mocion_voto']) {
                case '1':
                    // encontrar partido del miembro y anotar su voto
                    $votos_si[ $partido_slug ] += 1;
                    $si++;
                    $total++;
                    break;
                case '2':
                    $votos_no[ $partido_slug ] += 1;
                    $no++;
                    $total++;
                    break;
                case '3':
                    $votos_ab[ $partido_slug ] += 1;
                    $ab++;
                    $total++;
                    break;
                case '4':
                    $votos_bl[ $partido_slug ] += 1;
                    $bl++;
                    $total++;
                    break;
            }

        };

        $votos_si['voto'] = 'Afirmativos ('. number_format(($si*100) / $total,2) .'%)';
        $votos_no['voto'] = 'Negativos ('. number_format(($no*100) / $total,2) .'%)';
        $votos_ab['voto'] = 'Abstenciones ('. number_format(($ab*100) / $total,2) .'%)';
        $votos_bl['voto'] = 'Blancos ('. number_format(($bl*100) / $total,2) .'%)';       
        /* $votos = array(
            'si' => $si,
            'no' => $no,
            'ab' => $ab,
            'bl' => $bl,
        ); */

        $votos = array(
            $votos_si,
            $votos_no,
            $votos_ab,
            $votos_bl     
        );
        
        //return $votos;
        return $votos;
    }else{
        return false;
    }
}

// Obtener partidos politicos por documento
function get_partido_politico_documento($documento, $tipo){
    $partidos = array();
    $miembros_id = get_post_meta($documento, 'oda_'.$tipo.'_miembros', true);
    if (!$miembros_id){
        return false;
    }else{
        foreach($miembros_id as $miembro){
            if(
                !in_array(
                    get_post_meta($miembro, 'oda_partido_owner', true),
                    $partidos
                    )
                ){
                $partidos[] = get_post_meta($miembro, 'oda_partido_owner', true);
            }
        }
    }
    return $partidos;
}

// Observaciones por ciudad
function get_observaciones_ciudad($city_id = ''){
    $args = array(
        'post_type' => 'observacion',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'oda_ciudad_owner',
                'value' => $city_id,
                'compare' => '=',
            )
        )
    );
    return new WP_Query($args);
}

// Observaciones por miembro
function get_observaciones_miembro($miembro_id = ''){
    $args = array(
        'post_type' => 'observacion',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'oda_observacion_miembro',
                'value' => $miembro_id,
                'compare' => '=',
            )
        )
    );
    return new WP_Query($args);
}

// Resoluciones por ciudad
function get_resoluciones_ciudad($city_id = ''){
    $args = array(
        'post_type' => 'resolucion',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'oda_ciudad_owner',
                'value' => $city_id,
                'compare' => '=',
            )
        )
    );
    return new WP_Query($args);
}

// Solicitudes de Información por ciudad
function get_solicitudes_informacion($city_id = ''){
    $args = array(
        'post_type' => 'solicitud-info',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'oda_ciudad_owner',
                'value' => $city_id,
                'compare' => '=',
            )
        )
    );
    return new WP_Query($args);
}

// Solicitudes de Información por ciudad
function get_solicitudes_comparecencia($city_id = ''){
    $args = array(
        'post_type' => 'solicitud-comp',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'oda_ciudad_owner',
                'value' => $city_id,
                'compare' => '=',
            )
        )
    );
    return new WP_Query($args);
}

// Solicitudes de Información por ciudad
function get_instituciones($city_id = ''){
    $args = array(
        'post_type' => 'instituciones',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'oda_ciudad_owner',
                'value' => $city_id,
                'compare' => '=',
            )
        )
    );
    return new WP_Query($args);
}


// Comisiones por miembro
function get_comisiones_miembro($miembro_id = '', $city_id = ''){
    $comisiones_ciudad = get_comisiones_ciudad($city_id);
    if ( $comisiones_ciudad->have_posts() ){
        $comisiones = array();
        while ( $comisiones_ciudad->have_posts() ){
            $comisiones_ciudad->the_post();
            $presidente = get_post_meta(get_the_ID(), 'oda_comision_composicion_presidente', true);
            $videpresidente = get_post_meta(get_the_ID(), 'oda_comision_composicion_vicepresidente', true);
            $demas = get_post_meta(get_the_ID(), 'oda_comision_composicion_miembros', true);
            //echo $member_id . ' - ' . $presidente . ' - ' .get_the_ID() .'<br />';
            if ($presidente == $miembro_id){
                $comisiones[] = array(
                    'nombre' => get_the_title(),
                    'cargo' => 'Presidente'
                );
                continue;
            }
            if ($videpresidente == $miembro_id){
                $comisiones[] = array(
                    'nombre' => get_the_title(),
                    'cargo' => 'Vicepresidente'
                );
                continue;
            }
            if( $demas ){
                foreach($demas as $otro){
                    if($otro == $miembro_id){
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

    return array_reverse($comisiones);
}

// Partido Político del Miembro
function get_partido_politico_miembro($miembro_id = ''){
    $organizacion_politica_obj = array();

    if($miembro_id){
    
        $org_politica = get_post_meta($miembro_id, 'oda_partido_owner', true);
        $partido_politico_object = get_post($org_politica, OBJECT);
        $partido_politico_nombre = get_the_title($org_politica);
        $partido_politico_color_principal = get_post_meta($org_politica, 'oda_partido_color_principal', true);
        $partido_politico_color_secundario = get_post_meta($org_politica, 'oda_partido_color_secundario', true);
        $partido_politico_logo = get_the_post_thumbnail_url($org_politica);

        $organizacion_politica_obj = array(
            'orb_pol_OBJ'       => $partido_politico_object,
            'nombre'            => $partido_politico_nombre,
            'logo'              => $partido_politico_logo,
            'color_primario'    => $partido_politico_color_principal,
            'color_secundario'  => $partido_politico_color_secundario,
        );
    }

    return $organizacion_politica_obj;
}

// Circunscripción del Miembro
function get_circunscripcion_miembro($miembro_id = ''){
    $circunscripcion = '';
    $circunscripcion = get_post(
        get_post_meta($miembro_id, 'oda_circunscripcion_owner', true),
        OBJECT
    );
    return $circunscripcion; 
}


/**
 * FUNCIONES PARA LA VISTA DE PERFIL
 */
// Obtener asistencias en mociones por miembro
function estadisticas_del_miembro($miembro_id = ''){
    $as = $au = $ex = $de = $si = $no = $ab = $bl = 0;
    $estadisticas = array();
    $ciudad_miembro = get_post_meta($miembro_id, 'oda_ciudad_owner', true);
    $mociones = get_mociones_ciudad($ciudad_miembro);
    foreach($mociones->posts as $mocion){
        $metas = get_post_meta($mocion->ID, 'oda_sesion_mocion',true);
        if(isset($metas[$miembro_id]['member_excusa'])){
            // se excusó
            $ex++;
            if(isset($metas[$miembro_id]['member_suplente'])){
                $de++;
            }
        }else{
            // no se excusó, asistíó o no
            if (isset($metas[$miembro_id]['member_ausente'])){
                // No asistió y se excusó se cuentan votos para suplente
                $au++;
            }else{
                // Asistió se cuentan votos a miembro principal
                switch ($metas[$miembro_id]['mocion_voto']) {
                    case '1':
                        $si++; $as++;
                        break;
                    case '2':
                        $no++; $as++;
                        break;
                    case '3':
                        $ab++; $as++;
                        break;
                    case '4':
                        $bl++; $as++;
                        break;
                }
            }
        }
    }
    $estadisticas = array(
        'asistencias'   => $as,
        'ausencias'     => $au,
        'excusas'       => $ex,
        'delego'        => $de,
        'voto_si'       => $si,
        'voto_no'       => $no,
        'voto_ab'       => $ab,
        'voto_bl'       => $bl,
    );

    return $estadisticas;
}

// Documentos ordenanzas y resoluciones del miembro
function documentos_del_miembro($miembro_id= ''){
    $ord = $res = $obs = $sol_info = 0;
    $documentos = array();
    $ciudad_miembro = get_post_meta($miembro_id, 'oda_ciudad_owner', true);
    $ordenanzas = get_ordenanzas_ciudad($ciudad_miembro);
    $resoluciones = get_resoluciones_ciudad($ciudad_miembro);
    $observaciones = get_observaciones_miembro($miembro_id);
    $solicitudes_info = get_solicitudes_informacion($ciudad_miembro);
    foreach ($ordenanzas->posts as $ordenanza) {
        $lista_miembros = array();
        $lista_miembros = get_post_meta($ordenanza->ID, 'oda_ordenanza_miembros', true);
        if($lista_miembros){
            if(in_array($miembro_id,$lista_miembros)){
                $ord++;
            }
        }
    }
    foreach ($resoluciones->posts as $resolucion) {
        $lista_miembros = array();
        $lista_miembros = get_post_meta($resolucion->ID, 'oda_resolucion_miembros', true);
        if($lista_miembros){
            if(in_array($miembro_id,$lista_miembros)){
                $res++;
            }
        }
    }
    foreach ($solicitudes_info->posts as $solicitud_info) {
        $lista_miembros = array();
        $lista_miembros = get_post_meta($solicitud_info->ID, 'oda_solicitud_info_miembros', true);
        if($lista_miembros){
            if(in_array($miembro_id,$lista_miembros)){
                $sol_info++;
            }
        }
    }
    
    $obs = $observaciones->post_count;
    $documentos = array(
        'ordenanzas' => $ord,
        'resoluciones' => $res,
        'observaciones' => $obs,
        'solicitudes' => $sol_info
    );
    return $documentos;
}