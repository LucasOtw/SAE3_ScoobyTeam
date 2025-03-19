<?php

require_once __DIR__ . ("/../../.security/config.php");

if($_SERVER['REQUEST_METHOD'] === "POST"){
        var_dump($_POST);
}

?>
<!DOCTYPE html>
<html lang="fr">
        <head>
                <meta charset="utf-8">
                <title>Mon Formulaire</title>
        </head>
        <body>
                <h1>Mon formulaire</h1>
                <form id="formulaire" method="POST" action="#">
                        <input type="hidden" name="monChamp" value="Je suis une banane">
                        <input type="submit" value="Envoyer">
                </form>
        </body>
        <script>
                document.addEventListener('DOMContentLoaded',function(){
                        console.log("test");
                        let monForm = document.getElementById('formulaire');
                        monForm.addEventListener('submit',(e) =>{
                                e.preventDefault();
                                console.log("HAHAHAHAHAHAHA");
                        });
                });
        </script>
</html>