<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7851)){//1163 módulo recursos humanos

    include_once '../../back/functions.php';
    $acceso_origen=$_POST['acceso_origen'];
    $modulo=$_POST['modulo'];
    $empleados = array();
    $empleados=configuracion::get_accesos($modulo);


?>

<script src="configuracion/js/funciones.js"></script>

<div class="row">
  <div class="container-fluid w-100" action="" method="">
    <div class="form-group">
      <div class="row">
        <div class="col-sm-12">
          <select id="cmb_personas_modulo" class="chosen-select-width">
            <?php
            foreach($empleados as $e){
              if($e['estado']=='Activo' && $e['id_acceso']!=$acceso_origen){
                echo '<option value="'.$e['id_acceso'].'">'.$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'].'</option>';
              }
            }
            ?>
          </select>
        </div>
      </div>

      </div>
      <div class="form-group ">
        <div class="row">
        <div class="col-sm-12">
          <button class="btn btn-sm btn-success btn-block" type="submit" onclick="trasladar_privilegios_de_acceso_acceso(<?php echo $acceso_origen?>,<?php echo $modulo?>)"><i class="fa fa-copy"></i> Copiar</button>
        </div>

      </div>
    </div>
  </div>
</div>


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
