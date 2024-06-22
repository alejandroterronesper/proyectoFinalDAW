<?php


echo CHTML::dibujaEtiqueta("div", ["class"=> "divPrestamo", "id" => intval($prestamo["cod_prestamo"]) ."-". $codUsuario], null, true).PHP_EOL;

    echo CHTML::dibujaEtiqueta("label", ["class" => "cursiva"], $prestamo["titulo"], true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "Autor: ". $prestamo["autor"]).PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "Distribuidora: ". $prestamo["distribuidora"]).PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "Fecha de inicio de préstamo: " .$prestamo["fecha_inicio"]).PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "Fecha de devolución de préstamo: " .$prestamo["fecha_fin"]).PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "Estado: NO DEVUELTO" ).PHP_EOL;

    // if (isset($prestamo["fecha_devolucion"])){ 
    //     echo CHTML::dibujaEtiqueta("label", [], "Estado: DEVUELTO").PHP_EOL;

    // }
    // else{//Si no tiene fecha de devolución, entonces le mostramos el botón de opciones
    //     echo CHTML::dibujaEtiqueta("label", [], "Estado: NO DEVUELTO" ).PHP_EOL;

    //     echo CHTML::botonHtml("Más opciones", ["class" => "boton"]).PHP_EOL;

    // }


    echo CHTML::dibujaEtiqueta("label", [], "Categoría: ", false).PHP_EOL;
    if (intval($prestamo["cod_categoria_obra"]) === 1){
        echo CHTML::imagen("/imagenes/iconos/libro.png", "OBRA LIBRO", ["class" => "iconos", "title" => "libro"]).PHP_EOL;
        
    }

    if (intval($prestamo["cod_categoria_obra"]) === 2){
        //echo CHTML::dibujaEtiqueta("label", [], "Categoría: Película").PHP_EOL;
        echo CHTML::imagen("/imagenes/iconos/pelicula.png", "OBRA PELICULA", ["class" => "iconos", "title" => "pelicula"]).PHP_EOL;
    }


    if (intval($prestamo["cod_categoria_obra"]) === 3){
       // echo CHTML::dibujaEtiqueta("label", [], "Categoría: Audio").PHP_EOL;
       echo CHTML::imagen("/imagenes/iconos/audio.png", "OBRA AUDIO", ["class" => "iconos", "title" => "audio"]).PHP_EOL;
    }

    echo CHTML::dibujaEtiquetaCierre("label").PHP_EOL;

    echo CHTML::imagen( "../../../imagenes/obras/".$prestamo["foto"]).PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;




?>