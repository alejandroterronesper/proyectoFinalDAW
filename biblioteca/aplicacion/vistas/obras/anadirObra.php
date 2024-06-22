<?php
$this->textoHead = CHTML::scriptFichero("/js/main.js", ["defer" => "defer"]).PHP_EOL;

$titulo = CHTML::dibujaEtiqueta("span",["style" => "font-style:italic"], $obra->titulo);

echo CHTML::dibujaEtiqueta("div", ["class" => "contenedorPrincipalVerObra"], null, false).PHP_EOL;

echo CHTML::dibujaEtiqueta("div", ["class" => "verObra"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("h2", [], "Añadir obra: " .$titulo, true).PHP_EOL;

        echo CHTML::iniciarForm("", "POST", ["enctype" =>"multipart/form-data"]).PHP_EOL;

            //TITULO
            echo CHTML::modeloLabel($obra, "titulo", []).PHP_EOL;
            echo CHTML::modeloText($obra, "titulo", ["size" => 40, "required" => true]).PHP_EOL;
            echo CHTML::modeloError($obra, "titulo", ["class" => "error"]).PHP_EOL;
            
            //AUTOR
            echo CHTML::modeloLabel($obra, "autor", []).PHP_EOL;
            echo CHTML::modeloText($obra, "autor", ["size" => 40,  "required" => true]).PHP_EOL;
            echo CHTML::modeloError($obra, "autor", ["class" => "error"]).PHP_EOL;
            
            //DISTRIBUIDORA
            echo CHTML::modeloLabel($obra, "distribuidora", []).PHP_EOL;
            echo CHTML::modeloText($obra, "distribuidora", ["size" => 40,  "required" => true]).PHP_EOL;
            echo CHTML::modeloError($obra, "distribuidora", ["class" => "error"]).PHP_EOL;

            //FECHA LANZAMIENTO
            echo CHTML::modeloLabel($obra, "fecha_lanzamiento", []).PHP_EOL;
            echo CHTML::modeloText($obra, "fecha_lanzamiento", ["size" => 40, "placeholder" => "dd/mm/yyyy",  "required" => true]).PHP_EOL;
            echo CHTML::modeloError($obra, "fecha_lanzamiento", ["class" => "error"]).PHP_EOL;

            //CATEGORIA OBRA
            echo CHTML::modeloLabel($obra, "cod_categoria_obra", []).PHP_EOL;
            echo CHTML::modeloListaDropDown($obra, "cod_categoria_obra", $categoriasObra, ["id" => "comboCategoriaObra",  "required" => true]).PHP_EOL;
            echo CHTML::modeloError($obra, "cod_categoria_obra", ["class" => "error"]);

            //GENERO DE LA ORBA
            echo CHTML::modeloLabel($obra, "cod_genero", []).PHP_EOL;
            echo CHTML::modeloListaDropDown($obra, "cod_genero", $generosObras, ["id" => "comboGenerosObras",  "required" => true]).PHP_EOL;
            echo CHTML::modeloError($obra, "cod_genero", ["class" => "error"]);

            //DESCRIPCION
            echo CHTML::modeloLabel($obra, "descripcion", []).PHP_EOL;
            echo CHTML::modeloTextArea($obra, "descripcion", ["size" => 40, "rows" => 10, "cols" => 50,  "required" => true]).PHP_EOL;
            echo CHTML::modeloError($obra, "descripcion", ["class" => "error"]).PHP_EOL;

            //FOTO
            echo CHTML::modeloLabel($obra, "foto", []).PHP_EOL;
            echo CHTML::campoHidden("MAX_FILE_SIZE", 100000000, []).PHP_EOL;
            echo CHTML::modeloFile($obra, "foto",["accept" => "image/*"]).PHP_EOL;
            echo CHTML::modeloError($obra, "foto", ["class" => "error"]).PHP_EOL;

            
            if (isset($errorFoto)){ //Error en caso de no subirse la foto
                echo CHTML::dibujaEtiqueta("div", ["class"=> "error"], null, false).PHP_EOL;
                    echo CHTML::dibujaEtiqueta("span", [],$errorFoto, true).PHP_EOL;
                echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
            }


            //BORRADO
            echo CHTML::modeloLabel($obra, "borrado", []).PHP_EOL;
            echo CHTML::modeloListaRadioButton($obra, "borrado", [0=>"NO", 1=> "SI"], " ", ["required" => true]).PHP_EOL;
            echo CHTML::modeloError($obra, "borrado", ["class" => "error"]).PHP_EOL;
            echo "<br>".PHP_EOL;


            echo CHTML::campoBotonSubmit("Crear obra", ["class" => "boton"]).PHP_EOL;
            echo CHTML::campoBotonReset("Resetear campos", ["class" => "boton"]).PHP_EOL;

        echo CHTML::finalizarForm().PHP_EOL;


        //Operaciones

        echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

            echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

                    echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

                    echo CHTML::botonHtml(CHTML::link("Volver atrás", ["obras", "indexObras"]), ["class"=>"boton"]).PHP_EOL;
                    echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

            echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
        echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

    echo CHTML::imagen("../../imagenes/obras/" .$obra->foto , "Obra: {$obra->titulo}", []).PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;




?>