<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

if (function_exists('verificar_session') && verificar_session() == true):
    $permisos = array();
    $array = evaluar_flags_by_sistema($_SESSION['id_persona'], 8931);

    $pos = $array[3]['id_persona'];
    $permisos = array(
        // 'soli' => ($array[0]['flag_es_menu'] == 1) ? true : false,

        // 'dirSoli' => ($array[1]['flag_es_menu'] == 1) ? true : false,

        'tecnico' => ($array[2]['flag_es_menu'] == 1) ? true : false,
        // 'tecnicoActua' => ($array[2]['flag_actualizar'] == 1) ? true : false,

        'jefe' => ($array[3]['flag_es_menu'] == 1) ? true : false,
        // 'jefeActua' => ($array[3]['flag_actualizar'] == 1) ? true : false,
        // 'jefeAcce' => ($array[3]['flag_acceso'] == 1) ? true : false,

        // 'dir' => ($array[4]['flag_es_menu'] == 1) ? true : false,
        // 'dirElim' => ($array[4]['flag_eliminar'] == 1) ? true : false,
        // 'dirActua' => ($array[4]['flag_actualizar'] == 1) ? true : false,
        // 'dirAcce' => ($array[4]['flag_acceso'] == 1) ? true : false,
        // 'dirAuto' => ($array[4]['flag_autoriza'] == 1) ? true : false,

        'desarrollo' => ($array[5]['flag_es_menu'] == 1) ? true : false,
        'soporte' => ($array[6]['flag_es_menu'] == 1) ? true : false,
        'radios' => ($array[7]['flag_es_menu'] == 1) ? true : false,
    );
else:
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;

class Diagnostico
{
    public $diagnostico;
    //public $opcion = 1;
    protected function __construct()
    {
        $this->diagnostico = array();
    }

    //Opcion 1
    static function getDiagnosticos()
    {
        $estado = $_GET["filtro"];
        $permisos = $GLOBALS['permisos'];
        $estado = isset($_GET["filtro"]) ? $_GET["filtro"] : null;
        $tecnicoId = isset($GLOBALS['pos']) ? $GLOBALS['pos'] : null;
        $departamento = '';

        if ($permisos["desarrollo"]) {
            $departamento = 352;
        } elseif ($permisos["soporte"]) {
            $departamento = 353;
        } elseif ($permisos["radios"]) {
            $departamento = 354;
        }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT id_diagnostico
        ,i.bien_sicoin_code
        ,descripcion
        , p.primer_nombre +' '+ p.segundo_nombre + ' '+ p.primer_apellido + ' ' + p.segundo_apellido as solicitante
        ,i.bien_descripcion
        , pp.primer_nombre +' '+ pp.segundo_nombre + ' '+ pp.primer_apellido + ' ' + pp.segundo_apellido as tecnico
        ,fecha_finalizado
        ,d.id_estado
        ,e.estado
          FROM  Tickets.dbo.diagnostico as d
          LEFT JOIN SAAS_APP.dbo.rrhh_persona AS p ON d.id_persona_solicita = p.id_persona
          LEFT JOIN SAAS_APP.dbo.rrhh_persona AS pp ON d.id_tecnico = pp.id_persona
          LEFT JOIN APP_INVENTARIO.dbo.InventarioBien AS i ON d.id_bien = i.bien_id
          LEFT JOIN Tickets.dbo.Estado AS e ON d.id_estado = e.id_estado
           ";

        if ($estado == 1) {
            if ($permisos["tecnico"]) {
                $sql .= 'WHERE d.id_tecnico = ?
                ORDER BY d.id_diagnostico DESC';
                $p = $pdo->prepare($sql);
                $p->execute(array($tecnicoId));
            } else if ($permisos["jefe"]) {
                $sql .= 'WHERE d.id_departamento_roll = ?
                ORDER BY d.id_diagnostico DESC';
                $p = $pdo->prepare($sql);
                $p->execute(array($departamento));
            } else {
                $p = $pdo->prepare($sql);
                $p->execute();
            }
        } else if ($estado == 3) {
            $sql .= 'WHERE (d.id_estado = ? OR  d.id_estado = ?) ';
            if ($permisos["tecnico"]) {
                $sql .= 'AND d.id_tecnico = ?
                ORDER BY d.id_diagnostico DESC';
                $p = $pdo->prepare($sql);
                $p->execute(array($estado, 7, $tecnicoId));
            } else if ($permisos["jefe"]) {
                $sql .= 'AND d.id_departamento_roll = ?
                ORDER BY d.id_diagnostico DESC';
                $p = $pdo->prepare($sql);
                $p->execute(array($estado, 7, $departamento));
            } else {
                $sql .= 'ORDER BY d.id_diagnostico DESC';
                $p = $pdo->prepare($sql);
                $p->execute(array($estado, 7));
            }
        } else {
            $sql .= 'WHERE d.id_estado = ? ';
            if ($permisos["tecnico"]) {
                $sql .= 'AND d.id_tecnico = ?
                ORDER BY d.id_diagnostico DESC';
                $p = $pdo->prepare($sql);
                $p->execute(array($estado, $tecnicoId));
            } else if ($permisos["jefe"]) {
                $sql .= 'AND d.id_departamento_roll = ?
                ORDER BY d.id_diagnostico DESC';
                $p = $pdo->prepare($sql);
                $p->execute(array($estado, $departamento));
            } else {
                $sql .= 'ORDER BY d.id_diagnostico DESC';
                $p = $pdo->prepare($sql);
                $p->execute(array($estado));
            }
        }

        $diagnosticos = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        foreach ($diagnosticos as $d) {
            $sub_array = array(
                "id_diagnostico" => $d["id_diagnostico"],
                "bien_sicoin_code" => $d["bien_sicoin_code"],
                "descripcion" => $d["descripcion"],
                "solicitante" => $d["solicitante"],
                "bien_descripcion" => $d["bien_descripcion"],
                "tecnico" => $d["tecnico"],
                "fecha_finalizado" => (!empty($d["fecha_finalizado"])) ? date('d/m/Y', strtotime($d["fecha_finalizado"])) : '<div class="text-info">Mantenimiento <i class="fa-solid fa-rotate-right fa-spin mx-1"></i></div>',
                "id_estado" => $d["id_estado"],
                "estado" => $d["estado"],


            );
            $data[] = $sub_array;
        }
        echo json_encode($data);
        return $data;
    }

