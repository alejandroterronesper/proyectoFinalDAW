<?php

$titulo = CHTML::dibujaEtiqueta("span",["style" => "font-style:italic"], $obra->titulo);

echo CHTML::dibujaEtiqueta("div", ["class" => "contenedorPrincipalVerObra"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("div", ["class" => "verObra"], null, false).PHP_EOL;

        //DATOS
        echo CHTML::dibujaEtiqueta("h2", [], "Ver obra: " .$titulo, true).PHP_EOL;

        echo CHTML::modeloLabel($obra, "titulo", []).PHP_EOL;
        echo CHTML::modeloText($obra, "titulo", ["size" => 40, "readonly" => true]).PHP_EOL;
        echo CHTML::modeloLabel($obra, "autor", []).PHP_EOL;
        echo CHTML::modeloText($obra, "autor", ["size" => 40, "readonly" => true]).PHP_EOL;
        echo CHTML::modeloLabel($obra, "distribuidora", []).PHP_EOL;
        echo CHTML::modeloText($obra, "distribuidora", ["size" => 40, "readonly" => true]).PHP_EOL;
        echo CHTML::modeloLabel($obra, "fecha_lanzamiento", []).PHP_EOL;
        echo CHTML::modeloText($obra, "fecha_lanzamiento", ["size" => 40, "readonly" => true]).PHP_EOL;

        echo CHTML::dibujaEtiqueta("label", ["for" => "categoriaObra"], "Categoría:", true).PHP_EOL;
        echo CHTML::campoText("categoriaObra",  $categorias[$obra->cod_categoria_obra], ["readonly" => true]).PHP_EOL;

        $valor =  GenerosObras::devuelveGenerosObras($obra->cod_genero, null)."";
        echo CHTML::dibujaEtiqueta("label", ["for" => "generoObra"], "Género:", true).PHP_EOL;
        echo CHTML::campoText("generoObra", $valor , ["readonly" => true]).PHP_EOL;


        echo CHTML::modeloLabel($obra, "descripcion", []).PHP_EOL;
        echo CHTML::modeloTextArea($obra, "descripcion", ["size" => 40,  "rows" => 10, "cols" => 50,"readonly" => true]).PHP_EOL;
        //cod_genero
        echo CHTML::modeloLabel($obra, "borrado", []).PHP_EOL;
        echo CHTML::modeloListaRadioButton($obra, "borrado", [0=>"NO", 1=> "SI"], " ", ["disabled" => true]).PHP_EOL;


     
        //operaciones
        echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

            echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

                echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

                echo CHTML::botonHtml(CHTML::link("Volver atrás", ["obras", "indexObras"]), ["class"=>"boton"]).PHP_EOL;
                echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;
                echo CHTML::botonHtml(CHTML::link("Modificar obra", ["obras", "modificarObra/id=".$obra->cod_obra]), ["class"=>"boton"]).PHP_EOL;


                if ($obra->borrado === 0){
                    echo CHTML::botonHtml(CHTML::link("Borrar obra", ["obras", "borrarObra/id=".$obra->cod_obra]), ["class"=>"boton"]).PHP_EOL;
                }


            echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

        echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

        echo CHTML::imagen("../../imagenes/obras/" .$obra->foto , "Obra: {$obra->titulo}", []).PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

?>