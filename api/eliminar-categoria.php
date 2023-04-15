<?php
include_once "cors.php";

require 'config/database.php';

$data = json_decode(file_get_contents("php://input"));

if($_SERVER["REQUEST_METHOD"] != "DELETE"){
    echo json_encode(["status" => "error", "msg" => "Peticion Incorrecta"]);
}else{
    if (isset($data->id) && isset($data->id)) {
        $delID = $data->id;
        $deleteUser = mysqli_query($db_conn, "DELETE FROM `categoria` WHERE `id`='$delID'");
        if ($deleteUser) {
            echo json_encode(["status" => "success", "msg" => "Categoria Eliminada"]);
        } else {
            echo json_encode(["status" => "error", "msg" => "Categoria No Encontrada"]);
        }
    } else {
        echo json_encode(["status" => "error", "msg" => "Error en la base de datos, si persiste comuniquese con el encargado"]);
    }
}