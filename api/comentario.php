<?php

declare(strict_types=1);

include_once "cors.php";

require 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $comentarios = mysqli_query($db_conn, "SELECT * FROM `comentario`");
    if (mysqli_num_rows($comentarios) > 0) {
        $comentarios = mysqli_fetch_all($comentarios, MYSQLI_ASSOC);
        echo json_encode(["status" => "success", "comentarios" => $comentarios]);
    } else {
        echo json_encode(["status" => "success", "comentarios" => []]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (
        isset($data->id_articulo)
        && isset($data->id_usuario)
        && isset($data->texto)
        && !empty(trim($data->id_articulo))
        && !empty(trim($data->id_usuario))
        && !empty(trim($data->texto))
    ) {
        $id_articulo = mysqli_real_escape_string($db_conn, trim($data->id_articulo));
        $id_usuario = mysqli_real_escape_string($db_conn, trim($data->id_usuario));
        $texto = mysqli_real_escape_string($db_conn, trim($data->texto));

        $insertComentario = mysqli_query($db_conn, "INSERT INTO `comentario`(`id_articulo`,`id_usuario`,`texto`) VALUES('$id_articulo','$id_usuario','$texto')");
        if ($insertComentario) {
            echo json_encode(["status" => "success", "msg" => "Comentario agregado con exito"]);
        } else {
            echo json_encode(["status" => "error", "msg" => "No se agregó el comentario"]);
        }
    } else {
        echo json_encode(["status" => "error", "msg" => "Recuerde ingresar todos los datos"]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $data = json_decode(file_get_contents("php://input"));
    if (
        isset($data->id)
        && !empty(trim($data->id))
    ) {
        $id = mysqli_real_escape_string($db_conn, trim($data->id));

        $deleteComment = mysqli_query($db_conn, "DELETE FROM `comentario` WHERE `id`='$id'");
        if ($deleteComment) {
            echo json_encode(["status" => "success", "msg" => "Comentario eliminado con exito"]);
        } else {
            echo json_encode(["status" => "error", "msg" => "No se eliminó el comentario"]);
        }
    } else {
        echo json_encode(["status" => "error", "msg" => "Recuerde ingresar todos los datos"]);
    }
} else {
    echo json_encode(["status" => "error", "msg" => "Solicitud invalida"]);
}
