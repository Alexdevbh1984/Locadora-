<?php
require_once('../db/conexao.php');
    $conexao = conexaoMysql();

    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
    session_destroy();
    header('location:../index.php');
    
  }elseif($_SESSION['nivel'] == 3 || $_SESSION['nivel'] == 2){
      
  


?>

<!doctype html>

<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/adm_produtos.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
      <meta charset="utf-8">
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center clearfix">
                
              <!--AREA DO MENU ADM.-->
                <?php include('cabecalho_menu_adm.php')?>
                
                <div class="itens">
                  
                    <!--ICONE COM O LINK PARA CADASTRO DO PRODUTO-->
                    <div class="conteudo_itens">
                        <figure class="imagem_item">
                            <img src="img/house.png" alt="Home" title="Home" class="img">
                        </figure>
                        <div class="texto_item">
                            <a href="filme.php"><span class="link">Home</span></a>
                        </div>
                    </div>
                  
                    <!--ICONE COM O LINK PARA ESTATÍSTICAS DOS PRODUTOS-->
                    <div class="conteudo_itens">
                        <figure class="imagem_item">
                            <img src="img/estatistica.png" alt="Estatísticas" title="Estatísticas" class="img">
                        </figure>
                        <div class="texto_item">
                            <a href="estatistica_produto.php"><span class="link">Estatísticas</span></a>
                        </div>
                    </div>
                    
                    
                </div>
                
                <?php include('rodape.php')?>
                
            </div>
        </div>
    </body>
</html>

<?php

}else{
      
      header('location:index.php');
 }

?>