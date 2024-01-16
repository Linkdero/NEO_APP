<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions_plaza.php';

    $plazas = array();
    $id_plaza='';
  	if(isset($_GET['id_plaza'])){
  		$id_plaza=$_GET['id_plaza'];
  	}
    $clasep = new plaza;
    $p=$clasep->get_plaza_by_id_sueldo($id_plaza);
    $items = $clasep->get_sueldos_by_id_plaza($p['id_sueldo_plaza']);
    $data = array();

    $sueldos = array();
    if($items["status"] == 200){
        foreach($items["data"] as $i){
            //$response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
            $sub_array = array(
              'id_item'=>$i['id_concepto'],
              'item_string'=>$i['descripcion'],
              'bln_confirma'=>(!empty($i['monto_p'])) ? true : false,
              'monto_n'=>(!empty($i['monto_p'])) ? $i['monto_p'] : ''
            );
            $sueldos[] = $sub_array;
        }
    }else{
        $response = $items["msg"];
    }


    $data = array(
      'id_plaza'=>$p['id_plaza'],
      'cod_plaza'=>$p['cod_plaza'],
      'cod_puesto'=>$p['codigo_puesto_oficial'],
      'estado'=>$p['estado_plaza'],
      'cargo'=>$p['descripcion_plaza'],
      'secretaria_n'=>$p['nombre_secretaria_presupuestario'],
      'subsecretaria_n'=>$p['nombre_subsecretaria_presupuestario'],
      'direccion_n'=>$p['nombre_direccion_presupuestaria'],
      'subdireccion_n'=>(!empty($p['nombre_subdireccion_presupuestaria']))?$p['nombre_subdireccion_presupuestaria']:'Sin asignación',
      'departamento_n'=>(!empty($p['nombre_depto_presupuestario']))?$p['nombre_depto_presupuestario']:'Sin asignación',
      'seccion_n'=>(!empty($p['nombre_seccion_presupuestaria']))?$p['nombre_seccion_presupuestaria']:'Sin asignación',
      'sueldo'=>number_format($p['monto_sueldo_plaza'],2,'.',','),
      'sueldo_base'=>$p['monto_sueldo_base'],
      'partida_presupuestaria'=>$p['partida_presupuestaria'],

      'puesto_n'=>$p['codigo_puesto_presupuestario'],

      'Puesto_presupuestario'=>$p['Puesto_presupuestario'],
      'cod_estado'=>$p['Cod_estado'],
      //categoria_n



      'codigo_puesto_presupuestario'=>(!empty($p['codigo_puesto_presupuestario'])) ? $p['codigo_puesto_presupuestario'] : NULL,
      'id_jerarquia_presupuestario'=>(!empty($p['id_jerarquia_presupuestario'])) ? $p['id_jerarquia_presupuestario'] : NULL,
      'nivel_n'=>(!empty($p['id_jerarquia_presupuestario'])) ? $p['id_jerarquia_presupuestario'] : NULL,
      'nivel_presupuestario_ubicacion'=>(!empty($p['nivel_presupuestario_ubicacion'])) ? $p['nivel_presupuestario_ubicacion'] : NULL,
      'id_secretaria_presupuestario'=>(!empty($p['id_secretaria_presupuestario'])) ? $p['id_secretaria_presupuestario'] : NULL,
      'subsecretaria_n'=>(!empty($p['id_subsecretaria_presupuestaria'])) ? $p['id_subsecretaria_presupuestaria'] : NULL,
      'direccion_n'=>(!empty($p['id_direccion_presupuestaria'])) ? $p['id_direccion_presupuestaria'] : NULL,
      'subdireccion_n'=>(!empty($p['id_subdireccion_presupuestaria'])) ? $p['id_subdireccion_presupuestaria'] : NULL,
      'departamento_n'=>(!empty($p['id_depto_presupuestario'])) ? $p['id_depto_presupuestario'] : NULL,
      'seccion_n'=>(!empty($p['id_seccion_presupuestario'])) ? $p['id_seccion_presupuestario'] : NULL,

      'id_jerarquia_funcional'=>(!empty($p['id_jerarquia_funcional'])) ? $p['id_jerarquia_funcional'] : NULL,
      'nivel_f'=>(!empty($p['nivel_funcional'])) ? $p['nivel_funcional'] : NULL,
      'id_secretaria_funcional'=>(!empty($p['id_secretaria_funcional'])) ? $p['id_secretaria_funcional'] : NULL,
      'subsecretaria_f'=>(!empty($p['id_subsecretaria_funcional'])) ? $p['id_subsecretaria_funcional'] : NULL,
      'direccion_f'=>(!empty($p['id_direccion_funcional'])) ? $p['id_direccion_funcional'] : NULL,
      'subdireccion_f'=>(!empty($p['id_subdireccion_funcional'])) ? $p['id_subdireccion_funcional'] : NULL,
      'departamento_f'=>(!empty($p['id_depto_funcional'])) ? $p['id_depto_funcional'] : NULL,
      'seccion_f'=>(!empty($p['id_seccion_funcional'])) ? $p['id_seccion_funcional'] : NULL,
      'puesto_f'=>(!empty($p['id_puesto_funcional'])) ? $p['id_puesto_funcional'] : NULL,


      'id_sueldo_plaza'=>(!empty($p['id_sueldo_plaza'])) ? $p['id_sueldo_plaza'] : NULL,

      'sueldos'=>$sueldos

    );

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
