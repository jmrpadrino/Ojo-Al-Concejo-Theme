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
    if ( 'proyectos-de-ordenanza' == $wp_query->query_vars['oda_template'] ) {
        $template = THEME_DIR . '/templates/proyectos-de-ordenanza-template.php';
    }
    if ( 'proyectos-de-resolucion' == $wp_query->query_vars['oda_template'] ) {
        $template = THEME_DIR . '/templates/proyectos-de-resolucion-template.php';
    }
    if ( 'observaciones-a-proyectos-de-ordenanza' == $wp_query->query_vars['oda_template'] ) {
        $template = THEME_DIR . '/templates/observaciones-a-proyectos-de-ordenanza-template.php';
    }
    if ( 'solicitudes-de-informacion' == $wp_query->query_vars['oda_template'] ) {
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
add_action('wp_ajax_nopriv_oda_generate_csv', 'oda_generate_csv');
add_action('wp_ajax_oda_generate_csv', 'oda_generate_csv');
function oda_generate_csv(){
    $ranking = get_ranking_votaciones($_GET['city']);
    $id = array_column($ranking,'id');
    $name = array_column($ranking,'apellidos');
    array_multisort($columna, SORT_DESC,  $name, SORT_ASC, $ranking);
    /*
    foreach ($ranking as $rank){
        echo $rank[0];
    }
    */
    $csv_fields=array();
    $csv_fields[] = 'Miembro';
    $csv_fields[] = 'Organización Política';
    $csv_fields[] = 'Asistencia a Votaciones';
    $csv_fields[] = 'Votaciones posibles';
    $csv_fields[] = 'Porcentaje de Asistencia';
/*
    $output_handle = @fopen( 'php://output', 'w' );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv' );
    header( 'Content-Disposition: attachment; filename=oda_ciudad_documento_cambiar_esto.csv');
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    // Insert header row
    fputcsv( $output_handle, $csv_fields );
    */

    
    // Parse results to csv format
    foreach ($ranking as $rank) {
        var_dump( $rank );
        $row = '';
        $row = '';
        //$leadArray = (array) $Result; // Cast the Object to an array
        // Add row to file
        //fputcsv( $output_handle, $leadArray );
    }
    

    // Close output file stream
    //fclose( $output_handle );
    die;
}

add_action('wp_ajax_nopriv_oda_generate_xls', 'oda_generate_xls');
add_action('wp_ajax_oda_generate_xls', 'oda_generate_xls');
function oda_generate_ordenanzas_xls(){

    try{
    if ( defined('CBXPHPSPREADSHEET_PLUGIN_NAME') && file_exists( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' ) ) {
        //Include PHPExcel
        require_once( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' );
        
        $city=$_GET['cityname'];
        $ndatos=30; //numero limite de datos
        $fecha="2020-09-12"; //ultima fecha de la ordenza
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
        $ndatos=$ndatos+12;
        
       
        //Columnas ancho ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('55');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('25');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('35');
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('20');

        //TITULO
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B8','Listado de observaciones a proyectos de ordenanzas en el Concejo Metropolitano de Quito')
            ->setCellValue('B9','Período 2019-05-14 a '.$fecha)
            ->setCellValue('B11','Proyecto de ordenanza observado')
            ->setCellValue('C11','Proponente')
            ->setCellValue('D11','Fecha de presentación')
            ->setCellValue('E11','Comisión tratante')
            ->setCellValue('F11',"Enlace a documento");
        //TAMAÑOS
        $objPHPExcel->getActiveSheet()->getRowDimension(11)->setRowHeight(-1);
          




        //CONTENIDO
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
          // Estilos por columna
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getStyle('B11:F11')->applyFromArray($headerstyle);
          $objPHPExcel->getActiveSheet()->getStyle('F12:F'.$ndatos)->applyFromArray($urlstyle);
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

function oda_generate_resoluciones_xls(){

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
        $drawing->setCoordinates('D3');
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('55');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('25');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('35');
     

        //TITULO
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B8','Listado de proyectos de resoluciones del Concejo Metropolitano de Quito')
            ->setCellValue('B9','Período 2019-05-14 a '.$fecha)
            ->setCellValue('B11','Nombre del proyecto')
            ->setCellValue('C11','Proponente')
            ->setCellValue('D11','Fecha de presentación')
            ->setCellValue('E11','Estado');
        //TAMAÑOS
        $objPHPExcel->getActiveSheet()->getRowDimension(11)->setRowHeight(-1);
          




        //CONTENIDO
        for ($i = 12; $i <= $ndatos; $i++) {    
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i,'dssadasdasd sd sad sa asdasdasd sad sa das d asd as d sad as d as dasdasdsadsadasdsa');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i,'Documento');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i,'Documento');
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':'.'E'.$i)->applyFromArray($style);
            
        }    
          // Estilos por columna
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getStyle('B11:E11')->applyFromArray($headerstyle);
          $objPHPExcel->getActiveSheet()->getStyle('E12:E'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('C12:C'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('D12:D'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('B12:B'.$ndatos)->applyFromArray($longstyle);
        
        
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

function oda_generate_informacion_xls(){

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
            ->setCellValue('B8','Solicitudes de información presentadas por los miembros del Concejo')
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
        for ($i = 12; $i <= $ndatos; $i++) {    
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

function oda_generate_consolidado_xls(){

    try{
    if ( defined('CBXPHPSPREADSHEET_PLUGIN_NAME') && file_exists( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' ) ) {
        //Include PHPExcel
        require_once( CBXPHPSPREADSHEET_ROOT_PATH . 'lib/vendor/autoload.php' );
        
        $city=$_GET['cityname'];
        $ndatos=30; //numero limite de datos
        $fecha="2020-09-12";
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
        $titulostyle2 = array(
            'font' => [
                         'name' => 'Arial',
                          'strikethrough' => false,
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('55');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth('20');
        //TITULO
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B7','Listado de miembros del Concejo Metropolitano de Quito')
            ->setCellValue('B8','Periodo 2019-05-14 a '.$fecha)
            ->setCellValue('B9','Durante este periodo se realizaron 328 sesiones y 1070 votaciones')
            ->setCellValue('B11','Votaciones asistidas')
            ->setCellValue('C11','Votaciones ausentes')
            ->setCellValue('D11','Participación del suplente en votaciones')
            ->setCellValue('E11','Votaciones consideradas')
            ->setCellValue('F11','Votaciones consideradas')
            ->setCellValue('G11','Proyectos de ordenanzas')
            ->setCellValue('H11',"Proyectos de resoluciones")
            ->setCellValue('I11',"Observaciones a proyectos de ordenanzas")
            ->setCellValue('J11',"Solicitudes de información");
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
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$i,'Documento');
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':'.'J'.$i)->applyFromArray($style);
            
        }    
          // Estilos por columna
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getStyle('B11:J11')->applyFromArray($headerstyle);
          $objPHPExcel->getActiveSheet()->getStyle('J12:J'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('I12:I'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('H12:H'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('G12:G'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('F12:F'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('E12:E'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('C12:C'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('D12:D'.$ndatos)->applyFromArray($style);
          $objPHPExcel->getActiveSheet()->getStyle('B12:B'.$ndatos)->applyFromArray($style);
        
        
        //titulo
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B7:J7');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B8:J8');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:J9');

        //Header styles
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B9')->applyFromArray($titulostyle2);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B8')->applyFromArray($titulostyle);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B7')->applyFromArray($titulostyle);
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
    }
    

    
}

function oda_generate_xls(){

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
