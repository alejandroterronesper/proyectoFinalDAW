<?php


/**
 * 
 */
class usuariosControlador extends CControlador {





    /**
     * Index de usuarios mostramos tabla de usuarios
     * y comprobamos permisos para acceder a la página 
     * 
     *
     * @return Void
     */
    public function accionIndexUsuarios (): Void{

        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(2) === true){

                $this->barraUbi = [
                    [
                        "texto" => "inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de usuarios",
                        "url" => ["usuarios", "IndexUsuarios"]
                    ]
                ];


                if (!isset($_SESSION["arrayIndexUsuarios"])){

                    $_SESSION["arrayIndexUsuarios"] = [
                        "nick" => "",
                        "provincia" => "",
                        "selectWhere" => ""
                    ];
                }

                $datos = [
                    "nick" => $_SESSION["arrayIndexUsuarios"]["nick"],
                    "provincia" => $_SESSION["arrayIndexUsuarios"]["provincia"]
                ];



                $selectWhere = "";


                if ($_POST){

                    if (isset($_POST["filtradoIndexUsuarios"])){

                        $nick = "";
                        if ($_POST["nick"]){
                            $nick = trim($_POST["nick"]);

                            if ($nick !== ""){

                                $selectWhere = " `nick` LIKE '%$nick%'";

                            }
                        }
                        $datos["nick"] = $nick;


                        $provincia = "";

                        if($_POST["provincia"]){

                            $provincia = trim($_POST["provincia"]);

                            if ($provincia !== ""){

                                if ($selectWhere !== ""){

                                    $selectWhere .= " AND provincia LIKE '%$provincia%'";

                                }
                                else{
                                    $selectWhere .= " provincia LIKE '%$provincia%'";
                                }
                            }


                        }
                        $datos["provincia"] = $provincia;


                    }


                    if (isset($_POST["limpiarFiltradoIndexUsuarios"])){

                        $datos["nick"] = "";
                        $datos["provincia"] = "";
                        $selectWhere = "";

                    }

                    $_SESSION["arrayIndexUsuarios"] = [
                        "nick" => $datos["nick"],
                        "provincia" => $datos["provincia"],
                        "selectWhere" => $selectWhere
                    ];
                }


                $arrayProvincias = Usuarios::provinciasAndalucia();


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

                $usuarios = new Usuarios ();
                if (isset($_SESSION["arrayIndexUsuarios"]["selectWhere"]) &&
                        ($_SESSION["arrayIndexUsuarios"]["selectWhere"] !== "")){
                            
                            $selectWhere = $_SESSION["arrayIndexUsuarios"]["selectWhere"];
                            
                        }


                if ($selectWhere !== "") {

                    $filas = $usuarios->buscarTodos(
                        [
                            "where" => $selectWhere,
                            "limit" => $limite
                        ]
                    );
                } 
                else {
                    $filas = $usuarios->buscarTodos(
                        [
                            "limit" => $limite
                        ]
                    );
                }

                //Necesitamos datos 
                //de la acl, por lo que instanciamos un array
                //con todos los datos y los añadimos a las filas
                $aclUsers = new AclUsuarios ();
                $arrayAclUsers = $aclUsers->buscarTodos();
                $rolesAclUsers = AclUsuarios::devuelveAclRoles();
                
                
                foreach ($filas as $clave => $valor){


                    $valor["oper"] = CHTML::link(
                        CHTML::imagen("/imagenes/24x24/ver.png", "", ["title" => "Ver usuario"]),
                        Sistema::app()->generaURL(["usuarios", "verUsuario"], ["id" => $valor["cod_usuario"]])
                    ) . " " .
                        CHTML::link(
                            CHTML::imagen("/imagenes/24x24/modificar.png", "", ["title" => "Modificar usuario"]),
                            Sistema::app()->generaURL(["usuarios", "modificarUsuario"], ["id" => $valor["cod_usuario"]])
                        );


                    if (intval($valor["borrado"]) === 0) {
                        $valor["oper"] .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png", "",
                                        ["title" => "Borrar usuario"]),
                                        Sistema::app()->generaURL(["usuarios", "borrarUsuario"],
                                        ["id" => $valor["cod_usuario"]]));
                    }


