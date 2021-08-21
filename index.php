<?php

    require_once('db/conexao.php');
    $conexao = conexaoMysql();

    require_once('funcoes.php');
    require_once('estatistica.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Acme Tunes S.A.</title>
        
        
        <link rel="stylesheet" href="css/index.css" type="text/css">
        <link rel="stylesheet" href="css/menu.css" type="text/css">
        <link rel="stylesheet" href="css/itens_laterais.css" type="text/css">
        <link rel="stylesheet" href="css/rodape.css" type="text/css">
        <link rel="stylesheet" href="css/slider.css" type="text/css">
        <link rel="stylesheet" href="css/modal_home.css" type="text/css">
        <script src="js/jquery-1.9.1.min.js"></script>
        <script src="js/jssor.slider.min.js"></script>
        <script src="js/jquery.js"></script>
        <script src="js/slider.js"></script>
        <script src="js/modal.js"></script>
        <meta charset="utf-8">
    </head>
    <body>
        
        <!--MODAL-->
        <div id="container">
            <div id="modal" class="center">
                
            </div>
        </div>
        
        <!--MENU-->
        <?php include("menu.php")?>
        <?php
      
          $sql = "SELECT * FROM tbl_produto_filme WHERE imagem_slide IS NOT NULL ORDER BY RAND() LIMIT 1";
          $select = mysqli_query($conexao,$sql);
      
          if($rsslider = mysqli_fetch_array($select)){
        ?>
        <div class="imagemf">
            <img src="cms/arquivos/<?php echo($rsslider['imagem_slide'])?>" alt="<?php echo($rsslider['nome_filme'])?>" title="<?php echo($rsslider['nome_filme'])?>" class="imagem_mobile">
        
        </div>
        <?php
      
          }
        ?>
        
        <!--SLIDER-->
        <div id="caixa_slider" class="center">
            <div id="slider" class="center">
                
                <!--LINL DO SLIDER-->
                <?php include("sliderHome.php");?>
                
                <!--REDES SOCIAIS-->
                <div id="caixa_redes">
                    <div class="facebook"></div>
                    <div class="insta"></div>
                    <div class="twitter"></div>
                </div>
            </div>
            
        </div>
        
        <!--CONTEUDO 100%-->
        <div id="caixa_conteudo">
            
            <!--CONTEUDO COM TODAS AS INFORMACOES-->
            <div id="conteudo" class="center">
                
                <?php include('itens_laterais_index.php')?>
                
                <!--PRODUTOS-->
                <div id="caixa_produtos">
                    <?php
                        
                          //SELECT QUE BUSCA PRODUTOS DA CATEGORIA
                          if(isset($_GET['categoria'])){
                            
                            $cod_categoria = $_GET['cod_categoria'];
                            
                            //SELECT PARA TRAZER OS FILMES DA CATEGORIA QUE FOI CLICADA
                            $sql = "SELECT tbl_produto_filme.*, tbl_genero.*, tbl_categoria.cod_categoria 
                                    FROM tbl_produto_filme INNER JOIN tbl_subcategoria
                                    ON tbl_produto_filme.cod_produto_filme = tbl_subcategoria.cod_produto_filme

                                    INNER JOIN tbl_categoria
                                    ON tbl_subcategoria.cod_categoria = tbl_categoria.cod_categoria

                                    INNER JOIN tbl_genero
                                    ON tbl_subcategoria.cod_genero = tbl_genero.cod_genero

                                    INNER JOIN tbl_classificacao
                                    ON tbl_produto_filme.cod_classificacao = tbl_classificacao.cod_classificacao

                                    INNER JOIN tbl_filme_ator
                                    ON tbl_produto_filme.cod_produto_filme = tbl_filme_ator.cod_produto_filme

                                    INNER JOIN tbl_ator
                                    ON tbl_filme_ator.cod_ator = tbl_ator.cod_ator

                                    INNER JOIN tbl_filme_genero
                                    ON tbl_produto_filme.cod_produto_filme = tbl_filme_genero.cod_produto_filme

                                    INNER JOIN tbl_filme_diretor
                                    ON tbl_filme_diretor.cod_produto_filme = tbl_produto_filme.cod_produto_filme

                                    INNER JOIN tbl_diretor
                                    ON tbl_filme_diretor.cod_diretor = tbl_diretor.cod_diretor

                                    WHERE tbl_produto_filme.ativo_filme = 1 AND tbl_categoria.cod_categoria=".$cod_categoria."
                                    GROUP BY tbl_produto_filme.cod_produto_filme
                                    ORDER BY RAND()";
                            
                           
                           //SELECT QUE BUSCA PRODUTOS DA SUBCATEGORIA DE UMA CATEGORIA QUE FOR CLICADA
                          }elseif(isset($_GET['subcategoria'])){
                                  
                              $cod_genero = $_GET['cod_subcategoria'];
                              $categoriaSub = $_GET['categoriaSub'];
                            
                              //SELECT QUE TRAZ OS FILMES DA SUBCATEGORIA DA CATEGORIA
                              $sql = "SELECT tbl_produto_filme.*, tbl_genero.*,   
                                      tbl_subcategoria.* 
                                      FROM tbl_produto_filme INNER JOIN tbl_subcategoria
                                      ON tbl_produto_filme.cod_produto_filme = tbl_subcategoria.cod_produto_filme

                                      INNER JOIN tbl_categoria
                                      ON tbl_subcategoria.cod_categoria = tbl_categoria.cod_categoria

                                      INNER JOIN tbl_genero
                                      ON tbl_subcategoria.cod_genero = tbl_genero.cod_genero

                                      INNER JOIN tbl_classificacao
                                      ON tbl_produto_filme.cod_classificacao = tbl_classificacao.cod_classificacao

                                      INNER JOIN tbl_filme_ator
                                      ON tbl_produto_filme.cod_produto_filme = tbl_filme_ator.cod_produto_filme

                                      INNER JOIN tbl_ator
                                      ON tbl_filme_ator.cod_ator = tbl_ator.cod_ator

                                      INNER JOIN tbl_filme_genero
                                      ON tbl_produto_filme.cod_produto_filme = tbl_filme_genero.cod_produto_filme

                                      INNER JOIN tbl_filme_diretor
                                      ON tbl_filme_diretor.cod_produto_filme = tbl_produto_filme.cod_produto_filme

                                      INNER JOIN tbl_diretor
                                      ON tbl_filme_diretor.cod_diretor = tbl_diretor.cod_diretor

                                      WHERE tbl_produto_filme.ativo_filme = 1 AND tbl_subcategoria.cod_genero=".$cod_genero." AND tbl_subcategoria.cod_categoria=".$categoriaSub."
                                      GROUP BY tbl_produto_filme.cod_produto_filme
                                      ORDER BY RAND()";
                            
                            
                          }elseif(isset($_POST['btnPesquisa'])){
                            $pesquisa = $_POST['txt_pesquisa'];
                            
                                //SELECT QUE TRAZ OS FILMES QUE ESTÃO SENDO PESQUISADOS
                                $sql = "SELECT tbl_produto_filme.*, tbl_categoria.*
                                
                                    FROM tbl_produto_filme INNER JOIN tbl_classificacao
                                    ON tbl_produto_filme.cod_classificacao = tbl_classificacao.cod_classificacao

                                    INNER JOIN tbl_filme_ator
                                    ON tbl_produto_filme.cod_produto_filme = tbl_filme_ator.cod_produto_filme

                                    INNER JOIN tbl_ator
                                    ON tbl_filme_ator.cod_ator = tbl_ator.cod_ator

                                    INNER JOIN tbl_filme_genero
                                    ON tbl_produto_filme.cod_produto_filme = tbl_filme_genero.cod_produto_filme

                                    INNER JOIN tbl_genero
                                    ON tbl_filme_genero.cod_genero = tbl_genero.cod_genero
                                    
                                    INNER JOIN tbl_subcategoria
                                    ON tbl_subcategoria.cod_produto_filme = tbl_produto_filme.cod_produto_filme
                                    
                                    INNER JOIN tbl_categoria
                                    ON tbl_subcategoria.cod_categoria = tbl_categoria.cod_categoria
                                    
                                    INNER JOIN tbl_filme_diretor
                                    ON tbl_filme_diretor.cod_produto_filme = tbl_produto_filme.cod_produto_filme

                                    INNER JOIN tbl_diretor
                                    ON tbl_filme_diretor.cod_diretor = tbl_diretor.cod_diretor
                                
                                    WHERE tbl_produto_filme.ativo_filme = 1 AND tbl_categoria.ativo = 1 AND tbl_produto_filme.nome_filme LIKE '%".$pesquisa."%' OR tbl_produto_filme.descricao LIKE '%".$pesquisa."%' 
                                    GROUP BY tbl_produto_filme.cod_produto_filme";
                            
                           /* $sql = "SELECT * FROM vw_produtos_pesquisa WHERE nome_filme LIKE '%".$pesquisa."%' OR descricao LIKE '%".$pesquisa."%'";*/
                            
                               
                            
                          }else{
                            
                            //SELECT QUE TRAS TODAS AS INFORMAÇÕES DO FILME, E SE DER ALGUM ERRO ELE NÃO MOSTRA NO SITE, POR ISSO FOI TRAGO TODAS AS INFORMAÇÕES PARA VERIFICAR
                              $sql = "SELECT tbl_produto_filme.*, tbl_categoria.*, tbl_genero.*
                                    FROM tbl_produto_filme INNER JOIN tbl_classificacao
                                    ON tbl_produto_filme.cod_classificacao = tbl_classificacao.cod_classificacao

                                    INNER JOIN tbl_filme_ator
                                    ON tbl_produto_filme.cod_produto_filme = tbl_filme_ator.cod_produto_filme

                                    INNER JOIN tbl_ator
                                    ON tbl_filme_ator.cod_ator = tbl_ator.cod_ator

                                    INNER JOIN tbl_filme_genero
                                    ON tbl_produto_filme.cod_produto_filme = tbl_filme_genero.cod_produto_filme

                                    INNER JOIN tbl_genero
                                    ON tbl_filme_genero.cod_genero = tbl_genero.cod_genero

                                    INNER JOIN tbl_filme_diretor
                                    ON tbl_filme_diretor.cod_produto_filme = tbl_produto_filme.cod_produto_filme

                                    INNER JOIN tbl_diretor
                                    ON tbl_filme_diretor.cod_diretor = tbl_diretor.cod_diretor
                                    
									INNER JOIN tbl_subcategoria
                                    ON tbl_subcategoria.cod_produto_filme = tbl_produto_filme.cod_produto_filme
                                    
                                    INNER JOIN tbl_categoria
                                    ON tbl_subcategoria.cod_categoria = tbl_categoria.cod_categoria
                                    
                                    WHERE tbl_produto_filme.ativo_filme = 1 AND tbl_categoria.ativo = 1
                                    GROUP BY tbl_produto_filme.cod_produto_filme
                                    ORDER BY RAND()";
                            
                            /*$sql = "SELECT * FROM vw_produtos_home";*/
                            
                  
                            
                          }  
  
  
                    
                        $select = mysqli_query($conexao,$sql);
                        while($rsfilme = mysqli_fetch_array($select)){
                            
                            $preco = precoBrasileiro($rsfilme['preco']);
                          
                        
                    ?>
                    
                    <div class="produtos">
                        
                        <!--FOTO DO PRODUTO-->
                        <div class="caixa_foto">
                            <figure class="foto center" >
                                <img title="<?php echo($rsfilme['nome_filme'])?>" alt="<?php echo($rsfilme['nome_filme'])?>" src="cms/arquivos/<?php echo($rsfilme['foto'])?>" class="imagem_foto">
                            </figure>
                        </div>
                        
                        <!--DETALHES DO PRODUTO-->
                        <div class="caixa_detalhes">
                            
                            <!--NOME DO PRODUTO-->
                            <div class="nome">
                                <!--<span class="detalhes_nome">Nome:</span>-->
                                <div class="detalhes_texto_nome center"><?php echo($rsfilme['nome_filme'])?></div>
                            </div>
                            
                            <!--DESCRICAO DO PRODUTO-->
                            <div class="descricao">
                                <!--<span class="detalhes_descricao">Descrição:</span>-->
                                <div class="detalhes_texto_descricao center"><?php echo($rsfilme['descricao'])?></div>
                            </div>
                            
                            <!--PRECO DO PRODUTO-->
                            <div class="preco">
                                <!--<span class="detalhes_preco">Preço:</span>-->
                                <div class="detalhes_texto_preco center"><?php echo("R$".$preco)?></div>
                            </div>
                            
                            <!--ALINHAMENTO DO BOTAO ETALHES-->
                            <div class="alinhamento_detalhes">
                                <div class="detalhes" onclick="detalhesFilme(<?php echo($rsfilme['cod_produto_filme'])?>,'home')">
                                <span>Detalhes</span>
                            
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    
                        }
                    ?>
                    
                </div>
            </div>
        </div>
        
        <!--RODAPE-->
        <?php include("rodape.php")?>
        
    </body>
</html>