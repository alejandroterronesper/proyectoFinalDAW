<?php

/**
 *  Clase para el modelo usuarios, se encarga de gestionar la tabla de
 * usuarios
 */
class Usuarios extends CActiveRecord {

    /**
     * Devuelve el nombre del modelo
     *
     * @return String cadena del nombre del modelo
     */
    protected function fijarNombre(): string
    {
        return "usuarios";
    }

    /**
     * Devuelve el nombre de la tabla
     *
     * @return String de la vista del modelo de usuarios
     */
    protected function fijarTabla(): string
    {
        return "usuarios";
    }

    /**
     * Devuelve array con los atributos
     *
     * @return Array de los atributos del modelo de usuarios
     */
    protected function fijarAtributos(): array
    {

        return array ("cod_usuario", "nick",
                      "nif", "direccion",
                      "poblacion", "provincia",
                      "cp", "borrado", 
                      "email", "fecha_nacimiento", 
                      "estado", "codigo");

    }


    /**
    * Primary key de la vista
    *
    * @return String devuelve cadena del nombre de la primary key
    */
   protected function fijarId(): string
   {
       return "cod_usuario";
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
                      "cod_usuario" => "Código de usuario",
                       "nick" => "Nick",
                      "nif" => "NIF",
                       "direccion" => "Dirección",
                      "poblacion" => "Población", 
                      "provincia" => "Provincia",
                      "cp" => "Código postal",
                       "borrado" => "Borrado", 
                      "email" => "Email",
                       "fecha_nacimiento" => "Fecha de nacimiento", 
                      "estado" => "Estado",
                       "codigo" => "Código"
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


            //NICK
            array ("ATRI" => "nick", "TIPO" => "CADENA", "TAMANIO" => 50, 
                    "MENSAJE" => "El nick no puede superar los 50 caracteres"),
            array ("ATRI" => "nick", "TIPO" => "REQUERIDO", "MENSAJE" => "Debe introducir un nick"),
            array ("ATRI" => "nick", "TIPO" => "FUNCION", "FUNCION" => "validaNick"),


            //NIF
            array ("ATRI" => "nif", "TIPO" => "CADENA", "TAMANIO" => 9,
                     "MENSAJE" => "El NIF debe contener 6 caracteres"),
            array ("ATRI" => "nif", "TIPO" => "REQUERIDO", "MENSAJE" => "Debe introducir un NIF"),
            array("ATRI" => "nif", "TIPO" => "FUNCION", "FUNCION" => "validaDNI"),
            
            //DIRECCION
            array ("ATRI" => "direccion", "TIPO" => "CADENA", "TAMANIO" => 50, 
            "MENSAJE" => "La dirección no puede superar los 50 caracteres"),
            array ("ATRI" => "direccion", "TIPO" => "REQUERIDO", "MENSAJE" => "Debe introducir una dirección"),
            array("ATRI" => "nif", "TIPO" => "FUNCION", "FUNCION" => "validaDireccion"),

            
            //POBLACION
            array ("ATRI" => "poblacion", "TIPO" => "CADENA", "TAMANIO" => 50),
            array ("ATRI" => "poblacion", "TIPO" => "REQUERIDO", "MENSAJE" => "Debe introducir una población"),
            array ("ATRI" => "poblacion", "TIPO" => "FUNCION", "FUNCION" => "validaPoblacion"),

            
            //PROVINCIA
            array ("ATRI" => "provincia", "TIPO" => "CADENA", "TAMANIO" => 50),
            array ("ATRI" => "provincia", "TIPO" => "REQUERIDO", "MENSAJE" => "Debe introducir una provincia"),
            array ("ATRI" => "provincia", "TIPO" => "RANGO", "RANGO" => array_keys(Usuarios::provinciasAndalucia()),
                    "MENSAJE" => "Debes elegir entre una de las provincias disponibles"        
            ),


            //CP
            array ("ATRI" => "cp", "TIPO" => "REQUERIDO", "MENSAJE" => "Debe introducir un código postal"),
            array ("ATRI" => "cp", "TIPO" => "ENTERO"),
            array ("ATRI" => "cp","TIPO" => "FUNCION", "FUNCION" => "validaCP"),

            //BORRADO
            array ("ATRI" => "borrado", "TIPO" => "ENTERO"),
            array ("ATRI" => "borrado", "TIPO" => "RANGO", "RANGO" => array(0,1)),


            //EMAIL
            array ("ATRI" => "email", "TIPO" => "REQUERIDO", "MENSAJE" => "Debe introducir un email"),
            array ("ATRI" => "email", "TIPO" => "CADENA", "TAMANIO" => 50, "MENSAJE" => "El email no debe superar los 50 caracteres"),
            array ("ATRI" => "email", "TIPO" => "FUNCION", "FUNCION" => "validaEmail"),

            //ESTADO
            array ("ATRI" => "estado", "TIPO" => "ENTERO"),
            array ("ATRI" => "estado", "TIPO" => "RANGO", "RANGO" => array(1,2,3), 
            "MENSAJE" => "Debe elegir entre uno de los estados de usuario existente"), //1--> PENDIENTE, 2-->APROBADO, 3-->CANCELADO


            //fecha_nacimiento
            array ("ATRI" => "fecha_nacimiento", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes introducir una fecha"),
            array("ATRI" => "fecha_nacimiento", "TIPO" => "FECHA"),
            array("ATRI" => "fecha_nacimiento", "TIPO" => "FUNCION", "FUNCION" => "validaMayoriaEdad"),


            //CODIGO
            array ("ATRI" => "codigo", "TIPO" => "ENTERO", "MAX" => 999999, "Debes introducir una cifra"),
            array ("ATRI" => "codigo", "TIPO" => "FUNCION", "FUNCION" => "validaCodigo")

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
        $this->cod_usuario = 0;
        $this->nick = "";
        $this->nif = "";
        $this->direccion = "";
        $this->poblacion = "";
        $this->provincia = "MALAGA";
        $this->cp = 0;
        $this->borrado = 0;
        $this->email = "";
        $this->fecha_nacimiento = ""; 
        $this->estado = "";
        $this->codigo = 0;
       
    }


    /**
    * Función que convierte/transforma   
    * los diferentes valores del modelo actual
    * buscado en la BDD, por ejemplo transformación de fechas
    * saneamiento de cadenas, y conversión de tipos
    *
    * @return Void, no devuelve nada
    */
    protected function afterBuscar(): void
    {

        $this->cod_usuario = intval($this->cod_usuario);
        $this->cp = intval($this->cp);
        $this->borrado = intval($this->borrado);
        $this->fecha_nacimiento =  CGeneral::fechaMysqlANormal($this->fecha_nacimiento); 
        $this->codigo = intval($this->codigo);
        $this->estado = intval($this->estado);
       
    }




    /**
     * Función que devuelve una cadena con la cadena
     * para realizar sentencia inserta a la tabla usuarios
     *
     * @return String cadena de insert
     */
    protected function fijarSentenciaInsert(): string
    {
        
        $nick = CGeneral::addSlashes($this->nick);
        $nif = CGeneral::addSlashes($this->nif);
        $direccion = CGeneral::addSlashes($this->direccion);
        $poblacion = CGeneral::addSlashes($this->poblacion);
        $provincia = CGeneral::addSlashes($this->provincia);
        $cp = intval($this->cp);
        $borrado = intval($this->borrado);
        $email = CGeneral::addSlashes($this->email);
        $fecha_nacimiento = CGeneral::fechaNormalAMysql($this->fecha_nacimiento);
        $estado  = intval($this->estado);
        $codigo = intval($this->codigo);


        $sentencia = "INSERT INTO `usuarios` (`nick`, `nif`, `direccion`,
                                              `poblacion`, `provincia`, `cp`,
                                              `borrado`, `email`, `fecha_nacimiento`,
                                              `estado`, `codigo`)
                                              
                       VALUES ('$nick', '$nif', '$direccion', '$poblacion',
                                '$provincia', '$cp', '$borrado', '$email',
                                '$fecha_nacimiento', '$estado', '$codigo')";

        return $sentencia;
    }



