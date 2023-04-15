<?php

declare(strict_types=1);

include_once "cors.php";

require 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $usuarios = mysqli_query($db_conn, "SELECT * FROM `usuario`");
    if (mysqli_num_rows($usuarios) > 0) {
        $usuarios = mysqli_fetch_all($usuarios, MYSQLI_ASSOC);
        echo json_encode(["status" => "success", "msg" => "Usuario encontrados exitosamente", "users" => $usuarios]);
    } else {
        echo json_encode(["status" => "error", "msg" => "No se pudieron acceder a los usuarios"]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    if (
        isset($data->nombre)
        && isset($data->correo_electronico)
        && isset($data->contrasena)
        && isset($data->numero_movil)
        && isset($data->tipo_usuario)
        && !empty(trim($data->nombre))
        && !empty(trim($data->correo_electronico))
        && !empty(trim($data->contrasena))
        && !empty(trim($data->tipo_usuario))
    ) {
        $nombre = mysqli_real_escape_string($db_conn, trim($data->nombre));
        $correo_electronico = mysqli_real_escape_string($db_conn, trim($data->correo_electronico));
        $contrasena = mysqli_real_escape_string($db_conn, trim($data->contrasena));
        $numero_movil = mysqli_real_escape_string($db_conn, trim($data->numero_movil));
        $tipo_usuario = mysqli_real_escape_string($db_conn, trim($data->tipo_usuario));

        $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

        $insertUser = mysqli_query($db_conn, "INSERT INTO `usuario`(`nombre`,`correo_electronico`,`contrasena`,`numero_movil`,`tipo_usuario`,`fecha_creacion`,`fecha_actualizacion`) VALUES('$nombre','$correo_electronico','$contrasena_hash','$numero_movil','$tipo_usuario',now(),now())");
        if ($insertUser) {
            $last_id = mysqli_insert_id($db_conn);
            echo json_encode(["status" => "success", "msg" => "Usuario registrado exitosamente", "id" => $last_id]);
        } else {
            echo json_encode(["status" => "error", "msg" => "El correo ya está registrado"]);
        }
    } else {
        echo json_encode(["status" => "error", "msg" => "Recuerde llenar los campos correctamente"]);
    }
} else {
    echo json_encode(["status" => "error", "msg" => "Peticion Incorrecta"]);
}
