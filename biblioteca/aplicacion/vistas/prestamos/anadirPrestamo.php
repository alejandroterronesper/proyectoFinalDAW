<?php


echo CHTML::dibujaEtiqueta("div", ["class" => "verObra", "style" => "margin-left: 20%;"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("h2", [], "Añadir préstamo" , true).PHP_EOL;


    echo CHTML::iniciarForm("", "POST", []).PHP_EOL;


        //usuario
        echo CHTML::modeloLabel($prestamo, "cod_usuario", []).PHP_EOL;
        echo CHTML::modeloListaDropDown($prestamo, "cod_usuario", $arrayUsuarios, []).PHP_EOL;
        echo CHTML::modeloError($prestamo, "cod_usuario", ["class"=> "error"]).PHP_EOL;

        //ejemplar
        echo CHTML::modeloLabel($prestamo, "cod_ejemplar", []).PHP_EOL;
        echo CHTML::modeloListaDropDown($prestamo, "cod_ejemplar", $ejemplaresDisponibles, []).PHP_EOL;
        echo CHTML::modeloError($prestamo, "cod_ejemplar", ["class"=> "error"]).PHP_EOL;



        //fecha inicio
        echo CHTML::modeloLabel($prestamo, "fecha_inicio", []).PHP_EOL;
        echo CHTML::modeloDate($prestamo, "fecha_inicio", []).PHP_EOL;
        echo CHTML::modeloError($prestamo, "fecha_inicio", ["class"=> "error"]).PHP_EOL;


        //fecha fin
        echo CHTML::modeloLabel($prestamo, "fecha_fin", []).PHP_EOL;
        echo CHTML::modeloDate($prestamo, "fecha_fin", []).PHP_EOL;
        echo CHTML::modeloError($prestamo, "fecha_fin", ["class"=> "error"]).PHP_EOL;



        //fecha devolucion
        echo CHTML::modeloLabel($prestamo, "fecha_devolucion", []).PHP_EOL;
        echo CHTML::modeloDate($prestamo, "fecha_devolucion", []).PHP_EOL;
        echo CHTML::modeloError($prestamo, "fecha_devolucion", ["class"=> "error"]).PHP_EOL;



        echo CHTML::campoBotonSubmit("Acepar", ["class" => "boton"]).PHP_EOL;
        echo CHTML::campoBotonReset("Cancelar", ["class" => "boton"]).PHP_EOL;



    echo CHTML::finalizarForm().PHP_EOL;
    echo "<br>".PHP_EOL;


    echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones", ], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

            echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

                echo CHTML::botonHtml(CHTML::link("Volver atrás", ["prestamos", "indexPrestamos"]), ["class"=>"boton"]).PHP_EOL;
                echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

        
        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;



?>