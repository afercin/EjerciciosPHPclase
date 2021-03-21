<!DOCTYPE html>
<html>
	<head>
		<title>Sudoku</title>
	</head>
	<body>
		<?php
			include "SudokuResolver.php";
			
			$sudokus = new SudokuResolver("D:\\Sudokus.txt");
			echo $sudokus->Resolve();
		?>
	</body>
</html>