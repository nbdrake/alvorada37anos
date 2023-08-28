<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selo Alvorada - 37 Anos</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <style>
    @import url('https://fonts.cdnfonts.com/css/montserrat');
    </style>
</head>
<body>
    <div class="wrapper">
        <div>
            <p>
            Adicione o selo <b>#alvorada37anos</b> à sua foto de perfil do LinkedIn! <br> <br>
            São aceitas apenas imagens nos formatos JPG, JPEG e PNG. <br>
            Caso necessário converta o formato da sua imagem >>> <a href="https://convertio.co/pt/image-converter/" target="_blank">aqui</a> <<< <br>
            </p>
        </div>
        <div class="container">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                Selecione a imagem:
                <br>
                <br>
                <input type="file" name="fileToUpload" id="fileToUpload">
                <br>
                <br>
                <input type="submit" value="Enviar imagem" name="submit">
            </form>
        </div>
    </div>
    <footer>Alvorada Produtos Agropecuários - Todos os direitos Reservados - 2023 </footer>
</body>
</html>