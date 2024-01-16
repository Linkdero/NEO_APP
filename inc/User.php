<?php
/**
 * Created by PhpStorm.
 * User: usuario
 * Date: 14/09/2016
 * Time: 3:14 PM
 */

class User
{
    public $persona;

    protected function __construct() {
        $this->persona = array();
    }

    public static function getByUserId($user_id){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT user_vid,T1.user_id,role_id,user_mail,ext_id,user_pref,user_nm1,user_nm2,user_ap1,user_ap2,user_cui,T1.dep_id,dep_nm,user_puesto,user_nom,user_status,user_horario_id,verificacion
                FROM vp_user AS T1
                LEFT JOIN vp_deptos AS T2 ON T1.dep_id = T2.dep_id
                WHERE T1.user_id = ?";
        $p = $pdo->prepare($sql);
        $p->execute(array($user_id));
        $persona = $p->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();

        if(!empty($persona)){
            $usuario = new User();
            $usuario->persona['user_vid'] = $persona['user_vid'];
            $usuario->persona['user_id'] = $persona['user_id'];
            $usuario->persona['role_id'] = $persona['role_id'];
            $usuario->persona['user_mail'] = $persona['user_mail'];
            $usuario->persona['ext_id'] = $persona['ext_id'];
            $usuario->persona['user_pref'] = $persona['user_pref'];
            $usuario->persona['user_nm1'] = $persona['user_nm1'];
            $usuario->persona['user_nm2'] = $persona['user_nm2'];
            $usuario->persona['user_ap1'] = $persona['user_ap1'];
            $usuario->persona['user_ap2'] = $persona['user_ap2'];
            $usuario->persona['dep_id'] = $persona['dep_id'];
            $usuario->persona['dep_nm'] = $persona['dep_nm'];
            $usuario->persona['user_puesto'] = $persona['user_puesto'];
            $usuario->persona['user_nom'] = $persona['user_nom'];
            $usuario->persona['user_status'] = $persona['user_status'];
            $usuario->persona['user_cui'] = $persona['user_cui'];
            $usuario->persona['verificacion'] = $persona['verificacion'];
            $usuario->persona['user_horario_id'] = $persona['user_horario_id'];
            return $usuario;
        }else{
            return false;
        }
    }

//funcion reemplazada por empleado_nuevo()
    public static function usuarioNuevo(){
      $user_vid = 0;
      $user_mail = "";
      $ext_id = 0;
      $role_id ="";

      if($_POST['user_vid']!= null)
      {
        $user_vid = $_POST['user_vid'];
      }

      if($_POST['user_mail']!= "@vicepresidencia.gob.gt")
      {
      $user_mail = $_POST['user_mail']."@vicepresidencia.gob.gt";
    }

    if($_POST['ext_id']!= null)
    {
      $ext_id = $_POST['ext_id'];

    }

    if($_POST['roll_id']!= null)
    {

    $role_id = $_POST['roll_id'];

}

        $user_pref = $_POST['user_pref'];
        $user_nm1 = $_POST['user_nm1'];
        $user_nm2 = $_POST['user_nm2'];
        $user_ap1 = $_POST['user_ap1'];
        $user_ap2 = $_POST['user_ap2'];

        $dep_id = $_POST['dep_id'];
        $user_puesto = $_POST['user_puesto'];
        $user_nom = $_POST['user_nom'];

        $user_cui = $_POST['user_cui'];
        $user_mod = $_SESSION['user_id'];
        $user_hora = $_POST['hour_id'];

        $user_rev = 1;
        $user_status = 2;


        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO vp_user (role_id,user_vid,user_pref,user_nm1,user_nm2,user_ap1,user_ap2,ext_id,user_mail,dep_id,user_puesto,user_nom,user_mod,user_rev,user_status,user_cui,user_horario_id) values(?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($role_id,$user_vid,$user_pref,$user_nm1,$user_nm2,$user_ap1,$user_ap2,$ext_id,$user_mail,$dep_id,$user_puesto,$user_nom,$user_mod,$user_rev,$user_status,$user_cui,$user_hora));
        Database::disconnect();
    }

