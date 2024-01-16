<?php
include_once 'inc/functions.php';
include_once 'insumos/php/back/functions.php';
date_default_timezone_set('America/Guatemala');
sec_session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
// inicia proceso de validacion de usuario en Active Directory y acceso a los sistemas
error_reporting(0);
if (isset($_POST["email"])) {  //halo el usuario ingresado en pagina de logueo
    $ds = 'srvsaasdc002';
    //$ds = '172.16.10.101';
    $puertoldap = 389;
    $email = $_POST["email"] . '@saas.gob.gt';
    $usuario = $_POST['email'];
    $ldaprdn = $usuario . "@saas.local";
    $password = trim($_POST['password']);  //halo el password ingresado en pagina de logueo
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $login = false;

    if (strlen($ldaprdn) != 0 && strlen($password) != 0) {  // si usuario y contraseña no estan vacios
        // validacion de permisos y accesos al sistema
        $query = "SELECT TOP 1 a.primer_nombre, a.segundo_nombre,a.tercer_nombre,
              a.primer_apellido, a.segundo_apellido, a.tercer_apellido,
              a.id_persona, b.persona_user, b.persona_pass, b.persona_salt,
              isnull(a.correo_electronico,' ') AS correo_electronico, b.status,b.mostrar_nombre, b.valida_ldap,
			        h.vac_fch_ini, h.vac_fch_fin,
			        CONVERT(VARCHAR, GETDATE(), 23) AS fecha,
			        CASE WHEN CONVERT(VARCHAR, GETDATE(), 23) >=  h.vac_fch_ini AND CONVERT(VARCHAR, GETDATE(), 23) <= h.vac_fch_fin THEN 1 ELSE 0 END AS envacaciones
              FROM rrhh_persona a
              INNER JOIN rrhh_persona_usuario b ON b.id_persona=a.id_persona
              LEFT JOIN (SELECT  T.emp_id, T.vac_fch_ini, T.vac_fch_fin
                FROM
                (
                  SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY emp_id ORDER BY vac_id DESC) AS rnk FROM APP_VACACIONES.dbo.VACACIONES a
                  WHERE est_id = 5
                ) T
                WHERE T.rnk = 1
              ) AS h ON h.emp_id = a.id_persona
              WHERE b.persona_user=?
              AND b.status LIKE 1";
        $statement = $pdo->prepare($query);
        $statement->execute(array($email));
        $persona = $statement->fetchAll();
        Database::disconnect_sqlsrv();
        $total_row = $statement->rowCount();
        $msg = '';
        if ($total_row > 0) {
            foreach ($persona as $row) {
                $user_password = $row["persona_pass"];
                if ($row['envacaciones'] == 1) {
                    $msg = '<label class="text-danger slide_up_anim"><i class="fa fa-times"></i> Este empleado se encuentra en vacaciones.</label>';
                } else {
                    //inicio
                    if ($row['valida_ldap'] == 1) {  // si en la base de datos tiene que valide en Active Directory
                        $ldapconn = ldap_connect($ds, $puertoldap);
                        // Estableciendo la conexion con el servidor LDAP
                        define('LDAP_OPT_DIAGNOSTIC_MESSAGE', 0x0032);
                        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
                        if ($ldapconn) {  // si conecta
                            if (ldap_bind($ldapconn, $ldaprdn, $password)) { // si usuario y contraseña es correcto

                                if ($row['persona_pass'] != md5($password)) {
                                    $opciones = ['cost' => 12,];
                                    $passwordCrypt = password_hash($password, PASSWORD_BCRYPT, $opciones);
                                    $query2 = "update rrhh_persona_usuario set persona_pass = ?, persona_salt = ? where persona_user = ? and status like 1";
                                    $statement = $pdo->prepare($query2);
                                    $statement->execute(array(md5($password), $passwordCrypt, $email));
                                } else {
                                    $opciones = ['cost' => 12,];
                                    $passwordCrypt = password_hash($password, PASSWORD_BCRYPT, $opciones);
                                    $query2 = "update rrhh_persona_usuario set persona_pass = ?, persona_salt = ? where persona_user = ? and status like 1";
                                    $statement = $pdo->prepare($query2);
                                    $statement->execute(array(md5($password), $passwordCrypt, $email));
                                }
                                $user_password = md5($password);
                                $login = $sesion = inicio_sesion(md5($password), $user_password, $row);
                            } else {
                                ldap_get_option($ldapconn, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);
                                $msg = ldap_mensaje_error($extended_error);
                            }
                        } else {
                            $msg = '<label class="text-danger slide_up_anim"><i class="fa fa-times"></i> No se puede conectar al servidor de dominio.</label>';
                        }
                        ldap_close($ldapconn);
                    } else {
                        $login = $sesion = inicio_sesion(md5($password), $user_password, $row);
                        if (!$login)
                            $msg = '<label class="text-danger slide_up_anim"><i class="fa fa-times"></i> Password Incorrecto.</label>';
                    }
                    //fin
                }
            }
        } else {  // si no encontro usuario en tabla de accesos
            $msg = '<label class="text-danger  slide_up_anim"><i class="fa fa-times"></i> Este Email no tiene acceso al sistema  </label>';
        }
    } else {   // si email o password esta vacio
        $msg = '<label class="text-danger  slide_up_anim"><i class="fa fa-times"></i> Debe ingresar un Email y Password </label>';
    }
}

