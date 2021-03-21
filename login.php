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
		<title>Amail - Iniciar sesión</title>
		<link rel="stylesheet" href="amail.css"/>
	</head>
	<body>
		<?php 
			$err = false;
			// Cuando el usuario pulsa el botón de iniciar sesión se le realizan una serie de comprobaciones a sus credenciales para comprobar que sean correctas.
			// Cualquier error es escrito en la variable $err para posteriormente mostrarla por pantalla.
			if (isset($_POST["login"]))
				if (!preg_match("/[^A-Z0-9]/i", $_POST["user"]) && 
					!preg_match("/\s/", $_POST["pass"]) && 
					preg_match("/^.{6,30}$/", $_POST["pass"])) //Si las credenciales son las permitidas continúo
				{
					$result = $conn->query("SELECT id_usuario, usuario FROM usuarios WHERE usuario = '" . $_POST["user"] . "' AND pass = '" . $_POST["pass"] . "'");
					if ($result->num_rows > 0)
					{
						$row = $result->fetch_assoc();
						$_SESSION["user"] = array($row["id_usuario"], $row["usuario"]);
						header("Location: " . dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\amail.php");
					}
					else
						$err = true;
				}
				else
					$err = true;
			mysqli_close($conn);
		?>
		<div class="credential container">
			<h1>Iniciar sesión</h1>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<p>* Usuario</p>
				<input type="text" name="user" required/>
				<p>* Contraseña</p>
				<input type="password" name="pass" required/><br/><br/>
				<!-- En caso de que alguna de las credenciales no sea correcta se mostrará por pantalla el mensaje de error. -->
				<span><?php echo $err ? "* Usuario o contraseña incorrectos.<br/><br/>" : "" ?></span>
				<!-- Botón que envia las credenciales del usuario para comprobar que sean correctas e iniciar en ese caso. -->
				<div class="blue custom_button">
					Iniciar sesión
					<input type="submit" name="login" class="button"/>
				</div>
			</form>
			<br/>
			<hr/>
			<h3>¿Todavía no tienes una cuenta?</h3>
			<!-- Botón que redirige al usuario a la página de registro. -->
			<div class="white custom_button">
				Regístrate ahora
				<button onclick="window.location.href = './signin.php'" class="button"></button>
			</div>
		</div>
  </body>
</html>