    public static function empleado_nuevo()
    {
      $user_nm1 = $_POST['user_nm1'];
      $user_nm2 = $_POST['user_nm2'];
      $user_ap1 = $_POST['user_ap1'];
      $user_ap2 = $_POST['user_ap2'];
      $fecha1 = $_POST['fecha_nac'];
      $fecha_nac = date('Y-m-d', strtotime($fecha1));

      $user_lugar_nac = $_POST['user_lugar_nac'];
      $user_genre = $_POST['user_genre'];
      $user_civil = $_POST['user_civil'];
      $user_cui = $_POST['user_cui'];
      $user_movil = $_POST['user_movil'];
      $user_profesion = $_POST['user_prof'];
      $user_direccion = $_POST['user_direccion'];

      $dep_id = $_POST['dep_id'];
      $user_puesto = $_POST['user_puesto'];
      $user_cargo = $_POST['user_cargo'];
      $nacionalidad = $_POST['nacionalidad'];

      $user_mod = $_SESSION['user_id'];
      $user_rev = 1;
      $user_status = 2;


      $user_acuerdo = $_POST['user_acuerdo'];
      $fecha2 = $_POST['fecha_acuerdo'];
      $fecha_acuerdo = date('Y-m-d', strtotime($fecha2));

      $r = $_POST['renglon'];

      $grupo = substr($r, -3, 1);
      $subgrupo = substr($r, -2, 1);
      $renglon = substr($r, -1, 1);

      $user_igss = $_POST['user_igss'];
      $user_nit = $_POST['user_nit'];
      $user_partida = $_POST['user_partida'];
      $fecha3 = $_POST['fecha_posesion'];
      $fecha_posesion = date('Y-m-d', strtotime($fecha3));
      $fecha4 = $_POST['fecha_inicio'];
      $fecha_inicio = date('Y-m-d', strtotime($fecha4));
      $foto = 'user_i.png';



      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO vp_user (user_nm1,user_nm2,user_ap1,user_ap2,fecha_nac,
        user_lugar_nac,user_genero,user_estado_civil,user_cui,user_movil,user_profesion,user_direccion,
        dep_id,user_puesto,user_nom,user_nacionalidad,user_mod,user_rev,user_status,user_horario_id)
        values(?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($user_nm1,$user_nm2,
      $user_ap1,$user_ap2,$fecha_nac,$user_lugar_nac,$user_genre,$user_civil,$user_cui,$user_movil,$user_profesion,
      $user_direccion,$dep_id,$user_puesto,$user_cargo,$nacionalidad,$user_mod,$user_rev,$user_status,4));
      $Id = $pdo->lastInsertId();

      $sql2 = "INSERT INTO vp_user_datos_laborales (user_id,acuerdo_vice,fecha_acuerdo,grupo_id,subgrupo_id,
        renglon_id,user_igss,user_nit,partida,fecha_posesion,inicio_laboral,fotografia) values(?,?,?,?,?,?,?,?,?,?,?,?)";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array($Id,$user_acuerdo,$fecha_acuerdo,
      $grupo,$subgrupo,$renglon,$user_igss,$user_nit,$user_partida,$fecha_posesion,$fecha_inicio,$foto));

      Database::disconnect();

    }

    public static function get_empleado_sueldo_byId($user_id){

      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT T1.user_id, T1.user_nm1,T1.user_nm2,T1.user_ap1,T1.user_ap2,T2.salario_base,
              T2.complemento_personal,T2.bono_antiguedad,T2.bono_profesional,T2.bono_vicepresidencial,
              T2.bono_66_2000,T2.gastos_de_representacion,T2.viaticos,
              sum(T2.salario_base+T2.complemento_personal+T2.bono_antiguedad+T2.bono_profesional+T2.bono_vicepresidencial+
                      T2.bono_66_2000+T2.gastos_de_representacion+T2.viaticos) as sueldo,
                      T2.igss,T2.montepio,T2.decreto_81_70,
                      (sum(T2.salario_base+T2.complemento_personal+T2.bono_antiguedad+T2.bono_profesional+T2.bono_vicepresidencial+
                              T2.bono_66_2000+T2.gastos_de_representacion+T2.viaticos)-sum(T2.igss+T2.montepio+T2.decreto_81_70)) AS liquido,
                              sum(T2.igss+T2.montepio+T2.decreto_81_70) as gastos,
                              CONCAT(T2.grupo_id, T2.subgrupo_id, T2.renglon_id) AS renglon
              FROM vp_user AS T1
              LEFT JOIN vp_user_datos_laborales AS T2 ON T2.user_id=T1.user_id
              WHERE T1.user_id = ?";
      $p = $pdo->prepare($sql);
      $p->execute(array($user_id));
      $persona = $p->fetch(PDO::FETCH_ASSOC);
      Database::disconnect();

      return $persona;
    }

    public static function get_empleado_sueldo_byId_por_correlativo($user_id,$c){

      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT T1.user_id, T1.user_nm1,T1.user_nm2,T1.user_ap1,T1.user_ap2,T2.salario_base,
              T2.complemento_personal,T2.bono_antiguedad,T2.bono_profesional,T2.bono_vicepresidencial,
              T2.bono_66_2000,T2.gastos_de_representacion,T2.viaticos,
              sum(T2.salario_base+T2.complemento_personal+T2.bono_antiguedad+T2.bono_profesional+T2.bono_vicepresidencial+
                      T2.bono_66_2000+T2.gastos_de_representacion+T2.viaticos) as sueldo,
                      T2.igss,T2.montepio,T2.decreto_81_70,
                      (sum(T2.salario_base+T2.complemento_personal+T2.bono_antiguedad+T2.bono_profesional+T2.bono_vicepresidencial+
                              T2.bono_66_2000+T2.gastos_de_representacion+T2.viaticos)-sum(T2.igss+T2.montepio+T2.decreto_81_70)) AS liquido,
                              sum(T2.igss+T2.montepio+T2.decreto_81_70) as gastos,
                              CONCAT(T2.grupo_id, T2.subgrupo_id, T2.renglon_id) AS renglon,
                              T2.fecha_complemento_personal,T2.fecha_bono_antiguedad,T2.fecha_bono_profesional,T2.fecha_bono_vicepresidencial,
                              T2.fecha_bono_66_2000
              FROM vp_user AS T1
              LEFT JOIN vp_user_011_029_historial AS T2 ON T2.user_id=T1.user_id
              WHERE T1.user_id = ? AND T2.correlativo=?";
      $p = $pdo->prepare($sql);
      $p->execute(array($user_id,$c));
      $persona = $p->fetch(PDO::FETCH_ASSOC);
      Database::disconnect();

      return $persona;
    }

    public static function get_max_correlativo_historial_by_user($user_id){
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT MAX( correlativo ) as co
              FROM  vp_user_011_029_historial

              WHERE user_id = ? ";
      $p = $pdo->prepare($sql);
      $p->execute(array($user_id));
      $persona = $p->fetch(PDO::FETCH_ASSOC);
      Database::disconnect();

      return $persona;
    }


    public static function update_sueldos_empleado($user_id)
    {
      $a = $_POST['salario_base'];
      $b = $_POST['complemento_personal'];
      $c = $_POST['bono_antiguedad'];
      $d = $_POST['bono_profesional'];
      $f = $_POST['bono_vicepresidencial'];
      $g = $_POST['bono_66_2000'];
      $h = $_POST['gastos_de_representacion'];
      $i = $_POST['viaticos'];

      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "UPDATE vp_user_datos_laborales SET salario_base=?,complemento_personal=?,bono_antiguedad=?,bono_profesional=?,
                     bono_vicepresidencial=?, bono_66_2000 =?,
                     gastos_de_representacion=?,viaticos=? WHERE user_id = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($a,$b,$c,$d,$f,$g,$h,$i,$user_id));
      Database::disconnect();
    }

    public static function get_empleado_datos_id($user_id)
    {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT T1.user_id, T1.user_nm1,T1.user_nm2,T1.user_ap1,T1.user_ap2,T1.dep_id,
                            T1.fecha_nac
                             AS f_n,
                            T1.user_lugar_nac,T1.user_genero,T1.user_estado_civil,T1.user_pref,
                            T1.user_cui,T1.user_movil,T1.user_profesion,T1.user_direccion,T1.user_nacionalidad,
                            T1.user_puesto,T1.user_nom,T1.user_status,
                            T2.acuerdo_vice,T2.fecha_acuerdo,T2.grupo_id, T2.subgrupo_id, T2.renglon_id,
                            CONCAT(T2.grupo_id, T2.subgrupo_id, T2.renglon_id) AS renglon,T1.user_igss,
                            T1.user_nit,T2.partida,T2.fecha_posesion,T2.inicio_laboral,T2.fecha_destitucion,
                            T2.fotografia,T1.user_mail,T1.user_vid,T1.ext_id,T1.role_id,T1.verificacion,T1.dep_id,T1.dependencia_id,
                            T3.dep_nm,T1.user_mail,T1.ext_id
                            FROM vp_user AS T1
                            LEFT JOIN vp_user_datos_laborales AS T2 ON T2.user_id=T1.user_id
                            LEFT JOIN vp_deptos AS T3 ON T1.dep_id=T3.dep_id
                            WHERE T1.user_id = ?
              ";


      $p = $pdo->prepare($sql);
      $p->execute(array($user_id));
      $persona = $p->fetch(PDO::FETCH_ASSOC);
      Database::disconnect();
      return $persona;

    }

    public static function get_empleado_datos_id_por_correlativo($user_id,$c)
    {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT T1.user_id, T1.user_nm1,T1.user_nm2,T1.user_ap1,T1.user_ap2,T1.dep_id,
                            T1.fecha_nac
                             AS f_n,
                            T1.user_lugar_nac,T1.user_genero,T1.user_estado_civil,T1.user_pref,
                            T1.user_cui,T1.user_movil,T1.user_profesion,T1.user_direccion,T1.user_nacionalidad,
                            T2.user_puesto,T2.user_nom,T1.user_status,
                            T2.acuerdo_vice,T2.fecha_acuerdo,
                            CONCAT(T2.grupo_id, T2.subgrupo_id, T2.renglon_id) AS renglon,T1.user_igss,
                            T1.user_nit,T2.partida,T2.inicio_laboral,T2.fecha_destitucion,
                            T2.fotografia,T1.user_mail,T1.user_vid,T1.ext_id,T1.role_id,T1.verificacion,T1.dep_id,T1.dependencia_id,
                            T3.dep_nm,T1.user_mail,T1.ext_id,T2.contrato_num,T2.contrato_fecha,T2.contrato_ini,T2.contrato_fin,
                            T2.resolucion_no,T2.resolucion_fecha,T2.fianza,T2.tipo_cancelacion_c
                            FROM vp_user AS T1
                            LEFT JOIN vp_user_011_029_historial AS T2 ON T2.user_id=T1.user_id
                            LEFT JOIN vp_deptos AS T3 ON T1.dep_id=T3.dep_id
                            WHERE T1.user_id = ? AND T2.correlativo=?
              ";


      $p = $pdo->prepare($sql);
      $p->execute(array($user_id,$c));
      $persona = $p->fetch(PDO::FETCH_ASSOC);
      Database::disconnect();
      return $persona;

    }

    public static function get_empleado_datos_resolucion_id_por_correlativo($user_id,$c)
    {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT T1.resolucion_no, T1.resolucion_fecha, T2.literal_desc, T1.fecha_destitucion, T2.literal_id
              FROM vp_user_011_029_historial AS T1
              INNER JOIN vp_catalogo_literal_029 AS T2 ON T2.literal_id=T1.tipo_cancelacion_c
                            WHERE T1.user_id = ? AND T1.correlativo=?
              ";


      $p = $pdo->prepare($sql);
      $p->execute(array($user_id,$c));
      $persona = $p->fetch(PDO::FETCH_ASSOC);
      Database::disconnect();
      return $persona;

    }

    public static function get_empleado_ascensos_contratos_historial_id($user_id)
    {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM vp_user_011_029_historial WHERE user_id=? ORDER BY fecha_acuerdo";


      $p = $pdo->prepare($sql);
      $p->execute(array($user_id));
      $historial = $p->fetchAll(PDO::FETCH_ASSOC);
      Database::disconnect();
      return $historial;

    }

    function update_empleado_datos_id($user_id)
    {
      echo '<script>alert("hola");</script>';
      $persona = User::get_empleado_datos_id($user_id);
      $dirname = User::verificar_carpeta();
      date_default_timezone_set('America/Guatemala');
      $date = date('Ymd H.i.s',time());
      $foto_name= $persona['fotografia'];

      $allowed = array('jpeg','png');
      if($_FILES['foto']['name']){
          $ext = pathinfo($_FILES['foto']['name'],PATHINFO_EXTENSION);
          $foto = $date.' - FOTO '.substr(strtolower($_FILES['foto']['name']),-120); //renombrar archivo FIRMADO
          $foto = sanear_string($foto);
          if(subir_archivo($_FILES['foto'],$foto,$allowed,$ext,$dirname)){
              $foto_name = $foto;
          }

        }



    }

    public static function usuarioModificar($user_id){
      $user_veri;
      //$user_status = 1;
        $user_vid = $_POST['user_vid'];
        $user_pref = $_POST['user_pref'];
        $user_nm1 = $_POST['user_nm1'];
        $user_nm2 = $_POST['user_nm2'];
        $user_ap1 = $_POST['user_ap1'];
        $user_ap2 = $_POST['user_ap2'];
        $ext_id = $_POST['ext_id'];
        $user_mail = $_POST['user_mail'];
        $dep_id = $_POST['dep_id'];
        $user_puesto = $_POST['user_puesto'];
        $user_nom = $_POST['user_nom'];
        $role_id = $_POST['roll_id'];
        $user_mod = $_SESSION['user_id'];
        $user_status = $_POST['user_status'];
        $user_cui = $_POST['user_cui'];
        $user_hora = $_POST['hour_id'];
        if($_POST['user_status']==1)
        {
          $user_veri = 1;

        }else
          if($_POST['user_status']==0)
          {
            $user_veri = 2;

          }




        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE vp_user SET user_vid = ?, role_id = ?, user_pref = ?, user_nm1 = ?, user_nm2 = ?, user_ap1 = ?, user_ap2 = ?, ext_id = ?, user_mail = ?, dep_id = ?, user_puesto = ?, user_nom = ?, user_mod = ?, user_cui = ?, user_status = ?, user_horario_id = ?, user_rev = user_rev + 1, verificacion = ? WHERE user_id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($user_vid,$role_id,$user_pref,$user_nm1,$user_nm2,$user_ap1,$user_ap2,$ext_id,$user_mail,$dep_id,$user_puesto,$user_nom,$user_mod,$user_cui,$user_status,$user_hora,$user_veri,$user_id));
        Database::disconnect();
    }

    public static function actualizarPassword($password, $random_salt,$user_id){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE vp_user SET user_pass = ?, user_salt = ?, user_mod = ?, user_rev = user_rev + 1 WHERE user_id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($password, $random_salt,$_SESSION['user_id'],$user_id));
        Database::disconnect();
    }

    public static function userRole($user_id){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT role_id
            FROM vp_user
            WHERE user_id = ?";
        $p = $pdo->prepare($sql);
        $p->execute(array($user_id));
        $rol = $p->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $rol;
    }


    /* S U S P E N C I O N E S */

    public static function suspencion_nueva($user_id){
        $user_mod = $_SESSION['user_id'];


        $user_vid = $_POST['codigo'];
        $fecha_ini = $_POST['from'];
        $date1 = date('Y-m-d', strtotime($fecha_ini));
        $fecha_fin = $_POST['to'];
        $date2 = date('Y-m-d', strtotime($fecha_fin));
        $resolucion = $_POST['resolucion'];
        $tipo_sus = $_POST['dia'];
        $desc = $_POST['sus_desc'];



        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO vp_user_suspenciones (user_id, user_vid, fecha_ini, fecha_fin, resolucion, descripcion, tipo_suspencion, realizado_por) VALUES (?,?,?,?,?,?,?,?) ";
        $q = $pdo->prepare($sql);
        $q->execute(array($user_id,$user_vid,$date1,$date2,$resolucion,$desc,$tipo_sus,$user_mod));

        $sql2 = "UPDATE vp_user_horario_general SET tipo_dia_laboral = ?
        WHERE fecha_laboral BETWEEN ? AND ? and user_vid = ? AND tipo_dia_laboral <> 3 AND tipo_dia_laboral <> 1 ";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($tipo_sus,$date1,$date2,$user_vid));

        Database::disconnect();

        //agregar lo siguiente
        /**/
    }

    public static function suspencion_modificar()
    {
      $user_mod = $_SESSION['user_id'];


      //$user_vid = $_POST['codigo'];
      $fecha_ini = $_POST['from1'];
      $date1 = date('Y-m-d', strtotime($fecha_ini));
      $fecha_fin = $_POST['to1'];
      $date2 = date('Y-m-d', strtotime($fecha_fin));
      $resolucion = $_POST['resolucion1'];
      $tipo_sus = $_POST['dia1'];
      $desc = $_POST['descripcion1'];
      $user_id=$_POST['codigo_u'];



      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "UPDATE vp_user_suspenciones SET fecha_ini=?, fecha_fin=?, descripcion=?, tipo_suspencion=?, realizado_por=?
              WHERE user_id=? and resolucion=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($date1,$date2,$desc,$tipo_sus,$user_mod,$user_id,$resolucion));

      $sql1= "SELECT user_vid FROM vp_user_suspenciones WHERE user_id=? AND resolucion=?";
      $p1 = $pdo->prepare($sql1);
      $p1->execute(array($user_id,$resolucion));
      $obtener = $p1->fetch(PDO::FETCH_ASSOC);


      $user_vid = $obtener['user_vid'];

      $sql2 = "UPDATE vp_user_horario_general SET tipo_dia_laboral = ?
      WHERE fecha_laboral BETWEEN ? AND ? and user_vid = ? AND tipo_dia_laboral <> 3 AND tipo_dia_laboral <> 1 ";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array($tipo_sus,$date1,$date2,$user_vid));

      Database::disconnect();
    }


    /* F I N    S U S P E N C I O N E S*/


    public static function getByUser_HorarioId_copia($user_vid, $mes, $year){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DATE(t1.horario) AS FECHA, MIN(TIME(t1.horario)) AS F_INI, MAX(TIME(t1.horario)) AS F_FIN,
concat( MOD(HOUR(TIMEDIFF(MIN(t1.horario), MAX(t1.horario))), 24), ' : ',
       MINUTE(TIMEDIFF(MIN(t1.horario), MAX(t1.horario))), '  ') as HORAS,
       IF(time(min(t1.horario))<= time(t3.hora_inicio),'','0') AS E,
       IF(time(MAX(t1.horario)) <= time(t3.hora_final),'1','') AS S

       FROM vp_user_horario t1 INNER JOIN vp_user t2 ON t1.user_id_huella = t2.user_vid
       INNER JOIN vp_catalogo_horario t3 ON t2.user_horario_id = t3.hora_id
       WHERE t1.user_id_huella = ?  AND MONTH(t1.horario) = ? AND YEAR(t1.horario) = ? GROUP BY DATE(t1.horario)";

        $p = $pdo->prepare($sql);
        $p->execute(array($user_vid, $mes, $year));
        $persona = $p->fetchAll();
        Database::disconnect();
        return $persona;
    }

    public static function getByUser_HorarioId($user_vid, $mes, $year){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT CONCAT(t4.user_nm1, ' ', t4.user_nm2, ' ', t4.user_ap1, ' ' , t4.user_ap2) as NOMBRE,
                t1.fecha_laboral as FECHA, t1.hora_en AS F_INI, t1.hora_sal AS F_FIN,
                t1.tipo_dia_laboral AS LABOR, t3.dia_nm AS DIAN,
                SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , t1.hora_en, t1.hora_sal ))*60) as HORAS,
                IF (t1.hora_en<= time(t5.hora_inicio),'','Llegó tarde') AS E,
                IF(t1.hora_sal <= time(t5.hora_final),'Se fue temprano','') AS S
                FROM vp_user_horario_general t1
                INNER JOIN vp_user  t4 ON t1.user_vid = t4.user_vid
                INNER JOIN vp_catalogo_dia_laboral t3 ON t1.tipo_dia_laboral = t3.dia_laboral_id
                INNER JOIN vp_catalogo_horario t5 ON t4.user_horario_id=t5.hora_id

                WHERE t1.user_vid=? AND MONTH(t1.fecha_laboral) = ? AND YEAR(t1.fecha_laboral) = ?
                GROUP BY t1.fecha_laboral, t1.user_vid
                ORDER BY t1.user_vid, t1.fecha_laboral ASC";

        $p = $pdo->prepare($sql);
        $p->execute(array($user_vid, $mes, $year));
        $persona = $p->fetchAll();
        Database::disconnect();
        return $persona;
    }

    public static function getHorarios_Generales($mes, $year){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT CONCAT(t4.user_nm1, ' ', t4.user_nm2, ' ', t4.user_ap1, ' ' , t4.user_ap2) as NOMBRE,
        DATE(t1.horario) AS FECHA, MIN(TIME(t1.horario)) AS F_INI, MAX(TIME(t1.horario)) AS F_FIN,
        SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , t1.hora_en, t1.hora_sal ))*60) as HORAS
        FROM vp_user_horario t1 INNER JOIN vp_user t2 ON t1.user_id_huella = t2.user_vid
        INNER JOIN vp_catalogo_horario t3 ON t2.user_horario_id = t3.hora_id
        INNER JOIN vp_user t4 ON t1.user_id_huella = t4.user_vid
        WHERE MONTH(t1.horario) = ? AND YEAR(t1.horario) = ? GROUP BY DATE(t1.horario), t1.user_id_huella ORDER BY t1.user_id_huella, DATE(t1.horario) ASC";

        $p = $pdo->prepare($sql);
        $p->execute(array($mes, $year));
        $persona = $p->fetchAll();
        Database::disconnect();
        return $persona;
    }


    public static function getHorarios_Generales_Control($mes, $year){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT t1.user_vid, CONCAT(t4.user_nm1, ' ', t4.user_nm2, ' ', t4.user_ap1, ' ' , t4.user_ap2) as NOMBRE,
                t1.fecha_laboral as FECHA, t1.hora_en AS F_INI, t1.hora_sal AS F_FIN,
                t1.tipo_dia_laboral AS LABOR, t3.dia_nm AS DIAN,
                SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , t1.hora_en, t1.hora_sal ))*60) as HORAS
                FROM vp_user_horario_general t1
                INNER JOIN vp_user t4 ON t1.user_vid = t4.user_vid
                INNER JOIN vp_catalogo_dia_laboral t3 ON t1.tipo_dia_laboral = t3.dia_laboral_id

                WHERE MONTH(t1.fecha_laboral) = ? AND YEAR(t1.fecha_laboral) = ?
                GROUP BY t1.fecha_laboral, t1.user_vid
                ORDER BY t1.user_vid, t1.fecha_laboral ASC";

        $p = $pdo->prepare($sql);
        $p->execute(array($mes, $year));
        $persona = $p->fetchAll();
        Database::disconnect();
        return $persona;
    }
    public static function getHorarios_Generales_Control_by_dep($mes, $year, $dep){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT t1.user_vid, CONCAT(t4.user_nm1, ' ', t4.user_nm2, ' ', t4.user_ap1, ' ' , t4.user_ap2) as NOMBRE,
                t1.fecha_laboral as FECHA, t1.hora_en AS F_INI, t1.hora_sal AS F_FIN,
                t1.tipo_dia_laboral AS LABOR, t3.dia_nm AS DIAN,
                SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , t1.hora_en, t1.hora_sal ))*60) as HORAS
                FROM vp_user_horario_general t1
                INNER JOIN vp_user t4 ON t1.user_vid = t4.user_vid
                INNER JOIN vp_catalogo_dia_laboral t3 ON t1.tipo_dia_laboral = t3.dia_laboral_id

                WHERE MONTH(t1.fecha_laboral) = ? AND YEAR(t1.fecha_laboral) = ? AND t4.dep_id=?
                GROUP BY t1.fecha_laboral, t1.user_vid
                ORDER BY t1.user_vid, t1.fecha_laboral ASC";

        $p = $pdo->prepare($sql);
        $p->execute(array($mes, $year, $dep));
        $persona = $p->fetchAll();
        Database::disconnect();
        return $persona;
    }



    function get_user_suspenciones($id)
    {

      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT t1.fecha_ini as d, t1.fecha_fin as e, t1.resolucion as a, t1.descripcion as b, t2.dia_nm as c,t1.tipo_suspencion as f FROM vp_user_suspenciones t1
              INNER JOIN vp_catalogo_dia_laboral t2 ON t1.tipo_suspencion = t2.dia_laboral_id
              WHERE t1.user_id = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($id));
      $sus = $q->fetchAll();
      Database::disconnect();
      return $sus;


    }

    function get_userlist_suspenciones()
    {

      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT CONCAT(t3.user_nm1, ' ', t3.user_nm2, ' ', t3.user_ap1, ' ', t3.user_ap2)AS nombre, t4.dep_nm as dep, t1.fecha_ini as d, t1.fecha_fin as e, t1.resolucion as a, t1.descripcion as b, t2.dia_nm as c FROM vp_user_suspenciones t1
              INNER JOIN vp_catalogo_dia_laboral t2 ON t1.tipo_suspencion = t2.dia_laboral_id
              INNER JOIN vp_user t3 ON t1.user_id= t3.user_id
              INNER JOIN vp_deptos t4 ON t4.dep_id= t3.dep_id";
      $q = $pdo->prepare($sql);
      $q->execute(array());
      $sus = $q->fetchAll();
      Database::disconnect();
      return $sus;


    }


    function get_nombre_dia($fecha){
   $fechats = strtotime($fecha); //pasamos a timestamp

//el parametro w en la funcion date indica que queremos el dia de la semana
//lo devuelve en numero 0 domingo, 1 lunes,....
switch (date('w', $fechats)){
    case 0: return "Domingo"; break;
    case 1: return "Lunes"; break;
    case 2: return "Martes"; break;
    case 3: return "Miercoles"; break;
    case 4: return "Jueves"; break;
    case 5: return "Viernes"; break;
    case 6: return "Sabado"; break;
}
}

