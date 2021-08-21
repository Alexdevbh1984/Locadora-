<?php

    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }
  
    require_once('../db/conexao.php');
    $conexao = conexaoMysql();

    $img = "img/cancel.png";
    $legenda = "Desativado";

    if(isset($_GET['ativo_filme_mes'])){

    $id = $_GET['id'];
    $ativo = $_GET['ativo_filme_mes'];

    
    //SATIVAR O FILME DO MÊS
    if($ativo == 0){
      
      $sql = "UPDATE tbl_produto_filme SET ativo_filme_mes = 1 WHERE cod_produto_filme =".$id;
      
      mysqli_query($conexao,$sql);
      
      /*$sql = "SELECT * FROM tbl_produto_filme";
      
      $select = mysqli_query($conexao,$sql);
      
      
      while($rsativo = mysqli_fetch_array($select)){*/
        
        //DESATIVAR OS FILMES QUE FOREM DIFERENTES DO ID COM O ATIVO = 1
        $sql = "UPDATE tbl_produto_filme SET ativo_filme_mes = 0 WHERE cod_produto_filme <> ".$id;
        
        if(mysqli_query($conexao,$sql)){

          header('location:filme_mes.php');

       }else{

          echo("Erro");

        }
        
        
     /* }*/
      
    }elseif($ativo == 1){
      
      header('location:filme_mes.php');
    }
    
  }

?>

<!doctype html>

<html lang="pt-br">
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/filme_mes.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
        <script src="../js/valida.js"></script>
      <meta charset="utf-8">
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center">
                
                <?php include('cabecalho_menu_adm.php')?>
                
                
                <div class="itens">
                  
                    <!--MENU VERTICAL RELACIONADO AOS FILMES-->
                    <div id="menu_cadastro">
                      
                       
                        <a href="filme.php">
                            <div class="menu menu_filme">

                                 Filme

                            </div>
                        </a> 
                    


                        <a href="filme_mes.php">
                            <div class="menu menu_opcoes">

                                 Filme do mês

                            </div>
                        </a>  
                        
                    </div>
                  
                    <div class="cadastro">
                  
                        <div class="titulo_filme_mes"><h2>Cadastro de Filme do Mês</h2></div>
                    
                        <div class="tabela center">
                            <div class="linha_titulo">
                                <div class="titulo_form">
                                    <h4>Filmes</h4>
                                </div>
                                <div class="titulo_opcoes">
                                    <h4>Opções</h4>
                                </div>

                            </div>
                            <?php
                            
                              //SELECT QUE TRAS TODOS OS FILMES QUE TIVEREM ATIVOS
                              $sql = "SELECT * FROM tbl_produto_filme WHERE ativo_filme = 1";
                              $select = mysqli_query($conexao,$sql);


                              while($rsfilmes = mysqli_fetch_array($select)){

                                if($rsfilmes['ativo_filme_mes'] == 0){

                                  $img = "img/cancel.png";
                                  $legenda = "Desativado";

                                }elseif($rsfilmes['ativo_filme_mes'] == 1){

                                  $img = "img/ok.png";
                                  $legenda = "Ativado";

                                }

                            ?>

                            <div class="informacoes_form">
                                <div class="linha_informacao">
                                    <span><?php echo($rsfilmes['nome_filme'])?></span>
                                </div>
                                <div class="linha_botao">

                                    <figure class="desativar">
                                        <a href="filme_mes.php?ativo_filme_mes=<?php echo($rsfilmes['ativo_filme_mes'])?>&id=<?php echo($rsfilmes['cod_produto_filme'])?>">

                                            <img src="<?php echo($img)?>" alt="<?php echo($legenda)?>" title="<?php echo($legenda)?>" class="botao">
                                        </a>
                                    </figure>

                                </div>

                            </div>
                            <?php

                              }

                            ?>

                        </div>
                  
                    </div>
                    
                </div>
               
                <?php include('rodape.php')?>
                
            </div>
        </div>
    </body>
</html>