<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);


class Bien
{
    public $bien;
    //public $opcion = 1;
    protected function __construct()
    {
        $this->bien = array();
    }

    //Opcion 1
    static function getBienes()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT bien_id
        ,bien_sicoin_code
        ,bien_descripcion
        FROM APP_INVENTARIO.dbo.InventarioBien
        WHERE bien_renglon_id IN (?,?,?)";

        $p = $pdo->prepare($sql);

        $p->execute(array(324, 326, 328, 329));

        $bienes = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        foreach ($bienes as $b) {
            $sub_array = array(
                "bien_id" => $b["bien_id"],
                "bien_sicoin_code" => $b["bien_sicoin_code"],
                "bien_descripcion" => $b["bien_descripcion"],
            );
            $data[] = $sub_array;
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
            Bien::getBienes();
            break;
    }
}