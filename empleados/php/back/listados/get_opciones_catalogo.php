<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';

  $id_persona='';
	if(isset($_GET['id_persona'])){
		$id_persona=$_GET['id_persona'];
	}

  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/school.svg',
    'texto'=>'Cursos y Centros de Estudio',
    'option'=>101,
    'tipo'=>4
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/world.svg',
    'texto'=>'Direcciones',
    'option'=>102,
    'tipo'=>3
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/employees.png',
    'texto'=>'Puestos',
    'option'=>103,
    'tipo'=>5
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/education.png',
    'texto'=>'Cursos',
    'option'=>105,
    'tipo'=>6
  );
  $data[]=$sub_array;
/*
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/bank.png',
    'texto'=>'Bancos',
    'option'=>103,
    'tipo'=>6
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/education.png',
    'texto'=>'Cursos',
    'option'=>103,
    'tipo'=>6
  );
  $data[]=$sub_array;*/
  /*$sub_array =array(
    'imagen'=>'./assets/img/brands-sm/medical-history.svg',
    'texto'=>'Datos Médicos',
    'option'=>103
  );*/
  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