function inicio_sesion($password, $user_password, $persona)
{
    if ($password == $user_password) {   // si password es correcto
        $user_id = $persona['id_persona'];
        if ($persona['mostrar_nombre'] == 1) {
            $nom = $persona['primer_nombre'];
        } else {
            $nom = $persona['segundo_nombre'];
        }
        $nom_final = $nom . ' ' . $persona['primer_apellido'];
        $username = $nom_final;
        $salt = $persona['persona_salt'];
        $user_email = $persona['correo_electronico'];
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        $user_id = preg_replace("/[^0-9]+/", "", $user_id);
        $_SESSION['id_persona'] = $user_id;
        $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
        $_SESSION['username'] = $nom_final;
        $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
        $_SESSION['email'] = $user_email;

        $clase = new insumo();
        $datos = $clase->get_acceso_bodega_usuario($user_id);
        $bodega = 0;
        $compu_cliente = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $ip_address = $_SERVER['REMOTE_ADDR']; //gethostbyname($compu_cliente);

        exec("wmic /node:$_SERVER[REMOTE_ADDR] COMPUTERSYSTEM Get UserName", $user);


        $valor_anterior = array(
            'id_persona' => $user_id,
            //'estado'=>1051
        );

        $valor_nuevo = array(

            'id_persona' => $user_id,
            'usuario' => $_POST["email"],
            'descripcion' => 'Inicio de Sesion WEB - SAAS APP',
            'fecha' => date('Y-m-d H:i:s'),
            'equipo' => $compu_cliente,
            'ip_address' => $ip_address,
            'usuario_pc' => $user[1] //json_encode($user)
        );
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $log = "VALUES(NULL, 1163, 'rrhh_persona_usuario', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 3)";
        $sql2 = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) " . $log;
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array());
        Database::disconnect_sqlsrv();



        foreach ($datos as $d) {
            $bodega = $d['id_bodega_insumo'];
            //echo $bodega;
        }

        $_SESSION['id_bodega'] = $bodega;
        return true;
    } else {  // si password no coincide
        return false;
    }
}

function ldap_mensaje_error($extended_error)
{
    $msg = '<label class="text-danger slide_up_anim"><i class="fa fa-times"></i> No se puede conectar al servidor de dominio.</label>';
    if (!empty($extended_error)) {
        $errno = explode(',', $extended_error);
        $errno = $errno[2];
        $errno = explode(' ', $errno);
        $errno = $errno[2];
        $errno = intval($errno);
        if ($errno == 52)
            $msg = '<label class="text-danger slide_up_anim"><i class="fa fa-times"></i> Password Incorrecto.</label>';
        if ($errno == 532)
            $msg = '<label class="text-danger slide_up_anim"><i class="fa fa-times"></i> Password de windows expirado.</label>';
    }
    return $msg;
}

echo json_encode(
    array(
        "login" => $login,
        "msg" => $msg
    )
);
