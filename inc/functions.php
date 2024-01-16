<?php
require_once 'Database.php';
require_once 'User.php';
require_once 'Role.php';
require_once 'PrivilegedUser.php';
require_once 'Modulo.php';
require_once 'Privilegio.php';


function sec_session_start() {
    $session_name = 'saas_sec_session';   // Set a custom session name
    $secure = false;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
                              $cookieParams["path"],
                              $cookieParams["domain"],
                              $secure,
                              $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session
    //session_regenerate_id(true);    // regenerated the session, delete the old one.
}


function login($email, $password) {
    // Using prepared statements means that SQL injection is not possible.
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT TOP 1 a.primer_nombre, a.segundo_nombre,a.tercer_nombre,a.correo_electronico,
                   a.primer_apellido, a.segundo_apellido, a.tercer_apellido,
                   a.id_persona, b.persona_user, b.persona_pass, b.persona_salt,
                   b.status,b.mostrar_nombre
                   FROM rrhh_persona a
                   INNER JOIN rrhh_persona_usuario b ON b.id_persona=a.id_persona
                   WHERE b.correo_electronico=?
                   AND b.status LIKE 1
                   ';
    $u = $pdo->prepare($sql);
    if ($u) {
        $u->execute(array($email));
        $persona = $u->fetch(PDO::FETCH_ASSOC);
        Database::disconnect_sqlsrv();

        $user_id = $persona['id_persona'];
        $nom='';
        if($persona['mostrar_nombre']==1){
          $nom=$persona['primer_nombre'];
        }else{
          $nom=$persona['segundo_nombre'];
        }
        $nom_final = $nom.':'.$persona['primer_apellido'];
        $username = $nom_final;

        $db_password = $persona['persona_pass'];
        $salt = $persona['persona_salt'];

        $user_email = $persona['correo_electronico'];
        // hash the password with the unique salt.
        $password = hash('sha512', $password . $salt);
        if ($u->rowCount() == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts

            /*if (checkbrute($user_id) == true) {
                // Account is locked
                // Send an email to user saying their account is locked
                return false;
            } else {*/
                // Check if the password in the database matches
                // the password the user submitted.
                if ($db_password == $password) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['id_persona'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/",
                                             "",
                                             $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512',
                              $password . $user_browser);
                    //$_SESSION['role'] = $user_role;
                    $_SESSION['email'] = $user_email;
                    // Login successful.
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $login_computer = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                    /*$pdo = Database::connect();
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO vp_login(user_id, login_computer,login_ip)
                            VALUES (?, ?,?)";
                    $log = $pdo->prepare($sql);
                    $log->execute(array($user_id,$login_computer,$ip));
                    Database::disconnect();*/
                    return true;
              /*Password is not correct
                    // We record this attempt in the database
                    date_default_timezone_set('America/Guatemala');
                    $now = time();
                    $pdo = Database::connect();
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO vp_loginhistorial(user_id, login_fecha)
                                    VALUES (?, ?)";
                    $log = $pdo->prepare($sql);
                    $log->execute(array($user_id,$now));
                    Database::disconnect();
                    return false;
                }*/
            }
        } else {
            // No user exists.
            return false;
        }
    }
}

function checkbrute($user_id) {
    // Get timestamp of current time
    date_default_timezone_set('America/Guatemala');
    $now = time();
    // All login attempts are counted from the past 2 hours.
    $valid_attempts = $now - (2 * 60 * 60);
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT login_fecha
            FROM vp_loginhistorial
            WHERE user_id = ?
            AND login_fecha > ?';
    $u = $pdo->prepare($sql);
    $sql2 = 'SELECT user_fechahora, user_status
             FROM vp_user
             WHERE user_id = ?';
    $user = $pdo->prepare($sql2);
    $user->execute(array($user_id));
    $lastUpdate = $user->fetch(PDO::FETCH_ASSOC);
    if ($lastUpdate['user_status'] == 1 ){
        ((strtotime($lastUpdate['user_fechahora']) > $valid_attempts)? ($valid_attempts = strtotime($lastUpdate['user_fechahora'])):null);
    }
    if ($u) {
        $u->execute(array($user_id,$valid_attempts));
        $checkBrute = $u->fetchAll();
        // If there have been more than 5 failed logins
        if (count($checkBrute) >= 5) {
            $sql3 = 'UPDATE vp_user SET user_status = 0 WHERE user_id = ?';
            $disable = $pdo->prepare($sql3);
            $disable->execute(array($user_id));
            return true;
        } else {
            return false;
        }
    }
    Database::disconnect();
}

function verificar_session() {

if(isset($_SESSION['id_persona'],
          $_SESSION['username'],
          $_SESSION['login_string'],
          //$_SESSION['direccion'],
          //$_SESSION['role'],
          $_SESSION['email']))
{
  $user_id = $_SESSION['id_persona'];
  $login_string = $_SESSION['login_string'];
  $username = $_SESSION['username'];
  //$user_role = $_SESSION['role'];

  // Get the user-agent string of the user.
  $user_browser = $_SERVER['HTTP_USER_AGENT'];
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $query = "SELECT persona_pass
          FROM rrhh_persona_usuario
          WHERE id_persona = ? and status like 1";
  $statement = $pdo->prepare($query);
  $statement->execute(array($user_id));
  $persona = $statement->fetchAll();
  Database::disconnect_sqlsrv();
  $total_row = $statement->rowCount();
  if($total_row > 0)
  {
    foreach($persona as $row)
    {
      $password = $row['persona_pass'];
      $login_check = hash('sha512', $password . $user_browser);

      if ($login_check == $login_string ) {
          // Logged In!!!!
          return true;
      } else {
          // Not logged in
          return false;
      }

    }

  }


}
else
{
 return false;
}


}

