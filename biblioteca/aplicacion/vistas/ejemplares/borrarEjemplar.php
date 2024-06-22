<?php

$titulo = CHTML::dibujaEtiqueta("span",["style" => "font-style:italic"], $ejemplar->titulo);

echo CHTML::dibujaEtiqueta("h2", [], "Borrar ejemplar: " .$titulo, true).PHP_EOL;

echo CHTML::iniciarForm("", "post", []).PHP_EOL;

echo CHTML::dibujaEtiqueta("span", [], "¿Deseas eliminar el ejemplar ". $titulo 
        . " - " .$ejemplar->formato_ejemplar.
         " - ". $ejemplar->descripcion_formato_medio ."? ").PHP_EOL;


echo CHTML::modeloListaRadioButton($ejemplar, "borrado_ejemplar", [0=>"NO", 1=> "SI"], " ", []).PHP_EOL;
echo CHTML::modeloError($ejemplar, "borrado_ejemplar", ["class" => "error"]).PHP_EOL;
echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;

echo CHTML::campoBotonSubmit("Acepar", ["class" => "boton"]).PHP_EOL;
echo CHTML::campoBotonReset("Cancelar", ["class" => "boton"]).PHP_EOL;

echo CHTML::finalizarForm().PHP_EOL;


echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;

//Operaciones
echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

    echo CHTML::botonHtml(CHTML::link("Volver atrás", ["ejemplares", "indexEjemplares"]), ["class"=>"boton"]).PHP_EOL;
    echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

    //En caso de querer ver mas datos de la obra
    echo CHTML::botonHtml(CHTML::link("Ver ejemplar", ["ejemplares", "verEjemplar/id=".$ejemplar->cod_ejemplar]), ["class"=>"boton"]).PHP_EOL;

    echo CHTML::botonHtml(CHTML::link("Modificar ejemplar", ["ejemplares", "modificarEjemplar/id=".$ejemplar->cod_ejemplar]), ["class"=>"boton"]).PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;





?>