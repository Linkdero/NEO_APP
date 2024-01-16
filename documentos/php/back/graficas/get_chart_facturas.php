<?php
include_once '../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
$u2 = usuarioPrivilegiado_acceso();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';

    $id_persona=$_SESSION['id_persona'];
    //$year = $_GET['year'];

    $clased = new documento;
    $facturas = array();
    $dias = array();
    $privilegio = evaluar_flags_by_sistema($_SESSION['id_persona'],8017);

    $tecnico = ($privilegio[9]['flag_es_menu'] == 1) ? $id_persona : 0;
    //echo $tecnico;
    $cheque = 0;

    //echo $tecnico;

    if($u->hasPrivilege(302) || $u2->accesoModulo(7851) || $u->hasPrivilege(318)){
      $facturas = $clased->get_facturas(0,0,0,date('Y'));
    }



    $fecha_i=strtotime(date('Y-m-d'));
    $day0=0;
    $day1=0;
    $day2=0;
    $day3=0;
    $day4=0;
    $day5=0;

    $dayC0=0;
    $dayC1=0;
    $dayC2=0;
    $dayC3=0;
    $dayC4=0;
    $dayC5=0;

    $day_0=0;
    $day_1=0;
    $day_2=0;
    $day_3=0;
    $day_4=0;
    $day_5=0;
    $day_6=0;
    $day_7=0;
    $day_8=0;
    $day_9=0;
    $day_10=0;

    foreach($facturas as $f){
      $inc = 0;
      $f_final='';
      $fecha_f='';
      $f_actual='';

      $fecha1=strtotime($f['factura_fecha']);

      $fecha_i = strtotime(date('Y-m-d'));

      $day = 0;
      $i=0;
      $total_days = (!empty($f['nog'])) ? 10 : 5;
      if(User::get_nombre_dia(date('Y-m-d', strtotime($f['factura_fecha']))) == 'Viernes'){
        //$fecha1=strtotime('+2 day ' . date('Y-m-d',$fecha1));
        //$i+=1;
      }else
      if(User::get_nombre_dia(date('Y-m-d', strtotime($f['factura_fecha']))) == 'Sabado'){
        $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
      }else if(User::get_nombre_dia(date('Y-m-d', strtotime($f['factura_fecha']))) == 'Domingo'){
        $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
      }
      if(date('Y-m-d',$fecha1) == '2022-05-02'){
        $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
        //$i+=1;
      }else{
        $i+=0;
      }
      do{
        if(date('Y-m-d',$fecha1) == '2022-09-15' || date('Y-m-d',$fecha1) == '2022-09-16'){
          $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
        }
        $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
        if((strcmp(date('D',$fecha1),'Sun')!=0) && (strcmp(date('D',$fecha1),'Sat')!=0)){
          $inc++;
          $f_final=date('Y-m-d',$fecha1);
        }
      }while($inc<$total_days); //validación para mostrar la fecha última para publicar

//echo $f_final;
      //if(date('Y-m-d')<=$f_final){
      $fecha_ini = date('Y-m-d',$fecha_i);
        do{
          if(date('Y-m-d',$fecha_i) == '2022-09-15' || date('Y-m-d',$fecha_i) == '2022-09-16'){
            //$fecha_i=strtotime('+1 day ' . date('Y-m-d',$fecha_i));
          }
          $fecha_i=strtotime('+1 day ' . date('Y-m-d',$fecha_i));
          if((strcmp(date('D',$fecha_i),'Sun')!=0) && (strcmp(date('D',$fecha_i),'Sat')!=0)){
            $i++;
            $f_actual=date('Y-m-d',$fecha_i);

          }

        }while($f_actual<=$f_final); // validación para calcular la cantidad de días
      if(date('Y-m-d') > $f_final){
        $i = 0;
      }else {
        //$i =99;
      }

      $d='días';

      $d='días';

      if(empty($f['nog'])){
        if(empty($f['modalidad_pago'])){
          if($i == 0){
            $day0 +=1;
          }
          if($i == 1){
            $day1 += 1;
          } if($i == 2){
            $day2 += 1;
          } if($i == 3){
            $day3 += 1;
          } if($i == 4){
            $day4 += 1;
          } if($i == 5){
            $day5 += 1;
          }
        }else{
          if($i == 0){
            $dayC0 +=1;
          }
          if($i == 1){
            $dayC1 += 1;
          } if($i == 2){
            $dayC2 += 1;
          } if($i == 3){
            $dayC3 += 1;
          } if($i == 4){
            $dayC4 += 1;
          } if($i == 5){
            $dayC5 += 1;
          }
        }

      }else{
        if($i == 0){
          $day_0 += 1;
        }
        if($i == 1){
          $day_1 += 1;
        } if($i == 2){
          $day_2 += 1;
        } if($i == 3){
          $day_3 += 1;
        } if($i == 4){
          $day_4 += 1;
        } if($i == 5){
          $day_5 += 1;
        } if($i == 6){
          $day_6 += 1;
        } if($i == 7){
          $day_7 += 1;
        } if($i == 8){
          $day_8 += 1;
        } if($i == 9){
          $day_9 += 1;
        } if($i == 10){
          $day_10 += 1;
        }
      }


    }

    $sub_array = array(
      'cantidad'=>$day0,
      'series'=>'0 días',
      'descripcion'=>'0d: '.$day0,
      'color'=>'#8F1D21'
    );

    $dias[] = ($day0 > 0) ?$sub_array : '';

    $sub_array = array(
      'cantidad'=>$day1,
      'series'=>'1 día',
      'descripcion'=>'1d: '.$day1,
      'color'=>'#DC3023'
    );
    $dias[] = ($day1 > 0) ?$sub_array : '';

    $sub_array = array(
      'cantidad'=>$day2,
      'series'=>'2 dias',
      'descripcion'=>'2d: '.$day2,
      'color'=>'#F62459'
    );
    $dias[] = ($day2 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day3,
      'series'=>'3 dias',
      'descripcion'=>'3d: '.$day3,
      'color'=>'#F4D03F'
    );
    $dias[] = ($day3 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day4,
      'series'=>'4 dias',
      'descripcion'=>'4d: '.$day4,
      'color'=>'#4B77BE'
    );
    $dias[] = ($day4 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day5,
      'series'=>'5 dias',
      'descripcion'=>'5d: '.$day5,
      'color'=>'#19B5FE'
    );
    $dias[] = ($day5 > 0) ?$sub_array : '';


    //dias cheque

    $sub_array = array(
      'cantidad'=>$dayC0,
      'series'=>'0 días',
      'descripcion'=>'0d: '.$dayC0,
      'color'=>'#8F1D21'
    );

    $diasC[] = ($dayC0 > 0) ?$sub_array : '';

    $sub_array = array(
      'cantidad'=>$dayC1,
      'series'=>'1 día',
      'descripcion'=>'1d: '.$dayC1,
      'color'=>'#DC3023'
    );
    $diasC[] = ($dayC1 > 0) ?$sub_array : '';

    $sub_array = array(
      'cantidad'=>$dayC2,
      'series'=>'2 dias',
      'descripcion'=>'2d: '.$dayC2,
      'color'=>'#F62459'
    );
    $diasC[] = ($dayC2 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$dayC3,
      'series'=>'3 dias',
      'descripcion'=>'3d: '.$dayC3,
      'color'=>'#F4D03F'
    );
    $diasC[] = ($dayC3 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$dayC4,
      'series'=>'4 dias',
      'descripcion'=>'4d: '.$dayC4,
      'color'=>'#4B77BE'
    );
    $diasC[] = ($dayC4 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$dayC5,
      'series'=>'5 dias',
      'descripcion'=>'5d: '.$dayC5,
      'color'=>'#19B5FE'
    );
    $diasC[] = ($dayC5 > 0) ?$sub_array : '';

    // dias nog
    $sub_array = array(
      'cantidad'=>$day_0,
      'series'=>'0 días',
      'descripcion'=>'0d: '.$day_0,
      'color'=>'#8F1D21'
    );

    $dias_nog[] = ($day_0 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day_1,
      'series'=>'1 día',
      'descripcion'=>'1d: '.$day_1,
      'color'=>'#DC3023'
    );
    $dias_nog[] = ($day_1 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day_2,
      'series'=>'2 dias',
      'descripcion'=>'2d: '.$day_2,
      'color'=>'#F62459'
    );
    $dias_nog[] = ($day_2 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day_3,
      'series'=>'3 dias',
      'descripcion'=>'3d: '.$day_3,
      'color'=>'#F4D03F'
    );
    $dias_nog[] = ($day_3 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day_4,
      'series'=>'4 dias',
      'descripcion'=>'4d: '.$day_4,
      'color'=>'#4B77BE'
    );
    $dias_nog[] = ($day_4 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day_5,
      'series'=>'5 dias',
      'descripcion'=>'5d: '.$day_5,
      'color'=>'#19B5FE'
    );
    $dias_nog[] = ($day_5 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day_6,
      'series'=>'6 día',
      'descripcion'=>'1d: '.$day_6,
      'color'=>'#F62459'
    );
    $dias_nog[] = ($day_6 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day_7,
      'series'=>'7 dias',
      'descripcion'=>'2d: '.$day_7,
      'color'=>'#FFA631'
    );
    $dias_nog[] = ($day_7 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day_8,
      'series'=>'8 dias',
      'descripcion'=>'3d: '.$day_8,
      'color'=>'#F4D03F'
    );
    $dias_nog[] = ($day_8 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day_9,
      'series'=>'9 dias',
      'descripcion'=>'4d: '.$day_9,
      'color'=>'#4B77BE'
    );
    $dias_nog[] = ($day_9 > 0) ?$sub_array : '';
    $sub_array = array(
      'cantidad'=>$day_10,
      'series'=>'10 dias',
      'descripcion'=>'5d: '.$day_10,
      'color'=>'#19B5FE'
    );
    $dias_nog[] = ($day_10 > 0) ?$sub_array : '';

    $data = array();

    $data = array(
      'dias'=>$dias,
      'dias_cheque'=>$diasC,
      'dias_n'=>$dias_nog
    );

    echo json_encode($data);






else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
