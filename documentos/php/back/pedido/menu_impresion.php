<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

    $ped_tra = $_POST['ped_tra'];
    $tipo = $_POST['tipo'];

    include_once '../../back/functions.php';
    $clased = new documento;
    $pedido=$clased->get_pedido_by_id($ped_tra);

    $d = $clased->get_justificacion_by_ped_tra($ped_tra);

    echo '';


?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">


    </script>

    <div>

      <ul class="list-unstyled mb-0">
        <?php if($tipo == 1){
          if($clased->get_aprobacion_plani_by_pedido($ped_tra) == 'true' || $pedido['Ped_fop'] < '2022-01-28 15:30:00'){
          ?>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3 text-info" onclick="impresion_pedido(<?php echo $ped_tra?>)">
              <i class="fa fa-print mr-2"></i> Pedido y Remesa
            </a>
          </li>
        <?php }
        else{
          echo '<div class="row"><span class="col-sm-12 text-center"><br>El PYR no ha sido aprobado en la <br> Unidad de Planificación<hr></span> </div>';
        }
          if($pedido['Ped_justificacion']>0){

            ?>

          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3 text-info" onclick="justificacion_reporte(<?php echo $d['docto_id']?>)">
              <i class="fa fa-print mr-2"></i> Justificación
            </a>
          </li>
        <?php }?>
        <?php } else{?>

          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3 text-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/pedidos/pedido_detalle.php?ped_tra=<?php echo $ped_tra?>">
              <i class="fa fa-pen mr-2"></i> Pedido y Remesa
            </a>
          </li>
          <?php if(empty($d['docto_id'])){?>
          <!--<li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3 text-info" data-toggle="modal" data-target="#modal-remoto" href="documentos/php/front/justificacion/justificacion_nueva.php?ped_tra=<?php echo $ped_tra?>">
              <i class="fa fa-plus mr-2"></i> Generar Justificación
            </a>
          </li>-->
        <?php }else{?>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3 text-info" data-toggle="modal" data-target="#modal-remoto-lg" href="documentos/php/front/justificacion/justificacion_detalle.php?docto_id=<?php echo $d['docto_id']?>">
              <i class="fa fa-pen mr-2"></i> Justificación
            </a>
          </li>
        <?php }}?>

      </ul>


    </div>


    <?php
  }
}else{
    header("Location: index.php");
}
?>
