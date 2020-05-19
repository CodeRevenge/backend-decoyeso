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

    if ((int) $resp->role >= $MIN_PERMISSION) {


        $id = $_GET['id'];
        $name = $_GET['nombre'];
        $desc = $_GET['descripcion'];
        $cost = $_GET['costo'];
        $qty = $_GET['cantidad'];
        $status = $_GET['status'];

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


        $sql = "UPDATE products SET Prod_Name='".$name."', Prod_Descr='".$desc."', Prod_Value='".$cost."',"
        ." Prod_Quantity='".$qty."', `Prod_Status`='".$status."' WHERE Prod_Id = '".$id."';";

        $mysqli->query($sql);


        if ($mysqli->affected_rows > 0) {
            $json->status = "OK";
            $json->message = "Update has been done.";
            echo json_encode($json);
        } else {
            $json->status = "WARNING";
            $json->message = "Update failed.";
            $json->errorType = "MySQLError";
            $json->sqlMessage = $mysqli->error;
            echo json_encode($json);
        }
    }
} catch (Exception $e) {
    $json = new stdClass;
    $json->status = "ERROR";
    $json->message = "Update failed.";
    $json->errorType = "ExceptionError";
    $json->eMessage = $e->getMessage();
    echo json_encode($json);
} finally {
    if (!empty($mysqli)) {
        $mysqli->close();
    }
}
?>