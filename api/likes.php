<?php

declare(strict_types=1);

include_once "cors.php";

require 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $likes = mysqli_query($db_conn, "SELECT * FROM `likes`");
    if (mysqli_num_rows($likes) > 0) {
        $likes = mysqli_fetch_all($likes, MYSQLI_ASSOC);
        echo json_encode(["status" => "success", "likes" => $likes]);
    } else {
        echo json_encode(["status" => "success", "likes" => []]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (
        isset($data->id_articulo)
        && isset($data->id_usuario)
        && !empty(trim($data->id_articulo))
        && !empty(trim($data->id_usuario))
    ) {
        $id_articulo = mysqli_real_escape_string($db_conn, trim($data->id_articulo));
        $id_usuario = mysqli_real_escape_string($db_conn, trim($data->id_usuario));

        $insertLike = mysqli_query($db_conn, "INSERT INTO `likes`(`id_articulo`,`id_usuario`) VALUES('$id_articulo','$id_usuario')");
        if ($insertLike) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    } else {
        echo json_encode(["status" => "error"]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $data = json_decode(file_get_contents("php://input"));
    if (
        isset($data->id_articulo)
        && isset($data->id_usuario)
        && !empty(trim($data->id_articulo))
        && !empty(trim($data->id_usuario))
    ) {
        $id_articulo = mysqli_real_escape_string($db_conn, trim($data->id_articulo));
        $id_usuario = mysqli_real_escape_string($db_conn, trim($data->id_usuario));

        $deleteLike = mysqli_query($db_conn, "DELETE FROM `likes` WHERE `id_articulo`='$id_articulo' AND `id_usuario`='$id_usuario'");
        if ($deleteLike) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    } else {
        echo json_encode(["status" => "error"]);
    }
} else {
    echo json_encode(["status" => "error"]);
}
