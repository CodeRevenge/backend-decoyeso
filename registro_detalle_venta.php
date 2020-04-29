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
        $ventaId = $_GET['ventaId'];
        $productoId = $_GET['productoId'];
        $cantidad = $_GET['cantidad'];
        $precio = $_GET['precio'];
        $costo = $_GET['costo'];
        
        
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
                                

        $sql = "SELECT * FROM sales WHERE sales.Sal_Id = ".$ventaId.";";
        $sql2 = "SELECT * FROM products WHERE products.Prod_Id = ".$productoId.";";
        
        $res = $mysqli->query($sql);
        $res2 = $mysqli->query($sql2);
        
        if (($res->num_rows > 0) and ($res->num_rows > 0)) {
          $sql = "INSERT INTO sales_details (`SalDet_SalId`, `SalDet_ProdId`, `SalDet_Quantity`, `SalDet_Price`, `SalDet_Cost`)"
                ."VALUES ('".$ventaId."','".$productoId."', '".$cantidad."', '".$precio."', '".$costo."');";
        
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
        } else {
          $json->status = "WARNING";
          $json->message = "The sales or the product id does not exist.";
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