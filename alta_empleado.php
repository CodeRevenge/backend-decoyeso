<?php
    require("./vendor/autoload.php");
    include("./functions.php");
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $MIN_PERMISSION = 2;
    try {
        // Recibiendo valores
        $curp = $_GET['curp'];
        $nickname = $_GET['nickname'];
        $pw = $_GET['pw'];
        $nombre = $_GET['nombre'];
        $apellido = $_GET['apellido'];
        $nacimiento = $_GET['nacimiento'];
        $direccion = $_GET['direccion'];
        $telefono = $_GET['telefono'];
        $rol = $_GET['rol'];

        $token = $_SERVER['HTTP_TOKEN'] ?? "";
        $resp = verifyRole($token);



        if((int)$resp->role >= $MIN_PERMISSION) {

            $json = new stdClass; 
            // Conexión con Base de Datos
            
            $servername = $_ENV['DDBB_HOST'];
            $username = $_ENV['DDBB_USERNAME'];
            $password = $_ENV['DDBB_PASSWORD'];
            $dbname = $_ENV['DDBB_NAME'];
            
            $mysqli = new mysqli($servername,
                                    $username,
                                    $password,
                                    $dbname);

            if ($mysqli->connect_error) {
                $json->status = "WARNING";
                $json->message = "Connection failed.";
                $json->errorType = "MySQLError";
                $json->sqlMessage = $mysqli->connect_error;
                echo json_encode($json);
            }                                 

            // Verificar CURP
            $sql = "SELECT * FROM employees WHERE employees.Emp_Curp = '".$curp."';";
            
            $res = $mysqli->query($sql);

            if ($mysqli->affected_rows > 0) {
                $json->status = "EXIST_ERROR";
                $json->message = "CURP alredy exist.";
                $json->errorCode = 1;
                echo json_encode($json);
                return;
            }

            // Verificar Nickname
            $sql = "SELECT * FROM employees WHERE employees.Emp_Nickname = '".$nickname."';";
        
            $res = $mysqli->query($sql);

            if ($mysqli->affected_rows > 0) {
                $json->status = "EXIST_ERROR";
                $json->message = "Nickname alredy exist.";
                $json->errorCode = 2;
                echo json_encode($json);
                return;
            }

            

                    
            $pw = password_hash($pw, PASSWORD_DEFAULT, [15]);



        
            $sql = "INSERT INTO `employees` (`Emp_CURP`, `Emp_Nickname`,"
            ." `Emp_Password`, `Emp_Fistname`, `Emp_Lastname`, `Emp_Birthday`"
            .", `Emp_Addres`, `Emp_Phone`, `Emp_Role`)"
            ."VALUES ('".$curp."', '".$nickname."', '".$pw."', '"
            .$nombre."', '".$apellido."', '".$nacimiento."', '"
            .$direccion."','".$telefono."','".$rol."');";


            
            
            $mysqli->query($sql);


            if ($mysqli->affected_rows > 0) {
                $json->status = "OK";
                $json->message = "Insertion has been done.";
                echo json_encode($json);
            } else {
                $json->status = "WARNING";
                $json->message = "Insertion failed.";
                $json->errorType = "MySQLError";
                $json->sqlMessage = $mysqli->error;
                echo json_encode($json);
            }
            $res = $mysqli->query($sql);
        
        
                
        } else {
            $json = new stdClass;
            $json->status = "TOKEN_ERROR";
            $json->message = "Request failed.";
            $json->errorType = "ExceptionError";
            $json->eMessage = $resp->message;
            echo json_encode($json);
        }
        
    }catch(Exception $e) {
        $json = new stdClass;
        $json->status = "ERROR";
        $json->message = "Insertion failed.";
        $json->errorType = "ExceptionError";
        $json->eMessage = $e->getMessage();
        echo json_encode($json);
    }finally{
        if (!empty($mysqli)) {
            $mysqli->close();
        }
    }
    
?>