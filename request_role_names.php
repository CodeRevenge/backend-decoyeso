<?php
  require("./vendor/autoload.php");
  include("./functions.php");
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();
  $MIN_PERMISSION = 2;
  try {
    // Recibiendo valores
    

    $token = $_SERVER['HTTP_TOKEN'] ?? "";
    $resp = verifyRole($token);
    
    if((int)$resp->role >= $MIN_PERMISSION) {

      $servername = $_ENV['DDBB_HOST'];
      $username = $_ENV['DDBB_USERNAME'];
      $password = $_ENV['DDBB_PASSWORD'];
      $dbname = $_ENV['DDBB_NAME'];
      
      $mysqli = new mysqli($servername,
                              $username,
                              $password,
                              $dbname);

      if ($mysqli->connect_error) {
        $json = new stdClass; 
        $json->status = "WARNING";
        $json->message = "Connection failed.";
        $json->errorType = "MySQLError";
        $json->sqlMessage = $mysqli->connect_error;
        echo json_encode($json);
      }
      
      $sql = "SELECT * FROM employees_roles;";


      $res = $mysqli->query($sql);

      if ($mysqli->affected_rows > 0) {
        $list = new stdClass;
        $list->status = "OK";
        $cont = 0;
        $dt = new stdClass;
        while($row = $res->fetch_assoc()) {
          $data = new stdClass;
          $data->id = $row["EmpRoles_Id"];
          $data->name = $row["EmpRoles_Name"];
          $data->desc = $row["EmpRoles_Desc"];
          $dt->{$cont} = $data;
          $cont++;
        }
        $list->data = $dt;
        echo json_encode($list);
        } else {
          $json = new stdClass;
          $json->status = "NO_ROLES";
          $json->message = "No hay roles.";
          echo json_encode($json);
        }
      
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
      $json->message = "Request failed.";
      $json->errorType = "ExceptionError";
      $json->eMessage = $e->getMessage();
      echo json_encode($json);
  }finally{
      if (!empty($mysqli)) {
          $mysqli->close();
      }
  }
?>