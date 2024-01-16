<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1162)){
    $transaccion=null;
    if ( !empty($_GET['transaccion'])) {
      $transaccion = $_REQUEST['transaccion'];
    }

    if ( !empty($_POST)){
      header("Location: index.php?ref=_200");
    }else{

    }
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script type="text/javascript">



</script>

<script src="insumos/js/source_modal.js"></script>
<script src="insumos/js/cargar.js"></script>


</head>
<body>

  <div class="modal-body">
    <span  class="btn-circle" data-dismiss="modal" aria-label="Close"></span>
    <div class="row">
      <div class="col-sm-12">
        <div id="datos">
        </div>
      </div>

    </div>
    <input hidden id="transaccion" value="<?php echo $transaccion?>"  class="form-control input-sm form-corto"   autocomplete="off" autofocus></input>



      <table id="tb_insumos_transaccion" class="table  table-hover table-bordered" width="100%">
        <thead>
          <th>Producto</th>
          <th>Estante</th>
          <th>Serie</th>
          <th>Cantidad</th>


        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

  </div>
  <script>
  get_datos_empleado_by_transaccion();
  </script>
<?php }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index.php");
}
?>
