<?php
require("./vendor/autoload.php");
include("./functions.php");
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$MIN_PERMISSION = 2;
$final = false;

try {

    $token = $_SERVER['HTTP_TOKEN'] ?? "";
    $resp = verifyRole($token);

    if ((int) $resp->role >= $MIN_PERMISSION) {
        // Recibiendo valores
        $data = file_get_contents('php://input');
        $images = json_decode($data, true);


        
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


        
        for($i = 0; $i < count($images); $i++ ) {

          $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.imgur.com/3/image",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('image' => $images[$i]["image"]),
            CURLOPT_HTTPHEADER => array(
              "Authorization: Client-ID " . $_ENV['IMGUR_CLIENT_ID']
            ),
          ));


          $reply = curl_exec($curl);
          curl_close($curl);

          $reply = json_decode($reply);

          if($reply->success){
            $sql = "INSERT INTO `products_images`(`ProImage`, `Pro_Id`, `ProImage_Desc`)"
            ."VALUES ('".$reply->data->link."','".$images[$i]["id"]."', '".$images[$i]["title"]."');";
          
  
            $res = $mysqli->query($sql);
    
    
            if ($mysqli->affected_rows > 0) {
              $final = true;
            } else {
              $final = false;
                $json = new stdClass;
                $json->status = "WARNING";
                $json->code = "-1";
                $json->message = "Image not saved. (SQL)";
                $json->errorType = "MySQLError";
                $json->sqlMessage = $mysqli->error;
                echo json_encode($json);
            }
          }else{
            $json = new stdClass;
            $json->status = "WARNING";
            $json->code = "-1";
            $json->message = "Image not saved. Imgur";
            $json->errorType = "Imgur Error";
            $json->eMessage = $reply->data->error;
            echo json_encode($json);
          }        
      }
      if($final) {
        $json = new stdClass;
        $json->status = "OK";
        $json->message = "Insertion has been done.";
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
