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
        ,i.bien_id
        ,id_persona_solicita
        ,f.primer_nombre +' '+f.segundo_nombre+ ' '+f.primer_apellido+' '+f.segundo_apellido as nombre
        ,f.dir_funcional
        ,i.bien_descripcion_completa
        ,p.primer_nombre +' '+p.segundo_nombre+ ' '+p.primer_apellido+' '+p.segundo_apellido as tecnico
        ,d.id_tecnico
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
        LEFT JOIN Tickets.dbo.diagnostico_detalle  dd on d.id_diagnostico = dd.id_diagnostico
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
                "bien_descripcion_completa" => str_replace(array("\r", "\n"), ' ', $d["bien_descripcion_completa"]),
                "tecnico" => $d["tecnico"],
                "fecha_solicitado" => $fecha_solicitado,
                "fecha_finalizado" => $fecha_finalizado,
                "evaluacion" => $d["evaluacion"],
                "recomendacion" => $d["recomendacion"],
                "id_estado" => $d["id_estado"],
                "estado" => $d["estado"],
                "id_correlativo" => $d["id_correlativo"],
                "bien_sicoin_code" => $d["bien_sicoin_code"],
                "bien_id" => $d["bien_id"],
                "id_tecnico" => $d["id_tecnico"],
            );
        }

        echo json_encode($data);
        return $data;
    }

    public static function setPDFDiagnostico()
    {
        try {
            $archivo = $_POST['archivo'];
            $id = $_POST['id'];
            $fecha = date("Y-m-d H:i:s");
            $pdo = Database::connect_sqlsrv();

            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT COUNT(id_diagnostico) as cantidad_diagnosticos
            FROM Tickets.dbo.diagnostico_documento
            WHERE id_diagnostico = ?";

            $p = $pdo->prepare($sql);

            $p->execute(array($id));

            $diagnostico = $p->fetch();
            $diagnostico = $diagnostico["cantidad_diagnosticos"];
            $yes = '';
            if ($diagnostico) {
                $sql = "UPDATE Tickets.dbo.diagnostico_documento
                SET pdf = ?, fecha = ?
                WHERE id_diagnostico = ?";

                $p = $pdo->prepare($sql);
                $p->execute(array($archivo, $fecha, $id));
                $yes = array('msg' => 'OK', 'id' => 1, 'message' => '¡Actualización de Documento Exitoso!');

            } else {
                $sql = "INSERT INTO Tickets.dbo.diagnostico_documento (id_diagnostico,pdf,fecha)
                VALUES (?,?,?)";
                $p = $pdo->prepare($sql);
                $p->execute(array($id, $archivo, $fecha));
                $yes = array('msg' => 'OK', 'id' => 1, 'message' => 'Inyección de Documento Exitoso!');

            }

            $pdo->commit();

            echo json_encode($yes);
        } catch (PDOException $e) {
            // Manejo de errores en la base de datos
            $pdo->rollBack();

            $error = array('msg' => 'ERROR', 'id' => $e);
            echo json_encode($error);
        } catch (Exception $e2) {
            // Otro tipo de errores
            $error = array('msg' => 'ERROR', 'id' => $e2);
            echo json_encode($error);
        } finally {
            Database::disconnect_sqlsrv();
        }
    }
    static function getPDFDiagnostico()
    {
        $id = $_GET['id'];

        $pdo = Database::connect_sqlsrv();

        $pdo->beginTransaction();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT pdf
        FROM Tickets.dbo.diagnostico_documento
        WHERE id_diagnostico = ?";

        $p = $pdo->prepare($sql);

        $p->execute(array($id));

        $pdf = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        foreach ($pdf as $p) {
            $data = array(
                "pdf" => $p["pdf"],
            );
        }

        echo json_encode($data);
        return $data;
    }

    public static function setDiagnosticoImpreso()
    {
        try {
            $id = $_POST['id'];
            $bien = $_POST['bien'];
            $id_persona = $GLOBALS['emp'];
            $fecha = date("Y-m-d H:i:s");
            $pdo = Database::connect_sqlsrv();
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO Tickets.dbo.diagnostico_impresion_bitacora
            (id_diagnostico ,id_bien ,fecha,id_persona)
            VALUES (?,?,?,?)";
            $p = $pdo->prepare($sql);
            $p->execute(array($id, $bien, $fecha, $id_persona[1]));
            $yes = array('msg' => 'OK', 'id' => 1, 'message' => '¡Bitacora de Fecha Agregada!');
            $pdo->commit();
            echo json_encode($yes);
        } catch (PDOException $e) {
            // Manejo de errores en la base de datos
            $pdo->rollBack();

            $error = array('msg' => 'ERROR', 'id' => $e);
            echo json_encode($error);
        } catch (Exception $e2) {
            // Otro tipo de errores
            $error = array('msg' => 'ERROR', 'id' => $e2);
            echo json_encode($error);
        } finally {
            Database::disconnect_sqlsrv();
        }
    }

    static function getBitacoraImpresiones()
    {
        $id = $_GET['id'];

        $pdo = Database::connect_sqlsrv();

        $pdo->beginTransaction();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT
        ROW_NUMBER() OVER (ORDER BY dib.id_diagnostico) as correlativo,
        dib.id_diagnostico as id,
        i.bien_sicoin_code as bien,
        CAST(DAY(fecha) AS VARCHAR(2)) + ' ' +
        CASE DATENAME(MONTH, fecha)
            WHEN 'January' THEN 'de Enero de'
            WHEN 'February' THEN 'de Febrero de'
            WHEN 'March' THEN 'de Marzo de'
            WHEN 'April' THEN 'de Abril de'
            WHEN 'May' THEN 'de Mayo de'
            WHEN 'June' THEN 'de Junio de'
            WHEN 'July' THEN 'de Julio de'
            WHEN 'August' THEN 'de Agosto de'
            WHEN 'September' THEN 'de Septiembre de'
            WHEN 'October' THEN 'de Octubre de'
            WHEN 'November' THEN 'de Noviembre de'
            WHEN 'December' THEN 'de Diciembre de'
        END + ' ' + CAST(YEAR(fecha) AS VARCHAR(4)) as fecha_formateada,
        f.nombre,
        f.id_persona
    FROM Tickets.dbo.diagnostico_impresion_bitacora AS dib
    LEFT JOIN APP_INVENTARIO.dbo.InventarioBien as i on dib.id_bien = i.bien_id
    LEFT JOIN SAAS_APP.dbo.xxx_rrhh_Ficha as f on dib.id_persona = f.id_persona
    WHERE id_diagnostico = ?
    ORDER BY fecha DESC;
    
    ";

        $p = $pdo->prepare($sql);

        $p->execute(array($id));

        $bitacora = $p->fetchAll(PDO::FETCH_ASSOC);
        $subArray = array(); // Inicializa $subArray como un arreglo vacío

        foreach ($bitacora as $b) {
            $data = array(
                "correlativo" => $b["correlativo"],
                "id" => $b["id"],
                "bien" => $b["bien"],
                "fecha" => (!empty($b["fecha_formateada"])) ? $b["fecha_formateada"] : "Pendiente",
                "nombre" => $b["nombre"],
                "id_persona" => $b["id_persona"],
            );
            $subArray[] = $data;
        }

        if ($subArray) {
            echo json_encode($subArray);
            return $subArray;
        }

    }
}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        case 1:
            Detalle::getDetalleDiagnostico();
            break;

        case 2:
            Detalle::setPDFDiagnostico();
            break;

        case 3:
            Detalle::getPDFDiagnostico();
            break;

        case 4:
            Detalle::setDiagnosticoImpreso();
            break;

        case 5:
            Detalle::getBitacoraImpresiones();
            break;
    }
}