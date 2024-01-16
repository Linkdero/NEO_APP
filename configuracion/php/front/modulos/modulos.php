
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7851)){ // 1163 Módulo Empleados
//h3Xhg3jvCcrX9ygp

?>


<script src="configuracion/js/source.js"></script>




          <!-- Tab Content -->
          <table id="tb_modulos" class="table table-sm table-bordered table-striped table-hover" width="100%" style="width:100%">
            <thead>
              <tr>

                <th class="text-center">Código</th>
                <th class="text-center">Sub módulo</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Status</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>



<!-- End Comments -->
<?php
}
else{
  //include('../../../../../inc/401.php');
}
}
else{
  header("Location: index");
}
?>
