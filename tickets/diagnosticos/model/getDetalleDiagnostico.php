<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);


class Detalle
{
    public $bien;
    //public $opcion = 1;
    protected function __construct()
    {
        $this->bien = array();
    }

    //Opcion 1
    static function getDetalleDiagnostico()
    {
        $idDiagnostico = $_GET["id"];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT d.descripcion
        ,id_persona_solicita
        ,f.primer_nombre +' '+f.segundo_nombre+ ' '+f.primer_apellido+' '+f.segundo_apellido as nombre
        ,f.dir_funcional
        ,i.bien_descripcion_completa
        ,p.primer_nombre +' '+p.segundo_nombre+ ' '+p.primer_apellido+' '+p.segundo_apellido as tecnico
        ,fecha_solicitado
        ,fecha_finalizado
        ,dd.evaluacion
        ,dd.recomendacion
        ,d.id_estado
		,e.estado
		,dd.id_correlativo
		,i.bien_sicoin_code
        FROM Tickets.dbo.diagnostico as d
        LEFT JOIN SAAS_APP.dbo.xxx_rrhh_Ficha as f on d.id_persona_solicita = f.id_persona
        LEFT JOIN SAAS_APP.dbo.rrhh_persona as p on d.id_tecnico = p.id_persona
        LEFT JOIN APP_INVENTARIO.dbo.InventarioBien as i on d.id_bien = i.bien_id
        LEFT JOIN Tickets.dbo.diagnostico_detalle as dd on d.id_diagnostico = dd.id_diagnostico
		LEFT JOIN Tickets.dbo.Estado as e on d.id_estado = e.id_estado
        where d.id_diagnostico = ?";

        $p = $pdo->prepare($sql);

        $p->execute(array($idDiagnostico));

        $diagnostico = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        foreach ($diagnostico as $d) {
            // Verificar si la fecha es válida antes de formatear
            if (strtotime($d["fecha_solicitado"]) !== false) {
                $fecha_solicitado = date("d/m/Y", strtotime($d["fecha_solicitado"]));
            } else {
                $fecha_solicitado = "";
            }

            if (strtotime($d["fecha_finalizado"]) !== false) {
                $fecha_finalizado = date("d/m/Y", strtotime($d["fecha_finalizado"]));
            } else {
                $fecha_finalizado = "";
            }

            $data = array(
                "descripcion" => $d["descripcion"],
                "id_persona_solicita" => $d["id_persona_solicita"],
                "nombre" => $d["nombre"],
                "dir_funcional" => $d["dir_funcional"],
                "bien_descripcion_completa" => $d["bien_descripcion_completa"],
                "tecnico" => $d["tecnico"],
                "fecha_solicitado" => $fecha_solicitado,
                "fecha_finalizado" => $fecha_finalizado,
                "evaluacion" => $d["evaluacion"],
                "recomendacion" => $d["recomendacion"],
                "id_estado" => $d["id_estado"],
                "estado" => $d["estado"],
                "id_correlativo" => $d["id_correlativo"],
                "bien_sicoin_code" => $d["bien_sicoin_code"],

            );
        }

        echo json_encode($data);
        return $data;
    }

}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        case 1:
            Detalle::getDetalleDiagnostico();
            break;
    }
}