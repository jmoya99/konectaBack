<?php
include_once "cors.php";
header("Access-Control-Allow-Methods: DELETE");

require 'config/database.php';

$data = json_decode(file_get_contents("php://input"));

if($_SERVER["REQUEST_METHOD"] != "DELETE"){
    echo json_encode(["status" => "error", "msg" => "Peticion Incorrecta"]);
}else{
    if (isset($data->id_unico) && isset($data->id_unico)) {
        $delID = $data->id_unico;
        $deleteArticulo = mysqli_query($db_conn, "DELETE FROM `articulo` WHERE `id_unico`='$delID'");
        if ($deleteArticulo) {
            echo json_encode(["status" => "success", "msg" => "Articulo Eliminado"]);
        } else {
            echo json_encode(["status" => "error", "msg" => "Articulo No Encontrado"]);
        }
    } else {
        echo json_encode(["status" => "error", "msg" => "Error en la base de datos, si persiste comuniquese con el encargado"]);
    }
}
