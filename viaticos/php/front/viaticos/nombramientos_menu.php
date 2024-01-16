<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){
    //$correlativo=$_POST['correlativo'];
    include_once '../../back/functions.php';
    //$opciones=viaticos::get_opciones_menu($correlativo);
    ?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">

    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="viaticos/js/cargar_menu_pendientes_vue.js"></script>


    </script>

    <div>

      <div id="pendientes_div" >
        <div class="">
          <span class="form-icon-wrapper">
            <span class="form-icon form-icon--left">
              <i class="fa fa-search form-icon__item"></i>
            </span>
            <input placeholder="Filtrar.." id="myInput" onkeyup="filterFunction()" autocomplete="off" class=" form-icon-input-left" name="email" type="text" placeholder="tu.correo">
        </div>
        <ul class="list-unstyled mb-0 scrollable-menu">
          <li class="mb-1" v-for="p in pendientes_n" data-toggle="modal" data-target="#modal-remoto-lgg2" v-bind:href="''+p.url+''">
            <a class="d-flex align-items-center link-muted py-2 px-3">
              <small class="text-muted" style="margin-left:-10px">{{p.nombramiento}} </small>
              <h5  style="margin-left:10px"> {{p.direccion_solicitante}}</h5>
              <small class="text-muted" style="margin-left:10px">{{p.estado}} </small>
            </a>
          </li>
        </ul>
      </div>




    </div>


    <?php
  }
}else{
    header("Location: index.php");
}
?>
