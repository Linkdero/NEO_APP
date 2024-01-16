<?php




if (!isset($_SERVER['HTTP_REFERER'])){

echo "<script type='text/javascript'> window.location='index.php'; </script>";
exit();


}

else {
class Database
{
    private static $dbName = 'db_saas_app' ;
    private static $dbHost = 'localhost' ;
    private static $dbUsername = 'root';
    private static $dbUserPassword = '';
    private static $cont  = null;

    public function __construct() {
        die('Init function is not allowed');
    }

    public static function connect()
    {
        // One connection through whole application
        if ( null == self::$cont )
        {
            try
            {
                self::$cont =  new PDO( "mysql:host=".self::$dbHost.";charset=utf8;"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);
            }
            catch(PDOException $e)
            {
                die($e->getMessage());
            }
        }
        return self::$cont;
    }

    public static function disconnect()
    {
        self::$cont = null;
    }

    public static function connect_sqlsrv()
    {
        // One connection through whole application
        if ( null == self::$cont )
        {
            try
            {
                self::$cont =  new PDO("sqlsrv:Server=SRVDESA001;Database=SAAS_APP", "us_saas_app", 'h3Xhg3jvCcrX9ygp');
                //self::$cont =  new PDO("sqlsrv:Server=172.16.100.200;Database=SAAS_APP", "us_saas_app", 'h3Xhg3jvCcrX9ygp');
                // self::$cont =  new PDO("sqlsrv:Server=SRVAPLICACIONES;Database=SAAS_APP", "us_saas_app", 'h3Xhg3jvCcrX9ygp');
            }
            catch(PDOException $e)
            {
                die($e->getMessage());
            }
        }
        return self::$cont;
    }

    public static function disconnect_sqlsrv()
    {
        self::$cont = null;
    }

    public static function connect_sqlsrv_desa()
    {
        // One connection through whole application
        if ( null == self::$cont )
        {
            try
            {
                //self::$cont =  new PDO("sqlsrv:Server=SRVWSUS;Database=SAAS_APP", "us_saas_app", 'h3Xhg3jvCcrX9ygp');
                // self::$cont =  new PDO("sqlsrv:Server=SRVAPLICACIONES;Database=SAAS_APP", "us_saas_app", 'h3Xhg3jvCcrX9ygp');
                self::$cont =  new PDO("sqlsrv:Server=SRVDESA001;Database=SAAS_APP", "us_saas_app", 'h3Xhg3jvCcrX9ygp');
            }
            catch(PDOException $e)
            {
                die($e->getMessage());
            }
        }
        return self::$cont;
    }

    public static function disconnect_sqlsrv_desa()
    {
        self::$cont = null;
    }

}
}
