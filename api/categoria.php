<?php

declare(strict_types=1);

include_once "cors.php";

require 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $categorias = mysqli_query($db_conn, "SELECT * FROM `categoria`");
    if (mysqli_num_rows($categorias) > 0) {
        $categorias = mysqli_fetch_all($categorias, MYSQLI_ASSOC);
        echo json_encode(["status" => "success", "msg" => "Categorias encontrados exitosamente", "data" => $categorias]);
    } else {
        echo json_encode(["status" => "error", "msg" => "No se pudieron acceder a las categorias"]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    if (
        isset($data->titulo)
        && isset($data->descripcion)
        && !empty(trim($data->titulo))
        && !empty(trim($data->descripcion))
    ) {
        $titulo = mysqli_real_escape_string($db_conn, trim($data->titulo));
        $descripcion = mysqli_real_escape_string($db_conn, trim($data->descripcion));

        $insertCategorie = mysqli_query($db_conn, "INSERT INTO `categoria`(`titulo`,`descripcion`) VALUES('$titulo','$descripcion')");
        if ($insertCategorie) {
            $last_id = mysqli_insert_id($db_conn);
            echo json_encode(["status" => "success", "msg" => "Categoria registrada exitosamente", "id" => $last_id]);
        } else {
            echo json_encode(["status" => "error", "msg" => "Error al registrar la categoria"]);
        }
    } else {
        echo json_encode(["status" => "error", "msg" => "Recuerde llenar los campos correctamente"]);
    }
} else {
    echo json_encode(["status" => "error", "msg" => "Peticion Incorrecta"]);
}
