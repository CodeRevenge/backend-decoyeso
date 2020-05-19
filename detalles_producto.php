<?php
  require("./vendor/autoload.php");
  include("./functions.php");
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();
  $MIN_PERMISSION = 1;
  try {
    // Recibiendo valores
    $id = $_GET['id'];

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
      
      $sql = "SELECT * FROM products WHERE Prod_Id = " . $id;


      $res = $mysqli->query($sql);

      if ($mysqli->affected_rows > 0) {
        while($row = $res->fetch_assoc()) {
          
          $list = new stdClass;
          $list->status = "OK";
          $data = new stdClass;
          $data->id = $row["Prod_Id"];
          $data->name = $row["Prod_Name"];
          $data->descr = $row["Prod_Descr"];
          $data->value = $row["Prod_Value"];
          $data->quantity = $row["Prod_Quantity"];
          $data->status = $row["Prod_Status"];
          $data->introdate = $row["Prod_IntroDate"];
          $data->updated = $row["Prod_UpdateDate"];

          $sql = "SELECT * FROM products_status";
          $res2 = $mysqli->query($sql);
          $status = new stdClass;
          if ($mysqli->affected_rows > 0) {
            while($row2 = $res2->fetch_assoc()) {
              $temp = new stdClass;
              $temp->id = $row2["ProdStatus_Id"];
              $temp->name = $row2["ProdStatus_Name"];
              $status->{$temp->id} = $temp;
            }
          }


          $list->data = $data;
          $list->statusList = $status;
          echo json_encode($list);
        }
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