<?php

/**
 * clase para el modelo ejemplares
 * de la vista cons_ejemplares contiene los parámetros correspondiente
 * y sus restricciones
 */
class Ejemplares extends CActiveRecord {



    /**
     * Devuelve el nombre del modelo
     *
     * @return String cadena del nombre del modelo
     */
    protected function fijarNombre(): string
    {
        return "ejemplares";
    }

    /**
     * Devuelve el nombre de la tabla
     *
     * @return String de la vista del modelo de ejemplares
     */
    protected function fijarTabla(): string
    {
        return "cons_ejemplares";
    }

    /**
     * Devuelve array con los atributos
     *
     * @return Array de los atributos del modelo de ejemplares y obras
     */
    protected function fijarAtributos(): array
    {
        return array (
            "cod_obra",
            "titulo",
            "autor",
            "codigo_genero",
            "descripcion_genero",
            "distribuidora",
            "cod_categoria_obra",
            "fecha_lanzamiento",
            "descripcion",
            "foto",
            "obra_borrado",
            "cod_ejemplar",
            "cod_formato_ejemplar",
            "formato_ejemplar",
            "codigo_formato_medio",
            "descripcion_formato_medio",
            "fecha_registro",
            "borrado_ejemplar",
            "ubicacion_ejemplar", //ruta
            "estado_ejemplar", //0->reservado 1 ->libre
            "isbn_libro",
            "audio_duracion",
            "pelicula_duracion",
            "pelicula_pais",
            "pelicula_edad"
        );
    }




