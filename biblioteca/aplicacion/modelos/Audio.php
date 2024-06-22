<?php


/**
 * 
 */
class Audio extends CActiveRecord {


    /**
     * 
     *
     * @return string
     */
    protected function fijarNombre(): string
    {
        return "audio";
    }



    /**
     * 
     *
     * @return array
     */
    protected function fijarAtributos(): array
    {
        return array (
            "cod_audio",
            "cod_ejemplar",
            "duracion", 
            "cod_formato_medio"
        );
    }



    protected function fijarTabla(): string
    {
        return "audio";
    }




    protected function fijarId(): string
    {
        return "cod_audio";
    }



    protected function fijarDescripciones(): array
    {
        return array (
            "cod_audio" => "Código de audio",
            "cod_ejemplar" => "Código de ejemplar",
            "duracion" => "Duración",
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
        $this->cod_audio = 0;
        $this->cod_ejemplar = 0;
        $this->duracion = "00:00:00";
        $this->cod_formato_medio = 0;
    }

    /**
     * 
     *
     * @return void
     */
    protected function afterBuscar(): void
    {
        $this->cod_audio = intval($this->cod_audio);
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
        
        $cod_ejemplar =  intval($this->cod_ejemplar);
        $duracion =  trim($this->duracion);
        $cod_formato_medio =  intval($this->cod_formato_medio);

        $sentencia = "INSERT INTO `audio` (`cod_ejemplar`, `duracion`,
                                            `cod_formato_medio`)
                                            
                        VALUES  ($cod_ejemplar,'$duracion', $cod_formato_medio)";

        return $sentencia;

    }



    /**
     * 
     *
     * @return string
     */
    protected function fijarSentenciaUpdate(): string
    {        
        
        $cod_audio = intval($this->cod_audio);
        $cod_ejemplar =  intval($this->cod_ejemplar);
        $duracion =  trim($this->duracion);
        $cod_formato_medio =  intval($this->cod_formato_medio);



        $sentencia = "UPDATE `audio` SET `cod_ejemplar` = $cod_ejemplar,
                                     `duracion` = '$duracion',
                                     `cod_formato_medio` = $cod_formato_medio
                                    
                        WHERE `cod_audio` = $cod_audio";


        return $sentencia;
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

}







?>