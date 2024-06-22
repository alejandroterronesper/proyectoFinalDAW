<?php

$titulo = CHTML::dibujaEtiqueta("span",["style" => "font-style:italic"], $arrayEjemplares[intval($prestamo->cod_ejemplar)]);
$user = $arrayUsuarios[intval($prestamo->cod_usuario)];
echo CHTML::dibujaEtiqueta("h2", [], "Borrar préstamo: " .$titulo, true).PHP_EOL;

echo CHTML::iniciarForm("", "POST", []).PHP_EOL;

    echo CHTML::dibujaEtiqueta("span", [], "¿Deseas eliminar el préstamo " . $titulo . " del usuario ". $user . " ?").PHP_EOL;
    echo CHTML::modeloListaRadioButton($prestamo, "borrado", [0=>"NO", 1=> "SI"], " ", []).PHP_EOL;
    echo CHTML::modeloError($prestamo, "borrado", ["class" => "error"]).PHP_EOL;
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

        echo CHTML::botonHtml(CHTML::link("Volver atrás", ["prestamos", "indexPrestamos"]), ["class"=>"boton"]).PHP_EOL;
        echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

        echo CHTML::botonHtml(CHTML::link("Ver préstamo", ["prestamos", "verPrestamoC/id=".$prestamo->cod_prestamo]), ["class"=>"boton"]).PHP_EOL;
        echo CHTML::botonHtml(CHTML::link("Modificar préstamo", ["prestamos", "modificarPrestamo/id=".$prestamo->cod_prestamo]), ["class"=>"boton"]).PHP_EOL;


    echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

?>