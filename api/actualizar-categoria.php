<?php
include_once "cors.php";

require 'config/database.php';

$data = json_decode(file_get_contents("php://input"));
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["status" => "error", "msg" => "Peticion Incorrecta"]);
} elseif (
    isset($data->titulo)
    && isset($data->descripcion)
    && isset($data->id)
    && !empty(trim($data->id))
    && !empty(trim($data->titulo))
    && !empty(trim($data->descripcion))
) {
    $titulo = mysqli_real_escape_string($db_conn, trim($data->titulo));
    $descripcion = mysqli_real_escape_string($db_conn, trim($data->descripcion));

    $updateCategory = mysqli_query($db_conn, "UPDATE `categoria` SET `titulo`='$titulo', `descripcion`='$descripcion' WHERE `id`='$data->id'");
    if ($updateCategory) {
        echo json_encode(["status" => "success", "msg" => "Categoria Actualizada Exitosamente."]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Categoria No Actualizada"]);
    }
} else {
    echo json_encode(["status" => "error", "msg" => "Recuerda llenar los campos correctamente"]);
}