                    if (intval($valor["borrado"]) === 0){
                        $valor["borrado"] = "NO";
                    }

                     
                    if (intval($valor["borrado"]) === 1){
                        $valor["borrado"] = "SI";
                    }


                    $valor["fecha_nacimiento"] = CGeneral::fechaMysqlANormal($valor["fecha_nacimiento"]);
                    

                    //para los estados
                    if (intval($valor["estado"]) === 1){
                        $valor["estado"] = "PENDIENTE";
                    }

                    if (intval($valor["estado"]) === 2){
                        $valor["estado"] = "APROBADO";
                    }

                    if (intval($valor["estado"]) === 3){
                        $valor["estado"] = "CANCELADO";
                    }

                    $valor["codigo"] = intval($valor["codigo"]);

                    foreach ($arrayAclUsers as $key => $value){

                        if ($value["nick"] === $valor["nick"]){

                            $valor["nombre"] = $value["nombre"];
                            $valor["cod_acl_role"] =  $rolesAclUsers[intval($value["cod_acl_role"])];

                        }
                    }




                    $filas[$clave] = $valor;
                }

                $cabecera = [
                    [
                        "ETIQUETA" => "Nick",
                        "CAMPO" => "nick"
                    ],
                    [
                        "ETIQUETA" => "Nombre",
                        "CAMPO" => "nombre"
                    ],
                    [
                        "ETIQUETA" => "DNI",
                        "CAMPO" => "nif"
                    ],
                    [
                        "ETIQUETA" => "Dirección",
                        "CAMPO" => "direccion"
                    ],
                    [
                        "ETIQUETA" => "Provincia",
                        "CAMPO" => "provincia"
                    ],
                    [
                        "ETIQUETA" => "Población",
                        "CAMPO" => "poblacion"
                    ],
                    [
                        "ETIQUETA" => "Código Postal",
                        "CAMPO" => "cp"
                    ],
                    [
                        "ETIQUETA" => "Email",
                        "CAMPO" => "email"
                    ],
                    [
                        "ETIQUETA" => "Rol",
                        "CAMPO" => "cod_acl_role"
                    ],
                    [
                        "ETIQUETA" => "Fecha de nacimiento",
                        "CAMPO" => "fecha_nacimiento"
                    ],
                    [
                        "ETIQUETA" => "Estado",
                        "CAMPO" => "estado"
                    ],
                    [
                        "ETIQUETA" => "Código",
                        "CAMPO" => "codigo"
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
                    "URL" => Sistema::app()->generaURL(array("usuarios", "indexUsuarios")),
                    "TOTAL_REGISTROS" => $usuarios->buscarTodosNRegistros($selectWhere !== "" ? ["where" => $selectWhere] : []),
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
                $this->dibujaVista("indexUsuarios", [
                    "cabecera" => $cabecera,
                    "filas" => $filas,
                    "paginador" => $opcPaginador,
                    "datos" => $datos,
                    "arrayProvincias" => $arrayProvincias
                ], "Control de usuarios") . PHP_EOL;

            }
            else{//Tiene que acceder como superadmin

                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;

            }

        }
        else{//Si no hay usuario registrado, lo llevamos al login
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }

    }






    /**
     * 
     *
     * @return Void
     */
    public function accionVerUsuario (): Void{


        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(2) === true){

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

                //Se comprueba que existe el usuario

                $usuario =  new Usuarios ();


                if ($usuario->buscarPorId($id) === true){

                    $this->barraUbi = [
                        [
                            "texto" => "inicio",
                            "url" => "/"
                        ],
                        [
                            "texto" => "Control de usuario",
                            "url" => ["usuarios", "indexUsuarios"]
                        ],
                        [
                            "texto" => "Ver usuario ",
                            "url" => ["usuarios", "verUsuario/?id=$id"]
                        ]
                    ];

                    $arrayEstados = Usuarios::dameEstatos();
                    $usuario->estado = $arrayEstados[intval($usuario->estado)];
                    $arrayRole = AclUsuarios::devuelveAclRoles();
                    $aclUser = new AclUsuarios ();
                    $nick = $usuario->nick;

                    //Tiene que buscarlo porque ya hemos comprobado que existe
                    //en la tabla de usuarios y automaticamente va a estar en acl
                    if ($aclUser->buscarPor(["where" => " `nick` = '$nick' "]) === true){

                        $aclUser->cod_acl_role = $arrayRole[$aclUser->cod_acl_role];                        
                        
                        $this->dibujaVista("verUsuario", [
                            "aclUser" => $aclUser,
                            "usuario"=> $usuario,
                        ], "Ver usuario ". $nick);

                    }
                    else{
                        Sistema::app()->paginaError(404, "Problema en el acceso de la base de datos");
                        exit;


                    }
                }
                else{//No existe el código de usuario
                    Sistema::app()->paginaError(404, "No se ha encontrado el usuario con el código indicado");
                    exit;
                }
               
                

            }
            else{//Tiene que acceder como superadmin
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;

            }

        }
        else{//Si no está logueado
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }



    }


    /**
     * 
     *
     * @return Void
     */
    public function accionModificarUsuario (): Void {

        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(2) === true){

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

                //Se comprueba que existe el usuario

                $usuario =  new Usuarios ();


                if ($usuario->buscarPorId($id) === true){

                    $this->barraUbi = [
                        [
                            "texto" => "inicio",
                            "url" => "/"
                        ],
                        [
                            "texto" => "Control de usuario",
                            "url" => ["usuarios", "indexUsuarios"]
                        ],
                        [
                            "texto" => "Modificar usuario ",
                            "url" => ["usuarios", "modificarUsuario/?id=$id"]
                        ]
                    ];

                    $aclUser = new AclUsuarios ();
                    $nick = $usuario->nick;
                    $datosForm = [
                        "contraAcl" => "",
                        "contraAcl1" => ""
                    ];
                    $erroresForm = [];

                    if ($aclUser->buscarPor(["where" => " `nick` = '$nick' "]) === true){




                        $arrayEstados = Usuarios::dameEstatos();
                        $arrayProvincias = Usuarios::provinciasAndalucia();
                        $arrayPoblaciones = [];
                        $arrayRole = AclUsuarios::devuelveAclRoles();


                        $datos = [
                            "Provincia" => $usuario->provincia,
                            "Municipio" => ""
                        ];

                        $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
                        $proxy = "";
                        $errores = [];
                        $arbol = Funciones::peticionesXML($ruta, $errores, $datos, $proxy);
                        foreach ($arbol->xpath("//municipiero/muni/nm") as $valor) {
                            $arrayPoblaciones["" . $valor] = "" . $valor; //lo pasamos a cadena
                        }


                        if ($_POST) { //VALIDAR MODELO USUARIO Y ACL USUARIO

                            $nombre = $usuario->getNombre();

                            if (isset($_POST[$nombre])) {


                                //USUARIO
                                $usuario->setValores($_POST[$nombre]);

                                if ($usuario->fecha_nacimiento !== "") {
                                    $usuario->fecha_nacimiento = CGeneral::fechaMysqlANormal($usuario->fecha_nacimiento);
                                }

                                $usuario->codigo = intval($usuario->codigo);

                                //ACL USUARIO
                                //PRIMERO COMPROBAMOS CONTRASEÑA
                                if (isset($_POST["contraAcl"])){ //Se ha introducido contraseña

                                    $contra = trim($_POST["contraAcl"]);

                                    if ($contra !== ""){ //Si es distinta de cadena vacia, validamos

                                        $repiteContra = "";
                                        if (isset($_POST["contraAcl1"])){

                                            $repiteContra = trim($_POST["contraAcl1"]);
                                        }

                                        if ($repiteContra === ""){
                                            $erroresForm["contraAcl1"] = "Debes repetir contraseña";
                                        }

                                        if ($contra !== $repiteContra){
                                            $erroresForm["contraAcl"] = "La contraseña y repetir contraseña deben coincidir";
                                            $datos = [
                                                       "contraAcl" => "",
                                                        "contraAcl1" => ""
                                            ];
                                        }

                                    }
                                    $datosForm["contraAcl"] = trim($_POST["contraAcl"]);

                                }

                                //Actualizo aclRoles
                                $nombreAcl = $aclUser->getNombre();
                                if (isset($_POST[$nombreAcl])){
                                    //Actualizo datos
                                    $aclUser->setValores($_POST[$nombreAcl]);
                                }

                                //Ahora se comprueban si hay errores de contraseña normal
                                if (count($erroresForm) === 0){//Si no hay errores, actualizo contraseña de aclRoles

                                    //Actualizamos datos
                                    $datos["contraAcl"] = trim($_POST["contraAcl"]);
                                    $datos["contraAcl1"] = trim($_POST["contraAcl1"]);

                            
                                    if ( $datosForm["contraAcl"] !== ""){
                                        $aclUser->contrasenia = $datos["contraAcl"];
                                    }


                                }

                                //Para que este todo bien, se tiene que validar usuario
                                //se tiene que validar la acl 
                                //se tiene que validar que no hay errores de contraseña
                                $validaUsuario = $usuario->validar();
                                $validaAclUsuario =  $aclUser->validar();
                                
                                if ($validaUsuario && $validaAclUsuario && (count($erroresForm) === 0) ) {


                                        $usuarioG = $usuario->guardar();
                                        $aclG = $aclUser->guardar();

                                    if ($usuarioG && $aclG) {

                                        //se valida todo bien, lo mandamos a ver usuario
                                        $codUsuario = intval($usuario->cod_usuario);
                                        header("location: " . Sistema::app()->generaURL(["usuarios", "verUsuario"], ["id" => $codUsuario]));
                                        exit;
                                    } 
                                    
                                    else { //página de error

                                        if ($usuario->fecha_nacimiento !== "") {
                                            $usuario->fecha_nacimiento = CGeneral::fechaNormalAMysql($usuario->fecha_nacimiento);
                                        }



                                        $datos = [
                                            "Provincia" => $usuario->provincia,
                                            "Municipio" => ""
                                        ];

                                        $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
                                        $proxy = "";
                                        $errores = [];
                                        $arbol = Funciones::peticionesXML($ruta, $errores, $datos, $proxy);
                                        foreach ($arbol->xpath("//municipiero/muni/nm") as $valor) {
                                            $arrayPoblaciones["" . $valor] = "" . $valor; //lo pasamos a cadena
                                        }

                                        $this->dibujaVista("modificarUsuario", [
                                            "usuario" => $usuario,
                                            "arrayEstados" => $arrayEstados,
                                            "arrayRole" => $arrayRole,
                                            "aclUser" => $aclUser,
                                            "arrayAndalucia" => $arrayProvincias,
                                            "datosForm" => $datosForm,
                                            "arrayPoblaciones" => $arrayPoblaciones,
                                            "errores" => $erroresForm,
                                        ], "Modificar usuario " . $usuario->nick);
                                        exit;
                                    }

                                } else { //no se ha validado usuario, acl usuario o hay errores de contraseña

                                    if ($usuario->fecha_nacimiento !== "") {
                                        $usuario->fecha_nacimiento = CGeneral::fechaNormalAMysql($usuario->fecha_nacimiento);
                                    }


                                    $usuario->codigo = intval($usuario->codigo);

                                    $datos = [
                                        "Provincia" => $usuario->provincia,
                                        "Municipio" => ""
                                    ];

                                    $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
                                    $proxy = "";
                                    $errores = [];
                                    $arbol = Funciones::peticionesXML($ruta, $errores, $datos, $proxy);

                                    if ($arbol === false){ //cuando en el combo de provincia no elegimos una opcion
                                        
                                        $arrayPoblaciones = [];
                                    }
                                    else{
                                        $arrayPoblaciones = []; //vaciamos y actualizamos
                                        foreach ($arbol->xpath("//municipiero/muni/nm") as $valor) {
                                            $arrayPoblaciones["" . $valor] = "" . $valor; //lo pasamos a cadena
                                        }
                                    }
                                    

                                    $this->dibujaVista("modificarUsuario", [
                                        "usuario" => $usuario,
                                        "arrayEstados" => $arrayEstados,
                                        "arrayAndalucia" => $arrayProvincias,
                                        "aclUser" => $aclUser,
                                        "arrayRole" => $arrayRole,
                                        "datosForm" => $datosForm,
                                        "errores" => $erroresForm,
                                        "arrayPoblaciones" => $arrayPoblaciones
                                    ], "Modificar usuario " . $usuario->nick);
                                    exit;
                                }
                            }
                        }

                        if ($usuario->fecha_nacimiento !== "") {
                            $usuario->fecha_nacimiento = CGeneral::fechaNormalAMysql($usuario->fecha_nacimiento);
                        }



                        $this->dibujaVista("modificarUsuario", [
                            "usuario" => $usuario,
                            "arrayEstados" => $arrayEstados,
                            "aclUser" => $aclUser,
                            "arrayAndalucia" => $arrayProvincias,
                            "arrayRole" => $arrayRole,
                            "datosForm" => $datosForm,
                            "errores" => $erroresForm,
                            "arrayPoblaciones" => $arrayPoblaciones
                        ], "Modificar usuario " . $usuario->nick);

                    }
                    else{
                        Sistema::app()->paginaError(404, "Problema en el acceso de la base de datos");
                        exit;
                    }
                    
                }
                else{//No existe el código de usuario
                    Sistema::app()->paginaError(404, "No se ha encontrado el usuario con el código indicado");
                    exit;
                }
               
                

            }
            else{//Tiene que acceder como superadmin
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;

            }

        }
        else{//Si no está logueado
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }
    }






    /**
     * 
     *
     * @return Void
     */
    public function accionBorrarUsuario (): Void {
        
        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(2) === true){

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

                //Se comprueba que existe el usuario

                $usuario =  new Usuarios ();


                if ($usuario->buscarPorId($id) === true){

                    if (intval($usuario->borrado) === 0){
                        $this->barraUbi = [
                            [
                                "texto" => "inicio",
                                "url" => "/"
                            ],
                            [
                                "texto" => "Control de usuario",
                                "url" => ["usuarios", "indexUsuarios"]
                            ],
                            [
                                "texto" => "Borrar usuario ",
                                "url" => ["usuarios", "borrarUsuario/?id=$id"]
                            ]
                        ];
                        $arrayEstados = Usuarios::dameEstatos();
    
                        if ($_POST){
                            
                            $nombre = $usuario->getNombre();
    
                            if (isset($_POST[$nombre])){
    
                                $usuario->setValores($_POST[$nombre]);
    
                                if ($usuario->validar()){
    
                                    if ($usuario->guardar()){
    
                                        //se valida todo bien, lo mandamos a ver usuario
                                        $codUsuario = intval($usuario->cod_usuario);
                                        header("location: ". Sistema::app()->generaURL(["usuarios", "verUsuario"], ["id"=>$codUsuario]));
                                        exit; 
                                    }
                                    else{//página de error
                                                            

                                        $this->dibujaVista("borrarUsuario", [
                                                        "usuario"=> $usuario,
                                                    ], "Borrar usuario ". $usuario->nick);
                                        exit;
                                    }
    
                                }
                                else{//página de error
                                                
    
    
                                    $this->dibujaVista("borrarUsuario", [
                                                    "usuario"=> $usuario,
                                                ], "Borrar usuario ". $usuario->nick);
                                    exit;
                                }
    
                            }
    
                        }
    

    
                        $this->dibujaVista("borrarUsuario", [
                                        "usuario"=> $usuario,
                                    ], "Borrar usuario ". $usuario->nick);
    
    
                    }
                    else{
                        Sistema::app()->paginaError(404, "El usuario ya ha sido borrado");
                        exit;
                    }

                
                }
                else{//No existe el código de usuario
                    Sistema::app()->paginaError(404, "No se ha encontrado el usuario con el código indicado");
                    exit;
                }
               
                

            }
            else{//Tiene que acceder como superadmin
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;

            }

        }
        else{//Si no está logueado
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }
    }






    public function accionAnadirUsuario (): Void {


        if (Sistema::app()->Acceso()->hayUsuario() === true){

            if (Sistema::app()->Acceso()->puedePermiso(2) === true){



                $this->barraUbi = [
                    [
                        "texto" => "inicio",
                        "url" => "/"
                    ],
                    [
                        "texto" => "Control de usuario",
                        "url" => ["usuarios", "indexUsuarios"]
                    ],
                    [
                        "texto" => "Añadir usuario ",
                        "url" => ["usuarios", "anadirUsuario"]
                    ]
                ];


                $usuario =  new Usuarios ();
                $aclUser = new AclUsuarios ();
                $arrayEstados = Usuarios::dameEstatos();
                $arrayProvincias = Usuarios::provinciasAndalucia();
                $arrayPoblaciones = [];
                $datosForm = [
                    "contraAcl" => "",
                    "contraAcl1" => ""
                ];
                $erroresForm = [];

                $datos = [
                    "Provincia" => $usuario->provincia,
                    "Municipio" => ""
                ];
                $arrayRole = AclUsuarios::devuelveAclRoles();

                $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
                $proxy = "";
                $errores = [];
                $arbol = Funciones::peticionesXML($ruta, $errores, $datos, $proxy);
                foreach ($arbol->xpath("//municipiero/muni/nm") as $valor) {
                    $arrayPoblaciones["" . $valor] = "" . $valor; //lo pasamos a cadena
                }




                if ($_POST){
                    
                    $nombre = $usuario->getNombre();

                    if (isset($_POST[$nombre])){

                        //USUARIO
                        $usuario->setValores($_POST[$nombre]);

                        if ($usuario->fecha_nacimiento !== ""){
                            $usuario->fecha_nacimiento = CGeneral::fechaMysqlANormal($usuario->fecha_nacimiento);

                        }

                        //ACL USUARIO
                        //Se comprueba contraseña
                        $contra = "";
                        $contraAcl1 = "";

                        if (isset($_POST["contraAcl"])){
                            
                            $contra = trim($_POST["contraAcl"]);

                            if ($contra === ""){
                                $erroresForm["contraAcl"] = "Debes introducir una contraseña";

                            }
                        }
                        $datosForm["contraAcl"] = trim($_POST["contraAcl"]);


                        if (isset($_POST["contraAcl1"])){

                            $contraAcl1 = trim($_POST["contraAcl1"]);
                            
                            if ($contraAcl1 === ""){
                                $erroresForm["contraAcl1"] = "El campo repite contraseña no puede ir vacío";

                            }
                        }

                        //Ahora valido que sean igual con la condición de que sean
                        //distintos de cadena vacia para evitar que se pongan errores
                        //que no corresponden

                        if(($contra !== "" && $contraAcl1 !== "")){//son distintas de cadena vacia

                            if ($contra !== $contraAcl1){
                                $erroresForm["contraAcl"] = "Las contraseñas deben coincidir";
                                $datos["contraAcl1"] = "";
                            }

                        }

                        //Ahora se actualiza aclRoles

                        $nombreAcl = $aclUser->getNombre();
                        if(isset($_POST[$nombreAcl])){

                            //Actualizamos los datos
                            $aclUser->nick = $usuario->nick;
                            $aclUser->contrasenia =  trim(($_POST["contraAcl"]));
                            $aclUser->setValores($_POST[$nombreAcl]);
                        }

                        //Ahora se comprueban si hay errores de contraseña normal
                        if (count($erroresForm) === 0) { //Si no hay errores, actualizo contraseña de aclRoles

                            //Actualizamos datos
                            $datos["contraAcl"] = trim($_POST["contraAcl"]);
                            $datos["contraAcl1"] = trim($_POST["contraAcl1"]);


                            if ($datosForm["contraAcl"] !== "") {
                                $aclUser->contrasenia = $datos["contraAcl"];
                            }
                        }

                        //Para que este todo bien, se tiene que validar usuario
                        //se tiene que validar la acl 
                        //se tiene que validar que no hay errores de contraseña
                        $validaUsuario = $usuario->validar();
                        $validaAclUsuario =  $aclUser->validar();

                        if ($validaUsuario && $validaAclUsuario && (count($erroresForm) === 0) ) {

                            $usuarioG = $usuario->guardar();
                            $aclG = $aclUser->guardar();

                            if ($usuarioG && $aclG) {

                                //se valida todo bien, lo mandamos a ver usuario
                                $codUsuario = intval($usuario->cod_usuario);
                                header("location: ". Sistema::app()->generaURL(["usuarios", "verUsuario"], ["id"=>$codUsuario]));
                                exit; 
                            }

                            else{//página de error

                                if ($usuario->fecha_nacimiento !== ""){ 
                                    $usuario->fecha_nacimiento = CGeneral::fechaNormalAMysql($usuario->fecha_nacimiento);

                                }

                                                    
                                $datos = [
                                    "Provincia" => $usuario->provincia,
                                    "Municipio" => ""
                                ];
            
                                $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
                                $proxy = "";
                                $errores = [];
                                $arbol = Funciones::peticionesXML($ruta, $errores, $datos, $proxy);
                                foreach ($arbol->xpath("//municipiero/muni/nm") as $valor) {
                                    $arrayPoblaciones["" . $valor] = "" . $valor; //lo pasamos a cadena
                                }

                                $this->dibujaVista("anadirUsuario", [
                                            "usuario" => $usuario,
                                            "arrayEstados" => $arrayEstados,
                                            "arrayRole" => $arrayRole,
                                            "aclUser" => $aclUser,
                                            "arrayAndalucia" => $arrayProvincias,
                                            "datosForm" => $datosForm,
                                            "arrayPoblaciones" => $arrayPoblaciones,
                                            "errores" => $erroresForm,
                                            ], "Añadir usuario ");
                                exit;
                            }

                        }
                        else{ //no se ha validado usuario, acl usuario o hay errores de contraseña
                            
                            if ($usuario->fecha_nacimiento !== ""){
                                $usuario->fecha_nacimiento = CGeneral::fechaNormalAMysql($usuario->fecha_nacimiento);

                            }
                            $usuario->codigo = intval($usuario->codigo);

                            $datos = [
                                "Provincia" => $usuario->provincia,
                                "Municipio" => ""
                            ];
        
                            $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
                            $proxy = "";
                            $errores = [];
                            $arbol = Funciones::peticionesXML($ruta, $errores, $datos, $proxy);

                            if ($arbol === false){ //cuando en el combo de provincia no elegimos una opcion
                                        
                                $arrayPoblaciones = [];
                            }
                            else{
                                $arrayPoblaciones = []; //vaciamos y actualizamos
                                foreach ($arbol->xpath("//municipiero/muni/nm") as $valor) {
                                    $arrayPoblaciones["" . $valor] = "" . $valor; //lo pasamos a cadena
                                }
                            }
                            

                            $this->dibujaVista("anadirUsuario", [
                                "usuario" => $usuario,
                                "arrayEstados" => $arrayEstados,
                                "arrayAndalucia" => $arrayProvincias,
                                "aclUser" => $aclUser,
                                "arrayRole" => $arrayRole,
                                "datosForm" => $datosForm,
                                "errores" => $erroresForm,
                                "arrayPoblaciones" => $arrayPoblaciones
                                        ], "Añadir usuario ");
                            exit;
                        }

                    }

                }

                if ($usuario->fecha_nacimiento !== ""){
                    $usuario->fecha_nacimiento = CGeneral::fechaNormalAMysql($usuario->fecha_nacimiento);

                }



                $this->dibujaVista("anadirUsuario", [
                    "usuario" => $usuario,
                    "arrayEstados" => $arrayEstados,
                    "aclUser" => $aclUser,
                    "arrayAndalucia" => $arrayProvincias,
                    "arrayRole" => $arrayRole,
                    "datosForm" => $datosForm,
                    "errores" => $erroresForm,
                    "arrayPoblaciones" => $arrayPoblaciones
                            ], "Añadir usuario ");



            }
            else{//Tiene que acceder como superadmin
                Sistema::app()->paginaError(404, "No tienes permisos para acceder a esta página") . PHP_EOL;

            }

        }
        else{//Si no está logueado
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }
    }





    /**
     * Petición ajax combo de andalucia
     *
     * @return void
     */
    public function accionPeticionProvinciasAndalucia (){
       
        if ($_POST){

            $arrayRespuesta = [];

            if (isset($_POST["provincia"])){

                $provincia = trim($_POST["provincia"]);
                
                if  ($provincia !== ""){

                    $datos = [
                        "Provincia" => $provincia,
                        "Municipio" => ""
                    ];

                    $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
                    $proxy = "";
                    $errores = [];
                    $arbol = Funciones::peticionesXML($ruta, $errores, $datos, $proxy);

                    if ($arbol !== false) {
                
                        foreach ($arbol->xpath("//municipiero/muni/nm") as $valor) {
                            $arrayMunicipios["" . $valor] = "" . $valor; //lo pasamos a cadena
                        }

                        $arrayRespuesta = [
                            "correcto" => true,
                            "respuesta" => $arrayMunicipios
                        ];

                                            
                    }
                    else{
                        $arrayRespuesta = [
                            "correcto" => false,
                            "respuesta" => "Error API"
                        ];
                    }

                }
                else{

                    $arrayRespuesta = [
                        "correcto" => false,
                        "respuesta" => "Cadena vacía"
                    ];
                }

            }


            echo json_encode($arrayRespuesta);

        }
    }



    /**
     * Función para realizar peticiones post
     * desde una API
     * nos llega una ruta, un array con los parametros
     * y un string que es el proxy esto puede ser nulo o una cadena
     *
     * @param String $url
     * @param Array $parametros
     * @param String|Null $proxy
     * @return SimpleXMLElement | False
     */
    public static function peticionesXML(string $url, array &$errores, array $parametros = [], string $proxy = ""): false | SimpleXMLElement
    {

        $enlaceCurl = curl_init();

        if (!curl_setopt($enlaceCurl, CURLOPT_URL, $url)) {
            return false;
        }

        curl_setopt($enlaceCurl, CURLOPT_POST, 1);


        if (count($parametros) !== 0) { //comprobamos si nos llegan parametros al array
            $cadena = "";

            foreach ($parametros as $clave => $valor) {
                $cadena .= "$clave=$valor&";
            }

            $cadena = mb_substr($cadena, 0, -1);

            curl_setopt($enlaceCurl, CURLOPT_POSTFIELDS, "$cadena");
        }

        curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
        curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);


        //Comprobamos si llega proxy o no
        if ($proxy !== "") { //si hay proxy se añade

            if (!curl_setopt($enlaceCurl, CURLOPT_PROXY, $proxy)) {
                return false;
            }
        }

        //ejecuto la petición
        $xml = curl_exec($enlaceCurl);
        //cierro la sesión
        curl_close($enlaceCurl);


        $xml = str_replace('xmlns=', 'ns=', $xml);
        $arbol = new SimpleXMLElement($xml);


        if (count($arbol->xpath("//lerr/err/des")) !== 0) {

            foreach ($arbol->xpath("//lerr/err/des") as $valor) {
                $pasaACadena = "" . $valor[0][0];
                $errores["peticion"][] = $pasaACadena;
            }
            return false;
        } else {

            return $arbol;
        }
    }

}







?>