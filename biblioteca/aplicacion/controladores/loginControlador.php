<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:\web\sitios\biblioteca\vendor\autoload.php';

/**
 * Clase para el controlador del login
 * nos permite logearnos con uno de los usuarios disponibles en la BBDD
 */
class loginControlador extends CControlador {


    /**
     * Acción para el login de la aplicación, 
     * nos muestra un formulario que nos pide el nick y contraseña
     * 
     * se validan los datos a partir del modelo de Login, si se cumplen
     * iniciamos sesión con el nick ingresado, si no se muestran los errores del logeo
     * 
     * 
     * @return Void, no devuelve nada imprime una vista
     */
    public function accionInicioSesion (){

        //Comprobamos si hay usuario logeado, en tal caso
        //lo redirigimos a la acción anterior

		$nickUserActual = Sistema::app()->Acceso()->getNick();
		$codUserActual = Sistema::app()->ACL()->getCodUsuario($nickUserActual);
		$borradoActual = Sistema::app()->ACL()->getBorrado($codUserActual); 

        //Si hay usuario y no está borrado, hay login, lo mandamos a la acción anterior
		if (Sistema::app()->Acceso()->hayUsuario() === true && (!$borradoActual)){

			Sistema::app()->irAPagina(["inicial"]);
            exit();
		}

        

        //Barra de ubicación
		$this->barraUbi = [
			[
                "texto" => "inicio",
                "url" => "/"
            ],
            [
                "texto" => "Login",
                "url" => ["login", "InicioSesion"]
            ]
	  	];

        $login = new Login();
        $nombre = $login->getNombre();


        if (isset($_POST[$nombre])){


            //asigno valores de registro
            $login->setValores($_POST[$nombre]);

            //Guardo nick logueado
            $nick = $login->nick;


            //Creo objeto usuario
            $usuario = new Usuarios ();
         
            //Ahora busco en usuario si este existe
            if ($usuario->buscarPor(["where" =>  "nick = '$nick'"])){

                $estado = intval($usuario->estado);
                //1--> PENDIENTE, 2-->APROBADO, 3-->CANCELADO
                if ($estado === 1){ //ESTA PENDIENTE DE EL CODIGO


                    //LO REENVIO A LA PÁGINA DE CONFIRMACIÓN DEL CÓDIGO


                    //CREO LA SESIÓN
                    if (isset($_SESSION)){

                        $_SESSION["datosVerificacionCodigo"] = [
                            "usuario" => $usuario->nick,
                            "nombreCompleto" => $usuario->nombre,
                            "codigoVerificacion" => $usuario->codigo,
                            "correo" => $usuario->email
                        ];
                    }
                    else{
                        
                        $_SESSION["datosVerificacionCodigo"] = [
                            "usuario" => $usuario->nick,
                            "nombreCompleto" => $usuario->nombre,
                            "codigoVerificacion" => $usuario->codigo,
                            "correo" => $usuario->email
                        ];

                    }


                    //Lo mando a la página de verificación de código


                      
                    header("location: ". "http://www.biblioteca.es/login/ValidarCodigo");
                    exit();
                }

                //1--> PENDIENTE, 2-->APROBADO, 3-->CANCELADO
                else if  ($estado === 3){ //ESTA SUSPENSO

                    Sistema::app()->paginaError(505, "Usuario cancelado");

                }

               //1--> PENDIENTE, 2-->APROBADO, 3-->CANCELADO
                else if ($estado === 2){

                    if ($login->validar()) {

                        $codUser = Sistema::app()->ACL()->getCodUsuario($login->nick);
                        $nombreUser = Sistema::app()->ACL()->getNombre($codUser); //Nombre de usuario
                        $arrayPermisos = Sistema::app()->ACL()->getPermisos($codUser); //Lista de permisos


                        if ($usuario->borrado === 1 ) { //Si da true, es borrado, no accede
                            Sistema::app()->paginaError("505", "El usuario está borrado, no puede acceder");
                            exit;
                        } else { //Si no está borrado

                            if (Sistema::app()->Acceso() !== null) {

                                $registro = Sistema::app()->Acceso()->registrarUsuario($login->nick, $nombreUser, $arrayPermisos);


                                if ($registro === true) { //Si da true, le mandamos a la acción anterior

                                    if (isset($_SESSION["anterior"])) {
                                        Sistema::app()->irAPagina($_SESSION["anterior"]);
                                        exit;
                                    } else {
                                        Sistema::app()->irAPagina(["inicial"]);
                                        exit;
                                    }
                                } else {
                                    Sistema::app()->paginaError("505", "No se ha podido registrar el usuario");
                                    exit;
                                }
                            }
                        }
                    } else { //Si hay errores al validar

                        $this->dibujaVista("login", ["miLogin" => $login], "Biblioteca Grimorios - Inicio sesión");
                        exit;
                    }
                }


                else{ //ES QUE EL CODIGO SE HA INTRODUCIDO MAL
                    Sistema::app()->paginaError(505, "El usuario no contiene ningún código de estado");

                }   


            }
            else{

                Sistema::app()->paginaError(505, "El usuario no existe");
            }



            //PRIMERO ANTES DE TODO, SE COMPRUEBA SI EL USUARIO ESTA
            //PENDIENTE DE VERIFICACION

            //COGEMOS NICK DESDE ACL
            //BUSCAMOS EN USUARIOS

            //Y SE COMPRUEBA SI ESTA
            //MIRAMOS CODIGO

            //SI NO ESTÁ
            //CONTINUAMOS CON VERIFICACIÓN NORMAL
            


        }


        $this->dibujaVista("login", ["miLogin" => $login], "Biblioteca Grimorios - Inicio sesión");

    }


