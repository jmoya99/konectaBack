<?php
include_once "cors.php";

require 'config/database.php';

$data = json_decode(file_get_contents("php://input"));
if($_SERVER["REQUEST_METHOD"] != "POST"){
    echo json_encode(["status" => "error", "msg" => "Peticion Incorrecta"]);
}elseif (
    isset($data->nombre)
    && isset($data->correo_electronico)
    && isset($data->numero_movil)
    && isset($data->tipo_usuario)
    && isset($data->id)
    && !empty(trim($data->id))
    && !empty(trim($data->nombre))
    && !empty(trim($data->correo_electronico))
    && !empty(trim($data->numero_movil))
    && !empty(trim($data->tipo_usuario))
) {
    $nombre = mysqli_real_escape_string($db_conn, trim($data->nombre));
    $correo_electronico = mysqli_real_escape_string($db_conn, trim($data->correo_electronico));
    $numero_movil = mysqli_real_escape_string($db_conn, trim($data->numero_movil));
    $tipo_usuario = mysqli_real_escape_string($db_conn, trim($data->tipo_usuario));

    if (filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        $updateUser = mysqli_query($db_conn, "UPDATE `usuario` SET `nombre`='$nombre', `correo_electronico`='$correo_electronico',`numero_movil`='$numero_movil',`tipo_usuario`='$tipo_usuario',`fecha_actualizacion`=now() WHERE `id`='$data->id'");
        if ($updateUser) {
            echo json_encode(["status" => "success", "msg" => "Usuario Actualizado Exitosamente."]);
        } else {
            echo json_encode(["status" => "error", "msg" => "Usuario No Actualizado"]);
        }
    } else {
        echo json_encode(["status" => "error", "msg" => "Correo Electronico Invalido"]);
    }
} else {
    echo json_encode(["status" => "error", "msg" => "Recuerda llenar los campos correctamente"]);
}