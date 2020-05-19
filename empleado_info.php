<?php
require("./vendor/autoload.php");
include("./functions.php");
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$MIN_PERMISSION = 1;
try {
  // Recibiendo valores

  $token = $_SERVER['HTTP_TOKEN'] ?? "";
  $resp = verifyRole($token);

  if ((int) $resp->role >= $MIN_PERMISSION) {

    $servername = $_ENV['DDBB_HOST'];
    $username = $_ENV['DDBB_USERNAME'];
    $password = $_ENV['DDBB_PASSWORD'];
    $dbname = $_ENV['DDBB_NAME'];

    $mysqli = new mysqli(
      $servername,
      $username,
      $password,
      $dbname
    );

    if ($mysqli->connect_error) {
      $json = new stdClass;
      $json->status = "WARNING";
      $json->message = "Connection failed.";
      $json->errorType = "MySQLError";
      $json->sqlMessage = $mysqli->connect_error;
      echo json_encode($json);
    }

    $sql = "SELECT E.*,"
      . " (SELECT EmpRoles_Name FROM employees_roles WHERE EmpRoles_Id  = E.Emp_Role) AS Emp_Role "
      . ",(SELECT EmpStatus_Name FROM employees_status"
      . " WHERE EmpStatus_Id  = E.Emp_Status) AS Emp_StatusName"
      . " FROM employees E WHERE Emp_Id = '" . $resp->id . "';";



    $res = $mysqli->query($sql);

    if ($mysqli->affected_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        if($row["Emp_Status"] == 1) {
          $list = new stdClass;
          $list->status = "OK";
          $data = new stdClass;
          $data->id = $row["Emp_Id"];
          $data->CURP = $row["Emp_CURP"];
          $data->nickname = $row["Emp_Nickname"];
          $data->firstname = $row["Emp_Fistname"];
          $data->lastname = $row["Emp_Lastname"];
          $data->status = $row["Emp_Status"];
          $data->statusName = $row["Emp_StatusName"];
          $data->addres = $row["Emp_Addres"];
          $data->phone = $row["Emp_Phone"];
          $data->status = $row["Emp_Status"];
          $data->role = $row["Emp_Role"];
          $data->introdate = $row["Emp_InitDate"];
          $list->data = $data;
          echo json_encode($list);
        } else {
          $json = new stdClass;
          $json->status = "ERROR";
          $json->message = "El usuario no existe.";
          $json->errorType = "Inactive User.";
          echo json_encode($json);
        }
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
} catch (Exception $e) {
  $json = new stdClass;
  $json->status = "ERROR";
  $json->message = "Request failed.";
  $json->errorType = "ExceptionError";
  $json->eMessage = $e->getMessage();
  echo json_encode($json);
} finally {
  if (!empty($mysqli)) {
    $mysqli->close();
  }
}
