<?php

    require_once('db/conexao.php');
    $conexao = conexaoMysql();


?>

<!doctype html>
<html lang="pt-br">
    <head>
        <title>Sobre</title>
        <link rel="stylesheet" href="css/sobre.css" type="text/css">
        <link rel="stylesheet" href="css/menu.css" type="text/css">
        <link rel="stylesheet" href="css/rodape.css" type="text/css">
        <script src="js/jquery.js"></script>
        <script src="js/modal.js"></script>
      <meta charset="utf-8">
    </head>
    <body>
        
        <!--MENU-->
        <?php include("menu.php")?>
        
        <!--CONTEUDO 100%-->
        <div id="caixa_conteudo">
            
            <!--TITULO SOBRE-->
            <div  id="titulo" class="center">
              <h2>Sobre</h2>
            </div>
            <div id="conteudo" class="center">
                
                <!--REDES SOCIAIS-->
                <div id="caixa_redes">
                    <div class="facebook"></div>
                    <div class="insta"></div>
                    <div class="twitter"></div>
                </div>
                
                <!--SOBRER A EMPRESA-->
                <div id="sobre" class="center">
                    
                    <?php
                    
                        $sql = "SELECT * FROM tbl_sobre WHERE ativo = 1";
                        $select = mysqli_query($conexao,$sql);
        
                        while($rssobre = mysqli_fetch_array($select)){
                    
                    
                    ?>
                    
                    <!--SOBRE A HISTORIA-->
                    <div class="sobre_acme">
                        <div class="icone center">
                            <img src="cms/arquivos/<?php echo($rssobre['foto'])?>" alt="<?php echo($rssobre['titulo'])?>" title="<?php echo($rssobre['titulo'])?>" class="foto">
                        </div>
                        <div class="subtitulo">
                            <h3><?php echo(nl2br($rssobre['titulo']))?></h3>
                        </div>
                        <div class="info">
                            <p>
                                <?php echo(nl2br($rssobre['texto']))?>
                            </p>
                        </div>
                    </div>
                    <?php
                    
                        }
                    
                    ?>
                    
                </div>
                
            </div>
        </div>
        
        <!--RODAPE-->
        <?php require_once("rodape.php")?>
    </body>
</html>