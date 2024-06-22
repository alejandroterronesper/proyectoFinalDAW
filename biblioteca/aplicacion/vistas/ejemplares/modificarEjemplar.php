<?php
$this->textoHead = CHTML::scriptFichero("/js/main.js", ["defer" => "defer"]).PHP_EOL;


$titulo = CHTML::dibujaEtiqueta("span",["style" => "font-style:italic"], $ejemplar->titulo);

echo CHTML::dibujaEtiqueta("div", ["class" => "verObra", "style" => "margin-left: 20%;"], null, false).PHP_EOL;


    //PONER BOTONES ARRIBA
    echo CHTML::dibujaEtiqueta("h2", [], "Modificar ejemplar: " .$titulo, true).PHP_EOL;

    echo CHTML::iniciarForm("", "post", ["enctype" =>"multipart/form-data"]).PHP_EOL;

    //TITULO
    echo CHTML::modeloLabel($ejemplar, "titulo", []).PHP_EOL;
    echo CHTML::modeloText($ejemplar, "titulo", ["readonly" => true]).PHP_EOL;
    
    //AUTOR
    echo CHTML::modeloLabel($ejemplar, "autor", []).PHP_EOL;
    echo CHTML::modeloText($ejemplar, "autor", ["readonly" => true]).PHP_EOL;

    //DESCRIPCION GENERO
    echo CHTML::modeloLabel($ejemplar, "descripcion_genero", []).PHP_EOL;
    echo CHTML::modeloText($ejemplar, "descripcion_genero", ["readonly" => true]).PHP_EOL;

    //DISTRIBUIDORA
    echo CHTML::modeloLabel($ejemplar, "distribuidora", []).PHP_EOL;
    echo CHTML::modeloText($ejemplar, "distribuidora", ["readonly" => true]).PHP_EOL;

    //FECHA LANZAMIENTO
    echo CHTML::modeloLabel($ejemplar, "fecha_lanzamiento", []).PHP_EOL;
    echo CHTML::modeloDate($ejemplar, "fecha_lanzamiento", ["readonly" => true]).PHP_EOL;

    //A partir de aquí es editable!

    //FORMATO EJEMPLAR -> combobox
    echo CHTML::modeloLabel($ejemplar, "formato_ejemplar", []).PHP_EOL;
    echo CHTML::modeloListaDropDown($ejemplar, "cod_formato_ejemplar", $arrayFormatos, ["id" => "comboFormatosEjemplar", "disabled" => true], ).PHP_EOL;
    echo CHTML::modeloText($ejemplar, "cod_categoria_obra", ["hidden" => true, "id" => "valueCodTipoObra"]).PHP_EOL;

    //FORMATO MEDIO
    echo CHTML::modeloLabel($ejemplar, "descripcion_formato_medio", []).PHP_EOL;
    echo CHTML::modeloListaDropDown($ejemplar, "codigo_formato_medio", $arrayFormatosMedios, ["id" => "comboFormatoMedio"]).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "codigo_formato_medio", ["class" => "error"]).PHP_EOL;

    //FECHA REGISTRO
    echo CHTML::modeloLabel($ejemplar, "fecha_registro", []).PHP_EOL;
    echo CHTML::modeloDate($ejemplar, "fecha_registro", ["placeholder" => "dd/mm/aaaa"]).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "fecha_registro", ["class" => "error"]).PHP_EOL;


    //UBICACION EJEMPLAR

    echo CHTML::modeloLabel($ejemplar, "ubicacion_ejemplar", []).PHP_EOL;

    //Se comprueba si es fisico o digital, si es fisico un campo de texto
    //Si es digital un input file
    if (intval($ejemplar->cod_formato_ejemplar) === 1){
        echo CHTML::modeloText($ejemplar, "ubicacion_ejemplar", []).PHP_EOL;

    }

    if (intval($ejemplar->cod_formato_ejemplar) === 2){
        echo CHTML::modeloText($ejemplar, "ubicacion_ejemplar", []).PHP_EOL;

        //Ponemos input para subir
        echo CHTML::campoHidden("MAX_FILE_SIZE", 1000000000000, []).PHP_EOL;

        //Se comprueba si es libro, audio o pelicula
        if (intval($ejemplar->cod_categoria_obra) === 1){
                echo CHTML::modeloFile($ejemplar, "ubicacion_ejemplar",["accept" => "text/pdf, text/epub", "id" => "inputFileMod", "style" => "display:inline"]).PHP_EOL;

        }


        if (intval($ejemplar->cod_categoria_obra) === 2){
            echo CHTML::modeloFile($ejemplar, "ubicacion_ejemplar",["accept" => "video/*", "id" => "inputFileMod", "style" => "display:inline"]).PHP_EOL;

        }

        if (intval($ejemplar->cod_categoria_obra) === 3){
            echo CHTML::modeloFile($ejemplar, "ubicacion_ejemplar",["accept" => "audio/*", "id" => "inputFileMod","style" => "display:inline"]).PHP_EOL;

        }


    }
    echo CHTML::modeloError($ejemplar, "ubicacion_ejemplar", ["class" => "error"]).PHP_EOL;
    if (isset($errorFichero)){
        echo CHTML::dibujaEtiqueta("div", ["class"=> "error"], null, false).PHP_EOL;

            echo CHTML::dibujaEtiqueta("span", [], $errorFichero, true).PHP_EOL;

        echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
    }

        //Se comprueba si es pelicula, audio o libro
    if (intval($ejemplar->cod_categoria_obra) === 1){ //libro
        echo CHTML::modeloLabel($datosAdicionalesEjemplar, "isbn", ["readonly" => true]).PHP_EOL;
        echo CHTML::modeloText($datosAdicionalesEjemplar, "isbn", []).PHP_EOL;
        echo CHTML::modeloError($datosAdicionalesEjemplar, "isbn", ["class" => "error"]).PHP_EOL;

        echo "<br>";
    }



    if (intval($ejemplar->cod_categoria_obra) === 2){ //pelicula
        
        echo CHTML::modeloLabel($datosAdicionalesEjemplar, "duracion", ["readonly" => true]).PHP_EOL;
        echo "<br>";      
        echo CHTML::campoNumber("horaP", $datosForm["horaP"], ["min" => 0, "max" => 23, "placeholder" => "HH", "size" => 2, "name" => "horaP"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], ":", true).PHP_EOL;
        echo CHTML::campoNumber("minutoP", $datosForm["minutoP"], ["min" => 0, "max" => 59, "placeholder" => "MM", "size" => 2, "name" => "minutoP"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], ":", true).PHP_EOL;
        echo CHTML::campoNumber("segundoP", $datosForm["segundoP"], ["min" => 0, "max" => 59, "placeholder" => "SS", "size" => 2, "name" => "segundoP"]).PHP_EOL;
        echo CHTML::modeloError($datosAdicionalesEjemplar, "duracion", ["class" => "error"]).PHP_EOL;
        echo "<br>".PHP_EOL;
        echo "<br>".PHP_EOL;

        echo CHTML::modeloLabel($datosAdicionalesEjemplar, "pais", ["readonly" => true]).PHP_EOL;
        echo CHTML::modeloListaDropDown($datosAdicionalesEjemplar, "pais", $arrayPaises, []).PHP_EOL;
        echo CHTML::modeloError($datosAdicionalesEjemplar, "pais", ["class" => "error"]).PHP_EOL;
        echo "<br>";


        echo CHTML::modeloLabel($datosAdicionalesEjemplar, "calificacion_edad", ["readonly" => true]).PHP_EOL;
        echo CHTML::modeloListaDropDown($datosAdicionalesEjemplar, "calificacion_edad", $arrayEdades).PHP_EOL;
        echo CHTML::modeloError($datosAdicionalesEjemplar, "calificacion_edad", ["class" => "error"]).PHP_EOL;
        echo "<br>";

    }


    if (intval($ejemplar->cod_categoria_obra) === 3){ //audio
        echo CHTML::modeloLabel($datosAdicionalesEjemplar, "duracion", ["readonly" => true]).PHP_EOL;
        echo "<br>";      
        echo CHTML::campoNumber("horaA", $datosForm["horaA"], ["min" => 0, "max" => 23, "placeholder" => "HH", "size" => 2, "name" => "horaA"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], ":", true).PHP_EOL;
        echo CHTML::campoNumber("minutoA", $datosForm["minutoA"], ["min" => 0, "max" => 59, "placeholder" => "MM", "size" => 2, "name" => "minutoA"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], ":", true).PHP_EOL;
        echo CHTML::campoNumber("segundoA", $datosForm["segundoA"], ["min" => 0, "max" => 59, "placeholder" => "SS", "size" => 2, "name" => "segundoA"]).PHP_EOL;
        echo CHTML::modeloError($datosAdicionalesEjemplar, "duracion", ["class" => "error"]).PHP_EOL;

        echo "<br>";      
        echo "<br>".PHP_EOL;

    }


    //BORRADO EJEMPLAR
    echo CHTML::modeloLabel($ejemplar, "borrado_ejemplar", []).PHP_EOL;
    echo CHTML::modeloListaRadioButton($ejemplar, "borrado_ejemplar", [0=>"NO", 1=> "SI"], " ", []).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "borrado_ejemplar", ["class" => "error"]).PHP_EOL;
    echo "<br>";
    echo "<br>";

    //ESTADO EJEMPLAR
    echo CHTML::modeloLabel($ejemplar, "estado_ejemplar", []).PHP_EOL;
    echo CHTML::modeloListaRadioButton($ejemplar, "estado_ejemplar", [0=>"DISPONIBLE", 1=> "RESERVADO"], " ", []).PHP_EOL;
    echo CHTML::modeloError($ejemplar, "estado_ejemplar", ["class" => "error"]).PHP_EOL;


    echo "<br>".PHP_EOL;
    echo "<br>".PHP_EOL;
    echo CHTML::campoBotonSubmit("Modificar datos", ["class" => "boton"]).PHP_EOL;
    echo CHTML::campoBotonReset("Restaurar valores",["class" => "boton"]).PHP_EOL;


    echo CHTML::finalizarForm().PHP_EOL;


    //Operaciones
    echo "<br>".PHP_EOL;
    echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

            echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

            echo CHTML::botonHtml(CHTML::link("Volver atrás", ["ejemplares", "indexEjemplares"]), ["class"=>"boton"]).PHP_EOL;
            echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

            //En caso de querer ver mas datos de la obra
            echo CHTML::botonHtml(CHTML::link("Ver ejemplar", ["ejemplares", "verEjemplar/id=".$ejemplar->cod_ejemplar]), ["class"=>"boton"]).PHP_EOL;

            if ($ejemplar->borrado_ejemplar === 0){
                echo CHTML::botonHtml(CHTML::link("Borrar ejemplar", ["ejemplares", "borrarEjemplar/id=".$ejemplar->cod_ejemplar]), ["class"=>"boton"]).PHP_EOL;
            }


        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;



?>