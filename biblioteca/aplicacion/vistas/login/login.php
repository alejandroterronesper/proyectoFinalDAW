<?php


echo CHTML::iniciarForm("", "POST", ["class" => "formLogin"]).PHP_EOL;

    echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;
    
        echo CHTML::dibujaEtiqueta("legend", [], "Iniciar sesión", true).PHP_EOL;
        echo CHTML::modeloLabel($miLogin, "nick").PHP_EOL;
        echo CHTML::modeloText($miLogin, "nick", ["required" => true]).PHP_EOL;
        echo CHTML::modeloError($miLogin, "nick").PHP_EOL;

        echo CHTML::modeloLabel($miLogin, "contrasenia").PHP_EOL;
        echo CHTML::modeloPassword($miLogin, "contrasenia", ["maxlength" => 20, "required" => true])."<br>".PHP_EOL;
        echo CHTML::modeloError($miLogin, "contrasenia").PHP_EOL;


        echo "<br>".PHP_EOL;
        echo CHTML::campoBotonSubmit("Loguearse", ["class" => "boton", "name" => "botonLogin"]).PHP_EOL;
        echo CHTML::campoBotonReset("Limpiar campos", ["class" => "boton"]).PHP_EOL;

        echo CHTML::link("¿No tienes cuenta?", ["login", "NuevoUsuario"]).PHP_EOL; //Crear registro

    echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

echo CHTML::finalizarForm().PHP_EOL;


?>