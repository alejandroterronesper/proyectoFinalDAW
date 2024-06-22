<?php


/**
 *
 */
class CategoriasObras extends CActiveRecord {
 /**
     * Devuelve el nombre del modelo
     *
     * @return String cadena del nombre del modelo
     */
    protected function fijarNombre(): String{
        return "categoriasObras";
    }


    /**
     * Devuelve array con los atributos
     *
     * @return Array de los atributos del modelo de obras
     */
    protected function fijarAtributos(): array
    {
        return array (
            "cod_categoria_obra", 
            "descripcion",
        );
    }


    /**
     * Devuelve el nombre de la tabla
     *
     * @return String de la vista del modelo de obras
     */
    protected function fijarTabla(): string
    {
        return "categorias_obras";
    }


    /**
     * Primary key de la vista
     *
     * @return String devuelve cadena del nombre de la primary key
     */
    protected function fijarId(): string
    {
        return "cod_categoria_obra";
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
            //cod
            array("ATRI" => "cod_categoria_obra", "TIPO" => "ENTERO"),
            array("ATRI" => "descripcion", "TIPO" => "CADENA", "TAMANIO" => 30),
        );
    }


    /**
     * Función que inicializa los diferentes parámetros
     * tras darle memoria al modelo de categorias obras
     *
     * @return Void, nos devuelve inicializa valores
     */
    protected function afterCreate(): void
    {
        $this->cod_categoria_obra = 0;
        $this->descripcion = "";
    }



    /**
     * 
     *
     * @return void
     */
    protected function afterBuscar(): void
    {
        $this->cod_categoria_obra = intval($this->cod_categoria_obra);
    }




    /**
     * Método estático de la clase categorias obras
     * 
     * recibe como parametro cod_categoria_obra, puede ser nulo o entero
     * 
     * Se comprueba si es nulo, en caso de serlo, se devuelve un array con las categorias
     * si es un entero, comprobamos que la clave existe, si existe, devolvemos la descripcion
     * si no se devuelve un false
     *
     * @param integer|null $cod_categoria_obra
     * @return Array|String|Null
     */
    public static function dameCategoriasObras(?int $cod_categoria_obra = null):Array|String|Null{


        $objCategoriasObras = new CategoriasObras ();

        $arrayCategoriasObras = [];

        foreach ($objCategoriasObras->buscarTodos() as $clave => $valor){
            $arrayCategoriasObras[intval($valor["cod_categoria_obra"])] = $valor["descripcion"];
        }


        if ($cod_categoria_obra === null){
            return $arrayCategoriasObras;
        }
        else{

            if (isset($arrayCategoriasObras[$cod_categoria_obra])){
                return $arrayCategoriasObras[$cod_categoria_obra];
            }
            else{
                return false;
            }
        }
    }
}





?>