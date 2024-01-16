<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  ?>
  <div class=" overflow-hidden">

    <div class=" py-3 fadeInDown " style="margin-top:10px">

  <div class="row">
    <!-- App -->
    <div class="col-3  mb-2 block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/phonebook.svg" alt="">
        <span class="text-center">Teléfonos</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  mb-2 block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/maps-and-flags.svg" alt="">
        <span class="text-center">Direcciones</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  mb-2 block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/medical-history.svg" alt="">
        <span class="text-center">Datos Médicos</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/id-card.svg" alt="">
        <span class="text-center">Documentos Personales</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/camera.svg" alt="">
        <span class="text-center">Fotografías</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/graduation-cap.svg">
        <span class="text-center">Escolaridad</span>
      </a>
    </div>
    <!-- End App -->
  <!--</div>
  <br>
  <div class="row">-->
    <!-- App -->
    <div class="col-3  mb-2 block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="?ref=_500">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/network.svg" alt="">
        <span class="text-center">Referencias</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  mb-2 block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/police-hat.svg" alt="">
        <span class="text-center">Servicios</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  mb-2 block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/title.svg" alt="">
        <span class="text-center">Cursos / Capacitaciones</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/credit-cards.svg" alt="">
        <span class="text-center">Cuentas Bancarias</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/resume.svg" alt="">
        <span class="text-center">Experiencia</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/car.svg" alt="">
        <span class="text-center">Vehículos</span>
      </a>
    </div>
    <!-- End App -->
  <!--</div>

  <br>
  <div class="row">-->
    <!-- App -->
    <div class="col-3  mb-2 block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="?ref=_500">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/gun.svg" alt="">
        <span class="text-center">Armas</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  mb-2 block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/wallet.svg" alt="">
        <span class="text-center">Ingresos y Egresos</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3  mb-2 block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/house.svg" alt="">
        <span class="text-center">Bienes</span>
      </a>
    </div>
    <!-- End App -->

    <!-- App -->
    <div class="col-3 block block-link-hover3">
      <a class="u-apps d-flex flex-column rounded" href="#!">
        <img class="img-fluid u-avatar mx-auto mb-2" src="./assets/img/brands-sm/hammer.svg" alt="">
        <span class="text-center">Datos Legales</span>
      </a>
    </div>
    <!-- End App -->


    <!-- End App -->
  </div>
  </div>

  <div class="card-footer py-3">
    <span class="btn btn-soft-info btn-sm" onclick="mostrarOpcion(1)"><i class="fa fa-arrow-left"></i> Regresar</span>
  </div>

<?php }
?>
