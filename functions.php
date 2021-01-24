<?php

/**
 * Declarations
 */
define( 'THEME_DIR', get_template_directory() );
define( 'THEME_URL', get_template_directory_uri() );
define( 'THEME_PREFIX', 'oda_' );
define( 'PERIOD_BEGINS', '2019-05-14');

/**
 * Notas de descargo
 */
$descargos = array(
    'periodo'       => get_option('descargo_periodo'),
    'remitido'      => get_option('descargo_remitido'),
    'votaciones'    => get_option('descargo_votaciones'),
    'cuantitativo'  => get_option('descargo_cuantitativo')
);
define('DESCARGOS', $descargos);

/**
 * Setup of scripts and dependencies
 */
function ecopre($array, $die = false){
    echo '<pre>';
    var_dump($array);
    echo '</pre>';
    if ($die){
        die;
    }
}
require_once('config/helpers.php');
require_once('config/config.php');
