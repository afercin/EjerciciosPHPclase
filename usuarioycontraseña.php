<!DOCTYPE html>
<html>
	<head>
		<title>Usuario y contraseña</title>
	</head>
	<body>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
			Usuario: <input type="text" name="user"/><br/>
			Contraseña: <input type="password" name="password"/><br/>
			<input type="submit" value="Enviar" name="submit"/>
		</form>
		
		<?php
			if (isset($_POST["submit"]))
			{
				$user = $_POST["user"];
				$pass = $_POST["password"];
				if (isset($_COOKIE[$user]))
					if ($_COOKIE[$user] == $pass)
						echo "Bienvendido " . $user;
					else
						echo "Contraseña erroena";
				else
				{					
					echo "Registrado nuevo usuario " . $user;
					setcookie($user, $pass, time() + 86400 * 30); 
				}				
			}
		?>
	</body>
</html>