    public static function setNuevoDiagnostico($data)
    {
        try {
            $idEmpleado = $data['idEmpleado'];
            $idBien = $data['idBien'];
            $problema = $data['problema'];
            $permisos = $GLOBALS['permisos'];
            $idTecnico = $GLOBALS['emp'];
            $idTecnico = $idTecnico['id_persona'];
            $fecha = date('Y-m-d H:i:s');

            $valor_asignado = 0;
            if ($permisos["desarrollo"]) {
                $valor_asignado = 352;
            } else if ($permisos["soporte"]) {
                $valor_asignado = 353;
            } else if ($permisos["radios"]) {
                $valor_asignado = 354;
            }

            $pdo = Database::connect_sqlsrv();

            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO Tickets.dbo.diagnostico
            (descripcion ,id_persona_solicita ,id_bien ,id_tecnico ,fecha_solicitado ,id_estado,id_departamento_roll)
            VALUES (?, ? ,? ,?, ?, ?, ?)";

            $p = $pdo->prepare($sql);
            $p->execute(array($problema, $idEmpleado, $idBien, $idTecnico, $fecha, 6, $valor_asignado));

            $yes = array('msg' => 'OK', 'id' => 1, 'message' => '¡Diagnostico Generado!');
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

    public static function setAnularDiagnostico($data)
    {
        try {
            $motivoAnulacion = $data['motivoAnulacion'];
            $id = $data['id'];
            $fecha = date('Y-m-d H:i:s');

            $pdo = Database::connect_sqlsrv();

            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT COUNT(id_diagnostico) AS cantidad_correlativos
            FROM diagnostico
            WHERE (YEAR(fecha_solicitado) = ?)";
            $p = $pdo->prepare($sql);
            $p->execute();
            $correlativo = $p->fetch(PDO::FETCH_ASSOC);
            $correlativo = $correlativo["cantidad_correlativos"];

            $sql = "INSERT INTO Tickets.dbo.diagnostico_detalle (id_diagnostico ,id_correlativo,anulacion,fecha)
            VALUES (?,?,?,?)";

            $p = $pdo->prepare($sql);
            $p->execute(array($id, $correlativo, $motivoAnulacion, $fecha));

            $sql = "UPDATE Tickets.dbo.diagnostico
            SET fecha_finalizado =?, id_estado = ?
            WHERE id_diagnostico = ?";

            $p = $pdo->prepare($sql);
            $p->execute(array($fecha, 4, $id));

            $yes = array('msg' => 'OK', 'id' => 1, 'message' => '¡Diagnostico Anulado!');
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

    public static function setFinalizarDiagnostico($data)
    {
        try {
            $recomendacion = $data['recomendacion'];
            $evaluacion = $data['evaluacion'];
            $id = $data['id'];
            $fecha = date('Y-m-d H:i:s');

            $pdo = Database::connect_sqlsrv();

            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT COUNT(id_diagnostico) AS cantidad_correlativos
            FROM diagnostico
            WHERE (YEAR(fecha_solicitado) = ?)";
            $p = $pdo->prepare($sql);
            $p->execute();
            $correlativo = $p->fetch(PDO::FETCH_ASSOC);
            $correlativo = $correlativo["id_correlativo"];

            $sql = "INSERT INTO Tickets.dbo.diagnostico_detalle (id_diagnostico,id_correlativo ,evaluacion, recomendacion, fecha)
            VALUES (?,?,?,?,?)";

            $p = $pdo->prepare($sql);
            $p->execute(array($id, $correlativo, $evaluacion, $recomendacion, $fecha));

            $sql = "UPDATE Tickets.dbo.diagnostico
            SET fecha_finalizado =?, id_estado = ?
            WHERE id_diagnostico = ?";

            $p = $pdo->prepare($sql);
            $p->execute(array($fecha, 3, $id));

            $yes = array('msg' => 'OK', 'id' => 1, 'message' => '¡Diagnostico Finalizado!');
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

    public static function setEntregarDiagnostico($data)
    {
        try {
            $id = $data['id'];
            $fecha = date('Y-m-d H:i:s');

            $pdo = Database::connect_sqlsrv();

            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE Tickets.dbo.diagnostico
            SET fecha_finalizado =?, id_estado = ?
            WHERE id_diagnostico = ?";

            $p = $pdo->prepare($sql);
            $p->execute(array($fecha, 7, $id));

            $yes = array('msg' => 'OK', 'id' => 1, 'message' => '¡Diagnostico Entregado!');
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
}

if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        case 1:
            Diagnostico::getDiagnosticos();
            break;

    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    if (isset($data['opcion'])) {
        $opcion = $data['opcion'];

        switch ($opcion) {
            case 2:
                Diagnostico::setNuevoDiagnostico($data);
                break;

            case 3:
                Diagnostico::setAnularDiagnostico($data);
                break;

            case 4:
                Diagnostico::setFinalizarDiagnostico($data);
                break;

            case 5:
                Diagnostico::setEntregarDiagnostico($data);
                break;
        }
    }
}