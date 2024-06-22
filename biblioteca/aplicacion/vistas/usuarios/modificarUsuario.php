<?php

$this->textoHead = CHTML::scriptFichero("/js/main.js", ["defer" => "defer"]).PHP_EOL;


$nombre = CHTML::dibujaEtiqueta("span",[], $usuario->nick);

echo CHTML::dibujaEtiqueta("div", ["class" => "verObra", "style" => "margin-left: 20%;"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("h2", [], "Modificar usuario: " .$nombre, true).PHP_EOL;


    echo CHTML::iniciarForm("", "post", []).PHP_EOL;

        //nick
        echo CHTML::modeloLabel($usuario, "nick", []).PHP_EOL;
        echo CHTML::modeloText($usuario, "nick", ["readonly" => true]).PHP_EOL;

        //nombre
        echo CHTML::modeloLabel($aclUser, "nombre", []).PHP_EOL;
        echo CHTML::modeloText($aclUser, "nombre", []).PHP_EOL;
        echo CHTML::modeloError($aclUser ,"nombre", ["class" => "error"]).PHP_EOL;


        //dni
        echo CHTML::modeloLabel($usuario, "nif", []).PHP_EOL;
        echo CHTML::modeloText($usuario, "nif", []).PHP_EOL;
        echo CHTML::modeloError($usuario ,"nif", ["class" => "error"]).PHP_EOL;

        //provincia
        echo CHTML::modeloLabel($usuario, "provincia", []).PHP_EOL;
        echo CHTML::modeloListaDropDown($usuario, "provincia", $arrayAndalucia,["id" => "comboModAndalucia","required" => true]).PHP_EOL;
        echo CHTML::modeloError($usuario ,"provincia", ["class" => "error"]).PHP_EOL;


        //poblacion
        echo CHTML::modeloLabel($usuario, "poblacion", []).PHP_EOL;
        echo CHTML::modeloListaDropDown($usuario, "poblacion", $arrayPoblaciones,["id" => "comboModPoblacion", "required" => true]).PHP_EOL;
        echo CHTML::modeloError($usuario ,"poblacion", ["class" => "error"]).PHP_EOL;


        //cp
        echo CHTML::modeloLabel($usuario, "cp", []).PHP_EOL;
        echo CHTML::modeloNumber($usuario, "cp", ["class" => "campoNumero"]).PHP_EOL;
        echo CHTML::modeloError($usuario ,"cp", ["class" => "error"]).PHP_EOL;


        //direccion
        echo CHTML::modeloLabel($usuario, "direccion", []) . PHP_EOL;
        echo CHTML::modeloText($usuario, "direccion", []) . PHP_EOL;
        echo CHTML::modeloError($usuario, "direccion", ["class" => "error"]) . PHP_EOL;



        //email
        echo CHTML::modeloLabel($usuario, "email", []).PHP_EOL;
        echo CHTML::modeloEmail($usuario, "email", []).PHP_EOL;
        echo CHTML::modeloError($usuario ,"email", ["class" => "error"]).PHP_EOL;


        //fecha de nacimiento
        echo CHTML::modeloLabel($usuario, "fecha_nacimiento", []).PHP_EOL;
        echo CHTML::modeloDate($usuario, "fecha_nacimiento", []).PHP_EOL;
        echo CHTML::modeloError($usuario ,"fecha_nacimiento", ["class" => "error"]).PHP_EOL;


        //estado
        echo CHTML::modeloLabel($usuario, "estado", []).PHP_EOL;
        echo CHTML::modeloListaDropDown($usuario, "estado", $arrayEstados, ["required" => true]).PHP_EOL;
        echo CHTML::modeloError($usuario ,"estado", ["class" => "error"]).PHP_EOL;


        //codigo
        echo CHTML::modeloLabel($usuario, "codigo", []).PHP_EOL;
        echo CHTML::modeloNumber($usuario, "codigo", ["class" => "campoNumero"]).PHP_EOL;
        echo CHTML::modeloError($usuario ,"codigo", ["class" => "error"]).PHP_EOL;

        //rol de usuario
        echo CHTML::modeloLabel($aclUser, "cod_acl_role", []).PHP_EOL;
        echo CHTML::modeloListaDropDown($aclUser, "cod_acl_role", $arrayRole, []).PHP_EOL;
        echo CHTML::modeloError($aclUser ,"cod_acl_role", ["class" => "error"]).PHP_EOL;


        //Contraseña
        echo CHTML::dibujaEtiqueta("label", ["for" => "contraAcl"], "Contraseña:", true).PHP_EOL;
        echo CHTML::campoPassword("contraAcl", $datosForm["contraAcl"], ["maxlength" => 10]).PHP_EOL;
        if (isset($errores)){
            if (isset($errores["contraAcl"])){

                echo CHTML::dibujaEtiqueta("div", ["class" => "error"], null, false).PHP_EOL;

                    echo CHTML::dibujaEtiqueta("span", [], $errores["contraAcl"], true).PHP_EOL;

                echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
            }
        }
        echo CHTML::modeloError($aclUser ,"contrasenia", ["class" => "error"]).PHP_EOL; //se valida desde modelo



        //repite contraseña
        echo CHTML::dibujaEtiqueta("label", ["for" => "contraAcl"], "Repite contraseña:", true).PHP_EOL;
        echo CHTML::campoPassword("contraAcl1", $datosForm["contraAcl1"], ["maxlength" => 10]).PHP_EOL;
        if (isset($errores)){
            if (isset($errores["contraAcl1"])){

                echo CHTML::dibujaEtiqueta("div", ["class" => "error"], null, false).PHP_EOL;

                    echo CHTML::dibujaEtiqueta("span", [], $errores["contraAcl1"], true).PHP_EOL;

                echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
            }
        }

        
        //borrado
        echo CHTML::modeloLabel($usuario, "borrado", []).PHP_EOL;
        echo CHTML::modeloListaRadioButton($usuario, "borrado", [0=>"NO", 1=> "SI"], " ", []).PHP_EOL;
        echo CHTML::modeloError($usuario, "borrado", ["class" => "error"]).PHP_EOL;
        echo "<br>".PHP_EOL;

        echo CHTML::campoBotonSubmit("Actualizar datos", ["class" => "boton"]).PHP_EOL;
        echo CHTML::campoBotonReset("Restaurar datos", ["class" => "boton"]).PHP_EOL;



    echo CHTML::finalizarForm().PHP_EOL;


    echo "<br>".PHP_EOL;



    echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

            echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

                echo CHTML::botonHtml(CHTML::link("Volver atrás", ["usuarios", "indexUsuarios"]), ["class"=>"boton"]).PHP_EOL;
                echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

                //En caso de querer ver mas datos de la obra

                echo CHTML::botonHtml(CHTML::link("Ver usuario", ["usuarios", "verUsuario/id=".$usuario->cod_usuario]), ["class"=>"boton"]).PHP_EOL;


                if ($usuario->borrado === 0){
                    echo CHTML::botonHtml(CHTML::link("Borrar usuario", ["usuarios", "borrarUsuario/id=".$usuario->cod_usuario]), ["class"=>"boton"]).PHP_EOL;
                }


        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;



?>