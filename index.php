<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

    <!-- JQuery UI -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

    <!-- Estilos da livraria cropper.js -->
    <link rel="stylesheet" href="dist/cropper.min.css">


    <style type="text/css">

      .image-container {
        width: 100%;
        height: 500px;
      }

      /* Não mostrar o campo com o caminho do arquivo */
    	#image-selector {
    		display: none;
    	}

      /* Mostrar o indicador "carregando" no meio da tela */
      #loadingDiv {
        position: fixed;
        top: 0px;
        right: 0px;
        width: 100%;
        height: 100%;
        background-color: #666;
        background-image: url('ring-alt.gif');
        background-repeat: no-repeat;
        background-position: center;
        z-index: 10000000;
        opacity: 0.4;
        filter: alpha(opacity=40); /* For IE8 and earlier */
        display: none;
      }

      .saved-image {
        margin-top: 20px;
      }
    	
    </style>

  </head>
  <body>

  	<div class="container">
	    <h1>Redimensionar Imagem</h1>
	    <label class="btn btn-primary" for="image-selector">
	    	Selecionar Imagem
        <!-- Arquivo de Imagem -->
        <input id="image-selector" type="file" name="file">
	    </label>

      <div>
        <img id="image350x150" class="saved-image" src="images/image350x150.png">
      </div>

      <div>
        <img id="image285x122" class="saved-image" src="images/image285x122.png">
      </div>

	  </div>

    <!-- Popup com a imagem que será redimensionada -->
    <div class="modal fade" id="modal" aria-labelledby="modalLabel" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <!-- Formulario -->
          <form id="form" method="POST" action="actions.php/saveImage">
            <div class="modal-header">
              <h5 class="modal-title" id="modalLabel">Redimensionar a Imagem</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <div class="image-container">
                <!-- Imagem que é mostrada no popup para ser redimensionada -->
                <img id="image" name="image">
                <!-- Variável oculta utilizada que no formulario seja enviada a imagem redimensionada -->
                <input type="hidden" id="cropped-image" name="cropped-image">
              </div>
              <label class="btn">Zoom
                <span id="dataZoom">100</span>%
              </label>
              <!-- Slider para fazer zoom -->
              <div id="zoom-slider"></div>
            </div>
            <div class="modal-footer">
              <!-- Botão para gravar imagem -->
              <button type="submit" id="salvar-imagem" class="btn btn-primary">Salvar Imagem</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="loadingDiv"></div>

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <!-- Livraria cropper.js -->
    <script src="dist/cropper.min.js"></script>

    <script type="text/javascript">

      // Variáveis globais
    	var $image = $('#image');
    	var fileType;  // Tipo do Arquivo
    	var fileData;  // Conteúdo do Arquivo
      
      // Ocultar imagem de "carregando"
      $('#loadingDiv').hide();

      $(document)
        .ajaxStart(function () {
          $('#loadingDiv').show();
        })
        .ajaxStop(function () {
          $('#loadingDiv').hide();
        });

      // Carregar a imagem no popup e ativar a ferramenta para redimensionar
    	$('#image-selector').change(function(event) {

        // Tipos de arquivos permitidos
    		var imageTypes = ["image/gif", "image/jpeg", "image/png"];
    		fileData = this.files[0];
    		fileType = this.files[0].type;

        // Valida o formato de arquivo
    		if ($.inArray(fileType, imageTypes) > 0) {

	    		if (this.files[0]) {

		        	var reader = new FileReader();

			        reader.onload = function (e) {
                // Carrega a imagem no popup
			          $image.attr('src', e.target.result).hide();
					     };

			        reader.readAsDataURL(this.files[0]);

              // Mostra o popup
			        $('#modal').modal('show');
			    }

  			} else {

  				alert("Arquivo inválido!");

  			}
	
    	});

      // Evento executado quando o popup é mostrado na tela
    	$('#modal').on('shown.bs.modal', function() {
        // Mostra a imagem
        $image.show();
        // Ativa o plugin Cropper para redimensionar a imagem
        $image.cropper({
          viewMode: 0,
          dragMode: 'move',
          aspectRatio: 350 / 150,
          restore: false,
          guides: false,
          highlight: false,
          cropBoxMovable: false,
          cropBoxResizable: false,
          zoom: function(e){
                  // Valida zoom máximo e mínimo
                  if (e.ratio > 10 || e.ratio < 0.01) {
                    e.preventDefault();
                  } else {
                    // Atualiza texto com valor do zoom
                    var ratio = Math.round(e.ratio * 1000)/10;
                    $('#dataZoom').text(ratio);
                    console.log(typeof e.originalEvent);
                    // Atualiza o slider se o zoom foi feito com o mouse
                    if (typeof e.originalEvent !== "undefined") {
                      $('#zoom-slider').slider("value", e.ratio);
                    }
                  }
                },
          ready: function(e) {
            $image.cropper('zoomTo', 1);
          }
        });

      });

      // Controle do slider para fazer zoom
      $('#zoom-slider').slider({
        min: 0.01,    // Zoom mínimo: 1%
        max: 10,      // Zoom máximo: 1000%
        value: 1,     // Zoom inicial: 100%
        step: 0.01,
        slide: function( event, ui ) {
          if ($image.data('cropper')){
            // Atualiza zoom
            $image.cropper('zoomTo', ui.value);
          }
        }
      });

      // Evento executado quando o popup é fechado
      $('#modal').on('hidden.bs.modal', function() {
        // Inicializa o plugin Cropper
        $image.cropper('destroy');
        $('#image-selector').val("");
        $('#zoom-slider').slider("value", 1);
      });

      // Evento executado com o botão "salvar"
      $('#form').on('submit',(function(e) {
          e.preventDefault();

          // Dados do formulario
          var formData = new FormData(this);

          // Obter a imagem redimensionada para enviar ao servidor
          var canvasImage;
          if (fileType == "image/png") {
            canvasImage = $image.cropper('getCroppedCanvas');
          } else {
            // Se a imagem não for png, o fondo é enviado com a cor branca
            canvasImage = $image.cropper('getCroppedCanvas', {
              fillColor: '#fff'
            });
          }

          var croppedImage = canvasImage.toDataURL(fileType);

          // Enviar imagem redimensionada ao servidor
          formData.append('croppie', croppedImage);
          // Ação que será executada
          formData.append('action', "saveImage");
          // Executar o processo de envio ao servidor
          $.ajax({
              type:'POST',
              url: $('#form').attr('action'),
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){
                  console.log("success");
                  console.log(data);
                  // Se a imagem é carregada no servidor, atualiza as imagens mostradas na tela
                  $('#image350x150').prop('src', 'images/image350x150.png?' + Math.random());
                  $('#image285x122').prop('src', 'images/image285x122.png?' + Math.random());
                  $('#modal').modal('hide');
              },
              error: function(data){
                  console.log("error");
                  console.log(data);
              }
          });

      }));


    </script>

  </body>
</html>