<?php
  
  //CHAMANDO O ARQUIVO DE FUNÇÕES
  require_once('../funcoes.php');

  /*FAZENDO A CONEXÃO*/
  require_once('../db/conexao.php');
  $conexao = conexaoMysql();


  if(isset($_GET['cod_produto_filme'])){
    
    $id = $_GET['cod_produto_filme'];
    
    /*SELECT QUE ESTÁ TRAZENDO REGISTROS COM A CLASSIFICAÇÃO, OS ATORES, DIRETORES, GENERO E AS INFORMAÇÕES DO FILME*/
    $sql = "SELECT tbl_produto_filme.*, tbl_classificacao.*, 
          GROUP_CONCAT(DISTINCT tbl_ator.nome_artistico SEPARATOR ', ') AS atores,
          GROUP_CONCAT(DISTINCT tbl_diretor.diretor SEPARATOR ', ') AS diretores,
          GROUP_CONCAT(DISTINCT tbl_genero.genero SEPARATOR ', ') AS generos

          FROM tbl_produto_filme INNER JOIN tbl_classificacao
          ON tbl_produto_filme.cod_classificacao = tbl_produto_filme.cod_classificacao

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

          WHERE tbl_produto_filme.cod_produto_filme =".$id."
          GROUP BY tbl_produto_filme.cod_produto_filme";
    
    $select = mysqli_query($conexao, $sql);
    
    if($rsmes = mysqli_fetch_array($select)){
      
      //VÁRIAVEIS QUE RECEBEM CADA CAMPO DO REGISTRO
      $nome_filme = $rsmes['nome_filme'];
      $foto_filme = $rsmes['foto'];
      $classificacao = $rsmes['classificacao'];
      $foto_classificacao = $rsmes['foto_classificacao'];
      $lancamento = dataBrasileiro($rsmes['lancamento']);
      $tempo = $rsmes['tempo'];
      $generos = $rsmes['generos'];
      $atores = $rsmes['atores'];
      $diretores = $rsmes['diretores'];
      $sinopse = $rsmes['descricao'];
      $preco = precoBrasileiro($rsmes['preco']);
      
      
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
  
    <!--AREA COM A FOTO-->
    <div class="foto_modal">

        <img src="cms/arquivos/<?php echo($foto_filme)?>" alt="<?php echo($nome_filme)?>" title="<?php echo($nome_filme)?>" class="img_modal">

    </div>

    <!--AREA COM AS INFORMAÇÕES DO FILME-->
    <div class="info_filme">
        
        <!--NOME DO FILME-->
        <h1 class="nome_modal center"><?php echo($nome_filme)?></h1>
        
        <!--CLASSIFICAÇÃO-->
        <div class="foto_class_modal"><img src="cms/arquivos/<?php echo($foto_classificacao)?>" alt="<?php echo($classificacao)?>" title="<?php echo($classificacao)?>" class="img_modal"></div>
        
        <!--LANÇAMENTO-->
        <div class="data_modal"><?php echo($lancamento)?></div>
        
        <!--DURAÇÃODO FILME-->
        <div class="tempo_modal"><?php echo($tempo." Minutos")?></div>
        
        <!--PREÇO-->
        <div class="caixa_preco center">
            <div class="preco_final center">
                <?php echo("por R$".$preco)?>
            </div>
        </div>

    </div>
</div>

<!--TITULO DAS INFORMAÇÕES GERAIS-->
<h2 class="titulo_modal center">Informações Gerais</h2>

<!--GENERO DO FILME-->
<div class="genero_modal center">
<span class="titulo_linha">Gênero(s):</span>
  <span class="texto_linha"><?php echo($generos)?></span>
    
</div>

<!--ATORES DO FILME-->
<div class="atores_modal center">
<span class="titulo_linha">Ator(es):</span>
    <span class="texto_linha"><?php echo($atores)?></span>
    
</div>

<!--DIRETORES DO FILME-->
<div class="diretor_modal center">
<span class="titulo_linha">Diretor(es):</span>
  <span class="texto_linha"><?php echo($diretores)?></span>
    
</div>

<!--AREA DA SINOPSE-->
<div class="info_sinopse_modal">
    
    <h2 class="titulo_modal center">Sinopse</h2>
    <div class="sinopse_modal center">
        <p>
            <?php echo(nl2br($sinopse))?>
        </p>
    
    </div>


</div>