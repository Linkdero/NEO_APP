<?php
/**
 * base_side_overlay.php
 *
 * Author: pixelcave
 *
 * The side overlay of each page (Backend)
 *
 */

include_once 'base_side_overlay_functions.php';
$user = User::getByUserId($_SESSION["user_id"]);
$nombre = null;
if($user->persona['user_vid']==42)
{
  $nombre = $user->persona['user_nm2'];
}
else {
  $nombre = $user->persona['user_nm1'];
}
if (function_exists('login_check') && login_check() == true):
  if (isset($u) && $u->hasPrivilege('Configuracion')):
?>
    <!-- Side Overlay-->
    <aside id="side-overlay">
        <!-- Side Overlay Scroll Container -->
        <div id="side-overlay-scroll">
            <!-- Side Header -->
            <div class="side-header side-content">
                <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                <a class="boton_personalizado fa fa-times" type="button" data-toggle="layout" data-action="side_overlay_close">

                </a>
                <span>
                    <?php  ?>
                    <?php //$one->get_avatar('10', '', 32); ?>
                    <span class="font-w600 push-10-l"><?php echo $user->persona['user_pref']." ".$nombre." ".$user->persona['user_ap1']; ?></span>
                </span>
            </div>
            <!-- END Side Header -->

            <!-- Side Content -->
            <div class="side-content remove-padding-t">
                <!-- Side Overlay Tabs -->
                <div class="block pull-r-l border-t">
                    <ul class="nav nav-tabs nav-tabs-alt nav-justified "  data-toggle="tabs">
                        <li class="active" id="noti">
                            <a  href="#tabs-side-overlay-overview"><i id="bell" class="fa fa-fw fa-bell"></i> Notificaciones</a>
                        </li>
                        <li>
                            <a href="#tabs-side-overlay-sales"><i class="fa fa-fw fa-line-chart"></i> Estadísticas</a>
                        </li>
                    </ul>
                    <div class="block-content tab-content">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade fade-right in active" id="tabs-side-overlay-overview">
                            <!-- Activity -->
                            <div class="block pull-r-l">
                                <div class="block-header bg-gray-lighter">
                                    <ul class="block-options">
                                        <li>
                                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                                        </li>
                                        <li>
                                            <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                        </li>
                                    </ul>
                                    <h3 class="block-title">Actividad Reciente</h3>
                                </div>
                                <div class="block-content">
                                    <!-- Activity List -->
                                    <ul class="list list-activity pull-r-l">
                                        <?php
                                        if ($_SESSION['role'] == 'almacen'){
                                            $errores = productos_error_notificaciones();
                                            foreach ($errores as $key => $error){
                                                if ($error['error'] > 0){
                                                    echo '<li>';
                                                    echo '<i class="fa fa-circle text-danger"></i>';
                                                    echo '<div class="font-w600">Error de inventario</div>';
                                                    echo '<div><a href="inicio.php?ref=_26&id='.$key.'">'.$error['renglon'].'.'.$error['codigo'].' - '.$error['producto'].'</a></div>';
                                                    echo '</li>';
                                                    echo "\r\n";
                                                }
                                            }
                                        }
                                        ?>
                                    </ul>
                                    <!-- END Activity List -->

                                    <?php
                                    if (function_exists('login_check') && login_check() == true):
                                      if (isset($u) && $u->hasPrivilege('Configuracion')):
                                        ?>

                                        <ul class="dropdown-menu1"></ul>
                                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                                        <script src="../herramientas/administrador/js/notificaciones.js"></script>


                                        <?php
                                      endif;
                                    endif;
                                    ?>
                                </div>
                            </div>
                            <!-- END Activity -->

                        </div>
                        <!-- END Overview Tab -->

                        <!-- Sales Tab -->
                        <div class="tab-pane fade fade-left" id="tabs-side-overlay-sales">
                            <div class="block pull-r-l">
                                <!-- Stats
                                <div class="block-content pull-t">
                                    <div class="row items-push">
                                        <div class="col-xs-6">
                                            <div class="font-w700 text-gray-darker text-uppercase">Egresos</div>
                                            <a class="h3 font-w300 text-primary" href="javascript:void(0)">2200</a>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="font-w700 text-gray-darker text-uppercase">Balance</div>
                                            <a class="h3 font-w300 text-primary" href="javascript:void(0)">Q 320,054.35</a>
                                        </div>
                                    </div>
                                </div>
                                END Stats -->

                                <!-- Today
                                <div class="block-content block-content-full block-content-mini bg-gray-lighter">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <span class="font-w600 font-s13 text-gray-darker text-uppercase">Hoy</span>
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <span class="font-s13 text-muted">Q 996</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <ul class="list list-activity pull-r-l">
                                        <li>
                                            <i class="fa fa-circle text-success"></i>
                                            <div class="font-w600">Nueva requisicion - Q249</div>
                                            <div><small class="text-muted">Hace 3 minutos</small></div>
                                        </li>
                                    </ul>
                                </div>
                                END Today -->

                                <!-- Yesterday
                                <div class="block-content block-content-full block-content-mini bg-gray-lighter">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <span class="font-w600 font-s13 text-gray-darker text-uppercase">Ayer</span>
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <span class="font-s13 text-muted">Q 765</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <ul class="list list-activity pull-r-l">
                                        <li>
                                            <i class="fa fa-circle text-danger"></i>
                                            <div class="font-w600">Nueva Factura - Q500</div>
                                            <div><small class="text-muted">Hace 28 horas</small></div>
                                        </li>
                                    </ul>
                                </div>
                                END Yesterday -->

                                <!-- More -->
                                <div class="text-center">
                                    <small><a href="javascript:void(0)">Ver más..</a></small>
                                </div>
                                <!-- END More -->
                            </div>
                        </div>
                        <!-- END Sales Tab -->
                    </div>
                </div>
                <!-- END Side Overlay Tabs -->
            </div>
            <!-- END Side Content -->
        </div>
        <!-- END Side Overlay Scroll Container -->
    </aside>
    <!-- END Side Overlay -->

    <?php
    endif;
    endif;
    ?>
