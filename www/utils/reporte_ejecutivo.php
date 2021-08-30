
<?
include('../../config/common.php');
$usser = $_REQUEST['user'];
$key= '07374CEAC2EAEF2EB3441BA0C433F9834E6CF6E2';
$objetivo_id = $_REQUEST['objetivo'];
$t64 = $_REQUEST['t'];
$token64 = $_REQUEST['token'];
$ressponse = $_REQUEST['respoVari'];
$w = $_REQUEST['w'];
$url=REP_API_HOST3."/reporte/online/indicadores/getUptimeObjetive/?user=".$usser."&key=".$key."&objetivo=".$objetivo_id."&t=".$t64."&token=".$token64."".$ressponse."&w=".$w;
$arrContextOptions = array("ssl" => array("verify_peer" => false, "verify_peer_name" => false));
$json = file_get_contents($url, false, stream_context_create($arrContextOptions));
echo $json;
?>