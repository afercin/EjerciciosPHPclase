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
		
	if (isset($_POST["logout"]))	// Acción que realiza el botón de cerrar sesión.
	{
		session_destroy();
		header("Location: " . dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\login.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Amail - <?php echo $_SESSION["user"][1]; ?></title>
		<link rel="stylesheet" href="amail.css"/>
	</head>
	<body>
		<?php
			/* Función que muestra todos los mensajes del usuario que ha iniciado sesión dentro de una tabla.
			 * $conn: Parámetro con la referencia a la conexión con la base de datos, la cual hace falta para obtener los mensajes.
			 * $recibidos: Parámetro booleano que me permite decidir si deseo mostrar los mensajes recibidos o los enviados.
			 *			   Si tiene el valor `true´ muestra los recibidos, y si tiene el valor `false´ muestra los enviados.
			 */
			function mostrar_mensajes($conn, $recibidos)
			{
				if ($recibidos)
					$SQL = "SELECT usuario, texto FROM mensajes, usuarios WHERE id_remite = id_usuario AND id_destino = " . $_SESSION["user"][0];
				else
					$SQL = "SELECT usuario, texto FROM mensajes, usuarios WHERE id_destino = id_usuario AND id_remite = " . $_SESSION["user"][0];
				// Obtengo los mensajes correspondientes del usuario:
				$mensajes = $conn->query($SQL);
				// Creo la tabla y la relleno con los mensajes del usuario:
				?>
				<table class="inbox">
					<tr><th><?php echo $recibidos ? "Remitente" : "Destinatario"; ?></th><th>Contenido</th></tr>
				<?php
					if ($mensajes) // Es posible que el usuario no tenga mensajes, en ese caso no se mostrarían las filas con <td>.
						while ($mensaje = $mensajes->fetch_assoc())
						{
							?>
							<!-- Para protegerme de posibles inyecciones de js hago uso de htmlspecialchars. No es necesario en el usuario
							  -- ya que por defecto no se le permite usar caracteres especiales. -->
							<tr>
								<td> <?php echo $mensaje["usuario"]; ?></td>
								<td> <?php echo htmlspecialchars($mensaje["texto"]); ?></td>
							</tr>
							<?php
						}
				?>
				</table>
				<?php
			}
		?>
		<div class="mail container">
			<table class="top">
				<tr><td>
					<h1>Bienvenido, <?php echo $_SESSION["user"][1]; ?></h1>
				</td>
				<td>
					<!-- Botón que permite cerrar la sesión actual. -->
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="logout">
						<div class="blue custom_button">
							Cerrar sesión
							<input type="submit" name="logout" class="button"/>
						</div>
					</form>
				</td></tr>
			</table>
			<!-- Botón que redirecciona al usuario hacia otra página donde pueda redactar su mensaje. -->
			<form action="<?php echo dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\compose.php"; ?>" method="post" class="compose_button">
				<div class="white custom_button">
					Redactar Mensaje
					<input type="submit" name="compose" class="button"/>
				</div>
			</form>
			<!-- Tablas con los mensajes en la bandeja de entrada y salida del usuario. -->
			<br/>
			<div class="black custom_button">Mensajes recibidos</div>
			<br/>
			<?php mostrar_mensajes($conn, true); ?>
			<br/>
			<div class="black custom_button">Mensajes enviados</div>
			<br/>
			<?php mostrar_mensajes($conn, false); ?>
		</div>
		<?php
			mysqli_close($conn);
		?>
  </body>
</html>