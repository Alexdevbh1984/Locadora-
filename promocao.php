<?php

  require_once("funcoes.php");
  
  require_once('db/conexao.php');
  $conexao = conexaoMysql();


?>

<!doctype html>
<html lang="pt-br">
    <head>
        <title>Promoções</title>
        <link rel="stylesheet" href="css/promocao.css" type="text/css">
        <link rel="stylesheet" href="css/modal_promocao.css" type="text/css">
        <link rel="stylesheet" href="css/menu.css" type="text/css">
        <link rel="stylesheet" href="css/itens_laterais.css" type="text/css">
        <link rel="stylesheet" href="css/rodape.css" type="text/css">
        <link rel="stylesheet" href="css/slider.css" type="text/css">
        <script src="js/jquery.js"></script>
        <script src="js/jquery-1.9.1.min.js"></script>
        <script src="js/jssor.slider.min.js"></script>
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
      <div class="caixa_imagem">
      
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
      
      
      </div>
        
        <!--SLIDER-->
        <div id="caixa_slider" class="center">
            <div id="slider" class="center">
                
                <!--LINK DO SLIDER-->
                <?php include("sliderPromocao.php");?>
                
                <!--REDES SOCIAS-->
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
                
                <!--ITENS DO LADO ESQUERDO-->
                <?php
                  
                  include("itens_laterais_promocao.php");
              
                ?>
                
                <!--PRODUTOS - FILMES -->
                <div id="caixa_produtos">
                    <?php
  
                      //SELECT QUE BUSCA PRODUTOS DA CATEGORIA QUE FAZ RELAÇÃO COM A PROMOÇÃO, CLASSIFICAÇÃO, ATOR, DIRETOR, GENERO, CATEGOPRIA E SUBCATEGORIA ONDE A CATEGORIA FOR CLICADA
                      if(isset($_GET['categoria'])){
                        
                        $cod_categoria = $_GET['cod_categoria'];
                        
                        $sql = "SELECT tbl_produto_filme.*, tbl_promocao.*, 
                                (SELECT round(tbl_produto_filme.preco-(tbl_produto_filme.preco*tbl_promocao.porcentagem/100),2)) AS preco_final
                                
                                FROM tbl_produto_filme INNER JOIN tbl_promocao
                                ON tbl_produto_filme.cod_produto_filme = tbl_promocao.cod_produto_filme

                                INNER JOIN tbl_classificacao
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
                                ON tbl_produto_filme.cod_produto_filme = tbl_subcategoria.cod_produto_filme

                                INNER JOIN tbl_categoria
                                ON tbl_subcategoria.cod_categoria = tbl_categoria.cod_categoria

                                INNER JOIN tbl_filme_diretor
                                ON tbl_filme_diretor.cod_produto_filme = tbl_produto_filme.cod_produto_filme

                                INNER JOIN tbl_diretor
                                ON tbl_filme_diretor.cod_diretor = tbl_diretor.cod_diretor

                                WHERE tbl_produto_filme.ativo_filme = 1 AND tbl_promocao.ativo = 1 AND tbl_categoria.cod_categoria=".$cod_categoria."
                                GROUP BY tbl_produto_filme.cod_produto_filme
                                ORDER BY tbl_produto_filme.nome_filme";
                        
                        
                      //SELECT QUE BUSCA PRODUTOS DA CATEGORIA QUE FAZ RELAÇÃO COM A PROMOÇÃO, CLASSIFICAÇÃO, ATOR, DIRETOR, GENERO, CATEGOPRIA E SUBCATEGORIA ONDE A SUBCATEGORIA DA CATEGORIA FOR CLICADA
                      }elseif(isset($_GET['subcategoria'])){
                  
                          $cod_genero = $_GET['cod_subcategoria'];
                          $categoria = $_GET['categoriaSub'];
                          
                        
                          $sql = "SELECT tbl_produto_filme.*, tbl_promocao.*, tbl_genero.*,
                                (SELECT round(tbl_produto_filme.preco-(tbl_produto_filme.preco*tbl_promocao.porcentagem/100),2)) AS preco_final
                                
                                FROM tbl_produto_filme INNER JOIN tbl_promocao
                                ON tbl_produto_filme.cod_produto_filme = tbl_promocao.cod_produto_filme

                                INNER JOIN tbl_classificacao
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
                                ON tbl_produto_filme.cod_produto_filme = tbl_subcategoria.cod_produto_filme

                                INNER JOIN tbl_categoria
                                ON tbl_subcategoria.cod_categoria = tbl_categoria.cod_categoria

                                INNER JOIN tbl_filme_diretor
                                ON tbl_filme_diretor.cod_produto_filme = tbl_produto_filme.cod_produto_filme

                                INNER JOIN tbl_diretor
                                ON tbl_filme_diretor.cod_diretor = tbl_diretor.cod_diretor

                                WHERE tbl_produto_filme.ativo_filme = 1 AND tbl_promocao.ativo = 1 AND tbl_subcategoria.cod_genero=".$cod_genero." AND tbl_subcategoria.cod_categoria = ".$categoria."
                                
                                GROUP BY tbl_produto_filme.cod_produto_filme
                                ORDER BY tbl_produto_filme.nome_filme";
                   
                  
                           //SELECT QUE FAZ A PESQUISA DE PRODUTOS DA CATEGORIA QUE FAZ RELAÇÃO COM A PROMOÇÃO, CLASSIFICAÇÃO, ATOR, DIRETOR, GENERO, CATEGOPRIA 
                      }elseif(isset($_POST['btnPesquisa'])){
                        
                          $pesquisa = $_POST['txt_pesquisa'];
                        
                        $sql = "SELECT tbl_produto_filme.*, tbl_promocao.*, tbl_categoria.*,
                                (SELECT round(tbl_produto_filme.preco-(tbl_produto_filme.preco*tbl_promocao.porcentagem/100),2)) AS preco_final
                                FROM tbl_produto_filme INNER JOIN tbl_promocao
                                ON tbl_produto_filme.cod_produto_filme = tbl_promocao.cod_produto_filme

                                INNER JOIN tbl_classificacao
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

                                WHERE tbl_produto_filme.ativo_filme = 1 AND tbl_promocao.ativo = 1 AND tbl_categoria.ativo = 1 AND tbl_produto_filme.nome_filme LIKE '%".$pesquisa."%' OR tbl_produto_filme.descricao LIKE '%".$pesquisa."%'
                                GROUP BY tbl_produto_filme.cod_produto_filme
                                ORDER BY tbl_produto_filme.nome_filme";
                        
                        /*$sql="SELECT * FROM vw_produtos_promocao_pesquisa WHERE nome_filme LIKE '%".$pesquisa."%' OR descricao LIKE '%".$pesquisa."%'";*/
                        
                        
                      }else{
                      
//                        SELECT QUE TRAS AS INFORMAÇÕES DE TODOS OS FILMES, E CASO NÃO TROUXER QUER DIZER QUE FALTA CADASTRA ALGO DO FILME
                        $sql = "SELECT tbl_produto_filme.*, tbl_promocao.*,tbl_categoria.*,
                                (SELECT round(tbl_produto_filme.preco-(tbl_produto_filme.preco*tbl_promocao.porcentagem/100),2)) AS preco_final
                                FROM tbl_produto_filme INNER JOIN tbl_promocao
                                ON tbl_produto_filme.cod_produto_filme = tbl_promocao.cod_produto_filme

                                INNER JOIN tbl_classificacao
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

                                WHERE tbl_produto_filme.ativo_filme = 1 AND tbl_promocao.ativo = 1 AND tbl_categoria.ativo = 1
                                GROUP BY tbl_produto_filme.cod_produto_filme
                                ORDER BY tbl_produto_filme.nome_filme;";
                        
                       /* $sql = "SELECT * FROM vw_produtos_promocao";*/
                                
                                
              
              
                      }
                  
                  
                  
                      $select = mysqli_query($conexao,$sql);
                  
                      while($rsfilmpromo = mysqli_fetch_array($select)){
                        
                        
                      $precoFilme = precoBrasileiro($rsfilmpromo['preco']);
                      $precoFinal = precoBrasileiro($rsfilmpromo['preco_final']);
                      $porcentagem = precoBrasileiro($rsfilmpromo['porcentagem']);
                        
                      $dt_nasc = dataBrasileiro($rsfilmpromo['lancamento']);
                        
                  
                    ?>
                    <div class="produtos">
                        
                        <!--INDICA PROMOCAO-->
                        <div class="promocao">
                            <span><?php echo($porcentagem."%")?></span>
                        </div>
                        
                        <!--FOTO DO PRODUTO-->
                        <div class="caixa_foto">
                            <figure class="foto center">
                              
                              
                                <img src="cms/arquivos/<?php echo($rsfilmpromo['foto'])?>" title="<?php echo($rsfilmpromo['nome_filme'])?>" alt="<?php echo($rsfilmpromo['nome_filme'])?>" class="imagem_foto">
                              
                              
                            </figure>
                        </div>
                        
                        <!--DETALHES DO PRODUTO-->
                        <div class="caixa_detalhes">

                            <div class="nome_detalhes">
                            DVD - <?php echo($rsfilmpromo['nome_filme'])?> </div>
                            <div class="antigo_detalhes">
                            de R$<?php echo($precoFilme)?></div>
                            <div class="final_detalhes">
                            por R$<?php echo($precoFinal)?></div>
                        </div>
                        
                        <!--BOTAO DETALHES-->
                        <div class="botao_detalhes center">
                          
                              <div class="titulo_detalhes" onclick="detalhesFilme(<?php echo($rsfilmpromo['cod_produto_filme'])?>, 'promocao')">
                                  Detalhes
                              </div>

                            <div id="imagem_favorito" class="imagem_favorito_close">

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