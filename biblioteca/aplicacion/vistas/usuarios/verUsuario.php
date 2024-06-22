<?php



$nombre = CHTML::dibujaEtiqueta("span",[], $usuario->nick);

echo CHTML::dibujaEtiqueta("div", ["class" => "verObra", "style" => "margin-left: 20%;"], null, false).PHP_EOL;

    echo CHTML::dibujaEtiqueta("h2", [], "Ver usuario: " .$nombre, true).PHP_EOL;

    //nick
    echo CHTML::modeloLabel($usuario, "nick", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "nick", ["readonly" => true]).PHP_EOL;

    //nombre
    echo CHTML::modeloLabel($aclUser, "nombre", []).PHP_EOL;
    echo CHTML::modeloText($aclUser, "nombre", ["readonly" => true]).PHP_EOL;

    //dni
    echo CHTML::modeloLabel($usuario, "nif", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "nif", ["readonly" => true]).PHP_EOL;

    //poblacion
    echo CHTML::modeloLabel($usuario, "poblacion", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "poblacion", ["readonly" => true]).PHP_EOL;

    //provincia
    echo CHTML::modeloLabel($usuario, "provincia", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "provincia", ["readonly" => true]).PHP_EOL;


    //cp
    echo CHTML::modeloLabel($usuario, "cp", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "cp", ["readonly" => true]).PHP_EOL;


    //email
    echo CHTML::modeloLabel($usuario, "email", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "email", ["readonly" => true]).PHP_EOL;

    //fecha de nacimiento
    echo CHTML::modeloLabel($usuario, "fecha_nacimiento", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "fecha_nacimiento", ["readonly" => true]).PHP_EOL;

    //estado
    echo CHTML::modeloLabel($usuario, "estado", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "estado", ["readonly" => true]).PHP_EOL;

    //codigo
    echo CHTML::modeloLabel($usuario, "codigo", []).PHP_EOL;
    echo CHTML::modeloText($usuario, "codigo", ["readonly" => true]).PHP_EOL;

    //rol de usuario
    echo CHTML::modeloLabel($aclUser, "cod_acl_role", []).PHP_EOL;
    echo CHTML::modeloText($aclUser, "cod_acl_role", ["readonly" => true]).PHP_EOL;

    //borrado
    echo CHTML::modeloLabel($usuario, "borrado", []).PHP_EOL;
    echo CHTML::modeloListaRadioButton($usuario, "borrado", [0=>"NO", 1=> "SI"], " ", ["disabled" => true]).PHP_EOL;
    echo CHTML::modeloError($usuario, "borrado", ["class" => "error"]).PHP_EOL;
    echo "<br>".PHP_EOL;



    echo CHTML::dibujaEtiqueta("div", ["class" => "operaciones"], null, false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;

            echo CHTML::dibujaEtiqueta("legend", [], "Operaciones disponibles", true).PHP_EOL;

                echo CHTML::botonHtml(CHTML::link("Volver atrás", ["usuarios", "indexUsuarios"]), ["class"=>"boton"]).PHP_EOL;
                echo CHTML::botonHtml(CHTML::link("Volver al inicio", ["inicial", "index"]), ["class"=>"boton"]).PHP_EOL;

                //En caso de querer ver mas datos de la obra

                echo CHTML::botonHtml(CHTML::link("Modificar usuario", ["usuarios", "modificarUsuario/id=".$usuario->cod_usuario]), ["class"=>"boton"]).PHP_EOL;


                if ($usuario->borrado === 0){
                    echo CHTML::botonHtml(CHTML::link("Borrar usuario", ["usuarios", "borrarUsuario/id=".$usuario->cod_usuario]), ["class"=>"boton"]).PHP_EOL;
                }


        echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

?>