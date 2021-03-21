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
				function ParseString($str, $offset)
				{
					$strArray = str_split($str);
					$formatedStr = "";			 
					$position = $offset;
					
					while ($position < strlen($str) && $strArray[$position] != " ")
						$formatedStr = $formatedStr . $strArray[$position++];
					
					return floatval($formatedStr);
				}
				
				function IsCorrectResult()
				{
					$isCorrect = false;
					
					if (isset($_POST["result"]) && strlen($_POST["result"]) > 0)
					{
						$operand1 = ParseString($_POST["operand1"], 0);
						$operand2 = ParseString($_POST["operand2"], 2); 
						$result;
						
						switch (str_split($_POST["operand2"])[0])
						{
							case "+": $result = $operand1 + $operand2; break;
							case "-": $result = $operand1 - $operand2; break;
							case "x": $result = $operand1 * $operand2; break;
							case "/": $result = $operand1 / $operand2; break;
						}
						
						$isCorrect = round($result, 2) == round(ParseString($_POST["result"], 0), 2);
					}
					
					return $isCorrect;
				}
				
				$name = isset($_POST["name"]) && strlen($_POST["name"]) > 0 ? $_POST["name"] : "Anonymous";
				$age = isset($_POST["age"]) && strlen($_POST["age"]) > 0 ? $_POST["age"] : 12;
			?>
			
			<h2>Wellcome <?php echo $name; ?> to MathLearning!</h2>
			<h3>Math operations for users bellow <?php echo $age; ?> years</h3>
			
			<?php
				$report = isset($_POST["report"]) ? $_POST["report"] : "";
				
				if (isset($_POST["viewreport"]))
					if (file_exists("operations.txt"))
					{
						$file = fopen("operations.txt", "r");
						$reportArray = empty($report) ? array() : explode(";", $report);
			?>
			
			<table class="ReportTable">
			<?php
						$r = 1;
						while (!feof($file))
						{
							$value1 = fgets($file);
							$value2 = fgets($file);
							if (!feof($file))
								echo "<tr>" . 
										 "<th>" . ($r++) . ") " . "</th>" .
										 "<td>" . $value1 . "</td>" .
										 "<td>" . $value2 . "</td>" .
									 "</tr>";
						}
						
						for ($i = 0; $i < count($reportArray) - 1; $i+=2)
							echo "<tr>" . 
									 "<th>" . ($i / 2 + $r) . ") " . "</th>" .
									 "<td>" . $reportArray[$i] . "</td>" .
									 "<td>" . $reportArray[$i + 1] . "</td>" .
								 "</tr>";
			?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
					<tr> 
						<td colspan=3> <input type="submit" name="submit" value="Try again!"/> </td> 
					</tr>
					<input type="hidden" name="name" value="<?php echo $name; ?>"/>
					<input type="hidden" name="age" value="<?php echo $age; ?>"/>
				</form>
			</table>
			<?php
						fclose($file);
					}
					else
						echo "El usuario ha borrado el fichero.";
				else
				{					
					$questionCount;
					if (isset($_POST["Text"]))
					{
						$questionCount = ParseString($_POST["Text"], 15);
						$report = $report . $_POST["operand1"] . " " . $_POST["operand2"] . " = " 
										  . (isset($_POST["result"]) && strlen($_POST["result"]) > 0 ? $_POST["result"] : "NaN") . ";"
										  . (IsCorrectResult() ? "Correct!" : "Wrong!") . ";";
					}
					else
					{
						$questionCount = 0;						
						fclose(fopen("operations.txt", "w"));
					}
					
					if ($questionCount > 0 && $questionCount % 3 == 0)
					{
						$file = fopen("operations.txt", "a");	
						foreach (explode(";", $report) as $value)
							fwrite($file, $value . PHP_EOL);
						$report = "";
						fclose($file);
					}
					
					$operatorSymbol = array("+", "-", "x", "/");
					$operator = $operatorSymbol[rand(0, 3)];
					$operand1 = rand(0, 99);
					$operand2 = rand(0, 99);
					
					while ($operator == '/' && $operand2 == 0)
						$operand2 = rand(0, 99);
			?>
			
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
				<table>
					<tr>
						<td> <input type="text" name="Text" class="inputLabel" value="Math operation <?php echo ($questionCount + 1); ?> of 10:" readonly/> </td> 
					</tr>
					<tr>
						<td> <input type="text" name="operand1" class="inputLabel operationLabel" value="<?php echo $operand1; ?>" readonly/> </td> 
					</tr>
					<tr>
						<td> <input type="text" name="operand2" class="inputLabel operationLabel operand2" value="<?php echo $operator . " " . $operand2; ?>" readonly/> </td> 
					</tr>
					<tr>
						<td> Your answer: <input type="number" name="result" class="operationLabel" step=".001" max="9801" autofocus/> </td> 
					</tr>
					<tr> 
						<td> <input type="submit" name="submit" class="SubmitButton" value="Send"/> </td> 
					</tr>
					<tr> 
						<td> <input type="submit" name="viewreport" class="SubmitButton" value="datos"/> </td> 
					</tr>
				</table> 				
				<input type="hidden" name="report" value="<?php echo $report; ?>"/>
				<input type="hidden" name="name" value="<?php echo $name; ?>"/>
				<input type="hidden" name="age" value="<?php echo $age; ?>"/>
			</form>
			
			<?php
				}
			?>
		</div>
	</body>
</html>