<?php
class UserController{
    private $_method;
    private $_complement;
    private $_data;

    function __construct($_method, $_complement, $_data){
        $this->_method = $_method;
        $this->_complement = $_complement  == null ? 0 : $_complement;
        $this->_data = $_data != 0 ? $_data : "";
    }

    public function index(){
        switch($this->_method){
            case "GET":
                if($this->_complement == 0){
                    $user = UserModel::getUsers(0);
                    $json = $user;
                    echo json_encode($json, true);
                    return;
                }
                else{
                    $user = UserModel::getUsers($this->_complement);
                    $json = $user;
                    echo json_encode($json, true);
                    return;
                }
            case "POST":
                $createUser = UserModel::createUser($this->generateSalting());
                $json = array(
                    "response" => $createUser
                );
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                return;

            case "PUT":
                $updateUser = UserModel::actualizarUsuario($this->_data);
                $json = array(
                    "response" => $updateUser
                );
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                return;
                
            case "DELETE":
                $deleteUser = UserModel::borrarUsuario($this->_data);
                $json = array(
                    "response" => $deleteUser
                );
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                return;
            
            case "OPTIONS":
                $statusUser = UserModel::actualizarStatus($this->_data);
                $json = array(
                    "response" => $statusUser
                );
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                return;
            
            default:
                $json = array(
                    "ruta:" => "not found"
                );
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                return;
        }
    }

    private function generateSalting(){
        //$trimmedData se usa para limpiar los datos
        $trimmedData = "";
        if(($this->_data != "") || (!empty($this->_data))){
            //arra_map se utiliza para pasar el objeto tipo JSON a arreglo
            $trimmedData = array_map('trim', $this->_data);
            $trimmedData['use_pss'] = md5($trimmedData['use_pss']);
            //Generando el salting para credenciales
            $identifier = str_replace("$", "ue3", crypt($trimmedData['use_mail'], 'ue56'));
            $key = str_replace("$", "2023", crypt($trimmedData['use_mail'], '56ue'));
            $trimmedData['us_identifier'] = $identifier;
            $trimmedData['us_key'] = $key;
            return $trimmedData;
        }
    }
    
}
?>