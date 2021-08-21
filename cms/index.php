<?php
    require_once('../db/conexao.php');
    $conexao = conexaoMysql();

    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
    session_destroy();
    header('location:../index.php');
    
  }


?>

<!doctype html>
<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/cms.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
        <script src="js/funcoes.js"></script>
      <meta charset="utf-8">
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center">
                
                <!--AREA DE ADM.-->
                <?php include('cabecalho_menu_adm.php')?>
                
                <div class="itens">
                    
                </div>
                
                <!--RODAPÉ-->
                <?php include('rodape.php')?>
                
            </div>
        </div>
    </body>
</html>