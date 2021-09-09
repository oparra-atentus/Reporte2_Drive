<html>
  <head>
    <title>Reportes Especiales por Drive</title>
    <script type="text/javascript" src="tools/jquery/js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="js/funciones_drive.js"></script>
  </head>
  <body>
  <?php
  include("../config/include.php");
  /*Creado por:Oscar Parra Flores*/
  function enviarURL(){
    $consulta=new Clase_drive();
    $peticiones=$consulta->consultarDestinatarios();
    $min_actual=strftime("%H:%M:%S");
    $lenguage = 'es_CL.UTF-8';
    putenv("LANG=$lenguage");
    setlocale(LC_ALL, $lenguage);
    echo "Hora actual: ".$min_actual;
    echo '<span id="cuerpo_solicitud">';
      $get_token=$consulta->insertarToken();
      
      $contador_reg=0;
      $solicitados=0;
      $pendientes=0;
      echo "Presente dia: ".strftime('%A');
      /* Se obtienen los datos de todos los reportes según periodo de solicitud a través del PA obtenerdestinatarios
      Creado por:Oscar Parra Flores*/
      foreach ($peticiones as $reg) {
        $cont_token=-1;
        $registro=explode(",",$reg['obtenerdestinatarios']);
        $periodo = substr($registro[0],1);

        if($periodo=='semanal' && strftime('%A')=='viernes' || $periodo=='mensual' && strftime('%d')=='10' || $periodo=='diario'){
          $nombre_objetivo=$registro[1];
          $nombre_objetivo = str_replace(" ", "-", $nombre_objetivo);
          $objetivo_id=$registro[2];
          $type=$registro[3];
          $popup=$registro[4];
          $es_especial=$registro[5];
          $tiene_svg=$registro[6];
          echo "Presente periodo: ".$periodo;
          $usuario_id = substr($registro[7], 0, -1);
          if($periodo=='mensual' || $periodo=='semanal' || $periodo=='diario' ){
            $pre_termino=date('Y-m-j H:i');
            $fecha_termino=substr($pre_termino,0,10)."%2000:".substr($pre_termino, 11);
            if($periodo=="mensual"){
              $pre_inicio = strtotime ( '-1 month' , strtotime ( $pre_termino ));
              $pre_inicio = date ( 'Y\-m\-j H:i' , $pre_inicio );
              $fecha_inicio=substr($pre_inicio,0,10)."%2000:".substr($pre_inicio, 11);
            }elseif($periodo=="semanal"){
              $pre_inicio = strtotime ( '-7 day' , strtotime ( $pre_termino ));
              $pre_inicio = date ( 'Y\-m\-j H:i' , $pre_inicio );
              $fecha_inicio=substr($pre_inicio,0,10)."%2000:".substr($pre_inicio, 11);
            }elseif($periodo=="diario"){
              $pre_inicio = strtotime ( '-1 day' , strtotime ( $pre_termino ));
              $pre_inicio = date ( 'Y\-m\-j H:i' , $pre_inicio );
              $fecha_inicio=substr($pre_inicio,0,10)."%2000:".substr($pre_inicio, 11);
            }
            $min_comienzo="16:27:00";
            /* La variable tiempo_transcurrido representan los minutos que han pasado desde la ejecución del archivo,en cada minuto que transcurre de descarga un nuevo reporte, en el minuto 1 se solicita el primer reporte, en el minuto 2 el segundo reporte y así sucesivamente.*/
            $tiempo_transcurrido=strtotime($min_actual)-strtotime($min_comienzo);
            $ciclo=$tiempo_transcurrido/60;
            $registro_actual=0;
            $registro_actual=$registro_actual+$ciclo;
            $registro_siguiente=$registro_actual+1;
            /*Cada solicitud de reporte tendrá 60 segundos disponibles para descargar*/
            if($contador_reg>=0+$registro_actual && $contador_reg < $registro_siguiente ){
              $cont_token=$cont_token+1;
              $fecha_ini=substr($fecha_inicio, 0,10);
              $fecha_ini= new DateTime($fecha_ini);
              $fecha_ter=substr($fecha_termino, 0,10);
              $fecha_ter= new DateTime($fecha_ter);
              echo '<input type="hidden" id="objetivo_id" value='.$objetivo_id.'><br>';
              echo '<input type="hidden" id="tipo" value='.$type.'>';
              echo '<input type="hidden" id="popup" value='.$popup.'>';
              echo '<input type="hidden" id="es_especial" value='.$es_especial.'>';
              echo '<input type="hidden" id="tiene_svg" value='.$tiene_svg.'>';
              echo '<input type="hidden" id="usuario_id" value='.$usuario_id.'>';
              echo '<div id="fecha_ini" style="display:none;">'.$fecha_inicio.'</div>';
              echo '<div id="fecha_ter" style="display:none;">'.$fecha_termino.'</div>';
              echo '<input type="hidden" id="token" value='.$get_token[$cont_token].'>';
              echo '<div  id="dwn_completo" style="display:none;">'.$nombre_objetivo.$fecha_ini->format('d-m-Y').'_'.$fecha_ter->format('d-m-Y').'</div>';
              echo '<div  id="periodo_termino" style="display:none;">'.$fecha_ter->format('d-m-Y').'</div><br>';
              $solicitados++;
              
            }

            $contador_reg++;
            $pendientes=$pendientes+$solicitados;
            
          }
          
        }
      }
      echo "Valor de contador_reg: ".$pendientes;
      /*Se indica cuantos reportes quedan por descargar
      Creado por:Oscar Parra Flores*/
      echo "<h3 style='font-size:18px; color:blue;'>";
      echo "Reportes pendientes: ".$pendientes.'<br>';
      echo "</h3>";
      if ($pendientes==0){
        echo "<h3 style='font-size:18px; color:orange;' id='exito'>";
        echo "Todos los reportes han sido guardados con éxito";
        echo "</h3><br>";
      }
      echo '<input type="hidden" id="registros" value='.$pendientes.'>';
    echo '</span>';
  }
  enviarURL();
  ?> 
  <script type="text/javascript">
  var objetivo_id=$("#objetivo_id").val();
  var type=$("#tipo").val();
  var popup=$("#popup").val();
  var es_especial=$("#es_especial").val();
  var tiene_svg=$("#tiene_svg").val();
  var usuario_id=$("#usuario_id").val();
  var fecha_inicio=$("#fecha_ini").html();
  var fecha_termino=$("#fecha_ter").html();
  var token=$("#token").val();
  var dwn_completo=$("#dwn_completo").html();
  dwn_completo=dwn_completo.substr(1);
  console.log("Reporte visto: "+dwn_completo);
  var url="http://localhost/acceso_especial_api_drive.php?objetivo_id="+objetivo_id+"&type="+type+"&popup="+popup+"&es_especial="+es_especial+"&tiene_svg="+tiene_svg+"&usuario_id="+usuario_id+"&fecha_inicio="+fecha_inicio+"&fecha_termino="+fecha_termino+"&token="+token;
    document.write(url);
  function enviarSolicitud(){
    solicitudXhr("GET","http://localhost/acceso_especial_api_drive.php","?objetivo_id="+objetivo_id+"&type="+type+"&popup="+popup+"&es_especial="+es_especial+"&tiene_svg="+tiene_svg+"&usuario_id="+usuario_id+"&fecha_inicio="+fecha_inicio+"&fecha_termino="+fecha_termino+"&token="+token+"","application/pdf",dwn_completo+".pdf");
    
  }   
  setTimeout("enviarSolicitud()", 5000);
  function actualizarVistaRegistros(){
    var registros=document.getElementById('registros');
    var reg=registros.value;
    if (reg > 0){
      $("#cuerpo_solicitud").load("peticion_reportes_pdf.php #cuerpo_solicitud");
      document.location.reload()
    }
  }
  setInterval("actualizarVistaRegistros()", 60000);
  </script>
  </body>
</html>
