<?php
include_once '../../../../inc/functions.php';

sec_session_start();
$u = usuarioPrivilegiado();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';

    $id_persona=$_SESSION['id_persona'];
    $clased = new documento;
    $acta_id = $_POST['acta_id'];
    $acta = array();
    $data = array();
    $tipo = '';
    if($u->hasPrivilege(302)){
      $acta = $clased->get_acta_compra_by_id($acta_id);
    }

    $html = '<html>
    <body>
     <div id="fromHTMLtestdiv" style="text-align: justify;">En la ciudad de Guatemala, siendo las : data.i_hora horas con data.i_minuto minutos del día data.i_fecha,
      reunidos en la sede de la Secretaría de Asuntos Administrativos y de Seguridad de la Presidencia de la República, la que en adelante podrá denominarse SAAS,
      ubicada en la 6a. avenida "A" 4-18 Callejón Manchén, zona 1 del municipio de Guatemala, departamento de Guatemala, nosotros: data.director
      quien actúa en su calidad de Subdirector Administrativo y Financiero de la SAAS, data.jefe quien actúa en su calidad de Jefe de compras y data.tecnico
      quien actúa en su calidad de Técnico de Compras, procedemos a dejar constancia de: **${data.jefe}**: la adquisición se efectuará por medio del pedido número data.pyr ;
      </div>
    </body>
    </html>';
    $tipo_adjudicacion = '';
    if($acta['id_tipo_adjudicacion'] == 1){
      $tipo_adjudicacion = 'ofrecer mejor calidad';
    }else if($acta['id_tipo_adjudicacion'] == 2){
      $tipo_adjudicacion = 'ofrecer mejor tiempo de entrega';
    }else if($acta['id_tipo_adjudicacion'] == 3){
      $tipo_adjudicacion = 'ofrecer mejor calidad y tiempo de entrega';
    }else if($acta['id_tipo_adjudicacion'] == 4){
      $tipo_adjudicacion = 'es el único';
    }

    $data = array(
      'DT_RowId'=>$acta['acta_id'],
      'acta_id'=>$acta['acta_id'],
      'acta_fecha'=>$acta['acta_fecha'],
      'i_fecha'=>fechaCastellano(date('Y-m-d',strtotime($acta['acta_fecha']))),
      'i_hora'=>date('H',strtotime($acta['acta_fecha'])),
      'i_minuto'=>date('i',strtotime($acta['acta_fecha'])),

      'f_fecha'=>fechaCastellano(date('Y-m-d',strtotime($acta['acta_finalizacion']))),
      'f_hora'=>date('H',strtotime($acta['acta_finalizacion'])),
      'f_minuto'=>date('i',strtotime($acta['acta_finalizacion'])),
      'acta_finalizacion'=>$acta['acta_finalizacion'],
      'director'=>str_replace('cruz', 'Cruz', $acta['director']),
      'jefe'=>str_replace('cruz', 'Cruz', $acta['jefe']),
      'tecnico'=>str_replace('cruz', 'Cruz', $acta['tecnico']),
      'pyr'=>$acta['pyr'],
      'nit_proveedor'=>$acta['nit_proveedor'],
      'proveedor'=>$acta['Prov_nom'],
      'monto'=>'Q.'.number_format($acta['acta_monto'],2,'.',','),
      'tipo_pago'=>($acta['id_tipo_pago'] == 1) ? 'Cheque' : 'Acreditamiento',
      'acta_justificacion'=>$acta['acta_justificacion'],
      'tipo_adjudicacion'=>$tipo_adjudicacion,
      'id_tipo_compra'=>($acta['id_tipo_compra'] == 1) ? 'la presente compra' : 'el presente servicio',
      'html'=>$html
    );

    echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
