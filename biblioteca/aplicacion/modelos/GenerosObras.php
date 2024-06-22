<?php

class GenerosObras extends CActiveRecord {



    protected function fijarNombre(): string
    {
        return "GenerosObras";
    }

    protected function fijarTabla(): string
    {
        return "generos_obras";
    }


    protected function fijarId(): string
    {
        return "cod_genero_obra";
    }


    protected function fijarAtributos(): array
    {
        return array (
            "cod_genero_obra",
            "cod_categorias_obras",
            "descripcion"
        );
    }




    protected function fijarRestricciones(): array
    {
        return array (

            //cod_genero_obra
            array ("ATRI" => "cod_genero_obra", "TIPO" => "ENTERO"),

            //cod_categorias_obras
            array ("ATRI" => "cod_categorias_obras", "TIPO" => "ENTERO"),

            //descripcion
            array ("ATRI" => "descripcion", "TIPO" => "CADENA")


        );
    }


    /**
     * 
     *
     * @return void
     */
    protected function afterCreate(): void
    {
        $this->cod_genero_obra = 0;
        $this->cod_categorias_obras = 0;
        $this->descripcion = "";
    }


    /**
     * 
     *
     * @return void
     */
    protected function afterBuscar(): void
    {
        $this->cod_genero_obra = intval($this->cod_genero_obra);
        $this->cod_categorias_obras = intval($this->cod_categorias_obras);
    }


    /**
     * 
     *
     * @return Array
     */
    public static function dameTodosGeneros(): array{


        $generosObras = new GenerosObras ();

        $arrayGenerosObras = [];
        foreach($generosObras->buscarTodos() as $clave => $valor){

            $arrayGenerosObras[intval($valor["cod_genero_obra"])] = $valor["descripcion"];
        }


        return $arrayGenerosObras;


    }



    /**
     * 
     *
     * @param integer|null $cod_genero_obra
     * @param integer|null $cod_categorias_obras
     * @return void
     */
    public static function devuelveGenerosObras (? int $cod_genero_obra = null, ? int $cod_categorias_obras = null){

        $generosObras = new GenerosObras ();


        $arrayGenerosObras = [];


        foreach($generosObras->buscarTodos() as $clave => $valor){

            $arrayGenerosObras[intval($valor["cod_genero_obra"])] = [
                "cod_categorias_obras" => intval($valor["cod_categorias_obras"]),
                "descripcion" => $valor["descripcion"]
            ];
        }



        if ($cod_genero_obra !== null){

            if (isset($arrayGenerosObras[$cod_genero_obra])){
                return $arrayGenerosObras[$cod_genero_obra]["descripcion"];
            }
            else{
                return false;
            }

        }
        else{
            if ($cod_categorias_obras !== null){

                $arrayFiltrado = [];


                foreach ($arrayGenerosObras as $clave => $valor){

                    if ($valor["cod_categorias_obras"] === $cod_categorias_obras){
                        $arrayFiltrado[$clave] = $valor["descripcion"];
                    }
                }

                if (count($arrayFiltrado) === 0){
                    return false;
                }
                else{
                    return $arrayFiltrado;
                }
            }
            else{
                return false;
            }


        }

    }
}














?>