    /**
     * Función que devuelve una cadena
     * con la sentencia update para la tabla
     * usuarios
     *
     * @return String devuelve una cadena
     */
    protected function fijarSentenciaUpdate(): string
    {

        $cod_usuario = intval($this->cod_usuario);
        $nick = CGeneral::addSlashes($this->nick);
        $nif = CGeneral::addSlashes($this->nif);
        $direccion = CGeneral::addSlashes($this->direccion);
        $poblacion = CGeneral::addSlashes($this->poblacion);
        $provincia = CGeneral::addSlashes($this->provincia);
        $cp = intval($this->cp);
        $borrado = intval($this->borrado);
        $email = CGeneral::addSlashes($this->email);
        $fecha_nacimiento = CGeneral::fechaNormalAMysql($this->fecha_nacimiento);
        $estado  = intval($this->estado);
        $codigo = intval($this->codigo);

        
        $sentencia = "UPDATE `usuarios` SET `nick` = '$nick',
                                        `nif` = '$nif',
                                        `direccion` = '$direccion',
                                        `poblacion` = '$poblacion',
                                        `provincia` = '$provincia',
                                        `cp` = $cp,
                                        `borrado` = $borrado,
                                        `fecha_nacimiento` = '$fecha_nacimiento',
                                        `estado` = $estado,
                                        `email` = '$email',
                                        `codigo` = $codigo
                      WHERE `cod_usuario` = $cod_usuario";

        return  $sentencia;

    }


