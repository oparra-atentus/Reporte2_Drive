<html> 
  <head>
    <title>Problema</title>
  </head>
  <body>
  <?php
    function enviar_url(){
      $conexion=pg_connect("host=172.16.5.124 port=5432 dbname=central2010 user=reporte_web password=.112233.")or die("Problemas con la conexión");
      $registros = pg_query($conexion, "SELECT 
      oc.objetivo_id,o.nombre AS nombre_objetivo,unnest((xpath('//email/@periodo', oc.xml_configuracion))::text[]) as periodo,
      unnest((xpath('//email[@id=1]/destinatario/@nombre', oc.xml_configuracion))::text[]) as nombre,
      unnest((xpath('//email[@id=1]/destinatario/@direccion', oc.xml_configuracion))::text[]) as email,
      unnest((xpath('//email[@id=1]/reporte/generado_via/param[6]/@valor', oc.xml_configuracion))::text[]) as usuario_id,
      unnest((xpath('//email[@id=1]/reporte/generado_via/param[1]/@valor', oc.xml_configuracion))::text[]) as objetivo,
      unnest((xpath('//email[@id=1]/reporte/generado_via/param[2]/@valor', oc.xml_configuracion))::text[]) as tipo,
      unnest((xpath('//email[@id=1]/reporte/generado_via/param[3]/@valor', oc.xml_configuracion))::text[]) as popup,
      unnest((xpath('//email[@id=1]/reporte/generado_via/param[4]/@valor', oc.xml_configuracion))::text[]) as es_especial,
      unnest((xpath('//email[@id=1]/reporte/generado_via/param[5]/@valor', oc.xml_configuracion))::text[]) as tiene_svg
      FROM objetivo_config AS oc, objetivo o
      WHERE es_ultima_config=true
      AND oc.objetivo_id = o.objetivo_id
      AND oc.objetivo_id IN (
      SELECT o.objetivo_id
      FROM objetivo o, cliente_mapa_cliente_objetivo co
     WHERE o.objetivo_id = co.objetivo_id
      AND o.servicio_id IN (800)
      AND (o.fecha_expiracion IS NULL OR o.fecha_expiracion >= now())) ;") or
     die("Problemas en el select:" . pg_error($conexion));
    $token="043644a0-9615-4402-881d-bfb75f0a70cd";
       echo'<table border="1">';
        echo '<thead>';
          echo '<tr>';
            echo '<th>Url</th>';
            echo '<th> correo</th>';
            echo '</tr>';
            echo  '</thead>';
            echo  '<tbody>';
        while ($reg = pg_fetch_assoc($registros)){ 
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
          echo '<tr>';
          echo '<td>';
          echo '<a class="enlace"  href="http://localhost/acceso_especial.php?objetivo_id='.$reg['objetivo_id'].'&type='.$reg['tipo'].'&popup='.$reg['popup'].'&es_especial='.$reg['es_especial'].'&tiene_svg='.$reg['es_especial'].'&usuario_id='.$reg['usuario_id'].'&fecha_inicio='.$fecha_inicio.'&fecha_termino='.$fecha_termino.'&token='.$token.'" target="_blank">';
          echo 'http://localhost/acceso_especial.php?objetivo_id='.$reg['objetivo_id'].'&type='.$reg['tipo'].'&popup='.$reg['popup'].'&es_especial='.$reg['es_especial'].'&tiene_svg='.$reg['es_especial'].'&usuario_id='.$reg['usuario_id'].'&fecha_inicio='.$fecha_inicio.'&fecha_termino='.$fecha_termino.'&token='.$token.'';
          echo '</a>';
          echo '</td>';
          echo '<td>eegegege</td>';
          echo '</tr>';
        }
      } 
      echo '</tbody>';
      echo '</table>';         
      pg_close($conexion);
    }
    enviar_url();
    ?>
    <script type="text/javascript">
      links=new Array();
      window.onload= function() {
        var enlace = document.getElementsByClassName('enlace');
        for (var i=0; i<enlace.length;i++){
          links.push(enlace[i].href); 
        } 
        for (var i=0; i<links.length;i++){
               window.open(links[i]);
        }
      }
    </script>
  </body>
</html>