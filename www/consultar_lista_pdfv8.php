<html> 
  <head>
    <title>Problema</title>
  </head>
  <body>
    <?php
    include("../config/include.php");
    function enviar_url(){
      global $mdb2;
      $sql= "SELECT 
        oc.objetivo_id,o.nombre AS nombre_objetivo,unnest((xpath('//email/@periodo', oc.xml_configuracion))::text[]) as periodo,
       unnest((xpath('//email[@id=1]/reporte/generado_via/param[6]/@valor', oc.xml_configuracion))::text[]) as usuario_id,
      unnest((xpath('//email[@id=1]/reporte/generado_via/param[1]/@valor', oc.xml_configuracion))::text[]) as objetivo,
      unnest((xpath('//email[@id=1]/reporte/generado_via/param[2]/@valor', oc.xml_configuracion))::text[]) as tipo,
      unnest((xpath('//email[@id=1]/reporte/generado_via/param[3]/@valor', oc.xml_configuracion))::text[]) as popup,
      unnest((xpath('//email[@id=1]/reporte/generado_via/param[4]/@valor', oc.xml_configuracion))::text[]) as es_especial,
      unnest((xpath('//email[@id=1]/reporte/generado_via/param[5]/@valor', oc.xml_configuracion))::text[]) as tiene_svg
      FROM objetivo_config AS oc, objetivo o
      WHERE es_ultima_config=true 
      AND oc.objetivo_id = o.objetivo_id 
      AND oc.objetivo_id  IN (
      SELECT o.objetivo_id
      FROM objetivo o, cliente_mapa_cliente_objetivo co
      WHERE o.objetivo_id = co.objetivo_id 
      AND o.servicio_id IN (800)
      AND (o.fecha_expiracion IS NULL OR o.fecha_expiracion >= now()))"; 
      $res = & $mdb2->query($sql);
      if (MDB2::isError($res)) {
        return false;
      }
      $filas = $res->numRows();
      $lista_token=array();
      for ($i=0; $i<$filas;$i++){
        $nuevo_token=bin2hex(openssl_random_pseudo_bytes(4))."-".bin2hex(openssl_random_pseudo_bytes(2))."-".bin2hex(openssl_random_pseudo_bytes(2))."-".bin2hex(openssl_random_pseudo_bytes(2))."-".bin2hex(openssl_random_pseudo_bytes(6)."'");
        array_push($lista_token,$nuevo_token);
      }
      $contador_token=0;
      for($i=0 ;$i<count($lista_token);$i++){
        $sql_insert = "INSERT INTO public.token(token, fecha)".
         "VALUES ('".
        pg_escape_string($lista_token[$contador_token])."', ".
        pg_escape_string(current_timestamp).");";
        $contador_token++;
      //print($sql_insert);
        $res_insert =& $mdb2->query($sql_insert);
      }
      if (MDB2::isError($res_insert)) {
        $this->setSyslog($sql_insert, $res_insert->userinfo);
        exit();
      }
      $cont_token=-1;
      echo'<table border="1">';
        echo '<thead>';
          echo '<tr>';
            echo '<th>Url</th>';
            echo '<th>Registros</th>';
          echo '</tr>';
        echo  '</thead>';
        echo  '<tbody>';
        $periodo1='';
        $periodo2='';
        $periodo3='diario';
        $lenguage = 'es_ES.UTF-8';
        putenv("LANG=$lenguage");
        setlocale(LC_ALL, $lenguage);
        
        if(strftime('%d')=='17' || strftime('%d')==17 ){
          $periodo1='mensual';
        }
        if(strftime('%A')=='martes'){
          $periodo2='semanal';
        }
      
      while ($reg = $res->fetchRow()) {
       if($reg["periodo"]==$periodo1 || $reg["periodo"]==$periodo2 || $reg["periodo"]==$periodo3 ){ 
        if($reg["usuario_id"]!=""){
          $pre_termino=date('Y-m-j H:i');
          $fecha_termino=substr($pre_termino,0,10)."%2000:".substr($pre_termino, 11);
          if($reg['periodo']=="mensual"){
            $pre_inicio = strtotime ( '-1 month' , strtotime ( $pre_termino ));
            $pre_inicio = date ( 'Y\-m\-j H:i' , $pre_inicio );
            $fecha_inicio=substr($pre_inicio,0,10)."%2000:".substr($pre_inicio, 11);
          }elseif($reg['periodo']=="semanal"){
            $pre_inicio = strtotime ( '-7 day' , strtotime ( $pre_termino ));
            $pre_inicio = date ( 'Y\-m\-j H:i' , $pre_inicio );
            $fecha_inicio=substr($pre_inicio,0,10)."%2000:".substr($pre_inicio, 11);
          }elseif($reg['periodo']=="diario"){
            $pre_inicio = strtotime ( '-1 day' , strtotime ( $pre_termino ));
            $pre_inicio = date ( 'Y\-m\-j H:i' , $pre_inicio );
            $fecha_inicio=substr($pre_inicio,0,10)."%2000:".substr($pre_inicio, 11);
          } 
          $cont_token=$cont_token+1;
          echo '<tr>';
            echo '<td>';
              echo '<a class="enlace"  href="http://localhost/acceso_especial.php?objetivo_id='.$reg['objetivo_id'].'&type='.$reg['tipo'].'&popup='.$reg['popup'].'&es_especial='.$reg['es_especial'].'&tiene_svg='.$reg['es_especial'].'&usuario_id='.$reg['usuario_id'].'&fecha_inicio='.$fecha_inicio.'&fecha_termino='.$fecha_termino.'&token='.$lista_token[$cont_token].'" target="_blank">';
              echo 'http://localhost/acceso_especial.php?objetivo_id='.$reg['objetivo_id'].'&type='.$reg['tipo'].'&popup='.$reg['popup'].'&es_especial='.$reg['es_especial'].'&tiene_svg='.$reg['es_especial'].'&usuario_id='.$reg['usuario_id'].'&fecha_inicio='.$fecha_inicio.'&fecha_termino='.$fecha_termino.'&token='.$lista_token[$cont_token].'';
              echo '</a>';
            echo '</td>';
          echo '</tr>';
        }
      }
      } 
        echo '</tbody>';
      echo '</table>';    
      $mdb2->disconnect();
    }
    enviar_url();
    ?>
    <script type="text/javascript">
    solicitudes=new Array();
    window.onload= function() {
      var enlace = document.getElementsByClassName('enlace');
      for (var i=0; i<enlace.length;i++){
        solicitudes.push(enlace[i].href);
      } 
      for (var i=0; i<solicitudes.length;i++){
        window.open(solicitudes[i]);
        
        
      }
    }
    </script>
  </body>
</html>

