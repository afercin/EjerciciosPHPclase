<!DOCTYPE html>
<html>
	<head>
		<title>Ficheros</title>
	</head>
	<body>
		<table>
		<?php
			function IsValidFile($file)
			{
				$pattern = "/^index|^\.{1,2}/i";
				return !preg_match($pattern, $file); 
			}
			
			$files = array();
			foreach (scandir(getcwd(), SCANDIR_SORT_ASCENDING) as $file)
				if (IsValidFile($file))
					$files[filemtime($file)] = $file;
				
			krsort($files, SORT_NUMERIC);
			
			foreach($files as $file)
			{
		?>
			<tr>
				<td><a href="<?php echo $file; ?>"><?php echo $file; ?></a></td>
			</tr>
		<?php
			}
		?>
		</table>
	</body>
</html>