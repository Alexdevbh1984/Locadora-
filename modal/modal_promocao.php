<?php

  /*session_start();*/
require_once("../funcoes.php");

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();

$nome_filme = null;

  if(isset($_GET['cod_produto_filme'])){
      
        $codigo = $_GET['cod_produto_filme'];
      
        $sql = "INSERT INTO tbl_estatistica (cod_produto_filme, data_registro) VALUES (".$codigo.", (SELECT NOW()))";
    
        mysqli_query($conexao, $sql);
    
        //SELECT QUE TRAZ O PROSUTO DO FILME, A PROMOCOÇÃO, O CALCULO DA PORCENTAGEM, OS ATORES, OS GENEROS, OS DIRETORES, CLASSIFICACAO, ONDE O PRODUTO ESTIVER ATIVADO E O CODIGO DO PRODUTO FOR IGUAL AO QUE PEDI, ORDENANDO AS INFORMAÇÕES PELO CODIGO DO PRODUTO
    
        $sql = "SELECT tbl_produto_filme.*, tbl_promocao.*,tbl_classificacao.*, 
                GROUP_CONCAT(DISTINCT tbl_ator.nome_artistico SEPARATOR ', ') AS atores,
                GROUP_CONCAT(DISTINCT tbl_diretor.diretor SEPARATOR ', ') AS diretores,
                GROUP_CONCAT(DISTINCT tbl_genero.genero SEPARATOR ', ') AS generos,
                (SELECT round(tbl_produto_filme.preco-(tbl_produto_filme.preco*tbl_promocao.porcentagem/100),2)) AS preco_final

                FROM tbl_produto_filme INNER JOIN tbl_promocao
                ON tbl_produto_filme.cod_produto_filme = tbl_promocao.cod_produto_filme

                INNER JOIN tbl_classificacao
                ON tbl_produto_filme.cod_classificacao = tbl_classificacao.cod_classificacao

                INNER JOIN tbl_filme_ator
                ON tbl_produto_filme.cod_produto_filme = tbl_filme_ator.cod_produto_filme

                INNER JOIN tbl_ator
                ON tbl_filme_ator.cod_ator = tbl_ator.cod_ator

                INNER JOIN tbl_filme_diretor
                ON tbl_produto_filme.cod_produto_filme = tbl_filme_diretor.cod_produto_filme

                INNER JOIN tbl_diretor
                ON tbl_filme_diretor.cod_diretor = tbl_diretor.cod_diretor

                INNER JOIN tbl_filme_genero
                ON tbl_produto_filme.cod_produto_filme = tbl_filme_genero.cod_produto_filme

                INNER JOIN tbl_genero
                ON tbl_filme_genero.cod_genero = tbl_genero.cod_genero

                WHERE tbl_produto_filme.ativo_filme = 1 AND tbl_promocao.ativo = 1 AND tbl_produto_filme.cod_produto_filme = ".$codigo."

                GROUP BY tbl_produto_filme.cod_produto_filme";
      
      
      $select = mysqli_query($conexao, $sql);
      
      if($rspromocao = mysqli_fetch_array($select)){
          
          $nome_filme = $rspromocao['nome_filme'];
          $atores = $rspromocao['atores'];
          $diretores = $rspromocao['diretores'];
          $lancamento = dataBrasileiro($rspromocao['lancamento']);
          $foto_classificacao = $rspromocao['foto_classificacao'];
          $classificacao = $rspromocao['classificacao'];
          $foto_filme = $rspromocao['foto'];
          $generos = $rspromocao['generos']; 
          $preco = precoBrasileiro($rspromocao['preco']); 
          $preco_final = precoBrasileiro($rspromocao['preco_final']); 
          $sinopse = $rspromocao['descricao']; 
          $tempo = $rspromocao['tempo'];
          $desconto = precoBrasileiro($rspromocao['porcentagem']);
      }
      
   
  }


?>

<!--FECHAR MODAL-->
<script>
        $(document).ready(function(){
            
            $('#icone_fechar').click(function(){
                $('#container').fadeToggle(540);
                 $("html,body").css({"overflow":"auto"});
            });
            
        })

    </script>


<div id="fechar">
    <div id="icone_fechar">
    </div>
</div>


<div class="area_filme center">
    <div class="foto_modal">

        <img src="cms/arquivos/<?php echo($foto_filme)?>" alt="<?php echo($nome_filme)?>" title="<?php echo($nome_filme)?>" class="img_modal">

    </div>

    <div class="info_filme">
        
        <!--NOME DO FILME-->
        <h1 class="nome_modal center"><?php echo($nome_filme)?></h1>
        
        <!--CLASSIFICAÇÃO-->
        <div class="foto_class_modal"><img src="cms/arquivos/<?php echo($foto_classificacao)?>" alt="<?php echo($classificacao)?>" title="<?php echo($classificacao)?>" class="img_modal"></div>
        
        <!--LANÇAMENTO-->
        <div class="data_modal"><?php echo($lancamento)?></div>
        
        <!--TEMPO-->
        <div class="tempo_modal"><?php echo($tempo." Minutos")?></div>
        
        <!--PREÇO-->
        <div class="caixa_preco center">
            <div class="preco_inicial">
                <?php echo($desconto."%")?>
            </div>
            <div class="preco_final">
                <?php echo("por R$".$preco_final)?>
            </div>
        
        </div>

    </div>
</div>


<!--INFORMAÇÕES GERAIS-->
<h2 class="titulo_modal center">Informações Gerais</h2>


<!--GENEROS-->
<div class="genero_modal center">
<span class="titulo_linha">Gênero(s):</span>
  <span class="texto_linha"><?php echo($generos)?></span>
    
</div>

<!--ATORES-->
<div class="atores_modal center">
<span class="titulo_linha">Ator(es):</span>
    <span class="texto_linha"><?php echo($atores)?></span>
    
</div>

<!--DIRETORES-->
<div class="diretor_modal center">
<span class="titulo_linha">Diretor(es):</span>
  <span class="texto_linha"><?php echo($diretores)?></span>
    
</div>

<!--SINOPSE-->
<div class="info_sinopse_modal">
    
    <h2 class="titulo_modal center">Sinopse</h2>
    <div class="sinopse_modal center">
        <p>
            <?php echo(nl2br($sinopse))?>
        </p>
    
    </div>


</div>
