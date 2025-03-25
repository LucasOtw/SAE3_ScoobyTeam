<?php

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $codeOTP = $_POST['codeOTP'] ?? "";

    echo json_encode(["success" => true, "message" => $codeOTP]);
}

?>