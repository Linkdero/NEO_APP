<?php
include '../../../../inc/Database.php';
class Reporte
{
    static function obtenerSaldo()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = array(
            "saldoTotal" => array(),
            "totalCupones" => array(),
            "totalSaldoCupones" => array(),
        );

        // Obtener Saldo Total
        $sql = "SELECT ROUND(SUM(monto), 2) AS total
        FROM dayf_cupones
        WHERE id_estado_cupon IN (?, ?)";

        $p = $pdo->prepare($sql);
        $p->execute(array(1913, 4711));
        $saldoTotal = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($saldoTotal as $st) {
            $data["saldoTotal"][] = array(
                "total" => number_format($st["total"], 2, '.', ','),
                "totalEntero" => $st["total"],
            );
        }

        // Obtener Cantidad Cupones
        $sql = "SELECT COUNT(*) AS cupones100,
        (SELECT COUNT(*) AS Expr1
        FROM dayf_cupones
        WHERE (monto = 50) AND (id_estado_cupon IN (?, ?))) AS cupones50
        FROM dayf_cupones AS dayf_cupones_1
        WHERE (monto = 100) AND (id_estado_cupon IN (?, ?))";

        $p = $pdo->prepare($sql);
        $p->execute(array(1913, 4711, 1913, 4711));
        $totalCupones = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($totalCupones as $tc) {
            $data["totalCupones"][] = array(
                "cupones50" => $tc["cupones50"],
                "cupones100" => $tc["cupones100"],
            );
        }

        // Obtener Saldo Total Cupones
        $sql = "SELECT SUM(CASE WHEN monto = 100 THEN monto ELSE 0 END) AS cupones100, SUM(CASE WHEN monto = 50 THEN monto ELSE 0 END) AS cupones50
        FROM dayf_cupones
        WHERE (monto IN (50, 100)) AND (id_estado_cupon IN (?, ?))";

        $p = $pdo->prepare($sql);
        $p->execute(array(1913, 4711));
        $totalSaldoCupones = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($totalSaldoCupones as $tsc) {
            $data["totalSaldoCupones"][] = array(
                "cupones50" => number_format($tsc["cupones50"], 2, '.', ','),
                "cupones100" => number_format($tsc["cupones100"], 2, '.', ','),
                "cupones50Entero" => $tsc["cupones50"],
                "cupones100Entero" => $tsc["cupones100"],
            );
        }

        echo json_encode($data);
    }

    static function datosReporteria()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $tipoGrafica = $_GET["tipoGrafica"];

        $data = array(
            "movimientos5" => array(),
            "usoCupones" => array(),
            "ingresos" => array(),
            "ingresosTotalPorCupon" => array(),

        );

        if ($tipoGrafica == 1) {
            $sql = "SELECT TOP (5) total, total_devuelto, fecha_procesado
            FROM dayf_cupones_documento
            WHERE (id_tipo_documento = ?) AND (id_estado_documento = ?)
            ORDER BY id_documento DESC";

            $p = $pdo->prepare($sql);
            $p->execute(array(4353, 4347));
            $movimientos = $p->fetchAll(PDO::FETCH_ASSOC);

            foreach ($movimientos as $m) {
                $data["movimientos5"][] = array(
                    "total" => ($m["total"] - $m["total_devuelto"]),
                    "fecha_procesado" => $m["fecha_procesado"],
                );
            }
        } else if ($tipoGrafica == 2) {
            $sql = "SELECT 
            DATEFROMPARTS(YEAR(cd.fecha_procesado), MONTH(cd.fecha_procesado), 1) AS mes_procesado,
            SUM(CASE WHEN c.monto = 50 THEN c.monto ELSE 0 END) AS suma_montos_50,
            SUM(CASE WHEN c.monto = 100 THEN c.monto ELSE 0 END) AS suma_montos_100
            FROM dayf_cupones_documento AS cd
            LEFT JOIN dayf_cupones_documento_detalle AS cdd ON cd.id_documento = cdd.id_documento
            LEFT JOIN dayf_cupones AS c ON cdd.id_cupon = c.id_cupon
            WHERE 
            cd.id_tipo_documento = ?
            AND cd.id_estado_documento = ?
            AND (c.monto = 50 OR c.monto = 100)
            AND cd.fecha_procesado >= '2023-01-01 00:00:00.000' 
            AND cd.fecha_procesado < '2024-01-01 00:00:00.000'
            GROUP BY DATEFROMPARTS(YEAR(cd.fecha_procesado), MONTH(cd.fecha_procesado), 1)
            ORDER BY mes_procesado ASC";

            $p = $pdo->prepare($sql);
            $p->execute(array(4353, 4347));
            $usoCupones = $p->fetchAll(PDO::FETCH_ASSOC);

            foreach ($usoCupones as $uc) {
                $data["usoCupones"][] = array(
                    "monto50" => $uc["suma_montos_50"],
                    "monto100" => $uc["suma_montos_100"],
                    "fecha_procesado" => $uc["mes_procesado"],
                );
            }
        } else {
            $sql = "SELECT distinct id_documento
            ,fecha
            ,total
            ,descripcion
            FROM dayf_cupones_documento
            where id_tipo_documento = ? AND fecha >= '2023-01-01 00:00:00.000' AND fecha <= '2024-01-01 00:00:00.000'
            ORDER BY id_documento DESC";

            $p = $pdo->prepare($sql);
            $p->execute(array(4351));
            $ingresos = $p->fetchAll(PDO::FETCH_ASSOC);

            foreach ($ingresos as $i) {
                $data["ingresos"][] = array(
                    "total" => $i["total"],
                    "fecha" => date("Y-m-d", strtotime($i["fecha"])),
                    "descripcion" => $i["descripcion"]
                );
            }

            $sql = "SELECT
            cd.fecha,
            SUM(CASE WHEN c.monto = 50 THEN c.monto ELSE 0 END) AS montos50,
            SUM(CASE WHEN c.monto = 100 THEN c.monto ELSE 0 END) AS montos100
            FROM [SAAS_APP].[dbo].[dayf_cupones] AS c
            LEFT JOIN dayf_cupones_documento_detalle AS cdd ON c.id_cupon = cdd.id_cupon
            LEFT JOIN dayf_cupones_documento AS cd ON cdd.id_documento = cd.id_documento
            WHERE cd.id_tipo_documento = 4351 AND (c.monto = 50 OR c.monto = 100) AND cd.fecha >= '2023-01-01 00:00:00.000'
            GROUP BY cd.fecha
            ORDER BY cd.fecha";

            $p = $pdo->prepare($sql);
            $p->execute();
            $ingresosTotales = $p->fetchAll(PDO::FETCH_ASSOC);

            foreach ($ingresosTotales as $i) {
                $data["ingresosTotalPorCupon"][] = array(
                    "montos50" => $i["montos50"],
                    "montos100" => $i["montos100"],
                    "ingreso" => $i["fecha"]
                );
            }

        }

        echo json_encode($data);
    }
}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        //Para el Datatable
        case 1:
            Reporte::obtenerSaldo();
            break;

        case 2:
            Reporte::datosReporteria();
            break;
    }
}