function esc_url($url) {

    if ('' == $url) {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;

    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

//funciones de personal
function personas(){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT T1.user_id,user_vid,user_mail,ext_id,user_pref,user_nm1,user_nm2,user_ap1,user_ap2,user_cui,T1.dep_id,T2.dep_nm,user_puesto,user_nom,user_status,T1.role_id,role_nm,verificacion,
            CONCAT(grupo_id,subgrupo_id,renglon_Id)as renglon,fotografia,T5.dep_nm as dependencia
            FROM vp_user AS T1
            LEFT JOIN vp_deptos AS T2 ON T1.dep_id = T2.dep_id
            LEFT JOIN vp_roles AS T3 ON T1.role_id = T3.role_id
            LEFT JOIN vp_user_datos_laborales AS T4 ON T1.user_id=T4.user_id
            LEFT JOIN vp_deptos AS T5 ON T1.dependencia_id = T5.dep_id
            order by user_status DESC, user_nm1 ASC";
    $p = $pdo->prepare($sql);
    $p->execute();
    $personas = $p->fetchAll();
    Database::disconnect();
    return $personas;
}
function personas_por_renglon_029(){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT T1.user_id,user_vid,user_mail,ext_id,user_pref,user_nm1,user_nm2,user_ap1,user_ap2,user_cui,T1.dep_id,dep_nm,user_puesto,user_nom,user_status,T1.role_id,role_nm,verificacion,
            CONCAT(grupo_id,subgrupo_id,renglon_Id)as renglon,fotografia
            FROM vp_user AS T1
            LEFT JOIN vp_deptos AS T2 ON T1.dep_id = T2.dep_id
            LEFT JOIN vp_roles AS T3 ON T1.role_id = T3.role_id
            LEFT JOIN vp_user_datos_laborales AS T4 ON T1.user_id=T4.user_id
            WHERE CONCAT(grupo_id,subgrupo_id,renglon_Id) = ?
            order by user_status DESC";
    $p = $pdo->prepare($sql);
    $p->execute(array('029'));
    $personas = $p->fetchAll();
    Database::disconnect();
    return $personas;
}
function personas_por_renglon_011_022(){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT T1.user_id,user_vid,user_mail,ext_id,user_pref,user_nm1,user_nm2,user_ap1,user_ap2,user_cui,T1.dep_id,dep_nm,user_puesto,user_nom,user_status,T1.role_id,role_nm,verificacion,
            CONCAT(grupo_id,subgrupo_id,renglon_Id)as renglon,fotografia
            FROM vp_user AS T1
            LEFT JOIN vp_deptos AS T2 ON T1.dep_id = T2.dep_id
            LEFT JOIN vp_roles AS T3 ON T1.role_id = T3.role_id
            LEFT JOIN vp_user_datos_laborales AS T4 ON T1.user_id=T4.user_id
            WHERE CONCAT(grupo_id,subgrupo_id,renglon_Id) = ? OR CONCAT(grupo_id,subgrupo_id,renglon_Id) = ?
            order by user_status DESC";
    $p = $pdo->prepare($sql);
    $p->execute(array('011','022'));
    $personas = $p->fetchAll();
    Database::disconnect();
    return $personas;
}

function personas_depto($dep_id){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT T1.user_id,user_vid,user_mail,ext_id,user_pref,user_nm1,user_nm2,user_ap1,user_ap2,user_cui,T1.dep_id,dep_nm,user_puesto,user_nom,user_status,T1.role_id,role_nm
            FROM vp_user AS T1
            LEFT JOIN vp_deptos AS T2 ON T1.dep_id = T2.dep_id
            LEFT JOIN vp_roles AS T3 ON T1.role_id = T3.role_id
            WHERE T1.dep_id = ? AND user_status = 1
            order by user_nm1,user_ap1";
    $p = $pdo->prepare($sql);
    $p->execute(array($dep_id));
    $personas = $p->fetchAll();
    Database::disconnect();
    return $personas;
}


function personas_por_mes($fechai, $fechaf){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT T1.user_id,user_vid,user_mail,ext_id,user_pref,user_nm1,user_nm2,user_ap1,user_ap2,user_cui,T1.dep_id,user_puesto,user_nom,T1.user_status,T1.role_id,role_nm,T5.dep_nm as dependencia
            FROM vp_user AS T1
            LEFT JOIN vp_deptos AS T2 ON T1.dep_id = T2.dep_id
            LEFT JOIN vp_roles AS T3 ON T1.role_id = T3.role_id
            LEFT JOIN vp_user_status AS T4 ON T1.user_id = T4.user_id
            LEFT JOIN vp_deptos AS T5 ON T1.dependencia_id = T5.dep_id
            WHERE (T4.fecha_status BETWEEN ? AND ?)
            order by user_nm1,user_ap1";
    $p = $pdo->prepare($sql);
    $p->execute(array($fechai, $fechaf));
    $personas = $p->fetchAll();
    Database::disconnect();
    return $personas;
}

function personas_por_mes_horarios_copia($fechai, $fechaf){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT DISTINCT T1.user_id, user_vid, user_mail, ext_id, user_pref, user_nm1, user_nm2, user_ap1, user_ap2, T1.dep_id, dep_nm, user_puesto, user_nom, T1.role_id, role_nm
FROM vp_user AS T1
LEFT JOIN vp_deptos AS T2 ON T1.dep_id = T2.dep_id
LEFT JOIN vp_roles AS T3 ON T1.role_id = T3.role_id
INNER JOIN vp_user_horario AS T4 ON T1.user_vid = T4.user_id_huella
WHERE T4.horario
BETWEEN  ?
AND  ?
GROUP BY DATE( T4.horario ) , T1.user_id";
    $p = $pdo->prepare($sql);
    $p->execute(array($fechai, $fechaf));
    $personas = $p->fetchAll();
    Database::disconnect();
    return $personas;
}

function personas_por_mes_horarios($fechai, $fechaf){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT DISTINCT T1.user_id, T1.user_vid, user_mail, ext_id, user_pref, user_nm1, user_nm2, user_ap1, user_ap2, T1.dep_id, dep_nm, user_puesto, user_nom, T1.role_id, role_nm
FROM vp_user AS T1
LEFT JOIN vp_deptos AS T2 ON T1.dep_id = T2.dep_id
LEFT JOIN vp_roles AS T3 ON T1.role_id = T3.role_id
LEFT JOIN vp_user_horario_general AS T4 ON T1.user_vid = T4.user_vid
WHERE MONTH(T4.fecha_laboral) = ? AND YEAR(T4.fecha_laboral)=?
GROUP BY T4.fecha_laboral , T1.user_id";
    $p = $pdo->prepare($sql);
    $p->execute(array($fechai, $fechaf));
    $personas = $p->fetchAll();
    Database::disconnect();
    return $personas;
}
function personas_por_mes_horarios_dep($fechai, $fechaf,$dep){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT DISTINCT T1.user_id, T1.user_vid, user_mail, ext_id, user_pref, user_nm1, user_nm2, user_ap1, user_ap2, T1.dep_id, dep_nm, user_puesto, user_nom, T1.role_id, role_nm
FROM vp_user AS T1
LEFT JOIN vp_deptos AS T2 ON T1.dep_id = T2.dep_id
LEFT JOIN vp_roles AS T3 ON T1.role_id = T3.role_id
LEFT JOIN vp_user_horario_general AS T4 ON T1.user_vid = T4.user_vid
WHERE MONTH(T4.fecha_laboral) = ? AND YEAR(T4.fecha_laboral)=? AND T1.dep_id=?
GROUP BY T4.fecha_laboral , T1.user_id";
    $p = $pdo->prepare($sql);
    $p->execute(array($fechai, $fechaf,$dep));
    $personas = $p->fetchAll();
    Database::disconnect();
    return $personas;
}

function personas_por_semana_horario_especial($semana,$year){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT CONCAT(T1.user_nm1, ' ', T1.user_nm2, ' ', T1.user_ap1, ' ', T1.user_ap2) as nombre,T1.user_id,T1.user_vid
            FROM vp_user AS T1
            INNER JOIN vp_user_horario_especial_grupo AS T2 ON T2.user_id=T1.user_id
            INNER JOIN vp_user_horario_semana_detalle AS T3 ON T3.grupo=T2.horario_especial_id

            WHERE T3.semana=? AND T3.year=?
            GROUP BY T1.user_id";
    $p = $pdo->prepare($sql);
    $p->execute(array($semana,$year));
    $personas = $p->fetchAll();
    Database::disconnect();
    return $personas;
}

function personas_por_grupo_horario_especial()
{
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT CONCAT( T1.user_nm1,  ' ', T1.user_nm2,  ' ', T1.user_ap1,  ' ', T1.user_ap2 ) AS nombre, T1.user_id, T1.user_vid, T1.user_status, T3.horario_especial_desc
          FROM vp_user AS T1
          INNER JOIN vp_user_horario_especial_grupo AS T2 ON T2.user_id = T1.user_id
          INNER JOIN vp_horario_especial_grupo AS T3 ON T2.horario_especial_id = T3.horario_especial_id";
  $p = $pdo->prepare($sql);
  $p->execute(array());
  $personas = $p->fetchAll();
  Database::disconnect();
  return $personas;
}

function get_semanas_por_year($year,$user_id){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM vp_user_horario_semana AS T1";
  $p = $pdo->prepare($sql);
  $p->execute(array());
  $personas = $p->fetchAll();
  Database::disconnect();
  return $personas;
}

function departamentos(){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT T1.dep_id, dep_nm,dep_encargado as encargado_id,concat(T2.user_nm1,' ', T2.user_nm2, ' ', T2.user_ap1, ' ',T2.user_ap2) as dep_encargado, dep_status, T1.dep_padre
            FROM vp_deptos as T1
            LEFT JOIN vp_user as T2 on T1.dep_encargado = T2.user_id
            order by dep_nm ASC ";
    $r = $pdo->prepare($sql);
    $r->execute();
    $departamentos = $r->fetchAll();
    Database::disconnect();
    return $departamentos;
}

function get_departamento_by_id($dep_id){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM vp_deptos WHERE dep_id=? ";
    $r = $pdo->prepare($sql);
    $r->execute(array($dep_id));
    $departamento = $r->fetch();
    Database::disconnect();
    return $departamento;
}

function get_nacionalidad_by_id($id){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM vp_nacionalidad WHERE nac_id=? ";
    $r = $pdo->prepare($sql);
    $r->execute(array($id));
    $nac = $r->fetch();
    Database::disconnect();
    return $nac;
}

function tipos_dias_laborales(){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM vp_catalogo_dia_laboral
            where dia_laboral_id <> 0 AND dia_laboral_id <> 1 AND dia_laboral_id <> 3
            AND dia_laboral_id <> 5";
    $r = $pdo->prepare($sql);
    $r->execute();
    $dias = $r->fetchAll();
    Database::disconnect();
    return $dias;
}

function tipos_dias_laborales_suspencion(){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM vp_catalogo_dia_laboral
            where dia_laboral_id = 2 OR dia_laboral_id = 5 OR dia_laboral_id = 6 ";
    $r = $pdo->prepare($sql);
    $r->execute();
    $dias = $r->fetchAll();
    Database::disconnect();
    return $dias;
}

function tipos_dias_laborales_suspencion_igss(){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM vp_catalogo_dia_laboral
            where dia_laboral_id <> 0 AND dia_laboral_id <> 1 AND dia_laboral_id <> 3 AND dia_laboral_id <> 7 AND dia_laboral_id <> 50 AND dia_laboral_id <> 51";
    $r = $pdo->prepare($sql);
    $r->execute();
    $dias = $r->fetchAll();
    Database::disconnect();
    return $dias;
}


function roles(){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT role_id, role_nm
            FROM vp_roles AS T1
            order by role_id";
    $p = $pdo->prepare($sql);
    $p->execute();
    $roles = $p->fetchAll();
    Database::disconnect();
    return $roles;
}

//arreglar

function permisos(){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT perm_id, perm_desc
            FROM vp_permisos AS T1
            order by perm_id";
    $p = $pdo->prepare($sql);
    $p->execute();
    $permisos = $p->fetchAll();
    Database::disconnect();
    return $permisos;
}

//VERIFICAR ROL Y PERMISO
function verificar_per_rol($rol, $per){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT count(*) as conteo from vp_role_perm WHERE role_id=? and perm_id=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($rol, $per));
    $per_rol = $p->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();

    $conteo = $per_rol['conteo'];
    return $conteo;
}
// usuarios sub permisos por departamentos

function get_sub_permiso_dep($dep_id){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT sub_perm_id, dep_id, sub_perm_nm
            FROM vp_sub_perm_dep AS T1
            WHERE dep_id=?
            order by sub_perm_id";
    $p = $pdo->prepare($sql);
    $p->execute(array($dep_id));
    $subperm = $p->fetchAll();
    Database::disconnect();
    return $subperm;
}

function get_permisos_por_permiso($permiso){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT T1.permiso_por_permiso_id,T1.permiso_por_permiso_nm
            FROM vp_permiso_por_permiso AS T1
            INNER JOIN vp_permisos AS T2 ON T1.perm_id=T2.perm_id
            WHERE T2.perm_desc=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($permiso));
    $subperm = $p->fetchAll();
    Database::disconnect();
    return $subperm;
}