public static function get_nombre_mes($n){

//el parametro w en la funcion date indica que queremos el dia de la semana
//lo devuelve en numero 0 domingo, 1 lunes,....
switch ($n){
case 1: return "Enero"; break;
case 2: return "Febrero"; break;
case 3: return "Marzo"; break;
case 4: return "Abril"; break;
case 5: return "Mayo"; break;
case 6: return "Junio"; break;
case 7: return "Julio"; break;
case 8: return "Agosto"; break;
case 9: return "Septiembre"; break;
case 10: return "Octubre"; break;
case 11: return "Noviembre"; break;
case 12: return "Diciembre"; break;
}
}


public static function get_user_horario($user_vid){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT t2.user_nm1, t2.user_nm2, t2.user_ap1, t2.user_ap2,
HOUR(t1.hora_inicio) as h_ini, HOUR(t1.hora_final ) as h_fin, t3.dep_nm,t2.user_id
from vp_catalogo_horario t1
inner join vp_user t2 on t1.hora_id = t2.user_horario_id
inner join vp_deptos t3 on t2.dep_id = t3.dep_id
where t2.user_vid = ?";
  $p = $pdo->prepare($sql);
  $p->execute(array($user_vid));
  $persona = $p->fetch(PDO::FETCH_ASSOC);
  Database::disconnect();

  if(!empty($persona)){
      $usuario = new User();
      $usuario->persona['user_nm1'] = $persona['user_nm1'];
      $usuario->persona['user_nm2'] = $persona['user_nm2'];
      $usuario->persona['user_ap1'] = $persona['user_ap1'];
      $usuario->persona['user_ap2'] = $persona['user_ap2'];

      $usuario->persona['h_ini'] = $persona['h_ini'];
      $usuario->persona['h_fin'] = $persona['h_fin'];
      $usuario->persona['dep_nm'] = $persona['dep_nm'];
      $usuario->persona['user_id'] = $persona['user_id'];
      return $usuario;
  }else{
      return false;
  }
}

