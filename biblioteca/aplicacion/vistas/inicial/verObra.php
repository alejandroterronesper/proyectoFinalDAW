<?php

echo CHTML::dibujaEtiqueta("div", ["class" => "verObraInicial"], null, false).PHP_EOL;

//Primero se sacan características generales
echo CHTML::dibujaEtiqueta("h1", ["style" => "font-style: italic"], $obra->titulo, true).PHP_EOL;
echo CHTML::dibujaEtiqueta("h3", [], "Autor: ". $obra->autor, true).PHP_EOL;
echo CHTML::dibujaEtiqueta("h3", [], "Género: ". GenerosObras::devuelveGenerosObras(intval($obra->cod_genero)), true).PHP_EOL;
echo CHTML::dibujaEtiqueta("h3", [], "Distribuidora: ". $obra->distribuidora, true).PHP_EOL;
echo CHTML::dibujaEtiqueta("h3", [], "Fecha de lanzamiento: ". $obra->fecha_lanzamiento, true).PHP_EOL;


echo CHTML::dibujaEtiqueta("h3", [], "Descripción: ", true).PHP_EOL;
echo CHTML::dibujaEtiqueta("p", [], $obra->descripcion, true).PHP_EOL;



//Iteramos el array de ejemplares, comprobamos su disponibilidad
$disponibles = 0;
foreach ($arrayEjemplares as $clave => $valor){

    if (intval($valor["estado_ejemplar"]) === 0){ //0 ES DISPONIBLE
        $disponibles++;
    }

}


if ($disponibles  > 0){ //CONTAMOS Nº EJEMPALRES
    echo CHTML::dibujaEtiqueta("h3", [], "Ejemplares disponibles: ".  CHTML::dibujaEtiqueta("span", ["class" => "disponible"],$disponibles, true), true).PHP_EOL;


    echo CHTML::campoLabel("Elija ejemplar: ", "ejemplar", []).PHP_EOL;
    $arrayOpciones = [];

    foreach($arrayEjemplares as $clave => $valor){

        if (intval($valor["cod_formato_ejemplar"]) === 1){//FÍSICO
            $arrayOpciones[intval($valor["cod_ejemplar"])] = "FÍSICO". " - " . $valor["ubicacion_ejemplar"];
        }
        if (intval($valor["cod_formato_ejemplar"]) === 2){//DIGITAL
            $arrayOpciones[intval($valor["cod_ejemplar"])] = "DIGITAL". " - " . $valor["ubicacion_ejemplar"];
        }

    }

   
    echo CHTML::iniciarForm("#", "post", ["class" => "verObraInicialform"]).PHP_EOL;

    echo CHTML::campoListaDropDown("ejemplar", -1, $arrayOpciones, ["required" => true]).PHP_EOL;
    echo CHTML::campoBotonSubmit("Reservar ejemplar", ["name" => "cogeEjemplar"] ).PHP_EOL;

    if (isset($encontrado)){

        if ($encontrado === false){
            echo CHTML::dibujaEtiqueta("div", ["class" => "error"], null, false).PHP_EOL;

                echo CHTML::dibujaEtiqueta("span", [], "Se ha elegido una opción incorrecta", true).PHP_EOL;

            echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
        }

    }

    echo CHTML::finalizarForm().PHP_EOL;



}
else{
    echo CHTML::dibujaEtiqueta("span", ["class" => "disponible"], "Actualmente no hay ejemplares disponibles").PHP_EOL;
}




echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;










?>