function get_personas_por_permiso($permiso){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT T1.user_id, CONCAT(T1.user_nm1, ' ', T1.user_nm2,' ',T1.user_ap1,' ', T1.user_ap2) AS NOMBRE
            FROM vp_user AS T1
            INNER JOIN vp_roles AS T2 ON T1.role_id=T2.role_id
            INNER JOIN vp_role_perm AS T3 ON T3.role_id=T2.role_id
            INNER JOIN vp_permisos AS T4 ON T4.perm_id=T3.perm_id
            WHERE T4.perm_desc=? AND T1.user_status=? AND T1.dep_id<>?";
    $p = $pdo->prepare($sql);
    $p->execute(array($permiso,1,9));
    $subperm = $p->fetchAll();
    Database::disconnect();
    return $subperm;
}


//VERIFICAR ROL Y PERMISO
function verificar_subperm_user($subper, $user){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT count(*) as conteo from vp_sub_perm_user WHERE sub_perm_id=? and user_id=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($subper, $user));
    $subper_user = $p->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();

    $conteo = $subper_user['conteo'];
    return $conteo;
}

function verificar_permiso_por_permiso_user($permiso, $user){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT count(*) as conteo from vp_permiso_por_permiso_user WHERE permiso_por_permiso_id=? and user_id=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($permiso, $user));
    $subper_user = $p->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();

    $conteo = $subper_user['conteo'];
    return $conteo;
}

