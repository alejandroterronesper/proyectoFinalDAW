<?php

//JAVASCRIPT
$this->textoHead = CPager::requisitos().PHP_EOL;
$this->textoHead .= "    ". CCaja::requisitos().PHP_EOL;
$this->textoHead .= "   ". CHTML::scriptFichero("/js/main.js", ["defer" => "defer"]).PHP_EOL;

//paginador
$varPagina = new CPager($paginador, []);

//CAJA PARA FILTRAR BÚSQUEDAS
$objCaja = new CCaja("", "", []);


echo $objCaja->dibujaApertura().PHP_EOL;

    echo CHTML::iniciarForm("","POST", ["class" => "formulario"]).PHP_EOL;
        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

            //TITULO
            echo CHTML::dibujaEtiqueta("label", ["for" => "titulo"], "Título: ", true).PHP_EOL;
            echo CHTML::campoText("titulo", $datos["titulo"], ["class" => "entrada"]).PHP_EOL;
            echo "<br>".PHP_EOL;


            //AUTOR
            echo CHTML::dibujaEtiqueta("label", ["for" => "autor"], "Autor: ", true).PHP_EOL;
            echo CHTML::campoText("autor", $datos["autor"], ["class" => "entrada"]).PHP_EOL;
            echo "<br>".PHP_EOL;

            //CATEGORÍA
            echo CHTML::dibujaEtiqueta("label", ["for" => "categoria"], "Categoría: ", true).PHP_EOL;
            echo CHTML::campoListaDropDown("categoria", $datos["categoria"], $categoriasArray, ["class" => "seleccionar"] ).PHP_EOL;
            echo "<br>".PHP_EOL;

            echo CHTML::campoBotonSubmit("Filtrar", ["class" => "boton", "name" => "filtraDatosPrincipal", "style" => "margin-top: 2%;"]).PHP_EOL;
            echo CHTML::campoBotonSubmit("Limpiar filtrado", ["class" => "boton", "name" => "limpiaFiltradoPrincipal"]).PHP_EOL;


        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;


    echo CHTML::finalizarForm().PHP_EOL;


echo $objCaja->dibujaFin().PHP_EOL;


echo $varPagina->dibujate().PHP_EOL;
//div de los obras
echo CHTML::dibujaEtiqueta("div", ["class" => "contenedor"], null, false).PHP_EOL;

    foreach ($filas as $clave => $valor){
        

        echo $this->dibujaVistaParcial("divObra", ["obras" => $valor], true).PHP_EOL;

    }

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
echo $varPagina->dibujate().PHP_EOL;

?>

    