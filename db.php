<?php

$dsn ="mysql:host=localhost;dbname=espace-menbres;charset=utf8";
$user ="root";
$pass ="";

try {
$cnx =new PDO($dsn,$user,$pass);
$cnx->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_SILENT);
}catch(PDOException $e){
    echo"erreur:".$e->getmessage();
}
?>