    /**
     * Acción para cerrar sesión, la usaremos en una etiqueta a
     * para que el usuario la pulse y se desloguee de su sesión actual
     *
     * @return Void, no devuelve nada lo unico que hacer es quitar
     *          el login actual y redireccionar al inicio
     */
    public function accionCerrarSesion(){
        
        
        //Se comprueba que hay usuario
        if (Sistema::app()->Acceso()->hayUsuario() === true){

            Sistema::app()->Acceso()->quitarRegistroUsuario();
            Sistema::app()->irAPagina(array("inicial"));
            exit;


        }
        else{
            Sistema::app()->irAPagina(["login", "InicioSesion"]);

        }
    }




    /**
     * PONER COMENTARIOS
     *
     * @return void
     */
    public function accionNuevoUsuario (){


        //Cogemos datos para validar
        $nickUserActual = Sistema::app()->Acceso()->getNick();
		$codUserActual = Sistema::app()->ACL()->getCodUsuario($nickUserActual);
		$borradoActual = Sistema::app()->ACL()->getBorrado($codUserActual); 


        //Si existe un usuario, no puedo crear un nuevo usuario, debe quitar su login
        if (Sistema::app()->Acceso()->hayUsuario() === true && (!$borradoActual)){

			Sistema::app()->irAPagina(["inicial"]);
            exit();
		}


        //Barra de ubicación
		$this->barraUbi = [
			[
                "texto" => "inicio",
                "url" => "/"
            ],
            [
                "texto" => "Nuevo usuario",
                "url" => ["login", "NuevoUsuario"]
            ]
	  	];



        $usuario = new Usuarios ();
        $aclUsuario = new AclUsuarios ();
        $datos = [
            "nombre" => "",
            "contra" => ""
        ];
        $errores = []; //Posibles errores
        $arrayProvincias = Usuarios::provinciasAndalucia(); //para combo
        $arrayPoblaciones = []; //para combo

        //Para petición XML
        $datosPeticion = [
            "Provincia" => $usuario->provincia,
            "Municipio" => ""
        ];


        //PETICIÓN XML PARA SACAR MUNICIPIOS
        $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
        $proxy = "";
        $erroresXML = [];

        $arbol = Funciones::peticionesXML($ruta, $erroresXML, $datosPeticion, $proxy);
        foreach ($arbol->xpath("//municipiero/muni/nm") as $valor) {
            $arrayPoblaciones["" . $valor] = "" . $valor; //lo pasamos a cadena
        }



        if ($_POST){ //Se validan los datos del formulario

            $nombreUser = $usuario->getNombre();
            
            if (isset($_POST[$nombreUser])){

                $usuario->setValores($_POST[$nombreUser]); 
            
            }


            $nombre = "";
            if (isset($_POST["aclNombre"])){

                $nombre = trim($_POST["aclNombre"]);

                if ($nombre === ""){
                    $errores["nombre"] = "Debes introducir un nombre";
                }

            }
            $datos["nombre"] = $nombre;


            $contra = "";
            if (isset($_POST["aclPw"])){

                
                $contra = trim($_POST["aclPw"]);

                if ($contra === ""){
                    $errores["aclPw"] = "Debes introducir una contraseña";
                }

            }

            $usuario->estado = 1;
            $datos["contra"] = $contra;

            $valorPrueba = $usuario->validar();
            $num = count($errores);
            
            ///Validamos que no haya errores en el formulario
            if ($valorPrueba  && ($num === 0)){ 


                //Como todo es correcto, generamos un nº aleatorio que va a ser el que se guarde en la tabla
                //y el que se envie por correo al usuario, desde la accion de validar codigo de valida
                $codigo = mt_rand(100000, 999999);

                //Hacemos insercción en base de datos
                $usuario->codigo = $codigo;
                $validarGuardarUsuario = $usuario->guardar(); //probamos si funciona

                //guardamos datos para la acl
                $aclUsuario->nick = $usuario->nick;
                $aclUsuario->nombre = $datos["nombre"];
                $aclUsuario->contrasenia = $datos["contra"];
                $aclUsuario->cod_acl_role = 3; //por defecto role 3, cliente
                $aclUsuario->borrado = 0;


                $validarGuardarACLRole = $aclUsuario->validar();
                $validarGuardarACLRole = $aclUsuario->guardar();


                if ($validarGuardarUsuario && $validarGuardarACLRole) { //se guardan datos de usuario y nick en tablas  


                    //En sesión guardamos los dos modelos para poder acceder
                    //Esa sesión solamente va a guardar ACLusuario y usuario
                    //Se borrará una vez el cod se haya validado

                    if (isset($_SESSION["datosVerificacionCodigo"])){

                        $_SESSION["datosVerificacionCodigo"] = [
                            "usuario" => $usuario->nick,
                            "nombreCompleto" => $usuario->nombre,
                            "codigoVerificacion" => $codigo,
                            "correo" => $usuario->email
                        ];

                    }
                    else{
                        
                        $_SESSION["datosVerificacionCodigo"] = [
                            "usuario" => $usuario->nick,
                            "nombreCompleto" => $usuario->nombre, //VER PORQUE NO PILLA EL NOMBRE
                            "codigoVerificacion" => $codigo,
                            "correo" => $usuario->email

                        ];
                    }


                    //Tras guardar los datos en la sesión, mandamos el codigo por correo
                    $correo = $usuario->email;
                    $nombre = $datos["nombre"];
                    // $enviarMail  = sendCodigo($codigo, $correo, $nombre);
                    $mensaje = "Hola ". $nombre . " este es un mensaje para la verficiación de tu cuenta, \n
                                introduce el siguiente código, por favor no lo compartas con nadie <b>" . $codigo . "</b>";
                    $enviarMail = Funciones::sendMensajeEmail($correo, $nombre, "Código de verificación", $mensaje);

                    if ($enviarMail === true){//En caso de que se haya enviado bien el correo

                        //Redirigimos a la página de validación del código de cuenta
                        
                        header("location: ". "http://www.biblioteca.es/login/ValidarCodigo");
                        exit();

                    }
                    else{//Si ha habido errores al enviar el correo

                        Sistema::app()->paginaError(505, "No se pudo enviar código de verificación, vuelva a intentarlo");

                    }
     


                } else {

                    Sistema::app()->paginaError(505, "Problema en la base de datos");
                }
                
            }
            else{//errores del formulario
                

            }
         
        }

    

        //Aquí es donde llega la primera vez
        $this->dibujaVista("nuevousuario", ["usuario" => $usuario, 
                                            "aclUsuario" => $aclUsuario,
                                            "datos" => $datos,
                                            "errores" => $errores,
                                            "arrayPoblaciones" => $arrayPoblaciones,
                                            "arrayAndalucia" => $arrayProvincias,
                                        ],
                                             "Biblioteca Grimorios - Nuevo usuario");

    }



