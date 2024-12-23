<?php
session_start();
//Singleton pattern to make sure database connection is established once
class Database {
    // Static property to hold the single instance
    private static $instance = null;

    private const PARAM_HOST = "127.0.0.1";
    private const PARAM_DB = "DrivingExperience";
    private const PARAM_USER = "root";
    private const PARAM_PASSWD = "";
    private const PARAM_PORT = 3306;

    private $pdo;

    // Private constructor to prevent direct instantiation
    private function __construct() {
        $dsn = 'mysql:host=' . self::PARAM_HOST . ';port=' . self::PARAM_PORT . ';dbname=' . self::PARAM_DB;
        try {
            $this->pdo = new PDO($dsn, self::PARAM_USER, self::PARAM_PASSWD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec("SET NAMES 'utf8'");
        } catch (PDOException $ex) {
            echo "Error: " . $ex->getMessage() . "<br>";
            echo "Code: " . $ex->getCode() . "<br>";
            exit();
        }
    }

    // Prevent cloning the singleton instance
    private function __clone() {}

    // Public static method to get the single instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self(); 
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
//****************************************************
function random_pw($pw_length) {
    $pass = NULL;
    $charlist = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz023456789';
    $ps_len = strlen($charlist);
    mt_srand((double)microtime() * 1000000);

    for ($i = 0; $i < $pw_length; $i++) {
        $pass .= $charlist[mt_rand(0, $ps_len - 1)];
    }
    return ($pass);
}
//******************************************
?>
