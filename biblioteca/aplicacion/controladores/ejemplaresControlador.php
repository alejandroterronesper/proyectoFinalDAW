<?php

use PgSql\Lob;

/**
 *  Controlador del modelo de ejemplares contiene las siguientes acciones
 * index: que nos muestra un crud de ejemplares
 * ver ejemplar
 * modficiar ejemplar
 * borrar ejemplar
 * añadir ejemplar
 */
class ejemplaresControlador extends CControlador
{



    /**
     * Acción index de ejemplares, 
     * controlamos usuario registrado, si no se envia al login
     * Comprobamos sus permisos correspondientes
     * Si está registrado, se le muestra una vista
     * que tiene el crud de la tabla ejempalres, se muestran los diferentes ejemplares
     * y sus opciones de ver, modificar y borrar
     * Además de poder añadir ejemplares nuevos
     *
     * @return Void -> No devuelve nada, imprime una vista
     */
    public function accionIndexEjemplares(): Void
    {

        if (Sistema::app()->Acceso()->hayUsuario()  === true) {


            if (
                Sistema::app()->Acceso()->puedePermiso(1)  &&
                Sistema::app()->Acceso()->puedePermiso(9)

            ) {

                $this->barraUbi = [
                    [
                        "texto" => "inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de Ejemplares",
                        "url" => ["ejemplares", "IndexEjemplares"]
                    ]
                ];

                if (!isset($_SESSION["arrayIndexEjemplares"])){
                    $_SESSION["arrayIndexEjemplares"] = [
                        "tipoObra" => "",
                        "estado" => "",
                        "selectWhere" => ""
                    ];
                }


                $datos = [
                    "tipoObra" =>  $_SESSION["arrayIndexEjemplares"]["tipoObra"],
                    "estado" =>  $_SESSION["arrayIndexEjemplares"]["estado"],
                ];


                $selectWhere = "";



                if ($_POST){

                    if (isset($_POST["filtradoIndexEjemplares"])){

                        $tipoObra = "";
                        if (isset($_POST["tipoObra"])){

                            $tipoObra = intval($_POST["tipoObra"]);

                            if ($tipoObra !== 0){

                                $selectWhere  = " `cod_categoria_obra` = $tipoObra";
                            }
                            
                        }
                        $datos["tipoObra"] = $tipoObra;

                        $estado = "";
                        if (isset(($_POST["estado"]))){

                            $estado = trim($_POST["estado"]);

                            if ($estado !== ""){

                                if ($selectWhere !==""){
                                    if ($estado === "DISPONIBLE"){

                                        $selectWhere .= "  AND estado_ejemplar = 0";    
                                    }

                                    if ($estado === "RESERVADO"){
                                        $selectWhere .= " AND  estado_ejemplar = 1";
                                    }
                                }
                                else{

                                    if ($estado === "DISPONIBLE"){
                                        $selectWhere .= "  estado_ejemplar = 0";    
                                    }

                                    if ($estado === "RESERVADO"){
                                        $selectWhere .= "  estado_ejemplar = 1";
                                    }
                                }

                            }
                        }
                        $datos["estado"] = $estado;

                    }


                    if (isset($_POST["limpiarFiltradoIndexEjemplares"])){


                        $datos["tipoObra"] = "";
                        $datos["estado"] = "";
                        $selectWhere = "";
                    }

                    
                    $_SESSION["arrayIndexEjemplares"] = [
                        "tipoObra" =>  $datos["tipoObra"],
                        "estado" => $datos["estado"],
                        "selectWhere" => $selectWhere
                    ];

                }

                $estadoArray = [
                    "DISPONIBLE" => "DISPONIBLE",
                    "RESERVADO" => "RESERVADO"
                ];
                $arrayCategorias = CategoriasObras::dameCategoriasObras(null);

                //Parámetros para el paginador
                $numPaginas = 0;
                $numProductos = 5;
                $limite = "";
                $paginaActual = 1;

                if (isset($_GET["reg_pag"]) && isset($_GET["pag"])) {
                    $paginaActual = intval($_GET["pag"]);   //pagina actua
                    $numProductos = intval($_GET["reg_pag"]);
                    $numPaginas = $numProductos * ($paginaActual - 1);
                    $limite = $numPaginas . "," . $numProductos;
                } else {
                    $paginaActual = 1;
                    $limite = $numPaginas . "," . $numProductos;
                }

                $ejemplares = new Ejemplares();
                if (isset($_SESSION["arrayIndexEjemplares"]["selectWhere"]) &&$_SESSION["arrayIndexEjemplares"]["selectWhere"]  !== ""){
                    $selectWhere = $_SESSION["arrayIndexEjemplares"]["selectWhere"];
                }
             

                if ($selectWhere !== "") {

                    $filas = $ejemplares->buscarTodos(
                        [
                            "where" => $selectWhere,
                            "limit" => $limite
                        ]
                    );
                } else {
                    $filas = $ejemplares->buscarTodos(
                        [
                            "limit" => $limite
                        ]
                    );
                }


                //Añadimos la opción de la tabla
                foreach ($filas as $clave => $valor) {

                    //DATOS DE EJEMPLAR
                    $valor["cod_ejemplar"] = intval($valor["cod_ejemplar"]);
                    $valor["borrado_ejemplar"] = intval($valor["borrado_ejemplar"]);
                    $valor["fecha_registro"] = CGeneral::fechaMysqlANormal($valor["fecha_registro"]);
                    $valor["estado_ejemplar"] = intval($valor["estado_ejemplar"]);

                    //DATOS DE OBRA
                    $valor["cod_obra"] = intval($valor["cod_obra"]);
                    $valor["obra_borrado"] = intval($valor["obra_borrado"]);
                    $valor["codigo_genero"] = intval($valor["codigo_genero"]);
                    $valor["cod_categoria_obra"] = intval($valor["cod_categoria_obra"]);
                    $valor["fecha_lanzamiento"] = CGeneral::fechaMysqlANormal($valor["fecha_lanzamiento"]);

                    //sustituimos valores de cod por cadena
                    $valor["cod_categoria_obra"] =  mb_strtoupper(CategoriasObras::dameCategoriasObras($valor["cod_categoria_obra"]));
                    $valor["codigo_genero"] = GenerosObras::devuelveGenerosObras($valor["codigo_genero"], null);

                    if ($valor["obra_borrado"] === 0) {
                        $valor["obra_borrado"] = "NO";
                    }

                    if ($valor["obra_borrado"] === 1) {
                        $valor["obra_borrado"] = "SI";
                    }

                    if ($valor["borrado_ejemplar"] === 0) {
                        $valor["borrado_ejemplar"] = "NO";
                    }

                    if ($valor["borrado_ejemplar"] === 1) {
                        $valor["borrado_ejemplar"] = "SI";
                    }


                    if ($valor["estado_ejemplar"] === 0) {
                        $valor["estado_ejemplar"] = "DISPONIBLE";
                    }

                    if ($valor["estado_ejemplar"] === 1) {
                        $valor["estado_ejemplar"] = "RESERVADO";
                    }

                    //DATOS DE TIPO DE OBRA

                    if ($valor["cod_categoria_obra"] === "pelicula") { //pelicula

                        $valor["pelicula_duracion"] = intval($valor["pelicula_duracion"]);
                        $valor["pelicula_edad"] = intval($valor["pelicula_edad"]);
                    }

                    if ($valor["cod_categoria_obra"] === "audio") { //audio
                        $valor["audio_duracion"] = intval($valor["audio_duracion"]);
                    }

                    //Operaciones
                    $valor["oper"] = CHTML::link(
                        CHTML::imagen("/imagenes/24x24/ver.png", "", ["title" => "Ver ejemplar"]),
                        Sistema::app()->generaURL(["ejemplares", "verEjemplar"], ["id" => $valor["cod_ejemplar"]])
                    ) . " " .
                        CHTML::link(
                            CHTML::imagen("/imagenes/24x24/modificar.png", "", ["title" => "Modificar ejemplar"]),
                            Sistema::app()->generaURL(["ejemplares", "modificarEjemplar"], ["id" => $valor["cod_ejemplar"]])
                        );


                    if ($valor["borrado_ejemplar"] === "NO") {
                        $valor["oper"] .= CHTML::link(
                            CHTML::imagen(
                                "/imagenes/24x24/borrar.png",
                                "",
                                ["title" => "Borrar ejemplar"]
                            ),
                            Sistema::app()->generaURL(
                                ["ejemplares", "borrarEjemplar"],
                                ["id" => $valor["cod_ejemplar"]]
                            )
                        );
                    }


                    $filas[$clave] = $valor;
                }


                $cabecera = [
                    [
                        "ETIQUETA" => "Título",
                        "CAMPO" => "titulo"
                    ],
                    [
                        "ETIQUETA" => "Autor",
                        "CAMPO" => "autor"
                    ],
                    [
                        "ETIQUETA" => "Tipo de obra",
                        "CAMPO" => "cod_categoria_obra"
                    ],
                    [
                        "ETIQUETA" => "Género",
                        "CAMPO" => "codigo_genero"
                    ],
                    [
                        "ETIQUETA" => "Fecha de registro",
                        "CAMPO" => "fecha_registro"
                    ],
                    [
                        "ETIQUETA" => "Borrado obra",
                        "CAMPO" => "obra_borrado"
                    ],
                    [
                        "ETIQUETA" => "Borrado ejemplar",
                        "CAMPO" => "borrado_ejemplar"
                    ],
                    [
                        "ETIQUETA" => "Estado ejemplar",
                        "CAMPO" => "estado_ejemplar"
                    ],
                    [
                        "ETIQUETA" => "Formato ejemplar",
                        "CAMPO" => "formato_ejemplar"
                    ],
                    [
                        "ETIQUETA" => "ISBN",
                        "CAMPO" => "isbn_libro"
                    ],
                    [
                        "ETIQUETA" => "Duración audio",
                        "CAMPO" => "audio_duracion"
                    ],
                    [
                        "ETIQUETA" => "Duración película",
                        "CAMPO" => "pelicula_duracion"
                    ],
                    [
                        "ETIQUETA" => "País película",
                        "CAMPO" => "pelicula_pais"
                    ],
                    [
                        "ETIQUETA" => "Edad película",
                        "CAMPO" => "pelicula_edad"
                    ],
                    [
                        "ETIQUETA" => "Operaciones",
                        "CAMPO" => "oper"
                    ]
                ];


                //ordenamos las filas
                $filasOrdenadas = [];
                foreach($filas as $clave => $valor){

                    $filasOrdenadas[intval($valor["cod_ejemplar"])] = $valor;

                }
                //ordeno array
                ksort($filasOrdenadas);



                //opciones del paginador
                $opcPaginador = array(
                    "URL" => Sistema::app()->generaURL(array("ejemplares", "indexEjemplares")),
                    "TOTAL_REGISTROS" => $ejemplares->buscarTodosNRegistros($selectWhere !== "" ? ["where" => $selectWhere] : []),
                    "PAGINA_ACTUAL" => $paginaActual,
                    "REGISTROS_PAGINA" => $numProductos,
                    "TAMANIOS_PAGINA" => array(
                        5 => "5",
                        10 => "10",
                        20 => "20",
                        30 => "30",
                        40 => "40",
                        50 => "50"
                    ),
                    "MOSTRAR_TAMANIOS" => true,
                    "PAGINAS_MOSTRADAS" => 7,
                );


                //Ahora miro lo que devuelve esto

                $this->dibujaVista("indexEjemplares", [
                    "cabecera" => $cabecera,
                    "filas" => $filasOrdenadas,
                    "datos" => $datos,
                    "estadoArray" => $estadoArray,
                    "arrayCategorias" => $arrayCategorias,
                    "paginador" => $opcPaginador
                ], "Control de ejemplares");
                
            } else { //Si no tienes los permisos de bibliotecario
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;
            }
        } else { //Si no estás logueado
            Sistema::app()->irAPagina(["login", "InicioSesion"]);
        }
    }




