<?php

$titulo = CHTML::dibujaEtiqueta("span",["style" => "font-style:italic"], $arrayEjemplares[intval($prestamo->cod_ejemplar)]);


echo CHTML::dibujaEtiqueta("div", ["class" => "verObra", "style" => "margin-left: 20%;"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("h2", [], "Modificar préstamo: " .$titulo, true).PHP_EOL;


    //USUARIO
    echo CHTML::modeloLabel($prestamo, "cod_usuario", []).PHP_EOL;
    echo CHTML::dibujaEtiqueta("input", ["type" => "text", "readonly" => true, "value" => $arrayUsuarios[intval($prestamo->cod_usuario)]],null , true).PHP_EOL;


    //EJEMPLAR
    echo CHTML::modeloLabel($prestamo, "cod_ejemplar", []).PHP_EOL;
    echo CHTML::dibujaEtiqueta("input", ["type" => "text", "readonly" => true, "value" => $arrayEjemplares[intval($prestamo->cod_ejemplar)]],null , true).PHP_EOL;

    //Ponemos el formulario a partir de aquí asi evitamos que los datos
    //por defecto no se puedan modificar
    echo CHTML::iniciarForm("", "POST", []).PHP_EOL;

        //INICIO
        echo CHTML::modeloLabel($prestamo, "fecha_inicio", []).PHP_EOL;
        echo CHTML::modeloDate($prestamo, "fecha_inicio", []).PHP_EOL;
        echo CHTML::modeloError($prestamo, "fecha_inicio", ["class"=>"error"]).PHP_EOL;


        //FIN
        echo CHTML::modeloLabel($prestamo, "fecha_fin", []).PHP_EOL;
        echo CHTML::modeloDate($prestamo, "fecha_fin", []).PHP_EOL;
        echo CHTML::modeloError($prestamo, "fecha_fin", ["class"=>"error"]).PHP_EOL;

        //DEVOLUCION
        echo CHTML::modeloLabel($prestamo, "fecha_devolucion", []).PHP_EOL;
        echo CHTML::modeloDate($prestamo, "fecha_devolucion", []).PHP_EOL;
        echo CHTML::modeloError($prestamo, "fecha_devolucion", ["class"=>"error"]).PHP_EOL;


        //BORRADO
        echo CHTML::modeloLabel($prestamo, "borrado", []).PHP_EOL;
        echo CHTML::modeloListaRadioButton($prestamo, "borrado", [0=>"NO", 1=> "SI"], " ", []).PHP_EOL;
        echo CHTML::modeloError($prestamo, "borrado", ["class" => "error"]).PHP_EOL;
        echo "<br>".PHP_EOL;

        echo CHTML::campoBotonSubmit("Modificar datos", ["class" => "boton"]).PHP_EOL;
        echo CHTML::campoBotonReset("Limpiar campos", ["class" => "boton"]).PHP_EOL;

    echo CHTML::finalizarForm().PHP_EOL;


    echo "<br>".PHP_EOL;

    echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

            echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

            echo CHTML::botonHtml(CHTML::link("Volver atrás", ["prestamos", "indexPrestamos"]), ["class"=>"boton"]).PHP_EOL;
            echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

            echo CHTML::botonHtml(CHTML::link("Ver préstamo", ["prestamos", "verPrestamoC/id=".$prestamo->cod_prestamo]), ["class"=>"boton"]).PHP_EOL;


            if ($prestamo->borrado === 0){
                echo CHTML::botonHtml(CHTML::link("Borrar préstamo", ["prestamos", "borrarPrestamo/id=".$prestamo->cod_prestamo]), ["class"=>"boton"]).PHP_EOL;
            }

        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

?>