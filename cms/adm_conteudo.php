<?php
    
    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }elseif($_SESSION['nivel'] == 3 || $_SESSION['nivel'] == 4){

?>

<!doctype html>
<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/adm_conteudo.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
      <meta charset="utf-8">
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center clearfix">
                
                <?php include('cabecalho_menu_adm.php')?>
                
                <div class="itens">
                    
                    <!--AREA DA PROMOCAO-->
                    <div class="conteudo_itens">
                        <figure class="imagem_item">
                            <img src="img/discount.png" alt="Promoção" title="Promoção" class="img">
                        </figure>
                        <div class="texto_item">
                            <a href="promocao.php"><span class="link">Promoção</span></a>
                        </div>
                    </div>
                    
                    <!--AREA DO FILME DO MES-->
                    <div class="conteudo_itens">
                        <figure class="imagem_item">
                            <img src="img/hollywood-star.png" alt="Filme do Mês" title="Filme do Mês" class="img">
                        </figure>
                        <div class="texto_item">
                            <a href="filme_mes.php"><span class="link">Filme do Mês</span></a>
                        </div>
                    </div>
                    
                    <!--AREA DO ATOR DO MES-->
                    <div class="conteudo_itens">
                        <figure class="imagem_item">
                            <img src="img/actor.png" alt="Atores" title="Atores" class="img">
                        </figure>
                        <div class="texto_item">
                            <a href="ator.php"><span class="link">Atores</span></a>
                        </div>
                    </div>
                    
                    <!--AREA DAS LOJAS-->
                    <div class="conteudo_itens">
                        <figure class="imagem_item">
                            <img src="img/online-store.png" alt="Lojas" title="Lojas" class="img">
                        </figure>
                        <div class="texto_item">
                            <a href="lojas.php"><span class="link">Lojas</span></a>
                        </div>
                    </div>
                    
                    <!--AREA DO SOBRE-->
                    <div class="conteudo_itens">
                        <figure class="imagem_item">
                            <img src="img/iconfinder_about_2639759.png" alt="Sobre" title="Sobre" class="img">
                        </figure>
                        <div class="texto_item">
                            <a href="sobre_cms.php"><span class="link">Sobre</span></a>
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