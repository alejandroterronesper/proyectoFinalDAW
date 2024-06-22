<?php

$titulo = CHTML::dibujaEtiqueta("span",["style" => "font-style:italic"], $ejemplar->titulo);

echo CHTML::dibujaEtiqueta("div", ["class" => "verObra", "style" => "margin-left: 20%;"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("h2", [], "Ver ejemplar: " .$titulo, true).PHP_EOL;

    echo CHTML::iniciarForm("", "post", []).PHP_EOL;

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


        //FORMATO EJEMPLAR
        echo CHTML::modeloLabel($ejemplar, "formato_ejemplar", []).PHP_EOL;
        echo CHTML::modeloText($ejemplar, "formato_ejemplar", ["readonly" => true]).PHP_EOL;

        //FORMATO MEDIO
        echo CHTML::modeloLabel($ejemplar, "descripcion_formato_medio", []).PHP_EOL;
        echo CHTML::modeloText($ejemplar, "descripcion_formato_medio", ["readonly" => true]).PHP_EOL;

        //FECHA REGISTRO
        echo CHTML::modeloLabel($ejemplar, "fecha_registro", []).PHP_EOL;
        echo CHTML::modeloDate($ejemplar, "fecha_registro", ["readonly" => true]).PHP_EOL;

        //UBICACION EJEMPLAR
        //Si es digital poner un enlace y que salga una ventana emergente
        echo CHTML::modeloLabel($ejemplar, "ubicacion_ejemplar", []).PHP_EOL;
        echo CHTML::modeloText($ejemplar, "ubicacion_ejemplar", ["readonly" => true]).PHP_EOL;




        //Se comprueba si es pelicula, audio o libro
        if ($ejemplar->cod_categoria_obra === 1){ //libro
            echo CHTML::modeloLabel($ejemplar, "isbn_libro", ["readonly" => true]).PHP_EOL;
            echo CHTML::modeloText($ejemplar, "isbn_libro", ["readonly" => true]).PHP_EOL;
        }



        if ($ejemplar->cod_categoria_obra === 2){ //pelicula
            
            echo CHTML::modeloLabel($ejemplar, "pelicula_duracion", ["readonly" => true]).PHP_EOL;
            echo CHTML::modeloText($ejemplar, "pelicula_duracion", ["readonly" => true]).PHP_EOL;

            echo CHTML::modeloLabel($ejemplar, "pelicula_pais", ["readonly" => true]).PHP_EOL;
            echo CHTML::modeloText($ejemplar, "pelicula_pais", ["readonly" => true]).PHP_EOL;


            echo CHTML::modeloLabel($ejemplar, "pelicula_edad", ["readonly" => true]).PHP_EOL;
            echo CHTML::modeloText($ejemplar, "pelicula_edad", ["readonly" => true]).PHP_EOL;

        }


        if ($ejemplar->cod_categoria_obra === 3){ //audio
            echo CHTML::modeloLabel($ejemplar, "audio_duracion", ["readonly" => true]).PHP_EOL;
            echo CHTML::modeloText($ejemplar, "audio_duracion", ["readonly" => true]).PHP_EOL;
        }

        
        //BORRADO EJEMPLAR
        echo CHTML::modeloLabel($ejemplar, "borrado_ejemplar", []).PHP_EOL;
        echo CHTML::modeloListaRadioButton($ejemplar, "borrado_ejemplar", [0=>"NO", 1=> "SI"], " ", ["disabled" => true]).PHP_EOL;
        echo "<br>".PHP_EOL;      
        echo "<br>".PHP_EOL;


        //ESTADO EJEMPLAR
        echo CHTML::modeloLabel($ejemplar, "estado_ejemplar", []).PHP_EOL;
        echo CHTML::modeloListaRadioButton($ejemplar, "estado_ejemplar", [0=>"DISPONIBLE", 1=> "RESERVADO"], " ", ["disabled" => true]).PHP_EOL;



    echo CHTML::finalizarForm().PHP_EOL;



    //Operaciones
    echo "<br>".PHP_EOL;
    echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

            echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

            echo CHTML::botonHtml(CHTML::link("Volver atrás", ["ejemplares", "indexEjemplares"]), ["class"=>"boton"]).PHP_EOL;
            echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

            //En caso de querer ver mas datos de la obra

            echo CHTML::botonHtml(CHTML::link("Modificar ejemplar", ["ejemplares", "modificarEjemplar/id=".$ejemplar->cod_ejemplar]), ["class"=>"boton"]).PHP_EOL;


            if ($ejemplar->borrado_ejemplar === 0){
                echo CHTML::botonHtml(CHTML::link("Borrar ejemplar", ["ejemplares", "borrarEjemplar/id=".$ejemplar->cod_ejemplar]), ["class"=>"boton"]).PHP_EOL;
            }


        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
    

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;



?>