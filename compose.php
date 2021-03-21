<?php
	// Intento establecer conexión con la base de datos, si no lo consigo no puedo continuar y cierro la página.
	$conn = new mysqli("localhost", "root", "", "mensajes");
	if (!$conn)
	{
		echo "ERROR: No se pudo establecer la conexión.<br/>";
		die( print_r( sqlsrv_errors(), true));
	}
	
	session_start();
	if (!isset($_SESSION["user"]))	// Si el usuario todavía no ha iniciado sesión lo redirijo a la página de inicio de sesión.
		header("Location: " . dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\login.php");
		
	if (isset($_POST["cancel"]))	// Acción que realiza el botón de volver.
		header("Location: " . dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\amail.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Amail - <?php echo $_SESSION["user"][1]; ?></title>
		<link rel="stylesheet" href="amail.css"/>
	</head>
	<body>
		<?php
			$err="";
			if (isset($_POST["submit"]))
				if ($_POST["destinatario"] != -1)										// Si se ha elegido un destinatario continúo
					if (isset($_POST["mensaje"]) && strlen($_POST["mensaje"]) > 0)		// Si se ha escrito algo de texto continúo
						if (strlen($_POST["mensaje"]) <= 300)							// Si el mensaje no supera el tamaño máximo continúo
							if ($conn->query("INSERT INTO mensajes VALUES (NULL, '" . (isset($_POST["mensaje"]) ? $_POST["mensaje"] : "") . "', '" . $_SESSION["user"][0] . "', '" . $_POST["destinatario"] . "', 1)"))
								header("Location: " . dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\amail.php"); // Redirijo al usuario a la página principal
							else
								$err = "* Error interno al enviar el mensaje, por favor vuelva a intentarlo en unos instantes.";
						else
							$err = "* El mensaje que desea enviar es demasiado largo. La longitud máxima permitida es de 300 caracteres.";
					else
						$err = "* No está permitido enviar un mensaje sin contenido.";
				else
					$err = "* No se ha seleccionado ningún destinatario.";
		?>
		<div class="compose container">
			<h1><?php echo $_SESSION["user"][1]; ?> - Redactar mensaje</h1>
			<br/>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<table class="compose_table">
					<tr><td>Destinatario:</td>
					<td>
						<select name="destinatario">
							<!-- Por defecto dejo una opción con el valor -1, de esta forma si se envia el mensaje sin revisar este campo saltará un error en lugar de enviar
							  -- el mensaje al primer usuario, al cual puede que no queramos mandar dicho mensaje.
							  -- Si ya se había seleccionado un usuario anteriormente y saltó un mensaje de error, se seleccionará automáticamente dicho usuario.
							  -->
							<option value="-1">-- Seleccione uno --</option>
							<?php
								$usuarios = $conn->query("SELECT id_usuario AS id, usuario AS nombre FROM usuarios");
								// No compruebo si existen usuarios en la tabla ya que si el usuario ha llegado a esta página es porque está registrado y por lo tanto
								// hay al menos 1 usuario.
								while ($usuario = $usuarios->fetch_assoc())
								{
								?>
									<option value="<?php echo $usuario["id"]; ?>" <?php echo isset($_POST["destinatario"]) && $_POST["destinatario"] == $usuario["id"] ? "selected" : ""?>>
										<?php echo $usuario["nombre"]; ?>
									</option>
								<?php
								}
							?>
						</select>
					</td>
					<tr> <!-- En el caso de que el usuario comenta algún error se le rellenará este campo con el mensaje que ya había escrito. -->
						<td colspan=2><textarea name="mensaje"><?php echo isset($_POST["mensaje"]) ? $_POST["mensaje"] : ""; ?></textarea></td>
					</tr>
				</table>				
				<!-- En caso de que se produzca algún error se le mostrará por pantalla al usuario. -->
				<span><?php echo !empty($err) ? $err . "<br/>" : ""; ?></span>
				<table class="buttons">
					<tr><td>
						<div class="blue custom_button">
							Enviar
							<input type="submit" name="submit" class="button"/>
						</div>
					</td>
					<td>					
						<!-- Botón que redirecciona al usuario hacia la página principal. -->
						<div class="white custom_button">
							Volver
							<input type="submit" name="cancel" class="button"/>
						</div>
					</td></tr>
				</table>
			</form>
		</div>
		<?php
			mysqli_close($conn);
		?>
  </body>
</html>