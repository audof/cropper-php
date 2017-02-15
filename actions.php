<?php

	if(isset($_POST['action']) && !empty($_POST['action'])) {

	    $action = $_POST['action'];
	    switch($action) {
	        case 'saveImage':
	        	saveImage();
	        	break;
	    }
	}

	function saveImage() {

		// Nomes dos arquivos para as duas imagens
		$file350x150 = 'images/image350x150.png';
		$file285x122 = 'images/image285x122.png';
		$temp = 'images/temp.png';

		// Recupera informação enviada pelo formulário
        $data = $_POST['croppie'];

        // Decodifica a imagem
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        // Grava arquivo temporario (com resolução original)
        file_put_contents($temp, $data);
		
		// Tipo de conteúdo
		header('Content-Type: image/png');

		// Prepara o arquivo 350x150
		echo $type;
		switch ($type) {
			case 'data:image/jpeg':
				$imagetemp = imagecreatefromjpeg($temp);
				break;
			case 'data:image/gif':
				$imagetemp = imagecreatefromgif($temp);
				break;
			case 'data:image/png':
				$imagetemp = imagecreatefrompng($temp);
				break;
		}

		// Obtem a resolução do arquivo temporario
		list($width, $height) = getimagesize($temp);

		// Prepara os dados para copiar imagem com resolção 350x150
		$image = imagecreatetruecolor(350, 150);
		imagealphablending($image, false);
		imagesavealpha($image, true);

		// Criar imagem com resolução 350x150
		imagecopyresampled($image, $imagetemp, 0, 0, 0, 0, 350, 150, $width, $height);

		// Gravar primeira imagem
		imagepng($image, $file350x150);

		// Prepara os dados para copiar imagem com resolção 285x122
		$image2 = imagecreatetruecolor(285, 122);
		imagealphablending($image2, false);
		imagesavealpha($image2, true);

		// Criar imagem com resolução 285x122
		imagecopyresampled($image2, $imagetemp, 0, 0, 0, 0, 285, 122, $width, $height);

		// Gravar segunda imagem
		imagepng($image2, $file285x122);

		// Elimina arquivo temporario
		unlink($temp);

	}

?>