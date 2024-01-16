<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    include_once '../../back/functions.php';
    $id_viatico=null;
    $id_persona=null;
    $id_renglon=null;

    $id_viatico = $_POST['id_viatico'];
    $id_persona = $_POST['id_persona'];
    $id_renglon = $_POST['id_renglon'];

    $clase= new viaticos();

    $horas = $clase->get_items(37);
    $s=$clase->get_estado_by_id($id_viatico);
    $empleado = $clase->get_empleado_datos_por_nombramiento($id_viatico,$id_persona);
    $form_constancia='';
    if($empleado['id_pais']=='GT'){
      if($empleado['nro_frm_vt_cons']==0){
        $form_constancia='No se le ha generado constancia nacional';
      }else{
        $form_constancia=$empleado['nro_frm_vt_cons'];
      }
    }else{
      if($empleado['nro_frm_vt_ext']==0){
        $form_constancia='No se le ha generado constancia exterior';
      }else{
        $form_constancia=$empleado['nro_frm_vt_ext'];
      }
    }

    echo json_encode($empleado['fecha_llegada_lugar']);

    //$response = array(
    	/*'DT_RowId'=>$s['vt_nombramiento'],*/
    	//echo $empleado['nombramiento'];
      //echo  $empleado['id_empleado'].'<br>';
      //echo $empleado['primer_nombre'].' '.$empleado['segundo_nombre'].' '.$empleado['tercer_nombre'].' '.$empleado['primer_apellido'].' '.$empleado['segundo_apellido'].' '.$empleado['tercer_apellido'].'<br>';
      echo '<div class="row bg-soft-light text-muted"><div class="col-sm-3">';
      echo (!empty($empleado['nro_cheque']))?'Nro. Cheque: <strong>'.$empleado['nro_cheque'].'</strong><br>':'';
      echo ($empleado['id_pais']=='GT')?'Q '.number_format($empleado['monto_asignado'], 2, ".", ","):'$ '.number_format($empleado['monto_asignado'], 2, ".", ",").' X '.number_format($empleado['tipo_cambio'], 2, ".", ",").' = Q '. number_format(($empleado['monto_asignado']*$empleado['tipo_cambio']), 2, ".", ",").'<br>';
      echo '<br> VA: '.$empleado['nro_frm_vt_ant'].'<br>';
      echo ($empleado['id_pais']=='GT')?'VC: ':'VE: ';
      echo $form_constancia.'<br>';
      echo 'VL: '.($empleado['nro_frm_vt_liq']!=0)?'VL: '.$empleado['nro_frm_vt_liq']:'No se le ha generado liquidación';
      echo '</div>';
      echo '<div class="col-sm-5">';
      echo '<table width="100%" style="width:100%">';
      echo '<thead><th></th><th><strong>Fecha</strong></th><th><strong>Hora</strong></th></thead>';
      echo '<tbody>';
      if($s['id_status']==938 && $empleado['bln_confirma']==1 || $s['id_status']==939 && $empleado['bln_confirma']==1){
        echo '<tr><td style="width:120px;">Salida de SAAS:</td><td style="width:120px"><span class="f_persona" data-pk="'.$id_viatico.'-'.$empleado['id_empleado'].'" data-name="1">'.fecha_dmy($empleado['fecha_salida_saas']).'</span></td><td><span class="h_persona">'.$empleado['h_salida_saas'].'</span></td></tr>';
        echo '<tr><td>Llegada lugar:</td><td><span class="f_persona" data-pk="'.$id_viatico.'-'.$empleado['id_empleado'].'" data-name="3">'.fecha_dmy($empleado['fecha_llegada_lugar']).'</span></td><td><span class="h_persona">'.$empleado['h_llegada_lugar'].'</span></td></tr>';
        echo '<tr><td>Salida lugar:</td><td><span class="f_persona" data-pk="'.$id_viatico.'-'.$empleado['id_empleado'].'" data-name="4">'.fecha_dmy($empleado['fecha_salida_lugar']).'</span></td><td><span class="h_persona">'.$empleado['h_salida_lugar'].'</span></td></tr>';
        echo '<tr><td>Llegada a SAAS:</td><td><span class="f_persona" data-pk="'.$id_viatico.'-'.$empleado['id_empleado'].'" data-name="2">'.fecha_dmy($empleado['fecha_regreso_saas']).'</span></td><td><span class="h_persona">'.$empleado['h_regreso_saas'].'</span></td></tr>';
      }else{
        echo '<tr><td style="width:120px">Salida de SAAS:</td><td style="width:120px">'.fecha_dmy($empleado['fecha_salida_saas']).'</td><td>'.$empleado['h_salida_saas'].'</td></tr>';
        echo '<tr><td>Llegada lugar:</td><td>'.fecha_dmy($empleado['fecha_llegada_lugar']).'</td><td>'.$empleado['h_llegada_lugar'].'</td></tr>';
        echo '<tr><td>Salida lugar:</td><td>'.fecha_dmy($empleado['fecha_salida_lugar']).'</td><td>'.$empleado['h_salida_lugar'].'</td></tr>';
        echo '<tr><td>Llegada a SAAS:</td><td >'.fecha_dmy($empleado['fecha_regreso_saas']).'</td><td>'.$empleado['h_regreso_saas'].'</td></tr>';
      }
      echo '</tbody>';
      echo '</table>';
      echo '</div>';


      echo '<div class="col-sm-4">';
      echo '<table style="float:right">';
      echo '<thead><th class="text-center"><strong>Tipo</strong></th><th class="text-center"><strong>Monto</strong></th></thead>';
      echo '<tr><td>Reintegro Hospedaje: </td><td class="text-right" style="width:100px">'.number_format($empleado['hospedaje'], 2, ".", ",").'</td></tr>';
      echo '<tr><td>Reintegro Alimentación: </td><td class="text-right" style="width:100px">'.number_format($empleado['reintegro_alimentacion'], 2, ".", ",").'</td></tr>';
      echo '<tr><td>Otros gastos: </td><td class="text-right" style="width:100px">'.number_format($empleado['otros_gastos'], 2, ".", ",").'</td></tr>';
      echo '</table></div></div>';

