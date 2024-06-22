<?php
$this->textoHead = CPager::requisitos();
$this->textoHead .= "    ". CCaja::requisitos().PHP_EOL;
$this->textoHead .= "   ". CHTML::scriptFichero("/js/main.js", ["defer" => "defer"]).PHP_EOL;



//TABLA
$tabla = new CGrid($cabecera, $filas, ["class" => "tabla1"]);

//paginador
$varPagina = new CPager($paginador, []);

//CAJA PARA FILTRAR BÚSQUEDAS
$objCaja = new CCaja("Filtrar búsqueda", "", []);




//OPERACIONES
echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones", "style" => "margin-bottom: 2%; width: 96%; margin-left: 2%"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

        echo CHTML::dibujaEtiqueta("button", ["id" => "selectCheckPrestamo", "class" => "boton"], "Seleccionar todos", true ).PHP_EOL;
        echo CHTML::dibujaEtiqueta("button", ["id"=> "enviarCorreoPrestamo", "class" => "boton"], "Enviar notificación", true).PHP_EOL;
        echo CHTML::botonHtml(CHTML::link("Añadir préstamo", ["prestamos", "anadirPrestamo"]), ["class"=>"boton"]);


    echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

//FILTRO
echo $objCaja->dibujaApertura().PHP_EOL;

    echo CHTML::iniciarForm("","POST", ["class" => "formulario"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

        //DEVUELTO
        echo CHTML::dibujaEtiqueta("label", ["for" => "devuelto"], "Préstamos devueltos: ", true).PHP_EOL;
        echo CHTML::campoListaDropDown("devuelto", $datos["devuelto"], $arrayDevuelto, ["class" => "seleccionar"] ).PHP_EOL;
        echo "<br>".PHP_EOL;

        
        echo CHTML::campoBotonSubmit("Filtrar", ["class" => "boton", "name" => "filtradoPrestamos"]).PHP_EOL;
        echo CHTML::campoBotonSubmit("Limpiar filtrado", ["class" => "boton", "name" => "limpiarFiltradoPrestamos"]).PHP_EOL;


        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
   

    echo CHTML::finalizarForm().PHP_EOL;

    
echo $objCaja->dibujaFin().PHP_EOL;

echo $varPagina->dibujate().PHP_EOL;
echo CHTML::dibujaEtiqueta("div", ["id" => "divTablaIndexPrestamos", "class" => "divTablaIndexPrestamos"], null, false).PHP_EOL;
    echo $tabla->dibujate().PHP_EOL;
echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
echo $varPagina->dibujate().PHP_EOL;

echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;






?>