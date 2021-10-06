<?php
require_once(__DIR__.'/./db_credenciales.php');
class DataBase{
    private static $instance, $configuration = [
        "host" => DB_HOST,
        "port" => DB_PORT,
        "dbname" => DB_DATABASE,
        "user" => DB_USUARIO,
        "password" => DB_CLAVE
    ];
    public function __construct(){}
    private static function connect(){
        try{
            $db = new PDO('mysql:host='.self::$configuration["host"].';port='.self::$configuration["port"].';dbname='.self::$configuration["dbname"].';charset=utf8',self::$configuration["user"],self::$configuration["password"]);
            return $db;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public static function getInstance(){  // Devielve la base de datos
        if(!self::$instance){
            self::$instance = self::connect();
        }
        return self::$instance;
    }
}
?>