function verificar_exist_sub_perm($dep_id){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT count(*) as conteo from vp_sub_perm_dep WHERE dep_id=?";
  $p = $pdo->prepare($sql);
  $p->execute(array($dep_id));
  $veri = $p->fetch(PDO::FETCH_ASSOC);
  Database::disconnect();

  $conteo = $veri['conteo'];
  return $conteo;
}


//VERIFICAR SI USUARIO ES EL DIRECTOR DEL DEPARTAMENTO

function verificar_director($id){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT COUNT(*) as conteo from vp_deptos where dep_encargado = ?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id));
    $per_rol = $p->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();

    $conteo = $per_rol['conteo'];
    if($conteo > 0)
    {
      return true;
    }
    else {
      return false;
    }
}


function tipo_de_horarios(){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT hora_id, HOUR(hora_inicio) AS h_i, HOUR(hora_final) AS h_f
            FROM vp_catalogo_horario AS T1
            order by hora_id";
    $p = $pdo->prepare($sql);
    $p->execute();
    $permisos = $p->fetchAll();
    Database::disconnect();
    return $permisos;
}

function get_renglones($grupo)
{
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT CONCAT(r1.grupo_id, r2.subgrupo_id, r3.renglon_id) AS renglon, r3.renglon_nm
FROM vp_catalogo_grupo_gasto r1
INNER JOIN vp_catalogo_subgrupo_gasto r2 ON r2.grupo_id=r1.grupo_id INNER JOIN vp_renglon r3 ON r3.subgrupo_id=r2.subgrupo_id
WHERE r3.grupo_id=?";
  $p = $pdo->prepare($sql);
  $p->execute(array($grupo));
  $renglones = $p->fetchAll();
  Database::disconnect();
  return $renglones;
}
function get_renglones_011_022($grupo)
{
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT CONCAT(r1.grupo_id, r2.subgrupo_id, r3.renglon_id) AS renglon, r3.renglon_nm
FROM vp_catalogo_grupo_gasto r1
INNER JOIN vp_catalogo_subgrupo_gasto r2 ON r2.grupo_id=r1.grupo_id INNER JOIN vp_renglon r3 ON r3.subgrupo_id=r2.subgrupo_id
WHERE r3.grupo_id=? AND CONCAT(r1.grupo_id, r2.subgrupo_id, r3.renglon_id)=? OR CONCAT(r1.grupo_id, r2.subgrupo_id, r3.renglon_id)=?";
  $p = $pdo->prepare($sql);
  $p->execute(array($grupo,'011','022'));
  $renglones = $p->fetchAll();
  Database::disconnect();
  return $renglones;
}
function get_renglones_029($grupo)
{
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT CONCAT(r1.grupo_id, r2.subgrupo_id, r3.renglon_id) AS renglon, r3.renglon_nm
FROM vp_catalogo_grupo_gasto r1
INNER JOIN vp_catalogo_subgrupo_gasto r2 ON r2.grupo_id=r1.grupo_id INNER JOIN vp_renglon r3 ON r3.subgrupo_id=r2.subgrupo_id
WHERE r3.grupo_id=? AND CONCAT(r1.grupo_id, r2.subgrupo_id, r3.renglon_id)=?";
  $p = $pdo->prepare($sql);
  $p->execute(array($grupo,'029'));
  $renglones = $p->fetchAll();
  Database::disconnect();
  return $renglones;
}

function get_renglones_diferentes($renglon){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT CONCAT(r1.grupo_id, r2.subgrupo_id, r3.renglon_id) AS renglon, r3.renglon_nm
FROM vp_catalogo_grupo_gasto r1
INNER JOIN vp_catalogo_subgrupo_gasto r2 ON r2.grupo_id=r1.grupo_id INNER JOIN vp_renglon r3 ON r3.subgrupo_id=r2.subgrupo_id
WHERE CONCAT(r1.grupo_id, r2.subgrupo_id, r3.renglon_id)<>?";
  $p = $pdo->prepare($sql);
  $p->execute(array($renglon));
  $renglones = $p->fetchAll();
  Database::disconnect();
  return $renglones;
}

function get_nacionalidades()
{
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT nac_id, gentilicio FROM vp_nacionalidad";
  $p = $pdo->prepare($sql);
  $p->execute();
  $n = $p->fetchAll();
  Database::disconnect();
  return $n;
}

function get_genero()
{
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM vp_catalogo_genero";
  $p = $pdo->prepare($sql);
  $p->execute();
  $n = $p->fetchAll();
  Database::disconnect();
  return $n;
}

function get_renglon_by_id($g,$s,$r)
{

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM vp_renglon WHERE grupo_id=? AND subgrupo_id=? AND renglon_id=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($g,$s,$r));
    $n = $p->fetch();
    Database::disconnect();
    return $n;

}

function get_estado_civil()
{
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM vp_catalogo_estado_civil";
  $p = $pdo->prepare($sql);
  $p->execute();
  $n = $p->fetchAll();
  Database::disconnect();
  return $n;
}


//fin

