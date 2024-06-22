<?php

$titulo = CHTML::dibujaEtiqueta("span",["style" => "font-style:italic"], $obra->titulo);


echo CHTML::dibujaEtiqueta("h2", [], "Borrar obra: " .$titulo, true).PHP_EOL;

//Formulario
echo CHTML::iniciarForm("", "post", []).PHP_EOL;

echo CHTML::dibujaEtiqueta("span", [], "¿Deseas eliminar la obra " .  $obra->titulo .  " ?").PHP_EOL;
echo CHTML::modeloListaRadioButton($obra, "borrado", [0=>"NO", 1=> "SI"], " ", []).PHP_EOL;
echo CHTML::modeloError($obra, "borrado", ["class" => "error"]).PHP_EOL;

echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;

echo CHTML::campoBotonSubmit("Borrar obra", ["class" => "boton"]).PHP_EOL;
echo CHTML::campoBotonReset("Limpiar campos", ["class" => "boton"]).PHP_EOL;

echo CHTML::finalizarForm().PHP_EOL;


//Operaciones
echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

    echo CHTML::botonHtml(CHTML::link("Volver atrás", ["obras", "indexObras"]), ["class"=>"boton"]).PHP_EOL;
    echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;
    echo CHTML::botonHtml(CHTML::link("Modificar obra", ["obras", "modificarObra/id=".$obra->cod_obra]), ["class"=>"boton"]).PHP_EOL;
    echo CHTML::botonHtml(CHTML::link("Ver obra", ["obras", "verObra/id=".$obra->cod_obra]), ["class"=>"boton"]).PHP_EOL;



echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


?>