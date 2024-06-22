<?php

$titulo = CHTML::dibujaEtiqueta("span",["style" => "font-style:italic"], $arrayEjemplares[intval($prestamo->cod_ejemplar)]);

echo CHTML::dibujaEtiqueta("div", ["class" => "verObra", "style" => "margin-left: 20%;"], null, false).PHP_EOL;


    echo CHTML::dibujaEtiqueta("h2", [], "Ver préstamo: " .$titulo, true).PHP_EOL;

    //USUARIO
    echo CHTML::modeloLabel($prestamo, "cod_usuario", []).PHP_EOL;
    echo CHTML::dibujaEtiqueta("input", ["type" => "text", "readonly" => true, "value" => $arrayUsuarios[intval($prestamo->cod_usuario)]],null , true).PHP_EOL;


    //EJEMPLAR
    echo CHTML::modeloLabel($prestamo, "cod_ejemplar", []).PHP_EOL;
    echo CHTML::dibujaEtiqueta("input", ["type" => "text", "readonly" => true, "value" => $arrayEjemplares[intval($prestamo->cod_ejemplar)]],null , true).PHP_EOL;


    //INICIO
    echo CHTML::modeloLabel($prestamo, "fecha_inicio", []).PHP_EOL;
    echo CHTML::dibujaEtiqueta("input", ["type" => "text", "readonly" => true, "value" => $prestamo->fecha_inicio],null , true).PHP_EOL;


    //FIN
    echo CHTML::modeloLabel($prestamo, "fecha_fin", []).PHP_EOL;
    echo CHTML::dibujaEtiqueta("input", ["type" => "text", "readonly" => true, "value" => $prestamo->fecha_fin],null , true).PHP_EOL;


    //DEVOLUCION
    echo CHTML::modeloLabel($prestamo, "fecha_devolucion", []).PHP_EOL;
    if ($prestamo->fecha_devolucion === "01/01/1900"){
        echo CHTML::dibujaEtiqueta("input", ["type" => "text", "readonly" => true, "value" => "No devuelto"],null , true).PHP_EOL;

    }
    else{
        echo CHTML::dibujaEtiqueta("input", ["type" => "text", "readonly" => true, "value" => $prestamo->fecha_devolucion],null , true).PHP_EOL;

    }


    //BORRADO
    echo CHTML::modeloLabel($prestamo, "borrado", []).PHP_EOL;
    echo CHTML::modeloListaRadioButton($prestamo, "borrado", [0=>"NO", 1=> "SI"], " ", ["disabled" => true]).PHP_EOL;
    echo CHTML::modeloError($prestamo, "borrado", ["class" => "error"]).PHP_EOL;
    echo "<br>".PHP_EOL;




    echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

            echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

            echo CHTML::botonHtml(CHTML::link("Volver atrás", ["prestamos", "indexPrestamos"]), ["class"=>"boton"]).PHP_EOL;
            echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;
            echo CHTML::botonHtml(CHTML::link("Modificar préstamo", ["prestamos", "modificarPrestamo/id=".$prestamo->cod_prestamo]), ["class"=>"boton"]).PHP_EOL;


            if ($prestamo->borrado === 0){
                echo CHTML::botonHtml(CHTML::link("Borrar préstamo", ["prestamos", "borrarPrestamo/id=".$prestamo->cod_prestamo]), ["class"=>"boton"]).PHP_EOL;
            }


        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

?>