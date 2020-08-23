<?php

/**
 * Load scripts
 */
function oda_load_frontend_scripts($hook) {
 
    $my_js_ver = null;
    $my_css_ver = null;
    // create my own version codes
    //$my_js_ver  = date("ymd-Gis", filemtime( get_template_directory_uri() . '/js/oda-custom.js' ));
    //$my_css_ver = date("ymd-Gis", filemtime( get_template_directory_uri() . '/css/oda-styles.css' ));
     
    // 
    wp_enqueue_script( THEME_PREFIX . 'custom_js', THEME_URL . '/js/oda-custom.js', array(), $my_js_ver );
    wp_register_style( THEME_PREFIX . 'styles', THEME_URL . '/css/oda-styles.css', false,   $my_css_ver );
    wp_enqueue_style ( THEME_PREFIX . 'styles' );
 
}
add_action('wp_enqueue_scripts', 'oda_load_frontend_scripts');

/**
 * Rewrite API
 */
add_action('init', 'oda_rewrite_rules', 10, 0);
function oda_rewrite_rules() {
    //add_rewrite_tag('%city_slug%', '([^&]+)');
    add_rewrite_tag('%city_slug%', '([^/]*)');
    add_rewrite_tag('%oda_template%', '([^/]*)');
    add_rewrite_rule(
        'ciudad/([^/]*)/([^/]*)/?', // p followed by a slash, a series of one or more digits and maybe another slash
        'index.php?city_slug=$matches[1]&oda_template=$matches[2]',
        'top'
    );
    flush_rewrite_rules();
}


/**
 * Theme redirect
 */
add_filter('template_include', 'oda_redirect_template', 99);
function oda_redirect_template($template) {

    global $wp_query;
    
    if ( 'consejo-municipal' == $wp_query->query_vars['oda_template'] ) {
        $template = THEME_DIR . '/templates/concejo-municipal-template.php';
    }
    if ( 'evaluacion-de-gestion' == $wp_query->query_vars['oda_template'] ) {
        $template = THEME_DIR . '/templates/evaluacion-de-gestion-template.php';
    }
    
    if ( 'miembro' == $wp_query->query_vars['oda_template'] ) {
        $template = THEME_DIR . '/templates/miembro-detalle-template.php';
    }
    if ( 'contactanos' == $wp_query->query_vars['oda_template'] ) {
        $template = THEME_DIR . '/templates/page-contacto-template.php';
    }
    if ( 'sobre-nosotros' == $wp_query->query_vars['oda_template'] ) {
        $template = THEME_DIR . '/templates/page-sobre-nosotros-template.php';
    }
    
    
    return $template;
}
/*
if (!is_admin()){
    add_action('parse_request', 'ver_request');
}
function ver_request($query){
    global $wp_query;
    echo '<pre>';
    print_r($query);
    //print_r($wp_query);
    echo '</pre>';
}
*/