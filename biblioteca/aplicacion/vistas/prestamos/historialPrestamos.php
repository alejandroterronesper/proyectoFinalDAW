<?php

    $this->textoHead = CPager::requisitos();
    $this->textoHead .= "    ". CCaja::requisitos().PHP_EOL;



    
    if (isset($cabecera) === true){
    
      
        if (count($filas) === 0){
            echo "No has realizado ningún préstamo".PHP_EOL;
        }
        else{ 
        
            //TABLA
            $tabla = new CGrid($cabecera, $filas, ["class" => "tabla1"]);

            //paginador
            $varPagina = new CPager($paginador, []);

            //CAJA PARA FILTRAR BÚSQUEDAS
            $objCaja = new CCaja("Filtrar búsqueda", "", []);


            
            //FILTRO
            echo $objCaja->dibujaApertura().PHP_EOL;

            echo CHTML::iniciarForm("","POST", ["class" => "formulario"]).PHP_EOL;
                echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

                //DEVUELTO
                echo CHTML::dibujaEtiqueta("label", ["for" => "devuelto"], "Préstamos devueltos: ", true).PHP_EOL;
                echo CHTML::campoListaDropDown("devuelto", $datos["devuelto"], $arrayDevuelto, ["class" => "seleccionar"] ).PHP_EOL;
                echo "<br>".PHP_EOL;

                
                echo CHTML::campoBotonSubmit("Filtrar", ["class" => "boton", "name" => "filtradoPrestamosHistorial"]).PHP_EOL;
                echo CHTML::campoBotonSubmit("Limpiar filtrado", ["class" => "boton", "name" => "limpiarFiltradoHistorialPrestamos"]).PHP_EOL;


                echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;


            echo CHTML::finalizarForm().PHP_EOL;


            echo $objCaja->dibujaFin().PHP_EOL;

            echo $varPagina->dibujate().PHP_EOL;
                echo CHTML::dibujaEtiqueta("div", ["id" => "divTablaHistorialPrestamos"], null, false).PHP_EOL;
                     echo $tabla->dibujate().PHP_EOL;
                echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
            echo $varPagina->dibujate().PHP_EOL;

                    
        }
    }
    else{

        echo CHTML::dibujaEtiqueta("span", ["style"=> "color:red;"], 
        "No hay historial de préstamos",true).PHP_EOL;
    
    }





?>