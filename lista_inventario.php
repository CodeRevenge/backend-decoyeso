<?php
require("./vendor/autoload.php");
include("./functions.php");
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$MIN_PERMISSION = 1;

try {

    $token = $_SERVER['HTTP_TOKEN'] ?? "";
    $resp = verifyRole($token);

    if ((int) $resp->role >= $MIN_PERMISSION) {
        // Recibiendo valores
        $status = $_GET['status'] ?? [];

        $json = new stdClass;
        // Conexión con Base de Datos

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
            $json->status = "WARNING";
            $json->message = "Connection failed.";
            $json->errorType = "MySQLError";
            $json->sqlMessage = $mysqli->connect_error;
            echo json_encode($json);
        }

        if ((int) $resp->role > 1) {
            $sql = "SELECT * FROM products";
        } else {

            $sql = "SELECT * FROM products WHERE Prod_Status = 1";
        }

        // for ($i=0; $i < $status; $i++) { 
        //     $sql .= " OR Prod_Status = " . $status[$i];
        // }

        $res = $mysqli->query($sql);


        if ($mysqli->affected_rows > 0) {
            // $json->status = "OK";
            // $json->message = "Insertion has been done.";
            // echo json_encode($json);
            $list = new stdClass;
            $i = 0;
            while ($row = $res->fetch_assoc()) {
                $item = new stdClass;
                $item->id = $row["Prod_Id"];
                $item->name = $row["Prod_Name"];
                $item->desc = $row["Prod_Descr"];
                $item->value = $row["Prod_Value"];
                $item->quantity = $row["Prod_Quantity"];
                $item->status = $row["Prod_Status"];
                $item->introdate = $row["Prod_IntroDate"];
                $list->{$i} = $item;
                $i++;
            }
            echo json_encode($list);
        } else {
            $json->status = "WARNING";
            $json->code = "-1";
            $json->message = "No products.";
            $json->errorType = "MySQLError";
            $json->sqlMessage = $mysqli->error;
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
} catch (Exception $e) {
    $json = new stdClass;
    $json->status = "ERROR";
    $json->message = "Error.";
    $json->errorType = "ExceptionError";
    $json->eMessage = $e->getMessage();
    echo json_encode($json);
} finally {
    if (!empty($mysqli)) {
        $mysqli->close();
    }
}
