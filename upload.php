<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "uploads/";  // Diretório onde as imagens serão armazenadas
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Desativa os avisos de warning temporariamente
    error_reporting(E_ALL & ~E_WARNING);


    // Verifica se algum arquivo foi enviado
        if (empty($_FILES["fileToUpload"]["name"]) || $_FILES["fileToUpload"]["size"] == 0) {
            echo "Desculpe, nenhum arquivo foi enviado. <br> <a href=\"javascript:history.go(-1)\">Voltar</a>";
            exit; // Encerra o script se nenhum arquivo foi enviado
        }

    // Verifica se o arquivo é uma imagem
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo " O arquivo não é uma imagem. <br> <a href=\"javascript:history.go(-1)\">Voltar</a>";
        exit; // Encerra o script se o arquivo não for uma imagem
    }

    // Verifica se o arquivo já existe
    if (file_exists($target_file)) {
        echo " Desculpe, o arquivo já existe, renomeie o arquivo para nova tentativa. <br> <a href=\"javascript:history.go(-1)\">Voltar</a>";
        exit; // Encerra o script se o arquivo já existir
    }

    // Verifica se o formato do arquivo é válido
    if ($_FILES["fileToUpload"]["size"] > 0) {
        $fileInfo = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        $mimeType = $fileInfo["mime"];

        if ($mimeType == "image/jpeg" || $mimeType == "image/jpg") {
            // A imagem é um JPEG válido, pode prosseguir com o carregamento
            $originalImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
        } elseif ($mimeType == "image/png") {
            // A imagem é um PNG válido, pode prosseguir com o carregamento
            $originalImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
        } else {
            echo " Arquivo em formato inválido, apenas arquivos JPG, JPEG e PNG são permitidos. <br> <a href=\"javascript:history.go(-1)\">Voltar</a> <br>";
            echo 'Converta seu arquivo clicando <a href="https://convertio.co/pt/image-converter/" target="_blank">aqui</a> para um dos formatos válidos e depois retorne para adicionar o selo';
            $uploadOk = 0;
        }

    // Verifique o tamanho do arquivo ultrapassa 1MB
    if ($_FILES["fileToUpload"]["size"] > 1000000) {
        echo " Desculpe, seu arquivo é muito grande, ele deve ter no máximo 1MB. <br> <a href=\"javascript:history.go(-1)\">Voltar</a>";
        exit; // Encerra o script se o arquivo maior que 1MB
    }

        //Início redimensionamento
        
        list($width, $height) = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

        // Definir $targetSize como a menor dimensão da imagem
        $targetSize = min($width, $height);

        // Carregar a imagem original
        $originalImage = imagecreatefromstring(file_get_contents($_FILES["fileToUpload"]["tmp_name"]));

        // Calcular as dimensões do redimensionamento
        $newWidth = $newHeight = $targetSize;

        if ($width > $height) {
            $cropX = intval(($width - $height) / 2);
            $cropY = 0;
        } else {
            $cropX = 0;
            $cropY = intval(($height - $width) / 2);
        }

        // Criar uma nova imagem com as dimensões desejadas
        $resizedImage = imagecreatetruecolor($targetSize, $targetSize);

        // Copiar e redimensionar a imagem original para a nova imagem
        imagecopyresampled($resizedImage, $originalImage, 0, 0, $cropX, $cropY, $targetSize, $targetSize, $newWidth, $newHeight);

        // Salvar a imagem redimensionada (substitua o caminho pelo seu diretório de destino)
        $outputPath = 'uploads/' . pathinfo($_FILES['fileToUpload']['name'], PATHINFO_FILENAME) . '-redimensionada.jpg';
        imagejpeg($resizedImage, $outputPath, 100); // 100 é a qualidade da imagem (de 0 a 100)


        //fim redimensionamento

    // Verifique se $uploadOk está definido como 0 por um erro
    if ($uploadOk == 0) {
        //echo "Desculpe, seu arquivo não foi enviado.";
    } else {
        if ($outputPath) {

            // Caminho para a imagem de moldura (frame.png)
            $framePath = "images/frame.png";

            // Carregar a imagem da moldura (frame.png)
            $frameImage = imagecreatefrompng($framePath);

            $baseImage = 0;

            // Carregar a imagem enviada
            if ($imageFileType == "jpg" || $imageFileType == "jpeg") {
                $baseImage = imagecreatefromjpeg($outputPath);

            } elseif ($imageFileType == "png") {
                $baseImage = imagecreatefromstring(file_get_contents($outputPath));
            } 

           // Obter as dimensões das imagens
           $baseWidth = imagesx($baseImage);
           $baseHeight = imagesy($baseImage);

           // Redimensionar a imagem da moldura para a mesma dimensão da imagem enviada
           $frameImageResized = imagescale($frameImage, $baseWidth, $baseHeight);

           // Calcular a posição para centralizar a moldura na imagem carregada
           $x = 0;
           $y = 0;

           // Combinar as imagens
           imagecopy($baseImage, $frameImageResized, $x, $y, 0, 0, $baseWidth, $baseHeight);

           // Construir o nome do arquivo final com um ID único por sessão
            $sessionId = session_id();
            $finalFileName = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_FILENAME);
            $finalFileName .= "-{$sessionId}";
            $finalFileName .= '-alvorada-37-anos.png';
            $output_file = 'outputs/' . $finalFileName;

           // Salvar a imagem resultante em um arquivo
            imagepng($baseImage, $output_file);

            // Liberar memória
            imagedestroy($baseImage);
            imagedestroy($frameImage);

            // Liberar a memória usada pelas imagens
            imagedestroy($originalImage);
            imagedestroy($resizedImage);

            // Exibir a imagem resultante e um link para a página de edição de perfil do LinkedIn

            echo '<link rel="stylesheet type="text/css" href="style.css">';
            echo '<link href="https://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">';
            echo '<link rel="icon" type="image/x-icon" href="images/favicon.ico">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';

            echo '<style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                    background-color: #4B7767;
                    background-image: url(images/logo.jpg);
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: center;
                }
                .container {
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-start; /* Alterado para alinhar no canto esquerdo */
                    align-items: flex-start; /* Alterado para alinhar no canto esquerdo */
                    background-color: aliceblue;
                    border-radius: 5px;
                    box-shadow: 7px 7px 13px 0px rgba(50, 50, 50, 0.22);
                    padding: 30px;
                    max-width: 400px;
                    width: 100%;
                    margin: 20px;
                }
                .wrapper {
                    text-align: center;
                    padding: 20px;
                    color: white;
                }
                img {
                    max-width: 100%;
                    height: auto;
                    border: none;
                }

                h1 {
                    color: black;
                    font-size: 30px;
                    text-align: center;
                    padding: 12px 12px 0px 17px;
                    margin-bottom: 0;
                }

                p {
                    color: black;
                    justify-content: center;
                }
                a {
                    text-decoration: none;
                    font-size: 20px;
                    font-weight: 650;
                }
                
                a:link, a:visited {
                    color: #789FD7;
                    transition: color 0.3s ease-out; /* Adicionado para suavizar a transição de cor */
                }
                
                a:hover {
                    background-color: #A5C4B5;
                    color: white; /* Altera a cor do texto para branco ao passar o mouse */
                    text-decoration: none;
                    border-radius: 12px;
                }
                b {
                    background-color: #4B7767;
                    text-transform: uppercase;
                    border: 0;
                    border-radius: 7px;
                    cursor: pointer;
                    box-shadow: 7px 7px 13px 0px rgba(50, 50, 50, 0.22);
                    font-size: 15px;
                    transition: all 0.2s ease-out;
                }
                @media (max-width: 768px) {
                    body {
                        background-size: contain;
                    }
                
                    .container {
                        max-width: 100%; /* Ocupa a largura completa */
                        margin: 10px; /* Reduz a margem para mais espaço */
                        padding: 10px; /* Reduz o padding para mais espaço */
                    }
                
                    h1 {
                        font-size: 24px; /* Reduz o tamanho da fonte */
                    }
            </style>';
            
            echo '<div class="container">';
            echo '<img src="' . $output_file . '" alt="Sua imagem com moldura" border="1">';
            echo '<div>';

            echo '<br>';
            echo '<h1>Alvorada - 37 Anos</h1>';

            echo '<br>';

            echo '<p><a href="' . $output_file . '" download>Baixe sua imagem aqui</a></p>';
            echo '<br>';
            echo '<p>Depois de baixar, <a href="https://www.linkedin.com/in/me/edit/photo/">clique aqui</a> para definir esta imagem como sua foto de perfil do LinkedIn.</p>';
            echo '</div>';
            echo '</div>';
            echo '<footer>Alvorada Produtos Agropecuários - Todos os direitos Reservados - 2023</footer>';
        } else {
            echo "Desculpe, ocorreu um erro ao fazer o upload do seu arquivo.";
        }
        
    }
}

}
?>