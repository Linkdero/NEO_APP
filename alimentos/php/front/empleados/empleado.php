<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_persona=null;
    include_once '../../back/functions.php';
    //$permiso=array();

    if ( !empty($_GET['id_persona'])) {
      $id_persona = $_REQUEST['id_persona'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_100");
    }else{
      //$persona=empleado::get_empleado_by_id($id_persona);
      $foto = empleado::get_empleado_fotografia($id_persona);
      $encoded_image = base64_encode($foto['fotografia']);
      $Hinh = "<img class='img-fluid rounded-circle mb-3' src='data:image/jpeg;base64,{$encoded_image}' width='84'> ";
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/cargar.js"></script>
      <script>
      $(function(){
        get_empleado_perfil();
      });

      </script>
    </head>
    <body>
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Perfil</h5>
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item" data-toggle="tooltip">
            <a id="actions1Invoker" class=" link-muted h3" href="#!" aria-haspopup="true" aria-expanded="false"
               data-toggle="dropdown" style="margin-right:-30px">
              <i class="fa fa-cog"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-4" aria-labelledby="dropdownMenuLink" style="width: 480px;">
              <div class="card overflow-hidden">
                <div class="card-header d-flex align-items-center py-3">
                  <h2 class="h4 card-header-title">Apps</h2>
                  <a class="ml-auto" onclick="get_empleado_datos()">Perfil</a>
                </div>
                <div class="card-body py-3 animacion_right_to_left scrollable-menu">

              <div class="row">
                <!-- App -->
                <div class="col-4 px-2 mb-2">
                  <a class="u-apps d-flex flex-column rounded" onclick="get_telefonos()">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/phonebook.svg" alt="">
                    <span class="text-center">Teléfonos</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2 mb-2">
                  <a class="u-apps d-flex flex-column rounded" onclick="get_direcciones()">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/maps-and-flags.svg" alt="">
                    <span class="text-center">Direcciones</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2 mb-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/medical-history.svg" alt="">
                    <span class="text-center">Datos Médicos</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/id-card.svg" alt="">
                    <span class="text-center">Documentos Personales</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/camera.svg" alt="">
                    <span class="text-center">Fotografías</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/graduation-cap.svg">
                    <span class="text-center">Escolaridad</span>
                  </a>
                </div>
                <!-- End App -->
              </div>
              <br>
              <div class="row">
                <!-- App -->
                <div class="col-4 px-2 mb-2">
                  <a class="u-apps d-flex flex-column rounded" href="?ref=_500">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/network.svg" alt="">
                    <span class="text-center">Referencias</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2 mb-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/police-hat.svg" alt="">
                    <span class="text-center">Servicios</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2 mb-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/title.svg" alt="">
                    <span class="text-center">Cursos / Capacitaciones</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/credit-cards.svg" alt="">
                    <span class="text-center">Cuentas Bancarias</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/resume.svg" alt="">
                    <span class="text-center">Experiencia</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/car.svg" alt="">
                    <span class="text-center">Vehículos</span>
                  </a>
                </div>
                <!-- End App -->
              </div>

              <br>
              <div class="row">
                <!-- App -->
                <div class="col-4 px-2 mb-2">
                  <a class="u-apps d-flex flex-column rounded" href="?ref=_500">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/gun.svg" alt="">
                    <span class="text-center">Armas</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2 mb-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/wallet.svg" alt="">
                    <span class="text-center">Ingresos y Egresos</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2 mb-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/house.svg" alt="">
                    <span class="text-center">Bienes</span>
                  </a>
                </div>
                <!-- End App -->

                <!-- App -->
                <div class="col-4 px-2">
                  <a class="u-apps d-flex flex-column rounded" href="#!">
                    <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/hammer.svg" alt="">
                    <span class="text-center">Datos Legales</span>
                  </a>
                </div>
                <!-- End App -->


                <!-- End App -->
              </div>
            </div>

            <div class="card-footer py-3">

            </div>

            </div>
          </li>




          <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
            <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>
      <div class="u-content">
				<div class="u-body">
          <input type="text" id="id_persona" value="<?php echo $id_persona?>" hidden></input>

					<div class="" id="datos">
          </div>
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
