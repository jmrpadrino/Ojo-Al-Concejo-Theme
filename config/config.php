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
    wp_register_script( THEME_PREFIX . 'custom_js', THEME_URL . '/js/oda-custom.js', array('jquery'), $my_js_ver, true );
    wp_register_style( THEME_PREFIX . 'styles', THEME_URL . '/css/oda-styles.css', false,   $my_css_ver );
    wp_enqueue_style ( THEME_PREFIX . 'styles' );
    wp_localize_script(THEME_PREFIX . 'custom_js', 'oda_dom_vars', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ));
    wp_enqueue_script( THEME_PREFIX . 'custom_js' );
 
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
        add_filter( 'wp_title', function(){ return '| ¿Quiénes integran al Concejo Municipal?'; }, 10, 2 );
        $template = THEME_DIR . '/templates/concejo-municipal-template.php';
    }
    if ( 'evaluacion-de-gestion' == $wp_query->query_vars['oda_template'] ) {
        add_filter( 'wp_title', function(){ return '| Evaluación de Gestión del Concejo Municipal'; }, 10, 2 );
        $template = THEME_DIR . '/templates/evaluacion-de-gestion-template.php';
    }
    if ( 'miembro' == $wp_query->query_vars['oda_template'] ) {
        $template = THEME_DIR . '/templates/miembro-detalle-template.php';
    }
    if ( 'contactanos' == $wp_query->query_vars['oda_template'] ) {
        add_filter( 'wp_title', function(){ return '| Contáctanos'; }, 10, 2 );
        $template = THEME_DIR . '/templates/page-contacto-template.php';
    }
    if ( 'sobre-nosotros' == $wp_query->query_vars['oda_template'] ) {
        add_filter( 'wp_title', function(){ return '| Acerca de FCD'; }, 10, 2 );
        $template = THEME_DIR . '/templates/page-sobre-nosotros-template.php';
    }
    if ( 'ver-listado-de-miembros-de-concejo' == $wp_query->query_vars['oda_template'] ) {
        add_filter( 'wp_title', function(){ return '| Listado de miembros del concejo'; }, 10, 2 );
        $template = THEME_DIR . '/templates/ver-listado-miembros-template.php';
    }
    if ( 'proyectos-de-ordenanza' == $wp_query->query_vars['oda_template'] ) {
        add_filter( 'wp_title', function(){ return '| Proyectos de Ordenanzas del Concejo Municipal'; }, 10, 2 );
        $template = THEME_DIR . '/templates/proyectos-de-ordenanza-template.php';
    }
    if ( 'proyectos-de-resolucion' == $wp_query->query_vars['oda_template'] ) {
        add_filter( 'wp_title', function(){ return '| Proyectos de Resoluciones del Concejo Municipal'; }, 10, 2 );
        $template = THEME_DIR . '/templates/proyectos-de-resolucion-template.php';
    }
    if ( 'observaciones-a-proyectos-de-ordenanza' == $wp_query->query_vars['oda_template'] ) {
        add_filter( 'wp_title', function(){ return '| Observaciones a proyectos de Ordenanza del Concejo Municipal'; }, 10, 2 );
        $template = THEME_DIR . '/templates/observaciones-a-proyectos-de-ordenanza-template.php';
    }
    if ( 'solicitudes-de-informacion' == $wp_query->query_vars['oda_template'] ) {
        add_filter( 'wp_title', function(){ return '| Solicitudes de Información al Concejo Municipal'; }, 10, 2 );
        $template = THEME_DIR . '/templates/solicitudes-de-informacion-template.php';
    }
    if ( 'solicitudes-de-comparecencia' == $wp_query->query_vars['oda_template'] ) {
        $template = THEME_DIR . '/templates/solicitudes-de-comparecencia-template.php';
    }
    if ( 'tu-concejo-en-cifras' == $wp_query->query_vars['oda_template'] ) {
        $template = THEME_DIR . '/templates/tu-concejo-en-cifras-template.php';
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
add_action('wp_ajax_nopriv_oda_generate_csv_evaluacion', 'oda_generate_csv_evaluacion');
add_action('wp_ajax_oda_generate_csv_evaluacion', 'oda_generate_csv_evaluacion');
function oda_generate_csv_evaluacion(){
    $ranking = get_ranking_votaciones($_GET['city']);
    //$id = array_column($ranking,'id');
    //$name = array_column($ranking,'apellidos');
    //array_multisort($columna, SORT_DESC,  $name, SORT_ASC, $ranking);
    /*
    foreach ($ranking as $rank){
        echo $rank[0];
    }
    */
    $csv_fields=array();
    $csv_fields[] = 'Miembro del Concejo';
    $csv_fields[] = 'Votaciones asistidas';
    $csv_fields[] = 'Votaciones ausentes';
    $csv_fields[] = 'Participación del suplente en votaciones';
    $csv_fields[] = 'Votaciones consideradas';
    $csv_fields[] = 'Proyectos de ordenanzas';
    $csv_fields[] = 'Proyectos de resoluciones';
    $csv_fields[] = 'Observaciones a proyectos de ordenanzas';
    $csv_fields[] = 'Solicitudes de información';
    

    $output_handle = @fopen( 'php://output', 'w' );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv;charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename=oda_ciudad_documento_cambiar_esto.csv');
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    // Insert header row
    fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
    

    
    // Parse results to csv format
    foreach ($ranking as $rank) {
        $csv_fields=array();
        $csv_fields[] = $rank['title'];
        $csv_fields[] = $rank['as'];
        $csv_fields[] = $rank['au'];
        $csv_fields[] = $rank['de'];
        $csv_fields[] = $rank['as'] + $rank['au'];
        $csv_fields[] = $rank['or'];
        $csv_fields[] = $rank['re'];
        $csv_fields[] = $rank['ob'];
        $csv_fields[] = $rank['so'];
        //$row = '';
        //$row = '';
        //$leadArray = (array) $Result; // Cast the Object to an array
        // Add row to file
        fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
    }
    //var_dump( $rank );
    

    // Close output file stream
    fclose( $output_handle );
    die;
}

add_action('wp_ajax_nopriv_oda_generate_xls_evaluacion', 'oda_generate_xls_evaluacion');
add_action('wp_ajax_oda_generate_xls_evaluacion', 'oda_generate_xls_evaluacion');
function oda_generate_xls_evaluacion(){
    $ranking = get_ranking_votaciones($_GET['city']);
    $city_name = get_the_title($_GET['city']);
    $city_logo = get_post_thumbnail_id($_GET['city'], 'post-thumbnail');
    $city_logo = wp_get_attachment_metadata($city_logo);
    // var_dump($city_logo);
    // die;
    $city_logo = wp_upload_dir()['basedir'] . '/' . $city_logo['file'];    

    try{
    if ( defined('CBXPHPSPREADSHEET_PLUGIN_NAME') && file_exists( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' ) ) {
        //Include PHPExcel
        require_once( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' );
        
        $city=$_GET['cityname'];
        $ndatos=30; //numero limite de datos
        $fecha=date('Y-m-d'); //ultima fecha de la ordenza
        //Crear instancia excel
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //Logos cabecera
        
        //Logo ciudad
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($city_logo);
        $drawing->setWidth(180); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(180);
        $drawing->setCoordinates('B1');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

        //Logo FCD
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(THEME_DIR . '/img/FCD-top.png');
        $drawing->setWidth(230); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(100);
        $drawing->setCoordinates('I3');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        $drawing->setOffsetX(155);                      
        $drawing->setOffsetY(10);
        //Styles
        $titulostyle = array(
            'font' => [
                         'name' => 'Arial',
                         'bold' => true,
                         //'italic' => false,
                         //'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_DOUBLE,
                         'strikethrough' => false,
                         /*'color' => [
                             'rgb' => '808080'
                         ]*/
                     ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $headerstyle=array('font' => [
            'name' => 'Arial',
            'bold' => true,
            'strikethrough' => false,
              ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $urlstyle=array(
            'font' => [
                'name' => 'Arial',
                'color' => [
                                'rgb' => '0000FF'
                            ]
                ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $longstyle=array(
            'font' => [
                'size' =>9
            ]
            ,
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $style=array(
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $ndatos=$ndatos+12;
        
       
        //Columnas ancho ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('26');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('14');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('14');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('14');
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('15');
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('14');
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('14');
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('17');
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth('14');

        //TITULO
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B8','Listado de miembros del Concejo de ' . $city_name)
            ->setCellValue('B9','Período '.PERIOD_BEGINS.' a '.$fecha)
            ->setCellValue('B11','Miembro del Concejo')
            ->setCellValue('C11','Votaciones asistidas')
            ->setCellValue('D11','Votaciones ausentes')
            ->setCellValue('E11','Participación del suplente en votaciones')
            ->setCellValue('F11',"Votaciones consideradas")
            ->setCellValue('G11',"Proyectos de ordenanzas")
            ->setCellValue('H11',"Proyectos de resoluciones")
            ->setCellValue('I11',"Observaciones a proyectos de ordenanzas")
            ->setCellValue('J11',"Solicitudes de información");
        //TAMAÑOS
        $objPHPExcel->getActiveSheet()->getRowDimension(11)->setRowHeight(-1);
          




        //CONTENIDO
        /*
        for ($i = 12; $i <= $ndatos; $i++) {    
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i,'dssadasdasd sd sad sa asdasdasd sad sa das d asd as d sad as d as dasdasdsadsadasdsa');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i,'Documento');
            $url='https://stackoverflow.com/questions/23100636/phpexcel-how-to-set-a-url'; //añadir url del documentos
            $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getHyperlink()->setUrl($url);
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':'.'F'.$i)->applyFromArray($style);
            
        }    
        */
        $i = 12;
        foreach($ranking as $rank){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, $rank['title']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $rank['as']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $rank['au']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, $rank['de']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i, $rank['as'] + $rank['au']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i, $rank['or']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i, $rank['re']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$i, $rank['ob']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$i, $rank['so']);
            $i++;
        }
          // Estilos por columna
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getStyle('B11:F11')->applyFromArray($headerstyle);
          //$objPHPExcel->getActiveSheet()->getStyle('F12:F'.$ndatos)->applyFromArray($urlstyle);
          $objPHPExcel->getActiveSheet()->getStyle('J12:J'.$i)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('I12:I'.$i)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('H12:H'.$i)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('G12:G'.$i)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('F12:F'.$i)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('E12:E'.$i)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('C12:C'.$i)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('D12:D'.$i)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('B12:B'.$i)->applyFromArray($longstyle);
        
        
        //titulo
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B8:J8');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:J9');

        //Header styles
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B9')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B8')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('C11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('G11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('H11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('I11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('J11')->applyFromArray($headerstyle);

        $objPHPExcel->getActiveSheet()->setTitle('Simple');

       
        

        // CABEZERAS
        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        

        //ESCRITURA
        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
        ob_start();
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        //ENVIAR JSON CON ARCHIVO
        $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
        header('Content-type: application/json');
        die(json_encode($response));
        
    }
    } catch (Exception $e) {
        echo $e;
        die;
    }

}

// Listado de miembros del concejo 
add_action('wp_ajax_nopriv_oda_generate_csv_consolidado_miembros', 'oda_generate_csv_consolidado_miembros');
add_action('wp_ajax_oda_generate_csv_consolidado_miembros', 'oda_generate_csv_consolidado_miembros');
function oda_generate_csv_consolidado_miembros(){
    $miembros = get_miembro_ciudad($_GET['city']);
    //$id = array_column($ranking,'id');
    //$name = array_column($ranking,'apellidos');
    //array_multisort($columna, SORT_DESC,  $name, SORT_ASC, $ranking);
    /*
    foreach ($ranking as $rank){
        echo $rank[0];
    }
    */
    $csv_fields=array();
    $csv_fields[] = 'Asambleísta';              // B 
    $csv_fields[] = 'Género';                   // C
    $csv_fields[] = 'Cargo';                    // D
    $csv_fields[] = 'Organización Política';    // E
    $csv_fields[] = 'Suplente';                 // F
    // agregar bucle para las comisiones comienza en G
    $comisiones = get_comisiones_ciudad($_GET['city']);
    while($comisiones->have_posts()){ $comisiones->the_post();
        $csv_fields[] = get_the_title();
    }
    //var_dump($csv_fields);
    //die;
    
    
    $output_handle = @fopen( 'php://output', 'w' );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv;charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename=oda_ciudad_documento_cambiar_esto.csv');
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    // Insert header row
    fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
    
    // Parse results to csv format
    while ($miembros->have_posts()) { $miembros->the_post();
        $miembro_ID = get_the_ID();
        $genero = (get_post_meta(get_the_ID(), 'oda_miembro_gerero', true) == 1) ? 'Masculino' : 'Femenino';
        $cargo = get_post_meta(get_the_ID(), 'oda_miembro_cargo', true);
        switch($cargo){
            case '1': $cargo = 'Alcalde'; break;
            case '2': $cargo = 'Concejal Rural'; break;
            case '3': $cargo = 'Concejal Urbano'; break;
        }
        $partido_politico = get_partido_politico_miembro(get_the_ID());
        $suplentes = get_post_meta(get_the_ID(), 'oda_miembro_miembros_suplentes', true);
        $string = array();
        if($suplentes){
            foreach($suplentes as $suplente){
                $string[] = get_post($suplente)->post_title;
            }
        }
        $csv_fields=array();
        $csv_fields[] = get_the_title();
        $csv_fields[] = $genero;
        $csv_fields[] = $cargo;
        $csv_fields[] = $partido_politico['nombre'];
        $csv_fields[] = implode(' - ', $string);
        // Comisiones
        while($comisiones->have_posts()){ $comisiones->the_post();
            $comision_ID = get_the_ID();
            $aplica = 'No Aplica';
            $presidente = get_post_meta($comision_ID, 'oda_comision_composicion_presidente', true);
            $videpresidente = get_post_meta($comision_ID, 'oda_comision_composicion_vicepresidente', true);
            $demas = get_post_meta($comision_ID, 'oda_comision_composicion_miembros', true);
            //echo $member_id . ' - ' . $presidente . ' - ' .get_the_ID() .'<br />';
            if ($presidente == $miembro_ID){
                $aplica = 'Participa';
            }
            if ($videpresidente == $miembro_ID){
                $aplica = 'Participa';
            }
            if( $demas ){
                foreach($demas as $otro){
                    if($otro == $miembro_ID){
                        $aplica = 'Participa';
                    }
                }
            }
            $csv_fields[] = $aplica;
        }
        //$row = '';
        //$row = '';
        //$leadArray = (array) $Result; // Cast the Object to an array
        // Add row to file
        fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
        //var_dump( $csv_fields );
    }
    

    // Close output file stream
    fclose( $output_handle );
    die;
}

add_action('wp_ajax_nopriv_oda_generate_consolidado_miembros_xls', 'oda_generate_consolidado_miembros_xls');
add_action('wp_ajax_oda_generate_consolidado_miembros_xls', 'oda_generate_consolidado_miembros_xls');
function oda_generate_consolidado_miembros_xls(){
    $miembros = get_miembro_ciudad($_GET['city']);
    $city_name = get_the_title($_GET['city']);
    $city_logo = get_post_thumbnail_id($_GET['city'], 'post-thumbnail');
    $city_logo = wp_get_attachment_metadata($city_logo);
    // var_dump($miembros);
    // die;
    $city_logo = wp_upload_dir()['basedir'] . '/' . $city_logo['file'];

    try{
    if ( defined('CBXPHPSPREADSHEET_PLUGIN_NAME') && file_exists( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' ) ) {
        //Include PHPExcel
        require_once( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' );
        
        $city=$_GET['cityname'];
        $ndatos=30; //numero limite de datos
        $fecha=date('Y-m-d'); //ultima fecha de la resolucion
        //Crear instancia excel
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //Logos cabecera
        
        //Logo ciudad
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($city_logo);
        $drawing->setWidth(180); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(180);
        $drawing->setCoordinates('B1');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

        //Logo FCD
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(THEME_DIR . '/img/FCD-top.png');
        $drawing->setWidth(230); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(100);
        $drawing->setCoordinates('F3');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        $drawing->setOffsetX(200);                      
        $drawing->setOffsetY(10);
        //Styles
        $titulostyle = array(
            'font' => [
                         'name' => 'Arial',
                         'bold' => true,
                         //'italic' => false,
                         //'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_DOUBLE,
                         'strikethrough' => false,
                         /*'color' => [
                             'rgb' => '808080'
                         ]*/
                     ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $headerstyle=array('font' => [
            'name' => 'Arial',
            'bold' => true,
            'strikethrough' => false,
              ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        
        $longstyle=array(
            'font' => [
                'size' =>9
            ]
            ,
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $style=array(
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $participa=array(
            
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '008CF5AB',
                ],
                'endColor' => [
                    'argb' => '008CF5AB',
                ],
            ],

        );
        $ndatos=$miembros->post_count+11;
        
       
        //Columnas ancho ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('12');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('16');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('26');
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('16');
        
     

        //TITULO
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B8','Listado de miembros del Concejo de ' . $city_name)
            ->setCellValue('B9','Período '.PERIOD_BEGINS.' a '.$fecha)
            ->setCellValue('B11','Asambleísta')
            ->setCellValue('C11','Género')
            ->setCellValue('D11','Cargo')
            ->setCellValue('E11','Organización Política')
            ->setCellValue('F11','Suplente');

        $comisiones = get_comisiones_ciudad($_GET['city']);
        $col = 'G';
        while($comisiones->have_posts()){ $comisiones->the_post();
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($col . '11', get_the_title());
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth('18');
            $objPHPExcel->getActiveSheet()->getStyle($col.'11:'.$col.$ndatos)->applyFromArray($style);
            $col++;

        }
        //TAMAÑOS
        $objPHPExcel->getActiveSheet()->getRowDimension(11)->setRowHeight(-1);

        //CONTENIDO
        $i = 12;
        while($miembros->have_posts()){ $miembros->the_post();  
            $miembro_ID = get_the_ID();
            $genero = (get_post_meta(get_the_ID(), 'oda_miembro_gerero', true) == 1) ? 'Masculino' : 'Femenino';
            $cargo = get_post_meta(get_the_ID(), 'oda_miembro_cargo', true);
            switch($cargo){
                case '1': $cargo = 'Alcalde'; break;
                case '2': $cargo = 'Concejal Rural'; break;
                case '3': $cargo = 'Concejal Urbano'; break;
            }
            $partido_politico = get_partido_politico_miembro(get_the_ID());
            $suplentes = get_post_meta(get_the_ID(), 'oda_miembro_miembros_suplentes', true);
            $string = array();
            if($suplentes){
                foreach($suplentes as $suplente){
                    $string[] = get_post($suplente)->post_title;
                }
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, get_the_title());
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $genero);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $cargo);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, $partido_politico['nombre']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i, implode(', ', $string));
            $col = 'G';
            while($comisiones->have_posts()){ $comisiones->the_post();
                $comision_ID = get_the_ID();
                $aplica = 'No Aplica';
                $presidente = get_post_meta($comision_ID, 'oda_comision_composicion_presidente', true);
                $videpresidente = get_post_meta($comision_ID, 'oda_comision_composicion_vicepresidente', true);
                $demas = get_post_meta($comision_ID, 'oda_comision_composicion_miembros', true);
                //echo $member_id . ' - ' . $presidente . ' - ' .get_the_ID() .'<br />';
                if ($presidente == $miembro_ID){
                    $aplica = 'Participa';
                }
                if ($videpresidente == $miembro_ID){
                    $aplica = 'Participa';
                }
                if( $demas ){
                    foreach($demas as $otro){
                        if($otro == $miembro_ID){
                            $aplica = 'Participa';
                        }
                    }
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$i, $aplica);
                if ($aplica == 'Participa'){
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle($col.$i)->applyFromArray($participa);
                }
                $col++;
            }

            $i++;
        }    
        // Estilos por columna
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getStyle('B11:'.$col.'11')->applyFromArray($headerstyle);
        $objPHPExcel->getActiveSheet()->getStyle('F12:F'.$ndatos)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('E12:E'.$ndatos)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('C12:C'.$ndatos)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('D12:D'.$ndatos)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('B12:B'.$ndatos)->applyFromArray($longstyle);
        
        //titulo
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B8:F8');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:F9');

        //Header styles
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B9')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B8')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('C11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E11')->applyFromArray($headerstyle);
        
        $objPHPExcel->getActiveSheet()->setTitle('Simple');

       
        

        // CABEZERAS
        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        

        //ESCRITURA
        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
        ob_start();
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        //ENVIAR JSON CON ARCHIVO
        $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
        header('Content-type: application/json');
        die(json_encode($response));
        
    }
    } catch (Exception $e) {
        echo $e;
    }
    
}

// Listado de Ordenanzas del concejo
add_action('wp_ajax_nopriv_oda_generate_csv_listado_ordenanzas', 'oda_generate_csv_listado_ordenanzas');
add_action('wp_ajax_oda_generate_csv_listado_ordenanzas', 'oda_generate_csv_listado_ordenanzas');
function oda_generate_csv_listado_ordenanzas(){
    $documentos = get_ordenanzas_ciudad($_GET['city']);
    //$id = array_column($ranking,'id');
    //$name = array_column($ranking,'apellidos');
    //array_multisort($columna, SORT_DESC,  $name, SORT_ASC, $ranking);
    /*
    foreach ($ranking as $rank){
        echo $rank[0];
    }
    */
    $csv_fields=array();
    $csv_fields[] = 'Nombre del proyecto';      // B 
    $csv_fields[] = 'Proponente';               // C
    $csv_fields[] = 'Fecha de presentación';    // D
    $csv_fields[] = 'Comisión tratante';        // E
    $csv_fields[] = 'Estado';                   // F
    
    
    $output_handle = @fopen( 'php://output', 'w' );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv;charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename=oda_ciudad_documento_cambiar_esto.csv');
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    // Insert header row
    fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
    
    // Parse results to csv format
    while ($documentos->have_posts()) { $documentos->the_post();
        $proponentes = get_post_meta(get_the_ID(), 'oda_ordenanza_miembros', true);
        $fecha_presentacion = get_post_meta(get_the_ID(), 'oda_ordenanza_fecha', true);
        $comision_tratante = get_post(get_post_meta(get_the_ID(), 'oda_ordenanza_comision', true))->post_title;
        $estado = get_post_meta(get_the_ID(), 'oda_ordenanza_estado', true);
        $string = array();
        if($proponentes){
            foreach($proponentes as $proponente){
                $string[] = get_post($proponente)->post_title;
            }
        }
        switch ($estado){
            case '1': $estado = 'Iniciativa de proyecto de ordenanza'; break;
            case '2': $estado = 'Informe para primer debate'; break;
            case '3': $estado = 'Informe para segundo debate'; break;
            case '4': $estado = 'Aprobación Proyecto Final (Pleno)'; break;
            case '5': $estado = 'Sanción / observación (Alcalde)'; break;
            case '6': $estado = 'Promulgación y publicación'; break;
        }
        $csv_fields=array();
        $csv_fields[] = get_the_title();
        $csv_fields[] = implode(' - ', $string);
        $csv_fields[] = $fecha_presentacion;
        $csv_fields[] = $comision_tratante;
        $csv_fields[] = $estado;
        // Add row to file
        fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
        //var_dump( $csv_fields );
    }
    

    // Close output file stream
    fclose( $output_handle );
    die;
}

add_action('wp_ajax_nopriv_oda_generate_listado_ordenanzas_xls', 'oda_generate_listado_ordenanzas_xls');
add_action('wp_ajax_oda_generate_listado_ordenanzas_xls', 'oda_generate_listado_ordenanzas_xls');
function oda_generate_listado_ordenanzas_xls(){
    $documentos = get_ordenanzas_ciudad($_GET['city']);
    $city_name = get_the_title($_GET['city']);
    $city_logo = get_post_thumbnail_id($_GET['city'], 'post-thumbnail');
    $city_logo = wp_get_attachment_metadata($city_logo);
    // var_dump($miembros);
    // die;
    $city_logo = wp_upload_dir()['basedir'] . '/' . $city_logo['file'];
    try{
    if ( defined('CBXPHPSPREADSHEET_PLUGIN_NAME') && file_exists( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' ) ) {
        //Include PHPExcel
        require_once( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' );
        
        $city=$_GET['cityname'];
        $ndatos=30; //numero limite de datos

        //Crear instancia excel
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //Logos cabecera
        
        //Logo ciudad
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($city_logo);
        $drawing->setWidth(180); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(180);
        $drawing->setCoordinates('B1');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

        //Logo FCD
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(THEME_DIR . '/img/FCD-top.png');
        $drawing->setWidth(230); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(100);
        $drawing->setCoordinates('E3');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        $drawing->setOffsetX(155);                      
        $drawing->setOffsetY(10);
        //Styles
        $titulostyle = array(
            'font' => [
                         'name' => 'Arial',
                         'bold' => true,
                         //'italic' => false,
                         //'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_DOUBLE,
                         'strikethrough' => false,
                         /*'color' => [
                             'rgb' => '808080'
                         ]*/
                     ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $headerstyle=array('font' => [
            'name' => 'Arial',
            'bold' => true,
            'strikethrough' => false,
              ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $urlstyle=array(
            'font' => [
                'name' => 'Arial',
                'color' => [
                                'rgb' => '0000FF'
                            ]
                ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $longstyle=array(
            'font' => [
                'size' =>9
            ]
            ,
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $style=array(
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $ndatos=$documentos->post_count+11;
        
       
        //Columnas ancho ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('30');

        //TITULO
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B8','Listado de proyectos de ordenanzas del Concejo de ' . $city_name)
            ->setCellValue('B11','Nombre del proyecto')
            ->setCellValue('C11','Proponente')
            ->setCellValue('D11','Fecha de presentación')
            ->setCellValue('E11','Comisión tratante')
            ->setCellValue('F11','Estado');
        //TAMAÑOS
        $objPHPExcel->getActiveSheet()->getRowDimension(11)->setRowHeight(-1);

        //CONTENIDO
        /* for ($i = 12; $i <= $ndatos; $i++) {    
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i,'dssadasdasd sd sad sa asdasdasd sad sa das d asd as d sad as d as dasdasdsadsadasdsa');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i,'Documento');
            $url='https://stackoverflow.com/questions/23100636/phpexcel-how-to-set-a-url'; //añadir url del documentos
            $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getHyperlink()->setUrl($url);
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':'.'H'.$i)->applyFromArray($style);
            
        }   */
        $i = 12;
        while($documentos->have_posts()){ $documentos->the_post();
            $proponentes = get_post_meta(get_the_ID(), 'oda_ordenanza_miembros', true);
            $fecha_presentacion = get_post_meta(get_the_ID(), 'oda_ordenanza_fecha', true);
            $comision_tratante = get_post(get_post_meta(get_the_ID(), 'oda_ordenanza_comision', true))->post_title;
            $estado = get_post_meta(get_the_ID(), 'oda_ordenanza_estado', true);
            $string = array();
            if($proponentes){
                foreach($proponentes as $proponente){
                    $string[] = get_post($proponente)->post_title;
                }
            }
            switch ($estado){
                case '1': $estado = 'Iniciativa de proyecto de ordenanza'; break;
                case '2': $estado = 'Informe para primer debate'; break;
                case '3': $estado = 'Informe para segundo debate'; break;
                case '4': $estado = 'Aprobación Proyecto Final (Pleno)'; break;
                case '5': $estado = 'Sanción / observación (Alcalde)'; break;
                case '6': $estado = 'Promulgación y publicación'; break;
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, get_the_title());
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, implode(', ', $string));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $fecha_presentacion);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, $comision_tratante);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i, $estado);
            $i++;
        }
          // Estilos por columna
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getStyle('B11:H11')->applyFromArray($headerstyle);
          //$objPHPExcel->getActiveSheet()->getStyle('H12:H'.$ndatos)->applyFromArray($urlstyle);
          $objPHPExcel->getActiveSheet()->getStyle('F12:F'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('E12:E'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('C12:C'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('D12:D'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('B12:B'.$ndatos)->applyFromArray($style);
        
        
        //titulo
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B8:F8');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:F9');

        //Header styles
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B9')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B8')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('C11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F11')->applyFromArray($headerstyle);

        $objPHPExcel->getActiveSheet()->setTitle('Listado de Ordenanzas');

       
        

        // CABEZERAS
        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        

        //ESCRITURA
        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
        ob_start();
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        //ENVIAR JSON CON ARCHIVO
        $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
        header('Content-type: application/json');
        die(json_encode($response));
        
    }
    } catch (Exception $e) {
        echo $e;
    }
    

    
}

// Listado de Ordenanzas del concejo
add_action('wp_ajax_nopriv_oda_generate_csv_listado_resoliciones', 'oda_generate_csv_listado_resoliciones');
add_action('wp_ajax_oda_generate_csv_listado_resoliciones', 'oda_generate_csv_listado_resoliciones');
function oda_generate_csv_listado_resoliciones(){
    $documentos = get_resoluciones_ciudad($_GET['city']);
    //$id = array_column($ranking,'id');
    //$name = array_column($ranking,'apellidos');
    //array_multisort($columna, SORT_DESC,  $name, SORT_ASC, $ranking);
    /*
    foreach ($ranking as $rank){
        echo $rank[0];
    }
    */
    $csv_fields=array();
    $csv_fields[] = 'Nombre del proyecto';      // B 
    $csv_fields[] = 'Proponente';               // C
    $csv_fields[] = 'Fecha de presentación';    // D
    $csv_fields[] = 'Estado';                   // E
    
    
    $output_handle = @fopen( 'php://output', 'w' );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv;charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename=oda_ciudad_documento_cambiar_esto.csv');
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    // Insert header row
    fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
    
    // Parse results to csv format
    while ($documentos->have_posts()) { $documentos->the_post();
        $proponentes = get_post_meta(get_the_ID(), 'oda_resolucion_miembros', true);
        $fecha_presentacion = get_post_meta(get_the_ID(), 'oda_resolucion_fecha', true);
        $estado = get_post_meta(get_the_ID(), 'oda_resolucion_estado', true);
        $string = array();
        if($proponentes){
            foreach($proponentes as $proponente){
                $string[] = get_post($proponente)->post_title;
            }
        }
        switch ($estado){
            case '1': $estado = 'Proyecto de resolución'; break;
            case '2': $estado = 'Debate y aprobación'; break;
            case '3': $estado = 'Notificación y publicación'; break;
        }
        $csv_fields=array();
        $csv_fields[] = get_the_title();
        $csv_fields[] = implode(' - ', $string);
        $csv_fields[] = $fecha_presentacion;
        $csv_fields[] = $estado;
        // Add row to file
        fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
        //var_dump( $csv_fields );
    }
    

    // Close output file stream
    fclose( $output_handle );
    die;
}

add_action('wp_ajax_nopriv_oda_generate_listado_resoluciones_xls', 'oda_generate_listado_resoluciones_xls');
add_action('wp_ajax_oda_generate_listado_resoluciones_xls', 'oda_generate_listado_resoluciones_xls');
function oda_generate_listado_resoluciones_xls(){
    $documentos = get_resoluciones_ciudad($_GET['city']);
    $city_name = get_the_title($_GET['city']);
    $city_logo = get_post_thumbnail_id($_GET['city'], 'post-thumbnail');
    $city_logo = wp_get_attachment_metadata($city_logo);
    // var_dump($miembros);
    // die;
    $city_logo = wp_upload_dir()['basedir'] . '/' . $city_logo['file'];
    try{
    if ( defined('CBXPHPSPREADSHEET_PLUGIN_NAME') && file_exists( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' ) ) {
        //Include PHPExcel
        require_once( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' );
        
        $city=$_GET['cityname'];
        $ndatos=30; //numero limite de datos

        //Crear instancia excel
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //Logos cabecera
        
        //Logo ciudad
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($city_logo);
        $drawing->setWidth(180); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(180);
        $drawing->setCoordinates('B1');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

        //Logo FCD
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(THEME_DIR . '/img/FCD-top.png');
        $drawing->setWidth(230); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(100);
        $drawing->setCoordinates('D3');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        $drawing->setOffsetX(155);                      
        $drawing->setOffsetY(10);
        //Styles
        $titulostyle = array(
            'font' => [
                         'name' => 'Arial',
                         'bold' => true,
                         //'italic' => false,
                         //'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_DOUBLE,
                         'strikethrough' => false,
                         /*'color' => [
                             'rgb' => '808080'
                         ]*/
                     ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $headerstyle=array('font' => [
            'name' => 'Arial',
            'bold' => true,
            'strikethrough' => false,
              ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $urlstyle=array(
            'font' => [
                'name' => 'Arial',
                'color' => [
                                'rgb' => '0000FF'
                            ]
                ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $longstyle=array(
            'font' => [
                'size' =>9
            ]
            ,
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $style=array(
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $ndatos=$documentos->post_count+11;
        
       
        //Columnas ancho ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('30');

        //TITULO
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B8','Listado de proyectos de resolución del Concejo de ' . $city_name)
            ->setCellValue('B11','Nombre del proyecto')
            ->setCellValue('C11','Proponente')
            ->setCellValue('D11','Fecha de presentación')
            ->setCellValue('E11','Estado');
        //TAMAÑOS
        $objPHPExcel->getActiveSheet()->getRowDimension(11)->setRowHeight(-1);

        //CONTENIDO
        /* for ($i = 12; $i <= $ndatos; $i++) {    
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i,'dssadasdasd sd sad sa asdasdasd sad sa das d asd as d sad as d as dasdasdsadsadasdsa');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i,'Documento');
            $url='https://stackoverflow.com/questions/23100636/phpexcel-how-to-set-a-url'; //añadir url del documentos
            $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getHyperlink()->setUrl($url);
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':'.'H'.$i)->applyFromArray($style);
            
        }   */
        $i = 12;
        while($documentos->have_posts()){ $documentos->the_post();
            $proponentes = get_post_meta(get_the_ID(), 'oda_ordenanza_miembros', true);
            $fecha_presentacion = get_post_meta(get_the_ID(), 'oda_ordenanza_fecha', true);
            $estado = get_post_meta(get_the_ID(), 'oda_ordenanza_estado', true);
            $string = array();
            if($proponentes){
                foreach($proponentes as $proponente){
                    $string[] = get_post($proponente)->post_title;
                }
            }
            switch ($estado){
                case '1': $estado = 'Proyecto de resolución'; break;
                case '2': $estado = 'Debate y aprobación'; break;
                case '3': $estado = 'Notificación y publicación'; break;
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, get_the_title());
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, implode(', ', $string));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $fecha_presentacion);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, $estado);
            $i++;
        }
          // Estilos por columna
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getStyle('B11:H11')->applyFromArray($headerstyle);
          //$objPHPExcel->getActiveSheet()->getStyle('H12:H'.$ndatos)->applyFromArray($urlstyle);
          $objPHPExcel->getActiveSheet()->getStyle('E12:E'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('C12:C'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('D12:D'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('B12:B'.$ndatos)->applyFromArray($style);
        
        
        //titulo
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B8:E8');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:E9');

        //Header styles
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B9')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B8')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('C11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E11')->applyFromArray($headerstyle);

        $objPHPExcel->getActiveSheet()->setTitle('Listado de Resoluciones');

       
        

        // CABEZERAS
        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        

        //ESCRITURA
        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
        ob_start();
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        //ENVIAR JSON CON ARCHIVO
        $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
        header('Content-type: application/json');
        die(json_encode($response));
        
    }
    } catch (Exception $e) {
        echo $e;
    }
    

    
}

// Listado de Ordenanzas del concejo
add_action('wp_ajax_nopriv_oda_generate_csv_observaciones', 'oda_generate_csv_observaciones');
add_action('wp_ajax_oda_generate_csv_observaciones', 'oda_generate_csv_observaciones');
function oda_generate_csv_observaciones(){
    $documentos = get_observaciones_ciudad($_GET['city']);
    $csv_fields=array();
    $csv_fields[] = 'Proyecto de ordenanza observado';      // B 
    $csv_fields[] = 'Proponente';                           // C
    $csv_fields[] = 'Fecha de presentación';                // D
    $csv_fields[] = 'Enlace a documento';                   // E
    
    
    $output_handle = @fopen( 'php://output', 'w' );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv;charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename=oda_ciudad_documento_cambiar_esto.csv');
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    // Insert header row
    fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
    
    // Parse results to csv format
    while ($documentos->have_posts()) { $documentos->the_post();
        $ordenanza = get_post_meta(get_the_ID(), 'oda_observacion_ordenanza', true);
        $proponente = get_post_meta(get_the_ID(), 'oda_observacion_miembro', true);
        $fecha_presentacion = get_post_meta(get_the_ID(), 'oda_observacion_fecha', true);
        $enlace = get_post_meta(get_the_ID(), 'oda_observacion_documento', true);

        $csv_fields=array();
        $csv_fields[] = get_post($ordenanza)->post_title;
        $csv_fields[] = get_post($proponente)->post_title;
        $csv_fields[] = $fecha_presentacion;
        $csv_fields[] = $enlace;
        // Add row to file
        fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
        //var_dump( $csv_fields );
    }
    

    // Close output file stream
    fclose( $output_handle );
    die;
}

add_action('wp_ajax_nopriv_oda_generate_observaciones_xls', 'oda_generate_observaciones_xls');
add_action('wp_ajax_oda_generate_observaciones_xls', 'oda_generate_observaciones_xls');
function oda_generate_observaciones_xls(){
    $documentos = get_observaciones_ciudad($_GET['city']);
    $city_name = get_the_title($_GET['city']);
    $city_logo = get_post_thumbnail_id($_GET['city'], 'post-thumbnail');
    $city_logo = wp_get_attachment_metadata($city_logo);
    // var_dump($miembros);
    // die;
    $city_logo = wp_upload_dir()['basedir'] . '/' . $city_logo['file'];
    try{
    if ( defined('CBXPHPSPREADSHEET_PLUGIN_NAME') && file_exists( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' ) ) {
        //Include PHPExcel
        require_once( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' );
        
        $city=$_GET['cityname'];
        $ndatos=30; //numero limite de datos

        //Crear instancia excel
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //Logos cabecera
        
        //Logo ciudad
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($city_logo);
        $drawing->setWidth(180); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(180);
        $drawing->setCoordinates('B1');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

        //Logo FCD
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(THEME_DIR . '/img/FCD-top.png');
        $drawing->setWidth(230); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(100);
        $drawing->setCoordinates('D3');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        $drawing->setOffsetX(155);                      
        $drawing->setOffsetY(10);
        //Styles
        $titulostyle = array(
            'font' => [
                         'name' => 'Arial',
                         'bold' => true,
                         //'italic' => false,
                         //'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_DOUBLE,
                         'strikethrough' => false,
                         /*'color' => [
                             'rgb' => '808080'
                         ]*/
                     ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $headerstyle=array('font' => [
            'name' => 'Arial',
            'bold' => true,
            'strikethrough' => false,
              ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $urlstyle=array(
            'font' => [
                'name' => 'Arial',
                'color' => [
                                'rgb' => '0000FF'
                            ]
                ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $longstyle=array(
            'font' => [
                'size' =>9
            ]
            ,
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $style=array(
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $ndatos=$documentos->post_count+11;
        
       
        //Columnas ancho ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('30');

        //TITULO
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B8','Observaciones a proyectos de ordenanzas Concejo de ' . $city_name)
            ->setCellValue('B11','Proyecto de ordenanza observado')
            ->setCellValue('C11','Proponente')
            ->setCellValue('D11','Fecha de presentación')
            ->setCellValue('E11','Enlace a documento');
        //TAMAÑOS
        $objPHPExcel->getActiveSheet()->getRowDimension(11)->setRowHeight(-1);

        //CONTENIDO
        /* for ($i = 12; $i <= $ndatos; $i++) {    
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i,'dssadasdasd sd sad sa asdasdasd sad sa das d asd as d sad as d as dasdasdsadsadasdsa');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i,'Documento');
            $url='https://stackoverflow.com/questions/23100636/phpexcel-how-to-set-a-url'; //añadir url del documentos
            $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getHyperlink()->setUrl($url);
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':'.'H'.$i)->applyFromArray($style);
            
        }   */
        $i = 12;
        while($documentos->have_posts()){ $documentos->the_post();
            $ordenanza = get_post_meta(get_the_ID(), 'oda_observacion_ordenanza', true);
            $proponente = get_post_meta(get_the_ID(), 'oda_observacion_miembro', true);
            $fecha_presentacion = get_post_meta(get_the_ID(), 'oda_observacion_fecha', true);
            $enlace = get_post_meta(get_the_ID(), 'oda_observacion_documento', true);

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, get_post($ordenanza)->post_title);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, get_post($proponente)->post_title);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $fecha_presentacion);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, 'Documento');
            $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getHyperlink()->setUrl($enlace);

            $i++;
        }
          // Estilos por columna
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getStyle('B11:H11')->applyFromArray($headerstyle);
          //$objPHPExcel->getActiveSheet()->getStyle('H12:H'.$ndatos)->applyFromArray($urlstyle);
          $objPHPExcel->getActiveSheet()->getStyle('E12:E'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('C12:C'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('D12:D'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('B12:B'.$ndatos)->applyFromArray($style);
        
        
        //titulo
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B8:E8');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:E9');

        //Header styles
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B9')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B8')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('C11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E11')->applyFromArray($headerstyle);

        $objPHPExcel->getActiveSheet()->setTitle('Listado de Observaciones');

       
        

        // CABEZERAS
        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        

        //ESCRITURA
        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
        ob_start();
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        //ENVIAR JSON CON ARCHIVO
        $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
        header('Content-type: application/json');
        die(json_encode($response));
        
    }
    } catch (Exception $e) {
        echo $e;
    }
    

    
}

add_action('wp_ajax_nopriv_oda_generate_solicitudes_xls', 'oda_generate_solicitudes_xls');
add_action('wp_ajax_oda_generate_solicitudes_xls', 'oda_generate_solicitudes_xls');
function oda_generate_solicitudes_xls(){
    $documentos = get_solicitudes_informacion($_GET['city']);
    $city_name = get_the_title($_GET['city']);
    $city_logo = get_post_thumbnail_id($_GET['city'], 'post-thumbnail');
    $city_logo = wp_get_attachment_metadata($city_logo);
    // var_dump($miembros);
    // die;
    $city_logo = wp_upload_dir()['basedir'] . '/' . $city_logo['file'];

    try{
    if ( defined('CBXPHPSPREADSHEET_PLUGIN_NAME') && file_exists( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' ) ) {
        //Include PHPExcel
        require_once( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' );
        
        $city=$_GET['cityname'];
        $ndatos=30; //numero limite de datos

        //Crear instancia excel
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //Logos cabecera
        
        //Logo ciudad
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($city_logo);
        $drawing->setWidth(180); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(180);
        $drawing->setCoordinates('B1');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

        //Logo FCD
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(THEME_DIR . '/img/FCD-top.png');
        $drawing->setWidth(230); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(100);
        $drawing->setCoordinates('G3');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        $drawing->setOffsetX(155);                      
        $drawing->setOffsetY(10);
        //Styles
        $titulostyle = array(
            'font' => [
                         'name' => 'Arial',
                         'bold' => true,
                         //'italic' => false,
                         //'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_DOUBLE,
                         'strikethrough' => false,
                         /*'color' => [
                             'rgb' => '808080'
                         ]*/
                     ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $headerstyle=array('font' => [
            'name' => 'Arial',
            'bold' => true,
            'strikethrough' => false,
              ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $urlstyle=array(
            'font' => [
                'name' => 'Arial',
                'color' => [
                                'rgb' => '0000FF'
                            ]
                ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $longstyle=array(
            'font' => [
                'size' =>9
            ]
            ,
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $style=array(
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $ndatos=$ndatos+12;
        
       
        //Columnas ancho ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('20');

        //TITULO
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B8','Solicitudes de información presentadas por los miembros del Concejo de ' . $city_name)
            ->setCellValue('B11','Solicitante')
            ->setCellValue('C11','Información solicitada a')
            ->setCellValue('D11','Institución')
            ->setCellValue('E11','Cargo')
            ->setCellValue('F11',"Fecha de solicitud")
            ->setCellValue('G11',"Fecha de respuesta")
            ->setCellValue('H11',"Enlace a documento");
        //TAMAÑOS
        $objPHPExcel->getActiveSheet()->getRowDimension(11)->setRowHeight(-1);
          




        //CONTENIDO
        $i = 12;
        while($documentos->have_posts()){ $documentos->the_post();
            $ordenanza = get_post_meta(get_the_ID(), 'oda_observacion_ordenanza', true);
            $proponente = get_post_meta(get_the_ID(), 'oda_observacion_miembro', true);
            $fecha_presentacion = get_post_meta(get_the_ID(), 'oda_observacion_fecha', true);
            $enlace = get_post_meta(get_the_ID(), 'oda_observacion_documento', true);

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, get_post($ordenanza)->post_title);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, get_post($proponente)->post_title);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $fecha_presentacion);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, 'Documento');
            $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getHyperlink()->setUrl($enlace);

            $i++;
        }  
          // Estilos por columna
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getStyle('B11:H11')->applyFromArray($headerstyle);
          $objPHPExcel->getActiveSheet()->getStyle('H12:H'.$ndatos)->applyFromArray($urlstyle);
          $objPHPExcel->getActiveSheet()->getStyle('G12:G'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('F12:F'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('E12:E'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('C12:C'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('D12:D'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('B12:B'.$ndatos)->applyFromArray($style);
        
        
        //titulo
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B8:H8');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:H9');

        //Header styles
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B9')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B8')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('C11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F11')->applyFromArray($headerstyle);

        $objPHPExcel->getActiveSheet()->setTitle('Simple');

       
        

        // CABEZERAS
        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        

        //ESCRITURA
        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
        ob_start();
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        //ENVIAR JSON CON ARCHIVO
        $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
        header('Content-type: application/json');
        die(json_encode($response));
        
    }
    } catch (Exception $e) {
        echo $e;
    }
    

    
}


// Listado de votos
add_action('wp_ajax_nopriv_oda_generate_csv_votacion', 'oda_generate_csv_votacion');
add_action('wp_ajax_oda_generate_csv_votacion', 'oda_generate_csv_votacion');
function oda_generate_csv_votacion(){
    
    /**
     * Obtener los votos completos
     */    
    $registros = get_ranking_mocion($_GET['city'], $_GET['mocion']);
    //var_dump($registros);
    $csv_fields=array();
    $csv_fields[] = 'Miembro';  // B  
    $csv_fields[] = 'Voto';     // C
    
    
    $output_handle = @fopen( 'php://output', 'w' );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv;charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename=oda_ciudad_documento_cambiar_esto.csv');
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    // Insert header row
    fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );


    foreach($registros['votos'] as $voto){
        $csv_fields=array();
        $csv_fields[] = $voto['title'];        
        $csv_fields[] = $voto['status'];
        fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
    }
    foreach($registros['suplentes'] as $voto){
        $csv_fields=array();
        $csv_fields[] = $voto['title'] . ' (en reemplazo de '.$voto['suplencia'].')';        
        $csv_fields[] = $voto['status'];
        fputcsv( $output_handle, mb_convert_encoding($csv_fields,'UTF-16LE', 'UTF-8') );
    }    
    
    // Close output file stream
    fclose( $output_handle );
    die;
}
add_action('wp_ajax_nopriv_oda_generate_votacion_xls', 'oda_generate_votacion_xls');
add_action('wp_ajax_oda_generate_votacion_xls', 'oda_generate_votacion_xls');
function oda_generate_votacion_xls(){
    /**
     * Obtener los votos completos
     */    
    $registros = get_ranking_mocion($_GET['city'], $_GET['mocion']);
    
    $city_name = get_the_title($_GET['city']);
    $city_logo = get_post_thumbnail_id($_GET['city'], 'post-thumbnail');
    $city_logo = wp_get_attachment_metadata($city_logo);
    // var_dump($miembros);
    // die;
    $city_logo = wp_upload_dir()['basedir'] . '/' . $city_logo['file'];

    try{
    if ( defined('CBXPHPSPREADSHEET_PLUGIN_NAME') && file_exists( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' ) ) {
        //Include PHPExcel
        require_once( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' );
        
        $city=$_GET['cityname'];
        $ndatos=30; //numero limite de datos

        //Crear instancia excel
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //Logos cabecera
        
        //Logo ciudad
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($city_logo);
        $drawing->setWidth(180); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(180);
        $drawing->setCoordinates('B1');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

        //Logo FCD
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(THEME_DIR . '/img/FCD-top.png');
        $drawing->setWidth(230); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(100);
        $drawing->setCoordinates('C3');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        $drawing->setOffsetX(155);                      
        $drawing->setOffsetY(10);
        //Styles
        $titulostyle = array(
            'font' => [
                         'name' => 'Arial',
                         'bold' => true,
                         //'italic' => false,
                         //'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_DOUBLE,
                         'strikethrough' => false,
                         /*'color' => [
                             'rgb' => '808080'
                         ]*/
                     ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $headerstyle=array('font' => [
            'name' => 'Arial',
            'bold' => true,
            'strikethrough' => false,
              ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $urlstyle=array(
            'font' => [
                'name' => 'Arial',
                'color' => [
                                'rgb' => '0000FF'
                            ]
                ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $longstyle=array(
            'font' => [
                'size' =>9
            ]
            ,
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $style=array(
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $ndatos=$ndatos+12;
        
       
        //Columnas ancho ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('30');

        //TITULO
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B8','Sesión: ' . $registros['metas']['nombre_sesion'])
            ->setCellValue('B9', $registros['metas']['nombre_documento'])
            ->setCellValue('B10', 'Fecha: ' . $registros['metas']['fecha_mocion'])
            ->setCellValue('B12', 'Resumen')
            ->setCellValue('B13', 'Presentes')
            ->setCellValue('B14', 'Ausentes')
            ->setCellValue('B15', 'Sí')
            ->setCellValue('B16', 'No')
            ->setCellValue('B17', 'Blancos')
            ->setCellValue('B18', 'Abstenciones')
            ->setCellValue('B19', 'Excusas')
            ->setCellValue('C13', $registros['metas']['total'])
            ->setCellValue('C14', $registros['metas']['au'])
            ->setCellValue('C15', $registros['metas']['si'])
            ->setCellValue('C16', $registros['metas']['no'])
            ->setCellValue('C17', $registros['metas']['bl'])
            ->setCellValue('C18', $registros['metas']['ab'])
            ->setCellValue('C19', $registros['metas']['ex'])
            ->setCellValue('B22',"Miembro")
            ->setCellValue('C22',"Voto");
        //TAMAÑOS
        $objPHPExcel->getActiveSheet()->getRowDimension(11)->setRowHeight(-1);

        //CONTENIDO
        $i = 23;
        foreach($registros['votos'] as $voto){

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, $voto['title']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $voto['status']);

            $i++;
        }
        if ($registros['suplentes']){
            $celda_suplente = $i;
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$i.':'.'C'.$i);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, 'Suplentes');
            $i++;
            //CONTENIDO
            foreach($registros['suplentes'] as $voto){

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, $voto['title'] . ' (en reemplazo de '.$voto['suplencia'].')');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $voto['status']);

                $i++;
            }  
        }
        // Estilos por columna
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getStyle('B22:C22')->applyFromArray($headerstyle);
        $objPHPExcel->getActiveSheet()->getStyle('B23:C'.$i)->applyFromArray($style);
        /*
        $objPHPExcel->getActiveSheet()->getStyle('B23:H'.$i)->applyFromArray($urlstyle);
          $objPHPExcel->getActiveSheet()->getStyle('G12:G'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('F12:F'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('E12:E'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('C12:C'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('D12:D'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('B12:B'.$ndatos)->applyFromArray($style);
          */
        
        
        //titulo
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B8:D8');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:D9');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B10:D10');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B12:C12');

        //Header styles
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B8')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B9')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B10')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.$celda_suplente.':C'.$celda_suplente)->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B12:C12')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B13:C19')->applyFromArray($style);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B13:B19')->applyFromArray($headerstyle);


        $objPHPExcel->getActiveSheet()->setTitle('Votos Mocion');

       
        

        // CABEZERAS
        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        

        //ESCRITURA
        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
        ob_start();
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        //ENVIAR JSON CON ARCHIVO
        $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
        header('Content-type: application/json');
        die(json_encode($response));
        
    }
    } catch (Exception $e) {
        echo $e;
    }
    

    
}

function oda_generate_comparencencias_xls(){

    try{
    if ( defined('CBXPHPSPREADSHEET_PLUGIN_NAME') && file_exists( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' ) ) {
        //Include PHPExcel
        require_once( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' );
        
        $city=$_GET['cityname'];
        $ndatos=30; //numero limite de datos
        $fecha="2020-09-12"; //ultima fecha de la resolucion
        //Crear instancia excel
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //Logos cabecera
        
        //Logo ciudad
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(__DIR__ . '/' . $city . '.png');
        $drawing->setWidth(180); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(180);
        $drawing->setCoordinates('B1');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

        //Logo FCD
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(__DIR__ . '/FCD-top.png');
        $drawing->setWidth(230); 
        $drawing->setResizeProportional(false);
        $drawing->setHeight(100);
        $drawing->setCoordinates('H3');
        $drawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        $drawing->setOffsetX(200);                      
        $drawing->setOffsetY(10);
        //Styles
        $titulostyle = array(
            'font' => [
                         'name' => 'Arial',
                         'bold' => true,
                         //'italic' => false,
                         //'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_DOUBLE,
                         'strikethrough' => false,
                         /*'color' => [
                             'rgb' => '808080'
                         ]*/
                     ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        $headerstyle=array('font' => [
            'name' => 'Arial',
            'bold' => true,
            'strikethrough' => false,
              ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
            )
        );
        
        $longstyle=array(
            'font' => [
                'size' =>9
            ]
            ,
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $style=array(
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'left'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'right'=> [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,     
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    
                ]
            ],
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'     => TRUE
                )
        );
        $ndatos=$ndatos+12;
        
       
        //Columnas ancho ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('45');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('35');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('35');
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('25');
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('25');
     

        //TITULO
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B8','Listado de solicitudes de comparecencias del Concejo Metropolitano de Quito')
            ->setCellValue('B9','Período 2019-05-14 a '.$fecha)
            ->setCellValue('B11','Compareciente')
            ->setCellValue('C11','Cargo')
            ->setCellValue('D11','Institución')
            ->setCellValue('E11','Peticionario')
            ->setCellValue('F11','Fecha de solicitud')
            ->setCellValue('G11','Fecha de compareciencia')
            ->setCellValue('H11','Número de sesión')
            ->setCellValue('I11','Tipo de sesión');;
        //TAMAÑOS
        $objPHPExcel->getActiveSheet()->getRowDimension(11)->setRowHeight(-1);
          




        //CONTENIDO
        for ($i = 12; $i <= $ndatos; $i++) {    
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i,'dssadasdasd sd sad sa asdasdasd sad sa das d asd as d sad as d as dasdasdsadsadasdsa');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$i,'Documento');
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':'.'I'.$i)->applyFromArray($style);
            
        }    
          // Estilos por columna
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getStyle('B11:I11')->applyFromArray($headerstyle);
          $objPHPExcel->getActiveSheet()->getStyle('I12:I'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('H12:H'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('G12:G'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('F12:F'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('E12:E'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('C12:C'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('D12:D'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('B12:B'.$ndatos)->applyFromArray($style);
        
        
        //titulo
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B8:I8');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:I9');

        //Header styles
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B9')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B8')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('C11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('G11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('H11')->applyFromArray($headerstyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('I11')->applyFromArray($headerstyle);
        
        $objPHPExcel->getActiveSheet()->setTitle('Simple');

       
        

        // CABEZERAS
        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        

        //ESCRITURA
        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
        ob_start();
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        //ENVIAR JSON CON ARCHIVO
        $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
        header('Content-type: application/json');
        die(json_encode($response));
        
    }
    } catch (Exception $e) {
        echo $e;
    }
    

    
}
