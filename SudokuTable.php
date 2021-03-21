<?php
	require "SudokuCell.php";
	require_once "Utils.php";
	
	class SudokuTable
	{
		private UnorderList $remainingCells;
		private $cells;
		
		public function __construct($sudoku) 
		{
			$j = 0;
			foreach ($sudoku as $row)
				$this->cells[$j++] = $this->GetCellArray($row);
				
			$this->remainingCells = new UnorderList();
			for ($y = 0; $y < 9; $y++) for ($x = 0; $x < 9; $x++)
				$this->remainingCells->Add(new Point($x, $y));
		}
		
		private function GetCellArray($row)
		{
			$array = array();
			$i = 0;
			foreach(str_split($row) as $number)
				$array[$i++] = new SudokuCell($number);
			return $array;
		}
		
		public function Resolve()
		{
			for ($y = 0; $y < 9; $y++) for ($x = 0; $x < 9; $x++)
				if ($this->cells[$y][$x]->Value != 0)
					$this->Recalculate($x, $y);
			return $this->Resolve_Rec();
		}
		
		private function Resolve_Rec()
        {
            $target = new Point(-1, -1);
            $max_posibilidades = 10;
            // Obtengo la casilla con menos valores posibles y le pongo el valor a los que solamente le quedan una posibilidad, además de recalcular las casillas afectadas
            foreach ($this->remainingCells->GetArray() as $p)
            {
                if (count($this->cells[$p->y][$p->x]->Posibilities) == 1 && $this->cells[$p->y][$p->x]->Value == 0)
                {
                    $this->cells[$p->y][$p->x]->Value = $this->cells[$p->y][$p->x]->Posibilities->First();
                    $this->Recalculate($p->x, $p->y);
                }
                elseif (count($this->cells[$p->y][$p->x]->Posibilities) < $max_posibilidades)
                {
                    $max_posibilidades = count($this->cells[$p->y][$p->x]->Posibilities);
                    $target = $p;
                }
            }
            if ($target->x != -1)
            {
				//Realizo backup de la instancia actual
                $remainingCells_BackUp = new UnorderList($this->remainingCells->GetArray());
                $cells_BackUp = array();
				for ($y = 0; $y < 9; $y++)
				{
					$cells_BackUp[$y] = array();
					for ($x = 0; $x < 9; $x++)
						$cells_BackUp[$y][$x] = new SudokuCell($this->cells[$y][$x]->Value, $this->cells[$y][$x]->Posibilities->GetArray());
				}
                // Pruebo 1 a 1 los posibles valores de la casilla
				foreach ($this->cells[$target->y][$target->x]->Posibilities->GetArray() as $value)
				{
					$this->cells[$target->y][$target->x]->Value = $value;
					$this->Recalculate($target->x, $target->y);
					$this->Resolve_Rec(); // Repito recursivamente
					if (count($this->remainingCells) > 0) // Si no se ha llegado a una solución devuelve el sistema al estado anterior
                    {
                        $this->remainingCells = new UnorderList($remainingCells_BackUp->GetArray());
						for ($y = 0; $y < 9; $y++) for ($x = 0; $x < 9; $x++)
							$this->cells[$y][$x] = new SudokuCell($cells_BackUp[$y][$x]->Value, $cells_BackUp[$y][$x]->Posibilities->GetArray());
                    }
                    else
                        return true; // He llegado a una solución, por lo que salgo del bucle
                }
            }
            return false;
        }
		
		private function Recalculate($x, $y)
		{
			$this->remainingCells->Remove(new Point($x, $y));
			$this->RecalculateRow($x, $y);
			$this->RecalculateCol($x, $y);
			$this->RecalculateSquare($x, $y);
		}
		
		private function RecalculateRow($x, $y)
		{
			for ($i = 0; $i < 9; $i++)
                if ($i != $x && $this->cells[$y][$i]->Value == 0)
                    $this->cells[$y][$i]->Posibilities->Remove($this->cells[$y][$x]->Value);
		}
		
		private function RecalculateCol($x, $y)
		{			
			for ($j = 0; $j < 9; $j++)
                if ($j != $y && $this->cells[$j][$x]->Value == 0)
                    $this->cells[$j][$x]->Posibilities->Remove($this->cells[$y][$x]->Value);
		}
		
		private function RecalculateSquare($x, $y)
		{
			$xi = $x - ($x % 3);
			$yi = $y - ($y % 3);
            for ($i = $xi; $i < $xi + 3; $i++) for ($j = $yi; $j < $yi + 3; $j++)
                if ($i != $x && $j != $y && $this->cells[$j][$i]->Value == 0)
                    $this->cells[$j][$i]->Posibilities->Remove($this->cells[$y][$x]->Value);
		}
			
		public function ToString()
		{
			$output = "";
			for ($y = 0; $y < 9; $y++)
            {
                for ($x = 0; $x < 9; $x++)
                    $output .= $this->cells[$y][$x]->Value . ($x % 3 == 2 ? " | " : " ");
                $output .= "<br/>";
                if ($y % 3 == 2)
                    $output .= "-----------------------<br/>";
            }
			return $output;
		}
	}
?>