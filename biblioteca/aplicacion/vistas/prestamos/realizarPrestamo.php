<?php




echo CHTML::dibujaEtiqueta("div", ["class" => "contenedorPrincipalVerObra"], null, false).PHP_EOL;

   echo CHTML::dibujaEtiqueta("div", ["class" => "verObra"], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("label", [], "Información del ejemplar").PHP_EOL;

        echo "<br>".PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], "Título: ", true).PHP_EOL;
        echo CHTML::campoText("titulo", $ejemplar->titulo, []).PHP_EOL;

        echo CHTML::dibujaEtiqueta("label", [], "Autor: ", true).PHP_EOL;
        echo CHTML::campoText("autor", $ejemplar->autor, []).PHP_EOL;

        echo CHTML::dibujaEtiqueta("label", [], "Género: ", true).PHP_EOL;
        echo CHTML::campoText("genero", $ejemplar->descripcion_genero, []).PHP_EOL;

        echo "<br>".PHP_EOL;
        echo CHTML::dibujaEtiqueta("label", [], "Información del préstamo").PHP_EOL;
              
        echo CHTML::dibujaEtiqueta("label", [], "Fecha de inicio: " , true).PHP_EOL;
        echo CHTML::campoText("fechaInicio",$prestamo->fecha_inicio, []).PHP_EOL;
        
        echo CHTML::dibujaEtiqueta("label", [], "Fecha de devolución: " , true).PHP_EOL;
        echo CHTML::campoText("fechaFin", $prestamo->fecha_fin, []).PHP_EOL;


        echo CHTML::iniciarForm("", "post").PHP_EOL;

        echo CHTML::dibujaEtiqueta("label", [], "¿Quieres reservar " . $ejemplar->titulo. "?", true).PHP_EOL;
        echo CHTML::campoListaRadioButton("elegirReserva", $respuesta, [0 => "No " ,1 => "Sí ", ], "").PHP_EOL;

        if (isset($error)){
            if ($error){
                echo CHTML::dibujaEtiqueta("div", ["class" => "error"], null, false).PHP_EOL;

                    echo CHTML::dibujaEtiqueta("span", [], "No puedes reservar más ejemplares, has superado el nº de préstamos: 3", true).PHP_EOL;
                    
                echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
            }
        }

        if (isset($retraso)){
            if ($retraso){
                echo CHTML::dibujaEtiqueta("div", ["class" => "error"], null, false).PHP_EOL;

                    echo CHTML::dibujaEtiqueta("span", [], "Tienes préstamos atrasados, no puedes reservar más préstamos hasta que sean devueltos", true).PHP_EOL;
                    
                echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
            }
        }

        if (isset($fisico)){
            if ($fisico){
                echo CHTML::dibujaEtiqueta("div", ["class" => "error"], null, false).PHP_EOL;

                    echo CHTML::dibujaEtiqueta("span", [], "Para reservar un ejemplar físico, deberá acudir de manera presencial a la biblioteca
                            y ponerse en contacto con el bibliotecario", true).PHP_EOL;
                    
                echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
            }
        }




        echo CHTML::campoBotonSubmit("Aceptar", ["name" => "realizarReserva", "class" => "boton"]).PHP_EOL;
        echo "<br>".PHP_EOL;
        echo "<br>".PHP_EOL;



        echo CHTML::dibujaEtiqueta("label", [], "Se enviará la información de tu reserva al correo: ". $email).PHP_EOL;


   echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;





?>