<?php
class PrivilegedUser extends User
{
    private $roles;
    private $modulos;

    public function __construct() {
        parent::__construct();
    }

    // override User method
    public static function getByUserId($user_id) {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.primer_nombre, a.segundo_nombre,a.tercer_nombre,a.correo_electronico,
                       a.primer_apellido, a.segundo_apellido, a.tercer_apellido,
                       a.id_persona, b.persona_user, b.persona_pass, b.persona_salt,
                       b.status,b.mostrar_nombre
                       FROM rrhh_persona a
                       INNER JOIN rrhh_persona_usuario b ON b.id_persona=a.id_persona
                       WHERE a.id_persona=?";
        $sth = $pdo->prepare($sql);
        $sth->execute(array($user_id));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        Database::disconnect_sqlsrv();

        if (!empty($result)) {
            $privUser = new PrivilegedUser();
            $privUser->persona['user_id'] = $result["id_persona"];
            $nom='';
            if($result['mostrar_nombre']==1){
              $nom=$result['primer_nombre'];
            }else{
              $nom=$result['segundo_nombre'];
            }
            $nom_final=$nom.' '.$result['primer_apellido'];
            $privUser->persona['user_nm'] = $nom_final;
            $privUser->persona['user_mail'] = $result["correo_electronico"];
            $privUser->initRoles();
            return $privUser;
        } else {
            return false;
        }
    }
    public static function getByUserId_acceso($user_id) {
      $privUser = new PrivilegedUser();
      $privUser->persona['user_id'] = $user_id;
      return $privUser;
      /*
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.primer_nombre, a.segundo_nombre,a.tercer_nombre,a.correo_electronico,
                       a.primer_apellido, a.segundo_apellido, a.tercer_apellido,
                       a.id_persona, b.persona_user, b.persona_pass, b.persona_salt,
                       b.status,b.mostrar_nombre
                       FROM rrhh_persona a
                       INNER JOIN rrhh_persona_usuario b ON b.id_persona=a.id_persona
                       WHERE a.id_persona=?";
        $sth = $pdo->prepare($sql);
        $sth->execute(array($user_id));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        Database::disconnect_sqlsrv();

        if (!empty($result)) {
            $privUser = new PrivilegedUser();
            $privUser->persona['user_id'] = $result["id_persona"];
            $nom='';
            if($result['mostrar_nombre']==1){
              $nom=$result['primer_nombre'];
            }else{
              $nom=$result['segundo_nombre'];
            }
            $nom_final=$nom.' '.$result['primer_apellido'];
            $privUser->persona['user_nm'] = $nom_final;
            $privUser->persona['user_mail'] = $result["correo_electronico"];
            $privUser->initModulos();
            return $privUser;
        } else {
            return false;
        }*/
    }


    // populate roles with their associated permissions
    protected function initRoles() {
        $this->roles = array();
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT c.descrip_corta,c.id_pantalla,b.id_sistema
                FROM tbl_accesos_usuarios_det a
                INNER JOIN tbl_accesos_usuarios b ON a.id_acceso=b.id_acceso
                INNER JOIN tbl_pantallas c ON a.id_pantalla=c.id_pantalla
                WHERE b.id_persona=? AND a.flag_es_menu=?";
        $sth = $pdo->prepare($sql);
        $sth->execute(array($this->persona['user_id'],1));


        while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $this->roles[$row["id_pantalla"]] = Role::getRolePerms($row["id_pantalla"]);
            //$this->modulos[$row["id_sistema"]] = Modulo::getModulos($row["id_sistema"]);
        }

        Database::disconnect_sqlsrv();
    }

    protected function initModulos(){
      $this->modulos = array();
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT id_sistema
              FROM tbl_accesos_usuarios
              WHERE id_persona=? AND id_status=?";
      $sth = $pdo->prepare($sql);
      $sth->execute(array($this->persona['user_id'],1119));


      while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
          //$this->roles[$row["descrip_corta"]] = Role::getRolePerms($row["id_pantalla"]);
          $this->modulos[$row["id_sistema"]] = Modulo::getModulos($row["id_sistema"]);
      }

      Database::disconnect_sqlsrv();
    }

    // check if user has a specific privilege
    public function hasPrivilege($perm) {
      $this->roles = array();
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT c.descrip_corta,c.id_pantalla,b.id_sistema
              FROM tbl_accesos_usuarios_det a
              INNER JOIN tbl_accesos_usuarios b ON a.id_acceso=b.id_acceso
              INNER JOIN tbl_pantallas c ON a.id_pantalla=c.id_pantalla
              WHERE b.id_persona=? AND a.flag_es_menu=? AND a.id_pantalla=?";
      $sth = $pdo->prepare($sql);
      $sth->execute(array($this->persona['user_id'],1,$perm));
      $row = $sth->fetch();




      Database::disconnect_sqlsrv();
      if(!empty($row['id_pantalla'])){
        return true;
      }

        return false;
    }

    public function accesoModulo($id_modulo){
      $this->modulos = array();
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT id_sistema
              FROM tbl_accesos_usuarios
              WHERE id_persona=? AND id_status=? AND id_sistema =?";
      $sth = $pdo->prepare($sql);
      $sth->execute(array($this->persona['user_id'],1119,$id_modulo));
      $row = $sth->fetch();


      /*while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
          //$this->roles[$row["descrip_corta"]] = Role::getRolePerms($row["id_pantalla"]);
          $this->modulos[$row["id_sistema"]] = Modulo::getModulos($row["id_sistema"]);
      }*/

      Database::disconnect_sqlsrv();
      if(!empty($row['id_sistema'])){
        return true;
      }
      return false;
    }
}
