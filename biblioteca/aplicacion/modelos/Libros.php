<?php


/**
 * 
 */
class Libros extends CActiveRecord {



    protected function fijarNombre(): string
    {
        return "libros";
    }


    protected function fijarAtributos(): array
    {
        return array (
            "cod_libro",
            "cod_ejemplar",
            "isbn",
            "cod_formato_medio"
        );
    }



    protected function fijarTabla(): string
    {
        return "libros";
    }



    protected function fijarId(): string
    {
        return "cod_libro";
    }


    protected function fijarDescripciones(): array
    {
        return array (
            "cod_libro" => "Código libro",
            "cod_ejemplar" => "Código de ejemplar",
            "isbn" => "ISBN",
            "cod_formato_medio" => "Código de formato de medio"
        );
    }


    protected function fijarRestricciones(): array
    {
        return array (

            //cod_libro
            array ("ATRI" => "cod_libro","TIPO" => "ENTERO"),

            //cod_ejemplar
            array ("ATRI" => "cod_ejemplar", "TIPO" => "ENTERO"),
            array ("ATRI" => "cod_ejemplar", "TIPO" => "REQUERIDO", "MENSAJE" => "El libro debe estar asignado a un ejemplar "),
            // array("ATRI" => "cod_ejemplar", "TIPO" => "RANGO", "RANGO" => "validaLibroCod"),


            //ISBN
            array("ATRI" => "isbn", "TIPO" => "REQUERIDO", "MENSAJE" => "EL ISBN es obligatorio para el libro"),
            array ("ATRI" => "isbn", "TIPO" => "CADENA", "TAMANIO" => 17, "MENSAJE"=> "El Código ISBN debe tener una longitud de 17 caracteres"),
            array ("ATRI" => "isbn", "TIPO" => "FUNCION", "FUNCION" => "validaISBN13"),


            //cod_formato_medio
            array ("ATRI" => "cod_formato_medio", "TIPO" => "ENTERO"),
            array("ATRI" => "cod_formato_medio", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes elegir el tipo de formato"),



        );
    }



    protected function afterCreate(): void
    {
        $this->cod_libro = 0;
        $this->cod_ejemplar = 0;
        $this->isbn = "";
        $this->cod_formato_medio = 0;
    }


    protected function afterBuscar(): void
    {
        $this->cod_libro = intval($this->cod_libro);
        $this->cod_ejemplar = intval($this->cod_ejemplar);
        $this->cod_formato_medio = intval($this->cod_formato_medio);
    }



    protected function fijarSentenciaInsert(): string
    {
        $isbn = CGeneral::addSlashes($this->isbn);
        $cod_ejemplar = intval($this->cod_ejemplar);
        $cod_formato_medio = intval($this->cod_formato_medio);
    
        $sentencia = "INSERT INTO `libros` (`cod_ejemplar`,
                                            `isbn`,
                                            `cod_formato_medio`)
        
                    VALUES ($cod_ejemplar, '$isbn', $cod_formato_medio)";


        return $sentencia;
    
    }



    protected function fijarSentenciaUpdate(): string
    {
        $cod_libro = intval($this->cod_libro);
        $isbn = CGeneral::addSlashes($this->isbn);
        $cod_ejemplar = intval($this->cod_ejemplar);
        $cod_formato_medio = intval($this->cod_formato_medio);
    
    
    
        $sentencia = "UPDATE  `libros` SET  `cod_ejemplar` = $cod_ejemplar,
                                            `isbn` = '$isbn',
                                            `cod_formato_medio` = $cod_formato_medio
                                        
                               WHERE `cod_libro` = $cod_libro";

        return $sentencia;

    }

    public function validaLibroCod (){
        $arrayEjemplares = Ejemplares::devuelveEjemplares();

        if (in_array(intval($this->cod_ejemplar),   array_keys($arrayEjemplares) )){
            $this->setError("cod_ejemplar", "El ejemplar seleccionado no existe");
        }


    }



    /**
     * Función que valida el código de ISBN-13
     * a través de una expresión regular
     * 
     * se valida si la cadena que se recibe tiene longitud de 17
     * 
     * y si cumple con el formato 978-NN-NNN-NNNN-NN
     *
     * @return Void, no devuelve nada
     */
    public function validaISBN13(): void {


        //primero validamos longitud, el isbn tiene 13 numeros y 4 guiones, debe tener longitud 17
        if (mb_strlen ($this->isbn) < 17 || mb_strlen ($this->isbn) > 17){
            $this->setError("isbn", "EL ISBN debe tener una longitud de 17 caracteres, 13 números y 4 guiones");
        }


        //Ahora validamos formato con expresión regular
        $exReg = "/([9]{1}[7]{1}[8]{1}-[0-9]{2}-[0-9]{3}-[0-9]{4}-[0-9]{1})/";

        if (!preg_match_all($exReg, $this->isbn)){
            $this->setError("isbn", "Formato de ISBN incorrecto debe ser: 978-NN-NNN-NNNN-NN");
        }

    }

}




?>