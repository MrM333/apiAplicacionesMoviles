<?php
//TAREA: required, include, requeare
//Variables estaticas en PHP para acceder necesitamos Nombre_Clase :: nombreVariable
//PDO --> Una capa para entrar de una forma mas segura
//Estudiar JSON --> Formato ligero de transferencia de datos

require_once("config.php");

class Conection{

    public $mensaje = "fdsgfdsgdfgdf";

    static public function connection(){
        $con = false;
        try{
            $data = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
            $con = new PDO($data, DB_USERNAME, DB_PASSWORD);
            return $con;
        } catch(\Throwable $e){
            $mensaje = array(
                "COD" => "000",
                "MENSAJE" => ("Error en base de datos".$e)
            );
        }
        return $con;
    }
}


?>