    /**
     * 
     *
     * @return void
     */
    public function accionReenviarCodigo(){

        $this->barraUbi = [
			[
                "texto" => "inicio",
                "url" => "/"
            ]
	  	];


        //Primero se pregunta por la sesion
        if (isset($_SESSION["datosVerificacionCodigo"])){ //Para llegar aqui tenemos que haber introducido un usuario 
                                                          //que no recuerda su pin

            if (isset($_SESSION["datosVerificacionCodigo"]["usuario"])){

                $nick =  $_SESSION["datosVerificacionCodigo"]["usuario"];
                $usuario = new Usuarios ();
                //Buscamos usuario
                if ($usuario->buscarPor(["where" => " nick = '$nick' "])) {

                    //Ahora generamos nuevo numero aleatorio
                    $codigo = mt_rand(100000, 999999);
                    $usuario->codigo = $codigo; //creamos nuevo código

                    if ($usuario->guardar()) { //actualizamos usuario

                        //actualizamos valor en la sesion
                        $_SESSION["datosVerificacionCodigo"]["codigoVerificacion"] = $codigo; //ya se comprobó antes que existia

                        //Enviamos correo 
                        $correo = $usuario->email;
                        $nombre = $usuario->nick;
                        $mensaje = "Hola ". $nombre . " este es un mensaje para la verficiación de tu cuenta, \n
                        introduce el siguiente código, por favor no lo compartas con nadie <b>" . $codigo . "</b>";
                        $enviarMail = Funciones::sendMensajeEmail($correo, $nombre, "Reenviar código de verificación", $mensaje);


                        if ($enviarMail === true) { //Se ha enviado el correo
                            //Enviamos a la página de verificacion de códgio

                            header("location: " . "http://www.biblioteca.es/login/ValidarCodigo");
                            exit();
                        } else {


                            Sistema::app()->paginaError(505, "No se pudo enviar código de verificación, vuelva a intentarlo");
                        }
                    } else {
                        Sistema::app()->paginaError(505, "Problema en la base de datos");
                    }
                } else {
                    Sistema::app()->paginaError(505, "El usuario ingresado no existe");
                }
            } else {
                Sistema::app()->paginaError(505, "No tiene los permisos para acceder");
            }
        }
        else{//Se ha metido sin tener cuenta pendiente de aprobación


            Sistema::app()->paginaError(505, "Acceso denegado");
        }
        


        //al final se envia a validar codigo


    }



