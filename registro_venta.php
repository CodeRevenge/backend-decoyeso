<?php
    require("./vendor/autoload.php");
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
        $empleadoId = $_GET['empleadoId'];
        $neto = $_GET['neto'];
        $iva = $_GET['iva'];
        $total = $_GET['total'];
        $clienteId = $_GET['clienteId'] ?? "";
        
        
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
        
        if ($clienteId != "") {
          $sql = "SELECT * FROM clients WHERE clients.Cli_Id = ".$clienteId.";";
          $resCli = $mysqli->query($sql);
          if ($resCli->num_rows <= 0) {
            $json->status = "WARNING";
            $json->message = "The client id does not exist.";
            $json->errorType = "IndexNotValid";
            echo json_encode($json);
          }
        }

        $sql = "SELECT * FROM employees WHERE employees.Emp_Id = ".$empleadoId.";";
        
        $res = $mysqli->query($sql);
        
        if ($res->num_rows > 0) {
          $sql = "INSERT INTO sales (`Sal_EmpId`, `Sal_Net`, `Sal_IVA`, `Sal_Total`)"
                ."VALUES ('".$empleadoId."','".$neto."', '".$iva."', '".$total."');";
        
          $mysqli->query($sql);

          if ($mysqli->affected_rows > 0) {
            
            if ($clienteId != "") {
              $sql = "INSERT INTO sale_client (`Sal_Id`, `Cli_Id`)"
              ."VALUES ('".$mysqli->insert_id."','".$clienteId."');";
              $mysqli->query($sql);
            }
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
        } else {
          $json->status = "WARNING";
          $json->message = "The employee id does not exist.";
          $json->errorType = "IndexNotValid";
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