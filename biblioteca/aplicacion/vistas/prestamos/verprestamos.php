<?php
$this->textoHead = CHTML::scriptFichero("/js/main.js", ["defer" => "defer"]).PHP_EOL;

echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones", "style" => "margin-bottom: 1.5%;"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;


        echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;
        echo CHTML::botonHtml(CHTML::link("Ver historial de préstamos ", Sistema::app()->generaURL(["prestamos","historialPrestamos"])), ["class" => "boton"]).PHP_EOL;

    
    
    echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;



if (isset($ejemplaresUsuarioPrestamos)){

    if (count($ejemplaresUsuarioPrestamos) > 0){

        echo CHTML::dibujaEtiqueta("span", ["style" => "color: red;"], "Nº de préstamos disponibles: ". count($ejemplaresUsuarioPrestamos)).PHP_EOL;

        // //Aquí los prestamos totales tanto los actuales como los ya devueltos
        // echo CHTML::link("Ver historial de préstamos",
        
    

        //Aquí se muestran los préstamos actuales y los que se han pasado
        echo CHTML::dibujaEtiqueta("div", ["class" => "contenedor"], null, false).PHP_EOL;
    
            foreach ($ejemplaresUsuarioPrestamos as $clave => $valor){



                echo $this->dibujaVistaParcial("divPrestamo", ["prestamo" => $valor, "codUsuario" => $codUsuario], true);
            }

        echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


    }
    else{
        echo CHTML::dibujaEtiqueta("span", ["style" => "color: red;"], "El usuario actual no tiene ningún préstamo disponible").PHP_EOL;

    }

}
else{

    echo CHTML::dibujaEtiqueta("span", ["style" => "color: red;"], "El usuario actual no tiene ningún préstamo disponible").PHP_EOL;
}


















?>