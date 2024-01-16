<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 ??????????????
    $id_persona=null;
    include_once '../../back/functions.php';
    //$permiso=array();


    if ( !empty($_GET['id_persona'])) {
      $id_persona = $_REQUEST['id_persona'];
    }
    if ( !empty($_GET['tipo'])) {
      $id_tipo_filtro = $_REQUEST['tipo'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_100");
    }else{

    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js?v=<?php echo time();?>"></script>
      <script src="assets/js/plugins/vue/vue.js"></script>
      <script src="assets/js/pages/components.js?v=<?php echo time();?>"></script>
      <script src="alimentos/js/components.js?v=<?php echo time();?>"></script>
      <!-- <script src="empleados/js/validacionesv2.js?v=<?php echo time();?>"></script> -->


      <script src="empleados/js/persona_datos_vue_3.6.js?v=<?php echo time();?>"></script>


    </head>
    <body>
      <div id="persona_app">
        <!-- inicio -->
        <div class="modal-header" >
          <h4 class="modal-title" id="exampleModalLabel">Excepciones por Empleado</h4>
          <ul class="list-inline ml-auto mb-0">
              <span class="link-muted h3">
                <i class="fa fa-times"></i>
              </span>
          </ul>
        </div>
        <div class="modal-body content" >
          <input type="text" id="id_persona" value="<?php echo $id_persona?>" hidden></input>
          <input type="text" id="id_tipo_filtro" hidden value="<?php echo $id_tipo_filtro?>" ></input>
          <input type="text" id="id_cambio" hidden value="0"></input>
            <!-- inicio vue js -->
              <div class="row">
                <div class="col-sm-2">
                  <fotografia ref="myFotografia" :id_persona="id_persona" tipo="1"></fotografia>
                </div>
                <div class="col-sm-10 scrollable-div-persona"v-on:scroll.passive='onScroll()' style="left:0.8rem"><div ref="infoBox" id="div-persona">
                  <a href="#" class="scroll-down" v-if="showButton" address="true" @click="down"></a>
                  <!-- inicio -->
                  <div v-if="opcion == 1">
                    <div class="row" class="slide_up_anim">
                      <!-- inicio listado -->
                      <div class="col-sm-12">
                        <detalle-excepciones :id_persona="id_persona" :privilegio="privilegio"></detalle-excepciones>
                      </div>
                      <!-- fin listado -->
                    </div>
                    <!-- fin -->
                  </div>
                  <!-- fin -->
                  <!-- inicio editar informacion -->
                  <form-catalogo v-if="showCatalogo == true" :option="cargarCatalogo" :tipocarga="1" :opcionactual="opcion"></form-catalogo>

                  <section class="ok">
                  </section>
                </div>
              </div>
            </div>
            </div>
          <!-- fin vue js -->
        <!-- fin -->
      </div>
    </div>
  </body>



  <?php }
  else{
    include('inc/401.php');
  }
}
else{
  header("Location: index");
}
?>
