<?php
    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();

    require_once('../funcoes.php');
   


?>

<!doctype html>
<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/estatistica_produto.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
        <script src="js/jquery.min.js"></script>
      <meta charset="utf-8">
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center">
                
                <?php include('cabecalho_menu_adm.php')?>
                
                
                <div class="itens center">
                    
                  <div class="titulo_lojas center"><h2>Produtos Mais Visitados</h2></div>
                
                    <div class="cadastro_lojas center">

                      <?php include('estatistica_calculo.php')?>
                      
                      
                      <!--AREA DO TOTAL DE PRODUTOS VISUALIZADOS-->
                      <div class="nome_filme_estatistica">

                        Total de Produtos Visitados

                      </div>
                      
                      <!--GRAFICO DO TOTAL DE PRODUTOS VISUALIZADOS-->
                      <div class="grafico_estatistica">
    
                        <div class="estatistica" style="width:100%; background-color: #03204f;color:#ffffff;"><?php echo($soma)?></div>

                      </div>
                      
                      <!--PROCENTAGEM TOTAL DE PRODUTOS VISUALIZADOS-->
                      <div class="porcentagem">100,00%</div>

                    </div>
                        
                    
                    
                </div>
               
                <?php include('rodape.php')?>
                
            </div>
        </div>
        

    </body>
</html>