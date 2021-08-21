<?php

$soma = 0;

  //SELECT QUE TRAZ A QUANTIDADE TOTAL DE QUANTAS VEZES CADA PRODUTO FOI ACESSADO 
  $sql = "SELECT COUNT(tbl_estatistica.cod_produto_filme) AS quantidade, tbl_produto_filme.nome_filme
          FROM tbl_estatistica INNER JOIN tbl_produto_filme
          ON tbl_estatistica.cod_produto_filme = tbl_produto_filme.cod_produto_filme
          GROUP BY tbl_estatistica.cod_produto_filme ORDER BY quantidade DESC";
  $select = mysqli_query($conexao, $sql);

  while($rsesta = mysqli_fetch_array($select)){
    
   /* echo($rsesta['quantidade']."<br>");*/
    
    //SOMANDO A QUANTIDADE TOTAL DE PRODUTOS VISITADOS
    $soma = $soma + $rsesta['quantidade'];
    $nome_filme = $rsesta['nome_filme'];
    
    //echo("soma = ".$soma."<br>");
    
    //SELECT QUE TRAZ A QUANTIDADE TOTAL DE PRODUTOS VISITADOS
    $sql2 = "select count(*) AS registros from tbl_estatistica";
    $select2 = mysqli_query($conexao, $sql2);
    
    if($rsregistro = mysqli_fetch_array($select2)){
      
    //FAZENDO O CÁLCULO DE PORCENTAGEM DE UM PRODUTO BASEADO NO OUTRO  
    $porcentagem = number_format($rsesta['quantidade']/$rsregistro['registros']*100,2);
     /*echo("porcentagem = ".$porcentagem."<br>");*/
      
   
    //FAZENDO NUMERO ALEATORIOS  
    $aleatorio = rand(0,9);
    //ARRAY DE CORES PARA COLOCAR NOS GRÁFICOS
    $cores = array("#B90000","#003A4A","#9C3921","#147D76","#3E5E3B","#9ED970","#824248","#2E4F62","#563466","#8F714F");
     

?>


<!--AREA DOS GRAFICOS-->
<div class="graficos">
  
  <!--AREA DO NOME DO FILME-->
  <div class="nome_filme_estatistica">

    <?php echo($nome_filme)?>

  </div>
  
  <!--AREA DA ESTATÍSTICA-->
  <div class="grafico_estatistica">
    
    <!--GRAFICO DE BARRA QUE AUMENTA OU DIMINUI DE ACORDO COM AS VISUALIZAÇÕES DOS PRODUTOS-->
    <div class="estatistica" style="width:<?php echo($porcentagem)?>%; background-color: <?php echo($cores[$aleatorio])?>; color:#ffffff;"><?php echo($rsesta['quantidade'])?></div>
    
  </div>
  
  <!--AREA DA PROCENTAGEM QUE O PROSUTO FOI VISUALIZADO-->
  <div class="porcentagem">

    <?php echo(precoBrasileiro($porcentagem)."%")?>

  </div>
</div>

<?php
    
    }

  }


?>