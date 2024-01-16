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
    'imagen'=>'./assets/img/brands-sm/phonebook.svg',
    'texto'=>'Teléfonos',
    'option'=>3
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/maps-and-flags.svg',
    'texto'=>'Direcciones',
    'option'=>4
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/medical-history.svg',
    'texto'=>'Datos Médicos',
    'option'=>5
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/id-card.svg',
    'texto'=>'Documentos Personales',
    'option'=>6
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/camera.svg',
    'texto'=>'Fotografías',
    'option'=>7
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/graduation-cap.svg',
    'texto'=>'Escolaridad',
    'option'=>8
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/network.svg',
    'texto'=>'Referencias',
    'option'=>9
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/police-hat.svg',
    'texto'=>'Servicios',
    'option'=>10
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/title.svg',
    'texto'=>'Cursos / Capacitaciones',
    'option'=>11
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/credit-cards.svg',
    'texto'=>'Cuentas Bancarias',
    'option'=>12
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/resume.svg',
    'texto'=>'Experiencia',
    'option'=>13
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/car.svg',
    'texto'=>'Vehículos',
    'option'=>14
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/gun.svg',
    'texto'=>'Armas',
    'option'=>15
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/wallet.svg',
    'texto'=>'Ingresos y Egresos',
    'option'=>16
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/house.svg',
    'texto'=>'Bienes',
    'option'=>17
  );
  $data[]=$sub_array;
  $sub_array =array(
    'imagen'=>'./assets/img/brands-sm/hammer.svg',
    'texto'=>'Datos Legales',
    'option'=>18
  );
  $data[]=$sub_array;

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
