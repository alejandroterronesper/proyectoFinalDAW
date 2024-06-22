<?php




class Formatos extends CActiveRecord {



    protected function fijarNombre(): string
    {
        return "formatos";
    }


    protected function fijarTabla(): string
    {
        return "formatos";
    }


    protected function fijarAtributos(): array
    {
        return array ("cod_formato", 
                      "descripcion"
                    );
    }



    protected function fijarId(): string
    {
        return "cod_formato";
    }



    protected function fijarDescripciones(): array
    {
        return array ("cod_formato" => "Código formato",
                     "descripcion" => "Descripción"
                    );
    }


    protected function fijarRestricciones(): array
    {
        return array (
            array ("ATRI" => "cod_formato", "TIPO" => "ENTERO"),
            array ("ATRI" => "descripcion", "TIPO" => "CADENA")
        );
    }




    protected function afterCreate(): void
    {
        $this->cod_formato = 0;
        $this->cod_descripcion = "";
    }



    protected function afterBuscar(): void
    {
        $this->cod_formato = intval($this->cod_formato);
    }



    public static function devuelveFormatos ()
    {

        $formatos = new Formatos ();


        $arrayFormatos = [];


        foreach ($formatos->buscarTodos() as $clave => $valor){
            $arrayFormatos[intval($valor["cod_formato"])] = $valor["descripcion"];
        }


        return $arrayFormatos;

    }

}









?>