function usuarioPrivilegiado(){
    $u = PrivilegedUser::getByUserId($_SESSION["id_persona"]);
    return $u;
}
function usuarioPrivilegiado_acceso(){
    $u = PrivilegedUser::getByUserId_acceso($_SESSION["id_persona"]);
    return $u;
}

function permiso_dep($permiso){
  $us = $_SESSION["user_id"];
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT count(*) as conteo from vp_sub_perm_user WHERE sub_perm_id=? and user_id=?";
  $p = $pdo->prepare($sql);
  $p->execute(array($permiso, $us));
  $p_u = $p->fetch(PDO::FETCH_ASSOC);
  Database::disconnect();

  $conteo = $p_u['conteo'];
  if($conteo > 0)
  {
    return true;
  }
  else {
    return false;
  }
}

function permiso_perm($permiso){
  $us = $_SESSION["user_id"];
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT count(*) as conteo from vp_permiso_por_permiso_user WHERE permiso_por_permiso_id=? and user_id=?";
  $p = $pdo->prepare($sql);
  $p->execute(array($permiso, $us));
  $p_u = $p->fetch(PDO::FETCH_ASSOC);
  Database::disconnect();

  $conteo = $p_u['conteo'];
  if($conteo > 0)
  {
    return true;
  }
  else {
    return false;
  }
}

function get_literales_029(){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM vp_catalogo_literal_029";
  $p = $pdo->prepare($sql);
  $p->execute();
  $l= $p->fetchAll();
  Database::disconnect();
  return $l;
}

function get_detalle_asueto($fecha){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT asueto_titulo from vp_calendario_decreto WHERE asueto_fecha=?";
  $p = $pdo->prepare($sql);
  $p->execute(array($fecha));
  $asueto = $p->fetch(PDO::FETCH_ASSOC);
  Database::disconnect();
  return $asueto;
}

function fechaCastellano ($fecha) {
  $fecha = substr($fecha, 0, 10);
  $numeroDia = date('d', strtotime($fecha));
  $dia = date('l', strtotime($fecha));
  $mes = date('F', strtotime($fecha));
  $anio = date('Y', strtotime($fecha));
  $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
  $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
  $nombredia = str_replace($dias_EN, $dias_ES, $dia);
$meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
  $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
  //return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
  return $numeroDia." de ".$nombreMes." de ".$anio;
}

function CREAR_TOKEN($TokenForm)
{ $token = md5(uniqid(microtime(), true));
  $token_time = time();
  $_SESSION['csrf'][$TokenForm.'_token'] = array('token'=>$token, 'time'=>$token_time);
   return $token;
}
function VERIFICA_TOKEN($TokenForm, $token)
{  if(!isset($_SESSION['csrf'][$TokenForm.'_token'])) {
       return false;
   }
   if ($_SESSION['csrf'][$TokenForm.'_token']['token'] !== $token) {
       return false;
   }
   return true;
}


function pathRoot(){
    $pathRoot = '/herramientas';
    return $pathRoot;
}

function unauthorized(){
    $unauthorized = '/inc/401.php';
    return $unauthorized;
}

function unauthorizedModal(){
    $unauthorized = '../inc/401.php';
    return $unauthorized;
}

function fecha_dmy($fecha){
    $fecha = date("d-m-Y",strtotime($fecha));
    return $fecha;
}

function fecha_ymd($fecha){
    $fecha = date("Y-m-d",strtotime($fecha));
    return $fecha;
}

function sanear_string($string)
{

    $string = trim($string);

    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('Ñ', 'Ñ', 'c', 'C',),
        $string
    );

    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":"),
        '',
        $string
    );


    return $string;
}


