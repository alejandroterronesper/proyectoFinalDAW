<?php


/**
 * Clase para el controlador inicial
 * contiene la acción del index principal
 * de la página
 * 
 * 
 * la accion de descarga de PDF , la accion formulario sugerencia
 * y la ventana emergente de libros
 */
class inicialControlador extends CControlador
{

	/**
	 * Acción index, esta será la acción que se ejecuta
	 * al poner el nombre de la página, esta imprime una vista con 
	 * los diferentes libros de la tabla libros, para obtenerlos
	 * usamos el modelo de Libros
	 *
	 * @return Void no devuelve nada, imprime la vista de libros
	 */
	public function accionIndex()
	{

		//Guardamos en sesión acción actual
		$_SESSION["anterior"] = ["inicial", "index"];

	
		//Barra de ubicación
		$this->barraUbi = [
			[
			  "texto" => "Inicio",
			  "url" => "/"
		  ]
	  	];

		//Array para guardar filtrado
		if (!isset($_SESSION["arrayFiltradoIndex"])){
			$_SESSION["arrayFiltradoIndex"] = [
				"titulo" => "",
				"autor" => "",
				"categoria" => 0,
				"selectWhere" => "",
				"obras" => []
			];
		}

		//Datos formulario
		$datos = [
			"titulo" => $_SESSION["arrayFiltradoIndex"]["titulo"],
			"autor" => $_SESSION["arrayFiltradoIndex"]["autor"],
			"categoria" => $_SESSION["arrayFiltradoIndex"]["categoria"]

		];
		$categoriasArray = CategoriasObras::dameCategoriasObras();;

		$selectWhere = "";

		//POST DE FILTRADO
		if ($_POST){
		
			if (isset($_POST["filtraDatosPrincipal"])){

					$titulo = "";
					if (isset($_POST["titulo"])){
						$titulo = trim(($_POST["titulo"]));
						$titulo = CGeneral::addSlashes($titulo);


						if ($titulo !== ""){
							$selectWhere.= " titulo LIKE '%$titulo%'";
						}
						
					}
					$datos["titulo"] = $titulo;

					$autor = "";
					if (isset($_POST["autor"])){
						$autor = trim(($_POST["autor"]));
						$autor = CGeneral::addSlashes($autor);

						if ($selectWhere !== ""){
							if ($autor !== ""){
								$selectWhere.= " AND autor LIKE '%$autor%'";
							}
						}
						else{
							if ($autor !== ""){
								$selectWhere.= " autor LIKE '%$autor%'";
							}
						}
					}
					$datos["autor"] = $autor;


					$categoria = 0;
					if (isset($_POST["categoria"])){

						$categoria = intval($_POST["categoria"]);

						if ($selectWhere !== ""){

							if ($categoria !== 0){
								$selectWhere.= " AND cod_categoria_obra  = $categoria ";
							}

						}
						else{
							if ($categoria !== 0){
								$selectWhere.= "  cod_categoria_obra  = $categoria ";
							}
						}

					
					}
					$datos["categoria"] = $categoria;

			}


			//Limpiar filtrado
			if (isset($_POST["limpiaFiltradoPrincipal"])){

				$datos["titulo"] = "";
				$datos["autor"] = "";
				$datos["categoria"] = 0;
				$selectWhere = "";
			}


			$_SESSION["arrayFiltradoIndex"] = [
				"titulo" => $datos["titulo"],
				"autor" => $datos["autor"],
				"categoria" => $datos["categoria"],
				"selectWhere" => $selectWhere
			];
		}

		$numPaginas = 0;
		$numProductos = 4; 
		$limite = "";
		$paginaActual = 1;

		if (isset($_GET["reg_pag"]) && isset($_GET["pag"])){
			$paginaActual = intval($_GET["pag"]);   
			$numProductos = intval($_GET["reg_pag"]);
			$numPaginas = $numProductos * ($paginaActual - 1);
			$limite = $numPaginas.",".$numProductos;
		}
		else{
			$paginaActual = 1;
			$limite = $numPaginas.",". $numProductos;
		}


		//obras
		$obras = new Obras ();

		if (isset($_SESSION["arrayFiltradoIndex"]["selectWhere"])  && $_SESSION["arrayFiltradoIndex"]["selectWhere"] !== ""){
			
			
			$selectWhere = 	$_SESSION["arrayFiltradoIndex"]["selectWhere"];

		}

		//filtrado solamente por where
		if ($selectWhere !== "") {
			//QUE EL BORRADO SEA 0
			$selectWhere .= " AND borrado = 0";

			$filas = $obras->buscarTodos(
				[
					"where" => $selectWhere,
					"limit" => $limite
				]
			);
		}
		else{
			$filas = $obras->buscarTodos(
				[
					"limit" => $limite,
					"where" => " BORRADO = 0"
				]
	
			);
		}

		//opciones del paginador
		$opcPaginador = array(
			"URL" => Sistema::app()->generaURL(array("inicial", "index")),
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


		// $obras = $obras->buscarTodos(["where" => "`borrado` = 0"]); //Se hace consulta con la condicion de que la obra no esté borrada

		
		$this->dibujaVista("index",[
									"filas" => $filas, 
									"paginador" => $opcPaginador,
									 "datos" => $datos,
									 "categoriasArray" => $categoriasArray
									],
							"Biblioteca Grimorios - Inicio");
	}





	/**
	 * Vista para los ejemplares seleccionados en el index principal, se buscará el 
	 * la obra a partir de su ID, se comprobará que el id existe
	 * en caso de introducir un id erróneo, se redirige a página de error 
	 *
	 * @return Void -> no se devuelve nada, imprime una vista
	 */
	public function accionVerObra (){



		//Se comprueba si llega parámetro id
		$id = "";
		if ($_GET){

			

			if (isset($_GET["id"])){

				$id = intval($_GET["id"]);
			}

		}

		if ($id === ""){
			Sistema::app()->paginaError(404, "Se ha accedido con un parámetro distinto al id");
			exit;
		} 

		//Barra de ubicación
		$this->barraUbi = [
			[
				"texto" => "inicio",
				"url" => "/"
			],
			[
				"texto" => "Ver obra",
				"url" => ["inicial", "verObra/id=".$id]
			]
		];



		
		$obra = new Obras ();
		$encuentraObra = $obra->buscarPor(["where" => " `cod_obra` = $id AND `borrado` = 0  "]);
		if ($encuentraObra === true){//Comprobamos que el id existe en la BBDD

			$titulo = $obra->titulo;

			//sacamos los ejemplares disponibles de las obras
			$ejemplaresObra = new Ejemplares ();
			$arrayEjemplares = [];

			//Se buscan los ejemplares con el cod obra especificado
			//Teniendo en cuenta que el ejemplar no esté borrado
			//y esté disponibles, es decir, que el estado del ejemplar sea 0	
			foreach($ejemplaresObra->buscarTodos(["where" => "  `cod_obra` = $id
														AND `borrado_ejemplar`= 0
														AND  `estado_ejemplar` = 0"])
									 as $clave => $valor){

			

					$arrayEjemplares[intval($valor["cod_ejemplar"])] = $valor;


			}


			//Validar formulario
			if ($_POST){ //ESTO ES PARA LA PARTE DE LA RESERVA

				$codEjemplar = "";
				if (isset($_POST["cogeEjemplar"])){

					if (isset($_POST["ejemplar"])){

						$codEjemplar = intval($_POST["ejemplar"]);

					}
				}

				//Comprobamos que el ejemplar seleccionado realmente existe
				$encontrado = false;

				foreach ($arrayEjemplares as $clave => $valor) {

					if (intval($valor["cod_ejemplar"]) === $codEjemplar) {
						$encontrado = true;
						break;
					}
				}


				if ($encontrado === false){//Se ha introducido un codigo erróneo
					$this->dibujaVista("verObra",["obra" => $obra, 
												"arrayEjemplares" => $arrayEjemplares,
												"encontrado" => $encontrado],
									"Biblioteca Grimorios - obra: - $titulo");
					exit();
				}
				else{ //Se ha encontrado el código, lo mandamos a la página de reserva

					Sistema::app()->irAPagina(["prestamos", "realizarPrestamo/?codEj=$codEjemplar"]);
					exit();

				}


				
			}

			$this->dibujaVista("verObra",["obra" => $obra, "arrayEjemplares" => $arrayEjemplares],
				"Biblioteca Grimorios - obra: - $titulo");
		}
		else{

			Sistema::app()->paginaError(505, "No se ha encontrado la obra con el código indicado");
			exit;
		}
	}





	
}
