<?php



echo CHTML::dibujaEtiqueta("h2", [], "Borrar usuario: " .$usuario->nick, true).PHP_EOL;


echo CHTML::iniciarForm("", "POST", []).PHP_EOL;

echo CHTML::dibujaEtiqueta("span", [], "¿Deseas eliminar el usuario " . $usuario->nick . " ?").PHP_EOL;

echo CHTML::modeloListaRadioButton($usuario, "borrado", [0=>"NO", 1=> "SI"], " ", []).PHP_EOL;
echo CHTML::modeloError($usuario, "borrado", ["class" => "error"]).PHP_EOL;
echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;

echo CHTML::campoBotonSubmit("Acepar", ["class" => "boton"]).PHP_EOL;
echo CHTML::campoBotonReset("Cancelar", ["class" => "boton"]).PHP_EOL;


echo CHTML::finalizarForm().PHP_EOL;


echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;

echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

        echo CHTML::botonHtml(CHTML::link("Volver atrás", ["usuarios", "indexUsuarios"]), ["class"=>"boton"]).PHP_EOL;
        echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

        echo CHTML::botonHtml(CHTML::link("Ver usuario", ["usuarios", "verPrestamoC/id=".$usuario->cod_usuario]), ["class"=>"boton"]).PHP_EOL;
        echo CHTML::botonHtml(CHTML::link("Modificar usuario", ["usuarios", "modificarPrestamo/id=".$usuario->cod_usuario]), ["class"=>"boton"]).PHP_EOL;


    echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;




?>