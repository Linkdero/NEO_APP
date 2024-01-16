<?php
class Modulo
{
    protected $modulos;

    protected function __construct() {
        $this->modulos = array();
    }

    // return a role object with associated permissions
    public static function getModulos($modulo_id) {
        $modulo = new Modulo();
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_item, descripcion
                FROM tbl_catalogo_detalle
                WHERE id_item=? AND id_status=?";
        $sth = $pdo->prepare($sql);
        $sth->execute(array($modulo_id,1119));

        while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $modulo->modulos[$row["id_item"]] = true;
        }
        Database::disconnect_sqlsrv();
        return $modulo;
    }

    // check if a permission is set
    public function hasModulo($id_modulo) {
        return isset($this->modulos[$id_modulo]);
    }
}
