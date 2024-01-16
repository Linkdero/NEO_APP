<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  ?>
  <script src="assets/js/plugins/chosen/chosen.jquery.js"></script>
  <script src="assets/js/plugins/chosen/docsupport/prism.js"></script>
  <script src="assets/js/plugins/chosen/docsupport/init.js"></script>
  <link rel="stylesheet" href="assets/js/plugins/chosen/chosen.css">
  <?php
  $transaccion=$_POST['transaccion'];

  $datos=insumo::get_empleado_by_transaccion($transaccion);

  $id_persona=$datos['id_persona'];
  //$tipo=$_POST['tipo'];
  if(is_numeric($id_persona)){
    $e = array();
    $e = empleado::get_empleado_by_id_ficha($id_persona);
    $direccion = empleado::get_direcciones_saas_by_id($datos['id_persona_direccion_recibe']);
    //$tipos = insumo::get_tipos_movimientos($tipo);//Ingreso a Bodega
    $data = array();

    if($e['primer_nombre']!=''){
      $accion = "";
      $status = '';
      $igss='--';
      $nisp='--';
      $observaciones='--';
      $tipo_contrato='--';


      $nombre=$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.
      $e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'];

      $foto = empleado::get_empleado_fotografia($id_persona);
      $encoded_image = base64_encode($foto['fotografia']);
      $Hinh = "<img class='img-fluid mb-3' src='data:image/jpeg;base64,{$encoded_image}' > ";
      $resultado='';
      $resultado.='<div class="row slide_up_anim fadeIn  .retraso1"><div class="col-sm-4 ">';
      $resultado.='<div class="img-contenedor_profile border-md-right border-light" style="border-radius:50%">';
      $resultado.=$Hinh;
      $resultado.='</div>';
      $resultado.='</div><div class="col-sm-8">';
      $resultado.= '<h3>'.$nombre.'</h3>';
      $resultado.= '<h5 class="text-muted">'.$direccion['descripcion'].'</h5>';
      $resultado.=$datos['tipo_movimiento'];

      /*$resultado.='<br>
      <select data-placeholder="Seleccione el tipo de ingreso" placeholder="Seleccione el tipo de ingreso" class="chosen-select-width col-xs-12" >';
      foreach($tipos AS $t){
        $resultado.='<option id="'.$t['id_tipo_movimiento'].'">'.$t['descripcion_corta'].'</option>';
      }
      $resultado.='</select>';*/
      $anotaciones='Sin anotaciones';
      if($datos['descripcion']!=''){
        $anotaciones=$datos['descripcion'];
      }
      $resultado.='</div></div>';
      $resultado.='<br><div class="row"><div class="col-sm-4"><h3>Anotaciones:</h3></div>';
      $resultado.='<div class="col-sm-8">'.$anotaciones.'</div></div>';
      echo $resultado;
    }else{
      echo 'No existe este empleado';
    }


  }else{
    echo 'Debe ingresar un valor numérico';
  }






else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
