<?php
	require "Utils.php";
	
	class SudokuCell
	{
		public $Value;
		public $Posibilities;
		
		function __construct($value, $posibilities="") 
		{
			$this->Value = $value;
			if (empty($posibilities))
				$this->Posibilities = new UnorderList($value == 0 ? array(1,2,3,4,5,6,7,8,9) : array());
			else
				$this->Posibilities = new UnorderList($posibilities);
		}
	}
?>