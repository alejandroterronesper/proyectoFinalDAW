<?php

/**
 * 
 */
class Prestamos extends CActiveRecord {

    /**
     * 
     *
     * @return string
     */
    protected function fijarNombre(): string
    {
        return "prestamos";
    }


    /**
     * 
     *
     * @return array
     */
    protected function fijarAtributos(): array
    {
        return array (
            "cod_prestamo",
            "cod_usuario",
            "cod_ejemplar",
            "fecha_inicio",
            "fecha_fin",
            "fecha_devolucion",
            "borrado"
        );
    }
    
    /**
     * 
     *
     * @return string
     */
    protected function fijarTabla(): string
    {
        return "prestamos";
    }

    /**
     * 
     *
     * @return string
     */
    protected function fijarId(): string
    {
        return "cod_prestamo";
    }


    /**
     * 
     *
     * @return array
     */
    protected function fijarDescripciones(): array
    {
        return array (
            "cod_prestamos" => "Código de préstamo",
            "cod_usuario" => "Usuario",
            "cod_ejemplar" => "Ejemplar",
            "fecha_inicio" => "Fecha de inicio",
            "fecha_fin" => "Fecha de fin",
            "fecha_devolucion" => "Fecha de devolución",
            "borrado" => "Borrado"

        );
    }



