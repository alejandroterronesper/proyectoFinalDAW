<?php

/**
 *  Controlador del modelo de obras contiene las siguientes acciones
 * index: que nos muestra un crud de las obras
 * ver obra
 * modficiar obra
 * borrar obra
 * añadir obra
 */
class obrasControlador extends CControlador {


    /**
     * Acción de index de obras, 
     * controlamos que haya usuario registrado si no, lo enviamos al login
     * Si está registrado comprobamos permisos, esta vista
     * tiene un CRUD de la tabla de obras, se muestran las diferentes obras
     * y sus opciones de ver, modificar y borrar
     * 
     * Además podemos crear obras nuevas
     *
     * @return Void -> se imprime la vista
     */
    public function accionIndexObras(): Void{


        if (Sistema::app()->Acceso()->hayUsuario() === true) {


            if (
                Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ) {

                $this->barraUbi = [
                    [
                        "texto" => "inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de obras",
                        "url" => ["obras", "IndexObras"]
                    ]
                ];

                //Array para guardar filtrado
                if (!isset($_SESSION["arrayFiltradoIndexObras"])) {
                    $_SESSION["arrayFiltradoIndexObras"] = [
                        "tipoObra" => "",
                        "borrado" => "",
                        "selectWhere" => "",

                    ];
                }                


                $datos = [
                    "tipoObra" => $_SESSION["arrayFiltradoIndexObras"]["tipoObra"],
                    "borrado"=> $_SESSION["arrayFiltradoIndexObras"]["borrado"]
                ];

                $selectWhere = "";


                if ($_POST){


                    if (isset($_POST["filtradoObrasIndex"])){


                        $tipoObra = "";
                        if (isset($_POST["tipoObra"])){


                            $tipoObra = intval($_POST["tipoObra"]);

                            if ($tipoObra !== 0){
                                $selectWhere = " `cod_categoria_obra` = $tipoObra ";
                            }
                        }
                        $datos["tipoObra"] = $tipoObra;

                        
                        if (isset($_POST["borrado"])){


                            $borrado = trim($_POST["borrado"]);

                            if($borrado !== ""){

                                if ($selectWhere !== ""){//select con opcion

                                    if ($borrado === "NO"){

                                        $selectWhere .= " AND borrado = 0";

                                    }

                                    if ($borrado === "SI"){

                                        $selectWhere .= " AND borrado = 1";


                                    }

                                }
                                else{ //select vacio

                                    if ($borrado === "NO"){

                                        $selectWhere .= "  borrado = 0";

                                    }

                                    if ($borrado === "SI"){

                                        $selectWhere .= "  borrado = 1";


                                    }
                                }

                            }

                        }
                        $datos["borrado"] = $borrado;

                    }



                    //limpiar filtrado
                    if (isset($_POST["limpiarFiltradoObrasIndex"])){
                            $datos["tipoObra"] ="";
                            $datos["borrado"] ="";
                            $selectWhere = "";

                    }


                    $_SESSION["arrayFiltradoIndexObras"] = [
                        "tipoObra" => $datos["tipoObra"],
                        "borrado" => $datos["borrado"],
                        "selectWhere" => $selectWhere,

                    ];
                }

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

                $obras = new Obras();


                if (isset($_SESSION["arrayFiltradoIndexObras"]["selectWhere"])  && $_SESSION["arrayFiltradoIndexObras"]["selectWhere"] !== ""){

                    $selectWhere = $_SESSION["arrayFiltradoIndexObras"]["selectWhere"];

                }



                if ($selectWhere !== "") {

                    $filas = $obras->buscarTodos(
                        [
                            "where" => $selectWhere,
                            "limit" => $limite
                        ]
                    );
                } 
                else {
                    $filas = $obras->buscarTodos(
                        [
                            "limit" => $limite
                        ]
                    );
                }

                $arrayCategorias = CategoriasObras::dameCategoriasObras(null);
                $arrayBorrado = [
                    "NO" => "NO",
                    "SI" => "SI"
                ];

                //Añadimos las opciones de la tabla 
                foreach ($filas as $clave => $valor) {

                    $valor["cod_obra"] = intval($valor["cod_obra"]);
                    $valor["cod_genero"] = intval($valor["cod_genero"]);
                    $valor["cod_categoria_obra"] = intval($valor["cod_categoria_obra"]);
                    $valor["borrado"] = intval($valor["borrado"]);
                    $valor["categoriaObra"] =  mb_strtoupper($arrayCategorias[$valor["cod_categoria_obra"]]);
                    $valor["fecha_lanzamiento"] = CGeneral::fechaMysqlANormal($valor["fecha_lanzamiento"]);
                    $valor["generoObra"] = GenerosObras::devuelveGenerosObras($valor["cod_genero"], null);


                    $valor["oper"] = CHTML::link(
                        CHTML::imagen("/imagenes/24x24/ver.png", "", ["title" => "Ver producto"]),
                        Sistema::app()->generaURL(["obras", "verObra"], ["id" => $valor["cod_obra"]])
                    ) . " " .
                        CHTML::link(
                            CHTML::imagen("/imagenes/24x24/modificar.png", "", ["title" => "Modificar producto"]),
                            Sistema::app()->generaURL(["obras", "modificarObra"], ["id" => $valor["cod_obra"]])
                        );


                    if ($valor["borrado"] === 0) {
                        $valor["oper"] .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png", "",
                                        ["title" => "Borrar producto"]),
                                        Sistema::app()->generaURL(["obras", "borrarObra"],
                                        ["id" => $valor["cod_obra"]]));
                    }

                    if ($valor["borrado"] === 0) {
                        $valor["borrado"] = "NO";
                    }

                    if ($valor["borrado"] === 1) {
                        $valor["borrado"] = "SI";
                    }

                    $filas[$clave] = $valor;
                }


                //No se mostrará el campo cod_producto ni cod_categoría. 
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
                        "ETIQUETA" => "Distribuidora",
                        "CAMPO" => "distribuidora"
                    ],
                    [
                        "ETIQUETA" => "Fecha de lanzamiento",
                        "CAMPO" => "fecha_lanzamiento"
                    ],
                    // [ ARREGLAR ESTO
                    //     "ETIQUETA" => "Descripción",
                    //     "CAMPO" => "descripcion"
                    // ],
                    [
                        "ETIQUETA" => "Foto",
                        "CAMPO" => "foto"
                    ],
                    [
                        "ETIQUETA" => "Tipo de obra",
                        "CAMPO" => "categoriaObra"
                    ],
                    [
                        "ETIQUETA" => "Género de obra",
                        "CAMPO" => "generoObra"
                    ],
                    [
                        "ETIQUETA" => "Borrado",
                        "CAMPO" => "borrado"
                    ],
                    [
                        "ETIQUETA" => "operaciones",
                        "CAMPO" => "oper"
                    ]
                ];



                //opciones del paginador
                $opcPaginador = array(
                    "URL" => Sistema::app()->generaURL(array("obras", "indexObras")),
                    "TOTAL_REGISTROS" => $obras->buscarTodosNRegistros($selectWhere !== "" ? ["where" => $selectWhere] : []),
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



                //Vista
                $this->dibujaVista("indexObras", [
                    "cabecera" => $cabecera,
                    "filas" => $filas,
                    "paginador" => $opcPaginador,
                    " opBorrar" => $arrayBorrado,
                    "arrayCategorias" => $arrayCategorias,
                    "datos" => $datos
                ], "Control de obras") . PHP_EOL;


            }
            
            else { //En caso de acceder como usuario normal o superadmin

                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;
            }


        } 

        
        else { //Si no hay usuario registrado, lo llevamos al login
            Sistema::app()->irAPagina(["login", "InicioSesion"]);
        }

    }


    /**
     * Acción que permise visualizar los datos de una obra
     * desde un formulario, se incluye diferentes opcioens como modificar 
     * borrar la obra, volver atrás y volver al inicio
     *
     * @return Void -> se devuelve un formulario con los datos rellenos de la obra
     */
    public function accionVerObra(): Void{


        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ){

                
                $id = "";
                if ($_GET){

                    if (isset($_GET["id"])){
                        $id = intval($_GET["id"]);
                    }
                }

                if ($id === ""){//en caso de no recibir parámetro id
                    Sistema::app()->paginaError(404, "Se ha accedido con un parámetro distinto al id");
                    exit;
                }


                //Se comprueba que el id introducido existe en la tabla 

                $obra = new Obras ();

                if ($obra->buscarPorId($id) === true){


                    $this->barraUbi = [
                        [
                            "texto" => "inicio",
                            "url" => "/"
                        ],
                        [
                            "texto" => "Control de obras",
                            "url" => ["obras", "IndexObras"]
                        ],
                        [
                            "texto" => "Ver obra ",
                            "url" => ["obras", "verObra/?id=$id"]
                        ]
                    ];

                    $arrayCategoriasObras = CategoriasObras::dameCategoriasObras(null);

                

                    $this->dibujaVista("verObra", ["obra"=> $obra,
                                                    "categorias" =>$arrayCategoriasObras
                                                ], "Ver obra ". $obra->titulo);



                }
                else{
                    Sistema::app()->paginaError(404, "No se ha encontrado una obra con el código indicado");
                    exit;
                }
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
     * Acción que muestra un formulario con la obra seleccionada
     * se permiten modificar los datos en un formulario, se validarán
     * si están correctos se lleva a la página de ver obra, si no
     * se muestran las diferentes errores, tiene opciones adicionales
     * como ver, borrar, volver atras y volver al inicio
     *
     * @return Void -> No devuelve nada, imprime la obra
     */
    public function accionModificarObra(): Void{

        
        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ){

                $id = "";
                if ($_GET){

                    if (isset($_GET["id"])){
                        $id = intval($_GET["id"]);
                    }
                }

                if ($id === ""){//en caso de no recibir parámetro id
                    Sistema::app()->paginaError(404, "Se ha accedido con un parámetro distinto al id");
                    exit;
                }

                $obra = new Obras ();

                if ($obra->buscarPorId($id) === true){

                    $this->barraUbi = [
                        [
                            "texto" => "inicio",
                            "url" => "/"
                        ],
                        [
                            "texto" => "Control de obras",
                            "url" => ["obras", "IndexObras"]
                        ],
                        [
                            "texto" => "Modificar obra ",
                            "url" => ["obras", "modificarObra/?id=$id"]
                        ]
                    ];

                    $arrayCategoriasObras = CategoriasObras::dameCategoriasObras(null);
                    $arrayGenerosObras = GenerosObras::devuelveGenerosObras(null, $obra->cod_categoria_obra);

                    if ($_POST){
                        $nombre = $obra->getNombre();

                        if (isset($_POST[$nombre])){
                            $valoresNuevos = $_POST[$nombre];
                            $obra->setValores($valoresNuevos);


                            if (!$obra->validar()){
                                //AQUI HAY ERROR

                                if (intval($obra->cod_categoria_obra) === -1){
                                    $arrayGenerosObras = [];
                                }
                                else{
                                    $arrayGenerosObras = GenerosObras::devuelveGenerosObras(null, $obra->cod_categoria_obra);

                                }

                                $this->dibujaVista("modificarObra", ["obra" => $obra, 
                                                        "categorias" => $arrayCategoriasObras,
                                                        "generosObras" => $arrayGenerosObras
                                                        ], "Modificar obra ". $obra->titulo);
                                exit;

                            }
                            else{

                                if ($_FILES){

                                    $fotoNueva = "";

                                    if (isset($_FILES["obras"]["name"]["foto"])){

                                        $fotoNueva = trim($_FILES["obras"]["name"]["foto"]);
                                        $fotoNueva = CGeneral::addSlashes($fotoNueva);

                                        if ($fotoNueva !== ""){ //Comprobamos si se ha subido foto nueva
                                            $obra->foto = $fotoNueva;
                                            $rutaImagen = RUTA_BASE. "/imagenes/obras/".$fotoNueva;
        
                                            if (!move_uploaded_file($_FILES["obras"]["tmp_name"]["foto"], $rutaImagen)){    
                                                $arrayGenerosObras = GenerosObras::devuelveGenerosObras(null, $obra->cod_categoria_obra);
                                            
                                                $this->dibujaVista("modificarObra", ["obra" => $obra, 
                                                "categorias" => $arrayCategoriasObras,
                                                "generosObras" => $arrayGenerosObras,
                                                "errorFoto" => "La foto no se ha podido subir al servidor"
                                                ], "Modificar obra ". $obra->titulo);
                                                exit;
                                            }
                                        }

                                    }


                                    if ($obra->guardar() === true){
                                        $cod_obra = intval($obra->cod_obra);
                                        header("location: ". Sistema::app()->generaURL(["obras", "verObra"], ["id"=>$cod_obra]));
                                        exit;
                                    }
                                    else{ //error no se guarda
                                        $arrayGenerosObras = GenerosObras::devuelveGenerosObras(null, $obra->cod_categoria_obra);

                                        $this->dibujaVista("modificarObra", ["obra" => $obra, 
                                        "categorias" => $arrayCategoriasObras
                                        ], "Modificar obra ". $obra->titulo);
                                        exit;

                                    }
                                }
                            }
                        }
                    }

                    //VISTA
                    $this->dibujaVista("modificarObra", ["obra" => $obra, 
                                                        "categorias" => $arrayCategoriasObras,
                                                        "generosObras" => $arrayGenerosObras
                                                        ], "Modificar obra ". $obra->titulo);

                }
                else{//ERROR
                    Sistema::app()->paginaError(404, "No se ha encontrado una obra con el código indicado");
                    exit;  
                }

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
     * Acción que contiene un formulario para el borrado lógico
     * de una obra, se comprueba usuario registrado, permisos
     * y si la obra ha sido borrada o nos
     *
     * @return Void no devuelve nada se imprime una vista
     */
    public function accionBorrarObra(){
        
        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ){

                $id = "";
                if ($_GET){

                    if (isset($_GET["id"])){
                        $id = intval($_GET["id"]);
                    }
                }


                if ($id === ""){//en caso de no recibir parámetro id
                    Sistema::app()->paginaError(404, "Se ha accedido con un parámetro distinto al id");
                    exit;
                }

                
                //Se comprueba que el id introducido existe en la tabla 

                $obra = new Obras ();

                if ($obra->buscarPorId($id) === true){

                    //Se comprueba que la obra no haya sido borrada

                    $borrado = intval($obra->borrado);

                    if ($borrado === 0){

                        $this->barraUbi = [
                            [
                                "texto" => "inicio",
                                "url" => "/"
                            ],
                            [
                                "texto" => "Control de obras",
                                "url" => ["obras", "IndexObras"]
                            ],
                            [
                                "texto" => "Borrar obra ",
                                "url" => ["obras", "borrarObra/?id=$id"]
                            ]
                        ];
                        $arrayCategoriasObras = CategoriasObras::dameCategoriasObras(null);

                        if($_POST){ //Borrado lógico de obra

                            $nombre = $obra->getNombre();

                            if (isset($_POST[$nombre])){


                                $obra->setValores($_POST[$nombre]);

                                if (!$obra->validar()){

                                    $this->dibujaVista("borrarObra", [
                                        "obra" => $obra,
                                        "categorias" => $arrayCategoriasObras],
                                        "Borrar obra " . $obra->titulo
                                    );
                                    exit;
                                

                                }
                                else{

                                    if ($obra->guardar() === true){
                                        $cod_obra = intval($obra->cod_obra);
                                        header("location: ". Sistema::app()->generaURL(["obras", "verObra"], ["id"=>$cod_obra]));
                                        exit;
                                    }
                                    else{
                                        $this->dibujaVista("borrarObra", [
                                            "obra" => $obra,
                                            "categorias" => $arrayCategoriasObras],
                                            "Borrar obra " . $obra->titulo
                                        );
                                        exit;
                                    }
                                }

                            }

                        }

                        $this->dibujaVista("borrarObra", ["obra"=> $obra,
                                                        "categorias" =>$arrayCategoriasObras
                                                    ], "Borrar obra ". $obra->titulo);

                    }
                    else{
                        Sistema::app()->paginaError(404, "La obra actual ya ha sido borrada");
                        exit;
                    }

     
                }
                else{
                    Sistema::app()->paginaError(404, "No se ha encontrado una obra con el código indicado");
                    exit;
                }


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
     * Acción para el controlador de obras
     * Se trata de un formulario con los campos a rellenar
     * de un modelo Obra, se usa para añadir obras nuevas
     * 
     * Se validan sus datos, si todo es correcto lo llevamos a ver obra
     * Si no, mostramos los errores
     *
     * @return Void -> Se imprime una vista, no muestra nada
     */
    public function accionAnadirObra(): Void{
        
        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ){
                $obra = new Obras ();

                $this->barraUbi = [
                    [
                        "texto" => "inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de obras",
                        "url" => ["obras", "IndexObras"]
                    ],
                    [
                        "texto" => "Añadir obra ",
                        "url" => ["obras", "anadirObra"]
                    ]
                ];
                $arrayCategoriasObras = CategoriasObras::dameCategoriasObras(null);
                $arrayGenerosObras = GenerosObras::devuelveGenerosObras(null, $obra->cod_categoria_obra);

                $obra->fecha_lanzamiento = $obra->fecha_lanzamiento->format("d/m/Y");

                if ($_POST){

                    $nombre = $obra->getNombre();

                    if (isset($_POST[$nombre])){

                        $obra->setValores($_POST[$nombre]);

                        if (!$obra->validar()){ //En caso de error al validar

                            if (intval($obra->cod_categoria_obra) === -1){
                                $arrayGenerosObras = [];
                            }
                            else{
                                $arrayGenerosObras = GenerosObras::devuelveGenerosObras(null, $obra->cod_categoria_obra);

                            }



                            $this->dibujaVista("anadirObra", ["obra" => $obra,
                            "categoriasObra" => $arrayCategoriasObras,
                           "generosObras" => $arrayGenerosObras], 
                            "Añadir obra");
                            exit;


                        }
                        else{ //Se comprueba si se sube imagen


                            if ($_FILES){ //Tiene una por defecto, pero nos aseguramos si se sube una nueva

                                $fotoNueva = "";

                                if (isset($_FILES["obras"]["name"]["foto"])){

                                    $fotoNueva = trim($_FILES["obras"]["name"]["foto"]);
                                    $fotoNueva = CGeneral::addSlashes($fotoNueva);

                                    if ($fotoNueva !== ""){

                                        $obra->foto = $fotoNueva;
                                        $rutaImagen = RUTA_BASE. "/imagenes/obras/".$fotoNueva;

                                        if (!move_uploaded_file($_FILES["obras"]["tmp_name"]["foto"], $rutaImagen)){ //error al subir la imagen al servidor
                                            $arrayGenerosObras = GenerosObras::devuelveGenerosObras(null, $obra->cod_categoria_obra);

                                            $this->dibujaVista("anadirObra", [
                                            "obra" => $obra,
                                            "categoriasObra" => $arrayCategoriasObras,
                                           "generosObras" => $arrayGenerosObras,
                                           "errorFoto" => "La foto no se ha podido subir al servidor"
                                                                            ], 
                                            "Añadir obra");
                                            exit;
                                        }
                                    }
                                }
                            }


                            //Se guardan los datos
                            if ($obra->guardar() === true){ //Todo correcto, se envia a ver
                                $cod_obra = intval($obra->cod_obra);

                                header("location: ". Sistema::app()->generaURL(["obras", "verObra"], ["id"=>$cod_obra]));
                                exit;

                            }
                            else{ //Error al guardar la obra
                                $arrayGenerosObras = GenerosObras::devuelveGenerosObras(null, $obra->cod_categoria_obra);

                                $this->dibujaVista("anadirObra", ["obra" => $obra,
                                "categoriasObra" => $arrayCategoriasObras,
                               "generosObras" => $arrayGenerosObras], 
                                "Añadir obra");
                            }

                        }
                    }


                }

                $this->dibujaVista("anadirObra", ["obra" => $obra,
                                                 "categoriasObra" => $arrayCategoriasObras,
                                                "generosObras" => $arrayGenerosObras], 
                                "Añadir obra");

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
     * Acción que se usa para la petición fetch en JavaScript
     * cuando se cambia de opción en el comboBox de tipos de obra
     * y así actualizar el combo box de géneros de obras en el crud de 
     * obras de modificar y añadir. Recibimos el parámetro por POST
     *
     * @return Void no devuelve nada, hace un echo del json encode para la parte del JavaScript
     */
    public function accionPeticionGenerosCategorias (){

        $respuesta = [];

        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ){


                if ($_POST){

                    $id = "";
                    if(isset($_POST["id"])){


                        $id = intval($_POST["id"]);

                        //Aqui ya devuelvo el array
                        $arrayGenerosObras = GenerosObras::devuelveGenerosObras(null, $id);

                        if ($arrayGenerosObras !== false){ //Comprobamos que nos llegue bien el array

                            $respuesta = [
                                "correcto" => true,
                                "generosObras" => $arrayGenerosObras
                            ];

                        }
                        else{ //En caso de que no, se le envia mensaje de error a javascript

                            $respuesta = [
                                "correcto" => false,
                                "generosObras" => "No se ha encontrado géneros con el parámetro indicado"
                            ];
                        }

                        echo json_encode($respuesta); //se imprime respuesat
                    }
                }
                
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
     * petición ajax javascript
     *
     * @return void
     */
    public function accionDameObjObra(){
        $respuesta = [];

        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ){

                //peticion
                if ($_POST){
                    
                    $codObra = "";

                    if (isset($_POST["codObra"])){

                        $codObra = intval($_POST["codObra"]);

                        if ($codObra !== ""){

                            $obra = new Obras ();
                            

                            if ($obra->buscarPorId($codObra) === true){

                                $arrayValoresObra = [];

                                foreach ($obra as $clave => $valor){


                                    //COD GENERO 


                                    //SE PASAN AQUI A CADENA

                                    $arrayValoresObra[$clave]  = $valor;
                                }

                 

                                $arrayValoresObra["cod_genero"] =  GenerosObras::devuelveGenerosObras($obra->cod_genero);

                                if (count($arrayValoresObra) !== 0){

                                    $respuesta = [  
                                        "correcto" => true,
                                        "respuesta" => $arrayValoresObra
                                    ];

                                }
                                else{
                                
                                    $respuesta = [
                                        "correcto" => false,
                                        "respuesta" => "No se ha encontrado obra con el parámetro indicado"
                                    ];

                                }

                            }
                            else{
                                $respuesta = [
                                    "correcto" => false,
                                    "respuesta" => "No se ha encontrado obra con el parámetro indicado"
                                ];
                            }

                        }
                        else{

                            $respuesta = [
                                "correcto" => false,
                                "respuesta" => "Se ha entregado un parámetro vacío"
                            ];
                        }

                    }

                    echo json_encode($respuesta);
                }
                
            }
            else{
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;

            }
        }
        else{
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }
    }

}















?>