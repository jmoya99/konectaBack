<?php
include_once "cors.php";

require 'config/database.php';

$data = json_decode(file_get_contents("php://input"));
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["status" => "error", "msg" => "Peticion Incorrecta"]);
} elseif (
    isset($data->id)
    && isset($data->contrasena)
    && !empty(trim($data->id))
    && !empty(trim($data->contrasena))
) {
    $contrasena = mysqli_real_escape_string($db_conn, trim($data->contrasena));

    $contrasena_hash = password_hash($data->contrasena, PASSWORD_BCRYPT);
    $updateUser = mysqli_query($db_conn, "UPDATE `usuario` SET `contrasena`='$contrasena_hash', `fecha_actualizacion`=now() WHERE `id`='$data->id'");
    if ($updateUser) {
        echo json_encode(["status" => "success", "msg" => "Contraseña Actualizado Exitosamente."]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Contraseña No Actualizado"]);
    }
} else {
    echo json_encode(["status" => "error", "msg" => "Recuerda llenar los campos correctamente"]);
}