    protected function fijarRestricciones(): array
    {
        return array (


            //USUARIO
            array ("ATRI"=> "cod_usuario", "TIPO" => "ENTERO"),
            array ("ATRI"=> "cod_usuario", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes asignar el préstamo a un usuario"),
            array ("ATRI" => "cod_usuario", "TIPO" => "RANGO" , "RANGO" => array_keys(Usuarios::dameNickUsuarios())), 


            //EJEMPLAR
            array ("ATRI"=> "cod_ejemplar", "TIPO" => "ENTERO"),
            array ("ATRI"=> "cod_ejemplar", "TIPO" => "REQUERIDO", "MENSAJE" => "Debe asignar un ejemplar al préstamo"),
            array ("ATRI" => "cod_ejemplar", "TIPO" => "FUNCION" , "FUNCION" => "validaEjemplar"), 


            //FECHA INICIO
            array ("ATRI"=> "fecha_inicio", "TIPO" => "FECHA",  "DEFECTO" => new DateTime()),
            array("ATRI" => "fecha_inicio", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes introducir una fecha"),
            array ("ATRI" => "fecha_inicio", "TIPO" => "FUNCION" , "FUNCION" => "validaFechaInicio"), //La fecha de lanzamiento, no puede ser posterior a hoy

            //FECHA FIN
            array ("ATRI"=> "fecha_fin", "TIPO" => "FECHA"),
            array("ATRI" => "fecha_fin", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes introducir una fecha"),
            array ("ATRI" => "fecha_fin", "TIPO" => "FUNCION" , "FUNCION" => "validaFechaFin"), 

            //FECHA DEVOLUCION
            array ("ATRI"=> "fecha_devolucion", "TIPO" => "FECHA"),
            array ("ATRI" => "fecha_devolucion", "TIPO" => "FUNCION" , "FUNCION" => "validaFechaDevolucion"), 


            //BORRADO
            array("ATRI" => "borrado", "TIPO" => "ENTERO", "DEFECTO" => 0),
            array("ATRI" => "borrado", "TIPO" => "RANGO",
            "RANGO" => array(0, 1), "MENSAJE" => "Debes elegir una opción disponible"),
            array ("ATRI" => "borrado", "TIPO" => "FUNCION" , "FUNCION" => "validaBorrado"), //Si el prestamo aun no ha sido devuelto, no se puede borrar


        );
    }


    protected function afterCreate(): void
    {


        $this->cod_prestamo = 0;
        $this->cod_usuario = 0;
        $this->cod_ejemplar = 0;
        $this->borrado = 0;


        //Pasamos la fecha a cadena
        $fechaAhora = new DateTime();
        $fechaAhora = $fechaAhora->format("d/m/Y");
        $this->fecha_inicio = $fechaAhora;
        

        $fechaActual = new DateTime();
        $fechaFin = $fechaActual->add(new DateInterval("P3W"));
        $fechaFin = $fechaFin->format("d/m/Y");
        $this->fecha_fin = $fechaFin;


        //Ponemos la fecha fin por defecto
        $fecha_devolucion = new DateTime('1900-01-01');
        $fecha_devolucion = $fecha_devolucion->format("d/m/Y");


        $this->fecha_devolucion = $fecha_devolucion;

    }




    protected function afterBuscar(): void
    {

        
        $this->cod_prestamo = intval($this->cod_prestamo);
        $this->cod_usuario = intval($this->cod_usuario);
        $this->cod_ejemplar = intval($this->cod_ejemplar);
        $this->fecha_inicio = CGeneral::fechaMysqlANormal($this->fecha_inicio);
        $this->fecha_fin = CGeneral::fechaMysqlANormal($this->fecha_fin);
        $this->fecha_devolucion = CGeneral::fechaMysqlANormal($this->fecha_devolucion);
        $this->borrado = intval($this->borrado);


    }




    protected function fijarSentenciaInsert(): string
    {
        
        // $cod_prestamo = intval($this->cod_prestamo);
        $cod_usuario = intval($this->cod_usuario);
        $cod_ejemplar = intval($this->cod_ejemplar);
        $fecha_inicio = CGeneral::fechaNormalAMysql($this->fecha_inicio);
        $fecha_fin = CGeneral::fechaNormalAMysql($this->fecha_fin);
        $fecha_devolucion = CGeneral::fechaNormalAMysql($this->fecha_devolucion);
        $borrado = intval($this->borrado);

    
        $sentencia = "INSERT INTO `prestamos` (`cod_usuario`,
                                                `cod_ejemplar`, `fecha_inicio`,
                                                `fecha_fin`, `fecha_devolucion`,
                                                `borrado`)
        
        
                        VALUES ($cod_usuario, $cod_ejemplar,
                                '$fecha_inicio', '$fecha_fin', '$fecha_devolucion', $borrado)";


        return $sentencia; 
    }




    protected function fijarSentenciaUpdate(): string
    {

        $cod_prestamo = intval($this->cod_prestamo);
        $cod_usuario = intval($this->cod_usuario);
        $cod_ejemplar = intval($this->cod_ejemplar);
        $fecha_inicio = CGeneral::fechaNormalAMysql($this->fecha_inicio);
        $fecha_fin = CGeneral::fechaNormalAMysql($this->fecha_fin);
        $fecha_devolucion = CGeneral::fechaNormalAMysql($this->fecha_devolucion);
        $borrado = intval($this->borrado);

        //AQUI COMPRUEBA SI FECHA DEVOLUCION ES NULL O NO
        $sentencia = "UPDATE `prestamos` SET `cod_usuario` = $cod_usuario,
                                             `cod_ejemplar` = $cod_ejemplar,
                                             `fecha_inicio` = '$fecha_inicio',
                                             `fecha_fin` = '$fecha_fin',
                                             `fecha_devolucion` = '$fecha_devolucion',
                                             `borrado` = $borrado

                        WHERE `cod_prestamo` = $cod_prestamo ";

    
        return $sentencia;
    }


    /**
     * Función que valida 
     * que el prestamo elegido
     * no esté entre los prestamos del usuario
     * actual, en caso de no estar se comprueba si este
     * esta reservado o no si el usuario no tiene mas de 3 prestamos
     * sin devolver
     *
     * @return Void -> almacena el error en set errores
     */
    public function validaEjemplar ():Void{

        $codEjemplar = intval($this->cod_ejemplar);

        $codUsuario = intval($this->cod_usuario);

        //Ambos tienen que ser número si no
        //No puedo realizar comprobación

        if(($codUsuario !== "") && ($codEjemplar !== "" )){

            $usuario = new Usuarios ();

            if ($usuario->buscarPorId($codUsuario) === true){ //Si existe el usuario, seguimos operando

                //Ahora se comprueba que el usuario actual
                //No tiene el ejemplar seleccionado

                $prestamo = New Prestamos ();

                //Si es falso, es que no lo tiene, se siguen con las comprobaciones
                if ($prestamo->buscarPor(["where" => " `cod_usuario`  = $codUsuario AND `cod_ejemplar` = $codEjemplar "]) === false){

                    //Ahora se comprueba que el usuario tenga 3 prestamos sin devolver
                    $totalPrestamosSinDevolver = $prestamo->buscarTodosNRegistros(["where" => " `cod_usuario` = $codUsuario AND `fecha_devolucion` = '1900-01-01'"]);

                    if ($totalPrestamosSinDevolver === 3){//Tiene tres sin devolver
                        $this->setError("cod_ejemplar", "El usuario actual tiene 3 préstamos sin devolver, no puedes añadir más");
                    }
                    else{
                        //Aqui se comprueba que el prestamo
                        //en caso de existir comprobar que su fecha sea distinta de 
                        //fecha no devuelta 1900-01-01

                       


                        //Aqui se comprueba que el ejemplar este disponible
                        $ejemplar = new Ejemplares ();

                        if ($ejemplar->buscarPorId($codEjemplar) === true){ //existe se comprueba

                            // $estadoEjemplar = intval($ejemplar->estado_ejemplar);
                            //Hago una consulta, con el codEjemplar y fecha_devolucion = 1900-01-01 en tabla de prestamos
                            $prestamoEjemplarComprobacion = $prestamo->
                            buscarTodosNRegistros(
                            ["where" => "  `cod_ejemplar` = $codEjemplar AND `fecha_devolucion` = '1900-01-01 '"]);


                            if ($prestamoEjemplarComprobacion !== 0){//si no da 0, está reservado
                                $this->setError("cod_ejemplar", "El ejemplar seleccionado no está disponible");

                            }
                        }
                        else{//que no existe salta error
                            $this->setError("cod_ejemplar", "El ejemplar indicado no existe");

                        }

                    }
                }

            }

        }

    }



    /**
     * Función que se encarga de validar el borrado de un prestamo
     * en caso de que el préstamo no haya sido devuelto, no 
     * se podrá eliminar
     *
     * @return Void -> almacena error
     */
    public function validaBorrado(): void{


        if (intval($this->borrado) === 1){//En caso de elegir borrado

            $fechaDevolucion = $this->fecha_devolucion;

            if ($fechaDevolucion === "01/01/1900"){//No ha sido devuelto

                $this->setError("borrado", "No se puede borrar porque aún no ha sido devuelto");
            }
        }
    }


    /**
     * Función que nos permite validar la fecha de inicio de del préstamo
     * la fecha no podrá ser anterior al 1 de enero de 2024
     *
     * @return Void -> almacena error, no devuelve nada
     */
    public function validaFechaInicio(): void{

        $fechaInicio = $this->fecha_inicio;
        $fechaInicio = DateTime::createFromFormat("d/m/Y", $fechaInicio);

        if ($fechaInicio !== false){ //Se comprueba que exista la fecha

            $fechaInicio->setTime(0,0,0);

            $fechaLimite = "01/01/2024";
            $fechaLimite = DateTime::createFromFormat("d/m/Y", $fechaLimite);
            $fechaLimite->setTime(0,0,0);

            //Se comprueba que no es anterior al 1/1/2024
            if ($fechaInicio < $fechaLimite){
                $this->setError("fecha_inicio", "La fecha de inicio del préstamo no puede ser anterior al 1 de enero de 2024");
            }


            // //Se comprueba que no sea posterior a la fecha fin
            // $fechaFin = $this->fecha_fin;
            // $fechaFin = DateTime::createFromFormat("d/m/Y", $fechaFin);

            // if ($fechaFin !== false){ //Si existe la fecha fin

            //     $fechaFin->setTime(0,0,0);

            //     if ($fechaInicio > $fechaFin){
            //         $this->setError("fecha_inicio", "La fecha de inicio no puede ser posterior a la fecha de fin");
            //     }
            // }

            // //Ahora se comprueba la fecha de devolución
            // $fechaDevolucion = $this->fecha_devolucion;

            // if ($fechaDevolucion !== "01/01/1900"){ //En este caso no se ha devuelto, evitamos problemas de comparación

            //     $fechaDevolucion = DateTime::createFromFormat("d/m/Y", $fechaDevolucion);

            //     if ($fechaDevolucion !== false) { //en caso de existir

            //         $fechaDevolucion->setTime(0, 0, 0);

            //         //Se comprueba que no sea posterior a la fecha de devolución
            //         if ($fechaInicio > $fechaDevolucion){

            //         }
            //     }

            // }
        }
     

    }

    

    /**
     * Se comprueba que la fecha de fin no se anterior
     * a la fecha de inicio
     *
     * @return Void -> almacena error, no devuelve nada
     */
    public function validaFechaFin(): Void{
            
            $fechaFin = $this->fecha_fin;
            $fechaFin = DateTime::createFromFormat("d/m/Y", $fechaFin);

            if ($fechaFin !== false){ //Si existe la fecha fin

                $fechaFin->setTime(0,0,0);

                $fechaInicio = $this->fecha_inicio; //creamos fecha de inicio a datetime
                $fechaInicio = DateTime::createFromFormat("d/m/Y", $fechaInicio);

                if ($fechaInicio !== false){//Se comprueba que existe la fecha de inicio

                    $fechaInicio->setTime(0,0,0);

                    if ($fechaInicio > $fechaFin){
                        $this->setError("fecha_fin", "La fecha fin no puede ser anterior a la fecha de inicio");
                    }
                }
            }

    }


    /**
     * Se comprueba que la fecha de devolucion
     * no sea anterior a fecha fin
     *
     * @return Void -> almacena error, no devuelve nada
     */
    public function validaFechaDevolucion(): void{

        //Ahora se comprueba la fecha de devolución
            $fechaDevolucion = $this->fecha_devolucion;

            if ($fechaDevolucion !== "01/01/1900"){ //En este caso no se ha devuelto, evitamos problemas de comparación

                $fechaDevolucion = DateTime::createFromFormat("d/m/Y", $fechaDevolucion);

                if ($fechaDevolucion !== false) { //en caso de existir

                    $fechaDevolucion->setTime(0, 0, 0);

                    // //Instanciamos la fecha de fin                            
                    // $fechaInicio = $this->fecha_inicio;
                    // $fechaInicio = DateTime::createFromFormat("d/m/Y", $fechaInicio);

                    // if($fechaInicio !== false){//Existe la fecha de fin
                        
                    //     $fechaInicio->setTime(0,0,0);
                        
                    //     //Se comprueba que la fecha de devolucion no sea anterior a la fecha de fin
                    //     if ($fechaDevolucion < $fechaInicio){
                    //         $this->setError("fecha_devolucion", "La fecha de devolución no puede ser anterior a la fecha de inicio");

                    //     }
                    // }


                    //Ahora se comprueba que la devolución no sea posterior al día de hoy
                    $hoy = new DateTime();
                    $hoy->setTime(0,0,0);

                    if ($fechaDevolucion > $hoy){
                        $this->setError("fecha_devolucion", "La fecha de devolución no puede ser posterior a la fecha actual");

                    }
                }
            }

    }










}


















?>