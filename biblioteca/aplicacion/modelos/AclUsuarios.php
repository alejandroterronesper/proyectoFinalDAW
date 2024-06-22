<?php


class AclUsuarios extends CActiveRecord {


    /**
     * Devuelve el nombre del modelo
     *
     * @return String cadena del nombre del modelo
     */
    protected function fijarNombre(): string
    {
        return "acl_usuarios";
    }


    /**
     * Devuelve el nombre de la tabla
     *
     * @return String de la vista del modelo de acl_usuarios
     */
    protected function fijarTabla(): string
    {
        return "acl_usuarios";
    }

    /**
     * Devuelve array con los atributos
     *
     * @return Array de los atributos del modelo de acl_usuarios
     */
    protected function fijarAtributos(): array
    {

        return array ("cod_acl_usuario",
                      "nick",
                      "nombre", 
                      "contrasenia",
                      "cod_acl_role", 
                      "borrado");

    }


    /**
    * Primary key de la vista
    *
    * @return String devuelve cadena del nombre de la primary key
    */
   protected function fijarId(): string
   {
       return "cod_acl_usuario";
   }


    /**
    * Devuelve un array
    * de las diferentes parámetros
    * que tiene el modelo de ejemplares
    *
    * @return Array con descripción de los parámetros
    */
    protected function fijarDescripciones(): array
    {

        return array(
                      "nombre" => "Nombre",
                      "contrasenia" => "Contraseña",
                      "cod_acl_role" => "Role",
                      "borrado" => "Borrado"
        
                    );
    }


        /**
     * Función que devuelve un array con las difernetes restricciones de 
     * modelo actual
     *
     * @return Array de restricciones
     */
    protected function fijarRestricciones(): array
    {


        return array (
              

            //nick
            array ("ATRI" => "nick", "TIPO" => "CADENA", "TAMANIO" => 50,
             "MENSAJE" => "El nick no puede superar los 50 caracteres"),
            
            //nombre
            array ("ATRI" => "nombre", "TIPO" => "CADENA", "TAMANIO" => 50, "MENSAJE" => "El nombre no puede superar los 50 caracteres"),
            array ("ATRI" => "nombre", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes introducir un nombre"),

            //contrasenia
            array ("ATRI" => "contrasenia", "TIPO" => "CADENA", "TAMANIO" => 50,  "MENSAJE" => "La contraseña no puede superar los 50 caracteres"),
            array ("ATRI" => "contrasenia", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes introducir una contraseña"),//con sha1 sale 40

            //cod_acl_role
            array ("ATRI" => "cod_acl_role", "TIPO" => "ENTERO"),
            array ("ATRI" => "cod_acl_role", "TIPO" => "RANGO", "RANGO" => array_keys(AclUsuarios::devuelveAclRoles()),
                "MENSAJE" => "Elige uno de los roles existentes"),

            //borrado
            array("ATRI" => "borrado", "TIPO" => "ENTERO", "DEFECTO" => 0),
            array("ATRI" => "borrado", "TIPO" => "RANGO",
            "RANGO" => array(0, 1), "MENSAJE" => "Debes elegir una opción disponible"),

        );
    }


    /**
    * Función que inicializa los diferentes parámetros
    * tras darle memoria al modelo de ejemplares
    *
    * @return Void, no devuelve inicializa valores
    */
    protected function afterCreate(): void
    {
        $this->cod_acl_usuario = 0;
        $this->nick = "";
        $this->nombre = "";
        $this->contrasenia = "";
        $this->cod_acl_role = 0;
        $this->borrado = 0;
       
    }

    /**
     * 
     *
     * @return void
     */
    protected function afterBuscar(): void
    {

        $this->cod_acl_role = intval($this->cod_acl_role);
        $this->borrado = intval($this->borrado);
       
    }


    /**
     * 
     *
     * @return string
     */
    protected function fijarSentenciaInsert(): string
    {
        
        
        $nick = CGeneral::addSlashes($this->nick);
        $nombre = CGeneral::addSlashes($this->nombre);
        $contrasenia = CGeneral::addSlashes($this->contrasenia);
        $cod_acl_role = intval($this->cod_acl_role);
        $borrado = intval($this->borrado);
        $contrasenia = CGeneral::addSlashes($contrasenia);

        $sentencia = "INSERT INTO `acl_usuarios` (`nick`, `nombre`, `contrasenia`,
                                              `cod_acl_role`, `borrado`)
                                              
                       VALUES ('$nick', '$nombre',
                                sha1('$contrasenia'), $cod_acl_role,
                               $borrado)";

        return $sentencia;
    }



    /**
     * 
     *
     * @return string
     */
    protected function fijarSentenciaUpdate(): string
    {
        $sentencia = "";
        $cod_acl_usuario  = intval($this->cod_acl_usuario);
        $nick = CGeneral::addSlashes($this->nick);
        $nombre = CGeneral::addSlashes($this->nombre);
        $contrasenia = CGeneral::addSlashes($this->contrasenia);
        $cod_acl_role = intval($this->cod_acl_role);
        $borrado = intval($this->borrado);

        //Se ha consulta a bbdd y se comprueba si la contraseña ha sido
        //cambiada o no, es decir si el hash coincide
        $aclU = new AclUsuarios ();

        $aclU->buscarPorId($cod_acl_usuario);

        if ($contrasenia === $aclU->contrasenia){//sin actualizar contraseña
  
            $sentencia = "UPDATE `acl_usuarios` SET `nick` = '$nick',
            `nombre` = '$nombre',
            `cod_acl_role` = $cod_acl_role,
            `borrado` = $borrado

            WHERE `cod_acl_usuario` = $cod_acl_usuario";
        }
        else{//actualizando contraseña
            
            $sentencia = "UPDATE `acl_usuarios` SET `nick` = '$nick',
            `nombre` = '$nombre',
            `contrasenia` = sha1('$contrasenia'),
            `cod_acl_role` = $cod_acl_role,
            `borrado` = $borrado

            WHERE `cod_acl_usuario` = $cod_acl_usuario";
        }



        return  $sentencia;

    }


    /**
     * Funcion que devuelve el array de usuarios 
     * especificando el permiso
     *
     * @param integer $permiso
     * @return array
     */
    public static function devuelveUsuariosPermisos(int $permiso): array {

        $aclUsers = new AclUsuarios ();
        
        $arrayAcl = [];

        foreach($aclUsers->buscarTodos(["where" => " `cod_acl_role` = $permiso AND `borrado` = 0 "]) as $clave => $valor){
            $arrayAcl[intval($valor["cod_acl_usuario"])] = $valor["nick"];

        }


        return $arrayAcl;

    }


    public static function devuelveAclRoles (): array {
        
        $roles = [
            1 => "superadmin",
            2 => "bibliotecario",
            3 => "cliente"
        ];


        return $roles;
    }
}


?>