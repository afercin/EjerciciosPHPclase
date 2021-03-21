<?php
	class UnorderList implements Countable
	{
		private $arrayList;
		
		function __construct($array="") 
		{ 
			$this->arrayList = empty($array) ? array() : $array;
		}
		
		function Add($value)
		{
			if (!$this->Contains($value))
			{
				$this->arrayList[count($this->arrayList)] = $value;
			}
		}
		
		function Remove($value)
		{
			if ($this->Contains($value))
			{
				$temparray = array();
				$i = 0;
				foreach ($this->arrayList as $array_value)
					if ($array_value != $value)
						$temparray[$i++] = $array_value;
				$this->arrayList = $temparray;
			}
		}
		
		function Contains($value)
		{
			foreach ($this->arrayList as $array_value)
				if ($array_value == $value)
					return true;
			return false;
		}
		
		function First()
		{
			return $this->arrayList[0];
		}
		
		function GetArray()
		{
			return $this->arrayList;
		}
		
		public function count() 
		{
			return count($this->arrayList);
		}
	};
	
	class Point
	{
		public int $y;
		public int $x;
		
		function __construct($x = 0, $y = 0)
		{
			$this->x = $x;
			$this->y = $y;
		}
	};
?>