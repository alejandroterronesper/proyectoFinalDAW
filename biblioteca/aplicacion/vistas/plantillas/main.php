<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo $titulo; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" type="text/css" href="/estilos/principal.css" />

	<link rel="icon" type="image/jpg" href="/imagenes/favicon.jpg" />
	<?php
	if (isset($this->textoHead)) {
		$this->textoHead .= " " . CMenu::requisitos() . PHP_EOL;
		echo $this->textoHead;
	}


	?>
</head>

<body>

	<header>

		<div class="content">

			<div class="menu container">
				<a href="/index.php"> <img alt="logo" src="/imagenes/logo.jpg" class="logo"> </a>

				<input type="checkbox" id="menu" />

				<label for="menu">
					<img src="/imagenes/menu.png" class="menu-icono" alt="">
				</label>

				<nav class="navbar">
					
					<!-- <ul> -->
						<?php
							echo CHTML::dibujaEtiqueta("ul", [], null, false).PHP_EOL;

								//Preguntamos si hay usuario logueado
								if (Sistema::app()->Acceso()->hayUsuario() === true){

									echo CHTML::dibujaEtiqueta("li", [], null, false).PHP_EOL;
										echo CHTML::link(Sistema::app()->Acceso()->getNick(), ["inicial"]).PHP_EOL; 
									echo CHTML::dibujaEtiquetaCierre("li").PHP_EOL;

									echo CHTML::dibujaEtiqueta("li", [], null, false).PHP_EOL;
										echo CHTML::link("Cerrar sesión", ["login", "CerrarSesion"]).PHP_EOL; 
									echo CHTML::dibujaEtiquetaCierre("li").PHP_EOL;

		
									//Ahora se pregunta por los permisos del usuario actual

									//Usuario normal
									if (Sistema::app()->Acceso()->puedePermiso(9) && Sistema::app()->Acceso()->puedePermiso(10)){
											$nick = Sistema::app()->Acceso()->getNick();
											$usuario = new Usuarios ();  //Buscamos el id del usuario por el nick

											$usuario->buscarPor(["where" => "nick =  '$nick'  "]);
											$cod = $usuario->cod_usuario;

											echo CHTML::dibujaEtiqueta("li", [], null, false).PHP_EOL;
							 					echo CHTML::link("Préstamos", ["prestamos", "verPrestamos/?id=$cod"], []).PHP_EOL;
											echo CHTML::dibujaEtiquetaCierre("li").PHP_EOL;

									}

									//Super admin
									if (Sistema::app()->Acceso()->puedePermiso(2)){
										echo CHTML::dibujaEtiqueta("li", [], null, false).PHP_EOL;
						 					echo CHTML::link("Control de usuarios", ["usuarios", "indexUsuarios"], []).PHP_EOL;
										echo CHTML::dibujaEtiquetaCierre("li").PHP_EOL;
									}


									//Bibliotecario
									if (Sistema::app()->Acceso()->puedePermiso(1) && Sistema::app()->Acceso()->puedePermiso(9)){

										echo CHTML::dibujaEtiqueta("li", ["class" => "dropdown"], null, false).PHP_EOL;
											echo CHTML::link("Operaciones", []).PHP_EOL; 

											echo CHTML::dibujaEtiqueta("ul", ["class" => "submenu"], null, false).PHP_EOL;

													echo CHTML::dibujaEtiqueta("li", [], null, false).PHP_EOL;
															echo CHTML::link("Control de obras", ["obras", "indexObras"], []).PHP_EOL;
													echo CHTML::dibujaEtiquetaCierre("li").PHP_EOL;


													echo CHTML::dibujaEtiqueta("li", [], null, false).PHP_EOL;
														echo CHTML::link("Control de ejemplares", ["ejemplares", "indexEjemplares"], []).PHP_EOL;
													echo CHTML::dibujaEtiquetaCierre("li").PHP_EOL;


													echo CHTML::dibujaEtiqueta("li", [], null, false).PHP_EOL;
														echo CHTML::link("Control de préstamos", ["prestamos", "indexPrestamos"], []).PHP_EOL;
													echo CHTML::dibujaEtiquetaCierre("li").PHP_EOL;


											echo CHTML::dibujaEtiquetaCierre("ul").PHP_EOL;

										echo CHTML::dibujaEtiquetaCierre("li").PHP_EOL;



									}

								}
								else{

									echo CHTML::dibujaEtiqueta("li", [], null, false).PHP_EOL;
										echo CHTML::link("Iniciar sesión", ["login", "InicioSesion"]).PHP_EOL; 
									echo CHTML::dibujaEtiquetaCierre("li").PHP_EOL;

								}

							echo CHTML::dibujaEtiquetaCierre("ul").PHP_EOL;
					

						?>
						
						
					<!-- </ul> -->
				</nav>

			</div>

		</div>

	</header><!-- #header -->

	<!-- barra ubicacion -->

	<div id="barraUbicacion">

		<?php
		$longitud = count($this->barraUbi) - 1;
		foreach ($this->barraUbi as $clave => $valor) {

			if ($clave === $longitud) {
				echo CHTML::link($valor["texto"], $valor["url"]) . PHP_EOL;
			} else {
				// echo CHTML::link($valor["texto"], $valor["url"]) . "&nbsp;&nbsp;&nbsp;//&nbsp;&nbsp;&nbsp;" . PHP_EOL;
				echo CHTML::link($valor["texto"], $valor["url"]). 
				CHTML::dibujaEtiqueta("span", ["class" => "arrow"], "&nbsp;", true).PHP_EOL;
			}
		}
		?>

	</div>


	<main>

		<article> <!-- Aqui metemos el contenido de los productos -->
			<?php echo $contenido; ?>
		</article>
		
	</main>

	<footer>
		<h4>Alejandro Terrones Pérez</h4>
		<h4>2º DAW</h4>
		<div>
            <img src="/imagenes/iconos/instagram.png" alt="Instagram">
            <img src="/imagenes/iconos/youtube.png" alt="youtube">
            <img src="/imagenes/iconos/x.png" alt="Twitter">
        </div>
	</footer><!-- #footer -->

</body>

</html>