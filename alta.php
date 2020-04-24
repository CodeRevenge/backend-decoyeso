<?php
    require("./vendor/autoload.php");
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();


    try {
        // Recibiendo valores
        $codigo = $_GET['codigo'];
        $nombre = $_GET['nombre'];
        $carrera = $_GET['carrera'];
        $centro = $_GET['centro'];
        
        // Conexión con Base de Datos
        
        $servername = $_ENV['DDBB_HOST'];
        $username = $_ENV['DDBB_USERNAME'];
        $password = $_ENV['DDBB_PASSWORD'];
        $dbname = $_ENV['DDBB_NAME'];
        
        $connection = mysqli_connect($servername,
                                $username,
                                $password,
                                $dbname);
                                
        $insert = "INSERT INTO `Datos` (`Codigo`, `Nombre`, `Carrera`, `Centro`) VALUES ('".$codigo."', '".$nombre."','".$carrera."', '".$centro."')";
                    
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