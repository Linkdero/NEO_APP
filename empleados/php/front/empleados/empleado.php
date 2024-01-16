<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163) || $u != NULL && $u->accesoModulo(8085)){//1163 módulo recursos humanos
    $id_persona=null;
    $id_tipo_filto = null;
    $moduloF = usuarioPrivilegiado()->hasPrivilege(211);
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
      <script src='assets/js/plugins/cropper/cropper.js'></script>
      <link rel="stylesheet" href="assets/js/plugins/cropper/cropper.css">
      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js?v=<?php echo time();?>"></script>
      <script src="assets/js/plugins/vue/vue.js"></script>
      <script src="assets/js/pages/components.js?v=<?php echo time();?>"></script>
      <script src="empleados/js/appComponentes.1.3.js?v=<?php echo time();?>"></script>
      <script src="empleados/js/appValidacionesForm.1.2.js?v=<?php echo time();?>"></script>


      <script src="empleados/js/persona_datos_vue_3.8.js?v=<?php echo time();?>"></script>


    </head>
    <body>
      <div id="persona_app"style="-webkit-transform: translateZ(0);">
        <!-- inicio -->
        <div class="modal-header" >
          <h4 class="modal-title" id="exampleModalLabel">Perfil</h4>

          <ul class="list-inline ml-auto mb-0">
            <menu-perfil :opcion="opcion" :privilegio="privilegio"></menu-perfil>
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
                      <!-- inicio datos personales -->
                      <?php if($moduloF){

                      ?>
                      <div class="col-sm-3" id="h_perfil"  >

                        <div class="" style="background-color:">
                          <div class="card-body">
                            <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-user"></i>Datos personales
                          </div>
                          <div class="card-footer"  style="background-color:transparent">
                            <dato-persona icono="far fa-calendar-check" texto="Fecha de Nacimiento" :dato="persona.fecha_nacimiento"></dato-persona>
                            <dato-persona icono="fa fa-home" texto="Estado Civil" :dato="persona.estado_civil"></dato-persona>
                            <dato-persona icono="fa fa-venus-mars" texto="Género" :dato="persona.genero"></dato-persona>
                            <dato-persona icono="far fa-heart" texto="Tipo de sangre" :dato="persona.tsangre"></dato-persona>
                            <dato-persona icono="fa fa-church" texto="Religión" :dato="persona.religion"></dato-persona>
                          </div>

                        </div>
                      </div>
                      <!-- fin datos personales -->
                      <!-- inicio datos laborales -->
                      <div class="col-sm-3"  >
                        <div class="" style="background-color:">
                          <div class="card-body">
                            <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-user-tie"></i>Datos Laborales
                          </div>
                          <div class="card-footer"  style="background-color:transparent">
                            <dato-persona icono="far fa-id-card" texto="CUI" :dato="persona.cui"></dato-persona>
                            <dato-persona icono="far fa-address-card" texto="NIT" :dato="persona.nit"></dato-persona>
                            <dato-persona icono="fa fa-notes-medical" texto="IGSS" :dato="persona.igss"></dato-persona>
                            <dato-persona icono="far fa-id-badge" texto="NISP" :dato="persona.nisp"></dato-persona>
                            <dato-persona icono="fa fa-address-card" texto="Licencia" :dato="persona.licencia"></dato-persona>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                      <!-- fin datos laborales -->
                      <!-- inicio datos institucionales -->
                      <div class="col-sm-6"  >
                        <div class="" style="background-color:">
                          <div class="card-body">
                            <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-briefcase"></i>Datos Presupuestarios
                          </div>
                          <div class="card-footer"  style="background-color:transparent">
                            <dato-persona icono="fa fa-user-secret" texto="Dirección Nominal" :dato="persona.d_nominal"></dato-persona>
                            <dato-persona icono="fa fa-user-secret" texto="Dirección Funcional" :dato="persona.d_funcional"></dato-persona>
                            <dato-persona icono="fa fa-user-secret" texto="Puesto Nominal" :dato="persona.p_nominal"></dato-persona>
                            <dato-persona icono="fa fa-user-secret" texto="Puesto Funcional" :dato="persona.p_funcional"></dato-persona>
                            <dato-persona icono="fa fa-money-bill-wave-alt" texto="Sueldo" :dato="persona.sueldo"></dato-persona>
                          </div>
                        </div>

                      </div>
                      <!-- fin datos personales -->
                      <!-- inicio dirección -->
                      <?php if($moduloF){

                      ?>
                      <div class="col-sm-6" id="h_contacto">
                        <hr>
                        <div class="" style="background-color:">
                          <div class="card-body">
                            <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-address-book"></i> Contacto
                          </div>
                          <div class="card-footer"  style="background-color:transparent">
                            <dato-persona icono="fa fa-phone" texto="Teléfono" :dato="persona.telefono"></dato-persona>
                            <dato-persona icono="far fa-envelope" texto="Correo" :dato="persona.email"></dato-persona>
                            <dato-persona icono="fa fa-map-marker-alt" texto="Dirección" :dato="persona.direccion"></dato-persona>
                          </div>
                        </div>
                      </div>
                      <!-- fin dirección -->
                      <!-- inicio dirección -->
                      <div class="col-sm-6">
                        <hr>
                        <div class="" style="background-color:">
                          <div class="card-body">
                            <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-map-marker-alt"></i> Lugar de Nacimiento
                          </div>
                          <div class="card-footer"  style="background-color:transparent">
                            <dato-persona icono="fa fa-map-marked-alt" texto="Municipio" :dato="persona.municipio"></dato-persona>
                            <dato-persona icono="fa fa-map-marked-alt" texto="Departamento" :dato="persona.departamento"></dato-persona>
                            <dato-persona icono="fa fa-map-marker-alt" texto="Dirección" :dato="persona.direccion"></dato-persona>
                          </div>
                        </div>
                      </div>
                    <?php }?>
                      <!-- fin dirección -->
                      <!-- inicio listado de familia -->
                      <?php if($moduloF){

                      ?>
                      <div class="col-sm-12" id="h_escolaridad">
                        <detalle-nivel-academico :id_persona="id_persona" :privilegio="privilegio"></detalle-nivel-academico>
                      </div>
                      <!-- fin listado de familia -->
                      <div class="col-sm-12" id="h_familiares">
                        <detalle-familiares :id_persona="id_persona" :privilegio="privilegio"></detalle-familiares>
                      </div>
                      <div class="col-sm-12" id="h_telefonos">
                        <detalle-telefonos :id_persona="id_persona" :privilegio="privilegio"></detalle-telefonos>
                      </div>
                      <div class="col-sm-12" id="h_direcciones">
                        <detalle-direcciones :id_persona="id_persona" :privilegio="privilegio"></detalle-direcciones>
                      </div>
                      <div class="col-sm-12" id="h_documentos">
                        <detalle-documentos :id_persona="id_persona" :privilegio="privilegio"></detalle-documentos>
                      </div>
                      <div class="col-sm-12" id="h_cuentas">
                        <detalle-cuentas :id_persona="id_persona" :privilegio="privilegio"></detalle-cuentas>
                      </div>
                      <div class="col-sm-12" id="h_vacunas">
                        <detalle-vacunas :id_persona="id_persona" :privilegio="privilegio"></detalle-vacunas>
                      </div>
                      <div class="col-sm-12" id="h_capacitaciones">
                        <detalle-cursos :id_persona="id_persona" :privilegio="privilegio"></detalle-cursos>
                      </div>
                      <div class="col-sm-12" id="h_trabajos">
                        <detalle-trabajos :id_persona="id_persona" :privilegio="privilegio"></detalle-trabajos>
                      </div>
                    <?php }?>
                      <!-- fin listado de familia -->
                    </div>
                    <!-- fin -->
                  </div>
                  <!-- fin -->
                  <!-- inicio editar informacion -->
                  <?php if($moduloF){
                    ?>
                  <div v-else-if="opcion == 19">
                    <editar-persona ref="actInfo" :id_persona="id_persona" :persona="persona"></editar-persona>
                  </div>
                  <!-- fin editar informacion -->
                  <!-- inicio opciones de catálogos -->
                  <form-catalogo v-if="showCatalogo == true" :option="cargarCatalogo" :tipocarga="1" :opcionactual="opcion"></form-catalogo>
                <?php }?>

                  <!-- fin opciones de catálogos -->

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
