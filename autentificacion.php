<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Usuario y contraseña</title>
		<link rel="stylesheet" href="auth.css">
	</head>
	<body>
		<div>
			<table>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
					<tr>
						<td colspan=2><h1>Inice sesión:</h1></td>
					</tr><tr>
						<td>Usuario:</td><td><input type="text" name="user"/></td>
					</tr>
					<tr>
						<td>Contraseña:</td><td><input type="password" name="password"/></td>
					</tr>
					<tr>
						<td colspan=2><input type="submit" id="inputbutton" value="Iniciar sesión" name="submit"/></td>
					</tr>
				</form>
			</table>
		</div>
		<?php
			if (isset($_POST["submit"]))
			{
				$_SESSION["user"] = $_POST["user"];
				$_SESSION["pass"] = $_POST["password"];
				header("Location: " . dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\restringida.php") ;				
			}
		?>
	</body>
</html>