    /**
     * Función que valida si el nick introducido existe
     * en la tabla usuarios, si existe lanza error
     * 
     * Se comprueba primero que coincide con el cod usuario
     * en tal caso es que no se ha cambiado
     * luego se comprueba si existe en general en la tabla
     * entonces es que se está usando de otro cod user
     *
     * @return Void no devuelve nada
     */
    public function validaNick():void{

        //Se hace consulta a la bbdd que me devuelva el cod
        //de usuario con el nick
        //Si el cod de usuario coincide con el actual es correcto
        //Si no coincide es que estamos usando un nick 
        //usado por el usuario NO ACTUAL

        $usuario = new Usuarios ();

        $cod =  intval($this->cod_usuario);
        $nombre = $this->nick;

        $nombre = trim($nombre);

        if ($nombre !== ""){

            //Primero se comprueba que el nick es del user actual
            if ($usuario->buscarPor(["where" => " `cod_usuario` = $cod AND  `nick` = '$nombre'"]) === false){
                //el nick se ha cambiado, se comprueba si pertenece a otro usuario para que salte el error

                if ($usuario->buscarPor(["where" => "  `nick` = '$nombre'"]) === true){
                    //se esta usando un nick ya existente que no corresponde con el user actual
                    $this->setError("nick", "El nick introducido ya existe");

                }

            }
        }
        else{
            $this->setError("nick", "El nick no puede ir vacío");

        }

        
    }

    public function validaDireccion(): void{

        $direccion = $this->direccion;
        $direccion = trim($direccion);

        if ($direccion === ""){
            $this->setError("direccion", "La dirección no puede ir vacía");
        }
        
    }

    /**
     * 
     * Función que valida si el DNI introducido es correcto
     *
     * @return Void no devuelve nada
     */
    public function validaDNI():void{


        $arrayLetras = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B' 
        , 'N','J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E'];

        $expresionDNI = "/^[0-9]{8}[A-Z]$/";

