<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    $correlativo=$_POST['correlativo'];

    include_once '../../back/functions.php';
    $opciones=viaticos::get_opciones_menu($correlativo);
?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">


    </script>

    <div>

      <ul class="list-unstyled mb-0">
        <?php //if($opciones['usr_autoriza']>0){?>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="nombramiento_reporte(<?php echo $correlativo?>)">
              <i class="fa fa-print mr-2"></i> Nombramiento
            </a>
          </li>
          <?php if($opciones['id_pais']=='GT'){?>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_constancia_vacia()">
              <i class="fa fa-print mr-2"></i> Constancia vacía
            </a>
          </li>
        <?php } else{?>

          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_exterior_vacia()">
              <i class="fa fa-print mr-2"></i> Exterior vacía
            </a>
          </li>
        <?php }?>
        <?php if($opciones['anticipo']>0){?>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="imprimir_con_fecha(<?php echo $correlativo?>,1)">
              <i class="fa fa-print mr-2"></i> Anticipo
            </a>
          </li>
        <?php }?>
        <?php if($opciones['constancia']>0){?>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="imprimir_constancia(<?php echo $correlativo?>)">
              <i class="fa fa-print mr-2"></i> Constancia
            </a>
          </li>
        <?php }?>
        <?php if($opciones['exterior']>0){?>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_con_fecha(<?php echo $correlativo?>,2)">
              <i class="fa fa-print mr-2" ></i> Exterior
            </a>
          </li>

        <?php }?>
        <?php if($opciones['liquidacion']>0){?>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_con_fecha(<?php echo $correlativo?>,2)">
              <i class="fa fa-print mr-2"></i> Liquidacion
            </a>
          </li>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_con_fecha(<?php echo $correlativo?>,3)">
              <i class="fa fa-print mr-2"></i> Informe
            </a>
          </li>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_con_fecha(<?php echo $correlativo?>,4)">
              <i class="fa fa-print mr-2"></i> Nombramiento definitivo
            </a>
          </li>
        <?php }?>

      </ul>


    </div>


    <?php
  }
}else{
    header("Location: index.php");
}
?>
