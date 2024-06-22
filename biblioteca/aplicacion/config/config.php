<?php

	$config=array("CONTROLADOR"=> array("inicial"),
				  "RUTAS_INCLUDE"=>array("aplicacion/modelos", "aplicacion/funcionalidades"),
				  "URL_AMIGABLES"=>true,
				  "VARIABLES"=>array("autor"=>"Alejandro Terrones Pérez",
				  					"direccion"=>"C/ Mesones nº 18, 1º K",
									"cookieNombre" => "",//VARIABLE APLICACION COOKIE
									"cookiePW" => "", //VARIABLE APLICACION COOKIE
									"variableSesionGenero" => -1 //VARIABLE APLICACION SESION
								),
				  "BD"=>array("hay"=>true,
								"servidor"=>"localhost",
								"usuario"=>"root",
								"contra"=>"2daw",
								"basedatos"=>"biblioteca"),


					"Acceso" => array("controlAutomatico" => true),

					"SESION" => array("controlAutomatico" => true),

					"ACL" => array("controlAutomatico" => true),

				
);
