<?php


echo CHTML::dibujaEtiqueta("div", ["class"=> "divEjemplar", "id" => $obras["cod_obra"]], null, true).PHP_EOL;

    echo CHTML::dibujaEtiqueta("label", ["class" => "cursiva"], $obras["titulo"], true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "Autor: ". $obras["autor"]).PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "Distribuidora: ". $obras["distribuidora"]).PHP_EOL;



    //Ahora se muestra un label con imagenes que simbolicen el tipo de obra y si es digital o fisico

    echo CHTML::dibujaEtiqueta("label", [], "Categoría: ", false).PHP_EOL;
    if (intval($obras["cod_categoria_obra"]) === 1){
        echo CHTML::imagen("/imagenes/iconos/libro.png", "OBRA LIBRO", ["class" => "iconos", "title" => "libro"]).PHP_EOL;
        
    }

    if (intval($obras["cod_categoria_obra"]) === 2){
        //echo CHTML::dibujaEtiqueta("label", [], "Categoría: Película").PHP_EOL;
        echo CHTML::imagen("/imagenes/iconos/pelicula.png", "OBRA PELICULA", ["class" => "iconos", "title" => "pelicula"]).PHP_EOL;
    }


    if (intval($obras["cod_categoria_obra"]) === 3){
       // echo CHTML::dibujaEtiqueta("label", [], "Categoría: Audio").PHP_EOL;
       echo CHTML::imagen("/imagenes/iconos/audio.png", "OBRA AUDIO", ["class" => "iconos", "title" => "audio"]).PHP_EOL;
    }


    // if (intval($ejemplar["cod_formato_ejemplar"]) === 1){ //fisico
    //     echo CHTML::imagen("/imagenes/iconos/fisico.png", "FÍSICO", ["class" => "iconos", "title" => "físico"]).PHP_EOL;

    // }


    // if (intval($ejemplar["cod_formato_ejemplar"]) === 2){ //digital
    //     echo CHTML::imagen("/imagenes/iconos/digital.png", "DIGITAL", ["class" => "iconos", "title" => "digital"]).PHP_EOL;
    // }


    echo CHTML::dibujaEtiquetaCierre("label").PHP_EOL;

    // if (intval($ejemplar["cod_formato_ejemplar"]) === 1){ //true fisico
    //     echo CHTML::dibujaEtiqueta("label", [], "Formato: FÍSICO").PHP_EOL;

    // }
    // else{//false digital
    //     echo CHTML::dibujaEtiqueta("label", [], "Formato: DIGITAL").PHP_EOL;

    // }





    echo CHTML::imagen( "../../../imagenes/obras/".$obras["foto"]).PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;













?>