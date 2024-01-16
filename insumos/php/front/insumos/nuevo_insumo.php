<?php
include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){
    /*$id_insumo=null;
    if ( !empty($_GET['id_insumo'])) {
      $id_insumo = $_REQUEST['id_insumo'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_200");
    }else{

    }*/
    $tipos = array();
    $marcas = array();
    $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
    foreach($datos AS $d){
      $bodega = $d['id_bodega_insumo'];

      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT id_bodega_insumo, id_tipo_insumo, descripcion, descripcion_corta
              FROM inv_tipo_insumo
              WHERE id_bodega_insumo=?";
      $p = $pdo->prepare($sql);
      $p->execute(array($bodega));// 65 es el id de aplicaciones
      $tipos = $p->fetchAll();

      $sql2 = "SELECT id_marca, descripcion, descripcion_corta
              FROM inv_marca_producto
              WHERE id_bodega_insumo=?";
      $p2 = $pdo->prepare($sql2);
      $p2->execute(array($bodega));// 65 es el id de aplicaciones
      $marcas = $p2->fetchAll();
      Database::disconnect_sqlsrv();

    }



?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="insumos/js/funciones_agregar.js"></script>
  <script src="assets/js/plugins/chosen/chosen.jquery.js"></script>
  <script src="assets/js/plugins/chosen/docsupport/prism.js"></script>
  <script src="assets/js/plugins/chosen/docsupport/init.js"></script>
  <link rel="stylesheet" href="assets/js/plugins/chosen/chosen.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
  <script type="text/javascript" src="assets/js/plugins/file_style/bootstrap-filestyle.min.js"> </script>
  <script>
      jQuery(function(){
          // Init page helpers (Select2 Inputs plugins)
          //App.initHelpers(['select2']);

          $('#excelfile').filestyle({});


      });
  </script>


</head>
<body>

  <div class="modal-header">
    <h4 class="modal-title">Nuevo Insumo</h4>
    <ul class="list-inline ml-auto mb-0">

      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>

  </div>

  <div class="modal-body">
    <form action="" method="post">
    <!-- Country dropdown -->
    <div class="form-group">
      <select id="tipo" name="tipo" class="form-control chosen-select-width" data-placeholder="Seleccionar">
        <option value="0" >Seleccionar</option>
        <?php
        foreach($tipos AS $tipo){
          echo '<option value="'.$tipo['id_tipo_insumo'].'">'.$tipo['id_tipo_insumo'].'/'.$tipo['descripcion'].'</option>';
        }
        ?>
      </select>
    </div>


    <!-- State dropdown -->
    <div class="form-group">
      <select id="subtipo" name="subtipo" class="form-control">
          <option value="">Seleccionar sub tipo</option>
      </select>
    </div>


    <!-- City dropdown -->
    <div class="form-group">
      <select id="marca" name="marca" class="form-control">
        <option value="0" >Seleccionar</option>
        <?php
        foreach($marcas AS $marca){
          echo '<option value="'.$marca['id_marca'].'">'.$marca['descripcion'].'</option>';
        }
        ?>
      </select>
    </div>
    <div class="form-group">
      <select id="modelo" name="modelo" class="form-control ">
          <option value="">Seleccionar Modelo</option>
      </select>
    </div>

    <div class="form-group">
      <input class="form-control" type="text" id="producto_desc"></input>
    </div>

    <input type="file" id="excelfile" name="excelfile"class="form-control" />

    <br />
    <br />
    <table id="exceltable" class="table table-bordered table-striped"></table>
    <div class="form-group" id="but" style="display:none" >
      <div class="col-xs-12 text-center"  >
        <div class="form-material">
          <input class="btn btn-sm btn-block btn-success" type="button" id="viewfile" value="Agregar productos" onclick="contar_columnas()"></input>
        </div>
      </div>
    </div>


</form>


  </div>
<?php }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index.php");
}
?>
