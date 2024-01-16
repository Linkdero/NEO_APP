<?php
class Privilegio
{
  protected $privilegios;

  protected function __construct() {
      $this->privilegios = array();
  }

  // return a role object with associated permissions
  public static function getModulos($id_pantalla) {
      $privilegio = new Modulo();
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT id_pantalla,descrip_corta
              FROM tbl_pantallas
              WHERE id_pantalla=?";
      $sth = $pdo->prepare($sql);
      $sth->execute(array($id_pantalla));

      while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
          $privilegio->privilegios[$row["id_pantalla"]] = true;
      }
      Database::disconnect_sqlsrv();
      return $privilegio;
  }

  // check if a permission is set
  public function hasModulo($id_pantalla) {
      return isset($this->privilegios[$id_pantalla]);
  }
}
