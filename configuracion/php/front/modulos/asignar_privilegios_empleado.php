<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7851)){//1163 módulo recursos humanos

    include_once '../../../../empleados/php/back/functions.php';
    $empleados = array();
    $empleados=empleado::get_empleados_busqueda();
?>

<script src="configuracion/js/funciones.js"></script>

<select id="cmb_personas" class="chosen-select-width">
  <?php
  foreach($empleados as $e){

    echo '<option value="'.$e['id_persona'].'">
    '.$e['primer_nombre'].' '.'
    '.$e['segundo_nombre'].' '.'
    '.$e['tercer_nombre'].' '.'
    '.$e['primer_apellido'].' '.'
    '.$e['segundo_apellido'].' '.'
    '.$e['tercer_apellido'].'
    </option>';

  }
  ?>
</select>


<script src="./assets/js/plugins/chosen/chosen.jquery.js"></script>
<script src="./assets/js/plugins/chosen/docsupport/prism.js"></script>
<script src="./assets/js/plugins/chosen/docsupport/init.js"></script>
<link rel="stylesheet" href="./assets/js/plugins/chosen/chosen.css">
<?php
}
else{
  include('inc/401.php');
}
}
else{
  include_once '../../../../inc/401.php';
}
?>