function subir_horarios()
{
  $tipo = $_FILES['archivo']['type'];
  $tamanio = $_FILES['archivo']['size'];
  $archivotmp = $_FILES['archivo']['tmp_name'];

  //cargamos el archivo
  $lineas = file($archivotmp);

  //inicializamos variable a 0, esto nos ayudará a indicarle que no lea la primera línea
  $i=0;

  //Recorremos el bucle para leer línea por línea
  foreach ($lineas as $linea_num => $linea)
  {
    set_time_limit(0);//limite de tiempo cero para poder recorrer todo el archivo.txt
     //abrimos bucle
     /*si es diferente a 0 significa que no se encuentra en la primera línea
     (con los títulos de las columnas) y por lo tanto puede leerla*/
     if($i != 0)
     {
         //abrimos condición, solo entrará en la condición a partir de la segunda pasada del bucle.
         /* La funcion explode nos ayuda a delimitar los campos, por lo tanto irá
         leyendo hasta que encuentre un ; */
         $datos = explode(",",$linea);

         //Almacenamos los datos que vamos leyendo en una variable
         //usamos la función utf8_encode para leer correctamente los caracteres especiales
         $user_vid = $datos[0];
         $user_horario = $datos[2];

         $pdo = Database::connect();
         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

         //guardamos en base de datos la línea leida
         $sql = "INSERT IGNORE INTO vp_user_horario(user_id_huella, horario) VALUES (?,?)";
         $q = $pdo->prepare($sql);
         $q->execute(array($user_vid, $user_horario));
         Database::disconnect();

         //cerramos condición
     }

     /*Cuando pase la primera pasada se incrementará nuestro valor y a la siguiente pasada ya
     entraremos en la condición, de esta manera conseguimos que no lea la primera línea.*/
     $i++;

     //cerramos bucle
  }

}

