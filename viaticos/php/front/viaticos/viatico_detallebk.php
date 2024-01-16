<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    include_once '../../back/functions.php';
    $id_viatico=null;
    $tipo_filtro=null;
    $bodega;
    if ( !empty($_GET['id_viatico'])) {
      $id_viatico = $_REQUEST['id_viatico'];
    }
    if(!empty($_GET['tipo_filtro'])){
      $tipo_filtro=$_REQUEST['tipo_filtro'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_200");
    }else{
      /*include_once '../../back/functions.php';
      $clase= new insumo();
*/
      /*$datos = $clase->get_acceso_bodega_usuario($_SESSION['id_persona']);
      foreach($datos AS $d){
        $bodega = $d['id_bodega_insumo'];
      }*/
    }


?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="viaticos/js/cargar.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
  <!--<script src="viaticos/js/source_modal.js"></script>-->
  <script>
  get_viatico_detalle_encabezado('viatico_detalle_encabezado');
  </script>






</head>
<body>

  <div class="modal-header">
    <h4 class="modal-title">Detalle del Nombramiento # <?php echo $id_viatico;?></h4>
    <ul class="list-inline ml-auto mb-0">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-personalizado active btn-sm">
          <input type="radio" name="options" id="option1" autocomplete="off" checked onchange="get_viatico_detalle_encabezado('viatico_detalle_encabezado')"> Detalle
        </label>
        <label class="btn btn-personalizado btn-sm">
          <input type="radio" name="options" id="option2" autocomplete="off" onchange="get_viatico_detalle_encabezado('empleados_por_viatico')"> Empleados
        </label>
        <label class="btn btn-personalizado btn-sm" id="actions1Invoker" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="cargar_menu_impresion(<?php echo $id_viatico?>,3)">
        <span  autocomplete="off"> Imprimir</span>
        <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px">
          <div class="card overflow-hidden" style="margin-top:-20px;">
            <div class="card-header d-flex align-items-center py-3">
              <h2 class="h4 card-header-title">Opciones:</h2>
            </div>
            <div  class="card-body animacion_right_to_left" style="padding: 0rem;">
              <div id="menu3<?php echo $id_viatico?>"></div>
            </div>
          </div>
        </div>
        </label>
        <label class="btn btn-personalizado btn-sm" data-dismiss="modal">
          <span name="options" id="option3" autocomplete="off"  > Salir
        </label>
      </div>


      <!--<li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>-->
    </ul>

  </div>
  <input id="id_viatico" hidden value="<?php echo $id_viatico;?>"></input>
  <input id="id_filtro_detalle"hidden value="<?php echo $tipo_filtro?>"></input>
  <div class="modal-body">
    <div id="datos_nombramiento" class="slide_up_anim">
    </div>


    </div>











  <?php
 }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index.php");
}
?>
