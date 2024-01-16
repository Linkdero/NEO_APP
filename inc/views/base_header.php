<?php
if (function_exists('verificar_session') && verificar_session()){
  header("Access-Control-Allow-Origin: *");
  $foto_=datos::get_fotografia($_SESSION['id_persona']);
  $encoded_image = base64_encode($foto_['fotografia']);
  $foto='';
  if($encoded_image!=''){
    $foto.="<img class='u-avatar--xs img-fluid rounded-circle mr-2' src='data:image/jpeg;base64,{$encoded_image}' alt='Image description'>";
  }else{
    $foto.="<img class='u-avatar--xs img-fluid rounded-circle mr-2' src='assets/svg/mockups/escudo.png' style='width:55px; '> ";
  }

?>
 <header class="u-header" >
   <div class="u-header-left">
     <a class="u-header-logo" href="principal.php">
       <img class="u-logo-desktop" src="./assets/svg/mockups/Gobierno2024logo.png" width="100%" alt="Stream Dashboard">
       <img class="img-fluid u-logo-mobile" src="./assets/img/logo-mobile.png" width="50" alt="Stream Dashboard">
     </a>
   </div>

    <div class="u-header-middle">
      <a class="js-sidebar-invoker u-sidebar-invoker" href="#!" data-is-close-all-except-this="true" data-target="#sidebar">
        <i class="fa fa-bars u-sidebar-invoker__icon--open"></i>
        <i class="fa fa-times u-sidebar-invoker__icon--close"></i>
      </a>

      <div class="u-header-search" data-search-mobile-invoker="#headerSearchMobileInvoker" data-search-target="#headerSearch">
        <a id="headerSearchMobileInvoker" class="btn btn-link input-group-prepend u-header-search__mobile-invoker" href="#!">
          <i class="fa fa-search"></i>
        </a>
        <div id="headerSearch" class="u-header-search-form"></div>
      </div>
    </div>

    <div class="u-header-right">
    <!-- Apps -->
      <?php if(usuarioPrivilegiado_acceso()->accesoModulo(7851)): ?>
        <div class="dropdown mr-4"></div>
          <div class="dropdown mr-4">
            <a class="link-muted" href="#!" role="button" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
              <span class="h3">
                <i class="far fa-circle"></i>
              </span>
            </a>
          <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-4" aria-labelledby="dropdownMenuLink" style="width: 360px;">
            <div class="card overflow-hidden">
              <div class="card-header d-flex align-items-center py-3">
                <h2 class="h4 card-header-title">Configuración</h2>
              </div>
              <div class="card-body py-3 animacion_right_to_left">
                <div class="row">
                  <div class="col-4 px-2 mb-2">
                    <a class="u-apps d-flex flex-column rounded" href="?ref=_500">
                      <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/modules.svg" alt="">
                      <span class="text-center">Módulos</span>
                    </a>
                  </div>
                  <div class="col-4 px-2 mb-2">
                    <a class="u-apps d-flex flex-column rounded" href="?ref=_501">
                      <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/catalogue (1).svg" alt="">
                      <span class="text-center">Catálogo</span>
                    </a>
                  </div>
                  <div class="col-4 px-2 mb-2">
                    <a class="u-apps d-flex flex-column rounded" href="?ref=_502">
                      <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/team.svg" alt="">
                      <span class="text-center">Usuarios</span>
                    </a>
                  </div>
                  <!-- <div class="col-4 px-2 mb-2">
                    <a class="u-apps d-flex flex-column rounded" href="?ref=_503">
                      <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/boxc.svg" alt="">
                      <span class="text-center">Listado PPR</span>
                    </a>
                  </div> -->
                  <div class="col-4 px-2 mb-2">
                    <a class="u-apps d-flex flex-column rounded" href="?ref=_504">
                      <img class="img-fluid u-avatar--xs mx-auto mb-2" src="./assets/img/brands-sm/landline.svg" alt="">
                      <span class="text-center">Extensiones</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
      <?php endif;?>
    <!-- End Apps -->

     <!-- User Profile -->
      <div class="dropdown ml-2">
        <a class="link-muted d-flex align-items-center" href="#!" role="button" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
          <?php echo $foto;?>
          <span class="text-dark d-none d-sm-inline-block">
            <?php echo $_SESSION['username'] ?><small class="fa fa-angle-down text-muted ml-1"></small>
          </span>
        </a>

       <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3" aria-labelledby="dropdownMenuLink" style="width: 260px;">
          <div class="card overflow-hidden">
            <div class="card-header py-3">
              <div class="d-flex align-items-center mb-3">
                <span class="h6 text-muted text-uppercase mb-0">Opciones</span>
                <div class="ml-auto text-muted"></div>
              </div>
            </div>

           <div class="card-body animacion_right_to_left" style="padding: 0rem;">
              <ul class="list-unstyled mb-0">
                <li class="mb-1">
                  <a class="d-flex align-items-center link-muted py-2 px-3" data-toggle="modal" data-target="#modal-remoto" href="configuracion/php/front/empleado/cambiar_password.php">
                    <span class="h3 mb-0"><i class="far fa-share-square text-muted mr-3"></i></span> Cambiar Password
                  </a>
                </li>
                <?php if(usuarioPrivilegiado_acceso()->accesoModulo(7851)): ?>
                  <li class="mb-1">
                    <a class="d-flex align-items-center link-muted py-2 px-3" target="_blank" href="http://srvdb:82/tareas/index.php">
                      <span class="h3 mb-0"><i class="far fa-share-square text-muted mr-3"></i></span> Solicitar soporte
                    </a>
                  </li>
                <?php endif;?>
                <li class="mb-1">
                  <a class="d-flex align-items-center link-muted py-2 px-3" href="logout.php">
                    <span class="h3 mb-0"><i class="far fa-share-square text-muted mr-3"></i></span> Salir
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
     <!-- End User Profile -->
   </div>
 </header>
 <?php
 }
 else{
   header("Location: index.php");
 }
 ?>
