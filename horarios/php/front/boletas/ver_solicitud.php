<?php
include_once '../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
if (function_exists('verificar_session') && verificar_session()) {
  //if (usuarioPrivilegiado_acceso()->accesoModulo(7852)) {
    date_default_timezone_set('America/Guatemala');
    include_once '../../../../horarios/php/back/functions.php';
    $today = date("Y-m-d");
    $vac_id = $_GET['vac_id'];
    $BOLETA = new Boleta();
    $solicitud = $BOLETA->get_boleta_by_id($vac_id);
    $fsol = $BOLETA->full_fecha(strtotime($solicitud['vac_fch_sol']));
    $fini = $BOLETA->full_fecha(strtotime($solicitud['vac_fch_ini']));
    $ffin = $BOLETA->full_fecha(strtotime($solicitud['vac_fch_fin']));
    $fpre = $BOLETA->full_fecha(strtotime($solicitud['vac_fch_pre']));
    $vdia = $BOLETA->dias_horas($solicitud['vac_dia'], 1);
    $vgoz = $BOLETA->dias_horas($solicitud['vac_dia_goz'], 1);
    $vsub = $BOLETA->dias_horas($solicitud['vac_sub'], 1);
    $vds = $BOLETA->dias_horas($solicitud['vac_dia'], 2);
    $vhs = $BOLETA->dias_horas($solicitud['vac_dia'], 3);
    $vsol = $BOLETA->dias_horas($solicitud['vac_sol'], 1);
    $vpen = $BOLETA->dias_horas($solicitud['vac_pen'], 1);
    $est_id = $solicitud['est_id'];
    $fobs = $solicitud['vac_obs'];
    $nombre = $solicitud['nombre'];
    $puesto_n = $solicitud['p_nominal'];
    $puesto_f = $solicitud['p_funcional'];
    $dia_id = $solicitud['dia_id'];
    $dir_funcional = $solicitud['dir_general'];
    $estado = $BOLETA->set_estado_badge($est_id, $solicitud['est_des']);
    $id_persona = $solicitud['id_persona'];
    $cons = '';
    $impr = '';

    $plazas = $BOLETA->get_empleado_puesto_hist($id_persona, $solicitud['vac_fch_sol']);

    $puestoComisionado = getPuestoEmpleado($solicitud['id_persona'],$solicitud['vac_fch_ini']);
    $contratoComisionado = getContratoEmpleado($solicitud['id_persona'],$solicitud['vac_fch_ini']);

    if ($u->hasPrivilege(298)) {
      $impr = ($est_id == 5) ?  '<liss="list-inline-item">
    <span class="link-muted h3" onclick="imprimir_boleta(' . $vac_id . ')" title="Imprimir boleta">
      <i class="fa fa-print"></i>
    </span>
  </li>' : '';
      if ($today <= $solicitud['vac_fch_fin'] && $today >= $solicitud['vac_fch_ini'] && $est_id == 5) {
        $dest = '<div class="badge badge-info badge-pill">Vacaciones en proceso</div>';
      } elseif ($today > $solicitud['vac_fch_fin'] && $est_id == 5) {
        $dest = '<div class="badge badge-success badge-pill">Vacaciones finalizadas</div>';
        $cons = '<li class="list-inline-item">
      <span class="link-muted h3" onclick="imprimir_constancia(' . $vac_id . ')" title="Imprimir constancia">
        <i class="fas fa-file-signature"></i>
      </span>
    </li>';
      } else {
        $dest = '';
      }
    }

    $dirnom = (!empty($puestoComisionado['direccion'])) ? $puestoComisionado['direccion'] : $contratoComisionado['direccion'];
    $pueston = ($puestoComisionado['fecha_inicio'] > $contratoComisionado['fecha_inicio']) ? $puestoComisionado['pueston'] : $contratoComisionado['pueston'];
?>
    <!DOCTYPE html>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="horarios/js/functions.js"></script>
      <link rel='stylesheet' type='text/css' href='assets/js/plugins/lightpick/css/lightpick.css'>
      <script src='assets/js/plugins/lightpick/moment.min.js'></script>
      <script src='assets/js/plugins/lightpick/lightpick.js'></script>
      <link rel='stylesheet' href='assets/js/plugins/flatpickr/flatpickr.min.css'>
      <script src='assets/js/plugins/flatpickr/flatpickr.js'></script>
      <script src='assets/js/plugins/litepicker/litepicker.js'></script>
      <script src="assets/js/plugins/jspdf/jspdf.js"></script>
      <script src="assets/js/plugins/jspdf/vacaciones/impresiones.js"></script>
    </head>

    <body>
      <div class="modal-header">
        <h5 class="modal-title">Detalle de Solicitud <b> &nbsp; Boleta No.<?php echo $vac_id ?></b> &nbsp; <?php echo $estado ?> &nbsp; <?php echo $dest ?></h5>
        <ul class="list-inline ml-auto mb-0">
          <?php echo $cons ?>
          <?php echo $impr ?>
          <li class="list-inline-item">
            <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close" title="Cerrar">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <div class="col-sm-4">
                    <p>NOMBRE DEL TRABAJADOR:</p>
                  </div>
                  <div class="col-sm-8">
                    <p><b><?php echo $solicitud['nombre']; ?></b></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-4">
                    <p>PUESTO NOMINAL:</p>
                  </div>
                  <div class="col-sm-8">
                    <p><b><?php echo $pueston;?>
                </b></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-4">
                    <p>PUESTO FUNCIONAL:</p>
                  </div>
                  <div class="col-sm-8">
                    <!--<p><b><?php echo $solicitud['pue_des']; ?></b></p>-->
                    <p><b><?php echo (!empty($puestoComisionado['puesto_funcional'])) ? $puestoComisionado['puesto_funcional'] : 'TALLERISTA' ?></b></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-4">
                    <p>DIRECCION:</p>
                  </div>
                  <div class="col-sm-8">
                    <p><b><?php echo $dirnom; ?></b></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-4">
                    <p>FECHA DE SOLICITUD:</p>
                  </div>
                  <div class="col-sm-8">
                    <p><b><?php echo $fsol ?></b></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <table class="table table-sm table-bordered table-striped" width="100%">
                      <thead>
                        <tr>
                          <th colspan="5" class="text-left">
                            <div class="row">
                              <div class="col-sm-5">
                                <p>Período de vacaciones:</p>
                              </div>
                              <div class="col-sm-7">
                                <p><b><?php echo $solicitud['anio_des']; ?></b></p>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-5">
                                <p>Fecha en que gozará vacaciones:</p>
                              </div>
                              <div class="col-sm-7">
                                <p><b>del <?php echo $fini; ?> &nbsp; al <?php echo $ffin; ?></b></p>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-5">
                                <p>Fecha en que debe presentarse a sus labores:</p>
                              </div>
                              <div class="col-sm-7">
                                <p><b><?php echo $fpre; ?></b></p>
                              </div>
                            </div>
                          </th>
                        </tr>
                      </thead>
                      <tbody class="text-center">
                        <tr>
                          <td>Días que le corresponden</td>
                          <td>Días gozados con anterioridad</td>
                          <td>Subtotal de días pendientes</td>
                          <td>Días solicitados</td>
                          <td>Total de días pendientes</td>
                        </tr>
                        <tr>
                          <td><b><?php echo $vdia; ?></b></td>
                          <td><b><?php echo $vgoz; ?></b></td>
                          <td><b><?php echo $vsub; ?></b></td>
                          <td><b><?php echo $vsol; ?></b></td>
                          <td><b><?php echo $vpen; ?></b></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label>Observaciones:</label>
                  <div class=" input-group  has-personalizado">
                    <textarea rows="5" type="text" class=" form-control " placeholder="N/A" autocomplete="off" disabled><?php echo $fobs ?></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <input id="pnomi" name="pnomi" type="hidden" value="<?php if (!empty($plazas['puesto'])) {
                                                              echo $plazas['puesto'];
                                                            } else {
                                                              echo 'TALLERISTA';
                                                            } ?>">
        <input id="pfunc" name="pfunc" type="hidden" value="<?php echo $solicitud['pue_des']; ?>">
        <input id="dirg" name="dirg" type="hidden" value="<?php echo $solicitud['dir_des']; ?>">
        <?php if ($est_id == 1) :
          $sol = $solicitud['vac_fch_sol'];
          $ini = $solicitud['vac_fch_ini'];
          $fin = $solicitud['vac_fch_fin'];
          $pre = $solicitud['vac_fch_pre'];
        ?>

          <input id="vac_id" name="vac_id" type="hidden" value="<?php echo $vac_id; ?>">
          <input id="dia_id" name="dia_id" type="hidden" value="<?php echo $dia_id; ?>">
          <input id="fsol" name="fsol" type="hidden" value="<?php echo $sol; ?>">
          <input id="fini" name="fini" type="hidden" value="<?php echo $ini; ?>">
          <input id="ffin" name="ffin" type="hidden" value="<?php echo $fin; ?>">
          <input id="fpre" name="fpre" type="hidden" value="<?php echo $pre; ?>">
          <input id="fobs" name="fobs" type="hidden" value="<?php echo $fobs; ?>">
          <input id="nombre" name="nombre" type="hidden" value="<?php echo $nombre; ?>">
          <input id="puesto" name="puesto" type="hidden" value="<?php echo $puesto; ?>">
          <input id="dir_funcional" name="dir_funcional" type="hidden" value="<?php echo $dir_funcional; ?>">
          <input id="vds" name="vds" type="hidden" value="<?php echo $vds; ?>">
          <input id="vhs" name="vhs" type="hidden" value="<?php echo $vhs; ?>">
          <input id="dds" name="dds" type="hidden" value="<?php echo $solicitud['vac_dia']; ?>">
          <input id="vsol1" name="vsol1" type="hidden" value="<?php echo $solicitud['vac_sol']; ?>">
          <input id="fdecs1" name="fdecs1" type="hidden" value="0">



          <input id="vmod" type="hidden" value="false">
          <div class='row'>
            <div class='col-sm-1'>
            </div>
            <div class='col-sm-10'>
              <button class="btn btn-block btn-sm btn-info" onclick="modVacaciones()"><i class="fas fa-edit"></i> Modificar</button>
            </div>
            <div class='col-sm-1'>
            </div>
          </div>
          <br>
          <div class='row' id='vdias1'>
            <div class='col-sm-12'>
            </div>
          </div>
          <div class='row' id='vfechas1'>
            <div class='col-sm-12'>
            </div>
          </div>
          <div class='row' id='vobs1'>
            <div class='col-sm-12'>
            </div>
          </div>
        <?php endif; ?>
      </div>
  <?php
  /*} else {
    include('../inc/401.php');
  }*/
} else {
  header("Location: index");
}
  ?>