?>
<script>
$(document).ready(function(){
  $('.f_persona').editable({
    url: 'viaticos/php/back/viatico/update_fecha_persona.php',
    mode: 'popup',
    ajaxOptions: { dataType: 'json' },
    format: 'dd-mm-yyyy',
    viewformat: 'dd-mm-yyyy',
    datepicker: {
      weekStart: 1
    },
    type: 'date',
    display: function(value, response) {
      return false;   //disable this method
    },
    success: function(response, newValue) {

      if(response.msg=='Done'){
        $(this).text(response.valor_nuevo);
      }
    }
  });

  $('.h_persona').editable({
    url: 'viaticos/php/back/viatico/update_hora_persona.php',
    mode: 'popup',
    ajaxOptions: { dataType: 'json' },
    type: 'select',
    source:source2,
    display: function(value, response) {
      return false;   //disable this method
    },
    success: function(response, newValue) {
      if(response.msg=='Done'){
        $(this).text(response.valor_nuevo);
      }
    }
  });
})
var source2=[
  {value: "", text: "- Seleccionar -"},
  {value: "946", text: "00:00 HORAS"},
  {value: "947", text: "00:30 HORAS"},
  {value: "948", text: "01:00 HORAS"},
  {value: "949", text: "01:30 HORAS"},
  {value: "950", text: "02:00 HORAS"},
  {value: "951", text: "02:30 HORAS"},
  {value: "952", text: "03:00 HORAS"},
  {value: "954", text: "04:00 HORAS"},
  {value: "953", text: "03:30 HORAS"},
  {value: "955", text: "04:30 HORAS"},
  {value: "956", text: "05:00 HORAS"},
  {value: "957", text: "05:30 HORAS"},
  {value: "958", text: "06:00 HORAS"},
  {value: "959", text: "06:30 HORAS"},
  {value: "960", text: "07:00 HORAS"},
  {value: "961", text: "07:30 HORAS"},
  {value: "962", text: "08:00 HORAS"},
  {value: "963", text: "08:30 HORAS"},
  {value: "964", text: "09:00 HORAS"},
  {value: "965", text: "09:30 HORAS"},
  {value: "966", text: "10:00 HORAS"},
  {value: "967", text: "10:30 HORAS"},
  {value: "968", text: "11:00 HORAS"},
  {value: "969", text: "11:30 HORAS"},
  {value: "970", text: "12:00 HORAS"},
  {value: "971", text: "12:30 HORAS"},
  {value: "972", text: "13:00 HORAS"},
  {value: "973", text: "13:30 HORAS"},
  {value: "974", text: "14:00 HORAS"},
  {value: "975", text: "14:30 HORAS"},
  {value: "976", text: "15:00 HORAS"},
  {value: "977", text: "15:30 HORAS"},
  {value: "978", text: "16:00 HORAS"},
  {value: "979", text: "16:30 HORAS"},
  {value: "980", text: "17:00 HORAS"},
  {value: "981", text: "17:30 HORAS"},
  {value: "982", text: "18:00 HORAS"},
  {value: "983", text: "18:30 HORAS"},
  {value: "984", text: "19:00 HORAS"},
  {value: "985", text: "19:30 HORAS"},
  {value: "986", text: "20:00 HORAS"},
  {value: "987", text: "20:30 HORAS"},
  {value: "988", text: "21:00 HORAS"},
  {value: "989", text: "21:30 HORAS"},
  {value: "990", text: "22:00 HORAS"},
  {value: "991", text: "22:30 HORAS"},
  {value: "992", text: "23:00 HORAS"},
  {value: "993", text: "23:30 HORAS"}
];
</script>
<?php
}
else{
 include('inc/401.php');
}
}
else{
 header("Location: index.php");
}
?>