/*VALIDAR FECHA INICIAL Y FINAL*/

function validar_fecha_suspencion($f, $u)
{

  $date1 = date('Y-m-d', strtotime($f));

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT count(*) as conteo FROM vp_user_horario_general WHERE fecha_laboral = ? and user_vid = ?";
  $q = $pdo->prepare($sql);
  $q->execute(array($date1, $u));
  $fechas = $q->fetch(PDO::FETCH_ASSOC);
  Database::disconnect();

  $conteo = $fechas['conteo'];
  return $conteo;
}


function subir_archivo($file,$new_file_name,$allowed,$ext,$dirname){
    $file_valid = true;

    //Verificar que no hayan errores
    if(!$file['error'])
    {
        /*
         //definir tamaño maximo de archivos
        if($file['size'] > (10024000)) //can't be larger than 1 MB
        {
            echo $message = 'Tu archivo es demasiado grande.';
            $file_valid = false;
        }
        */

        //Verificar extension del archivo

        if(!in_array($ext,$allowed)){
            echo $message = 'Debe adjuntarse un archivo de tipo valido.';
            $file_valid = false;
        }

        if($file_valid){
            //moverlo a la carpeta deseada
            move_uploaded_file($file['tmp_name'], $dirname.'/'.$new_file_name);
            echo $message = 'El archivo se proceso correctamente.';
            return true;
        }else{
            return false;
        }
    }else{
        //set that to be the returned message
        echo $message = 'Lo sentimos, se produjo un error al procesar el archivo.'.$file['error'];
        return false;
    }

}


