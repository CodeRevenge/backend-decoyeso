<?php
    require("./vendor/autoload.php");
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();


    try {
        // Recibiendo valores
        $nombre = $_GET['nombre'];
        $desc = $_GET['descripcion'];
        $cost = $_GET['costo'];
        $qty = $_GET['cantidad'];
        
        // Conexión con Base de Datos
        
        $servername = $_ENV['DDBB_HOST'];
        $username = $_ENV['DDBB_USERNAME'];
        $password = $_ENV['DDBB_PASSWORD'];
        $dbname = $_ENV['DDBB_NAME'];
        
        $connection = mysqli_connect($servername,
                                $username,
                                $password,
                                $dbname);
                                
        $insert = "INSERT INTO `item` (`Itm_Name`, `Itm_Descr`, `Itm_Value`, `Itm_Quantity`) VALUES ('".$nombre."', '".$desc."', '".$cost."', '".$qty."');";
                    
        $query = mysqli_query($connection,
                            $insert);
                            
        if ($query) {
            echo "1";
        } else {
            echo "0";
        }
        
    }catch(Exception $e) {
        echo json_encode($e);
    }finally{
        mysqli_close($connection);
    }
    
?>