<?php

ob_start();
session_start();

if(isset($_POST)){
    echo "t";
    var_dump(unserialize($_POST));
}

?>
