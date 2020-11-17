<?
include("../config/include.php");
$tipo_pdf_especial = false;
ob_clean();
/* VARIABLES NECESARIAS */
if (!isset($_REQUEST['token'])) {
    header("HTTP/1.0 400 error de parametros");
    echo "token no existe";
    exit();
}
if (!isset($_REQUEST['objetivo_id'])) {
    header("HTTP/1.0 400 error de parametros");
    echo "objetivo_id no existe";
    exit();
}

if (!isset($_REQUEST['type'])) {
    header("HTTP/1.0 400 error de parametros");
    echo "type no existe";
    exit();
}

$current_usuario_id = Utiles::busca_usuario($_REQUEST['objetivo_id']);
if (!$current_usuario_id) {
    header("HTTP/1.0 400 error de parametros");
    echo "no existe usuario para el objetivo_id";
    exit();
}
if (isset($_REQUEST['usuario_id'])) {
   unset($current_usuario_id);
   $current_usuario_id = $_REQUEST['usuario_id'];

}
$usr = new Usuario($current_usuario_id);
$usr->__Usuario();
$objetivo1 = $usr->getObjetivo($_REQUEST['objetivo_id'], REP_DATOS_ESPECIALES);
if ($objetivo1 == null) {
    header("HTTP/1.0 400 error de parametros");
    echo "objetivo_id invalido";
    exit();
}
$objetivo = new ConfigEspecial($_REQUEST['objetivo_id']);
$type = $objetivo->getTypeById($_REQUEST['type']);
$sub_objetivos = $objetivo->getSubobjetivos();
if ($type == null) {
    header("HTTP/1.0 400 error de parametros");
    echo "type invalido";
    exit();
}
//$subobjetivo_xml variable que almacena si se envia un subobjetivo
if (isset($_REQUEST['subobjetivo_id'])) {
    $subobjetivo_xml = $objetivo->getSubobjetivo($_REQUEST["subobjetivo_id"]);
    if ($subobjetivo_xml == null) {
        header("HTTP/1.0 400 error de parametros");
        echo "subobjetivo_id invalido";
        exit();
    }   
}
//
if (!valida_token($_REQUEST['token'])) {
    header("HTTP/1.0 400 error de parametros");
    echo "token invalido";
    exit();
}
/* VARIABLES OPCIONALES */
if (isset($_REQUEST['fecha_inicio']) and isset($_REQUEST['fecha_termino'])) {
    $timestamp = new Timestamp($_REQUEST['fecha_inicio'], $_REQUEST['fecha_termino']);
    $timestamp->tipo_periodo = "especial";
} else {
    $timestamp = new Timestamp(date("Y-m-d 00:00:00"), date("Y-m-d 00:00:00"));
}
if (isset($_REQUEST['horario_id'])) {
    $horario = $usr->getHorario($_REQUEST['horario_id']);
    if ($horario == null) {
        header("HTTP/1.0 400 error de parametros");
        echo "horario_id invalido";
        exit();
    }
    $horario_id = $horario->horario_id;
} else {
    $horario_id = 0;
}
/* SI EL REPORTE ESPECIAL MUESTRA UN PDF */
if ($type->content == 'pdf' && !isset($_REQUEST["acceso_pdftohtml"])) {
    $html = REP_DOMINIO . "acceso_especial.php?acceso_pdftohtml=1&es_pdf=true";
    foreach ($_REQUEST as $nombre => $valor) {
        $html.="&" . $nombre . "=" . urlencode($valor);
    }
    
    $pdf= "tmp/file_" . md5(time()) . ".pdf";
    /* NOMBRE PDF : OBJETIVO */
    $dwn_objetivo = $objetivo->nombre . "_";
    $dwn_periodo = $timestamp->toString();
    /* NOMBRE PDF : REPORTE */
    $sactual = Seccion::getSeccionPorDefecto(34);
    $secciones = $sactual->getSeccionesNivel(1);
    $dwn_reporte = "";
    foreach ($secciones as $seccion) {
        if ($seccion->es_parent) {
            $dwn_reporte = $seccion->nombre . "_";
        }
    }
    /* NOMBRE COMPLETO PDF */
    $dwn_completo = $dwn_reporte . $dwn_objetivo . $dwn_periodo . ".pdf";
    $dwn_completo = str_replace(array(" - ", " ", "/", ","), array("_", "-", "-", "-"), $dwn_completo);
    exec(REP_PATH_HTMLTOPDF . " --dpi 180 -T 30mm -B 18mm -L 2mm -R 2mm " .
            "--header-spacing 10 --header-html " . REP_PATH_TEMPLATES . "header_pdf.html --javascript-delay 2500 " .
            "--footer-html " . REP_PATH_TEMPLATES . "footer_pdf.html " .
            escapeshellcmd($html) . " '" . escapeshellcmd($pdf) . "'", $result);
    ob_clean();
    header("Cache-Control:  maxage=1");
    header("Pragma: public");
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=" . $dwn_completo);
    $file = fopen($pdf, "r");
    fpassthru($file);
    //unlink($pdf);
    $fecha_inicio= substr($_REQUEST['fecha_inicio'],0,strlen($_REQUEST['fecha_inicio'])-9);
    $fecha_termino= substr($_REQUEST['fecha_termino'],0,strlen($_REQUEST['fecha_termino'])-9);
    $periodo=strtotime($fecha_termino) - strtotime($fecha_inicio);
    if ($periodo==2678400 || $periodo==2592000 || $periodo==2419200 || $periodo==2505600){
    $periodo='mensual'; 
    }   
    if ($periodo==86400){
    $periodo='diario'; 
    }  
    if ($periodo==604800){
    $periodo='semanal'; 
    }  
    $nom_objetivo = $objetivo->nombre . "_";
    $desc_periodo = $timestamp->toString();
    $nom_objetivo = str_replace(array(" - ", " ", "/", ","), array("_", "-", "-", "-"), $nom_objetivo);
    $desc_periodo = str_replace(array(" - ", " ", "/", ","), array("_", "-", "-", "-"), $desc_periodo);
    $dwn_reporte = str_replace(array(" - ", " ", "/", ","), array("_", "-", "-", "-"), $dwn_reporte);
    $cadena_email=$objetivo->__direccion;
    $contador_direccion=0;
    $largo_email=count($objetivo->__direccion);
    $lenguage = 'es_ES.UTF-8';
    putenv("LANG=$lenguage");
    setlocale(LC_ALL, $lenguage);
    $organizador='';
    $ruta_directorio='';
    $mes=strftime("%B-%Y");
    $semana="Semana-N-".strftime("%V")."-".strftime("%d-%B-%Y");
    $dia=strftime("%d-%B-%Y");
    for ($i=0; $i<$largo_email;$i++){
        chdir('../../../../home/oparra/gdrive/Reportes_Especiales_Betav4/');
        if ($periodo=='mensual' && strftime('%d')=='17'){
            $ruta_directorio = ''.$cadena_email[$contador_direccion].'/Reportes_Especiales/Reporte_'.$periodo;
            if (!file_exists($ruta_directorio)) {
                mkdir($ruta_directorio, 0777, true);
                chmod($ruta_directorio, 0777);
            }
            chdir($ruta_directorio);
            $organizador=$mes; 
            mkdir($organizador, 0777, true);
            chmod($organizador, 0777);

        }
        else if ($periodo=='semanal' && strftime('%A')=='martes'){
            $ruta_directorio = ''.$cadena_email[$contador_direccion].'/Reportes_Especiales/Reporte_'.$periodo;
            if (!file_exists($ruta_directorio)) {
                mkdir($ruta_directorio, 0777, true);
                chmod($ruta_directorio, 0777);
            }
            chdir($ruta_directorio);
            $organizador=$semana; 
            mkdir($organizador, 0777, true);
            chmod($organizador, 0777);

        } 
        else if ($periodo=='diario'){
            $ruta_directorio = ''.$cadena_email[$contador_direccion].'/Reportes_Especiales/Reporte_'.$periodo;
            if (!file_exists($ruta_directorio)) {
                mkdir($ruta_directorio, 0777, true);
                chmod($ruta_directorio, 0777);
            } 
            chdir($ruta_directorio);
            $organizador=$dia; 
            mkdir($organizador, 0777, true);
            chmod($organizador, 0777);   
        }
        chdir('../../../');
        $pdf_directorio="".$ruta_directorio."/".$organizador."/".$dwn_reporte.$nom_objetivo.$desc_periodo.".pdf";
        if (strftime('%d')=='17' || strftime('%d')==17 && $periodo=='mensual'  || $periodo=='semanal' && strftime('%A')=='martes' || $periodo=='diario'){
            exec(REP_PATH_HTMLTOPDF . " --dpi 180 -T 30mm -B 18mm -L 2mm -R 2mm " .
            "--header-spacing 10 --header-html " . REP_PATH_TEMPLATES . "header_pdf.html --javascript-delay 2500 " .
         "--footer-html " . REP_PATH_TEMPLATES . "footer_pdf.html " .
            escapeshellcmd($html) . " '" . $pdf_directorio . "'", $result);
            ob_clean();
            header("Cache-Control:  maxage=1");
            header("Pragma: public");
            header("Content-type: application/pdf");
            header("Content-Disposition: attachment; filename=" . $dwn_completo);
        }
        $file_directorio = fopen($pdf_directorio, "r");    
        fpassthru($file_directorio);
        $contador_direccion=$contador_direccion+1;
    }
    //unlink($pdf);
}
/* SI EL REPORTE ESPECIAL MUESTRA UN INFORME Y ES ENVIADO POR MAIL*/ 
elseif ($type->informe_id != null && $type->content == 'pdf' && isset($_REQUEST["es_especial"])) {
    $tipo_pdf_especial = true;
    $T = & new Template_PHPLIB(REP_PATH_TEMPLATES);
    $T->setFile('tpl_contenido', 'reporte_pdf.tpl');
    $T->setBlock('tpl_contenido', 'LISTA_GRAFICOS', 'lista_graficos');
    $T->setBlock('tpl_contenido', 'LISTA_ITEMS', 'lista_items');
    $T->setBlock('tpl_contenido', 'CONTENIDO', 'contenido');


    $T->setVar('contenido', '');
    $T->setVar('__fecha_inicio', $timestamp->getFechaInicio("d/m/Y H:i:s"));
    if ($timestamp->getFechaTermino("H:i:s") != "00:00:00") {
        $T->setVar('__fecha_termino', $timestamp->getFechaTermino("d/m/Y H:i:s"));
    } else {
        $T->setVar('__fecha_termino', date("d/m/Y 24:00:00", (strtotime($timestamp->getFechaTermino("Y-m-d H:i:s")) - 1)));
    }
    $reporte = new Reporte($type->informe_id);
    $monitor = 0;
    
    foreach ($sub_objetivos as $subobjetivo) {
        $linea = 1;

        // $subobjetivo = $objetivo->getSubobjetivo($valor);
        $paises = Constantes::getPaises();
        $T->setVar('__pais_email', $paises[$usr->pais_id]["soporte_email"]);
        $T->setVar('__pais_telefono', $paises[$usr->pais_id]["soporte_telefono"]);
        $T->setVar('__reporte_titulo', $objetivo->nombre);
        $T->setVar('__parent_objetivo_id', $objetivo->objetivo_id);
        
        if (isset($subobjetivo) and $subobjetivo != null) {

            $T->setVar('__objetivo_id', $subobjetivo->objetivo_id);
            $T->setVar('__objetivo_nombre', $subobjetivo->nombre);
            

//                  $T->setVar('__objetivo_servicio', $subobjetivo->getServicio()->nombre);
        } 
        else {

            $T->setVar('__objetivo_id', $objetivo->objetivo_id);
            $T->setVar('__objetivo_nombre', '');
        }
        $T->setVar('lista_items', '');



        $T->setVar('lista_graficos', '');
        foreach ($reporte->getReporteItems() as $item) {
            /* BORRAR VARIABLE CUANDO SE ELIMINE FLASH */
            $T->setVar('lista_graficos', '');

            $clase = $item->getContenido($_REQUEST["tiene_svg"], $_REQUEST["tiene_flash"]);
            $clase->tipo = "html";
            $clase->objetivo_id = ($subobjetivo != null) ? $subobjetivo->objetivo_id : $objetivo->objetivo_id;
            $clase->horario_id = ($horario != null) ? $horario->horario_id : 0;
            $clase->timestamp = $timestamp;
            $clase->subgrafico_id = 2;
            $clase->extra = array("imprimir" => 1, "item_orden" => $linea, "parent_objetivo_id" => $objetivo->objetivo_id, "reporte_id" => $type->informe_id, "monitor" => "$monitor");
            /*cuando es descarga por pdf (soluciona temas de visualizacion)*/
            if($_REQUEST["es_pdf"]==true){
                $es_pdf =$_REQUEST["es_pdf"];
                $clase->generarResultado($es_pdf);
            }
            else{
                $clase->generarResultado();
            }

            $T->setVar('__item_id', $item->item_id);
            $T->setVar('__item_titulo', $item->nombre);
            $T->setVar('__item_orden', $linea);
            $T->setVar('__item_descripcion', ($objetivo->display_description == "true") ? $item->descripcion : "");
            $T->setVar('__item_contenido', $clase->resultado);
            $T->parse('lista_items', 'LISTA_ITEMS', true);
            $linea++;
        }
        $monitor = $monitor + 10;
        $T->parse('contenido', 'CONTENIDO', true);
       
    }
    $T->setVar('__path_dojo', REP_PATH_DOJO);
    $T->setVar('__path_jquery_ui', REP_PATH_JQUERY_UI);
    $T->setVar('__path_js', REP_PATH_JS);
    $T->pparse('out', 'tpl_contenido');
}
/* SI EL REPORTE ESPECIAL MUESTRA UN INFORME  PDF  sin graficos*/ 
elseif ($type->informe_id != null && $tipo_pdf_especial == false) {

$reporte = new Reporte($type->informe_id);
    $T = & new Template_PHPLIB(REP_PATH_TEMPLATES);
    $T->setFile('tpl_contenido', 'reporte_mail.tpl');
    $T->setBlock('tpl_contenido', 'LISTA_ITEMS', 'lista_items');
    $T->setVar('__fecha_inicio', $timestamp->getFechaInicio("d/m/Y"));
    if ($timestamp->getFechaTermino("H:i:s") == "00:00:00") {
        $T->setVar('__fecha_termino', date("d/m/Y 24:00:00", (strtotime($timestamp->getFechaTermino("Y-m-d H:i:s")) - 1)));
    } else {
        $T->setVar('__fecha_termino', $timestamp->getFechaTermino("d/m/Y H:i:s"));
    }
    $T->setVar('__reporte_titulo', $objetivo1->nombre);

    if (isset($_REQUEST["subobjetivo_id"])) {

        $T->setVar('__objetivo_id', $subobjetivo_xml->objetivo_id);
        $T->setVar('__objetivo_nombre', $subobjetivo_xml->nombre);
//      $T->setVar('__objetivo_servicio', $subobjetivo_xml->getServicio()->nombre);
    } else {

        $T->setVar('__objetivo_id', $objetivo1->objetivo_id);
        $T->setVar('__objetivo_nombre', '');
    }
    /* ITEMS DEL REPORTE */
    $linea = 1;
    $T->setVar('items_reporte', '');
    foreach ($reporte->getReporteItems() as $item) {

        $clase = $item->getContenido(1, 1);

        $clase->tipo = "html";
        $clase->objetivo_id = ($subobjetivo_xml != null) ? $subobjetivo_xml->objetivo_id : $objetivo1->objetivo_id;
        $clase->horario_id = $horario_id;
        $clase->timestamp = $timestamp;
        $clase->subgrafico_id = 2;
        $clase->extra = array("item_orden" => $linea);
        if($_REQUEST["es_pdf"] == true){
            $es_pdf = $_REQUEST["es_pdf"];
            $clase->generarResultado($es_pdf);
        }
        else{
            $clase->generarResultado();
        }
        $T->setVar('__item_id', $item->item_id);
        $T->setVar('__item_titulo', $item->nombre);
        $T->setVar('__item_orden', $linea);
        $T->setVar('__item_contenido', $clase->resultado);
        $T->parse('lista_items', 'LISTA_ITEMS', true);
        $linea++;
    }
    echo $T->parse('out', 'tpl_contenido');
}
/* SI EL REPORTE ESPECIAL EJECUTA UN METODO DE UNA CLASE */ 
elseif ($type->class != null and $type->method != null) {
    $nombre_archivo = str_replace(" ", "_", $objetivo->nombre) . "_" . $timestamp->getFechaInicio("Y-m-d") . "_" . $timestamp->getFechaTermino("Y-m-d");
    ob_clean();

    header('Content-Transfer-Encoding: none');
    header("Content-Type: " . $header_type[$type->content] . ";");
    header('Content-Disposition: attachment; filename="' . $nombre_archivo . '.' . $type->content . '"');

    $contenido = $type->getContenido();
    if (isset($_REQUEST["subobjetivo_id"])) {
        $contenido->objetivo_id = $subobjetivo_xml->objetivo_id;
    } else {
        $contenido->objetivo_id = $objetivo1->objetivo_id;  
    }
    $contenido->horario_id = $horario_id;
    $contenido->timestamp = $timestamp;
    $contenido->generarResultado();
}

/**
 * Funcion que valida el token entregado por el administrador
 * para tener acceso a los datos.
 * 
 * @param string $token
 * @return boolean
 */
function valida_token($token) {
    global $mdb2;
    global $log;
    global $type;

    $sql = "SELECT token_id FROM public.token " .
            "WHERE token ='" . $token . "' AND extract(epoch from age(now(),fecha))<60000000";
    $res = & $mdb2->query($sql);
    if (MDB2::isError($res)) {
        return false;
    }

    //SI ENCUENTRA EL TOKEN LO ELIMINA Y PROCEDE
    if ($row = $res->fetchRow()) {
        if ($type->content != 'pdf' or isset($_REQUEST["acceso_pdftohtml"])) {
            $token_id = $row["token_id"];
            $sql2 = "DELETE FROM public.token WHERE token_id  = " . $token_id;
            $res2 = & $mdb2->query($sql2);
            if (MDB2::isError($res2)) {
                return false;
            }
        }
        return true;
    } else {
        return false;
    }
}

$mdb2->disconnect();
?>