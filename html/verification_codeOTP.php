<?php

require __DIR__ . '/../vendor/autoload.php';

use OTPHP\TOTP;

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $codeOTP = $_POST['codeOTP'] ?? "";
    $codeCompte = $_POST['code_compte'] ?? "";

    echo json_encode(["success" => true, "message" => $codeOTP." /".$codeCompte]);
}

?>