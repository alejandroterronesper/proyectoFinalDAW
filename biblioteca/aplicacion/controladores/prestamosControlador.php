<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:\web\sitios\biblioteca\vendor\autoload.php';
/**
 * REVIS
 */
class prestamosControlador extends CControlador {


    /**
     * Acción index prestamos solo pueden accerder
     * los bibliotecarios
     *
     * @return Void
     */
    public function accionIndexPrestamos(): void{


        if (Sistema::app()->Acceso()->hayUsuario() === true) {

            if (
                Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ){ //Permisos de bibliotecario


                $this->barraUbi = [
                    [
                        "texto" => "Inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de prestamos",
                        "url" => ["ejemplares", "IndexPrestamos"]
                    ]
                ];


                //Array para guardar filtrado
                if (!isset($_SESSION["arrayFiltradoIndexPrestamos"])) {
                    $_SESSION["arrayFiltradoIndexPrestamos"] = [
                        "devuelto" => "",
                        "selectWhere" => "",

                    ];
                }

                $datos = [
                    "devuelto" => $_SESSION["arrayFiltradoIndexPrestamos"]["devuelto"],
                ];
                $arrayDevuelto = [
                    1 => "Devueltos",
                    2 => "No devueltos"
                ];


                $selectWhere = "";

                //POST FILTRADO
                if ($_POST){


                    if (isset($_POST["filtradoPrestamos"])){


                        $devuelto = "";
                        if (isset($_POST["devuelto"])){

                            $devuelto = intval($_POST["devuelto"]);

                            if ($devuelto !== 0){//para que no llegue vacio

                                //DOS CASOS

                                //devuelto
                                if ($devuelto === 1){
                                    $selectWhere = " fecha_devolucion != '1900-01-01' ";
                                }

                                if ($devuelto === 2){ //no devuelto
                                    $selectWhere = " fecha_devolucion = '1900-01-01' ";
                                }
                                
                            }
                        }
                        $datos["devuelto"] = $devuelto;
                    }


                    //limpiar filtrado
                    if (isset($_POST["limpiarFiltradoPrestamos"])){

                        $datos["devuelto"] = 0;
                        $selectWhere = "";
                    }


                    //actualizo sesión
                    $_SESSION["arrayFiltradoIndexPrestamos"] = [
                        "devuelto" =>  $datos["devuelto"],
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


                $prestamos = new Prestamos ();

                if (isset($_SESSION["arrayFiltradoIndexPrestamos"]["selectWhere"]) && $_SESSION["arrayFiltradoIndexPrestamos"]["selectWhere"] !== ""){

                    $selectWhere = $_SESSION["arrayFiltradoIndexPrestamos"]["selectWhere"];
                }


                //filtrado por where
                if ($selectWhere !== "") {

                    $filas = $prestamos->buscarTodos(
                        [
                            "where" => $selectWhere,
                            "limit" => $limite
                        ]
                    );
                } 
                else {
                    $filas = $prestamos->buscarTodos(
                        [
                            "limit" => $limite
                        ]
                    );
                }
                
                $arrayEjemplares = Ejemplares::devuelveEjemplares();
                $arrayUsuarios  = Usuarios::dameNickUsuarios();

                //Añadir opciones de la tabla
                foreach ($filas as $clave => $valor) { //usamos los arrays para poner los valores

                    
                    $valor["cod_usuario"] = $arrayUsuarios[intval($valor["cod_usuario"])];
                    $valor["cod_ejemplar"] = $arrayEjemplares[intval($valor["cod_ejemplar"])];
                    $valor["fecha_inicio"] = CGeneral::fechaMysqlANormal($valor["fecha_inicio"]);
                    $valor["fecha_fin"] = CGeneral::fechaMysqlANormal($valor["fecha_fin"]);
                    $valor["fecha_devolucion"] = CGeneral::fechaMysqlANormal($valor["fecha_devolucion"]);

                    if ($valor["fecha_devolucion"] === "01/01/1900"){
                        $valor["fecha_devolucion"] = "No devuelto";
                    }

                    $valor["oper"] = CHTML::link(
                        CHTML::imagen("/imagenes/24x24/ver.png", "", ["title" => "Ver prestamos"]),
                        Sistema::app()->generaURL(["prestamos", "verPrestamoC"], ["id" => $valor["cod_prestamo"]])
                    ) . " " .
                        CHTML::link(
                            CHTML::imagen("/imagenes/24x24/modificar.png", "", ["title" => "Modificar prestamos"]),
                            Sistema::app()->generaURL(["prestamos", "modificarPrestamo"], ["id" => $valor["cod_prestamo"]])
                        );


                    if (intval($valor["borrado"]) === 0) {
                        $valor["oper"] .= CHTML::link(
                            CHTML::imagen(
                                "/imagenes/24x24/borrar.png",
                                "",
                                ["title" => "Borrar producto"]
                            ),
                            Sistema::app()->generaURL(
                                ["prestamos", "borrarPrestamo"],
                                ["id" => $valor["cod_prestamo"]]
                            )
                        );
                    }

                    if (intval($valor["borrado"]) === 0) {
                        $valor["borrado"] = "NO";
                    }

                    if (intval($valor["borrado"]) === 1) {
                        $valor["borrado"] = "SI";
                    }


                    //Añadimos checkBoxes
                    if ($valor["fecha_devolucion"] === "No devuelto"){
                        //Se añadiran en los prestamos que no hayan sido devueltos aún
                        $valor["check"] = CHTML::campoCheckBox("enviarMail",
                         false, ["value" => $valor["cod_prestamo"]]); //meto el id del prestamo
                    }
    


                    $filas[$clave] = $valor;


                }


                $cabecera = [
                    [
                        "ETIQUETA" => "Título",
                        "CAMPO" => "cod_ejemplar"
                    ],
                    [
                        "ETIQUETA" => "Usuario",
                        "CAMPO" => "cod_usuario"
                    ],
                    [
                        "ETIQUETA" => "Fecha de inicio",
                        "CAMPO" => "fecha_inicio"
                    ],
                    [
                        "ETIQUETA" => "Fecha de fin",
                        "CAMPO" => "fecha_fin"
                    ],
                    [
                        "ETIQUETA" => "Fecha de devolución",
                        "CAMPO" => "fecha_devolucion"
                    ],
                    [
                        "ETIQUETA" => "Borrado",
                        "CAMPO" => "borrado"
                    ],
                    [
                        "ETIQUETA" => "Operaciones",
                        "CAMPO" => "oper"
                    ],
                    [
                        "ETIQUETA" => "Enviar aviso",
                        "CAMPO" => "check"
                    ],


                ];


                //opciones del paginador
                $opcPaginador = array(
                    "URL" => Sistema::app()->generaURL(array("prestamos", "indexPrestamos")),
                    "TOTAL_REGISTROS" => $prestamos->buscarTodosNRegistros($selectWhere !== "" ? ["where" => $selectWhere] : []),
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

                $this->dibujaVista("indexPrestamos", [
                    "cabecera" => $cabecera,
                    "filas" => $filas,
                    "paginador" => $opcPaginador,
                    "arrayDevuelto" => $arrayDevuelto,
                    "datos" => $datos
                ], "Control de Préstamos") . PHP_EOL;


            }
            else{
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;

            }

        }
        else{ //Si no hay usuario registrado
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }

    }



    /**
     * Esto para el crud
     *
     * @return void
     */
    public function accionVerPrestamoC (): Void{


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

                $prestamo = new Prestamos ();
                $arrayUsuarios = Usuarios::dameNickUsuarios();
                $arrayEjemplares = Ejemplares::devuelveEjemplares();

                if ($prestamo->buscarPorId($id) === true){


                    $this->barraUbi = [
                        [
                            "texto" => "inicio",
                            "url" => "/"
                        ],
                        [
                            "texto" => "Control de prestamos",
                            "url" => ["prestamos", "IndexPrestamos"]
                        ],
                        [
                            "texto" => "Ver Préstamo ",
                            "url" => ["prestamos", "VerPrestamoC/?id=$id"]
                        ]
                    ];


                

                    $this->dibujaVista("VerPrestamoC", ["prestamo"=>$prestamo,
                    "arrayUsuarios" => $arrayUsuarios,
                    "arrayEjemplares" => $arrayEjemplares
                                                ], "Ver préstamo ");



                }
                else{
                    Sistema::app()->paginaError(404, "No se ha encontrado una obra con el código indicado");
                    exit;
                }

            }
            else{ //No hay permisos para acceder a la página
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;

            }
        }
        else{ //Si no hay usuario, te mando al login
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }

    }



    /**
     * Acción que permite modificar un préstamo
     * se validan los datos, si todo es correcto
     * se actualiza en la tabla correspondiente
     * y se redirige a ver prestamo del id actual
     *
     * @return Void
     */
    public function accionModificarPrestamo (): Void {

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

                $prestamo = new Prestamos ();
                $arrayUsuarios = Usuarios::dameNickUsuarios();
                $arrayEjemplares = Ejemplares::devuelveEjemplares();

                if ($prestamo->buscarPorId($id) === true){

                    $prestamo->fecha_inicio = CGeneral::fechaNormalAMysql($prestamo->fecha_inicio);
                    $prestamo->fecha_fin = CGeneral::fechaNormalAMysql($prestamo->fecha_fin);

                    if ($prestamo->fecha_devolucion === "01/01/1900"){
                        $prestamo->fecha_devolucion = "";
                    }
                    else{
                        $prestamo->fecha_devolucion = CGeneral::fechaNormalAMysql($prestamo->fecha_devolucion);
                    }


                    $this->barraUbi = [
                        [
                            "texto" => "inicio",
                            "url" => "/"
                        ],
                        [
                            "texto" => "Control de prestamos",
                            "url" => ["prestamos", "IndexPrestamos"]
                        ],
                        [
                            "texto" => "Modificar Préstamo ",
                            "url" => ["prestamos", "modificarPrestamo/?id=$id"]
                        ]
                    ];


                    if ($_POST){

                        $nombre = $prestamo->getNombre();

                        if (isset($_POST[$nombre])){
                            $valoresNuevos = $_POST[$nombre];
                            $prestamo->setValores($valoresNuevos);
                          

                            //Ahora tengo que pasar las fechas a formato normal para que se pueda validar
                            if($prestamo->fecha_inicio !== ""){
                                $prestamo->fecha_inicio = CGeneral::fechaMysqlANormal($prestamo->fecha_inicio);
                            }

                            if($prestamo->fecha_fin !== ""){
                                $prestamo->fecha_fin = CGeneral::fechaMysqlANormal($prestamo->fecha_fin);
                            }

                            if($prestamo->fecha_devolucion !== ""){
                                $prestamo->fecha_devolucion = CGeneral::fechaMysqlANormal($prestamo->fecha_devolucion);
                            }
                            else{ //Para poder validarlo en el modelo
                                $prestamo->fecha_devolucion = "01/01/1900";
                            }


                            if (!$prestamo->validar()){ //Si no se validan los datos

                                //Aqui las vuelvo a pasar a formato de input date
                                if($prestamo->fecha_inicio !== ""){
                                    $prestamo->fecha_inicio = CGeneral::fechaNormalAMysql($prestamo->fecha_inicio);
                                }
    
                                if($prestamo->fecha_fin !== ""){
                                    $prestamo->fecha_fin = CGeneral::fechaNormalAMysql($prestamo->fecha_fin);
                                }
    
                                if ($prestamo->fecha_devolucion === "01/01/1900"){
                                    $prestamo->fecha_devolucion = "";
                                }
                                else{
                                    $prestamo->fecha_devolucion = CGeneral::fechaNormalAMysql($prestamo->fecha_devolucion);
                                }
            
    
                                $this->dibujaVista("modificarPrestamo", ["prestamo"=>$prestamo,
                                "arrayUsuarios" => $arrayUsuarios,
                                "arrayEjemplares" => $arrayEjemplares
                                                            ], "Modificar préstamo ");
                                exit;

                            }
                            else{ //Si se validan los datos

                                if ($prestamo->guardar() === true){ //Si se guarda

                                    //Actualizamos el ejemplar en caso de que se haya devuelto

                                    if ($prestamo->fecha_devolucion !== "01/01/1900"){
                                        //devuelto

                                        $ejemplar = new Ejemplares ();

                                        if ($ejemplar->buscarPorId(intval($prestamo->cod_ejemplar))){

                                            if (intval($ejemplar->estado_ejemplar) === 1){ //el prestamo se ha devuelto y el ejemplar sigue
                                                                                        //reservado, lo actualizamos

                                                $ejemplar->estado_ejemplar = 0;
                                                if ($ejemplar->validar()){

                                                    if ($ejemplar->guardar()){
                                                        $codPrestamo = intval($prestamo->cod_prestamo);
                                                        header("location: ". Sistema::app()->generaURL(["prestamos", "verPrestamoC"], ["id"=>$codPrestamo]));
                                                        exit; 
                                                    }
                                                    else{
                                                        Sistema::app()->paginaError(404, "No se ha podido guardar el ejemplar del préstamo indicado");
                                                        exit;

                                                    }


                                                }
                                                else{
                                                    Sistema::app()->paginaError(404, "No se ha podido validar el ejemplar del préstamo indicado");
                                                    exit;
                                                }
                                            }
                                            else{//no se toca
                                                $codPrestamo = intval($prestamo->cod_prestamo);
                                                header("location: ". Sistema::app()->generaURL(["prestamos", "verPrestamoC"], ["id"=>$codPrestamo]));
                                                exit; 
                                            }

                                        }
                                        else{
                                            Sistema::app()->paginaError(404, "El ejemplar que se iba a catalogar como préstamo no existe");
                                                exit;
                                        }

                                    }
                                    else{ //se muestra
                                        $codPrestamo = intval($prestamo->cod_prestamo);
                                        header("location: ". Sistema::app()->generaURL(["prestamos", "verPrestamoC"], ["id"=>$codPrestamo]));
                                        exit; 
                                    }


                                            
                                }
                                else{//Si hay error al guardar
                                    
                                    
                                    $this->dibujaVista("modificarPrestamo", ["prestamo"=>$prestamo,
                                    "arrayUsuarios" => $arrayUsuarios,
                                    "arrayEjemplares" => $arrayEjemplares
                                                                ], "Modificar préstamo ");
                                    exit;

                                }
                            }
                        }

                    }

                    $this->dibujaVista("modificarPrestamo", ["prestamo"=>$prestamo,
                    "arrayUsuarios" => $arrayUsuarios,
                    "arrayEjemplares" => $arrayEjemplares
                                                ], "Modificar préstamo ");

                }
                else{
                    Sistema::app()->paginaError(404, "No se ha encontrado un prestamo con el código indicado");
                    exit;
                }

            }
            else{ //No hay permisos para acceder a la página
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;

            }
        }
        else{ //Si no hay usuario, te mando al login
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }
        
    }

    /**
     * 
     *
     * @return Void
     */
    public function accionBorrarPrestamo (): Void {

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

                $prestamo = new Prestamos ();
                $arrayUsuarios = Usuarios::dameNickUsuarios();
                $arrayEjemplares = Ejemplares::devuelveEjemplares();

                if ($prestamo->buscarPorId($id) === true){


                    $this->barraUbi = [
                        [
                            "texto" => "inicio",
                            "url" => "/"
                        ],
                        [
                            "texto" => "Control de prestamos",
                            "url" => ["prestamos", "IndexPrestamos"]
                        ],
                        [
                            "texto" => "Borrar Préstamo ",
                            "url" => ["prestamos", "borrarPrestamo/?id=$id"]
                        ]
                    ];


                    if ($_POST){

                        $nombre = $prestamo->getNombre();


                        if (isset($_POST[$nombre])){
                            $valoresNuevos = $_POST[$nombre];
                            $prestamo->setValores($valoresNuevos);

                            if (!$prestamo->validar()){ //Si no se valida los datos
       
                                $this->dibujaVista("borrarPrestamo", ["prestamo"=>$prestamo,
                                "arrayUsuarios" => $arrayUsuarios,
                                "arrayEjemplares" => $arrayEjemplares
                                                            ], "Borrar préstamo ");
                                exit;
                            }
                            else{

                                if ($prestamo->guardar() === true){ //Se guarda
                                    $codPrestamo = intval($prestamo->cod_prestamo);
                                    header("location: ". Sistema::app()->generaURL(["prestamos", "verPrestamoC"], ["id"=>$codPrestamo]));
                                    exit;         


                                }
                                else{ //error al guardar
                                    $this->dibujaVista("borrarPrestamo", ["prestamo"=>$prestamo,
                                    "arrayUsuarios" => $arrayUsuarios,
                                    "arrayEjemplares" => $arrayEjemplares
                                                                ], "Borrar préstamo ");
                                    exit;
                                }
                            }

                            
                        }

                    }

                    if (intval($prestamo->borrado) === 1){
                        Sistema::app()->paginaError(404, "El préstamo ha sido borrado anteriormente");
                        exit;                      
                    }
                
                    $this->dibujaVista("borrarPrestamo", ["prestamo"=>$prestamo,
                    "arrayUsuarios" => $arrayUsuarios,
                    "arrayEjemplares" => $arrayEjemplares
                                                ], "Borrar préstamo ");
                    
    
                }
                else{
                    Sistema::app()->paginaError(404, "No se ha encontrado un prestamo con el código indicado");
                    exit;
                }

            }
            else{ //No hay permisos para acceder a la página
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;

            }
        }
        else{ //Si no hay usuario, te mando al login
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }
        
    }


    /**
     * Acción que permite crear prestamos
     * se validaran los parámetros del modelo
     * prestamo
     * se enviará notificación al usuario correspondiente
     *
     * @return Void -> no devuelve nada, imprime una vista
     */
    public function accionAnadirPrestamo (): Void{
        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(1)
                && Sistema::app()->Acceso()->puedePermiso(9)
            ){

                $this->barraUbi = [
                    [
                        "texto" => "inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de prestamos",
                        "url" => ["prestamos", "IndexPrestamos"]
                    ],
                    [
                        "texto" => "Añadir Préstamo ",
                        "url" => ["prestamos", "anadirPrestamo"]
                    ]
                ];

                //los usuarios tienen que ser los normales
                $arrayUsuarios = Usuarios::dameNickUsuarios();

                



                $ejemplar = new Ejemplares ();
                $ejemplaresDisponibles = [];
                $arrayTipos = CategoriasObras::dameCategoriasObras();

                //solo pueden reservar ejemplares aquellos con rol de usuario normal
                $arrayAclUsers = AclUsuarios::devuelveUsuariosPermisos(3);

                $arrayUsers = [];
                //Ahora filtramos los usuarios de la acl con los de la tabla user
                 foreach($arrayAclUsers as $clave => $valor){ //valor es el nick

                    foreach ($arrayUsuarios as $cod => $nick){

                        if ($valor === $nick){
                            $arrayUsers[$cod] = $nick;
                        }

                    }
                }
                
        
                foreach($ejemplar->buscarTodos(["where" => " `estado_ejemplar` = 0  "]) as $clave => $valor){
                    $ejemplaresDisponibles[intval($valor["cod_ejemplar"])]= $valor["titulo"] ." - ". $arrayTipos[intval($valor["cod_categoria_obra"])] ." - " . $valor["descripcion_formato_medio"];

                }


                $prestamo = new Prestamos ();
                $prestamo->fecha_inicio = CGeneral::fechaNormalAMysql($prestamo->fecha_inicio);
                $prestamo->fecha_fin = CGeneral::fechaNormalAMysql($prestamo->fecha_fin);
                $prestamo->fecha_devolucion = "";



                if ($_POST){


                    $nombre = $prestamo->getNombre();

                    if ($_POST[$nombre]){
                        
                        $valoresNuevos = $_POST[$nombre];
                        $prestamo->setValores($valoresNuevos);

                        //Ahora tengo que pasar las fechas a formato normal para que se pueda validar
                        if ($prestamo->fecha_inicio !== "") {
                            $prestamo->fecha_inicio = CGeneral::fechaMysqlANormal($prestamo->fecha_inicio);
                        }

                        if ($prestamo->fecha_fin !== "") {
                            $prestamo->fecha_fin = CGeneral::fechaMysqlANormal($prestamo->fecha_fin);
                        }

                        if ($prestamo->fecha_devolucion !== "") {
                            $prestamo->fecha_devolucion = CGeneral::fechaMysqlANormal($prestamo->fecha_devolucion);
                        } 
                        
                        else { //Para poder validarlo en el modelo
                            $prestamo->fecha_devolucion = "01/01/1900";
                        } 
                        
                        
                        if (!$prestamo->validar()){ //error al validar

                            //Aqui las vuelvo a pasar a formato de input date
                            if ($prestamo->fecha_inicio !== "") {
                                $prestamo->fecha_inicio = CGeneral::fechaNormalAMysql($prestamo->fecha_inicio);
                            }

                            if ($prestamo->fecha_fin !== "") {
                                $prestamo->fecha_fin = CGeneral::fechaNormalAMysql($prestamo->fecha_fin);
                            }

                            if ($prestamo->fecha_devolucion === "01/01/1900") {
                                $prestamo->fecha_devolucion = "";
                            } else {
                                $prestamo->fecha_devolucion = CGeneral::fechaNormalAMysql($prestamo->fecha_devolucion);
                            }


                            $this->dibujaVista(
                                "anadirPrestamo",
                                [
                                    "prestamo" => $prestamo,
                                    "ejemplaresDisponibles" => $ejemplaresDisponibles,
                                    "arrayUsuarios" => $arrayUsers
                                ],
                                "Añadir préstamo "
                            );
                            exit;

                        }
                        else{ //se validan datos

                            if ($prestamo->guardar() === true){ //Si se guarda


                                //Ahora modificamos el ejemplar
                                if ($ejemplar->buscarPorId(intval($prestamo->cod_ejemplar)) === true){

                                    //lo actualizamos, cambiamos su estado de 0 a 1
                                    $ejemplar->estado_ejemplar = 1; //se ha reservado
                                    if ($ejemplar->validar() === true){

                                        if ($ejemplar->guardar() === true){

                                            $usuario = new Usuarios ();

                                            if ($usuario->buscarPorId(intval($prestamo->cod_usuario)) === true){

                                                //enviar notificación
                                                $subject = "Reserva del ejemplar " . $ejemplar->titulo;
                                                $mensaje = "Hola <b>" . $usuario->nick . "</b> has reservado <b>" . $ejemplar->titulo . "
                                                    </b> el día <b>" . $prestamo->fecha_inicio . "</b> hasta el día <b>" .
                                                    $prestamo->fecha_fin . "</b>. Podrás disfrutarlo accediendo desde la pestaña de préstamos";

                                                //Ahora enviamos correo con los datos
                                                $enviarMensaje = Funciones::sendMensajeEmail($usuario->email, $usuario->nick, $subject, $mensaje);

                                                if ($enviarMensaje === true){
                                                    $codPrestamo = intval($prestamo->cod_prestamo);
                                                    header("location: " . Sistema::app()->generaURL(["prestamos", 
                                                    "verPrestamoC"], ["id" => $codPrestamo]));
                                                    exit;    
                                                }
                                                else{
                                                    Sistema::app()->paginaError(404, "Problemas al enviar notificación del préstamo al usuario") . PHP_EOL;
                                                    exit;
                                                }

                                            }
                                            else{
                                                Sistema::app()->paginaError(404, "Problemas al acceder a los datos del usuario del préstamo") . PHP_EOL;
                                                exit;
                                            }


                                        }
                                        else{
                                            Sistema::app()->paginaError(404, "No se ha podido validar los datos del ejemplar a reservar") . PHP_EOL;
                                            exit;
                                        }

                                    }
                                    else{
                                        Sistema::app()->paginaError(404, "No se ha podido validar los datos del ejemplar a reservar") . PHP_EOL;
                                        exit;
                                    }

                                }
                                else{//error no existe el ejemplar a reservar

                                    Sistema::app()->paginaError(404, "El ejemplar a reservar no existe") . PHP_EOL;
                                    exit;
                                }
                                



                                    
                            }
                            else{//Error al guardar

                                $this->dibujaVista(
                                    "anadirPrestamo",
                                    [
                                        "prestamo" => $prestamo,
                                        "ejemplaresDisponibles" => $ejemplaresDisponibles,
                                        "arrayUsuarios" => $arrayUsers
                                    ],
                                    "Añadir préstamo "
                                );
                                exit;

                            }
                        }
                        
                    }
                }


                $this->dibujaVista("anadirPrestamo", 
                                    ["prestamo" => $prestamo, 
                                    "ejemplaresDisponibles" => $ejemplaresDisponibles,
                                     "arrayUsuarios" => $arrayUsers
                                    ],
                                     "Añadir préstamo ");
                    
            }
            else{ //No hay permisos para acceder a la página
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;

            }

        }
        else{
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }
    }

    /**
     * Acción que recibe una petición fetch de js
     * recibe un json con los id de los prestamos
     * a los que se pide enviar una notificación, se
     * iteran y se van enviado correos a los usuarios para
     * decirles que ya mismo va a cumplir la devolución de su préstamo
     *
     * @return Void -> no devuelve nada, enviamos json al cliente
     */
    public function accionEnviaNotificacionUsuariosPrestamos (): Void{

        $peticionJS = file_get_contents('php://input');

        
        $arrayCodPrestamos = json_decode($peticionJS, true);


        $ejemplar = new Ejemplares ();
        $prestamo = new Prestamos(); 
        $usuario = new Usuarios (); //buscamos nombre user

        $correcto = true;
        $respuesta = "Notificación correcta";

        foreach($arrayCodPrestamos as $clave => $valor){

            if ($prestamo->buscarPorId(intval($valor))){

                //Ahora tengo el cod de ejemplar y cod de usuario
                if ( ($ejemplar->buscarPorId($prestamo->cod_ejemplar) === true)  
                    && ($usuario->buscarPorId($prestamo->cod_usuario)) === true  ){

                            $motivo = "AVISO DE CORTESÍA";
                            $correo = $usuario->email;
                            $tituloObra = $ejemplar->titulo;
                            $fechaInicio = $prestamo->fecha_inicio;
                            $fechaFin = $prestamo->fecha_fin;
                            $nombre = $usuario->nick;
                            $hoyD = New DateTime();
                            $hoyD->setTime(0,0,0);
                            $fechaFinD = DateTime::createFromFormat("d/m/Y", $fechaFin);
                            $fechaFinD->setTime(0,0,0);
                            $diasQueQuedan = $fechaFinD->diff($hoyD);
                            $diasQueQuedan = $diasQueQuedan->d;
                             $mensaje = "Estimado usuario ".$nombre. 
                                    " le enviamos este mensaje \n para avisarle de que el
                                     ejemplar: " . $tituloObra . "\n reservado  el día <b>" .$fechaInicio . 
                                     "</b> aún no ha sido devuelto, tiene <b>". $diasQueQuedan . "</b> días para devolverlo <b>(". $fechaFin .")</b>" ;

                                    
                            $enviarMensaje = Funciones::sendMensajeEmail($correo, $nombre, $motivo, $mensaje);

                            if ($enviarMensaje === false){
                                $respuesta = "No se ha podido enviar correo";
                                $correcto = false;
                                break;
                            }
                }
                    else{
                        $respuesta = "No existe ejemplar y préstamo indicado";
                        $correcto = false;
                        break;
                }
            }
            else{
                $correcto = false;
                $respuesta = "No existe préstamo indicado";
                break;
                //error
            }
        }

        $arrayJS = [
            "respuesta" => $respuesta,
            "correcto" => $correcto
        ];

        echo json_encode($arrayJS);

    }


    /**
     * Acción que permite ver mis prestamos personales y gestionarlos
     * 
     * esta acción varía en función del cliente registriado
     * 
     * se tiene en cuenta el rol (SOLO USUARIO NORMAL) y si hay usuario registrado
     *
     * @return Void no devuelve nada, imprime una vista
     */
    public function accionVerPrestamos (){

        $id = "";
        if ($_GET){

            if (isset($_GET["id"])){
                $id = intval($_GET["id"]);
            }

            if ($id === ""){//en caso de no recibir parámetro id
                Sistema::app()->paginaError(404, "Se ha accedido con un parámetro distinto al id");
                exit;
            }
        }



        //Primero se comprueba si hay usuario registrado
        if(Sistema::app()->Acceso()->hayUsuario() === true){
            
            //Si hay usuario, se comprueba que sea permiso usuario normal, 
            //es decir, permiso 3

            $permiso = Sistema::app()->Acceso()->puedePermiso(3);
            $nickActual = Sistema::app()->Acceso()->getNick();

            if (Sistema::app()->Acceso()->puedePermiso(9) && Sistema::app()->Acceso()->puedePermiso(10)){


                //y ahora se comprueba que se accede a los prestamos
                //del usuario con el que estamos logueados
                //tiene permisos, hacemos petición a la tabla prestamos
                //buscamos prestamos a partir del id del usuario

                // $prestamos = new Prestamos (); //crear prestamos

                $usuario = new Usuarios ();
                


                if ($usuario->buscarPorId($id) === true){

                    $nickUser = $usuario->nick;


                    if (trim($nickActual) === trim($nickUser)){ //si coincide creamos modelo prestamos


                        //creamos objeto de prestamos
                        $prestamos = new Prestamos ();
                        $codUsuario = $usuario->cod_usuario;

                        $this->barraUbi = [
                            [
                              "texto" => "inicio",
                              "url" => "/"
                            ],
                            [
                                "texto" => "Ver préstamos",
                                "url" => ["prestamos", "verPrestamos/?id=$codUsuario"]
                            ]
                          ];
        


                        $arrayPrestamosUsuario = []; //se guardaran los prestamos del usuario actual
                        // $arrayPrestamosUsuario =  $prestamos->buscarTodos(["WHERE" => " `cod_usuario` =  $codUsuario "]);

                        //Buscamos los prestamos por id de usuario y que la fecha devolucion sea 01/01/1900
                        foreach($prestamos->buscarTodos(["where" => " `cod_usuario` =  $codUsuario AND `fecha_devolucion` = '1900-01-01' "]) as  $clave => $valor){

                            $arrayPrestamosUsuario[intval($valor["cod_ejemplar"])] = $valor; //se guardan por cod_ejemplar los prestamos

                        }
                        

                        //Ahora que tenemos todos los prestamos 
                        //Se muestran los ejemplares 
                    
                        if (count($arrayPrestamosUsuario) > 0){ //Iteramos sobre ejemplares
                            

                            //Ahora sacamos todos los prestamos que tengan cod de estado 0, es decir alquilado
                            $ejemplares = new Ejemplares ();
                            
                            $arrayPrestamosTotales = $ejemplares->buscarTodos(["where" => " `estado_ejemplar` = 1 "]);

                            

                            foreach ($arrayPrestamosTotales as $clave => $valor){

                      

                                $codAux = intval( $valor["cod_ejemplar"]); //saco el código de los ejemplares que hay
                                                                            //y los comparo con los del usuario

                                    foreach ($arrayPrestamosUsuario as $key => $value) {
                                        
                                        if ($key === $codAux){ //Si coincide sacamos el ejemplar


                                            //Hacemos las conversiones necesarias, ej. transformar fecha, añadir los campos de
                                            // prestamos


                                            $valor["cod_obra"] = intval($valor["cod_obra"]);
                                            $valor["codigo_genero"] = intval($valor["codigo_genero"]);
                                            $valor["cod_categoria_obra"] = intval($valor["cod_categoria_obra"]);
                                            $valor["fecha_lanzamiento"] =  CGeneral::fechaMysqlANormal($valor["fecha_lanzamiento"]);
                                            $valor["cod_obra"] = intval($valor["cod_obra"]);
                                            $valor["cod_obra"] = intval($valor["cod_obra"]);
                                            $valor["obra_borrado"] = intval($valor["obra_borrado"]);
                                            $valor["cod_ejemplar"] = intval($valor["cod_ejemplar"]);
                                            $valor["cod_formato_ejemplar"] = intval($valor["cod_formato_ejemplar"]);
                                            $valor["codigo_formato_medio"] = intval($valor["codigo_formato_medio"]);
                                            $valor["fecha_registro"] = CGeneral::fechaMysqlANormal($valor["fecha_registro"]);
                                            $valor["borrado_ejemplar"] = intval($valor["borrado_ejemplar"]);
                                            $valor["estado_ejemplar"] = intval($valor["estado_ejemplar"]);
                                            $valor["cod_prestamo"] = intval($arrayPrestamosUsuario[$key]["cod_prestamo"]);
                                            $valor["cod_usuario"] = intval($arrayPrestamosUsuario[$key]["cod_usuario"]);
                                            $valor["fecha_inicio"] = CGeneral::fechaMysqlANormal($arrayPrestamosUsuario[$key]["fecha_inicio"]);
                                            $valor["fecha_fin"] = CGeneral::fechaMysqlANormal($arrayPrestamosUsuario[$key]["fecha_fin"]);

                                            if (isset($arrayPrestamosUsuario[$key]["fecha_devolucion"])){

                                                $valor["fecha_devolucion"] = CGeneral::fechaMysqlANormal($arrayPrestamosUsuario[$key]["fecha_devolucion"]);

                                            }



                                            $arrayEjemplaresPrestamosUsuario[$key] = $valor; //le pongo de posicion su clave de ejemplar

                                        }
                                    }

                            }                            

                        }

                        if (isset($arrayEjemplaresPrestamosUsuario) === true){


                            $this->dibujaVista(
                                "verprestamos",
                                [
                                    "ejemplaresUsuarioPrestamos" => $arrayEjemplaresPrestamosUsuario,
                                    "codUsuario" =>  $codUsuario
                                ],
                                "Ver préstamos de $nickUser"
                            );
                        }
                        else{

                            $this->dibujaVista(
                                "verprestamos",
                                [
                                    "codUsuario" =>  $codUsuario
                                ],
                                "Ver préstamos de $nickUser"
                            );

                        }
                  


                    }
                    else{
                        Sistema::app()->paginaError(505, "Los prestamos a buscar no coinciden con el usuario actual");

                    }

                }
                else{
                    Sistema::app()->paginaError(505, "El usuario indicado no existe");
                }


                
            }
            else{ //Si no es un usuario normal y es superadmin o bibliotecario, lanzamos pagina de error

                Sistema::app()->paginaError(505, "Debes tener permisos de usuario normal para accerder");
            }


        }
        else{ //Si no hay usuario con sesión iniciada, lo llevamos a la página de login
            
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }



    }




    /**
     * 
     *
     * @return void
     */
    public function accionVerPrestamo (){

        $codUser = "";
        $codPrestamo = "";

        if ($_GET){

            if (isset($_GET["prest"])){

                $codPrestamo = intval($_GET["prest"]);
            }


            if (isset($_GET["us"])){
                $codUser = intval($_GET["us"]);
            }
        }


        if ($codUser === "" && $codPrestamo === ""){ //No se accede con ningún parámetro

            Sistema::app()->paginaError(404, "No se ha accedido con identificador de usuario ni de ejemplar");
            exit();
        }

        if ($codUser === "" && $codPrestamo !== ""){ //Falta uno de los dos parámetros
            Sistema::app()->paginaError(404, "No se ha accedido con identificador de usuario");
            exit();
        }


        if ($codUser !== "" && $codPrestamo === ""){
            Sistema::app()->paginaError(404, "No se ha accedido con identificador de ejemplar");
            exit();
        }


        
        if ($codUser !== "" && $codPrestamo !== ""){//Se acceden con los dos parámetros


            //Se comprueba que hay usuario registrado
            if (Sistema::app()->Acceso()->hayUsuario() === true){


                //Se comprueba que el usuario actual tenga los permisos de usuario normal
                $nickActual = Sistema::app()->Acceso()->getNick();


                if (Sistema::app()->Acceso()->puedePermiso(9) && Sistema::app()->Acceso()->puedePermiso(10)){


                    //Ahora se comprueba el id del usuario actual
                    $usuario = new Usuarios ();

                    if ($usuario->buscarPorId($codUser) === true){

                        //Se comprueba que coinciden los usuarios
                        $nickUser = $usuario->nick;

                        if (trim($nickActual) === trim($nickUser)){


                            //Se verifica que el cod del ejemplar coincide coincide con el del usuario
                            $prestamos = new Prestamos ();
                            $codUsuario = $usuario->cod_usuario;


                            //Consulto en prestamos por codUsuario y codEjemplar
                            if ($prestamos->buscarPor(["where" => " `cod_usuario`  = $codUsuario AND `cod_prestamo` = $codPrestamo "]) === true){

                                //Se comprueba que la fecha de devolucion sea 1/1/1900
                                //para indicar que este prestamo no ha sido devuelto

                                $fechaDevPres = $prestamos->fecha_devolucion;
                                $fechaDefecPrestamo =  "01/01/1900";
                            

                                if ($fechaDevPres === $fechaDefecPrestamo){ //No ha sido devuelto


                                    $this->barraUbi = [
                                        [
                                          "texto" => "inicio",
                                          "url" => "/"
                                        ],
                                        [
                                            "texto" => "Ver préstamos",
                                            "url" => ["prestamos", "verPrestamos/?id=$codUsuario"]
                                        ],
                                        [
                                            "texto" => "Ver préstamo",
                                            "url" => ["prestamos", "verPrestamo/?prest=$codPrestamo&us=$codUsuario"]
                                        ]
                                      ];
                        
                                    
                                    //Le decimos cuantos días le quedan al usuario
                                    //Sacamos la hora tal que asi dd/mm/aaaa 00:00 
                                    //ponemos las 0 horas para evitar problemas a la hora de restar
                                    $fechaDevolucion = $prestamos->fecha_fin;
                                    $fechaDevolucion = DateTime::createFromFormat("d/m/Y",  $fechaDevolucion);
                                    $fechaDevolucion->setTime(0,0,0);
    
    
                                    $fechaActual = new DateTime(); 
                                    $fechaActual->setTime(0,0,0);
    
                                    //Sacamos los días que faltan restando la fecha dev con la actual
                                    $diasQueQuedan = $fechaDevolucion->diff($fechaActual);
                                    $pasadoFecha = $diasQueQuedan->invert; //1-> no se pasa, 0->Se pasa
                                    $pasadoFecha = boolval($pasadoFecha);
                                    $diasQueQuedan = $diasQueQuedan->days;
    
                                    //sacamos el ejemplar
                                    $ejemplar = new Ejemplares ();
    
                                    $ejemplar->buscarPorId($prestamos->cod_ejemplar);
    
                                    $devolucion = 0;
    
                                    //validacion del formulario para devolver prestamo
                                    if ($_POST){
    
    
                                        if (isset($_POST["devolverPrestamo"]) === true){
    
                                            
                                            if (isset($_POST["devolver"]) === true){
    
    
                                                $devolucion = intval($_POST["devolver"]);
    
                                            }
    
    
                                            if ($devolucion === 1){//Actualizamos prestamo
    
                                                //AQUI ME QUEDO
                                                $this->devolverPrestamo($prestamos, $codUsuario);
                                            }
                                        }
                                    }
    
                                    $this->dibujaVista("verprestamo", ["diasQueQuedan" => $diasQueQuedan,
                                                                        "fechaPasada" => $pasadoFecha, 
                                                                        "ejemplar" => $ejemplar, 
                                                                        "devolucion" => $devolucion,
                                                                        "codUser" => $codUser ], 
                                                        "Ver préstamo disponible");
    

                                }
                                else{ //Error: Ha sido devuelto


                                    Sistema::app()->paginaError(505, "El préstamo seleccionado ya ha sido devuelto");

                                }
                               
                            }
                            else{ //Si no se encuentra, es que no se ha introducido bien el parámetro del ejemplar
                                Sistema::app()->paginaError(505, "El usuario actual no tiene ningún préstamo con el parámetro indicado");

                            }


                        }
                        else{ //Se ha accedido con un id que no es el nuestro en los parámetros del GET
                            Sistema::app()->paginaError(505, "Se está accediendo con un identificador distinto al del usuario actual");

                        }

                    }
                    else{
                        Sistema::app()->paginaError(505, "No existe el usuario indicado");

                    }

                }
                else{ //Se accede como superadmin o usuario bibliotecario

                    Sistema::app()->paginaError(505, "Debes tener permisos de usuario normal para accerder");
                }
            }
            else{//Si no hay usuario con sesión iniciada, lo llevamos a la página de login
                Sistema::app()->irAPagina(["login", "InicioSesion"]);

            }
        }
    }



    /**
     * 
     *
     * @return void
     */
    public function accionHistorialPrestamos (){

   
        //Comprobamos si hay usuario registrado
        if (Sistema::app()->Acceso()->hayUsuario() === true){

            //Cogemos nick actual
            $nickActual = Sistema::app()->Acceso()->getNick();

            //Comprobamos los permisos de acceso 
            if (Sistema::app()->Acceso()->puedePermiso(9) && Sistema::app()->Acceso()->puedePermiso(10)){


                //Cogemos usuario y comprobamos si coincide
                $usuario = new Usuarios ();

                if ($usuario->buscarPor(["where" => " `nick` = '$nickActual'   "])){

                    $id = intval($usuario->cod_usuario);
                                        
                        //Barra de ubicación
                        $this->barraUbi = [
                            [
                              "texto" => "inicio",
                              "url" => "/"
                            ],
                            [
                                "texto" => "Ver préstamos",
                                "url" => ["prestamos", "verPrestamos/?id=$id"]
                            ],
                            [
                                "texto" => "Historial de préstamos",
                                "url" => ["prestamos", "HistorialPrestamos"]
                            ]
                        ];


                        //codigo de usuario
                        $codUsuario = $usuario->cod_usuario;


                        //Array sesión de filtrado
                        if (!isset($_SESSION["arrayVerHistorialPrestamos"])) {
                            $_SESSION["arrayVerHistorialPrestamos"] = [
                                "devuelto" => "",
                                "selectWhere" => "",

                            ];
                        }


                        $datos = [
                            "devuelto" => $_SESSION["arrayVerHistorialPrestamos"]["devuelto"],
                        ];

                        $arrayDevuelto = [
                            1 => "Devueltos",
                            2 => "No devueltos"
                        ];

                        $selectWhere = "";


                        //Post de filtrado
                        if ($_POST){

                            if (isset($_POST["filtradoPrestamosHistorial"])){


                                $devuelto ="";
                                if (isset($_POST["devuelto"])){

                                    $devuelto = intval($_POST["devuelto"]);


                                    if ($devuelto !== 0){


                                        //Dos casos

                                        //devuelto
                                        if ($devuelto === 1){
                                            $selectWhere = " fecha_devolucion != '1900-01-01' ";
                                        }

                                        if ($devuelto === 2){//no devuelto
                                            $selectWhere = " fecha_devolucion = '1900-01-01' ";
                                        }
                                    }
                                }
                                $datos["devuelto"] = $devuelto;

                            }


                            //limpiar filtrado
                            if (isset($_POST["limpiarFiltradoHistorialPrestamos"])){

                                $datos["devuelto"] = 0;
                                $selectWhere = "";
                            }


                            //Actualizamos la sesión
                            $_SESSION["arrayVerHistorialPrestamos"] = [
                                "devuelto" => $datos["devuelto"],
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


                        //Objetos de prestamos
                        $prestamos = new Prestamos ();


                        if (isset($_SESSION["arrayVerHistorialPrestamos"]["selectWhere"]) && $_SESSION["arrayVerHistorialPrestamos"]["selectWhere"] !== ""){

                            $selectWhere = $_SESSION["arrayVerHistorialPrestamos"]["selectWhere"];
                        }

                        //filtrado por where
                        if ($selectWhere !== "") { //Aquí especifico el código de usuario
                            //viene confiltrado
                            $selectWhere .= " AND  `cod_usuario` = $codUsuario";

                            $filas = $prestamos->buscarTodos(
                                [
                                    "where" => $selectWhere,
                                    "limit" => $limite
                                ]
                            );
                        } 
                        else {
                            //sin filtrado

                            $selectWhere = " `cod_usuario` = $codUsuario";

                            $filas = $prestamos->buscarTodos(
                                [
                                    "where" => $selectWhere,
                                    "limit" => $limite
                                ]
                            );
                        }



                    
                        //Comprobamos si hay préstamos del usuario
                        if (count($filas) !== 0){

                            $ejemplarArray = Ejemplares::devuelveEjemplares(); //pos cod_ejemplar
                            $ejemplar = new Ejemplares ();


                            //Iteramos
                            foreach ($filas as $clave => $valor){

                                if ($ejemplar->buscarPorId(intval($valor["cod_ejemplar"]))){
                                    $valor["cod_obra"] = $ejemplar->cod_obra;

                                }
                                else{
                                    $valor["cod_obra"] = "";

                                }



                                $valor["titulo"] = $ejemplarArray[intval($valor["cod_ejemplar"])];
                                $valor["fecha_inicio"] = CGeneral::fechaMysqlANormal($valor["fecha_inicio"]);
                                $valor["fecha_fin"] = CGeneral::fechaMysqlANormal($valor["fecha_fin"]);
                               
                                if ($valor["fecha_devolucion"] === "1900-01-01"){
                                    
                                    $valor["fecha_devolucion"] = "NO DEVUELTO";

                                }
                                else{

                                    $valor["fecha_devolucion"] = CGeneral::fechaMysqlANormal($valor["fecha_devolucion"]);

                                }


                                //Añadimos campo con enlace
                                $valor["oper"] = CHTML::link(CHTML::imagen("/imagenes/24x24/ver.png", "",
                                 ["title" => "Ver obra"]),
                                  Sistema::app()->generaURL([
                                    "inicial","verObra"], ["id" => $valor["cod_obra"]])) ;
                                


                                $filas[$clave] = $valor;

                            }



                            //Cabecera
                            $cabecera = [
                                ["ETIQUETA" => "Título",
                                "CAMPO" => "titulo"],

                            
                                ["ETIQUETA" => "Fecha de inicio",
                                "CAMPO" => "fecha_inicio"],

                                ["ETIQUETA" => "Fecha de fin",
                                "CAMPO" => "fecha_fin"],


                                ["ETIQUETA" => "Fecha de devolución",
                                "CAMPO" => "fecha_devolucion"],

                                ["ETIQUETA" => "Ver obra",
                                "CAMPO" => "oper"]

                            ];


                        }

                        if (isset($cabecera) === true){ //Hay préstamos

                            //opciones del paginador
                            $opcPaginador = array(
                                "URL" => Sistema::app()->generaURL(array("prestamos", "historialPrestamos")),
                                "TOTAL_REGISTROS" => $prestamos->buscarTodosNRegistros($selectWhere !== "" ? ["where" => $selectWhere] : []),
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


                            $this->dibujaVista("historialPrestamos", 
                                            [
                                                "cabecera" => $cabecera,
                                                "filas" => $filas,
                                                "paginador" => $opcPaginador,
                                                "arrayDevuelto" => $arrayDevuelto,
                                                "datos" => $datos
                                            ],
                                            "Historial de préstamos de  $nickActual");

                        }
                        else{ //no hay prestamos
                           

                            $this->dibujaVista("historialPrestamos", [],
                                            "Historial de préstamos de  $nickActual");
                        }

                }
                else{
                    Sistema::app()->paginaError(505, "El usuario indicado no existe");

                }

            }
            else{//Si no es un usuario normal y es superadmin o bibliotecario, lanzamos pagina de error
                Sistema::app()->paginaError(505, "Debes tener permisos de usuario normal para accerder");

            }


        }
        else{ //Si no estas logueado, te mando a la página del loguin

            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }
    }



    /**
     * Acción al que accedemos después de elegir uno de los préstamos
     * disponibles que hay de una obra seleccionada, aqui se podrá ver
     * el ejemplar seleccionado, el correo donde se envían los datos al usuario
     * la fecha de devolución
     * 
     * Se validará si el ejemplar seleccionado para que sea préstamo
     * no esté borrado ni esté reservado
     * 
     * Cuando se reserve el estado del ejemplar cambiará a reservado y no se podrá
     * seleccionar hasta que esté libre
     * 
     * 
     *
     * @return void
     */
    public function accionRealizarPrestamo(){

        //Comprobamos que llega parámetro
        $codEj = "";

        if ($_GET){


            if (isset($_GET["codEj"])){

                $codEj = intval($_GET["codEj"]);

            }
        }

        if ($codEj === ""){
            Sistema::app()->paginaError(404, "Se ha accedido con un parámetro distinto al código de ejemplar");
            exit;
        }


        //Comprobamos si hay usuario logueado
        if (Sistema::app()->Acceso()->hayUsuario() === true){


            $nickActual = Sistema::app()->Acceso()->getNick();
            if (Sistema::app()->Acceso()->puedePermiso(9) && Sistema::app()->Acceso()->puedePermiso(10)){

                //Si tiene permisos ahora se comprueba que se ha introducido un cod de ejemplar existente
                //Se compurbea que el ejemplar no esté borrado
                //y que no esté reservado
                $ejemplar = new Ejemplares ();



                if ($ejemplar->buscarPor(["where" => " `cod_ejemplar` = $codEj
                                                AND `borrado_ejemplar` = 0
                                                AND `estado_ejemplar` = 0 "]) === true)
                    {

                    //Ahora se comprueba que no está reservado el ejemplar
                    if (intval($ejemplar->estado_ejemplar) === 0){//Disponible

                        //Creamos objeto préstamo
                        $prestamoEjemplar = new Prestamos (); //Se crean las fechas por defecto

                        $idObra = intval($ejemplar->cod_obra);

                        //Barra de ubicación
                        $this->barraUbi = [
                            [
                                "texto" => "Inicio",
                                "url" => "/"
                            ],
                            [
                                "texto" => "Ver obra",
                                "url" => ["inicial", "verObra/?id=$idObra"]
                            ],
                            [
                                "texto" => "Reservar ejemplar",
                                "url" => ["prestamos", "realizarPrestamo/codEj=$codEj"]
                            ]
                        ];

                        
                        $usuario = new Usuarios ();


                        $usuario->buscarPor(["where" =>  " `nick` = '$nickActual' "]);

                        $prestamoEjemplar->cod_usuario = intval($usuario->cod_usuario);
                        $prestamoEjemplar->cod_ejemplar = $codEj;
                        $email = $usuario->email;

                        
                        $respuesta = 0;
                        if ($_POST){ //Cuando se realice la reserva

                            //Se valida
                            if (isset($_POST["realizarReserva"])){

                                if (isset($_POST["elegirReserva"])){

                                    $respuesta = intval($_POST["elegirReserva"]);

                                }

                            }

                            //Se le manda a reservar a ver prestamo
                            if ($respuesta === 1){ //Solo se comprueba que la respuesta sea 1 que es cuando quiero reservarlo,
                                                    //si  marco no, vuelvo al principio

                                //Se comprueba si el usuario actual tiene 3 prestamos sin devolver,
                                //si supera ese número no le dejamos
                                $codUser = $usuario->cod_usuario;
                                $compruebaPrestamo = new Prestamos ();

                                //Se comprueba que no tenga prestamos atrasados
                              

                                $prestamosAtrasados = $compruebaPrestamo->buscarTodosNRegistros(["where" => 
                                " `cod_usuario` =  $codUser AND `fecha_fin` <= CURRENT_DATE AND `fecha_devolucion` = '1900-01-01'"]);
                                $totalPrestamosSinDevolver = $compruebaPrestamo->buscarTodosNRegistros(["where" => " `cod_usuario` = $codUser AND `fecha_devolucion` = '1900-01-01'"]);
                              

                                if ($totalPrestamosSinDevolver === 3 || $prestamosAtrasados !== 0) { //Si tiene 3 sin devolver no le dejamos reservar mas

                                    if ($totalPrestamosSinDevolver === 3 && $prestamosAtrasados !== 0){ //ambos casos
                                        //AQUI ME QUEDO
                                        //Vista
                                        $this->dibujaVista(
                                            "realizarPrestamo",
                                            [
                                                "respuesta" => $respuesta,
                                                "email" => $email,
                                                "ejemplar" => $ejemplar,
                                                "prestamo" => $prestamoEjemplar,
                                                "retraso" => true,
                                                "error" => true
                                            ],
                                            "Préstamo de: " . $ejemplar->titulo
                                        );
                                        exit();
                                    }

                                    if ($totalPrestamosSinDevolver !== 3 && $prestamosAtrasados !==0){//solo prestamos atrasados

                                        //AQUI ME QUEDO
                                        //Vista
                                        $this->dibujaVista(
                                            "realizarPrestamo",
                                            [
                                                "respuesta" => $respuesta,
                                                "email" => $email,
                                                "ejemplar" => $ejemplar,
                                                "prestamo" => $prestamoEjemplar,
                                                "retraso" => true
                                            ],
                                            "Préstamo de: " . $ejemplar->titulo
                                        );
                                        exit();
                                    }

                                    if ($totalPrestamosSinDevolver === 3 && $prestamosAtrasados === 0){//tiene 3 prestamos ya

                                        //AQUI ME QUEDO
                                        //Vista
                                        $this->dibujaVista(
                                            "realizarPrestamo",
                                            [
                                                "respuesta" => $respuesta,
                                                "email" => $email,
                                                "ejemplar" => $ejemplar,
                                                "prestamo" => $prestamoEjemplar,
                                                "error" => true
                                            ],
                                            "Préstamo de: " . $ejemplar->titulo
                                        );
                                        exit();
                                    }

                                    
                                
                                
                                
                                } 
                                else {//No tiene 3 prestamos sin devolver


                                    //MIRAMOS SI ES FISICO
                                    if (intval($ejemplar->cod_formato_ejemplar) === 1){


                                        //avisamos al usuario que debe ponerse en contacto con la biblioteca
                                        //acudiendo alli de manera presencial en caso de ser ejemplar FISICO
                                        $this->dibujaVista(
                                            "realizarPrestamo",
                                            [
                                                "respuesta" => $respuesta,
                                                "email" => $email,
                                                "ejemplar" => $ejemplar,
                                                "prestamo" => $prestamoEjemplar,
                                                "fisico" => true
                                            ],
                                            "Préstamo de: " . $ejemplar->titulo
                                        );
                                        exit();

                                    }

                                    if (intval($ejemplar->cod_formato_ejemplar) === 2){//FISICO
                                                                            
                                        //CAMBIO A 1 PORQUE SE RESERVA
                                        $ejemplar->estado_ejemplar = 1;
                                        if ($ejemplar->validar() === true) { //se valida

                                            if ($ejemplar->guardar() === true) { //se guarda el estado del ejemplar en bbdd
                                                //entonces esta a 1

                                                //Ahora se valida los datos del prestamo
                                                if ($prestamoEjemplar->validar() === true) {

                                                    if ($prestamoEjemplar->guardar() === true) { //se ha guardado el prestamo

                                                        $subject = "Reserva del ejemplar " . $ejemplar->titulo;
                                                        $mensaje = "Hola " . $nickActual . " has reservado " . $ejemplar->titulo . "
                                                            el día <b>" . $prestamoEjemplar->fecha_inicio . "</b> hasta el día <b>" .
                                                            $prestamoEjemplar->fecha_fin . "</b>. Podrás disfrutarlo accediendo desde la pestaña de préstamos";

                                                        //Ahora enviamos correo con los datos
                                                        $enviarMensaje = Funciones::sendMensajeEmail($email, $nickActual, $subject, $mensaje);

                                                        if ($enviarMensaje === true) { //Se ha enviado bien el correo


                                                            $codpres = $prestamoEjemplar->cod_prestamo;
                                                            $codUser = $usuario->cod_usuario;

                                                            //Redirigimos a la página de ver préstamo

                                                            header("location:http://www.biblioteca.es/prestamos/verPrestamo/?prest=$codpres&us=$codUser");
                                                            exit();
                                                        } else { //Error al enviar correo
                                                            Sistema::app()->paginaError(505, "Problema al enviar correo de confirmación");
                                                        }
                                                    } else {
                                                        Sistema::app()->paginaError(505, "Problema con el al acceso a la base de datos");
                                                    }
                                                } else {
                                                    Sistema::app()->paginaError(505, "Problema con la validación de datos");
                                                }
                                            } else {
                                                Sistema::app()->paginaError(505, "Problema con el al acceso a la base de datos");
                                            }
                                        } else {
                                            Sistema::app()->paginaError(505, "Problema con la validación de datos");
                                        }
                                    }
                                    


                                }
                            }
                        }


                        //Vista
                        $this->dibujaVista("realizarPrestamo",["respuesta" => $respuesta,
                                                                "email" => $email, 
                                                               "ejemplar" => $ejemplar,
                                                               "prestamo" => $prestamoEjemplar],
                                         "Préstamo de: ". $ejemplar->titulo);

                    }
                    else{//No disponible, reservado
                        Sistema::app()->paginaError(505, "El ejemplar seleccionado está reservado");
                    }


                }
                else{
                    Sistema::app()->paginaError(505, "El ejemplar no está disponible en este momento");
                }

            }
            else{ //Página de error, no tiene los permisos de usuario normal

                Sistema::app()->paginaError(505, "No dispones de permisos de usuario para reservar");

            }

        }
        else{
            Sistema::app()->irAPagina(["login", "InicioSesion"]);
            exit();
        }

    }



    
    /**
     * Método privado que se encarga de realizar la operación
     * lógica de devolver un ejemplar, para ello actualiza la fecha
     * de devolución del prestamo a la actual 
     * y cambia el estado del ejemplar prestado de reservado a libre
     *
     * @param Prestamos $prestamos -> objeto prestamo actual del usuario actual, se modifica su fecha
     * @param Int $codUsuario -> codigo de usuario, lo usamos para redireccionar a la página de préstamos
     * @return Void no devuelve nada se encarga de imprimir vistas
     */
    private function devolverPrestamo(Prestamos $prestamos, Int $codUsuario):void{ // se le pasa como parámetro el prestamo a validar
        
        //Primer actualizamos el prestamo,
        //poniendole una fecha de devolucion
        $fechaDevolucion = new DateTime();

        $fechaDevolucion = $fechaDevolucion->format("d/m/Y");

        $prestamos->fecha_devolucion =  $fechaDevolucion;

        if ($prestamos->validar() === true) {


            if ($prestamos->guardar() === true) { //Guardo valor

                //Una vez validado el prestamo, actualizamos el ejemplar y cambiamos su estado de prestamo
                //el campo estado pasa de 1 (reservado) a 0 (libre)

                $codEjemplar = $prestamos->cod_ejemplar;

                $ejemplar = new Ejemplares();


                if ($ejemplar->buscarPorId($codEjemplar) === true) {

                    $ejemplar->estado_ejemplar = 0; //Lo ponemos a libre

                    if ($ejemplar->validar() === true) { 

                        if ($ejemplar->guardar() === true) {

                            //Enviamos notificación
                            $usuario = new Usuarios();
                            $usuario->buscarPorId($codUsuario);
                            $correo = $usuario->email;
                            $nick = $usuario->nick;
                            $titulo = $ejemplar->titulo;
                            $subject = "Devolución de $titulo";
                            $mensaje = " $nick le informamos que el préstamo <b>$titulo</b> ha sido devuelto el día <b>$fechaDevolucion</b>";

                            $mensajeCorreo = Funciones::sendMensajeEmail($correo, $nick, $subject, $mensaje);

                            if ($mensajeCorreo === true){
                                //Lo mandamos a su página de préstamos
                                Sistema::app()->irAPagina(["prestamos","VerPrestamos/?id=$codUsuario"]);
                                exit();
                            }
                            else{
                                Sistema::app()->paginaError(505, "Problema al enviar notifiación por correo");
                                exit();
                            }


                            
                        } else { //error

                            Sistema::app()->paginaError(505, "Problema al actualizar el ejemplar del préstamo");
                        }
                    } else { //error

                        Sistema::app()->paginaError(505, "Problema al validar el ejemplar del préstamo");
                    }
                } else { //error
                    Sistema::app()->paginaError(505, "El código de ejemplar no coincide con el préstamo");
                }
            } else { //Página de error

                Sistema::app()->paginaError(505, "Problema con la actualización del préstamo");
            }
        } else { //Página de error

            Sistema::app()->paginaError(505, "Problema con la validación del préstamo");
        }
    }
}


/**
 * Función que permite enviar un mensaje
 * a partir de un correo dado
 *
 * @param String $correo
 * @param String $nick
 * @param String $subject
 * @param String $mensaje
 * @return Void
 */
function sendMensajeEmail (string $correo, string $nick, string $subject, string $mensaje){
    
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->CharSet = 'UTF-8';
    $mail->Username = 'libreriagrimorios@gmail.com';
    $mail->Password = 'pbzv gwpf qzpd wfjf';
    $mail->setFrom('libreriagrimorios@gmail.com', 'Librería Grimorios');
    $mail->addAddress($correo, $nick);
    $mail->Subject = $subject;
    $mail->Body = <<<EOL
                        <!DOCTYPE html>
                        <html lang='es'>
                            <head>
                                <meta charset='UTF-8'>
                                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                <title>Mensaje de Correo Electrónico</title>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        background-color: #f4f4f4;
                                        margin: 0;
                                        padding: 0;
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        height: 100vh;
                                    }
                                    .container {
                                        background-color: #fff;
                                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                        border-radius: 8px;
                                        overflow: hidden;
                                        width: 100%;
                                        max-width: 600px;
                                    }
                                    .header {
                                        background-color: #4CAF50;
                                        color: #fff;
                                        padding: 20px;
                                        text-align: center;
                                    }
                                    .header img {
                                        width: 50px;
                                        height: 50px;
                                    }
                                    .header h1 {
                                        margin: 10px 0 0;
                                        font-size: 24px;
                                    }
                                    .content {
                                        padding: 20px;
                                    }
                                    .content p {
                                        margin: 0 0 10px;
                                    }
                                    .content p.email-info {
                                        font-size: 14px;
                                        color: #666;
                                    }
                                </style>
                            </head>
                              <body>
                                <div class='container'>
                                    <div class='header'>
                                        <img src='https://i.imgur.com/LYWs3Pe_d.webp?maxwidth=760&fidelity=grand' alt='Logo'>
                                        <h1>{$subject}</h1>
                                    </div>
                                    <div class='content'>
                                        <p class='email-info'>De: {$mail->Username}</p>
                                        <p class='email-info'>Para: {$correo}</p>
                                        <p>{$mensaje}</p>
                                    </div> 
                                </div>
                            </body>
                        </html>
                    EOL;

    $mail->IsHTML(true);


    return $mail->send();
}






?>