<?php

include_once "cors.php";

require 'config/database.php';

function generarSlug($titulo, $id){
    // quito espacios dobles
    $tituloParse = str_replace("/\s+/", " ", trim($titulo));
    // reemplazo especios por guiones
    $tituloParse = str_replace(" ", "-", trim($tituloParse));
    return "articulo/$id/$tituloParse";
}

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $articulos = mysqli_query($db_conn, "SELECT * FROM `articulo`");
    if (mysqli_num_rows($articulos) > 0) {
        $articulos = mysqli_fetch_all($articulos, MYSQLI_ASSOC);
        echo json_encode(["status" => "success", "msg"=> "Articulos enontrados exitosamente", "data" => $articulos]);
    } else {
        echo json_encode(["status" => "error", "msg"=> "No hay articulos disponibles"]);
    }
}elseif($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = json_decode(file_get_contents("php://input"));
    if (
        isset($data->titulo)
        && isset($data->id_categoria)
        && isset($data->texto_corto)
        && isset($data->texto_largo)
        && isset($data->imagen)
        && !empty(trim($data->titulo))
        && !empty(trim($data->id_categoria))
        && !empty(trim($data->texto_corto))
        && !empty(trim($data->texto_largo))
        && !empty(trim($data->imagen))
    ) {
        $titulo = mysqli_real_escape_string($db_conn, trim($data->titulo));
        $id_categoria = mysqli_real_escape_string($db_conn, trim($data->id_categoria));
        $texto_corto = mysqli_real_escape_string($db_conn, trim($data->texto_corto));
        $texto_largo = mysqli_real_escape_string($db_conn, trim($data->texto_largo));
        $imagen = mysqli_real_escape_string($db_conn, trim($data->imagen));

        $insertArticulo = mysqli_query($db_conn, "INSERT INTO `articulo`(`titulo`,`id_categoria`,`texto_corto`,`texto_largo`,`imagen`,`fecha_creacion`,`fecha_actualizacion`) VALUES('$titulo','$id_categoria','$texto_corto','$texto_largo','$imagen',now(),now())");
        if ($insertArticulo) {
            $last_id = mysqli_insert_id($db_conn);
            $slug = generarSlug($titulo, $last_id);
            $updateArticulo = mysqli_query($db_conn, "UPDATE `articulo` SET `slug`='$slug' WHERE `id_unico`='$last_id'");
            if($updateArticulo){
            echo json_encode(["status" => "success", "msg" => "Articulo registrado exitosamente.", "id" => $last_id]);
            }else{
                echo json_encode(["status" => "error", "msg" => "Error al guardar el slug"]);
            }
        } else {
            echo json_encode(["status" => "error", "msg" => "Campo Invalido."]);
        }
    } else{
        echo json_encode(["status" => "error", "msg" => "Recuerde llenar los campos correctamente"]);
    }
}  else {
    echo json_encode(["status" => "error", "msg" => "Peticion Incorrecta"]);
}