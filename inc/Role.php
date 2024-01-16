<?php
class Role
{
    protected $permissions;

    protected function __construct() {
        $this->permissions = array();
    }

    // return a role object with associated permissions
    public static function getRolePerms($role_id) {
        $role = new Role();
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_pantalla
                FROM tbl_pantallas a
                INNER JOIN tbl_catalogo_detalle b ON a.id_sistema=b.id_item
                WHERE a.id_pantalla=?";
        $sth = $pdo->prepare($sql);
        $sth->execute(array($role_id));

        while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $role->permissions[$row["id_pantalla"]] = true;
        }
        Database::disconnect_sqlsrv();
        return $role;
    }

    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
    }
}