function verificar_carpeta(){
    $dirname = 'files/';
    $dirname = iconv("UTF-8","Windows-1252",$dirname);
    if (!file_exists($dirname)) {
        mkdir($dirname, 0777, true);
    }
    return $dirname;
}

function getAllDrivers($dep_id){

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT conductor_id, T1.user_id, CONCAT( user_nm1,  ' ', user_nm2,  ' ', user_ap1,  ' ', user_ap2 ) AS nombre, user_puesto, licencia_num, licencia_cad, T1.status
                FROM vp_conductor AS T1
                LEFT JOIN vp_user AS T2 ON T1.user_id = T2.user_id

                WHERE T1.dep_id =?";
        $p = $pdo->prepare($sql);
        $p->execute(array(21));
        $drivers = $p->fetchAll();
        Database::disconnect();
        return $drivers;




}

function getAllDrivers_s($dep_id){

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT conductor_id, T1.user_id, CONCAT( user_nm1,  ' ', user_nm2,  ' ', user_ap1,  ' ', user_ap2 ) AS nombre, user_puesto, licencia_num, licencia_cad, T1.status
                FROM vp_conductor AS T1
                LEFT JOIN vp_user AS T2 ON T1.user_id = T2.user_id

                WHERE T1.dep_id =?";
        $p = $pdo->prepare($sql);
        $p->execute(array($dep_id));
        $drivers = $p->fetchAll();
        Database::disconnect();
        return $drivers;




}

