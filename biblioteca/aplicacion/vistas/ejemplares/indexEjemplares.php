<?php
$this->textoHead = CPager::requisitos();
$this->textoHead .= "    ". CCaja::requisitos().PHP_EOL;



//TABLA
$tabla = new CGrid($cabecera, $filas, ["class" => "tabla1"]);

//paginador
$varPagina = new CPager($paginador, []);

//CAJA PARA FILTRAR BÚSQUEDAS
$objCaja = new CCaja("Filtrar búsqueda", "", []);


//operaciones
echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones", "style" => "margin-bottom: 2%; width: 96%; margin-left: 2%"], null, false).PHP_EOL;
    echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;
        echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;
        echo CHTML::botonHtml(CHTML::link("Añadir ejemplar libro", ["ejemplares", "anadeEjemplarLibro"]), ["class"=>"boton", "style" => "margin-right: 1%"]);
        echo CHTML::botonHtml(CHTML::link("Añadir ejemplar audio", ["ejemplares", "anadeEjemplarAudio"]), ["class"=>"boton", "style" => "margin-right: 1%"]);
        echo CHTML::botonHtml(CHTML::link("Añadir ejemplar video", ["ejemplares", "anadeEjemplarVideo"]), ["class"=>"boton"]);
    echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

//FILTRO
echo $objCaja->dibujaApertura().PHP_EOL;

    echo CHTML::iniciarForm("","POST", ["class" => "formulario"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

        //TIPO OBRA
        echo CHTML::dibujaEtiqueta("label", ["for" => "categoria"], "Categoría: ", true).PHP_EOL;
        echo CHTML::campoListaDropDown("tipoObra", $datos["tipoObra"], $arrayCategorias, ["class" => "seleccionar"] ).PHP_EOL;
        echo "<br>".PHP_EOL;

        //ESTADO
        echo CHTML::dibujaEtiqueta("label", ["for" => "borrado"], "Estado: ", true).PHP_EOL;
        echo CHTML::campoListaDropDown("estado", $datos["estado"], $estadoArray, ["class" => "seleccionar"] ).PHP_EOL;
        echo "<br>".PHP_EOL;


        
        echo CHTML::campoBotonSubmit("Filtrar", ["class" => "boton", "name" => "filtradoIndexEjemplares"]).PHP_EOL;
        echo CHTML::campoBotonSubmit("Limpiar filtrado", ["class" => "boton", "name" => "limpiarFiltradoIndexEjemplares"]).PHP_EOL;


        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
   

    echo CHTML::finalizarForm().PHP_EOL;

    
echo $objCaja->dibujaFin().PHP_EOL;


echo "<br>".PHP_EOL;


echo $varPagina->dibujate().PHP_EOL;
echo CHTML::dibujaEtiqueta("div", ["id" => "divTablaEjemplares"], null, false).PHP_EOL;
    echo $tabla->dibujate().PHP_EOL;
echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
echo $varPagina->dibujate().PHP_EOL;





?>