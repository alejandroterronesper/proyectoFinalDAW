<?php

/**
 * 
 */
class Peliculas extends CActiveRecord {


    /**
     * 
     *
     * @return string
     */
    protected function fijarNombre(): string
    {
        return "peliculas";
    }

    /**
     * 
     *
     * @return array
     */
    protected function fijarAtributos(): array
    {
        return array  (
            "cod_pelicula",
            "cod_ejemplar",
            "duracion",
            "pais",
            "calificacion_edad",
            "cod_formato_medio"
        );
    }
    
    
    /**
     * 
     *
     * @return string
     */
    protected function fijarTabla(): string
    {
        return "peliculas";
    }

    /**
     * 
     *
     * @return string
     */
    protected function fijarId(): string
    {
        return "cod_pelicula";
    }


    /**
     * 
     *
     * @return array
     */
    protected function fijarDescripciones(): array
    {
        return array (
            "cod_pelicula" => "Código de película",
            "cod_ejemplar" => "Código de ejemplar",
            "duracion" => "Duración",
            "pais" => "País",
            "calificacion_edad" => "Calificación de edad",
            "cod_formato_medio" => "Código de formato medio"
        );
    }


    /**
     * 
     *
     * @return array
     */
    protected function fijarRestricciones(): array
    {
        return array (

         
            //cod_ejemplar
            array ("ATRI" => "cod_ejemplar", "TIPO" => "ENTERO"),
            array ("ATRI" => "cod_ejemplar", "TIPO" => "REQUERIDO", "MENSAJE" => "El libro debe estar asignado a un ejemplar "),
            // array("ATRI" => "cod_ejemplar", "TIPO" => "RANGO", "RANGO" => array_keys(Ejemplares::devuelveEjemplares())),

            
            //duracion
            array ("ATRI" => "duracion", "TIPO" => "CADENA"),
            array ("ATRI" => "duracion", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes introducir una duración"),
            array ("ATRI" => "duracion", "TIPO" => "FUNCION", "FUNCION" => "validaDuracion"),

            
            //pais
            array ("ATRI" => "pais", "TIPO" => "CADENA"),
            array("ATRI" => "pais", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes introducir un país"),
            array ("ATRI" => "pais", "TIPO" => "RANGO", 
            "RANGO" => Peliculas::devuelvePaisesPeliculas(),
            "MENSAJE" => "Elige uno de los países disponibles"),
            
            //calificacion edad
            array ("ATRI" => "calificacion_edad", "TIPO" => "CADENA"),
            array ("ATRI" => "calificacion_edad", "TIPO" => "REQUERIDO", "MENSAJE" => "Introduce una clasificación de edad"),
            array ("ATRI" => "calificacion_edad", "TIPO" => "FUNCION", "FUNCION" => "validaEdades"),

                        
            //cod_formato_medio
            array ("ATRI" => "cod_formato_medio", "TIPO" => "ENTERO"),
            array("ATRI" => "cod_formato_medio", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes elegir el tipo de formato"),

        );
    }


    /**
     * 
     *
     * @return void
     */
    protected function afterCreate(): void
    {
        $this->cod_pelicula = 0;
        $this->cod_ejemplar = 0;
        $this->duracion = "00:00:00";
        $this->pais = "-1";
        $this->califiacion_edad = "-1";
        $this->cod_formato_medio = 0;
    }


    /**
     * 
     *
     * @return void
     */
    protected function afterBuscar(): void
    {
        $this->cod_pelicula = intval($this->cod_pelicula);
        $this->cod_ejemplar = intval($this->cod_ejemplar);
        $this->duracion = $this->duracion;
        $this->cod_formato_medio = intval($this->cod_formato_medio);
    }



    /**
     * 
     *
     * @return string
     */
    protected function fijarSentenciaInsert(): string
    {
        $cod_ejemplar = intval($this->cod_ejemplar);
        $duracion = CGeneral::addSlashes($this->duracion);
        $pais = CGeneral::addSlashes($this->pais);
        $calificacion_edad = CGeneral::addSlashes($this->calificacion_edad);
        $cod_formato_medio = intval($this->cod_formato_medio);
    
    
        $sentencia = "INSERT INTO `peliculas` (`cod_ejemplar`, `duracion`, 
                                                `pais`, `calificacion_edad`,
                                                `cod_formato_medio`)
                                                
                                                
                        VALUES ($cod_ejemplar, '$duracion', '$pais', '$calificacion_edad',
                                 $cod_formato_medio)";


        return $sentencia;
    
    
    }




    /**
     * 
     *
     * @return string
     */
    protected function fijarSentenciaUpdate(): string
    {
        $cod_pelicula = intval($this->cod_pelicula);
        $cod_ejemplar = intval($this->cod_ejemplar);
        $duracion = CGeneral::addSlashes( $this->duracion);
        $pais = CGeneral::addSlashes($this->pais);
        $calificacion_edad =  CGeneral::addSlashes($this->calificacion_edad);
        $cod_formato_medio = intval($this->cod_formato_medio);


        $sentencia = "UPDATE `peliculas` SET  `cod_ejemplar` = $cod_ejemplar,
                                            `duracion` = '$duracion',
                                            `pais` = '$pais',
                                            `calificacion_edad` = '$calificacion_edad',
                                            `cod_formato_medio` = $cod_formato_medio
                                            
                        WHERE `cod_pelicula` = $cod_pelicula";

        return $sentencia;
    }


    public function validaEdades (){

        $arrayEdades = ["Todas las edades" => "Todas las edades", "+7"=> "+7", "+12"=> "+12",
        "+16"=>"+16", "+18" => "+18"];

        $cali = $this->calificacion_edad;


        if (in_array($cali, $arrayEdades) === false){
            $this->setError("calificacion_edad", "Debes elegir entre una de las calificaciones propuestas");
        }
        
    }

    
    /**
     * Función que valida la duración de una película
     * se asegura de que esté en formato hh:mm:ss
     * @return void
     */
    public function validaDuracion (){

        $exp = "/[0-2]?[0-9]:[0-5]?[0-9]:[0-5]?[0-9]/";

        if(preg_match($exp,$this->duracion)){
            $array = mb_split(":", $this->duracion);
            $var=mb_substr("00".$array[0],-2).":".
                mb_substr("00".$array[1],-2).":".
                mb_substr("00".$array[2],-2);
            $this->duracion = $var;

        }else{
            $this->setError("duracion", "Formato de duración incorrecto, debe ser hh:mm:ss ");
        }

      
        
    }


    /**
     * Método estático que devuelve un array con los
     * países más comunes de donde se hacen las películas
     *
     * @return Array de paises
     */
    public static function devuelvePaisesPeliculas (): array {


        $paises = [
            "USA" => "USA",
            "China" => "China",
            "Corea del Sur" => "Corea del Sur",
            "España" => "España",
            "Alemania" => "Alemania",
            "Francia" => "Francia",
            "Japón" => "Japón",
            "Reino Unido" => "Reino Unido"
        ];


        return $paises;
    }


    public static function devuelveEdades (): array {

       $edad =  ["Todas las edades" => "Todas las edades", "+7"=> "+7", "+12"=> "+12",
        "+16"=>"+16", "+18" => "+18"];

        return $edad;

    }




   
}















?>