    /**
     * Primary key de la vista
     *
     * @return String devuelve cadena del nombre de la primary key
     */
    protected function fijarId(): string
    {
        return "cod_ejemplar";
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
            "cod_obra" => "Código obra",
            "titulo" => "Título",
            "autor" => "Autor",
            "descripcion_genero" => "Género",
            "codigo_genero" => "Código género",
            "distribuidora" => "Distribuidora",
            "cod_categoria_obra" => "Código de categoría de obra",
            "fecha_lanzamiento" => "Fecha de lanzamiento",
            "descripcion" => "Descripción",
            "foto" => "Foto",
            "obra_borrado" => "Obra borrado",
            "cod_ejemplar" => "Código de ejemplar",
            "cod_formato_ejemplar" => "Código de formato del ejemplar",
            "formato_ejemplar" => "Formato del ejemplar",
            "codigo_formato_medio" => "Código de formato medio",
            "descripcion_formato_medio" => "Tipo de formato",
            "fecha_registro" => "Fecha de registro del ejemplar",
            "borrado_ejemplar" => "Borrado de ejemplar",
            "ubicacion_ejemplar" => "Ubicación de ejemplar", //ruta
            "estado_ejemplar" => "Estado del ejemplar ", //0->reservado 1 ->libre
            "isbn_libro" => "ISBN libro",
            "audio_duracion" => "Duración del audio",
            "pelicula_duracion" => "Duración de película",
            "pelicula_pais" => "País de la película",
            "pelicula_edad" => "Edad película"

            
        );
    }
    //PARA LAS VALIDACIONES SE TENDRAN QUE COMPROBAR LOS CAMPOS CORRESPONDIENTES
    //PARA EVITAR PROBLEMAS CON LOS CAMPOS NULLS

    /**
     * Función que devuelve un array con las difernetes restricciones de 
     * modelo actual
     *
     * @return Array de restricciones
     */
    protected function fijarRestricciones(): array
    {
        return array (


            //Cod_obra 
            array("ATRI" => "cod_obra", "TIPO" => "ENTERO"),
            array("ATRI" => "cod_obra", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes elegir una obra"),
            array("ATRI" => "cod_obra", "TIPO" => "RANGO", "RANGO" => array_keys(Obras::dameObras())),


            //cod_formato_ejemplar
            array("ATRI" => "cod_formato_ejemplar", "TIPO" => "ENTERO"),
            array("ATRI" => "cod_formato_ejemplar", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes elegir un formato"),
            array("ATRI" => "cod_formato_ejemplar", "TIPO" => "RANGO", "RANGO" => array_keys(Formatos::devuelveFormatos())),



            //cod_formato_medio
            array("ATRI" => "codigo_formato_medio", "TIPO" => "ENTERO"),
            array("ATRI" => "codigo_formato_medio", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes elegir el tipo de formato"),
            array ("ATRI" => "codigo_formato_medio", "TIPO" => "FUNCION", "FUNCION"=> "validaCodFormatoMedio"),


            //fecha_registro
            array("ATRI" => "fecha_registro", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes introducir una fecha"),
            array ("ATRI" => "fecha_registro", "TIPO" => "FECHA", "DEFECTO" => new DateTime()),
            array("ATRI" => "fecha_registro", "TIPO" => "FUNCION", "FUNCION" => "validaFechaRegistro"), 


            //borrado
            array("ATRI" => "borrado_ejemplar", "TIPO" => "ENTERO",
            "DEFECTO" => 0),
            array("ATRI" => "borrado_ejemplar", "TIPO" => "RANGO",
            "RANGO" => array(0, 1), "MENSAJE" => "Debes elegir una opción disponible"),
            array("ATRI" => "borrado_ejemplar", "TIPO" => "FUNCION", "FUNCION" => "validaBorradoEjemplar"), 


            //UBICACION
            array("ATRI" => "ubicacion_ejemplar", "TIPO" => "CADENA", "TAMANIO" => 50, "MENSAJE" => "El nombre de la ruta no puede superar los 50 caracteres"),
            array("ATRI" => "ubicacion_ejemplar", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes añadir una ubicación"),
            

            //ESTADO
            array("ATRI" => "estado_ejemplar", "TIPO" => "ENTERO",
            "DEFECTO" => 0),
            array("ATRI" => "estado_ejemplar", "TIPO" => "RANGO",
            "RANGO" => array(0, 1), "MENSAJE" => "Debes elegir una opción disponible")

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
       
       
        $this->cod_obra = 0;
        $this->titulo = "";
        $this->autor = "";
        $this->codigo_genero= 0;
        $this->descripcion_genero = "";
        $this->distribuidora= "";
        $this->cod_categoria_obra = 0;
        $this->fecha_lanzamiento ="";
        $this->descripcion = "";
        $this->foto = "";
        $this->obra_borrado= 0;
        $this->cod_ejemplar = 0;
        $this->cod_formato_ejemplar = 0;
        $this->formato_ejemplar = "";
        $this->codigo_formato_medio = 0;
        $this->descripcion_formato_medio = "";
        $this->fecha_registro = "";
        $this->borrado_ejemplar = 0;
        $this->ubicacion_ejemplar =""; //ruta
        $this->estado_ejemplar = 0; //0->NO 1 ->SI
        $this->isbn_libro ="";
        $this->audio_duracion ="";
        $this->pelicula_duracion="";
        $this->pelicula_pais="";
        $this->pelicula_edad="";


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
        $this->cod_obra = intval($this->cod_obra);
        $this->codigo_genero= intval($this->codigo_genero);
        $this->descripcion_genero = GenerosObras::devuelveGenerosObras($this->codigo_genero);
        $this->cod_categoria_obra = intval($this->cod_categoria_obra);
        $this->fecha_lanzamiento =CGeneral::fechaMysqlANormal($this->fecha_lanzamiento);
        $this->obra_borrado= intval($this->obra_borrado);
        $this->cod_ejemplar = intval($this->cod_ejemplar);
        $this->cod_formato_ejemplar = intval($this->cod_formato_ejemplar);
        $this->codigo_formato_medio = intval($this->codigo_formato_medio);
        $this->fecha_registro = CGeneral::fechaMysqlANormal($this->fecha_registro);
        $this->borrado_ejemplar = intval($this->borrado_ejemplar);
        $this->estado_ejemplar = intval($this->estado_ejemplar); //0->NO 1 ->SI


        //Se comprueba el código de formato ejemplar 
        if ($this->cod_categoria_obra === 2){ // PELICULA
            $this->pelicula_duracion = $this->pelicula_duracion; //transformarlo a cadena? 
            $this->pelicula_edad = $this->pelicula_edad;
        }


        if ($this->cod_categoria_obra === 3){ //AUDIO
            $this->audio_duracion = $this->audio_duracion ; //transformarlo a cadena? 
        }

    }




    /**
     * 
     *
     * @return String
     */
    protected function fijarSentenciaInsert(): String {

        //Hacemos insert a partir de los parámetros que estan
        //en los campos de la tabla ejemplares no de la vista cons_ejemplares

        $cod_obra = intval($this->cod_obra);
        $cod_formato = intval($this->cod_formato_ejemplar);
        $cod_formato_medio = intval($this->codigo_formato_medio);
        $fecha_registro = CGeneral::fechaNormalAMysql($this->fecha_registro);
        $borrado = intval($this->borrado_ejemplar);
        $ubicacion = trim($this->ubicacion_ejemplar);
        $estado = intval($this->estado_ejemplar);


        $sentencia = "INSERT INTO `ejemplares` ( `cod_obra`,
                                                `cod_formato`, `cod_formato_medio`,
                                                `fecha_registro`, `borrado`,
                                                `ubicacion`, `estado`)

                    VALUES ( $cod_obra, $cod_formato,
                            $cod_formato_medio, '$fecha_registro',
                            $borrado, '$ubicacion', $estado)";

        return $sentencia;
    }

    /**
     * 
     *
     * @return string
     */
    protected function fijarSentenciaUpdate(): string
    {

        //Se hace update a la tabla de ejemplares
        //no a la vista
        $cod_ejemplar = intval($this->cod_ejemplar);
        $cod_obra = intval($this->cod_obra);
        $cod_formato = intval($this->cod_formato_ejemplar);
        $cod_formato_medio = intval($this->codigo_formato_medio);
        $fecha_registro = CGeneral::fechaNormalAMysql($this->fecha_registro);
        $borrado = intval($this->borrado_ejemplar);
        $ubicacion = trim($this->ubicacion_ejemplar);
        $estado = intval($this->estado_ejemplar);

        $sentencia = "UPDATE `ejemplares` SET `cod_obra` = $cod_obra,   
                                              `cod_formato` = $cod_formato,
                                              `cod_formato_medio` = $cod_formato_medio,
                                              `fecha_registro` = '$fecha_registro',
                                              `borrado` = $borrado,
                                              `ubicacion` = '$ubicacion',
                                              `estado` = $estado
        
                      WHERE `cod_ejemplar` = $cod_ejemplar ";


        return $sentencia;
    }



    /**
     * Función para validar el formato del medio
     * a partir del código del formato
     * se comprueba que existe
     * 
     * y luego que corresponde con el cod formato
     * 
     * es decir que si es digital sea mp3 y no tipo blue ray y que sea de tipo pelicula y no libro
     *
     * @return Void -> no devuelve nada, almacena el posible error en setError
     */
    public function validaCodFormatoMedio(): Void{

        //Se comprueba que el cod de formato existe

        $codFormatoMedio = intval($this->codigo_formato_medio);

        if (FormatosMedios::devuelveFormatosMedios($codFormatoMedio) === false){ //No existe
            $this->setError("codigo_formato_medio", "El tipo de formato introducido no existe");
        }
        else{ //En caso de no devolver false, 
              //existe entonces comprobamos que coincida con el tipo de obra y el tipo de formato



              $codCategoriaObra  =  intval($this->cod_categoria_obra);
              $codFormatoEjemplar = intval($this->cod_formato_ejemplar);

              if ($codCategoriaObra === 0 || $codCategoriaObra === -1){
                $this->setError("cod_categoria_obra", "Elige una categoría para la obra");

              }
              if ($codFormatoEjemplar === 0 || $codFormatoEjemplar === -1){
                $this->setError("cod_formato_ejemplar", "Elige un formato para el ejemplar");

              }

              if ($codFormatoEjemplar > 0 && $codCategoriaObra > 0){

                $arrayMedios = FormatosMedios::devuelveFormatosMedios(null, $codCategoriaObra, $codFormatoEjemplar);


                //Ahora se comprueba que el cod del formato de medio se encuentra en el array
  
                if (array_key_exists($codFormatoMedio, $arrayMedios) === false){
                  $this->setError("codigo_formato_medio", "El tipo de medio no coincide con el formato");
                }
              }


              
        }




    }


 
    /**
     * 
     *
     * @return Array
     */
    public static function devuelveEjemplares (): array{

        $ejemplares = new Ejemplares ();

        $arrayEjemplares = [];

        foreach($ejemplares->buscarTodos() as $clave => $valor){

            $hola  ="";
            $arrayEjemplares[intval($valor["cod_ejemplar"])] = $valor["titulo"];
        }

        


        return $arrayEjemplares;


    }


    public function validaBorradoEjemplar(): void{

        $borrado = intval($this->borrado_ejemplar);

        if ($borrado === 1){

            $estadoEjemplar = intval($this->estado_ejemplar);

            if ($estadoEjemplar === 1){
                $this->setError("borrado_ejemplar", "No puedes eliminar el ejemplar porque está reservado");
            }

        }


    }



    /**
     * Función para validar la fecha de registro de un ejemplar
     * se comprueba que la fecha de registro no sea anterior al 1 de enero de 1990 
     * y no sea posterior a la fecha actual
     * 
     * 
     *
     * @return Void -> no devuelve nada, almacena el posible error ne setError
     */
    public function validaFechaRegistro (): Void{

        $fechaRegistro = $this->fecha_registro;
        $fechaRegistro = DateTime::createFromFormat("d/m/Y", $fechaRegistro);

        if ($fechaRegistro !== false){

            $fechaRegistro->setTime(0, 0, 0);
            $fechaHoy = new DateTime();
            $fechaHoy->setTime(0, 0, 0);
            $hoyCadena = $fechaHoy->format("d/m/Y");

            if ($fechaRegistro > $fechaHoy){
                $this->setError("fecha_registro", "La fecha de lanzamiento no puede ser posterior al día de hoy: " . $hoyCadena);

            }

            //Ahora se comprueba que no sea anterior al 1/1/1990

            $fechaNoventas = DateTime::createFromFormat("d/m/Y", "01/01/1990");
            $fechaNoventas->setTime(0,0,0);

            if ($fechaNoventas > $fechaRegistro) {
                $this->setError("fecha_registro", "La fecha de registro no puede ser anterior al 01/01/1990");
            }

        }


    }


}





?>