function verificar_permiso_parcial($emp,$fecha,$tipo){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT t1.tipo_suspencion,t1.resolucion,t2.dia_nm FROM vp_user_suspenciones as t1
          INNER JOIN vp_catalogo_dia_laboral as t2 ON t1.tipo_suspencion=t2.dia_laboral_id
          WHERE t1.user_vid=? AND ? BETWEEN t1.fecha_ini AND t1.fecha_fin AND t1.tipo_suspencion=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($emp,$fecha,$tipo));
  $descripcion = $q->fetch();




  Database::disconnect();
  return $descripcion;

}

function verificar_permiso_parcial_horas($emp,$fecha){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT T1.resolucion,T1.descripcion,T1.tipo_suspencion,T2.dia_nm
          FROM vp_user_suspenciones AS T1
          INNER JOIN vp_catalogo_dia_laboral AS T2 ON T1.tipo_suspencion=T2.dia_laboral_id
          WHERE T1.user_vid=? AND ? BETWEEN T1.fecha_ini AND T1.fecha_fin";
  $q = $pdo->prepare($sql);
  $q->execute(array($emp,$fecha));
  $descripcion = $q->fetch();




  Database::disconnect();
  return $descripcion;

}

function verificar_horario_empleado_semanal($user_vid,$fecha){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT DATE(horario) AS FECHA FROM vp_user_horario
  WHERE user_id_huella=? AND DATE(horario)=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($user_vid,$fecha));
  $descripcion = $q->fetch();




  Database::disconnect();
  return $descripcion;

}

function get_nombre_grupo_por_semana_year($semana,$year){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT T1.horario_especial_desc
          FROM vp_horario_especial_grupo AS T1
          INNER JOIN vp_user_horario_semana AS T2 ON T2.grupo=T1.horario_especial_id
          WHERE semana=? AND year=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($semana,$year));
  $grupo = $q->fetch();




  Database::disconnect();
  return $grupo;

}

function check_in_range($start_date, $end_date, $evaluame) {
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($evaluame);
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

function verificar_partidas_vigente($user_id){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT COUNT(fecha_destitucion) as conteo FROM vp_user_011_029_historial WHERE user_id=? AND fecha_destitucion=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($user_id,'0000-00-00'));
  $conteo = $q->fetch();

  if($conteo['conteo']>0){
    return false;
  }
  else {
    return true;
  }



  Database::disconnect();


}