function numtoletras($xcifra)
{
    $xarray = array(0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
//
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }

    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
            }

            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas

                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {

                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada

                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = subfijo($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO

        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena.= " DE";

        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena.= " DE";

        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN BILLON ";
                    else
                        $xcadena.= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN MILLON ";
                    else
                        $xcadena.= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        //$xcadena = "CERO PESOS $xdecimales/100 M.N.";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        //$xcadena = "UN PESO $xdecimales/100 M.N. ";
                    }
                    if ($xcifra >= 2) {
                        //$xcadena.= " PESOS $xdecimales/100 M.N. "; //
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para México se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UNO UNO", "UNO", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DIECISEIS", "DIECISEIS", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("VEINTIUN", "VEINTIUN", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("VEINTISEIS", "VEINTISEIS", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("UN MIL", "MIL", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
}

// END FUNCTION

function subfijo($xx)
{ // esta función regresa un subfijo para la cifra
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
        $xsub = "";
    //
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
        $xsub = "MIL";
    //
    return $xsub;
}

// END FUNCTION


/*------------------------------------------------------------------
 * hug0
 * Blog Kiuvox
 * funcion para generar la fecha en letras
 * http://blog.kiuvox.com
 * ----------------------------------------------------------------
 */
function fechaATexto($fecha){
  $fecha_separada=explode("/", $fecha);

  $dia= strtolower(numtoletras($fecha_separada[0]));

  switch ($fecha_separada[1]) {

    case "01":
        $mes="ENERO";
      break;
    case "02":
        $mes="FEBRERO";
      break;
    case "03":
        $mes="MARZO";
      break;
    case "04":
        $mes="ABRIL";
      break;
    case "05":
        $mes="MAYO";
      break;
    case "06":
        $mes="JUNIO";
      break;
    case "07":
        $mes="JULIO";
      break;
    case "08":
        $mes="AGOSTO";
      break;
    case "09":
        $mes="SEPTIEMBRE";
      break;
    case "10":
        $mes="OCTUBRE";
      break;
    case "11":
        $mes="NOVIEMBRE";
      break;
    case "12":
        $mes="DICIEMBRE";
      break;

    default:
      break;
  }

  $anio= strtolower(numtoletras($fecha_separada[2]));


  return "$dia DIAS DEL MES DE $mes DEL AÑO $anio.";
}

function fechaATextoNumeroIncluido($fecha){
  $fecha_separada=explode("/", $fecha);

  $dia= strtolower(numtoletras($fecha_separada[0]));

  switch ($fecha_separada[1]) {

    case "01":
        $mes="ENERO";
      break;
    case "02":
        $mes="FEBRERO";
      break;
    case "03":
        $mes="MARZO";
      break;
    case "04":
        $mes="ABRIL";
      break;
    case "05":
        $mes="MAYO";
      break;
    case "06":
        $mes="JUNIO";
      break;
    case "07":
        $mes="JULIO";
      break;
    case "08":
        $mes="AGOSTO";
      break;
    case "09":
        $mes="SEPTIEMBRE";
      break;
    case "10":
        $mes="OCTUBRE";
      break;
    case "11":
        $mes="NOVIEMBRE";
      break;
    case "12":
        $mes="DICIEMBRE";
      break;

    default:
      break;
  }

  $anio= strtolower(numtoletras($fecha_separada[2]));


  return "$dia ($fecha_separada[0]) DIAS DEL MES DE $mes ($fecha_separada[1]) DEL AÑO $anio ($fecha_separada[2]).";
}



function fechaATexto22($fecha){
  $fecha_separada=explode("/", $fecha);

  $dia= strtolower(numtoletras($fecha_separada[0]));

  switch ($fecha_separada[1]) {

    case "01":
        $mes="ENERO";
      break;
    case "02":
        $mes="FEBRERO";
      break;
    case "03":
        $mes="MARZO";
      break;
    case "04":
        $mes="ABRIL";
      break;
    case "05":
        $mes="MAYO";
      break;
    case "06":
        $mes="JUNIO";
      break;
    case "07":
        $mes="JULIO";
      break;
    case "08":
        $mes="AGOSTO";
      break;
    case "09":
        $mes="SEPTIEMBRE";
      break;
    case "10":
        $mes="OCTUBRE";
      break;
    case "11":
        $mes="NOVIEMBRE";
      break;
    case "12":
        $mes="DICIEMBRE";
      break;

    default:
      break;
  }

  $anio= strtolower(numtoletras($fecha_separada[2]));


  return "$dia DE $mes DE $anio.";
}


/**
 * Clase que implementa un coversor de números
 * a letras.
 *
 * Soporte para PHP >= 5.4
 * Para soportar PHP 5.3, declare los arreglos
 * con la función array.
 *
 * @author AxiaCore S.A.S
 *
 */
class NumeroALetras
{
    private static $UNIDADES = [
        '',
        'UNO ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];
    private static $DECENAS = [
        'VEINTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];
    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];
    public static function convertir($number, $moneda = '', $centimos = '', $forzarCentimos = false)
    {
        $converted = '';
        $decimales = '';
        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }
        $div_decimales = explode('.',$number);
        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                $decCientos = substr($decNumberStrFill, 6);
                $decimales = self::convertGroup($decCientos);
            }
        }
        else if (count($div_decimales) == 1 && $forzarCentimos){
            $decimales = 'CERO ';
        }
        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);
        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }
        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }
        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }
        if(empty($decimales)){
            $valor_convertido = $converted . strtoupper($moneda);
        } else {
            $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . $decimales . ' ' . strtoupper($centimos);
        }
        return $valor_convertido;
    }
    private static function convertGroup($n)
    {
        $output = '';
        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];
        }
        $k = intval(substr($n,1));
        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }
        return $output;
    }
}

function time_diff_string($from, $to, $full = false) {
  $from = new DateTime($from); $to = new DateTime($to);
  $diff = $to->diff($from);
  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;
  $string = array( 'y' => 'year', 'm' => 'mes', 'w' => 'semana', 'd' => 'dia', 'h' => 'hora', 'i' => 'minuto' );
  foreach ($string as $k => &$v) {
    if ($diff->$k) { $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    } else { unset($string[$k]); }
  }
  if (!$full) $string = array_slice($string, 0, 1);
  if($from < $to){
    return $string ? implode(', ', $string) . ' pasaron' : 'just now';
  }
  else{
    return $string ? implode(', ', $string) . ' faltan' : 'just now';
  }

}

function calcular_tiempo_trasnc($hora1,$hora2){
    $separar[1]=explode(':',$hora1);
    $separar[2]=explode(':',$hora2);

$total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1];
$total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1];
$total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2];
if($total_minutos_trasncurridos<=59) return($total_minutos_trasncurridos.'');
elseif($total_minutos_trasncurridos>59){
$HORA_TRANSCURRIDA = round($total_minutos_trasncurridos/60);
if($HORA_TRANSCURRIDA<=9) $HORA_TRANSCURRIDA='0'.$HORA_TRANSCURRIDA;
$MINUITOS_TRANSCURRIDOS = $total_minutos_trasncurridos%60;
if($MINUITOS_TRANSCURRIDOS<=9) $MINUITOS_TRANSCURRIDOS='0'.$MINUITOS_TRANSCURRIDOS;
return ($HORA_TRANSCURRIDA.':'.$MINUITOS_TRANSCURRIDOS.' Horas');

} }


function get_meses_diferencia($inicio,$fin){
  $datetime1=new DateTime($inicio);
  $datetime2=new DateTime($fin);

  # obtenemos la diferencia entre las dos fechas
  $interval=$datetime2->diff($datetime1);

  # obtenemos la diferencia en meses
  $intervalMeses=$interval->format("%m");
  # obtenemos la diferencia en años y la multiplicamos por 12 para tener los meses
  $intervalAnos = $interval->format("%y")*12;

  return $intervalMeses+$intervalAnos;

}
function verificar_modulos_asignados_persona($id_persona){
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT COUNT(*) AS CONTEO FROM tbl_accesos_usuarios
          WHERE id_persona=?";
  $p = $pdo->prepare($sql);
  $p->execute(array($id_persona));
  $datos = $p->fetch();
  Database::disconnect();
  return $datos;
}

function url(){
  return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $_SERVER['REQUEST_URI']
  );
}

function base_url($url) {
$result = parse_url($url);
return $result['scheme']."://".$result['host'].':8080/'.'saas_app'.'/';
}

