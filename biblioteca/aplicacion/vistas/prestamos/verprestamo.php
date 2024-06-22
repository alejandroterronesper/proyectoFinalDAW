<?php




    //Aqui vamos a poner opciones para devolver etc 
    if ($fechaPasada === true){ //No se pasa de fecha


        
    }
    else{ //Se pasa de fecha
    }



    echo CHTML::dibujaEtiqueta("span", ["style" => "color:red;"], "Días disponibles: $diasQueQuedan",true).PHP_EOL;

    //Aquí pregunto que tipo de medio es
    //Y ya le muestro el PDF, Video o audio

    //Se pregunta si es digital o no
    if ($ejemplar->cod_formato_ejemplar === 1){ //FÍSICO
        echo "<br>".PHP_EOL;
        echo "<br>".PHP_EOL;

        if ($fechaPasada === false){
            echo CHTML::dibujaEtiqueta("span", ["style" => "color:red;"], "El plazo de devolución ha caducado!",true).PHP_EOL;
            echo "<br>".PHP_EOL;
            echo "<br>".PHP_EOL;

        }

        echo CHTML::dibujaEtiqueta("span", [], null, false).PHP_EOL;

            echo "Para devolver el préstamo físico deberá acudir presencialmente a la biblioteca".PHP_EOL;
        echo CHTML::dibujaEtiquetaCierre("span").PHP_EOL;

    }


    if ($ejemplar->cod_formato_ejemplar === 2){ //DÍGITAL

    if ($fechaPasada === true) {
        echo CHTML::dibujaEtiqueta("div", ["class" => "verPrestamoUserDigital"], null, false) . PHP_EOL;

        //Ahora miro si es libro, pelicula o audio

        if ($ejemplar->cod_categoria_obra === 1) { //LIBRO   

            //buscar algo de pdfs
            echo CHTML::dibujaEtiqueta(
                "object",
                [
                    "data" =>  $ejemplar->ubicacion_ejemplar,
                    "type" => "application/pdf", "width" => "80%", "height" => "600px"
                ],
                null,
                false
            ) . PHP_EOL;

            echo CHTML::dibujaEtiquetaCierre("object") . PHP_EOL;
        }


        if ($ejemplar->cod_categoria_obra === 2) { //PELICULA

            echo CHTML::dibujaEtiqueta("video", [
                "width" => "640", "height" => "360",
                "controls" => "true"
            ], null, false) . PHP_EOL;
            echo CHTML::dibujaEtiqueta("source", ["src" => $ejemplar->ubicacion_ejemplar], null, true) . PHP_EOL;
            echo CHTML::dibujaEtiquetaCierre("video") . PHP_EOL;
        }


        if ($ejemplar->cod_categoria_obra === 3) { //AUDIO

            echo CHTML::dibujaEtiqueta("audio", ["controls" => "true"], null, false) . PHP_EOL;
            echo CHTML::dibujaEtiqueta("source", ["src" => $ejemplar->ubicacion_ejemplar], null, true) . PHP_EOL;
            echo CHTML::dibujaEtiquetaCierre("audio") . PHP_EOL;
        }

        echo CHTML::dibujaEtiquetaCierre("div") . PHP_EOL;
        echo "<br>" . PHP_EOL;
        echo "<br>" . PHP_EOL;
    }
    else{
        echo CHTML::dibujaEtiqueta("span", ["style" => "color:red;"], "El plazo de devolución ha caducado!",true).PHP_EOL;
        echo "<br>".PHP_EOL;
        echo "<br>".PHP_EOL;
    }



    //Formulario
    echo CHTML::iniciarForm("", "post").PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], "¿Desea devolver  ". $ejemplar->titulo . " ?");
        echo CHTML::campoListaRadioButton("devolver", $devolucion, [0 => "NO " ,1 => "SÍ", ], "  ").PHP_EOL;
        echo "<br>".PHP_EOL;
        echo "<br>".PHP_EOL;
        echo CHTML::campoBotonSubmit("Aceptar",["name" => "devolverPrestamo", "class" => "boton"] ).PHP_EOL;
        
    echo CHTML::finalizarForm().PHP_EOL;

    }






//OPERACIONES DISPONIBLES


echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;

echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

        echo CHTML::botonHtml(CHTML::link("Volver atrás", ["prestamos", "verPrestamos/?id=$codUser"]), ["class"=>"boton"]).PHP_EOL;
        echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

      
    echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;




?>