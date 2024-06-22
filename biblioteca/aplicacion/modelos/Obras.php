<?php

/**
 * Clase para el modelo obras
 * de la tabla obras contiene los parámetros correspondiente
 */
class Obras extends  CActiveRecord {



    /**
     * Devuelve el nombre del modelo
     *
     * @return String cadena del nombre del modelo
     */
    protected function fijarNombre(): String{
        return "obras";
    }


    /**
     * Devuelve array con los atributos
     *
     * @return Array de los atributos del modelo de obras
     */
    protected function fijarAtributos(): array
    {
        return array (
            "cod_obra", 
            "titulo",
            "autor",
            "cod_genero",
            "distribuidora",
            "cod_categoria_obra",
            "fecha_lanzamiento",
            "descripcion",
            "foto",
            "borrado"
        );
    }


    /**
     * Devuelve el nombre de la tabla
     *
     * @return String de la vista del modelo de obras
     */
    protected function fijarTabla(): string
    {
        return "obras";
    }


    /**
     * Primary key de la vista
     *
     * @return String devuelve cadena del nombre de la primary key
     */
    protected function fijarId(): string
    {
        return "cod_obra";
    }


    /**
     * Devuelve un array
     * de las diferentes parámetros
     * que tiene el modelo de obras
     *
     * @return Array con descripción de los parámetros
     */
    protected function fijarDescripciones(): array
    {
        return array (
                        "cod_obra" => "Código de obra",
                        "titulo" => "Título",
                        "autor" => "Autor",
                        "cod_genero" => "Género",
                        "distribuidora" => "Distribuidora",
                        "cod_categoria_obra" => "Categoría obra",
                        "fecha_lanzamiento" => "Fecha de lanzamiento",
                        "descripcion" => "Descripción",
                        "foto" => "Foto",
                        "borrado" => "Borrado"
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

            //titulo
            array("ATRI" => "titulo", "TIPO" => "REQUERIDO", "MENSAJE" => "Debe introducir un título para la obra"),
            array ("ATRI" => "titulo", "TIPO" => "CADENA", "TAMANIO" => 50, "MENSAJE" => "El título no puede superar los 50 caracteres"),
            
            //autor
            array ("ATRI" => "autor", "TIPO" => "CADENA", "TAMANIO" => 50, "La longitud del autor debe ser 50 caracteres como máximo"),
            array ("ATRI" => "autor", "TIPO" => "REQUERIDO", "MENSAJE" => "El autor es obligatorio"),

            //distribuidora
            array ("ATRI" => "distribuidora", "TIPO" => "CADENA", "TAMANIO" => 50, "MENSAJE" => "La distribuidora debe ser 50 caracteres como máximo"),
            array ("ATRI" => "distribuidora", "TIPO" => "REQUERIDO", "MENSAJE" => "La distribuidora es obligatoria"),

            //cod_categoria_obra
            array ("ATRI" => "cod_categoria_obra", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes elegir una categoría para la obra"),
            array ("ATRI" => "cod_categoria_obra", "TIPO" => "ENTERO"),
            array("ATRI" => "cod_categoria_obra", "TIPO" => "RANGO",
            "RANGO" => array_keys(CategoriasObras::dameCategoriasObras(null)), "MENSAJE" => "Debes elegir una categorías disponible"),


            //cod_genero
            array ("ATRI" => "cod_genero", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes elegir un género para la obra"),
            array ("ATRI" => "cod_genero", "TIPO" => "ENTERO"),
            array ("ATRI" => "cod_genero", "TIPO" => "FUNCION" , "FUNCION" => "validarGeneroObra"),
            
            
            //fecha_lanzamiento
            array("ATRI" => "fecha_lanzamiento", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes introducir una fecha"),
            array ("ATRI" => "fecha_lanzamiento", "TIPO" => "FECHA", "DEFECTO" => new DateTime()), //se coge la fecha por defecto de hoy
            array ("ATRI" => "fecha_lanzamiento", "TIPO" => "FUNCION" , "FUNCION" => "validarFechaPosterior"), //La fecha de lanzamiento, no puede ser posterior a hoy

            //descripcion
            array ("ATRI" => "descripcion", "TIPO" => "CADENA", "TAMANIO" => 500, "MENSAJE" => "La descripción no puede superar los 500 caracteres"),
            array ("ATRI" => "descripcion", "TIPO" => "REQUERIDO", "MENSAJE" => "Debes introducir una descripción de la obra"),

            //foto
            array ("ATRI" => "foto", "TIPO" => "CADENA", "TAMANIO" => 40, "DEFECTO" => "libro.png"),

            //borrado
            array("ATRI" => "borrado", "TIPO" => "ENTERO",
            "DEFECTO" => 0),
            array("ATRI" => "borrado", "TIPO" => "RANGO",
            "RANGO" => array(0, 1), "MENSAJE" => "Debes elegir una opción disponible"),
            array ("ATRI" => "borrado", "TIPO" => "FUNCION" , "FUNCION" => "validaBorrado"), //La fecha de lanzamiento, no puede ser posterior a hoy


        );
    }




    /**
     * Función que inicializa los diferentes parámetros
     * tras darle memoria al modelo de obras
     *
     * @return Void, nos devuelve inicializa valores
     */
    protected function afterCreate(): void
    {
        $this->cod_obra = 0;
        $this->titulo = "";
        $this->autor = "";
        $this->cod_genero = 0;  
        $this->distribuidora = "";
        $this->cod_categoria_obra = 1;  
        $this->fecha_lanzamiento = new DateTime();
        $this->descripcion = "";
        $this->foto = "obra.png";
        $this->borrado = 0; 
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
        
        //conversión de fecha
        $fecha = $this->fecha_lanzamiento;
        $fecha = CGeneral::fechaMysqlANormal($fecha);
        $this->fecha_lanzamiento = $fecha;


        //pasamos cadenas a entero/real, en caso de los números
        $this->cod_obra = intval($this->cod_obra);
        $this->cod_genero = intval( $this->cod_genero);
        $this->cod_categoria_obra = intval($this->cod_categoria_obra);
        $this->borrado = intval($this->borrado);
         

    }


    /**
     * Devuelve una cadena INSERT
     * para la tabla actual
     * 
     * Saneamos los datos con addSlashes para las cadenas
     * o los hacemos a su tipos como los enteros
     *
     * @return String cadena de la sentencia insert
     */
    protected function fijarSentenciaInsert(): String
    {

        $titulo = trim($this->titulo);
        $titulo = CGeneral::addSlashes($titulo);

        $autor = trim($this->autor);
        $autor = CGeneral::addSlashes($autor);

        $codGenero = intval($this->cod_genero);

        $distribuidora = trim($this->distribuidora);
        $distribuidora = CGeneral::addSlashes($distribuidora);

        $codCategoriaObra = intval($this->cod_categoria_obra);
        $fechaLanzamiento = CGeneral::fechaNormalAMysql($this->fecha_lanzamiento);


        $descripcion = trim($this->descripcion);
        $descripcion = CGeneral::addSlashes($descripcion);

        $foto = trim($this->foto);
        $foto = CGeneral::addSlashes($foto);

        $borrado = intval($this->borrado);

        
        $sentencia = "INSERT INTO `obras` (`titulo`, `autor`, `cod_genero`,
                                            `distribuidora`, `cod_categoria_obra`, 
                                            `fecha_lanzamiento`, `descripcion`, `foto`,
                                            `borrado`)

                        VALUES ('$titulo', '$autor', '$codGenero',
                                '$distribuidora', '$codCategoriaObra',
                                '$fechaLanzamiento', '$descripcion',
                                '$foto', '$borrado')";

        return $sentencia;

    }
    
    
    
    /**
     * Devuelve la sentencia SQL Update que se ejecutará cuando se guarde el registro
     *
     * @return String de la cadena UPDATE
     */
    protected function fijarSentenciaUpdate(): string
    {
        $codObra = intval($this->cod_obra);
        
        $titulo = trim($this->titulo);
        $titulo = CGeneral::addSlashes($titulo);

        $autor = trim($this->autor);
        $autor = CGeneral::addSlashes($autor);

        $codGenero = intval($this->cod_genero);

        $distribuidora = trim($this->distribuidora);
        $distribuidora = CGeneral::addSlashes($distribuidora);

        $codCategoriaObra = intval($this->cod_categoria_obra);
                
        $fechaLanzamiento = CGeneral::fechaNormalAMysql($this->fecha_lanzamiento);

        $descripcion = trim($this->descripcion);
        $descripcion = CGeneral::addSlashes($descripcion);

        $foto = trim($this->foto);
        $foto = CGeneral::addSlashes($foto);

        $borrado = intval($this->borrado);
        

        $sentencia = "UPDATE `obras` SET `titulo` = '$titulo', `autor` = '$autor', 
                                        `cod_genero` = $codGenero, `distribuidora` = '$distribuidora', 
                                        `cod_categoria_obra` = $codCategoriaObra, `fecha_lanzamiento` = '$fechaLanzamiento', 
                                        `descripcion` = '$descripcion', `foto` = '$foto',
                                        `borrado` = $borrado
                                        
                                    WHERE `cod_obra` = $codObra";

        return $sentencia;

    }


    /**
     * Método del modelo Obra que se usa para verificar el género de la obra
     * para ello primero verificamos el género de la obra existe en GenerosObras
     * luego se verifica si ese género introducido corresponde con el tipo de obra
     * 
     *
     * @return Void no devuelve nada, solo carga el método set error
     */
    public function validarGeneroObra ():Void{


        $tipoObra = $this->cod_categoria_obra;
        $geneObra = $this->cod_genero;

        //Primero compruebo que el código existe
       if( GenerosObras::devuelveGenerosObras($geneObra, null) === false){
            $this->setError("cod_genero", "El género introducido no existe en la BBDD");
       }
       else{ //Se comprueba si coincide

            $arrayGenerosObras = GenerosObras::devuelveGenerosObras(null, $tipoObra);

            if ($arrayGenerosObras === false) {
                $this->setError("cod_genero", "El género introducido no coincide con la categoría de obra");

            }            
       }
    }


    /**
     * Se comprueba que la obra ligada al ejemplar no esté reservada
     *
     * @return void
     */
    public function validaBorrado():void{
        

        if (intval($this->borrado) === 1){

            $codObra = $this->cod_obra;

            $ejemplares = new Ejemplares ();

            $ejemplaresDeObraSinDevolver = $ejemplares->buscarTodosNRegistros(["where" => " `cod_obra` = $codObra AND `estado_ejemplar` = 1"]);



            if ($ejemplaresDeObraSinDevolver !== 0){
                $this->setError("borrado", "No puedes borrar la obra, hasta que los ejemplares de dicha obra estén disponibles");
            }
        }


    }

    /**
     * Función del modelo de obras para validar
     * que la fecha de lanzamiento no sea posterior
     * a la fecha actual, en caso de serlo, se lanza excepción
     *
     * @return Void -> no devuelve nada, almacena el error  en el método setERROR
     */
    public function validarFechaPosterior(): void
    {


        $fechaLanzamiento = $this->fecha_lanzamiento;
        $fechaLanzamiento =  DateTime::createFromFormat("d/m/Y", $fechaLanzamiento);

        if ($fechaLanzamiento !== false) {
            $fechaLanzamiento->setTime(0, 0, 0);
            $fechaHoy = new DateTime();
            $fechaHoy->setTime(0, 0, 0);
            $hoyCadena = $fechaHoy->format("d/m/Y");

            if ($fechaLanzamiento > $fechaHoy) {
                $this->setError("fecha_lanzamiento", "La fecha de lanzamiento no puede ser posterior al día de hoy: " . $hoyCadena);
            }
        }
    }



    //COMENTAR
    public static function dameObras (){

        $arrayObras = [];


        $obras = new Obras ();

        $arrayCategoiras = CategoriasObras::dameCategoriasObras();

        foreach($obras->buscarTodos() as $clave => $valor){

            $arrayObras[intval($valor["cod_obra"])] = $valor["titulo"] . " - " . mb_strtoupper($arrayCategoiras[intval($valor["cod_categoria_obra"])]);

        }


        return $arrayObras;

    }


    public static function dameObraPorCod (int $cod){

        $arrayObras = [];
        $obras = new Obras ();

        foreach($obras->buscarTodos() as $clave => $valor){
            $codActual = intval($valor["cod_categoria_obra"]);

            if ($codActual === $cod){
                $arrayObras[intval($valor["cod_obra"])] = $valor["titulo"];

            }

        }

        return $arrayObras;


    }

}


    

?>