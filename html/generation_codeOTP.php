<?php

echo "<pre>";
var_dump($_SERVER);
echo "</pre>";

if($_SERVER['REQUEST_METHOD'] === "POST"){
    var_dump($_POST);
}

?>