<?php
$u = usuarioPrivilegiado_acceso();
$u2 = usuarioPrivilegiado();
/*$clase = new insumo();

$datos = $clase->get_acceso_bodega_usuario($_SESSION['id_persona']);
$bodega;
foreach ($datos as $d) {
  $bodega = $d['id_bodega_insumo'];
}*/
?>

<!-- Sidebar -->
<main class="u-main" role="main">
  <aside id="sidebar" class="u-sidebar" style="top:2.5rem">
    <div class="u-sidebar-inner">
      <header class="u-sidebar-header">
        <a class="u-sidebar-logo" href="principal.php">
          <img class="img-fluid" src="./assets/img/logo.png" width="124" alt="Stream Dashboard">
        </a>
      </header>

      <nav class="u-sidebar-nav">
        <ul class="u-sidebar-nav-menu u-sidebar-nav-menu--top-level">
          <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link" href="principal.php">
              <i class="fas fa-layer-group u-sidebar-nav-menu__item-icon"></i>
              <span class="u-sidebar-nav-menu__item-title">Dashboard</span>
            </a>
          </li>
          <!-- Recursos Humanos -->
          <?php if ($u != NULL && $u->accesoModulo(1163) || $u != NULL && $u->accesoModulo(8085)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#baseUI">
                <i class="fa fa-users u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Recursos Humanos</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>
              <ul id="baseUI" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_100">
                    <span class="fas fa-user-alt u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Empleados</span>
                  </a>
                </li>
                <?php if ($u->hasPrivilege(87)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_101">
                      <span class="fas fa-id-card u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Plazas</span>
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($u->hasPrivilege(87) || $u->hasPrivilege(280)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_102">
                      <span class="fas fa-id-badge u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Puestos</span>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if ($u->hasPrivilege(167)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_103">
                      <span class="u-sidebar-nav-menu__item-icon">C</span>
                      <span class="u-sidebar-nav-menu__item-title">Contratos</span>
                    </a>
                  </li>
                <?php endif; ?>


              </ul>
            </li>
          <?php endif; ?>
          <!-- Viaticos -->
          <?php if ($u != NULL && $u->accesoModulo(1121) || $u != NULL && $u->accesoModulo(8085)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" id="vt_link" data-target="#base_viaticos">
                <i class="fa fa-money-bill u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Viáticos</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>

              <ul id="base_viaticos" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_700" id="vt_nombramiento">
                    <span class="fa fa-money-check-alt u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Nombramientos</span>
                  </a>
                </li>
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_701">
                    <span class="fa fa-chart-pie u-sidebar-nav-menu__item-icon"></span>

                    <span class="u-sidebar-nav-menu__item-title">Reportes</span>
                  </a>
                </li>
                <?php
                if ($u2->hasPrivilege(4)):
                  ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_703">
                      <span class="fa fa-file-contract u-sidebar-nav-menu__item-icon"></span>

                      <span class="u-sidebar-nav-menu__item-title">Formularios</span>
                    </a>
                  </li>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_704">
                      <span class="fa fa-file-contract u-sidebar-nav-menu__item-icon"></span>

                      <span class="u-sidebar-nav-menu__item-title">Formularios Utilizados</span>
                    </a>
                  </li>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_705">
                      <span class="fa fa-file-contract u-sidebar-nav-menu__item-icon"></span>

                      <span class="u-sidebar-nav-menu__item-title">Tipos de formularios</span>
                    </a>
                  </li>
                  <?php
                endif;
                ?>
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_702">
                    <span class="fa fa-user-check u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Solvencia</span>
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>
          <!-- Alimentos -->
          <?php if ($u != NULL && $u->accesoModulo(7844) || $u != NULL && $u->accesoModulo(8085)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#base_alimentos">
                <i class="fas fa-utensils u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Control de Alimentos</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>

              <ul id="base_alimentos" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <?php if ($u->hasPrivilege(269)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_790">
                      <span class="fa fa-user-plus u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Asignaciones</span>
                    </a>
                  </li>
                <?php endif; ?>

                <!-- <?php if ($u->hasPrivilege(270)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_791">
                      <span class="fa fa-user-times u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Excepciones</span>
                    </a>
                  </li>
                <?php endif; ?> -->

                <?php if ($u->hasPrivilege(272) || $u != NULL && $u->accesoModulo(8085)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_792">
                      <span class="fa fa-file-alt u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Reporte</span>
                    </a>
                  </li>
                <?php endif; ?>

              </ul>
            </li>
          <?php endif; ?>
          <!-- Vehiculos -->
          <?php if ($u != NULL && $u->accesoModulo(1162)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#base_vehiculos">
                <i class="fas fa-car u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Vehiculos</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>

              <ul id="base_vehiculos" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <?php if ($u->hasPrivilege(109)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_1200">
                      <span class="fas fa-gas-pump u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Vales de Combustible</span>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if ($u->hasPrivilege(112)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_1201">
                      <span class="fa-solid fa-ticket u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Ingreso de Cupones</span>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if ($u->hasPrivilege(112)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_1202">
                      <span class="fa-sharp fa-regular fa-ticket-simple u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Entrega de Cupones</span>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if ($u->hasPrivilege(115)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_1203">
                      <span class="fa-solid fa-cars u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Vehículos</span>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if ($u->hasPrivilege(373)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_1204">
                      <span class="fa-sharp fa-solid fa-sliders u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Servicios</span>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if ($u->hasPrivilege(376)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_1205">
                      <span class="fa-sharp fa-solid fa-sheet-plastic u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Reportes</span>
                    </a>
                  </li>
                <?php endif; ?>

              </ul>



            </li>
          <?php endif; ?>
          <!-- Insumos -->
          <?php if ($u != NULL && $u->accesoModulo(3549) || $u != NULL && $u->accesoModulo(8085)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#baseIN">
                <i class="fa fa-box u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Control de Insumos</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>
              <ul id="baseIN" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_200">
                    <span class="fa fa-archive u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Movimientos</span>
                  </a>
                </li>
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_200&mov=1">
                    <span class="fas fa-boxes u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Insumos</span>
                  </a>
                </li>
                <?php if ($u != NULL && $u->accesoModulo(3549)): //insumos
                      ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_200&mov=2">
                      <span class="fas fa-user-check u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Asignacion</span>
                    </a>
                  </li>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_200&mov=4">
                      <span class="fas fa-user-minus u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Devolución</span>
                    </a>
                  </li>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_200&mov=10">
                      <span class="fas fa-user-lock u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Resguardo</span>
                    </a>
                  </li>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_201">
                      <span class="fa fa-chart-pie u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Reportes</span>
                    </a>
                  </li>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_202">
                      <span class="fa fa-file-signature u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Solvencia</span>
                    </a>
                  </li>

                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_210">
                      <span class="fas fa-warehouse u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Bodegas</span>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>
          <!-- Control de Salones -->
          <?php if ($u != NULL && $u->accesoModulo(7845)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#base_salones">
                <i class="fas fa-table u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Control de Salones</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>

              <ul id="base_salones" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_300">
                    <span class="fas fa-list-alt u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Salones</span>
                  </a>
                </li>
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_301">
                    <span class="fa fa-book-open u-sidebar-nav-menu__item-icon"></span>

                    <span class="u-sidebar-nav-menu__item-title">Solicitudes</span>
                  </a>
                </li>
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_302">
                    <span class="fa fa-calendar-check u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Reservaciones</span>
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>
          <!-- Ingreso Peatonal -->
          <?php if ($u != NULL && $u->accesoModulo(7846)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#base_quinta">
                <i class="fas fa-user-circle u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Ingreso peatonal</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>
              <ul id="base_quinta" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <?php
                if ($u->hasPrivilege(277)) {
                  $id_puerta = visita::get_data_by_ip($_SERVER['REMOTE_ADDR']);
                }
                ?>
                <?php if (($id_puerta != null && $id_puerta['id_puerta'] == 1) || $u->hasPrivilege(278)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_400">
                      <span class="fa fa-file-alt u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Ingreso</span>
                    </a>
                  </li>
                <?php endif; ?>
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_401">
                    <span class="fas fa-id-card-alt u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Visita</span>
                  </a>
                </li>

                <?php if ($u->hasPrivilege(278)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_402">
                      <span class="fas fa-address-book u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Reporte</span>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>
          <!-- Control de Noticias -->
          <?php if ($u != NULL && $u->accesoModulo(7847)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#base_noticias">
                <i class="fas fa-newspaper u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Control de Noticias</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>
              <ul id="base_noticias" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_600">
                    <span class="fa fa-file-alt u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Noticias</span>
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>
          <!-- Control de Horarios -->
          <?php if ($u != NULL && $u->accesoModulo(7852)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#base_horarios">
                <i class="fas fa-clock u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Control de Horarios</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>
              <ul id="base_horarios" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <?php if ($u->hasPrivilege(299)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_800">
                      <span class="fas fa-user-clock u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Reporte Personal</span>
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($u->hasPrivilege(292)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_801">
                      <span class="fas fa-stopwatch u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Reporte Diario</span>
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($u->hasPrivilege(295)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_803">
                      <span class="fas fa-umbrella-beach u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Control de Vacaciones</span>
                    </a>
                  </li>
                <?php endif; ?>
                <!-- <?php if (usuarioPrivilegiado()->hasPrivilege(295)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_803" id="vt_vacaciones">
                      <span class="fa fa-file-invoice u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Control de Boletas</span>
                    </a>
                  </li>
                <?php endif; ?> -->
                <?php if ($u->hasPrivilege(295)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_804" id="vt_calendario">
                      <span class="fa fa-calendar u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Calendario</span>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>
          <!-- Planta Telefónica -->
          <?php if ($u != NULL && $u->accesoModulo(1875)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#base_directorio">
                <i class="fas fa-phone u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Directorio Telefónico</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>
              <ul id="base_directorio" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" target="_blank" href="../saas_directorio/index.php">
                    <span class="fas fa-fax u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Extensiones</span>
                  </a>
                </li>
                <?php if ($u->hasPrivilege(19)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_1000">
                      <span class="fas fa-book u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Dependencias</span>
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($u->hasPrivilege(228)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_1001">
                      <span class="fas fa-address-card u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Teléfonos Personales</span>
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($u->accesoModulo(7851)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_1002">
                      <span class="fas fa-eye u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Control de Vistas</span>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>
          <!-- Control de Documentos -->
          <?php if ($u != NULL && $u->accesoModulo(8017) || $u != NULL && $u->accesoModulo(8085)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#base_documentos">
                <i class="fas fa-folder u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Control de Documentos</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>
              <ul id="base_documentos" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <!--<li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_900">
                    <span class="fa fa-file-alt u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Documentos</span>
                  </a>
                </li>-->
                <!--<li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_901">
                    <span class="fa fa-file-alt u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Justificaciones</span>
                  </a>
                </li>-->
                <?php if ($u2->hasPrivilege(325)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_905">
                      <span class="fa fa-file-alt u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">PAC</span>
                    </a>
                  </li>
                <?php endif; ?>

                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_902">
                    <span class="fa fa-file-powerpoint u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Pedidos</span>
                  </a>
                </li>
                <?php if (usuarioPrivilegiado()->hasPrivilege(383)) : ?>
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_907">
                    <span class="fa fa-file-alt u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Insumos</span>
                  </a>
                </li>
              <?php endif;
                  if (usuarioPrivilegiado()->hasPrivilege(384)) : ?>
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_908">
                    <span class="fa fa-file-alt u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Fraccionamiento</span>
                  </a>
                </li>
                <?php endif; ?>

                <?php if ($u2->hasPrivilege(302) || $u2->hasPrivilege(318) || $u2->hasPrivilege(323)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_906">
                      <span class="fa fa-file-alt u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Facturas</span>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

          <?php if ($u != NULL && $u->accesoModulo(8931)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#tareas">
                <i class="fas fa-list-ol u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Control de Tareas</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>

              <ul id="tareas" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_3040">
                    <span class="fa-sharp fa-solid fa-ticket u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Tickets</span>
                  </a>
                </li>

                <?php if ($u2->hasPrivilege(349) || $u2->hasPrivilege(350) || $u2->hasPrivilege(351)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_3041">
                      <span class="fa-solid fa-chart-line-up u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Graficas</span>
                    </a>
                  </li>

                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_3042">
                      <span class="fa-regular fa-folder-open u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Reporteria</span>
                    </a>
                  </li>

                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_3043">
                      <span class="fa-solid fa-bug u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Diagnosticos</span>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>
          <!-- fin -- Documentos-->
          <!-- inicio bodega -->
          <!-- Control de Transportes -->
          <?php if ($u != NULL && $u->accesoModulo(8326)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#base_bodega">
                <i class="fa-regular fa-boxes-stacked u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Bodega</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>
              <ul id="base_bodega" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_4040">
                    <span class="fa-regular fa-person-carry-box u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Requisiciones</span>
                  </a>
                </li>
                <?php if ($u2->hasPrivilege(362)): ?>
                  <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="?ref=_4041">
                      <span class="fa-regular fa-chart-line u-sidebar-nav-menu__item-icon"></span>
                      <span class="u-sidebar-nav-menu__item-title">Reporte mensual</span>
                    </a>
                  </li>
                <?php endif; ?>

              </ul>
            </li>
          <?php endif; ?>

          <!-- fin bodega -->

          <!-- inicio -->
          <?php if ($u != NULL && $u->accesoModulo(8687)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#baseBienes">
                <i class="fas fa-newspaper u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Control de Bienes</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>
              <ul id="baseBienes" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_2020">
                    <span class="fa fa-file-alt u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Inventario</span>
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>
          <!-- fin -->

          <!-- Control de Transportes -->
          <?php if ($u != NULL && $u->accesoModulo(8686)): ?>
            <li class="u-sidebar-nav-menu__item">
              <a class="u-sidebar-nav-menu__link" href="#!" data-target="#base_transportes">
                <i class="fas fa-newspaper u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Control de Transportes</span>
                <i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
                <span class="u-sidebar-nav-menu__indicator"></span>
              </a>
              <ul id="base_transportes" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level"
                style="display: none;">
                <li class="u-sidebar-nav-menu__item">
                  <a class="u-sidebar-nav-menu__link" href="?ref=_3030">
                    <span class="fa fa-file-alt u-sidebar-nav-menu__item-icon"></span>
                    <span class="u-sidebar-nav-menu__item-title">Transportes</span>
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>
          <!-- Control de Transportes -->

        </ul>
      </nav>
    </div>
  </aside>
  <!-- END Sidebar -->
