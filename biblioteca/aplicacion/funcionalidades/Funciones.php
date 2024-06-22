<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:\web\sitios\biblioteca\vendor\autoload.php';


class Funciones {


    Public static function sendMensajeEmail (string $correo, string $nick, string $subject, string $mensaje){
    
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        // $mail->Port = 587;

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
                                            background-color: black;
                                            color: white;
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