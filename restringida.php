<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Página restringida</title>
		<link rel="stylesheet" href="auth.css">
	</head>
	<body>
		<?php
			$msg;
			$buttonText;
			$autorizado;
			if (isset($_POST["cerrar_sesion"]))
			{
				session_destroy();
				header("Location: " . dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\autentificacion.php") ;				
			}
			if (isset($_SESSION["user"]) && isset($_SESSION["pass"]))
			{
				$user = "adrian";
				$pass = "12345";
				if ($_SESSION["user"] == $user && $_SESSION["pass"] == $pass)
				{
					$msg = "Usuario autorizado.";
					$buttonText = "Cerrar sesión";
					$autorizado = true;
				}
				else
				{
					$msg = "Datos de sesión erroneos o usuario no autorizado.";
					$buttonText = "Volver a intentar";
					$autorizado = false;
				}			
			}
			else
			{
				$msg = "Debe iniciar sesión antes de entrar a esta zona restringida.";
				$buttonText = "Iniciar sesión";
				$autorizado = false;
			}
		?>
		<div>
			<h1><?php echo $msg; ?></h1>
			<img src="<?php echo $autorizado ? "Images/aproved.png" : "Images/denied.png"; ?>"/>
			<br/>
			<br/>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
				<td colspan=2><input type="submit" id="inputbutton" value="<?php echo $buttonText; ?>" name="cerrar_sesion"/></td>
			</form>
		</div>
	</body>
</html>