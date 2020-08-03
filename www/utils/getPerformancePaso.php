<?
include('../../config/common.php');

if (isset($_REQUEST['function'])) {
	$function = $_REQUEST['function'];
	$function();
}

function performancePaso(){
	$objetivo_id = $_REQUEST['objetivo_id'];
	$fecha_inicio = $_REQUEST['fecha_inicio'];
	$fecha_final = $_REQUEST['fecha_final'];
	$usuario = $_REQUEST['usuario'];
	$horario = $_REQUEST['horario'];
	$hash = $_REQUEST['clave_md5'];
	$listJson = $_REQUEST['listJson'];
	
	$url = REP_API_HOST3."/reporte/especial/performanceGlobal/?objetivo=".$objetivo_id."&fecha_inicio=".$fecha_inicio."&fecha_final=".$fecha_final."&usuario=".$usuario."&horarioId=".$horario."&token=".$hash."&format=json&listJson=".$listJson;

	$url = str_replace(' ', "%20", $url);
	$arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),);
	$json = file_get_contents($url, false, stream_context_create($arrContextOptions));
	echo $json;
}

function performancePdf($objetivo, $fecha_begin, $fecha_termino, $usuario_id, $horario_id, $clave){
	$objetivo_id = $objetivo;
	$fecha_inicio = $fecha_begin;
	$fecha_final = $fecha_termino;
	$usuario = $usuario_id;
	$horario = $horario_id;
	$hash = $clave;
	
	$url = REP_API_HOST3."/reporte/especial/performanceGlobal/?objetivo=".$objetivo_id."&fecha_inicio=".$fecha_inicio."&fecha_final=".$fecha_final."&usuario=".$usuario."&horarioId=".$horario."&token=".$hash."&format=json";

	$url = str_replace(' ', "%20", $url);
	$arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),);
	$json = file_get_contents($url, false, stream_context_create($arrContextOptions));
	return $json;
}
?>