function get_ascensos_menor_partida($user_id,$correlativo){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT acuerdo_vice, fecha_acuerdo, partida,
          SUM( salario_base + complemento_personal + bono_antiguedad + bono_profesional +
                bono_vicepresidencial + bono_66_2000 + gastos_de_representacion + viaticos ) AS sueldo
                FROM vp_user_011_029_historial
                WHERE user_id =? AND correlativo <? AND CONCAT(grupo_id,subgrupo_id,renglon_id) <>?GROUP BY correlativo ORDER BY correlativo ASC ";
  $q = $pdo->prepare($sql);
  $q->execute(array($user_id,$correlativo,'029'));
  $partidas = $q->fetchAll();

  Database::disconnect();

  return $partidas;
}

function get_requerimientos_usuario_id($user_id){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM vp_user_notificacion_creado WHERE user_id=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($user_id));
  $partidas = $q->fetch();

  Database::disconnect();

  return $partidas;
}

function get_partida_mas_reciente_user_carnet($user_id){

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT correlativo as corr, user_puesto, user_nom,
          contrato_fin,fotografia,grupo_id,subgrupo_id,renglon_id,acuerdo_vice
          FROM vp_user_011_029_historial
          WHERE user_id =?
          ORDER BY correlativo DESC
          LIMIT 1";
  $q = $pdo->prepare($sql);
  $q->execute(array($user_id));
  $partida = $q->fetch();

  Database::disconnect();

  return $partida;

}

function get_carnet_by($user_id,$year,$correlativo){

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT year, CONCAT(grupo_id,subgrupo_id,renglon_id) as renglon,
          LPAD(user_id, 3,'0') as u_i, LPAD(version_id, 2,'0')as ver, version_id,user_puesto, fecha_vencimiento,estado,fotografia
          FROM vp_user_carnet
          WHERE user_id =? AND year=? AND version_id = ?";
  $q = $pdo->prepare($sql);
  $q->execute(array($user_id,$year,$correlativo));
  $carnet = $q->fetch();

  Database::disconnect();

  return $carnet;

}

function get_firma_by_user($user_id){

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT user_firma FROM vp_user WHERE user_id=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($user_id));
  $datos = $q->fetch(PDO::FETCH_ASSOC);
  $q->closeCursor();

  Database::disconnect();


	//return $datos;

return $datos['user_firma'];


}

function get_pass($u_id){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT user_pass, user_salt
          FROM vp_user
          WHERE user_id=? ";
  $q = $pdo->prepare($sql);
  $q->execute(array($u_id));
  $user = $q->fetch();

  Database::disconnect();

  return $user;
}
function evaluar_carnet_activo_by_user($user_id){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT COUNT()
          FROM vp_user
          WHERE user_id=? ";
  $q = $pdo->prepare($sql);
  $q->execute(array($u_id));
  $user = $q->fetch();

  Database::disconnect();

  return $user;
}

function get_carnets($year,$renglon){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if($renglon==0){


    $sql = "SELECT T1.year, CONCAT(T1.grupo_id,T1.subgrupo_id,T1.renglon_id) as renglon,
    LPAD(T1.user_id, 3,'0') as u_i, LPAD(T1.version_id, 2,'0')as ver, T1.version_id,T1.user_puesto, T1.fecha_vencimiento,T1.estado,
    CONCAT(T2.user_nm1,' ',T2.user_nm2, ' ', T2.user_ap1, ' ', T2.user_ap2) as nombre,
    T1.estado_impreso,T1.user_id,T1.fotografia,T1.user_id
    FROM vp_user_carnet AS T1
    INNER JOIN vp_user AS T2 ON T1.user_id=T2.user_id
    WHERE T1.year=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($year));
    $carnets = $q->fetchAll();
    return $carnets;

  }
  else if($renglon==1){

    $sql = "SELECT T1.year, CONCAT(T1.grupo_id,T1.subgrupo_id,T1.renglon_id) as renglon,
    LPAD(T1.user_id, 3,'0') as u_i, LPAD(T1.version_id, 2,'0')as ver, T1.version_id,T1.user_puesto, T1.fecha_vencimiento,T1.estado,
    CONCAT(T2.user_nm1,' ',T2.user_nm2, ' ', T2.user_ap1, ' ', T2.user_ap2) as nombre,
    T1.estado_impreso,T1.user_id,T1.fotografia,T1.user_id
    FROM vp_user_carnet AS T1
    INNER JOIN vp_user AS T2 ON T1.user_id=T2.user_id
    WHERE T1.year=? AND CONCAT(T1.grupo_id,T1.subgrupo_id,T1.renglon_id)=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($year,'011'));
    $carnets = $q->fetchAll();
    return $carnets;

  }
  else if($renglon==2){


    $sql = "SELECT T1.year, CONCAT(T1.grupo_id,T1.subgrupo_id,T1.renglon_id) as renglon,
    LPAD(T1.user_id, 3,'0') as u_i, LPAD(T1.version_id, 2,'0')as ver, T1.version_id,T1.user_puesto, T1.fecha_vencimiento,T1.estado,
    CONCAT(T2.user_nm1,' ',T2.user_nm2, ' ', T2.user_ap1, ' ', T2.user_ap2) as nombre,
    T1.estado_impreso,T1.user_id,T1.fotografia,T1.user_id
    FROM vp_user_carnet AS T1
    INNER JOIN vp_user AS T2 ON T1.user_id=T2.user_id
    WHERE T1.year=? AND CONCAT(T1.grupo_id,T1.subgrupo_id,T1.renglon_id)=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($year,'029'));
    $carnets = $q->fetchAll();
    return $carnets;

  }

  Database::disconnect();
}

}