class datos{
  static function get_fotografia($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP 1 fotografia
            FROM rrhh_persona_fotografia
            WHERE id_persona=? ORDER BY id_fotografia DESC
            ";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $empleado = $p->fetch();
    Database::disconnect_sqlsrv();
    return $empleado;
  }
}
function get_empleado_by_session_direccion($id_persona){
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT DISTINCT a.id_dirf, a.id_persona, a.nombre, a.p_funcional, a.p_nominal, a.fecha_ingreso,
          CASE
          WHEN a.id_tipo=2 THEN '031' WHEN a.id_tipo=3 THEN '029' ELSE '011' END AS renglon, a.estado,
          a.id_tipo,a.dir_nominal,a.dir_funcional,
          CASE
          WHEN a.id_tipo=2 THEN a.dir_funcional WHEN a.id_tipo=4 THEN 'APOYO' ELSE a.dir_nominal END AS direccion,
          sd.SUELDO, f.fecha_toma_posesion, f.fecha_efectiva_resicion,f.id_status
                      FROM xxx_rrhh_Ficha a
          LEFT JOIN rrhh_plazas_sueldo b ON b.id_plaza=a.id_plaza

          LEFT JOIN (SELECT a.id_persona,
          SUM(c.monto_p) AS SUELDO
                      FROM xxx_rrhh_Ficha a
          LEFT JOIN rrhh_plazas_sueldo b ON b.id_plaza=a.id_plaza
          LEFT JOIN rrhh_plazas_sueldo_detalle c ON c.id_sueldo=b.id_sueldo

                      WHERE  actual =1

          group by a.id_persona) AS sd ON sd.id_persona=a.id_persona

          LEFT JOIN rrhh_persona_fotografia d ON d.id_persona=a.id_persona
    LEFT JOIN rrhh_empleado e ON a.id_persona=e.id_persona
    LEFT JOIN rrhh_empleado_plaza f ON f.id_empleado=e.id_empleado

          WHERE a.estado=1 AND f.id_status=891 AND a.id_persona=?
          ";


  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($id_persona));
  $nombramientos = $stmt->fetch();
  Database::disconnect_sqlsrv();
  return $nombramientos;
}

function contar_dias($f1,$f2){
  $datetime1 = new DateTime($f1);
$datetime2 = new DateTime($f2);
$interval = $datetime1->diff($datetime2);
  $x = $interval->format('%R%a');
  return $x;
}

function get_nombre_mes($n){

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

function get_nombre_mes_corto($n){

//el parametro w en la funcion date indica que queremos el dia de la semana
//lo devuelve en numero 0 domingo, 1 lunes,....
switch ($n){
case 1: return "Ene"; break;
case 2: return "Feb"; break;
case 3: return "Mar"; break;
case 4: return "Abr"; break;
case 5: return "May"; break;
case 6: return "Jun"; break;
case 7: return "Jul"; break;
case 8: return "Ago"; break;
case 9: return "Sep"; break;
case 10: return "Oct"; break;
case 11: return "Nov"; break;
case 12: return "Dic"; break;
}
}
function dias_de_un_mes($year,$mes){
  $dias_febrero;
  if((fmod($year,4)==0 && fmod($year,100)!==0) || fmod($year,400)==0){
    $dias_febrero=29;
  }else{
    $dias_febrero=28;
  }
  switch($mes){
    case '01':return 31; break;
    case '02':return $dias_febrero; break;
    case '03':return 31; break;
    case '04':return 30; break;
    case '05':return 31; break;
    case '06':return 30; break;
    case '07':return 31; break;
    case '08':return 31; break;
    case '09':return 30; break;
    case '10':return 31; break;
    case '11':return 30; break;
    case '12':return 31; break;
  }
}

function count_days($year,$month){
  $daysInMonth=dias_de_un_mes($year,$month);
  $workDays=0;
  $myTime=strtotime($year.'-'.$month.'-'.'01');

  while($daysInMonth>0){
    $day=date("D,",$myTime);
    if(strcmp(date('D',$myTime),'Sun')!=0 && strcmp(date('D',$myTime),'Sat')!=0){
      $workDays++;
    }
    $daysInMonth--;
    $myTime+=86400;
  }
  return $workDays;
}

function evaluar_flag($id_persona,$id_sistema,$id_pantalla, $flag){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_pantalla, a.id_acceso, b.id_persona
            FROM tbl_accesos_usuarios_det a
            INNER JOIN tbl_accesos_usuarios b ON a.id_acceso=b.id_acceso
            WHERE b.id_persona=? AND b.id_sistema=? AND a.id_pantalla=? AND $flag=1";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona,$id_sistema,$id_pantalla));
    $f_valor = $p->fetch();
    Database::disconnect_sqlsrv();
    if(!empty($f_valor['id_pantalla'])){
        return 1;
    }else{
        return 0;
    }

}

function insertar_bitacora($tipo_operacion,$descripcion,$pantalla){
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT TOP 1 id_auditoria,reng_num
          FROM tbl_bitacora_evento
          WHERE id_auditoria=?
          ORDER BY reng_num DESC
          ";
  $p = $pdo->prepare($sql);
  $p->execute(array(0));
  $auditoria = $p->fetch();

  $sql2 = "INSERT INTO tbl_bitacora_evento (reng_num,fecha,id_tipo_op,id_usuario,descripcion,equipo,domain_user,id_pantalla) VALUES(?,?,?,?,?,?,?,?)";
  $p2 = $pdo->prepare($sql2);
  $p2->execute(array(($auditoria['reng_num']+1),date('Y-m-d H:i:s'),$tipo_operacion,$_SESSION['id_persona'],$descripcion,$_SERVER["REMOTE_ADDR"],$_SESSION['email'],$pantalla));

  Database::disconnect_sqlsrv();

}
function evaluar_flags_by_sistema($id_persona,$id_sistema){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_pantalla, a.id_acceso, b.id_persona,reng_num,a.flag_es_menu,a.flag_insertar,a.flag_eliminar,
            a.flag_actualizar,a.flag_imprimir,a.flag_acceso,a.flag_autoriza,a.flag_descarga

            FROM tbl_accesos_usuarios_det a
            INNER JOIN tbl_accesos_usuarios b ON a.id_acceso=b.id_acceso
            WHERE b.id_persona=? AND b.id_sistema=? AND b.id_status = ?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona,$id_sistema, 1119));
    $array = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $array;

}

function createLog($pantalla, $sistema, $tabla,$descripcion, $valoranterior, $valornuevo){
  date_default_timezone_set('America/Guatemala');
  $compu_cliente= gethostbyaddr($_SERVER['REMOTE_ADDR']);
  $ip_address = $_SERVER['REMOTE_ADDR'];//gethostbyname($compu_cliente);

  exec("wmic /node:$_SERVER[REMOTE_ADDR] COMPUTERSYSTEM Get UserName", $user);


  $valor_anterior = array(
    'id_persona'=>$_SESSION['id_persona'],
    'anterior'=>$valoranterior
    //'estado'=>1051
  );

  $valor_nuevo = array(

    'id_persona'=>$_SESSION['id_persona'],
    //'usuario'=>$_POST["email"],
    'descripcion'=>$descripcion,
    'fecha'=>date('Y-m-d H:i:s'),
    'equipo'=>$compu_cliente,
    'ip_address'=>$ip_address,
    'usuario_pc'=>$user[1],
    'nuevo'=>$valornuevo
    //json_encode($user)
  );
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $log = "VALUES($pantalla, $sistema, '$tabla', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
  $sql2="INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
  $q2 = $pdo->prepare($sql2);
  $q2->execute(array());
  Database::disconnect_sqlsrv();
}
function retornaTipoViatico($valor){
  $arreglo = array(1 => 'Desayuno', 2 => 'Almuerzo', 3 => 'Cena', 4 => 'Hospedaje');
  return $arreglo[$valor];
}

