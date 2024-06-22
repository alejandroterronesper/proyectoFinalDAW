<?php

echo CHTML::dibujaEtiqueta("div", ["class" => "verificarUsuario"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("h2", [], "VALIDAR USUARIO", true).PHP_EOL;


    //Mensaje
    echo CHTML::dibujaEtiqueta("span", [], null, false).PHP_EOL;

        echo "Hola   <b>{$datos['nombreUsuario']}</b> se ha enviado un código de verificación <br>
            a tu correo <b>{$datos['correo']}</b>, intróducelo en el campo de verificación".PHP_EOL;

    echo CHTML::dibujaEtiquetaCierre("span").PHP_EOL;


    //Inicio de formulario
    echo CHTML::iniciarForm("", "POST", []).PHP_EOL;


        echo CHTML::dibujaEtiqueta("label", [], "Introduce el código", true).PHP_EOL;

        echo CHTML::campoNumber("codigoConfirmacion", $datos["codigoValidar"], ["id" => "codigoConfirmacion",
         "required" => true, "minlength" => "6", "maxlength" => "6"], true).PHP_EOL;

        if (isset($errores)){ //Se comprueban errores

            if (isset($errores["codigo"])){

                echo CHTML::dibujaEtiqueta("div", ["class" => "error"],null, false).PHP_EOL;

                    echo CHTML::dibujaEtiqueta("label", [], $errores["codigo"], true).PHP_EOL;

                echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
            }
        }
        echo "<br>".PHP_EOL;
      
        echo CHTML::campoBotonSubmit("Confirmar", ["name"=>"confirmarCodigo", "class" => "boton"]).PHP_EOL;
        echo CHTML::campoBotonReset("Limpiar campo", ["class" => "boton"]).PHP_EOL;


        echo CHTML::dibujaEtiqueta("p", [], null, false ).PHP_EOL;

            echo "Si no ha llegado el código, pulsa ".PHP_EOL;
            echo CHTML::dibujaEtiqueta("b", [], null, false).PHP_EOL;
                echo CHTML::link("aqui", ["login", "ReenviarCodigo"]).PHP_EOL;
            echo CHTML::dibujaEtiquetaCierre("b").PHP_EOL;

        echo CHTML::dibujaEtiquetaCierre("p").PHP_EOL;
       

    echo CHTML::finalizarForm().PHP_EOL;




echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;




?>