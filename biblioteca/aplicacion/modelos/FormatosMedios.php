<?php


/**
 *  Rellenar
 */
class FormatosMedios extends CActiveRecord {



    protected function fijarNombre(): string
    {
        return "FormatosMedios";
    }



    protected function fijarTabla(): string
    {
        return "formato_medios";
    }


    protected function fijarId(): string
    {
        return "cod_formato_medio";
    }


    protected function fijarAtributos(): array
    {
        return array (
            "cod_formato_medio",
            "cod_categoria_obra",
            "cod_formato",
            "descripcion"
        );
    }


    protected function fijarRestricciones(): array
    {
        return array (

            //cod_formato_medio
            array ("ATRI" => "cod_formato_medio", "TIPO" => "ENTERO"),


            //cod_categoria_obra
            array ("ATRI" => "cod_categoria_obra", "TIPO" => "ENTERO"),


            //cod_formato
            array ("ATRI" => "cod_formato", "TIPO" => "ENTERO"),


            //descripcion
            array("ATRI" => "descripcion", "TIPO" => "CADENA", "TAMANIO" => 50)
        );
    }



    protected function afterCreate(): void
    {
        $this->cod_formato_medio = 0;
        $this->cod_categoria_obra = 0;
        $this->cod_formato = 0;
        $this->descripcion = "";
    }


    protected function afterBuscar(): void
    {
        $this->cod_formato_medio = intval($this->cod_formato_medio);
        $this->cod_categoria_obra = intval( $this->cod_categoria_obra);
        $this->cod_formato = intval($this->cod_formato);
    }


    public static function devuelveFormatosPorCategoria (int $codCategoria){

        $formatosMedios = new FormatosMedios ();
        $array =[];
        foreach($formatosMedios->buscarTodos() as $clave => $valor){

                $prueba = $valor;
                $codCategoriaObraActual = intval($valor["cod_categoria_obra"]);
                if ($codCategoriaObraActual === $codCategoria){
                    $array[intval($valor["cod_formato_medio"])] = $valor["descripcion"];
                }

        }


        return $array;


    }

    /**
     * 
     *
     * @param integer|null $cod_formato_medio
     * @param integer|null $cod_categoria_obra
     * @param integer|null $cod_formato
     * @return 
     */
    public static function devuelveFormatosMedios(? int $cod_formato_medio = null,
                                                    ? int $cod_categoria_obra = null,
                                                    ? int $cod_formato = null){



        $formatosMedios = new FormatosMedios ();


        $arrayFormatosMedios = [];

        foreach($formatosMedios->buscarTodos() as $clave => $valor){
            $arrayFormatosMedios[intval($valor["cod_formato_medio"])] = 
            [
                "cod_categoria_obra" => intval($valor["cod_categoria_obra"]),
                "cod_formato" => intval($valor["cod_formato"]),
                "descripcion" => $valor["descripcion"]
            ];
        }


        //si me envian el cod formato medio
        if ($cod_formato_medio !== null){

            if (isset($arrayFormatosMedios[$cod_formato_medio])){
                return $arrayFormatosMedios[$cod_formato_medio];
            }
            else{
                return false;
            }
        }
        else{ //Si no lo envia, comprobamos resto de parámetros

            $arrayRaro = [];
            
            
            //pregunto por el tipo de categoria obra
            if ($cod_categoria_obra !== null && $cod_formato !== null){

                foreach($arrayFormatosMedios as $clave => $valor){

                    if ($valor["cod_categoria_obra"] === $cod_categoria_obra && $valor["cod_formato"] === $cod_formato){
                        $arrayRaro[$clave] = $valor["descripcion"]; //primary key => descripcion
                    }
                }
            }

            if (count($arrayRaro) === 0){
                return false;
            }
            else{
                return $arrayRaro;
            }
        }

    }
     
}


?>