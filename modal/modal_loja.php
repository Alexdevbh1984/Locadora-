<?php

  require_once('../db/conexao.php');
  require_once('../funcoes.php');

  $conexao = conexaoMysql();


  if(isset($_GET['cod_loja'])){
    
    $codigo = $_GET['cod_loja'];
    
    //SELECT QUE TRAZ AS LOJAS, AS CIDADES E OS ESTADOS
    $sql = "SELECT tbl_lojas.*, tbl_cidades.*, tbl_estados.* 
            FROM tbl_lojas INNER join tbl_cidades
            tbl_lojas.cod_cidade = tbl_cidades.cod_cidade
            INNER JOIN tbl_estados
            tbl_cidades.cod_estados = tbl_estados.cod_estado
            WHERE tbl_lojas.cod_ativo = 1 AND tbl_lojas.cos_loja =".$codigo;
    
    $select = mysqli_query($conexao,$sql);
    
    if($rsloja = mysqli_fetch-array($select)){
      
      $nome_loja = $rsloja['nome_loja'];
      $endereco = $rsloja['endereco'];
      $telefone = $rsloja['telefone'];
      $cidade = $rsloja['cidade'];
      $uf = $rsloja['uf'];
      
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