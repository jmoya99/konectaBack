<?php
include_once "cors.php";
header("Access-Control-Allow-Methods: POST");

require 'config/database.php';

function generarSlug($titulo, $id){
    // quito espacios dobles
    $tituloParse = str_replace("/\s+/", " ", trim($titulo));
    // reemplazo especios por guiones
    $tituloParse = str_replace(" ", "-", trim($tituloParse));
    return "articulo/$id/$tituloParse";
}

$data = json_decode(file_get_contents("php://input"));
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["status" => "error", "msg" => "Peticion Incorrecta"]);
} elseif (
    isset($data->titulo)
    && isset($data->texto_corto)
    && isset($data->texto_largo)
    && isset($data->imagen)
    && isset($data->id_unico)
    && isset($data->id_categoria)
    && !empty(trim($data->id_unico))
    && !empty(trim($data->id_categoria))
    && !empty(trim($data->titulo))
    && !empty(trim($data->texto_corto))
    && !empty(trim($data->texto_largo))
    && !empty(trim($data->imagen))
) {
    $id_categoria = mysqli_real_escape_string($db_conn, trim($data->id_categoria));
    $titulo = mysqli_real_escape_string($db_conn, trim($data->titulo));
    $texto_corto = mysqli_real_escape_string($db_conn, trim($data->texto_corto));
    $texto_largo = mysqli_real_escape_string($db_conn, trim($data->texto_largo));
    $imagen = mysqli_real_escape_string($db_conn, trim($data->imagen));

    $delID = $data->id_unico;
    $slug = generarSlug($titulo, $delID );
    $updateArticulo = mysqli_query($db_conn, "UPDATE `articulo` SET `id_categoria`='$id_categoria',`titulo`='$titulo',`slug`='$slug',`texto_corto`='$texto_corto',`texto_largo`='$texto_largo',`imagen`='$imagen',`fecha_actualizacion`=now() WHERE `id_unico`='$delID'");
    if ($updateArticulo) {
        echo json_encode(["status" => "success", "msg" => "Articulo Actualizado Exitosamente."]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Articulo No Actualizado"]);
    }
} else {
    echo json_encode(["status" => "error", "msg" => "Recuerda llenar los campos correctamente"]);
}
