<?php

  /*session_start();*/
    require_once('../funcoes.php');
    require_once('../db/conexao.php');
    
    $conexao = conexaoMysql();


    if(isset($_GET['cod_produto_filme'])){
      
        $codigo = $_GET['cod_produto_filme'];
      
        $sql = "INSERT INTO tbl_estatistica (cod_produto_filme, data_registro) VALUES (".$codigo.", (SELECT NOW()))";
    
        mysqli_query($conexao, $sql);
        
        //SELECT PARA TRAZER O PRODUTO DO FILME, A CLASSIFICAÇÃO, OS ATORES, OS DIRETORES, OS GENEROS ONDE O ATIVO DO FILME FOR IGUAL A 1(ATIVADO), O CODIGO DO FILME FOR IGUAL AO QUE PEDI, ORDENANDO AS INFORMAÇÕES PELO CODIGO DO PRODUTO
        $sql = "SELECT tbl_produto_filme.*,tbl_classificacao.*, 
                GROUP_CONCAT(DISTINCT tbl_ator.nome_artistico SEPARATOR ', ') AS atores,
                GROUP_CONCAT(DISTINCT tbl_diretor.diretor SEPARATOR ', ') AS diretores,
                GROUP_CONCAT(DISTINCT tbl_genero.genero SEPARATOR ', ') AS generos

                FROM tbl_produto_filme INNER JOIN tbl_classificacao
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

                WHERE tbl_produto_filme.ativo_filme = 1 AND tbl_produto_filme.cod_produto_filme = ".$codigo."
                GROUP BY tbl_produto_filme.cod_produto_filme";
      
      
      
          $select = mysqli_query($conexao,$sql);
          if($rsfilme = mysqli_fetch_array($select)){
            
            $foto_filme = $rsfilme['foto'];
            $nome_filme = $rsfilme['nome_filme'];
            $foto_classificacao = $rsfilme['foto_classificacao'];
            $classificacao = $rsfilme['classificacao'];
            $lancamento = dataBrasileiro($rsfilme['lancamento']);
            $tempo = $rsfilme['tempo'];
            $generos = $rsfilme['generos'];
            $atores = $rsfilme['atores'];
            $diretores = $rsfilme['diretores'];
            $sinopse = $rsfilme['descricao'];
            $preco = precoBrasileiro($rsfilme['preco']);
            
            
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
    
    <!--AREA COM AS INFORMAÇÕES DO FILME-->
    <div class="info_filme">
        
        <!--NOME DO FILME-->
        <h1 class="nome_modal center"><?php echo($nome_filme)?></h1>
        
        <!--CLASSIFICAÇÃO-->
        <div class="foto_class_modal"><img src="cms/arquivos/<?php echo($foto_classificacao)?>" alt="<?php echo($classificacao)?>" title="<?php echo($classificacao)?>" class="img_modal"></div>
        
        <!--LANÇAMENTO DO FILME-->
        <div class="data_modal"><?php echo($lancamento)?></div>
        
        <!--TEMPO DO FILME-->
        <div class="tempo_modal"><?php echo($tempo." Minutos")?></div>
        
        <div class="caixa_preco center">
            <?php echo("por R$".$preco)?>
        </div>

    </div>
</div>

<!--TITULO DE INFORMAÇÕES GERAIS-->
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