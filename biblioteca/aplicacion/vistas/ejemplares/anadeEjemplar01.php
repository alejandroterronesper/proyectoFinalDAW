<?php
$this->textoHead = CHTML::scriptFichero("/js/main.js", ["defer" => "defer"]).PHP_EOL;

echo CHTML::dibujaEtiqueta("h2", [], "Añade ejemplar ", true).PHP_EOL;


echo CHTML::iniciarForm("", "post", ["enctype" =>"multipart/form-data"]).PHP_EOL;

echo CHTML::dibujaEtiqueta("label", [], "Elige una obra:", true).PHP_EOL;
echo CHTML::campoListaDropDown("obra", -1, $arrayObras, ["id" => "comboObraEjemplar"]).PHP_EOL;


//PRIMER DIV OCULTO
echo CHTML::dibujaEtiqueta("div", ["id" => "camposAnade", "style" =>$divOculto["style"]], null, false).PHP_EOL;

    // //TITULO
    echo CHTML::modeloLabel($ejemplar, "titulo", []).PHP_EOL;
    echo CHTML::modeloText($ejemplar, "titulo", ["readonly" => true]).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "titulo", ["class" => "error"]).PHP_EOL;
    echo "<br>".PHP_EOL;
    //AUTOR
    echo CHTML::modeloLabel($ejemplar, "autor", []).PHP_EOL;
    echo CHTML::modeloText($ejemplar, "autor", ["readonly" => true]).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "autor", ["class" => "error"]).PHP_EOL;
    echo "<br>".PHP_EOL;

    //DESCRIPCION GENERO
    echo CHTML::modeloLabel($ejemplar, "descripcion_genero", []).PHP_EOL;
    echo CHTML::modeloListaDropDown($ejemplar, "codigo_genero", $generosObras, ["disabled" => true]).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "codigo_genero", ["class" => "error"]).PHP_EOL;

    echo "<br>".PHP_EOL;

    //DISTRIBUIDORA
    echo CHTML::modeloLabel($ejemplar, "distribuidora", []).PHP_EOL;
    echo CHTML::modeloText($ejemplar, "distribuidora", ["readonly" => true]).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "distribuidora", ["class" => "error"]).PHP_EOL;

    echo "<br>".PHP_EOL;

    //FECHA LANZAMIENTO
    echo CHTML::modeloLabel($ejemplar, "fecha_lanzamiento", []).PHP_EOL;
    echo CHTML::modeloText($ejemplar, "fecha_lanzamiento", ["readonly" => true]).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "fecha_lanzamiento", ["class" => "error"]).PHP_EOL;

    echo "<br>".PHP_EOL;


    //Parte editable

    //FORMATO EJEMPLAR
    echo CHTML::modeloLabel($ejemplar, "formato_ejemplar", []).PHP_EOL;
    echo CHTML::modeloListaDropDown($ejemplar, "cod_formato_ejemplar", $arrayFormatos, [] ).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "cod_formato_ejemplar", ["class" => "error"]).PHP_EOL;

    echo "<br>".PHP_EOL;

    //SEGUNDO DIV 
    //DIV INPUT FILE
    echo CHTML::modeloLabel($ejemplar, "ubicacion_ejemplar", [], true).PHP_EOL;


    echo CHTML::dibujaEtiqueta("div", ["style" => $divInputFile["style"], "id" => "divAnadeFileE"], null, false).PHP_EOL;

        echo CHTML::campoHidden("MAX_FILE_SIZE", 1000000000000, []).PHP_EOL;
        echo CHTML::modeloFile($ejemplar, "ubicacion_ejemplar",["style" => "display:inline"]).PHP_EOL;
        echo CHTML::modeloError($ejemplar, "ubicacion_ejemplar", ["class" => "error"]).PHP_EOL;

        if (isset($errorFichero)){
            echo CHTML::dibujaEtiqueta("div", ["class"=> "error"], null, false).PHP_EOL;
                echo CHTML::dibujaEtiqueta("span", [], $errorFichero, true).PHP_EOL;
            echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
        }

        if ($esValido !== true){//si es distinto de true, es que hay errores
            echo CHTML::dibujaEtiqueta("div", ["class"=> "error"], null, false).PHP_EOL;
                echo CHTML::dibujaEtiqueta("span", [], $esValido, true).PHP_EOL;
            echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
        }

    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

    
    echo CHTML::dibujaEtiqueta("div", ["style" => $divInputUbicacion["style"], "id" => "divAnadeUbicacionE"], null, false).PHP_EOL;

        echo CHTML::modeloText($ejemplar, "ubicacion_ejemplar",["style" => "display:inline"]).PHP_EOL;
        echo CHTML::modeloError($ejemplar, "ubicacion_ejemplar", ["class" => "error"]).PHP_EOL;


    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;



    echo "<br>".PHP_EOL;


    //FORMATO MEDIO
    echo CHTML::modeloLabel($ejemplar, "descripcion_formato_medio", []) . PHP_EOL;
    echo CHTML::modeloListaDropDown($ejemplar, "codigo_formato_medio", $arrayFormatosMedios, []) . PHP_EOL;
    echo CHTML::modeloError($ejemplar, "codigo_formato_medio", ["class" => "error"]) . PHP_EOL;
    echo "<br>".PHP_EOL;

    //FECHA REGISTRO
    echo CHTML::modeloLabel($ejemplar, "fecha_registro", []) . PHP_EOL;
    echo CHTML::modeloDate($ejemplar, "fecha_registro", ["placeholder" => "dd/mm/aaaa"]) . PHP_EOL;
    echo CHTML::modeloError($ejemplar, "fecha_registro", ["class" => "error"]) . PHP_EOL;
    echo "<br>" . PHP_EOL;

    //BORRADO EJEMPLAR
    echo CHTML::modeloLabel($ejemplar, "borrado_ejemplar", []).PHP_EOL;
    echo CHTML::modeloListaRadioButton($ejemplar, "borrado_ejemplar", [0=>"NO", 1=> "SI"], " ", []).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "borrado_ejemplar", ["class" => "error"]).PHP_EOL;

    echo "<br>";

    //ESTADO EJEMPLAR
    echo CHTML::modeloLabel($ejemplar, "estado_ejemplar", []).PHP_EOL;
    echo CHTML::modeloListaRadioButton($ejemplar, "estado_ejemplar", [0=>"DISPONIBLE", 1=> "RESERVADO"], " ", []).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "estado_ejemplar", ["class" => "error"]).PHP_EOL;

    echo "<br>";

    //DIV DE PELICULAS
    echo CHTML::dibujaEtiqueta("div", ["id" => "divAnadePeliculaE", "style" => $divPelicula["style"]], null, false).PHP_EOL;
        
        echo CHTML::modeloLabel($pelicula, "duracion", ["readonly" => true]).PHP_EOL;
        echo CHTML::campoNumber("horaP", $datosForm["horaP"], ["min" => 0, "max" => 23, "placeholder" => "HH", "size" => 2, "name" => "horaP"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], ":", true).PHP_EOL;
        echo CHTML::campoNumber("minutoP", $datosForm["minutoP"], ["min" => 0, "max" => 59, "placeholder" => "MM", "size" => 2, "name" => "minutoP"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], ":", true).PHP_EOL;
        echo CHTML::campoNumber("segundoP", $datosForm["segundoP"], ["min" => 0, "max" => 59, "placeholder" => "SS", "size" => 2, "name" => "segundoP"]).PHP_EOL;        echo CHTML::modeloError($pelicula, "duracion", ["class" => "error"]).PHP_EOL;

        echo "<br>";

        echo CHTML::modeloLabel($pelicula, "pais", ["readonly" => true]).PHP_EOL;
        echo CHTML::modeloListaDropDown($pelicula, "pais",$arrayPaisesPeliculas, []).PHP_EOL;
        echo CHTML::modeloError($pelicula, "pais", ["class" => "error"]).PHP_EOL;

        echo "<br>";

        
        echo CHTML::modeloLabel($pelicula, "calificacion_edad", ["readonly" => true]).PHP_EOL;
        echo CHTML::modeloListaDropDown($pelicula, "calificacion_edad", $arrayEdades).PHP_EOL;
        echo CHTML::modeloError($pelicula, "calificacion_edad", ["class" => "error"]).PHP_EOL;

        echo "<br>";
        
    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


    //DIV DE AUDIO
    echo CHTML::dibujaEtiqueta("div", ["id" => "divAnadeAudioE", "style" => $divAudio["style"]], null, false).PHP_EOL;
        echo CHTML::modeloLabel($audio, "duracion", ["readonly" => true]).PHP_EOL;
        echo CHTML::campoNumber("horaA", $datosForm["horaA"], ["min" => 0, "max" => 23, "placeholder" => "HH", "size" => 2, "name" => "horaA"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], ":", true).PHP_EOL;
        echo CHTML::campoNumber("minutoA", $datosForm["minutoA"], ["min" => 0, "max" => 59, "placeholder" => "MM", "size" => 2, "name" => "minutoA"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], ":", true).PHP_EOL;
        echo CHTML::campoNumber("segundoA", $datosForm["segundoA"], ["min" => 0, "max" => 59, "placeholder" => "SS", "size" => 2, "name" => "segundoA"]).PHP_EOL;        echo CHTML::modeloError($audio, "duracion", ["class" => "error"]).PHP_EOL;

        echo "<br>";
        
    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


    //DIV DE LIBRO
    echo CHTML::dibujaEtiqueta("div", ["id" => "divAnadeLibroE", "style" => $divLibro["style"]], null, false).PHP_EOL;
        echo CHTML::modeloLabel($libro, "isbn", ["readonly" => true]).PHP_EOL;
        echo CHTML::modeloText($libro, "isbn", []).PHP_EOL;
        echo CHTML::modeloError($libro, "isbn", ["class" => "error"]).PHP_EOL;

        echo "<br>";
        
    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;




    echo CHTML::campoBotonSubmit("Añadir Ejemplar", []).PHP_EOL;
    echo CHTML::campoBotonReset("Borrar datos", ["id" => "resetCampos"]).PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;



echo CHTML::finalizarForm().PHP_EOL;









?>
