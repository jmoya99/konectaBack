<?php

header('Access-Control-Allow-Headers: *');

use \Firebase\JWT\JWT;

require_once('./cors.php');

require_once('../vendor/autoload.php');
include_once './config/database.php';

if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
    header('HTTP/1.0 401 Bad Request');
    echo 'Token not found in request';
    exit;
}

$secret_key = "YOUR_SECRET_KEY";
$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$jwt = $matches[1];
if (!$jwt) {
    header('HTTP/1.0 402 Bad Request');
    exit;
}

 
    try {
 
        $decoded = JWT::decode($jwt, $secret_key, array('HS256'));

        echo json_encode(array(
            "message" => "Access granted: ".$jwt,
        ));
 
    }catch (Exception $e){
 
    http_response_code(401);
 
    echo json_encode(array(
        "message" => "Access denied.",
        "error" => $e->getMessage()
    ));
 
}
?>