function retornaConceptoFactura($valor){
  $concepto = 0;
  if($valor == 1 || $valor == 2 || $valor == 3 ){
    $concepto = 1;
  }else{
    $concepto = 2;
  }
  return $concepto;
}

function retornaTipoDuracion($valor){
  $tipoduracion = array(1 => "Minuto (s)", 2 => "Hora (s)",3=>"Día (s)");
  return $tipoduracion[$valor];
}

function retornaEstadoSolicitud($valor){
  $estado = array(1 => "Pendiente", 2 => "Autorizado",3=>"Aprobado",4 => "Cancelado");
  $color = array(1 => "warning", 2 => "info", 3 => "success", 4 => "danger");
  $icon = array(1 => "times", 2 => "check-circle", 3 => "success", 4 => "times-circle");
  $porcentaje  = array(1 => "10", 2 => "40", 3 => "100", 4 => "100");
  $disponible = array(1 => true, 2 => false, 3=>false,4 => true);
  $array = array(
    'estado'=>$estado[$valor],
    'color'=>$color[$valor],
    'icon'=>$icon[$valor],
    'porcentaje'=>$porcentaje[$valor],
    'disponible'=>$disponible[$valor]
  );
  return $array;
}

function retornaEstadoSolicitudVehiculo($valor){
  $color = array(1 => "warning", 2 => "info", 3=> "info", 4 => "success", 5 => "danger");
  $estado = array(1 => "Sin Vehículo", 2 => "Asignado", 3=> "En curso", 4 => "Finalizado", 5 => "Cancelado");
  $icon = array(1 => "times", 2 => "check-circle", 3 => "spinner fa-spin", 4 => "check-circle");
  $porcentaje = array(1 => "10", 2 => "25", 3=> "60", 4 => "100", 5 => "100");
  $array = array(
    'estado'=>$estado[$valor],
    'color'=>$color[$valor],
    'icon'=>$icon[$valor],
    'porcentaje'=>$porcentaje[$valor]
  );
  return $array;
}

function retornaEstadoVehiculoAsignacion($valor){
  $color = array(1 => "info", 2 => "success", 3=> "danger");
  $icono = array(1 => "check-circle", 2 => "check-circle", 3=> "times-circle");
  $porcentaje = array(1 => "10", 2 => "25", 3=> "60", 4 => "100", 5 => "100");
  $array = array(
    'icono'=>$icono[$valor],
    'color'=>$color[$valor],
    'porcentaje'=>$porcentaje[$valor]
  );
  return $array;
}

function retornaEstadoVehiculoSeguimiento($valor){
  $color = array(0 => "warning", 1 => "info", 2 => "success", 3=> "danger");
  $icono = array(0 => "pause",1 => "arrow-right", 2 => "arrow-left", 3=> "times-circle");
  $porcentaje = array(0 => "0", 1 => "10", 2 => "25", 3=> "60", 4 => "100", 5 => "100");
  $array = array(
    'icono'=>$icono[$valor],
    'color'=>$color[$valor],
    'porcentaje'=>$porcentaje[$valor]
  );
  return $array;
}

function retornaTipoAsignacion($valor){
  $titpoasignacion = array(1 => "Llevar y Traer", 2 => "Llevar",3=>"Traer");
  return $titpoasignacion[$valor];
}
function retornaEstadoAsignacion($valor){
  $estado = array(1 => "Asignado", 2 => "En curso",3=>"Finalizado",4=>'Cancelado');
  $color = array(1=> "warning", 2 => "info", 3 => "success", 4=> "danger");
  $icono = array(1 => "check-circle", 2 => "spinner fa-spin", 3 => "check-circle", 4=> "times-circle");
  $porcentaje = array(1 => "10", 2 => "50", 3=> "100", 4 => "100");
  $array = array(
    'estado'=>$estado[$valor],
    'icon'=>$icono[$valor],
    'color'=>$color[$valor],
    'porcentaje'=>$porcentaje[$valor]
  );
  return $array;
}


//obtener empleados

function get_empleados_por_direccion($direccion)
{
  $persona = '';
  $direcciones = '(' . $direccion . ')';
  /*if ($direccion == 15) {
    $direcciones = '(4,6,7,8,9,12,15)';
  } else
    if ($direccion == 14) {
    $direcciones = '(1,5,10,11,207)';
  } else if ($direccion == 12) {
    // code...
    $direcciones = '(1,4,5,6,7,8,9,10,11,12,15,207)';
  }*/
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if($direccion != 79977){
    $sql2 = "SELECT DISTINCT
                A.id_persona,
                A.nombre AS nombre_completo,
                COALESCE
                (
                  A.id_dirf,
                  A.id_dirapy
                ) AS	id_direccion_funcional
                FROM
                dbo.xxx_rrhh_Ficha A
                WHERE
                COALESCE(
                    A.id_dirf,
                    A.id_dirapy
                  ) IN $direcciones
                  ORDER BY
                  A.nombre
      ";
  }else if($direccion == 79977 || $direccion == 207){
    $sql2 = "SELECT DISTINCT
    A.id_persona,
    A.nombre AS nombre_completo,
    COALESCE
    (
      A.id_dirf,
      A.id_dirapy
    ) AS	id_direccion_funcional
    FROM
    dbo.xxx_rrhh_Ficha A
    WHERE
    COALESCE(
        A.id_subdireccion_funcional,
        A.id_subdireccion_funcional,
A.id_pcontrato
      ) IN (34,37,7089)
      ORDER BY
      A.nombre
      ";
  }

  $stmt2 = $pdo->prepare($sql2);
  $stmt2->execute(array());
  $empleados = $stmt2->fetchAll();

  Database::disconnect_sqlsrv();
  return $empleados;
}
