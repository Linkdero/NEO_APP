<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_persona=$_POST['id_persona'];
    ?>
    <input id="id_persona_di" type="text" value="<?php echo $id_persona?>" hidden></input>
    <script src="empleados/js/source_modal.js"></script>
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <table id="tb_empleado_listado_direcciones" class="table table-sm table-bordered table-striped  " width="100%">
      <thead>
        <tr>
          <th class="text-center">ID</th>
          <th class="text-center">Referencia</th>
          <th class="text-center">Reside</th>
          <th class="text-center">Calle/Av</th>
          <th class="text-center">Tope</th>
          <th class="text-center">Tipo</th>
          <th class="text-center">Nro. Casa</th>
          <th class="text-center">Zona</th>
          <th class="text-center">Departamento</th>
          <th class="text-center">Municipio</th>
          <th class="text-center">Lugar</th>
          <th class="text-center">Tipo</th>

        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  <?php }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index");
}
?>
