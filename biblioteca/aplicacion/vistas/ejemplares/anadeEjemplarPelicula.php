<?php

$this->textoHead = CHTML::scriptFichero("/js/anadeEjemplar.js", ["defer" => "defer"]).PHP_EOL;



echo CHTML::dibujaEtiqueta("div", ["class" => "verObra", "style" => "margin-left: 20%;"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("h2", [], "Añade ejemplar película ", true).PHP_EOL;

    echo CHTML::iniciarForm("", "post", ["enctype" =>"multipart/form-data"]).PHP_EOL;
        
        echo CHTML::dibujaEtiqueta("label", [], "Elige una obra:", true).PHP_EOL;
        echo CHTML::campoListaDropDown("obra", $datos["codPelicula"], $arrayObras, ["name" => "comboObraEjemplar", "required"=> true]).PHP_EOL;

        echo CHTML::modeloLabel($ejemplar, "formato_ejemplar", []).PHP_EOL;
        echo CHTML::modeloListaDropDown($ejemplar, "cod_formato_ejemplar", $arrayFormatoEjemplar, ["id" => "comboFormatoFisicoDigital", "required" => true] ).PHP_EOL;
        echo CHTML::modeloError($ejemplar, "cod_formato_ejemplar", ["class" => "error"]).PHP_EOL;


        //FORMATO MEDIO
        echo CHTML::modeloLabel($ejemplar, "descripcion_formato_medio", []) . PHP_EOL;
        echo CHTML::modeloListaDropDown($ejemplar, "codigo_formato_medio", $arrayFormatosMedios, ["id" => "comboFormatosEjemplaresMedios"]) . PHP_EOL;
        echo CHTML::modeloError($ejemplar, "codigo_formato_medio", ["class" => "error"]) . PHP_EOL;
        echo "<br>" . PHP_EOL;

        //fecha registro
        echo CHTML::modeloLabel($ejemplar, "fecha_registro", []).PHP_EOL;
        echo CHTML::modeloDate($ejemplar, "fecha_registro", ["placeholder" => "dd/mm/aaaa"]).PHP_EOL;
        echo CHTML::modeloError($ejemplar, "fecha_registro", ["class" => "error"]).PHP_EOL;
    
        //DURACION
        echo CHTML::modeloLabel($pelicula, "duracion", ["readonly" => true]).PHP_EOL;
        echo CHTML::campoNumber("horaP", $datos["horaP"], ["min" => 0, "max" => 23, "placeholder" => "HH", "size" => 2, "name" => "horaP"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], ":", true).PHP_EOL;
        echo CHTML::campoNumber("minutoP", $datos["minutoP"], ["min" => 0, "max" => 59, "placeholder" => "MM", "size" => 2, "name" => "minutoP"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], ":", true).PHP_EOL;
        echo CHTML::campoNumber("segundoP", $datos["segundoP"], ["min" => 0, "max" => 59, "placeholder" => "SS", "size" => 2, "name" => "segundoP"]).PHP_EOL;        
        echo CHTML::modeloError($pelicula, "duracion", ["class" => "error"]).PHP_EOL;
        echo "<br>";
        echo "<br>";


        //PAISES
        echo CHTML::modeloLabel($pelicula, "pais", ["readonly" => true]) . PHP_EOL;
        echo CHTML::modeloListaDropDown($pelicula, "pais", $arrayPaisesPeliculas, []) . PHP_EOL;
        echo CHTML::modeloError($pelicula, "pais", ["class" => "error"]) . PHP_EOL;

        //CALIFICACIONEDAD
        echo CHTML::modeloLabel($pelicula, "calificacion_edad", ["readonly" => true]) . PHP_EOL;
        echo CHTML::modeloListaDropDown($pelicula, "calificacion_edad", $arrayCalificacionEdad) . PHP_EOL;
        echo CHTML::modeloError($pelicula, "calificacion_edad", ["class" => "error"]) . PHP_EOL;


        
        echo CHTML::modeloLabel($ejemplar, "ubicacion_ejemplar", [], true).PHP_EOL;

        //DIGITAL
        echo CHTML::dibujaEtiqueta("div", ["style" => $divInputFile["style"], "id" => "divAnadeFileE"], null, false).PHP_EOL;

            echo CHTML::campoHidden("MAX_FILE_SIZE", 1000000000000, []).PHP_EOL;
            echo CHTML::modeloFile($ejemplar, "ubicacion_ejemplar",["style" => "display:inline", "id"=> "inputFILEE", "accept" => "video/*"]).PHP_EOL;

            if (isset($errores["ubicacion_ejemplar"])){
                echo CHTML::dibujaEtiqueta("div", ["class"=> "error"], null, false).PHP_EOL;
                    echo CHTML::dibujaEtiqueta("span", [], $errores["ubicacion_ejemplar"], true).PHP_EOL;
                echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
            }

        echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
        
        //FISICO
        echo CHTML::dibujaEtiqueta("div", ["style" => $divInputUbicacion["style"], "id" => "divAnadeUbicacionE"], null, false).PHP_EOL;
            echo CHTML::modeloText($ejemplar, "ubicacion_ejemplar",["style" => "display:inline"]).PHP_EOL;
        echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
        echo CHTML::modeloError($ejemplar, "ubicacion_ejemplar", ["class" => "error"]).PHP_EOL;
        echo "<br>".PHP_EOL;
        echo "<br>".PHP_EOL;

        //borrado
        echo CHTML::modeloLabel($ejemplar, "borrado_ejemplar", []) . PHP_EOL;
        echo CHTML::modeloListaRadioButton($ejemplar, "borrado_ejemplar", [0 => "NO", 1 => "SI"], " ", []) . PHP_EOL;
        echo CHTML::modeloError($ejemplar, "borrado_ejemplar", ["class" => "error"]) . PHP_EOL;
        echo "<br>";
        echo "<br>";


        //estado
        echo CHTML::modeloLabel($ejemplar, "estado_ejemplar", []) . PHP_EOL;
        echo CHTML::modeloListaRadioButton($ejemplar, "estado_ejemplar", [0 => "DISPONIBLE", 1 => "RESERVADO"], " ", []) . PHP_EOL;
        echo CHTML::modeloError($ejemplar, "estado_ejemplar", ["class" => "error"]) . PHP_EOL;
        echo "<br>";
        echo "<br>";

        echo CHTML::campoBotonSubmit("Añadir Ejemplar", ["class" => "boton"]).PHP_EOL;
        echo CHTML::campoBotonReset("Borrar datos", ["id" => "resetCampos","class" => "boton"]).PHP_EOL;

    echo CHTML::finalizarForm().PHP_EOL;

    echo "<br>";

    //Operaciones
    echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

            echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

            echo CHTML::botonHtml(CHTML::link("Volver atrás", ["ejemplares", "indexEjemplares"]), ["class"=>"boton"]).PHP_EOL;
            echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;



?>