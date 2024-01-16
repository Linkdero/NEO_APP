<?php
    include_once '../../../../inc/functions.php';
    sec_session_start();
?>
<?php if (function_exists('verificar_session') && verificar_session() == true): ?>
    <?php
        include_once '../functions.php';

        $clase = new configuracion();

        $totales = array();
        $totales = $clase->get_listado();
        $data = array();


        foreach ($totales as $t){
          // $button = "<div class='btn-group btn-group-sm' role='group'>
          //             <span class='btn btn-sm btn-personalizado outline' data-toggle='modal' data-target='#modal-remoto-lg' href='directorio/php/front/telefonos/detalle_telefonos.php?id_persona={$t['id_persona']}' data-toggle='tooltip' data-placement='top' title='Mostrar contactos'>
          //             <i class='fa fa-eye'></i>
          //             </span>
          //             </div>";

          $sub_array = array(
            'renglon'=>$t['Ppr_Ren'],
            'codigo_ins'=>$t['Ppr_cod'],
            'nombre'=>strtoupper($clase->cleanString($t['Ppr_Nom'])),
            'caracteristicas'=>strtoupper($clase->cleanString($t['Ppr_Des'])),
            'presentacion'=>strtoupper($clase->cleanString($t['Ppr_Pres'])),
            'cantidad'=>strtoupper($clase->cleanString($t['Med_nom'])),
            'codigo_pre'=>$t['Ppr_codPre']
            );
            $data[]=$sub_array;
          }

        $results = array(
          "sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData"=>$data);

          echo json_encode($results);

        else:
          echo "<script type='text/javascript'> window.location='principal'; </script>";
        endif;
  
     ?>

