<!DOCTYPE html>
<html>
	<head>
		<title>Upload multiple images</title>
		<style>
			body
			{
				font-size: 16px;
				font-family: 'Arial';
				text-align: center;
			}
			
			form
			{
				width: 300px;
				margin-left: auto;
				margin-right: auto;
			}

			a 
			{
				text-decoration: none;
			}
			
			.header
			{
				right: 0;
				top: 0;
				width: 100%;
				position: fixed;
				float: left;
				background: -webkit-linear-gradient(top, rgba(38,152,227,1) 0%, rgba(255,255,255,1) 100%);
				padding-top: 1px;
				padding-bottom: 10px;
				margin-left: auto;
				margin-right: auto;
				background-color: #00A9D3;
			}
			
			.custom_input_file 
			{
				background-color: #941B80;
				color: #fff;
				cursor: pointer;
				font-weight: bold;
				margin: 0 auto 0;
				min-height: 15px;
				overflow: hidden;
				padding: 10px;
				position: relative;
				text-align: center;
				transition: .4s ease;
				width: auto;
			}
			
			.custom_input_file .input_file 
			{
				border: 10000px solid transparent;
				cursor: pointer;
				font-size: 10000px;
				margin: 0;
				opacity: 0;
				outline: 0 none;
				padding: 0;
				position: absolute;
				right: -1000px;
				top: -1000px;
			}			
			
			.image_library
			{			
				position: relative;
				top: 150px;
			}
			
			.container
			{
				position: relative;
				float: left;
				border: 1px solid #ddd;
				border-radius: 4px;
				padding: 5px;
				width: 24%;
				minimun-height: 50%;
			}
			
			.image 
			{
				vertical-align: middle;
				opacity: 1;
				display: block;
				width: 100%;
				height: auto;
				transition: .4s ease;
				backface-visibility: hidden;
			}

			.middle_button 
			{
				width: auto;
				background-color: #941B80;
				transition: .4s ease;
				opacity: 0;
				text-align: center;
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
			}
			
			.text
			{				
				color: white;
				font-size: 14px;
				padding: 8px 16px;
			}

			.custom_input_file:hover { background-color: #C023A6; }

			.middle_button:hover { background-color: #C023A6; }			

			.container:hover { box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5); }
			
			.container:hover .image { opacity: 0.65; }

			.container:hover .middle_button { opacity: 1; }
		</style>
	</head>
	<body>
		<div class="header">
			<h1> Image library </h1>
			<form id="uploadform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
				<div class="custom_input_file">
					Upload images...
				<!-- Para seleccionar varios ficheros hay que poner el modificador "multiple", pero para luego poder acceder a todos los ficheros desde el PHP hay que declarar el nombre del imput como array, por ello se le ponen los corchetes. -->
					<input type="file" name="images[]" class="input_file" onchange="InputFile_AutoSubmit()" multiple/>
				</div>
				<br/><br/>
			</form>
		</div>
		<script>
			function InputFile_AutoSubmit()
			{
				document.getElementById('uploadform').submit(); // Cuando el usuario selecciona las fotos automáticamente se le hace submit al form.
			}
		</script>
		</div>
		<div class="image_library">
		<?php
			if (isset($_FILES["images"]))
			{	
				$error = false;
				foreach ($_FILES["images"]["tmp_name"] as $file) // Recorro el array con todos los archivos.
					if (getimagesize($file) == false)			 // Si alguno no es una imagen se produce un "error".
						$error = true;
				
				if (!$error) // Si no se han producido "errores" proceso las imágenes.
				{
					$n_images = count($_FILES["images"]["tmp_name"]); // Guardo en nº de imágenes en una variable para liberar de carga al servidor al no tener que recalcularlo en cada iteración del for.
					
					if (!is_dir(".\\imagenes")) // Si no existe el directorio .\imagenes lo creo.
						mkdir(".\\imagenes");
					
					for ($i = 0; $i < $n_images; $i++) // Recorro todas las imagenes.
					{
						$target_file = ".\\imagenes\\" . basename($_FILES["images"]["name"][$i]); // Para mostrar las imágenes debo haberlas guardado previamente ya que la etiqueta <img> no las reconoce en .tmp
						if (!move_uploaded_file($_FILES["images"]["tmp_name"][$i], $target_file))  // Si se producen errores al copiar la imagen en mi servidor, la imagen a mostrar es una imagen de error.
							$target_file = "https://developers.google.com/maps/documentation/streetview/images/error-image-generic.png";							
						?>
						<div class="container">
							<img src="<?php echo $target_file; ?>" class="image">
							<div class="middle_button">
								<a href="<?php echo $target_file; ?>" target="_blank">
									<div class="text">Open in a new tab</div>
								</a>
							</div>
						</div>
						<?php
					}
				}
				else
					echo "Sorry, can not upload your files because there are no-image files."; // Si alguno de los archivos resulta que no es una imagen muestro un mensaje de error.
			}
		?>
		</div>
	</body>
</html>