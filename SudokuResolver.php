<?php
	require "SudokuTable.php";
	
	class SudokuResolver
	{
		private $file;
		
		function __construct($filename) { $this->file = fopen($filename, "r"); }
		
		function __destruct() { fclose($this->file); }
		
		function ReadNextSudoku()
		{
			$sudoku = array();
			$i = 0;
			while (!feof($this->file) && 9 > $i)
				$sudoku[$i++] = fgets($this->file);
			return $sudoku;
		}
		
		function Resolve()
		{
			$output = "";
			while (!feof($this->file))
			{				
				$sudokuName = fgets($this->file);
				if (!feof($this->file))
				{					
					$currentSudoku = $this->ReadNextSudoku();
					$table = new SudokuTable($currentSudoku);
					
					$output .= $sudokuName . "<br/>";
					$output .= $table->ToString();
					
					$output .= "Solution:<br/>";
					$table->Resolve();
					$output .= $table->ToString() . "<br/>";
				}
			}
			return $output;
		}
	}
?>