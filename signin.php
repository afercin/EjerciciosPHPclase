<?php
	// Intento establecer conexión con la base de datos, si no lo consigo no puedo continuar y cierro la página.
	$conn = new mysqli("localhost", "root", "", "mensajes");
	if (!$conn)
	{
		echo "ERROR: No se pudo establecer la conexión.<br/>";
		die( print_r( sqlsrv_errors(), true));
	}
	
	session_start();
	if (isset($_SESSION["user"]))	// Si el usuario ya había iniciado sesión se le redirige a la página principal
		header("Location: " . dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\amail.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Amail - Registro</title>
		<link rel="stylesheet" href="amail.css"/>
	</head>
	<body>
		<?php 
			$err = "";
			// Cuando el usuario pulsa el botón de registrarse se le realizan una serie de comprobaciones a sus credenciales para comprobar que sean correctas.
			// Cualquier error es escrito en la variable $err para posteriormente mostrarla por pantalla.
			if (isset($_POST["signin"]))
				if (!preg_match("/[^A-Z0-9]/i", $_POST["user"])) 														// Si el usuario no contiene caracteres especiales continúo.	
					if ($conn->query("SELECT * FROM usuarios WHERE usuario = '" . $_POST["user"] . "'")->num_rows == 0)	// Si no existe un usuario con ese nombre continúo	
						if (!preg_match("/\s/", $_POST["pass"]))						 								// Si la contraseña no contiene espacios continúo
							if (preg_match("/^.{6,30}$/", $_POST["pass"])) 												// Si la contraseña tiene de longitud entre 6 y 30 continúo
								if ($conn->query("INSERT INTO usuarios VALUES (NULL, '" . $_POST["user"] . "', '" . $_POST["pass"] . "')"))
								{
									// Si el usuario logra registrarse y se insertan los datos en la tabla de usuarios se le inicia sesión y se le redirige a la página principal.
									$_SESSION["user"] = array($conn->query("SELECT id_usuario FROM usuarios WHERE usuario = '" . $_POST["user"] . "'")->fetch_assoc()["id_usuario"], 
															  $_POST["user"]);
									header("Location: " . dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\amail.php");
								}
								else
									$err = "Ha ocurrido un error en el proceso de registro, por favor intentelo de nuevo en unos instantes.";
							else
								$err = "* La contraseña debe de tener entre 6 y 30 caracteres sin incluir espacios.";
						else
							$err = "* La contraseña no puede contener espacios.";
					else
						$err = "* El nombre de usuario ya existe, pruebe con otro.";
				else
					$err = "* El nombre de usuario no puede contener espacios ni caracteres especiales.";
			mysqli_close($conn);
		?>
		<div class="credential container">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<h1>Registro</h1>
				<p>* Usuario</p>
				<input type="text" name="user" required/>
				<p>* Contraseña</p>
				<input type="password" name="pass" required/>
				<br/><br/>
				<!-- En caso de que alguna de las credenciales no sea válida se mostrará el mensaje de error correspondiente. -->
				<span><?php echo !empty($err) ? $err . "<br/><br/>" : ""; ?></span>
				<!-- Botón que envia las credenciales del usuario para comprobar que sean correctas y agregarlo en ese caso. -->
				<div class="blue custom_button">
					Registrarse
					<input type="submit" name="signin" class="button"/>
				</div>
			</form>
			<br/>
			<hr/>
			<h3>Ya tengo una cuenta</h3>
			<!-- Botón que redirige al usuario a la página de iniciar sesión. -->
			<div class="white custom_button">
				Iniciar sesión
				<button onclick="window.location.href = './login.php'" class="button"></button>
			</div>
		</div>
  </body>
</html>