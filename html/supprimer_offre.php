<?php

ob_start();
session_start();

if(isset($_POST)){
    var_dump(unserialize($_POST));
}

?>
