<?php
require_once "ConDB.php";

class UserModel{
    static public function createUser($data){
        $cantMail = self::getMail($data["use_mail"]);
        if($cantMail == 0){
            $query = "INSERT INTO users(use_id, use_mail, use_pss, use_dateCreate, us_identifier, us_key, us_status) 
            VALUES (NULL, :use_mail, :use_pss, :use_dateCreate, :us_identifier, :us_key, :us_status)";
            $status="0";
            $stament = Conection::connection()->prepare($query);
            $stament->bindParam(":use_mail", $data["use_mail"], PDO::PARAM_STR);
            $stament->bindParam(":use_pss", $data["use_pss"], PDO::PARAM_STR);
            $stament->bindParam(":use_dateCreate", $data["use_dateCreate"], PDO::PARAM_STR);
            $stament->bindParam(":us_identifier", $data["us_identifier"], PDO::PARAM_STR);
            $stament->bindParam(":us_key", $data["us_key"], PDO::PARAM_STR);
            $stament->bindParam(":us_status", $status, PDO::PARAM_STR);
            $message = $stament->execute() ? "ok" : Conection::connection() ->errorInfo();
            $stament -> closeCursor();
            $stament = null;
            $query = "";
        }
        else{
            $message = "Usuario ya esta registrado";
        }
        return $message;
    }
    //Metodo para verificar si existe ese correo en la BD
    static private function getMail($mail){
        $query = "";
        $query = "SELECT use_mail FROM users WHERE use_mail = '$mail';";
        $stament = Conection::connection()->prepare($query);
        $stament->execute();
        $result = $stament->rowCount();
        return $result;
    }
    //TRAE TODOS LOS USUARIOS
    static function getUsers($id){
        $query = "";
        $id = is_numeric($id) ? $id : 0;
        $query = "SELECT use_id, use_mail, use_dateCreate FROM users";
        $query.=($id > 0) ? " WHERE users.use_id = '$id' AND " : "";
        $query.=($id > 0) ? " us_status='1';" : " WHERE us_status = '1';";
        //echo $query;   
        $stament = Conection::connection()->prepare($query);
        $stament->execute();
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //Login
    static public function login($data){
        //$query = "";
        $user = $data['use_mail'];
        $pss = $data['use_pss'];
        //echo $pss
        if(!empty($user) && !empty($pss)){
            $query = "SELECT us_identifier, us_key, use_id FROM users WHERE use_mail='$user' and use_pss = '$pss' and us_status = '1'";
            $stament = Conection::connection()->prepare($query);
            $stament->execute();
            $result = $stament->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        else{
            $mensaje = array(
                "COD" => "001",
                "MENSAJE" => ("Error en credenciales")
            );
            return $mensaje;
        }
        $query="";
    }

    //Autentificador
    static public function getUserAuth(){
        $query="";
        $query="SELECT us_identifier,us_key FROM users WHERE us_status = '1';";
        $stament = Conection::connection()->prepare($query);
        $stament->execute();
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //METODO ACTUALIZAR USUARIO

    static public function actualizarUsuario($data){
        $mail = $data['use_mail'];
        $id = $data['use_id'];
        $idExiste = self::getUsers($id);

        if(!empty($idExiste)){
            $query = "UPDATE users SET use_mail = '$mail' WHERE use_id = '$id' AND us_status = '1';";
            $stament = Conection::connection()->prepare($query);
            $stament->execute();
            $result = $stament->fetchAll(PDO::FETCH_ASSOC);
            $message = $stament -> execute() ? "Se ha actualizado el correo" : Conection::connection() -> errorInfo();
            return $message;
        }
        else{
            $message = "Usuario no existe";
            return $message;
        }
    }

    //METODO BORRAR USUARIO

    static public function borrarUsuario($data) {
        $mail = $data['use_mail'];
    
        if (!empty($mail)) {
            $query = "DELETE FROM users WHERE use_mail = :mail;";
            $statement = Conection::connection()->prepare($query);
            $statement->bindParam(':mail', $mail);
    
            return $statement->execute()
                ? "Usuario eliminado correctamente"
                : "Error al eliminar el usuario: " . implode(", ", $statement->errorInfo());
        }
    
        return "Datos incompletos";
    }


    //METODO ACTUALIZAR STATUS

    static public function actualizarStatus($data) {
        $mail = $data['use_mail'];
        $status = $data['us_status'];
    
        if (!empty($mail)) {
            $query = "UPDATE users SET us_status = :status WHERE use_mail = :mail";
            $statement = Conection::connection()->prepare($query);
            $statement->bindParam(':mail', $mail);
            $statement->bindParam(':status', $status);
    
            return $statement->execute()
                ? "Status del usuario actualizado correctamente"
                : "Error al actualizar el status del usuario: " . implode(", ", $statement->errorInfo());
        }
    
        return "Datos incompletos";
    }
}


?>