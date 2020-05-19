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
  
  function verifyRole($token) {
    try {
      if(empty($token))
      {
          throw new Exception("Invalid token supplied.");
      }
        
        $json = new stdClass; 
        // Conexión con Base de Datos
        try {
          $data = JWT::decode(
            $token,
            $_ENV['SECRET_KEY'], 
            array('HS256')
          );
        }catch(Exception $e) {
          if($e->getMessage() == "Expired token") {
            $json = new stdClass;
            $json->status = "TOKEN_ERROR";
            $json->message = "Sesion has expired.";
            $json->eMessage = $e->getMessage();
            $json->role = -1;
            return $json;
          }else {
            $json = new stdClass;
            $json->status = "TOKEN_ERROR";
            $json->eMessage = $e->getMessage();
            $json->role = -1;
            return $json;
          }
          
        }
        
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
            $json->eMessage = $mysqli->connect_error;
            $json->role = -1;
            return $json;
        }
        
      
        $sql = "SELECT * FROM employees WHERE Emp_Nickname = '"
            .$data->data->name."' AND Emp_Id = '".$data->data->id."'"
            ."AND Emp_Role = '".$data->data->role."';";
        
  
        $res = $mysqli->query($sql);
        
        if ($res->num_rows > 0) {
          $json = new stdClass;
          $json->status = "OK";
          $json->message = "Role is verified";
          $json->role = (int)$data->data->role;
          $json->id = (int)$data->data->id;
          return $json;
        } else {
          $json->status = "ERROR";
          $json->message = "Token checksum is invalid.";
          $json->role = -1;
          return $json;
        }
    } catch (Exception $e) {
      $json = new stdClass;
      $json->status = "TOKEN_ERROR";
      $json->eMessage = $e->getMessage();
      $json->role = -1;
      return $json;
    }
    
  }

  function verifyToken($token) {
    try {
      if(empty($token))
      {
          throw new Exception("Invalid token supplied.");
      }
        
        $json = new stdClass; 
        // Conexión con Base de Datos
        try {
          $data = JWT::decode(
            $token,
            $_ENV['SECRET_KEY'], 
            array('HS256')
          );
        }catch(Exception $e) {
          if($e->getMessage() == "Expired token") {
            $json = new stdClass;
            $json->status = "TOKEN_EXPIRED";
            $json->message = "Sesion has expired.";
            $json->eMessage = $e->getMessage();
            $json->role = -1;
            return $json;
          }else {
            $json = new stdClass;
            $json->status = "TOKEN_ERROR";
            $json->eMessage = $e->getMessage();
            $json->role = -1;
            return $json;
          }
          
        }
        
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
            $json->eMessage = $mysqli->connect_error;
            $json->role = -1;
            return $json;
        }
      
        $sql = "SELECT * FROM employees WHERE Emp_Nickname = '"
            .$data->data->name."' AND Emp_Id = '".$data->data->id."'"
            ."AND Emp_Role = '".$data->data->role."';";
        
  
        $res = $mysqli->query($sql);
        
        if ($res->num_rows > 0) {
          $json = new stdClass;
          $json->status = "OK";
          $json->message = "Valid sesion.";
          $json->role = (int)$data->data->role;
          $json->id = (int)$data->data->id;
          return $json;
        } else {
          $json->status = "ERROR";
          $json->message = "Token checksum is invalid.";
          $json->role = -1;
          return $json;
        }
    } catch (Exception $e) {
      $json = new stdClass;
      $json->status = "TOKEN_ERROR";
      $json->eMessage = $e->getMessage();
      $json->role = -1;
      return $json;
    }
    
  }
?>