    /**
     * 
     *
     * @return void
     */
    public function accionValidarCodigo (){

        //Barra de ubicación
		$this->barraUbi = [
			[
                "texto" => "inicio",
                "url" => "/"
            ],
            [
                "texto" => "Nuevo usuario",
                "url" => ["login", "NuevoUsuario"]
            ],
            [
                "texto" => "Validación de usuario",
                "url" => ["login", "ValidarCodigo"]
            ]
	  	];


        //Primero comprobamos que existe la sesión
        //Si no existe, mandamos a página de error
        //Si existe pero no hay usuario, también

        if (isset($_SESSION["datosVerificacionCodigo"])) {

            if ((isset($_SESSION["datosVerificacionCodigo"]["usuario"]))  && (isset($_SESSION["datosVerificacionCodigo"]["codigoVerificacion"]))) {


                //Se envia a la vista para el mensaje de bienvenida
                $nombreUser =  $_SESSION["datosVerificacionCodigo"]["usuario"];
                $correo = $_SESSION["datosVerificacionCodigo"]["correo"];
                $codigoValidar = "";

                $datos = [
                    "nombreUsuario" => $nombreUser,
                    "codigoValidar" => $codigoValidar,
                    "correo" => $correo
                ];

                $errores = [];


                if ($_POST) { //Aqui se valida el código enviado

                    if (isset($_POST["confirmarCodigo"])) {

                        if (isset($_POST["codigoConfirmacion"])) {

                            $codigoValidar = intval($_POST["codigoConfirmacion"]);
                            $codOriginal = intval($_SESSION["datosVerificacionCodigo"]["codigoVerificacion"]);

                            if ($codigoValidar !== $codOriginal) {
                                $errores["codigo"] = "El código introducido no coincide con el enviado";
                            }
                        }


                        if (count($errores) === 0) { //No hay errores

                            //Aquí se actualiza el usuario en la tabla usuario
                            //a partir del nick
                            //cambiamos su codigo de estado a 2
                            //Que indica que ha sido verificado


                            $usuario = new Usuarios();
                            $nick = $_SESSION["datosVerificacionCodigo"]["usuario"];

                            if ($usuario->buscarPor(["where" =>  "nick = '$nick'"])) {



                                //Actualizo su estado
                                $usuario->estado = 2; //APROBADO
                                $usuario->codigo = 0;



                                if ($usuario->guardar()) { //Guardo //ME SIGUE DANDO ERRRORE


                                    //BORRO DATOS DE LA SESIÓn
                                    $_SESSION["datosVerificacionCodigo"] = ""; //489886

                                    //Redirijo al login
                                    header("location: " . "http://www.biblioteca.es/login/InicioSesion");
                                    exit();
                                } else {
                                    Sistema::app()->paginaError(505, "Problema con la verificación de datos, vuelva a intentarlo");
                                }
                  

                            } 
                            else { //En caso de fallar al encontrar al usuario
                                Sistema::app()->paginaError(505, "Problema en el acceso de la base de datos");
                            }
                        } else { //Errores, le enviamos a la página 

                            $this->dibujaVista(
                                "confirmarusuario",
                                ["datos" => $datos, "errores" =>  $errores],
                                "Biblioteca Grimorios - Confirmar usuario"
                            );
                            exit();
                        }
                    }
                }

                $this->dibujaVista(
                    "confirmarusuario",
                    ["datos" => $datos],
                    "Biblioteca Grimorios - Confirmar usuario"
                );





            }
            else{//SI NO EXISTEN LOS VALORES 
                Sistema::app()->paginaError(505,"Se ha accedido a la página incorrecta");
            }
 
    }
    
    else{ //Si no existe la sesión, lo mandamos a página de error
        Sistema::app()->paginaError(505,"Se ha accedido a la página incorrecta");
    }

}

}

    /**
     * 
     *
     * @param integer $codigo
     * @param string $email
     * @param string $nombre
     * @return void
     */
    function sendCodigo(int $codigo, string $email, string $nombre)
    {


        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPAuth = true;

        //$mail->SMTPDebug = 2;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->CharSet = 'UTF-8';
        $mail->Username = 'libreriagrimorios@gmail.com';
        $mail->Password = 'pbzv gwpf qzpd wfjf';
        $mail->setFrom('libreriagrimorios@gmail.com', 'Librería Grimorios');
        $mail->addAddress($email, $nombre);
        $mail->Subject = 'Confirmación de activación de cuenta ';
        $mail->Body = "

                    <html lang='en'>
                        <head>
                        <meta charset='UTF-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <title>Mensaje de Correo Electrónico</title>
                        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
                        </head>
                        <body>

                        <!-- Contenido principal -->
                        <div class='container mt-4'>
                            <div class='card'>
                            <!-- Encabezado con logo -->
                            <div class='card-header'>
                                <div class='d-flex justify-content-between align-items-center'>
                                <div>
                                    <img src='../imagenes/logo.jpg' alt='Logo' style='width: 50px; height: 50px;'>
                                </div>
                                <div>
                                    <h5 class='card-title'>Confirmación de usuario</h5>
                                    <p class='card-text'>De: nombre@dominio.com</p>
                                    <p class='card-text'>Para: tu@dominio.com</p>
                                </div>
                                </div>
                            </div>
                            <!-- Cuerpo del mensaje -->
                            <div class='card-body'>
                                <p class='card-text'>Hola ". $nombre ." este es un mensaje para la verificación de tu cuenta,
                                                    introduce el siguiente código,
                                                    por favor no lo compartas con nadie: <b>". $codigo . "</b></p>
                            </div>
                            </div>
                        </div>

                            <!-- Scripts de Bootstrap y jQuery (requerido para algunos componentes de Bootstrap) -->
                            <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js'></script>
                            <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js'></script>
                            <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script>
                        </body>
                    </html>";


        $mail->IsHTML(true);


        return $mail->send();
    }



    //pbzv gwpf qzpd wfjf

        // $email = new PHPMailer(true);
        // $email->isSMTP();
        // $email->SMTPAuth = true;
        // $email->Username = 'libreriagrimorios@gmail.com';
        // $email->Password = 'pbzv gwpf qzpd wfjf';
        // $email->SMTPSecure = 'tls';
        // $email->Port = 587;
    
        // // Configura el remitente y destinatario
        // $email->setFrom('libreriagrimorios@gmail.com', 'Librería grimorio');
        // $email->addAddress('alejandroterronesper@gmail.com', 'Alejandro');
    
        // // Configura el contenido del correo
        // $email->isHTML(true);
        // $email->Subject = 'pruebo correo';
        // $email->Body = 'probando correo';
    
        // // Envía el correo
        // $email->send();

?>