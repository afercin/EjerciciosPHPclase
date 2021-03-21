<!DOCTYPE html>
<html>
	<head>
		<title>Adrián Fernández Cintado</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="MathLearning.css">
	</head>
	<body>
		<div>
			<?php
				/*  Función que devuelve el valor en decimal de un número situado dentro de un string.
				 *	$str: es el string que contiene el número.
				 *	$offset: desplazamiento dentro del string (por si el número a extraer no está al principio de este).
				 */
				function ParseString($str, $offset)
				{
					$strArray = str_split($str); // Array que contiene todos los caracteres de mi string.
					$formatedStr = "";			 // String que contendrá el número extraido del array.
					$position = $offset;		 // Posición dentro del array.
					
					// Se repite mientras no se llegue al final del array y no se llegue a un espacio en blanco.
					while ($position < strlen($str) && $strArray[$position] != " ")
						$formatedStr = $formatedStr . $strArray[$position++]; // Concateno el caracter actual a mi string de salida y postincremento la variable de posición.
					
					return floatval($formatedStr); // Devuelvo el número en coma flotante utilizando la función floatval().
				}
				
				/*  Función que comprueba si la respuesta introducida por el usuario es correcta.
				 *	No recibe argumentos.
				 */
				function IsCorrectResult()
				{
					$isCorrect = false; // Mientras no se demuestre lo contrario mi usuario se ha equivocado en el resultado.
					
					// Si el usuario no ha escrito una respuesta no tengo porqué comprobar si es una respuesta correcta.
					if (isset($_POST["result"]) && strlen($_POST["result"]) > 0)
					{
						$operand1 = ParseString($_POST["operand1"], 0);	// Extraigo y transformo el string con el primer operando de la operación anterior.
						$operand2 = ParseString($_POST["operand2"], 2); // Extraigo y transformo el string con el segundo operando de la operación anterior. 
																		// Pongo offset = 2 ya que este string contiene a su vez el tipo de operación
						$result; // Variable que almacena el resultado correcto de la operación matemática.
						
						switch (str_split($_POST["operand2"])[0]) // El primer carácter del segundo operando es el tipo de operación, por ello lo extraigo y realizo la operación correspondiente.
						{
							case "+": $result = $operand1 + $operand2; break;
							case "-": $result = $operand1 - $operand2; break;
							case "x": $result = $operand1 * $operand2; break;
							case "/": $result = $operand1 / $operand2; break;
						}
						// Redondeo a dos decimales para no tener en cuenta un posible fallo en el redondeo del tercer decimal.
						$isCorrect = round($result, 2) == round(ParseString($_POST["result"], 0), 2); // Compruebo si el resultado que ha introducido el usuario y el correcto valen lo mismo. Almaceno el valor booleano en $isCorrect.
						
						/* -----Versión anterior-------
						// Redondeo el resultado a 3 decimales ya que es el máximo número de decimales le permito al usuario poner en su respuesta.
						$isCorrect = abs(round($result, 3) - ParseString($_POST["result"], 0)) <= 0.01; // Compruebo si el resultado que ha introducido el usuario y el correcto valen lo mismo (su resta debe de ser 0) 
																										// pero permito que el usuario cometa un error de 0,01 en el redondeo de los decimales.
						*/
					}
					
					return $isCorrect; // Devuelvo un valor de tipo bool que dice si es finalmente correcta la respuesta.
				}
				
				$name = isset($_POST["name"]) && strlen($_POST["name"]) > 0 ? $_POST["name"] : "Anonymous"; // Extraigo el nombre de mi usuario, si no ha introducido ninguno pasa a llamarse Anonymous.
				$age = isset($_POST["age"]) && strlen($_POST["age"]) > 0 ? $_POST["age"] : 12;				// Extraigo la edad de mi usuario, si no ha introducido ninguna pasa a tener 12 años.
			?>
			
			<!-- Saco por pantalla el nombre y la edad de mi usuario en un mensaje de bienvenida. -->
			<h2>Wellcome <?php echo $name; ?> to MathLearning!</h2>
			<h3>Math operations for users bellow <?php echo $age; ?> years</h3>
			
			<?php
				$report = isset($_POST["report"]) ? $_POST["report"] : ""; // Extraigo un string que contiene cada operación realizada con la respuesta del usuario y si están contestadas correctamente, 
																		   // en la primera ejecución se inicializa como una cadena vacía.
				$questionCount; // Variable que contendrá el número de operaciones que ha realizado mi usuario.
				
				if (isset($_POST["Text"])) // Si no es la primera ejecución, el enunciado que dice el número de la operación actual tendrá un valor.
				{
					$questionCount = ParseString($_POST["Text"], 15); // Extraigo y transforno el número de operación del enunciado.
					// Concateno en la operación que se realizó en la ejecución anterior con el resultado que introdujo el usuario y lo separo por ";" de si es correcto el resultado introducido.
					$report = $report . $_POST["operand1"] . " " . $_POST["operand2"] . " = " 
									  . (isset($_POST["result"]) && strlen($_POST["result"]) > 0 ? $_POST["result"] : "NaN") . ";"  // Si no ha escrito un valor en lugar de dejarlo en blanco escribo que no es un número (NaN).
									  . (IsCorrectResult() ? "Correct!" : "Wrong!") . ";"; // Concateno si el resultado es correcto o no y pongo ";" al final para separarlo del resultado de la siguiente ejecución.
				}
				else
					$questionCount = 0; // Si es la primera ejecución inicializo a 0 el contador.
				
				if ($questionCount < 10) // Si el usuario no ha realizado 10 operaciones le preparo otra.
				{
					$operatorSymbol = array("+", "-", "x", "/"); // Array que contiene los símbolos de las diferentes operaciones matemáticas disponibles.
					$operator = $operatorSymbol[rand(0, 3)]; 	 // Extraigo un operador de forma aleatoria. 
					$operand1 = rand(0, 99);	// Genero dos operandos de 2 cifras como máximo de forma aleatoria
					$operand2 = rand(0, 99);
					
					while ($operator == '/' && $operand2 == 0) // Si es una división y el divisor es 0 debo de buscar otro valor ya que no es posible dividir entre cero.
						$operand2 = rand(0, 99);
			?>
			
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
				<table>
					<tr> <!-- Texto con el número de la operación a realizar. -->
						<td> <input type="text" name="Text" class="inputLabel" value="Math operation <?php echo ($questionCount + 1); ?> of 10:" readonly/> </td> 
					</tr>
					<tr> <!-- Texto con el primer operando. -->
						<td> <input type="text" name="operand1" class="inputLabel operationLabel" value="<?php echo $operand1; ?>" readonly/> </td> 
					</tr>
					<tr> <!-- Texto con el tipo de operación y el segundo operando. -->
						<td> <input type="text" name="operand2" class="inputLabel operationLabel operand2" value="<?php echo $operator . " " . $operand2; ?>" readonly/> </td> 
					</tr>
					<tr> <!-- Cuadro de texto que contendrá la respuesta ofrecida por el usuario. -->
						<td> Your answer: <input type="number" name="result" class="operationLabel" step=".001" max="9801" autofocus/> </td> 
					</tr>
					<tr> 
						<td> <input type="submit" name="submit" class="SubmitButton" value="Send"/> </td> 
					</tr>
				</table> 				
				<input type="hidden" name="report" value="<?php echo $report; ?>"/> <!-- Cuadro de texto invisible que contiene las respuestas corregidas del usuario. -->
				<input type="hidden" name="name" value="<?php echo $name; ?>"/> 	<!-- Cuadro de texto invisible que contiene el nombre del usuario. -->
				<input type="hidden" name="age" value="<?php echo $age; ?>"/>		<!-- Cuadro de texto invisible que contiene la edad del usuario. -->
			</form>
			
			<?php
				}
				else // Si mi usuario ya ha realizado 10 operaciones le muestro una tabla con los resultados.
				{
					$reportArray = explode(";", $report); // Tal y como se vió arriba, este string separa cada elemento por ";", por ello utilizo explode() para extraer cada uno de los valores en un array.
			?>
			
			<table class="ReportTable">
			<?php
					// Salgo del bucle cuando queda un elemento restante ya que al terminar mi string $report en ";" explode() me genera un último valor vacío que debo descartar.
					for($i = 0; $i < count($reportArray) - 1; $i+=2) // Recorro el array de 2 en 2 ya que operación y corrección ocupan dos lugares en el array.
						echo "<tr>" . 
								 "<th>" . ($i / 2 + 1) . ") " . "</th>" . // Para dar más información también digo el número de la operación actual.
								 "<td>" . $reportArray[$i] . "</td>" .
								 "<td>" . $reportArray[$i + 1] . "</td>" .
							 "</tr>";
			?>
				<!-- Por último creo un formulario que va a contener un botón para repetir la prueba. Esto funciona debido a que mando la misma información que mandaría el primer HTML 
				     y mi código lo toma como si fuera la primera ejecución. -->
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
					<tr> 
						<td colspan=3> <input type="submit" name="submit" value="Try again!"/> </td> 
					</tr>
					<input type="hidden" name="name" value="<?php echo $name; ?>"/>
					<input type="hidden" name="age" value="<?php echo $age; ?>"/>
				</form>
			</table>
			<?php		
				}
			?>
		</div>
	</body>
</html>