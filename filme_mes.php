<?php

  require_once('funcoes.php');
  require_once('db/conexao.php');
  $conexao = conexaoMysql();

  $erro = null;

?>

<!doctype html>
<html lang="pt-br">
    <head>
        <title>Filme do Mês</title>
        <link rel="stylesheet" href="css/filme_mes.css" type="text/css">
        <link rel="stylesheet" href="css/menu.css" type="text/css">
        <link rel="stylesheet" href="css/rodape.css" type="text/css">
        <link rel="stylesheet" href="css/modal_filme_mes.css" type="text/css">
        <script src="js/jquery.js"></script>
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
        
        
        
        <!--conteudo 100%-->
        <div id="caixa_conteudo">
            
            <!--TITULO FILME DO MÊS-->
            <h2 id=titulo class="center">Filme do Mês</h2>
            
            <?php

              /*SELECT PARA TRAZER TODAS AS INFORMAÇÕES DO FILME (DIRETORES, ATORES, GENEROS, CLASSIFICAÇÃO, SINOPSE, TEMPO, LANÇAMENTO, NOME DO FILME)*/

              $sql = "SELECT COUNT(tbl_produto_filme.cod_produto_filme) AS quantidade, tbl_produto_filme.*, tbl_filme_diretor.*, 
                      GROUP_CONCAT(DISTINCT tbl_diretor.diretor SEPARATOR ', ' ) AS diretores, 
                      tbl_classificacao.*, GROUP_CONCAT(DISTINCT tbl_ator.nome_artistico SEPARATOR ', ') AS atores,
                      GROUP_CONCAT(DISTINCT tbl_genero.genero SEPARATOR ', ') AS generos

                      FROM tbl_produto_filme INNER JOIN tbl_filme_diretor
                      ON tbl_produto_filme.cod_produto_filme = tbl_filme_diretor.cod_produto_filme

                      INNER JOIN tbl_diretor
                      ON tbl_filme_diretor.cod_diretor = tbl_diretor.cod_diretor

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

                      WHERE tbl_produto_filme.ativo_filme_mes = 1";

              $select = mysqli_query($conexao,$sql);


                  if($rsfilme = mysqli_fetch_array($select)){

                    if($rsfilme['quantidade'] != 0){

                      $lancamento = dataBrasileiro($rsfilme['lancamento']);


            ?>
          
            <!--CONTEUDO COM TODAS AS INFORMACOES-->
            <div id="conteudo" class="center">
            
              <!--  REDES SOCIAIS-->
                <div id="caixa_redes">
                    <div class="facebook"></div>
                    <div class="insta"></div>
                    <div class="twitter"></div>
                </div>

                  <!--FOTO DO FILME-->
                  <div class="caixa_foto_filme">
                      <figure class="foto_filme">
                          <img src="cms/arquivos/<?php echo($rsfilme['foto'])?>" alt="<?php echo($rsfilme['nome_filme'])?>" title="<?php echo($rsfilme['nome_filme'])?>">

                      </figure>
                  </div>

                  <!--INFORMACOES PRINCIPAIS DO FILME-->
                  <div class="caixa_informacoes_filme">

                      <!--TITULO NOME DO FILME-->
                      <h4 class="nome_filme"><?php echo($rsfilme['nome_filme'])?></h4>

                      <!--TUDO SOBRE O FILME, LANCAMENTO, DURACAO, GENERO-->
                      <div class="sobre_filme">
                          <figure class="classificacao">
                              <img src="cms/arquivos/<?php echo($rsfilme['foto_classificacao'])?>" alt="<?php echo($rsfilme['classificacao'])?>" title="<?php echo($rsfilme['classificacao'])?>" class="foto_classificacao">
                          </figure>
                          <div class="data_lancamento"><span><?php echo($lancamento)?></span></div>
                          <div class="duracao_filme"><span><?php echo($rsfilme['tempo']." Minutos")?></span></div>
                          <div class="genero_filme"><span><?php echo($rsfilme['generos'])?></span></div>
                      </div>

                      <!--DIRETOR-->
                      <div class="diretor_filme">
                          <div class="texto_filme"><span>Diretor:</span></div>
                          <div class="nome_diretor"><span><?php echo($rsfilme['diretores'])?></span></div>
                      </div>

                      <!--ELENCO-->
                      <div class="elenco_filme">
                          <div class="texto_filme"><span>Elenco:</span></div>
                          <div ><span class="nome_elenco"><?php echo($rsfilme['atores'])?></span></div>
                      </div>

                      <!--SINOPSE-->
                      <div class="sinopse">
                          <p><?php echo(nl2br($rsfilme['descricao']))?></p>

                      </div>

                      <!--BOTAO DE DETALHES CORACAO PARA FAVORITAR-->
                      <div class="avaliacao">
                          <div class="detalhes_filme_mes center">

                              <div class="titulo_detalhes" onclick="detalhesFilme(<?php echo($rsfilme['cod_produto_filme'])?>, 'filme_mes')">
                                  Detalhes
                              </div>
                              <div class="imagem_favorito">

                              </div>
                          </div>
                      </div>
                  </div>
              
            </div>
            <?php
                  }/*else{

                    echo("<h1>Não se esqueça de colocar os gêneros, os diretores, os atores e a classificação do filme.</h1>");

                  }*/

                }


            ?>
        </div>
        
        <!--RODAPE-->
        <?php include("rodape.php")?>
    </body>
</html>