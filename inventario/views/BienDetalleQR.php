<?php
include_once '../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8687)){



    if ( !empty($_GET['code'])) {
      $sicoin_code = $_REQUEST['code'];
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
    <script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="../../assets/vendor/jquery-migrate/jquery-migrate.min.js"></script>

    <script src="../../assets/js/plugins/moment/moment.js"></script>
    <script src='../../assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="../../assets/js/plugins/vue/vue.js"></script>
    <script type="module" src="../../inventario/src/appInventarioDetalleQR.js?t=<?php echo time();?>"></script>
  </head>
  <body>
    <input id="bien_id" name="bien_id" value="<?php echo $bien_id ?>" hidden></input>
    <input id="sicoin_code" name="sicoin_code" value="<?php echo $sicoin_code ?>" hidden></input>


    <div id="biendetalle" style="-webkit-transform: translateZ(0);">
      <!--<div class="text-center" style="position: absolute; background-color: rgba(255, 255, 255, 0.7); min-width:100%; min-height:100%; z-index:5; border-radius:3px" v-if="loading == false">
        <div class="loaderr" style="margin-top:150px"></div>
      </div>-->

      <div class="modal-body">

        <!--<estadonom v-on:sendestado="getEstadoV" :id_viatico="id_viatico"></estadonom>-->
        {{ bienDetalle }}

      </div>

    </div>

    </div>

  </body>
</html>


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
