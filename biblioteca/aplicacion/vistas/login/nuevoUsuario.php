<?php
$this->textoHead = CHTML::scriptFichero("/js/main.js", ["defer" => "defer"]).PHP_EOL;

echo CHTML::dibujaEtiqueta("div", ["class" => "verObra", "style" => "margin-left: 20%;"], null, false).PHP_EOL;

echo CHTML::dibujaEtiqueta("h1", [], "Crear usuario", true).PHP_EOL;



echo CHTML::iniciarForm("", "POST", []).PHP_EOL;

    //NOMBRE
    echo CHTML::dibujaEtiqueta("label", [], "Nombre:", true).PHP_EOL;
    echo CHTML::campoText("aclNombre",$datos["nombre"], ["required" => true]).PHP_EOL;
    
    if (count($errores)){ //Se comprueban errores

        if (isset($errores["nombre"])){

            echo CHTML::dibujaEtiqueta("div", ["class" => "error"],null, false).PHP_EOL;

                echo CHTML::dibujaEtiqueta("label", [], $errores["nombre"], true).PHP_EOL;

            echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
        }
    }


    //NICK
    echo CHTML::modeloLabel($usuario, "nick", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "nick", ["required" => true]).PHP_EOL;
    echo CHTML::modeloError($usuario, "nick").PHP_EOL;

    //NIF
    echo CHTML::modeloLabel($usuario, "nif", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "nif", ["required" => true]).PHP_EOL;
    echo CHTML::modeloError($usuario, "nif").PHP_EOL;



    //DIRECCION
    echo CHTML::modeloLabel($usuario, "direccion", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "direccion", ["required" => true]).PHP_EOL;
    echo CHTML::modeloError($usuario, "direccion").PHP_EOL;

    //PROVINCIA
    echo CHTML::modeloLabel($usuario, "provincia", []).PHP_EOL;
    echo CHTML::modeloListaDropDown($usuario, "provincia", $arrayAndalucia,["id" => "comboModAndalucia","required" => true]).PHP_EOL;
    echo CHTML::modeloError($usuario, "provincia", []).PHP_EOL;

    //POBLACION
    echo CHTML::modeloLabel($usuario, "poblacion", []) . PHP_EOL;
    echo CHTML::modeloListaDropDown($usuario, "poblacion", $arrayPoblaciones, ["id" => "comboModPoblacion", "required" => true]) . PHP_EOL;
    echo CHTML::modeloError($usuario, "poblacion") . PHP_EOL;




    //CP
    echo CHTML::modeloLabel($usuario, "cp", []).PHP_EOL;
    echo CHTML::modeloNumber($usuario, "cp", ["required" => true, "class"=> "campoNumero"]).PHP_EOL;
    echo CHTML::modeloError($usuario, "cp", []).PHP_EOL;

    //EMAIL
    echo CHTML::modeloLabel($usuario, "email", []).PHP_EOL;
    echo CHTML::modeloEmail($usuario, "email", ["required" => true, "required" => true]).PHP_EOL;
    echo CHTML::modeloError($usuario, "email", []).PHP_EOL;

    //FECHA_NACIMIENTO
    echo CHTML::modeloLabel($usuario, "fecha_nacimiento", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "fecha_nacimiento", ["placeholder" => "dd/mm/aaaa","required" => true ]).PHP_EOL;
    echo CHTML::modeloError($usuario, "fecha_nacimiento", []).PHP_EOL;


    //CONTRASEÑA
    echo CHTML::dibujaEtiqueta("label", [], "Contraseña:", true).PHP_EOL;
    echo CHTML::campoPassword("aclPw",$datos["contra"], ["required" => true]).PHP_EOL;
        
    if (count($errores)){ //Se comprueban errores

        if (isset($errores["aclPw"])){

            echo CHTML::dibujaEtiqueta("div", ["class" => "error"],null, false).PHP_EOL;

                echo CHTML::dibujaEtiqueta("label", [], $errores["aclPw"], true).PHP_EOL;

            echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
        }
    }
    echo "<br>".PHP_EOL;


    //BOTON ENVIAR
    echo CHTML::campoBotonSubmit("Registrar", ["class"=>"boton"]).PHP_EOL;
    echo CHTML::campoBotonReset("Limpiar campos", ["class"=>"boton"]).PHP_EOL;

echo CHTML::finalizarForm().PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


?>