<?php
    require("./vendor/autoload.php");
    use \Firebase\JWT\JWT;

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    function exception_error_handler($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            // This error code is not included in error_reporting
            return;
        }
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
    set_error_handler("exception_error_handler");

    try {
        // Recibiendo valores
        $nickname = $_GET['nickname'];
        $pw = $_GET['password'];
        
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
      
        $sql = "SELECT * FROM employees WHERE Emp_Nickname = '".$nickname."';";
        
        $res = $mysqli->query($sql);
        
        if ($res->num_rows > 0) {
          while($row = $res->fetch_assoc()) {
            
            $hash = $row["Emp_Password"];

            if (password_verify($pw, $hash)) {
              $time = time();


              $payload = array(
                "iat" => $time,
                "exp" => $time + (60*60*8),
                "data" => [
                  "id" => $row["Emp_Id"],
                  "name" => $row["Emp_Nickname"],
                  "role" => $row["Emp_Role"]
                ]
              );

              $jwt = JWT::encode($payload, $_ENV['SECRET_KEY']);

              $json->status = "OK";
              $json->message = "Welcome ".$row['Emp_Fistname'].".";
              $json->jwt = $jwt;
              echo json_encode($json);
            } else {
              $json->status = "WARNING";
              $json->message = "Username or password are incorrect.";
              $json->errorType = "LOGIN error";
              echo json_encode($json);
            }
          }
        } else {
          $json->status = "WARNING";
          $json->message = "Username or password are incorrect.";
          $json->errorType = "LOGIN error";
          echo json_encode($json);
        }
    }catch(Exception $e) {
        $json = new stdClass;
        $json->status = "ERROR";
        $json->message = "Something gone wrong.";
        $json->errorType = "ExceptionError";
        $json->eMessage = $e->getMessage();
        echo json_encode($json);
    }finally{
        if (!empty($mysqli)) {
            $mysqli->close();
        }
    }
    
?>