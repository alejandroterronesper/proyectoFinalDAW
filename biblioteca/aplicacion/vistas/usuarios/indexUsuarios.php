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
        echo CHTML::botonHtml(CHTML::link("Añadir usuario", ["usuarios", "anadirUsuario", "class" => "boton"]), ["class"=>"boton"]);

    echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


//FILTRO
echo $objCaja->dibujaApertura().PHP_EOL;

    echo CHTML::iniciarForm("","POST", ["class" => "formulario"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

        //nick
        echo CHTML::dibujaEtiqueta("label", ["for" => "nick"], "Nick: ", true).PHP_EOL;
        echo CHTML::campoText("nick", $datos["nick"], ["class" => "entrada"]).PHP_EOL;
        echo "<br>".PHP_EOL;


        //provincia
        echo CHTML::dibujaEtiqueta("label", ["for" => "provincia"], "Provincia:", true).PHP_EOL;
        echo CHTML::campoListaDropDown("provincia", $datos["provincia"], $arrayProvincias, ["class" => "seleccionar"] ).PHP_EOL;
        echo "<br>".PHP_EOL;

        
        echo CHTML::campoBotonSubmit("Filtrar", ["class" => "boton", "name" => "filtradoIndexUsuarios"]).PHP_EOL;
        echo CHTML::campoBotonSubmit("Limpiar filtrado", ["class" => "boton", "name" => "limpiarFiltradoIndexUsuarios"]).PHP_EOL;


        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
   

    echo CHTML::finalizarForm().PHP_EOL;

    
echo $objCaja->dibujaFin().PHP_EOL;

echo $varPagina->dibujate().PHP_EOL;
echo CHTML::dibujaEtiqueta("div", ["id" => "divTablaUsuarios"], null, false).PHP_EOL;
    echo $tabla->dibujate().PHP_EOL;
echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
echo $varPagina->dibujate().PHP_EOL;

echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
















?>