        $varDNI = $this->nif;


        if (preg_match($expresionDNI, $varDNI)){

            $varLetra = $varDNI[8];
            $digitos = substr($varDNI, 0, 8);
            $letraResto = $digitos % 23;

            if (isset($arrayLetras[$letraResto])){

                if ($arrayLetras[$letraResto] !== $varLetra){
                    $this->setError("nif", "El dni introducido es incorrecto");
                }
            }
            else{
                $this->setError("nif", "El dni introducido es incorrecto");
            }


        }
        else{
            $this->setError("nif", "El dni introducido es incorrecto");
        }

    }


    /**
     * Función para validar el código postal
     *
     * @return Void no devuelve nada
     */
    public function validaCP(){


        if ($this->cp < 10000 || $this->cp > 99999){
            $this->setError("cp", "Código postal incorrecto");
        }
    }



    /**
     * Función que valida el email a través de una expresión regular
     *
     * @return Void no devuelve nada
     */
    public function validaEmail(){


        $regEmail = "/^[a-zA-Z0-9-_\.]+@{1}[a-z]+\.[a-z]{2,3}$/";


        if (!preg_match($regEmail, $this->email)){
            $this->setError("email", "El correo no tiene un formato correcto");
        }
    }


    /**
     * Función que comprueba que el código no sea negativo
     *
     * @return Void -> almacena error en el setError
     */
    public function validaCodigo (){

        if (intval($this->codigo) < 0){
            $this->setError("codigo", "El código no puede ser negativo");

        }
    }

    /**
     * 
     *
     * @return void
     */
    public function validaPoblacion (){

        if ($this->poblacion == "-1"){
            $this->setError("poblacion", "Debes elegir una población");

        }
    }

    /**
     * 
     *
     * @return void
     */
    public function validaMayoriaEdad (){

        $fecha = $this->fecha_nacimiento;
        $fecha = DateTime::createFromFormat("d/m/Y", $fecha);
    
    
        if ($fecha !== false){//existe fecha de nacimiento

            $fecha->setTime(0,0,0);

            $fechaHoy = new DateTime();
            $fechaHoy->setTime(0,0,0);

            $diferencia = $fechaHoy->diff($fecha);

            if (intval($diferencia->y) < 18){
                $this->setError("fecha_nacimiento", "El usuario debe ser mayor de edad");

            }



        }
    }



    /**
     * Método estático que devuelve un array con 
     * los nombres de los nicks de la tabla usuarios
     *
     * @return Array devuelve un array con los nombres
     */
    public static function dameNickUsuarios (): array{


        $usuarios = new Usuarios ();

        $arrayUsuarios = [];

        foreach ($usuarios->buscarTodos() as $clave => $valor){

            $arrayUsuarios[intval($valor["cod_usuario"])] = $valor["nick"];
        }


        return $arrayUsuarios;
    }


    
    /**
     * Función que devuelve los posibles estados de 
     * un usuario
     *
     * @return Array -> devuelve un array con cod y descripción
     */
    public static function dameEstatos (): array {
        $array = [
                    1 => "Pendiente",
                    2 => "Aprobado",
                    3 => "Cancelado"
                    ];


        return $array;
    }


    /**
     * Método estático que me devuelve un array con las provincias
     * de andalucia estan en clave valor en ambas para usarse luego
     * para la petición y que me saque los correspondientes municipios
     *
     * @return Array -> con provincias de andalucia
     */
    public static function provinciasAndalucia (): array {

        $andalucia = [
            "ALMERIA" => "ALMERIA",
            "CADIZ" => "CADIZ",
            "CORDOBA" => "CORDOBA",
            "GRANADA" => "GRANADA",
            "HUELVA" => "HUELVA",
            "JAEN" => "JAEN",
            "MALAGA" => "MALAGA",
            "SEVILLA" => "SEVILLA"
        ];


        return  $andalucia;

    }




}











?>