    /**
     * Acción del controlador de ejemplares
     * que me permite ver todos los datos del ejemplar seleccionado
     * 
     * Se comprueba que se accede con parametro correcto, que el ejemplar exista
     * y se tengan los permisos para verlo
     *
     * @return Void -> no devuelve nada, imprime una vista
     */
    public function accionVerEjemplar(): Void
    {


        if (Sistema::app()->Acceso()->hayUsuario() === true) {

            if (
                Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ) {

                $id = "";
                if ($_GET) {

                    if (isset($_GET["id"])) {
                        $id = intval($_GET["id"]);
                    }
                }


                //Se comprueba que el id introducido existe

                $ejemplar = new Ejemplares();

                if ($ejemplar->buscarPorId($id) === true) {

                    $this->barraUbi = [
                        [
                            "texto" => "inicio",
                            "url" => "/"
                        ],
                        [
                            "texto" => "Control de ejemplares",
                            "url" => ["ejemplares", "IndexEjemplares"]
                        ],
                        [
                            "texto" => "Ver ejemplar ",
                            "url" => ["ejemplares", "verEjemplar/?id=$id"]
                        ]
                    ];
                    $ejemplar->fecha_lanzamiento = CGeneral::fechaNormalAMysql($ejemplar->fecha_lanzamiento);
                    $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);


                    $this->dibujaVista("verEjemplar", [
                        "ejemplar" => $ejemplar
                    ], "Ver ejemplar " . $ejemplar->titulo);

                } 
                
                else { //Si no existe el cod
                    Sistema::app()->paginaError(404, "No se ha encontrado una obra con el código indicado");
                    exit;
                }


                if ($id === "") { //No se recibe parámetro id
                    Sistema::app()->paginaError(404, "Se ha accedido con un parámetro distinto al id");
                    exit;
                }
            } else { //En caso de no acceder con los permisos
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;
            }
        } else { //En caso de no acceder logueado
            Sistema::app()->irAPagina(["login", "InicioSesion"]);
        }
    }



    /**
     * Acción que permite modificar un ejemplar concreto
     * Se pueden modificar los datos del modelo ejemplar
     * y su respectivo modelo de libro, video o audio
     * Si es digital, se podrá añadir un nuevo archivo
     * 
     * Se validan los posibles errores
     * 
     *
     * @return Void -> no devuelve nada
     */
    public function accionModificarEjemplar(): Void{


        if (Sistema::app()->Acceso()->hayUsuario() === true) {

            if (
                Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ) {

                $id = "";
                if ($_GET) {

                    if (isset($_GET["id"])) {
                        $id = intval($_GET["id"]);
                    }
                }


                //Datos
                $ejemplar = new Ejemplares();
                // $formatos = new Formatos();
                $arrayFormatos = Formatos::devuelveFormatos();
                $arrayEdades = Peliculas::devuelveEdades();
                $arrayPaises = Peliculas::devuelvePaisesPeliculas();
                $datosForm = []; //lo uso para duracion en audio y pelicula
                $errores = [];

                if ($id !== "") { //No se recibe parámetro id


                    if ($ejemplar->buscarPorId($id) === true) { //Se comprueba que el id introducido existe

                        //Barra de ubicación
                        $this->barraUbi = [
                            [
                                "texto" => "inicio",
                                "url" => "/"
                            ],
                            [
                                "texto" => "Control de ejemplares",
                                "url" => ["ejemplares", "IndexEjemplares"]
                            ],
                            [
                                "texto" => "Modificar ejemplar ",
                                "url" => ["ejemplares", "modificarEjemplar/?id=$id"]
                            ]
                        ];

                        $ejemplar->fecha_lanzamiento = CGeneral::fechaNormalAMysql($ejemplar->fecha_lanzamiento);
                        $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);

                        $formatosMedios = new FormatosMedios();

                        $arrayFormatosMedios = $formatosMedios::devuelveFormatosMedios(
                            null,
                            $ejemplar->cod_categoria_obra,
                            $ejemplar->cod_formato_ejemplar
                        );

                        $tipoObra = $ejemplar->cod_categoria_obra;
                        $datosAdicionalesEjemplar = null;
                        $codEjemplar = $ejemplar->cod_ejemplar;

                        if ($tipoObra === 1){//libro 

                            $libro = new Libros ();

                            $libro->buscarPor(["where" => " `cod_ejemplar` = $codEjemplar"]);
                            $datosAdicionalesEjemplar = $libro;

                        }


                        if ($tipoObra === 2){//pelicula

                            $pelicula = new Peliculas ();
                            $pelicula->buscarPor(["where" => " `cod_ejemplar` = $codEjemplar"]);
                            $datosAdicionalesEjemplar = $pelicula;

                            $duracion = $datosAdicionalesEjemplar->duracion;

                            $duracion = mb_split(":", $duracion);

                            if (count($duracion) === 2){ //MINUTO Y SEGUDOS
                                $datosForm["horaP"] = 0;
                                $datosForm["minutoP"] = $duracion[0];
                                $datosForm["segundoP"] =  $duracion[1];
                            }

                            if (count($duracion) === 3){//HORAS MINUTOS Y SEGUNDOS
                                $datosForm["horaP"] = $duracion[0];
                                $datosForm["minutoP"] = $duracion[1];
                                $datosForm["segundoP"]= $duracion[2];


                            } 

                        }

                        if ($tipoObra === 3){//audio

                            $audio =  new Audio ();
                            $audio->buscarPor(["where" => " `cod_ejemplar` = $codEjemplar"]);
                            $datosAdicionalesEjemplar = $audio;


                            $duracion = $datosAdicionalesEjemplar->duracion;

                            $duracion = mb_split(":", $duracion);

                            if (count($duracion) === 2){ //MINUTO Y SEGUDOS
                                $datosForm["horaA"] = 0;
                                $datosForm["minutoA"] = $duracion[0];
                                $datosForm["segundoA"] =  $duracion[1];
                            }

                            if (count($duracion) === 3){//HORAS MINUTOS Y SEGUNDOS
                                $datosForm["horaA"] = $duracion[0];
                                $datosForm["minutoA"] = $duracion[1];
                                $datosForm["segundoA"]= $duracion[2];


                            } 
                        }


                        //modificar
                        if ($_POST) { 

                            //Se tiene que validar el modelo ejemplar
                            // y dependiendo del caso el de pelicula, audio o libro
                            //Y EL FILE

                            //EJEMPLAR
                            $nombreEjemplar = $ejemplar->getNombre();
                            if ($_POST[$nombreEjemplar]){

                                $ejemplar->setValores($_POST[$nombreEjemplar]);
                                //pasamos las fechas a normal

                                if ($ejemplar->fecha_lanzamiento !== ""){
                                    $ejemplar->fecha_lanzamiento = CGeneral::fechaMysqlANormal($ejemplar->fecha_lanzamiento);

                                }

                                if ($ejemplar->fecha_registro !== ""){
                                    $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);

                                }

                                
                            }


                            //DATOS ADICIONALES
                            $nombreDatosAdicionales = $datosAdicionalesEjemplar->getNombre();
                            if(isset($_POST[$nombreDatosAdicionales])){

                                $datosAdicionalesEjemplar->setValores($_POST[$nombreDatosAdicionales]);

                                
                            }


                            //datos adicionales
                            if ($tipoObra === 2){//pelicula

                                $horaP  ="";
                                $minutoP  ="";
                                $segundoP  =""; 

                                //Preguntar por duración en post
                                if ($_POST["horaP"]){
                                    $horaP = trim($_POST["horaP"]);

                                }

                                if ($_POST["minutoP"]){
                                    $minutoP = trim($_POST["minutoP"]);

                                }

                                if ($_POST["segundoP"]){
                                    $segundoP = trim($_POST["segundoP"]);

                                }

                                if ($horaP === ""){ //formato mm:ss o hh:mm:ss
                                    $horaP = "00";

                                }

                                $duracion = $horaP. ":". $minutoP. ":".$segundoP;
                                $datosAdicionalesEjemplar->duracion = $duracion;
                            }

                            if ($tipoObra === 3){//audio

                                $horaA  ="";
                                $minutoA  ="";
                                $segundoA  =""; 
                                
                            
                                //Preguntar por duración en post
                                if ($_POST["horaA"]){
                                    $horaA = trim($_POST["horaA"]);

                                }

                                if ($_POST["minutoA"]){
                                    $minutoA = trim($_POST["minutoA"]);

                                }

                                if ($_POST["segundoA"]){
                                    $segundoA = trim($_POST["segundoA"]);

                                }

                                if ($horaA === ""){ //formato mm:ss o hh:mm:ss
                                    $horaA = "00";

                                }
                         
                                $duracion = $horaA. ":". $minutoA. ":".$segundoA;
                                $datosAdicionalesEjemplar->duracion = $duracion;

                            }

                            //FICHEROS
                            if ($_FILES){//Se suben ficheros se validan aqui

                                $rutaArchivo = "";

                                if (isset($_FILES[$nombreEjemplar]["name"]["ubicacion_ejemplar"])){
                                    
                                    $nombreArchivo = trim($_FILES[$nombreEjemplar]["name"]["ubicacion_ejemplar"]);
                                    $nombreArchivo = CGeneral::addSlashes($nombreArchivo);


                                    //Comprobamos si se ha subido archivo
                                    if ($nombreArchivo !== ""){

                                        $ruta = "/audiovisual";

                                        if (intval($ejemplar->cod_categoria_obra) === 1){//libro
                                            $ruta.="/libro";
                                        }


                                        if (intval($ejemplar->cod_categoria_obra) === 2){//pelicula
                                            $ruta.="/pelicula";

                                        }


                                        if (intval($ejemplar->cod_categoria_obra) === 3){//audio
                                            $ruta.="/audio";

                                        }

                                        //Ubicamos el fichero
                                        $ruta  .= "/".$nombreArchivo;

                                        //actualizo valor ubicacion
                                        $ejemplar->ubicacion_ejemplar =  $ruta;

                                        //Movemos archivo a nuestro sitio
                                        $rutaArchivo = RUTA_BASE. $ruta;
                                        $moverFichero = move_uploaded_file($_FILES["ejemplares"]["tmp_name"]["ubicacion_ejemplar"], $rutaArchivo);

                                        //Ahora se validan los modelos correspondientes
                                        $validaEjemplar = $ejemplar->validar();
                                        $validaDatosAdicionales = $datosAdicionalesEjemplar->validar();

                                        if ($validaEjemplar && $validaDatosAdicionales && $moverFichero){
                                            //Se ha validado y subido el fichero correctamente

                                            //guardamos modelos
                                            $guardaEjemplar = $ejemplar->guardar();
                                            $guardarDatosAdicionales = $datosAdicionalesEjemplar->guardar();

                                            if ($guardaEjemplar && $guardarDatosAdicionales){

                                                //Se lleva a ver ejemplar
                                                $codEjemplar = intval($ejemplar->cod_ejemplar);
                                                header("location: ". Sistema::app()->generaURL(
                                                    ["ejemplares", "verEjemplar"], ["id"=>$codEjemplar]));
                                                exit;

                                            }
                                            else{ //página de error
                                                Sistema::app()->paginaError(404, "No se han podido actualizar los datos del ejemplar");
                                                exit;
                                            }

                                        }
                                        else{
                                            //Preguntamos si ha habido errores
                                            //para el fichero


                                            if (isset($_FILES[$nombreEjemplar]["error"]["ubicacion_ejemplar"])){
                                                $errores["ubicacion_ejemplar"] = "Problema al subir fichero";
                                            }

                                            //ponemos fechas para el input

                                            if ($ejemplar->fecha_lanzamiento !== ""){
                                                $ejemplar->fecha_lanzamiento = CGeneral::fechaNormalAMysql($ejemplar->fecha_lanzamiento);
                                            }

                                            if ($ejemplar->fecha_registro !== ""){
                                                $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                            }

                                            $this->dibujaVista("modificarEjemplar", [
                                                "ejemplar" => $ejemplar,
                                                "arrayFormatos" => $arrayFormatos,
                                                "arrayEdades" => $arrayEdades,
                                                "arrayFormatosMedios" => $arrayFormatosMedios,
                                                "datosAdicionalesEjemplar" => $datosAdicionalesEjemplar,
                                                "arrayPaises" => $arrayPaises,
                                                "datosForm" => $datosForm,
                                                "errores" => $errores
                                            ], "Modificar ejemplar " . $ejemplar->titulo);
                                            exit;
                                        }

                                    }
                                    else{//en caso de que haya files y no se haya subido nada

                                        //Ahora se validan los modelos correspondientes
                                        $validaEjemplar = $ejemplar->validar();
                                        $validaDatosAdicionales = $datosAdicionalesEjemplar->validar();
    
                                        if ($validaEjemplar && $validaDatosAdicionales){//validamos
                                                //Se ha validado y subido el fichero correctamente

                                                //guardamos modelos
                                                $guardaEjemplar = $ejemplar->guardar();
                                                $guardarDatosAdicionales = $datosAdicionalesEjemplar->guardar();

                                        
                                                if ($guardaEjemplar && $guardarDatosAdicionales) {//guardamos

                                                    //Se lleva a ver ejemplar
                                                    $codEjemplar = intval($ejemplar->cod_ejemplar);
                                                    header("location: " . Sistema::app()->generaURL(
                                                        ["ejemplares", "verEjemplar"],
                                                        ["id" => $codEjemplar]
                                                    ));
                                                    exit;
                                                } 
                                                
                                                else { //página de error
                                                    Sistema::app()->paginaError(404, "No se han podido actualizar los datos del ejemplar");
                                                    exit;
                                                }                                        
                                        
                            
                                        
                                            }
                                        else{//error validaciones, mostramos errores

                                            //ponemos fechas para el input
                                            $ejemplar->fecha_lanzamiento = CGeneral::fechaNormalAMysql($ejemplar->fecha_lanzamiento);
                                            $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
        
                                            $this->dibujaVista("modificarEjemplar", [
                                                "ejemplar" => $ejemplar,
                                                "arrayFormatos" => $arrayFormatos,
                                                "arrayEdades" => $arrayEdades,
                                                "arrayFormatosMedios" => $arrayFormatosMedios,
                                                "datosAdicionalesEjemplar" => $datosAdicionalesEjemplar,
                                                "arrayPaises" => $arrayPaises,
                                                "datosForm" => $datosForm,
                                                "errores" => $errores
                                            ], "Modificar ejemplar " . $ejemplar->titulo);
                                            exit;

                                        }
                                    }

                                }
                                
                            }

                            else{ //Si no se suben files

                                //Ahora se validan los modelos correspondientes
                                $validaEjemplar = $ejemplar->validar();
                                $validaDatosAdicionales = $datosAdicionalesEjemplar->validar();

                                if ($validaEjemplar && $validaDatosAdicionales){//validamos

                                    //Se ha validado y subido el fichero correctamente

                                    //guardamos modelos
                                    $guardaEjemplar = $ejemplar->guardar();
                                    $guardarDatosAdicionales = $datosAdicionalesEjemplar->guardar();

                                    if ($guardaEjemplar && $guardarDatosAdicionales) {//guardamos

                                        //Se lleva a ver ejemplar
                                        $codEjemplar = intval($ejemplar->cod_ejemplar);
                                        header("location: " . Sistema::app()->generaURL(
                                            ["ejemplares", "verEjemplar"],
                                            ["id" => $codEjemplar]
                                        ));
                                        exit;
                                    } 
                                    
                                    else { //página de error
                                        Sistema::app()->paginaError(404, "No se han podido actualizar los datos del ejemplar");
                                        exit;
                                    }

                                }
                                else{ //error validaciones, mostramos errores

                                    //ponemos fechas para el input
                                    $ejemplar->fecha_lanzamiento = CGeneral::fechaNormalAMysql($ejemplar->fecha_lanzamiento);
                                    $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);

                                    $this->dibujaVista("modificarEjemplar", [
                                        "ejemplar" => $ejemplar,
                                        "arrayFormatos" => $arrayFormatos,
                                        "arrayEdades" => $arrayEdades,
                                        "arrayFormatosMedios" => $arrayFormatosMedios,
                                        "datosAdicionalesEjemplar" => $datosAdicionalesEjemplar,
                                        "arrayPaises" => $arrayPaises,
                                        "datosForm" => $datosForm,
                                        "errores" => $errores
                                    ], "Modificar ejemplar " . $ejemplar->titulo);
                                    exit;

                                }
                            }

                        }

                        //Aquí es donde viene la primera vez que se carga la vista

                        $this->dibujaVista("modificarEjemplar", [
                            "ejemplar" => $ejemplar,
                            "arrayFormatos" => $arrayFormatos,
                            "arrayEdades" => $arrayEdades,
                            "arrayFormatosMedios" => $arrayFormatosMedios,
                            "datosAdicionalesEjemplar" => $datosAdicionalesEjemplar,
                            "arrayPaises" => $arrayPaises,
                            "datosForm" => $datosForm
                        ], "Modificar ejemplar " . $ejemplar->titulo);


                    } 
                    
                    else { //Si no existe el cod
                        Sistema::app()->paginaError(404, "No se ha encontrado una obra con el código indicado");
                        exit;
                    }
                    
                }
                else{ //Cuando se no accede con el parámetro id
                
                    Sistema::app()->paginaError(404, "Se ha accedido con un parámetro distinto al id");
                    exit;
                }
            } 
            
            else { //En caso de no acceder con los permisos
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;
            }


        } 
        else { //En caso de no acceder logueado
        
            Sistema::app()->irAPagina(["login", "InicioSesion"]);
        
        }
    
    
    
    
    }





    /**
     * 
     *
     * @return void
     */
    public function accionBorrarEjemplar()
    {

        if (Sistema::app()->Acceso()->hayUsuario() === true) {

            if (
                Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ) {

                $id = "";
                if ($_GET) {

                    if (isset($_GET["id"])) {
                        $id = intval($_GET["id"]);
                    }
                }


                //Se comprueba que el id introducido existe

                $ejemplar = new Ejemplares();

                if ($ejemplar->buscarPorId($id) === true) {

                    //SE COMPRUEBA SI ESTA BORRADO

                    $borrado = intval($ejemplar->borrado_ejemplar);

                    if ($borrado === 0){


                        $this->barraUbi = [
                            [
                                "texto" => "inicio",
                                "url" => "/"
                            ],
                            [
                                "texto" => "Control de ejemplares",
                                "url" => ["ejemplares", "IndexEjemplares"]
                            ],
                            [
                                "texto" => "Borrar ejemplar ",
                                "url" => ["ejemplares", "borrarEjemplar/?id=$id"]
                            ]
                        ];

                        if ($_POST){

                            $nombre = $ejemplar->getNombre();

                            if(isset($_POST[$nombre])){



                                $ejemplar->setValores($_POST[$nombre]);

                                if(!$ejemplar->validar()){

                                    $this->dibujaVista("borrarEjemplar", [
                                        "ejemplar" => $ejemplar
                                    ], "Borrar ejemplar " . $ejemplar->titulo);
                                    exit;
                                }
                                else{

                                    if ($ejemplar->guardar() === true){

                                        //Se lleva a ver ejemplar
                                        $codEjemplar = intval($ejemplar->cod_ejemplar);
                                        header("location: " . Sistema::app()->generaURL(["ejemplares", "verEjemplar"], ["id" => $codEjemplar]));
                                        exit;

                                    }
                                    else{
                                        $this->dibujaVista("borrarEjemplar", [
                                            "ejemplar" => $ejemplar
                                        ], "Borrar ejemplar " . $ejemplar->titulo);
                                        exit;
                                    }

                                }

                            }
                        }
    
    
                        $this->dibujaVista("borrarEjemplar", [
                            "ejemplar" => $ejemplar
                        ], "Borrar ejemplar " . $ejemplar->titulo);
    
    
    
                    }
                    else{
                        Sistema::app()->paginaError(404, "El ejemplar actual ya ha sido borrado");
                        exit;
                    }

                    
                } else { //Si no existe el cod
                    Sistema::app()->paginaError(404, "No se ha encontrado una obra con el código indicado");
                    exit;
                }


                if ($id === "") { //No se recibe parámetro id
                    Sistema::app()->paginaError(404, "Se ha accedido con un parámetro distinto al id");
                    exit;
                }
            } else { //En caso de no acceder con los permisos
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;
            }
        } else { //En caso de no acceder logueado
            Sistema::app()->irAPagina(["login", "InicioSesion"]);
        }
    }


    public function accionAnadeEjemplarLibro(){

        if (Sistema::app()->Acceso()->hayUsuario() === true) { //Hay usuario

            $permisos = Sistema::app()->Acceso()->puedePermiso(1) && Sistema::app()->Acceso()->puedePermiso(9);
            if ($permisos) { //tiene permisos

                //barra de ubicación
                $this->barraUbi = [
                    [
                        "texto" => "inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de ejemplares",
                        "url" => ["ejemplares", "IndexEjemplares"]
                    ],
                    [
                        "texto" => "Añade ejemplar libro",
                        "url" => ["ejemplares", "AnadeEjemplarLibro"]
                    ]
                ];

                //datos
                $arrayObras = Obras::dameObraPorCod(1);
                $ejemplar = new Ejemplares ();
                $ejemplar->cod_categoria_obra = 1;
                $libro = new Libros ();
                $arrayFormatoEjemplar = Formatos::devuelveFormatos();
                $divInputFile = ["style" => "display:none;"];
                $divInputUbicacion = ["style" => "display:none;"];
                $arrayFormatosMedios = FormatosMedios::devuelveFormatosPorCategoria(1);
                $datos = ["codLibro" => -1];

                if ($_POST){

                    $nombrEjemplar = $ejemplar->getNombre();

                    //SACO VALORES DE EJEMPLAR
                    if ($_POST[$nombrEjemplar]){
                        $ejemplar->setValores($_POST[$nombrEjemplar]);
                    }

                    if ($ejemplar->fecha_registro !== ""){
                        $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                    }

                    //SACO VALORES DE LIBRO
                    $nombreLibro = $libro->getNombre();
                    if ($_POST[$nombreLibro]){
                        $libro->setValores($_POST[$nombreLibro]);
                    }

                    if ($_POST["obra"]){
                        $ejemplar->cod_obra=intval($_POST["obra"]);
                    }

                    //Ahora pregunto por el files
                    if(intval($ejemplar->cod_formato_ejemplar) === -1){//va a dar error por lo que valido y saco los errores

                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                        }

                        

                        $validaEjemplar = $ejemplar->validar();
                        $validarLibro = $libro->validar();

                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                        }
           

                        $datos["codLibro"] = intval($ejemplar->cod_obra);//actualizo combo
                        
                            $this->dibujaVista("anadeEjemplarLibro", 
                            [

                            "ejemplar" => $ejemplar,
                            "libro" => $libro,
                            "arrayObras"=> $arrayObras,
                            "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                            "divInputFile" => $divInputFile,
                            "divInputUbicacion"=> $divInputUbicacion,
                            "arrayFormatosMedios" => $arrayFormatosMedios,
                            "datos" => $datos
                                ],
                                "Añadir ejemplar libro");
                            exit;


                        

                    }
                    if(intval($ejemplar->cod_formato_ejemplar) === 1){//FISICO
                        
                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                        }

                        $validaEjemplar = $ejemplar->validar();
           
                        if ($validaEjemplar){
                            if ($ejemplar->fecha_registro !== ""){
                                $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                            }

                            //Ahora guardo datos del ejemplar
                            $guardaEjemplar = $ejemplar->guardar();
                            
                            if ($guardaEjemplar){

                                //Valido el libro
                                $nombreLibro = $libro->getNombre();
                                if ($_POST[$nombreLibro]){

                                    $libro->setValores($_POST[$nombreLibro]);
                                    $libro->cod_ejemplar = intval($ejemplar->cod_ejemplar);
                                    $libro->cod_formato_medio = intval($ejemplar->codigo_formato_medio);

                                    if ($libro->validar()){

                                        if($libro->guardar()){
                                            $codEjemplar = intval($ejemplar->cod_ejemplar);
                                            header("location: " . Sistema::app()->generaURL(["ejemplares", "verEjemplar"], ["id" => $codEjemplar]));
                                            exit;
                                        }

                                    }
                                    else{
                                        $this->dibujaVista("anadeEjemplarLibro", 
                                        [
            
                                        "ejemplar" => $ejemplar,
                                        "libro" => $libro,
                                        "arrayObras"=> $arrayObras,
                                        "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                        "divInputFile" => $divInputFile,
                                        "divInputUbicacion"=> $divInputUbicacion,
                                        "arrayFormatosMedios" => $arrayFormatosMedios,
                                        "datos" => $datos
                                            ],
                                            "Añadir ejemplar libro");
                                        exit;
                                    }

                                }


                            }
                            else{
                                //error al guardar
                                Sistema::app()->paginaError(404, "Problema al actualizar la base de datos") . PHP_EOL;

                            }
                        }
                        else{


                            $divInputFile = ["style" => "display:none;"];
                            $divInputUbicacion = ["style" => "display:inline;"];

                 
    
                            $datos["codLibro"] = intval($ejemplar->cod_obra);//actualizo combo
                            
                                $this->dibujaVista("anadeEjemplarLibro", 
                                [
    
                                "ejemplar" => $ejemplar,
                                "libro" => $libro,
                                "arrayObras"=> $arrayObras,
                                "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                "divInputFile" => $divInputFile,
                                "divInputUbicacion"=> $divInputUbicacion,
                                "arrayFormatosMedios" => $arrayFormatosMedios,
                                "datos" => $datos
                                    ],
                                    "Añadir ejemplar libro");
                                exit;

                        }
                    }
                    if(intval($ejemplar->cod_formato_ejemplar) === 2){//DIGITAL

                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                        }


                        //Ahora pregunto por files
                        if ($_FILES){

                            $rutaArchivo = "";
                            $nombreEjemplar = $ejemplar->getNombre();
                            if (isset($_FILES[$nombreEjemplar]["name"]["ubicacion_ejemplar"])){

                                     
                                $nombreArchivo = trim($_FILES[$nombreEjemplar]["name"]["ubicacion_ejemplar"]);
                                $nombreArchivo = CGeneral::addSlashes($nombreArchivo);

                                if ($nombreArchivo !== ""){
                                    $ruta = "/audiovisual/libro";
                                    $ruta  .= "/".$nombreArchivo;
                                    $ejemplar->ubicacion_ejemplar =  $ruta;
                                    $rutaArchivo = RUTA_BASE. $ruta;
                                    $moverFichero = move_uploaded_file($_FILES[$nombreEjemplar]["tmp_name"]["ubicacion_ejemplar"], $rutaArchivo);


                                    if ($_POST[$ejemplar->getNombre()]){

                                        $ejemplar->setValores($_POST[$ejemplar->getNombre()]);
                                        $ejemplar->ubicacion_ejemplar =  $ruta;


                                        if ($moverFichero){
                                            if ($ejemplar->fecha_registro !== ""){
                                                $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                                            }
                                            if ($ejemplar->validar()){
                                                if ($ejemplar->fecha_registro !== ""){
                                                    $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                                }
                                                $guardaEjemplar = $ejemplar->guardar();
                                                if ($guardaEjemplar){
                                                    $nombreLibro = $libro->getNombre();
                                                    if ($_POST[$nombreLibro]){ 

                                                        $libro->setValores($_POST[$nombreLibro]);
                                                        $libro->cod_ejemplar = intval($ejemplar->cod_ejemplar);
                                                        $libro->cod_formato_medio = intval($ejemplar->codigo_formato_medio);
                    
                                                        if ($libro->validar()){
                                                            if($libro->guardar()){
                                                                $codEjemplar = intval($ejemplar->cod_ejemplar);
                                                                header("location: " . Sistema::app()->generaURL(["ejemplares", "verEjemplar"], ["id" => $codEjemplar]));
                                                                exit;
                                                            }
                                                            else{
                                                                    //error al guardar
                                                                    Sistema::app()->paginaError(404, "Problema al actualizar la base de datos") . PHP_EOL;
                                                                    exit;
                                                            }
                                                        }
                                                        else{
                                                            if ($ejemplar->fecha_registro !== ""){
                                                                $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                                            }
                                                            $divInputFile = ["style" => "display:inline;"];
                                                            $divInputUbicacion = ["style" => "display:none;"];
                                
                                                 
                                    
                                                            $datos["codLibro"] = intval($ejemplar->cod_obra);//actualizo combo
                                                            $this->dibujaVista("anadeEjemplarLibro", 
                                                            [
                                
                                                            "ejemplar" => $ejemplar,
                                                            "libro" => $libro,
                                                            "arrayObras"=> $arrayObras,
                                                            "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                                            "divInputFile" => $divInputFile,
                                                            "divInputUbicacion"=> $divInputUbicacion,
                                                            "arrayFormatosMedios" => $arrayFormatosMedios,
                                                            "datos" => $datos
                                                                ],
                                                                "Añadir ejemplar libro");
                                                            exit;
                                                        }

                                                    }


                                                }
                                                else{
                                                        //error al guardar
                                                        Sistema::app()->paginaError(404, "Problema al actualizar la base de datos") . PHP_EOL;
                                                        exit;
                                                }

                                            }
                                            else{
                                                

                                                if ($ejemplar->fecha_registro !== ""){
                                                    $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                                }

                                                $divInputFile = ["style" => "display:inline;"];
                                                $divInputUbicacion = ["style" => "display:none;"];
                    
                                     
                        
                                                $datos["codLibro"] = intval($ejemplar->cod_obra);//actualizo combo


                                                $this->dibujaVista("anadeEjemplarLibro", 
                                                [
                    
                                                "ejemplar" => $ejemplar,
                                                "libro" => $libro,
                                                "arrayObras"=> $arrayObras,
                                                "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                                "divInputFile" => $divInputFile,
                                                "divInputUbicacion"=> $divInputUbicacion,
                                                "arrayFormatosMedios" => $arrayFormatosMedios,
                                                "datos" => $datos
                                                    ],
                                                    "Añadir ejemplar libro");
                                                exit;
                                            }

                                        }
                                        else{ //error al mover el fichero

                                            if ($ejemplar->fecha_registro !== ""){
                                                $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                            }

                                            //preguntar por posible error
                                            if (isset($_FILES[$nombreEjemplar]["error"]["ubicacion_ejemplar"])){
                                                $errores["ubicacion_ejemplar"] = "Problema al subir fichero";
                                            }

                                            $divInputFile = ["style" => "display:inline;"];
                                            $divInputUbicacion = ["style" => "display:none;"];
                
                                 
                    
                                            $datos["codLibro"] = intval($ejemplar->cod_obra);//actualizo combo

                                            $this->dibujaVista("anadeEjemplarLibro", 
                                            [
                
                                            "ejemplar" => $ejemplar,
                                            "libro" => $libro,
                                            "arrayObras"=> $arrayObras,
                                            "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                            "divInputFile" => $divInputFile,
                                            "divInputUbicacion"=> $divInputUbicacion,
                                            "arrayFormatosMedios" => $arrayFormatosMedios,
                                            "errores" => $errores,
                                            "datos" => $datos
                                                ],
                                                "Añadir ejemplar libro");
                                            exit;
                                        }
                                    }
                                }
                                else{ //PÁGINA DE ERROR AL VALIDAR EJEMPLAR

                                    if ($_POST[$ejemplar->getNombre()]){

                                        $ejemplar->setValores($_POST[$ejemplar->getNombre()]);
                                        if ($ejemplar->fecha_registro !== ""){
                                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                                        }
                                        $ejemplar->validar();

                                        $divInputFile = ["style" => "display:inline;"];
                                        $divInputUbicacion = ["style" => "display:none;"];
            
                             
                
                                        $datos["codLibro"] = intval($ejemplar->cod_obra);//actualizo combo
                                        
                                            $this->dibujaVista("anadeEjemplarLibro", 
                                            [
                
                                            "ejemplar" => $ejemplar,
                                            "libro" => $libro,
                                            "arrayObras"=> $arrayObras,
                                            "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                            "divInputFile" => $divInputFile,
                                            "divInputUbicacion"=> $divInputUbicacion,
                                            "arrayFormatosMedios" => $arrayFormatosMedios,
                                            "datos" => $datos
                                                ],
                                                "Añadir ejemplar libro");
                                            exit;

                                    }

                                }

                            }

                        }


                    }


                }


                $this->dibujaVista("anadeEjemplarLibro", 
                                    [

                                    "ejemplar" => $ejemplar,
                                    "libro" => $libro,
                                    "arrayObras"=> $arrayObras,
                                    "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                    "divInputFile" => $divInputFile,
                                    "divInputUbicacion"=> $divInputUbicacion,
                                    "arrayFormatosMedios" => $arrayFormatosMedios,
                                    "datos" => $datos
                                
                                    ],
                                "Añadir ejemplar libro"
                );
                

                

            }

            else{
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;
            }
        }


        else{
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }

    }

    public function accionAnadeEjemplarVideo (){

        if (Sistema::app()->Acceso()->hayUsuario() === true) { //Hay usuario

            $permisos = Sistema::app()->Acceso()->puedePermiso(1) && Sistema::app()->Acceso()->puedePermiso(9);
            if ($permisos) { //tiene permisos

                //barra de ubicación
                $this->barraUbi = [
                    [
                        "texto" => "inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de ejemplares",
                        "url" => ["ejemplares", "IndexEjemplares"]
                    ],
                    [
                        "texto" => "Añade ejemplar video",
                        "url" => ["ejemplares", "AnadeEjemplarVideo"]
                    ]
                ];

                $arrayObras = Obras::dameObraPorCod(2);
                $ejemplar = new Ejemplares ();
                $ejemplar->cod_categoria_obra = 2;
                $pelicula = new Peliculas();
                $arrayFormatoEjemplar = Formatos::devuelveFormatos();
                $divInputFile = ["style" => "display:none;"];
                $divInputUbicacion = ["style" => "display:none;"];
                $arrayFormatosMedios = FormatosMedios::devuelveFormatosPorCategoria(2);
                $datos = ["horaP" => "00",
                         "minutoP" => "00",
                         "segundoP" => "00",
                         "codPelicula" => -1
                ];
                $arrayCalificacionEdad = Peliculas::devuelveEdades();
                $arrayPaisesPeliculas = Peliculas::devuelvePaisesPeliculas();


                if ($_POST){

                    $nombreEjemplar = $ejemplar->getNombre();

                    //sacamos valores de ejemplar
                    if ($_POST[$nombreEjemplar]){
                        $ejemplar->setValores($_POST[$nombreEjemplar]);
                    }

                    //pongo fecha formato normal
                    if ($ejemplar->fecha_registro !== ""){//ahora esta bien la fecha
                        $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                    }

                    //actualizo combo
                    if ($_POST["obra"]) {
                        $ejemplar->cod_obra = intval($_POST["obra"]);
                    }

                    //sacamos los valores de pelicula
                    $nombrePelicula = $pelicula->getNombre();

                    if ($_POST[$nombrePelicula]) {
                        $pelicula->setValores($_POST[$nombrePelicula]);
                    }
                    
                    //pregunto por los parametros de tiempo
                    $horaP  ="";
                    $minutoP  ="";
                    $segundoP  =""; 

                    //Preguntar por duración en post
                    if ($_POST["horaP"]){
                        $horaP = trim($_POST["horaP"]);

                    }

                    if ($_POST["minutoP"]){
                        $minutoP = trim($_POST["minutoP"]);

                    }

                    if ($_POST["segundoP"]){
                        $segundoP = trim($_POST["segundoP"]);

                    }

                    if ($horaP === ""){ //formato mm:ss o hh:mm:ss
                        $horaP = "00";

                    }
                    $duracion = $horaP. ":". $minutoP. ":".$segundoP;
                    $datos["horaP"] = $horaP;
                    $datos["minutoP"] = $minutoP;
                    $datos["segundoP"] = $segundoP;

                    $pelicula->duracion = $duracion;



                    //Ahora se pregunta por files
                    $tipoFisicoODigital = intval($ejemplar->cod_formato_ejemplar);

                    if($tipoFisicoODigital === -1){//da error

                        //se valida ejemplar que da error
                        $validaEjemplar = $ejemplar->validar();
                        $validarPelicula = $pelicula->validar();

                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                        }

                        $datos["codPelicula"] = intval($ejemplar->cod_obra);//actualizo combo


                         $this->dibujaVista("anadeEjemplarPelicula", 
                            [

                                "ejemplar" => $ejemplar,
                                "pelicula" => $pelicula,
                                "arrayObras"=> $arrayObras,
                                "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                "divInputFile" => $divInputFile,
                                "divInputUbicacion"=> $divInputUbicacion,
                                "arrayFormatosMedios" => $arrayFormatosMedios,
                                "datos" => $datos,
                                "arrayPaisesPeliculas" => $arrayPaisesPeliculas,
                                "arrayCalificacionEdad" => $arrayCalificacionEdad
                                    ],
                                "Añadir ejemplar pelicula"
                                    );
                            exit;
                    }

                    if($tipoFisicoODigital === 1){//fisico
                        
                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                        }

                        $validaEjemplar = $ejemplar->validar();
                        if ($validaEjemplar){

                            if ($ejemplar->fecha_registro !== ""){
                                $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                            }
                            
                            //Ahora guardo datos del ejemplar
                            $guardaEjemplar = $ejemplar->guardar();
                            if ($guardaEjemplar){

                                //validamos pelicula
                                $nombrePelicula = $pelicula->getNombre();

                                if ($_POST[$nombrePelicula]){

                                    $pelicula->setValores($_POST[$nombrePelicula]);
                                    $pelicula->cod_ejemplar = intval($ejemplar->cod_ejemplar);
                                    $pelicula->cod_formato_medio = intval($ejemplar->codigo_formato_medio);
                                    $pelicula->duracion = $duracion;


                                    if ($pelicula->validar()){//se valida la pelicula

                                        if($pelicula->guardar()){
                                            $codEjemplar = intval($ejemplar->cod_ejemplar);
                                            header("location: " . Sistema::app()->generaURL(["ejemplares", "verEjemplar"], ["id" => $codEjemplar]));
                                            exit;
                                        }
                                        

                                    }
                                    else{//error de validacion de la pelicula
                                        
                                        if ($ejemplar->fecha_registro !== ""){
                                            $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                        }
                                        
                                        $divInputFile = ["style" => "display:none;"];
                                        $divInputUbicacion = ["style" => "display:inline;"];
                                        $datos["codPelicula"] = intval($ejemplar->cod_obra);//actualizo combo
            
            
                                        $this->dibujaVista("anadeEjemplarPelicula", 
                                           [
               
                                               "ejemplar" => $ejemplar,
                                               "pelicula" => $pelicula,
                                               "arrayObras"=> $arrayObras,
                                               "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                               "divInputFile" => $divInputFile,
                                               "divInputUbicacion"=> $divInputUbicacion,
                                               "arrayFormatosMedios" => $arrayFormatosMedios,
                                               "datos" => $datos,
                                               "arrayPaisesPeliculas" => $arrayPaisesPeliculas,
                                               "arrayCalificacionEdad" => $arrayCalificacionEdad
                                                   ],
                                               "Añadir ejemplar pelicula"
                                                   );
                                           exit;

                                    }
                                }

                            }
                            else{
                                //error al guardar
                                Sistema::app()->paginaError(404, "Problema al actualizar la base de datos") . PHP_EOL;
                            }
                            
                        }
                        else{//NO SE VALIDA BIEN EN EL FISICO

                            if ($ejemplar->fecha_registro !== ""){
                                $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                            }
                            
                            $divInputFile = ["style" => "display:none;"];
                            $divInputUbicacion = ["style" => "display:inline;"];
                            $datos["codPelicula"] = intval($ejemplar->cod_obra);//actualizo combo


                            $this->dibujaVista("anadeEjemplarPelicula", 
                               [
   
                                   "ejemplar" => $ejemplar,
                                   "pelicula" => $pelicula,
                                   "arrayObras"=> $arrayObras,
                                   "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                   "divInputFile" => $divInputFile,
                                   "divInputUbicacion"=> $divInputUbicacion,
                                   "arrayFormatosMedios" => $arrayFormatosMedios,
                                   "datos" => $datos,
                                   "arrayPaisesPeliculas" => $arrayPaisesPeliculas,
                                   "arrayCalificacionEdad" => $arrayCalificacionEdad
                                       ],
                                   "Añadir ejemplar pelicula"
                                       );
                               exit;
                        }

                    }

                    if($tipoFisicoODigital === 2){//digital
                        
                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                        }

                        if ($_FILES){
                        
                            $rutaArchivo = "";
                            $nombreEjemplar = $ejemplar->getNombre();
                            if (isset($_FILES[$nombreEjemplar]["name"]["ubicacion_ejemplar"])){

                                $nombreArchivo = trim($_FILES[$nombreEjemplar]["name"]["ubicacion_ejemplar"]);
                                $nombreArchivo = CGeneral::addSlashes($nombreArchivo);


                                if ($nombreArchivo !== ""){
                                    $ruta = "/audiovisual/pelicula";
                                    $ruta  .= "/".$nombreArchivo;
                                    $ejemplar->ubicacion_ejemplar =  $ruta;
                                    $rutaArchivo = RUTA_BASE. $ruta;
                                    $moverFichero = move_uploaded_file($_FILES[$nombreEjemplar]["tmp_name"]["ubicacion_ejemplar"], $rutaArchivo);

                                    if ($_POST[$ejemplar->getNombre()]){

                                        $ejemplar->setValores($_POST[$ejemplar->getNombre()]);
                                        $ejemplar->ubicacion_ejemplar =  $ruta;


                                        if ($moverFichero){
                                            
                                            if ($ejemplar->fecha_registro !== ""){
                                                $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                                            }


                                            if ($ejemplar->validar()){
                                                if ($ejemplar->fecha_registro !== ""){
                                                    $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                                }
                                                $guardaEjemplar = $ejemplar->guardar();
                                                if ($guardaEjemplar){

                                                    $nombrePelicula = $pelicula->getNombre();

                                                    $pelicula->setValores($_POST[$nombrePelicula]);
                                                    $pelicula->cod_ejemplar = intval($ejemplar->cod_ejemplar);
                                                    $pelicula->cod_formato_medio = intval($ejemplar->codigo_formato_medio);
                

                                                    if ($pelicula->validar()){

                                                        if ($pelicula->guardar()){
                                                            $codEjemplar = intval($ejemplar->cod_ejemplar);
                                                            header("location: " . Sistema::app()->generaURL(["ejemplares", "verEjemplar"], ["id" => $codEjemplar]));
                                                            exit;
                                                        }
                                                        else{
                                                            //error al guardar
                                                            Sistema::app()->paginaError(404, "Problema al actualizar la base de datos") . PHP_EOL;
                                                            exit;
                                                        }

                                                    }
                                                    else{

                                                        if ($ejemplar->fecha_registro !== ""){
                                                            $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                                        }
                                                        $divInputFile = ["style" => "display:inline;"];
                                                        $divInputUbicacion = ["style" => "display:none;"];
                                                        $datos["codPelicula"] = intval($ejemplar->cod_obra);//actualizo combo
            
            
                                                        $this->dibujaVista("anadeEjemplarPelicula", 
                                                           [
                               
                                                               "ejemplar" => $ejemplar,
                                                               "pelicula" => $pelicula,
                                                               "arrayObras"=> $arrayObras,
                                                               "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                                               "divInputFile" => $divInputFile,
                                                               "divInputUbicacion"=> $divInputUbicacion,
                                                               "arrayFormatosMedios" => $arrayFormatosMedios,
                                                               "datos" => $datos,
                                                               "arrayPaisesPeliculas" => $arrayPaisesPeliculas,
                                                               "arrayCalificacionEdad" => $arrayCalificacionEdad
                                                                   ],
                                                               "Añadir ejemplar pelicula"
                                                                   );
                                                           exit;
                                                    }

                                                }
                                                else{
                                                        //error al guardar
                                                        Sistema::app()->paginaError(404, "Problema al actualizar la base de datos") . PHP_EOL;
                                                        exit;
                                                }

                                            }
                                            else{//error al validar ejemplar

                                                if ($ejemplar->fecha_registro !== ""){
                                                    $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                                }
                                                $divInputFile = ["style" => "display:inline;"];
                                                $divInputUbicacion = ["style" => "display:none;"];
                                                $datos["codPelicula"] = intval($ejemplar->cod_obra);//actualizo combo
    
    
                                                $this->dibujaVista("anadeEjemplarPelicula", 
                                                   [
                       
                                                       "ejemplar" => $ejemplar,
                                                       "pelicula" => $pelicula,
                                                       "arrayObras"=> $arrayObras,
                                                       "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                                       "divInputFile" => $divInputFile,
                                                       "divInputUbicacion"=> $divInputUbicacion,
                                                       "arrayFormatosMedios" => $arrayFormatosMedios,
                                                       "datos" => $datos,
                                                       "arrayPaisesPeliculas" => $arrayPaisesPeliculas,
                                                       "arrayCalificacionEdad" => $arrayCalificacionEdad
                                                           ],
                                                       "Añadir ejemplar pelicula"
                                                           );
                                                   exit;
                                            }

                                        }
                                        else{//error al mover el fichero

                                            
                                            if ($ejemplar->fecha_registro !== ""){
                                                $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                            }

                                            //preguntar por posible error
                                            if (isset($_FILES[$nombreEjemplar]["error"]["ubicacion_ejemplar"])){
                                                $errores["ubicacion_ejemplar"] = "Problema al subir fichero";
                                            }

                                            
                                            $divInputFile = ["style" => "display:inline;"];
                                            $divInputUbicacion = ["style" => "display:none;"];
                                            $datos["codPelicula"] = intval($ejemplar->cod_obra);//actualizo combo


                                            $this->dibujaVista("anadeEjemplarPelicula", 
                                               [
                   
                                                   "ejemplar" => $ejemplar,
                                                   "pelicula" => $pelicula,
                                                   "arrayObras"=> $arrayObras,
                                                   "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                                   "divInputFile" => $divInputFile,
                                                   "divInputUbicacion"=> $divInputUbicacion,
                                                   "arrayFormatosMedios" => $arrayFormatosMedios,
                                                   "datos" => $datos,
                                                   "arrayPaisesPeliculas" => $arrayPaisesPeliculas,
                                                   "arrayCalificacionEdad" => $arrayCalificacionEdad
                                                       ],
                                                   "Añadir ejemplar pelicula"
                                                       );
                                               exit;
                                        }
                                    }

                                }
                                else{//Página de error validar solamente el ejemplar

                                    if ($_POST[$ejemplar->getNombre()]){

                                        $ejemplar->setValores($_POST[$ejemplar->getNombre()]);

                                        if ($ejemplar->fecha_registro !== ""){
                                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                                        }
                                        $ejemplar->validar();

                                        
                                        $divInputFile = ["style" => "display:inline;"];
                                        $divInputUbicacion = ["style" => "display:none;"];
            
                                        $datos["codPelicula"] = intval($ejemplar->cod_obra);//actualizo combo


                                        $this->dibujaVista("anadeEjemplarPelicula", 
                                           [
               
                                               "ejemplar" => $ejemplar,
                                               "pelicula" => $pelicula,
                                               "arrayObras"=> $arrayObras,
                                               "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                               "divInputFile" => $divInputFile,
                                               "divInputUbicacion"=> $divInputUbicacion,
                                               "arrayFormatosMedios" => $arrayFormatosMedios,
                                               "datos" => $datos,
                                               "arrayPaisesPeliculas" => $arrayPaisesPeliculas,
                                               "arrayCalificacionEdad" => $arrayCalificacionEdad
                                                   ],
                                               "Añadir ejemplar pelicula"
                                                   );
                                           exit;
                                    }

                                }
                            }
                        }
                    }



                }

                $this->dibujaVista("anadeEjemplarPelicula", 
                                    [

                                    "ejemplar" => $ejemplar,
                                    "pelicula" => $pelicula,
                                    "arrayObras"=> $arrayObras,
                                    "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                    "divInputFile" => $divInputFile,
                                    "divInputUbicacion"=> $divInputUbicacion,
                                    "arrayFormatosMedios" => $arrayFormatosMedios,
                                    "datos" => $datos,
                                    "arrayPaisesPeliculas" => $arrayPaisesPeliculas,
                                    "arrayCalificacionEdad" => $arrayCalificacionEdad
                                
                                    ],
                                "Añadir ejemplar pelicula"
                );

            }
            else{
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;
            }
        }
        else{
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }

    }


    /**
     * 
     *
     * @return void
     */
    public function accionAnadeEjemplarAudio (){
        
        if (Sistema::app()->Acceso()->hayUsuario() === true) { //Hay usuario

            $permisos = Sistema::app()->Acceso()->puedePermiso(1) && Sistema::app()->Acceso()->puedePermiso(9);
            if ($permisos) { //tiene permisos

                //barra de ubicación
                $this->barraUbi = [
                    [
                        "texto" => "inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de ejemplares",
                        "url" => ["ejemplares", "IndexEjemplares"]
                    ],
                    [
                        "texto" => "Añade ejemplar audio",
                        "url" => ["ejemplares", "AnadeEjemplarAudio"]
                    ]
                ];

                $arrayObras = Obras::dameObraPorCod(3);
                $ejemplar = new Ejemplares ();
                $ejemplar->cod_categoria_obra = 3;
                $audio = new Audio();
                $arrayFormatoEjemplar = Formatos::devuelveFormatos();
                $divInputFile = ["style" => "display:none;"];
                $divInputUbicacion = ["style" => "display:none;"];
                $arrayFormatosMedios = FormatosMedios::devuelveFormatosPorCategoria(3);
                $datos = ["horaA" => "00",
                         "minutoA" => "00",
                         "segundoA" => "00",
                         "codAudio" => -1
                ];



                if ($_POST){

                    $nombreEjemplar = $ejemplar->getNombre();

                    //sacamos valores de ejemplar
                    if ($_POST[$nombreEjemplar]){
                        $ejemplar->setValores($_POST[$nombreEjemplar]);
                    }

                    //pongo fecha formato normal
                    if ($ejemplar->fecha_registro !== ""){//ahora esta bien la fecha
                        $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                    }

                    //actualizo combo
                    if ($_POST["obra"]) {
                        $ejemplar->cod_obra = intval($_POST["obra"]);
                    }

                    //sacamos los valores de audio
                    $nombreAudio = $audio->getNombre();

                    // if ($_POST[$nombreAudio]) {
                    //     $audio->setValores($_POST[$nombreAudio]);
                    // }
                    
                    //pregunto por los parametros de tiempo
                    $horaA  ="";
                    $minutoA  ="";
                    $segundoA  =""; 

                    //Preguntar por duración en post
                    if ($_POST["horaA"]){
                        $horaA = trim($_POST["horaA"]);

                    }

                    if ($_POST["minutoA"]){
                        $minutoA = trim($_POST["minutoA"]);

                    }

                    if ($_POST["segundoA"]){
                        $segundoA = trim($_POST["segundoA"]);

                    }

                    if ($horaA === ""){ //formato mm:ss o hh:mm:ss
                        $horaA = "00";

                    }
                    $duracion = $horaA. ":". $minutoA. ":".$segundoA;
                    $datos["horaA"] = $horaA;
                    $datos["minutoA"] = $minutoA;
                    $datos["segundoA"] = $segundoA;

                    $audio->duracion = $duracion;

                    //Ahora se pregunta por files
                    $tipoFisicoODigital = intval($ejemplar->cod_formato_ejemplar);

                    if($tipoFisicoODigital === -1){//da error

                        //se valida ejemplar que da error
                        $validaEjemplar = $ejemplar->validar();
                        $validarAudio = $audio->validar();

                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                        }

                        $datos["codAudio"] = intval($ejemplar->cod_obra);//actualizo combo


                         $this->dibujaVista("anadeEjemplarAudio", 
                            [

                                "ejemplar" => $ejemplar,
                                "audio" => $audio,
                                "arrayObras"=> $arrayObras,
                                "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                "divInputFile" => $divInputFile,
                                "divInputUbicacion"=> $divInputUbicacion,
                                "arrayFormatosMedios" => $arrayFormatosMedios,
                                "datos" => $datos,
                                    ],
                                "Añadir ejemplar audio"
                                    );
                            exit;
                    }

                    if($tipoFisicoODigital === 1){//fisico
                        
                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                        }

                        $validaEjemplar = $ejemplar->validar();
                        if ($validaEjemplar){

                            if ($ejemplar->fecha_registro !== ""){
                                $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                            }
                            
                            //Ahora guardo datos del ejemplar
                            $guardaEjemplar = $ejemplar->guardar();
                            if ($guardaEjemplar){

                                //validamos audio
                                $nombreAudio = $audio->getNombre();

                                // if ($_POST[$nombreAudio]){

                                    // $audio->setValores($_POST[$nombreAudio]);
                                    $audio->cod_ejemplar = intval($ejemplar->cod_ejemplar);
                                    $audio->cod_formato_medio = intval($ejemplar->codigo_formato_medio);
                                    $audio->duracion = $duracion;
                                    

                                    if ($audio->validar()){//se valida la audio

                                        if($audio->guardar()){
                                            $codEjemplar = intval($ejemplar->cod_ejemplar);
                                            header("location: " . Sistema::app()->generaURL(["ejemplares", "verEjemplar"], ["id" => $codEjemplar]));
                                            exit;
                                        }
                                        else{
                                            Sistema::app()->paginaError(404, "Problema al actualizar la base de datos") . PHP_EOL;
                                            exit;

                                        }
                                        

                                    }
                                    else{//error de validacion de la audio
                                        
                                        if ($ejemplar->fecha_registro !== ""){
                                            $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                        }
                                        
                                        $divInputFile = ["style" => "display:none;"];
                                        $divInputUbicacion = ["style" => "display:inline;"];
                                        $datos["codAudio"] = intval($ejemplar->cod_obra);//actualizo combo
            
            
                                        $this->dibujaVista("anadeEjemplarAudio", 
                                           [
               
                                               "ejemplar" => $ejemplar,
                                               "audio" => $audio,
                                               "arrayObras"=> $arrayObras,
                                               "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                               "divInputFile" => $divInputFile,
                                               "divInputUbicacion"=> $divInputUbicacion,
                                               "arrayFormatosMedios" => $arrayFormatosMedios,
                                               "datos" => $datos,
                                                   ],
                                               "Añadir ejemplar audio"
                                                   );
                                           exit;

                                    }
                                // }


                            }
                            else{
                                //error al guardar
                                Sistema::app()->paginaError(404, "Problema al actualizar la base de datos") . PHP_EOL;
                            }
                            
                        }
                        else{//NO SE VALIDA BIEN EN EL FISICO

                            if ($ejemplar->fecha_registro !== ""){
                                $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                            }
                            
                            $divInputFile = ["style" => "display:none;"];
                            $divInputUbicacion = ["style" => "display:inline;"];
                            $datos["codAudio"] = intval($ejemplar->cod_obra);//actualizo combo


                            $this->dibujaVista("anadeEjemplarAudio", 
                               [
   
                                   "ejemplar" => $ejemplar,
                                   "audio" => $audio,
                                   "arrayObras"=> $arrayObras,
                                   "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                   "divInputFile" => $divInputFile,
                                   "divInputUbicacion"=> $divInputUbicacion,
                                   "arrayFormatosMedios" => $arrayFormatosMedios,
                                   "datos" => $datos,
                                       ],
                                   "Añadir ejemplar audio"
                                       );
                               exit;
                        }

                    }

                    if($tipoFisicoODigital === 2){//digital
                        
                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                        }

                        if ($_FILES){
                        
                            $rutaArchivo = "";
                            $nombreEjemplar = $ejemplar->getNombre();
                            if (isset($_FILES[$nombreEjemplar]["name"]["ubicacion_ejemplar"])){

                                $nombreArchivo = trim($_FILES[$nombreEjemplar]["name"]["ubicacion_ejemplar"]);
                                $nombreArchivo = CGeneral::addSlashes($nombreArchivo);


                                if ($nombreArchivo !== ""){
                                    $ruta = "/audiovisual/audio";
                                    $ruta  .= "/".$nombreArchivo;
                                    $ejemplar->ubicacion_ejemplar =  $ruta;
                                    $rutaArchivo = RUTA_BASE. $ruta;
                                    $moverFichero = move_uploaded_file($_FILES[$nombreEjemplar]["tmp_name"]["ubicacion_ejemplar"], $rutaArchivo);

                                    if ($_POST[$ejemplar->getNombre()]){

                                        $ejemplar->setValores($_POST[$ejemplar->getNombre()]);
                                        $ejemplar->ubicacion_ejemplar =  $ruta;


                                        if ($moverFichero){
                                            
                                            if ($ejemplar->fecha_registro !== ""){
                                                $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                                            }


                                            if ($ejemplar->validar()){
                                                if ($ejemplar->fecha_registro !== ""){
                                                    $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                                }
                                                $guardaEjemplar = $ejemplar->guardar();
                                                if ($guardaEjemplar){

                                                    $nombreAudio = $audio->getNombre();

                                                    // $audio->setValores($_POST[$nombreAudio]);
                                                    $audio->cod_ejemplar = intval($ejemplar->cod_ejemplar);
                                                    $audio->cod_formato_medio = intval($ejemplar->codigo_formato_medio);
                

                                                    if ($audio->validar()){

                                                        if ($audio->guardar()){
                                                            $codEjemplar = intval($ejemplar->cod_ejemplar);
                                                            header("location: " . Sistema::app()->generaURL(["ejemplares", "verEjemplar"], ["id" => $codEjemplar]));
                                                            exit;
                                                        }
                                                        else{
                                                            //error al guardar
                                                            Sistema::app()->paginaError(404, "Problema al actualizar la base de datos") . PHP_EOL;
                                                            exit;
                                                        }

                                                    }
                                                    else{

                                                        if ($ejemplar->fecha_registro !== ""){
                                                            $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                                        }
                                                        $divInputFile = ["style" => "display:inline;"];
                                                        $divInputUbicacion = ["style" => "display:none;"];
                                                        $datos["codAudio"] = intval($ejemplar->cod_obra);//actualizo combo
            
            
                                                        $this->dibujaVista("anadeEjemplarAudio", 
                                                           [
                               
                                                               "ejemplar" => $ejemplar,
                                                               "audio" => $audio,
                                                               "arrayObras"=> $arrayObras,
                                                               "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                                               "divInputFile" => $divInputFile,
                                                               "divInputUbicacion"=> $divInputUbicacion,
                                                               "arrayFormatosMedios" => $arrayFormatosMedios,
                                                               "datos" => $datos,
                                                                   ],
                                                               "Añadir ejemplar audio"
                                                                   );
                                                           exit;
                                                    }

                                                }
                                                else{
                                                        //error al guardar
                                                        Sistema::app()->paginaError(404, "Problema al actualizar la base de datos") . PHP_EOL;
                                                        exit;
                                                }

                                            }
                                            else{//error al validar ejemplar

                                                if ($ejemplar->fecha_registro !== ""){
                                                    $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                                }
                                                $divInputFile = ["style" => "display:inline;"];
                                                $divInputUbicacion = ["style" => "display:none;"];
                                                $datos["codAudio"] = intval($ejemplar->cod_obra);//actualizo combo
    
    
                                                $this->dibujaVista("anadeEjemplarAudio", 
                                                   [
                       
                                                       "ejemplar" => $ejemplar,
                                                       "audio" => $audio,
                                                       "arrayObras"=> $arrayObras,
                                                       "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                                       "divInputFile" => $divInputFile,
                                                       "divInputUbicacion"=> $divInputUbicacion,
                                                       "arrayFormatosMedios" => $arrayFormatosMedios,
                                                       "datos" => $datos
                                                           ],
                                                       "Añadir ejemplar audio"
                                                           );
                                                   exit;
                                            }

                                        }
                                        else{//error al mover el fichero

                                            
                                            if ($ejemplar->fecha_registro !== ""){
                                                $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                            }

                                            //preguntar por posible error
                                            if (isset($_FILES[$nombreEjemplar]["error"]["ubicacion_ejemplar"])){
                                                $errores["ubicacion_ejemplar"] = "Problema al subir fichero";
                                            }

                                            
                                            $divInputFile = ["style" => "display:inline;"];
                                            $divInputUbicacion = ["style" => "display:none;"];
                                            $datos["codAudio"] = intval($ejemplar->cod_obra);//actualizo combo


                                            $this->dibujaVista("anadeEjemplarAudio", 
                                               [
                   
                                                   "ejemplar" => $ejemplar,
                                                   "audio" => $audio,
                                                   "arrayObras"=> $arrayObras,
                                                   "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                                   "divInputFile" => $divInputFile,
                                                   "divInputUbicacion"=> $divInputUbicacion,
                                                   "arrayFormatosMedios" => $arrayFormatosMedios,
                                                   "datos" => $datos,
                                                       ],
                                                   "Añadir ejemplar audio"
                                                       );
                                               exit;
                                        }
                                    }

                                }
                                else{//Página de error validar solamente el ejemplar

                                    if ($_POST[$ejemplar->getNombre()]){

                                        $ejemplar->setValores($_POST[$ejemplar->getNombre()]);

                                        if ($ejemplar->fecha_registro !== ""){
                                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                                        }
                                        $ejemplar->validar();

                                        
                                        $divInputFile = ["style" => "display:inline;"];
                                        $divInputUbicacion = ["style" => "display:none;"];
            
                                        $datos["codAudio"] = intval($ejemplar->cod_obra);//actualizo combo


                                        $this->dibujaVista("anadeEjemplarAudio", 
                                           [
               
                                               "ejemplar" => $ejemplar,
                                               "audio" => $audio,
                                               "arrayObras"=> $arrayObras,
                                               "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                               "divInputFile" => $divInputFile,
                                               "divInputUbicacion"=> $divInputUbicacion,
                                               "arrayFormatosMedios" => $arrayFormatosMedios,
                                               "datos" => $datos,
                                                   ],
                                               "Añadir ejemplar audio"
                                                   );
                                           exit;
                                    }

                                }
                            }
                        }
                    }



                }

                $this->dibujaVista("anadeEjemplarAudio", 
                                    [

                                    "ejemplar" => $ejemplar,
                                    "audio" => $audio,
                                    "arrayObras"=> $arrayObras,
                                    "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                    "divInputFile" => $divInputFile,
                                    "divInputUbicacion"=> $divInputUbicacion,
                                    "arrayFormatosMedios" => $arrayFormatosMedios,
                                    "datos" => $datos,
                                
                                    ],
                                "Añadir ejemplar audio"
                );

            }
            else{
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;
            }
        }
        else{
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }
  
    }


    public function accionAnadeEjemplar (){

        if (Sistema::app()->Acceso()->hayUsuario() === true) { //Hay usuario

            $permisos = Sistema::app()->Acceso()->puedePermiso(1) && Sistema::app()->Acceso()->puedePermiso(9);

            if ($permisos) { //tiene permisos

                //barra de ubicación
                $this->barraUbi = [
                    [
                        "texto" => "inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de ejemplar",
                        "url" => ["ejemplares", "IndexEjemplares"]
                    ],
                    [
                        "texto" => "Añade ejemplar ",
                        "url" => ["ejemplares", "AnadeEjemplar"]
                    ]
                ];

                //datos
                $arrayObras = Obras::dameObras();
                $ejemplar = new Ejemplares ();
                $pelicula = new Peliculas ();
                $audio = new Audio ();
                $libro = new Libros ();
                $arrayFormatoEjemplar = Formatos::devuelveFormatos();
                $divInputFile = ["style" => "display:none;"];
                $divInputUbicacion = ["style" => "display:none;"];
                $divPelicula = ["style" => "display:none;"];
                $divLibro = ["style" => "display:none;"];
                $divAudio = ["style" => "display:none;"];
                $arrayFormatosMedios = [];
                $datosForm =  [
                    "horaA" => "",
                    "minutoA" => "",
                    "segundoA" => "",
                    "horaP" => "",
                    "minutoP" => "",
                    "segundoP" => "",
                ];
                $arrayEdadesPeliculas = Peliculas::devuelveEdades();
                $arrayPaisesPeliculas = Peliculas::devuelvePaisesPeliculas();
                
                if ($_POST){

                    $nombreEjemplar = $ejemplar->getNombre();

                    //PRIMERO SE GUARDA EL EJEMPLAR
                    if ($_POST[$nombreEjemplar]){
                        $ejemplar->setValores($_POST[$nombreEjemplar]);
                        
                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                        }


                        
                    }

                    //AHORA SE GUARDA SI ES FILE O NO
                    if (intval($ejemplar->cod_formato_ejemplar) === 2){//Si es digital pregunto por FILES
                        $divInputFile = ["style" => "display:inline;"];
                        $divInputUbicacion = ["style" => "display:none;"];

                        $rutaArchivo  = "";
                        $moverFichero = false;
                        if ($_FILES){ //TU IMAGINATE QUE NO ENTRA EN FILE

                            if ($_FILES[$nombreEjemplar]["name"]["ubicacion_ejemplar"]){
                                $nombreArchivo = trim($_FILES[$nombreEjemplar]["name"]["ubicacion_ejemplar"]);
                                $nombreArchivo = CGeneral::addSlashes($nombreArchivo);
                            
                                $ruta = "/audiovisual";

                                if (intval($ejemplar->cod_categoria_obra) === 1){//libro
                                    $ruta.="/libro";
                                }


                                if (intval($ejemplar->cod_categoria_obra) === 2){//pelicula
                                    $ruta.="/pelicula";

                                }


                                if (intval($ejemplar->cod_categoria_obra) === 3){//audio
                                    $ruta.="/audio";

                                }
                            
                                
                                //Ubicamos el fichero
                                $ruta  .= "/".$nombreArchivo;

                                $rutaArchivo = RUTA_BASE. $ruta;
                                $moverFichero = move_uploaded_file($_FILES["ejemplares"]["tmp_name"]["ubicacion_ejemplar"], $rutaArchivo);
                        
                            
                            }
                        }

                        //AHORA MIRO SI LIBRO, PELICULA O AUDIO
                        //LO GUARDO EN UNA VARIABLE AUXILIAR
                        //LUEGO LO DEVUELVO A SU VAR CORRESPONDIENTE
                        //Y MUESTRO U OCULTO EL DIV CORRESPONDIENTE
                        $tipoObra = $ejemplar->cod_categoria_obra;
                        $datosAdicionalesEjemplar = false; //me guarda la validación

                        if ($tipoObra === 1) { //libro

                            if ($_POST[$libro->getNombre()]){
                                $libro->setValores($_POST[$libro->getNombre()]);
                                $datosAdicionalesEjemplar = $libro->validar();
                            }
                            
                        }
                        if ($tipoObra === 2) { //pelicula

                            $horaP  = "";
                            $minutoP  = "";
                            $segundoP  = "";

                            //Preguntar por duración en post
                            if ($_POST["horaP"]) {
                                $horaP = trim($_POST["horaP"]);
                            }

                            if ($_POST["minutoP"]) {
                                $minutoP = trim($_POST["minutoP"]);
                            }

                            if ($_POST["segundoP"]) {
                                $segundoP = trim($_POST["segundoP"]);
                            }

                            if ($horaP === "") { //formato mm:ss o hh:mm:ss
                                $horaP = "00";
                            }

                            $duracion = $horaP . ":" . $minutoP . ":" . $segundoP;
                            $pelicula->duracion = $duracion;

                            if ($_POST[$pelicula->getNombre()]){
                                $pelicula->setValores($_POST[$pelicula->getNombre()]);
                                $datosAdicionalesEjemplar = $pelicula->validar();

                            }
                        }
                        if ($tipoObra === 3) { //audio

                            $horaA  = "";
                            $minutoA  = "";
                            $segundoA  = "";


                            //Preguntar por duración en post
                            if ($_POST["horaA"]) {
                                $horaA = trim($_POST["horaA"]);
                            }

                            if ($_POST["minutoA"]) {
                                $minutoA = trim($_POST["minutoA"]);
                            }

                            if ($_POST["segundoA"]) {
                                $segundoA = trim($_POST["segundoA"]);
                            }

                            if ($horaA === "") { //formato mm:ss o hh:mm:ss
                                $horaA = "00";
                            }

                            $duracion = $horaA . ":" . $minutoA . ":" . $segundoA;
                            $audio->duracion = $duracion;

                            if ($_POST[$audio->getNombre()]){
                                $audio->setValores($_POST[$audio->getNombre()]);
                                $datosAdicionalesEjemplar = $audio->validar();
                            }
                        }

                        //VALIDO EJEMPLAR
                        $validaEjemplar = $ejemplar->validar();

                        if ($validaEjemplar && $datosAdicionalesEjemplar && $moverFichero){ //ahora se guarda

                        }
                        else{ //errores

                            //COMPRUEBO SI HAY ERROR EN FICHERO
                        }
                        
                    }
                    if (intval($ejemplar->cod_formato_ejemplar) === 1){ //SI FICHERO
                        $divInputFile = ["style" => "display:none;"];
                        $divInputUbicacion = ["style" => "display:inline;"];

                        //ES DECIR FISICO

                        
                        //AHORA MIRO SI LIBRO, PELICULA O AUDIO
                        //LO GUARDO EN UNA VARIABLE AUXILIAR
                        //LUEGO LO DEVUELVO A SU VAR CORRESPONDIENTE
                        //Y MUESTRO U OCULTO EL DIV CORRESPONDIENTE
                        $tipoObra = $ejemplar->cod_categoria_obra;
                        if ($tipoObra === 2){//pelicula
                        }
                        if ($tipoObra === 2){//pelicula
                        }
                        if ($tipoObra === 2){//pelicula
                        }

                    }

                    
                }


                $this->dibujaVista("anadeEjemplar", [
                                    "arrayObras" => $arrayObras,
                                    "arrayFormatoEjemplar" => $arrayFormatoEjemplar,
                                    "ejemplar" => $ejemplar,
                                    "divInputFile" => $divInputFile,
                                    "divInputUbicacion" => $divInputUbicacion,
                                    "arrayFormatosMedios" => $arrayFormatosMedios,
                                    "pelicula" => $pelicula,
                                    "audio" => $audio,
                                    "libro" => $libro,
                                    "divAudio" => $divAudio,
                                    "divLibro" => $divLibro,
                                    "divPelicula" => $divPelicula,
                                    "datosForm" => $datosForm,
                                    "arrayEdadesPeliculas" => $arrayEdadesPeliculas,
                                    "arrayPaisesPeliculas" => $arrayPaisesPeliculas
                                    ], "Añade Ejemplar");



            }
            else{//no tiene permisos
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;
            }

        }
        else{//No está registrado
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }

    }



    public function accionAnadeEjemplar01()
    {

        if (Sistema::app()->Acceso()->hayUsuario() === true) {

            if (
                Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ) {

                $ejemplar = new Ejemplares();
                $libro = new Libros ();
                $audio = new Audio ();
                $pelicula = new Peliculas ();
                $formatos = new Formatos ();    
                $arrayObras = Obras::dameObras();
            

                $divOculto = ["style" => "display:none;"];
                $divPelicula = ["style" => "display:none;"];
                $divLibro = ["style" => "display:none;"];
                $divAudio = ["style" => "display:none;"];
                $divInputFile = ["style" => "display:none;"];
                $divInputUbicacion = ["style" => "display:none;"];
                $generosObras = GenerosObras::dameTodosGeneros();
                $arrayFormatos = $formatos::devuelveFormatos();
                $arrayFormatosMedios = [];
                $arrayEdadesPeliculas = Peliculas::devuelveEdades();
                $arrayPaisesPeliculas = Peliculas::devuelvePaisesPeliculas();
                
                $datosForm =  [
                    "horaA" => "",
                    "minutoA" => "",
                    "segundoA" => "",
                    "horaP" => "",
                    "minutoP" => "",
                    "segundoP" => "",
                ];


                $this->barraUbi = [
                    [
                        "texto" => "inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de ejemplar",
                        "url" => ["ejemplares", "IndexEjemplares"]
                    ],
                    [
                        "texto" => "Añadir ejemplar ",
                        "url" => ["ejemplares", "anadeEjemplar"]
                    ]
                ];


                if ($_POST){
                    //desde aqui puedo validar los formatos del input file

                    //Ejemplar
                    $nombreEjemplar = $ejemplar->getNombre();
                    if ($_POST[$nombreEjemplar]){

                        $ejemplar->setValores($_POST[$nombreEjemplar]);

                        if ($ejemplar->fecha_registro !== ""){
                            $ejemplar->fecha_registro = CGeneral::fechaMysqlANormal($ejemplar->fecha_registro);
                        }


                        //Mostramos el div que oculta el resto de datos
                        $divOculto = ["style" => "display:block;"];



                        //Datos adicionales
                        //Preguntamos por el tipo de obra que es libro, pelicula o audio
                        $datosAdicionalesEjemplar = "";
                        $nombreDatosAdicionalesEjemplar = "";
                        if ($ejemplar->cod_categoria_obra === 1){//libro
                            $datosAdicionalesEjemplar = $libro;
                            $nombreDatosAdicionalesEjemplar = $datosAdicionalesEjemplar->getNombre();
                            
                            if (isset($_POST[$nombreDatosAdicionalesEjemplar])){
                                $datosAdicionalesEjemplar->setValores($_POST[$nombreDatosAdicionalesEjemplar]);
                            }
                            

                            //Ahora se actualizan los styles de los diferentes divs
                            $divPelicula = ["style" => "display:none;"];
                            $divLibro = ["style" => "display:inline;"];
                            $divAudio = ["style" => "display:none;"];
                        }


                        if ($ejemplar->cod_categoria_obra === 2){//pelicula
                            $datosAdicionalesEjemplar  = $pelicula;
                            $nombreDatosAdicionalesEjemplar = $datosAdicionalesEjemplar->getNombre();

                            if (isset($_POST[$nombreDatosAdicionalesEjemplar])){
                                $datosAdicionalesEjemplar->setValores($_POST[$nombreDatosAdicionalesEjemplar]);
                            }

                            $horaP  ="";
                            $minutoP  ="";
                            $segundoP  =""; 

                            //Preguntar por duración en post
                            if ($_POST["horaP"]){
                                $horaP = trim($_POST["horaP"]);

                            }

                            if ($_POST["minutoP"]){
                                $minutoP = trim($_POST["minutoP"]);

                            }

                            if ($_POST["segundoP"]){
                                $segundoP = trim($_POST["segundoP"]);

                            }

                            if ($horaP === ""){ //formato mm:ss o hh:mm:ss
                                $horaP = "00";

                            }

                            $duracion = $horaP. ":". $minutoP. ":".$segundoP;
                            $datosAdicionalesEjemplar->duracion = $duracion;


                            //Ahora se actualizan los styles de los diferentes divs
                            $divPelicula = ["style" => "display:inline;"];
                            $divLibro = ["style" => "display:none;"];
                            $divAudio = ["style" => "display:none;"];
                        }


                        
                        if ($ejemplar->cod_categoria_obra === 3){//audio
                            $datosAdicionalesEjemplar = $audio;
                            $nombreDatosAdicionalesEjemplar = $datosAdicionalesEjemplar->getNombre();
                            
                            if (isset($_POST[$nombreDatosAdicionalesEjemplar])){
                                $datosAdicionalesEjemplar->setValores($_POST[$nombreDatosAdicionalesEjemplar]);
                            }
                            
                            //Preguntar por duración en post
                            if ($_POST["horaA"]){
                                $horaA = trim($_POST["horaA"]);

                            }

                            if ($_POST["minutoA"]){
                                $minutoA = trim($_POST["minutoA"]);

                            }

                            if ($_POST["segundoA"]){
                                $segundoA = trim($_POST["segundoA"]);

                            }

                            if ($horaA === ""){ //formato mm:ss o hh:mm:ss
                                $horaA = "00";

                            }

                            $duracion = $horaA. ":". $minutoA. ":".$segundoA;
                            $datosAdicionalesEjemplar->duracion = $duracion;
                            
                            //Actualizamos los styles de los diferentes divs
                            $divPelicula = ["style" => "display:none;"];
                            $divLibro = ["style" => "display:none;"];
                            $divAudio = ["style" => "display:inline;"];
                        
                        }


                    }

                    if (intval($ejemplar->cod_formato_ejemplar) === 1){//fisico
                        $divInputFile = ["style" => "display:none;"];
                        $divInputUbicacion = ["style" => "display:inline;"];
                    }


                    $moverFichero = false; //aqui comprobamos que se ha guardado el fichero
                    $esValido = false;

                    //Si es digital se pregunta por file
                    //y se actualiza el valor de ruta ejemplar
                    if (intval($ejemplar->cod_formato_ejemplar) === 2){

                        $divInputFile = ["style" => "display:inline;"];
                        $divInputUbicacion = ["style" => "display:none;"];

                        $rutaArchivo = "";
                        $nombreArchivo = $ejemplar->getNombre();
                        if (isset($_FILES[$nombreArchivo]["name"]["ubicacion_ejemplar"])){ //Se ha subido fichero


                            $nombreArchivo = trim($_FILES[$nombreEjemplar["name"]["ubicacion_ejemplar"]]);
                            $nombreArchivo = CGeneral::addSlashes($nombreArchivo);

                            if ($nombreArchivo !== ""){//Se ha subido fichero

                                //En caso de estar la cadena vacía, al validarse
                                //va a dar error en los ejemplares

                                //Comprobamos la extensión del fichero
                                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                                $tipoArchivo = mime_content_type($_FILES[$nombreEjemplar]["tmp_name"]["ubicacion_ejemplar"]);


                                //Comprobamos el MIME SEGÚN EL TIPO DE OBRA
                                switch (intval($ejemplar->cod_categoria_obra)) {
                                    case 1: 
                                        if (in_array($extension, ["pdf", "epub"])) {
                                            $esValido = true;
                                        }
                                        break;
                                    case 2: 
                                        if (strpos($tipoArchivo, "video") !== false) {
                                            $esValido = true;
                                        }
                                        break;
                                    case 3: 
                                        if (strpos($tipoArchivo, "audio") !== false) {
                                            $esValido = true;
                                        }
                                        break;
                                    default:
                                        $esValido = false;
                                }                    

                                $ruta = "/audiovisual";

                                if (intval($ejemplar->cod_categoria_obra) === 1){//libro
                                    $ruta.="/libro";
                                }


                                if (intval($ejemplar->cod_categoria_obra) === 2){//pelicula
                                    $ruta.="/pelicula";

                                }


                                if (intval($ejemplar->cod_categoria_obra) === 3){//audio
                                    $ruta.="/audio";

                                }

                                //Ubicamos el fichero
                                $ruta  .= "/".$nombreArchivo;

                                //actualizo valor ubicacion
                                $ejemplar->ubicacion_ejemplar =  $ruta; //guardamos ruta

                                //Movemos archivo a nuestro sitio
                                $rutaArchivo = RUTA_BASE. $ruta;
                                $moverFichero = move_uploaded_file($_FILES["ejemplares"]["tmp_name"]["ubicacion_ejemplar"], $rutaArchivo);
                                

                            }

                        }
                    } //FIN FILE DIGITAL

                    //Ahora pregunto por datosAdicionalesEjemplar
                    //Si no existe mandamos error
                    //mostramos el div principal
                    //formateamos fecha

                    if ($datosAdicionalesEjemplar === ""){

                        //Al no existir los datos adicionales da error
                        $ejemplar->setValores($_POST[$ejemplar->getNombre()]);

                        //Aquí es donde llega la primera vez
                        $this->dibujaVista("anadeEjemplar", [
                            "arrayObras" => $arrayObras,
                            "ejemplar" => $ejemplar,
                            "libro" => $libro,
                            "generosObras" => $generosObras,
                            "divOculto" => $divOculto,
                            "divPelicula" => $divPelicula,
                            "divInputFile" => $divInputFile,
                            "divInputUbicacion" => $divInputUbicacion,
                            "divAudio" => $divAudio,
                            "divLibro" => $divLibro,
                            "pelicula" => $pelicula,
                            "arrayEdades" => $arrayEdadesPeliculas,
                            "arrayPaisesPeliculas" => $arrayPaisesPeliculas,
                            "audio" => $audio,
                            "arrayFormatos" => $arrayFormatos,
                            "arrayFormatosMedios" => $arrayFormatosMedios,
                            "datosForm" => $datosForm
                        ], "Crear ejemplar");

                    }
                    else{//EXISTEN DATOS ADICIONALES


                            //Validamos datos
                            $validarEjemplar = $ejemplar->validar();
                            $validaDatosAdicionales = $datosAdicionalesEjemplar->validar();

                            if ($validarEjemplar && $validaDatosAdicionales && $moverFichero && $esValido) {

                                //Se han validado los datos, ahora se guardan
                                $guardaEjemplar = $ejemplar->guardar();
                                $guardarDatosAdicionales = $datosAdicionalesEjemplar->guardar();

                                if ($guardaEjemplar && $guardarDatosAdicionales) {

                                    //Se lleva a ver ejemplar
                                    $codEjemplar = intval($ejemplar->cod_ejemplar);
                                    header("location: " . Sistema::app()->generaURL(
                                        ["ejemplares", "verEjemplar"],
                                        ["id" => $codEjemplar]
                                    ));
                                    exit;
                                } 
                                else { //Error al guardar los datos
                                    Sistema::app()->paginaError(404, "Problema en la bbdd al guardar datos del ejemplar");
                                    exit;
                                }
                            } 

                            else { //No se validan los datos, se muestran

                                if (isset($_FILES[$nombreEjemplar]["error"]["ubicacion_ejemplar"])) {
                                    $errores["ubicacion_ejemplar"] = "Problema al subir fichero";
                                }

                                if ($esValido === false) {
                                    $esValido = "Formato incorrecto, debe ser adecuado al tipo video, audio o libro: pdf o ebook";
                                }

                                //Formateamos cadena de fecha
                                if ($ejemplar->fecha_registro !== ""
                                ) {
                                    $ejemplar->fecha_registro = CGeneral::fechaNormalAMysql($ejemplar->fecha_registro);
                                }

                                //Asignamos los datos adicionales al modelo indicado
                                if ($ejemplar->cod_categoria_obra === 1) { //libro}
                                    $libro = $datosAdicionalesEjemplar;
                                }
                                if ($ejemplar->cod_categoria_obra === 2) { //pelicula}
                                    $pelicula = $datosAdicionalesEjemplar;
                                }
                                if ($ejemplar->cod_categoria_obra === 3) { //audio}
                                    $audio = $datosAdicionalesEjemplar;
                                }

                                $this->dibujaVista("anadeEjemplar", [
                                    "arrayObras" => $arrayObras,
                                    "ejemplar" => $ejemplar,
                                    "libro" => $libro,
                                    "generosObras" => $generosObras,
                                    "divOculto" => $divOculto,
                                    "divPelicula" => $divPelicula,
                                    "divInputFile" => $divInputFile,
                                    "divInputUbicacion" => $divInputUbicacion,
                                    "divAudio" => $divAudio,
                                    "divLibro" => $divLibro,
                                    "pelicula" => $pelicula,
                                    "arrayEdades" => $arrayEdadesPeliculas,
                                    "arrayPaisesPeliculas" => $arrayPaisesPeliculas,
                                    "audio" => $audio,
                                    "arrayFormatos" => $arrayFormatos,
                                    "arrayFormatosMedios" => $arrayFormatosMedios,
                                    "datosForm" => $datosForm,
                                    "esValido" => $esValido,
                                    "errores" => $errores
                                ], "Crear ejemplar");
                                exit;
                            }
                    } //SI EXISTE DATOS ADICIONALES


                } //FIN DEL POST


                //Aquí es donde llega la primera vez
                $this->dibujaVista("anadeEjemplar", [
                    "arrayObras" => $arrayObras,
                    "ejemplar" => $ejemplar,
                    "libro" => $libro,
                    "generosObras" => $generosObras,
                    "divOculto" => $divOculto,
                    "divPelicula" => $divPelicula,
                    "divInputFile" => $divInputFile,
                    "divInputUbicacion" => $divInputUbicacion,
                    "divAudio" => $divAudio,
                    "divLibro" => $divLibro,
                    "pelicula" => $pelicula,
                    "arrayEdades" => $arrayEdadesPeliculas,
                    "arrayPaisesPeliculas" => $arrayPaisesPeliculas,
                    "audio" => $audio,
                    "arrayFormatos" => $arrayFormatos,
                    "arrayFormatosMedios" => $arrayFormatosMedios,
                    "datosForm" => $datosForm
                ], "Crear ejemplar");



            } else { //En caso de no acceder con los permisos
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;
            }
        } else { //En caso de no acceder logueado
            Sistema::app()->irAPagina(["login", "InicioSesion"]);
        }
    }



    /**
     * 
     *
     * @return void
     */
    public function accionPeticionFormatosEjemplares()
    {


        $respuesta = [];


        if (Sistema::app()->Acceso()->hayUsuario() === true) {

            if (
                Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ) {

                if ($_POST) {
                    //me tienen que llegar dos cosas por parámetro el formato y el tipo de obra

                    $codTipoObra = "";
                    if (isset($_POST["codTipoObra"])) {

                        $codTipoObra = intval($_POST["codTipoObra"]);
                    }

                    $codFormatoEjemplar = "";
                    if (isset($_POST["codFormatoEjemplar"])) {

                        $codFormatoEjemplar = intval($_POST["codFormatoEjemplar"]);
                    }



                    if ($codTipoObra !== "" && $codFormatoEjemplar !== "") {

                        $formatoMedio  = new FormatosMedios();

                        $arrayRespuesta =  $formatoMedio::devuelveFormatosMedios(null, $codTipoObra, $codFormatoEjemplar);


                        if ($arrayRespuesta !== false) {

                            $respuesta = [
                                "correcto" => true,
                                "respuesta" => $arrayRespuesta
                            ];
                        } else {

                            $respuesta = [
                                "correcto" => false,
                                "respuesta" => "No se han encotrado formatos con los parámetros enviados"
                            ];
                        }
                    } else { //Si llega algun de los dos parámetros vacios, mandamos página de error

                        $respuesta = [
                            "correcto" => false,
                            "respuesta" => "Los parámetros han llegado vacíos"
                        ];
                    }

                    echo json_encode($respuesta); //se imprime respuesat



                }
            }
        } else {
            Sistema::app()->irAPagina(["login", "InicioSesion"]);
        }
    }
}
