<?php

declare(strict_types=1);

include_once "cors.php";

require 'config/database.php';

use Firebase\JWT\JWT;

require_once('../vendor/autoload.php');

function generarJWT($correo_electronico)
{
    $hasValidCredentials = true;
    if ($hasValidCredentials) {
        $secretKey  = 'B9aNmb6lJXWUZ0VHlYQPk6QG3L3cHTnn';
        $tokenId    = base64_encode(random_bytes(16));
        $issuedAt   = new DateTimeImmutable();
        $serverName = "http://localhost/konecta";
        $username   = $correo_electronico;

        $data = [
            'iat'  => $issuedAt->getTimestamp(),
            'jti'  => $tokenId,
            'iss'  => $serverName,
            'nbf'  => $issuedAt->getTimestamp(),
            'data' => [
                'userName' => $username,
            ]
        ];
        $jwt = JWT::encode(
            $data,
            $secretKey,
            'HS512'
        );
    };
    return $jwt;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    if (
        isset($data->correo_electronico)
        && isset($data->contrasena)
        && !empty(trim($data->correo_electronico))
        && !empty(trim($data->contrasena))
    ) {
        $correo_electronico = mysqli_real_escape_string($db_conn, trim($data->correo_electronico));

        $usuario = mysqli_query($db_conn, "SELECT id,nombre,tipo_usuario,correo_electronico,contrasena FROM `usuario` WHERE `correo_electronico` = '$correo_electronico' LIMIT 0,1");
        if (mysqli_num_rows($usuario) > 0) {
            $usuario = mysqli_fetch_all($usuario, MYSQLI_ASSOC);
            foreach ($usuario as $usere) {
                $user = $usere;
            }
            $contrasena = $data->contrasena;
            $contrasenaHash = $user['contrasena'];
            if (password_verify($contrasena, $contrasenaHash)) {
                $jwt = generarJWT($correo_electronico);

                $res = [
                    "jwt" => $jwt,
                    "data" => $user
                ];
                echo json_encode(["status" => "success", "data" => $res]);
            } else {
                echo json_encode(["status" => "error", "msg" => "Contraseña incorrecta"]);
            }
        } else {
            echo json_encode(["status" => "error", "msg" => "Usuario no registrado"]);
        }
    } else {
        echo json_encode(["status" => "error", "msg" => "Recuerde llenar los campos correctamente."]);
    }
} else {
    echo json_encode(["status" => "error", "msg" => "Peticion Incorrecta"]);
}
